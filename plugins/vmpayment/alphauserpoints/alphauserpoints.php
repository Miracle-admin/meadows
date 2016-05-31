<?php
/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */
/**
 *
 * Alphauserpoints payment plugin
 *
 * @author Jeremy Magne
 * @author Valérie Isaksen
 * @version $Id: alphauserpoints.php 7217 2013-09-18 13:42:54Z alatak $
 * @package VirtueMart
 * @subpackage payment
 * Copyright (C) 2004-2015 Virtuemart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
 *
 * http://virtuemart.net
 */

defined('_JEXEC') or die('Restricted access');
if (!class_exists('vmPSPlugin')) {
	require(JPATH_VM_PLUGINS . DS . 'vmpsplugin.php');
}

if (!class_exists('AlphauserpointsHelperAlphauserpoints')) {
	require(VMPATH_ROOT .DS.'plugins'.DS.'vmpayment'.DS.'alphauserpoints'.DS.'alphauserpoints'.DS.'helpers'.DS.'alphauserpoints.php');
}
if (!class_exists('AlphauserpointsHelperCustomerData')) {
	require(VMPATH_ROOT .DS.'plugins'.DS.'vmpayment'.DS.'alphauserpoints'.DS.'alphauserpoints'.DS.'helpers'.DS.'customerdata.php');
}
if (!class_exists('AlphauserpointsHelperAlphaUserpointsStd')) {
	require(VMPATH_ROOT . DS.'plugins'.DS.'vmpayment'.DS.'alphauserpoints'.DS.'alphauserpoints'.DS.'helpers'.DS.'alphauserpointsstd.php');
}
if (!class_exists('AlphauserpointsHelperAlphaUserpointsExp')) {
	require(VMPATH_ROOT . DS.'plugins'.DS.'vmpayment'.DS.'alphauserpoints'.DS.'alphauserpoints'.DS.'helpers'.DS.'alphauserpointsexp.php');
}
if (!class_exists('AlphauserpointsHelperAlphaUserpointsHosted')) {
	require(VMPATH_ROOT . DS.'plugins'.DS.'vmpayment'.DS.'alphauserpoints'.DS.'alphauserpoints'.DS.'helpers'.DS.'alphauserpointshosted.php');
}
if (!class_exists('AlphauserpointsHelperAlphaUserpointsApi')) {
	require(VMPATH_ROOT . DS.'plugins'.DS.'vmpayment'.DS.'alphauserpoints'.DS.'alphauserpoints'.DS.'helpers'.DS.'alphauserpointsapi.php');
}
class plgVmPaymentAlphauserpoints extends vmPSPlugin {

	// instance of class
	private $customerData;
	private $_autobilling_max_amount = '';
	private $_cc_name = '';
	private $_cc_type = '';
	private $_cc_number = '';
	private $_cc_cvv = '';
	private $_cc_expire_month = '';
	private $_cc_expire_year = '';
	private $_cc_valid = false;
	private $_user_data_valid = false;
	private $_errormessage = array();
	private $_logger='';

	function __construct(& $subject, $config) {


		//if (self::$_this)
		//   return self::$_this;
		parent::__construct($subject, $config);

		$this->customerData = new AlphauserpointsHelperCustomerData();
		$this->_loggable = TRUE;
		$this->tableFields = array_keys($this->getTableSQLFields());
		$this->_tablepkey = 'id'; //virtuemart_alphauserpoints_id';
		$this->_tableId = 'id'; //'virtuemart_alphauserpoints_id';
		$varsToPush = array('commission' =>array('','char'),
						    'payment_currency'       => array('', 'int'),
		                    'payment_logos'          => array('', 'char'),
		                    'status_pending'         => array('', 'char'),
		                    'status_success'         => array('', 'char'),
		                    'status_canceled'        => array('', 'char'),
		                    'countries'              => array('', 'char'),
		                    'min_amount'             => array('', 'int'),
		                    'max_amount'             => array('', 'int'),
		                    'secure_post'            => array('', 'int'),
							'cost_per_transaction'	 => array('', 'int'),
							'cost_percent_total'	 => array('', 'int')

		);

		$this->setConfigParameterable($this->_configTableFieldName, $varsToPush);
		
		
	 	//$api_jb = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jblance'.DS.'jblance.php';
		
//	
//	    include_once($api_jb);
//	die;
	    $this->_logger=get_class(JblanceHelper::get('helper.logger'));
		
		
		
		
		//self::$_this = $this;
	}

	public function getVmPluginCreateTableSQL() {
		return $this->createTableSQL('AlphaUserpoints Table');
	}

	function getTableSQLFields() {

		$SQLfields = array(
				 'id' => 'int(11) UNSIGNED NOT NULL AUTO_INCREMENT',
			     'virtuemart_order_id' => 'int(1) UNSIGNED',
			     'order_number' => 'char(64)',
			     'virtuemart_paymentmethod_id' => 'mediumint(1) UNSIGNED',
			     'payment_name' => 'varchar(5000)',
			     'payment_order_total' => 'decimal(15,5) NOT NULL',
			     'payment_currency' => 'smallint(1)',
			     'email_currency' => 'smallint(1)',
			     'cost_per_transaction' => 'decimal(10,2)',
			     'cost_percent_total' => 'decimal(10,2)',
			     'tax_id' => 'smallint(1)',
				 'mc_currency'            		   => 'varchar(255) NULL DEFAULT NULL ',
				 'alphauserpoints_custom'				   => 'decimal(10,2) NULL ',
				 'Amount'						   => 'decimal(10,2) NULL ',
				 'Order_Id'						   => 'int(50) UNSIGNED NOT NULL ',
				 'billing_cust_name'				   => 'varchar(255) NULL ',
				 'billing_cust_address'			   => 'varchar(255) NULL ',
				 'billing_cust_country'			   => 'varchar(255) NULL ',
				 'billing_cust_tel'				   => 'int(50) NULL ',
				 'billing_cust_email'			   => 'varchar(255) NULL ',
				 'delivery_cust_name'			   => 'varchar(255) NULL ',
				 'delivery_cust_address'   		   => 'varchar(255) NULL ',
				 'delivery_cust_state' 			   => 'varchar(255) NULL ',
				 'delivery_cust_country' 		   => 'varchar(255) NULL ',
				 'delivery_cust_tel'   	           => 'int(50) NULL NOT NULL',
				  'delivery_cust_notes'              => 'varchar(255) NULL ',
				 'Merchant_Param'  		           => 'varchar(255) NULL ',
				 'billing_city' 			           => 'varchar(255) NULL ', 
				 'billing_zip' 			           => 'int(50) NULL ',
				 'delivery_city' 			       => 'varchar(255) NULL ',
				 'delivery_zip'			           => 'int(50) NULL ',
				 'AuthDesc'            	           => 'varchar(255) NULL ',
				 'payment_date'           		   => 'varchar(255) NULL ',
				 'payment_status'         	       => 'varchar(255) NULL ',
				 'pending_reason'        	       => 'varchar(255) NULL ',
				 'mmp_txn'                          => 'int(50) NULL NOT NULL',
                 'mer_txn'                          => 'varchar(255) NULL ', 
                 'amt'                        => 'varchar(255) NULL ',
                 'prod'                             => 'varchar(255) NULL ',
                 'date'                             => 'varchar(255) NULL ', 
                 'bank_txn'                         => 'varchar(255) NULL ',  
                 'f_code'                           => 'varchar(255) NULL ',  
                 'clientcode'                       => 'varchar(255) NULL ', 
                 'bank_name'                        => 'varchar(255) NULL ',
                 'merchant_id'                      => 'int(50) NULL NOT NULL',
                 'descr'                             => 'varchar(255) NULL ',
		);
		return $SQLfields;
	}

