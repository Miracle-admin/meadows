/**
 * @package    AcyMailing for Joomla!
 * @version    4.8.1
 * @author     acyba.com
 * @copyright  (C) 2009-2014 ACYBA S.A.R.L. All rights reserved.
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */


var largeurMenuInline = 441;
var hauteurEditeurMin = 500;

var acyeditor_fullmode = false;
var acyeditor_listmode = false;
var acyeditor_templatemode = false;
var acyeditor_articlemode = false;
var typeCtrl;
var rangeIE;
var debutSelection;
var finSelection;
var rangeIE2;
var anchorNodeIE;
var realstylesheetpath;
var isJoomla2_5 = false;
var isJoomla3 = false;
var isBack = false;
var isTagAllowed = false;
var modalInitialized = false;
var tooltipTemplateDelete;
var tooltipTemplateText;
var tooltipTemplatePicture;
var tooltipShowAreas;
var templateShown = false;
var urlAcyeditor;
var boutonTags = "toolbar-popup-Acytags";
var boutonMediaBrowser = "toolbar-popup-Acymediabrowser";
var acyVersion = "4.8.1";
var pasteType = "plain";
var acyEnterMode = "br";
var urlSite = "";
var titleBtnMore = "";
var txtSup = "";
var titleSup = "";
var titleEd = "";
var titleBtnDupliAfter = "";
var defaultText = "Write you text here";
var inlineSource = 0;
var zoneActionActive;
var confirmInitAreas = "";
var tooltipInitAreas = "";

var initIE = false;
function Initialisation(id, type, urlBase, urlAdminBase, cssUrl, forceComplet, modeList, modeTemplate, modeArticle, joomla2_5, joomla3, back, tagAllowed, texteSuppression, titleSuppression, titleEdition, titleTemplateDelete, titleTemplateText, titleTemplatePicture, titleShowAreas) {

	txtSup = texteSuppression;
	titleSup = titleSuppression;
	titleEd = titleEdition;

	initIE = false;
	jQuery.noConflict();
	editor = undefined;
	realstylesheetpath = cssUrl;
	isJoomla2_5 = joomla2_5;
	isJoomla3 = joomla3;
	isBack = (back == 1);
	isTagAllowed = (tagAllowed == 1);
	typeCtrl = type;
	acyeditor_listmode = modeList;
	acyeditor_templatemode = modeTemplate;
	acyeditor_articlemode = modeArticle;
	tooltipTemplateDelete = titleTemplateDelete;
	tooltipTemplateText = titleTemplateText;
	tooltipTemplatePicture = titleTemplatePicture;
	tooltipShowAreas = titleShowAreas;
	urlAcyeditor = "plugins/editors/acyeditor/acyeditor/";
	if (!isJoomla2_5 && !isJoomla3){
		urlAcyeditor = "plugins/editors/acyeditor/";
	}
	if (!isBack)
	{
		boutonTags = "acybuttontag";
		boutonMediaBrowser = "acybuttonmediabrowser";
	}

	if (isBrowserIE7() || isBrowserIE8())
	{
		var popupTagContainer = parent.document.getElementById(boutonTags);
		var popupMediaBrowserContainer = parent.document.getElementById(boutonMediaBrowser);
		if (popupTagContainer != null
		 && popupTagContainer != undefined
		 && popupTagContainer.children[0] != null
		 && popupTagContainer.children[0] != undefined)
		{
			popupTagContainer.children[0].onclick = function () { IeCursorFix(true); };
		}

		if (popupMediaBrowserContainer != null
		 && popupMediaBrowserContainer != undefined
		 && popupMediaBrowserContainer.children[0] != null
		 && popupMediaBrowserContainer.children[0] != undefined)
		{
			popupMediaBrowserContainer.children[0].onclick = function () { IeCursorFix(true); };
		}
	}
	else
	{
		var popupTagContainer = parent.document.getElementById(boutonTags);
		var popupMediaBrowserContainer = parent.document.getElementById(boutonMediaBrowser);
		if (popupTagContainer != null
		 && popupTagContainer != undefined
		 && popupTagContainer.children[0] != null
		 && popupTagContainer.children[0] != undefined)
		{
			popupTagContainer.children[0].onclick = function () { IeCursorFix(); };
		}

		if (popupMediaBrowserContainer != null
		 && popupMediaBrowserContainer != undefined
		 && popupMediaBrowserContainer.children[0] != null
		 && popupMediaBrowserContainer.children[0] != undefined)
		{
			popupMediaBrowserContainer.children[0].onclick = function () { IeCursorFix(); };
		}
	}

	qs = document.location.search.split("+").join(" ");
	var params = {},
			tokens,
			re = /[?&]?([^=]+)=([^&]*)/g;

	while (tokens = re.exec(qs)) {
			params[decodeURIComponent(tokens[1])] = decodeURIComponent(tokens[2]);
	}

	if(params['tmpl']){
		if(params['tmpl']=='component')
			var inPopup = true;
	}


	if (jQuery('#AcyLienImage')[0] == null || jQuery('#AcyLienImage')[0] == undefined)
	{
		var lienImage = document.createElement("a");
		lienImage.id = "AcyLienImage";
		if(inPopup)
				endUrl='&inpopup=true';
		lienImage.href = "index.php?option=com_acymailing&tmpl=component&ctrl=editor&task=browse&e_name=ACY_NAME_AREA&image_zone=true" + endUrl;
		if (!isBack){
			lienImage.href = "index.php?option=com_acymailing&tmpl=component&ctrl=fronteditor&task=browse&e_name=ACY_NAME_AREA&image_zone=true" + endUrl;
		}
		lienImage.rel = "{handler: 'iframe', size: {x: 850, y: 600}}";
		if(inPopup)
			lienImage.rel = "{handler: 'iframe', size: {x: 700, y: 460}}";
		lienImage.style.display = "none";
		document.body.appendChild(lienImage);
		jQuery('#' + lienImage.id).addClass("modal");
	}
	if (jQuery('#AcyLienTag')[0] == null || jQuery('#AcyLienTag')[0] == undefined)
	{
		var lienTag = document.createElement("a");
		lienTag.id = "AcyLienTag";
		lienTag.href = "index.php?option=com_acymailing&ctrl=tag&task=tag&tmpl=component&type=" + typeCtrl;
		if (!isBack)
		{
			lienTag.href = urlBase + "index.php?option=com_acymailing&ctrl=fronttag&task=tag&tmpl=component&type=" + typeCtrl;
		}
		lienTag.rel = "{handler: 'iframe', size: {x: 780, y: 550}}";
		lienTag.onclick = function () { try { IeCursorFix(); } catch (e) {} };
		lienTag.style.display = "none";
		document.body.appendChild(lienTag);
		jQuery('#' + lienTag.id).addClass("modal");
	}

	if (jQuery('#AcyLienMediaBrowser')[0] == null || jQuery('#AcyLienMediaBrowser')[0] == undefined)
	{
			var lienMediaBrowser = document.createElement("a");
			lienMediaBrowser.id = "AcyLienMediaBrowser";
			var endUrl='';
			if(inPopup)
				endUrl='&inpopup=true';
			lienMediaBrowser.href = "index.php?option=com_acymailing&tmpl=component&ctrl=editor&task=browse&e_name=ACY_NAME_AREA" + endUrl;
			if (!isBack)
			{
				lienMediaBrowser.href = urlBase + "index.php?option=com_acymailing&tmpl=component&ctrl=fronteditor&task=browse&e_name=ACY_NAME_AREA" + endUrl;
			}
			lienMediaBrowser.rel = "{handler: 'iframe', size: {x: 850, y: 600}}";
			if(inPopup)
				lienMediaBrowser.rel = "{handler: 'iframe', size: {x: 700, y: 460}}";
			lienMediaBrowser.onclick = function () { try { IeCursorFix(); } catch (e) {} };
			lienMediaBrowser.style.display = "none";
			document.body.appendChild(lienMediaBrowser);
			jQuery('#' + lienMediaBrowser.id).addClass("modal");
	}

	var idIframe = id + "_ifr";
	var textArea = jQuery('#' + id)[0];
	var divParent = jQuery('#' + idIframe)[0];

	if (divParent != null && divParent != undefined)
	{
		divParent.outerHTML = "";
	}

	divParent = document.createElement("div");
	divParent.style.width = textArea.style.width;
	divParent.style.height = textArea.style.height;
	divParent.id = idIframe;
	divParent.innerHTML = textArea.value;
	textArea.parentElement.appendChild(divParent);

	var acyedition = (jQuery('#' + idIframe).find(".acyeditor_delete").length > 0
					 || jQuery('#' + idIframe).find(".acyeditor_text").length > 0
					 || jQuery('#' + idIframe).find(".acyeditor_picture").length > 0);

	if (acyedition && forceComplet != 1)
	{
		acyeditor_fullmode = false;
		var iframe = document.createElement("iframe");
		iframe.frameBorder = '0';
		divParent.parentElement.appendChild(iframe);

		var code = divParent.innerHTML;
		var width = divParent.style.width;
		var height = divParent.style.height;
		divParent.outerHTML = "";
		iframe.id = idIframe;

		iframe.onload = function() {
			if (isBrowserIE() && !initIE)
			{
				initIE = true;
				var markup = '<!DOCTYPE html><html></html>';
				iframe.contentWindow.document.open();
				iframe.contentWindow.document.write(markup);
				iframe.contentWindow.document.close();
			}
				ChargementIframe(iframe, urlBase, code, width, height, id, texteSuppression, titleSuppression, titleEdition, urlAdminBase, realstylesheetpath);

				if ((isBrowserIE7() || isBrowserIE8()) && !isJoomla2_5 && !isJoomla3)
				{
					setTimeout(function() {
						SqueezeBox.initialize({});

						$$('a.modal').each(function(el) {
							el.addEvent('click', function(e) {
								new Event(e).stop();
								SqueezeBox.fromElement(el);
							});
						});
					}, 200);
				}
		};

		iframe.src ="";
	}
	else
	{
		acyeditor_fullmode = true;

		var hauteur = jQuery('#' + idIframe).height() - 70 + "px";
		var largeur = "100%";

		var code = divParent.innerHTML;
		divParent.innerHTML = "<textarea id='edition_en_cours' style='width:100%;height:100%'></textarea>";
		if (acyeditor_articlemode)
		{
			largeur = jQuery(".adminform").width() + "px";
			hauteur = "150px";
		}
		if (acyeditor_listmode)
		{
			largeur = jQuery(".adminform").width() - 65 + "px";
			hauteur = "127px";
		}

		var extraPluginsCKEditor = 'resize';
		var toolbarGroupsCKEditor = [
				{ name: 'tools' },
				{ name: 'mode' },
				{ name: 'undo' },
				{ name: 'links' }];


		extraPluginsCKEditor += ',acymediabrowser';

		if (!acyeditor_listmode && isTagAllowed)
		{
			extraPluginsCKEditor += ',addtag';
			toolbarGroupsCKEditor.push({ name: 'insert', groups: [ "acymediabrowser", "addtag" ]});
		}
		else
		{
			toolbarGroupsCKEditor.push({ name: 'insert', groups: [ "acymediabrowser" ]});
		}
		toolbarGroupsCKEditor.push({ name: 'basicstyles',   groups: [ 'basicstyles', 'cleanup' ] },
									{ name: 'colors' },
									{ name: 'paragraph',   groups: [ 'list', 'indent' ] },
									{ name: 'align' },
									{ name: 'styles' });
		if (acyeditor_templatemode)
		{
			extraPluginsCKEditor += ',templatemode';
			toolbarGroupsCKEditor.push({ name: 'templatemode', groups: [ "textarea", "picturearea", "deletearea", "-", "showarea" ]});
		}
		extraPluginsCKEditor += ',stylesheetparser';

		if(pasteType == 'plain'){
			pastePlain = true;
			pasteWordSimple = false;
		} else if(pasteType == 'simpleStyle'){
			pastePlain = false;
			pasteWordSimple = true;
		}

		if(acyEnterMode == 'p'){ enterM = CKEDITOR.ENTER_P;}
		else if(acyEnterMode == 'div'){ enterM = CKEDITOR.ENTER_DIV;}
		else{ enterM = CKEDITOR.ENTER_BR; }

		editor = CKEDITOR.replace("edition_en_cours",{
			toolbarGroups : toolbarGroupsCKEditor,
			height : hauteur,
			width : largeur,
			baseHref : urlBase,
			filebrowserImageUploadUrl : urlBase + urlAcyeditor + 'kcfinder/upload.php?type=images',
			removeButtons: 'Cut,Copy,Paste,Blockquote,HorizontalRule,SpecialChar,Symbol',
			removePlugins: 'contextmenu,liststyle,tabletools,image,forms',
			extraPlugins: extraPluginsCKEditor,
			forcePasteAsPlainText: pastePlain,
			pasteFromWordRemoveFontStyles: pasteWordSimple,
			enterMode: enterM
		});

		if(cssUrl != null){ editor.config.contentsCss = urlBase + cssUrl; }

		editor.setData(code);
		editor.on('instanceReady',function(e){
			var iframe = jQuery('#edition_en_cours')[0].parentElement.getElementsByTagName('iframe')[0];
			editor.on('paste', function(e){ IeCursorFix(); });
			iframe.contentWindow.document.body.onkeyup = function () { IeCursorFix(); };
			iframe.contentWindow.document.body.onclick = function () { IeCursorFix(); };
			rangeIE = undefined;
			IeCursorFix();

			if (realstylesheetpath == null || realstylesheetpath == undefined || realstylesheetpath == "")
			{
				var headEditor = iframe.contentWindow.document;
				headEditor = headEditor.head || headEditor;
				var linkCss = headEditor.getElementsByTagName("link")[0];
				jQuery(linkCss).removeAttr("href");
			}
			else
			{
				var headEditor = iframe.contentWindow.document;
				headEditor = headEditor.head || headEditor;
				var linkCss = headEditor.getElementsByTagName("link")[0];
				SetStyleSheetEnBoucle(linkCss, urlBase, realstylesheetpath, Date.now());
			}

			ShowTemplateCss(true);

			editor.on('selectionChange', function(e) { IeCursorFix(); });

			editor.on('mode',function(e){
				var iframe = jQuery('#' + id)[0].parentElement.getElementsByTagName("iframe")[0];
				if (iframe != undefined)
				{
					var headEditor = iframe.contentWindow.document;
					headEditor = headEditor.head || headEditor;
					var linkCss = headEditor.getElementsByTagName("link")[0];
					if (realstylesheetpath != null && realstylesheetpath != undefined && realstylesheetpath != "")
					{
						SetStyleSheetEnBoucle(linkCss, urlBase, realstylesheetpath, Date.now());
					}
					else
					{
						jQuery(linkCss).removeAttr("href");
					}
					if (acyeditor_templatemode)
					{
						ShowTemplateCss(templateShown);
						SetStateForSelection();
					}
				}
				var textarea = jQuery('#cke_edition_en_cours .cke_inner .cke_contents textarea')[0];
				if (textarea != undefined)
				{
					textarea.title = "";
					textarea.parentElement.style.paddingRight = "5px";
				}
			});

			var editorBody = jQuery('#' + id + '_ifr')[0];
			if (editorBody != undefined)
			{
				editorBody.style.width = "100%";
				if (acyeditor_articlemode && isJoomla3)
				{
					editorBody.style.marginBottom = "27px";
				}
			}

			setTimeout(function() {
				jQuery("body")[0].onresize = function(e) {
					jQuery("#cke_edition_en_cours")[0].style.width = "10px";
					jQuery("#cke_edition_en_cours")[0].style.width = jQuery("#edition_en_cours")[0].parentElement.clientWidth - 10 + "px";
				};
			}, 500);

			jQuery("#edition_en_cours")[0].parentElement.style.height = "";
		});

		var listeForms = document.getElementsByTagName('form');
		for (indexForm = 0; indexForm < listeForms.length; ++indexForm)
		{
			listeForms[indexForm].onsubmit = function () { OnSubmit(id); };
		}
	}

	CKEDITOR.on( 'instanceCreated', function( ev ){
												var editor = ev.editor;

												editor.on( 'pluginsLoaded', function() {

																if ( !CKEDITOR.dialog.exists( 'myDialog' ) ) {
																				var href = document.location.href.split( '/' );
																				href.pop();
																				href.push( 'assets/my_dialog.js' );
																				href = href.join( '/' );

																				CKEDITOR.dialog.add( 'myDialog', href );
																}

																editor.addCommand( 'myDialogCmd', new CKEDITOR.dialogCommand( 'myDialog' ) );

																editor.ui.add( 'MyButton', CKEDITOR.UI_BUTTON, {
																				label: 'My Dialog',
																				command: 'myDialogCmd'
																});
												});
								});


}

