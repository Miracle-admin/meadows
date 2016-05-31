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

class JTableEvent extends JTable
{

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function JTableEvent(& $db) {
		parent::__construct('#__jbusinessdirectory_company_events', 'id', $db);
	}

	function setKey($k)
	{
		$this->_tbl_key = $k;
	}
	
	function getEventsByCategoriesSql($searchDetails){
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
		$startDate = isset($searchDetails["startDate"])?$searchDetails["startDate"]:null;
		$endDate = isset($searchDetails["endDate"])?$searchDetails["endDate"]:null;
		$type = isset($searchDetails["typeSearch"])?$searchDetails["typeSearch"]:null;
		$companyId = isset($searchDetails["companyId"])?$searchDetails["companyId"]:null;
		$orderBy = isset($searchDetails["orderBy"])?$searchDetails["orderBy"]:null;
		//dump($orderBy);
		$featured = isset($searchDetails["featured"])?$searchDetails["featured"]:null;
		
		$whereCatCond = '';
		//dump($categoriesIDs);
		if(isset($categoriesIDs) && count($categoriesIDs)>0){
			$whereCatCond = ' ( ';
			//dump($categoriesIDs);
			foreach($categoriesIDs as $categoryId){
				$whereCatCond .= $categoryId.',';
			}
			$whereCatCond = substr($whereCatCond, 0, -1);
			$whereCatCond.=')';
			$whereCatCond = ' and cc.categoryId in '.$whereCatCond;
		}
		
		$whereDateCond="";

		if(!empty($startDate) && !empty($endDate)){
			$whereDateCond.=" and (co.start_date<='$endDate' and co.end_date>='$startDate')";
		}else if(!empty($startDate)){
				$whereDateCond.=" and co.end_date>='$startDate'";
		}else if(!empty($endDate)){
				$whereDateCond.=" and co.start_date<='$endDate'";
		}else{
			$whereDateCond.=" and co.end_date>= DATE(NOW())";
		}
		
		$whereNameCond='';
		if(!empty($keyword)){
			$keyword = $db->escape($keyword);
			$whereNameCond=" and (co.name like '%$keyword%' or cg.name like '%$keyword%' or co.description like '%$keyword%') ";
		}
		
		$whereTypeCond='';
		if(!empty($type)){
			$whereTypeCond=" and co.type = $type";
		}
		
		$whereCompanyIdCond='';
		if(!empty($companyId)){
			$whereCompanyIdCond=" and cp.id = $companyId";
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
			$packageFilter = " and (((inv.state= ".PAYMENT_STATUS_PAID." and now() > (inv.start_date) and (now() < (inv.start_date + INTERVAL p.days DAY) or (inv.package_id=p.id and p.days = 0)))) or p.price=0) and pf.feature='company_events' ";
		}
		
		$companyStatusFilter="and (cp.approved = ".COMPANY_STATUS_APPROVED." or cp.approved= ".COMPANY_STATUS_CLAIMED.") and (co.approved = ".EVENT_APPROVED.")";
		if($showPendingApproval){
			$companyStatusFilter = "and (cp.approved = ".COMPANY_STATUS_APPROVED." or cp.approved= ".COMPANY_STATUS_CLAIMED." or cp.approved= ".COMPANY_STATUS_CREATED.")  and (co.approved = ".EVENT_CREATED." or co.approved = ".EVENT_APPROVED.") ";
		}

		if($orderBy=="rand()" || empty ($orderBy)){
			$orderBy = "co.id desc";
		}
		
		$query = " select co.*,op.picture_info,op.picture_path, et.name as eventType ,cp.phone, co.featured
					$distanceQuery
					from
					#__jbusinessdirectory_company_events co
					left join  #__jbusinessdirectory_company_event_pictures op on co.id=op.eventId and
					(op.id in (
							select  min(op1.id) as min from #__jbusinessdirectory_company_events co1
							left join  #__jbusinessdirectory_company_event_pictures op1 on co1.id=op1.eventId
							where op1.picture_enable=1
							group by co1.id
						)
					)
					left join  #__jbusinessdirectory_company_event_types et on co.type=et.id
					inner join #__jbusinessdirectory_companies cp on co.company_id = cp.id
					left join #__jbusinessdirectory_company_category cc on cp.id=cc.companyId
					left join #__jbusinessdirectory_categories cg on cg.id=cc.categoryId
					left join #__jbusinessdirectory_orders inv on inv.company_id=cp.id 
					left join #__jbusinessdirectory_packages p on (inv.package_id=p.id and p.status=1) or (p.price=0 and p.status=1)
					left join #__jbusinessdirectory_package_fields pf on p.id=pf.package_id
					where co.state=1 and co.approved !=-1  
					$whereDateCond $whereCompanyIdCond
					$whereCatCond $packageFilter $companyStatusFilter 
					$whereNameCond $whereTypeCond  $whereCityCond $whereRegionCond $featuredFilter
					and cp.state=1
					group by co.id
					$having
					order by featured desc, $orderBy
					";
		
		return $query;
	}
	
	
	function getEventsByCategories($searchDetails, $limitstart=0, $limit=0){
		$db =JFactory::getDBO();
	
		$query = $this->getEventsByCategoriesSql($searchDetails);
		//echo $query;

		$db->setQuery($query, $limitstart, $limit);
		//dump($this->_db->getErrorMsg());
		$result = $db->loadObjectList();
		
		return $result;
	}
	
