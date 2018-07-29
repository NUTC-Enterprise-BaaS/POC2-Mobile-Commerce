<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Com_Socialads
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access
defined('_JEXEC') or die;
jimport('joomla.form.formfield');
$sa_params = JComponentHelper::getParams('com_socialads');
$selected = $sa_params->get('payment_mode', 'pay_per_ad_mode');
?>
<script type="text/javascript">
	techjoomla.jQuery(document).ready(function(){
				techjoomla.jQuery(".migrate").parent().parent().removeClass("controls")
			});
function camp_hide(radiovalue,element)
{
	var result = check_migrate(radiovalue);

	if(radiovalue == 'wallet_mode')
	{
		techjoomla.jQuery(element).show();
		techjoomla.jQuery('#migrate_div_for_old').hide();
		techjoomla.jQuery('#migrate_error_div').hide();
		techjoomla.jQuery("#jform_pricing_options option[value='perday']").remove(); /*remove per day option */
		jQuery("select").trigger("liszt:updated");
		techjoomla.jQuery('.price2').hide();
		techjoomla.jQuery('.pay_per_ad').hide();
	}
	else
	{
		techjoomla.jQuery(element).hide();
		techjoomla.jQuery('#migrate_error_div').hide();
		techjoomla.jQuery('#migrate_div').hide();

		if (!techjoomla.jQuery("#jform_pricing_options option[value='perday']").length)
		{
			if ('true' == '<?php
				if (in_array('perday', $sa_params->get('pricing_options', array())))
				{
					echo 'true';
				}
				else
				{
					echo 'false';
				}?>'
				)
					var day_sel = 'selected="selected"';
			else
				var day_sel = '';
				/**remove per day option */
			techjoomla.jQuery("#jform_pricing_options").append(
			'<option value="perday" '+day_sel+' ><?php echo JText::_('COM_SOCIALADS_FORM_LBL_ZONE_PER_DAY') ?></option>'
			);
			jQuery("select").trigger("liszt:updated");

			techjoomla.jQuery('.price2').show();
		}

		techjoomla.jQuery('.pay_per_ad').show();
	}
}


function check_migrate(camp_or_old)
{
	var result;
	techjoomla.jQuery.ajax({
		url: '?option=com_socialads&task=migrationOfAds&migrate_chk=1&camp_or_old='+camp_or_old,
		type: 'GET',
		async: false,
		error: function(){
			return 0;
		},
		dataType: 'json',
		success: function(data) {
			if(data == 1)
			{
				if(camp_or_old == 'wallet_mode')
					techjoomla.jQuery('#migrate_div').show();
				else
					techjoomla.jQuery('#migrate_div_for_old').show();
				result =  1;
			}
			else
				result = 0;
		}
	});
	return result;
}

function migrateads()
{
	var migrate_sure=confirm('<?php echo JText::_('COM_SOCIALADS_PRICING_MIGRATE_ADS_CONFORMATION'); ?>');

	if (migrate_sure==true)
	{
		var camp_or_old = techjoomla.jQuery('input:radio[name =\"jform[payment_mode]\"]:checked').val();
		techjoomla.jQuery('#migrate_btn').hide();
		techjoomla.jQuery('#migrate_btn_for_old').hide();
		techjoomla.jQuery('#loader_image_div').show();

		techjoomla.jQuery.ajax({
			url: '?option=com_socialads&task=migrationOfAds&camp_or_old='+camp_or_old,
			type: 'GET',
			dataType: 'json',
			success: function(data)
			{
				if(data == 1)
				{
					techjoomla.jQuery('#loader_image_div').hide();
					if(camp_or_old == 'wallet_mode')
						techjoomla.jQuery('#migration_status').show();
					else
						techjoomla.jQuery('#migration_status_for_old').show();
					alert('<?php echo JText::_('COM_SOCIALADS_MIGRATE_CONFIRM_SAVE_POP'); ?>');
					/*Joomla.submitbutton('save');*/
				}
				else
				{
					techjoomla.jQuery('#loader_image_div').hide();
					techjoomla.jQuery('#migrate_error_div').show();
				}
			}
		});
	}
	else
	{
		return false;
	}
}

</script>

<?php
jimport('joomla.filesystem.file');
$tjStrapperPath = JPATH_SITE . '/media/techjoomla_strapper/tjstrapper.php';

if (JFile::exists($tjStrapperPath))
{
	require_once $tjStrapperPath;
	TjStrapper::loadTjAssets('com_socialads');
}


/**
 * Settings model class.
 *
 * @since  1.0
 */
class JFormFieldPaymentmode extends JFormField
{
	public $type = 'Paymentmode';

