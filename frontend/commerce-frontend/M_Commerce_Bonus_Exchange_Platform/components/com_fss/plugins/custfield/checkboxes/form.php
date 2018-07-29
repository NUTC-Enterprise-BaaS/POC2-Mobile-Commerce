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
		<label class="control-label">Entries (1 per line)</label>
	</div>
	<div class="controls">
		<textarea name='checkboxes_entries' cols='60' rows='10' style="width: 425px;"><?php 
		foreach ($params->entries as $entry) echo htmlentities($entry) . "\n"; 
		?></textarea>
	</div>
</div>
