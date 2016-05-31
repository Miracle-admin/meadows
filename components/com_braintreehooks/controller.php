<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

class BraintreehooksController extends JControllerLegacy
{

private $hooks;

//construct the params

public function __construct()
	{
	parent::__construct();
	
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
	
	$model = $this->getModel('hooks');
	$items = $model->getItems();
	
	foreach($items as $ik => $iv)
	{
	$this->hooks[trim($iv->webhook_name)]=$items[$ik];
	}
	
	}



//Subscription was canceled
public function Canceled()
{
$webhook_name="Canceled";  

$app=JFactory::getApplication();
$config = $this->hooks[$webhook_name];
$active= $config->active;
if($active)
{
//validate hooks

$this->_validateHook(); 


JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_braintreehooks/tables');

$rowEH=JTable::getInstance('edithooks', 'BraintreehooksTable');

$rowEH->load($config->id);

$post 	= $app->input->post->getArray();

$bt_signature = JRequest::getVar("bt_signature");
$bt_payload   = JRequest::getVar("bt_payload"); 


if(!empty($bt_signature) && !empty($bt_payload))
{
try
{
$subscription  = Braintree_WebhookNotification::parse($bt_signature,$bt_payload);

$triggeredOn   = $subscription->timestamp->format('Y-m-d H:i:s');

$status        = $subscription->subject['subscription']['statusHistory'][0]['status'];

$planId        = $subscription->subject['subscription']['planId'];

$subId         = $subscription->subject['subscription']['id'];


$cust          = $subscription->subject['subscription']['transactions'][0]['customer']['id'];

$type          = "Canceled";

$params        = array($triggeredOn,$status,$planId,$subId,$type,$rowEH,$cust);

if(!$this->validateSubscriptions($planId))
{
JPluginHelper::importPlugin('appmeadows');

$dispatcher = JDispatcher::getInstance();

$dispatcher->trigger('OnWebhookTriggered', $params);
}

}
catch(Exception $e)
{
$subscription = $e;
}

}
else
{
$subscription = "Unable to process the subscription";
}


Jexit();
}
else{
Jexit("#__FORBIDDEN");

}
}



//Subscription was successfully charged
public function ChargedSuccessfully()
{
$webhook_name="Charged Successfully";

$app=JFactory::getApplication();
$config = $this->hooks[$webhook_name];
$active= $config->active;
if($active)
{
//validate hooks

$this->_validateHook(); 


JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_braintreehooks/tables');

$rowEH=JTable::getInstance('edithooks', 'BraintreehooksTable');

$rowEH->load($config->id);

$post 	= $app->input->post->getArray();

$bt_signature = JRequest::getVar("bt_signature");
$bt_payload   = JRequest::getVar("bt_payload"); 


if(!empty($bt_signature) && !empty($bt_payload))
{
try
{
$subscription  = Braintree_WebhookNotification::parse($bt_signature,$bt_payload);

$triggeredOn   = $subscription->timestamp->format('Y-m-d H:i:s');

$status        = $subscription->subject['subscription']['statusHistory'][0]['status'];

$planId        = $subscription->subject['subscription']['planId'];

$subId         = $subscription->subject['subscription']['id'];

$cust          = $subscription->subject['subscription']['transactions'][0]['customer']['id'];

$type          = "Charged Successfully";

$params        = array($triggeredOn,$status,$planId,$subId,$type,$rowEH,$cust);

if(!$this->validateSubscriptions($planId))
{
JPluginHelper::importPlugin('appmeadows');

$dispatcher = JDispatcher::getInstance();

$dispatcher->trigger('OnWebhookTriggered', $params);
}

}
catch(Exception $e)
{
$subscription = $e;
}

}
else
{
$subscription = "Unable to process the subscription";
}


Jexit();
}
else{
Jexit("#__FORBIDDEN");

}
}	



//Subscription didn't charge
public function ChargedUnsuccessfully()
{
$webhook_name="Charged Unsuccessfully";

$app=JFactory::getApplication();
$config = $this->hooks[$webhook_name];
$active= $config->active;
if($active)
{
//validate hooks

$this->_validateHook(); 


JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_braintreehooks/tables');

$rowEH=JTable::getInstance('edithooks', 'BraintreehooksTable');

$rowEH->load($config->id);

$post 	= $app->input->post->getArray();

$bt_signature = JRequest::getVar("bt_signature");
$bt_payload   = JRequest::getVar("bt_payload"); 


if(!empty($bt_signature) && !empty($bt_payload))
{
try
{
$subscription  = Braintree_WebhookNotification::parse($bt_signature,$bt_payload);

$triggeredOn   = $subscription->timestamp->format('Y-m-d H:i:s');

$status        = $subscription->subject['subscription']['statusHistory'][0]['status'];

$planId        = $subscription->subject['subscription']['planId'];

$subId         = $subscription->subject['subscription']['id'];

$cust          = $subscription->subject['subscription']['transactions'][0]['customer']['id'];

$type          = "Charged Unsuccessfully";

$params        = array($triggeredOn,$status,$planId,$subId,$type,$rowEH,$cust);

if(!$this->validateSubscriptions($planId))
{
JPluginHelper::importPlugin('appmeadows');

$dispatcher = JDispatcher::getInstance();

$dispatcher->trigger('OnWebhookTriggered', $params);
}

}
catch(Exception $e)
{
$subscription = $e;
}

}
else
{
$subscription = "Unable to process the subscription";
}


Jexit();
}
else{
Jexit("#__FORBIDDEN");

}
}	



//Subscription has expired
public function Expired()
{
$webhook_name="Expired";

$app=JFactory::getApplication();
$config = $this->hooks[$webhook_name];
$active= $config->active;
if($active)
{
//validate hooks

$this->_validateHook(); 


JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_braintreehooks/tables');

$rowEH=JTable::getInstance('edithooks', 'BraintreehooksTable');

$rowEH->load($config->id);

$post 	= $app->input->post->getArray();

$bt_signature = JRequest::getVar("bt_signature");
$bt_payload   = JRequest::getVar("bt_payload"); 


if(!empty($bt_signature) && !empty($bt_payload))
{
try
{
$subscription  = Braintree_WebhookNotification::parse($bt_signature,$bt_payload);

$triggeredOn   = $subscription->timestamp->format('Y-m-d H:i:s');

$status        = $subscription->subject['subscription']['statusHistory'][0]['status'];

$planId        = $subscription->subject['subscription']['planId'];

$subId         = $subscription->subject['subscription']['id'];

$cust          = $subscription->subject['subscription']['transactions'][0]['customer']['id'];

$type          = "Expired";

$params        = array($triggeredOn,$status,$planId,$subId,$type,$rowEH,$cust);

if(!$this->validateSubscriptions($planId))
{
JPluginHelper::importPlugin('appmeadows');

$dispatcher = JDispatcher::getInstance();

$dispatcher->trigger('OnWebhookTriggered', $params);
}

}
catch(Exception $e)
{
$subscription = $e;
}

}
else
{
$subscription = "Unable to process the subscription";
}


Jexit();
}
else{
Jexit("#__FORBIDDEN");

}
}	



//Subscription trial ended
public function TrialEnded()
{

$webhook_name="Trial Ended";

$app=JFactory::getApplication();
$config = $this->hooks[$webhook_name];
$active= $config->active;
if($active)
{
//validate hooks

$this->_validateHook(); 


JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_braintreehooks/tables');

$rowEH=JTable::getInstance('edithooks', 'BraintreehooksTable');

$rowEH->load($config->id);

$post 	= $app->input->post->getArray();

$bt_signature = JRequest::getVar("bt_signature");
$bt_payload   = JRequest::getVar("bt_payload"); 


if(!empty($bt_signature) && !empty($bt_payload))
{
try
{
$subscription  = Braintree_WebhookNotification::parse($bt_signature,$bt_payload);

$triggeredOn   = $subscription->timestamp->format('Y-m-d H:i:s');

$status        = $subscription->subject['subscription']['statusHistory'][0]['status'];

$planId        = $subscription->subject['subscription']['planId'];

$subId         = $subscription->subject['subscription']['id'];

$cust          = $subscription->subject['subscription']['transactions'][0]['customer']['id'];

$type          = "Trial Ended";

$params        = array($triggeredOn,$status,$planId,$subId,$type,$rowEH,$cust);

if(!$this->validateSubscriptions($planId))
{
JPluginHelper::importPlugin('appmeadows');

$dispatcher = JDispatcher::getInstance();

$dispatcher->trigger('OnWebhookTriggered', $params);
}

}
catch(Exception $e)
{
$subscription = $e;
}

}
else
{
$subscription = "Unable to process the subscription";
}


Jexit();
}
else{
Jexit("#__FORBIDDEN");

}
}	


//Subscription started
public function WentActive()
{
$webhook_name="Went Active";

$app=JFactory::getApplication();
$config = $this->hooks[$webhook_name];
$active= $config->active;
if($active)
{
//validate hooks

$this->_validateHook(); 


JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_braintreehooks/tables');

$rowEH=JTable::getInstance('edithooks', 'BraintreehooksTable');

$rowEH->load($config->id);

$post 	= $app->input->post->getArray();

$bt_signature = JRequest::getVar("bt_signature");
$bt_payload   = JRequest::getVar("bt_payload"); 


if(!empty($bt_signature) && !empty($bt_payload))
{
try
{
$subscription  = Braintree_WebhookNotification::parse($bt_signature,$bt_payload);

$triggeredOn   = $subscription->timestamp->format('Y-m-d H:i:s');

$status        = $subscription->subject['subscription']['statusHistory'][0]['status'];

$planId        = $subscription->subject['subscription']['planId'];

$subId         = $subscription->subject['subscription']['id'];

$cust          = $subscription->subject['subscription']['transactions'][0]['customer']['id'];

$type          = "Subscription Went Active";

$params        = array($triggeredOn,$status,$planId,$subId,$type,$rowEH,$cust);

if(!$this->validateSubscriptions($planId))
{
JPluginHelper::importPlugin('appmeadows');

$dispatcher = JDispatcher::getInstance();

$dispatcher->trigger('OnWebhookTriggered', $params);
}

}
catch(Exception $e)
{
$subscription = $e;
}

}
else
{
$subscription = "Unable to process the subscription";
}


Jexit();
}
else{
Jexit("#__FORBIDDEN");

}

}	

//The subscription lapsed and is past due
public function WentPastDue()
{
$webhook_name="Went Past Due";

$app=JFactory::getApplication();
$config = $this->hooks[$webhook_name];
$active= $config->active;


if($active)
{
//validate hooks

$this->_validateHook(); 


JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_braintreehooks/tables');

$rowEH=JTable::getInstance('edithooks', 'BraintreehooksTable');

$rowEH->load($config->id);

$post 	= $app->input->post->getArray();

$bt_signature = JRequest::getVar("bt_signature");
$bt_payload   = JRequest::getVar("bt_payload"); 


if(!empty($bt_signature) && !empty($bt_payload))
{
try
{
$subscription  = Braintree_WebhookNotification::parse($bt_signature,$bt_payload);

$triggeredOn   = $subscription->timestamp->format('Y-m-d H:i:s');

$status        = $subscription->subject['subscription']['statusHistory'][0]['status'];

$planId        = $subscription->subject['subscription']['planId'];

$subId         = $subscription->subject['subscription']['id'];

$cust          = $subscription->subject['subscription']['transactions'][0]['customer']['id'];

$type          = "Went Past Due";

$params        = array($triggeredOn,$status,$planId,$subId,$type,$rowEH,$cust);

if(!$this->validateSubscriptions($planId))
{

//send plan went active email
$user = JFactory::getUser();
$data = new stdClass();
$data->user_id         = $user->id;
$data->name            = $user->name;
$data->subscriptionId  = $subId;
$data->price           = $subscription->subject['subscription']['price'];
$data->status          = $status;
$data->planId          = $planId;


$apiJb=JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jblance'.DS.'helpers'.DS.'email.php';
	
include_once($apiJb);


$jbmail = JblanceHelper::get('helper.email');

$jbmail->userPlanOverdue($data);

$jbmail->userPlanOverdueA($data);

JPluginHelper::importPlugin('appmeadows');

$dispatcher = JDispatcher::getInstance();

$dispatcher->trigger('OnWebhookTriggered', $params);
}

}
catch(Exception $e)
{
$subscription = $e;
}

}
else
{
$subscription = "Unable to process the subscription";
}


Jexit();
}
else{
Jexit("#__FORBIDDEN");

}
}	



//Transaction Disbursed
public function Disbursed()
{
$webhook_name="Disbursed";

$app=JFactory::getApplication();
$config = $this->hooks[$webhook_name];
$active= $config->active;
if($active)
{
//validate hooks

$this->_validateHook(); 


JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_braintreehooks/tables');

$rowEH=JTable::getInstance('edithooks', 'BraintreehooksTable');

$rowEH->load($config->id);

$post 	= $app->input->post->getArray();

$bt_signature = JRequest::getVar("bt_signature");
$bt_payload   = JRequest::getVar("bt_payload"); 


if(!empty($bt_signature) && !empty($bt_payload))
{
try
{
$subscription  = Braintree_WebhookNotification::parse($bt_signature,$bt_payload);

$triggeredOn   = $subscription->timestamp->format('Y-m-d H:i:s');

$status        = $subscription->subject['subscription']['statusHistory'][0]['status'];

$planId        = $subscription->subject['subscription']['planId'];

$subId         = $subscription->subject['subscription']['id'];

$cust          = $subscription->subject['subscription']['transactions'][0]['customer']['id'];


$type          = "Disbursed";

$params        = array($triggeredOn,$status,$planId,$subId,$type,$rowEH,$cust);

if(!$this->validateSubscriptions($planId))
{
JPluginHelper::importPlugin('appmeadows');

$dispatcher = JDispatcher::getInstance();

$dispatcher->trigger('OnWebhookTriggered', $params);
}

}
catch(Exception $e)
{
$subscription = $e;
}

}
else
{
$subscription = "Unable to process the subscription";
}


Jexit();
}
else{
Jexit("#__FORBIDDEN");

}

}	



//A dispute was opened
public function Opened()
{
$webhook_name="Opened";

$app=JFactory::getApplication();
$config = $this->hooks[$webhook_name];
$active= $config->active;
if($active)
{
//validate hooks

$this->_validateHook(); 


JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_braintreehooks/tables');

$rowEH=JTable::getInstance('edithooks', 'BraintreehooksTable');

$rowEH->load($config->id);

$post 	= $app->input->post->getArray();

$bt_signature = JRequest::getVar("bt_signature");
$bt_payload   = JRequest::getVar("bt_payload"); 


if(!empty($bt_signature) && !empty($bt_payload))
{
try
{
$subscription  = Braintree_WebhookNotification::parse($bt_signature,$bt_payload);

$triggeredOn   = $subscription->timestamp->format('Y-m-d H:i:s');

$status        = $subscription->subject['subscription']['statusHistory'][0]['status'];

$planId        = $subscription->subject['subscription']['planId'];

$subId         = $subscription->subject['subscription']['id'];

$cust          = $subscription->subject['subscription']['transactions'][0]['customer']['id'];

$type          = "A dispute was opened";

$params        = array($triggeredOn,$status,$planId,$subId,$type,$rowEH,$cust);

if(!$this->validateSubscriptions($planId))
{
JPluginHelper::importPlugin('appmeadows');

$dispatcher = JDispatcher::getInstance();

$dispatcher->trigger('OnWebhookTriggered', $params);
}

}
catch(Exception $e)
{
$subscription = $e;
}

}
else
{
$subscription = "Unable to process the subscription";
}


Jexit();
}
else{
Jexit("#__FORBIDDEN");

}
}


//An open dispute was lost
public function Lost()
{
$webhook_name="Lost";

$app=JFactory::getApplication();
$config = $this->hooks[$webhook_name];
$active= $config->active;
if($active)
{
//validate hooks

$this->_validateHook(); 


JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_braintreehooks/tables');

$rowEH=JTable::getInstance('edithooks', 'BraintreehooksTable');

$rowEH->load($config->id);

$post 	= $app->input->post->getArray();

$bt_signature = JRequest::getVar("bt_signature");
$bt_payload   = JRequest::getVar("bt_payload"); 


if(!empty($bt_signature) && !empty($bt_payload))
{
try
{
$subscription  = Braintree_WebhookNotification::parse($bt_signature,$bt_payload);

$triggeredOn   = $subscription->timestamp->format('Y-m-d H:i:s');

$status        = $subscription->subject['subscription']['statusHistory'][0]['status'];

$planId        = $subscription->subject['subscription']['planId'];

$subId         = $subscription->subject['subscription']['id'];

$cust          = $subscription->subject['subscription']['transactions'][0]['customer']['id'];

$type          = "An open dispute was lost";

$params        = array($triggeredOn,$status,$planId,$subId,$type,$rowEH,$cust);

if(!$this->validateSubscriptions($planId))
{
JPluginHelper::importPlugin('appmeadows');

$dispatcher = JDispatcher::getInstance();

$dispatcher->trigger('OnWebhookTriggered', $params);
}

}
catch(Exception $e)
{
$subscription = $e;
}

}
else
{
$subscription = "Unable to process the subscription";
}


Jexit();
}
else{
Jexit("#__FORBIDDEN");

}
}


//An open dispute was won
public function Won()
{
$webhook_name="Won";

$app=JFactory::getApplication();
$config = $this->hooks[$webhook_name];
$active= $config->active;
if($active)
{
//validate hooks

$this->_validateHook(); 


JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_braintreehooks/tables');

$rowEH=JTable::getInstance('edithooks', 'BraintreehooksTable');

$rowEH->load($config->id);

$post 	= $app->input->post->getArray();

$bt_signature = JRequest::getVar("bt_signature");
$bt_payload   = JRequest::getVar("bt_payload"); 


if(!empty($bt_signature) && !empty($bt_payload))
{
try
{
$subscription  = Braintree_WebhookNotification::parse($bt_signature,$bt_payload);

$triggeredOn   = $subscription->timestamp->format('Y-m-d H:i:s');

$status        = $subscription->subject['subscription']['statusHistory'][0]['status'];

$planId        = $subscription->subject['subscription']['planId'];

$subId         = $subscription->subject['subscription']['id'];

$cust          = $subscription->subject['subscription']['transactions'][0]['customer']['id'];

$type          = "An open dispute was won";

$params        = array($triggeredOn,$status,$planId,$subId,$type,$rowEH,$cust);

if(!$this->validateSubscriptions($planId))
{
JPluginHelper::importPlugin('appmeadows');

$dispatcher = JDispatcher::getInstance();

$dispatcher->trigger('OnWebhookTriggered', $params);
}

}
catch(Exception $e)
{
$subscription = $e;
}

}
else
{
$subscription = "Unable to process the subscription";
}


Jexit();
}
else{
Jexit("#__FORBIDDEN");

}
}


//Disbursement was sent to your account
public function Disbursement()
{
$webhook_name="Disbursement";

$app=JFactory::getApplication();
$config = $this->hooks[$webhook_name];
$active= $config->active;
if($active)
{
//validate hooks

$this->_validateHook(); 


JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_braintreehooks/tables');

$rowEH=JTable::getInstance('edithooks', 'BraintreehooksTable');

$rowEH->load($config->id);

$post 	= $app->input->post->getArray();

$bt_signature = JRequest::getVar("bt_signature");
$bt_payload   = JRequest::getVar("bt_payload"); 


if(!empty($bt_signature) && !empty($bt_payload))
{
try
{
$subscription  = Braintree_WebhookNotification::parse($bt_signature,$bt_payload);

$triggeredOn   = $subscription->timestamp->format('Y-m-d H:i:s');

$status        = $subscription->subject['subscription']['statusHistory'][0]['status'];

$planId        = $subscription->subject['subscription']['planId'];

$subId         = $subscription->subject['subscription']['id'];

$cust          = $subscription->subject['subscription']['transactions'][0]['customer']['id'];

$type          = "Disbursement";

$params        = array($triggeredOn,$status,$planId,$subId,$type,$rowEH,$cust);

if(!$this->validateSubscriptions($planId))
{
JPluginHelper::importPlugin('appmeadows');

$dispatcher = JDispatcher::getInstance();

$dispatcher->trigger('OnWebhookTriggered', $params);
}

}
catch(Exception $e)
{
$subscription = $e;
}

}
else
{
$subscription = "Unable to process the subscription";
}


Jexit();
}
else{
Jexit("#__FORBIDDEN");

}
}


//Only applies to marketplace merchants
public function DisbursementException()
{
$webhook_name="Disbursement Exception";

$app=JFactory::getApplication();
$config = $this->hooks[$webhook_name];
$active= $config->active;
if($active)
{
//validate hooks

$this->_validateHook(); 


JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_braintreehooks/tables');

$rowEH=JTable::getInstance('edithooks', 'BraintreehooksTable');

$rowEH->load($config->id);

$post 	= $app->input->post->getArray();

$bt_signature = JRequest::getVar("bt_signature");
$bt_payload   = JRequest::getVar("bt_payload"); 


if(!empty($bt_signature) && !empty($bt_payload))
{
try
{
$subscription  = Braintree_WebhookNotification::parse($bt_signature,$bt_payload);

$triggeredOn   = $subscription->timestamp->format('Y-m-d H:i:s');

$status        = $subscription->subject['subscription']['statusHistory'][0]['status'];

$planId        = $subscription->subject['subscription']['planId'];

$subId         = $subscription->subject['subscription']['id'];

$cust          = $subscription->subject['subscription']['transactions'][0]['customer']['id'];

$type          = "Disbursement Exception";

$params        = array($triggeredOn,$status,$planId,$subId,$type,$rowEH,$cust);

if(!$this->validateSubscriptions($planId))
{
JPluginHelper::importPlugin('appmeadows');

$dispatcher = JDispatcher::getInstance();

$dispatcher->trigger('OnWebhookTriggered', $params);
}

}
catch(Exception $e)
{
$subscription = $e;
}

}
else
{
$subscription = "Unable to process the subscription";
}


Jexit();
}
else{
Jexit("#__FORBIDDEN");

}
}	

private function _validateHook()
{
$btChalange = JRequest::getVar("bt_challenge","");
if(!empty($btChalange)) 
{
try{
echo(Braintree_WebhookNotification::verify($btChalange));
}
catch(Exception $e)
{
echo"<pre>";
print_r($e);
die;
}
Jexit();
}

}
private function _createHtml($file,$data,$us)
{
ob_start();

$sub      = $data->subscription;
$customer = Braintree_Customer::find($us);
$subLat   = array_reverse($customer->creditCards[0]->subscriptions);

foreach($subLat as $val)
{
echo $val->id."<br>"; 
}

$output = ob_get_clean();

$outputFile = JPATH_SITE.DS."logshtml".DS.$file;

$fileHandle = fopen($outputFile, 'w') or die('File creation error.');

fwrite($fileHandle, $output);

fclose($fileHandle);

return true;

}	



	
}
?>