<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
if (JVERSION > '3.0')
{
	JHtml::_('bootstrap.tooltip');
	JHtml::_('formbehavior.chosen', 'select');
}
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
$user       = JFactory::getUser();
$userId     = $user->get('id');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_socialads');
$canEdit    = $user->authorise('core.edit', 'com_socialads');
$canCheckin = $user->authorise('core.manage', 'com_socialads');
$canChange  = $user->authorise('core.edit.state', 'com_socialads');
$canDelete  = $user->authorise('core.delete', 'com_socialads');
$params=JComponentHelper::getParams('com_socialads');
$payment_mode=$params->get('payment_mode');
$canDo = SocialadsHelper::getActions();

if ($payment_mode =='0')
{
?>
	<div class="alert alert-block">
		<?php echo JText::_('COM_SOCIALADS_WALLET_NO_AUTH_SEE'); ?>
	</div>
<?php
	return false;
}
?>
<div class="<?php echo SA_WRAPPER_CLASS;?>" id="sa-wallet">
<form action="<?php echo JRoute::_('index.php?option=com_socialads&view=campaigns'); ?>" method="post" name="adminForm" id="adminForm">
	<div>
		<h1><?php echo JText::_("COM_SOCIALADS_TITLE_CAMPAIGNS");?></h1>
	</div>
	<div class="hidden-xs">
	<?php echo $this->toolbarHTML;?>
	</div>
	<div class="clearfix">
		<div id="filter-bar" class="btn-toolbar">
			<?php
			if (JVERSION >= '3.0'):
			?>
				<div class="btn-group pull-right hidden-phone social-ads-filter-margin-left">
			<?php
				echo $this->pagination->getLimitBox();
			?>
				</div>
			<?php
			endif;
			?>
		<div class="filter-search btn-group">
			<label for="filter_search" class="element-invisible"><?php echo JText::_('JSEARCH_FILTER_SUBMIT');?></label>
			<input type="text" class="pull-left input-medium" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>" />
			<div class="btn-group pull-left">
				<button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button class="btn hasTooltip" id="clear-search-button" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.getElementById('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
			</div>
		</div>
	</div>
	</div>
	<?php if (empty($this->items)):?>
		<div class="alert">
			<?php
				echo JText::_('COM_SOCIALADS_FILTER_SEARCH_NOT_FOUND');
			?>
		</div>
	<?php else:?>
	<div id="no-more-tables" class="table-responsive ads-list">
		<table class="table table-striped" id="campaignList">
			<thead>
				<tr>
					<th width="1%" class="hidden-phone center">
							<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL');?>" onclick="Joomla.checkAll(this)" />
					</th>
					<?php if (isset($this->items[0]->state)): ?>
					<th width="1%" class="center">
						<?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'a.state', $listDirn, $listOrder); ?>
					</th>
					<?php endif; ?>
					<th width="1%" class="center">
						<?php echo JHtml::_('grid.sort',  'COM_SOCIALADS_CAMPAIGNS_CAMPAIGN', 'a.campaign', $listDirn, $listOrder); ?>
					</th>
					<th width="1%" class="center">
						<?php echo JHtml::_('grid.sort',  'COM_SOCIALADS_CAMPAIGNS_DAILY_BUDGET', 'a.daily_budget', $listDirn, $listOrder); ?>
					</th>
					<th width="1%" class="center">
						<?php echo JHtml::_('grid.sort',  'COM_SOCIALADS_CAMPAIGNS_NO_OF_ADS', 'no_of_ads', $listDirn, $listOrder); ?>
					</th>
					<th width="1%" class="center">
						<?php echo JHtml::_('grid.sort',  'COM_SOCIALADS_ADS_AD_NO_OF_CLICKS', 'clicks', $listDirn, $listOrder); ?>
					</th>
					<th width="1%" class="center">
						<?php echo JHtml::_('grid.sort',  'COM_SOCIALADS_ADS_AD_TYPE_IMPRS', 'impressions', $listDirn, $listOrder); ?>
					</th>
					<th width="1%" class="center">
						<?php echo JHtml::_('grid.sort',  'COM_SOCIALADS_ADS_AD_TYPE_C_T_R', 'impressions', $listDirn, $listOrder); ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($this->items as $i => $item) : ?>
				<?php $canEdit = $user->authorise('core.edit', 'com_socialads'); ?>
				<?php if (!$canEdit && $user->authorise('core.edit.own', 'com_socialads')): ?>
				<?php $canEdit = JFactory::getUser()->id == $item->created_by; ?>
				<?php endif; ?>
				<tr class="row<?php echo $i % 2; ?>">
					<td width="1%" class="hidden-phone center">
							<?php
								echo JHtml::_('grid.id', $i, $item->id);
							?>
					</td>
					<?php if (isset($this->items[0]->state)): ?>
						<td width="1%" class="center">
							<?php if ($canDo->get("core.edit.state")): ?>
								<a class="btn btn-micro" href="javascript:void(0);" onclick="document.adminForm.cb<?php echo $i; ?>.checked=1; document.adminForm.boxchecked.value=1; Joomla.submitbutton('<?php echo ($item->state) ? 'campaigns.unpublish' : 'campaigns.publish';?>');">
								<?php if ($item->state == 1): ?>
								<img src="<?php echo JUri::root(true); ?>/media/com_sa/images/publish.png"/>
								<?php else: ?>
								<img src="<?php echo JUri::root(true); ?>/media/com_sa/images/unpublish.png"/>
								<?php endif; ?>
								</a>
							<?php else:?>
								<a class=" disabled btn btn-micro" title="<?php echo JText::_("COM_SOCIALADS_NOT_ALLOWED")?>">
								<?php if ($item->state == 1): ?>
								<img src="<?php echo JUri::root(true); ?>/media/com_sa/images/publish.png"/>
								<?php else: ?>
								<img src="<?php echo JUri::root(true); ?>/media/com_sa/images/unpublish.png"/>
								<?php endif; ?>
								</a>
							<?php endif;?>
						</td>
					<?php endif; ?>
					<td width="1%" class="center">
						<?php if (isset($item->checked_out) && $item->checked_out) : ?>
						<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'campaigns.', $canCheckin); ?>
						<?php endif; ?>
						<?php if ($canDo->get("core.edit")): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_socialads&view=campaignform&id='.(int) $item->id); ?>">
						<?php echo $this->escape($item->campaign); ?></a>
						<?php else: ?>
						<?php echo $this->escape($item->campaign); ?>
						<?php endif;?>
					</td>
					<td width="1%" class="center">
						<?php echo $item->daily_budget; ?>
					</td>
					<td width="1%" class="center">
						<?php if ($item->no_of_ads)
								{
						?>
									<a href="index.php?option=com_socialads&view=ads&filter_campaignslist=<?php echo $item->id; ?>" title="<?php echo JText::_('COM_SOCIALADS_CLICK_TO_VIEW_ADS'); ?>"><?php echo $item->no_of_ads; ?></a>
						<?php	}
								else
								{
									echo "0";
								}
						?>

					</td>
					<td width="1%" class="center">
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
					<td width="1%" class="center">
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
					<td width="1%" class="center">
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
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="pull-right">
		<?php
			echo $this->pagination->getListFooter();
		?>
		</div>
		<br><br><br>
	</div>
	<?php endif;?>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
</div>
<script type="text/javascript">
	Joomla.submitbutton = function(action){
		sa.campaigns.submitButtonAction(action)
	}

	techjoomla.jQuery(document).ready(function (){
		techjoomla.jQuery('.delete-button').click(sa.campaigns.deleteItem);
	});
</script>
