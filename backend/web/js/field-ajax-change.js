jQuery(function($){
    $('.enabled_field').on('change', function() {
        
        var item = $(this).data('item');
        var check = this.checked;

        if (item) {
            $.ajax({
                method: "POST",
                data: {id: item, enabled: check},
                dataType: "json"
            });
        }
    });
});