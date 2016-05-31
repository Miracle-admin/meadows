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

abstract class modJBusinessLatestHelper
{
	public static function getList($params){
		
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$enablePackage = $appSettings->enable_packages;
		// Get the database object.
		$db = JFactory::getDBO();
		
		$whereCatCond = '';
		$categoriesIds = $params->get('categoryIds');
		if(isset($categoriesIds) && count($categoriesIds)>0 && $categoriesIds[0]!=""){
			$categoriesIDs = implode(",",$params->get('categoryIds'));
			$whereCatCond = " and cc.categoryId in ($categoriesIDs)";	
		}
		
		$companyStatusFilter="and cp.approved = ".COMPANY_STATUS_APPROVED;
		if($appSettings->show_pending_approval){
			$companyStatusFilter = "and (cp.approved = ".COMPANY_STATUS_APPROVED." or cp.approved= ".COMPANY_STATUS_CREATED.") ";
		}
		
		$packageFilter = '';
		if($appSettings->enable_packages){
			$packageFilter = " and (((inv.state= ".PAYMENT_STATUS_PAID." and now() > (inv.start_date) and (now() < (inv.start_date + INTERVAL p.days DAY) or (inv.package_id=p.id and p.days = 0)))) or p.price=0) ";
		}
		
		$onlyFeatured = $params->get('count');
		
		$featuredFilter="";
		$featured  = $params->get('only_featured');
		if($featured){
			$featuredFilter = " having featured  = 1";
		}
		
		$city = $params->get('city');
		//dump($city);
		$whereCityCond='';
		if(!empty($city)){
			$whereCityCond=" and cp.city = '".$db->escape($city)."' ";
		}
		
		$orderBy = " rand() ";
		$ordering  = $params->get('order');
		if($ordering){
			$orderBy ="slec.creationDate desc";
		}
		$nrResults = $params->get('count');
		
		//dump($whereCatCond);
		$query = "select slec.*, mainCategory, mainCategoryId, companyName, companyId1, count(cra.id) as nrRatings, features, GREATEST(if(FIND_IN_SET('featured_companies',features) ,1,0), featured) as featured
				from (
				select  cp.id, cp.name, cp.alias, cp.short_description, cp.description, cp.street_number, cp.address, cp.city, cp.county, cp.website, cp.phone, cp.email, cp.state, cp.fax,
				cp.averageRating, cp.slogan, cp.logoLocation, cp.creationDate,
				cp.featured,cp.publish_only_city, cp.name as companyName,cp.id as companyId1, cn.country_name as countryName,ct.name as typeName, cnt.contact_name,
				GROUP_CONCAT( DISTINCT cg.id) as categoryIds, GROUP_CONCAT(DISTINCT cg.name separator '#') as categoryNames,
				GROUP_CONCAT(DISTINCT pf.feature) as features,
				bc.name as mainCategory,  bc.id as mainCategoryId
				from #__jbusinessdirectory_companies cp
				left join #__jbusinessdirectory_company_category cc on cp.id=cc.companyId
				left join #__jbusinessdirectory_categories cg on cg.id=cc.categoryId
				left join #__jbusinessdirectory_categories bc on bc.id=cp.mainSubcategory
				left join #__jbusinessdirectory_company_contact cnt on cp.id=cnt.companyId
				left join #__jbusinessdirectory_countries cn on cp.countryId=cn.id
				left join #__jbusinessdirectory_company_types ct on cp.typeId=ct.id
				left join #__jbusinessdirectory_orders inv on inv.company_id=cp.id
				left join #__jbusinessdirectory_packages p on (inv.package_id=p.id and p.status=1 and $enablePackage) or (p.price=0 and p.status=1 and $enablePackage)
				left join #__jbusinessdirectory_package_fields pf on p.id=pf.package_id
				where 1  $whereCatCond $packageFilter  and cp.state=1 $companyStatusFilter $whereCityCond
				group by cp.id order by cp.name) as slec
				left join #__jbusinessdirectory_company_ratings cra on cra.companyId=companyId1
				group by companyId1 
				$featuredFilter
				order by $orderBy ";

		//echo($query);
		// Set the query and get the result list.
		$db->setQuery($query, 0, $nrResults);
		$items = $db->loadObjectlist();
		
		//dump($db->getErrorMsg());
		return $items;
	}
}
?>
