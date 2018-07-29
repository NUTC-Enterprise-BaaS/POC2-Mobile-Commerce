<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access
defined('_JEXEC') or die;

// @TODO - start @manoj - find out why this is needed
$showimpclick = 0;
// @TODO end

$document = JFactory::getDocument();
JPluginHelper::importPlugin('payment');
$dispatcher = JEventDispatcher::getInstance();
$newvar = JPluginHelper::getPlugin('payment');
$selectbox = array();
$re_selectbox = array();
$selectbox_all = array();
$i = 0;
$payment_flag = 1;
$gatewaylabel = JText::_("SELECT");

if (empty($newvar))
{
	$selectbox[] = JHtml::_('select.option', '', JText::_('CHKGATEWAY'));
	$payment_flag = 0;
}
else
{
	$payment_flag = 1;
	$default_selected_gateway = 0;
	$gatewaySelected = (array) $displayData->sa_params->get('gateways', 'paypal', 'STRING');

	foreach ($newvar as $myparam)
	{
		if (!in_array($myparam->name, $gatewaySelected))
		{
			continue;
		}

		$plugin = JPluginHelper::getPlugin( 'payment',$myparam->name);
		$gateway_style = "";

		if (count($newvar) == 1)
		{
			$default_selected_gateway = 1;
			$gateway_style = "style=display:none";
			$gatewaylabel = JText::_("SELECT_GATEWY_DEFAULT");
		}

		$pluginParams = json_decode( $plugin->params );
		$selectbox[] = JHtml::_('select.option',$myparam->name, $pluginParams->plugin_name);

		if (empty($pluginParams->arb_support))
		{
			$re_selectbox[$i]['value'] = $myparam->name;
			$re_selectbox[$i++]['name'] =  $pluginParams->plugin_name;
		}
	}
}

$re_selectbox_json = json_encode($re_selectbox);

// Selectlist
$singleselect = array();
$slabs_json ='';
$pricingOptions = (array) $displayData->sa_params->get('pricing_options', 'perclick', 'STRING');
$saParams = $displayData->sa_params->get('slab_enable');
$slabPayPerDay = $displayData->sa_params->get('slab_pay_per_day');

foreach ($pricingOptions as $k => $v)
{
	if ($v == 'perimpression')
	{
		$singleselect[] = JHtml::_('select.option', '0', JText::_('COM_SOCIALADS_AD_CHARGE_PER_IMP'));
	}
	elseif ($v == 'perclick')
	{
		$singleselect[] = JHtml::_('select.option', '1', JText::_('COM_SOCIALADS_AD_CHARGE_PER_CLICK'));
	}
	elseif ($v=='perday')
	{
		if(empty($saParams))
		{
			$singleselect[] = JHtml::_('select.option', '2', JText::_('COM_SOCIALADS_AD_CHARGE_PER_DAY'));
		}
		else
		{
			if ($slabPayPerDay == 1)
				$singleselect[] = JHtml::_('select.option', '2', JText::_('COM_SOCIALADS_AD_CHARGE_PER_DAY'));

			$slabs_config = array();
			$j = 0;
			$configure_slab = $displayData->sa_params->get('configure_slab');

			for ($i = 0; $i < count($configure_slab); $i = $i + 3)
			{
				if (!empty($configure_slab[$i + 2]))
				{
					$slabs_config[$j]['label'] = $configure_slab[$i];
					$slabs_config[$j]['duration'] = $configure_slab[$i + 1];
					$slabs_config[$j]['price'] = $configure_slab[$i + 2];
					$j++;
					$singleselect[] = JHtml::_('select.option',$configure_slab[$i + 1], $configure_slab[$i]);
				}
			}

			$slabs_json = json_encode($slabs_config);
		}
	}
}

$buildadsession = JFactory::getSession();
$ad_chargeoption = $ad_totaldisplay = $ad_totalamount = $ad_gateway = $ad_currency = $ad_rate = $ad_daterangefrom =	$ad_daterangeto = $ad_totaldays = $ad_chargeoption_day = '';

