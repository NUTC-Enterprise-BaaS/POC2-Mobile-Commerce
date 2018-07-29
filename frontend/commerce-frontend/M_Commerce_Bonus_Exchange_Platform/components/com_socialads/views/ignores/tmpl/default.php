<?php
/**
 * @version     3.2
 * @package     com_socialads
 * @copyright   Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license     GNU General Public License version 2, or later
 * @author      Techjoomla <extensions@techjoomla.com> - http://www.techjoomla.com
 */

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$user	= JFactory::getUser();
$userId	= $user->get('id');
$input      = JFactory::getApplication()->input;
$adid = $input->get('adid');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$sortFields = $this->getSortFields();
?>
<script type="text/javascript">
	Joomla.orderTable = function()
	{
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}

	techjoomla.jQuery(document).ready(function () {
		techjoomla.jQuery('#clear-search-button').on('click', function () {
			techjoomla.jQuery('#filter_search').val('');
			techjoomla.jQuery('#adminForm').submit();
		});
	});
</script>

<form action="<?php echo JRoute::_('index.php?option=com_socialads&view=ignores&tmpl=component'); ?>" method="post" name="adminForm" id="adminForm">
	<h3><?php echo JText::_('COM_SOCIALADS_ADS_IGNORED');?></h3>
	<div id="filter-bar" class="btn-toolbar">
		<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12  ">
			<div class="input-group">
				<input type="text"
					placeholder="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"
					name="filter_search"
					id="filter_search"
					value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
					class="form-control"
					onchange="document.adminForm.submit();" />
				<span class="input-group-btn">
					<button type="button" onclick="this.form.submit();" class="button btn btn-sm" data-original-title="Search">
						<i class="fa fa-search" aria-hidden="true"></i>
					</button>

					<button onclick="document.getElementById('filter_search').value='';this.form.submit();" type="button" class="button btn btn-sm" data-original-title="Clear">
						<i class="fa fa-times" aria-hidden="true"></i>
					</button>
				</span>
			</div>
		</div>
	</div>
	<div class="clearfix"> </div>
	<table class="table table-striped" id="List">
		<thead>
			<?php
			if (!empty($this->items))
			{
			?>
			<tr>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_SOCIALADS_IGNORES_IGNORED_BY', 'ignored_by', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_SOCIALADS_IGNORES_IDATE', 'a.idate', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php
				echo JHtml::_('grid.sort',  'COM_SOCIALADS_IGNORES_AD_FEEDBACK', 'a.ad_feedback', $listDirn, $listOrder); ?>
				</th>
			</tr>
			<?php
			}
			?>
		</thead>
		<tfoot>
			<?php
			if(isset($this->items[0]))
			{
				$colspan = count(get_object_vars($this->items[0]));
			}
			else{
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
			if (!empty($this->items))
			{
			?>
			<?php foreach ($this->items as $i => $item) : ?>
				<tr class="row<?php echo $i % 2; ?>">
					<td>
						<?php echo $item->ignored_by; ?>
					</td>
					<td>
						<?php echo $item->idate; ?>
					</td>
					<td>
						<?php echo $item->ad_feedback; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			<?php
			}
			else
			{
				echo JText::_("COM_SOCIALADS_FILTER_SEARCH_NOT_FOUND");
			}
			?>
		</tbody>
	</table>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<input type="hidden" name="adid" value="<?php echo $adid ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
