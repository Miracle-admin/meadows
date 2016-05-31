<?php


defined('_JEXEC') or die;


class JBusinessDirectoryControllerRatings extends JControllerLegacy {
	/**
	 * Display the view
	 *
	 * @param   boolean			If true, the view output will be cached
	 * @param   array  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController		This object to support chaining.
	 * @since   1.6
	 */
	public function display($cachable = false, $urlparams = false) {
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
	public function getModel($name = 'Ratings', $prefix = 'JBusinessDirectoryModel', $config = array('ignore_request' => true)) {
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}

	public function back() {
		$this->setRedirect('index.php?option=com_jbusinessdirectory');
	}
	
	function save() {
		$model = $this->getModel('Rating');
		$post = JRequest::get( 'post' );
		$post["id"] = $post["companyId"];
		if ($model->store($post)) {
			$msg = JText::_('LNG_RATING_SAVED');
			$this->setRedirect( 'index.php?option=com_jbusinessdirectory&controller=ratings&view=ratings', $msg );
		}
		else {
			$msg = '';
			JError::raiseWarning( 500, JText::_("LNG_ERROR_SAVING_RATING"));
			$this->setRedirect( 'index.php?option=com_jbusinessdirectory&controller=ratings&view=ratings', $msg );
		}
	
		$this->setRedirect( 'index.php?option=com_jbusinessdirectory&controller=ratings&view=ratings', $msg );
	}
	
	/**
	 * Removes an item
	 */
	public function delete() {
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Get items to remove from the request.
		$cid = JRequest::getVar('cid', array(), '', 'array');

		if (!is_array($cid) || count($cid) < 1) {
			JError::raiseWarning(500, JText::_('COM_JBUSINESSDIRECTORY_NO_RATING_SELECTED'));
		}
		else {
			// Get the model.
			$model = $this->getModel("Rating");

			// Make sure the item ids are integers
			jimport('joomla.utilities.arrayhelper');
			JArrayHelper::toInteger($cid);

			// Remove the items.
			if (!$model->delete($cid)) {
				$this->setMessage($model->getError());
			} else {
			$this->setMessage(JText::plural('COM_JBUSINESS_DIRECTORY_N_RATINGS_DELETED', count($cid)));
			}
		}

		$this->setRedirect('index.php?option=com_jbusinessdirectory&view=ratings');
	}
}
