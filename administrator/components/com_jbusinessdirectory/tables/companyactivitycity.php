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

class JTableCompanyActivityCity extends JTable
{

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db){

		parent::__construct('#__jbusinessdirectory_company_activity_city', 'id', $db);
	}

	function setKey($k)
	{
		$this->_tbl_key = $k;
	}
	
	
	function deleteNotContainedCities($companyId, $cities){
		$db =JFactory::getDBO();
		$cities = implode(",", $cities);
		$sql = "delete from #__jbusinessdirectory_company_activity_city where company_id= $companyId and city_id not in ($cities)";
		
		$db->setQuery($sql);
		return $db->query();
	}
	
	function getActivityCities($companyId){
		$db =JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_company_activity_city where company_id= $companyId";
		$db->setQuery($query);
		
		return $db->loadObjectList();
	}
	
	function getActivityCity($companyId, $cityId){
		$db =JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_company_activity_city where company_id= $companyId and city_id=$cityId";
		$db->setQuery($query);
		
		return $db->loadObject();
	}
	
}