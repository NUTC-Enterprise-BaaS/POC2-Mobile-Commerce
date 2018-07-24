<?php
/**
 * @version    SVN: <svn_id>
 * @package    Jgive
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root(true) . '/media/techjoomla_strapper/bs3/css/bootstrap.min.css');
$document->addStyleSheet(JUri::root(true) . '/media/com_sa/vendors/font-awesome/css/font-awesome.min.css');
$document->addStyleSheet(JUri::root(true) . '/media/com_sa/vendors/morris/morris.css');

$document->addStyleSheet(JUri::root(true) . '/media/com_sa/css/tjdashboard-sb-admin.css');
$document->addStyleSheet(JUri::root(true) . '/media/com_sa/css/tjdashboard.css');

$document->addScript(JUri::root(true) . '/media/com_sa/vendors/morris/morris.min.js');
$document->addScript(JUri::root(true) . '/media/com_sa/vendors/morris/raphael.min.js');
?>
<div class="<?php echo SA_WRAPPER_CLASS; ?> tj-dashboard" id="sa-dashboard">
	<div class="page-header">
		<h1>
			<?php echo JText::_('COM_SOCIALADS_DASHBOARD');?>
		</h1>
	</div>
	<form action="" method="post" name="adminForm" id="adminForm">
		<div class="tjBs3">
			<div class="tjDB">
				<div class="row">
					<?php echo $this->loadTemplate('statboxes'); ?>
				</div>
				<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-bar-chart-o fa-fw"></i>
					<?php echo JText::_('COM_SOCIALADS_PREVIOUS_MONTHS_STATS'); ?>
				</div>
				<div class="panel-body">
					<div class="col-xs-12">
						<?php
							if (!empty($this->statsforbar))
							{ ?>
								<div id="curve_chart"></div>
							<?php
							} ?>
					</div>
				</div>
				</div>
				<div>&nbsp;</div>
				<div class="row">
					<?php echo $this->loadTemplate('reports'); ?>
				</div>
			</div>
		</div>
	</form>
</div>
<script>
	Morris.Line({
	element: 'curve_chart',
	data :<?php
			echo json_encode($this->statsforbar);
		?>,
	xkey: 'date',
	ykeys: ['click','impression'],
	labels: ['<?php echo JText::_('COM_SOCIALADS_ADS_AD_NO_OF_CLICKS');?>','<?php echo JText::_('COM_SOCIALADS_ADS_AD_TYPE_IMPRS');?>'],
	xLabels: '<?php echo JText::_('COM_SOCIALADS_DATE')?>',
	xLabelAngle: 70,
	lineColors: ['#FFA500','#3EA99F'],
	hideHover: 'auto',
	resize: true,
	smooth: false,
	});
</script>
