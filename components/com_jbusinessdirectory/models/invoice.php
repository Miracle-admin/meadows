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


class JBusinessDirectoryModelInvoice extends JModelItem
{ 
	
	function __construct()
	{
		parent::__construct();

	}

	function getInvoice(){
		
		$invoiceId = JRequest::getInt("invoiceId");
		
		$orderTable = JTable::getInstance("Order", "JTable", array());
		$order = $orderTable->getOrder($invoiceId);
		
		$companyTable = $table = JTable::getInstance("Company", "JTable", array());
		$company = $companyTable->getCompany($order->company_id);
		$order->companyName = $company->name;
		
		$user = JFactory::getUser();
		$billingDetailsTable = JTable::getInstance("BillingDetails", "JTable", array());
		$order->billingDetails = $billingDetailsTable->getBillingDetails($user->id);
		
		$order->company = $company;
		
		$packageTable = $table = JTable::getInstance("Package", "JTable", array());
		$package = $packageTable->getPackage($order->package_id);
		$order->packageName = $package->name;
		$order->package = $package;
		
		return $order;
	}
}
?>

