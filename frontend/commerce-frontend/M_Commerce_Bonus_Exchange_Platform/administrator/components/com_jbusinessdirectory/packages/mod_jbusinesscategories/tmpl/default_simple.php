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
?>

<div class="main-categories-simple" id="main-categories-simple">
	<?php if(!empty($categories)) { ?>
		<?php foreach($categories as $category) {
			if(!is_array($category) || $category[0]->published==0)
				continue; 
		?>
		<a href="<?php echo JBusinessUtil::getCategoryLink($category[0]->id, $category[0]->alias) ?>">
			<span class="category-icon">
				<?php if(!empty($category[0]->icon)){ ?>
						<i class="dir-icon-custom dir-icon-<?php echo $category[0]->icon ?>"></i>
				<?php } ?>
			</span> 
			<span class="category-name"><?php echo $category[0]->name; ?></span>
		</a>
		<?php } ?> 
	<?php } ?> 
	<div style="position: relative;">
		<span class="cta-text"><?php echo JText::_("LNG_BROWSE_HIGHLIGHTS") ?></span>
	</div>
</div>