function ChargementIframe(iframe, urlBase, code, width, height, id, texteSuppression, titleSuppression, titleEdition, urlAdminBase, stylesheetpath){
 	iframe.contentWindow.document.body.innerHTML = code;
	iframe.frameborder = "0";
	iframe.allowtransparency = "true";
	iframe.style.width = width;
	iframe.style.height = height;

	var header = jQuery('#' + iframe.id).contents().find("head")[0];
	var base1 = document.createElement("base");
	base1.href = urlBase;
	header.appendChild(base1);
	var script1 = document.createElement("script");
	script1.type = "text/javascript";
	script1.src =  urlBase + urlAcyeditor + "ckeditor/ckeditor.js?v=" + acyVersion;
	header.appendChild(script1);
	var link1 = document.createElement("link");
	var link2 = document.createElement("link");
	link1.type = "text/css"; link2.type = "text/css";
	link1.rel = "stylesheet"; link2.rel = "stylesheet";
	link1.href =  urlBase + urlAcyeditor + "css/acyeditor.css?v=" + acyVersion;
	if (stylesheetpath != null && stylesheetpath != undefined && stylesheetpath != "")
	{
		link2.href =  urlBase + stylesheetpath + "?time=" + Date.now();
	}
	link2.id = "acy_template_css";
	header.appendChild(link1); header.appendChild(link2);

	InitContent(id, texteSuppression, titleSuppression, titleEdition, urlAdminBase);
}

function InitContent(id, texteSuppression, titleSuppression, titleEdition, urlAdminBase){
	var idIframe = id + "_ifr";

	CreationDesZones(id, texteSuppression, titleSuppression, titleEdition, urlAdminBase);
	SetEditablesElements(id);

	SetImagesId(id);

	document.getElementsByTagName('body')[0].onclick = function (e) { CheckDeselection(id, e); hideActionButtons(id, e, 'outside', false);};
	var listeForms = document.getElementsByTagName('form');
	for (indexForm = 0; indexForm < listeForms.length; ++indexForm)
	{
		listeForms[indexForm].onsubmit = function () { CheckDeselection(id); };
	}
	jQuery('#' + idIframe)[0].contentWindow.document.onclick = function (e) { CheckDeselection(id, e); hideActionButtons(id, e, 'editor', false); };

	setTimeout(function() {
		ResizeIframe(id);
	}, 100);

	if (isBrowserIE())
	{
		jQuery('#' + idIframe).contents().find(".acyeditor_picture").hover(
			function () {
				if (jQuery(this)[0].className.indexOf("acyeditor_enedition") < 0)
				{
					jQuery(this).addClass('acyeditor_editablehover');
					jQuery(this).find(".acyeditor_zoneeditionsuppression").addClass('acyeditor_zoneeditionsuppressionhover');
				}
			},
			function() {
				jQuery(this).removeClass('acyeditor_editablehover');
				jQuery(this).find(".acyeditor_zoneeditionsuppression").removeClass('acyeditor_zoneeditionsuppressionhover');
			}
		);
		jQuery('#' + idIframe).contents().find(".acyeditor_text").hover(
			function () {
				if (jQuery(this)[0].className.indexOf("acyeditor_enedition") < 0)
				{
					jQuery(this).addClass('acyeditor_editablehover');
					jQuery(this).find(".acyeditor_zoneeditionsuppression").addClass('acyeditor_zoneeditionsuppressionhover');
				}
			},
			function() {
				jQuery(this).removeClass('acyeditor_editablehover');
				jQuery(this).find(".acyeditor_zoneeditionsuppression").removeClass('acyeditor_zoneeditionsuppressionhover');
			}
		);
		jQuery('#' + idIframe).contents().find(".acyeditor_delete").hover(
			function () {
				if (jQuery(this)[0].className.indexOf("acyeditor_enedition") < 0)
				{
					jQuery(this).find(".acyeditor_zoneeditionsuppression").addClass('acyeditor_zoneeditionsuppressionhover');
				}
			},
			function() {
				jQuery(this).find(".acyeditor_zoneeditionsuppression").removeClass('acyeditor_zoneeditionsuppressionhover');
			}
		);
	}
}

