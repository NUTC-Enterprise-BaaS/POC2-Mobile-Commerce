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
	<div class="page-header">
		<h1>
			<?php
			 if (!empty($this->item->id)):
			 echo JText::_('COM_SOCIALADS_EDIT_ITEM');
			else:
				echo JText::_('COM_SOCIALADS_ADD_ITEM');
			endif;
			?>
		</h1>
	</div>
	<form id="campaign-form" action="<?php echo JRoute::_('index.php?option=com_socialads&task=campaign.edit'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
		<div class="form-group">
			<div class="col-lg-2 col-md-2 col-sm-3 col-xs-12 control-label"><?php echo $this->form->getLabel('campaign'); ?></div>
			<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12"><?php echo $this->form->getInput('campaign'); ?></div>
		</div>
		<!---->
	<div class="form-group">
		<?php $canState = false; ?>
		<?php $canState = $canState = JFactory::getUser()->authorise('core.edit.own','com_socialads'); ?>

		<?php
		if(!$canState): ?>
			<div class=" col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label ">
				<?php echo $this->form->getLabel('state'); ?>
			</div>
				<?php
				$state_string = JText::_('COM_SOCIALADS_COUPONS_PUBLISHED');
				$state_value = 0;
				if($this->item->state == 1):
					$state_string = JText::_('COM_SOCIALADS_COUPONS_UNPUBLISHED');
					$state_value = 1;
				endif;
				?>
			<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12"><?php echo $state_string; ?></div>

			<input type="hidden" name="jform[state]" value="<?php echo $state_value; ?>" />
		<?php
		else: ?>
			<div class=" col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label ">
				<?php echo $this->form->getLabel('state'); ?>
			</div>
			<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">
				<?php //echo $this->form->getInput('state'); ?>

				<?php
				$jtPublish = " checked='checked' ";
				$jtUnpublish = "";

				if (empty($this->form->getValue('state')))
				{
					$jtPublish = "";
					$jtUnpublish = " checked='checked' ";
				}

				  $jtPublish;
				?>

				<label class="radio-inline">
				  <input type="radio" class="" <?php echo $jtPublish;?> value="1" id="jform_state1" name="jform[state]" >
				  <?php echo JText::_('COM_SOCIALADS_COUPONS_PUBLISHED');?>
				</label>
				<label class="radio-inline">
				  <input type="radio" class="" <?php echo $jtUnpublish;?> value="0" id="jform_state0" name="jform[state]" >
					<?php echo JText::_('COM_SOCIALADS_COUPONS_UNPUBLISHED');?>
				</label>
			</div>
		<?php
		endif; ?>

	</div>
		<!----->
		<div class="form-group">
			<?php
				$params = JComponentHelper::getParams('com_socialads');
				$currency = $params->get('currency');
			?>
			<div class="col-lg-2 col-md-2 col-sm-3 col-xs-12 control-label"><?php echo $this->form->getLabel('daily_budget'); ?></div>
			<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">
				<div class="input-group input-large">
					<?php echo $this->form->getInput('daily_budget'); ?>
					<span class="input-group-addon"><?php echo $currency; ?></span>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">
				<button type="submit" class="validate btn  btn-default btn-primary"><?php echo JText::_('JSUBMIT'); ?></button>
				<a class="btn btn-default" href="<?php echo JRoute::_('index.php?option=com_socialads&task=campaignform.cancel'); ?>" title="<?php echo JText::_('JCANCEL'); ?>">
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
