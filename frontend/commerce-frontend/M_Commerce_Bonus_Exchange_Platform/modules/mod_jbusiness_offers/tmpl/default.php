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
<div class="latest-offers<?php echo $moduleclass_sfx; ?>">
	<div class="offers-wrapper row-fluid">
	<?php if(!empty($items)){ ?>
	
	<?php $counter = 0; ?>
	<?php foreach ($items as $offer){  $counter++?>
	
			<div class="offer-item span3">
				<div class="offer-image">
					<div class="offer-overlay">
						<div class="offer-vertical-middle">
							<div> 
								<a href="<?php echo $offer->link?>" class="btn-view"><?php echo JText::_("LNG_VIEW")?></a>
							</div>
						</div>
						
					</div>
					<a href="<?php echo $offer->link ?>">
						<?php if(!empty($offer->picture_path)){?>
							<div class="dir-bg-image" style="background-image: url('<?php echo JURI::root().PICTURES_PATH.$offer->picture_path ?>')"></div>
						<?php } else { ?>
							<div class="dir-bg-image" style="background-image: url('<?php echo JURI::root().PICTURES_PATH.'/no_image.jpg' ?>')"></div>
						<?php } ?>
					</a>
				</div>
				<div class="offer-title">
					<a title="<?php echo $offer->subject?>" href="<?php echo $offer->link ?>"><?php echo $offer->subject?></a>
				</div>
			</div>
	
		
		<?php if( $counter%4==0 && $counter>3){?>
					</div>
					<div class="offers-wrapper row-fluid">
				<?php }?>
	<?php } ?>
	
	<?php } ?>
	</div>
	
	<?php if($params->get('showviewall')){?>
		<div class="view-all-offers">
			<a href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=offers'); ?>"><?php echo JText::_("LNG_VIEW_ALL_OFFERS")?></a>
		</div>
	<?php } ?>
</div>