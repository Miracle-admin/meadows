<?php
/*------------------------------------------------------------------------
# mod_featcats - Featured Categories
# ------------------------------------------------------------------------
# author    Jesús Vargas Garita
# Copyright (C) 2010 www.joomlahill.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomlahill.com
# Technical Support:  Forum - http://www.joomlahill.com/forum
-------------------------------------------------------------------------*/

defined('_JEXEC') or die;

if ($params->get('hide1', 0) && (JRequest::getCmd('option')=='com_content' && JRequest::getCmd('view')=='article')) {
	
	return;
	
} else {

	require_once dirname(__FILE__) . '/helper.php';
	
	$cats = modFeatcatsHelper::getList($params);
	
	if (!empty($cats)) {
		$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
		$item_heading = $params->get('item_heading');
		$cat_heading  = $params->get('cat_heading');
		$link_cats    = $params->get('link_cats', 1);
		$show_image   = $params->get('show_image', 0);
		$show_more    = $params->get('show_more');
		$link_target  = $params->get('link_target');
		$pag          = $params->get('pag_show', 0);
		$css          = $params->get('add_css','featcats.css');
		if (!$params->get('mid')) { 
			$mid = $module->id;
		} else {
			$mid = $params->get('mid');
		}
		if (!$params->get('ajaxed')) {
			require JModuleHelper::getLayoutPath('mod_featcats', 'default');
		} else {
			$id = '';
			if (JRequest::getVar('catid')) :
				$id  = (int)JRequest::getVar('catid');
			endif;
			$cat = $cats[$id];
			require JModuleHelper::getLayoutPath('mod_featcats', 'cat');
		}
	}
}