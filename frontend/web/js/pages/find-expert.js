
(function($) {
				//GLOBAL VARIABLE ---------
				var _window_height = $(window).height(),
								_window_width = $(window).width(),
								_mobile = 820,
								_tablet = 1025;

				$(window).resize(function() {
								_window_width = $(window).width();
				});

				var advancedSearch = {
								cloneEl: function(el,elToMobile,elToDesktop) {
												function appendElements(el,elToMobile,elToDesktop) {
																if($(elToMobile).length) {
																				var elHtml = $(el);

																				if (_window_width < _mobile) {
																								$(elToMobile).append(elHtml);
																				} else {
																								$(elToDesktop).append(elHtml);
																				}
																}
												}

												appendElements(el,elToMobile,elToDesktop);
												$(window).resize(function() {
																setTimeout(function(){
																				appendElements(el,elToMobile,elToDesktop);
																}, 600);
												});
								},
								openFilter: function(btn,content) {
												if($(content).length) {
																$(btn).click(function(e) {
																				var cur = $(this);
																				cur.toggleClass('active');
																				$(content).slideToggle();
																				e.preventDefault();
																});

																$('.mobile-filter-bottom .btn-no-style').click(function(e) {
																				$(btn).trigger('click');
																				e.preventDefault();
																});
												}
								}
				}

				$(document).ready(function() {
								advancedSearch.cloneEl('.filter-clone', '.mobile-filter-container', '.filter-clone-holder');
								advancedSearch.openFilter('.filter-mobile-link', '.mobile-filter');
				});
})(jQuery);