	/**
	 * @param $product
	 * @param $productDisplay
	 * @return bool
	 */
	function plgVmOnProductDisplayPayment($product, &$productDisplay) {
		return;
		$vendorId = 1;
		if ($this->getPluginMethods($vendorId) === 0) {
			return FALSE;
		}

		foreach ($this->methods as $this->_currentMethod) {
			if ($this->_currentMethod->alphauserpointsproduct == 'exp') {
				$alphauserpointsInterface = $this->_loadAlphaUserpointsInterface();
				$product = $alphauserpointsInterface->getExpressProduct();
				$productDisplayHtml = $this->renderByLayout('expproduct',
					array(
						'text' => vmText::_('VMPAYMENT_ALPHAUSERPOINTS_EXPCHECKOUT_AVAILABALE'),
						'img' => $product['img'],
						'link' => $product['link'],
						'sandbox' => $this->_currentMethod->sandbox,
						'virtuemart_paymentmethod_id' => $this->_currentMethod->virtuemart_paymentmethod_id,
					)
				);
				$productDisplay[] = $productDisplayHtml;

			}
		}
		return TRUE; 
	}

	/**
	 * @param VirtuemartViewUser $user
	 * @param                    $html
	 * @param bool               $from_cart
	 * @return bool|null
	 */
	function plgVmDisplayLogin(VirtuemartViewUser $user, &$html, $from_cart = FALSE) {

		// only to display it in the cart, not in list orders view
		if (!$from_cart) {
			return NULL;
		}

		$vendorId = 1;
		if (!class_exists('VirtueMartCart')) {
			require(VMPATH_SITE . DS . 'helpers' . DS . 'cart.php');
		}

		$cart = VirtueMartCart::getCart();
		if ($this->getPluginMethods($cart->vendorId) === 0) {
			return FALSE;
		}

		if (!($selectedMethod = $this->getVmPluginMethod($cart->virtuemart_paymentmethod_id))) {
			return FALSE;
		}
		if (!$this->isExpToken($selectedMethod, $cart) ) {
			$html .= $this->getExpressCheckoutHtml( $cart);
		}

		return;

	}

	/**
	 * @param $cart
	 * @param $payment_advertise
	 * @return bool|null
	 */
	function plgVmOnCheckoutAdvertise($cart, &$payment_advertise) {

		/* if ($this->getPluginMethods($cart->vendorId) === 0) {
			return FALSE;
		}
		if (!($selectedMethod = $this->getVmPluginMethod($cart->virtuemart_paymentmethod_id))) {
			return NULL;
		}
		if (isset($cart->cartPrices['salesPrice']) && $cart->cartPrices['salesPrice'] <= 0.0) {
			return NULL;
		}
		if (!$this->isExpToken($selectedMethod, $cart))  {
			$payment_advertise[] = $this->getExpressCheckoutHtml($cart);
		}

		return; */
	}

/**
 * check if selected method is AlphaUserpointsEC, and if a token exist
 */
	function isExpToken($selectedMethod, $cart) {

	 	if (!$this->selectedThisElement($selectedMethod->payment_element)) {
			return FALSE;
		}
		if ($selectedMethod->alphauserpointsproduct == 'exp') {
			$this->_currentMethod = $selectedMethod;
			$alphauserpointsExpInterface = $this->_loadAlphaUserpointsInterface();
			$alphauserpointsExpInterface->loadCustomerData();

				$alphauserpointsExpInterface->setCart($cart);
				$alphauserpointsExpInterface->loadCustomerData();
				$token = $alphauserpointsExpInterface->customerData->getVar('token');
				$payerid = $alphauserpointsExpInterface->customerData->getVar('payer_id');
				if (empty($token) and empty($payerid)) {
					$alphauserpointsExpInterface->customerData->clear();
					$cart->virtuemart_paymentmethod_id = 0;
					$cart->setCartIntoSession();
					return false;
				}
				if (!empty($token) and !empty($payerid)) {
					return true;
				}
		}
		return false;
	}

	/**
	 * @param $cart
	 * @return null|string
	 */
	function getExpressCheckoutHtml( $cart) {


		$html = '';
		foreach ($this->methods as $this->_currentMethod) {
			if ($this->_currentMethod->alphauserpointsproduct == 'exp') {
				$alphauserpointsInterface = $this->_loadAlphaUserpointsInterface();

				$button = $alphauserpointsInterface->getExpressCheckoutButton();
				$html .= $this->renderByLayout('expcheckout',
					array(
						'text' => vmText::_('VMPAYMENT_ALPHAUSERPOINTS_EXPCHECKOUT_BUTTON'),
						'img' => $button['img'],
						'link' => $button['link'],
						'sandbox' => $this->_currentMethod->sandbox,
						'virtuemart_paymentmethod_id' => $this->_currentMethod->virtuemart_paymentmethod_id
					)
				);
			}
		}
		return $html; 
	}

