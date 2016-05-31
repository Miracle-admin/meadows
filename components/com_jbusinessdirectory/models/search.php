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
require_once( JPATH_SITE.'/administrator/components/com_jbusinessdirectory/library/category_lib.php');

class JBusinessDirectoryModelSearch extends JModelList
{ 
	
	function __construct()
	{
		parent::__construct();
		
		$this->appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		$this->searchFilter = array();
		
		$this->prepareSearchAttribtues();
		
		$mainframe = JFactory::getApplication();
		
		// Get pagination request variables
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');
		
		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}
	
	/**
	 * Returns a Table object, always creating it
	 *
	 * @param   type	The table type to instantiate
	 * @param   string	A prefix for the table class name. Optional.
	 * @param   array  Configuration array for model. Optional.
	 * @return  JTable	A database object
	 */
	public function getTable($type = 'Companies', $prefix = 'JTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	
	public function prepareSearchAttribtues(){
		
		$this->keyword = JRequest::getVar('searchkeyword');
		$this->keywordLocation = JRequest::getVar('searchkeywordLocation');
		$this->categoryId = JRequest::getVar('categoryId',null);
		$this->citySearch = JRequest::getVar('citySearch',null);
		$this->typeSearch = JRequest::getVar('typeSearch',null);
		$this->regionSearch = JRequest::getVar('regionSearch',null);
		$this->countrySearch = JRequest::getVar('countrySearch',null);
		$this->categorySearch = JRequest::getVar('categorySearch');
		$this->zipCode = JRequest::getVar('zipcode');
		$this->radius = JRequest::getVar('radius');
		$this->preserve = JRequest::getVar('preserve',null);
		
		$resetSearch = JRequest::getVar('resetSearch',null);
		
		if(!empty($this->categorySearch) && empty($this->categoryId) &&  isset($this->preserve)){
			$this->categoryId = $this->categorySearch;
		}
		
		$session = JFactory::getSession();
		if(isset($this->categoryId) || !empty($resetSearch)){
			$session->set('categorySearch', $this->categoryId);
			$session->set('searchkeyword', "");
			$session->set('searchkeywordLocation',"");
			$session->set('typeSearch',"");
			$session->set('citySearch',"");
			$session->set('regionSearch',"");
			$session->set('countrySearch',"");
			$session->set('zipcode',"");
			$session->set('customAtrributes',"");
		}
		
		if(isset($this->typeSearch)){
			$session->set('typeSearch', $this->typeSearch);
		}
		
		if(isset($this->citySearch)){
			$session->set('citySearch', $this->citySearch);
		}
		
		if(isset($this->regionSearch)){
			$session->set('regionSearch', $this->regionSearch);
		}
		
		if(isset($this->countrySearch)){
			$session->set('countrySearch', $this->countrySearch);
		}
		
		if(isset($this->keyword)){
			$session->set('searchkeyword', $this->keyword);
		}
		
		if(isset($this->keywordLocation)){
			$session->set('searchkeywordLocation', $this->keywordLocation);
		}
		
		if(isset($this->zipCode)){
			$session->set('zipcode', $this->zipCode);
		}
		
		if(isset($this->radius)){
			$session->set('radius', $this->radius);
		}
		
		$this->keyword = $session->get('searchkeyword');
		$this->keywordLocation = $session->get('searchkeywordLocation');
		$this->typeSearch = $session->get('typeSearch');
		$this->citySearch = $session->get('citySearch');
		
		$this->regionSearch = $session->get('regionSearch');
		$this->countrySearch = $session->get('countrySearch');
		$this->categorySearch = $session->get('categorySearch');
		
		$this->zipCode = $session->get('zipcode');
		$this->radius = $session->get('radius');
		$this->location = null;
		
		$geolocation = JRequest::getVar('geolocation',null);
		if(isset($geolocation)){
			$session->set("geolocation",$geolocation);
		}
		$geolocation = $session->get("geolocation");
		// test if geo location is determined and set location array
		if($this->appSettings->enable_geolocation && $geolocation){
			$geoLatitutde = JRequest::getVar('geo-latitude',null);
			$geoLongitude = JRequest::getVar('geo-longitude',null);
		
			if(!empty($geoLatitutde)){
				$session->set('geo-latitude', $geoLatitutde);
			}
			if(!empty($geoLongitude)){
				$session->set('geo-longitude', $geoLongitude);
			}
			$geoLatitutde = $session->get('geo-latitude');
			$geoLongitude = $session->get('geo-longitude');
		
			if(!empty($geoLatitutde) && !empty($geoLongitude)){
				$this->location =  array();
				$this->location["latitude"] = $geoLatitutde;
				$this->location["longitude"] = $geoLongitude;
			}
		}
		
		$this->featured = JRequest::getVar('featured',null);
		$this->enablePackages = $this->appSettings->enable_packages;
		$this->showPendingApproval =  $this->appSettings->show_pending_approval==1;
		$this->showSecondayLocationsMap =  $this->appSettings->show_secondary_map_locations;
		
		if(isset($this->zipCode) && $this->zipCode!=""){
			$this->location = JBusinessUtil::getCoordinates($this->zipCode);
		}
		
		if(!empty($this->location)){
			$session->set("location",$this->location);
		}
		
		//prepare custom attributes
		$data = JRequest::get('post');
		$this->customAtrributes = array();
		foreach($data as $key=>$value){
			if(strpos($key,"attribute")===0){
				$attributeId = explode("_", $key);
				$attributeId = $attributeId[1];
				if(!empty($value)){
					$this->customAtrributes[$attributeId] = $value;
				}
			}
		}
		
		if(!empty($this->customAtrributes)){
			foreach($this->customAtrributes as &$customAttribute){
				if(is_array($customAttribute)){
					$customAttribute = implode(",", $customAttribute);
				}
			}
			
			$session->set('customAtrributes', $this->customAtrributes);
		}
		
		$this->customAtrributes = $session->get('customAtrributes');
	}
	
	function getCategoryId(){
		return $this->categoryId;
	}
	
	function getSearchParams(){
		$categories = $this->getSelectedCategories();
		//dump($categories);
	
		$filterActive = JRequest::getVar("filter_active");
		$categoryService = new JBusinessDirectorCategoryLib();
		$categoriesIds = array();
		if(!empty($categories) && !empty($filterActive)) {
			foreach($categories as $category){
				$categoriesLevel= array();
				$cats = $categoryService->getCategoryLeafs($category);
				//dump($category);
				//dump($cats);
				if(isset($cats)){
					$categoriesLevel = array_merge($categoriesLevel,$cats);
				}
				$categoriesLevel[] = $category;
				$categoriesIds[] = implode(",",$categoriesLevel);
			}
		}else if(isset($this->categoryId) && $this->categoryId!=0 && empty($filterActive)){
			$categoriesIds = $categoryService->getCategoryLeafs($this->categoryId);
				
			if(isset($this->categoryId) && $this->categoryId !=0){
				if(isset($categoriesIds) && count($categoriesIds) > 0 ){
					$categoriesIds[] = $this->categoryId;
				}else{
					$categoriesIds = array($this->categoryId);
				}
			}
			$categoriesIds = array(implode(",", $categoriesIds));
		}
		$orderBy = JRequest::getVar("orderBy", $this->appSettings->order_search_listings);
		
		$searchDetails = array();
		$searchDetails["keyword"] = $this->keyword;
		$searchDetails["keywordLocation"] = $this->keywordLocation;
		$searchDetails["categoriesIds"] = $categoriesIds;
		if(!empty($this->location)){
			$searchDetails["latitude"] = $this->location["latitude"];
			$searchDetails["longitude"] = $this->location["longitude"];
		}
		
		$radius = $this->radius;
		if($this->appSettings->metric==0){
			$radius  = $radius * 0.621371;
		}
		$searchDetails["radius"] = $radius;
		
		$searchDetails["typeSearch"] = $this->typeSearch;
		$searchDetails["citySearch"] = $this->citySearch;
		$searchDetails["regionSearch"] = $this->regionSearch;
		$searchDetails["countrySearch"] = $this->countrySearch;
		$searchDetails["enablePackages"] = $this->enablePackages;
		$searchDetails["showPendingApproval"] = $this->showPendingApproval;
		$searchDetails["orderBy"] = $orderBy;
		$searchDetails["facetedSearch"] = $this->appSettings->search_type;
		$searchDetails["zipcCodeSearch"] = $this->appSettings->zipcode_search_type;
		$searchDetails["limit_cities"] = $this->appSettings->limit_cities;
		$searchDetails["customAttributes"] = $this->customAtrributes;
		$searchDetails["featured"] = $this->featured;
		$searchDetails["showSecondayLocationsMap"] = $this->showSecondayLocationsMap;
		
		return $searchDetails;
	}
	
	function getItems(){
		$searchDetails = $this->getSearchParams();
		$companiesTable = $this->getTable("Company");
		//dump($this->getState('limitstart').' '.$this->getState('limit'));
		$companies =  $companiesTable->getCompaniesByNameAndCategories($searchDetails, $this->getState('limitstart'), $this->getState('limit'));
		foreach($companies as $company){
			$company->packageFeatures = explode(",", $company->features);
		}

		foreach($companies as $company){
			$company->packageFeatures = explode(",", $company->features);
			$attributesTable = $this->getTable('CompanyAttributes');
			$company->customAttributes = $attributesTable->getCompanyAttributes($company->id);
		}
		
		if($this->appSettings->enable_multilingual){
			JBusinessDirectoryTranslations::updateBusinessListingsTranslation($companies);
			JBusinessDirectoryTranslations::updateBusinessListingsSloganTranslation($companies);
		}
		
		if($searchDetails["orderBy"]=="rand()"){
			shuffle($companies);
		}
		
		JRequest::setVar("search-results",$companies);
		
		return $companies;
	}
	
	function getTotalCompaniesByNameAndCategory(){
		// Load the content if it doesn't already exist
		if (empty($this->_total)) {
			$searchDetails = $this->getSearchParams();
			$companiesTable = $this->getTable("Company");
			$this->_total = $companiesTable->getTotalCompaniesByNameAndCategories($searchDetails);
		}
		
		return $this->_total;
	}
	
	function getPagination(){
		// Load the content if it doesn't already exist
		if (empty($this->_pagination)) {
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotalCompaniesByNameAndCategory(), $this->getState('limitstart'), $this->getState('limit') );
			$this->_pagination->setAdditionalUrlParam('option','com_jbusinessdirectory');
			$this->_pagination->setAdditionalUrlParam('controller','search');
			
			if(isset($this->categoryId) && $this->categoryId!='')
				$this->_pagination->setAdditionalUrlParam('categoryId',$this->categoryId);
			$this->_pagination->setAdditionalUrlParam('categoryId',$this->categoryId);
			
			if(isset($this->categorySearch) && $this->categorySearch!='')
				$this->_pagination->setAdditionalUrlParam('categorySearch',$this->categorySearch);
			$categories = JRequest::getVar("categories");
			if(!empty($categories))
				$this->_pagination->setAdditionalUrlParam('categories',$categories);
			
			$orderBy = JRequest::getVar("orderBy", "packageOrder desc");
			if(!empty($orderBy))
				$this->_pagination->setAdditionalUrlParam('orderBy',$orderBy);
			
			if(!empty($this->keyword))
				$this->_pagination->setAdditionalUrlParam('searchkeyword',$this->keyword);

			if(!empty($this->citySearch))
				$this->_pagination->setAdditionalUrlParam('typeSearch',$this->citySearch);
			
			if(!empty($this->zipCode))
				$this->_pagination->setAdditionalUrlParam('zipcode',$this->zipCode);
			
			if(!empty($this->regionSearch))
				$this->_pagination->setAdditionalUrlParam('regionSearch',$this->regionSearch);
			
			if(!empty($this->countrySearch))
				$this->_pagination->setAdditionalUrlParam('countrySearch',$this->countrySearch);
			
			if(!empty($this->typeSearch))
				$this->_pagination->setAdditionalUrlParam('typeSearch',$this->typeSearch);
			
			if(!empty($this->radius))
				$this->_pagination->setAdditionalUrlParam('radius',$this->radius);
			
			if(!empty($this->preserve))
				$this->_pagination->setAdditionalUrlParam('preserve',$this->preserve);
			
			$this->_pagination->setAdditionalUrlParam('view','search');
		}
		return $this->_pagination;
	}
	
