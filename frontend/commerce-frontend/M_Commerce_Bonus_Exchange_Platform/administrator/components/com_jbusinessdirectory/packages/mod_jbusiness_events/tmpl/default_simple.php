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

<div id="events-container" class="latest-events<?php echo $moduleclass_sfx; ?>">
	<div class="events-wrapper row-fluid">
		<?php if(isset($items)){ ?>
			<?php $counter = 0; ?>
			<?php foreach($items as $i=>$item){ $counter++?>
				<article id="event-<?php echo $item->id ?>" class="event-item span3">
					<div class="event-item-content">
						<figure class="item-thumbnail">
							<a href="<?php echo JBusinessUtil::getEventLink($item->id, $item->alias) ?>">
								<?php if(!empty($item->picture_path)){?>
									<div class="dir-bg-image" style="background-image: url('<?php echo JURI::root().PICTURES_PATH.$item->picture_path ?>')"></div>
								<?php } else { ?>
									<div class="dir-bg-image" style="background-image: url('<?php echo JURI::root().PICTURES_PATH.'/no_image.jpg' ?>')"></div>
								<?php } ?>
							</a>
						</figure>
						
						<div class="entry-date">
							<div class="day"><?php echo JBusinessUtil::getDayOfMonth($item->start_date) ?></div>
							<span class="month"><?php echo JBusinessUtil::getMonth($item->start_date) ?></span>
							<span class="year"><?php echo JBusinessUtil::getYear($item->start_date) ?></span>
						</div>
						
						<div>
							<h3><a href="<?php echo JBusinessUtil::getEventLink($item->id, $item->alias) ?>"><?php echo stripslashes($item->name)?></a></h3>
							<div class="event-description"><?php echo $item->short_description ?></div>
							<div class="item-location">
								<a class="location" href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=events&citySearch='.$item->city); ?>"><?php echo $item->city ?></a>
							</div>
						</div>
					</div>
			</article>
		
			<?php if( $counter%4==0 && $counter>3){?>
				</div>
				<div class="events-wrapper row-fluid">
			<?php }?>
		<?php 
				}
			}
		 ?>	
		 <div class="clear"></div>
	</div>	
	
	<?php if($params->get('showviewall')){?>
		<div class="view-all-items">
			<a href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=events'); ?>"><?php echo JText::_("LNG_VIEW_ALL")?></a>
		</div>
	<?php }?>
</div>