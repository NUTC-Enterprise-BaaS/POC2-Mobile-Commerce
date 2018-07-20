// JavaScript Document.
var cis_debug = false;
var cis_run_once = false;	// Run only once.

/* **************** */
/* Initial Function */
/* **************** */
function completeImageStyles(styles) 
{
	styles = JSON.parse(styles);	// Convert from JSON to array.
	if (cis_debug) 
	{
		if (styles.name != undefined)
			console.log("Style: "+styles.name);
		if (styles.css_selectors != undefined)
			console.log("CSS Selectors: "+styles.css_selectors);
		console.log(styles);
	}
	
	cisOnLoad(styles);
}

/* ************* */
/* OnLoad Images */
/* ************* */
function cisOnLoad(styles) 
{
	var selector = '';
	if (styles.name != '')
	{
		selector = '.'+styles.name;
	}
	if (styles.css_selectors != '')
	{
		if (styles.name != '')
			selector += ', ';
		selector += styles.css_selectors;
	}

	jQuery(selector).imagesLoaded()
	.always(function(instance) {
		if (cis_debug) 
		{
			console.log('CIS: Image loading is completed. Results:');
			console.log(instance);
		}
	})
	.done(function(instance) {
		if (cis_debug) 
		{
			console.log('CIS: All images successfully loaded.');
		}
	})
	.fail(function() {
		if (cis_debug) 
		{
			console.log('CIS: All images loaded, at least one is broken.');
		}
	})
	.progress(function(instance, image) {
		if (cis_debug) 
		{
			var result = image.isLoaded ? 'loaded' : 'broken';
			console.log( 'CIS: Image "'+image.img.src+'" is '+result);
		}

		if (image.isLoaded)
		{
			cisSimpleImageToStyledImage(image.img, styles);
		}
	});
}

/* **************************** */
/* Simple Image To Styled Image */
/* **************************** */
function cisSimpleImageToStyledImage(cis_simple_image, styles) 
{
	/* ************** */
	/* Initialization */
	/* ************** */
	if (styles == undefined)
		styles = new Object();
	if (styles.name == undefined)
		styles.name = 'cis-'+Math.random().toString(36).substring(6);
	
	/* ************ */
	/* Styled Image */
	/* ************ */
	var cis_styled_image = jQuery('<div></div>');
	
	// Apply the default properties.
	cis_styled_image.addClass('cis-styled-image');
	cis_styled_image.addClass(styles.name);
	cis_styled_image.css('display', 'inline-block');
	cis_styled_image.css('position', 'relative');
	cis_styled_image.css('margin', '0px');
	cis_styled_image.css('padding', '0px');
	/*cis_styled_image.css('background-color', '#FFF');*/
	cis_styled_image.css('background-repeat', 'no-repeat');
	cis_styled_image.css('background-position', 'center');
	
	// Special case for background size.
	if (!cis_run_once) 
	{
		var css = '';
		css += '.cis-styled-image';
		css += ' { \r\n';
		css += 'background-size:100% 100%; \r\n';
		css += '} \r\n\r\n';
		jQuery('head').append('<style>'+css+'</style>');
		cis_run_once = true;
	}
	
	// Get the natural size of the image.
	var cis_image_natural_width = cis_simple_image.naturalWidth;
	var cis_image_natural_height = cis_simple_image.naturalHeight;
	
	// Get the size of the image in pixels.
	// But check if width or height are zero. If yes then this possibly means that the image is contained in a element with display=none.
	if (parseInt(jQuery(cis_simple_image).css('width'))==0 || parseInt(jQuery(cis_simple_image).css('height'))==0)	// If image width or height are zero then...
	{
		var temp_element = jQuery(cis_simple_image).clone().attr("id", false).css({visibility:"hidden", display:"block", position:"absolute", top: "-5000"});		// Clone the image.
		jQuery("body").append(temp_element);	// Add it as part of the webpage.

		cis_styled_image.css('width', temp_element.css('width'));
		cis_styled_image.css('height', temp_element.css('height'));
		
		temp_element.remove(); // Remove the cloned image.
	}
	else	// If image width or height are NOT zero then...
	{
		// Get the size of the image in pixels.
		cis_styled_image.css('width', jQuery(cis_simple_image).css('width'));
		cis_styled_image.css('height', jQuery(cis_simple_image).css('height'));
	}
	cis_styled_image.css('max-width', jQuery(cis_simple_image).css('max-width'));
	cis_styled_image.css('max-height', jQuery(cis_simple_image).css('max-height'));
	
	// Width '%'.
	if (jQuery(cis_simple_image).attr('width') && jQuery(cis_simple_image).attr('width').indexOf("%") > -1)
	{
		cis_styled_image.css('width', jQuery(cis_simple_image).attr('width'));
	}
	if (cis_simple_image.style.width && cis_simple_image.style.width.indexOf("%") > -1)
	{
		cis_styled_image.css('width', cis_simple_image.style.width);
	}
	// Height '%' (of the natural height).
	if (jQuery(cis_simple_image).attr('height') && jQuery(cis_simple_image).attr('height').indexOf("%") > -1)
	{
		cis_styled_image.css('height', parseFloat(jQuery(cis_simple_image).attr('height'))/100.0*cis_image_natural_height);
	}
	if (cis_simple_image.style.height && cis_simple_image.style.height.indexOf("%") > -1)
	{
		cis_styled_image.css('height', parseFloat(cis_simple_image.style.height)/100.0*cis_image_natural_height);
	}
	// Height 'auto'.
	if (jQuery(cis_simple_image).attr('height') && jQuery(cis_simple_image).attr('height') == 'auto')
	{
		cis_styled_image.css('height', cis_image_natural_height*parseInt(jQuery(cis_simple_image).css('width'))/cis_image_natural_width);
	}
	if (cis_simple_image.style.height && cis_simple_image.style.height == 'auto')
	{
		cis_styled_image.css('height', cis_image_natural_height*parseInt(jQuery(cis_simple_image).css('width'))/cis_image_natural_width);
	}
	
	// Get the margin of the image.
	cis_styled_image.css('margin-top', jQuery(cis_simple_image).css('margin-top'));
	cis_styled_image.css('margin-right', jQuery(cis_simple_image).css('margin-right'));
	cis_styled_image.css('margin-bottom', jQuery(cis_simple_image).css('margin-bottom'));
	cis_styled_image.css('margin-left', jQuery(cis_simple_image).css('margin-left'));
	
	// Inherit properties from the simple image.
	cis_styled_image.addClass(jQuery(cis_simple_image).attr('class'));
	cis_styled_image.css('background-image', 'url('+jQuery(cis_simple_image).attr('src')+')');
	cis_styled_image.attr('title', jQuery(cis_simple_image).attr('title'));
	
	// Inherit the alignment from the simple image.
	// Check the align attribute and convert it to float.
	if (jQuery(cis_simple_image).attr('align')=='left' || jQuery(cis_simple_image).attr('align')=='right')
		cis_styled_image.css('float', jQuery(cis_simple_image).attr('align'));
	// Check the float property.
	cis_styled_image.css('float', jQuery(cis_simple_image).css('float'));
	// Check special case for centering.
	if (jQuery(cis_simple_image).css('display')=='block' && cis_simple_image.style.marginLeft=='auto' && cis_simple_image.style.marginRight=='auto')
	{
		cis_styled_image.css('display', jQuery(cis_simple_image).css('display'));
		cis_styled_image.css('margin-left', cis_simple_image.style.marginLeft);
		cis_styled_image.css('margin-right', cis_simple_image.style.marginRight);
	}
	
	/* ****** */
	/* Events */
	/* ****** */
	jQuery(window).resize(function() {
		cisSetStyledImageSize(cis_simple_image, cis_styled_image);
	});
	
	/* ********************** */
	/* Temporary Simple Image */
	/* ********************** */
	var cis_temp_simple_image = jQuery(cis_simple_image).clone();
	cis_temp_simple_image.addClass('cis-image');
	cis_temp_simple_image.appendTo(cis_styled_image);

	/* ****** */
	/* Styles */
	/* ****** */
	// Apply the styles.
	if (styles.border_normal == true || styles.border_hover == true)
		cisBorderStyle(cis_styled_image, styles);
	if (styles.cutout_normal == true || styles.cutout_hover == true)
		cisCutoutStyle(cis_styled_image, styles);
	if (styles.curled_corners_normal == true)
		cisCurledCornersStyle(cis_styled_image, styles);
	if (styles.double_outlined_normal == true || styles.double_outlined_hover == true)
		cisDoubleOutlinedStyle(cis_styled_image, styles);
	if (styles.embossed_normal == true || styles.embossed_hover == true)
		cisEmbossedStyle(cis_styled_image, styles);
	if (styles.external_caption_normal == true)
		cisExternalCaptionStyle(cis_styled_image, styles);
	if (styles.float_normal == true)
		cisFloatStyle(cis_styled_image, styles);
	if (styles.glowing_normal == true || styles.glowing_hover == true)
		cisGlowingStyle(cis_styled_image, styles);
	if (styles.grayscale_normal == true || styles.grayscale_hover == true)
		cisGrayscaleStyle(cis_styled_image, styles);
	if (styles.horizontal_curve_normal == true)
		cisHorizontalCurveStyle(cis_styled_image, styles);
	if (styles.internal_caption_normal == true)
		cisInternalCaptionStyle(cis_styled_image, styles);
	if (styles.lifted_corners_normal == true)
		cisLiftedCornersStyle(cis_styled_image, styles);
	if (styles.margin_normal == true || styles.margin_hover == true)
		cisMarginStyle(cis_styled_image, styles);
	if (styles.opacity_normal == true || styles.opacity_hover == true)
		cisOpacityStyle(cis_styled_image, styles);
	if (styles.perspective_normal == true)
		cisPerspectiveStyle(cis_styled_image, styles);
	if (styles.pop_hover == true)
		cisPopStyle(cis_styled_image, styles);
	if (styles.raised_box_normal == true || styles.raised_box_hover == true)
		cisRaisedBoxStyle(cis_styled_image, styles);
	if (styles.reflection_normal == true)
		cisReflectionStyle(cis_styled_image, styles);
	if (styles.rotation_normal == true || styles.rotation_hover == true)
		cisRotationStyle(cis_styled_image, styles);
	if (styles.rounded_corners_normal == true || styles.rounded_corners_hover == true)
		cisRoundedCornersStyle(cis_styled_image, styles);
	if (styles.round_image_normal == true || styles.round_image_hover == true)
		cisRoundImageStyle(cis_styled_image, styles);
	if (styles.scale_normal == true || styles.scale_hover == true)
		cisScaleStyle(cis_styled_image, styles);
	if (styles.shadow_normal == true || styles.shadow_hover == true)
		cisShadowStyle(cis_styled_image, styles);
	if (styles.shake_normal == true || styles.shake_hover == true)
		cisShakeStyle(cis_styled_image, styles);
	if (styles.size_normal == true || styles.size_hover == true)
		cisSizeStyle(cis_styled_image, styles);
	if (styles.sliding_caption_hover == true)
		cisSlidingCaptionStyle(cis_styled_image, styles);
	if (styles.stretch == false)
		cisStretchStyle(cis_styled_image, styles);
	if (styles.tape_normal == true)
		cisTapeStyle(cis_styled_image, styles);
	if (styles.tooltip_hover == true)
		cisTooltipStyle(cis_styled_image, styles);
	if (styles.vertical_curve_normal == true)
		cisVerticalCurveStyle(cis_styled_image, styles);
	if (styles.svg_normal == true)
		cisSVGStyle(cis_styled_image, styles);
	
	// Special cases.
	cisAggregatedShadows(cis_styled_image);
	cisTransitionStyle(cis_styled_image, styles);
	if (styles.horizontal_curve_normal == true || styles.vertical_curve_normal == true || styles.lifted_corners_normal == true)	// Exception for Horizontal Curve and Vertical Curve and Lifted Corners.
	{
		var temp = jQuery('<div></div>');
		temp.css('position', 'relative');
		temp.css('z-index', '100');
		temp.append(cis_styled_image);
		cis_styled_image = temp;
	}

	/* ************ */
	/* Finalization */
	/* ************ */
	// Delete the image.
	cis_temp_simple_image.remove();	// <=======================================[ CRITICAL ACTION ]
	
	// Replace the original image with the styled image.
	jQuery(cis_simple_image).replaceWith(cis_styled_image);	// <=======================================[ CRITICAL ACTION ]
}

