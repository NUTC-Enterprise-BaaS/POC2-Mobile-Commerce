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
	<div class="control-label">
		<label class="control-label"><?php echo JText::_("RADIO_GROUP_VALUES"); ?></label>
	</div>
	<div class="controls">
		<textarea cols="40" rows="5" style="width: 420px;" name="radioplus_items"><?php echo implode("\n", $params->items); ?></textarea>
		<span class="help-inline">
			<i><?php echo JText::_("PLEASE_ENTER_ONE_VALUE_PER_ROW"); ?></i>
		</span>
	</div>
</div>

<div class="control-group">
	<div class="control-label">
		<label class="control-label">'Other' label:</label>
	</div>
	<div class="controls">
		<input type='text' name='radioplus_other_label' value='<?php echo $params->other_label; ?>'>
	</div>
</div>
