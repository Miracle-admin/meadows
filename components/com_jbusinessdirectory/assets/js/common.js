


function updateCompanyRate(companyId, rateScore) {
	var postParameters = "";
	ratingId = getRatingId(companyId);
	if(ratingId==undefined){
		ratingId = 0;
	}
	postParameters += "&companyId=" + companyId;
	postParameters += "&rating=" + rateScore;
	postParameters += "&ratingId=" + ratingId;

	var postData = '&task=companies.updateRating' + postParameters;
	jQuery.post(baseUrl, postData, processRateResult);
}

function processRateResult(responce) {
	var xml = responce;
	// alert(xml);
	// jQuery('#frmFacilitiesFormSubmitWait').hide();
	jQuery(xml).find('answer')
			.each(
					function() {
						jQuery("#rateNumber" + jQuery(this).attr('id')).html(
								jQuery(this).attr('nrRatings'));
						jQuery("#rateNumber" + jQuery(this).attr('id'))
								.parent().show();
						jQuery('#rating-average').raty('start',	jQuery(this).attr('averageRating'));
						saveCookieRating(jQuery(this).attr('id'),jQuery(this).attr('ratingId'));
					});
}

function getRatingId(companyId){
	var ratings = getCookie("companyRatingIds");
	if(ratings==undefined)
		return;
	var ratingsIds = ratings.split('#');
	for(i=0;i<ratingsIds.length;i++){
		temp = ratingsIds[i].split(',');
		if(temp[0]==companyId)
			return temp[1];
	}
}

function saveCookieRating(companyId,reviewId){
	//alert(companyId);
	//alert(reviewId);
	var ratings= getCookie("companyRatingIds");
	if(ratings==undefined)
		ratings = companyId +','+reviewId+'#';
	//alert(ratings);
	var ratingsIds = ratings.split('#');
	var found = false;
	for(i=0;i<ratingsIds.length;i++){
		temp = ratingsIds[i].split(',');
		if(temp[0]==companyId)
			found = true;
	}
	if(!found){
		ratings = ratings + companyId +','+reviewId+'#';
	}
	setCookie("companyRatingIds", ratings, 60);
	//alert(ids);
}


function getCookie(c_name) {
	var i, x, y, ARRcookies = document.cookie.split(";");
	for (i = 0; i < ARRcookies.length; i++) {
		x = ARRcookies[i].substr(0, ARRcookies[i].indexOf("="));
		y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
		x = x.replace(/^\s+|\s+$/g, "");
		if (x == c_name) {
			return unescape(y);
		}
	}
}

function setCookie(c_name, value, exdays) {
	var exdate = new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value = escape(value)
			+ ((exdays == null) ? "" : "; expires=" + exdate.toUTCString());
	document.cookie = c_name + "=" + c_value;
}


jQuery.fn.center = function () {
    this.css("left", ( jQuery(window).width() - this.width() ) / 2+jQuery(window).scrollLeft() + "px");
    return this;
}


jQuery(document).ready(function(){
	//Turn radios into btn-group
	jQuery('.radio.btn-group label').addClass('btn');
	jQuery('.btn-group label:not(.active)').click(function()
	{
		var label = jQuery(this);
		var input = jQuery('#' + label.attr('for'));
	
		if (!input.prop('checked')) {
			label.closest('.btn-group').find('label').removeClass('active btn-success btn-danger btn-primary');
			if (input.val() == '') {
				label.addClass('active btn-primary');
			} else if (input.val() == 0) {
				label.addClass('active btn-danger');
			} else {
				label.addClass('active btn-success');
			}
			input.prop('checked', true);
		}
	});
	
	jQuery('.btn-group input[checked=checked]').each(function()
	{
		if (jQuery(this).val() == '') {
			jQuery('label[for=' + jQuery(this).attr('id') + ']').addClass('active btn-primary');
		} else if (jQuery(this).val() == 0) {
			jQuery('label[for=' + jQuery(this).attr('id') + ']').addClass('active btn-danger');
		} else {
			jQuery('label[for=' + jQuery(this).attr('id') + ']').addClass('active btn-success');
		}
	});
});