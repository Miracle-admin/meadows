<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	14 March 2012
 * @file name	:	controllers/admconfig.php
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
class JblanceControllerAdmconfig extends JControllerAdmin {

	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct(){
		parent::__construct();
	
		// Register Extra tasks
		//following extra tasks has bee registered because they point to the default core functions instead of our own function , kind of override ;)
		$this->registerTask('publish', 'publish');
		$this->registerTask('unpublish', 'unpublish');
	}
	
	public function publish(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$app  = JFactory::getApplication();
		$ctype = $app->input->get('ctype', '', 'string');
		$msg = JText::_('COM_JBLANCE_PUBLISHED_SUCCESSFULLY');
		if($ctype == 'usergroup'){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showusergroup';
			$this->jbPubUnpub(1, '#__jblance_usergroup', $link, $msg);
		}
		elseif($ctype == 'plan'){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showplan';
			$this->jbPubUnpub(1, '#__jblance_plan', $link, $msg);
		}
		elseif($ctype == 'paymode'){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showpaymode';
			$this->jbPubUnpub(1, '#__jblance_paymode', $link, $msg);
		}
		elseif($ctype == 'customfield'){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showcustomfield';
			$this->jbPubUnpub(1, '#__jblance_custom_field', $link, $msg);
		}
		elseif($ctype == 'category'){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showcategory';
			$this->jbPubUnpub(1, '#__jblance_category', $link, $msg);
		}
		elseif($ctype == 'budget'){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showbudget';
			$this->jbPubUnpub(1, '#__jblance_budget', $link, $msg);
		}
		elseif($ctype == 'duration'){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showduration';
			$this->jbPubUnpub(1, '#__jblance_duration', $link, $msg);
		}
		elseif($ctype == 'location'){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showlocation';
			$this->jbPubUnpub(1, '#__jblance_location', $link, $msg);
		}
		elseif($ctype == 'planbenefits'){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showplanbenefits';
			$this->jbPubUnpub(1, '#__jblance_planbenefits', $link, $msg);
		}
	}
	
	public function unpublish(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$app  = JFactory::getApplication();
		$ctype = $app->input->get('ctype', '', 'string');
		
		
		$msg = JText::_('COM_JBLANCE_UNPUBLISHED_SUCCESSFULLY');
		if($ctype == 'usergroup'){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showusergroup';
			$this->jbPubUnpub(0, '#__jblance_usergroup', $link, $msg);
		}
		elseif($ctype == 'plan'){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showplan';
			$this->jbPubUnpub(0, '#__jblance_plan', $link, $msg);
		}
		elseif($ctype == 'paymode'){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showpaymode';
			$this->jbPubUnpub(0, '#__jblance_paymode', $link, $msg);
		}
		elseif($ctype == 'customfield'){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showcustomfield';
			$this->jbPubUnpub(0, '#__jblance_custom_field', $link, $msg);
		}
		elseif($ctype == 'category'){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showcategory';
			$this->jbPubUnpub(0, '#__jblance_category', $link, $msg);
		}
		elseif($ctype == 'budget'){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showbudget';
			$this->jbPubUnpub(0, '#__jblance_budget', $link, $msg);
		}
		elseif($ctype == 'duration'){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showduration';
			$this->jbPubUnpub(0, '#__jblance_duration', $link, $msg);
		}
		elseif($ctype == 'location'){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showlocation';
			$this->jbPubUnpub(0, '#__jblance_location', $link, $msg);
		}
		elseif($ctype == 'planbenefits'){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showplanbenefits';
			$this->jbPubUnpub(0, '#__jblance_planbenefits', $link, $msg);
		}
	}
	
	public function saveOrderAjax(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$app 		= JFactory::getApplication();
		$ctype 		= $app->input->get('ctype', '', 'string');
		$fieldfor	= $app->input->get('fieldfor', 0, 'int');
		$cid 		= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid);
	 
	    if($ctype == 'usergroup'){
			$row = JTable::getInstance('jbusergroup', 'Table');
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showusergroup';
		}
		elseif( $ctype == 'plan'){
			$row = JTable::getInstance('plan', 'Table');
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showplan';
		}
		elseif( $ctype == 'paymode'){
			$row = JTable::getInstance('paymode', 'Table');
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showpaymode';
		}
		elseif( $ctype == 'customfield'){
			$row = JTable::getInstance('custom', 'Table');
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showcustomfield';
		}
		elseif($ctype == 'category'){
			$row = JTable::getInstance('category', 'Table');
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showcategory';
		}
		elseif($ctype == 'budget'){
			$row = JTable::getInstance('budget', 'Table');
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showbudget';
		}
		elseif($ctype == 'duration'){
			$row = JTable::getInstance('duration', 'Table');
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showduration';
		}
		elseif($ctype == 'location'){
			$row = JTable::getInstance('location', 'Table');
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showlocation';
		}
		elseif($ctype == 'planbenefits'){
			$row = JTable::getInstance('planbenefits', 'Table');
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showlocation';
		}
	
		// location table uses nested set hierarchy model as against adjacency list model. Therefore, 'lft' & 'rgt' is used instead of 'ordering' column
		if($ctype == 'location' || $ctype == 'planbenefits'){
			$order 		= $app->input->post->get('order', array(), 'array');
			JArrayHelper::toInteger($order);
			
			$res = $row->saveorder($cid, $order);
		}
		else {
			$total		= count($cid);
			$groupings	= array();
			$order		= $app->input->get('order', array(0), 'array');
			JArrayHelper::toInteger($order, array(0));
			
			// update ordering values
			for($i=0; $i < $total; $i++){
				$row->load((int)$cid[$i]);
				// track parents
				$groupings[] = $row->parent;
				if($row->ordering != $order[$i]){
					$row->ordering = $order[$i];
					if(!$row->store()){
						JError::raiseError(500, $db->getErrorMsg());
					}
				}
			}
			
			if($ctype == 'category'){
				// execute updateOrder for each parent group
				$groupings = array_unique($groupings);
				foreach ($groupings as $group){
					$res = $row->reorder('parent = '.(int)$group.' AND published >=0');
				}
			}
			elseif($ctype == 'fieldorder' || $ctype == 'customfield'){
				// execute updateOrder for each parent group
				$groupings = array_unique($groupings);
				foreach ($groupings as $group){
					$res = $row->reorder('field_for='.$fieldfor.' AND parent = '.(int)$group.' AND published >=0');
				}
			}
			else {
				$res = $row->reorder();
			}
		}
	
		if($res)
			echo "1";

