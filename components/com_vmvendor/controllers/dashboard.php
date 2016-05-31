<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2015 Adrien Roussel Nordmograph.com
 * @license : GNU/GPL v3
 * @Website : http://www.nordmograph.com/extensions
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

/**
 * @package AlphaUserPoints
 */
class VmvendorControllerDashboard extends VmvendorController
{
	/**
	 * Custom Constructor
	 */
 	function __construct()	{
		parent::__construct( );
	}
	
	public function edittax()
	{
		$app 					= JFactory::getApplication();
		$user 					=  JFactory::getUser();
		$db							= JFactory::getDBO();	
		$model      				= $this->getModel ( 'edittax' );
		$view       				= $this->getView  ( 'edittax','html' );
		$cparams 					= JComponentHelper::getParams('com_vmvendor');
		$tax_mode 					= $cparams->get('tax_mode',0);
		
		$tax_id							=	$app->input->post->get('calc_id', '', 'INT' );
		$tax_name						=	$app->input->post->get('calc_name' );
		$tax_descr						=	$app->input->post->get('calc_descr');
		$tax_mathop						=	$app->input->post->get('calc_mathop');
		$tax_value						=	$app->input->post->get('calc_value' );
		$tax_currency					=	$app->input->post->get('calc_currency');
		$tax_vendor_id					=	$app->input->post->get('calc_vendor_id', '' , 'INT');
		$tax_cats						=	$app->input->post->get('taxproductcats');
		$tax_shoppergroups				=	explode( ',', $app->input->post->get('calc_shoppergroups') );
		$message .= 'count tax_cats: '.count($tax_cats);
		$message .= '<br />count tax_shoppergroups: '.count($tax_shoppergroups);
		
		 $q = "UPDATE `#__virtuemart_calcs` SET 
		`virtuemart_vendor_id` ='".$tax_vendor_id."' , 
		`calc_name` ='".$db->escape($tax_name)."' , 
		`calc_descr` = '".$db->escape($tax_descr)."' ,
		`calc_value_mathop` = '".$tax_mathop."' , 
		`calc_value` = '".$db->escape($tax_value)."' ,
		`calc_currency` ='".$tax_currency."' , 
		`publish_down` = '0000-00-00 00:00:00' , 
		`shared` ='0' ,
		`modified_on` ='".date('Y-m-d H:i:s')."' ,
		`modified_by`='".$user->id."' 
		WHERE `virtuemart_calc_id` ='".$tax_id."' AND `published`='1' AND `shared`='0' AND `calc_kind` ='VatTax' AND (`created_by`='0' OR `created_by`='".$user->id."') ";
		$db->setQuery($q);
		if (!$db->query()) die($db->stderr(true));
		// categories
		// check if any cat is being removed, we check in the DB if any entry is not in the cats array and if so, we delete these
		$q ="SELECT `virtuemart_category_id` FROM `#__virtuemart_calc_categories` WHERE `virtuemart_calc_id` ='".$tax_id."' ";
		$db->setQuery($q);
		$cat_ids = $db->loadObjectList();
		foreach($cat_ids as $cat_id){
			if( !in_array( $cat_id->virtuemart_category_id , $tax_cats ) ){
				$message .= '<br />' . $q =" DELETE FROM `#__virtuemart_calc_categories`  WHERE `virtuemart_calc_id` ='".$tax_id."' AND `virtuemart_category_id` ='".$cat_id->virtuemart_category_id."' ";	
				$db->setQuery($q);
				if (!$db->query()) die($db->stderr(true));
			}
		}
		for($i = 0; $i <= count($tax_cats) ; $i++ ){
			$q ="SELECT COUNT(*) FROM `#__virtuemart_calc_categories`  WHERE `virtuemart_calc_id` ='".$tax_id."' AND `virtuemart_category_id`='".$tax_cats[$i]."' ";
			$db->setQuery($q);
			$is_cat_yet = $db->loadResult();
			if ($is_cat_yet > 0){  // it's allready in , do nothing.
			}
			elseif($tax_cats[$i] !='' ){ // we add the cat
				
					$message .= '<br />' .  $q ="INSERT INTO `#__virtuemart_calc_categories` 
				( `virtuemart_calc_id` , `virtuemart_category_id` )
				VALUES
				('".$tax_id."' , '".$tax_cats[$i]."' )";
					$db->setQuery($q);
					if (!$db->query()) die($db->stderr(true));
				
			}	
		}
		
		// shoppergroups
		// check if any shoppergroup is being removed, we check in the DB if any entry is not in the cats array and if so, we delete these
		$q ="SELECT `virtuemart_shoppergroup_id` FROM `#__virtuemart_calc_shoppergroups` WHERE `virtuemart_calc_id` ='".$tax_id."' ";
		$db->setQuery($q);
		$shoppergroup_ids = $db->loadObjectList();
		foreach($shoppergroup_ids as $shoppergroup_id){
			if( !in_array( $shoppergroup_id->virtuemart_shoppergroup_id , $tax_shoppergroups ) ){
				$message .= '<br />' . $q =" DELETE FROM `#__virtuemart_calc_shoppergroups`  WHERE `virtuemart_calc_id` ='".$tax_id."' AND `virtuemart_shoppergroup_id` ='".$shoppergroup_id->virtuemart_shoppergroup_id."' ";	
				$db->setQuery($q);
				if (!$db->query()) die($db->stderr(true));
			}
		}
		for($j = 0; $j <= count($tax_shoppergroups) ; $j++ ){
			$message .= '<br />' .$q ="SELECT COUNT(*) FROM `#__virtuemart_calc_shoppergroups`  WHERE `virtuemart_calc_id` ='".$tax_id."' AND `virtuemart_shoppergroup_id`='".$tax_shoppergroups[$j]."' ";
			$db->setQuery($q);
			$is_shoppergroup_yet = $db->loadResult();
			if ($is_shoppergroup_yet > 0){  // it's allready in , do nothing.
			}
			elseif($tax_shoppergroups[$j] !='' ){ // we add the cat
				
					$message .= '<br />' .  $q ="INSERT INTO `#__virtuemart_calc_shoppergroups` 
				( `virtuemart_calc_id` , `virtuemart_shoppergroup_id` )
				VALUES
				('".$tax_id."' , '".$tax_shoppergroups[$j]."' )";
					$db->setQuery($q);
					if (!$db->query()) die($db->stderr(true));
				
			}	
		}
		
		
		$message .= '<i class="vmv-icon-ok"></i> '.JText::_( 'COM_VMVENDOR_DASHBOARD_TAXEDITED_SUCCESS' );
		$app->enqueueMessage( $message );
		$app->redirect('index.php?option=com_vmvendor&view=dashboard&Itemid='.$app->input->get('Itemid'));
	}
	public function deletetax()
	{
		$app 					= JFactory::getApplication();
		$user 					=  JFactory::getUser();
		$db							= JFactory::getDBO();	
		$model      				= $this->getModel ( 'dashboard' );
		$view       				= $this->getView  ( 'dashboard','html' );
		$cparams 					= JComponentHelper::getParams('com_vmvendor');
		$tax_mode 			= $cparams->get('tax_mode',0);
		
		$tax_id							=	$app->input->post->getInt('delete_taxid');
		$tax_userid						=	$app->input->post->getInt('userid');
		
		if($tax_userid = $user->id && $tax_mode==2){  // user is tax owner and tax management is enabled
			$q = "DELETE FROM `#__virtuemart_calcs` WHERE `virtuemart_calc_id` ='".$tax_id."' ";
			$db->setQuery($q);
			if (!$db->query()) die($db->stderr(true));
			
			$q = "DELETE FROM `#__virtuemart_calc_categories` WHERE `virtuemart_calc_id` ='".$tax_id."' ";
			$db->setQuery($q);
			if (!$db->query()) die($db->stderr(true));
			
			$q = "DELETE FROM `#__virtuemart_calc_countries` WHERE `virtuemart_calc_id` ='".$tax_id."' ";
			$db->setQuery($q);
			if (!$db->query()) die($db->stderr(true));
			
			$q = "DELETE FROM `#__virtuemart_calc_shoppergroups` WHERE `virtuemart_calc_id` ='".$tax_id."' ";
			$db->setQuery($q);
			if (!$db->query()) die($db->stderr(true));
			
			$q = "DELETE FROM `#__virtuemart_calc_states` WHERE `virtuemart_calc_id` ='".$tax_id."' ";
			$db->setQuery($q);
			if (!$db->query()) die($db->stderr(true));
			
			$message .= '<i class="vmv-icon-ok"></i> '.JText::_( 'COM_VMVENDOR_DASHBOARD_TAXDELETED_SUCCESS' );
			$app->enqueueMessage( $message );
			$app->redirect('index.php?option=com_vmvendor&view=dashboard&Itemid='.$app->input->get('Itemid'));
		}	
	}
	
