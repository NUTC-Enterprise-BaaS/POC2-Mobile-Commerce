<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<div class="fss_main">
	<table id="table_border" class="table table-bordered">
		<tr>
			<td>Table</td>
		</tr>	
	</table>

	<div id="glyph-test">
		<i class="icon-arrow-up"></i>
	</div>

	<p id="text-col-success" class="text-success">Etiam porta sem malesuada magna mollis euismod.</p>
	<p id="text-col-warning" class="text-error">Donec ullamcorper nulla non metus auctor fringilla.</p>

	<form class="form-horizontal form-condensed">
	</form>

	
</div>

<script>
<?php if (!FSS_Settings::get('hide_warnings')): ?>
	if (top.location == location)
	{
		jQuery('.fss_main').first().prepend("<div class='alert alert-error'><h4>Freestyle Support Portal Error: Frame Breakout issue detected</h4>Your template has a setting enabled that is causing iframes to be redirected to the main window. You will need to disable this for Freestyle Support Portal to work correctly as it uses iframes extensively.</div>");
	}
<?php endif; ?>
</script>