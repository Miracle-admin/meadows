<?php

/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	23 March 2012
 * @file name	:	controllers/project.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.controller');

class JblanceControllerProject extends JControllerLegacy {

    function __construct() {
        parent :: __construct();
    }

    function appsaveProject() {
        $user = JFactory::getUser();

        //if user is guest create profile
        if ($user->guest) {
            $this->saveProjectregistration();
        } else {
            //simply save the new project
            $this->saveProjectnew();
        }
    }

    function saveProject() {
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialize variables
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $user = JFactory::getUser();
        $row = JTable::getInstance('project', 'Table');
        $post = $app->input->post->getArray();
        $id = $app->input->get('pid', 0, 'int');
        ;
        $jbmail = JblanceHelper::get('helper.email');  // create an instance of the class EmailHelper
        $isNew = false;
        $totalAmount = $app->input->get('totalamount', 0, 'float');

        $config = JblanceHelper::getConfig();
        $reviewProjects = $config->reviewProjects;

        //load the project value if the project is 'edit'
        if ($id > 0)
            $row->load($id);
        else
            $isNew = true; // this is a new project

        $post['publisher_userid'] = $user->id;
        $post['description'] = $app->input->get('description', '', 'RAW');

        $id_category = $app->input->get('id_category', '', 'array');
        if (count($id_category) > 0 && !(count($id_category) == 1 && empty($id_category[0]))) {
            $proj_categ = implode(',', $id_category);
        } elseif ($id_category[0] == 0) {
            $proj_categ = 0;
        }
        $post['id_category'] = $proj_categ;

        if ($post['project_type'] == 'COM_JBLANCE_FIXED') {
            $budgetRange = explode('-', $post['budgetrange_fixed']);
        } elseif ($post['project_type'] == 'COM_JBLANCE_HOURLY') {
            $budgetRange = explode('-', $post['budgetrange_hourly']);
        }
        $post['budgetmin'] = $budgetRange[0];
        $post['budgetmax'] = $budgetRange[1];

        //save the commitment value
        $commitment = $app->input->get('commitment', null, 'array');
        $registry = new JRegistry();
        $registry->loadArray($commitment);
        $post['commitment'] = $registry->toString();

        //save the params value
        $params = $app->input->get('params', null, 'array');
        $registry = new JRegistry();
        $registry->loadArray($params);
        $post['params'] = $registry->toString();

        if ($isNew) {
            $now = JFactory::getDate();
            $post['create_date'] = $now->toSql();
            $post['status'] = 'COM_JBLANCE_OPEN';

            //check if the project is to be reviewed by admin. If so, set the approved=0
            if ($reviewProjects) {
                $post['approved'] = 0;
            }

            // deduce `charge per project` if amount is > 0
            $plan = JblanceHelper::whichPlan($user->id);
            $chargePerProject = $plan->buyChargePerProject;

            if ($chargePerProject > 0) {
                $transDtl = JText::_('COM_JBLANCE_CHARGE_PER_PROJECT') . ' - ' . $post['project_title'];
                JblanceHelper::updateTransaction($user->id, $transDtl, $chargePerProject, -1);
                $msg_debit = JText::sprintf('COM_JBLANCE_YOUR_ACCOUNT_DEBITED_WITH_CURRENCY_FOR_POSTING_PROJECT', JblanceHelper::formatCurrency($chargePerProject));
                $app->enqueueMessage($msg_debit);
            }
        }

        // deduce the amount from user's account
        if ($totalAmount > 0) {
            //check if the user has enough fund to promote project
            $totalFund = JblanceHelper::getTotalFund($user->id);
            if ($totalFund < $totalAmount) {
                $msg = JText::_('COM_JBLANCE_BALANCE_INSUFFICIENT_TO_PROMOTE_PROJECT');

                $id_link = '';
                if ($post['pid'] > 0)
                    $id_link = '&pid=' . $post['id'];

                $link = JRoute::_('index.php?option=com_jblance&view=project&layout=editproject' . $id_link, false);
                $this->setRedirect($link, $msg, 'error');
                return false;
            }
            else {
                $post['profit_additional'] = $row->profit_additional + $post['totalamount'];
                $transDtl = JText::_('COM_JBLANCE_PROJECT_PROMOTION_FEE_FOR') . ' - ' . $post['project_title'];
                JblanceHelper::updateTransaction($user->id, $transDtl, $totalAmount, -1);
            }
        }

        if (!$row->save($post)) {
            JError::raiseError(500, $row->getError());
        }

        //save the custom field value for project
        $fields = JblanceHelper::get('helper.fields');  // create an instance of the class fieldsHelper
        $fields->saveFieldValues('project', $row->id, $post);

        JBMediaHelper::uploadFile($post, $row);  //remove and upload files
        //update the project posting limit in the plan subscription table
        if ($isNew) {
            $finance = JblanceHelper::get('helper.finance');  // create an instance of the class FinanceHelper
            $finance->updateProjectLeft($user->id);
        }

        //Trigger the plugin event to feed the activity - buyer pick freelancer
        JPluginHelper::importPlugin('joombri');
        $dispatcher = JDispatcher::getInstance();
        $dispatcher->trigger('onProjectAfterSave', array($row, $isNew));

        //send email to admin if the project needs review (only for new project)
        if ($isNew) {
            if ($reviewProjects)
                $jbmail->sendAdminProjectPendingApproval($row->id);
        }

        // if the project in "private invite, send him to freelancer selection page else send notification"
        if ($post['is_private_invite']) {
            $msg = JText::_('COM_JBLANCE_PROJECT_SAVED_SUCCESSFULLY') . ' : ' . $row->project_title;
            $return = JRoute::_('index.php?option=com_jblance&view=project&layout=inviteuser&pid=' . $row->id, false);
            $this->setRedirect($return, $msg);
        } else {
            //send new project notification if the project doesn't need review
            if (!$reviewProjects)
                $jbmail->sendNewProjectNotification($row->id, $isNew);

            $msg = JText::_('COM_JBLANCE_PROJECT_SAVED_SUCCESSFULLY') . ' : ' . $row->project_title;
            $return = JRoute::_('index.php?option=com_jblance&view=project&layout=showmyproject', false);
            $this->setRedirect($return, $msg);
        }
    }

    //save new project


