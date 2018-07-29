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
<?php echo FSS_Helper::PageTitle("FREQUENTLY_ASKED_QUESTIONS",$this->curcattitle); ?>

<?php $acl = 1; ?>
<form action="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=faqs' );?>" method="get" name="fssForm" class="faq_search">
	<input type='hidden' name='option' value='com_fss' />
	<input type='hidden' name='Itemid' value='<?php echo FSS_Input::getInt('Itemid'); ?>' />
	<input type='hidden' name='view' value='faq' />
	<input type='hidden' name='catid' value='<?php echo (int)$this->curcatid; ?>' />
	<input type='hidden' name='enable_pages' value='<?php echo (int)$this->enable_pages; ?>' />
	<input type='hidden' name='view_mode' value='<?php echo FSS_Helper::escape($this->view_mode); ?>' />

<?php if ($this->showcats) : ?>
	
	<?php  
	?>
		
	<div id="faq_categories">
		
		<?php if (!$this->hide_search) : ?>	
			<div>	
				<div class="input-append">
					<input type="text" id='faqsearch' name='search' class="input-medium" placeholder="<?php echo JText::_("SEARCH_FAQS"); ?>" value="<?php echo FSS_Helper::escape($this->search); ?>">
					<input id='faq_search_submit' class='btn btn-primary' type='submit' value='<?php echo JText::_("SEARCH"); ?>' />
				</div>
			</div>
	    <?php endif; ?>

		<?php 
			if (FSS_Settings::get('faq_multi_col_responsive'))
			{
				$mc = new FSS_Multi_Col_Responsive();
			} else {
				$mc = new FSS_Multi_Col();
			}
			$mc->Init($this->num_cat_colums);
		?>


		<?php if (!$this->hide_allfaqs) : ?>
			<?php $mc->Item(); ?>
			<div class="media faq_cat faq_cat_all">
				<a class="pull-left" href="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=faq&catid=' . -6);?>"
				 <?php if ($this->view_mode_cat == "accordian"): ?> onclick="return false;" <?php endif; ?>
				 >
					<img class="media-object" src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/allfaqs.png' width='64' height='64'>
				</a>

				<div class="media-body">
					<div style="min-height: 64px">

						<h4 class="media-heading">
							<a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=faq&catid=' . -6);?>'><?php echo JText::_("ALL_FAQS"); ?></a>
						</h4>
			
						<?php echo JText::_("VIEW_ALL_FREQUENTLY_ASKED_QUESTIONS"); ?>
			
					</div>
				</div>
			</div>	
			
        <?php endif; ?>

		<?php if (!$this->hide_tags) : ?>
			<?php $mc->Item(); ?>
			<div class="media faq_cat faq_cat_tags">
				<a class="pull-left" href="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=faq&catid=' . -4); ?>"
				<?php if ($this->view_mode_cat == "accordian"): ?> data-toggle="collapse" data-target="#cat_content_tags" data-parent="#faq_categories" onclick="return false;"<?php endif; ?>
				>
					<img class="media-object" src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/tags-64x64.png' width='64' height='64'>
				</a>
		
				<div class="media-body">
					<div style="min-height: 64px">
						<div
							<?php if ($this->view_mode_cat == "accordian"): ?>
								style="cursor: pointer" data-toggle="collapse" data-target="#cat_content_tags" data-parent="#faq_categories"
							<?php endif; ?>
							>
							<?php 
							    $tagextra = "";
								if ($this->view_mode_cat == "accordian")
								{
									$tagextra = ' onclick="return false;" ';
								}
							?>						

							<h4 class="media-heading">
								<a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=faq&catid=' . -4); ?>' <?php echo $tagextra; ?>><?php echo JText::_("TAGS"); ?></a>
							</h4>
			
							<?php echo JText::_("VIEW_FAQ_TAGS"); ?>
						</div>
					</div>
					
					<?php if ($this->view_mode_cat == "inline" || $this->view_mode_cat == "accordian") : ?>
					<div id="cat_content_tags" class="<?php if ($this->view_mode_cat == "accordian"): ?>collapse<?php endif; ?>">
						<p style="font-size: 100%; line-height: 26px;">
							<?php foreach ($this->all_tags as $tag): ?>
								<a class="label label-large" href="<?php echo FSSRoute::_('index.php?option=com_fss&view=faq&tag=' . urlencode($tag->tag)); ?>"><?php echo $tag->tag; ?></a>&nbsp;
							<?php endforeach; ?>
						</p>
					</div>
					<?php endif; ?>
				</div>
			</div>	
	
	        <?php endif; ?>
			
		<?php 
			if ($this->show_featured)
			{
				// set up fake $cat object and include the _cat.php template
				$cat = array();
				$cat['image'] = '/components/com_fss/assets/images/featured.png';
				$cat['id'] = -5;
				$cat['title'] = JText::_('FEATURED_FAQS');
				$cat['description'] = JText::_('VIEW_FEATURED_FREQUENTLY_ASKED_QUESTIONS');
				$cat['faqs'] = array();
				if (!empty($this->featured_faqs))
					$cat['faqs'] = $this->featured_faqs;
				
				$mc->Item();
				
				include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'faq'.DS.'snippet'.DS.'_cat.php');
			}
		 ?>
		
		<?php foreach ($this->catlist as $cat) : ?>
			<?php $mc->Item(); ?>
			<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'faq'.DS.'snippet'.DS.'_cat.php'); ?>
		<?php endforeach; ?>
		<?php $mc->End(); ?>
	</div>
