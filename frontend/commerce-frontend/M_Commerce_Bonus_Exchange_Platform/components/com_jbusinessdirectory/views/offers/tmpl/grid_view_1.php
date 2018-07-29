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

$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
$user = JFactory::getUser();

$showData = !($user->id==0 && $appSettings->show_details_user == 1);

?>

<!-- layout -->
<div id="layout" class="pagewidth clearfix grid4 grid-view-1" <?php echo !$this->appSettings->offers_view_mode?'style="display: none"':'' ?>>

<div id="grid-content">
	<div id="loops-wrapper" class="loops-wrapper infinite-scrolling AutoWidthElement">

	<?php 
	if(!empty($this->offers)){
		foreach($this->offers as $index=>$offer){
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
					<div class="offer-company">
						<span><i class="dir-icon-building"></i> <?php echo $offer->company_name ?></span>
					</div>
					<?php if(JBusinessUtil::getLocationText($offer)!=""){ ?>
						<div class="post-date" ><span itemprop="address"><i class="dir-icon-map-marker dir-icon-large"></i> <?php echo JBusinessUtil::getLocationText($offer)?></span></div>
					<?php } ?>
					<div class="offer-dates">
						<i class="dir-icon-calendar"></i>
						<?php 
							echo JBusinessUtil::getDateGeneralShortFormat($offer->startDate)." - ".JBusinessUtil::getDateGeneralShortFormat($offer->endDate);
						?>
					</div>
					
					<?php if(!empty($offer->show_time) && JBusinessUtil::getRemainingtime($offer->endDate)!=""){?>
						<div class="offer-dates">
							<span ><i class="dir-icon-clock-o"></i> <?php echo JBusinessUtil::getRemainingtime($offer->endDate)?></span>
						</div>
					<?php } ?>
					
					<?php if(!empty($offer->categories) && false){ ?>
						<p class="company-clasificaiton">
							<span class="post-category">
								<?php 
									$categories = explode('#',$offer->categories);
									foreach($categories as $i=>$category){
										$category = explode("|", $category);
										?>
											 <a rel="nofollow" href="<?php echo JBusinessUtil::getOfferCategoryLink($category[0], $category[2]) ?>"><?php echo $category[1]?></a><?php echo $i<(count($categories)-1)? ',&nbsp;':'' ?>
										<?php 
									}
								?>
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
		}
	 ?>	
	 <div class="clear"></div>
	</div>
</div>
</div>	
