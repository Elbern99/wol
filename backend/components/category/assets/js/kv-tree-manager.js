(function ($) {

    // treeList
    treeListAccordion = {
        treeListItem: function(wrapper, btn, firstParent, secondParent){
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
     
    // searchTreeList
    searchTreeList = {    
        addCss: function($el, css) {
            $el.removeClass(css).addClass(css);
        },
        escapeRegExp: function (str) {
            return str.replace(/[\-\[\]\/\{}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
        },
        searchHeader: function(tree, treeContainer, search) {    
            search.on('keyup', function () {
                var filter = $(this).val();
                
                searchTreeList.clear($('.kv-tree-container'));
                
                if (filter.length === 0) return;
                
                searchTreeList.addCss(treeContainer, 'kv-loading-search');
                filter = searchTreeList.escapeRegExp(filter);
                tree.find('.kv-parent').addClass('kv-collapsed');

                tree.find('.kv-node-label').each(function () {
                    var $label = $(this), text = $label.text();
                    var pos = text.search(new RegExp(filter, "i"));
                    if (pos < 0) {
                        $label.removeClass('kv-highlight');
                    } else {
                        searchTreeList.addCss($label, 'kv-highlight');
                        tree.find('li.kv-parent').each(function () {
                            var $node = $(this);
                            if ($node.has($label).length > 0) {
                                $node.removeClass('kv-collapsed');
                            }
                        });
                    }
                });
                treeContainer.removeClass('kv-loading-search'); 
              
            });
        },
        clear: function (tree) {
            tree.find('.kv-node-label').removeClass('kv-highlight');
        },
        clearHeader: function(clear, search){
                clear.on('click', function () {
                search.val('');
                searchTreeList.clear($('.kv-tree-container'));
            });
        }
    }
   
   
    $(document).ready(function(){
        treeListAccordion.treeListItem('.kv-tree-list', '.kv-root-node-toggle', '.kv-tree-list', '.kv-parent');
        treeListAccordion.treeListHead('.kv-tree-root', '.kv-root-node-toggle', '.text-primary');

        searchTreeList.searchHeader($('.kv-tree-wrapper'), $('.kv-tree-container'), $('.kv-search-input'));
        searchTreeList.clearHeader($('.kv-search-clear'),$('.kv-search-input'));
    });

})(jQuery);