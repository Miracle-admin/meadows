<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Mail
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

//We play safe... it the JMail class is already defined, we don't load our file
if(class_exists('JMail', false)) return;

jimport('phpmailer.phpmailer');
$jversion = preg_replace('#[^0-9\.]#i','',JVERSION);
if(version_compare($jversion,'1.6.0','>='))
	include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'jMail_J25.php');
else
	include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'jMail_J15.php');

//Just to be safe...
if(!class_exists('jMail_acy')) return;

/**
 * Email Class.  Provides a common interface to send email from the Joomla! Platform
 *
 * @package     Joomla.Platform
 * @subpackage  Mail
 * @since       11.1
 */
class JMail extends jMail_acy
{
	// Link between Joomla notification and Acymailing mail
	protected $bodyAliasCorres = array();

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->initMailCorrespondance();
		parent::__construct();

	}

	// Create link between joomla message and corresponding Acymailing mail (alias)
	protected function initMailCorrespondance(){
		$jversion = preg_replace('#[^0-9\.]#i','',JVERSION);
		if(version_compare($jversion,'1.6.0','>=')){
			if(version_compare($jversion,'3.0.0','>=')){
				$this->bodyAliasCorres['joomla-directreg-j3'] = str_replace('%s', '(.*)', preg_quote(JText::_('COM_USERS_EMAIL_REGISTERED_BODY'),'/'));
				$this->bodyAliasCorres['joomla-directRegNoPwd-j3'] = str_replace('%s', '(.*)', preg_quote(JText::_('COM_USERS_EMAIL_REGISTERED_BODY_NOPW'),'/'));
			}else{
				$this->bodyAliasCorres['joomla-directreg'] = str_replace('%s', '(.*)', preg_quote(JText::_('COM_USERS_EMAIL_REGISTERED_BODY'),'/'));
			}
			$this->bodyAliasCorres['joomla-ownActivReg'] = str_replace('%s', '(.*)', preg_quote(JText::_('COM_USERS_EMAIL_REGISTERED_WITH_ACTIVATION_BODY'),'/'));
			$this->bodyAliasCorres['joomla-ownActivRegNoPwd'] = str_replace('%s', '(.*)', preg_quote(JText::_('COM_USERS_EMAIL_REGISTERED_WITH_ACTIVATION_BODY_NOPW'),'/'));
			$this->bodyAliasCorres['joomla-adminActivReg'] = str_replace('%s', '(.*)', preg_quote(JText::_('COM_USERS_EMAIL_REGISTERED_WITH_ADMIN_ACTIVATION_BODY'),'/'));
			$this->bodyAliasCorres['joomla-adminActivRegNoPwd'] = str_replace('%s', '(.*)', preg_quote(JText::_('COM_USERS_EMAIL_REGISTERED_WITH_ADMIN_ACTIVATION_BODY_NOPW'),'/'));
			$this->bodyAliasCorres['joomla-confirmActiv'] = str_replace('%s', '(.*)', preg_quote(JText::_('COM_USERS_EMAIL_ACTIVATED_BY_ADMIN_ACTIVATION_BODY'),'/'));
			$this->bodyAliasCorres['joomla-usernameReminder'] = str_replace('%s', '(.*)', preg_quote(JText::_('COM_USERS_EMAIL_USERNAME_REMINDER_BODY'),'/'));
			$this->bodyAliasCorres['joomla-resetPwd'] = str_replace('%s', '(.*)', preg_quote(JText::_('COM_USERS_EMAIL_PASSWORD_RESET_BODY'),'/'));
			$this->bodyAliasCorres['joomla-regByAdmin'] = str_replace('%s', '(.*)', preg_quote(JText::_('PLG_USER_JOOMLA_NEW_USER_EMAIL_BODY'),'/'));
			$this->bodyAliasCorres['joomla-regNotifAdmin'] = str_replace('%s', '(.*)', preg_quote(JText::_('COM_USERS_EMAIL_REGISTERED_NOTIFICATION_TO_ADMIN_BODY'),'/'));
			$this->bodyAliasCorres['joomla-regNotifAdminActiv'] = str_replace('%s', '(.*)', preg_quote(JText::_('COM_USERS_EMAIL_ACTIVATE_WITH_ADMIN_ACTIVATION_BODY'),'/'));
		} else{
			$this->bodyAliasCorres['joomla-directreg'] = str_replace('%s', '(.*)', preg_quote(JText::_('SEND_MSG'),'/'));
			$this->bodyAliasCorres['joomla-ownActivReg'] = str_replace('%s', '(.*)', preg_quote(JText::_('SEND_MSG_ACTIVATE'),'/'));
			$this->bodyAliasCorres['joomla-usernameReminder'] = str_replace('%s', '(.*)', preg_quote(JText::_('USERNAME_REMINDER_EMAIL_TEXT'),'/'));
			$this->bodyAliasCorres['joomla-resetPwd'] = str_replace('%s', '(.*)', preg_quote(JText::_('PASSWORD_RESET_CONFIRMATION_EMAIL_TEXT'),'/'));
			$this->bodyAliasCorres['joomla-regByAdmin'] = str_replace('%s', '(.*)', preg_quote(JText::_('NEW_USER_MESSAGE'),'/'));
			$this->bodyAliasCorres['joomla-regNotifAdmin'] = str_replace('%s', '(.*)', preg_quote(JText::_('SEND_MSG_ADMIN'),'/'));
		}
	}

	// Use Acymailing to send emails
	protected function sendMailThroughAcy(){
		// Check if this is a notifification that we override. If yes send with Acymailing, if no let Joomla handle it.
		foreach($this->bodyAliasCorres as $alias=>$oneMsg){
			$testMail = preg_match('/'.trim($oneMsg).'/', $this->Body, $matches);
			if($testMail !== 1) continue;
			$db = JFactory::getDBO();
			$db->setQuery('SELECT * FROM #__acymailing_mail WHERE `alias` = '. $db->Quote($alias) .' AND `type` = \'joomlanotification\'');
			$mailNotif = $db->loadObject();
			if($mailNotif->published != 1) break;

			$acymailer = acymailing_get('helper.acymailer');
			$acymailer->trackEmail = true;
			// Skip check on user enabled
			$acymailer->checkConfirmField = false;
			$acymailer->checkEnabled = false;
			$acymailer->checkAccept = false;
			$acymailer->autoAddUser = true;
			for($i=1; $i<count($matches); $i++){
				// Joomla emails does not contain links with href but lins as text
				$tmp = $matches[$i];
				$matches[$i] = preg_replace('/(http|https):\/\/(.*)/','<a href="$1://$2" target="_blank">$1://$2</a>',$matches[$i], -1, $count);
				$acymailer->addParam('param'.$i, $matches[$i]);
				if($count > 0) $acymailer->addParam('link', $tmp);
			}
			$acymailer->report = false;
			$statusSend = $acymailer->sendOne($mailNotif->mailid, $this->to[0][0]);
			$app = JFactory::getApplication();
			if(!$statusSend) $app->enqueueMessage(nl2br($acymailer->reportMessage), 'error');
			return $statusSend;
		}
		// No message sent
		return 'noSend';
	}

	/**
	 * Send the mail
	 *
	 * @return  mixed  True if successful, a JError object otherwise
	 *
	 * @since   11.1
	 */
	public function Send()
	{
		// Include Acymailing to override mails
		if(include_once(rtrim(JPATH_ADMINISTRATOR,DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_acymailing'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php'))
			$ret = $this->sendMailThroughAcy();

		if($ret === true || $ret === false){
			 return $ret;
		}
		return parent::Send();
	}
}
