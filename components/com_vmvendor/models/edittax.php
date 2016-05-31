<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');


class VmvendorModelEdittax extends JModelForm
{
	/**
	 * @var string msg
	 */
	//protected $msg;
 
	public function getThistaxdata() 
	{
		$app	= JFactory::getApplication();
		$taxid 	= $app->input->get('taxid','','INT');
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();		
		$q = "SELECT `virtuemart_vendor_id` FROM `#__virtuemart_vendors` WHERE `created_by`='".$user->id."' "; 
		$db->setQuery($q);
		$my_vendorid = $db->loadResult();
		$q ="SELECT  `virtuemart_calc_id`, `calc_name` , `calc_descr` , `calc_kind` , `calc_value_mathop` , `calc_value` , `calc_currency` , `publish_up` , `publish_down` 
		FROM `#__virtuemart_calcs` 		
		WHERE  `virtuemart_vendor_id`='".$my_vendorid."'  AND `virtuemart_calc_id` ='".$taxid."' ";
		$db->setQuery($q);
		$taxdata 	= $db->loadRow();
		$this->taxdata = $taxdata;
		return $this->taxdata;
	}
	
	
	public function getThistaxcats() 
	{
		$app	= JFactory::getApplication();
		$taxid 	= $app->input->get('taxid','','INT');
		$db 	= JFactory::getDBO();
		$q ="SELECT  virtuemart_category_id FROM `#__virtuemart_calc_categories`  WHERE virtuemart_calc_id ='".$taxid."' ";
		$db->setQuery($q);
		$taxcats 	= $db->loadObjectList();
		$taxcats_ids = array();
		foreach ($taxcats as $taxcat){
			array_push($taxcats_ids ,  $taxcat->virtuemart_category_id);	
		}	
		$this->tax_cats = $taxcats_ids;
		return $this->tax_cats;
	}
	
	
	public function getVendorshoppergroups() 
	{  // shared shoppergroups or vendor's own
		$db 	= JFactory::getDBO();
		$user	= JFactory::getUser();
		
		$q = "SELECT `virtuemart_vendor_id` FROM `#__virtuemart_vendors` WHERE `created_by`='".$user->id."' "; 
		$db->setQuery($q);
		$my_vendorid = $db->loadResult();
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
		$form = $this->loadForm('com_vmvendor.edittax', 'edittax', array('control' => 'jform', 'load_data' => true));
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