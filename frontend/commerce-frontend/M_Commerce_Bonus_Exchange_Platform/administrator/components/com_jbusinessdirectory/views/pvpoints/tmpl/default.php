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

$user       = JFactory::getUser();
$listOrder  = $this->escape($this->state->get('list.ordering'));
$listDirn   = $this->escape($this->state->get('list.direction'));
$canOrder   = true;
$saveOrder  = $listOrder == 'p.ordering';
?>

<script type="text/javascript">
    Joomla.submitbutton = function(task) {
        if (task != 'companies.delete' || confirm('<?php echo JText::_('COM_JBUSINESS_DIRECTORY_OFFERS_CONFIRM_DELETE', true);?>')) {
            Joomla.submitform(task);
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=prepoints');?>" method="post" name="adminForm" id="adminForm">
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
                <th nowrap="nowrap" width='23%' ><?php echo JHtml::_('grid.sort', '優惠店家名稱', 'p.name', $listDirn, $listOrder); ?></th>
                <th class="hidden-phone" nowrap="nowrap" width='23%' ><?php echo JText::_('會員名稱')?></th>
                <th class="hidden-phone" nowrap="nowrap" width='23%' ><?php echo JText::_('電話')?></th>
                 <th class="hidden-phone" nowrap="nowrap" width='23%' ><?php echo JText::_('地址')?></th>
                <th nowrap="nowrap" width='10%' ><?php echo JHtml::_('grid.sort', '收取點數', '', $listDirn, $listOrder); ?></th>
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
        <?php
               //取出優惠收取點數
               $db = JFactory::getDbo();
               $query = $db->getQuery(true);
                $query
                ->select(array('a.user_id', 'a.points', 'b.id'))
                ->from($db->quoteName('#__pre_points_history', 'a'))
                ->join('INNER', $db->quoteName('#__users', 'b') . ' ON (' . $db->quoteName('a.user_id') . ' = ' . $db->quoteName('b.id') . ')');
                $db->setQuery($query);
                $pointHistorys = $db->loadObjectList();
                foreach ($pointHistorys as $key => $pointHistory) {
                    if (!isset($preGETPoint[$pointHistory->user_id])) {
                        $preGETPoint[$pointHistory->user_id] = 0;
                    }
                    $preGETPoint[$pointHistory->user_id] = $preGETPoint[$pointHistory->user_id] + abs($pointHistory->points);
                }
                //取出優惠店家會員
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query
                ->select(array('a.email', 'a.name', 'a.id', 'b.shop_class'))
                ->from($db->quoteName('#__users', 'a'))
                ->join('INNER', $db->quoteName('#__jbusinessdirectory_companies', 'b') . ' ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('b.userId') . ')')
                ->where($db->quoteName('b.shop_class') . ' = ' . 2);
                $db->setQuery($query);
                $userPres = $db->loadObjectList();
                foreach ($userPres as $key => $userPre) {
                    $preStoreEmail[$userPre->id] = $userPre->email;
                    $preStoreName[$userPre->id] = $userPre->name;
                }
         ?>
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
                        href='<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=prepoint.edit&id='. $item->id )?>'
                        title="<?php echo JText::_('LNG_CLICK_TO_EDIT'); ?>"> <B>
                        <?php echo $item->name ?>
                        </B>
                    </a>
                    </TD>
                    <td>
                        <?php echo $preStoreName[$item->userId] ?>
                    </td>
                    <td>
                        <?php echo $item->phone ?>
                    </td>
                    <td>
                        <?php echo $item->address ?>
                    </td>
                    <td>
                        <?php
                           if (!isset($preSendPoint[$item->userId])) {
                                echo 0;
                            }
                            echo @$preSendPoint[$item->userId];
                        ?>
                    </td>
                    <!-- 改變狀態 -->
                    <!-- <td valign="top" align="center">
                        <img
                            src="<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName()."/assets/img/".($item->block==0? "unchecked.gif" : "checked.gif")?>"
                            onclick="document.location.href = '<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=prepoints.chageState&id='. $item->id )?> '"
                        />
                    </td> -->
                    <td class="hidden-phone">
                        <?php echo $item->id ?>
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