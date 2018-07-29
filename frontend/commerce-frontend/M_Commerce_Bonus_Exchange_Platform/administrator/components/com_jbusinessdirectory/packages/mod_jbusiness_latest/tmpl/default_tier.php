<?php
/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined('_JEXEC') or die('Restricted access');
$max_numbers_row = $params->get('max-numbers-row');
$showHeader = $params->get('show-header');
if(!empty($max_numbers_row) && is_numeric($max_numbers_row)) {
	$percent = 100/$max_numbers_row;
	$width = $percent.'%'; ?>
	<style type="text/css">
		.dynamic-col {height: 160px; float: left}
	</style>
<?php } else { ?>
	<style type="text/css">
		.dynamic-col {height: 160px; float: left}
	</style>
<?php } ?>
<style type="text/css">
	@media (max-width: 970px) { .dynamic-col {width: 25%;} }
	@media (max-width: 767px) { .dynamic-col {width: 33.3333%;} }
	@media (max-width: 599px) { .dynamic-col {width: 50%;} }
</style>

<div class="row grid-divider">
	<div id="latestbusiness" class="latestbusiness<?php echo $moduleclass_sfx; ?>" >
		<div class="bussiness responsive">
			<?php if(!empty($items)) { ?>
				<?php
				$categories = array();
				foreach ($items as $company) {
					array_push($categories, $company->mainCategory);
				} ?>

				<?php 
				$categories = array_unique($categories);
				sort($categories);
				foreach (array_unique($categories) as $categoryName) { ?>
					<?php if($showHeader){?>
						<div class="col-md-12">
							<h1><?php echo $categoryName; ?></h1>
						</div>
					<?php } ?>
					<div class="row">
						<div class="col-md-12">
							<div class="business-row-container">
								<ul>
									<?php foreach ($items as $company) { ?>
										<?php if ($company->mainCategory == $categoryName) { ?>
											<li>
												<div class="company-box dynamic-col remove-padding">
													<div class="full-width-logo slider-item" style="<?php echo $borderCss ?>">
														<div class="offer-overlay">
															<div class="offer-vertical-middle">
																<div> 
																	<a href="<?php echo JBusinessUtil::getCompanyLink($company, true)?>" class="btn-view"><?php echo $company->companyName; ?></a>
																</div>
															</div>
														</div>
														<a href="<?php echo JBusinessUtil::getCompanyLink($company, true)?>">
															<?php if(isset($company->logoLocation) && $company->logoLocation!='') { ?>
																<img title="<?php echo $company->name?>" alt="<?php echo $company->name?>" src="<?php echo JURI::root().PICTURES_PATH.$company->logoLocation ?>">
															<?php } else { ?>
																<img title="<?php echo $company->name?>" alt="<?php echo $company->name?>" src="<?php echo JURI::root().PICTURES_PATH.'/no_image.jpg' ?>">
															<?php } ?>
														</a>
													</div>
												</div>
											</li>
										<?php } ?>
									<?php } ?>
								</ul>
							</div>
						</div>
					</div>
				<?php } ?>
			<?php } ?>
		</div>
		<?php if($params->get('showviewall')){?>
			<div class="view-all-items">
				<a href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=search'); ?>"><?php echo JText::_("LNG_VIEW_ALL")?></a>
			</div>
		<?php }?>
	</div>
</div>

<script>
jQuery(document).ready(function(){
	<?php 
	$load = JRequest::getVar("latitude");
	if($params->get('geo_location') && empty($load)){ ?>
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(addCoordinatesToUrl);
		}
	<?php } ?>
});
</script>
