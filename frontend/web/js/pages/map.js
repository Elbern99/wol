(function($) {

    //ELEMENTS
    var elements  = {
        window: $(window),
        mapInfo: $('.map-info'),
        overlay: $('.overlay'),
        LMarker: $('.leaflet-marker-icon')
    };

    //GLOBAL VARIABLE ---------
    var _window_width = elements.window.width(),
        _mobile = 769,
        _mobileSmall  = 413,
        _tablet = 1025,
        _click_touch = ('ontouchstart' in window) ? 'touchstart' : ((window.DocumentTouch && document instanceof DocumentTouch) ? 'tap' : 'click');

    elements.window.resize(function() {
        _window_width = elements.window.width();
    });

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
            facebookAppID: '686925074844965',
            facebookDisplayMode: 'popup',
            tooltipTimeout: 50
        });
    }

    $(document).ready(function() {

        shareSelected('.evidence-map-text, .map-info, .article-map h1, .evidence-map-list');

        var countries_array = JSON.parse(mapConfig.source);

        var contentMap = {
            MobileMoreText: function(btn){
                $(btn).click(function(e) {
                    var cur = $(this);
                    cur.parent().toggleClass('open-text');
                    e.preventDefault();
                });
            },
            closeOverlay: function(overlay,triggerEl) {
                var
                    $overlay = $(overlay);
                    $overlay.addClass('map-overlay');

                $overlay.click(function(e) {
                    $overlay.addClass('js-tab-hidden').removeClass('active');
                    $(triggerEl).trigger('click');
                });
            }
        };

        contentMap.MobileMoreText('.more-evidence-map-text-mobile');
        contentMap.closeOverlay('.overlay', '.map-holder .icon-close');

        var mapObj = {
            options: {
                maxBounds: new L.LatLngBounds( new L.LatLng(-60, -180), new L.LatLng(86, 180)),
                minZoom: 0,
                maxZoom: 5,
                zoom: 2,
                attributionControl: false,
                clickable: false,
                tap: true,
                trackResize: true,
                center: [30, 10]
            },
            style: function(feature) {
                return {
                    stroke: false,
                    fill: false,
                    className: mapObj.getColor(feature.properties.economy)
                };
            },
            setZoom: function(map) {
                if (_window_width < _mobileSmall)   {
                    map.setMinZoom(0);
                    map.setZoom(0);
                } else if( _window_width < _tablet  && _window_width > _mobileSmall)  {
                    map.setMinZoom(0);
                    map.setZoom(1);
                } else if (_window_width > _tablet) {
                    map.setMinZoom(2);
                    map.setZoom(2);
                }
            },
            getColor: function(d) {
                if(d !== undefined) return d;
            },
            onEachFeature: function(feature, layer) {
                layer.on({});
            },
            hideInfoMap: function(btn,overlay){

                var
                    $overlay = $(overlay);

                map.on('click', function(e) {
                    elements.mapInfo.removeClass('map-info-open');
                    $overlay.addClass('js-tab-hidden').removeClass('active');
                    $('.leaflet-marker-icon').removeClass('opened-ref-tooltip');
                });

                map.on('movestart', function(e) {
                    elements.mapInfo.removeClass('map-info-open');
                    $overlay.addClass('js-tab-hidden').removeClass('active');
                    $('.leaflet-marker-icon').removeClass('opened-ref-tooltip');
                });

                $(btn).on('click', '.icon-close', function(e) {
                    elements.mapInfo.removeClass('map-info-open');
                    $overlay.addClass('js-tab-hidden').removeClass('active');
                    $('.leaflet-marker-icon').removeClass('opened-ref-tooltip');

                    $('.map-info-content').animate({
                        scrollTop: 0
                    }, 0);
                });
            },
            addNumbersForSources: function(list) {
                if($(list).length) {

                    var
                        $list = $(list);

                    $list.each(function(i) {
                        var $curParent = $(this);

                        $curParent.find('li').each(function(i) {
                            var
                                $cur = $(this),
                                curIndex = parseInt(i)+1;

                            $cur.prepend('['+curIndex+'] ');
                        });
                    });
                }
            },
            onMapClick: function(event) {
                event.target.closePopup();
                var
                    popup = event.target.getPopup();
                elements.mapInfo.addClass('map-info-open').find('.map-info-content').html(popup._content);
                elements.overlay.removeClass('js-tab-hidden').addClass('active');

                elements.LMarker.removeClass('opened-ref-tooltip');

                this._icon.classList.add("opened-ref-tooltip");


                mapObj.addNumbersForSources('.ref-type-list,.ref-source-list');

                if(_window_width < _mobile){
                    if(elements.window.scrollTop() !== 0) {
                        $("html, body").animate({ scrollTop: 0 }, 0);
                    }
                }
            },
            zoomControl:  function() {
                var
                    zoomCount = map.getZoom(),
                    $label = $('.leaflet-marker-iconlabel'),
                    checkZoom = zoomCount > 4;

                if(checkZoom) {
                    $label.fadeIn();
                } else {
                    $label.fadeOut();
                }
            }
        };

        var map = L.map('map', mapObj.options),
            geojson;

        mapObj.setZoom(map);

        map.zoomControl.setPosition('bottomright');
        mapObj.hideInfoMap('.map-holder','.overlay');

        map.on('zoomend', function() {
            mapObj.zoomControl();
        });

        //----------1 get
        $.getJSON(mapConfig.json_path_country, function( data ) {
            $.each(data[1], function(index, country) {

                $.each(countries_array, function(index, value) {
                    if(index === country.iso2Code) {
                        countries_array[index].id = country.id;
                        countries_array[index].capital = {};
                        countries_array[index].capital.x = country.longitude;
                        countries_array[index].capital.y = country.latitude;
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

                    $.each(countries_array, function(index, value) {
                        var capital = value.capital;

                        if (capital !== undefined)  {
                            var
                                x = capital.x;
                            y = capital.y;

                            //references
                            var key_references_obj = value.key_references,
                                key_additional_obj = value.additional_references,
                                key_country = value.country,
                                arrayTpl = [];

                            if (key_country !== undefined) {
                                if(key_references_obj) {
                                    var key_references_length = value.key_references.length;

                                    arrayTpl.push('<h3>Key reference(s) from '+key_country+'</h3>');

                                    for (i = 0; i < key_references_obj.length; i++) {
                                        var references_full_citation = key_references_obj[i].full_citation,
                                            references_method = key_references_obj[i].method,
                                            references_position = key_references_obj[i].position,
                                            references_source = key_references_obj[i].source,
                                            references_type = key_references_obj[i].type;

                                        //template
                                        var dataSourceText,
                                            dataTypesText,
                                            dataMethodText,
                                            dataLinkText;

                                        if(references_source !== '<li></li>') {

                                            dataSourceText = '<div class="dates">Data source(s): <ul class="ref-source-list">'+references_source+'</ul></div>';
                                        } else {
                                            dataSourceText = '';
                                        }

                                        if(references_type !== '<li></li>') {
                                            dataTypesText = '<div class="types">Data type(s): <ul class="ref-type-list">'+references_type+'</ul></div>';
                                        } else {
                                            dataTypesText = '';
                                        }

                                        if(references_method !== '') {
                                            dataMethodText = '<div class="method">Method(s): <br><strong>'+references_method+'</strong></div>';
                                        } else {
                                            dataMethodText = '';
                                        }

                                        if(references_full_citation !== '') {
                                            dataLinkText = '<div class="link">'+references_full_citation+' ['+references_position+']</div>';
                                        } else {
                                            dataLinkText = '';
                                        }

                                        arrayTpl.push('' +
                                            '<div class="ref-item">' +dataLinkText+dataSourceText+dataTypesText+dataMethodText+ '</div>'
                                        );
                                    }
                                }

                                if(key_additional_obj) {
                                    var key_additional_length = key_additional_obj.length;
                                    arrayTpl.push('<h3>Additional reference(s) from '+key_country+'</h3>');
                                    for (i = 0; i < key_additional_obj.length; i++) {
                                        var additional_full_citation = key_additional_obj[i].full_citation,
                                            additional_method = key_additional_obj[i].title;

                                        //template
                                        var dataAdditionalMethodText,
                                            dataAdditionalLinkText;

                                        if(additional_method !== '') {
                                            dataAdditionalMethodText = '<div class="method">Method(s): '+additional_method+'</div>';
                                        } else {
                                            dataAdditionalMethodText = '';
                                        }

                                        if(additional_full_citation !== '') {
                                            dataAdditionalLinkText = '<div class="link">'+additional_full_citation+'</div>';
                                        } else {
                                            dataAdditionalLinkText = '';
                                        }

                                        arrayTpl.push('' +
                                            '<div class="ref-item">' +dataAdditionalLinkText+ '</div>'
                                        );
                                    }
                                }

                                var key_ref_tpl;

                                if(arrayTpl) {
                                    key_ref_tpl = arrayTpl.join("\n");
                                }

                                if(key_references_length === undefined){
                                    key_references_length = 0;
                                }

                                if(key_additional_length === undefined){
                                    key_additional_length = 0;
                                }

                                var articles_count = key_references_length + key_additional_length;

                                var LeafIcon = L.divIcon({
                                    iconSize: new L.Point(24, 35),
                                    html: '<div class="icon-number-reference '+ key_country.replace(/\s/ig, '-')+'">'+ articles_count +'</div><div class="leaflet-marker-iconlabel">'+value.country+'</div>',
                                });

                                var marker = L.marker([y,x], {icon: LeafIcon, labelText: "Love it!"}).bindPopup(key_ref_tpl).addTo(map);

                                marker.on('click', mapObj.onMapClick );
                            }
                        };
                    });

                })
            });
        });
    });

})(jQuery);