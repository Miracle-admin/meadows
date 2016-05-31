<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_latest
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
require_once JPATH_SITE.'/components/com_jbusinessdirectory/assets/defines.php';
require_once JPATH_SITE.'/components/com_jbusinessdirectory/assets/utils.php';

JHtml::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/common.css');
JHTML::_('stylesheet', 	'components/com_jbusinessdirectory/assets/css/responsive.css');
JHtml::_('stylesheet', 'modules/mod_jbusiness_offers/assets/style.css');
// Include the syndicate functions only once
require_once __DIR__ . '/helper.php';

JBusinessUtil::loadSiteLanguage();

$items = modJBusinessOffersHelper::getList($params);
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_jbusiness_offers', $params->get('layout', 'default'));