function getPreviousSelection(){
	var id = 'editor_body';
	var acyframe =  jQuery('#' + id, window.parent.document)[0].parentElement.getElementsByTagName('iframe')[0];
	var previousSelection = {}, sel = acyframe.contentWindow.getSelection();
	for(var k in sel) {
		if(typeof(sel[k]) != 'function')
			previousSelection[k] = sel[k];
	}
	previousSelection.range_0 = sel.getRangeAt(0);

	return previousSelection;
}

function insertImageTag(tag,previousSelection){
	try{
		jInsertEditorText(tag,'edition_en_cours',previousSelection);
		return true;
	}catch(err){
		try{
			jInsertEditorText(tag,'editor_body',previousSelection);
			return true;
		}catch(err){
			alert('Your editor does not enable AcyMailing to automatically insert the tag, please copy/paste it manually in your Newsletter');
			return false;
		}
	}
}

function getSelectedHTML(editor){
	var id = jQuery('#htmlfieldset')[0] != null ? jQuery('#htmlfieldset')[0].getElementsByTagName("textarea")[0].id : "edition_en_cours";
	var element = GetElement(id, editor)[0];
	if (element == null || element == undefined)
	{
		element = $(editor);
	}
	return element.innerHTML;
}

function jInsertEditorText(text, editor, previousSelection){
	var id = jQuery('#htmlfieldset')[0] != null ? jQuery('#htmlfieldset')[0].getElementsByTagName("textarea")[0].id : "edition_en_cours";
	var element = GetElement(id, editor)[0];
	if (element == null || element == undefined)
	{
		element = document.getElementById(editor);
	}
	insertAtCursor(element, text,previousSelection);
}

function insertAtCursor(myField, myValue, previousSelection){

	var id = jQuery('#htmlfieldset')[0] != null ? jQuery('#htmlfieldset')[0].getElementsByTagName("textarea")[0].id : "edition_en_cours";

	if (myField.className.indexOf("acyeditor_picture") >= 0)
	{
		GetElement(id, myField.id).removeClass('acyeditor_picture');
		var zone = GetElement(id, "ZoneEditionSuppression_" + myField.id);
		if (zone[0] != null && zone[0] != undefined)
		{
			EffaceZone(zone[0]);
			zone.remove();
		}
		if (myValue == undefined)
		{
			myValue = "";
		}


		var width = 0;
		if(myField.width >0){
			width = myField.width;
		}else if(myField.style.width > 0){
			width = myField.style.width;
		}else{
			width = myField.clientWidth;
		}

		myField.innerHTML = myValue;
		var images = myField.getElementsByTagName("img");

		for (indexImage = 0; indexImage < images.length; ++indexImage)
		{
			if(images[indexImage].width > width){
				images[indexImage].width = width;
				images[indexImage].height = images[indexImage].clientHeight;
			}
		}
		if (zone[0] != null && zone[0] != undefined)
		{
			myField.appendChild(zone[0]);
		}

		AdapteTaille(id, zone);
		GetElement(id, myField.id).addClass('acyeditor_picture');
		Sauvegarde(id);
		ResizeIframe(id);
	}
	else
	{
		var acyframe =  jQuery('#' + id)[0].parentElement.getElementsByTagName("iframe")[0];
		if (acyframe != null
		 && acyframe != undefined
		 && acyframe.contentWindow != null
		 && acyframe.contentWindow != undefined
		 && acyframe.contentWindow.getSelection)
		{

			var sel = acyframe.contentWindow.getSelection();

			try{
				range = sel.getRangeAt(0);
			}catch(err){
				try{
					range = previousSelection.range_0;
				}catch(err2){
					range=false;
				}
			}
			if(sel.anchorNode==null && previousSelection){
				range = previousSelection.range_0;
				var sel = previousSelection;
			}

			var newNode = acyframe.contentWindow.document.createElement("div");
			newNode.innerHTML = myValue;
			if (isBrowserIE()
			 && rangeIE != undefined)
			{
				rangeIE.deleteContents();
				while (newNode.childNodes.length > 0)
				{
					rangeIE.insertNode(newNode.childNodes[newNode.childNodes.length - 1]);
				}
				if (editor != null && editor != undefined)
				{
					editor.fire( 'saveSnapshot' );
				}
			}
			else if (isEnEdition(sel.anchorNode) && range && sel.rangeCount) {
				range.deleteContents();
				while (newNode.childNodes.length > 0)
				{
					range.insertNode(newNode.childNodes[newNode.childNodes.length - 1]);
				}
				if (editor != null && editor != undefined)
				{
					editor.fire( 'saveSnapshot' );
				}
			}
			else
			{
				AjoutTagDansSujet(myValue);
			}
		}
		else if (acyframe != null
				&& acyframe != undefined
				&& acyframe.contentWindow.document != null
				&& acyframe.contentWindow.document != undefined
				&& acyframe.contentWindow.document.selection
				&& isBrowserIE()
				&& rangeIE != undefined
				&& rangeIE.parentElement
				&& isInForm(id, rangeIE.parentElement()))
		{
			if (isJoomla3
			 && (isBrowserIE7() || isBrowserIE8())
			 && debutSelection >= 0 && finSelection >= 0
			 && (acyframe.contentWindow.document.selection.createRange().parentElement
				&& isInForm(id, acyframe.contentWindow.document.selection.createRange().parentElement())
			 || !acyeditor_fullmode)
			&& rangeIE.text.length != "")
			{
				rangeIE.moveStart('character', debutSelection);
				rangeIE.moveEnd('character', -finSelection);
			}
			try
			{
				rangeIE.pasteHTML(myValue);
			}
			catch (e)
			{
				rangeIE.text = myValue;
			}
			if (editor != null && editor != undefined)
			{
				editor.fire( 'saveSnapshot' );
			}
		}
		else
		{
			AjoutTagDansSujet(myValue);
		}
	}
}

function AjoutTagDansSujet(myValue){
	jQuery("#subject").val(jQuery("#subject").val() + myValue);
}

function isEnEdition(element){
	var enedition = true;
	if (acyeditor_fullmode == false)
	{
		enedition = false;
		var parent = element;
		while (parent != null && parent != undefined)
		{
			if (parent.className != null && parent.className != undefined && parent.className.indexOf("acyeditor_enedition") >= 0)
			{
				enedition = true;
			}
			parent = parent.parentElement || parent.parentNode;
		}
	}
	return enedition;
}

function isInForm(id, element){
	var inForm = false;
	var parent = element;
	var idForm = jQuery("#" + id)[0].parentElement.id;
	var acyframe = jQuery("#" + id + "_ifr")[0].contentWindow;
	if (acyframe == undefined)
	{
		acyframe = jQuery(".cke_wysiwyg_frame")[0].contentWindow;
	}
	while (parent != null && parent != undefined)
	{
		if (parent.id == idForm
		 || acyframe != undefined
		 && (parent.id != "" && acyframe.document.getElementById(parent.id) != null
			|| parent.className != undefined && parent.className.indexOf("cke_editable") >= 0))
		{
			inForm = true;
		}
		parent = parent.parentElement || parent.parentNode;
	}
	return inForm;
}

function isBrowserIE(){
	return navigator.appName=="Microsoft Internet Explorer";
}

function isBrowserIE7(){
	return (navigator.appName=="Microsoft Internet Explorer" &&
			navigator.appVersion.indexOf("MSIE 7.0") >= 0);
}

function isBrowserIE8(){
	return (navigator.appName=="Microsoft Internet Explorer" &&
			navigator.appVersion.indexOf("MSIE 8.0") >= 0);
}

function IeCursorFix(avecPosition){
	debutSelection = -1;
	finSelection = -1;
	if (isBrowserIE()) {
		var id = jQuery('#htmlfieldset')[0] != null ? jQuery('#htmlfieldset')[0].getElementsByTagName("textarea")[0].id : "edition_en_cours";
		var acyframe =  jQuery('#' + id)[0].parentElement.getElementsByTagName("iframe")[0];
		if (acyframe != null
		 && acyframe != undefined
		 && acyframe.contentWindow != null
		 && acyframe.contentWindow != undefined
		 && acyframe.contentWindow.getSelection)
		{
			rangeIE = undefined;
			var sel = acyframe.contentWindow.getSelection();
			if (isEnEdition(sel.anchorNode) && sel.getRangeAt && sel.rangeCount) {
				rangeIE = sel.getRangeAt(0);
			}
		}
		else if (acyframe != null
				&& acyframe != undefined
				&& acyframe.contentWindow.document != null
				&& acyframe.contentWindow.document != undefined
				&& acyframe.contentWindow.document.selection)
		{
			if (acyframe.contentWindow.document.selection.createRange().parentElement)
			{
				anchorNodeIE = acyframe.contentWindow.document.selection.createRange().parentElement();
			}
			if (avecPosition
			 || rangeIE == undefined
			 || acyframe.contentWindow.document.selection.createRange().parentElement
			 && isEnEdition(acyframe.contentWindow.document.selection.createRange().parentElement()))
			{
				var nouvelleSelection = acyframe.contentWindow.document.selection;
				var bonElement = (nouvelleSelection.type != "Control");
				if ((rangeIE == undefined
					|| !avecPosition
					|| rangeIE.text != nouvelleSelection.createRange().text)
				 && bonElement)
				{
					rangeIE = nouvelleSelection.createRange();
					rangeIE2 = nouvelleSelection.createRange();
				}

				if (isJoomla3 && bonElement && avecPosition && (isBrowserIE7() || isBrowserIE8()))
				{
					var textComplet = rangeIE.parentElement().innerText;
					if (textComplet.length > rangeIE.text.length)
					{
						for (indexDebut = 0; indexDebut < textComplet.length; ++indexDebut)
						{
							rangeIE2.moveToBookmark(rangeIE.getBookmark());
							var longueurInitiale = rangeIE2.text.length;
							rangeIE2.moveStart('character', -indexDebut);
							var longueurIntermediaire = rangeIE2.text.length;
							var indexFin = textComplet.length - rangeIE2.text.length;
							rangeIE2.moveEnd('character', indexFin);
							if (rangeIE2.text.length > textComplet.length)
							{
								rangeIE2.moveEnd('character', -(rangeIE2.text.length - textComplet.length));
							}
							if (rangeIE2.text.length < textComplet.length)
							{
								rangeIE2.moveEnd('character', -(textComplet.length - rangeIE2.text.length));
							}
							var longueurFinale = rangeIE2.text.length;
							if (rangeIE2.text == textComplet)
							{
								debutSelection = indexDebut;
								finSelection = indexFin;

								indexDebut = textComplet.length + 1;
							}
						}
					}
					else
					{
						debutSelection = 0;
						finSelection = 0;
					}
				}
			}
		}
		else
		{
			rangeIE = undefined;
		}
	}
	return true;
}

