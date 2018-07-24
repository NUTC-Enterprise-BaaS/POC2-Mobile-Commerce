<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="view" value="timezone" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="timezone" />

<table>
	<tr>
		<td></td>
		<th>Current Time</th>
		<th>Time Zone</th>
	</tr>
	<tr>
		<th>Server Time</th>
		<td><?php echo system("date"); ?></td>
		<td></td>
	</tr>
</table>
</form>
<?php