/* ************ */
/* Border Style */
/* ************ */
function cisBorderStyle(cis_styled_image, styles) 
{
	// Check the style properties.
	if (styles.border_normal == undefined)
		styles.border_normal = true;
	if (styles.border_size == undefined)
		styles.border_size = 2;
	if (styles.border_color == undefined)
		styles.border_color = '#333333';
	if (styles.border_style == undefined)
		styles.border_style = 'solid';
	if (styles.border_opacity == undefined)
		styles.border_opacity = 1;
	if (styles.border_hover == undefined)
		styles.border_hover = 'true';
	if (styles.border_size_hover == undefined)
		styles.border_size_hover = 2;
	if (styles.border_color_hover == undefined)
		styles.border_color_hover = '#333333';
	if (styles.border_style_hover == undefined)
		styles.border_style_hover = 'dotted';
	if (styles.border_opacity_hover == undefined)
		styles.border_opacity_hover = 1;

	// Apply the normal style.
	if (styles.border_normal == true) 
	{
		cis_styled_image.css('border', styles.border_style+' '+styles.border_size+'px rgba('+cisHexToRgb(styles.border_color)+','+styles.border_opacity+')');
	}
	
	// Apply the 'on hover' style.
	if (styles.border_hover == true) 
	{
		cis_styled_image.hover(
			function() { jQuery(this).css('border', styles.border_style_hover+' '+styles.border_size_hover+'px rgba('+cisHexToRgb(styles.border_color_hover)+','+styles.border_opacity_hover+')'); }, 
			function() { jQuery(this).css('border', styles.border_style+' '+styles.border_size+'px rgba('+cisHexToRgb(styles.border_color)+','+styles.border_opacity+')'); }
		);
	}
}

/* ************ */
/* Cutout Style */
/* ************ */
function cisCutoutStyle(cis_styled_image, styles) 
{
	// Check the style properties.
	if (styles.cutout_normal == undefined)
		styles.cutout_normal = false;
	if (styles.cutout_size == undefined)
		styles.cutout_size = 5;
	if (styles.cutout_hover == undefined)
		styles.cutout_hover = false;
	if (styles.cutout_size_hover == undefined)
		styles.cutout_size_hover = 10;
	
	// Apply the normal style.
	if (styles.cutout_normal == true) 
	{
		var shadow = '';
		if (cis_styled_image.data('shadow'))
			shadow = ', '+cis_styled_image.data('shadow');
		shadow = '1px 1px 0px rgba(255,255,255,0.2), inset '+styles.cutout_size+'px '+styles.cutout_size+'px '+styles.cutout_size+'px rgba(0,0,0,0.6), inset 1px 1px 0px rgba(0,0,0,0.6)'+shadow;
		cis_styled_image.data('shadow', shadow);
	}
	
	// Apply the 'on hover' style.
	if (styles.cutout_hover == true) 
	{
		var shadow_hover = '';
		if (cis_styled_image.data('shadow_hover'))
			shadow_hover = ', '+cis_styled_image.data('shadow_hover');
		shadow_hover = '1px 1px 0px rgba(255,255,255,0.2), inset '+styles.cutout_size_hover+'px '+styles.cutout_size_hover+'px '+styles.cutout_size_hover+'px rgba(0,0,0,0.6), inset 1px 1px 0px rgba(0,0,0,0.6)'+shadow_hover;
		cis_styled_image.data('shadow_hover', shadow_hover);
	}
}

/* ******************** */
/* Curled Corners Style */
/* ******************** */
function cisCurledCornersStyle(cis_styled_image, styles) 
{
	// Check the style properties.
	if (styles.curled_corners_normal == undefined)
		styles.curled_corners_normal = false;

	if (styles.curled_corners_normal == true) 
	{
		// Create second div.
		var cis_curled_corners = jQuery('<div></div>');
		cis_curled_corners.attr('style', cis_styled_image.attr('style'));
		cis_curled_corners.css('z-index', '10');
		cis_curled_corners.css('border-radius', '0 0 120px 120px / 0 0 12px 12px');
		cis_curled_corners.css('margin', 0);
		cis_styled_image.append(cis_curled_corners);
	
		// Apply style to the main div.
		cis_styled_image.css('background-image', 'none');
		cis_styled_image.addClass('cis-curled-corners');
		
		// Apply background property to the second div.
		var css = '';
		css += '.'+styles.name;
		css += '.cis-curled-corners > div { \r\n';
		if (styles.stretch == 0)
			css += 'background-size: auto; \r\n';
		else
			css += 'background-size: 100% 100%; \r\n';
		css += '} \r\n\r\n';
		
		// Apply styles to :before and :after elements for the second div.
		css += '.'+styles.name;
		css += '.cis-curled-corners';
		css += ':before { \r\n';
		css += 'content: ""; \r\n';
		css += 'position: absolute; \r\n';
		css += 'z-index: 0; \r\n';
		css += 'background-color: rgba(0, 0, 0, 1); \r\n';
		css += 'bottom:12px; \r\n';
		css += 'left:10px; \r\n';
		css += 'margin-left:2px; \r\n';
		css += 'margin-right:2px; \r\n';
		css += 'width:50%; \r\n';
		css += 'height:55%; \r\n';
		css += '-webkit-box-shadow:0 10px 12px rgba(0, 0, 0, 1); \r\n';
		css += '-moz-box-shadow:0 10px 12px rgba(0, 0, 0, 1); \r\n';
		css += '-o-box-shadow:0 10px 12px rgba(0, 0, 0, 1); \r\n';
		css += 'box-shadow:0 10px 12px rgba(0, 0, 0, 1); \r\n';
		css += '-webkit-transform:skew(-8deg) rotate(-3deg); \r\n';
		css += '-moz-transform:skew(-8deg) rotate(-3deg); \r\n';
		css += '-ms-transform:skew(-8deg) rotate(-3deg); \r\n';
		css += '-o-transform:skew(-8deg) rotate(-3deg); \r\n';
		css += 'transform:skew(-8deg) rotate(-3deg); \r\n';
		css += '-moz-border-radius:0 0 12px 12px / 0 0 12px 12px; \r\n';
		css += 'border-radius:0 0 12px 12px / 0 0 12px 12px; \r\n';
		css += '} \r\n\r\n';
	
		css += '.'+styles.name;
		css += '.cis-curled-corners';
		css += ':after { \r\n';	
		css += 'content: ""; \r\n';
		css += 'position: absolute; \r\n';
		css += 'z-index: 0; \r\n';
		css += 'background-color: rgba(0, 0, 0, 1); \r\n';
		css += 'bottom:12px; \r\n';
		css += 'right:10px; \r\n';
		css += 'left:auto; \r\n';
		css += 'margin-left:2px; \r\n';
		css += 'margin-right:2px; \r\n';
		css += 'width:50%; \r\n';
		css += 'height:55%; \r\n';
		css += '-webkit-box-shadow:0 10px 12px rgba(0, 0, 0, 1); \r\n';
		css += '-moz-box-shadow:0 10px 12px rgba(0, 0, 0, 1); \r\n';
		css += '-o-box-shadow:0 10px 12px rgba(0, 0, 0, 1); \r\n';
		css += 'box-shadow:0 10px 12px rgba(0, 0, 0, 1); \r\n';
		css += '-webkit-transform:skew(8deg) rotate(3deg); \r\n';
		css += '-moz-transform:skew(8deg) rotate(3deg); \r\n';
		css += '-ms-transform:skew(8deg) rotate(3deg); \r\n';
		css += '-o-transform:skew(8deg) rotate(3deg); \r\n';
		css += 'transform:skew(8deg) rotate(3deg); \r\n';
		css += '-moz-border-radius:0 0 12px 12px / 0 0 12px 12px; \r\n';
		css += 'border-radius:0 0 12px 12px / 0 0 12px 12px; \r\n';
		css += '} \r\n\r\n';
		
		jQuery('head').append('<style>'+css+'</style>');
	}
}