function setEditorStylesheet(id, stylesheeturl, stylesheetpath){
	realstylesheetpath = stylesheetpath;
	rangeIE = undefined;
	var idIframe = id + "_ifr";
	var linkCss = GetElement(id, "acy_template_css")[0];
	if (editor != undefined){
		editor.config.contentsCss = urlSite+stylesheetpath;
	}
	if (linkCss == undefined)
	{
		if (editor != undefined)
		{
			editor.on('instanceReady',function(){
				var iframe = jQuery('#' + id)[0].parentElement.getElementsByTagName("iframe")[0];
				if (iframe != undefined)
				{
					var headEditor = iframe.contentWindow.document;
					headEditor = headEditor.head || headEditor;
					var base = headEditor.getElementsByTagName("base")[0];
					linkCss = headEditor.getElementsByTagName("link")[0];
					SetStyleSheetEnBoucle(linkCss, base.href, stylesheetpath, Date.now());
				}
			});
		}
	}
	else if (stylesheetpath != null && stylesheetpath != undefined && stylesheetpath != "")
	{
		linkCss.href = linkCss.baseURI + stylesheetpath + "?time=" + Date.now();
	}
}

function SetStyleSheetEnBoucle(linkCss, urlBase, stylesheetpath, date){
	SetStyleSheet(linkCss, urlBase, stylesheetpath, date);
	setTimeout(function() {
		SetStyleSheet(linkCss, urlBase, stylesheetpath, date);
	}, 200);
	setTimeout(function() {
		SetStyleSheet(linkCss, urlBase, stylesheetpath, date);
	}, 500);
	setTimeout(function() {
		SetStyleSheet(linkCss, urlBase, stylesheetpath, date);
	}, 1500);
}
function SetStyleSheet(linkCss, urlBase, stylesheetpath, date){
	if(stylesheetpath.indexOf("template_0.css")<0){
		linkCss.href = urlBase+stylesheetpath+"?time=" + date;
	}
}

function ResizeIframe(id){
	if (acyeditor_listmode)
	{
		var iframe = jQuery('#' + id)[0].parentElement.getElementsByTagName("iframe")[0];
		var textarea = jQuery('#edition_en_cours')[0];
		if (iframe != undefined && textarea != undefined)
		{
			var innerHeight = iframe.contentWindow.document.body.clientHeight;
			if (innerHeight < hauteurEditeurMin)
			{
				innerHeight = hauteurEditeurMin;
			}
			iframe.parentElement.style.height = innerHeight + 90 + "px";
			textarea.parentElement.style.height = "";
			var editorBody = jQuery('#' + id + '_ifr')[0];
			editorBody.style.width = "100%";
		}
	}
	else
	{
		var iframe = jQuery('#' + id)[0].parentElement.getElementsByTagName("iframe")[0];
		var editorBody = jQuery('#' + id + '_ifr')[0];
		var htmlfieldset = jQuery('#htmlfieldset')[0];
		if (iframe != undefined && editorBody != undefined && htmlfieldset != undefined)
		{

			editorBody.style.width = "100%";

			if (acyeditor_fullmode)
			{
				var innerHeight = iframe.contentWindow.document.body.clientHeight;
				if (innerHeight < hauteurEditeurMin)
				{
					innerHeight = hauteurEditeurMin;
				}
				iframe.parentElement.style.height = innerHeight + 90 + "px";
				editorBody.style.height = "";
				htmlfieldset.style.height = "";

			}
			else
			{
				var innerHeight = iframe.contentWindow.document.body.children[0].clientHeight;
				if (innerHeight < hauteurEditeurMin)
				{
					innerHeight = hauteurEditeurMin;
				}
				editorBody.style.height = innerHeight + 80 + "px";
				htmlfieldset.style.height = "";
			}
		}
	}
}

function OnSubmit(id){
	if (acyeditor_templatemode)
	{
		SetTitleTemplate(true);
	}

	jQuery('#' + id)[0].value = (editor != null && editor != undefined) ? editor.getData() : "";
}

function SetEditablesElements(id){
	var idIframe = id + "_ifr";
	var elements = jQuery('#' + idIframe)[0].contentWindow.document.body.getElementsByTagName('*');
	for (i = 0; i < elements.length; ++i)
	{
		var element = elements[i];
		if (element.className.indexOf("acyeditor_text") >= 0
		 && element.onclick == null)
		{
			if (element.id == null || element.id == '' || element.id == undefined)
			{
				element.id = GetNewId(id);
			}
			SetOnClick(id, element);
		}
	}
}

function SetImagesId(id){
	var idIframe = id + "_ifr";
	var elements = jQuery('#' + idIframe)[0].contentWindow.document.body.getElementsByTagName('img');
	for (indexImagesId = 0; indexImagesId < elements.length; ++indexImagesId)
	{
		var element = elements[indexImagesId];
		if (element.id == null || element.id == '' || element.id == undefined)
		{
			element.outerHTML = element.outerHTML.replace("<img ", "<img id=\"" + GetNewId(id) + "\" ");
		}
		else
		{
			element.outerHTML = element.outerHTML;
		}
	}
}

function CreationDesZones(id, texteSuppression, titleSuppression, titleEdition, urlBase){
	var idIframe = id + "_ifr";
	var elementsZones = jQuery('#' + idIframe)[0].contentWindow.document.body.getElementsByTagName('*');
	for (indexZone = 0; indexZone < elementsZones.length; ++indexZone)
	{
		var elementZone = elementsZones[indexZone];
		CreationZone(id, elementZone, texteSuppression, titleSuppression, titleEdition, urlBase);
	}
}

function CreationZone(id, element , texteSuppression, titleSuppression, titleEdition, urlBase){
 	if (jQuery(element).hasClass("acyeditor_delete")
	 || jQuery(element).hasClass("acyeditor_text")
	 || jQuery(element).hasClass("acyeditor_picture"))
	{
		if (element.id == null || element.id == '' || element.id == undefined)
		{
			element.id = GetNewId(id);
		}

		if (element.tagName == "TR")
		{
			if (jQuery(element).hasClass("acyeditor_delete"))
			{
				var elementTDEditables = jQuery(element).find("td:not(td.acyeditor_text), td:not(td.acyeditor_picture)");
				if (elementTDEditables.length == 0)
				{
					elementTDEditables = element.children;
				}
				for (j = 0; j < elementTDEditables.length; ++j)
				{
					var sousElementsTD = elementTDEditables[j];
					if (sousElementsTD.tagName == "TD")
					{
						if (sousElementsTD.id == null || sousElementsTD.id == '' || sousElementsTD.id == undefined)
						{
							sousElementsTD.id = GetNewId(id);
						}

						GetElement(id, sousElementsTD.id).addClass("acyeditor_delete");
						CreationZone(id, sousElementsTD , texteSuppression, titleSuppression, titleEdition, urlBase);
						GetElement(id, sousElementsTD.id).removeClass("acyeditor_delete");
					}
				}
			}
		}
		else if (jQuery(element).find(".acyeditor_delete").length
				 + jQuery(element).find(".acyeditor_text").length
				 + jQuery(element).find(".acyeditor_picture").length == 0)
		{
			if (Existe(id, "ZoneEditionSuppression_" + element.id) == false)
			{
				var zone = document.createElement("div");
				zone.id = "ZoneEditionSuppression_" + element.id;
				zone.style.position = "absolute";
				element.appendChild(zone);
				GetElement(id, zone.id).addClass('acyeditor_zoneeditionsuppression');
				if (jQuery(element).hasClass("acyeditor_text")
				 || jQuery(element).hasClass("acyeditor_picture"))
				{
					if (jQuery(element).hasClass('acyeditor_picture'))
					{
						zone.onclick = function () {
							if (!jQuery(element).hasClass('nepasediter'))
							{
								jQuery('#AcyLienImage')[0].href = jQuery('#AcyLienImage')[0].href.replace('ACY_NAME_AREA', element.id);
								FireClick(jQuery('#AcyLienImage')[0]);
								jQuery('#AcyLienImage')[0].href = jQuery('#AcyLienImage')[0].href.replace(element.id, 'ACY_NAME_AREA');
							}
							else if (!isBrowserIE())
							{
								jQuery(element).removeClass('nepasediter');
							}
						};
						if (isBrowserIE())
						{
							zone.parentElement.onclick = function () {
								if (!jQuery(element).hasClass('nepasediter'))
								{
									jQuery('#AcyLienImage')[0].href = jQuery('#AcyLienImage')[0].href.replace('ACY_NAME_AREA', element.id);
									FireClick(jQuery('#AcyLienImage')[0]);
									jQuery('#AcyLienImage')[0].href = jQuery('#AcyLienImage')[0].href.replace(element.id, 'ACY_NAME_AREA');
								}
								else
								{
									jQuery(element).removeClass('nepasediter');
								}
							};
						}
					}

					var zoneBoutonEdition = document.createElement("div");
					zoneBoutonEdition.id = "zone_bouton_edition_" + zone.id;
					zoneBoutonEdition.style.position = "absolute";
					zone.appendChild(zoneBoutonEdition);
					jQuery(zoneBoutonEdition).addClass("acyeditor_zoneboutonedition");
					var boutonEdition = document.createElement("div");
					boutonEdition.id = "BoutonEdition_" + element.id;
					boutonEdition.title = titleEdition;
					zoneBoutonEdition.appendChild(boutonEdition);
					if (jQuery(element).hasClass('acyeditor_picture'))
					{
						jQuery(boutonEdition).addClass("acyeditor_editpicture");
					}
					else
					{
						jQuery(boutonEdition).addClass("acyeditor_edittext");
					}
				}
				if (jQuery(element).hasClass("acyeditor_delete"))
				{
					var zoneBoutonSuppression = document.createElement("div");
					zoneBoutonSuppression.id = "zone_bouton_suppression_" + zone.id;
					zoneBoutonSuppression.style.position = "absolute";
					zone.appendChild(zoneBoutonSuppression);
					jQuery(zoneBoutonSuppression).addClass("acyeditor_zoneeditdelete");
					var boutonSuppression = document.createElement("div");
					boutonSuppression.id = "BoutonSuppression_" + element.id;
					boutonSuppression.title = titleSuppression;
					boutonSuppression.onclick = function () {
						Suppression(id, element, boutonSuppression, texteSuppression);
					};
					zoneBoutonSuppression.appendChild(boutonSuppression);
					zone.onmousemove = function(e) { CheckToujoursAuDessus(id, e); };
					GetElement(id, boutonSuppression.id).addClass("acyeditor_editdelete");

					CreateZoneMore(zone, element, id, zoneBoutonSuppression);
				}

				SetMouseOver(id, zone);
				AdapteTaille(id, zone);
			}
		}
		else
		{
			GetElement(id, element.id).removeClass("acyeditor_delete");
			GetElement(id, element.id).removeClass("acyeditor_text");
			GetElement(id, element.id).removeClass("acyeditor_picture");
		}
	}
}

