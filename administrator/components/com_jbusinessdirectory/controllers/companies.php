<?php


defined('_JEXEC') or die;


class JBusinessDirectoryControllerCompanies extends JControllerLegacy
{
	/**
	 * Display the view
	 *
	 * @param   boolean			If true, the view output will be cached
	 * @param   array  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController		This object to support chaining.
	 * @since   1.6
	 */
	public function display($cachable = false, $urlparams = false){
	}

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   1.6
	 */
	public function getModel($name = 'Companies', $prefix = 'JBusinessDirectoryModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}

	public function back(){
		$this->setRedirect('index.php?option=com_jbusinessdirectory');
	}
	
	/**
	 * Removes an item
	 */
	public function delete()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Get items to remove from the request.
		$cid = JRequest::getVar('cid', array(), '', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseWarning(500, JText::_('COM_JBUSINESSDIRECTORY_NO_COMPANY_SELECTED'));
		}
		else
		{
			// Get the model.
			$model = $this->getModel("Company");

			// Make sure the item ids are integers
			jimport('joomla.utilities.arrayhelper');
			JArrayHelper::toInteger($cid);

			// Remove the items.
			if (!$model->delete($cid))
			{
				$this->setMessage($model->getError());
			} else {
			$this->setMessage(JText::plural('COM_JBUSINESS_DIRECTORY_N_COMPANIES_DELETED', count($cid)));
			}
		}

		$this->setRedirect('index.php?option=com_jbusinessdirectory&view=companies');
	}

	public function importFromCsv(){
		JRequest::setVar("layout","import");	
		parent::display();
	}
	
	public function showExportCsv(){
		JRequest::setVar("layout","export");
		parent::display();
	}

	public function exportCompaniesCsv(){
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$model = $this->getModel('Companies');
		$model->exportCompaniesCSV();
		exit;
	}
	
	public function importCompaniesFromCsv(){
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$config = new JConfig();
		$dest = $config->tmp_path;
		
		$app      = JFactory::getApplication();
		$data = JRequest::get( 'post' );
		$model = $this->getModel("Companies");
		$config = new JConfig();
		$dest = $config->tmp_path;
		
		$dest = $model->uploadFile("csvFile", $data,$dest);
		$this->importCompanies($dest,$data["delimiter"]);
		
	}
	
	function importCompanies($filePath, $delimiter){
		
		$model = $this->getModel("Company");
		$result = $model->importCompanies($filePath, $delimiter);
		
		$message = "";
		if($result->newCategories){
			$message = $message.JText::plural('COM_JBUSINESS_DIRECTORY_N_CATEGORIES_IMPORTED', $result->newCategories);
			$message = $message."<br/>";
		}
		if($result->newSubCategories){
			$message = $message.JText::plural('COM_JBUSINESS_DIRECTORY_N_SUB_CATEGORIES_IMPORTED', $result->newSubCategories);
			$message = $message."<br/>";
		}
		if($result->newTypes){
			$message = $message.JText::plural('COM_JBUSINESS_DIRECTORY_N_TYPES_IMPORTED', $result->newTypes);
			$message = $message."<br/>";
		}
		
		$message = $message.JText::plural('COM_JBUSINESS_DIRECTORY_N_COMPANIES_IMPORTED', $result->newCompanies);
		$message = $message."<br/>";
		
		$this->setMessage($message);
		$this->setRedirect('index.php?option=com_jbusinessdirectory&view=companies');
		
	}
}
