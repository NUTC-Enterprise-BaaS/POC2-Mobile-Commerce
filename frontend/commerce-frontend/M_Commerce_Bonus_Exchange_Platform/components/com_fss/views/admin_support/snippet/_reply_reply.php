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
		<label class="control-label"><?php echo JText::_("NEW_STATUS"); ?></label>
		<div class="controls">
			<select name="reply_status" class="select-color">
				<?php
				FSS_Ticket_Helper::GetStatusList();
				$def_admin = FSS_Ticket_Helper::GetStatusID('def_admin');
				if ($def_admin < 1)
					$def_admin = $this->ticket->ticket_status_id;
				FSS_Translate_Helper::Tr(FSS_Ticket_Helper::$status_list);
				foreach (FSS_Ticket_Helper::$status_list as $status)
				{
					if ($status->def_archive) continue;
					if (!$this->can_Close() && $status->is_closed) continue;
					$sel = $status->id == $def_admin ? "SELECTED" : "";
					echo "<option value='{$status->id}' style='color:{$status->color};' {$sel}>{$status->title}</option>";	
				}
				?>
			</select>
		</div>
	</div>
	
	<?php if ($this->support_assign_reply || 
	(FSS_Settings::get('support_autoassign') == 3 && $this->ticket->admin_id == 0)) : ?>
		<div class="control-group">
			<label class="control-label"><?php echo JText::_("ASSIGN_TICKET"); ?></label>
			<div class="controls">
				<input type=checkbox value='1' id='dontassign' name="dontassign"><?php echo JText::_("DONT_ASSIGN_THIS_SUPPORT_TICKET_TO_ME"); ?>
			</div>
		</div>
	<?php endif; ?>

	<div class="control-group">
		<label class="control-label"><?php echo JText::_("MESSAGE"); ?></label>
		<div class="controls">
			<?php echo SupportCanned::CannedDropdown("body", true, $this->ticket); ?>
		</div>
	</div>

	<p>
		<textarea style='width:95%;height:<?php echo (int)((FSS_Settings::get('support_admin_reply_height') * 15) + 80); ?>px' name='body' id='body' class="sceditor" rows='<?php echo (int)FSS_Settings::get('support_admin_reply_height'); ?>' cols='<?php echo (int)FSS_Settings::get('support_admin_reply_width'); ?>'><?php echo htmlspecialchars($this->user_message); ?></textarea>
	</p>