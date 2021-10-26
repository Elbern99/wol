(function($) {

// ELEMENTS
    var elements = {
        document: $(document),
        window: $(window),
        htmlBody: $("html, body"),
        searchResult: $('.search-results'),
        findExpert: $('.find-expert')
    };

//GLOBAL VARIABLES ---------

    var _window_height = elements.window.height(),
      _window_width = elements.window.width(),
      _doc_height = elements.document.height(),
      _click_touch = ('ontouchstart' in window) ? 'touchstart' : ((window.DocumentTouch && document instanceof DocumentTouch) ? 'tap' : 'click'),
      _mobile = 768,
      _tablet = 1025;

    elements.window.resize(function() {
        _window_height = elements.window.height();
        _window_width = elements.window.width();
        _doc_height = elements.document.height();
    });

// 1. HEADER ---------

    // 1.1 HEADER MENU

    var activeMenu = {
        getUrl: function(parent,activeClass) {
            if($(parent).length) {
                var pathname = window.location.pathname,
                  pathnameSearch =  window.location.search,
                  pathnameArr =  pathname.split('/'),
                  pathnameArrFirst = pathnameArr[1],
                  $parent = $(parent),
                  $resultLink = $parent.find('a[href$="'+pathname+'"]'),
                  checkResultLink = $resultLink.length > 0;

                if(pathnameArrFirst === 'spokespeople' || pathnameArrFirst === 'editors') {
                    pathnameArrFirst = 'authors';
                };

                if(!checkResultLink) {
                    pathname = '/' + pathnameArrFirst;
                };

                if(pathnameSearch !== '') {
                    activeMenu.setActive(pathnameSearch,'.submenu', parent, activeClass);
                };

                if(pathname !== '') {
                    activeMenu.setActive(pathname,'.submenu', parent, activeClass);
                };
            }
        },
        setActive: function(pathname,submenu,parent,activeClass) {
            var $parent = $(parent);
            $parent.find('a[href$="'+pathname+'"]').parents('li, .item').addClass(activeClass);
        }
    };

    var headerMenu = {
        classes: 'open',
        submenu: '>.submenu',
        delay: 200,
        detectSubmenu: function(item) {
            $(item).each(function( index ) {
                var
                  $cur = $(this),
                  $curLink = $cur.find('a'),
                  curLinkText = $curLink.attr('href'),
                  curLinkTextLast;

                if(curLinkText) {
                    curLinkTextLast = curLinkText.substr(curLinkText.lastIndexOf("/")+1);
                };

                if(curLinkTextLast == 'key-topics' && $cur.parent(".submenu").length > 0) {
                    $cur.addClass('no-submenu');
                };

                if(curLinkTextLast == 'articles' && $cur.parent(".submenu").length > 0) {
                    $cur.addClass('no-submenu');
                };

                if ($cur.find(headerMenu.submenu).length > 0) {
                    $cur.addClass('has-drop');
                }
            });
        },
        mobileScroll: function(item){

            var
              $item = $(item),
              scrollPaneOption = {
                  showArrows: true,
                  autoReinitialise: false,
                  animateScroll: true,
                  stickToleft: true
              },
              checkMobile = _window_width < _mobile;

            if(checkMobile) {
                $item.jScrollPane({showArrows: true});

                elements.window.on('load', function () {
                    $item.bind('jsp-initialised', function(event, isScrollable) {
                        headerMenu.mobileScrollToActive(item);

                    }).jScrollPane(scrollPaneOption);
                });
            }

            elements.window.on('resize', function() {
                if(checkMobile) {
                    $item.jScrollPane(scrollPaneOption);
                } else {
                    var
                      element = $item.jScrollPane({}),
                      api = element.data('jsp');
                    api.destroy();
                }
            });
        },
        mobileScrollToActive: function(item) {
            if($(item).length) {
                var
                  $item = $(item),
                  padding = 30,
                  $activePageInScrollMenu = $item.find('.active-page');

                if($activePageInScrollMenu.length) {
                    var
                      widthScrollMenu = $item.width() - padding,
                      activePageInScrollMenuWidth = $activePageInScrollMenu.width(),
                      activePageInScrollMenuLeft = $activePageInScrollMenu.position().left,
                      scrollToLeftPosition = activePageInScrollMenuLeft+activePageInScrollMenuWidth,
                      scrollToLeft = scrollToLeftPosition - widthScrollMenu;

                    if(scrollToLeftPosition > widthScrollMenu) {
                        var api = $item.data('jsp');
                        api.scrollTo(parseInt(scrollToLeft), parseInt(0));
                    }
                }
            }
        },
        mobileOpenItem: function(cur) {
            var
              $cur = cur,
              $curParent = $cur.parent();

            $curParent.find(headerMenu.submenu).slideDown(headerMenu.delay);
            $curParent.addClass('open');
        },
        mobileCloseItem: function(cur) {
            var
              $cur = cur,
              $curParent = $cur.parent();

            $curParent.find(headerMenu.submenu).slideUp(headerMenu.delay);
            $curParent.removeClass('open');
        },
        mobile: function(btn,content,parent) {
            parent.find('.'+headerMenu.classes).find(content).slideDown(0);
            $('.mobile-menu').on('click', '.has-drop >a', function(e) {
                var
                  $cur = $(this),
                  $curParent = $cur.parent(),
                  $curParentIndex = $curParent.index(),
                  checkReturn = true,
                  checkLast = $curParent.hasClass('no-submenu'),
                  checkHash = $cur.attr('href') == '#';

                if (checkHash) {
                    e.preventDefault();
                };

                setTimeout(function(){
                    docHeightForElement.changeHeight();
                }, 500);

                if($curParent.hasClass(headerMenu.classes)){
                    headerMenu.mobileCloseItem($cur);
                } else {

                    if (!checkLast) {
                        e.preventDefault();
                    };

                    if (checkHash) {
                        $curParent.siblings().removeClass(headerMenu.classes)
                          .find(content).slideUp(headerMenu.delay)
                          .find('.item').removeClass('open');
                    };

                    headerMenu.mobileOpenItem($cur);
                }
            });
        },
        desktop: function(parent, btn, dropWidget) {
            $(parent).on('click', btn, function(e) {
                var
                  cur = $(this),
                  curAttr = cur.attr('href'),
                  $dropWidget = $(parent).find(dropWidget),
                  $btn = $(parent).find(btn);

                elements.document.unbind('click.submenu');
                $('.submenu,.dropdown-widget').removeClass('open');
                $('.has-drop>a,.dropdown-login>a').not(cur).removeClass('active');

                if ( !cur.hasClass('active') ) {
                    if(curAttr !== '#') {
                        $('.has-drop a[href$="#"]').removeClass('active');
                        e.preventDefault();
                    } else {
                        $('.has-drop a').removeClass('active');
                        e.preventDefault();
                    }

                    var yourClick = true;
                    var drop = cur.parents('.has-drop').find('>.submenu');
                    drop.addClass('open');
                    cur.addClass('active');
                    elements.document.bind('click.submenu', function (e) {
                        if(_window_width > _mobile ) {
                            if (!yourClick  && !$(e.target).closest(drop).length || $(e.target).closest(drop.find('div')).length ) {
                                $dropWidget.removeClass('open');
                                $btn.removeClass('active');
                                elements.document.unbind('click.submenu');
                            }
                            yourClick  = false;
                        }
                    });
                } else {
                    $dropWidget.removeClass('open');
                    cur.removeClass('active');
                }
            });
        }
    };

    var mobileNavDrop = {
        open: function(btn,drop) {
            $(btn).click(function(){
                var
                  $cur = $(this),
                  $drop = $(drop);

                $drop.toggleClass('open');
                $cur.toggleClass('active');
            });
        },
        close: function(btn,drop) {
            $(btn).click(function(){
                var
                  $cur = $(this),
                  $drop = $(drop);

                $drop.toggleClass('open');
                $cur.toggleClass('active');
            });
        }
    };

    var hardCode = {
        templates: {
            about:  '<ul class="submenu">' +
            '<li><a href="/about">About IZA World of Labor</a></li> ' +
            '<li><a href="/editorial-board">Editorial board</a></li> ' +
            '<li><a href="/about/iza">About IZA</a></li>' +
            '<li><a href="/about/partners">About our partners</a></li>' +
            '</ul>'
        },
        appendCode: function(templte,item) {

            if($(item).length) {
                var $item = $(item);

                $item.addClass('item has-drop');
                $item.append(templte);
            }
        }
    };

    // 1.2 HEADER SEARCH

    var search = {
        autoSelect: function(btn,parent,drop) {
            $(parent).on('click', btn, function(e) {
                var $cur = $(this),
                  curText = $cur.text();
                $cur.parents(parent).find(':text').val(curText);
                $(drop).fadeOut();
                e.preventDefault();
            });
        }
    };

    /* dropDown */
    function dropDown(parent, btn, dropWidget) {

        if ( $(dropWidget).length ) {
            $(parent).on('click', btn, function(e) {
                var
                  $cur = $(this),
                  $parent = $(parent),
                  $dropWidget = $parent.find(dropWidget),
                  $btn = $parent.find(btn);

                elements.document.unbind('click.drop-content');

                $btn.not($cur).removeClass('active');

                $('.submenu').removeClass('open');
                $('.has-drop>a').not($cur).removeClass('active');

                if (elements.searchResult.length > 0) {
                    localStorage.removeItem('AccordionItemAdvanced');
                    localStorage.removeItem('AccordionItemsObjAdvanced');
                };

                if ( !$cur.hasClass('active') ) {
                    var yourClick = true,
                      drop = $cur.parents('.dropdown').find('>.drop-content');

                    drop.addClass('open');
                    $cur.addClass('active');

                    elements.document.bind('click.drop-content', function (e) {
                        if(_window_width > _mobile ) {
                            if (!yourClick  && !$(e.target).closest(drop).length || $(e.target).closest(drop.find('li')).length ) {
                                $dropWidget.removeClass('open');
                                $btn.removeClass('active');
                                elements.document.unbind('click.drop-content');
                            }

                            yourClick  = false;
                        }
                    });
                } else {
                    $cur.parent().find(dropWidget).removeClass('open');

                    $cur.removeClass('active');
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

    /* tabsForm */
    var tabsForm = {
        init: function(list, content) {

            var
              $list = list;

            $list.find('li').eq(0).find('a').addClass('active');
            $list.find('li').eq(0).find(content).addClass('active');

            $list.find('>li>a').on('click', function(e) {
                var  cur = $(this),
                  curParent = cur.parents('li');

                $list.find('a').removeClass('active');
                $list.find(content).removeClass('active').addClass('js-tab-hidden');

                if ( !cur.hasClass('active') ) {
                    cur.addClass('active');
                    curParent.find(content).addClass('active').removeClass('js-tab-hidden');
                } else {
                    cur.removeClass('active');
                    curParent.find(content).removeClass('active').addClass('js-tab-hidden');
                }
                e.preventDefault();
            });
        },
        openLogin: function() {
            $('.open-mobile-register').on('click', function(e) {
                $('.btn-mobile-menu-show').trigger('click');
                $('.btn-mobile-login-show').trigger('click');
                $('.login-registration-list.mobile li').eq(1).find('a').trigger('click');
                e.preventDefault();
            });
        },
        openRegister: function() {
            $('.open-mobile-login').on('click', function(e) {
                $('.btn-mobile-menu-show').trigger('click');
                $('.btn-mobile-login-show').trigger('click');
                $('.login-registration-list.mobile li').eq(0).find('a').trigger('click');
                e.preventDefault();
            });
        }
    };
    /* tabsForm end */

    /* tabs */
    var tabs = {
        switcher: function(list,tabs,item) {
            $(list).on('click', '.js-widget', function(e) {
                var cur = $(this),
                  curParent = cur.parent(),
                  curParentIndex = curParent.index(),
                  curDataLinked = cur.data('linked');

                curParent.addClass('active').siblings().removeClass('active');
                $('div[data-linked="1"]').addClass('js-tab-hidden');

                if(curDataLinked !== undefined) {
                    $('div[data-linked="'+curDataLinked+'"]').removeClass('js-tab-hidden').addClass('active');
                }

                if(cur.hasClass('active')) {
                    $(tabs).find(item).eq(curParentIndex).addClass('active').removeClass('js-tab-hidden').siblings().removeClass('active').addClass('js-tab-hidden');
                }

                $(tabs).find(item).eq(curParentIndex).addClass('active').removeClass('js-tab-hidden').siblings().removeClass('active').addClass('js-tab-hidden');
                e.preventDefault();
            });

            elements.window.on('orientationchange', function() {
                setTimeout(function(){
                    if(_window_width > _mobile) {
                        $(list).find('li').eq(0).find('a').trigger('click');
                    }
                }, 10);
            });
        },
        cloneTab: function(el,elToMobile,elToDesktop){
            if($(el).length) {
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

                elements.window.on('orientationchange', function() {
                    setTimeout(function(){
                        setTimeout(function(){
                            appendElements(el,elToMobile,elToDesktop);
                        }, 600);
                    }, 10);
                });
            }
        }
    };
    /* tabs end */

// 2. CONTENT ---------

    // 2.1 ACCORDION
    var accordion = {
        classes: 'is-open',
        delay: 200,
        openItem: function(cur) {
            cur.next().slideDown(accordion.delay);
            cur.parent().addClass('is-open');
        },
        closeItem: function(cur) {
            cur.next().slideUp(accordion.delay);
            cur.parent().removeClass('is-open');
        },
        toggleItem: function(btn,content,parent) {
            parent.find('.'+accordion.classes).find(content).slideDown(0);
            btn.click(function(e) {
                var cur = $(this);

                if(cur.parent().hasClass(accordion.classes)){
                    accordion.closeItem(cur);
                } else {
                    accordion.openItem(cur);
                }
                e.preventDefault();
            });
        },
        scrollToHash:function(accordionList) {
            if ($(accordionList).length) {
                var hashItem = window.location.hash;

                if(hashItem !== '') {
                    var $item = $('a[href$="'+hashItem+'"]').parent(),
                      itemTop = $item.offset().top;

                    elements.htmlBody.animate({ scrollTop: itemTop }, 300);
                    $item.addClass('is-open').find('.text').slideDown(0);
                }
            }
        }
    };
    /* end */

    // 2.2 DOC HEIGHT FOR ELEMENTS
    var docHeightForElement = {
        elements: [],
        changeHeight: function() {
            var elementsAll = $(docHeightForElement.elements.toString()),
              elHeight = elements.document.height();

            elementsAll.css('height', '100vh');
            elementsAll.css('height', elHeight);
            elementsAll.css('max-height', elHeight);

            elements.window.on("orientationchange",function(){
                elementsAll.css({
                    'height': '100vh',
                    'max-height': '100vh'
                });
                setTimeout(function(){
                    elementsAll.css({
                        'height': elements.document.height(),
                        'max-height': elements.document.height(),
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
                var
                  cur = $(this),
                  curParent = cur.parent();

                cur.toggleClass('opened');
                curParent.toggleClass('opened-article');
                curParent.find(text).slideToggle(articleList.delay);
                e.preventDefault();
            });
        },
        pajax: function(container) {
            $(container).on('pjax:end', function() {
                articleList.openMoreText('.article-more','.description');
                text.sliceText();
            });
        },
        pajaxLoader: function(container) {
            var container = $(container);

            container.on('pjax:end', function() {
                var curButtton = $(this).find('.btn-gray');
                curButtton.removeClass("loaded-timer");
            });

            container.on('pjax:click', function() {
                var curButtton = $(this).find('.btn-gray');
                curButtton.addClass("loaded-timer");
            });

            elements.document.on('click','.loaded-timer',function() {
                return false;
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
            var
              $checkboxes = $(checkboxes);


            $checkboxes.on('click', btnClear, function(e) {
                if($('.search-site').length) {
                    $checkboxes.find('.field-advancedsearchform-types').find('.grid-item:not(:first)').find(':checkbox:checked').trigger('click');
                } else {
                    $checkboxes.find('.grid-line.four').find(':checkbox:checked').trigger('click');
                }
            });

        },
        selectAll: function(btnClear,btnSelect,checkboxes) {
            var
              $checkboxes = $(checkboxes);

            $checkboxes.on('click', btnSelect, function(e) {
                $checkboxes.find('.grid-line.four, .field-advancedsearchform-types').find(':checkbox:not(:checked)').trigger('click');
            });
        },
        close: function(btn,alert) {
            $(alert).on('click', btn, function() {
                $(this).parents(alert).fadeOut();
            });
        },
        clearAllCheckboxes: function(btn) {
            if($(btn).length) {
                var
                  $btn =  $(btn);

                $btn.each(function( index ) {
                    var
                      $cur = $(this),
                      $curParent = $cur.parent(),
                      accordionItem = $cur.parents('.sidebar-accrodion-item'),
                      checkDisabled = $curParent.find(':checkbox:not(:disabled)'),
                      checkChecked = true;

                    for(var i = 0; i < checkDisabled.length; i++) {
                        if(!checkDisabled[i].checked) checkChecked = false;
                    }

                    if(checkChecked) {
                        $cur.addClass('active').text('Clear all');
                    } else {
                        $cur.removeClass('active').text('Select all') ;
                    }
                });

                $btn.click(function(e) {
                    var
                      $cur = $(this),
                      $curParent = $cur.parents('li'),
                      $searchCheckBoxCheckedFirst = ':checkbox:checked',
                      $searchCheckBoxChecked = '.checkbox-list>li>label>:checkbox:checked',
                      $searchCheckBox = '.checkbox-list :checkbox:not(:checked)',
                      $expertCheckBoxChecked = 'div>.item>label>:checkbox:checked',
                      $expertCheckBox = 'div>.item>label>:checkbox:not(:checked)';

                    $cur.toggleClass('active');

                    if($cur.hasClass('active')) {
                        if (elements.searchResult.length > 0) {
                            $curParent.find($searchCheckBox).trigger('click');
                        };

                        if (elements.findExpert.length > 0) {
                            $curParent.find($expertCheckBox).trigger('click');
                        };

                        $cur.text('Clear all');
                        $curParent.addClass('now-click-trigger');
                    } else {

                        if (elements.searchResult.length > 0) {
                            $cur.parents('li').find($searchCheckBoxCheckedFirst).trigger('click');
                        };

                        if (elements.findExpert.length > 0) {
                            $cur.parents('li').find($expertCheckBoxChecked).trigger('click');
                        };

                        $cur.text('Select all');
                        $curParent.addClass('now-click-trigger');
                    };

                    e.preventDefault();
                });
            }
        }
    };
    /* end */

// 3. SIDEBAR WIDGETS ---------

    // 3.1 MORE SIDEBAR NEWS
    var sidebarNews = {
        moreSidebarNews: function(btnMore,parent,item,step,itemDisplay,animateDelay) {

            $(parent).next(btnMore).each(function() {
                var
                  cur = $(this),
                  curParent =  cur.parents('li,.expand-more'),
                  curHiddenEl = curParent.find(item).length;

                if(itemDisplay !== undefined) {
                    curHiddenEl = curHiddenEl - (itemDisplay-step);
                }

                cur.attr("data-length-hidden", curHiddenEl);
                cur.attr("data-count", step);
            });

            $(parent).next(btnMore).on('click',function(e) {
                var cur = $(this),
                  curNode = this,
                  curParent =  cur.parents('li,.expand-more'),
                  delay = 200;

                if (animateDelay !== undefined)  {
                    delay = animateDelay;
                };

                if(!cur.hasClass('no-open')) {

                    var arrayItems = item.split(',');

                    for (var i = 0; i < arrayItems.length; i++) {
                        var newItemArray  = arrayItems[i] + ':hidden:lt(' + step + ')';
                        arrayItems[i] = newItemArray;
                    };

                    var
                      allHiddenCount = curNode.getAttribute('data-length-hidden'),
                      curCount = parseInt(curNode.getAttribute('data-count')),
                      nextCount = curCount +(curParent.find(arrayItems.join()).addClass('hidden')).length,
                      nextAllHiddenElements = curParent.find(arrayItems.join()).addClass('hidden');

                    if(curCount < allHiddenCount) {
                        nextAllHiddenElements.addClass('hidden').slideDown(delay);
                        curNode.setAttribute('data-count', nextCount);
                    } else {
                        curParent.find('.hidden').slideUp(delay);
                        curNode.setAttribute("data-count", step);
                        cur.removeClass('showed');

                        setTimeout(function(){
                            elements.htmlBody.animate({ scrollTop: curParent.offset().top - 100 }, 0);
                        }, delay+1);
                    }

                    if(parseInt(curNode.getAttribute('data-count')) == allHiddenCount) {
                        cur.addClass('showed');
                    }

                    e.preventDefault();
                }
            });
        },
        detectMore: function(item,btn) {

            var itemEl = $(item);

            if(itemEl.length) {
                itemEl.each(function( index ) {
                    var
                      $cur = $(this),
                      $curLink = $cur.find('.more-link'),
                      curCount = $curLink.data('count'),
                      curCountHidden = $curLink.data('length-hidden');

                    if(curCount == curCountHidden) {
                        $curLink.addClass('showed');
                    }

                    if($cur.find(btn).length == 0) {
                        $cur.find('div >ul').addClass('no-more');
                    }
                });
            }
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
        openItem: function(parent) {
            var
              $curPrent = parent;

            $curPrent.find('>ul').slideDown(headerMenu.delay);
            $curPrent.addClass('open');
        },
        closeItem: function(parent) {
            var
              $curPrent = parent;

            $curPrent.find('>ul').slideUp(headerMenu.delay);
            setTimeout(function(){ $curPrent.removeClass('open'); }, headerMenu.delay);
        },
        accordion: function(btn,content,parent) {
            var
              $parent = $(parent);

            $parent.find('.'+headerMenu.classes).find(content).slideDown(0)
            $parent.find('.'+headerMenu.classes).parents('li').addClass('open')
            $parent.find('.'+headerMenu.classes).parents('.open').find('>ul').slideDown(0);

            $(btn).click(function(e) {
                var
                  $cur = $(this),
                  $curParent = $cur.parents('.item'),
                  curAttr = $cur.attr('href'),
                  checkClass = $curParent.hasClass(headerMenu.classes),
                  checkLink = curAttr === '#';

                if (checkLink) {
                    e.preventDefault();
                }

                if(checkClass){
                    articlesFilter.closeItem($curParent);
                } else {
                    articlesFilter.openItem($curParent);
                }
            });
        }
    };
    /* end */

    /* tooltipWhenAddToFav */
    var tooltipWhenAddToFav = {
        openLogin: function(btn,list,mobileTrigger) {
            elements.document.on('click', btn, function(e) {
                elements.htmlBody.animate({ scrollTop: 0 }, 200);
                if(_window_width > _mobile) {
                    $('.header-desktop .dropdown-login > .dropdown-link').trigger('click');
                } else {
                    $(mobileTrigger).trigger('click');
                    $(list).find('li').eq(0).find('a').trigger('click');
                }
                e.preventDefault();
            });
        },
        openRegister: function(btn,list,mobileTrigger) {
            elements.document.on('click', btn, function(e) {
                if(_window_width < _mobile) {
                    $(mobileTrigger).trigger('click');
                    $(list).find('li').eq(1).find('a').trigger('click');
                    elements.htmlBody.animate({ scrollTop: 0 }, 200);
                    e.preventDefault();
                }
            });
        }
    };
    /* tooltipWhenAddToFav end */

    // 3.4 SHARE BUTTONS

    var shareBtns = {
        btnContent: function(item) {
            if($(item).length) {

                //get
                var metaTitle = $('#title-document').text(),
                  desc = $('meta[name="description"]').attr("content"),
                  titleClear,
                  descClear,
                  descText = $('.content-inner-text p').text(),
                  title = $('.content h1').text(),
                  url = document.URL,
                  urlArray = url.split("//"),
                  urlText = urlArray[1];

                if(desc === undefined) {
                    descClear = descText;
                } else{
                    descClear = desc;
                }

                if(title === undefined) {
                    title = metaTitle;
                }

                descClear = descClear.replace(/\s+/g," ");
                titleClear = title.replace(/\s+/g," ");

                function sliceText(text,symbols) {
                    var slicedText = text.slice(0,symbols);
                    if (slicedText.length < text.length) {
                        slicedText += '...';
                    }

                    return slicedText;
                }

                var
                  slicedDesc = sliceText(descClear,240),
                  slicedTitle = sliceText(titleClear,140);

                //set
                var linkEdn = "http://www.linkedin.com/shareArticle?mini=true&url="+url+"&title="+slicedTitle+"&summary="+slicedDesc+"",
                  twitter = "http://twitter.com/share/?url=http%3A%2F%2F"+urlText+"&via=IZAWorldofLabor&related=IZAWorldofLabor&text="+slicedTitle+"",
                  facebook = 'http://facebook.com/dialog/share?display=popup&href='+url+'&title='+slicedTitle+'&description='+slicedDesc+'&app_id=686925074844965';

                $(item).each(function() {
                    var cur = $(this);
                    cur.find('.twitter-content').attr('href', twitter);
                    cur.find('.linkedin-content').attr('href', linkEdn);
                    cur.find('.facebook-content').attr('href', facebook);
                });
            }
        }
    };

    // 3.5 HOME

    var Cookie = {
        Create: function (name, value, days) {
            var expires = "";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toGMTString();
            }
            document.cookie = name + "=" + value + expires + "; path=/";
        },
        Read: function (name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(";");
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == " ") c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        },
        Erase: function (name) {
            Cookie.create(name, "", -1);
        }
    };

    var home = {
        cloneTopic: function(el,elToMobile,elToDesktop){
            if($(el).length) {
                function appendElements(el,elToMobile,elToDesktop) {
                    if($(elToMobile).length) {
                        var
                          $elHtml = $(el),
                          $elToMobile = $(elToMobile),
                          $elToDesktop = $(elToDesktop);

                        if (_window_width < _mobile) {
                            $elToMobile.append($elHtml);
                        } else {
                            $elToDesktop.append($elHtml);
                        }
                    }
                }

                appendElements(el,elToMobile,elToDesktop);

                elements.window.on('orientationchange', function() {
                    setTimeout(function(){
                        appendElements(el,elToMobile,elToDesktop);
                    }, 600);
                });
            }
        },
        closeSubscribe: function(btn,parent) {
            var $parent = $(parent);

            if($parent.length) {
                var cookieName = 'close_subscribse';
                var _this = this;

                $(btn).click(function(e) {

                    $parent = $(this).parents(parent);

                    e.preventDefault();
                    _this.addCloseCookie(cookieName,  $parent);
                });
            };
        },
        closeCookiesNotice: function(btn,parent) {
            var $parent = $(parent);

            if($parent.length) {
                var cookieName = 'cookies_notice';
                var _this = this;

                $(btn).click(function(e) {

                    $parent = $(this).parents(parent);

                    e.preventDefault();
                    _this.addCloseCookie(cookieName,  $parent);
                });
            };
        },
        addCloseCookie: function(name, $parent) {

            $.get('/site/add-close-cookie', 'name='+name)
              .success(function( data ) {
                  if (data.result) {
                      $parent.fadeOut(200);
                  }
              });
        }
    };

    //3.6 INNER PAGES

    var innerPages = {
        countChildren: function(list) {
            if($(list).length) {
                $list = $(list);

                $list.find('>li').each( function( i, v ) {
                    var $item = $(this),
                      children = $item.find('>ul'),
                      length = 0,
                      className = 'many';

                    if(children.length>0) {
                        length = children[0].childElementCount;
                    };

                    if(length>1) {
                        className = 'many';
                    }
                    $item.addClass(className);
                });
            }
        },
        subjectAreaMobile: function(item) {

            if($(item).length) {
                var
                  $item = $(item),
                  pathName = document.location.pathname,
                  $link = $item.find('a[href$="'+pathName+'"]'),
                  $linkPrent = $link.parent(),
                  $articleHead = $('.article-head');

                $linkPrent.addClass('open');
                $linkPrent.parent().addClass('open');
            }
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
                elements.htmlBody.animate({scrollTop : 0},delay);
                return false;
            });

            function setLeft(btn) {

                if($('.sidebar-right').length) {
                    var
                      $sidebarRight = $('.sidebar-right'),
                      $stiky = $('.stiky'),
                      stikyLeft = $sidebarRight.offset().left,
                      stikyWidth = $sidebarRight.width(),
                      stikyHeight = $sidebarRight.height(),
                      btnWidth = 30,
                      $btn = $(btn),
                      left = stikyLeft+stikyWidth-btnWidth,
                      bottom = '35px',
                      bottomCount = bottom;

                    if($stiky.length) {
                        bottomCount = stikyHeight;
                    }

                    if(_window_width > _mobile) {
                        $btn.css({
                            'left': left,
                            'right': 'auto',
                            'bottom': bottom
                        });
                    } else {
                        $btn.css({
                            'left': 'auto',
                            'right': '15px',
                            'bottom': bottomCount
                        });
                    }
                }
            }

            setLeft(btn);
            elements.window.resize(function() {
                setLeft(btn);
            });
        }
    };

    var calcHeight = {
        setheight: function(item) {
            if($(item).length && _window_width < _tablet) {
                var
                  $bg = $(item);

                $bg.css('height', _window_height *  0.4);

                elements.window.on('orientationchange', function() {
                    setTimeout(function(){
                        $bg.css('height', _window_height * 0.4);
                    }, 0);
                });
            }
        }
    };

    var filter = {
        clearStorage: function() {

            var
              $searchInput = $('.search-holder :text');

            if (elements.searchResult.length < 1) {
                localStorage.removeItem('AccordionItemAdvanced');
                localStorage.removeItem('AccordionItemsObjAdvanced');
            };

            if (elements.findExpert.length < 1) {
                localStorage.removeItem('AccordionItemExpert');
                localStorage.removeItem('AccordionItemsObjExpert');
            };

            $searchInput.keyup(function() {
                localStorage.removeItem('AccordionItemAdvanced');
                localStorage.removeItem('AccordionItemsObjAdvanced');
                localStorage.removeItem('AccordionItemExpert');
                localStorage.removeItem('AccordionItemsObjExpert');
            });
        }
    };

    var text = {
        sliceText:function() {

            var
              checkOpinionVideoItems = $('.video-item').length>0 || $('.opinion-item').length>0 || $('.s-opinion-item').length>0;

            if (checkOpinionVideoItems) {

                function truncate(str, maxlength) {
                    if (str.length > maxlength) {
                        str = str.slice(0, maxlength - 3);
                        str = str.trim();

                        var $strArray = str.split(' '),
                          symbols = [',','.','...','-','!','?','–',''];

                        $strArray[$strArray.length - 1] = '';

                        var
                          preLastWord = $strArray[$strArray.length - 2].split(''),
                          preLastSymbol = preLastWord[preLastWord.length - 1],
                          checkSymbols = symbols.indexOf(preLastSymbol);

                        if(checkSymbols > -1) {
                            $strArray[$strArray.length - 2] = '';
                            $strArray[$strArray.length - 3] = $strArray[$strArray.length - 3] + '...';
                        } else {
                            preLastWord = preLastWord.join('');
                            $strArray[$strArray.length - 2] = preLastWord + '...';
                        }

                        str = $strArray.join(' ');
                    }
                    return str;
                };

                function sliceTextItem(items) {
                    $(items).each(function(i){
                        var cur = $(this),
                          curIndex = cur.parent().index(),
                          checkImg = cur.hasClass('has-image'),
                          checkFirst = curIndex === 0,
                          curTitleTag = cur.find('h2'),
                          curTitleTagLink = curTitleTag.find('a'),
                          curTitleText = curTitleTagLink.text(),
                          textLong = 150,
                          titleLong = 70,
                          $videosPage = $('.videos-page'),
                          $opinionsPage = $('.opinions-page'),
                          $checkOpionionsOrVideosPage = $videosPage.length>0 || $opinionsPage.length>0,
                          checkParagraph = cur.find('p').length > 0;

                        if((checkFirst && $checkOpionionsOrVideosPage) && _mobile+1 <_window_width ) {
                            textLong = 500;
                            titleLong = 90;
                        }

                        if( _mobile+1 <= _window_width && _window_width <_tablet && !checkFirst ) {
                            textLong = 100;
                            titleLong = 45;
                        }

                        if( _mobile+1 <= _window_width && _window_width <_tablet ) {
                            textLong = 120;
                            titleLong = 45;
                        }

                        if(!checkImg && checkParagraph) {
                            textLong = 250;
                            titleLong = 150;

                            if( _mobile+1 <= _window_width && _window_width <_tablet && !checkFirst ) {
                                textLong = 170;
                                titleLong = 150;
                            };
                        }

                        if(!checkImg && !checkParagraph) {
                            titleLong = 150;
                        }

                        if(checkImg && !checkParagraph) {
                            titleLong = 100;
                        }

                        if(checkParagraph) {

                            cur.find('p').each(function(i){
                                var curParagraphTag = $(this),
                                  curParagraphText = curParagraphTag.text();

                                if (curParagraphText.trim().length > 0) {
                                    curParagraphTag.text(truncate(curParagraphText, textLong));

                                    cur.find('p').css('display','none');

                                    curParagraphTag.css({
                                        opacity:'1',
                                        display: 'block'
                                    });
                                    return false;
                                }
                            });
                        };

                        curTitleTagLink.text(truncate(curTitleText, titleLong));

                        curTitleTag.css({
                            opacity:'1',
                            display: 'block'
                        });
                    })
                }

                sliceTextItem('.video-item, .opinion-item, .s-opinion-item');

                elements.window.on('orientationchange', function() {
                    setTimeout(function(){
                        sliceTextItem('.video-item, .opinion-item, .s-opinion-item');
                    }, 10);
                });
            }
        },
        hideEmpty: function(text) {
            $(text).hide(0);
        }
    };

    //EVENTS
    elements.document.ready(function() {
        calcHeight.setheight('.header-background');
        shareBtns.btnContent('.share-buttons-list li') ;
        headerMenu.detectSubmenu('.header-menu-bottom-list .item');
        dropDown('.header-desktop', '.dropdown-link', '.drop-content');
        dropDown('.custom-select', '.dropdown-link', '.drop-content');
        dropDown('.sidebar-widget-reference-popup', '.dropdown-link', '.drop-content');
        dropDown('.tooltip-dropdown', '.icon-question', '.drop-content');
        closeDropDown($('.sidebar-widget-reference-popup .icon-close'), $('.sidebar-widget-reference-popup .drop-content'), $('.sidebar-widget-reference-popup .dropdown-link '));
        closeDropDown($('.tooltip-dropdown .icon-close'), $('.tooltip-dropdown .drop-content'), $('.tooltip-dropdown .icon-question'));
        innerPages.backToTop('.back-to-top');
        text.sliceText();
        innerPages.countChildren('.classification-list');

        if(_window_width < _tablet ) {
            mobileNavDrop.open('.btn-mobile-menu-show','.mobile-menu');
            mobileNavDrop.open('.btn-mobile-search-show','.mobile-search');
            mobileNavDrop.open('.btn-mobile-login-show','.mobile-login');
            mobileNavDrop.close('.btn-mobile-menu-close','.mobile-menu');
            mobileNavDrop.close('.btn-mobile-search-close','.mobile-search');
            mobileNavDrop.close('.btn-mobile-login-close','.mobile-login');
            tabsForm.init($('.login-registration-list.mobile'), '.dropdown-widget');
            tabsForm.openLogin();
            tabsForm.openRegister();
            accordion.toggleItem($('.events-list .title'), '.text',$('.events-list'));
            tabs.switcher('.mobile-filter-list','.mobile-filter-items','.tab-item');
            headerMenu.mobile($('.mobile-menu .has-drop >a'), '.submenu',$('.mobile-menu .has-drop >a'));
            tabs.cloneTab('.post-list-clone','.tab-item.empty','.post-list-clone-holder');
            home.cloneTopic('.clone-topics','.articles-list-holder','.clone-topics-widget');
            innerPages.subjectAreaMobile('.tab-item-subject-areas');
        }
        //CONTENT
        article.openPrintWindow('.btn-print');
        references.openPrintWindow('.btn-print');
        accordion.toggleItem($('.faq-accordion-list .title'), '.text',$('.faq-accordion-list'));
        accordion.toggleItem($('.sidebar-accrodion-list .title'), '.text',$('.sidebar-accrodion-list'));
        sidebarNews.moreSidebarNews('.more-link','.sidebar-news-list','li,.item',5);
        sidebarNews.moreSidebarNews('.more-link','.additional-references-list','li,.item',13);
        sidebarNews.moreSidebarNews('.more-link','.key-references-list','li',13);
        sidebarNews.moreSidebarNews('.more-link','.further-reading-list','li,.item',13);
        sidebarNews.moreSidebarNews('.more-link','.sidebar-key-topics-list','li,.item',13);
        sidebarNews.moreSidebarNews('.more-link','.more-extra-list','li,.item',13);
        sidebarNews.moreSidebarNews('.more-link','.articles-filter-list','li,.item',13);
        sidebarNews.moreSidebarNews('.btn-load-more-client-side','.former-editor-list','.editor-item',3,3,0);
        sidebarNews.moreSidebarNews('.btn-load-more-client-side','.associate-editor-list','.editor-item',3,9,0);
        hardCode.appendCode(hardCode.templates.about, '.header-menu-top-list li:nth-child(3)');
/*         hardCode.appendCode(hardCode.templates.commentary, '.header-menu-bottom-list >.item:nth-child(6)');
        hardCode.appendCode(hardCode.templates.key, '.header-menu-bottom-list >.item:nth-child(1)'); */
        activeMenu.getUrl('.header', 'active-page');
        activeMenu.getUrl('.articles-filter-list', 'open');
        activeMenu.getUrl('.sidebar-news-list', 'open');
        headerMenu.mobileScroll('.header-mobile  .header-bottom .header-menu-bottom-list');
    });

    elements.window.on('load', function() {
        home.closeSubscribe('.sticky-newsletter .icon-close','.sticky-newsletter');
        home.closeCookiesNotice('.cookie-notice .icon-close','.cookie-notice');

        if (elements.findExpert.length < 1 || elements.searchResult.length < 1) {
            $('.preloader').fadeOut();
        };

        articleList.openMoreText('.article-more','.description');
        articleList.pajax('.loader-ajax');
        articleList.pajaxLoader('.loader-ajax');
        articleList.accrodionSingleItem('.mobile-accordion-link', '.drop-content');
        articlesFilter.detectSubmenu('.articles-filter-list .item');
        articlesFilter.sort('.custom-select-title','.custom-select');
        articlesFilter.accordion('.articles-filter-list .icon-arrow', '.submenu', '.articles-filter-list');
        articlesFilter.accordion('.articles-filter-list strong>a', '.submenu', '.articles-filter-list');
        tooltipWhenAddToFav.openLogin('.fav-login', '.login-registration-list.mobile', '.btn-mobile-login-show');
        tooltipWhenAddToFav.openRegister('.fav-register', '.login-registration-list.mobile', '.btn-mobile-login-show');
        docHeightForElement.changeHeight();
        headerMenu.desktop('.header-desktop .header-menu-bottom-list', '> .has-drop >a', '.submenu');
        headerMenu.desktop('.header-desktop .header-menu-top-list', '> .has-drop >a', '.submenu');
        forms.clearAll('.clear-all', '.select-all', '.content-types');
        forms.clearAll('.clear-all', '.select-all', '.checkboxes-holder');
        forms.clearAll('.clear-all', '.select-all', '.dropdown-login');
        forms.clearAll('.clear-all', '.select-all', '.grid');
        forms.selectAll('.clear-all', '.select-all', '.content-types');
        forms.selectAll('.clear-all', '.select-all', '.checkboxes-holder');
        forms.selectAll('.clear-all', '.select-all', '.dropdown-login');
        forms.selectAll('.clear-all', '.select-all', '.grid');
        forms.close('.close','.alert');
        search.autoSelect('.auto-search-list span','.search', '.header-search-dropdown') ;
        forms.clearAllCheckboxes('.sidebar-widget-filter .clear-all');
        accordion.scrollToHash('.faq-accordion-list');
        filter.clearStorage();
        sidebarNews.detectMore('.sidebar-accrodion-item','.more-link');
        sidebarNews.detectMore('.mobile-filter-items','.more-link');
    });

})(jQuery);

(function($) {
    $(document).ready(function(){
        $(window).scroll(function(){

            if ($(window).scrollTop() > 100){
                if (!localStorage.getItem('already')) {
                    $('#asking').css('display','block')
                }
            }
        });

        if ($(window).scrollTop() > 100 && !localStorage.getItem('already')){
            $('#asking').css('display','block')
        }

        $('.remember-alert').click(function(e){
            localStorage.setItem('already',true);
            $('#asking').css('display','none')
        });

        $('#asking a').click(function(e){
            e.preventDefault();
            localStorage.setItem('already',true);
            window.location.href = $(this).attr('href');
        });
    });

    $(window).load(function(){
        let firstVisit = true 
        $(window).on('keydown',function(EO){
            if(firstVisit && EO.keyCode === 9){
                EO.preventDefault()
                console.log(123)
                $('.screen_reader_btn').focus()
                firstVisit = false
            }
        })

        $($('h1')[0]).attr('id','screen_reader_content')
    })
})(jQuery);
