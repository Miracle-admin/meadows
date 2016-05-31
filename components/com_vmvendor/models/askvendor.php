<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
class VmvendorModelAskvendor extends JModelForm
{
	protected $vendorcontacts;
	protected $productname;
	
	public function getVendorcontacts()
	{
		$cparams 	= JComponentHelper::getParams('com_vmvendor');
		$naming 	= $cparams->get('naming');
		$db 		= JFactory::getDBO();
		$app 			= JFactory::getApplication();
		$vendor_userid	= $app->input->get('vendoruserid','','INT');
		
		if( $vendor_userid =='' )
		{
			$q = "SELECT vv.virtuemart_user_id 
			FROM #__virtuemart_vmusers vv
			JOIN #__virtuemart_products vp ON vp.virtuemart_vendor_id = vv.virtuemart_vendor_id
			WHERE vp.virtuemart_product_id='".$app->input->get('virtuemart_product_id','','INT')."' ";
			$db->setQuery($q);
			$vendor_userid = $db->loadResult();
		}
		$q = "SELECT `".$naming."` , `email` FROM `#__users` WHERE `id`='".$vendor_userid."' ";
		$db->setQuery($q);
		$this->vendorcontacts = $db->loadRow();
		return $this->vendorcontacts;
	}
	public function getProductname()
	{
		$db = JFactory::getDBO();
		$app 			= JFactory::getApplication();
		$virtuemart_product_id = $app->input->get('productid','','INT');
		if($virtuemart_product_id!='')
		{
                    if (!class_exists('VmConfig')) {
                        require JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php';
                    }
                        VmConfig::loadConfig();
			$q = "SELECT vpl.`product_name` 
			FROM `#__virtuemart_products_".VMLANG."` vpl 
			WHERE `virtuemart_product_id` ='".$virtuemart_product_id."' ";
			$db->setQuery($q);
			$productname = $db->loadResult();
			$this->productname = $productname;
			return $this->productname;
		}
	}
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_vmvendor.askvendor', 'askvendor', array('control' => 'jform', 'load_data' => true));
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