<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Com_Socialads
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.formvalidation');
JHtmlBehavior::framework();

// Fetch ad detail


$gatwayName = 'bycheck';
$plugin = JPluginHelper::getPlugin( 'payment',$gatwayName);

$sa_params = JComponentHelper::getParams('com_socialads');
$selected_gateways = $sa_params->get("gateways");

// Getting GETWAYS
$dispatcher = JEventDispatcher::getInstance();
JPluginHelper::importPlugin('payment');

if (!is_array($selected_gateways))
{
	$gateway_param[] = $selected_gateways;
}
else
{
	$gateway_param = $selected_gateways;
}

if (!empty($gateway_param))
{
	$gateways = $dispatcher->trigger('onTP_GetInfo',array($gateway_param));
}

// getting payment list END

// get SELECTED paymen plugin html
$selectedGateway = !empty($this->adDetail['processor']) ? $this->adDetail['processor'] :'';

if (empty($sa_displayblocks))
	$sa_displayblocks = array('invoiceDetail' => 1, 'billingDetail' => 1, 'adsDetail' => 1);
?>
<script>
	Joomla.submitbutton = function(action)
	{
		if(action === 'printOrder')
		{
			var restorepage = document.body.innerHTML;
			var printcontent = document.getElementById('saOrderDetails').innerHTML;
			document.body.innerHTML = printcontent;
			window.print();
			document.body.innerHTML = restorepage;
			return false;
		}

		Joomla.submitform(action )

	}
