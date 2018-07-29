<?php 
/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$user		= JFactory::getUser();
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$canOrder	= true;
$saveOrder	= $listOrder == 'p.ordering';
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task != 'companies.delete' || confirm('<?php echo JText::_('COM_JBUSINESS_DIRECTORY_OFFERS_CONFIRM_DELETE', true);?>')) {
			Joomla.submitform(task);
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=packages');?>" method="post" name="adminForm" id="adminForm">
	<div id="j-main-container">
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left fltlft">
				<label class="filter-search-lbl element-invisible" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
				<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>" />
			</div>
			<div class="btn-group pull-left hidden-phone">
				<button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button class="btn hasTooltip" type="button" onclick="document.id('filter_search').value='';this.form.submit();" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
			<div class="filter-select pull-right fltrt btn-group">
				<select name="filter_status_id" class="inputbox input-medium" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('LNG_JOPTION_SELECT_STATUS');?></option>
					<?php echo JHtml::_('select.options', $this->statuses, 'value', 'text', $this->state->get('filter.status_id'));?>
				</select>
			</div>
		</div>
	</div>
	<div class="clr clearfix"></div>
	<table class="table table-striped adminlist" id="itemList">
		<thead>
			<tr>
				<th width="1%" class="hidden-phone">#</th>
				<th width="1%" class="hidden-phone">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th nowrap="nowrap" width='23%' ><?php echo JHtml::_('grid.sort', 'LNG_NAME', 'p.name', $listDirn, $listOrder); ?></th>
				<th class="hidden-phone" nowrap="nowrap" width='23%' ><?php echo JText::_('LNG_DESCRIPTION')?></th>
				
				<th nowrap="nowrap" width='10%' ><?php echo JHtml::_('grid.sort', 'LNG_PRICE', 'p.price', $listDirn, $listOrder); ?></th>
				<!-- th nowrap="nowrap" width='10%' ><?php echo JHtml::_('grid.sort', 'LNG_SPECIAL_PRICE', 'p.special_price', $listDirn, $listOrder); ?></th>
				<th nowrap="nowrap" width='10%' ><?php echo JHtml::_('grid.sort', 'LNG_SPECIAL_START_DATE', 'p.special_from_date', $listDirn, $listOrder); ?></th>
				<th nowrap="nowrap" width='10%' ><?php echo JHtml::_('grid.sort', 'LNG_SPECIAL_END_DATE', 'p.special_to_date', $listDirn, $listOrder); ?></th-->
				<th nowrap="nowrap" width='10%' ><?php echo JHtml::_('grid.sort', 'LNG_DAYS', 'p.days', $listDirn, $listOrder); ?></th>
				<th class="hidden-phone" nowrap="nowrap" width="10%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ORDERING', 'p.ordering', $listDirn, $listOrder); ?>
					<?php if ($canOrder && $saveOrder) :?>
						<?php echo JHtml::_('grid.order', $this->items, 'filesave.png', 'packages.saveorder'); ?>
					<?php endif; ?>
				</th>
				<th nowrap="nowrap" width='10%' ><?php echo JHtml::_('grid.sort', 'LNG_STATUS', 'p.status', $listDirn, $listOrder); ?></th>
				<th nowrap="nowrap" width='10%' ><?php echo JHtml::_('grid.sort', 'LNG_POPULAR', 'p.popular', $listDirn, $listOrder); ?></th>
				<th class="hidden-phone" nowrap="nowrap" width='1%' ><?php echo JHtml::_('grid.sort', 'LNG_ID', 'p.id', $listDirn, $listOrder); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="15">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php $nrcrt = 1; $i=0;
			$count = count($this->items); 
			foreach($this->items as $item) {
				$ordering  = ($listOrder == 'p.ordering');
				$canCreate  = true;
				$canEdit    = true;
				$canChange  = true;
				?>
				<TR class="row<?php echo $i % 2; ?>">
					<TD class="center hidden-phone"><?php echo $nrcrt++?></TD>
					<TD align="center" class="hidden-phone">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</TD>
					<TD align="left"><a
						href='<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=package.edit&id='. $item->id )?>'
						title="<?php echo JText::_('LNG_CLICK_TO_EDIT'); ?>"> <B><?php echo $item->name?>
						</B>
					</a>
					</TD>
				
					<td class="hidden-phone">
						<?php echo $item->description ?>
					</td>
					<td>
						<?php echo $item->price?>
					</td>
					<!-- td>
						<?php echo $item->special_price ?>
					</td>
					<td>
						<?php echo JBusinessUtil::getDateGeneralFormat($item->special_from_date) ?>
					</td>
					<td>
						<?php echo JBusinessUtil::getDateGeneralFormat($item->special_to_date) ?>
					</td -->
					<td>
						<?php echo $item->days ?>
					</td>
					<td class="hidden-phone" class="order">
						<?php if ($canChange) : ?>
							<div class="input-prepend">
							<?php if ($saveOrder) :?>
								<?php if ($listDirn == 'asc') : ?>
									<span class="add-on"><?php echo $this->pagination->orderUpIcon($i, true, 'packages.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
									<span class="add-on"><?php echo $this->pagination->orderDownIcon($i, $count, true, 'packages.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
								<?php elseif ($listDirn == 'desc') : ?>
									<span class="add-on"><?php echo $this->pagination->orderUpIcon($i, true, 'packages.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
									<span class="add-on"><?php echo $this->pagination->orderDownIcon($i, $count, true, 'packages.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
								<?php endif; ?>
							<?php endif; ?>
							<?php $disabled = $saveOrder ? '' : 'disabled="disabled"'; ?>
						 	<input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="width-20 text-area-order" />
						 </div>
						<?php else : ?>
							<?php echo $item->ordering; ?>
						<?php endif; ?>
					</td>
					<td valign="top" align="center">
						<img  
							src="<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName()."/assets/img/".($item->status==0? "unchecked.gif" : "checked.gif")?>" 
							onclick="document.location.href = '<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=packages.chageState&id='. $item->id )?> '"
						/>
					</td>
					<td valign="top" align="center">
						<img  
							src="<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName()."/assets/img/".($item->popular==0? "unchecked.gif" : "checked.gif")?>" 
							onclick="document.location.href = '<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=packages.chagePopularState&id='. $item->id )?> '"
						/>
					</td>
					<td class="hidden-phone">
						<?php echo $item->id?>
					</td>
				</TR>
			<?php
			$i++;
			} ?>
		</tbody>
	</table>

	<input type="hidden" name="option" value="<?php echo JBusinessUtil::getComponentName()?>" />
	<input type="hidden" name="task" value="" /> 
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHTML::_('form.token'); ?> 
</form>