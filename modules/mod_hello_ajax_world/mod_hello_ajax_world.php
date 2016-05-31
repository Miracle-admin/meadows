<?php defined('_JEXEC') or die;

/**
 * File       mod_hello_ajax_world.php
 * Created    1/17/14 12:29 PM
 * Author     Matt Thomas | matt@betweenbrain.com | http://betweenbrain.com
 * Support    https://github.com/Joomla-Ajax-Interface/Hello-Ajax-World-Module/issues
 * Copyright  Copyright (C) 2013 betweenbrain llc. All Rights Reserved.
 * License    GNU General Public License version 2, or later.
 */

// Include the helper.
require_once __DIR__ . '/helper.php';

// Instantiate global document object
$doc = JFactory::getDocument();

$js = <<<JS
(function ($) {
	
	$(document).on('click', 'a.pageTabs_link', function () {
		var img='<img src="images/ajax_loader.gif" alt=""/>';
		$('#product_containder').html(img);
		
		 if ($("a.pageTabs_link" ).hasClass( "active" ) ) {
			 	$( "a.pageTabs_link" ).removeClass("active");
		 }
		$(this).addClass('active');
		var newdata   = $('a.active').find('span').html();
		var newdata2   = $('div.active2').find('span').html();
		
			request = {
					'option' : 'com_ajax',
					'module' : 'hello_ajax_world',
					'data': { 'filter1': newdata, 'filtercategory': newdata2 },
					'format' : 'raw'
				};
		$.ajax({
			type   : 'POST',
			data   : request,
			success: function (response) {
				$('#product_containder').html(response);
			}
		});
		return false;
	});
	
	$(document).on('click', 'div.ajax_prod_cat', function () {
		var img='<img src="images/ajax_loader.gif" alt=""/>';
		$('#product_containder').html(img);
		 if ( $('div.ajax_prod_cat').hasClass( 'active2' ) ) {
			 	$('div.ajax_prod_cat').removeClass('active2');
		 }
		$(this).addClass('active2');
		var newdata   = $('a.active').find('span').html();
		var newdata2   = $('div.active2').find('span').html();
			request = {
					'option' : 'com_ajax',
					'module' : 'hello_ajax_world',
					'data': { 'filter1': newdata, 'filtercategory': newdata2 },
					'format' : 'raw'
				};
		$.ajax({
			type   : 'POST',
			data   : request,
			success: function (response) {
				$('#product_containder').html(response);
			}
		});
		return false;
	});
	
})(jQuery)
JS;

$doc->addScriptDeclaration($js);

require JModuleHelper::getLayoutPath('mod_hello_ajax_world');
