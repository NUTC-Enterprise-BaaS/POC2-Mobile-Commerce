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
		<label class="control-label">HTML</label>
	</div>
	<div class="controls">
		<textarea name='plugin_html_output'  cols='80' rows='6' style='width:425px;'><?php echo htmlentities($params); ?></textarea>
	</div>
</div>
