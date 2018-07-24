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
<?php echo FSS_Helper::PageTitle('SUPPORT_ADMIN',"EMAILS_AWAITING_APPROVAL"); ?>

	<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_tabbar.php'); ?>

	<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_supportbar.php'); ?>

	<form method="post" action="<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=emails'); ?>" name='fssForm' id='fssForm'>

		<div class="form-horizontal form-condensed">
			<div class="control-group">
				<label class="control-label"><?php echo JText::_('EMAIL_IMPORTS'); ?></label>
				<div class="controls">
					<div class="input-append">
						<select name="state" class="select-color" onchange="jQuery('#fssForm').submit();">
							<option value="" class="text-success" <?php if ($this->state == "") echo "Selected"; ?>><?php echo JText::_('AWAITING_APPROVAL'); ?></option>
							<option value="declined" class="text-error" <?php if ($this->state == "declined") echo "Selected"; ?>><?php echo JText::_('DECLINED'); ?></option>
						</select>
					</div>
					
					<a href="#" class="btn btn-default pull-right" onclick="refresh();return false;"><?php echo JText::_('REFRESH'); ?></a>
				</div>
			</div>
		</div>
	
		<?php if (count($this->pending) > 0): ?>
			<div style="overflow-x: auto">
				<table class='table table-bordered table-ticketborders table-condensed table-striped'>
					<tr>
						<th>
							<input type="checkbox" class='ticket_check_all'>
							<?php echo JText::_('SUBJECT'); ?>
						</th>
						<th>
							<?php echo JText::_('FROM'); ?>
						</th>
						<th>
							<?php echo JText::_('REVIEVED'); ?>
						</th>
						<th>
							<?php echo JText::_('ACTIONS'); ?>
						</th>
					</tr>
		
					<?php foreach ($this->pending as $ticket): ?>
						<tr class="first" id="ticket_<?php echo $ticket->id; ?>">
							<td style="vertical-align:middle;word-break: break-all;">
								<input type="checkbox" name="ticket_check_<?php echo $ticket->id; ?>" class='ticket_check' value='<?php echo $ticket->id; ?>'>
								<a class="show_modal_iframe" data_modal_width="760" href="<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=emails&tmpl=component&preview=' . $ticket->id); ?>">
									<?php echo $ticket->getTitle(); ?>
								</a>
							</td>
					
							<td style="vertical-align:middle;word-break: break-all;" nowrap>
								<?php echo $ticket->getUserName(); ?>
								<br />
								<span class="small">
									<?php echo $ticket->getUserEMail(); ?>
								</span>
							</td>
					
							<td style="vertical-align:middle;text-align:center;word-break: break-all;" nowrap>
								<?php echo FSS_Helper::Date($ticket->opened, FSS_DATE_SHORT); ?><br />
								<span class="small"><?php echo FSS_Helper::Date($ticket->opened, FSS_TIME_SHORT); ?></span>
							</td>

							<td style="vertical-align:middle;text-align:center;" nowrap class="action_buttons">
								<?php if ($this->state == ""): ?>
									<a href="#" class="btn btn-success accept_button" onclick="email_accept(<?php echo $ticket->id; ?>); return false; "><?php echo JText::_('ACCEPT'); ?></a>
									<a href="#" class="btn btn-danger decline_button" onclick="email_decline(<?php echo $ticket->id; ?>); return false; "><?php echo JText::_('DECLINE'); ?></a>
									<a href="#" class="btn btn-danger delete_button" style="display: none;" onclick="email_delete(<?php echo $ticket->id; ?>); return false; "><?php echo JText::_('DELETE'); ?></a>
									<a href="<?php echo FSSRoute::_("index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=" . $ticket->id,false); ?>" class="btn btn-info view_button" style="display: none;"><?php echo JText::_('VIEW'); ?></a>
								<?php else: ?>
									<a href="#" class="btn btn-success" onclick="email_accept(<?php echo $ticket->id; ?>); return false; "><?php echo JText::_('ACCEPT'); ?></a>
									<a href="#" class="btn btn-danger" onclick="email_delete(<?php echo $ticket->id; ?>); return false; "><?php echo JText::_('DELETE'); ?></a>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</table>
			</div>
			<div class="pull-right">
				<b><?php echo JText::_('CHECKED_TICKETS_'); ?></b> 
				<a href="#" class="btn btn-success accept_button" onclick="email_accept_checked(); return false; "><?php echo JText::_('ACCEPT'); ?></a>
				<a href="#" class="btn btn-danger decline_button" onclick="email_decline_checked(); return false; "><?php echo JText::_('DECLINE'); ?></a>
			</div>
	
			<?php echo $this->pagination->getListFooter(); ?>
		<?php else: ?>
			<?php if ($this->state == ""): ?>
				<div class="alert"><?php echo JText::_('THERE_ARE_NO_TICKETS_AWAITING_APPROVAL'); ?></div>
			<?php else: ?>
				<div class="alert"><?php echo JText::_('THERE_ARE_NO_DECLINED_TICKETS'); ?></div>
			<?php endif; ?>
		<?php endif; ?>
	</form>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>

