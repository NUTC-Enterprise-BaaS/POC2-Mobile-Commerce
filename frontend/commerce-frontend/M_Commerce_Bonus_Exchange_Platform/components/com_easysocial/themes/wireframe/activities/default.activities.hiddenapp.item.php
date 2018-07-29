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
<li class="type-<?php echo $app->context; ?> es-stream-mini"
	data-id="<?php echo $app->id;?>"
	data-context="<?php echo $app->context;?>"
	data-hidden-app-item
>
	<div class="es-stream activityData">
		<div class="media">
			<div class="media-body">
				<div class="row activity-meta">
					<div class="col-md-12">
						<div class="activity-title" data-hidden-app-content>
							<div class="pull-left mt-5">
								<?php echo JText::sprintf( 'COM_EASYSOCIAL_ACTIVITY_HIDDEN_APPS_NOTICE', $app->context ); ?>
							</div>

							<div class="pull-right">
								<a href="javascript:void(0);" class="btn btn-es-success btn-sm" data-hidden-app-unhide><?php echo JText::_('COM_EASYSOCIAL_BUTTON_UNHIDE_APP' ); ?></a>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>

	</div>
</li>
