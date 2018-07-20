<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */
// no direct access
defined('_JEXEC') or die;
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<i class="fa fa-pie-chart fa-fw"></i>
		<?php echo JText::_('COM_SOCIALADS_MONTHLY_PERIODIC_ORDERS_DETAILS'); ?>
	</div>
	<div class="panel-body">
		<div class="form-inline">
			<div class="col-lg-5 col-md-5 col-sm-5 pull-left">
				<label for="from" class="hidden-xs"><?php echo JText::_('COM_SOCIALADS_STATS_FROM_DATE'); ?></label>
				<?php
				echo JHtml::_('calendar', $this->from_date, 'from', 'from', '%Y-%m-%d', array(
					'class' => 'inputbox input-xs sa-dashboard-calender'
				));
				?>
			</div>
			<div class="col-lg-5 col-md-5 col-sm-5 pull-left">
				<label for="to" class="hidden-xs"><?php echo JText::_("COM_SOCIALADS_STATS_TO_DATE"); ?></label>
				<?php
				echo JHtml::_('calendar', $this->to_date, 'to', 'to', '%Y-%m-%d', array(
					'class' => 'inputbox input-xs sa-dashboard-calender'
				));
				?>
			</div>
			<div class="col-lg-2 col-md-2 col-sm-2 pull-left">
				<label class="hidden-xs">&nbsp;</label>
				<input id="btnRefresh" class="btn btn-micro btn-primary" type="button" value="<?php echo JText::_("COM_SOCIALADS_GO");?>" onclick="saAdmin.dashboard.validatePeriodicDates();Joomla.submitform();" />
			</div>
			<div class="clearfix"></div>
		</div>

		<div class="col-sm-12 col-md-12 col-lg-12">
			<div>
				<h4 class="center"><?php echo JText::_('COM_SOCIALADS_PERIODIC_INCOME'); ?></h4>
			</div>
			<?php
			// To show income between selected dates
			if ($this->periodicorderscount)
			{
				?>
				<div class="huge center">
					<?php echo $this->currency . ' ' . $this->periodicorderscount; ?>
				</div>
				<?php
			}
			else
			{
				?>
				<div class="huge center">
					<?php echo $this->currency . " 0"; ?>
				</div>
				<?php
			}
			?>
		</div>

		<div class="col-sm-12 col-md-12 col-lg-12">
			<div id="donut-chart">
			</div>
			<div class="center" id="donut-chart-msg">
				<?php echo JText::_("COM_SOCIALADS_NO_DATA_FOUND"); ?>
			</div>
		</div>
	</div>
</div>
