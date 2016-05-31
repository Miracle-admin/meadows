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
class plgAppmeadowsSubscriptioncanceled extends JPlugin
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
    function OnCancelSubscription($subscription,$webhooks)
							  {
							  
			$user = JFactory::getUser();					  
	        
			JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jblance'.DS.'tables');
            
			$row	= JTable::getInstance('plansubscr', 'Table'); 
	
	        $subtrans  = $subscription->subscription;
			$subId     = $subtrans->id;
			$planId    = $subtrans->planId;
                //format the response
            
			$Pid = $this->_getPlanIdByUser($subId);

			$row->load($Pid);
			
			
			$status                   = $subtrans ->status;
			$row->approved            = $status=="Active"?1:0;
			$row->status              = $status;
		
	        if($row->store())
	         {
	       
	        //update the customer
	        $app=JFactory::getApplication();
	        $upCust = JblanceHelper::getBtCustomer($user->id);
			
			$key = $user->id.".".$user->name.".bt.customer.";
	
	        $app->setUserState($key,$upCust);
	        
			
			$this->_generateLogs($upCust);
			
	        return $row; 
			
	         }
	}
						
							
									   
	private function _getPlanIdByUser($subId)
                            {
							$db=JFactory::getDbo();
							$q="SELECT id FROM #__jblance_plan_subscr WHERE subscriptionId ='".$subId."'";
							$db->setQuery($q);
							$id=$db->loadObject();
							
							return $id->id;
							}	
							
							
	private function _generateLogs($upCust)
                           {
						   $apiJb=JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jblance'.DS.'helpers'.DS.'jblance.php';
	
                           include_once($apiJb);
						   
						   $currSub = $cust['recent_subscription'];
						   
						   $logger=JblanceHelper::get('helper.logger');  
						   
						   $msgLog = $upCust['username']." (".$upCust['id'].") canceled his subscription ".$currSub['name'];
						   
						   $logger::addLogs(array("subcancel.php",JLog::INFO,"Subscription_canceled",$msgLog,JLog::INFO,"com_alphauserpoints"));
						   
						   
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
						   
		
}
?>