<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// num version
if(!defined("_ALPHAUSERPOINTS_NUM_VERSION")) {
   DEFINE( "_ALPHAUSERPOINTS_NUM_VERSION", "2.0.4" );
}

function aup_update_db_version () {
	
	$db	= JFactory::getDBO(); 
	// update table version
	$query = "UPDATE #__alpha_userpoints_version SET `version`='2.0.4' WHERE 1";
	$db->setQuery( $query );
	$db->query();

}
?>