(function($){
    function sendAlerts(element) {
        $.ajax({
            url: element.data('url'),
            type: 'POST',
            success: function(res){
                $('#article-newsletter-alerts')
                    .addClass('alert alert-success')
                    .html('<strong>Success!</strong> Emails sent successfully to the queue')
                    .fadeTo(8000, 8000).slideUp(1000, function(){});
                return true;
            },
            error: function(){
                $('#article-newsletter-alerts')
                    .addClass('alert alert-error')
                    .html('<strong>Error!</strong> Contact to administrators')
                    .fadeTo(8000, 8000).slideUp(1000, function(){});
                return false;
            }
        });
    }

    $('.send-alerts').on('click', function () {
        sendAlerts($(this));
        $(this).attr('disabled', true);
    })
})(jQuery);