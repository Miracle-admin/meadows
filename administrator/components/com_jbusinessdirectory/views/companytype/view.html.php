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
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php';
class JBusinessDirectoryViewCompanyType extends JViewLegacy
{
	protected $item;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null){
	
		$this->item	 = $this->get('Item');
		$this->state = $this->get('State');
		
		$this->appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$this->translations = JBusinessDirectoryTranslations::getAllTranslations(TYPE_TRANSLATION,$this->item->id);
		$this->languages = JBusinessUtil::getLanguages();
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		parent::display($tpl);
		$this->addToolbar();
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

		JToolbarHelper::title(JText::_($isNew ? 'COM_JBUSINESSDIRECTORY_NEW_COMPANY_TYPE' : 'COM_JBUSINESSDIRECTORY_EDIT_COMPANY_TYPE'), 'menu.png');
		
		if ($canDo->get('core.edit')){
			JToolbarHelper::apply('companytype.apply');
			JToolbarHelper::save('companytype.save');
		}
		
		JToolbarHelper::cancel('companytype.cancel', 'JTOOLBAR_CLOSE');
		
		JToolbarHelper::divider();
		JToolbarHelper::help('JHELP_JBUSINESSDIRECTORY_COMPANY_TYPE_EDIT');
	}
}
