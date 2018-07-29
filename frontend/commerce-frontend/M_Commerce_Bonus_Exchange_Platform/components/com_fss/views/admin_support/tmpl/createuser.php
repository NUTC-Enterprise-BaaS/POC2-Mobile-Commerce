<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
	<?php echo FSS_Helper::PageStylePopup(true); ?>
	<?php echo FSS_Helper::PageTitlePopup("CREATE_NEW_USER"); ?>

	<form action="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&layout=createuser&tmpl=component', false );?>" method="post" name="fssForm" id="fssForm" class="form-horizontal form-condensed">
	
			<input type="hidden" name="create" value="1" />
			<input type="hidden" name="ticketid" value="<?php echo JRequest::getVar('ticketid'); ?>" />
			
			<div class="control-group <?php if ($this->errors['name']) echo "error"; ?>">
				<label class="control-label"><?php echo JText::_('COM_USERS_PROFILE_NAME_LABEL'); ?></label>
				<div class="controls">
					<input type="text" name="name" value="<?php echo FSS_Helper::escape($this->data->name); ?>" required />
				</div>
			</div>
		
			<div class="control-group <?php if ($this->errors['username']) echo "error"; ?>">
				<label class="control-label"><?php echo JText::_('COM_USERS_PROFILE_USERNAME_LABEL'); ?></label>
				<div class="controls">
					<input type="text" name="username" value="<?php echo FSS_Helper::escape($this->data->username); ?>" required  />
				</div>
			</div>
		
			<div class="control-group <?php if ($this->errors['password']) echo "error"; ?>">
				<label class="control-label"><?php echo JText::_('COM_USERS_PROFILE_PASSWORD1_LABEL'); ?></label>
				<div class="controls">
					<input type="text" name="password" id="password" value="<?php echo FSS_Helper::escape($this->data->password); ?>" required  />
					<a class="btn btn-default" onclick="randomPassword();return false;" href="#"><?php echo JText::_("RANDOM_PASSWORD"); ?></a>
				</div>
			</div>
		
			<div class="control-group <?php if ($this->errors['password2']) echo "error"; ?>">
				<label class="control-label"><?php echo JText::_('COM_USERS_PROFILE_PASSWORD2_LABEL'); ?></label>
				<div class="controls">
					<input type="text" name="password2" id="password2" value="<?php echo FSS_Helper::escape($this->data->password2); ?>" required  />
				</div>
			</div>
		
			<div class="control-group <?php if ($this->errors['email']) echo "error"; ?>">
				<label class="control-label"><?php echo JText::_('COM_USERS_REGISTER_EMAIL1_LABEL'); ?></label>
				<div class="controls">
					<input type="text" name="email" value="<?php echo FSS_Helper::escape($this->data->email); ?>" required  />
				</div>
			</div>
		
	
	</form>
</div>

<div class="modal-footer">
	<a href="#" class='btn btn-success' onclick='jQuery("#fssForm").submit(); return false;'><?php echo JText::_("CREATE_NEW_USER"); ?></a>
	<a href='#' class="btn btn-default" onclick='parent.fss_modal_hide(); return false;'><?php echo JText::_('CANCEL'); ?></a>
</div>

<script>
function randomPassword()
{
    var length = 8,
        charset = "abcdefghijklnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
        retVal = "";
    for (var i = 0, n = charset.length; i < length; ++i) {
        retVal += charset.charAt(Math.floor(Math.random() * n));
    }
	
	jQuery('#password').val(retVal);
	jQuery('#password2').val(retVal);
}
</script>