	function updateorderstatus(){
		$app 					= JFactory::getApplication();
		$user 					=  JFactory::getUser();
		$db							= JFactory::getDBO();	
		$model      				= $this->getModel ( 'dashboard' );
		$view       				= $this->getView  ( 'dashboard','html' );
		$cparams 					= JComponentHelper::getParams('com_vmvendor');
		$allow_orderstatuschange 	= $cparams->get('allow_orderstatuschange',1);
		$update_notifies_admin		= $cparams->get('update_notifies_admin',1);
		$update_notifies_customer	= $cparams->get('update_notifies_customer',1);
		
		$saleordernumber			= $app->input->post->get('saleordernumber');		
		$orderitemid				= $app->input->post->get('orderitemid', '' , 'INT');
		$neworderstatus				= $app->input->post->get('neworderstatus' );
		if($allow_orderstatuschange && $user->id !='0' ){
			$q = "UPDATE `#__virtuemart_order_items` SET `order_status`='".$neworderstatus."' WHERE `virtuemart_order_item_id`='".$orderitemid."' ";
			$db->setQuery($q);
			if (!$db->query()) 
				die($db->stderr(true));
			else{
				$message = '<i class="vmv-icon-ok"></i> '.JText::_( 'COM_VMVENDOR_DASHBOARD_STATUSUPDATED' );
				$app->enqueueMessage( $message );
				
				if ( $neworderstatus=='X'){ // we cancel the AUP payment.
					if($use_aup = 0)
					{
						$q = "SELECT `id` FROM `#__alpha_userpoints_rules` WHERE plugin_function='plgaup_vm_points2vendor' " ;
						$db->setQuery( $q );
						$ruleid = $db->loadResult();
					
						$q = "SELECT `referreid` , `points` FROM `#__alpha_userpoints_details` WHERE `keyreference` ='".$orderitemid."|OderItemID' AND `rule`='".$ruleid."' ";
						$db->setQuery( $q );
						$pointsdata = $db->loadRow();
						$referreid = $pointsdata[0];
						$points2deduct = $pointsdata[1];
						$referencekey = $orderitemid. "|CanceledVendorPoints";
						$informationdata= JText::_('COM_VMVENDOR_EMAIL_STATUSCANCELED_INFODATA').' '.$saleordernumber;
						$api_AUP = JPATH_SITE.'/components/com_alphauserpoints/helper.php';
						if ( file_exists($api_AUP))
						{
							require_once ($api_AUP);
							AlphaUserPointsHelper::newpoints( 'plgaup_cancel_vendorpoints',$referreid, $referencekey , $informationdata , -$points2deduct );
						} 
					
					}
					else
					{
						$q = "SELECT `points` FROM `#__vmvendor_userpoints_details` WHERE `keyreference` ='".$orderitemid."|OderItemID' AND `userid`='".$user->id."' ";
						$db->setQuery( $q );
						$points2deduct = $db->loadResult();
						$referencekey = $orderitemid. "|CanceledVendorPoints";
						$informationdata= JText::_('COM_VMVENDOR_EMAIL_STATUSCANCELED_INFODATA').' '.$saleordernumber;
						$api_AUP = JPATH_SITE.'/components/com_alphauserpoints/helper.php';
						$q = "SELECT COUNT(id) FROM #__vmvendor_userpoints_details WHERE keyreference ='".$referencekey."' AND userid='".$user->id."' ";
						$db->setQuery($q);
						$yetrefunded = $db->loadResult();
						if(!$yetrefunded)
						{
							$q = "INSERT INTO #__vmvendor_userpoints_details 
							(userid , points, insert_date, status, approved , keyreference, datareference)
							VALUES 
							('".$user->id."','-".$points2deduct."','".date('Y-m-d H:i:s')."','1','1','".$referencekey."','".$informationdata."') ";
							$db->setQuery($q);
							if (!$db->query()) die($db->stderr(true));
							$q = "UPDATE #__vmvendor_userpoints SET points = points - ".$points2deduct." WHERE userid='".$user->id."' ";
							$db->setQuery($q);
						}
					}
				}
				
				
				
				if ($update_notifies_admin OR $update_notifies_customer OR $neworderstatus=='X'){  // if status is 'canceled', notify admin even if notifications disabled
				
				
					// email admin	
					$mailer = JFactory::getMailer();
					$config = JFactory::getConfig();
					$sender = array( 
						$config->get( 'config.mailfrom' ),
						$config->get( 'config.fromname' )
					);
					$mailer->setSender( $sender );
					if($update_notifies_admin OR $neworderstatus=='X')
						$mailer->addRecipient( $config->get( 'config.mailfrom' ) );
					if($to!='')
						$mailer->addRecipient( $to );
					if($update_notifies_customer){
						$q = "SELECT u.`email`
						FROM `#__users` u 
						JOIN `#__virtuemart_orders` vo ON u.`id` = vo.`virtuemart_user_id` 
						JOIN `#__virtuemart_order_items` voi ON vo.`virtuemart_order_id` = voi.`virtuemart_order_id` 
						WHERE voi.`virtuemart_order_item_id` = '".$orderitemid."' ";
						$db->setQuery($q);
						$customer_email = $db->loadResult();
						$mailer->addRecipient( $customer_email );
					}
					$subject = JText::_('COM_VMVENDOR_EMAIL_STATUSUPDATE_SUBJECT');
					
					$body = ucfirst($user->username).' '.JText::_('COM_VMVENDOR_EMAIL_STATUSUPDATE_BODY').' '.$neworderstatus.'. ';	
					$body .= JText::_('COM_VMVENDOR_EMAIL_STATUSUPDATE_ORDERID').': '.$saleordernumber;	

					
					$mailerror = '<i class="vmv-icon-cancel"></i> <font color="red"><b>'.JText::_('COM_VMVENDOR_EMAIL_FAIL').'</b></font>';		
					
					$mailer->setSubject( $subject );
					$mailer->isHTML(true);
					$mailer->Encoding = 'base64';
					$mailer->setBody($body);
					
					$send = $mailer->Send();
					if ($send != 1) 
						echo  $mailerror;
				}	
			}
		}
		$app->redirect('index.php?option=com_vmvendor&view=dashboard&Itemid='.$app->input->get('Itemid') );
	}
	
