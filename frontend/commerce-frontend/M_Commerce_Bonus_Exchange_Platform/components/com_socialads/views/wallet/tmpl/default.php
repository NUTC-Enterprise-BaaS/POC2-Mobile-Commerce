<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Socialads
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.formvalidation');
?>
<div class="<?php echo SA_WRAPPER_CLASS;?>" id="sa-wallet">
	<?php
	$payment_mode = $this->params->get('payment_mode');

	if ($payment_mode == 'pay_per_ad_mode')
	{
		?>
		<div class="alert alert-block">
			<?php echo JText::_('COM_SOCIALADS_WALLET_NO_AUTH_SEE'); ?>
		</div>
		<?php
		return false;
	}

	$statesticsInformation = $this->wallet[0];

	// $pay_info = $this->wallet[0];
	$campaignName = $this->wallet[1];
	$coupon_code = $this->wallet[2];

	// Get coupon code array
	$adTitle = $this->wallet[3];

	// Newly added for JS toolbar inclusion
	$jomsocialToolbarExist = $this->params->get('jomsocial_toolbar');

	if (file_exists(JPATH_SITE . '/components/com_community') and $jomsocialToolbarExist == 1)
	{
		require_once JPATH_ROOT .'/components/com_community/libraries/toolbar.php';
		$toolbar = CFactory::getToolbar();
		$tool = CToolbarLibrary::getInstance();
		?>
		<script src="<?php echo JUri::root() . 'components/com_community/assets/bootstrap/bootstrap.min.js'; ?>" type="text/javascript"></script>
		<div id="proimport-wrap">
			<div id="community-wrap">
				<?php echo $tool->getHTML(); ?>
			</div>
		</div>
		<!-- End of JS tool bar import div -->
		<?php
	}
	// End for JS toolbar inclusion
	?>
	<form action="" method="post" name="adminForm3" id="adminForm3">
		<div class="page-header">
			<h2><?php echo JText::_('COM_SOCIALADS_WALLET');?></h2>
		</div>
		<div class="btn-toolbar wallet_filter">
			<div class="pull-left btn-group">
				<?php
				echo JHtml::_('select.genericlist', $this->month, 'month', 'name="filter_order" class = "input-small"', "value", "text", $this->lists['month']);
				echo JHtml::_('select.genericlist', $this->year, 'year', 'name="filter_order" class = "input-small"', "value", "text", $this->lists['year']);
				?>
			</div>
			<div class="pull-left btn-group">
				<button type="button" name="go" title="<?php echo JText::_('COM_SOCIALADS_GO'); ?>" class="btn btn-success" id="go" onclick="this.form.submit();">
					<?php echo JText::_('COM_SOCIALADS_GO'); ?>
				</button>
			</div>
		</div>
		<div class="clearfix">&nbsp;</div>
		<div>
			<ul class="nav nav-tabs" id="AdWalletTab">
				<li class="active" ><a href="#spent_table" data-toggle="tab"><?php echo JText::_('COM_SOCIALADS_WALLET_ACCOUNT_HISTORY'); ?></a></li>
				<li><a href="#pay_table" data-toggle="tab"><?php echo JText::_('COM_SOCIALADS_WALLET_PAYMENT_CREDTIS_ONLY'); ?></a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane table-responsive" id="pay_table">
					<div id='no-more-tables'>
					<?php
					if (empty($statesticsInformation)) : ?>
						<div class="clearfix">&nbsp;</div>
						<div class="alert alert-no-items">
							<?php echo JText::_('COM_SOCIALADS_FILTER_SEARCH_NOT_FOUND'); ?>
						</div>
					<?php
					else : ?>
						<table class="table table-condensed ">
							<thead>
								<tr>
									<th>
										<?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_WALLET_DATE_WISE_RECORD'), '', '', JText::_('COM_SOCIALADS_WALLET_DATE')); ?>
									</th>
									<th>
										<?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_WALLET_PAYMENT_DONE'), '', '', JText::_('COM_SOCIALADS_WALLET_DESCRIPTION')); ?>
									</th>
									<th>
										<?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_WALLET_PAYMENT_AMOUNT'), '', '',
										JText::sprintf('COM_SOCIALADS_WALLET_PAYMENT', $this->params->get('currency'))); ?>
									</th>
								</tr>
							</thead>
							<tbody>
							<?php
							$totalCredits = 0;
							foreach ($statesticsInformation as $key)
							{
								$comment = explode('|', $key->comment);

								if (!empty($key->credits) && $key->credits != 0.00)
								{
									?>
									<tr>
										<td data-title="<?php echo JText::_('COM_SOCIALADS_WALLET_DATE_WISE_RECORD');?>">
											<?php echo $key->time; ?>
										</td>
										<td data-title="<?php echo JText::_('COM_SOCIALADS_WALLET_DESCRIPTION');?>">
											<?php
											if ($comment[0] == 'COM_SOCIALADS_WALLET_ADS_PAYMENT')
											{
												echo JText::_('COM_SOCIALADS_WALLET_ADS_PAYMENT');
											}
											elseif ($comment[0] == 'COM_SOCIALADS_WALLET_VIA_MIGRATTION')
											{
												foreach ($adTitle as $index => $value)
												{
													if (isset($comment[1]) && $index == $comment[1])
													{
														echo JText::sprintf('COM_SOCIALADS_WALLET_VIA_MIGRATTION', $value);
													}
												}
											}
											elseif ($comment[0] == 'COM_SOCIALADS_WALLET_COUPON_ADDED')
											{
												foreach ($coupon_code as $index => $value)
												{
													if ($index == $key->type_id)
													{
														$coupon_msg = JText::sprintf('COM_SOCIALADS_WALLET_COUPON_ADDED', $value);
														echo $coupon_msg;
													}
												}
											}
											?>
										</td >
										<td data-title="<?php echo JText::_('COM_SOCIALADS_WALLET_PAYMENT_AMOUNT');?>">
											<?php
											echo SaCommonHelper::getFormattedPrice($key->credits);
											$totalCredits = $totalCredits + $key->credits;
											?>
										</td>
									</tr>
								<?php
								}
							}
							?>
								<?php if ($totalCredits > 0):?>
									<tr>
										<td colspan = "2">
											<div class="pull-right">
												<strong><?php echo JText::_('COM_SOCIALADS_TOTAL_PAYMENT');?></strong>
											</div>
										</td>
										<td data-title="<?php echo JText::_('COM_SOCIALADS_TOTAL_PAYMENT');?>">
											<strong><?php echo SaCommonHelper::getFormattedPrice($totalCredits);?></strong>
										</td>
									</tr>
								<?php endif;?>
							</tbody>
						</table>
					<?php
					endif; ?>
					</div>
				</div>
				<div class="tab-pane active table-responsive" id="spent_table">
					<div id='no-more-tables'>
					<?php
					if (empty($statesticsInformation)) : ?>
						<div class="clearfix">&nbsp;</div>
						<div class="alert alert-no-items">
							<?php echo JText::_('COM_SOCIALADS_FILTER_SEARCH_NOT_FOUND'); ?>
						</div>
					<?php
					else : ?>
						<table class="table table-condensed ">
							<thead>
								<tr>
									<th>
										<?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_WALLET_DATE_WISE_RECORD'), '', '', JText::_('COM_SOCIALADS_WALLET_DATE'));?>
									</th>
									<th>
										<?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_WALLET_DESCRIPTION'), '', '', JText::_('COM_SOCIALADS_WALLET_DESCRIPTION'));?>
									</th>
									<th>
										<?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_WALLET_PAYMENT_AMOUNT'), '', '',
											JText::_('COM_SOCIALADS_WALLET_PAYMENT')); ?>
									</th>
									<th>
										<?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_WALLET_TOTAL_SPENT'),'', '', JText::_('COM_SOCIALADS_WALLET_TOTAL_SPENT')); ?>
									</th>

									<th>
										<?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_WALLET_BALANCE_REMAINING'), '', '', JText::_('COM_SOCIALADS_WALLET_BALANCE')); ?>
									</th>
								</tr>
							</thead>
							<?php
							$balance = 0;

							foreach ($statesticsInformation as $key)
							{
								$comment = explode('|', $key->comment); ?>
								<tr>
									<td style="width:15%" data-title="<?php echo JText::_('COM_SOCIALADS_WALLET_DATE_WISE_RECORD');?>">
										<?php echo $key->time; ?>
									</td>
									<td data-title="<?php echo JText::_('COM_SOCIALADS_WALLET_DESCRIPTION');?>">
										<?php
										if ($comment[0] == 'COM_SOCIALADS_WALLET_SPENT_DONE_FROM_MIGRATION')
										{
											foreach ($adTitle as $index => $value)
											{
												if (isset($comment[1]) && $index == $comment[1])
												{
													echo JText::sprintf('COM_SOCIALADS_WALLET_SPENT_DONE_FROM_MIGRATION', $value);
												}
											}
										}
										elseif ($comment[0] == 'COM_SOCIALADS_WALLET_ADS_PAYMENT')
										{
											echo JText::_('COM_SOCIALADS_WALLET_ADS_PAYMENT');
										}
										elseif ($comment[0] == 'COM_SOCIALADS_WALLET_VIA_MIGRATTION')
										{
											foreach($adTitle as $index => $value)
											{
												if (isset($comment[1]) && $index == $comment[1])
												{
													echo JText::sprintf('COM_SOCIALADS_WALLET_VIA_MIGRATTION', $value);
												}
											}
										}
										elseif($comment[0] == 'COM_SOCIALADS_WALLET_COUPON_ADDED')
										{
											foreach ($coupon_code as $index => $value)
											{
												if ($index == $key->type_id)
												{
													$coupon_msg = JText::sprintf('COM_SOCIALADS_WALLET_COUPON_ADDED', $value);
													echo $coupon_msg;
												}
											}
										}
										elseif('COM_SOCIALADS_WALLET_DAILY_CLICK_IMP')
										{
											foreach ($campaignName as $index => $value)
											{
												if ($index == $key->type_id)
												{
													$spent_msg = JText::sprintf('COM_SOCIALADS_WALLET_DAILY_CLICK_IMP', $value);
													echo $spent_msg;
												}
											}
										}
									?>
									</td>

									<td style="width:10%" data-title="<?php echo JText::_('COM_SOCIALADS_WALLET_PAYMENT_AMOUNT');?>">
										<?php echo SaCommonHelper::getFormattedPrice($key->credits); ?>
									</td>

									<td style="width:10%" data-title="<?php echo JText::_('COM_SOCIALADS_WALLET_TOTAL_SPENT');?>">
										<?php echo SaCommonHelper::getFormattedPrice($key->spent);?>
									</td>

									<td style="width:10%" data-title="<?php echo JText::_('COM_SOCIALADS_WALLET_BALANCE');?>">
										<?php echo SaCommonHelper::getFormattedPrice($key->balance); ?>
									</td>
								</tr>
								<?php
							}
							?>
						</table>
					<?php
					endif; ?>
					</div>
				</div>
			</div>
		</div>
		<div class="form-actions" >
			<div class="form-inline">
				<input type="button" class="btn btn-success" value="<?php echo JText::_('COM_SOCIALADS_AD_PAYMENT'); ?>" onclick="sa.wallet.addPayment(); " />
				<label title="<?php echo JText::_('COM_SOCIALADS_WALLET_DESC_REDEEM_COUPON'); ?>">
				<?php echo JText::_('COM_SOCIALADS_WALLET_REDEEM_COUPON'); ?></label>
				<input id="coupon_code" type="text" class="input-mini" name="coupon" placeholder="code" />
				<input type="button" class="btn btn-primary" id="add_coupon" value="<?php echo JText::_('COM_SOCIALADS_SUBMIT'); ?>" onclick="sa.wallet.applyCouponCode(); "/>
			</div>
		</div>
		<input type="hidden" name="option" value="com_socialads" />
	</form>
</div>
