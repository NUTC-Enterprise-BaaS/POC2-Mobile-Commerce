<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<div class="control-group">
	<label class="control-label"><?php echo JText::_("NEW_USER"); ?></label>
	<div class="controls">
		<a class="btn btn-default show_modal_iframe" data_modal_width="800" href="<?php echo FSSRoute::_("index.php?option=com_fss&view=admin_support&layout=users&tmpl=component"); ?>"><?php echo JText::_("CHANGE"); ?></a>
		<span class="help-inline" id="username_display"><?php echo $this->user->name; ?> (<?php echo $this->user->username; ?>)</span>&nbsp;&nbsp;&nbsp;&nbsp;
	</div>
</div>
<input name="user_id" id="user_id" type="hidden" value="<?php echo (int)$this->ticket->user_id; ?>">
		
<div class="control-group">
	<label class="control-label"><?php echo JText::_("MESSAGE_TO_USER"); ?></label>
	<div class="controls">
		<?php echo SupportCanned::CannedDropdown("body", true, $this->ticket); ?>
	</div>
</div>

<p>
	<textarea style='width:95%;height:<?php echo (int)((FSS_Settings::get('support_admin_reply_height') * 15) + 80); ?>px' name='body' id='body' class="sceditor" rows='<?php echo (int)FSS_Settings::get('support_admin_reply_height'); ?>' cols='<?php echo (int)FSS_Settings::get('support_admin_reply_width'); ?>'></textarea>
</p>

<script>
function PickUser(userid, username, name)
{
	fss_modal_hide();
	jQuery('#user_id').val(userid);
	jQuery('#username_display').html(name + " (" + username + ")");
}
</script>