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

require_once JPATH_COMPONENT_SITE.DS.'assets'.DS.'defines.php'; 
require_once JPATH_COMPONENT_SITE.DS.'assets'.DS.'logger.php'; 
require_once JPATH_COMPONENT_SITE.DS.'assets'.DS.'utils.php'; 


class JBusinessDirectoryController extends JControllerLegacy
{
	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	function display( $cachable = false,  $urlparams = array())
	{
		parent::display($cachable, $urlparams);
	}
}

?>