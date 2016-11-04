(function($) {
    
    var doc = $(document);
    
    function updateStatus() {
        
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
    }
    
    doc.ready(function() {
        updateStatus();
    });
    
    doc.on('pjax:end', function(e) {
        updateStatus();
    });
    
})(jQuery);