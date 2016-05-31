<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

function AlphaUserPointsBuildRoute(&$query) 
{
	$segments = array();
	$db		  =  JFactory::getDBO();

	if(isset($query['view']))
	{
		if(empty($query['Itemid'])) {
			$segments[] = $query['view'];
		}
		
		if($query['view'] == 'medals' ) {
			$segments[] = $query['view'];
		}
		if($query['view'] == 'account' ) {
			$segments[] = 'profile';
		}


		unset($query['view']);
	}
	
	if(isset($query['task']))
	{
		if($query['task'] == 'detailsmedal') {
			$segments[] = $query['task'];
			unset($query['task']);
			if(isset($query['cid']))
			{
				$sqlQuery = "SELECT `rank` FROM `#__alpha_userpoints_levelrank`
						WHERE `id` = " . $query['cid'] . " LIMIT 1";
				$db->setQuery($sqlQuery);				
				$segments[] = urlencode($db->loadResult());
				unset($query['cid']);
			}
		}
		elseif ( $query['task'] == 'downloadactivity' )
		{
			$segments[] = $query['task'];
			unset($query['task']);
			if(isset($query['userid']))
			{
				$segments[] = $query['userid'];
				unset($query['userid']);
			}
			
		}
	}
	
	if(isset($query['userid']))
	{			
		$sqlQuery = "SELECT u.username " .
				 "FROM #__users AS u, #__alpha_userpoints AS a " .
				 "WHERE a.referreid='".$query['userid']."' AND a.userid=u.id LIMIT 1";
		$db->setQuery($sqlQuery);				
		$segments[] = urlencode($db->loadResult());		
		unset($query['userid']);
	}
	
	return $segments;
}

function AlphaUserPointsParseRoute($segments)
{
	$vars = array();
	$db	=  JFactory::getDBO();
	
	// Count route segments
	$count = count($segments);	
	
	if ( $count ) {
	
		if($segments[0] == 'profile') {
			$vars['view'] = 'account';
			$sqlQuery = "SELECT a.referreid " .
					 "FROM #__alpha_userpoints AS a, #__users AS u " .
					 "WHERE u.username='".urldecode($segments[$count-1])."' AND a.userid=u.id LIMIT 1";
			$db->setQuery($sqlQuery);
			$vars['userid'] = $db->loadResult();
			
			/*$vars['task'] = $segments[$count-2];			
			if($vars['task'] == 'downloadactivity') {
				$vars['userid'] = $segments[$count-1];
			}*/
			
			if ( !empty($segments[$count-2]) ) {
			  $vars['task'] = $segments[$count-2];
					   
			  if($vars['task'] == 'downloadactivity') {
				  $vars['userid'] = $segments[$count-1];
			  }
			} 	
			
			return $vars;
		}
	
		if($segments[0] == 'medals') {
			$vars['view'] = 'medals';
			$vars['task'] = $segments[$count-2];
			$segments[$count-1] = str_replace( '.html', '', $segments[$count-1] );
			$sqlQuery = "SELECT `id` FROM `#__alpha_userpoints_levelrank`
					WHERE `rank`='" . urldecode($segments[$count-1]) . "' LIMIT 1";		
			$db->setQuery($sqlQuery);			
			$vars['cid'] = $db->loadResult();			
			return $vars;
		}
		
		
	}
}
?>