<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

?>
<?php foreach ($this->ticket->attach as $attach) : ?>
	<?php if ($attach->inline) continue; ?>
	<?php $image = in_array(strtolower(pathinfo($attach->filename, PATHINFO_EXTENSION)), array('jpg','jpeg','png','gif')); ?>
			
	<?php 
	$file_user_class = "warning";
	if (array_key_exists($attach->message_id, FSS_Helper::$message_labels))
		$file_user_class = FSS_Helper::$message_labels[$attach->message_id];
	if ($attach->hidefromuser)
		$file_user_class = "info";
	?>

	<div class="media padding-mini">

		<?php if ($this->print && empty($this->replying)): ?>
			
			<!-- Print version of attachment, no image or links! -->
			<?php if ($image): ?>
				<div class="pull-left">
					<img class="media-object" src="<?php echo JRoute::_('index.php?option=com_fss&view=admin_support&task=attach.thumbnail&ticketid=' . $this->ticket->id . '&fileid=' . $attach->id); ?>">
				</div>
			<?php else: ?>
				<div class="pull-left large-dl-icon">
					<i class="icon-download"></i>
				</div>
			<?php endif; ?>		
				
			<div class="media-body">

				<div class="pull-right" style="text-align: right;">
					<?php echo FSS_Helper::display_filesize($attach->size); ?><br />
					<?php echo FSS_Helper::Date($attach->added, FSS_DATETIME_MID); ?><br />			
				</div>
		
				<h4 class="media-heading">
				<?php echo $attach->filename; ?>
				</h4>
				<?php echo JText::_('UPLOADED_BY'); ?>
				<span class="label label-<?php echo $file_user_class; ?>">
					<?php echo $attach->name; ?>
				</span>
			</div>
			
		<?php else: ?>
			<?php if ($image): ?>
				<div class="pull-left">
					<a class="show_modal_image" href="<?php echo JRoute::_('index.php?option=com_fss&view=admin_support&task=attach.view&ticketid=' . $this->ticket->id . '&fileid=' . $attach->id); ?>">
						<img class="media-object" src="<?php echo JRoute::_('index.php?option=com_fss&view=admin_support&task=attach.thumbnail&ticketid=' . $this->ticket->id . '&fileid=' . $attach->id); ?>" width="48" height="48">
					</a>
				</div>
			<?php else: ?>
				<div class="pull-left large-dl-icon">
					<a class="" href="<?php echo JRoute::_('index.php?option=com_fss&view=admin_support&task=attach.download&ticketid=' . $this->ticket->id . '&fileid=' . $attach->id); ?>">
						<i class="icon-download"></i>
					</a>
				</div>
			<?php endif; ?>			
				<div class="media-body">

				<?php if ($this->can_ChangeTicket() && $this->can_EditTicket()): ?>
					<div class="pull-right" style="margin-left: 6px;margin-right: 6px;">
						<a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&task=attach.delete&ticketid=' . $this->ticket->id . '&fileid=' . $attach->id ); ?>' title='<?php echo JText::_('DELETE_ATTACHMENT'); ?>' class='fssTip'>
							<i class="icon-delete"></i>
						</a>
					</div>
				<?php endif; ?>
					
				<div class="pull-right" style="text-align: right;">
					<?php echo FSS_Helper::display_filesize($attach->size); ?><br />
					<?php echo FSS_Helper::Date($attach->added, FSS_DATETIME_MID); ?><br />
						
				</div>
		
				<h4 class="media-heading"><a href='<?php echo JRoute::_( 'index.php?option=com_fss&view=admin_support&task=attach.download&ticketid=' . $this->ticket->id . '&fileid=' . $attach->id ); ?>'><?php echo $attach->filename; ?></a></h4>
				<?php echo JText::_('UPLOADED_BY'); ?>
				<span class="label label-<?php echo $file_user_class; ?>">
					<?php echo $attach->name; ?>
				</span>
			</div>
		<?php endif; ?>
	</div>
		
<?php endforeach; ?>
