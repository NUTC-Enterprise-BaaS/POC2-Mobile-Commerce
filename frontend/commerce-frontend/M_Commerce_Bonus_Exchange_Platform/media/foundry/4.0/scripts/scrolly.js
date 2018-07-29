(function(){

// module factory: start

var moduleFactory = function($) {
// module body: start

var module = this; 
$.require() 
 .script("mousewheel") 
 .done(function() { 
var exports = function() { 

/*!
 * jquery.scrolly.
 * Overlay scrollbar for jQuery.
 *
 * Copyright (c) 2014 Jensen Tonne
 * http://jstonne.me
 *
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */

(function(){

var doco     = $(document),
    scrolly  = "[data-scrolly]",
    viewport = "[data-scrolly-viewport]",
    disabled = "scrolly-disabled",
    activeScrolly = "[data-scrolly]:not(.scrolly-disabled)",
    ns = function(event) {
        return event.split(" ").join(".scrolly ") + ".scrolly";
    },
    percent  = function(n) {
        return n * 100 + "%";
    },
    toggle_ = function(n) {
        return n < 1 ? "block" : "none";
    },
    self = {

        axis: function(node, mode) {

            var axis = $(node).data("axis");
            if (axis && !mode) return axis;

            mode = (mode || $(node).data("scrolly") || "xy").split("");

            var axis = {}, direction;
            while (direction = mode.shift()) {
                axis[direction] = true;
            }

            $(node).data("axis", axis);

            return axis;
        },

        viewport: function(node) {
            return $(node).find("> " + viewport)[0] || $(node).wrapInner("<div data-scrolly-viewport>").find("> " + viewport)[0];
        },

        x: function(node) {
            return $(node).find("> s[data-scrolly-axis=x]")[0] || $('<s data-scrolly-axis="x">').appendTo(node)[0];
        },

        y: function(node) {
            return $(node).find("> s[data-scrolly-axis=y]")[0] || $('<s data-scrolly-axis="y">').appendTo(node)[0];
        },

        update: function(node, dx, dy, fromScrollbar) {

            var axis = self.axis(node);

            with (self.viewport(node)) {

                var a = fromScrollbar ? scrollHeight / offsetHeight : 1;
                axis.y && (scrollTop  -= (dy || 0) * a);
                axis.x && (scrollLeft -= (dx || 0) * a);

                var scrollbarTop    =    scrollTop / scrollHeight,
                    scrollbarLeft   =   scrollLeft / scrollWidth,
                    scrollbarHeight = offsetHeight / scrollHeight,
                    scrollbarWidth  =  offsetWidth / scrollWidth;
            }

            if (axis.x) {
                with (self.x(node).style) {
                    left    = percent(scrollbarLeft);
                    width   = percent(scrollbarWidth);
                    display = toggle_(scrollbarWidth);
                }
            }

            if (axis.y) {
                with (self.y(node).style) {
                    top     = percent(scrollbarTop);
                    height  = percent(scrollbarHeight);
                    display = toggle_(scrollbarHeight);
                }
            }

            var elem = $(node);

            clearTimeout(elem.data("scrolly_timer"));

            elem.data("scrolly_timer",
                    setTimeout(function(){
                        elem.removeData("scrolly_timer");
                    }, 1000)
                )
                .trigger("scrolly");
        },

        enable: function(node) {
            $(node).removeClass(disabled);
        },

        disable: function(node) {
            $(node).addClass(disabled);
        },

        reset: function(node) {
            $(node).removeData("axis");
        }
    };

$.fn.scrolly = function(method) {

    // Calling a method
    if ($.isString(method)) {
        var ret = self[method]([this[0]].concat($.makeArray(arguments).slice(1)));
        return ret || this;
    }

    // Iniitalize scrolly
    return $(this).eq(0).attr("data-scrolly", (method || {}).axis);
}

// Last update
doco.on(ns("mouseover"), scrolly, function(event) {

        var node = $(event.target).closest(activeScrolly)[0];
        node && !$(node).data("scrolly_timer") && self.update(node);
    })
    .on(ns("mousewheel"), scrolly, function(event, delta, dx, dy) {

        var node = $(event.target).closest(activeScrolly)[0];
        node && self.update(node, dx, dy);
    })
    .on(ns("mousedown"), scrolly + " > s", function(before) {

        var node = $(this).parent(scrolly).addClass("scrolling");

        doco.on(ns("mousemove"), function(now){

                self.update(
                    node[0],
                    before.pageX - now.pageX,
                    before.pageY - now.pageY,
                    true
                );
                before = now;

                // This prevents text selection
                now.preventDefault();
            })
            .on(ns("mouseup"), function(){
                node.removeClass("scrolling");
                doco.off(ns("mousemove mouseup"));
            });
    });

})();
}; 

exports(); 
module.resolveWith(exports); 

}); 
// module body: end

}; 
// module factory: end

FD40.module("scrolly", moduleFactory);

}());