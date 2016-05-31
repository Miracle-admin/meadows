<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

if(!defined('DS')) {
   define('DS', DIRECTORY_SEPARATOR);
}

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_alphauserpoints')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// include version
require_once (JPATH_COMPONENT.DS.'assets'.DS.'includes'.DS.'version.php');
require_once (JPATH_COMPONENT.DS.'assets'.DS.'includes'.DS.'functions.php');
require_once (JPATH_COMPONENT.DS.'assets'.DS.'includes'.DS.'pane.php');


// include CSS
$document	= JFactory::getDocument();
$document->addStyleSheet(JURI::base().'components/com_alphauserpoints/assets/css/aup.css');


// Require the base controller
require_once (JPATH_COMPONENT.DS.'controller.php');

// Create the controller
$controller = new alphauserpointsController();

// Perform the Request task
$controller->execute( JFactory::getApplication()->input->get( 'task', 'cpanel', 'cmd' ) );
$controller->redirect();

aup_CopySite();

?>