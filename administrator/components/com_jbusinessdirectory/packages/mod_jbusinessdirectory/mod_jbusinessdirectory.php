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

JHtml::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/forms.css');
JHtml::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/font-awesome.css');
JHtml::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/chosen.css');

JHtml::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/ion.rangeSlider.css');
JHtml::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/ion.rangeSlider.skinFlat.css');

if(!defined('J_JQUERY_UI_LOADED')) {
	JHtml::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/jquery-ui.css');
	JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/jquery-ui.js');
	define('J_JQUERY_UI_LOADED', 1);
}

JHtml::_('script', 	 'components/com_jbusinessdirectory/assets/js/ion.rangeSlider.js');
JHtml::_('script', 	 'components/com_jbusinessdirectory/assets/js/chosen.jquery.min.js');
JHtml::_('script', 	 'modules/mod_jbusinessdirectory/assets/js/script.js');

JHtml::_('stylesheet', 'modules/mod_jbusinessdirectory/assets/style.css');
$session = JFactory::getSession();

$geoLocation = $session->get("geolocation");
$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
JBusinessUtil::loadSiteLanguage();

if($params->get('showCategories')){
	$categories =  modJBusinessDirectoryHelper::getMainCategories();
	if($params->get('showSubCategories')){
		$subCategories = modJBusinessDirectoryHelper::getSubCategories();
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




if($params->get('showCities')){
	if($appSettings->limit_cities ==1){
		$cities =  modJBusinessDirectoryHelper::getActivityCities();
	}else{
		$cities =  modJBusinessDirectoryHelper::getCities();
	}
}

if($params->get('showTypes')){
	$types =  modJBusinessDirectoryHelper::getTypes();
}

if($params->get('showRegions')){
	$regions =  modJBusinessDirectoryHelper::getRegions();
}

if($params->get('showCountries')){
	$countries =  modJBusinessDirectoryHelper::getCountries();
}

if($params->get('showMap')){
	$maxListings = $params->get('maxListings');
	if(empty($maxListings)){
		$maxListings = 200;
	}
	$companies =  modJBusinessDirectoryHelper::getCompanies($maxListings);
}

if($appSettings->enable_multilingual) {
	JBusinessDirectoryTranslations::updateCategoriesTranslation($categories);
	JBusinessDirectoryTranslations::updateTypesTranslation($types);
}

$attributes = $params->get('customAttributes');
$atrributesValues = $session->get('customAtrributes');
if(!$params->get('preserve')){
	$atrributesValues = array();
}

if(!empty($attributes) && $attributes[0]!=""){
	$customAttributes =  modJBusinessDirectoryHelper::getCustomAttributes($attributes,$atrributesValues);
}

$mapHeight = $params->get('mapHeight');
$mapWidth = $params->get('mapWidth');

$menuItemId ="";
if($params->get('mItemId')){
	$menuItemId="&Itemid=".$params->get('mItemId');
}

$layoutType = $params->get('layout-type', 'horizontal');
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

$radius = $session->get("radius");
if(!isset($radius))
	$radius = $params->get('radius');

require (JModuleHelper::getLayoutPath( 'mod_jbusinessdirectory',$params->get('base-layout','default')));

?>