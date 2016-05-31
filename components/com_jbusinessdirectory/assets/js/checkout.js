
function switchMethod(method) {
	jQuery(".payment-form-list").each(function(){
		this.hide();
	});
	
	jQuery("#payment_form_"+method).show();
}

