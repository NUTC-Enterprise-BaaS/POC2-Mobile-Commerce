var gids;

function jq(myid) {
    return "#" + myid.replace(/(:|\.|\[|\])/g, "\\$1");
}

function get_gid_value(gid, field, group) {
    var el = get_gid_el(gid, field, group);
    var value = el.val();

    if (value == "") {
        value = -1;
        if (el.parent().parent().find('td span.label-success').length > 0)
            value = 1;
    }

    return value;
}

function get_gid_neg_value(gid, field, group) {
    var el = get_gid_el(gid, field, group);
    var value = el.val();

    if (value == "") {
        value = -1;
        if (el.parent().parent().find('td span.label-important').length > 0)
            value = 1;
    }

    return value;
}

function get_gid_table(gid, field, group) {
    return get_gid_el(gid, field, group).parent().parent().parent();
}

function get_gid_el(gid, field, group) {
    if (typeof (group) == "undefined") group = 'jform_rules_fss';
    return jQuery(jq(group + '.' + field + '_' + gid));
}

function hide_block(gid, id, message) {
    jQuery('#' + id + '-' + gid + ' table').hide();
    jQuery('#' + id + '-' + gid + ' div').remove();
    jQuery('#' + id + '-' + gid).append("<div class='alert'>" + message + "</div>");
    jQuery('#' + id + '-' + gid + '-btn').hide();
}

function show_block(gid, id) {
    jQuery('#' + id + '-' + gid + '-btn').show();
    jQuery('#' + id + '-' + gid + ' table').show();
    jQuery('#' + id + '-' + gid + ' div').remove();
}


jQuery(document).ready(function () {
    gids = new Array();

    jQuery('#tab_support_admins-sliders .tab-content > div').each(function () {
        var id = jQuery(this).attr('id').replace("tab_support_admin-", "");
        gids.push(id);
    });

    jQuery('select').removeClass('input-small');
    jQuery('select').addClass('input-large');

    for (i = 0 ; i < gids.length ; i++)
    {
        var gid = gids[i];

        init_dont_assign(gid);
        init_labels(gid)

        init_view_assign_change(gid);

        init_ticket_perms(gid, '');
        init_ticket_perms(gid, '_cc');
        init_ticket_perms(gid, '_other');
        init_ticket_perms(gid, '_una');

        init_misc_perms(gid);

        update_admin_role(gid);
        update_perm_set(gid);

        jQuery(jq('jform_rules_fss.handler_' + gid)).change(function () {
            var id = jQuery(this).attr('id').replace('jform_rules_fss.handler_', '');
            update_admin_role(id);
            update_perm_set(id);
        });

        jQuery(jq('jform_rules_fss.handler.assign.separate_' + gid)).change(function () {
            var id = jQuery(this).attr('id').replace('jform_rules_fss.handler.assign.separate_', '');
            update_perm_set(id);
        });

        jQuery(jq('jform_rules_ticket_fss.ticket_admin.restrict_' + gid)).change(function () {
            var id = jQuery(this).attr('id').replace('jform_rules_ticket_fss.ticket_admin.restrict_', '');
            update_ticket_perms(id, '');
        });

        jQuery(jq('jform_rules_ticket_cc_fss.ticket_admin_cc.restrict_' + gid)).change(function () {
            var id = jQuery(this).attr('id').replace('jform_rules_ticket_cc_fss.ticket_admin_cc.restrict_', '');
            update_ticket_perms(id, '_cc');
        });

        jQuery(jq('jform_rules_ticket_other_fss.ticket_admin_other.restrict_' + gid)).change(function () {
            var id = jQuery(this).attr('id').replace('jform_rules_ticket_other_fss.ticket_admin_other.restrict_', '');
            update_ticket_perms(id, '_other');
        });

        jQuery(jq('jform_rules_ticket_una_fss.ticket_admin_una.restrict_' + gid)).change(function () {
            var id = jQuery(this).attr('id').replace('jform_rules_ticket_una_fss.ticket_admin_una.restrict_', '');
            update_ticket_perms(id, '_una');
        });

        jQuery(jq('jform_rules_misc_fss.misc_admin.restrict_' + gid)).change(function () {
            var id = jQuery(this).attr('id').replace('jform_rules_misc_fss.misc_admin.restrict_', '');
            update_misc_perms(id);
        });
    }

    jQuery('#support_ticket h4').remove();

    setTimeout("left_tab_events()", 500);

    jQuery('#model-backdrop').click(function () {
        close_modals();
    })
});

