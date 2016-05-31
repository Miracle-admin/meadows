<?php

/**
 * @version     1.0.0
 * @package     com_vmvendor
 * @copyright   Copyright (C) 2015. All rights rserved
 * @license     GNU General Public License version 3 ; see LICENSE.txt
 * @author      Nordmograph <contact@nordmograph.com> - http://www.nordmograph.com/extensions
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Vmvendor.
 */
class VmvendorViewPointsactivities extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        $this->state = $this->get('State');
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }

        VmvendorHelper::addSubmenu('pointsactivities');

        $this->addToolbar();

        $this->sidebar = JHtmlSidebar::render();
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since	1.6
     */
    protected function addToolbar() {
        require_once JPATH_COMPONENT . '/helpers/vmvendor.php';

        $state = $this->get('State');
        $canDo = VmvendorHelper::getActions($state->get('filter.category_id'));

        JToolBarHelper::title(JText::_('COM_VMVENDOR_TITLE_POINTSACTIVITIES'), 'pointsactivities.png');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/pointsactivity';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
                JToolBarHelper::addNew('pointsactivity.add', 'JTOOLBAR_NEW');
            }

            if ($canDo->get('core.edit') && isset($this->items[0])) {
                JToolBarHelper::editList('pointsactivity.edit', 'JTOOLBAR_EDIT');
            }
        }

        if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0]->state)) {
                JToolBarHelper::divider();
                JToolBarHelper::custom('pointsactivities.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
                JToolBarHelper::custom('pointsactivities.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            } else if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('', 'pointsactivities.delete', 'JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->state)) {
                JToolBarHelper::divider();
                JToolBarHelper::archiveList('pointsactivities.archive', 'JTOOLBAR_ARCHIVE');
            }
            if (isset($this->items[0]->checked_out)) {
                JToolBarHelper::custom('pointsactivities.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
        }

        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
            if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
                JToolBarHelper::deleteList('', 'pointsactivities.delete', 'JTOOLBAR_EMPTY_TRASH');
                JToolBarHelper::divider();
            } else if ($canDo->get('core.edit.state')) {
                JToolBarHelper::trash('pointsactivities.trash', 'JTOOLBAR_TRASH');
                JToolBarHelper::divider();
            }
        }

        if ($canDo->get('core.admin')) {
            JToolBarHelper::preferences('com_vmvendor');
        }
		 if ($canDo->get('core.admin')) {
           // JToolBarHelper::custom('pointsactivities.recalculate', 'refresh.png', 'refresh_f2.png', 'JTOOLBAR_RECALCULATE', true);
        }

        //Set sidebar action - New in 3.0
        JHtmlSidebar::setAction('index.php?option=com_vmvendor&view=pointsactivities');

        $this->extra_sidebar = '';
        
    }

	protected function getSortFields()
	{
		return array(
		'a.id' => JText::_('JGRID_HEADING_ID'),
		'a.userid' => JText::_('COM_VMVENDOR_POINTSACTIVITIES_USERID'),
		'a.points' => JText::_('COM_VMVENDOR_POINTSACTIVITIES_POINTS'),
		'a.insert_date' => JText::_('COM_VMVENDOR_POINTSACTIVITIES_INSERT_DATE'),
		'a.status' => JText::_('COM_VMVENDOR_POINTSACTIVITIES_STATUS'),
		'a.approved' => JText::_('COM_VMVENDOR_POINTSACTIVITIES_APPROVED'),
		'a.keyreference' => JText::_('COM_VMVENDOR_POINTSACTIVITIES_KEYREFERENCE'),
		'a.datareference' => JText::_('COM_VMVENDOR_POINTSACTIVITIES_DATAREFERENCE'),
		);
	}

}
