<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_categories
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Categories Component Category Model
 *
 * @package     Joomla.Administrator
 * @subpackage  com_categories
 * @since       1.6
 */
class JBusinessDirectoryModelCategory extends JModelAdmin
{
	/**
	 * @var    string  The prefix to use with controller messages.
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_JBUSINESSDIRECTORY';

	/**
	 * The type alias for this content type. Used for content version history.
	 *
	 * @var      string
	 * @since    3.2
	 */


	/**
	 * Override parent constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JModelLegacy
	 * @since   3.2
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object   $record  A record object.
	 *
	 * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
	 *
	 * @since   1.6
	 */
	protected function canDelete($record)
	{
		return true;
	}

	/**
	 * Method to test whether a record can have its state changed.
	 *
	 * @param   object   $record  A record object.
	 *
	 * @return  boolean  True if allowed to change the state of the record. Defaults to the permission set in the component.
	 *
	 * @since   1.6
	 */
	protected function canEditState($record)
	{
		return true;
	}

	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param   string  $type    The table name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JTable  A JTable object
	 *
	 * @since   1.6
	 */
	public function getTable($type = 'Category', $prefix = 'JBusinessTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('administrator');

		$parentId = $app->input->getInt('parent_id');
		$this->setState('category.parent_id', $parentId);

		// Load the User state.
		$pk = $app->input->getInt('id');
		$this->setState('category.id', $pk);
		
		// Load the parameters.
		$params = JComponentHelper::getParams('com_categories');
		$this->setState('params', $params);
	}

	/**
	 * Method to get a category.
	 *
	 * @param   integer  $pk  An optional id of the object to get, otherwise the id from the model state is used.
	 *
	 * @return  mixed    Category data object on success, false on failure.
	 *
	 * @since   1.6
	 */
	public function getItem($pk = null)
	{
		$itemId = (!empty($itemId)) ? $itemId : (int) $this->getState('category.id');
		$false	= false;

		$this->appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
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
		$result = JArrayHelper::toObject($properties, 'JObject');
		
		return $result;
	}

