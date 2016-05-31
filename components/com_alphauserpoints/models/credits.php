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

class alphauserpointsModelcredits extends JmodelLegacy {




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
	
	function processPayment($amt,$nonce)
	{
    $app=JFactory::getApplication();
	$user=JFactory::getUser();
	$api_jb = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jblance'.DS.'jblance.php';
	
	include_once($api_jb);
	
	$logger=JblanceHelper::get('helper.logger');
	
	$result = Braintree\Transaction::sale([
    'amount' => $amt,
    'paymentMethodNonce' => $nonce,
	'customer'=>[
	'email'=>$user->email,
	'firstName'=>$user->username
	],
    'options' => [ 'submitForSettlement' => true ]
        ]);
	
    $success = $result->success;
	
	if($success)
	{
	$trans = $result->transaction;
	 
	$row = JTable::getInstance('transactions', 'Table');
	$row->uid=$user->id;
	$row->amt=$trans->amount;
	$row->trans_id=$trans->id;
	$trdate = $trans->createdAt;
	$row->transDate = $trdate->format('Y-m-d H:i:s');
	$row->card_type= $trans->creditCard['cardType'];
	$creditCard = $trans->creditCardDetails;
	$data = new stdClass ();
	$data->customerName                = $user->name;
	$data->customerId                  = $user->id; 
    $data->transaction_id              = $trans->id;
    $data->transaction_status          = $trans->status;
    $data->transaction_type            = $trans->type;
    $data->transaction_currencyIsoCode = $trans->currencyIsoCode;
    $data->transaction_amount          = $trans->amount;
    $data->transaction_created_at      = $trans->createdAt->format('j F Y h:i:s A');
	$data->transaction_updatedAt       = $trans->updatedAt->format('j F Y h:i:s A');
	$data->card_token                  = $creditCard->token; 
    $data->cardbin                     = $creditCard->bin;
    $data->cardlast4                   = $creditCard->last4;
    $data->cardType                    = $creditCard->cardType;
    $data->expirationMonth             = $creditCard->expirationMonth;
    $data->expirationYear              = $creditCard->expirationYear;
    $data->customerLocation            = $creditCard->customerLocation;
    $data->cardholderName              = $creditCard->cardholderName; 
    $data->cardImage                   = $creditCard->imageUrl; 
    $data->prepaid                     = $creditCard->prepaid;
    $data->healthcare                  = $creditCard->healthcare;
    $data->debit                       = $creditCard->debit;
    $data->durbinRegulated             = $creditCard->durbinRegulated;
    $data->commercial                  = $creditCard->commercial;
    $data->payroll                     = $creditCard->payroll;
    $data->issuingBank                 = $creditCard->issuingBank;
    $data->countryOfIssuance           = $creditCard->countryOfIssuance;
    $data->productId                   = $creditCard->productId;
    $data->uniqueNumberIdentifier      = $creditCard->uniqueNumberIdentifier;
    $data->venmoSdk                    = $creditCard->venmoSdk; 
    $data->expirationDate              = $creditCard->expirationDate;
    $data->maskedNumber                = $creditCard->maskedNumber;
	
	//send email to admin and customer
	
	$jbmail = JblanceHelper::get('helper.email');
    $jbmail->userAddedFunds($data);
	$jbmail->userAddedFundsA($data);
   
    if($row->store())
	{
	$params = array('', '', '','', $datareference='Appmeadows Credits', $randompoints=$trans->amount+0, '', '', '');
	
	
	$credits = JblanceHelper::get('helper.credits');
	
	
	if($credits::UpdateCredits($params))
	{
	//email to be triggered here.
	//logs to be generated here.
	$msgLog = "Transaction Success:: userid: ".$row->uid." Ammount: ".$row->amt." Transaction Id: ".$row->trans_id." Creation Date: ".$row->transDate." Card: ".$row->card_type."\n";
	
	
	
	$logger::addLogs(array("new_credits.php",JLog::ALL,"credit_info",$msgLog,JLog::INFO,"com_alphauserpoints"));
	
	
   $app->redirect(JRoute::_(JUri::root()."index.php?option=com_alphauserpoints&view=credits&type=deposit"),"Your payment has been successfully processed, $".$trans->amount." successfully credited to your account.","message");
	}
	
	
	}
	}
	else
	{
	$msg = $result->message;
	
	$msgLog = "Transaction Failed:: userid: ".$user->id." Reason: ".$msg."\n";
	
	$logger::addLogs(array("new_credits.php",JLog::ALL,"credit_info",$msgLog,JLog::ERROR,"com_alphauserpoints"));
	
	
	
	$app->redirect(JRoute::_(JUri::root()."index.php?option=com_alphauserpoints&view=credits&type=deposit"),$msg,"error");
	}


    }


