<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2015 Adrien Roussel
 * @license : GNU/GPL v3
 * @Website : http://www.nordmograph.com/extensions
 */
//-- No direct access
defined('_JEXEC') or die('=;)');
// include the helper file
require_once(dirname(__FILE__).'/helper.php');
// get a parameter from the module's configuration
// get the items to display from the helper
$results 	= ModVendorPoints2PaypalHelper::getItems();
$currency 	= ModVendorPoints2PaypalHelper::getCurrency();
$paypal_email 	= ModVendorPoints2PaypalHelper::getPaypalEmail();
// include the template for display
require(JModuleHelper::getLayoutPath('mod_vendorpoints2paypal'));