(function($) {

    //ELEMENTS
    var elements  = {
        window: $(window),
        mapInfo: $('.map-info'),
        overlay: $('.overlay')
    }

    //GLOBAL VARIABLE ---------
    var _window_width = elements.window.width(),
        _mobile = 820,
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
            facebookAppID: '1273981299361667',
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
            $(overlay).click(function(e) {
                $(overlay).addClass('js-tab-hidden').removeClass('active');
                $(triggerEl).trigger('click');
            });
        }
    };

    contentMap.MobileMoreText('.more-evidence-map-text-mobile');
    contentMap.closeOverlay('.overlay', '.map-holder .icon-close');

    var mapObj = {
      options: {
          maxBounds: new L.LatLngBounds( new L.LatLng(-60, -180), new L.LatLng(86, 180)),
          //inertia: false,
          minZoom: 0,
          maxZoom: 5,
          zoom: 2,
          attributionControl: false,
          clickable: false,
          //boxZoom: true,
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
        map.on('click', function(e) {
            elements.mapInfo.removeClass('map-info-open');
            $(overlay).addClass('js-tab-hidden').removeClass('active');
        });

        map.on('movestart', function(e) {
            elements.mapInfo.removeClass('map-info-open');
            $(overlay).addClass('js-tab-hidden').removeClass('active');
        });

        $(btn).on('click', '.icon-close', function(e) {
            elements.mapInfo.removeClass('map-info-open');
            $(overlay).addClass('js-tab-hidden').removeClass('active');
        });
      },
      onMapClick: function(event) {
        event.target.closePopup();
        var popup = event.target.getPopup();
            elements.mapInfo.addClass('map-info-open').find('.map-info-content').html(popup._content);
            elements.overlay.removeClass('js-tab-hidden').addClass('active');
            elements.overlay.css('height', '1px');
            elements.overlay.css('height', $(document).height());
            elements.overlay.css('max-height', $(document).height());

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
        //setTimeout(mapObj.setZoom(map), 1000);
    });

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
                  arrayTpl.push('<h3>Additional reference(s) from '+key_country+'</h3>');
                  for (i = 0; i < key_additional_obj.length; i++) {
                      var additional_full_citation = key_additional_obj[i].full_citation,
                          additional_method = key_additional_obj[i].title;

                      arrayTpl.push('' +
                          '<div class="ref-item">' +
                          '<div class="link">'+additional_full_citation+'</div>' +
                          '<div class="method">Method(s): '+additional_method+'</div>' +
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
                  html: '<div class="icon-number-reference '+ key_country+'">'+ articles_count +'</div><div class="leaflet-marker-iconlabel">'+value.country+'</div>',
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