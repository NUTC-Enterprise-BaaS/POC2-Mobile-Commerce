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

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task != 'offercoupons.delete' || confirm('<?php echo JText::_('ARE_YOU_SURE_YOU_WANT_TO_DELETE', true);?>')) {
			Joomla.submitform(task);
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=offercoupons');?>" method="post" name="adminForm" id="adminForm">
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
		</div>
	</div>
	<div class="clr clearfix"></div>
	<table class="table table-striped adminlist" id="itemList">
		<thead>
			<tr>
				<th width="1%">#</th>
				<th width="1%"><input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" /></th>
				<th width='23%'><?php echo JHtml::_('grid.sort', 'LNG_COUPON', 'ofc.code', $listDirn, $listOrder); ?></th>
				<th width='23%' class="hidden-phone"><?php echo JHtml::_('grid.sort', 'LNG_OFFER', 'of.subject', $listDirn, $listOrder); ?></th>
				<th width='10%' class="hidden-phone"><?php echo JHtml::_('grid.sort', 'LNG_COMPANY', 'co.name', $listDirn, $listOrder); ?></th>
				<th width='10%' class="hidden-phone"><?php echo JHtml::_('grid.sort', 'LNG_GENERATED_TIME', 'ofc.generated_time', $listDirn, $listOrder); ?></th>
				<th width='10%' class="hidden-phone"><?php echo JHtml::_('grid.sort', 'LNG_EXPIRATION_TIME', 'of.endDate', $listDirn, $listOrder); ?></th>
				<th nowrap width='7%'>PDF</th>
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
			foreach($this->items as $coupon) { ?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="center">
						<?php echo $nrcrt++?>
					</td>
					<td class="center">
						<?php echo JHtml::_('grid.id', $i, $coupon->id); ?>
					</td>
					<td align="left">
						<b><?php echo strtoupper($coupon->code); ?></b>
					</td>
					<td class="hidden-phone">
						<a href='<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=offer.edit&id='. $coupon->offer_id )?>'
							title="<?php echo JText::_('LNG_CLICK_TO_EDIT'); ?>">
							<?php echo $coupon->offer; ?> 
						</a>
					</td>
					<td class="hidden-phone">
						<a href='<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=company.edit&id='. $coupon->company_id )?>'
							title="<?php echo JText::_('LNG_CLICK_TO_EDIT'); ?>">
							<?php echo $coupon->company; ?> 
						</a>
					</td>
					<td class="hidden-phone">
						<?php echo JBusinessUtil::getDateGeneralFormat($coupon->generated_time); ?>
					</td>
					<td class="hidden-phone">
						<?php echo JBusinessUtil::getDateGeneralFormat($coupon->expiration_time); ?>
					</td>
					<td>
						<a href='<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=offercoupon.show&id='. $coupon->id )?>'
							title='<?php echo JText::_('LNG_CLICK_TO_VIEW'); ?>'
							target='_blank'>
							<?php echo JText::_('LNG_VIEW'); ?>
						</a>
					</td>
				</tr>
			<?php
				$i++;
			} ?>
		</tbody>
	</table>
	<input type="hidden" name="option" value="<?php echo JBusinessUtil::getComponentName()?>" />
	<input type="hidden" name="task" value="" /> 
	<input type="hidden" name="offerId" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHTML::_('form.token'); ?> 
</form>