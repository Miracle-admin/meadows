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

class JTableReview extends JTable
{

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db){

		parent::__construct('#__jbusinessdirectory_company_reviews', 'id', $db);
	}

	function setKey($k)
	{
		$this->_tbl_key = $k;
	}


	function changeState($reviewId){
		$db =JFactory::getDBO();
		$query = 	" UPDATE #__jbusinessdirectory_company_reviews SET state = IF(state, 0, 1) WHERE id = ".$reviewId ;
		$db->setQuery( $query );
		if (!$db->query()){
			return false;
		}
		return true;
	}
	
	function getReview($reviewId){
		$db =JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_company_reviews where id=".$reviewId." order by creationDate desc";
		$db->setQuery($query);
		//dump($query);
		return $db->loadObjectList();
	}
	
	
	
	function getCompanyReviews($companyId){
		$db =JFactory::getDBO();
		$query = "select cr.*, GROUP_CONCAT(ruc.score) as scores, GROUP_CONCAT(rc.name) as criteria_names 
				  from #__jbusinessdirectory_company_reviews cr
				  left join #__jbusinessdirectory_company_reviews_user_criteria ruc on cr.id= ruc.review_id
				  left join #__jbusinessdirectory_company_reviews_criteria rc on rc.id=ruc.criteria_id 
				  where cr.companyId=".$companyId." and state=1 
				  group by cr.id
			      order by creationDate desc";
		$db->setQuery($query);
		//dump($query);
		return $db->loadObjectList();
	}
	
	function getCompanyReviewResponse($reviewId){
		$db =JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_company_review_responses  where reviewId=$reviewId and state=1 order by id";
		$db->setQuery($query);
		//dump($query);
		return $db->loadObjectList();
	}
	
	function getUserCompanyReviews($userId, $companyId){
		$db =JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_company_reviews where companyId=".$companyId." and userId=".$userId." order by creationDate desc";
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	function getAllReviews(){
		$db =JFactory::getDBO();
		$query = "select cr.*,  c.name as companyName from #__jbusinessdirectory_company_reviews  cr
		inner join #__jbusinessdirectory_companies c on cr.companyId= c.id order by cr.creationDate desc";
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	function getAllActiveReviews(){
		$db =JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_company_reviews where state=1 order by creationDate desc";
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	function saveReview($data){
		$db =JFactory::getDBO();
		$query = "insert into #__jbusinessdirectory_company_reviews(name,subject,description,companyId, userId,ipAddress,rating) values ";
		$query = $query."('".$db->escape($data['name'])."','".$db->escape($data['subject'])."','".$db->escape($data['description'])."',".$db->escape($data['companyId']).",".$db->escape($data['userId']).",'".$data['ipAddress']."',".$db->escape($data['rating']).")";
		$db->setQuery($query);
	
		if (!$db->query() )
		{
			echo 'INSERT / UPDATE sql STATEMENT error !';
			return false;
		}
		return true;
	}
	
	function updateCompanyReview($companyId){
		$db =JFactory::getDBO();
		$query = "update #__jbusinessdirectory_companies set review_score=(select avg(rating) from #__jbusinessdirectory_company_reviews where companyId=".$companyId.") where id=".$companyId;
		$db->setQuery($query);
		if (!$db->query() )
		{
			echo 'INSERT / UPDATE sql STATEMENT error !';
			return false;
		}
		return true;
	}
	
	function getNumberOfReviews($companyId){
		$db =JFactory::getDBO();
		$query = "select count(*) as nrRatings from #__jbusinessdirectory_company_ratings where companyId=".$companyId;
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result->nrRatings;
	}
	
	function changeAprovalState($reviewId, $state){
		$db =JFactory::getDBO();
		$query = " UPDATE #__jbusinessdirectory_company_reviews SET approved=$state WHERE id = ".$reviewId ;
		$db->setQuery( $query );
		if (!$db->query()){
			return false;
		}
		return true;
	}
	
	function increaseReviewLike($reviewId){
		$db =JFactory::getDBO();
		$query = " UPDATE #__jbusinessdirectory_company_reviews SET likeCount=likeCount+1 WHERE id = ".$reviewId ;
	
		$db->setQuery( $query );
		if (!$db->query()){
			return false;
		}
		return true;
	}
	
	function increaseReviewDislike($reviewId){
		$db =JFactory::getDBO();
		$query = " UPDATE #__jbusinessdirectory_company_reviews SET dislikeCount=dislikeCount+1 WHERE id = ".$reviewId ;
		$db->setQuery( $query );
		if (!$db->query()){
			return false;
		}
		return true;
	}
	
	function deleteReview($reviewId){
		$db =JFactory::getDBO();
		$query = " delete from #__jbusinessdirectory_company_reviews WHERE id = ".$reviewId ;
	
		$db->setQuery( $query );
		if (!$db->query()){
			return false;
		}
		return true;
	}
}