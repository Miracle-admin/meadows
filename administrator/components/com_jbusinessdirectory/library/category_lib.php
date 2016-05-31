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

JTable::addIncludePath('/components/'.JRequest::getVar('option').'/tables');

class JBusinessDirectorCategoryLib
{
	function __construct()
	{
	}

	function getCategories(){
		$categories = $this->getAllCategories();
		$categories = $this->processCategories($categories);
		
		$startingLevel = 1;
		$maxLevel=0;
		$path=array();
		$categories["maxLevel"] = $this->setCategoryLevel($categories, $startingLevel, $maxLevel, $path);

		return $categories;
	}

	function getAllCategories(){
		$db =JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_categories order by parent_id,name";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function getCompleteCategoryById($categoryId){
		$categoryTable = JTable::getInstance("Category","JBusinessTable");
		
		$category = null;
		if(!empty($categoryId)){
			$category = $categoryTable->getCategoryById($categoryId);
			$category = array($category);
		}
		
		$categories = $categoryTable->getAllCategories();
		$categories = $this->processCategories($categories);

		$startingLevel = 1;
		$maxLevel=0;
		$path=array();
		//set the level by reference
		$this->setCategoryLevel($categories, $startingLevel, $maxLevel,$path);
		//set the category by reference
		$this->findCategory($categories, $category);
		return $category;
	}

	function processCategories($categories){
		$newCategories = array();
		//if(empty($categories))
		//	return $newCategories;
		foreach ($categories as $category){
			if($category->parent_id!=1){
				$parentCategory = $this->getParent($newCategories, $category);
			}else{
				$newCategories[$category->id] = array($category,"subCategories"=>array());
			}
		}
		return $newCategories;
	}
	
	function processCategoriesByName($categories){
		$newCategories = array();
		//if(empty($categories))
		//	return $newCategories;
		foreach ($categories as $category){
			if($category->parent_id!=1){
				$parentCategory = $this->getParentByName($newCategories, $category);
			}else{
				$newCategories[$category->name] = array($category,"subCategories"=>array());
			}
		}
		return $newCategories;
	}

