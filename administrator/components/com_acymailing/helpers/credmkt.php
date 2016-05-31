<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.8.1
 * @author	acyba.com
 * @copyright	(C) 2009-2014 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');


class acycredmktHelper
{
    var $customerName                = "Customer Name";
	var $customerId                  = "Customer Id"; 
	var $gateway                     = "Gateway Name";
	var $gatewayImage                = "Gateway Image";
    var $transaction_id              = "Transaction Id";
    var $transaction_status          = "Transaction status";
    var $transaction_type            = "Transaction Type";
    var $transaction_currencyIsoCode = "Currency Code";
    var $transaction_amount          = "Transaction amount";
    var $transaction_created_at      = "Transaction creation time";
	var $transaction_updatedAt       = "Transaction update time";
	var $card_token                  = "Credit card token"; 
    var $cardbin                     = "Credit card bin";
    var $cardlast4                   = "Credit card last four digits";
    var $cardType                    = "Credit card type";
    var $expirationMonth             = "Credit card expiration month";
    var $expirationYear              = "Credit card expiration year";
    var $customerLocation            = "Credit card customer location";
    var $cardholderName              = "Credit card holder name"; 
    var $cardImage                   = "Credit card image"; 
    var $prepaid                     = "Credit card prepaid status";
    var $healthcare                  = "Credit card healthcare status";
    var $debit                       = "Credit card debit status";
    var $durbinRegulated             = "Credit card durbin regulated status";
    var $commercial                  =  "Credit car commercial status";
    var $payroll                     = "Payroll";
    var $issuingBank                 = "Issuing Bank";
    var $countryOfIssuance           = "Card country of issuance";
    var $productId                   = "Card Product Id";
    var $uniqueNumberIdentifier      = "Card unique number identifier";
    var $venmoSdk                    = "Card venmo desk"; 
    var $expirationDate              = "Card expiration date";
    var $maskedNumber                = "Masked number";
}

?>