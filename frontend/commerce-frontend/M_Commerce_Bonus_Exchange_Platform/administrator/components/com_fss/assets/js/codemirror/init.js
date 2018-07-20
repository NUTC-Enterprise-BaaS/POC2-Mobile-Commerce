var fss_cm_editors = new Array();

jQuery(document).ready(function () {
    jQuery('.fss_codemirror_editor').each(function () {
        fss_init_cm(jQuery(this).attr('id'));
    });
});


function fss_init_cm(elem_id) {
    var elem = jQuery("#" + elem_id)[0];

    if (jQuery(elem).hasClass('fss_codemirror_editor')) {

        if (!jQuery(elem).is(':visible')) {
            setTimeout("fss_init_cm('" + elem_id + "');", 250);
            return;
        }

        var readonly = false;
        if (jQuery(elem).attr('readonly') !== undefined) readonly = true;

        var codetype = jQuery(elem).attr('codetype');
        fss_cm_editors[jQuery(elem).attr('id')] = CodeMirror.fromTextArea(elem, {
            mode: codetype,
            lineNumbers: true,
            viewportMargin: Infinity,
            lineWrapping: true,
            tabSize: 4,
            indentUnit: 4,
            readOnly: readonly
        });

        jQuery(elem).removeClass('fss_codemirror_editor');
    }
}
