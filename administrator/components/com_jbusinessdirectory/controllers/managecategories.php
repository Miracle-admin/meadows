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

JHTML::_('script', 'administrator/components/com_jbusinessdirectory/assets/js/manage.categories.js');
JHTML::_('script', 'components/com_jbusinessdirectory/assets/js/jquery.upload.js');

class JBusinessDirectoryControllerManageCategories extends JControllerLegacy
{
	
	public function display($cachable = false, $urlparams = false)
	{
	}
	
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	 
	function __construct()
	{
		parent::__construct();
		$this->registerTask( 'state', 'state');  
		$this->registerTask( 'add', 'edit');
		$this->registerTask( 'save', 'save');
	}

	public function back(){
		$this->setRedirect('index.php?option=com_jbusinessdirectory');
	}
	
	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save()
	{
		$model = $this->getModel('managecategories');
		$post['name'] 			= JRequest::getVar('name', '', 'post', 'string', JREQUEST_ALLOWRAW);
		
		$post = JRequest::get( 'post' );
		if ($model->store($post)) 
		{
			$msg = JText::_('LNG_CURRENCY_SAVED');
			$this->setRedirect( 'index.php?option=com_jbusinessdirectory&controller=managecategories&view=managecategories', $msg );
		} 
		else 
		{
			$msg = '';
			JError::raiseWarning( 500, JText::_("LNG_ERROR_SAVING_CURRENCY"));
			$this->setRedirect( 'index.php?option=com_jbusinessdirectory&controller=managecategories&view=managecategories', $msg );	
		}

		$this->setRedirect( 'index.php?option=com_jbusinessdirectory&controller=managecategories&view=managecategories', $msg );
	}

	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel()
	{
		
		$msg = JText::_('LNG_OPERATION_CANCELLED');
		$this->setRedirect( 'index.php?option=com_jbusinessdirectory&controller=managecategories&view=managecategories', $msg );
	}
	
	function edit()
	{
		JRequest::setVar( 'view', 'managecategories' );
	
		parent::display(); 
	}
	
	function changeCategoryState(){
		$errorFlag = false;
		$model = $this->getModel('managecategories');
		
		$categoryId = intval($_POST['categoryId']);		
		$errorFlag = !$model->changeCategoryState($categoryId);

		$category = $model->getCategoryById($categoryId);
		
		echo '<?xml version="1.0" encoding="utf-8" ?>';
		echo '<category_statement>';
		echo '<answer id="'.$category[0]->id.'" state="'.$category[0]->state.'"/>';
		echo '</category_statement>';
		echo '</xml>';
		exit;
	}
	
	function deleteCategory(){
		$errorFlag = false;
		$model = $this->getModel('managecategories');
		
		$categoryId = intval($_POST['categoryId']);

		$category = $model->getCategoryById($categoryId);
		$model->deleteCategory($categoryId);

		if($category[0]->parent_id == 0){
			$categories = $model->getCategories();
			$level=1;
		}else{
			$category = $model->getCompleteCategoryById($category[0]->parent_id);
			$categories = $category["subCategories"];
			$level=intval($category["level"])+1;
		}
	
		
		//dump($categories);
		
		
		$buff = $errorFlag ? '' : $this->getHTMLContentSubcategories($categories);
		
		echo '<?xml version="1.0" encoding="utf-8" ?>';
		echo '<category_statement>';
		echo '<answer error="'.(!$errorFlag ? "0" : "1").'" errorMessage="'.$message.'" category-level="'.$level.'" content_categories="'.$buff.'"';
		echo '</category_statement>';
		echo '</xml>';
		exit;
	}
	
	function saveCategory(){
		$post = JRequest::get( 'post' );
		$post['name'] 	= urldecode(JRequest::getVar('name', '', 'post', 'string', JREQUEST_ALLOWRAW));
		$post['description'] 	= urldecode(JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW));
		$model = $this->getModel('managecategories');
		$errorFlag = false;
		$message = '';
		$categoryId = $model->store($post);
		if (empty($categoryId)){
			$errorFlag = true;
			$message = $model->getError();
		}

		$parentId = intval($_POST['parentId']);
		$category = null;
		$categories = null;
		if($parentId == 0){
			$categories = $model->getCategories();
		}else{
			$category = $model->getCompleteCategoryById($parentId);
			$categories = $category["subCategories"];
		}
		
		$level=intval($category["level"])+1;
		
		$buff = $errorFlag ? '' : $this->getHTMLContentSubcategories($categories);
		
		echo '<?xml version="1.0" encoding="utf-8" ?>';
		echo '<category_statement>';
		echo '<answer error="'.(!$errorFlag ? "0" : "1").'" errorMessage="'.$message.'" category-level="'.$level.'" content_categories="'.$buff.'"';
		echo '</category_statement>';
		echo '</xml>';
		exit;
	}
	
	function getCategoryById(){
		$model = $this->getModel("managecategories");
		$categoryId = intval($_POST['categoryId']);
		$category = $model->getCategoryById($categoryId);
		
		echo '<?xml version="1.0" encoding="utf-8" ?>';
		echo '<category_statement>';
		echo '<answer id="'.$category[0]->id.'" parentId="'.$category[0]->parent_id.'"  name="'.$category[0]->name.'"  alias="'.$category[0]->alias.'" description="'.$category[0]->description.'" imagePath="'.$category[0]->imageLocation.'" markerPath="'.$category[0]->markerLocation.'"/>';
		echo '</category_statement>';
		echo '</xml>';
		exit;
	}
	
	function displaySubcategories(){
		$model = $this->getModel("managecategories");
		$categoryId = intval($_POST['categoryId']);
		$category = $model->getCompleteCategoryById($categoryId);
		$level=intval($category["level"])+1;
		
		$buff 		=  $this->getHTMLContentSubcategories($category["subCategories"]);
			
		echo '<?xml version="1.0" encoding="utf-8" ?>';
		echo '<category_statement>';
		echo '<answer error=0  mesage="'.$m.'" category-level="'.$level.'" content_categories="'.$buff.'" />';
		echo '</category_statement>';
		echo '</xml>';
		exit;
		
	}
	
	function getHTMLContentSubcategories($categories){
		$path = JPATH_ADMINISTRATOR . '/components/com_jbusinessdirectory/views/managecategories/view.html.php';
		include_once( $path);
		
		$view = $this->getView('managecategories');
		$buff = $view->displayCategories($categories);
		return htmlspecialchars($buff);
	}
	
	public function importFromCsv(){
		JRequest::setVar("layout","import");
		parent::display();
	}
	
	public function showExportCsv(){
		JRequest::setVar("layout","export");
		parent::display();
	}
	
	public function exportCategoriesCsv(){
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$model = $this->getModel('ManageCategories');
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
	
		$model = $this->getModel("ManageCategories");
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
		$this->setRedirect('index.php?option=com_jbusinessdirectory&view=managecategories');
	
	}
}