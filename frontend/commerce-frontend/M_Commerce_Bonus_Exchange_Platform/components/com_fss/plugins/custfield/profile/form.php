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
		<label class="control-label">Profile Field</label>
	</div>
	<div class="controls">
		<?php echo JHTML::_('select.genericlist', $options_parsed, 'profile_field', ' onchange="profile_changed();"', 'value', 'text', $params->field); ?>
	</div>
</div>

<div class="control-group profile_custom_container" id="">
	<div class="control-label">
		<label class="control-label">Custom Format</label>
	</div>
	<div class="controls">
		<textarea rows="8" cols="80" name="profile_custom" style="width: 420px";><?php echo htmlentities($params->custom); ?></textarea>
		<span class="help-inline">
			<i>For when 'Profile Field' is 'Custom List', one entry per line. For information about the format see <a href='http://freestyle-joomla.com/help/other/knowledge-base?prodid=1&kbartid=96' target='_blank'>here</a>.</i>
		</span>
	</div>
</div>

<div class="control-group profile_custom_container" id="">
	<div class="control-label">
		<label class="control-label">Custom Tags</label>
	</div>
	<div class="controls">
		<span class="help-inline">
			<p>You can use the following tags in your custom format:</p>
			<ul>
				<?php foreach ($keys as $key => $display): ?>
				<li><b>{<?php echo $key; ?>}</b> => <?php echo $display; ?></li>
				<?php endforeach; ?>
			</ul>
		</span>
	</div>
</div>

<script>
function profile_changed()
{
	if (jQuery('#profile_field').val() == '-')
		jQuery('#profile_field').val('email');

	if (jQuery('#profile_field').val() == 'custom.html' || jQuery('#profile_field').val() == 'custom.text')
	{
		jQuery('.profile_custom_container').show();
	} else {
		jQuery('.profile_custom_container').hide();
	}
}
profile_changed();
</script>