<?php
/**
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2014 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
*/

defined('_JEXEC') or die;

/**
 * @param	array	A named array
 * @return	array
 */
function JBusinessDirectoryBuildRoute(&$query)
{
	$segments = array();

	
	if (isset($query['view'])) {
		$segments[] = $query['view'];
		unset($query['view']);
	}
	
	if (isset($query['companyId'])) {
		$segments[] = $query['companyId'];
		unset($query['companyId']);
	}
	
	if (isset($query['categoryId'])) {
		$segments[] = $query['categoryId'];
		unset($query['categoryId']);
	}

	if (isset($query['eventId'])) {
		$segments[] = $query['eventId'];
		unset($query['eventId']);
	}
	
	if (isset($query['offerId'])) {
		$segments[] = $query['offerId'];
		unset($query['offerId']);
	}
	//var_dump($segments);
	return $segments;
}

/**
 * @param	array	A named array
 * @param	array
 *
 * Formats:
 *
 * index.php?/banners/task/id/Itemid
 *
 * index.php?/banners/id/Itemid
 */
function JBusinessDirectoryParseRoute($segments)
{
	$vars = array();
	
	// view is always the first element of the array
	$count = count($segments);

	// Count route segments
	$count = count($segments);

	// Standard routing for articles.  If we don't pick up an Itemid then we get the view from the segments
	// the first segment is the view and the last segment is the id of the article or category.

	$vars['view']	= $segments[0];
	
	switch($vars['view']){
		case "companies":	
			$temp = explode( ":", $segments[$count - 1]);
			$vars['companyId']		= $temp[0];
			break;
		case "search":
			$temp = explode( ":", $segments[$count - 1]);
			if(is_numeric($temp[0])){
				$vars['categoryId']		= $temp[0];
			}
			break;
		case "event":
			$temp = explode( ":", $segments[$count - 1]);
			$vars['eventId']		= $temp[0];
			break;
		case "offer":
			$temp = explode( ":", $segments[$count - 1]);
			$vars['offerId']		= $temp[0];
			break;
		case "events":
			$temp = explode( ":", $segments[$count - 1]);
			break;
		case "offers":
			$temp = explode( ":", $segments[$count - 1]);
			break;
		case "payment":
			$temp = explode( ":", $segments[$count - 1]);
			$vars['companyId']		= $temp[0];
			break;
	}
	
	//var_dump($vars);
	
	return $vars;
}