<?php endif; ?>

<?php if ($this->showfaqs) : ?>

	<?php if ($this->curcatid != -1): ?>
		<div class="media">
			<?php if ($this->curcatimage) : ?>
			<a class="pull-left" href="#" onclick="return false;">
				<?php if (substr($this->curcatimage,0,1) == "/") : ?>
					<img class="media-object" src='<?php echo JURI::root( true ); ?><?php echo $this->curcatimage; ?>' width='64' height='64'>
				<?php else: ?>
					<img class="media-object" src='<?php echo JURI::root( true ); ?>/images/fss/faqcats/<?php echo $this->curcatimage; ?>' width='64' height='64'>
				<?php endif; ?>
			</a>
			<?php endif; ?>
				
			<div class="media-body">
				<div <?php if ($this->curcatimage) : ?>style="min-height: 64px"<?php endif;?>>
						
					<h4 class="media-heading">
						<?php if (FSS_Settings::Get('faq_cat_prefix') || !$this->curcattitle): ?>
							<?php echo JText::_("FAQS"); ?>
							<?php if ($this->curcattitle) echo " - "; ?> 
						<?php endif; ?>
						<?php echo $this->curcattitle; ?>
					</h4>
			
					<?php echo $this->curcatdesc; ?>
			
				</div>
			</div>
		</div>	

	<?php endif; ?>

	<?php if ($this->curcatid == -1): ?>
		<?php // Search box when in search mode ?>
			<div class="input-append">
				<input type="text" id='faqsearch' name='search' class="input-medium" placeholder="<?php echo JText::_("SEARCH_FAQS"); ?>" value="<?php echo FSS_Helper::escape($this->search); ?>">
				<input id='prod_submit' class='btn btn-primary' type='submit' value='<?php echo JText::_("SEARCH"); ?>' />
			</div>
	<?php endif; ?>
	
	<?php if (count($this->items)) foreach ($this->items as $faq) : ?>
		<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'faq'.DS.'snippet'.DS.'_faq.php'); ?>
	<?php endforeach; ?>
	<?php if (count($this->items) == 0): ?>
		<p class="fss_no_results"><?php echo JText::_("NO_FAQS_MATCH_YOUR_SEARCH_CRITERIA");?></p>
		

		<?php if ($this->notEnoughArticles()): ?>
			<div class="alert alert-danger">
			<h4>Not enough FAQs to search</h4>
	<p>You do not have enough FAQs to allow searching. Please ensure that you have 4 or more FAQs. Alternatly enable the "<b>Search: Support non latin text</b>" setting to allow searching when less than 4 FAQs.</p>
			</div>
		<?php endif; ?>			
	<?php endif; ?>

	<?php if ($this->enable_pages): ?>
			<?php echo $this->pagination->getListFooter(); ?>
	<?php endif; ?>

<?php endif; ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php if (FSS_Settings::get( 'glossary_faqs' )) echo FSS_Glossary::Footer(); ?>

<?php echo FSS_Helper::PageStyleEnd(); ?>

<script>
	<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'assets'.DS.'js'.DS.'content_edit.js'; ?>
</script>

</form>
