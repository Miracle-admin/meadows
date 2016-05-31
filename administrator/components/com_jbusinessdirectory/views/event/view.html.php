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

JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/manage.companies.js');
JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/jquery.upload.js');
JHTML::_('stylesheet', 	'components/com_jbusinessdirectory/assets/css/jquery.timepicker.css');
JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/jquery.timepicker.min.js');
JHTML::_('script', 'https://maps.google.com/maps/api/js?sensor=true&libraries=places');

JBusinessUtil::includeValidation();
JHTML::_('script', 	'components/com_jbusinessdirectory/assets/js/common.js');

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php';

class JBusinessDirectoryViewEvent extends JViewLegacy
{
	protected $item;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null){
	
		$this->item	 = $this->get('Item');
		$this->state = $this->get('State');

		$this->companies = $this->get('Companies');
		$this->states = JBusinessDirectoryHelper::getStatuses();
		$this->claimDetails = $this->get('ClaimDetails');

		$this->translations = JBusinessDirectoryTranslations::getAllTranslations(EVENT_DESCRIPTION_TRANSLATION,$this->item->id);
		$this->languages = JBusinessUtil::getLanguages();
		
		$this->appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
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

		JToolbarHelper::title(JText::_($isNew ? 'COM_JBUSINESSDIRECTORY_NEW_EVENT' : 'COM_JBUSINESSDIRECTORY_EDIT_EVENT'), 'menu.png');
		
		if ($canDo->get('core.edit')){
			JToolbarHelper::apply('event.apply');
			JToolbarHelper::save('event.save');
		}
		
		if($this->item->id > 0){
			JToolBarHelper::divider();
			JToolBarHelper::custom( 'event.aprove', 'publish.png', 'publish.png', JText::_("LNG_APPROVE"), false, false );
			JToolBarHelper::custom( 'event.disaprove', 'unpublish.png', 'unpublish.png', JText::_("LNG_DISAPPROVE"), false, false );
			JToolBarHelper::divider();
		}
			
		JToolbarHelper::cancel('event.cancel', 'JTOOLBAR_CLOSE');
		
		JToolbarHelper::divider();
		JToolbarHelper::help('JHELP_JBUSINESSDIRECTORY_EVENT_EDIT');
	}
	
}
