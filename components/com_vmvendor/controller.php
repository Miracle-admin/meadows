<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2015 Adrien Roussel
 * @license : GNU/GPL v3
 * @Website : http://www.nordmograph.com/extensions
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla controller library
jimport('joomla.application.component.controller');
class VmvendorController extends JControllerLegacy{
	
	function display()
	{
	$app = &JFactory::getApplication();
    $params = JComponentHelper::getParams('com_vmvendor');
	$restricted=$params->get('restricted_group');
	$allowed=$params->get('vendor_views_access');
	$user=JFactory::getUser();
	$userg=$user->groups;
	$view=JRequest::getVar('view');
    $exists = array_intersect($restricted, $userg);
    $exists = count($exists);
	
	if($exists && !in_array($view,$allowed)){
	$app->redirect(JRoute::_(JUri::root())."index.php","You are not authorized to perform this action","error");
	}
    if($view=="vendorprofile")
	{
	$userid = $app->input->getInt('userid',0);
	if($userid!=0)
	{
	$userR=JFactory::getUser($userid);
	$usergR=$userR->groups;
	$existsR = array_intersect($restricted, $usergR);
	$existsR = count($existsR);
	if($existsR)
	$app->redirect(JRoute::_(JUri::root())."index.php","Profile not found","error");
	}
	else
	{
	if($exists)
	$app->redirect(JRoute::_(JUri::root())."index.php","Profile not found","error");
	}
	
	}
	
	
    parent::display();
	
	}
	function deleteproduct()
	{
		$app 					= JFactory::getApplication();
		
		$doc 					= JFactory::getDocument();
		$user 					= JFactory::getUser();		
		$juri 					= JURI::base();
		$db						= JFactory::getDBO();	
		$model      			= $this->getModel ( 'vendorprofile' );
		$view       			= $this->getView  ( 'vendorprofile','html' );
		if (!class_exists( 'VmConfig' ))
			require JPATH_ADMINISTRATOR .  '/components/com_virtuemart/helpers/config.php';
		VmConfig::loadConfig();
		$cparams 				= JComponentHelper::getParams('com_vmvendor');
		$multilang_mode 		= $cparams->get('multilang_mode', 0);
		if($multilang_mode >0)
		{
			$active_languages	=	VmConfig::get( 'active_languages' ); //en-GB
		}
		$allow_deletion 		= $cparams->get('allow_deletion');
		$enablerss 				= $cparams->get('enablerss', 1);
		$profileman 			= $cparams->get('profileman');
		$freefiles_folder 	= $cparams->get('freefiles_folder','media');
		
		$price					= $app->input->post->get('price');
		$userid					= $app->input->get('userid', '' , 'INT');
		$delete_productid 		= $app->input->post->get('delete_productid', '' , 'INT');
		jimport('joomla.filesystem.file');
		
		$forsalefiles_plugin = 'ekerner';
		
		if($allow_deletion >0 && $delete_productid    && $user->id >0 && $userid==$user->id   )
		{
			
			$q = " SELECT `virtuemart_vendor_id` FROM `#__virtuemart_vmusers` WHERE `virtuemart_user_id`='".$user->id."' " ;
			$db->setQuery($q);
			$virtuemart_vendor_id = $db->loadResult();
				
			if($profileman=='js' || $profileman=='es' )
			{ // we need the product name to delete the activity stream from url
				$q ="SELECT `product_name` FROM `#__virtuemart_products_".VMLANG."`  WHERE `virtuemart_product_id`='".$delete_productid."' ";
				$db->setQuery($q);
				$delete_productname = $db->loadResult();
			}
			switch($allow_deletion)
			{
				case 1: // unpublish
					$q ="UPDATE `#__virtuemart_products` SET `published`='0' WHERE `virtuemart_product_id`='".$delete_productid."' ";
					$db->setQuery($q);
					if (!$db->query()) die($db->stderr(true));
					$message = '<span class="vmv-icon-ok"></span> '.JText::_( 'COM_VMVENDOR_PROFILE_PRODUCTUNPUBLISHED' );
					$app->enqueueMessage( $message );
				break;				
				case 2: // Delete if no sale
				$q ="SELECT count(*) FROM `#__virtuemart_order_items` WHERE `virtuemart_product_id`='".$delete_productid."' ";
				$db->setQuery($q);
				$yetsold = $db->loadResult();
				if($yetsold<1)
				{
					$q = "DELETE FROM `#__virtuemart_products` WHERE `virtuemart_product_id`='".$delete_productid."' ";
					$db->setQuery($q);
					if (!$db->query()) die($db->stderr(true));					
					$q = "DELETE FROM `#__virtuemart_products_".VMLANG."` WHERE `virtuemart_product_id`='".$delete_productid."' ";
					$db->setQuery($q);
					if (!$db->query()) die($db->stderr(true));
					if($multilang_mode >0)
					{ 					
						for($i = 0 ; $i < count( $active_languages ) ; $i++)
						{
							//$app->enqueueMessage($active_languages[$i]); //en-GB
							if( str_replace('_' , '-' , VMLANG) != strtolower( $active_languages[$i]) ){
								$q = "DELETE FROM `#__virtuemart_products_".strtolower( str_replace('-' , '_' , $active_languages[$i]) ) ."` WHERE `virtuemart_product_id`='".$delete_productid."' ";
								$db->setQuery($q);
								if (!$db->query()) die($db->stderr(true));
							}
						}
					}
					
					
										
					$q = "DELETE FROM `#__virtuemart_product_categories` WHERE `virtuemart_product_id`='".$delete_productid."' ";
					$db->setQuery($q);
					if (!$db->query()) die($db->stderr(true));															
					$q = "DELETE FROM `#__virtuemart_product_manufacturers` WHERE `virtuemart_product_id`='".$delete_productid."' ";
					$db->setQuery($q);
					if (!$db->query()) die($db->stderr(true));
					////// Delete medias
					$q ="SELECT `virtuemart_media_id` FROM `#__virtuemart_product_medias` WHERE `virtuemart_product_id`='".$delete_productid."' ";
					$db->setQuery($q);
					$mediastodel = $db->loadObjectList();
					foreach($mediastodel as $mediatodel )
					{
						$q = "SELECT `file_url` , `file_url_thumb` FROM `#__virtuemart_medias` WHERE `virtuemart_media_id` ='".$mediatodel->virtuemart_media_id."' ";
						$db->setQuery($q);
						$files_url = $db->loadRow();
						$image_url = $files_url[0];
						$thumb_url = $files_url[1];
						if($image_url!='')
							JFile::delete($image_url);
						if($thumb_url!='')
							JFile::delete($thumb_url);
						$q ="DELETE FROM `#__virtuemart_medias` WHERE `virtuemart_media_id` ='".$mediatodel->virtuemart_media_id."' ";	
						$db->setQuery($q);
						if (!$db->query()) die($db->stderr(true));
					}
					if($price>0 OR $freefiles_folder=='safe')
					{						
						if($forsalefiles_plugin == 'istraxx')
						{
							$q ="SELECT `customfield_params` FROM `#__virtuemart_product_customfields` 
							WHERE `virtuemart_product_id`='".$delete_productid."' 
							AND customfield_value='st42_download'";	
							
							$db->setQuery($q);
							$custom_params = $db->loadObjectList();
							if(count($custom_params)>0)
							{
								foreach($custom_params as $custom_param)
								{
									$virtuemart_media_id = 	str_replace('media_id="' , '' , $custom_param->customfield_params);
									$strlen = strlen($virtuemart_media_id);
									$strpos = strpos($virtuemart_media_id , '"');
									$virtuemart_media_id = substr($virtuemart_media_id , 0 , -($strlen - $strpos) );
									$q ="SELECT `file_url` FROM `#__virtuemart_medias` WHERE `virtuemart_media_id` ='".$virtuemart_media_id."' " ;
									$db->setQuery($q);
									$customfiled_file_url = $db->loadResult();
									if($customfiled_file_url!='')
										JFile::delete($customfiled_file_url);
									$q ="DELETE FROM `#__virtuemart_medias` WHERE `virtuemart_media_id` ='".$virtuemart_media_id."'  ";
									$db->setQuery($q);
									if (!$db->query()) die($db->stderr(true));
								}
							}
						}
						elseif($forsalefiles_plugin == 'ekerner')
						{
							$q ="SELECT `customfield_params` FROM `#__virtuemart_product_customfields` 
							WHERE `virtuemart_product_id`='".$delete_productid."' 
							AND customfield_value='downloadable'";	
							
							$db->setQuery($q);
							$custom_params = $db->loadObjectList();
							if(count($custom_params)>0)
							{
								foreach($custom_params as $custom_param)
								{
									$virtuemart_media_id = 	str_replace('downloadable_media_id="' , '' , $custom_param->customfield_params);
									$strlen = strlen($virtuemart_media_id);
									$strpos = strpos($virtuemart_media_id , '"');
									$virtuemart_media_id = substr($virtuemart_media_id , 0 , -($strlen - $strpos) );
									$q ="SELECT `file_url` FROM `#__virtuemart_medias` WHERE `virtuemart_media_id` ='".$virtuemart_media_id."' " ;
									$db->setQuery($q);
									$customfiled_file_url = $db->loadResult();
									if($customfiled_file_url!='')
										JFile::delete($customfiled_file_url);
									$q ="DELETE FROM `#__virtuemart_medias` WHERE `virtuemart_media_id` ='".$virtuemart_media_id."'  ";
									$db->setQuery($q);
									if (!$db->query()) die($db->stderr(true));
								}
							}
						}
					}
					$q ="DELETE FROM `#__virtuemart_product_medias` WHERE `virtuemart_product_id` ='".$delete_productid."' ";	
					$db->setQuery($q);
					if (!$db->query()) die($db->stderr(true));
					$q = "DELETE FROM `#__virtuemart_product_customfields` WHERE `virtuemart_product_id`='".$delete_productid."' ";
					$db->setQuery($q);
					if (!$db->query()) die($db->stderr(true));					
					$q = "DELETE FROM `#__virtuemart_product_prices` WHERE `virtuemart_product_id`='".$delete_productid."' ";
					$db->setQuery($q);
					if (!$db->query()) die($db->stderr(true));
					/*$q = "DELETE FROM `#__virtuemart_product_relations`  WHERE `virtuemart_product_id`='".$delete_productid."' ";
					$db->setQuery($q);
					if (!$db->query()) die($db->stderr(true));*/
					$q = "DELETE FROM `#__virtuemart_product_shoppergroups`  WHERE `virtuemart_product_id`='".$delete_productid."' ";
					$db->setQuery($q);
					if (!$db->query()) die($db->stderr(true));
					$q = "DELETE FROM `#__virtuemart_ratings`  WHERE `virtuemart_product_id`='".$delete_productid."' ";
					$db->setQuery($q);
					if (!$db->query()) die($db->stderr(true));
					$q = "DELETE FROM `#__virtuemart_rating_reviews`  WHERE `virtuemart_product_id`='".$delete_productid."' ";
					$db->setQuery($q);
					if (!$db->query()) die($db->stderr(true));
					$q = "DELETE FROM `#__virtuemart_rating_votes`  WHERE `virtuemart_product_id`='".$delete_productid."' ";
					$db->setQuery($q);
					if (!$db->query()) die($db->stderr(true));
					$message = '<span class="vmv-icon-ok"></span> '.JText::_( 'COM_VMVENDOR_PROFILE_PRODUCTDELETED' );
					$app->enqueueMessage( $message );
					
				}
				else
				{					
					$q ="UPDATE `#__virtuemart_products` SET `published`='0' WHERE `virtuemart_product_id`='".$delete_productid."' ";
					$db->setQuery($q);
					if (!$db->query()) die($db->stderr(true));
					$message='<span class="vmv-icon-ok"></span> '.JText::_( 'COM_VMVENDOR_PROFILE_PRODUCTUNPUBLISHEDBECAUSE' );
					$app->enqueueMessage( $message );
				}
				break;
				
				case 3: // Delete !
				$q = "DELETE FROM `#__virtuemart_products` WHERE `virtuemart_product_id`='".$delete_productid."' ";
				$db->setQuery($q);
				if (!$db->query()) die($db->stderr(true));					
				$q = "DELETE FROM `#__virtuemart_products_".VMLANG."` WHERE `virtuemart_product_id`='".$delete_productid."' ";
				$db->setQuery($q);
				if (!$db->query()) die($db->stderr(true));	
				if($multilang_mode >0)
				{ 					
                                        for($i = 0 ; $i < count( $active_languages ) ; $i++)
					{
						//$app->enqueueMessage($active_languages[$i]); //en-GB
						if( str_replace('_' , '-' , VMLANG) != strtolower( $active_languages[$i]) ){
							$q = "DELETE FROM `#__virtuemart_products_".strtolower( str_replace('-' , '_' , $active_languages[$i]) ) ."` WHERE `virtuemart_product_id`='".$delete_productid."' ";
							$db->setQuery($q);
							if (!$db->query()) die($db->stderr(true));	
						}	
					}
				}				
				$q = "DELETE FROM `#__virtuemart_product_categories` WHERE `virtuemart_product_id`='".$delete_productid."' ";
				$db->setQuery($q);
				if (!$db->query()) die($db->stderr(true));									
				$q = "DELETE FROM `#__virtuemart_product_manufacturers` WHERE `virtuemart_product_id`='".$delete_productid."' ";
				$db->setQuery($q);
				if (!$db->query()) die($db->stderr(true));					
				////// Delete medias
				$q ="SELECT `virtuemart_media_id` FROM `#__virtuemart_product_medias` WHERE `virtuemart_product_id`='".$delete_productid."' ";
				$db->setQuery($q);
				$mediastodel = $db->loadObjectList();
				foreach($mediastodel as $mediatodel )
				{
					$q = "SELECT `file_url` , `file_url_thumb` FROM `#__virtuemart_medias` WHERE `virtuemart_media_id` ='".$mediatodel->virtuemart_media_id."' ";
					$db->setQuery($q);
					$files_url = $db->loadRow();
					$image_url = $files_url[0];
					$thumb_url = $files_url[1];
					if($image_url!='')
						JFile::delete($image_url);
					if($thumb_url!='')
						JFile::delete($thumb_url);
					$q ="DELETE FROM `#__virtuemart_medias` WHERE `virtuemart_media_id` ='".$mediatodel->virtuemart_media_id."' ";	
					$db->setQuery($q);
					if (!$db->query()) die($db->stderr(true));	
				}					
				if($price>0 OR $freefiles_folder=='safe')
				{
					if($forsalefiles_plugin == 'istraxx')
					{
						$q ="SELECT `customfield_params` FROM `#__virtuemart_product_customfields` 
							WHERE `virtuemart_product_id`='".$delete_productid."' 
							AND customfield_value='st42_download'";	
						$db->setQuery($q);
						$custom_params = $db->loadObjectList();
						if(count($custom_params)>0)
						{
							foreach($custom_params as $custom_param)
							{
								$virtuemart_media_id = 	str_replace('media_id="' , '' , $custom_param->customfield_params);
								$strlen = strlen($virtuemart_media_id);
								$strpos = strpos($virtuemart_media_id , '"');
								$virtuemart_media_id = substr($virtuemart_media_id , 0 , -($strlen - $strpos) );
								$q ="SELECT `file_url` FROM `#__virtuemart_medias` WHERE  `virtuemart_media_id` ='".$virtuemart_media_id."' " ;
								$db->setQuery($q);
								$customfiled_file_url = $db->loadResult();
								if($customfiled_file_url!='')
									JFile::delete($customfiled_file_url);
								$q ="DELETE FROM `#__virtuemart_medias` WHERE `virtuemart_media_id` ='".$virtuemart_media_id."'  ";
								$db->setQuery($q);
								if (!$db->query()) die($db->stderr(true));
							}
						}
					}
					elseif($forsalefiles_plugin == 'ekerner')
					{
						$q ="SELECT `customfield_params` FROM `#__virtuemart_product_customfields` 
							WHERE `virtuemart_product_id`='".$delete_productid."' 
							AND customfield_value='downloadable'";	
						$db->setQuery($q);
						$custom_params = $db->loadObjectList();
						if(count($custom_params)>0)
						{
							foreach($custom_params as $custom_param)
							{
								$virtuemart_media_id = 	str_replace('downloadable_media_id="' , '' , $custom_param->customfield_params);
								$strlen = strlen($virtuemart_media_id);
								$strpos = strpos($virtuemart_media_id , '"');
								$virtuemart_media_id = substr($virtuemart_media_id , 0 , -($strlen - $strpos) );
								$q ="SELECT `file_url` FROM `#__virtuemart_medias` WHERE  `virtuemart_media_id` ='".$virtuemart_media_id."' " ;
								$db->setQuery($q);
								$customfiled_file_url = $db->loadResult();
								if($customfiled_file_url!='')
									JFile::delete($customfiled_file_url);
								$q ="DELETE FROM `#__virtuemart_medias` WHERE `virtuemart_media_id` ='".$virtuemart_media_id."'  ";
								$db->setQuery($q);
								if (!$db->query()) die($db->stderr(true));
							}
						}
					}
				}
				$q ="DELETE FROM `#__virtuemart_product_medias` WHERE `virtuemart_product_id` ='".$delete_productid."' ";	
				$db->setQuery($q);
				if (!$db->query()) die($db->stderr(true));				
				$q = "DELETE FROM `#__virtuemart_product_customfields` WHERE `virtuemart_product_id`='".$delete_productid."' ";
				$db->setQuery($q);
				if (!$db->query()) die($db->stderr(true));										
				$q = "DELETE FROM `#__virtuemart_product_prices` WHERE `virtuemart_product_id`='".$delete_productid."' ";
				$db->setQuery($q);
				if (!$db->query()) die($db->stderr(true));	
				/*$q = "DELETE FROM `#__virtuemart_product_relations`  WHERE `virtuemart_product_id`='".$delete_productid."' ";
				$db->setQuery($q);
				if (!$db->query()) die($db->stderr(true));*/
				$q = "DELETE FROM `#__virtuemart_product_shoppergroups`  WHERE `virtuemart_product_id`='".$delete_productid."' ";
				$db->setQuery($q);
				if (!$db->query()) die($db->stderr(true));
				$q = "DELETE FROM `#__virtuemart_ratings`  WHERE `virtuemart_product_id`='".$delete_productid."' ";
				$db->setQuery($q);
				if (!$db->query()) die($db->stderr(true));
				$q = "DELETE FROM `#__virtuemart_rating_reviews`  WHERE `virtuemart_product_id`='".$delete_productid."' ";
				$db->setQuery($q);
				if (!$db->query()) die($db->stderr(true));
				$q = "DELETE FROM `#__virtuemart_rating_votes`  WHERE `virtuemart_product_id`='".$delete_productid."' ";
				$db->setQuery($q);
				if (!$db->query()) die($db->stderr(true));
				$message = '<span class="vmv-icon-ok"></span> '.JText::_( 'COM_VMVENDOR_PROFILE_PRODUCTDELETED' );
				$app->enqueueMessage( $message );			
				break;
			}			
			if ($enablerss)
			{							
				require_once JPATH_COMPONENT.'/helpers/functions.php' ;
				VmvendorFunctions::updateRSS(3  , $virtuemart_vendor_id );	 // 3 for deletion						
			}
			if($profileman =='js')
			{ // delete product related Jomsocial activity stream
				$q = "DELETE FROM `#__community_activities` 
				WHERE `actor`='".$user->id."' 
				AND `title` LIKE '%".ucfirst($delete_productname)."%' " ;
				$db->setQuery($q);
				if (!$db->query()) die($db->stderr(true));
			}
			elseif($profileman =='es')
			{ // delete product related EasySocial activity stream
				require_once JPATH_ROOT . '/administrator/components/com_easysocial/includes/foundry.php';
				Foundry::stream()->delete( $delete_productid , 'product' );
			}
			
		}
		return $this->display ();	
	}	
}