	function getTotalEventsByCategories($searchDetails){
		$db =JFactory::getDBO();
		
		$query = $this->getEventsByCategoriesSql($searchDetails);
	
		//dump($query);
		$db->setQuery($query);
		$db->query();
		return $db->getNumRows();
	}

	function changeAprovalState($eventId, $state){
		$db =JFactory::getDBO();
		$query = " UPDATE #__jbusinessdirectory_company_events SET approved=$state WHERE id = ".$eventId ;
		$db->setQuery( $query );

		if (!$db->query()){
			return false;
		}
		return true;
	}


	function increaseViewCount($eventId){
		$db =JFactory::getDBO();
		$query = "update  #__jbusinessdirectory_company_events set view_count = view_count + 1 where id=$eventId";
		$db->setQuery($query);
		return $db->query();
	}

	function getEvent($eventId){
		$db =JFactory::getDBO();
		$query = "select e.*, et.name as eventType
				from #__jbusinessdirectory_company_events e
				left join  #__jbusinessdirectory_company_event_types et on e.type=et.id
				where e.id=".$eventId;
		$db->setQuery($query);
		//dump($query);
		return $db->loadObject();
	}

	function getEvents($filter, $limitstart=0, $limit=0){
		$db =JFactory::getDBO();
		$query = "select co.*,cp.name as companyName from #__jbusinessdirectory_company_events co
				  LEFT join  #__jbusinessdirectory_companies cp on cp.id=co.companyId
		$filter";
		// 		dump($query);
		$db->setQuery($query, $limitstart, $limit);
		return $db->loadObjectList();
	}

	function getTotalEvents(){
		$db =JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_company_events";
		$db->setQuery($query);
		$db->query();
		return $db->getNumRows();
	}

	function changeState($eventId){
		$db =JFactory::getDBO();
		$query = 	" UPDATE #__jbusinessdirectory_company_events SET state = IF(state, 0, 1) WHERE id = ".$eventId ;
		$db->setQuery( $query );

		if (!$db->query()){
			return false;
		}
		return true;
	}
	
	function changeStateFeatured($eventId){
		$db =JFactory::getDBO();
		$query = 	" UPDATE #__jbusinessdirectory_company_events SET featured = IF(featured, 0, 1) WHERE id = ".$eventId ;
		$db->setQuery( $query );
	
		if (!$db->query()){
			return false;
		}
		return true;
	}

	function getCompanyEvents($companyId, $limitstart=0, $limit=0){
		$db =JFactory::getDBO();
		$query = "select co.*, op.picture_path, et.name as eventType
					from #__jbusinessdirectory_company_events co
					left join  #__jbusinessdirectory_company_event_pictures op on co.id=op.eventId
					and (op.id in (
							select  min(op1.id) as min from #__jbusinessdirectory_company_events co1
							left join  #__jbusinessdirectory_company_event_pictures op1 on co1.id=op1.eventId
							where op1.picture_enable=1 and company_id=$companyId
							group by co1.id))
					left join  #__jbusinessdirectory_company_event_types et on co.type=et.id
					where co.state=1 and co.approved !=-1 and co.end_date>=DATE(now()) and company_id=$companyId 
					group by co.id	
					order by co.start_date";
			
		//echo($query);
		$db->setQuery($query, $limitstart, $limit);
		$result = $db->loadObjectList();
		//dump($result);
		//dump($this->_db->getErrorMsg());
		return $result;
	}

