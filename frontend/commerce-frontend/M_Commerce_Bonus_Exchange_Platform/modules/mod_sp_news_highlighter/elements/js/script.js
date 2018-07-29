/*
# SP News Highlighter Module by JoomShaper.com
# --------------------------------------------
# Author    JoomShaper http://www.joomshaper.com
# Copyright (C) 2010 - 2014 JoomShaper.com. All Rights Reserved.
# License - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomshaper.com
*/
jQuery(document).ready(function(){
	showhide();	
	jQuery('#jform_params_content_source').change(function() {showhide()});
	jQuery('#jform_params_content_source').blur(function() {showhide()});
	function showhide(){
		if (jQuery("#jform_params_content_source").val()=="k2") {
			jQuery("#jform_params_catid").parent().parent().css("display", "none");
			jQuery("#jformparamsk2catids").parent().parent().css("display", "block");
		} else {
			jQuery("#jformparamsk2catids").parent().parent().css("display", "none");
			jQuery("#jform_params_catid").parent().parent().css("display", "block");	
		}
	}
});