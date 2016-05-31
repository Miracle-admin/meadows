<?php
/**
 * @version		$Id: mod_login.php 22338 2011-11-04 17:24:53Z github_bot $
 * @package		Joomla.Site
 * @subpackage	mod_login
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once JPATH_SITE.'/components/com_jbusinessdirectory/assets/defines.php'; 
require_once JPATH_SITE.'/components/com_jbusinessdirectory/assets/utils.php'; 
require_once dirname(__FILE__).'/helper.php';


if(JBusinessUtil::isJoomla3()){
	JHtml::_('jquery.framework', true, true);
}else{
	if(!defined('J_JQUERY_LOADED')) {
		JHTML::_('script',  'components/com_jbusinessdirectory/assets/js/jquery.min.js');
		JHTML::_('script',  'components/com_jbusinessdirectory/assets/js/jquery-noconflict.js');
		define('J_JQUERY_LOADED', 1);
	}
}
JHTML::_('script', 'components/com_jbusinessdirectory/assets/js/jquery.blockUI.js');
JHTML::_('stylesheet', 'modules/mod_juserlogin/css/juserlogin.css');

$params->def('greeting', 1);

$type	= modUserLoginHelper::getType();
$return	= modUserLoginHelper::getReturnURL($params, $type);
$user	= JFactory::getUser();


$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_juserlogin', $params->get('layout', 'default'));
