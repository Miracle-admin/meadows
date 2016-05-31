<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');


class VmvendorModelEditproduct extends JModelItem
{
	/**
	 * @var string msg
	 */
	//protected $msg;
 
	/**
	 * Get the message
	 * @return string The message to be displayed to the user
	 */

	public function getProductdata() 
	{
		$db 	= JFactory::getDBO();
		$app	= JFactory::getApplication();
		if (!class_exists( 'VmConfig' ))
			require(JPATH_ADMINISTRATOR .  '/components/com_virtuemart/helpers/config.php');
		VmConfig::loadConfig();
		$virtuemart_product_id		= $app->input->get('productid','','INT');		
		$q = "SELECT vp.`virtuemart_vendor_id` , vp.`product_sku` , vp.`product_weight` , vp.`product_weight_uom` , vp.`product_in_stock` , vp.`published` , vp.`created_on` , 
		vpl.`product_s_desc` , vpl.`product_desc` , vpl.`product_name` , 
		vpc.`virtuemart_category_id` , 
		vpp.`product_price` 
		FROM `#__virtuemart_products` vp 
		LEFT JOIN `#__virtuemart_products_".VMLANG."` vpl ON vpl.`virtuemart_product_id` = vp.`virtuemart_product_id`  
		LEFT JOIN `#__virtuemart_product_categories` vpc ON vpc.`virtuemart_product_id` = vpl.`virtuemart_product_id` 
		LEFT JOIN `#__virtuemart_product_prices` vpp ON vpp.`virtuemart_product_id` = vpl.`virtuemart_product_id` 
		WHERE vp.`virtuemart_product_id` =".$virtuemart_product_id." ";
		$db->setQuery($q);
		$product_data = $db->loadRow();
		$this->_product_data = $product_data;
		return $this->_product_data;
	}
	public function getMultipleCats() 
	{
		$db = JFactory::getDBO();
		$app	= JFactory::getApplication();
		$virtuemart_product_id		= $app->input->get('productid','','INT');			
		$q = "SELECT `virtuemart_category_id` FROM  `#__virtuemart_product_categories` WHERE `virtuemart_product_id` ='".$virtuemart_product_id."' ";
		$db->setQuery($q);
		$product_cats = $db->loadObjectList();
		$catz = array();
		foreach($product_cats as $product_cat)
		{
			$catz[] = $product_cat->virtuemart_category_id;
		}		
		return $catz;
	}
	public function getProductimages() 
	{
		$app	= JFactory::getApplication();
		$db = JFactory::getDBO();
		$virtuemart_product_id		= $app->input->get('productid','','INT');			
		$q = "SELECT vm.`file_title` , vm.`virtuemart_media_id`, vm.`file_url` , vm.`file_url_thumb` 
		FROM `#__virtuemart_medias` vm 
		LEFT JOIN `#__virtuemart_product_medias` vpm on vpm.`virtuemart_media_id` = vm.`virtuemart_media_id`  
		WHERE vpm.`virtuemart_product_id` =".$virtuemart_product_id." 
		AND vm.`file_mimetype` LIKE 'image/%' 
		ORDER BY vm.`virtuemart_media_id` ASC";
		$db->setQuery($q);
		$product_images = $db->loadObjectList();
		$this->_product_images = $product_images;
		return $this->_product_images;
	}


