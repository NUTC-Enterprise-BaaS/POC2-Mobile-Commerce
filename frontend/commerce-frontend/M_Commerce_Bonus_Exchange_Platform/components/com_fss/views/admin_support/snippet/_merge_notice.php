<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<div class="alert alert-info">

	<?php if ($this->merge == "related"): ?>
		<h4><?php echo JText::_('ADD_RELATED_TICKET'); ?></h4>
	<?php else: ?>
		<h4><?php echo JText::_('MERGE_TICKETS'); ?></h4>
	<?php endif; ?>
	<ul style="margin-top: 8px;">
		<li>
			<?php echo $this->merge_ticket->reference; ?> - <?php echo $this->merge_ticket->title; ?>
		</li>	
	</ul>
	<?php if ($this->merge == "into"): ?>
		<p><?php echo JText::_('MERGE_INFO_INTO'); ?></p>
	<?php elseif ($this->merge == "from"): ?>
		<p><?php echo JText::_('MERGE_INFO_FROM'); ?></p>
	<?php elseif ($this->merge == "related"): ?>
		<p><?php echo JText::_('MERGE_INFO_RELATED'); ?></p>
	<?php endif; ?>
	
	<div style="text-align: right;">
		<?php if (!empty($this->ticket) && $this->ticket->id != JFactory::getSession()->Get('merge_ticket_id')): ?>
			<?php 
				if ($this->merge == "into")
				{
					$link = FSSRoute::_('index.php?option=com_fss&view=admin_support&task=merge.merge&source_id=' . JFactory::getSession()->Get('merge_ticket_id') . '&dest_id=' . $this->ticket->id, false);
				} elseif ($this->merge == "related")
				{
					$link = FSSRoute::_('index.php?option=com_fss&view=admin_support&task=merge.related&source_id=' . JFactory::getSession()->Get('merge_ticket_id') . '&dest_id=' . $this->ticket->id, false);
				} else {
					$link = FSSRoute::_('index.php?option=com_fss&view=admin_support&task=merge.merge&source_id=' . $this->ticket->id . '&dest_id=' . JFactory::getSession()->Get('merge_ticket_id'), false);
				}
			?>
			<a href="<?php echo $link; ?>" class="btn btn-success">
				<?php if ($this->merge == "related"): ?>
					<?php echo JText::_("ADD_RELATED_TICKET"); ?>
				<?php else: ?>
					<?php echo JText::_('TICKET_MERGE'); ?>
				<?php endif; ?>
			</a>
		<?php endif; ?>
		<a href="<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=list&merge=cancel', false); ?>" class="btn btn-default"><?php echo JText::_("CANCEL"); ?></a>
	</div>
	
</div>