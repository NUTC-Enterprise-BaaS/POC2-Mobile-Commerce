<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */
// No direct access
defined('_JEXEC') or die;?>
<div class="col-sm-12 col-lg-12 col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="pull-left">
				<i class="fa fa-list fa-fw"></i>
				<span><?php echo JText::_('COM_SOCIALADS_DASHBOARD_QUICK_REPORTS'); ?></span>
			</div>
			<div class="clearfix"></div>
		</div>
		<div id="stripedTable" class="panel-collapse collapse in">
			<div class="panel-body">
				<div class="row">
					<div class="col-sm-12 col-lg-12 col-md-12">
						<ul class="nav nav-tabs">
							<li class="active">
								<a href="#report_1" data-toggle="tab">
									<?php echo JText::_('COM_SOCIALADS_DASHBOARD_TOP_PERFORMING_ADS'); ?>
								</a>
							</li>
							<li class="">
								<a href="#report_2" data-toggle="tab">
									<?php echo JText::_('COM_SOCIALADS_DASHBOARD_PENDING_OREDERS'); ?>
								</a>
							</li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="report_1">
								<?php if (!empty($this->topads))
								{
								?>
								<div>&nbsp;</div>
								<div class="no-more-tables">
								<table class="table table-striped table-hover table-bordered">
									<thead>
										<tr>
											<th><?php echo JText::_('COM_SOCIALADS_DASHBOARD_AD_ID'); ?></th>
											<th><?php echo JText::_('COM_SOCIALADS_DASHBOARD_AD_TITLE'); ?></th>
											<th><?php echo JText::_('COM_SOCIALADS_DASHBOARD_CTR'); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($this->topads as $top)
										{
										?>
											<tr>
												<td data-title="<?php echo JText::_("COM_SOCIALADS_DASHBOARD_AD_ID"); ?>"><?php echo $top['ad_id'];?></td>
												<td data-title="<?php echo JText::_("COM_SOCIALADS_DASHBOARD_AD_TITLE"); ?>"><?php echo $top['ad_title'];?></td>
												<td data-title="<?php echo JText::_("COM_SOCIALADS_DASHBOARD_CTR"); ?>"><?php echo number_format((float) $top['ctr'], 6, '.', ''); ?></td>
											</tr>
										<?php
										}?>
									</tbody>
								</table>
								</div>
								<?php
								}
								else
								{?>
									<div>&nbsp;</div>
									<div class="alert alert-info">
										<?php echo JText::_("COM_SOCIALADS_FILTER_SEARCH_NOT_FOUND");?>
									</div>
								<?php
								}
								?>
							</div>
							<div class="tab-pane" id="report_2">
								<?php if (!empty($this->pendingorders))
								{
								?>
								<div>&nbsp;</div>
								<div class="no-more-tables">
								<table class="table table-striped table-hover table-bordered">
									<thead>
										<tr>
											<th width="9%" class="center"><?php echo JText::_('COM_SOCIALADS_DASHBOARD_ORDERS_ID'); ?></th>
											<?php if ($this->params->get('payment_mode')=="pay_per_ad_mode")
											{?>
												<th><?php echo JText::_('COM_SOCIALADS_DASHBOARD_AD_TITLE');?></th>
											<?php
											} ?>
											<th><?php echo JText::_('COM_SOCIALADS_DASHBOARD_ORDERS_AMOUNT');?></th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($this->pendingorders as $porders)
										{
										?>
										<tr>
											<td class="center" data-title="<?php echo JText::_("COM_SOCIALADS_DASHBOARD_ORDERS_ID"); ?>"><?php echo $porders->id;?></td>
											<?php if ($this->params->get('payment_mode')=="pay_per_ad_mode")
											{?>
												<td data-title="<?php echo JText::_("COM_SOCIALADS_DASHBOARD_AD_TITLE"); ?>"><?php echo $porders->ad_title;?></td>
											<?php
											} ?>
											<td data-title="<?php echo JText::_("COM_SOCIALADS_DASHBOARD_ORDERS_AMOUNT"); ?>"><?php echo SaCommonHelper::getFormattedPrice($porders->amount);?></td>
										</tr>
										<?php
										}
										?>
									</tbody>
								</table>
								</div>
									<?php
									if ($this->params->get('payment_mode')=="pay_per_ad_mode")
									{
											$link ='index.php?option=com_socialads&view=myorders'?>
										<a href="<?php echo JRoute::_($link, false);?>" title="<?php echo JText::_('COM_SOCIALADS_DASHBOARD_SHOW_ALL_DESC');?>" class="btn btn-primary btn-small pull-right">
										<?php echo JText::_('COM_SOCIALADS_DASHBOARD_SHOW_ALL'); ?>
										</a>
									<?php
									}
									elseif ($this->params->get('payment_mode')=="wallet_mode")
									{
										$link ='index.php?option=com_socialads&view=wallet'?>
										<a href="<?php echo JRoute::_($link, false);?>" title="<?php echo JText::_('COM_SOCIALADS_DASHBOARD_SHOW_ALL_DESC');?>" class="btn btn-primary btn-small pull-right">
										<?php echo JText::_('COM_SOCIALADS_DASHBOARD_SHOW_ALL'); ?>
										</a>
									<?php
									}
								}
								else
								{?>
									<div>&nbsp;</div>
									<div class="alert alert-info">
										<?php echo JText::_("COM_SOCIALADS_FILTER_SEARCH_NOT_FOUND");?>
									</div>
								<?php
								}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
