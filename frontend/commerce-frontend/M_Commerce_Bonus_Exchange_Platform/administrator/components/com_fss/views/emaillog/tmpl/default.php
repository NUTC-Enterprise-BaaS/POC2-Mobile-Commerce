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
<style>
#account, #date, #emailstatus {
	margin-bottom: 0;
}
</style>

<form action="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=emaillog' );?>" method="post" name="adminForm" id="adminForm">
<p>
	<?php echo $this->account; ?>
	<?php echo $this->dates; ?>
	<?php echo $this->statuslist; ?>
	<button class="btn btn-primary" onclick="this.form.submit();"><?php echo JText::_("GO"); ?></button>
	<button class="btn btn-default" onclick="this.form.getElementById('taskname').value='';this.form.getElementById('date').value='';this.form.submit();"><?php echo JText::_("RESET"); ?></button>
</p>

<input type="hidden" name="task" value="" id="task">
<input type="hidden" name="page" value="<?php echo $this->page; ?>" id="page">

<?php if (count($this->rows) == 0): ?>
	<div>No log data found</div>
<?php else :?>
	
	<table class="table table-striped table-bordered table-condensed">
	<tr>
	<th>Recieved</th>
	<th>Subject</th>
	<th>Status</th>
	<th>Sender</th>
	<th>Last Seen</th>
	<th>Log</th>
</tr>
<?php foreach ($this->rows as $row): ?>
	
	<tr>
		<td style="white-space: nowrap;">
			<?php echo FSS_Helper::Date($row->firstseen,FSS_DATETIME_MYSQL); ?>
		</td>
		<td>
			<?php echo $row->subject; ?>
		</td>
		<td>
			<?php echo $row->currentlog; ?>
		</td>
		<td>
			<?php echo $row->from; ?>
		</td>
		<td style="white-space: nowrap;">
			<?php echo FSS_Helper::Date($row->lastseen,FSS_DATETIME_MYSQL); ?>
</td>
<td>
<?php 
$log = str_replace("\n", "<br />", $row->oldlog);
$log = htmlspecialchars($log);
?>
			<a href="#" onclick="return false;" data-placement="left" class="fssTip" title="<?php echo $log; ?>">Log</a>
		</td>	
	</tr>
<?php endforeach; ?>
	</table>	
	
	<?php if ($this->pagecount > 1) : ?>
	<div class="pagination">
		<ul>
		<?php for ($i = 0; $i < $this->pagecount; $i++) : ?>
			<?php if ($i == $this->page) :?>
				<li class="active"><a><?php echo ($i+1); ?></a></li>
			<?php else: ?>
				<li><a href="#" onclick='SetPage(<?php echo $i; ?>); return false;'><?php echo ($i+1); ?></a></li>
			<?php endif; ?>
		<?php endfor; ?>
		</ul>
	</div>
	<?php endif; ?>
<?php endif; ?>
<script>

function ClearCron()
{
	if (confirm('Are you sure? This cannot be undone.'))
	{
		document.getElementById('task').value='clear';
		return true;
	}

	return false;
}

function SetPage(page)
{
	document.getElementById('page').value=page;
	document.getElementById('adminForm').submit();
}

</script>

</div>