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
 * The HTML Menus Menu Menus View.
 *
 * @package    JBusinessDirectory
 * @subpackage  com_jbusinessdirectory

 */

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php';

class JBusinessDirectoryViewCompanies extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		$this->statuses		= $this->get('Statuses');
		$this->states		= $this->get('States');
		$this->companyTypes	= $this->get('CompanyTypes');
		
		$layout = JRequest::getVar("layout");
		if(isset($layout)){
			$tpl = $layout;
			$this->categories = $this->get('MainCategories');
			$this->subCategories = $this->get('SubCategories');
		}
		
		JBusinessDirectoryHelper::addSubmenu('companies');

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
		JHtml::_('bootstrap.modal', 'collapseModal');
		$canDo = JBusinessDirectoryHelper::getActions();
		$user  = JFactory::getUser();
		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');
		
		JToolBarHelper::title('J-BusinessDirectory : '.JText::_('LNG_COMPANIES'), 'generic.png' );
		
		if ($canDo->get('core.create') || (count($user->getAuthorisedCategories('com_jbusinessdirectory', 'core.create'))) > 0 ){
			JToolbarHelper::addNew('company.add');
		}
		
		if (($canDo->get('core.edit'))){
			JToolbarHelper::editList('company.edit');
		}
		
		if ($canDo->get('core.create'))
		{
			$dhtml = '<button data-toggle="modal" onclick="jQuery( \'#collapseModal\' ).modal(\'show\'); return true;" class="btn btn-small">
			<i class="icon-checkbox-partial" title="'.JText::_('JTOOLBAR_BATCH').'"></i>'.JText::_('JTOOLBAR_BATCH').'</button>';
			$bar->appendButton('Custom', $dhtml, 'batch');
		}
		
		
		if($canDo->get('core.delete')){
			JToolbarHelper::divider();
			JToolbarHelper::deleteList('', 'companies.delete');
		}
		
		JToolbarHelper::divider();
		
		
		$dhtml = '<button data-toggle="modal" onclick="jQuery( \'#import-model\' ).modal(\'show\'); return true;" class="btn btn-small">
		<i class="icon-upload" title="'.JText::_('LNG_IMPORT_CSV').'"></i>'.JText::_('LNG_IMPORT_CSV').'</button>';
		$bar->appendButton('Custom', $dhtml, 'companies.importFromCsv');
		
		$dhtml = '<button data-toggle="modal" onclick="jQuery( \'#export-model\' ).modal(\'show\'); return true;" class="btn btn-small">
		<i class="icon-download" title="'.JText::_('LNG_EXPORT_CSV').'"></i>'.JText::_('LNG_EXPORT_CSV').'</button>';
		$bar->appendButton('Custom', $dhtml, 'companies.showExportCsv');
		

		if ($canDo->get('core.admin')){
			JToolbarHelper::preferences('com_jbusinessdirectory');
		}
		
		JToolbarHelper::divider();
		JToolBarHelper::custom( 'companies.back', 'dashboard', 'dashboard', JText::_("LNG_CONTROL_PANEL"), false, false );
		JToolBarHelper::help('', false, DOCUMENTATION_URL.'businessdiradmin.html#business-listings' );
	}
}
