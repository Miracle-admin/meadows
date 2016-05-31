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
JHtml::_('stylesheet', 'modules/mod_jbusiness_latest/assets/style.css');



if(JBusinessUtil::isJoomla3()){
	JHtml::_('jquery.framework', true, true);
}else{
	if(!defined('J_JQUERY_LOADED')) {
		JHTML::_('script',  'components/com_jbusinessdirectory/assets/js/jquery.min.js');
		JHTML::_('script',  'components/com_jbusinessdirectory/assets/js/jquery-noconflict.js');
		define('J_JQUERY_LOADED', 1);
	}
}

if($params->get('slider')){
	JHtml::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/slick.css');
	JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/slick.js');
	
}

JHTML::_('script', 	'components/com_jbusinessdirectory/assets/js/jquery.raty.min.js');

require_once JPATH_SITE.'/administrator/components/com_jbusinessdirectory/helpers/translations.php';

// Include the syndicate functions only once
require_once __DIR__ . '/helper.php';

JBusinessUtil::loadSiteLanguage();

//load items through cache mechanism
$cache = JFactory::getCache();
$items  = $cache->call( array( 'modJBusinessLatestHelper', 'getList' ), $params );

$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
if($appSettings->enable_multilingual){
	JBusinessDirectoryTranslations::updateBusinessListingsTranslation($items);
	JBusinessDirectoryTranslations::updateBusinessListingsSloganTranslation($items);
}

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

$backgroundCss="";
if($params->get('backgroundColor')){
	$backgroundCss = "background-color:".$params->get('backgroundColor').";";
}

$borderCss="";
if($params->get('borderColor')){
	$borderCss="border-color:".$params->get('borderColor').";";
}

if($params->get('slider'))
	require JModuleHelper::getLayoutPath('mod_jbusiness_latest', "default_slider");
else
	require JModuleHelper::getLayoutPath('mod_jbusiness_latest', $params->get('layout', 'default'));
