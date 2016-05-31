jQuery(document).ready(function(){
	if(jQuery("#descriptionCounter").val())
		jQuery("#descriptionCounter").val(parseInt(jQuery("#description").attr('maxlength')) - jQuery("#description").val().length);
	if(jQuery("#descriptionCounterShort").val())
		jQuery("#descriptionCounterShort").val(parseInt(jQuery("#short_description").attr('maxlength')) - jQuery("#short_description").val().length);

});
																							

function addSelectedSubcategory(){
	var option = jQuery('#subcategories option:selected');
	option = option.clone();
	/*var text = getMainOptionName()+ option.text();
	option.text(text);*/
	option.appendTo("#mainSubcategory");
	jQuery("#mainSubcategory").val(option.val());
	
	
	jQuery('#subcategories option:selected').appendTo('#selectedSubcategories');
	jQuery("#selectedSubcategories").attr('selectedIndex', '-1');
		
}

function removeSelectedCategory(){
	var option = jQuery('#selectedSubcategories option:selected');
	jQuery('#mainSubcategory option[value="'+option.val()+'"]').remove();

	jQuery('#selectedSubcategories option:selected').appendTo('#subcategories');
	jQuery("#subcategories").attr('selectedIndex', '-1');

}

function getMainOptionName(){
	var name="";
	for(var i=0;i<10;i++){
		var selectObjectId="#category"+i;
		if(jQuery(selectObjectId).length>0){
			name+=jQuery(selectObjectId+" option:selected").text() + " > ";
		}
	}
	return name;
}

function displaySubcategories(id, level, maxLevel){
	
	var categoryId = jQuery("#"+id).val();

	if(!categoryId)
		categoryId=0;
	//invalidate subcategories level
	for(var i=level+1;i<=maxLevel;i++){
		jQuery("#company_categories-level-"+i).html('');
	}
	jQuery("#company_categories-level-"+(level+1)).html("<div style='width:20px;margin: 0 auto;'><img align='center' src='"+imageRepo+"/assets/img/loading.gif'  /></div>");

	var postParameters='';
	
	postParameters +="&categoryId="+categoryId;
	//alert(postParameters);
	
	var postData='';
	if(typeof isProfile === 'undefined')
		postData='&option=com_jbusinessdirectory&task=company.getSubcategories'+postParameters;
	else
		postData='&option=com_jbusinessdirectory&task=managecompany.getSubcategories'+postParameters;
//	alert(baseUrl + postData);
	jQuery.post(baseUrl, postData, processDisplaySubcategoriesResponse);
	//jQuery('#frmFacilitiesFormSubmitWait').show();
}

function processDisplaySubcategoriesResponse(responce){
	var xml = responce;
	//alert(xml);
	//jQuery('#frmFacilitiesFormSubmitWait').hide();
	jQuery(xml).find('answer').each(function()
	{
		if( jQuery(this).attr('error') == '1' )
		{
			jQuery('#frm_error_msg_facility').className='text_error';
			jQuery('#frm_error_msg_facility').html(jQuery(this).attr('errorMessage'));
			jQuery('#frm_error_msg_facility').show();

		}
		else if( jQuery(this).attr('error') == '0' )
		{
			
			jQuery("#subcategories").html(jQuery(this).attr('content_categories'));
			removeSelectedCategories();
			//clear current level
			jQuery("#company_categories-level-"+jQuery(this).attr('category-level')).html('');
			//clear next level
			level = 1+parseInt(jQuery(this).attr('category-level'));
			jQuery("#company_categories-level-"+level).html('');
			if(jQuery(this).attr('isLastLevel') != '1'){
				jQuery("#company_categories-level-"+jQuery(this).attr('category-level')).html(jQuery(this).attr('content_select_categories'));
				
			}
		}
	});
}

function removeSelectedCategories(){
	jQuery("#mainSubcategory > option").each(function() {
		jQuery("#subcategories option[value="+jQuery(this).val()+"]").remove();
	});

}

function calculateLenght(){

	var obj = jQuery("#description");

    var max = parseInt(obj.attr('maxlength'));
    if(obj.val().length > max){
        obj.val(obj.val().substr(0, obj.attr('maxlength')));
    }
  
 	jQuery("#descriptionCounter").val((max - obj.val().length));
}

function calculateLenghtShort(){

	var obj = jQuery("#short_description");

    var max = parseInt(obj.attr('maxlength'));
    if(obj.val().length > max){
        obj.val(obj.val().substr(0, obj.attr('maxlength')));
    }
  
 	jQuery("#descriptionCounterShort").val((max - obj.val().length));
}

function checkCompanyName(initialName, newName){
	if(initialName != newName){
		var postParameters='';
		
		postParameters +="&companyName="+newName;
		//alert(postParameters);
		var postData='';

		if(typeof isProfile === 'undefined')
			postData='&task=company.checkCompanyName'+postParameters;
		else
			postData='&task=managecompany.checkCompanyName'+postParameters;
	
		//jQuery.post(baseUrl, postData, processCheckCompanyNameResponse);
	}
}

function processCheckCompanyNameResponse(responce){
	var xml = responce;
	jQuery(xml).find('answer').each(function()
	{
		
		if( jQuery(this).attr('exists') == '1' )
		{
			//alert("exists");
			jQuery('#company_exists_msg').show();
			jQuery("#company-exists").val("1");
			if(jQuery(this).attr('claim') == '1'){
				jQuery('#claim_company_exists_msg').show();
			}
		}else{
			jQuery('#company_exists_msg').hide();
			jQuery("#company-exists").val("0");
			jQuery('#claim_company_exists_msg').hide();
		}
	});
}



