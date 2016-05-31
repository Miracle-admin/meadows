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

class alphauserpointsModelalphauserpoints extends JmodelLegacy {

	function __construct(){
		parent::__construct();
		
	}
	
	function _getParamsAUP() {
	
		// Get the parameters of the active menu item
		$app = JFactory::getApplication();
		$menus = $app->getMenu();		
		$menu       = $menus->getActive();
		$menuid     = @$menu->id;
		$params     = $menus->getParams($menuid);
		
		return $params;
	
	}	

	function _get_last_points( $referrerid, $limit=10 ) {
	
		$app = JFactory::getApplication();
		
		$db	   = JFactory::getDBO();
		
		if ( $limit=='all') {
			// Get the pagination request variables
			$limit = $app->getUserStateFromRequest('com_alphauserpoints.limit', 'limit', $app->getCfg('list_limit'), 'int');
			$limitstart = JFactory::getApplication()->input->get('limitstart', 0, 'int');
			// In case limit has been changed, adjust limitstart accordingly
			$limitstart = ( $limit != 0 ? (floor( $limitstart / $limit ) * $limit) : 0);		
			$query = "SELECT a.*, r.rule_name, r.plugin_function FROM #__alpha_userpoints_details AS a, #__alpha_userpoints_rules as r "
					."\nWHERE a.referreid='$referrerid' AND a.rule=r.id AND r.displayactivity='1' AND a.enabled='1' "
					."\nORDER BY a.insert_date DESC";
			$total = @$this->_getListCount($query);
			$result = $this->_getList($query, $limitstart, $limit);
			return array($result, $total, $limit, $limitstart);
			
		} elseif ( $limit=='nolimit' ) {		// used for export CSV
			$query = "SELECT a.*, r.rule_name, r.plugin_function FROM #__alpha_userpoints_details AS a, #__alpha_userpoints_rules as r "
					."\nWHERE a.referreid='$referrerid' AND a.rule=r.id AND r.displayactivity='1' AND a.enabled='1' "
					."\nORDER BY a.insert_date DESC";
			$db->setQuery( $query );
			$rowslastpoints = $db->loadObjectList();
			return $rowslastpoints;				
		} else {		
			$limit = "LIMIT " . $limit ;
			$query = "SELECT a.*, r.rule_name, r.plugin_function FROM #__alpha_userpoints_details AS a, #__alpha_userpoints_rules as r "
					."\nWHERE a.referreid='$referrerid' AND a.rule=r.id AND r.displayactivity='1' AND a.enabled='1' "
					."\nORDER BY a.insert_date DESC $limit";
			$db->setQuery( $query );
			$rowslastpoints = $db->loadObjectList();
			return array($rowslastpoints, null, null, null);
		}	
	
	}
	
