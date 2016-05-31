<?php
/**
 * @copyright	Copyright © 2015 - All rights reserved.
 * @license		GNU General Public License v2.0
 * @generator	http://xdsoft/joomla-module-generator/
 */
defined('_JEXEC') or die;

$doc = JFactory::getDocument();
/* Available fields:"num_pjs","project_status", */
// Include assets
$doc->addStyleSheet(JURI::root()."modules/mod_jblance_devdashboard_latest_projects/assets/css/style.css");
$doc->addScript(JURI::root()."modules/mod_jblance_devdashboard_latest_projects/assets/js/script.js");
// $width 			= $params->get("width");

/**
	$db = JFactory::getDBO();
	$db->setQuery("SELECT * FROM #__mod_jblance_devdashboard_latest_projects where del=0 and module_id=".$module->id);
	$objects = $db->loadAssocList();
*/

require_once(dirname(__FILE__).'/helper.php');
$total_row  	= intval($params->get('num_pjs', 2));
$pjStatus       = $params->get('project_status', "COM_JBLANCE_OPEN");


$rows 			= ModJblanceDevdashboardLatestProjectsHelper::getLatestProjects($total_row,$pjStatus);



require JModuleHelper::getLayoutPath('mod_jblance_devdashboard_latest_projects', $params->get('layout', 'default'));