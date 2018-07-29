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
if(empty($order_id))
{
	?>
	<div class="well" >
		<div class="alert alert-error">
			<span ><?php echo JText::_('SA_UNABLE_TO_TRACK_ORDER_ID'); ?> </span>
		</div>
	</div>
	<?php
	return false;
}
$sa_params = JComponentHelper::getParams('com_socialads');
$socialadsPaymentHelper = new SocialadsPaymentHelper();
$adDetail = $socialadsPaymentHelper->getOrderAndAdDetail($order_id);

if ($sa_params->get('payment_mode') == 'pay_per_ad_mode')
{
	$this->chargeoption = $adDetail['ad_payment_type'];
	$this->ad_totaldisplay = $adDetail['ad_credits_qty'];  // no of clicks or impression
}

$gatwayName = 'bycheck';
$plugin = JPluginHelper::getPlugin( 'payment',$gatwayName);

if (0 && $sa_params->get('pricing_options') == 0)
{
	$pluginParams = json_decode( $plugin->params );
	$this->assignRef( 'ad_gateway', $pluginParams->plugin_name);
	$arb_enforce = '';
	$this->assignRef( 'arb_enforce', $pluginParams->arb_enforce);
	$arb_enforce = '';
	$this->assignRef( 'arb_support', $pluginParams->arb_support);
	$points1=0;

	if (isset($pluginParams->points))
	{
		if ($pluginParams->points=='point')
		{
			$points1 = 1;
			$this->assignRef('ad_points', $points1);
			$this->assignRef('ad_jconver', $pluginParams->conversion);
		}
	}
}

// If ends
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

$this->ad_gateways = $gateways;
// getting payment list END


// get SELECTED paymen plugin html
$selectedGateway = !empty($adDetail['processor']) ? $adDetail['processor'] :(!empty($this->ad_gateways) ? $this->ad_gateways[0]->id : '');

if (empty($sa_displayblocks))
	$sa_displayblocks = array('invoiceDetail' => 1, 'billingDetail' => 1, 'adsDetail' => 1);
?>