		// Close the application
		JFactory::getApplication()->close();
	}
	
	public function required(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$app  = JFactory::getApplication();
		$ctype = $app->input->get('ctype', '', 'string');
		$msg = JText::_('COM_JBLANCE_FIELD_SET_REQUIRED');
		if( $ctype == 'fieldorder' ){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=fieldorder';
			$this->jbReqUnrequired(1, '#__jblance_fieldorder', $link, $msg);
		}
		elseif($ctype == 'customfield'){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showcustomfield';
			$this->jbReqUnrequired(1, '#__jblance_custom_field', $link, $msg);
		}
	}
	
	public function unrequired(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$app  = JFactory::getApplication();
		$ctype = $app->input->get('ctype', '', 'string');
		$msg = JText::_('COM_JBLANCE_FIELD_SET_UNREQUIRED');
		if( $ctype == 'fieldorder' ){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=fieldorder';
			$this->jbReqUnrequired(0, '#__jblance_fieldorder', $link, $msg);
		}
		if( $ctype == 'customfield' ){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showcustomfield';
			$this->jbReqUnrequired(0, '#__jblance_custom_field', $link, $msg);
		}
	}
	
	/**
	 ================================================================================================================
	 SECTION : Configuration:Config - save, cancel
	 ================================================================================================================
	 */
	function saveConfig(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		// Initialize variables
		$app = JFactory::getApplication();
		$db		= JFactory::getDbo();
		$row	= JTable::getInstance('config', 'Table');
		$post 	= $app->input->post->getArray();
		$params	= $app->input->get('params', null, 'array');
	
		// Build parameter string
		$registry = new JRegistry();
		$registry->loadArray($params);
		$row->params = $registry->toString();
		unset($post['params']);
	
		if(!$row->save($post)){
			JError::raiseError(500, $row->getError());
		}
	
		$msg	= JText::_('COM_JBLANCE_COMPONENT_SETTINGS_SAVED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=config';
		$this->setRedirect($link, $msg);
	}
	
	function cancelConfig(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$app = JFactory::getApplication();
		$msg ='';
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=configpanel';
		$this->setRedirect($link, $msg);
	}	
	
	/**
	 ================================================================================================================
	 SECTION : Configuration: User Group - new, remove, save, cancel
	 ================================================================================================================
	 */
	function newUserGroup(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		JRequest :: setVar('view', 'admconfig');
		JRequest :: setVar('hidemainmenu', 1);
		JRequest :: setVar('layout', 'editusergroup');
		$this->display();
	}
	
	function removeUserGroup(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		// Initialize variables
		$app 	= JFactory::getApplication();
		$db 	= JFactory::getDbo();
		$row	= JTable::getInstance('jbusergroup', 'Table');
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid);
		$delCount = 0;
	
		if(count($cid)){
			$count_ketemu = 0;
			for($i=0; $i<count($cid); $i++){
				$curr_bid = $cid[$i];
	
				$query ="SELECT COUNT(*) FROM #__jblance_plan WHERE ug_id=$curr_bid";
				$db->setQuery($query);
				$find_1 = $db->loadResult();
	
				$query ="SELECT COUNT(*) FROM #__jblance_user WHERE ug_id=$curr_bid";
				$db->setQuery($query);
				$find_2 = $db->loadResult();
	
				if($find_1 > 0 || $find_2 > 0){
					$ketemu = 1;
				}
				if($find_1 == 0 && $find_2 == 0){
					$row->delete($curr_bid);
					$delCount++;
				}
				if($ketemu > 0){
					$count_ketemu++;
				}
			}
			if($count_ketemu > 0){
				$app->enqueueMessage(JText::sprintf('COM_JBLANCE_CANNOT_DELETE_DATA_DUE_TO_TABLE_LINKING', JText::_('COM_JBLANCE_USER_GROUP')), 'error');
			}
		}
		$msg	= $delCount.' '.JText::_('COM_JBLANCE_USER_GROUP_DELETED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showusergroup';
		$this->setRedirect($link, $msg);
	}
	
	function saveUserGroup(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		// Initialize variables
		$app 	= JFactory::getApplication();
		$db		= JFactory::getDbo();
		$row	= JTable::getInstance('jbusergroup', 'Table');
		$post 	= $app->input->post->getArray();
		$id		= $app->input->get('id' , 0 , 'int');
		$fields	= $app->input->get('fields', '', 'array');
		$tmpParents	= $app->input->get('parents', '', 'array');
		$isNew	= ($id == 0) ? true : false;
	
		$post['description']  = $app->input->get('description', '', 'RAW');
	
		//set the Joomla user group
		$joomla_ug_id 	= $app->input->get('joomla_ug_id', '', 'array');
		if(count($joomla_ug_id) > 0 && !(count($joomla_ug_id) == 1 && empty($joomla_ug_id[0]))){
			$ugroup_id = implode(',', $joomla_ug_id);
		}
		elseif($joomla_ug_id[0] == 0){
			$ugroup_id = 2;	//default is registered
		}
		
		$post['joomla_ug_id'] = $ugroup_id;
		
		$params	= $app->input->get('params', null, 'array');
	
		// Build parameter string
		$registry = new JRegistry();
		$registry->loadArray($params);
		$row->params = $registry->toString();
		unset($post['params']);
	
		if(!$row->save($post)){
			JError::raiseError(500, $row->getError());
		}
	
		// Since it would be very tedious to check if previous fields were enabled or disabled.
		// We delete all existing mapping and remap it again to ensure data integrity.
		if(!$isNew && !empty($fields)){
			$row->deleteChilds();
		}
	
		if(!empty($fields)){
			$parents = array();
	
			// We need to unique the parents first.
			foreach($fields as $id){
				$customFields	= JTable::getInstance('custom', 'Table');
				$customFields->load($id);
	
				// Need to only
				$parent	= $customFields->getCurrentParentId();
	
				if(in_array($parent, $tmpParents)){
					$parents[]	= $parent;
				}
			}
			$parents	= array_unique($parents);
	
			$fields		= array_merge($fields, $parents);
	
			foreach($fields as $id){
				$field				= JTable::getInstance('UsergroupField' , 'Table');
				$field->parent		= $row->id;
				$field->field_id	= $id;
	
				$field->store();
			}
		}
		
		// Enque message to warn that the newly created user group should have default plan
		if($isNew){
			$link_plan = 'index.php?option=com_jblance&view=admconfig&layout=showplan';
			$app->enqueueMessage(JText::sprintf('COM_JBLANCE_WARNING_TO_CREATE_DEFAULT_PLAN_FOR_USERGROUP', $link_plan), 'warning');
		}
	
		$msg	= JText::_('COM_JBLANCE_USER_GROUP_SAVED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showusergroup';
		$this->setRedirect($link, $msg);
	}
	
	function cancelUserGroup(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$app = JFactory::getApplication();
		$msg = '';
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showusergroup';
		$this->setRedirect($link, $msg);
	}
	
	/**
	 ================================================================================================================
	 SECTION : Configuration:Plan - new, remove, save, cancel, show, setplandefault
	 ================================================================================================================
	 */
	function newPlan(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		JRequest :: setVar('view', 'admconfig');
		JRequest :: setVar('hidemainmenu', 1);
		JRequest :: setVar('layout', 'editplan');
		$this->display();
	}
	
	function removePlan(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		// Initialize variables
		$app 	= JFactory::getApplication();
		$db 	= JFactory::getDbo();
		$row	= JTable::getInstance('plan', 'Table');
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid);
		$ketemu = 0;
		$find_1 = $find_2 = 0;
		$delCount = 0;
	
	    if(count($cid))
		{
		if(in_array(5,$cid))
		$app->redirect(JURI::root()."administrator/index.php?option=com_jblance&view=admconfig&layout=showplan","Company named plan can never be deleted",'error');
		}
		
		if(count($cid)){
			$count_ketemu = 0;
			for($i=0; $i < count($cid); $i++){
				$curr_bid = $cid[$i];
	
				$query = "SELECT COUNT(*) FROM  #__jblance_plan_subscr ".
						 "WHERE plan_id = $curr_bid";
				$db->setQuery($query);
				$find_1 = $db->loadResult();
	
				$row->load($curr_bid);
				/* if($row->default_plan){
					$find_2 = 1;		//default plan cannot be deleted.
					$app->enqueueMessage(JText::sprintf('COM_JBLANCE_PLAN_DEFAULT_CANNOT_BE_DELETED', $row->id), 'error');
				} */
	
				if($find_1 > 0 || $find_2 > 0){
					$ketemu = 1;
				}
				if($find_1 == 0 && $find_2 == 0){
					$row->delete($curr_bid);
					$delCount++;
				}
				if($ketemu > 0){
					$count_ketemu++;
				}
			}
			if($count_ketemu > 0){
				$app->enqueueMessage(JText::sprintf('COM_JBLANCE_CANNOT_DELETE_DATA_DUE_TO_TABLE_LINKING', JText::_('COM_JBLANCE_PLAN')), 'error');
			}
		}
		$msg	= $delCount.' '.JText::_('COM_JBLANCE_PLAN_DELETED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showplan';
		$this->setRedirect($link, $msg);
	}
	
	function savePlan(){
	    
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		// Initialize variables
		$app 	= JFactory::getApplication();
		$db		= JFactory::getDbo();
		$row	= JTable::getInstance('plan', 'Table');
		$post 	= $app->input->post->getArray();
		$tagged = array(13,5);
		$btPlan = JblanceHelper::getBraintreePlan($post['pidbt']);
		
		if(count($btPlan)==0 && !in_array($post['id'],$tagged))
		{
		$msg	= JText::_("As per the response returned by the payment gateway plan with id. ".$post['pidbt']." doesn't exist.");
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=editplan&cid[]='.$post['id'];
		$app->redirect($link, $msg,"error");
		
		}
		
		
		
		$post['description'] = $app->input->get('description', '', 'RAW');
		$params	= $app->input->get('params', null, 'array');
		$post['name']=trim($post['name']);
		
		$qexists="SELECT name FROM #__jblance_plan WHERE name =".$db->quote($db->escape($post['name']));
		$db->setQuery($qexists);
		$aExists=$db->loadObject();
		if($post['id']=="" && $aExists->name == $post['name']){$app->redirect("index.php?option=com_jblance&view=admconfig&layout=showplan","Plan with the same name already exists.",'error');}
		// Build parameter string
		$registry = new JRegistry();
		$registry->loadArray($params);
		$row->params = $registry->toString();
		unset($post['params']);
	
	    $row->merchantId            =    $btPlan['merchantId'];      
        $row->billingDayOfMonth     =    $btPlan['billingDayOfMonth'];     
        $row->billingFrequency      =    $btPlan['billingFrequency'];
        $row->currencyIsoCode       =    $btPlan['currencyIsoCode'];
        $row->bt_description        =    $btPlan['description'];          
        $row->numberOfBillingCycles =    $btPlan['numberOfBillingCycles'];  
        $row->price_bt              =    $btPlan['price'];          
        $row->trialDuration         =    $btPlan['trialDuration'];
        $row->trialDurationUnit     =    $btPlan['trialDurationUnit'];
        $row->trialPeriod           =    $btPlan['trialPeriod'];
        $row->createdAt             =    $btPlan['createdAt'];
        $row->updatedAt             =    $btPlan['updatedAt'];
	
	
	
	
		if(!$row->save($post)){
			JError::raiseError(500, $row->getError());
		}
	
	
	    $id=$row->id;
	    $query="SELECT * FROM #__jblance_planbenefits WHERE parent_id=1 AND published=1";
		$db->setQuery($query);
		$parents=$db->loadObjectList();
		$bfields=array();
		foreach($parents as $par)
		{
		$query="SELECT * FROM #__jblance_planbenefits WHERE parent_id=".$par->id." AND published=1";
		$db->setQuery($query);
		$child=$db->loadObjectList();
		foreach($child as $chil)
		$bfields[]=$chil->lft.'-'.$chil->ben_type.'-'.$par->id.'-'.$chil->id;
		}
	
		foreach($bfields as $bf)
		{
		
	
		//add enteries
		$bfarr=explode('-',$bf);
		$order=$bfarr[0];
		$type=$bfarr[1];
		$ben_parent_id=$bfarr[2];
		$ben_id=$bfarr[3];
		$state=$type=="custom"?1:$params[$bf];
		$custom=$type=="custom"?$params[$bf]:'';
		$benDet	= JTable::getInstance('planbenefits', 'Table');
		$benDet->load($ben_id);
		
		$query="SELECT * FROM #__jblance_benefitaccess WHERE ben_id=".$ben_id." AND plan_id=".$id;
		$db->setQuery($query);
		$db->query();
        $numRows = $db->getNumRows();
		
		if($numRows==1)
		{
		$query="UPDATE  #__jblance_benefitaccess SET  
       `type` =  '".$type."',
       `state` =  '".$state."',
       `custom` =  '".$custom."',
       `sort` =  '".$order."' WHERE ben_id=".$ben_id." AND plan_id= ".$id;
		}
		else
		{
		$query="INSERT INTO #__jblance_benefitaccess (ben_id,plan_id,ben_parent_id,type,state,custom,sort) VALUES(".$ben_id.", ".$id.", ".$ben_parent_id.",'".$type."',".$state.",'".$custom."',".$order.")";
		}
    
	   if($type=='checkmark')
		{
		   $plan=$post['name'];
		   $lg=$this->_getLevelGroupId($benDet->title,$plan);
		   $state=$params[$bf];
		   $level =  $lg[0];
		   $gid   =  $lg[1];
		   $jDecodedRule = json_decode($level->rules);
		
		   
		   if($state==0 && in_array(intval($gid->id), $jDecodedRule))
		   {
			$jDecodedRule = array_diff($jDecodedRule,array(intval($gid->id)));
			$newRules=array_values( (array)$jDecodedRule );
			$level->rules = json_encode($newRules);
			
			$level->store(); 
		   }
		   elseif($state==1 && !in_array(intval($gid->id), $jDecodedRule))
		   {
		   
			$jDecodedRule[]= intval($gid->id);
			$level->rules = json_encode($jDecodedRule);
			$level->store();
		   }
	   
		}
	$db->setQuery($query);
    $db->execute();
	  
	 
		
		}
	


	
		$msg	= JText::_('COM_JBLANCE_PLAN_SAVED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showplan';
		$this->setRedirect($link, $msg);
	}
	
	//save without cancel
	
		function savePlanwc(){
	    
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		// Initialize variables
		$app 	= JFactory::getApplication();
		$db		= JFactory::getDbo();
		$row	= JTable::getInstance('plan', 'Table');
		$post 	= $app->input->post->getArray();
		$tagged = array(13,5);
		$btPlan = JblanceHelper::getBraintreePlan($post['pidbt']);
	
		if(count($btPlan)==0 && !in_array($post['id'],$tagged))
		{
		$msg	= JText::_("As per the response returned by the payment gateway plan with id. ".$post['pidbt']." doesn't exist.");
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=editplan&cid[]='.$post['id'];
		$app->redirect($link, $msg,"error");
		
		}
		
		$post['description'] = $app->input->get('description', '', 'RAW');
		$params	= $app->input->get('params', null, 'array');
		$post['name']=trim($post['name']);
		
		$qexists="SELECT name FROM #__jblance_plan WHERE name =".$db->quote($db->escape($post['name']));
		$db->setQuery($qexists);
		$aExists=$db->loadObject();
		if($post['id']=="" && $aExists->name == $post['name']){$app->redirect("index.php?option=com_jblance&view=admconfig&layout=showplan","Plan with the same name already exists.",'error');}
		// Build parameter string
		$registry = new JRegistry();
		$registry->loadArray($params);
		$row->params = $registry->toString();
		
		unset($post['params']);
	    
		$post['days'] = $btPlan['billingFrequency'];
        $post['days_type'] = 'months';
		$post['price'] = $btPlan['price'];
		
		
        $row->merchantId            =    $btPlan['merchantId'];      
        $row->billingDayOfMonth     =    $btPlan['billingDayOfMonth'];     
        $row->billingFrequency      =    $btPlan['billingFrequency'];
        $row->currencyIsoCode       =    $btPlan['currencyIsoCode'];
        $row->bt_description        =    $btPlan['description'];          
        $row->numberOfBillingCycles =    $btPlan['numberOfBillingCycles'];  
        $row->price_bt              =    $btPlan['price'];          
        $row->trialDuration         =    $btPlan['trialDuration'];
        $row->trialDurationUnit     =    $btPlan['trialDurationUnit'];
        $row->trialPeriod           =    $btPlan['trialPeriod'];
        $row->createdAt             =    $btPlan['createdAt'];
        $row->updatedAt             =    $btPlan['updatedAt'];
		
		
		if(!$row->save($post)){
			JError::raiseError(500, $row->getError());
		}
	
	    $id=$row->id;
	    $query="SELECT * FROM #__jblance_planbenefits WHERE parent_id=1 AND published=1";
		$db->setQuery($query);
		$parents=$db->loadObjectList();
		$bfields=array();
		foreach($parents as $par)
		{
		$query="SELECT * FROM #__jblance_planbenefits WHERE parent_id=".$par->id." AND published=1";
		$db->setQuery($query);
		$child=$db->loadObjectList();
		foreach($child as $chil)
		$bfields[]=$chil->lft.'-'.$chil->ben_type.'-'.$par->id.'-'.$chil->id;
		}
	
		foreach($bfields as $bf)
		{
		
	
		//add enteries
		$bfarr=explode('-',$bf);
		$order=$bfarr[0];
		$type=$bfarr[1];
		$ben_parent_id=$bfarr[2];
		$ben_id=$bfarr[3];
		$state=$type=="custom"?1:$params[$bf];
		$custom=$type=="custom"?$params[$bf]:'';
		$benDet	= JTable::getInstance('planbenefits', 'Table');
		$benDet->load($ben_id);
		
		$query="SELECT * FROM #__jblance_benefitaccess WHERE ben_id=".$ben_id." AND plan_id=".$id;
		$db->setQuery($query);
		$db->query();
        $numRows = $db->getNumRows();
		
		if($numRows==1)
		{
		$query="UPDATE  #__jblance_benefitaccess SET  
       `type` =  '".$type."',
       `state` =  '".$state."',
       `custom` =  '".$custom."',
       `sort` =  '".$order."' WHERE ben_id=".$ben_id." AND plan_id= ".$id;
		}
		else
		{
		$query="INSERT INTO #__jblance_benefitaccess (ben_id,plan_id,ben_parent_id,type,state,custom,sort) VALUES(".$ben_id.", ".$id.", ".$ben_parent_id.",'".$type."',".$state.",'".$custom."',".$order.")";
		}
    
	   if($type=='checkmark')
		{
		   $plan=$post['name'];
		   $lg=$this->_getLevelGroupId($benDet->title,$plan);
		   $state=$params[$bf];
		   $level =  $lg[0];
		   $gid   =  $lg[1];
		   $jDecodedRule = json_decode($level->rules);
		
		   
		   if($state==0 && in_array(intval($gid->id), $jDecodedRule))
		   {
			$jDecodedRule = array_diff($jDecodedRule,array(intval($gid->id)));
			$newRules=array_values( (array)$jDecodedRule );
			$level->rules = json_encode($newRules);
			
			$level->store(); 
		   }
		   elseif($state==1 && !in_array(intval($gid->id), $jDecodedRule))
		   {
		   
			$jDecodedRule[]= intval($gid->id);
			$level->rules = json_encode($jDecodedRule);
			$level->store();
		   }
	   
		}
	$db->setQuery($query);
    $db->execute();
	  
	 
		
		}
	


	
		$msg	= JText::_('COM_JBLANCE_PLAN_SAVED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=editplan&cid[]='.$post['id'];
		$this->setRedirect($link, $msg);
	}
	
	//get accesslevelid with title
	protected function _getLevelGroupId($tile,$gtile)
	{
	$db=JFactory::getDbo();
	$query="SELECT id FROM #__viewlevels WHERE title = ".$db->quote( $db->escape( $tile ), false );
	$db->setQuery($query);
	$vieId=$db->loadObject();
	$utable = JTable::getInstance('Viewlevel', 'JTable');
	$query="SELECT id FROM #__usergroups WHERE title = ".$db->quote( $db->escape( $gtile ), false );
	$db->setQuery($query);
	$Gid=$db->loadObject();
	$return=array();

	if($vieId && $Gid)
	{
	$utable->load($vieId->id);
	}
	$return[0]=$utable;
	$return[1]=$Gid;
	return $return;
	}
	
	function cancelPlan(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$app = JFactory::getApplication();
		$msg = '';
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showplan';
		$this->setRedirect($link, $msg);
	}
	
	function showPlan(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		JRequest :: setVar('view', 'admconfig');
		JRequest :: setVar('layout', 'showplan');
		$this->display();
	}
	
	function setPlanDefault(){
		// Check for request forgeries
		JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
	
		// Initialise variables.
		$app  	= JFactory::getApplication();
		$db		= JFactory::getDbo();
		$ug_id 	= $app->input->get('ug_id', 0, 'int');
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid);
	
		//set all the plans to undefault for the user group
		$query = "UPDATE #__jblance_plan SET default_plan = 0 WHERE ug_id =".$db->quote($ug_id);
		$db->setQuery($query);
		if(!$db->execute()){
			JError::raiseError($db->getErrorNum(), $db->getErrorMsg());
		}
	
		//now set the particular plan to be default
		$query = "UPDATE #__jblance_plan SET default_plan = 1 WHERE id = $cid[0] AND ug_id =".$db->quote($ug_id);
		$db->setQuery($query);
		if(!$db->execute()){
			JError::raiseError($db->getErrorNum(), $db->getErrorMsg());
		}
	
		$msg	= JText::_('COM_JBLANCE_PLAN_SET_DEFAULT_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showplan';
		$this->setRedirect($link, $msg);
	}
	
/**
 ================================================================================================================
 SECTION : Configuration:Payment Gateways - save, cancel
 ================================================================================================================
 */
	function savePaymode(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		// Initialize variables
		$app  	= JFactory::getApplication();
		$db		= JFactory::getDbo();
		$post 	= $app->input->post->getArray();
		$row	= JTable::getInstance('paymode', 'Table');
		$gateway = $app->input->get('gateway', '', 'string');
		$id		= $app->input->get('id', 0, 'int');
		$params	= $app->input->get('params', null, 'array');
		
		$registry = new JRegistry();
		$registry->loadArray($params);
		$row->params = $registry->toString();
		unset($post['params']);
		
		// save the changes
		if(!$row->save($post)){
			JError::raiseError(500, $row->getError());
		}
		$row->checkin();
	
		$msg	= JText::_('COM_JBLANCE_PAYMENT_GATEWAY_SAVED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showpaymode';
		$this->setRedirect($link, $msg);
	}
	
	function cancelPaymode(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$app = JFactory::getApplication();
		$msg = '';
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showpaymode';
		$this->setRedirect($link, $msg);
	}

/**
 ================================================================================================================
 SECTION : Custom Fields - newcustomgroup, newCustomField, remove, save, cancel
 ================================================================================================================
 */
	function newCustomGroup(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		JRequest :: setVar('view', 'admconfig');
		JRequest :: setVar('hidemainmenu', 1);
		JRequest :: setVar('layout', 'editcustomfield');
		JRequest :: setVar('type', 'group');
		$this->display();
	}
	
	function newCustomField(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		JRequest :: setVar('view', 'admconfig');
		JRequest :: setVar('hidemainmenu', 1);
		JRequest :: setVar('layout', 'editcustomfield');
		$this->display();
	}
	
	function removeCustomField(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		// Initialize variables
		$app = JFactory::getApplication();
		$db  = JFactory::getDbo();
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger( $cid );
	
		$cids = implode(',', $cid);
		$query = 'DELETE FROM #__jblance_custom_field WHERE id IN ('.$cids.')';
		$db->setQuery($query);
		if(!$db->execute()){
			JError::raiseError($db->getErrorNum(), $db->getErrorMsg());
		}
		// Remove custom field values too.
		$query = 'DELETE FROM #__jblance_custom_field_value WHERE fieldid IN ('.$cids.')';
		$db->setQuery($query);
		if(!$db->execute()){
			JError::raiseError($db->getErrorNum(), $db->getErrorMsg());
		}
	
		$msg	= JText::_('COM_JBLANCE_CUSTOM_FIELD_DELETED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showcustomfield';
		$this->setRedirect($link, $msg);
	}
	
	function saveCustomField(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		// Initialize variables
		$app 		= JFactory::getApplication();
		$db	 		= JFactory::getDbo();
		$row 		= JTable::getInstance('custom', 'Table');
		$post 		= $app->input->post->getArray();
		$required 	= (!empty($post['required']))? $post['required'] : 0;
		$published 	= (!empty($post['published']))? $post['published'] : 0;
		$parent 	= ($post['type'] == 'group')? 0 : $post['parent'];
		$id			= $app->input->get('id' , 0 , 'int');
		$isNew		= ($id == 0) ? true : false;
	
		$row->required = $required;
		$row->published = $published;
		$row->parent = $parent;
	
		if($post['field_type'] == 'Select' && $post['value_type'] == 'database')
			$post['value'] = $post['databaseValues'];
		else
			$post['value'] = $post['customValues'];
	
		if(!$row->save($post)){
			JError::raiseError(500, $row->getError());
		}
		
		if($isNew && $post['field_for'] == 'profile'){
			$link_usergroup	= 'index.php?option=com_jblance&view=admconfig&layout=showusergroup';
			$app->enqueueMessage(JText::sprintf('COM_JBLANCE_INFO_TO_ASSIGN_CUSTOMFIELD_TO_USERGROUP', $link_usergroup), 'warning');
		}
	
		$msg	= JText::_('COM_JBLANCE_CUSTOM_FIELD_SAVED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showcustomfield';
		$this->setRedirect($link, $msg);
	}
	
	function cancelCustomField(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$app = JFactory::getApplication();
		$msg = '';
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showcustomfield';
		$this->setRedirect($link, $msg);
	}
	
/**
 ================================================================================================================
 SECTION : Configuration:Email Templates - save
 ================================================================================================================
 */
	function saveEmailTemplate(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		// Initialize variables
		$app 	= JFactory::getApplication();
		$db		= JFactory::getDbo();
		$tempfor = $app->input->get('templatefor', 'subscr-pending', 'string');
		$row	= JTable::getInstance('emailtemp', 'Table');
		$post 	= $app->input->post->getArray();
		$post['body'] = $app->input->get('body', '', 'RAW');
	
		if(!$row->save($post)){
			JError::raiseError(500, $row->getError());
		}
	
		$msg	= JText::_('COM_JBLANCE_EMAIL_TEMPLATE_SAVED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=emailtemplate&tempfor='.$tempfor;
		$this->setRedirect($link, $msg);
	}

/**
 ================================================================================================================
 SECTION : Configuration:Category - new, remove, save, cancel
 ================================================================================================================
 */
	function newCategory(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		JRequest :: setVar('view', 'admconfig');
		JRequest :: setVar('hidemainmenu', 1);
		JRequest :: setVar('layout', 'editcategory');
		$this->display();
	}
	
	function removeCategory(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		// Initialize variables
		$app 		= JFactory::getApplication();
		$db  		= JFactory::getDbo();
		$delCount 	= 0;
		$cid 		= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid);
	
		$cids = implode(',', $cid);
		$query = 'DELETE FROM #__jblance_category WHERE id IN ('.$cids.')';
		$db->setQuery($query);
		if(!$db->execute()){
			JError::raiseError($db->getErrorNum(), $db->getErrorMsg());
		}
		$delCount = $db->getAffectedRows();
		
		$msg	= $delCount.' '.JText::_('COM_JBLANCE_CATEGORY_DELETED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showcategory';
		$this->setRedirect($link, $msg);
	}
	
	function saveCategory(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		// Initialize variables
		$app 	= JFactory::getApplication();
		$db		= JFactory::getDbo();
		$row	= JTable::getInstance('category', 'Table');
		$post 	= $app->input->post->getArray();
	
		if(!$row->save($post)){
			JError::raiseError(500, $row->getError());
		}
	
		$msg	= JText::_('COM_JBLANCE_CATEGORY_SAVED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showcategory';
		$this->setRedirect($link, $msg);
	}
	
	function cancelCategory(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$app = JFactory::getApplication();
		$msg = '';
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showcategory';
		$this->setRedirect($link, $msg);
	}
	
/**
 ================================================================================================================
 SECTION : Configuration:Budget - new, remove, save, cancel
 ================================================================================================================
 */
	
	function newBudget(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		JRequest :: setVar('view', 'admconfig');
		JRequest :: setVar('hidemainmenu', 1);
		JRequest :: setVar('layout', 'editbudget');
		$this->display();
	}
	
	function removeBudget(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		// Initialize variables
		$app = JFactory::getApplication();
		$db  = JFactory::getDbo();
		$delCount = 0;
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid);
	
		$cids = implode(',', $cid);
		$query = 'DELETE FROM #__jblance_budget WHERE id IN ('.$cids.')';
		$db->setQuery($query);
		if(!$db->execute()){
			JError::raiseError($db->getErrorNum(), $db->getErrorMsg());
		}
		$delCount = $db->getAffectedRows();
	
		$msg	= $delCount.' '.JText::_('COM_JBLANCE_BUDGET_RANGE_DELETED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showbudget';
		$this->setRedirect($link, $msg);
	}
	
	function saveBudget(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		// Initialize variables
		$app 	= JFactory::getApplication();
		$db		= JFactory::getDbo();
		$row	= JTable::getInstance('budget', 'Table');
		$post 	= $app->input->post->getArray();
	
		if(!$row->save($post)){
			JError::raiseError(500, $row->getError());
		}
	
		$msg	= JText::_('COM_JBLANCE_BUDGET_RANGE_SAVED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showbudget';
		$this->setRedirect($link, $msg);
	}
	
	function cancelBudget(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$app = JFactory::getApplication();
		$msg = '';
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showbudget';
		$this->setRedirect($link, $msg);
	}
	
/**
 ================================================================================================================
 SECTION : Configuration: Project Duration - new, remove, save, cancel
 ================================================================================================================
 */
	function newDuration(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		JRequest :: setVar('view', 'admconfig');
		JRequest :: setVar('hidemainmenu', 1);
		JRequest :: setVar('layout', 'editduration');
		$this->display();
	}
	
	function removeDuration(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		// Initialize variables
		$app = JFactory::getApplication();
		$db  = JFactory::getDbo();
		$delCount = 0;
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid);
	
		$cids = implode(',', $cid);
		$query = 'DELETE FROM #__jblance_duration WHERE id IN ('.$cids.')';
		$db->setQuery($query);
		if(!$db->execute()){
			JError::raiseError($db->getErrorNum(), $db->getErrorMsg());
		}
		$delCount = $db->getAffectedRows();
	
		$msg	= $delCount.' '.JText::_('COM_JBLANCE_PROJECT_DURATION_DELETED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showduration';
		$this->setRedirect($link, $msg);
	}
	
	function saveDuration(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		// Initialize variables
		$app 	= JFactory::getApplication();
		$db		= JFactory::getDbo();
		$row	= JTable::getInstance('duration', 'Table');
		$post 	= $app->input->post->getArray();
		
		if($post['less_great'] == 'less')
			$post['less_great'] = '<';
		elseif($post['less_great'] == 'great')
			$post['less_great'] = '>';
		
		if(!$row->save($post)){
			JError::raiseError(500, $row->getError());
		}
	
		$msg	= JText::_('COM_JBLANCE_PROJECT_DURATION_SAVED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showduration';
		$this->setRedirect($link, $msg);
	}
	
	function cancelDuration(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$app = JFactory::getApplication();
		$msg = '';
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showduration';
		$this->setRedirect($link, $msg);
	}
	
/**
 ================================================================================================================
 SECTION : Configuration: Location - new, remove, save, cancel
 ================================================================================================================
 */
	function newLocation(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		JRequest :: setVar('view', 'admconfig');
		JRequest :: setVar('hidemainmenu', 1);
		JRequest :: setVar('layout', 'editlocation');
		$this->display();
	}
	
	function removeLocation(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		// Initialize variables
		$app = JFactory::getApplication();
		$row	= JTable::getInstance('location', 'Table');
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid);
	
		if(count($cid)){
			for($i=0; $i < count($cid); $i++){
				$curr_bid = $cid[$i];
				if(!$row->delete($curr_bid)){
					$this->setError($row->getError());
				}
			}
		}
		
		$msg	= JText::_('COM_JBLANCE_LOCATION_DELETED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showlocation';
		$this->setRedirect($link, $msg);
	}
	
	function saveLocation(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		// Initialize variables
		$app 	= JFactory::getApplication();
		$db		= JFactory::getDbo();
		$row	= JTable::getInstance('location', 'Table');
		$post 	= $app->input->post->getArray();
		
		//save the params value
		$params	= $app->input->get('params', null, 'array');
		$registry = new JRegistry();
		$registry->loadArray($params);
		$post['params'] = $registry->toString();
		
		// Set the new parent id if parent id not matched OR while New/Save as Copy .
		if($row->parent_id != $post['parent_id'] || $post['id'] == 0){
			$row->setLocation($post['parent_id'], 'last-child');
		}
		
		// Save the data.
		if(!$row->save($post)){
			$this->setError($row->getError());
			return false;
		}
		
		// Rebuild the path for the category:
		if(!$row->rebuildPath($row->id)){
			$this->setError($row->getError());
			return false;
		}
		
		// Rebuild the paths of the category's children:
		if(!$row->rebuild($row->id, $row->lft, $row->level, $row->path)){
			$this->setError($row->getError());
			return false;
		}
	
		$msg	= JText::_('COM_JBLANCE_LOCATION_SAVED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showlocation';
		$this->setRedirect($link, $msg);
	}
	
	function cancelLocation(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		$app = JFactory::getApplication();
		$msg = '';
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showlocation';
		$this->setRedirect($link, $msg);
	}

/**
 ================================================================================================================
 SECTION : Configuration: Planbenefits - new, remove, save, cancel
 ================================================================================================================
 */
	function newPlanbenefits(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		JRequest :: setVar('view', 'admconfig');
		JRequest :: setVar('hidemainmenu', 1);
		JRequest :: setVar('layout', 'editplanbenefits');
		$this->display();
	}
	
	function removePlanbenefits(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	    $db=JFactory::getDbo();
		// Initialize variables
		$app = JFactory::getApplication();
		$row	= JTable::getInstance('planbenefits', 'Table');
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid);
		$utable = JTable::getInstance('Viewlevel', 'JTable');

		
		if(count($cid)){
			for($i=0; $i < count($cid); $i++){
				$curr_bid = $cid[$i];
				$row->load($cid[$i]);
				$title=$row->title;
				
				if(!$row->delete($curr_bid)){
					$this->setError($row->getError());
				}
				$level=$this->getLevelByTitle($title);
				if($level!="")
				{
				$utable->delete($level->id);
				}
				$cid 	= implode(',', $cid);
				$query="DELETE FROM #__jblance_benefitaccess WHERE ben_id IN(".$cid.")";
				$db->setQuery($query);
				$db->execute();
				
			}
		}
		
		
		
		
		$msg	= JText::_('Item successfully removed');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showplanbenefits';
		$this->setRedirect($link, $msg);
	}
	
	function savePlanbenefits(){
	
	
	
	 // Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		// Initialize variables
		$app 	= JFactory::getApplication();
		$db		= JFactory::getDbo();
		$row	= JTable::getInstance('planbenefits', 'Table');
		$post 	= $app->input->post->getArray();
		foreach($post as $uk=>$uv)
		$fp[$uk] = trim($uv);
		
		
		
		
		$postParent=$post['parent_id'];
	 
	    $isNew  = $post['id']!=''?false: true;
		
		$exist = $this->getBenByTitle($fp['title']);
		
		
		if($isNew && $exist->title== $fp['title'])
		{
		$msg	= JText::_('Plan benefit with the same name allready exists.');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showplanbenefits';
		$app->redirect($link, $msg,'error');
		Jexit();
		}
		
	    //save the params value
		$params	= $app->input->get('params', null, 'array');
		$registry = new JRegistry();
		$registry->loadArray($params);
		$post['params'] = $registry->toString();
		
		// Set the new parent id if parent id not matched OR while New/Save as Copy .
		if($row->parent_id != $post['parent_id'] || $post['id'] == 0){
			$row->setLocation($post['parent_id'], 'last-child');
		}
		
		$oldType=$this->getOldByType($post['id']);
		
		// Save the data.
		if(!$row->save($fp)){
		    
			$this->setError($row->getError());
			return false;
		}
		
		$isParent=$row->parent_id == 1 ||  $post['parent_id']==1 ? true : false; 
		
		
			
		// Rebuild the path for the category:
		if(!$row->rebuildPath($row->id)){
	
			$this->setError($row->getError());
			return false;
		}
		
		// Rebuild the paths of the category's children:
		if(!$row->rebuild($row->id, $row->lft, $row->level, $row->path)){
			$this->setError($row->getError());
			
			return false;
		}
		
		
		
	    $benId=$row->id;
		
		$levTitle=$row->title;
		$benType=$row->ben_type;
		$utable = JTable::getInstance('Viewlevel', 'JTable');
	  
	    $utable->title=$levTitle;
	    $utable->ordering=0;
		$utable->rules=json_encode(array());
		if($isNew && !$isParent)
		{
		
		if($benType=='checkmark')
		{
		$utable->store();
		
		}
		}
		else
		{
		if(!$isNew)
		{
		
		//if old record
	     if($post['ben_type']=="checkmark" && $post['ben_type']!=$oldType->ben_type)
		{
		
		//CREATE A NEW LEVEL
		$utable->title=$post['title'];
	    $utable->ordering=0;
		$utable->rules=json_encode(array());
		$utable->store(); 
		} 
		else
		{
	     
		 //DELETE THE OLD ACCESS LEVEL
		$currLevel=$this->getLevelByTitle($post['title']);
		if($currLevel)
		{
		
		$utable->load($currLevel->id);
		$utable->delete(); 
		}
		
		}
		
		}
		

		}
		$msg	= JText::_('Plan benefit successfully saved.');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showplanbenefits';
		$this->setRedirect($link, $msg);
	}
	
	function getOldByType($id)
	{
	$db=JFactory::getDbo();
	$query="SELECT ben_type FROM #__jblance_planbenefits WHERE id='".$id."'";
	$db->setQuery($query);
	$res=$db->loadObject();
	return $res;
	}
	
	function getLevelByTitle($title)
	{
	$db=JFactory::getDbo();
	$query="SELECT id,title FROM #__viewlevels WHERE title=".$db->quote($db->escape($title));
	$db->setQuery($query);
	return $db->loadObject();
	}
	
	function getBenByTitle($title)
	{

	
	$db=JFactory::getDbo();
	$query="SELECT title FROM #__jblance_planbenefits WHERE title=".$db->quote($db->escape($title));
	
	$db->setQuery($query);
	return $db->loadObject();
	}
	
	
	function cancelPlanbenefits(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		$app = JFactory::getApplication();
		$msg = '';
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showplanbenefits';
		$this->setRedirect($link, $msg);
	}	
	/* Misc Functions */
	
	//5.Publish / Unpublish row data
	function jbPubUnpub($publish, $tbl, $link, $msg){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		// Initialize variables
		$app 	= JFactory::getApplication();
		$db 	= JFactory::getDbo();
		$cid 	= $app->input->get('cid', array(), 'array');
		$layout=JRequest::getVar("layout",'');
		
		if($layout=="showplanbenefits"):
		
		//check if the item has a parent that is unpublished
		$queryP="SELECT * FROM #__jblance_planbenefits WHERE id=".$cid[0]." AND parent_id!=1";
		$db->setQuery($queryP);
		$rowP=$db->LoadAssoc();
		if($rowP):
		$queryc="SELECT * FROM #__jblance_planbenefits WHERE id=".$rowP['parent_id'];
		$db->setQuery($queryc);
		$rowc=$db->LoadAssoc();
		if($rowc):
		if($rowc['published']==0)
		$app->redirect($link,"Please publish the parent item first.","error");
		endif;
		endif;
		
		$query="SELECT * FROM #__jblance_planbenefits WHERE parent_id=".$cid[0];
		$db->setQuery($query);
		$rowsC=$db->loadObjectList();
		
		if($rowsC):
		foreach($rowsC as $rv)
		$cid[]=$rv->id;
		endif;
		endif;
		
		
		
		JArrayHelper::toInteger($cid);
		
		$cids 	= implode(',', $cid);
		$query 	= 'UPDATE '.$tbl.' SET published = '.(int)$publish.' WHERE id IN ('.$cids.')';//echo $query;exit;
		$db->setQuery($query);
	
		if(!$db->execute()){
			return JError::raiseWarning(500, $db->getError());
		}
		$this->setRedirect($link, $msg);
	}
	
	//6.Require / Unrequire fields
	function jbReqUnrequired($required, $tbl, $link, $msg){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		// Initialize variables
		$app  	= JFactory::getApplication();
		$db 	= JFactory::getDbo();
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid);
		
		$cids 	= implode(',', $cid);
		$query = 'UPDATE '.$tbl.' SET required = '.(int)$required.' WHERE id IN ('.$cids.')';
		$db->setQuery($query);
	
		if(!$db->execute()){
			return JError::raiseWarning(500, $db->getError());
		}
		$this->setRedirect($link, $msg);
	}
	
	function runSql(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$app  	= JFactory::getApplication();
		$db 	= JFactory::getDbo();
		$post 	= $app->input->post->getArray();
		$result = 0;
		
		if($post['actiontype'] == 1){ // first time
			if($_FILES['runsql']['size'] > 0){
				$file_name = $_FILES['runsql']['name']; // file name
				$file_tmp = $_FILES['runsql']['tmp_name']; // actual location
				
				$ext = JFile::getExt($file_name);
				if(!empty($file_tmp)){
					if($ext != "sql")
						$result = 2; //file type mismatch
				}
				
				$theData = JFile::read($file_tmp);
				
				$db->setQuery($theData);
				if($db->execute())
					$result = 1;
			}
		}
		
		if($result == 1){
			$msg = JText::_('COM_JBLANCE_SQL_EXECUTED_SUCCESSFULLY');
		}
		elseif($result == 2){ // file mismatch
			$msg = JText::_('COM_JBLANCE_ONLY_SQL_ALLOWED');
		}
		else {
			$msg = JText::_('COM_JBLANCE_OPERATIION_UNSUCCESSFUL');
		}
		
		$link = 'index.php?option=com_jblance&view=admconfig&layout=showpaymode';
		$this->setRedirect($link, $msg);
		
	}
	
	function optimise(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$app  		 = JFactory::getApplication();
		$db		 	 = JFactory::getDbo();
		$user_ids 	 = $app->input->get('userIds', '', 'string');
		$project_ids = $app->input->get('projectIds', '', 'string');
	
		if(empty($user_ids) && empty($project_ids)){
			$msg	= JText::_('COM_JBLANCE_NO_OPERATION_EXECUTED');
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=optimise';
			$this->setRedirect($link, $msg);
		}
		else {
			// delete from user table
			$query = "DELETE FROM #__jblance_user WHERE user_id IN (".$user_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' users deleted from JoomBri Users table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from project table
			$query = "DELETE FROM #__jblance_project WHERE id IN (".$project_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' projects deleted from JoomBri Project table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from bid table
			$query = "DELETE FROM #__jblance_bid WHERE user_id IN (".$user_ids.") OR project_id IN (".$project_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from Bids table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from custom field value table
			$query = "DELETE FROM #__jblance_custom_field_value WHERE userid IN (".$user_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from Custom Field Value table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from deposit table
			$query = "DELETE FROM #__jblance_deposit WHERE user_id IN (".$user_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from Deposit table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from escrow table
			$query = "DELETE FROM #__jblance_escrow WHERE from_id IN (".$user_ids.") OR to_id IN (".$user_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from Escrow table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
			
			// delete from Expiry Alert table
			$query = "DELETE FROM #__jblance_expiry_alert WHERE user_id IN (".$user_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from Expiry Alert table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
			
			// delete entries from Favourite table
			$query = "DELETE FROM #__jblance_favourite WHERE actor IN (".$user_ids.") OR target IN (".$user_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from Favourite table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from feeds table
			$query = "DELETE FROM #__jblance_feed WHERE actor IN (".$user_ids.") OR target IN (".$user_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from Feeds table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from feeds hide table
			$query = "DELETE FROM #__jblance_feed_hide WHERE user_id IN (".$user_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from Feeds Hide table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from forum table
			$query = "DELETE FROM #__jblance_forum WHERE user_id IN (".$user_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from Forum table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from message table
			$query = "DELETE FROM #__jblance_message WHERE idFrom IN (".$user_ids.") OR idTo IN (".$user_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from Message table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from notify table
			$query = "DELETE FROM #__jblance_notify WHERE user_id IN (".$user_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from Notify table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from plan subscr table
			$query = "DELETE FROM #__jblance_plan_subscr WHERE user_id IN (".$user_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from Plan Subscription table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from portfolio table
			$query = "DELETE FROM #__jblance_portfolio WHERE user_id IN (".$user_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from Portfolio table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from project file table
			$query = "DELETE FROM #__jblance_project_file WHERE project_id IN (".$project_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from Project File table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from rating table
			$query = "DELETE FROM #__jblance_rating WHERE actor IN (".$user_ids.") OR target IN (".$user_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from Rating table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from report table
			$query = "DELETE FROM #__jblance_report WHERE (`method` like 'project%' AND params IN ($project_ids)) OR (`method` like 'profile%' AND params IN ($user_ids))";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from Report table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from reporter table
			$query = "DELETE FROM #__jblance_report_reporter WHERE user_id IN (".$user_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from Reporter table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from transaction table
			$query = "DELETE FROM #__jblance_transaction WHERE user_id IN (".$user_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from Transaction table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from withdraw table
			$query = "DELETE FROM #__jblance_withdraw WHERE user_id IN (".$user_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from Withdraw table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
		}
	
		$msg	= JText::_('COM_JBLANCE_OPERATION_COMPLETED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=optimise';
		$this->setRedirect($link, $msg);
	}
	
	function display($cachable = false, $urlparams = false){
		$document = JFactory :: getDocument();
		$viewName = JRequest :: getVar('view', 'admconfig');
		$layoutName = JRequest :: getVar('layout', 'configpanel');
		$viewType = $document->getType();
		$model = $this->getModel('admconfig', 'JblanceModel');
		$view = $this->getView($viewName, $viewType);
		if (!JError :: isError($model)){
			$view->setModel($model, true);
		}
		$view->setLayout($layoutName);
		$view->display();
	}
}
