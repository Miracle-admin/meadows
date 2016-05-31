/**
 * Main JavaScript file
 *
 * @package         Tooltips
 * @version         4.1.3
 *
 * @author          Peter van Westen <peter@nonumber.nl>
 * @link            http://www.nonumber.nl
 * @copyright       Copyright © 2015 NoNumber All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

(function($) {
	$(document).ready(function() {
		var tt_timeout = null;
		var tt_timeoutOff = 0;

		// hover mode
		$('.nn_tooltips-link.hover').popover({
			trigger  : 'hover',
			container: 'body',
		});


		// close all popovers on click ouside
		$('html').click(function() {
			$('.nn_tooltips-link').popover('hide');
		});

		// do stuff differently for touchscreens
		$('html').one('touchstart', function() {
			// add click mode for hovers
			$('.nn_tooltips-link.hover').popover({
				trigger  : 'manual',
				container: 'body'
			}).click(function(evt) {
				tooltipsShow($(this), evt, 'click');
			});
		});

		// close all popovers on click outside
		$('html').on('touchstart', function(e) {
			if ($(e.target).closest('.nn_tooltips').length) {
				return;
			}

			$('.nn_tooltips-link').popover('hide');
		});

		$('.nn_tooltips-link').on('touchstart', function(evt) {
			// prevent click close event
			evt.stopPropagation();
		});

		function tooltipsShow(el, evt, cls) {
			// prevent other click events
			evt.stopPropagation();

			clearTimeout(tt_timeout);

			// close all other popovers
			$('.nn_tooltips-link.' + cls).each(function() {
				if ($(this).data('popover') != el.data('popover')) {
					$(this).popover('hide');
				}
			});

			// open current
			if (!el.data('popover').tip().hasClass('in')) {
				el.popover('show');
			}

			$('.nn_tooltips')
				.click(function(evt) {
					// prevent click close event on popover
					evt.stopPropagation();

					// switch timeout off for this tooltip
					tt_timeoutOff = 1;
					clearTimeout(tt_timeout);
				})
			;
		}

	});
})(jQuery);
