
/////////////////////////////////////////////
// Page Width Calculation
/////////////////////////////////////////////
var ItemBoard = {
	init : function(config) {
		this.config = config;
		this.bindEvents();
	},
	columns : 0,
	itemMargin : 20,
	itemPadding : 0,
	sidebarGap : 30,
	bindEvents : function() {
		var _self = this;
		jQuery(document).ready(function() {
			_self.elementSetup()
		});
		jQuery(window).resize(function() {
			_self.elementSetup()
		});
	},

	elementSetup : function() {
		var item = jQuery(this.config.itemElement),
				viewport_width = this.viewportWidth(),
				fixwidth,
				maxWidth,
				content_w;

		this.itemWidthOuter = this.itemWidthInner() + this.itemMargin + this.itemPadding;
		this.columns = parseInt(viewport_width / this.itemWidthOuter);

		fixwidth = this.columns * this.itemWidthInner() + ((this.columns - 1) * this.itemMargin);
		maxWidth = '100%';

		// check if there sidebar
		if(jQuery('#sidebar').length > 0){
			content_w = fixwidth - jQuery('#sidebar').width() + 15;
			
			jQuery('#grid-content').width(content_w);
			fixwidth = fixwidth + this.sidebarGap;

			if(viewport_width < 965){
				jQuery('#grid-content').css({width : ''}); // reset width inline
				fixwidth = this.columns * this.itemWidthInner() + ((this.columns - 1) * this.itemMargin); // reset page width
			}
		}

		// make exception for width smaller than 480px then dont apply the inline width
		// assume 480 = 1 column item and apply to only viewport <= 505
		if ((this.columns <= 1 && viewport_width <= 505) || (jQuery('.list-post').length > 0)) {
			fixwidth = 978;
			maxWidth = '94%';
		}

		jQuery(this.config.appliedTo).each(function() {
			jQuery(this).css({
				'width' : fixwidth + 'px',
				'max-width' : maxWidth
			});
		});
	},

	itemWidthInner : function() {
		var innerwidth = jQuery(this.config.itemElement).width();
		return innerwidth;
	},

	viewportWidth : function() {
		return jQuery(window).width();
	}
};

jQuery(window).load(function() {
	applyIsotope();
});


function applyIsotope(){
	
	// initialize Isotope after all images have loaded
	var $container = jQuery('#loops-wrapper');
	
	$container.imagesLoaded( function() {
		// auto width
		if( $container.length > 0 && jQuery('body.grid4, body.grid3, body.grid2').length > 0 ){
			ItemBoard.init({
				itemElement : '.AutoWidthElement .post',
				appliedTo   : '.pagewidth'
			});
		}
		
		// Add social buttons
		//addSocialButtons('#body');

		// isotope init
		if (jQuery('.list-post').length == 0) {
			$container.isotope({
				itemSelector : '.post',
				transformsEnabled : false,
				layoutMode: 'masonry',
			});
			jQuery(window).resize();
			
			/*$container.append(divs).isotope('appended', divs, function () {
			    $container.isotope('reLayout');
			});*/
		}
	});
	
	
}