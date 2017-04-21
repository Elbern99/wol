$('.textarea-line textarea').slideUp(0);

$('.save-citation').on('click',function() {
    var cur = $(this),
        curParent =  cur.parents('.textarea-line'),
        control = curParent.find('.form-control'),
        text = curParent.find('.text-for-form-control');

        control.slideUp(0);
        text.slideDown(100);

    var val = control.val();

    text.html(val);
});

$('.edit-citation').on('click',function() {
    var cur = $(this),
        curParent =  cur.parents('.textarea-line'),
        control = curParent.find('.form-control'),
        text = curParent.find('.text-for-form-control');

        control.slideDown(100);
        text.slideUp(0);
});