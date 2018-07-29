jQuery(document).ready(function () {
    setTimeout("init_sceditor()", 500);
});

function nl2br(str, is_xhtml) {
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

function init_sceditor() {

    if (typeof (sceditor_paste) == "undefined")
    {
        sceditor_paste = "raw";
    }

    jQuery("textarea.sceditor").each(function () {
        var rows = parseInt(jQuery(this).attr('rows'));
        if (isNaN(rows)) rows = 8;
        if (rows < 8) rows = 8;

        jQuery(this).attr('rows', rows + 8);

        var filter = true;
        if (sceditor_paste == "raw") filter = false;

        var editor = jQuery(this).sceditor({
            plugins: "bbcode",
            style: sceditor_style_root + "jquery.sceditor." + sceditor_style_type + ".css",
            emoticonsRoot: sceditor_emoticons_root,
            toolbarExclude: sceditor_toolbar_exclude,
            enablePasteFiltering: filter
        });
  
        jQuery('div.sceditor-container').addClass('sceditor-container-' + sceditor_style_type);

        jQuery(this).removeClass('sceditor');

        sceditor_clipboard();
    });
}

function sceditor_clipboard() {

    var ifd = jQuery('div.sceditor-container iframe')[0].contentWindow.document;

    var allow_image = false;
    var allow_text = false;

    if (sceditor_paste == "plaintext" || sceditor_paste == "plainimage")
        allow_text = "plain";

    if (sceditor_paste == "" || sceditor_paste == "raw")
        allow_text = "html";

    if (sceditor_paste == "plainimage" || sceditor_paste == "" || sceditor_paste == "raw")
        allow_image = true;

    try {
        ifd.querySelector("body[contenteditable]").addEventListener("paste", function (e) {

            try { // attempt to paste an image for chrome and ie
                var items = e.clipboardData.items;
                for (var i = 0; i < items.length; ++i) {

                    if (items[i].kind == 'file' && items[i].type.indexOf('image/') !== -1) // image
                    {

                        if (allow_image) { // image allowed
                            e.preventDefault();

                            var blob = items[i].getAsFile();

                            var reader = new window.FileReader();
                            reader.readAsDataURL(blob);
                            reader.onloadend = function () {
                                base64data = reader.result;
                                var html = "<img src='" + base64data + "' />";
                                ifd.execCommand("insertHTML", false, html);
                            }

                            return false;
                        } else { // no images allowed
                            e.preventDefault();
                            return false;
                        }
                    }
                }
            } catch (e) {

            }

            // text detected, see what to do!
            var text = e.clipboardData.getData("text/plain");
            if (text != "") {
                if (allow_text == false) // no text allowed
                {
                    e.preventDefault();
                    return false;

                } else if (allow_text == "plain") // plain text
                {
                    e.preventDefault();


                    text = nl2br(text);
                    ifd.execCommand("insertHTML", false, text);

                } else if (allow_text == "html") // normal paste
                {
                    return false;
                }
            }

            // if we are here, then most likley firefox with an image
            if (!allow_image) {
                e.preventDefault();
                return false;
            }

        });
    } catch (e) {

    }
}