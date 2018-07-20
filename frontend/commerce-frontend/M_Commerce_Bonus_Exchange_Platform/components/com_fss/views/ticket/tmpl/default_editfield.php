<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php echo FSS_Helper::PageStylePopup(); ?>

<?php echo FSS_Helper::PageTitlePopup("SUPPORT","EDIT_TICKET"); ?>

<form action="<?php echo FSSRoute::_("&editfield=tmpl=what=savefield");// FIX LINK ?>" method="post">
<table class="fss_table" cellpadding="0" cellspacing="0">
	<tr>
		<th style="text-align: center;"><?php echo FSSCF::FieldHeader($this->field, false, false); ?></th>
	</tr>
	<tr>
		<td><?php echo FSSCF::FieldInput($this->field, $this->errors, "ticket", array('ticketid' => FSS_Input::getInt('ticketid'), 'userid' => $this->ticket['user_id'], 'ticket' => $this->ticket)); ?></td>
	</tr>
</table>
<br />
<input type="hidden" name="what" value="savefield">
<input type="hidden" name="savefield" value="<?php echo (int)$this->field['id']; ?>">
<input class='button' type="submit" value="<?php echo JText::_("SAVE"); ?>" name="store">&nbsp;
<input class='button' type="submit" name="store" value="<?php echo JText::_("CANCEL"); ?>" onclick="parent.SqueezeBox.close();return false;">
</form>

<?php echo FSS_Helper::PageStylePopupEnd(); ?>

