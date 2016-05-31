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

$db = JFactory::getDBO();
$db->setQuery("select `extension_id` from `#__extensions` where `element`='com_rsmonials' and `enabled`='1'");
$cId = $db->loadObject();

if($cId->extension_id > 0) {

	$width_RSMSC = $params->get('rsm_scroller_width', 200);
	$height_RSMSC = $params->get('rsm_scroller_height', 200);
	$border_thickness_RSMSC = $params->get('rsm_scroller_border_thickness', 0);
	$border_style_RSMSC = $params->get('rsm_scroller_border_style', 'solid');
	$border_color_RSMSC = $params->get('rsm_scroller_border_color', '#CCCCCC');
	$padding_RSMSC = $params->get('rsm_scroller_padding', 5);
	$delay_RSMSC = $params->get('rsm_scroller_delay', 5000);
	$ordering_RSMSC = $params->get('rsm_scroller_ordering', 'random');
	$load_RSMSC = $params->get('rsm_scroller_load', 0);
	$additional_RSMSC = $params->get('rsm_scroller_additional', '');
	$alignRSMSC = $params->get('rsm_align', 'justify');
	
	$displayaboutRSMSC = $params->get('rsm_aboutdisplay', 0);
	$displayurlRSMSC = $params->get('rsm_urldisplay', 0);
	$displaydateRSMSC = $params->get('rsm_datedisplay', 1);
	
	$imgDispRSMSC = $params->get('rsm_imagedisplay', 0);
	$imgMwRSMSC = $params->get('rsm_image_maxw', 100);
	$imgMhRSMSC = $params->get('rsm_image_maxh', 100);
	$imgAlignRSMSC = $params->get('rsm_imagealignment', 0);
	$imgDefaultRSMSC = $params->get('rsm_imagedefault', 0);
	$imgBorderRSMSC = $params->get('rsm_image_border', '1px solid #DEDEDE');
	
	if($imgDispRSMSC == '1') {
		if($imgDefaultRSMSC == '1') {
			$RS_noimg = '<img src="'.JURI::root().'components/com_rsmonials/images/default_user_0.png" style="max-width:'.$imgMwRSMSC.'px; max-height:'.$imgMhRSMSC.'px; border:'.$imgBorderRSMSC.';" />';
		} else if($imgDefaultRSMSC == '2') {
			$RS_noimg = '<img src="'.JURI::root().'components/com_rsmonials/images/default_user_1.png" style="max-width:'.$imgMwRSMSC.'px; max-height:'.$imgMhRSMSC.'px; border:'.$imgBorderRSMSC.';" />';
		} else if($imgDefaultRSMSC == '3') {
			$RS_noimg = '<img src="'.JURI::root().'components/com_rsmonials/images/default_user_2.png" style="max-width:'.$imgMwRSMSC.'px; max-height:'.$imgMhRSMSC.'px; border:'.$imgBorderRSMSC.';" />';
		} else {
			$RS_noimg = '';
		}
	}
	
	$char_RSMSC = $params->get('rsm_scroller_char', 300);
	$moredisplay_RSMSC = $params->get('rsm_scroller_moredisplay', 0);
	$morealign_RSMSC = $params->get('rsm_scroller_morealign', 'right');
	$moretext_RSMSC = $params->get('rsm_scroller_moretext', 'View More &gt;&gt;');
	$moreurl_RSMSC = $params->get('rsm_scroller_moreurl', '');

	$css_RSMSC = "#rsmsc_scroller { width: ".$width_RSMSC."px; height: ".$height_RSMSC."px; border: ".$border_thickness_RSMSC."px ".$border_style_RSMSC." ".$border_color_RSMSC."; padding: ".$padding_RSMSC."px; } .rsmsc_scroller_class{ ".$additional_RSMSC." }";
	$doc_RSMSC = JFactory::getDocument();
	$doc_RSMSC->addStyleDeclaration($css_RSMSC);
	
	$sql_RSMSC = '';
	if($ordering_RSMSC == 'random') {
		$sql_RSMSC .= " select * from `#__rsmonials` where `status`='1' order by RAND() ";
		if($load_RSMSC != '0') {
			$sql_RSMSC .= " limit ".$load_RSMSC." ";
		}
	} else {
		$sql_RSMSC .= " select * from `#__rsmonials` where `status`='1' order by `date` desc ";
		if($load_RSMSC != '0') {
			$sql_RSMSC .= " limit 0, ".$load_RSMSC." ";
		}
	}
	
	$db_RSMSC = JFactory::getDBO();
	$db_RSMSC->setQuery($sql_RSMSC);
	$testi_RSMSC = $db_RSMSC->loadAssocList();
	for($ik=0; $ik<count($testi_RSMSC); $ik++) {
		foreach($testi_RSMSC[$ik] as $key=>$value) {
			$testi_RSMSC[$ik][$key] = stripslashes($value);
		}
	}
?>
<script type="text/javascript">
<!--
var pausecontent=new Array();
var cnti = 0;

<?php
for($im=0; $im<count($testi_RSMSC); $im++) {
	$dateExp_RSMSC = explode('-', $testi_RSMSC[$im]['date']);
	$timestamp_RSMSC = mktime(12,0,0,$dateExp_RSMSC[1],$dateExp_RSMSC[2],$dateExp_RSMSC[0]);
	$dateConfig_RSMSC = JFactory::getConfig();
	$siteLang_RSMSC = $dateConfig_RSMSC->get('config.language');
	setlocale(LC_ALL, $siteLang_RSMSC);
	$dateView_RSMSC = strftime("%d %B %Y", $timestamp_RSMSC);
	$testi_RSMSC[$im]['comment'] = preg_replace('/\s\s+/', ' ', trim($testi_RSMSC[$im]['comment']));
	$testi_text = '';
	if($char_RSMSC > 0) {
		$testi_text .= substr($testi_RSMSC[$im]['comment'], 0, ($char_RSMSC-3)).'...';
	} else {
		$testi_text .= $testi_RSMSC[$im]['comment'];
	}
	####
	$RStesti_pic_file = '';
	if($imgDispRSMSC == '1') {
		if(file_exists(JPATH_ROOT.DS.'images'.DS.'com_rsmonials'.DS.$testi_RSMSC[$im]['id'].'.gif')) {
			$RStesti_pic_file = '<img src="'.JURI::root().'images/com_rsmonials/'.$testi_RSMSC[$im]['id'].'.gif" style="max-width:'.$imgMwRSMSC.'px; max-height:'.$imgMhRSMSC.'px; border:'.$imgBorderRSMSC.';" />';
		} else if(file_exists(JPATH_ROOT.DS.'images'.DS.'com_rsmonials'.DS.$testi_RSMSC[$im]['id'].'.png')) {
			$RStesti_pic_file = '<img src="'.JURI::root().'images/com_rsmonials/'.$testi_RSMSC[$im]['id'].'.png" style="max-width:'.$imgMwRSMSC.'px; max-height:'.$imgMhRSMSC.'px; border:'.$imgBorderRSMSC.';" />';
		} else if(file_exists(JPATH_ROOT.DS.'images'.DS.'com_rsmonials'.DS.$testi_RSMSC[$im]['id'].'.jpg')) {
			$RStesti_pic_file = '<img src="'.JURI::root().'images/com_rsmonials/'.$testi_RSMSC[$im]['id'].'.jpg" style="max-width:'.$imgMwRSMSC.'px; max-height:'.$imgMhRSMSC.'px; border:'.$imgBorderRSMSC.';" />';
		} else if(file_exists(JPATH_ROOT.DS.'images'.DS.'com_rsmonials'.DS.$testi_RSMSC[$im]['id'].'.jpeg')) {
			$RStesti_pic_file = '<img src="'.JURI::root().'images/com_rsmonials/'.$testi_RSMSC[$im]['id'].'.jpeg" style="max-width:'.$imgMwRSMSC.'px; max-height:'.$imgMhRSMSC.'px; border:'.$imgBorderRSMSC.';" />';
		} else {
			$RStesti_pic_file = $RS_noimg;
		}
		if($imgAlignRSMSC == '1') {
			$RStesti_pic_file = '<div style="margin-bottom:5px; text-align:left;">'.$RStesti_pic_file.'</div>';
		} else if($imgAlignRSMSC == '2') {
			$RStesti_pic_file = '<div style="margin-bottom:5px; text-align:right;">'.$RStesti_pic_file.'</div>';
		} else if($imgAlignRSMSC == '3') {
			$RStesti_pic_file = '<span style="float:left; margin-right:5px;">'.$RStesti_pic_file.'</span>';
		} else if($imgAlignRSMSC == '4') {
			$RStesti_pic_file = '<span style="float:right; margin-left:5px;">'.$RStesti_pic_file.'</span>';
		} else {
			$RStesti_pic_file = '<div style="margin-bottom:5px; text-align:center;">'.$RStesti_pic_file.'</div>';
		}
	}
	####
	$RSMSC_disp_context = '<div style="text-align:'.$alignRSMSC.';">'.$RStesti_pic_file.addslashes($testi_text).'</div><br /><em><strong>'.addslashes($testi_RSMSC[$im]['fname']).' '.addslashes($testi_RSMSC[$im]['lname']).'</strong>';
	if($displayaboutRSMSC == '1') {
		if(($testi_RSMSC[$im]['about'] != '') || ($testi_RSMSC[$im]['location'] != '')) {
			$RSMSC_disp_context .= '<br /><small>';
			$RS_isa = 0;
			if($testi_RSMSC[$im]['about'] != '') {
				$RSMSC_disp_context .= addslashes($testi_RSMSC[$im]['about']);
				$RS_isa = 1;
			}
			if($testi_RSMSC[$im]['location'] != '') {
				if($RS_isa == '1') {
					$RSMSC_disp_context .= ', ';
				}
				$RSMSC_disp_context .= addslashes($testi_RSMSC[$im]['location']);
			}
			$RSMSC_disp_context .= '</small>';
		}
	}
	if(($displayurlRSMSC == '1') && ($testi_RSMSC[$im]['website'] != '')) {
		$RSMSC_disp_context .= '<br /><small>'.$testi_RSMSC[$im]['website'].'</small>';
	}
	if($displaydateRSMSC == '1') {
		$RSMSC_disp_context .= '<br /><small>'.$dateView_RSMSC.'</small>';
	}
	$RSMSC_disp_context .= '</em>';
	
?>
pausecontent[cnti++]='<?php echo $RSMSC_disp_context; ?>';
<?php
}
?>

function pausescroller(content, divId, divClass, delay){
this.content=content //message array content
this.tickerid=divId //ID of ticker div to display information
this.delay=delay //Delay between msg change, in miliseconds.
this.mouseoverBol=0 //Boolean to indicate whether mouse is currently over scroller (and pause it if it is)
this.hiddendivpointer=1 //index of message array for hidden div
document.write('<div id="'+divId+'" class="'+divClass+'" style="position: relative; overflow: hidden"><div class="innerDiv" style="position: absolute; width: 100%" id="'+divId+'1">'+content[0]+'</div><div class="innerDiv" style="position: absolute; width: 100%; visibility: hidden" id="'+divId+'2">'+content[1]+'</div></div>')
var scrollerinstance=this
if (window.addEventListener) //run onload in DOM2 browsers
window.addEventListener("load", function(){scrollerinstance.initialize()}, false)
else if (window.attachEvent) //run onload in IE5.5+
window.attachEvent("onload", function(){scrollerinstance.initialize()})
else if (document.getElementById) //if legacy DOM browsers, just start scroller after 0.5 sec
setTimeout(function(){scrollerinstance.initialize()}, 500)
}

/* initialize()- Initialize scroller method. -Get div objects, set initial positions, start up down animation */

pausescroller.prototype.initialize=function(){
this.tickerdiv=document.getElementById(this.tickerid)
this.visiblediv=document.getElementById(this.tickerid+"1")
this.hiddendiv=document.getElementById(this.tickerid+"2")
this.visibledivtop=parseInt(pausescroller.getCSSpadding(this.tickerdiv))
//set width of inner DIVs to outer DIV's width minus padding (padding assumed to be top padding x 2)
this.visiblediv.style.width=this.hiddendiv.style.width=this.tickerdiv.offsetWidth-(this.visibledivtop*2)+"px"
this.getinline(this.visiblediv, this.hiddendiv)
this.hiddendiv.style.visibility="visible"
var scrollerinstance=this
document.getElementById(this.tickerid).onmouseover=function(){scrollerinstance.mouseoverBol=1}
document.getElementById(this.tickerid).onmouseout=function(){scrollerinstance.mouseoverBol=0}
if (window.attachEvent) //Clean up loose references in IE
window.attachEvent("onunload", function(){scrollerinstance.tickerdiv.onmouseover=scrollerinstance.tickerdiv.onmouseout=null})
setTimeout(function(){scrollerinstance.animateup()}, this.delay)
}


/* animateup()- Move the two inner divs of the scroller up and in sync */

pausescroller.prototype.animateup=function(){
var scrollerinstance=this
if (parseInt(this.hiddendiv.style.top)>(this.visibledivtop+5)){
this.visiblediv.style.top=parseInt(this.visiblediv.style.top)-5+"px"
this.hiddendiv.style.top=parseInt(this.hiddendiv.style.top)-5+"px"
setTimeout(function(){scrollerinstance.animateup()}, 50)
}
else{
this.getinline(this.hiddendiv, this.visiblediv)
this.swapdivs()
setTimeout(function(){scrollerinstance.setmessage()}, this.delay)
}
}

/* swapdivs()- Swap between which is the visible and which is the hidden div */

pausescroller.prototype.swapdivs=function(){
var tempcontainer=this.visiblediv
this.visiblediv=this.hiddendiv
this.hiddendiv=tempcontainer
}

pausescroller.prototype.getinline=function(div1, div2){
div1.style.top=this.visibledivtop+"px"
div2.style.top=Math.max(div1.parentNode.offsetHeight, div1.offsetHeight)+"px"
}

/* setmessage()- Populate the hidden div with the next message before it's visible */

pausescroller.prototype.setmessage=function(){
var scrollerinstance=this
if (this.mouseoverBol==1) //if mouse is currently over scoller, do nothing (pause it)
setTimeout(function(){scrollerinstance.setmessage()}, 100)
else{
var i=this.hiddendivpointer
var ceiling=this.content.length
this.hiddendivpointer=(i+1>ceiling-1)? 0 : i+1
this.hiddendiv.innerHTML=this.content[this.hiddendivpointer]
this.animateup()
}
}

pausescroller.getCSSpadding=function(tickerobj){ //get CSS padding value, if any
if (tickerobj.currentStyle)
return tickerobj.currentStyle["paddingTop"]
else if (window.getComputedStyle) //if DOM2
return window.getComputedStyle(tickerobj, "").getPropertyValue("padding-top")
else
return 0
}

//new pausescroller(name_of_message_array, CSS_ID, CSS_classname, pause_in_miliseconds)
new pausescroller(pausecontent, "rsmsc_scroller", "rsmsc_scroller_class", <?php echo $delay_RSMSC; ?>);
//-->
</script>
<?php
	if($moredisplay_RSMSC == '1') {
		echo '<div id="rsmsc" style="padding-top:5px;text-align:'.$morealign_RSMSC.';"><a href="'.$moreurl_RSMSC.'" title="'.$moretext_RSMSC.'">'.$moretext_RSMSC.'</a></div>';
	}
?>
<?php
}
else {
	$RSText = '<div style="color:red; padding: 0 5px;" id="rsm5">This module is exclusively designed to use with RSMonials component. To enable this module please download and install "RS-Monials" component from <a href="http://www.rswebsols.com" target="_blank" style="color:red; font-weight:bold;">here</a></div>';
	echo $RSText;
}
?>