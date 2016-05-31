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

class JTableRating extends JTable {

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db){

		parent::__construct('#__jbusinessdirectory_company_ratings', 'id', $db);
	}

	function setKey($k) {
		$this->_tbl_key = $k;
	}
	
	function getRating($ratingId){
		$db =JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_company_ratings where id=".$ratingId;
		$db->setQuery($query);
		return $db->loadObject();
	}

	function saveRating($data){
		$db =JFactory::getDBO();
		$query = "insert into #__jbusinessdirectory_company_ratings(id,companyId,rating,ipAddress) values ";
		$query = $query."(".$db->escape($data['ratingId']).",".$db->escape($data['companyId']).",".$db->escape($data['rating']).",'".$db->escape($data['ipAddress'])."') ";
		$query = $query." ON DUPLICATE KEY UPDATE rating= values(rating)";
		//dump($query);
		//exit();
		$db->setQuery($query);
		if (!$db->query() )
		{
			echo 'INSERT / UPDATE sql STATEMENT error !';
			exit();
			return false;
		}
		return $db->insertid();
	}
	function getAllRatings() {
		$db = JFactory::getDBO();
		$query = "select cr.*, c.name as name from #__jbusinessdirectory_company_ratings cr
		inner join #__jbusinessdirectory_companies c on cr.companyId = c.id order by cr.id desc";
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	function getNumberOfRatings($companyId) {
		$db = JFactory::getDBO();
		$query = "select count(*) as nrRatings from #__jbusinessdirectory_company_ratings where companyId=".$companyId;
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result->nrRatings;
	}

	function deleteRating($ratingId) {
		$db = JFactory::getDBO();
		$query = "delete from #__jbusinessdirectory_company_ratings WHERE id = ".$ratingId ;
		$db->setQuery( $query );
		if (!$db->query()) {
			return false;
		}
		return true;
	}
	
	function updateCompanyRating($companyId){
		$db =JFactory::getDBO();
		$query = "update #__jbusinessdirectory_companies set averageRating=(select avg(rating) from #__jbusinessdirectory_company_ratings where companyId=".$companyId.") where id=".$companyId;
		$db->setQuery($query);
		if (!$db->query() )
		{
			echo 'INSERT / UPDATE sql STATEMENT error !';
			return false;
		}
		return true;
	}
}