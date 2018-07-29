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
// Image source
if ($params->get('random_image_background_source') == 'local'){
	$sourceUrl = JURI::base();
} else {
	$sourceUrl = '';
}
// Image URLS
$urls = ltrim(''.$params->get('random_image_backgrounds_url').'', ',');
$urls = explode(',', $urls);
$newurls = array();
foreach($urls as $url) $newurls[] = trim($url);
$urls = $newurls;

// CSS for Background Image
$document->addStyleDeclaration('
	#pri-background-random-image-'.$module_id.' {
		background-image:url("'. $sourceUrl .''.$urls[array_rand($urls)].'") !important;
		background-color:'.$params->get('random_image_background_color').' !important;
		background-size: '.$params->get('random_image_background_size').' !important;
    	background-attachment: '.$params->get('random_image_background_attachment').' !important;
		background-position: '.$params->get('random_image_background_position').' !important;
		background-repeat: '.$params->get('random_image_background_repeat').' !important;
	}
');

?>

<div id="pri-background-container-<?php echo $module_id; ?>" class="pri-background-container
	pri-background-container-<?php echo $params->get('random_image_background_attachment'); ?>">
    <div id="pri-background-<?php echo $module_id; ?>" class="pri-background-inner pri-background-size">
       	<div id="pri-background-random-image-<?php echo $module_id; ?>" class="pri-background-image pri-background-size"></div>
        <?php include JPATH_ROOT . '/modules/mod_pri_background/includes/overlay.php'; ?>
    </div>
</div>