/* ********************* */
/* Double Outlined Style */
/* ********************* */
function cisDoubleOutlinedStyle(cis_styled_image, styles) 
{
	// Check the style properties.
	if (styles.double_outlined_normal == undefined)
		styles.double_outlined_normal = false;
	if (styles.double_outlined_inner_color == undefined)
		styles.double_outlined_inner_color = '#FFFFFF';
	if (styles.double_outlined_inner_size == undefined)
		styles.double_outlined_inner_size = 5;
	if (styles.double_outlined_inner_opacity == undefined)
		styles.double_outlined_inner_opacity = 1;
	if (styles.double_outlined_outer_color == undefined)
		styles.double_outlined_outer_color = '#000000';
	if (styles.double_outlined_outer_size == undefined)
		styles.double_outlined_outer_size = 2;
	if (styles.double_outlined_outer_opacity == undefined)
		styles.double_outlined_outer_opacity = 1;
	if (styles.double_outlined_hover == undefined)
		styles.double_outlined_hover = false;
	if (styles.double_outlined_inner_color_hover == undefined)
		styles.double_outlined_inner_color_hover = '#FFFFFF';
	if (styles.double_outlined_inner_size_hover == undefined)
		styles.double_outlined_inner_size_hover = 5;
	if (styles.double_outlined_inner_opacity_hover == undefined)
		styles.double_outlined_inner_opacity_hover = 1;
	if (styles.double_outlined_outer_color_hover == undefined)
		styles.double_outlined_outer_color_hover = '#FFFFFF';
	if (styles.double_outlined_outer_size_hover == undefined)
		styles.double_outlined_outer_size_hover = 20;
	if (styles.double_outlined_outer_opacity_hover == undefined)
		styles.double_outlined_outer_opacity_hover = 0;
	
	// Apply the normal style.
	if (styles.double_outlined_normal == true) 
	{
		cis_styled_image.css('border', 'solid '+styles.double_outlined_inner_size+'px rgba('+cisHexToRgb(styles.double_outlined_inner_color)+', '+styles.double_outlined_inner_opacity+')');
		
		var shadow = '';
		if (cis_styled_image.data('shadow'))
			shadow = ', '+cis_styled_image.data('shadow');
		shadow = '0px 0px 0px '+styles.double_outlined_outer_size+'px rgba('+cisHexToRgb(styles.double_outlined_outer_color)+', '+styles.double_outlined_outer_opacity+')'+shadow;
		cis_styled_image.data('shadow', shadow);
	}
	
	// Apply the 'on hover' style.
	if (styles.double_outlined_hover == true) 
	{
		cis_styled_image.hover(
			function() { 
				jQuery(this).css('border', 'solid '+styles.double_outlined_inner_size_hover+'px rgba('+cisHexToRgb(styles.double_outlined_inner_color_hover)+', '+styles.double_outlined_inner_opacity_hover+')');
			}, 
			function() { 
				jQuery(this).css('border', 'solid '+styles.double_outlined_inner_size+'px rgba('+cisHexToRgb(styles.double_outlined_inner_color)+', '+styles.double_outlined_inner_opacity+')');
			}
		);
		
		var shadow_hover = '';
		if (cis_styled_image.data('shadow_hover'))
			shadow_hover = ', '+cis_styled_image.data('shadow_hover');
		shadow_hover = '0px 0px 0px '+styles.double_outlined_outer_size_hover+'px rgba('+cisHexToRgb(styles.double_outlined_outer_color_hover)+', '+styles.double_outlined_outer_opacity_hover+')'+shadow_hover;
		cis_styled_image.data('shadow_hover', shadow_hover);
	}
}

/* ************** */
/* Embossed Style */
/* ************** */
function cisEmbossedStyle(cis_styled_image, styles) 
{
	// Check the style properties.
	if (styles.embossed_normal == undefined)
		styles.embossed_normal = false;
	if (styles.embossed_size == undefined)
		styles.embossed_size = 5;
	if (styles.embossed_hover == undefined)
		styles.embossed_hover = false;
	if (styles.embossed_size_hover == undefined)
		styles.embossed_size_hover = 10;
	
	// Apply the normal style.
	if (styles.embossed_normal == true) 
	{
		var shadow = '';
		if (cis_styled_image.data('shadow'))
			shadow = ', '+cis_styled_image.data('shadow');
		shadow = 'inset 0px 0px 2px rgba(0,0,0,.8),inset '+styles.embossed_size+'px '+styles.embossed_size+'px '+styles.embossed_size+'px rgba(255,255,255,.5),inset -'+styles.embossed_size+'px -'+styles.embossed_size+'px '+styles.embossed_size+'px rgba(0,0,0,.6)'+shadow;
		cis_styled_image.data('shadow', shadow);
	}
	
	// Apply the 'on hover' style.
	if (styles.embossed_hover == true) 
	{
		var shadow_hover = '';
		if (cis_styled_image.data('shadow_hover'))
			shadow_hover = ', '+cis_styled_image.data('shadow_hover');
		shadow_hover = 'inset 0px 0px 2px rgba(0,0,0,.8),inset '+styles.embossed_size_hover+'px '+styles.embossed_size_hover+'px '+styles.embossed_size_hover+'px rgba(255,255,255,.5),inset -'+styles.embossed_size_hover+'px -'+styles.embossed_size_hover+'px '+styles.embossed_size_hover+'px rgba(0,0,0,.6)'+shadow_hover;
		cis_styled_image.data('shadow_hover', shadow_hover);
	}
}

/* **************** */
/* External Caption */
/* **************** */
function cisExternalCaptionStyle(cis_styled_image, styles) 
{
	// Check the style properties.
	if (styles.external_caption_normal == undefined)
		styles.external_caption_normal = false;
	if (styles.external_caption_background_color == undefined)
		styles.external_caption_background_color = "#F6F6F6";
	if (styles.external_caption_font_color == undefined)
		styles.external_caption_font_color = "#333333";
	if (styles.external_caption_font_size == undefined)
		styles.external_caption_font_size = 12;
	if (styles.external_caption_space == undefined)
		styles.external_caption_space = 10;
	
	// Apply the normal style.
	if (styles.external_caption_normal == true)
	{
		// Apply styles to the main div.
		cis_styled_image.css('background-origin', 'content-box');
		cis_styled_image.css('background-color', styles.external_caption_background_color);
		cis_styled_image.css('padding', styles.external_caption_space+'px '+styles.external_caption_space+'px '+(2*parseInt(styles.external_caption_space)+parseInt(styles.external_caption_font_size))+'px '+styles.external_caption_space+'px');
	
		// Create second div.
		if (cis_styled_image.find('img').attr('title') != undefined)
		{
			var cis_external_caption = jQuery('<p>'+cis_styled_image.find('img').attr('title')+'</p>');
			cis_external_caption.addClass("cis-external-caption");
			cis_external_caption.css('position', 'absolute');
			cis_external_caption.css('overflow', 'hidden');
			cis_external_caption.css('left', '0px');
			cis_external_caption.css('bottom', styles.external_caption_space+'px');
			cis_external_caption.css('width', '100%');
			cis_external_caption.css('height', (1.5*parseInt(styles.external_caption_font_size))+'px');
			cis_external_caption.css('margin', 0);
			cis_external_caption.css('padding', 0);
			cis_external_caption.css('text-align', 'center');
			cis_external_caption.css('text-decoration', 'none');
			cis_external_caption.css('color', 'rgba('+cisHexToRgb(styles.external_caption_font_color)+')');
			cis_external_caption.css('font-size', styles.external_caption_font_size+'px');
			cis_external_caption.css('line-height', (1.5*parseInt(styles.external_caption_font_size))+'px');
			cis_styled_image.append(cis_external_caption);
		}
	}
}

/* ***** */
/* Float */
/* ***** */
function cisFloatStyle(cis_styled_image, styles) 
{
	// Check the style properties.
	if (styles.float_normal == undefined)
		styles.float_normal = false;
	if (styles.float_direction == undefined)
		styles.float_direction = "left";
	
	// Apply the normal style.
	if (styles.float_normal == true)
	{
		cis_styled_image.css('float', styles.float_direction);
	}
}

/* ******* */
/* Glowing */
/* ******* */
function cisGlowingStyle(cis_styled_image, styles) 
{
	// Check the style properties.
	if (styles.glowing_normal == undefined)
		styles.glowing_normal = false;
	if (styles.glowing_size == undefined)
		styles.glowing_size = 20;
	if (styles.glowing_hover == undefined)
		styles.glowing_hover = false;
	if (styles.glowing_size_hover == undefined)
		styles.glowing_size_hover = 30;
	
	// Apply the normal style.
	if (styles.glowing_normal == true)
	{
		var shadow = '';
		if (cis_styled_image.data('shadow'))
			shadow = ', '+cis_styled_image.data('shadow');
		shadow = '0 0 '+styles.glowing_size+'px rgba(255,255,255,.6), inset 0 0 '+styles.glowing_size+'px rgba(255,255,255,1)'+shadow;
		cis_styled_image.data('shadow', shadow);
	}
	
	// Apply the 'on hover' style.
	if (styles.glowing_hover == true) 
	{
		var shadow_hover = '';
		if (cis_styled_image.data('shadow_hover'))
			shadow_hover = ', '+cis_styled_image.data('shadow_hover');
		shadow_hover = '0 0 '+styles.glowing_size_hover+'px rgba(255,255,255,.6), inset 0 0 '+styles.glowing_size_hover+'px rgba(255,255,255,1)'+shadow_hover;
		cis_styled_image.data('shadow_hover', shadow_hover);
	}
}

/* ********* */
/* Grayscale */
/* ********* */
function cisGrayscaleStyle(cis_styled_image, styles) 
{
	// Check the style properties.
	if (styles.grayscale_normal == undefined)
		styles.grayscale_normal = false;	
	if (styles.grayscale_hover == undefined)
		styles.grayscale_hover = false;	
	
	var css = '';
	// Apply the normal style.
	if (styles.grayscale_normal == true) 
	{
		css += '.'+styles.name;
		css += ' { \r\n';
		css += 'filter: url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'grayscale\'><feColorMatrix type=\'matrix\' values=\'0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0\'/></filter></svg>#grayscale"); /* Firefox 10+, Firefox on Android */ \r\n';
		css += 'filter: gray; /* IE6-9 */ \r\n';
		css += '-webkit-filter: grayscale(100%); /* Chrome 19+, Safari 6+, Safari 6+ iOS */ \r\n';
		css += '} \r\n\r\n';
	}
	else if (styles.grayscale_hover == true)
	{
		css += '.'+styles.name;
		css += ' { \r\n';
		css += 'filter: url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'grayscale\'><feColorMatrix type=\'matrix\' values=\'1 0 0 0 0, 0 1 0 0 0, 0 0 1 0 0, 0 0 0 1 0\'/></filter></svg>#grayscale"); \r\n';
		css += '-webkit-filter: grayscale(0%); \r\n';
		css += '} \r\n\r\n';		
	}
	
	// Apply the 'on hover' style.
	if (styles.grayscale_hover == true) 
	{
		css += '.'+styles.name;
		css += ':hover { \r\n';
		css += 'filter: url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'grayscale\'><feColorMatrix type=\'matrix\' values=\'0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0\'/></filter></svg>#grayscale"); /* Firefox 10+, Firefox on Android */ \r\n';
		css += 'filter: gray; /* IE6-9 */ \r\n';
		css += '-webkit-filter: grayscale(100%); /* Chrome 19+, Safari 6+, Safari 6+ iOS */ \r\n';
		css += '} \r\n\r\n';	
	}
	else if (styles.grayscale_normal == true)
	{
		css += '.'+styles.name;
		css += ':hover { \r\n';
		css += 'filter: url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'grayscale\'><feColorMatrix type=\'matrix\' values=\'1 0 0 0 0, 0 1 0 0 0, 0 0 1 0 0, 0 0 0 1 0\'/></filter></svg>#grayscale"); \r\n';
		css += '-webkit-filter: grayscale(0%); \r\n';
		css += '} \r\n\r\n';
	}
	
	if (styles.grayscale_normal == true || styles.grayscale_hover == true) 
	{
		jQuery('head').append('<style>'+css+'</style>');
	}
}

