(function($) {
    
    var search = $('.header-search-input');
    
    function searchForm(element) {
       
        var searchRequest = null;
        
        function clearDropDown(_current) {
            _current.next('.header-search-dropdown').html('');
        }

        function addDropDown(data, _current) {

            var html = '<ul class="auto-search-list">';

            for (var el in data) {
                html += '<li><span class="item">'+data[el]+'</span></li>';
            }

            html += '</ul>';

            _current.next('.header-search-dropdown').html(html) ;
        }

        element.on('keyup',function(event) {

            var str = event.currentTarget.value;
            var form = $(event.currentTarget).closest('form');
            clearDropDown(form);

            if (str.replace(/\s/g, '').length >= 3  && !/\s/.test(event.key)) {

                var postData = form.serialize();

                if (searchRequest != null) {
                    searchRequest.abort();
                }

                searchRequest = $.ajax({
                    url : '/search/ajax',
                    type: 'POST',
                    data : postData,
                    success:function(data, textStatus, jqXHR)  {

                        if (data.length) {
                            addDropDown(data, form);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                    }
                });
            }
        });
    }

    $(document).ready(function() {
        $.each(search, function() {
            var element = $(this);
            searchForm(element);
        });
    });
    
})(jQuery);

(function($) {
    if (!localStorage.getItem('already')) {
        $(document).ready(function(){
            $(window).scroll(function(){
                if ($(window).scrollTop() > 100){
                    $('#asking').css('display','block')
                }
            });
        });
    }

    $('.remember-alert').click(function(e){
        localStorage.setItem('already',true);
        $('#asking').css('display','none')
    });

    $('#asking a').click(function(e){
        e.preventDefault();
        localStorage.setItem('already',true);
        window.location.href = $(this).attr('href');
    });
})(jQuery);
