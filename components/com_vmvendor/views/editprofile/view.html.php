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
class VmvendorViewEditprofile extends JViewLegacy
{
	 protected $form;
	 // Overwriting JViewLegacy display method
	function display($tpl = null) 
	{
		
		// Assign data to the view
		jimport( 'joomla.form.form' ); 
		JForm::addFormPath(JPATH_COMPONENT.'/models/forms');
		$model  = $this->getModel('editprofile', 'VmvendorModel');

		$app 	= JFactory::getApplication();
		$cparams 					= JComponentHelper::getParams('com_vmvendor');
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


		$this->form	= $this->get('Form');
		$this->vendor_data		= $this->get('vendordata');
		$this->vendor_thumb		= $this->get('vendorthumb');
		// Display the view
		$data = array( 'vendor_title'        		=> $this->vendor_data[3] ,
						'vendor_telephone'      	=> $this->vendor_data[4] ,
						'vendor_url'        		=> $this->vendor_data[5] ,
						'vendor_store_desc'     	=> $this->vendor_data[0] ,
						'vendor_terms_of_service'   => $this->vendor_data[1] ,
						'vendor_legal_info'        	=> $this->vendor_data[2] ,
						
						'vendor_address'        	=> $this->vendor_data[6] ,
						'vendor_zip'        		=> $this->vendor_data[7] ,
						'vendor_city'        		=> $this->vendor_data[8] ,
						'vendor_state_id'        	=> $this->vendor_data[9] ,
						'vendor_country_id'        	=> $this->vendor_data[10] ,
						'paypal_email'        		=> $this->vendor_data[12] ,
							   
							 );
				if (!empty($data)) {
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
}