/* **************** */
/* Horizontal Curve */
/* **************** */
function cisHorizontalCurveStyle(cis_styled_image, styles) 
{
	// Check the style properties.
	if (styles.horizontal_curve_normal == undefined)
		styles.horizontal_curve_normal = false;
	if (styles.horizontal_curve_size == undefined)
		styles.horizontal_curve_size = 15;

	// Apply the normal style.
	if (styles.horizontal_curve_normal == true)
	{
		// Create second div.
		var cis_horizontal_curve = jQuery('<div></div>');
		cis_horizontal_curve.addClass('cis-horizontal-curve');
		cis_styled_image.append(cis_horizontal_curve);
		
		// Apply styles to :after element for the second div.
		var css = '';
		css += '.'+styles.name+' ';
		css += '.cis-horizontal-curve {';
		css += 'position: absolute; \r\n';
		css += 'z-index: -1; \r\n';
		css += 'top:0px; \r\n';
		css += 'bottom:0px; \r\n';
		css += 'left:10px; \r\n';
		css += 'right:10px; \r\n';
		css += '-webkit-box-shadow:0 '+((styles.horizontal_curve_size)/15)+'px '+styles.horizontal_curve_size+'px rgba(0,0,0,1); \r\n';
		css += '-moz-box-shadow:0 '+((styles.horizontal_curve_size)/15)+'px '+styles.horizontal_curve_size+'px rgba(0,0,0,1); \r\n';
		css += '-o-box-shadow:0 '+((styles.horizontal_curve_size)/15)+'px '+styles.horizontal_curve_size+'px rgba(0,0,0,1); \r\n';
		css += 'box-shadow:0 '+((styles.horizontal_curve_size)/15)+'px '+styles.horizontal_curve_size+'px rgba(0,0,0,1); \r\n';
		css += '-webkit-border-radius:30%/4%; \r\n';
		css += '-moz-border-radius:30%/4%; \r\n';
		css += 'border-radius:30%/4%;  \r\n';
		css += '} \r\n\r\n';
		jQuery('head').append('<style>'+css+'</style>');
	}
}

/* **************** */
/* Internal Caption */
/* **************** */
function cisInternalCaptionStyle(cis_styled_image, styles) 
{
	// Check the style properties.
	if (styles.internal_caption_normal == undefined)
		styles.internal_caption_normal = false;
	if (styles.internal_caption_background_color == undefined)
		styles.internal_caption_background_color = '#000000';
	if (styles.internal_caption_font_color == undefined)
		styles.internal_caption_font_color = '#FFFFFF';
	if (styles.internal_caption_font_size == undefined)
		styles.internal_caption_font_size = 10;
	if (styles.internal_caption_position == undefined)
		styles.internal_caption_position = 'bottom';	// Values: bottom, top.

	// Apply the normal style.
	if (styles.internal_caption_normal == true && cis_styled_image.find('img').attr('title')!=undefined) 
	{
		cis_styled_image.attr('title', '');
		
		// Create second div.
		var cis_internal_caption = jQuery('<p></p>');
		cis_internal_caption.addClass('cis-internal-caption');
		cis_internal_caption.html(cis_styled_image.find('img').attr('title'));
		cis_styled_image.append(cis_internal_caption);
		
		// Apply styles to :after element for the second div.
		var css = '';
		css += '.'+styles.name+' ';
		css += '.cis-internal-caption';
		css += ' { \r\n';
		css += 'z-index: 100; \r\n';
		css += 'position: absolute; \r\n';
		css += 'display: block; \r\n';
		css += 'width: auto; \r\n';
		css += 'left: 10px; \r\n';
		css += 'right: 10px; \r\n';
		if (styles.internal_caption_position == 'bottom')
			css += 'bottom: 10px; \r\n';
		else
			css += 'top: 10px; \r\n';
		css += 'background-color:rgba('+cisHexToRgb(styles.internal_caption_background_color)+', 1); \r\n';
		css += 'font-size:'+styles.internal_caption_font_size+'px; \r\n';
		css += 'line-height:'+(styles.internal_caption_font_size*1.2)+'px; \r\n';
		css += 'color:rgba('+cisHexToRgb(styles.internal_caption_font_color)+', 1); \r\n';
		css += 'text-decoration:none; \r\n';
		css += 'text-align:center; \r\n';
		css += 'padding:5px; \r\n';
		css += 'margin:0px !important; \r\n';
		css += 'filter:alpha(opacity=75); \r\n';
		css += 'opacity: 0.75; \r\n';
		css += '-moz-opacity:0.75; \r\n';
		css += '-khtml-opacity: 0.75; \r\n';
		css += '-webkit-border-radius: 10px; \r\n';
		css += '-moz-border-radius: 10px; \r\n';
		css += 'border-radius: 10px; \r\n';
		css += '} \r\n\r\n';
		jQuery('head').append('<style>'+css+'</style>');
	}
}

/* ************** */
/* Lifted Corners */
/* ************** */
function cisLiftedCornersStyle(cis_styled_image, styles) 
{
	// Check the style properties.
	if (styles.lifted_corners_normal == undefined)
		styles.lifted_corners_normal = false;
	
	// Apply the normal style.
	if (styles.lifted_corners_normal == true)
	{
		// Apply style to the main div.
		cis_styled_image.addClass('cis-lifted-corners');
		cis_styled_image.css('box-shadow', '0px 1px 4px rgba(0, 0, 0, 0.5), 0 0 40px rgba(0, 0, 0, 0.1) inset');
		
		// Apply styles to :before element for the main div.
		var css = '';
		css += '.'+styles.name;
		css += '.cis-lifted-corners';
		css += ':before { \r\n';
		css += 'content: ""; \r\n';
		css += 'position: absolute; \r\n';
		css += 'z-index: -1; \r\n';
		css += 'bottom: 15px; \r\n';
		css += 'right: 10px; \r\n';
		css += 'width: 50%; \r\n';
		css += 'height: 20%; \r\n';
		css += 'background: #000; \r\n';
		css += '-webkit-box-shadow: 0 15px 10px rgba(0, 0, 0, 0.8); \r\n';
		css += '-moz-box-shadow: 0 15px 10px rgba(0, 0, 0, 0.8); \r\n';
		css += '-o-box-shadow: 0 15px 10px rgba(0, 0, 0, 0.8); \r\n';
		css += 'box-shadow: 0 15px 10px rgba(0, 0, 0, 0.8); \r\n';
		css += '-webkit-transform: rotate(4deg); \r\n';
		css += '-moz-transform: rotate(4deg); \r\n';
		css += '-ms-transform: rotate(4deg); \r\n';
		css += '-o-transform: rotate(4deg); \r\n';
		css += 'transform: rotate(4deg); \r\n';
		css += '} \r\n\r\n';
		
		// Apply styles to :after element for the main div.
		css += '.'+styles.name;
		css += '.cis-lifted-corners';
		css += ':after { \r\n';
		css += 'content: ""; \r\n';
		css += 'position: absolute; \r\n';
		css += 'z-index: -1; \r\n';
		css += 'bottom: 15px; \r\n';
		css += 'left: 10px; \r\n';
		css += 'width: 50%; \r\n';
		css += 'height: 20%; \r\n';
		css += 'background: #000; \r\n';
		css += '-webkit-box-shadow: 0 15px 10px rgba(0, 0, 0, 0.8); \r\n';
		css += '-moz-box-shadow: 0 15px 10px rgba(0, 0, 0, 0.8); \r\n';
		css += '-o-box-shadow: 0 15px 10px rgba(0, 0, 0, 0.8); \r\n';
		css += 'box-shadow: 0 15px 10px rgba(0, 0, 0, 0.8); \r\n';
		css += '-webkit-transform: rotate(-4deg); \r\n';
		css += '-moz-transform: rotate(-4deg); \r\n';
		css += '-ms-transform: rotate(-4deg); \r\n';
		css += '-o-transform: rotate(-4deg); \r\n';
		css += 'transform: rotate(-4deg); \r\n';
		css += '} \r\n\r\n';
		
		jQuery('head').append('<style>'+css+'</style>');
	}
}

/* ****** */
/* Margin */
/* ****** */
function cisMarginStyle(cis_styled_image, styles) 
{
	// (Special check for Margin in order not to affect the values coming from the document editor.)
	
	// Check the style properties.
	if (styles.margin_normal == undefined)
		styles.margin_normal = false;
	if (styles.margin_hover == undefined)
		styles.margin_hover = false;
		
	
	// Apply the normal style.
	if (styles.margin_normal == true)
	{
		if (styles.margin_top != undefined && styles.margin_top != '')
			cis_styled_image.css('margin-top', styles.margin_top+'px');
		if (styles.margin_right != undefined && styles.margin_right != '')
			cis_styled_image.css('margin-right', styles.margin_right+'px');
		if (styles.margin_bottom != undefined && styles.margin_bottom != '')
			cis_styled_image.css('margin-bottom', styles.margin_bottom+'px');
		if (styles.margin_left != undefined && styles.margin_left != '')
			cis_styled_image.css('margin-left', styles.margin_left+'px');
	}
	
	// Apply the 'on hover' style.
	if (styles.margin_hover == true) 
	{
		cis_styled_image.hover(
			function() { 
				if (styles.margin_top_hover != undefined && styles.margin_top_hover != '')
					jQuery(this).css('margin-top', styles.margin_top_hover+'px');
				if (styles.margin_right_hover != undefined && styles.margin_right_hover != '')
					jQuery(this).css('margin-right', styles.margin_right_hover+'px');
				if (styles.margin_bottom_hover != undefined && styles.margin_bottom_hover != '')
					jQuery(this).css('margin-bottom', styles.margin_bottom_hover+'px');
				if (styles.margin_left_hover != undefined && styles.margin_left_hover != '')
					jQuery(this).css('margin-left', styles.margin_left_hover+'px');
			}, 
			function() { 
				if (styles.margin_top != undefined && styles.margin_top != '')
					cis_styled_image.css('margin-top', styles.margin_top+'px');
				if (styles.margin_right != undefined && styles.margin_right != '')
					cis_styled_image.css('margin-right', styles.margin_right+'px');
				if (styles.margin_bottom != undefined && styles.margin_bottom != '')
					cis_styled_image.css('margin-bottom', styles.margin_bottom+'px');
				if (styles.margin_left != undefined && styles.margin_left != '')
					cis_styled_image.css('margin-left', styles.margin_left+'px');
			}
		);
	}
}

