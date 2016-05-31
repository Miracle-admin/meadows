<?php
$input = JFactory::getApplication()->input;
$view = $input->get('view'); 
?>

<script type="text/javascript">

var maxAttachments = '<?php echo isset($this->item->package)?$this->item->package->max_attachments :$this->appSettings->max_attachments ?>';

var view = '<?php echo $view ?>';

var picturesFolder = '<?php echo JURI::root().PICTURES_PATH ?>';
var checkedIcon = '<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/checked.gif"?>';
var uncheckedIcon = '<?php echo JURI::root()."administrator/components/".JBusinessUtil::getComponentName() ?>/assets/img/unchecked.gif';
var deleteIcon = '<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/del_options.gif"?>';
var upIcon = '<?php echo JURI::root()."administrator/components/".JBusinessUtil::getComponentName() ?>/assets/img/up-icon.png';
var downIcon = '<?php echo JURI::root()."administrator/components/".JBusinessUtil::getComponentName() ?>/assets/img/down-icon.png';

if(view == 'category') {
	var caregoryFolder = '<?php echo CATEGORY_PICTURES_PATH ?>';
	var caregoryFolderPath = '<?php echo JURI::root()?>components/<?php echo JBusinessUtil::getComponentName()?>/assets/upload.php?t=<?php echo strtotime("now")?>&_root_app=<?php echo urlencode(JPATH_ROOT."/".PICTURES_PATH)?>&_target=<?php echo urlencode(CATEGORY_PICTURES_PATH)?>';

	imageUploader(caregoryFolder, caregoryFolderPath);
	markerUploader(caregoryFolder, caregoryFolderPath);

	function removeImage() {
		jQuery("#imageLocation").val("");
		jQuery("#categoryImg").attr("src","");
	}

	function removeMarker() {
		jQuery("#markerLocation").val("");
		jQuery("#markerImg").attr("src","");
	}
}

if(view == 'applicationsettings') {
	var settingsFolder = '<?php echo COMPANY_PICTURES_PATH ?>';
	var settingsFolderPath = '<?php echo JURI::root()?>components/<?php echo JBusinessUtil::getComponentName()?>/assets/upload.php?t=<?php echo strtotime("now")?>&_root_app=<?php echo urlencode(JPATH_ROOT."/".PICTURES_PATH)?>&_target=<?php echo urlencode(COMPANY_PICTURES_PATH)?>';

	imageUploader(settingsFolder, settingsFolderPath);

	function removeImage() {
		jQuery("#imageLocation").val("");
		jQuery("#categoryImg").attr("src","");
	}
}


if(view == 'company' || view == 'managecompany') {
	var companyFolder = '<?php echo COMPANY_PICTURES_PATH.($this->item->id+0)."/" ?>';
	var companyFolderPath = '<?php echo JURI::root()?>components/<?php echo JBusinessUtil::getComponentName()?>/assets/upload.php?t=<?php echo strtotime("now")?>&picture_type=<?php echo PICTURE_TYPE_LOGO?>&_root_app=<?php echo urlencode(JPATH_ROOT."/".PICTURES_PATH) ?>&_target=<?php echo urlencode(COMPANY_PICTURES_PATH.($this->item->id+0)."/")?>';
	var companyAttachFolderPath = '<?php echo JURI::root()?>components/<?php echo JBusinessUtil::getComponentName()?>/assets/uploadFile.php?t=<?php echo strtotime("now")?>&_root_app=<?php echo urlencode(JPATH_ROOT."/".ATTACHMENT_PATH)?>&_target=<?php echo urlencode(COMPANY_PICTURES_PATH.((int)$this->item->id)."/")?>'
	var removePath = '<?php echo JURI::root()?>/components/<?php echo JBusinessUtil::getComponentName()?>/assets/remove.php?_root_app=<?php echo urlencode(JPATH_COMPONENT_SITE)?>&_filename=';
	
	imageUploader(companyFolder, companyFolderPath);
	multiImageUploader(companyFolder, companyFolderPath);
	multiFileUploader(companyFolder, companyAttachFolderPath);
	btn_removefile(removePath);
	btn_removefile_at(removePath);
}

if(view == 'offer' || view == 'managecompanyoffer') {
	var offerFolder = '<?php echo OFFER_PICTURES_PATH.((int)$this->item->id)."/"?>';
	var offerFolderPath = '<?php echo JURI::root()?>components/<?php echo JBusinessUtil::getComponentName()?>/assets/upload.php?t=<?php echo strtotime("now")?>&picture_type=<?php echo PICTURE_TYPE_OFFER?>&_root_app=<?php echo urlencode(JPATH_ROOT."/".PICTURES_PATH)?>&_target=<?php echo urlencode(OFFER_PICTURES_PATH.((int)$this->item->id)."/")?>';
	var offerAttachFolderPath = '<?php echo JURI::root()?>components/<?php echo JBusinessUtil::getComponentName()?>/assets/uploadFile.php?t=<?php echo strtotime("now")?>&_root_app=<?php echo urlencode(JPATH_ROOT."/".ATTACHMENT_PATH)?>&_target=<?php echo urlencode(OFFER_PICTURES_PATH.((int)$this->item->id)."/")?>'
	var removePath = '<?php echo JURI::root()?>/components/<?php echo JBusinessUtil::getComponentName()?>/assets/remove.php?_root_app=<?php echo urlencode(JPATH_COMPONENT_ADMINISTRATOR)?>&_filename=';
	var removePath_at = '<?php echo JURI::root()?>/components/<?php echo JBusinessUtil::getComponentName()?>/assets/remove.php?_root_app=<?php echo urlencode(JPATH_COMPONENT_SITE)?>&_filename=';

	multiOfferImageUploader(offerFolder, offerFolderPath);
	multiFileUploader(offerFolder, offerAttachFolderPath);
	btn_removefile(removePath);
	btn_removefile_at(removePath_at);
}

