<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_pri_background
 * @version     4.0
 *
 * @copyright   Copyright (C) 2016 Devpri SRL. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

//Color Overlay CSS
$color_overlay_css = '
	#pri-background-overlay-'.$module_id.' {
		background-color: '.$params->get('color_overlay').';
		opacity: '.$params->get('color_overlay_opacity').';
	}
';
// Pattern Overlay CSS
$pattern_overlay_css = '
	#pri-background-overlay-'.$module_id.' {
		background-image:url("'.JURI::base() .$params->get('pattern_overlay_url').'") !important;
		background-position: '.$params->get('pattern_overlay_position').';
		background-repeat: '.$params->get('pattern_overlay_repeat').';
		background-size: '.$params->get('pattern_overlay_size').';
		opacity: '.$params->get('pattern_overlay_opacity').';
	}
';
// SVG Overlay
$svg_overlay_css = '
	#pri-background-overlay-'.$module_id.' {
		opacity: '.$params->get('svg_overlay_opacity').';
	}
';
//Load CSS
if($params->get('overlay_type') == "color"){
	$document->addStyleDeclaration($color_overlay_css);
} else if ($params->get('overlay_type') == "pattern"){
	$document->addStyleDeclaration($pattern_overlay_css);
} else if ($params->get('overlay_type') == "svg"){
	$document->addStyleDeclaration($svg_overlay_css);
}

?>

<!-- Start Overlay DIV -->
<div id="pri-background-overlay-<?php echo $module_id; ?>"
class="pri-background-overlay pri-background-size">
	<?php if ($params->get('overlay_type') == "svg"){
      echo $params->get('svg_overlay_code');
    } ?>
</div>
<!-- End Overlay DIV --> 