	function publishreview(){
		$app 					= JFactory::getApplication();
		$user 					=  JFactory::getUser();
		$db							= JFactory::getDBO();	
		$model      				= $this->getModel ( 'dashboard' );
		$view       				= $this->getView  ( 'dashboard','html' );
		$cparams 					= JComponentHelper::getParams('com_vmvendor');
		$manage_reviews 			= $cparams->get('manage_reviews',1);
		
		$review_id					= $app->input->post->get('review_id', '' , 'INT');
		if($manage_reviews){
			$q ="UPDATE `#__virtuemart_ratings` SET `published` ='1' WHERE `virtuemart_rating_id`='".$review_id."' ";
			$db->setQuery($q);
			if (!$db->query()) die($db->stderr(true));
			$q ="UPDATE `#__virtuemart_rating_reviews` SET `published` ='1' WHERE `virtuemart_rating_review_id`='".$review_id."' ";
			$db->setQuery($q);
			if (!$db->query()) die($db->stderr(true));
			
			$q = "SELECT `virtuemart_product_id` FROM  `#__virtuemart_rating_reviews` WHERE `virtuemart_rating_review_id`='".$review_id."' ";
			$db->setQuery($q);
			$virtuemart_product_id = $db->loadResult();
			// recount ratings
			$q = "SELECT review_rates  
			 FROM `#__virtuemart_rating_reviews`  
			 WHERE published='1' AND virtuemart_product_id = '".$virtuemart_product_id."' ";
			 $db->setQuery($q);
			 $published_reviews_ratings = $db->loadObjectList();
			 $published_review_total = 0;
			 $published_review_count = 0;
			 foreach($published_reviews_ratings as $published_reviews_rating){
				 $published_review_total = $published_review_total + $published_reviews_rating->review_rates;
				 $published_review_count++;
			 }
			 $q= "UPDATE `#__virtuemart_ratings`  SET rates='".$published_review_total."' , ratingcount	='".$published_review_count."' , rating='".($published_review_total/$published_review_count)."' 
			 WHERE virtuemart_product_id='".$virtuemart_product_id."' ";
			 $db->setQuery($q);
			if (!$db->query()) die($db->stderr(true));
			 
			 $message = '<i class="vmv-icon-ok"></i> '.JText::_( 'COM_VMVENDOR_DASHBOARD_REVIEWS_PUBLISHEDSUCCESS' );
			$app->enqueueMessage( $message );
		}
		$app->redirect('index.php?option=com_vmvendor&view=dashboard&Itemid='.$app->input->get('Itemid') );
	}
	function deletereview()
	{
		$app 					= JFactory::getApplication();
		$user 					=  JFactory::getUser();
		$db							= JFactory::getDBO();	
		$model      				= $this->getModel ( 'dashboard' );
		$view       				= $this->getView  ( 'dashboard','html' );
		$cparams 					= JComponentHelper::getParams('com_vmvendor');
		$manage_reviews 			= $cparams->get('manage_reviews',1);
		
		$review_id					= $app->input->post->get('review_id', '' , 'INT');
		if($manage_reviews){
			$q = "SELECT `virtuemart_product_id` FROM  `#__virtuemart_rating_reviews` WHERE `virtuemart_rating_review_id`='".$review_id."' ";
			$db->setQuery($q);
			$virtuemart_product_id = $db->loadResult();
			
			
			
			$q = "DELETE FROM `#__virtuemart_rating_reviews` WHERE `virtuemart_rating_review_id`='".$review_id."' ";
			$db->setQuery($q);
			if (!$db->query()) die($db->stderr(true));
			
			$q = "DELETE FROM `#__virtuemart_rating_votes` WHERE `virtuemart_rating_vote_id`='".$review_id."' ";
			$db->setQuery($q);
			if (!$db->query()) die($db->stderr(true));
			
			
			// recount ratings
			$qc = "SELECT review_rates , published  
			 FROM `#__virtuemart_rating_reviews`  
			 WHERE virtuemart_product_id = '".$virtuemart_product_id."' ";
			 $db->setQuery($qc);
			 $reviews_ratings = $db->loadObjectList();
			 $published_review_total = 0;
			 $published_review_count = 0;
			 $unpublished_review_count= 0;
			 foreach($reviews_ratings as $reviews_rating){
				 if($reviews_rating->published){
				 	$published_review_total = $published_review_total + $reviews_rating->review_rates;
				 	$published_review_count++;
				 }
				 else
				 	$unpublished_review_count++;
			 }
			 if($published_review_count + $unpublished_review_count <1){
				 $q = "DELETE FROM `#__virtuemart_ratings` WHERE `virtuemart_product_id`='".$virtuemart_product_id."' ";
				$db->setQuery($q);
				if (!$db->query()) die($db->stderr(true));
			 }
			 else{		 	
			 	$q= "UPDATE `#__virtuemart_ratings`  SET rates='".$published_review_total."' , ratingcount	='".$published_review_count."' , rating='".($published_review_total/$published_review_count)."' 
			 	WHERE virtuemart_product_id='".$virtuemart_product_id."' ";
			 	$db->setQuery($q);
				if (!$db->query()) die($db->stderr(true));
			 }
			
			$message = '<i class="vmv-icon-ok"></i> '.JText::_( 'COM_VMVENDOR_DASHBOARD_REVIEWS_DELETEDSUCCESS' );
			$app->enqueueMessage( $message );
		}
		$app->redirect('index.php?option=com_vmvendor&view=dashboard&Itemid='.$app->input->get('Itemid') );
	}