if(view == 'event' || view == 'managecompanyevent') {
	var eventFolder = '<?php echo EVENT_PICTURES_PATH.((int)$this->item->id)."/"?>';
	var eventFolderPath = '<?php echo JURI::root()?>components/<?php echo JBusinessUtil::getComponentName()?>/assets/upload.php?t=<?php echo strtotime("now")?>&picture_type=<?php echo PICTURE_TYPE_EVENT?>&_root_app=<?php echo urlencode(JPATH_ROOT."/".PICTURES_PATH)?>&_target=<?php echo urlencode(EVENT_PICTURES_PATH.((int)$this->item->id)."/")?>';
	var removePath = '<?php echo JURI::root()?>/components/<?php echo JBusinessUtil::getComponentName()?>/assets/remove.php?_root_app=<?php echo urlencode(JPATH_COMPONENT_ADMINISTRATOR)?>&_filename=';

	multiEventImageUploader(eventFolder, eventFolderPath);
	btn_removefile(removePath);
}


/* ======================================
   ============== GENERAL ===============
   ====================================== */
function imageUploader(folderID, folderIDPath) {
	jQuery("#imageUploader").change(function()  {
		jQuery("#remove-image-loading").remove();
		jQuery("#picture-preview").append('<p id="remove-image-loading" class="text-center"><span class="icon-refresh icon-refresh-animate"></span> Loading...</p>');
		jQuery("#item-form").validationEngine('detach');
		var fisRe = /^.+\.(jpg|bmp|gif|png|jpeg|PNG|JPG|GIF|JPEG)$/i;
		var path = jQuery(this).val();
		if (path.search(fisRe) == -1) {
			jQuery("#remove-image-loading").remove();
			alert('JPG, JPEG, BMP, GIF, PNG only!');
			return false;
		}
		jQuery(this).upload(folderIDPath, function(responce)  {
			if( responce == '' ) {
				jQuery("#remove-image-loading").remove();
				alert("<?php echo JText::_('LNG_ERROR_ADDING_FILE',true)?>");
				jQuery(this).val('');
			}
			else {
				var xml = responce;
				jQuery(xml).find("picture").each(function() {
					if(jQuery(this).attr("error") == 0 ) {
						if(view == 'company' || view == 'managecompany') {
							setUpCompanyImage(
								folderID + jQuery(this).attr("path"),
								jQuery(this).attr("name")
							);
							jQuery("#remove-image-loading").remove();
						}
						if(view == 'category') {
							setUpCategoryImage(
								folderID + jQuery(this).attr("path"),
								jQuery(this).attr("name")
							);
							jQuery("#remove-image-loading").remove();
						}

						if(view == 'applicationsettings') {
							setUpCategoryImage(
								folderID + jQuery(this).attr("path"),
								jQuery(this).attr("name")
							);
							jQuery("#remove-image-loading").remove();
						}
					}
					else if( jQuery(this).attr("error") == 1 )
						alert("<?php echo JText::_('LNG_FILE_ALLREADY_ADDED',true)?>");
					else if( jQuery(this).attr("error") == 2 )
						alert("<?php echo JText::_('LNG_ERROR_ADDING_FILE',true)?>");
					else if( jQuery(this).attr("error") == 3 )
						alert("<?php echo JText::_('LNG_ERROR_GD_LIBRARY',true)?>");
					else if( jQuery(this).attr("error") == 4 )
						alert("<?php echo JText::_('LNG_ERROR_RESIZING_FILE',true)?>");
				});
			}
		});
		jQuery("#item-form").validationEngine('attach');
	});
}

function multiFileUploader(folderID, folderIDPath) {
	jQuery("#multiFileUploader").change(function() {
		jQuery("#remove-file-loading").remove();
		if(view == 'company' || view == 'managecompany')
			jQuery("#table_company_attachments").append('<p id="remove-file-loading" class="text-center"><span class="icon-refresh icon-refresh-animate"></span> Loading...</p>');
		if(view == 'offer' || view == 'managecompanyoffer')
			jQuery("#table_offer_attachments").append('<p id="remove-file-loading" class="text-center"><span class="icon-refresh icon-refresh-animate"></span> Loading...</p>');
		jQuery("#item-form").validationEngine('detach');
		var path = jQuery(this).val();
		jQuery(this).upload(folderIDPath, function(responce) {
			if( responce =='' ) {
				jQuery("#remove-file-loading").remove();
				alert("<?php echo JText::_('LNG_ERROR_ADDING_FILE',true)?>");
				jQuery(this).val('');
			}
			else {
				var xml = responce;
				jQuery(xml).find("attachment").each(function() {
					if(jQuery(this).attr("error") == 0 ) {
						if(view == 'company' || view == 'managecompany') {
							if(jQuery("#table_company_attachments tr").length < maxAttachments) {
								addCompanyAttachment(
									folderID + jQuery(this).attr("path"),
									jQuery(this).attr("name")
								);
							} else {
								alert("<?php echo JText::_('LNG_MAX_ATTACHMENTS_ALLOWED',true)?>"+maxAttachments);
							}
							jQuery("#remove-file-loading").remove();
						}
						if(view == 'offer' || view == 'managecompanyoffer') {
							if(jQuery("#table_offer_attachments tr").length < maxAttachments) {
								addOfferAttachment(
									folderID + jQuery(this).attr("path"),
									jQuery(this).attr("name")
								);
							} else {
								alert("<?php echo JText::_('LNG_MAX_ATTACHMENTS_ALLOWED',true)?>"+maxAttachments);
							}
							jQuery("#remove-file-loading").remove();
						}
					}
					else if( jQuery(this).attr("error") == 1 )
						alert("<?php echo JText::_('LNG_FILE_ALLREADY_ADDED',true)?>");
					else if( jQuery(this).attr("error") == 2 )
						alert("<?php echo JText::_('LNG_ERROR_ADDING_FILE',true)?>");
					else if( jQuery(this).attr("error") == 3 )
						alert("<?php echo JText::_('LNG_ERROR_GD_LIBRARY',true)?>");
					else if( jQuery(this).attr("error") == 4 )
						alert("<?php echo JText::_('LNG_ERROR_RESIZING_FILE',true)?>");
				});
			}
		}, 'html');
		jQuery("#item-form").validationEngine('attach');
	});
}