if (!empty($displayData->pricingData->ad_credits_qty))
{
	$ad_totaldisplay =   $displayData->pricingData->ad_credits_qty;
}
if($showimpclick == 0)
{
	if (!empty($displayData->pricingData))
	{
		$ad_chargeoption =  $displayData->pricingData->ad_payment_type;
		$ad_daterangefrom = $displayData->pricingData->ad_startdate;
		$ad_totaldays = $displayData->pricingData->ad_credits_qty;
		$ad_totalamount = $displayData->pricingData->original_amount;
	}

	// $sa_recuring = $buildadsession->get('sa_recuring');

	// $ad_rate = $buildadsession->get('ad_rate');
}
elseif ($showimpclick == 1)
{
	$ad_chargeoption =  $buildadsession->get('ad_chargeoption');
	$ad_chargeoption_day = $buildadsession->get('ad_chargeoption_day');
}

$u_points = $buildadsession->get('user_points');
$SocialadsPaymentHelper = new SocialadsPaymentHelper;
$recurring_gateway = $SocialadsPaymentHelper->getRecurringGateways();

if (!$recurring_gateway)
{
	$recurring_gateway='';
}

if (!isset($u_points))
{
	$u_points = 0;
}

// Load the calendar behavior
// JHtml::_('behavior.calendar');
$articlelink= JRoute::_('index.php?option=com_content&tmpl=component&view=article&id=' . $displayData->sa_params->get('articleid_terms'));

// Bottom div starts here
$pricing_options = $displayData->sa_params->get('pricing_options'); ?>

