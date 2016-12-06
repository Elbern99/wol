(function (exports) {
    var getPageUrl = function getPageUrl() {
        if (document.querySelector('meta[property="og:url"]') && document.querySelector('meta[property="og:url"]').getAttribute('content')) {
            return document.querySelector('meta[property="og:url"]').getAttribute('content');
        }

        return window.location.href;
    };

    // constants
    var TOOLTIP_HEIGHT = 50;
    var FACTOR = 1.33;
    var TWITTER_LIMIT_LENGTH = 140;
    var TWITTER_URL_LENGTH_COUNT = 24;
    var TWITTER_QUOTES = 2;
    var TWITTER_DOTS = 3;
    var TOOLTIP_TIMEOUT = 250;
    var FACEBOOK_DISPLAY_MODES = {
        popup: 'popup',
        page: 'page'
    };

    var REAL_TWITTER_LIMIT = TWITTER_LIMIT_LENGTH - TWITTER_URL_LENGTH_COUNT - TWITTER_QUOTES - TWITTER_DOTS;

    var SOCIAL = {
        twitter: 'twitter',
        buffer: 'buffer',
        digg: 'digg',
        linkedin: 'linkedin',
        stumbleupon: 'stumbleupon',
        reddit: 'reddit',
        tumblr: 'tumblr',
        facebook: 'facebook',
        google: 'google'
    };

    var NO_START_WITH = /[ .,!?/\\\+\-=*£$€:~§%^µ)(|@"{}&#><_]/g;
    var NO_ENDS_WITH = /[ ,/\\\+\-=*£$€:~§%^µ)(|@"{}&#><_]/g;
    var PAGE_URL = getPageUrl();

    // globals
    var tooltip = undefined;
    var parameters = undefined;
    var selected = {};

    var extend = function extend(out) {
        out = out || {};

        for (var i = 1; i < arguments.length; i += 1) {
            if (arguments[i]) {
                for (var key in arguments[i]) {
                    if (arguments[i].hasOwnProperty(key)) {
                        out[key] = arguments[i][key];
                    }
                }
            }
        }
        return out;
    };

    var hideTooltip = function hideTooltip() {
        tooltip.classList.remove('active');
    };

    var showTooltip = function showTooltip() {
        tooltip.classList.add('active');
    };

    var smartSanitize = function smartSanitize(text) {
        while (text.length && text[0].match(NO_START_WITH)) {
            text = text.substring(1, text.length);
        }

        while (text.length && text[text.length - 1].match(NO_ENDS_WITH)) {
            text = text.substring(0, text.length - 1);
        }

        return text;
    };

    var sanitizeText = function sanitizeText(text) {
        var sociaType = arguments.length <= 1 || arguments[1] === undefined ? '' : arguments[1];

        var author = '';
        var tweetLimit = REAL_TWITTER_LIMIT;

        if (!text) {
            return '';
        }

        if (parameters.twitterUsername && sociaType === SOCIAL.twitter) {
            author = ' via @' + parameters.twitterUsername;
            tweetLimit = REAL_TWITTER_LIMIT - author.length;
        }

        if (text.length > REAL_TWITTER_LIMIT) {
            text = text.substring(0, tweetLimit);
            text = text.substring(0, text.lastIndexOf(' ')) + '...';
        } else {
            text = text.substring(0, tweetLimit + TWITTER_DOTS);
        }

        return smartSanitize(text);
    };

    var generateSocialUrl = function generateSocialUrl(socialType, text) {
        if (parameters.sanitize) {
            text = sanitizeText(text, socialType);
        } else {
            text = smartSanitize(text);
        }

        var twitterUrl = 'https://twitter.com/intent/tweet?url=' + PAGE_URL + '&text="' + text + '"';

        if (parameters.twitterUsername && parameters.twitterUsername.length) {
            twitterUrl += '&via=' + parameters.twitterUsername;
        }

        var facebookUrl = 'https://facebook.com/dialog/share?display=' + parameters.facebookDisplayMode + '&href=' + PAGE_URL;

        if (document.querySelector('meta[property="fb:app_id"]') && document.querySelector('meta[property="fb:app_id"]').getAttribute('content')) {
            var content = document.querySelector('meta[property="fb:app_id"]');
            facebookUrl += '&app_id=' + content;
        } else if (parameters.facebookAppID && parameters.facebookAppID.length) {
            facebookUrl += '&app_id=' + parameters.facebookAppID;
        } else {
            var idx = parameters.buttons.indexOf('facebook');
            if (idx > -1) {
                parameters.buttons.splice(idx, 1);
            }
        }

        var urls = {
            twitter: twitterUrl,
            buffer: 'https://buffer.com/add?text="' + text + '"&url=' + PAGE_URL,
            digg: 'http://digg.com/submit?url=' + PAGE_URL + '&title=' + text,
            linkedin: 'https://www.linkedin.com/shareArticle?url=' + PAGE_URL + '&title=' + text,
            stumbleupon: 'http://www.stumbleupon.com/submit?url=' + PAGE_URL + '&title=' + text,
            reddit: 'https://reddit.com/submit?url=' + PAGE_URL + '&title=' + text,
            tumblr: 'https://plus.google.com/share?url=' + PAGE_URL + '&title=' + text,
            facebook: facebookUrl
        };

        if (urls.hasOwnProperty(socialType)) {
            return urls[socialType];
        }

        return '';
    };

    var updateTooltip = function updateTooltip(rect) {
        var actualPosition = document.documentElement.scrollTop || document.body.scrollTop;
        var body = document.querySelector('body');

        tooltip.style.top = actualPosition + rect.top - TOOLTIP_HEIGHT * FACTOR + 'px';
        tooltip.style.left = rect.left + rect.width / 2 - body.getBoundingClientRect().width / 2 + 'px';

        Array.prototype.forEach.call(parameters.buttons, function (btn) {
            tooltip.querySelector('.share-selected-text-btn-' + btn).href = generateSocialUrl(btn, selected.text);
        });

        window.setTimeout(function () {
            showTooltip();
        }, parameters.tooltipTimeout);
    };

    var generateAnchorTag = function generateAnchorTag(anchorType) {
        var customIconClass = arguments.length <= 1 || arguments[1] === undefined ? null : arguments[1];

        var anchorTag = document.createElement('A');
        var anchorIcon = document.createElement('i');

        if (parameters.anchorsClass) {
            anchorTag.classList.add('share-selected-text-btn', 'share-selected-text-btn-' + anchorType, '' + parameters.anchorsClass);
        } else {
            anchorTag.classList.add('share-selected-text-btn', 'share-selected-text-btn-' + anchorType);
        }

        if (customIconClass) {
            anchorIcon.classList.add('' + customIconClass);
        } else {
            anchorIcon.classList.add('icon-sst-' + anchorType, 'fa', 'fa-' + anchorType);
        }

        anchorIcon.style.pointerEvents = 'none';
        anchorTag.addEventListener('click', function (e) {
            e.preventDefault();
            var windowFeatures = 'status=no,menubar=no,location=no,scrollbars=no,width=720,height=540';
            var url = e.target.href;
            window.open(url, 'Share this post', windowFeatures);
        });

        anchorTag.href = generateSocialUrl(anchorType, selected.text ? selected.text : '');
        anchorTag.appendChild(anchorIcon);
        return anchorTag;
    };

    var generateTooltip = function generateTooltip() {
        var body = document.querySelector('body');
        var mainDiv = document.createElement('DIV');
        var btnContainer = document.createElement('DIV');

        mainDiv.classList.add('share-selected-text-main-container');
        btnContainer.classList.add('share-selected-text-inner');

        if (parameters.tooltipClass) {
            btnContainer.classList.add(parameters.tooltipClass);
        }

        mainDiv.style.height = TOOLTIP_HEIGHT + 'px';
        mainDiv.style.top = 0;
        mainDiv.style.left = 0;

        Array.prototype.forEach.call(parameters.buttons, function (btn) {
            var aTag = generateAnchorTag(btn);
            btnContainer.appendChild(aTag);
        });

        mainDiv.appendChild(btnContainer);
        body.appendChild(mainDiv);

        return mainDiv;
    };

    var getSelectedText = function getSelectedText() {
        var text = '';
        var selection = undefined;

        if (window.getSelection) {
            selection = window.getSelection();
            text = selection.toString();
        } else if (document.selection && document.selection.type !== 'Control') {
            selection = document.selection.createRange();
            text = selection.text;
        }

        return {
            selection: selection,
            text: text
        };
    };

    var shareTooltip = function shareTooltip() {
        selected = getSelectedText();

        if (selected.text.length) {
            var oRange = selected.selection.getRangeAt(0);
            var oRect = oRange.getBoundingClientRect();
            updateTooltip(oRect);
        } else {
            hideTooltip();
        }


        $('*').click(function(e) {
            hideTooltip();
        });
    };

    exports.shareSelectedText = function (element, args) {
        var elt = document.querySelectorAll(element);

        parameters = extend({
            tooltipClass: '',
            sanitize: true,
            buttons: [SOCIAL.twitter, SOCIAL.buffer],
            anchorsClass: '',
            twitterUsername: '',
            facebookAppID: '',
            facebookDisplayMode: FACEBOOK_DISPLAY_MODES.popup,
            tooltipTimeout: TOOLTIP_TIMEOUT,
        }, args);

        tooltip = generateTooltip();

        Array.prototype.forEach.call(elt, function (el) {
            el.addEventListener('mouseup', function () {
                shareTooltip();
            });
        });
    };
})(window);

/*global jQuery, shareSelectedText*/
if (window.jQuery) {
    (function ($, shareSelected) {
        'use strict';

        var shareSelectedify = function shareSelectedify(el, options) {
            shareSelected(el, options);
        };

        $.fn.shareSelectedText = function (options) {
            return shareSelectedify(this.selector, options);
        };
    })(jQuery, shareSelectedText);
}
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

                if(_window_width < _mobile){
                    $("html, body").animate({ scrollTop: 0 }, 0);
                }

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

                if(_window_width < _mobile){
                    $("html, body").animate({ scrollTop: 0 }, "slow");
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
                keyLink.parent().addClass('opened-reflink');
                var allLinks = $('.content-inner-text a[href$="'+curAttr+'"]');
                $(parent).fadeOut(article.delay);
                $(parent).fadeIn(article.delay);
                $(allLinks).each(function( index ) {
                    $(this).attr('data-index', index+1);
                });

                if(_window_width < _mobile){
                    $("html, body").animate({ scrollTop: 0 }, 0);
                }

                if(_window_width > _mobile){
                    var
                        curAttrIndex = cur.data('index'),
                        nextAttrIndex = curAttrIndex+1,
                        nextCur = $('.text-reference[href$="'+curAttr+'"][data-index='+nextAttrIndex+']');

                    var
                        prevAttrIndex = curAttrIndex-1,
                        prevCur = $('.text-reference[href$="'+curAttr+'"][data-index='+prevAttrIndex+']');

                    $(btnPrev).css('opacity','1');
                    $(btnNext).css('opacity','1');

                    if(prevCur.length == 0) {
                        $(btnPrev).css('opacity','0.5');
                    }

                    if(nextCur.length == 0) {
                        $(btnNext).css('opacity','0.5');
                    }

                    $(parent).find('.arrows').fadeIn(0);
                }

                e.preventDefault();
            });
        },
        detectCoordinate: function(cur,parent) {
            if($('.text-reference').length) {
                var alignCenter = (_window_height - $(parent).height())/2;
                if(_window_width > _mobile){
                    $('html, body').animate({ scrollTop: cur.offset().top - alignCenter }, article.delay+200);
                } else {
                    $('html, body').animate({ scrollTop: cur.offset().top }, 0);
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

                if(_window_width > _mobile){
                    if(nextCur.length == 0) {
                        $(btnNext).css('opacity','0.5');

                    } else {
                        $(btnNext).css('opacity','1');
                        $(btnPrev).css('opacity','1');
                    }
                    nextCur.trigger('click');
                } else {
                    var curMobileItem = $('.sidebar-widget-articles-references .opened-reflink'),
                        curMobileItemIndex = curMobileItem.data('li-index'),
                        curMobileItemNextIndex = curMobileItemIndex+1,
                        curMobileItemParentNext = $('li[data-li-index='+curMobileItemNextIndex+']'),
                        curMobileItemNext = curMobileItemParentNext.find('>a');

                    if(curMobileItemParentNext.length == 0) {
                        $(btnNext).css('opacity','0.5');

                    } else {
                        $(btnPrev).css('opacity','1');
                        $(btnNext).css('opacity','1');
                        article.changeContentPupop(curMobileItemNext);
                        curMobileItem.removeClass('opened-reflink');
                        curMobileItemParentNext.addClass('opened-reflink');
                    }
                }
            });
        },
        arrowsSwitchPrev: function(btnPrev,btnNext) {
            $(btnPrev).click(function(e) {
                var cur = $('.text-reference-opened'),
                    curAttrIndex = cur.data('index'),
                    curAttr = cur.attr('href'),
                    nextAttrIndex = curAttrIndex-1,
                    prevCur = $('.text-reference[href$="'+curAttr+'"][data-index='+nextAttrIndex+']');
                if(_window_width > _mobile){
                    if(prevCur.length == 0) {
                        $(btnPrev).css('opacity','0.5');

                    } else {
                        $(btnPrev).css('opacity','1');
                        $(btnNext).css('opacity','1');
                    }

                    prevCur.trigger('click');
                } else {
                    var curMobileItem = $('.sidebar-widget-articles-references .opened-reflink'),
                        curMobileItemIndex = curMobileItem.data('li-index'),
                        curMobileItemPrevIndex = curMobileItemIndex-1,
                        curMobileItemParentPrev = $('li[data-li-index='+curMobileItemPrevIndex+']'),
                        curMobileItemPrev = curMobileItemParentPrev.find('>a');

                    if(curMobileItemParentPrev.length == 0) {
                        $(btnPrev).css('opacity','0.5');
                    } else {
                        $(btnPrev).css('opacity','1');
                        $(btnNext).css('opacity','1');
                        article.changeContentPupop(curMobileItemPrev);
                        curMobileItem.removeClass('opened-reflink');
                        curMobileItemParentPrev.addClass('opened-reflink');
                    }

                }
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
                'digg',
                'tumblr',
            ],
            anchorsClass: '',    // class given to each tooltip's links
            twitterUsername: '', // for twitter widget, will add 'via @twitterUsername' at the end of the tweet.
            facebookAppID: '', // Can also be an HTML element inside the <head> tag of your page : <meta property="fb:APP_ID" content="YOUR_APP_ID"/>
            facebookDisplayMode: 'popup', //can be 'popup' || 'page'
            tooltipTimeout: 50  //Timeout before that the tooltip appear in ms
        });
    }

    //EVENTS
    $(document).ready(function() {
        article.closeReference('.icon-close-popup ','.reference-popup');
        article.openReferencePopup('.sidebar-widget-articles-references ul li li>a');
        article.openReference('.key-references-list a','.reference-popup');
        article.openReference('.bg-news-list a','.reference-popup');
        article.openReferenceTextLink('.text-reference[data-type="bible"]','.reference-popup', '.reference-popup .left','.reference-popup .right');
        article.openReferenceTextLink('.text-reference[data-type="term"]','.reference-popup', '.reference-popup .left','.reference-popup .right');
        article.openTooltip('.rel-tooltip','.reference-popup');
        article.arrowsSwitchNext('.reference-popup .right','.reference-popup .left');
        article.arrowsSwitchPrev('.reference-popup .left','.reference-popup .right');
        article.articleReference('.sidebar-widget-articles-references','li:not(.sidebar-articles-item) ul>li');
    });

    $(window).load(function() {

        shareSelected('.article-full article');

        var countries_arrays = mapConfig.source;
        var countries_array = {};

        for (var prop in countries_arrays) {
            countries_array[countries_arrays[prop]] = {};
        }

        var elements = {
            mapMini: 'map-mini',
            mapMedium: 'article-map-medium'
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
                    weight: 1,
                    opacity: 1,
                    color: mapObj.getBorderColor(feature.properties.economy),
                    dashArray: '1',
                    fillOpacity: 1,
                    fillColor: mapObj.getColor(feature.properties.economy)
                };
            },
            getColor: function(d) {
                return  d === 'factor-efficiency' ? '#9ced10' :
                    d === 'factor' ? '#f6ff00' :
                        d === 'efficiency-innovation' ? '#008954' :
                            d === 'efficiency' ? '#49da2c' :
                                d === 'innovation' ? '#00453a' :
                                    d === 'none' ? '#d2e1e8' :
                                        '#d2e1e8';
            },
            getBorderColor: function(d) {
                return  d === 'factor-efficiency' ? '#86d400' :
                    d === 'factor' ? '#cfd700' :
                        d === 'efficiency-innovation' ? '#006c42' :
                            d === 'efficiency' ? '#3bc81f' :
                                d === 'innovation' ? '#00352d' :
                                    d === 'none' ? '#e2ecf3' :
                                        '#e2ecf3';
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
                                value_countries.country = country.properties.name;
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

        article.openPrintWindow('.btn-print');
        article.addToFavourite('.btn-like');
    });

})(jQuery);