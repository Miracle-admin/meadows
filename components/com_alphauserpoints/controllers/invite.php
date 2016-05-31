<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

/**
 * @package AlphaUserPoints
 */
class alphauserpointsControllerInvite extends alphauserpointsController
{
	/**
	 * Custom Constructor
	 */
 	public function __construct()	{
		parent::__construct( );
	}
	
	public function display($cachable = false, $urlparams = false) 
	{
		// active user
		$user =  JFactory::getUser();
		
		if ( $user->id ) {		
			$user_name = $user->name;			
		} else $user_name = "";	
		
		$model      = $this->getModel ( 'alphauserpoints' );
		$view       = $this->getView  ( 'invite','html' );		
		
		$referrerid = $model->_getReferreid();
		
		$params = $model->_getParamsAUP();
		
		$cparams = JComponentHelper::getParams( 'com_alphauserpoints' );
		
		if ( $referrerid )	
		{				
			$referrer_link = getLinkToInvite( $referrerid, $cparams->get('systemregistration') );
		} else $referrer_link = getLinkToInvite( '', $cparams->get('systemregistration') );
		
		$view->assign('params', $params );
		$view->assign('user_name', $user_name );
		$view->assign('referreid', $referrerid );	
		$view->assign('referrer_link', $referrer_link );		
		
		// Display
		if ( JFactory::getApplication()->input->get('task', '', 'cmd')=='addressbook') {
			 $view->_display_addressbook();
		} else $view->_display();
	
	}
	
