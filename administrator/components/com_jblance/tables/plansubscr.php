<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	15 March 2012
 * @file name	:	tables/plan.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Class for table (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
	
class TablePlanSubscr extends JTable {
	var $id = null;
	var $user_id = null;
	var $plan_id = null;
	var $approved = null;
	var $price = null;
	var $tax_percent = null;
	var $access_count = null;
	var $gateway = null;
	var $gateway_id = null;
	var $trans_id = null;
	var $fund = null;
	var $date_buy = null;
	var $date_approval = null;
	var $date_expire = null;
	var $invoiceNo = null;
	var $lifetime = null;
	var $billing_day     = null;
	var $createdAt = null;
	var $updatedAt = null;
	var $currentBillingCycle = null;
	var $status=null;
	var $planId=null;
	var $token=null;
    var $bin = null;
    var $last4 = null;
    var $cardType = null; 
    var $expirationDate = null;
    var $customerLocation = null;
    var $cardholderName   = null;
    var $imageUrl         = null;
    var $prepaid          = null;
    var $healthcare       = null;
    var $debit            = null;
    var $durbinRegulated  = null;
    var $commercial       = null;
    var $payroll          = null;
    var $issuingBank      = null;
    var $countryOfIssuance= null;
    var $productId        = null;
    var $uniqueNumberIdentifier = null;
    var $maskedNumber           = null;
		
	function __construct(&$db){
		parent::__construct('#__jblance_plan_subscr', 'id', $db);
	}
	
	public function planJoin($subscr_id){
		$db	= JFactory::getDbo();
	
		$query = "SELECT ug_id FROM #__jblance_plan_subscr ps ".
				 "INNER JOIN #__jblance_plan p ON ps.plan_id=p.id ".
				 "WHERE ps.id=".$db->quote($subscr_id);
		$db->setQuery($query);
		$ug_id	= $db->loadResult();
		return $ug_id;
	}
}
?>