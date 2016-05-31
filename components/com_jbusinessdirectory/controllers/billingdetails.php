<?php


defined('_JEXEC') or die;
jimport('joomla.application.component.controllerform');

/**
 * The Company Controller
 *
 */
class JBusinessDirectoryControllerBillingDetails extends JControllerForm
{
	/**
	 * Dummy method to redirect back to standard controller
	 *
	 */
	public function display($cachable = false, $urlparams = false)
	{
		$this->setRedirect(JRoute::_('index.php?option=com_jbusinessdirectory&view=billingdetailss', false));
	}

	public function add()
	{
		$app = JFactory::getApplication();
		$context = 'com_jbusinessdirectory.edit.billingdetails';
	
		$result = parent::add();
		if ($result)
		{
			$this->setRedirect(JRoute::_('index.php?option=com_jbusinessdirectory&view=billingdetails'. $this->getRedirectToItemAppend(), false));
		}
	
		return $result;
	}
	
	
	/**
	 * Method to cancel an edit.
	 *
	 * @param   string  $key  The name of the primary key of the URL variable.
	 *
	 * @return  boolean  True if access level checks pass, false otherwise.

	 */
	public function cancel($key = null)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=useroptions', false));
		$result;
	
	}
	
	/**
	 * Method to edit an existing record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key
	 * (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if access level check and checkout passes, false otherwise.
	 *
	 */
	public function edit($key = null, $urlVar = null)
	{
		$app = JFactory::getApplication();
		$result = parent::edit();
	
		return true;
	}
	
	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save()
	{
		
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$app      = JFactory::getApplication();
		$model = $this->getModel('billingdetails');
		$post = JRequest::get( 'post' );
		$data = JRequest::get( 'post' );
		$context  = 'com_jbusinessdirectory.edit.billingdetails';
		$task     = $this->getTask();
		$recordId = JRequest::getInt('id');

		if (!$model->save($post)){
			// Save the data in the session.
			$app->setUserState('com_jbusinessdirectory.edit.billingdetails.data', $data);
			
			// Redirect back to the edit screen.
			$this->setMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($recordId), false));
			
			return false;
		}

		
		
		// Redirect the user and adjust session state based on the chosen task.
		switch ($task)
		{
			case 'apply':
				// Set the row data in the session.
				$recordId = $model->getState($this->context . '.id');
				$this->holdEditId($context, $recordId);
				$app->setUserState('com_jbusinessdirectory.edit.billingdetails.data', null);
			
				// Redirect back to the edit screen.
				$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($recordId), false));
				break;

			default:
				// Clear the row id and data in the session.
				$this->releaseEditId($context, $recordId);
				$app->setUserState('com_jbusinessdirectory.edit.billingdetails.data', null);

				if(!empty($data["orderId"])){
					$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=payment&orderId='.$data["orderId"], false));
				}else{
				// Redirect to the list screen.
					$this->setMessage(JText::_('COM_JBUSINESSDIRECTORY_BILLING_DETAILS_SAVE_SUCCESS'));
					$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=useroptions', false));
				}
				break;
		}
	}
}
