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
		<label class="control-label">Items</label>
	</div>
	<div class="controls">
		<textarea name="dualcombo_items" style="width:400px;height:400px;"><?php echo $params->items; ?></textarea>
		<span class="help-inline">
			<p>Enter the items to display in your combo box. Use + to denote a group item for the first combo box displayed, and the rest of the items will be filtered in the 2nd box.</p>
			<p>An example list of data is below. It will create a combo with Ford, Volkswagen and Citeron in, and when selected display a 2nd combo wher the sub item can be selected.</p>
			<pre>+Ford
Fiesta
Mondeo
Focus
+Volkswagen
Golf
Passat
Polo
+Citeron
C3
C4
C1</pre>
			<!--<li>For documentation see <a href='http://freestyle-joomla.com/help/other/knowledge-base?prodid=1&kbartid=97' target='_blank'>here</a></li>-->
		</span>
	</div>
</div>

<div class="control-group">
	<div class="control-label">
		<label class="control-label">Display Separator</label>
	</div>
	<div class="controls">
		<input name="dualcombo_separator" type="text" value="<?php echo htmlspecialchars($params->separator); ?>" />
	</div>
</div>

<div class="control-group">
	<div class="control-label">
		<label class="control-label">First Combo Header</label>
	</div>
	<div class="controls">
		<input name="dualcombo_header1" type="text" value="<?php echo htmlspecialchars($params->header1); ?>" />
	</div>
</div>

<div class="control-group">
	<div class="control-label">
		<label class="control-label">Second Combo Header</label>
	</div>
	<div class="controls">
		<input name="dualcombo_header2" type="text" value="<?php echo htmlspecialchars($params->header2); ?>" />
	</div>
</div>

<div class="control-group">
	<div class="control-label">
		<label class="control-label">Combo Separator</label>
	</div>
	<div class="controls">
		<input name="dualcombo_boxsep" type="text" value="<?php echo htmlspecialchars($params->boxsep); ?>" />
	</div>
</div>

<div class="control-group">
	<div class="control-label">
		<label class="control-label">Hide group in display</label>
	</div>
	<div class="controls">
		<input name="dualcombo_hidegroup" type="checkbox" value="1" <?php if ($params->hidegroup) echo 'checked="checked"' ?> />
	</div>
</div>	  	 	  	 	  			