var tab_click_break = false;
function left_tab_events()
{
    jQuery('div.tabs-left li').click(function (ev) {
        if (tab_click_break)
            return;

        var id = jQuery(this).find('a').attr('href');
        id = id.split('-')[1];

        tab_click_break = true;

        jQuery('div.tabs-left a').each(function () {
            var gid = jQuery(this).attr('href');
            gid = gid.split('-')[1];

            if (gid == id)
            {
                jQuery(this).click();
            }
        });

        tab_click_break = false;
    });
}

function update_admin_role(gid)
{
    var value = get_gid_value(gid, 'handler');
    var table = get_gid_table(gid, 'handler');

    if (value == 1)
    {
        table.find('tr').each(function () {
            jQuery(this).show();
        });

        show_block(gid, 'tab_support_admin_misc');
        show_block(gid, 'tab_support_admin_ticket');
        show_block(gid, 'tab_support_admin_ticket_cc');
        show_block(gid, 'tab_support_admin_ticket_other');
        show_block(gid, 'tab_support_admin_ticket_una');
        
    } else {
        var first = true;
        table.find('tr').each(function () {
            if (!first)
            {
                jQuery(this).hide();
            }
            first = false;
        });

        hide_block(gid, 'tab_support_admin_misc', "Not in use, this group cannot manage tickets");
        hide_block(gid, 'tab_support_admin_ticket', "Not in use, this group cannot manage tickets");
        hide_block(gid, 'tab_support_admin_ticket_cc', "Not in use, this group cannot manage tickets");
        hide_block(gid, 'tab_support_admin_ticket_other', "Not in use, this group cannot manage tickets");
        hide_block(gid, 'tab_support_admin_ticket_una', "Not in use, this group cannot manage tickets");

    }
}

function init_dont_assign(gid) {
    var value = get_gid_value(gid, 'handler.dontassign');
    var table = get_gid_table(gid, 'handler.dontassign');
    var el = get_gid_el(gid, 'handler.dontassign');
    el.parent().parent().find('label').html("Auto Assign Tickets");
    el.find('option[value="1"]').html("Dont Assign Tickets");
    el.find('option[value="0"]').html("Assigned Tickets");

    var label = el.parent().parent().find('span');
    
    if (label.hasClass('label-important'))
    {
        label.html("Assigned Tickets");
        label.removeClass('label-important');
        label.addClass('label-success');
    } else {
        label.html("Dont Assign Tickets");
        label.removeClass('label-success');
        label.addClass('label-important');
    }

    el.parent().parent().prev().find('div.alert').hide();
}

function change_labels(gid, field, yeslabel, nolabel, group)
{
    var value = get_gid_value(gid, field, group);
    var el = get_gid_el(gid, field, group);
    el.find('option[value="1"]').html(yeslabel);
    el.find('option[value="0"]').html(nolabel);

    var label = el.parent().parent().find('span');

    if (label.hasClass('label-important')) {
        label.html(nolabel);
    } else {
        label.html(yeslabel);
    }
}

function change_name(gid, field, name)
{
    var el = get_gid_el(gid, field);
    el.parent().parent().find('label').html(name);

}

function init_labels(gid) {
    change_labels(gid, 'handler.assign.separate', "Separate Permissions", "No");
    change_labels(gid, 'handler.view.products', "All Products", "Restricted Products");
    change_labels(gid, 'handler.view.departments', "All Departments", "Restricted Departments");
    change_labels(gid, 'handler.view.categories', "All Categories", "Restricted Categories");

    change_labels(gid, 'handler.assign.products', "All Products", "Restricted Products");
    change_labels(gid, 'handler.assign.departments', "All Departments", "Restricted Departments");
    change_labels(gid, 'handler.assign.categories', "All Categories", "Restricted Categories");
}

function update_perm_set(gid) {
    var value = get_gid_value(gid, 'handler.assign.separate');
    var table = get_gid_table(gid, 'handler.assign.separate');

    if (value == 1) {
        table.find('tr').slice(-4).show();
        change_name(gid, 'handler.view.products', 'View tickets for all products');
        change_name(gid, 'handler.view.departments', 'View tickets for all departments');
        change_name(gid, 'handler.view.categories', 'View tickets for all categories');
    } else {
        table.find('tr').slice(-4).hide();
        change_name(gid, 'handler.view.products', 'Manage tickets for all products');
        change_name(gid, 'handler.view.departments', 'Manage tickets for all departments');
        change_name(gid, 'handler.view.categories', 'Manage tickets for all categories');
    }

    update_viewassign_set(gid, 'view', 'products', 'perm_vp');
    update_viewassign_set(gid, 'view', 'departments', 'perm_vd');
    update_viewassign_set(gid, 'view', 'categories', 'perm_vc');
    update_viewassign_set(gid, 'assign', 'products', 'perm_ap');
    update_viewassign_set(gid, 'assign', 'departments', 'perm_ad');
    update_viewassign_set(gid, 'assign', 'categories', 'perm_ac');

}

