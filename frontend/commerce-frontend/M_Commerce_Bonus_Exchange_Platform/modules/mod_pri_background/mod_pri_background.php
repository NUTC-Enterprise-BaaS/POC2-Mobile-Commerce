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
$module_name = basename(dirname(__FILE__));
$module_dir = $module->module;
$module_id = $module->id;

/* Load mobile_detect */
if(!class_exists('Mobile_Detect')) {
	require_once JPATH_SITE . '/modules/' . $module_dir . '/libraries/Mobile_Detect.php';
}
/* Load Minifier */
if(!class_exists('Minifier')) {
	require_once JPATH_SITE . '/modules/' . $module_dir . '/libraries/Minifier.php';
}
$detect = new Mobile_Detect;
$is_mobile = ($detect->isMobile() === true) or ($detect->isTablet() === true);
$document->addStyleSheet(JURI::root() .'modules/mod_pri_background/assets/css/pri-background.css');

/* Custom CSS */
$document->addStyleDeclaration(' '.$params->get('custom_css').'');

/* Background Selector */
$document->addStyleDeclaration('
	'.$params->get('background_selector').' {
		position: relative;
		background-image: none !important;
		background-color: none !important;
		z-index: 1;
	}
');

/* Load Layout */
require JModuleHelper::getLayoutPath('mod_pri_background', $params->get('background_type'));

?>

<script type="text/javascript">
	/* Background Selector */
	(function($){
		$('#pri-background-container-<?php echo $module_id; ?>').appendTo('<?php echo $params->get('background_selector'); ?>');
	})(jQuery);
</script>