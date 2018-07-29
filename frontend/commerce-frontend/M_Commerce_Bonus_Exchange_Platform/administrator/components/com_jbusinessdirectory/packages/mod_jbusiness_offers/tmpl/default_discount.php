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

<div id="latest-offers-discount" class="latest-offers<?php echo $moduleclass_sfx; ?>">
	<div class="offers-wrapper row-fluid">
		<?php if(isset($items)){ ?>
			<?php $counter = 0; ?>
			<?php foreach($items as $i=>$item){ $counter++?>
				<article id="offer-<?php echo $item->id ?>" class="offer-item span3">
					<?php 
						$discount = 0;
						if(!empty($item->price) && $item->specialPrice>0){
							$discount = round((($item->price -$item->specialPrice) * 100)/$item->price ,0);
						}
					?>
					
					<?php if(!empty($discount)){?>
						<div class="offer-discount"> <?php echo JText::_("LNG_DISCOUNT") ." ".$discount?> %</div>
					<?php }?>

					<div class="offer-item-content">
						<figure class="offer-image">
							<a href="<?php echo $item->link ?>">
								<?php if(!empty($item->picture_path)){?>
									<div class="dir-bg-image" style="background-image: url('<?php echo JURI::root().PICTURES_PATH.$item->picture_path ?>')"></div>
								<?php } else { ?>
									<div class="dir-bg-image" style="background-image: url('<?php echo JURI::root().PICTURES_PATH.'/no_image.jpg' ?>')"></div>
								<?php } ?>
							</a>
						</figure>
						<div>
							<h3><a href="<?php echo  $item->link ?>"><?php echo stripslashes($item->subject)?></a></h3>
							<div class="offer-city"><?php echo $item->city.', '.$item->county ?></div>
							<div class="clear"></div>	
							<div class="offer-price">
								<?php if(!empty($item->price) && $item->specialPrice>0){ ?>
									<span class="old-price"><?php echo JBusinessUtil::getPriceFormat($item->price) ?></span>
								<?php } ?>
								<?php if(!empty($item->specialPrice)){?>
									<span class="price red"><?php echo JBusinessUtil::getPriceFormat($item->specialPrice); ?></span>
								<?php }?>
							</div>
							<div class="offer-action">
								<a class="" href="<?php echo $item->link ?>">
									<span class=""><?php echo JText::_("LNG_VIEW_DETAILS")?></span>
								</a>
							</div>				
						</div>
					</div>
			</article>
		
			<?php if( $counter%4==0 && $counter>3){?>
				</div>
				<div class="offers-wrapper row-fluid">
			<?php }?>
		<?php 
				}
			}
		 ?>	
		 <div class="clear"></div>
	</div>	
	
	<?php if($params->get('showviewall')){?>
		<div class="view-all-offers">
			<a href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=offers'); ?>"><?php echo JText::_("LNG_VIEW_ALL_OFFERS")?></a>
		</div>
	<?php } ?>
</div>