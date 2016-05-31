<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2015 Adrien Roussel
 * @license : GNU/GPL
 * @Website : http://www.nordmograph.com
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HTML View class for the HelloWorld Component
 */
class VMVendorViewEditproduct extends JViewLegacy
{
	// Overwriting JViewLegacy display method
	function display($tpl = null) 
	{
		$user 	= JFactory::getUser();
		
		
		
                // Assign data to the view
		$this->product_data			= $this->get('productdata');
		$this->product_images		= $this->get('productimages');
		$this->product_files		= $this->get('productfiles');
		$this->price_format			= $this->get('priceformat');
		$this->product_cats			= $this->get('multiplecats');
		$this->product_tags			= $this->get('producttags');
		$this->core_custom_fields	= $this->get('corecustomfields');
		$this->virtuemart_vendor_id	= $this->get('vendorid');
		
		
		$this->productlocation		= $this->get('ProductLocation');		
		$this->geoparams_set		= $this->productlocation['set'];
		$this->be_lat				= $this->productlocation['be_lat'];
		$this->be_lng				= $this->productlocation['be_lng'];
		$this->be_zoom				= $this->productlocation['be_zoom'];
		$this->be_maptype			= $this->productlocation['be_maptype'];
		$this->js_key				= $this->productlocation['js_key'];
		$this->js_client			= $this->productlocation['js_client'];
		$this->js_signature			= $this->productlocation['js_signature'];
                
        $this->plan_max_img ='';
		$this->plan_max_files = '';
		if($user->id>0)
		{
			require_once JPATH_COMPONENT.'/helpers/getvendorplan.php';
			$vendor_plan = VmvendorHelper::getvendorplan( $user->id );
			$vendor_products_count = VmvendorHelper::countVendorProducts( $user->id );
			
			$this->plan_max_products = $vendor_plan->max_products;
			$this->plan_max_img = $vendor_plan->max_img;
			$this->plan_max_files = $vendor_plan->max_files;
			$this->autopublish = $vendor_plan->autopublish;
		}
		
		$cparams    = JComponentHelper::getParams('com_vmvendor');
		$enable_embedvideo		= $cparams->get('enable_embedvideo', 0);
		if($enable_embedvideo )
			$this->getEmbedvideoFields   = $this->get('EmbedvideoFields');

		$product_vendor_id		= $this->product_data[0];
		if($product_vendor_id != $this->virtuemart_vendor_id)
		{
			JError::raiseWarning( 100, '<font color="red"><b>'.JText::_('COM_VMVENDOR_VMVENADD_NOTYOURPRODUCT').'</b></font>');
			return false;
		}
		// Display the view
		parent::display($tpl);
	}
}