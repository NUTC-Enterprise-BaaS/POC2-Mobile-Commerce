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
JHtml::_('behavior.formvalidation');
?>
<div class="<?php echo SA_WRAPPER_CLASS; ?>" id="sa-wallet">
	<form action="" method="post" name="adminForm3" id="adminForm3">
		<div class="page-header">
			<h2><?php echo JText::_('COM_SOCIALADS_WALLET_DETAIL_TITLE');?></h2>
		</div>
		<div class="btn-toolbar wallet_filter">
			<div class = "pull-left btn-group">
				<?php
				echo JHtml::_('select.genericlist', $this->month, 'month', 'name="filter_order" class = "input-small"', "value", "text", $this->lists['month']);
				echo JHtml::_('select.genericlist', $this->year, 'year', 'name="filter_order" class = "input-small"', "value", "text", $this->lists['year']);?>
			</div>
			<div class = "pull-left btn-group">
				<button type="button" name="go" title="<?php echo JText::_('COM_SOCIALADS_GO'); ?>" class="btn btn-success" id="go" onclick="this.form.submit();">
					<?php echo JText::_('COM_SOCIALADS_GO'); ?>
				</button>
			</div>
		</div>

		<div class = "clearfix">&nbsp;</div>
		<div>
		<ul class="nav nav-tabs" id="AdWalletTab">
			<li class="active">
				<a href="#spent_table" data-toggle="tab">
					<?php echo JText::_('COM_SOCIALADS_WALLET_ACCOUNT_HISTORY'); ?>
				</a>
			</li>
			<li>
				<a href="#pay_table" data-toggle="tab">
					<?php echo JText::_('COM_SOCIALADS_WALLET_PAYMENT_CREDTIS_ONLY'); ?>
				</a>
			</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane table-responsive" id="pay_table">
				<div id='no-more-tables'>
					<?php
					if (empty($this->wallet)) : ?>
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
									JText::_('COM_SOCIALADS_WALLET_PAYMENT')); ?>
								</th>
							</tr>
						</thead>

						<?php
						$statesticsInformation = $this->wallet[0];
						$totalCredits = 0;
						$adTitle = $this->wallet[3];
						$coupon_code = $this->wallet[2];
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
					</table>
				<?php
				endif; ?>
				</div>
			</div>

			<div class="tab-pane active table-responsive" id="spent_table">
				<div id='no-more-tables'>
					<?php
					if (empty($this->wallet)) : ?>
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
										$adTitle = $this->wallet[3];

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
											$coupon_code = $this->wallet[2]; // get coupon code array

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
											$campaignName = $this->wallet[1];

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
	<input type="hidden" name="option" value="com_socialads" />
	</form>
</div>
