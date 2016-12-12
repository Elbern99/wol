(function($) {
    
    var search = $('#header-search-input');
    var dropdown = $('#header-search-dropdown');
    var searchRequest = null;
    
    function clearDropDown() {
        dropdown.html('');
    }
    
    function addDropDown(data) {
        
        var html = '<ul>';
        
        for (el in data) {
            html += '<li>'+data[el]+'</li>';
        }
        
        html += '</ul>';
        
        dropdown.html(html);
    }
    
    function sendSearchRequest(event) {
        
        var str = event.currentTarget.value;
        
        clearDropDown();
        
        if (str.replace(/\s/g, '').length >= 3  && !/\s/.test(event.key)) {
            
            var postData = $('#header-search-form').serialize();
            
            if (searchRequest != null) {
                searchRequest.abort();
            }
             
            searchRequest = $.ajax({
                url : '/search/ajax',
                type: 'POST',
                data : postData,
                success:function(data, textStatus, jqXHR)  {
                    
                    if (data.length) {
                        addDropDown(data);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                }
            });
        }
    };

    $(document).ready(function() {
        search.bind('keyup', sendSearchRequest);
    });
    
})(jQuery);