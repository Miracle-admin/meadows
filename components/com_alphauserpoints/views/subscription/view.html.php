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

class alphauserpointsViewSubscription extends JViewLegacy
{


	function display($tpl = null) 
	    {		
		 $app=JFactory::getApplication();
		 //caching is not required on payment page
		 $app->setHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
		 $app->setHeader('Pragma', 'no-cache');
		 $app->setHeader('Expires', '0');
		 $subId = JRequest::getVar("subid");
	     $this->_validateRequest();
		 $btPlan = JblanceHelper::getBraintreePlan($subId,true);
		 
		
		 $user = JFactory::getUser();
		 
		 $return = JUri::root().
		 "index.php?option=com_jblance&view=membership&layout=plans&Itemid=344";
		 
		 $redirectUrl=JRoute::_(JUri::base()."index.php?option=com_users&view=login&return=".urlencode(base64_encode($return)));
		 //not logged in
		
		 
		 
		 $groups = $user->groups;
		 //only for developers
		 if(!in_array(13,$groups ))
		 {
		 $app->redirect(JRoute::_(JUri::root()),"Sorry, but this facility is only available for developers","error");
		 }
		 //plan not available
		 if(empty($btPlan))
		 {
		 $app->redirect(JRoute::_(Juri::root()."index.php?option=com_jblance&view=membership&layout=plans"),"Invalid plan","error");
		 }
		 
		 //fine
		 $planname         = $btPlan['name'];
		 $billingFrequency = $btPlan['billingFrequency'];
		 $ammount          = $btPlan['price'];
		 $description      = $btPlan['description'];
		 $customer         = JblanceHelper::getBtCustomer($user->id);
		 $subscript        = $customer['recent_subscription'];
		 
		 //can't purchase same plan for second time
		 
		 
		 $this->assignRef("planname",$planname);
		  $this->assignRef("customer",$customer);
		 $this->assignRef("billingFrequency",$billingFrequency);
		 
		 $this->assignRef("ammount",round($ammount));
		 
		 $this->assignRef("description",$description);
		 
		 $this->assignRef("subid",$subId);
		 $token=$this->get('ClientToken');
		 $this->assignRef("token",$token);
		 
		 parent::display($tpl);
	    }
	
	private function _validateRequest()
	{
	$user             = JFactory::getUser();
	$app              = JFactory::getApplication();
	$subId            = JRequest::getVar("subid");
	
	if($user->guest)
	{
	$return = JRoute::_(JUri::root()."index.php?option=com_alphauserpoints&view=subscription&subid=".$subId);
	$redirectUrl=JRoute::_(JUri::base()."index.php?option=com_users&view=login&return=".urlencode(base64_encode($return)));
	$app->redirect($redirectUrl,"Please login to proceed further","error");
	}
	else
	{
	$customer         = JblanceHelper::getBtCustomer($user->id);
    $subscript        = $customer['recent_subscription'];
	$cursub           = $subscript['plan_id']; 
    $status           = $subscript['status'];	
	
	
	if($status=="Active" && $subId==$cursub)
	{
	$app->redirect(JRoute::_(JUri::root()."index.php?option=com_alphauserpoints&view=paymentmanagement&Itemid=448"),"You have already subscribed for this plan.","error");
	}
	
	
	}
	
		
		}
	
	
	
}
?>