<?php


defined('_JEXEC') or die;
jimport('joomla.application.component.modeladmin');
/**
 * Company Model for Companies.
 *
 */
class JBusinessDirectoryModelDiscount extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since   1.6
	 */
	protected $text_prefix = 'COM_JBUSINESSDIRECTORY_DISCOUNT';

	/**
	 * Model context string.
	 *
	 * @var		string
	 */
	protected $_context		= 'com_jbusinessdirectory.discount';

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
	public function getTable($type = 'Discount', $prefix = 'JTable', $config = array())
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
		$this->setState('discount.id', $id);
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
		$itemId = (!empty($itemId)) ? $itemId : (int) $this->getState('discount.id');
		$false	= false;

		// Get a menu item row instance.
		$table = $this->getTable("Discount");

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
		
		if($itemId == 0){
			$value->start_date = date('Y-m-d');
			$value->end_date = date("Y-m-d");// current date
		}
		
		$value->start_date = JBusinessUtil::convertToFormat($value->start_date);
		$value->end_date = JBusinessUtil::convertToFormat($value->end_date);
		$value->package_ids = explode(",",$value->package_ids);
		
		return $value;
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
		$form = $this->loadForm('com_jbusinessdirectory.event', 'item', array('control' => 'jform', 'load_data' => $loadData), true);
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
		$data = JFactory::getApplication()->getUserState('com_jbusinessdirectory.edit.event.data', array());
	
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
		$id	= (!empty($data['id'])) ? $data['id'] : (int) $this->getState('discount.id');
		$isNew = true;
		
		$data["start_date"] = JBusinessUtil::convertToMysqlFormat($data["start_date"]);
		$data["end_date"] = JBusinessUtil::convertToMysqlFormat($data["end_date"]);
		$data["package_ids"] = implode(",",$data["package_ids"]);
		
		if(empty($data["package_ids"])){
			$data["package_ids"] = "";
		}
		
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

		$this->setState('discount.id', $table->id);

		// Clean the cache
		$this->cleanCache();
		
		return true;
	}

	function deleteDiscount($discountId){
		$discountsTable = $this->getTable("Discount");
		return $discountsTable->delete($discountId);
	}
	
	function changeState(){
		$this->populateState();
		$discountsTable = $this->getTable("Discount");
		return $discountsTable->changeState($this->getState('discount.id'));
	}
	
	function getPackages(){
		$packageTable = JTable::getInstance("Package", "JTable");
		$packages =  $packageTable->getPackages();
		return $packages;
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
	
	public function generateDiscounts($data){
		
		$nrDiscounts = (int)$data["nr_discounts"];
		$codeLength = (int)$data["code_length"] - strlen($data["code_prefix"]);
		//dump($data);
		
		for($i=0;$i<$nrDiscounts;$i++){
			$discount = array();
			
			$code = $data["code_prefix"].substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $codeLength);
			
			$discount["id"] = 0;
			$discount["name"] = $code;
			$discount["code"] = $code;
			$discount["value"]=$data["value"];
			$discount["price_type"]=$data["price_type"];
			$discount["uses_per_coupon"]=$data["uses_per_coupon"];
			$discount["start_date"]=$data["start_date"];
			$discount["end_date"]=$data["end_date"];
			$discount["package_ids"]=$data["package_ids"];
			$discount["state"]=$data["state"];

			//dump($discount);
			
			$this->save($discount);
		}
		return true;
	}
}
