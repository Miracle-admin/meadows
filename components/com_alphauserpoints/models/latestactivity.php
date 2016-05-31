<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class alphauserpointsModelLatestactivity extends JmodelLegacy {

	function __construct(){
		parent::__construct();
		
	}

	function _getLatestActivity($params) {
	
		$app = JFactory::getApplication();

		$db			      = JFactory::getDBO();
		
		$nullDate	= $db->getNullDate();
		$date = JFactory::getDate();
		$now  = $date->toSql();
		
		$activity   = $params->get('activity', 0);
		
		$typeActivity = "";
		
		if ( $activity == 1 )
		{			
			$typeActivity = " AND a.points >= 1";
		}
		elseif ( $activity == 2 ) 
		{
			$typeActivity = " AND a.points <= 0";		
		}

	
		// Get the pagination request variables
		$limit = $app->getUserStateFromRequest('com_alphauserpoints.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = JFactory::getApplication()->input->get('limitstart', 0, 'int');
		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ( $limit != 0 ? (floor( $limitstart / $limit ) * $limit) : 0);		
		$query = "SELECT a.insert_date, a.referreid, a.points AS last_points, a.datareference, u.".$params->get('usrname', 'name')." AS usrname, r.rule_name, r.plugin_function, r.category"
			   . " FROM #__alpha_userpoints_details AS a, #__alpha_userpoints AS aup, #__users AS u, #__alpha_userpoints_rules AS r"
			   . " WHERE aup.referreid=a.referreid AND aup.userid=u.id AND r.displayactivity='1' AND aup.published='1' AND a.approved='1' AND a.enabled='1' AND (a.expire_date>='".$now."' OR a.expire_date='0000-00-00 00:00:00') AND r.id=a.rule"
			   . $typeActivity
			   . " ORDER BY a.insert_date DESC"
		 	   ;
		$total = @$this->_getListCount($query);
		$result = $this->_getList($query, $limitstart, $limit);
		return array($result, $total, $limit, $limitstart);
	
	}	

}
?>