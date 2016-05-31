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
	if(isset($data->param_value)) {
		return $data->param_value;
	} else {
		return false;	
	}
}

function fetchParamName($name) {
	$db = JFactory::getDBO();
	$db->setQuery("select `param_name` from `#__".RSWEBSOLS_TABLE_PREFIX."_param` where `param_name`='".$name."'");
	$data = $db->loadObject();
	if(isset($data->param_name)) {
		return $data->param_name;
	} else {
		return false;	
	}
}

function fetchParamStyle($name) {
	$db = JFactory::getDBO();
	$db->setQuery("select `param_value` from `#__".RSWEBSOLS_TABLE_PREFIX."_param_style` where `param_name`='".$name."'");
	$data = $db->loadObject();
	if(isset($data->param_value)) {
		return $data->param_value;
	} else {
		return false;	
	}
}

function fetchParamStyleName($name) {
	$db = JFactory::getDBO();
	$db->setQuery("select `param_name` from `#__".RSWEBSOLS_TABLE_PREFIX."_param_style` where `param_name`='".$name."'");
	$data = $db->loadObject();
	if(isset($data->param_name)) {
		return $data->param_name;
	} else {
		return false;	
	}
}

function sendMail($smTo, $smSubject, $smBody) {
	global $mainframe;

	$subject = $smSubject;
	$message_body = nl2br($smBody);
	$to = $smTo;
	$from = fetchParam('admin_email');
	$fromName = fetchParam('admin_name');
	
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

function safePost() {
	foreach($_POST as $key=>$value) {
		if(!is_array($value)) {
			$_POST[''.$key.''] = addslashes($value);
		}
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

function checkLangDir($dname) {
	if(strpos($dname, '-') != 2) {
		return false;
	}
	if(strlen($dname)!=5) {
		return false;
	}
	if(!ctype_lower(substr($dname, 0, 2))) {
		return false;
	}
	if(!ctype_upper(substr($dname, 3, 2))) {
		return false;
	}
	return true;
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

function returnMsg($id = 0) {
	switch($id) {
		case '1':
			$msg = 'Setting Successfully Saved.';
			return $msg;
			break;
		case '2':
			$msg = 'CSS Style Successfully Saved.';
			return $msg;
			break;
		case '3':
			$msg = 'CSS Style Successfully Restored.';
			return $msg;
			break;
		case '4':
			$msg = 'Content Successfully Saved.';
			return $msg;
			break;
		case '5':
			$msg = 'Content Successfully Restored.';
			return $msg;
			break;
		case '6':
			$msg = 'Testimonial Successfully Published.';
			return $msg;
			break;
		case '7':
			$msg = 'Testimonial Successfully Unpublished.';
			return $msg;
			break;
		case '8':
			$msg = 'Testimonial Successfully Deleted.';
			return $msg;
			break;
		case '9':
			$msg = 'Testimonial(s) Successfully Deleted.';
			return $msg;
			break;
		case '10':
			$msg = 'Testimonial Successfully Saved.';
			return $msg;
			break;
		case '1010':
			$msg = 'Testimonial Successfully Saved. IMAGE UPLOADING ERROR: Failed to Upload Image.';
			return $msg;
			break;
		case '1011':
			$msg = 'Testimonial Successfully Saved. IMAGE UPLOADING ERROR: File is not supported. Please upload jpg, gif or png file.';
			return $msg;
			break;
		case '1012':
			$msg = 'Testimonial Successfully Saved. IMAGE UPLOADING ERROR: Image is larger than '.fetchParam('image_max_size').' kb';
			return $msg;
			break;
		case '1013':
			$msg = 'Testimonial Successfully Saved. IMAGE UPLOADING ERROR: Image width is larger than '.fetchParam('image_max_width').' px';
			return $msg;
			break;
		case '1014':
			$msg = 'Testimonial Successfully Saved. IMAGE UPLOADING ERROR: Image height is larger than '.fetchParam('image_max_height').' px';
			return $msg;
			break;
		case '11':
			$msg = 'Testimonial Successfully Moved to Spam.';
			return $msg;
			break;
		case '12':
			$msg = 'Testimonial(s) Successfully Moved to Spam.';
			return $msg;
			break;
		case '13':
			$msg = 'IP Address is now blocked.';
			return $msg;
			break;
		case '14':
			$msg = 'Testimonial(s) Successfully Marked as Not Spam.';
			return $msg;
			break;
		case '15':
			$msg = 'Testimonial Successfully Marked as Not Spam.';
			return $msg;
			break;
		default:
			$msg='';
			return $msg;
			break;
	}
}

function updateOldTable() {
	$db = JFactory::getDBO();
	$db->setQuery("SHOW COLUMNS FROM `#__".RSWEBSOLS_TABLE_PREFIX."` LIKE 'ip'");
	$db->query();
	$exists = $db->getNumRows();
	if($exists > 0) { } else {
		$db->setQuery("ALTER TABLE `#__".RSWEBSOLS_TABLE_PREFIX."` ADD `ip` VARCHAR( 100 ) NOT NULL AFTER `comment`");
		$db->query();
	}
}

function updateSettingsTable() {
	$db = JFactory::getDBO();
	$param_arr = array();
	$param_arr[] = array('admin_name', 'Name of Administrator', 'Site Administrator', 1);
	$param_arr[] = array('admin_email', 'Email Address of Administrator - This will use for sending and receiving Email', 'admin@yoursite.com', 2);
	$param_arr[] = array('show_desc', 'Set "false" to hide the description text appeared just agter the page title in front end. Set "true" to display', 'true', 3);
	$param_arr[] = array('login_to_submit_testimonial', 'Here you can specify who can post testimonials/comments. If you wish anyone can post testimonials then set the value of this field to "false". But if you wish only registered/logged in users can post comment then set the value to "true" here.', 'false', 4);
	$param_arr[] = array('show_single_name_field', 'Set "false" to display two name fields (i.e. "First Name" and "Last Name"). Set "true" to display single name field (i.e. "Your Name")', 'true', 5);
	$param_arr[] = array('show_about', 'Set "false" to hide "About You" field in front end. Set "true" to display', 'true', 6);
	$param_arr[] = array('show_location', 'Set "false" to hide "Your Location" field in front end. Set "true" to display', 'true', 7);
	$param_arr[] = array('show_website', 'Set "false" to hide "Your Website" field in front end. Set "true" to display', 'true', 8);
	$param_arr[] = array('show_image', 'Set "true" to enable image/picture uploading. Set "false" to disable.', 'true', 9);
	$param_arr[] = array('image_max_width', 'If you enabled "show_image", then please set the maximum allowed width of image (in pixel).', '500', 10);
	$param_arr[] = array('image_max_height', 'If you enabled "show_image", then please set the maximum allowed height of image (in pixel).', '500', 11);
	$param_arr[] = array('image_max_size', 'If you enabled "show_image", then please set the maximum allowed size of image to optimize server load(in kb).', '250', 12);
	$param_arr[] = array('show_captcha', 'Set "false" to hide "Captcha" field in front end. Set "true" to display', 'true', 13);
	$param_arr[] = array('use_recaptcha', 'Set "true" if you wish to use "ReCaptcha Library". To enable "ReCaptcha" you need to obtain ReCaptcha "Public API Key" and "Private API Key" from "www.google.com/recaptcha"\r\n\r\nSet "false" to use the component default captcha image.\r\n\r\nWe recommend you to enable ReCaptcha.', 'false', 14);
	$param_arr[] = array('recaptcha_public_key', 'If you are using "ReCaptcha" then please enter "ReCaptcha Public API Key" here.', 'xxx', 15);
	$param_arr[] = array('recaptcha_private_key', 'If you are using "ReCaptcha" then please enter "ReCaptcha Private API Key" here.', 'xxx', 16);
	$param_arr[] = array('recaptcha_theme', 'Four themes are currently available for ReCaptcha (Red, BlackGlass, White, Clean).\r\n\r\nEnter "1" to enable "Red Theme". Enter "2" to enable "BlackGlass Theme". Enter "3" to enable "White Theme". Enter "4" to enable "Clean Theme".', '4', 17);
	$param_arr[] = array('auto_approval', 'Set "false" if you wish to review all new testimonial first and then approve manually. Set "true" if you wish new testimonials will approve and start to dispaly instantly after submission', 'false', 18);
	$param_arr[] = array('show_pagination', 'Set "true" to show pagination in front end. Set "false" to show all testimonials in a single page', 'true', 19);
	$param_arr[] = array('pagination', 'Set how many testimonials you wish to display in a page. This will only work if you set (show_pagination = true)', '10', 20);
	$param_arr[] = array('pagination_alignment', 'Set the text alignment of the pagination => left, right or center', 'center', 21);
	$param_arr[] = array('admin_email_alert', 'Set "true" if you wish to receive one autogenerated email for each and every new testimonial posting. Set "false" to not receive autogenerated email', 'false', 22);
	$param_arr[] = array('blocked_ips', 'Block IP Addresses to post comment. You can add multiple IP addresses separated by comma.\r\n\r\nExample: 192.168.1.1, 202.101.50.x\r\n\r\nHow it works:\r\n1) 192.168.1.1 will block only this particular ip.\r\n2) 192.168.1.x will block all ip addresses starting with 192.168.1\r\n3) 192.168.x.x will block all ip addresses starting with 192.168\r\n4) 192.x.x.x will block all ip addresses starting with 192\r\n5) x.x.x.x will block all ip addresses.\r\n6) Set "false" to allow all ip addresses.', 'false', 23);
	$param_arr[] = array('show_website_as_link', 'Set "true" to display website as link (hyperlinked), set "false" to display website as plain text.', 'true', 8);	
	
	$tot_param = count($param_arr);
	for($i=0;$i<$tot_param;$i++) {
		if(fetchParamName(''.$param_arr[$i][0].'') == '') {
			$db->setQuery("INSERT INTO `#__".RSWEBSOLS_TABLE_PREFIX."_param` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', '".$param_arr[$i][0]."', '".$param_arr[$i][1]."', '".$param_arr[$i][2]."', ".$param_arr[$i][3].");");
			$db->query();
		}
	}	
	
	$param_style_arr = array();
	$param_style_arr[] = array('testimonial_block_border', 'Border Style of each Testimonial block:\r\n\r\nExample 1: 1px solid #cccccc\r\nExample 2: 2px dotted #0000ff\r\nExample 3: 1px dashed #006600', '1px solid #dedede', 1);
	$param_style_arr[] = array('testimonial_block_background_color', 'Background Color of Testimonial Block:\r\n\r\nExample: #CCCCCC', '#FFFFFF', 2);
	$param_style_arr[] = array('testimonial_block_rounded_corner', 'Set "true" to get rounded corner of each testimonial block. Set "false" to get square block.\r\n\r\nNote: Rounded corner will not work in IE (upto IE 8). IE not supports it. From IE 9 it will work. IE9 is till to launch.', 'false', 3);
	$param_style_arr[] = array('testimonial_block_rounded_corner_radius', 'If you enabled "Rounded Corner", then you can set the Radius of the rounded corner (in pixel). By default it is 10. ', '10', 4);
	$param_style_arr[] = array('testimonial_block_enable_gradient', 'If you wish to use Gradient set this to "true", otherwise "False".\r\n\r\nNote: Gradient will not work in IE (upto IE 8). IE not supports it. From IE 9 it will work. IE9 is till to launch.', 'false', 5);
	$param_style_arr[] = array('testimonial_block_gradient_start_color', 'If you enabled "Gradient" then please set the start color of the gradient here.\r\n\r\nExample: #F7F7F7', '#F7F7F7', 6);
	$param_style_arr[] = array('testimonial_block_gradient_end_color', 'If you enabled "Gradient" then please set the start color of the gradient here.\r\n\r\nExample: #FFFFFF', '#FFFFFF', 7);
	$param_style_arr[] = array('testimonial_block_quotation_image_style', 'Enter "0" to "disable quotation image".\r\nEnter "1" to use "square type quotation image".\r\nEnter "2" to use "round type quotation image".', '2', 8);
	$param_style_arr[] = array('testimonial_block_default_image', 'If you enabled the image upload option in your testimonial form, then here you can set the default image.\r\n\r\nEnter "0" to "not display any image if someone do not upload his/her image".\r\nEnter "1" to use "Gray User image if there is no image".\r\nEnter "2" to use "Black User (Male) image if there is no image".\r\nEnter "3" to use "Black User (Female) image if there is no image".', '1', 9);
	$param_style_arr[] = array('testimonial_block_image_position', 'If you enabled the image upload option in your testimonial form, then here you can set the default image position.\r\n\r\nEnter "1" to "Display image in left side of testimonial".\r\nEnter "2" to "Display image in right side of testimonial".\r\nEnter "3" to "Display image in alternate side of testimonial (one in left, next one in right and so on...)".', '1', 10);
	$param_style_arr[] = array('testimonial_block_image_display_width', 'Display width of the testimonial image (in pixel).', '125', 11);
	$param_style_arr[] = array('testimonial_block_show_date', 'Enter "true" to display the date of submission of the testimonial.\r\nEnter "false" to not display the date of submission of the testimonial.', 'true', 12);
	
	$tot_param_style = count($param_style_arr);
	for($i=0;$i<$tot_param_style;$i++) {
		if(fetchParamName(''.$param_style_arr[$i][0].'') == '') {
			$db->setQuery("INSERT INTO `#__".RSWEBSOLS_TABLE_PREFIX."_param_style` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', '".$param_style_arr[$i][0]."', '".$param_style_arr[$i][1]."', '".$param_style_arr[$i][2]."', ".$param_style_arr[$i][3].");");
			$db->query();
		}
	}
	
	$db->setQuery("DELETE FROM `#__".RSWEBSOLS_TABLE_PREFIX."_param` WHERE `param_name`='new_installation'");
	$db->query();
}

$rsws_newInstallation = fetchParamName('new_installation');
if($rsws_newInstallation == 'new_installation') {
	updateOldTable();
	updateSettingsTable();
}
?>