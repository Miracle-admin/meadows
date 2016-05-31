<?php
/**
 * @version		1.6.6
 * @package		Joomla
 * @subpackage	Membership Pro
 * @author  Tuan Pham Ngoc
* @copyright	Copyright (C) 2012 - 2014 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined( '_JEXEC' ) or die ;

class os_Braintree extends os_payment {	

	public $Braintree_mode;
	public $x_Merchant;
	public $x_Public_key;
	public $x_Private_key;
	/**
	 * Constructor functions, init some parameter
	 *
	 * @param object $params
	 */
	function os_Braintree($params) {
            
            parent::setName('os_Braintree');		
            parent::os_payment();				
            parent::setCreditCard(false);		
            parent::setCardType(false);
            parent::setCardCvv(false);
            parent::setCardHolderName(false);

            $this->Braintree_mode = $params->get('Braintree_mode');
            $this->x_Merchant = $params->get('x_Merchant');
            $this->x_Public_key = $params->get('x_Public_key');
            $this->x_Private_key = $params->get('x_Private_key');
        
	}	
	/**
	 * Process payment 
	 *
	 */
	function processPayment($row, $data) 
	{
          include_once (JPATH_ROOT.'/libraries/braintree/lib/Braintree.php');
             
			 
			 
			 
			$id=$row->id;
			
			$planId='';
			
			if($row->plan_id==2)
			{
			$planId="93bm";
			}
			if($row->plan_id==5)
			{
			$planId="pzpw";
			}
			
			$email=$row->email;
			
			$firstName=$row->first_name;
		
            $lastName = $row->last_name;
			
			
			$Braintree_mode =  $this->Braintree_mode;
            $x_Merchant =      $this->x_Merchant;
            $x_Public_key =     $this->x_Public_key;
            $x_Private_key =    $this->x_Private_key;
			
		
			
			
			Braintree_Configuration::environment($Braintree_mode);
			Braintree_Configuration::merchantId($x_Merchant);
			Braintree_Configuration::publicKey($x_Public_key);
			Braintree_Configuration::privateKey($x_Private_key);
			
	
			$amount =   $data['amount'].'.00';
			
            $number =   $data['x_card_num'];
			
            $expirationDate =   $data['exp_month'].'/'.$data['exp_year'];
			
			$cvv=$data['x_card_code'];
			
            $customerWithCredit = Braintree_Customer::create(array(
            'id' => $row->user_id,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'creditCard' => array(
            'number' => $number,
            'expirationDate' => $expirationDate,
            'cvv' => $cvv
			)
            ));

          if ($customerWithCredit->success) {
		  
		  //customer creation success full
  
	      $paymentMethodToken=$customerWithCredit->customer->creditCards[0]->token;
	
	      $subscribe = Braintree_Subscription::create(array(
          'paymentMethodToken' => $paymentMethodToken,
          'planId' => $planId,
		  'options' => array('startImmediately' => true),
		  'trialPeriod' => false
		  
          ));
	
	      if($subscribe->success)
	      {
		  
		  //customer successfully subscribed
		  $mid=$this->getMembershipId();
			
		  $transactionId=$subscribe->subscription->id;
	
	      $amount=$subscribe->subscription->transactions[0]->amount;
			
		  $paymentDate=date('Y-m-d H:i:s');
			
		  $published=1;
			
		  $row->payment_date=$paymentDate;
			
		  $row->transaction_id=$transactionId;
			
		  $row->published=$published;
			
		  $row->membership_id=$mid;
			
		  $row->store();
			
		  $Itemid = JRequest::getint('Itemid');
			
		  $config = OSMembershipHelper::getConfig() ;	
			
		  OSMembershipHelper::sendEmails($row, $config);
			
		  $db = JFactory::getDbo();
			
		  $sql = 'SELECT title,subscription_complete_url FROM #__osmembership_plans WHERE id='.$row->plan_id ;
			
		  $db->setQuery($sql);
			
		  $planData =  $db->loadAssoc();
		  
		  
		  
		   //if not willing to accept news letter delete the acymailing subscription
		  if(!isset($data['accept_newsletter']))
	       {
	      $this->subunsub($row);
	        }
		  
		  
		  
			
		  if (!empty($planData['subscription_complete_url']))
		  {
		  JFactory::getApplication()->redirect($planData['subscription_complete_url']);
		  }
			
		  else
		  { 
		  JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_osmembership&view=complete&act='.$row->act.'&subscription_code='.$row->subscription_code.'&Itemid='.$Itemid, false, false),$msg="You Have Successfully Subscribed To Our ".$planData[title]." Plan", $msgType='message');
		  }
		  
		 
		  
	   
	      }
	     else
		 {
		 //error subscribing to new plan
	      $errors='';
		
		  $errors.=$subscribe->message;
		  
		  $red_url=$_SERVER['HTTP_REFERER'];
         
		  $application = JFactory::getApplication();
			   
          if($this->deleteSubscriber($row->user_id))
		  {
	      $application->redirect($red_url, $msg=$errors, $msgType='error');
	      }
		 
	     }
	}
	   else 
		 {
		
		  //error creating a new customer
		  $errors='';
		  
	      $errors.=$customerWithCredit->message; 
		  
		  
         $red_url=$_SERVER['HTTP_REFERER'];
         
		 $application = JFactory::getApplication();
			   
         if($this->deleteSubscriber($row->user_id))
		 {
	     $application->redirect($red_url, $msg=$errors, $msgType='error');
	     }
		 

		 
		 
         }

			
		}
		
		//subscription expired
		
			function subscriptionExpired($id)
	       {
	      $db=JFactory::getDbo();
	
	     //set expired state2
	
	     $query="UPDATE #__osmembership_subscribers SET published='2' WHERE user_id=".$id;
 
         $db->setQuery($query);
 
         $result = $db->execute();
	     if($result)
	     {
	    jimport('joomla.user.helper'); 
	    JUserHelper::removeUserFromGroup($id, 11);
	
        }
        }
		
		
		//subscription went active
		
					function subscriptionActive($id,$start,$end,$transact)
	{
    
	$db=JFactory::getDbo();
	
	$query="UPDATE #__osmembership_subscribers SET from_date='".$start."',to_date='".$end."',published='1',transaction_id='".$transact."' WHERE user_id=".$id;
 
    $db->setQuery($query);
 
    $result = $db->execute();
    
	if($result)
	{
	jimport('joomla.user.helper'); 
	JUserHelper::addUserToGroup($id, 11);
	JUserHelper::removeUserFromGroup($id, 13);
	
	
	
	}

	
    }
		
		
		
		
		//subscription charged successfully
		function subscriptionCharged($id,$start,$end,$transact)
	    {
    
	   $db=JFactory::getDbo();
	
	    $query="UPDATE #__osmembership_subscribers SET from_date='".$start."',to_date='".$end."',published='1',transaction_id='".$transact."' WHERE user_id=".$id;
 
       $db->setQuery($query);
 
       $result = $db->execute();
    
	   if($result)
	   {
	   jimport('joomla.user.helper'); 
	   JUserHelper::addUserToGroup($id, 11);
	   JUserHelper::removeUserFromGroup($id, 13);
	   }

	
    }


    function deleteSubscriber($id)
    {
	
	//log file for user deletion
	$path= JPATH_SITE.'/userlogs/user_logs.txt';
	
	echo $path;
	$myfile = fopen($path, "a");

    $content="\r\n Users deleted via Braintree on date: ".date('Y-m-d H:i:s')."\r\n";
	
	$userDel=JFactory::getUser($id);
	$name=!empty($userDel->name)?$userDel->name:'Unable to fetch , user allready deleted';
	$uname=!empty($userDel->username)?$userDel->username:'Unable to fetch , user allready deleted';
	$emailDel=!empty($userDel->email)?$userDel->email:'Unable to fetch , user allready deleted';
	$content.="\r\n Name= ".$name." \r\n";
	$content.="\r\n User Name= ".$uname." \r\n";
	$content.="\r\n Email= ".$emailDel." \r\n";
	
	
	fwrite($myfile,$content);
	
	//log file
	
	
	$db=JFactory::getDBo();
	
    $query="DELETE FROM #__users WHERE id='".$id."'";

    $db->setQuery($query);
 
    $deleteUser = $db->execute();
	
	if($deleteUser)
	{
	$query="DELETE FROM #__osmembership_subscribers WHERE user_id='".$id."'";
	
	$db->setQuery($query);
	
	$deleteSubscriber = $db->execute();
	
	if($deleteSubscriber)
	{
	
	$query="DELETE FROM #__user_usergroup_map WHERE user_id='".$id."'";
	
	$db->setQuery($query);
	
	$deletePerms = $db->execute();
	
	if($deletePerms)
	{
	
	$ret= true;
	}
	else
	{
	$ret= false;
	}
	
	
	
	}
	
	}
	return $ret;
	}

   function getMembershipId()
	{
		$db = JFactory::getDbo();
		$sql = 'SELECT MAX(membership_id) FROM #__osmembership_subscribers';
		$db->setQuery($sql);
		$membershipId = (int) $db->loadResult();
		if (!$membershipId)
		{
			$membershipId = (int) $this->getConfigValue('membership_id_start_number');
			if (!$membershipId)
				$membershipId = 1000;
		}
		else
		{
			$membershipId++;
		}
		
		return $membershipId;
	}

    	function getConfigValue($key)
	{
		$db = JFactory::getDBO();
		$sql = 'SELECT config_value FROM #__osmembership_configs WHERE config_key="' . $key . '"';
		$db->setQuery($sql);
		return $db->loadResult();
	}	
	
	//subscribe unsubscribe
	
	private function subunsub($row)
	{
	      $db = JFactory::getDbo();
			
		  $sql="SELECT subid FROM #__acymailing_subscriber WHERE userid=".$row->user_id;
		  
		  $db->setQuery($sql);
			
		  $sub_data =  $db->loadAssoc();
		  
		  $sub_id=$sub_data['subid'];
		  
		  $query="DELETE FROM #__acymailing_listsub WHERE subid='".$sub_id."'";
	
	      $db->setQuery($query);
	
	      $deleteSubscription = $db->execute();
		  
    }
	
	
	
}