jQuery(function($){
    
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    
    $('.profile-author-letter').on('click', function() {
       var link = $(this);
       
       $.ajax({
            url : link.prop('href'),
            type: 'POST',
            dataType: 'json',
            data : {'letter': link.data('letter'), '_csrf' : csrfToken},
            success:function(data, textStatus, jqXHR)  {

                if (data.length) {
                    alert(data);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
            }
       });
       
       return false;
    });
});


