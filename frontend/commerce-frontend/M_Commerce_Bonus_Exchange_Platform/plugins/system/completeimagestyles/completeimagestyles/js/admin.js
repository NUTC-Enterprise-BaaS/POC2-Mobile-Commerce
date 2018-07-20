// JavaScript Document
jQuery(document).ready(function($) {
	// Variables.
	var themes = new Array();
	var styles = {};
	
	/* ************ */
	/* Simple Theme */
	/* ************ */
	var theme = {};
	// Border.
	theme.border_normal = 1;
	theme.border_size = 2;
	theme.border_color = '#FFFFFF';
	theme.border_opacity = 1;
	theme.border_style = 'solid';
	// Pop.
	theme.pop_hover = 1;
	theme.pop_size_hover = 8;
	// Rounded Corners.
	theme.rounded_corners_normal = 1;
	theme.rounded_corners_size = 20;
	// Shadow.
	theme.shadow_normal = 1;
	theme.shadow_size = 10;
	theme.shadow_color = '#333333';
	theme.shadow_opacity = 1;
	
	themes['simple'] = theme;
	
	/* *********** */
	/* Crazy Theme */
	/* *********** */
	var theme = {};
	// Border.
	theme.border_normal = 1;
	theme.border_size = 4;
	theme.border_color = '#FFFFFF';
	theme.border_opacity = 1;
	theme.border_style = 'solid';
	// Shadow.
	theme.shadow_normal = 1;
	theme.shadow_size = 10;
	theme.shadow_color = '#333333';
	theme.shadow_opacity = 1;
	// Shake.
	theme.shake_hover = 1;
	
	themes['crazy'] = theme;
	
	/* ***** */
	/* Paper */
	/* ***** */
	var theme = {};
	// Border.
	theme.border_normal = 1;
	theme.border_size = 1;
	theme.border_color = '#FFFFFF';
	// Lifted Corners.
	theme.lifted_corners_normal = 1;
	
	themes['paper'] = theme;
	
	/* ************** */
	/* Polaroid Theme */
	/* ************** */
	var theme = {};
	// External Caption.
	theme.external_caption_normal = 1;
	theme.external_caption_background_color = '#FFFFFF';
	theme.external_caption_font_color = '#333333';
	theme.external_caption_font_size = 12;
	theme.external_caption_space = 10;
	// Shadow.
	theme.shadow_normal = 1;
	theme.shadow_size = 5;
	theme.shadow_color = '#333333';
	theme.shadow_opacity = 1;
	
	themes['polaroid'] = theme;
	
	/* ************** */
	/* Emersion Theme */
	/* ************** */
	var theme = {};
	// Cutout.
	theme.cutout_normal = 1;
	theme.cutout_size = 5;
	// Rounded Corners.
	theme.rounded_corners_normal = 1;
	theme.rounded_corners_size = 30;
	// Shadow.
	theme.shadow_hover = 1;
	theme.shadow_size_hover = 15;
	theme.shadow_color_hover = '#000000';
	theme.shadow_opacity_hover = 1;
	
	themes['emersion'] = theme;
	
	/* **************** */
	/* Reflection Theme */
	/* **************** */
	var theme = {};
	// Rounded Corners.
	theme.rounded_corners_normal = 1;
	theme.rounded_corners_size = 10;
	// Internal Caption.
	theme.internal_caption_normal = 1;
	theme.internal_caption_background_color = '#000000';
	theme.internal_caption_font_color = '#FFFFFF';
	theme.internal_caption_font_size = 10;
	// Reflection.
	theme.reflection_normal = 1;
	theme.reflection_size = 30;
	theme.reflection_color = '#FFFFFF';
	
	themes['reflection'] = theme;
	
	/* ************ */
	/* Mirror Theme */
	/* ************ */
	var theme = {};
	// Double Outlined.
	theme.double_outlined_normal = 1;
	theme.double_outlined_inner_color = '#FFFFFF';
	theme.double_outlined_inner_size = 5;
	theme.double_outlined_outer_color = '#000000';
	theme.double_outlined_outer_size = 2;
	// Rotation.
	theme.rotation_hover = 1;
	theme.rotation_size_hover = 3;
	// Round Image.
	theme.round_image_normal = 1;
	// Shadow.
	theme.shadow_normal = 1;
	theme.shadow_size = 10;
	theme.shadow_color = '#333333';
	theme.shadow_opacity = 1;
	
	themes['mirror'] = theme;
	
	/* ************ */
	/* Pillow Theme */
	/* ************ */
	var theme = {};
	// Double Outlined.
	theme.double_outlined_normal = 1;
	theme.double_outlined_inner_color = '#FFFFFF';
	theme.double_outlined_inner_size = 1;
	theme.double_outlined_outer_color = '#666666';
	theme.double_outlined_outer_size = 1;
	// Embossed.
	theme.embossed_normal = 1;
	theme.embossed_size = 7;
	// Rounded Corners.
	theme.rounded_corners_normal = 1;
	theme.rounded_corners_size = 10;
	// Tooltip.
	theme.tooltip_hover = 1;
	theme.tooltip_font_size = 11;
	
	themes['pillow'] = theme;
	
	/* ********** */
	/* Wall Theme */
	/* ********** */
	var theme = {};
	// Border.
	theme.border_normal = 1;
	theme.border_size = 1;
	theme.border_color = '#333333';
	theme.border_opacity = 1;
	theme.border_style = 'solid';
	// Perspective.
	theme.perspective_normal = 1;
	theme.perspective_direction = 'right';
	
	themes['wall'] = theme;
	
	/* ************ */
	/* Bent Corners */
	/* ************ */
	var theme = {};
	// External Caption.
	theme.external_caption_normal = 1;
	theme.external_caption_background_color = '#EFEFEF';
	theme.external_caption_space = 10;
	theme.external_caption_border_color = '#EFEFEF';
	theme.external_caption_border_size = 1;
	theme.external_caption_font_color = '#333333';
	theme.external_caption_font_size = 10;
	// Horizontal Curve.
	theme.horizontal_curve_normal = 1;
	theme.horizontal_curve_size = 8;
	// Vertical Curve.
	theme.vertical_curve_normal = 1;
	theme.vertical_curve_size = 8;
	
	themes['bentcorners'] = theme;
	
	/* ********** */
	/* Tape Theme */
	/* ********** */
	var theme = {};
	// Border.
	theme.border_normal = 1;
	theme.border_size = 4;
	theme.border_color = '#FFFFFF';
	theme.border_opacity = 1;
	theme.border_style = 'solid';
	// Raised Box.
	theme.raised_box_normal = 1;
	theme.raised_box_size = 15;
	theme.raised_box_hover = 1;
	theme.raised_box_size_hover = 25;
	// Tape.
	theme.tape_normal = 1;
	
	themes['tape'] = theme;
	
	/* ***** */
	/* Diary */
	/* ***** */
	var theme = {};
	// Curled Corners.
	theme.curled_corners_normal = 1;
	// Internal Caption.
	theme.internal_caption_normal = 1;
	theme.internal_caption_background_color = '#000000';
	theme.internal_caption_font_color = '#FFFFFF';
	theme.internal_caption_font_size = 10;
	theme.internal_caption_position = 'top';
	
	themes['diary'] = theme;
	
	/* *************** */
	/* Sliding Caption */
	/* *************** */
	var theme = {};
	// Border.
	theme.border_normal = 1;
	theme.border_size = 4;
	theme.border_color = '#FFFFFF';
	theme.border_opacity = 1;
	theme.border_style = 'solid';
	// Shadow.
	theme.shadow_normal = 1;
	theme.shadow_size = 7;
	theme.shadow_color = '#000000';
	theme.shadow_opacity = 0.5;
	// Sliding Caption.
	theme.sliding_caption_hover = 1;
	theme.sliding_caption_font_size = 11;
	
	themes['slidingcaption'] = theme;
	
	/* ******* */
	/* Pick Up */
	/* ******* */
	var theme = {};
	// Border.
	theme.border_normal = 1;
	theme.border_size = 2;
	theme.border_color = '#FFFFFF';
	theme.border_opacity = 1;
	theme.border_style = 'solid';
	// Pop.
	theme.pop_hover = 1;
	theme.pop_size_hover = 15;
	// Rotation.
	theme.rotation_hover = 1;
	theme.rotation_size_hover = 3;
	// Shadow.
	theme.shadow_normal = 1;
	theme.shadow_size = 0;
	theme.shadow_color = '#000000';
	theme.shadow_opacity = 1;
	theme.shadow_hover = 1;
	theme.shadow_size_hover = 10;
	theme.shadow_color_hover = '#000000';
	theme.shadow_opacity_hover = 1;
	
	themes['pickup'] = theme;
	
	/* ************** */
	/* Show Up Theme */
	/* ************** */
	var theme = {};
	// Opacity.
	theme.opacity_normal = 1;
	theme.opacity_size = 0.7;
	theme.opacity_hover = 1;
	theme.opacity_size_hover = 1;
	// Rounded Corners.
	theme.rounded_corners_normal = 1;
	theme.rounded_corners_size = 25;
	// Shadow.
	theme.shadow_normal = 1;
	theme.shadow_size = 0;
	theme.shadow_color = '#000000';
	theme.shadow_opacity = 0;
	theme.shadow_hover = 1;
	theme.shadow_size_hover = 10;
	theme.shadow_color_hover = '#000000';
	theme.shadow_opacity_hover = 1;
	
	themes['showup'] = theme;
	
	/* ************* */
	/* Fill It Theme */
	/* ************* */
	var theme = {};
	// Double Outlined.
	theme.double_outlined_normal = 1;
	theme.double_outlined_inner_color = '#FFFFFF';
	theme.double_outlined_inner_size = 10;
	theme.double_outlined_inner_opacity = 0;
	theme.double_outlined_outer_color = '#000000';
	theme.double_outlined_outer_size = 2;
	theme.double_outlined_outer_opacity = 1;
	theme.double_outlined_hover = 1;
	theme.double_outlined_inner_color_hover= '#000000';
	theme.double_outlined_inner_size_hover = 10;
	theme.double_outlined_inner_opacity_hover = 1;
	theme.double_outlined_outer_color_hover = '#000000';
	theme.double_outlined_outer_size_hover = 2;
	theme.double_outlined_outer_opacity_hover = 1;
	// Horizontal Curve.
	theme.horizontal_curve_normal = 1;
	theme.horizontal_curve_size = 15;
	// Vertical Curve.
	theme.vertical_curve_normal = 1;
	theme.vertical_curve_size = 15;
	
	themes['fillit'] = theme;
	
	/* ************* */
	/* Scuttle Theme */
	/* ************* */
	var theme = {};
	// Cutout.
	theme.cutout_normal = 1;
	theme.cutout_size = 5;
	theme.cutout_hover = 1;
	theme.cutout_size_hover = 5;
	// Double Outlined.
	theme.double_outlined_normal = 1;
	theme.double_outlined_inner_color = '#DFDFDF';
	theme.double_outlined_inner_size = 7;
	theme.double_outlined_inner_opacity = 1;
	theme.double_outlined_outer_color = '#DFDFDF';
	theme.double_outlined_outer_size = 0;
	theme.double_outlined_outer_opacity = 1;
	theme.double_outlined_hover = 1;
	theme.double_outlined_inner_color_hover= '#DFDFDF';
	theme.double_outlined_inner_size_hover = 7;
	theme.double_outlined_inner_opacity_hover = 1;
	theme.double_outlined_outer_color_hover = '#DFDFDF';
	theme.double_outlined_outer_size_hover = 20;
	theme.double_outlined_outer_opacity_hover = 0;
	// Rotation.
	theme.rotation_hover = 1;
	theme.rotation_size_hover = 360;
	// Round Image.
	theme.round_image_normal = 1;
	// Strech.
	theme.stretch = 0;
	
	themes['scuttle'] = theme;
	
	/* ****** */
	/* Pop Up */
	/* ****** */
	var theme = {};
	// Border.
	theme.border_normal = 1;
	theme.border_size = 1;
	theme.border_color = '#FFFFFF';
	theme.border_opacity = 1;
	theme.border_style = 'solid';
	// Scale.
	theme.scale_normal = 1;
	theme.scale_size = 1;
	theme.scale_hover = 1;
	theme.scale_size_hover = 1.05;
	// Shadow.
	theme.shadow_normal = 1;
	theme.shadow_size = 5;
	theme.shadow_color = '#000000';
	theme.shadow_opacity = 1;
	theme.shadow_hover = 1;
	theme.shadow_size_hover = 10;
	theme.shadow_color_hover = '#000000';
	theme.shadow_opacity_hover = 1;
	
	themes['popup'] = theme;
	
	/* ****** */
	/* Dashed */
	/* ****** */
	var theme = {};
	// Border.
	theme.border_normal = 1;
	theme.border_size = 4;
	theme.border_color = '#333333';
	theme.border_opacity = 1;
	theme.border_style = 'dashed';
	// Shadow.
	theme.shadow_normal = 1;
	theme.shadow_size = 10;
	theme.shadow_color = '#333333';
	theme.shadow_opacity = 1;
	// Shake.
	theme.shake_hover = 1;
	
	themes['dashed'] = theme;
	
	/* *** */
	/* Tin */
	/* *** */
	var theme = {};
	// Border.
	theme.border_normal = 1;
	theme.border_size = 4;
	theme.border_color = '#333333';
	theme.border_opacity = 1;
	theme.border_style = 'double';
	// External Caption.
	theme.external_caption_normal = 1;
	theme.external_caption_background_color = '#EFEFEF';
	theme.external_caption_space = 10;
	theme.external_caption_border_color = '#EFEFEF';
	theme.external_caption_border_size = 1;
	theme.external_caption_font_color = '#333333';
	theme.external_caption_font_size = 10;
	// Horizontal Curve.
	theme.horizontal_curve_normal = 1;
	theme.horizontal_curve_size = 15;
	// Vertical Curve.
	theme.vertical_curve_normal = 1;
	theme.vertical_curve_size = 15;
	// Rounded Corners.
	theme.rounded_corners_normal = 1;
	theme.rounded_corners_size = 25;
	
	themes['tin'] = theme;
	
	/* ******** */
	/* Close It */
	/* ******** */
	var theme = {};
	// Double Outlined.
	theme.double_outlined_normal = 1;
	theme.double_outlined_inner_color = '#000000';
	theme.double_outlined_inner_size = 0;
	theme.double_outlined_inner_opacity = 0;
	theme.double_outlined_outer_color = '#000000';
	theme.double_outlined_outer_size = 15;
	theme.double_outlined_outer_opacity = 1;
	theme.double_outlined_hover = 1;
	theme.double_outlined_inner_color_hover= '#000000';
	theme.double_outlined_inner_size_hover = 0;
	theme.double_outlined_inner_opacity_hover = 0;
	theme.double_outlined_outer_color_hover = '#000000';
	theme.double_outlined_outer_size_hover = 15;
	theme.double_outlined_outer_opacity_hover = 1;
	// Glowing.
	theme.glowing_normal = 1;
	theme.glowing_size = 40;
	theme.glowing_hover = 1;
	theme.glowing_size_hover = 0;
	// Margin.
	theme.margin_normal = 1;
	theme.margin_top = 25;
	theme.margin_right = 25;
	theme.margin_bottom = 25;
	theme.margin_left = 25;
	// Shadow.
	theme.shadow_normal = 1;
	theme.shadow_size = 15;
	theme.shadow_color = '#000000';
	theme.shadow_opacity = 1;
	theme.shadow_hover = 1;
	theme.shadow_size_hover = 0;
	theme.shadow_color_hover = '#000000';
	theme.shadow_opacity_hover = 0;
	
	themes['closeit'] = theme;
	
	/* ******** */
	/* Inclined */
	/* ******** */
	var theme = {};
	// Cutout.
	theme.cutout_normal = 1;
	theme.cutout_size = 5;
	// Rounded Corners.
	theme.rounded_corners_normal = 1;
	theme.rounded_corners_size = 150;
	// Shadow.
	theme.shadow_normal = 1;
	theme.shadow_size = 10;
	theme.shadow_color = '#333333';
	theme.shadow_opacity = 1;
	
	themes['inclined'] = theme;
	
	/* ********* */
	/* Paperclip */
	/* ********* */
	var theme = {};
	// Raised Box.
	theme.raised_box_normal = 1;
	theme.raised_box_size = 15;
	theme.raised_box_hover = 1;
	theme.raised_box_size_hover = 25;
	// SVG.
	theme.svg_normal = 1;
	theme.svg_image = 'paperclip-red.svg';
	theme.svg_joomla_uri = ((document.location.protocol + '//' + document.location.host + document.location.pathname).replace('index.php', ''))+'../';
	
	themes['paperclip'] = theme;
	
	/* *************** */
	/* Grayscale Theme */
	/* *************** */
	var theme = {};
	// Border.
	theme.border_normal = 1;
	theme.border_size = 4;
	theme.border_color = '#FFFFFF';
	theme.border_opacity = 1;
	theme.border_style = 'solid';
	// Shadow.
	theme.shadow_normal = 1;
	theme.shadow_size = 10;
	theme.shadow_color = '#333333';
	theme.shadow_opacity = 1;
	// Grayscale.
	theme.grayscale_normal = 1;
	
	themes['grayscale'] = theme;
	
	// Load all the styles.	// <------------- IMPORTANT CODE.
	if ($('#jform_params_styles').val() != '')
	{
		styles = $('#jform_params_styles').val();
		styles = JSON.parse(styles);	// Convert JSON with styles to object array.
	}
	
	/* ********************* */
	/* Appearance and Events */
	/* ********************* */
	// Show themes as samples.
	for (theme in themes)
	{
		if (!themes.hasOwnProperty(theme)) continue;	// Avoid the default properties of the array.
		var temp = {};
		$.extend(temp, themes[theme]);
		temp.css_selectors = '.cis-theme-'+theme;
		completeImageStyles(JSON.stringify(temp));
	}
	
	for (i=1; i<=15; i++)
	{
		cisDeserializeStyle(i);
		cisRefreshAppearanceOfStyle(i);
	}

	$('.cis-toggle').hide(0);	// Hide substyles.

	$('.cis-header').click(function() {
		$(this).attr('clicked', 'true');
	});
	
	$('.cis-sub-header').click(function() {
		if ($(this).next().css('display') == 'none')	// If closed.
			$(this).children('.cis-openclose').css('background-image', 'url(../plugins/system/completeimagestyles/completeimagestyles/images/minus-dark.png)');
		else		// If open.
			$(this).children('.cis-openclose').css('background-image', 'url(../plugins/system/completeimagestyles/completeimagestyles/images/plus-dark.png)');
		$(this).next().slideToggle(400);
	});
	
	// Stylize the select boxes with 'Enabled' and 'Disabled'.
	$('.cis-field select.cis-switch').change(function() {
		$(this).removeClass('cis-red');
		$(this).removeClass('cis-green');
		if ($(this).val() == '0')
			$(this).addClass('cis-red');
		if ($(this).val() == '1')
			$(this).addClass('cis-green');
		
		// Show if the sub style is enabled or disabled.
		$(this).closest('.cis-substyle').each(function(index, element) {
			cisSubStyleEnabledLabel($(this));
		});
	});
	
	// On theme selection...
	$('select[name="theme"]').change(function() {
		var value = $(this).val();
		var style = $(this).parents('.cis-style');
		var i = $('.cis-style > .cis-toggle').parent().attr('id').replace('cis-style-', '');	// Get the id of the current style.
		
		cisResetAllSubStyles(i);
		
		// Apply theme to the fields.
		var theme = themes[value];
		for (var property in theme) 
		{
			$(style).find("[name='"+property+"']").val(theme[property]);
		}
		
		cisRefreshAppearanceOfAllSubStyles(i);
	});
	
	if ($('#jform_params_styles').val().length == 0)
	{
		$('select[name="theme"]').trigger('change');
	}
	
	// On click to any style...
	$('.cis-style').click(function() {
		if ($(this).attr('clicked') != 'true')
		{
			$(this).siblings('.cis-style').removeAttr('clicked');	// Set the others styles as not clicked.
			$(this).attr('clicked', 'true');	// Set the current style as clicked.
			
			var i = $('.cis-style > .cis-toggle').parent().attr('id').replace('cis-style-', '');	// Get the id of the current style.
			cisSerializeStyle(i);	// Save the old style.
			$('.cis-style > .cis-toggle').hide(0);	// Hide the substyles.
			$('.cis-style > .cis-toggle').appendTo($(this));	// Move the '.cis-style > .cis-toggle' to the current style.
			
			var i = $(this).attr('id').replace('cis-style-', '');	// Get the id of the current style.
			$('.cis-style > .cis-toggle > .cis-field:nth-child(1) input').val('cis-style-'+i);	// Change the value of the "Class" field.
			// Change the value of the "CSS Selectors" field.
			if (styles[i] == undefined || styles[i]['css_selectors'] == undefined)
				$('.cis-style > .cis-toggle > .cis-field:nth-child(2) input').val('');
			else
				$('.cis-style > .cis-toggle > .cis-field:nth-child(2) input').val(styles[i]['css_selectors']);
			$('.cis-style > .cis-toggle > .cis-field:nth-child(3) select').val('simple');	// Change the value of the "Theme" field.
			if (styles[i] !== 'undefined')
				cisDeserializeStyle();	// Restore the current style.
			else
				$('select[name="theme"]').trigger('change');
		}
		
		if ($(this).children('.cis-header').attr('clicked') == 'true')
		{
			if ($(this).children('.cis-toggle').css('display') == 'none')	// If closed.
			{
				$(this).find('.cis-header .cis-openclose').css('background-image', 'url(../plugins/system/completeimagestyles/completeimagestyles/images/minus-light.png)');
			}
			else		// If open.
			{
				$(this).find('.cis-header .cis-openclose').css('background-image', 'url(../plugins/system/completeimagestyles/completeimagestyles/images/plus-light.png)');
			}
			$(this).children('.cis-toggle').slideToggle(400);
			$(this).children('.cis-header').removeAttr('clicked');
		}
	});
	
	// Apply / Save.
	// Override the default Joomla buttons for Joomla 2.5.
	$('#toolbar-apply a').attr('onclick', '');
	$('#toolbar-save a').attr('onclick', '');
	
	$("#toolbar-apply a").click(function() {
		cisSerializeStyle();
		$('#jform_params_styles').val(JSON.stringify(styles));	// Save all styles.
		Joomla.submitbutton('plugin.apply');	// <------------- CRITICAL ACTION.
	});
	
	$("#toolbar-save a").click(function() {
		cisSerializeStyle();
		$('#jform_params_styles').val(JSON.stringify(styles));	// Save all styles.
		Joomla.submitbutton('plugin.save');	// <------------- CRITICAL ACTION.
	});
	
	// Override the default Joomla buttons for Joomla 3.x.
	$('#toolbar-apply button').attr('onclick', '');
	$('#toolbar-save button').attr('onclick', '');
	
	$("#toolbar-apply button").click(function() {
		cisSerializeStyle();
		$('#jform_params_styles').val(JSON.stringify(styles));	// Save all styles.
		Joomla.submitbutton('plugin.apply');	// <------------- CRITICAL ACTION.
	});
	
	$("#toolbar-save button").click(function() {
		cisSerializeStyle();
		$('#jform_params_styles').val(JSON.stringify(styles));	// Save all styles.
		Joomla.submitbutton('plugin.save');	// <------------- CRITICAL ACTION.
	});
	
	
	// Reset the fields of the specific style (appearance and values).
	function cisResetAllSubStyles(i) {
		// Reset the text fields.
		$('#cis-style-'+i+' .cis-substyle').find('input[type="text"]').each(function() {
			$(this).val($(this).prop("defaultValue"));
		});

		// Reset the select field.
		$('#cis-style-'+i+' .cis-substyle').find('select.cis-switch').each(function() {
			$(this).children('option[selected="selected"]').attr("selected",true);
			$(this).children('option[value="0"]').attr("selected",true);
			if ($(this).val()=='0')
			{
				$(this).removeClass('cis-red');
				$(this).removeClass('cis-green');
				$(this).addClass('cis-red');
			}
			if ($(this).val()=='1')
			{
				$(this).removeClass('cis-red');
				$(this).removeClass('cis-green');
				$(this).addClass('cis-green');
			}
		});
	};
	
	// Refresh the fields of the specific style (only the appearance).
	function cisRefreshAppearanceOfAllSubStyles(i) {
		// Stylize the select field.
		$('#cis-style-'+i+' > .cis-toggle').find('select.cis-switch').each(function() {
			if ($(this).val()=='0')
			{
				$(this).removeClass('cis-red');
				$(this).removeClass('cis-green');
				$(this).addClass('cis-red');
			}
			if ($(this).val()=='1')
			{
				$(this).removeClass('cis-red');
				$(this).removeClass('cis-green');
				$(this).addClass('cis-green');
			}
		});
		
		// Show if the sub style is enabled or disabled.
		$('#cis-style-'+i+' .cis-substyle').each(function(index, element) {
			cisSubStyleEnabledLabel($(this));
		});
	};
	
	// Refresh the fields of all the styles (only the appearance).
	function cisRefreshAppearanceOfStyle(i) {
		// Stylize the select field.
		$('#cis-style-'+i).find('select.cis-switch').each(function() {
			if ($(this).val()=='0')
			{
				$(this).removeClass('cis-red');
				$(this).removeClass('cis-green');
				$(this).addClass('cis-red');
			}
			if ($(this).val()=='1')
			{
				$(this).removeClass('cis-red');
				$(this).removeClass('cis-green');
				$(this).addClass('cis-green');
			}
		});
		
		// Show if the sub style is enabled or disabled.
		$('#cis-style-'+i+' .cis-substyle').each(function(index, element) {
			cisSubStyleEnabledLabel($(this));
		});
	};
	
	// Show if the sub style is enabled or disabled.
	function cisSubStyleEnabledLabel(substyle) {
		//var parent = $(this).closest('.cis-substyle');
		//substyle.find('.cis-sub-header span').text('(Disabled)');
		substyle.find('.cis-sub-header .cis-onoff').css('background-image', 'url(../plugins/system/completeimagestyles/completeimagestyles/images/off.png)')
		substyle.find('.cis-switch').each(function(index, element) {
			if ($(this).val() == '1')
			{
				//substyle.find('.cis-sub-header span').text('(Enabled)');
				substyle.find('.cis-sub-header .cis-onoff').css('background-image', 'url(../plugins/system/completeimagestyles/completeimagestyles/images/on.png)');
			}
		});
	};
	
	// Deserialize specific style.
	function cisDeserializeStyle(i) {
		if (typeof i === 'undefined')
			var i = $('.cis-style > .cis-toggle').parent().attr('id').replace('cis-style-', '');
		var temp = styles[i];
		if (temp != undefined)
		{
			$.each(temp, function(index, value) { 
				// Text field.
				$('#cis-style-'+i).find("input:text[name="+index+"]").val(value);
				// Select field.
				$('#cis-style-'+i).find("select[name="+index+"]").val(value);
				// Textarea field.
				$('#cis-style-'+i).find("textarea[name="+index+"]").val(value.replace(new RegExp('%%','g'), '\r\n'));	// Convert '%%' to new lines.
			});
			
			cisRefreshAppearanceOfAllSubStyles(i);
		}
	};
	
	// Serialize specific style.
	function cisSerializeStyle(i) {
		if (typeof i === 'undefined')
			var i = $('.cis-style > .cis-toggle').parent().attr('id').replace('cis-style-', '');
		var temp = {};

		/* ********************************************************************************************************************** */
		/* This is the old way of gathering the values from the fields. This code gathers all the fields, used ones and not used. */
		/* ********************************************************************************************************************** */
		/*$('#cis-style-'+i+' input, #cis-style-'+i+' select, #cis-style-'+i+' textarea').not('#jform_params_style_'+i).each(function() {
			if ($(this).attr('name') != undefined)
			{
				//temp[$(this).attr('name')] = $(this).attr('value');
				temp[$(this).attr('name')] = $(this).val();;
			}
		});*/
		/* **************************************************************************************************** */
		/* This is the new way of gathering the values from the fields. This code gathers only the used fields. */
		/* **************************************************************************************************** */
		// First: serialize the common fields at the start.
		$('#cis-style-'+i+' > .cis-field, #cis-style-'+i+' > .cis-toggle > .cis-field').find('input, select, textarea').not('#jform_params_style_'+i).each(function() {
			if ($(this).attr('name') != undefined)
			{
				temp[$(this).attr('name')] = $(this).val().replace(new RegExp('\r?\n','g'), '%%');	// Convert new lines to '%%'.
			}
		});
		// Second: serialize only the styles that are enabled.
		$('#cis-style-'+i+' .cis-substyle').each(function(index, element) {
			var $enabled = false;
			$(this).find('select').each(function(index, element) {
				if ($(this).val() == "1")
					$enabled = true;
			});
			if ($enabled) {
				$(this).find('input, select, textarea').not('#jform_params_style_'+i).each(function() {
					if ($(this).attr('name') != undefined)
					{
						temp[$(this).attr('name')] = $(this).val().replace(new RegExp('\r?\n','g'), '%%');	// Convert new lines to '%%'.
					}
				});
			}
		});
		// Exception: serialize the Strech style if it is disabled or not.
		$('select[name=stretch]').not('#jform_params_style_'+i).each(function() {
			if ($(this).attr('name') != undefined)
			{
				temp[$(this).attr('name')] = $(this).val().replace(new RegExp('\r?\n','g'), '%%');	// Convert new lines to '%%'.
			}
		});
		// Last: serialize the Transition style.
		$('#cis-style-'+i+' .cis-substyle:last-child').find('input, select, textarea').not('#jform_params_style_'+i).each(function(index, element) {
			if ($(this).attr('name') != undefined)
			{
				temp[$(this).attr('name')] = $(this).val();
			}
		});

		styles[i] = temp;
	};
});