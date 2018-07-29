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

$geoTargeting = $displayData->sa_params->get('geo_targeting');
$displayData->geodbfile_exists = JFile::exists(JPATH_SITE . '/components/com_socialads/classes/geolocation/maxmind/GeoLiteCity.dat');

if ($geoTargeting && $displayData->geodbfile_exists)
{
	?>
	<link rel="stylesheet" href="<?php echo JUri::root(true) . '/media/com_sa/css/geo.css' ?>">
	<?php
}
?>

<!-- geo target start here -->
<?php
if ($geoTargeting)
{
	if (!empty($displayData->geo_target))
	{
		$geo_dis = 'style="display:block;"';
	}
	else
	{
		$geo_dis = 'style="display:none;"';
	}

	// If edit ad from adsummary then prefill targeting for geo targeting...else placeholder
	$check_radio_region = $check_radio_city = '';
	$everywhere = $country = $region = $city = '';

	if ($displayData->edit_ad_id)
	{
		if (isset($displayData->geo_target['country']))
		{
			$country = $displayData->geo_target['country'];
		}

		// For region field to prefilled...
		if (!empty($displayData->geo_target['region']))
		{
			$check_radio_region = 1;
			$region             = $displayData->geo_target['region'];
		}

		// For city field to prefilled...
		if (!empty($displayData->geo_target['city']))
		{
			$check_radio_city = 1;

			if (isset($displayData->geo_target['city']))
			{
				$city = $displayData->geo_target['city'];
			}
		}

		if (empty($displayData->geo_target['region']) && empty($displayData->geo_target['city']))
		{
			$everywhere = 1;
		}
	}

	$publish1 = $publish2 = $publish1_label = $publish2_label = '';

	if (isset($displayData->geo_target))
	{
		if ($displayData->geo_target)
		{
			$publish1       = 'checked="checked"';
			$publish1_label = 'btn-success';
		}
		else
		{
			$publish2       = 'checked="checked"';
			$publish2_label = 'btn-danger';
		}
	}
	else
	{
		$publish2       = 'checked="checked"';
		$publish2_label = 'btn-danger';
	}
	?>
<div class="form-horizontal">
	<div id="geo_target_space" class="target_space well">
		<div class="control-group">
			<label class="control-label" for="">
				<?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_AD_GEO_TARGETING_DESC'), JText::_('COM_SOCIALADS_AD_GEO_TARGETING'), '', JText::_('COM_SOCIALADS_AD_GEO_TARGETING')); ?>
			</label>
			<div class="controls input-append targetting_yes_no">
				<input type="radio" name="geo_targett" id="publish1" value="1" <?php echo $publish1;?> >
				<label class="first btn <?php echo $publish1_label;?>" type="button" for="publish1">
					<?php echo JText::_('JYES');?>
				</label>
				<input type="radio" name="geo_targett" id="publish2" value="0" <?php echo $publish2;?> >
				<label class="last btn <?php echo $publish2_label;?>" type="button" for="publish2">
					<?php echo JText::_('JNO');?>
				</label>
			</div>
		</div>

		<div id="geo_targett_div" <?php echo $geo_dis; ?> class="targetting">
			<div class="alert alert-info">
				<i><?php echo JText::_('COM_SOCIALADS_AD_GEO_TARGET_MSG'); ?></i>
			</div>
			<?php
			if ($displayData->geodbfile_exists)
			{
				?>
				<div id="mapping-field-table">
					<div class="control-group">
						<label for="" title="<?php echo JText::_('COM_SOCIALADS_AD_GEO_COUNTRY');?>">
							<?php echo JText::_('COM_SOCIALADS_AD_GEO_COUNTRY');?>
						</label>
						<div class="controls">
							<ul class='selections' id='selections_country'>
								<input autocomplete="off" type="text" class="geo_fields sa-fields-inputbox"  id="country" value="<?php echo $country; ?>"
									placeholder="<?php echo JText::_('COM_SOCIALADS_AD_GEO_COUNTRY_MSG');?>"/>
								<input type="hidden" class="geo_fields_hidden" name="geo[country]" id="country_hidden" value="" />
							</ul>
						</div>
					</div>

					<div class="control-group">
						<div class="controls">
							<div id ="geo_others" style="display:none;">
								<label class="saradioLabel radio" for="everywhere"><input type="radio"
									<?php echo $everywhere ? 'checked="checked"' : ''; ?>
									value="everywhere" name="geo_type" id="everywhere" class="saradioLabel">
									<?php echo JText::_("COM_SOCIALADS_AD_GEOEVERY"); ?>
								</label>
								<div <?php echo in_array('byregion', $displayData->sa_params->get('geo_options')) ? '' :'style="display:none;"'; ?> >
									<label class="saradioLabel radio" for="byregion">
										<input type="radio"
											<?php echo $check_radio_region ? 'checked="checked"' : ''; ?>
											value="byregion" name="geo_type" id="byregion" class="saradioLabel">
											<?php echo JText::_("COM_SOCIALADS_AD_GEO_STATE"); ?>
									</label>
									<ul style="display:none;" class="selections byregion_ul" id='selections_region' >
										<input type="text" class="geo_fields sa-fields-inputbox"  id="region"
											value="<?php echo $region; ?>"
											placeholder="Start typing region.."/>
										<input type="hidden" class="geo_fields_hidden" name="geo[region]" id="region_hidden" value="" />
									</ul>
								</div>
								<div <?php echo in_array('bycity', $displayData->sa_params->get('geo_options')) ? '' : 'style="display:none;"'; ?>>
									<label class="saradioLabel radio" for="bycity">
										<input type="radio"
											<?php echo $check_radio_city ? 'checked="checked"' : ''; ?>
											value="bycity" name="geo_type" id="bycity" class="saradioLabel">
											<?php echo JText::_("COM_SOCIALADS_AD_GEO_CITY"); ?>
									</label>
									<ul style="display:none;" class="selections bycity_ul"  id='selections_city' >
										<input type="text" class="geo_fields sa-fields-inputbox"  id="city"
											value="<?php echo $city; ?>"
											placeholder="Start typing city.."/>
										<input type="hidden" class="geo_fields_hidden" name="geo[city]" id="city_hidden" value="" />
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php
			}
			else
			{
				?>
				<div><span class="sa_labels"><?php echo JText::_('COM_SOCIALADS_AD_GEO_NO_DBFILE'); ?></span></div>
				<?php
			}
			?>
		</div>
		<!-- Geo_target_div end here -->
		<div style="clear:both;"></div>
	</div>
</div>
	<?php
}
?>
<!-- geo target end here -->

