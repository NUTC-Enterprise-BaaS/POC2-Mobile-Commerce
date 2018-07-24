<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access
defined( '_JEXEC' ) or die( ';)' );
JHTML::_('behavior.formvalidation');
$document = JFactory::getDocument();

// Get ad preview
// Get payment HTML
// JLoader::import('showad',JPATH_SITE.DS.'components'.DS.'com_socialads'.DS.'models');
// JLoader::import('showad', JPATH_ADMINISTRATOR.DS.'components'.DS.'com_socialads'.DS.'models');
// $showadmodel = new socialadsModelShowad();
// $preview = $showadmodel->getAds($displayData->ad_id);

require_once JPATH_ROOT . '/components/com_socialads/helpers/engine.php';
// $displayData->preview = SaAdEngineHelper::getAdHtml($displayData->ad_id, 1); // $preview

if ($displayData->AdPreviewData->ad_payment_type == 0)
{
	$mode =  JText::_('COM_SOCIALADS_PAY_IMP');
}
elseif($displayData->AdPreviewData->ad_payment_type == 1)
{
	$mode = JText::_('COM_SOCIALADS_PAY_CLICK');
}
elseif($displayData->AdPreviewData->ad_payment_type == 3)
{
	$mode = JText::_('SELL_THROUGH');
}
?>

	<form action="" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="" >
		<fieldset class="sa-fieldset">
			<legend class="hidden-desktop"><?php echo JText::_('COM_SOCIALADS_REVIEW_AD_TAB'); ?></legend>
			<!-- for ad detail and preview -->
			<div class=" row-fluid show-grid">
				<!--ad detai start -->
				<div class="span6">
					<h4><?php echo JText::_('COM_SOCIALADS_AD_PAYMENT_REVIEW');?></h4>
					<div class="clearfix">&nbsp;</div>
					<table class="table table-hover">
						<tr>
							<td><?php echo JText::_('COM_SOCIALADS_CAMPAIGN_NAME'); ?></td>
							<td>
								<div id="ncamp"><?php echo $displayData->AdPreviewData->campaign; ?></div>
							</td>
						</tr>
						<tr>
							<td><?php echo JText::_('COM_SOCIALADS_PRICING_MODE'); ?></td>
							<td>
								<div id="modecamp"><?php echo $mode; ?></div>
							</td>
						</tr>
						<tr>
							<?php
							/*if($socialads_config['bidding']==1 && $displayData->bid_value)
							{
								?>
								<td><?php echo JText::_('BID_VALUE'); ?></td>
								<td>
									<div id="bid"><?php echo $displayData->bid_value; echo " "; echo JText::_('USD'); ?></div>
								</td>
								<?php
							}
							*/
							?>
						</tr>
					</table>
				</div>
				<div class="span6">
					<?php echo SaAdEngineHelper::getAdHtml($displayData->ad_id, 1); ?>
				</div>
			</div>

			<input type="hidden" name="option" value="com_socialads"/>
			<!--
			<input type="hidden" name="controller" value="ad"/>
			-->
			<input type="hidden" name="task" value=""/>

			<div class="form-actions">
				<button id="buy" type="button" class="btn btn-success" onclick="submitbutton('create.activateAd')">
					<?php echo JText::_('COM_SOCIALADS_SAVE_ACTIVATE');?>
				</button>
				<button id="draft" type="button" class="btn btn-info" onclick="submitbutton('create.draftAd');">
					<?php echo JText::_('COM_SOCIALADS_SHOWAD_DRAFT');?>
				</button>
			</div>
		</fieldset>
	</form>
</div>
