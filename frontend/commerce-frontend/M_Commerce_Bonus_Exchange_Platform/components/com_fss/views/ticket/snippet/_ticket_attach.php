
<?php echo FSS_Helper::PageSubTitle("ATTACHEMNTS"); ?>

<?php FSS_Helper::HelpText("support_user_view_attach_header"); ?>

<?php foreach ($this->ticket->attach as $attach) : ?>
	<?php if ($attach->inline) continue; ?>

	<?php 
	$file_user_class = "warning";
	if (array_key_exists($attach->message_id, FSS_Helper::$message_labels))
	$file_user_class = FSS_Helper::$message_labels[$attach->message_id];
	?>
				
	
	<?php $image = in_array(strtolower(pathinfo($attach->filename, PATHINFO_EXTENSION)), array('jpg','jpeg','png','gif')); ?>

	<div class="media padding-mini">
		
	<?php if ($image): ?>
		<div class="pull-left large-dl-image">
			<a class="show_modal_image" href="<?php echo FSSRoute::_('index.php?option=com_fss&view=ticket&task=attach.view&fileid=' . $attach->id . "&ticketid=" . $this->ticket->id); ?>">
				<img class="media-object" src="<?php echo FSSRoute::_('index.php?option=com_fss&view=ticket&task=attach.thumbnail&fileid=' . $attach->id . "&ticketid=" . $this->ticket->id); ?>" width="48" height="48">
			</a>
		</div>
	<?php else: ?>
		<div class="pull-left large-dl-icon">
			<a href="<?php echo FSSRoute::_('index.php?option=com_fss&view=ticket&task=attach.download&fileid=' . $attach->id . "&ticketid=" . $this->ticket->id); ?>">
				<i class="icon-download"></i>
			</a>
		</div>
	<?php endif; ?>		
			
		<div class="media-body">

			<div class="pull-right" style="text-align: right;">
				<?php echo FSS_Helper::display_filesize($attach->size); ?><br />
				<?php echo FSS_Helper::Date($attach->added, FSS_DATETIME_MID); ?>
			</div>

			<h4 class="media-heading"><a href='<?php echo FSSRoute::_('index.php?option=com_fss&view=ticket&task=attach.download&fileid=' . $attach->id . "&ticketid=" . $this->ticket->id); ?>'><?php echo $attach->filename; ?></a></h4>
			<?php echo JText::_('UPLOADED_BY'); ?> 
			<span class="label label-<?php echo $file_user_class; ?>">
				<?php echo $attach->name; ?>
			</span>
			
		</div>
	</div>

<?php endforeach; ?>

<?php FSS_Helper::HelpText("support_user_view_attach_footer"); ?>
