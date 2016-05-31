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


JTable::addIncludePath(DS.'components'.DS.JRequest::getVar('option').DS.'tables');

class JBusinessDirectoryModelCompanies extends JModelLegacy
{ 
	function __construct(){
		$this->appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		parent::__construct();
	}

	function getCompany($cmpId=null){
		$companiesTable = $this->getTable("Company");
		$companyId = JRequest::getVar('companyId');
		if(isset($cmpId))
			 $companyId = $cmpId;
		if(empty($companyId))
			return;
		
		$company = $companiesTable->getCompany($companyId);
		if(!empty($company->business_hours)){
			$company->business_hours = explode(",",$company->business_hours);
		}
		
		$categoryTable = $this->getTable("Category","JBusinessTable");
		
		$category = null;
		if(!empty($company->mainSubcategory)){
			$category = $categoryTable->getCategoryById($company->mainSubcategory);
		}else{
			if(!empty($company->categoryIds)){
				$categoryIds = explode(",",$company->categoryIds);
				$category = $categoryTable->getCategoryById($categoryIds[0]);
			}
		}
		
		$path=array();
		if(!empty($category)){
			$path[]=$category;
	
			while($category->parent_id != 1){
				if(!$category->parent_id)
					break;
				$category= $categoryTable->getCategoryById($category->parent_id);
				$path[] = $category;
			}
			
			$path = array_reverse($path);
			
			$company->path=$path;
		}
		
		$companyLocationsTable = $this->getTable('CompanyLocations');
		$company->locations = $companyLocationsTable->getCompanyLocations($companyId);
		
		if($this->appSettings->enable_multilingual){
			JBusinessDirectoryTranslations::updateEntityTranslation($company, BUSSINESS_DESCRIPTION_TRANSLATION);
			JBusinessDirectoryTranslations::updateCategoriesTranslation($company->path);
		}
		
		$userId = JFactory::getUser()->id;
		$company->isBookmarked = false;
		if(!empty($userId)){
			$bookmarkTable = $this->getTable('Bookmark');
			$company->bookmark = $bookmarkTable->getBookmark($companyId, $userId);
		}
		
		$company->attachments = JBusinessDirectoryAttachments::getAttachments(BUSSINESS_ATTACHMENTS, $companyId, true);
		
		return $company;
	}
	
	function getPlainCompany($companyId){
		$companiesTable = $this->getTable("Company");
		$company = $companiesTable->getCompany($companyId);
		return $company;
	}

	function getUserRating(){
		$companyId = JRequest::getVar('companyId');
		//dump($_COOKIE['companyRatingIds']);
		$companyRatingIds=array();
		if(isset($_COOKIE['companyRatingIds']))
			$companyRatingIds = explode("#",$_COOKIE['companyRatingIds']);
			
		//dump($companyRatingIds);
		$ratingId =0;
		foreach($companyRatingIds as $companyRatingId){
			$temp = explode(",",$companyRatingId);
			if(strcmp($temp[0],$companyId)==0)
				$ratingId = $temp[1];
		}
		
		$ratingTable = $this->getTable("Rating");
		$rating = $ratingTable->getRating($ratingId);
		//dump($rating);
		
		//exit;
		return $rating;
	}
	