	/**
	 *
	 * @param $cart
	 * @param $order
	 * @return bool|null|void
	 */
	function plgVmConfirmedOrder($cart, $order) {
	
	
	
	if (!($this->_currentMethod = $this->getVmPluginMethod($order['details']['BT']->virtuemart_paymentmethod_id))) {
			return NULL; // Another method was selected, do nothing
		}
		
	if (!$this->selectedThisElement($this->_currentMethod->payment_element)) {
			return FALSE;
		}

	if (!class_exists('VirtueMartModelOrders')) {
			require(VMPATH_ADMIN . DS . 'models' . DS . 'orders.php');
		}

	if (!class_exists('VirtueMartModelCurrency')) {
			require(VMPATH_ADMIN . DS . 'models' . DS . 'currency.php');
		}
    $api_AUP = JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php';
    if ( file_exists($api_AUP))
    {
     require_once ($api_AUP);
     
    }		
	
	if (!class_exists( 'VmModel' )) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart'.DS.'helpers'.DS.'vmmodel.php');
	$app  = JFactory::getApplication();
	$productModel = VmModel::getModel('Product');
	
	$method=$this->_currentMethod;
	//$method->commission=40;
	$commission = round($method->commission);
	
	$this->getPaymentCurrency ($method);
	
	$currency_code_3 = shopFunctions::getCurrencyByID ($method->payment_currency, 'currency_code_3');
	
	$paymentCurrency = CurrencyDisplay::getInstance ($method->payment_currency);
	
	$totalInPaymentCurrency = round ($paymentCurrency->convertCurrencyTo ($method->payment_currency, $order['details']['BT']->order_total, FALSE), 2);
	
	$cd = CurrencyDisplay::getInstance ($cart->pricesCurrency);
		
		
	if ($totalInPaymentCurrency <= 0) {
			vmInfo (JText::_ ('Payment Amount is incorrect.'));
			return FALSE;
		}
	if (empty($commission)) {
			vmInfo (JText::_ ('Commission not set.'));
			return FALSE;
		}
		
	$address = $cart->getST();
	
	$totalPoints = round(AlphaUserPointsHelper::getCurrentTotalPoints('',$order['details']['BT']->virtuemart_user_id));
	
	$order_number    = $order['details']['BT']->order_number;
	//db values
	
	$dbValues['order_number'] = $order_number;
		$dbValues['payment_name'] = $method->payment_element;
		$dbValues['virtuemart_paymentmethod_id'] = $cart->virtuemart_paymentmethod_id;
		$dbValues['atompaynetz_custom'] = $method->payment_element;
		$dbValues['cost_per_transaction'] = $method->cost_per_transaction;
		$dbValues['cost_percent_total'] = $method->cost_percent_total;
		$dbValues['payment_currency'] = $method->payment_currency;
		$dbValues['mc_currency'] = ShopFunctions::getCountryByID ($address->virtuemart_country_id, 'country_2_code');;
		$dbValues['payment_order_total'] = $totalInPaymentCurrency;
		$dbValues['Amount'] = $amount;
		$dbValues['Order_Id'] = $order_number; 
		$dbValues['billing_cust_name'] = $address->first_name .  $address->last_name;
		$dbValues['billing_cust_address'] = $address->address_1.$address->address_2;
		$dbValues['billing_cust_country'] = ShopFunctions::getCountryByID ($address->virtuemart_country_id, 'country_2_code');
		$dbValues['billing_cust_tel'] =  $address->phone_1;
		$dbValues['billing_cust_email'] = $order['details']['BT']->email;
		$dbValues['delivery_cust_name'] = $address->first_name .  $address->last_name;
		$dbValues['delivery_cust_address'] = $address->address_1.$address->address_2;
		$dbValues['delivery_cust_state'] = ShopFunctions::getStateByID ($address->virtuemart_state_id);
		$dbValues['delivery_cust_country'] = ShopFunctions::getCountryByID ($address->virtuemart_country_id, 'country_2_code');
		$dbValues['delivery_cust_tel'] =  $address->phone_1;
		$dbValues['billing_city'] =  $address->city;
		$dbValues['billing_zip'] = $address->zip;
		$dbValues['delivery_city'] =  $address->city;
		$dbValues['delivery_zip'] = $address->zip;
		$dbValues['AuthDesc'] = $order_number; 
		$dbValues['payment_date'] = gmdate ('Y-m-d H:i:s', time ());
		$dbValues['payment_status'] = 'Y';
		$dbValues['pending_reason'] = $method->status_pending;
		$dbValues['delivery_cust_notes'] = $method->payment_element;
		$dbValues['Merchant_Param'] = $merchant_param;
	
	    //save in the database
		
		$this->storePSPluginInternalData($dbValues);
	
	    $orderItems = $order['items'];
		
		$paymentParams = array();
		
		$paymentParams['virtuemart_order_id']= $order['details']['BT']->virtuemart_order_id;
		$paymentParams['order_number']        = $order['details']['BT']->order_number;
		$paymentParams['virtuemart_user_id']=$order['details']['BT']->virtuemart_user_id;
		$paymentParams['commission'] = $method->commission;
		$paymentParams['virtuemart_paymentmethod_id'] = $order['details']['BT']->virtuemart_paymentmethod_id;
		$paymentParams['amount'] = $totalInPaymentCurrency;
		$producsInCart = array();
		foreach($orderItems as $ok=> $ov)
		{
		$productM = $productModel->getProduct($ov->virtuemart_product_id);
		
		$link = $productM->link;
		$vendor = $productM->virtuemart_vendor_id;
		$userid = $order['details']['BT']->virtuemart_user_id;
	    $vendor = $this->getVmVendor($vendor);
	    $aupidb = AlphaUserPointsHelper::getAnyUserReferreID( $userid );
	    $aupids = AlphaUserPointsHelper::getAnyUserReferreID( $vendor );
	
	    
	    if(empty($aupids) || empty($aupidb) || empty($userid) || empty($vendor) )
	    {
		$msgToken ="Unknown cause";
		if($aupids=="")
		$msgToken = "Seller(uid: ".$aupids.") reference id is not present";
		
		if($aupidb=="")
		$msgToken = "Buyer(uid: ".$aupidb.") reference id is not present";
		
		if($userid=="")
		$msgToken = "Buyer id is not present";
		
		if($vendor=="")
		$msgToken = "Vendor id is not present";
		
		
		$msgLog ="Order no: ".$paymentParams['order_number']." Order id: ".$paymentParams['virtuemart_order_id']." Reason: Error processing order, reason".$msgToken."\n";
		
		
		LoggerHelper::addLogs(array("marketplace_failed_order_credits.php",JLog::WARNING,"order_info",$msgLog,JLog::WARNING,"com_alphauserpoints"));  
		
		
	    $cart = VirtueMartCart::getCart();
	    $cart->emptyCart();
	    $app->redirect(JRoute::_(JUri::root()."index.php?option=com_virtuemart&view=virtuemart&productsublayout=products_horizon"),"Sorry, something went wrong.The issue has been reported to the site administrator.");
	    }
	
	
		$images = $this->__getProductMedia($ov->virtuemart_product_id);
		$producsInCart[$ok]=array('pid'=>$ov->virtuemart_product_id,'virtuemart_vendor_id'=>$vendor,"link"=>$link,'order_item_name' => $ov->order_item_name,'media'=>$images,
		'price' => round ($paymentCurrency->convertCurrencyTo ($method->payment_currency, $ov->product_subtotal_with_tax, FALSE), 2));
        } 
		
		
		$paymentParams['products']= $producsInCart;
		
	    if($totalInPaymentCurrency<=$totalPoints)
		{
		$this->_processPayment($paymentParams,$cart, $order);
	    }
		else
		{
		$currUser = JFactory::getUser($order['details']['BT']->virtuemart_user_id);
		
		$uKey = $currUser->id.".".$currUser->name;
		
		$mainframe =JFactory::getApplication();
        $stateVar = $mainframe->setUserState( $uKey, $paymentParams );
		
		//redirect to the payment form
		$app = JFactory::getApplication();
		
		$amrequired = $totalPoints==0?$totalInPaymentCurrency:$totalInPaymentCurrency-$totalPoints;
		
		$app->redirect(JRoute::_(JUri::root()."index.php?option=com_alphauserpoints&view=credits&type=cart&Itemid=442"),"In order to successfully process this order,You need to add $".$amrequired." or more in your account.","message");
		}
	    

 		$cart->_confirmDone = FALSE;
		$cart->_dataValidated = FALSE;
		$cart->setCartIntoSession();		

		vRequest::setVar('html', $html); 
		} 
	
