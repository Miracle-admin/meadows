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

if(!defined('DS')) { define('DS', DIRECTORY_SEPARATOR); }

require_once(JPATH_BASE.DS.'components'.DS.'com_rsmonials'.DS.'includes'.DS.'rssettings.php');
require_once(JPATH_BASE.DS.'components'.DS.'com_rsmonials'.DS.'includes'.DS.'rsfunctions.php');

if(!isset($_REQUEST['task'])){$_REQUEST['task']='';}
if(!isset($_REQUEST['page'])){$_REQUEST['page']=0;}
if(!isset($_REQUEST['saved'])){$_REQUEST['saved']='';}
if(!isset($_REQUEST['err'])){$_REQUEST['err']='';}

$task = $_REQUEST['task'];

switch($task) {
	case 'submit':
		submit();
		break;
	default:
		display();
		break;
}

function headerT() {
	global $app;
	$config = JFactory::getConfig();
	$params = $app->getParams();
	
	// Page Title & Meta Description Config
	$rs_pTitle = $params->get('page_title');	
	$rs_isSitename = $config->get( 'sitename_pagetitles' );
	$rs_sitename = $config->get( 'sitename' );
	if($_REQUEST['page'] > 1) {
		$rs_pTitle = ''.JText::_('RSM_SEO_TITLE_PAGE').' '.$_REQUEST['page'].' '.JText::_('RSM_SEO_TITLE_SEPARATOR').' '.$rs_pTitle;
	}
	if($_REQUEST['saved'] == 'true') {
		$rs_pTitle = JText::_('RSM_SEO_TITLE_SUCCESS').' '.JText::_('RSM_SEO_TITLE_SEPARATOR').' '.$rs_pTitle;
	}
	if($_REQUEST['err'] == 'true') {
		$rs_pTitle = JText::_('RSM_SEO_TITLE_ERROR').' '.JText::_('RSM_SEO_TITLE_SEPARATOR').' '.$rs_pTitle;
	}
	if($rs_isSitename == '1') {
		$rs_pTitle = $rs_sitename.' '.JText::_('RSM_SEO_TITLE_SEPARATOR').' '.$rs_pTitle;
	} else if($rs_isSitename == '2') {
		$rs_pTitle = $rs_pTitle.' '.JText::_('RSM_SEO_TITLE_SEPARATOR').' '.$rs_sitename;
	} else {}
	JFactory::getDocument()->setTitle($rs_pTitle);
	JFactory::getDocument()->setDescription($rs_pTitle);
	//End
?>
<?php if ( $params->get('show_page_heading') ) { ?>
	<div class="page-header">
		<h2><?php echo stripHTML($params->get('page_title')); ?></h2>
	</div>
<?php } else { ?>
	<div class="page-header"><h2><?php echo JText::_('RSM_PAGE_TITLE'); ?></h2></div>
<?php } ?>
<?php
if(isset($_REQUEST['saved']) && $_REQUEST['saved'] == 'true') {
	if(fetchParam('auto_approval') == 'true') {
		echo "<p>&nbsp;</p><p class='RSWS_success'>".JText::_('RSM_MSG_AUTO_APPROVAL')."</p><p>&nbsp;</p>";
	}
	else {
		echo "<p>&nbsp;</p><p class='RSWS_success'>".JText::_('RSM_MSG_ADMIN_APPROVAL')."</p><p>&nbsp;</p>";
	}
}
?>
<?php
if(fetchParam('show_desc') == 'true') {
	echo "<p class='RSWS_desc'>".JText::_('RSM_PAGE_DESCRIPTION')."</p>";
}
?>
<?php if(($params->def('rsm_show_what') != '1') && ($params->def('rsm_show_what') != '2')) { ?><p class="RSWS_submit_link"><a href="<?php echo JRoute::_($_SERVER['REQUEST_URI'].'#submitform'); ?>"><?php echo JText::_('RSM_TXT_SUBMIT_A_TESTI'); ?></a></p><?php } ?>
<?php
}

