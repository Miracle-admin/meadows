<?php
/**
 * @version 2.2
 * @package Joomla 3.x
 * @subpackage RS-Monials
 * @copyright (C) 2013-2022 RS Web Solutions (http://www.rswebsols.com)
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

function fetchParam($name) {
	$db = JFactory::getDBO();
	$db->setQuery("select `param_value` from `#__".RSWEBSOLS_TABLE_PREFIX."_param` where `param_name`='".$name."'");
	$data = $db->loadObject();
	return $data->param_value;
}

function fetchParamStyle($name) {
	$db = JFactory::getDBO();
	$db->setQuery("select `param_value` from `#__".RSWEBSOLS_TABLE_PREFIX."_param_style` where `param_name`='".$name."'");
	$data = $db->loadObject();
	return $data->param_value;
}

function sendMail($smFrom, $smName, $smSubject, $smBody) {
	global $mainframe;

	$subject = $smSubject;
	$message_body = nl2br($smBody);
	$to = fetchParam('admin_email');
	$from = $smFrom;;
	$fromName = $smName;
	
	$mailer = JFactory::getMailer();
	
	// Build e-mail message format
	$mailer->setSender(array(''.$from.'', ''.$fromName.''));
	$mailer->setSubject(stripslashes($subject));
	$mailer->setBody($message_body);
	$mailer->IsHTML(1);
	$mailer->addRecipient($to);

	// Send the Mail
	$rs	= $mailer->Send();

	// Check for an error
	if ( JError::isError($rs) ) {
		return false;
	} else {
		return true;
	}
}

function safeHTML($var) {
	if (is_array($var)) {
		$out = array();
		foreach ($var as $key => $v) {
			$out[$key] = htmlspecialchars_decode($v);
			$out[$key] = htmlspecialchars(stripslashes(trim($out[$key])), ENT_QUOTES);
		}
	} else {
		$out = htmlspecialchars_decode($var);
		$out = htmlspecialchars(stripslashes(trim($out)), ENT_QUOTES);
	}
	return $out;
}

function stripHTML($var, $decode = false) {
	$var = nl2br(stripslashes($var));
	if($decode == true) {
		$var = htmlspecialchars_decode($var);	
	}
	return $var;
}

function makeLink($var) {
	$var = stripslashes($var);
	if(!(strpos($var, "http://") === 0) && !(strpos($var, "https://") === 0)) {
		$var = "http://".$var;
	}
	return $var;
}

function getClientIP() {
     $ipaddress = '';
	 if (getenv('HTTP_CLIENT_IP')) { $ipaddress = getenv('HTTP_CLIENT_IP'); }
	 else if(getenv('HTTP_X_FORWARDED_FOR')) { $ipaddress = getenv('HTTP_X_FORWARDED_FOR'); }
	 else if(getenv('HTTP_X_FORWARDED')) { $ipaddress = getenv('HTTP_X_FORWARDED'); }
	 else if(getenv('HTTP_FORWARDED_FOR')) { $ipaddress = getenv('HTTP_FORWARDED_FOR'); }
	 else if(getenv('HTTP_FORWARDED')) { $ipaddress = getenv('HTTP_FORWARDED'); }
	 else if(getenv('REMOTE_ADDR')) { $ipaddress = getenv('REMOTE_ADDR'); }
	 else if ($_SERVER['HTTP_CLIENT_IP']) { $ipaddress = $_SERVER['HTTP_CLIENT_IP']; }
     else if($_SERVER['HTTP_X_FORWARDED_FOR']) { $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR']; }
     else if($_SERVER['HTTP_X_FORWARDED']) { $ipaddress = $_SERVER['HTTP_X_FORWARDED']; }
     else if($_SERVER['HTTP_FORWARDED_FOR']) { $ipaddress = $_SERVER['HTTP_FORWARDED_FOR']; }
     else if($_SERVER['HTTP_FORWARDED']) { $ipaddress = $_SERVER['HTTP_FORWARDED']; }
     else if($_SERVER['REMOTE_ADDR']) { $ipaddress = $_SERVER['REMOTE_ADDR']; }
     else { $ipaddress = 'UNKNOWN'; }
     return $ipaddress; 
}

function isIPBlock() {
	$cip = getClientIP();
	if($cip == 'UNKNOWN') {
		return false;	
	}
	$blocked_ips = trim(fetchParam('blocked_ips'));
	if($blocked_ips == '' || $blocked_ips == 'false') {
		return false;
	} else {
		$blocked_ips_arr = explode(',', $blocked_ips);
		$cip_arr = explode('.', $cip);
		$cip1 = $cip_arr[0];
		$cip2 = $cip_arr[1];
		$cip3 = $cip_arr[2];
		$cip4 = $cip_arr[3];
		foreach($blocked_ips_arr as $key=>$value) {
			$bip = trim($value);
			$bip_arr = explode('.', $bip);
			$bip1 = strtolower(trim($bip_arr[0]));
			$bip2 = strtolower(trim($bip_arr[1]));
			$bip3 = strtolower(trim($bip_arr[2]));
			$bip4 = strtolower(trim($bip_arr[3]));
			if($bip1 == 'x') {
				return true;
			} elseif($bip2 == 'x') {
				if($bip1 == $cip1) {
					return true;	
				}
			} elseif($bip3 == 'x') {
				if($bip1.'.'.$bip2 == $cip1.'.'.$cip2) {
					return true;	
				}
			} elseif($bip4 == 'x') {
				if($bip1.'.'.$bip2.'.'.$bip3 == $cip1.'.'.$cip2.'.'.$cip3) {
					return true;	
				}
			} else if($bip == $cip) {
				return true;
			}
		}
		return false;
	}
	return false;
}

function getMonth($month) {
	$dmonth = '%B';
	switch($month) {
		case '01':
		case '02':
		case '03':
		case '04':
		case '05':
		case '06':
		case '07':
		case '08':
		case '09':
		case '10':
		case '11':
		case '12':
			$dmonth = JText::_('RSM_MONTH_'.$month.'');
			break;
		default:
			$dmonth = '%B';
			break;
	}
	return $dmonth;
}
?>