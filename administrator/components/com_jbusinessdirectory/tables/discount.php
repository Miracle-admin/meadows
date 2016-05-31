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

class JTableDiscount extends JTable
{

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function JTableDiscount(& $db) {

		parent::__construct('#__jbusinessdirectory_discounts', 'id', $db);
	}

	
	function setKey($k)
	{
		$this->_tbl_key = $k;
	}
	
	function getDiscount($discountCode, $ignoreCount){
		
		$countFilter = "";
		if(!$ignoreCount){
			$countFilter = "and uses_per_coupon>coupon_used";
		}
		
		$db =JFactory::getDBO();
		$query = "SELECT * FROM #__jbusinessdirectory_discounts  WHERE code = '$discountCode' $countFilter and state=1 and DATE(now())>= start_date and DATE(now())<=end_date";
		$db->setQuery($query);
		$result = $db->loadObject();
	
		return $result;
	}
	
	function increaseUse($discountCode){
		$db =JFactory::getDBO();
		$query = "update  #__jbusinessdirectory_discounts set coupon_used = coupon_used + 1 where  code = '$discountCode'";
		// 		dump($query);
		$db->setQuery($query);
		return $db->query();
	}
	
	
	function changeState($id){
		$db =JFactory::getDBO();
		$query = 	" UPDATE #__jbusinessdirectory_discounts SET state = IF(state, 0, 1) WHERE id = ".$id ;
		$db->setQuery( $query );
	
		if (!$db->query()){
			return false;
		}
		return true;
	}
	
	
	function getDiscountsForExport(){
		$db =JFactory::getDBO();
		$query = "SELECT * FROM #__jbusinessdirectory_discounts";
		$db->setQuery($query);
		$result = $db->loadObjectList();
		
		return $result;
	
	}
	
	
}