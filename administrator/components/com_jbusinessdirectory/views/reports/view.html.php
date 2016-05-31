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

class JBusinessDirectoryViewReports extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->reports		= $this->get('Reports');
		$this->state		= $this->get('State');
	
		$this->states		= $this->get('States');
		
		JBusinessDirectoryHelper::addSubmenu('reports');

		$layout = JRequest::getVar("layout");
		if(isset($layout)){
			$tpl = $layout;
		}else{
			$tpl="standard";
		}
		
		$this->report	 = $this->get('ReportData');
		$this->params =  JBusinessDirectoryHelper::getCompanyParams();
		
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
	 */
	protected function addToolbar()
	{
		$canDo = JBusinessDirectoryHelper::getActions();
		$user  = JFactory::getUser();
		
		JToolBarHelper::title('J-BusinessDirectory : '.JText::_('LNG_REPORTS'), 'generic.png' );
		
		if ($canDo->get('core.create') || (count($user->getAuthorisedCategories('com_jbusinessdirectory', 'core.create'))) > 0 )
		{
			JToolbarHelper::addNew('report.add');
		}
		
		if (($canDo->get('core.edit')))
		{
			JToolbarHelper::editList('report.edit');
		}
		
		if($canDo->get('core.delete')){
			JToolbarHelper::divider();
			JToolbarHelper::deleteList('', 'reports.delete');
		}
		
		$reportId = JRequest::getVar("reportId", null);
		if(!empty($reportId)){
			JToolBarHelper::custom('reports.exportReportToCSV', 'download', 'stats.png', JText::_('LNG_EXPORT_CSV'), false, false );
		}
		
		if ($canDo->get('core.admin'))
		{
			JToolbarHelper::preferences('com_jbusinessdirectory');
		}
		
		JToolbarHelper::divider();
		JToolBarHelper::custom( 'ratings.back', 'dashboard', 'dashboard', JText::_("LNG_CONTROL_PANEL"), false, false );
		JToolBarHelper::help('', false, DOCUMENTATION_URL.'businessdiradmin.html#reports' );
	}
}
