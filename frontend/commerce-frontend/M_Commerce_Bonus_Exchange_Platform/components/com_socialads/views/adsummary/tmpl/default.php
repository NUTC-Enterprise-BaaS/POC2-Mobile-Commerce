<?php
/**
 * @version     SVN:<SVN_ID>
 * @package     Com_Socialads
 * @author      Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license     GNU General Public License version 2, or later
 */

// No direct access
defined('_JEXEC') or die;
JHtml::_('behavior.framework');
JHtml::_('behavior.formvalidation');
$ad_params    = JComponentHelper::getParams('com_socialads');
$payment_mode = $ad_params->get('payment_mode');
$document     = JFactory::getDocument();
$document->addScript(JUri::root(true) . '/media/com_sa/vendors/morris/morris.min.js');
$document->addScript(JUri::root(true) . '/media/com_sa/vendors/morris/raphael.min.js');
$document->addStyleSheet(JUri::root(true) . '/media/com_sa/vendors/morris/morris.css');
$document->addStyleSheet(JUri::root(true) . '/media/com_sa/css/tjdashboard-sb-admin.css');
$document->addStyleSheet(JUri::root(true) . '/media/com_sa/css/tjdashboard.css');
$document->addStyleSheet(JUri::root(true) . '/media/techjoomla_strapper/bs3/css/bootstrap.min.css');
?>
<div class="<?php echo SA_WRAPPER_CLASS; ?> tj-adsummary" id="sa-adsummary">
	<div class="page-header">
		<h1>
			<?php echo JText::_('COM_SOCIALADS_AD_STATS');?>
		</h1>
	</div>
	<form action="" method="post" name="adminForm" id="adminForm">
		<div class="tjBs3">
			<div class="tjDB">
				<div class="row">
				<div class="col-lg-4 col-md-5 col-sm-5 col-xs-9">
					<div class="form-group">
						<label label-default class="col-lg-2 col-md-2 col-sm-2 col-xs-3 control-label">
							<?php echo JText::_('COM_SOCIALADS_STATS_FROM_DATE'); ?>
						</label>
						<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
							<div class="input-group">
								<?php echo JHtml::_('calendar', $this->from_date, 'from', 'from', '%Y-%m-%d', array('class' => ' input-small input-sm')); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-5 col-sm-5 col-xs-9">
					<div class="form-group">
						<label label-default class="col-lg-2 col-md-2 col-sm-2 col-xs-3 control-label">
							<?php echo JText::_("COM_SOCIALADS_STATS_TO_DATE"); ?>
						</label>
						<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
							<div class="input-group">
								<?php echo JHtml::_('calendar', $this->to_date, 'to', 'to', '%Y-%m-%d', array('class' => ' input-small input-sm')); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
					<input id="getadstatds" type="button" class="btn btn-success" value="<?php echo JText::_("COM_SOCIALADS_GO"); ?>" onclick="saAdmin.dashboard.validatePeriodicDates();Joomla.submitform();"/>
				</div>
				<div class="clearfix"></div>
			</div>
				<div class="row-fluid">
					<div class="col-sm-6 col-md-6 col-lg-6">
						<div class="panel panel-default">
							<div class="panel-heading">
								<i class="fa fa-bar-chart-o fa-fw"></i>
									<b><?php echo JText::_('COM_SOCIALADS_LINE_CHART_STAT'); ?></b>
							</div>
							<div class="panel-body">
								<?php if (!empty($this->statsforbar))
								{?>
									<div id="curve_chart"></div>
								<?php
								}
								else
								{?>
									<div class="">
										<?php echo JText::_("COM_SOCIALADS_NO_STATS_FOUND");?>
									</div>
								<?php
								}?>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-md-6 col-lg-6">
						<div class="panel panel-default">
							<div class="panel-heading">
								<i class="fa fa-bar-chart-o fa-fw"></i>
									<b><?php echo JText::_('COM_SOCIALADS_PIE_CHART_STAT'); ?></b>
							</div>
							<div class="panel-body">
								<?php
								if ($this->statsforpie[0] > 0 or $this->statsforpie[1] > 0)
								{?>
									<div id="donut_chart"></div>
								<?php
								}
								else
								{?>
									<div class="">
										<?php echo JText::_("COM_SOCIALADS_NO_STATS_FOUND");?>
									</div>
								<?php
								}?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" name="task" id="task" value="" />
		<input type="hidden" name="option" value="com_socialads" />
		<input type="hidden" name="view" value="adsummary" />
		<input type="hidden" name="layout" value="default" />
	</form>
</div>
<script>
	<!-- To draw charts on page load -->
	techjoomla.jQuery(document).ready(function() {
		drawCharts();
	});

	<!-- Function to draw charts on click for ststs tab -->
	function drawCharts()
	{
		<!-- SetTimeout function used to draw charts on page reload -->
		setTimeout(function()
		{
			techjoomla.jQuery('#curve_chart').html('');
			techjoomla.jQuery('#donut_chart').html('');
			<?php
			if (!empty($this->statsforbar))
			{
			?>
				<!-- Line chart for ad summary -->
				Morris.Line({
				element: 'curve_chart',
				data :<?php echo json_encode($this->statsforbar);?>,
				xkey: 'date',
				ykeys: ['click','impression'],
				labels: ['<?php echo JText::_('COM_SOCIALADS_FORM_LBL_ARCHIVESTAT_CLICKS');?>','<?php echo JText::_('COM_SOCIALADS_FORM_LBL_ARCHIVESTAT_IMPRESSIONS');?>'],
				xLabels: '<?php echo JText::_('COM_SOCIALADS_DATE')?>',
				lineColors: ['#FFA500','#3EA99F'],
				hideHover: 'auto',
				resize: true,
				});
			<?php
			}
			?>

			<?php
			if ($this->statsforpie[0] > 0 or $this->statsforpie[1] > 0)
			{
			?>
				<!-- Donut chart for ad summary -->
				Morris.Donut({
				element: 'donut_chart',
				data: [
				{label: "<?php echo JText::_('COM_SOCIALADS_FORM_LBL_ARCHIVESTAT_CLICKS');?>", value: <?php echo $this->statsforpie[1];?>},
				{label: "<?php echo JText::_('COM_SOCIALADS_FORM_LBL_ARCHIVESTAT_IMPRESSIONS');?>", value: <?php echo $this->statsforpie[0];?>},
				],
				colors: ["#f0ad4e", "#5cb85c"]
				});
			<?php
			}
			?>
		},300);
	}
</script>
