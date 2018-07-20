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
<div class="es-widget es-widget-borderless">
	<?php if( $module->showtitle ){ ?>
	<div class="es-widget-head">
		<?php echo JText::_( $module->title ); ?>
	</div>
	<?php } ?>

	<div class="es-widget-body pl-0 pl-5 pr-5">
		<?php echo $contents; ?>
	</div>
</div>
