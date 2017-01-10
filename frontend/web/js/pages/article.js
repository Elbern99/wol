(function($) {

//ELEMENTS
    var elements = {
        window: $(window),
        document: $(document),
        mapMini: 'map-mini',
        mapMedium: 'article-map-medium'
    }

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
            $(overlay).removeClass('js-tab-hidden').addClass('active');
            $(overlay).css('height', '1px');
            $(overlay).css('height', $(document).height());
            $(overlay).css('max-height', $(document).height());
        },
        closeOverlay: function(overlay,triggerEl) {
            $(overlay).click(function(e) {
                $(overlay).addClass('js-tab-hidden').removeClass('active');
                $(triggerEl).trigger('click');
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

                $('li').removeClass('opened-reflink');
                cur.parent('li').addClass('opened-reflink');
                $(parent).find('.arrows').fadeOut(0);

                article.changeContentPupop(cur);
                $(parent).fadeIn(article.delay+200);

                if(_window_width < _tablet){
                    article.showPopupMobile(parent);
                }
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


                if(_window_width > _mobile){
                    $(parent).fadeIn(article.delay);
                }

                if(keyLink.length>0){
                    keyLink.trigger('click');
                } else {
                    cur.parent().find('.rel-tooltip').trigger('click');
                }

                e.preventDefault();
            });
        },
        openReferenceTextLink: function(btn,parent,btnPrev,btnNext){
            $(btn).click(function(e) {
                var
                    cur = $(this),
                    curAttr = cur.attr('href'),
                    keyLink = $('.sidebar-widget-articles-references a[href$="'+curAttr+'"]');
                $('.text-reference').removeClass('text-reference-opened');
                cur.addClass('text-reference-opened');

                $('li').removeClass('opened-reflink');
                article.changeContentPupop(keyLink);
                article.detectCoordinate(cur,parent);
                article.showPopupMobile(parent);
                keyLink.parent().addClass('opened-reflink');
                var allLinks = $('.content-inner-text a[href$="'+curAttr+'"]');
                $(parent).fadeOut(article.delay);

                $(allLinks).each(function( index ) {
                    $(this).attr('data-index', index+1);
                });

                if(_window_width > _mobile) {
                    $(parent).fadeIn(article.delay);
                }

                var
                    curAttrIndex = cur.data('index'),
                    nextAttrIndex = curAttrIndex+1,
                    nextCur = $('.text-reference[href$="'+curAttr+'"][data-index='+nextAttrIndex+']');

                var
                    prevAttrIndex = curAttrIndex-1,
                    prevCur = $('.text-reference[href$="'+curAttr+'"][data-index='+prevAttrIndex+']');

                $(btnPrev).removeClass('disabled');
                $(btnNext).removeClass('disabled');

                if(prevCur.length == 0) {
                    $(btnPrev).addClass('disabled');
                }

                if(nextCur.length == 0) {
                    $(btnNext).addClass('disabled');
                }

                $(parent).find('.arrows').fadeIn(0);

                e.preventDefault();
            });
        },
        showPopupMobile: function(parent) {
            if(_window_width < _tablet){
                article.overlayOpen('.overlay');

                setTimeout(function(){
                    $(parent).css('top',  elements.window.scrollTop() - 2);
                    $(parent).fadeIn(0);
                }, article.delay+402);

                elements.window.bind('scrollstop', function(e){
                    if ($('.opened-reflink').length == 1 || $('.text-reference-opened').length == 1){
                        //$(parent).fadeIn(article.delay+200);
                        $(parent).css('top',  elements.window.scrollTop() - 2);
                    }
                });
            }
        },
        detectCoordinate: function(cur,parent) {
            if($('.text-reference').length) {
                var alignCenter = (_window_height - $(parent).height())/2;
                var CurCord = cur.offset().top;

                if(_window_width > _mobile){
                    $('html, body').animate({ scrollTop: CurCord - alignCenter }, article.delay+200);
                } else {
                    $('html, body').animate({ scrollTop: CurCord - _window_height+20 }, article.delay+400);
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
																				//$(btnNext).fadeIn(0);
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
                    //$(btnPrev).fadeIn(0);
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
            
            function getCiteValue() {
                
                return citeConfig.authors+' '+citeConfig.title+' '+citeConfig.publisher+
                        ' '+citeConfig.date+': '+citeConfig.id+' doi:'+citeConfig.doi;
            }
            
            let value = getCiteValue();
            
            $('.cite-input-box').each(function( index ) {
                $(this).children('textarea').val(value);
            });
            
            $('.download-cite-button').on('click', function() {
                $.ajax({
                    url : citeConfig.postUrl,
                    type: 'POST',
                    dataType: 'json',
                    data : citeConfig,
               });
            });
        }
    };
    /* end */

    function shareSelected(selector){
        shareSelectedText(selector, {
            tooltipClass: '',    // cool, if you want to customize the tooltip
            sanitize: true,      // will sanitize the user selection to respect the Twitter Max length (recommended)
            buttons: [           // services that you want to enable you can add :
                'twitter',       // - twitter, tumblr, buffer, stumbleupon, digg, reddit, linkedin, facebook
                'linkedin',
                'facebook',
                'tumblr',
            ],
            anchorsClass: '',    // class given to each tooltip's links
            twitterUsername: '', // for twitter widget, will add 'via @twitterUsername' at the end of the tweet.
            facebookAppID: '1273981299361667', // Can also be an HTML element inside the <head> tag of your page : <meta property="fb:APP_ID" content="YOUR_APP_ID"/>
            facebookDisplayMode: 'popup', //can be 'popup' || 'page'
            tooltipTimeout: 50  //Timeout before that the tooltip appear in ms
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
    });

    elements.window.load(function() {

        shareSelected('.article-full article');

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
                return  d === 'factor-efficiency' ? 'factor-efficiency' :
                    d === 'factor' ? 'factor' :
                        d === 'efficiency-innovation' ? 'efficiency-innovation' :
                            d === 'efficiency' ? 'efficiency' :
                                d === 'innovation' ? 'innovation' :
                                d === 'none';
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

        article.addToFavourite('.btn-like');
    });

})(jQuery);