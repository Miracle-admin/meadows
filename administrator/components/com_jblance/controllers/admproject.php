<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	12 March 2012
 * @file name	:	controllers/admproject.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controlleradmin');
include_once(JPATH_ADMINISTRATOR.'/components/com_jblance/helpers/jblance.php');	//include this helper file to make the class accessible in all other PHP files
JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_jblance/tables');	//include the tables path in order to use JTable in this controller file

/**
 * Showuser list controller class.
 */
class JblanceControllerAdmproject extends JControllerAdmin {

	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct(){
		parent::__construct();
	
		// Register Extra tasks
		/* $this->registerTask('add', 'edit');
		
		$this->registerTask('approveproject', 'approveproject'); */
		
		
		$this->registerTask('block', 'changeBlock');
		$this->registerTask('unblock', 'changeBlock');
	}
	
	/* function edit(){
		$app  	= JFactory::getApplication();
		$layout = $app->input->get('layout', '', 'string');
		$app->input->set('view', 'admproject');
		$app->input->set('hidemainmenu', 1);
		
		if($layout == 'showproject')	$app->input->set('layout', 'editproject');

		$this->display();
	}
	
	function remove(){
		$layout = $app->input->get('layout');
		if($layout == 'showproject') $this->removeProject();
	} */
/**
 ================================================================================================================
 SECTION : Project - new, remove, save, cancel, approve
 ================================================================================================================
 */
	function newProject(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		JRequest :: setVar('view', 'admproject');
		JRequest :: setVar('hidemainmenu', 1);
		JRequest :: setVar('layout', 'editproject');
		$this->display();
	}
	
	//4.Remove Resume
	function removeProject(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		// Initialize variables
		$app 	= JFactory::getApplication();
		$db 	= JFactory::getDbo();
		$row 	= JTable::getInstance('project', 'Table');
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid, array(0));
		
		if(count($cid)){
			for($i = 0; $i < count($cid); $i++){
				if(!$row->delete($cid[$i])){
					JError::raiseError($db->getErrorNum(), $db->getErrorMsg());
				}
				// Remove the bids for this project
				$query = 'DELETE FROM #__jblance_bid WHERE project_id = '.$db->quote($cid[$i]);
				$db->setQuery($query);
				if(!$db->execute()){
					JError::raiseError($db->getErrorNum(), $db->getErrorMsg());
				}
			}
		}
		$msg	= JText::_('COM_JBLANCE_PROJECTS_DELETED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admproject&layout=showproject';
		$this->setRedirect($link, $msg);
	}
	
