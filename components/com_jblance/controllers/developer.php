<?php

/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	16 March 2012
 * @file name	:	controllers/developer.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.controller');

class JblanceControllerDeveloper extends JControllerLegacy {

    function __construct() {
        parent :: __construct();
    }

    /**
      ==================================================================================================================
      SECTION : Registration & Login
      ==================================================================================================================
     */
    //1. grabUsergroupInfo
    function grabUsergroupInfo() {
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $user = JFactory::getUser();
        $ugid = $app->input->get('ugid', 0, 'int');

        //if the user has JoomBri profile, redirect him to the dashboard
        $hasJBProfile = JblanceHelper::hasJBProfile($user->id);
        if ($hasJBProfile) {
            $link = JRoute::_('index.php?option=com_jblance&view=user&layout=dashboard', false);
            $this->setRedirect($link);
        }

        $session = JFactory::getSession();
        $session->set('ugid', $ugid, 'register');
        $session->clear('skipPlan', 'register'); //clear or reset skip plan session if the registration is restarted.

        $freeMode = JblanceHelper::isFreeMode($ugid);

        if ($freeMode) {
            // if the user is not registered, direct him to registration page else to profile page.
            if ($user->id == 0)
                $return = JRoute::_('index.php?option=com_jblance&view=developer&layout=register&step=3', false);
            else
                $return = JRoute::_('index.php?option=com_jblance&view=developer&layout=usergroupfield', false);
        }
        else {
            // check for skipping of plan selection for this usergroup. If skipped, set the default plan for the usergroup
            $userHelper = JblanceHelper::get('helper.user');
            $ugroup = $userHelper->getUserGroupInfo(null, $ugid);

            if ($ugroup->skipPlan) {

                $query = "SELECT id FROM #__jblance_plan WHERE default_plan=1 AND ug_id=" . $db->quote($ugid);
                $db->setQuery($query);
                $defaultPlanId = $db->loadResult();

                if (empty($defaultPlanId)) {
                    $app->enqueueMessage(JText::_('COM_JBLANCE_NO_DEFAULT_PLAN_FOR_THE_USERGROUP', 'error'));
                    $return = JRoute::_('index.php?option=com_jblance&view=developer&layout=register&step=3', false);
                    //$return = JRoute::_('index.php?option=com_jblance&view=developer&layout=showfront', false);
                } else {
                    $session->set('planid', $defaultPlanId, 'register');
                    $session->set('gateway', 'banktransfer', 'register');
                    $session->set('skipPlan', 1, 'register');
                    // if the user is not registered, direct him to registration page else to profile page.
                    if ($user->id == 0)
                        $return = JRoute::_('index.php?option=com_jblance&view=developer&layout=register&step=2', false);
                    else
                        $return = JRoute::_('index.php?option=com_jblance&view=developer&layout=usergroupfield&step=2', false);
                }
            }
            else {
                $return = JRoute::_('index.php?option=com_jblance&view=membership&layout=planadd&step=2', false);
            }
        }
        $this->setRedirect($return);
        return;
    }

    /* //1. grabUsergroupInfo
      function grabUsergroupInfo(){
      // Check for request forgeries
      JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

      $app 	= JFactory::getApplication();
      $db		= JFactory::getDbo();
      $user 	= JFactory::getUser();
      $ugid 	= $app->input->get('ugid', 0, 'int');

      //if the user has JoomBri profile, redirect him to the dashboard
      $hasJBProfile = JblanceHelper::hasJBProfile($user->id);
      if($hasJBProfile){
      $link = JRoute::_('index.php?option=com_jblance&view=user&layout=dashboard', false);
      $this->setRedirect($link);
      }

      $session = JFactory::getSession();
      $session->set('ugid', $ugid, 'register');
      $session->clear('skipPlan', 'register');	//clear or reset skip plan session if the registration is restarted.

      $freeMode = JblanceHelper::isFreeMode($ugid);

      if($freeMode){
      // if the user is not registered, direct him to registration page else to profile page.
      if($user->id == 0)
      $return = JRoute::_('index.php?option=com_jblance&view=developer&layout=register&step=3', false);
      else
      $return = JRoute::_('index.php?option=com_jblance&view=developer&layout=usergroupfield', false);

      }
      else {
      // check for skipping of plan selection for this usergroup. If skipped, set the default plan for the usergroup
      $userHelper = JblanceHelper::get('helper.user');
      $ugroup = $userHelper->getUserGroupInfo(null, $ugid);

      if($ugroup->skipPlan){

      $query = "SELECT id FROM #__jblance_plan WHERE default_plan=1 AND ug_id=".$db->quote($ugid);
      $db->setQuery($query);
      $defaultPlanId = $db->loadResult();

      if(empty($defaultPlanId)){
      $app->enqueueMessage(JText::_('COM_JBLANCE_NO_DEFAULT_PLAN_FOR_THE_USERGROUP', 'error'));
      $return = JRoute::_('index.php?option=com_jblance&view=developer&layout=register&step=3', false);
      //$return = JRoute::_('index.php?option=com_jblance&view=developer&layout=showfront', false);
      }
      else {
      $session->set('planid', $defaultPlanId, 'register');
      $session->set('gateway', 'banktransfer', 'register');
      $session->set('skipPlan', 1, 'register');
      // if the user is not registered, direct him to registration page else to profile page.
      if($user->id == 0)
      $return = JRoute::_('index.php?option=com_jblance&view=developer&layout=register&step=2', false);
      else
      $return = JRoute::_('index.php?option=com_jblance&view=developer&layout=usergroupfield&step=2', false);
      }
      }
      else {
      $return	= JRoute::_('index.php?option=com_jblance&view=membership&layout=planadd&step=2', false);
      }
      }
      $this->setRedirect($return);
      return;
      } */

