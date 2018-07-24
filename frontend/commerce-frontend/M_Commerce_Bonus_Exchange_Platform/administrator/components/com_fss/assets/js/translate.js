
var translate_html = false;

function doTranlsateLoaded() {
    // load in existing translate data into fields
    var fr = jQuery('div.tcontent > iframe').contents();
    // load in existing values

    for (field in to_translate) {
        if (to_translate[field].type == "html") {
            translate_html = true;
            var val = jQuery('[name="' + field + '"]').html();
            fr.find('#current-' + field).html("'" + val + "'");
        } else {
            var val = jQuery('#' + field).val();
            fr.find('#current-' + field).html("'" + val + "'");
        }
    }

    try
    {
        var data = jQuery.parseJSON(jQuery('#translation').val());

        for (field in data) {
            for (lang in data[field]) {
                var value = data[field][lang];
                fr.find('#tran-' + field + '-' + lang).val(value);
            }
        }

        /*if (translate_html) {
            jQuery('.tbox').css('position', 'absolute');
            jQuery('.tinner').css('height', 'auto');
            jQuery('.tbox').css('top', '100px');
            jQuery('.tcontent').css('overflow', 'auto');
            window.scrollTo(0, 0);
        }*/
    } catch (err) {

    }
}

function saveTranslated() {

    var fr = jQuery('div.tcontent > iframe').contents();

    if (translate_html) {

        /*for (field in to_translate) {
            if (to_translate[field].type == "html") {
                for (lang in tr_langs) {
                    tinyMCE.get('tran-' + field + '-' + lang).save();
                }
                
            }
        }*/
    }

    var target = {};

    for (field in to_translate) {
        for (lang in tr_langs) {
            var val = fr.find('#tran-' + field + '-' + lang).val();
            if (val) {
                if (!target[field]) target[field] = {};
                target[field][lang] = val;
            }
        }
    }

    jQuery('#translation').val(JSON.stringify(target));

    TINY.box.hide();

    displayTranslations();
}

function displayTranslations() {

    try {
        var data = jQuery.parseJSON(jQuery('#translation').val());

        for (field in to_translate) {
            jQuery('#trprev_' + field).html("");
        }

        for (field in data) {
            var html = "";
            for (lang in data[field]) {
                var value = data[field][lang];
                html += "<b>" + tr_langs[lang] + "</b>:";
                html += value;
                html += "<br />";
            }
            jQuery('#trprev_' + field).html(html);
        }
    } catch (err) {

    }
}