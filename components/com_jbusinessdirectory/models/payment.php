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

JTable::addIncludePath(DS.'components'.DS.JRequest::getVar('option').DS.'tables');

JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_jbusinessdirectory/models', 'Orders');

class JBusinessDirectoryModelPayment extends JModelLegacy
{ 
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Populate state
	 * @param unknown_type $ordering
	 * @param unknown_type $direction
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication('administrator');
		
		$id = JRequest::getInt('orderId');
		$this->setState('payment.orderId', $id);
		
		$paymentMethod = JRequest::getVar('payment_method');
		$this->setState('payment.payment_method', $paymentMethod);
		
	}
	
	/**
	 * Get payment methods
	 * @return multitype:unknown
	 */
	function getPaymentMethods(){
		$paymentMethods = PaymentService::getPaymentProcessors();
		return $paymentMethods;
	}
	
	/**
	 * Returns a Table object, always creating it
	 *
	 * @param   type	The table type to instantiate
	 * @param   string	A prefix for the table class name. Optional.
	 * @param   array  Configuration array for model. Optional.
	 * @return  JTable	A database object
	 */
	public function getTable($type = 'PaymentProcessors', $prefix = 'JTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	/**
	 * Get current order
	 * @return unknown
	 */
	function getOrder(){
		$model = JModelLegacy::getInstance('Orders', 'JBusinessDirectoryModel', array('ignore_request' => true));
		$order = $model->getOrder($this->getState('payment.orderId'));
		
		return $order;
	}

	/**
	 * Send payment e-mail
	 * @param unknown_type $data
	 */
	function sendPaymentEmail($data){
		
		$orderTable = JTable::getInstance("Order", "JTable", array());
		$orderTable->load($data->order_id);
		
		$properties = $orderTable->getProperties(1);
		$order = JArrayHelper::toObject($properties, 'JObject');
		$order->details = $data;
		
		if($order->amount==0){
			$order->details->processor_type = JText::_("LNG_NO_PAYMENT_INFO_REQUIRED");
		}
		
		$companiesTable = $this->getTable("Company");
		$company = $companiesTable->getCompany($order->company_id);

		$packageTable =  $this->getTable("Package");
		$order->package = $packageTable->getPackage($order->package_id);
		
		if(!isset($company->email))
			return;
	
		return EmailService::sendPaymentEmail($company, $order);
	}
	
	function sendPaymentDetailsEmail($data){
		
		$orderTable = JTable::getInstance("Order", "JTable", array());
		$orderTable->load($data->order_id);
	
		$properties = $orderTable->getProperties(1);
		$order = JArrayHelper::toObject($properties, 'JObject');
		$order->details = $data;
		
		$companiesTable = $this->getTable("Company");
		$company = $companiesTable->getCompany($order->company_id);
		
		$packageTable =  $this->getTable("Package");
		$order->package = $packageTable->getPackage($order->package_id);
		
		if(!isset($company->email))
			return;
	
		return EmailService::sendPaymentDetailsEmail($company, $order);
	}
	
}

?>