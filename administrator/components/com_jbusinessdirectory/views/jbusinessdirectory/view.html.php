<?php
/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );
JHtml::_('script',  'components/com_jbusinessdirectory/assets/js/jquery-ui.js');

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php';

class JBusinessDirectoryViewJBusinessDirectory extends JViewLegacy
{
	function display($tpl = null)
	{
		
		$this->addToolbar();
		
		$this->appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		$this->statistics = $this->get("Statistics");
		
		$this->income = $this->get("Income");
		
		
		parent::display($tpl);
		
	}	
	
	protected function addToolbar()
	{
		//require_once JPATH_COMPONENT.'/helpers/menus.php';
	
		JToolBarHelper::title(JText::_('COM_JBUSINESSDIRECTORY'), 'menumgr.png');
		$canDo = JBusinessDirectoryHelper::getActions();
		if ($canDo->get('core.admin'))
		{
			JToolbarHelper::preferences('com_jbusinessdirectory');
		}
		
	}
}