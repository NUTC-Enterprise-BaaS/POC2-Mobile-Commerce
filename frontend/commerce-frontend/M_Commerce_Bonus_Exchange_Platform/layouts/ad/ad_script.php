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
?>

<script type="text/javascript">
	/*javascript global variables declared here*/
	var sa_base_url="<?php echo JUri::base(); ?>";
	var root_url="<?php echo $displayData->root_url; ?>";
	var root_url2="<?php echo $displayData->root_url; ?>";
	var base_url="<?php echo JUri::base(); ?>";
	var currency="<?php echo $displayData->sa_params->get('currency'); ?>"
	var camp_currency_daily="<?php echo $displayData->sa_params->get('min_pre_balance'); ?>";
	var allowWholeAdEdit="<?php echo $displayData->allowWholeAdEdit; ?>";
	var selected_layout="<?php echo (!empty($displayData->addata_for_adsumary_edit->layout) ? $displayData->addata_for_adsumary_edit->layout : ''); ?>";
	var savennextbtn_text="<?php echo JText::_("COM_SOCIALADS_BTN_SAVEANDNEXT"); ?>";
	var savenexitbtn_text="<?php echo JText::_("COM_SOCIALADS_BTN_SAVEANDEXIT"); ?>";
	var showTargeting=parseInt("<?php echo $displayData->showTargeting; ?>");
	var selected_pricing_mode= "<?php echo $displayData->sa_params->get('payment_mode'); ?>";
	var sa_zone_pricing="<?php echo $displayData->sa_params->get('zone_pricing'); ?>";
	var sa_price_per_clicks="<?php echo $displayData->sa_params->get('per_clicks'); ?>";
	var sa_price_per_day="<?php echo $displayData->sa_params->get('per_day'); ?>";
	var sa_price_per_impressions="<?php echo $displayData->sa_params->get('per_impressions'); ?>";
	var sa_minimum_charge="<?php echo $displayData->sa_params->get('minimum_charge'); ?>";
	var sa_minimum_charge_msg="<?php echo JText::sprintf('COM_SOCIALADS_AD_MORE_MINCHARGE', $displayData->sa_params->get('minimum_charge'), $displayData->sa_params->get('currency'));?>";
	var sa_invalid_date_msg="<?php echo JText::_('COM_SOCIALADS_AD_DATE_NEED_MSG', true); ?>";
	var sa_ad_date_need_msg="<?php echo JText::_('COM_SOCIALADS_AD_DATE_NEED_MSG', true); ?>";
	var sa_wrong_dates_msg="<?php echo JText::_('COM_SOCIALADS_AD_WRONGDATES_MSG', true); ?>";
	var sa_invalid_credits_msg="<?php echo JText::_('COM_SOCIALADS_AD_INVALID_CREDITS', true); ?>";
	var sa_chk_contextual_msg="<?php echo JText::_('COM_SOCIALADS_AD_CHKCONTEXTUAL', true); ?>";
	var sa_per_day_msg="<?php echo JText::_('COM_SOCIALADS_AD_CHARGE_TOTAL_DAYS_FOR_RENEWAL', true); ?>";
	var addMoreCredit="<?php echo $displayData->addMoreCredit ? $displayData->addMoreCredit : 0; ?>";
	var sa_moreCreditMsg="<?php echo JText::_("COM_SOCIALADS_AD_MORE_CREDIT"); ?>";
	var cancelUrl="<?php echo JRoute::_('index.php?option=com_socialads&view=ads', false); ?>";

	var saAllowedMimeTypes = [
		'image/gif','image/png',
		'image/jpeg',
		'image/pjpeg',
		'image/jpeg',
		'image/pjpeg',
		'image/jpeg',
		'image/pjpeg'

	<?php
	if ($displayData->sa_params->get('video_uploads'))
	{
		// Videos
		?>
		,
		'video/mp4',
		'video/x-flv'
		<?php
	}
	?>

	<?php
	if ($displayData->sa_params->get('flash_uploads'))
	{
		/*Flash*/
		?>
		,
		'application/x-shockwave-flash',
		'application/octet-stream'
		<?php
	}
	?>
	];

	var saAllowedMediaSize = '<?php echo $displayData->sa_params->get('media_size') * 1024; ?>';

	<?php
		$mainframe = JFactory::getApplication();
		$isAdmin = 0;
		$adminApproval = 0;

		if ($mainframe->isAdmin())
		{
			$isAdmin = 1;
		}
	?>
	var isAdmin = '<?php echo $isAdmin; ?>';
	/*Initialize create ad js*/
	sa.create.initCreateJs();
</script>

<script type="text/javascript" src="<?php echo JUri::root(true); ?>/media/com_sa/js/ajaxupload.js"></script>