    private function saveProjectnew() {

        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialize variables
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $user = JFactory::getUser();
        $jbProject = $row = JTable::getInstance('project', 'Table');
        if (JRequest::getInt('pid') != 0)
            $jbProject->load(JRequest::getInt('pid'));
        $post = $app->input->post->getArray();
        $id = $app->input->get('pid', 0, 'int');

        //  echo '<pre>'; print_r($post); die;
        $post = $app->input->post->getArray();
        $jbmail = JblanceHelper::get('helper.email');
        $totalAmount = $app->input->get('totalamount', 0, 'float');
        $config = JblanceHelper::getConfig();
        $plan = JTable::getInstance('plan', 'Table');
        $plan = JblanceHelper::whichPlan($user->id);


        $planParams = json_decode($plan->params);
        $chargePerProject = $planParams->buyChargePerProject;
        $totalFund = JblanceHelper::getTotalFund($user->id);
        $paymentMode = $plan->paymentmode;
        $model = $this->getModel('project');
        $upgrades = array('buyFeePerUrgentProject', 'buyFeePerPrivateProject', 'buyFeePerFeaturedProject', 'buyFeePerAssistedProject');
        $availablePjs = array('buyFeePerUrgentProject' => 'is_urgent', 'buyFeePerPrivateProject' => 'is_private', 'buyFeePerFeaturedProject' => 'is_featured', 'buyFeePerAssistedProject' => 'is_assisted', 'buyFeePerSealedProject' => 'is_sealed');
        $transaction = JTable::getInstance('transaction', 'Table');


        $upsellAmmount = 0;
        $upsellPurchased = array();
        foreach ($_POST as $k => $value) {
            if (in_array($k, $upgrades)) {
                $upsellPurchased[$planParams->$k] = $k;
                $upsellAmmount+=$planParams->$k;
            }
        }

        $upsellCount = count($upsellPurchased);


        //calculate lat long

        if (!empty($post['pj_city'])) {
            $latLong = $post['pj_city'];
        } elseif (!empty($post['pj_state'])) {
            $latLong = $post['pj_state'];
        } else {
            $latLong = $post['pj_count'];
        }

        //validate the project
        $model->validateProject($post, false);

        //if user has not suffficient funds for posting a project redirect back
        if ($chargePerProject > 0) {
            if ($totalFund < $chargePerProject) {
                $app->redirect(JRoute::_("index.php?option=com_jblance&view=project&layout=editprojectcustom&Itemid=337"), "You do not have sufficient funds to post this project, Please deposit funds in your account", "error");
            }
        }


        $jbProject->project_title = $post['pj_title'];
        $jbProject->id_category = $post['pj_platform'] . ',' . $post['dev_type'];
        $jbProject->start_date = date('Y-m-d H:i:s');
        $jbProject->create_date = date('Y-m-d H:i:s');
        $jbProject->expires = $post['pj_expires'];
        $jbProject->publisher_userid = $user->id;
        $jbProject->status = "COM_JBLANCE_OPEN";
        $jbProject->budgetmin = explode(',', $post['pj_budget']);
        $jbProject->budgetmin = $jbProject->budgetmin[0];
        $jbProject->budgetmax = explode(',', $post['pj_budget']);
        $jbProject->budgetmax = $jbProject->budgetmax[1];
        $jbProject->description = $post['pz_desc'];
        if ($row->id)
            $jbProject->approved = 1;
        else
            $jbProject->approved = 0;
        $jbProject->id_location = $latLong;
        //if project posted successfully
        if ($jbProject->store()) {


            //Trigger the plugin event to feed the activity - buyer pick freelancer
            JPluginHelper::importPlugin('joombri');
            $dispatcher = JDispatcher::getInstance();
            //$dispatcher->trigger('onProfileProgress', array(30, $user->id));
            //notify administrator and user
            $jbmail->sendAdminProjectPendingApproval($jbProject->id);
            //send notification to user
            $jbmail->sendNewProjectNotification($jbProject->id, true);



            //clear user state
            foreach ($post as $ukey => $uvalue) {
                $app->setUserState($ukey, null);
            }

            //upload any attached files
            $projfile = JTable::getInstance('projectfile', 'Table');
            $allowedExts = explode(',', $config->projectFileText);
            $allowedMime = explode(',', $config->projectFileType);
            for ($i = 0; $i < $post['uploadLimit']; $i++) {
                $file = $_FILES['uploadFile' . $i];

                if ($file['size'] > 0) {
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $mime = $file['type'];
                    if (!in_array($ext, $allowedExts) && !in_array($mime, $allowedMime)) {
                        $app->enqueueMessage('File type ' . $file['name'] . ' is not allowed , you can update you project later');
                        continue;
                    } else {
                        JBMediaHelper::uploadEachFile($file, $jbProject, $projfile);
                    }
                } // end of file size
            }

            //if project posting is paid charge the user
            if ($chargePerProject > 0) {
                $transDtl = JText::_('COM_JBLANCE_CHARGE_PER_PROJECT') . ' - ' . $post['pj_title'];
                JblanceHelper::updateTransaction($user->id, $transDtl, $chargePerProject, -1);
            }

            //if user has purchased any project upgrades charge via the assigned payment mode.
            if ($upsellCount > 0) {
                if ($paymentMode == 1) {
                    //if mode is deposited funds 
                    //again calculate the total funds if user was charged for project posting
                    $newtotalFund = JblanceHelper::getTotalFund($user->id);

                    if ($newtotalFund > $upsellAmmount) {
                        $project = JTable::getInstance('project', 'Table');
                        $project->load($jbProject->id);

                        foreach ($upsellPurchased as $pjname) {
                            $project->$availablePjs[$pjname] = 1;
                        }
                        //store the project
                        if ($project->store()) {

                            $transDtl = JText::_("Buy Project Upgrades(Deposited fund)");
                            JblanceHelper::updateTransaction($user->id, $transDtl, $upsellAmmount, -1);
                        }
                    } else {
                        $app->enqueueMessage('You do not have sufficient funds to upgrade this project');
                    }
                } else {
                    //invoke the appropriate payment gateway
                    $app->setUserState('pjId', $jbProject->id);
                    $app->setUserState('upsells', $upsellPurchased);
                    $app->setUserState('upammount', $upsellAmmount);
                    $app->redirect(JRoute::_('index.php?option=com_jblance&task=project.processuppayment'), 'Please complete your payment', 'message');
                }
            }
        }

//index.php?option=com_jblance&view=project&layout=detailproject&id=285
        if ($row->id) {
            $redirect = JRoute::_(JUri::base() . "index.php?option=com_jblance&view=project&layout=detailproject&pid=" . $jbProject->id);
        } else {
            $redirect = JRoute::_(JUri::base() . "index.php?option=com_jblance&task=project.projectdashboard&pid=" . $jbProject->id);
        }
        $app->redirect($redirect, 'Project successfully added');
    }