function footerT($currPage=1) {
	global $app;
	$params	= $app->getParams();
?>
<script type="text/javascript">
function testimonialSubmit() {
	var f = document.rsmonialsForm;
	var err = 0;
	<?php if(fetchParam('show_single_name_field') == 'true') { ?>
	if(trim(f.fname.value) == '') {
		document.getElementById('fname_d').style.color='#ff0000';
		document.getElementById('fname').style.borderColor='#ff0000';
		err++;
	}
	else {
		document.getElementById('fname_d').style.color='';
		document.getElementById('fname').style.borderColor='';
	}
	<?php } else { ?>
	if(trim(f.fname.value) == '') {
		document.getElementById('fname_d').style.color='#ff0000';
		document.getElementById('fname').style.borderColor='#ff0000';
		err++;
	}
	else {
		document.getElementById('fname_d').style.color='';
		document.getElementById('fname').style.borderColor='';
	}
	if(trim(f.lname.value) == '') {
		document.getElementById('lname_d').style.color='#ff0000';
		document.getElementById('lname').style.borderColor='#ff0000';
		err++;
	}
	else {
		document.getElementById('lname_d').style.color='';
		document.getElementById('lname').style.borderColor='';
	}
	<?php } ?>
	if(trim(f.email.value) == '') {
		document.getElementById('email_d').style.color='#ff0000';
		document.getElementById('email').style.borderColor='#ff0000';
		err++;
	}
	else if((/^[a-zA-Z0-9._-]+@([a-zA-Z0-9.-]+\.)+[a-zA-Z.]{2,5}$/).exec(f.email.value)==null) {
		document.getElementById('email_d').style.color='#ff0000';
		document.getElementById('email').style.borderColor='#ff0000';
		err++;
	}
	else {
		document.getElementById('email_d').style.color='';
		document.getElementById('email').style.borderColor='';
	}
	<?php if(fetchParam('show_captcha') != 'false') { ?>
	<?php if(fetchParam('use_recaptcha') == 'true') { ?>
	if(trim(f.recaptcha_response_field.value) == '') {
		document.getElementById('security_code_d').style.color='#ff0000';
		document.getElementById('recaptcha_response_field').style.borderColor='#ff0000';
		err++;
	}
	else {
		document.getElementById('security_code_d').style.color='';
		document.getElementById('recaptcha_response_field').style.borderColor='';
	}
	<?php } else { ?>
	if(trim(f.security_code.value) == '') {
		document.getElementById('security_code_d').style.color='#ff0000';
		document.getElementById('security_code').style.borderColor='#ff0000';
		err++;
	}
	else {
		document.getElementById('security_code_d').style.color='';
		document.getElementById('security_code').style.borderColor='';
	}
	<?php } ?>
	<?php } ?>
	if(trim(f.comments.value) == '') {
		document.getElementById('comments_d').style.color='#ff0000';
		document.getElementById('comments').style.borderColor='#ff0000';
		err++;
	}
	else {
		document.getElementById('comments_d').style.color='';
		document.getElementById('comments').style.borderColor='';
	}
	
	if(err > 0) {
		return false;
	}
	else {
		f.submit();
	}
}

function trim(str, chars) {
    return ltrim(rtrim(str, chars), chars);
}

function ltrim(str, chars) {
    chars = chars || "\\s";
    return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
}

function rtrim(str, chars) {
    chars = chars || "\\s";
    return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
}

window.onload = function() {
	var e = document.getElementsByClassName('comment-form-ant-spm', 'comment-form-ant-spm-2');
	for(var i=0; i<e.length; i++) {
		e[i].style.display='none';	
	}
	document.getElementById('ant-spm-q').value = document.getElementById('ant-spm-a').value;
};
</script>
<?php
if($params->def('rsm_show_what') != '1') {
	$rsws_fshow = true;
	$user_logged = false;
	$user = JFactory::getUser();
	$usr_id = $user->get('id');
	if($usr_id > 0) { $user_logged = true; }
	if(fetchParam('login_to_submit_testimonial') == 'true') {
		if($usr_id > 0) {
			$rsws_fshow = true;
		} else {
			$rsws_fshow = false;
		}
	}
	if($rsws_fshow == true) {
		//$postA = $_SESSION['RSM_post'];
		$session = JFactory::getSession();
		$postA = $session->get('RSM_post');
?>
<a name="submitform"></a>
<div class="RSWS_testi_block RSWS_testi_block_form">
<div class="RSWS_form_heading"><?php echo JText::_('RSM_FORM_TXT_HEADING'); ?></div>
<div>
<form name="rsmonialsForm" action="index.php" method="post" class="rsmonialsForm" enctype="multipart/form-data">
<input type="hidden" name="option" id="option" value="com_rsmonials" />
<input type="hidden" name="task" id="task" value="submit" />
<input type="hidden" name="Itemid" id="Itemid" value="<?php echo $_REQUEST['Itemid']; ?>" />
<input type="hidden" name="page" id="page" value="<?php echo $currPage; ?>" />
	<table class="RSWS_form_main" cellpadding="5" cellspacing="5" border="0">
	<?php if(isset($_REQUEST['err']) && $_REQUEST['err'] == 'true') { ?>
	<tr>
	  <td colspan="2" class="RSWS_form_error">
	  <p><?php echo JText::_('RSM_MSG_ERR_CORRECTION'); ?></p>
	  <div>
	  <ul>
	  	<?php
			//$errA = $_SESSION['RSM_error'];
			$errA = $session->get('RSM_error');
			foreach($errA as $val) {
				echo '<li>'.$val.'</li>';
			}
		?>
	  </ul>
	  </div>
	  </td>
	</tr>
	<?php } ?>
	<tr><td colspan="2" class="RSWS_form_mandatory"><?php echo JText::_('RSM_FORM_TXT_MANDATORY'); ?></td></tr>
	<?php if(fetchParam('show_single_name_field') == 'true') { ?>
    <tr><td class="RSWS_form_first_col"><span id="fname_d" class="RSWS_form_text"><?php echo JText::_('RSM_FORM_TXT_NAME'); ?>:</span><span class="RSWS_form_star_color">*</span></td><td><input name="fname" id="fname" type="text" maxlength="50" class="RSWS_form_input" value="<?php if(isset($postA['fname'])) { echo stripHTML($postA['fname']); } ?>" /></td></tr>
    <?php } else { ?>
    <tr><td class="RSWS_form_first_col"><span id="fname_d" class="RSWS_form_text"><?php echo JText::_('RSM_FORM_TXT_FNAME'); ?>:</span><span class="RSWS_form_star_color">*</span></td><td><input name="fname" id="fname" type="text" maxlength="50" class="RSWS_form_input" value="<?php if(isset($postA['fname'])) { echo stripHTML($postA['fname']); } ?>" /></td></tr>
	<tr><td><span id="lname_d" class="RSWS_form_text"><?php echo JText::_('RSM_FORM_TXT_LNAME'); ?>:</span><span class="RSWS_form_star_color">*</span></td><td><input name="lname" id="lname" type="text" maxlength="50" class="RSWS_form_input" value="<?php if(isset($postA['lname'])) { echo stripHTML($postA['lname']); } ?>" /></td></tr>
	<?php } ?>
    <tr><td><span id="email_d" class="RSWS_form_text"><?php echo JText::_('RSM_FORM_TXT_EMAIL'); ?>:</span><span class="RSWS_form_star_color">*</span></td><td><input name="email" id="email" type="text" maxlength="100" class="RSWS_form_input" value="<?php if(isset($postA['email'])) { echo stripHTML($postA['email']); } ?>" /></td></tr>
	<?php if(fetchParam('show_about') != 'false') { ?>
	<tr><td><span class="RSWS_form_text"><?php echo JText::_('RSM_FORM_TXT_ABOUT'); ?>:</span></td><td><input name="about" id="about" type="text" class="RSWS_form_input" value="<?php if(isset($postA['about'])) { echo stripHTML($postA['about']); } ?>" /></td></tr>
	<?php } ?>
	<?php if(fetchParam('show_location') != 'false') { ?>
	<tr><td><span class="RSWS_form_text"><?php echo JText::_('RSM_FORM_TXT_LOCATION'); ?>:</span></td><td><input name="location" id="location" type="text" maxlength="255" class="RSWS_form_input" value="<?php if(isset($postA['location'])) { echo stripHTML($postA['location']); } ?>" /></td></tr>
	<?php } ?>
	<?php if(fetchParam('show_website') != 'false') { ?>
	<tr><td><span class="RSWS_form_text"><?php echo JText::_('RSM_FORM_TXT_WEBSITE'); ?>:</span></td><td><input name="website" id="website" type="text" maxlength="255" class="RSWS_form_input" value="<?php if(isset($postA['website'])) { echo stripHTML($postA['website']); } ?>" /></td></tr>
	<?php } ?>
    <?php if(fetchParam('show_image') == 'true') { ?>
	<tr><td><span class="RSWS_form_text"><?php echo JText::_('RSM_FORM_TXT_IMG'); ?>:</span></td><td><input type="file" name="testi_pic" id="testi_pic" /></td></tr>
    <tr><td></td><td><?php echo JText::sprintf('RSM_FORM_TXT_IMG_INFO', fetchParam('image_max_width'), fetchParam('image_max_height'), fetchParam('image_max_size')); ?></td></tr>
	<?php } ?>
	<?php
	if(fetchParam('show_captcha') != 'false') {
		if(fetchParam('use_recaptcha') == 'true') {
			$rs_rc_theme = fetchParam('recaptcha_theme');
			if($rs_rc_theme == '1') {
				$rs_rc_theme_name = 'red';
			} else if($rs_rc_theme == '2') {
				$rs_rc_theme_name = 'blackglass';
			} else if($rs_rc_theme == '3') {
				$rs_rc_theme_name = 'white';
			} else if($rs_rc_theme == '4') {
				$rs_rc_theme_name = 'clean';
			}
			$rs_rc_style = 'var RecaptchaOptions = { theme : \''.$rs_rc_theme_name.'\' };';
			$rs_rc_document	= JFactory::getDocument();
			$rs_rc_document->addScriptDeclaration($rs_rc_style);
			require_once(JPATH_BASE.DS.'components'.DS.'com_rsmonials'.DS.'includes'.DS.'recaptchalib.php');
			$rs_rc_publickey = fetchParam('recaptcha_public_key');
			$rs_rc_error = $session->get('RSM_rc');
		?>
    <tr><td valign="top"><span class="RSWS_form_text" id="security_code_d"><?php echo JText::_('RSM_FORM_TXT_SECURITY'); ?>:</span></td><td>
	<?php echo recaptcha_get_html($rs_rc_publickey, $rs_rc_error); ?>
	</td></tr>    
        <?php
		} else {
			$rand = rand(1111,9999);
	?>
	<tr><td><span class="RSWS_form_text"><?php echo JText::_('RSM_FORM_TXT_SECURITY'); ?>:</span></td><td>
	<img id="imgCaptcha" src="components/com_rsmonials/includes/rscaptcha.php" alt="" />
	</td></tr>
	<tr><td><span id="security_code_d" class="RSWS_form_text"><?php echo JText::_('RSM_FORM_TXT_ENTER_SECURITY'); ?>:</span><span class="RSWS_form_star_color">*</span></td><td><input name="security_code" id="security_code" type="text" maxlength="255" class="RSWS_form_input" /></td></tr>
	<?php
		}
	}
	?>
	<tr><td colspan="2"><span id="comments_d" class="RSWS_form_text"><?php echo JText::_('RSM_FORM_TXT_COMMENT'); ?>:</span><span class="RSWS_form_star_color">*</span></td></tr>
	<tr><td colspan="2"><textarea name="comments" id="comments" class="RSWS_form_textarea" rows="" cols=""><?php if(isset($postA['comments'])) { echo stripHTML($postA['comments']); } ?></textarea></td></tr>
	<tr><td colspan="2" style="text-align:center;"><input type="button" value="<?php echo JText::_('RSM_FORM_BUTTON_SUBMIT'); ?>" class="RSWS_form_button" onclick="return testimonialSubmit();" /></td></tr>
	</table>
    <?php
	if($user_logged == false) {
		// question (hidden with js) [aria-required="true" required="required"]
		$antispam_form_part = '<p class="comment-form-ant-spm" style="clear:both; visibility:hidden;"><strong>Current <span style="display:none;">month</span> <span style="display:inline;">ye@r</span> <span style="display:none;">day</span></strong> <span class="required">*</span><input type="hidden" name="ant-spm-a" id="ant-spm-a" value="'.date('Y').'" /><input type="text" name="ant-spm-q" id="ant-spm-q" size="30" value="19" /></p>';
		// empty field (hidden with css)
		$antispam_form_part .= '<p class="comment-form-ant-spm-2" style="display:none;"><strong>Leave this field empty</strong> <span class="required">*</span><input type="text" name="ant-spm-e-email-url" id="ant-spm-e-email-url" size="30" value=""/></p>';
		echo $antispam_form_part;
	}
	?>
</form>
</div>
</div>
<?php } else { ?>
<a name="submitform"></a>
<div class="RSWS_testi_block RSWS_testi_block_login">
<div class="RSWS_form_heading"><?php echo JText::_('RSM_FORM_TXT_HEADING'); ?></div>
<div>
	<table class="RSWS_form_main" cellpadding="5" cellspacing="5" border="0">
	<tr>
	  <td class="RSWS_form_error"><?php echo JText::_('RSM_FORM_MSG_LOGIN_TO_COMMENT'); ?></td>
	</tr>
	</table>
</div>
</div>
<?php }}} ?>
<?php
function display() {
	global $app;
	$params	= $app->getParams();
	$rsws_document	= JFactory::getDocument();
	$rsws_document->addStyleSheet(JURI::root().'components/com_rsmonials/css/style.css');
	/* $rsws_document->addScript(JURI::root().'components/com_rsmonials/js/core.js'); */
	// Custom Style
	$rsws_custom_style = '.RSWS_testi_block {';
	
	$rsws_testimonial_block_border = fetchParamStyle('testimonial_block_border');
	if($rsws_testimonial_block_border) { $rsws_custom_style .= ' border: '.$rsws_testimonial_block_border.';'; }
	
	$rsws_testimonial_block_background_color = fetchParamStyle('testimonial_block_background_color');
	if($rsws_testimonial_block_background_color) { $rsws_custom_style .= ' background-color: '.$rsws_testimonial_block_background_color.';'; }
	
	$rsws_testimonial_block_rounded_corner = fetchParamStyle('testimonial_block_rounded_corner');
	$rsws_testimonial_block_rounded_corner_radius = fetchParamStyle('testimonial_block_rounded_corner_radius');
	if(!$rsws_testimonial_block_rounded_corner_radius) { $rsws_testimonial_block_rounded_corner_radius = '10'; }
	if($rsws_testimonial_block_rounded_corner == 'true') { $rsws_custom_style .= ' -moz-border-radius:'.$rsws_testimonial_block_rounded_corner_radius.'px; -webkit-border-radius:'.$rsws_testimonial_block_rounded_corner_radius.'px; behavior:url(border-radius.htc);'; }
	
	$rsws_testimonial_block_enable_gradient = fetchParamStyle('testimonial_block_enable_gradient');
	$rsws_testimonial_block_gradient_start_color = fetchParamStyle('testimonial_block_gradient_start_color');
	$rsws_testimonial_block_gradient_end_color = fetchParamStyle('testimonial_block_gradient_end_color');
	if($rsws_testimonial_block_enable_gradient == 'true') { $rsws_custom_style .= ' filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="'.$rsws_testimonial_block_gradient_start_color.'", endColorstr="'.$rsws_testimonial_block_gradient_end_color.'"); background: -webkit-gradient(linear, left top, left bottom, from('.$rsws_testimonial_block_gradient_start_color.'), to('.$rsws_testimonial_block_gradient_end_color.')); background: -moz-linear-gradient(top,  '.$rsws_testimonial_block_gradient_start_color.',  '.$rsws_testimonial_block_gradient_end_color.');'; }
	
	$rsws_custom_style .= '}';
	//
	$rsws_document->addStyleDeclaration($rsws_custom_style);
	headerT();
	//echo '<div><br /><hr align="center" width="100%" size="1" noshade="noshade" class="RSWS_hr_color" /><br /></div>';
	if($params->def('rsm_show_what') != '2') {
		$database = JFactory::getDBO();
		if(fetchParam('show_pagination') == 'true') {
			$database->setQuery("select count(*) as tot from `#__".RSWEBSOLS_TABLE_PREFIX."` where `status`='1'");
			$dataTesti = $database->loadObject();
			$totalTesti = $dataTesti->tot;
			$paginationStore = fetchParam('pagination');
			$itemEachPage = (($paginationStore > 0) ? fetchParam('pagination') : 10);
			$totalTestiPage = ceil($totalTesti/$itemEachPage);
			if(isset($_REQUEST['page']) && $_REQUEST['page'] > 0) {
				$currPage = $_REQUEST['page'];
			}
			else {
				$currPage = 1;
			}
			$lStart = ($currPage-1)*$itemEachPage;
			$lEnd = $itemEachPage;
			$database->setQuery("select * from `#__".RSWEBSOLS_TABLE_PREFIX."` where `status`='1' order by `date` desc, `id` desc limit ".$lStart.", ".$lEnd."");
		}
		else {
			$database->setQuery("select * from `#__".RSWEBSOLS_TABLE_PREFIX."` where `status`='1' order by `date` desc, `id` desc");
		}
		
		$items = $database->loadObjectList();
		if(count($items) > 0) {
			$rsws_qis = fetchParamStyle('testimonial_block_quotation_image_style');
			$rsws_noimg = fetchParamStyle('testimonial_block_default_image');
			$rsws_imgpos = fetchParamStyle('testimonial_block_image_position');
			$rsws_imgmax = fetchParamStyle('testimonial_block_image_display_width');
			$rsws_dshow = fetchParamStyle('testimonial_block_show_date');
			
			if($rsws_qis == '0') {
				$left_quote = '';
				$right_quote = '';
			} else if($rsws_qis == '1') {
				$left_quote = '<span class="RSWS_left_quote"><img src="components/com_rsmonials/images/quote-left.png" /></span>';
				$right_quote = '<span class="RSWS_right_quote"><img src="components/com_rsmonials/images/quote-right.png" /></span>';
			} else {
				$left_quote = '<span class="RSWS_left_quote"><img src="components/com_rsmonials/images/quote_left.png" /></span>';
				$right_quote = '<span class="RSWS_right_quote"><img src="components/com_rsmonials/images/quote_right.png" /></span>';
			}
			
			if($rsws_noimg == '1') {
				$rsws_noimg = '<img class="RSWS_testi_img" src="'.JURI::root().'components/com_rsmonials/images/default_user_0.png" style="width:'.$rsws_imgmax.'px;" />';
			} else if($rsws_noimg == '2') {
				$rsws_noimg = '<img class="RSWS_testi_img" src="'.JURI::root().'components/com_rsmonials/images/default_user_1.png" style="width:'.$rsws_imgmax.'px;" />';
			} else if($rsws_noimg == '3') {
				$rsws_noimg = '<img class="RSWS_testi_img" src="'.JURI::root().'components/com_rsmonials/images/default_user_2.png" style="width:'.$rsws_imgmax.'px;" />';
			} else {
				$rsws_noimg = '';
			}
			
			$rsws_alt_cntr = 1;
			foreach($items as $item) {
				$dateExp = explode('-', $item->date);
				$timestamp = mktime(12,0,0,$dateExp[1],$dateExp[2],$dateExp[0]);
				$dateView = strftime("%d ".getMonth($dateExp[1])." %Y", $timestamp);
				$extra = "";
				$extra2 = "";
				if(trim($item->about) != "") {
					if($extra != "") {
						$extra .= ", ";
					}
					else {
						$extra .= "<br />";
					}
					$extra .= stripHTML($item->about);
				}
				if(trim($item->location) != "") {
					if($extra != "") {
						$extra .= ", ";
					}
					else {
						$extra .= "<br />";
					}
					$extra .= stripHTML($item->location);
				}
				
				if(trim($item->website) != "") {
					if(fetchParam('show_website_as_link') == 'true') {
						$extra2 .= "<br /><a href='".makeLink($item->website)."' target='_blank' rel='nofollow'>".stripHTML($item->website).'</a>';
					} else {
						$extra2 .= "<br />".stripHTML($item->website);
					}
				}
				?>
				<div class="RSWS_testi_block RSWS_testi_block_<?php echo $rsws_alt_cntr%2; ?>">
					<?php
					$rsws_testi_cont = '<div class="RSWS_testimonial">'.$left_quote.'<span>'.stripHTML($item->comment, true).'</span>'.$right_quote.'</div>';
					$rsws_testi_subcont = '<div>&nbsp;</div><div class="RSWS_testmonial_subtext">';
					if($rsws_dshow != 'false') {
						$rsws_testi_subcont .= '<em>'.JText::_('RSM_TXT_POSTING_DATE').': '.$dateView.'<br />';
					}
					$rsws_testi_subcont .= ''.JText::_('RSM_TXT_POSTED_BY').': '.stripHTML($item->fname).' '.stripHTML($item->lname).$extra.$extra2.'</em></div>';
					if(fetchParam('show_image') == 'true') {
						$testi_pic_file = '';
						if(file_exists(JPATH_ROOT.DS.'images'.DS.'com_rsmonials'.DS.$item->id.'.gif')) {
							$testi_pic_file = '<img class="RSWS_testi_img" src="'.JURI::root().'images/com_rsmonials/'.$item->id.'.gif" style="width:'.$rsws_imgmax.'px;" />';
						} else if(file_exists(JPATH_ROOT.DS.'images'.DS.'com_rsmonials'.DS.$item->id.'.png')) {
							$testi_pic_file = '<img class="RSWS_testi_img" src="'.JURI::root().'images/com_rsmonials/'.$item->id.'.png" style="width:'.$rsws_imgmax.'px;" />';
						} else if(file_exists(JPATH_ROOT.DS.'images'.DS.'com_rsmonials'.DS.$item->id.'.jpg')) {
							$testi_pic_file = '<img class="RSWS_testi_img" src="'.JURI::root().'images/com_rsmonials/'.$item->id.'.jpg" style="width:'.$rsws_imgmax.'px;" />';
						} else if(file_exists(JPATH_ROOT.DS.'images'.DS.'com_rsmonials'.DS.$item->id.'.jpeg')) {
							$testi_pic_file = '<img class="RSWS_testi_img" src="'.JURI::root().'images/com_rsmonials/'.$item->id.'.jpeg" style="width:'.$rsws_imgmax.'px;" />';
						} else {
							$testi_pic_file = $rsws_noimg;
						}
						echo '<table width="100%" cellpadding="0" cellspacing="0" border="0" class="RSWS_testi_main"><tr>';
						if($rsws_imgpos == '3') {
							if($rsws_alt_cntr %2 == 0) {
								echo '<td valign="top" align="left">'.$rsws_testi_cont.$rsws_testi_subcont.'</td><td align="right" valign="top" style="width:'.($testi_pic_file=='' ? 0 : ($rsws_imgmax+20)).'px;">'.$testi_pic_file.'</td>';
							} else {
								echo '<td valign="top" align="left" style="width:'.($testi_pic_file=='' ? 0 : ($rsws_imgmax+20)).'px;">'.$testi_pic_file.'</td><td valign="top" align="left">'.$rsws_testi_cont.$rsws_testi_subcont.'</td>';
							}
						} else if($rsws_imgpos == '2') {
							echo '<td valign="top" align="left">'.$rsws_testi_cont.$rsws_testi_subcont.'</td><td valign="top" align="right" style="width:'.($testi_pic_file=='' ? 0 : ($rsws_imgmax+20)).'px;">'.$testi_pic_file.'</td>';
						} else {
							echo '<td valign="top" align="left" style="width:'.($testi_pic_file=='' ? 0 : ($rsws_imgmax+20)).'px;">'.$testi_pic_file.'</td><td valign="top" align="left">'.$rsws_testi_cont.$rsws_testi_subcont.'</td>';
						}
						echo '</tr></table>';
					} else {
						echo $rsws_testi_cont.$rsws_testi_subcont;
					}
					?>
				</div>
				<?php
				$rsws_alt_cntr++;
			}
			if(fetchParam('show_pagination') == 'true') {
			?>
				<div class="RSWS_pagination_text" style="text-align:<?php echo fetchParam('pagination_alignment'); ?>;">
					<br />
					<?php if($currPage > 1) { ?> <a href="index.php?option=<?php echo $_REQUEST['option']; ?>&page=1" title="<?php echo JText::_('RSM_TXT_PAGINATION_START'); ?>"><?php echo JText::_('RSM_TXT_PAGINATION_START'); ?></a> <?php } else { echo JText::_('RSM_TXT_PAGINATION_START'); } ?>
					<?php if($currPage > 1) { ?> <a href="index.php?option=<?php echo $_REQUEST['option']; ?>&page=<?php echo ($currPage-1); ?>" title="<?php echo JText::_('RSM_TXT_PAGINATION_PREV'); ?>"><?php echo JText::_('RSM_TXT_PAGINATION_PREV'); ?></a> <?php } else { echo JText::_('RSM_TXT_PAGINATION_PREV'); } ?>
					<?php
					for($i=1; $i<=$totalTestiPage; $i++) {
						if($currPage == $i) {
							echo ' <strong>'.$i.'</strong> ';
						}
						else {
							echo ' <a href="index.php?option='.$_REQUEST['option'].'&page='.$i.'">'.$i.'</a> ';
						}
					}
					?>
					<?php if($currPage < $totalTestiPage) { ?> <a href="index.php?option=<?php echo $_REQUEST['option']; ?>&page=<?php echo ($currPage+1); ?>" title="<?php echo JText::_('RSM_TXT_PAGINATION_NEXT'); ?>"><?php echo JText::_('RSM_TXT_PAGINATION_NEXT'); ?></a> <?php } else { echo JText::_('RSM_TXT_PAGINATION_NEXT'); } ?>
					<?php if($currPage < $totalTestiPage) { ?> <a href="index.php?option=<?php echo $_REQUEST['option']; ?>&page=<?php echo $totalTestiPage; ?>" title="<?php echo JText::_('RSM_TXT_PAGINATION_LAST'); ?>"><?php echo JText::_('RSM_TXT_PAGINATION_LAST'); ?></a> <?php } else { echo JText::_('RSM_TXT_PAGINATION_LAST'); } ?>
					<br /><br />
					<?php echo JText::sprintf('RSM_TXT_PAGINATION_PAGE_OUTOF_TOTAL', $currPage, $totalTestiPage); ?>
				</div>
				<div><br /></div>
				<div><br /></div>
			<?php
			}
		}
		else {
		?>
			<div>
				<p>
					<span class="RSWS_left_quote"><img src="components/com_rsmonials/images/lrs.gif" /></span>
					<span class="RSWS_testimonial"><?php echo JText::_('RSM_TXT_NO_TESTI'); ?></span>
					<span class="RSWS_right_quote"><img src="components/com_rsmonials/images/rrs.gif" /></span>
				</p>
			</div>
			<div>
				<br />
				<hr align="center" width="100%" size="1" noshade="noshade" class="RSWS_hr_color" />
				<br />
			</div>
		<?php
		}
	}
	if(isset($currPage)) { footerT($currPage); } else { footerT(); }
}

