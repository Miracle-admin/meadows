<?php
/**
 * Vertical scroll recent article
 *
 * @package Vertical scroll recent article
 * @subpackage Vertical scroll recent article
 * @version   3.4
 * @author    Gopi Ramasamy
 * @copyright Copyright (C) 2010 - 2015 www.gopiplus.com, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */
 
// Lide Demo : http://www.gopiplus.com/extensions/2011/06/vertical-scroll-recent-article-joomla-module/
// Technical Support : http://www.gopiplus.com/extensions/2011/06/vertical-scroll-recent-article-joomla-module/

// no direct access
defined('_JEXEC') or die;

$vspost_height 		= (int) $params->get('vspost_height', 25);
$vspost_display 	= (int) $params->get('vspost_display', 5);
$vspost_no_of_chars = (int) $params->get('vspost_no_of_chars', 150);
if(!is_numeric($vspost_height)) { $vspost_height = 30; }
if(!is_numeric($vspost_display)) { $vspost_display = 5; }
if(!is_numeric($vspost_no_of_chars)) { $vspost_no_of_chars = 150; }
$vspost_count = 0;
$vspost_html = "";
$vspost_x = "";

if ( ! empty($items) ) 
{
	$vspost_count = 0;
	foreach ( $items as $item ) 
	{
		if($vspost_no_of_chars <> "" && $vspost_no_of_chars > 0 )
		{
			$vspost_title = substr($item->title, 0, $vspost_no_of_chars);
		}
		$vspost_title =  addslashes($vspost_title);
		$vspost_link = $item->links;	
		$dis_height = $vspost_height ."px";
		$vspost_html = $vspost_html . "<div class='crs_div' style='height:$dis_height;padding:2px 0px 2px 0px;'>"; 
		$vspost_html = $vspost_html . "<a href='$vspost_link'>$vspost_title</a>";
		$vspost_html = $vspost_html . "</div>";
		$vspost_x = $vspost_x . "crs_array[$vspost_count] = '<div class=\'crs_div\' style=\'height:$dis_height;padding:2px 0px 2px 0px;\'><a href=\'$vspost_link\'>$vspost_title</a></div>'; ";	
		$vspost_count++;
	}
}
$vspost_height = $vspost_height + 4;
if($vspost_count >= $vspost_display)
{
	$vspost_count = $vspost_display;
	$vspost_newheight = ($vspost_height  * $vspost_display);
}
else
{
	$vspost_count = $vspost_count;
	$vspost_newheight = ($vspost_count * $vspost_height );
}
$ivrss_height1 = $vspost_height ."px";
?>
<div>
<h3 >News Feed</h3>
  <div style="text-align:left;vertical-align:middle;text-decoration: none;overflow: hidden; position: relative; margin-left: 1px; height: <?php echo $ivrss_height1; ?>;" id="crs_Holder">
  <?php echo $vspost_html; ?>
  </div>
</div>
<script type="text/javascript">
var crs_array	= new Array();
var crs_obj	= '';
var crs_scrollPos 	= '';
var crs_numScrolls	= '';
var crs_heightOfElm = '<?php echo $vspost_height; ?>';
var crs_numberOfElm = '<?php echo $vspost_count; ?>';
var crs_scrollOn 	= 'true';
function vsra_createscroll() 
{
	<?php echo $vspost_x; ?>
	crs_obj	= document.getElementById('crs_Holder');
	crs_obj.style.height = (crs_numberOfElm * crs_heightOfElm) + 'px';
	vsra_content();
}
</script>
<script type="text/javascript">
vsra_createscroll();
</script>