function btn_removefile(removePath) {
	jQuery('#btn_removefile').click(function() {
		pos = jQuery('#crt_pos').val();
		path = jQuery('#crt_path').val();
		jQuery( this ).upload(removePath + path + '&_pos='+pos, function(responce) {
			if( responce =='' ) {
				alert("<?php echo JText::_('LNG_ERROR_REMOVING_FILE',true)?>");
				jQuery(this).val('');
			}
			else {
				var xml = responce;
				jQuery(xml).find("picture").each(function() {
					if(jQuery(this).attr("error") == 0 ) {
						removePicture( jQuery(this).attr("pos") );
					}
					else if( jQuery(this).attr("error") == 2 )
						alert("<?php echo JText::_('LNG_ERROR_REMOVING_FILE',true)?>");
					else if( jQuery(this).attr("error") == 3 )
						alert("<?php echo JText::_('LNG_FILE_DOESNT_EXIST',true)?>");
				});
				jQuery('#crt_pos').val('');
				jQuery('#crt_path').val('');
			}
		}, 'html');
		jQuery("#item-form").validationEngine('detach');
	});
}

function btn_removefile_at(removePath_at) {
	jQuery('#btn_removefile_at').click(function() {
		jQuery("#item-form").validationEngine('detach');
		pos = jQuery('#crt_pos_a').val();
		path = jQuery('#crt_path_a').val();
		jQuery(this).upload(removePath_at + path + '&_pos='+pos, function(responce) {
			if( responce =='' ) {
				alert("<?php echo JText::_('LNG_ERROR_REMOVING_FILE',true)?>");
				jQuery(this).val('');
			}
			else {
				var xml = responce;
				jQuery(xml).find("picture").each(function() {
					if(jQuery(this).attr("error") == 0 ) {
						removeAttachment( jQuery(this).attr("pos") );
					}
					else if( jQuery(this).attr("error") == 2 )
						alert("<?php echo JText::_('LNG_ERROR_REMOVING_FILE',true)?>");
					else if( jQuery(this).attr("error") == 3 )
						alert("<?php echo JText::_('LNG_FILE_DOESNT_EXIST',true)?>");
				});
				jQuery('#crt_pos_a').val('');
				jQuery('#crt_path_a').val('');
			}
		}, 'html');
		jQuery("#item-form").validationEngine('detach');
	});
}

function removeAttachment(pos) {
	if(view == 'offer' || view == 'managecompanyoffer') var tb = document.getElementById('table_offer_attachments');
	if(view == 'company' || view == 'managecompany') var tb = document.getElementById('table_company_attachments');

	if( tb==null ) {
		alert('Undefined table, contact administrator !');
	}

	if( pos >= tb.rows.length )
		pos = tb.rows.length-1;
	tb.deleteRow( pos );
}

function removePicture(pos) {
	if(view == 'offer' || view == 'managecompanyoffer') var tb = document.getElementById('table_offer_pictures');
	if(view == 'company' || view == 'managecompany') var tb = document.getElementById('table_company_pictures');
	if(view == 'event' || view == 'managecompanyevent') var tb = document.getElementById('table_event_pictures');

	if( tb==null ) {
		alert('Undefined table, contact administrator !');
	}

	if( pos >= tb.rows.length )
		pos = tb.rows.length-1;
	tb.deleteRow( pos );

	if(view == 'company') {
		jQuery("#company-deleted").val(1);
		checkNumberOfPictures();
	}
}

function removeRow(id) {
	jQuery('#'+id).remove();
}


/* ======================================
   ============= COMPANY ================
   ====================================== */
function setUpCompanyImage(path, name) {
	jQuery("#imageLocation").val(path);
	var img_new	= document.createElement('img');
	img_new.setAttribute('src', picturesFolder + path );
	img_new.setAttribute('class', 'company-logo');
	img_new.setAttribute('alt', "<?php echo JText::_('LNG_COMPANY_LOGO',true) ?>");
	img_new.setAttribute('title', "<?php echo JText::_('LNG_COMPANY_LOGO',true) ?>");
	jQuery("#picture-preview").empty();
	jQuery("#picture-preview").append(img_new);
}

function multiImageUploader(companyFolder, companyFolderPath) {
	jQuery("#multiImageUploader").change(function() {
		jQuery("#remove-image-loading").remove();
		jQuery("#table_company_pictures").append('<p id="remove-image-loading" class="text-center"><span class="icon-refresh icon-refresh-animate"></span>Loading...</p>');
		jQuery("#item-form").validationEngine('detach');
		var fisRe = /^.+\.(jpg|bmp|gif|png|jpeg|PNG|JPG|GIF|JPEG)$/i;
		var path = jQuery(this).val();
		
		if (path.search(fisRe) == -1) {
			jQuery("#remove-image-loading").remove();
			alert(' JPG, JPEG, BMP, GIF, PNG only!');
			return false;
		}	
		jQuery(this).upload(companyFolderPath, function(responce) {
			if( responce =='' ) {
				jQuery("#remove-image-loading").remove();
				alert("<?php echo JText::_('LNG_ERROR_ADDING_FILE',true)?>");
				jQuery(this).val('');
			}
			else {
				var xml = responce;
				jQuery(xml).find("picture").each(function() {
					if(jQuery(this).attr("error") == 0 ) {
						addCompanyPicture(
							companyFolder + jQuery(this).attr("path"),
							jQuery(this).attr("name")
						);
						jQuery("#remove-image-loading").remove();
					}
					else if( jQuery(this).attr("error") == 1 )
						alert("<?php echo JText::_('LNG_FILE_ALLREADY_ADDED',true)?>");
					else if( jQuery(this).attr("error") == 2 )
						alert("<?php echo JText::_('LNG_ERROR_ADDING_FILE',true)?>");
					else if( jQuery(this).attr("error") == 3 )
						alert("<?php echo JText::_('LNG_ERROR_GD_LIBRARY',true)?>");
					else if( jQuery(this).attr("error") == 4 )
						alert("<?php echo JText::_('LNG_ERROR_RESIZING_FILE',true)?>");
				});
				jQuery(this).val('');
			}
		}, 'html');
		jQuery("#item-form").validationEngine('attach');
	});
}