	function setCategoryLevel(&$categories, $level, &$maxLevel=0, &$path){
	
		foreach ($categories as &$cat){
			//dump($cat[0]->name);
			if($maxLevel < $level){
				$maxLevel = $level;
			}
			$cat["level"]= $level;

			$cat["path"]=$path;
			//dump($cat);
			if(is_array($cat["subCategories"]) && count($cat["subCategories"])>0) {
				$path[$level] =array($cat[0]->id,$cat[0]->name,$cat[0]->alias);
				$this->setCategoryLevel($cat["subCategories"], $level+1, $maxLevel, $path);
			}
		}
		// 		echo "------start unset ".(count($path));
		//  		dump($path);
		unset($path[count($path)]);
		// 		dump($path);
		// 		echo "-----end unset -------------";
		//dump((count($path)-1));
		//unset($path[count($path)-1]);
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

	function getParentByName(&$categories, $category){
		foreach ($categories as &$cat){
			if($category->parent_id==$cat[0]->id){
				$cat["subCategories"][$category->name] = array($category,"subCategories"=>array());
				return $cat;
			}
			else if(isset($cat["subCategories"])){
				$this->getParent($cat["subCategories"], $category);
			}
		}
	}
	
	function findCategory($categories, &$category){
		if(!isset($category)){
			return null;
		}
		foreach ($categories as $cat){
			if(isset($category) && $category[0]->id==$cat[0]->id){
				$category=$cat;
				return $cat;
			}
			else if(isset($cat["subCategories"])){
				$this->findCategory($cat["subCategories"], $category);
			}
		}
	}
	
	function findCategoryById($categories, &$category, $id){
		foreach ($categories as $cat){
			//dump( $cat[0]->id);
			if($id == $cat[0]->id ){
				$category=$cat;
				return $cat;
			}
			else if(isset($cat["subCategories"])){
				$this->findCategoryById($cat["subCategories"], $category, $id);
			}
		}
	}
	
	function findCategoryByName($categories, &$category, $categoryName){
		foreach ($categories as $cat){
			if(strcmp($cat[0]->name,$categoryName)==0){
				
				$category = $cat;
				return $cat;
			}
			else if(isset($cat["subCategories"])){
				 $this->findCategoryByName($cat["subCategories"],$category, $categoryName);
			}
		}
		return $category;
	}

	function getCategoryLeafs($categoryId){
		$category = $this->getCompleteCategoryById($categoryId);
		$leafsIds = array();
		
		if(isset($category["subCategories"]) && is_array($category["subCategories"]) && count($category["subCategories"])>0){
			$leafsIds = $this->getAllLeafs($category["subCategories"],$leafsIds );
			//dump($leafsIds);
		}else{
			if(isset($categoryId) && isset($category)){
				$leafsIds[] = $category[0]->id;
			}
		}
		//dump($leafsIds);
		return $leafsIds;
	}

	function getCategoryChilds($category){
		if(!isset($category)){
			return null;
		}
		$leafsIds = array();
		if(is_array($category["subCategories"]) && count($category["subCategories"])>0){
			$leafsIds = $this->getAllLeafs($category["subCategories"],$leafsIds );
			//dump($leafsIds);
		}else{
			//dump($category);
			$leafsIds[] = $category[0]->id;
		}

		return $leafsIds;
	}

	function getAllLeafs($categories,&$leafIds){
		foreach ($categories as &$cat){
			if(count($cat["subCategories"])==0) {
				//dump($cat);
				$leafIds[]=$cat[0]->id;
				//dump($leafIds);
			}
			if(is_array($cat["subCategories"])) {
				$this->getAllLeafs($cat["subCategories"], $leafIds);
			}
		}
		return $leafIds;
	}
	
	
	
	function convertCategories($categories,  &$result){

			foreach ($categories as $cat){
				if(!empty($cat["level"]))
					$cat[0]->level = $cat["level"]-1;
				$result[] = $cat[0];
				
				if(isset($cat["subCategories"])) {
					$this->convertCategories($cat["subCategories"], $result);
				}
			}
		
			return;
	}

	
	function createRootElement(){
		$db =JFactory::getDBO();
		
		$query = "select * from #__jbusinessdirectory_categories where id =1 ";
		$db->setQuery($query);
		$rootElement = $db->loadObject();
		
		if(!empty($rootElement) && $rootElement->name != "Root"){
			//get max number
			$query = "select max(id) as max_id from #__jbusinessdirectory_categories";
			$db->setQuery($query);
			$max = $db->loadObject()->max_id;
			$max = $max + 1;
				
			$query = "update #__jbusinessdirectory_categories set id = $max where id =1";
			$db->setQuery($query);
			$db->query();
				
			$query = "update #__jbusinessdirectory_categories set parent_id = $max where parent_id = 1";
			$db->setQuery($query);
			$db->query();
			//change first id
		}else if(empty($rootElement)){
			$query = "insert into #__jbusinessdirectory_categories(id,parent_id,name,alias) values(1,0,'Root','root')";
			$db->setQuery($query);
			$db->query();
		}
	}
	
	function updateCategoryStructure(){ 
		
		$db =JFactory::getDBO();
		
		$query = "select max(lft) as max_left from #__jbusinessdirectory_categories ";
		$db->setQuery($query);
		$maxLft = $db->loadObject()->max_left;
				
		$this->createRootElement();
		
		$query = "select * from #__jbusinessdirectory_categories where id =1 ";
		$db->setQuery($query);
		$rootElement = $db->loadObject();
		
		if(empty($rootElement)){
			//insert Root category
			$query = "INSERT INTO #__jbusinessdirectory_categories(`id`,`parent_id`,`lft`,`rgt`,`level`,`name`,`alias`,`description`,`published`,`imageLocation`,`markerLocation`,`path`) VALUES ('1', '0', '0', '286', '0', 'Root', 'root', '', '1', '', '', NULL)	";
			$db->setQuery($query);
			$db->query();
		}
		
		$query = "update #__jbusinessdirectory_categories set parent_id = 1 where parent_id = 0 and id>1";
		$db->setQuery($query);
		$db->query();
		//change first id
		
		$categories = $this->getCategories();
		//dump($categories);
		$result = array();
		$this->convertCategories($categories,$result);
		$categories = $result;
		$lft = 1;
		$rgt = 2;
	
		foreach($categories as $category){
			
			if(isset($category)){
				$id = $category->id;
				if($id > 1){
					$category->lft = $lft;
					$category->rgt = $rgt;
					//dump($category);
					$query = "update #__jbusinessdirectory_categories set lft = $category->lft, rgt=$category->rgt, level = ($category->level+1)  where id = $category->id";
					$db->setQuery($query);
					$db->query();
					
					$lft = $lft+2;
					$rgt = $rgt+2;
				}
			}
		}
		
		$query = "update #__jbusinessdirectory_categories set rgt = $rgt where id = 1";
		$db->setQuery($query);
		$db->query();
		//exit;
	}
}
?>