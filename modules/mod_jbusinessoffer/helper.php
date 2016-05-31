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


require_once( JPATH_ADMINISTRATOR .'/components/com_jbusinessdirectory/library/category_lib.php');

class modJBusinessOfferHelper{
	
	function getOffer(){

		$db = JFactory::getDBO();
		$query = "SELECT *  FROM #__jbusinessdirectory_company_offers where offerOfTheDay=1 and state=1 order by rand()";

		$db->setQuery($query);
		$offer = $db->loadObject();
		
		if(isset($offer)){
			$query = "select * from #__jbusinessdirectory_company_offer_pictures where offerId=$offer->id order by id";
			$db->setQuery($query);
			$offer->pictures = $db->loadObjectList();
		}
		//var_dump($offer);
		return $offer;
	}
}
?>
