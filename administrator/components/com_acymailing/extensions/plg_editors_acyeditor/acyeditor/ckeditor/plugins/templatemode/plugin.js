(function() {
	var a= {
		exec:function(editor){
			SetClass("acyeditor_text", editor);
		}
	};
	var b= {
		exec:function(editor){
			SetClass("acyeditor_picture", editor);
		}
	};
	var c= {
		exec:function(editor){
			SetClass("acyeditor_delete", editor);
		}
	};
	var g= {
		canUndo: false,
		exec:function(editor){
			if (parent.AddRemoveTemplateCss)
			{
				AddRemoveTemplateCss();
			}
		}
	};
	var i={
		exec:function(editor){
			initAreas();
		}
	}
	var d='setText';
	var e='setPicture';
	var f='setDelete';
	var h='showAreas';
	var j='initAreas';

	CKEDITOR.plugins.add("templatemode",{
		init:function(editor){
			editor.addCommand(d,a);
			editor.addCommand(e,b);
			editor.addCommand(f,c);
			editor.addCommand(h,g);
			editor.addCommand(j,i);
			editor.ui.addButton("textarea",{label: parent.tooltipTemplateText,
											icon: this.path + "icons/icon-16-edittext.png",
											command:d,
											className: "boutontemplate_text",
											toolbar: "templatemode"});
			editor.ui.addButton("picturearea",{label: parent.tooltipTemplatePicture,
											 icon: this.path + "icons/icon-16-editpicture.png",
											 command:e,
											 className: "boutontemplate_picture",
											toolbar: "templatemode"});
			editor.ui.addButton("deletearea",{label: parent.tooltipTemplateDelete,
											icon: this.path + "icons/icon-16-delete.png",
											command:f,
											className: "boutontemplate_delete",
											toolbar: "templatemode"});
			editor.ui.addButton("showarea",{label: parent.tooltipShowAreas,
											icon: this.path + "icons/icon-16-show.png",
											command:h,
											className: "boutontemplate_show",
											toolbar: "templatemode"});
			editor.ui.addButton("initareas",{label:parent.tooltipInitAreas,
												icon: this.path + "icons/icon-16-initareas.png",
												command: j,
												className:"boutontemplate_initareas",
												toolbar: "templatemode"});


			editor.on( 'selectionChange', function() {
				SetAnchorNodeIE()
				if (parent.SetStateForSelection)
				{
					// refresh the buttons states
					parent.SetStateForSelection();
				}
			});
		}
	});

	// Remove all areas (classes) from the template
	function initAreas(){
		var removeConfirm = confirm(parent.confirmInitAreas);
		if(removeConfirm){
			var acyframe =  jQuery('#edition_en_cours')[0].parentElement.getElementsByTagName("iframe")[0];
			var zoneGlob = acyframe.contentWindow.document.body.getElementsByTagName('*');
			jQuery(zoneGlob).find('.acyeditor_text').removeClass('acyeditor_text');
			jQuery(zoneGlob).find('.acyeditor_picture').removeClass('acyeditor_picture');
			jQuery(zoneGlob).find('.acyeditor_delete').removeClass('acyeditor_delete');
		}
	}

	function SetClass(classe, editor){

		var acyframe =  jQuery('#edition_en_cours')[0].parentElement.getElementsByTagName("iframe")[0];

		var node = null;
		if (parent.isBrowserIE())
		{
			if (parent.anchorNodeIE == undefined)
			{
				SetAnchorNodeIE();
			}
			node = parent.GetParentForClass(parent.anchorNodeIE, classe);
		}
		else if (acyframe != null
				&& acyframe != undefined
				&& acyframe.contentWindow != null
				&& acyframe.contentWindow != undefined
				&& acyframe.contentWindow.getSelection)
		{
			// MOZILLA/NETSCAPE
			var sel = acyframe.contentWindow.getSelection();
			if (sel.anchorNode) {
				node = parent.GetParentForClass(sel.anchorNode, classe);
			}
		}
		SetClassNode(classe, editor, node);

		parent.SetStateForSelection();
	}

	function SetClassNode(classe, editor, node){
		if (node != null && node != undefined)
		{
			if (node.className != null && node.className != undefined && node.className.indexOf(classe) < 0)
			{
				if (classe == "acyeditor_text")
				{
					jQuery(node).removeClass("acyeditor_picture");
				}
				else if (classe == "acyeditor_picture")
				{
					jQuery(node).removeClass("acyeditor_text");
				}
				jQuery(node).addClass(classe);
			}
			else
			{
				jQuery(node).removeClass(classe);
			}
			parent.SetTitleTemplate();
		}
	}

	function SetAnchorNodeIE()
	{
		if (parent.isBrowserIE())
		{
			var acyframe =  jQuery('#edition_en_cours')[0].parentElement.getElementsByTagName("iframe")[0];
			parent.anchorNodeIE = undefined;
			if (acyframe != null
			 && acyframe != undefined
			 && acyframe.contentWindow.document != null
			 && acyframe.contentWindow.document != undefined
			 && acyframe.contentWindow.document.selection)
			{
				if (acyframe.contentWindow.document.selection.createRange().parentElement)
				{
					parent.anchorNodeIE = acyframe.contentWindow.document.selection.createRange().parentElement();
				}
				else if (acyframe.contentWindow.document.selection.createRange().item)
				{
					parent.anchorNodeIE = acyframe.contentWindow.document.selection.createRange().item(0);
				}
			}
		}
	}
})();
