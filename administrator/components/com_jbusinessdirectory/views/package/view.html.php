<?php
/**
 * @package    JBusinessDirectory
 * @subpackage  com_jbusinessdirectory
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * The HTML  View.
 */

JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/jquery-ui.js'); 
JHtml::_('script', 'administrator/components/com_jbusinessdirectory/assets/js/ui.multiselect.js');
JHtml::_('stylesheet', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.10/themes/ui-lightness/jquery-ui.css');
JHtml::_('stylesheet', 'administrator/components/com_jbusinessdirectory/assets/css/ui.multiselect.css');

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php';

class JBusinessDirectoryViewPackage extends JViewLegacy
{
	protected $item;
	protected $state; 

	/**
	 * Display the view
	 */
	public function display($tpl = null){
	
		$this->item	 = $this->get('Item');
		$this->state = $this->get('State');

		$this->statuses	= JBusinessDirectoryHelper:: getStatuses();
		
		$this->features = JBusinessDirectoryHelper::getPackageFeatures();
		$this->customFeatures = JBusinessDirectoryHelper::getPackageCustomFeatures();
		
		$this->selectedFeatures = $this->get('SelectedFeatures');
		
		$this->appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$this->translations = JBusinessDirectoryTranslations::getAllTranslations(PACKAGE_TRANSLATION,$this->item->id);
		$this->languages = JBusinessUtil::getLanguages();
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		$this->addToolbar();
		
		parent::display($tpl);
		
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		$canDo = JBusinessDirectoryHelper::getActions();
		$user  = JFactory::getUser();
		
		$input = JFactory::getApplication()->input;
		$input->set('hidemainmenu', true);

		$user  = JFactory::getUser();
		$isNew = ($this->item->id == 0);

		JToolbarHelper::title(JText::_($isNew ? 'COM_JBUSINESSDIRECTORY_NEW_PACKAGE' : 'COM_JBUSINESSDIRECTORY_EDIT_PACKAGE'), 'menu.png');
		
		if ($canDo->get('core.edit')){
			JToolbarHelper::apply('package.apply');
			JToolbarHelper::save('package.save');
		}
		
		JToolbarHelper::cancel('package.cancel', 'JTOOLBAR_CLOSE');
		
		JToolbarHelper::divider();
		JToolbarHelper::help('JHELP_JBUSINESSDIRECTORY_PACKAGE_EDIT');
	}
	
}
