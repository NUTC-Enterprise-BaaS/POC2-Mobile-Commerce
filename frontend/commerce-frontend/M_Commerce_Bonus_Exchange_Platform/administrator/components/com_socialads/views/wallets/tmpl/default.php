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
JHtml::_('behavior.modal', 'a.modal');
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$user	= JFactory::getUser();
$userId	= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$canOrder	= $user->authorise('core.edit.state', 'com_socialads');
$saveOrder	= $listOrder == 'a.ordering';
if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_socialads&task=adwallets.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'adwalletList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$sortFields = $this->getSortFields();

if (!empty($this->extra_sidebar))
{
	$this->sidebar .= $this->extra_sidebar;
}

if(!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
	<form action="<?php echo JRoute::_('index.php?option=com_socialads&view=wallets'); ?>" method="post" name="adminForm" id="adminForm">
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('COM_SOCIALADS_ADWALETS_FILTER_SEARCH'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_SOCIALADS_ADWALETS_FILTER_SEARCH'); ?>" />
			</div>
			<div class="btn-group pull-left">
				<button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button class="btn hasTooltip" id="clear-search-button" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>

			<div class="btn-group pull-right hidden-phone">
				<label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC');?></label>
				<select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC');?></option>
					<option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING');?></option>
					<option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');?></option>
				</select>
			</div>

			<div class="btn-group pull-right">
				<label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY');?></label>
				<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
					<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder);?>
				</select>
			</div>
<!--
				<div class="btn-group pull-right hidden-phone">
				<?php
					//echo JHtml::_('select.genericlist', $this->publish_states, "filter_published", 'class="input-medium" size="1" onchange="document.adminForm.submit();" name="filter_published"', "value", "text", $this->state->get('filter.state'));
					?>
			</div>
-->
		</div>
		<div class="clearfix"> </div>
		<?php if (empty($this->items)) : ?>
		<div class="clearfix">&nbsp;</div>
		<div class="alert alert-no-items">
			<?php echo JText::_('COM_SOCIALADS_FILTER_SEARCH_NOT_FOUND'); ?>
		</div>
		<?php
		else : ?>
		<table class="table table-striped" id="adwalletList">
			<thead>
				<tr>
				<?php if (isset($this->items[0]->ordering)): ?>
					<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
					</th>
				<?php endif; ?>

<!--
					<th width="1%" class="hidden-phone">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
                <?php// if (isset($this->items[0]->state)): ?>
					<th width="1%" class="nowrap center">
						<?php //echo JHtml::_('grid.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
					</th>
				<?php //endif; ?>
-->
				<th class='left'>
					<?php echo JHtml::_('grid.sort',  'COM_SOCIALADS_ADWALETS_USERNAME', 'u.username', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
					<?php echo JHtml::_('grid.sort','COM_SOCIALADS_ADWALETS_DETAILS', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
					<?php echo JHtml::_('grid.sort',  'COM_SOCIALADS_ADWALETS_SPENT', 'a.spent', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_SOCIALADS_ADWALETS_EARN', 'a.earn', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_SOCIALADS_ADWALETS_BALANCE', 'a.balance', $listDirn, $listOrder); ?>
				</th>
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
				$canCreate = $user->authorise('core.create', 'com_socialads');
				$canEdit = $user->authorise('core.edit', 'com_socialads');
				$canCheckin = $user->authorise('core.manage', 'com_socialads');
				$canChange = $user->authorise('core.edit.state', 'com_socialads');
				?>
				<tr class="row<?php echo $i % 2; ?>">
					<?php if (isset($this->items[0]->ordering)): ?>

					<td class="order nowrap center hidden-phone">
					<?php if ($canChange) :
						$disableClassName = '';
						$disabledLabel = '';
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

				<td>
					<?php echo $item->created_by . " (" . $item->email . ")"; ?>
				</td>
				<td>
					<?php
						$link = JRoute::_('index.php?option=com_socialads&view=wallet&tmpl=component&layout=default&userid='.$item->user_id);?>
						<a rel="{handler: 'iframe', size: {x: 900, y: 400}}" href="<?php echo $link; ?>" class="modal">
							<?php echo JText::_('COM_SOCIALADS_ADWALETS_VIEW_DETAILS');?>
						</a>
				</td>
				<td>
					<?php echo SaCommonHelper::getFormattedPrice($item->spent);?>
				</td>
				<td>
					<?php echo SaCommonHelper::getFormattedPrice($item->earn);?>
				</td>
				<td>
					<?php echo SaCommonHelper::getFormattedPrice($item->balance);?>
				</td>
			</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
			<?php endif; ?>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

<script type="text/javascript">
	saAdmin.initSaJs();
	var tjListOrderingColumn = "<?php echo $listOrder; ?>";
</script>
