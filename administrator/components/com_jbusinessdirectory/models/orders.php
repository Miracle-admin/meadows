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
class JBusinessDirectoryModelOrders extends JModelList
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
				'id', 'inv.id',
				'title', 'inv.name',
				"order_id",'inv.order_id',
				"company",'c.name',
				"package",'p.name',
				"amount",'inv.amount',
				"created",'inv.created',
				"transaction_id",'inv.transaction_id',
				"paid_at",'inv.start_date',
				"user_name",'inv.user_name',
				"state",'inv.state'
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
		$query->select($this->getState('list.select', 'inv.*'));
		$query->from($db->quoteName('#__jbusinessdirectory_orders').' AS inv');
		
		// Join over the company types
		$query->select('c.name as companyName');
		$query->join('LEFT', $db->quoteName('#__jbusinessdirectory_companies').' AS c ON c.id=inv.company_id');
		
		// Join over the company types
		$query->select('p.name as packageName');
		$query->join('LEFT', $db->quoteName('#__jbusinessdirectory_packages').' AS p ON p.id=inv.package_id');
		
		// Filter by search in title.
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			$query->where("inv.order_id LIKE '%".trim($db->escape($search))."%' or c.phone LIKE '%".trim($db->escape($search))."%' or c.name LIKE '%".trim($db->escape($search))."%' or p.name LIKE '%".trim($db->escape($search))."%' ");
		}
		
		$statusId = $this->getState('filter.state_id');
		if (is_numeric($statusId)) {
			$query->where("inv.state =".(int) $statusId);
		}
		
		$query->group('inv.id');

		// Add the list ordering clause.
		$query->order($db->escape($this->getState('list.ordering', 'inv.id')).' '.$db->escape($this->getState('list.direction', 'ASC')));

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
		
		$statusId = $app->getUserStateFromRequest($this->context.'.filter.state_id', 'filter_state_id');
		$this->setState('filter.state_id', $statusId);
	
		// Check if the ordering field is in the white list, otherwise use the incoming value.
		$value = $app->getUserStateFromRequest($this->context.'.ordercol', 'filter_order', $ordering);
		$this->setState('list.ordering', $value);
	
		// Check if the ordering direction is valid, otherwise use the incoming value.
		$value = $app->getUserStateFromRequest($this->context.'.orderdirn', 'filter_order_Dir', $direction);
		$this->setState('list.direction', $value);
		
		// List state information.
		parent::populateState('inv.id', 'desc');
	}
	
	
}
