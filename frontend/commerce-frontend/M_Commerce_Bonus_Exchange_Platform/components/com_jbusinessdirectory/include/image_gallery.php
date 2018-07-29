<?php /*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined('_JEXEC') or die('Restricted access');
?>

<div class='picture-container'>
	<ul id="imageGallery" class="gallery list-unstyled">
		<?php if (!empty($this->pictures)) { ?>	
			<?php foreach($this->pictures as $picture) { ?>
				<li data-thumb="<?php echo JURI::root().PICTURES_PATH.$picture->picture_path ?>">
					<img src="<?php echo JURI::root().PICTURES_PATH.$picture->picture_path ?>" alt="<?php echo isset($picture->picture_info)?$picture->picture_info:""?>"/>
				</li>
			<?php } ?>
		<?php } else { ?>
			<?php echo JText::_("LNG_NO_IMAGES"); ?>
		<?php } ?>
	</ul>
	<div style="clear:both;"></div>
</div>
			
<?php if (!empty($this->pictures)){?>	
	<script type="text/javascript">
		 var slider = null;
		jQuery(document).ready(function($) {
			 slider = jQuery('#imageGallery').lightSlider({
				gallery: true,
				item: 1,
				currentPagerPosition: 'left',
				mode: 'fade',
				thumbItem: 5,
				slideMargin: 0,
				enableDrag: true,
				adaptiveHeight: <?php if ($this->appSettings->adaptive_height_gallery) echo 'true'; else echo 'false'; ?>,
				speed: 500,
				pause: 3500,
				auto: <?php if ($this->appSettings->autoplay_gallery) echo 'true'; else echo 'false'; ?>,
				loop: true,
				responsive : [
				{
					breakpoint: 800,
					settings: {
						thumbItem: 4
					}
				},
				{
					breakpoint: 480,
					settings: {
						thumbItem: 3
					}
				}
				]
			});
		});
	</script>
<?php } ?>
	