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
class VmvendorViewEdittax extends JViewLegacy
{
	function display($tpl = null) 
	{
		jimport( 'joomla.form.form' ); 
		JForm::addFormPath(JPATH_COMPONENT.'/models/forms');
		$model  	= $this->getModel('edittax', 'VmvendorModel');
		$this->form	= $this->get('Form');
		$app 	= JFactory::getApplication();
		$cparams 	= JComponentHelper::getParams('com_vmvendor');
		$tax_mode 	= $cparams->get('tax_mode',0);
		if($tax_mode!=2)
		{
			JError::raiseWarning( 100, JText::_('COM_VMVENDOR_EDITTAX_TAX_MODE_DISABLED'). '<br /><input type="button" class="btn btn-default" name="cancel" id="cancelbutton" value="'.JText::_('JCANCEL').'" onclick="history.go(-1)">' );
			return false;	
		}
		$this->taxdata					= $this->get('thistaxdata');
		$this->tax_cats					= $this->get('thistaxcats');
		$this->virtuemart_vendor_id		= $this->get('vendorid');
		$this->vendor_shoppergroups		= $this->get('vendorshoppergroups');
		$data = array( 'calc_name'        	=> $this->taxdata[1] ,
						'calc_descr'        => $this->taxdata[2] ,
						'calc_kind'        	=> $this->taxdata[3] ,
						'taxcatselect'      => $this->tax_cats ,
						'calc_mathop'       => $this->taxdata[4] ,
						'calc_value'        => $this->taxdata[5] 
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
}