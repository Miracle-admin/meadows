<?php
/**
 * @package    JBusinessDirectory
 * @subpackage  com_jbusinessdirectory
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
jimport('joomla.application.component.modellist');
/**
 * List Model.
 *
 * @package    JBusinessDirectory
 * @subpackage  com_jbusinessdirectory

 */
class JBusinessDirectoryModelOffers extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  An optional associative array of configuration settings.
	 *
	 * @see     JController
	 * @since   1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'co.id',
				'name', 'co.subject',
				'companyName', 'bc.name',
				'publishStartDate', 'co.publish_start_date',
				'publishEndDate', 'co.publish_end_date',
				'startDate', 'co.startDate',
				'endDate', 'co.endDate',
				'viewCount', 'co.viewCount',
				'offerOfTheDay', 'co.offerOfTheDay',
				'featured', 'co.featured',
				'state', 'co.state',
				'approved', 'co.approved'
			);
		}

		parent::__construct($config);
	}

	/**
	 * Overrides the getItems method to attach additional metrics to the list.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since   1.6.1
	 */
	public function getItems()
	{
		// Get a storage key.
		$store = $this->getStoreId('getItems');

		// Try to load the data from internal storage.
		if (!empty($this->cache[$store]))
		{
			return $this->cache[$store];
		}

		// Load the list items.
		$items = parent::getItems();

		// If emtpy or an error, just return.
		if (empty($items))
		{
			return array();
		}
		
		foreach($items as $item){
			$item->startDate = JBusinessUtil::convertToFormat($item->startDate);
			$item->endDate = JBusinessUtil::convertToFormat($item->endDate);
		}
		
		// Add the items to the internal cache.
		$this->cache[$store] = $items;

		return $this->cache[$store];
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return  string  An SQL query
	 *
	 * @since   1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		
		
		// Select all fields from the table.
		$query->select($this->getState('list.select', 'co.*'));
		$query->from($db->quoteName('#__jbusinessdirectory_company_offers').' AS co');
		
		// Join over the company types
		$query->select('bc.name as companyName');
		$query->join('LEFT', $db->quoteName('#__jbusinessdirectory_companies').' AS bc ON bc.id=co.companyId');
		
		// Filter by search in title.
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			$query->where("co.subject LIKE '%".trim($db->escape($search))."%' or 
							bc.name LIKE '%".trim($db->escape($search))."%'");
		}
		
		$statusId = $this->getState('filter.status_id');
		if (is_numeric($statusId)) {
			$query->where('co.approved ='.(int) $statusId);
		}
	
		$stateId = $this->getState('filter.state_id');
		if (is_numeric($stateId)) {
			$query->where('co.state ='.(int) $stateId);
		}
	
		$query->group('co.id');

		// Add the list ordering clause.
		$query->order($db->escape($this->getState('list.ordering', 'co.id')).' '.$db->escape($this->getState('list.direction', 'ASC')));

		return $query;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication('administrator');

		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		
		$statusId = $app->getUserStateFromRequest($this->context.'.filter.status_id', 'filter_status_id');
		$this->setState('filter.status_id', $statusId);
	
		$stateId = $app->getUserStateFromRequest($this->context.'.filter.state_id', 'filter_state_id');
		$this->setState('filter.state_id', $stateId);
	
		// Check if the ordering field is in the white list, otherwise use the incoming value.
		$value = $app->getUserStateFromRequest($this->context.'.ordercol', 'filter_order', $ordering);
		$this->setState('list.ordering', $value);
	
		// Check if the ordering direction is valid, otherwise use the incoming value.
		$value = $app->getUserStateFromRequest($this->context.'.orderdirn', 'filter_order_Dir', $direction);
		$this->setState('list.direction', $value);
		
		// List state information.
		parent::populateState('co.id', 'desc');
	}
	
	function getCompanyTypes(){
		$companiesTable = $this->getTable("Company");
		return $companiesTable->getCompanyTypes();
	}
	
	function getStates(){
		$states = array();
		$state = new stdClass();
		$state->value = 0;
		$state->text = JTEXT::_("LNG_INACTIVE");
		$states[] = $state;
		$state = new stdClass();
		$state->value = 1;
		$state->text = JTEXT::_("LNG_ACTIVE");
		$states[] = $state;
	
		return $states;
	}
	
	function getStatuses(){
		$statuses = array();
		$status = new stdClass();
		$status->value = 0;
		$status->text = JTEXT::_("LNG_NEEDS_CREATION_APPROVAL");
		$statuses[] = $status;
		$status = new stdClass();
		$status->value = -1;
		$status->text = JTEXT::_("LNG_DISAPPROVED");
		$statuses[] = $status;
		$status = new stdClass();
		$status->value = 1;
		$status->text = JTEXT::_("LNG_APPROVED");
		$statuses[] = $status;
	
		return $statuses;
	}
	
	public function exportOffersCSVtoFile($path){
		$csv_output = $this->getOffersCSV();
		$result =  file_put_contents($path,$csv_output);
		return $result;
	}
	
	
	public function  getOffersCSV(){
		$delimiter = JRequest::getVar("delimiter",",");
	
		$offerTable = JTable::getInstance("Offer", "JTable");
		$offers =  $offerTable->getOfferForExport();
	
		$csv_output = "name".$delimiter. "alias".$delimiter."categories".$delimiter."short_description".$delimiter."description".$delimiter."price".$delimiter."special_price".$delimiter."start_date".$delimiter."end_date".$delimiter."start_publish_date".$delimiter."end_publish_date".$delimiter."address".$delimiter."city".$delimiter."region".$delimiter."offer_of_the_day".$delimiter.
		"view_type".$delimiter."url".$delimiter."article_id".$delimiter."latitude".$delimiter."longitude".$delimiter."state".$delimiter."approved".$delimiter."viewCount".$delimiter."pictures"."\n";
		
		foreach($offers as $offer){
			$offer->short_description = str_replace(array("\r\n", "\r", "\n"), "<br />", $offer->short_description);
			$offer->short_description = str_replace('"', '""', $offer->short_description);
			$offer->description = str_replace(array("\r\n", "\r", "\n"), "<br />", $offer->description);
			$offer->description = str_replace('"', '""', $offer->description);
			$csv_output .= "\"$offer->subject\"".$delimiter."\"".$offer->alias."\"".$delimiter."$offer->categories".$delimiter."\"$offer->short_description\"".$delimiter."\"$offer->description\"".$delimiter."\"$offer->price\"".$delimiter."\"$offer->specialPrice\"".$delimiter."\"$offer->startDate\"".$delimiter."\"$offer->endDate\"".$delimiter."$offer->publish_start_date".$delimiter."\"$offer->publish_end_date\"".$delimiter."\"$offer->address\"".$delimiter."\"$offer->city\"".$delimiter."\"$offer->county\"".$delimiter."\"$offer->offerOfTheDay\"".$delimiter.
			 "\"$offer->view_type\"".$delimiter. "\"$offer->url\"".$delimiter. "\"$offer->article_id\"".$delimiter. "\"$offer->latitude\"".$delimiter. "\"$offer->longitude\"".$delimiter. "\"$offer->state\"".$delimiter. "\"$offer->approved\"".$delimiter. "\"$offer->viewCount\"".$delimiter."\"$offer->pictures\"";
			$csv_output .= "\n";
		}
	
		return $csv_output;
	}
	
}