	function getSearchFilter(){
	
		$categoryService = new JBusinessDirectorCategoryLib();
		$category=array();
		if(!empty($this->categoryId) && $this->appSettings->search_type != 1){
			$category = $categoryService->getCompleteCategoryById($this->categoryId);
		} else {
			$category["subCategories"] = $categoryService->getCategories();
			$category["path"]=array();
		}
		
		$companiesTable = $this->getTable("Company");
		$searchDetails = $this->getSearchParams();
		$searchDetails["facetedSearch"] = $this->appSettings->search_type;
		
		if(empty($category["subCategories"])){
			$searchDetails["categoriesIds"] = array($category[0]->parent_id);		}
		
		if($this->appSettings->search_type == 1){
			$searchDetails["categoriesIds"] = null;
		}
		$categoriesTotal = $companiesTable->getTotalCompaniesByCategories($searchDetails);	
		
		$subcategories='';
		$enableSelection = false;
		if($this->appSettings->enable_multilingual){
			$categoryTranslations = JBusinessDirectoryTranslations::getCategoriesTranslations();
			foreach($category["path"] as &$path){
				if(!empty($categoryTranslations[$path[0]])){
					$path[1] = $categoryTranslations[$path[0]]->name;
				}
			}
			
		}
		
		$this->searchFilter["path"]=$category["path"];
		
		if(isset($category["subCategories"]) && count($category["subCategories"])>0){
			$subcategories = $category["subCategories"];
		}else {
			$parentCategories = $category["path"];
// 			dump($parentCategories);
			if(count($parentCategories)>0){
				$categoryId = $parentCategories[count($parentCategories)][0];	
				//dump($categoryId);
				$parentCategory = $categoryService->getCompleteCategoryById($categoryId);
				$subcategories = $parentCategory["subCategories"];
				$this->searchFilter["enableSelection"]=1;
				$enableSelection = true;
			}
		}	
		
 		$categories = array();
		if(isset($subcategories) && $subcategories!=''){
			if($this->appSettings->enable_multilingual){
				JBusinessDirectoryTranslations::updateCategoriesTranslation($subcategories);
			}
			foreach($subcategories as $cat){
				if(!is_array($cat))
					continue;
				
				$childCategoryIds = $categoryService->getCategoryChilds($cat);

				if(count($childCategoryIds)==0){
					$childCategoryIds = array($cat[0]->id);
				}else{
					$mainCat = array($cat[0]->id);
					$childCategoryIds = array_merge($mainCat, $childCategoryIds);
				}
				
				$companies =array();
				$companiesNumber = 0;
				foreach($categoriesTotal as $categoryTotal){
					if(in_array( $categoryTotal->id, $childCategoryIds)){
					   $companiesNumber += $categoryTotal->nr_listings;
					}
				}
		
				if( $companiesNumber > 0 || $enableSelection)
					$this->searchFilter["categories"][]=array($cat, $companiesNumber);
			}
		}
		
		return $this->searchFilter;
	}
	
