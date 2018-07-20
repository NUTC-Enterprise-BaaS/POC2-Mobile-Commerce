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
// Source for image url
if ($params->get('image_background_source') == 'local'){
	$sourceUrl = JURI::base();
} else {
	$sourceUrl = '';
}
// CSS for background image
$document->addStyleDeclaration('
	#pri-background-image-'.$module_id.' {
		background-image: url("'. $sourceUrl .''.$params->get('image_background').'") !important;
		background-color:'.$params->get('image_background_color').' !important;
		background-size: '.$params->get('image_background_size').' !important;
	   	background-attachment: '.$params->get('image_background_attachment').' !important;
		background-position: '.$params->get('image_background_position').' !important;
		background-repeat: '.$params->get('image_background_repeat').' !important;
	}
');
?>

<div id="pri-background-container-<?php echo $module_id; ?>" class="pri-background-container 
	pri-background-container-<?php echo $params->get('image_background_attachment'); ?>">
    <div id="pri-background-<?php echo $module_id; ?>" class="pri-background-inner pri-background-size">
       	<div id="pri-background-image-<?php echo $module_id; ?>" class="pri-background-image pri-background-size"></div>
        <?php include JPATH_ROOT . '/modules/mod_pri_background/includes/overlay.php'; ?>
    </div>
</div>

