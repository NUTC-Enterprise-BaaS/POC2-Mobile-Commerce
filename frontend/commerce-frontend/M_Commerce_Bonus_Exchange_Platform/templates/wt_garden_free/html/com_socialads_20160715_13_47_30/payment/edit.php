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

if (JVERSION > '3.0')
{
	JHtml::_('behavior.tooltip');
	JHtml::_('behavior.formvalidation');
	JHtml::_('formbehavior.chosen', 'select');
	JHtml::_('behavior.keepalive');
}

$params = JComponentHelper::getParams('com_socialads');
$user = JFactory::getUser();
?>
<div class="techjoomla-bootstrap">
	<script type="text/javascript">
		var currency = "<?php echo $params->get('currency')?>";
		var imgpath = "<?php echo JUri::root(true).'/media/com_sa/images/ajax.gif'; ?>";
		techjoomla.jQuery(document).ready(function()
		{
				techjoomla.jQuery("#couponHide").hide();

				techjoomla.jQuery(".alphaCheck").keyup(function()
					{
						sa.checkForZeroAndAlpha(this,'46', Joomla.JText._('COM_SOCIALAD_PAYMENT_ENTER_NUMERICS'));
					});
				techjoomla.jQuery(".couponshow").change(function()
					{
						var rad = techjoomla.jQuery('input[name=jform\\[coupon_result\\]]:checked').val()
						sa.payment.showCoupon(rad, '<?php $params->get('min_pre_balance')?>');
					});
				techjoomla.jQuery(".pluginShow").click(function()
					{
						sa.payment.makePayment(this.value)
					});
		});
	</script>
	<div class="page-header">
		<h2><?php echo JText::_('COM_SOCIALAD_PAYMENT_MAKE_PAYMENT'); ?></h2>
	</div>
	<form name="adminForm" class="form-validate form-horizontal" id="hello" action="" method="post" class="form-validate" enctype="multipart/form-data">
		<?php
		$gatewayselect = array();
		$gateways = $params->get('gateways');
		$gateways = (array)$gateways;

		foreach ($this->gatewayplugin as $gateway)
		{
			if (!in_array($gateway->element,$gateways))
				continue;
			$gatewayname = ucfirst(str_replace('plugpayment', '', $gateway->name));
			$gatewayselect[] = JHtml::_('select.option', $gateway->element, $gatewayname);
		}
		?>
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('amount'); ?></div>
			<div class="controls" id="amount">
				<div class="input-append">
					<?php echo $this->form->getInput('amount'); ?>
					<span class="add-on"><?php echo $params->get('currency');?></span>
				</div>
			</div>
		</div>
		<div class="control-group" id="showCoupon">
			<div class="control-label"><?php echo $this->form->getLabel('coupon_result'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('coupon_result'); ?></div>
		</div>
		<div class="control-group" id = "couponHide">
			<div class="control-label"><?php echo $this->form->getLabel('coupon_code'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('coupon_code'); ?>
				<button type="button" class="btn btn-success" onclick="sa.payment.applyCoupon()">
					<?php echo JText::_('COM_SOCIALADS_PAYMENT_COUPON_APPLY');?>
				</button>
			</div>
		</div>
		<div id="coupon_discount" class="control-group hidePaymentFields">
			<label class="control-label"><?php echo JText::_('COM_SOCIALAD_PAYMENT_COUPON_DISCOUNT');?></label>
				<div id="coupon_value" class="controls qtc_controls_text">
				</div>
		</div>

		<div id="dis_amt1" class="control-group hidePaymentFields">
			<label class="control-label"><?php echo JText::_('COM_SOCIALAD_PAYMENT_FINAL_AMOUNT');?></label>
			<div id="dis_amt" class="controls qtc_controls_text">
			</div>
		</div>

		<div class="control-group" id="pay_gateway">
			<label class="control-label">
				<?php echo JText::_('COM_SOCIALAD_PAYMENT_SELECT_PAYMENT_GATEWAY');?></label>
			<?php if(JVERSION > 3.0){  ?>
			<div class="controls">
				<?php  } ?>
				<?php
			if(!empty($gatewayselect))
				echo JHtml::_('select.radiolist', $gatewayselect, "payment_gateway", 'onclick="sa.payment.makePayment(this.value)"', "value", "text");

			else
				echo JText::_('COM_SOCIALAD_PAYMENT_NO_GATEWAY_PLUG');

			if(JVERSION > 3.0)
			{ ?>
				</div>
			<?php
			} ?>
		</div>
		<div class="control-group hidePaymentFields" id="coupon_div">
			<div class="controls" id="coupon">
			</div>
		</div>

	<!--
	//@TODO- CHANGES NEED FOR RECURRING
		<input type="hidden" name="arb_flag" id="arb_flag" value="<?php //echo  ($this->sa_recuring == '1' || $params->get('recurring_payments') == '1')? '1': '0'; ?>">
	-->
		<input type="hidden" name="option" value="com_socialads" />
		<input type="hidden" name="controller" value="" />
		<input type="hidden" name="task" value="save" />


	<!--
	//@TODO- cHANGES NEED FOR RECURRING
		<input type="hidden" name="arb_flag" id="arb_flag" value="<?php //echo  ($this->sa_recuring == '1' || $params->get('recurring_payments')== '1')? '1': '0'; ?>">
	-->
		<?php echo JHtml::_('form.token'); ?>
	</form>
	<div id="html-container" ></div>
</div>
