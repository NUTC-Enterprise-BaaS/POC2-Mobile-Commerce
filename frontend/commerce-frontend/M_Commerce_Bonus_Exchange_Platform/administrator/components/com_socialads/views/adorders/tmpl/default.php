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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

if (JVERSION > '3.0')
{
	JHtml::_('formbehavior.chosen', 'select');
}

$user	= JFactory::getUser();
$userId	= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$canOrder	= $user->authorise('core.edit.state', 'com_socialads');
$saveOrder	= $listOrder == 'a.ordering';

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_socialads&task=adorders.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'List', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

$sortFields = $this->getSortFields();
$totalamount = 0;

if (!empty($this->extra_sidebar))
{
	$this->sidebar .= $this->extra_sidebar;
}
?>
<div class="<?php echo SA_WRAPPER_CLASS;?> sa-ad-order">
	<?php
	if(!empty($this->sidebar)): ?>
		<div id="j-sidebar-container" class="span2">
			<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span10">
		<?php
	else : ?>
		<div id="j-main-container">
	<?php
	endif;?>
			<form action="<?php echo JRoute::_('index.php?option=com_socialads&view=adorders'); ?>" method="post" name="adminForm" id="adminForm">
				<div id="filter-bar" class="btn-toolbar">
					<div class="filter-search btn-group pull-left">
						<input type="text" name="filter_search" id="filter_search"
						placeholder="<?php echo JText::_('COM_SOCIALADS_AD_ORDERS_FILTER_SEARCH'); ?>"
						value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
						title="<?php echo JText::_('COM_SOCIALADS_AD_ORDERS_FILTER_SEARCH'); ?>" />
					</div>

					<div class="btn-group pull-left">
						<button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>">
							<i class="icon-search"></i>
						</button>
						<button class="btn hasTooltip" id="clear-search-button" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>">
							<i class="icon-remove"></i>
						</button>
					</div>

					<?php
					if (JVERSION >= '3.0') : ?>
						<div class="btn-group pull-right hidden-phone">
							<label for="limit" class="element-invisible">
								<?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?>
							</label>
							<?php echo $this->pagination->getLimitBox(); ?>
						</div>
						<div class="btn-group pull-right hidden-phone">
							<?php
								$payment_status = $this->state->get('filter.status');
								echo JHtml::_('select.genericlist', $this->ostatus, "filter.status", 'class="input-medium" size="1" onchange="document.adminForm.submit();" name="filter_status"', "value", "text", $this->state->get('filter.status'));
								?>
						</div>

						<div class="btn-group pull-right hidden-phone">
							<?php
								echo JHtml::_('select.genericlist', $this->gatewayoptions, "filter_gatewaylist", 'class="ad-status inputbox input-medium" size="1" onchange="document.adminForm.submit();" name="gatewaylist"', "value", "text", $this->state->get('filter.gatewaylist'));
							?>
						</div>
					<?php
					endif; ?>
				</div>

				<div class="clearfix"> </div>
				<?php
				if (empty($this->items)) : ?>
					<div class="clearfix">&nbsp;</div>
					<div class="alert alert-no-items">
						<?php echo JText::_('COM_SOCIALADS_FILTER_SEARCH_NOT_FOUND'); ?>
					</div>

				<?php
				else : ?>
					<div id='no-more-tables'>
						<table class="table table-striped" id="List">
							<thead>
								<tr>
									<?php if (isset($this->items[0]->ordering)): ?>
										<th class="tj-width-1 nowrap center hidden-phone">
											<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
										</th>
									<?php endif; ?>

									<th class="tj-width-1 hidden-phone">
										<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
									</th>

									<?php if (isset($this->items[0]->id)): ?>
										<th class="tj-width-1 nowrap center hidden-phone">
											<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'o.prefix_oid', $listDirn, $listOrder); ?>
										</th>
									<?php endif; ?>
									<th class='tj-width-5'>
										<?php echo JHtml::_('grid.sort',  'COM_SOCIALADS_ADORDERS_AD_ID', 'd.ad_id', $listDirn, $listOrder); ?>
									</th>
									<th class='tj-width-5'>
										<?php echo JHtml::_('grid.sort',  'COM_SOCIALADS_ADORDERS_AD_TITLE', 'd.ad_title', $listDirn, $listOrder); ?>
									</th>
									<th class='tj-width-5'>
										<?php echo JHtml::_('grid.sort',  'COM_SOCIALADS_ADORDERS_AD_CREDITS_QTY', 'p.ad_credits_qty', $listDirn, $listOrder); ?>
									</th>
									<th class='tj-width-5'>
										<?php echo JHtml::_('grid.sort',  'COM_SOCIALADS_ADORDERS_CDATE', 'o.cdate', $listDirn, $listOrder); ?>
									</th>

									<th class='tj-width-5'>
										<?php echo JHtml::_('grid.sort',  'COM_SOCIALADS_ADORDERS_STATUS', 'o.status', $listDirn, $listOrder); ?>
									</th>

									<th class='tj-width-5'>
										<?php echo JHtml::_('grid.sort',  'COM_SOCIALADS_ADORDERS_AD_TYPE', 'd.ad_payment_type', $listDirn, $listOrder); ?>
									</th>

									<th class='tj-width-5'>
										<?php echo JHtml::_('grid.sort',  'COM_SOCIALADS_ADORDERS_USERNAME', 'u.username', $listDirn, $listOrder); ?>
									</th>
									<th class='tj-width-5'>
										<?php echo JHtml::_('grid.sort',  'COM_SOCIALADS_ADORDERS_PROCESSOR', 'o.processor', $listDirn, $listOrder); ?>
									</th>

									<th class='tj-width-5'>
										<?php echo JHtml::_('grid.sort',  'COM_SOCIALADS_ADORDERS_AD_AMOUNT', 'o.amount', $listDirn, $listOrder); ?>
									</th>
								</tr>
							</thead>

							<tfoot>
								<?php
									if (isset($this->items[0]))
									{
										$colspan = count(get_object_vars($this->items[0]));
									}
									else
									{
										$colspan = 10;
									}
								?>
								<tr>
									<td colspan="<?php echo $colspan ?>">
										<?php echo $this->pagination->getListFooter(); ?>
									</td>
								</tr>
							</tfoot>

							<tbody>
								<?php
								$k = 0;
								foreach ($this->items as $i => $item) :
									$ordering   = ($listOrder == 'a.ordering');
									$canCreate	= $user->authorise('core.create',		'com_socialads');
									$canEdit	= $user->authorise('core.edit',			'com_socialads');
									$canCheckin	= $user->authorise('core.manage',		'com_socialads');
									$canChange	= $user->authorise('core.edit.state',	'com_socialads');
								?>
								<?php
								$whichever = '';
								$row_color = '';

								 switch ($item->status)
								 {
										case 'C' :
											$whichever =  JText::_('COM_SOCIALADS_ADORDERS_AD_CONFIRM');
											$row_color = "success";
										break;
										case 'RF' :
											$whichever = JText::_('COM_SOCIALADS_ADORDERS_AD_REFUND');
											$row_color = "error";
										break;
										case 'P' :
											$whichever = JText::_('COM_SOCIALADS_ADORDERS_AD_PENDING');
											$row_color = "error";
										break;
										case 'E' :
											$whichever = JText::_('COM_SOCIALADS_ADORDERS_AD_CANCEL') ;
											$row_color = "error";
										break;
										default:
											$whichever = $item->status;
											break;
								 }
								?>
								<tr class="row<?php echo $i % 2; ?> <?php echo $row_color; ?>">
									<?php if (isset($this->items[0]->ordering)): ?>
										<td class="order nowrap center hidden-phone" data-title="<?php echo JText::_('');?>">
											<?php if ($canChange) :
													$disableClassName = '';
													$disabledLabel	  = '';

														if (!$saveOrder) :
															$disabledLabel    = JText::_('JORDERINGDISABLED');
															$disableClassName = 'inactive tip-top';
														endif; ?>

														<span class="sortable-handler hasTooltip <?php echo $disableClassName?>" title="<?php echo $disabledLabel?>">
															<i class="icon-menu"></i>
														</span>
														<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering;?>" class="width-20 text-area-order " />
											<?php else : ?>
													<span class="sortable-handler inactive" >
														<i class="icon-menu"></i>
													</span>
											<?php endif; ?>
										</td>
									<?php endif; ?>

									<td class="hidden-phone" data-title="<?php echo JText::_('id');?>">
										<?php echo JHtml::_('grid.id', $i, $item->id); ?>
									</td>

									<?php if (isset($this->items[0]->id)): ?>
										<td data-title="<?php echo JText::_('JGRID_HEADING_ID');?>">
											<?php
											$link = JRoute::_('index.php?option=com_socialads&view=adorders&layout=details&id=' . $item->id);
											if ($item->comment != "AUTO_GENERATED")
											{?>
												<a href="<?php echo $link;?>" rel="{handler: 'iframe', size: {x: 800, y: 350}}" class="modal">
													<?php echo (!empty($item->prefix_oid)?$item->prefix_oid:$item->id); ?>
												</a>
											<?php
											}
											else
											{
												echo (!empty($item->prefix_oid)?$item->prefix_oid:$item->id);
											}
											?>
										</td>
									<?php endif; ?>
									<td data-title="<?php echo JText::_('COM_SOCIALADS_ADORDERS_AD_ID');?>">
										<?php echo $item->ad_id; ?>
									</td>
									<td data-title="<?php echo JText::_('COM_SOCIALADS_ADORDERS_AD_TITLE');?>">
										<?php echo $item->ad_title; ?>
									</td>
									<td data-title="<?php echo JText::_('COM_SOCIALADS_ADORDERS_AD_CREDITS_QTY');?>">
									<?php
										if ($item->ad_payment_type == 2){ ?>
										<img src="<?php echo JUri::root(true).'/media/com_sa/images/start_date.png' ?>">
										<?php echo $item->ad_startdate; ?>
										<br/><img src="<?php echo JUri::root(true).'/media/com_sa/images/end_date.png' ?>">
										<?php echo $item->ad_enddate;
									}
									else
										echo $item->ad_credits_qty; ?>
									</td>
									<td data-title="<?php echo JText::_('COM_SOCIALADS_ADORDERS_CDATE');?>">
										<?php echo $item->cdate; ?>
									</td>

									<td data-title="<?php echo JText::_('COM_SOCIALADS_ADORDERS_STATUS');?>">
										<?php
											if($item->status == 'P' || $item->status == 'C' || $item->status == 'RF' || $item->status == 'E')
												echo JHtml::_('select.genericlist',$this->pstatus,'pstatus'.$k,'class="pad_status"  onChange="saAdmin.adorders.selectstatusorder('.$item->id.',this);"',"value","text",$item->status);
											else
												echo $whichever ;
										?>
									</td>

									<td data-title="<?php echo JText::_('COM_SOCIALADS_ADS_AD_TYPE_ALT_AD');?>">
										<?php
											if ($item->ad_payment_type == '')
											{
												echo "--";
											}
											elseif ($item->ad_payment_type == 0)
											{
												echo JText::_('COM_SOCIALADS_ADS_AD_TYPE_IMPRS');
											}
											elseif($item->ad_payment_type == 1)
											{
												echo JText::_('COM_SOCIALADS_ADS_AD_TYPE_CLICKS');
											}
											else
											{
												echo JText::_('COM_SOCIALADS_ADS_AD_TYPE_PERDATE');
											}
										?>
									</td>


									<td data-title="<?php echo JText::_('COM_SOCIALADS_ADORDERS_PROCESSOR');?>">
										<?php echo $item->username;?>
									</td>

									<td data-title="<?php echo JText::_('COM_SOCIALADS_ADORDERS_PROCESSOR');?>">
									<?php
									if ($item->processor)
									{
										echo $item->processor;
									}
									elseif ($item->amount == 0 && !empty($item->coupon) )
									{
										echo JText::_('COM_SOCIALADS_ADORDERS_VIA_COUPON');
									}
									elseif ($item->comment == "AUTO_GENERATED")
									{
										echo JText::_('COM_SOCIALADS_ADORDERS_VIA_MIGRATION');
									}?>
									</td>

									<td data-title="<?php echo JText::_('COM_SOCIALADS_ADORDERS_AD_AMOUNT');?>">
										<?php echo SaCommonHelper::getFormattedPrice($item->amount);?>
										<?php $totalamount=$totalamount+$item->amount;?>
									</td>
								</tr>
								<?php endforeach; ?>

								<tr>
									<td colspan="9"></td>

									<td>
										<b><?php echo JText::_('COM_SOCIALADS_TOTAL'); ?></b>
									</td>

									<td>
										<b><?php echo SaCommonHelper::getFormattedPrice($totalamount);?></b>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				<?php
				endif; ?>
				<input type="hidden" name="task" value="" />
				<input type="hidden" name="boxchecked" value="0" />
				<input type="hidden" id='hidid' name="id" value="" />
				<input type="hidden" id='hidstat' name="status" value="" />
				<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
				<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
				<?php echo JHtml::_('form.token'); ?>
			</form>
		</div>
</div>

<script type="text/javascript">
	var tjListOrderingColumn = "<?php echo $listOrder; ?>";
	saAdmin.initSaJs();
</script>
