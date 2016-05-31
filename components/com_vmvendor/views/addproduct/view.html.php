<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2015 Adrien Roussel
 * @license : GNU/GPL v3
 * @Website : http://www.nordmograph.com/extensions
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
class VmvendorViewAddproduct extends JViewLegacy
{
	// Overwriting JView display method
	function display($tpl = null) 
	{
		JHtml::_('jquery.framework'); /// included in Bootstrap
		$user 	= JFactory::getUser();
		$app	= JFactory::getApplication();

		$this->price_format			= $this->get('priceformat');
		$this->core_custom_fields	= $this->get('corecustomfields');
		$this->virtuemart_vendor_id	= $this->get('vendorid');
		
		
		if (!class_exists( 'VmConfig' ))
			require JPATH_ADMINISTRATOR .  '/components/com_virtuemart/helpers/config.php';
		VmConfig::loadConfig();
		$safepath	= VmConfig::get( 'forSale_path' );
		
		$cparams    = JComponentHelper::getParams('com_vmvendor');
		$enablefiles 		= $cparams->get('enablefiles', 0);
		$profileman  		= $cparams->get('profileman');
		$paypalemail_field	= $cparams->get('paypalemail_field',1);
		$enable_embedvideo		= $cparams->get('enable_embedvideo', 0);
		
		if($enable_embedvideo )
			$this->getEmbedvideoFields   = $this->get('EmbedvideoFields');

		if($paypalemail_field && $user->id >0 )
		{
			$vendor_paypal_email		= $this->get('VendorPaypalEmail');
			if(!$vendor_paypal_email )
				$app->enqueueMessage( JText::_('COM_VMVENDOR_VMVENADD_MISSINGPAYPALEMAIL'),'warning');
		}

		
		if($enablefiles && $safepath=='')
		{
			JError::raiseWarning( 100, JText::_('COM_VMVENDOR_VMVENADD_SAFEPATHREQUIRED') );
		}
		if(VmConfig::get('multix','none')!='admin')
		{
			JError::raiseWarning( 100, JText::_('COM_VMVENDOR_VMVENADD_MULTIVENDORREQUIRED') );
		}
		
		
		$this->plan_max_img ='';
		$this->plan_max_files = '';
		$this->autopublish =0;// for guests
		if($user->id>0)
		{
			require_once JPATH_COMPONENT.'/helpers/getvendorplan.php';
			$vendor_plan 			= VmvendorHelper::getvendorplan( $user->id );
			$vendor_products_count 	= VmvendorHelper::countVendorProducts( $user->id );
			
			$this->plan_max_products 	= $vendor_plan->max_products;
			$this->plan_max_img 		= $vendor_plan->max_img;
			$this->plan_max_files 		= $vendor_plan->max_files;
			$this->autopublish 			= $vendor_plan->autopublish;
			if($this->autopublish=='2')
				$this->autopublish=0;
			
			if( $vendor_plan->max_products >0 && $vendor_products_count >= $vendor_plan->max_products )
			{
				JError::raiseWarning( 100, sprintf( JText::_('COM_VMVENDOR_PLAN_MAXPRODREACHED') , $vendor_plan->max_products , $vendor_plan->title ) );
				return false;
			}
		}


		$allowed = 1;
		$profiletypes_mode		= $cparams->get('profiletypes_mode', 0);
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


 
		// Display the view
		parent::display($tpl);
	}
}