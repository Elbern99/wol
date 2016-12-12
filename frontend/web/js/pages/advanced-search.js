(function($) {

    //GLOBAL VARIABLE ---------
    var _window_height = $(window).height(),
        _window_width = $(window).width(),
        _doc_height = $(document).height(),
        _click_touch = ('ontouchstart' in window) ? 'touchstart' : ((window.DocumentTouch && document instanceof DocumentTouch) ? 'tap' : 'click'),
        _mobile = 769,
        _tablet = 1025;

    $(window).resize(function() {
        _window_width = $(window).width();
    });

    var advancedSearch = {
        customTagList: function(list,holder) {
            if($(list).length){
                $(holder).each(function( index ) {
                    $(this).find(list).tagit({
                        singleField: true,
                        placeholderText: 'Enter words seperated with spaces'
                    });
                });
            }
        },
        searchHightLight: function(input,body){
            var mark = function() {
                var keyword = $(input).val();
                $(body).unmark().mark(keyword);
            };

            if($(body).length) {
                $(input).on("input", mark);
            }
        },
        cloneEl: function(el,elToMobile) {
            function appendElements(el,elToMobile) {
                if($(elToMobile).length) {
                    var elHtml = $(el).html();

                    if (_window_width < _mobile) {
                        $(elToMobile).append(elHtml);
                    }
                }
            }

            appendElements(el,elToMobile);
            $(window).resize(function() {
                setTimeout(function(){
                    appendElements(el,elToMobile);
                }, 600);
            });
        },
        openFilter: function(btn,content) {
            if($(content).length) {
                $(btn).click(function(e) {
                    var cur = $(this);
                    cur.toggleClass('active');
                    $(content).slideToggle();
                    e.preventDefault();
                });
            }
        },
        clearAllCheckboxes: function(btn) {
            if($(btn).length) {
                $(btn).click(function(e) {
                    var cur = $(this);
                    cur.parent().find(':checkbox').prop('checked', false);
                    e.preventDefault();
                });
            }
        },
        saveSearch: function(btn) {
            if($(btn).length) {
                $(btn).click(function(e) {
                    var cur = $(this);
                    cur.addClass('added');

                    setTimeout(function(){
                        cur.removeClass('added');
                    }, 1600);
                    e.preventDefault();
                });
            }
        },
        focusCustom: function(el) {
            $(el).on('click', function(e) {
                $(this).addClass('focus');
            })

            $(document).mouseup(function (e) {
                var container = $(el);
                if (container.has(e.target).length === 0){
                    container.removeClass('focus');
                }
            });
        }
    }

    $(document).ready(function() {
        advancedSearch.customTagList('.my-tags-list', '.my-tags-holder');
        advancedSearch.searchHightLight('.search-results-top input', '.search-results-table-body .description-center');
        advancedSearch.cloneEl('.filter-clone', '.mobile-filter-container');
        advancedSearch.openFilter('.filter-mobile-link', '.mobile-filter');
        advancedSearch.clearAllCheckboxes('.sidebar-widget-filter .clear-all');
        advancedSearch.saveSearch('.btn-save-search');
        advancedSearch.focusCustom('.my-tags-list');
});
})(jQuery);