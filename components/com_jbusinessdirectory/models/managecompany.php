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


JTable::addIncludePath(DS.'components'.DS.JRequest::getVar('option').DS.'tables');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'company.php');

class JBusinessDirectoryModelManageCompany extends JBusinessDirectoryModelCompany
{ 
	
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object	A record object.
	 *
	 * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
	 */
	protected function canDelete($record)
	{
		return true;
	}
	
	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object	A record object.
	 *
	 * @return  boolean  True if allowed to change the state of the record. Defaults to the permission set in the component.
	 */
	protected function canEditState($record)
	{
		return true;
	}
	
	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object	A record object.
	 *
	 * @return  boolean  True if allowed to change the state of the record. Defaults to the permission set in the component.
	 */
	protected function canEdit($record)
	{
		return true;
	}
	
	protected function populateState()
	{
		$app = JFactory::getApplication('administrator');
	
		$user = JFactory::getUser();
		$companiesTable = JTable::getInstance("Company", "JTable", array());
		
		$companyId = JRequest::getVar("id",0);
		
		$this->setState('company.id', $companyId);
			
		$packageId = JRequest::getInt('filter_package');
		if(isset($packageId)){
			$this->setState('company.packageId', $packageId);
		}
	}
	
	public function updateCompanyOwner($companyId, $userId){
		// Get a row instance.
		$table = $this->getTable("Company");
		$table->load($companyId);
		$table->userId = $userId;
		
		// Store the data.
		if (!$table->store())
		{
			$this->setError($table->getError());
			return false;
		}
	}
}
?>