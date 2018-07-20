jQuery(document).ready(function () {
    fss_rating_events();
});

function fss_rating_events() {

    jQuery('.can_rate').unbind('mouseover');
    jQuery('.can_rate').unbind('mouseout');
    jQuery('.can_rate').unbind('click');

    jQuery('.can_rate').mouseover(function () {
        fss_rating_over(jQuery(this));
    })
    jQuery('.can_rate').mouseout(function () {
        fss_rating_out(jQuery(this));
    })
    jQuery('.can_rate').click(function () {
        fss_rating_click(jQuery(this));
    })
}

function fss_rating_over(el) {
    var parent = el.parent();
    var current = el.attr('rating');

    parent.find('.can_rate').each(function () {
        var rate = jQuery(this).attr('rating');
        if (rate <= current) {
            jQuery(this).css('color', 'yellow');
        } else {
            jQuery(this).css('color', '#ccc');
        }
    });
}

function fss_rating_out(el) {
    var parent = el.parent();
    parent.find('.can_rate').css('color', '');
}

function fss_rating_click(el) {
    el.parent().fss_tooltip('hide');

    var div = el.parent().parent();
    var id = jQuery(div).attr('id');

    var url = jQuery(div).attr('url');
    var rating = jQuery(el).attr('rating');

    if (url == "inline")
    {
        el.parent().children().addClass('unlit');
        while (el.length > 0)
        {
            jQuery(el).removeClass('unlit');
            el = el.prev();
        }
        jQuery('#' + id).val(rating);
        return;
    }

    url = fss_url_append(url, 'id', id);
    url = fss_url_append(url, 'rating', rating);

    div.html(jQuery(div).attr('wait'));

    jQuery.get(url, function (data) {
        div.html(data);
        fss_rating_events();
    });
}