	public function getProductfiles() 
	{
		$forsalefiles_plugin = 'ekerner';
		$cparams 				= JComponentHelper::getParams( 'com_vmvendor' );
		$freefiles_folder 	= $cparams->get('freefiles_folder','media');
		
		$db 	= JFactory::getDBO();
		$app	= JFactory::getApplication();
		$virtuemart_product_id		= $app->input->get('productid','','INT');
		// Get price first to know where to look for files
		$q ="SELECT `product_price` FROM `#__virtuemart_product_prices` WHERE `virtuemart_product_id`='".$virtuemart_product_id."' ";
		$db->setQuery($q);
		$product_price = $db->loadResult();
		
		if($product_price >0 OR $freefiles_folder=='safe')
		{ // product is not free, file is stored in the st42_download customfield table
			if($forsalefiles_plugin =='istraxx')
			{
				$q = "SELECT vm.`file_title` , vm.`virtuemart_media_id`, vm.`file_url`  
				FROM `#__virtuemart_medias` vm 
				LEFT JOIN `#__virtuemart_product_customfields` vpc ON vpc.`customfield_params` LIKE CONCAT( CONCAT('media_id=\"' , vm.`virtuemart_media_id` , '\"') , '%' )
				WHERE vpc.`virtuemart_product_id` =".$virtuemart_product_id." 
				AND ( vm.`file_is_downloadable`='1' OR vm.`file_is_forSale`='1' ) 
				ORDER BY vm.`virtuemart_media_id` ASC";
			}
			elseif($forsalefiles_plugin =='ekerner')
			{
				$q = "SELECT vm.`file_title` , vm.`virtuemart_media_id`, vm.`file_url`  
				FROM `#__virtuemart_medias` vm 
				LEFT JOIN `#__virtuemart_product_customfields` vpc ON vpc.`customfield_params` LIKE CONCAT( CONCAT('downloadable_media_id=\"' , vm.`virtuemart_media_id` , '\"') , '%' )
				WHERE vpc.`virtuemart_product_id` =".$virtuemart_product_id." 
				AND ( vm.`file_is_downloadable`='1' OR vm.`file_is_forSale`='1' ) 
				ORDER BY vm.`virtuemart_media_id` ASC";
			}
			$db->setQuery($q);
			$product_files = $db->loadObjectList();
		}
		else
		{ // product is free and the free files folder is MEDIA, file is stored in the products media table
			$q = "SELECT vm.`file_title` , vm.`virtuemart_media_id`, vm.`file_url`  
			FROM `#__virtuemart_medias` vm 
			LEFT JOIN `#__virtuemart_product_medias` vpm on vpm.`virtuemart_media_id` = vm.`virtuemart_media_id`  
			WHERE vpm.`virtuemart_product_id` =".$virtuemart_product_id." 
			AND ( vm.`file_is_downloadable`='1' OR vm.`file_is_forSale`='1' ) 
			ORDER BY vm.`virtuemart_media_id` ASC";
			$db->setQuery($q);
			$product_files = $db->loadObjectList();
		}
		
		$this->_product_files = $product_files;
		return $this->_product_files;
	}
	
	
	public function getPriceformat() 
	{
		$db = JFactory::getDBO();
		$q ="SELECT curr.*  
		FROM `#__virtuemart_currencies` AS curr 
		LEFT JOIN `#__virtuemart_vendors` AS vend ON vend.`vendor_currency` = curr.`virtuemart_currency_id`  
		WHERE vend.`virtuemart_vendor_id` = '1' ";
		$db->setQuery($q);
		$price_format 	= $db->loadRow();
		$this->_price_format = $price_format;
		return $this->_price_format;
	}
	
	
	public function getProductTags() 
	{
		$db 	= JFactory::getDBO();
		$app	= JFactory::getApplication();
		$virtuemart_product_id		= $app->input->get('productid','','INT');
		$q ="SELECT `virtuemart_custom_id` FROM `#__virtuemart_customs` WHERE `custom_element`='vm2tags' ";
		$db->setQuery($q);
		$virtuemart_customfield_id = $db->loadResult();			
		$q = " SELECT `customfield_params` 
		FROM `#__virtuemart_product_customfields` 
		WHERE `customfield_value`='vm2tags' 
		AND `virtuemart_product_id`='".$virtuemart_product_id."'  
		AND `virtuemart_custom_id` ='".$virtuemart_customfield_id."' ";
		$db->setQuery($q);
		$product_tags = $db->loadResult();	
		$this->_product_tags = $product_tags;
		return $this->_product_tags;
	}
	
	function get_string_between($string, $start, $end){
		$string = " ".$string;
		$ini = strpos($string,$start);
		if ($ini == 0) return "";
		$ini += strlen($start);
		$len = strpos($string,$end,$ini) - $ini;
		return substr($string,$ini,$len);
	}
	
	
	public function getProductLocation() 
	{
		$db 	= JFactory::getDBO();
		$app	= JFactory::getApplication();
		$virtuemart_product_id		= $app->input->get('productid','','INT');
		$q = "SELECT `custom_params` FROM `#__virtuemart_customs` WHERE `custom_element`='vm2geolocator' AND `published`='1' ";
		$db->setQuery($q);
		$vm2geo_params= $db->loadResult();

		$q ="SELECT `customfield_params` FROM `#__virtuemart_product_customfields` 
		WHERE `virtuemart_product_id`='".$virtuemart_product_id."' AND `customfield_value`='vm2geolocator' ";
		$db->setQuery($q);
		$custom_param = $db->loadResult();
		$geo_params = array();
		$geo_params['set'] =0 ;
		$geo_params['be_lat'] = $this->get_string_between(  $custom_param , 'latitude="', '"|longitude' );
		$geo_params['be_lng'] = $this->get_string_between(  $custom_param , 'longitude="', '"|zoom' );
		$geo_params['be_zoom'] = $this->get_string_between(  $custom_param , 'zoom="', '"|maptype' );
		$geo_params['be_maptype'] = $this->get_string_between(  $custom_param , 'maptype="', '"|' );
		if($geo_params['be_lat']!='')
			$geo_params['set'] = 1;
		else
		{	
			$geo_params['set'] = 0;
			$geo_params['be_lat'] = $this->get_string_between(  $vm2geo_params , 'default_lat="', '"|default_ln' );
			$geo_params['be_lng'] = $this->get_string_between(  $vm2geo_params , 'default_lng="', '"|default_zo' );
			$geo_params['be_zoom'] = $this->get_string_between(  $vm2geo_params , 'default_zoom="', '"|default_ma' );
			$geo_params['be_maptype'] = $this->get_string_between(  $vm2geo_params , 'default_maptype="', '"|stylez' );		
		}
		$geo_params['js_key'] = $this->get_string_between(  $vm2geo_params , 'js_key="', '"|js_client' );
		$geo_params['js_client'] = $this->get_string_between(  $vm2geo_params , 'js_client="', '"|js_sig' );
		$geo_params['js_signature'] = $this->get_string_between(  $vm2geo_params , 'js_signature="', '"|static_key' );
		return $geo_params;
	}
	
