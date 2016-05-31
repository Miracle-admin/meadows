<?php


defined('_JEXEC') or die;


class JBusinessDirectoryControllerEvents extends JControllerLegacy
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
	public function getModel($name = 'Events', $prefix = 'JBusinessDirectoryModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}

	public function back(){
		$this->setRedirect('index.php?option=com_jbusinessdirectory');
	}
	
	/**
	 * Delete an event or the associated reccuring events.
	 */
	public function delete(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Get the model.
		$model = $this->getModel("Event");
		$this->deleteEvents($model);

		$this->setRedirect('index.php?option=com_jbusinessdirectory&view=events');
	}
	
	function deleteEvents($model){
		// Get items to remove from the request.
		$cid = JRequest::getVar('cid', array(), '', 'array');
		$delete_mode = JRequest::getVar("delete_mode",1);
		$delete_ids = array();
		//if there are multiple events selected delete only the events selected. If only one give multiple options.
		
		if(!empty($cid) && count($cid)>1){
			$delete_ids = $cid;
		}else{
			$id = $cid[0];
			switch($delete_mode){
				case 1:
					$delete_ids[]= $id;
					break;
				case 2:
					$delete_ids= $model->getNextEventsIds($id);
					break;
				case 3:
					$delete_ids = $model->getAllSeriesEventsIds($id);
					break;
				default:
					$delete_ids[]= $id;
					break;
			}
		}
		
		if (!is_array($delete_ids) || count($delete_ids) < 1)
		{
			JError::raiseWarning(500, JText::_('COM_JBUSINESSDIRECTORY_NO_EVENT_SELECTED'));
		}
		else{
			// Make sure the item ids are integers
			jimport('joomla.utilities.arrayhelper');
			JArrayHelper::toInteger($delete_ids);
		
			// Remove the items.
			if (!$model->delete($delete_ids))
			{
				$this->setMessage($model->getError());
			} else {
				$this->setMessage(JText::plural('COM_JBUSINESSDIRECTORY_N_EVENTS_DELETED', count($delete_ids)));
			}
		}
		
		$this->setRedirect('index.php?option=com_jbusinessdirectory&view=events');
	}

	function chageState(){
		$model = $this->getModel('Event');
	
		if ($model->changeState()){
			$this->setMessage(JText::_('LNG_ERROR_CHANGE_STATE'), 'warning');
		}
	
		$this->setRedirect('index.php?option=com_jbusinessdirectory&view=events');
	}
	
	function changeStateEventOfTheDay(){
		$model = $this->getModel('Event');
	
		if (!$model->changeStateEventOfTheDay())	{
			$this->setMessage(JText::_('LNG_ERROR_CHANGE_STATE'), 'warning');
		}
	
		$this->setRedirect('index.php?option=com_jbusinessdirectory&view=events');
	}

}
