<?php // no direct access
/**
* @copyright	Copyright (C) 2008-2009 CMSJunkie. All rights reserved.
* 
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
* See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

defined('_JEXEC') or die('Restricted access');

$document = JFactory::getDocument();
$config = new JConfig();
$user = JFactory::getUser(); 

$uri = JURI::getInstance();
$url = $uri->toString( array('scheme', 'host', 'port', 'path'));

$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
require_once JPATH_COMPONENT_SITE.'/classes/attributes/attributeservice.php';

$title = stripslashes($this->offer->subject)." | ".$config->sitename;
$description = !empty($this->offer->short_description)?strip_tags(JBusinessUtil::truncate($this->offer->short_description,100)):$appSettings->meta_description;
$keywords = $appSettings->meta_keywords;

if(!empty($this->offer->meta_title))
	$title = stripslashes($this->offer->meta_title)." | ".$config->sitename;

if(!empty($this->offer->meta_description))
	$description = $this->offer->meta_description;

if(!empty($this->offer->meta_keywords))
	$keywords = $this->offer->meta_keywords;

$document->setTitle($title);
$document->setDescription($description);
$document->setMetaData('keywords', $keywords);

if(!empty($this->offer->pictures)){
	$document->addCustomTag('<meta property="og:image" content="'.JURI::root().PICTURES_PATH.$this->offer->pictures[0]->picture_path .'" /> ');
}
$document->addCustomTag('<meta property="og:type" content="website"/>');
$document->addCustomTag('<meta property="og:url" content="'.$url.'"/>');
$document->addCustomTag('<meta property="og:site_name" content="'.$config->sitename.'"/>');
?>

<?php require_once JPATH_COMPONENT_SITE."/include/fixlinks.php" ?>
<?php require_once JPATH_COMPONENT_SITE."/include/social_share.php" ?>

<div><a href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=offers'); ?>"><?php echo JText::_("BACK") ?></a></div>
<div id="offer-container" class="offer-container row-fluid">
	<div class="row-fluid">
		<?php if(!empty($this->offer->pictures)){?>
			<div id="offer-image-container" class="offer-image-container span6">
				<?php 
					$this->pictures = $this->offer->pictures;
					require_once JPATH_COMPONENT_SITE.'/include/image_gallery.php'; 
				?>
			</div>
		<?php }?>
		<div id="offer-content" class="offer-content span6">
			<div class="dir-print">
				<a href="javascript:printOffer(<?php echo $this->offer->id ?>)"><?php echo JText::_("LNG_PRINT")?></a>
				<?php if($appSettings->enable_offer_coupons) { ?>
					<?php if($this->offer->checkOffer) { ?>
						<br>
						<?php if($user->id !=0) { ?>
							<a class="btn btn-primary btn-xs" href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&task=offer.generateCoupon&id='.$this->offer->id) ?>" target="_blank">
								<?php echo JText::_("LNG_GENERATE_COUPON")?>
							</a>
						<?php } else { ?>
							<a class="btn btn-primary btn-xs" href="javascript:getCoupon()">
								<?php echo JText::_('LNG_GENERATE_COUPON')?>
							</a>
						<?php } ?>
					<?php } ?>
				<?php } ?>
			</div>

			<?php if($user->id ==0) { ?>
				<div style="display:none" class="login-awareness tooltip" id="claim-login-awarness">
					<div class="arrow">»</div>
					<div class="inner-dialog">
						<a href="javascript:void(0)" class="close-button" onclick="jQuery(this).parent().parent().hide()"><?php echo JText::_('LNG_CLOSE') ?></a>
						<p>
							<strong><?php echo JText::_('LNG_INFO') ?></strong>
						</p>
						<p>
							<?php echo JText::_('LNG_YOU_HAVE_TO_BE_LOGGED_IN') ?>
						</p>
						<p>
							<a href="<?php echo JRoute::_('index.php?option=com_users&view=login&return='.base64_encode($url)); ?>"><?php echo JText::_('LNG_CLICK_LOGIN') ?></a>
						</p>
					</div>
				</div>
			<?php } ?>

			<h1>
				<?php echo $this->offer->subject?>
			</h1>
			<div class="offer-details">
				<table>
					<?php if(!empty($this->offer->price)){?>
						<tr>
							<th><?php echo JText::_('LNG_PRICE') ?>:</th>
							<td class="price-old"><?php echo JBusinessUtil::getPriceFormat($this->offer->price, $this->offer->currencyId) ?></td>
						</tr>
					<?php } ?>
					
					<?php if(!empty($this->offer->specialPrice)){?>
						<tr>
							<th><?php echo JText::_('LNG_SPECIAL_PRICE') ?>:</th>
							<td><?php echo JBusinessUtil::getPriceFormat($this->offer->specialPrice, $this->offer->currencyId)?></td>
						</tr>
					<?php } ?>
				</table>
				
				<div class="offer-location">
					<span itemprop="address"><i class="dir-icon-map-marker dir-icon-large"></i> <?php echo JBusinessUtil::getLocationText($this->offer)?></span>
				</div>
				
				<?php if((!empty($this->offer->startDate) && $this->offer->startDate!="0000-00-00") || (!empty($this->offer->endDate) && $this->offer->endDate!="0000-00-00")){?>
					<div class="offer-dates">
						<i class="dir-icon-calendar"></i>
						<?php 
							echo  JBusinessUtil::getDateGeneralFormat($this->offer->startDate)." - ". JBusinessUtil::getDateGeneralFormat($this->offer->endDate);
						?>
					</div>
				<?php } ?>
				
				<?php if(!empty($this->offer->show_time) && JBusinessUtil::getRemainingtime($this->offer->endDate)!=""){?>
					<div class="offer-dates">
						<span ><i class="dir-icon-clock-o dir-icon-large"></i> <?php echo JBusinessUtil::getRemainingtime($this->offer->endDate)?></span>
					</div>
				<?php } ?>
				
				<?php if(!empty($this->offer->categories)){?>
					<div class="offer-categories">
						<div><strong><?php echo JText::_("LNG_CATEGORIES")?></strong></div>
						<?php 
							$categories = explode('#',$this->offer->categories);
							foreach($categories as $i=>$category){
								$category = explode("|", $category);
								?>
									 <a rel="nofollow" href="<?php echo JBusinessUtil::getOfferCategoryLink($category[0], $category[2]) ?>"><?php echo $category[1]?></a><?php echo $i<(count($categories)-1)? ',&nbsp;':'' ?>
								<?php 
							}
						?>
					</div>
				<?php }?>
					
				<?php if(!empty($this->offer->attachments)){?>
				<div class="offer-attachments">
					<div><strong><?php echo JText::_("LNG_FILES")?></strong></div>
					<div> 
						<?php foreach($this->offer->attachments as $attachment){?>	
								<a target="_blank" href="<?php echo JURI::root()."/".ATTACHMENT_PATH.$attachment->path?>"><?php echo !empty($attachment->name)?$attachment->name:basename($attachment->path)?></a> </li>
						<?php }?>
					</div>
				</div>
				<?php } ?>
			</div>
			<div class="company-details">
				<table>
					<tr>
						<td><strong><?php echo JText::_('LNG_COMPANY_DETAILS') ?></strong></td>
					</tr>
					<tr>
						<td><a href="<?php echo JBusinessUtil::getCompanyLink($this->offer->company)?>"> <?php echo $this->offer->company->name?></a></td>
					</tr>
					<tr>
						<td><i class="dir-icon-map-marker dir-icon-large"></i> <?php echo JBusinessUtil::getAddressText($this->offer->company)?></td>
					</tr>
					<?php if(!empty($this->offer->company->phone)){?>
						<tr>
							<td><i class="dir-icon-phone dir-icon-large"></i> <a href="tel:<?php  echo $this->offer->company->phone; ?>"><?php  echo $this->offer->company->phone; ?></a></td>
						</tr>
					<?php } ?>
					<?php if(!empty($this->offer->company->website)){?>
						<tr>
							<td><a target="_blank" href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=companies&task=companies.showCompanyWebsite&companyId='.$this->offer->company->id) ?>"><i class="dir-icon-link "></i> <?php echo JText::_('LNG_WEBSITE')?></a></td>
						</tr>
					<?php } ?>
				</table>
				
			</div>
		</div>
	</div>
	<div>
		<div class="classification">
			<?php
				$renderedContent = AttributeService::renderAttributesFront($this->offerAttributes,false, array());
				echo $renderedContent;
			?>
		</div>
		<div class="offer-description">
			<?php echo $this->offer->description?>
		</div>
	</div>
	<div class="clear"></div>
</div>

<div id="offer-dialog" class="offer" style="display:none">
	<div id="dialog-container">
		<div class="titleBar">
			<span class="dialogTitle" id="dialogTitle"></span>
			<span  title="Cancel"  class="dialogCloseButton" onClick="jQuery.unblockUI();">
				<span title="Cancel" class="closeText">x</span>
			</span>
		</div>
		<div class="dialogContent">
			<iframe id="offerIfr" height="500" src="about:blank">
			</iframe>
		</div>
	</div>
</div>

<script>
	// starting the script on page load
	jQuery(document).ready(function() {
		jQuery("img.image-prv").click(function(e) {
			jQuery("#image-preview").attr('src', this.src);	
		});
	});		

	function printOffer(offerId) {
		var winref = window.open('<?php echo JRoute::_("index.php?option=com_jbusinessdirectory&view=offer&tmpl=component"); ?>&offerId='+offerId,'windowName','width=1050,height=700');
		if (window.print) winref.print();
	}

	function getCoupon() {
		<?php if($user->id==0) {	?>
			jQuery("#claim-login-awarness").show();
		<?php } else { ?>
			jQuery(".error_msg").each(function(){
				jQuery(this).hide();
			});
			showCouponLoginDialog();
		<?php } ?>
	}

	function showCouponLoginDialog() {
		jQuery.blockUI({ message: jQuery('#offer-coupon'), css: {width: 'auto', top: '5%', left:"0", position:"absolute", cursor:'default'} });
		jQuery('.blockUI.blockMsg').center();
		jQuery('.blockOverlay').attr('title','Click to get a deal').click(jQuery.unblockUI);
		jQuery(document).scrollTop( jQuery("#offer-coupon").offset().top );
		jQuery("html, body").animate({ scrollTop: 0}, "slow");
	}
</script>