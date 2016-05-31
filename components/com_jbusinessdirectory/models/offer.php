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

class JBusinessDirectoryModelOffer extends JModelItem
{ 
	
	function __construct()
	{
		parent::__construct();
		
		$this->appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		$this->offerId = JRequest::getVar('offerId');
	}

	function getOffer(){
		$offersTable = JTable::getInstance("Offer", "JTable");
		$offer =  $offersTable->getOffer($this->offerId);
		$offer->pictures = $offersTable->getOfferPictures($this->offerId);
		$offersTable->increaseViewCount($this->offerId);
		
		$companiesTable = JTable::getInstance("Company", "JTable");
		$company = $companiesTable->getCompany($offer->companyId);
		$offer->company=$company;
		$offersTable = JTable::getInstance("Offer", "JTable");
		
		if($this->appSettings->enable_multilingual){
			JBusinessDirectoryTranslations::updateEntityTranslation($offer, OFFER_DESCRIPTION_TRANSLATION);
		}
		
		$offer->attachments = JBusinessDirectoryAttachments::getAttachments(OFFER_ATTACHMENTS, $this->offerId, true);
		
		return $offer;
	}
	
	
}
?>

