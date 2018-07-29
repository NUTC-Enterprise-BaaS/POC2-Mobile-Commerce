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
		<label class="control-label">SQL Command</label>
	</div>
	<div class="controls">
		<textarea name="sql_command" style="width:400px;height:200px;"><?php echo $params->command; ?></textarea>
		<span class="help-inline">
			<i>Enter the SQL Command used to display the data you require. The first row returned will be parsed using the display below. 
			The following will be replaces within the SQL:</i> <br />
			<ul>
				<li>{user_id} - The tickets user id</li>
				<li>{admin_id} - The tickets admin id</li>
				<li>{id} - The tickets id</li>
				<li>{reference} - The ticket reference</li>
			</ul>
			<li>For documentation see <a href='http://freestyle-joomla.com/help/other/knowledge-base?prodid=1&kbartid=97' target='_blank'>here</a></li>
		</span>
	</div>
</div>

<div class="control-group">
	<div class="control-label">
		<label class="control-label">Display Field</label>
	</div>
	<div class="controls">
		<input name="sql_display" type="text" value="<?php echo htmlspecialchars($params->display); ?>" />
	</div>
</div>

<div class="control-group">
	<div class="control-label">
		<label class="control-label">ID Field</label>
	</div>
	<div class="controls">
		<input name="sql_field" type="text" value="<?php echo htmlspecialchars($params->field); ?>" />
	</div>
</div>

<div class="control-group">
	<div class="control-label">
		<label class="control-label">Grouping Field</label>
	</div>
	<div class="controls">
		<input name="sql_group" type="text" value="<?php echo htmlspecialchars($params->group); ?>" />
		<span class="help-inline">
			Entering a field here will group the combo box entries by this field as a heading. You must do a "ORDER BY" in your SQL with this field first 
			or duplicate headers will be displayed.
		</span>
	</div>
</div>

<div class="control-group">
	<div class="control-label">
		<label class="control-label">Multi Select</label>
	</div>
	<div class="controls">
		<input name="sql_multi" type="checkbox" value="1" <?php if ($params->multi) echo 'checked="checked"' ?> />
	</div>
</div>

<div class="control-group">
	<div class="control-label">
		<label class="control-label">Select Box Class</label>
	</div>
	<div class="controls">
		<input name="sql_class_select" type="text" value="<?php echo htmlspecialchars($params->class_select); ?>" />
	</div>
</div>

<div class="control-group">
	<div class="control-label">
		<label class="control-label">Display Span Class</label>
	</div>
	<div class="controls">
		<input name="sql_class_label" type="text" value="<?php echo htmlspecialchars($params->class_label); ?>" />
	</div>
</div>
