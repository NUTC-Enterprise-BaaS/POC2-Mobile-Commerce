<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.6
 */

defined('_JEXEC') or die;

?>

<fieldset id="users-profile-core">
	<legend>
		<?php echo JText::_('COM_USERS_PROFILE_CORE_LEGEND'); ?>
	</legend>
	<div class="profile-edit-tbl">
    <div>
		<div class="prof-usrlb">
			<?php echo JText::_('COM_USERS_PROFILE_NAME_LABEL'); ?>
		</div>
		<div>
			<?php echo $this->data->name; ?>
		</div>
     </div>
     <div>
		<div class="prof-usrlb">
			<?php echo JText::_('COM_USERS_PROFILE_USERNAME_LABEL'); ?>
		</div>
		<div>
			<?php echo htmlspecialchars($this->data->username); ?>
		</div>
     </div>
     <div>
		<div class="prof-usrlb">
			<?php echo JText::_('COM_USERS_PROFILE_REGISTERED_DATE_LABEL'); ?>
		</div>
		<div>
			<?php echo JHtml::_('date', $this->data->registerDate); ?>
		</div>
     </div>
     <div>
		<div class="prof-usrlb">
			<?php echo JText::_('COM_USERS_PROFILE_LAST_VISITED_DATE_LABEL'); ?>
		</div>

		<?php if ($this->data->lastvisitDate != '0000-00-00 00:00:00'){?>
			<div>
				<?php echo JHtml::_('date', $this->data->lastvisitDate); ?>
			</div>
		<?php }
		else {?>
			<div>
				<?php echo JText::_('COM_USERS_PROFILE_NEVER_VISITED'); ?>
			</div>
		<?php } ?>
	</div>
	</div>
</fieldset>
