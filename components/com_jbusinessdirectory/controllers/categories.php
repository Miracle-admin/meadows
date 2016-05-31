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

class JBusinessDirectoryControllerCategories extends JControllerLegacy
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	 
	function __construct()
	{
		parent::__construct();
	}

	function displayCategories(){
		
		parent::display();
	}
	
	function getCategories(){
		$keyword = JRequest::getVar('term',null);
		
		//dmp($keyword);
		if(empty($keyword)){
			JFactory::getApplication()->close();
		}
		
		$categoriesModel = $this->getModel("Categories");
		
		$categoriesList = $categoriesModel ->getCategoriesList($keyword);
		header('Content-Type: application/json');
		echo $categoriesList;
		
		JFactory::getApplication()->close();
		}
}