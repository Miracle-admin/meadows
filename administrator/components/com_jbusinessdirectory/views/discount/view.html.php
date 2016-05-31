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

JBusinessUtil::includeValidation();

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php';

class JBusinessDirectoryViewDiscount extends JViewLegacy
{
	protected $item;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null){
	
		$this->item	 = $this->get('Item');
		$this->packages	= $this->get('Packages');
		$this->state = $this->get('State');

		$this->companies = $this->get('Companies');
		$this->states = JBusinessDirectoryHelper::getStatuses();
		$this->claimDetails = $this->get('ClaimDetails');
			
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		$layout = JRequest::getVar("layout");
		
		$this->addToolbar($layout);
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar($layout)
	{
		$canDo = JBusinessDirectoryHelper::getActions();
		$user  = JFactory::getUser();
		
		$input = JFactory::getApplication()->input;
		$input->set('hidemainmenu', true);

		$user  = JFactory::getUser();
		$isNew = ($this->item->id == 0);

		JToolbarHelper::title(JText::_($isNew ? 'COM_JBUSINESSDIRECTORY_NEW_DISCOUNT' : 'COM_JBUSINESSDIRECTORY_EDIT_DISCOUNT'), 'menu.png');
		
		if($layout!="generate"){
			if ($canDo->get('core.edit')){
				JToolbarHelper::apply('discount.apply');
				JToolbarHelper::save('discount.save');
			}
		}else{
			JToolBarHelper::custom( 'discount.generateDiscounts', 'plus', 'plus', JText::_("LNG_GENERATE"), false, false );
		}
		
		JToolbarHelper::cancel('discount.cancel', 'JTOOLBAR_CLOSE');
		
		JToolbarHelper::divider();
		JToolbarHelper::help('JHELP_JBUSINESSDIRECTORY_DISCOUNT_EDIT');
	}
	
}
