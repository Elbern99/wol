(function($) {

				//ELEMENTS
				var elements  = {
								window: $(window)
				}

				//GLOBAL VARIABLE ---------
				var _window_width = elements.window.width(),
								_mobile = 820,
								_mobileSmall  = 413,
								_tablet = 1025,
								_click_touch = ('ontouchstart' in window) ? 'touchstart' : ((window.DocumentTouch && document instanceof DocumentTouch) ? 'tap' : 'click');

				elements.window.resize(function() {
								_window_width = elements.window.width();
				});

				$(document).ready(function() {
								var contentMap = {
												MobileMoreText: function(btn){
																$(btn).click(function(e) {
																				var cur = $(this);
																				cur.parent().toggleClass('open-text');
																				e.preventDefault();
																});
												}
								};

								contentMap.MobileMoreText('.more-evidence-map-text-mobile');
				});

})(jQuery);