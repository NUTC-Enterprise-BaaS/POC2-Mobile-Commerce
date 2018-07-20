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
			<label class="control-label"><?php echo JText::_("COMMENT"); ?></label>
			<div class="controls">
				<?php echo SupportCanned::CannedDropdown("body2", true, $this->ticket); ?>
			</div>
		</div>

		<p>
			<textarea style='width:95%;height:<?php echo (int)( (FSS_Settings::get('support_admin_reply_height') * 15) + 80); ?>px' name='body2' id='body2' class="sceditor" rows='<?php echo (int)FSS_Settings::get('support_admin_reply_height'); ?>' cols='<?php echo (int)FSS_Settings::get('support_admin_reply_width'); ?>'></textarea>
		</p>

		<input name="hidefromuser" value="1" type="hidden" />
	