function init_view_assign_change(gid) {
    update_viewassign_set(gid, 'view', 'products', 'perm_vp');
    update_viewassign_set(gid, 'view', 'departments', 'perm_vd');
    update_viewassign_set(gid, 'view', 'categories', 'perm_vc');
    update_viewassign_set(gid, 'assign', 'products', 'perm_ap');
    update_viewassign_set(gid, 'assign', 'departments', 'perm_ad');
    update_viewassign_set(gid, 'assign', 'categories', 'perm_ac');

    jQuery(jq('jform_rules_fss.handler.view.products_' + gid)).change(function () {
        var id = jQuery(this).attr('id').replace('jform_rules_fss.handler.view.products_', '');
        update_viewassign_set(gid, 'view', 'products', 'perm_vp');
    });
    jQuery(jq('jform_rules_fss.handler.view.departments_' + gid)).change(function () {
        var id = jQuery(this).attr('id').replace('jform_rules_fss.handler.view.departments_', '');
        update_viewassign_set(gid, 'view', 'departments', 'perm_vd');
    });
    jQuery(jq('jform_rules_fss.handler.view.categories_' + gid)).change(function () {
        var id = jQuery(this).attr('id').replace('jform_rules_fss.handler.view.categories_', '');
        update_viewassign_set(gid, 'view', 'categories', 'perm_vc');
    });
    jQuery(jq('jform_rules_fss.handler.assign.products_' + gid)).change(function () {
        var id = jQuery(this).attr('id').replace('jform_rules_fss.handler.assign.products_', '');
        update_viewassign_set(gid, 'assign', 'products', 'perm_ap');
    });
    jQuery(jq('jform_rules_fss.handler.assign.departments_' + gid)).change(function () {
        var id = jQuery(this).attr('id').replace('jform_rules_fss.handler.assign.departments_', '');
        update_viewassign_set(gid, 'assign', 'departments', 'perm_ad');
    });
    jQuery(jq('jform_rules_fss.handler.assign.categories_' + gid)).change(function () {
        var id = jQuery(this).attr('id').replace('jform_rules_fss.handler.assign.categories_', '');
        update_viewassign_set(gid, 'assign', 'categories', 'perm_ac');
    });

    jQuery(jq('jform_rules_fss.handler.view.products_' + gid)).parent().append("<a href='#' id='perm_vp-" + gid + "-btn' class='btn btn-default' onclick='show_pdc(" + gid + ", \"vp\");return false;'>Edit Products</a>");
    jQuery(jq('jform_rules_fss.handler.view.departments_' + gid)).parent().append("<a href='#' id='perm_vd-" + gid + "-btn' class='btn btn-default' onclick='show_pdc(" + gid + ", \"vd\");return false;'>Edit Departments</a>");
    jQuery(jq('jform_rules_fss.handler.view.categories_' + gid)).parent().append("<a href='#' id='perm_vc-" + gid + "-btn' class='btn btn-default' onclick='show_pdc(" + gid + ", \"vc\");return false;'>Edit Categories</a>");

    jQuery(jq('jform_rules_fss.handler.assign.products_' + gid)).parent().append("<a href='#' id='perm_ap-" + gid + "-btn' class='btn btn-default' onclick='show_pdc(" + gid + ", \"ap\");return false;'>Edit Products</a>");
    jQuery(jq('jform_rules_fss.handler.assign.departments_' + gid)).parent().append("<a href='#' id='perm_ad-" + gid + "-btn' class='btn btn-default' onclick='show_pdc(" + gid + ", \"ad\");return false;'>Edit Departments</a>");
    jQuery(jq('jform_rules_fss.handler.assign.categories_' + gid)).parent().append("<a href='#' id='perm_ac-" + gid + "-btn' class='btn btn-default' onclick='show_pdc(" + gid + ", \"ac\");return false;'>Edit Categories</a>");

    jQuery('#support_view').find('ul.nav-tabs').remove();
    jQuery('#support_assign').find('ul.nav-tabs').remove();
    jQuery('#support_tabs').find('a[href="#support_view"]').remove();
    jQuery('#support_tabs').find('a[href="#support_assign"]').remove();
}