<div id="bottomdiv" style="display:block;">
	<fieldset class="sa-fieldset">
		<div class="form-horizontal buildad_pricing_tab">
			<?php
			$publish1 = $publish2 = $publish1_label = $publish2_label = '';

			if ($displayData->special_access)
			{
				if (!empty($displayData->addata_for_adsumary_edit->ad_noexpiry))
				{
					if ($displayData->addata_for_adsumary_edit->ad_noexpiry)
					{
						$publish1 = 'checked';
						$publish1_label = ' btn-success ';
					}
					else
					{
						$publish2 = 'checked';
						$publish2_label = 'btn-danger';
					}
				}
				else
				{
					$publish2 = 'checked';
					$publish2_label = 'btn-danger';
				} ?>

				<div class="control-group">
					<div class="unlimited_adtext alert alert-info">
						<?php echo JText::_('COM_SOCIALADS_AD_UNLIMITED_AD_MSG'); ?>
					</div>

					<label class="control-label" for="type" title="">
						<?php echo JText::_('COM_SOCIALADS_AD_UNLIMITED_AD'); ?>
					</label>

					<div id="review" class="controls input-append unlimited_yes_no">
						<input type="radio" name="unlimited_ad" id="unlimited_ad1" value="1" <?php echo $publish1; ?> />
						<label class="first btn <?php echo $publish1_label; ?>" for="unlimited_ad1">
							<?php echo JText::_('JYES'); ?>
						</label>
						<input type="radio" name="unlimited_ad" id="unlimited_ad2" value="0" <?php echo  $publish2; ?> />
						<label class="last btn <?php echo $publish2_label; ?>" for="unlimited_ad2">
							<?php echo JText::_('JNO'); ?>
						</label>
					</div>
				</div>
				<?php
			} ?>

			<div class="control-group">
				<?php
				if ($showimpclick == 0)
				{ ?>
					<label class="ad-price-lable control-label">
						<?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_AD_AD_CHARGE_METHOD_DESC'), JText::_('COM_SOCIALADS_AD_AD_CHARGE_METHOD'), '', '* ' . JText::_('COM_SOCIALADS_AD_AD_CHARGE_METHOD')); ?>
					</label>
					<div class="controls">
						<?php $disabled = $displayData->allowWholeAdEdit ? "": "disabled=disabled"; ?>

						<?php echo JHtml::_('select.genericlist', $singleselect, "chargeoption", 'class="ad-pricing chzn-done" onchange="sa.create.calculateTotal()"' . $disabled . '', "value", "text", $ad_chargeoption); ?>
						<span class="help-inline editlinktip " >
							<img border="0" title="<?php echo JText::_("COM_SOCIALADS_ADS_TO_BE_CHARGED_MSG"); ?>" alt=""
								src="<?php echo $displayData->root_url . "media/com_sa/images/tooltip.png"; ?>">
						</span>
					</div>
					<?php
				}
				else
				{ ?>
					<div class="controls">
						<input type="hidden" name="chargeoption" id="chargeoption" value="<?php echo $ad_chargeoption; ?>">
						<input type="hidden" name="chargeoption_day" id="chargeoption_day" value="<?php echo $ad_chargeoption_day; ?>">
					</div>
				<?php
				} ?>
			</div>

			<?php
			if ($ad_chargeoption == 2 && $ad_chargeoption_day == '')
			{
				$display_style = "display:display";
			}
			else
			{
				if ($ad_chargeoption_day)
				{
					$display_style = "";
					// display:none";
				}
				elseif ((count($pricing_options)==1 && $pricing_options[0] == 2))
				{
					$display_style	=	"display:display";
				}
				else
				{
					$display_style	=	"display:none";
				}
			} ?>

			<div class="control-group" id="priceperdate" style="<?php echo $display_style; ?>">
				<?php
				if ($ad_daterangefrom && isset($ad_daterangefrom))
				{
					$checked = '';
					/*if ($sa_recuring == '1')
					{
						$checked = 'checked="checked"';
					}*/
					?>
					<label class="ad-price-lable control-label" >
						<?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_AD_CHARGE_PER_DAY_FROM_DESC'), JText::_('COM_SOCIALADS_AD_CHARGE_PER_DAY_FROM'), '', '* ' . JText::_('COM_SOCIALADS_AD_CHARGE_PER_DAY_FROM')); ?>
					</label>
					<div class="ad-price-lable controls">
						<?php echo JHtml::_("calendar", $ad_daterangefrom , "datefrom", "datefrom", "%Y-%m-%d", 'class="ad-pricing", onchange="sa.create.calculateTotal()"'); ?>
						<!--
						<div id="sa_recuring_div" style="display:none">
							<input type="checkbox" maxlength="5" name="sa_recuring" class="ad-pricing" id="sa_recuring" <?php //echo $checked; ?> value="1" onchange="sa.create.calculateTotal()" />
							<?php //echo JText::_("SA_AUTO_RENEW"); ?>
						</div>
						-->
					</div>
				<?php
				}
				else
				{
					$re_chked = '';
					$recurringPayments = $displayData->sa_params->get('recurring_payments');

					if ($recurringPayments)
						$re_chked = 'checked="checked"';
									//echo "=====2=====";
					?>
						<label class="ad-price-lable control-label" width="40%">
							<?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_AD_CHARGE_PER_DAY_FROM_DESC'), JText::_('COM_SOCIALADS_AD_CHARGE_PER_DAY_FROM'), '', '* ' . JText::_('COM_SOCIALADS_AD_CHARGE_PER_DAY_FROM')); ?>
						</label>
						<div class="ad-price-lable controls">
							<?php echo JHtml::_("calendar", " ", "datefrom", "datefrom", "%Y-%m-%d", 'class="ad-pricing", onchange="sa.create.calculateTotal()"'); ?>
							<!--
							<div id="sa_recuring_div" style="display:none">
								<input type="checkbox" maxlength="5" name="sa_recuring" class="ad-pricing" id="sa_recuring" <?php //echo $re_chked; ?> value="1" onchange="sa.create.calculateTotal()" />
									<?php //echo JText::_("SA_AUTO_RENEW"); ?>
							</div>
							-->
						</div>
				<?php
				}
				?>
			</div>

			<?php
			if ($ad_chargeoption == 2 && $ad_chargeoption_day)
			{
				$date_dis = 'display:block';
			}
			elseif ((count($pricing_options)==1 && $pricing_options[0] == 2))
			{
				$date_dis = 'display:block';
			}
			else
			{
				$date_dis = 'display:none';
			} ?>
			<div class="control-group" id="total_days" style="<?php echo $date_dis; ?>">
				<label class="ad-price-lable control-label" id="total_days_label" width="40%">
					<?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_AD_CHARGE_TOTAL_DAYS_FOR_RENEWAL_DESC'), JText::_('COM_SOCIALADS_AD_CHARGE_TOTAL_DAYS_FOR_RENEWAL'), '', '* ' . JText::_('COM_SOCIALADS_AD_CHARGE_TOTAL_DAYS_FOR_RENEWAL')); ?>
				</label>
				<div class="controls">
					<input type="text" maxlength="5" name="totaldays" class="ad-pricing" id="totaldays" value="<?php echo $ad_totaldays; ?>" onchange="sa.create.calculateTotal()" />
					<input type="hidden" name="ad_totaldays" id="ad_totaldays" value="<?php echo  $ad_totaldays; ?>" />
				</div>
			</div>

			<?php
			if ($ad_chargeoption == 2 || (count($pricing_options))==1 && $pricing_options[0] == 2)
			{ ?>
				<div id="priceperclick" class = "control-group" style="display:none">
			<?php
			}
			else
			{ ?>
				<div id="priceperclick" class = "control-group" style="display:block">
			<?php
			} ?>
					<label class="ad-price-lable control-label">
						<?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_AD_CLICKS_IMPRESSIONS_DESC'), JText::_('COM_SOCIALADS_AD_CLICKS_IMPRESSIONS'), '', '* ' . JText::_('COM_SOCIALADS_AD_CLICKS_IMPRESSIONS')); ?>
					</label>
					<div class="controls">
						<input type="text" maxlength="5" name="totaldisplay" class="ad-pricing input-medium cal_text" id="totaldisplay"
							value="<?php echo $ad_totaldisplay; ?>" onchange="sa.create.calculateTotal()"  />
					</div>
				</div>

			<div class = "control-group">
				<label class="ad-price-lable control-label">
					<span id="totalamtspan" name="totalamtspan">
						<?php echo JText::_("COM_SOCIALADS_AD_TOTAL"); ?>
					</span>
				</label>
				<div class="controls cal_text">
					<span id="ad_totalamount" name="ad_totalamount" class="ad_pricing" ><?php echo $ad_totalamount; ?></span>
					<input type="hidden" name="totalamount" id="totalamount" value="<?php echo $ad_totalamount; ?>" onchange="sa.create.calculateTotal()" />
					<input type="hidden" name="jpoints" id="jpoints"  value="<?php echo $u_points; ?>" />
					<span id="currency" name="currency" class="ad_pricing " ><?php echo $ad_currency; ?></span>
					<input type="hidden" name="h_currency" id="hcurrency"  value="<?php echo $ad_currency; ?>" />
				</div>
			</div>

			<div id= "dis_amt" style="display:none;">
			</div>

			<!-- Remove payment gateway html -->

			<div class="control-group" style="display:none">
				<label class="ad-price-lable control-label"><?php echo $gatewaylabel; ?></label>
				<?php
				if ($payment_flag)
				{ ?>
					<div class="controls">
						<?php
						// Show only default gateway
						if ($default_selected_gateway)
						{ ?>
							<span><?php echo $pluginParams->plugin_name; ?></span>
						<?php
						} ?>
						<span id="gateway_div" <?php echo $gateway_style; ?>>
							<?php echo JHtml::_(
							'select.genericlist', $selectbox , "gateway",
							'class="ad-pricing" size="1" onchange="sa.create.calculatePoints()" ', "value",
							"text", $ad_gateway
							); ?>
						</span>
					</div>
				<?php
				}
				else
				{ ?>
					<div class="controls">
						<span><?php echo JText::_('CHKGATEWAY'); ?><input type="hidden" name="gateway" id="gateway"  value="" /></span>
					</div>
				<?php
				} ?>

				<div class="controls">
					<div  id="rate" name="rate" class="ad_pricing" ><?php echo $ad_rate; ?></div>
					<input type="hidden" name="h_rate" id="hrate"  value="<?php echo $ad_rate; ?>" />
				</div>
			</div>

			<div class="sa_hideForUnlimitedads" >
				<div class="control-group " >
					<div class="controls">
						<div class="">
							<label class="checkbox">
								<input type="checkbox" id="sa_coupon_chk" autocomplete="off"name="coupon_chk" value="" size="10" onchange="sa.create.showCoupon()">
								<?php echo JText::_("COM_SOCIALADS_AD_GOT_COUPON") ?>
							</label>
							<span id="sa_cop_tr"  style="display:none;" >
								<input class="input-small focused" autocomplete="off" id="sa_coupon_code" name="sa_cop" value=""
									type="text" placeholder="<?php echo JText::_("COM_SOCIALADS_AD_COUPON_PLACEHOLDER") ?>">
								<input type="button" class="btn btn-success" onclick="sa.create.applyCoupon(1)"
									value="<?php echo JText::_("COM_SOCIALADS_AD_COUPON_APPLY") ?>">
							</span>
						</div>
					</div>
				</div>

				<div class="control-group sa_cop_details " style="display:none;" >
					<label class="control-label"><?php echo JText::_("COM_SOCIALADS_COUPON_PRICE") ?></label>
						<div class="controls" id="sa_cop_afterprice">
						</div>
				</div>

				<div class="control-group sa_cop_details " style="display:none;" >
					<label class="control-label"> <?php echo JText::_("COM_SOCIALADS_AFTER_COUPON_PRICE") ?></label>
					<div class="controls" id="sa_cop_price">
					</div>
				</div>
			</div>
		</div>
	</fieldset>
