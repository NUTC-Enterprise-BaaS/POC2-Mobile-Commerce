<?php
/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<div class="latest-events<?php echo $moduleclass_sfx; ?>">
	<div class="events-wrapper row-fluid">
	<?php if(!empty($items)){ ?>
		<?php $counter = 0; ?>
		<?php foreach ($items as $event){ $counter++  ?>
			<div class="event-item-container span3">
				<div class="event-image">
					<div class="event-overlay">
						<div class="event-vertical-middle">
							<div> 
								<a href="<?php echo JBusinessUtil::getEventLink($event->id, $event->alias)?>" class="btn-view"><?php echo JText::_("LNG_VIEW")?></a>
							</div>
						</div>
					</div>
					<a href="<?php echo JBusinessUtil::getEventLink($event->id, $event->alias) ?>">
						<?php if(!empty($event->picture_path)){?>
							<div class="dir-bg-image" style="background-image: url('<?php echo JURI::root().PICTURES_PATH.$event->picture_path ?>')"></div>
						<?php } else { ?>
							<div class="dir-bg-image" style="background-image: url('<?php echo JURI::root().PICTURES_PATH.'/no_image.jpg' ?>')"></div>
						<?php } ?>
					</a>
				</div>
				<div class="event-title">
					<a
						title="<?php echo $event->name?>"
						href="<?php echo JBusinessUtil::getEventLink($event->id, $event->alias) ?>"><?php echo $event->name?>
						</a>
				</div>
			</div>
			<?php if( $counter%4==0 && $counter>3){?>
				</div>
				<div class="events-wrapper row-fluid">
			<?php }?>
		<?php } ?>
	<?php } ?>
	<div class="clear"></div>
	<?php if($params->get('showviewall')){?>
		<div class="view-all-items">
			<a href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=events'); ?>"><?php echo JText::_("LNG_VIEW_ALL")?></a>
		</div>
	<?php }?>
</div>