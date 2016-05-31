<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');


class VmvendorModelEditshipment extends JModelForm
{
	/**
	 * @var string msg
	 */
	//protected $msg;
 
	public function getThisShipmentdata() 
	{
		$app			= JFactory::getApplication();
		$shipmentid 	= $app->input->get('shipmentid','','INT');
		//$extensionid	= $app->input->get('extensionid','','INT');
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();		

		if (!class_exists( 'VmConfig' ))
			require JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php';
		VmConfig::loadConfig();

		$cparams 		= JComponentHelper::getParams('com_vmvendor');
		$multilang_mode = $cparams->get('multilang_mode', 0);

		$my_vendorid = VmvendorModelEditshipment::getVendorid();

		$q ="SELECT  vs.`virtuemart_shipmentmethod_id`, vs.`shipment_params` , vs.`published` ,
		vsl.`shipment_name`, vsl.`shipment_desc` 
		FROM `#__virtuemart_shipmentmethods` vs	
		LEFT JOIN `#__virtuemart_shipmentmethods_".VMLANG."` vsl ON vs.`virtuemart_shipmentmethod_id` = vsl.`virtuemart_shipmentmethod_id` 
		WHERE  vs.`virtuemart_vendor_id`='".$my_vendorid."'  
		AND vs.`virtuemart_shipmentmethod_id` ='".$shipmentid."' 
		
		AND vs.shared='0'" ;
//AND vs.shipment_jplugin_id='".$extensionid."' 
		$db->setQuery($q);
		$shipmentdata 	= $db->loadRow();
		$this->shipmentdata = $shipmentdata;
		//var_dump($shipmentdata);
		return $this->shipmentdata;
	}
	
	
	static function get_string_between($string, $start, $end){
		$string = " ".$string;
		$ini = strpos($string,$start);
		if ($ini == 0) return "";
		$ini += strlen($start);
		$len = strpos($string,$end,$ini) - $ini;
		return substr($string,$ini,$len);
	}





	
	
	public function getVendorshoppergroups() 
	{  // shared shoppergroups or vendor's own
		$db 	= JFactory::getDBO();
		$user	= JFactory::getUser();
		
		$my_vendorid = VmvendorModelEditshipment::getVendorid();

		$q ="SELECT  virtuemart_shoppergroup_id ,  shopper_group_name 
		FROM `#__virtuemart_shoppergroups` 
		WHERE ( shared ='1' OR virtuemart_vendor_id='".$my_vendorid."' ) 
		AND published ='1'  ";
		$db->setQuery($q);
		$shoppergroups 	= $db->loadObjectList();
		$shoppergroups_ids = array();
		foreach ($shoppergroups as $shoppergroup){
			array_push( $shoppergroups_ids ,  $shoppergroup->virtuemart_shoppergroup_id);	
		}	
		$this->vendor_shoppergroups = $shoppergroups_ids;
		return 	 $this->vendor_shoppergroups;
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
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_vmvendor.editshipment', 'editshipment', array('control' => 'jform', 'load_data' => true));
		if (empty($form))
		{
			return false;
		}

		//$id = $this->getState('contact.id');
		//$params = $this->getState('params');
		//$contact = $this->_item[$id];
		//$params->merge($contact->params);
		return $form;
	}
}
?>