	/**
	 * Returns a Table object, always creating it
	 *
	 * @param   type	The table type to instantiate
	 * @param   string	A prefix for the table class name. Optional.
	 * @param   array  Configuration array for model. Optional.
	 * @return  JTable	A database object
	 */
	public function getTable($type = 'Companies', $prefix = 'JTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	
	function getReviews(){
		$reviewsTable = $this->getTable("Review");
		$reviews = $reviewsTable->getCompanyReviews(JRequest::getVar('companyId'));
		
		if(count($reviews)){
			foreach($reviews as $review){
				$review->responses =  $reviewsTable->getCompanyReviewResponse($review->id);
				if(isset($review->scores)){
					$review->scores = explode(",",$review->scores);
				}
				if(isset($review->criteria_names)){
					$review->criteriaNames = explode(",",$review->criteria_names);
				}
				//dump($review->responses);
			}
		}
		//dump($reviews);
		return $reviews;
	}
	
	function getReviewCriterias(){
		$reviewsCriteriaTable = $this->getTable("ReviewCriteria");
		$criterias = $reviewsCriteriaTable->getCriterias();
		
		return $criterias;
	}
	
	function getCompanyImages(){
		$query = "SELECT *
				FROM #__jbusinessdirectory_company_pictures
				WHERE picture_enable =1 and companyId =".JRequest::getVar('companyId') ."
				ORDER BY id ";

		$pictures =  $this->_getList( $query );
		
		return $pictures;
	}
	
	function getCompanyVideos(){
		$table = $this->getTable("companyvideos");
		return $table->getCompanyVideos(JRequest::getVar('companyId'));
	}
	
	function getCompanyAttributes(){
		$attributesTable = $this->getTable('CompanyAttributes');
		return  $attributesTable->getCompanyAttributes(JRequest::getVar('companyId'));
	}
	
	function getCompanyOffers(){
		$table = $this->getTable("Offer");
		$offers =  $table->getCompanyOffers(JRequest::getVar('companyId'));
		if(!empty($offers)){
			JBusinessDirectoryTranslations::updateOffersTranslation($offers);
			foreach($offers as $offer){
				switch($offer->view_type){
					case 1:
						$offer->link = JBusinessUtil::getofferLink($offer->id, $offer->alias);
						break;
					case 2:
						$offer->link = JRoute::_('index.php?option=com_content&view=article&id='.$offer->article_id);
						break;
					case 3:
						$offer->link = $offer->url;
						break;
					default:
						$offer->link = JBusinessUtil::getofferLink($offer->id, $offer->alias);
				}
			}
		}
		return $offers;
	}
	
	function getCompanyEvents(){
		$table = $this->getTable("Event");
		$events = $table->getCompanyEvents(JRequest::getVar('companyId'));
		if(!empty($events) && $this->appSettings->enable_multilingual){
			JBusinessDirectoryTranslations::updateEventsTranslation($events);
		}
		return $events;
	}
	
	function getPackage(){
		$table = $this->getTable("Package"); 
		$package = $table->getCurrentActivePackage(JRequest::getVar('companyId'));
		
		return $package;
	}
	
	
	function claimCompany($data){
		$companiesTable = $this->getTable("Company");
		$companyId = JRequest::getVar('companyId');
		
		if($companiesTable->claimCompany($data)){
			return $this->updateCompanyOwner($data['companyId'], $data['userId']);
		}
		exit;
		return false;
	}
	
	
	function saveReview($data){
		$criterias = array();
		foreach($data as $key=>$value){
			if(strpos($key, "criteria")===0){
				$key = str_replace("criteria-", "", $key);
				$criterias[$key]=$value;
			}
		}
		
		$rating = 0;
		if(isset($data["review"])){
			$rating = $data["review"];
		}
		if(!empty($criterias)){
			$score = 0;
			foreach($criterias as $key=>$value){
				$score += $value;
			}
			$rating = $score/count($criterias);
			$data["rating"] = number_format($rating,2); 
		}
		
		$table = $this->getTable("Review");
		
		// Bind the data.
		if (!$table->bind($data))
		{
			$this->setError($table->getError());
		}
		
		// Check the data.
		if (!$table->check())
		{
			$this->setError($table->getError());
		}
		
		// Store the data.
		if (!$table->store())
		{
			$this->setError($table->getError());
		}
		$table->updateCompanyReview($data['companyId']);
		
		$reviewId = $table->id;

		foreach($criterias as $key=>$score){
			$table = $this->getTable("ReviewUserCriteria");
			
			$criteriaObj = array();
			$criteriaObj["review_id"]= $reviewId;
			$criteriaObj["criteria_id"]= $key;
			$criteriaObj["score"]= $score;
			// Bind the data.
			if (!$table->bind($criteriaObj))
			{
				$this->setError($table->getError());
			}
			
			// Check the data.
			if (!$table->check())
			{
				$this->setError($table->getError());
			}
			
			// Store the data.
			if (!$table->store())
			{
				$this->setError($table->getError());
			}
		}
		
		//$table->saveReview($data);
		
		$this->sendReviewEmail($data['companyId'],"Review Email");

		return true;
	}
	
	function saveRating($data){
		$table = $this->getTable("Rating");
		$ratingId = $table->saveRating($data);
		$table->updateCompanyRating($data['companyId']);
		
		return $ratingId;
	}
	
	function getRatingsCount(){
		$companyId = JRequest::getVar('companyId');
		$table = $this->getTable("Rating");
		return $table->getNumberOfRatings($companyId);
	}
	
	function reportAbuse($data){
		
		$data["state"]=1;
		$row = $this->getTable("reviewabuses");
		
		// Bind the form fields to the table
		if (!$row->bind($data))
		{
				
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		// Make sure the record is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		// Store the web link table to the database
		if (!$row->store()) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		$this->sendReportAbuseEmail($data);
		
		return true;
	}
	
	function saveReviewResponse($data){
		
		//save in banners table
		$row = $this->getTable("reviewresponses");
		$data["state"]=1;
	
		// Bind the form fields to the table
		if (!$row->bind($data))
		{
			dump($this->_db->getErrorMsg());
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		// Make sure the record is valid
		if (!$row->check()) {
			dump($this->_db->getErrorMsg());
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
	
		// Store the web link table to the database
		if (!$row->store()) {
			dump($this->_db->getErrorMsg());
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		$this->sendReviewEmail($data['companyId'],"Review Response Email");
		
		return true;
	}
	
	function increaseReviewLikeCount($reviewId){
		$table = $this->getTable("Review");
		return $table->increaseReviewLike($reviewId);
	}
	
	function increaseReviewDislikeCount($reviewId){
		$table = $this->getTable("Review");
		return $table->increaseReviewDislike($reviewId);
	}
	
	function increaseViewCount(){
		$companiesTable = $this->getTable("Company");
		return $companiesTable->increaseViewCount(JRequest::getVar('companyId'));
	}
	
	function getViewCount(){
		return $this->increaseViewCount();
	}
	
	function updateCompanyOwner($companyId, $userId){
		$companiesTable = $this->getTable("Company");
		return $companiesTable->updateCompanyOwner($companyId, $userId);
	}
	
	function getUserCompanies(){
		$user = JFactory::getUser();
		if($user->id == 0 ){
			return null;
		}
		$companiesTable = $this->getTable("Company");
		$companies = $companiesTable->getCompaniesByUserId($user->id);
		
		return $companies;
	}
	
	function getCompanyByName($companyName){
		$companiesTable = $this->getTable("Company");
		return $companiesTable->getCompanyByName($companyName);
	}
	
	function contactCompany($data){
		$company = $this->getTable("Company");
		$company->load($data['companyId']);
		
		$templ = $this->getEmailTemplate("Contact Email");
		if( $templ ==null )
			return null;
		
		$content = $this->prepareEmail($company, $templ->email_content, $data);
		$content = EmailService::updateCompanyDetails($content);
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();

		$company->increaseContactsNumber($data['companyId']);
		$subject=sprintf($templ->email_subject, $applicationSettings->company_name); 
		$sender = $data["firstName"]." ".$data["lastName"];
		jimport('joomla.mail.mail');
		$mail = JFactory::getMailer();
		$mail->setSender(array($applicationSettings->company_email, $sender));
		$mail->addRecipient($company->email);
		$mail->setSubject($subject);
		$mail->setBody($content);
		//$mail->addBCC($applicationSettings->company_email);
		$mail->IsHTML(true);
		$ret = $mail->send();

		return $ret;
	}
	
	function addBookmark($data){
		//save in banners table
		$row = $this->getTable("Bookmark");
		
		// Bind the form fields to the table
		if (!$row->bind($data))
		{
			dump($this->_db->getErrorMsg());
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		// Make sure the record is valid
		if (!$row->check()) {
			dump($this->_db->getErrorMsg());
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		// Store the web link table to the database
		if (!$row->store()) {
			dump($this->_db->getErrorMsg());
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		return true;
	}
	
	function updateBookmark($data){
		//save in banners table
		$row = $this->getTable("Bookmark");
	
		// Bind the form fields to the table
		if (!$row->bind($data))
		{
			dump($this->_db->getErrorMsg());
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		// Make sure the record is valid
		if (!$row->check()) {
			dump($this->_db->getErrorMsg());
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
	
		// Store the web link table to the database
		if (!$row->store()) {
			dump($this->_db->getErrorMsg());
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
	
		return true;
	}
	
	function removeBookmark($data){
		$row = $this->getTable("Bookmark");
		return $row->delete($data["id"]);
	}
	
	
	function requestQuoteCompany($data){
		$company = $this->getTable("Company");
		$company->load($data['companyId']);
	
		$templ = $this->getEmailTemplate("Request Quote Email");
		if( $templ ==null )
			return null;
	
		$content = $this->prepareEmail($company, $templ->email_content, $data);
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		$subject=sprintf($templ->email_subject, $applicationSettings->company_name);
		$sender = $data["firstName"]." ".$data["lastName"];
		jimport('joomla.mail.mail');
		$mail = JFactory::getMailer();
		$mail->setSender(array($applicationSettings->company_email, $sender));
		$mail->addRecipient($company->email);
		$mail->setSubject($subject);
		$mail->setBody($content);
		$mail->addBCC($applicationSettings->company_email);
		$mail->IsHTML(true);
		$ret = $mail->send();
	
		return $ret;
	}
	
	
	function checkBusinessAboutToExpire(){
		$companyTable = $this->getTable("Company");
		$orderTable = $this->getTable("Order");
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$nrDays = $appSettings->expiration_day_notice;
		$companies = $companyTable->getBusinessAboutToExpire($nrDays);
		foreach($companies as $company){
			echo "sending expiration e-mail to: ".$company->name;
			$result = $this->sendExpirationEmail($company, "Expiration Notification Email", $nrDays);
			if($result){
				$orderTable->updateExpirationEmailDate($company->orderId);
			}
		}
		exit;
	}
	
	function increaseWebsiteCount($companyId){
		
		$company = $this->getCompany();
		
		$companiesTable = $this->getTable("Company");
		$companiesTable->increaseWebsiteCount($company->id);
		
		return $company;
	}
	
	function sendClaimEmail(){
		$email = new stdClass();
	
		$company=$this->getCompany($companyId);
		if(!isset($company->email))
			return;
	
		$email->content = JText::_("LNG_CLAIM_EMAIL_TXT");
		$email->content = str_replace(EMAIL_COMPANY_NAME, $company->name, $email->content);
	
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$email->subject = JText::_("LNG_CLAIM_EMAIL_SUBJECT");
		$email->subject = str_replace(EMAIL_COMPANY_NAME, $company->name, $email->subject);
		
		$email->to =$applicationSettings->company_email;
		$email->company_email = $applicationSettings->company_email;
		$email->company_name = $applicationSettings->company_name;
	
		return $this->sendEMail($email);
	}
	
	function sendExpirationEmail($company, $template, $nrDays){
		$email = new stdClass();
		$templ = $this->getEmailTemplate($template );
		if( $templ ==null )
			return null;
	
	
		if(!isset($company->email))
			return;
	
		$data = array("nrDays"=>$nrDays);
		$email->content = $this->prepareEmail($company, $templ->email_content, $data);
		
		$email->subject = $templ->email_subject;
		$email->to = $company->email;
		
		echo $email->content;
		return $this->sendEMail($email);;
	}
	
	function sendReviewEmail($companyId, $template){
		$email = new stdClass();
		$templ = $this->getEmailTemplate($template );
		if( $templ ==null )
			return null;
	
		$company=$this->getCompany($companyId);
	
		if(!isset($company->email))
			return;
	
		$data = array();
		$email->content = $this->prepareEmail($company, $templ->email_content, $data);
		$email->subject = $templ->email_subject;
		$email->to = $company->email;
		
		$this->sendEMail($email);
	
		return $email;
	}
	
	function sendReportAbuseEmail($data){
		$email = new stdClass();
		$templ = $this->getEmailTemplate("Report Abuse Email");
		
		if( $templ ==null )
			return null;
	
		$company=$this->getCompany($data["companyId"]);
	
		if(!isset($company->email))
			return;
	
		$reviewsTable = $this->getTable("Review");
		$review = $reviewsTable->getReview($data["reviewId"]);
		if(isset($review)){
			$data["reviewName"]= $review[0]->subject;
		}
		
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$email->content = $this->prepareEmail($company, $templ->email_content, $data);
		$email->subject = $templ->email_subject;
		$email->to =$applicationSettings->company_email;
		
		$this->sendEMail($email);
	
		return $email;
	}
	
	
	function getEmailTemplate( $template)
	{
		$query = ' SELECT * FROM #__jbusinessdirectory_emails WHERE email_type = "'.$template.'"';
		$this->_db->setQuery( $query );
		$templ=  $this->_db->loadObject();
		return $templ;
	}
	
	function sendEmail($email)
	{
		
		$email->content = EmailService::updateCompanyDetails($email->content);

		jimport('joomla.mail.mail');
		$mail = new JMail();
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$mail->setSender(array($applicationSettings->company_email, $applicationSettings->company_name));
		$mail->addRecipient($email->to);
		$mail->setSubject($email->subject);
		$mail->setBody($email->content);
		$mail->IsHTML(true);
	
		$ret = $mail->send();
		
		$log = Logger::getInstance();
		$log->LogDebug("E-mail with subject ".$email->subject." sent from ".$applicationSettings->company_email." to ".$email->to." result:".$ret);
	
		return $ret;
	}
	
	function prepareEmail($company, $templEmail, $data)
	{
		$fistName= isset($data["firstName"])?$data["firstName"]:"";
		$lastName=isset($data["lastName"])?$data["lastName"]:"";
		$description = isset($data["description"])?$data["description"]:"";
		$email = isset($data["email"])?$data["email"]:"";
		$abuseTxt = isset($data["description"])?$data["description"]:"";
		$expDays = isset($data["nrDays"])?$data["nrDays"]:"";
		$reviewName = isset($data["reviewName"])?$data["reviewName"]:"";
		$category = isset($data["category"])?$data["category"]:"";
		
		$reviewLink = JRoute::_('index.php?option=com_jbusinessdirectory&controller=companies&task=showcompany&view=companies&tabId=3&companyId='.$company->id, true, -1);
		$reviewLink = '<a href="'.$reviewLink.'">'.$reviewLink.'</a>';

		$companyLink = JBusinessUtil::getCompanyLink($company);
		$companyLink = '<a href="'.$companyLink.'">'.$company->name.'</a>';
		

		$templEmail = str_replace(EMAIL_CATEGORY, 								$category,			$templEmail);
		
		$templEmail = str_replace(EMAIL_FIRST_NAME, 								$fistName,			$templEmail);
	
		$templEmail = str_replace(EMAIL_LAST_NAME, 									$lastName,			$templEmail);
	
		$templEmail = str_replace(EMAIL_REVIEW_LINK,								$reviewLink, 		$templEmail);
	
		$templEmail = str_replace(EMAIL_BUSINESS_NAME,								$companyLink, 	$templEmail);
	
		$templEmail = str_replace(EMAIL_CONTACT_EMAIL,								$email, 			$templEmail);
		$templEmail = str_replace(EMAIL_CONTACT_CONTENT,							$description, 		$templEmail);
	
		$templEmail = str_replace(EMAIL_ABUSE_DESCRIPTION,							$description, 		$templEmail);
		$templEmail = str_replace(EMAIL_EXPIRATION_DAYS,							$expDays, 			$templEmail);
		
		$templEmail = str_replace(EMAIL_REVIEW_NAME,								$reviewName, 		$templEmail);
		
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
	
		$templEmail = str_replace(EMAIL_COMPANY_NAME,								$applicationSettings->company_name, 		$templEmail);
	
		return "<div style='width: 600px;'>".$templEmail.'</div>';
	}
	
	
	
}
?>