	public function getCorecustomfields() 
	{
		$app						= JFactory::getApplication();
		$virtuemart_product_id		= $app->input->get('productid','','INT');
		$db 						= JFactory::getDBO();
		/// j3mod:   custom_field_desc changed to custom_desc
		$q ="SELECT vc.`virtuemart_custom_id` , vc.`custom_parent_id` , vc.`virtuemart_vendor_id` , vc.`custom_jplugin_id` , vc.`custom_title` , vc.`custom_tip` , vc.`custom_value`, vc.`custom_desc` , vc.`field_type` , vc.`is_list` , vc.`shared` ,
		vpc.`customfield_value` AS value 
		FROM `#__virtuemart_customs` vc 
		LEFT JOIN `#__virtuemart_product_customfields` vpc ON vc.`virtuemart_custom_id` = vpc.`virtuemart_custom_id` AND vpc.`virtuemart_product_id` ='".$virtuemart_product_id."'
		WHERE vc.`custom_jplugin_id`='0' 
		AND vc.`admin_only`='0' 
		AND vc.`published`='1' 
		AND `field_type`!='R'  AND `field_type`!='Z' AND is_cart_attribute!='1' 
		ORDER BY vc.`ordering` ASC , vc.`virtuemart_custom_id` ASC ";
		//AND vc.`custom_element`!='' 
		$db->setQuery($q);
		$core_custom_fields	= $db->loadObjectList();
		$this->_core_custom_fields = $core_custom_fields;
		return $this->_core_custom_fields;
	}
	
	public function getVendorid() 
	{
		$db 		= JFactory::getDBO();
		$user 		= JFactory::getUser();
		$q = "SELECT `virtuemart_vendor_id` FROM `#__virtuemart_vmusers` WHERE `virtuemart_user_id` = '".$user->id."' ";
		$db->setQuery($q);
		$virtuemart_vendor_id = $db->loadResult();
		$this->_virtuemart_vendor_id = $virtuemart_vendor_id;
		return $this->_virtuemart_vendor_id;
	}
	static function getManufacturers() 
	{
		if (!class_exists( 'VmConfig' ))
		{
			require(JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php');
		}
		VmConfig::loadConfig();
		$db = JFactory::getDBO();
		$q = "SELECT vm.virtuemart_manufacturer_id ,
		vml.mf_name 
		FROM #__virtuemart_manufacturers vm 
		LEFT JOIN #__virtuemart_manufacturers_".VMLANG." vml ON vm.virtuemart_manufacturer_id = vml.virtuemart_manufacturer_id
		WHERE vm.published='1' ORDER BY mf_name ASC ";
		$db->setQuery($q);
		$manufacturers = $db->loadObjectList();
		return $manufacturers;
	}
	static function getProductManufacturer() 
	{
		$app						= JFactory::getApplication();
		$virtuemart_product_id		= $app->input->get('productid','','INT');
		$db 						= JFactory::getDBO();
		$q = "SELECT virtuemart_manufacturer_id FROM #__virtuemart_product_manufacturers  WHERE virtuemart_product_id='".$virtuemart_product_id."' ";
		$db->setQuery($q);
		$manufacturerid = $db->loadResult();
		return $manufacturerid;
	}
	
	static function getEmbedvideoFields() 
	{
		$app						= JFactory::getApplication();
		$virtuemart_product_id		= $app->input->get('productid','','INT');
		$db 	= JFactory::getDBO();
		$q = "SELECT vc.virtuemart_custom_id, vc.custom_title, vc.custom_tip , vpc.customfield_params
		FROM #__virtuemart_customs vc
		JOIN #__extensions e ON e.extension_id = vc.custom_jplugin_id 
		LEFT JOIN #__virtuemart_product_customfields vpc 
			ON vpc.virtuemart_custom_id = vc.virtuemart_custom_id 
			AND vpc.customfield_value='embedvideo' 
			AND vpc.virtuemart_product_id='".$virtuemart_product_id."' 
		WHERE vc.custom_element='embedvideo' 
		AND e.enabled='1' ";
		$db->setQuery($q);
		return $db->loadObjectList();
	}	
	
	
	function getAddproductItemid()
	{
		$lang = JFactory::getLanguage();
		$db 	= JFactory::getDBO();
		$q = "SELECT `id` FROM `#__menu` WHERE `link`='index.php?option=com_vmvendor&view=addproduct' AND `type`='component'  AND ( language ='".$lang->getTag()."' OR language='*') AND `published`='1' ";
		$db->setQuery($q);
		return $addproduct_itemid = $db->loadResult();
	}
	
}
?>