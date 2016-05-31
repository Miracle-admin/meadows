<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2015 Adrien Roussel
 * @license : GNU/GPL v3
 * @Website : http://www.nordmograph.com/extensions
 */
defined('_JEXEC') or die('=;)');
class ModVendorPoints2PaypalHelper
{
    static function getItems()
    {
        $db 	= JFactory::getDBO();
		$user = JFactory::getUser();	
        $q = 'SELECT `points`
		FROM `#__vmvendor_userpoints` 				  
		WHERE `userid` ='.$user->id;
        $db->setQuery($q);
        $points = $db->loadResult();
        return $points;
    }

	static function getCurrency()
    {
        $db = JFactory::getDBO();
        $q ="SELECT vc.`currency_code_3` , vc.`currency_symbol` , vc.`currency_positive_style` , vc.`currency_decimal_place` , vc.`currency_decimal_symbol` , vc.`currency_thousands` 
        FROM `#__virtuemart_currencies` vc 
        LEFT JOIN `#__virtuemart_vendors` vv ON vv.`vendor_currency` = vc.`virtuemart_currency_id` 
        WHERE vv.`virtuemart_vendor_id` ='1' " ;        
        $db->setQuery($q);
        $results = $db->loadRow();
        return $results;
    }
    static function getPaypalEmail()
    {
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $q ="SELECT `paypal_email` 
        FROM `#__vmvendor_paypal_emails`
        WHERE userid='".$user->id."' " ;        
        $db->setQuery($q);
        $results = $db->loadResult();
        return $results;
    }
}