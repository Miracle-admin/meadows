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
class VmvendorViewAskvendor extends JViewLegacy
{
	protected $form;
	// Overwriting JViewLegacy display method
	function display($tpl = null) 
	{
		$app 			= JFactory::getApplication();
		if($app->input->getInt('sent')=='1')
		{
			//$app->enqueueMessage(JText::_('COM_VMVENDOR_ASKVENDOR_SENT') );
			echo JText::_('COM_VMVENDOR_ASKVENDOR_SENT');
			return false;
		}
		jimport( 'joomla.form.form' );
		JHtml::_('behavior.keepalive'); 
		JForm::addFormPath(JPATH_COMPONENT.'/models/forms');
		$model  = $this->getModel('askvendor', 'VmvendorModel');
		$this->form	= $this->get('Form');   
		$this->vendorcontacts 	= $this->get('vendorcontacts');
		$this->productname		= $this->get('productname');
		// Display the view
		parent::display($tpl);
	}
}