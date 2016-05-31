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

class JTableOffer extends JTable
{

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db){

		parent::__construct('#__jbusinessdirectory_company_offers', 'id', $db);
	}

	function setKey($k)
	{
		$this->_tbl_key = $k;
	}


	function changeStateOfferOfTheDay($offerId){
		$db =JFactory::getDBO();
		$query = 	" UPDATE #__jbusinessdirectory_company_offers SET offerOfTheDay = IF(offerOfTheDay, 0, 1) WHERE id = ".$offerId ;
		$db->setQuery( $query );

		if (!$db->query()){
			return false;
		}
		return true;
	}

	function getOffer($offerId){
		$db =JFactory::getDBO();
		$query = "select of.*, GROUP_CONCAT( DISTINCT cg.id) as categoryIds, GROUP_CONCAT(cg.name separator '#') as categoryNames, GROUP_CONCAT(cg.alias separator '#') as categoryAliases  
					from #__jbusinessdirectory_company_offers of
					left join #__jbusinessdirectory_company_offer_category cc on of.id=cc.offerId
					left join #__jbusinessdirectory_categories cg on cg.id=cc.categoryId 
					where of.id=$offerId";
		$db->setQuery($query);
		//dump($query);
		return $db->loadObject();
	}

	function getOffers($filter, $limitstart=0, $limit=0){
		$db =JFactory::getDBO();
		$query = "select co.*,cp.name as companyName from #__jbusinessdirectory_company_offers co
				left join  #__jbusinessdirectory_companies cp on cp.id=co.companyId
		$filter";
		// 		dump($query);
		$db->setQuery($query, $limitstart, $limit);
		return $db->loadObjectList();
	}

	function getTotalOffers(){
		$db =JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_company_offers";
		$db->setQuery($query);
		$db->query();
		return $db->getNumRows();
	}

	function changeState($offerId){
		$db =JFactory::getDBO();
		$query = 	" UPDATE #__jbusinessdirectory_company_offers SET state = IF(state, 0, 1) WHERE id = ".$offerId ;
		$db->setQuery( $query );

		if (!$db->query()){
			return false;
		}
		return true;
	}
	
	function changeStateFeatured($offerId){
		$db =JFactory::getDBO();
		$query = 	" UPDATE #__jbusinessdirectory_company_offers SET featured = IF(featured, 0, 1) WHERE id = ".$offerId ;
		$db->setQuery( $query );
	
		if (!$db->query()){
			return false;
		}
		return true;
	}
	

	function getCompanyOffers($companyId, $limitstart=0, $limit=0){
		$db =JFactory::getDBO();
		$orderByClause= "order by co.id desc";
		
		if(!empty($orderBy)){
			$orderByClause = "order by $orderBy";
		}

		$query = " select co.id, co.specialPrice, co.subject, co.short_description, co.address, co.city, co.county, co.latitude, co.longitude, co.startDate, co.endDate, co.alias, co.view_type, co.article_id, co.url,
					op.picture_info,op.picture_path,GROUP_CONCAT( DISTINCT cg.id) as categoryIds, GROUP_CONCAT(cg.name separator '#') as categoryNames, GROUP_CONCAT(cg.alias separator '#') as categoryAliases 
					from
					#__jbusinessdirectory_company_offers co
					left join  #__jbusinessdirectory_company_offer_pictures op on co.id=op.offerId
					and
						(op.id in (
							select  min(op1.id) as min from #__jbusinessdirectory_company_offers co1
							left join  #__jbusinessdirectory_company_offer_pictures op1 on co1.id=op1.offerId
							where op1.picture_enable=1
							group by co1.id
						)
					)
					left join #__jbusinessdirectory_company_offer_category cc on co.id=cc.offerId
					left join #__jbusinessdirectory_categories cg on cg.id=cc.categoryId
					where co.companyId=$companyId and co.state=1 and co.approved !=-1 and (co.publish_start_date<=DATE(now()) or co.publish_start_date='0000-00-00') and  (co.publish_end_date>=DATE(now()) or co.publish_end_date='0000-00-00') 
					group by co.id
					$orderByClause ";

		//dump($query);
		$db->setQuery($query, $limitstart, $limit);
		return $db->loadObjectList();
	}
	
	function getTotalCompanyOffers($companyId){
		$db =JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_company_offers where companyId=$companyId";
		$db->setQuery($query);
		$db->query();
		return $db->getNumRows();
	}
	
	function getUserOffers($companyIds, $limitstart=0, $limit=0){
		$db =JFactory::getDBO();
		$companyIds = implode(",", $companyIds);
		if(empty($companyIds)){
			return null;
		}
		$query = "select co.*, op.picture_path from #__jbusinessdirectory_company_offers co
					left join  #__jbusinessdirectory_company_offer_pictures op on co.id=op.offerId
					where companyId in ($companyIds) and (1 or op.id in (
					select  min(op1.id) as min from #__jbusinessdirectory_company_offers co1
					left join  #__jbusinessdirectory_company_offer_pictures op1 on co1.id=op1.offerId
					where companyId in ($companyIds)
					group by co1.id))
					group by co.id	
					order by co.startDate";
		
		//dump($query);
		$db->setQuery($query, $limitstart, $limit);
		return $db->loadObjectList();
	}
	
	function getTotalUserOffers($companyIds){
		$db =JFactory::getDBO();
		$companyIds = implode(",", $companyIds);
		if(empty($companyIds)){
			return 0;
		}
		$query = "select * from #__jbusinessdirectory_company_offers where companyId in ($companyIds)";
		$db->setQuery($query);
		$db->query();
		return $db->getNumRows();
	}

	function getOfferPictures($offerId){
		$db =JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_company_offer_pictures where offerId=$offerId and picture_enable=1 order by id";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function getOffersByCategoriesSql($searchDetails){
		$db =JFactory::getDBO();
		
		$keyword = isset($searchDetails['keyword'])?$searchDetails['keyword']:null;
		$categoriesIDs = isset($searchDetails["categoriesIds"])?$searchDetails["categoriesIds"]:null;
		$latitude = isset($searchDetails["latitude"])?$searchDetails["latitude"]:null;
		$longitude = isset($searchDetails["longitude"])?$searchDetails["longitude"]:null;
		$radius = isset($searchDetails["radius"])?$searchDetails["radius"]:null;
		$city = isset($searchDetails["citySearch"])?$searchDetails["citySearch"]:null;
		$region = isset($searchDetails["regionSearch"])?$searchDetails["regionSearch"]:null;
		$enablePackage = isset($searchDetails["enablePackages"])?$searchDetails["enablePackages"]:null;
		$showPendingApproval = isset($searchDetails["showPendingApproval"])?$searchDetails["showPendingApproval"]:null;
		$orderBy = isset($searchDetails["orderBy"])?$searchDetails["orderBy"]:null;
		$featured = isset($searchDetails["featured"])?$searchDetails["featured"]:null;
		
		$whereCatCond = '';
		//dump($categoriesIDs);
		if(isset($categoriesIDs) && count($categoriesIDs)>0){
			$categoriesIDs = implode(",",$categoriesIDs);
			$whereCatCond = " and cc.categoryId in ($categoriesIDs)";
		}
		
		$whereNameCond='';
		if(!empty($keyword)){
			$keyword = $db->escape($keyword);
			$whereNameCond=" and (co.subject like '%$keyword%' or cg.name like '%$keyword%' or co.short_description like '%$keyword%') ";
		}
		
		$whereCityCond='';
		if(!empty($city)){
			$city = $db->escape($city);
			$whereCityCond=" and co.city = '".$db->escape($city)."' ";
		}
		
		$whereRegionCond='';
		if(!empty($region)){
			$region = $db->escape($region);
			$whereRegionCond=" and co.county = '".$db->escape($region)."' ";
		}		
		
		$distanceQuery = "";
		$having = "";
		if(!empty($latitude) && !empty($longitude)){
			$distanceQuery = ", 3956 * 2 * ASIN(SQRT( POWER(SIN(($latitude -abs( co.latitude)) * pi()/180 / 2),2) + COS($latitude * pi()/180 ) * COS( abs( co.latitude) *  pi()/180) * POWER(SIN(($longitude -  co.longitude) *  pi()/180 / 2), 2) )) as distance";

			if(!empty($orderBy))
				$orderBy = "distance, ".$orderBy;	
			else
				$orderBy = "distance";
				
			if($radius>0)
				$having = "having distance < $radius";
		}

		$featuredFilter = "";
		if($featured){
			$featuredFilter = " and co.featured = 1";
		}
		
		$packageFilter = '';
		if($enablePackage){
			$packageFilter = " and (((inv.state= ".PAYMENT_STATUS_PAID." and now() > (inv.start_date) and (now() < (inv.start_date + INTERVAL p.days DAY) or (inv.package_id=p.id and p.days = 0)))) or p.price=0) and pf.feature='company_offers' ";
		}
		
		$companyStatusFilter="and (cp.approved = ".COMPANY_STATUS_APPROVED." or cp.approved= ".COMPANY_STATUS_CLAIMED.") and (co.approved = ".OFFER_APPROVED.")";
		if($showPendingApproval){
			$companyStatusFilter = "and (cp.approved = ".COMPANY_STATUS_APPROVED." or cp.approved= ".COMPANY_STATUS_CLAIMED." or cp.approved= ".COMPANY_STATUS_CREATED.") and (co.approved = ".OFFER_CREATED." or co.approved = ".OFFER_APPROVED.") ";
		}

		if($orderBy=="rand()" || empty ($orderBy)){
			$orderBy = "co.id desc";
		}
		
		$query = " select co.id, co.subject, co.short_description, co.address, co.city, co.county, co.latitude, co.longitude, co.startDate, co.endDate, co.alias, co.view_type, co.article_id, co.url, co.featured,
					op.picture_info,op.picture_path,GROUP_CONCAT( DISTINCT cg.id) as categoryIds, GROUP_CONCAT(cg.name separator '#') as categoryNames, GROUP_CONCAT(cg.alias separator '#') as categoryAliases,
					cp.phone
					$distanceQuery
					from
					#__jbusinessdirectory_company_offers co
					left join  #__jbusinessdirectory_company_offer_pictures op on co.id=op.offerId
					and
						(op.id in (
								select  min(op1.id) as min from #__jbusinessdirectory_company_offers co1
								left join  #__jbusinessdirectory_company_offer_pictures op1 on co1.id=op1.offerId
								where op1.picture_enable=1
								group by co1.id
							)
						)
					left join #__jbusinessdirectory_company_offer_category cc on co.id=cc.offerId
					left join #__jbusinessdirectory_categories cg on cg.id=cc.categoryId
					inner join #__jbusinessdirectory_companies cp on co.companyId = cp.id
					left join #__jbusinessdirectory_orders inv on inv.company_id=cp.id 
					left join #__jbusinessdirectory_packages p on (inv.package_id=p.id and p.status=1) or (p.price=0 and p.status=1)
					left join #__jbusinessdirectory_package_fields pf on p.id=pf.package_id
					where co.state=1 and co.approved !=-1 
					$whereNameCond $whereCityCond $whereRegionCond $featuredFilter
					and (co.publish_start_date<=DATE(now()) or co.publish_start_date='0000-00-00' or co.publish_start_date is null) and  (co.publish_end_date>=DATE(now()) or co.publish_end_date='0000-00-00' or co.publish_end_date is null) $whereCatCond $packageFilter $companyStatusFilter 
					and cp.state=1
					group by co.id
					$having
					order by co.featured desc, $orderBy
					";
		
		return $query;
	}
	
	function getOffersByCategories($searchDetails, $limitstart=0, $limit=0){
		$db =JFactory::getDBO();

		$query = $this->getOffersByCategoriesSql($searchDetails);

		//echo($query);
		$db->setQuery($query, $limitstart, $limit);
		return $db->loadObjectList();
	}
 
	function getTotalOffersByCategories($searchDetails){
		$db =JFactory::getDBO();

		$query = $this->getOffersByCategoriesSql($searchDetails);
		
		$db->setQuery($query);
		$db->query();
		return $db->getNumRows();
	}

	function changeAprovalState($offerId, $state){
		$db =JFactory::getDBO();
		$query = " UPDATE #__jbusinessdirectory_company_offers SET approved=$state WHERE id = ".$offerId ;
		$db->setQuery( $query );

		if (!$db->query()){
			return false;
		}
		return true;
	}

	function increaseViewCount($offerId){
		$db =JFactory::getDBO();
		$query = "update  #__jbusinessdirectory_company_offers set viewCount = viewCount + 1 where id=$offerId";
		$db->setQuery($query);
		return $db->query();
	}
	
	function getTotalNumberOfOffers(){
		$db =JFactory::getDBO();
		$query = "SELECT count(*) as nr FROM #__jbusinessdirectory_company_offers";
		$db->setQuery($query);
		$result = $db->loadObject();
	
		return $result->nr;
	}
	
	function getTotalActiveOffers(){
		$db =JFactory::getDBO();
		$query = "SELECT count(*) as nr FROM #__jbusinessdirectory_company_offers where state =1 and endDate>now()";
		$db->setQuery($query);
		$result = $db->loadObject();
	
		return $result->nr;
	}
	
	function getOfferForExport(){
		$db =JFactory::getDBO();

		$query = "select co.*, GROUP_CONCAT(op.picture_path) as pictures,
					GROUP_CONCAT(cg.name) as categories
					from
					#__jbusinessdirectory_company_offers co
					left join  #__jbusinessdirectory_company_offer_pictures op on co.id=op.offerId
					left join #__jbusinessdirectory_company_offer_category cc on co.id=cc.offerId
					left join #__jbusinessdirectory_categories cg on cg.id=cc.categoryId		
					group by co.id
					order by co.id desc ";
		

		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	function checkAlias($id, $alias){
		$db =JFactory::getDBO();
		$query = "SELECT count(*) as nr FROM #__jbusinessdirectory_company_offers  WHERE alias='$alias' and id<>$id";
		$db->setQuery($query);
		$result = $db->loadObject();
		dump($result);
		return $result->nr;
	}
}