
(function($) {
    //GLOBAL VARIABLE ---------

    var
        $window = $(window),
        $document = $(document),
        $html = $('html,body'),
        _window_height = $window.height(),
        _window_width = $window.width(),
        _doc_height = $document.height(),
        _click_touch = ('ontouchstart' in window) ? 'touchstart' : ((window.DocumentTouch && document instanceof DocumentTouch) ? 'tap' : 'click'),
        _mobile = 767,
        _tablet = 1025;

    $(window).resize(function() {
        _window_width = $(window).width();
    });


    var sources = {
        scrollToAnchor: function(btn,scrollTo) {
            function scrollToAnchor(aid){
                var
                    top = aid.offset().top,
                    topPadding = 5,
                    delay = 200;

                $html.animate({scrollTop: top-topPadding}, delay);
            }
            $(btn).click(function(e) {
                var
                    cur = $(this),
                    curAnchor = cur.attr('href'),
                    curParent = cur.parent(),
                    curScrollEl = $(scrollTo).find('a[href$="'+curAnchor+'"]'),
                    checkScrollLength = curScrollEl.length > 0,
                    delay = 200;

                if(checkScrollLength) {
                    scrollToAnchor(curScrollEl);
                    setTimeout(function(){
                        curParent.addClass('active').siblings().removeClass('active');
                    }, delay*2);
                }
                e.preventDefault();
            });
            $window.scroll(function() {
                var
                    checkAnimate = $html.not(':animated');

                if (checkAnimate) {
                    $('.abs-list li').removeClass('active');
                }
            });
        },
        scrollToSelf:function(btn) {
            $(btn).click(function(e) {
                var
                    cur = $(this),
                    curParent = cur.parent(),
                    curAnchor = cur.attr('href');

                $('.profile-author-letter[href$="'+curAnchor+'"]').trigger('click');
                e.preventDefault();
            });
        },
        stikySidebar: function(sidebar) {
            var
                $sidebar   = $(sidebar),
                offset     = $sidebar.offset(),
                topPadding = 3;

            $window.scroll(function() {
                if ($window.scrollTop() > offset.top) {
                    $sidebar.css('margin-top', $window.scrollTop() - offset.top + topPadding);
                } else {
                    $sidebar.css('margin-top', 0);
                }
            });
        },
        paddingBottomBody:function(item) {
            function setPadding(item) {
                var
                    $stiky = $('.stiky');

                if(_window_width < _mobile) {
                    $(item).css('padding-bottom', $stiky.outerHeight());
                } else {
                    $(item).css('padding-bottom', '0');
                }
            };

            setPadding(item);
            $window.resize(function() {
                setPadding(item);
            });
        },
        backToTop: function(btn) {
            var
                delay = 200;

            $(window).scroll(function(){
                var
                    $btn = $(btn);

                if ($(this).scrollTop() > 100) {
                    $btn.fadeIn();
                } else {
                    $btn.fadeOut();
                }
            });

            // Click event to scroll to top
            $(btn).click(function(){
                $html.animate({scrollTop : 0},delay);
                return false;
            });

            function setLeft(btn) {
                var
                    $stiky = $('.stiky'),
                    stikyLeft = $stiky.offset().left,
                    stikyWidth = $stiky.width(),
                    stikyHeight = $stiky.height(),
                    btnWidth = 30,
                    $btn = $(btn);

                if(_window_width > _mobile) {
                    $btn.css({
                        'left': stikyLeft+stikyWidth-btnWidth,
                        'right': 'auto',
                        'bottom': '35px'
                    });
                } else {
                    $btn.css({
                        'left': 'auto',
                        'right': '15px',
                        'bottom': stikyHeight
                    });
                }
            }

            setLeft(btn);
            $window.resize(function() {
                setLeft(btn);
            });
        }
    };

    $(document).ready(function() {
        sources.scrollToAnchor('.profile-author-letter', '.source-table-holder');
        sources.scrollToSelf('.td-letter a');
        sources.stikySidebar('.stiky');
        sources.paddingBottomBody('.wrapper');
    });
})(jQuery);