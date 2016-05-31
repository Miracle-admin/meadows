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

class JBusinessDirectoryViewEvents extends JViewLegacy
{

	function __construct()
	{
		parent::__construct();
	}
	
	
	function display($tpl = null)
	{
		$state = $this->get('State');
		$this->params = $state->get("parameters.menu");
		
		$categoryId= JRequest::getVar('categoryId');
		$this->assignRef('categoryId', $categoryId);
		
		$this->appSettings =  JBusinessUtil::getInstance()->getApplicationSettings();
		$this->orderBy = JRequest::getVar("orderBy", $this->appSettings->order_search_events);
		$this->categorySearch = JRequest::getVar('categorySearch',null);
		$this->citySearch = JRequest::getVar('citySearch',null);
		$this->regionSearch = JRequest::getVar('regionSearch',null);
		$this->zipCode = JRequest::getVar('zipcode');
		
		$events = $this->get('Events');
		$this->assignRef('events', $events);
		//dump($events);
		
		$categories = $this->get('Categories');
		$this->assignRef('categories', $categories);
		
		if($this->appSettings->enable_search_filter_events){
			$serachFilter = $this->get('SeachFilter');
			$this->assignRef('searchFilter', $serachFilter);
		}
		
		$this->location = $this->get("Location");
		$session = JFactory::getSession();
		$this->radius= $session->get('ev-radius');
		
		$categoryId= $this->get('CategoryId');
		if(!empty($categoryId)){
			$this->categoryId=$categoryId;
			$this->category = $this->get('Category');
		}
		
		$this->pagination = $this->get('Pagination');
		$this->sortByOptions = $this->get('SortByConfiguration');
		

		parent::display($tpl);
	}
}
?>
