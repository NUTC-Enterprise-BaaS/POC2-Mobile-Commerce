<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
		<?php $products = SupportHelper::getProducts(); ?>
		<?php if (count($products) > 0): ?>
			<div class="control-group">
				<label class="control-label"><?php echo JText::_("NEW_PRODUCT"); ?></label>
				<div class="controls">
					<?php echo JHTML::_('select.genericlist',  $products, 'new_product_id', 'class="inputbox" size="1" ', 'id', 'title', $this->ticket->prod_id); ?>
				</div>
			</div>
		<?php endif; ?>

		<?php $departments = SupportHelper::getDepartments(); ?>
		<?php if (count($departments) > 0): ?>			
			<div class="control-group">
				<label class="control-label"><?php echo JText::_("NEW_DEPARTMENT"); ?></label>
				<div class="controls">
					<?php echo JHTML::_('select.genericlist',  $departments, 'new_department_id', 'class="inputbox" size="1" ', 'id', 'title', $this->ticket->ticket_dept_id); ?>
				</div>
			</div>
		<?php endif; ?>
		
		<div class="control-group">
			<label class="control-label"><?php echo JText::_("NEW_HANDLER"); ?></label>
			<div class="controls">
				<select name="new_handler" id="new_handler">
					<option value="-1" <?php if (FSS_Settings::get('forward_product_handler') == 'unchanged') echo "selected"; ?>>
						<?php echo JText::_("Unchanged") ?>
					</option>		
					<option value="-2" <?php if (FSS_Settings::get('forward_product_handler') == 'auto') echo "selected"; ?>>
						<?php echo JText::_("AUTO_ASSIGN") ?>
					</option>		
					<option value="0" <?php if (FSS_Settings::get('forward_product_handler') == 'unassigned') echo "selected"; ?>>
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
			<label class="control-label"><?php echo JText::_("MESSAGE_TO_HANDLER"); ?></label>
			<div class="controls">
				<?php echo SupportCanned::CannedDropdown("body2", true, $this->ticket); ?>
			</div>
		</div>

		<p>
			<textarea style='width:95%;height:<?php echo (int)((FSS_Settings::get('support_admin_reply_height') * 15) + 80); ?>px' name='body2' id='body2' class="sceditor" rows='<?php echo (int)FSS_Settings::get('support_admin_reply_height'); ?>' cols='<?php echo (int)FSS_Settings::get('support_admin_reply_width'); ?>'></textarea>
		</p>

		<div class="control-group">
			<label class="control-label"><?php echo JText::_("MESSAGE_TO_USER"); ?></label>
			<div class="controls">
				<?php echo SupportCanned::CannedDropdown("body", true, $this->ticket); ?>
			</div>
		</div>

		<p>
			<textarea style='width:95%;height:<?php echo (int)((FSS_Settings::get('support_admin_reply_height') * 15) + 80); ?>px' name='body' id='body' class="sceditor" rows='<?php echo (int)FSS_Settings::get('support_admin_reply_height'); ?>' cols='<?php echo (int)FSS_Settings::get('support_admin_reply_width'); ?>'></textarea>
		</p>
		