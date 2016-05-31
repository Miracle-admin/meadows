(function ($) {
	var vm2w = {
		savefave: function(listid,product_id) {
			$.post(VM2W_AJAX_URL, {
				"option": "com_vm2wishlists",
				"task": "savefave",
				"format": "raw",
				"tmpl": "component",
				"listid": listid,
				"product_id": product_id
			}, function(data) {
				if (data) {
					jQuery('#fave'+listid+'-'+product_id).html(data);
				}
			});
		},
		delfave: function(listid,product_id) {
			$.post(VM2W_AJAX_URL, {
				"option": "com_vm2wishlists",
				"task": "delfave",
				"format": "raw",
				"tmpl": "component",
				"listid": listid,
				"product_id": product_id
			}, function(data) {
				if (data) {
					jQuery('#fave'+listid+'-'+product_id).html(data);
				}
			});
		},
		delfromlist: function(listid,product_id) {
			$.post(VM2W_AJAX_URL, {
				"option": "com_vm2wishlists",
				"task": "delfave",
				"format": "raw",
				"tmpl": "component",
				"listid": listid,
				"product_id": product_id
			}, function(data) {
				if (data) {
					jQuery('#brick'+product_id).hide();
				}
			});
		}
	};
	$.extend({"vm2w":vm2w});	
})(jQuery);
