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

$itemsPerRow=6;
?>
<script>
	jQuery(document).ready(function(){
		jQuery("li.main-cat").mouseover(function() {
			jQuery(this).addClass('over');
		}).mouseout(function() {
			jQuery(this).removeClass('over');
		});

		jQuery("ul.subcategories").mouseover(function() {
			jQuery(this).parent().addClass('over');
		}).mouseout(function() {
			jQuery(this).parent().removeClass('over');
		});

		jQuery("#category-holder").height( jQuery("#categories-menu-container").height());

		jQuery(".subcategories").each(function(){
			var offset =jQuery( ".main-categories" ).width()-1;
			jQuery(this).css({left: offset});
		});

		jQuery('.metismenu').metisMenu();
	});
</script>

<div class="categories-menu<?php echo $moduleclass_sfx ?>" id="category-holder">
	<ul id="categories-menu-container" class="metismenu main-categories">
		<?php 
		foreach($categories as $category) {
			if(!is_array($category) || $category[0]->published==0)
				continue; ?>
			<li>
				<?php if(isset($category["subCategories"]) && count($category["subCategories"]) > 0) { 
					$nrCategories = count($category["subCategories"]); ?>
					<a aria-expanded="true" href="#">
						<?php if(!empty($category[0]->icon)) { ?>
							<span class="dir-icon-<?php echo $category[0]->icon ?>"></span> 
						<?php } ?>
						<span onclick="goToLink('<?php echo JBusinessUtil::getOfferCategoryLink($category[0]->id, $category[0]->alias) ?>')"> <?php echo $category[0]->name; ?></span> 
						<span class="dir-icon-menu-arrow"></span>
					</a>
					<ul aria-expanded="false" class="collapse">
						<?php 
						$index = 0;
						$rowIndex = 0;
						if($nrCategories>0) {
							foreach($category["subCategories"] as $subcategory) {
								$index++;
								if($index % 10 == 0) {
									$rowIndex ++;
									$index = 1;
									echo '</ul><ul aria-expanded="true" class="collapse in" style="">';
								} ?>
								<li>
									<a href="<?php echo JBusinessUtil::getOfferCategoryLink($subcategory[0]->id, $subcategory[0]->alias) ?>">
										<?php echo $subcategory[0]->name ?>
									</a>
								</li>
							<?php } ?>
						<?php } ?>
					</ul>
				<?php } else { ?>
					<a href="<?php echo JBusinessUtil::getOfferCategoryLink($category[0]->id, $category[0]->alias) ?>">
					<?php if(!empty($category[0]->icon)) { ?>
						<span class="dir-icon-<?php echo $category[0]->icon ?>"></span> 
					<?php } ?>
					<?php echo $category[0]->name ?></a>
				<?php } ?>
			</li>
		<?php } ?>
	</ul>
</div>

<script>
function goToLink(link){
	document.location.href=link;
}
</script>