(function ($) {
	var vmv = {
		rating: function(vendor_user_id, current_vote) {
			jQuery('#rate1').rating(JVA_AJAX_RATING_URL + game_id, {maxvalue:5,increment:.5,curvalue:current_vote});
		}
	};
	$.extend({"vmv":vmv});
})(jQuery);