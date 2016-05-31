<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	16 March 2012
 * @file name	:	models/guest.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 jimport('joomla.application.component.model');
 
 class alphauserpointsModelCardmanagement extends JModelLegacy {
 	
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
	
	function addcard($nonce,$json)
	{
	$user = JFactory::getUser();
	$resp  = array();
	$customer=JblanceHelper::getBtCustomer($user->id);
	
	$app=JFactory::getApplication();
	if(!empty($customer))
	{
	$card = $customer['recent_creditcard'];
	
	$token = $card['token'];
	
	
	
	
	try
	{
	$result = Braintree_PaymentMethod::update(
     $token,[
            'paymentMethodNonce' => $nonce,
            'options' => [
                         'makeDefault' => true
                         ]
            ]
            );
			}
			catch(Exception $e)
			{
			$resp["message"]="Unknown error occurred, please try again later.";
	        $resp["type"]="error";
	        $resp["err_code"]=5;
	        echo json_encode($resp);
	        $app->close();
			
			}
			
	
	if($result->success)
	{
	
	//prepare fields
	
	$resp["message"]="Your card has been successfully updated.";
	$resp["type"]="success";
	$resp["suc_code"]=1;
	$this->__updateCard($result,$customer);
	
	echo json_encode($resp);
	$app->close();
	}
	else
	{
	$msg = $result->message;
	$resp["message"]=$msg;
	$resp["type"]="error";
	$resp["err_code"]=3;
	
	
	echo json_encode($resp);
	$app->close();
	}
	}
	else
	{
	
	$resp["message"]="Customer not found.";
	$resp["type"]="error";
	$resp["err_code"]=4;
	echo json_encode($resp);
	$app->close();
	
	}
	
	}
	
private function __updateCard($result,$customer)
	           {
			   $user=JFactory::getUser();
			   
			   $app = JFactory::getApplication();
			   JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jblance'.DS.'tables');
              $row	= JTable::getInstance('plansubscr', 'Table'); 
			  $row->load($this->_getPlanIdByUser($user->id));
			  
			  
			  $paymentMethod               = $result->paymentMethod;
              $row->token                  = $paymentMethod->token;
              $row->bin                    = $paymentMethod->bin;
              $row->last4                  = $paymentMethod->last4;
              $row->cardType               = $paymentMethod->cardType;
              $row->expirationDate         = $paymentMethod->expirationDate;
              $row->customerLocation       = $paymentMethod->customerLocation;
              $row->cardholderName         = $paymentMethod->cardholderName;
              $row->imageUrl               = $paymentMethod->imageUrl;
              $row->prepaid                = $paymentMethod->prepaid;
              $row->healthcare             = $paymentMethod->healthcare;
              $row->debit                  = $paymentMethod->debit;
              $row->durbinRegulated        = $paymentMethod->durbinRegulated;
              $row->commercial             = $paymentMethod->commercial;
              $row->payroll                = $paymentMethod->payroll;
              $row->issuingBank            = $paymentMethod->issuingBank;
              $row->countryOfIssuance      = $paymentMethod->countryOfIssuance;
              $row->productId              = $paymentMethod->productId;
              $row->uniqueNumberIdentifier = $paymentMethod->uniqueNumberIdentifier;
              $row->maskedNumber           = $paymentMethod->maskedNumber; 
			   
			  //update records
              if($row->store())
			  {
			  
			  
			  //update customer
			  $customer['recent_creditcard']['token']                  =  $row->token; 
              $customer['recent_creditcard']['bin']                    =  $row->bin;
              $customer['recent_creditcard']['last4']                  =  $row->last4;
              $customer['recent_creditcard']['cardType']               =  $row->cardType;
              $customer['recent_creditcard']['expirationDate']         =  $row->expirationDate;
              $customer['recent_creditcard']['customerLocation']       =  $row->customerLocation;
              $customer['recent_creditcard']['cardholderName']         =  $row->cardholderName; 
              $customer['recent_creditcard']['imageUrl']               =  $row->imageUrl;
              $customer['recent_creditcard']['prepaid']                =  $row->prepaid;
              $customer['recent_creditcard']['healthcare']             =  $row->healthcare;
              $customer['recent_creditcard']['debit']                  =  $row->debit;
              $customer['recent_creditcard']['durbinRegulated']        =  $row->durbinRegulated;
              $customer['recent_creditcard']['commercial']             =  $row->commercial;
              $customer['recent_creditcard']['payroll']                =  $row->payroll;
              $customer['recent_creditcard']['issuingBank']            =  $row->issuingBank;
              $customer['recent_creditcard']['countryOfIssuance']      =  $row->countryOfIssuance;
              $customer['recent_creditcard']['productId']              =  $row->productId;
              $customer['recent_creditcard']['uniqueNumberIdentifier'] =  $row->uniqueNumberIdentifier;
              $customer['recent_creditcard']['maskedNumber']           =  $row->maskedNumber;
			  
			 
	
	          $jbmail = JblanceHelper::get('helper.email');
			 

              $jbmail->addedNewCard($row,$user);
			  
			  $key = $user->id . "." . $user->name . ".bt.customer.";
          
              $app->setUserState($key, $customer);
			  
			  
			  return true;
			  
               	}		  
			   }
			   
			   
	private function _getPlanIdByUser($userId)
                            {
							$db=JFactory::getDbo();
							$q="SELECT id FROM #__jblance_plan_subscr WHERE user_id='".$userId."'";
							$db->setQuery($q);
							$id=$db->loadObject();
							
							return $id->id;
							}			   
 	
 }