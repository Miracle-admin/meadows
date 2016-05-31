/*------------------------------------------------------------------------
# mod_featcats - Featured Categories
# ------------------------------------------------------------------------
# author    Jesús Vargas Garita
# Copyright (C) 2010 www.joomlahill.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomlahill.com
# Technical Support:  Forum - http://www.joomlahill.com/forum
-------------------------------------------------------------------------*/

function fc_paginate(start,catid,mid) {

	var currentURL = window.location;
	var live_site = currentURL.protocol+'//'+currentURL.host+sfolder;
	
	var column = document.getElementById('fc_ajax-'+mid+'-'+catid);
	var items  = document.getElementById('fc_items-'+mid+'-'+catid);
	
	var height = items.offsetHeight-parseInt(getStyle(column,'padding-top'))-parseInt(getStyle(column,'padding-bottom'));
	var imgtop = height/2-33;
	items.style.height = height+'px';	
	items.style.opacity = .5;
	column.innerHTML=column.innerHTML+'<div class="fc_loading" style="line-height:'+height+'px"><img src="'+live_site+'/modules/mod_featcats/assets/loading.gif" border="0" style="margin-top:'+imgtop+'px" /></div>';
	
	var ajax = new XMLHttpRequest;
   	ajax.onreadystatechange=function()
  	{
		if (ajax.readyState==4 && ajax.status==200)
		{
			column.innerHTML = ajax.responseText;
		}
  	}	
	ajax.open("GET",live_site+"/modules/mod_featcats/assets/ajax.php?fc_start="+start+"&catid="+catid+"&mid="+mid,true);
	ajax.send();
	
}

function getStyle(el,styleProp)
{
	if (el.currentStyle)
		var y = el.currentStyle[styleProp];
	else if (window.getComputedStyle)
		var y = document.defaultView.getComputedStyle(el,null).getPropertyValue(styleProp);
	return y;
}