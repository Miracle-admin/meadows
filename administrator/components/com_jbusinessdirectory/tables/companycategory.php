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

class JTableCompanyCategory extends JTable
{

	var $companyId			= null;	
	var $categoryId			= null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db){
		parent::__construct('#__jbusinessdirectory_company_category', 'id', $db);
	}

	function setKey($k)
	{
		$this->_tbl_key = $k;
	}

	function insertRelations($companyId, $categoryIds){
		
		if(empty($categoryIds))
			return;
		$db =JFactory::getDBO();
		$query = "insert into #__jbusinessdirectory_company_category(companyId, categoryId) values ";
		foreach ($categoryIds as $categoryId){
			$query = $query."(".$companyId.",".$categoryId."),";
		}
		$query =substr($query, 0, -1);
		$query = $query." ON DUPLICATE KEY UPDATE companyId=values(companyId), categoryId=values(categoryId) ";
		
		$db->setQuery($query);	
		
		if (!$db->query() )
		{
			echo 'INSERT / UPDATE sql STATEMENT error !';
			return false;
		}
		
		$filter ="(";
		foreach ($categoryIds as $categoryId){
			$filter = $filter.$categoryId.",";
		}
		$filter =substr($filter, 0, -1);
		$filter = $filter.")";
		$query = "delete from #__jbusinessdirectory_company_category where companyId =$companyId and categoryId not in $filter ";
		$db->setQuery($query);
		if (!$db->query() )
		{
			echo 'INSERT / UPDATE sql STATEMENT error !';
			return false;
		}
		
		return true;
	}
	
	function getSelectedCategories($companyId){
		$db =JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_company_category  cc inner join #__jbusinessdirectory_categories c  on cc.categoryId=c.id  where companyId=".$companyId;
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	function getSelectedCategoriesList($companyId){
		$db =JFactory::getDBO();
		$query = "select categoryId from #__jbusinessdirectory_company_category  cc inner join #__jbusinessdirectory_categories c  on cc.categoryId=c.id  where companyId=".$companyId;
		$db->setQuery($query);
		$list = $db->loadObjectList();
		$result = array();
		foreach($list as $item){
			$result[]=$item->categoryId;
		}
		
		return $result;
	}
	
	
	function getSelectedOfferCategories($offerId){
		$db =JFactory::getDBO();
		$query = "select categoryId from #__jbusinessdirectory_company_offer_category  cc inner join #__jbusinessdirectory_categories c  on cc.categoryId=c.id  where offerId=".$offerId;$db->setQuery($query);
		$list = $db->loadObjectList();
		$result = array();
		foreach($list as $item){
			$result[]=$item->categoryId;
		}
		
		return $result;
	}
	
	function insertOfferRelations($offerId, $categoryIds){
		$db =JFactory::getDBO();
		
		if(empty($categoryIds)){
			
			$query = "delete from #__jbusinessdirectory_company_offer_category where offerId =$offerId ";
			$db->setQuery($query);
			if (!$db->query() )
			{
				echo 'INSERT / UPDATE sql STATEMENT error !';
				return false;
			}
			
			return;
		}
			
		$db =JFactory::getDBO();
		$query = "insert into #__jbusinessdirectory_company_offer_category(offerId, categoryId) values ";
		foreach ($categoryIds as $categoryId){
			$query = $query."(".$offerId.",".$categoryId."),";
		}
		$query =substr($query, 0, -1);
		$query = $query." ON DUPLICATE KEY UPDATE offerId=values(offerId), categoryId=values(categoryId) ";
	
		$db->setQuery($query);
	
		if (!$db->query() )
		{
			echo 'INSERT / UPDATE sql STATEMENT error !';
			return false;
		}
	
		$filter ="(";
		foreach ($categoryIds as $categoryId){
			$filter = $filter.$categoryId.",";
		}
		$filter =substr($filter, 0, -1);
		$filter = $filter.")";
		$query = "delete from #__jbusinessdirectory_company_offer_category where offerId =$offerId and categoryId not in $filter ";
		$db->setQuery($query);
		if (!$db->query() )
		{
			echo 'INSERT / UPDATE sql STATEMENT error !';
			return false;
		}
	
		return true;
	}
}