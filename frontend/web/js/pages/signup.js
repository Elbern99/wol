(function($) {

//ELEMENTS
    var elements = {
        window: $(window),
        document: $(document)
    }

//GLOBAL VARIABLE ---------
    var _window_height = elements.window.height(),
        _window_width = elements.window.width(),
        _doc_height = elements.document.height(),
        _click_touch = ('ontouchstart' in window) ? 'touchstart' : ((window.DocumentTouch && document instanceof DocumentTouch) ? 'tap' : 'click'),
        _mobile = 769,
        _tablet = 1025;

    elements.window.resize(function() {
        _window_height = elements.window.height();
        _window_width = elements.window.width();
        _doc_height = elements.document.height();
    });

    var account = {
        tabsLocation: function(tabsMenu,tab) {
            if(window.location.hash && $(tabsMenu).length) {
                var hashArray = window.location.hash.split('-'),
                    hashIndex = parseInt(hashArray[1]) - 1;
                $(tabsMenu).eq(hashIndex).addClass('active').siblings().removeClass("active");
                $(tab).removeClass("active").addClass('js-tab-hidden').eq(hashIndex).removeClass('js-tab-hidden').addClass('active');
            }
        },
        tabs: function(tabsMenu,tab) {
            $(tabsMenu).click(function(event) {
                event.preventDefault();
                $(this).parent().addClass("active");
                $(this).parent().siblings().removeClass("active");
                var tabCur = $(this).attr("href");
                window.location.hash = tabCur;
                $(tab).not(tabCur).removeClass('active').addClass('js-tab-hidden');
                $(tabCur).addClass('active').removeClass('js-tab-hidden');
            });
        },
        formEdit: function(btn) {
            $(btn).click(function(e) {
                var cur = $(this),
                    curParent = cur.parent();

                curParent.find('.hidden').slideToggle();
                curParent.toggleClass('opened');
                e.preventDefault();
            });
        }
    };

    //EVENTS
    elements.document.ready(function() {
        account.tabsLocation('.account-tabs-list li', '.tab');
    });

    elements.window.load(function() {
        account.tabs('.account-tabs-list a','.tab');
        account.formEdit('.form-item-edit .edit');
        account.formEdit('.form-item-edit .edit-password');
    });
    
})(jQuery);