<?php
/**
 * @version    $Id: myauth.php 7180 2007-04-23 16:51:53Z jinx $
 * @package    Joomla.Tutorials
 * @subpackage Plugins
 * @license    GNU/GPL
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
 
/**
 * Example Authentication Plugin.  Based on the example.php plugin in the Joomla! Core installation
 *
 * @package    Joomla.Tutorials
 * @subpackage Plugins
 * @license    GNU/GPL
 */
class plgAppmeadowsWebhooktriggered extends JPlugin
{
    /**
     * This method should handle any authentication and report back to the subject
     * This example uses simple authentication - it checks if the password is the reverse
     * of the username (and the user exists in the database).
     *
     * @access    public
     * @param     array     $credentials    Array holding the user credentials ('username' and 'password')
     * @param     array     $options        Array of extra options
     * @param     object    $response       Authentication response object
     * @return    boolean
     * @since 1.5
     */
function OnWebhookTriggered($triggeredOn,$status,$planId,$subId,$type,$rowEH,$custId)
{
$countTrig             = $rowEH->trigger_count + 1;  
 
$rowEH->last_triggered = $triggeredOn;

$rowEH ->trigger_count =  $countTrig;

$rowEH->store();

$placustIdn = $this->getJoombriPlan($custId);

 

JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jblance'.DS.'tables');

$row	= JTable::getInstance('plansubscr', 'Table'); 

$row->load($placustIdn->id);



$row->approved            = $status=="Active"?1:0;

$row->status= $status;


if($row->id!="")
{
$row->store();
$this->_createLogs($type,$triggeredOn,$custId,$status,$planId,$subId,$rowEH->logs);

}
Jexit();

}
						
							
private function _createHtml($file,$data)
{
ob_start();

echo"<pre><b style='color:red;'>";

print_r($data);
echo"</b>";
$output = ob_get_clean();

$outputFile = JPATH_SITE.DS."logshtml".DS.$file;

$fileHandle = fopen($outputFile, 'w') or die('File creation error.');

fwrite($fileHandle, $output);

fclose($fileHandle);

return true;

}								   

private function _createLogs($hookType,$triggeredOn,$custId,$status,$planId,$subId,$enabled)
{
if($enabled)
{
 $hookType = str_replace(' ', '', $hookType);
 jimport('joomla.log.log');
JLog::addLogger(
       array(
          'text_file' => "Webhook-".$hookType.".php"
       ),
       // Sets messages of all log levels to be sent to the file
       JLog::INFO,
      
       array($hookType)
   );
   
 $message = "Braintree Webhook triggered : Type : ".$hookType." Triggered On: ".$triggeredOn." CustomerId: ".$custId." Subscription id: ".$subId." Plan Id: ".$planId;
 
 $paramsExt =  JComponentHelper::getParams("com_braintreehooks");
 
 $rec="";
  $receipt = array();
 for($i=1;$i<=5;$i++)
 {
 $reccount = "el_r".$i;
 $receiptent = $paramsExt->get($reccount);
 if($receiptent!="")
 {
  $receipt[]= $receiptent;
  
 }
 }
 
 if(count($receipt)>0)
 {
 $config = JFactory::getConfig();
 $sender = array( 
    "Appmeadows Webhooks logger",
    "Appmeadows Webhooks logger" 
 );
 $mailer = JFactory::getMailer();
 $mailer->setSender($sender);
 $mailer->addRecipient($receipt);
 $body   = $message; 
 $mailer->setSubject('New logs received');
 $mailer->setBody($body);
 $send = $mailer->Send();
 }
 
 JLog::add($message,JLog::INFO  , $hookType);   
}
}


private function getJoombriPlan($custId)
{
$db = JFactory::getDbo();
$q  = "SELECT * FROM #__jblance_plan_subscr WHERE user_id='".$custId."'";
$db->setQuery($q);
$plan = $db->loadObject();

return $plan;
}					
	
private function cancelOldPlan($us)
{

$customer = Braintree_Customer::find($us);
$subLat   = array_reverse($customer->creditCards[0]->subscriptions);
$user     = JFactory::getUser($us);

if(count($subLat)>0)
{

$lastSubscription = $subLat[1]->id;

$subCancel = Braintree_Subscription::cancel($lastSubscription);

$app=JFactory::getApplication();

$upCust = JblanceHelper::getBtCustomer($user->id);
			
$key = $user->id.".".$user->name.".bt.customer.";
	
$app->setUserState($key,$upCust);




/* JPluginHelper::importPlugin('appmeadows');

$dispatcher = JDispatcher::getInstance();
//trigger the subscription cancel handler
$dispatcher->trigger('OnCancelSubscription', array($subCancel,true)); */
}
}	
}
?>