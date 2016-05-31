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

require_once( JPATH_ADMINISTRATOR .'/components/com_jbusinessdirectory/library/category_lib.php');

class modJBusinessCategoriesOffersHelper{
	
	function getCategories(){
		$categoryService = new JBusinessDirectorCategoryLib();
		$categories = $categoryService->getCategories();
		
		return $categories;
	}
}
?>
