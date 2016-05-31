<?php
/*
 * @component AlphaUserPoints, Copyright (C) 2008-2015 Bernard Gilly, http://www.alphaplug.com
 * Extension menu created by Mike Gusev (migus)
 * @copyright Copyright (C) 2011 Mike Gusev (migus) - Updated by Bernard Gilly for full compatibility with Joomla 3.1.x on June 2013
 * @license : GNU/GPL
 * @Website : http://migusbox.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class alphauserpointsModelPaymentmanagement extends JmodelLegacy {




	function __construct(){
	
	$apiBt=JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'libraries'.DS.'bt'.DS.'lib'.DS.'Braintree.php';
	
    include_once($apiBt);
	
	jimport('joomla.application.component.helper');
	$params=  JComponentHelper::getParams('com_alphauserpoints');
	$mode = $params->get('mode') != 1?"production":"sandbox";
    $bt_merch_id = $params->get('bt_merch_id');
    $bt_pub_key = $params->get('bt_pub_key');
    $bt_pvt_key = $params->get('bt_pvt_key');
	
    
    Braintree\Configuration::environment($mode);
    Braintree\Configuration::merchantId($bt_merch_id);
    Braintree\Configuration::publicKey($bt_pub_key);
    Braintree\Configuration::privateKey($bt_pvt_key);
	
		parent::__construct();
		
	}
	
	function getCustomer()
	{
    $user = JFactory::getUser();
	
	$customer = JblanceHelper::getBtFullCustomer($user->id);
	
	$customer['customer'] = array('id' => $customer['id'],'firstName' => $customer['firstName'] ,'email' => $customer['email'],'createdAt' => $customer['createdAt'] ,
    'updatedAt' => $customer['updatedAt']);
	
	return $customer; 
    }
function getClientToken()
{
return Braintree_ClientToken::generate();
}

}
?>