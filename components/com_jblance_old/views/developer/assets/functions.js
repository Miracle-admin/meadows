function ajax_execute(url_execute,values,target){
		$(target).animate({ opacity: 0.30 });
		//start send the post request
       $.post(url_execute,values,
    	function(data){         
	   		$(target).html(data);
	   		$('html,body').animate({ scrollTop: $(target).position()['top']-80}, { duration: 'fast', easing: 'swing'});
			$(target).animate({ opacity: 1.00 });
       	},"html");
      return false;
}


function filterURLValue(value){
	return value.toLowerCase().replace(/[Ã Ã¡Ã¢Ã£Ã¤Ã¥Ã¦ÇÄÃ€ÃÃ„]/gi, 'a').replace(/[Ã¨Ã©ÃªÃ«Ã‰ÃˆÃ‹]/gi, 'e').replace(/[Ã¬Ã­Ã®Ã¯Ä±Ä«ÃÃŒÃ]/gi, 'i').replace(/[(Ã°Ã²Ã³Ã´ÃµÃ¶Ã¸Å“Ã“Ã’Ã–)]/gi, 'o').replace(/[Ã¹ÃºÃ»Ã¼ÃšÃ™Ãœ]/gi, 'u').replace(/[Ã¾ÃŸ]/gi, 'b').replace(/[Ã§]/gi, 'c').replace(/[ÄŸ]/gi, 'g').replace(/[Ã±]/gi, 'n').replace(/[ÅŸ]/gi, 's').replace(/[Ã½Ã¿]/gi, 'y').replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, ' ');
}

function generateURLValue(value){
	new_value = filterURLValue(value.toLowerCase()).replace(/[ ]/gi,'-');
	return new_value;
}

function ajaxValidation(url,element,validating){

	//ml forced
	if(validating[element.attr('id')]['field_id']!= undefined) field_id = validating[element.attr('id')]['field_id'];
	else field_id = element.attr('id');
	//ml check
	if(element.val()!="") value = element.val();
	else value = $('.'+field_id+':[value!=""]').val();
	if(value == undefined) value = '';

	//comprovem que no hi hagi cap altre notificacio red
	if($('#container_'+field_id+' .notifications.red').length > 0) return true;
	
	$.post(url,
			{ 'validate' : validating[element.attr('id')]['entity'] , 'name' : validating[element.attr('id')]['name']  , 'value' : value },        
	   		function(data){
	   			if($('#container_'+field_id+' .notifications.icon-remove').length == 0){
		   			if(data==''){
		   				$('#container_'+field_id+' .notifications').addClass('icon-ok');
		   				$('#container_'+field_id+' .error-message').fadeOut();
						$('#container_'+field_id+' .error-message').addClass('hide');
		   			}else{
			   			if(data!="	" && $('#container_'+field_id+' .error-message:visible').length == 0 ){
			   				$('#container_'+field_id+' .notifications').removeClass('icon-ok');
			   				$('#container_'+field_id+' .notifications').addClass('icon-remove');
			   				$('#container_'+field_id+' .error-message').hide();
							$('#container_'+field_id+' .error-message').removeClass('hide');
							$('#container_'+field_id+' .error-message').fadeIn();
			   				$('#container_'+field_id+' .error-message').html(data);
			   			}
		   			}
	   			}
   			},
	   		'html');
}

function ajaxConditionalValidation(url,element,condition, value, value2, message, ignore_value){
	//comprovem que no hi hagi cap altre notificacio red
	if($('#container_'+element.attr('id')+' .notifications.red').length > 0) return true;
	$.post(url,
			{ 'value' : element.val(), 'condition' : condition, 'message' : message, 'condition_value' : value, 'condition_value2' : value2, 'ignore_value' : ignore_value },        
	   		function(data){ 
	   			if($('#container_'+element.attr('id')+' .notifications.icon-ok').length == 0){
					if(data==''){
		   				$('#container_'+element.attr('id')+' .notifications').addClass('icon-ok');
		   				$('#container_'+element.attr('id')+' .error-message').fadeOut();
						$('#container_'+element.attr('id')+' .error-message').addClass('hide');
		   			}else{
			   			if(data!="	" && $('#container_'+element.attr('id')+' .error-message:visible').length == 0 ){
			   				$('#container_'+element.attr('id')+' .notifications').removeClass('icon-ok');
			   				$('#container_'+element.attr('id')+' .notifications').addClass('icon-remove');
			   				$('#container_'+element.attr('id')+' .error-message').hide();
							$('#container_'+element.attr('id')+' .error-message').removeClass('hide');
							$('#container_'+element.attr('id')+' .error-message').fadeIn();
			   				$('#container_'+element.attr('id')+' .error-message').html(data);
			   			}
		   			}
	   			}
   			},
	   		'html');
}

