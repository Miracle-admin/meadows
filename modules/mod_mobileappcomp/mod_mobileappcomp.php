<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_mobileappcomp
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once __DIR__ . '/helper.php';

// Get the list of featured companies
$list=ModMobileAppcompHelper::getUserList($params);
$helper=ModMobileAppcompHelper;


$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_mobileappcomp', $params->get('layout', 'default'));