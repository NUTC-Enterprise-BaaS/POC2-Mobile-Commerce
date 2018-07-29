jQuery(document).ready(function ($) {
	
	// Add classes
	jQuery("body.admin").addClass("pri-module-admin");
	jQuery("#jform_params_color_overlay").addClass("prichild overlaytype overlaytype_color");
	jQuery("#jform_params_svg_overlay_code").addClass("prichild overlaytype overlaytype_svg");
	var fieldsetControl = function () {
		jQuery("#myTabTabs>li").each(function () {
			var target = jQuery(this).find(">a").prop("hash");
			if (target == "#attrib-image") {
				jQuery(this).find(">a").addClass("prichild backgroundtype backgroundtype_image").parent().addClass("pri-fieldset");
			} else if (target == "#attrib-random_image") {
				jQuery(this).find(">a").addClass("prichild backgroundtype backgroundtype_random-image").parent().addClass("pri-fieldset");
			} else if (target == "#attrib-slideshow") {
				jQuery(this).find(">a").addClass("prichild backgroundtype backgroundtype_slideshow").parent().addClass("pri-fieldset");
			} else if (target == "#attrib-video") {
				jQuery(this).find(">a").addClass("prichild backgroundtype backgroundtype_video").parent().addClass("pri-fieldset");
			} else if (target == "#attrib-youtube") {
				jQuery(this).find(">a").addClass("prichild backgroundtype backgroundtype_youtube").parent().addClass("pri-fieldset");
			} else if (target == "#attrib-vimeo") {
				jQuery(this).find(">a").addClass("prichild backgroundtype backgroundtype_vimeo").parent().addClass("pri-fieldset");
			}
		});
		jQuery("#collapseTypes>.accordion-group").each(function () {
			var target = jQuery(this).find(".accordion-heading a").html();
			if (target == "Image") {
				jQuery(this).find(".accordion-heading a").addClass("prichild backgroundtype backgroundtype_image").parent().parent().parent().addClass("pri-fieldset");
			}else if (target == "Video") {
				jQuery(this).find(".accordion-heading a").addClass("prichild backgroundtype backgroundtype_video").parent().parent().parent().addClass("pri-fieldset");
			}else if (target == "Youtube") {
				jQuery(this).find(".accordion-heading a").addClass("prichild backgroundtype backgroundtype_youtube").parent().parent().parent().addClass("pri-fieldset");
			}else if (target == "Vimeo") {
				jQuery(this).find(".accordion-heading a").addClass("prichild backgroundtype backgroundtype_vimeo").parent().parent().parent().addClass("pri-fieldset");
			}
			
		});
	}
	

	var parentControl = function () {
		var classes = new Array();
		$("fieldset.priparent, select.priparent").each(function () {
			var eleclass = $(this).attr("class").split(/\s/g);
			var $key = $.inArray("priparent", eleclass);
			if ($key != -1) {
				classes.push(eleclass[$key + 1]);
			}
		});

		$("fieldset.priparent, select.priparent").each(function () {
			var parent = $(this);
			var eleclass = $(this).attr("class").split(/\s/g);
			var childClassName = ".prichild";
			var conditionClassName = "";
			var i;

			for (i = 0; i < eleclass.length; i++) {
				if ($.inArray(eleclass[i], classes) < 0) {
					continue;
				} else {
					var elecls = "." + eleclass[i];
					$(childClassName + elecls).parents(".control-group, .pri-fieldset").hide();
					if ($(parent).prop("type") == "fieldset") {
						var selected = $(parent).find("input[type=radio]:checked");
						var radios = $(parent).find("input[type=radio]");
						var activeItems = conditionClassName + elecls + "_" + $(selected).val();
						var childitem = $.trim(childClassName + elecls + activeItems);
						setTimeout(function () {
							$(childitem).parents(".control-group").show();
						}, 100);

						$(radios).on("click", function (event) {
							$(childClassName + elecls).parents(".control-group, .pri-fieldset").hide();
							$(childClassName + elecls + conditionClassName + elecls + "_" + $.trim($(this).val())).parents(".control-group, .pri-fieldset").fadeIn();
						});

					} else if ($(parent).prop("type") == "select-one") {
						var element = $(parent);
						var selected = $(parent).find("option:selected");
						var option = $(parent).find("option");
						var activeItems = conditionClassName + elecls + "_" + $(selected).val();
						var childitem = $.trim(childClassName + elecls + activeItems);
						setTimeout(function () {
							$(childitem).parents(".control-group, .pri-fieldset").show();
						}, 100);

						$(element).on("change", function (event) {
							$(childClassName + elecls).parents(".control-group, .pri-fieldset").hide();
							$(childClassName + elecls + conditionClassName + elecls + "_" + $.trim($(this).val())).parents(".control-group, .pri-fieldset").fadeIn();
							
						});

					}
				}
			}
		});
	}
	$(window).load(function() {
		fieldsetControl();
		parentControl();
	});
});