	function processPaymentCart($amt,$nonce,$orderId)
	{
	
	if (!class_exists('VmConfig'))
    {
        require JPATH_ROOT . '/administrator/components/com_virtuemart/helpers/config.php';
        VmConfig::loadConfig();
    }
	
	$api_jb = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jblance'.DS.'jblance.php';
	
	include_once($api_jb);
	
	
    $checkOutUrl="";
	
	$logger=JblanceHelper::get('helper.logger');
	
    $ordersModel = VmModel::getModel('Orders');
	
	$order = $ordersModel->getOrder($orderId); 
	
	$this->_validateOrder($amt,$order);
	
	//process the cart
	$this->_processPayment($amt,$nonce,$order);

	
	}
	
	private function _validateOrder($amtr,$order)
	{
	
	$app=JFactory::getApplication();
	$user = JFactory::getUser();
	$cartUrl = JRoute::_(JUri::root().'index.php?option=com_virtuemart&view=cart&Itemid=199');
	
	$items = $order['items'];
	
	$status = $order['details']['BT']->order_status;
	
	$orderNo = $order['details']['BT']->order_number;
	
	$ammount = round($order['details']['BT']->order_total);
	
	$api_AUP = JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php';
    if ( file_exists($api_AUP))
        {
        require_once ($api_AUP);
        }
	
	$totalPoints = round(AlphaUserPointsHelper::getCurrentTotalPoints( "",$user->id  ));
	if($amtr < $ammount-$totalPoints )
	{
	$app->redirect($cartUrl,"Insufficient payment amount.","error");
	}
	
	if(empty($items))
	{
	$app->redirect($cartUrl,"Order cannot be processed, Please try again.","error");
	}
	if($status=="C")
	{
	$app->redirect($cartUrl,"Order no ".$orderNo." has been already processed.","error");
	}
	
	}
	