	private function _processPayment($params,$cart,$order)
	{
	
	
	if (!class_exists( 'VmConfig' )) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart'.DS.'helpers'.DS.'config.php');
    VmConfig::loadConfig();
    if (!class_exists( 'VmModel' )) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart'.DS.'helpers'.DS.'vmmodel.php');

  
	
    $cparams 				= JComponentHelper::getParams('com_vmvendor');
	$aup_ratio				= $cparams->get('aup_ratio');
	$commission 			= $cparams->get('commission');
	$vmitemid				= $cparams->get('vmitemid');
	$forbidcatids			= $cparams->get('forbidcatids');
	$onlycatids				= $cparams->get('onlycatids');
	$profileman				= $cparams->get('profileman');
	$sells_activitystream	= $cparams->get('sells_activitystream',0);	
	$banned_cats 			= explode(',',$forbidcatids);
	$prefered_cats 			= explode(',',$onlycatids);
	$app = JFactory::getApplication();
	
	
	
	
	$products = $params['products'];
	$commission = $params['commission'];
	
	$api_AUP = JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php';
    if ( file_exists($api_AUP))
    {
     require_once ($api_AUP);
     
    }
	
	$totalPoints = round(AlphaUserPointsHelper::getCurrentTotalPoints('',$params['virtuemart_user_id']));
	
	$err = array();
	foreach($products as $pk => $pv)
	{
	
	$pprice   =  round( ( $pv['price'] * (100 - $commission) / 100 ) );
	
	
	if($forbidcatids!='' && (in_array($virtuemart_category_id  , $banned_cats) OR $virtuemart_category_id==$forbidcatids) )
	return false;
	if($onlycatids!='' && (!in_array($virtuemart_category_id  , $prefered_cats) OR $virtuemart_category_id==$onlycatids) )
	return false;
	
	$product_url = "<a href='".$pv['link']."'>".$pv['order_item_name']."</a>"; 
	
	$informationdataS = "Sale: ".$product_url." Earned: $".$pprice."($".$pv['price']." - ".$commission."% Appmeadows Commission)";
	
	$informationdataP = "Purchase: ".$product_url." Price: $".$pv['price'];
	$referencekey = $params['virtuemart_order_id'].'|OderItemID|'. microtime();

	$sid = $pv['virtuemart_vendor_id'];
	
	$bid = $params['virtuemart_user_id'];
	
	$aupidb = AlphaUserPointsHelper::getAnyUserReferreID( $bid );
	$aupids = AlphaUserPointsHelper::getAnyUserReferreID( $sid );
	
	
	
	$seller = JFactory::getUser($sid);
	$buyer =  JFactory::getUser($bid);
	if(AlphaUserPointsHelper::newpoints( 'plgaup_vmsales',$aupids, $referencekey,$informationdataS, $pprice,true) && AlphaUserPointsHelper::newpoints( 'plgaup_vmsales',$aupidb, $referencekey,$informationdataP, -1 * abs($pv['price']),true))
	{
	
	$msgLog ="Order no: ".$params['order_number']." Order id: ".$params['virtuemart_order_id']." Message: "."Order successfully processed, placed by: ".$buyer->username."(".$buyer->id.") vendor: ".$seller->username." (".$seller->id.") Amount paid by buyer: $".$pv['price']." Amount paid to vendor: $".$pprice."($".$pv['price']." - ".$commission."% Appmeadows Commission) Product: ".$product_url."\n" ;
		
	LoggerHelper::addLogs(array("marketplace_successfull_order_credits.php",JLog::INFO,"order_info",$msgLog,JLog::INFO,"com_alphauserpoints")); 
	
	
	
	
	}
	else
	{
	$err[] = 1;
	
	} 
	
	}
	if(count($err)==0)
	{
	$sucurl = JURI::root () . 'index.php?option=com_virtuemart&view=vmplg&task=pluginresponsereceived&on=' . $params['order_number'] . '&pm=' . $params['virtuemart_paymentmethod_id'] . '&Itemid=' . JRequest::getInt ('Itemid');
	$app->redirect(JRoute::_($sucurl));
	}
	else
	{
	$failUrl = JURI::root().'index.php?option=com_virtuemart&view=cart';
	$app->redirect(JRoute::_($failUrl,"Sorry, something has went wrong,Issue has been reported to the site administrator.","error"));
	}
	
	}

	
	//get vendor
	private function getVmVendor($virtuemart_vendor_id)
	{

	
	$db = JFactory::getDbo();
	
	$q = "SELECT virtuemart_user_id FROM #__virtuemart_vendor_users WHERE virtuemart_vendor_id='".$virtuemart_vendor_id."'";
	
	$db->setQuery($q);
	
	$items = $db->loadObject();
	

	
	if(!empty($items))
	{
	return $items->virtuemart_user_id;
	}
	else
	{
	return "";
	}
	}

	/**
	 * @param null $msg
	 */
	function redirectToCart ($msg = NULL) {
		 if (!$msg) {
			$msg = vmText::_('VMPAYMENT_ALPHAUSERPOINTS_ERROR_TRY_AGAIN');
		}
		$this->customerData->clear();
		$app = JFactory::getApplication();
		$app->redirect(JRoute::_('index.php?option=com_virtuemart&view=cart&Itemid=' . vRequest::getInt('Itemid'), false), $msg); 
	}

	/**
	 * @param $virtuemart_paymentmethod_id
	 * @param $paymentCurrencyId
	 * @return bool|null
	 */
	function plgVmgetPaymentCurrency($virtuemart_paymentmethod_id, &$paymentCurrencyId) {

		 if (!($this->_currentMethod = $this->getVmPluginMethod($virtuemart_paymentmethod_id))) {
			return NULL; // Another method was selected, do nothing
		}
		if (!$this->selectedThisElement($this->_currentMethod->payment_element)) {
			return FALSE;
		}
		$this->getPaymentCurrency($this->_currentMethod);
		$paymentCurrencyId = $this->_currentMethod->payment_currency; 
	}

	function plgVmgetEmailCurrency($virtuemart_paymentmethod_id, $virtuemart_order_id, &$emailCurrencyId) {

		if (!($this->_currentMethod = $this->getVmPluginMethod($virtuemart_paymentmethod_id))) {
			return NULL; // Another method was selected, do nothing
		}
		if (!$this->selectedThisElement($this->_currentMethod->payment_element)) {
			return FALSE;
		}
		if (!($payments = $this->_getAlphauserpointsInternalData($virtuemart_order_id))) {
			// JError::raiseWarning(500, $db->getErrorMsg());
			return '';
		}
		if (empty($payments[0]->email_currency)) {
			$vendorId = 1; //VirtueMartModelVendor::getLoggedVendor();
			$db = JFactory::getDBO();
			$q = 'SELECT   `vendor_currency` FROM `#__virtuemart_vendors` WHERE `virtuemart_vendor_id`=' . $vendorId;
			$db->setQuery($q);
			$emailCurrencyId = $db->loadResult();
		} else {
			$emailCurrencyId = $payments[0]->email_currency;
		} 

	}

	/**
	 * @param $html
	 * @return bool|null|string
	 */
	function plgVmOnPaymentResponseReceived(&$html) {
 
		if (!class_exists('VirtueMartCart')) {
			require(VMPATH_SITE . DS . 'helpers' . DS . 'cart.php');
		}
		$cart = VirtueMartCart::getCart();
		$app = JFactory::getApplication();
		if (!class_exists('shopFunctionsF')) {
			require(VMPATH_SITE . DS . 'helpers' . DS . 'shopfunctionsf.php');
		}
		if (!class_exists('VirtueMartModelOrders')) {
			require(VMPATH_ADMIN . DS . 'models' . DS . 'orders.php');
		}
		VmConfig::loadJLang('com_virtuemart_orders', TRUE);

		// the payment itself should send the parameter needed.
		$virtuemart_paymentmethod_id = vRequest::getInt('pm', 0);
		
		$order_number = vRequest::getString('on', 0);
		$vendorId = 0;
		
		if (!($this->_currentMethod = $this->getVmPluginMethod($virtuemart_paymentmethod_id))) {
			return NULL; // Another method was selected, do nothing
		}
		
		
		if (!$this->selectedThisElement($this->_currentMethod->payment_element)) {
			return NULL;
		}
		
		if (!($virtuemart_order_id = VirtueMartModelOrders::getOrderIdByOrderNumber($order_number))) {
			return NULL;
		}
		
		$payment_name = $this->renderPluginName($this->_currentMethod);
		
		
		VmConfig::loadJLang('com_virtuemart');
		$orderModel = VmModel::getModel('orders');
		$order = $orderModel->getOrder($virtuemart_order_id); 
		$orderno = $order['details']['BT']->order_number;
		$status = $order['details']['BT']->order_status;
		  if($status=="C")
		{
		$app->redirect(JRoute::_(JUri::root()."index.php?option=com_virtuemart&view=virtuemart&productsublayout=products_horizon&Itemid=199"),"Order no. ".$orderno." has been already processed.","error");
		}  
		
		//update status
		
		$orderU = array();
	
		$orderU['order_status'] = 'C';
		$orderU['virtuemart_order_id'] = $virtuemart_order_id;
		$orderU['customer_notified'] = 0;
		$orderU['comments'] = JText::_ ('COM_VIRTUEMART_ORDER_STATUS_CONFIRMED_BY_SHOPPER');
	    $orderModel->updateStatusForOneOrder ($virtuemart_order_id, $orderU, TRUE);
		
		//notify shopper
		
		/*mail settings*/
	    
		$returnValue = 1;
	
	    $payment_name = $this->renderPluginName($this->_currentMethod, $order);
	
	    $new_status = $this->_currentMethod->status_success;
	
	    $html = $this->renderByLayout('apiresponse', array('method' => $this->_currentMethod, 'success' => $success, 'payment_name' => $payment_name, 'responseData' => $response, "order" => $order));
	
	   
	    $this->processConfirmedOrderPaymentResponse($returnValue, $cart, $order, $html, $payment_name, $new_status);
	
	    /*--mail settings--*/
		
		
		
		//notify administrator
		
		$ammount = $order['details']['BT']->order_total;
		$userId  = $order['details']['BT']->virtuemart_user_id;
		$user = JFactory::getUser($userId);
		$items = $order['items'];
		$productModel = VmModel::getModel('product');
		$cart = VirtueMartCart::getCart();
		$html.="<div class='pm_status'>Your payment has been successfully processed</div>";
	    $html.="<div>Item(s) Bought:</div>";
		$html.= '<table class="table table-hover table-striped">
        <thead>
        <tr>
        <th>Product</th>
        <th>Download</th>
        <th>Price</th>
		<th>Total</th>
        </tr>
        </thead>
        <tbody>';
	
		for( $it=0;$it<count($items); $it++)
		{
		$iv = $items[$it];
	    $product = $productModel->getProduct($iv->virtuemart_product_id);
		$productLink = JUri::root().$product->link;
		$medias = $this->__getProductMedia($iv->virtuemart_product_id);
		$downloadLink=!empty($medias['file_url_thumb'])?$medias['file_url_thumb']:$medias['file_url'];
		$downloadLink.="<br><a href='".$productLink."'>Download: ".$product->product_name."</a>";
		$html.=     '<tr >
        <td>'.$product->product_name.'</td>
        <td>'.$downloadLink.'</td>
        <td>$'.round($iv->product_subtotal_with_tax).'</td>';
		$html.=$it==count($items)-1?"<td>$".round($ammount)."</t>":"";
        $html.='</tr>';
        }
		
  
        $html.='</tbody>
        </table>';
		
		
		//We delete the old stuff
		// get the correct cart / session
		
		$cart->emptyCart();
		
		return TRUE; 
	}

