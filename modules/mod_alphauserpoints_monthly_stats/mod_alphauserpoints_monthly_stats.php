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

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');

// Import file dependencies if needed
if (!function_exists('getProfileLink')) {
	require_once (JPATH_ROOT.DS.'components'.DS.'com_alphauserpoints'.DS.'helpers'.DS.'helpers.php');
}

$curr_month   = date("m");
$current_year = date("Y");

$selectmonth  = JRequest::getVar('mod_aup_ms_month', $curr_month, 'post', 'int');
$current_year = JRequest::getVar('mod_aup_ms_current_year', $current_year, 'post', 'int');
$previousyear = JRequest::getVar('mod_aup_ms_previousyear', $current_year, 'post', 'int');

if ($curr_month == $selectmonth) {
	$searchdate= $current_year."-".str_pad($selectmonth, 2, "0", STR_PAD_LEFT )."-";
} else {
	$searchdate= $previousyear."-".str_pad($selectmonth, 2, "0", STR_PAD_LEFT )."-";
}

$list = modAlphaUserPointsMonthlyStatsHelper::getList($searchdate, $params);
$currentmonthlist = modAlphaUserPointsMonthlyStatsHelper::getCurrentMonthList($selectmonth, $params);
require(JModuleHelper::getLayoutPath('mod_alphauserpoints_monthly_stats'));

?>