/* ******* */
/* Opacity */
/* ******* */
function cisOpacityStyle(cis_styled_image, styles) 
{
	// Check the style properties.
	if (styles.opacity_normal == undefined)
		styles.opacity_normal = false;
	if (styles.opacity_size == undefined)
		styles.opacity_size = 0.7;
	if (styles.opacity_hover == undefined)
		styles.opacity_hover = false;
	if (styles.opacity_size_hover == undefined)
		styles.opacity_size_hover = 1;	
	
	// Apply the normal style.
	if (styles.opacity_normal == true)
	{
		cis_styled_image.css('opacity', styles.opacity_size);
	}
	
	// Apply the 'on hover' style.
	if (styles.opacity_hover == true) 
	{
		cis_styled_image.hover(
			function() { 
				jQuery(this).css('opacity', styles.opacity_size_hover);
			}, 
			function() { 
				jQuery(this).css('opacity', styles.opacity_size);
			}
		);
	}
}

/* *********** */
/* Perspective */
/* *********** */
function cisPerspectiveStyle(cis_styled_image, styles) 
{
	// Check the style properties.
	if (styles.perspective_normal == undefined)
		styles.perspective_normal = false;
	if (styles.perspective_direction == undefined)
		styles.perspective_direction = 'right';
	
	// Apply the normal style.
	if (styles.perspective_normal == true)
	{
		// Create second div.
		var cis_perspective = jQuery('<div class="cis-perspective"></div>');
		cis_perspective.attr('style', cis_styled_image.attr('style'));
		cis_perspective.css('z-index', '10');
		cis_perspective.css('margin', 0);
		cis_styled_image.append(cis_perspective);
	
		// Apply style to the main div.
		cis_styled_image.css('background-image', 'none');
		cis_styled_image.css('border', 'none');
		
		// Apply background property to the second div.
		var css = '';
		css += '.'+styles.name+' ';
		css += '.cis-perspective { \r\n';
		if (styles.stretch == 0)
			css += 'background-size: auto; \r\n';
		else
			css += 'background-size: 100% 100%; \r\n';
		css += '} \r\n\r\n';
		
		// Apply styles to :before element for the main div.
		css += '.'+styles.name;
		css += ':before { \r\n';
		css += 'content: ""; \r\n';
		css += 'position: absolute; \r\n';
		css += 'z-index: 0; \r\n';

		if (styles.perspective_direction=="right") 
		{
			css += 'background-size:100% 100%; \r\n';
			css += 'right:'+parseInt(cis_styled_image.css('height'))/parseInt(cis_styled_image.css('width'))*120+'px; \r\n';
			css += 'bottom:5px; \r\n';
			css += 'width:50%; \r\n';
			css += 'height:35%; \r\n';
			css += 'max-width:200px; \r\n';
			css += '-webkit-box-shadow:'+parseInt(cis_styled_image.css('height'))/parseInt(cis_styled_image.css('width'))*120+'px 0 8px rgba(0, 0, 0, 0.4); \r\n';
			css += '-moz-box-shadow:'+parseInt(cis_styled_image.css('height'))/parseInt(cis_styled_image.css('width'))*120+'px 0 8px rgba(0, 0, 0, 0.4); \r\n';
			css += '-o-box-shadow:'+parseInt(cis_styled_image.css('height'))/parseInt(cis_styled_image.css('width'))*120+'px 0 8px rgba(0, 0, 0, 0.4); \r\n';
			css += 'box-shadow:'+parseInt(cis_styled_image.css('height'))/parseInt(cis_styled_image.css('width'))*120+'px 0 8px rgba(0, 0, 0, 0.4); \r\n';
			css += '-webkit-transform:skew(-30deg); \r\n';
			css += '-moz-transform:skew(-30deg); \r\n';
			css += '-ms-transform:skew(-30deg); \r\n';
			css += '-o-transform:skew(-30deg); \r\n';
			css += 'transform:skew(-30deg); \r\n';
			css += '-webkit-transform-origin:0 100%; \r\n';
			css += '-moz-transform-origin:0 100%; \r\n';
			css += '-ms-transform-origin:0 100%; \r\n';
			css += '-o-transform-origin:0 100%; \r\n';
			css += 'transform-origin:0 100%; \r\n';
		}
		else
		{
			css += 'left:'+parseInt(cis_styled_image.css('height'))/parseInt(cis_styled_image.css('width'))*120+'px; \r\n';
			css += 'bottom:5px; \r\n';
			css += 'width:50%; \r\n';
			css += 'height:35%; \r\n';
			css += 'max-width:200px; \r\n';
			css += '-webkit-box-shadow:-'+parseInt(cis_styled_image.css('height'))/parseInt(cis_styled_image.css('width'))*120+'px 0 8px rgba(0, 0, 0, 0.4); \r\n';
			css += '-moz-box-shadow:-'+parseInt(cis_styled_image.css('height'))/parseInt(cis_styled_image.css('width'))*120+'px 0 8px rgba(0, 0, 0, 0.4); \r\n';
			css += '-o-box-shadow:-'+parseInt(cis_styled_image.css('height'))/parseInt(cis_styled_image.css('width'))*120+'px 0 8px rgba(0, 0, 0, 0.4); \r\n';
			css += 'box-shadow:-'+parseInt(cis_styled_image.css('height'))/parseInt(cis_styled_image.css('width'))*120+'px 0 8px rgba(0, 0, 0, 0.4); \r\n';
			css += '-webkit-transform:skew(30deg); \r\n';
			css += '-moz-transform:skew(30deg); \r\n';
			css += '-ms-transform:skew(30deg); \r\n';
			css += '-o-transform:skew(30deg); \r\n';
			css += 'transform:skew(30deg); \r\n';
			css += '-webkit-transform-origin:0 100%; \r\n';
			css += '-moz-transform-origin:0 100%; \r\n';
			css += '-ms-transform-origin:0 100%; \r\n';
			css += '-o-transform-origin:0 100%; \r\n';
			css += 'transform-origin:0 100%; \r\n';
		}

		css += '} \r\n\r\n';
		jQuery('head').append('<style>'+css+'</style>');
	}
}

/* *** */
/* Pop */
/* *** */
function cisPopStyle(cis_styled_image, styles) 
{
	// Check the style properties.
	if (styles.pop_hover == undefined)
		styles.pop_hover = false;
	if (styles.pop_size_hover == undefined)
		styles.pop_size_hover = 8;	
	
	// Apply the 'on hover' style.
	if (styles.pop_hover == true) 
	{
		cis_styled_image.css('top', 0);
		
		cis_styled_image.hover(
			function() { 
				jQuery(this).css('top', -styles.pop_size_hover);
			}, 
			function() { 
				jQuery(this).css('top', 0);
			}
		);
	}
}

/* ********** */
/* Raised Box */
/* ********** */
function cisRaisedBoxStyle(cis_styled_image, styles) 
{
	// Check the style properties.
	if (styles.raised_box_normal == undefined)
		styles.raised_box_normal = false;
	if (styles.raised_box_size == undefined)
		styles.raised_box_size = 15;
	if (styles.raised_box_hover == undefined)
		styles.raised_box_hover = false;
	if (styles.raised_box_size_hover == undefined)
		styles.raised_box_size_hover = 25;	
	
	// Apply the normal style.
	if (styles.raised_box_normal == true)
	{
		var shadow = '';
		if (cis_styled_image.data('shadow'))
			shadow = ', '+cis_styled_image.data('shadow');
		shadow = '0 '+styles.raised_box_size+'px '+(2/3*styles.raised_box_size)+'px -'+(2/3*styles.raised_box_size)+'px rgba(0, 0, 0, 0.5), 0 '+(1/15*styles.raised_box_size)+'px '+(1/3*styles.raised_box_size)+'px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset'+shadow;
		cis_styled_image.data('shadow', shadow);
	}
	
	// Apply the 'on hover' style.
	if (styles.raised_box_hover == true) 
	{
		var shadow_hover = '';
		if (cis_styled_image.data('shadow_hover'))
			shadow_hover = ', '+cis_styled_image.data('shadow_hover');
		shadow_hover = '0 '+styles.raised_box_size_hover+'px '+(2/3*styles.raised_box_size_hover)+'px -'+(2/3*styles.raised_box_size_hover)+'px rgba(0, 0, 0, 0.5), 0 '+(1/15*styles.raised_box_size_hover)+'px '+(1/3*styles.raised_box_size_hover)+'px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset'+shadow_hover;
		cis_styled_image.data('shadow_hover', shadow_hover);
	}
}

/* ********** */
/* Reflection */
/* ********** */
function cisReflectionStyle(cis_styled_image, styles) 
{
	// Check the style properties.
	if (styles.reflection_normal == undefined)
		styles.reflection_normal = false;
	if (styles.reflection_size == undefined)
		styles.reflection_size = 30;
	if (styles.reflection_color == undefined)
		styles.reflection_color = '#FFFFFF';
	
	// Apply the normal style.
	if (styles.reflection_normal == true)
	{
		cis_styled_image.css('margin-bottom', '+='+styles.reflection_size);
		
		// Apply styles to :before element for the main div.	cisHexToRgb
		var css = '';
		css += '.'+styles.name;
		css += ':after { \r\n';
		css += 'content: ""; \r\n';
		css += 'position: absolute; \r\n';
		css += "width: 100%; \r\n";
		css += "height: "+styles.reflection_size+"px; \r\n";
		css += "left: 0; \r\n";
		css += "background: -moz-linear-gradient(top, rgba(0,0,0,.3) 0%, rgba("+cisHexToRgb(styles.reflection_color)+",0) 100%); \r\n";
		css += "background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, rgba(0,0,0,.3)), color-stop(100%, rgba("+cisHexToRgb(styles.reflection_color)+",0))); \r\n";
		css += "background: linear-gradient(top, rgba(0,0,0,.3) 0%, rgba("+cisHexToRgb(styles.reflection_color)+",0) 100%); \r\n";

		// Exception for Rounded Corners style.
		if (styles.rounded_corners_normal == true) 
		{
			css += "-webkit-border-top-left-radius: "+styles.rounded_corners_size+"px; \r\n";
			css += "-webkit-border-top-right-radius: "+styles.rounded_corners_size+"px; \r\n";
			css += "-moz-border-radius-topleft: "+styles.rounded_corners_size+"px; \r\n";
			css += "-moz-border-radius-topright: "+styles.rounded_corners_size+"px; \r\n";
			css += "border-top-left-radius: "+styles.rounded_corners_size+"px; \r\n";
			css += "border-top-right-radius: "+styles.rounded_corners_size+"px; \r\n";
		}
		
		// Exception for Round Image style.
		if (styles.round_image_normal == true) 
		{
			css += "-webkit-border-radius: 50% 50% 50% 50%; \r\n";
			css += "-moz-border-radius: 50% 50% 50% 50%; \r\n";
			css += "border-radius: 50% 50% 50% 50%; \r\n";
		}

		// Exception for Border style.
		if (styles.border_normal == true) 
		{
			css += "bottom: -"+(1+parseInt(styles.reflection_size)+parseInt(styles.border_size))+"px; \r\n";
		}
		// Exception for External Caption style.
		else if (styles.external_caption_normal == true) 
		{
			css += "bottom: -"+(1+parseInt(styles.reflection_size)+2*parseInt(styles.external_caption_space)+parseInt(styles.external_caption_font_size))+"px; \r\n";
		}
		else
		{
			css += "bottom: -"+(1+parseInt(styles.reflection_size))+"px; \r\n";
		}

		css += '} \r\n\r\n';
		jQuery('head').append('<style>'+css+'</style>');
	}
}

