<?php


defined('_JEXEC') or die;
jimport('joomla.application.component.modeladmin');
/**
 * Company Model for Companies.
 *
 */
class JBusinessDirectoryModelPaymentProcessor extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since   1.6
	 */
	protected $text_prefix = 'COM_JBUSINESSDIRECTORY_PAYMENT_PROCESSOR';

	/**
	 * Model context string.
	 *
	 * @var		string
	 */
	protected $_context		= 'com_jbusinessdirectory.paymentprocessor';

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object	A record object.
	 *
	 * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
	 */
	protected function canDelete($record)
	{
		return true;
	}

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object	A record object.
	 *
	 * @return  boolean  True if allowed to change the state of the record. Defaults to the permission set in the component.
	 */
	protected function canEditState($record)
	{
		return true;
	}

	/**
	 * Returns a Table object, always creating it
	 *
	 * @param   type	The table type to instantiate
	 * @param   string	A prefix for the table class name. Optional.
	 * @param   array  Configuration array for model. Optional.
	 * @return  JTable	A database object
	*/
	public function getTable($type = 'PaymentProcessor', $prefix = 'JTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since   1.6
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('administrator');

		// Load the User state.
		$id = JRequest::getInt('id');
		$this->setState('paymentprocessor.id', $id);
	}

	/**
	 * Method to get a menu item.
	 *
	 * @param   integer	The id of the menu item to get.
	 *
	 * @return  mixed  Menu item data object on success, false on failure.
	 */
	public function &getItem($itemId = null)
	{
		$itemId = (!empty($itemId)) ? $itemId : (int) $this->getState('paymentprocessor.id');
		$false	= false;

		// Get a menu item row instance.
		$table = $this->getTable();

		// Attempt to load the row.
		$return = $table->load($itemId);

		// Check for a table object error.
		if ($return === false && $table->getError())
		{
			$this->setError($table->getError());
			return $false;
		}

		$properties = $table->getProperties(1);
		$value = JArrayHelper::toObject($properties, 'JObject');
		
		$value->processorFields = $this->getPaymentProcessorFields();
		
		return $value;
	}
	
	function getPaymentProcessorFields(){
		$table = $this->getTable("PaymentProcessorFields");
		return $table->getPaymentProcessorFields($this->getState('paymentprocessor.id'));
	}

	
	/**
	 * Method to get the menu item form.
	 *
	 * @param   array  $data		Data for the form.
	 * @param   boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return  JForm	A JForm object on success, false on failure
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		exit;
		// The folder and element vars are passed when saving the form.
		if (empty($data))
		{
			$item		= $this->getItem();
			// The type should already be set.
		}
		// Get the form.
		$form = $this->loadForm('com_jbusinessdirectory.paymentprocessor', 'item', array('control' => 'jform', 'load_data' => $loadData), true);
		if (empty($form))
		{
			return false;
		}
		
		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 * @since   1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_jbusinessdirectory.edit.paymentprocessor.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		return $data;
	}
	
	
	/**
	 * Method to save the form data.
	 *
	 * @param   array  The form data.
	 * @return  boolean  True on success.
	 */
	public function save($data)
	{
		
		$id	= (!empty($data['id'])) ? $data['id'] : (int) $this->getState('paymentprocessor.id');
		$isNew = true;
		
		
		// Get a row instance.
		$table = $this->getTable();

		// Load the row if saving an existing item.
		if ($id > 0)
		{
			$table->load($id);
			$isNew = false;
		}

		// Bind the data.
		if (!$table->bind($data))
		{
			$this->setError($table->getError());
			return false;
		}

		// Check the data.
		if (!$table->check())
		{
			$this->setError($table->getError());
			return false;
		}

		// Store the data.
		if (!$table->store())
		{
			$this->setError($table->getError());
			return false;
		}

		$this->setState('paymentprocessor.id', $table->id);

		if(count($data["features"])>0)
			$table->insertRelations( $table->id,$data["features"]);
		
		$this->saveProcessorFields($data);
		
		// Clean the cache
		$this->cleanCache();

		return true;
	}

	function saveProcessorFields($data){
		$columns = $data["column_name"];
		$columnValues = $data["column_value"];
		$processorId = (int) $this->getState('paymentprocessor.id');
		$table = $this->getTable("PaymentProcessorFields");
		$table->deleteProcessorFields($processorId);
	
		foreach($columns as $i=>$column){
			$tableFields = $this->getTable("PaymentProcessorFields");
			$tableFields->column_name = $column;
			$tableFields->column_value = $columnValues[$i];
			$tableFields->processor_id = $processorId;
			if (!$tableFields->check()) {
				$application = JFactory::getApplication();
				$application->enqueueMessage( $this->_db->getErrorMsg(), 'error');
				return false;
			}
			if(!$tableFields->store()){
				$application = JFactory::getApplication();
				$application->enqueueMessage( $this->_db->getErrorMsg(), 'error');
				return false;
			}
		}
	}
	
	function changeState(){
		$this->populateState();
		$paymentprocessorsTable = $this->getTable("PaymentProcessor");
		return $paymentprocessorsTable->changeState($this->getState('paymentprocessor.id'));
	}

	function changeFrontState(){
		$this->populateState();
		$paymentprocessorsTable = $this->getTable("PaymentProcessor");
		return $paymentprocessorsTable->changeFrontState($this->getState('paymentprocessor.id'));
	}

	function getSelectedFeatures(){
		$paymentprocessorsTable = $this->getTable("PaymentProcessor");
		$features = $paymentprocessorsTable->getSelectedFeatures($this->getState('paymentprocessor.id'));
		$result = array();
		foreach($features as $feature){
			$result[]=$feature->feature;
		}

		return $result;
	}
	
	/**
	 * Method to delete groups.
	 *
	 * @param   array  An array of item ids.
	 * @return  boolean  Returns true on success, false on failure.
	 */
	public function delete(&$itemIds)
	{
		// Sanitize the ids.
		$itemIds = (array) $itemIds;
		JArrayHelper::toInteger($itemIds);
	
		// Get a group row instance.
		$table = $this->getTable();
	
		// Iterate the items to delete each one.
		foreach ($itemIds as $itemId)
		{
	
			if (!$table->delete($itemId))
			{
				$this->setError($table->getError());
				return false;
			}
		}
	
		// Clean the cache
		$this->cleanCache();
	
		return true;
	}
	
	
}
