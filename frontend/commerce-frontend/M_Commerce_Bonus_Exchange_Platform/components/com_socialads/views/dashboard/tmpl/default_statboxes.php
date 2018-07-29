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
$statsdataforlinechart = $this->statsforbar;
$totalclicks  = 0;
$totalimpressions = 0;
$totalctr = 0;
foreach ($statsdataforlinechart as $data )
{
	$totalclicks += $data->click;
	$totalimpressions += $data->impression;
}

if ($totalclicks != 0 && $totalimpressions != 0)
{
	$totalctr = number_format(($totalclicks)/($totalimpressions), 4);
}
?>

<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 stat-box">
	<div class="panel panel-olive-tradewind">
		<div class="panel-heading">
			<div class="row">
				<div class="center">
					<i class="fa fa-shopping-cart fa-2x"></i>
				</div>
				<div class="center">
					<div>
						<h4><?php echo JText::_('COM_SOCIALADS_DASHBOARD_NUMBER_OF_ACTIVE_ADS'); ?><?php echo " : " . $this->activeAds;?></h4>
					</div>
					<div>
						<h4><?php echo JText::_('COM_SOCIALADS_DASHBOARD_NUMBER_OF_INACTIVE_ADS'); ?><?php echo " : " . $this->inactiveAds;?></h4>

					</div>
				</div>
			</div>
		</div>
		<a href="index.php?option=com_socialads&view=ads">
			<div class="panel-footer">
				<span class="pull-left">
					<?php echo JText::_('COM_SOCIALADS_VIEW_DETAILS'); ?>
				</span>
				<span class="pull-right">
					<i class="fa fa-arrow-circle-right"></i>
				</span>
				<div class="clearfix"></div>
			</div>
		</a>
	</div>
</div>

<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 stat-box">
	<div class="panel panel-blue-viking">
		<div class="panel-heading">
			<div class="row">
				<div class="center">
					<i class="fa fa-bullhorn fa-2x"></i>
				</div>
				<div class="center">
					<div>
						<h4><?php echo JText::_('COM_SOCIALADS_DASHBOARD_CLICKS'); ?><?php echo " : "  . $totalclicks;?></h4>
						<h4><?php echo JText::_('COM_SOCIALADS_DASHBOARD_TOTAL_IMPRESSIONS'); ?><?php echo " : " . $totalimpressions;?></h4>
					</div>
				</div>
			</div>
		</div>
		<a href="index.php?option=com_socialads&view=ads">
			<div class="panel-footer">
				<span class="pull-left">
					<?php echo JText::_('COM_SOCIALADS_VIEW_DETAILS'); ?>
				</span>
				<span class="pull-right">
					<i class="fa fa-arrow-circle-right"></i>
				</span>
				<div class="clearfix"></div>
			</div>
		</a>
	</div>
</div>

<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 stat-box">
	<div class="panel panel-green-downy">
		<div class="panel-heading">
			<div class="row">
				<div class="center">
					<i class="fa fa-random fa-2x"></i>
				</div>
				<div class="center">
					<div>
						<h4><?php echo JText::_('COM_SOCIALADS_DASHBOARD_AVERAGE_CTR'); ?></h4>
						<h4><b><?php echo $totalctr;?></b></h4>
					</div>
				</div>
			</div>
		</div>
		<a href="index.php?option=com_socialads&view=ads">
			<div class="panel-footer">
				<span class="pull-left">
					<?php echo JText::_('COM_SOCIALADS_VIEW_DETAILS'); ?>
				</span>
				<span class="pull-right">
					<i class="fa fa-arrow-circle-right"></i>
				</span>
				<div class="clearfix"></div>
			</div>
		</a>
	</div>
</div>
<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 stat-box">
	<div class="panel panel-purple-blue-marguerita">
		<div class="panel-heading">
			<div class="row">
				<div class="center">
					<i class="fa fa-money fa-2x"></i>
				</div>
				<div class="center">
					<div>
						<?php
						if ($this->params->get('payment_mode')=="pay_per_ad_mode")
						{?>
							<h4><?php echo JText::_('COM_SOCIALADS_DASHBOARD_TOTAL_SPENT'); ?></h4>
						<?php
						}
						elseif ($this->params->get('payment_mode')=="wallet_mode")
						{?>
							<h4><?php echo JText::_('COM_SOCIALADS_DASHBOARD_WALLET_BALAENCE'); ?></h4>
						<?php
						}?>
						<h4><?php echo SaCommonHelper::getFormattedPrice($this->totalSpent);?></h4>
					</div>
				</div>
			</div>
		</div>
		<a href="<?php
			if ($this->params->get('payment_mode')=="pay_per_ad_mode")
			{
				echo 'index.php?option=com_socialads&view=myorders';
			}
			elseif ($this->params->get('payment_mode')=="wallet_mode")
			{
				echo 'index.php?option=com_socialads&view=wallet';
			}
			?>">
			<div class="panel-footer">
				<span class="pull-left">
					<?php echo JText::_('COM_SOCIALADS_VIEW_DETAILS'); ?>
				</span>
				<span class="pull-right">
					<i class="fa fa-arrow-circle-right"></i>
				</span>
				<div class="clearfix"></div>
			</div>
		</a>
	</div>
</div>
