
(function ($) {

    // ELEMENTS
    var elements = {
        document: $(document),
        window: $(window),
        htmlBody: $('html, body')
    };

    //GLOBAL VARIABLE ---------
    var _window_height = elements.window.height(),
        _window_width = elements.window.width(),
        _mobile = 769,
        _tablet = 1025,
        _LSAccordionItem = localStorage.getItem('AccordionItemExpert'),
        _LSAccordionItemObj  = localStorage.getItem('AccordionItemsObjExpert');

    elements.window.resize(function () {
        _window_width = $(window).width();
    });

    var filter = {
        cloneEl: function (el, elToMobile, elToDesktop) {
            function appendElements(el, elToMobile, elToDesktop) {
                if ($(elToMobile).length) {
                    var elHtml = $(el);

                    if (_window_width < _mobile) {
                        $(elToMobile).append(elHtml);
                    } else {
                        $(elToDesktop).append(elHtml);
                    }
                }
            }

            appendElements(el, elToMobile, elToDesktop);
            $(window).resize(function () {
                setTimeout(function () {
                    appendElements(el, elToMobile, elToDesktop);
                }, 600);
            });
        },
        openFilter: function (btn, content) {
            if ($(content).length) {
                $(btn).click(function (e) {
                    var cur = $(this);
                    cur.toggleClass('active');
                    $(content).slideToggle();
                    e.preventDefault();
                });

                $('.mobile-filter-bottom .btn-no-style').click(function (e) {
                    $(btn).trigger('click');
                    e.preventDefault();
                });
            }
        }
    };

    var filterLoad = {
        scrollToLastPosition: function(position) {
            setTimeout(function() {

                console.log(position);

                elements.htmlBody.animate({ scrollTop: position }, 0);
            }, 100);
        },
        openInMobile: function() {
            var
                $preloader = $('.preloader'),
                $filterMobileLink = $('.filter-mobile-link');

            if(!_LSAccordionItem) {
                $preloader.fadeOut(0);
            } else {
                $filterMobileLink.trigger('click');
            }
        },
        setCheckResult: function(item) {
            var $accordionItem = $(item);

            $accordionItem.bind('click',function(){

                var
                    LSFilter = { 0: true, 1: false, 2: false, 3: false };

                $accordionItem.each(function(i) {
                    var checkIsOpen = $(this).hasClass('is-open');
                    (checkIsOpen) ? LSFilter[i] = true : LSFilter[i] = false;
                });

                localStorage.setItem('AccordionItemsObjExpert', JSON.stringify(LSFilter));
            });
        },
        showCheckedAccordion: function(item) {

            var
                $accordionItem = $(item),
                checkPageLS = ($('.search-results').length>0 || $('.find-expert').length>0) && _LSAccordionItem;

                if (checkPageLS){
                    var
                        accordionItemObj = JSON.parse(_LSAccordionItemObj);

                    for(var key in accordionItemObj) {
                        var value = accordionItemObj[key],
                            index = key;
                        if(value) {
                            $accordionItem.eq(key).addClass('is-open').find('.text').slideDown(0);
                        }
                    }

                    console.log(_LSAccordionItem,'asd')

                    filterLoad.scrollToLastPosition(_LSAccordionItem);
                };
        }
    };

    function dynamicFilter(filter) {
        var
            $cur = $(this),
            curTop = $cur.offset().top;

        localStorage.setItem('AccordionItemExpert', curTop);
        $('.expert-filter-form').submit();
    }

    elements.document.ready(function () {
        $('.item').find('input:checkbox').bind('change', dynamicFilter);
        filter.cloneEl('.filter-clone', '.mobile-filter-container', '.filter-clone-holder');
        filter.openFilter('.filter-mobile-link', '.mobile-filter');
        filterLoad.openInMobile();
    });

    elements.window.load(function() {
        filterLoad.setCheckResult('.sidebar-accrodion-item');
        filterLoad.showCheckedAccordion('.sidebar-accrodion-item');
    });
})(jQuery);