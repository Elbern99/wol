(function($) {

    //GLOBAL VARIABLE ---------

    var _window_height = $(window).height(),
        _window_width = $(window).width(),
        _click_touch = ('ontouchstart' in window) ? 'touchstart' : ((window.DocumentTouch && document instanceof DocumentTouch) ? 'tap' : 'click'),
        _mobile = 769;


    $(window).resize(function() {
        _window_height = $(window).height();
        _window_width = $(window).width();
    });

    //FUNCTIONS ---------------

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

    /* menu */
    menu = {
        classes: 'open',
        delay: 200,
        openItem: function(cur) {
            cur.parent().find('>div').slideDown(menu.delay);
            cur.parent().addClass('open');
        },
        closeItem: function(cur) {
            cur.parent().find('>div').slideUp(menu.delay);
            cur.parent().removeClass('open');
        },
        toggleItem: function(btn,content,parent) {
            parent.find('.'+menu.classes).find(content).slideDown(0);
            btn.click(function(e) {
                var cur = $(this);

                if(cur.parent().hasClass(menu.classes)){
                    menu.closeItem(cur);
                } else {
                    cur.parent().siblings().removeClass(menu.classes).find(content).slideUp(menu.delay);
                    menu.openItem(cur);
                }
                e.preventDefault();
            });
        }
    }
    /* menu end */

    /* dropDownMenu */
    dropDownMenu = {
        toggleMenu: function(btn, dropWidget) {
            btn.on('click',function(e) {
                var cur = $(this);
                $(document).unbind('click.submenu-holder');

                dropWidget.removeClass('open');

                btn.not(cur).removeClass('active');
                if ( !cur.hasClass('active') ) {
                    e.preventDefault();
                    var yourClick = true;
                    var drop = cur.parents('.has-submenu').find('>.submenu-holder');
                    drop.addClass('open');
                    cur.addClass('active');
                    $(document).bind('click.submenu-holder', function (e) {
                        if(_window_width > _mobile ) {
                            if (!yourClick  && !$(e.target).closest(drop).length || $(e.target).closest(drop.find('li')).length ) {
                                dropWidget.removeClass('open');
                                btn.removeClass('active');
                                $(document).unbind('click.submenu-holder');
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
    /* dropDownMenu end */

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
                    console.log(2);
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

        dropDown($('.header-desktop .dropdown-link'), $('.drop-content'));

        faqAccordion.toggleItem($('.faq-accordion-list .title'), '.text',$('.faq-accordion-list'));
        faqAccordion.toggleItem($('.sidebar-accrodion-list .title'), '.text',$('.sidebar-accrodion-list'));

        menu.toggleItem($('.mobile-menu .has-submenu >a'), '.submenu-holder',$('.mobile-menu .header-menu-bottom-list'));
        dropDownMenu.toggleMenu($('.header-desktop .has-submenu >a'),$('.header-desktop .submenu-holder'));

        moreSidebarNews('.more-link','.sidebar-news-list');


        //only mobile
        if(_window_width < _mobile ) {
            tabsFn($('.login-registration-list'), '.dropdown-widget');
            dropDown($('.btn-mobile-menu-show'), $('.drop-content'));
            dropDown($('.btn-mobile-search-show'), $('.drop-content'));
            dropDown($('.btn-mobile-login-show'), $('.drop-content'));
            closeDropDown($('.btn-mobile-menu-close'), $('.mobile-menu'), $('.btn-mobile-menu-show'));
            closeDropDown($('.btn-mobile-search-close'), $('.mobile-search'), $('.btn-mobile-search-show'));
            closeDropDown($('.btn-mobile-login-close'), $('.mobile-login'), $('.btn-mobile-login-show'));
        }
    });

    $( window ).resize(function() {

        dropDown($('.header-desktop .dropdown-link'), $('.drop-content'));

        if(_window_width < _mobile ) {
            tabsFn($('.login-registration-list'), '.dropdown-widget');
        }
    });

    $( window ).load(function() {
        $('.preloader').fadeOut();
    });

})(jQuery);