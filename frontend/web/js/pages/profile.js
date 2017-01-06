jQuery(function($){
    
    var block = $('#author-letter-result');
    
    function createResult(data) {
        
        let html = '<ul>';
        for (let item in data) {
            html += '<li>'+data[item]+'</li>';
        }
        
        html += '</ul>';
        block.html(html);
    }
    
    $('.profile-author-letter').on('click', function() {
       let link = $(this);
       
       block.html('');
       
       $.ajax({
            url : link.prop('href'),
            type: 'POST',
            dataType: 'json',
            data : {'letter': link.data('letter')},
            success: function(data, textStatus, jqXHR) {

                if (data.length) {
                    createResult(data);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
            }
       });
       
       return false;
    });
});


