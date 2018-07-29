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

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

if (JVERSION > '3.0')
{
	JHtml::_('formbehavior.chosen', 'select');
}

// Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_socialads', JPATH_ADMINISTRATOR);
$doc = JFactory::getDocument();
$doc->addScript(JUri::root(true) . '/media/com_sa/js/form.js');

if ($this->item->state == 1)
{
	$state_string = JText::_("COM_SOCIALADS_COUPONS_PUBLISHED");
	$state_value = 1;
}
else
{
	$state_string = JText::_("COM_SOCIALADS_COUPONS_UNPUBLISHED");
	$state_value = 0;
}

$canState = JFactory::getUser()->authorise('core.edit.state','com_socialads');
?>
<script type="text/javascript">
		techjoomla.jQuery(document).ready(function()
		{
			techjoomla.jQuery(".alphaCheck").keyup(function()
					{
						sa.checkForZeroAndAlpha(this,'46', Joomla.JText._('COM_SOCIALAD_PAYMENT_ENTER_NUMERICS'));
					});
		});
</script>

<div class="campaign-edit front-end-edit">
	<?php
	 if (!empty($this->item->id)): ?>
		<h1><?php echo JText::_('COM_SOCIALADS_EDIT_ITEM'); ?></h1>
	<?php
	else: ?>
		<h1><?php echo JText::_('COM_SOCIALADS_ADD_ITEM'); ?></h1>
	<?php
	endif; ?>

	<form id="campaign-form" action="<?php echo JRoute::_('index.php?option=com_socialads&task=campaign.edit'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('campaign'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('campaign'); ?></div>
		</div>
		<div class="control-group">
			<?php
			if (!$canState): ?>
				<div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
				<div class="controls"><?php echo $state_string; ?></div>
				<input type="hidden" name="jform[state]" value="<?php echo $state_value; ?>" />
			<?php
			else: ?>
				<div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('state'); ?></div>
			<?php
			endif; ?>
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
		</div>
		<div class="control-group">
			<div class="controls">
				<button type="submit" class="validate btn btn-primary"><?php echo JText::_('JSUBMIT'); ?></button>
				<a class="btn" href="<?php echo JRoute::_('index.php?option=com_socialads&task=campaignform.cancel'); ?>" title="<?php echo JText::_('JCANCEL'); ?>">
					<?php echo JText::_('JCANCEL'); ?>
				</a>
			</div>
		</div>
		<input type="hidden" name="option" value="com_socialads" />
		<input type="hidden" name="option" value="com_socialads" />
		<input type="hidden" name="task" value="campaignform.save" />
		<input type="hidden" name="cid" value=<?php echo $this->item->id;?>/>
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
