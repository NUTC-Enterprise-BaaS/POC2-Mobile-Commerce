<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.formvalidation');
JHtmlBehavior::framework();
require_once JPATH_ROOT . '/components/com_socialads/helpers/engine.php';

// Fetch ad detail
if (empty($displayData->order_id))
{ ?>
	<div>
		<div class="alert alert-error">
			<span ><?php echo JText::_('COM_SOCIALADS_AD_UNABLE_TO_TRACK_ORDER_ID'); ?> </span>
		</div>
	</div>
	<?php
	return false;
}

$socialadsPaymentHelper = new SocialadsPaymentHelper();
$adDetail = $socialadsPaymentHelper->getOrderAndAdDetail($displayData->order_id,1);

$this->chargeoption = $adDetail['ad_payment_type'];

// No of clicks or impression
$this->ad_totaldisplay = $adDetail['ad_credits_qty'];

// VM:: hv to add and code for jomsical points ( we are looking later for jomscial points)
$gatwayName = 'bycheck';
$plugin = JPluginHelper::getPlugin( 'payment',$gatwayName);
$paymentMode = $displayData->sa_params->get('payment_mode');

if (0 && $paymentMode == 'pay_per_ad_mode')
{
	$pluginParams = json_decode( $plugin->params );
	$this->assignRef('ad_gateway', $pluginParams->plugin_name);
	$arb_enforce = '';
	$this->assignRef('arb_enforce', $pluginParams->arb_enforce);
	$arb_enforce = '';
	$this->assignRef('arb_support', $pluginParams->arb_support);
	$points1 = 0;

	if (isset($pluginParams->points))
	{
		if ($pluginParams->points == 'point')
		{
			$points1 = 1;

			// $points1=$this->get('JomSocialPoints');
			$this->assignRef('ad_points', $points1);
			$this->assignRef('ad_jconver', $pluginParams->conversion);
		}
	}
}
// If ends

// Get ad preview
$this->preview = SaAdEngineHelper::getAdHtml($adDetail['ad_id'], 1);

// Getting selected payment gateway list form component config
$selected_gateways = (array) $displayData->sa_params->get('gateways', 'paypal', 'STRING');

// Getting GETWAYS
$dispatcher = JDispatcher::getInstance();
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
	$gateways = $dispatcher->trigger('onTP_GetInfo', array($gateway_param));
}

$this->ad_gateways = $gateways;
// Getting payment list END
?>