    //save project with registration
    private function saveProjectregistration() {
        $options = array('action' => 'core.login.site',
            'remember' => '',
            'return' => '',
            'entry_url' => ''
        );
        jimport('joomla.user.helper');
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        //initialize the required variables
        $app = JFactory::getApplication();
        $model = $this->getModel('project');


        //get the post vars
        $post = $app->input->post->getArray();



        //validate the submitted data
        $model->validateProject($post, true);

        if (!empty($post['pj_city'])) {
            $latLong = $post['pj_city'];
        } elseif (!empty($post['pj_state'])) {
            $latLong = $post['pj_state'];
        } else {
            $latLong = $post['pj_count'];
        }

        //process form
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $user = JFactory::getUser();
        $row = JTable::getInstance('project', 'Table');

        $app = JFactory::getApplication();
        $jbmail = JblanceHelper::get('helper.email');
        $jbConfig = JblanceHelper::getConfig();
        //first create user
        $config = & JFactory::getConfig();
        $authorize = & JFactory::getACL();
        $document = & JFactory::getDocument();
        //get user config
        $usersConfig = &JComponentHelper::getParams('com_users');

        //halt the default process of user registration
        $newUsertype = $usersConfig->get('new_usertype');

        //prepare user params

        $username = explode('@', $post['u_email']);
        $username = $username[0];
        $name = !empty($post['u_name']) ? $post['u_name'] : $username;
        //generate a 8 digit temp password
        $password = $this->random_password();
        $email = $post['u_email'];

        $bindArray = array("name" => $name, "password" => $password, "password2" => $password, "username" => $username, "email" => $email);

        $user->bind($bindArray);


        $user->set('groups', array(2, 11));
        $user->set('gid', array(2, 11));

        $user->set('activation', '');
        $user->set('block', 0);
        $user->set('guest', 0);
        $user->set('lastvisitDate', date("y:m:d h:i:s"));

        $user->set('emailvalid', md5(JUserHelper::genRandomPassword()));
        $User = new stdClass();
        $User->newUsername = $user->username;
        $User->newPassword = $user->password_clear;
        $User->activationLink = $user->emailvalid;
        $User->email = $user->email;
        $user->set('password_clear', '');

        //save joomla user
        if ($user->save()) {
            $credentials = array('username' => $username, 'password' => $password);
            jimport('joomla.user.authentication');
            JPluginHelper::importPlugin('user');
            $authenticate = JAuthentication::getInstance();
            $response = $authenticate->authenticate($credentials, $options);
            if ($response->status === JAuthentication::STATUS_SUCCESS) {
                $dispatcher = JEventDispatcher::getInstance();
                $results = $dispatcher->trigger('onUserLogin', array((array) $response, $options));
            }

            $jbLocation = JTable::getInstance('location', 'Table');
            $jbLocation->load($latLong);
            $locParams = json_decode($jbLocation->params);
            $jbUser = JTable::getInstance('jbuser', 'Table');
            $jbUser->biz_name = $user->name;
            $jbUser->user_id = $user->id;
            //$jbUser->name       = $name;
            $jbUser->jbname = $name;
            $jbUser->ug_id = 3;
            $jbUser->latitude = $locParams->latitude;
            $jbUser->longitude = $locParams->longitude;
            //get any bonus to the user
            $planRow = JTable::getInstance('plan', 'Table');
            $planRow->load(5);
            $bonus = $planRow->bonusFund;
            if ($jbUser->store()) {
                //
                //give the bonus to user
                if ($bonus != 0) {
                    $jbTransact = JTable::getInstance('transaction', 'Table');
                    $jbTransact->date_trans = date("y:m:d h:i:s");
                    $jbTransact->transaction = "Buy Subscription - Company";
                    $jbTransact->user_id = $user->id;
                    $jbTransact->fund_plus = $bonus;
                    $jbTransact->fund_minus = 0;
                    $jbTransact->store();

                    //subscribe company subscription
                    $this->saveSub($user);
                }
            }
            //post the actual project now
            $jbProject = JTable::getInstance('project', 'Table');

            $jbProject->project_title = $post['pj_title'];
            $jbProject->id_category = $post['pj_platform'] . ',' . $post['dev_type'];
            $jbProject->start_date = date('Y-m-d H:i:s');
            $jbProject->create_date = date('Y-m-d H:i:s');
            $jbProject->expires = $post['pj_expires'];
            $jbProject->publisher_userid = $user->id;
            $jbProject->status = "COM_JBLANCE_OPEN";
            $jbProject->budgetmin = explode(',', $post['pj_budget']);
            $jbProject->budgetmin = $jbProject->budgetmin[0];
            $jbProject->budgetmax = explode(',', $post['pj_budget']);
            $jbProject->budgetmax = $jbProject->budgetmax[1];
            $jbProject->description = $post['pz_desc'];
            $jbProject->approved = 0;
            $jbProject->id_location = $latLong;

            if ($jbProject->store()) {
                JPluginHelper::importPlugin('joombri');
                $dispatcher = JDispatcher::getInstance();
                // $dispatcher = JEventDispatcher::getInstance();
               // $dispatcher->trigger('onProfileProgress', array(30, $user->id));
                //insert the user to notify table
                $obj = new stdClass();
                $obj->user_id = $user->id;
                $db->insertObject('#__jblance_notify', $obj);

                //clear user state
                foreach ($post as $ukey => $uvalue) {
                    $app->setUserState($ukey, null);
                }
                //send notification to user
                $jbmail->sendNewProjectRegistrationNotificationUser($jbProject->id, $User);

                //send notification to admin
                $jbmail->sendNewProjectRegistrationNotificationAdmin($jbProject->id, $User);
                //jblance clear userstate
                //Trigger the plugin event to feed the activity - buyer pick freelancer

                $dispatcher->trigger('onProjectAfterSave', array($jbProject, true));
                //upload any attached files
                $projfile = JTable::getInstance('projectfile', 'Table');
                $allowedExts = explode(',', $jbConfig->projectFileText);
                $allowedMime = explode(',', $jbConfig->projectFileType);
                for ($i = 0; $i < $post['uploadLimit']; $i++) {
                    $file = $_FILES['uploadFile' . $i];

                    if ($file['size'] > 0) {
                        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                        $mime = $file['type'];
                        if (!in_array($ext, $allowedExts) && !in_array($mime, $allowedMime)) {
                            $app->enqueueMessage('File type ' . $file['name'] . ' is not allowed , you can update you project later');
                            continue;
                        } else {
                            JBMediaHelper::uploadEachFile($file, $jbProject, $projfile);
                        }
                    } // end of file size
                }




                //check for any upgrades purchased
                $planRow = JTable::getInstance('plan', 'Table');
                $planRow->load(5);
                $planParams = json_decode($planRow->params);
                $upgrades = array('buyFeePerUrgentProject', 'buyFeePerPrivateProject', 'buyFeePerFeaturedProject', 'buyFeePerAssistedProject');
                $upsellAmmount = 0;
                $upsellPurchased = array();
                foreach ($_POST as $k => $value) {
                    if (in_array($k, $upgrades)) {
                        $upsellPurchased[$planParams->$k] = $k;
                        $upsellAmmount+=$planParams->$k;
                    }
                }

                if (count($upsellPurchased)) {
                    $app->setUserState('pjId', $jbProject->id);
                    $app->setUserState('upsells', $upsellPurchased);
                    $app->setUserState('upammount', $upsellAmmount);
                    $app->redirect(JRoute::_('index.php?option=com_jblance&task=project.processuppayment'), 'Please complete your payment', 'message');
                }
            }

            //redirect else
            $app->redirect(JRoute::_("index.php?option=com_jblance&task=project.projectdashboard&pid=" . $jbProject->id), 'Project successfully added', 'message');
        }
    }

    function processuppayment() {
        $app = JFactory::getapplication();
        $pjId = $app->getUserState('pjId');
        $pjSells = $app->getUserState('upsells');
        $pjAmmount = $app->getUserState('upammount');
        JPluginHelper::importPlugin('appmeadows');
        $dispatcher = JEventDispatcher::getInstance();
        $parameters = array(&$pjId, &$pjSells, &$pjAmmount);

        $dispatcher->trigger('onUpgradePurchased', $parameters);
    }

    //project dashboard functionality
    function projectDashboard() {

        $model = $this->getModel('project');
        $dash = $model->getProjectdashboard();
        // echo '<pre>'; print_r($dash);

        $step = $dash['step'];
        $id = JRequest::getInt('pid');
        if (!$id) {
            $id = JRequest::getInt('pid');
        }
        $app = JFactory::getApplication();
        $root = JUri::root();
        $db = JFactory::getDbo();
        $query = "SELECT * FROM #__jblance_bid WHERE project_id=" . $id . " AND status='COM_JBLANCE_ACCEPTED'";
        $db->setQuery($query);
        $bid = $db->loadAssoc();
        $bId = $bid['id'];

        $query = "SELECT id FROM #__jblance_rating WHERE project_id=" . $id;

        $db->setQuery($query);

        $rat = $db->loadAssoc();

        $routes = array("1" => $root . "index.php?option=com_jblance&view=project&layout=projectdashboard&pid=" . $id . "&Itemid=340", "2" => $root . "index.php?option=com_jblance&view=project&layout=pickuser&pid=" . $id . "&Itemid=308", "3" => $root . "index.php?option=com_jblance&view=project&layout=agreement&pid=" . $id, "4" => $root . "index.php?option=com_jblance&view=project&layout=projectprogress&bid=" . $bId . "&Itemid=308&pid=" . $id, "5" => $root . "index.php?option=com_jblance&view=project&layout=rateuser&id=" . $rat['id'] . "&Itemid=308&pid=" . $id);
        $route = $routes[$step];
        $app->redirect($route);
    }

