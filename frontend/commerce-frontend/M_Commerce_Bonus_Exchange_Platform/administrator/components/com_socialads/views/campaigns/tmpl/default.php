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

if (JVERSION >= '3.0')
{
	JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
	JHtml::_('bootstrap.tooltip');
	JHtml::_('behavior.multiselect');
	JHtml::_('formbehavior.chosen', 'select');
}

$user      = JFactory::getUser();
$userId    = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
$canOrder  = $user->authorise('core.edit.state', 'com_socialads');
$saveOrder = $listOrder == 'a.ordering';

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_socialads&task=campaigns.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'campaignList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

if (!empty($this->extra_sidebar))
{
	$this->sidebar .= $this->extra_sidebar;
}
?>
<div class="<?php echo SA_WRAPPER_CLASS;?> sa-ad-campagins">
	<?php
	if (!empty($this->sidebar)):
	?>
		<div id="j-sidebar-container" class="span2">
			<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span10">
	<?php
	else :
	?>
		<div id="j-main-container">
	<?php
	endif;
	?>
	<form action="<?php echo JRoute::_('index.php?option=com_socialads&view=campaigns'); ?>" method="post"
		name="adminForm" id="adminForm">
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('COM_SOCIALADS_CAMPAIGNS_FILTER_SEARCH'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_SOCIALADS_CAMPAIGNS_FILTER_SEARCH'); ?>" />
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
					echo JHtml::_('select.genericlist', $this->createdbyoptions, "filter_usernamelist", 'class="ad-status inputbox input-medium" size="1" onchange="document.adminForm.submit();" name="usernamelist"', "value", "text", $this->state->get('filter.usernamelist'));
				?>
			</div>
			<?php endif; ?>
				<div class="btn-group pull-right hidden-phone">
					<?php
						echo JHtml::_('select.genericlist', $this->publish_states, "filter_published", 'class="input-medium" size="1" onchange="document.adminForm.submit();" name="filter_published"', "value", "text", $this->state->get('filter.state'));
					?>
				</div>
		</div>
		<div class="clearfix">&nbsp;</div>
		<?php
		if (empty($this->items)) : ?>
			<div class="clearfix">&nbsp;</div>
			<div class="alert alert-no-items">
				<?php echo JText::_('COM_SOCIALADS_FILTER_SEARCH_NOT_FOUND'); ?>
			</div>
			<?php
		else : ?>
			<div id = "no-more-tables">
				<table class="table table-striped" id="campaignList">
					<thead>
						<tr>
							<?php
							if (isset($this->items[0]->ordering)): ?>
								<th width="1%" class="nowrap center hidden-phone">
									<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
								</th>
							<?php
							endif; ?>
							<th width="1%" class="hidden-phone">
								<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
							</th>
							<?php
							if (isset($this->items[0]->state)): ?>
								<th width="1%" class="nowrap center">
									<?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
								</th>
							<?php endif; ?>
								<th class='left'>
									<?php echo JHtml::_('grid.sort', 'COM_SOCIALADS_CAMPAIGNS_CAMPAIGN', 'a.campaign', $listDirn, $listOrder); ?>
								</th>
								<th class='left'>
									<?php echo JHtml::_('grid.sort', 'COM_SOCIALADS_CAMPAIGNS_CREATED_BY', 'u.name', $listDirn, $listOrder); ?>
								</th>
								<th class='left'>
									<?php echo JHtml::_('grid.sort', 'COM_SOCIALADS_CAMPAIGNS_DAILY_BUDGET', 'a.daily_budget', $listDirn, $listOrder); ?>
								</th>
								<th class='left'>
									<?php echo JHtml::_('grid.sort', 'COM_SOCIALADS_CAMPAIGNS_NO_OF_ADS', 'no_of_ads', $listDirn, $listOrder); ?>
								</th>
								<th class='left'>
									<?php echo JHtml::_('grid.sort', 'COM_SOCIALADS_CAMPAIGNS_CLICKS', 'clicks', $listDirn, $listOrder); ?>
								</th>
								<th class='left'>
									<?php echo JHtml::_('grid.sort', 'COM_SOCIALADS_CAMPAIGNS_IMPRESSIONS', 'impressions', $listDirn, $listOrder); ?>
								</th>
								<th class='left'>
									<?php echo JHtml::_('grid.sort', 'COM_SOCIALADS_CAMPAIGNS_CLICK_THROUGH_RATIO', 'impressions', $listDirn, $listOrder); ?>
								</th>

							<?php if (isset($this->items[0]->id)): ?>
								<th width="1%" class="nowrap center hidden-phone">
									<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
								</th>
							<?php endif; ?>
						</tr>
					</thead>
					<tfoot>
						<?php
							if(isset($this->items[0]))
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
						<?php foreach ($this->items as $i => $item) :
							$ordering = ($listOrder == 'a.ordering');
							$canCreate	= $user->authorise('core.create',		'com_socialads');
							$canEdit	= $user->authorise('core.edit',			'com_socialads');
							$canCheckin	= $user->authorise('core.manage',		'com_socialads');
							$canChange	= $user->authorise('core.edit.state',	'com_socialads');
						?>
						<tr class="row<?php echo $i % 2; ?>">
							<?php if (isset($this->items[0]->ordering)): ?>
								<td class="order nowrap center hidden-phone">
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
								<td class="hidden-phone">
									<?php echo JHtml::_('grid.id', $i, $item->id); ?>
								</td>
							<?php if (isset($this->items[0]->state)): ?>
								<td class="center" data-title="<?php echo JText::_('JSTATUS');?>">
									<?php echo JHtml::_('jgrid.published', $item->state, $i, 'campaigns.', $canChange, 'cb'); ?>
								</td>
							<?php endif; ?>
								<td data-title="<?php echo JText::_('COM_SOCIALADS_CAMPAIGNS_CAMPAIGN');?>">
									<?php if (isset($item->checked_out) && $item->checked_out) : ?>
										<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'campaigns.', $canCheckin); ?>
									<?php endif; ?>
									<?php if ($canEdit) : ?>
										<a href="<?php echo JRoute::_('index.php?option=com_socialads&task=campaign.edit&id='.(int) $item->id); ?>">
											<?php echo $this->escape($item->campaign); ?>
										</a>
									<?php else : ?>
										<?php echo $this->escape($item->campaign); ?>
									<?php endif; ?>
								</td>
								<td data-title="<?php echo JText::_('COM_SOCIALADS_CAMPAIGNS_CREATED_BY');?>">
									<?php echo $item->uname; ?>
								</td>
								<td data-title="<?php echo JText::_('COM_SOCIALADS_CAMPAIGNS_DAILY_BUDGET');?>">
									<?php echo $item->daily_budget; ?>
								</td>
								<td data-title="<?php echo JText::_('COM_SOCIALADS_CAMPAIGNS_NO_OF_ADS');?>">
									<?php if ($item->no_of_ads)
											{
									?>
												<a href="index.php?option=com_socialads&view=forms&filter_campaignslist=<?php echo $item->id; ?>" title="<?php echo JText::_('COM_SOCIALADS_CLICK_TO_VIEW_ADS'); ?>"><?php echo $item->no_of_ads; ?></a>
									<?php	}
											else
											{
												echo "0";
											}
									?>

								</td>
								<td data-title="<?php echo JText::_('COM_SOCIALADS_CAMPAIGNS_CLICKS');?>">
									<?php
									if($item->clicks > 0)
									{
										echo $item->clicks;
									}
									else
									{
										echo "0";
									}
									?>
								</td>
								<td data-title="<?php echo JText::_('COM_SOCIALADS_CAMPAIGNS_IMPRESSIONS');?>">
									<?php
									if($item->impressions > 0)
									{
										echo $item->impressions;
									}
									else
									{
										echo "0";
									}
									?>
								</td>
								<td data-title="<?php echo JText::_('COM_SOCIALADS_CAMPAIGNS_CLICK_THROUGH_RATIO');?>">
									<?php
									$ctr = 0;
									if ($item->impressions != 0)
									{
										$ctr = ($item->clicks) / ($item->impressions);
										echo number_format($ctr, 6);
									}
									else
									{
										echo number_format($ctr, 6);
									}
									?>
								</td>
								<?php
								if (isset($this->items[0]->id)): ?>
									<td class="hidden-phone">
										<?php echo (int) $item->id; ?>
									</td>
								<?php
								endif; ?>
							</tr>
						<?php
						endforeach; ?>
					</tbody>
				</table>
			</div>
			<?php
			endif; ?>
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>

<script type="text/javascript">
	var tjListOrderingColumn = "<?php echo $listOrder; ?>";
	saAdmin.initSaJs();
	Joomla.submitbutton = function(action){saAdmin.campaigns.submitButtonAction(action);}
</script>
