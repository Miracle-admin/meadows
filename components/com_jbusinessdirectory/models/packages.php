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

jimport('joomla.application.component.modelitem');
JTable::addIncludePath(DS.'components'.DS.JRequest::getVar('option').DS.'tables');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php';

class JBusinessDirectoryModelPackages extends JModelItem
{ 
	
	function __construct()
	{
		parent::__construct();
		$this->appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
	}

	/**
	 * Get available packages with included features.
	 * @return array
	 */
	function getPackages(){
		$packageTable = JTable::getInstance("Package", "JTable");
		$packages = $packageTable->getPackages();
		
		foreach($packages as $package){
			$package->features = explode(",", $package->featuresS);
		}
				
		if($this->appSettings->enable_multilingual){
			JBusinessDirectoryTranslations::updatePackagesTranslation($packages);
		}
		
		return $packages;
	}
	
	/**
	 * Get the default package features
	 * Remove the features that are not used in the packages
	 * @return package features
	 */
	function getDefaultPackageFeatures(){
		$packageFeatures = JBusinessDirectoryHelper::getPackageFeatures();
		$packages = $this->getPackages();
		
		$result = array();
		//check if the attribues are contained in at least one package. If not it will be removed.
		foreach($packageFeatures as $key=>$value){
			$found = false;
			foreach($packages as $package){
				foreach($package->features as $feature){
					if($feature == $key){
						$found = true;
					}
				}
			}
				
			if($found){
				$result[$key] = $value;
			}
		}
		
		
		return $result;
	}
	/**
	 * Get custom attributes and filter them based on package features
	 * @return array
	 */
	function getAttributes(){
		$attributesTable = JTable::getInstance('Attribute','JTable');
		$attributes = $attributesTable->getActiveAttributes();
		
		$packages = $this->getPackages();
		$result = array();
		//check if the attribues are contained in at least one package. If not it will be removed.
		foreach($attributes as $attribute){
			$found = false;
			foreach($packages as $package){
				foreach($package->features as $feature){
					if($feature == $attribute->code){
						$found = true;
					}
				}
			}
			
			if($found){
				$result[] = $attribute;
			}
		}
		
		if($this->appSettings->enable_multilingual){
			JBusinessDirectoryTranslations::updateAttributesTranslation($result);
		}
		
		return $result;
	}
	
	
	
}
?>

