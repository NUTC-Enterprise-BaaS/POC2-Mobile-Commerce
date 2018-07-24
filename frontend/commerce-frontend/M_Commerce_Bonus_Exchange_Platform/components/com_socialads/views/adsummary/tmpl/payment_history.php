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
?>
<div class="<?php echo SA_WRAPPER_CLASS;?>" id="sa-payment_history">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div id = "no-more-tables">
				<?php
				if (empty($this->items)) : ?>
					<div class="clearfix">&nbsp;</div>
					<div class="alert alert-no-items">
						<?php echo JText::_('COM_SOCIALADS_FILTER_SEARCH_NOT_FOUND'); ?>
					</div>
				<?php
				else : ?>
					<table class="table table-striped table-bordered table-hover" id="dataList">
						<thead>
							<tr>
								<th class="center">
									<?php
									echo JText::_('COM_SOCIALADS_DATE');
									?>
								</th>
								<th class="center">
									<?php
									echo JText::_('COM_SOCIALADS_TRANSACTION_ID');
									?>
								</th>
								<th class="center">
									<?php
									echo JText::_('COM_SOCIALADS_CREDITS_BOUGHT');
									?>
								</th>
								<th class="center">
									<?php
									echo JText::_('COM_SOCIALADS_AMOUNT');
									?>
								</th>
								<th class="center">
									<?php
									echo JText::_('COM_SOCIALADS_STATUS');
									?>
								</th>
								<th class="center">
									<?php
									echo JText::_('COM_SOCIALADS_PAYMENT_MODE');
									?>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($this->items as $i => $item):
							?>
							<tr>
								<td class="center" data-title="<?php echo JText::_("COM_SOCIALADS_DATE"); ?>">
									<?php
										echo $item->cdate;
									?>
								</td>
								<td class="center" data-title="<?php echo JText::_("COM_SOCIALADS_TRANSACTION_ID"); ?>">
									<?php
									if (empty($item->transaction_id))
									{
										echo JText::_('COM_SOCIALADS_PAYMENT_N_A');
									}
									else
										echo $item->transaction_id;
										?>
								</td>
								<td class="center" data-title="<?php echo JText::_("COM_SOCIALADS_CREDITS_BOUGHT"); ?>">
									<?php echo $item->ad_credits_qty; ?>
								</td>
								<td class="center" data-title="<?php echo JText::_("COM_SOCIALADS_AMOUNT"); ?>">
									<?php
											echo $item->amount;
										?>
								</td>
								<td class="center" data-title="<?php echo JText::_("COM_SOCIALADS_STATUS"); ?>">
									<?php
										if ($item->status == "C")
										{
											echo JText::_('COM_SOCIALADS_SA_CONFIRM');
										}
										else if ($item->status == "P")
										{
											echo JText::_('COM_SOCIALADS_SA_PENDIN');
										}
										else if ($item->status == "RF")
										{
											echo JText::_('COM_SOCIALADS_SA_REFUND');
										}
										else if ($item->status == "E")
										{
											echo JText::_('COM_SOCIALADS_SA_REJECTED');
										}
									?>
							   </td>
								<td class="center" data-title="<?php echo JText::_("COM_SOCIALADS_PAYMENT_MODE"); ?>">
								<?php
									echo $item->processor;
								?>
								</td>
							</tr>
									<?php
							endforeach;
							?>
						</tbody>
					</table>
				<?php
				endif; ?>
			</div>
		</div>
	</div>
</div>
