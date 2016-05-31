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
class JBusinessDirectoryModelDiscounts extends JModelList
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
				'code', 'co.code',
				'value', 'co.value',
				'start_date', 'co.start_date',
				'end_date', 'co.end_date',
				'state', 'co.state'
				
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
		
		$packages = $this->getPackages();
		foreach($items as $item){
			$item->start_date = JBusinessUtil::convertToFormat($item->start_date);
			$item->end_date = JBusinessUtil::convertToFormat($item->end_date);
			$packageIds = explode(",",$item->package_ids);
			$item->packageNames = $this->getPackageNames($packages, $packageIds);
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
		$query->from($db->quoteName('#__jbusinessdirectory_discounts').' AS co');
		
		
		// Filter by search in title.
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			$query->where("co.name LIKE '%".trim($db->escape($search))."%' or
							co.value LIKE '%".trim($db->escape($search))."%' or
							co.code LIKE '%".trim($db->escape($search))."%'");
		}
		
		$stateId = $this->getState('filter.state_id');
		if (is_numeric($stateId)) {
			$query->where('co.state ='.(int) $stateId);
		}
		
		$packageId = $this->getState('filter.package_id');
		if (is_numeric($packageId)) {
			$query->where("FIND_IN_SET('{$packageId}', package_ids)");
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
	
		$stateId = $app->getUserStateFromRequest($this->context.'.filter.state_id', 'filter_state_id');
		$this->setState('filter.state_id', $stateId);
		
		$packageId = $app->getUserStateFromRequest($this->context.'.filter.package_id', 'filter_package_id');
		$this->setState('filter.package_id', $packageId);
	
		// Check if the ordering field is in the white list, otherwise use the incoming value.
		$value = $app->getUserStateFromRequest($this->context.'.ordercol', 'filter_order', $ordering);
		$this->setState('list.ordering', $value);
	
		// Check if the ordering direction is valid, otherwise use the incoming value.
		$value = $app->getUserStateFromRequest($this->context.'.orderdirn', 'filter_order_Dir', $direction);
		$this->setState('list.direction', $value);
		
		// List state information.
		parent::populateState('co.id', 'desc');
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
	
	function getPackagesOption(){
		$packageTable = JTable::getInstance("Package", "JTable");
		$packages =  $packageTable->getPackages();
		
		return $packages;
	}
	
	function getPackages(){
		$packageTable = JTable::getInstance("Package", "JTable");
		$packages =  $packageTable->getPackages();
		$result = array();
		foreach($packages as $package){
			$result[$package->id] = $package;
		}
		return $result;
	}
	
	function getPackageNames($packages, $selectePackages){
		$result = array();
		if(!empty($selectePackages)){
			foreach($selectePackages as $selectePackage){
				if(isset($packages[$selectePackage])){
					$result[] = $packages[$selectePackage]->name;
				}
			}
		}
		
		$result = implode(",",$result);
		return $result;
	}
	
	
	public function exportDiscountsCsv(){
	
		$csv_output = $this->getDiscountsCSV();
	
		$fileName = "jbusinessdirectory_business_discounts";
		header("Content-type: application/vnd.ms-excel");
		header("Content-disposition: csv" . date("Y-m-d") . ".csv");
		header( "Content-disposition: filename=".$fileName.".csv");
		print $csv_output;
	}
	
	public function getDiscountsCSV(){
		$delimiter = JRequest::getVar("delimiter",",");
		$category = JRequest::getVar("category","");
	
		$this->setState('list.limit', 0);
		$discounts =  $this->getItems();
		
	
		$csv_output = "name".$delimiter."start date".$delimiter."end date".$delimiter."packages".$delimiter."coupon code".$delimiter."uses".$delimiter."discount"."\n";
		foreach($discounts as $discount){
			$csv_output .= "\"$discount->name\"".$delimiter."\"".$discount->start_date."\"".$delimiter."$discount->end_date".$delimiter."\"$discount->packageNames\"".$delimiter."$discount->code".$delimiter."\"".$discount->uses_per_coupon."\"".$delimiter.($discount->value.(($discount->price_type==2)? "%":''));
			$csv_output .= "\n";
		}
	
		return $csv_output;
	}
}
