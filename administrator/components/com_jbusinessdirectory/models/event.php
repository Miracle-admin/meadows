<?php


defined('_JEXEC') or die;
jimport('joomla.application.component.modeladmin');
/**
 * Company Model for Companies.
 *
 */
class JBusinessDirectoryModelEvent extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since   1.6
	 */
	protected $text_prefix = 'COM_JBUSINESSDIRECTORY_EVENT';

	/**
	 * Model context string.
	 *
	 * @var		string
	 */
	protected $_context		= 'com_jbusinessdirectory.event';

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
	public function getTable($type = 'Event', $prefix = 'JTable', $config = array())
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
		$this->setState('event.id', $id);
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
		$itemId = (!empty($itemId)) ? $itemId : (int) $this->getState('event.id');
		$false	= false;

		// Get a menu item row instance.
		$table = $this->getTable("Event");

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
		
		$typesTable = $this->getTable('EventType');
		$value->types = $typesTable->getEventTypes();
		
		$value->pictures = $this->getEventPictures((int) $this->getState('event.id'));
		
		if($itemId == 0){
			$value->start_date = date('Y-m-d');
			$value->end_date = date("Y-m-d");// current date
		}
		
		$value->start_date = JBusinessUtil::convertToFormat($value->start_date);
		$value->end_date = JBusinessUtil::convertToFormat($value->end_date);
		
		$value->start_time = JBusinessUtil::convertTimeToFormat($value->start_time);
		$value->end_time = JBusinessUtil::convertTimeToFormat($value->end_time);
		return $value;
	}
	
	
	function getEventPictures($eventId){
		$query = "SELECT * FROM #__jbusinessdirectory_company_event_pictures
					WHERE eventId =".$eventId ."
					ORDER BY id ";
		
		$files =  $this->_getList( $query );
		$pictures = array();
		foreach( $files as $value )
		{
			$pictures[]	= array(
					'event_picture_info' 		=> $value->picture_info,
					'event_picture_path' 		=> $value->picture_path,
					'event_picture_enable'	=> $value->picture_enable,
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
	 * Check for duplicate alias and generate a new alias
	 * @param unknown_type $busienssId
	 * @param unknown_type $alias
	 */
	function checkAlias($eventId, $alias){
		$table = $this->getTable();
		while($table->checkAlias($eventId, $alias)){
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
		dump($data['id']);
		$id	= (!empty($data['id'])) ? $data['id'] : (int) $this->getState('event.id');
		dump("event-id ".$id);
		$isNew = true;
		
		if(!empty($data["start_date"]))
			$data["start_date"] = JBusinessUtil::convertToMysqlFormat($data["start_date"]);
		if(!empty($data["end_date"]))
			$data["end_date"] = JBusinessUtil::convertToMysqlFormat($data["end_date"]);	
		$data["alias"]= JBusinessUtil::getAlias($data["name"],$data["alias"]);
		$data["alias"] = $this->checkAlias($id, $data["alias"]);
		
		$defaultLng = JFactory::getLanguage()->getDefault();
		$description = 	JRequest::getVar( 'description_'.$defaultLng, '', 'post', 'string', JREQUEST_ALLOWHTML);
		
		$data["start_time"] = JBusinessUtil::convertTimeToMysqlFormat($data["start_time"]);
		$data["end_time"] = JBusinessUtil::convertTimeToMysqlFormat($data["end_time"]);
		
		if(!empty($description) && empty($data["description"]))
			$data["description"] = $description;
		
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

		$this->setState('event.id', $table->id);

		if(!empty($post["frequency"])){
			$table->recurring_id = $table->id;
			$table->store();
		}
		
		// Clean the cache
		$this->cleanCache();

		if($isNew && empty($data["no-email"])){
			EmailService::sendNewEventNotificaiton($table);
		}
		
		//save in companycategory table
		$this->storePictures($data, $this->getState('event.id'),$id);
		
		JBusinessDirectoryTranslations::saveTranslations(EVENT_DESCRIPTION_TRANSLATION, $table->id, 'description_');
		
		return true;
	}

	function storePictures($data, $eventId, $oldId){
	
	
		//prepare photos
		$path_old = JBusinessUtil::makePathFile(JPATH_ROOT."/".PICTURES_PATH .EVENT_PICTURES_PATH.($oldId)."/");
		$files = glob( $path_old."*.*" );
	
		$path_new = JBusinessUtil::makePathFile(JPATH_ROOT."/".PICTURES_PATH .EVENT_PICTURES_PATH.($eventId)."/");
	
	
		//dbg($eventId);

		//exit;
		$picture_ids 	= array();
		foreach( $data['pictures'] as $value )
		{
			$row = $this->getTable('EventPictures');
	
			//dbg($key);
			$pic 						= new stdClass();
			$pic->id		= 0;
			$pic->eventId 	= $eventId;
			$pic->picture_info	= $value['event_picture_info'];
			$pic->picture_path	= $value['event_picture_path'];
			$pic->picture_enable	= $value['event_picture_enable'];
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
			dump(2); 
			dbg(($path_old.basename($pic->picture_path).",".$path_new.basename($pic->picture_path)));
			//exit;
			if( $path_old.basename($pic->picture_path) != $path_new.basename($pic->picture_path) )
			{
				
				dump("rename file");
				if(@rename($path_old.basename($pic->picture_path),$path_new.basename($pic->picture_path)) )
				{
	
					$pic->picture_path	 = EVENT_PICTURES_PATH.($eventId).'/'.basename($pic->picture_path);
					//@unlink($path_old.basename($pic->room_picture_path));
				}
				else
				{
					throw( new Exception($this->_db->getErrorMsg()) );
				}
			}
	
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
		
		if($files && count($files)>0){	
			foreach( $files as $pic )
			{
				$is_find = false;
				foreach( $data['pictures'] as $value )
				{
					if( $pic == JBusinessUtil::makePathFile(JPATH_ROOT."/".PICTURES_PATH .$value['event_picture_path']) )
					{
						$is_find = true;
						break;
					}
				}
				//if( $is_find == false )
				//	@unlink( JBusinessUtil::makePathFile(JPATH_ROOT."/".PICTURES_PATH .$value['event_picture_path']) );
			}
		}
		
		dump($picture_ids);
		$query = " DELETE FROM #__jbusinessdirectory_company_event_pictures
		WHERE eventId = '".$eventId."'
		".( count($picture_ids)> 0 ? " AND id NOT IN (".implode(',', $picture_ids).")" : "");
	
		// dbg($query);
		
		$this->_db->setQuery( $query );
		if (!$this->_db->query())
		{
			throw( new Exception($this->_db->getErrorMsg()) );
			
		}
		dump($this->_db->getErrorMsg());
	}
	
	function createRecurringEvents($parentId, $data){
		//dump("createRecurringEvents");
		$table = $this->getTable();
		$table->deleteReccuringEvents($parentId);
		
		$dates = $this->getReccuringDates($data);
		$data["recurring_id"]=$parentId;
		foreach($dates as $date){
			$this->setState('event.id', $table->id);
			$data["id"]=0;
			$data["start_date"] = $date[0];
			$data["end_date"] =	$date[1];
			$data["approved"] =	1;
			$this->save($data);
		}
	}
	
	function updateRecurringEvents($parentId, $data){
		$table = $this->getTable();
		$table->deleteReccuringEvents($parentId);
	}
	
	function getReccuringDates($data){
		$dates = array();
		$data["start_date"] = JBusinessUtil::convertToMysqlFormat($data["start_date"]);
		$data["end_date"] = JBusinessUtil::convertToMysqlFormat($data["end_date"]);
		$data["rend_date"] =  isset($data["rend_date"])?JBusinessUtil::convertToMysqlFormat($data["rend_date"]):"";
		
		$eventPeriod = JBusinessUtil::getNumberOfDays($data["start_date"],$data["end_date"]);
		
		$frequency = $data["frequency"];
		$interval = $data["interval"];
		$rstart_date = $data["start_date"];
		$endson = $data["endson"];
		$week_days = isset($data["week_days"])?$data["week_days"]:"";
		$repeatby = $data["repeatby"];
		$occurrences = isset($data["occurrences"])?$data["occurrences"]:0;
		$occurrences--;
		$rend_date = isset($data["rend_date"])?$data["rend_date"]:"";
		$count = 0;
		
		switch($frequency){
			case 1:
				$create = true;
				$startDate = $rstart_date;
				while($create){
					$startDate = date("Y-m-d",strtotime("+$interval days", strtotime($startDate)));
					$endDate = date("Y-m-d",strtotime("+$eventPeriod days", strtotime($startDate)));
					$date = array($startDate,$endDate);
						
					if($endson ==1  && $count>=$occurrences){
						$create = false;
					}
					if($endson ==2 && strtotime($endDate)>=strtotime($rend_date)){
						$create = false;
					}
						
					if($create){
						$dates[] = $date;
					}
					
					if($count>100)
						exit;
					$count++;
				}
				break;
			case 2:
				$create = true;
				$weekDate = $rstart_date;
				while($create){
					$week_start = date('Y-m-d', strtotime('this week last monday', strtotime($weekDate)));
					foreach($week_days as $weekday){
						$weekday--;
						$startDate = date("Y-m-d",strtotime("+$weekday days", strtotime($week_start)));
						$endDate = date("Y-m-d",strtotime("+$eventPeriod days", strtotime($startDate)));
						$date = array($startDate,$endDate);
		
						if($endson ==1  && $count>=$occurrences){
							$create = false;
						}
		
						if($endson ==2 && strtotime($endDate)>=strtotime($rend_date)){
							$create = false;
						}
		
						if($create && strtotime($startDate)>strtotime($rstart_date)){
							$dates[] = $date;
						}
						$count++;
					}
					$weekDate = date("Y-m-d",strtotime("+$interval weeks", strtotime($weekDate)));
				}
				break;
			case 3:
		
				$create = true;
				$monthDate = $rstart_date;
				$day = strtotime($rstart_date);
				$weekNumber = date('W', $day) - date('W', strtotime(date('Y-m-01', $day)));
				$dayOfWeek = date('l', $day);
				$numbermappings = array("first", "second","third","fourth","fifth");
		
				while($create){
					$startDate ="";
					$endDate ="";
					$month_start = strtotime('first day of this month', strtotime($monthDate));
					dump(date("Y-m-d",$month_start));
					if($repeatby == 1){
						$dayMonth = date("d",strtotime($monthDate))-1;
						$startDate = date("Y-m-d",strtotime("+$dayMonth days", $month_start));
						$endDate = date("Y-m-d",strtotime("+$eventPeriod days", strtotime($startDate)));
					}else{
						$startDate = date("Y-m-d",strtotime(" $numbermappings[$weekNumber] $dayOfWeek ", $month_start));
						$endDate = date("Y-m-d",strtotime("+$eventPeriod days", strtotime($startDate)));
					}
		
					$date = array($startDate,$endDate);
		
					if($endson ==1  && $count>=$occurrences){
						$create = false;
					}
					if($endson ==2 && strtotime($endDate)>=strtotime($rend_date)){
						$create = false;
					}
		
					if($create & strtotime($startDate)>strtotime($rstart_date)){
						$dates[] = $date;
					}
					$count++;
					if($count>3000)
						exit;
						
					$monthDate = date("Y-m-d",strtotime("+$interval months", strtotime($monthDate)));
				}
				break;
			case 4:
				$yearDate = $rstart_date;
				$create = true;
				while($create){
						
					$startDate = date("Y-m-d",strtotime($yearDate));
					$endDate = date("Y-m-d",strtotime("+$eventPeriod days", strtotime($startDate)));
		
					$date = array($startDate,$endDate);
		
					if($endson ==1  && $count>=$occurrences){
						$create = false;
					}
						
					if($endson ==2 && strtotime($endDate)>=strtotime($rend_date)){
						$create = false;
					}
		
					if($create & strtotime($startDate)>strtotime($rstart_date)){
						$dates[] = $date;
					}
					$count++;
					$yearDate = date("Y-m-d",strtotime("+$interval years", strtotime($yearDate)));
				}
				break;
		}
		
		return $dates;
	}
	
	function getNextEventsIds($eventId){
		$eventsTable = $this->getTable("Event");
		$eventsTable->load($eventId);
	
		return $eventsTable->getNextEventsIds($eventsTable->id, $eventsTable->recurring_id);
	}
	
	function getAllSeriesEventsIds($eventId){
		$eventsTable = $this->getTable("Event");
		$eventsTable->load($eventId);
		return $eventsTable->getAllSeriesEventsIds($eventsTable->recurring_id);
	}
	
	function deleteEvent($eventId){
		$eventsTable = $this->getTable("Event");
		return $eventsTable->delete($eventId);
	}
	
	function changeState(){
		$this->populateState();
		$eventsTable = $this->getTable("Event");
		return $eventsTable->changeState($this->getState('event.id'));
	}
	
	function changeStateEventOfTheDay(){
		$this->populateState();
		$eventsTable = $this->getTable("Event");
		return $eventsTable->changeStateEventOfTheDay($this->getState('event.id'));
	}
	
	function changeStateFeatured(){
		$this->populateState();
		$offersTable = $this->getTable("Event");
		return $offersTable->changeStateFeatured($this->getState('event.id'));
	}
	
	
	function changeAprovalState($state){
		$this->populateState();
		$eventsTable = $this->getTable("Event");
		$event = $eventsTable->getEvent($this->getState('event.id'));

		$companiesTable = JTable::getInstance("Company", "JTable");
		$company = $companiesTable->getCompany($event->company_id);
		
		if($event->approved ==0 and $state==1){
			EmailService::sendApproveEventNotificaiton($event, $company->email);
		}

		return $eventsTable->changeAprovalState($this->getState('event.id'), $state);
	}
	
	function getCompanies(){
		$companiesTable = JTable::getInstance("Company", "JTable");
		$companies =  $companiesTable->getAllCompanies();
		return $companies;
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
		foreach ($itemIds as $itemId){
			if (!$table->delete($itemId)){
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
	 * Delete event files
	 * @param $itemId
	 * @return boolean
	 */
	function deleteFiles($itemId){
		$imagesDir = JPATH_ROOT."/".PICTURES_PATH .EVENT_PICTURES_PATH.($itemId);
		JBusinessUtil::removeDirectory($imagesDir);
	
		return true;
	}
}