    function saveBid() {
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        $jbmail = JblanceHelper::get('helper.email');  // create an instance of the class EmailHelper
        $projhelp = JblanceHelper::get('helper.project');  // create an instance of the class ProjectHelper
        $finance = JblanceHelper::get('helper.finance');  // create an instance of the class FinanceHelper
        $user = JFactory::getUser();
        $post = $app->input->post->getArray();
        $id = $app->input->get('pid', 0, 'int');
        $row = JTable::getInstance('bid', 'Table');
        $rowp = JTable::getInstance('project', 'Table');
        $message = JTable::getInstance('message', 'Table');
        $project_id = $app->input->get('project_id', 0, 'int');
        $proj_detail = $projhelp->getProjectDetails($project_id);
        $isNew = false;
        $now = JFactory::getDate();
        $config = JblanceHelper::getConfig();

        if ($id > 0)
            $row->load($id);
        else
            $isNew = true;

        // if the placed bid is new, check for bids left
        $lastSubscr = $finance->getLastSubscription($user->id);
        if ($isNew && ($lastSubscr->bids_allowed > 0 && $lastSubscr->bids_left <= 0)) {
            $msg = JText::sprintf('COM_JBLANCE_NOT_ALLOWED_TO_BID_PROJECT_LIMIT_EXCEEDED');
            $link = JRoute::_('index.php?option=com_jblance&view=membership&layout=planadd', false);
            $this->setRedirect($link, $msg, 'error');
            return false;
        }

        $post['user_id'] = $user->id;
        $post['outbid'] = $app->input->get('outbid', 0, 'int');

        if ($isNew) {
            $post['bid_date'] = $now->toSql();

            // deduce `charge per bid` if amount is > 0
            $plan = JblanceHelper::whichPlan($user->id);
            $chargePerBid = $plan->flChargePerBid;

            if ($chargePerBid > 0) {
                $transDtl = JText::_('COM_JBLANCE_CHARGE_PER_BID') . ' - ' . $proj_detail->project_title;
                JblanceHelper::updateTransaction($user->id, $transDtl, $chargePerBid, -1);
                $msg_debit = JText::sprintf('COM_JBLANCE_YOUR_ACCOUNT_DEBITED_WITH_CURRENCY_FOR_BIDDING_PROJECT', JblanceHelper::formatCurrency($chargePerBid));
                $app->enqueueMessage($msg_debit);
            }
        }

        //if the freelancer has denied the bid and is editing, reset the status. Edit option is available if the project is reopened by buyer.
        $row->status = '';



        if (!$row->save($post)) {
            JError::raiseError(500, $row->getError());
        }
        //increment the bid count
        $project_id = $post['project_id'];
        $rowp->load($project_id);
        $bidInc = $rowp->bid_count;
        $bidInc++;
        $rowp->bid_count = $bidInc;
        $rowp->store();

        //if the project requires NDA and not signed
        $is_nda_signed = $app->input->get('is_nda_signed', 0, 'int');
        if ($proj_detail->is_nda && $is_nda_signed) {
            jbimport('pdflib.fpdf');

            if (!file_exists(JBBIDNDA_PATH)) {
                if (mkdir(JBBIDNDA_PATH)) {
                    JPath::setPermissions(JBBIDNDA_PATH, '0777');
                    if (file_exists(JPATH_SITE . '/images/index.html')) {
                        copy(JPATH_SITE . '/images/index.html', JBBIDNDA_PATH . '/index.html');
                    }
                }
            }

            $new_doc = "nda_" . $row->id . "_" . $proj_detail->id . "_" . strtotime("now") . ".pdf"; //file name format: nda_BIDID_PROJECTID_time.pdf
            $dest = JBBIDNDA_PATH . '/' . $new_doc;

            //replace texts in the file
            $dformat = $config->dateFormat;
            $ndaFile = JPATH_COMPONENT . '/images/nda.txt';
            $ndaText = file_get_contents($ndaFile);

            $siteURL = JURI::root();
            $projectName = $proj_detail->project_title;
            $startDate = JHtml::_('date', $proj_detail->start_date, $dformat . ' H:i:s', false);
            $bidderName = JFactory::getUser($row->user_id)->name;
            $bidDate = JHtml::_('date', $row->bid_date, $dformat . ' H:i:s', false);
            $publisherName = JFactory::getUser($proj_detail->publisher_userid)->name;

            $tags = array("[SITEURL]", "[PROJECTNAME]", "[STARTDATE]", "[BIDDERNAME]", "[BIDDATE]", "[PUBLISHERNAME]");
            $tagsValues = array("$siteURL", "$projectName", "$startDate", "$bidderName", "$bidDate", "$publisherName");

            $ndaText = str_replace($tags, $tagsValues, $ndaText);

            $pdf = new PDF();
            $title = JText::_('COM_JBLANCE_NON_DISCLOSURE_AGREEMENT');
            $pdf->SetTitle($title);

            $pdf->PrintChapter($ndaText);
            $pdf->Output($dest, 'F');

            $row->attachment = $title . ';' . $new_doc;
        }

        // save the changes after updating nda
        if (!$row->store()) {
            JError::raiseError(500, $row->getError());
        }
        $row->checkin();

        //update the project posting limit in the plan subscription table
        if ($isNew) {
            $finance = JblanceHelper::get('helper.finance');  // create an instance of the class FinanceHelper
            $finance->updateBidsLeft($user->id);
        }

        //save and send PM
        $send_pm = $app->input->get('sendpm', 0, 'int');
        if ($send_pm) {
            //save the file attachment `if` checked
            $chkAttach = $app->input->get('chk-uploadmessage', 0, 'int');
            $attachedFile = $app->input->get('attached-file-uploadmessage', '', 'string');

            if ($chkAttach) {
                $post['attachment'] = $attachedFile;
            } else {
                $attFile = explode(';', $attachedFile);
                $filename = $attFile[1];
                $delete = JBMESSAGE_PATH . '/' . $filename;
                if (JFile::exists($delete))
                    unlink($delete);
            }

            $post['date_sent'] = $now->toSql();
            $post['idFrom'] = $user->id;
            $post['idTo'] = $proj_detail->publisher_userid;
            $post['type'] = 'COM_JBLANCE_PROJECT';

            //check if messages to be moderated
            $reviewMessages = $config->reviewMessages;
            if ($reviewMessages)
                $post['approved'] = 0;

            if (!$message->save($post)) {
                JError::raiseError(500, $message->getError());
            }

            //if message does not require moderation, send PM notification email to recipient else send to admin for approval
            if ($reviewMessages)
                $jbmail->sendAdminMessagePendingApproval($message->id);
            else
                $jbmail->sendMessageNotification($message->id);
        }

        //send mail to publisher
        /* if($isNew){
          $jbmail->sendNewBidNotification($row->id, $project_id);
          } */


        $jbmail->sendNewBidNotification($row->id, $project_id, $isNew);

        //	die(' --- getMailer test ---');
        //send out bid notification to bidders with higher amount
        $jbmail->sendOutBidNotification($row->id, $project_id);

        //Trigger the plugin event to feed the activity - buyer pick freelancer
        JPluginHelper::importPlugin('joombri');
        $dispatcher = JDispatcher::getInstance();
        $dispatcher->trigger('onBidAfterSave', array($row, $proj_detail->publisher_userid, $isNew));

        $msg = ($isNew) ? JText::_('COM_JBLANCE_BID_PLACED_SUCCESSFULLY') : JText::_('COM_JBLANCE_BID_EDITED_SUCCESSFULLY');
        $msg .= ' - ' . $proj_detail->project_title;
        $return = JRoute::_('index.php?option=com_jblance&view=project&layout=showmybid', false);
        $this->setRedirect($return, $msg);
    }

    function savePickUser() {
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialize variables
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $id = $app->input->get('pid', 0, 'int'); //proj id
        $row = JTable::getInstance('project', 'Table');
        $post = $app->input->post->getArray();

        $row->load($id);
        $row->status = 'COM_JBLANCE_FROZEN';

        if (!$row->save($post)) {
            JError::raiseError(500, $row->getError());
        }
        JPluginHelper::importPlugin('joombri');
        $dispatcher = JDispatcher::getInstance();
        //$dispatcher->trigger('onProfileProgress', array(50, $row->publisher_userid));

        //send bid won notification to user
        $jbmail = JblanceHelper::get('helper.email');  // create an instance of the class EmailHelper
        $jbmail->sendBidWonNotification($id);

        //Trigger the plugin event to feed the activity - buyer pick freelancer
        JPluginHelper::importPlugin('joombri');
        $dispatcher = JDispatcher::getInstance();
        $dispatcher->trigger('onBuyerPickFreelancer', array($row->publisher_userid, $row->assigned_userid, $row->id));

        $msg = JText::_('COM_JBLANCE_USER_PICKED_SUCCESSFULLY');
        $return = JRoute::_('index.php?option=com_jblance&view=project&layout=showmyproject', false);
        $this->setRedirect($return, $msg);
    }

    function reopenProject() {
        // Check for request forgeries
        JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        $id = $app->input->get('pid', 0, 'int');

        //update status project
        $project = JTable::getInstance('project', 'Table');
        $project->id = $id;
        $project->status = 'COM_JBLANCE_OPEN';
        $project->assigned_userid = 0;

        if (!$project->check())
            JError::raiseError(500, $project->getError());

        if (!$project->store())
            JError::raiseError(500, $project->getError());

        $project->checkin();

        $msg = JText::_('COM_JBLANCE_PROJECT_REOPENED_SUCCESSFULLY');
        $return = JRoute::_('index.php?option=com_jblance&view=project&layout=showmyproject', false);
        $this->setRedirect($return, $msg);
    }

