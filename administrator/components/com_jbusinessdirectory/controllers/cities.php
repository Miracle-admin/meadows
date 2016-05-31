<?php


defined('_JEXEC') or die;


class JBusinessDirectoryControllerCities extends JControllerLegacy
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
	public function getModel($name = 'Cities', $prefix = 'JBusinessDirectoryModel', $config = array('ignore_request' => true))
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
	public function delete(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Get items to remove from the request.
		$cid = JRequest::getVar('cid', array(), '', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseWarning(500, JText::_('COM_JBUSINESSDIRECTORY_NO_EVENT_SELECTED'));
		}
		else
		{
			// Get the model.
			$model = $this->getModel("City");

			// Make sure the item ids are integers
			jimport('joomla.utilities.arrayhelper');
			JArrayHelper::toInteger($cid);

			// Remove the items.
			if (!$model->delete($cid))
			{
				$this->setMessage($model->getError());
			} else {
			$this->setMessage(JText::plural('COM_JBUSINESSDIRECTORY_N_EVENTS_DELETED', count($cid)));
			}
		}

		$this->setRedirect('index.php?option=com_jbusinessdirectory&view=cities');
	}

	function chageState(){
		$model = $this->getModel('City');
	
		if ($model->changeState()){
			$this->setMessage(JText::_('LNG_ERROR_CHANGE_STATE'), 'warning');
		}
	
		$this->setRedirect('index.php?option=com_jbusinessdirectory&view=cities');
	}
	
	function changeStateCityOfTheDay(){
		$model = $this->getModel('City');
	
		if (!$model->changeStateCityOfTheDay())	{
			$this->setMessage(JText::_('LNG_ERROR_CHANGE_STATE'), 'warning');
		}
	
		$this->setRedirect('index.php?option=com_jbusinessdirectory&view=cities');
	}

}
