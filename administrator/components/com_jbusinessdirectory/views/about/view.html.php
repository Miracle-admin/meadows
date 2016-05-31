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


// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );




class JBusinessDirectoryViewAbout extends JViewLegacy
{
	function display($tpl = null)
	{
		JToolBarHelper::title(JText::_('LNG_ABOUT'), 'generic.png');	
		// JRequest::setVar( 'hidemainmenu', 1 );  
		JToolBarHelper::custom( 'back', 'back.png', 'back.png', 'Back',false, false );
		parent::display($tpl);
	}
	
}