    function removeProject() {
        // Check for request forgeries
        JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $user = JFactory::getUser();
        $id = $app->input->get('pid', 0, 'int');

        $query = "SELECT COUNT(*) FROM #__jblance_project WHERE id=" . $db->quote($id) . " AND publisher_userid=" . $db->quote($user->id);
        $db->setQuery($query);
        if ($db->loadResult() > 0) {
            $queries = array();
            $queries[] = "DELETE FROM #__jblance_project WHERE id=" . $db->quote($id) . " AND publisher_userid=" . $db->quote($user->id);
            $queries[] = "DELETE FROM #__jblance_bid WHERE project_id=" . $db->quote($id);
            foreach ($queries as $query) {
                $db->setQuery($query);
                $db->execute();
            }
        }

        $msg = JText::_('COM_JBLANCE_PROJECT_DELETED_SUCCESSFULLY');
        $return = JRoute::_('index.php?option=com_jblance&view=project&layout=showmyproject', false);
        $this->setRedirect($return, $msg);
    }

    function acceptBid() {
        // Check for request forgeries
        JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        $id = $app->input->get('id', 0, 'int');  // bid id
        $now = JFactory::getDate();
        $projHelp = JblanceHelper::get('helper.project');  // create an instance of the class ProjectHelper
        //update the bid status to 'accept'
        $bid = JTable::getInstance('bid', 'Table');
        $bid->load($id);
        $bid->status = 'COM_JBLANCE_ACCEPTED';

        if (!$bid->check())
            JError::raiseError(500, $bid->getError());

        if (!$bid->store())
            JError::raiseError(500, $bid->getError());

        $bid->checkin();

        //update project status to 'close'
        $project = JTable::getInstance('project', 'Table');
        $project->load($bid->project_id);
        $project->status = 'COM_JBLANCE_CLOSED';
        $project->accept_date = $now->toSql();

        /* if(!$project->check())
          JError::raiseError(500, $project->getError());

          if(!$project->store())
          JError::raiseError(500, $project->getError());

          $project->checkin(); */

        //calculate the project fee for buyer and debit him [ONLY FOR FIXED PROJECT TYPE - FOR HOURLY PROJECTS DEBIT COMMISSION FOR EACH FUND TRANSFERS]
        /* if($project->project_type == 'COM_JBLANCE_FIXED'){
          $fee_from_buyer  = $projHelp->calculateProjectFee($project->publisher_userid, $bid->amount, 'buyer');
          $transDtl = JText::sprintf('COM_JBLANCE_PROJECT_COMMISSION_FOR_PROJECT_NAME', $project->project_title);
          JblanceHelper::updateTransaction($project->publisher_userid, $transDtl, $fee_from_buyer, -1);

          //update transaction table for freelancer
          $fee_from_lancer = $projHelp->calculateProjectFee($project->assigned_userid, $bid->amount, 'freelancer');
          $transDtl = JText::sprintf('COM_JBLANCE_PROJECT_COMMISSION_FOR_PROJECT_NAME', $project->project_title);
          JblanceHelper::updateTransaction($project->assigned_userid, $transDtl, $fee_from_lancer, -1);

          //update the profit of the project
          $project->profit = $fee_from_buyer + $fee_from_lancer;

          //update the buyer & freelancer commission
          $project->buyer_commission = $fee_from_buyer;
          $project->lancer_commission = $fee_from_lancer;
          } */

        if (!$project->check())
            JError::raiseError(500, $project->getError());

        if (!$project->store())
            JError::raiseError(500, $project->getError());

        $project->checkin();

        //rating actor:buyer and target:freelancer (buyer rating freelancer)
        $rate_buyer = JTable::getInstance('rating', 'Table');
        $rate_buyer->id = 0;
        $rate_buyer->actor = $project->publisher_userid;
        $rate_buyer->target = $project->assigned_userid;
        $rate_buyer->project_id = $project->id;
        JPluginHelper::importPlugin('joombri');
        $dispatcher = JDispatcher::getInstance();
       // $dispatcher->trigger('onProfileProgress', array(70, $project->assigned_userid));
        if (!$rate_buyer->check())
            JError::raiseError(500, $rate_buyer->getError());

        if (!$rate_buyer->store())
            JError::raiseError(500, $rate_buyer->getError());

        $rate_buyer->checkin();

        //rating actor:freelancer and target:buyer (freelancer rating buyer)
        $rate_lancer = JTable::getInstance('rating', 'Table');
        $rate_lancer->id = 0;
        $rate_lancer->actor = $project->assigned_userid;
        $rate_lancer->target = $project->publisher_userid;
        $rate_lancer->project_id = $project->id;

        if (!$rate_lancer->check())
            JError::raiseError(500, $rate_lancer->getError());

        if (!$rate_lancer->store())
            JError::raiseError(500, $rate_lancer->getError());

        $rate_lancer->checkin();

        //send bid accept notification to publisher
        $jbmail = JblanceHelper::get('helper.email');  // create an instance of the class EmailHelper
        $jbmail->sendProjectAcceptedNotification($bid->project_id, $bid->user_id);

        //Trigger the plugin event to feed the activity - buyer pick freelancer
        JPluginHelper::importPlugin('joombri');
        $dispatcher = JDispatcher::getInstance();
        $dispatcher->trigger('onFreelancerAcceptBid', array($project->assigned_userid, $project->publisher_userid, $project->id));

        $msg = JText::_('COM_JBLANCE_BID_ACCEPTED_SUCCESSFULLY');
        $return = JRoute::_('index.php?option=com_jblance&view=project&layout=showmybid', false);
        $this->setRedirect($return, $msg);
    }

    function denyBid() {
        // Check for request forgeries
        JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        $row = JTable::getInstance('bid', 'Table');

        $id = $app->input->get('id', 0, 'int'); //bid id
        $row->load($id);
        $row->status = 'COM_JBLANCE_DENIED';

        $project = JTable::getInstance('project', 'Table');
        $project->load($row->project_id);

        if (!$row->check())
            JError::raiseError(500, $row->getError());

        if (!$row->store())
            JError::raiseError(500, $row->getError());

        $row->checkin();

        //send bid denied notification to publisher
        $jbmail = JblanceHelper::get('helper.email');  // create an instance of the class EmailHelper
        $jbmail->sendProjectDeniedNotification($row->project_id, $row->user_id);

        //Trigger the plugin event to feed the activity - buyer pick freelancer
        JPluginHelper::importPlugin('joombri');
        $dispatcher = JDispatcher::getInstance();
        $dispatcher->trigger('onFreelancerDenyBid', array($project->assigned_userid, $project->publisher_userid, $project->id));

        $msg = JText::_('COM_JBLANCE_BID_DENIED_SUCCESSFULLY');
        $return = JRoute::_('index.php?option=com_jblance&view=project&layout=showmybid', false);
        $this->setRedirect($return, $msg);
    }

    function retractBid() {
        // Check for request forgeries

        JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        $row = JTable::getInstance('bid', 'Table');
        $id = $app->input->get('bidid', 0, 'int'); //bid id
        $row->load($id);

        //remove the bid attachement
        $attachedFile = $row->attachment;
        if ($attachedFile) {
            $attFile = explode(';', $attachedFile);
            $filename = $attFile[1];
            $delete = JBBIDNDA_PATH . '/' . $filename;
            if (JFile::exists($delete))
                unlink($delete);
        }

        if (!$row->delete($id))
            JError::raiseError(500, $row->getError());

        $msg = JText::_('COM_JBLANCE_BID_RETRACTED_SUCCESSFULLY');
        $return = JRoute::_('index.php?option=com_jblance&view=project&layout=showmybid', false);
        $this->setRedirect($return, $msg);
    }

