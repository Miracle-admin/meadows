<?php


defined('_JEXEC') or die;
jimport('joomla.application.component.modeladmin');
/**
 * Company Model for Companies.
 *
 */
class JBusinessDirectoryModelOffer extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since   1.6
	 */
	protected $text_prefix = 'COM_JBUSINESSDIRECTORY_OFFER';

	/**
	 * Model context string.
	 *
	 * @var		string
	 */
	protected $_context		= 'com_jbusinessdirectory.offer';

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
	public function getTable($type = 'Offer', $prefix = 'JTable', $config = array())
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
		$this->setState('offer.id', $id);
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
		$itemId = (!empty($itemId)) ? $itemId : (int) $this->getState('offer.id');
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
		
		$value->pictures = $this->getOfferPictures((int) $this->getState('offer.id'));
		
		if($itemId == 0){
			$value->startDate = date('Y-m-d');
			$value->endDate = date("Y-m-d");// current date
		}
		
		$value->startDate = JBusinessUtil::convertToFormat($value->startDate);
		$value->endDate = JBusinessUtil::convertToFormat($value->endDate);
		
		$value->publish_start_date = JBusinessUtil::convertToFormat($value->publish_start_date);
		$value->publish_end_date = JBusinessUtil::convertToFormat($value->publish_end_date);
		
		$companyCategoryTable = $this->getTable('CompanyCategory');
		$value->selectedCategories = $companyCategoryTable->getSelectedOfferCategories($itemId);
		
		$value->attachments = JBusinessDirectoryAttachments::getAttachments(OFFER_ATTACHMENTS, $itemId);
		
		return $value;
	}
	
	function getOfferPictures($offerId){
		$query = "SELECT * FROM #__jbusinessdirectory_company_offer_pictures WHERE offerId =".$offerId ." ORDER BY id ";
		
		$files =  $this->_getList( $query );
		$pictures = array();
		foreach( $files as $value )
		{
			$pictures[]	= array(
					'offer_picture_info' 		=> $value->picture_info,
					'offer_picture_path' 		=> $value->picture_path,
					'offer_picture_enable'		=> $value->picture_enable,
			);
		}
	
		return $pictures;
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
		
		// The folder and element vars are passed when saving the form.
		if (empty($data))
		{
			$item		= $this->getItem();
			// The type should already be set.
		}
		// Get the form.
		$form = $this->loadForm('com_jbusinessdirectory.offer', 'item', array('control' => 'jform', 'load_data' => $loadData), true);
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
		$data = JFactory::getApplication()->getUserState('com_jbusinessdirectory.edit.offer.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		return $data;
	}
	
	/**
	 * Check for duplicate alias and generate a new alias
	 * @param unknown_type $busienssId
	 * @param unknown_type $alias
	 */
	function checkAlias($busienssId, $alias){
	
		$table = $this->getTable();
		while($table->checkAlias($busienssId, $alias)){
			$alias = JString::increment($alias, 'dash');
		}
		return $alias;
	}
	
	/**
	 * Method to save the form data.
	 *
	 * @param   array  The form data.
	 * @return  boolean  True on success.
	 */
	public function save($data)
	{
		
		$id	= (!empty($data['id'])) ? $data['id'] : (int) $this->getState('offer.id');
		$isNew = true;
		
		$data["startDate"] = JBusinessUtil::convertToMysqlFormat($data["startDate"]);
		$data["endDate"] = JBusinessUtil::convertToMysqlFormat($data["endDate"]);
		$data["publish_start_date"] = JBusinessUtil::convertToMysqlFormat($data["publish_start_date"]);
		$data["publish_end_date"] = JBusinessUtil::convertToMysqlFormat($data["publish_end_date"]);
		$data["alias"]= JBusinessUtil::getAlias($data["subject"],$data["alias"]);
		$data["alias"] = $this->checkAlias($id, $data["alias"]);
		
		$defaultLng = JFactory::getLanguage()->getDefault();
		$description = 	JRequest::getVar( 'description_'.$defaultLng, '', 'post', 'string', JREQUEST_ALLOWHTML);
		if(!empty($description) && empty($data["description"]))
			$data["description"] = $description;
		
		$shortDescription = 	JRequest::getVar( 'short_description_'.$defaultLng, '', 'post', 'string', JREQUEST_ALLOWHTML);
		if(!empty($shortDescription) && empty($data["short_description"]))
			$data["short_description"] = $shortDescription;
		
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

		$this->setState('offer.id', $table->id);

		if($isNew && empty($data["no-email"])){
			EmailService::sendNewOfferNotificaiton($table);
		}
		
		JBusinessDirectoryTranslations::saveTranslations(OFFER_DESCRIPTION_TRANSLATION, $table->id, 'description_');
		JBusinessDirectoryAttachments::saveAttachments(OFFER_ATTACHMENTS, OFFER_ATTACHMENTS_PATH, $table->id, $data, $id);

		//save in companycategory table
		$table = $this->getTable('CompanyCategory');
		$table->insertOfferRelations( $this->getState('offer.id'), $data["categories"]);
		
		//save in companycategory table
		$this->storePictures($data, $this->getState('offer.id'),$id);
		
		// Clean the cache
		$this->cleanCache();
		

		return true;
	}

	function storePictures($data, $offerId, $oldId){
	
	
		//prepare photos
		$path_old = JBusinessUtil::makePathFile(JPATH_ROOT."/".PICTURES_PATH .OFFER_PICTURES_PATH.($oldId)."/");
		$files = glob( $path_old."*.*" );
	
		$path_new = JBusinessUtil::makePathFile(JPATH_ROOT."/".PICTURES_PATH .OFFER_PICTURES_PATH.($offerId)."/");
	
		dbg($path_new);
		//dbg($offerId);
		dbg($data['pictures']);
		//exit;
		$picture_ids 	= array();
		foreach( $data['pictures'] as $value )
		{
			$row = $this->getTable('OfferPictures');
	
			//dbg($key);
			$pic 						= new stdClass();
			$pic->id		= 0;
			$pic->offerId 				= $offerId;
			$pic->picture_info	= $value['offer_picture_info'];
			$pic->picture_path	= $value['offer_picture_path'];
			$pic->picture_enable	= $value['offer_picture_enable'];
			dbg($pic);
			$file_tmp = JBusinessUtil::makePathFile( $path_old.basename($pic->picture_path) );
	
			//dbg($pic);
			dbg($file_tmp);
			dbg(is_file($file_tmp));
			//exit;
			if( !is_file($file_tmp) )
				continue;
				
			//exit;
			if( !is_dir($path_new) )
			{
				if( !@mkdir($path_new) )
				{
					throw( new Exception($this->_db->getErrorMsg()) );
				}
			}
	
			dbg(($path_old.basename($pic->picture_path).",".$path_new.basename($pic->picture_path)));
			//exit;
			if( $path_old.basename($pic->picture_path) != $path_new.basename($pic->picture_path) )
			{
				if(@rename($path_old.basename($pic->picture_path),$path_new.basename($pic->picture_path)) )
				{
	
					$pic->picture_path	 = OFFER_PICTURES_PATH.($offerId).'/'.basename($pic->picture_path);
					//@unlink($path_old.basename($pic->room_picture_path));
				}
				else
				{
					throw( new Exception($this->_db->getErrorMsg()) );
				}
			}
	
			//dbg($pic);
			//exit;
			if (!$row->bind($pic))
			{
				throw( new Exception($this->_db->getErrorMsg()) );
				$this->setError($this->_db->getErrorMsg());
					
			}
			// Make sure the record is valid
			if (!$row->check())
			{
				throw( new Exception($this->_db->getErrorMsg()) );
				$this->setError($this->_db->getErrorMsg());
			}
	
			// Store the web link table to the database
			if (!$row->store())
			{
				throw( new Exception($this->_db->getErrorMsg()) );
				$this->setError($this->_db->getErrorMsg());
			}
	
			$picture_ids[] = $this->_db->insertid();
		}
			
		$files = glob( $path_new."*.*" );
			
		foreach( $files as $pic )
		{
			$is_find = false;
			foreach( $data['pictures'] as $value )
			{
				if( $pic == JBusinessUtil::makePathFile(JPATH_ROOT."/".PICTURES_PATH .$value['picture_path']) )
				{
					$is_find = true;
					break;
				}
			}
			if( $is_find == false )
				@unlink( JBusinessUtil::makePathFile(JPATH_ROOT."/".PICTURES_PATH .$value['picture_path']) );
		}
			
		$query = " DELETE FROM #__jbusinessdirectory_company_offer_pictures
		WHERE offerId = '".$offerId."'
		".( count($picture_ids)> 0 ? " AND id NOT IN (".implode(',', $picture_ids).")" : "");
	
		// dbg($query);
		//exit;
		$this->_db->setQuery( $query );
		if (!$this->_db->query())
		{
			throw( new Exception($this->_db->getErrorMsg()) );
		}
		//~prepare photos
	}
	
	function getCompanies(){
		$companiesTable = JTable::getInstance("Company", "JTable");
		$companies =  $companiesTable->getAllCompanies();
		return $companies;
	}
	
	function deleteOffer($offerId){
		$offersTable = $this->getTable("Offer");
		return $offersTable->delete($offerId);
	}
	
	function changeState(){
		$this->populateState();
		$offersTable = $this->getTable("Offer");
		return $offersTable->changeState($this->getState('offer.id'));
	}
	
	function changeStateOfferOfTheDay(){
		$this->populateState();
		$offersTable = $this->getTable("Offer");
		return $offersTable->changeStateOfferOfTheDay($this->getState('offer.id'));
	}
	
	function changeStateFeatured(){
		$this->populateState();
		$offersTable = $this->getTable("Offer");
		return $offersTable->changeStateFeatured($this->getState('offer.id'));
	}
	
	function changeAprovalState($state){
		$this->populateState();
		$offersTable = $this->getTable("Offer");
		$offer = $offersTable->getOffer($this->getState('offer.id'));
		
		$companiesTable = JTable::getInstance("Company", "JTable");
		$company = $companiesTable->getCompany($offer->companyId);
		
		if($offer->approved ==0 and $state==1){
			EmailService::sendApproveOfferNotificaiton($offer, $company->email);
		}
		
		return $offersTable->changeAprovalState($this->getState('offer.id'), $state);
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
		$status->value = 1;
		$status->text = JTEXT::_("LNG_DISAPPROVED");
		$statuses[] = $status;
		$status = new stdClass();
		$status->value = 2;
		$status->text = JTEXT::_("LNG_APPROVED");
		$statuses[] = $status;
	
		return $statuses;
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
			
			if (!$this->deleteFiles($itemId)){
				$this->setError("Could not delete files");
				return false;
			}
		}
	
		// Clean the cache
		$this->cleanCache();
	
		return true;
	}
	
	/**
	 * Delete offer files
	 * @param $itemId
	 * @return boolean
	 */
	function deleteFiles($itemId){
		$imagesDir = JPATH_ROOT."/".PICTURES_PATH .OFFER_PICTURES_PATH.($itemId);
		JBusinessUtil::removeDirectory($imagesDir);
		
		$attachmentDir = JPATH_ROOT."/".ATTACHMENT_PATH .OFFER_ATTACHMENTS_PATH.$itemId;
		JBusinessUtil::removeDirectory($attachmentDir);
		
		return true;
	}
}
