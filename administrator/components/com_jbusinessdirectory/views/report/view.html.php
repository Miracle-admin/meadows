<?php
/**
 * @report    JBusinessDirectory
 * @subreport  com_jbusinessdirectory
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

class JBusinessDirectoryViewReport extends JViewLegacy
{
	protected $item;
	protected $state; 

	/**
	 * Display the view
	 */
	public function display($tpl = null){
	
		$this->item	 = $this->get('Item');
		$this->item->selected_params = explode(",",$this->item->selected_params);
		$this->item->custom_params = explode(",",$this->item->custom_params);
		$this->state = $this->get('State');
		
		$this->params =  JBusinessDirectoryHelper::getCompanyParams();
		$this->customFeatures = JBusinessDirectoryHelper::getPackageCustomFeatures();
		
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

		JToolbarHelper::title(JText::_($isNew ? 'COM_JBUSINESSDIRECTORY_NEW_REPORT' : 'COM_JBUSINESSDIRECTORY_EDIT_REPORT'), 'menu.png');
		
		if ($canDo->get('core.edit')){
			JToolbarHelper::apply('report.apply');
			JToolbarHelper::save('report.save');
		}
		
		JToolbarHelper::cancel('report.cancel', 'JTOOLBAR_CLOSE');
		
		JToolbarHelper::divider();
		JToolbarHelper::help('JHELP_JBUSINESSDIRECTORY_REPORT_EDIT');
	}
	
}
