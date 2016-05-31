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

class modJBusinessEventSearchHelper
{
	function getTitle( $params )
    {
		return '';
    }
		

	static function getTypes(){
		$db = JFactory::getDBO();
		$query = ' SELECT * FROM #__jbusinessdirectory_company_event_types order by name';
		$db->setQuery($query);
		$result = $db->loadObjectList();
	
		return $result;
	}
	
	static function getCities(){
		$db = JFactory::getDBO();
		$query = ' SELECT distinct city FROM #__jbusinessdirectory_company_events where state =1 and city!="" order by city asc';
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	static function getRegions(){
		$db = JFactory::getDBO();
		$query = 'SELECT distinct county FROM #__jbusinessdirectory_company_events where state =1 and county!="" order by county asc';
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	static function getActivityCities(){
		$db = JFactory::getDBO();
		$query = ' SELECT distinct name as city FROM #__jbusinessdirectory_cities order by name asc';
		$db->setQuery($query);
		return $db->loadObjectList();
	}
}
?>
