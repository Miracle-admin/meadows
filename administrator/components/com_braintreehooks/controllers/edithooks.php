<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

/**
 * Edithooks item controller class.
 *
 * @package     Braintreehooks
 * @subpackage  Controllers
 */
class BraintreehooksControllerEdithooks extends JControllerForm
{
	/**
	 * The URL view item variable.
	 *
	 * @var    string
	 * @since  12.2
	 */
	protected $view_item = 'edithooks';

	/**
	 * The URL view list variable.
	 *
	 * @var    string
	 * @since  12.2
	 */
	protected $view_list = 'hooks';
	
	/**
	 * Method to run batch operations.
	 *
	 * @param   object  $model  The model.
	 *
	 * @return  boolean   True if successful, false otherwise and internal error is set.
	 *
	 * @since   2.5
	 */
	public function batch($model = null)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Set the model
		$model = $this->getModel('Edithooks', 'BraintreehooksModel', array());

		// Preset the redirect
		$this->setRedirect(JRoute::_('index.php?option=com_braintreehooks&view=hooks' . $this->getRedirectToListAppend(), false));

		return parent::batch($model);
	}
}
?>