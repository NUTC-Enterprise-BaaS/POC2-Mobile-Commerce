
function initVisual() {
    cminit('display_head', 'htmlmixed');
    cminit('display_foot', 'htmlmixed');
    cminit('display_h1', 'htmlmixed');
    cminit('display_h2', 'htmlmixed');
    cminit('display_h3', 'htmlmixed');
    cminit('display_style', 'css');
    cminit('display_popup_style', 'css');
    cminit('bootstrap_variables', 'css');
    cminit('display_module_style', 'css')

    cminit('popup_css', 'htmlmixed');
    cminit('popup_js', 'htmlmixed');
}

function initTComments() {
    setup_comments('comments_general');
    setup_comments('comments_announce');
    setup_comments('comments_kb');
    setup_comments('comments_test');
    setup_comments('comments_testmod');
}

function initTSupport() {
    showhide_customize();
}

function initTAnnounce() {
    setup_comments('announce');
    setup_comments('announcemod');
    setup_comments('announcesingle');
}

function cminit(element_name, editmode) {

    if (CodeMirror.editors[element_name])
        return;

    element = jQuery('#' + element_name);

    CodeMirror.editors[element_name] = CodeMirror.fromTextArea(element[0], {
        mode: editmode,
        lineNumbers: true,
        viewportMargin: Infinity,
        lineWrapping: true,
        tabSize: 2
    });
}

function initSection(hash)
{
    if (hash == 'visual')
        initVisual();

    if (hash == 'tcomments')
        initTComments();

    if (hash == 'tsupport')
        initTSupport();

    if (hash == 'tannounce')
        initTAnnounce();
}

jQuery(document).ready(function () {

    if (typeof (CodeMirror) != "undefined") {
        if (typeof (CodeMirror.editors) == "undefined")
            CodeMirror.editors = new Array();
    }

    jQuery('.nav-tabs li').click(function (ev) {
        ev.preventDefault();
        jQuery(this).find('a').tab('show');

        var href = jQuery(this).find('a').attr('href').replace('#','');
        initSection(href);
        location.hash = '#_' + href;
    });

    if (location.hash) {
        var hash = location.hash.replace('#', '').replace('_','');
        jQuery('a[href="#' + hash + '"]').click();
        initSection(hash);
    } else {
        // init comments tab if we are in templates mode
        if (jQuery('#tcomments').length > 0)
            initTComments();
    }

    // Date and time testing for settings / support
    jQuery('#test_date_formats').click(function (ev) {
        ev.preventDefault();

        var url = fss_settings_url + '&what=testdates';

        url += '&date_dt_short=' + encodeURIComponent(jQuery('#date_dt_short').val());
        url += '&date_dt_long=' + encodeURIComponent(jQuery('#date_dt_long').val());
        url += '&date_d_short=' + encodeURIComponent(jQuery('#date_d_short').val());
        url += '&date_d_long=' + encodeURIComponent(jQuery('#date_d_long').val());
        url += '&offset=' + encodeURIComponent(jQuery('#timezone_offset').val());

        jQuery.get(url, function (data) {
            var result = jQuery.parseJSON(data);
            jQuery('#test_date_dt_short').html("Result : " + result.date_dt_short);
            jQuery('#test_date_dt_long').html("Result : " + result.date_dt_long);
            jQuery('#test_date_d_short').html("Result : " + result.date_d_short);
            jQuery('#test_date_d_long').html("Result : " + result.date_d_long);
            jQuery('#test_timezone_offset').html("Result : " + result.timezone_offset);
        });
    });

    jQuery('#customize_button').click(function (e) {
        e.preventDefault();
        slt_customize();
    });

    jQuery('#user_customize_button').click(function (e) {
        e.preventDefault();
        slt_user_customize();
    });

    jQuery('#send_test_email').click(function (e) {
        e.preventDefault();
        send_test_email();
    });
});


function testreference() {
    jQuery('#testref').innerHTML = "Please Wait";
    var format = jQuery('#support_reference').val();
    var url = fss_settings_url + '&what=testref&ref=' + encodeURIComponent(format);

    jQuery('#testref').load(url);
}

