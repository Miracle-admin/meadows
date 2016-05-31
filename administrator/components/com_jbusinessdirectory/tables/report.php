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

class JTableReport extends JTable
{

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db){

		parent::__construct('#__jbusinessdirectory_reports', 'id', $db);
	}

	function setKey($k)
	{
		$this->_tbl_key = $k;
	}


	function getReport($reportId){
		$db =JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_reports where id=".$reportId;
		$db->setQuery($query);
		//dump($query);
		return $db->loadObject();
	}
	
	function getReportData($reportColumns, $orderBy){
		
		$db =JFactory::getDBO();
		$query = "select $reportColumns, ctm.* from (
					select cp.*, cn.country_name as countryName, u.name as userName, ct.name as type, p.name as package ,
					cnt.contact_name, cnt.contact_email, cnt.contact_phone, cnt.contact_fax
				    from #__jbusinessdirectory_companies as cp
					left join #__jbusinessdirectory_company_category cc on cp.id=cc.companyId 
					left join #__jbusinessdirectory_categories cg on cg.id=cc.categoryId 
					left join #__jbusinessdirectory_categories bc on bc.id=cp.mainSubcategory
					left join #__jbusinessdirectory_company_contact cnt on cp.id=cnt.companyId 
					left join #__jbusinessdirectory_countries cn on cp.countryId=cn.id 
					left join #__jbusinessdirectory_company_types ct on cp.typeId=ct.id 
					left join #__jbusinessdirectory_packages p on p.id=cp.package_id
					left join #__users as u on u.id = cp.userId
					group by cp.id 
				    order by $orderBy
				) as jb
		 		left join
				 (
		 		  select 
		 		  GROUP_CONCAT( a.name,'||', a.code,'||', at.code,'||', ca.value   separator '#') as custom_attributes, ca.company_id
		 		  from #__jbusinessdirectory_attributes a 
		          left join #__jbusinessdirectory_attribute_types AS at on at.id=a.type
		          left join #__jbusinessdirectory_company_attributes AS ca on ca.attribute_id = a.id 
		          group by ca.company_id
				) as ctm on jb.id = ctm.company_id
		";
		
		$db->setQuery($query);
		$reportData = $db->loadObjectList();
		return $reportData;
	}

	function getReports(){
		$db =JFactory::getDBO();
		$query = "select p.*
					from #__jbusinessdirectory_reports p
					group by p.id
					order by p.name asc";

		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function insertRelations($reportId, $features){
		$db =JFactory::getDBO();
		
		$query = "delete from #__jbusinessdirectory_report_fields where report_id = $reportId";
		$db->setQuery($query);
		if (!$db->query() )
		{
			echo 'INSERT / UPDATE sql STATEMENT error !';
			return false;
		}
			
		$query = "insert into #__jbusinessdirectory_report_fields(report_id, feature) values ";
		foreach ($features as $feature){
			$query = $query."(".$reportId.",'".$db->escape($feature)."'),";
		}
		$query =substr($query, 0, -1);
		$query = $query." ON DUPLICATE KEY UPDATE report_id=values(report_id), feature=values(feature) ";
	
		$db->setQuery($query);
		if (!$db->query() )
		{
			echo 'INSERT / UPDATE sql STATEMENT error !';
			return false;
		}
	}
	
	

}