function CreateZoneMore(zone, element, id, zoneBoutonSuppression){
	var btnPlus = document.createElement("div");
	btnPlus.id = "BoutonPlus_" + element.id;
	btnPlus.title = titleBtnMore;
	btnPlus.className = "acyeditor_btnplus";
	jQuery(btnPlus).on('click', function (evt) {
		var zoneBody = jQuery('#' + id + "_ifr")[0].contentWindow.document.body.getElementsByTagName('*');
		jQuery(zoneBody).find('.acyeditor_text').addClass('nepasediter');
		jQuery(zoneBody).find('.acyeditor_picture').addClass('nepasediter');

		try{
			GetElement(id, btnPlus.parentElement.parentElement.parentElement.parentElement.id).children().addClass('noOpacity');
		} catch(err){
			GetElement(id, btnPlus.parentElement.parentElement.parentElement.id).addClass('noOpacity');
		}

		zoneActionActive = btnPlus;

		addActionsButtons(id, zoneBoutonSuppression, evt);
	});
	zoneBoutonSuppression.appendChild(btnPlus);
}

function addActionsButtons(id, zoneBoutonSuppression, evt){
	var zoneBody = jQuery('#' + id + "_ifr")[0].contentWindow.document.body;

	hideActionButtons(id, null, 'noClick', true);
	zoneBody.childNodes[0].className = "acyeditor_disable";

	var zoneFade = document.createElement("div");
	zoneFade.style.position = "absolute";
	zoneFade.className = "acyeditor_mask";
	zoneFade.style.width = zoneBody.clientWidth + "px";
	zoneFade.style.height = zoneBody.clientHeight + "px";
	zoneFade.onclick = function(){ hideAll(id); };
	zoneFade.id = "zoneFade";

	var zoneAction = document.createElement("div");
	zoneAction.style.position = "absolute";
	zoneAction.className = "acyeditor_action";
	zoneAction.id = "zoneAction";
	if (evt != null && evt != undefined){
		if(evt.clientX + 100 <= zoneBody.clientWidth) zoneAction.style.left = evt.clientX - 50 + "px";
		else zoneAction.style.left = zoneBody.clientWidth - 100 + "px";
		if(evt.layerY != undefined && evt.layerY > 0 && evt.layerY != evt.clientY){ decaY = evt.layerY; }
		else if(evt.offsetY != undefined){ decaY = evt.offsetY; }
		else{ decaY = 0; }
		zoneAction.style.top = evt.clientY - decaY + "px";
	}

	var zoneCopyAfterButton = document.createElement("div");
	zoneCopyAfterButton.title = titleBtnDupliAfter;
	zoneCopyAfterButton.id = "zoneCopyAfterButton";
	zoneCopyAfterButton.className = "acyeditor_copyButton acyeditor_copyButtonAfter";
	zoneCopyAfterButton.onclick = function(){ duplicateZone(id, zoneBoutonSuppression, 'after'); }

	zoneAction.appendChild(zoneCopyAfterButton);
	zoneFade.appendChild(zoneAction);
	zoneBody.appendChild(zoneFade);
}

function duplicateZone(id, zoneCopy, action){
	jQuery(zoneCopy).closest('acyeditor_delete').addClass('nepasediter');
	elem = jQuery(zoneCopy).closest('.acyeditor_delete');
	elemCopy = elem.clone();
	jQuery(elemCopy).find('.acyeditor_zoneeditionsuppression').remove();

	elemCopy[0].id = "";
	elementsZones = jQuery(elemCopy).find('*');
	for (indexZone = 0; indexZone < elementsZones.length; ++indexZone){
		elementsZones[indexZone].id = "";
	}

	if(action == 'before'){ jQuery(elem).before(elemCopy); }
	else{ jQuery(elem).after(elemCopy); }

	hideActionButtons(id, null, 'noClick', false);
	InitContent(id, txtSup, titleSup, titleEd, urlSite);
}

function hideAll(id){
	var zoneBody = jQuery('#' + id + "_ifr")[0].contentWindow.document.body;
	zoneBody.childNodes[0].className = "";
	var zoneGlob = zoneBody.getElementsByTagName('*');
	jQuery(zoneGlob).remove('.acyeditor_mask');
}

function hideActionButtons(id, e, zoneClick, protectEditor){
	canHide = false;
	if(zoneClick == 'editor'){
		if (e != null && e != undefined){
			srcEvent = e.srcElement? e.srcElement : (e.target ? e.target : e);
			parentDelete = jQuery(srcEvent).closest('.acyeditor_delete');
			if(parentDelete == null || parentDelete == undefined){
				canHide == true;
			}else if(zoneActionActive != null){
				childActive = jQuery(parentDelete).find('#'+zoneActionActive.id);
				if(childActive[0] == null || childActive[0] == undefined) canHide = true;
			}
		}else{
			canHide = true;
		}
	}

	if(zoneClick == 'outside' || zoneClick == 'noClick' || canHide){
		zoneBody = jQuery('#' + id + "_ifr")[0].contentWindow.document.body;
		zoneBody.childNodes[0].className = "";
		var zoneGlob = zoneBody.getElementsByTagName('*');
		jQuery('.acyeditor_action').remove();
		jQuery(zoneGlob).find('.noOpacity').removeClass('noOpacity');
		if(protectEditor == false) jQuery(zoneGlob).find('.nepasediter').removeClass('nepasediter');
	}
	if(zoneClick == 'outside') hideAll(id);
}

function FireClick(itemElement, arretRecursif){
	if (isBrowserIE7() || isBrowserIE8())
	{
		var popupTagContainer = parent.document.getElementById(boutonTags);
		var popupMediaBrowserContainer = parent.document.getElementById(boutonMediaBrowser);
		if (popupTagContainer != null
		 && popupTagContainer != undefined
		 && popupTagContainer.children[0] != null
		 && popupTagContainer.children[0] != undefined)
		{
			popupTagContainer.children[0].onclick = function () { IeCursorFix(); };
		}

		if (popupMediaBrowserContainer != null
		 && popupMediaBrowserContainer != undefined
		 && popupMediaBrowserContainer.children[0] != null
		 && popupMediaBrowserContainer.children[0] != undefined)
		{
			popupMediaBrowserContainer.children[0].onclick = function () { IeCursorFix(); };
		}

	}
	try
	{
		itemElement.click();
	}
	catch (err)
	{
		try
		{
			var ev = new Event({type: "click", target: itemElement, srcElement: itemElement});
			itemElement.fireEvent("click", ev);
		}
		catch (err2)
		{
			itemElement.fireEvent("click");
		}
	}

	if ((isBrowserIE7() || isBrowserIE8()) && isJoomla3 && arretRecursif != true && itemElement.id != "AcyLienImage")
	{
		setTimeout(function() {
			IeCursorFix();
			SetIgnoreDeselection();
			FireClick(itemElement, true);
		}, 100);
	}
	else if (isBrowserIE7() || isBrowserIE8())
	{
		var popupTagContainer = parent.document.getElementById(boutonTags);
		var popupMediaBrowserContainer = parent.document.getElementById(boutonMediaBrowser);
		if (popupTagContainer != null
		 && popupTagContainer != undefined
		 && popupTagContainer.children[0] != null
		 && popupTagContainer.children[0] != undefined)
		{
			popupTagContainer.children[0].onclick = function () { IeCursorFix(true); };
		}

		if (popupMediaBrowserContainer != null
		 && popupMediaBrowserContainer != undefined
		 && popupMediaBrowserContainer.children[0] != null
		 && popupMediaBrowserContainer.children[0] != undefined)
		{
			popupMediaBrowserContainer.children[0].onclick = function () { IeCursorFix(true); };
		}
	}
}

