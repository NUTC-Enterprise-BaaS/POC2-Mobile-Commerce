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
			<label class="control-label"><?php echo JText::_("NEW_HANDLER"); ?></label>
			<div class="controls">
				<select name="new_handler" id="new_handler">
					<option value="-1" <?php if (FSS_Settings::get('forward_handler_handler') == 'unchanged') echo "selected"; ?>>
						<?php echo JText::_("Unchanged") ?>
					</option>		
					<option value="-2" <?php if (FSS_Settings::get('forward_handler_handler') == 'auto') echo "selected"; ?>>
						<?php echo JText::_("AUTO_ASSIGN") ?>
					</option>		
					<option value="0" <?php if (FSS_Settings::get('forward_handler_handler') == 'unassigned') echo "selected"; ?>>
						<?php echo JText::_("UNASSIGNED") ?>
					</option>		
					<?php $handlerid = FSS_Input::getInt('handler',''); ?>
					<optgroup label="Handlers">
						<?php foreach ($this->handlers as $handler) :?>
							<option value="<?php echo $handler->id ?>"><?php echo $handler->name ?></option>
						<?php endforeach; ?>
					</optgroup>
				</select>			
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"><?php echo JText::_("NEW_STATUS"); ?></label>
			<div class="controls">
				<select name="reply_status" class="select-color">
					<?php
					FSS_Ticket_Helper::GetStatusList();
					FSS_Translate_Helper::Tr(FSS_Ticket_Helper::$status_list);
					foreach (FSS_Ticket_Helper::$status_list as $status)
					{
						if ($status->def_archive) continue;
						if (!$this->can_Close() && $status->is_closed) continue;
						$sel = $status->id == $this->ticket->ticket_status_id ? "SELECTED" : "";
						echo "<option value='{$status->id}' style='color:{$status->color};' {$sel}>{$status->title}</option>";	
					}
					?>
				</select>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"><?php echo JText::_("MESSAGE_TO_HANDLER"); ?></label>
			<div class="controls">
				<?php echo SupportCanned::CannedDropdown("body2", true, $this->ticket); ?>
			</div>
		</div>

		<p>
			<textarea style='width:95%;height:<?php echo (FSS_Settings::get('support_admin_reply_height') * 15) + 80; ?>px' name='body2' id='body2' class="sceditor" rows='<?php echo (int)FSS_Settings::get('support_admin_reply_height'); ?>' cols='<?php echo (int)FSS_Settings::get('support_admin_reply_width'); ?>'></textarea>
		</p>

		<div class="control-group">
			<label class="control-label"><?php echo JText::_("MESSAGE_TO_USER"); ?></label>
			<div class="controls">
				<?php echo SupportCanned::CannedDropdown("body", true, $this->ticket); ?>
			</div>
		</div>

		<p>
			<textarea style='width:95%;height:<?php echo (FSS_Settings::get('support_admin_reply_height') * 15) + 80; ?>px' name='body' id='body' class="sceditor" rows='<?php echo (int)FSS_Settings::get('support_admin_reply_height'); ?>' cols='<?php echo (int)FSS_Settings::get('support_admin_reply_width'); ?>'></textarea>
		</p>