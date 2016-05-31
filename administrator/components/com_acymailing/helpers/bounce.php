<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.8.1
 * @author	acyba.com
 * @copyright	(C) 2009-2014 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><?php

class acybounceHelper{

	var $report = false;
	var $config;
	var $mailer;
	var $mailbox;
	var $_message;
	var $listsubClass;
	var $subClass;
	var $db;
	var $blockedUsers = array();
	var $deletedUsers = array();
	var $bounceMessages = array();
	var $usepear = false;
	var $detectEmail = '/[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*@([a-z0-9\-]+\.)+[a-z0-9]{2,8}/i';
	var $detectEmail2 = '/(([a-z0-9\-]+\.)+[a-z0-9]{2,8})\/([a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*)/i';
	var $messages = array();
	var $stoptime = 0;
	var $mod_security2 = false;
	var $obend = 0;

	public function acybounceHelper(){
		$this->config = acymailing_config();
		$this->mailer = acymailing_get('helper.mailer');
		$this->rulesClass = acymailing_get('class.rules');
		$this->mailer->report = false;
		$this->mailer->alreadyCheckedAddresses = true;
		$this->subClass = acymailing_get('class.subscriber');
		$this->listsubClass = acymailing_get('class.listsub');
		$this->listsubClass->checkAccess = false;
		$this->listsubClass->sendNotif = false;
		$this->listsubClass->sendConf = false;
		$this->historyClass = acymailing_get('class.acyhistory');
		$this->encodingHelper = acymailing_get('helper.encoding');
		$charset = acymailing_get('type.charset');
		$this->allCharsets = $charset->charsets;
		$this->db = JFactory::getDBO();
	}

	public function init(){

		if($this->config->get('bounce_connection') == 'pear'){
			$this->usepear = true;
			include_once(ACYMAILING_FRONT.'inc'.DS.'pear'.DS.'pop3.php');
			return true;
		}

		if(extension_loaded("imap") OR function_exists('imap_open')) return true;

		$prefix = (PHP_SHLIB_SUFFIX == 'dll') ? 'php_' : '';
		$EXTENSION = $prefix . 'imap.' . PHP_SHLIB_SUFFIX;

		if(function_exists('dl')){
			$fatalMessage = 'The system tried to load dynamically the '.$EXTENSION.' extension';
			$fatalMessage .= '<br />If you see this message, that means the system could not load this PHP extension';
			$fatalMessage .= '<br />Please enable the PHP Extension '.$EXTENSION;
			ob_start();
			echo $fatalMessage;
			dl($EXTENSION);
			$warnings = str_replace($fatalMessage,'',ob_get_clean());
			if(extension_loaded("imap") OR function_exists('imap_open')) return true;
		}

		if($this->report){
			acymailing_display('The extension "'.$EXTENSION.'" could not be loaded, please change your PHP configuration to enable it or use the pop3 method without imap extension','error');
			if(!empty($warnings)) acymailing_display($warnings,'warning');
		}

		return false;

	}

	public function connect(){
		if($this->usepear) return $this->_connectpear();
		return $this->_connectimap();
	}

	private function _connectpear(){
		ob_start();

		$this->mailbox = new Net_POP3();

		$timeout = $this->config->get('bounce_timeout');
		if(!empty($timeout)) $this->mailbox->setTimeOut($timeout);

		$port = intval($this->config->get('bounce_port',''));
		$secure = $this->config->get('bounce_secured','');
		if(empty($port)){
			if($secure == 'ssl') $port = '995';
			else $port = '110/pop3/notls';
		}

		$serverName = trim($this->config->get('bounce_server'));

		if(!empty($secure) AND !strpos($serverName,'://')) $serverName = $secure.'://'.$serverName;

		if(!$this->mailbox->connect($serverName,$port)){
			$warnings = ob_get_clean();
			if($this->report) acymailing_display("Error connecting to the server ".$this->config->get('bounce_server')." : ".$port,'error');
			if(!empty($warnings) AND $this->report) acymailing_display($warnings,'warning');
			return false;
		}

		$login = $this->mailbox->login(trim($this->config->get('bounce_username')),trim($this->config->get('bounce_password')),'USER' );
		if(empty($login) OR isset($login->code)){
			$warnings = ob_get_clean();
			if($this->report) acymailing_display('Identication error '.$this->config->get('bounce_username').':'.$this->config->get('bounce_password'),'error');
			if(!empty($warnings) AND $this->report) acymailing_display($warnings,'warning');
			return false;
		}

		ob_end_clean();

		return true;
	}