	function getCategory(){
		$categoryTable = $this->getTable("Category", "JBusinessTable");
		$category = $categoryTable->getCategoryById($this->categoryId);
		
		if($this->appSettings->enable_multilingual){
			JBusinessDirectoryTranslations::updateEntityTranslation($category, CATEGORY_TRANSLATION);
		}
		
		return $category;
	}
	
	function getSelectedCategories(){
		$categories = array();
		$selectedCat = JRequest::getVar("categories");
		if(!empty($selectedCat)){
			$categories = explode(";", $selectedCat);
		}
		
		if(!empty($this->categoryId) && !isset($selectedCat)){
			$categories[]=$this->categoryId;
		}
		
		if (in_array('', $categories))
		{
			unset($categories[array_search('',$categories)]);
		}

		return $categories;
	}

	function getLocation(){
		return $this->location;
	}
	
	function getMainCategories(){
		$categoryTable = $this->getTable("Category","JBusinessTable");
		return  $categoryTable->getMainCategories();
	}
	
	function getSubCategories(){
		$categoryTable = $this->getTable("Category","JBusinessTable");
		return  $categoryTable->getSubCategories();
	}
	
	function getSortByConfiguration(){
		
		$states = array();
		$state = new stdClass();
		$state->value = 'packageOrder desc';
		$state->text = JTEXT::_("LNG_RELEVANCE");
		$states[] = $state;
		
		$state = new stdClass();
		$state->value = 'id desc';
		$state->text = JTEXT::_("LNG_LATEST");
		$states[] = $state;
		
		$state = new stdClass();
		$state->value = 'companyName';
		$state->text = JTEXT::_("LNG_NAME");
		$states[] = $state;
		$state = new stdClass();
		$state->value = 'city asc';
		$state->text = JTEXT::_("LNG_CITY");
		$states[] = $state;

		if ($this->appSettings->enable_ratings == 1) {
			$state = new stdClass();
			$state->value = 'averageRating desc';
			$state->text = JTEXT::_("LNG_RATING");
			$states[] = $state;
		}
		
		if ($this->appSettings->enable_reviews == 1) {
			$state = new stdClass();
			$state->value = 'review_score desc';
			$state->text = JTEXT::_("LNG_REVIEW");
			$states[] = $state;
		}
		
		$state = new stdClass();
		$state->value = 'rand()';
		$state->text = JTEXT::_("LNG_RANDOM");
		$states[] = $state;
		
		return $states;
	}
	
	function getCountry(){
		$country = null;
		if(!empty($this->countrySearch)){
			$countryTable = $this->getTable("Country","JTable");
			$country =  $countryTable->getCountry($this->countrySearch);
			if($this->appSettings->enable_multilingual){
				JBusinessDirectoryTranslations::updateEntityTranslation($country, COUNTRY_DESCRIPTION_TRANSLATION);
			}
		}
		
		return $country;
	}
}
?>

