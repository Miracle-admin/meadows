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

// Import file dependencies
require_once (JPATH_COMPONENT.DS.'helpers'.DS.'helpers.php');
// Require the base controller
require_once (JPATH_COMPONENT.DS.'controller.php');

$doc = JFactory::getDocument();
$direction = $doc->direction;
// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');
// Load optional RTL Bootstrap CSS
JHtml::_('bootstrap.loadCss', false, $direction);

// Require specific controller if requested
if ( JFactory::getApplication()->input->get('task', 'display', 'cmd')=='showRSSAUPActivity') {
	JFactory::getApplication()->input->set( 'view', 'rssactivity');
}

//if( $controller = JRequest::getWord('controller')) {
if( $controller = JFactory::getApplication()->input->get('controller', '', 'cmd')) {
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if (file_exists($path)) {
		require_once $path;
	}
} else {
	$controller = JFactory::getApplication()->input->get( 'view', '', 'cmd' );
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if (file_exists($path)) {
		require_once $path;
	}	
} 

// Create the controller
$classname	= 'alphauserpointsController'.ucfirst($controller);
$controller	= new $classname( );


// Perform the Request task
$controller->execute( JFactory::getApplication()->input->get( 'task', 'display', 'cmd' ) );
$controller->redirect();
?>