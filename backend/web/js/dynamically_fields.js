var dynamicallyFields = {
    _config: null,
    x: 0,
    _max_fields: 10
};

(function($){
    
    var $obj = dynamicallyFields;
    
    $obj.init = function(config) {
        this._config = config;
        this._wrapper = $(this._config.wrapper);

        this.start();
    };
    
    $obj.start = function() {
        this.generateData();
        this.addButton();
        this.removeInput();
    };
    
    $obj.generateData = function() {

        if ((typeof this._config.data == "object") && Object.keys(this._config.data).length) {

            for(var item in this._config.data) {
                this.generateInputGroup(this.x, this._config.data[item]);
                this.x++;
            }
        } else {
            this.generateInputGroup(this.x);
            this.x++;
        }
    };
    
    $obj.generateInputGroup = function(index, values = {}) {
        var input = '';
        
        for (var i in this._config.fields) {
            var field = this._config.fields[i];
            var val = '';
            
            if (field.name in values) {
                val = values[field.name];
            }
            
            input += '<label>'+field.label+'</label>'+ 
                    '<input type="'+ field.type + 
                    '" name="' + this._config.model_field_name+'['+index+']'+'['+field.name+']"';
                    
            if (val) {
                input += ' value="'+val+'"';
            }
            
            input += '>';
        }
        
        this.addInput(this.addWrapperInput(input));
    };
    
    $obj.addWrapperInput = function(input) {
        return '<div>' + input + '<a href="#" class="remove_field">Remove</a></div>';
    };
    
    $obj.addInput = function(input) {
        this._wrapper.append(input);
    };
    
    $obj.addButton = function() {
        var add_button = $(this._config.add_button);

        $(add_button).click(function(e){ //on add input button click
            e.preventDefault();
            if($obj.x < $obj._max_fields){ //max input box allowed
                $obj.generateInputGroup($obj.x);
                $obj.x++;
            }
        });
    };
    
    $obj.removeInput = function() {
        this._wrapper.on("click", ".remove_field", function(e){ //user click on remove text
            e.preventDefault(); $(this).parent('div').remove(); $obj.x--;
        });
    };
    
})(jQuery);
