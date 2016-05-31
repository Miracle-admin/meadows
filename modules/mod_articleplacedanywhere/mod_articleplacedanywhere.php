<?php
/**
* @copyright    Copyright (C) 2008 Ian MacLennan. All rights reserved.
* @copyright    Upgrade to J2.5.  Copyright 2012 HartlessByDesign, LLC.
* @copyright	Portions Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license   GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once (dirname(__FILE__).'/helper.php');

$lang = JFactory::getLanguage();
$lang->load('com_content', JPATH_SITE);

//get the article default params
$articleParams = JComponentHelper::getParams("com_content");
//we want the module's params to overwrite - thanks designsinnovate
$allParams = new JRegistry($articleParams->toArray());
$allParams->merge($params);
$params = $allParams;

$item   = modArticlePlacedAnywhereHelper::getItem($params);

//require icon HTML
if (!class_exists("JHtmlIcon")) {
    include_once JPATH_SITE . "/components/com_content/helpers/icon.php";
}

// check if any results returned
if (empty( $item )) {
	return;
}

if ($params->get('load_mootools', 0)) {
	JHTML::_( 'behavior.mootools' );
}

$layout = $params->get('layout', 'default');
$filter = JFilterInput::getInstance();
$layout = $filter->clean($layout, 'word');

$path = JModuleHelper::getLayoutPath('mod_articleplacedanywhere', $layout);

if (file_exists($path)) {
	require($path);
}
