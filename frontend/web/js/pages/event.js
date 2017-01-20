(function($) {

				//ELEMENTS
				var elements = {
								document: $(document),
								window: $(window)
				};

				//GLOBAL VARIABLE ---------
				var _window_width = elements.window.width(),
								_window_height = elements.window.height(),
								_mobile = 820;

				elements.window.resize(function() {
								_window_width = elements.window.width();
								_window_height = elements.window.height();
				});

				//EVENT
				var event = {
								cloneEl: function(el,elToMobile,elToDesktop) {
												function appendElements(el,elToMobile,elToDesktop) {
																if($(elToMobile).length) {
																				var elHtml = $(el);

																				if (_window_width < _mobile) {
																								$(elToMobile).after(elHtml);
																				} else {
																								$(elToDesktop).append(elHtml);
																				}
																}
												}

												appendElements(el,elToMobile,elToDesktop);
												elements.window.resize(function() {
																setTimeout(function(){
																				appendElements(el,elToMobile,elToDesktop);
																}, 600);
												});
								},
								showMore: function(btn,text,wrapper,startHeight) {

												function showBtn() {
																if($(wrapper).height() >= startHeight) {
																				$(btn).css('display','block');
																				$(wrapper).removeClass('opened');
																} else {
																				$(btn).css('display','none');
																				$(wrapper).addClass('opened');
																}
												}

												showBtn();
												elements.window.resize(function() {
																setTimeout(function(){
																				showBtn();
																}, 600);
												});

												$(btn).on('click', function(e) {
																if ($(wrapper).height() == $(text).height()) {
																				$(wrapper).css('max-height', startHeight + "px").removeClass('opened');
																				$('html, body').animate({
																								scrollTop: $(btn).offset().top - (_window_height/2)
																				}, 0);
																				$(btn).text('read more');
																} else {
																				$(wrapper).css('max-height', $(text).height() + "px").addClass('opened');
																				$(btn).text('hide');
																}
																e.preventDefault();
												});
								}
				};


				elements.document.ready(function() {
								event.cloneEl('.other-events', '.sidebar-buttons-holder', '.other-events-holder');
								event.showMore('.more-event', '.event-text', '.event-text-holder', 696);
				});
})(jQuery);