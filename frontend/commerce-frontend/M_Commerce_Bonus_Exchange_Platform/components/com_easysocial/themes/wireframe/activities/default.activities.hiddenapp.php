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
<?php if( $apps ){ ?>
<div class="es-streams">
	<ul data-apps-list class="es-stream-list fd-reset-list">
		<?php foreach( $apps as $app ){ ?>
			<?php echo $this->loadTemplate( 'site/activities/default.activities.hiddenapp.item' , array( 'app' => $app ) ); ?>
		<?php } ?>
	</ul>
</div>
<?php } else { ?>
<div class="center mt-20">
	<i class="icon-es-empty-activity mb-10"></i>
	<div>
		<?php echo JText::_('COM_EASYSOCIAL_ACTIVITY_NO_APPS_FOUND'); ?>
	</div>
</div>
<?php } ?>
