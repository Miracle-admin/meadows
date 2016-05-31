<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2015 Adrien Roussel
 * @license : GNU/GPL v3
 * @Website : http://www.nordmograph.com/extensions
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');
class VmvendorViewEditshipment extends JViewLegacy
{
	function display($tpl = null) 
	{
		jimport( 'joomla.form.form' ); 
		JForm::addFormPath(JPATH_COMPONENT.'/models/forms');
		$model  	= $this->getModel('editshipment', 'VmvendorModel');
		$this->form	= $this->get('Form');
		$app 	= JFactory::getApplication();
		$cparams 	= JComponentHelper::getParams('com_vmvendor');	
		$shipment_mode 	= $cparams->get('shipment_mode',0);
		if($shipment_mode!=1)
		{
			JError::raiseWarning( 100, JText::_('COM_VMVENDOR_EDITSHIPMENT_SHIPMENT_MODE_DISABLED'). ' <input type="button" class="btn" name="cancel" id="cancelbutton" value="'.JText::_('JCANCEL').'" onclick="history.go(-1)">' );
			return false;	
		}
		if(JFactory::getUser()->id<1)
		{
			JError::raiseWarning( 100, JText::_('COM_VMVENDOR_VMVENADD_ONLYLOGGEDIN'). ' <input type="button" class="btn" name="cancel" value="'.JText::_('JCANCEL').'" onclick="history.go(-1)">' );
			return false;	
		}
		$this->shipmentdata	= $this->get('thisshipmentdata');
		//$this->tax_cats	= $this->get('thistaxcats');
		$this->virtuemart_vendor_id		= $this->get('vendorid');
		$this->vendor_shoppergroups		= $this->get('vendorshoppergroups');

		$shipment_params	=	$this->shipmentdata[1];

		$show_on_pdetails		= VmvendorModelEditshipment::get_string_between( $shipment_params , 'show_on_pdetails="', '"|countries' );
		$shipm_countries 		= VmvendorModelEditshipment::get_string_between( $shipment_params , 'countries="', '"|zip_start' );
		$shipm_zip_start 		= VmvendorModelEditshipment::get_string_between( $shipment_params , 'zip_start="', '"|zip_stop' );
		$shipm_zip_stop 		= VmvendorModelEditshipment::get_string_between( $shipment_params , 'zip_stop="', '"|weight_start' );
		$shipm_weight_start 	= VmvendorModelEditshipment::get_string_between( $shipment_params , 'weight_start="', '"|weight_stop' );
		$shipm_weight_stop		= VmvendorModelEditshipment::get_string_between( $shipment_params , 'weight_stop="', '"|weight_unit' );
		$shipm_weight_unit		= VmvendorModelEditshipment::get_string_between( $shipment_params , 'weight_unit="', '"|nbproducts_start' );
		/// no double quote for 2v following lines why ? makes no sense , but it's that way in VM
		$shipm_nbproducts_start = VmvendorModelEditshipment::get_string_between( $shipment_params , 'nbproducts_start=', '|nbproducts_stop' );
		$shipm_nbproducts_stop 	= VmvendorModelEditshipment::get_string_between( $shipment_params , 'nbproducts_stop=', '|orderamount_start' );
		///
		$shipm_orderamount_start= VmvendorModelEditshipment::get_string_between( $shipment_params , 'orderamount_start="', '"|orderamount_stop' );
		$shipm_orderamount_stop	= VmvendorModelEditshipment::get_string_between( $shipment_params , 'orderamount_stop="', '"|shipment_cost' );
		$shipm_shipment_cost	= VmvendorModelEditshipment::get_string_between( $shipment_params , 'shipment_cost="', '"|package_fee' );
		$shipm_package_fee 		= VmvendorModelEditshipment::get_string_between( $shipment_params , 'package_fee="', '"|tax_id' );
		$shipm_tax_id 			= VmvendorModelEditshipment::get_string_between( $shipment_params , 'tax_id="', '"|free_shipment' );
		$shipm_free_shipment 	= VmvendorModelEditshipment::get_string_between( $shipment_params , 'free_shipment="', '"|' );



		$multicountries = str_replace( array('[','"',']'), '', $shipm_countries );
		$countries = explode(',' , $multicountries);


		$data = array( 'shipment_name'        	=> $this->shipmentdata[3] ,
						'shipment_desc'        	=> $this->shipmentdata[4] ,
						'shipment_published'   	=> $this->shipmentdata[2] ,
						'shipment_id'       	=> $this->shipmentdata[0],
						'show_on_pdetails'		=> $show_on_pdetails,
						'multicountries'		=> $countries,
						'ziprange_start'		=> $shipm_zip_start,
						'ziprange_end'			=> $shipm_zip_stop,
						'lowest_weight'			=> $shipm_weight_start,
						'highest_weight'		=> $shipm_weight_stop,
						'weightunit'			=> $shipm_weight_unit,
						'minimum_orderproducts'	=> $shipm_nbproducts_start,
						'maximum_orderproducts'	=> $shipm_nbproducts_stop,
						'minimum_orderamount'	=> $shipm_orderamount_start,
						'maximum_orderamount'	=> $shipm_orderamount_stop,
						'shipment_cost'			=> $shipm_shipment_cost,
						'package_fee'			=> $shipm_package_fee,
						'shipmenttaxrules'		=> $shipm_tax_id,
						'freeshipment_amount'	=> $shipm_free_shipment
		 			);
		if (!empty($data))
		{
			$this->form->bind($data);	
		}

		$profiletypes_mode		= $cparams->get('profiletypes_mode', 0);
		$profileman				= $cparams->get('profileman', '');
		$profiletypes_ids		= $cparams->get('profiletypes_ids');
		
		if($profiletypes_mode>0 && $profiletypes_ids!='')
		{
			require_once JPATH_COMPONENT.'/helpers/getallowedprofiles.php';
			if($profileman =='js')
		   	{
				$allowed = VmvendorAllowedProfiles::getJSProfileallowed($profiletypes_ids);
		    }
		    elseif($profileman =='es')
		    {
				$allowed = VmvendorAllowedProfiles::getESProfileallowed($profiletypes_ids);
		    }
		    if($allowed==0)
		    	return false;	
		    
		}
		parent::display($tpl);
	}

	function get_string_between($string, $start, $end){
		$string = " ".$string;
		$ini = strpos($string,$start);
		if ($ini == 0) return "";
		$ini += strlen($start);
		$len = strpos($string,$end,$ini) - $ini;
		return substr($string,$ini,$len);
	}
}