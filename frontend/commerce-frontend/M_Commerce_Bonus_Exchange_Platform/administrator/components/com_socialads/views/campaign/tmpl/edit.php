<?php
/**
 * @version    SVN: <svn_id>
 * @package    Quick2cart
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

// No direct access.
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

$canState = JFactory::getUser()->authorise('core.edit.state','com_socialads');
$canEdit = JFactory::getUser()->authorise('core.edit','com_socialads');
if($this->item->state == 1){
	$state_string = JText::_("COM_SOCIALADS_COUPONS_PUBLISHED");
	$state_value = 1;
}
else
{
	$state_string = JText::_("COM_SOCIALADS_COUPONS_UNPUBLISHED");
	$state_value = 0;
}
?>

<form action="<?php echo 'index.php?option=com_socialads&layout=edit&id=' . (int) $this->item->id; ?>" method="post"
	enctype="multipart/form-data" name="adminForm" id="campaign-form" class="form-validate">
	<div class="form-horizontal">
		<div class="row-fluid">
			<div class="span10 form-horizontal">
				<fieldset class="adminform">
					<?php if ($canEdit):?>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('created_by'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('created_by'); ?></div>
						</div>
						<div class="control-group">
							<?php if(!$canState): ?>
							<div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
							<div class="controls"><?php echo $state_string; ?></div>
							<input type="hidden" name="jform[state]" value="<?php echo $state_value; ?>" />
							<?php else: ?>
								<div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
								<div class="controls"><?php echo $this->form->getInput('state'); ?></div>
							<?php endif; ?>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('campaign'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('campaign'); ?></div>
						</div>
						<div class="control-group">
							<?php
							$params = JComponentHelper::getParams('com_socialads');
							$currency = $params->get('currency');
							?>
							<div class="control-label"><?php echo $this->form->getLabel('daily_budget'); ?></div>
							<div class="controls">
								<div class="input-append">
									<?php echo $this->form->getInput('daily_budget'); ?>
									<span class="add-on"><?php echo $currency; ?></span>
								</div>
						</div>
					<?php endif;?>
				</fieldset>
			</div>
		</div>
		<input type="hidden" name="task" value="" />
			<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

<script type="text/javascript">
	saAdmin.campaign.initCampaignJs();
	Joomla.submitbutton = function(task){saAdmin.campaign.campaignSubmitButton(task);}
</script>