/* ******** */
/* Rotation */
/* ******** */
function cisRotationStyle(cis_styled_image, styles) 
{
	// Check the style properties.
	if (styles.rotation_normal == undefined)
		styles.rotation_normal = false;
	if (styles.rotation_size == undefined)
		styles.rotation_size = -3;
	if (styles.rotation_hover == undefined)
		styles.rotation_hover = false;
	if (styles.rotation_size_hover == undefined)
		styles.rotation_size_hover = 3;	
	
	var css = '';
	
	// Apply the normal style.
	if (styles.rotation_normal == true)
	{
		css += '.'+styles.name;
		css += ' { \r\n';
		css += '-webkit-transform: rotate('+styles.rotation_size+'deg); \r\n';
		css += '-moz-transform: rotate('+styles.rotation_size+'deg); \r\n';
		css += '-ms-transform: rotate('+styles.rotation_size+'deg); \r\n';
		css += '-o-transform: rotate('+styles.rotation_size+'deg); \r\n';
		css += 'transform: rotate('+styles.rotation_size+'deg); \r\n';
		css += '} \r\n\r\n';
	}
	
	// Apply the 'on hover' style.
	if (styles.rotation_hover == true) 
	{
		css += '.'+styles.name;
		css += ':hover { \r\n';
		css += '-webkit-transform: rotate('+styles.rotation_size_hover+'deg); \r\n';
		css += '-moz-transform: rotate('+styles.rotation_size_hover+'deg); \r\n';
		css += '-ms-transform: rotate('+styles.rotation_size_hover+'deg); \r\n';
		css += '-o-transform: rotate('+styles.rotation_size_hover+'deg); \r\n';
		css += 'transform: rotate('+styles.rotation_size_hover+'deg); \r\n';
		css += '} \r\n\r\n';
	}
	
	if (styles.rotation_normal == true || styles.rotation_hover == true)
	{
		jQuery('head').append('<style>'+css+'</style>');
	}
}

/* *************** */
/* Rounded Corners */
/* *************** */
function cisRoundedCornersStyle(cis_styled_image, styles) 
{
	// Check the style properties.
	if (styles.rounded_corners_normal == undefined)
		styles.rounded_corners_normal = false;
	if (styles.rounded_corners_size == undefined)
		styles.rounded_corners_size = 20;
	if (styles.rounded_corners_hover == undefined)
		styles.rounded_corners_hover = false;
	if (styles.rounded_corners_size_hover == undefined)
		styles.rounded_corners_size_hover = 30;
	
	// Apply the normal style.
	if (styles.rounded_corners_normal == true)
	{
		cis_styled_image.css('border-radius', styles.rounded_corners_size+'px');
	}
	
	// Apply the 'on hover' style.
	if (styles.rounded_corners_hover == true) 
	{
		cis_styled_image.hover(
			function() { 
				jQuery(this).css('border-radius', styles.rounded_corners_size_hover+'px');
			}, 
			function() { 
				jQuery(this).css('border-radius', styles.rounded_corners_size+'px');
			}
		);
	}
}

/* *********** */
/* Round Image */
/* *********** */
function cisRoundImageStyle(cis_styled_image, styles) 
{
	// Check the style properties.
	if (styles.round_image_normal == undefined)
		styles.round_image_normal = false;
	if (styles.round_image_hover == undefined)
		styles.round_image_hover = false;
	
	// Apply the normal style.
	if (styles.round_image_normal == true)
	{
		cis_styled_image.css('border-radius', '50% 50% 50% 50%');
	}
	
	// Apply the 'on hover' style.
	if (styles.round_image_hover == true) 
	{
		cis_styled_image.hover(
			function() { 
				jQuery(this).css('border-radius', '50% 50% 50% 50%');
			}, 
			function() { 
				jQuery(this).css('border-radius', '50% 50% 50% 50%');
			}
		);
	}
}

/* ***** */
/* Scale */
/* ***** */
function cisScaleStyle(cis_styled_image, styles) 
{
	// Check the style properties.
	if (styles.scale_normal == undefined)
		styles.scale_normal = false;
	if (styles.scale_size == undefined)
		styles.scale_size = 1;
	if (styles.scale_hover == undefined)
		styles.scale_hover = false;
	if (styles.scale_size_hover == undefined)
		styles.scale_size_hover = 1.05;	
	
	var css = '';
	
	// Apply the normal style.
	if (styles.scale_normal == true)
	{
		css += '.'+styles.name;
		css += ' { \r\n';
		css += '-webkit-transform: scale('+styles.scale_size+'); \r\n';
		css += '-moz-transform: scale('+styles.scale_size+'); \r\n';
		css += '-ms-transform: scale('+styles.scale_size+'); \r\n';
		css += '-o-transform: scale('+styles.scale_size+'); \r\n';
		css += 'transform: scale('+styles.scale_size+'); \r\n';
		css += '} \r\n\r\n';
	}
	
	// Apply the 'on hover' style.
	if (styles.scale_hover == true) 
	{
		css += '.'+styles.name;
		css += ':hover { \r\n';
		css += '-webkit-transform: scale('+styles.scale_size_hover+'); \r\n';
		css += '-moz-transform: scale('+styles.scale_size_hover+'); \r\n';
		css += '-ms-transform: scale('+styles.scale_size_hover+'); \r\n';
		css += '-o-transform: scale('+styles.scale_size_hover+'); \r\n';
		css += 'transform: scale('+styles.scale_size_hover+'); \r\n';
		css += '} \r\n\r\n';
	}
	
	if (styles.scale_normal == true || styles.scale_hover == true)
	{
		jQuery('head').append('<style>'+css+'</style>');
	}
}

/* ****** */
/* Shadow */
/* ****** */
function cisShadowStyle(cis_styled_image, styles) 
{
	// Check the style properties.
	if (styles.shadow_normal == undefined)
		styles.shadow_normal = false;
	if (styles.shadow_size == undefined)
		styles.shadow_size = 10;
	if (styles.shadow_color == undefined)
		styles.shadow_color = '#333333';
	if (styles.shadow_opacity == undefined)
		styles.shadow_opacity = 1;
	if (styles.shadow_hover == undefined)
		styles.shadow_hover = false;
	if (styles.shadow_size_hover == undefined)
		styles.shadow_size_hover = 15;
	if (styles.shadow_color_hover == undefined)
		styles.shadow_color_hover = '#000000';
	if (styles.shadow_opacity_hover == undefined)
		styles.shadow_opacity_hover = 1;
	
	// Apply the normal style.
	if (styles.shadow_normal == true) 
	{
		var shadow = '';
		if (cis_styled_image.data('shadow'))
			shadow = ', '+cis_styled_image.data('shadow');
		shadow = (styles.shadow_size/4)+'px '+(styles.shadow_size/4)+'px '+styles.shadow_size+'px rgba('+cisHexToRgb(styles.shadow_color)+', '+styles.shadow_opacity+')'+shadow;
		cis_styled_image.data('shadow', shadow);
	}
	
	// Apply the 'on hover' style.
	if (styles.shadow_hover == true) 
	{
		var shadow_hover = '';
		if (cis_styled_image.data('shadow_hover'))
			shadow_hover = ', '+cis_styled_image.data('shadow_hover');
		shadow_hover = (styles.shadow_size/4)+'px '+(styles.shadow_size_hover/4)+'px '+styles.shadow_size_hover+'px rgba('+cisHexToRgb(styles.shadow_color_hover)+', '+styles.shadow_opacity_hover+')'+shadow_hover;
		cis_styled_image.data('shadow_hover', shadow_hover);
	}
}