function addCompanyPicture(path, name) {
	var tb = document.getElementById('table_company_pictures');
	if( tb==null ) {
		alert('Undefined table, contact administrator !');
	}
	var td1_new	= document.createElement('td');  
	td1_new.style.textAlign = 'left';
	var textarea_new = document.createElement('textarea');
	textarea_new.setAttribute("name","company_picture_info[]");
	textarea_new.setAttribute("id","company_picture_info");
	textarea_new.setAttribute("cols","50");
	textarea_new.setAttribute("rows","2");
	td1_new.appendChild(textarea_new);
	
	var td2_new	= document.createElement('td');  
	td2_new.style.textAlign='center';
	var img_new	= document.createElement('img');
	img_new.setAttribute('src', picturesFolder + path );
	img_new.setAttribute('class', 'img_picture_company');
	td2_new.appendChild(img_new);
	var span_new = document.createElement('span');
	span_new.innerHTML = "<BR>"+name;
	td2_new.appendChild(span_new);
	
	var input_new_1 = document.createElement('input');
	input_new_1.setAttribute('type', 'hidden');
	input_new_1.setAttribute('name', 'company_picture_enable[]');
	input_new_1.setAttribute('id', 'company_picture_enable[]');
	input_new_1.setAttribute('value', '1');
	td2_new.appendChild(input_new_1);
	
	var input_new_2	= document.createElement('input');
	input_new_2.setAttribute('type', 'hidden');
	input_new_2.setAttribute('name', 'company_picture_path[]');
	input_new_2.setAttribute('id', 'company_picture_path[]');
	input_new_2.setAttribute('value', path);
	td2_new.appendChild(input_new_2);
	
	var td3_new	= document.createElement('td');  
	td3_new.style.textAlign='center';
	
	var img_del	= document.createElement('img');
	img_del.setAttribute('src', deleteIcon);
	img_del.setAttribute('class', 'btn_picture_delete');
	img_del.setAttribute('id', 	tb.rows.length);
	img_del.setAttribute('name', 'del_img_' + tb.rows.length);
	img_del.onmouseover = function(){ this.style.cursor='hand';this.style.cursor='pointer' };
	img_del.onmouseout = function(){ this.style.cursor='default' };
	img_del.onclick = function() { 
		if( !confirm('<?php echo JText::_("LNG_CONFIRM_DELETE_PICTURE",true)?>' )) 
			return; 					
		var row = jQuery(this).parents('tr:first');
		var row_idx = row.prevAll().length;
		jQuery('#crt_pos').val(row_idx);
		jQuery('#crt_path').val( path );
		jQuery('#btn_removefile').click();
	};
		
	td3_new.appendChild(img_del);
	
	var td4_new	= document.createElement('td');  
	td4_new.style.textAlign='center';
	var img_enable = document.createElement('img');
	img_enable.setAttribute('src', checkedIcon);
	img_enable.setAttribute('class', 'btn_picture_status');
	img_enable.setAttribute('id', 	tb.rows.length);
	img_enable.setAttribute('name', 'enable_img_' + tb.rows.length);
	
	img_enable.onclick = function() { 
		var form = document.adminForm;
		var v_status = null; 
		if( form.elements['company_picture_enable[]'].length == null ) {
			v_status  = form.elements['company_picture_enable[]'];
		}
		else {
			var pos = jQuery(this).closest('tr')[0].sectionRowIndex;
			var tb = document.getElementById('table_company_pictures');
			if( pos >= tb.rows.length )
				pos = tb.rows.length-1;
			v_status  = form.elements['company_picture_enable[]'][ pos ];
		}
		if(v_status.value=='1') {
			jQuery(this).attr('src', uncheckedIcon);
			v_status.value ='0';
		}
		else {
			jQuery(this).attr('src', checkedIcon);
			v_status.value ='1';
		}
	};
	td4_new.appendChild(img_enable);
	
	var td5_new = document.createElement('td');  
	td5_new.style.textAlign ='center';
			
	td5_new.innerHTML = '<span class=\'span_up\' onclick=\'var row = jQuery(this).parents("tr:first");  row.insertBefore(row.prev());\'><img src="' + upIcon + '"></span>'+
						'<span class=\'span_down\' onclick=\'var row = jQuery(this).parents("tr:first"); row.insertAfter(row.next());\'><img src="' + downIcon + '"></span>';
	
	var tr_new = tb.insertRow(tb.rows.length);
	
	tr_new.appendChild(td1_new);
	tr_new.appendChild(td2_new);
	tr_new.appendChild(td3_new);
	tr_new.appendChild(td4_new);
	tr_new.appendChild(td5_new);
	checkNumberOfPictures();
}

