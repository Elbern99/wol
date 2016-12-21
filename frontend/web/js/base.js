(function($) {

// ELEMENTS
var elements = {

}

//GLOBAL VARIABLE ---------

    var _window_height = $(window).height(),
        _window_width = $(window).width(),
        _doc_height = $(document).height(),
        _click_touch = ('ontouchstart' in window) ? 'touchstart' : ((window.DocumentTouch && document instanceof DocumentTouch) ? 'tap' : 'click'),
        _mobile = 769,
        _tablet = 1025;

    $(window).resize(function() {
        _window_height = $(window).height();
        _window_width = $(window).width();
        _doc_height = $(document).height();
    });

// 1. HEADER ---------

    // 1.1 HEADER MENU

    var headerMenu = {
        classes: 'open',
        delay: 200,
        detectSubmenu: function(item) {
            $(item).each(function( index ) {
                var cur = $(this);
                if (cur.find('>.submenu').length > 0) {
                    cur.addClass('has-drop');
                }
            });
        },
        mobileScroll: function(item){
            var scrollPaneOption = {
                showArrows: true,
                autoReinitialise: true,
                animateScroll: true
            }

            if(_window_width < _mobile) {
                $(item).jScrollPane(scrollPaneOption);
            }

            $(window).resize(function() {
                if(_window_width < _mobile) {
                    $(item).jScrollPane(scrollPaneOption);
                } else {
                    var element = $(item).jScrollPane({});
                    var api = element.data('jsp');
                    api.destroy();
                }
            });
        },
        mobileOpenItem: function(cur) {
            cur.parent().find('>div').slideDown(headerMenu.delay);
            cur.parent().addClass('open');
        },
        mobileCloseItem: function(cur) {
            cur.parent().find('>div').slideUp(headerMenu.delay);
            cur.parent().removeClass('open');
        },
        mobile: function(btn,content,parent) {
            parent.find('.'+headerMenu.classes).find(content).slideDown(0);
            btn.click(function(e) {
                var cur = $(this);
                if(cur.parent().hasClass(headerMenu.classes)){
                    headerMenu.mobileCloseItem(cur);
                } else {
                    cur.parent().siblings().removeClass(headerMenu.classes)
                        .find(content).slideUp(headerMenu.delay)
                        .find('.item').removeClass('open');
                    headerMenu.mobileOpenItem(cur);
                    e.preventDefault();
                }
            });
        },
        desktop: function(btn, dropWidget) {
            btn.on('click',function(e) {
                var cur = $(this);
                $(document).unbind('click.submenu');
                dropWidget.removeClass('open');
                btn.not(cur).removeClass('active');
                if ( !cur.hasClass('active') ) {
                    e.preventDefault();
                    var yourClick = true;
                    var drop = cur.parents('.has-drop').find('>.submenu');
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
    };

    // 1.2 HEADER SEARCH

    var search = {
        autoSelect: function(btn,parent) {
            $(parent).on('click', btn, function(e) {
                var cur = $(this),
                    curText = cur.text();
                cur.parents(parent).find(':text').val(curText);

                console.log(1);
                e.preventDefault();
            });
        }
    };

    /* dropDown */
    function dropDown(btn, dropWidget, mobile) {
        if ( dropWidget.length ) {



            btn.on('click',function(e) {
                var cur = $(this);
                $(document).unbind('click.drop-content');

                console.log(mobile);

                if(_window_width > _mobile ) {
                    //dropWidget.removeClass('open');
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

        console.log(list.find('li'));

        list.find('li').eq(0).find('a').addClass('active');
        list.find('li').eq(0).find(content).addClass('active');

        list.find('>li>a').on('click', function(e) {
            var  cur = $(this),
                curParent = cur.parents('li');

            list.find('a').removeClass('active');
            list.find(content).removeClass('active').addClass('js-tab-hidden');

            if ( !cur.hasClass('active') ) {
                cur.addClass('active');
                curParent.find(content).addClass('active').removeClass('js-tab-hidden');
            } else {
                cur.removeClass('active');
                curParent.find(content).removeClass('active').addClass('js-tab-hidden');
            }
            e.preventDefault();
        });

        $('.open-mobile-login').on('click', function(e) {
            $('.btn-mobile-menu-show').trigger('click');
            $('.btn-mobile-login-show').trigger('click');
            $('.login-registration-list.mobile li').eq(0).find('a').trigger('click');
            e.preventDefault();
        });

        $('.open-mobile-register').on('click', function(e) {
            $('.btn-mobile-menu-show').trigger('click');
            $('.btn-mobile-login-show').trigger('click');
            $('.login-registration-list.mobile li').eq(1).find('a').trigger('click');
            e.preventDefault();
        });

    }
    /* tabs end */

// 2. CONTENT ---------

    // 2.1 ACCORDION
    var faqAccordion = {
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
                    faqAccordion.openItem(cur);
                }
                e.preventDefault();
            });
        }
    };
    /* end */

    // 2.2 DOC HEIGHT FOR ELEMENTS
    var docHeightForElement = {
        elements: ['.reference-popup, .mobile-menu, .mobile-search, .mobile-login, .map-info'],
        changeHeight: function() {
            var elements = $(docHeightForElement.elements.toString());

            elements.css('height', '1px');
            elements.css('height', $(document).height());
            elements.css('max-height', $(document).height());

            $(window).on("orientationchange",function(){
                elements.css({
                    'height': '1px',
                    'max-height': '1px'
                });
                setTimeout(function(){
                    elements.css({
                        'height': $(document).height(),
                        'max-height': $(document).height(),
                    });
                }, 100);
            });
        }
    };
    /* end */

    // 2.4 ARTICLE LIST
    var articleList = {
        delay: 200,
        openMoreText: function(btn,text) {
            $(btn).click(function(e) {
                var cur = $(this);
                cur.toggleClass('opened');
                cur.parent().find(text).slideToggle(articleList.delay);
                e.preventDefault();
            });
        },
        pajax: function(container) {
            $(container).on('pjax:end',   function() {
                articleList.openMoreText('.article-more','.description');
            });
        },
        accrodionSingleItem: function(btn,container) {
            $(btn).click(function(e) {
                var cur = $(this);
                    cur.toggleClass('active');
                    cur.parent().find(container).slideToggle();
                e.preventDefault();
            });
        }
    };
    /* end */

    // 2.5 ARTICLE
    var article = {
        openPrintWindow: function(btn) {
            $(btn).click(function(e) {
                window.print();
                e.preventDefault();
            });
        }
    };
    /* end */

    // 2.6 REFERENCES
    var references = {
        openPrintWindow: function(btn) {
            if(window.location.hash == "#print" && $(btn).length) {
                $(btn).first().trigger('click')
            }
        }
    };
    /* end */

    // 2.7 SUBSCRIBE
    var forms = {
        clearAll: function(btnClear,btnSelect,checkboxes) {
            $(btnClear).click(function(e) {
                $(this).addClass('active');
                $(btnSelect).removeClass('active');
                $(checkboxes).find(':checkbox').prop('checked', false);
            });
        },
        selectAll: function(btnClear,btnSelect,checkboxes) {
            $(btnSelect).click(function(e) {
                $(this).addClass('active');
                $(btnClear).removeClass('active');
                $(checkboxes).find(':checkbox').prop('checked', true);
            });
        },
        close: function(btn,alert) {
            $(alert).on('click', btn, function() {
                $(this).parents(alert).fadeOut();
            });
        }
    };
    /* end */

// 3. SIDEBAR WIDGETS ---------

    // 3.1 MORE SIDEBAR NEWS
    var sidebarNews = {
        moreSidebarNews: function(btnMore,parent) {
            $(parent).next(btnMore).on('click',function(e) {
                var cur = $(this),
                    curParent =  cur.parents('li');
                    cur.toggleClass('showed');

                    if(cur.hasClass('showed')) {
                        curParent.find('li').addClass('opened');
                    } else {
                        curParent.find('li').removeClass('opened');
                    }

                e.preventDefault();
            });
        }
    };
    /* end */

    // 3.2 ARTICLES FILTER
    var articlesFilter = {
        classes: 'open',
        delay: 200,
        detectSubmenu: function(item) {
            $(item).each(function( index ) {
                var cur = $(this);
                if (cur.find('>.submenu').length > 0) {
                    cur.addClass('has-drop');
                }
            });
        },
        sort: function(btn,item) {
            $(item).each(function( index ) {
                var itemCur = $(this);

                var selectItem = itemCur.find('[data-select=selected]'),
                    selectText = selectItem.find('a').text();
                if(selectText.length > 0) {
                    itemCur.find(btn).text(selectText);
                }
            });
        },
        openItem: function(cur) {
            cur.parent().find('>ul').slideDown(headerMenu.delay);
            cur.parent().addClass('open');
        },
        closeItem: function(cur) {
            cur.parent().find('>ul').slideUp(headerMenu.delay);
            cur.parent().removeClass('open');
        },
        accordion: function(btn,content,parent) {
            parent.find('.'+headerMenu.classes).find(content).slideDown(0);

            parent.find('.'+headerMenu.classes).parents('li').addClass('open');
            parent.find('.'+headerMenu.classes).parents('.open').find('>ul').slideDown(0);

            btn.click(function(e) {
                var cur = $(this);
                if(cur.parent().hasClass(headerMenu.classes)){
                    articlesFilter.closeItem(cur);
                } else {
                    articlesFilter.openItem(cur);
                }
            });
        }
    };
    /* end */

    //EVENTS
    $(document).ready(function() {

        headerMenu.detectSubmenu('.header-menu-bottom-list .item');
        headerMenu.mobileScroll('.header-mobile  .header-bottom .header-menu-bottom-list');
        headerMenu.mobile($('.mobile-menu .has-drop >a'), '.submenu',$('.mobile-menu .header-menu-bottom-list'));

        dropDown($('.header-desktop .dropdown-link'), $('.drop-content'));
        dropDown($('.custom-select .dropdown-link'), $('.drop-content'));
        dropDown($('.sidebar-widget-reference-popup .dropdown-link'), $('.drop-content'));
        dropDown($('.tooltip-dropdown .icon-question'), $('.drop-content'));
        closeDropDown($('.sidebar-widget-reference-popup .icon-close'), $('.sidebar-widget-reference-popup .drop-content'), $('.sidebar-widget-reference-popup .dropdown-link '));
        closeDropDown($('.tooltip-dropdown .icon-close'), $('.tooltip-dropdown .drop-content'), $('.tooltip-dropdown .icon-question'));

        if(_window_width < _tablet ) {
            tabsFn($('.login-registration-list.mobile'), '.dropdown-widget');
            dropDown($('.btn-mobile-menu-show'), $('.drop-content'));
            dropDown($('.btn-mobile-search-show'), $('.drop-content'));
            dropDown($('.btn-mobile-login-show'), $('.drop-content'), true);
            closeDropDown($('.btn-mobile-menu-close'), $('.mobile-menu'), $('.btn-mobile-menu-show'));
            closeDropDown($('.btn-mobile-search-close'), $('.mobile-search'), $('.btn-mobile-search-show'));
            closeDropDown($('.btn-mobile-login-close'), $('.mobile-login'), $('.btn-mobile-login-show'));
        }
        //CONTENT
        article.openPrintWindow('.btn-print');
        references.openPrintWindow('.btn-print');
        faqAccordion.toggleItem($('.faq-accordion-list .title'), '.text',$('.faq-accordion-list'));
        faqAccordion.toggleItem($('.sidebar-accrodion-list .title'), '.text',$('.sidebar-accrodion-list'));
        sidebarNews.moreSidebarNews('.more-link','.sidebar-news-list');
        sidebarNews.moreSidebarNews('.more-link','.additional-references-list');
        sidebarNews.moreSidebarNews('.more-link','.key-references-list');
        sidebarNews.moreSidebarNews('.more-link','.further-reading-list');
    });

    $(window).resize(function() {

    });

    $(window).load(function() {
        $('.preloader').fadeOut();
        articleList.openMoreText('.article-more','.description');
        articleList.pajax('#w0');
        articleList.accrodionSingleItem('.mobile-accordion-link', '.drop-content');
        articlesFilter.detectSubmenu('.articles-filter-list .item');
        articlesFilter.sort('.custom-select-title','.custom-select');
        articlesFilter.accordion($('.articles-filter-list .icon-arrow'), '.submenu', $('.articles-filter-list'));
        docHeightForElement.changeHeight();
        headerMenu.desktop($('.header-desktop .header-menu-bottom-list > .has-drop >a'),$('.header-desktop .submenu'));
        forms.clearAll('.clear-all', '.select-all', '.checkboxes');
        forms.selectAll('.clear-all', '.select-all', '.checkboxes');
        forms.close('.close','.alert');
        search.autoSelect('.auto-search-list span','.search');
    });

})(jQuery);