	private function __getProductMedia($pid)
	{
	$db=JFactory::getDbo();
	$q = "SELECT file_url,file_url_thumb FROM #__virtuemart_product_medias as vpm left join #__virtuemart_medias as vm on vm.virtuemart_media_id = vpm.virtuemart_media_id where virtuemart_product_id ='".$pid."'";
	
	$db->setQuery($q);
	$res = $db->loadAssoc();
	
	$return = array("file_url" => "<img src='".JUri::root()."plugins/vmcustom/downloadable/images/download_button.png'>","file_url_thumb" => "<img src='".JUri::root()."plugins/vmcustom/downloadable/images/download_button.png'>");
	
	if(!empty($res['file_url']) || !empty($res['file_url_thumb']))
	{
	$fileurl = "<img src='".JUri::root().$res['file_url']."'>";
	$fileurl_thumb = empty($res['file_url_thumb'])?"":"<img src='".JUri::root().$res['file_url_thumb']."'>";
	$return = array("file_url" => $fileurl,"file_url_thumb" => $fileurl_thumb);
	}
	
	return $return;
	
	}
	
	
	function plgVmOnUserPaymentCancel() {

		if (!class_exists('VirtueMartModelOrders')) {
			require(VMPATH_ADMIN . DS . 'models' . DS . 'orders.php');
		}

		$order_number = vRequest::getString('on', '');
		$virtuemart_paymentmethod_id = vRequest::getInt('pm', '');
		if (empty($order_number) or empty($virtuemart_paymentmethod_id) or !$this->selectedThisByMethodId($virtuemart_paymentmethod_id)) {
			return NULL;
		}
		if (!($virtuemart_order_id = VirtueMartModelOrders::getOrderIdByOrderNumber($order_number))) {
			return NULL;
		}
		if (!($paymentTable = $this->getDataByOrderNumber($order_number))) {
			return NULL;
		}

		VmInfo(vmText::_('VMPAYMENT_ALPHAUSERPOINTS_PAYMENT_CANCELLED'));
		$session = JFactory::getSession();
		$return_context = $session->getId();
		if (strcmp($paymentTable->alphauserpoints_custom, $return_context) === 0) {
			$this->handlePaymentUserCancel($virtuemart_order_id);
		}
		return TRUE; 
	}

	function plgVmOnPaymentNotification() {

 		//https://developer.alphauserpoints.com/webapps/developer/docs/classic/ipn/integration-guide/IPNandPDTVariables/

		if (!class_exists('VirtueMartModelOrders')) {
			require(VMPATH_ADMIN . DS . 'models' . DS . 'orders.php');
		}
		$alphauserpoints_data = $_POST;

		//Recuring payment return rp_invoice_id instead of invoice
		if (array_key_exists('rp_invoice_id', $alphauserpoints_data)) {
			$alphauserpoints_data['invoice'] = $alphauserpoints_data['rp_invoice_id'];
		}
		if (!isset($alphauserpoints_data['invoice'])) {
			return FALSE;
		}

		$order_number = $alphauserpoints_data['invoice'];
		if (!($virtuemart_order_id = VirtueMartModelOrders::getOrderIdByOrderNumber($alphauserpoints_data['invoice']))) {
			return FALSE;
		}

		if (!($payments = $this->getDatasByOrderNumber($order_number))) {
			return FALSE;
		}

		$this->_currentMethod = $this->getVmPluginMethod($payments[0]->virtuemart_paymentmethod_id);
		if (!$this->selectedThisElement($this->_currentMethod->payment_element)) {
			return FALSE;
		}

		$orderModel = VmModel::getModel('orders');
		$order = $orderModel->getOrder($virtuemart_order_id);

		$alphauserpointsInterface = $this->_loadAlphaUserpointsInterface();
		$alphauserpointsInterface->setOrder($order);
		$alphauserpointsInterface->debugLog($alphauserpoints_data, 'PaymentNotification, alphauserpoints_data:', 'debug');
		$alphauserpointsInterface->debugLog($order_number, 'PaymentNotification, order_number:', 'debug');
		$alphauserpointsInterface->debugLog($payments[0]->virtuemart_paymentmethod_id, 'PaymentNotification, virtuemart_paymentmethod_id:', 'debug');
		$order_history = $alphauserpointsInterface->processIPN($alphauserpoints_data, $payments);
		if (!$order_history) {
			return false;
		} else {
			$this->_storeAlphauserpointsInternalData( $alphauserpoints_data, $virtuemart_order_id, $payments[0]->virtuemart_paymentmethod_id, $order_number);
			$alphauserpointsInterface->debugLog('plgVmOnPaymentNotification order_number:' . $order_number . ' new_status:' . $order_history['order_status'], 'plgVmOnPaymentNotification', 'debug');

			$orderModel->updateStatusForOneOrder($virtuemart_order_id, $order_history, TRUE);
			//// remove vmcart
			if (isset($alphauserpoints_data['custom'])) {
				$this->emptyCart($alphauserpoints_data['custom'], $order_number);
				$alphauserpointsInterface->debugLog('plgVmOnPaymentNotification empty cart ', 'plgVmOnPaymentNotification', 'debug');
			}
		} 
	}

	/*********************/
	/* Private functions */
	/*********************/
	private function _loadAlphaUserpointsInterface() {
		 $this->_currentMethod->alphauserpointsproduct = $this->getAlphauserpointsProduct($this->_currentMethod);

		if ($this->_currentMethod->alphauserpointsproduct == 'std') {
			$alphauserpointsInterface = new AlphauserpointsHelperAlphaUserpointsStd($this->_currentMethod, $this);
		} else {
			if ($this->_currentMethod->alphauserpointsproduct == 'api') {
				$alphauserpointsInterface = new AlphauserpointsHelperAlphaUserpointsApi($this->_currentMethod, $this);
			} else {
				if ($this->_currentMethod->alphauserpointsproduct == 'exp') {
					$alphauserpointsInterface = new AlphauserpointsHelperAlphaUserpointsExp($this->_currentMethod, $this);
				} else {
					if ($this->_currentMethod->alphauserpointsproduct == 'hosted') {
						$alphauserpointsInterface = new AlphauserpointsHelperAlphaUserpointsHosted($this->_currentMethod, $this);
					} else {
						Vmerror('Wrong alphauserpoints mode');
						return NULL;
					}
				}
			}
		}
		
		
		return $alphauserpointsInterface;
	}

