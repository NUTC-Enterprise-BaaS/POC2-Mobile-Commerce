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
<div data-field-file-list>
<?php foreach( $value as $file ) { ?>
	<div class="data-field-file-item">
		<div class="file-name">
		<?php if( $params->get( 'allow_download' ) ) { ?>
			<a href="<?php echo $file->downloadLink; ?>" target="_blank">
		<?php } ?>
		<?php if( $params->get( 'allow_preview' ) && $file->hasPreview() ) { ?>
		<div class="text-center mt-5 mb-5"><img src="<?php echo $file->previewLink; ?>" style="max-height: <?php echo $params->get( 'preview_max_height' ); ?>px; max-width: <?php echo $params->get( 'preview_max_width' ); ?>px;" /></div>
		<?php } ?>
		<?php echo $file->name; ?>
		<?php if( $params->get( 'allow_download' ) ) { ?>
			</a>
		<?php } ?>
		</div>
	</div>
<?php } ?>
</div>
