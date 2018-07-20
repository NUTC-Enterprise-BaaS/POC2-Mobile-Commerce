<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

$cid = 1;
?>
		
	<?php 
		$has_cats = false;

		foreach ($this->depts as $dept)
			if ($dept->category != "") $has_cats = true;

		if (JRequest::getVar('prodsearch') != "" && JRequest::getVar('prodsearch') != "__all__") $has_cats = false;
	?>

	<?php if (!$has_cats): ?>
		<ul class="unstyled departments">
			<?php foreach ($this->depts as $dept): ?>
				<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_dept.php'); ?>
			<?php endforeach; ?>
		</ul>
	
	<?php else: ?>

		<?php $category = "----------"; $subcat = "---------"; ?>
		<?php $in_cat = false; $in_subcat = false; ?>

		<?php $col = 0; ?>

		<?php foreach ($this->depts as $dept): ?>

			<?php if ($dept->category != $category): ?>
				<?php if ($in_subcat) echo "</ul></div></div>"; ?>
				<?php if ($in_cat) echo "</div></div>";?>

				<?php if ($dept->category == ""): ?>
					<div class="department_no_category"><div>
				<?php else: ?>
					<div class="department_category_container">
					<h4 class="department_category_header">
						<?php echo FSS_Settings::get('support_open_cat_prefix'); ?>
						<?php if (FSS_Settings::get('support_open_accord')): ?>
							<a data-toggle="collapse" href="#prodcat<?php echo $cid; ?>"><?php echo $dept->category; ?></a>
						<?php else : ?>
							<?php echo $dept->category; ?>
						<?php endif; ?>
					</h4>
					<div class="department_category_indent <?php if (FSS_Settings::get('support_open_accord')) echo 'collapse' ?>" id="prodcat<?php echo $cid++; ?>">
				<?php endif; ?>

				<?php $subcat = "--------"; $in_subcat = false; ?>
				<?php $category = $dept->category; $in_cat = true; ?>
			<?php endif; ?>

			<?php if ($dept->subcat != $subcat): ?>
				<?php if ($in_subcat) echo "</ul></div></div>";?>

				<?php if ($dept->subcat == ""): ?>
					<div class="department_no_subcat"><div>
				<?php else: ?>
					<div class="department_subcat_container">
					<h5 class="department_subcat_header">
						<?php echo FSS_Settings::get('support_open_cat_prefix'); ?>
						<?php if (FSS_Settings::get('support_open_accord')): ?>
							<a data-toggle="collapse" href="#prodcat<?php echo $cid; ?>"><?php echo $dept->subcat; ?></a>
						<?php else : ?>
							<?php echo $dept->subcat; ?>
						<?php endif; ?>
					</h5>
					<div class="department_subcat_indent <?php if (FSS_Settings::get('support_open_accord')) echo 'collapse' ?>" id="prodcat<?php echo $cid++; ?>">
				<?php endif; ?>

					<ul class="unstyled departments">
				<?php $subcat = ""; ?>
				<?php $subcat = $dept->subcat; $in_subcat = true; ?>
			<?php endif; ?>

			<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_dept.php'); ?>
			<?php $col = 1 - $col; ?>
			
		<?php endforeach; ?>

		<?php if ($in_subcat) echo "</ul></div></div>";?>
		<?php if ($in_cat) echo "</div></div>";?>
	<?php endif; ?>
		
	<div class="clearfix"></div>
		
	<?php if (count($this->depts) == 0): ?>
	<div class="alert alert-info"><?php echo JText::_("NO_PRODUCTS_MATCH_YOUR_SEARCH_CRITERIA"); ?></div>
	<?php endif; ?>
	
	<?php if (FSS_Settings::Get('ticket_prod_per_page') > 0) echo $this->pagination->getListFooter(); ?>
