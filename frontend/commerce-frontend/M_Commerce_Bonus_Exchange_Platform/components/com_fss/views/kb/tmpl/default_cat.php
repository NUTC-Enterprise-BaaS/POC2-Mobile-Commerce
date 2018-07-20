<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php echo FSS_Helper::PageStyle(); ?>
<?php echo FSS_Helper::PageTitle("KNOWLEDGE_BASE",$this->pagetitle); ?>
<div class="fss_spacer"></div>

<?php if ($this->cat_desc): ?>
	<?php echo $this->cat['description']; ?>
<?php endif; ?>

<?php $product = $this->product; ?>
<?php $cat = $this->cat; ?>
<?php if ($product['id'] > 0): ?>
<?php //include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_prod.php'); ?>	
<?php endif; ?>
<?php //include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_cat.php'); ?>	
<form id="searchProd" action="<?php echo FSSRoute::_( $this->base_url ); ?>" method="post" name="fssForm">

<?php if ($this->cat_search) : ?>
		<input type="hidden" name='prodid' value='<?php echo (int)$product['id']; ?>'>
		<input type="hidden" name='catid' value='<?php echo (int)$cat['id']; ?>'>
		<div class="input-append">
			<input id='kb_search' type="text" name='kbsearch' value="<?php echo FSS_Helper::escape($this->search); ?>" placeholder="<?php echo JText::_('SEARCH_KNOWLEDGE_BASE_ARTICLES'); ?>">
			<input id='kb_submit' class='btn btn-primary' type='submit' value='<?php echo JText::_("SEARCH"); ?>' />
			<input id='art_reset' class='btn btn-default' type='submit' value='<?php echo JText::_("RESET"); ?>' />
		</div>

<?php endif; ?>

<?php if (FSS_Settings::get('kb_view_top')) include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_views.php'); ?>

<div class="fss_subcat_cont">
	<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_cat_list.php');?>
</div>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_art_list.php'); ?>	

<?php if ($this->cat_art_pages) echo $this->pagination->getListFooter(); ?>

<?php if (!FSS_Settings::get('kb_view_top'))  include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_views.php'); ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>


<script>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'assets'.DS.'js'.DS.'content_edit.js'; ?>
</script>
	</form>
