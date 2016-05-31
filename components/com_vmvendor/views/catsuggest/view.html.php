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
class VmvendorViewCatsuggest extends JViewLegacy
{
	protected $form;
	// Overwriting JViewLegacy display method
	function display($tpl = null) 
	{
		jimport( 'joomla.form.form' ); 
		JHtml::_('behavior.keepalive');
		JForm::addFormPath(JPATH_COMPONENT.'/models/forms');
		$model  = $this->getModel('catsuggest', 'VmvendorModel');
		$this->form	= $this->get('Form');
		$cparams 		= JComponentHelper::getParams('com_vmvendor');
		$cat_suggest 	= $cparams->get('cat_suggest',1);
		if($cat_suggest==0)
		{
			JError::raiseWarning('100', JText::_('COM_VMVENDOR_CATSUGGEST_DISABLED') );
			return false;
		}
 		$this->virtuemart_vendor_id		= $this->get('vendorid');

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


		// Display the view
		parent::display($tpl);
	}
}