$.getScript("./js/plugins/owl.carousel.min.js");

(function($) {

    //GLOBAL VARIABLE ---------

    var _window_height = $(window).height(),
        _window_width = $(window).width(),
        _click_touch = ('ontouchstart' in window) ? 'touchstart' : ((window.DocumentTouch && document instanceof DocumentTouch) ? 'tap' : 'click'),
        _mobile = 769,
        _tablet = 1025


    $(window).resize(function() {
        _window_height = $(window).height();
        _window_width = $(window).width();
    });

    //FUNCTIONS ---------------

    //HEADER ---------

    //1. HEADER MENU
    menu = {
        detectSubmenu: function(items,addClass, submenuClass) {
            $(items).each(function( index ) {
                if($(this).find(submenuClass).length) {
                    $(this).addClass(addClass);
                }
            });
        }
    }

    /* menuMobile */
    menuMobile = {
        classes: 'open',
        delay: 200,
        openItem: function(cur) {
            cur.parent().find('>div').slideDown(menuMobile.delay);
            cur.parent().addClass('open');
        },
        closeItem: function(cur) {
            cur.parent().find('>div').slideUp(menuMobile.delay);
            cur.parent().removeClass('open');
        },
        toggleItem: function(btn,content,parent) {
            parent.find('.'+menuMobile.classes).find(content).slideDown(0);

            console.log(btn);

            btn.click(function(e) {
                var cur = $(this);

                if(cur.parent().hasClass(menuMobile.classes)){
                    menuMobile.closeItem(cur);
                } else {
                    cur.parent().siblings().removeClass(menuMobile.classes).find(content).slideUp(menu.delay);
                    menuMobile.openItem(cur);
                }
                e.preventDefault();
            });
        }
    }
    /* menuMobile end */

    /* menuDesktop */
    menuDesktop = {
        toggleMenu: function(btn, dropWidget) {
            btn.on('click',function(e) {
                var cur = $(this);
                    $(document).unbind('click.submenu');
                    dropWidget.removeClass('open');
                    btn.not(cur).removeClass('active');
                    if ( !cur.hasClass('active') ) {
                        e.preventDefault();
                        var yourClick = true;
                        var drop = cur.parents('.has-submenu').find('>.submenu');
                        drop.addClass('open');
                        cur.addClass('active');
                        $(document).bind('click.submenu', function (e) {
                            if(_window_width > _mobile ) {
                                if (!yourClick  && !$(e.target).closest(drop).length || $(e.target).closest(drop.find('div')).length ) {
                                    dropWidget.removeClass('open');
                                    btn.removeClass('active');
                                    $(document).unbind('click.submenu');
                                }
                                yourClick  = false;
                            }
                        });
                    } else {
                        dropWidget.removeClass('open');
                        cur.removeClass('active');
                    }
            });
        }
    }
    /* menuDesktop end */

    /* menuSlider */
    menuMobileSlider = {
        arrowOption: function() {
            if($('.owl-stage .owl-item').last().hasClass('active')){
                //$('.owl-prev').fadeIn();
                console.log('prev');
            } else {
                //$('.owl-next').fadeIn();
                console.log('next');
            }
        },
        init: function(slider) {

            var sliderOption = {
                loop: false,
                autoWidth: true,
                nav : true,
                navigationText : ["prev","next"],
                responsiveRefreshRate : 0,
                responsiveBaseWidth: window,
                onInitialized: menuMobileSlider.arrowOption,
                onRefreshed: menuMobileSlider.arrowOption,
                pagination: false,
                responsiveClass:true,
                responsive:{
                    0:{
                        items:4
                    },
                    375:{
                        items:5
                    },
                    600:{
                        items:9
                    }
                }
            }

            if(_window_width < _mobile) {

            }

            if(_window_width < _mobile) {
                $(slider).owlCarousel(sliderOption);
            } else {
                $(slider).owlCarousel(sliderOption);
            }

            $(window).resize(function() {
                if(_window_width < _mobile) {
                    $(slider).owlCarousel(sliderOption);
                } else {
                    $(slider).owlCarousel(sliderOption);
                }
            });
        }
    }
    /* menuSlider end */

    //CONTENT ---------

    /* faqAccordion */
    faqAccordion = {
        classes: 'is-open',
        delay: 200,
        openItem: function(cur) {
            cur.next().slideDown(faqAccordion.delay);
            cur.parent().addClass('is-open');
        },
        closeItem: function(cur) {
            cur.next().slideUp(faqAccordion.delay);
            cur.parent().removeClass('is-open');
        },
        toggleItem: function(btn,content,parent) {
            parent.find('.'+faqAccordion.classes).find(content).slideDown(0);
            btn.click(function(e) {
                var cur = $(this);
                if(cur.parent().hasClass(faqAccordion.classes)){
                    faqAccordion.closeItem(cur);
                } else {
                    cur.parent().siblings().removeClass(faqAccordion.classes).find(content)
                        .slideUp( faqAccordion.delay, function() {
                            setTimeout(function() {
                                faqAccordion.openItem(cur);
                            }, 100);
                        });
                }
                e.preventDefault();
            });
        }
    }
    /* faqAccordion end */

    /* moreSidebarNews */
    function moreSidebarNews(btnMore,parent) {
        $(parent).next(btnMore).on('click',function(e) {
            var cur = $(this),
                itemStep = 5,
                curParent =  cur.parents('li');
                curParent.find('li').slideDown();

                if(curParent.find('li').is(":visible")) cur.css('opacity','0');

            e.preventDefault();
        });
    }
    /* moreSidebarNews end */

    /// -------------------------- to refactor

    /* dropDown */
    function dropDown(btn, dropWidget) {
        if ( dropWidget.length ) {
            btn.on('click',function(e) {
                var cur = $(this);
                $(document).unbind('click.drop-content');

                if(_window_width > _mobile ) {
                    dropWidget.removeClass('open');
                }

                btn.not(cur).removeClass('active');
                if ( !cur.hasClass('active') ) {
                    var yourClick = true;
                    var drop = cur.parents('.dropdown').find('>.drop-content');
                    drop.addClass('open');
                    cur.addClass('active');

                    $(document).bind('click.drop-content', function (e) {
                        if(_window_width > _mobile ) {
                            if (!yourClick  && !$(e.target).closest(drop).length || $(e.target).closest(drop.find('li')).length ) {
                                dropWidget.removeClass('open');
                                btn.removeClass('active');
                                $(document).unbind('click.drop-content');
                            }

                            yourClick  = false;
                        }
                    });
                } else {
                    dropWidget.removeClass('open');
                    cur.removeClass('active');
                }
                e.preventDefault();
            });
        }
    }
    /* dropDown end */

    /* closeDropDown */
    function closeDropDown(btnClose,drop,btnOpen) {
        btnClose.on('click',function(e) {
            drop.removeClass('open');
            btnOpen.removeClass('active');
            e.preventDefault();
        });
    }
    /* closeDropDown end */

    /* tabs */
     function tabsFn(list, content){

         //list.find('li').eq(0).find('a').addClass('active');
         //list.find('li').eq(0).find(content).addClass('open');

         list.find('a').on('click', function(e) {
             var  cur = $(this),
                  curParent = cur.parents('li');

             list.find('a').removeClass('active');
             list.find(content).removeClass('open');

             if ( !cur.hasClass('active') ) {
                 cur.addClass('active');
                 curParent.find(content).addClass('open');
             } else {
                 cur.removeClass('active');
                 curParent.find(content).removeClass('open');
             }
             e.preventDefault();
         });
    }
    /* tabs end */

    $(document).ready(function() {
        //HEADER
        menu.detectSubmenu('.header-menu-bottom-list .item','has-submenu','.submenu');
        //mobile
        menuMobile.toggleItem($('.mobile-menu .has-submenu >a'), '.submenu',$('.mobile-menu .header-menu-bottom-list'));
        //desktop
        menuDesktop.toggleMenu($('.header-desktop .has-submenu >a'),$('.header-desktop .submenu'));
        dropDown($('.header-desktop .dropdown-link'), $('.drop-content'));

        if(_window_width < _tablet ) {
            tabsFn($('.login-registration-list'), '.dropdown-widget');
            dropDown($('.btn-mobile-menu-show'), $('.drop-content'));
            dropDown($('.btn-mobile-search-show'), $('.drop-content'));
            dropDown($('.btn-mobile-login-show'), $('.drop-content'));
            closeDropDown($('.btn-mobile-menu-close'), $('.mobile-menu'), $('.btn-mobile-menu-show'));
            closeDropDown($('.btn-mobile-search-close'), $('.mobile-search'), $('.btn-mobile-search-show'));
            closeDropDown($('.btn-mobile-login-close'), $('.mobile-login'), $('.btn-mobile-login-show'));
        }

        //CONTENT
        faqAccordion.toggleItem($('.faq-accordion-list .title'), '.text',$('.faq-accordion-list'));
        faqAccordion.toggleItem($('.sidebar-accrodion-list .title'), '.text',$('.sidebar-accrodion-list'));
        moreSidebarNews('.more-link','.sidebar-news-list');
    });

    $(window).resize(function() {
        //HEADER
        dropDown($('.header-desktop .dropdown-link'), $('.drop-content'));

        if(_window_width < _mobile ) {
            tabsFn($('.login-registration-list'), '.dropdown-widget');
        }
        //CONTENT
    });

    $( window ).load(function() {
        $('.preloader').fadeOut();
        menuMobileSlider.init('.header-bottom .header-menu-bottom-list');
    });

})(jQuery);