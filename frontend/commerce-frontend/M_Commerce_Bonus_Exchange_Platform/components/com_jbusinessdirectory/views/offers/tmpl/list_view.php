<div id="offer-list-view" class='offer-container <?php echo $fullWidth ?'full':'noClass' ?>' <?php echo $this->appSettings->offers_view_mode?'style="display: none"':'' ?>>
	<ul class="offer-list">
	<?php 
		if(isset($this->offers) && count($this->offers)>0){
			foreach ($this->offers as $offer){ ?>
				<li>
					<div class="offer-box row-fluid <?php echo !empty($offer->featured)?"featured":"" ?>">
						<div class="offer-img-container span3">
							<a class="offer-image" href="<?php echo $offer->link ?>">
								<?php if(isset($offer->picture_path) && $offer->picture_path!=''){?>
									<img  alt="<?php ?>" src="<?php echo JURI::root()."/".PICTURES_PATH.$offer->picture_path?>">
								<?php }else{?>
									<img title="<?php echo $offer->subject?>" alt="<?php echo $offer->subject?>" src="<?php echo JURI::root().PICTURES_PATH.'/no_image.jpg' ?>">
								<?php } ?>
							</a>
						</div>
						<div class="offer-content span9">
							<div class="offer-subject">
								<a title="<?php echo $offer->subject?>"
									href="<?php echo $offer->link ?>"><?php echo $offer->subject?>
								</a>
							</div>
							<div class="offer-company">
								<span><i class="dir-icon-building dir-icon-large"></i> <?php echo $offer->company_name ?></span>
							</div>
							<?php if(JBusinessUtil::getLocationText($offer)!=""){ ?>
								<div class="offer-location">
									<span itemprop="address"><i class="dir-icon-map-marker dir-icon-large"></i> <?php echo JBusinessUtil::getLocationText($offer)?></span>
								</div>
							<?php } ?>
							
							<?php if((!empty($offer->startDate) && $offer->startDate!="0000-00-00") || (!empty($offer->endDate) && $offer->endDate!="0000-00-00")){?>
								<div class="offer-dates">
									<i class="dir-icon-calendar"></i>
									<?php 
										echo JBusinessUtil::getDateGeneralFormat($offer->startDate)." - ". JBusinessUtil::getDateGeneralFormat($offer->endDate);
									?>
								</div>
							<?php } ?>
							<?php if(!empty($offer->show_time) && JBusinessUtil::getRemainingtime($offer->endDate)!=""){?>
								<div class="offer-dates">
									<span ><i class="dir-icon-clock-o dir-icon-large"></i> <?php echo JBusinessUtil::getRemainingtime($offer->endDate)?></span>
								</div>
							<?php } ?>
					
							<?php if(!empty($offer->categories)){?>
								<div class="offer-categories">
									<?php 
										$categories = explode('#',$offer->categories);
										foreach($categories as $i=>$category){
											$category = explode("|", $category);
											?>
												 <a rel="nofollow" href="<?php echo JBusinessUtil::getOfferCategoryLink($category[0], $category[2]) ?>"><?php echo $category[1]?></a><?php echo $i<(count($categories)-1)? ',&nbsp;':'' ?>
											<?php 
										}
									?>
								</div>
							<?php } ?>
							
							<div class="offer-desciption">
								<?php echo $offer->short_description ?>
							</div>
						</div>
						<?php if(isset($offer->featured) && $offer->featured==1){ ?>
							<div class="featured-text">
								<?php echo JText::_("LNG_FEATURED")?>
							</div>
						<?php } ?>
					</div>
					<div class="clear"></div>
				</li>
			<?php }
		}?>
	</ul>
</div>