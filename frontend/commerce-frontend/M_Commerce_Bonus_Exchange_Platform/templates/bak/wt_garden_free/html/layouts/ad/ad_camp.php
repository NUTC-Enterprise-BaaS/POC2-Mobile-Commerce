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

$input = JFactory::getApplication()->input;
$document = JFactory::getDocument();

// Generate pricing mode options
$pricingOptions   = array();
$pricingOptions[] = JHtml::_('select.option', '', JText::_('COM_SOCIALADS_SELECT_PRICING_MODE'));
$pricingOption = $displayData->sa_params->get('pricing_options');

foreach ($pricingOption as $k => $v)
{
	if ($v == 'perimpression')
	{
		$pricingOptions[] = JHtml::_('select.option', '0', JText::_('COM_SOCIALADS_AD_CHARGE_PER_IMP'));
	}
	elseif($v == 'perclick')
	{
		$pricingOptions[] = JHtml::_('select.option', '1', JText::_('COM_SOCIALADS_AD_CHARGE_PER_CLICK'));
	}
}

// Generate campaign options
$campaignOptions = array();
$campaignOptions[] = JHtml::_('select.option', '0', JText::_('COM_SOCIALADS_SELECT_CAMPAIGN'));

if (count($displayData->camp_dd))
{
	foreach ($displayData->camp_dd as $camp)
	{
		// @TODO - manoj - not sure about first commented line
		// $campname = ucfirst(str_replace('plugpayment', '', $camp->campaign));
		$campname = $camp->campaign;
		// Static options($arr, $optKey= 'value', $optText= 'text', $selected=null, $translate=false)

		$campaignOptions[] = JHtml::_('select.option', $camp->id, $campname);
	}
}

/*
if($socialads_config['bidding']==1) { ?>
	$def = $displayData->cname;

	if($input->get('frm','','STRING'))
	{
		$def = $displayData->camp_id;
		$bid = $displayData->bid_value;
	}
}
*/
?>

<div class="techjoomla-bootstrap">
	<fieldset class="sa-fieldset">
		<legend class="hidden-desktop"><?php echo JText::_('PRICING'); ?></legend>
		<div class="form-horizontal">
			<div class="control-group">
				<?php
				$publish1 = $publish2 = $publish1_label = $publish1_label = '' ;

				if ($displayData->special_access)
				{
					if (!empty($displayData->addata_for_adsumary_edit->ad_noexpiry))
					{
						if ($displayData->addata_for_adsumary_edit->ad_noexpiry)
						{
							$publish1 = 'checked';
							$publish1_label =' btn-success ';
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
					}
					?>

					<div class="unlimited_adtext alert alert-info">
						<?php echo JText::_('COM_SOCIALADS_AD_UNLIMITED_AD_MSG'); ?>
					</div>

					<label class="control-label" for="type">
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
					<?php
				}
				?>
			</div>

			<div class="control-group">
				<label class="control-label" for="">
					<?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_SELECT_CAMP_TOOLTIP'), JText::_('COM_SOCIALADS_SELECT_CAMP'), '', JText::_('COM_SOCIALADS_SELECT_CAMP')); ?>
				</label>
				<div class="controls">
					<?php
					echo JHtml::_('select.genericlist', $campaignOptions, "ad_campaign", 'class="chzn-done" onchange="sa.create.hideNewCampaign()"', "value", "text", $displayData->camp_id);

					if (empty($displayData->cname))
					{
						?>
						<button type="button" class="btn btn-primary" onclick="sa.create.showNewCamp()"><?php echo JText::_('COM_SOCIALADS_NEW'); ?></button>
						<?php
					}
					?>
				</div>
			</div>

			<?php
			// If edit ad-- show the campaign name and value box if stored earlier
			// if (empty($displayData->cname) && $displayData->camp_id && $displayData->ad_value)
			if (empty($displayData->cname) && $displayData->camp_id)
			{
				$show_new_campaign_box = 'style="display:block"';
			}
			else
			{
				$show_new_campaign_box = 'style="display:none"';
			}
			?>

			<div id="new_campaign" <?php echo $show_new_campaign_box; ?> class="control-group">
				<label class="control-label" for="">
				</label>
				<div class="controls">
					<div class="form-inline">
						<input type="text" class="input-small" id="camp_name" name="camp_name" placeholder="<?php echo JText::_('COM_SOCIALADS_CAMPAIGN_NAME'); ?>" value="<?php // echo $displayData->cname; ?>">
						<div class="input-append">
							<input type="text" class="input-mini" id="camp_amount" name="camp_amount" placeholder="<?php echo JText::_('COM_SOCIALADS_CAMPAIGNS_DAILY_BUDGET'); ?>" value="<?php // echo $displayData->ad_value; ?>">
							<span class="add-on"><?php echo $displayData->sa_params->get('currency'); ?></span>
						</div>
					</div>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="">
					<?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_SELECT_METHOD_TOOLTIP'), JText::_('COM_SOCIALADS_AD_AD_CHARGE_METHOD'), '', JText::_('COM_SOCIALADS_AD_AD_CHARGE_METHOD')); ?>
				</label>

				<div class="controls">
					<?php echo JHtml::_('select.genericlist', $pricingOptions, "pricing_opt", 'class="chzn-done"  onchange="sa.create.getZonePricing()"', "value", "text", $displayData->ad_payment_type); ?>
				</div>

				<div class="controls">
					<div id="click" style="display:none">
						<p class="text-info"><span id="click_span"></span></p>
					</div>
					<div id="imps" style="display:none">
						<p class="text-info"><span id="imps_span">
					</div>
				</div>
			</div>

			<?php
			/* if($socialads_config['bidding']==1) { ?>
			<div class="control-group" id="bid_div">
				<label class="control-label" for=""><?php echo JText::_('BID_VALUE'); ?></label>
				<div class="controls">
					<div class="input-append ">
						<input type="text" class="input-mini" id="bid_value" name="bid_value" value="<?php echo (JRequest::getVar('frm')=='editad')? $bid : ''; ?>" placeholder="<?php echo JText::_('VALUE'); ?>">
						<span class="add-on"><?php echo JText::_('USD'); ?></span>
					</div>
				</div>
			</div>
			<?php } */?>
		</div>
	</fieldset>
</div>
