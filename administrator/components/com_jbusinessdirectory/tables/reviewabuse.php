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

class JTableReviewabuse extends JTable {

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db){

		parent::__construct('#__jbusinessdirectory_company_review_abuses', 'id', $db);
	}

	function setKey($k) {
		$this->_tbl_key = $k;
	}

	function changeState($reviewabuseId) {
		$db = JFactory::getDBO();
		$query = "UPDATE #__jbusinessdirectory_company_review_abuses SET state = IF(state, 0, 1) WHERE id =".$reviewabuseId ;
		$db->setQuery( $query );
		if (!$db->query()){
			return false;
		}
		return true;
	}

	function getAllReviewabuses() {
		$db = JFactory::getDBO();
		$query = "select ra.*,  r.subject as subject from #__jbusinessdirectory_company_review_abuses ra
		inner join #__jbusinessdirectory_company_review r on ra.reviewId = r.id order by ra.id desc";
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	function getAllActiveReviewabuses() {
		$db = JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_company_review_abuses where state=1 order by id desc";
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	function getNumberOfReviewabuses($reviewId) {
		$db = JFactory::getDBO();
		$query = "select count(*) as nrReviewabuses from #__jbusinessdirectory_company_review_abuses where reviewId=".$reviewId;
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result->nrReviewabuses;
	}

	function deleteReviewabuse($reviewabuseId) {
		$db = JFactory::getDBO();
		$query = "delete from #__jbusinessdirectory_company_review_abuses WHERE id = ".$reviewabuseId ;
		$db->setQuery( $query );
		if (!$db->query()) {
			return false;
		}
		return true;
	}
}