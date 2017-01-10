jQuery(function($){
    
    var block = $('.author-letter-result');
    
    function createResult(data) {
        
        let html = '<ul class="abs-authors-list">';
        
        if (data.length) {
            
            for (let item in data) {
                html += '<li>'+data[item]+'</li>';
            }
            
        } else {
            html += '<li>No Result</li>';
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

                createResult(data);
                link.parent().addClass('active').siblings().removeClass('active');

            },
            error: function(jqXHR, textStatus, errorThrown) {
            }
       });
       
       return false;
    });
});


