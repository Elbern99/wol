(function($) {
    
    function avatarChange() {
        
        $('#load_image').on('change', function(event) {
            var form = $(event.currentTarget).closest('form');
            var formData = new FormData(form[0]);

            $.ajax({
                url: form.attr("action"),
                type: 'POST',
                data: formData,
                async: true,
                success: function (result) {

                    if ('success' in result) {
                        $('.user-avatar').css({'background-image':'url('+result.success+')'});
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
    }
    
    $(document).ready(function() {
        avatarChange();
    });
    
})(jQuery);
