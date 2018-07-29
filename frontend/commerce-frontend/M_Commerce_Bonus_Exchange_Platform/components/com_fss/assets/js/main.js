
jQuery(document).ready(function () {
    // test if original jQuery library is loaded
    try {
        if (jQuery.fn.fss_jquery_ok()) {
        }
    } catch (e) {
        if (typeof (fss_no_warn) == "undefined") {
            jQuery('.fss_main').first().prepend("<div class='alert alert-error'><h4>Freestyle Support Portal Error: jQuery issue detected</h4>Multiple copies of the jQuery library are being loaded on your site. This will prevent much of the component from working correctly. Please install the jQuery Easy Plugin to ensure that onle a single instance of the jQuery library is loaded to allow this component to work correctly.</div>");
        }
    }

    jQuery('.fss_main .hide').hide().removeClass('hide');

    init_elements();

    jQuery('#fss_modal_container').appendTo(document.body);
    jQuery('#fss_modal_container').attr("style", "");

    // fix hide event in bootstrap when mootools is loaded 
    if (typeof (MooTools) != "undefined") {
        (function ($) {
            $$('[data-toggle=collapse]').each(function (e) {
                if ($$(e.get('data-target')).length > 0) {
                    $$(e.get('data-target'))[0].hide = null;
                }
            });
        })(MooTools);
    }

    setTimeout("fix_joomla_art_mess();", 500);

    jQuery('.help-inline').each(function () {
        try {
            if (jQuery(this).text().trim() == "")
                jQuery(this).hide();
        } catch (e)
        {

        }
    })
});

function fix_joomla_art_mess() {
    jQuery('.fss_main .nav.nav-tabs a').unbind('click');
}

function init_elements() {

    jQuery('.fss_main .vert-center').each(function () {
        var elem_height = jQuery(this).outerHeight(true);
        var parent_height = jQuery(this).parent().height();
        var offset = parseInt((parent_height - elem_height) / 2);
        jQuery(this).css('top', offset + 'px');
    });

    jQuery('.fss_main .show_modal').click(function (ev) {
        ev.preventDefault();
        var url = jQuery(this).attr('href');

        jQuery('#fss_modal').html(jQuery('#fss_modal_base').html());

        var modal_max_width = jQuery('#fss_modal_container').width();

        if (560 < modal_max_width) {
            jQuery('#fss_modal').css('width', '560px');
            jQuery('#fss_modal').css('margin-left', '-280px');
        }

        //jQuery('#fss_modal').css('margin-top', '-250px');
        jQuery('#fss_modal').modal("show");
        jQuery('#fss_modal').load(url);
        fss_modal_bs3_fix();
    });

    jQuery('.fss_main .show_modal_iframe').click(function (ev) {
        ev.preventDefault();
        var url = jQuery(this).attr('href');
        var width = parseInt(jQuery(this).attr('data_modal_width'));
        if (typeof (width) != "number")
            width = 0;
        if (width < 1)
            width = 820;

        if (jQuery(window).width() < 766)
        {
            width = jQuery(window).width();
        }

        if (width > jQuery(window).width() * 0.9) width = jQuery(window).width() * 0.9;

        var offset = parseInt(width / 2);

        jQuery('#fss_modal').addClass('iframe');
        jQuery('#fss_modal').html("<iframe src='" + url + "' seamless='seamless'>");
        jQuery('#fss_modal').modal("show");
        fss_modal_bs3_fix();

        var modal_max_width = jQuery('#fss_modal_container').width();

        if (width < modal_max_width) {
            jQuery('#fss_modal').css('width', width + 'px');
            jQuery('#fss_modal').css('margin-left', '-' + offset + 'px');
        }
    });

    jQuery('.fss_main .show_modal_image').click(function (ev) {
        ev.preventDefault();
        var url = jQuery(this).attr('href');

        if (!url)
            url = jQuery(this).attr('src');
        
        jQuery('#fss_modal').addClass('iframe');

        var is_inline = false;
        if (url.substring(0, 10) == "data:image")
        {
            is_inline = true;
        }

        if (is_inline) {
            var html = "<img id='modal_image_image' src='" + url + "'>";
            html += "<div class='modal_image_close'>&times;</div>";
        } else {
            var html = "<img id='modal_image_wait' src='" + jQuery('#fss_base_url').text() + "/components/com_fss/assets/images/ajax-loader.gif' style='padding:84px;'>";
            html += "<img id='modal_image_image' src='" + url + "' style='display: none'>";
            html += "<div class='modal_image_close'>&times;</div>";
        }

        jQuery('#fss_modal').html(html);
        jQuery('#fss_modal').modal("show");
        fss_modal_bs3_fix();

        var modal_max_width = jQuery('#fss_modal_container').width();
        if (200 < modal_max_width) {
            jQuery('#fss_modal').css('width', '200px');
            jQuery('#fss_modal').css('margin-left', '-100px');
        }
        //jQuery('#fss_modal').css('margin-top', '-250px');

        jQuery('.modal_image_close').click(function () {
            jQuery('#fss_modal').modal("hide");
        });

        if (is_inline) {
            fss_resize_modal(jQuery('#modal_image_image').naturalWidth(), jQuery('#modal_image_image').naturalHeight());
        } else {
            jQuery('#modal_image_image').load(function () {
                fss_resize_modal(this.width, this.height);
            });
        }

    });

    jQuery('.fss_main .select-color').change(function (ev) {
        select_update_color(this);
    });

    jQuery('.fss_main .select-color').each(function () {
        select_update_color_init(this);
        select_update_color(this);
    });



    try {
        if (jQuery.fn.fss_tooltip) jQuery('.fss_main .fssTip').fss_tooltip();
        if (jQuery.fn.dropdown) jQuery('.fss_main .dropdown-toggle').dropdown();
    } catch (e)
    {

    }
}

