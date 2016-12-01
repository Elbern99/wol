(function($) {

  /* old example need remove */
  var countries_array =  {
    'AU':{
      'name':'Australia',
      'link':'http:australia.ru',
      'content': {
        'key_references': {
          'title': 'Key references from Australia',
          'columns': [
            {
              'Reference': [
                {
                  'link_title':'Organisation for Economic Co-operation and <em>Development Employment Outlook</em>, Paris: OECD Publishing, 2013. [2]',
                  'link':'http://dx.doi.org/10.1787/empl_outlook-2013-en'
                },
                {
                  'link_title':'OECD Employment Outlook, Paris: OECD Publishing, 2010. [9]',
                  'link':'http://www.w3.org/1999/xhtml'
                }  
              ]
            },
            {
              'Data type(s)': [
                {
                  'type':'[1] Other - Time series'
                }
              ]
            },
            {
              'Method(s)': [
                {
                  'method':'Macro-level analysis - Descriptive statistics'
                }
              ]
            }
          ]
        },
        'additional_references': {
          'title': 'Additional references from United States',
          'reference_title': 'Reference',
          'columns': [
            {
              'link_title': 'Organisation for Economic Co-operation and Development Employment Outlook, Paris: OECD Publishing, 2006.',
              'link': 'http://dx.doi.org/10.1787/empl_outlook-2006-en'
            },
            {
              'link_title': 'Organisation for Economic Co-operation and Development Employment Outlook, Paris: OECD Publishing, 2006.',
              'link': 'http://dx.doi.org/10.1787/empl_outlook-2006-en'
            },
            {
              'link_title': 'Organisation for Economic Co-operation and Development Employment Outlook, Paris: OECD Publishing, 2006.',
              'link': 'http://dx.doi.org/10.1787/empl_outlook-2006-en'
            }
          ]
        }
      }
    }
  }  

  $(document).ready(function() {

    //console.log(JSON.parse(mapConfig.source));
    mapObj = { 
      options: {
          maxBounds: new L.LatLngBounds( new L.LatLng(-60, -180), new L.LatLng(86, 180)),
          inertia: false,
          minZoom: 2,
          maxZoom: 4,
          zoom: 2,
          attributionControl: false,
          clickable: false
      },
      style: function(feature) {
        return {
          weight: 1,
          opacity: 1,
          color: mapObj.getBorderColor(feature.properties.economy),
          dashArray: '1',
          fillOpacity: 0.7,
          fillColor: mapObj.getColor(feature.properties.economy)
        };
      },
      getColor: function(d) {
        return  d === 'factor-efficiency' ? '#01BBD7' :
                d === 'factor' ? '#0EA494' :
                d === 'efficiency-innovation' ? '#3AB000' :
                d === 'efficiency' ? '#87DB00' :
                d === 'innovation' ? '#00550C' :
                d === 'none' ? '#C8D9E1' :
                '#C8D9E1';
      },
      getBorderColor: function(d) {
        return  d === 'factor-efficiency' ? '#2D8580' :
                d === 'factor' ? '#2D8580' :
                d === 'efficiency-innovation' ? '#257525' :
                d === 'efficiency' ? '#55A617' :
                d === 'innovation' ? '#00550C' :
                d === 'none' ? '#C1D2DA' :
                '#C1D2DA';
      },
      onEachFeature: function(feature, layer) {
        layer.on({});
      },
      hideInfoMap: function(){
        map.on('click', function(e) {        
          $('.map-info').removeClass('map-info-open');
        });

        map.on('movestart', function(e) {        
            $('.map-info').removeClass('map-info-open');
        });

        $('.map-holder').on('click', '.icon-close', function(e) {        
            $('.map-info').removeClass('map-info-open');
        });
      },
      onMapClick: function(event) {
        event.target.closePopup();
        var popup = event.target.getPopup();
        $('.map-info').addClass('map-info-open').find('.map-info-content').html(popup._content);
      }
    }    

    var map = L.map('map', mapObj.options).setView([40, 0], 2),
        geojson;

        map.zoomControl.setPosition('bottomright');
        mapObj.hideInfoMap();

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
                var key_references = value.content.key_references,
                    key_references_text = key_references.title,
                    key_references_columns = key_references.columns,
                    key_references_columns_length = key_references_columns.length;
                    key_references_list = [];

                //additional 
                var additional_references = value.content.additional_references,
                    additional_references_text = additional_references.title,
                    aditional_references_title = additional_references.reference_title,
                    aditional_columns = additional_references.columns,
                    aditional_columns_length = aditional_columns.length,
                    aditional_references_list = [];

                  for (i = 0; i < aditional_columns.length; i++) { 
                    aditional_references_list.push('<li><a href="'+aditional_columns[i].link+'" target="_blank">'+aditional_columns[i].link_title+'</a></li>');
                  }  

                  var tpl = [
                      '<div class="title">'+additional_references_text+'</div>',
                      '<div class="title-references">'+aditional_references_title+'</div>',
                      '<ul class="references-list">'+aditional_references_list.join("\n")+'</ul>'
                  ].join("\n");

                var articles_count = aditional_columns_length+key_references_columns_length;  

                var LeafIcon = L.divIcon({
                    iconSize: new L.Point(20, 27), 
                    html: '<div class="map-icon">'+ articles_count +'</div>'
                });

                var marker = L.marker([y,x], {icon: LeafIcon}).bindPopup(tpl).addTo(map);

                marker.on('click', mapObj.onMapClick );
            });

          })
        });
      });
  });

})(jQuery);