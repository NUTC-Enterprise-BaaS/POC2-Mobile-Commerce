<?php
/*
# ------------------------------------------------------------------------
# Vina Jssor Image Slider for Joomla 3
# ------------------------------------------------------------------------
# Copyright(C) 2014 www.VinaGecko.com. All Rights Reserved.
# @license http://www.gnu.org/licenseses/gpl-3.0.html GNU/GPL
# Author: VinaGecko.com
# Websites: http://vinagecko.com
# Forum:    http://vinagecko.com/forum/
# ------------------------------------------------------------------------
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

$doc = JFactory::getDocument();
$doc->addScript('modules/mod_vina_jssor_image_slider/assets/jssor.core.js', 'text/javascript');
$doc->addScript('modules/mod_vina_jssor_image_slider/assets/jssor.utils.js', 'text/javascript');
$doc->addScript('modules/mod_vina_jssor_image_slider/assets/jssor.slider.js', 'text/javascript');
$doc->addScript('media/jui/js/bootstrap.min.js', 'text/javascript');
$doc->addStyleSheet('modules/mod_vina_jssor_image_slider/assets/jssor.image.css');
?>
<style type="text/css">
#vina-copyright<?php echo $module->id; ?> {
	font-size: 12px;
	<?php if(!$params->get('copyRightText', 0)) : ?>
	height: 1px;
	overflow: hidden;
	<?php endif; ?>
}
</style>
<div id="vina-jssor-image-slider<?php echo $module->id; ?>" class="vina-jssor-image-slider" style=" width: <?php echo $params->get('maxWidth', 600); ?>px; height: <?php echo $params->get('maxHeight', 300); ?>px; overflow: hidden;">
	<!-- Loading Screen -->
	<div u="loading" class="vina-is-loading">
		<div class="vina-is-filter"></div>
		<div class="vina-is-loading-icon"></div>
	</div>

	<!-- Slides Container -->
	<div u="slides" class="vina-is-slides" style="width: <?php echo $params->get('maxWidth', 600); ?>px; height: <?php echo $params->get('maxHeight', 300); ?>px; overflow: hidden;">
		<?php 
		foreach($slides as $slide) :
			$title = $slide->name;
			$image = $slide->img;
			$image = (strpos($image, 'http://') === false) ? JURI::base() . $image : $image;
			
			$w = $params->get('maxWidth', 600);
			$h = $params->get('maxHeight', 300);
			
			// render large image
			$largeImage = '<img class="image" alt="'.$title.'" src="'.$image.'" />';
			if($params->get('resizeImage', 0) == 1) {
				$rImage 	= $timthumb . '&w=' . $w . '&h=' . $h . '&src=' . $image;
				$largeImage = '<img class="image" alt="'.$title.'" src="'.$rImage.'" />';
			}
			if($params->get('resizeImage', 0) == 2) {
				$largeImage = '<img width="'.$w.'" height="'.$h.'" class="image" alt="'.$title.'" src="'.$image.'" />';
			}
		?>
		<div class="vina-is-slide">
			<?php echo $largeImage; ?>
			<?php echo ($params->get('displayCaptions', 1)) ? $slide->text : ''; ?>
		</div>
		<?php endforeach; ?>
	</div>
	
	<!-- Navigator Skin Begin -->
	<div u="navigator" class="slider1-N" style="position: absolute; bottom: 16px; right: 10px;">
		<div u="prototype" style="POSITION: absolute; WIDTH: 12px; HEIGHT: 12px;"></div>
	</div>
	<!-- Navigator Skin End -->
	
	<!-- Direction Navigator Skin Begin -->
	<!-- Arrow Left -->
	<span u="arrowleft" class="jssord02l" style="width: 55px; height: 55px; top: 43%; left: 8px;"></span>
	<!-- Arrow Right -->
	<span u="arrowright" class="jssord02r" style="width: 55px; height: 55px; top: 43%; right: 8px"></span>
	<!-- Direction Navigator Skin End -->
</div>
<script>
jQuery(document).ready(function ($) {
	<?php if($params->get('imageTransitions', 1)) : ?>
	var _ImageStransitions = [
		<?php echo $params->get('imageTransitionList', '{$Duration:1200,$Opacity:2}'); ?>
	];
	<?php endif; ?>
	
	<?php if($params->get('captionTransitions', 1)) : ?>
	var _CaptionTransitions = [
		<?php echo $params->get('captionTransitionList', '{$Duration:900,$FlyDirection:1,$Easing:{$Left:$JssorEasing$.$EaseInOutSine},$ScaleHorizontal:0.6,$Opacity:2}'); ?>
	];
	<?php endif; ?>
	
	var options<?php echo $module->id; ?> = {
		$AutoPlay: <?php echo ($params->get('autoPlay', 1)) ? 'true' : 'false'; ?>,
		$AutoPlaySteps: 1,
		$AutoPlayInterval: <?php echo $params->get('autoPlayInterval', 4000); ?>,
		$PauseOnHover: <?php echo $params->get('pauseOnHover', 0); ?>,
		$ArrowKeyNavigation: <?php echo ($params->get('arrowKeyNavigation', 1)) ? 'true' : 'false'; ?>,
		$SlideDuration: <?php echo $params->get('slideDuration', 500); ?>,
		$MinDragOffsetToSlide: 20, 
		$SlideSpacing: 0,
		$DisplayPieces: 1,
		$ParkingPosition: 0,
		$UISearchMode: 1,
		$PlayOrientation: <?php echo $params->get('playOrientation', 1); ?>,
		$DragOrientation: <?php echo $params->get('dragOrientation', 3); ?>,
		
		<?php if($params->get('imageTransitions', 1)) : ?>
		$SlideshowOptions: {
			$Class: $JssorSlideshowRunner$,
			$Transitions: _ImageStransitions,
			$TransitionsOrder: 0,
			$ShowLink: true 
		},
		<?php endif; ?>
		
		<?php if($params->get('captionTransitions', 1)) : ?>
		$CaptionSliderOptions: {
			$Class: $JssorCaptionSlider$,
			$CaptionTransitions: _CaptionTransitions,
			$PlayInMode: <?php echo $params->get('playInMode', 1); ?>,
			$PlayOutMode: <?php echo $params->get('playOutMode', 3); ?>
		},
		<?php endif; ?>

		$NavigatorOptions: {
			$Class: $JssorNavigator$,
			$ChanceToShow: <?php echo $params->get('chanceToShow', 2); ?>,
			$AutoCenter: <?php echo $params->get('autoCenter', 0); ?>,
			$Steps: 1,
			$Lanes: 1,
			$SpacingX: 10,
			$SpacingY: 10,
			$Orientation: 1
		},

		$DirectionNavigatorOptions: {
			$Class: $JssorDirectionNavigator$,
			$ChanceToShow: <?php echo $params->get('directionArrow', 1); ?>,
			$Steps: 1
		}
	};
	var jssor_slider<?php echo $module->id; ?> = new $JssorSlider$("vina-jssor-image-slider<?php echo $module->id; ?>", options<?php echo $module->id; ?>);
	//responsive code begin
	//you can remove responsive code if you don't want the slider scales while window resizes
	function ScaleSlider<?php echo $module->id; ?>() {
		var parentWidth = jssor_slider<?php echo $module->id; ?>.$Elmt.parentNode.clientWidth;
		
		if (parentWidth)
			jssor_slider<?php echo $module->id; ?>.$SetScaleWidth(Math.min(parentWidth, <?php echo $params->get('scaleSlider', 600); ?>));
		else
			window.setTimeout(ScaleSlider<?php echo $module->id; ?>, 30);
	}

	ScaleSlider<?php echo $module->id; ?>();
	$(window).bind('resize', ScaleSlider<?php echo $module->id; ?>);
	//responsive code end
});
</script>