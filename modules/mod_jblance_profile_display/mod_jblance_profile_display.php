<?php
/**
 * @copyright	Copyright © 2016 - All rights reserved.
 * @license		GNU General Public License v2.0
 * @generator	http://xdsoft/joomla-module-generator/
 */
defined('_JEXEC') or die;
require_once dirname(__FILE__) . '/helper.php';
$doc = JFactory::getDocument();
// Include assets
$doc->addStyleSheet(JURI::root()."modules/mod_jblance_profile_display/assets/css/style.css");
$doc->addScript(JURI::root()."modules/mod_jblance_profile_display/assets/js/script.js");
// $width 			= $params->get("width");
$helper = new modJblanceProfileDisplayHelper();
/**
	$db = JFactory::getDBO();
	$db->setQuery("SELECT * FROM #__mod_jblance_profile_display where del=0 and module_id=".$module->id);
	$objects = $db->loadAssocList();
*/
require JModuleHelper::getLayoutPath('mod_jblance_profile_display', $params->get('layout', 'default'));