function addCompanyAttachment(path, name) {
	var tb = document.getElementById('table_company_attachments');
	if( tb==null ) {
		alert('Undefined table, contact administrator !');
	}
	
	var td1_new = document.createElement('td');  
	td1_new.style.textAlign = 'left';
	var input_new = document.createElement('input');
	input_new.setAttribute("name","attachment_name[]");
	input_new.setAttribute("id","attachment_name");
	input_new.setAttribute("type","text");
	td1_new.appendChild(input_new);

	var span_new = document.createElement('span');
	span_new.innerHTML = "<BR>"+name;
	td1_new.appendChild(span_new);
	
	var input_new_1 = document.createElement('input');
	input_new_1.setAttribute('type', 'hidden');
	input_new_1.setAttribute('name', 'attachment_status[]');
	input_new_1.setAttribute('id', 'attachment_status');
	input_new_1.setAttribute('value', '1');
	td1_new.appendChild(input_new_1);

	var input_new_2 = document.createElement('input');
	input_new_2.setAttribute('type', 'hidden');
	input_new_2.setAttribute('name', 'attachment_path[]');
	input_new_2.setAttribute('id', 'attachment_path');
	input_new_2.setAttribute('value', path);
	td1_new.appendChild(input_new_2);

	var td3_new = document.createElement('td');  
	td3_new.style.textAlign = 'center';

	var img_del = document.createElement('img');
	img_del.setAttribute('src', deleteIcon);
	img_del.setAttribute('class', 'btn_attachment_delete');
	img_del.setAttribute('id', 	tb.rows.length);
	img_del.setAttribute('name', 'del_attachment_' + tb.rows.length);
	img_del.onmouseover = function() { this.style.cursor='hand';this.style.cursor='pointer' };
	img_del.onmouseout = function() { this.style.cursor='default' };
	img_del.onclick = function() {
		if( !confirm('<?php echo JText::_("LNG_CONFIRM_DELETE_ATTACHMENT", true)?>' ))
			return;
		var row = jQuery(this).parents('tr:first');
		var row_idx = row.prevAll().length;
		jQuery('#crt_pos_a').val(row_idx);
		jQuery('#crt_path_a').val( path );
		jQuery('#btn_removefile_at').click();
	};
		
	td3_new.appendChild(img_del);
	
	var td4_new	= document.createElement('td');  
	td4_new.style.textAlign = 'center';
	var img_enable = document.createElement('img');
	img_enable.setAttribute('src', checkedIcon);
	img_enable.setAttribute('class', 'btn_attachment_status');
	img_enable.setAttribute('id', tb.rows.length);
	img_enable.setAttribute('name', 'enable_img_' + tb.rows.length);

	img_enable.onclick = function() {
		var form = document.adminForm;
		var v_status = null;
		if( form.elements['attachment_status[]'].length == null ) {
			v_status  = form.elements['attachment_status[]'];
		}
		else {
			var pos = jQuery(this).closest('tr')[0].sectionRowIndex;
			var tb = document.getElementById('table_company_attachments');
			if( pos >= tb.rows.length )
				pos = tb.rows.length-1;
			v_status  = form.elements['attachment_status[]'][ pos ];
		}
		
		if(v_status.value=='1') {
			jQuery(this).attr('src', uncheckedIcon);
			v_status.value ='0';
		}
		else {
			jQuery(this).attr('src', checkedIcon);
			v_status.value ='1';
		}
	};

	td4_new.appendChild(img_enable);

	var td5_new = document.createElement('td');  
	td5_new.style.textAlign = 'center';

	td5_new.innerHTML = '<span class=\'span_up\' onclick=\'var row = jQuery(this).parents("tr:first");  row.insertBefore(row.prev());\'><img src="' + upIcon + '"></span>'+
						'<span class=\'span_down\' onclick=\'var row = jQuery(this).parents("tr:first"); row.insertAfter(row.next());\'><img src="' + downIcon + '"></span>';

	var tr_new = tb.insertRow(tb.rows.length);

	tr_new.appendChild(td1_new);
	tr_new.appendChild(td3_new);
	tr_new.appendChild(td4_new);
	tr_new.appendChild(td5_new);
}

function checkNumberOfPictures() {
	var nrPictures = jQuery('textarea[name*="company_picture_info[]"]').length;
	if(nrPictures <maxPictures) {
		jQuery("#add-pictures").show();
	}
	else {
		jQuery("#add-pictures").hide();
	}
}

function removeLogo() {
	jQuery("#imageLocation").val("");
	jQuery("#picture-preview").html("");
	jQuery("#imageUploader").val("");
}


/* ======================================
   ============= CATEGORY ===============
   ====================================== */
function setUpCategoryImage(path, name) {
	jQuery("#imageLocation").val(path);
	var img_new	= document.createElement('img');
	img_new.setAttribute('src', picturesFolder + path );
	img_new.setAttribute('id', 'categoryImg');
	img_new.setAttribute('class', 'category-image');
	jQuery("#picture-preview").empty();
	jQuery("#picture-preview").append(img_new);
}

function markerUploader(folderID, folderIDPath) {
	jQuery("#markerfile").change(function() {
		jQuery("#remove-image-loading").remove();
		jQuery("#marker-preview").append('<p id="remove-image-loading" class="text-center"><span class="icon-refresh icon-refresh-animate"></span></p>');
		var fisRe 	= /^.+\.(jpg|bmp|gif|png)$/i;
		var path = jQuery(this).val();
		if (path.search(fisRe) == -1) {
			jQuery("#remove-image-loading").remove();
			alert(' JPG, BMP, GIF, PNG only!');
			return false;
		}
		jQuery(this).upload(folderIDPath, function(responce) {
			if( responce == '' ) {
				jQuery("#remove-image-loading").remove();
				alert("<?php echo JText::_('LNG_ERROR_ADDING_FILE',true)?>");
				jQuery(this).val('');
			}
			else {
				var xml = responce;
				jQuery(xml).find("picture").each(function() {
					if(jQuery(this).attr("error") == 0 ) {
						setUpMarker(
							folderID + jQuery(this).attr("path"),
							jQuery(this).attr("name")
						);
						jQuery("#remove-image-loading").remove();
					}
					else if( jQuery(this).attr("error") == 1 )
						alert("<?php echo JText::_('LNG_FILE_ALLREADY_ADDED',true)?>");
					else if( jQuery(this).attr("error") == 2 )
						alert("<?php echo JText::_('LNG_ERROR_ADDING_FILE',true)?>");
					else if( jQuery(this).attr("error") == 3 )
						alert("<?php echo JText::_('LNG_ERROR_GD_LIBRARY',true)?>");
					else if( jQuery(this).attr("error") == 4 )
						alert("<?php echo JText::_('LNG_ERROR_RESIZING_FILE',true)?>");
				});
			}
		});
		jQuery("#item-form").validationEngine('attach');
	});
}