</div>

<?php
$addCreditAttr = '';

if (!empty($displayData->editableSteps) && $displayData->editableSteps['pricing'] == 0)
{
	$addCreditAttr = "disabled='disabled'";
} ?>

<script type="text/javascript">
	/*Define vars for sa.create.calculateTotal*/
	var jpoints=<?php echo $u_points; ?>;
	var amt1=0;
	var jconver;
	var gt=0;
	var recurring_gateway="<?php echo $recurring_gateway;?>";
	var re_jsondata='<?php echo $re_selectbox_json;?>';
	var addCreditAttr = "<?php echo $addCreditAttr; ?>";
	var slabs_json = '<?php echo $slabs_json; ?>';

	if (addCreditAttr)
	{
		jQuery("#chargeoption").attr("disabled","disabled");
			//jQuery("#totaldisplay").attr("disabled","disabled");
			//jQuery("#datefrom").attr("disabled","disabled");
	}
	/*
	jQuery(function() {
		var totaldisplay=document.getElementById('totaldisplay').value;

		jQuery('#sa_recuring').change(function(){
			var re_select = jQuery.parseJSON(re_jsondata);
			chargeselected=document.getElementById('chargeoption').value;
			if(document.getElementById('chargeoption').value > '2'){
				if (jQuery('#sa_recuring').is(':checked')){
					document.getElementById('total_days_label').innerHTML = Joomla.JText._('SA_RENEW_RECURR') + ' ' + jQuery('#chargeoption option:selected').text();

					if(parseInt(chargeselected)>2)
					{
						document.getElementById('totalamtspan').innerHTML = Joomla.JText._('TOTAL_SLAB') . + ' ' + jQuery('#chargeoption option:selected').text();
					}
					//sa.create.removeOption(re_select);
				}
				else{
					document.getElementById('total_days_label').innerHTML = Joomla.JText._('SA_RENEW_NO_RECURR') + ' ' + jQuery('#chargeoption option:selected').text();
					document.getElementById('totalamtspan').innerHTML = Joomla.JText._('TOTAL');
					//sa.create.addOption(re_select);
				}
			}
		});

		jQuery('#sa_recuring').change();

		if(document.getElementById('editview').value=='1'){
			//sa.create.calculatePoints();
			sa.create.calculateTotal();
		}
	});
	*/
</script>
