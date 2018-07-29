<?php  
/**
* Mod_VikWallImage
* http://www.extensionsforjoomla.com
*/

// no direct access
defined('_JEXEC') or die('Restricted Area');

//$arrslide = array();

	//$document = & JFactory :: getDocument();
	$background_img = $params->get('background');
	$gettext = $params->get('textarea');
	if ($params->def('prepare_content', 1)) {
		JPluginHelper::importPlugin('content');
		$gettext = JHtml::_('content.prepare', $gettext, '', 'mod_vikwallimage.content');
	}
	$mask = $params->get('enablemask');
	$get_colormask = $params->get('colormask', '#000000');
	$get_opacitymask = $params->get('opacitymask');
?>

<div class="vikwallimage-container<?php echo $params->get('moduleclass_sfx'); ?>">
<div class="vikwallimage-inner" style="background-image:url(<?php echo $background_img; ?>);">
	<?php if($mask == 1) { ?>
		<div class="vikwallimage-mask" style="background-color:<?php echo $get_colormask; ?>; opacity: <?php echo $get_opacitymask; ?>;"></div>
	<?php } ?>
		<div class="vikwallimage-desc">
			<?php echo $gettext; ?>
		</div>
</div>
</div>
<?php

?>

	