function setUpMarker(path, name) {
	jQuery("#markerLocation").val(path);
	var img_new	= document.createElement('img');
	img_new.setAttribute('src', picturesFolder + path );
	img_new.setAttribute('id', 'markerImg');
	img_new.setAttribute('class', 'category-image');
	jQuery("#marker-preview").empty();
	jQuery("#marker-preview").append(img_new);
}


/* ======================================
   =============== OFFER ================
   ====================================== */
function multiOfferImageUploader(offerFolder, offerFolderPath) {
	jQuery("#multiImageUploader").change(function() {
		jQuery("#remove-image-loading").remove();
		jQuery("#table_offer_pictures").append('<p id="remove-image-loading" class="text-center"><span class="icon-refresh icon-refresh-animate"></span>Loading...</p>');
		jQuery("#item-form").validationEngine('detach');
		var fisRe = /^.+\.(jpg|bmp|gif|png|jpeg|PNG|JPG|GIF|JPEG)$/i;
		var path = jQuery(this).val();
		
		if (path.search(fisRe) == -1) {
			jQuery("#remove-image-loading").remove();
			alert(' JPG, JPEG, BMP, GIF, PNG only!');
			return false;
		}	
		jQuery(this).upload(offerFolderPath, function(responce) {
			if( responce =='' ) {
				jQuery("#remove-image-loading").remove();
				alert("<?php echo JText::_('LNG_ERROR_ADDING_FILE',true)?>");
				jQuery(this).val('');
			}
			else {
				var xml = responce;
				jQuery(xml).find("picture").each(function() {
					if(jQuery(this).attr("error") == 0 ) {
						addOfferPicture(
							offerFolder + jQuery(this).attr("path"),
							jQuery(this).attr("name")
						);
						jQuery("#remove-image-loading").remove();
					}
					else if( jQuery(this).attr("error") == 1 )
						alert("<?php echo JText::_('LNG_FILE_ALLREADY_ADDED',true)?>");
					else if( jQuery(this).attr("error") == 2 )
						alert("<?php echo JText::_('LNG_ERROR_ADDING_FILE',true)?>");
					else if( jQuery(this).attr("error") == 3 )
						alert("<?php echo JText::_('LNG_ERROR_GD_LIBRARY',true)?>");
					else if( jQuery(this).attr("error") == 4 )
						alert("<?php echo JText::_('LNG_ERROR_RESIZING_FILE',true)?>");
				});
				jQuery(this).val('');
			}
		}, 'html');
		jQuery("#item-form").validationEngine('attach');
	});
}

function addOfferPicture(path, name) {
	var tb = document.getElementById('table_offer_pictures');
	if( tb==null ) {
		alert('Undefined table, contact administrator !');
	}
	
	var td1_new	= document.createElement('td');  
	td1_new.style.textAlign='left';
	var textarea_new = document.createElement('textarea');
	textarea_new.setAttribute("name","offer_picture_info[]");
	textarea_new.setAttribute("id","offer_picture_info");
	textarea_new.setAttribute("cols","50");
	textarea_new.setAttribute("rows","2");
	td1_new.appendChild(textarea_new);
	
	var td2_new	= document.createElement('td');  
	td2_new.style.textAlign='center';
	var img_new = document.createElement('img');
	img_new.setAttribute('src', picturesFolder + path );
	img_new.setAttribute('class', 'img_picture_offer');
	td2_new.appendChild(img_new);
	var span_new = document.createElement('span');
	span_new.innerHTML = "<BR>"+name;
	td2_new.appendChild(span_new);
	
	var input_new_1 = document.createElement('input');
	input_new_1.setAttribute('type', 'hidden');
	input_new_1.setAttribute('name', 'offer_picture_enable[]');
	input_new_1.setAttribute('id', 'offer_picture_enable[]');
	input_new_1.setAttribute('value', '1');
	td2_new.appendChild(input_new_1);
	
	var input_new_2	= document.createElement('input');
	input_new_2.setAttribute('type', 'hidden');
	input_new_2.setAttribute('name', 'offer_picture_path[]');
	input_new_2.setAttribute('id', 'offer_picture_path[]');
	input_new_2.setAttribute('value', path);
	td2_new.appendChild(input_new_2);
	
	var td3_new	= document.createElement('td');  
	td3_new.style.textAlign = 'center';
	
	var img_del	= document.createElement('img');
	img_del.setAttribute('src', deleteIcon);
	img_del.setAttribute('class', 'btn_picture_delete');
	img_del.setAttribute('id', 	tb.rows.length);
	img_del.setAttribute('name', 'del_img_' + tb.rows.length);
	img_del.onmouseover = function(){ this.style.cursor='hand';this.style.cursor='pointer' };
	img_del.onmouseout = function(){ this.style.cursor='default' };
	img_del.onclick = function() {
		if( !confirm("<?php echo JText::_('LNG_CONFIRM_DELETE_PICTURE',true)?>" ))
			return;
		var row = jQuery(this).parents('tr:first');
		var row_idx = row.prevAll().length;
		jQuery('#crt_pos').val(row_idx);
		jQuery('#crt_path').val( path );
		jQuery('#btn_removefile').click();
	};
		
	td3_new.appendChild(img_del);
	
	var td4_new	= document.createElement('td');  
	td4_new.style.textAlign='center';
	var img_enable = document.createElement('img');
	img_enable.setAttribute('src', checkedIcon);
	img_enable.setAttribute('class', 'btn_picture_status');
	img_enable.setAttribute('id', tb.rows.length);
	img_enable.setAttribute('name', 'enable_img_' + tb.rows.length);

	img_enable.onclick = function() {
		var form = document.adminForm;
		var v_status = null;
		if( form.elements['offer_picture_enable[]'].length == null ) {
			v_status  = form.elements['offer_picture_enable[]'];
		}
		else {
			var pos = jQuery(this).closest('tr')[0].sectionRowIndex;
			var tb = document.getElementById('table_offer_pictures');
			if( pos >= tb.rows.length )
				pos = tb.rows.length-1;
			v_status  = form.elements['offer_picture_enable[]'][ pos ];
		}
		if(v_status.value=='1') {
			jQuery(this).attr('src', uncheckedIcon);
			v_status.value ='0';
		}
		else {
			jQuery(this).attr('src', checkedIcon);
			v_status.value ='1';
		}
	};

	td4_new.appendChild(img_enable);
	
	var td5_new	= document.createElement('td');  
	td5_new.style.textAlign = 'center';
			
	td5_new.innerHTML = '<span class=\'span_up\' onclick=\'var row = jQuery(this).parents("tr:first");  row.insertBefore(row.prev());\'><img src="' + upIcon + '"></span>'+
						'<span class=\'span_down\' onclick=\'var row = jQuery(this).parents("tr:first"); row.insertAfter(row.next());\'><img src="' + downIcon + '"></span>';
	
	var tr_new = tb.insertRow(tb.rows.length);
	
	tr_new.appendChild(td1_new);
	tr_new.appendChild(td2_new);
	tr_new.appendChild(td3_new);
	tr_new.appendChild(td4_new);
	tr_new.appendChild(td5_new);
}