	private function _connectimap(){
		ob_start();
		$buff = imap_alerts();
		$buff = imap_errors();

		$timeout = intval($this->config->get('bounce_timeout'));
		if(!empty($timeout)) imap_timeout(IMAP_OPENTIMEOUT,$timeout);

		$port = intval($this->config->get('bounce_port',''));
		$secure = $this->config->get('bounce_secured','');
		$protocol = $this->config->get('bounce_connection','');
		$serverName = '{'.trim($this->config->get('bounce_server'));
		if(empty($port)){
			if($secure == 'ssl' && $protocol == 'imap') $port = '993';
			elseif($secure == 'ssl' && $protocol == 'pop3') $port = '995';
			elseif($protocol == 'imap') $port = '143';
			elseif($protocol == 'pop3') $port = '110';
		}


		if(!empty($port)) $serverName .= ':'.$port;
		if(!empty($secure)) $serverName .= '/'.$secure;
		if($this->config->get('bounce_certif',false)) $serverName .= '/novalidate-cert';
		if(!empty($protocol)) $serverName .='/service='.$protocol;
		$serverName .= '}';
		$this->mailbox = imap_open($serverName,trim($this->config->get('bounce_username')),trim($this->config->get('bounce_password')));
		$warnings = ob_get_clean();

		if($this->report){
			if(!$this->mailbox){
				acymailing_display('Error connecting to '.$serverName,'error');
			}
			if(!empty($warnings)){
				acymailing_display($warnings,'warning');
			}
		}


		return $this->mailbox ? true : false;
	}

	public function getNBMessages(){
		if($this->usepear){
			$this->nbMessages = $this->mailbox->numMsg();
		}else{
		$this->nbMessages = imap_num_msg($this->mailbox);
		}

		return $this->nbMessages;
	}

	public function getMessage($msgNB){
		if($this->usepear){
			$message = new stdClass();
			$message->headerString = $this->mailbox->getRawHeaders($msgNB);
			if(empty($message->headerString)) return false;
		}else{
			$message = imap_headerinfo($this->mailbox,$msgNB);
		}

		return $message;
	}

	public function deleteMessage($msgNB){
		if($this->usepear){
			$this->mailbox->deleteMsg($msgNB);
		}else{
			imap_delete($this->mailbox,$msgNB);
			imap_expunge($this->mailbox);
		}
	}

	public function close(){
		if($this->usepear){
			$this->mailbox->disconnect();
		}else{
			imap_close($this->mailbox);
		}

	}

	private function decodeMessage(){
		if($this->usepear){
			return $this->_decodeMessagepear();
		}else{
			return  $this->_decodeMessageimap();
		}
	}

	private function _decodeMessagepear(){
		$this->_message->headerinfo = $this->mailbox->getParsedHeaders($this->_message->messageNB);
		if(empty($this->_message->headerinfo['subject'])) return false;
		$this->_message->text = '';
		$this->_message->html = $this->mailbox->getBody($this->_message->messageNB);
		$this->_message->subject = $this->_decodeHeader($this->_message->headerinfo['subject']);
		if(empty($this->_message->header)) $this->_message->header = new stdClass();
		$this->_message->header->sender_email = @$this->_message->headerinfo['return-path'];
		if(is_array($this->_message->header->sender_email)) $this->_message->header->sender_email = reset($this->_message->header->sender_email);
		if(preg_match($this->detectEmail,$this->_message->header->sender_email,$results)){
			$this->_message->header->sender_email = $results[0];
		}
		$this->_message->header->sender_name = strip_tags(@$this->_message->headerinfo['from']);
		$this->_message->header->reply_to_email = $this->_message->header->sender_email;
		$this->_message->header->reply_to_name = $this->_message->header->sender_name;
		$this->_message->header->from_email = $this->_message->header->sender_email;
		$this->_message->header->from_name = $this->_message->header->sender_name;

		return true;
	}

