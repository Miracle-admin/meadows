<?php

/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	23 March 2012
 * @file name	:	models/project.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class JblanceModelProject extends JModelLegacy {

    function getEditProject($pid = null) {

        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $user = JFactory::getUser();
        if ($pid) {
            $id = $pid;
        } else
            $id = $app->input->get('id', 0, 'int');
        $finance = JblanceHelper::get('helper.finance');  // create an instance of the class FinanceHelper

        $isOwnedOperation = JblanceHelper::checkOwnershipOfOperation($id, 'project');
        if ($id > 0 && !$isOwnedOperation) {
            $msg = JText::sprintf('COM_JBLANCE_NOT_AUTHORIZED_TO_ACCESS_THIS_PAGE');
            $link = JRoute::_('index.php?option=com_jblance&view=user&layout=dashboard', false);
            $app->enqueueMessage($msg, 'error');
            $app->redirect($link);
            return false;
        }

        //check if the user's plan has expired or not approved. If so, do not allow him to post new project
        $planStatus = JblanceHelper::planStatus($user->id);
        if (($id == 0) && ($planStatus == 1 || $planStatus == 2)) {
            $msg = JText::sprintf('COM_JBLANCE_NOT_ALLOWED_TO_DO_OPERATION_NO_ACTIVE_SUBSCRIPTION');
            $link = JRoute::_('index.php?option=com_jblance&view=user&layout=dashboard', false);
            $app->enqueueMessage($msg, 'error');
            $app->redirect($link);
            return false;
        }

        //check if the user has enough fund to post new projects. This should be checked for new projects only
        $plan = JblanceHelper::whichPlan($user->id);
        $chargePerProject = $plan->buyChargePerProject;

        if (($chargePerProject > 0) && ($id == 0)) {
            $totalFund = JblanceHelper::getTotalFund($user->id);
            if ($totalFund < $chargePerProject) {
                $msg = JText::sprintf('COM_JBLANCE_BALANCE_INSUFFICIENT_TO_POST_PROJECT', JblanceHelper::formatCurrency($chargePerProject));
                $link = JRoute::_('index.php?option=com_jblance&view=membership&layout=depositfund', false);
                $app->enqueueMessage($msg, 'error');
                $app->redirect($link);
                return false;
            }
        }

        //check if the user has any project limit. If any and exceeds, then disallow him
        $lastSubscr = $finance->getLastSubscription($user->id);
        if (($id == 0) && ($lastSubscr->projects_allowed > 0 && $lastSubscr->projects_left == 0)) {
            $msg = JText::sprintf('COM_JBLANCE_NOT_ALLOWED_TO_POST_PROJECT_LIMIT_EXCEEDED');
            $link = JRoute::_('index.php?option=com_jblance&view=membership&layout=planadd', false);
            $app->enqueueMessage($msg, 'error');
            $app->redirect($link);
            return false;
        }

        $row = JTable::getInstance('project', 'Table');
        $row->load($id);

        $query = 'SELECT * FROM #__jblance_project_file WHERE project_id=' . $id;
        $db->setQuery($query);
        $projfiles = $db->loadObjectList();

        $query = "SELECT * FROM #__jblance_custom_field " .
                "WHERE published=1 AND field_for=" . $db->quote('project') . " " .
                "ORDER BY ordering";
        $db->setQuery($query);
        $fields = $db->loadObjectList();

        $return[0] = $row;
        $return[1] = $projfiles;
        $return[2] = $fields;
        return $return;
    }

    function getEditProjectwithregistration() {
        $user = JFactory::getUser();
        if ($user->guest) {
            $guest = 1;
        } else {
            $guest = 0;
        }
        $db = JFactory::getDbo();
        $return = array();
        //get the dev type categories
        $query = 'SELECT * FROM #__jblance_category WHERE parent=51 AND published=1 ORDER BY ordering';
        $db->setQuery($query);
        $categs = $db->loadRowList();
        //get the platforms
        $query = 'SELECT * FROM #__jblance_category WHERE parent=58 AND published=1 ORDER BY ordering';
        $db->setQuery($query);
        $platform = $db->loadRowList();
        //get the budget only for fixed price
        $query = 'SELECT * FROM #__jblance_budget WHERE published=1 AND project_type="COM_JBLANCE_FIXED" ORDER BY ordering';
        $db->setQuery($query);
        $budget = $db->loadObjectList();
        //get the locations
        $query = 'SELECT id,title FROM #__jblance_location WHERE parent_id=1';
        $db->setQuery($query);
        $countries = $db->loadObjectList();
        //get the project upgrades for company subscription
        $planRow = JTable::getInstance('plan', 'Table');

        $planRow->load(5);

        $query = 'SELECT id, price, pidbt,  name, params FROM #__jblance_plan WHERE `days_type` = "months" AND `pidbt` != "" AND `days` = 1';
        $db->setQuery($query);
        $plans = $db->loadObjectList();

        $return[0] = $categs;
        $return[1] = $platform;
        $return[2] = $budget;
        $return[3] = $countries;
        $return[4] = $planRow;
        $return[5] = array($guest, $user->email, $user->name);
        $return[6] = $plans;

        return $return;
    }

    function getShowMyProject($limit = null) {
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $user = JFactory::getUser();
        if (!$limit)
            $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->get('list_limit'), 'int');
        $limitstart = $app->input->get('limitstart', 0, 'int');
        $query = 'SELECT * FROM #__jblance_project p WHERE p.publisher_userid=' . $db->quote($user->id) . '  ORDER BY p.id DESC';
        $db->setQuery($query);
        $db->execute();
        $total = $db->getNumRows();

        jimport('joomla.html.pagination');
        $pageNav = new JPagination($total, $limitstart, $limit);

        $db->setQuery($query, $pageNav->limitstart, $pageNav->limit);
        $rows = $db->loadObjectList();
        // echo $user->id; echo  '<pre>';  print_r($rows); die;
        $return[0] = $rows;
        $return[1] = $pageNav;
        $return[2] = $rows;
        return $return;
    }

    function getListProject($limit = null) {
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $user = JFactory::getUser();
        $now = JFactory::getDate();
        $where = array();

        // Load the parameters.
        $params = $app->getParams();
        $param_status = $params->get('param_status', 'all');
        $param_upgrade = $params->get('param_upgrade', 'all');
        $param_categid = $params->get('id_categ', '');

        if ($param_status == 'open')
            $where[] = "p.status=" . $db->quote('COM_JBLANCE_OPEN');
        elseif ($param_status == 'frozen')
            $where[] = "p.status=" . $db->quote('COM_JBLANCE_FROZEN');
        elseif ($param_status == 'closed')
            $where[] = "p.status=" . $db->quote('COM_JBLANCE_CLOSED');

        if ($param_upgrade == 'featured')
            $where[] = "p.is_featured=1";
        elseif ($param_upgrade == 'urgent')
            $where[] = "p.is_urgent=1";
        elseif ($param_upgrade == 'private')
            $where[] = "p.is_private=1";
        elseif ($param_upgrade == 'sealed')
            $where[] = "p.is_sealed=1";

        if (!empty($param_categid))
            $where[] = 'FIND_IN_SET(' . $param_categid . ', p.id_category)';
        if (!$limit)
            $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->get('list_limit'), 'int');
        $limitstart = $app->input->get('limitstart', 0, 'int');

        $where[] = "p.approved=1";
        $where[] = "'$now' > p.start_date";

        $where = (count($where) ? ' WHERE (' . implode(') AND (', $where) . ')' : '');

        $query = "SELECT p.*,(TO_DAYS(p.start_date) - TO_DAYS(NOW())) AS daydiff FROM #__jblance_project p " .
                $where . " " .
                "ORDER BY p.is_featured DESC, p.id DESC"; //echo $query; 

        $db->setQuery($query);
        $db->execute();
        $total = $db->getNumRows();

        jimport('joomla.html.pagination');
        $pageNav = new JPagination($total, $limitstart, $limit);

        $db->setQuery($query, $pageNav->limitstart, $pageNav->limit);
        $rows = $db->loadObjectList();

        $return[0] = $rows;
        $return[1] = $pageNav;
        $return[2] = $params;
        return $return;
    }

    function getDetailProject() {
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $user = JFactory::getUser();
        $id = $app->input->get('id', 0, 'int');

        $config = JblanceHelper::getConfig();
        $sealProjectBids = $config->sealProjectBids;

        $row = JTable::getInstance('project', 'Table');
        $row->load($id);

        //get the location info
        $location = JTable::getInstance('location', 'Table');
        if ($row->id_location > 0) {
            $location->load($row->id_location);
            $this->setState('projectLocation', $location->params);
        }

        //redirect the project to login page if the project is a `private` project and user is not logged in
        if ($row->is_private && $user->guest) {
            $url = JFactory::getURI()->toString();
            $msg = JText::_('COM_JBLANCE_PRIVATE_PROJECT_LOGGED_IN_TO_SEE_DESCRIPTION');
            $link_login = JRoute::_('index.php?option=com_users&view=login&return=' . base64_encode($url), false);
            $app->enqueueMessage($msg, 'error');
            $app->redirect($link_login);
        }
        $publisherU = $row->publisher_userid;

        $pub = JFactory::getUser($publisherU);

        //redirect the user to dashboard if the project is not approved.
        if (!$row->approved || $pub->emailvalid != '') {
            $msg = JText::_('COM_JBLANCE_PROJECT_PENDING_APPROVAL_FROM_ADMIN');
            $link_dash = JRoute::_('index.php?option=com_jblance&view=user&layout=dashboard', false);
            $app->enqueueMessage($msg, 'error');
            $app->redirect($link_dash);
        }

        //redirect to dashboard if this is private invite project
        if ($row->is_private_invite) {
            $isMine = ($row->publisher_userid == $user->id);
            $invite_ids = explode(',', $row->invite_user_id);
            if (!in_array($user->id, $invite_ids) && !$isMine) {
                $msg = JText::_('COM_JBLANCE_THIS_IS_A_PRIVATE_INVITE_PROJECT_VISIBLE_TO_OWNER_INVITEES');
                $link_dash = JRoute::_('index.php?option=com_jblance&view=user&layout=dashboard', false);
                $app->enqueueMessage($msg, 'error');
                $app->redirect($link_dash);
            }
        }

        //get project files
        $query = 'SELECT * FROM #__jblance_project_file WHERE project_id=' . $id;
        $db->setQuery($query);
        $projfiles = $db->loadObjectList();

        //if the project is sealed, get the particular bid row for the bidder.
        $projHelper = JblanceHelper::get('helper.project');  // create an instance of the class ProjectHelper
        $hasBid = $projHelper->hasBid($row->id, $user->id);

        $bidderQuery = 'TRUE';
        if (($sealProjectBids || $row->is_sealed) && $hasBid) {
            $bidderQuery = " b.user_id=$user->id";
        }

        //for nda projects, bid count should inlcude only signed bids
        $ndaQuery = 'TRUE';
        if ($row->is_nda)
            $ndaQuery = " b.is_nda_signed=1";

        //get bid info
        $query = "SELECT b.*, u.username, u.name FROM #__jblance_bid b " .
                "INNER JOIN #__users u ON b.user_id=u.id " .
                "WHERE b.project_id =" . $id . " AND $bidderQuery AND $ndaQuery"; //echo $query;
        $db->setQuery($query);
        $bids = $db->loadObjectList();

        $query = "SELECT * FROM #__jblance_custom_field " .
                "WHERE published=1 AND field_for=" . $db->quote('project') . " " .
                "ORDER BY ordering";
        $db->setQuery($query);
        $fields = $db->loadObjectList();

        //get the forum list
        $query = "SELECT * FROM #__jblance_forum " .
                "WHERE project_id=$row->id " .
                "ORDER BY date_post ASC";
        $db->setQuery($query); //echo $query;
        $forums = $db->loadObjectList();

        $return[0] = $row;
        $return[1] = $projfiles;
        $return[2] = $bids;
        $return[3] = $fields;
        $return[4] = $forums;
        return $return;
    }

    function getPlaceBid() {
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $user = JFactory::getUser();
        $id = $app->input->get('id', 0, 'int'); //id is the "project id"
        $finance = JblanceHelper::get('helper.finance');  // create an instance of the class FinanceHelper

        $project = JTable::getInstance('project', 'Table');
        $project->load($id);

        // Project author is allowed to bid on his own project
        if ($project->publisher_userid == $user->id) {
            $msg = JText::sprintf('COM_JBLANCE_NOT_ALLOWED_TO_BID_ON_YOUR_OWN_PROJECT');
            $link = JRoute::_('index.php?option=com_jblance&view=user&layout=dashboard', false);
            $app->enqueueMessage($msg, 'error');
            $app->redirect($link);
            return false;
        }

        //project in Frozen/Closed should not be allowed to bid
        if ($project->status != 'COM_JBLANCE_OPEN') {
            $link = JRoute::_('index.php?option=com_jblance&view=project&layout=listproject', false);
            $app->redirect($link);
            return;
        }

        //redirect to dashboard if this is private invite project
        if ($project->is_private_invite) {
            $invite_ids = explode(',', $project->invite_user_id);
            if (!in_array($user->id, $invite_ids)) {
                $msg = JText::_('COM_JBLANCE_THIS_IS_A_PRIVATE_INVITE_PROJECT_VISIBLE_TO_OWNER_INVITEES');
                $link_dash = JRoute::_('index.php?option=com_jblance&view=user&layout=dashboard', false);
                $app->enqueueMessage($msg, 'error');
                $app->redirect($link_dash);
            }
        }


        //get the bid id
        $query = "SELECT id FROM #__jblance_bid WHERE project_id=" . $id . " AND user_id=" . $user->id;
        $db->setQuery($query);
        $bid_id = $db->loadResult();

        $bid = JTable::getInstance('bid', 'Table');
        $bid->load($bid_id);

        //check if the user's plan is expired or not approved. If so, do not allow him to bid new on project
        $planStatus = JblanceHelper::planStatus($user->id);
        if (empty($bid_id) && ($planStatus == 1 || $planStatus == 2)) {
            $msg = JText::sprintf('COM_JBLANCE_NOT_ALLOWED_TO_DO_OPERATION_NO_ACTIVE_SUBSCRIPTION');
            $link = JRoute::_('index.php?option=com_jblance&view=user&layout=dashboard', false);
            $app->enqueueMessage($msg, 'error');
            $app->redirect($link);
            return false;
        }

        //check if the user has enough fund to bid new on projects. This should be checked for new bids only
        $plan = JblanceHelper::whichPlan($user->id);
        $chargePerBid = $plan->flChargePerBid;

        if (($chargePerBid > 0) && (empty($bid_id))) { // bid_id will be empty for new bids
            $totalFund = JblanceHelper::getTotalFund($user->id);
            if ($totalFund < $chargePerBid) {
                $msg = JText::sprintf('COM_JBLANCE_BALANCE_INSUFFICIENT_TO_BID_PROJECT', JblanceHelper::formatCurrency($chargePerBid));
                $link = JRoute::_('index.php?option=com_jblance&view=membership&layout=depositfund', false);
                $app->enqueueMessage($msg, 'error');
                $app->redirect($link);
                return false;
            }
        }

        //check if the user has any bid limit. If any and exceeds, then disallow him
        $lastSubscr = $finance->getLastSubscription($user->id);
        if (empty($bid_id) && ($lastSubscr->bids_allowed > 0 && $lastSubscr->bids_left <= 0)) {
            $msg = JText::sprintf('COM_JBLANCE_NOT_ALLOWED_TO_BID_PROJECT_LIMIT_EXCEEDED');
            $link = JRoute::_('index.php?option=com_jblance&view=membership&layout=planadd', false);
            $app->enqueueMessage($msg, 'error');
            $app->redirect($link);
            return false;
        }

        $return[0] = $project;
        $return[1] = $bid;
        return $return;
    }

    function getProjectProgress() {
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $user = JFactory::getUser();
        $id = $app->input->get('id', 0, 'int'); //bid id

        $isOwnedOperation = JblanceHelper::checkOwnershipOfOperation($id, 'projectprogress'); //check ownership

        if (!$isOwnedOperation) {
            $msg = JText::sprintf('COM_JBLANCE_NOT_AUTHORIZED_TO_ACCESS_THIS_PAGE');
            $link = JRoute::_('index.php?option=com_jblance&view=user&layout=dashboard', false);
            $app->enqueueMessage($msg, 'error');
            $app->redirect($link);
            return false;
        }

        //get the project id from bid id
        $bid = JTable::getInstance('bid', 'Table');
        $bid->load($id);

        //get the project details
        $project = JTable::getInstance('project', 'Table');
        $project->load($bid->project_id);

        $query = "SELECT p.id project_id, p.publisher_userid buyer_id, p.assigned_userid freelancer_id, p.project_title, " .
                " b.id bid_id, b.amount, b.delivery, b.p_status, b.p_percent, b.p_started, b.p_updated, b.p_ended FROM #__jblance_bid b" .
                " LEFT JOIN #__jblance_project p ON p.id=b.project_id" .
                " WHERE b.id=" . $db->quote($id); //echo $query;
        $db->setQuery($query);
        $row = $db->loadObject();

        $query = "SELECT * FROM #__jblance_message " .
                "WHERE project_id=" . $db->quote($project->id) . " AND type='COM_JBLANCE_PROJECT' AND deleted=0 AND " .
                "(idFrom = " . $db->quote($project->publisher_userid) . " OR idTo = " . $db->quote($project->publisher_userid) . ") AND (idFrom = " . $db->quote($project->assigned_userid) . " OR idTo = " . $db->quote($project->assigned_userid) . ") " .
                "ORDER BY id"; //echo $query;
        $db->setQuery($query);
        $messages = $db->loadObjectList();

        $return[0] = $row;
        $return[1] = $messages;
        return $return;
    }

    function getShowMyBid() {
        $db = JFactory::getDbo();
        $user = JFactory::getUser();

        $query = "SELECT b.*,p.id proj_id,p.project_title,p.status proj_status,p.assigned_userid,p.publisher_userid," .
                "p.paid_amt,p.paid_status,p.lancer_commission,p.is_featured,p.is_urgent,p.is_private,p.is_sealed,p.is_nda,p.project_type,p.is_private_invite FROM #__jblance_bid b " .
                "LEFT JOIN #__jblance_project p ON b.project_id=p.id " .
                "WHERE user_id =" . $db->quote($user->id) . " ORDER BY b.id DESC"; //echo $query;
        $db->setQuery($query);
        $rows = $db->loadObjectList();

        $return[0] = $rows;
        return $return;
    }

    function getShowMyOngoingProjects() {
        $db = JFactory::getDbo();
        $user = JFactory::getUser();

        $query = "SELECT b.*,p.id proj_id,p.project_title,p.status proj_status,p.assigned_userid,p.publisher_userid," .
                "p.paid_amt,p.paid_status,p.lancer_commission,p.description,p.budgetmax,p.id_category,p.is_featured,p.is_urgent,p.is_private,p.is_sealed,p.is_nda,p.project_type,p.is_private_invite FROM #__jblance_bid b " .
                "LEFT JOIN #__jblance_project p ON b.project_id=p.id " .
                "WHERE user_id =" . $db->quote($user->id) . " AND b.p_status!='COM_JBLANCE_COMPLETED' ORDER BY b.id DESC";
        $db->setQuery($query);
        $rows = $db->loadObjectList();

        $return[0] = $rows;
        return $return;
    }

    function getPickUser() {

        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $id = $app->input->get('id', 0, 'int'); //proj id

        $project = JTable::getInstance('project', 'Table');
        $project->load($id);

        $query = "SELECT b.*,u.username,u.name,p.project_title,p.project_type,p.commitment FROM #__jblance_bid b " .
                "LEFT JOIN #__jblance_project p ON b.project_id=p.id " .
                "INNER JOIN #__users u ON b.user_id=u.id " .
                //"WHERE b.project_id =".$id." AND b.status =''";
                "WHERE b.project_id =" . $id . " AND TRUE";
        $db->setQuery($query);
        $rows = $db->loadObjectList();

        $return[0] = $rows;
        $return[1] = $project;
        return $return;
    }

    function getRateUser() {
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $id = $app->input->get('id', 0, 'int'); //rate id

        $rate = JTable::getInstance('rating', 'Table');
        $rate->load($id);

        //get info project
        $project = JTable::getInstance('project', 'Table');
        $project->load($rate->project_id);

        $return[0] = $rate;
        $return[1] = $project;
        return $return;
    }

    //7.Search Project
    function getSearchProject() {

        // Initialize variables
        $app = JFactory::getApplication();
        $user = JFactory::getUser();
        $db = JFactory::getDbo();
        $now = JFactory::getDate();

        $keyword = $app->input->get('keyword', '', 'string');
        $phrase = $app->input->get('phrase', 'any', 'string');
        $order = $app->input->get('order', 'popular', 'string');
        $id_categ = $app->input->get('id_categ', array(), 'array');
        $proj_type = $app->input->get('project_type', array(), 'array');
        $id_locate = $app->input->get('id_location', array(), 'array');
        $budget = $app->input->get('budget', '', 'string');
        $status = $app->input->get('status', 'COM_JBLANCE_OPEN', 'string');

        $keyword = preg_replace("/\s*,\s*/", ",", $keyword); //remove the spaces before and after the commas(,)
        switch ($phrase) {
            case 'exact':
                $text = $db->quote('%' . $db->escape($keyword, true) . '%', false);
                $wheres2 = array();
                $wheres2[] = 'p.project_title LIKE ' . $text;
                $wheres2[] = 'ju.biz_name LIKE ' . $text;
                $wheres2[] = 'cv.value LIKE ' . $text;
                $wheres2[] = 'p.description LIKE ' . $text;
                $queryStrings[] = '(' . implode(') OR (', $wheres2) . ')';
                break;

            case 'all':
            case 'any':
            default:
                $words = explode(',', $keyword);
                $wheres = array();
                if ($words) {
                    foreach ($words as $word) {
                        $word = $db->quote('%' . $db->escape($word, true) . '%', false);
                        $wheres2 = array();
                        $wheres2[] = 'p.project_title LIKE ' . $word;
                        $wheres2[] = 'ju.biz_name LIKE ' . $word;
                        $wheres2[] = 'cv.value LIKE ' . $word;
                        $wheres2[] = 'p.description LIKE ' . $word;
                        $wheres[] = implode(' OR ', $wheres2);
                    }
                    $queryStrings[] = '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
                }
                break;
        }
        //echo "<pre>"; print_r($order); die;

        switch ($order) {
            case 'popular':
                $orders = 'p.bid_count desc';
                break;
            case 'newer':
                $orders = 'p.create_date desc';
                break;
            case 'older':
                $orders = 'p.create_date asc';
                break;
            default:
                $orders = 'p.id desc';
                break;
        }

        if (count($id_categ) > 0 && !(count($id_categ) == 1 && empty($id_categ[0]))) {
            if (is_array($id_categ)) {
                $miniquery = array();
                foreach ($id_categ as $cat) {
                    $miniquery[] = "FIND_IN_SET($cat, p.id_category)";
                }


                $querytemp = '(' . implode(' OR ', $miniquery) . ')';
            }
            $queryStrings[] = $querytemp;
        }
        if (count($id_locate) > 0 && ($id_locate[0] > 1)) {
            $location = JTable::getInstance('location', 'Table');
            $queryStrings[] = "p.id_location IN (" . implode(',', $location->getChildren($id_locate[0])) . ")";
        }
        if (count($proj_type) > 0) {
            if (isset($proj_type['fixed']) && isset($proj_type['hourly']))
                $queryStrings[] = "(p.project_type = " . $db->quote($proj_type['fixed']) . ' OR ' . "p.project_type = " . $db->quote($proj_type['hourly']) . ')';
            elseif (isset($proj_type['fixed']))
                $queryStrings[] = "p.project_type = " . $db->quote($proj_type['fixed']);
            elseif (isset($proj_type['hourly']))
                $queryStrings[] = "p.project_type = " . $db->quote($proj_type['hourly']);
        }
        if (!empty($budget)) {
            $buget_exp = explode(',', $budget);
            $queryStrings[] = "p.budgetmin >= " . $db->quote($buget_exp[0]) . " AND p.budgetmax <= " . $db->quote($buget_exp[1]);
        }
       
        if ($status != 'any') {
            $queryStrings[] = "p.status=" . $db->quote($status);
        }

        $queryStrings[] = "p.approved=1";
        $queryStrings[] = "'$now' > p.start_date ";



        $where = implode(' AND ', $queryStrings);

        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->get('list_limit'), 'int');
        $limitstart = $app->input->get('limitstart', 0, 'int');

        $query = "SELECT DISTINCT p.*,(TO_DAYS(p.start_date) - TO_DAYS(NOW())) AS daydiff FROM #__jblance_project p" .
                " LEFT JOIN #__jblance_user ju ON p.publisher_userid = ju.user_id" .
                " LEFT JOIN #__jblance_custom_field_value cv ON cv.projectid=p.id" .
                " WHERE " . $where .
                " ORDER BY " . $orders;