function CheckToujoursAuDessus(id, e){

	var iframe = jQuery('#' + id + '_ifr');

	e = e || iframe[0].contentWindow.event;
	var target = e.currentTarget || e.srcElement;
	var parent = GetElement(id, target.parentElement.id);
	var leftElement = parent.offset().left;
	var topElement =  parent.offset().top;
	var widthElement = parent.outerWidth();
	var heightElement = parent.outerHeight();

	if (e.pageX < leftElement
	 || e.pageX > leftElement + widthElement
	 || e.pageY < topElement
	 || e.pageY > topElement + heightElement)
	{
		EffaceZone(target);
	}
}

function EffaceZone(zone){
	if (zone != null && zone != undefined)
	{
		var enfants = zone.getElementsByTagName("*");
		for (i = 0; i < enfants.length; ++i)
		{
			enfants[i].style.display = "none";
		}
		zone.style.width = "0px";
		zone.style.height = "0px";
		zone.style.borderStyle = "hidden";
	}
}

function Suppression(id, element, boutonSuppression, texteSuppression){
	GetElement(id, boutonSuppression.parentElement.parentElement.parentElement.id).addClass('nepasediter');
	if (confirm(texteSuppression))
	{
		var idParent = boutonSuppression.parentElement.parentElement.id;
		if (element.tagName == "TD")
		{
			var parentTR = element;
			while (parentTR != null
				&& parentTR != undefined
				&& (parentTR.tagName != "TR"
				 || !jQuery(parentTR).hasClass("acyeditor_delete")))
			{
				parentTR = parentTR.parentElement;
			}
			if (parentTR != null && parentTR != undefined)
			{
				parentTR.parentElement.removeChild(parentTR);
			}
		}
		else
		{
			element.parentElement.removeChild(element);
		}
		Sauvegarde(id);
		ResizeIframe(id);
	}
}

function SetMouseOver(id, zone){
	zone.parentElement.onmouseover = function() { AdapteTaille(id, zone);};
}

function AdapteTaille(id, zone){
	if (zone != null && zone.parentElement != null)
	{
		zone.style.display = "";

		zone.style.width = "0px";
		zone.style.height = "0px";

		var parentZone = zone.parentElement;
		if (parentZone.tagName == "TD")
		{
			var parentTR = parentZone;
			while (parentTR != null
				&& parentTR != undefined
				&& (parentTR.tagName != "TR"
				 || !jQuery(parentTR).hasClass("acyeditor_delete")))
			{
				parentTR = parentTR.parentElement;
			}
			if (parentTR != null && parentTR != undefined)
			{
				parentZone = parentTR;
				var zonesTaille = jQuery(parentZone).find(".acyeditor_zoneeditionsuppression");
				for (indexZoneTaille = 0; indexZoneTaille < zonesTaille.length; ++indexZoneTaille)
				{
					if (zonesTaille[indexZoneTaille].id != zone.id)
					{
						zonesTaille[indexZoneTaille].style.display = "none";
					}
				}
			}
		}

		var parent = GetElement(id, parentZone.id);
		var left = parent.offset().left;
		var top = parent.offset().top;
		var widthZone = (parent.outerWidth() - 2);
		var heightZone = (parent.outerHeight());

		if (widthZone >= 0)
		{
			zone.style.width = widthZone + "px";
		}
		if (heightZone >= 0)
		{
			zone.style.height = heightZone + "px";
		}
		zone.style.left = left + "px";
		zone.style.top = top + "px";

		var enfants = zone.getElementsByTagName("*");
		for (i = 0; i < enfants.length; ++i)
		{
			enfants[i].style.display = "block";
			if (enfants[i].tagName == "A")
			{
				enfants[i].style.width = widthZone;
				enfants[i].style.height = heightZone;
			}
		}

		zone.style.borderStyle = "";

		var zoneBoutonEdition = null;
		try
		{
			zoneBoutonEdition = jQuery(zone).find(".acyeditor_zoneboutonedition")[0];
		}
		catch (err)
		{
			var enfantsZone = zone.children;
			for (indexEnfantZone = 0; indexEnfantZone < enfantsZone.length; ++indexEnfantZone)
			{
				if (jQuery(enfantsZone[indexEnfantZone]).hasClass("acyeditor_zoneboutonedition"))
				{
					zoneBoutonEdition = enfantsZone[indexEnfantZone];
				}
			}
		}
		if (zoneBoutonEdition != null && zoneBoutonEdition != undefined)
		{
			var parentReel = GetElement(id, zone.parentElement.id);
			var leftReel = parentReel.offset().left - left;
			var topReel = parentReel.offset().top - top;
			var widthZoneReel = (parentReel.outerWidth() - 2);
			var heightZoneReel = (parentReel.outerHeight() - 2);

			if (widthZoneReel >= 0)
			{
				zoneBoutonEdition.style.width = widthZoneReel + "px";
			}
			if (heightZoneReel >= 0)
			{
				zoneBoutonEdition.style.height = heightZoneReel + "px";
			}
			zoneBoutonEdition.style.left = leftReel + "px";
			zoneBoutonEdition.style.top = topReel + "px";
		}
	}
}

function GetNewId(id) {
	for (i = 1; i < 1000; ++i)
	{
		var identifiant = "zone_" + i;
		if (Existe(id, identifiant) == false)
		{
			return identifiant;
		}
	}
	return null;
}

function Existe(id, itemId){
	var idIframe = id + "_ifr";
	var element = undefined;
	var element2 = undefined;
	try
	{
		element = document.getElementById(itemId);
	}
	catch (err)
	{
	}
	try
	{
		element2 = jQuery('#' + idIframe)[0].contentWindow.document.getElementById(itemId);
	}
	catch (err2)
	{
	}

	return (element != null && element != undefined || element2 != null && element2 != undefined);
}

function GetElement(id, itemId){
	var idIframe = id + "_ifr";
	return jQuery('#' + idIframe).contents().find('#' + itemId);
}

function SetOnClick(id, element){
	element.onclick = function(e) { ClickTemplateCKEditor(id, element.id, e);};
	if (isBrowserIE())
	{
		element.onmousedown = null;
	}
}