	function _get_referrees ( $referrerid ) {
		
		$db	   = JFactory::getDBO();
		$query = "SELECT * FROM #__alpha_userpoints WHERE referraluser='$referrerid'";
		$db->setQuery( $query );
		$rowsreferrees = $db->loadObjectList();
		
		if ( $rowsreferrees ) {
			require_once (JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php');		
			for ($i=0, $n=count( $rowsreferrees ); $i < $n; $i++) {				
				$UserInfo = AlphaUserPointsHelper::getUserInfo ( $rowsreferrees[$i]->referreid );
				$username = $UserInfo->username;
				$name = $UserInfo->name;
				$rowsreferrees[$i]->username = $username;	
				$rowsreferrees[$i]->name = $name;		
			}		
		}

		return $rowsreferrees;
	}
	
	function _checkCurrentMaxPerDay( $ruleid, $userid, $referrerid, $ip ) {	
	
		$db	= JFactory::getDBO();
		
		$curdate = date( "Y-m-d" );
		
		if ( $userid ) {			
			// count invite sent this day
			$query = "SELECT count(*) FROM #__alpha_userpoints_details WHERE rule='$ruleid' AND referreid='$referrerid' AND `insert_date` LIKE '$curdate%' AND enabled='1'";
		} else {
			// count guest invite sent this day
			$query = "SELECT count(*) FROM #__alpha_userpoints_details WHERE rule='$ruleid' AND referreid='GUEST' AND `insert_date` LIKE '$curdate%' AND keyreference='$ip' AND enabled='1'";
		}	
		$db->setQuery( $query );
		$result = $db->loadResult();
		
		return $result;
	
	}
	
	function _checkLastInviteForDelay( $ruleid, $userid=0, $referrerid, $ip, $delay ) {	
	
		$db	= JFactory::getDBO();
		
		$jnow		= JFactory::getDate();
		$now		= $jnow->toSql();
		$ts 		= strtotime( $now );		
				
		$checkdelay = 1;
		
		if ( $userid ) {			
			$query = "SELECT `insert_date` FROM #__alpha_userpoints_details WHERE rule='$ruleid' AND referreid='$referrerid' AND enabled='1' ORDER BY `insert_date` DESC LIMIT 1";
		} else {
			$query = "SELECT `insert_date` FROM #__alpha_userpoints_details WHERE rule='$ruleid' AND referreid='GUEST' AND keyreference='$ip' AND enabled='1' ORDER BY `insert_date` DESC LIMIT 1";
		}	
		$db->setQuery( $query );
		$result = $db->loadResult();
		
		// if exist -> compare
		if ( $result ) {				
			$lasttime = strtotime($result) + $delay;						
			if ( $lasttime > $ts ){
				$checkdelay = 0;
			}	
		}
		
		return $checkdelay;
	
	}
	
	function _extractEmailsFromString($sChaine) {	 
		if(false !== preg_match_all('`\w(?:[-_.]?\w)*@\w(?:[-_.]?\w)*\.(?:[a-z]{2,4})`', $sChaine, $aEmails)) {
			if(is_array($aEmails[0]) && sizeof($aEmails[0])>0) {
				return array_unique($aEmails[0]);
			}
		}		 
		return null;
	}	
	
	function _checkUser()  {
		$app = JFactory::getApplication();
		
		// active user
		$user =  JFactory::getUser();	
		
		// check referre ID
		$referrerid = @$_SESSION['referrerid'];		
		
		if ( !$user->id || !$referrerid ) {		
			$msg = JText::_('ALERTNOTAUTH' );
			JControllerLegacy::setRedirect('index.php', $msg);
			JControllerLegacy::redirect();
		} else return $referrerid;
		
	}

	function _getReferreid()  {
		
		// check referre ID
		$referrerid = @$_SESSION['referrerid'];		
		
		return $referrerid;
		
	}
	
	function _getRuleID ( $plugin_function ) {
	
		$db	= JFactory::getDBO();
		 
		$query = "SELECT id FROM #__alpha_userpoints_rules WHERE plugin_function='$plugin_function'";
		$db->setQuery( $query );
		$result = $db->loadResult();
		
		return $result;	
	}
	
	
	function _getUsersList(){
	
		$app = JFactory::getApplication();
		
		$db			        = JFactory::getDBO();		

		$filter_order		= $app->getUserStateFromRequest( "com_alphauserpoints.filter_order",		'filter_order',		'aup.points',	'cmd' );
		$filter_order_Dir	= $app->getUserStateFromRequest( "com_alphauserpoints.filter_order_Dir",	'filter_order_Dir',	'desc',			'word' );

		// Get the pagination request variables
		$limit = $app->getUserStateFromRequest('com_alphauserpoints.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = JFactory::getApplication()->input->get('limitstart', 0, 'int');
		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ( $limit != 0 ? (floor( $limitstart / $limit ) * $limit) : 0);
		
		$orderby = " ORDER BY " . $filter_order . " " . $filter_order_Dir;
			
		$query = "SELECT aup.id AS rid, aup.points, aup.referreid, aup.last_update, aup.referraluser, u.name AS usr_name, u.username AS usr_username, aup.levelrank"
		. "\n FROM #__alpha_userpoints AS aup, #__users AS u"
		. "\n WHERE aup.userid=u.id AND aup.published='1'"
		. $orderby
		;				
		$total  = @$this->_getListCount($query);

		$rows = $this->_getList( $query, $limitstart , $limit );			
		
		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;
		
		return array ( $rows, $total, $limit, $limitstart, $lists );
	
	}
	
	function _getArticleDescription ( $idArticle ){
	
		$db	= JFactory::getDBO();
		
		$query = "SELECT id, title, introtext FROM #__content WHERE id='$idArticle'";
		$db->setQuery( $query );
		$result = $db->loadObjectList();
		
		return $result;	
	
	}	
	
	function _pointsearned() { // function for TOP 10 in statistics tab
	
		$db = JFactory::getDBO();
		
		$query = "SELECT a.referreid, SUM(a.points) AS sumpoints, u.username AS username, u.name AS name"
			   . " FROM #__alpha_userpoints_details AS a, #__alpha_userpoints AS aup, #__users AS u"
			   . " WHERE aup.referreid=a.referreid AND aup.userid=u.id AND a.approved='1' AND a.status='1' AND a.enabled='1' AND aup.published='1'"
			   . " GROUP BY a.referreid"
			   . " ORDER BY sumpoints DESC"
			   . " LIMIT 10"
			   ;

		$db->setQuery( $query );
		$result = $db->loadObjectList();

		return $result;
	}
	
	function _totalpoints() {
	
		$db = JFactory::getDBO();
		
		$query = "SELECT SUM(a.points)"
			   . " FROM #__alpha_userpoints_details AS a"
			   . " WHERE a.approved='1' AND a.status='1' AND a.enabled='1'"
			   ;

		$db->setQuery( $query );
		$result = $db->loadResult();

		return $result;
	
	}	
	
	function _mypointsearned($referreid) {	
		
		$db = JFactory::getDBO();
		
		$query = "SELECT SUM(a.points)"
			   . " FROM #__alpha_userpoints_details AS a"
			   . " WHERE a.approved='1' AND a.status='1' AND a.enabled='1' AND a.points>=1 AND a.referreid='" . $referreid . "'";
			   ;

		$db->setQuery( $query );
		$result = $db->loadResult();

		return $result;
	
	}

	function _mypointsspent($referreid) {	
		
		$db = JFactory::getDBO();
		
		$query = "SELECT SUM(a.points)"
			   . " FROM #__alpha_userpoints_details AS a"
			   . " WHERE a.approved='1' AND a.status='1' AND a.enabled='1' AND a.points<0 AND a.referreid='" . $referreid . "'";
			   ;

		$db->setQuery( $query );
		$result = $db->loadResult();

		return $result;
	
	}
	
	function _mypointsearnedthismonth($referreid) {	
		
		$db = JFactory::getDBO();
		
		$curmonth = date( "Y-m-" );
		
		$query = "SELECT SUM(a.points)"
			   . " FROM #__alpha_userpoints_details AS a"
			   . " WHERE a.approved='1' AND a.status='1' AND a.enabled='1' AND a.points>=1 AND a.referreid='" . $referreid . "' AND insert_date LIKE '".$curmonth."%'";
			   ;

		$db->setQuery( $query );
		$result = $db->loadResult();

		return $result;
	
	}

	function _mypointsspentthismonth($referreid) {	
		
		$db = JFactory::getDBO();
		
		$curmonth = date( "Y-m-" );
		
		$query = "SELECT SUM(a.points)"
			   . " FROM #__alpha_userpoints_details AS a"
			   . " WHERE a.approved='1' AND a.status='1' AND a.enabled='1' AND a.points<0 AND a.referreid='" . $referreid . "' AND insert_date LIKE '".$curmonth."%'";
			   ;

		$db->setQuery( $query );
		$result = $db->loadResult();

		return $result;
	
	}

	function _mypointsearnedthisday($referreid) {	
		
		$db = JFactory::getDBO();
		
		$curday = date( "Y-m-d" );
		
		$query = "SELECT SUM(a.points)"
			   . " FROM #__alpha_userpoints_details AS a"
			   . " WHERE a.approved='1' AND a.status='1' AND a.enabled='1' AND a.points>=1 AND a.referreid='" . $referreid . "' AND insert_date LIKE '".$curday."%'";
			   ;

		$db->setQuery( $query );
		$result = $db->loadResult();

		return $result;
	
	}

	function _mypointsspentthisday($referreid) {	
		
		$db = JFactory::getDBO();
		
		$curday = date( "Y-m-d" );
		
		$query = "SELECT SUM(a.points)"
			   . " FROM #__alpha_userpoints_details AS a"
			   . " WHERE a.approved='1' AND a.status='1' AND a.enabled='1' AND a.points<0 AND a.referreid='" . $referreid . "' AND insert_date LIKE '".$curday."%'";
			   ;

		$db->setQuery( $query );
		$result = $db->loadResult();

		return $result;
	
	}
	
	function _save_profile() {
	
		$app = JFactory::getApplication();
		
		// initialize variables
		$db = JFactory::getDBO();
		$post	= JRequest::get( 'post' );
		
		$profilecomplete = 0;
		
		if ( $post['referreid']=='' ) {
			echo "<script>window.history.go(-1);</script>\n";
			exit();
		} 
		
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_alphauserpoints'.DS.'tables');
		$row = JTable::getInstance('userspoints');
		
		if (!isset($post['gender'])) $post['gender'] = 0;

		if ( $post['birthdate']=='' ) {
			$post['birthdate'] = '0000-00-00';
		} elseif ( $post['birthdate']!='' ) {
			// Check format date to save
			$valdat = $post['birthdate'];
			$format = substr($valdat, -5, 1);
			if ( $format=='-' || $format=='/' )
			{				
				// french format
				$cday = substr($valdat, -10, 2);
				$cmonth = substr($valdat, -7, 2);
				$cyear = substr($valdat, -4, 4);				
				$post['birthdate'] = $cyear.'-'.$cmonth.'-'.$cday;	
			}
		}
		
		$curdate = date( "Y-m-d" );
		if ( $post['birthdate'] >= $curdate ) $post['birthdate'] = '0000-00-00';		
		
		if (!$row->bind( $post )) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		
		if (!$row->store()) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		
		$app->enqueueMessage( JText::_('AUP_CHANGE_SAVED') );
		
		// check if all field are complete for the profile complete rule
		if ( $post['gender'] > 0 && $post['birthdate']!='' && $post['birthdate']!='0000-00-00' && $post['aboutme']!='' && $post['city']!='' && $post['avatar']!='' && $post['job']!='' && $post['education']!='' && $post['graduationyear']!='' ) {
			$profilecomplete = 1;
		}
				
		// rules for Profile
		require_once (JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php');
		if ( $profilecomplete ) {
			// assigns points when the user upload his avatar: this rule can be assigned only once per user
			AlphaUserPointsHelper::userpoints( 'sysplgaup_profilecomplete', '', 0, $post['referreid'] );
		} else {
			// remove points if profile has been complete and now remove required fields for profile complete -> return state incomplete
			$rule = AlphaUserPointsHelper::checkRuleEnabled ( 'sysplgaup_profilecomplete' ) ;
			if ( $rule ) { // rule published			
				// get ID of rule named 'sysplgaup_profilecomplete'
				$rule_id = $rule[0]->id;
				//$query = "DELETE FROM #__alpha_userpoints_details WHERE rule='".$rule_id."' AND referreid='".$post['referreid']."' AND keyreference='".$post['referreid']."'";
				$query = "UPDATE #__alpha_userpoints_details SET enabled='0'"
				. " WHERE rule='".$rule_id."' AND referreid='".$post['referreid']."' AND keyreference='".$post['referreid']."'";
				$db->setQuery( $query );
				$db->query();
				// recount for this user
				$this->checkNewTotal( $post['referreid'], $rule_id );
			}
		}
	}
	
	function checkNewTotal( $referreid, $rule_id )
	{
		$db			= JFactory::getDBO();
		$jnow		= JFactory::getDate();		
		$now		= $jnow->toSql();
		
		require_once (JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php');		
		
		// recalculate for this user 
		$query = "SELECT SUM(points) FROM #__alpha_userpoints_details WHERE `referreid`='" . $referreid . "' AND `approved`='1' AND (`expire_date`>'$now' OR `expire_date`='0000-00-00 00:00:00') AND `enabled`='1'";
		$db->setQuery($query);
		$newtotal = $db->loadResult();

		$query = "UPDATE #__alpha_userpoints SET `points`='" . $newtotal . "', `last_update`='$now' WHERE `referreid`='" . $referreid . "'";
		$db->setQuery( $query );
		$db->query();
		
		// update Ranks / Medals if necessary		
		AlphaUserPointsHelper::checkRankMedal ( $referreid, $rule_id );
	
	}	
	
	function _getMyCouponCode( $referreid ) {
	
		$db = JFactory::getDBO();		
		
		$query = "SELECT d.* FROM #__alpha_userpoints_details AS d, #__alpha_userpoints_rules AS r WHERE d.referreid='$referreid' AND d.enabled='1' AND r.id=d.rule AND r.plugin_function='sysplgaup_couponpointscodes'";
		$db->setQuery( $query );
		$resultCoupons = $db->loadObjectList();

		return $resultCoupons;		
	
	}	
	
}
?>