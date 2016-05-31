<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

require_once JPATH_COMPONENT.'/helpers/braintreehooks.php';

/**
 * Hooks list view class.
 *
 * @package     Braintreehooks
 * @subpackage  Views
 */
class BraintreehooksViewHooks extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	
	public function display($tpl = null)
	{
		$this->items		 = $this->getModel()->getItems();
		$this->state		 = $this->getModel()->getState();
		$this->pagination	 = $this->getModel()->getPagination();
		$this->filterForm    = $this->getModel()->getFilterForm();
		$this->activeFilters = $this->getModel()->getActiveFilters();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
			return false;
		}
		
		BraintreehooksHelper::addSubmenu('hooks');
		
		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
			$this->addToolbar();
			$this->sidebar = JHtmlSidebar::render();
		}
		
		parent::display($tpl);
	}
	
	/**
	 *	Method to add a toolbar
	 */
	protected function addToolbar()
	{
		$state	= $this->get('State');
		$canDo	= BraintreehooksHelper::getActions();
		$user	= JFactory::getUser();

		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');
		
		JToolBarHelper::title(JText::_('COM_BRAINTREEHOOKS_BRAINTREEHOOKS_VIEW_HOOKS_TITLE'));
		
		if ($canDo->get('core.create'))
		{
			JToolBarHelper::addNew('edithooks.add','JTOOLBAR_NEW');
		}

		if (($canDo->get('core.edit')) && isset($this->items[0]))
		{
			JToolBarHelper::editList('edithooks.edit','JTOOLBAR_EDIT');
		}
		
		if ($canDo->get('core.delete') && isset($this->items[0]))
		{
            JToolBarHelper::deleteList('', 'hooks.delete','JTOOLBAR_DELETE');
		}
		
		// Add a batch button
		if (isset($this->items[0]) && $user->authorise('core.create', 'com_contacts') && $user->authorise('core.edit', 'com_contacts'))
		{
			JHtml::_('bootstrap.modal', 'collapseModal');
			$title = JText::_('JTOOLBAR_BATCH');

			// Instantiate a new JLayoutFile instance and render the batch button
			$layout = new JLayoutFile('joomla.toolbar.batch');

			$dhtml = $layout->render(array('title' => $title));
			$bar->appendButton('Custom', $dhtml, 'batch');
		}
		
		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_braintreehooks');
		}
	}
}
?>