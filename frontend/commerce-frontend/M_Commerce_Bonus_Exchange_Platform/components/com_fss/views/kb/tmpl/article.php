<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>

<?php if (FSS_Input::getCmd('tmpl') == "component" && !FSS_Input::getString('print')): ?>
	<?php echo FSS_Helper::PageStylePopup(true); ?>
	
	<?php echo FSS_Helper::PageTitlePopup($this->art['title']); ?>
	
<?php else: ?>
	
	<?php echo FSS_Helper::PageStyle(); ?>

	<?php if (!FSS_Input::getString('print')) : ?>
		<?php echo $this->content->EditPanel($this->art); ?>
	<?php endif; ?>

	<?php echo FSS_Helper::PageTitle("KNOWLEDGE_BASE",$this->art['title']); ?>
	<div class="fss_spacer"></div>
<?php endif; ?>

<div>

	<div class="pull-right">

		<?php if (!FSS_Input::getString('print')) : ?>
			<?php if ($this->kb_rate || FSS_Settings::Get('kb_print')): ?>
				<div style="margin-bottom: 12px;">
					<?php if ($this->kb_rate) : ?>
						<div style="position:relative; display:inline-block" id="fss_kb_rate">
							<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#">
								<i class='icon-star'></i> 
								<?php echo JText::_("Rate"); ?>
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								<li>
									<a id='rate_up' href='#'>
										<img src='<?php echo JURI::base(); ?>/components/com_fss/assets/images/rate_up.png'>
										<?php echo JText::_("VERY_HELPFULL"); ?>
									</a>
								</li>
								<li>
									<a id='rate_same' href='#'>
										<img src='<?php echo JURI::base(); ?>/components/com_fss/assets/images/rate_same.png'>
										<?php echo JText::_("COULD_BE_BETTER"); ?>
									</a>
								</li>
								<li>
									<a id='rate_down' href='#'>
										<img src='<?php echo JURI::base(); ?>/components/com_fss/assets/images/rate_down.png'>
										<?php echo JText::_("NOT_HELPFULL"); ?>
									</a>
								</li>
							</ul>
						</div>
					<?php endif; ?>
			
					<?php if (FSS_Settings::Get('kb_print')): ?>
						<a class="btn btn-default" href="<?php echo JRoute::_('index.php?option=com_fss&view=kb&kbartid=' . $this->art['id'] . "&tmpl=component&print=1") ?>" target="_blank">
							<i class='icon-print'></i>
							<?php echo JText::_("PRINT"); ?>
						</a>
		
					<?php endif; ?>	
				</div>
			<?php endif; ?>

		<?php endif; ?>

		<?php if ($this->toc) echo $this->toc; ?>

		<?php if (FSS_Settings::get('kb_contents_auto')) : ?>
			<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_contents.php'); ?>
		<?php endif; ?>

	</div>
		<?php //echo FSS_Helper::PageSubTitle($this->art['title']); ?>
	
	<?php if (
			(FSS_Settings::get('kb_show_art_products') == 2 && count($this->products) > 1)
			||
			(FSS_Settings::get('kb_show_dates') == 2 && ($this->art['created'] != "0000-00-00 00:00:00" || $this->art['modified'] != "0000-00-00 00:00:00"))
			||
			(FSS_Settings::get('kb_show_art_related') == 2 && count($this->related) > 0)
		): ?>
	
		<div class="article-info muted">
			<dl class="article-info">

				<?php if (FSS_Settings::get('kb_show_art_products') == 2 && count($this->products) > 1): ?>
					<?php $applies = array(); ?>
					<?php foreach ($this->applies as $app) { $applies[] = $app['title']; } ?>
					<?php if (count($applies) > 0): ?>
						<dd class="published">
								<span class="icon-filter"></span> <?php echo JText::_("APPLIES_TO"); ?>: <?php echo implode(", ",$applies); ?>			
						</dd>
					<?php endif; ?>
				<?php endif; ?>

				<?php if (FSS_Settings::get('kb_show_art_related') == 2 && count($this->related) > 0): ?>
					<?php 
						$output = array(); 
						foreach ($this->related as $relart)
							$output[] = "<a href='". FSSRoute::_('&kbartid=' . $relart['id']) ."'>". $relart['title']."</a>";// FIX LINK
					?>
						<dd class="published">
							<span class="icon-arrow-right"></span> <?php echo JText::_("RELATED_ARTICLES"); ?>: <?php echo implode(", ",$output); ?>			
						</dd>
				<?php endif; ?>


				<?php if (FSS_Settings::get('kb_show_dates') == 2 && $this->art['created'] != "0000-00-00 00:00:00"): ?>
							<dd class="create">
								<span class="icon-calendar"></span> <?php echo JText::_("CREATED"); ?>: <?php echo FSS_Helper::Date($this->art['created']); ?>				
							</dd>	
				<?php endif; ?>

				<?php if (FSS_Settings::get('kb_show_dates') == 2 && $this->art['modified'] != "0000-00-00 00:00:00"): ?>
							<dd class="create">
								<span class="icon-calendar"></span> <?php echo JText::_("MODIFIED"); ?>: <?php echo FSS_Helper::Date($this->art['modified']); ?>				
							</dd>	
				<?php endif; ?>

			</dl>
		</div>
	
	<?php endif; ?>	
	
	<?php echo $this->pages_header; ?>
	
	<div id="kb_art_body">
		<?php 
		if (FSS_Settings::get( 'glossary_kb' )) {
			echo FSS_Glossary::ReplaceGlossary($this->art['body']); 
		} else {
			echo $this->art['body']; 
		}		
		?>
	</div>
	
	<?php echo $this->pages_footer; ?>