	/**
	 * Method to get the row form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed    A JForm object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
	
	}

	/**
	 * A protected method to get the where clause for the reorder
	 * This ensures that the row will be moved relative to a row with the same extension
	 *
	 * @param   JCategoryTable  $table  Current table instance
	 *
	 * @return  array           An array of conditions to add to add to ordering queries.
	 *
	 * @since   1.6
	 */
	protected function getReorderConditions($table)
	{
		return 'extension = ' . $this->_db->quote($table->extension);
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   1.6
	 */
	protected function loadFormData()
	{
		
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_categories.edit.' . $this->getName() . '.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		return $data;
	}

	/**
	 * Method to preprocess the form.
	 *
	 * @param   JForm   $form   A JForm object.
	 * @param   mixed   $data   The data expected for the form.
	 * @param   string  $group  The name of the plugin group to import.
	 *
	 * @return  void
	 *
	 * @see     JFormField
	 * @since   1.6
	 * @throws  Exception if there is an error in the form event.
	 */
	protected function preprocessForm(JForm $form, $data, $group = 'content'){
		
	}
	
	/**
	 * Check for duplicate alias and generate a new alias
	 * @param unknown_type $busienssId
	 * @param unknown_type $alias
	 */
	function checkAlias($busienssId, $alias){
	
		$companiesTable = $this->getTable();
		while($companiesTable->checkAlias($busienssId, $alias)){
			$alias = $alias."-1";
		}
		return $alias;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array    $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.6
	 */
	public function save($data)
	{
		$dispatcher = JEventDispatcher::getInstance();
		$table = $this->getTable();
		$input = JFactory::getApplication()->input;
		$pk = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('category.id');
		$isNew = true;

		$defaultLng = JFactory::getLanguage()->getDefault();
		$description = 	JRequest::getVar( 'description_'.$defaultLng, '', 'post', 'string', JREQUEST_ALLOWHTML);
		$name = JRequest::getVar( 'name_'.$defaultLng, '', 'post', 'string', JREQUEST_ALLOWHTML);
		
		if(!empty($description) && empty($data["description"]))
			$data["description"] = $description;
		
		if(!empty($name) && empty($data["name"]))
			$data["name"] = $name;
		
		$data["alias"]= JBusinessUtil::getAlias($data["name"],$data["alias"]);
		$data["alias"] = $this->checkAlias($pk, $data["alias"]);
		
		// Load the row if saving an existing category.
		if ($pk > 0)
		{
			$table->load($pk);
			$isNew = false;
		}

		// Set the new parent id if parent id not matched OR while New/Save as Copy .
		if ($table->parent_id != $data['parent_id'] || $data['id'] == 0)
		{
			$table->setLocation($data['parent_id'], 'last-child');
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

		// Trigger the onContentAfterSave event.
		//$dispatcher->trigger($this->event_after_save, array($this->option . '.' . $this->name, &$table, $isNew));

		// Rebuild the path for the category:
		if (!$table->rebuildPath($table->id))
		{
			$this->setError($table->getError());
			return false;
		}

		// Rebuild the paths of the category's children:
		if (!$table->rebuild($table->id, $table->lft, $table->level, $table->path))
		{
			$this->setError($table->getError());
			return false;
		}

		JBusinessDirectoryTranslations::saveTranslations(CATEGORY_TRANSLATION, $table->id, 'description_');				

		$this->setState($this->getName() . '.id', $table->id);

		return $table->id;
	}

	
	/**
	 * Method to change the published state of one or more records.
	 *
	 * @param   array    &$pks   A list of the primary keys to change.
	 * @param   integer  $value  The value of the published state.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   2.5
	 */
	public function publish(&$pks, $value = 1)
	{
		if (parent::publish($pks, $value))
		{
			return true;
		}
	}

	/**
	 * Method rebuild the entire nested set tree.
	 *
	 * @return  boolean  False on failure or error, true otherwise.
	 *
	 * @since   1.6
	 */
	public function rebuild()
	{
		// Get an instance of the table object.
		$table = $this->getTable();

		if (!$table->rebuild())
		{
			$this->setError($table->getError());
			return false;
		}

		return true;
	}

	/**
	 * Method to save the reordered nested set tree.
	 * First we save the new order values in the lft values of the changed ids.
	 * Then we invoke the table rebuild to implement the new ordering.
	 *
	 * @param   array    $idArray    An array of primary key ids.
	 * @param   integer  $lft_array  The lft value
	 *
	 * @return  boolean  False on failure or error, True otherwise
	 *
	 * @since   1.6
	 */
	public function saveorder($idArray = null, $lft_array = null)
	{
		// Get an instance of the table object.
		$table = $this->getTable();

		if (!$table->saveorder($idArray, $lft_array))
		{
			$this->setError($table->getError());
			return false;
		}

		return true;
	}
	
	function importCategories($filePath, $delimiter){
		$categories = $this->getCategories();
		require_once( JPATH_COMPONENT_ADMINISTRATOR.'/library/category_lib.php');
		$service=new JBusinessDirectorCategoryLib();
		$service->createRootElement();
		
		$newCategoryCount = 0;
		$newSubCategoryCount = 0;
			
		$row = 1;
		ini_set("auto_detect_line_endings", "1");
		if (($handle = fopen($filePath, "r")) !== FALSE) {
			
			while (($data = fgetcsv($handle, 3000, $delimiter)) !== FALSE) {
				$categoryData = array();
				
				if($row==1){
					$header = $data;
					$row++;
					continue;
				}
				$num = count($data);
				
				//echo "<p> $num fields in line $row: <br /></p>\n";
				$row++;
				for ($c=0; $c < $num; $c++) {
					$categoryData[strtolower($header[$c])]= $data[$c];
				}
	
				$categoryIds = array();
				//dump($company);
				//dump($company["categories"]);
				//dump($categoryData);
				if(!empty($categoryData["category"])){
					
					$categoryName = $categoryData["category"];
					
					if(!isset($categories[$categoryName])){
						$newCategoryCount ++;
						$categoryId = $this->addCategory($categoryName);
						$categories = $this->getCategories();
					}
					$categoryId = $categories[$categoryName];
					
					$subcategories = explode(",", $categoryData["subcategories"]);
	
					foreach($subcategories as $subcategory){
						$id = $this->addCategory($subcategory, $categoryId);
						$newSubCategoryCount ++;
					}
					
					if(!empty($subcategories)){
						$categories = $this->getCategories();
					}
					
				}
				
			}
		}
		
		$result = new stdClass();
		$result->newCategories = $newCategoryCount;
		$result->newSubCategoryCount = $newSubCategoryCount;

		return $result;
	}
	
	public function exportCategoriesCSV(){
		$delimiter = JRequest::getVar("delimiter",",");
		$category = JRequest::getVar("category",",");
	
		$categoryTable = $this->getTable("Category","JBusinessTable");
		$categories =  $categoryTable->getCategoriesForExport();
	
		$csv_output = "category".$delimiter."subcategories"."\n";
	
		foreach($categories as $category){
			if($category->name=="Root")
				continue;
			$csv_output .= "\"$category->name\"".$delimiter."\"$category->subcategories\"";
			$csv_output .= "\n";
		}
	
		$fileName = "jbusinessdirectory_categories";
		header("Content-type: application/vnd.ms-excel");
		header("Content-disposition: csv" . date("Y-m-d") . ".csv");
		header("Content-disposition: filename=".$fileName.".csv");
		print $csv_output;
	}
	
	function getCategories(){
		$categoryService = new JBusinessDirectorCategoryLib();
		$categoryTable = JTable::getInstance("Category","JBusinessTable");
		$categories = $categoryTable->getAllCategories();
		$result = array();
		foreach($categories as $category){
			$result[$category->name] = $category->id;
		}
		return $result;
	}
	
	function addCategory($name, $parentId=1){
		if(!isset($name) || strlen(trim($name))<2 )
			return;
	
		$table = $this->getTable("Category","JBusinessTable");
	
		$category = array();
		$category["id"] = 0;
		$category["parent_id"] = $parentId;
		$category["name"] = $name;
		$category["alias"] = "";
		$category["published"] = 1;
	
		
		return $this->save($category);
	}
	
}
