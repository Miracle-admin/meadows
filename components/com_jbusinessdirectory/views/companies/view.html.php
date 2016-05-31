<?php /*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );

JHtml::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/gallery.css');
JHtml::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/magnific-popup.css');

JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/jquery.opacityrollover.js');
JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/jquery.galleriffic.js');
JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/jquery.magnific-popup.min.js');
JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/review.js');

if(!defined('J_JQUERY_UI_LOADED')) {
	JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/jquery-ui.js');

	define('J_JQUERY_UI_LOADED', 1);
}

class JBusinessDirectoryViewCompanies extends JViewLegacy
{
	function display($tpl = null)
	{
		
		$tabId = JRequest::getVar('tabId');
		if(!isset($tabId))
			$tabId = 1;
		$this->tabId = $tabId;
			
		$this->company = $this->get('Company');
		$this->companyAttributes = $this->get('CompanyAttributes');
		$this->pictures = $this->get('CompanyImages');
		$this->videos = $this->get('CompanyVideos');
		$this->offers = $this->get('CompanyOffers');
		$this->events = $this->get('CompanyEvents');
		$this->reviews = $this->get('Reviews');
		$this->reviewCriterias = $this->get('ReviewCriterias');
		
		$this->rating = $this->get('UserRating');
		$this->ratingCount = $this->get('RatingsCount');
		//$this->userCompany = $this->get('UserCompanies');
		$this->viewCount = $this->get('ViewCount');
		$this->package = $this->get('package');
		
		$this->appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		if($this->appSettings->enable_packages && !empty($this->package)){
			$this->videos = array_slice($this->videos,0, $this->package->max_videos);
			$this->pictures = array_slice($this->pictures,0, $this->package->max_pictures);
		}
		
		if(JRequest::getVar('layout2')!='')
			$tpl=JRequest::getVar('layout2');
		else if($this->appSettings->company_view==1){
			$tpl=null;
		}else if($this->appSettings->company_view==2){
			$tpl="style_3";
		}else if($this->appSettings->company_view==4){
			$tpl="style_4";
		}else{
			$tpl="onepage";
		}
		
		if($this->company->state == 0  
				|| $this->company->approved== COMPANY_STATUS_DISAPPROVED 
				|| ($this->appSettings->enable_packages && empty($this->package))){
			$tpl="inactive";
		}
		
		$session = JFactory::getSession();
		$this->location = $session->get('location');
		
		parent::display($tpl);

	}
}
?>
