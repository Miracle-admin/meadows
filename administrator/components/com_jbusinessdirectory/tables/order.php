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

class JTableOrder extends JTable
{

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db){

		parent::__construct('#__jbusinessdirectory_orders', 'id', $db);
	}

	function setKey($k)
	{
		$this->_tbl_key = $k;
	}


	function getOrder($invoiceId){
		$db =JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_orders where id=".$invoiceId;
		$db->setQuery($query);
		//dump($query);
		return $db->loadObject();
	}
	
	function getLastNonPaidCompanyOrder($companyId){
		$db =JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_orders where company_id=$companyId and state=0 order by id desc";
		$db->setQuery($query);
		//dump($query);
		return $db->loadObject();
	}
	

	function getOrders($userId){
		$db =JFactory::getDBO();
		$query = "select inv.*,p.name as businessName from #__jbusinessdirectory_companies p
				  inner join #__jbusinessdirectory_orders inv on p.id=inv.company_id
				  where p.userId=$userId  
				  order by created desc";
		$db->setQuery($query);
		
		
		return $db->loadObjectList();
	}
	
	function deleteOldOrders($companyId){
		$db =JFactory::getDBO();
		$query = "delete from #__jbusinessdirectory_orders where company_id=$companyId and state=".PAYMENT_STATUS_NOT_PAID;
		$db->setQuery($query);
		//dump($query);
		return $db->query();
	}
	
	function getTotalIncome(){
		$db =JFactory::getDBO();
		$query = "SELECT sum(amount_paid) as amount FROM #__jbusinessdirectory_orders";
		$db->setQuery($query);
		$result = $db->loadObject();
	
		return $result->amount;
	}
	
	function getTodayIncome(){
		$db =JFactory::getDBO();
		$query = "SELECT sum(amount_paid) as amount FROM #__jbusinessdirectory_orders where  DATE(`paid_at`) = CURDATE() ";
		$db->setQuery($query);
		$result = $db->loadObject();
	
		return $result->amount;
	}
	
	function getWeekIncome(){
		$db =JFactory::getDBO();
		$query = "SELECT sum(amount_paid) as amount FROM #__jbusinessdirectory_orders  WHERE WEEKOFYEAR(paid_at)=WEEKOFYEAR(NOW())";
		$db->setQuery($query);
		$result = $db->loadObject();
	
		return $result->amount;
	}
	
	function getMonthIncome(){
		$db =JFactory::getDBO();
		$query = "SELECT sum(amount_paid) as amount FROM #__jbusinessdirectory_orders WHERE MONTH(paid_at)=MONTH(NOW())";
		$db->setQuery($query);
		$result = $db->loadObject();
	
		return $result->amount;
	}
	
	function getYearIncome(){
		$db =JFactory::getDBO();
		$query = "SELECT sum(amount_paid) as amount FROM #__jbusinessdirectory_orders  WHERE YEAR(paid_at)=YEAR(NOW())";
		$db->setQuery($query);
		$result = $db->loadObject();
	
		return $result->amount;
	}
	
	function updateExpirationEmailDate($orderId){
		$db =JFactory::getDBO();
		$query = "update  #__jbusinessdirectory_orders set expiration_email_date = now() where id=$orderId";
		// 		dump($query);
		$db->setQuery($query);
		return $db->query();
	}
	
	
}