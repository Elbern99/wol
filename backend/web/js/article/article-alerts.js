(function($){
    function sendAlerts(element) {
        $.ajax({
            url: element.data('url'),
            type: 'POST',
            success: function(res){
                console.log(res);
            },
            error: function(){
                alert('Error!');
            }
        });
    }

    $('.send-alerts').on('click', function () {
        sendAlerts($(this));
    })
})(jQuery);