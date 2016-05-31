<?php
/*------------------------------------------------------------------------
# mod_featcats - Featured Categories
# ------------------------------------------------------------------------
# author    Jesús Vargas Garita
# Copyright (C) 2010 www.joomlahill.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomlahill.com
# Technical Support:  Forum - http://www.joomlahill.com/forum
-------------------------------------------------------------------------*/

define('_JEXEC', 1);

// No direct access.
defined('_JEXEC') or die;

define( 'DS', DIRECTORY_SEPARATOR );

define('JPATH_BASE', dirname(__FILE__).DS.'..'.DS.'..'.DS.'..' );

if (file_exists(JPATH_BASE . '/defines.php'))
{
	include_once JPATH_BASE . '/defines.php';
}

if (!defined('_JDEFINES'))
{
	require_once JPATH_BASE . '/includes/defines.php';
}

require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

$app = JFactory::getApplication('site');
$app->initialise();

$lang = JFactory::getLanguage();
$lparams = JComponentHelper::getParams('com_languages');
$lang->setLanguage($lparams->get('site'));

$document = JFactory::getDocument();

require_once JPATH_BASE.DS.'administrator'.DS.'components'.DS.'com_modules'.DS.'models'.DS.'module.php';

$modModel = JModelLegacy::getInstance('Module', 'ModulesModel', array('ignore_request' => true));

$mid = JRequest::getInt('mid');

$mymodule = $modModel->getItem($mid);

$myparams = new JRegistry;
$myparams->loadArray($mymodule->params);
$myparams->mid = $mid;

$module = JModuleHelper::getModule('mod_featcats');

$registry = new JRegistry;
$registry->loadString($module->params);
$registry->merge($myparams);
$registry->set('mid', $mid);
$registry->set('ajaxed', 1);

$module->params = $registry->toString();

$renderer	= $document->loadRenderer('module');
echo $renderer->render($module, array('style' => 'none'));
