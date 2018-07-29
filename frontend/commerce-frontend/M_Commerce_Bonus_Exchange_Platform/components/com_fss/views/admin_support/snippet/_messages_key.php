<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>

<div class="pull-right messages_key">

	<div class="fss_support_msg_audit" style="display:none;">
		<table class="table table-bordered table-ticketborders table-condensed">
			<tr class="fss_support_msg_audit ticket_message_audit success" style="display: none;">
				<td><?php echo JText::_('AUDIT_LOG'); ?></td>
			</tr>
		</table>
	</div>

	<div>
		<table class="table table-bordered table-ticketborders table-condensed">
			<tr class="warning ticket_message_header ticket_message_2">
				<td><?php echo JText::_('MESSAGE_KEY_PRIVATE'); ?></td>
			</tr>
		</table>
	</div>

	<div>
		<table class="table table-bordered table-ticketborders table-condensed">
			<tr class="info ticket_message_draft">
				<td><?php echo JText::_('DRAFT_REPLY'); ?></td>
			</tr>
		</table>
	</div>

</div>

<p class="messages_key">
	<span class="label label-warning"><?php echo JText::_('MESSAGE_KEY_A_USER'); ?></span>
	<span class="label label-success"><?php echo JText::_('MESSAGE_KEY_HANDLER'); ?></span>
	<span class="label label-info"><?php echo JText::_('MESSAGE_KEY_PRIVATE'); ?></span>
</p>
