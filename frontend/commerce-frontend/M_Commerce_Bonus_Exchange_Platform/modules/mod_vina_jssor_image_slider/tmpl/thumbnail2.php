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
#vina-jssor-image-slider<?php echo $module->id; ?> .jssort03 .c {
	width: <?php echo $params->get('thumbnailImageWidth', 60) + 2; ?>px;
	height: <?php echo $params->get('thumbnailImageHeight', 30) + 2; ?>px;
}
#vina-jssor-image-slider<?php echo $module->id; ?> .jssort03 .w,
#vina-jssor-image-slider<?php echo $module->id; ?> .jssort03 .pav:hover .w {
	width: <?php echo $params->get('thumbnailImageWidth', 60); ?>px;
	height: <?php echo $params->get('thumbnailImageHeight', 30); ?>px;
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
			$image  = $slide->img;
			$image  = (strpos($image, 'http://') === false) ? JURI::base() . $image : $image;
			
			// render large image
			$w = $params->get('maxWidth', 600);
			$h = $params->get('maxHeight', 300);
			
			$largeImage = '<img class="image" alt="'.$title.'" src="'.$image.'" />';
			if($params->get('resizeImage', 0) == 1) {
				$rImage 	= $timthumb . '&w=' . $w . '&h=' . $h . '&src=' . $image;
				$largeImage = '<img class="image" alt="'.$title.'" src="'.$rImage.'" />';
			}
			if($params->get('resizeImage', 0) == 2) {
				$largeImage = '<img width="'.$w.'" height="'.$h.'" class="image" alt="'.$title.'" src="'.$image.'" />';
			}
			
			// render thumbnail image
			$sw = $params->get('thumbnailImageWidth', 60);
			$sh = $params->get('thumbnailImageHeight', 30);
			
			$smallImage = '<img u="thumb" width="'.$sw.'" height="'.$sh.'" title="'.$title.'" src="'.$image.'" />';
			if($params->get('resizeImage', 0) == 1) {
				$rImage = $timthumb . '&w=' . $sw . '&h=' . $sh . '&src=' . $image;
				$smallImage = '<img u="thumb" title="'.$title.'" src="'.$rImage.'" />';
			}
		?>
		<div class="vina-is-slide">
			<?php echo $largeImage; ?>
			<?php echo $smallImage; ?>
			<?php echo ($params->get('displayCaptions', 1)) ? $slide->text : ''; ?>
		</div>
		<?php endforeach; ?>
	</div>
	
	<!-- Direction Navigator Skin Begin -->
	<!-- Arrow Left -->
	<span u="arrowleft" class="jssord02l" style="width: 55px; height: 55px; top: 40%; left: 8px;"></span>
	<!-- Arrow Right -->
	<span u="arrowright" class="jssord02r" style="width: 55px; height: 55px; top: 40%; right: 8px"></span>
	<!-- Direction Navigator Skin End -->
	
	<!-- ThumbnailNavigator Skin Begin -->
	<div u="thumbnavigator" class="jssort03" style="position: absolute; overflow: hidden; width: <?php echo $params->get('maxWidth', 600); ?>px; height: <?php echo $sh + 30; ?>px; left:0px; top: 0px;">
		<div class="vina-thumbnail-mask"></div>
		<!-- Thumbnail Item Skin Begin -->
		<div u="slides" style="cursor: move;">
			<div u="prototype" class="p" style="POSITION: absolute; WIDTH: <?php echo $sw + 2; ?>px; HEIGHT: <?php echo $sh + 2; ?>px; TOP: 0; LEFT: 0;">
				<div class="w"><ThumbnailTemplate style=" WIDTH: 100%; HEIGHT: 100%; border: none;position:absolute; TOP: 0; LEFT: 0;"></ThumbnailTemplate></div>
				<div class="c" style="POSITION: absolute; BACKGROUND-COLOR: #000; TOP: 0; LEFT: 0"></div>
			</div>
		</div>
		<!-- Thumbnail Item Skin End -->
	</div>
	<!-- ThumbnailNavigator Skin End -->
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
		
		$DirectionNavigatorOptions: {
			$Class: $JssorDirectionNavigator$,
			$ChanceToShow: <?php echo $params->get('directionArrow', 1); ?>,
			$Steps: 1
		},

		$ThumbnailNavigatorOptions: {
			$Class: $JssorThumbnailNavigator$,
			$ChanceToShow: <?php echo $params->get('chanceToShow', 2); ?>,
			$ActionMode: 1,
			$AutoCenter: 3,
			$Lanes: 1,
			$SpacingX: 3,
			$SpacingY: 3,
			$DisplayPieces: 9,
			$ParkingPosition: 260,
			$Orientation: 1,
			$DisableDrag: false
		}
	};

	var jssor_slider<?php echo $module->id; ?> = new $JssorSlider$("vina-jssor-image-slider<?php echo $module->id; ?>", options<?php echo $module->id; ?>);
	//responsive code begin
	//you can remove responsive code if you don't want the slider scales while window resizes
	function ScaleSlider<?php echo $module->id; ?>() {
		var parentWidth = jssor_slider<?php echo $module->id; ?>.$Elmt.parentNode.clientWidth;
		if (parentWidth)
			jssor_slider<?php echo $module->id; ?>.$SetScaleWidth(Math.min(parentWidth, <?php echo $params->get('maxWidth', 600); ?>));
		else
			window.setTimeout(ScaleSlider<?php echo $module->id; ?>, 30);
	}

	ScaleSlider<?php echo $module->id; ?>();
	$(window).bind('resize', ScaleSlider<?php echo $module->id; ?>);
	//responsive code end
});
</script>