	private function _decodeMessageimap(){

		$this->_message->structure = imap_fetchstructure($this->mailbox,$this->_message->messageNB);

		if(empty($this->_message->structure)) return false;
		$this->_message->headerinfo = imap_fetchheader($this->mailbox,$this->_message->messageNB);

		$this->_message->html = '';
		$this->_message->text = '';

		if($this->_message->structure->type == 1){
			$this->_message->contentType = 2;
			$allParts = $this->_explodeBody($this->_message->structure);

			$this->_message->text = '';
			foreach($allParts as $num => $onePart){
				if ($onePart->subtype=='HTML'){
					$this->_message->html = $this->_decodeContent(imap_fetchbody($this->mailbox,$this->_message->messageNB,$num),$onePart);
				}else{
					$this->_message->text .= $this->_decodeContent(imap_fetchbody($this->mailbox,$this->_message->messageNB,$num),$onePart)."\n\n- - -\n\n";
				}
			}
		}else{
			if($this->_message->structure->subtype == 'HTML'){
				$this->_message->contentType = 1;
				$this->_message->html = $this->_decodeContent(imap_body($this->mailbox,$this->_message->messageNB),$this->_message->structure);
			}else{
				$this->_message->contentType = 0;
				$this->_message->text = $this->_decodeContent(imap_body($this->mailbox,$this->_message->messageNB),$this->_message->structure);
			}
		}

		$this->_message->subject = $this->_decodeHeader($this->_message->subject);

		$this->_decodeAddressimap('sender');
		$this->_decodeAddressimap('from');
		$this->_decodeAddressimap('reply_to');
		$this->_decodeAddressimap('to');
		return true;
	}

