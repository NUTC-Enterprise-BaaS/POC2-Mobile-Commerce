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

<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=points');?>" method="post" name="adminForm" id="adminForm">
<?php
    $count = count($this->items);
?>
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
                <!-- 顯示使用者人數筆數 -->
                <?php echo $this->pagination->getLimitBox(); ?>
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
                <th nowrap="nowrap" width='23%' ><?php echo JHtml::_('grid.sort', 'LNG_USERID', 'p.name', $listDirn, $listOrder); ?></th>
                <th nowrap="nowrap" width='23%' ><?php echo JHtml::_('grid.sort', '名稱', 'p.name', $listDirn, $listOrder); ?></th>
                <th class="hidden-phone" nowrap="nowrap" width='23%' ><?php echo JText::_('LNG_USERNAME')?></th>

                <th nowrap="nowrap" width='10%' ><?php echo JHtml::_('grid.sort', 'LNG_EMAIL', '', $listDirn, $listOrder); ?></th>
                <th nowrap="nowrap" width='10%' ><?php echo JHtml::_('grid.sort', 'Point', '', $listDirn, $listOrder); ?></th>
                <th class="hidden-phone" nowrap="nowrap" width="25%" class="nowrap">
                    <?php echo JHtml::_('grid.sort', 'lastvisitDate', 'p.ordering', $listDirn, $listOrder); ?>
                    <?php if ($canOrder && $saveOrder) :?>
                        <?php echo JHtml::_('grid.order', $this->items, 'filesave.png', 'packages.saveorder'); ?>
                    <?php endif; ?>
                </th>

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
            <!-- 取出  ad_orders & users 資料表欄位 -->
            <?php $nrcrt = 1; $i=0;
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select($db->quoteName(array('username','email','id','lastvisitDate', 'name')))->from($db->quoteName('#__users').'AS p');
            $db->setQuery($query);
            $results = $db->loadObjectList();

            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query
                ->select($db->quoteName(array('payee_id','amount')))
                ->from($db->quoteName('#__ad_orders').'AS p');
                    // ->where($db->quoteName('payee_id')."=".$results->id);
            $db->setQuery($query);
            $adsLists= $db->loadObjectList();

            //取出資料後跟social_points_history資料表串接
            foreach ($results as $result) {
                $allUser[$result->id] =$result->username;
                $email[$result->id] = $result->email;
                $name[$result->id] = $result->name;
                $lastvisitDate[$result->id] = $result->lastvisitDate;

                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query
                    ->select($db->quoteName(array('payee_id','amount')))
                    ->from($db->quoteName('#__ad_orders').'AS p');
                    // ->where($db->quoteName('payee_id')."=".$results->id);
                $db->setQuery($query);
                $ads = $db->loadObjectList();

                foreach ($ads as $ad) {
                    $db = JFactory::getDbo();
                    $query = $db->getQuery(true);
                    $query
                        ->select($db->quoteName(array('points','user_id')))
                        ->from($db->quoteName('#__social_points_history').'AS p')
                        ->where($db->quoteName('user_id')."=".$result->id);
                    $db->setQuery($query);
                    $pointHistorys = $db->loadObjectList();
                }

            // 結合social_points_history 和 users 資料做使用者總點數疊加的動作
                foreach ($pointHistorys as $pointHistory) {
                    $user_points = $pointHistory->points;
                    $user_id = $pointHistory->user_id ;

                    if (!isset($value[$user_id])) {
                        $value[$user_id]=0;
                    }
                    $value[$user_id]=$value[$user_id]+$user_points;
                }
            }

            foreach ($adsLists as $adsList) {
                $payee_id[$result->id] = $adsList->payee_id;
                if (!isset($adTotal[$payee_id[$result->id]])){
                    $adTotal[$payee_id[$result->id]]=0;
                }
                $adTotal[$adsList->payee_id]=$adTotal[$adsList->payee_id]+$adsList->amount;
            }
            $nrcrt = 1; $i=0;
            // current is get now arrary value
            while($element = current($allUser)) {
                foreach($this->items as $item) {
                    $ordering  = ($listOrder == 'p.ordering');
                    $canCreate  = true;
                    $canEdit    = true;
                    $canChange  = true;
                    if (key($allUser)==$item->id) {?>
                    <TR class="row<?php echo $i % 2; ?>">
                        <TD class="center hidden-phone"><?php echo $nrcrt++?></TD>
                        <TD align="center" class="hidden-phone">

                        </TD>
                        <TD align="left"><a
                            href='<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=point.edit&id='. key($allUser) )?>'
                            title="<?php echo JText::_('LNG_CLICK_TO_EDIT'); ?>"> <B>
                            <?php
                            // 顯行使用者ID
                            echo key($allUser)."\n"; ?>
                            </B>
                        </a>
                        </TD>
                        <TD align="left"><a
                            href='<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=user.edit&id='. key($allUser) )?>'
                            title="<?php echo JText::_('LNG_CLICK_TO_EDIT'); ?>"> <B>
                            <?php
                                //顯示使用者名稱
                                echo $name[key($allUser)];
                            ?>
                            </B>
                        </a>
                        </TD>
                        <td class="hidden-phone">
                            <?php
                                //顯示使用者名稱
                                echo $element;
                            ?>
                        </td>
                        <td>
                            <?php
                                //顯示使用者EMAIL
                                echo $email[key($allUser)]
                            ?>
                        </td>
                        <td>
                            <?php
                                //顯示使用者點數
                            if (!isset($value[key($allUser)])){
                                echo "0";
                            }
                                // echo @$value[key($allUser)]-@$adTotal[key($allUser)];
                                echo @$value[key($allUser)];
                            ?>
                        </td>
                        <td>
                            <?php echo $lastvisitDate[key($allUser)] ?>
                        </td>
                        <td valign="top" align="center"></td>
                        <td class="hidden-phone">
                            <?php echo key($allUser) ?>
                        </td>
                    </TR>
            <?php }}
            $i++;
            next($allUser);
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