(function ($) {

    //treelist
    treeList = {
        treeListAccordion: function(wrapper, btn, firstParent, secondParent){
            $(wrapper).on('click', btn, function(){
            var first_parent = $(this).parent(firstParent),
                second_parent = first_parent.parent(secondParent);
                
                second_parent.toggleClass('kv-collapsed');
            });
        },
        treeListHead: function(wrapper, btn, firstParent){
            $(wrapper).on('click', btn, function(){
                var first_parent = $(this).parent(firstParent);
                    
                    first_parent.toggleClass('kv-collapsed');
                    
                    if(first_parent.hasClass('kv-collapsed')) {
                        $('.kv-parent').addClass('kv-collapsed');
                    } else {
                        $('.kv-parent').removeClass('kv-collapsed');
                    }
             });
        }
    }
   
    $(document).ready(function(){
        treeList.treeListAccordion('.kv-tree-list', '.kv-root-node-toggle', '.kv-tree-list', '.kv-parent');
        treeList.treeListHead('.kv-tree-root', '.kv-root-node-toggle', '.text-primary');
    });

})(jQuery);