<?php


defined('_JEXEC') or die;
jimport('joomla.application.component.modeladmin');

/**
 * Company Model for Companies.
 *
 */
class JBusinessDirectoryModelCompany extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since   1.6
	 */
	protected $text_prefix = 'COM_JBUSINESSDIRECTORY_COMPANY';

	/**
	 * Model context string.
	 *
	 * @var		string
	 */
	protected $_context		= 'com_jbusinessdirectory.company';

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
	public function getTable($type = 'Company', $prefix = 'JTable', $config = array())
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
	
		$id = JRequest::getInt('id');
		$this->setState('company.id', $id);

		$packageId = JRequest::getInt('filter_package');
		if(isset($packageId)){
			$this->setState('company.packageId', $packageId);
		}
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
		$itemId = (!empty($itemId)) ? $itemId : (int) $this->getState('company.id');
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
		$value = JArrayHelper::toObject($properties, 'JObject');
	
		$value->pictures = $this->getCompanyPictures($itemId);
		
		//dbg($this->_data->pictures);
		$value->videos = $this->getCompanyVideos($itemId);
		
		$activityCitiesTable = $this->getTable('CompanyActivityCity');
		$value->activityCities = $activityCitiesTable->getActivityCities($itemId);
		
		$contactTable = $this->getTable('CompanyContact',"Table");
		$value->contact = $contactTable->getCompanyContact($itemId);
				
		$countriesTable = $this->getTable('Country');
		$value->countries = $countriesTable->getCountries();
		
		$typesTable = $this->getTable('CompanyTypes');
		$value->types = $typesTable->getCompanyTypes();
		
		if($this->appSettings->enable_multilingual){
			JBusinessDirectoryTranslations::updateTypesTranslation($value->types);
		}
		
		$cityTable = $this->getTable('City');
		$value->cities = $cityTable->getCities();
		
		$companyLocationsTable = $this->getTable('CompanyLocations');
		$value->locations = $companyLocationsTable->getCompanyLocations($itemId);
		
		$companyCategoryTable = $this->getTable('CompanyCategory');
		if(!empty($itemId)){
			$value->selCats = $companyCategoryTable->getSelectedCategoriesList($itemId);
		}else{
			$value->selCats= array();
		}
		
		
		$companyCategoryTable = $this->getTable('CompanyCategory');
		$value->selectedCategories = $companyCategoryTable->getSelectedCategories($itemId);
		if($this->appSettings->enable_multilingual){
			JBusinessDirectoryTranslations::updateCategoriesTranslation($value->selectedCategories);
		}
		
		$packageId = $this->getState('company.packageId');
	
		if($packageId==0){
			$this->setState('company.packageId',$value->package_id);
			$packageId = $value->package_id;
		}
		
		$value->defaultAtrributes = $this->getAttributeConfiguration();

		if($this->appSettings->enable_packages){
			if($packageId !=0){
				$value->package = $this->getPackage($packageId);
			}else{
				$value->package = $this->getDefaultPackage();
			}
		
			if($this->getState('company.id') > 0 && !empty($value->package->id)){
				$value->paidPackage = $this->getPackagePayment($this->getState('company.id'), $value->package->id);
				$value->lastActivePackage  =  $this->getLastActivePackage($this->getState('company.id'));
				$this->checkBusinessListing($value->package, $value->lastActivePackage, $value->paidPackage);
			}
		}
		
		$attributesTable = $this->getTable('CompanyAttributes');
		$value->customFields = $attributesTable->getCompanyAttributes($itemId);
		
		if($this->appSettings->enable_multilingual){
			JBusinessDirectoryTranslations::updateAttributesTranslation($value->customFields);
		}
		
		//check if custom fields are contained on packages
		$value->containsCustomFields = false;
		if($this->appSettings->enable_packages){
			foreach($value->customFields as $attribute){
				if(!empty($value->package->features) && in_array($attribute->code,$value->package->features)){
					$value->containsCustomFields = true;
					break;
				}
			}
		}else{
			$value->containsCustomFields = true;
		}
		
		$value->attachments = JBusinessDirectoryAttachments::getAttachments(BUSSINESS_ATTACHMENTS, $itemId);
		if(!empty($value->business_hours))
			$value->business_hours = explode(",",$value->business_hours);
		
		return $value;
	}

	function getAttributeConfiguration(){
		$defaultAttributesTable = JTable::getInstance('DefaultAttributes','Table');
		$attributesConfiguration = $defaultAttributesTable->getAttributesConfiguration(); 
		$defaultAtrributes= array();
		if(isset($attributesConfiguration) && count($attributesConfiguration)>0){
			foreach($attributesConfiguration as $attrConfig){
				$defaultAtrributes[$attrConfig->name] = $attrConfig->config;
			}
		}
		
		return $defaultAtrributes;
	}
	
	public function getPackage($packageId){
		$packageTable = $this->getTable("Package");
		$packageTable->load($packageId);
		$properties = $packageTable->getProperties(1);
		$value = JArrayHelper::toObject($properties, 'JObject');
		
		$packageTable = $this->getTable("Package");
		$value->features = $packageTable->getSelectedFeaturesAsString($packageId);
		
		if(isset($value->features))
			$value->features = explode(",",$value->features);

		if(!is_array($value->features)){
			$value->features = array($value->features);
		}
		
		return $value;
	}
	
	public function getDefaultPackage(){
		$packageTable = $this->getTable("Package");
		$package = $packageTable->getDefaultPackage();

		$packageTable = $this->getTable("Package");
		$package->features = $packageTable->getSelectedFeaturesAsString($package->id);
	
		if(isset($package->features))
			$package->features = explode(",",$package->features);
	
		if(!is_array($package->features)){
			$package->features = array($package->features);
		}
		
		return $package;
	}
	
	
	public function getPackagePayment($companyId, $packageId){
		$packageTable = $this->getTable("Package");
		$package = $packageTable->getPackagePayment($companyId, $packageId);
		
		if(!$package)
			return null;
		
		$package->expirationDate = date('Y-m-d', strtotime($package->start_date. ' + '.$package->days.' days'));
		$package->expired = strtotime($package->expirationDate) <= time();
		
		return $package;
	}
	
	public function getLastActivePackage($companyId){
		$packageTable = $this->getTable("Package");
		$package = $packageTable->getLastActivePackage($companyId);
		
		if(!$package)
			return null;
		
		$package->expirationDate = date('Y-m-d', strtotime($package->start_date. ' + '.$package->days.' days'));
		$package->expired = strtotime($package->expirationDate) <= time();
		
		return $package;
	}
	
	public function getLastPackage($companyId){
		$packageTable = $this->getTable("Package");
		$package = $packageTable->getLastPackage($companyId);
	
		if(!$package)
			return null;
	
		$package->expirationDate = date('Y-m-d', strtotime($package->start_date. ' + '.$package->days.' days'));
		$package->expired = strtotime($package->expirationDate) <= time();
	
		return $package;
	}
	
	
	public function extendPeriod($data){
		$this->createOrder($data["id"], $data["filter_package"], UPDATE_TYPE_EXTEND);
	}
	
	public function getPackages(){
		$packageTable = $this->getTable("Package");
		$packages = $packageTable->getPackages();
		
		return $packages;
	}
	
	function checkBusinessListing($currentPackage, $lastPackage, $packageP){
		$packages = $this->getPackages();
		$freePackage = null;
		foreach($packages as $package){
			if($package->price == 0)
				$freePackage = $package;
		}

		if(!isset($freePackage) && isset($lastPackage) && $lastPackage->expired){
			JFactory::getApplication()->enqueueMessage(JText::_('LNG_BUSINESS_NOT_SHOWN'), 'message');
		}
		
		if(!isset($packageP) && $currentPackage->price>0){
			JFactory::getApplication()->enqueueMessage(JText::_('LNG_BUSINESS_FEATURES_NOT_SHOWN'), 'message');
		}
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
		//exit;
		// The folder and element vars are passed when saving the form.
		if (empty($data))
		{
			$item		= $this->getItem();
			// The type should already be set.
		}
		// Get the form.
		$form = $this->loadForm('com_jbusinessdirectory.company', 'item', array('control' => 'jform', 'load_data' => $loadData), true);
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
		$data = JFactory::getApplication()->getUserState('com_jbusinessdirectory.edit.company.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		return $data;
	}

	function getClaimDetails(){
		$companiesTable = $this->getTable("Company");
		return $companiesTable->getClaimDetails((int) $this->getState('company.id'));
	}
	
	function getCompanyPictures($companyId){
		$query = "SELECT * FROM #__jbusinessdirectory_company_pictures
				WHERE companyId =".$companyId ."
				ORDER BY id ";
		$files =  $this->_getList( $query );
		$pictures			= array();
		foreach( $files as $value )
		{
			$pictures[]	= array(
					'company_picture_info' 		=> $value->picture_info,
					'company_picture_path' 		=> $value->picture_path,
					'company_picture_enable'	=> $value->picture_enable,
			);
		}
	
		return $pictures;
	}
	
	function getCompanyVideos($companyId){
		$query = "SELECT * FROM #__jbusinessdirectory_company_videos
					WHERE companyId =".$companyId ."
					ORDER BY id "
					;
		
		$files =  $this->_getList( $query );
		return $files;
	}
	
	function deleteCompany(){
		$companiesTable = $this->getTable("Company");
		return $companiesTable->deteleCompany((int) $this->getState('company.id'));
	}
	
	/**
	 * Check for duplicate alias and generate a new alias
	 * @param unknown_type $busienssId
	 * @param unknown_type $alias
	 */
	function checkAlias($busienssId, $alias){

		$companiesTable = $this->getTable();
		while($companiesTable->checkIfAliasExists($busienssId, $alias)){
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
		$id	= (!empty($data['id'])) ? $data['id'] : (int) $this->getState('company.id');
		$isNew = true;
		$createOrder = false;
		$data["alias"] = !empty($data["alias"])?$data["alias"]:"";
		$data["alias"]= JBusinessUtil::getAlias($data["name"],$data["alias"]);
		$data["alias"] = $this->checkAlias($id, $data["alias"]);
		
		$data["modified"]=date("Y-m-d H:i:s");
		$data["publish_only_city"]=JRequest::getVar("publish_only_city",0);

		$this->appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		if(!empty($data["business_hours"][0])){
			$data["business_hours"] = implode(",",$data["business_hours"] );
		}else{
			$data["business_hours"]="";
		}
		
		$defaultLng = JFactory::getLanguage()->getDefault();
		$description = 	JRequest::getVar( 'description_'.$defaultLng, '', 'post', 'string', JREQUEST_ALLOWHTML);
		if(!empty($description) && empty($data["description"]))
			$data["description"] = $description;
		
		$shortDescription = 	JRequest::getVar( 'short_description_'.$defaultLng, '', 'post', 'string', JREQUEST_ALLOWHTML);
		if(!empty($shortDescription) && empty($data["short_description"]))
			$data["short_description"] = $shortDescription;
		
		$slogan = 	JRequest::getVar( 'slogan_'.$defaultLng, '', 'post', 'string', JREQUEST_ALLOWHTML);
		if(!empty($slogan) && empty($data["slogan"]))
			$data["slogan"] = $slogan;
		
		// Get a row instance.
		$table = $this->getTable();
		
		// Load the row if saving an existing item.
		if ($id > 0)
		{
			$table->load($id);
			$isNew = false;
		}
		
		if(isset($data["filter_package"])){
			if($isNew || $table->package_id != $data["filter_package"]){
				$createOrder = true;
			}
			$data["package_id"]=$data["filter_package"];
		}
		
		// Bind the data.
		if (!$table->bind($data))
		{
			$this->setError($table->getError());
			dump($table->getError());
			return false;
		}

		// Check the data.
		if (!$table->check())
		{
			$this->setError($table->getError());
			dump($table->getError());
			return false;
		}

		// Store the data.
		if (!$table->store())
		{
			$this->setError($table->getError());
			dump($table->getError());
			return false;
		}
		
		$id =  $table->id;
		$this->setState('company.id', $table->id);

		// Clean the cache
		$this->cleanCache();
		$properties = $table->getProperties(1);
		$company = JArrayHelper::toObject($properties, 'JObject');
		
		$lastPackage = $this->getLastPackage($company->id);
		if($this->appSettings->enable_packages && ($isNew || $createOrder || $lastPackage->expired)){
			$type = $isNew? UPDATE_TYPE_NEW: UPDATE_TYPE_UPGRADE;
			$redirect= $this->createOrder($company->id, $company->package_id, $type);
			if($redirect){
				$this->setState('company.redirect.payment',1);
			}
		}
		
		JBusinessDirectoryTranslations::saveTranslations(BUSSINESS_DESCRIPTION_TRANSLATION, $table->id, 'description_');
		JBusinessDirectoryTranslations::saveTranslations(BUSSINESS_SLOGAN_TRANSLATION, $table->id, 'slogan_');
		JBusinessDirectoryAttachments::saveAttachments(BUSSINESS_ATTACHMENTS, BUSINESS_ATTACHMENTS_PATH, $table->id, $data, $id);
		//save in companycategory table
		$table = $this->getTable('CompanyCategory');
		//dump($data["selectedSubcategories"]);
		if(!empty($data["selectedSubcategories"]))
			$table->insertRelations( $this->getState('company.id'), $data["selectedSubcategories"]);
		
		try{
			if(!isset($data["activity_cities"]))
				$data["activity_cities"]= array(-1);
			$this->storeActivityCities($this->getState('company.id'),$data["activity_cities"]);
			
			if(isset($data['contact_name']) && count($data['contact_name'])>0){
				$this->storeCompanyContact($data,  $this->getState('company.id'));
			}
			if(isset($data['images_included']) && count($data['pictures'])>0 || (isset($data['company-deleted']) && $data['company-deleted']==1)){
				$oldId = $isNew?0:$id;
				$this->storePictures($data,  $this->getState('company.id'), $oldId);
			}
			if(isset($data['videos-included'])) {
				$this->storeVideos($data,  $this->getState('company.id'));
			}
			$this->storeAttributes($this->getState('company.id'), $data);
		}catch( Exception $ex ){
			$this->setError($ex);
		}
		
		$post = JRequest::get("post");
		$controller = substr($post["task"], 0,strpos($post["task"], "."));
		$company=  $this->getItem($company->id);

		if($isNew && empty($data["no-email"])){
			if($controller == "managecompany"){
				EmailService::sendNewCompanyNotificaitonEmailToAdmin($company);
				EmailService::sendNewCompanyNotificaitonEmailToOwner($company);
			}else{
				EmailService::sendNewCompanyNotificaitonEmailToOwner($company);
			}	
		}
		
		return $id;
	}
	
	function createOrder($companyId, $packageId, $type){
		
		$companyTable = $this->getTable("Company");
		$company = $companyTable->getCompany($companyId);
		
		$packageTable = $this->getTable("Package");
		$package = $packageTable->getPackage($packageId);
		
		if(empty($package) || $package->price == 0){
			return false;
		}
		
		$orderId = JText::_("LNG_UPGRADE")."-".time()%10000;
		$serviceName = $company->name;
		$desription = JText::_("LNG_UPGRADE")."-".JText::_("LNG_PACKAGE").": ".$package->name;
		
		if($type == UPDATE_TYPE_NEW){
			$orderId = JText::_("LNG_NEW_LISTING")."-".time()%10000;
			$desription = JText::_("LNG_NEW_LISTING")."-".JText::_("LNG_PACKAGE").": ".$package->name;
		}else if($type == UPDATE_TYPE_EXTEND){
			$orderId = JText::_("LNG_EXTEND_PERIOD")."-".time()%10000;
			$desription = JText::_("LNG_EXTEND_PERIOD")."-".JText::_("LNG_PACKAGE").": ".$package->name;
		}
		
		$orderId = $desription;
		
		$lastPaidPackage = $packageTable->getLastActivePackage($company->id);
		$start_date = date("Y-m-d");
		$remainingAmount = 0;
		if(isset($lastPaidPackage)){
			$lastActiveDay = date('Y-m-d', strtotime($lastPaidPackage->start_date. ' + '.$lastPaidPackage->days.' days'));
			if(strtotime(date("Y-m-d"))<=strtotime($lastActiveDay)){
				$start_date = $lastActiveDay;
			}else{
				$start_date = date("Y-m-d");
			}
			
			if($type == UPDATE_TYPE_UPGRADE && strtotime(date("Y-m-d"))<strtotime($lastActiveDay)){
				$start_date = date("Y-m-d");	
		
				$remainingDays = floor((strtotime($lastActiveDay) - strtotime(date("Y-m-d")))/ (60 * 60 * 24));
				if($remainingDays>0){
					$remainingAmount = $lastPaidPackage->price/$lastPaidPackage->days * $remainingDays;
					$remainingAmount = $remainingAmount  + $appSettings->vat*$remainingAmount /100;
				}
				
				//the same upgrade package as paid package
				if($lastPaidPackage->package_id == $packageId)
					return false;
			}
			
			
		}
		
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		$user = JFactory::getUser($company->userId);
		$table=$this->getTable("Order");
		$table->deleteOldOrders($company->id);
		
		$table=$this->getTable("Order");
		$data["order_id"]= $orderId;
		$data["company_id"]= $company->id;
		$data["package_id"]= $company->package_id;
		
		//if it's an upgrade calculate the price minus the remaining days.  
		if($type == UPDATE_TYPE_UPGRADE){
			$data["initial_amount"]= $package->price- $remainingAmount;
			$data["vat_amount"]= $appSettings->vat*$data["initial_amount"]/100;
			$data["amount"]= $data["initial_amount"] + $data["vat_amount"];
		}else{
			$data["initial_amount"]= $package->price;
			$data["vat_amount"]= $appSettings->vat*$package->price/100;
			$data["amount"]= $package->price + $appSettings->vat*$package->price/100;
		}
		
		//exit;
		$data["state"]= 0;
		$data["start_date"]=$start_date;
		$data["user_name"]= $user->name;
		$data["service"]= $serviceName;
		$data["description"]=$desription;
		$data["type"]=$type;
		$data["currency"] = $appSettings->currency_name;
		
		
		//dump($data);
		//exit;
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
		
		return true; 
	}

	function storeAttributes($companyId, $data){
		#delete all ad attributes
		$adTableAttr =$this->getTable('CompanyAttributes');
		if(!$adTableAttr->deleteCompanyAttributes($companyId))
			$this->setError(JText::_("LNG_ERROR_DELETING_AD_ATTRIBUTES").$this->_db->getErrorMsg());

		foreach($data as $key=>$value){
			#save ad attributes
			if(strpos($key,"attribute")===0){
				$attributeArr = explode("_", $key);
				//print_r($attributeArr);
				$companyAttributeTable =$this->getTable('CompanyAttributes');
				$companyAttributeTable->company_id= $companyId;
				$companyAttributeTable->option_id= $value;
				$companyAttributeTable->value= $value;
				$companyAttributeTable->attribute_id= $attributeArr[1];
				
				if(is_array($companyAttributeTable->value)){
					$companyAttributeTable->value = implode(",", $companyAttributeTable->value);
				}
				
				$properties = $companyAttributeTable->getProperties(1);
				$value = JArrayHelper::toObject($properties, 'JObject');

				if(!$companyAttributeTable->store())
					$this->setError(JText::_("LNG_ERROR_SAVING_AD_ATTRIBUTES").$this->_db->getErrorMsg());
			}
		}
	}
	
	function storeCompanyContact($data, $companyId){
	
		$row = $this->getTable('CompanyContact',"Table");
		$data["companyId"]= $companyId;
		$key = array("companyId"=>$companyId);
		$data["id"]=null;
		$row->load($key,true);
		
		if (!$row->bind($data))
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
	}
	
	function storeActivityCities($companyId, $cities){
		$companyActivityCity = $this->getTable('CompanyActivityCity',"JTable");
		if(!is_array($cities)){
			$cities = array($cities);
		}
		$companyActivityCity->deleteNotContainedCities($companyId, $cities);
		
		foreach($cities as $city){
			
			$row = $this->getTable('CompanyActivityCity',"JTable");
			
			$obj = $row->getActivityCity($companyId,$city);
			
			if(!empty($obj)){
				continue;
			}
			$obj = new stdClass();
			$obj->company_id = $companyId;
			$obj->city_id = $city;
			
			if (!$row->bind($obj)){
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
			if (!$row->store(true))
			{
				throw( new Exception($this->_db->getErrorMsg()) );
				$this->setError($this->_db->getErrorMsg());
			}
		}
		
	}
	
	function storeVideos($data, $companyId){
	
		$table = $this->getTable('CompanyVideos');
		$table->deleteAllForCompany($companyId);
	
		foreach( $data['videos'] as $value ){
			if(empty($value)){
				continue;
			}
			
			$row = $this->getTable('CompanyVideos');
				
			$video = new stdClass();
			$video->id =0;
			$video->companyId = $companyId;
			$video->url = $value;
				
			if (!$row->bind($video))
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
		}
	}
	
	function storePictures($data, $companyId, $oldId){
		//dump("store pcis");
		//dump($companyId);
		//dump($oldId);
		
		//prepare photos
		$path_old = JBusinessUtil::makePathFile(JPATH_ROOT."/".PICTURES_PATH .COMPANY_PICTURES_PATH.($oldId)."/");
		$files = glob( $path_old."*.*" );
	
		$path_new = JBusinessUtil::makePathFile(JPATH_ROOT."/".PICTURES_PATH .COMPANY_PICTURES_PATH.($companyId)."/");
	
		
		$picture_ids 	= array();
		foreach($data['pictures'] as $value )
		{
			$row = $this->getTable('CompanyPictures');
	
			$pic 						= new stdClass();
			$pic->id		= 0;
			$pic->companyId 				= $companyId;
			$pic->picture_info	= $value['company_picture_info'];
			$pic->picture_path	= $value['company_picture_path'];
			$pic->picture_enable	= $value['company_picture_enable'];
				
			$file_tmp = JBusinessUtil::makePathFile( $path_old.basename($pic->picture_path) );
			//dump($file_tmp);
			if( !is_file($file_tmp) )
				continue;
			//dump("is file");
			if( !is_dir($path_new) )
			{
				if( !@mkdir($path_new) )
				{
					throw( new Exception($this->_db->getErrorMsg()) );
				}
			}
	
			//dbg(($path_old.basename($pic->picture_path).",".$path_new.basename($pic->picture_path)));
			// exit;
			if( $path_old.basename($pic->picture_path) != $path_new.basename($pic->picture_path) )
			{
				if(@rename($path_old.basename($pic->picture_path),$path_new.basename($pic->picture_path)) )
				{
	
					$pic->picture_path	 = COMPANY_PICTURES_PATH.($companyId).'/'.basename($pic->picture_path);
					//@unlink($path_old.basename($pic->room_picture_path));
				}
				else
				{
					throw( new Exception($this->_db->getErrorMsg()) );
				}
			}
			//dump("save");
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
		//exit;	
		$files = glob( $path_new."*.*" );
			
		foreach( $files as $pic )
		{
			$is_find = false;
			foreach( $data['pictures'] as $value )
			{
				if(isset($value['picture_path']) &&  $pic == JBusinessUtil::makePathFile(JPATH_ROOT."/".PICTURES_PATH .$value['picture_path']) )
				{
					$is_find = true;
					break;
				}
			}
			if( $is_find == false )
				@unlink( JBusinessUtil::makePathFile(JPATH_ROOT."/".PICTURES_PATH .$value['picture_path']) );
		}
	
		//dump($picture_ids);
	
		$query = " DELETE FROM #__jbusinessdirectory_company_pictures
		WHERE companyId = '".$companyId."'
		".( count($picture_ids)> 0 ? " AND id NOT IN (".implode(',', $picture_ids).")" : "");
	
		 //dbg($query);
		 //exit;
		$this->_db->setQuery( $query );
		if (!$this->_db->query())
		{
			throw( new Exception($this->_db->getErrorMsg()) );
		}
		//~prepare photos
		//exit;
	}
	
	function changeState(){
		$id = JRequest::getInt('id');
		
		$companiesTable = $this->getTable("Company");
		return $companiesTable->changeState($id);
	}
	
	function changeFeaturedState(){
		$id = JRequest::getInt('id');
	
		$companiesTable = $this->getTable("Company");
		return $companiesTable->changeFeaturedState($id);
	}
	
	
	function changeAprovalState($state){
		$this->populateState();
		$companiesTable = $this->getTable("Company");
		return $companiesTable->changeAprovalState($this->getState('company.id'), $state);
	}
	
	function changeClaimAprovalState($state){
		$this->populateState();
		$companiesTable = $this->getTable("Company");
		$claimDetails = $companiesTable->getClaimDetails($this->getState('company.id'));
		$companiesTable->changeClaimState($this->getState('company.id'), $state);
		
		if($state == -1){
			$this->sendClaimResponseEmail($this->getState('company.id'), $claimDetails, "Claim Negative Response Email");
			$companiesTable->resetCompanyOwner($this->getState('company.id'));
		}else{
			$this->sendClaimResponseEmail($this->getState('company.id'), $claimDetails, "Claim Response Email");
		}
	}
	
	function checkCompanyName($companyName){
		$companiesTable = $this->getTable("Company");
		return $companiesTable->checkCompanyName($companyName);
	}
	
	
	function sendClaimResponseEmail($companyId, $claimDetails, $template){
		$email = new stdClass();
	
		$templ = $this->getEmailTemplate($template );
	
		if( $templ ==null )
			return null;
	
		$companyTable = $this->getTable("Company");
		$company = $companyTable->getCompany($companyId);
		
		$email->content = $this->prepareEmail($company, $templ->email_content);
		$email->content = EmailService::updateCompanyDetails($email->content);
		
		$email->subject = $templ->email_subject;
		$email->to = $claimDetails->email;
		$applicationSettings = $this->getAppSettings();
		$email->company_email = $applicationSettings->company_email;
		$email->company_name = $applicationSettings->company_name;
		$result = $this->sendEMail($email);
		
		return $email;
	}
	
	
	function getEmailTemplate( $template)
	{
		$query = ' SELECT * FROM #__jbusinessdirectory_emails WHERE email_type = "'.$template.'"';
		$this->_db->setQuery( $query );
		$templ=  $this->_db->loadObject();
		return $templ;
	}
	
	function sendEmail($email)
	{
	
		$mode		 = 1 ;//html

		$ret = EmailService::sendEmail(
				$email->company_email,
				$email->company_name,
				$email->company_email,
				$email->to,
				null,
				null,
				$email->subject,
				$email->content,
				$mode
		);
		return $ret;
	}
	
	function prepareEmail($company, $templEmail)
	{
		$fistName="";
		$lastName="";
			
		$templEmail = str_replace(htmlspecialchars(EMAIL_FIRST_NAME),				$fistName,				$templEmail);
		$templEmail = str_replace(EMAIL_FIRST_NAME, 								$fistName,				$templEmail);
	
		$templEmail = str_replace(htmlspecialchars(EMAIL_LAST_NAME), 				$lastName,				$templEmail);
		$templEmail = str_replace(EMAIL_LAST_NAME, 									$lastName,				$templEmail);
	
		$applicationSettings = $this->getAppSettings();
	
		$templEmail = str_replace(htmlspecialchars(EMAIL_COMPANY_NAME),				$applicationSettings->company_name,			$templEmail);
		$templEmail = str_replace(EMAIL_COMPANY_NAME,								$applicationSettings->company_name, 		$templEmail);
	
		$templEmail = str_replace(htmlspecialchars(EMAIL_CLAIMED_COMPANY_NAME),				$company->name,			$templEmail);
		$templEmail = str_replace(EMAIL_CLAIMED_COMPANY_NAME,								$company->name, 		$templEmail);
	
		return "<div style='width: 600px;'>".$templEmail.'</div>';
	}
	
	function getAppSettings()
	{
		// Load the data
	
		$query = ' SELECT * FROM #__jbusinessdirectory_applicationsettings ';
		$this->_db->setQuery( $query );
		$appSettings =  $this->_db->loadObject();
	
		return $appSettings;
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
			
			if (!$table->deleteAllDependencies($itemId))
			{
				$this->setError($table->getError());
				return false;
			}
		}
		
		// Clean the cache
		$this->cleanCache();

		return true;
	}
	
	/**
	 * Delete business listing files
	 * @param $itemId
	 * @return boolean
	 */
	function deleteFiles($itemId){
		$imagesDir = JPATH_ROOT."/".PICTURES_PATH .COMPANY_PICTURES_PATH.($itemId);
		JBusinessUtil::removeDirectory($imagesDir);
		
		$attachmentDir = JPATH_ROOT."/".ATTACHMENT_PATH .BUSINESS_ATTACHMENTS_PATH.$itemId;
		JBusinessUtil::removeDirectory($attachmentDir);

		return true;
	}

	
	function importCompanies($filePath, $delimiter){
		//dump($comapanies); 
		$categories = $this->getCategories();
		$companyTypes = $this->getCompanyTypes();
		$packages = $this->getPackagesByName();
		$countries = $this->getCountries();
		//dump($countries);
		$newCategoyCount = 0;
		$newSubcategoryCount = 0;
		$newTypesCount = 0;
		$newCompaniesCount = 0;
		$mainSubcategory = 0;
		//dump($categories);
	
		ini_set("auto_detect_line_endings", "1");
		
		$row = 1;
		if (($handle = fopen($filePath, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 3000, $delimiter)) !== FALSE) {
				$company = array();
				if($row==1){
					$header = $data;
					$row++;
					continue;
				}
				$num = count($data);
				//dump($data);
				//echo "<p> $num fields in line $row: <br /></p>\n";
				$row++;
				for ($c=0; $c < $num; $c++) {
					$company[strtolower($header[$c])]= $data[$c];
				}
		
				$categoryIds = array();
				//dump($company);
				//exit;
				//dump($company["categories"]);
				if(!empty($company["categories"])){
					$categoriesNames = explode(",", $company["categories"]);
					foreach($categoriesNames as $category){
						if(empty($category)){
							continue;
						}
							
						//dump("search ".$category);
						$cat = $this->getCategoryByName($categories, $category);
					//	dump($cat[0]->name);
						if(!isset($cat))
							continue;
						$categoryIds[] = $cat[0]->id;
						
						if(!empty($company["mainsubcategory"]) && $company["mainsubcategory"]== $category){
							$mainSubcategory =$cat[0]->id;
						}
					}
				}
				
				$typeId = 0;
				if(isset($company["type"])){
					$companyTypeId=0;
					if(!isset($companyTypes[$company["type"]])){
						$this->addCompanyType($company["type"], count($companyTypes));
						$companyTypes = $this->getCompanyTypes();
						$newTypesCount++;
					}
					$typeId = $companyTypes[$company["type"]]->id;
				}
					
				$package_id = 0;
				if(isset($company["package"])){
					if(isset($packages[$company["package"]])){
						$package_id = $packages[$company["package"]]->id;
					}
				}
				
				$countryId = 0;
				if(isset($company["country"])){
					if(isset($countries[$company["country"]])){
						$countryId = $countries[$company["country"]]->id;
					}
				}
				//dump($company);
				$categoryData = array();
				$categoryData["name"]=isset($company["name"])?$company["name"]:"";
				$company["alias"] = !empty($company["alias"])?$company["alias"]:"";
				$categoryData["alias"]=JBusinessUtil::getAlias($company["name"],$company["alias"]);
				$categoryData["comercialName"]=isset($company["commercial name"])?$company["commercial name"]:"";
				$categoryData["registrationCode"]=isset($company["registration code"])?$company["registration code"]:"";
				$categoryData["slogan"]=isset($company["slogan"])?$company["slogan"]:"";
				$categoryData["description"]=isset($company["description"])?$company["description"]:"";
				$categoryData["short_description"]=isset($company["short description"])?$company["short description"]:"";
				$categoryData["street_number"]=isset($company["street number"])?$company["street number"]:"";
				$categoryData["address"]=isset($company["address"])?$company["address"]:"";
				if(isset($company["address 2"]))
					$categoryData["address"]= $categoryData["address"].', '.$company["address 2"];
				$categoryData["city"]=isset($company["city"])?$company["city"]:"";
				$categoryData["county"]=isset($company["region"])?$company["region"]:"";
				$categoryData["countryId"]=$countryId;
				$categoryData["website"]=isset($company["website"])?$company["website"]:"";
				$categoryData["keywords"]=isset($company["keywords"])?$company["keywords"]:"";
				$categoryData["phone"]=isset($company["phone"])?$company["phone"]:"";
				$categoryData["mobile"]=isset($company["mobile"])?$company["mobile"]:"";
				$categoryData["email"]=isset($company["email"])?$company["email"]:"";
				$categoryData["fax"]=isset($company["fax"])?$company["fax"]:"";
				$categoryData["typeId"]= $typeId;
				$categoryData["mainSubcategory"]  = $mainSubcategory;
				$categoryData["latitude"]=isset($company["latitude"])?$company["latitude"]:"";
				$categoryData["longitude"]=isset($company["longitude"])?$company["longitude"]:"";
				
				$categoryData["userId"]=isset($company["user"])?$company["user"]:"";
				$categoryData["averageRating"]=isset($company["averageRating"])?$company["averageRating"]:"";
				$categoryData["viewCount"]=isset($company["views"])?$company["views"]:"";
				$categoryData["filter_package"]=$package_id;
				$categoryData["facebook"]=isset($company["facebook"])?$company["facebook"]:"";
				$categoryData["twitter"]=isset($company["twitter"])?$company["twitter"]:"";
				$categoryData["googlep"]=isset($company["googlep"])?$company["googlep"]:"";
				$categoryData["postalCode"]=isset($company["postal code"])?$company["postal code"]:"";
				$categoryData["logoLocation"]=isset($company["logolocation"])?$company["logolocation"]:"";
				$categoryData["contact_name"]=isset($company["contact_name"])?$company["contact_name"]:"";
				$categoryData["contact_email"]=isset($company["contact_email"])?$company["contact_email"]:"";
				$categoryData["contact_phone"]=isset($company["contact_phone"])?$company["contact_phone"]:"";
				$categoryData["contact_fax"]=isset($company["contact_fax"])?$company["contact_fax"]:"";
					
				$categoryData["state"] =isset($company["state"])?$company["state"]:1;
				$categoryData["approved"] =isset($company["approved"])?$company["approved"]:2; 
				$categoryData["selectedSubcategories"] = $categoryIds;
				
				if(empty($categoryData["latitude"]) && empty($categoryData["longitude"]) && !empty($categoryData["postalCode"])){
						$location = JBusinessUtil::getCoordinates($categoryData["postalCode"]);		
						$categoryData["latitude"] = $location["latitude"];
						$categoryData["longitude"] = $location["longitude"];
				}
				
				$categoryData["no-email"]=1;
				
				//load custom attributes
				$attributesTable = JTable::getInstance("Attribute","JTable");
				$attributes = $attributesTable->getAttributesWithTypes();
				
				$attributesTable = JTable::getInstance("AttributeOptions","JTable");
				$attributeOptions = $attributesTable->getAllAttributesWithOptions();
				foreach($attributes as $attribute){
					$attribute->name = strtolower($attribute->name);
					//dump($attribute->name);
				//	dump($company[$attribute->name]);
					if(!empty($company[$attribute->name])){
						$attrValues = $company[$attribute->name];
						$attrValues = explode(",",$attrValues);
						//dump($attrValues);
						foreach($attrValues as $value){
							//dump($attribute->code);
							if($attribute->attr_type=="input" || $attribute->attr_type=="textarea"){
								$categoryData["attribute_".$attribute->id]=$value;
							}
							else	{
								foreach($attributeOptions as $attributeOption){
									if($attributeOption->attr_id == $attribute->id && $attributeOption->name==$value){
										$categoryData["attribute_".$attribute->id][]=$attributeOption->id;
									}
								}
							}
						}
					}
				}
				
				//dump($categoryData);
				//exit;
				try{
					$this->setState('company.id',0);
					if($this->save($categoryData)){
						$newCompaniesCount ++;
					}
				}catch(Exception $e){
					dump($e);
				}		
				
			}
			fclose($handle);
		}	
		
		$result = new stdClass();
		$result->newCategories = $newCategoyCount;
		$result->newSubCategories = $newSubcategoryCount;
		$result->newTypes = $newTypesCount;
		$result->newCompanies = $newCompaniesCount;
		return $result;
	
	}
	
	function getCategories(){
		$categoryService = new JBusinessDirectorCategoryLib();
		$categoryTable = $this->getTable("Category","JBusinessTable");
		$categories = $categoryTable->getAllCategories();
		$categories = $categoryService->processCategoriesByName($categories);
		return $categories;
	}
	
	function getCategoryByName($categories, $categoryName){
		$categoryService = new JBusinessDirectorCategoryLib();
		$cat = null;
		$category = $categoryService->findCategoryByName($categories, $cat, $categoryName);
	
		return $category;
	}
	
	function addCompanyType($name,$ordering){
		$table = $this->getTable("CompanyType");
	
		$type = array();
		$type["name"] = $name;
		$type["ordering"] = $ordering;
	
		if (!$table->bind($type))
		{
			throw( new Exception($this->_db->getErrorMsg()) );
			$this->setError($this->_db->getErrorMsg());
		}
		// Make sure the record is valid
		if (!$table->check())
		{
			throw( new Exception($this->_db->getErrorMsg()) );
			$this->setError($this->_db->getErrorMsg());
		}
	
		// Store the web link table to the database
		if (!$table->store())
		{
			throw( new Exception($this->_db->getErrorMsg()) );
			$this->setError($this->_db->getErrorMsg());
		}
	
		return $table->id;
	}
	
	function getCompanyTypes(){
		$result = array();
		$companyTypesTable = $this->getTable("CompanyTypes");
		$companyTypes = $companyTypesTable->getCompanyTypes();
		foreach($companyTypes as $companyType){
			$result[$companyType->name] = $companyType;
		}
	
		return $result;
	}
	
	function getCountries(){
		$result = array();
		$countriesTable = $this->getTable("Country");
		$countries = $countriesTable->getCountries();
		foreach($countries as $country){
			$result[$country->country_name] = $country;
		}
		
		return $result;
	}
	
	function getPackagesByName(){
		$result = array();
		$packageTable = $this->getTable("Package");
		$packages = $packageTable->getPackages();
	
		foreach($packages as $package){
			$result[$package->name] = $package;
		}
	
		return $result;
	}

	
	function getLocation(){
		$locationId = JRequest::getVar("locationId",0);
		// Get a menu item row instance.
		$table = $this->getTable("CompanyLocations");
		
		
		// Attempt to load the row.
		$return = $table->load($locationId);
		
		// Check for a table object error.
		if ($return === false && $table->getError())
		{
			$this->setError($table->getError());
			return $false;
		}
		
		$properties = $table->getProperties(1);
		$value = JArrayHelper::toObject($properties, 'JObject');
		
		return $value;
	}
	
	/**
	 * Method to save the form data.
	 *
	 * @param   array  The form data.
	 * @return  boolean  True on success.
	 */
	public function saveLocation($data)
	{
		$id	= $data['locationId'];
		// Get a row instance.
		$table = $this->getTable("CompanyLocations");
	
		// Load the row if saving an existing item.
		if ($id > 0)		{
			$table->load($id);
		}
		// Bind the data.
		if (!$table->bind($data))		{
			$this->setError($table->getError());
			dump($table->getError());
			return false;
		}
	
		// Check the data.
		if (!$table->check())		{
			$this->setError($table->getError());
			dump($table->getError());
			return false;
		}
	
		// Store the data.
		if (!$table->store())		{
			$this->setError($table->getError());
			dump($table->getError());
			return false;
		}
	
		return $table->id;
	}
	
	function deleteLocation($locationId){
		$table = $this->getTable("CompanyLocations");
		return $table->delete($locationId);
	}
	
	/**
	 * Method to perform batch operations on an item or a set of items.
	 *
	 * @param   array  $commands  An array of commands to perform.
	 * @param   array  $pks       An array of item ids.
	 * @param   array  $contexts  An array of item contexts.
	 *
	 * @return  boolean  Returns true on success, false on failure.
	 *
	 * @since   12.2
	 */
	public function batch($vars, $pks, $contexts)
	{
		// Sanitize ids.
		$pks = array_unique($pks);
		JArrayHelper::toInteger($pks);
	
		// Remove any values of zero.
		if (array_search(0, $pks, true))
		{
			unset($pks[array_search(0, $pks, true)]);
		}
	
		if (empty($pks))
		{
			$this->setError(JText::_('JGLOBAL_NO_ITEM_SELECTED'));
	
			return false;
		}
	
		$done = false;
	
		// Set some needed variables.
		$this->user = JFactory::getUser();
		$this->table = $this->getTable();
		$this->tableClassName = get_class($this->table);
		
		$this->batchSet = true;
		// Parent exists so let's proceed
		while (!empty($pks))
		{
			// Pop the first ID off the stack
			$pk = array_shift($pks);
		
			$this->table->reset();
		
			// Check that the row actually exists
			if (!$this->table->load($pk))
			{
				if ($error = $this->table->getError())
				{
					// Fatal error
					$this->setError($error);
		
					return false;
				}
				else
				{
					// Not fatal error
					$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_BATCH_MOVE_ROW_NOT_FOUND', $pk));
					continue;
				}
			}
		
			// set new approval state
			if ($vars["approval_status_id"]!="")
			{
				$this->table->approved = $vars["approval_status_id"];
			}
			
			// set new approval state
			if ($vars["featured_status_id"]!="")
			{
				$this->table->featured = $vars["featured_status_id"];
			}
			
			// set new approval state
			if ($vars["state_id"]!="")
			{
				$this->table->state = $vars["state_id"];
			}
		
			// Check the row.
			if (!$this->table->check())
			{
				$this->setError($this->table->getError());
		
				return false;
			}
		
			// Store the row.
			if (!$this->table->store())
			{
				$this->setError($this->table->getError());
		
				return false;
			}
	
		}
		
		// Clean the cache
		$this->cleanCache();
		
		return true;
	}
}
