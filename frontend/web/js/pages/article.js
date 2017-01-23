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
        _mobile = 820,
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
            var overlayEl = $(overlay);

            overlayEl.removeClass('js-tab-hidden').addClass('active');
            overlayEl.css('height', '1px');
            overlayEl.css('height', $(document).height());
            overlayEl.css('max-height', $(document).height());
        },
        closeOverlay: function(overlay,triggerEl) {
            var overlayEl = $(overlay);

            overlayEl.click(function(e) {
                overlayEl.addClass('js-tab-hidden').removeClass('active');
                $(triggerEl).trigger('click');
            });
        },
        heightEq: function(item, firstColumn) {

            $(item).each(function(index) {
                var cur = $(this),
                    curHeight = cur.height(),
                    curHeightIndex = cur.index(),
                    curSiblings = $('.column-types .types').find('.item').eq(index),
                    curSiblingsHeight = curSiblings.height(),
                    countHeight = 0;

                if(curHeight > curSiblingsHeight) {
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
                $('a,li').removeClass('opened-reflink');
                $('a').removeClass('text-reference-opened');
                $(this).parents(parent).fadeOut(article.delay+200);
                $('.icon-circle-arrow').removeClass('disabled');
                $(parent).find('.arrows').fadeOut(article.delay+200);
                $(overlay).addClass('js-tab-hidden').removeClass('active');
                e.preventDefault();
            });
        },
        changeContentPopup: function(cur){

            var curEl = cur,
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

            var visibleEl = $('.container-inner > .visible');
                visibleEl.removeClass('last-visible');
                visibleEl.last().addClass('last-visible');

            console.log(_window_width,_tablet);

            if(_window_width >= _mobile-100) {
                setTimeout(function(){
                    article.heightEq('.column-sources .item');

                    elements.window.resize(function() {
                        article.heightEq('.column-sources .item');
                    });
                }, article.delay+201);
            }
        },
        openTooltip: function(btn,parent) {
            $(btn).click(function(e) {
                var cur = $(this),
                    parentEl = $(parent),
                    classEl = 'opened-reflink',
                    arrows = '.arrows';

                $('li').removeClass(classEl);
                cur.parent('li').addClass(classEl);
                parentEl.find(arrows).fadeOut(0);

                article.changeContentPopup(cur);
                parentEl.fadeIn(article.delay+200);

                if(_window_width < _tablet){
                    article.showPopupMobile(parent);
                }
            });
        },
        openReference: function(btn,parent) {
            $(btn).click(function(e) {
                var
                    cur = $(this),
                    curParent = cur.parent(),
                    parentHolder = $(parent),
                    curAttr = cur.attr('href'),
                    keyLink = $('.content-inner-text a[href$="'+curAttr+'"]').first(),
                    classEl = 'opened-reflink';

                //action
                $('li').removeClass(classEl);
                curParent.addClass(classEl);
                // parentHolder.fadeOut(article.delay);
                //
                // if(_window_width > _mobile){
                //     parentHolder.fadeIn(article.delay);
                // }

                e.preventDefault();
            });
        },
        openReferenceTextLink: function(btn,parent,btnPrev,btnNext){
            $(btn).click(function(e) {
                var
                    cur = $(this),
                    curAttr = cur.attr('href'),
                    keyLink = $('.sidebar-widget-articles-references a[href$="'+curAttr+'"]'),
                    refLink = $('.text-reference'),
                    parentHolder = $(parent),
                    textReferenceOpened = 'text-reference-opened',
                    openedClass = 'opened-reflink',
                    btnPrevEl = $(btnPrev),
                    btnNextEl = $(btnNext);

                refLink.removeClass(textReferenceOpened);
                cur.addClass(textReferenceOpened);

                $('li').removeClass(openedClass);

                article.changeContentPopup(keyLink);
                article.detectCoordinate(cur,parent);
                article.showPopupMobile(parent);

                keyLink.parent().addClass(openedClass);
                var allLinks = $('.content-inner-text a[href$="'+curAttr+'"]');
                parentHolder.fadeOut(article.delay);

                $(allLinks).each(function( index ) {
                    $(this).attr('data-index', index+1);
                });

                if(_window_width > _mobile) {
                    parentHolder.fadeIn(article.delay);
                }

                var
                    curAttrIndex = cur.data('index'),
                    nextAttrIndex = curAttrIndex+1,
                    nextCur = $('.text-reference[href$="'+curAttr+'"][data-index='+nextAttrIndex+']');

                var
                    prevAttrIndex = curAttrIndex-1,
                    prevCur = $('.text-reference[href$="'+curAttr+'"][data-index='+prevAttrIndex+']');

                btnPrevEl.removeClass('disabled');
                btnNextEl.removeClass('disabled');

                if(prevCur.length == 0) {
                    btnPrevEl.addClass('disabled');
                }

                if(nextCur.length == 0) {
                    btnNextEl.addClass('disabled');
                }

                parentHolder.find('.arrows').fadeIn(0);

                e.preventDefault();
            });
        },
        showPopupMobile: function(parent) {

            if(_window_width < _tablet){
                var parentHolder = $(parent);

                setTimeout(function(){
                    parentHolder.css('top',  elements.window.scrollTop() - 2);
                    parentHolder.fadeIn(0);
                }, article.delay+402);

                elements.window.bind('scrollstop', function(e){
                    if (parentHolder.is(":visible") == true){
                        parentHolder.css('top',  elements.window.scrollTop() - 2);
                    }
                });
            }
        },
        detectCoordinate: function(cur,parent) {
            if($('.text-reference').length) {
                var alignCenter = (_window_height - $(parent).height())/2,
                    CurCord = cur.offset().top,
                    htmlEl = $('html, body');

                if(_window_width > _mobile){
                    htmlEl.animate({ scrollTop: CurCord - alignCenter }, article.delay+200);
                } else {
                    htmlEl.animate({ scrollTop: CurCord - _window_height+20 }, article.delay+400);
                }
            }
        },
        arrowsSwitchNext: function(btnNext,btnPrev) {
            $(btnNext).click(function(e) {
                var cur = $('.text-reference-opened'),
                    curAttrIndex = cur.data('index'),
                    curAttr = cur.attr('href'),
                    nextAttrIndex = curAttrIndex+1,
                    nextCur = $('.text-reference[href$="'+curAttr+'"][data-index='+nextAttrIndex+']');

                if(nextCur.length == 0) {
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
                    prevCur = $('.text-reference[href$="'+curAttr+'"][data-index='+nextAttrIndex+']');

                if(prevCur.length == 0) {
                    $(btnPrev).addClass('disabled');
                } else {
                    $(btnNext).removeClass('disabled');
                }

                prevCur.trigger('click');
            });
        },
        addToFavourite: function(btn) {
            $(btn).click(function(e) {
                var cur = $(this);
                $.get(cur.prop('href'), function(data, status) {
                    cur.children('.btn-like-inner').html(data.message);
                    cur.addClass('added');
                    setTimeout(function() {
                        cur.removeClass('added');
                    }, 3000);
                });

                e.preventDefault();
            });
        },
        articleReference: function(parent,tag) {
            if(_window_width < _tablet) {
                $(parent).find(tag).each(function( index ) {
                    $(this).attr('data-li-index',index+1);
                });
            }
        },
        openReferencePopup: function(btn) {
            $(btn).click(function(e) {
                $(this).parent().find('.rel-tooltip').trigger('click');
                e.preventDefault();
            });
        },
        openReferenceListInPopup: function(btn,parent) {
            $(btn).click(function(e) {
                var cur = $(this),
                    curAttrIndex = cur.data('index'),
                    curAttr = cur.attr('href');

                $(parent).find('a[href$="'+curAttr+'"]').trigger('click');
                e.preventDefault();
            });
        },
        openListInpopup: function(btn,parent) {
            $(btn).click(function(e) {
                var cur = $(this),
                    curIndex = cur.parent().index();

                $(parent).find('a[href$="'+curAttr+'"]').trigger('click');
                e.preventDefault();
            });
        },
        citeInit: function() {

            if (typeof citeConfig === 'undefined'){
                return false;
            }

            function getCiteValue() {

                return citeConfig.authors+' '+citeConfig.title+' '+citeConfig.publisher+
                    ' '+citeConfig.date+': '+citeConfig.id+' doi:'+citeConfig.doi;
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
            var popupHolder = $(popup);
            $(btn).on('click', function(e) {
                popupHolder.fadeIn();
                popupHolder.find('div').removeClass('visible');

                article.overlayOpen('.overlay');

                $('.cite-input-box-holder').addClass('visible');

                if(_window_width < _tablet){
                    article.showPopupMobile(popup);
                }

                e.preventDefault();
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
        article.openReferencePopup('.sidebar-widget-articles-references ul li li>a');
        article.openReference('.key-references-list a','.reference-popup');
        article.openReference('.bg-news-list a','.reference-popup');
        article.openReferenceTextLink('.text-reference[data-type="bible"]','.reference-popup', '.reference-popup .left','.reference-popup .right');
        article.openReferenceTextLink('.text-reference[data-type="term"]','.reference-popup', '.reference-popup .left','.reference-popup .right');
        article.openTooltip('.rel-tooltip','.reference-popup');
        article.arrowsSwitchNext('.reference-popup .right','.reference-popup .left');
        article.arrowsSwitchPrev('.reference-popup .left','.reference-popup .right');
        article.articleReference('.sidebar-widget-articles-references','li:not(.sidebar-articles-item) ul>li');
        article.openReferenceListInPopup('.key-reference-in-popup a','.key-references-list');
        article.closeOverlay('.overlay','.icon-close-popup');
        article.citeInit();
        article.closeOpen('.btn-cite','.reference-popup');
    });

    elements.window.load(function() {
        shareSelected('.article-full article');
        article.mapInit();
        article.addToFavourite('.btn-like');
    });

})(jQuery);