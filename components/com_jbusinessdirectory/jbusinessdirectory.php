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
require_once JPATH_COMPONENT_SITE.'/assets/defines.php';
require_once JPATH_COMPONENT_SITE.'/assets/utils.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/translations.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/attachments.php';
require_once JPATH_COMPONENT_SITE.'/assets/logger.php';
JHtml::_('behavior.framework');

JHTML::_('stylesheet', 	'components/com_jbusinessdirectory/assets/css/common.css');
JHTML::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/forms.css');
JHTML::_('stylesheet', 	'components/com_jbusinessdirectory/assets/css/responsive.css');
JHtml::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/font-awesome.css');

if(JBusinessUtil::isJoomla3()){
	JHtml::_('jquery.framework', true, true);
	define('J_JQUERY_LOADED', 1);
}else{
	if(!defined('J_JQUERY_LOADED')) {
		JHTML::_('script',  'components/com_jbusinessdirectory/assets/js/jquery.min.js');
		JHTML::_('script',  'components/com_jbusinessdirectory/assets/js/jquery-noconflict.js');
		define('J_JQUERY_LOADED', 1);
	}
}

JHTML::_('script', 	'components/com_jbusinessdirectory/assets/js/jquery.raty.min.js');
JHTML::_('script', 	'components/com_jbusinessdirectory/assets/js/jquery.blockUI.js');
JHTML::_('script', 	'components/com_jbusinessdirectory/assets/js/common.js');
JHTML::_('script', 	'components/com_jbusinessdirectory/assets/js/utils.js');

if( !defined('COMPONENT_IMAGE_PATH' ))
	define("COMPONENT_IMAGE_PATH", JURI::base()."components/".JRequest::getVar('option')."/assets/images/");
if( !defined( 'IMAGE_BASE_PATH') )
	define( 'IMAGE_BASE_PATH', (JURI::base()."administrator/components/".JBusinessUtil::getComponentName()));

JBusinessUtil::loadClasses();

$document  =JFactory::getDocument();
$document->addScriptDeclaration('
		var baseUrl="'.(JURI::base().'index.php?option='.JBusinessUtil::getComponentName()).'";
		var imageBaseUrl="'.(JURI::root().PICTURES_PATH).'";		
		'
);
$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
$session = JFactory::getSession();
//setting menu item Id
$lang = JFactory::getLanguage();
$app = JFactory::getApplication();
$menu = $app->getMenu();
$activeMenu = $app->getMenu()->getActive();

$url = $_SERVER['REQUEST_URI'];
$urlParts = parse_url($url);

if (( !empty($activeMenu) && $menu->getActive() != $menu->getDefault($lang->getTag()))
		|| ($urlParts["path"]=='/' && empty($urlParts["query"]))) {
	$menuId = $activeMenu->id;
	$session->set('menuId', $menuId);
}

if($appSettings->enable_seo){
	$menuId = $session->get('menuId');
}

if(!empty($appSettings->menu_item_id) && ($menuId == $menu->getDefault($lang->getTag())->id || empty($menuId))){
	$menuId = $appSettings->menu_item_id;
}

if(!empty($menuId)){
	JFactory::getApplication()->getMenu()->setActive($menuId);
	JRequest::setVar('Itemid',$menuId);
}

JBusinessUtil::loadSiteLanguage();

$log = Logger::getInstance(JPATH_COMPONENT."/logs/site-log-".date("d-m-Y").'.log',1);

// Execute the task.
$controller	= JControllerLegacy::getInstance('JBusinessDirectory');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();

