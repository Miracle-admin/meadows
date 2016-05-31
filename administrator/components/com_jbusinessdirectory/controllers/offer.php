<?php


defined('_JEXEC') or die;
jimport('joomla.application.component.controllerform');

/**
 * The Company Controller
 *
 */
class JBusinessDirectoryControllerOffer extends JControllerForm
{
	/**
	 * Dummy method to redirect back to standard controller
	 *
	 */
	public function display($cachable = false, $urlparams = false)
	{
		$this->setRedirect(JRoute::_('index.php?option=com_jbusinessdirectory&view=offers', false));
	}

	public function add()
	{
		$app = JFactory::getApplication();
		$context = 'com_jbusinessdirectory.edit.offer';
	
		$result = parent::add();
		if ($result)
		{
			$this->setRedirect(JRoute::_('index.php?option=com_jbusinessdirectory&view=offer'. $this->getRedirectToItemAppend(), false));
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
	
		$app = JFactory::getApplication();
		$context = 'com_jbusinessdirectory.edit.offer';
		$result = parent::cancel();
	
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
	function save($key = NULL, $urlVar = NULL)
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$app      = JFactory::getApplication();
		$model = $this->getModel('offer');
		$post = JRequest::get( 'post' );
		$post['description'] = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);
		
		$context  = 'com_jbusinessdirectory.edit.offer';
		$task     = $this->getTask();
		$recordId = JRequest::getInt('id');
		
		$post["pictures"] = $this->preparePictures($post);
		
		if(isset($post['url']) && strlen($post['url'])>1){
			if (!preg_match("~^(?:f|ht)tps?://~i", $post['url'])) {
				$post['url'] = "http://" . $post['url'];
			}
		}
		
		if (!$model->save($post)){
			// Save the data in the session.
			$app->setUserState('com_jbusinessdirectory.edit.offer.data', $post);
			
			// Redirect back to the edit screen.
			$this->setMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($recordId), false));
			
			return false;
		}

		$this->setMessage(JText::_('COM_JBUSINESSDIRECTORY_OFFER_SAVE_SUCCESS'));
		
		// Redirect the user and adjust session state based on the chosen task.
		switch ($task)
		{
			case 'apply':
				// Set the row data in the session.
				$recordId = $model->getState($this->context . '.id');
				$this->holdEditId($context, $recordId);
				$app->setUserState('com_jbusinessdirectory.edit.offer.data', null);
			
				// Redirect back to the edit screen.
				$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($recordId), false));
				break;

			default:
				// Clear the row id and data in the session.
				$this->releaseEditId($context, $recordId);
				$app->setUserState('com_jbusinessdirectory.edit.offer.data', null);
							
				// Redirect to the list screen.
				$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list . $this->getRedirectToListAppend(), false));
				break;
		}
	
	}
	
	
	function preparePictures($post){
		//save images
		$pictures					= array();
		foreach( $post as $key => $value )
		{
			if(
					strpos( $key, 'offer_picture_info' ) !== false
					||
					strpos( $key, 'offer_picture_path' ) !== false
					||
					strpos( $key, 'offer_picture_enable' ) !== false
			){
				foreach( $value as $k => $v )
				{
					if( !isset($pictures[$k]) )
						$pictures[$k] = array('offer_picture_info'=>'', 'offer_picture_path'=>'','offer_picture_enable'=>1);
					$pictures[$k][$key] = $v;
				}
			}
		}
	
		return $pictures;
	}
	
	
	function aprove(){
		$post = JRequest::get( 'post' );
		$model = $this->getModel('Offer');
		$model ->changeAprovalState(1);
	
		$this->setRedirect(JRoute::_('index.php?option=com_jbusinessdirectory&view=offers', false));
	}
	
	function disaprove(){
		$post = JRequest::get( 'post' );
		$model = $this->getModel('Offer');
		$model ->changeAprovalState(-1);
		
		$this->setRedirect(JRoute::_('index.php?option=com_jbusinessdirectory&view=offers', false));
	}
	
	function chageState()
	{
		$model = $this->getModel('Offer');
	
		if ($model->changeState())
		{
			$msg = JText::_( '' );
		} else {
			$msg = JText::_('LNG_ERROR_CHANGE_STATE');
		}
	
	
		$this->setRedirect(JRoute::_('index.php?option=com_jbusinessdirectory&view=offers', $msg));
	}
	
	
	function changeStateOfferOfTheDay()
	{
		$model = $this->getModel('Offer');
	
		if ($model->changeStateOfferOfTheDay())
		{
			$msg = JText::_( '' );
		} else {
			$msg = JText::_('LNG_ERROR_CHANGE_STATE');
		}
	
		$this->setRedirect(JRoute::_('index.php?option=com_jbusinessdirectory&view=offers', $msg));
	}
	
	function changeStateFeatured()
	{
		$model = $this->getModel('Offer');
	
		if ($model->changeStateFeatured())
		{
			$msg = JText::_( '' );
		} else {
			$msg = JText::_('LNG_ERROR_CHANGE_STATE');
		}
	
		$this->setRedirect(JRoute::_('index.php?option=com_jbusinessdirectory&view=offers', $msg));
	}
	
}
