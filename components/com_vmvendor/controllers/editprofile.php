<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2015 Adrien Roussel Nordmograph.com
 * @license : GNU/GPL v3
 * @Website : http://www.nordmograph.com/extensions
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
//jimport('joomla.application.component.controller');


class VmvendorControllerEditprofile extends JControllerForm
{
	/**
	 * Custom Constructor
	 */
	 public function getModel($name = '', $prefix = '', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, array('ignore_request' => false));
	}
 	function __construct()	{
		parent::__construct( );
	}
	function getVendorid()
	{
		$user 	= JFactory::getUser();		
		$db		= JFactory::getDBO();	$q = "SELECT  `virtuemart_vendor_id` FROM `#__virtuemart_vmusers` WHERE `virtuemart_user_id` = '".$user->id."' ";
		$db->setQuery($q);
		return $vendor_id = $db->loadResult();	
	}
	
	function save($key = NULL, $urlVar = NULL)
	{
		
		
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		//require_once( JPATH_COMPONENT.'/helpers/functions.php' );
		jimport('joomla.application.component.controller');
		
		
		if (!class_exists( 'VmConfig' ))
			require JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php';
		VmConfig::loadConfig();
		$dblang = VMLANG;
		$image_path 			= VmConfig::get('media_vendor_path');
		$vmconfig_img_width		= VmConfig::get('img_width');	
		if(!$vmconfig_img_width) $vmconfig_img_width = 90;
		$thumb_path = $image_path.'resized/';
		$app 					= JFactory::getApplication();
		$user 					= JFactory::getUser();		
		$juri 					= JURI::base();
		$db						= JFactory::getDBO();	
		$model      			= $this->getModel ( 'editprofile' );
		$view       			= $this->getView  ( 'editprofile','html' );
		$Itemid 			= $app->input->get('Itemid');

		$data  = $this->input->post->get('jform', array(), 'array');
		$files = $this->input->files->get('jform');

		$form = $model->getForm();
		if (!$form)
		{
			JError::raiseError(500, $model->getError());
			return false;
		}
		$validate = $model->validate($form, $data);

		if ($validate === false)
		{
			// Get the validation messages.
			$errors	= $model->getErrors();
			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 5; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$app->enqueueMessage( $errors[$i]->getMessage(), 'warning');
				} else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			// Save the data in the session.
			$app->setUserState('com_vmvendor.editprofile.data', $data);

			// Redirect back to the form.
			$this->setRedirect(JRoute::_('index.php?option=com_vmvendor&view=editprofile&Itemid='.$Itemid, false));
			return false;
		}
		
		
		
		$cparams 				= JComponentHelper::getParams( 'com_vmvendor' );
		$multilang_mode 		= $cparams->get('multilang_mode', 0);
		if($multilang_mode >0)
		{
			//$active_languages	=	VmConfig::get( 'active_languages' );
			$lang = JFactory::getLanguage(); 
			$dblang = strtolower( str_replace('-' , '_' , $lang->getTag() ) );
		}

		$profileman 			= $cparams->get( 'profileman' );
		$maximgside 			= $cparams->get('maximgside', '800');
		$thumbqual 				= $cparams->get('thumbqual', 90);
		$wysiwyg_prof			= $cparams->get('wysiwyg_prof', 1);
		$paypalemail_field		= $cparams->get('paypalemail_field', 1);
		
		$vendor_title						=	$data['vendor_title'];
		$vendor_telephone					=	$data['vendor_telephone'];
		$vendor_url							=	$data['vendor_url'];
		$vendor_store_desc					=	$data['vendor_store_desc'];
		$vendor_terms_of_service			=	$data['vendor_terms_of_service'];
		$vendor_legal_info					=	$data['vendor_legal_info'];

		$paypal_email					=	$data['paypal_email'];
		if($paypalemail_field)
		{
			$q ="SELECT id, paypal_email FROM `#__vmvendor_paypal_emails` WHERE userid='".$user->id."' ";
			$db->setQuery($q);
			$paypalemail_data = $db->loadObject();
			if($paypalemail_data->id>0 && $paypalemail_data->paypal_email != $paypal_email)
			{
				$q = "UPDATE #__vmvendor_paypal_emails SET paypal_email='".$paypal_email."' WHERE userid='".$user->id."'" ;
				$db->setQuery($q);
				if (!$db->query()) die($db->stderr(true));
			}
			elseif(!$paypalemail_data->id && $paypal_email!='' )
			{
				$q = "INSERT INTO `#__vmvendor_paypal_emails`  
				(userid,vendorid, paypal_email)
				VALUES
				('".$user->id."' , '".VmvendorControllerEditprofile::getVendorid()."' ,'".$paypal_email."' )" ;
				$db->setQuery($q);
				if (!$db->query()) die($db->stderr(true));

			}

		}


		/*if($wysiwyg_prof)
		{
			//$vendor_store_desc     				= JRequest::getVar('vendor_store_desc', '', 'post', 'string', JREQUEST_ALLOWRAW);
			//$vendor_terms_of_service     		= JRequest::getVar('vendor_terms_of_service', '', 'post', 'string', JREQUEST_ALLOWRAW);
			//$vendor_legal_info     				= JRequest::getVar('vendor_legal_info', '', 'post', 'string', JREQUEST_ALLOWRAW);
			
			$vendor_store_desc     				= $app->input->post->get('vendor_store_desc');
			$vendor_terms_of_service     		= $app->input->post->get('vendor_terms_of_service');
			$vendor_legal_info     				= $app->input->post->get('vendor_legal_info');
		}*/
		$activity_stream					=	&$data['activity_stream'];
		$slug								= str_replace( " " , "-" , $vendor_title );
		$slug								= $user->id.'-'.str_replace( "'" , "-" , strtolower( $slug ) );		
		
		$vendor_id = $this->getVendorid();		
		
		if(!$vendor_id)
		{
			require_once JPATH_COMPONENT.'/helpers/functions.php';
			VmvendorFunctions::createVendor( $user->id );
			$vendor_id = $this->getVendorid();	
		}

			$update = 1;
			if($multilang_mode >0)
			{ // check if data allready exists in the current language to know if we update or insert.
				$q = "SELECT  COUNT(*)  FROM `#__virtuemart_vendors_".$dblang."`  WHERE `virtuemart_vendor_id` 	='".$vendor_id."' ";
				$db->setQuery($q);
				$allready_in = $db->loadResult();	
				if($allready_in <1)
				{
					$update = 0;
					$q = "INSERT INTO `#__virtuemart_vendors_".$dblang."` 
					( `virtuemart_vendor_id` , `vendor_store_desc` , `vendor_terms_of_service` , `vendor_legal_info` , `vendor_store_name` , `vendor_phone` , `vendor_url` , `slug` ) 
					VALUES
					('".$vendor_id."' , 
					'".$db->escape( $vendor_store_desc )."'  ,
					 '".$db->escape( $vendor_terms_of_service )."' ,
					 '".$db->escape( $vendor_legal_info )."' , 
					 '".$db->escape( $vendor_title )."' , 
					 '".$db->escape( $vendor_telephone )."' , 
					 '".$db->escape( $vendor_url )."' , 
					 '".$db->escape( $slug )."'  ) ";			
					$db->setQuery($q);
					if (!$db->query()) die($db->stderr(true));
				}
			}
			
			if($update==1)
			{
				$q = "UPDATE `#__virtuemart_vendors_".$dblang."` SET 
				`vendor_store_desc` 			= '".$db->escape( $vendor_store_desc )."'  ,
				`vendor_terms_of_service` 		= '".$db->escape( $vendor_terms_of_service )."' , 
				`vendor_legal_info` 			= '".$db->escape( $vendor_legal_info )."' , 
				`vendor_store_name`				= '".$db->escape( $vendor_title )."' , 
				`vendor_phone` 					= '".$db->escape( $vendor_telephone )."' , 
				`vendor_url` 					= '".$db->escape( $vendor_url )."' , 
				`slug` 							= '".$db->escape( $slug )."' 
				WHERE `virtuemart_vendor_id`='".$vendor_id."' ";
							
				$db->setQuery($q);
				if (!$db->query()) die($db->stderr(true));
				
				$q = "UPDATE `#__virtuemart_vendors` SET 
				`vendor_name`				= '".$db->escape( $vendor_title )."' 
				WHERE `virtuemart_vendor_id`='".$vendor_id."' ";			
				$db->setQuery($q);
				if (!$db->query()) die($db->stderr(true));
			}

			/*if($multilang_mode >0){ 					
				for($i = 0 ; $i < count( $active_languages ) ; $i++){
					$app->enqueueMessage($active_languages[$i]); //en-GB
					if( str_replace('_' , '-' , VMLANG) != strtolower( $active_languages[$i]) ){
						$q = "UPDATE `#__virtuemart_vendors_".Vstrtolower( str_replace('-' , '_' , $active_languages[$i]) ) ."` SET 
						`vendor_store_desc` 			= '".$db->escape( $vendor_store_desc )."'  ,
						`vendor_terms_of_service` 		= '".$db->escape( $vendor_terms_of_service )."' , 
						`vendor_legal_info` 			= '".$db->escape( $vendor_legal_info )."' , 
						`vendor_store_name`				= '".$db->escape( $vendor_title )."' , 
						`vendor_phone` 					= '".$db->escape( $vendor_telephone )."' , 
						`vendor_url` 					= '".$db->escape( $vendor_url )."' , 
						`slug` 							= '".$db->escape( $slug )."' 
						WHERE `virtuemart_vendor_id` 	='".$vendor_id."' ";
						$db->setQuery($q);
						if (!$db->query()) die($db->stderr(true));			
					}				
				}
			}*/
			jimport('joomla.filesystem.file');
			//$image = JRequest::getVar('vendor_image', null, 'files', 'array');
			$image = $files['vendor_image'];
			
			$image['name'] = JFile::makeSafe($image['name']);
			if($image['name']!='')
			{
				/////// check if there allready is a vendor image
				$imgisvalid = 1;
				$q = "SELECT vm.`virtuemart_media_id` , vm.`file_url` , vm.`file_url_thumb` 
				FROM `#__virtuemart_medias` vm 
				LEFT JOIN `#__virtuemart_vendor_medias` vvm ON vvm.`virtuemart_media_id` = vm.`virtuemart_media_id` 
				WHERE vvm.`virtuemart_vendor_id`='".$vendor_id."' ";
				$db->setQuery($q);
				$vendorimages = $db->loadRow();
				$virtuemart_media_id = $vendorimages[0];
				$file_url = $vendorimages[1];
				$file_url_thumb = $vendorimages[2];
				$vendorimage_path ='images/stories/virtuemart/vendor/';
				$vendorthumb_path ='images/stories/virtuemart/vendor/resized/';
				$infosImg = getimagesize($image['tmp_name']);		
				//if ( (substr($image['type'],0,5) != 'image' || $infosImg[0] > $maximgside || $infosImg[1] > $maximgside ) ){
				if ( (substr($image['type'],0,5) != 'image' ) ){
					JError::raiseWarning( 100, JText::_('COM_VMVENDOR_VMVENADD_IMGEXTNOT'));
					$imgisvalid = 0;
				}
				//JError::raiseWarning( 100,$imgisvalid);
				$vendor_image = strtolower($user->id ."_".$image['name']);														
				$target_imagepath = JPATH_BASE . '/' . $vendorimage_path . $vendor_image;
				if($imgisvalid){
					if( JFile::upload( $image['tmp_name'] , $target_imagepath )  )
					{
						$app->enqueueMessage( '<i class="vmv-icon-ok"></i> '.JText::_('COM_VMVENDOR_VMVENADD_IMAGEUPLOADRENAME_SUCCESS').' '.$vendor_image);
					}
					else
						JError::raiseWarning( 100,JText::_('COM_VMVENDOR_VMVENADD_IMAGEUPLOAD_ERROR') );
				}
				$ext = JFile::getExt( $image['name'] ) ; 
				$ext = strtolower($ext);
				$ext = str_replace('jpeg','jpg',$ext);
								//SWITCHES THE IMAGE CREATE FUNCTION BASED ON FILE EXTENSION
				switch($ext)
				{
								case 'jpg':
									$source = imagecreatefromjpeg($target_imagepath);
									$large_source = imagecreatefromjpeg($target_imagepath);
								break;
								case 'png':
									$source = imagecreatefrompng($target_imagepath);
									$large_source = imagecreatefrompng($target_imagepath);
								break;
								case 'gif':
									$source = imagecreatefromgif($target_imagepath);
									$large_source = imagecreatefromgif($target_imagepath);
								break;
								default:
									//JError::raiseWarning( 100, JText::_('COM_VMVENDOR_VMVENADD_IMAGEUPLOAD_INVALID') );
									$imgisvalid = 0;
								break;
				} 
				if($vendor_image!='' && $imgisvalid )
				{
					list($width,  $height) = getimagesize($target_imagepath); 
					if($width>$maximgside)
					{
									$resizedH = ( $maximgside * $height) / $width;
						if($ext=='gif')
							$largeone = imagecreate( $maximgside ,  $resizedH);
						else
							$largeone = imagecreatetruecolor( $maximgside ,  $resizedH);
						imagealphablending( $largeone, false);
						imagesavealpha( $largeone,true);
						$transparent = imagecolorallocatealpha($largeone, 255, 255, 255, 127);
						imagefilledrectangle($largeone, 0, 0, $maximgside, $resizedH, $transparent);
						imagecopyresampled( $largeone,  $large_source,  0,  0,  0,  0,  $maximgside ,  $resizedH,  $width,  $height );
					}
                     else
					 {
                                     $largeone = $target_imagepath;
                    }
					switch($ext)
					{
									case 'jpg':
										imagejpeg($largeone, JPATH_BASE . '/' . $image_path .'/' .$vendor_image,  $thumbqual);
									break;
									case 'jpeg':
										imagejpeg($largeone, JPATH_BASE . '/' . $image_path .'/' .$vendor_image,  $thumbqual);
									break;
									case 'png':
										imagepng($largeone, JPATH_BASE . '/' . $image_path .'/' .$vendor_image);
									break;
									case 'gif':
										imagegif($largeone, JPATH_BASE . '/' . $image_path .'/' .$vendor_image);
									break;
					} 
					imagedestroy($largeone);

					if($width>=$height)
					{ 
									$resizedH = ($vmconfig_img_width * $height) / $width;
									if($ext=='gif')
										$thumb = imagecreate( $vmconfig_img_width ,  $resizedH);
									else
										$thumb = imagecreatetruecolor( $vmconfig_img_width ,  $resizedH);
									imagealphablending( $thumb, false);
									imagesavealpha( $thumb,true);
									$transparent = imagecolorallocatealpha($thumb, 255, 255, 255, 127);
									imagefilledrectangle($thumb, 0, 0, $vmconfig_img_width , $resizedH, $transparent);
									imagecopyresampled( $thumb,  $source,  0,  0,  0,  0,  $vmconfig_img_width ,  $resizedH,  $width,  $height );
								}
								else
								{
									$resizedW = ( VmConfig::get('img_height') * $width) / $height;
									if($ext=='gif')
										$thumb = imagecreate($resizedW,  VmConfig::get('img_height') );
									else
										$thumb = imagecreatetruecolor($resizedW,  VmConfig::get('img_height') );
									imagealphablending( $thumb, false);
									imagesavealpha( $thumb,true);
									$transparent = imagecolorallocatealpha($thumb, 255, 255, 255, 127);
									imagefilledrectangle($thumb, 0, 0, $resizedW , VmConfig::get('img_height'), $transparent);
									imagecopyresampled($thumb ,  $source ,  0 ,  0 ,  0 ,  0 ,  $resizedW,  VmConfig::get('img_height') ,  $width,  $height );
								}
								 switch($ext)
								 {
									case 'jpg':
										imagejpeg($thumb, JPATH_BASE . '/' . $thumb_path .'/' .$vendor_image,  $thumbqual);
									break;
									case 'jpeg':
										imagejpeg($thumb, JPATH_BASE . '/' . $thumb_path .'/' .$vendor_image,  $thumbqual);
									break;
									case 'png':
										imagepng($thumb, JPATH_BASE . '/' . $thumb_path .'/' .$vendor_image);
									break;
									case 'gif':
										imagegif($thumb, JPATH_BASE . '/' . $thumb_path .'/' .$vendor_image);
									break;
								} 
								imagedestroy($thumb);
					if($virtuemart_media_id!='')
					{ // we updated the picture
							// delete all media file
						if($file_url !=  $image_path.JFile::makeSafe($vendor_image) ){ // only delete old file if new filename and old are diferent. If same file has allready been overwritten
							if($file_url!='')
								JFile::delete($file_url);
							if($file_url_thumb!='')
								JFile::delete($file_url_thumb);
						}						
						$q ="UPDATE `#__virtuemart_medias` SET 
						 `file_title`='".$db->escape($vendor_title)."' , 
						 `file_mimetype`='".$image['type']."' , 
						`file_url` = '".$vendorimage_path.JFile::makeSafe($vendor_image)."' , 
						 `file_url_thumb` ='".$vendorthumb_path.JFile::makeSafe($vendor_image)."' ,
						 `modified_on`='".date('Y-m-d H:i:s')."' , 
						 `modified_by` ='".$user->id."' 
						 WHERE `virtuemart_media_id` ='".$virtuemart_media_id."' ";
						$db->setQuery($q);
						if (!$db->query()) die($db->stderr(true));
					}
					else
					{ // we insert the new file					
						$q = "INSERT INTO `#__virtuemart_medias` 
							( `virtuemart_vendor_id` , `file_title` , `file_mimetype` , `file_type` , `file_url` , `file_url_thumb` , `file_is_product_image` , `published` , `created_on` , `created_by`)
							VALUES
							(  '".$vendor_id."' , '".$db->escape($vendor_title)."' , '".$image['type']."' , 'vendor' , '".$vendorimage_path.JFile::makeSafe($vendor_image)."' , '".$vendorthumb_path.JFile::makeSafe($vendor_image)."' , '1', '1' , '".date('Y-m-d H:i:s')."' , '".$user->id."' )";
						$db->setQuery($q);
						if (!$db->query()) die($db->stderr(true));
						$virtuemart_media_id = $db->insertid();
						$q = "INSERT INTO `#__virtuemart_vendor_medias` 
							( `virtuemart_vendor_id` , `virtuemart_media_id` )
							VALUES
							(  '".$vendor_id."' , '".$virtuemart_media_id."'  )";
						$db->setQuery($q);
						if (!$db->query()) die($db->stderr(true));
					}
				}		
			}
			if( $activity_stream )
			{
				$vendorprofile_url = JRoute::_('index.php?option=com_vmvendor&view=vendorprofile&userid='.$user->id.'&Itemid='.$Itemid);
				if($profileman=='js')
				{
					$jspath = JPATH_ROOT . '/components/com_community';
					include_once $jspath. '/libraries/core.php';
					CFactory::load('libraries', 'activities');          
					$act = new stdClass();
					$act->cmd    = 'wall.write';
					$act->actor    = $user->id;
					$act->target    = 0; // no target
					$act->title    = JText::_( 'COM_VMVENDOR_JOMSOCIAL_EDITEDPROFILE').' <a href="'.$vendorprofile_url.'">'.ucfirst($vendor_title).'</a>' ;		
					$act->content    = '';
					$act->app    = 'vmvendor.vendorupdate';
					$act->cid    = 0;
					$act->comment_id	= CActivities::COMMENT_SELF;
					$act->comment_type	= 'vmvendor.vendorupdate';
					$act->like_id		= CActivities::LIKE_SELF;		
					$act->like_type		= 'vmvendor.vendorupdate';
					CActivityStream::add($act);	
				}
				if($profileman=='es') 
				{
					require_once JPATH_ROOT . '/administrator/components/com_easysocial/includes/foundry.php';
					$vendorprofile_link = '<a href="'.$vendorprofile_url.'">'.ucfirst($vendor_title).'</a>';
					$contextParams =  array(  'vendorprofile_link'=>$vendorprofile_link );
					$stream     = Foundry::stream();
					$template   = $stream->getTemplate();
					$template->setActor( $user->id, 'user' );
					$template->setContext( $user->id , 'vendorprofile' ,$contextParams);
					$template->setVerb( 'edit' );
					$stream->add( $template );
				
				}
			}
			$app->enqueueMessage( '<i class="vmv-icon-ok"></i> '.JText::_('COM_VMVENDOR_UPDATED_SUCCESS') );
		if($vendorprofile_itemid=='')
			$vendorprofile_itemid = $app->input->get('Itemid');
		$app->redirect( JRoute::_('index.php?option=com_vmvendor&view=vendorprofile&Itemid='.$this->getVMVprofileItemid() ) );
	}
	public function cancel($key = NULL, $urlVar = NULL)
	{	
		$this->setRedirect(JRoute::_('index.php?option=com_vmvendor&view=vendorprofile', false));	
	}
	
	public function getVMVprofileItemid()
	{
		$db 	= JFactory::getDBO();
		$lang 	= JFactory::getLanguage();
		$q = "SELECT `id` FROM `#__menu` WHERE `link` ='index.php?option=com_vmvendor&view=vendorprofile' AND ( language ='".$lang->getTag()."' OR language='*') AND published='1' AND access='1' ";
		$db->setQuery($q);
		return $vmvendoritemid = $db->loadResult();	
	}
}
?>