</div>

<script>
<?php if ($this->kb_rate): ?>
	jQuery(document).ready( function () {
	jQuery('#rate_up').click( function (ev) {
		ev.preventDefault();
		jQuery.get('<?php echo JRoute::_( 'index.php?option=com_fss&view=kb', false); ?>' + '?&rate=up&kbartid=<?php echo (int)$this->art['id']; ?>');
		jQuery('#fss_kb_rate').html("");
		jQuery('<div/>', {class: 'fss_kb_rate_head well well-mini', text: '<?php echo JText::_("THANK_YOU_FOR_YOUR_FEEDBACK"); ?>'}).appendTo('#fss_kb_rate');
});	

	jQuery('#rate_same').click( function (ev) {
		ev.preventDefault();
		jQuery.get('<?php echo JRoute::_( 'index.php?option=com_fss&view=kb' , false); ?>' + '?&rate=same&kbartid=<?php echo (int)$this->art['id']; ?>');
		jQuery('#fss_kb_rate').html("");
		jQuery('<div/>', {class: 'fss_kb_rate_head well well-mini', text: '<?php echo JText::_("THANK_YOU_FOR_YOUR_FEEDBACK"); ?>'}).appendTo('#fss_kb_rate');
	});

	jQuery('#rate_down').click( function (ev) {
		ev.preventDefault();
		jQuery.get('<?php echo JRoute::_( 'index.php?option=com_fss&view=kb', false); ?>' + '?&rate=down&kbartid=<?php echo (int)$this->art['id']; ?>');
		jQuery('#fss_kb_rate').html("");
		jQuery('<div/>', {class: 'fss_kb_rate_head well well-mini', text: '<?php echo JText::_("THANK_YOU_FOR_YOUR_FEEDBACK"); ?>'}).appendTo('#fss_kb_rate');
	});
});
<?php endif; ?>
</script>

<?php if (FSS_Settings::get('kb_show_art_products') == 1): ?>
<?php $applies = array(); ?>
<?php if (count($this->products) > 1) :?>
	<?php foreach ($this->applies as $app) { $applies[] = $app['title']; } ?>
	<?php if (count($applies) > 0) { ?>
		<?php echo FSS_Helper::PageSubTitle2("APPLIES_TO"); ?>
		<p><?php echo implode(", ",$applies); ?></p> 
	<?php } ?>
