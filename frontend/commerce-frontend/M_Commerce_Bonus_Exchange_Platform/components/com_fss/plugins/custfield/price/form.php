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
		<label class="control-label">Symbol</label>
	</div>
	<div class="controls">
		<input type='text' name='price_symbol' value='<?php echo $params->symbol; ?>'>
	</div>
</div>

<div class="control-group">
	<div class="control-label">
		<label class="control-label">Location</label>
	</div>
	<div class="controls">
		<select name="price_location">
			<option value="0">Prefix</option>
			<option value="1" <?php if ($params->location == 1) echo "selected"; ?>>Suffix</option>
		</select>
	</div>
</div>
