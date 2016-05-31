<?php
/*
 * @component com_vmvendor
 * @copyright Copyright (C) 2010-2015 Adrien ROUSSEL Nordmograph.com
 * @license GNU/GPL Version 3
 * @Website : http://www.nordmograph.com/extensions
 */

defined('_JEXEC') or die;
//abstract class VmvendorHelper
abstract class VmvendorFunctions
{
	static function xml_entities($string) {
    return strtr(
        $string, 
        array(
            "<" => "&lt;",
            ">" => "&gt;",
            '"' => "&quot;",
            "'" => "&apos;",
            "&" => "&amp;",
        )
    );
}


	static function updateRSS( $state , $virtuemart_vendor_id )
	{
		if (!class_exists( 'VmConfig' ))
			require(JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php');
		VmConfig::loadConfig();
		
		$cparams 				= JComponentHelper::getParams('com_vmvendor');
		$rsslimit				= $cparams->get('rsslimit');
		$vmitemid				= $cparams->get('vmitemid');
		$juri 					= JURI::base();	
		$user 					= JFactory::getUser();
		$doc 					= JFactory::getDocument();
		$db						= JFactory::getDBO();
		$app					= JFactory::getApplication();
		if(!file_exists(JPATH_BASE."/media/vmvendorss"))
			mkdir(JPATH_BASE."/media/vmvendorss", 0777);	
		$xml_path = JPATH_BASE.'/media/vmvendorss/'.$user->id.'.rss';				
		$q = "SELECT vm.file_title , vm.file_url_thumb FROM #__virtuemart_medias vm 
			LEFT JOIN #__virtuemart_vendor_medias vvm ON vvm.virtuemart_media_id = vm.virtuemart_media_id 
			WHERE vvm.virtuemart_vendor_id='".$virtuemart_vendor_id."'";
			$db->setQuery($q);
			$row = $db->loadRow();
			$vfile = $row[0];
			$vthumb = $row[1];
			if($vthumb!='')
				$feed_img = $juri.$vthumb;
			elseif($vfile!='')
			{
				$product_thumb = str_replace('virtuemart/product/','virtuemart/product/resized/',$vfile);		
				$thum_side_width			=	VmConfig::get( 'img_width' );
				$thum_side_height			=	VmConfig::get( 'img_height' );
				$extension_pos = strrpos($product_thumb, ".");
				$product_thumb = substr($product_thumb, 0, $extension_pos) . '_'.$thum_side_width.'x'.$thum_side_height . substr($product_thumb, $extension_pos);
				$feed_img = $juri.$product_thumb;
			}
			else
				$feed_img = $juri.'components/com_vmvendor/assets/img/noimage.gif';
			$profile_url =JRoute::_('index.php?option=com_vmvendor&view=vendorprofile&userid='.$user->id);				
			$rss_xml = '<?xml version="1.0" encoding="utf-8" ?>';
			$rss_xml .='<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom">';
			$rss_xml .='<channel>';
			$rss_xml .='<atom:link href="'.$juri.'media/vmvendorss/'.$user->id.'.rss" rel="self" type="application/rss+xml" />';
			$rss_xml .='<title>'.VmvendorFunctions::xml_entities(ucfirst($user->username).' - '.JText::_('COM_VMVENDOR_RSS_CAT')).'</title>';
			$rss_xml .='<link>http://'.$_SERVER["SERVER_NAME"].$profile_url.'</link>';
			$rss_xml .='<description>'.JText::_('COM_VMVENDOR_RSS_DESC').' '.$juri.'</description>';
			$rss_xml .='<generator>VMVendor user catalogue RSS feed for Virtuemart and Joomla</generator>';
			$rss_xml .='<lastBuildDate>'.date("D, d M Y H:i:s O").'</lastBuildDate>';
			$rss_xml .='<language>'.substr($doc->getLanguage(),0,2).'</language>';
			$rss_xml .='<image>';
			$rss_xml .='<url>'.$feed_img.'</url>';
			$rss_xml .='<title>'.VmvendorFunctions::xml_entities(ucfirst($user->username).' - '.JText::_('COM_VMVENDOR_RSS_CAT')).'</title>';
			$rss_xml .='<link>http://'.$_SERVER["SERVER_NAME"].$profile_url.'</link>';
			$rss_xml .='</image>';
			$q = "SELECT p.`virtuemart_product_id` , p.`virtuemart_vendor_id` ,p.`created_on` , 
			pl.`product_s_desc` , pl.`product_name` , 
			vu.`virtuemart_user_id` , 
			vc.`virtuemart_category_id`
			FROM `#__virtuemart_products` p 
			LEFT JOIN `#__virtuemart_products_".VMLANG."` pl ON pl.`virtuemart_product_id` = p.`virtuemart_product_id` 
			LEFT JOIN `#__virtuemart_product_medias` vpm ON vpm.`virtuemart_product_id` = p.`virtuemart_product_id`

			LEFT JOIN `#__virtuemart_vmusers` AS vu ON vu.`virtuemart_vendor_id` = p.`virtuemart_vendor_id`
			LEFT JOIN `#__virtuemart_product_categories` vc ON vc.`virtuemart_product_id` = p.`virtuemart_product_id` 
			WHERE vu.`virtuemart_user_id` ='".$user->id."' 
				
			AND p.`published`='1' 
			ORDER BY p.`virtuemart_product_id` DESC LIMIT ".$rsslimit;
			$db->setQuery($q);				
			$products = $db->loadObjectList();				
			foreach($products as $product)
			{
				$q = "SELECT vm.`file_url` , vm.`file_url_thumb` 
				FROM `#__virtuemart_medias` vm 
				LEFT JOIN `#__virtuemart_product_medias` vpm ON vpm.`virtuemart_media_id` = vm.`virtuemart_media_id` 
				WHERE vpm.`virtuemart_product_id` = '".$product->virtuemart_product_id."' 
				AND vm.`file_is_product_image`='1' ";
				$db->setQuery($q);		
				$imgz = 		$db->loadRow();
				$product_file_url_thumb = $imgz[1];
				
				
				if ( $imgz[1] =='')
				{
					$product_thumb = str_replace('virtuemart/product/','virtuemart/product/resized/',$imgz[0]);
			
					$thum_side_width			=	VmConfig::get( 'img_width' );
					$thum_side_height			=	VmConfig::get( 'img_height' );
					$extension_pos = strrpos($product_thumb, "."); // find position of the last dot, so where the extension starts
					$feeditem_img = substr($product_thumb, 0, $extension_pos) . '_'.$thum_side_width.'x'.$thum_side_height . substr($product_thumb, $extension_pos);
				}
	
				$link = 'http://'.$_SERVER["SERVER_NAME"].JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$product->virtuemart_product_id.'&virtuemart_category_id='.$product->virtuemart_category_id.'&Itemid='.$vmitemid);
				$rss_xml .='<item>';
				$rss_xml .='<title>'.VmvendorFunctions::xml_entities( $product->product_name ).'</title>';
				$rss_xml .='<link>'.$link.'</link>';
				$feeditem_img = $juri. $product_file_url_thumb;
			
				$rss_xml .='<description><![CDATA[';
				if($imgz[0] OR $imgz[1])
					$rss_xml .='<img src="'.$feeditem_img.'" />';
				$rss_xml .= VmvendorFunctions::xml_entities($product->product_s_desc) ;
				$rss_xml .= ']]> </description>';
					
				$RFC_formatted = date('r', strtotime( $product->created_on ) );
				$rss_xml .='<pubDate>'.$RFC_formatted.'</pubDate>';
				$rss_xml .='<guid>'.$link.'</guid>';
				$rss_xml .='</item>';
			}
			
			$rss_xml .='</channel>';
			$rss_xml .='</rss>';	
				
					
			$feed = fopen($xml_path, "w");	
			fwrite($feed,$rss_xml);
			fclose($feed);	
			if($state == 1 || $state == 2) // product addition or edition
			{
				$app->enqueueMessage( '<i class="vmv-icon-feed"></i> '.JText::_('COM_VMVENDOR_RSSUPDATED').' <a target="_blank" href="'.$juri.'media/vmvendorss/'.$user->id.'.rss" class="hasTooltip" title="'.JText::_('COM_VMVENDOR_RSS_SHAREFEED').'" ></a>');
			}
	}
	
	
	static function emailFlickr( $virtuemart_product_id , $formname , $form_s_desc , $limited_tags , $product_url , $flickr_img)
	{
		$user 		= JFactory::getUser();
		$db 		= JFactory::getDBO();
		$message 	= JFactory::getMailer();
		$app 		= JFactory::getApplication();
		
		if (!class_exists( 'VmConfig' ))
			require JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php';
		//VmConfig::loadConfig();
		$cparams 				= JComponentHelper::getParams('com_vmvendor');
		$flickr_autopost_email	= $cparams->get('flickr_autopost_email');
		
		$q ="SELECT vvl.vendor_store_name 
		FROM #__virtuemart_vendors_".VMLANG." vvl
		JOIN #__virtuemart_products vp ON vvl.virtuemart_vendor_id = vp.virtuemart_vendor_id 
		WHERE vp.virtuemart_product_id='".$virtuemart_product_id."' ";
		$db->setQuery($q);
		$vendor_store_name = $db->loadResult();
		
		$vendor_url = JRoute::_('index.php?option=com_vmvendor&view=vendorprofile&userid='.$user->id.'&Itemid=');
		
		
		$flickr_subject = JText::_('COM_VMVENDOR_FLICKR_TITLEPREFIX').$db->escape(ucwords($formname)).JText::_('COM_VMVENDOR_FLICKR_TITLESUFFIX');	
		$flickr_subject .= ' '.JText::_('COM_VMVENDOR_BYVENDOR').' '. ucwords($vendor_store_name).'' ;
					
					
		$flickr_body = JText::_('COM_VMVENDOR_FLICKR_DESCPREFIX');
		
		$form_s_desc = str_replace(array('\r','\n'),' ',$form_s_desc);
		$flickr_body .= $db->escape($form_s_desc);	
		$flickr_body .= JText::_('COM_VMVENDOR_FLICKR_DESCSUFFIX');	
		$flickr_body .= "\n\n ".JText::_('COM_VMVENDOR_FLICKR_GETITAT').': '.substr(JURI::root(false, $product_url) , 0,-1);
		$flickr_body .= "\n\n ".JText::_('COM_VMVENDOR_FLICKR_MOREPRODUCTSBYVENDOR'). ' '.ucwords($vendor_store_name).': '.substr(JURI::root(false, $vendor_url) , 0,-1);			
					
		if($limited_tags!='')
		{
			$exploded_tags = explode(',',$limited_tags);
						for($zi=0;$zi<count($exploded_tags);$zi++)
						{
							$flickr_tags[] = '"'.$exploded_tags[$zi].'"';
						}
						$flickr_tags = implode(' ' ,$flickr_tags);
						$flickr_subject .= " tags: ".$flickr_tags;
		}
			
														
		$mailerror = '<i class="icon-cancel"></i> <font color="red"><b>'.JText::_('COM_VMVENDOR_FLICKREMAIL_FAIL').'</b></font>';		
		$message->addRecipient( $flickr_autopost_email );
		$message->setSubject( $flickr_subject );
		$message->setBody($flickr_body);
		$message->addAttachment( $flickr_img );
		$config = JFactory::getConfig();
		$sender = array( 
			$config->get( 'config.mailfrom' ),
			$config->get( 'config.fromname' )
		);
		$message->isHTML(true);
		$message->Encoding = 'base64';
		$message->setSender( $sender );
		$message->addRecipient( $config->get( 'config.mailfrom' ) );
		$sent = $message->send();
		if ($sent != 1) 
			echo  $mailerror;
		else
			$app->enqueueMessage('<i class="vmv-icon-flickr"></i> '.JText::_('COM_VMVENDOR_FLICKREMAILED') );
		
	}
	
	
	
	
	static function createVendor( $user_id )
	{
		if (!class_exists( 'VmConfig' ))
			require JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php';
		VmConfig::loadConfig();
		$cparams 		= JComponentHelper::getParams('com_vmvendor');
		$naming 		= $cparams->get('naming', 'username');
		$acy_listid		= $cparams->get('acy_listid');
		$multilang_mode = $cparams->get('multilang_mode', 0);
		if($multilang_mode >0)
		{
			$active_languages	=	VmConfig::get( 'active_languages' ); //en-GB
		}
		
		$db				= JFactory::getDBO();
		$app			= JFactory::getApplication();
		$juser			= JFactory::getUser();
		$user_name 		= $juser->name; 
		$user_username 	= $juser->username; 
		$user_email 	= $juser->email;
		$user_naming 	= $juser->$naming;
		$user_slug = JFilterOutput::stringURLSafe($user_id.'-'.$user_username);

			  ////////////////// We create the vendor and create the vmuser or update if allready exists as a customer 
					// look for main vendor currency
		$q ="SELECT `vendor_currency` FROM `#__virtuemart_vendors` WHERE `virtuemart_vendor_id` ='1' " ;
		$db->setQuery($q);
		$currency_id = $db->loadResult();
		$q = "INSERT INTO `#__virtuemart_vendors` 
		( `vendor_name` , `vendor_currency` , `vendor_accepted_currencies` , `vendor_params` , `created_on` , `created_by` , `modified_on` , `modified_by` , `locked_on` , `locked_by` ) 
		VALUES 
		('".$db->escape($user_naming)."' , '".$currency_id."' , '' , 'vendor_min_pov=0|vendor_min_poq=1|vendor_freeshipment=0|vendor_address_format=\"\"|vendor_date_format=\"\"|' , '".date('Y-m-d H:i:s')."' , '".$user_id."' , '".date('Y-m-d H:i:s')."' , '".$user_id."' , '0000-00-00 00:00:00' , '0') ";
		$db->setQuery($q);
		if (!$db->query()) die($db->stderr(true));		
		$vendorid = $db->insertid();	
			
		$q = "INSERT INTO `#__virtuemart_vendors_".VMLANG."`  
		( `virtuemart_vendor_id` ,  `vendor_store_name` , `slug` ) 
		VALUES 
		('".$vendorid."' ,  '".$db->escape($user_naming)."' , '".$db->escape($user_slug)."') ";
		$db->setQuery($q);
		if (!$db->query()) die($db->stderr(true));
		
		$q = "INSERT INTO `#__virtuemart_vendor_users`  
		( `virtuemart_vendor_id` , `virtuemart_user_id` ) 
		VALUES 
		('".$vendorid."' , '". $db->escape($user_id)."') ";
		$db->setQuery($q);
		if (!$db->query()) die($db->stderr(true));

		if($multilang_mode >0)
		{ //					
			for($i = 0 ; $i < count( $active_languages ) ; $i++)
			{
				if( str_replace('_' , '-' , VMLANG) != strtolower( $active_languages[$i]) )
				{
					$q = "INSERT INTO `#__virtuemart_vendors_".strtolower( str_replace('-' , '_' , $active_languages[$i]) ) ."`  
					( `virtuemart_vendor_id` ,  `vendor_store_name` ,  `slug` ) 
					VALUES 
					('".$vendorid."' ,  '".$db->escape($user_naming)."' , '".$db->escape($user_slug)."') ";
					$db->setQuery($q);
					if (!$db->query()) die($db->stderr(true));
				}
			}
		}
						
		if($virtuemart_vendor_id==='0')
		{  // vmuser allready (has allready created a customer profile)
			$q = "UPDATE `#__virtuemart_vmusers`  
			 SET `virtuemart_vendor_id` ='".$vendorid."', 
			 `user_is_vendor` = '1' 
			 WHERE `virtuemart_user_id` ='".$user_id."' ";
			$db->setQuery($q);
			if (!$db->query()) die($db->stderr(true));
			$message = '<i class="vmv-icon-ok"></i> '.JText::_('COM_VMVENDOR_VMVENADD_USERUPDATEDVENDOR');
		}
		elseif(!$virtuemart_vendor_id)
		{ //vmuser does not exist, we create it (without a customer_number...)		
			$q = "INSERT INTO `#__virtuemart_vmusers`  	( `virtuemart_user_id` , `virtuemart_vendor_id` , `user_is_vendor` , `customer_number`  ) 						VALUES 	('".$user_id."' , '".$vendorid."' , '1', '' )";
			$db->setQuery($q);
			if (!$db->query()) die($db->stderr(true));
			$message = '<i class="vmv-icon-ok"></i> '.JText::_('COM_VMVENDOR_VMVENADD_VENDORCREATED');
		}
		$virtuemart_vendor_id = $vendorid;
		if($acy_listid!='')
		{ // we subscribe the member to the vendor mailing list
			// is new vendor allready subscribed? to that list!?
			$q = "SELECT COUNT(acyl.`subid`)  
			FROM `#__acymailing_listsub` acyl 
			LEFT JOIN `#__acymailing_subscriber` acys ON acys.`subid` = acyl.`subid` 
			WHERE acys.`userid` = '".$user_id."' 
			AND acyl.`listid` ='".$acy_listid."' ";
			$db->setQuery($q);
			$is_subscribed = $db->loadResult();
			if( $is_subscribed < 1 )
			{
				$q = "SELECT COUNT(*) FROM `#__acymailing_subscriber` WHERE `userid`='".$user_id."' ";
				$db->setQuery($q);
				$is_subscriber = $db->loadresult();
				if( $is_subscriber <1 )
				{					
					$q ="INSERT INTO `#__acymailing_subscriber` 
					(`email` , `userid`, `name` , `created` , `confirmed`, `enabled`, `accept`, `html` )
					VALUES 
					('".$user_email."','".$user_id."','".$user_name."','".time()."','1','1','1','1' ) ";
					$db->setQuery($q);
					if (!$db->query()) die($db->stderr(true));
					$subid = $db->insertid();
				}
				else
				{
					$q ="SELECT `subid` FROM `#__acymailing_subscriber` WHERE `userid` ='".$user_id."' ";	
					$db->setQuery($q);
					$subid = $db->loadResult();
				}
				$q ="INSERT INTO `#__acymailing_listsub` 
					(`listid` , `subid` , `subdate` , `status`) 
					VALUES 
					('".$acy_listid."' , '".$subid."' , '".time()."' , '1' ) ";
				$db->setQuery($q);
				if (!$db->query()) die($db->stderr(true));	
			}
		}
		$app->enqueueMessage( $message );
	}
	
