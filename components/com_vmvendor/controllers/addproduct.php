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

class VmvendorControllerAddproduct extends VmvendorController {

    /**
     * Custom Constructor
     */
    function __construct() {
        parent::__construct();
    }

    public function addproduct() {
        $user = JFactory::getUser();
        $db = JFactory::getDBO();
        $doc = JFactory::getDocument();
        $juri = JURI::base();
        $app = JFactory::getApplication();
        if (!class_exists('VmConfig'))
            require JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php';
        VmConfig::loadConfig();

        $image_path = VmConfig::get('media_product_path');
        $safepath = VmConfig::get('forSale_path');
        $vmconfig_img_width = VmConfig::get('img_width');
        if (!$vmconfig_img_width)
            $vmconfig_img_width = 90;
        $cparams = JComponentHelper::getParams('com_vmvendor');
        $multilang_mode = $cparams->get('multilang_mode', 0);

        if ($multilang_mode > 0) {
            $active_languages = VmConfig::get('active_languages'); //en-GB
        }

        $profileman = $cparams->get('profileman');
        $naming = $cparams->get('naming', 'username');
        $vmitemid = $cparams->get('vmitemid', '103');
        $profileitemid = $cparams->get('profileitemid', '2');
        $multicat = 1;//$cparams->get('multicat', 0);
        $autopublish = $cparams->get('autopublish', 1);

        require_once JPATH_COMPONENT . '/helpers/getvendorplan.php';
        $vendor_plan = VmvendorHelper::getvendorplan($user->id);
        $vendor_products_count = VmvendorHelper::countVendorProducts($user->id);
        $plan_max_img = $vendor_plan->max_img;
        $plan_max_files = $vendor_plan->max_files;
        if ($vendor_plan->autopublish != '')
            $autopublish = $vendor_plan->autopublish;
        if ($autopublish == '2')
            $autopublish = '0';


        if ($vendor_plan->max_products > 0 && $vendor_products_count >= $vendor_plan->max_products) {
            JError::raiseWarning(100, sprintf(JText::_('COM_VMVENDOR_PLAN_MAXPRODREACHED'), $vendor_plan->max_products, $vendor_plan->title));
            return false;
        }

        // echo '<pre>'; print_r($cparams); die;
        $enablerss = $cparams->get('enablerss', 1);
        $emailnotify_addition = $cparams->get('emailnotify_addition', 0);
        $to = $cparams->get('to');
        $flickr_autopost = $cparams->get('flickr_autopost', 0);
        $flickr_autopost_email = $cparams->get('flickr_autopost_email');
        $flickr_img = '';

        $maxfilesize = $cparams->get('maxfilesize', '4000000'); //4 000 000 bytes   =  4M
        $max_imagefields = $cparams->get('max_imagefields', 4);
        if ($plan_max_img)
            $max_imagefields = $plan_max_img;

        $max_filefields = $cparams->get('max_filefields', 4);
        if ($plan_max_files)
            $max_filefields = $plan_max_files;


        $maximgside = $cparams->get('maximgside', '600');
        $thumbqual = $cparams->get('thumbqual', 70);
        $enable_sdesc = $cparams->get('enable_sdesc', 1);
        $wysiwyg_prod = $cparams->get('wysiwyg_prod', 0);
        $enablefiles = $cparams->get('enablefiles', 1);
        $enableweight = $cparams->get('enableweight', 1);
        $weightunits = $cparams->get('weightunits');
        $enableprice = $cparams->get('enableprice', 1);
        $enablestock = $cparams->get('enablestock', 1);
        $cat_suggest = $cparams->get('cat_suggest', 1);

        $imagemandatory = $cparams->get('imagemandatory', 0);
        $filemandatory = $cparams->get('filemandatory', 1);
        $allowedexts = $cparams->get('allowedexts', 'zip,mp3');
        $minimum_price = $cparams->get('minimum_price');
        $sepext = explode(",", $allowedexts);
        $countext = count($sepext);

        $freefiles_folder = $cparams->get('freefiles_folder', 'media');

        $acy_listid = $cparams->get('acy_listid');

        $enable_corecustomfields = $cparams->get('enable_corecustomfields', 1);


        $enable_vm2tags = $cparams->get('enable_vm2tags', 0);
        $tagslimit = $cparams->get('tagslimit', '5');
        $vm2tags_asmetakeywords = $cparams->get('vm2tags_asmetakeywords', '1');
        $enable_vm2geolocator = $cparams->get('enable_vm2geolocator', 0);
        $enable_embedvideo = $cparams->get('enable_embedvideo', 0);
        $resample_commercial_mp3 = $cparams->get('resample_commercial_mp3', 1);
        $mp3sample_start = $cparams->get('mp3sample_start', 0);
        $mp3sample_end = $cparams->get('mp3sample_end', 30);
        $customfields_autoadd = $cparams->get('customfields_autoadd');

        $thumb_path = $image_path . 'resized/';

        $formfile = '';

        $formpublished = $app->input->post->get('formpublished', null, 'int');
        $formname = $app->input->post->get('formname', null, 'string');

        $formdesc = $app->input->post->get('formdesc', null, 'string');
        if ($wysiwyg_prod) {
            $formdesc = $app->input->post->get('formdesc', '', 'array');
            $formdesc = implode($formdesc);
        }
        $form_s_desc = $app->input->post->get('form_s_desc', null, 'string');
        if ($enableprice)
            $formprice = $app->input->post->get('formprice');
        else
            $formprice = 0;
        if ($enableprice)
            $formprice1 = $app->input->post->get('formprice1');
        else
            $formprice1 = 0;
        //  echo $formprice1; die;
        $formweight = $app->input->post->get('formweight');
        $formweightunit = $app->input->post->get('formweightunit');

        if ($multicat)
            $formcat = $app->input->post->get('formcat', null, 'ARRAY');
        else
            $formcat = $app->input->post->get('formcat', null, 'INT');

        $formmanufacturer = $app->input->post->get('formmanufacturer');
        $formtags = $app->input->post->get('formtags', '', 'RAW');
        $latitude = $app->input->post->get('latitude');
        $longitude = $app->input->post->get('longitude');
        $zoom = $app->input->post->get('zoom', '', 'INT');
        $maptype = $app->input->post->get('maptype');
        $file1 = $app->input->files->get('file1');
        $file1name = $file1['name'];
        $flickrcheckbox = $app->input->post->get('flickrcheckbox');

        jimport('joomla.filesystem.file');
        if ($imagemandatory) {
            $first_image = $app->input->files->get('image1');
            if ($first_image) {
                $first_image_name = JFile::makeSafe($first_image['name']);
                if ($first_image['name'] != '') {
                    $infosImg = getimagesize($first_image['tmp_name']);
                    //if ( (substr($first_image['type'],0,5) != 'image' || $infosImg[0] > $maximgside || $infosImg[1] > $maximgside )){
                    if ((substr($first_image['type'], 0, 5) != 'image')) {
                        //image not valid
                        JError::raiseWarning(100, '<i class="icon-cancel"></i> ' . JText::_('COM_VMVENDOR_VMVENADD_IMGEXTNOT'));
                        $app->redirect('index.php?option=com_vmvendor&view=addproduct&Itemid=' . $app->input->get('Itemid'));
                    }
                }
            }
        }
        if ($formcat && $formname && ($formdesc || $form_s_desc) && ( ($enableweight && $formweight != '' && $formweightunit != '') OR ! $enableweight ) && ( ($enableprice && $formprice != '') OR ! $enableprice ) && $user->id > 0 && ( ($enablefiles && $filemandatory && $file1name != '' ) OR ! $filemandatory OR ! $enablefiles )) { // We add the product
            $formsku = $app->input->get('formsku');
            $q = "SELECT COUNT(*) FROM `#__virtuemart_products` WHERE `product_sku`='" . $formsku . "' ";
            $db->setQuery($q);
            $checknotyetadded = $db->loadResult();
            if ($checknotyetadded < 1) {
                $formcurrency = $app->input->post->get('formcurrency');
                if ($enablestock)
                    $formstock = $app->input->post->get('formstock');
                else
                    $formstock = '1';



                // we check here if vendor id has not been reset by 0 when editing an address
                $q = "SELECT `virtuemart_vendor_id` , `user_is_vendor` FROM `#__virtuemart_vmusers` WHERE `virtuemart_user_id` = '" . $user->id . "' ";
                $db->setQuery($q);
                $row = $db->loadRow();
                $virtuemart_vendor_id = $row[0];
                $user_is_vendor = $row[1];
                if ($virtuemart_vendor_id == '0' && $user_is_vendor = '1') {
                    $uslug = JFilterOutput::stringURLSafe($user->id . "-" . $user->username);
                    $q = "SELECT `virtuemart_vendor_id` 
					FROM `#__virtuemart_vendors_" . VMLANG . "`  
					WHERE `slug`='" . $uslug . "' ";
                    $db->setQuery($q);
                    $vvi = $db->loadResult();
                    $q = "UPDATE `#__virtuemart_vmusers`  
					SET `virtuemart_vendor_id`='" . $vvi . "' 
					WHERE `virtuemart_user_id`='" . $user->id . "' ";
                    $db->setQuery($q);
                    if (!$db->query())
                        die($db->stderr(true));
                }

                // look for main vendor currency
                $q = "SELECT `vendor_currency` FROM `#__virtuemart_vendors` WHERE `virtuemart_vendor_id` ='1' ";
                $db->setQuery($q);
                $currency_id = $db->loadResult();
                //////////////// Check if the vendor has ben created
                if (!$virtuemart_vendor_id OR $virtuemart_vendor_id == '0') {  ////////////////// We create the vendor and create the vmuser or update if allready exists as a customer 
                    require_once JPATH_COMPONENT . '/helpers/functions.php';
                    VmvendorFunctions::createVendor($user->id);
                    $q = "SELECT `virtuemart_vendor_id`  FROM `#__virtuemart_vmusers` WHERE `virtuemart_user_id` = '" . $user->id . "' ";
                    $db->setQuery($q);
                    $virtuemart_vendor_id = $db->loadResult();
                }//////////////// Vendor is created

                 $insert_date = date('Y-m-d H:i:s');
//                if ($formpublished)
//                    $insert_date = date('Y-m-d H:i:s');
//                else // way to check ifa p has ever been published or not. (announcements and notifications)
//                    $insert_date = '0000-00-00 00:00:00';

//				$q = "INSERT INTO `#__virtuemart_products` 
//					( `virtuemart_vendor_id` , `product_parent_id` , `product_sku` , ";
//				if($enableweight)
//					$q .= " `product_weight` , `product_weight_uom` , ";
//				$q .=" `product_in_stock` , `product_ordered` ,  `published` , `created_on` , `created_by` ) 
//					VALUES 
//					( '".$virtuemart_vendor_id."' , '0' , '".$formsku."' , ";
//				if($enableweight)
//					$q .= " '".$formweight."' , '".$formweightunit."' , ";			 
//				$q .= " '".$formstock."' , '0' , '".$formpublished."' , '".$insert_date."' , '".$user->id."' )";
//				
//				
                $pro_params = 'min_order_level=""|max_order_level="1"|step_order_level=""|product_box=""|';
                $q = "INSERT INTO `#__virtuemart_products` 
					( `virtuemart_vendor_id` , `product_parent_id` , `product_sku` , `product_params` , ";
                if ($enableweight)
                    $q .= " `product_weight` , `product_weight_uom` , ";
                $q .=" `product_in_stock` , `product_ordered` ,  `published` , `created_on` , `created_by` ) 
					VALUES 
					( '" . $virtuemart_vendor_id . "' , '0' , '" . $formsku . "' ,'" . $pro_params . "' , ";
                if ($enableweight)
                    $q .= " '" . $formweight . "' , '" . $formweightunit . "' , ";
                $q .= " '" . $formstock . "' , '0' , '" . $formpublished . "' , '" . $insert_date . "' , '" . $user->id . "' )";

                $db->setQuery($q);
                if (!$db->query())
                    die($db->stderr(true));
                $virtuemart_product_id = $db->insertid();
                ///////////////// 3rd party plugins insertion
                $metakey = '';
                $limited_tags = '';
                if ($enable_vm2tags && $formtags != '') {
                    $q = "SELECT `virtuemart_custom_id` FROM `#__virtuemart_customs` WHERE `custom_element`='vm2tags' AND `published`='1'";
                    $db->setQuery($q);
                    $virtuemart_custom_id = $db->loadResult();
                    $septags = explode(',', $formtags);
                    $i = 0;
                    foreach ($septags as $septag) {
                        $i++;
                        if ($i <= $tagslimit && strlen($septag) >= 2 && strlen($septag) <= 20) {
                            if ($i > 1) {

                                $limited_tags .=',';
                            }
                            $limited_tags .= $septag;
                        }
                    }
                    $plugin_tags = 'product_tags="' . $limited_tags . '"|';

                    $q = "INSERT INTO `#__virtuemart_product_customfields` 
					( `virtuemart_product_id` , `virtuemart_custom_id` , `customfield_value` , `customfield_params` , `published` , `created_on` , `created_by`  ) 
					VALUES 
					('" . $virtuemart_product_id . "' , '" . $virtuemart_custom_id . "' , 'vm2tags' , '" . $db->escape($plugin_tags) . "' , '1',  '" . date('Y-m-d H:i:s') . "' , '" . $user->id . "' )";
                    $db->setQuery($q);
                    if (!$db->query())
                        die($db->stderr(true));
                    if ($vm2tags_asmetakeywords)
                        $metakey = $limited_tags;
                    if (count($septags) > $tagslimit)
                        JError::raiseNotice(100, '<img src="' . $juri . 'components/com_vmvendor/assets/img/warning.png" width="16" height="16" alt="" align="absmiddle" /> <b>' . $tagslimit . '</b> ' . JText::_('COM_VMVENDOR_VMVENADD_FIRSTTAGSONLY') . '');
                }

                if ($enable_vm2geolocator && $latitude != '' && $longitude != '') {
                    $q = "SELECT `virtuemart_custom_id` FROM `#__virtuemart_customs` WHERE `custom_element` = 'vm2geolocator' AND `published`='1' ";
                    $db->setQuery($q);
                    $virtuemart_custom_id = $db->loadResult();
                    if ($virtuemart_custom_id != '') {
                        $q = "INSERT INTO `#__virtuemart_product_customfields` 			(`virtuemart_product_id`,`virtuemart_custom_id`,`customfield_value`,`customfield_params`,`published`,`created_on`,`created_by`)
					VALUES 
					('" . $virtuemart_product_id . "','" . $virtuemart_custom_id . "','vm2geolocator','latitude=\"" . $latitude . "\"|longitude=\"" . $longitude . "\"|zoom=\"" . $zoom . "\"|maptype=\"" . $maptype . "\"|','1','" . date('Y-m-d H:i:s') . "','" . $user->id . "')";
                        $db->setQuery($q);
                        if (!$db->query())
                            die($db->stderr(true));
                    }
                }

                if ($enable_embedvideo) {
                    $q = "SELECT vc.virtuemart_custom_id, vc.custom_title, vc.custom_tip
					FROM #__virtuemart_customs vc
					LEFT JOIN #__extensions e ON e.extension_id = vc.custom_jplugin_id 
					WHERE vc.custom_element='embedvideo' 
					AND e.enabled='1' ";
                    $db->setQuery($q);
                    $vid_fields = $db->loadObjectList();
                    //for($i=0;$i<count($vid_fields);$i++)
                    foreach ($vid_fields as $vid_field) {
                        $vid_url = $app->input->post->get('embedvideo_' . $vid_field->virtuemart_custom_id, '', 'raw');
                        if (substr($vid_url, 0, 32) == 'https://www.youtube.com/watch?v=') {
                            $q = "INSERT INTO `#__virtuemart_product_customfields` 			(`virtuemart_product_id`,`virtuemart_custom_id`,`customfield_value`,`customfield_params`,`published`,`created_on`,`created_by`)
					VALUES 
					('" . $virtuemart_product_id . "','" . $vid_field->virtuemart_custom_id . "','embedvideo','video_url=\"" . addslashes($vid_url) . "\"','1','" . date('Y-m-d H:i:s') . "','" . $user->id . "')";
                            $db->setQuery($q);
                            if (!$db->query())
                                die($db->stderr(true));
                        }
                    }
                }
                ////////////////////////
                $admitted = 1;
                if ($enablefiles && $filemandatory)
                    $admitted = 0;

                if ($enablefiles) {
                    for ($i = 1; $i <= $max_filefields; $i++) {
                        $fileisvalid = 0;
                        $file = $app->input->files->get('file' . $i);
                        if ($file) {
                            $filename = JFile::makeSafe($file['name']);
                            $ext = JFile::getExt($filename);
                            $formfilesize = $file['size'];
                            $form_mime = $file['type'];
                            for ($j = 0; $j < $countext; $j++) {
                                if ($sepext[$j] == $ext)
                                    $fileisvalid = 1; // file has an allowed extention					
                            }

                            if ($filename != '') {
                                if (!$fileisvalid) {
                                    JError::raiseWarning(
                                            100, '<i class="icon-cancel"></i> ' . JText::_('COM_VMVENDOR_FILEEXTNOT'));
                                }
                                if ($formfilesize > $maxfilesize OR $formfilesize == 0) {
                                    $fileisvalid = 0;
                                    JError::raiseWarning(100, '<i class="icon-cancel">
									</i> ' . JText::_('COM_VMVENDOR_MAXFILESIZEX') . ' ' . $formsku . "_" . $filename);
                                }
                            } else
                                $fileisvalid = 0;

                            $file_is_downloadable = 0;
                            $file_is_forSale = 1;
                            $target_filepath = $safepath . $formsku . "_" . $filename;
                            if ($formprice == '0' && $freefiles_folder == 'media') {
                                $file_is_downloadable = 1;
                                $file_is_forSale = 0;
                                $target_filepath = $image_path . $formsku . "_" . $filename;
                            }

                            if ($fileisvalid) {
                                if (JFile::upload($file['tmp_name'], $target_filepath)) {
                                    $app->enqueueMessage(
                                            '<i class="icon-ok">
									</i> ' . JText::_('COM_VMVENDOR_FILEUPLOADRENAME_SUCCESS') . ' ' . $formsku . '_' . $filename);
                                } else {
                                    JError::raiseWarning(
                                            100, '<i class="icon-cancel"></i> ' . JText::_('COM_VMVENDOR_FILEUPLOAD_ERROR'));
                                    //$fileisvalid = 0;	
                                }
                            }

                            if ($fileisvalid) {
                                $q = "INSERT INTO `#__virtuemart_medias` 
									( `virtuemart_vendor_id` , `file_title` , `file_description` , `file_mimetype` , `file_type` , `file_url` , `file_url_thumb` , `file_is_downloadable` , `file_is_forSale` , `published` , `created_on` , `created_by` )
									VALUES
									(  '" . $virtuemart_vendor_id . "' , '" . $formsku . '_' . $filename . "' , '" . JText::_('COM_VMVENDOR_FILE') . " " . $i . "',  '" . $form_mime . "' , 'product' , '" . addslashes($target_filepath) . "' , '' , '" . $file_is_downloadable . "', '" . $file_is_forSale . "', '1' , '" . date('Y-m-d H:i:s') . "' , '" . $user->id . "' )";                ///addslashes($target_filepath)
                                $db->setQuery($q);
                                if (!$db->query())
                                    die($db->stderr(true));
                                $virtuemart_media_id = $db->insertid();
                                $forsalefiles_plugin = 'ekerner'; //istraxx
                                if ($formprice > 0 OR $freefiles_folder == 'safe') { // product is not free, file is For sale and has to be added as a ST42_download custom plugin entry
                                    ////// mp3
                                    if (strtolower(substr($filename, -4) == '.mp3') && $resample_commercial_mp3) {
                                        require_once JPATH_COMPONENT_SITE . '/classes/class.mp3.php';
                                        $mp3 = new mp3;
                                        /* cut the mp3 file 
                                          cut_mp3($file_input, $file_output, $startindex = 0, $endindex = -1, $indextype = 'frame', $cleantags = false)
                                          it will return true or false */
                                        $vmvsample_path = $image_path . "vmvsample_" . $formsku . '_' . $filename;
                                        //   $mp3->cut_mp3( $file['tmp_name'] , $vmvsample_path , 0, 30, 'second', false);*
                                        $mp3->cut_mp3($target_filepath, $vmvsample_path, $mp3sample_start, $mp3sample_end, 'second', false);

                                        $q = "INSERT INTO `#__virtuemart_medias` 
										( `virtuemart_vendor_id` , `file_title` , `file_description` , `file_mimetype` , `file_type` , `file_url` , `file_url_thumb` , `file_is_downloadable` , `file_is_forSale` , `published` , `created_on` , `created_by` )
										VALUES
										(  '" . $virtuemart_vendor_id . "' , 'vmvsample_" . $formsku . '_' . $filename . "' , '" . $db->escape($formname) . " " . JText::_('COM_VMVENDOR_SAMPLETRACK') . " " . $i . "' , 'audio/mp3' , 'product' , '" . addslashes($vmvsample_path) . "' , '' , '1', '0', '1' , '" . date('Y-m-d H:i:s') . "' , '" . $user->id . "' )";                ///addslashes($target_filepath)
                                        $db->setQuery($q);
                                        if (!$db->query())
                                            die($db->stderr(true));
                                        $virtuemart_sample_media_id = $db->insertid();
                                        $q = "INSERT INTO `#__virtuemart_product_medias` 
										( `virtuemart_product_id` , `virtuemart_media_id`, `ordering` )
										VALUES
										(  '" . $virtuemart_product_id . "' , '" . $virtuemart_sample_media_id . "' , '1' )";
                                        $db->setQuery($q);
                                        if (!$db->query())
                                            die($db->stderr(true));
                                        $app->enqueueMessage('<i class="icon-ok"></i> ' . JText::_('COM_VMVENDOR_PREVIEWCREATEDSUCCESSFULLY') . ' ' . ($mp3sample_end - $mp3sample_start) . '"');
                                    }
                                    if ($forsalefiles_plugin == 'istraxx') {  // Istraxx plugin is used
                                        /* $q = "SELECT `virtuemart_custom_id` FROM `#__virtuemart_customs` WHERE `custom_element`='st42_download' ";
                                          $db->setQuery($q);
                                          $virtuemart_custom_id = $db->loadresult();
                                          $q = "INSERT INTO `#__virtuemart_product_customfields`
                                          ( `virtuemart_product_id` , `virtuemart_custom_id` , `customfield_value` ,  `customfield_params` , `published` , `created_on` , `created_by` )
                                          VALUES
                                          ( '".$virtuemart_product_id."' , '".$virtuemart_custom_id."' , 'st42_download' ,  '{\"media_id\":\"".$virtuemart_media_id."\",\"stream\":\"".$stream."\",\"maxspeed\":\"".$maxspeed."\",\"maxtime\":\"".$maxtime."\"}' , '0' , '".date('Y-m-d H:i:s')."' , '".$user->id."'  )";
                                          $db->setQuery($q);
                                          if (!$db->query()) die($db->stderr(true)); */
                                    } elseif ($forsalefiles_plugin == 'ekerner') {  // http://shop.ekerner.com/index.php/shop/joomla-extensions/vmcustom-downloadable-detail
                                        $downloadable_expires_quantity = $cparams->get('downloadable_expires_quantity', '0');
                                        $downloadable_expires_period = $cparams->get('downloadable_expires_period', 'days');
                                        $downloadable_order_states = $cparams->get('downloadable_order_states');
                                        $statuses_string = '';
                                        for ($e = 0; $e < count($downloadable_order_states); $e++) {
                                            $statuses_string .= '\"' . $downloadable_order_states[$e] . '\"';
                                            if ($e < count($downloadable_order_states) - 1)
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
										( '" . $virtuemart_product_id . "' , '" . $virtuemart_custom_id . "' , 'downloadable'  , 
										'downloadable_media_id=\"" . $virtuemart_media_id . "\"|downloadable_order_states=[" . $statuses_string . "]|downloadable_expires_quantity=\"" . $downloadable_expires_quantity . "\"|downloadable_expires_period=\"" . $downloadable_expires_period . "\"|' , '1' , '" . date('Y-m-d H:i:s') . "' , '" . $user->id . "'  )";
                                        $db->setQuery($q);
                                        if (!$db->query())
                                            die($db->stderr(true));
                                    }
                                }
                                else {  ////////// Product is free, file is shown on product details
                                    $q = "INSERT INTO `#__virtuemart_product_medias` 
									( `virtuemart_product_id` , `virtuemart_media_id` , `ordering`)
									VALUES
									(  '" . $virtuemart_product_id . "' , '" . $virtuemart_media_id . "' , '" . $i . "' )";
                                    $db->setQuery($q);
                                    if (!$db->query())
                                        die($db->stderr(true));
                                }
                                $admitted = 1;
                            }
                            elseif ($i == 1 && $filemandatory) { // files are mandatory and first file field is not valid . EXIT PRODUCT ADDITION!
                                $admitted = 0;
                            }
                        }
                    }
                } else
                    $admitted = 1;



                if ($admitted == 0) {// we unstorez the product id from the DB
                    $q = "DELETE FROM `#__virtuemart_products` WHERE `virtuemart_product_id`='" . $virtuemart_vendor_id . "' ";
                    $db->setQuery($q);
                    if (!$db->query())
                        die($db->stderr(true));
                }


                if ($formdesc == '')
                    $formdesc = $form_s_desc;
                if ($form_s_desc == '')
                    $form_s_desc = strip_tags(str_replace(array('<p>', '<div>', '<br>', '<br />'), ' ', $formdesc));

                if ($admitted == 1) {
                    // we format the short description, cut at 255 char and replace last word by '...'
                    if (strlen($form_s_desc) > 255) {
                        $form_s_desc = substr($form_s_desc, 0, 251);
                        $splitted = split(" ", $form_s_desc);
                        $keys = array_keys($splitted);
                        $lastKey = end($splitted);
                        $countlastkey = strlen($lastKey);
                        $form_s_desc = substr_replace($form_s_desc . ' ', '...', -($countlastkey + 1), -1);
                    }

                    $q = "INSERT INTO `#__virtuemart_products_" . VMLANG . "` 
					( `virtuemart_product_id` , `product_s_desc` , `product_desc` , `product_name` , `slug` , `metadesc` , `metakey`  ) 
					VALUES 
					( '" . $virtuemart_product_id . "' , '" . $db->escape($form_s_desc) . "' , '" . $db->escape($formdesc) . "' , '" . $db->escape($formname) . "' , '" . $virtuemart_product_id . '-' . JFilterOutput::stringURLSafe($db->escape($formname)) . $virtuemart_product_id . "' ,'" . $db->escape($formdesc) . "' , '" . $db->escape($metakey) . "' )";
                    $db->setQuery($q);
                    if (!$db->query())
                        die($db->stderr(true));

                    if ($multilang_mode > 0) {
                        for ($i = 0; $i < count($active_languages); $i++) {
                            //$app->enqueueMessage($active_languages[$i]); //en-GB
                            if (str_replace('_', '-', VMLANG) != strtolower($active_languages[$i])) {
                                $q = "INSERT INTO `#__virtuemart_products_" . strtolower(str_replace('-', '_', $active_languages[$i])) . "` 
								( `virtuemart_product_id` , `product_s_desc` , `product_desc` , `product_name` , `slug`  , `metadesc` , `metakey` ) 
								VALUES 
								( '" . $virtuemart_product_id . "' , '" . $db->escape($form_s_desc) . "' , '" . $db->escape($formdesc) . "' , '" . $db->escape($formname) . "' , '" . strtolower(str_replace(' ', '-', $db->escape($formname))) . $virtuemart_product_id . "' , '" . $db->escape($formdesc) . "' , '" . $db->escape($metakey) . "' )";
                                $db->setQuery($q);
                                if (!$db->query())
                                    die($db->stderr(true));
                            }
                        }
                    }





                    if ($multicat) {
                        for ($i = 0; $i < count($formcat); $i++) {
                            $q = "INSERT INTO `#__virtuemart_product_categories` 
							( `virtuemart_product_id` , `virtuemart_category_id`   ) 
							VALUES 
							( '" . $virtuemart_product_id . "' , '" . $formcat[$i] . "'  )";
                            $db->setQuery($q);
                            if (!$db->query())
                                die($db->stderr(true));
                        }
                    }
                    else {
                        $q = "INSERT INTO `#__virtuemart_product_categories` 
						( `virtuemart_product_id` , `virtuemart_category_id`   ) 
						VALUES 
						( '" . $virtuemart_product_id . "' , '" . $formcat . "'  )";
                        $db->setQuery($q);
                        if (!$db->query())
                            die($db->stderr(true));
                    }
                    if ($formmanufacturer) {
                        $q = "INSERT INTO #__virtuemart_product_manufacturers 
						(virtuemart_product_id , virtuemart_manufacturer_id) 
						VALUES ('" . $virtuemart_product_id . "' , '" . $formmanufacturer . "') ";
                        $db->setQuery($q);
                        if (!$db->query())
                            die($db->stderr(true));
                    }

                    if ($formprice < $minimum_price) {
                        $formprice = $minimum_price;
                        JError::raiseNotice(100, '<img src="' . $juri . 'components/com_vmvendor/assets/img/warning.png" width="16" height="16" alt="" align="absmiddle" /> ' . JText::_('COM_VMVENDOR_VMVENADD_PRICECHANGED') . ' ' . $minimum_price);
                    }

                    if ($formprice > 0) {
                        $q = "INSERT INTO `#__virtuemart_product_prices` 
						( `virtuemart_product_id` , `virtuemart_shoppergroup_id` , `product_price` ,  `product_currency` , `created_on` , `created_by` ) 
						VALUES 
						( '" . $virtuemart_product_id . "' , '' , '" . $formprice . "' , '" . $currency_id . "' , '" . date('Y-m-d H:i:s') . "' , '" . $user->id . "')";
                        $db->setQuery($q);
                        if (!$db->query())
                            die($db->stderr(true));
                    }

                    for ($i = 1; $i <= $max_imagefields; $i++) { ////////////// images
                        //if($_FILES['image'.$i]['tmp_name']!=''){
                        $imgisvalid = 1;
                        $image = $app->input->files->get('image' . $i);
                        $image['name'] = JFile::makeSafe($image['name']);
                        if ($image['name'] != '') {
                            $infosImg = getimagesize($image['tmp_name']);
                            //if ( (substr($image['type'],0,5) != 'image' || $infosImg[0] > $maximgside || $infosImg[1] > $maximgside )){
                            if ((substr($image['type'], 0, 5) != 'image')) {
                                JError::raiseWarning(
                                        100, '<i class="icon-cancel"></i> ' . JText::_('COM_VMVENDOR_VMVENADD_IMGEXTNOT'));
                                $imgisvalid = 0;
                            }

                            $product_image = strtolower($formsku . "_" . $image['name']);
                            $product_image = str_replace(' ', '', $product_image);


                            $target_imagepath = JPATH_BASE . '/' . $image_path . $product_image;
                            if ($imgisvalid) {
                                if (JFile::upload($image['tmp_name'], $target_imagepath)) {
                                    $app->enqueueMessage(
                                            '<i class="icon-ok">
									</i> ' . JText::_('COM_VMVENDOR_VMVENADD_IMAGEUPLOADRENAME_SUCCESS') . ' ' . $product_image);
                                } else {
                                    JError::raiseWarning(
                                            100, '<i class="icon-cancel"></i> ' . JText::_('COM_VMVENDOR_VMVENADD_IMAGEUPLOAD_ERROR'));
                                }
                            }
                            $ext = JFile::getExt($image['name']);
                            $ext = strtolower($ext);
                            $ext = str_replace('jpeg', 'jpg', $ext);
                            //SWITCHES THE IMAGE CREATE FUNCTION BASED ON FILE EXTENSION

                            switch (strtolower($ext)) {
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
                                    $imgisvalid = 0;
                                    break;
                            }
                            if ($product_image != '' && $imgisvalid) {
                                list($width, $height) = getimagesize($target_imagepath);
                                /* if($width>=$height){ 
                                  $resizedH = ( $vmconfig_img_width * $height) / $width;
                                  $thumb = imagecreatetruecolor( $vmconfig_img_width ,  $resizedH);
                                  imagecopyresampled( $thumb,  $source,  0,  0,  0,  0,  $vmconfig_img_width ,  $resizedH,  $width,  $height );
                                  }
                                  else{
                                  $resizedW = ( VmConfig::get('img_height') * $width) / $height;
                                  $thumb = imagecreatetruecolor($resizedW,  VmConfig::get('img_height') );
                                  imagecopyresampled($thumb ,  $source ,  0 ,  0 ,  0 ,  0 ,  $resizedW,  VmConfig::get('img_height') ,  $width,  $height );
                                  } */

                                if ($width > $maximgside) {
                                    $resizedH = ( $maximgside * $height) / $width;
                                    if ($ext == 'gif')
                                        $largeone = imagecreate($maximgside, $resizedH);
                                    else
                                        $largeone = imagecreatetruecolor($maximgside, $resizedH);
                                    imagealphablending($largeone, false);
                                    imagesavealpha($largeone, true);
                                    $transparent = imagecolorallocatealpha($largeone, 255, 255, 255, 127);
                                    imagefilledrectangle($largeone, 0, 0, $maximgside, $resizedH, $transparent);
                                    imagecopyresampled($largeone, $large_source, 0, 0, 0, 0, $maximgside, $resizedH, $width, $height);
                                } else
                                    $largeone = $target_imagepath;
                                switch ($ext) {
                                    case 'jpg':
                                        imagejpeg($largeone, JPATH_BASE . '/' . $image_path . $product_image, $thumbqual);
                                        break;
                                    case 'jpeg':
                                        imagejpeg($largeone, JPATH_BASE . '/' . $image_path . $product_image, $thumbqual);
                                        break;
                                    case 'png':
                                        imagepng($largeone, JPATH_BASE . '/' . $image_path . $product_image);
                                        break;
                                    case 'gif':
                                        imagegif($largeone, JPATH_BASE . '/' . $image_path . $product_image);
                                        break;
                                }
                                imagedestroy($largeone);



                                //list($width,  $height) = getimagesize($target_imagepath); 
                                if ($width >= $height) {
                                    $resizedH = ( $vmconfig_img_width * $height) / $width;
                                    if ($ext == 'gif')
                                        $thumb = imagecreate($vmconfig_img_width, $resizedH);
                                    else
                                        $thumb = imagecreatetruecolor($vmconfig_img_width, $resizedH);
                                    imagealphablending($thumb, false);
                                    imagesavealpha($thumb, true);
                                    $transparent = imagecolorallocatealpha($thumb, 255, 255, 255, 127);
                                    imagefilledrectangle($thumb, 0, 0, $vmconfig_img_width, $resizedH, $transparent);
                                    imagecopyresampled($thumb, $source, 0, 0, 0, 0, $vmconfig_img_width, $resizedH, $width, $height);
                                }
                                else {
                                    $resizedW = ( VmConfig::get('img_height') * $width) / $height;
                                    if ($ext == 'gif')
                                        $thumb = imagecreate($resizedW, VmConfig::get('img_height'));
                                    else
                                        $thumb = imagecreatetruecolor($resizedW, VmConfig::get('img_height'));
                                    imagealphablending($thumb, false);
                                    imagesavealpha($thumb, true);
                                    $transparent = imagecolorallocatealpha($thumb, 255, 255, 255, 127);
                                    imagefilledrectangle($thumb, 0, 0, $resizedW, VmConfig::get('img_height'), $transparent);
                                    imagecopyresampled($thumb, $source, 0, 0, 0, 0, $resizedW, VmConfig::get('img_height'), $width, $height);
                                }
                                switch ($ext) {
                                    case 'jpg':
                                        imagejpeg($thumb, JPATH_BASE . '/' . $thumb_path . $product_image, $thumbqual);
                                        break;
                                    case 'jpeg':
                                        imagejpeg($thumb, JPATH_BASE . '/' . $thumb_path . $product_image, $thumbqual);
                                        break;
                                    case 'png':
                                        imagepng($thumb, JPATH_BASE . '/' . $thumb_path . $product_image);
                                        break;
                                    case 'gif':
                                        imagegif($thumb, JPATH_BASE . '/' . $thumb_path . $product_image);
                                        break;
                                }
                                imagedestroy($thumb);


                                 $q = "INSERT INTO `#__virtuemart_medias` 
								( `virtuemart_vendor_id` , `file_title` ,  `file_mimetype` , `file_type` , `file_url` , `file_url_thumb` , `file_is_product_image` , `published` , `created_on` , `created_by` )
								VALUES
								(  '" . $virtuemart_vendor_id . "' , '" . JFile::makeSafe($product_image) . "'  , '" . $image['type'] . "' , 'product' , '" . $image_path . JFile::makeSafe($product_image) . "' , '" . $thumb_path . JFile::makeSafe($product_image) . "' , '1', '1' , '" . date('Y-m-d H:i:s') . "' , '" . $user->id . "' )";
                            
                            
                             $db->setQuery($q);
                                if (!$db->query())
                                    die($db->stderr(true));
                                
                               
                                $virtuemart_media_id = $db->insertid();
                                $q = "INSERT INTO `#__virtuemart_product_medias` 
								( `virtuemart_product_id` , `virtuemart_media_id` , `ordering`)
								VALUES
								(  '" . $virtuemart_product_id . "' , '" . $virtuemart_media_id . "' ,'" . ($i - 1) . "' )";
                                $db->setQuery($q);
                                if (!$db->query())
                                    die($db->stderr(true));
                            }
                        }

                        if ($i == 1 && $flickr_autopost && $flickr_autopost_email != '')
                            $flickr_img = $image_path . JFile::makeSafe($product_image);
                    }
                    ////////////////////////////// Core Custom fields support
                    if ($enable_corecustomfields) {
                        $q = "SELECT `virtuemart_custom_id` , `custom_parent_id` , `virtuemart_vendor_id` , `custom_jplugin_id` , `custom_title` , `custom_tip` , `custom_value`, `custom_desc` , `field_type` , `is_list` , `shared`  
						FROM `#__virtuemart_customs`
						WHERE `custom_jplugin_id`='0' AND field_type !='R' AND field_type!='Z'
						AND `admin_only`='0' 
						AND `published`='1' 
						ORDER BY `ordering` ASC , `virtuemart_custom_id` ASC ";
                        $db->setQuery($q);
                        $core_custom_fields = $db->loadObjectList();
                        $i = 0;
                        foreach ($core_custom_fields as $core_custom_field) {
                            $i++;
                            if ($app->input->get('corecustomfield_' . $i) != '') {
                                $q = "INSERT INTO #__virtuemart_product_customfields 
								( virtuemart_product_id , virtuemart_custom_id , customfield_value , published , created_on , created_by , ordering )
								VALUES
								(  '" . $virtuemart_product_id . "' , '" . $core_custom_field->virtuemart_custom_id . "' , '" . $db->escape($app->input->post->get('corecustomfield_' . $i, '', 'string')) . "' , '1' , '" . date('Y-m-d H:i:s') . "' , '" . $user->id . "' , '" . $i . "'  )";
                                $db->setQuery($q);
                                if (!$db->query())
                                    die($db->stderr(true));
                            }
                        }
                        for ($ij = 1; $ij <= 2; $ij++) {

                            if ($ij == 2) {
                                $formp1 = $formprice1;
                                $datavalue = "Multiple licence";
                            } else {
                                $formp1 = 0;
                                $datavalue = "Single licence";
                            }
                            $q1 = "INSERT INTO #__virtuemart_product_customfields 
		( virtuemart_product_id , virtuemart_custom_id , customfield_value ,customfield_price, published , created_on , created_by , ordering )
								VALUES
		(  '" . $virtuemart_product_id . "' , '23' , '" . $datavalue . "' ,'" . $formp1 . "', '1' , '" . date('Y-m-d H:i:s') . "' , '" . $user->id . "' , '" . $ij . "'  )";
                            $db->setQuery($q1);
                            if (!$db->query())
                                die($db->stderr(true));
                        }
                    }


                    if ($customfields_autoadd) {
                        $customfields_autoadd_id = explode(',', $customfields_autoadd);
                        for ($i = 0; $i < count($customfields_autoadd_id); $i++) {
                            $q = "SELECT custom_value FROM #__virtuemart_customs 	 WHERE virtuemart_custom_id='" . $customfields_autoadd_id[$i] . "' AND published='1' ";
                            $db->setQuery($q);
                            $custom_value = $db->loadResult();
                            $q = "INSERT INTO #__virtuemart_product_customfields 
							(virtuemart_product_id , virtuemart_custom_id ,customfield_value ,  created_on , created_by)
							 VALUES ('" . $virtuemart_product_id . "' , '" . $customfields_autoadd_id[$i] . "' ,'" . $db->escape($custom_value) . "', '" . date('Y-m-d H:i:s') . "' , '" . $user->id . "' )";
                            $db->setQuery($q);
                            if (!$db->query())
                                die($db->stderr(true));
                        }
                    }
                }

                if ($enablerss) {
                    require_once JPATH_COMPONENT . '/helpers/functions.php';
                    VmvendorFunctions::updateRSS(1, $virtuemart_vendor_id);  // 1 for addition						
                }
                if ($multicat)
                    $product_url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $virtuemart_product_id . '&virtuemart_category_id=' . $formcat[0] . '&Itemid=' . $vmitemid);
                else
                    $product_url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $virtuemart_product_id . '&virtuemart_category_id=' . $formcat . '&Itemid=' . $vmitemid);



                if ($formpublished && ( $profileman == 'js' OR $profileman == 'es' )) {
                    require_once JPATH_COMPONENT . '/helpers/functions.php';
                    if ($profileman == 'js')// Add profile application + activity streams
                        VmvendorFunctions::doJomsocialActions($virtuemart_product_id, $formcat, $product_image);
                    elseif ($profileman == 'es') // Activity Stream
                        VmvendorFunctions::doEasysocialActions($virtuemart_product_id, $formcat, $product_image);
                }

                if ($flickr_autopost && $flickrcheckbox == 'on' && $flickr_autopost_email != '' && $flickr_img != '' && $formpublished) {
                    require_once JPATH_COMPONENT . '/helpers/functions.php';
                    VmvendorFunctions::emailFlickr($virtuemart_product_id, $formname, $form_s_desc, $limited_tags, $product_url, $flickr_img);
                }


                // Email Notification when new product added
                if (($emailnotify_addition && $to != NULL ) || !$autopublish || $autopublish == '2') {
                    require_once JPATH_COMPONENT . '/helpers/functions.php';
                    VmvendorFunctions::emailNotifyAddition($virtuemart_product_id, $formcat, $autopublish);
                }
            }
            if ($formpublished) {
                $app->enqueueMessage('<i class="icon-ok"></i> ' . JText::_('COM_VMVENDOR_PRODUCTADDED'));
                $app->enqueueMessage('<a href="' . JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $virtuemart_product_id . '&virtuemart_category_id=' . $formcat . '&Itemid=' . $vmitemid) . '"><i class="icon-cube"></i> ' . stripslashes(ucfirst($formname)) . '</a>');
            } else
                JError::raiseNotice(100, '<i class="icon-clock"></i> ' . JText::_('COM_VMVENDOR_TOBEREVIEWD'));
        }
        if (isset($_POST['formsku']) && $user->id == 0) // user has been inactive for too long and session time out
            JError::raiseWarning(100, '<i class="icon-cancel"></i> ' . JText::_('COM_VMVENDOR_UPLOAD_TIMEOUT') . '');

        $app->redirect('index.php?option=com_vmvendor&view=addproduct&Itemid=' . $app->input->get('Itemid'));
    }

}

?>