/* ***** */
/* Shake */
/* ***** */
function cisShakeStyle(cis_styled_image, styles) 
{
	// Check the style properties.
	if (styles.shake_normal == undefined)
		styles.shake_normal = false;	
	if (styles.shake_hover == undefined)
		styles.shake_hover = false;	
	
	var css = '';
	if (styles.shake_normal == true || styles.shake_hover == true) 
	{
		css += '@keyframes shake{ \r\n';
		css += '0% { transform: translate(2px, 1px) rotate(0deg); } \r\n';
		css += '10% { transform: translate(-1px, -2px) rotate(-1deg); } \r\n';
		css += '20% { transform: translate(-3px, 0px) rotate(1deg); } \r\n';
		css += '30% { transform: translate(0px, 2px) rotate(0deg); } \r\n';
		css += '40% { transform: translate(1px, -1px) rotate(1deg); } \r\n';
		css += '50% { transform: translate(-1px, 2px) rotate(-1deg); } \r\n';
		css += '60% { transform: translate(-3px, 1px) rotate(0deg); } \r\n';
		css += '70% { transform: translate(2px, 1px) rotate(-1deg); } \r\n';
		css += '80% { transform: translate(-1px, -1px) rotate(1deg); } \r\n';
		css += '90% { transform: translate(2px, 2px) rotate(0deg); } \r\n';
		css += '100% { transform: translate(1px, -2px) rotate(-1deg); } \r\n';
		css += '} \r\n';
		css += '@-moz-keyframes shake{ \r\n';
		css += '0% { -moz-transform: translate(2px, 1px) rotate(0deg); } \r\n';
		css += '10% { -moz-transform: translate(-1px, -2px) rotate(-1deg); } \r\n';
		css += '20% { -moz-transform: translate(-3px, 0px) rotate(1deg); } \r\n';
		css += '30% { -moz-transform: translate(0px, 2px) rotate(0deg); } \r\n';
		css += '40% { -moz-transform: translate(1px, -1px) rotate(1deg); } \r\n';
		css += '50% { -moz-transform: translate(-1px, 2px) rotate(-1deg); } \r\n';
		css += '60% { -moz-transform: translate(-3px, 1px) rotate(0deg); } \r\n';
		css += '70% { -moz-transform: translate(2px, 1px) rotate(-1deg); } \r\n';
		css += '80% { -moz-transform: translate(-1px, -1px) rotate(1deg); } \r\n';
		css += '90% { -moz-transform: translate(2px, 2px) rotate(0deg); } \r\n';
		css += '100% { -moz-transform: translate(1px, -2px) rotate(-1deg); } \r\n';
		css += '} \r\n';
		css += '@-webkit-keyframes shake { \r\n';
		css += '0% { -webkit-transform: translate(2px, 1px) rotate(0deg); } \r\n';
		css += '10% { -webkit-transform: translate(-1px, -2px) rotate(-1deg); } \r\n';
		css += '20% { -webkit-transform: translate(-3px, 0px) rotate(1deg); } \r\n';
		css += '30% { -webkit-transform: translate(0px, 2px) rotate(0deg); } \r\n';
		css += '40% { -webkit-transform: translate(1px, -1px) rotate(1deg); } \r\n';
		css += '50% { -webkit-transform: translate(-1px, 2px) rotate(-1deg); } \r\n';
		css += '60% { -webkit-transform: translate(-3px, 1px) rotate(0deg); } \r\n';
		css += '70% { -webkit-transform: translate(2px, 1px) rotate(-1deg); } \r\n';
		css += '80% { -webkit-transform: translate(-1px, -1px) rotate(1deg); } \r\n';
		css += '90% { -webkit-transform: translate(2px, 2px) rotate(0deg); } \r\n';
		css += '100% { -webkit-transform: translate(1px, -2px) rotate(-1deg); } \r\n';
		css += '} \r\n';
	}
	
	// Apply the normal style.
	if (styles.shake_normal == true) 
	{
		css += '.'+styles.name;
		css += ' { \r\n';
		css += 'animation-name: shake; \r\n';
		css += 'animation-duration: 0.8s; \r\n';
		css += 'transform-origin:50% 50%; \r\n';
		css += 'animation-iteration-count: infinite; \r\n';
		css += 'animation-timing-function: linear; \r\n';
		css += '-moz-animation-name: shake; \r\n';
		css += '-moz-animation-duration: 0.8s; \r\n';
		css += '-moz-transform-origin:50% 50%; \r\n';
		css += '-moz-animation-iteration-count: infinite; \r\n';
		css += '-moz-animation-timing-function: linear; \r\n';
		css += '-webkit-animation-name: shake; \r\n';
		css += '-webkit-animation-duration: 0.8s; \r\n';
		css += '-webkit-transform-origin:50% 50%; \r\n';
		css += '-webkit-animation-iteration-count: infinite; \r\n';
		css += '-webkit-animation-timing-function: linear; \r\n';
		css += '} \r\n\r\n';
	}
	
	// Apply the 'on hover' style.
	if (styles.shake_hover == true) 
	{
		css += '.'+styles.name;
		css += ':hover { \r\n';
		css += 'animation-name: shake; \r\n';
		css += 'animation-duration: 0.8s; \r\n';
		css += 'transform-origin:50% 50%; \r\n';
		css += 'animation-iteration-count: infinite; \r\n';
		css += 'animation-timing-function: linear; \r\n';
		css += '-moz-animation-name: shake; \r\n';
		css += '-moz-animation-duration: 0.8s; \r\n';
		css += '-moz-transform-origin:50% 50%; \r\n';
		css += '-moz-animation-iteration-count: infinite; \r\n';
		css += '-moz-animation-timing-function: linear; \r\n';
		css += '-webkit-animation-name: shake; \r\n';
		css += '-webkit-animation-duration: 0.8s; \r\n';
		css += '-webkit-transform-origin:50% 50%; \r\n';
		css += '-webkit-animation-iteration-count: infinite; \r\n';
		css += '-webkit-animation-timing-function: linear; \r\n';
		css += '} \r\n\r\n';
	}
	
	if (styles.shake_normal == true || styles.shake_hover == true) 
	{
		jQuery('head').append('<style>'+css+'</style>');
	}
}

/* **** */
/* Size */
/* **** */
function cisSizeStyle(cis_styled_image, styles) 
{
	// Check the style properties.
	if (styles.size_normal == undefined)
		styles.size_normal = false;
	if (styles.size_width == undefined)
		styles.size_width = 200;
	if (styles.size_height == undefined)
		styles.size_height = 200;
	if (styles.size_hover == undefined)
		styles.size_hover = false;
	if (styles.size_width_hover == undefined)
		styles.size_width_hover = 220;
	if (styles.size_height_hover == undefined)
		styles.size_height_hover = 220;
	
	// Apply the normal style.
	if (styles.size_normal == true)
	{
		cis_styled_image.css('width', styles.size_width);
		cis_styled_image.css('height', styles.size_height);
	}
	
	// Apply the 'on hover' style.
	if (styles.size_hover == true) 
	{
		cis_styled_image.hover(
			function() { 
				jQuery(this).stop().animate({'width': styles.size_width_hover, 'height': styles.size_height_hover});
			}, 
			function() { 
				jQuery(this).stop().animate({'width': styles.size_width, 'height': styles.size_height});
			}
		);
	}
}

/* *************** */
/* Sliding Caption */
/* *************** */
function cisSlidingCaptionStyle(cis_styled_image, styles) 
{
	// Check the style properties.
	if (styles.sliding_caption_hover == undefined)
		styles.sliding_caption_hover = false;
	if (styles.sliding_caption_font_size == undefined)
		styles.sliding_caption_font_size = 11;
	
	// Apply the 'on hover' style.
	if (styles.sliding_caption_hover == true && cis_styled_image.find('img').attr('title') != undefined && cis_styled_image.find('img').attr('title').length > 0) 
	{
		cis_styled_image.css('overflow', 'hidden');
		cis_styled_image.addClass('cis-sliding-caption');
		cis_styled_image.attr('title', '');
		
		// Create second element.
		var cis_sliding_caption = jQuery('<p></p>');
		cis_sliding_caption.addClass('cis-sliding-caption');
		cis_sliding_caption.html(cis_styled_image.find('img').attr('title'));
		cis_styled_image.append(cis_sliding_caption);	
		
		var css = '';
		
		css += '.'+styles.name;
		css += '.cis-sliding-caption';
		css += ':before { \r\n';
		css += 'content:"?"; \r\n';
		css += 'position:absolute; \r\n';
		css += 'font-weight:800; \r\n';
		css += 'background:black; \r\n';
		css += 'background:rgba(255,255,255,0.75); \r\n';
		css += 'text-shadow:0 0 5px white; \r\n';
		css += 'color:black; \r\n';
		css += 'width:24px; \r\n';
		css += 'height:24px; \r\n';
		css += '-webkit-border-radius:12px; \r\n';
		css += '-moz-border-radius:12px; \r\n';
		css += 'border-radius:12px; \r\n';
		css += 'text-align:center; \r\n';
		css += 'font-size:14px; \r\n';
		css += 'line-height:24px; \r\n';
		css += '-webkit-transition:all 0.6s ease; \r\n';
		css += '-moz-transition:all 0.6s ease; \r\n';
		css += '-o-transition:all 0.6s ease; \r\n';
		css += 'transition:all 0.6s ease; \r\n';
		css += 'opacity:0.75; \r\n';
		css += 'bottom: 10px; \r\n';
		css += 'left: 10px; \r\n';
		css += '} \r\n\r\n';
		
		css += '.'+styles.name;
		css += '.cis-sliding-caption';
		css += ':hover:before { \r\n';
		css += 'opacity:0; \r\n';
		css += '} \r\n\r\n';
		
		css += '.'+styles.name;
		css += '.cis-sliding-caption';
		css += ' p.cis-sliding-caption { \r\n';
		css += 'position:absolute; \r\n';
		css += 'background:black; \r\n';
		css += 'background:rgba(0,0,0,0.75); \r\n';
		css += 'color:white; \r\n';
		css += 'padding:10px 20px; \r\n';
		css += 'opacity:0; \r\n';
		css += '-webkit-transition:all 0.6s ease; \r\n';
		css += '-moz-transition:all 0.6s ease; \r\n';
		css += '-o-transition:all 0.6s ease; \r\n';
		css += 'transition:all 0.6s ease; \r\n';
		css += 'left:0; \r\n';
		css += 'right:0; \r\n';
		css += 'bottom:-30%; \r\n';
		css += 'margin:0px; \r\n';
		css += 'font-size: '+styles.sliding_caption_font_size+'px; \r\n';
		css += 'line-height: '+(1.5*parseInt(styles.sliding_caption_font_size))+'px; \r\n';
		css += 'text-align:left; \r\n';
		css += '} \r\n\r\n';
		
		css += '.'+styles.name;
		css += '.cis-sliding-caption';
		css += ':hover p.cis-sliding-caption { \r\n';
		css += 'opacity:1; \r\n';
		css += 'bottom:0; \r\n';
		css += '} \r\n\r\n';
		
		jQuery('head').append('<style>'+css+'</style>');
	}
}

/* ******* */
/* Stretch */
/* ******* */
function cisStretchStyle(cis_styled_image, styles) 
{
	// Check the style properties.
	if (styles.stretch == undefined)
		styles.stretch = true;
	
	if (styles.stretch == false) 
	{
		cis_styled_image.css('background-size', 'cover');
	}
}

/* *** */
/* SVG */
/* *** */
function cisSVGStyle(cis_styled_image, styles) 
{
	// Check the style properties.
	if (styles.svg_normal == undefined)
		styles.svg_normal = false;
	if (styles.svg_image == undefined)
		styles.svg_image = 'paperclip-black.svg';

	// Apply the normal style.
	if (styles.svg_normal == true)
	{
		// Create second div.
		var cis_svg = jQuery('<img src="'+styles.svg_joomla_uri+'plugins/system/completeimagestyles/completeimagestyles/images/svg/'+styles.svg_image+'" />');
		cis_svg.css('width', '20px');
		cis_svg.css('height', '50px');
		cis_svg.css('border', 'none');
		cis_svg.css('margin', '0 auto');
		cis_svg.css('display', 'block');
		cis_svg.css('margin-top', '-9px');
		var svg_top = -9;
		if (styles.border_normal==true)
			svg_top -= parseInt(styles.border_size);
		if (styles.external_caption_normal==true)
			svg_top -= parseInt(styles.external_caption_space);
		cis_svg.css('margin-top', svg_top+'px');
		cis_styled_image.append(cis_svg);
	}
}