	private function _storeAlphauserpointsInternalData( $alphauserpoints_data, $virtuemart_order_id, $virtuemart_paymentmethod_id, $order_number) {
		$alphauserpointsInterface = $this->_loadAlphaUserpointsInterface();
		// get all know columns of the table
		$db = JFactory::getDBO();
		$query = 'SHOW COLUMNS FROM `' . $this->_tablename . '` ';
		$db->setQuery($query);
		$columns = $db->loadColumn(0);

		$post_msg = '';
		
        foreach ($alphauserpoints_data as $key => $value) {
            $post_msg .= $key . "=" . $value . "<br />";
            $table_key = 'alphauserpoints_response_' . $key;
            $table_key=strtolower($table_key);
            if (in_array($table_key, $columns)   ) {
                $response_fields[$table_key] = $value;
            }
        }
		
		//$response_fields = $alphauserpointsInterface->storeAlphauserpointsInternalData($alphauserpoints_data);
		if (array_key_exists('PAYMENTINFO_0_PAYMENTSTATUS', $alphauserpoints_data)) {
			$response_fields['alphauserpoints_response_payment_status'] = $alphauserpoints_data['PAYMENTINFO_0_PAYMENTSTATUS'];
		} else {
			if (array_key_exists('PAYMENTSTATUS', $alphauserpoints_data)) {
				$response_fields['alphauserpoints_response_payment_status'] = $alphauserpoints_data['PAYMENTSTATUS'];
			} else {
				if (array_key_exists('PROFILESTATUS', $alphauserpoints_data)) {
					$response_fields['alphauserpoints_response_payment_status'] = $alphauserpoints_data['PROFILESTATUS'];
				} else {
					if (array_key_exists('STATUS', $alphauserpoints_data)) {
						$response_fields['alphauserpoints_response_payment_status'] = $alphauserpoints_data['STATUS'];
					}
				}
			}
		}


		if ($alphauserpoints_data) {
			$response_fields['alphauserpoints_fullresponse'] = json_encode($alphauserpoints_data);
		}
		$response_fields['order_number'] = $order_number;
		if (isset($alphauserpoints_data['invoice'])) {
			$response_fields['alphauserpoints_response_invoice'] = $alphauserpoints_data['invoice'];
		}

		$response_fields['virtuemart_order_id'] = $virtuemart_order_id;
		$response_fields['virtuemart_paymentmethod_id'] = $virtuemart_paymentmethod_id;
		if (array_key_exists('custom', $alphauserpoints_data)) {
			$response_fields['alphauserpoints_custom'] = $alphauserpoints_data['custom'];
		}

		//$preload=true   preload the data here too preserve not updated data
		return $this->storePSPluginInternalData($response_fields, $this->_tablepkey, 0);

	}

	/**
	 * @param   int $virtuemart_order_id
	 * @param string $order_number
	 * @return mixed|string
	 */
	private function _getAlphauserpointsInternalData($virtuemart_order_id, $order_number = '') {
		if (empty($order_number)) {
			$orderModel = VmModel::getModel('orders');
			$order_number = $orderModel->getOrderNumber($virtuemart_order_id);
		}
		$db = JFactory::getDBO();
		$q = 'SELECT * FROM `' . $this->_tablename . '` WHERE ';
		$q .= " `order_number` = '" . $order_number . "'";

		$db->setQuery($q);
		if (!($payments = $db->loadObjectList())) {
			// JError::raiseWarning(500, $db->getErrorMsg());
			return '';
		}
		return $payments;
	}

    protected function renderPluginName($activeMethod) {
	return '<span class="pmname">'.$activeMethod->payment_name.'<img src="'.JUri::root().'plugins/vmpayment/alphauserpoints/alphauserpoints/assets/images/'.implode('_',explode(' ',$activeMethod->payment_name)).'.png"></span><br />' ;
	}

	function displayExtraPluginNameInfo($activeMethod) {
		$this->_currentMethod = $activeMethod;

		$alphauserpointsInterface = $this->_loadAlphaUserpointsInterface();
		$alphauserpointsInterface->loadCustomerData();
		$extraInfo = $alphauserpointsInterface->displayExtraPluginInfo();

		return $extraInfo;

	}

	/**
	 * Display stored payment data for an order
	 *
	 * @see components/com_virtuemart/helpers/vmPSPlugin::plgVmOnShowOrderBEPayment()
	 */
	function plgVmOnShowOrderBEPayment($virtuemart_order_id, $payment_method_id) {

		if (!$this->selectedThisByMethodId($payment_method_id)) {
			return NULL; // Another method was selected, do nothing
		}
		if (!($this->_currentMethod = $this->getVmPluginMethod($payment_method_id))) {
			return FALSE;
		}
		if (!($payments = $this->_getAlphauserpointsInternalData($virtuemart_order_id))) {
			// JError::raiseWarning(500, $db->getErrorMsg());
			return '';
		}

		//$html = $this->renderByLayout('orderbepayment', array($payments, $this->_psType));
		$html = '<table class="adminlist table" >' . "\n";
		$html .= $this->getHtmlHeaderBE();
		$code = "alphauserpoints_response_";
		$first = TRUE;
		foreach ($payments as $payment) {
			$html .= ' <tr class="row1"><td><strong>' . vmText::_('VMPAYMENT_ALPHAUSERPOINTS_DATE') . '</strong></td><td align="left"><strong>' . $payment->created_on . '</strong></td></tr> ';
			// Now only the first entry has this data when creating the order
			if ($first) {
				$html .= $this->getHtmlRowBE('COM_VIRTUEMART_PAYMENT_NAME', $payment->payment_name);
				// keep that test to have it backwards compatible. Old version was deleting that column  when receiving an IPN notification
				if ($payment->payment_order_total and  $payment->payment_order_total != 0.00) {
					$html .= $this->getHtmlRowBE('COM_VIRTUEMART_TOTAL', $payment->payment_order_total . " " . shopFunctions::getCurrencyByID($payment->payment_currency, 'currency_code_3'));
				}

				$first = FALSE;
			} else {
				$alphauserpointsInterface = $this->_loadAlphaUserpointsInterface();

				if (isset($payment->alphauserpoints_fullresponse) and !empty($payment->alphauserpoints_fullresponse)) {
					$alphauserpoints_data = json_decode($payment->alphauserpoints_fullresponse);
					$alphauserpointsInterface = $this->_loadAlphaUserpointsInterface();
					$html .= $alphauserpointsInterface->onShowOrderBEPayment($alphauserpoints_data);

					$html .= '<tr><td></td><td>
    <a href="#" class="AlphaUserpointsLogOpener" rel="' . $payment->id . '" >
        <div style="background-color: white; z-index: 100; right:0; display: none; border:solid 2px; padding:10px;" class="vm-absolute" id="AlphaUserpointsLog_' . $payment->id . '">';

					foreach ($alphauserpoints_data as $key => $value) {
						$html .= ' <b>' . $key . '</b>:&nbsp;' . $value . '<br />';
					}

					$html .= ' </div>
        <span class="icon-nofloat vmicon vmicon-16-xml"></span>&nbsp;';
					$html .= vmText::_('VMPAYMENT_ALPHAUSERPOINTS_VIEW_TRANSACTION_LOG');
					$html .= '  </a>';
					$html .= ' </td></tr>';
				} else {
					$html .= $alphauserpointsInterface->onShowOrderBEPaymentByFields($payment);
				}
			}


		}
		$html .= '</table>' . "\n";

		$doc = JFactory::getDocument();
		$js = "
	jQuery().ready(function($) {
		$('.AlphaUserpointsLogOpener').click(function() {
			var logId = $(this).attr('rel');
			$('#AlphaUserpointsLog_'+logId).toggle();
			return false;
		});
	});";
		$doc->addScriptDeclaration($js);
		return $html;
		
	

	}


