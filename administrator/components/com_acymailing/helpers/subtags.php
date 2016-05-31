<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.8.1
 * @author	acyba.com
 * @copyright	(C) 2009-2014 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');


class acysubtagsHelper
{
    var $username                  = "Username of the user";
	var $subscriptionId            = "Subscription id";
	var $subscriptionName          = "Subscription Name";
	var $gateway                   = "Gateway";
	var $gatewayImage              ="Gateway image";
	var $amount                    = "Amount";
    var $transid                   = "Transaction id";
	var $date_buy                  = "Subscription date buy";
	var $date_approval             = "Sbscription date approval";
	var $date_expire               = "Subscription date expire";
	var $billing_day               = "Subscription billing day";
	var $createdAt                 = "Subscription created at";
	var $updatedAt                 = "Subscription updated at";
	var $currentBillingCycle       = "Current billing cycle of subscription";
    var $status                    = "Subscription status";
	var $planId                    = "Subscription plan id";
	var $canceledOn                = "Plan canceled on";
	var $overdueOn                 = "Plan went overdue on";
	//card details
	var $token                     = "Card token";
	var $bin                       = "Card bin";
	var $last4                     = "Card last four";
	var $cardType                  = "Card type";
    var $expirationDate            = "Card expiration date";
    var $customerLocation          = "Customer location";
    var $cardholderName            = "Card holder name";
	var $cardImage                  = "Card image"; 
	var $prepaid                   = "Card prepaid status"; 
	var $healthcare                = "Card healthcare"; 
	var $debit                     = "Card debit"; 
	var $durbinRegulated           = "Card durbin regulated"; 
	var $commercial                = "Card commercial status"; 
	var $payroll                   = "Card payroll status"; 
	var $issuingBank               = "Card issuing bank"; 
	var $countryOfIssuance         = "Card country of issuance"; 
	var $productId                 = "Card product Id"; 
	var $uniqueNumberIdentifier    = "Card unique identifier"; 
	var $maskedNumber              = "Card masked number"; 

}

?>