    function saveRateUser() {
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        $post = $app->input->post->getArray();
        $id = $app->input->get('id', 0, 'int');
        $rate_type = $app->input->get('rate_type', '', 'string');

        $row = JTable::getInstance('rating', 'Table');
        $row->load($id);
        
        $now = JFactory::getDate();
        $row->rate_date = $now->toSql();

        if (!$row->save($post))
            JError::raiseError(500, $row->getError());
         JPluginHelper::importPlugin('joombri');
            $dispatcher = JDispatcher::getInstance();
           // $dispatcher->trigger('onProfileProgress', array(90, $project->assigned_userid));
        $msg = JText::_('COM_JBLANCE_USER_RATING_SAVED_SUCCESSFULLY');

        /* 	If I rate a buyer, I'm a Freelancer. Hence direct me to showmybid page.
         * 	If I rate a freelancer, I'm a buyer. Hence direct me to showmyproject page.
         */

        if ($rate_type == 'COM_JBLANCE_BUYER')
            $return = JRoute::_('index.php?option=com_jblance&view=project&layout=showmybid', false);
        elseif ($rate_type == 'COM_JBLANCE_FREELANCER')
            $return = JRoute::_('index.php?option=com_jblance&view=project&layout=showmyproject', false);
        else
            $return = JRoute::_('index.php?option=com_jblance&view=project&layout=listproject', false);

        //Trigger the plugin event to feed the activity - buyer pick freelancer
        JPluginHelper::importPlugin('joombri');
        $dispatcher = JDispatcher::getInstance();
        $dispatcher->trigger('onUserRating', array($row->actor, $row->target, $row->project_id));

        $this->setRedirect($return, $msg);
    }

    function paymentComplete() {
        // Check for request forgeries
        JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        $id = $app->input->get('pid', 0, 'int');
        $user = JFactory::getUser();

        //update status project
        $project = JTable::getInstance('project', 'Table');
        $project->load($id);
        $project->paid_status = 'COM_JBLANCE_PYMT_COMPLETE';

        if (!$project->check())
            JError::raiseError(500, $project->getError());

        if (!$project->store())
            JError::raiseError(500, $project->getError());

        $project->checkin();

        //send send hourly project payment complete notification
        $jbmail = JblanceHelper::get('helper.email');  // create an instance of the class EmailHelper
        $jbmail->sendProjectPaymentCompleteNotification($id, $user->id);

        $msg = JText::_('COM_JBLANCE_PAYMENT_MARKED_COMPLETE_SUCCESSFULLY');
        $redirect_url = empty($_SERVER['HTTP_REFERER']) ? JRoute::_('index.php?option=com_jblance&view=user&layout=dashboard', false) : $_SERVER['HTTP_REFERER'];
        $this->setRedirect($redirect_url, $msg);
        return false;
    }

    function repostProject() {
        // Check for request forgeries
        JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        $id = $app->input->get('pid', 0, 'int');
        $user = JFactory::getUser();
        $now = JFactory::getDate();

        //update status project
        $project = JTable::getInstance('project', 'Table');
        $project->load($id);
        $project->status = 'COM_JBLANCE_OPEN';
        $project->start_date = $now->toSql();

        if (!$project->check())
            JError::raiseError(500, $project->getError());

        if (!$project->store())
            JError::raiseError(500, $project->getError());

        $project->checkin();

        // deduce `charge per project` if amount is > 0
        $plan = JblanceHelper::whichPlan($user->id);
        $chargePerProject = $plan->buyChargePerProject;

        if ($chargePerProject > 0) {
            $transDtl = JText::_('COM_JBLANCE_REPOST') . ' - ' . $project->project_title;
            JblanceHelper::updateTransaction($user->id, $transDtl, $chargePerProject, -1);
            $msg_debit = JText::sprintf('COM_JBLANCE_YOUR_ACCOUNT_DEBITED_WITH_CURRENCY_FOR_POSTING_PROJECT', JblanceHelper::formatCurrency($chargePerProject));
            $app->enqueueMessage($msg_debit);
        }

        /* //send send hourly project payment complete notification
          $jbmail = JblanceHelper::get('helper.email');		// create an instance of the class EmailHelper
          $jbmail->sendProjectPaymentCompleteNotification($id, $user->id); */

        $msg = JText::_('COM_JBLANCE_PROJECT_REPOSTED_SUCCESSFULLY');
        $return = JRoute::_('index.php?option=com_jblance&view=project&layout=showmyproject', false);
        $this->setRedirect($return, $msg);
        return false;
    }

