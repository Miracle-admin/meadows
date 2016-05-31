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

class alphauserpointsModelSubscription extends JmodelLegacy {




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
	
	function processSubscription($subId,$pmn)
	{
    $btPlan = JblanceHelper::getBraintreePlan($subId);
	
	
	$app = JFactory::getApplication();
	$user = JFactory::getUser();
	$oldCustomer = false;
	$return = JUri::root().
		 "index.php?option=com_jblance&view=membership&layout=plans&Itemid=344";
		 
    $redirectUrl=JRoute::_(JUri::base()."index.php?option=com_users&view=login&return=".urlencode(base64_encode($return)));
	if(empty($btPlan))
	{
	$app->redirect($redirectUrl,"Invalid plan","error");
	}
	
	$dashboardUrl = JRoute::_(JUri::root()."index.php?option=com_jblance&view=user&layout=dashboarddeveloper&Itemid=368");
	
	$subPlanUrl = JRoute::_(JUri::root()."index.php?option=com_jblance&view=membership&layout=plans");
	
	$backUrl =   JRoute::_(JUri::root()."index.php?option=com_alphauserpoints&view=subscription");
	
	$pid = $btPlan['id'];
	
	$logger=JblanceHelper::get('helper.logger');
	$case="new";
	//find the customer
	
	$existCust = JblanceHelper::getBtCustomer($user->id,true);
	$exCustomer = false;
	$token = "";
	
	//customer alrerady exists
	if(count($existCust)!=0)
	{
	$oldCustomer=true;
	$exCustomer = true;
	$token=$existCust['recent_creditcard']['token'];
    $case="update";

	//its old customer 
    //get it from bt
	$updateCard = JRequest::getInt("pm",0);
	if($updateCard==1)
	{
	 $result = Braintree_PaymentMethod::update(
     $token,[
            'paymentMethodNonce' => $pmn,
            'options' => [
                         'makeDefault' => true
                         ]
            ]
            );
			  
    if($result->success)
       {
	   //card successfully updated
       $upCust = JblanceHelper::getBtCustomer($user->id);
	   $key = $user->id.".".$user->name.".bt.customer.";
	   $app->setUserState($key,$upCust);
	   $paymentMethod              = $result->paymentMethod;
	   $data                       = new stdClass();
	   $data->token                = $paymentMethod->token;
	   $data->bin                  = $paymentMethod->bin;
	   $data->last4                = $paymentMethod->last4;
	   $data->cardType             = $paymentMethod->cardType;
       $data->expirationDate       = $paymentMethod->expirationDate;
       $data->customerLocation     = $paymentMethod->customerLocation;
       $data->cardholderName       = $paymentMethod->cardholderName;
	   $data->imageUrl             = $paymentMethod->imageUrl;
	   $data->prepaid              = $paymentMethod->prepaid;
	   $data->healthcare           = $paymentMethod->healthcare;
	   $data->debit                = $paymentMethod->debit;
	   $data->durbinRegulated      = $paymentMethod->durbinRegulated;
	   $data->commercial           = $paymentMethod->commercial;
	   $data->payroll              = $paymentMethod->payroll;
	   $data->issuingBank          = $paymentMethod->issuingBank;
	   $data->countryOfIssuance    = $paymentMethod->countryOfIssuance;
	   $data->productId            = $paymentMethod->productId;
	   $data->uniqueNumberIdentifier = $paymentMethod->uniqueNumberIdentifier;
	   $data->maskedNumber           = $paymentMethod->maskedNumber;
	   //send mail
	    $jbmail = JblanceHelper::get('helper.email');
		$jbmail->addedNewCard($data,$user);
	
	   }
       else
       {
	   //failed updating card
	   $msg =  $result->message;
       $msgLog="Customer: ".$user->name."(".$user->id.")"." failed to add new card. Reason: ".$msg;
	   $logger::addLogs(array("card_error.php",JLog::INFO,"card_info",$msgLog,JLog::ERROR,"com_alphauserpoints"));
	   
	   $app->redirect($backUrl,$msg,"error");
       }

       
	   
	}
	$this->cancelSubscription($user->id,false); 
	}
	
	else
	{
	
	$result = Braintree_Customer::create([
	'id'                 =>$user->id,
    'firstName'          => $user->username,
    'email'              => $user->email,
    'paymentMethodNonce' => $pmn
    ]);
    }
	
	if($result->success || $exCustomer)
	{
	
	//customer created
	$cid = $user->id;
    $token = $exCustomer ? $token : $result->customer->paymentMethods[0]->token;
	
	if(!$exCustomer)
	{
	$msgLog="Customer: ".$user->name."(".$user->id.")"." Plan: ".$btPlan['name']." Payment token: ". $token." customer id ".$cid."Braintree response: success";
	$logger::addLogs(array("braintree_customer.php",JLog::INFO,"customer_info",$msgLog,JLog::INFO,"com_alphauserpoints"));
	}
	
    //create the subscription
	$subscription = Braintree_Subscription::create([
     'paymentMethodToken' => $token,
     'planId' => $pid,
     
     ]);
	if($subscription->success)
	{
	
	$msgLog="Customer: ".$user->name."(".$user->id.")"."SubscriptionId: ". $subscription->subscription->id." Subscription status ".$subscription->subscription->status." Braintree response: ".$subscription->subscription->status;
	$logger::addLogs(array("braintree_subscription_success.php",JLog::INFO,"Subscription_success",$msgLog,JLog::INFO,"com_alphauserpoints"));
    //trigger plugins
	JPluginHelper::importPlugin('appmeadows');
	$dispatcher = JDispatcher::getInstance();
	$dispatcher->trigger('OnNewSubscription', array($subscription,$oldCustomer,$case));
															
															
	//redirect
	$app->redirect($dashboardUrl,"Congratulations You have successfully upgraded your geek status to ".$btPlan['name'],"message");
	

	}
	else
	{
	//failed subscribing to plan
 
	$msg =  $result->message;
	$msgLog="Subscriber: ".$user->name."(".$user->id.") Braintree response: ".$msg." Plan: ".$btPlan['name']; 
	$logger::addLogs(array("braintree_subscription_error.php",JLog::ERROR,"subscription_error",$msgLog,JLog::ERROR,"com_alphauserpoints"));
	//redirect from here
	$app->redirect($subPlanUrl,$msg,"error");
	
	}
	}
	else
	{
	//unable to create customer
	$msg =  $result->message;
	$msgLog="Customer: ".$user->name."(".$user->id.") Braintree response: ".$msg." Plan: ".$btPlan['name']; 
	$logger::addLogs(array("braintree_customer_error.php",JLog::ERROR,"customer_error",$msgLog,JLog::ERROR,"com_alphauserpoints"));
	//trigger plugins
	
	//redirect from here
	$app->redirect($backUrl,$msg,"error");
	
	}
	
	
	}


function cancelSubscription($id,$return=true,$mail=false)
{
$existCust = JblanceHelper::getBtCustomer($id);
$user = JFactory::getUser($id);
if(!empty($existCust))
{
$app = JFactory::getApplication();
$subId = $existCust['recent_subscription']['subscriptionId'];

$subCancel = Braintree_Subscription::cancel($subId);


$subscriptionC = $subCancel->subscription;
if($subCancel->success)
{
$data = new stdClass();
$data->user_id         = $user->id;
$data->name            = $user->name;
$data->subscriptionId  = $subscriptionC->id;
$data->price           = $subscriptionC->price;
$data->status          = $subscriptionC->status;
$data->planId          = $subscriptionC->planId;


 
JPluginHelper::importPlugin('appmeadows');

$dispatcher = JDispatcher::getInstance();
//trigger the subscription cancel handler
$row=$dispatcher->trigger('OnCancelSubscription', array($subCancel,false));
  //send email
 if($mail)
{ 
$jbmail = JblanceHelper::get('helper.email');

$jbmail->userCanceledPlan($data);

$jbmail->userCanceledPlanA($data);
}
if($return)
{
$app->redirect(JRoute::_(JUri::root()."index.php?option=com_alphauserpoints&view=paymentmanagement&Itemid=448"));
}
}
}

}	
    
function getClientToken()
{
return Braintree_ClientToken::generate();
}


}
?>