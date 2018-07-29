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
<div class="es-widget">
	<div class="es-widget-head">
		<div class="pull-left widget-title">
			<?php echo JText::_('APP_GROUP_GROUPS_ONLINE_MEMBERS_WIDGET_TITLE');?>
		</div>
		<span class="widget-label">(<?php echo $total;?>)</span>
	</div>

	<div class="es-widget-body">
		<?php if( $users ){ ?>
		<ul class="widget-list-grid">
			<?php foreach( $users as $user ){ ?>
			<li>
				<div class="es-avatar-wrap">
					<a href="<?php echo $user->getPermalink();?>" class="es-avatar es-avatar-sm" data-popbox="module://easysocial/profile/popbox" data-user-id="<?php echo $user->id;?>">
						<img alt="<?php echo $this->html( 'string.escape' , $user->getName() );?>" src="<?php echo $user->getAvatar();?>" />
					</a>
				</div>
			</li>
			<?php } ?>
		</ul>
		<?php } else { ?>
		<div class="fd-small empty">
			<?php echo JText::_( 'APP_GROUP_GROUPS_NO_ONLINE_USERS' ); ?>
		</div>
		<?php } ?>
	</div>
</div>
