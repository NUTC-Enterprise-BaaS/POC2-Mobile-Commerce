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
		<label class="control-label">Display</label>
	</div>
	<div class="controls">
		<textarea name="sql_display" style="width:400px;height:200px;"><?php echo $params->display; ?></textarea>
		<span class="help-inline">
			<i>Enter the HTML used to display the data. Any of the SQL variable can be used, along with any of the returned fields.</i> <br />
			<li>For documentation see <a href='http://freestyle-joomla.com/help/other/knowledge-base?prodid=1&kbartid=97' target='_blank'>here</a></li>
		</span>
	</div>
</div>
