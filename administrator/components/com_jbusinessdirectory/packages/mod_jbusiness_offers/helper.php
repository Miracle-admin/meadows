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


abstract class modJBusinessOffersHelper
{
	public static function getList($params){
		
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		// Get the database object.
		$db = JFactory::getDBO();
		
		$categoriesIds = $params->get('categoryIds');
		
		if(!(!empty($categoriesIds) && $categoriesIds[0]!="")){
			$categoriesIds = null;
		}

		$featured  = $params->get('only_featured');
		
		$orderBy = " rand() ";
		$ordering  = $params->get('order');
		if($ordering){
			$orderBy ="co.id desc";
		}
		
		$nrResults = $params->get('count');
		
		$searchDetails = array();
		$searchDetails["categoriesIds"] = $categoriesIds;
		$searchDetails["enablePackages"] = $appSettings->enable_packages;
		$searchDetails["showPendingApproval"] = $appSettings->show_pending_approval;
		$searchDetails["orderBy"] = $orderBy;
		$searchDetails["featured"] = $featured;
		
		JTable::addIncludePath(JPATH_ROOT.'/administrator/components/com_jbusinessdirectory/tables');
		$offersTable = JTable::getInstance("Offer", "JTable");
		$offers =  $offersTable->getOffersByCategories($searchDetails, 0, $nrResults);
	
		foreach($offers as $offer){
			switch($offer->view_type){
				case 1:
					$offer->link = JBusinessUtil::getofferLink($offer->id, $offer->alias);
					break;
				case 2:
					$itemId = JRequest::getVar('Itemid');
					$offer->link = JRoute::_("index.php?option=com_content&view=article&Itemid=$itemId&id=".$offer->article_id);
					break;
				case 3:
					$offer->link = $offer->url;
					break;
				default:
					$offer->link = JBusinessUtil::getofferLink($offer->id, $offer->alias);
			}
		}
		
		return $offers;
	}
}
?>
