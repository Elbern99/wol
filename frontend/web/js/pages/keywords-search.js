jQuery(function($){
    $('.search-keywords-article').on('click', function(){
        var _text = $(this).text();
        
        if (_text) {
            var _form = $('.keywords-search-form');
            
            _form.find('input:text').prop('value', _text);
            _form.submit();
        }
        return false;
    });
});