	public function handleMessages(){

		$maxMessages = min($this->nbMessages,$this->config->get('bounce_max',0));
		if(empty($maxMessages)) $maxMessages = min($this->nbMessages,500);

		if($this->report){
			if( function_exists('apache_get_modules') ) {
				$modules = apache_get_modules();
				$this->mod_security2 = in_array('mod_security2', $modules);
			}


			@ini_set('output_buffering', 'off');
			@ini_set('zlib.output_compression', 0);


			if(!headers_sent()){
				while(ob_get_level() > 0 && $this->obend++ < 3) { @ob_end_flush(); }
			}

			$disp = "<div style='position:fixed; top:3px;left:3px;background-color : white;border : 1px solid grey; padding : 3px;font-size:14px'>";
			$disp.= JText::_('BOUNCE_PROCESS');
			$disp.= ':  <span id="counter">0</span> / '. $maxMessages;
			$disp.= '</div>';
			$disp.= '<script type="text/javascript" language="javascript">';
			$disp.= 'var mycounter = document.getElementById("counter");';
			$disp.= 'function setCounter(val){ mycounter.innerHTML=val;}';
			$disp.= '</script>';
			echo $disp;
			if(function_exists('ob_flush')) @ob_flush();
			if(!$this->mod_security2) @flush();
		}

		$rules = $this->rulesClass->getRules(false);

		$msgNB = $maxMessages;
		$listClass = acymailing_get('class.list');
		$this->allLists = $listClass->getLists('listid');

		$replyemail = $this->config->get('reply_email');
		$fromemail=$this->config->get('from_email');
		$bouncemail = $this->config->get('bounce_email');
		$removeEmails = '#('.str_replace(array('%'),array('@'),$this->config->get('bounce_username'));
		if(!empty($bouncemail)) $removeEmails .= '|'.$bouncemail;
		if(!empty($fromemail)) $removeEmails .= '|'.$fromemail;
		if(!empty($replyemail)) $removeEmails .= '|'.$replyemail;
		$removeEmails .= ')#i';

		while(($msgNB>0) && ($this->_message = $this->getMessage($msgNB))){
			if($this->report){
				echo '<script type="text/javascript" language="javascript">setCounter('. ($maxMessages-$msgNB+1) .')</script>';
				if(function_exists('ob_flush')) @ob_flush();
				if(!$this->mod_security2) @flush();
			}
			$this->_message->messageNB = $msgNB;
			$msgNB--;

			if(!$this->decodeMessage()){
				$this->_display('Error retrieving message',false,$maxMessages-$this->_message->messageNB+1);
				continue;
			}

			if(empty($this->_message->subject)) $this->_message->subject = 'empty subject';

			$this->_message->analyseText = $this->_message->html.' '.$this->_message->text;
			if(!empty($this->_message->header->from_email)) $this->_message->analyseText .= ' '.$this->_message->header->from_email;
			$this->_display('<b>'.JText::_('JOOMEXT_SUBJECT').' : '.strip_tags($this->_message->subject).'</b>',false,$maxMessages-$this->_message->messageNB+1);

			preg_match('#AC([0-9]+)Y([0-9]+)BA#i',$this->_message->analyseText,$resultsVars);
			if(!empty($resultsVars[1])) $this->_message->subid = $resultsVars[1];
			if(!empty($resultsVars[2])) $this->_message->mailid = $resultsVars[2];

			if(empty($this->_message->subid)){
				preg_match('#X-Subid *:? *([0-9]*)#i',$this->_message->analyseText,$resultsSubid);
				if(!empty($resultsSubid[1])) $this->_message->subid = $resultsSubid[1];
			}

			if(empty($this->_message->mailid)){
				preg_match('#X-Mailid *:? *([0-9]*)#i',$this->_message->analyseText,$resultsMailid);
				if(!empty($resultsMailid[1])) $this->_message->mailid = $resultsMailid[1];
			}

			if(empty($this->_message->subid)){
				preg_match_all($this->detectEmail,$this->_message->analyseText,$results);

				if(empty($results[0])){
					preg_match_all($this->detectEmail2,$this->_message->analyseText,$results2);
					for ($i = 0; $i < count($results2[0]); $i++)
						$results[0][] = $results2[3][$i].'@'.$results2[1][$i];
				}

				if(!empty($results[0])){
					$alreadyChecked = array();
					foreach($results[0] as $oneEmail){
						if(!preg_match($removeEmails,$oneEmail)){
							$this->_message->subemail = strtolower($oneEmail);
							if(!empty($alreadyChecked[$this->_message->subemail])) continue;
							$this->_message->subid = $this->subClass->subid($this->_message->subemail);
							$alreadyChecked[$this->_message->subemail] = true;
							if(!empty($this->_message->subid)) break;
						}
					}
				}
			}

			if(empty($this->_message->mailid) && !empty($this->_message->subid)){
				$this->db->setQuery('SELECT `mailid` FROM '.acymailing_table('userstats').' WHERE `subid` = '.(int) $this->_message->subid.' ORDER BY `senddate` DESC LIMIT 1');
				$this->_message->mailid = $this->db->loadResult();
			}

			foreach($rules as $oneRule){
				if($this->_handleRule($oneRule)) break;
			}

			if($msgNB%50 == 0) $this->_subActions();

			if(!empty($this->stoptime) AND time()>$this->stoptime) break;
		}

		$this->_subActions();


	}


