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

function vsra_scroll() {
	crs_obj.scrollTop = crs_obj.scrollTop + 1;
	crs_scrollPos++;
	if ((crs_scrollPos%crs_heightOfElm) == 0) {
		crs_numScrolls--;
		if (crs_numScrolls == 0) {
			crs_obj.scrollTop = '0';
			vsra_content();
		} else {
			if (crs_scrollOn == 'true') {
				vsra_content();
			}
		}
	} else {
		setTimeout("vsra_scroll();", 10);
	}
}
var crs_Num = 0;
function vsra_content() {
	var tmp_vsrp = '';

	w_vsrp = crs_Num - parseInt(crs_numberOfElm);
	if (w_vsrp < 0) {
		w_vsrp = 0;
	} else {
		w_vsrp = w_vsrp%crs_array.length;
	}
	
	// Show amount of vsrru
	var elementsTmp_vsrp = parseInt(crs_numberOfElm) + 1;
	for (i_vsrp = 0; i_vsrp < elementsTmp_vsrp; i_vsrp++) {
		
		tmp_vsrp += crs_array[w_vsrp%crs_array.length];
		w_vsrp++;
	}

	crs_obj.innerHTML 	= tmp_vsrp;
	
	crs_Num 			= w_vsrp;
	crs_numScrolls 	= crs_array.length;
	crs_obj.scrollTop 	= '0';
	// start scrolling
	setTimeout("vsra_scroll();", 2000);
}