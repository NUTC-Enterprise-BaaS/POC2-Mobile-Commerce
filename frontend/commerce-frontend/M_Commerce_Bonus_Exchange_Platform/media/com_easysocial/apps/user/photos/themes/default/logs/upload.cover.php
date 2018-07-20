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
<?php echo JText::_('APP_USER_PHOTOS_ACTIVITY_LOG_UPDATED_COVER'); ?>

<div class="es-stream-photo-row mt-10 mb-10">
	<a class="es-stream-item-photo" href="<?php echo $photo->getPermalink();?>">
		<div class="es-photo-image" style="background-image: url(<?php echo $actor->getCover();?>);background-position: <?php echo $actor->getCoverPosition();?>;"></div>
	</a>
</div>