function fieldValidation(field){
	var field_box = field.parent();
	var notification_box = field.parent().children('.notifications');
	var error_box = field.parent().children('.error-message');
	error_box.hide().removeClass('hide');
	
	var field_identificator = field.attr('id');
	if(field.hasClass('multilanguage')) field_identificator = field_identificator.substring(0,field_identificator.length-6); //delete locale from name
	
	if(typeof form_validation == "undefined" || typeof form_validation[field_identificator] == "undefined"){
		return true;
	}

	//Comprovem si es required i esta definit
	if(typeof form_validation[field_identificator]['required'] != "undefined" && form_validation[field_identificator]['required'] == true 
		&& ((!field.hasClass('multilanguage') && field.val()=="") || (field.hasClass('multilanguage') && $('.'+field_identificator+':[value!=""]').length == 0))){
			field_box.removeClass('success').addClass('error');
			notification_box.removeClass('icon-ok').addClass('icon-remove');
			error_box.removeClass('hide').fadeIn().html(js_required_message);
			return false;
	}

	//Validation Objecte
	if(typeof form_validation[field_identificator]['object_validation'] != "undefined" && form_validation[field_identificator]['object_validation'] == true && typeof form_validation[field_identificator]['object'] != "undefined"){
		//ml forced
		if(typeof form_validation[field_identificator]['field_id'] != "undefined") field_id = form_validation[field_identificator]['field_id'];
		else field_id = field.attr('id');
		//ml check
		if(field.val()!="") value = field.val();
		else value = $('.'+field_id+':[value!=""]').val();
		if(value == undefined) value = '';

		$.post(form_validation[field_identificator]['object']['url'],
			{ 'validate' : form_validation[field_identificator]['object']['entity'] , 'name' : form_validation[field_identificator]['object']['name']  , 'value' : value },        
	   		function(data){
	   			if(data!=""){
	   				field_box.removeClass('success').addClass('error');
	   				notification_box.removeClass('icon-ok').addClass('icon-remove');
	   				error_box.removeClass('hide').fadeIn().html(data);
	   				return false;
	   			}
   			},
	   		'html');
	}

	//Validation Conditional
	if(typeof form_validation[field_identificator]['conditional_validation'] != "undefined" && form_validation[field_identificator]['conditional_validation'] == true && typeof form_validation[field_identificator]['conditional'] != "undefined"){
		field_id = field.attr('id');
		$.post(form_validation[field_identificator]['conditional']['url'],
			{ 'value' : field.val(), 'condition' : form_validation[field_identificator]['conditional']['condition'], 'message' : form_validation[field_identificator]['conditional']['message'], 'condition_value' : form_validation[field_identificator]['conditional']['condition_value'], 'condition_value2' : form_validation[field_identificator]['conditional']['condition_value2'], 'ignore_value' : form_validation[field_identificator]['conditional']['ignore_value'] },        
	   		function(data){ 
	   			if(data!=""){
	   					field_box.removeClass('success').addClass('error');
		   				notification_box.removeClass('icon-ok').addClass('icon-remove');
		   				error_box.removeClass('hide').fadeIn().html(data);
		   				return false;
		   			}
   			},
	   		'html');
	}

	//Si ha passat tots els errors
	field_box.removeClass('error').addClass('success');
	notification_box.removeClass('icon-remove').addClass('icon-ok');
	error_box.fadeOut().addClass('hide').html('&nbsp;').hide();
	return true;
}

