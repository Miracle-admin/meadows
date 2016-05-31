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

require_once JPATH_SITE.'/components/com_jbusinessdirectory/assets/defines.php'; 
require_once JPATH_SITE.'/components/com_jbusinessdirectory/assets/utils.php'; 
require_once JPATH_SITE.'/administrator/components/com_jbusinessdirectory/helpers/translations.php';

// Include the syndicate functions only once
require_once( dirname(__FILE__).DS.'helper.php' );

if(JBusinessUtil::isJoomla3()){
	JHtml::_('jquery.framework', true, true);
}else{
	if(!defined('J_JQUERY_LOADED')) {
		JHTML::_('script',  'components/com_jbusinessdirectory/assets/js/jquery.min.js');
		JHTML::_('script',  'components/com_jbusinessdirectory/assets/js/jquery-noconflict.js');
		define('J_JQUERY_LOADED', 1);
	}
}
JHtml::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/chosen.css');
JHtml::_('script', 	 'components/com_jbusinessdirectory/assets/js/chosen.jquery.min.js');
JHtml::_('stylesheet', 'modules/mod_jbusiness_offer_search/assets/style.css');

JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/jquery-ui.js'); 

JHtml::_('script', 	 'modules/mod_jbusiness_offer_search/assets/js/script.js');

$session = JFactory::getSession();
JBusinessUtil::loadSiteLanguage();
$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();

if($params->get('showCategories')){
	$categories =  modJBusinessOfferSearchHelper::getMainCategories();
	if($params->get('showSubCategories')){
		$subCategories = modJBusinessOfferSearchHelper::getSubCategories();
		foreach($categories as $category){
			foreach($subCategories as $subCat){
				if($category->id == $subCat->parent_id){
					if(!isset($category->subcategories)){
						$category->subcategories = array();
					}
					$category->subcategories[] = $subCat;
				}
			}
		}
	}
}

if($appSettings->enable_multilingual) {
	JBusinessDirectoryTranslations::updateCategoriesTranslation($categories);
}

if($params->get('showCities')){
	if($appSettings->limit_cities ==1){
		$cities =  modJBusinessOfferSearchHelper::getActivityCities();
	}else{
		$cities =  modJBusinessOfferSearchHelper::getCities();
	}
}

if($params->get('showTypes')){
	$types =  modJBusinessOfferSearchHelper::getTypes();
}

if($params->get('showRegions')){
	$regions =  modJBusinessOfferSearchHelper::getRegions();
}

$menuItemId ="";
if($params->get('mItemId')){
	$menuItemId="&Itemid=".$params->get('mItemId');
}

$layoutType = $params->get('layout-type', 'horizontal');
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

$radius = JRequest::getVar("radius");
if(!isset($radius))
	$radius = $params->get('radius');

require (JModuleHelper::getLayoutPath( 'mod_jbusiness_offer_search',$params->get('base-layout','default')));

?>