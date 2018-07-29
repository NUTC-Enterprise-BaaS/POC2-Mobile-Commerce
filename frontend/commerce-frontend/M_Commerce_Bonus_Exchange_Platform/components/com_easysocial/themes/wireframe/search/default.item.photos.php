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
						<div class="es-item-title" style="word-break:break-all;">
							<i class="fa <?php echo $item->icon;?> mr-5"></i> <a href="<?php echo JRoute::_($item->link); ?>"><?php echo $item->title; ?></a>
						</div>
					</li>
					
					<li class="item-meta">
						<?php echo JText::sprintf( 'COM_EASYSOCIAL_SEARCH_RESULT_PHOTOS_UPLOADED_ON' , $this->html( 'string.date' , $item->finder->publish_start_date , 'COM_EASYSOCIAL_PHOTOS_DATE_FORMAT') ); ?>
					</li>
				</ul>
			</div>
		</div>

	</div>

</li>