function setup_comments(cset) {
    jQuery('#' + cset + '_reset').click(function (ev) {
        ev.stopPropagation();
        ev.preventDefault();
        if (confirm("Are you sure you wish to reset this custom template")) {
            jQuery('#' + cset).val(jQuery('#' + cset + '_default').val());
            CodeMirror.editors[cset].setValue(jQuery('#' + cset + '_default').val());
        }
    });

    jQuery('#' + cset + '_use_custom').change(function (ev) {
        showhide_comments(cset);
    });
    showhide_comments(cset);
}

function showhide_comments(cset) {
    var value = jQuery('#' + cset + '_use_custom').attr('checked');
    if (value == "checked") {
        jQuery('#' + cset + '_row').css('display', 'table-row');

        if (jQuery('#' + cset).val() == "")
            jQuery('#' + cset).val(jQuery('#' + cset + '_default').val());

        cminit(cset, 'htmlmixed');
    } else {
        jQuery('#' + cset + '_row').css('display', 'none');
    }
}

function showhide_customize() {
    var current = jQuery('#support_list_template').val();
    if (current == "custom") {
        jQuery('#customize_button').hide();
        jQuery('#customtemplaterow').show();

        if (!CodeMirror.editors['support_list_head']) {
            cminit('support_list_head', 'htmlmixed');
            CodeMirror.editors['support_list_head'].setValue(jQuery('#support_list_head').val());
            cminit('support_list_row', 'htmlmixed');
            CodeMirror.editors['support_list_row'].setValue(jQuery('#support_list_row').val());
        }

    } else {
        jQuery('#customize_button').show();
        jQuery('#customtemplaterow').hide();
    }

    var current = jQuery('#support_user_template').val();
    if (current == "usercustom") {
        jQuery('#user_customize_button').hide();
        jQuery('#customusertemplaterow').show();

        if (!CodeMirror.editors['support_user_head']) {
            cminit('support_user_head', 'htmlmixed');
            CodeMirror.editors['support_user_head'].setValue(jQuery('#support_user_head').val());
            cminit('support_user_row', 'htmlmixed');
            CodeMirror.editors['support_user_row'].setValue(jQuery('#support_user_row').val());
        }

    } else {
        jQuery('#user_customize_button').show();
        jQuery('#customusertemplaterow').hide();
    }
}

function slt_customize() {
    var current = jQuery('#support_list_template').val();
    if (current == "custom")
        return;

    if (!confirm("This will over write any existing custom template! Are you sure?" + current))
        return;

    var url = fss_settings_url + '&what=customtemplate&name=' + current;

    jQuery.get(url, function (response) {
        jsonObj = JSON.decode(response);
        jQuery('#support_list_head').val(jsonObj.head);
        jQuery('#support_list_row').val(jsonObj.row);
        jQuery('#support_list_template').val("custom");
        showhide_customize();
        CodeMirror.editors['support_list_head'].setValue(jQuery('#support_list_head').val());
        CodeMirror.editors['support_list_row'].setValue(jQuery('#support_list_row').val());
    });

    return false;
}

function slt_user_customize() {
    var current = jQuery('#support_user_template').val();
    if (current == "usercustom")
        return;

    if (!confirm("This will over write any existing custom template! Are you sure?"))
        return;

    var url = fss_settings_url + '&what=customtemplate&name=' + current;

    jQuery.get(url, function (response) {
        jsonObj = JSON.decode(response);
        jQuery('#support_user_head').val(jsonObj.head);
        jQuery('#support_user_row').val(jsonObj.row);
        jQuery('#support_user_template').val("usercustom");
        showhide_customize();
        CodeMirror.editors['support_user_head'].setValue(jQuery('#support_user_head').val());
        CodeMirror.editors['support_user_row'].setValue(jQuery('#support_user_row').val());
    });

    return false;
}

function slt_preview() {
    jQuery('#list_template').val(jQuery('#support_list_template').val());
    jQuery('#list_head').val(jQuery('#support_list_head').val());
    jQuery('#list_row').val(jQuery('#support_list_row').val());
    jQuery('#adminForm2').submit();
}

function send_test_email() {

    var email = jQuery('#email_test_address').val();
    if (!email) {
        alert("You must enter an email to test!");
        return;
    }

    jQuery('#email_test_result').html("Sending...");
    var url = fss_settings_url + '&what=send_test_email&&tmpl=component&email=' + email;
    jQuery('#email_test_result').load(url);
}