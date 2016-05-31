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
class VmvendorViewMailCustomer extends JViewLegacy
{
	// Overwriting JViewLegacy display method
	function display($tpl = null) 
	{
		$app 			= JFactory::getApplication();
		if($app->input->get('sent','','int')=='1')
		{
			$app->enqueueMessage(JText::_('COM_VMVENDOR_ASKVENDOR_SENT') );
			return false;
		}
		// Assign data to the view
		$this->customercontacts = $this->get('customercontacts');
		$this->orderitem		= $this->get('orderitem');
		// Display the view	
		parent::display($tpl);
	}
}