	/**
	 * Check if the payment conditions are fulfilled for this payment method
	 * @param VirtueMartCart $cart
	 * @param int $activeMethod
	 * @param array $cart_prices
	 * @return bool
	 */
	protected function checkConditions($cart, $activeMethod, $cart_prices) {
return true;
	}


	/**
	 * @param $jplugin_id
	 * @return bool|mixed
	 */
	function plgVmOnStoreInstallPaymentPluginTable($jplugin_id) {
//triggers everytime payment is saved
//return $this->onStoreInstallPluginTable($jplugin_id);

	}

	/**
	 *     * This event is fired after the payment method has been selected.
	 * It can be used to store additional payment info in the cart.
	 * @param VirtueMartCart $cart
	 * @param $msg
	 * @return bool|null
	 */
	public function plgVmOnSelectCheckPayment(VirtueMartCart $cart, &$msg) {

		if (!$this->selectedThisByMethodId($cart->virtuemart_paymentmethod_id)) {
			return null; // Another method was selected, do nothing
		}

		if (!($this->_currentMethod = $this->getVmPluginMethod($cart->virtuemart_paymentmethod_id))) {
			return FALSE;
		}

		$alphauserpointsInterface = $this->_loadAlphaUserpointsInterface();
		$alphauserpointsInterface->setCart($cart);
		$alphauserpointsInterface->setTotal($cart->cartPrices['billTotal']);
		$alphauserpointsInterface->loadCustomerData();
		$alphauserpointsInterface->getExtraPluginInfo($this->_currentMethod);

		if (!$alphauserpointsInterface->validate()) {
			if ($this->_currentMethod->alphauserpointsproduct != 'api') {
				VmInfo('VMPAYMENT_ALPHAUSERPOINTS_PAYMENT_NOT_VALID');
			}
			return false;
		}


		return true;
	}

	/*******************/
	/* Order cancelled */
	/* May be it is removed in VM 2.1
	/*******************/
	public function plgVmOnCancelPayment(&$order, $old_order_status) {
		return NULL;

	}

	/**
	 *  Order status changed
	 * @param $order
	 * @param $old_order_status
	 * @return bool|null
	 */
	public function plgVmOnUpdateOrderPayment(&$order, $old_order_status) {

		//Load the method
		if (!($this->_currentMethod = $this->getVmPluginMethod($order->virtuemart_paymentmethod_id))) {
			return NULL; // Another method was selected, do nothing
		}

		if (!$this->selectedThisElement($this->_currentMethod -> payment_element)) {
			return NULL;
		}

		//Load only when updating status to shipped
		if ($order->order_status != $this->_currentMethod->status_capture AND $order->order_status != $this->_currentMethod->status_refunded) {
			//return null;
		}
		//Load the payments
		if (!($payments = $this->_getAlphauserpointsInternalData($order->virtuemart_order_id))) {
			// JError::raiseWarning(500, $db->getErrorMsg());
			return null;
		}

		if ($this->_currentMethod->alphauserpointsproduct == 'std') {
			return null;
		}
		//$this->_currentMethod->alphauserpointsproduct = $this->($this->_currentMethod);

		$payment = end($payments);
		if ($this->_currentMethod->payment_action == 'Authorization' and $order->order_status == $this->_currentMethod->status_capture) {
			$alphauserpointsInterface = $this->_loadAlphaUserpointsInterface();
			$alphauserpointsInterface->setOrder($order);
			$alphauserpointsInterface->setTotal($order->order_total);
			$alphauserpointsInterface->loadCustomerData();
			if ($alphauserpointsInterface->DoCapture($payment)) {
				$alphauserpointsInterface->debugLog(vmText::_('VMPAYMENT_ALPHAUSERPOINTS_API_TRANSACTION_CAPTURED'), 'plgVmOnUpdateOrderPayment', 'message', true);
				$this->_storeAlphauserpointsInternalData(  $alphauserpointsInterface->getResponse(false), $order->virtuemart_order_id, $payment->virtuemart_paymentmethod_id, $order->order_number);
			}

		} elseif ($order->order_status == $this->_currentMethod->status_refunded OR $order->order_status == $this->_currentMethod->status_canceled) {
			$alphauserpointsInterface = $this->_loadAlphaUserpointsInterface();
			$alphauserpointsInterface->setOrder($order);
			$alphauserpointsInterface->setTotal($order->order_total);
			$alphauserpointsInterface->loadCustomerData();
			if ($alphauserpointsInterface->RefundTransaction($payment)) {
				if ($this->_currentMethod->payment_type == '_xclick-subscriptions') {
					$alphauserpointsInterface->debugLog(vmText::_('VMPAYMENT_ALPHAUSERPOINTS_SUBSCRIPTION_CANCELLED'), 'plgVmOnUpdateOrderPayment Refund', 'message', true);
				} else {
					//Mark the order as refunded
					// $order->order_status = $method->status_refunded;
					$alphauserpointsInterface->debugLog(vmText::_('VMPAYMENT_ALPHAUSERPOINTS_API_TRANSACTION_REFUNDED'), 'plgVmOnUpdateOrderPayment Refund', 'message', true);
				}
				$this->_storeAlphauserpointsInternalData( $alphauserpointsInterface->getResponse(false), $order->virtuemart_order_id, $payment->virtuemart_paymentmethod_id, $order->order_number);
			}
		}

		return true;
	}

	function plgVmOnUpdateOrderLinePayment(&$order) {
		// $xx=1;
	}

	/*******************/
	/* Credit Card API */
	/*******************/
	public function _displayCVVImages($method) {
		$cvv_images = $method->cvv_images;
		$img = '';
		if ($cvv_images) {
			$img = $this->displayLogos($cvv_images);
			$img = str_replace('"', "'", $img);
		}
		return $img;
	}


	/**
	 * * List payment methods selection
	 * @param VirtueMartCart $cart
	 * @param int $selected
	 * @param $htmlIn
	 * @return bool
	 */

	public function plgVmDisplayListFEPayment(VirtueMartCart $cart, $selected = 0, &$htmlIn) {

		if ($this->getPluginMethods($cart->vendorId) === 0) {
			if (empty($this->_name)) {
				$app = JFactory::getApplication();
				$app->enqueueMessage(vmText::_('COM_VIRTUEMART_CART_NO_' . strtoupper($this->_psType)));
				return false;
			} else {
				return false;
			}
		}
		$method_name = $this->_psType . '_name';

		$htmla = array();
		foreach ($this->methods as $this->_currentMethod) {
			if ($this->checkConditions($cart, $this->_currentMethod, $cart->cartPrices)) {

				$html = '';
				$cartPrices=$cart->cartPrices;
				if (isset($this->_currentMethod->cost_method)) {
					$cost_method=$this->_currentMethod->cost_method;
				} else {
					$cost_method=true;
				}
				$methodSalesPrice = $this->setCartPrices($cart, $cartPrices, $this->_currentMethod, $cost_method);

				$this->_currentMethod->$method_name = $this->renderPluginName($this->_currentMethod);
				$html .= $this->getPluginHtml($this->_currentMethod, $selected, $methodSalesPrice);


				if ($this->_currentMethod->alphauserpointsproduct == 'api') {
					if (empty($this->_currentMethod->creditcards)) {
						$this->_currentMethod->creditcards = AlphauserpointsHelperAlphauserpoints::getAlphauserpointsCreditCards();
					} elseif (!is_array($this->_currentMethod->creditcards)) {
						$this->_currentMethod->creditcards = (array)$this->_currentMethod->creditcards;
					}
					$html .= $this->renderByLayout('creditcardform', array('creditcards' => $this->_currentMethod->creditcards,
						'virtuemart_paymentmethod_id' => $this->_currentMethod->virtuemart_paymentmethod_id,
						'method' => $this->_currentMethod,
						'sandbox' => $this->_currentMethod->sandbox,
						'customerData' => $this->customerData));
				}
				if ($this->_currentMethod->payment_type == '_xclick-auto-billing' && $this->_currentMethod->billing_max_amount_type == 'cust') {
					$html .= $this->renderByLayout('billingmax', array("method" => $this->_currentMethod, "customerData" => $this->customerData));
				}
				if ($this->_currentMethod->payment_type == '_xclick-subscriptions') {
					$alphauserpointsInterface = $this->_loadAlphaUserpointsInterface();
					$html .= '<br/><span class="vmpayment_cardinfo">' . $alphauserpointsInterface->getRecurringProfileDesc() . '</span>';
				}
				if ($this->_currentMethod->payment_type == '_xclick-payment-plan') {
					$alphauserpointsInterface = $this->_loadAlphaUserpointsInterface();
					$html .= '<br/><span class="vmpayment_cardinfo">' . $alphauserpointsInterface->getPaymentPlanDesc() . '</span>';
				}

				$htmla[] = $html;
			}
		}
		$htmlIn[] = $htmla;
		return true;

	}