	static function getVendorid($user)
	{	
		$db		= JFactory::getDBO();	
		$q = "SELECT  `virtuemart_vendor_id` FROM `#__virtuemart_vmusers` WHERE `virtuemart_user_id` = '".$user."' ";
		$db->setQuery($q);
		return $vendor_id = $db->loadResult();	
	}
	
	static function doJomsocialActions( $virtuemart_product_id , $formcat, $product_image)
	{ 
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();
		$app 	= JFactory::getApplication();
		$juri 			= JURI::base();
		$cparams 		= JComponentHelper::getParams('com_vmvendor');
		$vmitemid 		= $cparams->get('vmitemid', '103');
		$autopublish 	= $cparams->get('autopublish', 1);
		require_once JPATH_COMPONENT.'/helpers/getvendorplan.php';
		$vendor_plan = VmvendorHelper::getvendorplan( $user->id );
		if($vendor_plan->autopublish!='')
			$autopublish = $vendor_plan->autopublish;
		if($autopublish=='2')
			$autopublish='0';
		$enablerss 		= $cparams->get('enablerss', 1);
		if( $autopublish)
		{ // auto add the jomsocial profile applciation
		
			$q = "SELECT COUNT(*) FROM `#__community_apps` WHERE `apps`='vmvendor' AND `userid`='".$user->id."'  " ;
			$db->setQuery($q);
			$app_added = $db->loadResult();
			if($app_added <1)
			{
				$q ="INSERT INTO `#__community_apps` 
				( `userid` , `apps` , `ordering` , `position` ) 
				VALUES
				('".$user->id."' , 'vmvendor' , '0' , 'content' )"; 	
				 $db->setQuery($q);
				if (!$db->query()) die($db->stderr(true));	
					$app->enqueueMessage(JText::_('COM_VMVENDOR_USERPROFILE_APPLICATION_ADDED'));	
			}	
		
		
			if($product_image!='')
			{
				if (!class_exists( 'VmConfig' ))
					require(JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php');
				VmConfig::loadConfig();
				$thumb_path 	= VmConfig::get('media_product_path').'resized';
				 $thumb_url = $juri .  $thumb_path .'/' .$product_image;
			}
		
			$form_s_desc		 =  $app->input->post->get('form_s_desc',null,'string');
			$formname 			= $app->input->post->get('formname',null,'string');
			$product_url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$virtuemart_product_id.'&virtuemart_category_id='.$formcat.'&Itemid='.$vmitemid);
			$jspath = JPATH_ROOT .  '/components/com_community';
			include_once($jspath.  '/libraries/core.php');//activity stream  - added a blog
			CFactory::load('libraries', 'activities');          
			$act = new stdClass();
			$act->cmd    = 'wall.write';
			$act->actor    = $user->id;
			$act->target    = 0; // no target
			$act->title    = JText::_('COM_VMVENDOR_JOMSOCIAL_HASJUSTADDED').' <a href="'.$product_url.'">'.stripslashes( ucfirst($formname) ).'</a>' ;
			$output = '';
			if($product_image!='')
				$output .= '<div style="float:left;padding:0 10px 0 0;width:100px"><a href="'.$product_url.'"><img src="'.$thumb_url.'" alt="thumb" width="90"  /></a></div>';
			if($form_s_desc!='')
				$output .= '<div style="float:left;width:300px">'.$form_s_desc.'</div>';
			if($enablerss)
				$output .='<div style="clear:both"></div><a href="'.$juri.'media/vmvendorss/'.$user->id.'.rss" >
			<i class="icon-feed"></i> '.JText::_('COM_VMVENDOR_VENDORRSS').'</a>';
			$act->content    = $output;
			$act->app    = 'vmvendor.productaddition';
			$act->cid    = 0;
			$act->comment_id	= CActivities::COMMENT_SELF;
			$act->comment_type	= 'vmvendor.productaddition';
			$act->like_id		= CActivities::LIKE_SELF;		
			$act->like_type		= 'vmvendor.productaddition';
			CActivityStream::add($act);
		}
	}