	public function deleteshipment()
	{
		$app 			= JFactory::getApplication();
		$user 			=  JFactory::getUser();
		$db				= JFactory::getDBO();	
		$model      	= $this->getModel ( 'dashboard' );
		$view       	= $this->getView  ( 'dashboard','html' );
		$cparams 		= JComponentHelper::getParams('com_vmvendor');
		$shipment_mode 	= $cparams->get('shipment_mode',0);
		$shipment_id	= $app->input->post->get('delete_shipmentid', '' , 'INT');
		$shipment_userid= $app->input->post->get('userid', '' , 'INT');

		if (!class_exists( 'VmConfig' ))
			require JPATH_ADMINISTRATOR .  '/components/com_virtuemart/helpers/config.php';
		VmConfig::loadConfig();
		$multilang_mode 		= $cparams->get('multilang_mode', 0);
		if($multilang_mode >0)
		{
			$active_languages	=	VmConfig::get( 'active_languages' ); //en-GB
		}
		
		if($shipment_userid = $user->id && $shipment_mode)
		{  // user is tax owner and tax management is enabled
			$q = "DELETE FROM `#__virtuemart_shipmentmethods` 
			WHERE `virtuemart_shipmentmethod_id` ='".$shipment_id."' ";
			$db->setQuery($q);
			if (!$db->query()) die($db->stderr(true));

			$q = "DELETE FROM `#__virtuemart_shipmentmethods_".VMLANG."` 
			WHERE `virtuemart_shipmentmethod_id` ='".$shipment_id."' ";
			$db->setQuery($q);
			if (!$db->query()) die($db->stderr(true));

			$q = "DELETE FROM `#__virtuemart_shipmentmethod_shoppergroups` 
			WHERE `virtuemart_shipmentmethod_id` ='".$shipment_id."' ";
			$db->setQuery($q);
			if (!$db->query()) die($db->stderr(true));

			if($multilang_mode >0)
			{ 					
				for($i = 0 ; $i < count( $active_languages ) ; $i++)
				{
					//$app->enqueueMessage($active_languages[$i]); //en-GB
					if( str_replace('_' , '-' , VMLANG) != strtolower( $active_languages[$i]) )
					{
						$q = "DELETE FROM `#__virtuemart_shipmentmethods_".strtolower( str_replace('-' , '_' , $active_languages[$i]) ) ."` 
						WHERE `virtuemart_shipmentmethod_id`='".$delete_shipmentid."' ";
						$db->setQuery($q);
						if (!$db->query()) die($db->stderr(true));
					}
				}
			}
			$message = '<i class="vmv-icon-ok"></i> '.JText::_( 'COM_VMVENDOR_DASHBOARD_SHIPMENT_DELETED_SUCCESS' );
			$app->enqueueMessage( $message );
			$app->redirect('index.php?option=com_vmvendor&view=dashboard&Itemid='.$app->input->get('Itemid'));
		}	
	}
	
	



	
}
?>