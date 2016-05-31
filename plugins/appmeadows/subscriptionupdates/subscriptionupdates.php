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
class plgAppmeadowsSubscriptionupdates extends JPlugin
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
    function OnNewSubscription($subscription,$oldCustomer)
							  {
							  
			$user = JFactory::getUser();					  
	        JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jblance'.DS.'tables');
            $row	= JTable::getInstance('plansubscr', 'Table'); 
	
	      
	
	        if($oldCustomer)
	            {
	            
	            $row->load($this->_getPlanIdByUser($user->id));
	            }
				
				
	            $subtrans  = $subscription->subscription;
                //format the response
	       $subid  = $subtrans->id;
			 
	       $bday   = $subtrans->billingDayOfMonth;
	       $nextBillingDate        = $subtrans->nextBillingDate->format('Y-m-d H:i:s');
	       $billingPeriodStartDate = $subtrans->billingPeriodStartDate->format('Y-m-d H:i:s');
	       $createdAt              = $subtrans->createdAt->format('Y-m-d H:i:s');
	       $updatedAt              = $subtrans->updatedAt->format('Y-m-d H:i:s');
	       $currentBillingCycle    = $subtrans->currentBillingCycle;
		   $planId                 = $subtrans->planId;
	       //$id                   = $subtrans->id;
	       $status                 = $subtrans ->status;
	       $transactions           = $subtrans->transactions[0];
	       $transid                = $transactions->id;
	       $amount                 = $transactions->amount;
	       $card                   = $transactions->creditCardDetails;
	
	       $token                  = $card->token;
	       $bin                    = $card->bin;
	       $last4                  = $card->last4;
	       $cardType               = $card->cardType;
           $expirationDate         = $card->expirationDate;
           $customerLocation       = $card->customerLocation;
           $cardholderName         = $card->cardholderName;
	       $imageUrl               = $card->imageUrl; 
	       $prepaid                = $card->prepaid; 
	       $healthcare             = $card->healthcare; 
	       $debit                  = $card->debit; 
	       $durbinRegulated        = $card->durbinRegulated; 
	       $commercial             = $card->commercial; 
	       $payroll                = $card->payroll; 
	       $issuingBank            = $card->issuingBank; 
	       $countryOfIssuance      = $card->countryOfIssuance; 
	       $productId              = $card->productId; 
	       $uniqueNumberIdentifier = $card->uniqueNumberIdentifier; 
	       $maskedNumber           = $card->maskedNumber; 
	
	
           $Pid = $this->_getPlanId($planId);					   				  
							 
	$row->user_id             = $user->id;
	$row->plan_id             = $Pid->id;
	$row->subscriptionId      = $subid;
	$row->approved            = $status=="Active"?1:0;
	$row->gateway             = "btpay";
	$row->gateway_id          = 6;
	$row->price               = $amount;
    $row->trans_id            = $transid;
	$row->fund                = $amount;
	$row->date_buy            = $billingPeriodStartDate;
	$row->date_approval       = $billingPeriodStartDate;
	$row->date_expire         = $nextBillingDate;
	$row->billing_day         = $createdAt;
	$row->createdAt           = $createdAt;
	$row->updatedAt           = $updatedAt;
	$row->currentBillingCycle = $currentBillingCycle;
    $row->status              = $status;
	$row->planId              = $planId;
	//card details
	$row->token               = $token;
	$row->bin                 = $bin;
	$row->last4               = $last4;
	$row->cardType            = $cardType;
    $row->expirationDate      = $expirationDate;
    $row->customerLocation    = $customerLocation;
    $row->cardholderName      = $cardholderName;
	$row->imageUrl            = $imageUrl; 
	$row->prepaid             = $prepaid; 
	$row->healthcare          = $healthcare; 
	$row->debit               = $debit; 
	$row->durbinRegulated     = $durbinRegulated; 
	$row->commercial          = $commercial; 
	$row->payroll             = $payroll; 
	$row->issuingBank         = $issuingBank; 
	$row->countryOfIssuance   = $countryOfIssuance; 
	$row->productId           = $productId; 
	$row->uniqueNumberIdentifier       = $uniqueNumberIdentifier; 
	$row->maskedNumber                 = $maskedNumber; 
	
	$jbmail = JblanceHelper::get('helper.email');
	
	
	
	if($row->store())
	{
	//send email
	//update the customer
	$app=JFactory::getApplication();
	$upCust = JblanceHelper::getBtCustomer($user->id);
	
	$key = $user->id.".".$user->name.".bt.customer.";
	if($oldCustomer)
	{
	//cancel current subscription first so that the user is not charged for the old plan
	$jbmail->userUpdatedPlan($row);
	$jbmail->userUpdatedPlanAdmin($row);
	
	}
	
	
	else{
	$jbmail->userSubscribedNewPlan($row);
	$jbmail->userSubscribedNewPlanAdmin($row);
	
	}
	
	$app->setUserState($key,$upCust);
	
	
	

	return true;
	} 
    }
							 
							 private function _getPlanId($pname)
							 {
							 $db = JFactory::getDbo();
							 $q  = "SELECt id FROM #__jblance_plan WHERE pidbt='".$pname."'"; 
							 $db->setQuery($q);
							 $ret = $db->loadObject();
							 return $ret;
							}
							
									   
	private function _getPlanIdByUser($userId)
                            {
							$db=JFactory::getDbo();
							$q="SELECT id FROM #__jblance_plan_subscr WHERE user_id='".$userId."'";
							$db->setQuery($q);
							$id=$db->loadObject();
							
							return $id->id;
							}	
							
							
	private function _cancelSubscription($key,$upCust)
                           {
						   $apiJb=JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jblance'.DS.'helpers'.DS.'jblance.php';
	
                           include_once($apiJb);
						   
						   
						  JblanceHelper::getBtConfig();
						  $logger=JblanceHelper::get('helper.logger');
						  $app=JFactory::getApplication();
						  $cust= $app->getUserState($key);
						  $currSub = $cust['recent_subscription'];
						  $newSub = $upCust['recent_subscription'];
						  
						  $subId = $currSub['subscriptionId'];
						  $result = Braintree_Subscription::cancel($subId);
						  if($result->success)
						  {
						  $msgLog = $cust['username']." canceled his subscription ".$currSub['name']." for an upgrade/downgrade to ".$newSub['name'];
						  
						 $logger::addLogs(array("subcancel_up.php",JLog::INFO,"Subscription_cancelup",$msgLog,JLog::INFO,"com_alphauserpoints"));
						  
						 
						  
						  
						  return true;
						  }
						  else
						  {
						  $msgLog = $cust['username']." Failed to cancel his current plan ".$currSub['name']." where as he/she has been successfully upgraded for new ".$newSub['name']." plan , its a serious vulnerability and should be fixed as soon as possible, A customer can't exist in two plans simultaineously. Reason: connection loss + ".$result->message;
						  
						  
						   $logger::addLogs(array("vulnerabilities.php",JLog::INFO,"vulnerability found",$msgLog,JLog::INFO,"com_alphauserpoints"));
						  return false;
						  }
						   }						
		
}
?>