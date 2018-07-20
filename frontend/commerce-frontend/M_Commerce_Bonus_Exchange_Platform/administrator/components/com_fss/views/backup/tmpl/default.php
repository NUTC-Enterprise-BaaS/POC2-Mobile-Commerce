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
<?php if ($this->log) : ?>
<h1>Your upgrade has been completed.</h1>
<h4>The log of this process is below.</h4>
<?php $logno = 1; ?>
<?php foreach ($this->log as &$log): ?>
	<div>
	<div style="margin:4px;font-size:115%;"><a href="#" onclick="ToggleLog('log<?php echo $logno; ?>');return false;">+<?php echo $log['name']; ?></a></div>
	<div id="log<?php echo $logno; ?>" style="display:none;">
	<pre style="margin-left: 20px;border: 1px solid #ccc;padding: 4px 5px;"><?php echo $log['log']; ?></pre>
	</div>
</div>
	<?php $logno++; ?>
<?php endforeach; ?>

<script>
function ToggleLog(log)
{
	if (document.getElementById(log).style.display == "inline")
	{
		document.getElementById(log).style.display = 'none';
	} else {
		document.getElementById(log).style.display = 'inline';
	}
}
</script>
<?php else: ?>

<h1><?php echo JText::_("API_KEY"); ?></h1>
To use the automatic Joomla updater, you need to enter your username and API key for freestyle-joomla.com here. To find your API get, please goto <a href='http://freestyle-joomla.com/my-account' target="_blank">http://freestyle-joomla.com/my-account</a> and log in.<br/><br/>
<form action="<?php echo FSSRoute::_("index.php?option=com_fss&view=backup&task=saveapi"); ?>" method="post" name="adminForm3" id="adminForm3"></::>
<table>
	<tr>
		<th>Username:</th>
		<td><input id="username" type='text' name="username" value="<?php echo FSS_Settings::get('fsj_username'); ?>"></td>
	</tr>
	<tr>
		<th>API Key:</th>
		<td><input id="apikey" type='text' name="apikey" size="60" value="<?php echo FSS_Settings::get('fsj_apikey'); ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><input class='btn btn-default' type="submit" name="Save" value="<?php echo JText::_("SAVE"); ?>"></td>
	</tr>
</table>
<br />
<h3>Checking API Key:</h3>
<iframe src="http://www.freestyle-joomla.com/api/validateapi.php?username=<?php echo FSS_Settings::get('fsj_username'); ?>&apikey=<?php echo FSS_Settings::get('fsj_apikey'); ?>" width="600" height="50" frameborder="0" border="0" scrolling="no"></iframe>
</form>

<h1><?php echo JText::_("UPDATE"); ?></h1>
<a class='btn btn-default' href='<?php echo FSSRoute::_("index.php?option=com_fss&view=backup&task=update"); ?>'><?php echo JText::_("PROCESS_FREESTYLE_JOOMLA_INSTALL_UPDATE"); ?></a><br />&nbsp;<br />

<h1><?php echo JText::_("BACKUP_DATABASE"); ?></h1>
<a class='btn btn-default' href='<?php echo FSSRoute::_("index.php?option=com_fss&view=backup&task=backup"); ?>'><?php echo JText::_("DOWNLOAD_BACKUP_NOW"); ?></a><br />&nbsp;<br />

<h1><?php echo JText::_("RESTORE_DATABASE"); ?></h1>
<div style="color:red; font-size:150%"><?php echo JText::_("PLEASE_NOTE_THE_WILL_OVERWRITE_AND_EXISTING_DATA_FOR_FREESTYLE_SUPPORT_PORTAL"); ?></div>

<?php echo JText::_("YOU_CAN_ALSO_RESTORE_BACKUPS_FROM_FREESTYLE_TESTIMONIALS_LITE_AND_FREESTYLE_FAQS_LITE_HERE"); ?><br>

<form action="<?php echo FSSRoute::_("index.php?option=com_fss&view=backup&task=restore"); ?>"  method="post" name="adminForm2" id="adminForm2" enctype="multipart/form-data"></::>
<input type="file" id="filedata" name="filedata" /><input type="submit" class='btn btn-default' name="Restore" value="<?php echo JText::_("RESTORE"); ?>">
</form>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="task" id="task" value="" />
<input type="hidden" name="view" value="backup" />
</form>
<?php endif; ?>
</div>