    function grabPlanInfo() {
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        $user = JFactory::getUser();
        $post = $app->input->post->getArray();

        $session = JFactory::getSession();
        $session->set('planid', $post['plan_id'], 'register');
        $session->set('gateway', $post['gateway'], 'register');
        $session->set('planChosen', $post, 'register');

        // if the user is not registered, direct him to registration page else to profile page.
        if ($user->id == 0) {
            $return = JRoute::_('index.php?option=com_jblance&view=developer&layout=register&step=3', false);
        } else {
            $return = JRoute::_('index.php?option=com_jblance&view=developer&layout=usergroupfield&step=3', false);
        }

        $this->setRedirect($return);
        return;
    }

    function grabUserAccountInfo() {
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        $session = JFactory::getSession();
        $post = $app->input->post->getArray();

        $session->set('userInfo', $post, 'register');

        //find step 3 or 4; step 4 = normal registration; step 3 = skip plan
        $skipPlan = $session->get('skipPlan', 0, 'register');
        $step = ($skipPlan) ? 'step=3' : 'step=4';

        $link = JRoute::_('index.php?option=com_jblance&view=developer&layout=usergroupfield&' . $step, false);
        $this->setRedirect($link);
        return false;
    }

    //1.Save new Developer
    function saveUserNew() {

        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        $now = JFactory::getDate();
        $jbmail = JblanceHelper::get('helper.email');  // create an instance of the class EmailHelper

        $config = JblanceHelper::getConfig();

        //get the user info from the session
        $session = JFactory::getSession();
        $userInfo = $session->get('userInfo', null, 'register');
        //$ugid 		= $session->get('ugid', null, 'register');
        $ugid = $session->get('ugid', 4, 'register');
        //$gateway 	= $session->get('gateway', '', 'register');
        $skipPlan = $session->get('skipPlan', 0, 'register');

        //$session->clear('id', 'upgsubscr');



        $user = JFactory::getUser();



        $post = $app->input->post->getArray();

        $session->get('planid', $post['plan_id'], 'register');
        $gateway = $session->get('gateway', 'paypal', 'register');

        //set user state
        foreach ($post as $ukey => $uvalue) {
            $app->setUserState($ukey, $uvalue);
        }

        //echo "<pre>"; print_r($post); die;
        //get the Joombri user group information
        $usergroup = JTable::getInstance('jbusergroup', 'Table');

        $usergroup->load($ugid);

        $jbrequireApproval = $usergroup->approval;
        $joomlaUserGroup = $usergroup->joomla_ug_id;
        $defaultUserGroup = explode(',', $joomlaUserGroup);

        //if the user is already registered and setting his profile to be JoomBri, then ignore the steps below.
        if ($user->id == 0) {

            // Get required system objects
            $usern = clone(JFactory::getUser());

            // Registration is disabled - Redirect to login page.
            $usersConfig = JComponentHelper::getParams('com_users');
            if ($usersConfig->get('allowUserRegistration') == '0') {
                $link_login = JRoute::_('index.php?option=com_users&view=login', false);
                $msg = JText::_('COM_JBLANCE_REGISTRATION_DISABLED_MESSAGE');
                $this->setRedirect($link_login, $msg, 'error');
                return;
            }

            //print_r($session->get('slogin_id','register')); die;

            $name = !empty($post['name']) ? $post['name'] : $post['username'];
            $bindArray = array("name" => $name, "password" => $post['password'], "password2" => $post['password'], "username" => $post['username'], "email" => $post['email'], "id_location" => $post['pj_city'], "work" => $post['work'], "dev_type" => $post['dev_type'], "plan_id" => $post['plan_id'], "slogin_id" => $session->get('slogin_id'));
            $usern->bind($bindArray);
            // add above line  */
            // Bind the post array to the user object
            if (!$usern->bind($post, 'usertype')) {
                JError::raiseError(500, $usern->getError());
            }

            // Set some initial user values
            $usern->set('id', 0);
            $usern->set('usertype', 'deprecated');
            $usern->set('groups', array(2, 13));
            $usern->set('jbname', $post['dname']);
            $usern->set('profileUrl', $post['url']);



            $usern->set('emailvalid', md5(JUserHelper::genRandomPassword()));


            //$usern->set('groups', $defaultUserGroup);
            $usern->set('registerDate', $now->toSql());

            $jAdminApproval = ($usersConfig->get('useractivation') == '2') ? 1 : 0; //require Joomla Admin approval

            $requireApproval = $jbrequireApproval | $jAdminApproval; //approval is required either JoomBri or Joomla require approval

            if ($requireApproval)
                $usern->set('block', '0');

            // If user activation is turned on, we need to set the activation information
            $useractivation = $usersConfig->get('useractivation');
            if (($useractivation == 1 || $useractivation == 2) && !$requireApproval) {
                jimport('joomla.user.helper');
                $usern->set('activation', JApplicationHelper::getHash(JUserHelper::genRandomPassword()));
                $usern->set('block', '0');
            }


            // If there was an error with registration, set the message and display form
            if (!$usern->save()) {
                $msg = JText::_($usern->getError());
                $link = JRoute::_('index.php?option=com_jblance&view=developer&layout=register');
                $this->setRedirect($link, $msg);
                return false;
            }
            $userid = $usern->id;

            $plan = JblanceHelper::getPlanPrice($usern->plan_id);
        }

        // Initialize variables
        $db = JFactory::getDbo();
        $row = JTable::getInstance('jbuser', 'Table');
        $row->user_id = $userid;
        $row->biz_name = $usern->username;
        $row->ug_id = 4;
        $row->id_location = $usern->pj_city;
        $row->rate = '0';
        $row->address = 'Null';
        $row->postcode = 'Null';
        /* $row->jbname = $post['dname'];
          $row->profileUrl = $post['url']; */


        $row->id_category = $usern->work;
        $row->id_category .= ", " . $usern->dev_type;
        if (!$row->save($post)) {
            JError::raiseError(500, $row->getError());
        }

        $fields = JblanceHelper::get('helper.fields');  // create an instance of the class fieldsHelper
        $fields->saveFieldValues('profile', $row->user_id, $post);

        //insert the user to notify table
        $obj = new stdClass();
        $obj->user_id = $userid;
        $db->insertObject('#__jblance_notify', $obj);

        // Send registration confirmation mail only to new registered user
        if ($user->id == 0) {
            $password = $post['password2'];
            $password = preg_replace('/[\x00-\x1F\x7F]/', '', $password); //Disallow control chars in the email

            $jbmail->sendRegistrationMail($usern, $password);

            if ($requireApproval) {
                $msg = JText::_('COM_JBLANCE_ACCOUNT_HAS_BEEN_CREATED_NEED_ADMIN_APPROVAL');
            } else {
                if ($useractivation) {
                    $msg = JText::_('COM_JBLANCE_ACCOUNT_HAS_BEEN_CREATED_NEED_ACTIVATION');
                } else {
                    $msg = JText::_('COM_JBLANCE_ACCOUNT_HAS_BEEN_CREATED_PLEASE_LOGIN');
                }
            }
        } else {
            $msg = JText::_('COM_JBLANCE_YOUR_PROFILE_HAS_BEEN_SUCCESSFULLY_CREATED');
        }


        $freeMode = JblanceHelper::isFreeMode($ugid);



        if (!$freeMode) {
            include_once(JPATH_COMPONENT . '/controllers/membership.php');
            $subscrRow = JblanceControllerMembership::addSubscription($userid); //add user to the subscription Table
            $subscrid = $subscrRow->id; //this returnid is the subscr id from plan_subscr table
            $session->set('id', $subscrid, 'upgsubscr');
            if ($gateway == 'banktransfer') {
                //send alert to admin and user
                $jbmail->alertAdminSubscr($subscrid, $userid);
                $jbmail->alertUserSubscr($subscrid, $userid);
            }
             //if plan selection is skipped, redirect him to the home page
            if ($skipPlan || ($subscrRow->price == 0)) {
                //$link = JRoute::_(JURI::root(), false);
                $link = JRoute::_('index.php?option=com_jblance&view=user&layout=dashboard');
            } else {
                //$app->enqueueMessage(JText::_('COM_JBLANCE_PROCEED_PAYMENT_AFTER_REGISTRATION'));
                 $link = JRoute::_('index.php?option=com_alphauserpoints&view=subscription&subid='.$plan->pidbt.'');
              //  $link = JRoute::_('index.php?option=com_jblance&view=membership&layout=check_out&type=plan', false);
            }
            $link = JRoute::_('index.php?option=com_alphauserpoints&view=subscription&subid=' . $plan->pidbt . '');
        } else {
            //$link = JRoute::_(JURI::root(), false);
            $link = JRoute::_('index.php?option=com_jblance&view=user&layout=dashboard');
        }

        //die('after subscription');
        //clear the session variable of 'register'
        /* $session->clear('ugid', 'register');
          $session->clear('planid', 'register');
          $session->clear('gateway', 'register');
          $session->clear('userInfo', 'register');
          $session->clear('skipPlan', 'register');
         */

        $credentials = Array('username' => $post['username'], 'password' => $post['password2'] );
        JFactory::getApplication()->login($credentials);
     
        $this->setRedirect($link, $msg);
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

    /* Misc Functions */

    //1.Check Username & Email (ajax)
    function checkUser() {
        // Check for request forgeries
        JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $inputstr = $app->input->get('inputstr', '', 'string');
        $name = $app->input->get('name', '', 'string');

        if ($name == 'username') {
            $sql = "SELECT COUNT(*) FROM #__users WHERE username='$inputstr'";
            $msg = 'COM_JBLANCE_USERNAME_EXISTS';
        } elseif ($name == 'email') {
            $sql = "SELECT COUNT(*) FROM #__users WHERE email='$inputstr'";
            $msg = 'COM_JBLANCE_EMAIL_EXISTS';
        }

        $db->setQuery($sql);
        if ($db->loadResult()) {
            echo JText::sprintf($msg, $inputstr);
        } else {
            echo 'OK';
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
        $model = $this->getModel('developer');
        $model->getLocations();
        $app->close();
    }

    //function to get the location
    //validate username
    function checkUserName() {
        $app = JFactory::getApplication();
        $model = $this->getModel('developer');
        $model->checkUser();
        $app->close();
    }

    //validate email
    function checkUserEmail() {
        $app = JFactory::getApplication();
        $model = $this->getModel('developer');
        $model->checkEmail();
        $app->close();
    }

    //validate profile url
    function checkUrl() {
        $app = JFactory::getApplication();
        $model = $this->getModel('developer');
        $model->checkProfileUrl();
        $app->close();
    }

    function activate($token) {
        $user = JFactory::getUser();
        $app = JFactory::getApplication();
        $token = JRequest::getString('token');
        if (isset($token) AND ! empty($token)) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from($db->quoteName('#__users'));
            $query->where($db->quoteName('activation') . '=' . $db->quote($token));
            $db->setQuery($query);
            $checkToken = $db->loadObject();
            if (isset($checkToken->id) AND $checkToken->id != 0) {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query = "UPDATE #__users SET emailvalid = '', activation='' WHERE activation=" . $db->quote($token);
                $db->setQuery($query);
                $result = $db->execute();
                if (isset($user->id) AND $user->id != 0) {
                    $bindArray = array("emailvalid" => '', "activation" => '');
                    $user = & JUser::getInstance((int) $user->id);
                    $user->bind($bindArray);
                    if ($user->save()) {
                        $url = JRoute::_('index.php?option=com_jblance&view=user&layout=dashboarddeveloper&Itemid=354');
                        $app->redirect($url, $msg = 'Your email is successfully verify.', 'message');
                    }
                } else {
                    $url = JRoute::_('index.php?option=com_users&view=login');
                    $app->redirect($url, $msg = 'Your email is successfully verify.', 'message');
                }
            } else {
                $app->redirect(JUri::root(), 'Invalid operation encountered', 'error');
            }
        }
    }

    //resend registration mail
    public function resendVerfiedEmail() {
        jimport('joomla.user.helper');
        $app = JFactory::getApplication();
        $user = JFactory::getUser();
        $jbmail = JblanceHelper::get('helper.email');

        if (isset($user->id) AND $user->id != 0 AND $user->emailvalid != '') {
            $encrypted = JApplicationHelper::getHash(JUserHelper::genRandomPassword());
            //echo "<pre>"; print_r($user); die;
            $password = JUserHelper::genRandomPassword();
            $bindArray = array("password" => $password, "password2" => $password, "activation" => $encrypted);

            $user = & JUser::getInstance((int) $user->id);
            $user->bind($bindArray);
            if ($user->save()) {

                $user->set('id', $user->id);
                $user->set('name', $user->name);
                $user->set('email', $user->email);
                $user->set('username', $user->username);
                $user->set('activation', $encrypted);
                //we wont save plain user password in the session_cache_expire
                $jbmail->sendRegistrationMail($user, $password);
                $redirect = JRoute::_(JUri::root());
                $app->redirect($redirect, 'Email successfully sent.', 'message');
            }
        } else {
            $app->redirect(JUri::root(), 'Invalid operation encountered', 'error');
        }
    }

}
