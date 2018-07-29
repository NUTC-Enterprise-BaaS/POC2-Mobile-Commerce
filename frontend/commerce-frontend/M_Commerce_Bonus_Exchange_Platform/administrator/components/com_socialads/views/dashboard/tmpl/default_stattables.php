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

<!-- Start - Pending orders details -->
<div class="panel panel-default">
	<div class="panel-heading">
		<i class="fa fa-list fa-fw"></i>
		<?php echo JText::_('COM_SOCIALADS_MONTHLY_PENDING_ORDERS'); ?>
	</div>
	<div class="panel-body">
		<?php if (!empty($this->pendingorders)): ?>
			<table class="table table-hover">
				<thead>
					<tr>
						<th class="col-lg-2"><?php echo JText::_("COM_SOCIALADS_PENDING_ORDERS_PAYEE_NAME"); ?></th>
						<th class="col-lg-2"><?php echo JText::_("COM_SOCIALADS_PENDING_ORDERS_PROCESSOR"); ?></th>
						<th class="col-lg-2"><?php echo JText::_("COM_SOCIALADS_PENDING_ORDERS_AMOUNT"); ?></th>
					</tr>
				</thead>

				<?php
				foreach ($this->pendingorders as $porders)
				{
					?>
					<tbody>
						<tr>
							<td class="col-lg-2"><?php echo $porders->uname; ?></td>
							<td class="col-lg-2"><?php echo $porders->processor; ?></td>
							<td class="col-lg-2"><?php echo $porders->amount; ?></td>
						</tr>
					</tbody>
					<?php
				}
				?>
			</table>

			<div>
				<a class="pull-right clearfix" href="<?php
					// If payment mode is pay per ad then redirect to adorders view else to orders view
					if ($this->params->get('payment_mode') == 'wallet_mode')
					{
						echo 'index.php?option=com_socialads&view=orders&filter.status=P';
					}
					else
					{
						echo 'index.php?option=com_socialads&view=adorders&filter.status=P';
					}
					?>" target="_blank">
					<?php echo JText::_('COM_SOCIALADS_SEE_ALL');?>
				</a>
			</div>

		<?php else:
			echo JText::_("COM_SOCIALADS_NO_DATA_FOUND");
		endif;
		?>
	</div>
</div>
<!-- End - Pending orders details -->

<!-- Start - Top ads -->
<div class="panel panel-default">
	<div class="panel-heading">
		<i class="fa fa-line-chart"></i>
		<?php echo JText::_('COM_SOCIALADS_TOP_PERFORMING_ADS'); ?>
	</div>
	<div class="panel-body">
		<?php if (!empty($this->topads)): ?>
			<table class="table table-hover">
				<thead>
					<tr>
						<th class="col-lg-2"><?php echo JText::_("COM_SOCIALADS_ORDERS_AD_ID"); ?></th>
						<th class="col-lg-2"><?php echo JText::_("COM_SOCIALADS_ADORDERS_AD_TITLE"); ?></th>
						<th class="col-lg-2"><?php echo JText::_("COM_SOCIALADS_CTR"); ?></th>
					</tr>
				</thead>

				<?php
				foreach ($this->topads as $tads)
				{
					?>
					<tbody>
						<tr>
							<td class="col-lg-2"><?php echo $tads['ad_id']; ?></td>
							<td class="col-lg-2"><?php echo $tads['ad_title']; ?></td>
							<td class="col-lg-2"> <?php echo number_format((float) $tads['ctr'], 6, '.', ''); ?></td>
						</tr>
					</tbody>
					<?php
				}
				?>
			</table>
		<?php else:
			echo JText::_("COM_SOCIALADS_NO_DATA_FOUND");
		endif; ?>
	</div>
</div>
<!-- End - Top ads -->
