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


class JBusinessDirectoryControllerEvents extends JControllerLegacy
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	 
	function __construct()
	{
		parent::__construct();
	}

	function getCalendarEvents(){
		JRequest::setVar('limitstart',0);
		JRequest::setVar('limit',0);
		JRequest::setVar('startDate',JRequest::setVar("start"));
		JRequest::setVar('endDate',JRequest::setVar("end"));
		
		$model = $this->getModel('events');
		$events = $model->getCalendarEvents();
		

		echo json_encode($events);		
		exit;
	}
	
}