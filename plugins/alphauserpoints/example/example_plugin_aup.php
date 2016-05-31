<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

/**
 * AlphaUserPoints Plugin
 *
 * @package		Joomla
 * @subpackage	AlphaUserPoints
 * @since 		1.6
 */
class plgAlphauserpointsExample_plugin_aup extends JPlugin
{
	
	public function onUpdateAlphaUserPoints( &$result, $rule_name, $assignpoints, $referrerid, $userID )
	{	
		// on insert activity and save points
			
		return true;
			
	}
	
	public function onAfterUpdateAlphaUserPoints( &$result, $rule_name, $assignpoints, $referrerid, $userID )
	{	
		// after inserted activity and saved points and send notification
			
		return true;
			
	}	
	
	public function onSendNotificationAlphaUserPoints( &$result, $rule_name, $assignpoints, $newtotal, $referrerid, $userID )
	{
	
		// on send email notification points
			
		return true;	
	
	}
	
	public function onUnlockMedalAlphaUserPoints( &$userinfo, &$medal )
	{	
		
		return true;				
	
	}
	
	
	public function onGetNewRankAlphaUserPoints( &$userinfo, &$rankdetail )
	{	
	
		return true;
			
	}
	
	
	public function onBeforeInsertUserActivityAlphaUserPoints( &$result, $points, $referrerid, $keyreference, $datareference )
	{
	
		return true;
		
	}
	
	public function onResetGeneralPointsAlphaUserPoints( $date )
	{
	
		return true;
		
	}
	
	
	public function onBeforeDeleteUserActivityAlphaUserPoints ( $cid, $referrerid )
	{	
		// $cid is an array : each id in cid is an activity of the user with this $referrerid
		
		return true;
	
	}
	
	
	public function onAfterDeleteUserActivityAlphaUserPoints ( $referrerid )
	{	
				
		return true;
	
	}
	
	
	public function onBeforeDeleteAllUserActivitiesAlphaUserPoints ( $referrerid )
	{	
				
		return true;
	
	}
	
	
	public function onAfterDeleteAllUserActivitiesAlphaUserPoints ( $referrerid )
	{	
				
		return true;
	
	}
	

	public function onBeforeMakeRaffleAlphaUserPoints ( &$rowRaffle, $now )
	{	
				
		return true;
	
	}
	

	public function onAfterMakeRaffleAlphaUserPoints ( &$rowRaffle, $now )
	{	
				
		return true;
	
	}

}
?>