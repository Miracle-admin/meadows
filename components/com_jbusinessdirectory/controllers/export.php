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



class JBusinessDirectoryControllerExport extends JControllerLegacy
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	 
	function __construct()
	{
		parent::__construct();
	}
	
	public function exportFiles(){
		$path = JRequest::getVar("path");
	
		if(empty($path))
			return;
		
		require_once( JPATH_COMPONENT_ADMINISTRATOR.'/models/companies.php');
		$companiesModel = new JBusinessDirectoryModelCompanies();
		$companiesModel->exportCompaniesCSVtoFile($path."/business_listings.csv");
	
		exit;
		
		require_once( JPATH_COMPONENT_ADMINISTRATOR.'/models/offers.php');
		$offersModel = new JBusinessDirectoryModelOffers();
		$offersModel->exportOffersCSVtoFile($path."/offers.csv");
	
		require_once( JPATH_COMPONENT_ADMINISTRATOR.'/models/events.php');
		$eventsModel = new JBusinessDirectoryModelEvents();
		$eventsModel->exportEventsCSVtoFile($path."/events.csv");
		
		exit;
	}

}