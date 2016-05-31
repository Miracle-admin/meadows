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

if(!function_exists("findProperSerialRSMonials")) {
	function findProperSerialRSMonials($prevId=0, $choice) {
		$allRSMonials = fetchAllRSMonials($choice);
		$chkr = 0;
		for($i=0; $i<count($allRSMonials); $i++) {
			if($allRSMonials[$i]['id'] > $prevId) {
				$displayRSMonials = $allRSMonials[$i];
				$chkr = 1;
				break;
			}
		}
		if($chkr == 0) {
			$displayRSMonials = $allRSMonials[0];
		}
		return $displayRSMonials;
	}
}

if(!function_exists("findProperRandomRSMonials")) {
	function findProperRandomRSMonials($choice) {
		$allRSMonials = fetchAllRSMonials($choice);
		$rand = rand(0, count($allRSMonials)-1);
		$displayRSMonials = $allRSMonials[$rand];
		return $displayRSMonials;
	}
}

if(!function_exists("fetchAllRSMonials")) {
	function fetchAllRSMonials($choice) {
		$db = JFactory::getDBO();
		if($choice == '') {
			$db->setQuery("select * from `#__rsmonials` where `status`='1' order by `id`");
			$testimonials = $db->loadAssocList();
		}
		else {
			$db->setQuery("select * from `#__rsmonials` where `status`='1' and `id` in (".$choice.") order by `id`");
			$testimonials = $db->loadAssocList();
		}
		for($i=0; $i<count($testimonials); $i++) {
			foreach($testimonials[$i] as $key=>$value) {
				$testimonials[$i][$key] = stripslashes($value);
			}
		}
		return $testimonials;
	}
}

$db = JFactory::getDBO();
$db->setQuery("select `extension_id` from `#__extensions` where `element`='com_rsmonials' and `enabled`='1'");
$cId = $db->loadObject();

