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
jimport( 'joomla.application.component.view' );

class alphauserpointsModelRssactivity extends JmodelLegacy {

	function __construct(){
		parent::__construct();
		
	}

	function _showRSSAUPActivity() {
	
		$db			      = JFactory::getDBO();
				
		$nullDate	= $db->getNullDate();
		$date = JFactory::getDate();
		$now  = $date->toSql();
		
		$app = JFactory::getApplication();
		$menus = $app->getMenu();		
		$menu       = $menus->getActive();
		$menuid     = $menu->id;

		$params     = $menus->getParams($menuid);		
		
		$count      = JFactory::getApplication()->input->get('c', $params->get('count', 20), 'int');		
		$usrname 	= JFactory::getApplication()->input->get('u', $params->get('usrname', 'name'), 'string');
		$activity   = JFactory::getApplication()->input->get('a', $params->get('activity', 0), 'int');		
		
		$typeActivity = "";
		
		if ( $activity == 1 )
		{			
			$typeActivity = " AND a.points >= 1";		
		}
		elseif ( $activity == 2 ) 
		{
			$typeActivity = " AND a.points < 0";		
		}
						
		$query = "SELECT a.insert_date, a.referreid, a.points AS last_points, a.datareference, u.".$usrname." AS usrname, r.rule_name, r.plugin_function"
			   . " FROM #__alpha_userpoints_details AS a, #__alpha_userpoints AS aup, #__users AS u, #__alpha_userpoints_rules AS r"
			   . " WHERE aup.referreid=a.referreid AND aup.userid=u.id AND aup.published='1' AND a.approved='1' AND a.enabled='1' AND (a.expire_date>='".$now."' OR a.expire_date='0000-00-00 00:00:00') AND r.id=a.rule"
			   . $typeActivity
			   . " ORDER BY a.insert_date DESC"
		 	   ;
		$db->setQuery($query, 0, $count);
		$rows = $db->loadObjectList();
	
		$this->showRSSActivity( $rows );				
	}
	
	function showRSSActivity( &$rows ) {
	
		$app = JFactory::getApplication();
	
		// Load feed creator class
		require_once (JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'assets'.DS.'feedcreator'.DS.'feedcreator.php' );

		$rssfile = $app->getCfg('tmp_path') . '/rssAUPactivity.xml';
		
		$rss = new UniversalFeedCreator();
		$rss->title = $app->getCfg('sitename');
		$rss->description = JText::_('AUP_LASTACTIVITY');
		$rss->link = JURI::base();
		$rss->syndicationURL = JURI::base();
		$rss->cssStyleSheet = NULL;
		$rss->descriptionHtmlSyndicated = true;
		
		if ( $rows ) {
			foreach ( $rows as $row ) {				
				// exceptions private data
				if ( $row->plugin_function=='plgaup_getcouponcode_vm' || $row->plugin_function=='plgaup_alphagetcouponcode_vm' || $row->plugin_function=='sysplgaup_buypointswithpaypal') {
					$datareference = '';
				}
				switch ( $row->plugin_function ) {
					case 'sysplgaup_dailylogin':
					case 'plgaup_dailylogin':
						$row->datareference = JHTML::_('date', $row->datareference, JText::_('DATE_FORMAT_LC1') );
						break;
					case 'plgaup_getcouponcode_vm':
					case 'plgaup_alphagetcouponcode_vm':
					case 'sysplgaup_buypointswithpaypal':
						$row->datareference = ''; 
						break;
					default:
				}
				$datareference = ( $row->datareference!='' ) ? ' ('. $row->datareference . ')' : '' ;
				// special format
				//if ( $row->plugin_function=='sysplgaup_dailylogin' ) $datareference = '';
				
				$item = new FeedItem();
				$item->title = htmlspecialchars($row->usrname, ENT_QUOTES, 'UTF-8');
				$item->description = JText::_( $row->rule_name ) . "$datareference /  " . $row->last_points . " " .  JText::_('AUP_POINTS');
				$item->descriptionTruncSize = 250;
				$item->descriptionHtmlSyndicated = true;
				@$date = ( $row->insert_date ? date( 'r', strtotime($row->insert_date) ) : '' );
				$item->date = $date;
				$item->source = JURI::base();
				$rss->addItem( $item );
			}
		}	
		// save feed file
		$rss->saveFeed('RSS2.0', $rssfile);		
	}

}
?>