	private function _subActions(){

		if(!empty($this->deletedUsers)){
			$this->subClass->delete($this->deletedUsers);
			$this->deletedUsers = array();
		}
		if(!empty($this->blockedUsers)){
			$allUsersId = implode(',',$this->blockedUsers);
			$this->db->setQuery('UPDATE `#__acymailing_subscriber` SET `enabled` = 0 WHERE `subid` IN ('.$allUsersId.')');
			$this->db->query();
			$this->db->setQuery('DELETE FROM `#__acymailing_queue` WHERE `subid` IN ('.$allUsersId.')');
			$this->db->query();
			$this->blockedUsers = array();
		}

		if(!empty($this->bounceMessages)){
			foreach($this->bounceMessages as $mailid => $bouncedata){

				$updateBounceDetails = '';
				if(!empty($bouncedata['bouncedetails'])){
					$this->db->setQuery('SELECT `bouncedetails` FROM #__acymailing_stats WHERE mailid = '.(int) $mailid.' LIMIT 1');
					$bouncedetails  = $this->db->loadResult();
					if(!empty($bouncedetails)){
						$bouncedetails = unserialize($bouncedetails);
					}else{
						$bouncedetails = array();
					}

					foreach($bouncedata['bouncedetails'] as $ruleName => $nbTimes){
						if(empty($bouncedetails)) $bouncedetails[$ruleName] = 0;
						$bouncedetails[$ruleName] += $nbTimes;
					}

					$updateBounceDetails = ' , `bouncedetails` = '.$this->db->Quote(serialize($bouncedetails));
				}

				if(!empty($bouncedata['subid'])){
					$this->db->setQuery('UPDATE '.acymailing_table('userstats').' SET `bounce` = `bounce` + 1 WHERE `subid` IN ('.implode(',',$bouncedata['subid']).') AND `mailid` = '.(int) $mailid);
					$this->db->query();

					$this->db->setQuery('SELECT COUNT(*) FROM '.acymailing_table('userstats').' WHERE `bounce` = 1 AND `subid` IN ('.implode(',',$bouncedata['subid']).') AND `mailid` = '.(int) $mailid);
					$realUniqueBounces = $this->db->loadResult();
					$bouncedata['nbbounces'] = $bouncedata['nbbounces'] - count($bouncedata['subid']) + $realUniqueBounces;
				}

				$this->db->setQuery('UPDATE '.acymailing_table('stats').' SET `bounceunique` = `bounceunique` + '.$bouncedata['nbbounces'].$updateBounceDetails.' WHERE `mailid` = '.(int) $mailid.' LIMIT 1');
				$this->db->query();
			}
			$this->bounceMessages = array();
		}

	}

	private function _handleRule(&$oneRule){
		$regex = $oneRule->regex;
		if(empty($regex)) return false;


		$analyseText = '';
		if(isset($oneRule->executed_on['senderinfo'])) $analyseText .= ' '.$this->_message->header->sender_name.$this->_message->header->sender_email;
		if(isset($oneRule->executed_on['subject'])) $analyseText .= ' '.$this->_message->subject;
		if(isset($oneRule->executed_on['body'])){
			if(!empty($this->_message->html)) $analyseText .= ' '.$this->_message->html;
			if(!empty($this->_message->text)) $analyseText .= ' '.$this->_message->text;
		}

		$analyseText = str_replace(array("\n","\r","\t"),' ',$analyseText);

		if(!preg_match('#'.$regex.'#ims',$analyseText)) return false;

		$message = JText::_('BOUNCE_RULES').' ['.JText::_('ACY_ID').' '.$oneRule->ruleid.'] '.$oneRule->name.' : ';

		$message .= $this->_actionuser($oneRule);
		$message .= $this->_actionmessage($oneRule);

		$this->_display($message,true);

		return true;
	}

