<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view' );
jimport( 'joomla.html.pagination' );

class alphauserpointsViewPaymentmanagement extends JViewLegacy
{


	function display($tpl = null) 
	    {
        $user = JFactory::getUser();
		
		$app = JFactory::getApplication();
		
        if($user->guest)
        {
        $return = JRoute::_(JUri::root()."index.php?option=com_alphauserpoints&view=paymentmanagement&Itemid=448");
		
	    $redirectUrl=JRoute::_(JUri::base()."index.php?option=com_users&view=login&return=".urlencode(base64_encode($return)));
	    $app->redirect($redirectUrl,"Please login to proceed further","error");
        }		
        $customer = $this->get("customer");
		
		
		
		if(empty($customer) || empty($customer['customer']['id']))
		{
		$return = JRoute::_(JUri::root()."index.php?option=com_jblance&view=membership&layout=plans&Itemid=344");
		
	    
	    $app->redirect($return,"You do not have an active subscription.","error");
		}
		
		
		
		$token = $this->get('ClientToken');
	
		$this->assignref("customer",$customer['customer']);
		
		
		
        $this->assignref("card",$customer["recent_creditcards"]);
		$this->assignref("token",$token);
		$this->assignref("subscriptions",$customer['recent_subscriptions']);
		 parent::display($tpl); 
	    }
	
	private function _validateRequest($user,$app)
	{
		
		
	}
	function getPlanName($pid)
	{
	$db = JFactory::getDbo();
	$q="SELECT name FROM #__jblance_plan WHERE pidbt='".$pid."'";
	$db->setQuery($q);
	$name  = $db->loadObject();
	return $name->name;
	
	}
	
	
}
?>