    function saveInviteUser() {
        // Check for request forgeries
        JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));

        // Initialize variables
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $id = $app->input->get('pid', 0, 'int'); //proj id
        $project = JTable::getInstance('project', 'Table');
        $post = $app->input->post->getArray();
        $invite_ids = $app->input->get('invite_userid', array(), 'array');
        $jbmail = JblanceHelper::get('helper.email');  // create an instance of the class EmailHelper

        $config = JblanceHelper::getConfig();
        $reviewProjects = $config->reviewProjects;

        if (count($invite_ids) > 0 && !(count($invite_ids) == 1 && empty($invite_ids[0]))) {
            $user_ids = implode(',', $invite_ids);
        } elseif ($invite_ids[0] == 0) {
            $user_ids = 0;
        }

        $project->load($id);
        $project->invite_user_id = $user_ids;

        if (!$project->check())
            JError::raiseError(500, $project->getError());

        if (!$project->store())
            JError::raiseError(500, $project->getError());

        $project->checkin();


        //send private project invitation to selected user if the project doesn't need review
        if (!$reviewProjects)
            $jbmail->sendNewProjectNotification($id, true);

        $msg = JText::_('COM_JBLANCE_INVITATION_SENT_SUCCESSFULLY');
        $return = JRoute::_('index.php?option=com_jblance&view=project&layout=showmyproject', false);
        $this->setRedirect($return, $msg);
        return false;
    }

    function saveInviteToProject() {
        // Check for request forgeries
        JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));

        // Initialize variables
        $app = JFactory::getApplication();
        $user_id = $app->input->get('user_id', 0, 'int'); //id of the user being invited
        $project_id = $app->input->get('project_id', 0, 'int'); //proj id
        $project = JTable::getInstance('project', 'Table');
        $jbmail = JblanceHelper::get('helper.email');  // create an instance of the class EmailHelper

        $project->load($project_id);

        //if invite user id is 0, it is new else append
        if ($project->invite_user_id == 0 || empty($project->invite_user_id)) {
            $project->invite_user_id = $user_id;
        } else {
            $project->invite_user_id .= ',' . $user_id;
        }

        if (!$project->check())
            JError::raiseError(500, $project->getError());

        if (!$project->store())
            JError::raiseError(500, $project->getError());

        $project->checkin();

        //send invitation to the user to bid on this project
        $jbmail->sendInviteToProjectNotification($project_id, $user_id);


        $msg = JText::_('COM_JBLANCE_INVITATION_SENT_SUCCESSFULLY');
        echo '<p class="alert alert-success">' . $msg . '</p>';

        $doc = JFactory::getDocument();
        $js = "javascript:window.top.setTimeout('window.parent.SqueezeBox.close()', 5000);";
        $doc->addScriptDeclaration($js);

        return false;
    }

    function updateProgress() {
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        $post = $app->input->post->getArray();
        $now = JFactory::getDate();
        $bid = JTable::getInstance('bid', 'Table');
        $bidId = $app->input->get('id', 0, 'int');
        $status = $app->input->get('p_status', '', 'string');
        $statusInit = $app->input->get('status_initiated', 0, 'boolean');

        if ($statusInit)
            $status = 'COM_JBLANCE_INITIATED';

        $bid->load($bidId);

        $bid->p_status = $status;
        $bid->p_percent = $post['p_percent'];
        $bid->p_updated = $now->toSql();


        //set the started date only for the first time
        if ($status == 'COM_JBLANCE_INITIATED') {
            if ($bid->p_started == '0000-00-00 00:00:00') {
                $bid->p_started = $now->toSql();
            }
        } elseif ($status == 'COM_JBLANCE_COMPLETED') {
            if ($bid->p_ended == '0000-00-00 00:00:00') {
                $bid->p_ended = $now->toSql();
            }
        }

        if (!$bid->store()) {
            JError::raiseError(500, $bid->getError());
        }
        $bid->checkin();

        //send project progress notification to buyer
        $jbmail = JblanceHelper::get('helper.email');  // create an instance of the class EmailHelper
        if (!empty($status))
            $jbmail->sendProjectProgressNotification($bidId, $bid->project_id);

        $msg = JText::_('COM_JBLANCE_PROJECT_PROGRESS_UPDATED_SUCCESSFULLY');
        $return = JRoute::_('index.php?option=com_jblance&view=project&layout=projectprogress&id=' . $bidId . '&pid=' . $bid->project_id, false);
        $this->setRedirect($return, $msg, 'message');
    }

    /* Misc Functions */

    //download file
    function download() {
        // Check for request forgeries
        JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));

        JBMediaHelper::downloadFile();
    }

    // submit forum
    function submitForum() {
        // Check for request forgeries
        JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        $now = JFactory::getDate();
        $row = JTable::getInstance('forum', 'Table');
        $message = $app->input->get('message', '', 'string');
        $userid = $app->input->get('user_id', 0, 'int');
        $projectid = $app->input->get('project_id', 0, 'int');
        $post = array();
        $jbmail = JblanceHelper::get('helper.email');  // create an instance of the class EmailHelper
        $projHelper = JblanceHelper::get('helper.project');  // create an instance of the class ProjectHelper
        //show whether name/username
        $config = JblanceHelper::getConfig();
        $showUsername = $config->showUsername;
        $nameOrUsername = ($showUsername) ? 'username' : 'name';

        //get user details
        $user = JFactory::getUser($userid);

        //get project details 
        $jbproject = $projHelper->getProjectDetails($projectid);

        $post['date_post'] = $now->toSql();
        $post['message'] = stripslashes($message);
        $post['user_id'] = $userid;
        $post['project_id'] = $projectid;
        $post['project_title'] = $jbproject->project_title;
        $post['publisher_userid'] = $jbproject->publisher_userid;
        if ($message) {
            $result = $row->save($post);
            if ($result) {
                $username = '<span>' . $user->$nameOrUsername . '</span>';
                $message = '<p>' . strip_tags($post['message']) . '</p>';
                echo "<li>$username.$message</li>";

                //send notification to the users who are in the forum
                $jbmail->sendForumMessageNotification($post);
            } else
                JError::raiseError(500, $row->getError());
        }
        exit;
    }

    function getLocationAjax() {
        // Check for request forgeries
        JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));

        JblanceHelper::getLocation();
    }

    //function to get the location
    function getnewlocationAjax() {
        $app = JFactory::getApplication();
        $model = $this->getModel('project');
        $model->getLocations();
        $app->close();
    }

    //validate email
    function checkUser() {
        $app = JFactory::getApplication();
        $model = $this->getModel('project');
        $model->checkEmail();
        $app->close();
    }

    //function generate random string
    function random_password($length = 8) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $password = substr(str_shuffle($chars), 0, $length);
        return $password;
    }

    //function to save subscription
    private function saveSub($user) {
        $row = JTable::getInstance('plansubscr', 'Table');
        //GENERATE RANDOM TRANSACTION ID
        $chars = "0123456789";
        $transaction_id = substr(str_shuffle($chars), 0, 3);

        //calculate the price
        $db = JFactory::getDbo();
        $plan = JTable::getInstance('plan', 'Table');
        $plan->load(5);
        $now = JFactory::getDate();

        $row->user_id = $user->id;
        $row->gateway = "byadmin";
        $row->gateway_id = time();
        $row->approved = 1;
        $row->trans_id = $transaction_id;
        $row->plan_id = 5;
        $row->price = $plan->price;
        $row->fund = $plan->bonusFund;
        $row->date_buy = $now->toSql();
        $row->date_approval = $now->toSql();
        $row->tax_percent = $config->taxPercent;
        $date = new DateTime();
        $date->add(new DateInterval('P' . $plan->days . 'Y'));

        $row->date_expire = $date->format('Y-m-d H:i:s');


        if (!$row->store()) {
            return false;
        } else {
            $lastId = $row->id;
            $row = JTable::getInstance('plansubscr', 'Table');
            $row->load($lastId);
            $config = JblanceHelper::getConfig();
            $invoiceFormatPlan = $config->invoiceFormatPlan;
            //update the invoice no
            $year = date("Y");
            $time = time();
            //replace the tags
            $tags = array("[ID]", "[USERID]", "[YYYY]", "[TIME]");
            $tagsValues = array("$row->id", "$row->user_id", "$year", "$time");
            $invoiceNo = str_replace($tags, $tagsValues, $invoiceFormatPlan);
            $row->invoiceNo = $invoiceNo;

            $row->store();

            return true;
        }
    }

    //return after successful payment for project upgrades
    function returnafterpayment() {

        $app = JFactory::getApplication();
        $data = $app->input->post->getArray();

        //load the plugin to validate ipn
        JPluginHelper::importPlugin('appmeadows');
        $dispatcher = JEventDispatcher::getInstance();
        $response = $dispatcher->trigger('onReceivedIpn', array(& $data));
        $response = $response[0];

        $payment_gross = intval(JRequest::getVar('payment_gross'));
        $custom = JRequest::getVar('custom');
        $status = JRequest::getVar('payment_status');
        $custom = explode('-', $custom);
        $pj_id = $custom[0];
        $user_id = $custom[1];
        $pjUps = explode(' ', $custom[2]);

        $redirect = JRoute::_(JUri::base() . "index.php?option=com_jblance&task=project.projectdashboard&pid=" . $pj_id . "&Itemid=340");
        //if success prcess the payment
        if ($status == "Completed" && $response) {
            $this->processPayment($pj_id, $user_id, $pjUps, $payment_gross, $redirect);
        } else {
            $app->redirect($redirect, 'Payment status: ' . $status, 'error');
        }
    }

    //process project payment
    private function processPayment($pid, $uid, $pjups, $ammount, $redir) {
        $Pstatus = JRequest::getVar('payment_status');
        $app = JFactory::getApplication();
        $jbmail = JblanceHelper::get('helper.email');
        $availablePjs = array('buyFeePerUrgentProject' => 'is_urgent', 'buyFeePerPrivateProject' => 'is_private', 'buyFeePerFeaturedProject' => 'is_featured', 'buyFeePerAssistedProject' => 'is_assisted', 'buyFeePerSealedProject' => 'is_sealed');
        //project
        $project = JTable::getInstance('project', 'Table');
        $project->load($pid);
        //check for tempered price 
        $planRow = JblanceHelper::whichPlan($uid);
        $planParams = json_decode($planRow->params);
        $calculated_price = '';
        foreach ($pjups as $pprice) {
            $calculated_price+=$planParams->$pprice;
        }


        if ($calculated_price == $ammount) {
            foreach ($pjups as $pjname) {
                $project->$availablePjs[$pjname] = 1;
            }
            //store the project
            if ($project->store()) {
                $jbmail->sendProjectupgradeNotificationAdmin($project->id, $uid);
                //save the transaction
                $transaction = JTable::getInstance('transaction', 'Table');
                $transaction->date_trans = date("y:m:d h:i:s");
                $transaction->transaction = "Buy Project Upgrades(Payment gateway)";
                $transaction->fund_plus = $ammount;
                $transaction->fund_minus = $ammount;
                $transaction->user_id = $uid;
                $transaction->store();
                if (!empty($redir)) {

                    $app->redirect($redir, "Project successfully upgraded", "message");
                }
            }
        } else {
            $app->redirect($redir, "Error Processing your payment", "error");
            //else scope for notifying admin about tempered payment
        }
    }

    public function validateEmail() {

        jimport('joomla.user.helper');
        $user = JFactory::getUser();
        $jbmail = JblanceHelper::get('helper.email');
        $registered = false;
        if (!$user->guest)
            $registered = true;

        $app = JFactory::getApplication();
        //get sanitized email from request
        $email = JRequest::getString('email', '', 'METHOD', JREQUEST_NOTRIM);
        //get integer based id
        $id = JRequest::getString('id', '', 'METHOD', JREQUEST_NOTRIM);
        $pid = JRequest::getInt('pid');
        $db = JFactory::getDbo();
        $redirect = JRoute::_(JUri::root() . 'index.php?option=com_jblance&task=project.projectdashboard&id=' . $pid);
        if (empty($email) || empty($id) || empty($pid))
            $app->redirect(JUri::root(), 'Invalid request', 'error');

        $query = 'SELECT id,email,emailvalid FROM #__users WHERE `email` = ' . $db->quote($email) . ' AND `emailvalid`=' . $db->quote($id);

        $db->setQuery($query);
        $resuser = $db->loadAssoc();
        if (!empty($resuser)) {
            $query = "UPDATE #__users SET emailvalid='' WHERE email=" . $db->quote($email);
            $db->setQuery($query);
            $result = $db->execute();
            if ($result)
                $jbmail->sendEmailValidationNotificationAdmin($pid, $resuser['id']);
            if ($registered)
                $user->set('emailvalid', '');

            $app->redirect($redirect, 'Email successfully validated,please wait while we validate your project', 'message');
        }
        else {
            $app->redirect(JUri::root(), 'Invalid operation encountered', 'error');
        }
    }

    //resend registration mail
    public function resendRegistrationmail() {
        JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
        jimport('joomla.user.helper');
        $app = JFactory::getApplication();
        $user = JFactory::getUser();
        $jbmail = JblanceHelper::get('helper.email');
        $id = JRequest::getInt('pid');
        $redirect = JRoute::_(JUri::root() . 'index.php?option=com_jblance&task=project.projectdashboard&pid=' . $id);
        $isOwnedOperation = JblanceHelper::checkOwnershipOfOperation($id, 'project');
        if (!empty($id) && $isOwnedOperation && $user->emailvalid != '') {
            $password = $this->random_password();
            $bindArray = array("password" => $password, "password2" => $password);

            $user->bind($bindArray);
            if ($user->save()) {
                $User = new stdClass();
                $User->newUsername = $user->username;
                $User->newPassword = $user->password_clear;
                $User->activationLink = $user->emailvalid;
                $User->email = $user->email;
                //we wont save plain user password in the session_cache_expire
                $user->set('password_clear', '');
                $jbmail->sendNewProjectRegistrationNotificationUser($id, $User);

                $app->redirect($redirect, 'Email successfully sent.', 'message');
            }
        } else {
            $app->redirect(JUri::root(), 'Invalid operation encountered', 'error');
        }
    }

    //charge upgrades

    public function upgradeProject() {
        JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
        $app = JFactory::getApplication();
        $pid = JRequest::getInt('pid');
        $user = JFactory::getUser();
        $post = $app->input->getArray();
        $jbmail = JblanceHelper::get('helper.email');
        $redirect = JRoute::_('index.php?option=com_jblance&view=project&layout=projectdashboard&pid=' . $pid . '&Itemid=340');
        $upgrades = array('urgent' => 'buyFeePerUrgentProject', 'private' => 'buyFeePerPrivateProject', 'featured' => 'buyFeePerFeaturedProject', 'assisted' => 'buyFeePerAssistedProject');
        $availablePjs = array('buyFeePerUrgentProject' => 'is_urgent', 'buyFeePerPrivateProject' => 'is_private', 'buyFeePerFeaturedProject' => 'is_featured', 'buyFeePerAssistedProject' => 'is_assisted', 'buyFeePerSealedProject' => 'is_sealed');
        if (empty($pid))
            $app->redirect(JUri::root(), 'Invalid operation encountered', 'error');

        $isOwnedOperation = JblanceHelper::checkOwnershipOfOperation($pid, 'project');

        if ($isOwnedOperation) {

            $plan = JblanceHelper::whichPlan($user->id);

            $planParams = json_decode($plan->params);

            $paymentMode = $plan->paymentmode;

            $upsellAmmount = 0;
            $upsellPurchased = array();
            foreach ($post as $k => $value) {
                if (array_key_exists($k, $upgrades)) {
                    $upsellPurchased[$planParams->$upgrades[$k]] = $upgrades[$k];
                    $upsellAmmount+=$planParams->$upgrades[$k];
                }
            }



            $upsellCount = count($upsellPurchased);

            if ($upsellCount > 0) {
                if ($paymentMode == 1) {
                    //if mode is deposited funds 
                    //again calculate the total funds if user was charged for project posting
                    $newtotalFund = JblanceHelper::getTotalFund($user->id);
                    if ($newtotalFund > $upsellAmmount) {
                        $project = JTable::getInstance('project', 'Table');
                        $project->load($pid);

                        foreach ($upsellPurchased as $pjname) {
                            $project->$availablePjs[$pjname] = 1;
                        }
              

                        //store the project
                        if ($project->store()) {
                            $transDtl = JText::_("Buy Project Upgrades(Deposited fund)");
                            JblanceHelper::updateTransaction($user->id, $transDtl, $upsellAmmount, -1);
                            $jbmail->sendProjectupgradeNotificationAdmin($project->id, $user->id);
                            $app->enqueueMessage('Project successfully upgraded,$ ' . $upsellAmmount . ' charged from your deposited funds.', 'message');
                        }
                    } else {
                        $app->enqueueMessage('You do not have sufficient funds to upgrade this project', 'error');
                    }
                    $app->redirect($redirect);
                } else {
                    //invoke the appropriate payment gateway
                    $app->setUserState('pjId', $pid);
                    $app->setUserState('upsells', $upsellPurchased);
                    $app->setUserState('upammount', $upsellAmmount);

$api_jb = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jblance'.DS.'helper.php';
include_once($api_jb);
$credits=JblanceHelper::get('helper.credits');
$points='';
$upsell= array();
if($post['urgent']=='1'){
    $credits::UpdateCredits(array("","","","","Project Upgraded by Urgent Upselling","-".$post['urgent_points'],"","",$post['urgent_points']." USD has been credited to your account."));

array_push($upsell,array('is_urgent' => "1"));
}
if($post['private']=='1'){
    $credits::UpdateCredits(array("","","","","Project Upgraded by Private Upselling","-".$post['private_points'],"","",$post['private_points']." USD has been credited from your account."));
array_push($upsell,array('is_private' => "1"));

}
if($post['featured']=='1'){
    $credits::UpdateCredits(array("","","","","Project Upgraded by Featured Upselling","-".$post['featured_points'],"","",$post['featured_points']." USD has been credited from your account."));
array_push($upsell,array('is_featured' => "1"));
}
if($post['assisted']=='1'){
    $credits::UpdateCredits(array("","","","","Project Upgraded by Assisted Upselling","-".$post['assisted_points'],"","",$post['assisted_points']." USD has been credited from your account."));
array_push($upsell,array('is_assisted' => "1"));
}
    $model = $this->getModel('project');
    $model->upsellings($upsell);
die('controller');
        $app->redirect(JRoute::_('index.php?option=com_jblance&view=project&layout=pickuser&pid=392&Itemid=308'), 'Project is Upgraded', 'message');
                }
            }
            $app->redirect(JRoute::_('index.php?option=com_jblance&view=project&layout=projectdashboard&pid=' . $pid . '&Itemid=340', 'Please select any upgrade to purchase.', 'error'));
        }
        
    }

    // start by kamal  
    function savenotification() {
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialize variables
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $user = JFactory::getUser();
        $row = JTable::getInstance('notify', 'Table');
        $post = $app->input->post->getArray();
        $id = $app->input->get('pid', 0, 'int');
        $jbmail = JblanceHelper::get('helper.email');  // create an instance of the class EmailHelper
        $isNew = false;

        $jinput = JFactory::getApplication()->input;

        $value['notifyBidNewAcceptDeny'] = $jinput->get('notifyBidNewAcceptDeny', 0, null);
        $value['notifyDeveloperRecommendation'] = $jinput->get('notifyDeveloperRecommendation', 0, null);
        $value['notifyNewMessage'] = $jinput->get('notifyNewMessage', 0, null);
        $value['notifyPaymentTransaction'] = $jinput->get('notifyPaymentTransaction', 0, null);

        $result = $row->checkUserExist($user->id);

        $row->id = $result->id;

        if (isset($result->usercount) AND $result->usercount >= 1) {
            $result = $row->UserNotifyUpdate($value);
            $msg = JText::_('Notification is successfully save');
            $return = JRoute::_('index.php?option=com_jblance&view=project&layout=clientalert&Itemid=398', false);
            $this->setRedirect($return, $msg);
        }
    }

}

?>