	private function _actionuser(&$oneRule){
		$message = '';

		if(empty($this->_message->subid)){
			$message .= 'user not identified';
			if(!empty($this->_message->subemail)) $message .= ' ( '.$this->_message->subemail.' ) ';
			return $message;
		}

		if(isset($oneRule->action_user['removesub']) || isset($oneRule->action_user['unsub']) || isset($oneRule->action_user['sub'])){
			$status = $this->subClass->getSubscriptionStatus($this->_message->subid);
			if(empty($this->_message->subemail)){
				$currentUser = $this->subClass->get($this->_message->subid);
				if(!empty($currentUser->email)) $this->_message->subemail = $currentUser->email;
			}
		}

		if(empty($this->_message->subemail)) $this->_message->subemail = $this->_message->subid;


		if(isset($oneRule->action_user['stats']) && !empty($this->_message->mailid)){

			if(empty($this->bounceMessages[$this->_message->mailid])){
				$this->bounceMessages[$this->_message->mailid] = array();
				$this->bounceMessages[$this->_message->mailid]['nbbounces'] = 0;
				$this->bounceMessages[$this->_message->mailid]['subid'] = array();
				$this->bounceMessages[$this->_message->mailid]['bouncedetails'] = array();
			}

			$this->bounceMessages[$this->_message->mailid]['nbbounces']++ ;

			$ruleName = $oneRule->name.' [ '.$oneRule->ruleid.' ] ';
			$this->bounceMessages[$this->_message->mailid]['bouncedetails'][$ruleName] = intval(@$this->bounceMessages[$this->_message->mailid]['bouncedetails'][$ruleName])+1;

			if(!empty($this->_message->subid) AND !isset($oneRule->action_user['delete'])){
				$this->bounceMessages[$this->_message->mailid]['subid'][] = intval($this->_message->subid);
			}
		}

		if(!empty($oneRule->action_user['min']) && $oneRule->action_user['min']>1){
			$this->db->setQuery('SELECT COUNT(mailid) FROM #__acymailing_userstats WHERE bounce > 0 AND subid = '.$this->_message->subid.' AND mailid != '.intval(@$this->_message->mailid));
			$nb = intval($this->db->loadResult()) + 1;

			if($nb < $oneRule->action_user['min']){
				$message .= ' | '.JText::sprintf('BOUNCE_RECEIVED',$nb,$this->_message->subemail).' | '.JText::sprintf('BOUNCE_MIN_EXEC',$oneRule->action_user['min']);
				return $message;
			}
		}

		if(isset($oneRule->action_user['delete'])){
			$message .= ' | user '.$this->_message->subemail.' deleted';
			$this->deletedUsers[] = intval($this->_message->subid);
			return $message;
		}

		$listId = 0;
		if(isset($oneRule->action_user['sub']) && !empty($oneRule->action_user['subscribeto'])){
			$listId = $oneRule->action_user['subscribeto'];

			$listName = empty($this->allLists[$listId]->name) ? $listId : $this->allLists[$listId]->name;
			$message .= ' | user '.$this->_message->subemail.' subscribed to '.$listName;
			if(empty($status[$listId])){
				$this->listsubClass->addSubscription($this->_message->subid,array('1' => array($listId)));
			}elseif($status[$listId]->status != 1){
				$this->listsubClass->updateSubscription($this->_message->subid,array('1' => array($listId)));
			}
		}

		if(isset($oneRule->action_user['removesub'])){
			$unsubLists = array_diff(array_keys($status),array($listId));
			if(!empty($unsubLists)){
				$listnames = array();
				foreach($unsubLists as $oneListId){
					if(!empty($this->allLists[$oneListId]->name)){ $listnames[] = $this->allLists[$oneListId]->name;}
					else{ $listnames[] = $oneListId;}
				}
				$message .= ' | user '.$this->_message->subemail.' removed from lists '.implode(', ',$listnames);
				$this->listsubClass->removeSubscription($this->_message->subid,$unsubLists);
			}else{
				$message .= ' | user '.$this->_message->subemail.' not subscribed';
			}
		}


		if(isset($oneRule->action_user['unsub'])){
			$unsubLists = array_diff(array_keys($status),array($listId));
			if(!empty($unsubLists)){
				$listnames = array();
				foreach($unsubLists as $oneListId){
					if(!empty($this->allLists[$oneListId]->name)){ $listnames[] = $this->allLists[$oneListId]->name;}
					else{ $listnames[] = $oneListId;}
				}
				$message .= ' | user '.$this->_message->subemail.' unsubscribed from lists '.implode(',',$listnames);
				$this->listsubClass->updateSubscription($this->_message->subid,array('-1' => $unsubLists));
			}else{
				$message .= ' | user '.$this->_message->subemail.' not subscribed';
			}
		}

		if(isset($oneRule->action_user['block'])){
			$message .= ' | user '.$this->_message->subemail.' ( '.JText::_('ACY_ID').' '.intval($this->_message->subid).' ) blocked';
			$this->blockedUsers[] = intval($this->_message->subid);
		}

		return $message;
	}