if($cId->extension_id > 0) {

	$widthRSM = $params->get('rsm_width', '150');
	$choiceRSM = $params->get('rsm_randchoice', '');
	$displayRSM = $params->get('rsm_display', '0');
	$charRSM = $params->get('rsm_char', '300');
	$alignRSM = $params->get('rsm_align', 'justify');
	$displayaboutRSM = $params->get('rsm_aboutdisplay', 0);
	$displayurlRSM = $params->get('rsm_urldisplay', 0);
	$displaydateRSM = $params->get('rsm_datedisplay', 1);
	$ismoreRSM = $params->get('rsm_moredisplay', '0');
	$morealignRSM = $params->get('rsm_morealign', 'right');
	$moretextRSM = $params->get('rsm_moretext', 'View More &gt;&gt;');
	$moreurlRSM = $params->get('rsm_moreurl', '');
	
	$imgDispRSM = $params->get('rsm_imagedisplay', 0);
	$imgMwRSM = $params->get('rsm_image_maxw', 100);
	$imgMhRSM = $params->get('rsm_image_maxh', 100);
	$imgAlignRSM = $params->get('rsm_imagealignment', 0);
	$imgDefaultRSM = $params->get('rsm_imagedefault', 0);
	$imgBorderRSM = $params->get('rsm_image_border', '1px solid #DEDEDE');
	
	if($imgDispRSM == '1') {
		if($imgDefaultRSM == '1') {
			$RS_noimg = '<img src="'.JURI::root().'components/com_rsmonials/images/default_user_0.png" style="max-width:'.$imgMwRSM.'px; max-height:'.$imgMhRSM.'px; border:'.$imgBorderRSM.';" />';
		} else if($imgDefaultRSM == '2') {
			$RS_noimg = '<img src="'.JURI::root().'components/com_rsmonials/images/default_user_1.png" style="max-width:'.$imgMwRSM.'px; max-height:'.$imgMhRSM.'px; border:'.$imgBorderRSM.';" />';
		} else if($imgDefaultRSM == '3') {
			$RS_noimg = '<img src="'.JURI::root().'components/com_rsmonials/images/default_user_2.png" style="max-width:'.$imgMwRSM.'px; max-height:'.$imgMhRSM.'px; border:'.$imgBorderRSM.';" />';
		} else {
			$RS_noimg = '';
		}
	}
	
	$choiceRSM2 = explode(',', $choiceRSM);
	foreach($choiceRSM2 as $key=>$value) {
		$choiceRSM2[$key] = trim($value);
	}
	$choiceRSM = implode(',', $choiceRSM2);
	
	$displayRSMonials = '';
	if($displayRSM == '1') {
		if($_SESSION['prevDisplayIdRSM'] > 0) {
			$prevId = $_SESSION['prevDisplayIdRSM'];
		}
		else {
			$prevId = 0;
		}
		$displayRSMonials = findProperSerialRSMonials($prevId, ''.$choiceRSM.'');
	}
	else {
		$displayRSMonials = findProperRandomRSMonials(''.$choiceRSM.'');
	}
	if($displayRSMonials['id'] > 0) {
		$RSStripComment = stripslashes($displayRSMonials['comment']);
		$RStesti_pic_file = '';
		if($imgDispRSM == '1') {
			if(file_exists(JPATH_ROOT.DS.'images'.DS.'com_rsmonials'.DS.$displayRSMonials['id'].'.gif')) {
				$RStesti_pic_file = '<img src="'.JURI::root().'images/com_rsmonials/'.$displayRSMonials['id'].'.gif" style="max-width:'.$imgMwRSM.'px; max-height:'.$imgMhRSM.'px; border:'.$imgBorderRSM.';" />';
			} else if(file_exists(JPATH_ROOT.DS.'images'.DS.'com_rsmonials'.DS.$displayRSMonials['id'].'.png')) {
				$RStesti_pic_file = '<img src="'.JURI::root().'images/com_rsmonials/'.$displayRSMonials['id'].'.png" style="max-width:'.$imgMwRSM.'px; max-height:'.$imgMhRSM.'px; border:'.$imgBorderRSM.';" />';
			} else if(file_exists(JPATH_ROOT.DS.'images'.DS.'com_rsmonials'.DS.$displayRSMonials['id'].'.jpg')) {
				$RStesti_pic_file = '<img src="'.JURI::root().'images/com_rsmonials/'.$displayRSMonials['id'].'.jpg" style="max-width:'.$imgMwRSM.'px; max-height:'.$imgMhRSM.'px; border:'.$imgBorderRSM.';" />';
			} else if(file_exists(JPATH_ROOT.DS.'images'.DS.'com_rsmonials'.DS.$displayRSMonials['id'].'.jpeg')) {
				$RStesti_pic_file = '<img src="'.JURI::root().'images/com_rsmonials/'.$displayRSMonials['id'].'.jpeg" style="max-width:'.$imgMwRSM.'px; max-height:'.$imgMhRSM.'px; border:'.$imgBorderRSM.';" />';
			} else {
				$RStesti_pic_file = $RS_noimg;
			}
			if($imgAlignRSM == '1') {
				$RStesti_pic_file = '<div style="margin-bottom:5px; text-align:left;">'.$RStesti_pic_file.'</div>';
			} else if($imgAlignRSM == '2') {
				$RStesti_pic_file = '<div style="margin-bottom:5px; text-align:right;">'.$RStesti_pic_file.'</div>';
			} else if($imgAlignRSM == '3') {
				$RStesti_pic_file = '<span style="float:left; margin-right:5px;">'.$RStesti_pic_file.'</span>';
			} else if($imgAlignRSM == '4') {
				$RStesti_pic_file = '<span style="float:right; margin-left:5px;">'.$RStesti_pic_file.'</span>';
			} else {
				$RStesti_pic_file = '<div style="margin-bottom:5px; text-align:center;">'.$RStesti_pic_file.'</div>';
			}
		}
		$RSDt = explode('-', $displayRSMonials['date']);
		$RSText = '<div id="rsm1" style="width:'.$widthRSM.'px; padding: 0 5px;">';
		$RSText .= '<div id="rsm2" style="text-align:'.$alignRSM.';">'.$RStesti_pic_file.(($charRSM >0 ) ? ((strlen($RSStripComment) > $charRSM) ? (substr($RSStripComment, 0, ($charRSM-3)).'...') : $RSStripComment) : $RSStripComment).'<br /><br /><em><strong>'.$displayRSMonials['fname'].' '.$displayRSMonials['lname'].'</strong>';
		if($displayaboutRSM == '1') {
			if(($displayRSMonials['about'] != '') || ($displayRSMonials['location'] != '')) {
				$RSText .= '<br /><small>';
				$RS_isa = 0;
				if($displayRSMonials['about'] != '') {
					$RSText .= $displayRSMonials['about'];
					$RS_isa = 1;
				}
				if($displayRSMonials['location'] != '') {
					if($RS_isa == '1') {
						$RSText .= ', ';
					}
					$RSText .= $displayRSMonials['location'];
				}
				$RSText .= '</small>';
			}
		}
		if(($displayurlRSM == '1') && ($displayRSMonials['website'] != '')) {
			$RSText .= '<br /><small>'.$displayRSMonials['website'].'</small>';
		}
		if($displaydateRSM == '1') {
			$RSText .= '<br /><small>'.date('M d, Y', mktime(12, 0, 0, $RSDt[1], $RSDt[2], $RSDt[0])).'</small>';
		}
		$RSText .= '</em></div>';
		if($ismoreRSM == '1') {
			$RSText .= '<div id="rsm3" style="padding-top:5px;text-align:'.$morealignRSM.';"><a href="'.$moreurlRSM.'" title="'.$moretextRSM.'">'.$moretextRSM.'</a></div>';
		}
		$RSText .= '</div>';
		echo $RSText;
		$_SESSION['prevDisplayIdRSM'] = $displayRSMonials['id'];
	}
	else {
		echo '<div id="rsm4" style="padding: 0 5px;">No Testimonial Found.</div>';
	}
}
else {
	$RSText = '<div style="color:red; padding: 0 5px;" id="rsm5">To enable this module please download and install "RS-Monials" component from <a href="http://www.rswebsols.com" target="_blank">Here</a></div>';
	echo $RSText;
}
echo '<div id="rsm6" style="padding-bottom:5px;"></div>';
?>