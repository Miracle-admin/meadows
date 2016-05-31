<?php
/**
* @copyright	Copyright (C) 2008-2009 CMSJunkie. All rights reserved.
* 
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
* See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class JTableCompany extends JTable
{
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
/**
	 * @param	JDatabase	A database connector object
	 */
	function __construct(&$db)
	{
		parent::__construct('#__jbusinessdirectory_companies', 'id', $db);
	}
	
	
	public function delete($pk = null, $children = false)
	{
		$query = $this->_db->getQuery(true);
		$query->delete();
		$query->from("#__jbusinessdirectory_companies");
		$query->where('id = ' . (int)$pk);
		$this->_runQuery($query, 'JLIB_DATABASE_ERROR_DELETE_FAILED');
		
		return parent::delete($pk, $children);
	}
	
	/**
	 * Method to run an update query and check for a database error
	 *
	 * @param   string  $query         The query.
	 * @param   string  $errorMessage  Unused.
	 *
	 * @return  boolean  False on exception
	 *
	 * @since   11.1
	 */
	protected function _runQuery($query, $errorMessage)
	{
		$this->_db->setQuery($query);
	
		// Check for a database error.
		if (!$this->_db->execute())
		{
			$e = new JException($this->_db->getErrorMsg());
			$this->setError($e);
			$this->_unlock();
			return false;
		}
		if ($this->_debug)
		{
			$this->_logtable();
		}
	}
	
	function getCompanies($searchFilter, $limitstart=0, $limit=0){
		$db =JFactory::getDBO();
		$companiesQuery = "select bc.*, ct.name as typeName 
							from #__jbusinessdirectory_companies bc 
							left join #__jbusinessdirectory_company_types ct on  bc.typeId=ct.id  $searchFilter";
		//dump($companiesQuery);
		$db->setQuery($companiesQuery, $limitstart, $limit);
		return $db->loadObjectList();
	}
	
	function getAllCompanies(){
		$db =JFactory::getDBO();
		$companiesQuery = "select bc.id, bc.name, bc.alias, ct.name as typeName 
						    from #__jbusinessdirectory_companies bc 
							left join #__jbusinessdirectory_company_types ct on  bc.typeId=ct.id order by bc.name";
		//dump($companiesQuery);
		$db->setQuery($companiesQuery);
		return $db->loadObjectList();
	}
	
	function getTotalCompanies($searchFilter){
		$db =JFactory::getDBO();
		$companiesQuery = "select bc.* from #__jbusinessdirectory_companies bc   $searchFilter";
		$db->setQuery($companiesQuery);
		$db->query();
		return $db->getNumRows();
	}
	
	function getCompany($companyId){
		$db =JFactory::getDBO();
		
		$query = "select bc.*, ct.name as typeName,GROUP_CONCAT(cg.id) as categoryIds, GROUP_CONCAT(cg.name separator '#') as categoryNames, 
				GROUP_CONCAT(cg.alias separator '#') as categoryAliases, cnt.contact_name, cnt.contact_email,cnt.contact_phone,cnt.contact_fax, cr.country_name,
				ccm.name as mainCategory, ccm.alias as mainCategoryAlias, ccm.markerLocation as categoryMarker, ccm.id as mainCategoryId
				from #__jbusinessdirectory_companies bc 
				left join #__jbusinessdirectory_company_types ct on  bc.typeId=ct.id
				left join #__jbusinessdirectory_company_contact cnt on bc.id=cnt.companyId 
				left join #__jbusinessdirectory_company_category cc on bc.id=cc.companyId 
				left join #__jbusinessdirectory_categories cg on cg.id=cc.categoryId 
				left join #__jbusinessdirectory_categories ccm on ccm.id=bc.mainSubcategory and ccm.published=1
				left join #__jbusinessdirectory_countries as cr on cr.id = bc.countryId 
				where bc.id=".$companyId." group by bc.id order by name";
		$db->setQuery($query);
		$result =  $db->loadObject();
		
		return $result;
	}
	
	function getCompaniesByLetterSql($letter, $enablePackage, $showPendingApproval){
		$packageFilter = '';
		if($enablePackage){
			$packageFilter = " and (((inv.state= ".PAYMENT_STATUS_PAID." and now() > (inv.start_date) and (now() < (inv.start_date + INTERVAL p.days DAY) or (inv.package_id=p.id and p.days = 0)))) or p.price=0) ";
		}
		
		$companyStatusFilter="and (cp.approved = ".COMPANY_STATUS_APPROVED." or cp.approved= ".COMPANY_STATUS_CLAIMED.") ";
		if($showPendingApproval){
			$companyStatusFilter = "and (cp.approved = ".COMPANY_STATUS_APPROVED." or cp.approved= ".COMPANY_STATUS_CLAIMED." or cp.approved= ".COMPANY_STATUS_CREATED.") ";
		}
		
		if($letter=="[x]"){
			$letter = "#";
		}
		
		$query = "select slec.*, mainCategory, mainCategoryId, companyName, companyId1, count(cra.id) as nrRatings, features, if(FIND_IN_SET('featured_companies',features) ,1,0) as featured
				 from (
				 select cp.id, cp.name, cp.alias, cp.short_description, cp.street_number, cp.address, cp.city, cp.county, cp.typeId, cp.website, cp.phone, cp.email, cp.state, cp.fax,
				 cp.averageRating, cp.slogan, cp.logoLocation,
				 cp.featured,cp.publish_only_city,
				 cp.name as companyName,cp.id as companyId1, cp.latitude, cp.longitude, cp.postalCode,
				 cn.country_name as countryName,ct.name as typeName, 
				 GROUP_CONCAT( DISTINCT cg.id) as categoryIds, GROUP_CONCAT(DISTINCT cg.name separator '#') as categoryNames, GROUP_CONCAT(cg.alias separator '#') as categoryAliases,
				 GROUP_CONCAT(DISTINCT pf.feature) as features,
				 cg.name as mainCategory, cg.alias as mainCategoryAlias, cg.markerLocation as categoryMaker, cg.id as mainCategoryId
				 from #__jbusinessdirectory_companies cp 
				left join #__jbusinessdirectory_company_category cc on cp.id=cc.companyId 
				left join #__jbusinessdirectory_categories cg on cg.id=cc.categoryId 
				left join #__jbusinessdirectory_countries cn on cp.countryId=cn.id 
				left join #__jbusinessdirectory_company_types ct on cp.typeId=ct.id 
				left join #__jbusinessdirectory_orders inv on inv.company_id=cp.id 
				left join #__jbusinessdirectory_packages p on (inv.package_id=p.id and p.status=1 and $enablePackage) or (p.price=0 and p.status=1 and $enablePackage)
				left join #__jbusinessdirectory_package_fields pf on p.id=pf.package_id
				where (cp.name like '#lowerLetter%' or cp.name like '#upperLetter%' ) $packageFilter and cp.state=1  $companyStatusFilter 
				group by cp.id order by cp.name  ) as slec 
				left join #__jbusinessdirectory_company_ratings cra on cra.companyId=companyId1
				group by companyId1 
				order by companyName";
		
		
		if($letter=="[0-9]"){
			$query = str_replace ("like '#lowerLetter%'"," REGEXP '^[0-9]'", $query);
			$query = str_replace ("like '#upperLetter%'"," REGEXP '^[0-9]'", $query);
		}else{
			$query = str_replace ("#lowerLetter", strtolower($letter), $query);
			$query = str_replace ("#upperLetter", strtoupper($letter), $query);
		}
		
		return $query;
	} 
	
	function getCompaniesByLetter($letter, $enablePackage, $showPendingApproval, $limitstart=0, $limit=0){
		$db =JFactory::getDBO();
		$query = $this->getCompaniesByLetterSql($letter, $enablePackage, $showPendingApproval);
		//echo $query;
		$db->setQuery($query, $limitstart, $limit);
		return $db->loadObjectList();
	}
	
	function getTotalCompaniesByLetter($letter,$enablePackage, $showPendingApproval){
		$db =JFactory::getDBO();
		$query = $this->getCompaniesByLetterSql($letter, $enablePackage, $showPendingApproval);
	
		$db->setQuery($query);
		$db->query();
		
		return $db->getNumRows();
	}
	
	function getCompaniesByPhone($phone, $limitstart=0, $limit=0){
		$db =JFactory::getDBO();
		$serachCompaniesByPhoneQuery = "select * from #__jbusinessdirectory_companies where telefon1 like '%#phone%' order by name";
		$query = str_replace ("#phone", $phone, $serachCompaniesByPhoneQuery);
		$db->setQuery($query, $limitstart, $limit);
		return $db->loadObjectList();
	}
	
	function getTotalCompaniesByPhone($phone){
		$db =JFactory::getDBO();
		$serachCompaniesByPhoneQuery = "select * from #__jbusinessdirectory_companies where telefon1 like '%#phone%' order by name";
		$query = str_replace ("#phone", $phone, $serachCompaniesByPhoneQuery);
		$db->setQuery($query);
		$db->query();
		return $db->getNumRows();
	}
	
	
	function getCompaniesByNameAndCategoriesSql($searchDetails, $totalCategories = false){
		$db =JFactory::getDBO();
		
		$keyword = isset($searchDetails['keyword'])?$searchDetails['keyword']:null;
		$categoriesIDs = isset($searchDetails["categoriesIds"])?$searchDetails["categoriesIds"]:null;
		$latitude = isset($searchDetails["latitude"])?$searchDetails["latitude"]:null;
		$longitude = isset($searchDetails["longitude"])?$searchDetails["longitude"]:null;
		$radius = isset($searchDetails["radius"])?$searchDetails["radius"]:null;
		$type = isset($searchDetails["typeSearch"])?$searchDetails["typeSearch"]:null;
		$city = isset($searchDetails["citySearch"])?$searchDetails["citySearch"]:null; 
		$region = isset($searchDetails["regionSearch"])?$searchDetails["regionSearch"]:null; 
		$countryId = isset($searchDetails["countrySearch"])?$searchDetails["countrySearch"]:null; 
		$enablePackage = isset($searchDetails["enablePackages"])?$searchDetails["enablePackages"]:null; 
		$showPendingApproval = isset($searchDetails["showPendingApproval"])?$searchDetails["showPendingApproval"]:null; 
		$orderBy = isset($searchDetails["orderBy"])?$searchDetails["orderBy"]:null;
		$facetedSearch = isset($searchDetails["facetedSearch"])?$searchDetails["facetedSearch"]:null;
		$zipCodeSearch = isset($searchDetails["zipcCodeSearch"])?$searchDetails["zipcCodeSearch"]:null;
		$limitCities = isset($searchDetails["limit_cities"])?$searchDetails["limit_cities"]:null;
		$customAttributes = isset($searchDetails["customAttributes"])?$searchDetails["customAttributes"]:null;
		
		$featured = isset($searchDetails["featured"])?$searchDetails["featured"]:null;
		
		$keywordLocation = isset($searchDetails['keywordLocation'])?$searchDetails['keywordLocation']:null;
		$showLocations = isset($searchDetails["showSecondayLocationsMap"])?$searchDetails["showSecondayLocationsMap"]:null;
		
		if(!empty($keywordLocation)){
			$keyword = $keywordLocation;
		}
		
		if(empty($orderBy) || $orderBy=="rand()"){
			$orderBy ="packageOrder desc";
		}else{
			$orderBy = $db->escape($orderBy);
		}
		
		$whereCatCond = '';
		//dump($categoriesIDs);
		if($facetedSearch == 1){
			if(!empty($categoriesIDs)){
				//dump($categoriesIDs);
				foreach($categoriesIDs as $categoryId){
					$values = explode(",", $categoryId);
					$whereCatCond .= ' and (0  ';
					foreach($values as $value){
						$whereCatCond .= " or cp.categoryIds REGEXP '[[:<:]]".$value."[[:>:]]' ";
					}
					
					$whereCatCond .= ' ) ';
				}
			}
		}else{
			if(!empty($categoriesIDs) && count($categoriesIDs)>0){
				//dump($categoriesIDs);
				foreach($categoriesIDs as $categoryId){
					$whereCatCond .= " and cc.categoryId in ($categoryId)";
				}
			}
		}
		
		//dump($whereCatCond);
		//$whereCatCond = '';

		$distanceQuery = "";
		$having = "";
		if(!empty($latitude) && !empty($longitude) && $radius>0){
			$distanceQuery = ", 3956 * 2 * ASIN(SQRT( POWER(SIN(($latitude -( slec.latitude)) * pi()/180 / 2),2) + COS($latitude * pi()/180 ) * COS( abs( slec.latitude) *  pi()/180) * POWER(SIN(($longitude -  slec.longitude) *  pi()/180 / 2), 2) )) as distance";

			$orderBy = "distance, ".$orderBy;
			if($zipCodeSearch == SEARCH_BY_DISTNACE){
				if($radius>0)
					$having = "having distance < $radius";
			}else{
				$having = "having distance < slec.activity_radius";
			}
		}
		
		if($featured){
			$having = "having featured = 1";
		}
		
		
		$whereNameCond='';
		if(!empty($keyword)){
			/*$keyword = $db->escape($keyword);
			while(strpos($keyword, "  ")){
				$keyword = str_replace("  ", " ", $keyword);
			}
			$keyword = str_replace(" ", "|", $keyword);
			$whereNameCond=" and (cp.name REGEXP '$keyword'  or cg.name REGEXP '$keyword' or cp.short_description REGEXP '$keyword' or cp.phone REGEXP '$keyword' or  cp.keywords REGEXP '$keyword' or ca.value REGEXP '$keyword') ";
			*/	
			
			$keyword = $db->escape($keyword); 
			$whereNameCond=" and (cp.name like '%$keyword%' or cg.name like '%$keyword%' or cp.short_description like '%$keyword%' or cp.phone like '%$keyword%' or LOCATE('$keyword', cp.keywords)>0  or ca.value like '%$keyword%') ";
		}
		
		$whereCityCond='';
		if(!empty($city)){
			$city = $db->escape($city);
			if($limitCities){
				$whereCityCond=" and cty.name = '".$city."' ";
			}else{
				$whereCityCond=" and cp.city = '".$city."' ";
			}
		}

		$whereRegionCond='';
		if(!empty($region)){
			$region = $db->escape($region);
			$whereRegionCond=" and cp.county = '".$db->escape($region)."' ";
		}
		
		$whereCountryCond='';
		if(!empty($countryId)){
			$whereCountryCond=" and cp.countryId = $countryId";
		}
		
		$whereTypeCond='';
		if(!empty($type)){
			$whereTypeCond=" and cp.typeId = $type";
		}
		
		$companyStatusFilter="and (cp.approved = ".COMPANY_STATUS_APPROVED." or cp.approved= ".COMPANY_STATUS_CLAIMED.") ";
		if($showPendingApproval){
			$companyStatusFilter = "and (cp.approved = ".COMPANY_STATUS_APPROVED." or cp.approved= ".COMPANY_STATUS_CLAIMED." or cp.approved= ".COMPANY_STATUS_CREATED.") ";
		}
		
		$packageFilter = '';
		if($enablePackage){
			$packageFilter = " and (((inv.state= ".PAYMENT_STATUS_PAID." and now() > (inv.start_date) and (now() < (inv.start_date + INTERVAL p.days DAY) or (inv.package_id=p.id and p.days = 0)))) or p.price=0) ";
		}
		
		$customAttrFilter="";
		if(!empty($customAttributes)){
			//dump($customAttributes);
			$customAttrFilterS="";
			$index=0;
			foreach($customAttributes as $key=>$value){
				$index++;
				$values = explode(",",$value);
				$filter = "";
				foreach($values as $value2){
					$value2 = $db->escape($value2);
					if(is_numeric($value2)){
						$filter.=" or ca.option_id = $value2 ";
					}
				}
				if($index>1){
					$customAttrFilterS .=" or ";
				}
				
				$customAttrFilterS .=" (ca.attribute_id = $key  and (ca.value like '%$value%' $filter)) ";
			}
			
			$customAttrFilter=" and ($customAttrFilterS) ";
		}
		
		//dump($customAttrFilter);
		//exit;
	//	dump($orderBy);
		//dump($whereCatCond);
		$query = "select slec.*, mainCategory, mainCategoryId, categoryMaker, companyName, companyId1, count(cra.id) as nrRatings, features, GREATEST(if(FIND_IN_SET('featured_companies',features) ,1,0), featured) as featured
				$distanceQuery
				 from (
					 select cp.*, cp.name as companyName,cp.id as companyId1, 
					 ".(!empty($whereCountryCond)? "cn.country_name as countryName,":"")."
					 ct.name as typeName, 
					 #cnt.contact_name,
					 GROUP_CONCAT(DISTINCT pf.feature) as features, max(p.ordering) as packageOrder, 
					 bc.name as mainCategory, bc.alias as mainCategoryAlias ,bc.markerLocation as categoryMaker, bc.id as mainCategoryId
					from 
					(
					 select 
				   	  	cp2.id, cp2.name, cp2.alias, cp2.short_description, cp2.street_number, cp2.address, cp2.city, cp2.county, cp2.website, cp2.phone, cp2.email, cp2.state, cp2.fax,
						 cp2.averageRating, cp2.slogan,cp2.logoLocation, cp2.activity_radius, cp2. review_score,
						 cp2.featured,cp2.publish_only_city,cp2.userId,
						 cp2.latitude, cp2.longitude, cp2.keywords,cp2.approved, cp2.mainSubcategory, cp2.countryId,cp2.typeId, cp2.postalCode,
						
					 	GROUP_CONCAT( DISTINCT cg.id) as categoryIds, GROUP_CONCAT(cg.name separator '#') as categoryNames, GROUP_CONCAT(cg.alias separator '#') as categoryAliases
					 	".($showLocations?",GROUP_CONCAT(DISTINCT l.latitude,'|',l.longitude,'|',l.street_number,'|',l.address,'|',l.city,'|',l.county,'|',l.postalCode,'|',l.phone) as locations ":"")."
					 
					  from #__jbusinessdirectory_companies cp2
					  left join #__jbusinessdirectory_company_category cc on cp2.id=cc.companyId 
					  left join #__jbusinessdirectory_categories cg on cg.id=cc.categoryId and cg.published=1
					  ".($showLocations?"left join #__jbusinessdirectory_company_locations l on cp2.id = l.company_id ":"")."
					  where 1 ".(empty($facetedSearch)?$whereCatCond:"")."
					  group by cp2.id
					 ) as cp
					left join #__jbusinessdirectory_company_category cc on cp.id=cc.companyId 
					left join #__jbusinessdirectory_categories cg on cg.id=cc.categoryId and cg.published=1
					left join #__jbusinessdirectory_categories bc on bc.id=cp.mainSubcategory and bc.published=1
					 ".((!empty($whereCityCond) && $limitCities )?"
					left join #__jbusinessdirectory_company_activity_city cat on cat.company_id=cp.id
					left join #__jbusinessdirectory_cities cty on cty.id=cat.city_id":"")
					."
					#left join #__jbusinessdirectory_company_contact cnt on cp.id=cnt.companyId 
					 ".(!empty($whereCountryCond)?"left join #__jbusinessdirectory_countries cn on cp.countryId=cn.id":"")."
					left join #__jbusinessdirectory_company_types ct on cp.typeId=ct.id 
					left join #__jbusinessdirectory_orders inv on inv.company_id=cp.id
					left join #__jbusinessdirectory_packages p on (inv.package_id=p.id and p.status=1 and $enablePackage) or (p.price=0 and p.status=1 and $enablePackage)
					left join #__jbusinessdirectory_package_fields pf on p.id=pf.package_id
					left join #__jbusinessdirectory_company_attributes AS ca on ca.company_id=cp.id"
					 .(!empty($customAttrFilter)?"
					left join #__jbusinessdirectory_attributes a on ca.attribute_id = a.id
					left join #__jbusinessdirectory_attribute_options as ao on ao.attribute_id = a.id ":"").
					" where 1 $whereNameCond $whereCatCond $whereTypeCond $packageFilter $whereCityCond $whereRegionCond $whereCountryCond $customAttrFilter and cp.state=1 $companyStatusFilter
					group by cp.id order by cp.name  ) as slec 
				left join #__jbusinessdirectory_company_ratings cra on cra.companyId=companyId1
				group by companyId1 
				$having
				order by featured desc, $orderBy";
		
		
		if($totalCategories){
			$parentId = 1;
			if(!empty($categoriesIDs)){
				$categoriesIDs= explode(",",$categoriesIDs[0]);
				$parentId = end($categoriesIDs);
			}
			
			$query = "select count(distinct cp.id) as nr_listings, cg1.name, cg1.id
					from #__jbusinessdirectory_companies cp
					inner join #__jbusinessdirectory_company_category cc on cp.id=cc.companyId 
					inner join #__jbusinessdirectory_categories cg on cg.id=cc.categoryId and cg.published=1
					inner join #__jbusinessdirectory_categories cg1 ON cg1.id = cg.parent_id or cg1.id=cg.id
					 ".((!empty($whereCityCond) && $limitCities )?"
					left join #__jbusinessdirectory_company_activity_city cat on cat.company_id=cp.id
					left join #__jbusinessdirectory_cities cty on cty.id=cat.city_id":"")
					."
					#left join #__jbusinessdirectory_company_contact cnt on cp.id=cnt.companyId 
					 ".(!empty($whereCountryCond)?"left join #__jbusinessdirectory_countries cn on cp.countryId=cn.id":"")." 
					left join #__jbusinessdirectory_company_types ct on cp.typeId=ct.id 
					left join #__jbusinessdirectory_orders inv on inv.company_id=cp.id 
					left join #__jbusinessdirectory_packages p on (inv.package_id=p.id and p.status=1) or (p.price=0 and p.status=1)
					left join #__jbusinessdirectory_company_attributes AS ca on ca.company_id=cp.id"
					.(!empty($customAttrFilter)?"
					left join #__jbusinessdirectory_attributes a on ca.attribute_id = a.id
					left join #__jbusinessdirectory_attribute_options as ao on ao.attribute_id = a.id":"").
					" where 1 and cg1.parent_id = $parentId $whereNameCond $whereTypeCond $packageFilter $whereCityCond $whereRegionCond $whereCountryCond $customAttrFilter and cp.state=1 $companyStatusFilter
					group by cg1.id";
			//echo($query);
		}
	
		
		return $query;
	}
	
	function getCompaniesByNameAndCategories($searchDetails, $limitstart=0, $limit=0){
		$db =JFactory::getDBO();
		$startTime = microtime(true); // Gets current microtime as one long string
		 
		$query = $this->getCompaniesByNameAndCategoriesSql($searchDetails);
	
		//echo($query);
		$db->setQuery($query, $limitstart, $limit);
		
		$result =  $db->loadObjectList();
		//dmp($this->_db->getErrorMsg());
		//dump($result);
		$endTime = microtime(true) - $startTime; // And this at the end of your code
			
		//echo PHP_EOL . 'Script took ' . round($endTime, 4) . ' seconds to run.';
		return $result;
	}
	
	function getTotalCompaniesByNameAndCategories($searchDetails){
		$startTime = microtime(true); // Gets current microtime as one long string
		$db =JFactory::getDBO();
		$query = $this->getCompaniesByNameAndCategoriesSql($searchDetails, false);
		
		$db->setQuery($query);
		if(!$db->query()){
			return 0;
		}
		$endTime = microtime(true) - $startTime; // And this at the end of your code
			
	//	echo PHP_EOL . 'Total Script took ' . round($endTime, 4) . ' seconds to run.';
		return $db->getNumRows();
	}
	
	function getTotalCompaniesByCategories($searchDetails){
		$startTime = microtime(true); // Gets current microtime as one long string
		$db =JFactory::getDBO();
		
		$db->setQuery("SET OPTION SQL_BIG_SELECTS=1 ");
		//$db->query();
		
		$db->setQuery("SET SESSION group_concat_max_len = 10000");
		$db->query();
		
		$query = $this->getCompaniesByNameAndCategoriesSql($searchDetails, true);
	
		$db->setQuery($query);
		
		$result =  $db->loadObjectList();
		//dump($result);
		
		$endTime = microtime(true) - $startTime; // And this at the end of your code
			
		//	echo PHP_EOL . 'Total Script took ' . round($endTime, 4) . ' seconds to run.';
		return $result;
	}
	
	public function getCompanyCategories($companyId){
		$db =JFactory::getDBO();
		$query = "select GROUP_CONCAT(c.name) as categories from #__jbusinessdirectory_categories c
				  left join #__jbusinessdirectory_company_category cc on c.id=cc.categoryId
				  where companyId=".$companyId." 
  				  group by companyId
				 order by c.name";
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result->categories;
	}
	
	function deteleCompany($companyId){
		$db =JFactory::getDBO();
		$sql = "delete from #__jbusinessdirectory_companies where id=".$companyId;
		$db->setQuery($sql);
		return $db->query();
	}
	
	function changeState($companyId){
		$db =JFactory::getDBO();
		$query = 	" UPDATE #__jbusinessdirectory_companies SET state = IF(state, 0, 1) WHERE id = ".$companyId ;
		$db->setQuery( $query );
		if (!$db->query()){
			return false;
		}
		return true;
	}
	
	function changeFeaturedState($companyId){
		$db =JFactory::getDBO();
		$query = 	" UPDATE #__jbusinessdirectory_companies SET featured = IF(featured, 0, 1) WHERE id = ".$companyId ;
		$db->setQuery( $query );
		if (!$db->query()){
			return false;
		}
		return true;
	}
	
	function changeAprovalState($companyId, $state){
		$db =JFactory::getDBO();
		$query = " UPDATE #__jbusinessdirectory_companies SET approved=$state WHERE id = ".$companyId ;
		$db->setQuery( $query );
		if (!$db->query()){
			return false;
		}
		return true;
	}
	
	function changeClaimState($companyId, $state){
		$db =JFactory::getDBO();
		$query = " UPDATE #__jbusinessdirectory_company_claim SET status=$state WHERE companyId = ".$companyId ;
		$db->setQuery( $query );
		if (!$db->query()){
			return false;
		}
		return true;
	}
	
	function getCompaniesByUserId($userId){
		$db =JFactory::getDBO(); 
		$query = "select * from #__jbusinessdirectory_companies bc  where bc.userId=".$userId;
		// 		dump($query);
		$db->setQuery($query,0,1000);
		return $db->loadObjectList();
	}
	
	function getCompanyTypes(){
		$db =JFactory::getDBO();
		$query = "select id as value, name as text from #__jbusinessdirectory_company_types order by name";
		// 		dump($query);
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	function increaseViewCount($id){
		$db =JFactory::getDBO();
		$query = "update  #__jbusinessdirectory_companies set viewCount = viewCount + 1 where id=$id";
		// 		dump($query);
		$db->setQuery($query);
		return $db->query();
	}
	
	function increaseWebsiteCount($id){
		$db =JFactory::getDBO();
		$query = "update  #__jbusinessdirectory_companies set websiteCount = websiteCount + 1 where id=$id";
		// 		dump($query);
		$db->setQuery($query);
		return $db->query();
	}
	
	function increaseContactsNumber($id){
		$db =JFactory::getDBO();
		$query = "update  #__jbusinessdirectory_companies set contactCount = contactCount + 1 where id=$id";
		//dump($query);
		$db->setQuery($query);
		return $db->query();
	}
	
	function resetCompanyOwner($companyId){
		$db =JFactory::getDBO();
		$query = "update  #__jbusinessdirectory_companies set userId = null  where id=$companyId";
		//dump($query);
		$db->setQuery($query);
		return $db->query();
	}
	
	function updateCompanyOwner($companyId, $userId){
		$db =JFactory::getDBO();
		$query = "update  #__jbusinessdirectory_companies set userId = $userId, approved=-1  where id=$companyId";
		$db->setQuery($query);
		return $db->query();
	}
	
	function claimCompany($data){
		$db =JFactory::getDBO();
		$query = "insert into  #__jbusinessdirectory_company_claim (companyId, firstName, lastName, function, phone, email) values 
			(".$db->escape($data['companyId']).",'".$db->escape($data['firstName'])."','".$db->escape($data['lastName'])."','".$db->escape($data['function'])."','".$db->escape($data['phone'])."','".$db->escape($data['email'])."')";
		//dump($query);
		//exit;
		$db->setQuery($query);
		return $db->query();
	}
	
	function getClaimDetails($companyId){
		$db =JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_company_claim where companyId = $companyId and status <> -1";
		$db->setQuery($query);
		return $db->loadObject();
	}
	
	function checkCompanyName($companyName){
		$db =JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_companies where name = '$companyName' ";
		$db->setQuery($query);
		$db->query();
		return $db->getNumRows();
	}
	
	function getCompanyByName($companyName){
		$db =JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_companies where name = '$companyName' ";
		$db->setQuery($query);
		return $db->loadObject();
	}
	
	function getBusinessAboutToExpire($nrDays){
		$db =JFactory::getDBO();
		$packageFilter = '';
		$packageFilter = " and (inv.state= ".PAYMENT_STATUS_PAID." and (CURDATE() + INTERVAL $nrDays DAY) > (inv.start_date + INTERVAL p.days DAY)) ";
		
		$query = "  select cp.*, inv.id as orderId
				from #__jbusinessdirectory_companies cp 
				left join #__jbusinessdirectory_orders inv on inv.company_id=cp.id and inv.expiration_email_date is null
				left join #__jbusinessdirectory_packages p on inv.package_id=p.id 
				where 1 $packageFilter and cp.state=1 and cp.approved=".COMPANY_STATUS_APPROVED."
				group by cp.id ";
		//dump($query);

		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	function getCompaniesForExport($categories){
		$db =JFactory::getDBO();
		
		$whereCatCond="";
		if(!empty($categories))
			$whereCatCond .= " and cc.categoryId in ($categories)";
		
		$query = "select slec.*, companyName, companyId1
				from (
					select cp.*, cp.name as companyName,cp.id as companyId1, cn.country_name as countryName,ct.name as typeName,cnt.contact_name,
					t.name as type,
					p.name as packageName,
					GROUP_CONCAT( DISTINCT cg.id) as categoryIds, GROUP_CONCAT(DISTINCT cg.name separator ',') as categoryNames, GROUP_CONCAT( DISTINCT cpt.picture_path) as pictures
					from #__jbusinessdirectory_companies cp
					left join #__jbusinessdirectory_company_pictures cpt on cpt.companyId=cp.id
					left join #__jbusinessdirectory_company_category cc on cp.id=cc.companyId
					left join #__jbusinessdirectory_categories cg on cg.id=cc.categoryId
					left join #__jbusinessdirectory_countries cn on cp.countryId=cn.id
					left join #__jbusinessdirectory_company_contact cnt on cp.id=cnt.companyId 
					left join #__jbusinessdirectory_company_types t on t.id=cp.typeId
					left join #__jbusinessdirectory_company_types ct on cp.typeId=ct.id
					left join #__jbusinessdirectory_packages p on p.id=cp.package_id
					where 1 $whereCatCond
					group by cp.id order by cp.name  )
				 as slec
				left join #__jbusinessdirectory_company_ratings cra on cra.companyId=companyId1
				group by companyId1
				order by companyName";
		
		$db->setQuery($query);	
		$result =  $db->loadObjectList();	
		return $result;
	}
	
	function getUsedLetters(){
		$db =JFactory::getDBO();
		$query = "SELECT DISTINCT UPPER(LEFT(name, 1)) as letter FROM #__jbusinessdirectory_companies ORDER BY letter";
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	function getTotalListings(){
		$db =JFactory::getDBO();
		$query = "SELECT count(*) as nr FROM #__jbusinessdirectory_companies";
		$db->setQuery($query);
		$result = $db->loadObject();
		
		return $result->nr;
	}
	
	function getTodayListings(){
		$db =JFactory::getDBO();
		$query = "SELECT count(*) as nr FROM #__jbusinessdirectory_companies where  DATE(`creationDate`) = CURDATE() ";
		$db->setQuery($query);
		$result = $db->loadObject();
		
		return $result->nr;
	}
	
	function getWeekListings(){
		$db =JFactory::getDBO();
		$query = "SELECT count(*) as nr FROM #__jbusinessdirectory_companies  WHERE WEEKOFYEAR(creationDate)=WEEKOFYEAR(NOW())";
		$db->setQuery($query);
		$result = $db->loadObject();
		
		return $result->nr;
	}
	
	function getMonthListings(){
		$db =JFactory::getDBO();
		$query = "SELECT count(*) as nr FROM #__jbusinessdirectory_companies WHERE MONTH(creationDate)=MONTH(NOW())";
		$db->setQuery($query);
		$result = $db->loadObject();
		
		return $result->nr;
	}
	
	function getYearListings(){
		$db =JFactory::getDBO();
		$query = "SELECT count(*) as nr FROM #__jbusinessdirectory_companies  WHERE YEAR(creationDate)=YEAR(NOW())";
		$db->setQuery($query);
		$result = $db->loadObject();
		
		return $result->nr;
	}

	function checkIfAliasExists($busienssId, $alias){
		$db =JFactory::getDBO();
		$query = "SELECT count(*) as nr FROM #__jbusinessdirectory_companies  WHERE alias='$alias' and id<>$busienssId";
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result->nr;
	}
	
	function getLastAlias($alias){
		$db =JFactory::getDBO();
		$query = "SELECT alias FROM #__jbusinessdirectory_companies  WHERE alias like'$alias%'order by alias desc";
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result;
	}
	
	function deleteAllDependencies($itemId){
		$db =JFactory::getDBO();
		$sql = "delete from #__jbusinessdirectory_company_activity_city where company_id= $itemId";
		$db->setQuery($sql);
		$db->query();
		
		$sql = "delete from #__jbusinessdirectory_company_attachments WHERE type=1 and object_id = $itemId";
		$db->setQuery($sql);
		$db->query();
		
		$sql = "delete from #__jbusinessdirectory_company_attributes WHERE company_id = $itemId";
		$db->setQuery($sql);
		$db->query();
		
		$sql = "delete from #__jbusinessdirectory_company_category where companyId= $itemId";
		$db->setQuery($sql);
		$db->query();
		
		$sql = "delete from #__jbusinessdirectory_company_contact where companyId= $itemId";
		$db->setQuery($sql);
		$db->query();
		
		$sql = "delete from #__jbusinessdirectory_company_locations where company_id= $itemId";
		$db->setQuery($sql);
		$db->query();
		
		$sql = "delete from #__jbusinessdirectory_company_pictures where companyId= $itemId";
		$db->setQuery($sql);
		$db->query();
		
		$sql = "delete from #__jbusinessdirectory_company_videos where companyId= $itemId";
		$db->setQuery($sql);
		$db->query();
		
		$sql = "delete t1, t2 from #__jbusinessdirectory_company_reviews t1 
			inner join #__jbusinessdirectory_company_reviews_user_criteria t2 on t1.id = t2.review_id where t1.companyId= $itemId";
		$db->setQuery($sql);
		$db->query();
		
		$sql = "delete from #__jbusinessdirectory_company_ratings where companyId= $itemId";
		$db->setQuery($sql);
		$db->query();
		
		return true;
	}
}