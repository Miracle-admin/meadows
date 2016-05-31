<?php
/**
* @copyright	Copyright (C) 2008-2009 CMSJunkie. All rights reserved.
* 
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
* See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


JHtml::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/categories.css');

JHTML::_('script',  'components/com_jbusinessdirectory/assets/js/imagesloaded.pkgd.min.js');
JHTML::_('script',  'components/com_jbusinessdirectory/assets/js/jquery.isotope.min.js');
JHTML::_('script',  'components/com_jbusinessdirectory/assets/js/isotope.init.js');

class JBusinessDirectoryViewOffers extends JViewLegacy
{

	function __construct()
	{
		parent::__construct();
	}
	
	
	function display($tpl = null)
	{
		$this->appSettings =  JBusinessUtil::getInstance()->getApplicationSettings();
		$state = $this->get('State');
		$this->params = $state->get("parameters.menu");
		
		$this->offers = $this->get('Offers');
		$this->orderBy = JRequest::getVar("orderBy", $this->appSettings->order_search_offers);
	
		$this->categorySearch = JRequest::getVar('categorySearch',null);
		$this->citySearch = JRequest::getVar('citySearch',null);
		$this->regionSearch = JRequest::getVar('regionSearch',null);
		$this->zipCode = JRequest::getVar('zipcode');
		
		$searchkeyword = JRequest::getVar('searchkeyword');
		if(isset($searchkeyword)){
			$this->searchkeyword=  $searchkeyword;
		}
		
		$this->categories = JRequest::getVar("Categories");
		
		$categoryId= $this->get('CategoryId');
		if(!empty($categoryId)){
			$this->categoryId=$categoryId;
			$this->category = $this->get('Category');
		}	
		
		if($this->appSettings->enable_search_filter_offers){
			$this->searchFilter = $this->get('SeachFilter');
		}
		
		$this->location = $this->get("Location");
		$session = JFactory::getSession();
		$this->radius= $session->get('of-radius');
		
		$this->pagination = $this->get('Pagination');
		$this->sortByOptions = $this->get('SortByConfiguration');
		
		parent::display($tpl);
	}
}
?>
