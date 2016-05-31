<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_categories
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * The Categories List Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_categories
 * @since       1.6
 */
class JBusinessDirectoryControllerCategories extends JControllerAdmin
{
	/**
	 * Proxy for getModel
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  The array of possible config values. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   1.6
	 */
	public function getModel($name = 'Category', $prefix = 'JBusinessDirectoryModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	/**
	 * Rebuild the nested set tree.
	 *
	 * @return  bool  False on failure or error, true on success.
	 *
	 * @since   1.6
	 */
	public function rebuild()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$this->setRedirect(JRoute::_('index.php?option=com_jbusinessdirectory&view=categories', false));

		$model = $this->getModel();

		if ($model->rebuild())
		{
			// Rebuild succeeded.
			$this->setMessage(JText::_('COM_CATEGORIES_REBUILD_SUCCESS'));

			return true;
		}
		else
		{
			// Rebuild failed.
			$this->setMessage(JText::_('COM_CATEGORIES_REBUILD_FAILURE'));

			return false;
		}
	}

	/**
	 * Save the manual order inputs from the categories list page.
	 *
	 * @return      void
	 *
	 * @since       1.6
	 * @see         JControllerAdmin::saveorder()
	 * @deprecated  4.0
	 */
	public function saveorder()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		JLog::add('CategoriesControllerCategories::saveorder() is deprecated. Function will be removed in 4.0', JLog::WARNING, 'deprecated');

		// Get the arrays from the Request
		$order = $this->input->post->get('order', null, 'array');
		$originalOrder = explode(',', $this->input->getString('original_order_values'));

		// Make sure something has changed
		if (!($order === $originalOrder))
		{
			parent::saveorder();
		}
		else
		{
			// Nothing to reorder
			$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false));

			return true;
		}
	}

	/**
	 * Deletes and returns correctly.
	 *
	 * @return  void
	 *
	 * @since   3.1.2
	 */
	public function delete()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Get items to remove from the request.
		$cid = $this->input->get('cid', array(), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseWarning(500, JText::_($this->text_prefix . '_NO_ITEM_SELECTED'));
		}
		else
		{
			// Get the model.
			$model = $this->getModel();

			// Make sure the item ids are integers
			jimport('joomla.utilities.arrayhelper');
			JArrayHelper::toInteger($cid);

			// Remove the items.
			if ($model->delete($cid))
			{
				$this->setMessage(JText::plural($this->text_prefix . '_N_ITEMS_DELETED', count($cid)));
			}
			else
			{
				$this->setMessage($model->getError());
			}
		}
		
		$this->setRedirect(JRoute::_('index.php?option=com_jbusinessdirectory&view=categories', false));
	}
	
	public function importFromCsv(){
		$this->setRedirect(JRoute::_('index.php?option=com_jbusinessdirectory&view=categories&layout=import', false));
	}
	
	public function showExportCsv(){
		$this->setRedirect(JRoute::_('index.php?option=com_jbusinessdirectory&view=categories&layout=export', false));
	}
	
	public function exportCategoriesCsv(){
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$model = $this->getModel('Category');
		$model->exportCategoriesCsv();
		exit;
	}
	
	public function importCategoriesFromCsv(){
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$config = new JConfig();
		$dest = $config->tmp_path;
	
		$app      = JFactory::getApplication();
		$data = JRequest::get( 'post' );
		$model = $this->getModel("Companies");
		$config = new JConfig();
		$dest = $config->tmp_path;
	
		$dest = $model->uploadFile("csvFile", $data, $dest);
		$this->importCategories($dest,$data["delimiter"]);
	
	}
	
	function importCategories($filePath, $delimiter){
	
		$model = $this->getModel("Category");
		$result = $model->importCategories($filePath, $delimiter);
	
		$message = "";
		if($result->newCategories){
			$message = $message.JText::plural('COM_JBUSINESS_DIRECTORY_N_CATEGORIES_IMPORTED', $result->newCategories);
			$message = $message."<br/>";
		}
	
		if($result->newSubCategoryCount){
			$message = $message.JText::plural('COM_JBUSINESS_DIRECTORY_N_SUBCATEGORIES_IMPORTED', $result->newSubCategoryCount);
			$message = $message."<br/>";
		}
	
		$this->setMessage($message);
		$this->setRedirect('index.php?option=com_jbusinessdirectory&view=categories');
	
	}
}