	function getUserEvents($companyIds, $limitstart=0, $limit=0){
		$db =JFactory::getDBO();
		$companyIds = implode(",", $companyIds);
		$query = "select co.*, cp.name as companyName, op.picture_path from #__jbusinessdirectory_company_events co
					left join  #__jbusinessdirectory_company_event_pictures op on co.id=op.eventId
					left join #__jbusinessdirectory_companies cp on cp.id = co.company_id
					where company_id in ($companyIds) and (1 or op.id in (
					select  min(op1.id) as min from #__jbusinessdirectory_company_events co1
					left join  #__jbusinessdirectory_company_event_pictures op1 on co1.id=op1.eventId
					where company_id in ($companyIds)
					group by co1.id))
					group by co.id	";

		//dump($query);
		$db->setQuery($query, $limitstart, $limit);
		return $db->loadObjectList();
	}

	function getTotalUserEvents($companyIds){
		$db =JFactory::getDBO();
		$companyIds = implode(",", $companyIds);
		$query = "select * from #__jbusinessdirectory_company_events where company_id in ($companyIds)";
		$db->setQuery($query);
		$db->query();
		return $db->getNumRows();
	}

	function getTotalCompanyEvents($companyId){
		$db =JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_company_events where company_id=$companyId";
		$db->setQuery($query);
		$db->query();
		return $db->getNumRows();
	}

	function getEventPictures($eventId){
		$db =JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_company_event_pictures where eventId=$eventId and picture_enable=1 order by id";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function getTotalNumberOfEvents(){
		$db =JFactory::getDBO();
		$query = "SELECT count(*) as nr FROM #__jbusinessdirectory_company_events";
		$db->setQuery($query);
		$result = $db->loadObject();

		return $result->nr;
	}

	function getTotalActiveEvents(){
		$db =JFactory::getDBO();
		$query = "SELECT count(*) as nr FROM #__jbusinessdirectory_company_events where state =1 and end_date>now()";
		$db->setQuery($query);
		$result = $db->loadObject();

		return $result->nr;
	}
	
	function getEventsForExport(){
		$db =JFactory::getDBO();
		$query = "select co.*, GROUP_CONCAT(op.picture_path) as pictures, et.name as eventType, c.name as company
					from #__jbusinessdirectory_company_events co
					left join  #__jbusinessdirectory_company_event_pictures op on co.id=op.eventId
					left join  #__jbusinessdirectory_company_event_types et on co.type=et.id
					left join  #__jbusinessdirectory_companies c on co.company_id=c.id
					group by co.id	
					order by co.id";
			
		$db->setQuery($query);
		$result = $db->loadObjectList();

		return $result;
	}
	
	function checkAlias($id, $alias){
		$db =JFactory::getDBO();
		$query = "SELECT count(*) as nr FROM #__jbusinessdirectory_company_events  WHERE alias='$alias' and id<>$id";
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result->nr;
	}
	
	function getNextEventsIds($id, $reccuring_id){
		$db =JFactory::getDBO();
		$query = "select id from #__jbusinessdirectory_company_events where id>=$id and recurring_id=$reccuring_id";
		$db->setQuery($query);
		$db->setQuery($query);
		$items = $db->loadObjectList();
		$result = array();
		
		foreach($items as $item){
			$result[] = $item->id;
		}
		
		return $result;
	}
	
	function getAllSeriesEventsIds($reccuring_id){
		$db =JFactory::getDBO();
		$query = "select id from #__jbusinessdirectory_company_events where recurring_id=$reccuring_id or id = $reccuring_id";
		$db->setQuery($query);
		$items = $db->loadObjectList();
		$result = array();
		
		foreach($items as $item){
			$result[] = $item->id;
		}
		
		return $result;
	}
	
	function deleteReccuringEvents($reccuring_id){
		$db =JFactory::getDBO();
		$query = "delete from #__jbusinessdirectory_company_events where recurring_id=$reccuring_id";
		$db->setQuery($query);
		return $db->query();
	}
	
	function getReccuringEvents($id, $reccuring_id){
		
	}
}