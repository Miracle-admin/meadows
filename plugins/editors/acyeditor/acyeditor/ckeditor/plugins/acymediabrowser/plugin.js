(function() {
	var a= {
		exec:function(editor){
			if (parent.IeCursorFix)
			{
				parent.IeCursorFix();
			}
			if (parent.SetIgnoreDeselection)
			{
				parent.SetIgnoreDeselection();
			}
			var itemElement = document.getElementById('AcyLienMediaBrowser');
			if (itemElement)
			{
				if (FireClick)
					FireClick(itemElement);
			}else if(parent.FireClick)
			{
				var itemElement = parent.document.getElementById('AcyLienMediaBrowser');
				parent.FireClick(itemElement);
			}

		}
	},
	b='acymediabrowser';
	CKEDITOR.plugins.add(b,{
		init:function(editor){
			editor.addCommand(b,a);
			editor.ui.addButton("acymediabrowser",{label:'Images',
											icon: this.path + "icon-16-mediabrowser.png",
											command:b,
											toolbar: "insert"});

			editor.on( 'doubleclick', function( evt )
					{
							var element = evt.data.element;

							if ( element.is( 'img' ) ){
								var itemElement = document.getElementById('AcyLienMediaBrowser');
								if(itemElement){
									FireClick(itemElement);
								}else{
									itemElement = parent.document.getElementById('AcyLienMediaBrowser');
									parent.FireClick(itemElement);
								}
							}

					});
		}
	});

})();