function fss_modal_bs3_fix()
{
    if (jQuery('#fss_modal .modal-backdrop').length > 0)
    {
       	jQuery('#fss_modal .modal-backdrop').css('height', '');
        jQuery('#fss_modal').parent().append(jQuery('#fss_modal .modal-backdrop'));
    }
}

function fss_resize_modal(width, height)
{
    var max_width = parseInt(jQuery('#fss_modal_container').width() * 0.9);
    var max_height = parseInt(jQuery(window).height() * 0.9);

    if (width > max_width) {
        var scale = max_width / width;
        width = parseInt(scale * width);
        height = parseInt(scale * height);
    }

    if (height > max_height) {
        var scale = max_height / height;
        width = parseInt(scale * width);
        height = parseInt(scale * height);
    }

    var w_offset = parseInt(width / 2);
    var h_offset = parseInt(height / 2);
    jQuery('#modal_image_wait').hide();
    jQuery('#modal_image_image').show();

    if (width >= max_width) {
        // full page size
        jQuery('#fss_modal').css('width', '');
        jQuery('#fss_modal').css('margin-left', '');
    } else {

        if (jQuery.isFunction(jQuery('#fss_modal').animate)) {
            jQuery('#fss_modal').animate({ width: width + 'px', marginLeft: '-' + w_offset + 'px', marginTop: '-' + h_offset + 'px' }, 500);
        } else {
            jQuery('#fss_modal').css('width', width + 'px');
            jQuery('#fss_modal').css('margin-left', '-' + w_offset + 'px');
            jQuery('#fss_modal').css('margin-top', '-' + h_offset + 'px');
        }
    }
}

function select_update_color_init(el) {
    var sel_el = jQuery(el);
    var value = sel_el.val();
    // change color of dropdown

    basecol = sel_el.css('color');
    
    sel_el.css('color', sel_el.css('color'));

    sel_el.find('option').each(function () {
        var active = false;
        if (value == jQuery(this).attr('value')) {
            sel_el.val(value + 1);
            active = true;
            jQuery(this).removeAttr('selected');
        }

        var color = jQuery(this).css('color');

        if (color == "rgb(255, 255, 255)") // hack for IE
            color = basecol;

        jQuery(this).attr('dropdown-color', color);
        jQuery(this).css('color', color);
        if (active)
            jQuery(this).attr('selected', 'selected');
    });


    sel_el.find('optgroup').each(function () {
        jQuery(this).css('color', jQuery(this).css('color'));
    });
    sel_el.val(value);
}