function checkboxValidation(field_id){
	if($('#'+field_id+' input:checkbox:checked').length == 0){
		$('#'+field_id).addClass('error');
		if($('#'+field_id+' .error-message').html()=="<p>&nbsp;</p>") $('#'+field_id+' .error-message').html(js_required_message);
		$('#'+field_id+' .error-message').removeClass('hide').fadeIn();
		return false;
	}else{
		$('#'+field_id).removeClass('error');
		$('#'+field_id+' .error-message').hide().addClass('hide');	
	}
	return true;
}

function radioValidation(field_id){
	if($('#'+field_id+' input:radio').is(":visible") && $('#'+field_id+' input:radio:checked').length == 0){
		$('#'+field_id+' .error-message').removeClass('hide').fadeIn().html(js_required_message);
		return false;
	}else $('#'+field_id+' .error-message').hide().addClass('hide');
	return true;
}

function selectValidation(field_id){
	if($('#'+field_id+' select:last').is(":visible") && !$('#'+field_id).hasClass('no-default') && $('#'+field_id+' select:last option:selected').index()==0){
		$('#'+field_id+' .error-message').removeClass('hide').fadeIn().html(js_required_message);
		return false;
	}else $('#'+field_id+' .error-message').hide().addClass('hide');	
	return true;
}

$(document).ready(function() {
	$('.confirmation-required').live('click', function(){
		if(!$(this).hasClass('deactivated-button')){
			if ($("#confirmation-message").length > 0){
				$('#confirmation-message').slideUp('fast',function(){ $('#confirmation-message').remove(); });
			}else{
				title = $(this).attr('title');
				if(title == undefined) title = 'delete this project';
				$('body').append('<div id="confirmation-message" class="confirmation hide"><p>'+title+'</p><a class="small-button confirm" href="'+$(this).attr('href')+'">'+js_confirm_message+'</a><a class="grey-dgr small-button " href="#" onclick="$(\'.confirmation-required\').click(); return false;">'+js_maybelater_message+'</a><span class="triangle"></span></div>');
				$('#confirmation-message').offset($(this).offset());
				$('#confirmation-message').offset({ top: $('#confirmation-message').offset().top + $(this).outerHeight() + 5, left: $('#confirmation-message').offset().left - $('#confirmation-message').outerWidth()/2 + $(this).outerWidth()/2 });
				$('#confirmation-message').hide().removeClass('hide').slideDown('fast');
			}
		}
		return false;
	});
	
	$('.deactivated-button').live('click', function(){
		return false;
	});
});

//upload Ajax Image
function uploadAjaxImage(elementId, url, target){
	var inputFileImage = document.getElementById(elementId);
	
	var file = inputFileImage.files[0];
	var data = new FormData();
	data.append('uploadedImage',file);
	
	$.ajax({
		url:url,
		type:'POST',
		contentType:false,
		data:data,
		success: function (data){
			$(target).html(data);
		},
		processData:false,
		cache:false
	});
}

//imatge actual de les destacades
function getFeaturedDeveloperCurrentAppScreenshot(app_url,action){
	var total = $('#bullets-'+app_url+'-screenshot').children().size();
	var selected = 0;
	for(i=1;i<=total;i++){
		if($('#preview-'+app_url+'-screenshot-selector-'+i).hasClass('active')) selected = i;
	}
	if(action == 'next') selected = selected + 1;
	if(action == 'prev') selected = selected - 1;
	if(selected < 1) selected = total;
	if(selected > total) selected = 1;
	return selected;
}

function similar_text(first, second, percent) {
  if (first === null || second === null || typeof first === 'undefined' || typeof second === 'undefined') {
    return 0;
  }

  first += '';
  second += '';

  var pos1 = 0,
    pos2 = 0,
    max = 0,
    firstLength = first.length,
    secondLength = second.length,
    p, q, l, sum;

  max = 0;

  for (p = 0; p < firstLength; p++) {
    for (q = 0; q < secondLength; q++) {
      for (l = 0;
        (p + l < firstLength) && (q + l < secondLength) && (first.charAt(p + l) === second.charAt(q + l)); l++)
      ;
      if (l > max) {
        max = l;
        pos1 = p;
        pos2 = q;
      }
    }
  }

  sum = max;

  if (sum) {
    if (pos1 && pos2) {
      sum += this.similar_text(first.substr(0, pos1), second.substr(0, pos2));
    }

    if ((pos1 + max < firstLength) && (pos2 + max < secondLength)) {
      sum += this.similar_text(first.substr(pos1 + max, firstLength - pos1 - max), second.substr(pos2 + max,
        secondLength - pos2 - max));
    }
  }

  if (!percent) {
    return sum;
  } else {
    return (sum * 200) / (firstLength + secondLength);
  }
}