<!--techjoomla-bootstrap -->
<div class="techjoomla-bootstrap ad_reviewAdmainContainer" >
	<fieldset class="sa-fieldset">
		<legend class="hidden-desktop">
			<?php echo JText::_('COM_SOCIALADS_CKOUT_ADS_SUMMERY'); ?>
		</legend>
		<!-- For ad detail and preview -->
		<div class="row-fluid">
			<!--ad details start -->
			<div class="span6">
				<h4>
					<?php echo JText::_('COM_SOCIALADS_AD_PAYMENT_REVIEW');?>
				</h4>
				<div class = "clearfix">&nbsp;</div>
				<div class="table-responsive">
					<table class=" table table-bordered">
						<tr>
							<?php
							$td_value = '';

							if ($this->chargeoption == 0)
							{
								$td_value = JText::_('COM_SOCIALADS_ADMODE_IMP_TXT');
							}
							elseif ($this->chargeoption == 1)
							{
								$td_value = JText::_('COM_SOCIALADS_ADMODE_CLK_TXT');
							}
							elseif ($this->chargeoption == 2)
							{
								$td_value = JText::_('COM_SOCIALADS_ADMODE_DAY_TXT');
							}
							elseif ($this->chargeoption > 2)
							{
								$td_value = JText::_('COM_SOCIALADS_ADMODE_SLAB_TXT');
							} ?>
							<td class="" width="30%">
								<?php echo JText::_('COM_SOCIALADS_ADMODE_DEFAULT_KEY');; ?>
							</td>
							<td class="" width="30%">
								<?php echo $td_value; ?>
							</td>
						</tr>

						<?php
						if ($this->chargeoption < 2)
						{
							$ad_chargeOpKey =  ($this->chargeoption == 1) ? JText::_('COM_SOCIALADS_NUMBER_CLICKS') : JText::_('COM_SOCIALADS_NUMBER_IMPRESSIONS');

							// No of clicks or impression
							$ad_chargeOpValue =  $this->ad_totaldisplay;
							?>
							<tr>
								<td class=""><?php echo $ad_chargeOpKey; ?></td>
								<td class=""><?php echo $ad_chargeOpValue; ?></td>
							</tr>
						<?php
						}

						// If days then show day count
						elseif ($this->chargeoption == 2)
						{
							$ad_dayOpKey   = JText::_('COM_SOCIALADS_NUMBER_DAYS');

							// No of days
							$ad_dayOpValue =  $this->ad_totaldisplay; ?>
							<tr>
								<td class=""><?php echo $ad_dayOpKey; ?></td>
								<td class=""><?php echo $ad_dayOpValue; ?></td>
							</tr>
						<?php
						}
						else
						{
							$slabDetails = $socialadsPaymentHelper->getSlabDetails($this->chargeoption);
							?>
							<tr>
								<td class="">
									<?php echo JText::sprintf('COM_SOCIALADS_ADMODE_DEFAULT_SLAB_KEY', $slabDetails['label']); ?>
								</td>
								<td class="">
									<?php echo SaCommonHelper::getFormattedPrice($slabDetails['price']); ?>
								</td>
							</tr>
						<?php
						}

						// Jomsocial points
						if (!isset($this->ad_points))
						{ ?>
							<tr>
								<?php
								$ad_chargeOpKey   = JText::_('COM_SOCIALADS_TOTAL_AMT');
								$ad_chargeOpValue =  SaCommonHelper::getFormattedPrice($adDetail['original_amount']);
								?>
								<td class=""><?php echo $ad_chargeOpKey; ?></td>
								<td class=""><?php echo $ad_chargeOpValue; ?></td>
							</tr>
						<?php
						}

						if (isset($this->ad_points))
						{ ?>
							<tr>
								<td class=""><?php echo JText::_('POINTS');; ?></td>
								<td class=""><?php echo $adDetail['original_amount']; ?></td>
							</tr>
							<?php
							// @TODO - check if this is needed
							$makecal='makepayment();';
						}

						$cop_dis = 0;

						if (!empty($adDetail['coupon']))
						{
							// Get payment HTML
							$adcop = $socialadsPaymentHelper->getcoupon($adDetail['coupon']);

							if ($adcop)
							{
								// Discount rate
								if ($adcop[0]->val_type == 1)
								{
									$cop_dis = ($adcop[0]->value/100) * $adDetail['original_amount'];
								}
								else
								{
									$cop_dis = $adcop[0]->value;
								}
							}
							else
							{
								$cop_dis = 0;
							}
						}

						$discountedPrice = $adDetail['original_amount'] - $cop_dis; ?>

						<!-- Coupon discount display:block-->
						<tr id= "dis_cop">
							<td class=""><?php echo JText::_('COM_SOCIALADS_DIS_COP'); ?></td>
							<td class=""><?php echo  SaCommonHelper::getFormattedPrice($cop_dis); ?></td>
						</tr>

						<!-- Tax amount -->
						<tr id= "ad_tax" style="">
							<td class=""><?php echo JText::sprintf('COM_SOCIALADS_TAX_AMT', $tax[0]); ?></td>
							<td class=""><?php echo SaCommonHelper::getFormattedPrice($adDetail['tax']); ?></td>
						</tr>

						<!-- NET TOTAL AMOUNT after tax and coupon-->
						<tr id= "dis_amt">
							<td class=""><?php echo JText::_('COM_SOCIALADS_NET_AMT_PAY'); ?></td>
							<td class=""><?php echo SaCommonHelper::getFormattedPrice($adDetail['amount']); ?>
							</td>
						</tr>
					</table>
				</div>
				<!-- Table-responsive -->
			</div>
			<!-- ad detail end -->

			<div class="span6">
				<h4>
					<?php echo JText::_('COM_SOCIALADS_AD_LOOK');?>
				</h4>
				<div class = "clearfix">&nbsp;</div>
				<?php echo $this->preview; ?>
			</div>
		</div>
		<hr>
		<!-- show payment option start -->
		<div class="row-fluid">
			<div class="paymentHTMLWrapper">
				<?php
				$paymentListStyle = '' ;
				$mainframe = JFactory::getApplication();
				$termsAnsCondition = $displayData->sa_params->get('terms_conditions_payment');
				$articleTerms = $displayData->sa_params->get('articleid_terms');

				if (!$mainframe->isAdmin() && $termsAnsCondition == 1 && !empty($articleTerms))
				{
					$paymentListStyle = 'display:none' ;
					?>
					<!-- TERMS AND CONDITION -->
					<div class="control-group">
						<input class="inputbox sa_terms_checkbox_style" type="checkbox" name="sa_accpt_terms" id="sa_termsCondCk" size="30" aria-invalid="false" onclick="sa.create.paymentListShowHide()" >&nbsp;&nbsp;<?php  echo JText::_('COM_SOCIALADS_ACCEPT'); ?>
						<?php
						// $termslink = "'".JRoute::_('index.php?option=com_content&tmpl=component&view=article&id='.$displayData->sa_params->get('articleid_terms'))."','_blank'";
						// onclick="window.open(<?php echo $termslink;
						?>
							<a href="<?php echo JUri::root().'index.php?option=com_content&tmpl=component&view=article&id='.$displayData->sa_params->get('articleid_terms') ; ?>" class="" target="_blank">
								<span class="hasTip" title="<?php echo JText::_( 'COM_SOCIALADS_TERMS_CONDITION' ); ?>">
									<?php  echo JText::_( 'COM_SOCIALADS_TERMS_CONDITION' ); ?>
								</span>
							</a>
					</div>
					<?php
				}

				if (!empty($adDetail['amount']))
				{ ?>
					<div class="" id="sa_paymentlistWrapper" style="<?php echo $paymentListStyle; ?>">
						<div class="control-group " id="sa_paymentGatewayList">
							<?php
							$default = "";
							$lable   = JText::_('COM_SOCIALADS_AD_SEL_GATEWAY');
							$gateway_div_style = 1;

							// If only one geteway then keep it as selected
							if (!empty($this->ad_gateways))
							{
								// Id and value is same
								$default = $this->ad_gateways[0]->id;
							}

							// If only one geteway then keep it as selected
							if (!empty($this->ad_gateways) && count($this->ad_gateways) == 1)
							{
								// Id and value is same
								$default = $this->ad_gateways[0]->id;
								$lable = JText::_( 'COM_SOCIALADS_AD_SEL_GATEWAY' );

								// To show payment radio btn even if only one payment gateway
								$gateway_div_style = 1;
							} ?>

							<label for="" class="control-label">
								<h4><?php echo $lable; ?></h4>
							</label>
							<div class="controls" style="<?php echo ($gateway_div_style==1)?"" : "display:none;" ?>">
								<?php
								if (empty($this->ad_gateways))
								{
									echo JText::_( 'COM_SOCIALADS_AD_SELECT_PAYMENT_GATEWAY' );
								}
								else
								{
									// Removed selected gateway Bug #26993
									$default = '';
									$imgpath = JUri::root() . "media/com_sa/images/ajax.gif";
									$ad_fun = "onchange=\"sa.create.getPaymentGatewayHtml(this.value," . $displayData->order_id . ",'" . trim($displayData->sa_params->get('payment_mode', 'pay_per_ad_mode')) . "', '" . JText::_('COM_SOCIALADS_PAYMENT_GATEWAY_LOADING_MSG') . "', '" . $imgpath . "');\"";
									$pg_list = JHtml::_('select.radiolist', $this->ad_gateways, 'ad_gateways', 'class="inputbox required" ' . $ad_fun . ' ', 'id', 'name', $default, false);
									echo $pg_list;
								} ?>
							</div>

							<?php
							if (empty($gateway_div_style))
							{ ?>
								<div class="controls qtc_left_top">
									<?php
									// Id and value is same
									echo $this->ad_gateways[0]->name;
									?>
								</div>
							<?php
							}
							?>
						</div>
						<!-- END OF control-group-->

						<!-- show payment hmtl form-->
						<div id="sa_payHtmlDiv"></div>
				<?php
				}
				else
				{ ?>
					<div id="sa_payHtmlDiv">
						<form method="post" name="sa_freePlaceOrder" class="" id="sa_freePlaceOrder">
							<input type="hidden" name="option" value="com_sa">
							<input type="hidden" id="task" name="task" value="payment.sa_processFreeOrder">
							<input type="hidden" name="order_id" value="<?php echo $displayData->order_id; ?>">

							<div class="form-actions">
								<input type="submit" class="btn btn-success btn-large" value="<?php echo JText::_('COM_SOCIALADS_AD_CONFORM_ORDER'); ?>">
							</div>
						</form>
					</div>
				<?php
				}
				?>
				</div>

			</div>
			<!-- end of paymentHTMLWrapper-->
		</div>
		<!-- show payment option end -->
	</fieldset>
</div>