function submit() {
	//unset($_SESSION['RSM_error']);
	//unset($_SESSION['RSM_post']);
	$session = JFactory::getSession();
	$session->set('RSM_error', '');
	$session->set('RSM_post', '');
	$session->set('RSM_rc', '');
	$isfalse = false;
	$RSM_error = array();
	$user = JFactory::getUser();
	$usr_id = $user->get('id');
	if(fetchParam('login_to_submit_testimonial') == 'true') {
		if($usr_id > 0) {} else {
			$isfalse = true;
			$RSM_error[] = JText::_('RSM_MSG_ERR_LOGIN_FAIL');
		}
	}
	if(fetchParam('show_single_name_field') != 'false') {
		if(trim($_POST['fname']) == '') {
			$isfalse = true;
			$RSM_error[] = JText::_('RSM_MSG_ERR_NAME');
		}
	} else {
		if(trim($_POST['fname']) == '') {
			$isfalse = true;
			$RSM_error[] = JText::_('RSM_MSG_ERR_FNAME');
		}
		if(trim($_POST['lname']) == '') {
			$isfalse = true;
			$RSM_error[] = JText::_('RSM_MSG_ERR_LNAME');
		}
	}
	if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", trim($_POST['email']))) {
		$isfalse = true;
		$RSM_error[] = JText::_('RSM_MSG_ERR_EMAIL');
	}
	
	if(fetchParam('show_image') == 'true') {
		if(is_uploaded_file($_FILES['testi_pic']['tmp_name'])) {
			$max_s = fetchParam('image_max_size');
			$max_h = fetchParam('image_max_height');
			$max_w = fetchParam('image_max_width');
			$err = '';
			$img_settings = getimagesize($_FILES['testi_pic']['tmp_name']);
			if(($img_settings[2] != 1) && ($img_settings[2] != 2) && ($img_settings[2] != 3)) {
				$isfalse = true;
				$RSM_error[] = JText::_('RSM_MSG_ERR_PICTURE_NOT_SUPPORTED');
			} else if($_FILES['testi_pic']['size'] > $max_s*1024) {
				$isfalse = true;
				$RSM_error[] = JText::sprintf('RSM_MSG_ERR_PICTURE_IS_OVER_SIZE', $max_w, $max_h, $max_s);
			} else if($img_settings[0] > $max_w) {
				$isfalse = true;
				$RSM_error[] = JText::sprintf('RSM_MSG_ERR_PICTURE_IS_OVER_SIZE', $max_w, $max_h, $max_s);
			} else if($img_settings[1] > $max_h) {
				$isfalse = true;
				$RSM_error[] = JText::sprintf('RSM_MSG_ERR_PICTURE_IS_OVER_SIZE', $max_w, $max_h, $max_s);
			} else {}
		}
	}
	
	if(fetchParam('show_captcha') != 'false') {
		if(fetchParam('use_recaptcha') == 'true') {
			require_once(JPATH_BASE.DS.'components'.DS.'com_rsmonials'.DS.'includes'.DS.'recaptchalib.php');
			$rs_rc_privatekey = fetchParam('recaptcha_private_key');
			$rs_rc_resp = recaptcha_check_answer ($rs_rc_privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
			if ($rs_rc_resp->is_valid) {} else {
				$rs_rc_error = $rs_rc_resp->error;
				$isfalse = true;
				$RSM_error[] = JText::_('RSM_MSG_ERR_SECURITY_CODE');
			}
		} else {
			if($session->get("RSM_code") != $_POST['security_code']) {
				$isfalse = true;
				$RSM_error[] = JText::_('RSM_MSG_ERR_SECURITY_CODE');
			}
		}
	}
	if(trim($_POST['comments']) == '') {
		$isfalse = true;
		$RSM_error[] = JText::_('RSM_MSG_ERR_COMMENTS');
	}
	$retpagenumber = '';
	if($_POST['page'] > 1) {
		$retpagenumber = '&page='.$_POST['page'];
	}
	if($isfalse == false) {
		### Final Spam
		$spam_flag = false;
		if($usr_id > 0) { } else {
			if ( trim( $_POST['ant-spm-q'] ) != date('Y') ) { $spam_flag = true; }
			if ( ! empty( $_POST['ant-spm-e-email-url'] ) ) { $spam_flag = true; }
		}
		### Final Spam End
		$rsm_is_ip_blocked = isIPBlock();
		if($rsm_is_ip_blocked == false) {
			foreach($_POST as $key=>$value) {
				$_POST[$key] = safeHTML($value);
			}
			$database = JFactory::getDBO();
			if($spam_flag == true) {
				$tesistatus = 3;
			} else if(fetchParam('auto_approval') == 'true') {
				$tesistatus = 1;
			}
			else {
				$tesistatus = 2;
			}
			$database->setQuery("insert into `#__".RSWEBSOLS_TABLE_PREFIX."`(`id`, `fname`, `lname`, `about`, `location`, `website`, `email`, `comment`, `ip`, `date`, `status`) values('', '".$database->escape($_POST['fname'])."', '".$database->escape($_POST['lname'])."', '".$database->escape($_POST['about'])."', '".$database->escape($_POST['location'])."', '".$database->escape($_POST['website'])."', '".$database->escape($_POST['email'])."', '".$database->escape($_POST['comments'])."', '".getClientIP()."', '".date('Y-m-d')."', '".$tesistatus."')");
			$database->query();
			
			//$session->set('RSM_error', '');
			//$session->set('RSM_post', '');
			//$session->set('RSM_rc', '');
			
			if(is_uploaded_file($_FILES['testi_pic']['tmp_name'])) {
				$new_t_id = $database->insertid();
				$upload_dir_path = JPATH_ROOT.DS.'images'.DS.'com_rsmonials';
				if(!file_exists($upload_dir_path)) {
					mkdir($upload_dir_path, 0755);	
				}
				$upload_path = $upload_dir_path.DS.$new_t_id.'.';
				$img_settings = getimagesize($_FILES['testi_pic']['tmp_name']);
				if($img_settings[2] == 1) {
					$upload_path .= 'gif';
				} else if($img_settings[2] == 2) {
					$upload_path .= 'jpg';
				} else if($img_settings[2] == 3) {
					$upload_path .= 'png';
				}
				move_uploaded_file($_FILES['testi_pic']['tmp_name'], $upload_path);
			}
			
			if(fetchParam('admin_email_alert') == 'true') {
				$smFrom = $_POST['email'];
				$smName = $_POST['fname'].' '.$_POST['lname'];
				$smSubject = JText::_('RSM_EMAIL_ADMIN_SUBJECT');
				$smBody = JText::_('RSM_EMAIL_ADMIN_BODY');
				sendMail($smFrom, $smName, $smSubject, $smBody);
			}
		}
		header('location:'.JRoute::_("index.php?option=com_rsmonials&Itemid=".$_REQUEST['Itemid'].$retpagenumber."&saved=true", false).'');
		exit();
	}
	else {
		//$_SESSION['RSM_error'] = $RSM_error;
		//$_SESSION['RSM_post'] = $_POST;
		$session->set('RSM_error', $RSM_error);
		$session->set('RSM_post', $_POST);
		$session->set('RSM_rc', $rs_rc_error);
		header('location:'.JRoute::_("index.php?option=com_rsmonials&Itemid=".$_REQUEST['Itemid'].$retpagenumber."&err=true#submitform", false).'');
		exit();
	}
}
?>