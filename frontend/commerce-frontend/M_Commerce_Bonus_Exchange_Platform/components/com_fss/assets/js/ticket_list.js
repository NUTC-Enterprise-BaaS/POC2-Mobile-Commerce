
jQuery(document).ready(function () {
    jQuery('#advanced_username').keyup(function () {
        var val = jQuery('#advanced_username').val();
        if (val.length < 3) {
            jQuery('#user_select').removeClass('open');
            return;
        }

        if (!jQuery('#user_select').hasClass("open")) {
            jQuery('#user_select_list').html("<li><a href='#' onclick='return false;'>Searching</a></li>");
            jQuery('#user_select').addClass('open');
        }

        var items = jQuery('#user_select_list li');


        var url = jQuery('#users_search_url').text();
        url += "&q=" + encodeURIComponent(val);

        jQuery.get(url).done(function (data) {
            if (data.length < 1) {
                jQuery('#user_select_list').html("<li><a href='#' onclick='return false;'>None Found</a></li>");
            } else {
                jQuery('#user_select_list').html("");
                for (username in data) {
                    var name = data[username];
                    jQuery('#user_select_list').append("<li><a href='#' onclick='return setusername(\"" + username + "\");'>" + username + " - " + name + "</a></li>");
                }
            }
        });
    });

    jQuery(document).click(function () {
        jQuery('#user_select').removeClass('open');
    });

    jQuery('#batch_select_all').click(function (ev) {
        ev.preventDefault();
        jQuery('.ticket_cb').attr('checked', 'checked');
    });

    jQuery('#batch_select_none').click(function (ev) {
        ev.preventDefault();
        jQuery('.ticket_cb').removeAttr('checked');
    });

    jQuery('#batch_select_invert').click(function (ev) {
        ev.preventDefault();
        jQuery('.ticket_cb').each(function () {
            if (jQuery(this).attr('checked') !== undefined) {
                jQuery(this).removeAttr('checked');
            } else {
                jQuery(this).attr('checked', 'checked');
            }
        });
    });
    // if we are advanced search, move the ordering box
    if (!jQuery('#basicsearch').is(':visible'))
        jQuery('#advanced_ordering').append(jQuery('#ordering'));
});

var prev_batch_mode = -99;

function toggleBatch(print) {

    if (print == prev_batch_mode) {
        // hide form
        prev_batch_mode = -99;
        jQuery('.ticket_cb').hide();
        jQuery('#batch_form').hide();
    } else if (print > 0) {

        jQuery('.ticket_cb').show();
        jQuery('#batch_form').show();
        prev_batch_mode = print;

        if (print == 1) {
            // show batch
            jQuery('.batch_print_hide').show();
            jQuery('.batch_action_hide').hide();
        } else {
            // show print
            jQuery('.batch_print_hide').hide();
            jQuery('.batch_action_hide').show();
        }
    }

    if (jQuery('#batch_form').is(":visible")) {
        // we made batch visible, clear any onclick handers for the table rows!
        jQuery('#fss_ticket_list table tr').removeAttr('onclick');
        jQuery('#fss_ticket_list table tr').css('cursor', 'default');
    }
}

function processBatch() {
    jQuery('#batch').val('batch.process');
    jQuery('#fssForm').submit();
}


function setusername(name) {
    jQuery('#advanced_username').val(name);
    jQuery('#user_select').removeClass('open');
    return false;
}

function showadvsearch() {
    jQuery('.advsearch').show();
    jQuery('#basicsearch').hide();
    jQuery('#searchtype').val('advanced');

    // move ordering dropdown to adv search pos
    jQuery('#advanced_ordering').append(jQuery('#ordering'));
    jQuery('#advanced_ordering').append(jQuery('#ordering_chzn'));
    // jQuery('#ordering').show();
}

function showbasicsearch() {
    jQuery('#basicsearch').show();
    jQuery('.advsearch').hide();
    jQuery('#searchtype').val('basic');

    // move ordering dropdown to basic search pos
    jQuery('#basic_ordering').append(jQuery('#ordering'));
    jQuery('#basic_ordering').append(jQuery('#ordering_chzn'));
    //jQuery('#ordering_chzn').hide();
    //jQuery('#ordering').show();
}

function resetbasic() {
    jQuery('#basic_search').val('');
    jQuery('#tags').val('');
    jQuery('#fssForm').submit();
}

function tag_add(tagname) {
    tagsshown = 1;
    jQuery('#tags').val(jQuery('#tags').val() + ';' + tagname);
    if (jQuery('#fss_what').val() == "")
        jQuery('#fss_what').val("search");
    jQuery('#fssForm').submit();
}
function tag_remove(tagname) {
    jQuery('#tags').val(jQuery('#tags').val().replace(tagname, ''));
    jQuery('#tags').val(jQuery('#tags').val().replace(";;", ""));
    jQuery('#fssForm').submit();
}

jQuery(document).ready(function () {
    myCalendar = new dhtmlXCalendarObject(["advanced_date_from", "advanced_date_to"]);
    myCalendar.setSkin("omega");
    myCalendar.hideTime();
    myCalendar.loadUserLanguage(fss_calendar_locale);
});

function fss_refresh_tickets() {

    if (jQuery('#fss_showing_search').length > 0) {
        return fss_refresh_results();
    }

    var url = jQuery(location).attr('href');
    var sort = jQuery('#ordering').val();
    if (url.indexOf("#") > 0)
        url = url.split("#")[0];

    if (url.indexOf("?") > 0) {
        url += "&";
    } else {
        url += "?";
    }
    url += "tmpl=component&refresh=1&ordering=" + encodeURIComponent(sort);

    jQuery.get(url, function (result) {
        if (!jQuery('#batch_form').is(":visible")) {

            jQuery('#fss_ticket_list').html(result.tickets);

            for (status in result.count) {
                var count = result.count[status];

                jQuery('.ticket_count_' + status).html(count);
            }
        }
    });
}

function batch_print(el) {
    var a = jQuery(el);
    var ids = new Array();
    jQuery('.ticket_cb').each(function () {
        if (jQuery(this).attr('checked') !== undefined) {
            var id = jQuery(this).attr('id').replace("ticket_cb_", "");
            ids.push(id);
        }
    });

    ids = ids.join(":");

    var href = a.attr('href');
    if (href.indexOf("&ticketids") > 0)
        href = href.substr(0, href.indexOf("&ticketids"));

    href = href + "&ticketids=" + ids;

    a.attr('href', href);
}

function fssAdminOrder(ordering) {
    var deforder = "asc";
    if (ordering.indexOf(".asc") > 0) {
        ordering = ordering.replace(".asc", "");
    } else if (ordering.indexOf(".desc") > 0) {
        ordering = ordering.replace(".desc", "");
        deforder = "desc";
    }

    var current = jQuery('#ordering').val();
    if (!current) current = "";

    var curorder = "asc";
    if (current.indexOf(".asc") > 0) {
        current = current.replace(".asc", "");
    } else if (current.indexOf(".desc") > 0) {
        current = current.replace(".desc", "");
        curorder = "desc";
    }

    if (current != ordering) {
        // different field
        jQuery('#ordering').val(ordering + "." + deforder);
    } else {
        // change direction
        if (curorder == "asc") {
            jQuery('#ordering').val(ordering + ".desc");
        } else {
            jQuery('#ordering').val(ordering + ".asc");
        }
    }

    fss_refresh_tickets();
}

function fss_refresh_results() {
    jQuery('#fssForm').submit();
}