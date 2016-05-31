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
jimport('joomla.application.component.modellist');

JTable::addIncludePath(DS.'components'.DS.JRequest::getVar('option').DS.'tables');
require_once( JPATH_COMPONENT_ADMINISTRATOR.'/library/category_lib.php');

class JBusinessDirectoryModelCategories extends JModelList
{ 
	function __construct()
	{
		$this->appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		parent::__construct();
	}

	/**
	 * Returns a Table object, always creating it
	 *
	 * @param   type	The table type to instantiate
	 * @param   string	A prefix for the table class name. Optional.
	 * @param   array  Configuration array for model. Optional.
	 * @return  JTable	A database object
	 */
	public function getTable($type = 'Category', $prefix = 'JBusinessTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	/**
	 * 
	 * @return object with data
	 */
	function getCategories(){
			
		$categoryService = new JBusinessDirectorCategoryLib();
		$categoryTable = $this->getTable();
			
		$categories = $categoryTable->getAllCategories();
		$categories = $categoryService->processCategories($categories);
		//$categories = $categories[1]["subCategories"];
		$startingLevel = 0;
		$path=array();
		$level =0;
		$categories["maxLevel"] = $categoryService->setCategoryLevel($categories,$startingLevel,$level,$path);
		
		
		if($this->appSettings->enable_multilingual){
			JBusinessDirectoryTranslations::updateCategoriesTranslation($categories);
		}
		
		return $categories;
	}
	
	function getCategoriesList($keyword){
		$table = $this->getTable();
		$suggestionList = $table->getCategoriesList($keyword);
		$suggestionList = json_encode($suggestionList);
		return $suggestionList;
	}
}
?>