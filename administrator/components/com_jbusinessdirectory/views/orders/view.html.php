<?php
/**
 * @order     Joomlp.Administrator
 * @suborder  com_jbusinessdirectory
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * The HTML Menus Menu Menus View.
 *
 * @order     Joomlp.Administrator
 * @suborder  com_jbusinessdirectory

 */

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php';
JHTML::_('script', 	'components/com_jbusinessdirectory/assets/js/common.js');

class JBusinessDirectoryViewOrders extends JViewLegacy
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

		$this->states = JBusinessDirectoryHelper::getOrderStates();
		
		JBusinessDirectoryHelper::addSubmenu('orders');

		
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
		
		JToolBarHelper::title(   'J-BusinessDirectory : '.JText::_('LNG_ORDERS'), 'generic.png' );
		//JToolbarHelper::addNew('order.add');
		
		
		if (($canDo->get('core.edit')))
		{
			JToolbarHelper::editList('order.edit');
		}
		
		if($canDo->get('core.delete')){
			JToolbarHelper::divider();
			JToolbarHelper::deleteList('', 'orders.delete');
		}
			
		if ($canDo->get('core.admin'))
		{
			JToolbarHelper::preferences('com_jbusinessdirectory');
		}
		
		JToolbarHelper::divider();
		JToolBarHelper::custom( 'orders.back', 'dashboard', 'dashboard', JText::_("LNG_CONTROL_PANEL"), false, false );
		JToolBarHelper::help('', false, DOCUMENTATION_URL.'businessdiradmin.html#orders' );
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
				'p.ordering' => JText::_('JGRID_HEADING_ORDERING'),
				'p.status' => JText::_('JSTATUS'),
				'p.name' => JText::_('JGLOBAL_TITLE'),
				'p.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
