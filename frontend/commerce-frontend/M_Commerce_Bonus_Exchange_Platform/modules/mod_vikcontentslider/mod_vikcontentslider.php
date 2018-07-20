<?php  
/**------------------------------------------------------------------------
 * mod_VikContentSlider
 * ------------------------------------------------------------------------
 * author    Valentina Arras - Extensionsforjoomla.com
 * copyright Copyright (C) 2014 extensionsforjoomla.com. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: http://www.extensionsforjoomla.com
 * Technical Support:  templates@extensionsforjoomla.com
 * ------------------------------------------------------------------------
*/

defined('_JEXEC') or die('Restricted Area'); 
//jimport( 'joomla.methods' );
//JHTML::_('behavior.mootools');

$document = JFactory::getDocument();
JHtml::_('stylesheet', JURI::root().'modules/mod_vikcontentslider/src/mod_vikcontentslider.css', false, true, false, false);

$arrslide = '';

$getfont = $params->get('font');
switch ($getfont) {
	case 0:
		JHtml::_('stylesheet', 'https://fonts.googleapis.com/css?family=Lato:400,700,30', false, true, false, false);
		$slidefont = "Lato";
	break;
	case 1:
		JHtml::_('stylesheet', 'https://fonts.googleapis.com/css?family=Roboto:400,500,300', false, true, false, false);
		$slidefont = "Roboto";
	break;
	case 2:
		JHtml::_('stylesheet', 'https://fonts.googleapis.com/css?family=Oswald:400,300,700', false, true, false, false);
		$slidefont = "Oswald";
	break;
	case 3:
		JHtml::_('stylesheet', 'https://fonts.googleapis.com/css?family=Convergence', false, true, false, false);
		$slidefont = "Convergence";
	break;
}

if(intval($params->get('loadjq')) == 1 ) {
	JHtml::_('jquery.framework', true, true);
	JHtml::_('script', JURI::root().'modules/mod_vikcontentslider/src/jquery.js', false, true, false, false);
}
JHtml::_('script', JURI::root().'modules/mod_vikcontentslider/src/effects.js', false, true, false, false);
//JHtml::_('script', JURI::root().'modules/mod_vikcontentslider/src/jquery.bxslider.min.js', false, true, false, false);
JHtml::_('script', JURI::root().'modules/mod_vikcontentslider/src/modernizr.custom.js', false, true, false, false);



$timeback = $params->get('timebackground');
$dotsnav = $params->get('dotsnav');
$get_dotsnavalign = $params->get('navdotsdalign');
$css_speed = ".vikcs-slide-fromright  .vikcs-img-bckground { 
-webkit-animation: fromRightAnim2 ".$timeback."s ease-in 0s both;
-moz-animation: fromRightAnim2 ".$timeback."s ease-in 0s both;
-o-animation: fromRightAnim2 ".$timeback."s ease-in 0s both;
-ms-animation: fromRightAnim2 ".$timeback."s ease-in 0s both;
animation: fromRightAnim2 ".$timeback."s ease-in 0s both;} .vikcs-slide-dots {text-align:".$get_dotsnavalign.";}";

if($dotsnav == 0) {
	$css_speed .= ".vikcs-slide-dots {display:none;}";
}
$css_speed .=".vikcs-slide {font-family:\"".$slidefont."\";}";

$document->addStyleDeclaration($css_speed);

$viksliderid = rand(1, 17);
$width = $params->get('width');
$height = $params->get('height');
$wwidth = (!empty($width) ? "width=\"".$width."\"" : "");
$wheight = (!empty($height) ? "height=\"".$height."\"" : "");
$stwidth = (!empty($width) ? "width: ".$width.(substr($width, -1) != "%" ? "px" : "%")."; " : "");
$stheight = (!empty($height) ? "height: ".$height.(substr($height, -1) != "%" ? "px" : "%")."; " : "");

$autoplay = $params->get('autoplay');
$interval = $params->get('interval');
$navigation = $params->get('navigation');
$readmtext = $params->get('readmoretext');
$textbackgr = $params->get('textbackground');
$wnext = "";
$wprev = "";

$navenable = intval($navigation) == 1 ? true : false;
$autoplaygo = intval($autoplay) == 1 ? '1' : '0';
$textbackval = intval($textbackgr) == 1 ? " bckgr-text" : '';
$first_height = 0;

$slidejstr = $params->get('viksliderimages', '[]');
$slides = json_decode($slidejstr);
if (count($slides)) {
	foreach ($slides as $sk => $slide) {
		if((int)$slide->published < 1 || empty($slide->image)) {
			continue;
		}
		$imgabpath = JPATH_SITE.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $slide->image);
		if (file_exists($imgabpath)) {
			if(!($sk > 0)) {
				$img_size = @getimagesize($imgabpath);
				$first_height = $img_size && !($first_height > 0) ? $img_size[1] : $first_height;
			}
			$slider_entry = '<div id="vikcs-container"><img class="vikcs-img-bckground" src="'.JURI::root().$slide->image.'" alt="'.$slide->title.'"/>'.
							'	<div class="vikcs-texts">';
			if(!empty($slide->title)) {
				$slider_entry .= '	<h2>'.$slide->title.'</h2>';
			}
			if(!empty($slide->caption)) {
				$slider_entry .= '	<p>'.$slide->caption.'</p>';
			}
			if(!empty($slide->readmore)) {
				$slider_entry .= '	<span class="vikcs-link"><a href="'.$slide->readmore.'">'.$readmtext.'</a></span>';
			}
			$slider_entry .= '	</div>'.
							'</div>';
			$arrslide[] = $slider_entry;
		}
	}
}

?>
<div id="vikcs-slider" class="vikcs-slider<?php echo $textbackval; ?>">
    <?php
    if (is_array($arrslide)) {
		foreach($arrslide as $vsl) {
			echo "<div class=\"vikcs-slide\">";
			echo $vsl;
			echo "</div>";
		}
	}
	if ($navigation) {
	?>
	<nav class="vikcs-slide-arrows arrow-prev">
		<span class="vikcs-slide-arrows-prev"></span>
	</nav>
	<nav class="vikcs-slide-arrows arrow-next">
		<span class="vikcs-slide-arrows-next"></span>
	</nav>
	<?php
	}
	?>
</div>

<script>
jQuery.noConflict();
jQuery(document).ready(function(){		
	jQuery('#vikcs-slider').cslider({
		autoplay : <?php echo $autoplaygo; ?>,
		interval : <?php echo $interval; ?>
	});

	var altezza = jQuery('.vikcs-img-bckground').height();
	if( altezza > 0 ) {
		jQuery('.vikcs-slider').css('height', altezza);
	}

	// for SAFARI reload action
	jQuery('.vikcs-img-bckground').first().on('load', function(e){
		var altezza = jQuery('.vikcs-img-bckground').height();
		jQuery('.vikcs-slider').css('height', altezza);
	});

	jQuery(window).resize(function() {
		var altezza = jQuery('.vikcs-img-bckground').height();
		jQuery('.vikcs-slider').css('height', altezza);
	});

});
</script>
	