	private function _actionmessage(&$oneRule){
		$message = '';

		if(!empty($oneRule->action_message['forwardto'])){
			if(strtolower($oneRule->action_message['forwardto']) == strtolower($this->config->get('bounce_username')) || strtolower($oneRule->action_message['forwardto']) == strtolower($this->config->get('bounce_email'))){
				$oneRule->action_message['forwardto'] = '';
				unset($oneRule->action_message['delete']);
				$message .= 'The forward e-mail address is the same as the bounce one... Acy will not forward your message';
			}
		}


		if(isset($oneRule->action_message['save']) && !empty($this->_message->subid) && empty($oneRule->action_user['delete'])){
			$data = array();
			$data[] = 'SUBJECT::'.@htmlentities($this->_message->subject, ENT_COMPAT, 'UTF-8');
			$data[] = 'ACY_RULE::'.$oneRule->ruleid.' '.$oneRule->name;
			$data[] = 'REPLYTO_ADDRESS::'.$this->_message->header->reply_to_name. ' ( '.$this->_message->header->reply_to_email.' )';
			$data[] = 'FROM_ADDRESS::'.$this->_message->header->from_name. ' ( '.$this->_message->header->from_email.' )';
			if(!empty($this->_message->html)) $data[] = 'HTML_VERSION::'.@htmlentities($this->_message->html, ENT_COMPAT, 'UTF-8');
			if(!empty($this->_message->text)) $data[] = 'TEXT_VERSION::'.nl2br(@htmlentities($this->_message->text, ENT_COMPAT, 'UTF-8'));
			$data[] = print_r($this->_message->headerinfo,true);
			$this->historyClass->insert($this->_message->subid,'bounce',$data,@$this->_message->mailid);
			$message .= ' | message saved (user '.$this->_message->subid.')';
		}

		$donotdelete = false;

		if(!empty($oneRule->action_message['forwardto'])){
			$this->mailer->clearAll();
			$this->mailer->Subject = 'BOUNCE FORWARD : '.$this->_message->subject;

			if(substr_count($oneRule->action_message['forwardto'],'@')>1){
				$forwardAddresses = explode(';', str_replace(array(';',','), ';', $oneRule->action_message['forwardto']));
			}else{
				$forwardAddresses = array($oneRule->action_message['forwardto']);
			}

			foreach($forwardAddresses as $oneForwardAddress){
				$this->mailer->AddAddress($this->mailer->cleanText($oneForwardAddress));
			}

			$info = JText::_('BOUNCE_RULES').' ['.JText::_('ACY_ID').' '.$oneRule->ruleid.'] '.$oneRule->name;
			if(!empty($this->_message->html)){
				$this->mailer->IsHTML(true);
				$this->mailer->Body = $info.'<br />'.$this->_message->html;
				if(!empty($this->_message->text)) $this->mailer->Body .= '<br /><br />-------<br />'.nl2br($this->_message->text);
			}else{
				$this->mailer->IsHTML(false);
				$this->mailer->Body = $info."\n".$this->_message->text;
			}

			$this->mailer->Body .= print_r($this->_message->headerinfo,true);
			$replyAddress = trim(@$this->_message->header->reply_to_email,'<> ');
			if(!empty($replyAddress)) $this->mailer->AddReplyTo(trim($this->_message->header->reply_to_email,'<> '),$this->_message->header->reply_to_name);

			if($this->mailer->send()){
				$message .= ' | forwarded to '.$oneRule->action_message['forwardto'];
			}else{
				 $message .= ' | error forwarding to '.$oneRule->action_message['forwardto'].' '.$this->mailer->reportMessage;
				 $donotdelete = true;
			}
		}

		if(isset($oneRule->action_message['delete']) && !$donotdelete){
			$message .= ' | message deleted';
			$this->deleteMessage($this->_message->messageNB);
		}

		return $message;

	}

	private function _decodeAddressimap($type){
		$address = $type.'address';
		$name = $type.'_name';
		$email = $type.'_email';
		if(empty($this->_message->$type)) return false;

		$var = $this->_message->$type;

		if(empty($this->_message->header)) $this->_message->header = new stdClass();

		if(!empty($this->_message->$address)){
			$this->_message->header->$name = $this->_message->$address;
		}else{
			$this->_message->header->$name = $var[0]->personal;
		}

		$this->_message->header->$email = $var[0]->mailbox.'@'.@$var[0]->host;
		return true;
	}


