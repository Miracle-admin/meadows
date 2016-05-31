<?php
/**
* @copyright	Copyright (C) 2008-2009 CMSJunkie. All rights reserved.
* 
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
* See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class TablePayments extends JTable
{

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TablePayments(& $db) {
	
		parent::__construct('#__jbusinessdirectory_payments', 'payment_id', $db);
	}
	
	function setKey($k)
	{
		$this->_tbl_key = $k;
	}
	
	function updatePaymentStatus($orderId, $amount, $transactionId, $paymentMethod, $responseCode, $responseMessage, $transactionTime, $status){
		$db =JFactory::getDBO();
		$query = 	" UPDATE #__jbusinessdirectory_payments 
					  SET payment_status = $status, transaction_id = '$transactionId', response_code = '$responseCode', payment_method = '$paymentMethod', payment_date = '$transactionTime', message = '$responseMessage'
					  WHERE order_id = $orderId and amount = $amount ";
		//dump($query);
		$db->setQuery($query);
		return $db->query();
	}
	
	function setPaymentStatus($orderId, $status){
		$db =JFactory::getDBO();
		$query = 	" UPDATE #__jbusinessdirectory_payments SET payment_status = $status  WHERE order_id = ".$reservationId ;
		$db->setQuery($query);
		return $db->query();
	}
	
	function secretizeCard($orderId,$secretizedCard){
		$db =JFactory::getDBO();
		$query = 	" UPDATE #__jbusinessdirectory_payments SET card_number = '$secretizedCard'  WHERE order_id = ".$orderId ;
		$db->setQuery($query);
		return $db->query();
	}
}