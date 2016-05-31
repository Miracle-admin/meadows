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
jimport('joomla.application.component.modeladmin');

class JBusinessDirectoryModelManageCategories extends JModelAdmin
{ 
	function __construct()
	{
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
	public function getTable($type = 'Categories', $prefix = 'JTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	/**
	 * 
	 * @return object with data
	 */
	function &getDatas()
	{	
		// Load the data
		if (empty( $this->_data )) 
		{
			
			$this->_data = $this->getCategories();
		}
		
		return $this->_data;
	}
		
	function store($data)
	{	
		$row = $this->getTable();
		$data["alias"]= JBusinessUtil::getAlias($data["name"],$data["alias"]);

		// Bind the form fields to the table
		if (!$row->bind($data)) 
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		// Make sure the record is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store the web link table to the database
		if (!$row->store()) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		return $row->id;
	}
	
	
	function deleteCategory($id){

		$categoryTable = $this->getTable();
		if(!$categoryTable->delete( $id )){
			$this->setError("Unable to delete category");
			return;
		}
		
		$categoryTable->deleteSubcategories($id);
	}
	
	function getCategories(){
		$categoryTable = $this->getTable();
		
		$categories = $categoryTable->getAllCategories();
		$categories = $this->processCategories($categories);
		$categories = $categories[1]["subCategories"];
		$startingLevel = 1;
		$categories["maxLevel"] = $this->setCategoryLevel($categories,$startingLevel);

		return $categories;
	}
	
	function getCategoryById($categoryId){
		$categoryTable = $this->getTable();
		
		$category = $categoryTable->getCategoryById($categoryId);
		return $category;
	}
	
	function changeCategoryState($categoryId){
		$categoryTable = $this->getTable();
		return $categoryTable->changeCategoryState($categoryId);
	}
	
	function getCompleteCategoryById($categoryId){
		$categoryTable = $this->getTable();
		
		$category = $categoryTable->getCategoryById($categoryId);

		$categories = $categoryTable->getAllCategories();
		$categories = $this->processCategories($categories);
		//dump($categories);
		$startingLevel = 1;
		//set the level by reference
		$this->setCategoryLevel($categories,$startingLevel);
		//set the category by reference
		$this->findCategory($categories, $category);
		return $category;
	}
	
	function processCategories($categories){
		$newCategories = array();
		foreach ($categories as $category){
			if($category->parent_id!=0){
				$parentCategory = $this->getParent($newCategories, $category);		
			}else{
				$newCategories[$category->id] = array($category,"subCategories"=>array());
			}
		}
		return $newCategories;
	}
	
	function setCategoryLevel(&$categories, $level, &$maxLevel=0){
		foreach ($categories as &$cat){
			
			if($maxLevel < $level){
				$maxLevel = $level;
			}
			$cat["level"]= $level;
			if(is_array($cat["subCategories"])) {
				$this->setCategoryLevel($cat["subCategories"], $level+1, $maxLevel);
			}
		}
		return $maxLevel;
	}
	
	function getParent(&$categories, $category){		
		foreach ($categories as &$cat){
			if($category->parent_id==$cat[0]->id){
				$cat["subCategories"][$category->id] = array($category,"subCategories"=>array());
				return $cat;
			}
			else if(isset($cat["subCategories"])){
				$this->getParent($cat["subCategories"], $category);
			}
		}
	}
	
	function findCategory($categories, &$category){
		foreach ($categories as $cat){
			if($category[0]->id==$cat[0]->id){		
				$category=$cat;	
				return $cat;
			}
			else if(isset($cat["subCategories"])){
				 $this->findCategory($cat["subCategories"], $category);
			}
		}
	}
	
	public function getForm($data = array(), $loadData = true){
	
	}
	
	function importCategories($filePath, $delimiter){
	
		$newCategoryCount = 0;
		$newSubCategoryCount = 0;
			
		$row = 1;
		if (($handle = fopen($filePath, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 3000, $delimiter)) !== FALSE) {
				$categoryData = array();
				if($row==1){
					$header = $data;
					$row++;
					continue;
				}
				$num = count($data);
				dump($data);
				//echo "<p> $num fields in line $row: <br /></p>\n";
				$row++;
				for ($c=0; $c < $num; $c++) {
					$categoryData[strtolower($header[$c])]= $data[$c];
				}
	
				$categoryIds = array();
				//dump($company);
				//dump($company["categories"]);
				dump($categoryData);
				if(!empty($categoryData["category"])){
					$newCategoryCount ++;
					$categoryId = $this->addCategory($categoryData["category"]);
					
					$subcategories = explode(",", $categoryData["subcategories"]);
					
					foreach($subcategories as $subcategory){
						$this->addCategory($subcategory, $categoryId);
						$newSubCategoryCount ++;
					}
				}
			}
		}
		
		$result = new stdClass();
		$result->newCategories = $newCategoryCount;
		$result->newSubCategoryCount = $newSubCategoryCount;
		exit;
		return $result;
	}
	
	public function exportCategoriesCSV(){
		$delimiter = JRequest::getVar("delimiter",",");
		$category = JRequest::getVar("category",",");

		$categoryTable = $this->getTable("Categories");
		$categories =  $categoryTable->getCategoriesForExport();
	
		$csv_output = "category".$delimiter."subcategories"."\n";
	
		foreach($categories as $category){

			$csv_output .= "\"$category->name\"".$delimiter."\"$category->subcategories\"";
			$csv_output .= "\n";
		}

		$fileName = "jbusinessdirectory_categories";
		header("Content-type: application/vnd.ms-excel");
		header("Content-disposition: csv" . date("Y-m-d") . ".csv");
		header( "Content-disposition: filename=".$fileName.".csv");
		print $csv_output;
	}
	
	function addCategory($name, $parentId=0){
		dump("insert category");
		dump($name);
		if(!isset($name) || strlen(trim($name))<2 )
			return;
	
		$table = $this->getTable("Categories");
	
		$category = array();
		$category["parent_id"] = $parentId;
		$category["name"] = $name;
		$category["published"] = 1;
	
		if (!$table->bind($category))
		{
			throw( new Exception($this->_db->getErrorMsg()) );
			$this->setError($this->_db->getErrorMsg());
		}
		// Make sure the record is valid
		if (!$table->check())
		{
			throw( new Exception($this->_db->getErrorMsg()) );
			$this->setError($this->_db->getErrorMsg());
		}
	
		// Store the web link table to the database
		if (!$table->store())
		{
			throw( new Exception($this->_db->getErrorMsg()) );
			$this->setError($this->_db->getErrorMsg());
		}
	
		return $table->id;
	}
	
}
?>