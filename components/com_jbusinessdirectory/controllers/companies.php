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



class JBusinessDirectoryControllerCompanies extends JControllerLegacy
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	 
	function __construct()
	{
		parent::__construct();
	}

	function displayCompany(){
		parent::display();
	}
	
	function showCompany(){
		$model = $this->getModel('companies');
		$model->increaseViewCount();
		JRequest::setVar("view","companies");
		parent::display();
	}
	
	function saveReview(){
		$app = JFactory::getApplication();
		$model = $this->getModel('companies');
		$post = JRequest::get( 'post' );
		$companyId = JRequest::getVar('companyId');
		$company = $model->getPlainCompany($companyId);
		
		$ipAddress = $_SERVER['REMOTE_ADDR'];
		$post["ipAddress"] = $ipAddress;
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$captchaAnswer = !empty($post['recaptcha_response_field'])?$post['recaptcha_response_field']:$post['g-recaptcha-response'];
		
		if($appSettings->captcha){
			$namespace="jbusinessdirectory.contact";
			$captcha = JCaptcha::getInstance("recaptcha", array('namespace' => $namespace));
			if(!$captcha->checkAnswer($captchaAnswer)){
				$error = $captcha->getError();
				$app->setUserState('com_jbusinessdirectory.add.review.data', $post);
				$this->setMessage("Captcha error!", 'warning');
				$this->setRedirect(JBusinessUtil::getCompanyLink($company));
				return;
			}
		}
		
		if ($model->saveReview($post)){
			$this->setMessage(JText::_('LNG_REVIEW_SAVED'));
			$this->setRedirect(JBusinessUtil::getCompanyLink($company));
		}else {
			$this->setMessage(JText::_('LNG_ERROR_SAVING_REVIEW'),'error');
			$this->setRedirect(JBusinessUtil::getCompanyLink($company));	
		}	
	}
	
	function cancelReview(){
		$this->setMessage(JText::_('LNG_OPERATION_CANCELLED'));
		$this->setRedirect(JBusinessUtil::getCompanyLink($company));
	}
	
	
	function updateRating(){
		$model = $this->getModel('companies');
		$post = JRequest::get( 'post' );
		$ipAddress = $_SERVER['REMOTE_ADDR'];
		$post["ipAddress"] = $ipAddress;
		$ratingId = $model->saveRating($post);
		$nrRatings = $model->getRatingsCount($post['companyId']);
		$company = $model->getCompany($post['companyId']);
		
		echo '<?xml version="1.0" encoding="utf-8" ?>';
		echo '<company_statement>';
		echo '<answer  nrRatings="'.($nrRatings).'" id="'.$post["companyId"].'" ratingId="'.$ratingId.'" averageRating="'.$company->averageRating.'"/>';
		echo '</company_statement>';
		echo '</xml>';
		exit;
	}
	
	function reportAbuse(){
		$app = JFactory::getApplication();
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$model = $this->getModel('companies');
		$post = JRequest::get('post');

		$companyId = JRequest::getVar('companyId');
		$company = $model->getPlainCompany($companyId);
		$captchaAnswer = !empty($post['recaptcha_response_field'])?$post['recaptcha_response_field']:$post['g-recaptcha-response'];
		
		if($appSettings->captcha){
			$namespace="jbusinessdirectory.contact";
			$captcha = JCaptcha::getInstance("recaptcha", array('namespace' => $namespace));
			if(!$captcha->checkAnswer($captchaAnswer)){
				$error = $captcha->getError();
				$this->setMessage("Captcha error!", 'warning');
				$message="Captcha error";
				$this->setRedirect(JBusinessUtil::getCompanyLink($company));
				return;
			}
		}
		
		$model = $this->getModel('companies');
	
		$result = $model->reportAbuse($post);
	
		if($result){
			$this->setMessage(JText::_('COM_JBUSINESS_DIRECTORY_COMPANY_CONTACTED'));
			$this->setRedirect(JBusinessUtil::getCompanyLink($company));
		}else{
			$this->setMessage(JText::_('COM_JBUSINESS_DIRECTORY_COMPANY_NOT_CONTACTED'),'warning');
			$this->setRedirect(JBusinessUtil::getCompanyLink($company));
	
		}
	}
	
	function saveReviewResponse(){
		$app = JFactory::getApplication();
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$model = $this->getModel('companies');
		$post = JRequest::get( 'post' );
		$companyId = JRequest::getVar('companyId');
		$company = $model->getPlainCompany($companyId);
		$ipAddress = $_SERVER['REMOTE_ADDR'];
		$post["ipAddress"] = $ipAddress;
		$captchaAnswer = !empty($post['recaptcha_response_field'])?$post['recaptcha_response_field']:$post['g-recaptcha-response'];
		//exit;
		
		if($appSettings->captcha){
			$namespace="jbusinessdirectory.contact";
			$captcha = JCaptcha::getInstance("recaptcha", array('namespace' => $namespace));
			if(!$captcha->checkAnswer($captchaAnswer)){
				$error = $captcha->getError();
				$app->setUserState('com_jbusinessdirectory.review.response.data', $post);
				$this->setMessage("Captcha error!", 'warning');
				$this->setRedirect(JRoute::_('index.php?option=com_jbusinessdirectory&controller=companies&view=companies&companyId='.$companyId, false));
				return;
			}
		}
		
		if ($model->saveReviewResponse($post)){
			$this->setMessage(JText::_('LNG_REVIEW_RESPONSE_SAVED'));
			$this->setRedirect(JBusinessUtil::getCompanyLink($company));
		}else {
			$this->setMessage(JText::_('LNG_ERROR_SAVING_REVIEW'),'warning');
			$this->setRedirect(JBusinessUtil::getCompanyLink($company));
		}
	}
	
	function claimCompany(){
		$app = JFactory::getApplication();
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$model = $this->getModel('companies');
		$post = JRequest::get( 'post' );
		$companyId = JRequest::getVar('companyId');
		$company = $model->getPlainCompany($companyId);
		$ipAddress = $_SERVER['REMOTE_ADDR'];
		$post["ipAddress"] = $ipAddress;
		//exit;
		$captchaAnswer = !empty($post['recaptcha_response_field'])?$post['recaptcha_response_field']:$post['g-recaptcha-response'];
		
		if($appSettings->captcha){
			$namespace="jbusinessdirectory.contact";
			$captcha = JCaptcha::getInstance("recaptcha", array('namespace' => $namespace));
			if(!$captcha->checkAnswer($captchaAnswer)){
				$error = $captcha->getError();
				$app->setUserState('com_jbusinessdirectory.claim.company.data', $post);
				$this->setMessage("Captcha error!", 'warning');
				$this->setRedirect(JBusinessUtil::getCompanyLink($company));
				return;
			}
		}
	
		if ($model->claimCompany($post))
		{
			$this->setMessage(JText::_('LNG_CLAIM_SUCCESSFULLY'));
			$model->sendClaimEmail();
			$this->setRedirect(JBusinessUtil::getCompanyLink($company));
		}else {
			$this->setMessage(JText::_('LNG_ERROR_CLAIMING_COMPANY'), 'warning');
			$this->setRedirect(JBusinessUtil::getCompanyLink($company));
		}
	}
	
	
	function increaseReviewLikeCount(){
		$model = $this->getModel('companies');
		$post = JRequest::get('post');
		$result = $model->increaseReviewLikeCount($post["reviewId"]);
		
		echo '<?xml version="1.0" encoding="utf-8" ?>';
		echo '<company_statement>';
		echo '<answer result="'.$result.'" reviewId="'.$post["reviewId"].'"/>';
		echo '</company_statement>';
		echo '</xml>';
		exit;
	}
	
	function increaseReviewDislikeCount(){
		$model = $this->getModel('companies');
		$post = JRequest::get('post');
		$result = $model->increaseReviewDislikeCount($post["reviewId"]);
		
		echo '<?xml version="1.0" encoding="utf-8" ?>';
		echo '<company_statement>';
		echo '<answer result="'.$result.'" reviewId="'.$post["reviewId"].'"/>';	
		echo '</company_statement>';
		echo '</xml>';
		exit;
	}
	
	function updateCompanyOwner(){
		$model = $this->getModel('companies');
		$post = JRequest::get('post');
		$result = $model->updateCompanyOwner($post["companyId"], $post["userId"]);
		
		
		echo '<?xml version="1.0" encoding="utf-8"?>';
		echo '<company_statement>';
		echo '<answer result="'.$result.'"/>';
		echo '</company_statement>';
		echo '</xml>';
		exit;
	}
	
	function checkCompanyName(){
		$post = JRequest::get( 'post' );
		$model = $this->getModel('companies');
		$company = $model->getCompanyByName(trim($post["companyName"]));
	
		
		$claim = isset($company->userId)?0:1;
		
		$exists = isset($company)?1:0;
	
		echo '<?xml version="1.0" encoding="utf-8" ?>';
		echo '<company_statement>';
		echo '<answer exists="'.$exists.'" claim="'.$claim.'" name="'.trim($post["companyName"]).'"/>';
		echo '</company_statement>';
		echo '</xml>';
		exit;
	}
	
	function contactCompany(){
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$model = $this->getModel('companies');
		
		$data = JRequest::get( 'post' );
		$companyId = JRequest::getVar('companyId');
		$company = $model->getPlainCompany($companyId);
		$captchaAnswer = !empty($post['recaptcha_response_field'])?$post['recaptcha_response_field']:$post['g-recaptcha-response'];
		
		if($appSettings->captcha){
			$namespace="jbusinessdirectory.contact";
			$captcha = JCaptcha::getInstance("recaptcha", array('namespace' => $namespace));
			if(!$captcha->checkAnswer($captchaAnswer)){
				$error = $captcha->getError();
				$this->setMessage("Captcha error!", 'warning');
				$this->setRedirect(JBusinessUtil::getCompanyLink($company));
				return;
			}
		}

		$result = $model->contactCompany($data);
		
		if($result){
			$this->setMessage(JText::_('COM_JBUSINESS_DIRECTORY_COMPANY_CONTACTED'));
		}else{
			$this->setMessage(JText::_('COM_JBUSINESS_DIRECTORY_COMPANY_NOT_CONTACTED'));
		}
		
		$this->setRedirect(JBusinessUtil::getCompanyLink($company));
		
	}
	
	
	function addBookmark(){
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$data = JRequest::get( 'post' );

		$model = $this->getModel('companies');
	
	
		$result = $model->addBookmark($data);
	
		if($result){
			$this->setMessage(JText::_('COM_JBUSINESS_BOOKMARK_ADDED'));
		}else{
			$this->setMessage(JText::_('JLIB_APPLICATION_ERROR_SAVE_FAILED'));
		}
	
		$company = $model->getPlainCompany($data["company_id"]);
		$this->setRedirect(JBusinessUtil::getCompanyLink($company));
	
	}
	
	function updateBookmark(){
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$data = JRequest::get( 'post' );
	
		$model = $this->getModel('companies');
	
	
		$result = $model->updateBookmark($data);
	
		if($result){
			$this->setMessage(JText::_('COM_JBUSINESS_BOOKMARK_UPDATED'));
		}else{
			$this->setMessage(JText::_('JLIB_APPLICATION_ERROR_SAVE_FAILED'));
		}
	
		$company = $model->getPlainCompany($data["company_id"]);
		$this->setRedirect(JBusinessUtil::getCompanyLink($company));
	
	}
	
	
	function removeBookmark(){
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$data = JRequest::get( 'post' );
	
		$model = $this->getModel('companies');
		$result = $model->removeBookmark($data);
	
		if($result){
			$this->setMessage(JText::_('COM_JBUSINESS_BOOKMARK_REMVED'));
		}else{
			$this->setMessage(JText::_('JLIB_APPLICATION_ERROR_SAVE_FAILED'));
		}
	
		$company = $model->getPlainCompany($data["company_id"]);
		$this->setRedirect(JBusinessUtil::getCompanyLink($company));
	
	}
	
	function contactCompanyAjax(){
		// Check for request forgeries.
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$post = JRequest::get( 'post' );
		$captchaAnswer = !empty($post['recaptcha_response_field'])?$post['recaptcha_response_field']:$post['g-recaptcha-response'];
		
		$errorFlag = false;
		$message="";
		if($appSettings->captcha){
			$namespace="jbusinessdirectory.contact";
			$captcha = JCaptcha::getInstance("recaptcha", array('namespace' => $namespace));
			if(!$captcha->checkAnswer($captchaAnswer)){
				$error = $captcha->getError();
				$this->setMessage("Captcha error!", 'warning');
				$message="Captcha error";
				$errorFlag = true;
			}
		}

		if(!$errorFlag){
			$model = $this->getModel('companies');
		
			$result = $model->contactCompany($post);
		
			if($result){
				$this->setMessage(JText::_('COM_JBUSINESS_DIRECTORY_COMPANY_CONTACTED'));
			}else{
				$this->setMessage(JText::_('COM_JBUSINESS_DIRECTORY_COMPANY_NOT_CONTACTED'));
				$message="JText::_('COM_JBUSINESS_DIRECTORY_COMPANY_NOT_CONTACTED')";
				$errorFlag = true;
			}
		}
	
		echo '<?xml version="1.0" encoding="utf-8" ?>';
		echo '<category_statement>';
		echo '<answer error="'.(!$errorFlag ? "0" : "1").'" errorMessage="'.$message.'"';
		echo '</category_statement>';
		echo '</xml>';
		exit;
		
	}
	
	function requestQuoteCompanyAjax(){
		// Check for request forgeries.
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$post = JRequest::get( 'post' );
		$captchaAnswer = !empty($post['recaptcha_response_field'])?$post['recaptcha_response_field']:$post['g-recaptcha-response'];
	
		$errorFlag = false;
		$message="";
		if($appSettings->captcha){
			$namespace="jbusinessdirectory.contact";
			$captcha = JCaptcha::getInstance("recaptcha", array('namespace' => $namespace));
			if(!$captcha->checkAnswer($captchaAnswer)){
				$error = $captcha->getError();
				$this->setMessage("Captcha error!", 'warning');
				$message="Captcha error";
				$errorFlag = true;
			}
		}
	
		if(!$errorFlag){
			$model = $this->getModel('companies');
		
			$result = $model->requestQuoteCompany($post);
		
			if($result){
				$this->setMessage(JText::_('COM_JBUSINESS_DIRECTORY_COMPANY_CONTACTED'));
				$message=JText::_('COM_JBUSINESS_DIRECTORY_COMPANY_CONTACTED');
			}else{
				$this->setMessage(JText::_('COM_JBUSINESS_DIRECTORY_COMPANY_NOT_CONTACTED'));
				$message="JText::_('COM_JBUSINESS_DIRECTORY_COMPANY_NOT_CONTACTED')";
				$errorFlag = true;
			}
		}
	
	
		echo '<?xml version="1.0" encoding="utf-8" ?>';
		echo '<category_statement>';
		echo '<answer error="'.(!$errorFlag ? "0" : "1").'" errorMessage="'.$message.'"/>';
		echo '</category_statement>';
		echo '</xml>';
		exit;
	
	}
	
	function checkBusinessAboutToExpire(){
		$model = $this->getModel('companies');
		$model->checkBusinessAboutToExpire();
	}
	
	function showCompanyWebsite(){
		$model = $this->getModel('companies');
		$company = $model->increaseWebsiteCount(JRequest::getVar('companyId'));
		
		$this->setRedirect($company->website);
	}
}
