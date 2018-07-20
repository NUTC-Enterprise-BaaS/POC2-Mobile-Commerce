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
		<label class="control-label">Use Time</label>
	</div>
	<div class="controls">
		<input type='checkbox' name='cal_use_time' value='1' <?php if ($values->use_time) echo 'checked'; ?> />
	</div>
</div>

<div class="control-group">
	<div class="control-label">
		<label class="control-label">Today as default</label>
	</div>
	<div class="controls">
		<input type='checkbox' name='cal_today_default' value='1' <?php if ($values->today_default) echo 'checked'; ?> />
	</div>
</div>

<div class="control-group">
	<div class="control-label">
		<label class="control-label">No past selections</label>
	</div>
	<div class="controls">
		<input type='checkbox' name='cal_no_past' value='1' <?php if ($values->no_past) echo 'checked'; ?> />
	</div>
</div>

<div class="control-group">
	<div class="control-label">
		<label class="control-label">Maximum</label>
	</div>
	<div class="controls">
		<input name='cal_format' value='<?php echo $values->format; ?>'>
		<span class="help-inline">Leave blank for default format.</span>
	</div>
</div>

<div>
<div style="display: inline-block;padding-right: 16px;">
	<b>%d</b> - day as number (with leading zero)<br>
	<b>%j</b> - day as number<br>
	<b>%D</b> - abbreviated name of the day<br>
	<b>%l</b> - full name of the day<br>
	<br />
	<br />
</div>
<div style="display: inline-block;padding-right: 16px;">
	<b>%m</b> - month as number (with leading zero)<br>
	<b>%n</b> - month as number<br>
	<b>%M</b> - abbreviated name of the month<br>
	<b>%F</b> - full name of the month<br>
	<b>%y</b> - year as number (2 digits)<br>
	<b>%Y</b> - year as number (4 digits)<br>
</div>
<div style="display: inline-block;padding-right: 16px;">
	<b>%h</b> - hours (12)<br>
	<b>%H</b> - hours (24)<br>
	<b>%i</b> - minutes<br>
	<b>%s</b> - seconds<br>
	<b>%a</b> - am or pm<br>
	<b>%A</b> - AM or PM<br>
</div>
</div>
