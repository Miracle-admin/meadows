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
//validate the bt challange

$this->_validateHook();
$webhook_name="Canceled";

$app=JFactory::getApplication();
$config = $this->hooks[$webhook_name];

JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_braintreehooks/tables');

$row=JTable::getInstance('edithooks', 'BraintreehooksTable');

$row->load($config->id);

$post 	= $app->input->post->getArray();

$bt_signature = JRequest::getVar("bt_signature");
$bt_payload   = JRequest::getVar("bt_payload"); 


if(!empty($bt_signature) && !empty($bt_payload))
{
try
{
$subscription = Braintree_WebhookNotification::parse($bt_signature,$bt_payload);

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
$this->_createHtml("Canceled.html",$subscription);
Jexit();
}



//Subscription was successfully charged
public function ChargedSuccessfully()
{
//validate the bt challange

$this->_validateHook();

$webhook_name="Charged Successfully";

$app=JFactory::getApplication();
$config = $this->hooks[$webhook_name];

JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_braintreehooks/tables');

$row=JTable::getInstance('edithooks', 'BraintreehooksTable');

$row->load($config->id);

$post 	= $app->input->post->getArray();

$bt_signature = JRequest::getVar("bt_signature");
$bt_payload   = JRequest::getVar("bt_payload"); 


if(!empty($bt_signature) && !empty($bt_payload))
{
try
{
$subscription = Braintree_WebhookNotification::parse($bt_signature,$bt_payload);

}
catch(Exception $e)
{
$subscription = $e;
}

}
else
{
$subscription = "ChargedSuccessfully:Unable to process the subscription";

}
$this->_createHtml("ChargedSuccessfully.html",$subscription);
Jexit();
}	



//Subscription didn't charge
public function ChargedUnsuccessfully()
{
//validate the bt challange

$this->_validateHook();
$webhook_name="Charged Unsuccessfully";

$app=JFactory::getApplication();
$config = $this->hooks[$webhook_name];

JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_braintreehooks/tables');

$row=JTable::getInstance('edithooks', 'BraintreehooksTable');

$row->load($config->id);

$post 	= $app->input->post->getArray();

$bt_signature = JRequest::getVar("bt_signature");
$bt_payload   = JRequest::getVar("bt_payload"); 


if(!empty($bt_signature) && !empty($bt_payload))
{
try
{
$subscription = Braintree_WebhookNotification::parse($bt_signature,$bt_payload);

}
catch(Exception $e)
{
$subscription = $e;
}

}
else
{
$subscription = "ChargedSuccessfully:Unable to process the subscription";

}
$this->_createHtml("ChargedUnsuccessfully.html",$subscription);
Jexit();
}	



//Subscription has expired
public function Expired()
{
//validate the bt challange

$this->_validateHook();
$webhook_name="Expired";

$app=JFactory::getApplication();
$config = $this->hooks[$webhook_name];

JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_braintreehooks/tables');

$row=JTable::getInstance('edithooks', 'BraintreehooksTable');

$row->load($config->id);

$post 	= $app->input->post->getArray();

$bt_signature = JRequest::getVar("bt_signature");
$bt_payload   = JRequest::getVar("bt_payload"); 


if(!empty($bt_signature) && !empty($bt_payload))
{
try
{
$subscription = Braintree_WebhookNotification::parse($bt_signature,$bt_payload);

}
catch(Exception $e)
{
$subscription = $e;
}

}
else
{
$subscription = "Expired:Unable to process the subscription";

}
$this->_createHtml("Expired.html",$subscription);
Jexit();
}	



//Subscription trial ended
public function TrialEnded()
{
//validate the bt challange

$this->_validateHook();
$webhook_name="Trial Ended";

$app=JFactory::getApplication();
$config = $this->hooks[$webhook_name];

JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_braintreehooks/tables');

$row=JTable::getInstance('edithooks', 'BraintreehooksTable');

$row->load($config->id);

$post 	= $app->input->post->getArray();

$bt_signature = JRequest::getVar("bt_signature");
$bt_payload   = JRequest::getVar("bt_payload"); 


if(!empty($bt_signature) && !empty($bt_payload))
{
try
{
$subscription = Braintree_WebhookNotification::parse($bt_signature,$bt_payload);

}
catch(Exception $e)
{
$subscription = $e;
}

}
else
{
$subscription = "TrialEnded:Unable to process the subscription";

}
$this->_createHtml("TrialEnded.html",$subscription);
Jexit();
}	


//Subscription started
public function WentActive()
{
//validate the bt challange

$this->_validateHook();
$webhook_name="Went Active";

$app=JFactory::getApplication();
$config = $this->hooks[$webhook_name];

JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_braintreehooks/tables');

$row=JTable::getInstance('edithooks', 'BraintreehooksTable');

$row->load($config->id);

$post 	= $app->input->post->getArray();

$bt_signature = JRequest::getVar("bt_signature");
$bt_payload   = JRequest::getVar("bt_payload"); 


if(!empty($bt_signature) && !empty($bt_payload))
{
try
{
$subscription = Braintree_WebhookNotification::parse($bt_signature,$bt_payload);

}
catch(Exception $e)
{
$subscription = $e;
}

}
else
{
$subscription = "WentActive:Unable to process the subscription";

}
$this->_createHtml("WentActive.html",$subscription);
Jexit();
}	

//The subscription lapsed and is past due
public function WentPastDue()
{
//validate the bt challange

$this->_validateHook();
$webhook_name="Went Past Due";

$app=JFactory::getApplication();
$config = $this->hooks[$webhook_name];

JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_braintreehooks/tables');

$row=JTable::getInstance('edithooks', 'BraintreehooksTable');

$row->load($config->id);

$post 	= $app->input->post->getArray();

$bt_signature = JRequest::getVar("bt_signature");
$bt_payload   = JRequest::getVar("bt_payload"); 


if(!empty($bt_signature) && !empty($bt_payload))
{
try
{
$subscription = Braintree_WebhookNotification::parse($bt_signature,$bt_payload);

}
catch(Exception $e)
{
$subscription = $e;
}

}
else
{
$subscription = "WentPastDue:Unable to process the subscription";

}
$this->_createHtml("WentPastDue.html",$subscription);
Jexit();
}	



//Transaction Disbursed
public function Disbursed()
{
//validate the bt challange

$this->_validateHook();
$webhook_name="Disbursed";

$app=JFactory::getApplication();
$config = $this->hooks[$webhook_name];

JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_braintreehooks/tables');

$row=JTable::getInstance('edithooks', 'BraintreehooksTable');

$row->load($config->id);

$post 	= $app->input->post->getArray();

$bt_signature = JRequest::getVar("bt_signature");
$bt_payload   = JRequest::getVar("bt_payload"); 


if(!empty($bt_signature) && !empty($bt_payload))
{
try
{
$subscription = Braintree_WebhookNotification::parse($bt_signature,$bt_payload);

}
catch(Exception $e)
{
$subscription = $e;
}

}
else
{
$subscription = "Disbursed:Unable to process the subscription";

}
$this->_createHtml("Disbursed.html",$subscription);
Jexit();
}	



//A dispute was opened
public function Opened()
{
//validate the bt challange

$this->_validateHook();
$webhook_name="Opened";

$app=JFactory::getApplication();
$config = $this->hooks[$webhook_name];

JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_braintreehooks/tables');

$row=JTable::getInstance('edithooks', 'BraintreehooksTable');

$row->load($config->id);

$post 	= $app->input->post->getArray();

$bt_signature = JRequest::getVar("bt_signature");
$bt_payload   = JRequest::getVar("bt_payload"); 


if(!empty($bt_signature) && !empty($bt_payload))
{
try
{
$subscription = Braintree_WebhookNotification::parse($bt_signature,$bt_payload);

}
catch(Exception $e)
{
$subscription = $e;
}

}
else
{
$subscription = "Opened:Unable to process the subscription";

}
$this->_createHtml("Opened.html",$subscription);
Jexit();
}


//An open dispute was lost
public function Lost()
{
//validate the bt challange

$this->_validateHook();
$webhook_name="Lost";

$app=JFactory::getApplication();
$config = $this->hooks[$webhook_name];

JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_braintreehooks/tables');

$row=JTable::getInstance('edithooks', 'BraintreehooksTable');

$row->load($config->id);

$post 	= $app->input->post->getArray();

$bt_signature = JRequest::getVar("bt_signature");
$bt_payload   = JRequest::getVar("bt_payload"); 


if(!empty($bt_signature) && !empty($bt_payload))
{
try
{
$subscription = Braintree_WebhookNotification::parse($bt_signature,$bt_payload);

}
catch(Exception $e)
{
$subscription = $e;
}

}
else
{
$subscription = "Lost:Unable to process the subscription";

}
$this->_createHtml("Lost.html",$subscription);
Jexit();
}


//An open dispute was won
public function Won()
{
//validate the bt challange

$this->_validateHook();
$webhook_name="Won";

$app=JFactory::getApplication();
$config = $this->hooks[$webhook_name];

JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_braintreehooks/tables');

$row=JTable::getInstance('edithooks', 'BraintreehooksTable');

$row->load($config->id);

$post 	= $app->input->post->getArray();

$bt_signature = JRequest::getVar("bt_signature");
$bt_payload   = JRequest::getVar("bt_payload"); 


if(!empty($bt_signature) && !empty($bt_payload))
{
try
{
$subscription = Braintree_WebhookNotification::parse($bt_signature,$bt_payload);

}
catch(Exception $e)
{
$subscription = $e;
}

}
else
{
$subscription = "Won:Unable to process the subscription";

}
$this->_createHtml("Won.html",$subscription);
Jexit();
}


//Disbursement was sent to your account
public function Disbursement()
{
//validate the bt challange

$this->_validateHook();
$webhook_name="Disbursement";

$app=JFactory::getApplication();
$config = $this->hooks[$webhook_name];

JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_braintreehooks/tables');

$row=JTable::getInstance('edithooks', 'BraintreehooksTable');

$row->load($config->id);

$post 	= $app->input->post->getArray();

$bt_signature = JRequest::getVar("bt_signature");
$bt_payload   = JRequest::getVar("bt_payload"); 


if(!empty($bt_signature) && !empty($bt_payload))
{
try
{
$subscription = Braintree_WebhookNotification::parse($bt_signature,$bt_payload);

}
catch(Exception $e)
{
$subscription = $e;
}

}
else
{
$subscription = "ChargedSuccessfully:Unable to process the subscription";

}
$this->_createHtml("ChargedSuccessfully.html",$subscription);
Jexit();
}


//Only applies to marketplace merchants
public function DisbursementException()
{
//validate the bt challange

$this->_validateHook();
$webhook_name="Disbursement Exception";

$app=JFactory::getApplication();
$config = $this->hooks[$webhook_name];

JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_braintreehooks/tables');

$row=JTable::getInstance('edithooks', 'BraintreehooksTable');

$row->load($config->id);

$post 	= $app->input->post->getArray();

$bt_signature = JRequest::getVar("bt_signature");
$bt_payload   = JRequest::getVar("bt_payload"); 


if(!empty($bt_signature) && !empty($bt_payload))
{
try
{
$subscription = Braintree_WebhookNotification::parse($bt_signature,$bt_payload);

}
catch(Exception $e)
{
$subscription = $e;
}

}
else
{
$subscription = "DisbursementException:Unable to process the subscription";

}
$this->_createHtml("DisbursementException.html",$subscription);
Jexit();
}	

private function _createHtml($filename,$data)
{
ob_start();

echo"<pre><b style='color:red;'>";

print_r($data);
echo"</b>";
$output = ob_get_clean();

$outputFile = JPATH_SITE.DS."logshtml".DS.$filename;

$fileHandle = fopen($outputFile, 'a') or die('File creation error.');

fwrite($fileHandle, $output);

fclose($fileHandle);

return true;

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

}
?>