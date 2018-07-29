var frames_ready = 0;
jQuery(document).ready(function () {
    jQuery('#popuptest_btn').attr('disabled', 'disabled');
    jQuery('#csstest_btn').attr('disabled', 'disabled');

    jQuery('.iframe_load_wait').load(function () {
        frames_ready++;
        if (frames_ready == 2)
            csstest_ready();
    });

    jQuery('#csstest_btn').click(function (ev) {
        ev.preventDefault();

        jQuery('#csstest_component').show();
        jQuery('#csstest_normal').show();

        try {
            console.log("Running CSS Tests");
            run_csstest();
            console.log("Running CSS Tests Done");
        } catch (e) {
            console.log("Running CSS Tests Failed");
        }

        try {
            console.log("Running Popup Tests");
            run_popuptest();
            console.log("Running Popup Tests Done");
        } catch (e) {
            console.log("Running Popup Tests Failed");
        }

        jQuery('#csstest_component').hide();
        jQuery('#csstest_normal').hide();

        console.log("Updating Box");
        tests_done();
    });
});
function csstest_ready() {
    jQuery('#popuptest_btn').removeAttr('disabled');
    jQuery('#csstest_btn').removeAttr('disabled');
}

function run_csstest() {

    console.log("Detect Colors");
    detect_colors('csstest_normal');

    try {
        if (selectorExists('csstest_normal', '.modal-footer')) {
            console.log("Bootstrap Modal Exists");
            jQuery('#bootstrap_modal').removeAttr("checked");
        } else {
            console.log("Bootstrap Modal Missing");
            jQuery('#bootstrap_modal').attr("checked", "checked");
        }
    } catch (e) {

    }

    try {
        if (selectorExists('csstest_normal', '.alert')) {
            // bootstrap exists, so call it partial!
            console.log("Bootstrap Partial");
            jQuery('#bootstrap_css').val('partial');
        } else {
            console.log("Bootstrap FSS Only");
            jQuery('#bootstrap_css').val('fssonly');
            //jQuery('#bootstrap_border').val('rgb(221, 221, 221)');
        }
    } catch (e) {

    }

    try {
        if (selectorExists('csstest_normal', '.jumbotron')) {
            console.log("Bootstrap v3 Template");
            jQuery('#bootstrap_v3').attr("checked", "checked");
        } else {
            console.log("Not Bootstrap v3 Template");
            jQuery('#bootstrap_v3').removeAttr("checked");
        }
    } catch (e) {

    }
    
    try {
        var contents = jQuery('#csstest_normal').contents();

        var gt = contents.find('#glyph-test');
        
        if (gt.height() < 10)
        {
            console.log("IcoMoon not included");
            jQuery('#bootstrap_icomoon').attr("checked", "checked");
        } else {
            console.log("IcoMoon included");
            jQuery('#bootstrap_icomoon').removeAttr("checked");
        }
    } catch (e) {

    }
}

function run_popuptest() {
    console.log("Getting Scripts");
    try {
        console.log("Getting Scripts");
        var com_scripts = get_scripts('csstest_component');
        var main_scripts = get_scripts('csstest_normal');
        var scripts = arrayDiff(com_scripts, main_scripts);
        jQuery('#popup_js_suggestions').html(scripts.join("\n"));
        jQuery('#popup_js_outer').show();
        console.log("Getting Scripts Done");
    } catch (e) {
        console.log("Getting Scripts Failed");
    }

    try {
        console.log("Getting CSS");
        var com_css = get_styles('csstest_component');
        var main_css = get_styles('csstest_normal');

        var css = arrayDiff(com_css, main_css);
        jQuery('#popup_css_suggestions').html(css.join("\n"));
        jQuery('#popup_css_outer').show();
        console.log("Getting CSS Done");
    } catch (e) {
        console.log("Getting Scripts Failed");
    }
}

function tests_done() {

    try {
        jQuery('#css_alert').removeClass('alert-danger');
        jQuery('#css_alert').addClass('alert-info');
        jQuery('#bootstrap_template').val(jQuery('#current_template').text());
        jQuery('#css_must').remove();
    } catch (e) {
    }

}

function get_scripts(frame) {
    var contents = jQuery('#' + frame).contents();

    var result = new Array();
    contents.find('head script').each(function () {
        var src = jQuery(this).attr('src');
        if (typeof src === "undefined")
            return;

        /*if (endsWith(src, "/js/mootools-core.js"))
            return;

        if (endsWith(src, "/js/mootools-more.js"))
            return;

        if (endsWith(src, "/js/core.js"))
            return;*/

        result.push(src);
    });

    return result;
}

function get_styles(frame) {
    var contents = jQuery('#' + frame).contents();

    var result = new Array();
    contents.find('head link[rel="stylesheet"]').each(function () {
        var href = jQuery(this).attr('href');

        if (typeof href === "undefined")
            return;

        if (strContains(href, "/css-compiled/"))
            return;

        result.push(href);
    });

    return result;
}

function detect_colors(frame) {
    var contents = jQuery('#' + frame).contents();

    var table = contents.find('#table_border');

    var border_col = table.css('border-right-color');

    jQuery('#bootstrap_border').val(border_col);

    var success_color = contents.find('#text-col-success').css('color');
    var warning_color = contents.find('#text-col-warning').css('color');

    if (success_color == warning_color) {
        jQuery('#bootstrap_textcolor').attr("checked", "checked");
    } else {
        jQuery('#bootstrap_textcolor').removeAttr("checked");
    }
}
function arrayDiff(a1, a2) {
    var o1 = {}, o2 = {}, diff = [], i, len, k;
    for (i = 0, len = a1.length; i < len; i++) { o1[a1[i]] = true; }
    for (i = 0, len = a2.length; i < len; i++) { o2[a2[i]] = true; }
    for (k in o1) { if (!(k in o2)) { diff.push(k); } }
    for (k in o2) { if (!(k in o1)) { diff.push(k); } }
    return diff;
}
function endsWith(str, suffix) {
    return str.indexOf(suffix, str.length - suffix.length) !== -1;
} 
function strContains(str, substr) {
    return str.indexOf(substr) !== -1;
}

function getDefinedCss(frame, s) {
    var contents = jQuery('#' + frame).contents()[0];
    if (!contents.styleSheets) return '';
    if (typeof s == 'string') s = RegExp('\\b' + s + '\\b', 'i'); // IE capitalizes html selectors 

    var A, S, DS = contents.styleSheets, n = DS.length, SA = [];
    while (n) {
        S = DS[--n];
        A = (S.rules) ? S.rules : S.cssRules;
        for (var i = 0, L = A.length; i < L; i++) {
            tem = A[i].selectorText ? [A[i].selectorText, A[i].style.cssText] : [A[i] + ''];
            if (s.test(tem[0])) SA[SA.length] = tem;
        }
    }
    return SA.join('\n\n');
}

function getAllSelectors(frame) {
    var contents = jQuery('#' + frame).contents()[0];
    var ret = [];
    for (var i = 0; i < contents.styleSheets.length; i++) {
        try {
            var rules = contents.styleSheets[i].rules || contents.styleSheets[i].cssRules;
            for (var x in rules) {
                if (typeof rules[x].selectorText == 'string') ret.push(rules[x].selectorText);
            }
        } catch (e) {
        }
    }
    return ret;
}


function selectorExists(frame, selector) {
    var selectors = getAllSelectors(frame);
    for (var i = 0; i < selectors.length; i++) {
        if (selectors[i] == selector) return true;
    }
    return false;
}

