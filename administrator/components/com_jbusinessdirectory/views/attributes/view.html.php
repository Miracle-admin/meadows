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

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php';

class JBusinessDirectoryViewAttributes extends JViewLegacy
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
		$this->states		= $this->get('States');
	
		JBusinessDirectoryHelper::addSubmenu('attributes');
	
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
	
		JToolBarHelper::title('J-BusinessDirectory : '.JText::_('LNG_ATTRIBUTES'), 'generic.png' );
	
		if ($canDo->get('core.create') || (count($user->getAuthorisedCategories('com_jbusinessdirectory', 'core.create'))) > 0 )
		{
			JToolbarHelper::addNew('attribute.add');
		}
	
		if (($canDo->get('core.edit')))
		{
			JToolbarHelper::editList('attribute.edit');
		}
	
		if($canDo->get('core.delete')){
			JToolbarHelper::divider();
			JToolbarHelper::deleteList('','attributes.delete');
		}
	
		if ($canDo->get('core.admin'))
		{
			JToolbarHelper::preferences('com_jbusinessdirectory');
		}
	
		JToolbarHelper::divider();
		JToolBarHelper::custom( 'attributes.back', 'dashboard', 'dashboard', JText::_("LNG_CONTROL_PANEL"), false, false );
		JToolBarHelper::help('', false, DOCUMENTATION_URL.'businessdiradmin.html#custom-attributes' );
	}	
}