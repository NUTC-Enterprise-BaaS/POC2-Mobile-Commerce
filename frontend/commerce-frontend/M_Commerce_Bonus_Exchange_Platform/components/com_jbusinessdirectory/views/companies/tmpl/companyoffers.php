<?php /*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<div id="grid-content" class='offers-container grid4'>
	<?php
		if(isset($this->offers) && count($this->offers)){ 
			foreach ($this->offers as $offer){
	?>
		<article id="post-<?php echo  $offer->id ?>" class="post post type-post status-publish format-standard hentry category-food post clearfix ">
			<div class="post-inner">
				<figure class="post-image ">
						<a href="<?php echo $offer->link ?>">
							<?php if(!empty($offer->picture_path) ){?>
								<img title="<?php echo $offer->subject?>" alt="<?php echo $offer->subject?>" src="<?php echo JURI::root().PICTURES_PATH.$offer->picture_path ?>">
							<?php }else{ ?>
								<img title="<?php echo $offer->subject?>" alt="<?php echo $offer->subject?>" src="<?php echo JURI::root().PICTURES_PATH.'/no_image.jpg' ?>">
							<?php } ?>
						</a>
				</figure>
				
				<div class="post-content">
					<h1 class="post-title"><a href="<?php echo  $offer->link ?>"><?php echo $offer->subject?></a></h1>
					<span class="post-date" ><span itemprop="address"><?php echo $offer->address?>, <?php echo $offer->city	?>, <?php echo $offer->county?></span></span>
					
					<p class="offer-dates">
						<?php 
							echo JBusinessUtil::getDateGeneralFormat($offer->startDate)." - ".JBusinessUtil::getDateGeneralFormat($offer->endDate);
						?>
					</p>
					
					<?php if(!empty($offer->categories)){ ?>
					<p class="company-clasificaiton">
						<span class="offer-categories">
							<?php 
								$categories = explode('#',$offer->categories);
								foreach($categories as $i=>$category){
									$category = explode("|", $category);
									?>
										 <a rel="nofollow" href="<?php echo JBusinessUtil::getOfferCategoryLink($category[0], $category[2]) ?>"><?php echo $category[1]?><?php echo $i<(count($categories)-1)? ',&nbsp;':'' ?> </a>
								<?php }	?>
						</span> <br/>
					</p>
					<?php } ?>
				</div>
				<!-- /.post-content -->
			</div>
		<!-- /.post-inner -->
		</article>
	<?php 
			} 
		}else{
			echo JText::_("LNG_NO_COMPANY_OFFERS");
		}
	?>
	
</div>
<div class="clear"></div>	
			
		
	