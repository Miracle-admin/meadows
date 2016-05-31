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
$doc->addStyleSheet(JURI::root() . "modules/mod_recentsoldproducts/assets/css/style.css");
$doc->addScript(JURI::root() . "modules/mod_recentsoldproducts/assets/js/script.js");


require JModuleHelper::getLayoutPath('mod_recentsoldproducts', $params->get('layout', 'default')); 
