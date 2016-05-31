<?php
/**
* @package		AlphaUserPoints for Joomla 3.x
* @copyright	Copyright (C) 2008-2015. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

if(!defined('DS')) {
   define('DS', DIRECTORY_SEPARATOR);
}

// Import file dependencies if needed
if (!function_exists('getProfileLink')) {
	require_once (JPATH_ROOT.DS.'components'.DS.'com_alphauserpoints'.DS.'helpers'.DS.'helpers.php');
}

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');

$list = modAlphaUserPointsMostActiveUsersHelper::getList($params);
require(JModuleHelper::getLayoutPath('mod_alphauserpoints_mostactiveusers'));

?>