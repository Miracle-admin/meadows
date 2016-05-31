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

jimport('joomla.application.component.modelitem');
JTable::addIncludePath(DS.'components'.DS.JRequest::getVar('option').DS.'tables');
require_once( JPATH_COMPONENT_ADMINISTRATOR.'/library/category_lib.php');

class JBusinessDirectoryModelEvent extends JModelItem
{ 
	
	function __construct()
	{
		parent::__construct();
		$this->appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		$this->eventId = JRequest::getVar('eventId');
	}

	function getEvent(){
		$eventsTable = JTable::getInstance("Event", "JTable");
		$event =  $eventsTable->getEvent($this->eventId);
		$event->pictures = $eventsTable->getEventPictures($this->eventId);
		$eventsTable->increaseViewCount($this->eventId);
		
		$companiesTable = JTable::getInstance("Company", "JTable");
		$company = $companiesTable->getCompany($event->company_id);
		$event->company=$company;
 
		if($this->appSettings->enable_multilingual){
			JBusinessDirectoryTranslations::updateEntityTranslation($event, EVENT_DESCRIPTION_TRANSLATION);
		}
		
		return $event;
	}
	
	
}
?>