	/**
	 * Method to get the field input markup.
	 *
	 * TODO: Add access check.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   1.6
	 */
	public function getInput()
	{
		$sa_params = JComponentHelper::getParams('com_socialads');
		$selected = $sa_params->get('payment_mode', 'pay_per_ad_mode');
		$camp_currency_daily = $sa_params->get('min_pre_balance', 5);
		$currency = $sa_params->get('currency', 'USD');
		require_once JPATH_SITE . "/components/com_socialads/helpers/payment.php";

		$SocialadsPaymentHelper = new SocialadsPaymentHelper;

		if ($selected == 'wallet_mode')
		{
			$ads2_camp = $SocialadsPaymentHelper->migrateads_camp('camp_hide');
		}
		else
		{
			// Check if migrating camp_budget to old
			$ads2_old = $SocialadsPaymentHelper->migrateads_old('camp_hide');
		}

		$options = array(
			JHtml::_('select.option', 'wallet_mode', JText::_('COM_SOCIALADS_PRICING_ADVERTISING_WALLET')) ,
			JHtml::_('select.option', 'pay_per_ad_mode', JText::_('COM_SOCIALADS_PRICING_PAY_PER_DAY'))
		);
		$return = JHtml::_('select.radiolist', $options, 'jform[payment_mode]',
		'class="inputbox migrate" onclick="camp_hide(this.value,\'.camp_price\');"', 'value', 'text', $selected, '');
		$return .= '
		<div style="margin-top:5px; margin-left:60px;">
			<div id="migrate_div" ';

		if (!($selected == 'wallet_mode' && $ads2_camp))
		{
			$return .= 'style="display:none"';
		}

		$return .= ' >
				<div id="migrate_btn">
					<div class="alert alert-error">' . JText::_('COM_SOCIALADS_PRICING_MIGRATE_NOTICE_FOR_WALLET') . '</div>
					<input type="button" class="btn btn-danger"  width="50%" onclick="migrateads()" value="'
					. JText::_('COM_SOCIALADS_PRICING_MIGRATE_AD_WALLET_MODE') . '" />
				</div>
				<div id="migration_status" style="display:none">
					<div class="completed_old_migrate" >
						<span class="image_span alert alert-info" ><img class="image"  src="' . JUri::root() . '/media/com_sa/images/publish.png' . '" > '
						. JText::sprintf('COM_SOCIALADS_PRICING_WALLET_STEP1', $camp_currency_daily, $currency) . '</span>
					</div>
					<div class="completed_old_migrate" >
						<span class="image_span alert alert-info" ><img class="image"  src="' . JUri::root() . '/media/com_sa/images/publish.png' . '" > '
						. JText::sprintf('COM_SOCIALADS_PRICING_WALLET_STEP2', $currency) . '</span>
					</div>
					<div class="completed_old_migrate" >
						<span class="image_span alert alert-info" ><img class="image"  src="' . JUri::root() . '/media/com_sa/images/publish.png' . '" > '
						. JText::_('COM_SOCIALADS_PRICING_WALLET_STEP3') . '</span>
					</div>
					<div class="completed_old_migrate" >
						<span class="image_span alert alert-info" ><img class="image"  src="' . JUri::root()
						. 'media/com_sa/images/publish.png' . '" > '
						. JText::_('COM_SOCIALADS_PRICING_WALLET_STEP4') . '</span>
					</div>
				</div>
			</div>
			<div id="loader_image_div" style="display:none">
				 <div class="alert alert-warning">' . JText::_('COM_SOCIALADS_PRICING_PLEASE_WAIT') . ' </div>
				 <img src="' . JUri::root() . 'media/com_sa/images/loader_light_blue.gif" width="128" height="15" border="0" />
			</div>
			<div id="migrate_div_for_old" ';

		if (!($selected == 'pay_per_ad_mode' && $ads2_old))
		{
			$return .= 'style="display:none"';
		}

		$return .= ' >';
		$return .= '
				<div id="migrate_btn_for_old">
					<div class="alert alert-error">' . JText::_('COM_SOCIALADS_PRICING_MIGRATE_NOTICE_FOR_PAY_PER_AD') . '</div>
					<input type="button" class="btn btn-danger"  width="50%" onclick="migrateads()" value="' .
					JText::_('COM_SOCIALADS_PRICING_MIGRATE_PAY_PER_AD_MODE') . '" />
				</div>

				<div id="migration_status_for_old" style="display:none">
					<div class="completed_old_migrate" >
						<span class="image_span alert alert-info" ><img class="image"  src="'
						. JUri::root() . 'media/com_sa/images/publish.png' . '" > '
						. JText::_('COM_SOCIALADS_PRICING_PAY_PER_AD_STEP1') . '</span>
					</div>
					<div class="completed_old_migrate" >
						<span class="image_span alert alert-info" ><img class="image"  src="' . JUri::root()
						. 'media/com_sa/images/publish.png' . '" > '
						. JText::_('COM_SOCIALADS_PRICING_PAY_PER_AD_STEP2') . '</span>
					</div>
					<div class="completed_old_migrate" >
						<span class="image_span alert alert-info" ><img class="image"  src="'
						. JUri::root() . 'media/com_sa/images/publish.png' . '" > '
						. JText::_('COM_SOCIALADS_PRICING_PAY_PER_AD_STEP3') . '</span>
					</div>
				</div>
			</div>
			<div id="migrate_error_div" class="alert alert-warning" style="display:none"  ></div>
		</div>
		';

		return $return;
	}
}
