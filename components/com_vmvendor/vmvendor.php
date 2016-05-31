<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2015 Adrien Roussel
 * @license : GNU/GPL v3
 * @Website : http://www.nordmograph.com/extensions
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import joomla controller library
jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT.'/controller.php';
$app = JFactory::getApplication();
$view = $app->input->get( 'view');
$path = JPATH_COMPONENT.'/controllers/'.$view.'.php';
	if (file_exists($path)) {
		require_once $path;
	}
 $controller = JControllerLegacy::getInstance('Vmvendor');
 
// Create the controller
$classname	= 'VmvendorController'.ucfirst($view);
$controller	= new $classname( );


// Perform the Request task
$controller->execute($app->input->get('task','','cmd'));
$controller->redirect();