function addOfferAttachment(path, name) {
	var tb = document.getElementById('table_offer_attachments');
	if( tb==null ) {
		alert('Undefined table, contact administrator !');
	}
	
	var td1_new	= document.createElement('td');  
	td1_new.style.textAlign = 'left';
	var input_new = document.createElement('input');
	input_new.setAttribute("name","attachment_name[]");
	input_new.setAttribute("id","attachment_name");
	input_new.setAttribute("type","text");
	td1_new.appendChild(input_new);

	var span_new = document.createElement('span');
	span_new.innerHTML = "<BR>"+name;
	td1_new.appendChild(span_new);
	
	var input_new_1 = document.createElement('input');
	input_new_1.setAttribute('type', 'hidden');
	input_new_1.setAttribute('name', 'attachment_status[]');
	input_new_1.setAttribute('id', 'attachment_status');
	input_new_1.setAttribute('value', '1');
	td1_new.appendChild(input_new_1);

	var input_new_2 = document.createElement('input');
	input_new_2.setAttribute('type', 'hidden');
	input_new_2.setAttribute('name', 'attachment_path[]');
	input_new_2.setAttribute('id', 'attachment_path');
	input_new_2.setAttribute('value', path);
	td1_new.appendChild(input_new_2);
	
	var td3_new	= document.createElement('td');  
	td3_new.style.textAlign = 'center';
	
	var img_del	= document.createElement('img');
	img_del.setAttribute('src', deleteIcon);
	img_del.setAttribute('class', 'btn_attachment_delete');
	img_del.setAttribute('id', 	tb.rows.length);
	img_del.setAttribute('name', 'del_attachment_' + tb.rows.length);
	img_del.onmouseover = function() { this.style.cursor='hand';this.style.cursor='pointer' };
	img_del.onmouseout = function() { this.style.cursor='default' };
	img_del.onclick = function() { 
		if( !confirm('<?php echo JText::_("LNG_CONFIRM_DELETE_ATTACHMENT",true)?>' )) 
			return; 
		var row = jQuery(this).parents('tr:first');
		var row_idx = row.prevAll().length;
		jQuery('#crt_pos_a').val(row_idx);
		jQuery('#crt_path_a').val( path );
		jQuery('#btn_removefile_at').click();
	};

	td3_new.appendChild(img_del);

	var td4_new	= document.createElement('td');  
	td4_new.style.textAlign='center';
	var img_enable = document.createElement('img');
	img_enable.setAttribute('src', checkedIcon);
	img_enable.setAttribute('class', 'btn_attachment_status');
	img_enable.setAttribute('id', 	tb.rows.length);
	img_enable.setAttribute('name', 'enable_img_' + tb.rows.length);

	img_enable.onclick = function() { 
		var form = document.adminForm;
		var v_status = null; 
		if( form.elements['attachment_status[]'].length == null ) {
			v_status  = form.elements['attachment_status[]'];
		}
		else {
			var pos = jQuery(this).closest('tr')[0].sectionRowIndex;
			var tb = document.getElementById('table_offer_attachments');
			if( pos >= tb.rows.length )
				pos = tb.rows.length-1;
			v_status  = form.elements['attachment_status[]'][pos];
		}

		if(v_status.value=='1') {
			jQuery(this).attr('src', uncheckedIcon);
			v_status.value ='0';
		}
		else {
			jQuery(this).attr('src', checkedIcon);
			v_status.value ='1';
		}
	};

	td4_new.appendChild(img_enable);
	var td5_new	= document.createElement('td');  
	td5_new.style.textAlign = 'center';
			
	td5_new.innerHTML = '<span class=\'span_up\' onclick=\'var row = jQuery(this).parents("tr:first");  row.insertBefore(row.prev());\'><img src="' + upIcon + '"></span>'+
						'<span class=\'span_down\' onclick=\'var row = jQuery(this).parents("tr:first"); row.insertAfter(row.next());\'><img src="' + downIcon + '"></span>';

	var tr_new = tb.insertRow(tb.rows.length);

	tr_new.appendChild(td1_new);
	tr_new.appendChild(td3_new);
	tr_new.appendChild(td4_new);
	tr_new.appendChild(td5_new);
}


/* ======================================
   =============== EVENT ================
   ====================================== */
