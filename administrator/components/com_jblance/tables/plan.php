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
	
class TablePlan extends JTable {
	var $id = null;
	var $name = null;
	var $pidbt = null;
	var $days = null;
	var $days_type = null;
	var $price = null;
	var $discount = null;
	var $published = null;
	var $invisible = null;
	var $sellfeatured= null;
    var $sellurgent= null;
    var $sellprivate= null;
    var $sellsealed= null;
	var $sellassisted = null;
	var $sellnda = null;
	var $ordering = null;
	var $time_limit = null;
	var $alert_admin = null;
	var $adwords = null;
	var $bonusFund = null;
	var $description = null;
	var $finish_msg = null;
	var $params = null;
	var $ug_id = null;
	var $default_plan = null;
	var $user_featured = null;
	var $lifetime = null;
	var $paymentmode=null;
	var $merchantId            =    null;      
    var $billingDayOfMonth     =    null;     
    var $billingFrequency      =    null;
    var $currencyIsoCode       =    null;
    var $bt_description        =    null;         
    var $numberOfBillingCycles =    null;  
    var $price_bt              =    null;          
    var $trialDuration         =    null;
    var $trialDurationUnit     =    null;
    var $trialPeriod           =    null;
    var $createdAt             =    null;
    var $updatedAt             =    null;
			
	function __construct(&$db){
		parent::__construct('#__jblance_plan', 'id', $db);
	}
}
?>