<script>

function email_accept(ticketid)
{
	jQuery('#ticket_' + ticketid + ' .action_buttons a').hide();
	jQuery('#ticket_' + ticketid + ' .action_buttons').append("<span class='saving'>Saving...</span>");

	jQuery('#ticket_' + ticketid).removeClass('error');
	jQuery('#ticket_' + ticketid).addClass('success');
	
	var url = '<?php echo JRoute::_( 'index.php?option=com_fss&view=admin_support&task=emails.approve', false); ?>' + '&ticketid=' + ticketid;
	
	jQuery.get(url, function () {
		jQuery('#ticket_' + ticketid + ' .action_buttons span.saving').remove();
		jQuery('#ticket_' + ticketid + ' .action_buttons a.decline_button').show();
		jQuery('#ticket_' + ticketid + ' .action_buttons a.view_button').show();
	});
}

function email_decline(ticketid)
{
	jQuery('#ticket_' + ticketid + ' .action_buttons a').hide();
	jQuery('#ticket_' + ticketid + ' .action_buttons').append("<span class='saving'>Saving...</span>");
	
	jQuery('#ticket_' + ticketid).removeClass('success');
	jQuery('#ticket_' + ticketid).addClass('error');
	
	var url = '<?php echo JRoute::_( 'index.php?option=com_fss&view=admin_support&task=emails.decline', false); ?>' + '&ticketid=' + ticketid;
	
	jQuery.get(url, function () {
		jQuery('#ticket_' + ticketid + ' .action_buttons span.saving').remove();
		jQuery('#ticket_' + ticketid + ' .action_buttons a.accept_button').show();
		jQuery('#ticket_' + ticketid + ' .action_buttons a.delete_button').show();
	});
}

function email_delete(ticketid)
{
	jQuery('#ticket_' + ticketid + ' .action_buttons a').hide();
	jQuery('#ticket_' + ticketid + ' .action_buttons').append("<span class='saving'>Saving...</span>");
	
	jQuery('#ticket_' + ticketid).removeClass('error');
	jQuery('#ticket_' + ticketid).removeClass('success');
	jQuery('#ticket_' + ticketid).addClass('info');
	
	var url = '<?php echo JRoute::_( 'index.php?option=com_fss&view=admin_support&task=emails.delete', false); ?>' + '&ticketid=' + ticketid;
	
	jQuery.get(url, function () {
		jQuery('#ticket_' + ticketid + ' .action_buttons').html("Deleted");
	});
}

function refresh()
{
	jQuery('input[name="limitstart"]').val(0);
	jQuery('#fssForm').submit();
}

jQuery(document).ready(function () {
	jQuery('.ticket_check_all').change(function () {
		if (jQuery('.ticket_check_all').is(":checked"))
		{
			jQuery('.ticket_check').attr('checked', 'checked');
		} else {
			jQuery('.ticket_check').removeAttr('checked');
		}
	});
});

function email_accept_checked()
{
	jQuery('.ticket_check:checked').each( function () {
		var ticketid = jQuery(this).attr('value');
		if (jQuery('#ticket_' + ticketid + ' .action_buttons a.accept_button').is(":visible"))
			email_accept(ticketid);
	});
}

function email_decline_checked()
{
	jQuery('.ticket_check:checked').each( function () {
		var ticketid = jQuery(this).attr('value');
		if (jQuery('#ticket_' + ticketid + ' .action_buttons a.decline_button').is(":visible"))
			email_decline(ticketid);
	});
}
</script>