<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die; ?>

<?php echo FSS_Helper::PageStyle(); ?>
<?php echo FSS_Helper::PageTitle("KNOWLEDGE_BASE","SEARCH_RESULTS"); ?>

<script>
function ChangePage(newpage)
{
	var limitstart = document.getElementById('limitstart');
	if (!newpage)
		newpage = 0;
	limitstart.value = newpage;
	
	document.forms.searchProd.submit();
}

function ChangePageCount(newcount)
{
	var limit = document.getElementById('limit');
	if (!newcount)
		newcount = 10;
	limit.value = newcount;
		
	var limitstart = document.getElementById('limitstart');
	limitstart.value = 0;
	
	document.forms.searchProd.submit();
}


</script>

<?php $product = $this->product; ?>
<?php $cat = $this->cat; ?>

<div class="fss_kb_search">
		<form id="searchProd" action="<?php echo FSSRoute::_( '&view=kb' );// FIX LINK?>" method="post" name="fssForm">
		<?php if ($product['id'] > 0): ?>
			<input type="hidden" name='prodid' value='<?php echo (int)$product['id']; ?>'>
		<?php endif; ?>
		<?php if ($cat['id'] > 0): ?>
			<input type="hidden" name='catid' value='<?php echo (int)$cat['id']; ?>'>
		<?php endif; ?>
		<input type="hidden" name="limitstart" id='limitstart' value="0">
		<input type="hidden" name="limit" id='limit' value="<?php echo (int)$this->limit; ?>">
		
		<?php if ($product && $cat): ?>
			<?php $title = JText::sprintf('SEARCH_KNOWLEDGE_BASE_ARTICLES_FOR_IN',$product['title'],$cat['title']); ?> 
		<?php elseif ($product) : ?>
			<?php $title = JText::sprintf('SEARCH_KNOWLEDGE_BASE_ARTICLES_FOR',$product['title']); ?> 
		<?php elseif ($cat) : ?>
			<?php $title = JText::sprintf('SEARCH_KNOWLEDGE_BASE_ARTICLES_IN',$cat['title']); ?> 
		<?php else: ?>
			<?php $title = JText::_("SEARCH_KNOWLEDGE_BASE_ARTICLES"); ?>
		<?php endif; ?>
		
		<div class="input-append">	
			<input id='kb_search' type='text' name='kbsearch' value="<?php echo FSS_Helper::escape($this->search); ?>" placeholder='<?php echo $title; ?>'>
			<input id='kb_submit' class='btn btn-primary' type='submit' value='<?php echo JText::_("SEARCH"); ?>' />
			<input id='art_reset' class='btn btn-default' type='submit' value='<?php echo JText::_("RESET"); ?>' />
		</div>		

		</form>

</div>
<div class='kb_category_artlist'>
<?php foreach ($this->results as $art): ?>
	<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_art.php'); ?>
<?php endforeach; ?>
<?php if (count($this->results) == 0): ?>

	<p class="fss_no_results"><?php echo JText::_("NO_ARTICLES_MATCH_YOUR_SEARCH_CRITERIA"); ?></p>

	<?php if ($this->notEnoughArticles()): ?>
		<div class="alert alert-danger">
		<h4>Not enough articles to search</h4>
	<p>You do not have enough KB articles to allow searching. Please ensure that you have 4 or more articles.  Alternatly enable the "<b>Search: Support non latin text</b>" setting to allow searching when less than 4 articles.</p>
		</div>
	<?php endif; ?>	

<?php endif; ?>
</div>

<?php if ($this->cat_art_pages) echo $this->pagination->getListFooter(); ?>


<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>


<script>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'assets'.DS.'js'.DS.'content_edit.js'; ?>

jQuery(document).ready(function () {
	jQuery('#art_reset').click(function () {
		jQuery('#kb_search').val("");
	});
});
</script>
