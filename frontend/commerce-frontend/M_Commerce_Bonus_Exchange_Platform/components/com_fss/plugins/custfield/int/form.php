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
		<label class="control-label">Minimum</label>
	</div>
	<div class="controls">
		<input type='text' name='int_min' value='<?php echo $params->min; ?>'>
	</div>
</div>

<div class="control-group">
	<div class="control-label">
		<label class="control-label">Maximum</label>
	</div>
	<div class="controls">
		<input type='text' name='int_max' value='<?php echo $params->max; ?>'>
	</div>
</div>