function select_update_color(el) {
    jQuery(el).css('color', '');
    jQuery(el).find('option').each(function () {
        if (jQuery(this).attr('value') == jQuery(el).val()) {
            jQuery(el).css('color', jQuery(this).attr('dropdown-color'));
        }
    });
}

function fss_modal_show(url, iframe, width) {
    if (!width)
        width = 820;
    var offset = parseInt(width / 2);

    jQuery('#fss_modal').css('width', width + 'px');
    jQuery('#fss_modal').css('margin-left', '-' + offset + 'px');

    if (iframe) {
        jQuery('#fss_modal').addClass('iframe');
        jQuery('#fss_modal').html("<iframe src='" + url + "' scrolling='no' seamless='seamless'>");
        jQuery('#fss_modal').modal("show");
    } else {
        jQuery('#fss_modal').removeClass('iframe');
        jQuery('#fss_modal').html(jQuery('#fss_modal_base').html());
        jQuery('#fss_modal').modal("show");
        jQuery('#fss_modal').load(url);
    }
    fss_modal_bs3_fix();
}

function fss_modal_hide() {
    try {
        jQuery('#fss_modal').modal("hide");
    } catch (e) {
    }
    try {
        jQuery.modal.close();
    } catch (e)
    {
    }
}

(function (jQuery) {
    function _outerSetter(direction, args) {

        var $el = jQuery(this),
            $sec_el = jQuery(args[0]),
            dir = (direction == 'Height') ? ['Top', 'Bottom'] : ['Left', 'Right'],
            style_attrs = ['padding', 'border'],
            style_data = {};
        // If we are detecting margins
        if (args[1]) {
            style_attrs.push('margin');
        }
        jQuery(style_attrs).each(function () {
            var $style_attrs = this;
            jQuery(dir).each(function () {
                var prop = $style_attrs + this + (($style_attrs == 'border') ? 'Width' : '');
                style_data[prop] = parseFloat($sec_el.css(prop));
            });
        });
        $el[direction.toLowerCase()]($sec_el[direction.toLowerCase()]());
        $el.css(style_data);
        return $el['outer' + direction](args[1]);

    }
    jQuery(['Height', 'Width']).each(function () {
        var old_method = jQuery.fn['outer' + this];
        var direction = this;
        jQuery.fn['outer' + this] = function () {
            if (typeof arguments[0] === 'string') {
                return _outerSetter.call(this, direction, arguments);
            }
            return old_method.apply(this, arguments);
        }
    });
})(jQuery);

(function (jQuery) {
    var
    props = ['Width', 'Height'],
    prop;

    while (prop = props.pop()) {
        (function (natural, prop) {
            jQuery.fn[natural] = (natural in new Image()) ?
            function () {
                return this[0][natural];
            } :
            function () {
                var
                node = this[0],
                img,
                value;

                if (node.tagName.toLowerCase() === 'img') {
                    img = new Image();
                    img.src = node.src,
                    value = img[prop];
                }
                return value;
            };
        }('natural' + prop, prop.toLowerCase()));
    }
}(jQuery));

function fss_ClearFileInput(oldInput) {
    var newInput = document.createElement("input");

    newInput.type = "file";
    newInput.id = oldInput.id;
    newInput.name = oldInput.name;
    newInput.className = oldInput.className;
    newInput.style.cssText = oldInput.style.cssText;
    jQuery(newInput).attr('size', jQuery(oldInput).attr('size'));
    // copy any other relevant attributes

    var par = jQuery(oldInput).parent();
    oldInput.remove();

    //jQuery(newInput).prependTo(par);
    jQuery(par).prepend(newInput);
}

jQuery.fn.fss_jquery_ok = function () {
    return true;
};