function multiEventImageUploader(eventFolder, eventFolderPath) {
	jQuery("#multiImageUploader").change(function() {
		jQuery("#remove-image-loading").remove();
		jQuery("#table_event_pictures").append('<p id="remove-image-loading" class="text-center"><span class="icon-refresh icon-refresh-animate"></span>Loading...</p>');
		jQuery("#item-form").validationEngine('detach');
		var fisRe = /^.+\.(jpg|bmp|gif|png|jpeg|PNG|JPG|GIF|JPEG)$/i;
		var path = jQuery(this).val();
		
		if (path.search(fisRe) == -1) {
			jQuery("#remove-image-loading").remove();
			alert(' JPG, JPEG, BMP, GIF, PNG only!');
			return false;
		}	
		jQuery(this).upload(eventFolderPath, function(responce) {
			if( responce =='' ) {
				jQuery("#remove-image-loading").remove();
				alert("<?php echo JText::_('LNG_ERROR_ADDING_FILE',true)?>");
				jQuery(this).val('');
			}
			else {
				var xml = responce;
				jQuery(xml).find("picture").each(function() {
					if(jQuery(this).attr("error") == 0 ) {
						addEventPicture(
							eventFolder + jQuery(this).attr("path"),
							jQuery(this).attr("name")
						);
						jQuery("#remove-image-loading").remove();
					}
					else if( jQuery(this).attr("error") == 1 )
						alert("<?php echo JText::_('LNG_FILE_ALLREADY_ADDED',true)?>");
					else if( jQuery(this).attr("error") == 2 )
						alert("<?php echo JText::_('LNG_ERROR_ADDING_FILE',true)?>");
					else if( jQuery(this).attr("error") == 3 )
						alert("<?php echo JText::_('LNG_ERROR_GD_LIBRARY',true)?>");
					else if( jQuery(this).attr("error") == 4 )
						alert("<?php echo JText::_('LNG_ERROR_RESIZING_FILE',true)?>");
				});
				jQuery(this).val('');
			}
		}, 'html');
		jQuery("#item-form").validationEngine('attach');
	});
}

function addEventPicture(path, name) {
	var tb = document.getElementById('table_event_pictures');
	if( tb==null ) {
		alert('Undefined table, contact administrator !');
	}
	
	var td1_new	= document.createElement('td');  
	td1_new.style.textAlign='left';
	var textarea_new = document.createElement('textarea');
	textarea_new.setAttribute("name","event_picture_info[]");
	textarea_new.setAttribute("id","event_picture_info");
	textarea_new.setAttribute("cols","50");
	textarea_new.setAttribute("rows","2");
	td1_new.appendChild(textarea_new);
	
	var td2_new	= document.createElement('td');  
	td2_new.style.textAlign='center';
	var img_new	= document.createElement('img');
	img_new.setAttribute('src', picturesFolder + path );
	img_new.setAttribute('class', 'img_picture_offer');
	td2_new.appendChild(img_new);
	var span_new = document.createElement('span');
	span_new.innerHTML = "<BR>"+name;
	td2_new.appendChild(span_new);
	
	var input_new_1 = document.createElement('input');
	input_new_1.setAttribute('type', 'hidden');
	input_new_1.setAttribute('name', 'event_picture_enable[]');
	input_new_1.setAttribute('id', 'event_picture_enable[]');
	input_new_1.setAttribute('value', '1');
	td2_new.appendChild(input_new_1);
	
	var input_new_2 = document.createElement('input');
	input_new_2.setAttribute('type', 'hidden');
	input_new_2.setAttribute('name', 'event_picture_path[]');
	input_new_2.setAttribute('id', 'event_picture_path[]');
	input_new_2.setAttribute('value', path);
	td2_new.appendChild(input_new_2);
	
	var td3_new	= document.createElement('td');  
	td3_new.style.textAlign='center';
	
	var img_del	= document.createElement('img');
	img_del.setAttribute('src', deleteIcon);
	img_del.setAttribute('class', 'btn_picture_delete');
	img_del.setAttribute('id', 	tb.rows.length);
	img_del.setAttribute('name', 'del_img_' + tb.rows.length);
	img_del.onmouseover = function(){ this.style.cursor='hand';this.style.cursor='pointer' };
	img_del.onmouseout = function(){ this.style.cursor='default' };
	img_del.onclick = function() {
		if( !confirm('<?php echo JText::_("LNG_CONFIRM_DELETE_PICTURE",true)?>' ))
			return; 
		var row = jQuery(this).parents('tr:first');
		var row_idx = row.prevAll().length;
		jQuery('#crt_pos').val(row_idx);
		jQuery('#crt_path').val( path );
		jQuery('#btn_removefile').click();
	};
		
	td3_new.appendChild(img_del);
	
	var td4_new	= document.createElement('td');  
	td4_new.style.textAlign='center';
	var img_enable = document.createElement('img');
	img_enable.setAttribute('src', checkedIcon);
	img_enable.setAttribute('class', 'btn_picture_status');
	img_enable.setAttribute('id', tb.rows.length);
	img_enable.setAttribute('name', 'enable_img_' + tb.rows.length);
	
	img_enable.onclick = function() {
		var form = document.adminForm;
		var v_status = null;
		if( form.elements['event_picture_enable[]'].length == null ) {
			v_status  = form.elements['event_picture_enable[]'];
		}
		else {
			var pos = jQuery(this).closest('tr')[0].sectionRowIndex;
			var tb = document.getElementById('table_event_pictures');
			if( pos >= tb.rows.length )
				pos = tb.rows.length-1;
			v_status  = form.elements['event_picture_enable[]'][ pos ];
		}
		if(v_status.value=='1') {
			jQuery(this).attr('src', uncheckedIcon);
			v_status.value ='0';
		}
		else {
			jQuery(this).attr('src', checkedIcon);
			v_status.value ='1';
		}
	};
	td4_new.appendChild(img_enable);
	
	var td5_new = document.createElement('td');  
	td5_new.style.textAlign = 'center';
			
	td5_new.innerHTML = '<span class=\'span_up\' onclick=\'var row = jQuery(this).parents("tr:first");  row.insertBefore(row.prev());\'><img src="' + upIcon + '"></span>'+
						'<span class=\'span_down\' onclick=\'var row = jQuery(this).parents("tr:first"); row.insertAfter(row.next());\'><img src="' + downIcon + '"></span>';
	
	var tr_new = tb.insertRow(tb.rows.length);
	
	tr_new.appendChild(td1_new);
	tr_new.appendChild(td2_new);
	tr_new.appendChild(td3_new);
	tr_new.appendChild(td4_new);
	tr_new.appendChild(td5_new);
}

</script>