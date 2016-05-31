<?php
defined('_JEXEC') or 	die( 'Direct Access to ' . basename( __FILE__ ) . ' is not allowed.' ) ;
/**
 * @version $Id: points2vendor.php
 * @author Adrien Roussel
 * @subpackage payment
 * @copyright Copyright (C) 2004-2014 Nordmograph.com - All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL v3, see LICENSE.php
 * http://virtuemart.org
 */

if (!class_exists('vmCustomPlugin'))
	require JPATH_VM_PLUGINS .  '/vmcustomplugin.php';
class plgVmPaymentPoints2vendor extends vmCustomPlugin
{
	public function plgVmOnUpdateOrderPayment(  $_formData)
	{
		
		$use_aup = 0 ;
		$db 	= JFactory::getDBO();
		$lang 	= JFactory::getLanguage();
		$app 	= JFactory::getApplication();
		$language_tag = $lang->get('tag');
		$reload = true;
		$lang->load( 'com_vmvendor', JPATH_SITE, $language_tag, $reload);
		
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
		vpc.`virtuemart_category_id` ,
		u.name, u.email
		
		
		FROM `#__virtuemart_order_items` voi 
		LEFT JOIN `#__virtuemart_products` vp ON vp.`virtuemart_product_id` = voi.`virtuemart_product_id` 
		LEFT JOIN `#__virtuemart_product_categories` vpc ON vpc.`virtuemart_product_id` = voi.`virtuemart_product_id` 
		LEFT JOIN #__virtuemart_vendors vv ON vv.virtuemart_vendor_id = vp.`virtuemart_vendor_id` 
		LEFT JOIN #__users u ON vv.created_by = u.id 
		WHERE `virtuemart_order_id` = '".$virtuemart_order_id."' ";  //echo ok
		$db->setQuery($q);
		$items = $db->loadObjectList();
			
			
			
	 	if ($_formData->order_status == "C")
		{
			foreach ($items as $item)
			{
				$virtuemart_order_item_id	= $item->virtuemart_order_item_id;
				$virtuemart_product_id		= $item->virtuemart_product_id;
				$virtuemart_category_id 	= $item->virtuemart_category_id;


				if($forbidcatids!='' && (in_array($virtuemart_category_id  , $banned_cats) OR $virtuemart_category_id==$forbidcatids) )
					return false;
				if($onlycatids!='' && (!in_array($virtuemart_category_id  , $prefered_cats) OR $virtuemart_category_id==$onlycatids) )
					return false;
				
				
				 
				$product_item_price 		= $item->product_item_price;
				$product_final_price 		= $item->product_final_price;
				$tax_topay					= $product_final_price - $product_item_price;
				$product_quantity 			= $item->product_quantity;
				$order_item_name			= $item->order_item_name;
				$order_item_sku				= $item->order_item_sku;
				$virtuemart_vendor_id		= $item->virtuemart_vendor_id;
				$product_url = 'index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$virtuemart_product_id.'&virtuemart_category_id='.$virtuemart_category_id.'&Itemid='.$vmitemid;
									
				$total_price = $product_item_price * $product_quantity;

				$q = "SELECT `virtuemart_user_id` FROM `#__virtuemart_vmusers` WHERE `virtuemart_vendor_id`='".$virtuemart_vendor_id."' ";
				$db->setQuery($q);
				$vendor_user_id = $db->loadResult();
					
				require_once JPATH_SITE.'/components/com_vmvendor/helpers/getvendorplan.php';
				$vendor_plan = VmvendorHelper::getvendorplan( $vendor_user_id );
				if($vendor_plan->commission_pct!='')
					$commission = $vendor_plan->commission_pct;
											
				$aup_topay = ( ( $total_price * (100 - $commission) / 100 ) * $aup_ratio ) + $tax_topay;
				$ratio = 1 / $aup_ratio;
								
				$res = number_format((float)$total_price,$currency_decimal_place,$currency_decimal_symbol,$currency_thousands);
				$search 	= array('{sign}', '{number}', '{symbol}');
				$replace 	= array('+', $res, $currency_symbol);
				$formattedRounded_price = str_replace ($search,$replace,$currency_positive_style);
										
				$res = number_format((float)$ratio,$currency_decimal_place,$currency_decimal_symbol,$currency_thousands);
				$search = array( '{sign}' , '{number}', '{symbol}');
				$replace = array( '', $res, $currency_symbol);
				$formattedRounded_ratio = str_replace ($search , $replace , $currency_positive_style);
														
					
				$informationdata = JText::_('VMPAYMENT_PTS2VENDOR_INFODATA1').':<br />';
				$informationdata .=	$product_quantity.' * <a href="'.$product_url.'">'.$order_item_name.'</a> = '.$formattedRounded_price.'<br />';
				$informationdata .=	'- '.$commission.'% '.JText::_('VMPAYMENT_PTS2VENDOR_INFODATA2').' = '.$aup_topay. JText::_('VMPAYMENT_PTS2VENDOR_PTS') .'<br />';
				$informationdata .=	JText::_('VMPAYMENT_PTS2VENDOR_REMINDER').'1'.JText::_('VMPAYMENT_PTS2VENDOR_PT').' = '. $formattedRounded_ratio ;					
										
				$referencekey = $virtuemart_order_item_id.'|OderItemID';
				////////////// Payment via AUP API//////////////////////
				if($use_aup)
				{
					$api_AUP = JPATH_SITE.'/components/com_alphauserpoints/helper.php';
					if ( file_exists($api_AUP))
					{
						require_once ($api_AUP);
						$aupid = AlphaUserPointsHelper::getAnyUserReferreID( $vendor_user_id );
						if ( $aupid )
						{
							AlphaUserPointsHelper::newpoints( 'plgaup_vm_points2vendor', $aupid, $referencekey , $informationdata , $aup_topay );
								//$app->enqueueMessage('success: '.$informationdata);	
												
						}	
					}
				}
				else // vmvendor userpoints
				{
						// insert point event
					$q = "INSERT INTO #__vmvendor_userpoints_details 
					(userid, points, insert_date, status, approved , keyreference , datareference )
					VALUES 
					('".$vendor_user_id."','".$aup_topay."','".date('Y-m-d H:i:s')."','1','1','".$referencekey."','".$informationdata."' )";
					$db->setQuery( $q );
					if (!$db->query()) die($db->stderr(true));
					// update user userpoints total or create
					$q = "SELECT COUNT(*) FROM #__vmvendor_userpoints WHERE userid='".$vendor_user_id."' ";
					$db->setQuery( $q );
					$exists = $db->loadResult( $q );
					if($exists==0)
					{
						$q = "INSERT INTO #__vmvendor_userpoints 
						( userid , points) VALUES ('".$vendor_user_id."' , '".$aup_topay."' ) ";
						$db->setQuery( $q );
						if (!$db->query()) die($db->stderr(true));
					}
					else
					{
						$q ="UPDATE #__vmvendor_userpoints SET points = points + '".$aup_topay."' ";
						$db->setQuery( $q );
						if (!$db->query()) die($db->stderr(true));	
					}
				}
					
					
					
					
					/////// Email notification to vendor.
				$message 	= JFactory::getMailer(); 
				$config 	= JFactory::getConfig();
					
				$mailfrom 	= $config->get( 'config.mailfrom' );
				$fromname 	= $config->get( 'config.fromname' );
				$subject 	= ucwords($item->name).', '.JText::_('VMPAYMENT_PTS2VENDOR_ORDERNOTIFICATIONMAIL_SUBJECT');					
				$emailto	= $item->email;

					
					
					
				$orderitem_detail = $product_quantity .' * '.$order_item_name.' '.$product_item_price;
				$dashoboard_url = 'http://'.$_SERVER["SERVER_NAME"].JRoute::_('index.php?option=com_vmvendor&view=dashboard');
				$dashoboard_url = str_replace('administrator/','',$dashoboard_url);
					
				$body = JText::_('VMPAYMENT_PTS2VENDOR_ORDERNOTIFICATIONMAIL_INTRO').",\r\n\r\n";
				$body .= $orderitem_detail.",\r\n\r\n";
				$body .= JText::_('VMPAYMENT_PTS2VENDOR_ORDERNOTIFICATIONMAIL_OUTTRO').",\r\n\r\n";
				$body .= $dashoboard_url;

					
				$message->addRecipient($emailto); 
				$message->addBCC( $mailfrom );

				$message->setSubject($subject);
				$message->setBody($body);
				$sender = array( $mailfrom, $fromname );
				$message->setSender($sender);
				$sent = $message->send();


				if($sales_activitystream=0 >0 )
				{   // sales announcement in Jomsocial Activity Stream
					if($profileman=='js')
					{
						//echo $_formData->order_status;
						$jspath = JPATH_ROOT .  '/components/com_community';
						include_once($jspath.  '/libraries/core.php');//activity stream  - added a blog
						CFactory::load('libraries', 'activities');          
						$act = new stdClass();
						$act->cmd    = 'wall.write';
									
						if($sales_activitystream==1)
							$act->actor    = $vendor_user_id;
						elseif($sales_activitystream==2)
							$act->actor    = '';	
						$act->target    = 0; // no target
						$act->title    = JText::_('COM_VMVENDOR_JOMSOCIAL_HASJUSTSOLD')." <a href='".$product_url."'>".stripslashes( ucfirst($order_item_name) )."</a>" ;
						$output = '';
									
						$act->content    = $output;
						$act->app    = 'vmvendor';
						$act->cid    = 0;
						$act->comment_id	= CActivities::COMMENT_SELF;
						$act->comment_type	= 'vmvendor.productsale';
						$act->like_id		= CActivities::LIKE_SELF;		
						$act->like_type		= 'vmvendor.productsale';
						CActivityStream::add($act);
					}
				}		
			}					
		}
		
		
		if ( $_formData->order_status == 'X' || $_formData->order_status == 'R' )
		{  // cancelled or refund
			$q = "SELECT `virtuemart_user_id` FROM `#__virtuemart_vmusers` WHERE `virtuemart_vendor_id`='".$virtuemart_vendor_id."' ";
			$db->setQuery($q);
			$vendor_user_id = $db->loadResult();
					
			require_once JPATH_SITE.'/components/com_vmvendor/helpers/getvendorplan.php';
			$vendor_plan = VmvendorHelper::getvendorplan( $vendor_user_id );
			if($vendor_plan->commission_pct!='')
				$commission = $vendor_plan->commission_pct;
						
						
			foreach ($items as $item)
			{
				
				$virtuemart_order_item_id	= $item->virtuemart_order_item_id;
				$virtuemart_product_id		= $item->virtuemart_product_id;
				$virtuemart_category_id 	= $item->virtuemart_category_id;
				$product_quantity 			= $item->product_quantity;
				$order_item_name			= $item->order_item_name;
				$product_final_price 		= $item->product_final_price;
				
				
				
				$paid_keyreference = $virtuemart_order_item_id .'|OderItemID';
				if($use_aup)
				{
					$q = "SELECT referreid , points FROM #__alpha_userpoints_details WHERE keyreference='".$paid_keyreference."' ";
					$db->setQuery($q);
					$aup_data = $db->loadRow();
					$referreid = $aup_data[0];
					$points2revert = -$aup_data[1];
				}
				else
				{
					$q = "SELECT userid , points FROM #__vmvendor_userpoints_details WHERE keyreference='".$paid_keyreference."' ";
					$db->setQuery($q);
					$aup_data = $db->loadRow();
					$vendor_user_id = $data[0];
					$points2revert = -$data[1];
					
				}
				if($points2revert<0)
				{
					$app->enqueueMessage('Refund Vendor points! '.$points2revert);
					$keyreference =  $virtuemart_order_item_id. "|CanceledVendorPoints" ;
					$informationdata= JText::_('COM_VMVENDOR_EMAIL_STATUSCANCELED_INFODATA').' '.$saleordernumber.'.  '.$product_quantity.' x '.$order_item_name .': ('.$product_quantity.'x'.$product_final_price.')x'.$commission.'%='.$points2revert.' '.JText::_('VMPAYMENT_PTS2VENDOR_PTS');
							
							
					if($use_aup){
						$api_AUP = JPATH_SITE.'/components/com_alphauserpoints/helper.php';
						if ( file_exists($api_AUP))
						{
							require_once ($api_AUP);
							
							AlphaUserPointsHelper::newpoints( 
								'plgaup_cancel_vendorpoints', 
								$referreid ,
								$keyreference,  
								$informationdata,
								$points2revert
							);
						}
					}
					else
					{
						// insert point event
						$q = "INSERT INTO #__vmvendor_userpoints_details 
						(userid, points, insert_date, status, approved , keyreference , datareference )
						VALUES 
						('".$vendor_user_id."','".$points2revert."','".date('Y-m-d H:i:s')."','1','1','".$keyreference."','".$informationdata."' )";
						$db->setQuery( $q );
						if (!$db->query()) die($db->stderr(true));
						// update user userpoints total or create
						$q ="UPDATE #__vmvendor_userpoints SET points = points + '".$points2revert."' ";
						$db->setQuery( $q );
						if (!$db->query()) die($db->stderr(true));	
						
					}
				}
			}
		} 
	}
}