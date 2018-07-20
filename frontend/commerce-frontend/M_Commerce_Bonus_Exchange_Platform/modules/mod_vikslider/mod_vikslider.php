<?php  
/**
* Mod_VikSlider
* http://www.extensionsforjoomla.com
*/

defined('_JEXEC') or die('Restricted Area'); 
//jimport( 'joomla.methods' );
//JHTML::_('behavior.mootools');

$document = & JFactory :: getDocument();
$document->addStyleSheet(JURI::base().'modules/mod_vikslider/mod_vikslider.css');
if(intval($params->get('loadjq')) == 1 ) {
	$document->addScript(JURI::base().'modules/mod_vikslider/src/jquery.js');
}
$document->addScript(JURI::base().'modules/mod_vikslider/src/effects.js');

$viksliderid = rand(1, 17);
$width = $params->get('width');
$height = $params->get('height');
$wwidth = (!empty($width) ? "width=\"".$width."\"" : "");
$wheight = (!empty($height) ? "height=\"".$height."\"" : "");
$stwidth = (!empty($width) ? "width: ".$width.(substr($width, -1) != "%" ? "px" : "%")."; " : "");
$stheight = (!empty($height) ? "height: ".$height.(substr($height, -1) != "%" ? "px" : "%")."; " : "");

$effect = $params->get('effect');
$timeout = $params->get('timeout');
$speed = $params->get('speedeffect');
$pause = $params->get('pausehover');
$navigation = $params->get('navigation');
$wnext = "";
$wprev = "";

if (intval($navigation) == 1) {
	$navenable=true;
	$wnext="next: '#vikslider_next',";
	$wprev="prev: '#vikslider_prev'";
}elseif ((int)$timeout == 0) {
	$wnext="next: '#vikslider_".$viksliderid."'";
	$wprev="";
}

//virgola check
$speed.=(!empty($wnext) || !empty($wprev) ? "," : "");

$decl="var jq = jQuery.noConflict();\n";
$decl.="jq(document).ready(function(){		
		jq('#vikslider_$viksliderid').cycle({
			fx:     '$effect', 
			timeout: $timeout, 
			pause: $pause,
			speed: $speed
			$wnext 
			$wprev 
		});
		});";
$document->addScriptDeclaration($decl);

for($v = 1; $v <= 10; $v++) {
	$getslide = $params->get('vikslide_'.$v);
	$getalt = $params->get('alt'.$v);
	$getlink = $params->get('link_'.$v);
	if (!empty($getslide) && file_exists('./images/vikslider/'.$getslide)) {
		if (@getimagesize('./images/vikslider/'.$getslide)) {
		$textimg = $params->get('text_'.$v);
		if (!empty($textimg)) {
			$spantextimg = "<span class=\"vikslidertxtimg\">". $textimg ."</span>";
		} else {
			$spantextimg = "";
		}
			if (!empty($getlink)) {
				$arrslide[]="<a href=\"".$getlink."\"><img alt=\"".$getalt."\" src=\"".JURI::root().'images/vikslider/'.$getslide."\" border=\"0\"/>".$spantextimg."</a>";
			}else {
				
				if (!empty($textimg)) {
					$arrslide[]="<div class=\"viksliderimgcontainer\"><img alt=\"".$getalt."\" src=\"".JURI::root().'images/vikslider/'.$getslide."\"/><span class=\"vikslidertxtimg\">". $textimg ."</span></div>";
				} else {
					$arrslide[]="<img alt=\"".$getalt."\" src=\"".JURI::root().'images/vikslider/'.$getslide."\"/>";
				}
			}
		}else {
			if (!empty($getlink)) {
				$contslide = "<a href=\"".$getlink."\">".file_get_contents('./images/vikslider/'.$getslide)."<span class=\"vikslidertxtimg\">". $textimg ."</span></a>";
			}else {
				$contslide = file_get_contents('./images/vikslider/'.$getslide);
			}
			$arrslide[]="<div style=\"position: absolute; top: 0px; display: block; z-index: 4; opacity: 1; height:$height; width:$width; \">".$contslide."</div>";
		}
	}
}

echo "<!-- Init VikSlider http://www.extensionsforjoomla.com/ -->	";	?>

<div class="vikslImage">
<div id="vikslider_<?php echo $viksliderid; ?>" class="<?php echo $params->get('moduleclass_sfx'); ?>" style="overflow: hidden; margin: auto; position: relative; height:<?php echo $height;?>; width:<?php $width;?>">

    <?php
    if (is_array($arrslide)) {
		foreach($arrslide as $vsl) {
			echo $vsl;
		}
	}
    ?>
</div>
<?php
if ($navenable) {
	?>
<img alt="prev" src="<?php echo JURI::root(); ?>modules/mod_vikslider/src/left.png" id="vikslider_prev" style="cursor: pointer;"/>
<img alt="next" src="<?php echo JURI::root(); ?>modules/mod_vikslider/src/right.png" id="vikslider_next" style="cursor: pointer;"/>


<?php
}
echo "<!-- End VikSlider http://www.extensionsforjoomla.com/ -->	";

?>
</div>

	