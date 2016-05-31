<?php
/**
 * @copyright	Copyright © 2015 - All rights reserved.
 * @license		GNU General Public License v2.0
 * @generator	hhp://xdsoft/joomla-module-generator/
 */
defined('_JEXEC') or die;

$doc = JFactory::getDocument();
// Include assets
require_once dirname(__FILE__) . '/helper.php';
$doc->addStyleSheet(JURI::root()."modules/mod_developer_analytics/assets/css/style.css");
$doc->addScript(JURI::root()."modules/mod_developer_analytics/assets/js/highcharts.js");
$doc->addScript(JURI::root()."modules/mod_developer_analytics/assets/js/exporting.js");
$doc->addScript(JURI::root()."modules/mod_developer_analytics/assets/js/highcharts-more.js");

JHtml::script(Juri::base() . '/media/system/js/bpopup.js');
$user     = JFactory::getUser();

$planName = JblanceHelper::getBtPlan(); 
$planName = $planName['name'];
$totalFunds=number_format(JblanceHelper::getTotalFund($user->id));
$profile=JblanceHelper::get("userhelper");
$completedOrders=JblanceHelper::getCompletedOrders($user->id);
jimport('joomla.application.component.model');
JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_vmvendor/models');
$vmodel = JModelLegacy::getInstance('Dashboard', 'VmvendorModel');

$db=JFactory::getDbo();
$query="SELECT * FROM #__jblance_visitcounts WHERE uid=".$user->id;
$db->setQuery($query);
$viewsCount=$db->loadObject();
//$rate=JblanceHelper::getRatingHTML($avgRate);
//$ratings=getRatingHTML


require JModuleHelper::getLayoutPath('mod_developer_analytics', $params->get('layout', 'default'));