	static function doEasysocialActions( $virtuemart_product_id , $formcat, $product_image)
	{
		$cparams 		= JComponentHelper::getParams('com_vmvendor');
		$vmitemid 		= $cparams->get('vmitemid', '103');
		$autopublish 	= $cparams->get('autopublish', 1);
		require_once JPATH_COMPONENT.'/helpers/getvendorplan.php';
		$vendor_plan = VmvendorHelper::getvendorplan( $user->id );
		if($vendor_plan->autopublish!='')
			$autopublish = $vendor_plan->autopublish;
		if($autopublish=='2')
			$autopublish='0';	
		$enablerss 		= $cparams->get('enablerss', 1);
		if( $autopublish)
		{
			$user 	= JFactory::getUser();
			$app 	= JFactory::getApplication();
			$juri 			= JURI::base();
			require_once JPATH_ROOT . '/administrator/components/com_easysocial/includes/foundry.php';
			$config 	= Foundry::config();
			$naming = $config->get( 'users.displayName' );  // username or realname
			if($naming=='realname')
				$naming = 'name';
			if($product_image!='')
			{
				if (  !class_exists( 'VmConfig' )  )
					require JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php';
				VmConfig::loadConfig();
				$thumb_path 	= VmConfig::get('media_product_path').'resized';
				 $thumb_url =   $thumb_path .'/' .$product_image;
			}
			 
			$form_s_desc		 =  $app->input->post->get('form_s_desc',null,'string');
			$formname 			= 	$app->input->post->get('formname',null,'string');
			$product_url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$virtuemart_product_id.'&virtuemart_category_id='.$formcat.'&Itemid='.$vmitemid);
			$stream     = Foundry::stream();
			$template   = $stream->getTemplate();
			$my         = Foundry::user();
			$template->setActor( $user->id , 'user' );
			$template->setVerb( 'add' );
			$content = '';
			if($product_image!='')
				$content .= '<div style="float:left;padding:0 10px 0 0;width:100px"><a href="'.$product_url.'"><img src="'.$thumb_url.'" alt="thumb" width="90"  /></a></div>';
			if($form_s_desc!='')
				$content .= '<div style="float:left">'.$form_s_desc.'</div>';
			if($enablerss)
				$content .='<div style="clear:both"></div><a href="media/vmvendorss/'.$user->id.'.rss" >
			<i class="icon-feed"></i> '.JText::_('COM_VMVENDOR_VENDORRSS').'</a>';
			//$template->setcontextParams( $content );	
			$contextParams =  array( 
				'product_name'=>stripslashes( ucfirst($formname) ),
				'product_url'=>$product_url ,
				'thumb_url'=>$thumb_url,
				'form_s_desc'=>$form_s_desc,
				'userid'=>$user->id,
			);
			$template->setContext( $virtuemart_product_id  , 'product' , $contextParams );
			$template->setType( 'full' );
			$streamItem = $stream->add($template);
		}
	}
	
	static function emailNotifyAddition( $virtuemart_product_id, $formcat , $autopublish )
	{
		$juri = JURI::Base();
		$user = JFactory::getUser();
		$cparams 		= JComponentHelper::getParams('com_vmvendor');
		$naming			= $cparams->get('naming','username');
		$profileitemid 	= $cparams->get('profileitemid', '2');
		$vmitemid 		= $cparams->get('vmitemid', '103');
		$to 			= $cparams->get('to');
		
		$subject = JText::_('COM_VMVENDOR_EMAIL_HELLO')." ".$juri." ".JText::_('COM_VMVENDOR_EMAIL_BYUSER')." ".$user->$naming;	
		$mailurl= $juri.'index.php?option=com_vmvendor&view=vendorprofile&userid='.$user->id.'&Itemid='.$profileitemid;
		$body = JText::_('COM_VMVENDOR_EMAIL_YOUCAN')." <a href='".$juri."index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=".$virtuemart_product_id."&virtuemart_category_id=".$formcat."&Itemid=".$vmitemid."' >"
				.JText::_('COM_VMVENDOR_EMAIL_HERE')."</a>."
				.JText::_('COM_VMVENDOR_EMAIL_SUBMITTEDBY')." <a href='".$mailurl."'>".$user->$naming."</a>. ";
						
		if(!$autopublish)
			$body .=JText::_('COM_VMVENDOR_EMAIL_BUTFIRSTREVIEW').' <a href="'.$juri.'administrator/index.php?option=com_virtuemart&view=product&task=edit&virtuemart_product_id='.$virtuemart_product_id.'&product_parent_id=0">'.JText::_('COM_VMVENDOR_EMAIL_SHOPADMIN').'</a>.';												
		$mailerror = '<i class="icon-cancel"></i> '.JText::_('COM_VMVENDOR_EMAIL_FAIL');		
		$message = JFactory::getMailer();
		$message->addRecipient( $to );
		$message->setSubject( $subject );
		$message->setBody($body);
		$config = JFactory::getConfig();
		$sender = array( 
			$config->get( 'config.mailfrom' ),
			$config->get( 'config.fromname' )
		);
		$message->isHTML(true);
		$message->Encoding = 'base64';
		$message->setSender( $sender );
		$message->addRecipient( $config->get( 'config.mailfrom' ) );
		$sent = $message->send();
		if ($sent != 1) 
			echo  $mailerror;
	}
}