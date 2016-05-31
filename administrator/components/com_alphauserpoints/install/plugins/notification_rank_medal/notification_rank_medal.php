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
class plgAlphauserpointsNotification_rank_medal extends JPlugin
{
	
	
	public function onUnlockMedalAlphaUserPoints( &$userinfo, &$medal )
	{	
		if ( !$medal->notification ) return;
		$this->sendNotificationOnUpdateRank( $userinfo, $medal );		
	
	}
	
	
	public function onGetNewRankAlphaUserPoints( &$userinfo, &$rankdetail )
	{	
		if ( !$rankdetail->notification ) return;
		
		$this->sendNotificationOnUpdateRank( $userinfo, $rankdetail );
			
	}
	
	
	private function sendNotificationOnUpdateRank ( $userinfo, $result )
	{
		$app = JFactory::getApplication();	
		
		$lang = JFactory::getLanguage();
		$lang->load( 'com_alphauserpoints', JPATH_SITE);
	
		jimport( 'joomla.mail.helper' );		
		
		require_once(JPATH_ROOT . '/components/com_alphauserpoints/helper.php');
		
		// get params definitions
		$params = JComponentHelper::getParams( 'com_alphauserpoints' );		
		$jsNotification = $params->get('jsNotification', 0);
		$jsNotificationAdmin = $params->get('fromIdUddeim', 0);		
		
		$SiteName 	= $app->getCfg('sitename');
		$MailFrom 	= $app->getCfg('mailfrom');
		$FromName 	= $app->getCfg('fromname');
		$sef		= $app->getCfg('sef');		
		
		$email	    = $userinfo->email;
		
		$subject	= $result->emailsubject;
		$body		= $result->emailbody;
		$formatMail	= $result->emailformat;
		$bcc2admin	= $result->bcc2admin;
		
		
		$subject = str_replace('{username}', $userinfo->username, $subject);
		$subject = str_replace('{points}', AlphaUserPointsHelper::getFPoints($userinfo->points), $subject);
		$body 	 = str_replace('{username}', $userinfo->username, $body);
		$body 	 = str_replace('{points}', AlphaUserPointsHelper::getFPoints($userinfo->points), $body);
		
		$subject = JMailHelper::cleanSubject($subject);		
		
		if ( !$jsNotification )
		{
		
			$mailer = JFactory::getMailer();
			$mailer->setSender( array( $MailFrom, $FromName ) );
			$mailer->setSubject( $subject);
			$mailer->isHTML((bool) $formatMail);
			$mailer->CharSet = "utf-8";
			$mailer->setBody($body);
			$mailer->addRecipient( $email );
			if ( $bcc2admin ) 
			{			
				// get all users allowed to receive e-mail system
				$query = "SELECT email" .
						" FROM #__users" .
						" WHERE sendEmail='1' AND block='0'";
				$db->setQuery( $query );
				$rowsAdmins = $db->loadObjectList();		
				
				foreach ( $rowsAdmins as $rowsAdmin ) {
					$mailer->addBCC( $rowsAdmin->email );
				}
			}		
			$send = $mailer->Send();
			
		}
		else
		{
			require_once JPATH_ROOT .'/components/com_community/libraries/core.php';

			$params = new CParameter('');
			CNotificationLibrary::add( 'system_messaging' , $jsNotificationAdmin , $userinfo->id , $subject , $body , '' , $params );			
			if ( $bcc2admin ) 
			{			
				// get all users allowed to receive e-mail system
				$query = "SELECT id" .
						" FROM #__users" .
						" WHERE sendEmail='1' AND block='0'";
				$db->setQuery( $query );
				$rowsAdmins = $db->loadObjectList();		
				
				foreach ( $rowsAdmins as $rowsAdmin ) {
					$mailer->addBCC( $rowsAdmin->id );
					CNotificationLibrary::add( 'system_messaging' , $userinfo->id , $rowsAdmin->id , $subject , $body , '' , $params );
				}
			}		
		}
		
		
	}
	
}
?>