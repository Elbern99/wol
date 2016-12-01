(function($) {

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

    //ARTICLE
    var article = {
        delay: 0,
        closeReference: function(btn,parent) {
            $(btn).click(function(e) {
                $('a').removeClass('opened-reflink');
                $(this).parents(parent).fadeOut(article.delay);
                $('.icon-circle-arrow').removeClass('disabled');
                $(parent).find('.arrows').fadeOut(0);
                e.preventDefault();
            });
        },
        changeContentPupop: function(cur){
            var curCaption = cur.parent().find('.caption').html(),
                curSources = cur.parent().find('.sources').html(),
                curTypes = cur.parent().find('.types').html(),
                curMethods = cur.parent().find('.methods').html(),
                curCountries = cur.parent().find('.countries').html(),
                curFurnitureReading = cur.parent().find('.further-reading-info').html(),
                curBgInfo = cur.parent().find('.bg-info').html(),
                curAdditional = cur.parent().find('.additional-references-info').html(),
                popup = $('.reference-popup');

            var
                caption = popup.find('.caption'),
                sources = popup.find('.sources'),
                types = popup.find('.types'),
                methods = popup.find('.methods'),
                countries = popup.find('.countries'),
                furnitureReading = popup.find('.furniture-reading'),
                additional = popup.find('.additional-references'),
                bgInfo = popup.find('.bg-info');

            furnitureReading.parent().removeClass('visible');
            caption.parent().removeClass('visible');
            sources.parent().removeClass('visible');
            types.parent().removeClass('visible');
            methods.parent().removeClass('visible');
            countries.parent().removeClass('visible');
            additional.parent().removeClass('visible');
            bgInfo.parent().removeClass('visible');

            if(typeof curFurnitureReading !== "undefined") {
                if (curFurnitureReading.length > 0) {
                    furnitureReading.html(curFurnitureReading);
                    furnitureReading.parent().addClass('visible');
                }
            }
            if(typeof curCaption !== "undefined") {
                if(curCaption.length > 0) {
                    caption.html(curCaption);
                    caption.parent().addClass('visible');
                }
            }
            if(typeof curSources !== "undefined") {
                if(curSources.length > 0) {
                    sources.html(curSources);
                    sources.parent().addClass('visible');
                }
            }
            if(typeof curTypes !== "undefined") {
                if(curTypes.length > 0) {
                    types.html(curTypes);
                    types.parent().addClass('visible');
                }
            }
            if(typeof curMethods !== "undefined") {
                if(curMethods.length > 0) {
                    methods.html(curMethods);
                    methods.parent().addClass('visible');
                }
            }
            if(typeof curCountries !== "undefined") {
                if(curCountries.length > 0) {
                    countries.html(curCountries);
                    countries.parent().addClass('visible');
                }
            }
            if(typeof curBgInfo !== "undefined") {
                if(curBgInfo.length > 0) {
                    bgInfo.html(curBgInfo);
                    bgInfo.parent().addClass('visible');
                }
            }
            if(typeof curAdditional !== "undefined") {
                if(curAdditional.length > 0) {
                    additional.html(curAdditional);
                    additional.parent().addClass('visible');
                }
            }
        },
        openTooltip: function(btn,parent) {
            $(btn).click(function(e) {
                var cur = $(this);
                $(parent).fadeIn(article.delay);
                $('li').removeClass('opened-reflink');
                cur.parent('li').addClass('opened-reflink');
                $(parent).find('.arrows').fadeOut(0);
                article.changeContentPupop(cur);
            });
        },
        openReference: function(btn,parent) {
            $(btn).click(function(e) {
                var
                    cur = $(this),
                    curAttr = cur.attr('href'),
                    keyLink = $('.content-inner-text a[href$="'+curAttr+'"]').first();
                //action
                $('li').removeClass('opened-reflink');
                cur.parent().addClass('opened-reflink');
                $(parent).fadeOut(article.delay);
                $(parent).fadeIn(article.delay);

                if(keyLink.length>0){
                    keyLink.trigger('click');
                } else {
                    cur.parent().find('.rel-tooltip').trigger('click');
                }

                e.preventDefault();
            });
        },
        openReferenceTextLink: function(btn,parent){
            $(btn).click(function(e) {
                var
                    cur = $(this),
                    curAttr = cur.attr('href'),
                    keyLink = $('.key-references-list a[href$="'+curAttr+'"]');
                $('.text-reference').removeClass('text-reference-opened');
                cur.addClass('text-reference-opened');
                article.changeContentPupop(keyLink);
                article.detectCoordinate(cur,parent);
                var allLinks = $('.content-inner-text a[href$="'+curAttr+'"]');
                $(parent).fadeOut(article.delay);
                $(parent).fadeIn(article.delay);
                $(allLinks).each(function( index ) {
                    $(this).attr('data-index', index+1);
                });

                $(parent).find('.arrows').fadeIn(0);
                e.preventDefault();
            });
        },
        detectCoordinate: function(cur,parent) {
            if($('.text-reference').length) {
                var alignCenter = (_window_height - $(parent).height())/2;
                if(_window_width > _mobile){
                    $('html, body').animate({ scrollTop: cur.offset().top - alignCenter }, article.delay+200);
                } else {
                    $('html, body').animate({ scrollTop: 0 }, article.delay+200);
                }
            }
        },
        detectNext: function(btnNext) {
            var link = $('.opened-reflink'),
                curNext = link.next();
            if(curNext.length > 0) {
                $(btnNext).css('opacity',1);
            } else {
                $(btnNext).css('opacity',0.4);
            }
        },
        detectPrev: function(btnPrev) {
            var link = $('.opened-reflink'),
                curPrev = link.prev();
            if(curPrev.length > 0) {
                $(btnPrev).css('opacity',1);
            } else {
                $(btnPrev).css('opacity',0.4);
            }
        },
        arrowsSwitchNext: function(btnNext,btnPrev) {
            $(btnNext).click(function(e) {
                var cur = $('.text-reference-opened'),
                    curAttrIndex = cur.data('index'),
                    curAttr = cur.attr('href'),
                    nextAttrIndex = curAttrIndex+1,
                    nextCur = $('.text-reference[href$="'+curAttr+'"][data-index='+nextAttrIndex+']');
                nextCur.trigger('click');
            });
        },
        arrowsSwitchPrev: function(btnPrev,btnNext) {
            $(btnPrev).click(function(e) {
                var cur = $('.text-reference-opened'),
                    curAttrIndex = cur.data('index'),
                    curAttr = cur.attr('href'),
                    nextAttrIndex = curAttrIndex-1,
                    nextCur = $('.text-reference[href$="'+curAttr+'"][data-index='+nextAttrIndex+']');
                nextCur.trigger('click');
            });
        },
        openPrintWindow: function(btn) {
            $(btn).click(function(e) {
                window.print();
                e.preventDefault();
            });
        },
        addToFavourite: function(btn) {
            $(btn).click(function(e) {
                var cur = $(this);
                cur.addClass('added');
                setTimeout(function(){
                    cur.removeClass('added');
                }, 5000);
                e.preventDefault();
            });
        }
    };
    /* end */

    //EVENTS
    $(document).ready(function() {
        article.closeReference('.icon-close-popup ','.reference-popup');
        article.openReference('.key-references-list a','.reference-popup');
        article.openReferenceTextLink('.text-reference','.reference-popup');
        article.openTooltip('.rel-tooltip','.reference-popup');
        article.arrowsSwitchNext('.reference-popup .right','.reference-popup .left');
        article.arrowsSwitchPrev('.reference-popup .left','.reference-popup .right');
    });

    $(window).load(function() {
        article.openPrintWindow('.btn-print');
        article.addToFavourite('.btn-like');
    });

})(jQuery);