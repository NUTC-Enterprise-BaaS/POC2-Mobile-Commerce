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

$img 	= ( $item->image ) ? $item->image : '';
?>
<li data-search-item
	data-search-item-id="<?php echo $item->id; ?>"
	data-search-item-type="<?php echo $item->utype; ?>"
	data-search-item-typeid="<?php echo $item->uid; ?>"
	>
	<div class="es-item">
		<a href="<?php echo JRoute::_( $item->link ); ?>" class="es-avatar pull-left mr-10">
			<img src="<?php echo $img ?>" title="<?php echo $this->html( 'string.escape' , $item->title ); ?>" class="avatar" />
		</a>

		<div class="es-item-body">
			<div class="es-item-detail">
				<ul class="fd-reset-list">
					<li>
						<span class="es-item-title">
							<i class="fa <?php echo $item->icon; ?> mr-5"></i>
							<a href="<?php echo JRoute::_( $item->link ); ?>">
								<?php echo $item->title; ?>
							</a>
						</span>
					</li>
					<li class="item-meta">
						<?php
							$group = FD::group( $item->uid );
						?>
						<?php if( $group->isOpen() ){ ?>
						<span class="label label-success" data-original-title="<?php echo FD::_('COM_EASYSOCIAL_GROUPS_OPEN_GROUP_TOOLTIP' , true );?>" >
							<i class="fa fa-globe"></i> <?php echo JText::_( 'COM_EASYSOCIAL_GROUPS_OPEN_GROUP' ); ?>
						</span>
						<?php } ?>

						<?php if( $group->isClosed() ){ ?>
						<span class="label label-important" data-original-title="<?php echo FD::_('COM_EASYSOCIAL_GROUPS_CLOSED_GROUP_TOOLTIP' , true );?>">
							<i class="fa fa-lock"></i> <?php echo JText::_( 'COM_EASYSOCIAL_GROUPS_CLOSED_GROUP' ); ?>
						</span>
						<?php } ?>

						<?php if( $group->isInviteOnly() ){ ?>
						<span class="label label-warning" data-original-title="<?php echo FD::_('COM_EASYSOCIAL_GROUPS_INVITE_GROUP_TOOLTIP' , true );?>">
							<i class="fa fa-lock"></i> <?php echo JText::_( 'COM_EASYSOCIAL_GROUPS_INVITE_GROUP' ); ?>
						</span>
						<?php } ?>
					</li>
				</ul>
			</div>
		</div>

	</div>

</li>
