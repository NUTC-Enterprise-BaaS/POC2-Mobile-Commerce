<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>

<?php echo FSS_Helper::PageStyle(); ?>
<?php echo FSS_Helper::PageTitle("Reports", $this->report->title); ?>
<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_tabbar.php'); ?>

<?php if (isset($this->report->style)): ?>
	<style>
		<?php echo $this->report->style; ?>
	</style>
<?php endif; ?>
<p>
	<div class="btn-group pull-right">
		<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#">
			<i class="icon-print"></i>
			<?php echo JText::_('Print'); ?>
			<span class="caret"></span>
		</a>
		
		<ul class="dropdown-menu">
			<li>
				<a href="<?php echo JRoute::_('&output=print&tmpl=component'); ?>" target='_new'><?php echo JText::_('ALL_DETAILS'); ?></a>
			</li>
			<li>
				<a href="<?php echo JRoute::_('&output=print&tmpl=component&type=noheader'); ?>" target='_new'><?php echo JText::_('NO_HEADER'); ?></a>
			</li>
			<li>
				<a href="<?php echo JRoute::_('&output=print&tmpl=component&type=bare'); ?>" target='_new'><?php echo JText::_('TABLE_ONLY'); ?></a>
			</li>
		</ul>
    </div>
	
	<div class="pull-right">&nbsp;</div>
	<div class="pull-right">
	       
		<a class="btn btn-default" href="<?php echo JRoute::_('&output=csv'); ?>"><?php echo JText::_('AS_CSV'); ?></a>
		<?php if (JDEBUG || FSS_Settings::get('debug_reports')): ?>
			<a class="btn btn-default" href="#" onclick='jQuery("#sql").toggle();return false;'><?php echo JText::_('SHOW_SQL'); ?></a>
		<?php endif; ?>
	</div>

	<a class="btn btn-default" href="<?php echo JRoute::_('index.php?option=com_fss&view=admin_report'); ?>">
		<i class="icon-arrow-left"></i>
		<?php echo JText::_('BACK_TO_REPORTS'); ?>
	</a>
</p>

<form action="<?php echo JRoute::_('index.php'); ?>" name='report_params' id='report_params'>

	<input type="hidden" name="option" value="com_fss" />
	<input type="hidden" name="view" value="admin_report" />
	<input type="hidden" name="report" value="<?php echo FSS_Helper::escape(FSS_Input::getCmd('report')); ?>" />
	
	<div class="well well-small form-horizontal form-condensed">
		<?php echo $this->report->getFilters(); ?>
	</div>
	
	<?php if (JDEBUG || FSS_Settings::get('debug_reports')): ?>
		<div style="display:none;" id="sql">
			<h5><?php echo JText::_('BASE_SQL'); ?>:</h5>
			<pre><?php echo $this->report->sql; ?></pre>
			<h5><?php echo JText::_('FINAL_SQL'); ?>:</h5>
			<pre><?php echo $this->report->final_sql; ?></pre>
			<h5><?php echo JText::_('REPORT_TAGS'); ?>:</h5>
			<table class="table table-bordered table-striped table-condensed table-narrow">
				<?php foreach ($this->report->vars as $key => $value): ?>
					<tr>
						<th>{<?php echo $key; ?>}</th>
						<td><?php echo $value; ?></td>
					</tr>
				<?php endforeach; ?>
			</table>
		</div>
	<?php endif; ?>
	
</form>

<?php if ($this->report->error): ?>
	<div class="alert alert-error">
		<?php echo str_replace("\n"," ",$this->report->error); ?>
	</div>
	<div>
		<pre class="line_numbers"><span><?php echo implode("</span>\n<span>", explode("\n", "\n".$this->report->sql)); ?></span></pre>
	</div>

	<?php if (preg_match("/at line (\d{1,3})/", $this->report->error, $matches)): ?>
		<style>
		pre.line_numbers span:nth-child(<?php echo $matches[1]; ?>)
		{
			font-weight: bold;
			color: red;
		}
		</style>
	<?php endif; ?>

<?php endif; ?>

<?php if (false /*$this->graph*/): ?>

<script>
function ResetElement(tabid)
{
	document.getElementById('tab_' + tabid).style.display = 'none';
	document.getElementById('link_' + tabid).style.backgroundColor = '';

	document.getElementById('link_' + tabid).onmouseover = function() {
		this.style.backgroundColor='<?php echo FSS_Settings::get('css_hl'); ?>';
	}
	document.getElementById('link_' + tabid).onmouseout = function() {
		this.style.backgroundColor='';
	}

}
function ShowTab(tabid)
{
	ResetElement('data');
	ResetElement('graph');
	
	location.hash = tabid;
	
	jQuery.cookie('fss_tab_report', tabid);
	
	jQuery('#tab').val(tabid);
	
	document.getElementById('tab_' + tabid).style.display = 'inline';
	document.getElementById('link_' + tabid).style.backgroundColor = '#f0f0ff';
	
	document.getElementById('link_' + tabid).onmouseover = function() {
	
	}
	document.getElementById('link_' + tabid).onmouseout = function() {
	
	}
}

jQuery(document).ready( function () {
	if (location.hash)
	{
		ShowTab(location.hash.replace('#',''));
	}
	else if (jQuery.cookie('fss_tab_report'))
	{
		ShowTab(jQuery.cookie('fss_tab_report'));
	} else {
		ShowTab('data');
	}
});

function fsj_datepreset(obj)
{
	
}

</script>
<div class="ffs_tabs">
<a id='link_data' class="ffs_tab " href="#" onclick="ShowTab('data');return false;">Data</a> 
<a id='link_graph' class="ffs_tab " href="#"  onclick="ShowTab('graph');return false;">Graph</a>
</div>
<?php endif; ?>
<style>

table.fss_report_table tr.row1 td {
	border-top: 1px solid #ccc;
}

table.fss_report_table  td {
	padding: 3px;
}

table.fss_report_table  th {
	padding: 3px;
	text-align: left;
}
</style>

<div class='table-responsive'>
	<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_report'.DS.'snippet'.DS.'_report_table.php'); ?>
</div>

<?php if (false /*$this->graph*/): ?>
<div id="tab_graph">
	Somehow invoke jqplot here with our data
	
</div>
<?php endif; ?>

<?php echo FSS_Helper::PageStyleEnd(); ?>