/* **** */
/* Tape */
/* **** */
function cisTapeStyle(cis_styled_image, styles) 
{
	// Check the style properties.
	if (styles.tape_normal == undefined)
		styles.tape_normal = false;
	
	if (styles.tape_normal == true) 
	{
		// Apply the normal style.
		cis_styled_image.css('margin-top', '+=13');
		
		var shadow = '';
		if (cis_styled_image.data('shadow'))
			shadow = ', '+cis_styled_image.data('shadow');
		shadow = 'inset 0 0 2px rgba(0,0,0,.7), inset 0 2px 0 rgba(255,255,255,.3), inset 0 -1px 0 rgba(0,0,0,.5), 0 1px 3px rgba(0,0,0,.4)'+shadow;
		cis_styled_image.data('shadow', shadow);
	
		// Apply the 'on hover' style so it will not be deleted by other shadows.
		var shadow_hover = '';
		if (cis_styled_image.data('shadow_hover'))
			shadow_hover = ', '+cis_styled_image.data('shadow_hover');
		shadow_hover = 'inset 0 0 2px rgba(0,0,0,.7), inset 0 2px 0 rgba(255,255,255,.3), inset 0 -1px 0 rgba(0,0,0,.5), 0 1px 3px rgba(0,0,0,.4)'+shadow_hover;
		cis_styled_image.data('shadow_hover', shadow_hover);
		
		// After selector.
		var css = '';
		css += '.'+styles.name;
		css += ':after { \r\n';
		css += 'position: absolute; \r\n';
		css += 'content: " "; \r\n';
		css += 'width: 60px; \r\n';
		css += 'height: 25px; \r\n';
		css += 'top: -13px; \r\n';
		css += 'left: 50%; \r\n';
		css += 'margin-left: -30px; \r\n';
		css += 'border: solid 1px rgba(137,130,48,.2); \r\n';
		css += 'background-color: rgba(254,243,127,0.6); \r\n';
		css += 'background: -moz-linear-gradient(top, rgba(254,243,127,.6) 0%, rgba(240,224,54,.6) 100%); \r\n';
		css += 'background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(254,243,127,.6)), color-stop(100%,rgba(240,224,54,.6))); \r\n';
		css += 'background: linear-gradient(top, rgba(254,243,127,.6) 0%,rgba(240,224,54,.6) 100%); \r\n';
		css += '-webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,.3), 0 1px 0 rgba(0,0,0,.2); \r\n';
		css += '-moz-box-shadow: inset 0 1px 0 rgba(255,255,255,.3), 0 1px 0 rgba(0,0,0,.2); \r\n';
		css += '-o-box-shadow: inset 0 1px 0 rgba(255,255,255,.3), 0 1px 0 rgba(0,0,0,.2); \r\n';
		css += 'box-shadow: inset 0 1px 0 rgba(255,255,255,.3), 0 1px 0 rgba(0,0,0,.2); \r\n';
		css += '} \r\n\r\n';
		
		jQuery('head').append('<style>'+css+'</style>');
	}
}

/* ******* */
/* Tooltip */
/* ******* */
function cisTooltipStyle(cis_styled_image, styles) 
{
	// Check the style properties.
	if (styles.tooltip_hover == undefined)
		styles.tooltip_hover = false;
	if (styles.tooltip_font_size == undefined)
		styles.tooltip_font_size = 11;
	
	// Apply the 'on hover' style.
	if (styles.tooltip_hover == true && cis_styled_image.find('img').attr('title') != undefined) 
	{
		cis_styled_image.addClass('cis-tooltip');
		cis_styled_image.attr('tooltip', cis_styled_image.find('img').attr('title'));
		cis_styled_image.attr('title', '');
		
		var css = '';
		
		css += '.'+styles.name;
		css += '.cis-tooltip';
		css += ':hover:after { \r\n';
		css += 'content:attr(tooltip); \r\n';
		css += 'position:absolute; \r\n';
		css += 'display:block; \r\n';
		css += 'padding:5px 10px; \r\n';
		css += 'margin-top:10px; \r\n';
		css += 'margin-left:10px; \r\n';
		css += 'margin-right:10px; \r\n';
		css += 'background:#111; \r\n';
		css += 'background:rgba(0,0,0,.8); \r\n';
		css += '-webkit-border-radius:10px; \r\n';
		css += '-moz-border-radius:10px; \r\n';
		css += 'border-radius:10px; \r\n';
		css += 'color:#FFF; \r\n';
		css += 'font-size:'+styles.tooltip_font_size+'px; \r\n';
		css += 'font-weight:normal; \r\n';
		css += 'text-align:left; \r\n';
		css += 'text-shadow:0 1px 0 #000; \r\n';
		css += '-webkit-box-shadow:2px 2px 2px rgba(0, 0, 0, 0.6); \r\n';
		css += '-moz-box-shadow:2px 2px 2px rgba(0, 0, 0, 0.6); \r\n';
		css += '-o-box-shadow:2px 2px 2px rgba(0, 0, 0, 0.6); \r\n';
		css += 'box-shadow:2px 2px 2px rgba(0, 0, 0, 0.6); \r\n';
		css += '} \r\n\r\n';
		
		jQuery('head').append('<style>'+css+'</style>');
	}
}

/* ************** */
/* Vertical Curve */
/* ************** */
function cisVerticalCurveStyle(cis_styled_image, styles) 
{
	// Check the style properties.
	if (styles.vertical_curve_normal == undefined)
		styles.vertical_curve_normal = false;
	if (styles.vertical_curve_size == undefined)
		styles.vertical_curve_size = 15;

	// Apply the normal style.
	if (styles.vertical_curve_normal == true)
	{
		// Create second div.
		var cis_vertical_curve = jQuery('<div></div>');
		cis_vertical_curve.addClass('cis-vertical-curve');
		cis_styled_image.append(cis_vertical_curve);
		
		// Apply styles to :after element for the second div.
		var css = '';
		css += '.'+styles.name+' ';
		css += '.cis-vertical-curve { \r\n';
		css += 'position: absolute; \r\n';
		css += 'z-index: -1; \r\n';
		css += 'top:10px; \r\n';
		css += 'bottom:10px; \r\n';
		css += 'left:0px; \r\n';
		css += 'right:0px; \r\n';
		css += '-webkit-box-shadow:0 0 '+styles.vertical_curve_size+'px rgba(0,0,0,1); \r\n';
		css += '-moz-box-shadow:0 0 '+styles.vertical_curve_size+'px rgba(0,0,0,1); \r\n';
		css += '-o-box-shadow:0 0 '+styles.vertical_curve_size+'px rgba(0,0,0,1); \r\n';
		css += 'box-shadow:0 0 '+styles.vertical_curve_size+'px rgba(0,0,0,1); \r\n';
		css += '-webkit-border-radius:4%/30%; \r\n';
		css += '-moz-border-radius:4%/30%; \r\n';
		css += 'border-radius:4%/30%;  \r\n';
		
		css += '} \r\n\r\n';
		jQuery('head').append('<style>'+css+'</style>');
	}
}

/* ****************** */
/* Aggregated Shadows */
/* ****************** */
function cisAggregatedShadows(cis_styled_image) 
{
	// Apply the normal style.
	if (cis_styled_image.data('shadow')) 
	{
		cis_styled_image.css('box-shadow', cis_styled_image.data('shadow'));
	}
	
	// Apply the 'on hover' style.
	if (cis_styled_image.data('shadow_hover')) 
	{
		cis_styled_image.hover(
			function() { 
				jQuery(this).css('box-shadow', cis_styled_image.data('shadow_hover'));
			}, 
			function() { 
				jQuery(this).css('box-shadow', cis_styled_image.data('shadow'));
			}
		);
	}
}

/* ********** */
/* Transition */
/* ********** */
function cisTransitionStyle(cis_styled_image, styles) 
{
	// Check the style properties.
	if (styles.transition_duration == undefined)
		styles.transition_duration = 0.5;

	cis_styled_image.css('transition-duration', styles.transition_duration+'s');
	cis_styled_image.css('transition', 'all ease '+styles.transition_duration+'s, width 0, height 0');
}

/* *************** */
/* Other Functions */
/* *************** */
function cisHexToRgb(hex) 
{
	var bigint = parseInt(hex.replace('#', ''), 16);
	var r = (bigint >> 16) & 255;
	var g = (bigint >> 8) & 255;
	var b = bigint & 255;
	
	return r + ',' + g + ',' + b;
}

function cisSetStyledImageSize(cis_simple_image, cis_styled_image) 
{
	// Get the natural size of the image.
	var cis_image_natural_width = cis_simple_image.naturalWidth;
	var cis_image_natural_height = cis_simple_image.naturalHeight;
	
	// Make it responsive.
	// Width '%'.
	if (jQuery(cis_simple_image).attr('width') && jQuery(cis_simple_image).attr('width').indexOf("%") > -1)
	{
		cis_styled_image.css('width', jQuery(cis_simple_image).attr('width'));
	}
	if (cis_simple_image.style.width && cis_simple_image.style.width.indexOf("%") > -1)
	{
		cis_styled_image.css('width', cis_simple_image.style.width);
	}
	// Height '%' (of the natural height).
	if (jQuery(cis_simple_image).attr('height') && jQuery(cis_simple_image).attr('height').indexOf("%") > -1)
	{
		cis_styled_image.css('height', parseFloat(jQuery(cis_simple_image).attr('height'))/100.0*cis_image_natural_height);
	}
	if (cis_simple_image.style.height && cis_simple_image.style.height.indexOf("%") > -1)
	{
		cis_styled_image.css('height', parseFloat(cis_simple_image.style.height)/100.0*cis_image_natural_height);
	}
	// Height 'auto'.
	if (jQuery(cis_simple_image).attr('height') && jQuery(cis_simple_image).attr('height') == 'auto')
	{
		cis_styled_image.css('height', cis_image_natural_height*parseInt(cis_styled_image.css('width'))/cis_image_natural_width);
	}
	if (cis_simple_image.style.height && cis_simple_image.style.height == 'auto')
	{
		cis_styled_image.css('height', cis_image_natural_height*parseInt(cis_styled_image.css('width'))/cis_image_natural_width);
	}
}