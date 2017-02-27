
(function($) {

    // ELEMENTS
    var elements = {
        document: $(document),
        window: $(window),
        htmlBody: $('html, body')
    };

    //GLOBAL VARIABLE ---------
    var _window_height = elements.window.height(),
        _window_width = elements.window.width(),
        _doc_height = elements.document.height(),
        _mobile = 769,
        _tablet = 1025,
        _LSAccordionItem = localStorage.getItem('AccordionItemAdvanced'),
        _LSAccordionItemObj  = localStorage.getItem('AccordionItemsObjAdvanced');

    $(window).resize(function() {
        _window_width = $(window).width();
    });

    var advancedSearch = {
        customTagList: function(list,holder) {
            if($(list).length){
                $(holder).each(function( index ) {
                    var cur = $(this),
                        curInput = $(this).find('.my-single-field');

                    cur.find(list).tagit({
                        singleField: true,
                        placeholderText: 'Enter words separated with spaces',
                        singleFieldNode: curInput
                    });
                });
            }
        },
        searchHightLight: function(input,body){
            var mark = function() {
                var
                    keyword = $(input).val();

                $(body).unmark().mark(keyword);
            };
            if($(body).length) {
                mark();
                $(input).on("input", mark);
            }
        },
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

            $(window).on('orientationchange', function() {
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
        },
        saveSearch: function(btn) {
            function addToSave(element) {
                element.click(function(e) {
                    $.get(element.prop('href'), function(data, status) {
                    
                        if ('message' in data) {

                            var
                                parentEl = element.parent();

                            parentEl.find('.save-search-alert').html(data.message);
                            parentEl.addClass('added');

                            setTimeout(function(){
                                parentEl.removeClass('added');
                            }, 3000);
                        }
                    });
                    e.preventDefault();
                });
            }
            
            $(btn).each(function(){
                var element = $(this);
                addToSave(element);
            });
        },
        focusCustom: function(el) {
            $(el).on('click', function(e) {
                $(el).removeClass('focus');
                $(this).addClass('focus');
            });

            $(document).mouseup(function (e) {
                var container = $(el);
                if (container.has(e.target).length === 0){
                    container.removeClass('focus');
                }
            });
        },
        customTriggerFocus: function(btn,elClick,parent) {
            $(btn).on('click', function(e) {
                $(this).parents(parent).find(elClick).trigger('click');
            })
        }
    };

    var filterLoad = {
        scrollToLastPosition: function(position) {
            setTimeout(function() {
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

                localStorage.setItem('AccordionItemsObjAdvanced', JSON.stringify(LSFilter));
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

                filterLoad.scrollToLastPosition(_LSAccordionItem);
            };
        }
    };
    
    function dynamicFilter(filter) {
        var
            $cur = $(this),
            curTop = $cur.offset().top;

        localStorage.setItem('AccordionItem', curTop);
        $('.result-search').submit();
    }
   
    $(document).ready(function() {
        $('.item-filter-box').children('input:checkbox').bind('change', dynamicFilter);
        advancedSearch.customTagList('.my-tags-list', '.my-tags-holder');
        advancedSearch.searchHightLight('.search-results-top input', '.search-results-table-body .article-item');
        advancedSearch.cloneEl('.filter-clone', '.mobile-filter-container', '.filter-clone-holder');
        advancedSearch.openFilter('.filter-mobile-link', '.mobile-filter');
        advancedSearch.saveSearch('.btn-save-search');
        advancedSearch.focusCustom('.my-tags-list');
        advancedSearch.customTriggerFocus('.label-text-custom','.my-tags-list','.my-tags-holder');
        filterLoad.openInMobile();
    });

    elements.window.load(function() {
        filterLoad.setCheckResult('.sidebar-accrodion-item');
        filterLoad.showCheckedAccordion('.sidebar-accrodion-item');
    });
})(jQuery);