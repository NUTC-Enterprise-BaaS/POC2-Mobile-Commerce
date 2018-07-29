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
JHtml::_('behavior.modal', 'a.modal');
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

if (JVERSION > '3.0')
{
	JHtml::_('formbehavior.chosen', 'select');
}

JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal', 'a.modal');

$document     = JFactory::getDocument();
$document->addStyleSheet(JUri::root(true) . '/media/com_sa/vendors/font-awesome/css/font-awesome.min.css');
$model        = $this->getModel('ads');
$ad_params    = JComponentHelper::getParams('com_socialads');
$payment_mode = $ad_params->get('payment_mode');
$userId       = $this->user->get('id');
$listOrder    = $this->state->get('list.ordering');
$listDirn     = $this->state->get('list.direction');
$canOrder     = $this->user->authorise('core.edit.state', 'com_socialads');
$saveOrder    = $listOrder == 'a.ordering';
$statsdataforlinechart = $this->statsforbar;
$totalclicks  = 0;
$totalimpressions = 0;
$totalctr = 0;
$canDo = SocialadsHelper::getActions();
?>
<div class="<?php echo SA_WRAPPER_CLASS;?>" id="sa-ads">
	<form action="" method="post" name="adminForm" id="adminForm">
		<div>
			<h1><?php echo JText::_('COM_SOCIALADS_MANAGE_ADS');?></h1>
		</div>
		<div id="container">
			<?php
			if (JVERSION >= '3.0'):
			?>
				<div>
					<?php echo $this->toolbarHTML;?>
				</div>
				<div class="clearfix"> </div>
				<hr class="hr-condensed" />
				<div class="btn-group pull-right hidden-phone social-ads-filter-margin-left">
					<?php echo $this->pagination->getLimitBox(); ?>
				</div>
			<?php
			endif; ?>
			<div class="btn-group pull-right hidden-phone social-ads-filter-margin-left">
				<?php
				if ($ad_params->get('payment_mode') == 'wallet_mode')
					{
						echo JHtml::_('select.genericlist', $this->campaignsoptions, "filter_campaignslist", 'class="ad-status inputbox input-medium" size="1"
						onchange="document.adminForm.submit();" name="campaignslist"', "value", "text", $this->state->get('filter.campaignslist'));
					} ?>
			</div>
			<div class="btn-group pull-right hidden-phone social-ads-filter-margin-left">
				<?php echo JHtml::_('select.genericlist', $this->zonesoptions, "filter_zoneslist", 'class="ad-status inputbox input-medium" size="1" onchange="document.adminForm.submit();" name="zoneslist"', "value", "text", $this->state->get('filter.zoneslist')); ?>
			</div>
			<div class="btn-group pull-right hidden-phone hidden-tablet social-ads-filter-margin-left">
				<?php
					echo JHtml::_('select.genericlist', $this->adstatus, "filter_adstatus", 'class="ad-status inputbox input-medium" size="1"
					onchange="document.adminForm.submit();" name="adstatus"', "value", "text", $this->state->get('filter.adstatus'));
				?>
			</div>
			<div class="clearfix"></div>
			<?php
			if (empty($this->items)):
			?>
				<div class="clearfix">&nbsp;</div>
					<div class="alert">
						<?php
						echo JText::_('COM_SOCIALADS_FILTER_SEARCH_NOT_FOUND');
						?>
					</div>
			<?php
			else:
			?>
				<div id="no-more-tables" class="table-responsive ads-list">
					<table class="table table-striped table-condensed" id="dataList">
						<thead>
							<tr>
								<th class="hidden-phone">
									<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL');?>" onclick="Joomla.checkAll(this)" />
								</th>
								<th>
									<?php echo JHtml::_('grid.sort', 'COM_SOCIALADS_ADS_AD_TITLE', 'a.ad_title', $listDirn, $listOrder); ?>
								</th>
								<?php
								if (isset($this->items[0]->state)): ?>
									<th class="nowrap center">
										<?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder);  ?>
									</th>
								<?php
								endif;
								if ($ad_params->get('payment_mode') == 'wallet_mode')
								{ ?>
									<th>
										<?php echo JHtml::_('grid.sort', 'COM_SOCIALADS_ADS_AD_CAMPAGIN', 'c.campaign', $listDirn, $listOrder); ?>
									</th>
								<?php
								} ?>
								<th>
									<?php echo JHtml::_('grid.sort', 'COM_SOCIALADS_ADS_AD_ZONE', 'a.ad_zone', $listDirn, $listOrder); ?>
								</th>
								<th>
									<?php echo JHtml::_('grid.sort', 'COM_SOCIALADS_ADS_AD_TYPE', 'a.ad_title', $listDirn, $listOrder); ?>
								</th>
								<?php
								if ($payment_mode == 'pay_per_ad_mode')
								{ ?>
									<th class="center">
										<?php echo JHtml::_('grid.sort', 'COM_SOCIALADS_PAYMENT_STATUS', 'ao.status', $listDirn, $listOrder); ?>
									</th>
								<?php
								} ?>
								<th>
									<?php echo JHtml::_('grid.sort', 'COM_SOCIALADS_ADS_AD_TYPE_IMPRS', 'impressions', $listDirn, $listOrder); ?>
								</th>
								<th>
									<?php echo JHtml::_('grid.sort', 'COM_SOCIALADS_ADS_AD_NO_OF_CLICKS', 'clicks', $listDirn, $listOrder); ?>
								</th>
								<th>
									<?php echo JHtml::_('grid.sort', 'COM_SOCIALADS_ADS_AD_TYPE_C_T_R', 'impressions', $listDirn, $listOrder); ?>
								</th>
								<th>
									<?php echo JText::_('COM_SOCIALADS_ADS_IGNORES'); ?>
								</th>
								<th class="center">
									<?php echo JText::_('COM_SOCIALADS_ADS_AD_ACTIONS'); ?>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($this->items as $i => $item): ?>
								<tr class="row<?php echo $i % 2;?>">
									<td class="">
										<?php echo JHtml::_('grid.id', $i, $item->ad_id); ?>
									</td>
									<td data-title="<?php echo JText::_('COM_SOCIALADS_ADS_AD_TITLE');?>" class="">
										<?php
										if ($canDo->get("core.edit")): ?>
											<a href="<?php echo JRoute::_('index.php?option=com_socialads&view=adform&ad_id='.(int) $item->ad_id); ?>">
												<span class="ad-type-img">
													<?php
													if ($item->ad_guest == 1)
													{ ?>
														<i class="fa fa-user"></i>
													<?php
													}
													elseif (($item->ad_guest == 0) && ($item->ad_alternative == 0))
													{ ?>
														<img src="<?php echo JUri::root(true) . '/media/com_sa/images/group.png';?>" />
													<?php
													}?>
												</span>
												<?php echo $this->escape($item->ad_title); ?>
											</a>
										<?php
										else: ?>
											<?php echo $this->escape($item->ad_title); ?>
										<?php
										endif;?>
									</td>
									<td class="center" data-title="<?php echo JText::_('JSTATUS');?>">
										<div>
											<a class="btn btn-micro hasTooltip" href="javascript:void(0);" title="<?php echo ($item->state) ? JText::_('COM_SOCIALADS_ADS_UNPUBLISH') : JText::_('COM_SOCIALADS_ADS_PUBLISH');?>"
											onclick="document.adminForm.cb<?php echo $i; ?>.checked=1; document.adminForm.boxchecked.value=1; Joomla.submitbutton('<?php echo ($item->state) ? 'ads.unpublish' : 'ads.publish';?>');">
												<img src="<?php echo JUri::root(true); ?>/media/com_sa/images/<?php echo ($item->state) ? 'publish.png' : 'unpublish.png';?>"/>
											</a>
										</div>
									</td>
									<?php
									if ($ad_params->get('payment_mode') == 'wallet_mode')
									{?>
										<td data-title="<?php echo JText::_('COM_SOCIALADS_ADS_AD_CAMPAGIN');?>">
											<?php
											if($item->campaign == "")
											{
												echo JText::_("COM_SOCIALADS_NA");
											}
											else
											{
												echo $item->campaign;
											} ?>
										</td>
									<?php
									}?>
									<td data-title="<?php echo JText::_('COM_SOCIALADS_ADS_AD_ZONE');?>">
										<?php echo $item->zone_name; ?>
									</td>
									<td data-title="<?php echo JText::_('COM_SOCIALADS_ADS_AD_TYPE');?>">
										<?php
										if ($item->ad_alternative == 1)
										{
											echo JText::_('COM_SOCIALADS_ADS_AD_TYPE_ALT_AD');
										}
										elseif ($item->ad_noexpiry == 1)
										{
											echo JText::_('COM_SOCIALADS_ADS_AD_TYPE_UNLTD_AD');
										}
										else if ($item->ad_affiliate == 1)
										{
											echo JText::_('COM_SOCIALADS_AD_TYP_AFFI');
										}
										else
										{
											if ($item->ad_payment_type == 0)
											{
												echo JText::_('COM_SOCIALADS_ADS_AD_TYPE_IMPRS');
											}
											else if ($item->ad_payment_type == 1)
											{
												echo JText::_('COM_SOCIALADS_ADS_AD_TYPE_CLICKS');
											}
											else
											{?>
												<img src="<?php echo JUri::root(true) . '/media/com_sa/images/start_date.png' ?>">
													<?php echo $item->ad_startdate; ?>
													<br/>
												<?php
												if(($item->ad_enddate!='0000-00-00') )			//if not 0 then	only show end date
												{?>
													<img src="<?php echo JUri::root(true) . '/media/com_sa/images/end_date.png' ?>">
													<?php echo $item->ad_enddate;
												} ?>
											<?php
											}
										}?>
									</td>
									<?php
									if ($payment_mode == 'pay_per_ad_mode')
									{ ?>
										<td class="center" data-title="<?php echo JText::_('COM_SOCIALADS_PAYMENT_STATUS');?>">
											<?php
											if ($item->ad_alternative == 1 || $item->ad_noexpiry == 1 || $item->ad_affiliate == 1)
											{ ?>
												<i class="fa fa-check"></i>
											<?php
											}
											else
											{
												switch ($item->status)
												{
													case 'P': ?>
														<i class="fa fa-clock-o"> </i>
														<?php
														break;
													case 'C': ?>
														<i class="fa fa-check"></i>
														<?php
														break;
													case 'RF': ?>
														<i class="fa fa-times"></i>
														<?php
														break;
													default: ?>
															<i class="fa fa-minus-circle"></i>
														<?php
														break;
												}
											} ?>
										</td>
										<?php
									}

									// Popover for ad credits and availability
									$out_of = '';

									if ($payment_mode == 'pay_per_ad_mode')
									{
										// if camp ad is there den they dont have credits..
										if ($item->camp_id!=0 && !$item->bid_value)
										{
											$out_of = '';
										}
										elseif ($item->bid_value > 0)
										{
											$out_of = $item->bid_value;
										}
										elseif ($item->ad_alternative== 1 || $item->ad_noexpiry== 1 || $item->ad_affiliate == 1)
										{
											$out_of = JText::_('COM_SOCIALADS_CREDIT_UNLIMITED');
										}
										elseif ($item->ad_payment_type == 2)
										{
											$out_of = '';
										}
										else
										{
											$out_of = $item->ad_credits_balance;
										}

										if ($out_of)
										{
											$text_to_show = JText::_('COM_SOCIALADS_CREDITS_AVAILABLE')." : " . $out_of . '<br />';

											if ($item->ad_payment_type == 0)
											{
												$text_to_show .= JText::_('COM_SOCIALADS_ADS_AD_TYPE_IMPRS')." : " . $item->impressions;
											}

											if($item->ad_payment_type == 1)
											{
												$text_to_show .= JText::_('COM_SOCIALADS_ADS_AD_NO_OF_CLICKS')." : " . $item->clicks;
											}

											$out_of_anchor = '<a class="ad_type_tootip" data-content="' . $text_to_show.'" data-placement="top" data-html="html"  data-trigger="hover" rel="popover" >';
											$out_of_anchor = ' / ' . $out_of_anchor . $out_of . '</a>';
										}
									} ?>

									<td data-title="<?php echo JText::_('COM_SOCIALADS_ADS_AD_NO_OF_IMPRESSIONS');?>">
										<?php
										if ($item->impressions)
										{
											echo $item->impressions;
										}
										else
										{
											echo "0";
										}

										// If ad is type is impreddions then show available credits
										if ($item->ad_payment_type == 0 && $out_of)
										{
											echo $out_of_anchor;
										} ?>
									</td>
									<td data-title="<?php echo JText::_('COM_SOCIALADS_ADS_AD_NO_OF_CLICKS');?>">
										<?php
										if ($item->clicks)
										{
											echo $item->clicks;
										}
										else
										{
											echo "0";
										}

										// If ad is type is clicks then show available credits
										if ($item->ad_payment_type == 1 && $out_of)
										{
											echo $out_of_anchor;
										}
										?>
									</td>
									<td data-title="<?php echo JText::_('COM_SOCIALADS_ADS_AD_TYPE_C_T_R');?>">
										<?php
										if ($item->impressions != 0)
										{
											$ctr = ($item->clicks) / ($item->impressions);
											echo number_format($ctr, 6);
										}
										else
										{
											echo number_format($item->clicks, 6);
										}
										?>
									</td>
									<td class="actions" data-title="<?php echo JText::_('COM_SOCIALADS_ADS_IGNORES');?>">
										<?php
										$ignorecounts = $model->getIgnorecount($item->ad_id);

										if ($ignorecounts == 0)
										{
											echo $ignorecounts;
										}
										else
										{
											$link = JRoute::_('index.php?option=com_socialads&view=ignores&tmpl=component&adid=' . $item->ad_id); ?>
											<a href="<?php echo $link; ?>" rel="{handler: 'iframe', size: {x: 800, y: 350}}" class="modal">
												<?php echo $model->getIgnorecount($item->ad_id); ?>
											</a>
										<?php
										} ?>
									</td>
									<td data-title="<?php echo JText::_('COM_SOCIALADS_ADS_AD_ACTIONS');?>">
										<?php
										$paymentHistory = JRoute::_(JUri::base() . 'index.php?option=com_socialads&tmpl=component&view=adsummary&layout=payment_history&Itemid=' . $item->ad_id . '&adid=' . $item->ad_id);
										$stats = JRoute::_(JUri::base() . 'index.php?option=com_socialads&tmpl=component&view=adsummary&Itemid=' . $item->ad_id . '&adid=' . $item->ad_id);
										$link = JRoute::_('index.php?option=com_socialads&view=preview&tmpl=component&layout=default&id=' . $item->ad_id);
										?>
										<div class="btn-group actions">
											<a rel="{handler: 'iframe', size: {x: 350, y: 350}}"title="<?php echo JText::_('COM_SOCIALADS_AD_PREVIEW'); ?>" class="modal btn" href="<?php echo $link; ?>"  >
													<i class="fa fa-picture-o"></i>
											</a>
											<a rel="{handler: 'iframe', size: {x: 900, y: 350}}" title="<?php echo JText::_('COM_SOCIALADS_ADS_PAYMENT_HISTORY'); ?> "  href="<?php echo $paymentHistory; ?>" class="modal  btn">
												<i class="fa fa-money"></i>
											</a>
											<a rel="{handler: 'iframe', size: {x: 1100, y: 700}}" title="<?php echo JText::_('COM_SOCIALADS_AD_STATS'); ?>" href="<?php echo $stats; ?>" class="modal btn saActions">
												<i class="fa fa-bar-chart"></i>
											</a>
										</div>
									</td>
								</tr>
							<?php
							endforeach; ?>
						</tbody>
					</table>
				</div>
				<div class="pull-right clearfix">
					<?php echo $this->pagination->getListFooter(); ?>
				</div>
				<div class="clearfix"></div>
				<div class="alert alert-info">
					<div class="pull-left sa-legends-padding">
						<div>
							<i class="fa fa-user"></i> = <?php echo JText::_('COM_SOCIALADS_GUEST_ADS'); ?>
						</div>
						<div>
							<img src="<?php echo JUri::root(true) . '/media/com_sa/images/group.png'; ?>"/> = <?php echo JText::_('COM_SOCIALADS_TARGET_ADS'); ?>
						</div>
					</div>
					<div class="pull-left sa-legends-padding">
						<div>
							<i class="fa fa-minus-circle"></i> = <?php echo JText::_('COM_SOCIALADS_NO_ADORDER'); ?>
						</div>
						<div>
							<i class="fa fa-clock-o" ></i> = <?php echo JText::_('COM_SOCIALADS_SA_PENDIN'); ?>
						</div>
					</div>
					<div class="pull-left sa-legends-padding">
						<div>
							<i class="fa fa-check"></i> = <?php echo JText::_('COM_SOCIALADS_SA_CONFIRM') . ' / ' . JText::_('COM_SOCIALADS_SA_APPROVE'); ?>
						</div>
						<div>
							<i class="fa fa-times"></i> = <?php echo JText::_('COM_SOCIALADS_SA_REFUND') . ' / ' . JText::_('COM_SOCIALADS_SA_REJEC'); ?>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>
			<?php
			endif; ?>
			<input type="hidden" id='reason' name="reason" value="" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" id='hidid' name="id" value="" />
			<input type="hidden" id='hidstat' name="status" value="" />
			<input type="hidden" id='hidzone' name="zone" value="" />
			<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>
<script>
	Joomla.submitbutton = function(action)
	{
		sa.ads.submitButtonAction(action)
	}
	techjoomla.jQuery(document).ready(function()
	{
		jQuery('.ad_type_tootip').popover();
	});
</script>
