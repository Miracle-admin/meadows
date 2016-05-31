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

class JTableReviewresponse extends JTable {

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db){

		parent::__construct('#__jbusinessdirectory_company_review_responses', 'id', $db);
	}

	function setKey($k) {
		$this->_tbl_key = $k;
	}

	function changeState($reviewresponseId) {
		$db = JFactory::getDBO();
		$query = "UPDATE #__jbusinessdirectory_company_review_responses SET state = IF(state, 0, 1) WHERE id =".$reviewresponseId ;
		$db->setQuery( $query );
		if (!$db->query()){
			return false;
		}
		return true;
	}

	function getAllReviewresponses() {
		$db = JFactory::getDBO();
		$query = "select rr.*,  r.subject as subject from #__jbusinessdirectory_company_review_responses rr
		inner join #__jbusinessdirectory_company_review r on rr.reviewId = r.id order by rr.id desc";
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	function getAllActiveReviewresponses() {
		$db = JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_company_review_responses where state=1 order by id desc";
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	function getNumberOfReviewresponses($reviewId) {
		$db = JFactory::getDBO();
		$query = "select count(*) as nrReviewresponses from #__jbusinessdirectory_company_review_responses where reviewId=".$reviewId;
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result->nrRatings;
	}

	function deleteReviewresponse($reviewresponseId) {
		$db = JFactory::getDBO();
		$query = "delete from #__jbusinessdirectory_company_review_responses WHERE id = ".$reviewresponseId ;
		$db->setQuery( $query );
		if (!$db->query()) {
			return false;
		}
		return true;
	}
}