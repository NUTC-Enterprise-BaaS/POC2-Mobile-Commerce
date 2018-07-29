<?php
/**
 * @version		$Id: default.php 14276 2010-01-18 14:20:28Z laurelle $
 * @package		Joomla.Administrator
 * @subpackage	mod_kc_admin_quickicons
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @copyright	Copyright (C) 2010 - 2014 Keashly.ca Consulting
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

$buttons = KC_Admin_QuickIconHelper::getButtons( $params );
?>
<div class="row-striped<?php echo $params->get('moduleclass_sfx'); ?> kc_admin_quickicons_cpanel<?php echo $params->get('moduleclass_sfx'); ?>">
<?php
foreach ($buttons as $button):
	echo KC_Admin_QuickIconHelper::button($button);
endforeach;
?>
</div>