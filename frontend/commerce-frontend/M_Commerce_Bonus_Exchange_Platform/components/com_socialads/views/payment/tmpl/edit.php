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
<div class="<?php echo SA_WRAPPER_CLASS;?>" id="sa-payment">
	<script type="text/javascript">
		var currency = "<?php echo $params->get('currency')?>";
		var imgpath = "<?php echo JUri::root(true).'/media/com_sa/images/ajax.gif'; ?>";
		var submit = "<?php echo JText::_('COM_SOCIALADS_SUBMIT');?>";
		techjoomla.jQuery(document).ready(function()
		{
			techjoomla.jQuery("#couponHide").hide();

			techjoomla.jQuery(".alphaCheck").keyup(function()
			{
				sa.checkForZeroAndAlpha(this,'46', Joomla.JText._('COM_SOCIALAD_PAYMENT_ENTER_NUMERICS'));
			});

			techjoomla.jQuery("#showCoupon").click(function()
			{
				var rad = techjoomla.jQuery('input[name=coupon_result]:checked').val()
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
		<div class="container-fluid" >
			<div class="row">
				<div class="form-group">
					<div class="col-lg-2 col-md-2 col-sm-3 col-xs-12 control-label">
						<?php echo $this->form->getLabel('amount'); ?>
					</div>
					<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12" id="amount">
						<div class="input-group input-large">
							<?php echo $this->form->getInput('amount'); ?>
							<span class="input-group-addon"><?php echo $params->get('currency');?></span>
						</div>
					</div>
				</div>
				<div class="form-group coupon-display" id="showCoupon">
					<div class="col-lg-2 col-md-2 col-sm-3 col-xs-12 control-label"><?php echo $this->form->getLabel('coupon_result'); ?></div>
<!--
					<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 controls"><?php echo $this->form->getInput('coupon_result');?></div>
-->

			<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 controls">
				<label class="radio-inline">
				  <input type="radio" name="coupon_result" id="coupon_result1" value="1"><?php echo JText::_('JYES');?>
				</label>
				<label class="radio-inline">
				  <input type="radio" name="coupon_result" id="coupon_result0" value="0"><?php echo JText::_('JNO');?>
				</label>
			</div>

				</div>
				<div class="form-group" id="couponHide">
					<div class="col-lg-2 col-md-2 col-sm-3 col-xs-12 control-label"><?php echo $this->form->getLabel('coupon_code'); ?></div>
					<div class="col-lg-6 col-md-6 col-sm-7 col-xs-12"><?php echo $this->form->getInput('coupon_code'); ?>
						<button type="button" class="btn .btn-default btn-success pymentCoupon" onclick="sa.payment.applyCoupon()">
							<?php echo JText::_('COM_SOCIALADS_PAYMENT_COUPON_APPLY');?>
						</button>
					</div>
				</div>
				<div id="coupon_discount" class="form-group hidePaymentFields">
					<label class="col-lg-2 col-md-2 col-sm-3 col-xs-12 control-label"><?php echo JText::_('COM_SOCIALAD_PAYMENT_COUPON_DISCOUNT');?></label>
						<div id="coupon_value" class="col-lg-10 col-md-10 col-sm-9 col-xs-12">
					</div>
				</div>
				<div id="dis_amt1" class="form-group hidePaymentFields">
					<label class="col-lg-2 col-md-2 col-sm-3 col-xs-12 control-label"><?php echo JText::_('COM_SOCIALAD_PAYMENT_FINAL_AMOUNT');?></label>
					<div id="dis_amt" class="col-lg-10 col-md-10 col-sm-9 col-xs-12 qtc_col-lg-10 col-md-10 col-sm-9 col-xs-12_text"></div>
				</div>
				<div class="form-group" id="pay_gateway">
					<label class="col-lg-2 col-md-2 col-sm-3 col-xs-12 control-label">
						<?php echo JText::_('COM_SOCIALAD_PAYMENT_SELECT_PAYMENT_GATEWAY');?>
					</label>
					<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 controls">
							<?php
								if (empty($gatewayselect))
								{
									echo JText::_( 'COM_SOCIALADS_AD_SELECT_PAYMENT_GATEWAY' );
								}
								else
								{
									// Removed selected gateway Bug #26993
									$default = '';
									$imgpath = JUri::root() . "media/com_sa/images/ajax.gif";
									$ad_fun = "onclick='sa.payment.makePayment(this.value)'";

									foreach ($gatewayselect as $gateway)
									{ ?>
										<div class="radio">
										  <label>
											<input type="radio" name="ad_gateways"
												id="<?php echo $gateway->value; ?>"
												value="<?php echo $gateway->value ?>"
												aria-label="..."
												 <?php echo $ad_fun; ?> >
												<?php echo $gateway->text; ?>
										  </label>
										</div>
										<?php
									}

								} ?>
					</div>
				</div>
				<div class="form-group hidePaymentFields" id="coupon_div">
					<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12" id="coupon"> </div>
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
			</div>
		</div>
	</form>
	<div id="html-container"></div>
</div>
