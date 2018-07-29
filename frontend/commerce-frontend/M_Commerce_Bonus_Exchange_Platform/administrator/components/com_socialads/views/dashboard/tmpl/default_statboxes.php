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

<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
	<div class="panel panel-olive-tradewind">
		<div class="panel-heading">
			<div class="row">
				<div class="col-xs-3 ">
					<i class="fa fa-money fa-4x"></i>
				</div>
				<div class="col-xs-9 text-right">
					<div>
						<h4><?php echo JText::_('COM_SOCIALADS_ALL_TIME_INCOME'); ?></h4>
					</div>
					<div class="huge">
						<?php
						// Print all time income
						if ($this->allincome)
						{
							echo $this->currency . ' ' . $this->allincome;
						}
						else
						{
							echo $this->currency . " 0";
						}
						?>
					</div>
				</div>
			</div>
		</div>
		<a href="<?php
			if ($this->params->get('payment_mode') == 'wallet_mode')
			{
				echo 'index.php?option=com_socialads&view=orders&filter.status=C';
			}
			else
			{
				echo 'index.php?option=com_socialads&view=adorders&filter.status=C';
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

<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
	<div class="panel panel-blue-viking">
		<div class="panel-heading">
			<div class="row">
				<div class="col-xs-3 ">
					<i class="fa fa-bullhorn fa-4x"></i>
				</div>
				<div class="col-xs-9 text-right">
					<div>
						<h4><?php echo JText::_('COM_SOCIALADS_TOTAL_ADS'); ?></h4>
					</div>
					<div class="huge">
						<?php
						// Print all time income
						if ($this->totalads)
						{
							echo $this->totalads;
						}
						else
						{
							echo "0";
						}
						?>
					</div>
				</div>
			</div>
		</div>
		<a href="index.php?option=com_socialads&view=forms">
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

<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
	<div class="panel panel-purple-blue-marguerita">
		<div class="panel-heading">
			<div class="row">
				<div class="col-xs-3 ">
					<i class="fa fa-random fa-4x"></i>
				</div>
				<div class="col-xs-9 text-right">
					<div>
						<h4><?php echo JText::_('COM_SOCIALADS_AVERAGE_CTR'); ?></h4>
					</div>
					<div class="huge">
						<?php
						// Print average CTR
						if ($this->averagectr > 0)
						{
							echo number_format((float) $this->averagectr, 6, '.', '');
						}
						else
						{
							echo " 0.00";
						}
						?>
					</div>
				</div>
			</div>
		</div>
		<a href="index.php?option=com_socialads&view=forms">
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

<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
	<div class="panel panel-green-downy">
		<div class="panel-heading">
			<div class="row">
				<div class="col-xs-3 ">
					<i class="fa fa-shopping-cart fa-4x"></i>
				</div>
				<div class="col-xs-9 text-right">
					<div>
						<h4><?php echo JText::_('COM_SOCIALADS_TOTAL_ORDERS'); ?></h4>
					</div>
					<div class="huge">
						<?php
						// Print orders count
						if ($this->totalorders > 0)
						{
							echo $this->totalorders;
						}
						else
						{
							echo "0";
						}
						?>
					</div>
				</div>
			</div>
		</div>
		<a href="<?php
			if ($this->params->get('payment_mode') == 'wallet_mode')
			{
				echo 'index.php?option=com_socialads&view=orders&filter.status=';
			}
			else
			{
				echo 'index.php?option=com_socialads&view=adorders&filter.status=';
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
