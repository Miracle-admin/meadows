<?php


defined('_JEXEC') or die;


class JBusinessDirectoryControllerReviews extends JControllerLegacy
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
	public function getModel($name = 'Reviews', $prefix = 'JBusinessDirectoryModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}

	public function back(){
		$this->setRedirect('index.php?option=com_jbusinessdirectory');
	}
	
	function save()
	{
		$model = $this->getModel('Review');
		$post = JRequest::get( 'post' );
		$post["id"] = $post["reviewId"];
		if ($model->store($post))
		{
			$msg = JText::_('LNG_REVIEW_SAVED');
			$this->setRedirect( 'index.php?option=com_jbusinessdirectory&controller=reviews&view=reviews', $msg );
		}
		else
		{
			$msg = '';
			JError::raiseWarning( 500, JText::_("LNG_ERROR_SAVING_REVIEW"));
			$this->setRedirect( 'index.php?option=com_jbusinessdirectory&controller=reviews&view=reviews', $msg );
		}
	
		$this->setRedirect( 'index.php?option=com_jbusinessdirectory&controller=reviews&view=reviews', $msg );
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
			JError::raiseWarning(500, JText::_('COM_JBUSINESSDIRECTORY_NO_REVIEW_SELECTED'));
		}
		else
		{
			// Get the model.
			$model = $this->getModel("Review");

			// Make sure the item ids are integers
			jimport('joomla.utilities.arrayhelper');
			JArrayHelper::toInteger($cid);

			// Remove the items.
			if (!$model->delete($cid))
			{
				$this->setMessage($model->getError());
			} else {
			$this->setMessage(JText::plural('COM_JBUSINESS_DIRECTORY_N_REVIEWS_DELETED', count($cid)));
			}
		}

		$this->setRedirect('index.php?option=com_jbusinessdirectory&view=reviews');
	}

	function chageState()
	{
		$model = $this->getModel("Review");
		if (!$model->changeState())
		{
			$this->setMessage(JText::_('LNG_ERROR_CHANGE_STATE'), 'warning');
		}
		
		$this->setRedirect('index.php?option=com_jbusinessdirectory&view=reviews');
	}
}