jQuery(document).bind('dragover', function (e) {
    var dropZone = jQuery('#dropzone'),
        timeout = window.dropZoneTimeout;
    if (!timeout) {
        dropZone.addClass('in');
    } else {
        clearTimeout(timeout);
    }
    var found = false,
        node = e.target;
    do {
        if (node === dropZone[0]) {
            found = true;
            break;
        }
        node = node.parentNode;
    } while (node != null);
    if (found) {
        dropZone.addClass('hover');
    } else {
        dropZone.removeClass('hover');
    }
    window.dropZoneTimeout = setTimeout(function () {
        window.dropZoneTimeout = null;
        dropZone.removeClass('in hover');
    }, 100);
});

function cannedPopup(list)
{
    width = 450;
    var offset = parseInt(width / 2);

    var modal_max_width = jQuery('#fss_modal_container').width();

    if (modal_max_width < 600 || typeof (support_insertpopup) != "undefined") {


        if (width < modal_max_width) {
            // if mobile device, use 90% otherwise 450px
            jQuery('#fss_modal').css('width', '450px');
            jQuery('#fss_modal').css('margin-left', '-' + offset + 'px');
        }

        jQuery('#fss_modal').html(jQuery('#fss_modal_base').html());

        jQuery('#fss_modal .modal-header h3').text(jQuery('.canned_list > div > a').text());

        var html = "";

        // need to make a modal popup here with an accordion type display of all items in the modal

        jQuery('.fss_list_modal').removeClass('fss_list_modal');

        var ul = jQuery(list).parent().children("ul");
        ul.addClass('fss_list_modal');

        var links = ul.find('a');
        for (var i = 0 ; i < links.length ; i++)
        {
            jQuery(links[i]).attr('link_id', 'fss_link_' + i);
        }

        html = '<div class="" id="canned_accordion" style="margin-left:12px">';

        var link_id = 1;

        var children = ul.children();

        for (i = 0 ; i < children.length ; i++) {
            var child = jQuery(children[i]);

            var id = "set" + i;

            if (child.hasClass("divider")) {

                html += "<hr style='margin-top:4px;margin-bottom:4px' />";

            } else if (child.hasClass("dropdown-submenu")) {

                var ael = child.children("a");
                html += '<div class="">';
                html += '<div class=""><h4 style="margin-top:4px;margin-bottom:4px">';
                html += '  <a class="" data-toggle="collapse" data-parent="#accordion2" href="#' + id + '"><i class="icon-arrow-right"></i> ';
                html += ael.text();
                html += '  </a>';
                html += '</h4></div>';
                html += '<div id="' + id + '" class="collapse">';
                html += '  <div style="margin-left: 16px;">';
                html += ' <ul class="unstyled">';

                var subitems = child.children('ul').children('li');

                for (var c = 0 ; c < subitems.length ; c++)
                {
                    var subitem = jQuery(subitems[c]);
                    var new_el = subitem.clone();
                    new_el.attr('onclick', '');
                    var h = new_el.wrapAll('<div>').parent().html()
                    html += h;
                }
                //html += child.children('ul').html();

                html += ' </ul>';
                html += '  </div>';
                html += ' </div>';
                html += '</div>';

            } else {
                html += "<div style='margin-left: 16px;'>";
                html += child.html();
                html += "</div>";
            }
        }

        html += '</div>';

        jQuery('#fss_modal').find('.modal-body').html(html);

        jQuery('#fss_modal').find('.modal-body').find("li").css('margin-top', '1px');
        jQuery('#fss_modal').find('.modal-body').find("li").css('margin-bottom', '1px');

        // capture a events and close the modal before running the previous event

        jQuery('#fss_modal').find('.modal-body').find('a').click(function (ev) {
            ev.preventDefault();

            if (jQuery(this).attr('data-toggle')) {
                return;
            } else if (typeof(jQuery(this).attr('onclick')) != "undefined") {
                fss_modal_hide();
            } else {
                fss_modal_hide();

                var link_id = jQuery(this).attr('link_id');
                var el = jQuery('.fss_list_modal [link_id=' + link_id + ']');
                el.trigger("click");
            }

        });

        jQuery('#fss_modal').modal("show");
        fss_modal_bs3_fix();
    }
}

function fss_url_append(url, param, value)
{
    if (url.indexOf('?') > 0)
    {
        url += "&";
    } else {
        url += "?";
    }

    return url += param + "=" + encodeURIComponent(value);
}
