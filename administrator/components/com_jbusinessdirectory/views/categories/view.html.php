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
require_once( JPATH_COMPONENT_ADMINISTRATOR.'/library/category_lib.php');

/**
 * Categories view class for the Category package.
 *
 */
class JBusinessDirectoryViewCategories extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		
		//$service=new JBusinessDirectorCategoryLib();
		//$service->updateCategoryStructure();
		
		$this->state         = $this->get('State');
		$this->items         = $this->get('Items');
		
		//$model=new JBusinessDirectoryModelManageCategories();
		//$categories =$model->getCategories();
		//var_dump($this->items);
		$this->pagination    = $this->get('Pagination');
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));

			return false;
		}

		// Preprocess the list of items to find ordering divisions.
		foreach ($this->items as &$item)
		{
			$this->ordering[$item->parent_id][] = $item->id;
		}

		// Levels filter.
		$options	= array();
		$options[]	= JHtml::_('select.option', '1', JText::_('J1'));
		$options[]	= JHtml::_('select.option', '2', JText::_('J2'));
		$options[]	= JHtml::_('select.option', '3', JText::_('J3'));
		$options[]	= JHtml::_('select.option', '4', JText::_('J4'));
		$options[]	= JHtml::_('select.option', '5', JText::_('J5'));
		$options[]	= JHtml::_('select.option', '6', JText::_('J6'));
		$options[]	= JHtml::_('select.option', '7', JText::_('J7'));
		$options[]	= JHtml::_('select.option', '8', JText::_('J8'));
		$options[]	= JHtml::_('select.option', '9', JText::_('J9'));
		$options[]	= JHtml::_('select.option', '10', JText::_('J10'));

		$this->f_levels = $options;

		$layout = JRequest::getVar("layout");
		if(isset($layout)){
			$tpl = $layout;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		JHtml::_('bootstrap.modal', 'collapseModal');
		JBusinessDirectoryHelper::addSubmenu('categories');
		
		$categoryId	= $this->state->get('filter.category_id');
	
		$canDo = JBusinessDirectoryHelper::getActions();
		$user		= JFactory::getUser();

		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');
		
		// Prepare the toolbar.
		JToolbarHelper::title(JText::_('LNG_CATEGORIES'));

		if ($canDo->get('core.create'))
		{
			JToolbarHelper::addNew('category.add');
		}

		if ($canDo->get('core.edit') || $canDo->get('core.edit.own'))
		{
			JToolbarHelper::editList('category.edit');
		}

		if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::publish('categories.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('categories.unpublish', 'JTOOLBAR_UNPUBLISH', true);
		}

		JToolbarHelper::deleteList('', 'categories.delete', 'JTOOLBAR_DELETE');
		
		JToolbarHelper::divider();
		$dhtml = '<button data-toggle="modal" onclick="jQuery( \'#import-model\' ).modal(\'show\'); return true;" class="btn btn-small">
		<i class="icon-upload" title="'.JText::_('LNG_IMPORT_CSV').'"></i>'.JText::_('LNG_IMPORT_CSV').'</button>';
		$bar->appendButton('Custom', $dhtml, 'categories.importFromCsv');
		
		$dhtml = '<button data-toggle="modal" onclick="jQuery( \'#export-model\' ).modal(\'show\'); return true;" class="btn btn-small">
		<i class="icon-download" title="'.JText::_('LNG_EXPORT_CSV').'"></i>'.JText::_('LNG_EXPORT_CSV').'</button>';
		$bar->appendButton('Custom', $dhtml, 'categories.showExportCsv');
		
		JToolbarHelper::divider();
		
		JToolbarHelper::custom('categories.rebuild', 'refresh.png', 'refresh_f2.png', 'JTOOLBAR_REBUILD', false);
		
		JToolBarHelper::custom( 'companies.back', 'dashboard', 'dashboard', JText::_("LNG_CONTROL_PANEL"), false, false );
		JToolBarHelper::help('', false, DOCUMENTATION_URL.'businessdiradmin.html#categories' );

	}

	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 *
	 * @since   3.0
	 */
	protected function getSortFields()
	{
		return array(
			'a.lft' => JText::_('JGRID_HEADING_ORDERING'),
			'a.published' => JText::_('JSTATUS'),
			'a.title' => JText::_('JGLOBAL_TITLE'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
