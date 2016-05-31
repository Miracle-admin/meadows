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

class modJBusinessOfferSearchHelper
{
	function getTitle( $params )
    {
		return '';
    }
		
	static function getMainCategories(){
		$db = JFactory::getDBO();
		$query = ' SELECT * FROM #__jbusinessdirectory_categories where parent_id=1 and published=1  order by name';
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	static function getSubCategories(){
		$db = JFactory::getDBO();
		$query = ' SELECT c.* FROM #__jbusinessdirectory_categories c
                   inner join  #__jbusinessdirectory_categories  cc  on c.parent_id = cc.id  where c.parent_id!=1  and cc.parent_id = 1 and c.published=1
                   order by c.name';
		$db->setQuery($query,0,1000);
		$result = $db->loadObjectList();

		return $result;
	}

	static function getCities(){
		$db = JFactory::getDBO();
		$query = ' SELECT distinct city FROM #__jbusinessdirectory_company_offers where state =1 and city!="" order by city asc';
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	static function getRegions(){
		$db = JFactory::getDBO();
		$query = 'SELECT distinct county FROM #__jbusinessdirectory_company_offers where state =1 and county!="" order by county asc';
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
