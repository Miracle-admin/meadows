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
jimport('joomla.application.component.modeladmin');

class JBusinessDirectoryModelJBusinessDirectory extends JModelAdmin
{ 
	public function getForm($data = array(), $loadData = true)
	{
	
	}
	
	public function getStatistics(){
		$statistics = new stdClass();
		
		$companyTable = JTable::getInstance('Company','JTable');
		$statistics->totalListings = $companyTable->getTotalListings();
		$statistics->today = $companyTable->getTodayListings();
		$statistics->week = $companyTable->getWeekListings();
		$statistics->month = $companyTable->getMonthListings();
		$statistics->year = $companyTable->getYearListings();
		
		$categoryTable = JTable::getInstance('Category','JBusinessTable');
		$statistics->totalCategories = $categoryTable->getTotalCategories();
		
		$offersTable = JTable::getInstance('Offer','JTable');
		$statistics->totalOffers = $offersTable->getTotalNumberOfOffers();
		$statistics->activeOffers = $offersTable->getTotalActiveOffers();
		
		$eventsTable = JTable::getInstance('Event','JTable');
		$statistics->totalEvents = $eventsTable->getTotalNumberOfEvents();
		$statistics->activeEvents = $eventsTable->getTotalActiveEvents();
		
		return $statistics;
	}
	
	public function getIncome(){
		$income = new stdClass();
		
		$orderTable = JTable::getInstance('Order','JTable');
		$income->total = $orderTable->getTotalIncome();
		$income->today = $orderTable->getTodayIncome();
		$income->week = $orderTable->getWeekIncome();
		$income->month = $orderTable->getMonthIncome();
		$income->year = $orderTable->getYearIncome();

		return $income;
	}
}
?>