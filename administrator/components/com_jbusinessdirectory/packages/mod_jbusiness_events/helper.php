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


abstract class modJBusinessEventsHelper
{
	public static function getList($params){
		
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		// Get the database object.
		$db = JFactory::getDBO();
		
		
		$featured  = $params->get('only_featured');
		
		$orderBy = " rand() ";
		$ordering  = $params->get('order');
		if($ordering){
			$orderBy ="co.id desc";
		}
		
		$nrResults = $params->get('count');
		
		$searchDetails = array();
		$searchDetails["enablePackages"] = $appSettings->enable_packages;
		$searchDetails["showPendingApproval"] = $appSettings->show_pending_approval;
		$searchDetails["orderBy"] = $orderBy;
		$searchDetails["featured"] = $featured;
		
		JTable::addIncludePath(JPATH_ROOT.'/administrator/components/com_jbusinessdirectory/tables');
		$eventsTable = JTable::getInstance("Event", "JTable");
		$events =  $eventsTable->getEventsByCategories($searchDetails, 0, $nrResults);
		
		return $events;
	}
}
?>
