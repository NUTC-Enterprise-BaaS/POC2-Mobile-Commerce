<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>

<?php if (empty($imports)) { ?>
<div class="alert"><?php echo JText::_('COM_EASYSOCIAL_THEMES_COMPILER_NO_IMPORTS_YET'); ?></div>
<?php } else { ?>
<table class="es-theme-compiler-status table table-striped table-bordered table-condensed" data-imports>
<?php foreach($imports as $file) { ?>
	<tr class="is-<?php echo $file->state; ?>">
		<td><span class="label"><?php echo $file->state; ?></span></td>
		<td width="100%">
			<a href="<?php echo $file->uri; ?>" target="_blank"><?php echo $file->name; ?></a>
			<br/>
			<small><?php echo $file->path; ?></small>
		</td>
	</tr>
<?php } ?>
</table>
<?php } ?>
