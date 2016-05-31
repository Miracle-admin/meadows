<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_categories
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php';

JHTML::_('script', 'components/com_jbusinessdirectory/assets/js/jquery.upload.js');
JBusinessUtil::includeValidation();
/**
 * HTML View class for the Categories component
 *
 */
class JBusinessDirectoryViewCategory extends JViewLegacy
{
	protected $item;

	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->item = $this->get('Item');
		$this->state = $this->get('State');

		$catId = (int)$this->state->get('category.id');
		
		$this->categoryOptions = JBusinessUtil::getCategoriesOptions(false, $catId, true);
		$this->translations = JBusinessDirectoryTranslations::getAllTranslations(CATEGORY_TRANSLATION,$this->item->id);
		$this->languages = JBusinessUtil::getLanguages();
		
		$this->appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		$input = JFactory::getApplication()->input;

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// Check for tag type

		$input->set('hidemainmenu', true);


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
		$input = JFactory::getApplication()->input;
		$user = JFactory::getUser();
		$userId = $user->get('id');

		$isNew = ($this->item->id == 0);


		// Get the results for each action.
		$canDo = JBusinessDirectoryHelper::getActions();

		// Prepare the toolbar.
		JToolbarHelper::title(JText::_("LNG_CATEGORY"), ($isNew ? 'add' : 'edit'));

		// For new records, check the create permission.
		if ( $canDo->get('core.edit'))
		{
			JToolbarHelper::apply('category.apply');
			JToolbarHelper::save('category.save');
			
		}
		
		if (empty($this->item->id))
		{
			JToolbarHelper::cancel('category.cancel');
		}
		else
		{
			JToolbarHelper::cancel('category.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