function update_viewassign_set(gid, view, set, id)
{
    var value = get_gid_value(gid, 'handler.' + view + '.' + set);
    var tickets = get_gid_value(gid, 'handler');
    var separate = get_gid_value(gid, 'handler.assign.separate');

    if (tickets == 1) {
        if (view == "assign" && separate < 1) {
            hide_block(gid, id, "Not in use, this group has combined permissions enabled");
        } else if (value == 1) {
            hide_block(gid, id, "Not in use, this group is set to use all " + set);
        } else {
            show_block(gid, id);
        }
    } else {
        hide_block(gid, id, "Not in use, this group cannot manage tickets");
    }
}

function init_ticket_perms(gid, set) {
    var value = get_gid_value(gid, 'ticket_admin' + set + '.restrict', 'jform_rules_ticket' + set + '_fss');
    var table = get_gid_table(gid, 'ticket_admin' + set + '.restrict', 'jform_rules_ticket' + set + '_fss');
    var el = get_gid_el(gid, 'ticket_admin' + set + '.restrict', 'jform_rules_ticket' + set + '_fss');

    change_labels(gid, 'ticket_admin' + set + '.restrict', "Actions restricted", "All actions allowed", 'jform_rules_ticket' + set + '_fss');

    var label = el.parent().parent().find('span');

    if (label.hasClass('label-important')) {
        label.html("All actions allowed");
        label.removeClass('label-important');
        label.addClass('label-success');
    } else {
        label.html("Actions restricted");
        label.removeClass('label-success');
        label.addClass('label-important');
    }

    update_ticket_perms(gid, set);
}

function init_misc_perms(gid) {
    var value = get_gid_value(gid, 'misc_admin.restrict', 'jform_rules_misc_fss');
    var table = get_gid_table(gid, 'misc_admin.restrict', 'jform_rules_misc_fss');
    var el = get_gid_el(gid, 'misc_admin.restrict', 'jform_rules_misc_fss');

    change_labels(gid, 'misc_admin.restrict', "Actions restricted", "All actions allowed", 'jform_rules_misc_fss');

    var label = el.parent().parent().find('span');

    if (label.hasClass('label-important')) {
        label.html("All actions allowed");
        label.removeClass('label-important');
        label.addClass('label-success');
    } else {
        label.html("Actions restricted");
        label.removeClass('label-success');
        label.addClass('label-important');
    }

    update_misc_perms(gid);
}

function update_ticket_perms(gid, set)
{
    var value = get_gid_neg_value(gid, 'ticket_admin' + set + '.restrict', 'jform_rules_ticket' + set + '_fss');
    var table = get_gid_table(gid, 'ticket_admin' + set + '.restrict', 'jform_rules_ticket' + set + '_fss');

    if (value == 1) {
        table.find('tr').each(function () {
            jQuery(this).show();
        });
    } else {
        var first = true;
        table.find('tr').each(function () {
            if (!first) {
                jQuery(this).hide();
            }
            first = false;
        });
    }
}

function update_misc_perms(gid) {
    var value = get_gid_neg_value(gid, 'misc_admin.restrict', 'jform_rules_misc_fss');
    var table = get_gid_table(gid, 'misc_admin.restrict', 'jform_rules_misc_fss');

    if (value == 1) {
        table.find('tr').each(function () {
            jQuery(this).show();
        });
    } else {
        var first = true;
        table.find('tr').each(function () {
            if (!first) {
                jQuery(this).hide();
            }
            first = false;
        });
    }
}

function show_pdc(gid, set) {
    var div = jQuery('#perm_' + set + '-' + gid);
    div.addClass('modal');

    if (div.find('.modal-body').length < 1)
    {
        var wh = jQuery(window).height();
        div.append('<div class="modal-header fss_main"><button class="close" onclick="close_modals();return false;">&times;</button><h3>Ticket Admin - Canned Replies</h3></div>');
        div.append('<div class="modal-body" style="max-height: ' + wh + 'px;"></div>');
        div.append('<div class="modal-footer"><a href="#" class="btn btn-default" onclick="close_modals();return false;">Close</a></div>');
        div.find('.modal-body').append(div.find('table'));
        div.css('width', '750px');
    }

    jQuery('#adminForm').append(div);
    jQuery('#model-backdrop').show();
    div.show();
}

function close_modals()
{
    jQuery('.tab-pane.modal').hide();
    jQuery('.tab-pane.modal').removeClass('modal');
    jQuery('#model-backdrop').hide();
}