var editor;
function ClickTemplateCKEditor(id, idElement, e){
	var idIframe = id + "_ifr";
	ignoreDeselection = false;
	CheckDeselection(id, e)
	var elementToEditJQ = GetElement(id, idElement);
	var elementToEdit = elementToEditJQ[0];
	if (elementToEdit != undefined)
	{
		if (elementToEdit.className.indexOf('nepasediter') < 0)
		{
			var okPourEdition = true;
			var editionEnCours = GetElement(id, 'edition_en_cours');
			if (editionEnCours[0] != null && editionEnCours[0] != undefined)
			{
				if (editor != null && editor != undefined)
				{
					editor.destroy();
					editor = null;
				}
				var editionEnCoursParentJQ = GetElement(id, editionEnCours[0].parentElement.id);
				editionEnCoursParentJQ.removeClass('acyeditor_enedition');
				editionEnCoursParentJQ.addClass('acyeditor_text');
				SetOnClick(id, editionEnCoursParentJQ[0]);
				editionEnCours[0].outerHTML = editionEnCours[0].innerHTML;
				okPourEdition = false;
			}

			if (okPourEdition)
			{
				var zone = GetElement(id, "ZoneEditionSuppression_" + elementToEdit.id);

				elementToEditJQ.removeClass('acyeditor_editablehover');
				zone.removeClass('acyeditor_zoneeditionsuppressionhover');

				if (zone[0] != null && zone[0] != undefined)
				{
					zone.remove();
				}
				var code = elementToEdit.innerHTML;

				var iframeCKEDITOR = jQuery('#' + idIframe)[0].contentWindow.CKEDITOR;

				var headerIFrame = jQuery('#' + idIframe)[0].contentWindow.document;
				headerIFrame = headerIFrame.head || headerIFrame;
				var urlBase = headerIFrame.getElementsByTagName("base")[0].href;

				var borderSize = 1;
				var left = elementToEditJQ.css("padding-left");
				var right = elementToEditJQ.css("padding-right");
				var top = elementToEditJQ.css("padding-top");
				var bottom = elementToEditJQ.css("padding-bottom");
				var leftPad = (elementToEditJQ.css("padding-left").replace("px", "") - borderSize);
				var rightPad = (elementToEditJQ.css("padding-right").replace("px", "") - borderSize);
				var topPad = (elementToEditJQ.css("padding-top").replace("px", "") - borderSize);
				var bottomPad = (elementToEditJQ.css("padding-bottom").replace("px", "") - borderSize);
				leftPad = leftPad < 0 ? 0 : leftPad;
				rightPad = rightPad < 0 ? 0 : rightPad;
				topPad = topPad < 0 ? 0 : topPad;
				bottomPad = bottomPad < 0 ? 0 : bottomPad;
				elementToEdit.innerHTML = "<div id='edition_en_cours' contenteditable='true' style='border:solid " + borderSize + "px orange;padding:" + topPad + "px " + rightPad + "px " + bottomPad + "px " + leftPad + "px;margin:-" + top + " -" + right + " -" + bottom + " -" + left + ";color:inherit;background:inherit;font:inherit;text-indent:inherit;text-decoration:inherit;text-transform:inherit;text-justify:inherit;text-kashida-space:inherit;text-overflow:inherit;text-shadow:inherit;text-underline-position:inherit;unicode-bidi:inherit;word-spacing:inherit;writing-mode:inherit;word-break:inherit;word-wrap:inherit;zoom:inherit;marker-offset:inherit;marks:inherit;quotes:inherit;table-layout:inherit;text-align-last:inherit;text-autospace:inherit;outline:inherit;overflow:inherit;min-height:inherit;max-height:inherit;line-break:inherit;letter-spacing:inherit;layout-flow:inherit;layout-grid:inherit;line-height:inherit;white-space:inherit;text-align:inherit;direction:inherit;list-style:inherit;float:inherit;ime-mode:inherit;layer-background-color:inherit;layer-background-image:inherit;filter:inherit;behavior:inherit;position:inherit;clear:inherit;clip:inherit;cursor:inherit;vertical-align:inherit'>" + code + "</div><div id='bottom' style='width:" + largeurMenuInline + "px;position:absolute'></div>";
				iframeCKEDITOR.disableAutoInline = true;

				var extraPluginsCKEditor = 'sharedspace';
				var toolbarGroupsCKEditor = [{ name: 'mode' },
											 { name: 'undo' },
											 { name: 'links' }];

				extraPluginsCKEditor += ',acymediabrowser';

				if (!acyeditor_listmode && isTagAllowed)
				{
					extraPluginsCKEditor += ',addtag';
					toolbarGroupsCKEditor.push({ name: 'insert', groups: [ "acymediabrowser", "addtag" ]});
				}
				else
				{
					toolbarGroupsCKEditor.push({ name: 'insert', groups: [ "acymediabrowser" ]});
				}
				toolbarGroupsCKEditor.push({ name: 'basicstyles' },
											{ name: 'colors' },
											'/',
											{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks' ] },
											{ name: 'align' },
											{ name: 'styles' });
				if (acyeditor_templatemode)
				{
					toolbarGroupsCKEditor.push({ name: 'templatemode', groups: [ "textarea", "picturearea", "deletearea", "-", "showarea" ] });
				}

				var elementEditor = GetElement(id, "edition_en_cours")[0];
				var xEditor = elementEditor.offsetLeft;
				var yEditor = elementEditor.offsetTop;
				var parentOffset = elementEditor.offsetParent;
				while (parentOffset != null && parentOffset != undefined)
				{
					xEditor = xEditor + parentOffset.offsetLeft;
					yEditor = yEditor + parentOffset.offsetTop;
					parentOffset = parentOffset.offsetParent;
				}
				var largeurEditor = elementEditor.clientWidth;
				var newX = ((largeurEditor - largeurMenuInline) / 2);
				if (newX + largeurMenuInline > window.outerWidth)
				{
					newX = window.outerWidth - largeurMenuInline;
				}
				else if (newX + xEditor < 0)
				{
					newX = 0;
				}
				GetElement(id, 'bottom')[0].style.marginLeft = newX + "px";
				GetElement(id, 'bottom')[0].style.marginTop = (((GetElement(id, 'edition_en_cours').css("margin-bottom").replace("px", "") - 1) + 1) * -1) + "px";

				var topValue = "";
				if (yEditor < 70)
				{
					topValue = "bottom";
				}

				extraPluginsCKEditor += ',stylesheetparser';
				if(inlineSource == 1) extraPluginsCKEditor += ',sourcedialog';

				if(pasteType == 'plain'){
					pastePlain = true;
					pasteWordSimple = false;
				} else if(pasteType == 'simpleStyle'){
					pastePlain = false;
					pasteWordSimple = true;
				}

				if(acyEnterMode == 'p'){ enterM = CKEDITOR.ENTER_P;}
				else if(acyEnterMode == 'div'){ enterM = CKEDITOR.ENTER_DIV;}
				else{ enterM = CKEDITOR.ENTER_BR; }

				editor = iframeCKEDITOR.inline('edition_en_cours',
				{
					toolbarGroups: toolbarGroupsCKEditor,
					removeButtons: 'Cut,Copy,Paste,Blockquote,RemoveFormat,Subscript,Superscript,Table,HorizontalRule,SpecialChar,Font,Symbol',
					removePlugins: 'contextmenu,liststyle,tabletools,image,forms',
					filebrowserImageUploadUrl : urlBase + urlAcyeditor + 'kcfinder/upload.php?type=images',
					extraPlugins: extraPluginsCKEditor,
					floatSpaceDockedOffsetX : newX,
					sharedSpaces: { top: topValue },
					forcePasteAsPlainText: pastePlain,
					pasteFromWordRemoveFontStyles: pasteWordSimple,
					enterMode: enterM
				});

				editor.on('instanceReady',function(){
					var zoneEdition = GetElement(id, "edition_en_cours");
					zoneEdition[0].title = "";

					editor.on('change',function(e){ CleanEditorContent(id, e); IeCursorFix(); });
					GetElement(id, "edition_en_cours")[0].onkeyup = function () { IeCursorFix(); };
					GetElement(id, "edition_en_cours")[0].onclick = function () { IeCursorFix(); };
					rangeIE = undefined;
					IeCursorFix();

					editor.on('selectionChange', function(e) { IeCursorFix(); });
					editor.on('change', function(e) { ResizeIframe(id); });

					try
					{
						GetElement(id, "cke_edition_en_cours").find(".cke_inner .cke_toolbar .cke_button__bold")[0].click();
						GetElement(id, "cke_edition_en_cours").find(".cke_inner .cke_toolbar .cke_button__bold")[0].click();
					}
					catch (e)
					{
						eval(GetElement(id, "cke_edition_en_cours").find(".cke_inner .cke_toolbar .cke_button__bold")[0].onclick);
						eval(GetElement(id, "cke_edition_en_cours").find(".cke_inner .cke_toolbar .cke_button__bold")[0].onclick);
					}
					editor.resetUndo();
				});

				if (zone[0] != null && zone[0] != undefined)
				{
					elementToEdit.appendChild(zone[0]);
				}
				elementToEditJQ.removeClass('acyeditor_text');
				setTimeout(function() {
					elementToEditJQ.addClass('acyeditor_enedition');

					var inlineMenu = GetElement(id, "cke_edition_en_cours")[0];
					if (inlineMenu != undefined && inlineMenu != null)
					{
						inlineMenu.onclick = function() { IgnoreDeselection(id); };
						if (isBrowserIE())
						{
							inlineMenu.onmousedown = function (e) { IgnoreDeselection(id) };
						}
					}
				}, 200);

				elementToEdit.onclick = function (e) { IgnoreDeselection(id) };
				if (isBrowserIE())
				{
					elementToEdit.onmousedown = function (e) { IgnoreDeselection(id) };
				}
			}
		}
		else
		{
			elementToEditJQ.removeClass('nepasediter');
		}
	}
}

var ignoreDeselection = false;
function IgnoreDeselection(id)
{
	ignoreDeselection = true;

	setTimeout(function() {
		var popups = jQuery('#' + id + '_ifr').contents().find('.cke_editor_edition_en_cours_dialog');
		if (popups != undefined && popups != null){
			for (indexPopup = 0; indexPopup < popups.length; ++ indexPopup)
			{
				if (popups[indexPopup].style.display != "none")
				{
					popups[indexPopup].onclick = function () { IgnoreDeselection(id); };
				}
			}
		}
	}, 200);
}

function SetIgnoreDeselection()
{
	ignoreDeselection = true;
}

function CheckDeselection(id, e){
	var idIframe = id + "_ifr";
	var div = document.getElementsByTagName('body')[0];
	if (e != null && e != undefined)
	{
		div = e.srcElement? e.srcElement : (e.target ? e.target : e);
	}

	var parentElement = div;
	var acyeditor_enedition = false;
	while (parentElement != null && parentElement != undefined)
	{
		if ((parentElement.className && parentElement.className.indexOf('acyeditor_enedition') >= 0)
		 || parentElement.id == boutonTags
		 || ClickSurPopup(parentElement))
		{
			acyeditor_enedition = true;
		}
		if (parentElement.parentElement == null && parentElement.tagName != "HTML")
		{
			acyeditor_enedition = true;
		}
		parentElement = parentElement.parentElement;
	}
	if (!acyeditor_enedition)
	{
		ValidationModifications(id);
	}
	else
	{
		ignoreDeselection = false;
	}
}

function ClickSurPopup(element){
	return ((element.className  && (element.className.indexOf('cke_dialog_body') >= 0
		 || element.className.indexOf('cke_dialog_background_cover') >= 0))
		 || element.id == 'cke_edition_en_cours');
}

function CleanEditorContent(id, e){
	var idIframe = id + "_ifr";
	var code = (editor != null && editor != undefined) ? editor.getData() : "";
	var fauxDiv = jQuery('#' + idIframe)[0].contentWindow.document.createElement("div");
	fauxDiv.innerHTML = code;
	code = fauxDiv.innerHTML;
	var enfantsFauxDiv = fauxDiv.getElementsByTagName("*");
	for (indexEnfantsFauxDiv = 0; indexEnfantsFauxDiv < enfantsFauxDiv.length; ++indexEnfantsFauxDiv)
	{
		if (enfantsFauxDiv[indexEnfantsFauxDiv].className != "")
		{
			jQuery(enfantsFauxDiv[indexEnfantsFauxDiv]).removeClass("acyeditor_delete");
			jQuery(enfantsFauxDiv[indexEnfantsFauxDiv]).removeClass("acyeditor_picture");
			jQuery(enfantsFauxDiv[indexEnfantsFauxDiv]).removeClass("acyeditor_text");
			if (jQuery(enfantsFauxDiv[indexEnfantsFauxDiv]).hasClass("acyeditor_zoneeditionsuppression"))
			{
				enfantsFauxDiv[indexEnfantsFauxDiv].outerHTML = "";
			}
		}
		if (Existe(id, enfantsFauxDiv[indexEnfantsFauxDiv].id))
		{
			enfantsFauxDiv[indexEnfantsFauxDiv].removeAttribute("id");
		}
	}

	if (fauxDiv.innerHTML != code)
	{
		editor.setData(fauxDiv.innerHTML);
	}
}

function ValidationModifications(id){
	if (ignoreDeselection == false)
	{
		if (!isJoomla3
		 || !(isBrowserIE7() || isBrowserIE8()))
		{
			rangeIE = undefined;
		}
		var idIframe = id + "_ifr";
		var elementJQ = jQuery('#' + idIframe).contents().find('.acyeditor_enedition');
		var element = elementJQ[0];
		if (element != null && element != undefined)
		{
			var popup = false;
			var popups = jQuery('#' + idIframe).contents().find('.cke_editor_edition_en_cours_dialog');
			if (popups != undefined && popups != null){
				for (indexPopup = 0; indexPopup < popups.length; ++ indexPopup)
				{
					if (popups[indexPopup].style.display != "none")
					{
						popup = true;
					}
				}
			}
			if (popup == false)
			{
				var code = "";
				if (editor != null && editor != undefined)
				{
					code = editor.getData();
					editor.destroy();
					editor = null;
					var zone = GetElement(id, "ZoneEditionSuppression_" + element.id);
					if (zone != null && zone != undefined)
					{
						zone.remove();
					}
					element.innerHTML = code;
					if (zone[0] != null && zone[0] != undefined)
					{
						element.appendChild(zone[0]);
					}
				}
				elementJQ.removeClass('acyeditor_enedition');
				elementJQ.addClass('acyeditor_text');
				SetOnClick(id, element);
				AdapteTaille(id, zone);

				Sauvegarde(id);
			}
		}
	}
	else
	{
		ignoreDeselection = false;
	}
}

function Sauvegarde(id)
{
	var idIframe = id + "_ifr";
	var newNoeud = jQuery('#' + idIframe)[0].contentWindow.document.body.cloneNode(true);
	var elements = newNoeud.getElementsByTagName('*');
	for (i = 0; i < elements.length; ++i)
	{
		if (jQuery(elements[i]).hasClass("acyeditor_zoneeditionsuppression"))
		{
			elements[i].outerHTML = "";
			i = i - 1;
		}
	}
	jQuery('#' + id)[0].value = newNoeud.innerHTML;
}



function SetTitleTemplate(onlyRemove){
	if (acyeditor_templatemode)
	{
		var iframe = jQuery('#edition_en_cours')[0].parentElement.getElementsByTagName("iframe")[0];
		iframe = jQuery(iframe);

		if (iframe[0] != undefined)
		{
			var allZones = iframe[0].contentWindow.document.getElementsByTagName("*");
			for (indexZone = 0; indexZone < allZones.length; ++indexZone)
			{
				jQuery(allZones[indexZone]).removeAttr("title");
			}

			if (onlyRemove != true)
			{
				var zonesDelete = iframe.contents().find(".acyeditor_delete");
				for (indexZone = 0; indexZone < zonesDelete.length; ++indexZone)
				{
					zonesDelete[indexZone].title = tooltipTemplateDelete;
					if (isBrowserIE())
					{
						var children = zonesDelete[indexZone].getElementsByTagName("*");
						for (indexchild = 0; indexchild < children.length; ++indexchild)
						{
							children[indexchild].title = zonesDelete[indexZone].title;
						}
					}
				}
				var zonesTexte = iframe.contents().find(".acyeditor_text");
				for (indexZone = 0; indexZone < zonesTexte.length; ++indexZone)
				{
					zonesTexte[indexZone].title = tooltipTemplateText;
					if (isParentSupprimable(zonesTexte[indexZone]))
					{
						zonesTexte[indexZone].title =  tooltipTemplateText + "\r\n" + tooltipTemplateDelete;
					}
					if (isBrowserIE())
					{
						var children = zonesTexte[indexZone].getElementsByTagName("*");
						for (indexchild = 0; indexchild < children.length; ++indexchild)
						{
							children[indexchild].title = zonesTexte[indexZone].title;
						}
					}
				}
				var zonesPicture = iframe.contents().find(".acyeditor_picture");
				for (indexZone = 0; indexZone < zonesPicture.length; ++indexZone)
				{
					zonesPicture[indexZone].title = tooltipTemplatePicture;
					if (isParentSupprimable(zonesPicture[indexZone]))
					{
						zonesPicture[indexZone].title = tooltipTemplatePicture + "\r\n" + tooltipTemplateDelete;
					}
					if (isBrowserIE())
					{
						var children = zonesPicture[indexZone].getElementsByTagName("*");
						for (indexchild = 0; indexchild < children.length; ++indexchild)
						{
							children[indexchild].title = zonesPicture[indexZone].title;
						}
					}
				}
			}
		}
	}
}

function SetStateForSelection(){
	if (acyeditor_templatemode)
	{
		var alltemplatebuttons = jQuery(".boutontemplate_text");
		for (indexTemplateButton = 0;indexTemplateButton < alltemplatebuttons.length; ++indexTemplateButton)
		{
			SetStateForSelectionForClasse(jQuery(alltemplatebuttons[indexTemplateButton]), "acyeditor_text");
		}
		alltemplatebuttons = jQuery(".boutontemplate_picture");
		for (indexTemplateButton = 0;indexTemplateButton < alltemplatebuttons.length; ++indexTemplateButton)
		{
			SetStateForSelectionForClasse(jQuery(alltemplatebuttons[indexTemplateButton]), "acyeditor_picture");
		}
		alltemplatebuttons = jQuery(".boutontemplate_delete");
		for (indexTemplateButton = 0;indexTemplateButton < alltemplatebuttons.length; ++indexTemplateButton)
		{
			SetStateForSelectionForClasse(jQuery(alltemplatebuttons[indexTemplateButton]), "acyeditor_delete");
		}
	}
}

function SetStateForSelectionForClasse(item, classe){
	var acyframe = jQuery('#edition_en_cours')[0].parentElement.getElementsByTagName("iframe")[0];
	var node = null;
	if (isBrowserIE())
	{
		node = GetParentForClass(anchorNodeIE, classe);
	}
	else if (acyframe != null
			&& acyframe != undefined
			&& acyframe.contentWindow != null
			&& acyframe.contentWindow != undefined
			&& acyframe.contentWindow.getSelection)
	{
		var sel = acyframe.contentWindow.getSelection();
		if (sel.anchorNode) {
			node = GetParentForClass(sel.anchorNode, classe);
		}
	}

	item.removeClass("cke_button_on");
	item.removeClass("cke_button_off");
	if (node != null && node != undefined)
	{
		item.removeClass("cke_button_disabled");
		if (jQuery(node).hasClass(classe))
		{
			item.addClass("cke_button_on");
		}
		else
		{
			item.addClass("cke_button_off");
		}
	}
	else if (!item.hasClass("cke_button_disabled"))
	{
		item.addClass("cke_button_disabled");
	}
}

function GetParentForClass(item, classe){
	var parent = item;
	while (parent != null && parent != undefined)
	{
		var elementModifiables = jQuery(parent).find(".acyeditor_delete, .acyeditor_text, .acyeditor_picture").length;
		if (jQuery(parent).find(".acyeditor_delete, .acyeditor_text, .acyeditor_picture").length > 0)
		{
			var tdEditableTotaux = jQuery(parent).find("td.acyeditor_text, td.acyeditor_picture").length;
			var tdEditable = jQuery(parent).find("table td.acyeditor_text, table td.acyeditor_picture").length;
			if (parent.tagName != "TR"
			 || classe != "acyeditor_delete"
			 || elementModifiables != tdEditableTotaux
			 || tdEditable != 0)
			{
				parent = null;
			}
		}
		if (parent != null
		 && (parent.tagName == "DIV"
			|| parent.tagName == "TABLE"
			|| parent.tagName == "TR"
			&& classe == "acyeditor_delete"
			|| parent.tagName == "TD"
			&& classe != "acyeditor_delete"))
		{
			var vraiParent = parent != null ? parent.parentElement || parent.parentNode : null;
			while (vraiParent != null && vraiParent != undefined)
			{
				if (parent.tagName == "TD"
				 && vraiParent.tagName == "TR"
				 && jQuery(vraiParent).hasClass("acyeditor_delete"))
				{
					return parent;
				}
				if (jQuery(vraiParent).hasClass("acyeditor_delete")
				 || jQuery(vraiParent).hasClass("acyeditor_text")
				 || jQuery(vraiParent).hasClass("acyeditor_picture"))
				{
					return vraiParent;
				}
				vraiParent = vraiParent != null ? vraiParent.parentElement || vraiParent.parentNode : null;
			}

			return parent;
		}
		parent = parent != null ? parent.parentElement || parent.parentNode : null;
	}
	return parent;
}

function isParentSupprimable(element){
	var supprimable = false;
	var parent = element;
	while (parent != null && parent != undefined)
	{
		if (parent.className != null && parent.className != undefined && parent.className.indexOf("acyeditor_delete") >= 0)
		{
			supprimable = true;
		}
		parent = parent.parentElement || parent.parentNode;
	}
	return supprimable;
}

function AddRemoveTemplateCss(){
	if (acyeditor_templatemode)
	{
		ShowTemplateCss(!IsTemplateCssShown());
	}
}

function IsTemplateCssShown(){
	if (acyeditor_templatemode)
	{
		var boutonShow = jQuery(".boutontemplate_show")[0];
		if (boutonShow != null
		 && boutonShow != undefined
		 && boutonShow.className != null
		 && boutonShow.className != undefined
		 && boutonShow.className.indexOf("cke_button_on") >= 0)
		{
			return true;
		}
	}
	return false;
}

function ShowTemplateCss(show){
	if (acyeditor_templatemode)
	{
		var iframe = jQuery('#edition_en_cours')[0].parentElement.getElementsByTagName("iframe")[0];
		iframe = jQuery(iframe);
		var boutonShow = jQuery(".boutontemplate_show")[0];
		templateShown = show;

		if (!show)
		{
			var link = iframe.contents().find("#AcyTemplateCss")[0];
			if (link != null
			 && link != undefined)
			{
				jQuery(link).remove();
			}
			SetTitleTemplate(true);

			jQuery(boutonShow).removeClass("cke_button_on");
			jQuery(boutonShow).addClass("cke_button_off");
		}
		else
		{
			var headEditor = iframe[0].contentWindow.document;
			headEditor = headEditor.head || headEditor;
			var base = headEditor.getElementsByTagName("base")[0];
			var link1 = document.createElement("link");
			link1.type = "text/css";
			link1.rel = "stylesheet";
			link1.id = "AcyTemplateCss";
			link1.href =  base.href + urlAcyeditor + "css/acyeditor_template.css?v=" + acyVersion;
			headEditor.appendChild(link1);
			SetTitleTemplate();

			jQuery(boutonShow).removeClass("cke_button_off");
			jQuery(boutonShow).addClass("cke_button_on");
		}
	}
}

function AcyGetData(){
	for(var myField in CKEDITOR.instances){
		return CKEDITOR.instances[myField].getData();
	}
}