	private function _processPayment($amt,$nonce,$order)
	{
	$itemsO = $order['items'];
	$orderNo = $order['details']['BT']->virtuemart_order_id;
	$app=JFactory::getApplication();
	$user=JFactory::getUser();
	$api_jb = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jblance'.DS.'jblance.php';
	$cparams 				= JComponentHelper::getParams('com_vmvendor');
    $commission 			= $cparams->get('commission');
	$vmitemid				= $cparams->get('vmitemid');
	$db = JFactory::getDbo();
	$api_AUP = JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php';
    if ( file_exists($api_AUP))
    {
     require_once ($api_AUP);
     
    }
	$totalPoints = round(AlphaUserPointsHelper::getCurrentTotalPoints( "",$user->id  ));
	include_once($api_jb);
	
	$logger=JblanceHelper::get('helper.logger');
	
	$result = Braintree\Transaction::sale([
    'amount' => $amt,
    'paymentMethodNonce' => $nonce,
	'customer'=>[
	'email'=>$user->email,
	'firstName'=>$user->username
	],
    'options' => [ 'submitForSettlement' => true ]
        ]);
	
    $success = $result->success;
	
	if($success)
	{
	$trans = $result->transaction;
	 
	$row = JTable::getInstance('transactions', 'Table');
	$row->uid=$user->id;
	$row->amt=$trans->amount;
	$row->trans_id=$trans->id;
	$trdate = $trans->createdAt;
	$row->transDate = $trdate->format('Y-m-d H:i:s');
	$row->card_type= $trans->creditCard['cardType'];
	
	

    if($row->store())
	{ 
	//process the cart
	if (!class_exists( 'VmConfig' )) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart'.DS.'helpers'.DS.'config.php');
    VmConfig::loadConfig();
    if (!class_exists( 'VmModel' )) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart'.DS.'helpers'.DS.'vmmodel.php');

   
	
	
	$q = "SELECT virtuemart_user_id FROM #__virtuemart_order_items INNER JOIN #__virtuemart_vendor_users ON #__virtuemart_order_items.virtuemart_vendor_id =  #__virtuemart_vendor_users.virtuemart_vendor_id  WHERE virtuemart_order_id='".$orderNo."'";
	
	$db->setQuery($q);
	
	$items = $db->loadObjectList();
	
	$errorProcessing = array();
	
	foreach($items as $isk => $isv)
	{

	$iv=$itemsO[$isk];
	
	$virtuemart_product_id     = $iv->virtuemart_product_id;
	
	$virtuemart_category_id    = $iv->virtuemart_category_id;
	
	$product_subtotal_with_tax = round($iv->product_subtotal_with_tax);
	
	$referencekey =  $order['details']['BT'] ->virtuemart_order_id.'|OderItemID|'. microtime();
	
	$order_item_name            = $iv->order_item_name;
	
	$seller_comission =  round( $product_subtotal_with_tax * (100 - $commission) / 100 );
	
	$sellerId = $isv->virtuemart_user_id;
	
	$buyerid = $order['details']['BT']->virtuemart_user_id;
	
	$virtuemart_paymentmethod_id = $order['details']['BT']->virtuemart_paymentmethod_id;
	
	$order_number                 = $order['details']['BT']->order_number;
	
	$aupids = AlphaUserPointsHelper::getAnyUserReferreID( $sellerId );
	
	$aupidb = AlphaUserPointsHelper::getAnyUserReferreID( $buyerid );
	
	$buyerJ = JFactory::getUser($buyerid);
	$sellerJ = JFactory::getUser($sellerId);
	
	
	$product_url      = '<a href= "'.JUri::root().'index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$virtuemart_product_id.'&virtuemart_category_id='.$virtuemart_category_id.'&Itemid='.$vmitemid.'">'.$order_item_name.'</a>'; 
	
	$informationdataS = "Sale: ".$product_url." Earned: $".$seller_comission."($".$product_subtotal_with_tax." - ".$commission."% Appmeadows Commission)";
	
	$informationdataB = "Purchase: ".$product_url." Price: $".$product_subtotal_with_tax;
	
	//add ammount in the buyers account
	if(AlphaUserPointsHelper::newpoints( 'plgaup_appmeadowscredits',$aupidb, $referencekey,"Appmeadows Credits", round($product_subtotal_with_tax),true))
	{
	
	$msgLog ="User: ".$buyerJ->username." added $".$product_subtotal_with_tax." for purchasing ".$order_item_name."\n";
	
	//now deduct and pay the ammount
	$logger::addLogs(array("marketplace_successfull_order_braintree.php",JLog::ALL,"credit_info",$msgLog,JLog::INFO,"com_alphauserpoints"));
	
	
	
	 if(AlphaUserPointsHelper::newpoints( 'plgaup_vmsales',$aupids, $referencekey,$informationdataS, $seller_comission,true) && AlphaUserPointsHelper::newpoints( 'plgaup_vmsales',$aupidb, $referencekey,$informationdataB, -1 * abs($product_subtotal_with_tax),true))
	{
	//now deduct and pay the ammount 
	
	$msgLog ="Order no: ".$order_number." Message: "."Order successfully processed, placed by: ".$buyerJ->username."(".$buyerJ->id.") vendor: ".$sellerJ->username." (".$sellerJ->id.") Amount paid by buyer: $".$product_subtotal_with_tax." Amount paid to vendor: $".$seller_comission."($".$product_subtotal_with_tax." - ".$commission."% Appmeadows Commission) Product: ".$product_url."\n" ;
	
	
	$logger::addLogs(array("marketplace_successfull_order_braintree.php",JLog::INFO,"credit_info",$msgLog,JLog::INFO,"com_alphauserpoints"));
	
	
	


	
	}
	
	else{
	
	$errorProcessing[]=1;
	}
	
	
	
	
	
	
	}
	
	}
	
	if(count($errorProcessing)==0)
	{
	
	$uKey  = $user->id.".".$user->name;
	$app->setUserState($uKey,null);
	

	$sucurl = JURI::root () . 'index.php?option=com_virtuemart&view=vmplg&task=pluginresponsereceived&on=' .$order_number. '&pm=' . $virtuemart_paymentmethod_id ;
	$app->redirect(JRoute::_($sucurl));
	
	}
	else
	{
	$failUrl = JURI::root () . 'index.php?option=com_virtuemart&view=cart';
	
	$app->redirect(JRoute::_($failUrl,"Unable to process your payment,Please inform the site administrator, if you believe this is an error.","error"));
	} 
	
	
	
	
	}
	
	}
	else
	{
	$msg = $result->message;
	
	$msgLog ="Order no: ".$order_number." Message: "."Error processing order, placed by: ".$user->username."(".$user->id.") Response returned by braintree: ".$msg."\n";
	
	$logger::addLogs(array("marketplace_failed_order_braintree.php",JLog::ERROR,"credit_info",$msgLog,JLog::ERROR,"com_alphauserpoints"));
	
	$app->redirect(JRoute::_(JUri::root()."index.php?option=com_alphauserpoints&view=credits&type=cart"),$msg,"error");
	} 
	
	}
	

function getClientToken()
{
return Braintree_ClientToken::generate();
}


}
?>