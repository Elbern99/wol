(function($) {

    //ELEMENTS
    var elements  = {
        window: $(window),
        mapInfo: $('.map-info')
    }

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
            facebookAppID: '369541053397983', // Can also be an HTML element inside the <head> tag of your page : <meta property="fb:APP_ID" content="YOUR_APP_ID"/>
            facebookDisplayMode: 'popup', //can be 'popup' || 'page'
            tooltipTimeout: 50  //Timeout before that the tooltip appear in ms
        });
    }

  $(document).ready(function() {

    shareSelected('.evidence-map-text, .map-info, .article-map h1, .evidence-map-list');

    var countries_array = JSON.parse(mapConfig.source);

    var contentMap = {
        MobileMoreText: function(btn){
            $(btn).click(function(e) {
                var cur = $(this);
                    cur.parent().toggleClass('open-text-map');
                e.preventDefault();
            });
        }
    };

    contentMap.MobileMoreText('.more-evidence-map-text-mobile');

    var mapObj = {
      options: {
          maxBounds: new L.LatLngBounds( new L.LatLng(-60, -180), new L.LatLng(86, 180)),
          inertia: false,
          minZoom: 0,
          maxZoom: 5,
          zoom: 2,
          attributionControl: false,
          clickable: false,
          boxZoom: false,
          tap: false,
          trackResize: true,
          center: [30, 10],
          load: function(){
              console.log('load')
          }
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
      setZoom: function(map) {
          if (_window_width < _mobileSmall)   {
              map.setMinZoom(0)
              map.setZoom(0);
          } else if( _window_width < _tablet  && _window_width > _mobileSmall)  {
              map.setMinZoom(0)
              map.setZoom(1);
          } else if (_window_width > _tablet) {
              map.setMinZoom(2)
              map.setZoom(2);
          }
      },
      getColor: function(d) {
        return  d === 'factor-efficiency' ? '#9ced10' :
                d === 'factor' ? '#f6ff00' :
                d === 'efficiency-innovation' ? '#008954' :
                d === 'efficiency' ? '#49da2c' :
                d === 'innovation' ? '#00806a' :
                d === 'none' ? '#c1d2d9' :
                '#c1d2d9';
      },
      getBorderColor: function(d) {
        return  d === 'factor-efficiency' ? '#86d400' :
                d === 'factor' ? '#cfd700' :
                d === 'efficiency-innovation' ? '#006c42' :
                d === 'efficiency' ? '#3bc81f' :
                d === 'innovation' ? '#006655' :
                d === 'none' ? '#a4b5bd' :
                '#a4b5bd';
      },
      onEachFeature: function(feature, layer) {
        layer.on({});
      },
      hideInfoMap: function(){
        map.on('click', function(e) {
            elements.mapInfo.removeClass('map-info-open');
        });

        map.on('movestart', function(e) {
            elements.mapInfo.removeClass('map-info-open');
        });

        $('.map-holder').on('click', '.icon-close', function(e) {
            elements.mapInfo.removeClass('map-info-open');
        });
      },
      onMapClick: function(event) {
        event.target.closePopup();
        var popup = event.target.getPopup();
            elements.mapInfo.addClass('map-info-open').find('.map-info-content').html(popup._content);

          if(_window_width < _mobile){
              if(elements.window.scrollTop() !== 0) {
                  $("html, body").animate({ scrollTop: 0 }, 0);
              }
          }
      },
      zoomControl:  function() {
        var zoomCount = map.getZoom(),
        label = $('.leaflet-marker-iconlabel');

        if(zoomCount > 4) {
          label.fadeIn();
        } else {
          label.fadeOut();
        }
      }
    };

    var map = L.map('map', mapObj.options),
        geojson;

    mapObj.setZoom(map);

    elements.window.resize(function() {
        setTimeout(mapObj.setZoom(map), 1000);
    });

    if(_click_touch == 'touchstart') {
        map.dragging.disable();
    }

    map.zoomControl.setPosition('bottomright');
    mapObj.hideInfoMap();

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
      var
        x = value.capital.x;
        y = value.capital.y;

        //references
        var key_references_obj = value.key_references,
            key_additional_obj = value.additional_references,
            key_country = value.country,
            arrayTpl = [];

        if(key_references_obj) {
            var key_references_length = value.key_references.length;

            arrayTpl.push('<h3>Key references from '+key_country+'</h3>');

            for (i = 0; i < key_references_obj.length; i++) {
                var references_full_citation = key_references_obj[i].full_citation,
                    references_method = key_references_obj[i].method,
                    references_position = key_references_obj[i].position,
                    references_source = key_references_obj[i].source,
                    references_title = key_references_obj[i].title,
                    references_type = key_references_obj[i].type;

                arrayTpl.push('' +
                    '<div class="ref-item">' +
                    '<div class="authors">'+references_title+'</div>' +
                    '<div class="link">'+references_full_citation+' ['+references_position+']</div>' +
                    '<div class="dates">Data source(s): <strong>'+references_source+'</strong></div>' +
                    '<div class="types">Data type(s): <strong>'+references_type+'</strong></div>' +
                    '<div class="method">Method(s): <strong>'+references_method+'</strong></div>' +
                    '</div>'
                );
            }
        }

        if(key_additional_obj) {
            var key_additional_length = key_additional_obj.length;
                arrayTpl.push('<h3>Additional references from '+key_country+'</h3>');
                for (i = 0; i < key_additional_obj.length; i++) {
                    var additional_full_citation = key_additional_obj[i].full_citation,
                        additional_method = key_additional_obj[i].title;

                    arrayTpl.push('' +
                        '<div class="ref-item">' +
                        '<div class="link">'+additional_full_citation+'</div>' +
                        '<div class="method">'+additional_method+'</div>' +
                        '</div>'
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
            html: '<div class="icon-number-reference">'+ articles_count +'</div><div class="leaflet-marker-iconlabel">'+value.country+'</div>',
        });

        var marker = L.marker([y,x], {icon: LeafIcon, labelText: "Love it!"}).bindPopup(key_ref_tpl).addTo(map);

        marker.on('click', mapObj.onMapClick );
    });

    })
    });
    });
  });

})(jQuery);