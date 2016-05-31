<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2015 Adrien Roussel
 * @license : GNU/GPL v3
 * @Website : http://www.nordmograph.com/extensions
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modelitem');
class VmvendorModelAddproduct extends JModelItem
{
	public function getPriceformat() 
	{
		$db = JFactory::getDBO();
		$q = "SELECT curr.*  
		FROM `#__virtuemart_currencies` AS curr 
		LEFT JOIN `#__virtuemart_vendors` AS vend ON vend.`vendor_currency` = curr.`virtuemart_currency_id`  
		WHERE vend.`virtuemart_vendor_id` = '1' ";
		$db->setQuery($q);
		$price_format 	= $db->loadRow();
		$this->_price_format = $price_format;
		return $this->_price_format;
	}
	
	public function getCorecustomfields() 
	{
		$db = JFactory::getDBO();
		$q ="SELECT `virtuemart_custom_id` , `custom_parent_id` , `virtuemart_vendor_id` , `custom_jplugin_id` , `custom_title` , `custom_tip` , `custom_value`, `custom_desc` , `field_type` , `is_list` , `shared`  
		FROM `#__virtuemart_customs`
		WHERE `custom_jplugin_id`='0' 
		AND `admin_only`='0' 
		AND `published`='1' 
		AND `field_type`!='R'  AND `field_type`!='Z' AND is_cart_attribute!='1'
		ORDER BY `ordering` ASC , `virtuemart_custom_id` ASC ";
		// AND `custom_element`!='' 
		$db->setQuery($q);
		$core_custom_fields	= $db->loadObjectList();
		$this->_core_custom_fields = $core_custom_fields;
		return $this->_core_custom_fields;
	}
	
	public function getVendorid() 
	{
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();
		$q = "SELECT `virtuemart_vendor_id` FROM `#__virtuemart_vmusers` WHERE `virtuemart_user_id` = '".$user->id."' ";
		$db->setQuery($q);
		$virtuemart_vendor_id = $db->loadResult();
		$this->_virtuemart_vendor_id = $virtuemart_vendor_id;
		return $this->_virtuemart_vendor_id;
	}
	
	static function getManufacturers() 
	{
        if (!class_exists('VmConfig'))
            require JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php';
        VmConfig::loadConfig();
		$db = JFactory::getDBO();
		$q = "SELECT vm.virtuemart_manufacturer_id , vml.mf_name 
		FROM `#__virtuemart_manufacturers` vm 
		LEFT JOIN `#__virtuemart_manufacturers_".VMLANG."` vml ON vm.virtuemart_manufacturer_id = vml.virtuemart_manufacturer_id
		WHERE vm.published='1' ORDER BY mf_name ASC ";
		$db->setQuery($q);
		$manufacturers = $db->loadObjectList();
		return $manufacturers;
	}	

	static function getVendorPaypalEmail() 
	{
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();
		$q = "SELECT paypal_email 
		FROM `#__vmvendor_paypal_emails` 
		WHERE userid='".$user->id."'";
		$db->setQuery($q);
		return $db->loadResult();
	}
	
	static function getEmbedvideoFields() 
	{
		$db 	= JFactory::getDBO();
		$q = "SELECT vc.virtuemart_custom_id, vc.custom_title, vc.custom_tip
		FROM #__virtuemart_customs vc
		LEFT JOIN #__extensions e ON e.extension_id = vc.custom_jplugin_id 
		WHERE vc.custom_element='embedvideo' 
		AND e.enabled='1' ";
		$db->setQuery($q);
		return $db->loadObjectList();
	}	
}