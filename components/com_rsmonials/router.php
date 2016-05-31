<?php
/**
 * @version 2.2
 * @package Joomla 3.x
 * @subpackage RS-Monials
 * @copyright (C) 2013-2022 RS Web Solutions (http://www.rswebsols.com)
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die;

/**
 * @param	array
 * @return	array
 */
function RSMonialsBuildRoute(&$query)
{
	$segments = array();
	
	// for page
	if (isset($query['page']) && $query['page'] > 0) {
		if($query['page'] == '1') {
			unset($query['page']);	
		} else {
			$segments[] = $query['page'];
			unset($query['page']);
		}
	}
	
	// for saved
	if (isset($query['saved']) && $query['saved'] == 'true') {
		$segments[] = 'saved';
		unset($query['saved']);
	}
	
	// for err
	if (isset($query['err']) && $query['err'] == 'true') {
		$segments[] = 'err';
		unset($query['err']);
	}
	
	if (isset($query['view'])) {
		unset($query['view']);
	}
	return $segments;
}

/**
 * @param	array
 * @return	array
 */
function RSMonialsParseRoute($segments)
{
	$vars = array();
	foreach($segments as $key=>$value) {
		if($value=='saved') {
			$vars['saved'] = 'true';
		} elseif($value=='err') {
			$vars['err'] = 'true';
		} elseif($value > 1) {
			$vars['page'] = $value;
		}
	}
	$vars['view'] = 'rsmonials';

	return $vars;
}
