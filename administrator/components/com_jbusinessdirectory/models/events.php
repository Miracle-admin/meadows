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
class JBusinessDirectoryModelEvents extends JModelList
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
				'name', 'co.name',
				'companyName', 'bc.name',
				'price', 'co.price',
				'specialPrice', 'co.specialPrice',
				'type', 'co.type',
				'location', 'co.location',
				'start_date', 'co.start_date',
				'end_date', 'co.end_date',
				'view_count', 'co.view_count',
				'eventOfTheDay', 'co.eventOfTheDay',
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
			$item->start_date = JBusinessUtil::convertToFormat($item->start_date);
			$item->end_date = JBusinessUtil::convertToFormat($item->end_date);
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
		$query->from($db->quoteName('#__jbusinessdirectory_company_events').' AS co');
		
		// Join over the company types
		$query->select('bc.name as companyName');
		$query->join('LEFT', $db->quoteName('#__jbusinessdirectory_companies').' AS bc ON bc.id=co.company_id');
		
		// Join over the company types
		$query->select('et.name as eventType');
		$query->join('LEFT', $db->quoteName('#__jbusinessdirectory_company_event_types').' AS et ON co.type=et.id');
		
		// Filter by search in title.
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			$query->where("co.name LIKE '%".trim($db->escape($search))."%' or 
							co.location LIKE '%".trim($db->escape($search))."%' or 
						  	bc.name LIKE '%".trim($db->escape($search))."%' ");
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
	
	
	public function exportEventsCSVtoFile($path){
		$csv_output = $this->getEventsCSV();
		$result =  file_put_contents($path,$csv_output);
		return $result;
	}
	
	
	public function  getEventsCSV(){
		$delimiter = JRequest::getVar("delimiter",",");
		
		$eventTable = JTable::getInstance("Event", "JTable");
		$events =  $eventTable->getEventsForExport();
	
		$csv_output = "name".$delimiter."company".$delimiter."alias".$delimiter."description".$delimiter."location".$delimiter."type".$delimiter."start_date".$delimiter."end_date".$delimiter."status".$delimiter."approved".$delimiter."views".$delimiter."pictures"."\n";
	
		foreach($events as $event){
			$event->description = str_replace(array("\r\n", "\r", "\n"), "<br />", $event->description);
			$event->description = str_replace('"', '""', $event->description);
			$csv_output .= "\"$event->name\"".$delimiter."\"".$event->company."\"".$delimiter."$event->alias".$delimiter."\"$event->description\"".$delimiter."$event->location".$delimiter."\"$event->eventType\"".$delimiter."\"$event->start_date\"".$delimiter."\"$event->end_date\"".$delimiter."\"$event->state\"".$delimiter."$event->approved".$delimiter."\"$event->view_count\"".$delimiter."\"$event->pictures\"";
			$csv_output .= "\n";
		}
	
		return $csv_output;
		
	}
	
}
