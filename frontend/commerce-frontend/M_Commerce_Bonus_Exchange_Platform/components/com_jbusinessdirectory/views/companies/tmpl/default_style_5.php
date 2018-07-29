<?php /*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

require_once 'header.php';
require_once JPATH_COMPONENT_SITE.'/classes/attributes/attributeservice.php';
?>

<style>
	#sp-main-body {background-color:#f0f3f6;padding:0;}
	<?php
	if(!empty($this->company->business_cover_image)) { ?>
		.company-style-5-header-image {
			background-image:url(<?php echo JURI::root().PICTURES_PATH.$this->company->business_cover_image ?>);
			background-repeat: no-repeat;
			background-size:cover;
			background-position:center;
		}
	<?php } ?>
</style>


<div id="company-style-5-container">
	<div id="company-style-5-header">
		<div class="row-fluid">
			<!-- Business Categories -->
			<div class="company-style-5-header-image span12">
				<div class="company-style-5-header-info row-fluid">
					<div class="span9 first-column">
						<div class="span3">
							<!-- Business Logo -->
							<?php if(isset($this->company->logoLocation) && $this->company->logoLocation!=''){?>
								<img class="business-logo" title="<?php echo $this->company->name?>" alt="<?php echo $this->company->name?>" src="<?php echo JURI::root().PICTURES_PATH.$this->company->logoLocation ?>">
							<?php }else{ ?>
								<img class="business-logo" title="<?php echo $this->company->name?>" alt="<?php echo $this->company->name?>" src="<?php echo JURI::root().PICTURES_PATH.'/no_image.jpg' ?>">
							<?php } ?>
						</div>
						<div class="span9">
							<!-- Business Name -->
							<h2><?php echo isset($this->company->name)?$this->company->name:""; ?></h2>
							<div class="dir-address">
								<span itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress">
									<!-- Business Address -->
									<?php echo JBusinessUtil::getAddressText($this->company); ?>
								</span>
							</div>
							<div class="dir-categories">
								<!-- Business Categories -->
								<?php if(!empty($this->company->categories)){?>
									<?php 
										$categories = explode('#',$this->company->categories);
										foreach($categories as $i=>$category){
											$category = explode("|", $category);
											?>
												<a rel="nofollow" href="<?php echo JBusinessUtil::getCategoryLink($category[0], $category[2]) ?>"><?php echo $category[1]?></a><?php echo $i<(count($categories)-1)? ',&nbsp;':'' ?>
											<?php 
										}
									?>
								<?php } ?>
							</div>
							<div>
								<?php if($appSettings->enable_ratings) { ?> 
									<!-- Business Ratings -->
									<div class="company-info-average-rating">
										<div class="rating">
											<p class="user-rating-avg" title="<?php echo $company->averageRating?>" alt="<?php echo $company->id?>" style="display: block;"></p>
										</div>
									</div>
								<?php } ?>
	
								<?php if($appSettings->enable_reviews) { ?> 
									<!-- Business Reviews -->
									<a href="#go-company-reviews"><span><?php echo count($this->reviews); ?> <?php echo JText::_('LNG_REVIEWS'); ?></span></a>
								<?php } ?>
	
								<?php if($this->appSettings->enable_bookmarks) { ?>
									<?php if($appSettings->enable_reviews) { ?> | <?php } ?>
									<?php if(!empty($company->bookmark)) { ?>
										<!-- Business Bookmarks -->
										<a href="javascript:showUpdateBookmarkDialog()"  title="<?php echo JText::_("LNG_UPDATE_BOOKMARK")?>" class="bookmark"><i class="dir-icon-heart"></i> <span><?php echo JText::_('LNG_UPDATE_BOOKMARK'); ?></span></a>
									<?php } else {?>
										<a href="javascript:showAddBookmarkDialog()"  title="<?php echo JText::_("LNG_ADD_BOOKMARK")?>" class="bookmark"><i class="dir-icon-heart-o"></i> <span><?php echo JText::_('LNG_ADD_BOOKMARK'); ?></span></a>
									<?php } ?>
								<?php } ?>
							</div>
						</div>
					</div>
					<div class="span3 second-column">
						<?php if($appSettings->enable_socials) { ?>
							<!-- Business Socials -->
							<div class="span12">
								<?php require_once JPATH_COMPONENT_SITE."/include/social_share.php"; ?>
							</div>
						<?php } ?>
						<?php if($appSettings->enable_reviews && (!$appSettings->enable_reviews_users || $user->id !=0)){ ?>
							<div class="span12 clear">
								<!-- Business Add Review -->
								<a href="#go-company-reviews" onclick="showReviewForm()" class="ui-dir-button ui-dir-button-blue">
									<span class="ui-button-text"><?php echo JText::_("LNG_ADD_REVIEW") ?></span>
								</a>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="company-style-5-body">
		<div class="row-fluid">

			<!-- BODY -->
			<div class="span8">
				<!-- Business Gallery -->
				<?php if(!empty($this->pictures)){?>
					<div class="company-style-box">
						<div class="row-fluid">
							<div class="span12">
								<h3><i class="fa dir-icon-camera-retro"></i> <?php echo JText::_("LNG_GALLERY"); ?></h3>
								<?php require_once JPATH_COMPONENT_SITE."/include/image_gallery.php";  ?>
							</div>
						</div>
					</div>
				<?php } ?>
				<!-- Business Details -->
				<div class="company-style-box">
					<div class="row-fluid">
						<div class="span12">
							<h3><i class="fa dir-icon-newspaper-o"></i> <?php echo JText::_("LNG_COMPANY_DETAILS"); ?></h3>
							<!-- Business Slogan -->
							<?php if(isset($this->company->slogan) && strlen($this->company->slogan)>2) { ?>
								<p class="business-slogan"><?php echo  $this->company->slogan; ?> </p>
							<?php } ?>
						</div>
					</div>

					<!-- Business Description -->
					<div class="row-fluid">
						<div class="span12">
							<div id="dir-listing-description" class="dir-listing-description">
								<?php if(!empty($this->company->description)) { ?>
									<?php echo JHTML::_("content.prepare", $this->company->description); ?>
								<?php } ?>
							</div>
						</div>
					</div>
					<div class="row-fluid">
						<div class="span12">
							<dl>
								<!-- Business Type -->
								<?php if(!empty($this->company->typeName)) { ?>
									<dt><?php echo JText::_('LNG_TYPE'); ?>:</dt>
									<dd><?php echo $this->company->typeName; ?></dd>
								<?php } ?>
							
								<!-- Business Keywords -->
								<?php if(!empty($this->company->keywords)) { ?>
									<dt><?php echo JText::_('LNG_KEYWORDS'); ?>:</dt>
									<dd>
										<ul class="dir-keywords">
											<?php 
											$keywords =  explode(',', $this->company->keywords);
											for($i=0; $i<count($keywords); $i++) { ?>
												<li>
													<a  href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=search&searchkeyword='.$keywords[$i]) ?>"><?php echo $keywords[$i]?><?php echo $i<(count($keywords)-1)? ',&nbsp;':'' ?></a>
												</li>
											<?php 
											} ?>
										</ul>
									</dd>
								<?php } ?>
								
								<!-- Business Tabs -->
								<?php if((isset($this->package->features) && in_array(CUSTOM_TAB,$this->package->features) || !$appSettings->enable_packages)
					 				  && !empty($this->company->custom_tab_name)){ ?>
									<dt><?php echo $this->company->custom_tab_name; ?>:</dt>
									<dd><?php echo  $this->company->custom_tab_content;	?></dd>
								<?php } ?>

								<!-- Business Locations -->
								<?php if(!empty($this->company->locations)) { ?>
									<dt><?php echo JText::_("LNG_COMPANY_LOCATIONS"); ?>:</dt>
									<dd><?php require_once 'locations.php'; ?></dd>
								<?php } ?>
								
								<!-- Business Attachments -->
								<?php if($showData && isset($this->package->features) && in_array(ATTACHMENTS, $this->package->features) || !$appSettings->enable_packages ) { ?>
									<?php if(!empty($this->company->attachments)) { ?>
										<dt><?php echo JText::_("LNG_ATTACHMENTS"); ?>:</dt>
										<dd>
											<div class="attachments">
												<ul>
													<?php foreach($this->company->attachments as $attachment) { ?>	
														<li>
															<a href="<?php echo JURI::root()."/".ATTACHMENT_PATH.$attachment->path?>"><?php echo !empty($attachment->name)?$attachment->name:basename($attachment->path)?></a>
														</li>
													<?php } ?>
												</ul>
											</div>
										</dd>
									<?php } ?>
								<?php } ?>
							</dl>
						</div>
					</div>
					<div class="row-fluid">
						<div class="span12">
							<div class="classification">
								<?php 
								$packageFeatured = isset($this->package->features)?$this->package->features:null;
								$renderedContent = AttributeService::renderAttributesFront($this->companyAttributes,$appSettings->enable_packages, $packageFeatured);
								echo $renderedContent;
								?>
							</div>
						</div>
					</div>
				</div>

				<!-- Business Videos -->
				<?php if((isset($this->package->features) && in_array(VIDEOS,$this->package->features) || !$appSettings->enable_packages)
							&& isset($this->videos) && count( $this->videos)>0) { ?>
					<div class="company-style-box">
						<div class="row-fluid">
							<div class="span12">
								<h3><i class="fa dir-icon-video-camera"></i> <?php echo JText::_("LNG_VIDEOS")?></h3>
								<div id="company-videos">
									<?php require_once 'companyvideos.php'; ?>
									<div class="clear"></div>
								</div>
							</div>
						</div>
					</div>
				<?php }	?>

				<!-- Business Map Location -->
				<?php if((isset($this->package->features) && in_array(GOOGLE_MAP,$this->package->features) || !$appSettings->enable_packages ) 
												&& !empty($this->company->latitude) && !empty($this->company->longitude)){ ?>
					<div class="company-style-box">
						<div class="row-fluid">
							<div class="span12">
								<h3><i class="fa dir-icon-map-marker"></i> <?php echo JText::_("LNG_BUSINESS_MAP_LOCATION"); ?></h3>
								<div id="company-map">
									<?php require_once 'map.php';?>
									<div class="clear"></div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>

				<!-- Business Offers -->
				<?php if((isset($this->package->features) && in_array(COMPANY_OFFERS,$this->package->features) || !$appSettings->enable_packages)
							&& isset($this->offers) && count($this->offers) && $appSettings->enable_offers) { ?>
					<div class="company-style-box">
						<div class="row-fluid">
							<div class="span12">
								<h3><i class="fa dir-icon-tag"></i> <?php echo JText::_("LNG_COMPANY_OFFERS"); ?></h3>
								<div id="company-offers">
									<?php require_once 'companyoffers.php';?>
									<div class="clear"></div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>

				<!-- Business Events -->
				<?php if((isset($this->package->features) && in_array(COMPANY_EVENTS,$this->package->features) || !$appSettings->enable_packages)
							&& isset($this->events) && count($this->events) && $appSettings->enable_events) { ?>
					<div class="company-style-box">
						<div class="row-fluid">
							<div class="span12">
								<h3><i class="fa dir-icon-calendar"></i> <?php echo JText::_("LNG_COMPANY_EVENTS"); ?></h3>
								<div id="company-events">
									<?php require_once 'events.php';?>
									<div class="clear"></div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>

				<!-- Business Reviews -->
				<?php if($appSettings->enable_reviews) { ?>
					<div id="go-company-reviews" class="company-style-box">
						<div class="row-fluid">
							<div class="span12">
								<h3><i class="fa dir-icon-check-square-o"></i> <?php echo JText::_("LNG_BUSINESS_REVIEWS"); ?></h3>
								<div id="company-reviews">
									<?php require_once 'reviews.php'; ?>
									<div class="clear"></div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>

			<!-- SIDEBAR -->
			<div class="span4">

				<?php if((!isset($this->company->userId) || $this->company->userId == 0) && $appSettings->claim_business) { ?>
					<div class="company-style-box">
						<!-- Business Map -->
						<div class="row-fluid">
							<div class="span12">
								<div><?php echo JText::_('LNG_CLAIM_COMPANY_TEXT')?></div>
								<div class="claim-container" id="claim-container">
									<a href="javascript:void(0)" onclick="claimCompany()">
										<div class="claim-btn">
											<?php echo JText::_('LNG_CLAIM_COMPANY')?>
										</div>
									</a>
								</div>
								<div id="claim-login-awarness" class="login-awareness tooltip" style="display:none;" >
								<div class="arrow">ï¿½</div>
								<div class="inner-dialog">
									<a href="javascript:void(0)" class="close-button" onclick="jQuery(this).parent().parent().hide()"><?php echo JText::_('LNG_CLOSE') ?></a>
									<p><strong><?php echo JText::_('LNG_INFO') ?></strong></p>
									<p><?php echo JText::_('LNG_YOU_HAVE_TO_BE_LOGGED_IN') ?></p>
									<p><a href="<?php echo JRoute::_('index.php?option=com_users&view=login&return='.base64_encode($url)) ?>"><?php echo JText::_('LNG_CLICK_LOGIN') ?></a></p>
								</div>
							</div>
							</div>
						</div>
					</div>
				<?php } ?>
			
				<div class="company-style-box">
					<!-- Business Map -->
					<div class="row-fluid">
						<div class="span12">
							<div class="dir-map-image">
								<?php 
									$key="";
									if(!empty($appSettings->google_map_key))
									$key="&key=".$appSettings->google_map_key;
								?>
							
								<?php if((isset($this->package->features) && in_array(GOOGLE_MAP,$this->package->features) || !$appSettings->enable_packages) && !empty($this->company->latitude) && !empty($this->company->longitude)) { 
									echo '<img src="https://maps.googleapis.com/maps/api/staticmap?center='.$this->company->latitude.','.$this->company->longitude.'&zoom=13&size=300x300&markers=color:blue|'.$this->company->latitude.','.$this->company->longitude.$key.'&sensor=false">';
								} ?>
							</div>
						</div>
					</div>

					<!-- Business Address -->
					<div class="row-fluid">
						<div class="span12 dir-address">
							<?php echo JBusinessUtil::getAddressText($this->company); ?>
						</div>
					</div>

					<!-- Business Contact Informations -->
					<div class="row-fluid">
						<div class="span12">
							<div class="company-info-details">
								<?php if(!empty( $this->company->email) && $appSettings->show_email){?>
									<span itemprop="email">
										<i class="dir-icon-envelope"></i> <?php echo $this->company->email?>
									</span>
									<br/>
								<?php } ?>

								<?php if($showData && isset($this->package->features) && in_array(PHONE, $this->package->features) || !$appSettings->enable_packages ) { ?>
									<?php if(!empty($this->company->phone)) { ?>
										<span class="phone" itemprop="tel">
											<i class="dir-icon-phone"></i> <a href="tel:<?php  echo $this->company->phone; ?>"><?php  echo $this->company->phone; ?></a>
										</span><br/>
									<?php } ?>
										
									<?php if(!empty($this->company->mobile)) { ?>
										<span class="phone" itemprop="tel">
											<i class="dir-icon-mobile-phone"></i> <a href="tel:<?php  echo $this->company->mobile; ?>"><?php  echo $this->company->mobile; ?></a>
										</span><br/>
									<?php } ?>
										
									<?php if(!empty($this->company->fax)) {?>
										<span class="phone" itemprop="tel">
											<i class="dir-icon-fax"></i> <?php echo $this->company->fax?>
										</span>
									<?php } ?>
								<?php } ?>
							</div>
						</div>
					</div>

					<!-- Business Website & Business Contact -->
					<div class="row-fluid">
						<div class="span12">
							<div class="company-links">
								<?php if(($showData && isset($this->package->features) && in_array(WEBSITE_ADDRESS, $this->package->features) || !$appSettings->enable_packages) && !empty($company->website)) { ?>
									<i class="dir-icon-globe"></i> 
									<a class="website" title="<?php echo $this->company->name?> Website" target="_blank" onclick="increaseWebsiteClicks(<?php echo $company->id ?>)" href="<?php echo $company->website ?>">
										<?php echo JText::_('LNG_WEBSITE') ?>
									</a>
								<?php }?>
								<?php if((isset($this->package->features) && in_array(CONTACT_FORM,$this->package->features) || !$appSettings->enable_packages) && $showData && !empty($company->email)) { ?>
									<div class="span12">
										<br>
										<a href="javascript:showContactCompany()" class="ui-dir-button ui-dir-button-blue email">
											<span class="ui-button-text"><?php echo JText::_("LNG_CONTACT_COMPANY") ?></span>
										</a>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>

				<!-- Business Contact Person Informations -->
				<?php if( $showData && isset($this->package->features) && in_array(CONTACT_FORM, $this->package->features) || !$appSettings->enable_packages ) { ?>
					<?php if(!empty($company->contact_name)) { ?>
						<div class="company-style-box">
							<div class="row-fluid">
								<div class="span12">
									<h3><i class="fa dir-icon-user"></i> <?php echo JText::_("LNG_CONTACT_PERSON"); ?></h3>
									<div class="company-info-details">
										<div id="contact-person-details" class="contact-person-details">
											<i class="dir-icon-user"></i> <?php echo $this->company->contact_name; ?>
											<br/>
											<?php if(!empty($company->contact_email)) { ?>
												<i class="dir-icon-envelope"></i> <?php echo $this->company->contact_email; ?>
												<br/>
											<?php }?>
	
											<?php if(!empty($company->contact_fax)) {?>
												<i class="dir-icon-fax"></i> <?php echo $company->contact_fax?>
												<br/>
											<?php }?>
	
											<?php if(!empty($company->contact_phone)) { ?>
												<i class="dir-icon-mobile-phone"></i> <a href="tel:<?php  echo $this->company->contact_phone; ?>"><?php  echo $this->company->contact_phone; ?></a>
												<br/>
											<?php }?>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
				<?php } ?>

				<!-- Business Social Networks -->
				<?php if((isset($this->package->features) && in_array(SOCIAL_NETWORKS, $this->package->features) || !$appSettings->enable_packages)
						&& ((!empty($this->company->linkedin) || !empty($this->company->youtube) ||!empty($this->company->facebook) || !empty($this->company->twitter) 
						|| !empty($this->company->googlep) || !empty($this->company->linkedin) || !empty($this->company->skype)|| !empty($this->company->instagram) || !empty($this->company->pinterest)))) { ?> 
					<div class="company-style-box">
						<div class="row-fluid">
							<div class="span12">
								<h3><i class="fa dir-icon-share-alt"></i> <?php echo JText::_("LNG_SOCIAL_NETWORK"); ?></h3>
								<?php require_once 'listing_social_networks.php'; ?>
							</div>
						</div>
					</div>
				<?php } ?>

				<!-- Business Hours -->
				<?php if((isset($this->package->features) && in_array(OPENING_HOURS,$this->package->features) || !$appSettings->enable_packages)
					&& !empty($this->company->business_hours)) { ?>
					<div class="company-style-box">
						<div class="row-fluid">
							<div class="span12">
								<h3><i class="fa dir-icon-clock-o"></i> <?php echo JText::_("LNG_OPENING_HOURS"); ?></h3>
								<?php require_once 'business_hours.php'; ?>
							</div>
						</div>
					</div>
				<?php } ?>
				
				<div class="listing-banners">
					<?php 
						jimport('joomla.application.module.helper');
						// this is where you want to load your module position
						$modules = JModuleHelper::getModules('listing-banners');
						$fullWidth = true;
						?>
						<?php if(isset($modules) && count($modules)>0) { ?>
							<div class="listing-banner">
								<?php 
								$fullWidth = false;
								foreach($modules as $module) {
									echo JModuleHelper::renderModule($module);
								} ?>
							</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>

<form name="tabsForm" action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory') ?>" id="tabsForm" method="post">
 	 <input type="hidden" name="option"	value="<?php echo JBusinessUtil::getComponentName()?>" />
	 <input type="hidden" name="task" value="companies.displayCompany" /> 
	 <input type="hidden" name="tabId" id="tabId" value="<?php echo $this->tabId?>" /> 
	 <input type="hidden" name="view" value="companies" /> 
	 <input type="hidden" name="layout2" id="layout2" value="" /> 
	 <input type="hidden" name="companyId" value="<?php echo $this->company->id?>" />
	 <input type="hidden" name="controller"	value="<?php echo JRequest::getCmd('controller', 'J-BusinessDirectory')?>" />
</form>

<script>
 
	jQuery(document).ready(function() {
		<?php if($appSettings->enable_ratings) { ?> 
			jQuery(".user-rating-avg").raty({
				half:       true,
				precision:  false,
				size:       24,
				starHalf:   'star-half.png',
				starOff:    'star-off.png',
				starOn:     'star-on.png',
				hintList:	  ["<?php echo JText::_('LNG_BAD') ?>","<?php echo JText::_('LNG_POOR') ?>","<?php echo JText::_('LNG_REGULAR') ?>","<?php echo JText::_('LNG_GOOD') ?>","<?php echo JText::_('LNG_GORGEOUS') ?>"],
				noRatedMsg: "<?php echo JText::_('LNG_NOT_RATED_YET') ?>",
				start:	  <?php echo $this->company->averageRating ?>, 	
				path:		  '<?php echo COMPONENT_IMAGE_PATH; ?>',
				click: function(score, evt) {
					<?php 
					$user = JFactory::getUser(); 
					if($appSettings->enable_reviews_users && $user->id ==0) { ?>
						jQuery(this).raty('start',jQuery(this).attr('title'));
						jQuery(this).parent().parent().find(".rating-awareness").show();
					<?php } else { ?>
						updateCompanyRate(jQuery(this).attr('alt'),score);
					<?php } ?>
				}	
			});
		<?php } ?>
	
		<?php if((isset($this->package->features) && in_array(GOOGLE_MAP,$this->package->features) || !$appSettings->enable_packages ) 
				&& !empty($this->company->latitude) && !empty($this->company->longitude)){ ?>
			loadScript();
		<?php }	?>

	
	});
</script>

<?php require_once 'company_util.php'; ?>