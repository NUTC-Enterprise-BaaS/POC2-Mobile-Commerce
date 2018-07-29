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
$contextualTargeting = $displayData->sa_params->get('contextual_targeting');

if ($contextualTargeting)
{
	if (!empty($displayData->context_target_data_keywordtargeting))
	{
		$context_dis = 'style="display:block;"';
	}
	else
	{
		$context_dis = 'style="display:none;"';
	}

	$context_target1 = $context_target2 = $context_target1_label = $context_target2_label = '';

	if (isset($displayData->context_target_data_keywordtargeting))
	{
		if ($displayData->context_target_data_keywordtargeting)
		{
			$context_target1       = 'checked="checked"';
			$context_target1_label = 'btn-success';
		}
		else
		{
			$context_target2       = 'checked="checked"';
			$context_target2_label = 'btn-danger';
		}
	}
	else
	{
		$context_target2       = 'checked="checked"';
		$context_target2_label = 'btn-danger';
	}
	?>
	<div class="form-horizontal">
		<div id="context_target_space" class="target_space well">
			<div class="control-group">
				<label class="control-label" for="">
					<?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_AD_CONTEXT_TARGET_DESC'), JText::_('COM_SOCIALADS_AD_CONTEXT_TARGET'), '', JText::_('COM_SOCIALADS_AD_CONTEXT_TARGET')); ?>
				</label>
				<div class="controls input-append targetting_yes_no">
					<input type="radio" name="context_targett" id="context_target1" value="1" class="target" <?php echo $context_target1; ?> >
					<label class="first btn <?php echo $context_target1_label;?>" type="button" for="context_target1"><?php echo JText::_('JYES');?></label>
					<input type="radio" name="context_targett" id="context_target2" value="0" class="target" <?php echo $context_target2; ?> >
					<label class="last btn <?php echo $context_target2_label;?>" type="button" for="context_target2"><?php echo JText::_('JNO');?></label>
				</div>
			</div>

			<div id="context_targett_div" <?php echo $context_dis; ?> class="targetting">
				<div class="alert alert-info"><i><?php echo JText::_('COM_SOCIALADS_AD_CONTEXT_TARGET_DESC'); ?></i></div>

				<div id="mapping-field-table">
					<div class="control-group">
						<label for="context_target_data" title="<?php echo JText::_('COM_SOCIALADS_AD_CONTEXT_TARGET_INPUTBOX_DESC');?>">
							<?php echo JText::_('COM_SOCIALADS_AD_CONTEXT_TARGET_INPUTBOX');?>
						</label>
						<div class="controls">
							<input type="text" name="context_target_data[keywordtargeting]" class="inputbox input-xlarge" id="context_target_data" value="<?php echo $displayData->context_target_data_keywordtargeting;?>" onchange="" />
						</div>
					</div>
				</div>
			</div>
			<!-- context_target_div end here -->
			<div style="clear:both;"></div>
		</div>
	</div>
<?php
}
?>
