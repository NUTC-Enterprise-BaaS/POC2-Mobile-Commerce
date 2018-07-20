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

JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$user       = JFactory::getUser();
$listOrder  = $this->escape($this->state->get('list.ordering'));
$listDirn   = $this->escape($this->state->get('list.direction'));
$canOrder   = true;
$saveOrder  = $listOrder == 'cm.ordering';
?>

<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=companymessages');?>" method="post" name="adminForm" id="adminForm">
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
                <select name="filter_type_id" class="inputbox input-medium" onchange="this.form.submit()">
                    <option value=""><?php echo JText::_('LNG_FILTER_BY');?></option>
                    <?php echo JHtml::_('select.options', $this->searchType, 'value', 'text', $this->state->get('filter.type_id'));?>
                </select>
            </div>
        </div>
    </div>
    <div class="clr clearfix"></div>

    <table class="table table-striped adminlist"  id="itemList">
    <thead>
    <tr>
        <th width="1%">#</th>
        <th width="1%">
            <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
        </th>
        <th width="5%"><?php echo JHtml::_('grid.sort', 'LNG_NAME', 'cm.name', $listDirn, $listOrder); ?></th>
        <th width="5%" class="nowrap hidden-phone"><?php echo JHtml::_('grid.sort', 'LNG_EMAIL', 'cm.email', $listDirn, $listOrder); ?></th>
        <th width="10%" class="nowrap hidden-phone"><?php echo JHtml::_('grid.sort', 'LNG_COMPANY_NAME', 'bc.name', $listDirn, $listOrder); ?></th>
        <th width="25%" class="nowrap hidden-phone"><?php echo JText::_('LNG_MESSAGE') ?></th>
      <th nowrap="nowrap" class="hidden-phone" width="1%"><?php echo JHtml::_('grid.sort', 'LNG_ID', 'cm.id', $listDirn, $listOrder); ?></th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <td colspan="15"><?php echo $this->pagination->getListFooter(); ?></td>
    </tr>
    </tfoot>
    <tbody>
    <?php if(!empty($this->items)) : ?>
        <?php foreach($this->items as $i=>$item) : ?>
        <tr>
            <td>
                <?php echo $this->pagination->getRowOffset($i); ?>
            </td>
            <td>
                <?php echo JHtml::_('grid.id', $i, $item->id); ?>
            </td>
            <td>
                 <?php echo $item->name." ".$item->surname; ?>
            </td>
            <td class="hidden-phone">
                <?php echo $item->email; ?>
            </td>
            <td class="hidden-phone">
                <?php echo $item->companyName; ?>
            </td>
            <td class="hidden-phone">
                <?php echo $item->message; ?>
            </td>
            <td class="center hidden-phone">
                <span><?php echo (int) $item->id; ?></span>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
    </table>
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
    <?php echo JHtml::_('form.token'); ?>
</form>

