<?php
defined('_JEXEC') or 	die( 'Direct Access to ' . basename( __FILE__ ) . ' is not allowed.' ) ;
/**
 * @version $Id: points2vendor.php
 *
 * @author Adrien Roussel
 * @package VirtueMart
 * @subpackage payment
 * @copyright Copyright (C) 2004-2008 soeren - All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
 *
 * http://virtuemart.org
 */

if (!class_exists('vmCustomPlugin')) require(JPATH_VM_PLUGINS . DS . 'vmcustomplugin.php');
class plgVmPaymentPaypaladaptive extends vmCustomPlugin {
	public function plgVmOnUpdateOrderPayment(  $_formData) {
		
		include_once('paypal.class.php');
    
		function execute_payment( $sandbox, $api_username, $api_password, $api_signature, $currency, $fees_payer, $receivers, $return_page ) {
			
			// Create PayPal object.
			$PayPalConfig = array(
				'Sandbox' => $sandbox, 
				'DeveloperAccountEmail' => '', 
				'ApplicationID' => 'APP-80W284485P519543T', 
				'DeviceID' => '', 
				'IPAddress' => $_SERVER['REMOTE_ADDR'], 
				'APIUsername' => $api_username, 
				'APIPassword' => $api_password, 
				'APISignature' => $api_signature, 
				'APISubject' => ''
			);
			$PayPal = new PayPal_Adaptive($PayPalConfig);
			
			// Prepare request arrays
			$PayRequestFields = array(
				'ActionType' => 'CREATE', 
				'CancelURL' => $return_page, 	
				'CurrencyCode' => $currency, 	
				'FeesPayer' => $fees_payer, 			
				'IPNNotificationURL' => '', 	
				'Memo' => '', 	
				'Pin' => '', 	
				'PreapprovalKey' => '', 
				'ReturnURL' => $current_page, 
				'ReverseAllParallelPaymentsOnError' => '', 
				'SenderEmail' => '',           
				'TrackingID' => ''	
			);
				
			$ClientDetailsFields = array(
				'CustomerID' => '', 		
				'CustomerType' => '', 				
				'GeoLocation' => '', 		
				'Model' => '', 				
				'PartnerName' => 'Always Give Back'
			);
									
			$FundingTypes = array('ECHECK', 'BALANCE', 'CREDITCARD');
			
			$SenderIdentifierFields = array(
				'UseCredentials' => ''			
			);
											
			$AccountIdentifierFields = array(
				'Email' => '', 			
				'Phone' => array('CountryCode' => '', 'PhoneNumber' => '', 'Extension' => '')	
			);
											
			$PayPalRequestData = array(
				'PayRequestFields' => $PayRequestFields, 
				'ClientDetailsFields' => $ClientDetailsFields, 
				'FundingTypes' => $FundingTypes, 
				'Receivers' => $receivers, 
				'SenderIdentifierFields' => $SenderIdentifierFields, 
				'AccountIdentifierFields' => $AccountIdentifierFields
			);
			
			$PayPalResult = $PayPal->Pay($PayPalRequestData);
			
			return $PayPalResult;
		}





		
		$lang =& JFactory::getLanguage();
		$app = JFactory::getApplication();
		$extension = 'com_vmvendor';
		$language_tag = $lang->get('tag');
		$reload = true;
		$lang->load($extension, JPATH_SITE, $language_tag, $reload);
		$db = &JFactory::getDBO();
		$cparams 		=& JComponentHelper::getParams('com_vmvendor');
		$aup_ratio		= $cparams->getValue('aup_ratio');
		$commission 	= $cparams->getValue('commission');
		$vmitemid		= $cparams->getValue('vmitemid');
		$forbidcatids	= $cparams->getValue('forbidcatids');
		$profileman		= $cparams->getValue('profileman');
		$sells_activitystream	= $cparams->getValue('sells_activitystream',0);	
		$banned_cats = explode(',',$forbidcatids);
		
		//$order_number 	= $order['details']['BT']->order_number;		
		$q ="SELECT vc.`currency_symbol` , vc.`currency_positive_style` , vc.`currency_decimal_place` , vc.`currency_decimal_symbol` , vc.`currency_thousands` 
		FROM `#__virtuemart_currencies` vc 
		LEFT JOIN `#__virtuemart_vendors` vv ON vv.`vendor_currency` = vc.`virtuemart_currency_id` 
		WHERE vv.`virtuemart_vendor_id` ='1' " ;		
		$db->setQuery($q);
		$main_currency = $db->loadRow();
		$currency_symbol 			= $main_currency[0];
		$currency_positive_style	= $main_currency[1];
		$currency_decimal_place 	= $main_currency[2];
		$currency_decimal_symbol 	= $main_currency[3];
		$currency_thousands 		= $main_currency[4];
		$virtuemart_order_id = $_formData->virtuemart_order_id;
		
		$q ="SELECT voi.`virtuemart_order_item_id` ,  voi.`virtuemart_product_id` , voi.`order_item_sku` , voi.`order_item_name` , voi.`product_quantity` , voi.`product_item_price` , voi.`product_final_price` , 
		vp.`virtuemart_vendor_id` ,
		vpc.`virtuemart_category_id` 
		FROM `#__virtuemart_order_items` voi 
		LEFT JOIN `#__virtuemart_products` vp ON vp.`virtuemart_product_id` = voi.`virtuemart_product_id` 
		LEFT JOIN `#__virtuemart_product_categories` vpc ON vpc.`virtuemart_product_id` = voi.`virtuemart_product_id` 
		WHERE `virtuemart_order_id` = '".$virtuemart_order_id."' ";  //echo ok
		$db->setQuery($q);
		$items = $db->loadObjectList();
			
			
			
	 	if ($_formData->order_status == "C") {
		
			
							
			foreach ($items as $item){
				$virtuemart_order_item_id	= $item->virtuemart_order_item_id;
				$virtuemart_product_id		= $item->virtuemart_product_id;
				$virtuemart_category_id 	= $item->virtuemart_category_id;
				$dopay=1;
				foreach($banned_cats as $banned_cat){
					if( $banned_cat == $virtuemart_category_id)
						$dopay=0;
				}
				
				
				 
				$product_item_price 		= $item->product_item_price;
				$product_final_price 		= $item->product_final_price;
				$tax_topay					= $product_final_price - $product_item_price;
				$product_quantity 			= $item->product_quantity;
				$order_item_name			= $item->order_item_name;
				$order_item_sku				= $item->order_item_sku;
				$virtuemart_vendor_id		= $item->virtuemart_vendor_id;
				$product_url = 'index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$virtuemart_product_id.'&virtuemart_category_id='.$virtuemart_category_id.'&Itemid='.$vmitemid;
									
				$total_price = $product_item_price * $product_quantity;
				if($dopay==1){
					$q = "SELECT `virtuemart_user_id` FROM `#__virtuemart_vmusers` WHERE `virtuemart_vendor_id`='".$virtuemart_vendor_id."' ";
					$db->setQuery($q);
					$vendor_user_id = $db->loadResult();
											
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
							 
				}		
			}					
		}
		
		
		if ( $_formData->order_status == 'X' || $_formData->order_status == 'R' ){  // cancelled or refund
			
			foreach ($items as $item){
				
				$virtuemart_order_item_id	= $item->virtuemart_order_item_id;
				$virtuemart_product_id		= $item->virtuemart_product_id;
				$virtuemart_category_id 	= $item->virtuemart_category_id;
				$product_quantity 			= $item->product_quantity;
				$order_item_name			= $item->order_item_name;
				$product_final_price 		= $item->product_final_price;
				
				
				
				$paid_keyreference = $virtuemart_order_item_id .'|OderItemID';
				$q = "SELECT referreid , points FROM #__alpha_userpoints_details WHERE keyreference='".$paid_keyreference."' ";
				$db->setQuery($q);
				$aup_data = $db->loadRow();
				$referreid = $aup_data[0];
				$points2revert = -$aup_data[1];
				if($points2revert<0){
					$app->enqueueMessage('Refund Vendor points! '.$points2revert);
					$api_AUP = JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php';
					if ( file_exists($api_AUP)){
						require_once ($api_AUP);
						$keyreference = AlphaUserPointsHelper::buildKeyreference('plgaup_cancel_vendorpoints', $virtuemart_order_item_id. "|CanceledVendorPoints" );
						$informationdata= JText::_('COM_VMVENDOR_EMAIL_STATUSCANCELED_INFODATA').' '.$saleordernumber.'.  '.$product_quantity.' x '.$order_item_name .': ('.$product_quantity.'x'.$product_final_price.')x'.$commission.'%='.$points2revert.' '.JText::_('VMPAYMENT_PTS2VENDOR_PTS');
						AlphaUserPointsHelper::newpoints( 
							'plgaup_cancel_vendorpoints', 
							$referreid ,
							$keyreference,  
							$informationdata,
							$points2revert
						);
					}
				}
			}
		} 
	}
}




























