</script>
<div class="<?php echo SA_WRAPPER_CLASS;?> sa-ad-details" id = "saOrderDetails">
	<!-- Order detail block-->
	<div class="row-fluid">
		<h4><?php echo JText::_('COM_SOCIALADS_INVOICE_ORDER_DETAIL');?></h4><hr>
		<div class="span6">
			<?php
			if ($sa_displayblocks['invoiceDetail']==1)
			{ ?>
				<div id='no-more-tables'>
					<table class="table table-condensed table-bordered">
							<tbody>
								<tr>
									<td class="hidden-phone"><?php echo JText::_('COM_SOCIALADS_INVOICE_ID');?></td>
									<td data-title="<?php echo JText::_('COM_SOCIALADS_INVOICE_ID');?>">
									<?php echo (!empty($this->adDetail['prefix_oid'])?$this->adDetail['prefix_oid']:$this->order_id); ?>
									</td>
								</tr>
								<tr>
									<td class="hidden-phone"><?php echo JText::_('COM_SOCIALADS_INVOICE_DATE');?></td>
									<td data-title="<?php echo JText::_('COM_SOCIALADS_INVOICE_DATE');?>"><?php echo $this->adDetail['ad_modified_date']?></td>
								</tr>
								<?php
								if(!empty($this->adDetail['vat_number']))
								{ ?>
									<tr>
										<td class="hidden-phone"><?php echo JText::_('COM_SOCIALADS_BILLING_VAT_NUM');?></td>
										<td data-title="<?php echo JText::_('COM_SOCIALADS_BILLING_VAT_NUM');?>"><?php echo $this->adDetail['vat_number'];?></td>
									</tr>
								<?php
								} ?>
								<tr>
									<td class="hidden-phone"><?php echo JText::_('COM_SOCIALADS_INVOICE_AMOUNT');?></td>
									<td data-title="<?php echo JText::_('COM_SOCIALADS_INVOICE_AMOUNT');?>"><?php echo SaCommonHelper::getFormattedPrice($this->adDetail['amount']);?></td>
								</tr>
								<!--
								<tr>
									<td><?php //echo JText::_('COM_SOCIALADS_INVOICE_EMAIL');?></td>
									<td><?php //echo $orderDetails->zipcode;?></td>
								</tr> -->
								<tr>
									<td class="hidden-phone"><?php echo JText::_('COM_SOCIALADS_INVOICE_STATUS');?></td>
									<td data-title="<?php echo JText::_('COM_SOCIALADS_INVOICE_STATUS');?>">
										<?php
										if ($this->adDetail['status'] == 'P')
											echo JText::_('COM_SOCIALADS_AD_PENDING');
										if ($this->adDetail['status'] == 'C')
											echo JText::_('COM_SOCIALADS_AD_CONFIRM');
										if ($this->adDetail['status'] == 'RF')
											echo JText::_('COM_SOCIALADS_AD_REFUND');
										if ($this->adDetail['status'] == 'E')
											echo JText::_('COM_SOCIALADS_ADORDERS_AD_CANCEL');
										 ?>
									</td>
								</tr>
								<tr>
									<td class="hidden-phone"><?php echo JText::_('COM_SOCIALADS_INVOICE_PREPROCESSOR');?></td>
									<td data-title="<?php echo JText::_('COM_SOCIALADS_INVOICE_PREPROCESSOR');?>">
										<?php
										$gtway = !empty($this->adDetail['processor']) ? $this->adDetail['processor']:'' ;

										if (!empty($gtway))
										{
											$plugin = JPluginHelper::getPlugin('payment',$gtway);

											if (is_object($plugin))
											{
												$pluginParams = json_decode($plugin->params);
												echo $pluginParams->plugin_name;
											}
											else
											{
												echo $gtway;
											}
										}
										else
										{
											echo JText::_('COM_SOCIALADS_ADORDERS_NO_PAYMENT_GATEWAY_SELECTED');
										}?>
									</td>
								</tr>
								<?php
								if (!empty($this->userInformation['vat_number']))
								{ ?>
									<tr>
										<td><?php echo JText::_('COM_SOCIALADS_BILLING_VAT_NUM');?></td>
										<td><?php echo $this->userInformation['vat_number'];?></td>
									</tr>
								<?php
								} ?>
						</table>
					</div>
			<?php
			} ///1st table End?>
		</div>
		<div class="span6">
			<?php
			if (!empty($this->userInformation))
			{?>
				<div id='no-more-tables'>
					<table class="table table-condensed table-bordered">
						<thead>
							<tr>
								<th>
									<?php echo JText::_('COM_SOCIALADS_INVOICE_CUSTOMER_DETAILS');?>
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td data-title="<?php echo JText::_('COM_SOCIALADS_INVOICE_CUSTOMER_DETAILS');?>" class="qtcWordWrap">
									<address>
										<strong>
											 <?php 	echo $this->userInformation['firstname'] . ' ' . $this->userInformation['lastname']; ?> &nbsp;&nbsp;
										</strong><br />
											<?php echo $this->userInformation['address']; ?>
										<br/>

										<?php	echo $this->userInformation['city'] . ', ' ;
											echo (!empty($this->userInformation['state_code']) ? $this->userInformation['state_code'] : $this->userInformation['state_code']) . ' ' . $this->userInformation['zipcode'];
											echo '<br/>';
											echo (!empty($this->userInformation['country_name']) ? $this->userInformation['country_name'] : $this->userInformation['country_code']) . ', ';

										?>
										<br/>
										<?php echo $this->userInformation['user_email']; ?>
										<br/>
										 <abbr title="<?php echo JText::_('COM_SOCIALADS_BILLIN_PHONE');?>"><?php echo JText::_('COM_SOCIALADS_BILLIN_PHONE');?> :</abbr> <?php	echo $this->userInformation['phone']; ?>
									</address>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			<?php
			} ?>
		</div>
	</div>
	<?php
	if ($sa_displayblocks['adsDetail'] == 1)
	{ ?>
		<div class="row-fluid">
			<h4><?php echo JText::_('COM_SOCIALADS_INVOICE_DETAILS');?></h4><hr>
			<div class="span12">
				<div id="no-more-tables">
					<table class="table table-condensed table-bordered">
						<tr class="hidden-phone">
							<th class="cartitem_num" width="5%" align="right" style="text-align: left;" ><?php echo JText::_('COM_SOCIALADS_INVOICE_AD_TITLE'); ?></th>
							<th class="cartitem_num" width="5%" align="right" style="text-align: left;" ><?php echo JText::_('COM_SOCIALADS_INVOICE_ADMODE_KEY'); ?></th>
							<?php
							$ad_chargeOpKey = JText::_('COM_SOCIALADS_INVOICE_PRICE_DAY');

							if ($this->chargeoption < 2)
							{
								$ad_chargeOpKey = JText::_('COM_SOCIALADS_INVOICE_NUMBER_CLICKS');
								// No of clicks or impression
							}?>
							<th class="cartitem_num" width="5%" align="right" style="text-align: left;" ><?php echo $ad_chargeOpKey; ?></th>
							<?php
							if (!isset($this->ad_points))
							{?>
								<th class="cartitem_num" width="5%" align="right" style="text-align: left;" ><?php echo JText::_('COM_SOCIALADS_INVOICE_TOTAL_AMT'); ?></th>
							<?php
							}
							if (isset($this->ad_points))
							{ ?>
								<th class="cartitem_num" width="5%" align="right" style="text-align: left;" ><?php echo JText::_('COM_SOCIALADS_INVOICE_POINTS');?></th>
							<?php
							} ?>
						</tr>
						<tr>
							<td data-title="<?php echo JText::_('COM_SOCIALADS_INVOICE_AD_TITLE');?>"><?php echo $this->adDetail['ad_title']; ?></td>
							<?php
							$adMoreTr = 0;
							$td_value = '';

							// Click , impression or date
							if ($this->chargeoption	== 0)
							{
								$td_value = JText::_('COM_SOCIALADS_INVOICE_ADMODE_IMP_VALUE');
							}
							elseif ($this->chargeoption==1)
							{
								$td_value = JText::_('COM_SOCIALADS_INVOICE_ADMODE_CLK_VALUE');
							}
							elseif ($this->chargeoption >= 2)
							{
								if ($this->chargeoption == 2)
								{
									$td_value = JText::_('COM_SOCIALADS_INVOICE_ADMODE_DATE_VALUE');
								}
								else if($this->chargeoption > 2)
								{
									$td_value = JText::_('COM_SOCIALADS_INVOICE_ADMODE_SLAB_VALUE');

								// @TODO we have to supprt recurring payment ( skipped for now) ( Ask DJ for how to do)
								/*
								if($this->adDetail['sa_recuring'] != '1')
								{
									$adMoreTr = 1;
								}
								*/
								}
							}?>
							<td data-title="<?php echo JText::_('COM_SOCIALADS_INVOICE_ADMODE_KEY');?>"><?php echo $td_value; ?></td>
							<?php
							$ad_chargeOpValue = $this->adDetail['ad_credits_qty'];

							if ($this->chargeoption < 2)
							{
								$ad_chargeOpValue =  $this->ad_totaldisplay;
							} ?>
							<td data-title="<?php echo JText::_('COM_SOCIALADS_INVOICE_NUM_IMP_OR_CLICKS');?>"><?php echo $ad_chargeOpValue; ?></td>

							<td data-title="<?php echo JText::_('COM_SOCIALADS_INVOICE_TOTAL_AMT');?>"><?php echo SaCommonHelper::getFormattedPrice($this->adDetail['original_amount']);?></td>
						<tr>
							<td class="hidden-phone" colspan="4">&nbsp;</td>
						</tr>
						<tr>
							<td class="hidden-phone" colspan="2"></td>
							<?php
							if (!isset($this->ad_points))
							{?>
								<td class="hidden-phone"class="cartitem_num" width="5%" align="right" style="text-align: left;" ><strong><?php echo JText::_('COM_SOCIALADS_INVOICE_TOTAL_AMT'); ?></strong></td>
								<?php $ad_amount = SaCommonHelper::getFormattedPrice($this->adDetail['original_amount']);?>
								<td class="hidden-phone"><?php echo $ad_amount; ?></td>
							<?php
							}
							if (isset($this->ad_points))
							{ ?>
								<td class="cartitem_num hidden-phone" width="5%" align="right" style="text-align: left;" ><?php echo JText::_('COM_SOCIALADS_INVOICE_POINTS');?></td>
								<td class="hidden-phone"><?php echo $this->adDetail['original_amount']; ?></td> <!--@TODO: Check for points-->
							<?php
							} ?>
						</tr>
						<tr>
							<td class="hidden-phone" colspan="2"></td>
							<td class="cartitem_num hidden-phone" width="5%" align="right" style="text-align: left;" ><strong><?php echo JText::_('COM_SOCIALADS_INVOICE_DIS_COP'); ?></strong></td>
							<?php $cop_dis = 0;

								if (!empty($this->adDetail['coupon']))
								{
									// Get payment HTML
									$adcop = $this->socialadsPaymentHelper->getcoupon($this->adDetail['coupon']);

									if ($adcop)
									{
										if ($adcop[0]->val_type == 1) 		// Discount rate
										{
											$cop_dis = ($adcop[0]->value/100) * $this->adDetail['original_amount'];
										}
										else
											$cop_dis = $adcop[0]->value;
									}
									else
									{
										$cop_dis = 0;
									}
								}

								$discountAmt = 450;

								if ($cop_dis < $this->adDetail['original_amount'])
								{
									$discountedPrice = $this->adDetail['original_amount'] - $cop_dis + $this->adDetail['tax'];
								}
								else
								{
									$discountedPrice = 0;
								}?>
							<td data-title="<?php echo JText::_('COM_SOCIALADS_INVOICE_DIS_COP');?>" class=""><?php echo SaCommonHelper::getFormattedPrice($cop_dis);?></td>
						</tr>
						<tr>
							<td class="hidden-phone" colspan="2"></td>
							<td class="cartitem_num hidden-phone" width="5%" align="right" style="text-align: left;" ><strong><?php echo JText::_('COM_SOCIALADS_INVOICE_TAX_AMT'); ?></strong></td>
							<td data-title="<?php echo JText::_('COM_SOCIALADS_INVOICE_TAX_AMT');?>" class=""><?php echo SaCommonHelper::getFormattedPrice($this->adDetail['tax']);?></td>
						</tr>
						<tr>
							<td class="hidden-phone" colspan="2"></td>
							<td class="cartitem_num hidden-phone" width="5%" align="right" style="text-align: left;"><strong><?php echo JText::_('COM_SOCIALADS_INVOICE_NET_AMT_PAY'); ?></strong></td>
							<?php
							if (!isset($this->ad_points))
							{
								$ad_amount =  SaCommonHelper::getFormattedPrice($discountedPrice);?>
								<td data-title="<?php echo JText::_('COM_SOCIALADS_INVOICE_NET_AMT_PAY');?>" class=""><strong><?php echo $ad_amount; ?></strong></td>
							<?php
							}
							if (isset($this->ad_points))
							{?>
								<td data-title="<?php echo JText::_('COM_SOCIALADS_INVOICE_NET_AMT_PAY');?>" class=""><strong><?php echo $this->adDetail['original_amount']; ?></strong></td>
								<?php	// $makecal='makepayment();';
							}?>
						</tr>
					</table>
				</div>
			</div>
		</div>
	<?php
	}?>
</div>
