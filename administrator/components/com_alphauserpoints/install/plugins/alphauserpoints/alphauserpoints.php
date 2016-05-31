<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

if(!defined('DS')) {
   define('DS', DIRECTORY_SEPARATOR);
}

jimport( 'joomla.plugin.plugin' );

/**
 * AlphaUserPoints System Plugin
 *
 * @package		Joomla
 * @subpackage	AlphaUserPoints
 * @since 		1.5
 */
class plgSystemAlphaUserPoints extends JPlugin
{

	public function onAfterRender()
	{
		$app = JFactory::getApplication();
		
		// this function stores the referee if the invited user does not register immediately
		
		// No need in admin panel
		if($app->isAdmin()) return;
		
		//Get ?referrer= from URL
		$referrer 	= JFactory::getApplication()->input->get('referrer', '', 'string');
		//Get ?draw= from URL
		$draw		= JFactory::getApplication()->input->get('draw', 0, 'int');
		
		@session_start('alphauserpoints');
		
		$expire=time()+60*60*24*30; //expires in 30 days
		
		if ( $draw ) 
		{
			setcookie("draw", $draw, $expire );
			//$_SESSION['draw'] = $draw;
			setcookie("referredraw", $referrer, $expire );
			//$_SESSION['referredraw'] = $referrer;
		}
		
		// If there is no cookie, session, AND ?referrer=, then guest is self referred.  Skip code
		if(!$referrer && !isset($_COOKIE['referrerid']) && !isset($_SESSION['referrerid'])) 
		{     
			return; 
		}
		else 
		{			
			// If there is a ?referrer=, it is the most recent referrer.  Set it into session & cookie
			if($referrer && !isset($_COOKIE['referrerid']) && !isset($_SESSION['referrerid'])) {
				
				 // Set cookie
				setcookie("referrerid", $referrer, $expire );				

				// Set session
				$_SESSION['referrerid'] = $referrer;
				
				
				return;
			} 
			else 
			{
				
				// If a session is set AND does not match the cookie, Set the session into the cookie.  Session is most recent referrer.
				if (isset($_SESSION['referrerid']) && ($_SESSION['referrerid'] != @$_COOKIE['referrerid'])) 
				{
					// Set cookie
					setcookie("referrerid", $_SESSION['referrerid'], $expire);
					return;
				}
				
				// If No session is set AND a cookie is set, it is an old referral.  Set cookie into a current session
				if (!isset($_SESSION['referrerid']) && isset($_COOKIE['referrerid'])) 
				{
					// Set session
					$_SESSION['referrerid'] = $_COOKIE['referrerid'];
					return;              
				}
			}
		}		
	}	
	
}
?>