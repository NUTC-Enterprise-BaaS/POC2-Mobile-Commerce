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
<div data-album-create-button class="es-album es-album-create-button">
	<div class="es-album-header"></div>
	<div class="es-album-content">
		<a data-album-cover class="es-album-cover" href="<?php echo FRoute::albums( array( 'layout' => 'form' ));?>">
			<span class="es-album-create-hint"><?php echo JText::_("COM_EASYSOCIAL_CREATE_NEW_ALBUM"); ?></span>
		</a>
	</div>
</div>
