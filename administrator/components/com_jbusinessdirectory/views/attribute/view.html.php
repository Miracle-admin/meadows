<?php
/*------------------------------------------------------------------------
# JAdManager
# author SoftArt
# copyright Copyright (C) 2012 SoftArt.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.SoftArt.com
# Technical Support:  Forum - http://www.SoftArt.com/forum/j-admanger-forum/?p=1
-------------------------------------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.pane');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php';

JBusinessUtil::includeValidation();

class JBusinessDirectoryViewAttribute extends JViewLegacy
{

protected $item;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null){
	
		$this->item	 = $this->get('Item');
		$this->state = $this->get('State');
		$this->attributeTypes = $this->get('AttributeTypes');
		$this->attributeOptions = $this->get('AttributeOptions');
		
		$this->states		= JBusinessDirectoryHelper::getStatuses();
		$this->translations = JBusinessDirectoryTranslations::getAllTranslations(ATTRIBUTE_TRANSLATION,$this->item->id);
		$this->languages = JBusinessUtil::getLanguages();
		
		$this->appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
				
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

		JToolbarHelper::title(JText::_($isNew ? 'COM_JBUSINESSDIRECTORY_NEW_ITEM' : 'COM_JBUSINESSDIRECTORY_EDIT_ITEM'), 'menu.png');
		
		if ($canDo->get('core.edit')){
			JToolbarHelper::apply('attribute.apply');
			JToolbarHelper::save('attribute.save');
		}
		
		JToolbarHelper::cancel('attribute.cancel', 'JTOOLBAR_CLOSE');
		
		JToolbarHelper::divider();
		JToolbarHelper::help('JHELP_JBUSINESSDIRECTORY_ATTRIBUTE_EDIT');
	}

	
}