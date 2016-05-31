<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
class VmvendorModelCatsuggest extends JModelForm
{
	
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_vmvendor.catsuggest', 'catsuggest', array('control' => 'jform', 'load_data' => true));
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