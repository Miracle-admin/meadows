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
JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/jquery-ui.js'); 

class JBusinessDirectoryViewCategories extends JViewLegacy
{
	function display($tpl = null)
	{
		$state = $this->get('State');
		$this->params = $state->get("parameters.menu");
		
		$categories = $this->get('Categories');
		$this->assignRef('categories', $categories);
		
		$this->appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		parent::display($tpl);

	}
}
?>