	function saveProject(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		// Initialize variables
		$app  = JFactory::getApplication();
		$db	  = JFactory::getDbo();
		$row  = JTable::getInstance('project', 'Table');
		$id	  = $app->input->get('id' , 0 , 'int');
		$post = $app->input->post->getArray();
		$post['description'] = $app->input->get('description', '', 'RAW');
		//$budgetRange = $app->input->get('budgetrange', '', 'string');
		$isNew	= ($id == 0) ? true : false;
		$jbmail = JblanceHelper::get('helper.email');		// create an instance of the class EmailHelper
		
		if($isNew){
			$now = JFactory::getDate();
			$post['create_date'] = $now->toSql();
		}
		
		$id_category 	= $app->input->get('id_category', '', 'array'); 
		if(count($id_category) > 0 && !(count($id_category) == 1 && empty($id_category[0]))){
			$proj_categ = implode(',', $id_category);
		}
		elseif($id_category[0] == 0){
			$proj_categ = 0;
		}
		
		$post['id_category'] = $proj_categ;
		
		if($post['project_type'] == 'COM_JBLANCE_FIXED'){
			$budgetRange = explode('-', $post['budgetrange_fixed']);
		}
		elseif($post['project_type'] == 'COM_JBLANCE_HOURLY'){
			$budgetRange = explode('-', $post['budgetrange_hourly']);
		}
		$post['budgetmin'] = $budgetRange[0];
		$post['budgetmax'] = $budgetRange[1];
		
		//save the commitment value
		$commitment	= $app->input->get('commitment', null, 'array');
		
		// Build commitment string
		$registry = new JRegistry();
		$registry->loadArray($commitment);
		$post['commitment'] = $registry->toString();
	 
		if(!$row->save($post)){
			JError::raiseError(500, $row->getError());
		}
		
		//save the custom field value for project
		$fields = JblanceHelper::get('helper.fields');		// create an instance of the class fieldsHelper
		$fields->saveFieldValues('project', $row->id, $post);
		
		JBMediaHelper::uploadFile($post, $row);		//remove and upload files
		
		//send new project notification if the project is new and approved
		if($isNew && $post['approved'] == 1)
			$jbmail->sendNewProjectNotification($row->id, $isNew);
	
		$msg	= JText::_('COM_JBLANCE_PROJECT_SAVED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admproject&layout=showproject';
		$this->setRedirect($link, $msg);
	}
	
	function cancelProject(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$app = JFactory::getApplication();
		$msg = '';
		$link	= 'index.php?option=com_jblance&view=admproject&layout=showproject';
		$this->setRedirect($link, $msg);
	}
	
	function approveProject(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		$app 	= JFactory::getApplication();
		$db		= JFactory::getDbo();
		$user	= JFactory::getUser();
		$cid 	= $app->input->get('cid', array(), 'array');
		$jbmail = JblanceHelper::get('helper.email');		// create an instance of the class EmailHelper
		JArrayHelper::toInteger($cid, array(0));
		$delCount = 0;
	
		if(count($cid)){
			$count_ketemu = 0;
			for($i = 0; $i < count($cid); $i++){
				$curr_bid = $cid[$i];
				$row	= JTable::getInstance('project', 'Table');
				$row->load($curr_bid);
	
				//checking first
				if(!$row->approved){
	
					$row->approved = 1;
	
					if(!$row->check())
						JError::raiseError(500, $row->getError());
					
					if(!$row->store())
						JError::raiseError(500, $row->getError());
					
					$row->checkin();
					$delCount++;
					
					//send project approved email to the publisher
					$jbmail->sendPublisherProjectApproved($row->id);
					
					//send new project notification to all users
					$jbmail->sendNewProjectNotification($row->id, true);
				}
			}
		}
		$msg = $delCount.' '.JText::_('COM_JBLANCE_PROJECTS_APPROVED_SUCCESSFULLY');
		$link = 'index.php?option=com_jblance&view=admproject&layout=showproject';
		$this->setRedirect($link, $msg);
	}
	
/**
 ================================================================================================================
 SECTION : Service - new, remove, save, cancel, approve
 ================================================================================================================
 */
	function newService(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		JRequest :: setVar('view', 'admproject');
		JRequest :: setVar('hidemainmenu', 1);
		JRequest :: setVar('layout', 'editservice');
		$this->display();
	}
	
	function removeService(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		// Initialize variables
		$app 		= JFactory::getApplication();
		$db  		= JFactory::getDbo();
		$delCount 	= 0;
		$cid 		= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid);
		$cids = implode(',', $cid);
		
		// remove from service table
		$query = 'DELETE FROM #__jblance_service WHERE id IN ('.$cids.')';
		$db->setQuery($query);
		if(!$db->execute()){
			JError::raiseError($db->getErrorNum(), $db->getErrorMsg());
		}
		$delCount = $db->getAffectedRows();
		
		// remove from service order table
		$query = 'DELETE FROM #__jblance_service_order WHERE service_id IN ('.$cids.')';
		$db->setQuery($query);
		if(!$db->execute()){
			JError::raiseError($db->getErrorNum(), $db->getErrorMsg());
		}
	
		$msg	= $delCount.' '.JText::_('COM_JBLANCE_SERVICES_DELETED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admproject&layout=showservice';
		$this->setRedirect($link, $msg);
	}
	
	function saveService(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		// Initialize variables
		$app	= JFactory::getApplication();
		$row	= JTable::getInstance('service', 'Table');
		$post 	= $app->input->post->getArray();
		
		//process category ids
		$id_category 	= $app->input->get('id_category', '', 'array');
		if(count($id_category) > 0 && !(count($id_category) == 1 && empty($id_category[0]))){
			$categ = implode(',', $id_category);
		}
		elseif($id_category[0] == 0){
			$categ = 0;
		}
		$post['id_category'] = $categ;
		
		//process extra add-ons
		$extras	= $app->input->get('extras', null, 'array');
		$registry = new JRegistry();
		$registry->loadArray($extras);
		$post['extras'] = $registry->toString();
		
		//process service files
		$serviceFiles	= $app->input->get('serviceFiles', null, 'array');
		$registry = new JRegistry();
		$registry->loadArray($serviceFiles);
		$post['attachment'] = $registry->toString();
		
		if(!$row->save($post)){
 			throw new Exception($row->getError(), 500);
 		}
 		
		$msg	= JText::_('COM_JBLANCE_SERVICE_SAVED_SUCCESSFULLY').' : '.$row->service_title;
		$return	= JRoute::_('index.php?option=com_jblance&view=admproject&layout=showservice', false);
		$this->setRedirect($return, $msg);
	}

	function cancelService(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		$app = JFactory::getApplication();
		$msg = '';
		$link	= 'index.php?option=com_jblance&view=admproject&layout=showservice';
		$this->setRedirect($link, $msg);
	}
	
	function approveService(){
		// Check for request forgeries
		JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		
		// Initialize variables
		$app 		= JFactory::getApplication();
		$row		= JTable::getInstance('service', 'Table');
		$now 		= JFactory::getDate();
		$approved 	= $app->input->get('approved', 0, 'int');
		$service_id	= $app->input->get('service_id', 0, 'int');
		$reason		= $app->input->get('disapprove_reason', '', 'string');
		$jbmail 	= JblanceHelper::get('helper.email');		// create an instance of the class EmailHelper
		$response   = array();
		
		$isNew = ($service_id > 0) ? false : true;
		
		$row->load($service_id);
		
		if(!$approved){
			$row->disapprove_reason = $reason;
			$row->approved = 0;
		}
		
		if($approved){
			$row->approved = 1;
			if($row->publish_up == '0000-00-00 00:00:00')
				$row->publish_up = $now->toSql();
		}
		
		// save the changes after updating nda
		if(!$row->check())
			JError::raiseError(500, $row->getError());
		
		if(!$row->store()){
			JError::raiseError(500, $row->getError());
		}
		$row->checkin();
		
		//if($approved){
			//send service approval status email to the seller
			$jbmail->sendSellerServiceApprovalStatus($row->id);
		//}
		
		
		$response['result'] = 'OK';
		$response['msg'] = JText::_('COM_JBLANCE_APPROVAL_STATUS_UPDATED_EMAIL_SENT');
		echo json_encode($response); 
		$app->close();
	}

/**
 ================================================================================================================
 SECTION : User - save, cancel
 ================================================================================================================
 */	
	function saveUser(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		// Initialize variables
		$app 		= JFactory::getApplication();
		$db			= JFactory::getDbo();
		$row		= JTable::getInstance('jbuser', 'Table');
		$post 		= $app->input->post->getArray();
		$id			= $app->input->get('id', 0, 'int');
		$fund 	 	= $app->input->get('fund', null, 'float');
		$desc_fund 	= $app->input->get('desc_fund', null, 'string');
		$type_fund 	= $app->input->get('type_fund', null, 'string');
		$user_id 	= $app->input->get('user_id', 0, 'int');
		$is_new		= false;
		
		$config 	= JblanceHelper::getConfig();
		$currsymb 	= $config->currencySymbol;
	
		if($id > 0){	//existing user in JoomBri user table
			$row->load($id);
			$user_id = $row->user_id;
		}
		else {	// new user
			$is_new		= true;
			$row->user_id = $user_id;
		}
		
		$id_category 	= $app->input->get('id_category', '', 'array');
		if(count($id_category) > 0 && !(count($id_category) == 1 && empty($id_category[0]))){
			$proj_categ = implode(',', $id_category);
		}
		elseif($id_category[0] == 0){
			$proj_categ = 0;
		}
		$post['id_category'] = $proj_categ;
	
		/* //set the company name to empty if the user has JoomBri profile and not allowed to post job.
		$hasJBProfile = JblanceHelper::hasJBProfile($user_id);	//check if the user has JoomBri profile
		if($hasJBProfile){
			$jbuser = JblanceHelper::get('helper.user');		// create an instance of the class UserHelper
			$userInfo = $jbuser->getUserGroupInfo(null, $post['ug_id']);
			if(!$userInfo->allowPostProjects)
				$post['biz_name'] = '';
		} */
	
		if(!$row->save($post)){
			JError::raiseError(500, $row->getError());
		}
	
		if(!empty($fund)){
			//update the transaction table
			if($type_fund == 'p'){
				JblanceHelper::updateTransaction($user_id, $desc_fund, $fund, 1);
				$msg_fund = JText::sprintf('COM_JBLANCE_USER_CREDITED_WITH_CURRENCY', $currsymb, $fund);
				$app->enqueueMessage($msg_fund);
			}
			elseif($type_fund == 'm'){
				JblanceHelper::updateTransaction($user_id, $desc_fund, $fund, -1);
				$msg_fund = JText::sprintf('COM_JBLANCE_USER_DEBITED_WITH_CURRENCY', $currsymb, $fund);
				$app->enqueueMessage($msg_fund);
			}
		}
	
		$fields = JblanceHelper::get('helper.fields');		// create an instance of the class fieldsHelper
		$fields->saveFieldValues('profile', $row->user_id, $post);
		
		//insert the user to notify table, if new user
		if($is_new){
			$obj = new stdClass();
			$obj->user_id = $user_id;
			$db->insertObject('#__jblance_notify', $obj);
		}
	
		$name = $post['name'];
		$query = "UPDATE #__users SET name='$name' WHERE id=".$db->quote($user_id);
		$db->setQuery($query);
		$db->execute();
	
		$msg	= JText::_('COM_JBLANCE_USER_SAVED_SUCCESSFULLY').' - '.JText::_('COM_JBLANCE_USERID').' : '.$user_id;
		$link	= 'index.php?option=com_jblance&view=admproject&layout=showuser';
		$this->setRedirect($link, $msg);
	}
	
	function cancelUser(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$app  = JFactory::getApplication();
		$msg  = '';
		$link = 'index.php?option=com_jblance&view=admproject&layout=showuser';
		$this->setRedirect($link, $msg);
	}
	
	public function changeBlock(){
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$app 	= JFactory::getApplication();
		$jbmail = JblanceHelper::get('helper.email');		// create an instance of the class EmailHelper
		$cid 	= $app->input->get('cid', array(), 'array');
		$values	= array('block' => 1, 'unblock' => 0);
		$task	= $this->getTask();
		$value	= JArrayHelper::getValue($values, $task, 0, 'int');

		// Get the model of user component.
		include_once(JPATH_ADMINISTRATOR.'/components/com_users/models/user.php');
		$model =  new UsersModelUser();

		if(count($cid)){
			for($i=0; $i<count($cid); $i++){
				$curr_bid = $cid[$i];
				$userid = $curr_bid;
				if(!$model->block($curr_bid, $value)){
					JError::raiseWarning(500, $model->getError());
				}
				//send user approved email
				if($value == 0)
					$jbmail->sendUserAccountApproved($userid);
			}
		}
		
		if($value == 1){
			$msg = count($cid).' '.JText::_('COM_JBLANCE_USERS_BLOCKED_SUCCESSFULLY');
		}
		elseif($value == 0){
			$msg = count($cid).' '.JText::_('COM_JBLANCE_USERS_APPROVED_SUCCESSFULLY');
		}
		
		$link = 'index.php?option=com_jblance&view=admproject&layout=showuser';
		$this->setRedirect($link, $msg);
	}
	
	/**
	 ================================================================================================================
	 SECTION : Subscription - new, remove, save, cancel, approve, show
	 ================================================================================================================
	 */
	function newSubscr(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		JRequest :: setVar('view', 'admproject');
		JRequest :: setVar('hidemainmenu', 1);
		JRequest :: setVar('layout', 'editsubscr');
		$this->display();
	}
	
	function removeSubscr(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$app 	= JFactory::getApplication();
		$db 	= JFactory::getDbo();
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid, array(0));
	
		if(count($cid)){
			for($i = 0; $i < count($cid); $i++){
				$db->setQuery("DELETE FROM #__jblance_plan_subscr WHERE id=".$cid[$i]);
				if (!$db->execute()){
					JError::raiseError($db->getErrorNum(), $db->getErrorMsg());
				}
			}
		}
		$msg  = JText::_('COM_JBLANCE_SUBSCRIPTIONS_DELETED_SUCCESSFULLY');
		$link = 'index.php?option=com_jblance&view=admproject&layout=showsubscr';
		$this->setRedirect($link, $msg);
	}
	