<?php endif; ?>
<?php endif; ?>

<?php if (FSS_Settings::get('kb_show_art_related') == 1 && count($this->related) > 0): ?>
	<?php echo FSS_Helper::PageSubTitle2("RELATED_ARTICLES"); ?>
	
	<?php foreach ($this->related as $relart) : ?>
	<p>
			<a href='<?php echo FSSRoute::_('&kbartid=' . $relart['id']);// FIX LINK ?>'><?php echo $relart['title']; ?></a>
		</p>
	<?php endforeach; ?>
<?php endif; ?>

<?php if (FSS_Settings::get('kb_show_dates') == 1 && ($this->art['created'] != "0000-00-00 00:00:00" || $this->art['modified'] != "0000-00-00 00:00:00")) : ?>
	<?php echo FSS_Helper::PageSubTitle2("DETAILS"); ?>

	<p>
		<?php 
			$dates = array();
			if ($this->art['created'] != "0000-00-00 00:00:00") $dates[] = JText::_("CREATED"). " : " . $this->art['created'];
			if ($this->art['modified'] != "0000-00-00 00:00:00") $dates[] = JText::_("MODIFIED"). " : " . $this->art['modified'];
		?>
		<?php echo implode(", ",$dates); ?>
	</p> 
<?php endif; ?>

<?php if (count($this->artattach) > 0 && FSS_Settings::get('kb_show_art_attach')) :?>
	<?php echo FSS_Helper::PageSubTitle2("ATTACHED_FILES"); ?>

	<?php foreach ($this->artattach as $file) : ?>
		<?php $filelink = FSSRoute::_( '&fileid=' . $file['id'] );// FIX LINK ?>
	
		<div class="media highlight padding-mini">
			<div class="pull-left">
				<a class="show_modal_image" href="<?php echo $filelink; ?>">
					<img class="media-object" src="<?php echo JURI::base(); ?>/components/com_fss/assets/images/diskbig.png" width="48" height="48">
				</a>
			</div>

			<div class="media-body">				
				<div class="pull-right" style="text-align: right;">
					<?php if (FSS_Settings::get('kb_show_art_attach_filenames')): ?>
						<code><?php echo $file['filename']; ?></code><br>		
					<?php endif; ?>
					<?php echo round($file['size'] / 1000,0); ?> Kb<br>
				</div>
		
				<h4 class="media-heading">
					<a href="<?php echo $filelink; ?>">
						<?php echo $file['title']; ?>
					</a>
				</h4>
				<p><?php echo $file['description']; ?></p>
			</div>
		</div>
	
	<?php endforeach; ?>

<?php endif; ?>

<?php if (!FSS_Input::getString('print')) : ?>
	
	<?php 
	if (FSS_Settings::get('kb_comments') == 1)
	{
		$this->comments->DisplayComments();
	} else if (FSS_Settings::get('kb_comments') == 2)
	{
		$comments = JPATH_SITE . '/components/com_jcomments/jcomments.php';
		if (file_exists($comments)) {
			require_once($comments);
			echo JComments::showComments($this->art['id'], 'com_fss_kb', $this->art['title']);
		}
	}
	?>
	
<?php endif; ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>

<?php if (FSS_Settings::get( 'glossary_kb' )) echo FSS_Glossary::Footer(); ?>

<?php if (FSS_Input::getCmd('tmpl') == "component" && !FSS_Input::getString('print')): ?>
	<?php echo FSS_Helper::PageStylePopupEnd(); ?>
<?php else: ?>
	<?php echo FSS_Helper::PageStyleEnd(); ?>
<?php endif; ?>

<script>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'assets'.DS.'js'.DS.'content_edit.js'; ?>
</script>

<?php if (FSS_Input::getString('print')) : ?>
<script>
jQuery(document).ready( function () {
	window.print();
});
</script>
<?php endif; ?>