//        echo $query;
//        die;

        $db->setQuery($query); //echo $query;
        $db->execute();
        $total = $db->getNumRows();

        jimport('joomla.html.pagination');
        $pageNav = new JPagination($total, $limitstart, $limit);

        $db->setQuery($query, $pageNav->limitstart, $pageNav->limit);
        $rows = $db->loadObjectList();
//echo '<pre>'; print_r($rows); die;

        $return[0] = $rows;
        $return[1] = $pageNav;
        return $return;
    }

    /* function getSearchProject(){


      $app  = JFactory::getApplication();
      $user = JFactory::getUser();
      $db   = JFactory::getDbo();
      $now  = JFactory::getDate();

      $keyword	= $app->input->get('keyword', '', 'string');
      $phrase	  	= $app->input->get('phrase', 'any', 'string');
      $id_categ	= $app->input->get('id_categ', array(), 'array');
      $proj_type	= $app->input->get('project_type', array(), 'array');
      $id_locate  = $app->input->get('id_location', array(), 'array');
      $budget 	= $app->input->get('budget', '', 'string');
      $status		= $app->input->get('status', 'COM_JBLANCE_OPEN', 'string');
     */

    //$keyword = preg_replace("/\s*,\s*/", ",", $keyword); 
    /*
      switch ($phrase) {
      case 'exact':
      $text		= $db->quote('%'.$db->escape($keyword, true).'%', false);
      $wheres2 	= array();
      $wheres2[] 	= 'p.project_title LIKE '.$text;
      $wheres2[] 	= 'ju.biz_name LIKE '.$text;
      $wheres2[] 	= 'cv.value LIKE '.$text;
      $wheres2[] 	= 'p.description LIKE '.$text;
      $queryStrings[] = '(' . implode( ') OR (', $wheres2 ) . ')';
      break;

      case 'all':
      case 'any':
      default:
      $words = explode(',', $keyword);
      $wheres = array();
      foreach ($words as $word) {
      $word		= $db->quote('%'.$db->escape($word, true).'%', false);
      $wheres2 	= array();
      $wheres2[] 	= 'p.project_title LIKE '.$word;
      $wheres2[] 	= 'ju.biz_name LIKE '.$word;
      $wheres2[] 	= 'cv.value LIKE '.$word;
      $wheres2[] 	= 'p.description LIKE '.$word;
      $wheres[] 	= implode(' OR ', $wheres2);
      }
      $queryStrings[] = '(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres ) . ')';
      break;
      }

      if(count($id_categ) > 0 && !(count($id_categ) == 1 && empty($id_categ[0]))){
      if(is_array($id_categ)){
      $miniquery = array();
      foreach($id_categ as $cat){
      $miniquery[] = "FIND_IN_SET($cat, p.id_category)";
      }
      $querytemp = '('.implode(' OR ', $miniquery).')';
      }
      $queryStrings[] = $querytemp;
      }
      if(count($id_locate) > 0 && ($id_locate[0] > 1)){
      $location	= JTable::getInstance('location', 'Table');
      $queryStrings[] = "p.id_location IN (".implode(',', $location->getChildren($id_locate[0])).")";

      }
      if(count($proj_type) > 0){
      if(isset($proj_type['fixed']) && isset($proj_type['hourly']))
      $queryStrings[] = "(p.project_type = ".$db->quote($proj_type['fixed']).' OR '."p.project_type = ".$db->quote($proj_type['hourly']).')';
      elseif(isset($proj_type['fixed']))
      $queryStrings[] = "p.project_type = ".$db->quote($proj_type['fixed']);
      elseif(isset($proj_type['hourly']))
      $queryStrings[] = "p.project_type = ".$db->quote($proj_type['hourly']);
      }
      if(!empty($budget)){
      $buget_exp = explode(',', $budget);
      $queryStrings[] = "p.budgetmin >= ".$db->quote($buget_exp[0])." AND p.budgetmax <= ".$db->quote($buget_exp[1]);
      }
      if($status != 'any'){
      $queryStrings[] = "p.status=".$db->quote($status);
      }

      $queryStrings[] = "p.approved=1";
      $queryStrings[] = "'$now' > p.start_date ";

      $where =  implode (' AND ', $queryStrings);

      $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->get('list_limit'), 'int');
      $limitstart	= $app->input->get('limitstart', 0, 'int');

      $query ="SELECT DISTINCT p.*,(TO_DAYS(p.start_date) - TO_DAYS(NOW())) AS daydiff FROM #__jblance_project p".
      " LEFT JOIN #__jblance_user ju ON p.publisher_userid = ju.user_id".
      " LEFT JOIN #__jblance_custom_field_value cv ON cv.projectid=p.id".
      " WHERE ".$where.
      " ORDER BY p.id DESC";
      $db->setQuery($query);
      $db->execute();
      $total = $db->getNumRows();

      jimport('joomla.html.pagination');
      $pageNav = new JPagination($total, $limitstart, $limit);

      $db->setQuery($query, $pageNav->limitstart, $pageNav->limit);
      $rows = $db->loadObjectList();

      $return[0] = $rows;
      $return[1] = $pageNav;
      return $return;
      } */

    function getInviteUser() {
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $where = array();

        $project_id = $app->input->get('id', 0, 'int');
        $project = JTable::getInstance('project', 'Table');
        $project->load($project_id);
        $id_categ = explode(',', $project->id_category);

        $isOwnedOperation = JblanceHelper::checkOwnershipOfOperation($project_id, 'project');
        if (!$isOwnedOperation) {
            $msg = JText::sprintf('COM_JBLANCE_NOT_AUTHORIZED_TO_ACCESS_THIS_PAGE');
            $link = JRoute::_('index.php?option=com_jblance&view=user&layout=dashboard', false);
            $app->enqueueMessage($msg, 'error');
            $app->redirect($link);
            return false;
        }

        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->get('list_limit'), 'int');
        $limitstart = $app->input->get('limitstart', 0, 'int');

        if (count($id_categ) > 0 && !(count($id_categ) == 1 && empty($id_categ[0]))) {
            if (is_array($id_categ)) {
                $miniquery = array();
                foreach ($id_categ as $cat) {
                    $miniquery[] = "FIND_IN_SET($cat, ju.id_category)";
                }
                $querytemp = '(' . implode(' OR ', $miniquery) . ')';
            }
            $queryStrings[] = $querytemp;
        }

        $queryStrings[] = "u.block=0";

        $queryStrings[] = "u.id <> " . $db->quote($project->publisher_userid); // do not list the project owner

        $where = (count($queryStrings) ? ' WHERE (' . implode(') AND (', $queryStrings) . ') ' : '');

        $query = "SELECT DISTINCT ju.*,u.username,u.name,ug.name AS grpname FROM #__jblance_user ju " .
                "LEFT JOIN #__users u ON ju.user_id=u.id " .
                "LEFT JOIN #__jblance_usergroup ug ON ju.ug_id=ug.id " .
                $where .
                "ORDER BY u.name"; //echo $query;
        $db->setQuery($query);
        $db->execute();
        $total = $db->getNumRows();

        //if there are no matching users, redirect to project edit page
        if ($total == 0) {
            $msg = JText::sprintf('COM_JBLANCE_NO_MATCHING_SKILLS_RECHOOSE');
            $link = JRoute::_('index.php?option=com_jblance&view=project&layout=editproject&id=' . $project_id, false);
            $app->enqueueMessage($msg, 'notice');
            $app->redirect($link);
            return false;
        }

        jimport('joomla.html.pagination');
        $pageNav = new JPagination($total, $limitstart, $limit);

        $db->setQuery($query, $pageNav->limitstart, $pageNav->limit);
        $rows = $db->loadObjectList();

        $return[0] = $rows;
        $return[1] = $project;
        $return[2] = $pageNav;
        return $return;
    }

    function getInviteToProject() {
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $user = JFactory::getUser();

        $query = 'SELECT id AS value, project_title AS text, invite_user_id FROM #__jblance_project p ' .
                'WHERE p.publisher_userid=' . $user->id . ' AND p.status=' . $db->quote('COM_JBLANCE_OPEN') . ' AND p.approved=1 ' .
                'ORDER BY p.id DESC';
        $db->setQuery($query);
        $rows = $db->loadObjectList();

        $return[0] = $rows;
        return $return;
    }

    /* Misc Functions */

    function countBids($id) {
        $db = JFactory::getDbo();
        $row = JTable::getInstance('project', 'Table');
        $row->load($id);

        //for nda projects, bid count should include only signed bids
        $ndaQuery = 'TRUE';
        if ($row->is_nda)
            $ndaQuery = "is_nda_signed=1";

        $query = "SELECT COUNT(*) FROM #__jblance_bid WHERE project_id = $id AND $ndaQuery";
        $db->setQuery($query);
        $total = $db->loadResult();
        return $total;
    }

    function getRate($pid, $userid) {
        $db = JFactory::getDbo();
        $query = "SELECT id,quality_clarity FROM #__jblance_rating WHERE project_id = " . $pid . " AND target =" . $userid;
        $db->setQuery($query);
        $rate = $db->loadObject();
        return $rate;
    }

    function getSelectRating($var, $default) {
        $put[] = JHtml::_('select.option', '', '- ' . JText::_('COM_JBLANCE_PLEASE_SELECT') . ' -');
        $put[] = JHtml::_('select.option', 1, '1 --- ' . JText::_('COM_JBLANCE_VERY_POOR'));
        $put[] = JHtml::_('select.option', 2, '2');
        $put[] = JHtml::_('select.option', 3, '3 --- ' . JText::_('COM_JBLANCE_ACCEPTABLE'));
        $put[] = JHtml::_('select.option', 4, '4');
        $put[] = JHtml::_('select.option', 5, '5 --- ' . JText::_('COM_JBLANCE_EXCELLENT'));
        $rating = JHtml::_('select.genericlist', $put, $var, "class='required'", 'value', 'text', $default);
        return $rating;
    }

    function getLabelProjectStatus($status) {

        if ($status == 'COM_JBLANCE_OPEN')
            $statusLabel = 'label label-success';
        elseif ($status == 'COM_JBLANCE_FROZEN')
            $statusLabel = 'label label-warning';
        elseif ($status == 'COM_JBLANCE_CLOSED')
            $statusLabel = 'label label-important';
        elseif ($status == 'COM_JBLANCE_EXPIRED')
            $statusLabel = 'label label-inverse';

        return '<span class="' . $statusLabel . '">' . JText::_($status) . '</span>';
    }

    function getMaxMinBudgetLimit($type) {
        $db = JFactory::getDbo();
        $query = "SELECT 0 minlimit, MAX(budgetmax) maxlimit FROM #__jblance_budget " .
                "WHERE project_type=" . $db->quote($type);
        $db->setQuery($query);
        $limit = $db->loadObject();
        return $limit;
    }

    //individual project dashboard

    function getProjectdashboard() {
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $user = JFactory::getUser();
        $id = $app->input->get('id', 0, 'int');
        $pid = $app->input->get('pid', 0, 'int');

        $bidAccepted = false;

        $project = JblanceHelper::get('helper.project');  // create an instance of the class FinanceHelper
        $config = JblanceHelper::getConfig();
        $return = array();
        $step = 0;
        $id = $pid == 0 ? $id : $pid;
        $appProgress = false;
        $isOwnedOperation = JblanceHelper::checkOwnershipOfOperation($id, 'project');
        $db = JFactory::getDbo();
        $pj = $project::getProjectDetails($id);
        $status = $pj->status;
        $freelancer = $pj->assigned_userid == $user->id ? true : false;


        if ($id > 0 && $isOwnedOperation || $freelancer) {

            $pjcat = explode(',', JblanceHelper::getCategoryNames($pj->id_category));
            $apr = $pj->approved;
            $appActivityPhase = false;
            //if not approved or email is not validated yet.
            if ($apr == 0 || $user->emailvalid != '') {
                $approved = "Certification pending";
                $step = 1;
            }
            //if approved and email valid set set approval activity phase to true.
            if ($apr == 1 && $user->emailvalid == '') {
                $approved = "Approved";
                $step = 2;
                $appActivityPhase = true;

                $query = "SELECT * FROM #__jblance_bid WHERE project_id=" . $id . " AND status='COM_JBLANCE_ACCEPTED'";
                $db->setQuery($query);
                $bid = $db->loadAssoc();
                $bId = $bid['id'];
            }

            if ($appActivityPhase && $status == "COM_JBLANCE_FROZEN") {
                $step = 3;
            }
            if ($appActivityPhase && $status == "COM_JBLANCE_CLOSED") {
                $query = "SELECT p.id project_id, p.publisher_userid buyer_id, p.assigned_userid freelancer_id, p.project_title, " .
                        " b.id bid_id, b.amount, b.delivery, b.p_status, b.p_percent, b.p_started, b.p_updated, b.p_ended FROM #__jblance_bid b" .
                        " LEFT JOIN #__jblance_project p ON p.id=b.project_id" .
                        " WHERE b.id=" . $db->quote($bId);

                $db->setQuery($query);
                $row = $db->loadAssoc();
                $step = $row['p_status'] != "COM_JBLANCE_COMPLETED" ? 4 : 5;
                $appProgress = true;
            }




            $expiredate = JFactory::getDate($pj->start_date);
            $expiredate->modify("+$pj->expires days");
            $status = $pj->status;
            $title = strlen($pj->project_title) > 100 ? substr($pj->project_title, 0, 100) . "..." : $pj->project_title;
            $description = strlen($pj->description) > 500 ? substr($pj->description, 0, 500) . "(...)" : $pj->description;
            $remaining = $status == 'COM_JBLANCE_EXPIRED' ? 'Expired' : JblanceHelper::showRemainingDHM($expiredate, 'SHORT', 'COM_JBLANCE_PROJECT_EXPIRED_SHORT');

            $bids = $this->countBids($pj->id);
            $location = explode(" &raquo; ", JblanceHelper::getLocationNames($pj->id_location));
            $budget = JblanceHelper::formatCurrency($pj->budgetmin, true, false, 0) . ' - ' . JblanceHelper::formatCurrency($pj->budgetmax, true, false, 0) . '(' . $config->currencyCode . ')';

            $projectStateBar = '		<div class="steps">
			<span class="line"></span>
			<ul class="fleft">
				<li class="step1';

            $step == 1 ? $projectStateBar.=' active' : '';
            $step > 1 ? $projectStateBar.=' valid' : '';


            $projectStateBar.= '">
				<span class="point"></span><span class="name-step">Validating</span>
				</li>
				<li class="step2 ';
            $step == 2 ? $projectStateBar.=' active' : '';
            $step > 2 ? $projectStateBar.=' valid' : '';

            $projectStateBar.='">
				<span class="point"></span><span class="name-step">Select Developer</span>				
				</li>
				<li class="step3 ';
            $step == 3 ? $projectStateBar.=' active' : '';
            $step > 3 ? $projectStateBar.=' valid' : '';

            $projectStateBar.='">
				<span class="point"></span><span class="name-step">Agreement</span>				
				</li>
			
            <li class="step4 ';
            $step == 4 ? $projectStateBar.=' active' : '';
            $step > 4 ? $projectStateBar.=' valid' : '';

            $projectStateBar.='">
				<span class="point"></span><span class="name-step">Work</span>				
				</li>			
			
			 <li class="step5 ';
            $step == 5 ? $projectStateBar.=' active' : '';
            $step > 5 ? $projectStateBar.=' valid' : '';

            $projectStateBar.='">
				<span class="point"></span><span class="name-step">Rate</span>				
				</li>
			
			</ul>
		</div>';
            $upgrades = array('urgent' => $pj->is_urgent, 'private' => $pj->is_private, 'featured' => $pj->is_featured, 'assisted' => $pj->is_assisted);
            $ugradesPurchased = 0;
            foreach ($upgrades as $upk => $upv):
                if ($upv == 1)
                    $ugradesPurchased+=(int) $upv;
            endforeach;

            $return['project_title'] = $title;
            $return['description'] = $description;
            $return['remaining'] = $remaining;
            $return['bids'] = $bids . " proposals";
            $return['location'] = $location;
            $return['budget'] = $budget;
            $return['approved'] = $approved;
            $return['category'] = $pjcat;
            $return['projectBar'] = $projectStateBar;
            $return['upgrades'] = $upgrades;
            $return['upgradespurchased'] = $ugradesPurchased;
            $return['assigneduserid'] = $pj->assigned_userid;
            $return['step'] = $step;
            //echo"<pre>";
            //print_r($return);
            //die;
            return $return;
        }
        else {
            $app->redirect(JRoute::_(JUri::root() . 'index.php?option=com_jblance&view=user&layout=dashboard&Itemid=148'), 'Invalid Request Type', 'error');
        }
    }

    function validateProject($post, $skipEmail) {
        $app = JFactory::getApplication();
        $redirect = JRoute::_("index.php?option=com_jblance&view=project&layout=editprojectcustom&Itemid=337");
        $fields = array('pj_title', 'pz_desc', 'pj_expires', 'pj_platform', 'pj_budget', 'dev_type', 'u_name', 'u_email', 'pj_count', 'pj_state', 'pj_city', 'buyFeePerUrgentProject', 'buyFeePerPrivateProject', 'buyFeePerFeaturedProject', 'buyFeePerAssistedProject', 'term_cond');



        $required = array('pj_title', 'pz_desc', 'pj_expires', 'pj_platform', 'pj_budget', 'dev_type', 'u_email', 'country_select', 'pj_count', 'term_cond');
        $numeric = array('pj_expires');
        $unique = array('u_email');
        $messagesRequired = array('pj_title' => JText::_('COM_JBLANCE_ENTER_PJ_TITLE'), 'pz_desc' => JText::_('COM_JBLANCE_ENTER_PJ_DESC'), 'pj_platform' => JText::_('COM_JBLANCE_SELECT_PLATFORM'), 'pj_budget' => JText::_('COM_JBLANCE_SELECT_BUDGET'), 'dev_type' => JText::_('COM_JBLANCE_SELECT_DEVELOPER_TYPE'), 'u_email' => JText::_('COM_JBLANCE_ENTER_EMAIL'), 'country_select' => JText::_('COM_JBLANCE_CHOOSE_COUNTRY'), 'term_cond' => JText::_('COM_JBLANCE_TANDC_AGREE'), 'pj_count' => JText::_('COM_JBLANCE_CHOOSE_COUNTRY'), 'pj_expires' => JText::_('COM_JBLANCE_ENTER_NUMBER_DAYS'), 'inv_email' => JText::_('COM_JBLANCE_ENTER_VALID_EMAIL'), 'inv_num' => JText::_('COM_JBLANCE_ENTER_NUM_VAL'), 'email_exist' => JText::sprintf('COM_JBLANCE_EMAIL_EXISTS', JRequest::getVar('u_email')));

        //set user state

        foreach ($fields as $value) {
            if (!empty($post[$value])) {
                $app->setUserState($value, $post[$value]);
            }
        }

        foreach ($post as $key => $value) {

            if (in_array($key, $required) && $value == "") {
                $app->redirect($redirect, $msg = $messagesRequired[$key], $msgType = 'error');
            }
            //validate two special cases email and num days
            if ($key == "u_email" && $skipEmail) {
                if (!filter_var($value, FILTER_VALIDATE_EMAIL) && !preg_match('/@.+\./', $value)) {
                    $app->redirect($redirect, $msg = $messagesRequired['inv_email'], $msgType = 'error');
                }
                if ($this->checkEmail($value)) {

                    $app->redirect($redirect, $msg = $messagesRequired['email_exist'], $msgType = 'error');
                }
            }
            //numeric
            if ($key == "pj_expires") {
                if (preg_match("/[^0-9]/", $value)) {
                    $app->redirect($redirect, $msg = $messagesRequired['inv_num'], $msgType = 'error');
                }
            }
        }
    }

    function getLocations($json = true) {
        $db = JFactory::getDbo();
        $location_id = JRequest::getVar('location_id');
        $query = 'SELECT id,title FROM #__jblance_location WHERE parent_id=' . $db->quote($location_id);
        //echo $query;
        //die;
        $db->setQuery($query);
        $return = $db->loadAssocList();
        if (!empty($return)) {
            if ($json)
                echo json_encode($return);
            else
                return $return;
        }
        else {
            if ($json)
                echo 0;
            else
                return 0;
        }
    }

    function checkEmail($email) {
        $db = JFactory::getDbo();
        $email_id = empty($email) ? JRequest::getVar('u_email') : $email;
        $query = 'SELECT email FROM #__users WHERE email=' . $db->quote($email_id);

        $db->setQuery($query);
        $return = $db->loadAssocList();
        if (!empty($return)) {
            if (empty($email)) {
                echo "false";
            } else {
                return true;
            }
        } else {
            if (empty($email)) {
                echo "true";
            } else {
                return false;
            }
        }
    }

    function getdefaultCheckedList() {
        $db = JFactory::getDbo();
        $i = 0;
        $query = 'SELECT * FROM #__jblance_category WHERE parent=0 AND published=1 ORDER BY ordering';
        $db->setQuery($query);
        $categs = $db->loadObjectList();

        foreach ($categs as $categ) {
            $subcategory = $this->getSubcategories($categ->id);
        }

        return $subcategory;
    }

    // list subcats as tree
    function getSubcategories($parent) {
        $db = JFactory::getDbo();
        $db->setQuery("SELECT * FROM #__jblance_category WHERE parent =" . $parent . " ORDER BY ordering");
        $rows = $db->loadObjectList();

        return $rows;
    }

    function getdefaultCategoryCheckedList() {
        $db = JFactory::getDbo();
        $i = 0;
        $query = 'SELECT * FROM #__jblance_category WHERE parent!=0 AND published=1 ORDER BY ordering';
        $db->setQuery($query);
        $categs = $db->loadObjectList();

        foreach ($categs as $categ) {
            $category[] = $categ->id;
        }

        return $category;
    }

    function getdefaultMainCategoryCheckedList() {
        $db = JFactory::getDbo();
        $i = 0;
        $query = 'SELECT * FROM #__jblance_category WHERE parent=0 AND published=1 ORDER BY ordering';
        $db->setQuery($query);
        $categs = $db->loadObjectList();

        foreach ($categs as $categ) {
            $category[] = $categ->id;
        }

        return $category;
    }

    public function getProjectHistory() {
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $user = JFactory::getUser();


        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->get('list_limit'), 'int');
        $limitstart = $app->input->get('limitstart', 0, 'int');

        $query = $db->getQuery(true);

        $query->select('*')
                ->from($db->quoteName('#__jblance_project'))
                ->join('INNER', $db->quoteName('#__jblance_bid') . ' ON (' . $db->quoteName('#__jblance_project.id') . ' = ' . $db->quoteName('#__jblance_bid.project_id') . ')')
                ->where($db->quoteName('user_id') . ' = ' . $db->quote($user->id))
                ->where($db->quoteName('p_status') . ' = ' . $db->quote('COM_JBLANCE_COMPLETED'))
                ->order('#__jblance_bid.id DESC');

        // Reset the query using our newly populated query object.
        $db->setQuery($query);

        // Load the results as a list of stdClass objects (see later for more options on retrieving data).
        $results = $db->loadObjectList();



        $total = $db->getNumRows();

        jimport('joomla.html.pagination');
        $pageNav = new JPagination($total, $limitstart, $limit);

        $db->setQuery($query, $pageNav->limitstart, $pageNav->limit);
        $rows = $db->loadObjectList();

        $return[0] = $rows;
        $return[1] = $pageNav;
        $return[2] = $params;
        return $return;
    }

    //start by kamal
    public function getClientProjectHistory() {
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $user = JFactory::getUser();


        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->get('list_limit'), 'int');
        $limitstart = $app->input->get('limitstart', 0, 'int');

        $query = $db->getQuery(true);

        $query->select('*')
                ->from($db->quoteName('#__jblance_project', 'a'))
                ->join('INNER', $db->quoteName('#__jblance_bid', 'b') . ' ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('b.project_id') . ')')
                ->where($db->quoteName('a.publisher_userid') . ' = ' . $db->quote($user->id))
                ->where($db->quoteName('b.p_status') . ' = ' . $db->quote('COM_JBLANCE_COMPLETED'))
                ->order('b.id DESC');

        //echo $query; die;
        // Reset the query using our newly populated query object.
        $db->setQuery($query);

        // Load the results as a list of stdClass objects (see later for more options on retrieving data).
        $results = $db->loadObjectList();



        $total = $db->getNumRows();

        jimport('joomla.html.pagination');
        $pageNav = new JPagination($total, $limitstart, $limit);

        $db->setQuery($query, $pageNav->limitstart, $pageNav->limit);
        $rows = $db->loadObjectList();

        $return[0] = $rows;
        $return[1] = $pageNav;
        $return[2] = $params;
        return $return;
    }

    public function getClientNotify() {
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $user = JFactory::getUser();

        $query = $db->getQuery(true);

        $query->select('*')
                ->from($db->quoteName('#__jblance_notify'))
                ->where($db->quoteName('user_id') . ' = ' . $db->quote($user->id));

        // Reset the query using our newly populated query object.
        $db->setQuery($query);

        // Load the results as a list of stdClass objects (see later for more options on retrieving data).
        $results = $db->loadObjectList();

        return $results;
    }

}