	function saveSubscr(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		// Initialize variables
		$app 	= JFactory::getApplication();
		$db		= JFactory::getDbo();
		$row	= JTable::getInstance('plansubscr', 'Table');
		$post 	= $app->input->post->getArray();
		
		$config = JblanceHelper::getConfig();
		$invoiceFormatPlan = $config->invoiceFormatPlan;
		
		$id = $post['id'];
	
		if($id > 0){	//existing subscription
			$row->load($id);
			$isNew = false;
		}
		else {	// new user
			$isNew = true;
		}
	
		if(!$row->bind($post)){
			JError::raiseError(500, $row->getError());
		}
		// pre-save checks
		if(!$row->check()){
			JError::raiseError(500, $row->getError());
		}
		if($isNew){
			//calculate the price
			$query = 'SELECT id, days, days_type, price, discount, bonusFund, name FROM #__jblance_plan WHERE id ='.$row->plan_id;
			$db->setQuery($query);
			$plan = $db->loadObject();
	
			if($plan->discount){
				$query = 'SELECT COUNT(*) AS total FROM #__jblance_plan_subscr WHERE plan_id ='.$row->plan_id.' AND user_id='.$row->user_id;
				$db->setQuery($query);
				$total = $db->loadResult();
				if($total > 0){
					$plan->price = $plan->price - (($plan->price / 100) * $plan->discount);
				}
			}
			$now = JFactory::getDate();
			$row->price = $plan->price;
			$row->fund = $plan->bonusFund;
			$row->date_buy = $now->toSql();
			$row->tax_percent = $config->taxPercent;
	
			if (!$row->store()){
				JError::raiseError(500, $row->getError());
			}
			//update the invoice no
			$year = date("Y");
			$time = time();
			//replace the tags
			$tags = array("[ID]", "[USERID]", "[YYYY]", "[TIME]");
			$tagsValues = array("$row->id", "$row->user_id", "$year", "$time");
			$invoiceNo = str_replace($tags, $tagsValues, $invoiceFormatPlan);
			$row->invoiceNo = $invoiceNo;
		}
		
		// save the changes
		if(!$row->store()) {
			JError::raiseError(500, $row->getError());
		}
		$row->checkin();

		//approve the subscription if new or approve manully by admin.
		if($post['approved'] == 1){
			if($isNew || $row->date_approval == '0000-00-00 00:00:00'){
				JRequest :: setVar('cid', $row->id);
				$this->approveSubscr();
			}
		}
	
		$msg	= JText::_('COM_JBLANCE_SUBSCRIPTION_SAVED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admproject&layout=showsubscr';
		$this->setRedirect($link, $msg);
	}
	
	function cancelSubscr(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$app = JFactory::getApplication();
		$msg = '';
		$link = 'index.php?option=com_jblance&view=admproject&layout=showsubscr';
		$this->setRedirect($link, $msg);
	}	
	
	function approveSubscr(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		$app 	= JFactory::getApplication();
		$db		= JFactory::getDbo();
		$user	= JFactory::getUser();
		$cid 	= $app->input->get('cid', array(), 'array');
		$jbmail = JblanceHelper::get('helper.email');		// create an instance of the class EmailHelper
		JArrayHelper::toInteger($cid, array(0));
	
		if(count($cid)){
			$count_ketemu = 0;
			for($i = 0; $i < count($cid); $i++){
				$curr_bid = $cid[$i];
				$row	= JTable::getInstance('plansubscr', 'Table');
				$row->load($curr_bid);
	
				//get the plan details
				$query = "SELECT * FROM #__jblance_plan WHERE id = ".$row->plan_id;
				$db->setQuery($query);
				$plan = $db->loadObject();
	
				//checking first
				if($row->trans_id == 0){
	
					$desc_trans = JText::_('COM_JBLANCE_BUY_SUBSCR').' - '.$plan->name;
					$trans = JblanceHelper::updateTransaction($row->user_id, $desc_trans, $plan->bonusFund, 1);
	
					//update subscription approval
					$now = JFactory::getDate();
					$date_approve = $now->toSql();
					$now->modify("+$plan->days $plan->days_type");
					$date_expires = $now->toSql();
	
					$row->date_approval = $date_approve;
					$row->date_expire = $date_expires;
					$row->approved = 1;
					$row->gateway_id = time();
					$row->trans_id = $trans->id;
					$row->access_count = 1;
					
					//set the project/bid limit details
					$planParams = new JRegistry;
					$planParams->loadString($plan->params);
					
					$row->bids_allowed		= $planParams->get('flBidCount');
					$row->bids_left 		= $planParams->get('flBidCount');
					$row->projects_allowed	= $planParams->get('buyProjectCount');
					$row->projects_left		= $planParams->get('buyProjectCount');
	
					// pre-save checks
					if(!$row->check()){
						JError::raiseError(500, $row->getError());
					}
					// save the changes
					if(!$row->store()){
						JError::raiseError(500, $row->getError());
					}
					$row->checkin();
					$jbmail->sendSubscrApprovedEmail($row->id, $row->user_id);
				}
			}
		}
		$msg = JText::_('COM_JBLANCE_SUBSCRIPTIONS_APPROVED_SUCCESSFULLY');
		$link = 'index.php?option=com_jblance&view=admproject&layout=showsubscr';
		$this->setRedirect($link, $msg);
	}
	
	function showSubscr(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		JRequest :: setVar('view', 'admproject');
		JRequest :: setVar('layout', 'showsubscr');
		$this->display();
	}
/**
 ================================================================================================================
 SECTION : Funds Deposit - approve, remove
 ================================================================================================================
 */
	function approveDeposit(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		$app 	= JFactory::getApplication();
		$db		= JFactory::getDbo();
		$user 	= JFactory::getUser();
		$jbmail = JblanceHelper::get('helper.email');		// create an instance of the class EmailHelper
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid, array(0));
		$delCount = 0;
	
		if (count($cid)) {
			$count_ketemu = 0;
			for($i = 0; $i < count($cid); $i++){
				$curr_bid = $cid[$i];
				$row	= JTable::getInstance('deposit', 'Table');
				$row->load($curr_bid);
	
				//checking first
				if($row->trans_id == 0){
					$desc_credit = JText::sprintf('COM_JBLANCE_DEPOSIT_FUNDS');
					$trans = JblanceHelper::updateTransaction($row->user_id, $desc_credit, $row->amount, 1);
	
					$now = JFactory::getDate();
	
					//update deposit approval
					$row->date_approval = $now->toSql();
					$row->approved = 1;
					$row->trans_id = $trans->id;
	
					if(!$row->check())
						JError::raiseError(500, $row->getError());
					
					if(!$row->store())
						JError::raiseError(500, $row->getError());
					
					$row->checkin();
					$delCount++;
					
					//send approved deposit fund to depositor
					$jbmail->sendUserDepositFundApproved($row->id);
				}
				
			}
		}
	
		$msg	= $delCount.' '.JText::_('COM_JBLANCE_FUND_DEPOSIT_APPROVED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admproject&layout=showdeposit';
		$this->setRedirect($link, $msg);
	}
	
	function removeDeposit(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$app 	= JFactory::getApplication();
		$db 	= JFactory::getDbo();
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid, array(0));
		$delCount = 0;
	
		if(count($cid)){
			for($i = 0; $i < count($cid); $i++){
				$db->setQuery("DELETE FROM #__jblance_deposit WHERE id=".$cid[$i]);
				$delCount++;
				if(!$db->execute()){
					JError::raiseError($db->getErrorNum(), $db->getErrorMsg());
				}
			}
		}
		$msg	= $delCount.' '.JText::_('COM_JBLANCE_FUND_DEPOSIT_DELETED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admproject&layout=showdeposit';
		$this->setRedirect($link, $msg);
	}	
/**
 ================================================================================================================
 SECTION : Funds Withdraw - approve, remove
 ================================================================================================================
 */
	function approveWithdraw(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		$app  	= JFactory::getApplication();
		$db	  	= JFactory::getDbo();
		$user 	= JFactory::getUser();
		$now 	= JFactory::getDate();
		$jbmail = JblanceHelper::get('helper.email');		// create an instance of the class EmailHelper
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid, array(0));
		$delCount = 0;
	
		if (count($cid)) {
			$count_ketemu = 0;
			for($i = 0; $i < count($cid); $i++){
				$curr_bid = $cid[$i];
				$row = JTable::getInstance('withdraw', 'Table');
				$row->load($curr_bid);
	
				//checking first
				if($row->trans_id == 0){
					$desc_trans = JText::sprintf('COM_JBLANCE_WITHDRAW_FUNDS');
					$trans = JblanceHelper::updateTransaction($row->user_id, $desc_trans, $row->amount, -1);
	
					
	
					//update withdraw approval
					$row->date_approval = $now->toSql();
					$row->approved = 1;
					$row->trans_id = $trans->id;
	
					if(!$row->check())
						JError::raiseError(500, $row->getError());
					
					if(!$row->store())
						JError::raiseError(500, $row->getError());
					
					$row->checkin();
					
					$delCount++;
					
					//send approved withdraw request to requestor
					$jbmail->sendWithdrawRequestApproved($row->id);
				}
			}
		}
	
		$msg	= $delCount.' '.JText::_('COM_JBLANCE_FUND_WITHDRAWAL_APPROVED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admproject&layout=showwithdraw';
		$this->setRedirect($link, $msg);
	}
	
	function removeWithdraw(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$app 	= JFactory::getApplication();
		$db 	= JFactory::getDbo();
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid, array(0));
		$delCount = 0;
	
		if(count($cid)){
			for($i = 0; $i < count($cid); $i++){
				$db->setQuery("DELETE FROM #__jblance_withdraw WHERE id=".$cid[$i]);
				$delCount++;
				if(!$db->execute()){
					JError::raiseError($db->getErrorNum(), $db->getErrorMsg());
				}
			}
		}
		$msg	= $delCount.' '.JText::_('COM_JBLANCE_FUND_WITHDRAWAL_DELETED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admproject&layout=showwithdraw';
		$this->setRedirect($link, $msg);
	}
	
/**
 ================================================================================================================
 SECTION : Escrow - release, cancel
 ================================================================================================================
 */
	function releaseEscrow(){
		// Check for request forgeries
		JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		
		$app  	= JFactory::getApplication();
		$db 	= JFactory::getDbo();
		$id 	= $app->input->get('id', 0, 'int');
		
		$escrow = JTable::getInstance('escrow', 'Table');
		$escrow->load($id);
		
		$now = JFactory::getDate();
		$escrow->date_release = $now->toSql();
		$escrow->status = 'COM_JBLANCE_RELEASED';
		
		if(!$escrow->check())
			JError::raiseError(500, $escrow->getError());
		
		if(!$escrow->store())
			JError::raiseError(500, $escrow->getError());
		
		$escrow->checkin();
		
		//send escrow pymt released to the reciever
		$jbmail = JblanceHelper::get('helper.email');		// create an instance of the class EmailHelper
		$jbmail->sendEscrowPaymentReleased($escrow->id);
		
		/* //Trigger the plugin event to feed the activity - buyer pick freelancer
		JPluginHelper::importPlugin('system', 'jblancefeeds');
		$dispatcher = JDispatcher::getInstance();
		$dispatcher->trigger('onBuyerReleaseEscrow', array($escrow->from_id, $escrow->to_id, $escrow->project_id)); */
		
		$msg = JText::_('COM_JBLANCE_ESCROW_PAYMENT_RELEASED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admproject&layout=showescrow';
		$this->setRedirect($link, $msg);
	}
	
	function cancelEscrow(){
		// Check for request forgeries
		JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
	
		$app  	= JFactory::getApplication();
		$db 	= JFactory::getDbo();
		$id 	= $app->input->get('id', 0, 'int');
	
		$escrow = JTable::getInstance('escrow', 'Table');
		$escrow->load($id);
	
		//set the status to cancelled
		$escrow->status = 'COM_JBLANCE_CANCELLED';
	
		// get the transaction id and delete it
		$trans_id = $escrow->from_trans_id;
		$trans	= JTable::getInstance('transaction', 'Table');
		$trans->delete($trans_id);
	
		$escrow->from_trans_id = 0;
	
		if(!$escrow->check())
			JError::raiseError(500, $escrow->getError());
	
		if(!$escrow->store())
			JError::raiseError(500, $escrow->getError());
	
		$escrow->checkin();
	
	
		//Trigger the plugin event to feed the activity - buyer pick freelancer
		//JPluginHelper::importPlugin('system', 'jblancefeeds');
		//$dispatcher = JDispatcher::getInstance();
		//$dispatcher->trigger('onBuyerReleaseEscrow', array($escrow->from_id, $escrow->to_id, $escrow->project_id));
	
		$msg = JText::_('COM_JBLANCE_ESCROW_PAYMENT_CANCELLED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admproject&layout=showescrow';
		$this->setRedirect($link, $msg);
	}
/**
 ================================================================================================================
 SECTION : Reporting - action, remove, purge
 ================================================================================================================
 */
	function reportAction(){
		// Check for request forgeries
		JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		
		// Initialise variables.
		$app 	= JFactory::getApplication();
		$db		= JFactory::getDbo();
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid);
		
		$report = JTable::getInstance('report', 'Table');
		$report->load($cid[0]);
		
		$reportHelper = JblanceHelper::get('helper.report');		// create an instance of the class ReportHelper
		
		$method		= explode(',', $report->method);
		$args		= $report->params;
		
		$result = $reportHelper->$method[1]($args);
		
		//set the status to 'processed'
		$report->status = 1;
		$report->store();
		
		$msg	= $result['action'];
		$link	= 'index.php?option=com_jblance&view=admproject&layout=showreporting';
		$this->setRedirect($link, $msg);
	}
	
	function removeReporting(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$app 	= JFactory::getApplication();
		$db 	= JFactory::getDbo();
		$report = JTable::getInstance('report', 'Table');
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid, array(0));
		$delCount = 0;
		
		if(count($cid)){
			for($i = 0; $i < count($cid); $i++){
				$curr_bid = $cid[$i];
				
				//remove from report table
				$report->load($curr_bid);
				$report->delete($curr_bid);
				
				//remove from reporter table
				$db->setQuery("DELETE FROM #__jblance_report_reporter WHERE report_id=".$curr_bid);
				if(!$db->execute()){
					JError::raiseError($db->getErrorNum(), $db->getErrorMsg());
				}
				$delCount++;
			}
		}
		$msg	= $delCount.' '.JText::_('COM_JBLANCE_REPORTING_DELETED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admproject&layout=showreporting';
		$this->setRedirect($link, $msg);
	}
	
	function purgeReporting(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$app = JFactory::getApplication();
		$db	= JFactory::getDbo();
		$delCount = 0;
		
		$query = 'SELECT * FROM #__jblance_report WHERE status=1';
		$db->setQuery($query);
		$reports = $db->loadObjectList();
		
		for($i = 0; $i < count($reports); $i++){
			$report = $reports[$i];
			
			//remove from report table
			$db->setQuery("DELETE FROM #__jblance_report WHERE id=".$report->id);
			if(!$db->execute()){
				JError::raiseError($db->getErrorNum(), $db->getErrorMsg());
			}
			
			//remove from reporter table
			$db->setQuery("DELETE FROM #__jblance_report_reporter WHERE report_id=".$report->id);
			if(!$db->execute()){
				JError::raiseError($db->getErrorNum(), $db->getErrorMsg());
			}
			$delCount++;
		}
		$msg	= $delCount.' '.JText::_('COM_JBLANCE_REPORTING_PURGED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admproject&layout=showreporting';
		$this->setRedirect($link, $msg);
		
	}
	
	function cancelReporting(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		$app = JFactory::getApplication();
		$msg = '';
		$link = 'index.php?option=com_jblance&view=admproject&layout=showreporting';
		$this->setRedirect($link, $msg);
	}
/**
 ================================================================================================================
 SECTION : Private Messages - process
 ================================================================================================================
 */	
	//2.Hide/remove Message
	function processMessage(){
		// Check for request forgeries
		JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		
		JblanceHelper::processMessage();
	}
	
	function manageMessage(){
		// Check for request forgeries
		JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		
		$response = array();
		$app  	= JFactory::getApplication();
		$db 	= JFactory::getDbo();
		$msgid 	= $app->input->get('msgid', '', 'int');
		$text 	= $app->input->get('text', '', 'string');
		$type 	= $app->input->get('type', '', 'string');
		$attach	= $app->input->get('attachment', '', 'string');
	
		if($type == 'message')
			$query = "UPDATE #__jblance_message SET message=".$db->quote($text)." WHERE id=".$db->quote($msgid);
		elseif($type == 'subject')
			$query = "UPDATE #__jblance_message SET subject=".$db->quote($text)." WHERE id=".$db->quote($msgid)." OR parent=".$db->quote($msgid);
		
		$db->setQuery($query);
		$res = $db->execute();
		
		//remove the attachment
		if(!empty($attach)){
			$attFile = explode(";", $attach);
			$fileName = $attFile[1];
			$delete = JBMESSAGE_PATH.'/'.$fileName;
			if(JFile::exists($delete))
				unlink($delete);
			
			$query = "UPDATE #__jblance_message SET attachment='' WHERE id=".$db->quote($msgid);
			$db->setQuery($query);
			if($db->execute())
				$response['attachRemoved'] = 1;
		}
	
		if($res){
			$response['result'] = 'OK';
		}
		else
			$response['result'] = 'NO';
		
		echo json_encode($response); exit;
	}
	
	function approveMessage(){
		// Check for request forgeries
		JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		
		$app  	= JFactory::getApplication();
		$db 	= JFactory::getDbo();
		$msgid 	= $app->input->get('msgid', '', 'int');
		$jbmail = JblanceHelper::get('helper.email');		// create an instance of the class EmailHelper
	
		$query = "UPDATE #__jblance_message SET approved=1 WHERE id=".$db->quote($msgid);//." OR parent=".$msgid;
		$db->setQuery($query);
		$res = $db->execute();
	
		//sent pm notification
		$jbmail->sendMessageNotification($msgid);
		
		if($res)
			echo 'OK';
		else
			echo 'NO';
		exit;
	}
	
	function removeForum(){
		// Check for request forgeries
		JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
	
		$app  	= JFactory::getApplication();
		$db 	= JFactory::getDbo();
		$forumid= $app->input->get('forumid', '', 'int');
	
		$query = "DELETE FROM #__jblance_forum WHERE id=".$db->quote($forumid);
		$db->setQuery($query);
		if($db->execute())
			echo 'OK';
		else
			echo 'NO';
		exit;
	}
	
	function removeTransaction(){
		// Check for request forgeries
		JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
	
		$app  	= JFactory::getApplication();
		$db 	= JFactory::getDbo();
		$transid= $app->input->get('transid', '', 'int');
	
		$query = "DELETE FROM #__jblance_transaction WHERE id=".$db->quote($transid);
		$db->setQuery($query);
		if($db->execute())
			echo 'OK';
		else
			echo 'NO';
		exit;
	}
	
	function processBid(){
		// Check for request forgeries
		JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
	
		$app  	= JFactory::getApplication();
		$db 	= JFactory::getDbo();
		$bidId	= $app->input->get('bidid', '', 'int');
	
		$query = "DELETE FROM #__jblance_bid WHERE id=".$db->quote($bidId);
		$db->setQuery($query);
		if($db->execute())
			echo 'OK';
		else
			echo 'NO';
		exit;
	}
	
	//download file
	function download(){
		// Check for request forgeries
		JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
	
		JBMediaHelper::downloadFile();
	}
	
	/* Misc Functions */
	
	function uploadPicture(){
		// Check for request forgeries
		JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		
		JBMediaHelper::uploadPictureMedia();
	}
	
	function removePicture(){
		// Check for request forgeries
		JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		
		JBMediaHelper::removePictureMedia();
	}
	
	function cropPicture(){
		// Check for request forgeries
		JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		
		JBMediaHelper::cropPictureMedia();
	}
	
	function serviceuploadfile(){
		// Check for request forgeries
		JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		
		JBMediaHelper::serviceuploadfile();
	}
	
	function removeServiceFile(){
		// Check for request forgeries
		JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		
		JBMediaHelper::removeServiceFile();
	}
	
	function getLocationAjax(){
		// Check for request forgeries
		JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
	
		JblanceHelper::getLocation();
	}
	
	function display($cachable = false, $urlparams = false){
		$document = JFactory :: getDocument();
		$viewName = JRequest :: getVar('view', 'admproject');
		$layoutName = JRequest :: getVar('layout', 'dashboard');
		$viewType = $document->getType();
		$model = $this->getModel('admproject', 'JblanceModel');
		$view = $this->getView($viewName, $viewType);
		if (!JError :: isError($model)){
			$view->setModel($model, true);
		}
		$view->setLayout($layoutName);
		$view->display();
	}
	//set featured
	
	function featureuser()
	{
	$uid=JRequest::getVar('uid','');
	$app=JFactory::getApplication();
	$feature=JRequest::getVar('f','');
	$row=JTable::getInstance('jbuser','Table');
	$row->load(array('user_id'=>$uid));
	$msg=$feature==1?$row->biz_name.' successfully set featured':$row->biz_name.' successfully set unfeatured';
	$row->featured=$feature;
	if($row->store())
	{
	$app->redirect('index.php?option=com_jblance&view=admproject&layout=showuser',$msg);
	}
	}

}