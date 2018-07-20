<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

?>
<form action="<?php echo FSSRoute::_("index.php?option=com_fss&view=admin_support&task=update.field"); ?>" method="post" class="form-horizontal form-condensed" id="fssForm">

	<?php echo FSS_Helper::PageStylePopup(true); ?>
	<?php echo FSS_Helper::PageTitlePopup('SUPPORT_ADMIN',"EDIT_TICKET_FIELD"); ?>

		<?php $input = FSSCF::FieldInput($this->field, $this->errors, "ticket", array('ticketid' => FSS_Input::getInt('ticketid'), 'userid' => $this->ticket->user_id, 'ticket' => $this->ticket)); ?>

		<div class="control-group" style="min-height:<?php echo (int)$this->field['min_popup_height']; ?>px;">
			<label class="control-label">
		
				<?php echo FSSCF::FieldHeader($this->field, false, false); ?>
			</label>
			<div class="controls">
				<?php echo $input ?>
			</div>
		</div>

		<input type="hidden" name="fieldid" value="<?php echo FSS_Helper::escape($this->field['id']); ?>">
		<input type="hidden" name="ticketid" value="<?php echo FSS_Helper::escape($this->ticket->id); ?>">

	</div>

	<div class="modal-footer">
		<a class="btn btn-primary" href='#' onclick="jQuery('#fssForm').submit();return false;"><?php echo JText::_("SAVE"); ?></a>
		<a class='btn btn-default' onclick='parent.fss_modal_hide(); return false;'><?php echo JText::_("CANCEL"); ?></a>
	</div>
	
</form>

