<?php

/**
 * @copyright	Copyright © 2016 - All rights reserved.
 * @license		GNU General Public License v2.0
 * @generator	http://xdsoft/joomla-module-generator/
 */
defined('_JEXEC') or die;

$doc = JFactory::getDocument();
/* Available fields: */
// Include assets
require_once dirname(__FILE__) . '/helper.php';
$doc->addStyleSheet(JURI::root() . "modules/mod_latest_products/assets/css/style.css");
$doc->addScript(JURI::root() . "modules/mod_latest_products/assets/js/script.js");


require JModuleHelper::getLayoutPath('mod_latest_products', $params->get('layout', 'default')); 
