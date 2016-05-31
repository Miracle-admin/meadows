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

class modJBannersHelper{

	public static function getList(&$params){
		JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_banners/models', 'BannersModel');
		$document	= JFactory::getDocument();
		$app		= JFactory::getApplication();
		$keywords	= explode(',', $document->getMetaData('keywords'));

	
		$categoryId = JRequest::getVar("categorySearch");
		if(empty($categoryId)){
			$categoryId = JRequest::getVar('categoryId',null);
		}
		
		$db = JFactory::getDBO();
		if(!empty($categoryId)){
			$query = "select * from #__jbusinessdirectory_categories where published=1 and id = $categoryId";
			$db->setQuery($query);
			$category = $db->loadObject();
			//exit;
		}
		
		$name= JRequest::getVar("searchkeyword");
		if(!empty($category)){
			$name= $category->name;
		}
		
		if(empty($name)){
			return;
		}
		
		$query = "SELECT * FROM #__categories where extension='com_banners' and title='$name'";
		
		$db->setQuery($query);
		$category = $db->loadObject();
		
		if(empty($category)){
			return;
		}
		
		
		$model = JModelLegacy::getInstance('Banners', 'BannersModel', array('ignore_request' => true));
		$model->setState('filter.client_id', (int) $params->get('cid'));
		$model->setState('filter.category_id', array($category->id));
		$model->setState('list.limit', (int) $params->get('count', 1));
		$model->setState('list.start', 0);
		$model->setState('filter.ordering', $params->get('ordering'));
		$model->setState('filter.tag_search', $params->get('tag_search'));
		$model->setState('filter.keywords', $keywords);
		$model->setState('filter.language', $app->getLanguageFilter());

		$banners = $model->getItems();
		$model->impress();
	
		
		return $banners;
	}
}
?>
