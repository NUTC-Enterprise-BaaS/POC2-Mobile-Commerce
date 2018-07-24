<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>

<?php $mins = array(0, 1, 2, 3, 4, 5, 10, 15, 20, 25, 30, 45); ?>
<?php $hours = array(0, 1, 1.5, 2, 2.5, 3, 4, 5, 6, 7, 8); ?>
		
<div class="form-horizontal form-condensed">
	<div class="control-group">
		<label class="control-label"><?php echo JText::_('TIME_HOURS'); ?></label>
		<div class="controls">
			<input type="text" style="width:30px" id="taken_hours" name="taken_hours" value="0" />
			<span class="help-inline">
				<?php foreach ($hours as $hour) : ?>
					<a href="#" onclick="time_set_hour(<?php echo $hour; ?>); return false;"><?php echo str_replace(".5", "&frac12;", $hour); ?></a>
				<?php endforeach; ?>
			</span>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label"><?php echo JText::_('TIME_MINS'); ?></label>
		<div class="controls">
			<input type="text" style="width:30px" id="taken_mins" name="taken_mins" value="0" />
			<span class="help-inline">
				<?php foreach ($mins as $min) : ?>
					<a href="#" onclick="time_set_min(<?php echo $min; ?>); return false;"><?php echo $min ?></a>
				<?php endforeach; ?>	
			</span>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label"><?php echo JText::_('TIME_NOTES'); ?></label>
		<div class="controls">
			<input type="text" id="taken_notes" name="taken_notes"/>
		</div>
	</div>

</div>
		