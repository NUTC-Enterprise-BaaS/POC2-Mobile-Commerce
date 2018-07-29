<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

?>
<table class='table table-bordered table-striped' style="width: inherit;">

	<tr>
		<th>Attachments</th>
		<td><?php echo $this->stats->files; ?>
	</tr>

	<tr>
		<th>Total Size</th>
		<td><?php echo FSS_Helper::display_filesize($this->stats->size); ?>
	</tr>

	<tr>
		<th>Missing on disk</th>
		<td><?php echo $this->stats->missing; ?>
	</tr>

	<tr>
		<th>Orphaned files</th>
		<td><?php echo $this->stats->orphaned; ?>
	</tr>

	<tr>
		<th>Thumbnails</th>
		<td><?php echo $this->stats->thumbs; ?>
	</tr>

	<tr>
		<th>Thumb Size</th>
		<td><?php echo FSS_Helper::display_filesize($this->stats->thumbsize); ?>
	</tr>

</table>