	public function sendinvite () 
	{
		$app = JFactory::getApplication();
		
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
				
		// active user
		$user =  JFactory::getUser();		
		
		$db	= JFactory::getDBO();

		jimport( 'joomla.mail.helper' );
		
		$model      = $this->getModel ( 'alphauserpoints' );
		$view       = $this->getView  ( 'invite','html' );		

		$SiteName 	= $app->getCfg('sitename');
		$MailFrom 	= $app->getCfg('mailfrom');
		$FromName 	= $app->getCfg('fromname');
		
		$jnow		= JFactory::getDate();		
		$now		= $jnow->toSql();		

		$uri        = JURI::getInstance();
		$base    	= $uri->toString( array('scheme', 'host', 'port'));		
		
		$params = $model->_getParamsAUP();
		
		$cparams = JComponentHelper::getParams( 'com_alphauserpoints' );
		
		$referrerid = $model->_getReferreid();
		
		if ( $referrerid )	
		{
			$link = getLinkToInvite( $referrerid, $cparams->get('systemregistration') );
		} else {
			$link = $base.JRoute::_('');
		}
		
		if ( $params->get( 'userecaptcha' )==1 || $params->get( 'userecaptcha' )==2 && !$user->id ) {				
			require_once (JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'assets'.DS.'recaptcha'.DS.'recaptchalib.php');
			$privatekey = $params->get( 'privkey' );
			
			// the response from reCAPTCHA
			$resp = null;
			// the error code from reCAPTCHA, if any
			$error = null;
			
			// was there a reCAPTCHA response?
			$recaptcha_response_field = JFactory::getApplication()->input->get('recaptcha_response_field', '', 'string');
			
			//if ($_POST["recaptcha_response_field"]) {
			if ( $recaptcha_response_field ) {
					$resp = recaptcha_check_answer ($privatekey,
													$_SERVER["REMOTE_ADDR"],
													$_POST["recaptcha_challenge_field"],
													$recaptcha_response_field);
			
					if (!$resp->is_valid) {
						// set the error code so that we can display it
						$error = $resp->error;
						JError::raiseWarning(0, $error );
						return $this->display ();
					}
			} else {
					JError::raiseWarning(0, 'Captcha' );
					return $this->display ();
			}

		}

		// An array of e-mail headers we do not want to allow as input
		$headers = array (	'Content-Type:',
							'MIME-Version:',
							'Content-Transfer-Encoding:',
							'bcc:',
							'cc:');

		// An array of the input fields to scan for injected headers
		$fields = array ('mailto',
						 'sender',
						 'from',
						 'subject',
						 );

		/*
		 * Here is the meat and potatoes of the header injection test.  We
		 * iterate over the array of form input and check for header strings.
		 * If we fine one, send an unauthorized header and die.
		 */
		foreach ($fields as $field)
		{
			foreach ($headers as $header)
			{
				if (strpos(@$_POST[$field], $header) !== false)
				{
					JError::raiseError(403, '');
				}
			}
		}

		/*
		 * Free up memory
		 */
		unset ($headers, $fields);
		
		$imported_emails	= $_POST['importedemails'];
		$other_emails		= JRequest::getString('other_recipients', '', 'post');
		$sender 			= JRequest::getString('sender', '', 'post');		
		
		// Check for a valid to address
		$errorMail	= false;		
				
		// build list emails
		if($imported_emails=='' && $other_emails!='') {
			$emails = $other_emails;
		} elseif($other_emails=='' && $imported_emails!='') {
			$emails = $imported_emails;
		} elseif ( $imported_emails!='' && $other_emails!='') {
			$emails = $imported_emails . "," . $other_emails;
		} else {
			$emails = "";
			$errorMail	=  JText::_( 'AUP_EMAIL_INVALID' );
			JError::raiseWarning(0, $errorMail );
		}
	
		$emails = @explode( ',', $emails );

		// Check for a valid from address
		if ( ! $MailFrom || ! JMailHelper::isEmailAddress($MailFrom) )
		{
			$errorMail	= JText::sprintf('AUP_EMAIL_INVALID', $MailFrom);
			JError::raiseWarning(0, $errorMail );
		}

		if ( $errorMail ) return $this->display ();

		// Build the message to send
		$msg			= JText:: _('AUP_EMAIL_MSG_INVITE');	
		
		$custommessage	= JRequest::getString('custommessage', '', 'post');
		$formatMail 	= '0';
		$bcc2admin		= '0';
		
		if ( $params->get( 'templateinvite', 0 ) )
		{
			JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_alphauserpoints'.DS.'tables');				
			$row = JTable::getInstance('template_invite');				
			$row->load( intval($params->get( 'templateinvite' )));			
			$subject        = $row->emailsubject;
			$body           = $row->emailbody;
			$body           = str_replace('{name}', $sender, $body);
			$body           = str_replace('{custom}', $custommessage, $body);
			$body           = str_replace('{link}', $link, $body);
			$bcc2admin		= $row->bcc2admin;
			$formatMail     = $row->emailformat;
		} 
		else 
		{		
			$subject		= JText::_( 'AUP_YOUAREINVITEDTOREGISTERON' ) . " " . $SiteName;
			$body			= sprintf( $msg, $SiteName, $sender, $link) . " \n" . $custommessage;			
		}
		
		// Clean the email data
		$subject = JMailHelper::cleanSubject($subject);
		//$body	 = JMailHelper::cleanBody($body);
				
		require_once (JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php');		
		
		// Limit
		$max 		= $params->get( 'maxemailperinvite'   );
		$maxperday  = $params->get( 'maxinvitesperday'    );
		$delay 		= intval($params->get( 'delaybetweeninvites' ));
		
		$counter 	= 0;
		
		$rule_ID = $model->_getRuleID ( 'sysplgaup_invite' );
		
		$refer_ID = AlphaUserPointsHelper::getAnyUserReferreID( $user->id );
		
		$numpoints4invite = AlphaUserPointsHelper::getPointsRule( 'sysplgaup_invite' );
		$totalpointsearned= 0;
		
		$currentmaxperday = $model->_checkCurrentMaxPerDay( $rule_ID, $user->id, $referrerid, $_SERVER["REMOTE_ADDR"] );
		
		$checkdelay = 1;
		if ( $delay ) {
			$checkdelay = $model->_checkLastInviteForDelay( $rule_ID, $user->id, $referrerid, $_SERVER["REMOTE_ADDR"], $delay );
		}
		
		if ( !$checkdelay ) {
			$errorTime = JText :: _('AUP_DELAY_BETWEEN_INVITES_INVALID');
			JError::raiseWarning(0, $errorTime );
			return $this->display ();
		} 
				
		if ( $currentmaxperday < $maxperday ) {
		
			$mailer = JFactory::getMailer();			
		
			foreach ($emails as $email) {
				$aEmails[0] = $model->_extractEmailsFromString($email);
				$email= $aEmails[0][0];
				if ( JMailHelper::isEmailAddress($email) ) {
					
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
					
					if ( $mailer->Send() === true ) {
						if ( $user->id ) {				
							if ( AlphaUserPointsHelper::checkRuleEnabled('sysplgaup_invite') ) {						
								// insert email for tracking
								$email2 = str_replace("@" ," [at] ", $email); // change @ because can be display on frontend in latest activity
								$keyreference = AlphaUserPointsHelper::buildKeyreference( 'sysplgaup_invite', $email );
								AlphaUserPointsHelper::userpoints( 'sysplgaup_invite', $refer_ID, 0, $keyreference, $email2  );
								$totalpointsearned = $totalpointsearned + $numpoints4invite;
							}
						} else {
							// guest user : Insert IP and email fortracking							
							JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_alphauserpoints'.DS.'tables');							
							$row = JTable::getInstance('userspointsdetails');
							$row->id				= NULL;
							$row->referreid			= 'GUEST';
							$row->points			= 0;
							$row->insert_date		= $now;
							$row->expire_date 		= '';		
							$row->rule				= $rule_ID;
							$row->approved			= 1;
							$row->status			= 1;
							$row->keyreference		= $_SERVER["REMOTE_ADDR"];
							$row->datareference		= $email;										
							$row->enabled			= 1;
							
							if ( !$row->store() )
							{
								JError::raiseError(500, $row->getError());
							}							
						}
						$counter++;
						$currentmaxperday++;
					}
					if ( $counter==$max || $currentmaxperday==$maxperday )	break;
				}
			}
			if ( $totalpointsearned ) $app->enqueueMessage( sprintf ( JText::_('AUP_CONGRATULATION'), $totalpointsearned ));			
		} else {
			$maxperdaylimit = JText :: _('AUP_MAXINVITESPERDAY') . " " . $maxperday ;
			$app->enqueueMessage( $maxperdaylimit );			
		}
		
		switch ( $counter ) {		
			case '0':
				$message = JText :: _('AUP_NO_EMAIL_HAS_BEEN_SENT');				
				break;				
			case '1':
				$message = JText :: _('AUP_EMAIL_SENT');		
				break;			
			default:
				$message = JText :: _('AUP_EMAILS_SENT');
				$message = sprintf( $message, $counter);
				break;				
		}	
		        
		$app->enqueueMessage( $message );
		$this->setRedirect('index.php?option=com_alphauserpoints&view=invite&Itemid='.JFactory::getApplication()->input->get('Itemid', ''));
		$this->redirect();        		

	}

}
?>