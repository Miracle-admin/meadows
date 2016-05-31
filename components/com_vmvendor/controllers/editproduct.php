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
class VmvendorControllerEditproduct extends VmvendorController
{
	/**
	 * Custom Constructor
	 */
 	function __construct()
	{
		parent::__construct( );
	}
	function updateproduct() 
	{
		$app 					= JFactory::getApplication();
		$user 					= JFactory::getUser();		
		$juri 					= JURI::base();
		$db						= JFactory::getDBO();	
		$doc 					= JFactory::getDocument();
		if (!class_exists( 'VmConfig' ))
			require JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php';
		VmConfig::loadConfig();
		$dblang = VMLANG;	
		
		$virtuemart_product_id = $app->input->getInt( 'productid' );
		$cparams 				= JComponentHelper::getParams( 'com_vmvendor' );
		$profileman 			= $cparams->get( 'profileman' );
		$naming 				= $cparams->get( 'naming' );
		$forbidcatids 			= $cparams->get('forbidcatids');	
		$vmitemid 				= $cparams->get('vmitemid', '103');
		$profileitemid 			= $cparams->get('profileitemid', '2');
		
		$multicat	 			= $cparams->get('multicat', 0);
		$autopublish 			= $cparams->get('autopublish', 1);
		
		require_once JPATH_COMPONENT.'/helpers/getvendorplan.php';
		$vendor_plan = VmvendorHelper::getvendorplan( $user->id );
		if($vendor_plan->autopublish!='')
			$autopublish = $vendor_plan->autopublish;
		if($autopublish=='2')
			$autopublish='1';
			
	
		$plan_max_img 	= $vendor_plan->max_img;
		$plan_max_files = $vendor_plan->max_files;
		
		
		
		$enablerss 				= $cparams->get('enablerss', 1);
		$emailnotify_updated 	= $cparams->get('emailnotify_updated', 1);
		$emailnotify_addition	= $cparams->get('emailnotify_addition', 1); // in case product has never been published before
		$to 					= $cparams->get('to');
		$flickr_autopost		= $cparams->get('flickr_autopost',0);
		$flickr_autopost_email	= $cparams->get('flickr_autopost_email');
		$flickr_img				= '';
		$maxfilesize 			= $cparams->get('maxfilesize', '4000000');//4 000 000 bytes   =  4M
		$max_imagefields		= $cparams->get('max_imagefields', 4);
		if( $plan_max_img )
			$max_imagefields = $plan_max_img;
			
		$max_filefields			= $cparams->get('max_filefields', 4);
		if( $plan_max_files )
			$max_filefields = $plan_max_files;
			
		$maximgside 			= $cparams->get('maximgside', '800');
		$thumbqual 				= $cparams->get('thumbqual', 90);
		$wysiwyg_prod 			= $cparams->get('wysiwyg_prod', 0);
		$enablefiles 			= $cparams->get('enablefiles', 1);
		$forsalefiles_plugin = 'ekerner';
		
		$freefiles_folder 	= $cparams->get('freefiles_folder','media');
		
		$stream		 			= $cparams->get('stream', 0);
		$maxspeed 				= $cparams->get('maxspeed', '3000');
		$maxtime				= $cparams->get('maxtime', '365');
		$enableprice			= $cparams->get('enableprice', 1);
		$enablestock			= $cparams->get('enablestock', 1);
		$enableweight			= $cparams->get('enableweight', 1);
		$weightunits			= $cparams->get('weightunits');
		
		$enable_corecustomfields= $cparams->get('enable_corecustomfields', 1);
		$enable_vm2tags			= $cparams->get('enable_vm2tags', 0);
		$tagslimit				= $cparams->get('tagslimit', '5');
		$vm2tags_asmetakeywords	= $cparams->get('vm2tags_asmetakeywords', '1');
		
		$enable_vm2geolocator	= $cparams->get('enable_vm2geolocator', 0);
		$enable_embedvideo		= $cparams->get('enable_embedvideo', 0);
		$resample_commercial_mp3= $cparams->get('resample_commercial_mp3', 1);
		$mp3sample_start		= $cparams->get('mp3sample_start', 0);
		$mp3sample_end			= $cparams->get('mp3sample_end', 30);
		
		$latitude				= $app->input->post->get('latitude');
		$longitude				= $app->input->post->get('longitude');
		$zoom					= $app->input->post->get('zoom');
		$maptype				= $app->input->post->get('maptype');
		
		
		
		$reset_onpricestatus 	= $cparams->get('reset_onpricestatus', 1);
		
		$multilang_mode 		= $cparams->get('multilang_mode', 0);
		if($multilang_mode >0)
		{
			$lang = JFactory::getLanguage(); 
			$dblang = strtolower( str_replace('-' , '_' , $lang->getTag() ) );
		}
		$filemandatory 			= $cparams->get('filemandatory', 1);
		$imagemandatory 		= $cparams->get('imagemandatory', 0);
		$allowedexts 			= $cparams->get('allowedexts', 'zip,mp3');
		$minimum_price			= $cparams->get('minimum_price');
		$sepext 				= explode( "," , $allowedexts );
		$countext 				= count($sepext);
		$image_path 			= VmConfig::get('media_product_path');
		$safepath 				= VmConfig::get('forSale_path' );
		$vmconfig_img_width		= VmConfig::get('img_width');	
		if(!$vmconfig_img_width) $vmconfig_img_width = 90;
		
		
		
		$thumb_path 			= $image_path.'resized/';
		
		$formfile = '';
		
		$published 				= $app->input->post->get('formpublished',null,'int');
		$formname 				= $app->input->post->get('formname',null,'string');
		$formdesc		 		= $app->input->post->get('formdesc',null,'string');
		//if($enable_sdesc)
			$form_s_desc		= $app->input->post->get('form_s_desc',null,'string');
		$formmanufacturer		= $app->input->post->get('formmanufacturer');
		/*else{
			$length = 250; 
			if (strlen($formdesc) <= $length) {
			   $form_s_desc	=  $formdesc; //do nothing
			} else {
			   $form_s_desc = substr( $formdesc ,0,strpos($formdesc ,' ',$length));
			   $form_s_desc .= '...';
			}
		}*/
		if($wysiwyg_prod)
		{
			$formdesc     	= $app->input->post->get('formdesc','','array');
			$formdesc     	= implode($formdesc);
		}
		if($enableprice)
			$formprice 			= $app->input->post->get('formprice',null,'string');
		else
			$formprice			= 0;
		$oldprice 				= $app->input->post->get('oldprice');
		$formweight				= $app->input->post->get('formweight');
		$formweightunit			= $app->input->post->get('formweightunit');
		if($multicat)
			$formcat 			= $app->input->get('formcat',null, 'ARRAY');
		else
			$formcat 			= $app->input->get('formcat',null, 'INT');
		$announceupdate			= $app->input->post->get('announceupdate');
		$file1					= $app->input->files->get('file1');
		$file1name				= $file1['name'];
		
		if($enablefiles && $safepath=='')
			JError::raiseWarning( 100, JText::_('COM_VMVENDOR_VMVENADD_SAFEPATHREQUIRED') );
		if(VmConfig::get('multix','none')!='admin')
			JError::raiseWarning( 100, JText::_('COM_VMVENDOR_VMVENADD_MULTIVENDORREQUIRED') );
			
		$q = " SELECT `virtuemart_vendor_id` FROM `#__virtuemart_vmusers` WHERE `virtuemart_user_id`='".$user->id."' " ;
		$db->setQuery($q);
		$virtuemart_vendor_id = $db->loadResult();
		if( $formcat 
		   && $formname 
		   && $formdesc 
		   && ( ($enableweight && $formweight!='' && $formweightunit!='') OR !$enableweight  ) 
		   && ( ($enableprice && $formprice!='') OR !$enableprice )
		   && $user->id > 0 

		  ){ 
	
		jimport('joomla.filesystem.file');
		$formsku 			= $app->input->post->get('formsku');
	
		$formcurrency 		= $app->input->post->get('formcurrency');
		if($enablestock)
			$formstock 			= $app->input->post->get('formstock');
		else
			$formstock = '1';
		// check if product has neverbeen published before
		$q = "SELECT created_on FROM #__virtuemart_products WHERE virtuemart_product_id='".$virtuemart_product_id."' ";
		$db->setQuery($q);
		$created_on = $db->loadResult();
		$isnew = 0;
		if($created_on=='0000-00-00 00:00:00')
			$isnew = 1;
			
			
			
		/////////// check if any image is removed
		for($l = 2 ; $l<= $max_imagefields ; $l++)
		{
			if($app->input->post->get('delimg'.$l)=='on')
			{
				$virtuemart_media_id = $app->input->post->get('media_id'.$l);
				$q = "SELECT `file_url` , `file_url_thumb` FROM `#__virtuemart_medias` WHERE `virtuemart_media_id`='".$virtuemart_media_id."' ";
				$db->setQuery($q);
				$media_files = $db->loadRow();
				$image_url 		= $media_files[0];
				$thumb_url		= $media_files[1];
				if($image_url!='')
					JFile::delete($image_url);
				if($thumb_url!='')
					JFile::delete($thumb_url);
				$q ="DELETE FROM `#__virtuemart_medias` WHERE `virtuemart_media_id`='".$virtuemart_media_id."' ";
				$db->setQuery($q);
				if (!$db->query()) die($db->stderr(true));
				$q ="DELETE FROM `#__virtuemart_product_medias` WHERE `virtuemart_media_id`='".$virtuemart_media_id."' ";
				$db->setQuery($q);
				if (!$db->query()) die($db->stderr(true));
				$app->enqueueMessage( '<i class="vmv-icon-ok"></i> '.JText::_('COM_VMVENDOR_IMGREMOVEDSUCCESSFULLY'));				
			}	
		}
		
		/////////// check if any file is removed
		for($n = 1 ; $n<= $max_filefields ; $n++)
		{
			if($app->input->post->get('delfile'.$n)=='on')
			{
				$virtuemart_media_id = $app->input->post->get('filemedia_id'.$n);
				$q = "SELECT `file_title`, `file_url`  FROM `#__virtuemart_medias` WHERE `virtuemart_media_id`='".$virtuemart_media_id."' ";
				$db->setQuery($q);
				$file_media = $db->loadRow();
				$file_title	= $file_media[0];
				$file_url	= $file_media[1];
				if($file_url!='')
					JFile::delete($file_url);
				$q ="DELETE FROM `#__virtuemart_medias` WHERE `virtuemart_media_id`='".$virtuemart_media_id."' ";
				$db->setQuery($q);
				if (!$db->query()) die($db->stderr(true));
				if($oldprice>0 OR $freefiles_folder =='safe')
				{ 
					if($forsalefiles_plugin == 'istraxx')
					{// we delete the st42_download entry
						$q ="DELETE FROM `#__virtuemart_product_customfields` WHERE `customfield_value`='st42_download' AND  `customfield_params` LIKE CONCAT('%' , CONCAT('media_id=\"' , '".$virtuemart_media_id."' , '\"') , '%' ) ";
					
					}
					elseif($forsalefiles_plugin == 'ekerner')
					{
						$q ="DELETE FROM `#__virtuemart_product_customfields` WHERE `customfield_value`='downloadable' AND  `customfield_params` LIKE CONCAT('downloadable_media_id=\"".$virtuemart_media_id."\"'  , 
						'%' ) ";
					
					}
					$db->setQuery($q);if (!$db->query()) die($db->stderr(true));		
					if( strtolower(substr($file_url,-4))=='.mp3' )
					{ // delete mp3 preview too
						JFile::delete('images/stories/virtuemart/product/vmvsample_'.$file_title);
						
						$q = "SELECT virtuemart_media_id FROM #__virtuemart_medias WHERE file_title ='vmvsample_".$file_title."' ";
						$db->setQuery($q);
						$media_id_to_delete = $db->loadresult();
						$q= "DELETE FROM #__virtuemart_medias WHERE virtuemart_media_id='".$media_id_to_delete."' ";
						$db->setQuery($q);
						if (!$db->query()) die($db->stderr(true));
						$q= "DELETE FROM #__virtuemart_product_medias WHERE virtuemart_media_id='".$media_id_to_delete."' ";
						$db->setQuery($q);
						if (!$db->query()) die($db->stderr(true));
					}		
				}
				else
				{ // we delete the regular media entry
					$q ="DELETE FROM `#__virtuemart_product_medias` WHERE `virtuemart_media_id`='".$virtuemart_media_id."' ";
					$db->setQuery($q);
					if (!$db->query()) die($db->stderr(true));
				}
				$app->enqueueMessage( '<i class="vmv-icon-ok"></i> '.JText::_('COM_VMVENDOR_FILEREMOVEDSUCCESSFULLY'));				
			}	
		}
		
		
		
			
	
	
		
		if($enablefiles)
		{
			for ($i=1; $i <= $max_filefields ;$i++){ ////////////// images
				$fileisvalid = 0;
				$file = $app->input->files->get('file'.$i);
				if($file)
				{
					$filename = JFile::makeSafe($file['name']);
					$ext =  JFile::getExt($filename);
					$formfilesize 	= $file['size'];
					$form_mime 		= $file['type'];					
					for ( $j=0 ; $j < $countext ; $j++ ){
						if ($sepext[$j] == $ext)
							$fileisvalid = 1; // file has an allowed extention					
					}
					
					if($filename!='')
					{							
						if	(!$fileisvalid)
							JError::raiseWarning( 100,JText::_('COM_VMVENDOR_FILEEXTNOT') );
						if($formfilesize > $maxfilesize OR $formfilesize==0){
							$fileisvalid = 0;
							JError::raiseWarning( 100, JText::_('COM_VMVENDOR_MAXFILESIZEX').' '.$formsku ."_".$filename );
						}
					}
					else
						$fileisvalid = 0;
						
					$target_filepath = $safepath .  $formsku ."_".$filename;
						//echo 'error: '.$_FILES['file'.$i]['error'];
						
					if($fileisvalid)
					{
						$file_is_downloadable = 0;
						$file_is_forSale = 1;
						$target_filepath = $safepath .  $formsku ."_".$filename;
						if( $formprice == '0' && $freefiles_folder=='media')
						{
							$file_is_downloadable = 1;
							$file_is_forSale = 0;
							$target_filepath = $image_path. $formsku ."_".$filename;
						}
						
						if( JFile::upload($file['tmp_name'] , $target_filepath ) )
							$app->enqueueMessage( '<i class="vmv-icon-ok"></i> '.JText::_('COM_VMVENDOR_FILEUPLOADRENAME_SUCCESS').' '. $formsku.'_'. $filename);
						else
						{
							JError::raiseWarning( 100, JText::_('COM_VMVENDOR_FILEUPLOAD_ERROR') );
						}
						
						
						if($app->input->post->get('filemedia_id'.$i,'','INT')!='')
						{ // we updated the file
						//echo 'we update the file';
							$q = "SELECT `file_title`, `file_url`  FROM `#__virtuemart_medias` WHERE `virtuemart_media_id`='".$app->input->post->get('filemedia_id'.$i,'','INT')."' ";
							$db->setQuery($q);
							$file_media = $db->loadRow();
							$file_title	= $file_media[0];
							$file_url	= $file_media[1];
							if($file_url !=  $target_filepath )
							{ // only delete old file if new filename and old are diferent. If same file has allready been overwritten
								if($file_url!='')
									JFile::delete($file_url);
								if( $resample_commercial_mp3 && ($oldprice>0 OR $freefiles_folder=='safe' )&& strtolower( substr($file_url,-4))=='.mp3' )
								{ // delete mp3 preview too
									JFile::delete('images/stories/virtuemart/product/vmvsample_'.$file_title);	
								}
								if($resample_commercial_mp3 && ($formprice==0 && $freefiles_folder=='media' )&& strtolower( substr($file_url,-4))=='.mp3' )
								{ 
									// product now free no need a sample, delete preview sample DB entries
									JFile::delete('images/stories/virtuemart/product/vmvsample_'.$file_title);	
									$q = "SELECT virtuemart_media_id FROM #__virtuemart_medias WHERE file_title ='vmvsample_".$file_title."' ";
									$db->setQuery($q);
									$media_id_to_delete = $db->loadResult();
									$q= "DELETE FROM #__virtuemart_medias WHERE virtuemart_media_id='".$media_id_to_delete."' ";
									$db->setQuery($q);
									if (!$db->query()) die($db->stderr(true));
									$q= "DELETE FROM #__virtuemart_product_medias WHERE virtuemart_media_id='".$media_id_to_delete."' ";
									$db->setQuery($q);
									if (!$db->query()) die($db->stderr(true));
								}
								elseif( $resample_commercial_mp3 && ($oldprice>0 OR $freefiles_folder=='safe')&& ($formprice>0 OR $freefiles_folder=='safe' )&& strtolower( substr($file_url,-4))=='.mp3' )
								{ // we update the entry
									$q = "SELECT virtuemart_media_id FROM #__virtuemart_medias WHERE file_title ='vmvsample_".$file_title."' ";
									$db->setQuery($q);
									$media_id_to_update = $db->loadResult();
									$q = "UPDATE `#__virtuemart_medias` SET 
										 `file_title`='".$db->escape('vmvsample_'.$formsku.'_'.$filename)."' , 
										 `file_mimetype`='".$file['type']."' , 
										 `file_url` = '".addslashes($image_path. "vmvsample_".$formsku .'_'.$filename)."' , 
										 `modified_on`='".date('Y-m-d H:i:s')."' , 
										 `modified_by` ='".$user->id."' 
										 WHERE `virtuemart_media_id` ='".$media_id_to_update."' ";
									$db->setQuery($q);
									if (!$db->query()) die($db->stderr(true));
									
									
									require_once JPATH_BASE.'/components/com_vmvendor/classes/class.mp3.php'; 
									$mp3 = new mp3; 
										/* cut the mp3 file  cut_mp3($file_input, $file_output, $startindex = 0, $endindex = -1, $indextype = 'frame', $cleantags = false) 
												it will return true or false*/ 
									$vmvsample_path = $image_path. "vmvsample_".$formsku .'_'.$filename;
										//   $mp3->cut_mp3( $file['tmp_name'] , $vmvsample_path , 0, 30, 'second', false);*
									$mp3->cut_mp3( $target_filepath , $vmvsample_path , $mp3sample_start , $mp3sample_end , 'second', false);
									$app->enqueueMessage( '<i class="vmv-icon-ok"></i> '.JText::_('COM_VMVENDOR_PREVIEWCREATEDSUCCESSFULLY').' '. ($mp3sample_end - $mp3sample_start) .'"');	
								}
								if( ($oldprice>0 && $formprice>0) OR $freefiles_folder=='safe')
								{  // we update the for sale entry
									$q = "SELECT virtuemart_media_id FROM #__virtuemart_medias WHERE file_title ='".$file_title."' ";
									$db->setQuery($q);
									$media_id_to_update = $db->loadResult();
									$q = "UPDATE `#__virtuemart_medias` SET 
										 `file_title`='".$db->escape($formsku.'_'.$filename)."' , 
										 `file_mimetype`='".$file['type']."' , 
										 `file_url` = '".addslashes($target_filepath)."' , 
										 `modified_on`='".date('Y-m-d H:i:s')."' , 
										 `modified_by` ='".$user->id."' 
										 WHERE `virtuemart_media_id` ='".$media_id_to_update."' ";
									$db->setQuery($q);
									if (!$db->query()) die($db->stderr(true));		
								}	
							}
							else
							{ // free media
								$q ="UPDATE `#__virtuemart_medias` SET 
							 `file_title`='".$db->escape($formsku.'_'.$filename)."' , 
							 `file_mimetype`='".$file['type']."' , 
							 `file_url` = '".addslashes($target_filepath)."' , 
							 `modified_on`='".date('Y-m-d H:i:s')."' , 
							 `modified_by` ='".$user->id."' 
							 WHERE `virtuemart_media_id` ='".$app->input->post->get('filemedia_id'.$i,'','INT')."' ";
								$db->setQuery($q);
								if (!$db->query()) die($db->stderr(true));
							}
						}
						else
						{ // we add the new file
							$q = "INSERT INTO `#__virtuemart_medias` 
							( `virtuemart_vendor_id` , `file_title` , `file_mimetype` , `file_type` , `file_url` ,  `file_is_downloadable` , `file_is_forSale` , `published` , `created_on` , `created_by`)
							VALUES
							(  '".$virtuemart_vendor_id."' , '".$db->escape($formsku.'_'.$filename)."' , '".$file['type']."' , 'product' , '".addslashes($target_filepath)."' , '".$file_is_downloadable."', '".$file_is_forSale."' , '1' , '".date('Y-m-d H:i:s')."' , '".$user->id."' )";
							$db->setQuery($q);
							if (!$db->query()) die($db->stderr(true));
							$virtuemart_media_id = $db->insertid();
							
							if($formprice>0 OR $freefiles_folder=='safe')
							{
								if($forsalefiles_plugin == 'istraxx')
								{
									/*$q = "SELECT `virtuemart_custom_id` FROM `#__virtuemart_customs` WHERE `custom_element`='st42_download' ";
									$db->setQuery($q);
									$virtuemart_custom_id = $db->loadresult();
									$q = "INSERT INTO `#__virtuemart_product_customfields` 
									( `virtuemart_product_id` , `virtuemart_custom_id` , `customfield_value` ,  `customfield_params` , `published` , `created_on` , `created_by` )
									VALUES 
									( '".$virtuemart_product_id."' , '".$virtuemart_custom_id."' , 'st42_download' ,  '{\"media_id\":\"".$virtuemart_media_id."\",\"stream\":\"".$stream."\",\"maxspeed\":\"".$maxspeed."\",\"maxtime\":\"".$maxtime."\"}' , '0' , '".date('Y-m-d H:i:s')."' , '".$user->id."'  )";
									$db->setQuery($q);
									if (!$db->query()) die($db->stderr(true));*/
								}
								elseif($forsalefiles_plugin == 'ekerner')
									{  // http://shop.ekerner.com/index.php/shop/joomla-extensions/vmcustom-downloadable-detail
										$downloadable_expires_quantity = $cparams->get('downloadable_expires_quantity','0');
										$downloadable_expires_period = $cparams->get('downloadable_expires_period','days');
										$downloadable_order_states = $cparams->get('downloadable_order_states');
										$statuses_string ='';
										for($e=0;$e<count($downloadable_order_states);$e++)
										{
											$statuses_string .= '\"'.$downloadable_order_states[$e].'\"';
											if($e<count($downloadable_order_states)-1)
												$statuses_string .= ',';
										}
										
										$q = "SELECT `virtuemart_custom_id` 
										FROM `#__virtuemart_customs` 
										WHERE `custom_element`='downloadable' ";
										$db->setQuery($q);
										$virtuemart_custom_id = $db->loadresult();
										$q = "INSERT INTO `#__virtuemart_product_customfields` 
										( `virtuemart_product_id` , `virtuemart_custom_id` , `customfield_value`  , `customfield_params` , `published` , `created_on` , `created_by` )
										VALUES 
										( '".$virtuemart_product_id."' , '".$virtuemart_custom_id."' , 'downloadable'  , 
										'downloadable_media_id=\"".$virtuemart_media_id."\"|downloadable_order_states=[".$statuses_string."]|downloadable_expires_quantity=\"".$downloadable_expires_quantity."\"|downloadable_expires_period=\"".$downloadable_expires_period."\"|yo' , '1' , '".date('Y-m-d H:i:s')."' , '".$user->id."'  )";
										$db->setQuery($q);
										if (!$db->query()) die($db->stderr(true));
									}
								
								
								if( strtolower(substr($filename,-4)) =='.mp3' && $resample_commercial_mp3)
								{
										require_once JPATH_BASE.'/components/com_vmvendor/classes/class.mp3.php'; 
										$mp3 = new mp3; 
										/* cut the mp3 file 
												cut_mp3($file_input, $file_output, $startindex = 0, $endindex = -1, $indextype = 'frame', $cleantags = false) 
												it will return true or false*/ 
										$vmvsample_path = $image_path. "vmvsample_".$formsku .'_'.$filename;
										//   $mp3->cut_mp3( $file['tmp_name'] , $vmvsample_path , 0, 30, 'second', false);*
										$mp3->cut_mp3( $target_filepath , $vmvsample_path , $mp3sample_start , $mp3sample_end , 'second', false);
										$q = "INSERT INTO `#__virtuemart_medias` 
										( `virtuemart_vendor_id` , `file_title` , `file_description` , `file_mimetype` , `file_type` , `file_url` , `file_url_thumb` , `file_is_downloadable` , `file_is_forSale` , `published` , `created_on` , `created_by` )
										VALUES
										(  '".$virtuemart_vendor_id."' , 'vmvsample_".$formsku.'_'.$filename."' , '".$db->escape($formname)." ".JText::_('COM_VMVENDOR_SAMPLETRACK')." ".$i."' , 'audio/mp3' , 'product' , '".addslashes($vmvsample_path)."' , '' , '1', '0', '1' , '".date('Y-m-d H:i:s')."' , '".$user->id."' )";																///addslashes($target_filepath)
										$db->setQuery($q);
										if (!$db->query()) die($db->stderr(true));
										$virtuemart_sample_media_id = $db->insertid();
										$q = "INSERT INTO `#__virtuemart_product_medias` 
										( `virtuemart_product_id` , `virtuemart_media_id`, `ordering` )
										VALUES
										(  '".$virtuemart_product_id."' , '".$virtuemart_sample_media_id."' , '".($i+1)."' )";
										$db->setQuery($q);
										if (!$db->query()) die($db->stderr(true));
										$app->enqueueMessage( '<i class="vmv-icon-ok"></i> '.JText::_('COM_VMVENDOR_PREVIEWCREATEDSUCCESSFULLY').' '. ($mp3sample_end - $mp3sample_start) .'"');
										
								}	
							}
							else
							{
								$q = "INSERT INTO `#__virtuemart_product_medias` 
								( `virtuemart_product_id` , `virtuemart_media_id` , `ordering`)
								VALUES
								(  '".$virtuemart_product_id."' , '".$virtuemart_media_id."'  ,'".($i + 1)."' )";
								$db->setQuery($q);
								if (!$db->query()) die($db->stderr(true));
							}	
						}
					}				
				}
			}
		}
	
		if($formdesc=='') $formdesc = $form_s_desc;
		if($form_s_desc=='') $form_s_desc = strip_tags( str_replace(array('<p>','<div>','<br>', '<br />'),' ',$formdesc) );
	
	
		if($admitted = 1)
		{
			if (strlen($form_s_desc) > 255)
			{ 
				$form_s_desc = substr($form_s_desc,0,251);
				$splitted = split(" ",$form_s_desc);
				$keys = array_keys($splitted);
				$lastKey = end($splitted);
				$countlastkey = strlen($lastKey);
				$form_s_desc = substr_replace($form_s_desc.' ','...',-($countlastkey+1),-1);
			}
			$q ="UPDATE `#__virtuemart_products` SET `product_in_stock`='".$formstock."' , `published`='".$published."' , `modified_on`='".date('Y-m-d H:i:s')."' , `modified_by`='".$user->id."' ";
			if ($enableweight)
				$q .= " , `product_weight`='".$formweight."' , `product_weight_uom`='".$formweightunit."' ";
			if($created_on=='000-00-00 00:00:00' && $published)
				$q .= " , `created_on`='".date('Y-m-d H:i:s')."' ";
			$q .="WHERE `virtuemart_product_id`='".$virtuemart_product_id."'";
			$db->setQuery($q);
			if (!$db->query()) die($db->stderr(true));
			
			
			
			/*$q = "UPDATE `#__virtuemart_products_".$dblang."` 
			 SET  `product_s_desc`='".$db->escape($form_s_desc)."' , 
			 `product_desc`='".$db->escape($formdesc)."' , 
			 `product_name`='".$db->escape($formname)."' , 
			 `slug`='".$virtuemart_product_id.'-'.JFilterOutput::stringURLSafe($formname)."'  
			WHERE `virtuemart_product_id`='".$virtuemart_product_id."'   ";
			$db->setQuery($q);
			if (!$db->query()) die($db->stderr(true));*/
			
			/*if($multilang_mode >0){ 					
				for($i = 0 ; $i < count( $active_languages ) ; $i++){
					$app->enqueueMessage($active_languages[$i]); //en-GB
					if( str_replace('_' , '-' , VMLANG) != strtolower( $active_languages[$i]) ){
						$q = "UPDATE `#__virtuemart_products_".strtolower( str_replace('-' , '_' , $active_languages[$i]) ) ."` 
						 SET  `product_s_desc`='".$db->escape($form_s_desc)."' , 
						 `product_desc`='".$db->escape($formdesc)."' , 
						 `product_name`='".$db->escape($formname)."' , 
						 `slug`='".strtolower(str_replace(' ','-', $db->escape($formname))).$virtuemart_product_id."'  
						WHERE `virtuemart_product_id`='".$virtuemart_product_id."'   ";
						$db->setQuery($q);
						if (!$db->query()) die($db->stderr(true));
					}
				}
			}*/
			if($multicat)
			{
				$q = "SELECT virtuemart_category_id FROM #__virtuemart_product_categories WHERE virtuemart_product_id='".$virtuemart_product_id."' ";
				$db->setQuery($q);
				$old_cats = $db->loadObjectList();
				foreach($old_cats as $old_cat)  // we delete removed cats
				{
					if( !in_array( $old_cat->virtuemart_category_id , $formcat ) )
					{
						$q = "DELETE FROM #__virtuemart_product_categories WHERE virtuemart_product_id='".$virtuemart_product_id."' AND virtuemart_category_id='".$old_cat->virtuemart_category_id."' "	;
						$db->setQuery($q);
						if (!$db->query()) die($db->stderr(true));
						
					}
				}	
				for ($i=0 ; $i < count($formcat) ; $i++)
				{
					$q = "SELECT COUNT(*) FROM #__virtuemart_product_categories WHERE virtuemart_product_id='".$virtuemart_product_id."' AND virtuemart_category_id='".$formcat[$i]."' ";
					$db->setQuery($q);
					$cat_allreadyin = $db->loadResult();
					if($cat_allreadyin<1) // we add new cats
					{
						$q = "INSERT INTO `#__virtuemart_product_categories` 
						( `virtuemart_product_id` , `virtuemart_category_id`   ) 
						VALUES 
						( '".$virtuemart_product_id."' , '".$formcat[$i]."'  )";
						$db->setQuery($q);
						if (!$db->query()) die($db->stderr(true));
					}
				}
			}
			else
			{
				$q ="UPDATE `#__virtuemart_product_categories` SET `virtuemart_category_id`='".$formcat."' WHERE `virtuemart_product_id`='".$virtuemart_product_id."' ";
				$db->setQuery($q);
				if (!$db->query()) die($db->stderr(true));
			}
			
			
			if( $formprice < $minimum_price )
			{
				$formprice = $minimum_price;
				JError::raiseNotice( 100, '<i class="vmv-icon-warning"></i> <b>'.JText::_('COM_VMVENDOR_VMVENADD_PRICECHANGED').' '.$minimum_price.'</b>');
			}
			
			if($formmanufacturer)
			{
				$q="SELECT COUNT(*) FROM #__virtuemart_product_manufacturers WHERE virtuemart_product_id='".$virtuemart_product_id."' ";
				$db->setQuery($q);
				$manuf = $db->loadResult();
				if($manuf>0)
					$q = "UPDATE #__virtuemart_product_manufacturers SET virtuemart_manufacturer_id='".$formmanufacturer."' WHERE virtuemart_product_id='".$virtuemart_product_id."'";
				else
					$q= "INSERT INTO #__virtuemart_product_manufacturers 
					(virtuemart_product_id , virtuemart_manufacturer_id) 
					VALUES ('".$virtuemart_product_id."' , '".$formmanufacturer."') ";	
				$db->setQuery($q);
				if (!$db->query()) die($db->stderr(true));
			}
			
			
			/////////////////////////////   3rd party custom plugins
			$formtags = $app->input->post->get('formtags','','RAW');
			$metakey = '';
			$limited_tags='';
			if($enable_vm2tags )
			{
				$q ="SELECT `virtuemart_custom_id` FROM `#__virtuemart_customs` WHERE `custom_element`='vm2tags' ";
				$db->setQuery($q);
				$virtuemart_custom_id = $db->loadResult();
				$septags = explode(',' ,$formtags); 
				$i=0;
				foreach ( $septags as $septag )
				{
					$i++;
					if ( $i <= $tagslimit && strlen($septag)>=2 && strlen($septag)<=20 )
					{
						
						if( $i > 1)
							$limited_tags .=',';
						$limited_tags .= $septag ;			
					}				
				}
				$plugin_tags = 'product_tags="'.$limited_tags.'"|';
				$q = "SELECT COUNT(*) FROM `#__virtuemart_product_customfields` 
				WHERE `virtuemart_product_id` ='".$virtuemart_product_id."' 
				AND `virtuemart_custom_id` ='".$virtuemart_custom_id."' ";
				$db->setQuery($q);
				$allready_tagged = $db->loadResult();
				if($allready_tagged >0)
				{
					$q = "UPDATE `#__virtuemart_product_customfields` 
					SET `customfield_params` = '".$db->escape($plugin_tags)."' 
					WHERE `virtuemart_product_id` ='".$virtuemart_product_id."' 
					AND `virtuemart_custom_id` ='".$virtuemart_custom_id."' ";
				}
				else
				{
					$q = "INSERT INTO `#__virtuemart_product_customfields` 
					( `virtuemart_product_id` , `virtuemart_custom_id` , `customfield_value` , `customfield_params` , `created_on` , `created_by`  ) 
					VALUES 
					('".$virtuemart_product_id."' , '".$virtuemart_custom_id."' , 'vm2tags' , '".$db->escape($plugin_tags)."' , '".date('Y-m-d H:i:s')."' , '".$user->id."' )";
				}
				
				$db->setQuery($q);
				if (!$db->query()) die($db->stderr(true));
				if($vm2tags_asmetakeywords)
						$metakey = $limited_tags;	
				if(count($septags) > $tagslimit)
				{
					JError::raiseNotice( 100, '<i class="vmv-icon-warning"></i> <b>'.$tagslimit.'</b> '.JText::_('COM_VMVENDOR_VMVENADD_FIRSTTAGSONLY').'');	
				}	
			}
			
			if($enable_vm2geolocator && $latitude!='' && $longitude!='')
			{
				$q="SELECT `virtuemart_custom_id` FROM `#__virtuemart_customs` WHERE `custom_element` = 'vm2geolocator' ";
				$db->setQuery($q);
				$virtuemart_custom_id = $db->loadResult();
				if($virtuemart_custom_id!='')
				{
					//check if product has coordinates yet. If yes->update, if not->insert
					$q ="SELECT `customfield_params` FROM `#__virtuemart_product_customfields`  WHERE `virtuemart_product_id` ='".$virtuemart_product_id."' AND `customfield_value`='vm2geolocator' ";
					$db->setQuery($q);
					$custom_param = $db->loadResult();
					if($custom_param!='')
					{ // we update product coordinates
						$q ="UPDATE `#__virtuemart_product_customfields` SET `customfield_params` ='latitude=\"".$latitude."\"|longitude=\"".$longitude."\"|zoom=\"".$zoom."\"|maptype=\"".$maptype."\"|' , 
						modified_by='".$user->id."' ,
						modified_on='".date('Y-m-d H:i:s')."' 
						
						 WHERE `customfield_value`='vm2geolocator' AND `virtuemart_product_id`=".$virtuemart_product_id." AND `virtuemart_custom_id` = ".$virtuemart_custom_id." ";
						$db->setQuery($q);
						if (!$db->query()) die($db->stderr(true));
					
					}
					else
					{ // we insert 
						$q="INSERT INTO `#__virtuemart_product_customfields` 
					(`virtuemart_product_id`,`virtuemart_custom_id`,`customfield_value`,`customfield_params`,`published`,`created_on`,`created_by`)
					VALUES 
					('".$virtuemart_product_id."','".$virtuemart_custom_id."','vm2geolocator','latitude=\"".$latitude."\"|longitude=\"".$longitude."\"|zoom=\"".$zoom."\"|maptype=\"".$maptype."\"|','1','".date('Y-m-d H:i:s')."','".$user->id."')";
						$db->setQuery($q);
						if (!$db->query()) die($db->stderr(true));
					}
				}
			}
			
			
			if($enable_embedvideo)
			{
				$q = "SELECT vc.virtuemart_custom_id, vc.custom_title, vc.custom_tip
					FROM #__virtuemart_customs vc
					LEFT JOIN #__extensions e ON e.extension_id = vc.custom_jplugin_id 
					WHERE vc.custom_element='embedvideo' 
					AND e.enabled='1' ";
					$db->setQuery($q);
					$vid_fields =  $db->loadObjectList();
					//for($i=0;$i<count($vid_fields);$i++)
				foreach($vid_fields as $vid_field)
				{
					$vid_url = $app->input->post->get('embedvideo_'.$vid_field->virtuemart_custom_id , '', 'raw');
					if( substr($vid_url,0,32 )== 'https://www.youtube.com/watch?v=' )
					{
						$q = "SELECT virtuemart_custom_id FROM `#__virtuemart_product_customfields`  
						WHERE virtuemart_product_id='".$virtuemart_product_id."' 
						AND virtuemart_custom_id='".$vid_field->virtuemart_custom_id."' 
						AND customfield_value='embedvideo'   ";
						$db->setQuery($q);
						$is_in =   $db->loadResult();
						if( !$is_in  )
						{
							$q="INSERT INTO `#__virtuemart_product_customfields` 						
				(`virtuemart_product_id`,`virtuemart_custom_id`,`customfield_value`,`customfield_params`,`published`,`created_on`,`created_by`)
					VALUES 
					('".$virtuemart_product_id."','".$vid_field->virtuemart_custom_id."','embedvideo','video_url=\"".addslashes($vid_url)."\"|','1','".date('Y-m-d H:i:s')."','".$user->id."')";
							$db->setQuery($q);
							if (!$db->query()) die($db->stderr(true));	
						}
						else
						{
							$q="UPDATE `#__virtuemart_product_customfields` SET `customfield_params` ='video_url=\"".addslashes($vid_url)."\"|',
							 modified_by='".$user->id."' , 	modified_on='".date('Y-m-d H:i:s')."'
							 WHERE `customfield_value`='embedvideo' AND `virtuemart_product_id`=".$virtuemart_product_id." AND `virtuemart_custom_id` = ".$vid_field->virtuemart_custom_id."";
							$db->setQuery($q);
							if (!$db->query()) die($db->stderr(true));	
						}
					}
				}	
			}
				
			$q = "UPDATE `#__virtuemart_products_".$dblang."` 
			 SET  `product_s_desc`='".$db->escape($form_s_desc)."' , 
			 `product_desc`='".$db->escape($formdesc)."' , 
			 `product_name`='".$db->escape($formname)."' , 
			 `slug`='".$virtuemart_product_id.'-'.JFilterOutput::stringURLSafe($formname)."'  ,
			 metadesc ='".$db->escape($form_s_desc)."' , 
			 metakey = '".$db->escape($metakey)."' 
			WHERE `virtuemart_product_id`='".$virtuemart_product_id."'   ";
			$db->setQuery($q);
			if (!$db->query()) die($db->stderr(true));
			
			
	
			$q = "UPDATE `#__virtuemart_product_prices` 
			SET `product_price`='".$formprice."' , 
			`modified_on`='".date('Y-m-d H:i:s')."' , 
			`modified_by`='".$user->id."' WHERE `virtuemart_product_id`='".$virtuemart_product_id."'";
			$db->setQuery($q);
			if (!$db->query()) die($db->stderr(true));	
				
			for ($i=1; $i <= $max_imagefields ;$i++)
			{ ////////////// images
				$imgisvalid = 1;
				$image = $app->input->files->get('image'.$i);
				$image['name'] = JFile::makeSafe($image['name']);
				if($image['name']!='')
				{
					$infosImg = getimagesize($image['tmp_name']);							
					//if ( (substr($image['type'],0,5) != 'image' || $infosImg[0] > $maximgside || $infosImg[1] > $maximgside )){
					if ( (substr($image['type'],0,5) != 'image'  ))
					{
						JError::raiseWarning( 100, JText::_('COM_VMVENDOR_VMVENADD_IMGEXTNOT') );
						$imgisvalid = 0;
					}
					$product_image = strtolower($formsku ."_".$image['name']);
					$product_image = str_replace(' ','', $product_image );	
																		
					$target_imagepath = JPATH_BASE . '/' . $image_path . $product_image;
					if($imgisvalid){
						if( JFile::upload( $image['tmp_name'] , $target_imagepath )  )
						{
							$app->enqueueMessage( '<i class="vmv-icon-ok"></i> '.JText::_('COM_VMVENDOR_VMVENADD_IMAGEUPLOADRENAME_SUCCESS').' '.$product_image);
						}
						else
							JError::raiseWarning( 100, JText::_('COM_VMVENDOR_VMVENADD_IMAGEUPLOAD_ERROR') );
					}
							
										// we store thumb
					$ext = JFile::getExt( $image['name'] ) ; 
					$ext = strtolower($ext);
					$ext = str_replace('jpeg','jpg',$ext);
								//SWITCHES THE IMAGE CREATE FUNCTION BASED ON FILE EXTENSION
					switch(strtolower($ext))
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
					if($product_image!='' && $imgisvalid )
					{		
						list($width,  $height) = getimagesize($target_imagepath); 
						/*if($width>=$height){ 
							$resizedH = ( $vmconfig_img_width * $height) / $width;
							$thumb = imagecreatetruecolor( $vmconfig_img_width ,  $resizedH);
							imagecopyresampled( $thumb,  $source,  0,  0,  0,  0,  $vmconfig_img_width ,  $resizedH,  $width,  $height );
						}
						else{
							$resizedW = ( VmConfig::get('img_height') * $width) / $height;
							$thumb = imagecreatetruecolor($resizedW,  VmConfig::get('img_height') );
							imagecopyresampled($thumb ,  $source ,  0 ,  0 ,  0 ,  0 ,  $resizedW,  VmConfig::get('img_height') ,  $width,  $height );
						}*/
						
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
										imagejpeg($largeone, JPATH_BASE . '/' . $image_path  .$product_image,  $thumbqual);
									break;
									case 'jpeg':
										imagejpeg($largeone, JPATH_BASE . '/' . $image_path  .$product_image,  $thumbqual);
									break;
									case 'png':
										imagepng($largeone, JPATH_BASE . '/' . $image_path  .$product_image);
									break;
									case 'gif':
										imagegif($largeone, JPATH_BASE . '/' . $image_path  .$product_image);
									break;
								} 
								imagedestroy($largeone);
						//imagejpeg($thumb, JPATH_BASE . '/' . $thumb_path  .$product_image,  $thumbqual);
						
						if($width>=$height)
						{ 
									$resizedH = ( $vmconfig_img_width * $height) / $width;
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
										imagejpeg($thumb, JPATH_BASE . '/' . $thumb_path .'/' .$product_image,  $thumbqual);
									break;
									case 'jpeg':
										imagejpeg($thumb, JPATH_BASE . '/' . $thumb_path .'/' .$product_image,  $thumbqual);
									break;
									case 'png':
										imagepng($thumb, JPATH_BASE . '/' . $thumb_path .'/' .$product_image);
									break;
									case 'gif':
										imagegif($thumb, JPATH_BASE . '/' . $thumb_path .'/' .$product_image);
									break;
								} 
						imagedestroy($thumb);
						
						
						
						if( $app->input->post->get('media_id'.$i )!='')
						{ // we updated the picture
							// delete all media file
							$q = "SELECT `file_url` , `file_url_thumb` FROM `#__virtuemart_medias` WHERE `virtuemart_media_id`='".$app->input->post->get('media_id'.$i)."' ";
							$db->setQuery($q);
							$media_files = $db->loadRow();
							$image_url 		= $media_files[0];
							$thumb_url		= $media_files[1];
							if($image_url !=  $image_path.JFile::makeSafe($product_image) )
							{ // only delete old file if new filename and old are diferent. If same file has allready been overwritten
								if($image_url!='')
									JFile::delete($image_url);
								if($thumb_url!='')
									JFile::delete($thumb_url);
							}
							$q ="UPDATE `#__virtuemart_medias` SET 
							`file_title`='".JFile::makeSafe($product_image)."' , 
							 `file_mimetype`='".$image['type']."' , 
							 `file_url` = '".$image_path.JFile::makeSafe($product_image)."' , 
							 `file_url_thumb` ='".$thumb_path.JFile::makeSafe($product_image)."' ,
							 `modified_on`='".date('Y-m-d H:i:s')."' , 
							 `modified_by` ='".$user->id."' 
							 WHERE `virtuemart_media_id` ='".$app->input->post->get('media_id'.$i)."' ";
							$db->setQuery($q);
							if (!$db->query()) die($db->stderr(true));
						}
						else
						{ // we add a new file					
							$q = "INSERT INTO `#__virtuemart_medias` 
							( `virtuemart_vendor_id` , `file_title` , `file_mimetype` , `file_type` , `file_url` , `file_url_thumb` , `file_is_product_image` , `published` , `created_on` , `created_by`)
							VALUES
							(  '".$virtuemart_vendor_id."' , '".$db->escape($product_image)."' , '".$image['type']."' , 'product' , '".$image_path.JFile::makeSafe($product_image)."' , '".$thumb_path.JFile::makeSafe($product_image)."' , '1', '1' , '".date('Y-m-d H:i:s')."' , '".$user->id."' )";
							$db->setQuery($q);
							if (!$db->query()) die($db->stderr(true));
							$virtuemart_media_id = $db->insertid();
							$q = "INSERT INTO `#__virtuemart_product_medias` 
							( `virtuemart_product_id` , `virtuemart_media_id` , `ordering` )
							VALUES
							(  '".$virtuemart_product_id."' , '".$virtuemart_media_id."' , '".$i."'  )";
							$db->setQuery($q);
							if (!$db->query()) die($db->stderr(true));
						}
					}
				}
				if($i == 1 && $flickr_autopost && $flickr_autopost_email!='')
					$flickr_img = $image_path.JFile::makeSafe($product_image);
			}
		
			////////////////////////////// Core Custom fields support
					
					if($enable_corecustomfields)
					{
						$q ="SELECT `virtuemart_custom_id` , `custom_parent_id` , `virtuemart_vendor_id` , `custom_jplugin_id` , `custom_title` , `custom_tip` , `custom_value`, `custom_desc` , `field_type` , `is_list` , `shared`  
						FROM `#__virtuemart_customs`
						WHERE `custom_jplugin_id`='0'  AND field_type !='R' AND field_type!='Z'
						AND `admin_only`='0' 
						AND `published`='1' 
						AND field_type!='R' AND field_type!='Z' 
						ORDER BY `ordering` ASC , `virtuemart_custom_id` ASC ";
						//						AND `custom_element`!='' 
						$db->setQuery($q);
						$core_custom_fields	= $db->loadObjectList();
						
						$i = 0;
						foreach ($core_custom_fields as $core_custom_field)
						{
							$i++;
							if( $app->input->post->get( 'corecustomfield_'.$i  ) !='')
							{
								$q ="SELECT count(*) FROM #__virtuemart_product_customfields 
								WHERE `virtuemart_product_id` ='".$virtuemart_product_id."' 
								AND `virtuemart_custom_id` ='".$core_custom_field->virtuemart_custom_id."' ";
								$db->setQuery($q);
								$exists = $db->loadResult();
								if($exists>0)
								{
									$q ="UPDATE `#__virtuemart_product_customfields`
									SET 
									`customfield_value`='".$db->escape( $app->input->post->get( 'corecustomfield_'.$i ,'','string') )."' ,
									`modified_on` ='".date('Y-m-d H:i:s')."' ,
									`modified_by` = '".$user->id."' 
									WHERE `virtuemart_product_id` ='".$virtuemart_product_id."' 
									AND `virtuemart_custom_id` ='".$core_custom_field->virtuemart_custom_id."' ";
									$db->setQuery($q);
									if (!$db->query()) die($db->stderr(true));
								}
								else
								{
									$q ="INSERT INTO #__virtuemart_product_customfields 
									( virtuemart_product_id , virtuemart_custom_id , customfield_value, published , created_on , created_by , ordering )
									VALUES
									(  '".$virtuemart_product_id."' , '".$core_custom_field->virtuemart_custom_id."' , '".$db->escape( $app->input->post->get( 'corecustomfield_'.$i ,'','string') )."' , '1' , '".date('Y-m-d H:i:s')."' , '".$user->id."' , '".$i."'  )";
									$db->setQuery($q);
									if (!$db->query()) die($db->stderr(true));	
								}
							}
							else
							{
								$q = "DELETE FROM `#__virtuemart_product_customfields` 
								WHERE  virtuemart_product_id='".$virtuemart_product_id."' 
								AND virtuemart_custom_id='".$core_custom_field->virtuemart_custom_id."' 
								AND created_by='".$user->id."' ";
								$db->setQuery($q);
								if (!$db->query()) die($db->stderr(true));
							}	
						}
					}
					
			
			if ($enablerss)
			{							
				require_once JPATH_COMPONENT.'/helpers/functions.php';
				VmvendorFunctions::updateRSS(2  , $virtuemart_vendor_id );	 // 2 for deletion										
			}
		
			$product_url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$virtuemart_product_id.'&virtuemart_category_id='.$formcat.'&Itemid='.$vmitemid);

