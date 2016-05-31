<?php

/**
 * @version     2.0.0
 * @package     com_vm2wishlists
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 3 or higher ; See LICENSE.txt
 * @author      Nordmograph <contact@nordmograph.com> - http://www.nordmograph.com/extensions
 */
// No direct access
defined('_JEXEC') or die;

/**
 * @param	array	A named array
 * @return	array
 */
function Vm2wishlistsBuildRoute(&$query) {
    $segments = array();

    if (isset($query['task'])) {
        $segments[] = implode('/', explode('.', $query['task']));
        unset($query['task']);
    }
    if (isset($query['view'])) {
        $segments[] = $query['view'];
        unset($query['view']);
    }
    if (isset($query['id'])) {
		$db = JFactory::getDBO();
		$q = "SELECT list_name FROM #__vm2wishlists_lists WHERE id='".$query['id']."' ";
		$db->setQuery($q);
		$list_name = $db->loadResult();
        $segments[] = $query['id'].':'.JFilterOutput::stringURLSafe( JText::_($list_name) );
        unset($query['id']);
    }
	if (isset($query['listid'])) {
        $segments[] = $query['listid'];
        unset($query['listid']);
    }
    if (isset($query['userid'])) {
        $u = JFactory::getUser( $query['userid'] );
        $segments[] = JFilterOutput::stringURLSafe( $u->username );
        unset($query['userid']);
    }

    return $segments;
}

/**
 * @param	array	A named array
 * @param	array
 *
 * Formats:
 *
 * index.php?/vm2wishlists/task/id/Itemid
 *
 * index.php?/vm2wishlists/id/Itemid
 */
function Vm2wishlistsParseRoute($segments) {
    $vars = array();

    // view is always the first element of the array
    $vars['view'] = array_shift($segments);

    while (!empty($segments)) {
        $segment = array_pop($segments);
        if (is_numeric($segment)) {
            if($vars['view']=='list')
                $vars['id'] = (int) $segment;
            elseif($vars['view']=='recommend')
                $vars['listid'] = (int) $segment;
        } elseif( !strpos($segment,':')) {
            $db = JFactory::getDBO();
           $q = "SELECT id FROM #__users WHERE LOWER(username)='".strtolower($segment)."' ";
            $db->setQuery($q);
            $vars['userid'] = $db->loadResult();
           // $vars['task'] = $vars['view'] . '.' . $segment;
        }
    }

    return $vars;
}