	/**
	 * Validate payment on checkout
	 * @param VirtueMartCart $cart
	 * @return bool|null
	 */
	function plgVmOnCheckoutCheckDataPayment(VirtueMartCart $cart) {

		if (!$this->selectedThisByMethodId($cart->virtuemart_paymentmethod_id)) {
			return NULL; // Another method was selected, do nothing
		}

		if (!($this->_currentMethod = $this->getVmPluginMethod($cart->virtuemart_paymentmethod_id))) {
			return FALSE;
		}

		//If AlphaUserpoints express, make sure we have a valid token.
		//If not, redirect to AlphaUserpoints to get one.
		$alphauserpointsInterface = $this->_loadAlphaUserpointsInterface();

		$alphauserpointsInterface->setCart($cart);
		$cart->getCartPrices();
		$alphauserpointsInterface->setTotal($cart->cartPrices['billTotal']);

		// Here we only check for token, but should check for payer id ?
		$alphauserpointsInterface->loadCustomerData();
		$alphauserpointsInterface->getExtraPluginInfo($this->_currentMethod);
		$expressCheckout = vRequest::getVar('expresscheckout', '');
		if ($expressCheckout == 'cancel') {
			return true;
		}
		if (!$alphauserpointsInterface->validate()) {
			return false;
		}

		return true;
		//Validate amount
		//if ($totalInPaymentCurrency <= 0) {
		//	vmInfo (vmText::_ ('VMPAYMENT_ALPHAUSERPOINTS_PAYMENT_AMOUNT_INCORRECT'));
		//	return FALSE;
		//}
	}


	/**
	 * For Express Checkout
	 * @param $type
	 * @param $name
	 * @param $render
	 * @return bool|null
	 */

	function plgVmOnSelfCallFE($type, $name, &$render) {
		if ($name != $this->_name || $type != 'vmpayment') {
			return FALSE;
		}
		$action = vRequest::getCmd('action');
		$virtuemart_paymentmethod_id = vRequest::getInt('pm');
		//Load the method
		if (!($currentMethod = $this->getVmPluginMethod($virtuemart_paymentmethod_id))) {
			return NULL; // Another method was selected, do nothing
		}
		if (!$this->selectedThisElement($currentMethod->payment_element)) {
			return FALSE;
		}
		if ($action != 'SetExpressCheckout') {
			return false;
		}

			if (!class_exists('VirtueMartCart')) {
				require(VMPATH_SITE . DS . 'helpers' . DS . 'cart.php');
			}
			$cart = VirtueMartCart::getCart();
			//$cart->prepareCartData();
			$cart->virtuemart_paymentmethod_id = $virtuemart_paymentmethod_id;
			$cart->setCartIntoSession();
			$this->_currentMethod = $currentMethod;
			$alphauserpointsInterface = $this->_loadAlphaUserpointsInterface();
			$alphauserpointsInterface->setCart($cart);
			$alphauserpointsInterface->setTotal($cart->cartPrices['billTotal']);
			$alphauserpointsInterface->loadCustomerData();
			// will perform $this->getExpressCheckoutDetails();
			$alphauserpointsInterface->getExtraPluginInfo($this->_currentMethod);

			if (!$alphauserpointsInterface->validate()) {
				VmInfo('VMPAYMENT_ALPHAUSERPOINTS_PAYMENT_NOT_VALID');
				return false;
			} else {
				$app = JFactory::getApplication();
				$app->redirect(JRoute::_('index.php?option=com_virtuemart&view=cart&Itemid=' . vRequest::getInt('Itemid'), false));
			}


	}

	//Calculate the price (value, tax_id) of the selected method, It is called by the calculator
	//This function does NOT to be reimplemented. If not reimplemented, then the default values from this function are taken.
	public function plgVmOnSelectedCalculatePricePayment(VirtueMartCart $cart, array &$cart_prices, &$cart_prices_name) {
		if (!($selectedMethod = $this->getVmPluginMethod($cart->virtuemart_paymentmethod_id))) {
			return FALSE;
		}
		//$this->isExpToken($selectedMethod, $cart) ;
		return $this->onSelectedCalculatePrice($cart, $cart_prices, $cart_prices_name);
	}


	/* backward compatibility */
	function getAlphauserpointsProduct() {
		if (isset($this->_currentMethod->alphauserpointsproduct) and !empty($this->_currentMethod->alphauserpointsproduct)) {
			return $this->_currentMethod->alphauserpointsproduct;
		} else {
			return 'std';
		}
	}


	// Checks how many plugins are available. If only one, the user will not have the choice. Enter edit_xxx page
	// The plugin must check first if it is the correct type
	function plgVmOnCheckAutomaticSelectedPayment(VirtueMartCart $cart, array $cart_prices = array(), &$paymentCounter) {
		return $this->onCheckAutomaticSelected($cart, $cart_prices, $paymentCounter);
	}

	// This method is fired when showing the order details in the frontend.
	// It displays the method-specific data.
	public function plgVmOnShowOrderFEPayment($virtuemart_order_id, $virtuemart_paymentmethod_id, &$payment_name) {
		$this->onShowOrderFE($virtuemart_order_id, $virtuemart_paymentmethod_id, $payment_name);
	}

	// This method is fired when showing when priting an Order
	// It displays the the payment method-specific data.
	function plgVmonShowOrderPrintPayment($order_number, $method_id) {
		return $this->onShowOrderPrint($order_number, $method_id);
	}

	function plgVmDeclarePluginParamsPaymentVM3( &$data) {
		return $this->declarePluginParams('payment', $data);
	}

	function plgVmSetOnTablePluginParamsPayment($name, $id, &$table) {
		return $this->setOnTablePluginParams($name, $id, $table);
	}
	
		private function _preprocessInitialPayment($domain,$port,$param)
	{
	
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $domain);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_PORT , $port);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
	
	$returnData = curl_exec($ch);
	
	$xmlObj = new SimpleXMLElement($returnData);
    $final_url = $xmlObj->MERCHANT->RESPONSE->url;
    // eof code to generate token
    // code to generate form action
    $param = "";
    $param .= "&ttype=NBFundTransfer";
    $param .= "&tempTxnId=".$xmlObj->MERCHANT->RESPONSE->param[1];
    $param .= "&token=".$xmlObj->MERCHANT->RESPONSE->param[2];
    $param .= "&txnStage=1";
    $url = $domain."?".$param;
	
	
	
	
	return $url;
	}

}

// No closing tag