/* controla tamany finestra per mostrar una capa o unaltre */
$(window).resize(function() {
	if( $(window).width() > 1024){
		$('.share-widget').slideDown('2000');
	}else{
		$('.share-widget').slideUp();
	} 
});

function waitTime(time){
	var date = new Date();
	var curDate = null;

	do { curDate = new Date(); } while(curDate-date < time);
}

/* MENU DEVELOPER FIXED */

var num = 400; //number of pixels before modifying styles

$(window).bind('scroll', function () {
    if ($(window).scrollTop() > num) {
        $('.quick-info').addClass('quick-info-fixed');
    } else {
        $('.quick-info').removeClass('quick-info-fixed');
    }
});

/* COUNSELLING 

var num2 = 300; //number of pixels before modifying styles

$(window).bind('scroll', function () {
    if ($(window).scrollTop() > num2) {
        $('.submenu-counselling').addClass('submenu-create-visible');
    } else {
        $('.submenu-counselling').removeClass('submenu-create-visible');
    }
});*/

// BUTTONS CREATE PROFILE AND POST A PROJECT 

var num3 = 50; //number of pixels before modifying styles

$(window).bind('scroll', function () {
    if ($(window).scrollTop() > num3) {
        $('.submenu-create.no-logged').addClass('submenu-create-visible');
        $('.post-your-project-top-bar').removeClass('hide');
        $('.header #logo.small-logo').addClass('mini-logo-positivo',200);
    } else {
        $('.submenu-create.no-logged').removeClass('submenu-create-visible');
        $('.post-your-project-top-bar').addClass('hide');
        $('.header #logo.small-logo').removeClass('mini-logo-positivo',200);
    }
});

/* MOBILE MENU */

$(document).ready(function() {
    $('.showMenu, #fade, .hideMenu').click(function() {
        if($('.showMenu').hasClass('closed')) {
            var scroll = $(document).scrollTop();
            $('body').css({"overflow":"hidden"}).css({"position":"fixed"});
            if($('.wrapper').length>0) $('.wrapper').css({'margin-top':'-'+scroll+'px'});
            $('#main-nav').animate({left: "0"}, 400);
            $('.showMenu').removeClass('closed').addClass('open');
            $('#fade').fadeIn(); }
        else if($('.showMenu').hasClass('open')) {
        	if($('.wrapper').length>0){
        		var scroll = $('.wrapper').css('margin-top').replace('-','').replace('px','');
        		$('.wrapper').css({'margin-top':'0px'});
            }
            $('body').css({"overflow":"scroll"}).css({"position":"relative"});
            $(document).scrollTop(scroll);
            $('#main-nav').animate({left: "-250px"}, 400);
            $('.showMenu').removeClass('open').addClass('closed');
            $('#fade').fadeOut(); }
		return false;
    });
});

/* PRICING */

var num4 = 450; //number of pixels before modifying styles

$(window).bind('scroll', function () {
    if ($(window).scrollTop() > num4) {
        $('.scroll-price').addClass('scroll-price-visible');
    } else {
        $('.scroll-price').removeClass('scroll-price-visible');
    }
});

/** ===========================================
    Hide / show the master navigation menu
============================================ */

  // console.log('Window Height is: ' + $(window).height());
  // console.log('Document Height is: ' + $(document).height());

  var previousScroll = 0;

  $(window).scroll(function(){

    var currentScroll = $(this).scrollTop();

    /*
      If the current scroll position is greater than 0 (the top) AND the current scroll position is less than the document height minus the window height (the bottom) run the navigation if/else statement.
    */

    if (currentScroll >= 35) hideTopBar();
    else showTopBar();

  });

  function hideTopBar(){
  	$('.top-header').slideUp(50,function(){ $('.top-bar-menu').removeClass('hide'); $('.save-profile').css('top','51px'); });
  }

  function showTopBar(){
  	$('.top-header').slideDown(50,function(){ $('.top-bar-menu').addClass('hide'); $('.save-profile').css('top','81px'); });
  }