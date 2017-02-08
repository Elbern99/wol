(function($) {

//ELEMENTS
    var elements = {
        window: $(window),
        document: $(document),
        mapMini: 'map-mini',
        mapMedium: 'article-map-medium'
    };

//GLOBAL VARIABLE ---------
    var _window_height = elements.window.height(),
        _window_width = elements.window.width(),
        _doc_height = elements.document.height(),
        _click_touch = ('ontouchstart' in window) ? 'touchstart' : ((window.DocumentTouch && document instanceof DocumentTouch) ? 'tap' : 'click'),
        _mobile = 769,
        _tablet = 1025;

    elements.window.resize(function() {
        _window_height = elements.window.height();
        _window_width = elements.window.width();
        _doc_height = elements.document.height();
    });

    //ARTICLE
    var article = {
        delay: 0,
        overlayOpen: function(overlay) {
            var
                overlayEl = $(overlay);

            overlayEl.removeClass('js-tab-hidden').addClass('active');
        },
        closeOverlay: function(overlay,triggerEl) {
            var
                overlayEl = $(overlay);

            overlayEl.click(function(e) {
                overlayEl.addClass('js-tab-hidden').removeClass('active');
                $(triggerEl).trigger('click');
            });
        },
        heightEq: function(item, firstColumn) {

            $(item).each(function(index) {
                var
                    cur = $(this),
                    curHeight = cur.height(),
                    curHeightIndex = cur.index(),
                    curSiblings = $('.column-types .types').find('.item').eq(index),
                    curSiblingsHeight = curSiblings.height(),
                    countHeight = 0,
                    equalHeight = curHeight > curSiblingsHeight;

                    if(equalHeight) {
                        countHeight = curHeight;
                    } else {
                        countHeight = curSiblingsHeight;
                    }

                cur.css('min-height', '1px');
                curSiblings.css('min-height', '1px');

                cur.css('min-height', countHeight);
                curSiblings.css('min-height', countHeight);
            });
        },
        closeReference: function(btn,parent,overlay) {
            $(btn).click(function(e) {

                var
                    cur = $(this),
                    parentEl = $(parent),
                    allListItem = $('a,li'),
                    allLInks =  $('a'),
                    overlayEl = $(overlay),
                    arrowItem = $('.icon-circle-arrow');

                    allListItem.removeClass('opened-reflink');
                    allLInks.removeClass('text-reference-opened');
                    cur.parents(parent).fadeOut(article.delay+200);
                    arrowItem.removeClass('disabled');
                    parentEl.find('.arrows').fadeOut(article.delay+200);
                    overlayEl.addClass('js-tab-hidden').removeClass('active');

                    setTimeout(function() {
                        parentEl.removeClass('def-reference-popup tooltip-ref-popup');
                    }, 300);
                e.preventDefault();
            });
        },
        changeContentPopup: function(cur){

            var
                curEl = cur,
                curElParent = curEl.parent(),
                curCaption = curElParent.find('.caption').html(),
                curSources = curElParent.find('.sources').html(),
                curTypes = curElParent.find('.types').html(),
                curMethods = curElParent.find('.methods').html(),
                curCountries = curElParent.find('.countries').html(),
                curFurnitureReading = curElParent.find('.further-reading-info').html(),
                curBgInfo = curElParent.find('.bg-info').html(),
                curAdditional = curElParent.find('.additional-references-info').html(),
                popup = $('.reference-popup');

            var
                caption = popup.find('.caption'),
                sources = popup.find('.sources'),
                types = popup.find('.types'),
                methods = popup.find('.methods'),
                countries = popup.find('.countries'),
                furnitureReading = popup.find('.furniture-reading'),
                additional = popup.find('.additional-references'),
                bgInfo = popup.find('.bg-info'),
                cite = popup.find('.cite-input-box');

            furnitureReading.parent().removeClass('visible');
            caption.parent().removeClass('visible');
            sources.parent().removeClass('visible');
            types.parent().removeClass('visible');
            methods.parent().removeClass('visible');
            countries.parent().removeClass('visible');
            additional.parent().removeClass('visible');
            bgInfo.parent().removeClass('visible');
            cite.parent().removeClass('visible');

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
                if(curSources.trim().length > 0 && curSources !== "") {
                    sources.html(curSources);
                    sources.parent().addClass('visible');
                }
            }
            if(typeof curTypes !== "undefined") {
                if(curTypes.trim().length > 0) {
                    types.html(curTypes);
                    types.parent().addClass('visible');
                }
            }
            if(typeof curMethods !== "undefined") {
                if(curMethods.trim().length > 0) {
                    methods.html(curMethods);
                    methods.parent().addClass('visible');
                }
            }
            if(typeof curCountries !== "undefined") {
                if(curCountries.trim().length > 0) {
                    countries.html(curCountries);
                    countries.parent().addClass('visible');
                }
            }
            if(typeof curBgInfo !== "undefined") {
                if(curBgInfo.trim().length > 0) {
                    bgInfo.html(curBgInfo);
                    bgInfo.parent().addClass('visible');
                }
            }
            if(typeof curAdditional !== "undefined") {
                if(curAdditional.trim().length > 0) {
                    additional.html(curAdditional);
                    additional.parent().addClass('visible');
                }
            }

            article.overlayOpen('.overlay');

            var
                visibleEl = $('.container-inner > .visible'),
                getWindowLessMobile = _window_width >= _mobile-100;

            visibleEl.removeClass('last-visible');
            visibleEl.last().addClass('last-visible');

            if(getWindowLessMobile) {
                setTimeout(function(){
                    article.heightEq('.column-sources .item');

                    elements.window.resize(function() {
                        article.heightEq('.column-sources .item');
                    });
                }, 0);
            }
        },
        openTooltip: function(btn,parent) {
            $(btn).click(function(e) {
                var
                    cur = $(this),
                    parentEl = $(parent),
                    classEl = 'opened-reflink',
                    arrows = '.arrows',
                    htmlEl = $('html, body');

                parentEl.removeClass('def-reference-popup');
                parentEl.addClass('tooltip-ref-popup');

                function setCoordinatePopup(cur) {
                    var offset = cur.offset(),
                        curCordTop = offset.top,
                        curCordLeft = offset.left;

                    parentEl.css('top',curCordTop + 18);
                    parentEl.css('left', curCordLeft);

                    var
                        getCenter = curCordTop - _window_height/2,
                        checkDesktop = _window_width > _mobile,
                        checkTooltip = $('.tooltip-ref-popup').length > 0,
                        checkElAfterCenter = getCenter > $(document).scrollTop();

                    if(checkTooltip) {
                        if(checkDesktop) {
                            if(checkElAfterCenter) {
                                htmlEl.animate({ scrollTop: getCenter }, article.delay+200);
                            }
                        } else {
                            htmlEl.animate({ scrollTop: curCordTop }, article.delay+200);
                        }
                    }
                };

                $('li').removeClass(classEl);
                cur.parent('li').addClass(classEl);
                parentEl.find(arrows).fadeOut(0);

                setCoordinatePopup(cur);

                elements.window.resize(function() {
                    setCoordinatePopup(cur);
                });

                article.changeContentPopup(cur);
                parentEl.fadeIn(article.delay+200);
            });
        },
        setIndex: function(parentHolder,curAttr) {
            var allLinks = $('.content-inner-text a[href$="'+curAttr+'"]');
                parentHolder.fadeOut(article.delay);

            $(allLinks).each(function( index ) {
                $(this).attr('data-index', index+1);
            });
        },
        openReferenceTextLink: function(btn,parent,btnPrev,btnNext){
            elements.document.on('click', btn, function(e) {
                var
                    cur = $(this),
                    curAttr = cur.attr('href'),
                    keyLink = $('.sidebar-widget-articles-references a[href$="'+curAttr+'"]'),
                    refLink = $('.text-reference'),
                    parentEl = $(parent),
                    textReferenceOpened = 'text-reference-opened',
                    openedClass = 'opened-reflink',
                    checkReferenceOpened = $('.'+textReferenceOpened).length;

                if(!checkReferenceOpened) {
                    article.changeContentPopup(keyLink);
                }

                refLink.removeClass(textReferenceOpened);
                cur.addClass(textReferenceOpened);

                $('li').removeClass(openedClass);

                keyLink.parent().addClass(openedClass);

                article.setIndex(parentEl,curAttr);
                article.detectNextPrevArrows(cur,curAttr,parentEl,btnPrev,btnNext);

                parentEl.removeClass('tooltip-ref-popup');
                parentEl.addClass('def-reference-popup');

                article.showPopupMobile('.def-reference-popup');

                article.detectCoordinate(cur,parentEl);

                elements.window.on('orientationchange', function() {
                    setTimeout(function(){
                        article.detectCoordinate(cur,parentEl);
                    }, 600);
                });

                e.preventDefault();
            });
        },
        detectNextPrevArrows: function(cur,curAttr,parentHolder,btnPrev,btnNext) {

            var
                btnPrevEl = $(btnPrev),
                btnNextEl = $(btnNext),
                checkDesktop = _window_width > _mobile;

            if(checkDesktop) {
                parentHolder.fadeIn(article.delay);
            }

            var
                curAttrIndex = cur.data('index'),
                nextAttrIndex = curAttrIndex+1,
                nextCur = $('.text-reference[href$="'+curAttr+'"][data-index='+nextAttrIndex+']').attr('href'),
                prevAttrIndex = curAttrIndex-1,
                prevCur = $('.text-reference[href$="'+curAttr+'"][data-index='+prevAttrIndex+']').attr('href');

            btnPrevEl.removeClass('disabled');
            btnNextEl.removeClass('disabled');

            var
                checkPrev = prevCur == undefined,
                checkNext = nextCur == undefined;

            if(checkPrev) {
                btnPrevEl.addClass('disabled');
            }

            if(checkNext) {
                $('.icon-circle-arrow.right').addClass('disabled');
            }

            parentHolder.find('.arrows').fadeIn(0);
        },
        showPopupMobile: function(parent) {
            var checkWindow = _window_width < _tablet;

            if(checkWindow){
                var
                    parentHolder = $(parent);

                setTimeout(function(){
                    parentHolder.css('top',  elements.window.scrollTop() - 2);
                    parentHolder.fadeIn(0);
                }, article.delay+402);

                elements.window.bind('scrollstop', function(e){
                    if (parentHolder.is(":visible") == true && parentHolder.hasClass('def-reference-popup')){
                        parentHolder.css('top',  elements.window.scrollTop() - 2);
                    }
                });
            }
        },
        detectCoordinate: function(cur,parent) {
            if($('.text-reference').length) {
                var alignCenter = (_window_height - $(parent).height())/2,
                    CurCord = cur.offset().top,
                    htmlEl = $('html, body'),
                    checkWindow = _window_width > _mobile,
                    checkPopup = $('.def-reference-popup').length > 0;

                if(checkPopup) {
                    if(checkWindow){
                        htmlEl.animate({ scrollTop: CurCord - alignCenter }, article.delay+200);
                    } else {
                        htmlEl.animate({ scrollTop: CurCord - _window_height+20 }, article.delay+400);
                    }
                }
            }
        },
        arrowsSwitchNext: function(btnNext,btnPrev) {
            $(btnNext).click(function(e) {
                var cur = $('.text-reference-opened'),
                    curAttrIndex = cur.data('index'),
                    curAttr = cur.attr('href'),
                    nextAttrIndex = curAttrIndex+1,
                    nextCur = $('.text-reference[href$="'+curAttr+'"][data-index='+nextAttrIndex+']'),
                    checkNext = nextCur.length == 0;

                if(checkNext) {
                    $(btnNext).addClass('disabled');

                } else {
                    $(btnPrev).removeClass('disabled');
                }
                nextCur.trigger('click');
            });
        },
        arrowsSwitchPrev: function(btnPrev,btnNext) {
            $(btnPrev).click(function(e) {
                var cur = $('.text-reference-opened'),
                    curAttrIndex = cur.data('index'),
                    curAttr = cur.attr('href'),
                    nextAttrIndex = curAttrIndex-1,
                    prevCur = $('.text-reference[href$="'+curAttr+'"][data-index='+nextAttrIndex+']'),
                    checkPrev = prevCur.length == 0;

                if(checkPrev) {
                    $(btnPrev).addClass('disabled');
                } else {
                    $(btnNext).removeClass('disabled');
                }

                prevCur.trigger('click');
            });
        },
        addToFavourite: function(btn) {
            $(btn).click(function(e) {
                var
                    cur = $(this),
                    curParent = cur.parent();

                $.get(cur.prop('href'), function(data, status) {
                    curParent.find('.add-fav-alert').html(data.message);
                    curParent.addClass('added');
                    setTimeout(function() {
                        curParent.removeClass('added');
                    }, 3000);
                });

                e.preventDefault();
            });
        },
        articleReference: function(parent,tag) {
            var
                checkWindow = _window_width < _tablet,
                cur = $(this);

            if(checkWindow) {
                $(parent).find(tag).each(function( index ) {
                    cur.attr('data-li-index',index+1);
                });
            }
        },
        openReferencePopup: function(curAttr,keyLink,parent) {
            $(parent).fadeIn(article.delay+200);
        },
        openReference: function(btn,parent) {
            $(btn).click(function(e) {
                var
                    cur = $(this),
                    curParent = cur.parent(),
                    parentEl = $(parent),
                    curAttr = cur.attr('href'),
                    keyLink = $('.content-inner-text a[href$="'+curAttr+'"]').first(),
                    classEl = 'opened-reflink',
                    textReferenceOpened = 'text-reference-opened';

                //action
                $('li').removeClass(classEl);
                curParent.addClass(classEl);

                keyLink.addClass(textReferenceOpened);

                article.setIndex(parentEl,curAttr);

                article.openReferencePopup(curAttr,keyLink,parent);
                article.changeContentPopup(cur);
                article.detectNextPrevArrows(keyLink,curAttr,parentEl,'.icon-circle-arrow.left','icon-circle-arrow.right');

                parentEl.removeClass('tooltip-ref-popup');
                parentEl.addClass('def-reference-popup');

                var
                    checkAttr = curAttr.length> 0,
                    checkWindow = _window_width < _tablet;

                if(checkAttr) {
                    article.detectCoordinate(keyLink,parentEl);

                    elements.window.on('orientationchange', function() {
                        setTimeout(function(){
                            article.detectCoordinate(keyLink,parentEl);
                        }, 600);
                    });

                    if(checkWindow){
                        article.showPopupMobile('.def-reference-popup');
                    }
                }

                e.preventDefault();
            });
        },
        openReferenceListInPopup: function(btn,parent) {
            $(btn).click(function(e) {
                var
                    cur = $(this),
                    curAttrIndex = cur.data('index'),
                    curAttr = cur.attr('href');

                $(parent).find('a[href$="'+curAttr+'"]').trigger('click');
                e.preventDefault();
            });
        },
        citeInit: function() {

            if (typeof citeConfig === 'undefined'){
                return false;
            }
            
            function getAuthorFromat() {
                
                var authors = [];
                
                for(var author in citeConfig.authors) {

                    var attribute = citeConfig.authors[author].name;
                    var name = attribute.last_name;
                    
                    if (attribute.first_name) {
                        name += ', '+attribute.first_name.substring(1, 0).toUpperCase()+'.';
                    }
                    
                    authors.push(name);
                }
                
                return authors.join(', ');
            }

            function getCiteValue() {

                var cite = '';
                
                cite += getAuthorFromat();
                
                cite += ' ';
                cite += citeConfig.title;
                cite += '. ';
                cite += citeConfig.publisher;
                
                if (citeConfig.date) {
                    cite += ' '+citeConfig.date+': ';
                } else {
                    cite += ' ';
                }
                
                cite += citeConfig.id;
                
                if (citeConfig.doi) {
                    cite += ' doi: '+citeConfig.doi;
                }
                
                return cite;
            }

            var value = getCiteValue();

            $('.cite-input-box').each(function( index ) {
                $(this).children('textarea').val(value);
            });

            $('.download-cite-button').on('click', function() {
                var querystring = $.param(citeConfig);
                window.location.assign(citeConfig.postUrl+'?'+querystring);
            });

            $('.copy-cite-button').on('click', function() {

                var cur = $(this);

                function copySelectionText(){
                    var copysuccess;
                    try{
                        copysuccess = document.execCommand("copy");
                    } catch(e){
                        copysuccess = false
                    }
                    return copysuccess
                }

                function copyfieldvalue(id){
                    var field = document.getElementById(id);
                    field.focus();
                    field.setSelectionRange(0, field.value.length);
                    var copysuccess = copySelectionText();
                    if (copysuccess){
                        cur.text('Copied');
                    }
                }

                setTimeout(function(){
                    cur.text('Copy');
                }, 2000);

                copyfieldvalue('copy-field');
            });
        },
        closeOpen: function(btn,popup) {
            var
                popupHolder = $(popup);

            $(btn).on('click', function(e) {
                popupHolder.fadeIn();
                popupHolder.find('div').removeClass('visible');
                popupHolder.addClass('def-reference-popup');

                article.overlayOpen('.overlay');

                $('.cite-input-box-holder').addClass('visible');

                var
                    checkWindow = _window_width < _tablet;

                if(checkWindow){
                    article.showPopupMobile('.def-reference-popup');
                }

                e.preventDefault();
            });
        },
        changeFigureClass: function(item) {
            $(item).each(function() {
                var cur = $(this),
                    curType = cur.data('type'),
                    checkType = curType === 'figure';

                if(checkType) {
                    cur.attr('class','scroll-to-img');
                }
            });
        },
        mapInit: function() {
            var countries_arrays = mapConfig.source;
            var countries_array = {};

            for (var prop in countries_arrays) {
                countries_array[countries_arrays[prop]] = {};
            }

            var mapObj = {
                options: {
                    inertia: false,
                    zoom: 0,
                    clickable: false,
                    boxZoom: false,
                    tap: false,
                    trackResize: true,
                    center: [0, 0],
                    attributionControl: false,
                    zoomControl: false,
                    dragging: false,
                    scrollWheelZoom: false
                },
                style: function(feature) {
                    return {
                        stroke: false,
                        fill: false,
                        className: mapObj.getColor(feature.properties.economy)
                    };
                },
                getColor: function(d) {
                    if(d !== undefined) return d;
                },
                onEachFeature: function(feature, layer) {
                    layer.on({});
                }
            };

            var map = L.map(elements.mapMini, mapObj.options),
                geojson;

            if($('#'+ elements.mapMedium).length) {
                var mapSecond = L.map(elements.mapMedium, mapObj.options),
                    geojson;
            }

            //----------1 get
            $.getJSON(mapConfig.json_path_country, function( data ) {
                $.each(data[1], function(index, country) {
                    $.each(countries_array, function(index, value) {
                        if(index === country.iso2Code) {
                            countries_array[index].id = country.id;
                        }
                    });
                });

                //------------2 get
                $.getJSON(mapConfig.json_path_economytypes, function( data ) {
                    $.each(data, function(index, value) {

                        $.each(countries_array, function(index_countries, value_countries) {

                            if(index === index_countries) {
                                countries_array[index].economy = value;
                            }
                        });
                    });

                    //------------3 get
                    $.getJSON(mapConfig.json_path, function( data ) {

                        $.each(data.features, function(index, country) {
                            var country_id = country.id;

                            $.each(countries_array, function(index_countries, value_countries) {
                                if(value_countries.id === country_id) {
                                    country.properties.economy = value_countries.economy;
                                }
                            });
                        });

                        geojson = L.geoJson(data, {
                            style: mapObj.style,
                            onEachFeature: mapObj.onEachFeature
                        }).addTo(map);

                        if(mapSecond){
                            geojson = L.geoJson(data, {
                                style: mapObj.style,
                                onEachFeature: mapObj.onEachFeature
                            }).addTo(mapSecond);
                        }
                    })
                });
            });
        }
    };
    /* end */

    function shareSelected(selector){
        shareSelectedText(selector, {
            tooltipClass: '',
            sanitize: true,
            buttons: [
                'twitter',
                'linkedin',
                'facebook',
                'tumblr',
            ],
            anchorsClass: '',
            twitterUsername: '',
            facebookAppID: '1273981299361667',
            facebookDisplayMode: 'popup',
            tooltipTimeout: 50
        });
    }

    //EVENTS
    elements.document.ready(function() {
        article.closeReference('.icon-close-popup ','.reference-popup','.overlay');
        article.openReferencePopup('.sidebar-widget-articles-references ul li li>a', '.reference-popup');
        article.openReferenceTextLink('.text-reference','.reference-popup', '.reference-popup .left','.reference-popup .right');
        article.openTooltip('.rel-tooltip','.reference-popup');
        article.arrowsSwitchNext('.reference-popup .right','.reference-popup .left');
        article.arrowsSwitchPrev('.reference-popup .left','.reference-popup .right');
        article.articleReference('.sidebar-widget-articles-references','li:not(.sidebar-articles-item) ul>li');
        article.openReferenceListInPopup('.key-reference-in-popup a','.key-references-list');
        article.closeOverlay('.overlay','.icon-close-popup');
        article.citeInit();
        article.closeOpen('.btn-cite','.reference-popup');
        article.addToFavourite('.btn-like');
        article.openReference('.key-references-list a','.reference-popup');
        article.openReference('.additional-references-list a','.reference-popup');
        article.openReference('.further-reading-list a','.reference-popup');
        article.openReference('.bg-news-list a','.reference-popup');
    });

    elements.window.load(function() {
        shareSelected('.article-full article');
        article.mapInit();
        article.changeFigureClass('.text-reference[data-type="figure"]');
    });

})(jQuery);