	private function _display($message,$status = '',$num = ''){
		$this->messages[] = $message;

		if(!$this->report) return;

		$color = $status ? 'green' : 'blue';
		if(!empty($num)) echo '<br />'.$num.' : ';
		else echo '<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

		echo '<font color="'.$color.'">'.$message.'</font>';
		if(function_exists('ob_flush')) @ob_flush();
		if(!$this->mod_security2) @flush();
	}

	private function _decodeHeader($input){
		$input = preg_replace('/(=\?[^?]+\?(q|b)\?[^?]*\?=)(\s)+=\?/i', '\1=?', $input);
		$this->charset = false;

		while (preg_match('/(=\?([^?]+)\?(q|b)\?([^?]*)\?=)/i', $input, $matches)) {

				$encoded  = $matches[1];
				$charset  = $matches[2];
				$encoding = $matches[3];
				$text     = $matches[4];

				switch (strtolower($encoding)) {
						case 'b':
								$text = base64_decode($text);
								break;

						case 'q':
								$text = str_replace('_', ' ', $text);
								preg_match_all('/=([a-f0-9]{2})/i', $text, $matches);
								foreach($matches[1] as $value)
										$text = str_replace('='.$value, chr(hexdec($value)), $text);
								break;
				}
				$this->charset = $charset;
				$input = str_replace($encoded, $text, $input);
		}

		if($this->charset && in_array($this->charset,$this->allCharsets)){
			$input = $this->encodingHelper->change($input,$this->charset,'UTF-8');
		}

		return $input;
	}

	private function _explodeBody($struct, $path="0",$inline=0){
		$allParts = array();

		if(empty($struct->parts)) return $allParts;

		$c=0; //counts real content
		foreach ($struct->parts as $part){
			if ($part->type==1){
				if ($part->subtype=="MIXED"){ //Mixed:
					$path = $this->_incPath($path,1); //refreshing current path
					$newpath = $path.".0"; //create a new path-id (ex.:2.0)
					$allParts = array_merge($this->_explodeBody($part,$newpath),$allParts); //fetch new parts
				}
				else{ //Alternativ / rfc / signed
					$newpath = $this->_incPath($path, 1);
					$path = $this->_incPath($path,1);
					$allParts = array_merge($this->_explodeBody($part,$newpath,1),$allParts);
				}
			}
			else {
				$c++;
				if ($c==1 && $inline){
					$path = $path.".0";
				}
				$path = $this->_incPath($path, 1);
				$allParts[$path] = $part;
			}
		}

		return $allParts;

	}

	private function _incPath($path, $inc){
		$newpath="";
		$path_elements = explode(".",$path);
		$limit = count($path_elements);
		for($i=0;$i < $limit;$i++){
			if($i == $limit-1){ //last element
					$newpath .= $path_elements[$i]+$inc; // new Part-Number
			}
			else{
					$newpath .= $path_elements[$i]."."; //rebuild "1.2.2"-Chronology
			}
		}
		return $newpath;
	}

	private function _decodeContent($content,$structure){
		$encoding = $structure->encoding;

		if($encoding == 2) $content = imap_binary($content);
		elseif($encoding == 3) $content = imap_base64($content);
		elseif($encoding == 4) $content = imap_qprint($content);

		return substr($content,0,100000);
	}

	private function _getMailParam($params,$name){
			$searchIn = array();

		if ($params->ifparameters)
			$searchIn=array_merge($searchIn,$params->parameters);
		if ($params->ifdparameters)
			$searchIn=array_merge($searchIn,$params->dparameters);

		if(empty($searchIn)) return false;

		foreach($searchIn as $num => $values)
		{
			if (strtolower($values->attribute) == $name)
			{
				return $values->value;
			}
		}

	}

	public function getErrors(){
		$return = array();
		if($this->usepear){
		}else{
			$alerts = imap_alerts();
			$errors = imap_errors();
			if(!empty($alerts)) $return = array_merge($return,$alerts);
			if(!empty($errors)) $return = array_merge($return,$errors);
		}

		return $return;
	}
}
