<?php
/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.modeladmin');

class JBusinessDirectoryModelApplicationSettings extends JModelAdmin { 
	function __construct() {
		parent::__construct();
		$array = JRequest::getVar('applicationsettings_id',  0, '', 'array');
		$this->setId((int)$array[0]);
	}
	function setId($applicationsettings_id) {
		// Set id and wipe data
		$this->_applicationsettings_id		= $applicationsettings_id;
		$this->_data						= null;
	}

	/**
	 * Method to get applicationsettings
	 * @return object with data
	 */
	function &getData() {
		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__jbusinessdirectory_applicationsettings';
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();	
		}
		
		$config = new JConfig();
		$this->_data->sendmail_from = $config->mailfrom;;
		$this->_data->sendmail_name = $config->fromname;

		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->applicationsettings_id 	= null;
			$this->_data->company_name				= null;
			$this->_data->company_email				= null;
			$this->_data->currency_id				= null;
			$this->_data->css_style					= null;
			$this->_data->css_module_style			= null;			
			$this->_data->show_frontend_language	= null;
			$this->_data->default_frontend_language	= null;
		}

		if( $this->_data) {
			$this->_data->currencies = array();
			$query = ' SELECT * FROM #__jbusinessdirectory_currencies';
			$this->_data->currencies = $this->_getList( $query );
			$this->_data->dateFormats = array();
			$query = ' SELECT * FROM #__jbusinessdirectory_date_formats';
			$this->_data->dateFormats = $this->_getList( $query );
			$query = ' SELECT * FROM #__jbusinessdirectory_default_attributes';
			$this->_data->defaultAtrributes = $this->_getList( $query );
		}
		$this->_data->css_styles 			= glob(JPATH_COMPONENT_SITE. DS.'assets'.DS.'*.css');
		$this->_data->css_module_styles 	= glob(JPATH_ROOT.DS.'modules'.DS.'mod_jbusinessdirectory'. DS.'assets'.DS.'*.css');
		$this->_data->languages 			= glob(JPATH_COMPONENT_ADMINISTRATOR. DS.'language'.DS.'*', GLOB_ONLYDIR);
			
		return $this->_data;
	}
	
	
	function store($data) {	
		$row = $this->getTable();

		$this->assignPackageToCompanies($data["package"]);
		
		// Bind the form fields to the table
		if (!$row->bind($data)) 
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		// Make sure the record is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		// Store the web link table to the database
		if (!$row->store()) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		$this->storeAttributeConfiguration($data);
		
		return true;
	}

	function getLanguages() {
		$path = JLanguage::getLanguagePath(JPATH_COMPONENT_ADMINISTRATOR);
		$dirs = JFolder::folders($path);
		foreach ($dirs as $dir) {
			if(strlen($dir) != 5){
				continue;
			}
			$iniFiles = JFolder::files( $path.DS.$dir, '.ini', false, false);
			$iniFiles = reset($iniFiles);
			if(empty($iniFiles)){ 
				continue;
			}
			$fileName = JFile::getName($iniFiles);
			$oneLanguage = new stdClass();
			$oneLanguage->language = $dir;
			$oneLanguage->name = substr($fileName, 0, 5);
			
			$languages[] = $oneLanguage;
		}
		return $languages;
	}

	function storeAttributeConfiguration($data) {
		foreach($data as $key=>$value){
			if(strpos($key, "attribute-", 0) === 0){
				$obj = new stdClass();
				$obj->id = substr($key,10);
				$obj->config = $value; 
			
				$table = $this->getTable("DefaultAttributes");
				// Bind the form fields to the table
				if (!$table->bind($obj))
				{
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
				// Make sure the record is valid
				if (!$table->check()) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
				// Store the web link table to the database
				if (!$table->store()) {
					$this->setError( $this->_db->getErrorMsg() );
					return false;
				}
			}
		}
	}

	public function assignPackageToCompanies($packageId) {
		if($packageId>0){
			$packageTable = JTable::getInstance("Package", "JTable");
			$packageTable->updateUnassignedCompanies($packageId);
		}
	}
	
	public function getForm($data = array(), $loadData = true) {
	}
}
?>