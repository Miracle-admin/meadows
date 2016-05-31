<?php
/**
* @package		AlphaUserPoints for Joomla 3.x
* @copyright	Copyright (C) 2008-2015. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class modAlphaUserPointsMostActiveUsersHelper {

	public static function getList($params) {

		$db			      = JFactory::getDBO();
				
		$count		      = intval($params->get('count', 5));
		$showavatar		  = intval($params->get('showavatar', 1));
		$usrname		  = trim($params->get('usrname', 'name'));
		
		$nullDate		= $db->getNullDate();
		$date 			= JFactory::getDate();
		$now  			= $date->toSql();		
				
		$query = "SELECT aup.points, u.".$usrname." AS usrname, aup.userid, aup.referreid FROM #__alpha_userpoints AS aup, #__users AS u"
		. " WHERE aup.userid = u.id AND aup.published='1'"
		. " ORDER BY aup.points DESC, u.$usrname ASC"
		;
		$db->setQuery($query, 0, $count);
		$rows = $db->loadObjectList();
	
		return $rows;
	
	}
}
?>