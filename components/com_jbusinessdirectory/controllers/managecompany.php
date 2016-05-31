<?php

/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );

JTable::addIncludePath(DS.'components'.DS.JRequest::getVar('option').DS.'models');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.'company.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'managecategories.php');


class JBusinessDirectoryControllerManageCompany extends JBusinessDirectoryControllerCompany
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	 
	function __construct()
	{
		parent::__construct();
		
	}
	
	
	/**
	 * Dummy method to redirect back to standard controller
	 *
	 */
	public function display($cachable = false, $urlparams = false)
	{
		$this->setRedirect(JRoute::_('index.php?option=com_jbusinessdirectory&view=managecompanies', false));
		
	}
	
	/**
	 * Method override to check if you can add a new record.
	 *
	 * @param   array  $data  An array of input data.
	 *
	 * @return  boolean
	 */
	
	protected function allowAdd($data = array())
	{
		return true;
	}
	
	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key.
	 *
	 * @return  boolean
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		return true;
	}
	
	public function add()
	{
		
		$app = JFactory::getApplication();
		$context = 'com_jbusinessdirectory.edit.company';
	
		$result = parent::add();
		if ($result)
		{
			$this->setRedirect(JRoute::_('index.php?option=com_jbusinessdirectory&view=managecompany'. $this->getRedirectToItemAppend(), false));
		}
	
		return $result;
	}
	
	
	/**
	 * Method to cancel an edit.
	 *
	 * @param   string  $key  The name of the primary key of the URL variable.
	 *
	 * @return  boolean  True if access level checks pass, false otherwise.
	
	 */
	public function cancel($key = null)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		$app = JFactory::getApplication();
		$context = 'com_jbusinessdirectory.edit.managecompany';
		$result = parent::cancel();
	
	}
	
	/**
	 * Method to edit an existing record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key
	 * (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if access level check and checkout passes, false otherwise.
	 *
	 */
	public function edit($key = null, $urlVar = null)
	{
		
		JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		
		$app = JFactory::getApplication();
		$result = parent::edit();
	
		return true;
	}
	
	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save($key = NULL, $urlVar = NULL)
	{
	
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$app      = JFactory::getApplication();
		$model = $this->getModel('managecompany');
		$post = JRequest::get( 'post' );
		$post['description'] = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$data = JRequest::get( 'post' );
		$context  = 'com_jbusinessdirectory.edit.managecompany';
		$task     = $this->getTask();
		$recordId = JRequest::getInt('id');
	
		if(isset($post['website']) && strlen($post['website'])>1){
			if (!preg_match("~^(?:f|ht)tps?://~i", $post['website'])) {
				$post['website'] = "http://" . $post['website'];
			}
		}
		
		if(isset($post['facebook']) && strlen($post['facebook'])>1){
			if (!preg_match("~^(?:f|ht)tps?://~i", $post['facebook'])) {
				$post['facebook'] = "http://" . $post['facebook'];
			}
		}
		
		if(isset($post['twitter']) && strlen($post['twitter'])>1){
			if (!preg_match("~^(?:f|ht)tps?://~i", $post['twitter'])) {
				$post['twitter'] = "http://" . $post['twitter'];
			}
		}
		
		if(isset($post['googlep']) && strlen($post['googlep'])>1){
			if (!preg_match("~^(?:f|ht)tps?://~i", $post['googlep'])) {
				$post['googlep'] = "http://" . $post['googlep'];
			}
		}
	
		//save images
		$pictures = array();
		foreach( $post as $key => $value )
		{
			if(
					strpos( $key, 'company_picture_info' ) !== false
					||
					strpos( $key, 'company_picture_path' ) !== false
					||
					strpos( $key, 'company_picture_enable' ) !== false
			){
				foreach( $value as $k => $v )
				{
					if( !isset($pictures[$k]) )
						$pictures[$k] = array('company_picture_info'=>'', 'company_picture_path'=>'','company_picture_enable'=>1);
					$pictures[$k][$key] = $v;
				}
			}
		}
		$post['pictures'] 				= $pictures;
	
		if (!$model->save($post)){
			// Save the data in the session.
			$app->setUserState('com_jbusinessdirectory.edit.managecompany.data', $data);
				
			// Redirect back to the edit screen.
			$this->setMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($recordId), false));
				
			return false;
		}
	
		$this->setMessage(JText::_('COM_JBUSINESSDIRECTORY_COMPANY_SAVE_SUCCESS'));
	
		// Redirect the user and adjust session state based on the chosen task.
		switch ($task)
		{
			case 'apply':
				$recordId = $model->getState("company.id");
				// Set the row data in the session.
				$this->holdEditId($context, $recordId);
				$app->setUserState('com_jbusinessdirectory.edit.managecompany.data', null);
				// Redirect back to the edit screen.
				$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($recordId), false));
				break;
	
			default:
				// Clear the row id and data in the session.
				$this->releaseEditId($context, $recordId);
				$app->setUserState('com_jbusinessdirectory.edit.managecompany.data', null);
					
				
				$redirect = $model->getState()->get("company.redirect.payment");
				
				if($redirect=="1"){
					$this->setRedirect( 'index.php?option=com_jbusinessdirectory&view=orders', $msg );
				}else{
					$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list . $this->getRedirectToListAppend(), false));
				}
				// Redirect to the list screen.
				
				break;
		}
	}

	function saveLocation(){
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$app      = JFactory::getApplication();
		$model = $this->getModel('managecompany');
		$data = JRequest::get('post');
	
		if (!($locationId = $model->saveLocation($data))){
			// Save the data in the session.
			$app->setUserState('com_jbusinessdirectory.edit.companylocation.data', $data);
	
			// Redirect back to the edit screen.
			$this->setMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&tmpl=component&layout=locations&view=managecompany'. $this->getRedirectToItemAppend($recordId).'&locationId='.$locationId, false));
	
			return false;
				
		}
		$this->setMessage(JText::_('COM_JBUSINESSDIRECTORY_LOCATION_SAVE_SUCCESS'));
	
		$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&tmpl=component&layout=locations&view=managecompany' . $this->getRedirectToListAppend().'&locationId='.$locationId, false));
	
	}
	
	function deleteLocation(){
		$errorFlag = false;
		$locationId =JRequest::getVar('locationId');
		$model = $this->getModel('managecompany');
	
		$result = $model->deleteLocation($locationId);
		$message="";
	
		echo '<?xml version="1.0" encoding="utf-8" ?>';
		echo '<category_statement>';
		echo '<answer error="'.(!$result ? "0" : "1").'" errorMessage="'.$message.'" locationId="'.$locationId.'"';
		echo '</category_statement>';
		echo '</xml>';
		exit;
	}
	
	function changeState()
	{
		$model = $this->getModel('ManageCompany');
		$msg ="";
		if (!$model->changeState())
		{
			$msg = JText::_('LNG_ERROR_CHANGE_STATE');
		}
	
		$this->setRedirect(JRoute::_('index.php?option=com_jbusinessdirectory&view=managecompanies', $msg));
	}
	
	function extendPeriod(){
		$model = $this->getModel('ManageCompany');
		$data = JRequest::get('post');
		$model ->extendPeriod($data);
		$this->setMessage(JText::_('COM_JBUSINESSDIRECTORY_EXTENDED_NEW_ORDER_CREATED'));
		$this->setRedirect(JRoute::_('index.php?option=com_jbusinessdirectory&view==managecompany&layout=edit&id='.$data["id"], false));
	}

}