			if($autopublish && $published )
			{
				if( $announceupdate )
				{
					if($profileman=='js' )
					{// Jomsocial activity feed
						$jspath = JPATH_ROOT .  '/components/com_community';
						include_once $jspath. '/libraries/core.php';
						CFactory::load('libraries', 'activities');          
						$act = new stdClass();
						$act->cmd    = 'wall.write';
						$act->actor    = $user->id;
						$act->target    = 0; // no target
						$act->title    = JText::_( 'COM_VMVENDOR_JOMSOCIAL_HASJUSTEDITED').' <a href="'.$product_url.'">'.stripslashes( ucfirst( $formname ) ).'</a>' ;
						$output = '';
						if($enablerss)
							$output .='<div><a href="'.$juri.'media/vmvendorss/'.$user->id.'.rss" ><i class="vmv-icon-feed"></i> '.JText::_('COM_VMVENDOR_VENDORRSS').'</a></div>';
						$act->content    = $output;
						$act->app    = 'vmvendor.productedition';
						$act->cid    = 0;
						$act->comment_id	= CActivities::COMMENT_SELF;
						$act->comment_type	= 'vmvendor.productedition';
						$act->like_id		= CActivities::LIKE_SELF;		
						$act->like_type		= 'vmvendor.productedition';
						CActivityStream::add($act);
					}
					if($profileman=='es')
					{
						jimport( 'joomla.filesystem.file' );
						require_once  JPATH_ROOT . '/administrator/components/com_easysocial/includes/foundry.php';
						$config 	= Foundry::config();
						$naming = $config->get( 'users.displayName' );  // username or realname
						if($naming=='realname')
							$naming = 'name';
						/*if($product_image!='')
						{
							if (  !class_exists( 'VmConfig' )  )
								require JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php';
							VmConfig::loadConfig();
							$thumb_path 	= VmConfig::get('media_product_path').'resized';
							 $thumb_url =   $thumb_path .'/' .$product_image;
						}*/
						 
						$form_s_desc		 =  $app->input->post->get('form_s_desc',null,'string');
						$formname 			= 	$app->input->post->get('formname',null,'string');
						$product_url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$virtuemart_product_id.'&virtuemart_category_id='.$formcat.'&Itemid='.$vmitemid);
						
						require_once JPATH_ROOT . '/administrator/components/com_easysocial/includes/foundry.php' ;
						$stream     = Foundry::stream();
						$template   = $stream->getTemplate();
						$my         = Foundry::user();
						$template->setActor( $user->id , 'user' );
						$template->setVerb( 'edit' );
	
						$content = '';
						if($form_s_desc!='')
							$content .= '<div style="float:left">'.$form_s_desc.'</div>';
						if($enablerss)
							$content .='<div style="clear:both"></div><a href="media/vmvendorss/'.$user->id.'.rss" >
						<i class="vmv-icon-feed"></i> '.JText::_('COM_VMVENDOR_VENDORRSS').'</a>';
						//$template->setcontextParams( $content );
						$contextParams =  array( 
							'product_name'=>stripslashes( ucfirst($formname) ),
							'product_url'=>$product_url ,
							//'thumb_url'=>$thumb_url,
							'form_s_desc'=>$form_s_desc,
							'userid'=>$user->id,
						);
						$template->setContext( $virtuemart_product_id  , 'product' , $contextParams );
			
						$template->setType( 'full' );
						$streamItem = $stream->add($template);
					}
				}
			}
			if( $isnew && $autopublish)
			{
				if($emailnotify_added && $to!= NULL )
				{
					require_once JPATH_COMPONENT.'/helpers/functions.php';
					VmvendorFunctions::emailNotifyAddition( $virtuemart_product_id, $formcat, $autopublish);
				}
				if($flickr_autopost && $flickrcheckbox=='on' 
				&& $flickr_autopost_email!='' && $flickr_img !='' 
				)
				{
					require_once JPATH_COMPONENT.'/helpers/functions.php';
					VmvendorFunctions::emailFlickr( $virtuemart_product_id , $formname , $form_s_desc , $limited_tags , $product_url , $flickr_img );
				}
				if($emailnotify_added && $to!= NULL )
				{
					require_once JPATH_COMPONENT.'/helpers/functions.php';
					VmvendorFunctions::emailNotifyAddition( $virtuemart_product_id, $formcat);
				}
				if( $profileman=='js' OR $profileman=='es' ) 
				{
					require_once JPATH_COMPONENT.'/helpers/functions.php';
					if($profileman=='js' )// Add profile application + activity streams
						VmvendorFunctions::doJomsocialActions( $virtuemart_product_id,$formcat, $product_image);
					elseif($profileman=='es' ) // Activity Stream
						VmvendorFunctions::doEasysocialActions($virtuemart_product_id,$formcat , $product_image);
				}
			}
			
			
			if( ($emailnotify_updated && $to!= NULL ) || !$autopublish) // Email Notification when new product edited
			{
				$subject = JText::_('COM_VMVENDOR_EMAIL_HELLO_EDITED')." ".$juri." ".JText::_('COM_VMVENDOR_EMAIL_BYUSER')." ".$user->$naming;				
				$mailurl= $juri.'index.php?option=com_vmvendor&view=vendorprofile&userid='.$user->id.'&Itemid='.$profileitemid;									
				$body = JText::_('COM_VMVENDOR_EMAIL_VISIT_EDITED')." <a href='".$juri."index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=".$virtuemart_product_id."&virtuemart_category_id=".$formcat."&Itemid=".$vmitemid."' >"
					.JText::_('COM_VMVENDOR_EMAIL_HERE')."</a>."
					.JText::_('COM_VMVENDOR_EMAIL_SUBMITTEDBY')." <a href='"
					.$mailurl."'>".$user->$naming."</a>. ";
				if(!$autopublish)
					$body .=JText::_('COM_VMVENDOR_EMAIL_BUTFIRSTREVIEW').' <a href="'.$juri.'administrator/index.php?pshop_mode=admin&page=product.product_list&option=com_virtuemart">'.JText::_('COM_VMVENDOR_EMAIL_SHOPADMIN').'</a>.';												
				$mailerror = '<font color="red"><i class="vmv-icon-cancel"></i> <b>'.JText::_('COM_VMVENDOR_EMAIL_FAIL').'</b></font>';							
				$message = JFactory::getMailer();
				$message->addRecipient($to);
				$message->setSubject($subject);
				$message->setBody($body);
				$config = JFactory::getConfig();
				$sender = array( 
					$config->get( 'config.mailfrom' ),
					$config->get( 'config.fromname' )
				);				
				$message->isHTML(true);
				$message->Encoding = 'base64';
				$message->setSender($sender);
				$message->addRecipient( $config->get( 'config.mailfrom' ) );
				$sent = $message->send();
				if ($sent != 1) 
					echo  $mailerror;		}
			}
			
			if($oldprice == 0 && $formprice >0  &&  $freefiles_folder=='media')
			{ // becomes commercial, we move downloads to safe path
				// get downloadable files
				if($forsalefiles_plugin=='istraxx')
					$q = "SELECT `virtuemart_custom_id` FROM `#__virtuemart_customs` WHERE `custom_element`='st42_download' ";
				elseif($forsalefiles_plugin=='ekerner')
					$q = "SELECT `virtuemart_custom_id` FROM `#__virtuemart_customs` WHERE `custom_element`='downloadable' ";
				$db->setQuery($q);
				$virtuemart_custom_id = $db->loadresult();
				
				$q ="SELECT vm.`virtuemart_media_id` , vm.`file_title` , vm.`file_url`  
				FROM `#__virtuemart_medias` vm 
				LEFT JOIN `#__virtuemart_product_medias` vpm ON vpm.`virtuemart_media_id` = vm.`virtuemart_media_id` 
				WHERE vpm.`virtuemart_product_id` ='".$virtuemart_product_id."' 
				AND vm.`file_is_downloadable`='1' 
				AND SUBSTRING(vm.file_title, 1, 9 ) NOT LIKE 'vmvsample' ";
				//JError::raiseNotice( 100, $q);
				$db->setQuery($q);
				$filestomove = $db->loadObjectList();
				foreach($filestomove as $filetomove)
				{
					JFile::copy( $filetomove->file_url , $safepath.$filetomove->file_title);
					JFile::delete( $filetomove->file_url );
					$q= "UPDATE `#__virtuemart_medias` 
					SET `file_url`='".$safepath.$filetomove->file_title."' ,
					`file_is_downloadable`='0' ,
					`file_is_forSale`='1' 
					WHERE `virtuemart_media_id`='".$filetomove->virtuemart_media_id."' ";
					$db->setQuery($q);
					if (!$db->query()) die($db->stderr(true));
					$q ="DELETE FROM `#__virtuemart_product_medias` 
					WHERE `virtuemart_media_id`='".$filetomove->virtuemart_media_id."' 
					AND `virtuemart_product_id`='".$virtuemart_product_id."' ";
					$db->setQuery($q);
					if (!$db->query()) die($db->stderr(true));
					if($forsalefiles_plugin=='istraxx')
					{
						$q ="INSERT INTO `#__virtuemart_product_customfields` 
						( `virtuemart_product_id` , `virtuemart_custom_id` , `customfield_value` , `customfield_params` , `created_on` , `created_by` , `modified_on` , `modified_by` )
						VALUES
						('".$virtuemart_product_id."' , '".$virtuemart_custom_id."' , 'st42_download' , '{\"media_id\":\"".$filetomove->virtuemart_media_id."\",\"stream\":\"".$stream."\",\"maxspeed\":\"".$maxspeed."\",\"maxtime\":\"".$maxtime."\"}' , '".date('Y-m-d H:i:s')."' , '".$user->id."' , '".date('Y-m-d H:i:s')."' , '".$user->id."'  )";
					}
					elseif($forsalefiles_plugin=='ekerner')
					{
						$downloadable_expires_quantity = $cparams->get('downloadable_expires_quantity','0');
						$downloadable_expires_period = $cparams->get('downloadable_expires_period','days');
						$downloadable_order_states = $cparams->get('downloadable_order_states');
						$statuses_string ='';
						for($e=0;$e<count($downloadable_order_states);$e++)
						{
							$statuses_string .= '\"'.$downloadable_order_states[$e].'\"';
							if($e<count($downloadable_order_states)-1)
								$statuses_string .= ',';
						}			
						$q ="INSERT INTO `#__virtuemart_product_customfields` 
						( `virtuemart_product_id` , `virtuemart_custom_id` , `customfield_value` , `customfield_params` , `created_on` , `created_by` , `modified_on` , `modified_by` )
						VALUES
						('".$virtuemart_product_id."' , '".$virtuemart_custom_id."' , 'downloadable' , 'downloadable_media_id=\"".$filetomove->virtuemart_media_id."\"|downloadable_order_states=[".$statuses_string."]|downloadable_expires_quantity=\"".$downloadable_expires_quantity."\"|downloadable_expires_period=\"".$downloadable_expires_period."\"|yu' , '".date('Y-m-d H:i:s')."' , '".$user->id."' , '".date('Y-m-d H:i:s')."' , '".$user->id."'  )";
					}
					$db->setQuery($q);
					if (!$db->query()) die($db->stderr(true));
					
					if( $resample_commercial_mp3 && strtolower( substr($filetomove->file_url,-4))=='.mp3' )
					{
									// create the vmvsample
									require_once JPATH_BASE.'/components/com_vmvendor/classes/class.mp3.php'; 
										$mp3 = new mp3; 
										/* cut the mp3 file 
												cut_mp3($file_input, $file_output, $startindex = 0, $endindex = -1, $indextype = 'frame', $cleantags = false) 
												it will return true or false*/ 
										$vmvsample_path = $image_path. "vmvsample_".$filetomove->file_title;
										$target_filepath =$safepath.$filetomove->file_title;
										//   $mp3->cut_mp3( $file['tmp_name'] , $vmvsample_path , 0, 30, 'second', false);*
										$mp3->cut_mp3( $target_filepath , $vmvsample_path , $mp3sample_start , $mp3sample_end , 'second', false);
										$q = "INSERT INTO `#__virtuemart_medias` 
										( `virtuemart_vendor_id` , `file_title` , `file_description` , `file_mimetype` , `file_type` , `file_url` , `file_url_thumb` , `file_is_downloadable` , `file_is_forSale` , `published` , `created_on` , `created_by` )
										VALUES
										(  '".$virtuemart_vendor_id."' , 'vmvsample_".$filetomove->file_title."' , '".$db->escape($formname)." ".JText::_('COM_VMVENDOR_SAMPLETRACK')." ".$i."' , 'audio/mp3' , 'product' , '".addslashes($vmvsample_path)."' , '' , '1', '0', '1' , '".date('Y-m-d H:i:s')."' , '".$user->id."' )";																///addslashes($target_filepath)
										$db->setQuery($q);
										if (!$db->query()) die($db->stderr(true));
										$virtuemart_sample_media_id = $db->insertid();
										$q = "INSERT INTO `#__virtuemart_product_medias` 
										( `virtuemart_product_id` , `virtuemart_media_id`, `ordering` )
										VALUES
										(  '".$virtuemart_product_id."' , '".$virtuemart_sample_media_id."' , '". ($i+1) ."' )";
										$db->setQuery($q);
										if (!$db->query()) die($db->stderr(true));
										$app->enqueueMessage( '<i class="vmv-icon-ok"></i> '.JText::_('COM_VMVENDOR_PREVIEWCREATEDSUCCESSFULLY').' '. ($mp3sample_end - $mp3sample_start) .'"');
									
									
								}
		
				}
				if(count($filestomove)>0)
					JError::raiseNotice( 100, '<i class="vmv-icon-warning"></i> <b>'.JText::_('COM_VMVENDOR_FILESMADESAFE').'</b>');
				if($reset_onpricestatus)
				{
					$q ="DELETE FROM `#__virtuemart_ratings` WHERE `virtuemart_product_id`='".$virtuemar_product_id."' ";
					$db->setQuery($q);
					if (!$db->query()) die($db->stderr(true));
					$q ="DELETE FROM `#__virtuemart_rating_reviews` WHERE `virtuemart_product_id`='".$virtuemar_product_id."' ";
					$db->setQuery($q);
					if (!$db->query()) die($db->stderr(true));
					$q ="DELETE FROM `#__virtuemart_rating_votes` WHERE `virtuemart_product_id`='".$virtuemar_product_id."' ";
					$db->setQuery($q);
					if (!$db->query()) die($db->stderr(true));	
					JError::raiseNotice( 100, '<i class="vmv-icon-warning"></i> <b>'.JText::_('COM_VMVENDOR_REVIEWSRESET').'</b>');
				}	
			}
			elseif($oldprice > 0 && $formprice == '0'  && $freefiles_folder=='media')
			{
				$q ="SELECT vm.`virtuemart_media_id` , vm.`file_title` , vm.`file_url` , vm.`file_is_forSale` ,  vpc.`customfield_value`
				FROM `#__virtuemart_medias` vm 
				LEFT JOIN `#__virtuemart_product_customfields` vpc ON vpc.`customfield_params` LIKE CONCAT('%' , CONCAT('\"media_id\":\"' , vm.`virtuemart_media_id` , '\"') , '%' )
				WHERE vpc.`virtuemart_product_id` ='".$virtuemart_product_id."'  ";
				
				$db->setQuery($q);
				$filestomove = $db->loadObjectList();
				
				$ii=1;
				foreach($filestomove as $filetomove)
				{
					if( $filetomove->file_is_forSale && ( $filetomove->customfield_value=='st42_download'  OR $filetomove->customfield_value=='downloadable' )   )
					{
						JFile::copy( $filetomove->file_url , $image_path.$filetomove->file_title); //copie to media folder
						JFile::delete($filetomove->file_url);   //deletes forsale file
						$q= "UPDATE `#__virtuemart_medias` 
						SET `file_url`='".$image_path.$filetomove->file_title."' ,
						`file_description`='".JText::_('COM_VMVENDOR_FILE').' '.$ii."' ,
						`file_is_downloadable`='1' ,
						`file_is_forSale`='0' 
						WHERE `virtuemart_media_id`='".$filetomove->virtuemart_media_id."' ";
						$db->setQuery($q);
						if (!$db->query()) die($db->stderr(true));
						$q ="DELETE FROM `#__virtuemart_product_customfields`
						WHERE `customfield_value`='".$filetomove->customfield_value."' 
						AND `virtuemart_product_id`='".$virtuemart_product_id."' ";
						$db->setQuery($q);
						if (!$db->query()) die($db->stderr(true));
						
						$q = "INSERT INTO `#__virtuemart_product_medias` 
						( `virtuemart_product_id` , `virtuemart_media_id`, `ordering` ) 
						VALUES
						( '".$virtuemart_product_id."' , '".$filetomove->virtuemart_media_id."' ,'". ($ii + 1) ."' )";
						$db->setQuery($q);
						if (!$db->query()) die($db->stderr(true));
					
						
						
						if( $resample_commercial_mp3  &&  strtolower(substr($filetomove->file_url,-4))=='.mp3' )
						{ // delete mp3 preview 
							JFile::delete('images/stories/virtuemart/product/vmvsample_'.$filetomove->file_title);
							$q = "SELECT virtuemart_media_id FROM #__virtuemart_medias WHERE file_title ='vmvsample_".$filetomove->file_title."' ";
							$db->setQuery($q);
							$media_id_to_delete = $db->loadresult();
							$q= "DELETE FROM #__virtuemart_medias WHERE virtuemart_media_id='".$media_id_to_delete."' ";
							$db->setQuery($q);
							if (!$db->query()) die($db->stderr(true));
							$q= "DELETE FROM #__virtuemart_product_medias WHERE virtuemart_media_id='".$media_id_to_delete."' ";
							$db->setQuery($q);
							if (!$db->query()) die($db->stderr(true));
						}
					}
					$ii++;
				}
				if(count($filestomove)>0)
					JError::raiseNotice( 100, '<i class="vmv-icon-warning"></i> <b>'.JText::_('COM_VMVENDOR_FILESMADEDOWNLOADABLE').'</b>');
			}
			if($published)
			{
				$app->enqueueMessage( '<i class="vmv-icon-ok"></i> '.JText::_('COM_VMVENDOR_PRODUCTUPDATED'));
			}
			else
				JError::raiseNotice( 100, '<i class="vmv-icon-clock"></i> <b>'.JText::_('COM_VMVENDOR_TOBEREVIEWD').'</b>');
		}
		if (isset($_POST['formsku']) && $user->id == 0) // user has been inactive for too long and session time out
			JError::raiseWarning( 100,  JText::_('COM_VMVENDOR_UPLOAD_TIMEOUT') );
		$app->redirect( 'index.php?option=com_vmvendor&view=vendorprofile&Itemid='.$this->getVendorprofileItemid() );
		//$app->redirect( 'index.php?option=com_vmvendor&view=editproduct&productid='.$virtuemart_product_id.'&Itemid='.$app->input->get('Itemid') );
	}	
	
	function getVendorprofileItemid()
	{
		$lang = JFactory::getLanguage();
		$db 	= JFactory::getDBO();
		$q = "SELECT `id` FROM `#__menu` 
		WHERE `link`='index.php?option=com_vmvendor&view=vendorprofile' 
		AND `type`='component'  
		AND ( language ='".$lang->getTag()."' OR language='*') AND published='1'  AND access='1' ";
		$db->setQuery($q);
		return $profile_itemid = $db->loadResult();
	
	}
}
?>