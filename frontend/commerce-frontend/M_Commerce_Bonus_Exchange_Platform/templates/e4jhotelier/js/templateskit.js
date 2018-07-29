jQuery.noConflict();

/**** Cache problem LESS files ***/
/*function destroyLessCache(templates/e4jeasyhiring/css) { // e.g. '/css/' or '/stylesheets/'
 
  if (!window.localStorage || !less || less.env !== 'development') {
    return;
  }
  var host = window.location.host;
  var protocol = window.location.protocol;
  var keyPrefix = protocol + '//' + host + pathToCss;
  
  for (var key in window.localStorage) {
    if (key.indexOf(keyPrefix) === 0) {
      delete window.localStorage[key];
    }
  }
}
/*** End Less Problem ***/

var resp_menu_on = false;

function vikShowResponsiveMenu() {
    jQuery(".nav-menu-active").toggle();
    jQuery(".e4j-body-page").toggleClass("e4j-body-fixed e4j-body-shifted");
    if(jQuery(".e4j-body-page").hasClass("e4j-body-shifted")) {
        resp_menu_on = true;
    }else {
        resp_menu_on = false;
    }
}

function vikcs_adapter() {
    if (jQuery("#vikcs-slider")) {
        var vcsl_height = jQuery("#vikcs-slider").css("height");
        if (parseInt(vcsl_height) > 0) {
            jQuery("#slideadv").css("height", vcsl_height);
        }
    }
}

jQuery(document).ready(function() {

    vikcs_adapter();

    /**** Menu MAINMENU position ***/
    var screen = jQuery(window).width();
    if (screen < 800) {
        jQuery("#nav-menu-devices").addClass("nav-menu-active");
       
    }
    jQuery(window).resize(function() {
        var width = jQuery(window).width();
        if (width < 800) {
            jQuery("#nav-menu-devices").addClass("nav-menu-active");
        } else {
            jQuery("#nav-menu-devices").removeClass("nav-menu-active").removeAttr("style");
        }
        vikcs_adapter();
    });

    var screen = jQuery(window).width();
    if (screen <= 1480) {
        jQuery("#mainmenu .loginmenu").addClass("e4jsign-rsz");
    }
    jQuery(window).resize(function() {
        var width = jQuery(window).width();
        if (width <= 1480) {
            jQuery("#mainmenu .loginmenu").addClass("e4jsign-rsz");
        } else {
            jQuery("mainmenu .loginmenu").removeClass("e4jsign-rsz").removeAttr("style");
        }
        vikcs_adapter();
    });

    /** Class fixed menu ***/

	var menu_lim = jQuery("#tbar-upmenu");
	var menu_selector = jQuery(".fixedmenu");
	var change_to_sticky = true;
    var lim_pos = 75;
    if(menu_lim.length) {
        lim_pos = menu_lim.offset().top + menu_lim.outerHeight(true);
    }
	jQuery(window).scroll(function() {
		var scrollpos = jQuery(window).scrollTop();
		if (scrollpos > lim_pos) {
			if (change_to_sticky === true) {
				menu_selector.addClass("fx-menu-slide");
				change_to_sticky = false;
			}
		} else {
			if (change_to_sticky === false) {
				menu_selector.removeClass("fx-menu-slide");
				change_to_sticky = true;
			}
		}
	});

    var login_blocked = false;
    jQuery("#mainmenu li.parent, .loginmenu, .topmenu li.parent").hover(function() {
        jQuery(this).addClass("parent-open");
    }, function() {
        if(!login_blocked) {
            jQuery(this).removeClass("parent-open");
        }
    });
    jQuery(".loginmenu #modlgn-username, .loginmenu #modlgn-passwd").focus(function() {
        login_blocked = true;
    });

    jQuery(document).mouseup(function (e) {
        //Responsive Menu
        var resp_menu_cont = jQuery("#nav-menu-devices");
        var resp_menu_button = jQuery("#menutitlemob");
        if(resp_menu_on && !resp_menu_cont.is(e.target) && resp_menu_cont.has(e.target).length === 0  && !resp_menu_button.is(e.target) && resp_menu_button.has(e.target).length === 0) {
            resp_menu_on = false;
            jQuery(".nav-menu-active").hide();
            jQuery(".e4j-body-page").removeClass("e4j-body-fixed e4j-body-shifted");
        }
        //Login dropdown
        var login_container = jQuery(".loginmenu");
        if (!login_container.is(e.target) && login_container.has(e.target).length === 0) {
            login_blocked = false;
            login_container.removeClass(".parent-open");
            login_container.trigger("mouseout");
        }
    });


    /**** Menu SUBMENU position ***/
    var screen = jQuery(window).width();
    if (screen < 860) {
        jQuery("#submenu .l-inline").addClass("menumobile");
    }
    jQuery(window).resize(function() {
        var width = jQuery(window).width();
        if (width < 860) {
            jQuery("#submenu .l-inline").addClass("menumobile");
        } else {
            jQuery("#submenu .l-inline").removeClass("menumobile").removeAttr("style");
        }
    });

    jQuery("#submenu li.parent").hover(function() {
        jQuery(this).find(".l-block:first").stop(true, true).delay(50).slideDown(400);
    }, function() {
        jQuery(this).find(".l-block:first").stop(true, true).slideUp(600);
    });


    /**** Login Tab ***/
    jQuery(".logintab h3").click(function() {
        jQuery(this).toggleClass('logintabopened');
        jQuery(".logintab #login-form").fadeToggle();
    });
    jQuery(document).mouseup(function(e) {
        var logintabcontainer = jQuery(".logintab #login-form");
        if (!logintabcontainer.is(e.target) && logintabcontainer.has(e.target).length === 0) {
            logintabcontainer.hide();
            jQuery(".logintab h3").removeClass("logintabopened");
        }
    });  
});