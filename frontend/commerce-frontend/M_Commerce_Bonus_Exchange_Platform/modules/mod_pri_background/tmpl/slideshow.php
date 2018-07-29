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
$document = JFactory::getDocument();

// Image URLs
$urls = ltrim(''.$params->get('slideshow_background_urls').'', ',');
$urls = explode(',', $urls);
$newurls = array();
foreach($urls as $url) $newurls[] = trim($url);
$urls = $newurls;
if ($params->get('slideshow_background_source') == 'local'){
	$sourceUrl = JURI::base();
} else {
	$sourceUrl = '';
}
// CSS
$document->addStyleDeclaration('
	#pri-background-slideshow-'.$module_id.' div {
		background-size: '.$params->get('slideshow_background_size').' !important;
	   	background-attachment: '.$params->get('slideshow_background_attachment').' !important;
		background-position: '.$params->get('slideshow_background_position').' !important;
		background-repeat: '.$params->get('slideshow_background_repeat').' !important;
	}
');
?>

<div id="pri-background-container-<?php echo $module_id; ?>" class="pri-background-container 
	pri-background-container-<?php echo $params->get('slideshow_background_attachment'); ?>">
    <div id="pri-background-<?php echo $module_id; ?>" class="pri-background-inner pri-background-size">
       	<div id="pri-background-slideshow-<?php echo $module_id; ?>" class="pri-background-slideshow pri-background-size">
       		<?php foreach ($urls as $url) { ?>
				<div class="pri-background-slider" style="background-image: url('<?php echo  $sourceUrl . trim($url) ?>');"></div>
			<?php } ?>
       	</div>
        <?php
        	include JPATH_ROOT . '/modules/mod_pri_background/includes/overlay.php';
        ?>
    </div>
</div>

<script type="text/javascript">
	(function($){
		$("#pri-background-slideshow-<?php echo $module_id; ?> > div:gt(0)").hide();

		setInterval(function() { 
		$('#pri-background-slideshow-<?php echo $module_id; ?> > div:first')
	    	.fadeOut(<?php echo $params->get('slideshow_fade_duration'); ?>)
	    	.next()
	    	.fadeIn(<?php echo $params->get('slideshow_fade_duration'); ?>)
	    	.end()
	    	.appendTo('#pri-background-slideshow-<?php echo $module_id; ?>');
		},  <?php echo $params->get('slideshow_duration'); ?>);
	})(jQuery);
</script>

