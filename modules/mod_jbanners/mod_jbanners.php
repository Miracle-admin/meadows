<?php

/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );
require_once JPATH_SITE.'/components/com_jbusinessdirectory/assets/utils.php';
require_once __DIR__ . '/helper.php';
require_once JPATH_ADMINISTRATOR . '/components/com_banners/helpers/banners.php';

if(JBusinessUtil::isJoomla3()){
	JHtml::_('jquery.framework', true, true);
}else{
	if(!defined('J_JQUERY_LOADED')) {
		JHTML::_('script',  'components/com_jbusinessdirectory/assets/js/jquery.min.js');
		define('J_JQUERY_LOADED', 1);
	}
}

JHTML::_('script', 'modules/mod_jbanners/galleria/galleria-1.3.3.js');

BannersHelper::updateReset();
$list = &modJBannersHelper::getList($params);

if(empty($list))
	return;

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_jbanners', $params->get('layout', 'default'));
?>