<div class="<?php echo SA_WRAPPER_CLASS;?> sa-ad-invoice">
<?php echo ""?>
	<!-- Order detail block-->
	<?php
	if ($sa_displayblocks['invoiceDetail']==1)
	{
	?>
	<div class=" row-fluid" style="width: 80%;">
		<h4><?php echo JText::_('COM_SOCIALADS_INVOICE_ORDER_DETAIL');?></h4><hr>
		<div class="span6">
			<div id='no-more-tables'>
				<table class="table table-condensed table-bordered">
					<tr>
						<td><?php echo JText::_('COM_SOCIALADS_INVOICE_ID');?></td>
						<td><?php echo (!empty($adDetail['prefix_oid'])?$adDetail['prefix_oid']:$order_id); ?></td>
					</tr>
					<tr>
						<td><?php echo JText::_('COM_SOCIALADS_INVOICE_DATE');?></td>
						<td><?php echo $orderDetails->mdate;?></td>
					</tr>
					<?php
						if(!empty($billinfo->vat_number))
						{
					?>
					<tr>
						<td><?php echo JText::_('COM_SOCIALADS_BILLIN_VAT_NUM');?></td>
						<td><?php echo $orderDetails->vat_number;?></td>
					</tr>
					<?php } ?>
					<tr>
						<td><?php echo JText::_('COM_SOCIALADS_INVOICE_AMOUNT');?></td>
						<td><?php echo $orderDetails->amount;?></td>
					</tr>
					<!--
					<tr>
						<td><?php //echo JText::_('COM_SOCIALADS_INVOICE_EMAIL');?></td>
						<td><?php //echo $orderDetails->zipcode;?></td>
					</tr> -->
					<tr>
						<td><?php echo JText::_('COM_SOCIALADS_INVOICE_STATUS');?></td>
						<td><?php echo $orderstatus;?></td>
					</tr>
					<tr>
						<td><?php echo JText::_('COM_SOCIALADS_INVOICE_PREPROCESSOR');?></td>
						<td>
							<?php
							if ($orderDetails->amount)
							{
								$gtway= !empty($adDetail['processor']) ? $adDetail['processor']:'' ;
								$plugin = JPluginHelper::getPlugin('payment',$gtway);
								$pluginParams = json_decode($plugin->params);
							}

							echo !empty ($pluginParams->plugin_name) ? $pluginParams->plugin_name : '-';
							?>
						</td>
					</tr>
				</table>
			</div>
		</div>
	<?php
	} ?>
		<div class="span6">
			<?php
			if ($sa_displayblocks['billingDetail'] == 1)
			{?>
				<div id='no-more-tables'>
					<table class="table table-condensed table-bordered">
						<tr>
							<td><?php echo JText::_('COM_SOCIALADS_BILLIN_FNAM');?></td>
							<td><?php echo $billinfo->firstname;?></td>
						</tr>
						<tr>
							<td><?php echo JText::_('COM_SOCIALADS_BILLIN_LNAM');?></td>
							<td><?php echo $billinfo->lastname;?></td>
						</tr>
						<?php
							if (!empty($billinfo->vat_number))
							{
						?>
						<tr>
							<td><?php echo JText::_('COM_SOCIALADS_BILLIN_VAT_NUM');?></td>
							<td><?php echo $billinfo->vat_number;?></td>
						</tr>
						<?php } ?>
						<tr>
							<td><?php echo JText::_('COM_SOCIALADS_BILLIN_ADDR');?></td>
							<td><?php echo $billinfo->address;?></td>
						</tr>
						<tr>
							<td><?php echo JText::_('COM_SOCIALADS_BILLIN_ZIP');?></td>
							<td><?php echo $billinfo->zipcode;?></td>
						</tr>
						<tr>
							<td><?php echo JText::_('COM_SOCIALADS_BILLIN_COUNTRY');?></td>
							<td><?php echo $billinfo->country_code;?></td>
						</tr>
						<tr>
							<td><?php echo JText::_('COM_SOCIALADS_BILLIN_STATE');?></td>
							<td><?php echo $billinfo->state_code;?></td>
						</tr>
						<tr>
							<td><?php echo JText::_('COM_SOCIALADS_BILLIN_CITY');?></td>
							<td><?php echo $billinfo->city;?></td>
						</tr>
						<tr>
							<td><?php echo JText::_('COM_SOCIALADS_BILLIN_PHONE');?></td>
							<td><?php echo $billinfo->phone;?></td>
						</tr>
						<tr>
							<td><?php echo JText::_('COM_SOCIALADS_BILLIN_EMAIL');?></td>
							<td><?php echo $billinfo->user_email;?></td>
						</tr>
					</table>
				</div>
			<?php
			} ?>
		</div>
	</div>
	<?php
	if ($sa_displayblocks['adsDetail'] == 1)
	{ ?>
		<!--ad detaiL start -->
		<div class="row-fluid">
			<h4><?php echo JText::_('COM_SOCIALADS_INVOICE_DETAILS');?></h4><hr>
			<div class="span12">
				<div id='no-more-tables'>
					<table class="table table-condensed table-bordered">
						<tr>
							<?php
							$adMoreTr = 0;
							$td_key = '';
							$td_value = '';

							// click , impression or date
							if ($this->chargeoption	== 0)
							{
								$td_key = JText::_('COM_SOCIALADS_INVOICE_ADMODE_KEY');
								$td_value = JText::_('COM_SOCIALADS_ADMODE_IMP_TXT');
							}
							elseif ($this->chargeoption==1)
							{
								$td_key = JText::_('COM_SOCIALADS_INVOICE_ADMODE_KEY');
								$td_value = JText::_('COM_SOCIALADS_INVOICE_ADMODE_CLK_VALUE');
							}
							elseif ($this->chargeoption >= 2)
							{
								if ($this->chargeoption == 2)
								{
									$td_key = JText::_('COM_SOCIALADS_INVOICE_ADMODE_KEY');
									$td_value = JText::_('COM_SOCIALADS_ADMODE_DATE_TXT');
								}
								else if($this->chargeoption > 2)
								{
									$td_key = JText::_('COM_SOCIALADS_INVOICE_ADMODE_KEY');
									$td_value = $slablabel;

									// @TODO we have to supprt recurring payment ( skipped for now) ( Ask DJ for how to do)
									/*
									if($adDetail['sa_recuring'] != '1')
									{
										$adMoreTr = 1;
									}
									*/
								}

							} ?>
							<td class="" width="30%"><?php echo $td_key; ?></td>
							<td class="" width="30%"><?php echo $td_value; ?></td>
						</tr>
						<tr>
							<?php
							$ad_chargeOpKey = JText::_('COM_SOCIALADS_INVOICE_ADMODE_DATE_VALUE');
							$ad_chargeOpValue =  $this->ad_totaldisplay;

							if ($this->chargeoption < 2)
							{
								$ad_chargeOpKey = JText::_('COM_SOCIALADS_INVOICE_NUM_IMP_OR_CLICKS');
								$ad_chargeOpValue =  $this->ad_totaldisplay;

								// No of clicks or impression

							} ?>
							<td class=""><?php echo $ad_chargeOpKey; ?></td>
							<td class=""><?php echo $ad_chargeOpValue; ?></td>
						</tr>
						<tr>
						<?php
						// Jomsocial points
						if (!isset($this->ad_points))
						{?>
							<?php
							$ad_chargeOpKey = JText::_('COM_SOCIALADS_INVOICE_TOTAL_AMT');
							$ad_chargeOpValue =  $adDetail['original_amount'] .' ' . $sa_params->get('currency');

							?>
							<td class=""><?php echo $ad_chargeOpKey; ?></td>
							<td class=""><?php echo $ad_chargeOpValue; ?></td>
						</tr>
						<?php
						} ?>

						<?php
						if (isset($this->ad_points))
						{ ?>
						<tr>
							<td class=""><?php echo JText::_('COM_SOCIALADS_INVOICE_POINTS');; ?></td>
							<td class=""><?php echo $adDetail['original_amount']; ?></td>
						</tr>
						<?php $makecal='makepayment();';
						}?>

						<?php
						$cop_dis = 0;

						if (!empty($adDetail['coupon']))
						{
							// Get payment HTML
							$adcop = $socialadsPaymentHelper->getcoupon($adDetail['coupon']);

							if ($adcop)
							{
								if ($adcop[0]->val_type == 1) 		// Discount rate
								{
									$cop_dis = ($adcop[0]->value/100) * $adDetail['original_amount'];
								}
								else
									$cop_dis = $adcop[0]->value;
							}
							else
							{
								$cop_dis = 0;
							}
						}

						if ($cop_dis < $adDetail['original_amount'])
						{
							$discountedPrice = $adDetail['original_amount'] - $cop_dis + $adDetail['tax'];
						}
						else
						{
							$discountedPrice = 0;
						} ?>

						<!-- coupon discount display:block-->
						<tr id= "" style="">
							<td class=""><?php echo JText::_('COM_SOCIALADS_INVOICE_DIS_COP'); ?></td>
							<td class=""><?php echo  $cop_dis; ?>&nbsp;<?php echo $sa_params->get('currency'); ?></td>
						</tr>

						<!-- tax amount -->
						<tr id= "ad_tax" style="">
							<td class=""><?php echo JText::sprintf('COM_SOCIALADS_TAX_AMT');//,$tax[0]); ?></td>
							<td class=""><?php echo  $adDetail['tax']	; ?>&nbsp;<?php echo $sa_params->get('currency'); ?></td>
						</tr>

						<!-- NET TOTAL AMOUNT after tax and coupon-->
						<tr id= "">
							<td class=""><?php echo JText::_('COM_SOCIALADS_INVOICE_NET_AMT_PAY'); ?></td>
							<td class=""><?php echo $discountedPrice;?>&nbsp;<?php echo $sa_params->get('currency');?>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<!-- ad detail end -->
	<?php
	} ?>
</div>
