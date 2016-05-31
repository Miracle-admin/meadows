function loader(){
		//var msg = '<div id="loader" style="display:none"  class="main_loader_sec"><div class="loader_block">    <div class="loader_block_inner"></div>    <div class="loader_text">Please wait...</div>  </div></div>';
		var browser=navigator.appName;
		if (browser != "Microsoft Internet Explorer")
		{
			jQuery.blockUI({message: '<div id="loader" class="main_loader_sec"><div class="loader_block"><div class="loader_block_inner"></div><div class="loader_text">Please wait...</div></div></div>', css: {top:'40%', left:'45%', width: 'auto'}});
		}
		else
		{
			jQuery(document).ready(function() {
				var IEVersion = getIEVersionNumber();

				if(IEVersion > 7)
				{
					if(varFileNameJS != 'dashboard.php')
						jQuery.blockUI({message: '<div id="loader" class="main_loader_sec"><div class="loader_block"><div class="loader_block_inner"></div><div class="loader_text">Please wait...</div></div></div>',  centerY: true, centerX: true, css: {top:'40%', left:'45%'}});
				}
				/*else
				{
					$.blockUI({message: '<div id="loader" class="main_loader_sec"><div class="loader_block"><div class="loader_block_inner"></div><div class="loader_text">Please wait...</div></div></div>',  centerY: true, centerX: true, css: {top:'30%', left:''} });
				}*/
			});

		}

	    // setTimeout('test()', '7000');
	}

function popUpMessage(Message)
{
	var html = '<div id="pop_up_container_new"><div class="content_row1"><h3>Notification</h3></div><div class="content_area_outer"><div class="content_area"><div class="alertmeassage"><span>';

	var new_html = html + Message;

	new_html = new_html + '</span></div></div></div></div>';
	jQuery.blockUI({message: new_html, css: {top: '40%', left: '40%'}});
	setTimeout(jQuery.unblockUI, 1500); 
}

function popUpMessageHeading(Message,Heading)
{
	var html = '<div id="pop_up_container_new"><div class="content_row1"><h3>'+Heading+'</h3></div><div class="content_area_outer"><div class="content_area"><div class="alertmeassage">';
	var new_html = html + Message;
	new_html = new_html + '</div></div></div></div>';
	jQuery.blockUI({message: new_html, css: {top: '20%', left: '40%'}});
	setTimeout(jQuery.unblockUI, 1500); 
}

function removeRow(id){
	jQuery('#'+id).remove();
}

function closePopup()	{
	jQuery.unblockUI();
}


