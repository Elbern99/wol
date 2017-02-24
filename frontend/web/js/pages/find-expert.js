
(function ($) {

    // ELEMENTS
    var elements = {
        document: $(document),
        window: $(window),
        htmlBody: $('html, body')
    };

    //GLOBAL VARIABLE ---------
    var _window_height = $(window).height(),
            _window_width = $(window).width(),
            _mobile = 769,
            _tablet = 1025;

    elements.window.resize(function () {
        _window_width = $(window).width();
    });

    var advancedSearch = {
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

    function dynamicFilter(filter) {
        var $cur = $(this),
            $parent = $cur.parents('.sidebar-accrodion-item'),
            parentIndex = $parent.index();

        localStorage.setItem('AccordionItems', parentIndex);
        $('.expert-filter-form').submit();
    }

    elements.document.ready(function () {
        $('.item').find('input:checkbox').bind('change', dynamicFilter);
        advancedSearch.cloneEl('.filter-clone', '.mobile-filter-container', '.filter-clone-holder');
        advancedSearch.openFilter('.filter-mobile-link', '.mobile-filter');

        //scroll to el
        var
            checkStorage = localStorage.getItem('AccordionItems');

        if(!checkStorage) {
            $('.preloader').fadeOut(0);
        } else {
            $('.filter-mobile-link').trigger('click');
        }
    });

    elements.window.load(function() {

        //scroll to el
        var
            checkStorage = localStorage.getItem('AccordionItems');

        if ($('.search-results').length>0 || $('.find-expert').length>0 && checkStorage){
            var
                $accordionItem = $('.sidebar-accrodion-item'),
                accordionItemIndex =  localStorage.getItem('AccordionItems'),
                $findItem = $accordionItem.eq(accordionItemIndex),
                itemScrollCoord =  $findItem.offset().top;

            elements.htmlBody.animate({scrollTop: itemScrollCoord}, 1);
        }
    });
})(jQuery);