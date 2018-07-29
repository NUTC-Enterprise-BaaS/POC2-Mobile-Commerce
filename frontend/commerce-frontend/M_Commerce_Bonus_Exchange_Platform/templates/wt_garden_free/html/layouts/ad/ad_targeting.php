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
$mainframe = JFactory::getApplication();
$isAdmin = 0;

if ($mainframe->isAdmin())
{
	$isAdmin = 1;
}
?>

<!--for showing selection type of fields which is imported from targeting fields-->
<div id="lowerdiv" style="display:block;">
	<fieldset class="sa-fieldset">
		<legend class="hidden-desktop"><?php echo JText::_('COM_SOCIALADS_AD_TARGETING'); ?></legend>
		<div class="alert">
			<i><?php echo JText::_('COM_SOCIALADS_AD_TARGETING_DESC');?> </i>
		</div>
		<!-- geo target start here -->
		<?php
		if ($isAdmin == 1)
		{
			$saLayout = new JLayoutFile('bs2.ad.ad_targeting_geo');
		}
		else
		{
			$saLayout = new JLayoutFile('ad.ad_targeting_geo');
		}
		// Trick- we are using layout inside layout, so instead of $this, pass $displayData
		echo $saLayout->render($displayData); ?>

		<!-- social target start here -->
		<?php
		if ($isAdmin == 1)
		{
			$saLayout = new JLayoutFile('bs2.ad.ad_targeting_social');
		}
		else
		{
			$saLayout = new JLayoutFile('ad.ad_targeting_social');
		}
		// Trick- we are using layout inside layout, so instead of $this, pass $displayData
		echo $saLayout->render($displayData);
		?>

		<!-- context target start here -->
		<?php
		if ($isAdmin == 1)
		{
			$saLayout = new JLayoutFile('bs2.ad.ad_targeting_contextual');
		}
		else
		{
			$saLayout = new JLayoutFile('ad.ad_targeting_contextual');
		}
		// Trick- we are using layout inside layout, so instead of $this, pass $displayData
		echo $saLayout->render($displayData); ?>
	</fieldset>

	<?php
	// If edit ad from adsummary then dont show continue and back button ...show update button directly..
	if ($displayData->edit_ad_id)
	{
		if (($displayData->addata_for_adsumary_edit->ad_alternative == 0 && $displayData->addata_for_adsumary_edit->ad_affiliate == 0))
		{
		}
	}
	else
	{
	}
	?>
</div>
