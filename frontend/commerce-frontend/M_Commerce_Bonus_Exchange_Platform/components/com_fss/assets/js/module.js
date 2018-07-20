
function fss_mod_page(uid, page) {
    var max_page = parseInt(jQuery('.fss_mod_' + uid + ' .page_max').text());
    var last_page = parseInt(jQuery('.fss_mod_' + uid + ' .cur_page').text());

    if (page == 'p') {
        page = last_page - 1;
    } else if (page == 'n') {
        page = last_page + 1;
    }

    page = parseInt(page);

    // numerical page at this point

    jQuery('.fss_mod_' + uid + ' .page_' + last_page).removeClass('active');
    jQuery('.fss_mod_' + uid + ' .art_page_' + last_page).hide();

    jQuery('.fss_mod_' + uid + ' .page_' + page).addClass('active');
    jQuery('.fss_mod_' + uid + ' .art_page_' + page).show();

    if (page > 1) {
        jQuery('.fss_mod_' + uid + ' .page_prev').removeClass('disabled');
    } else {
        jQuery('.fss_mod_' + uid + ' .page_prev').addClass('disabled');
    }

    if (page < max_page) {
        jQuery('.fss_mod_' + uid + ' .page_next').removeClass('disabled');
    } else {
        jQuery('.fss_mod_' + uid + ' .page_next').addClass('disabled');
    }

    jQuery('.fss_mod_' + uid + ' .cur_page').text(page);
}
