<?php
/*------------------------------------------------------------------------
# coupon.html.php - Ossolution emailss Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2015 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die;

class HTML_OSappscheduleBalance{
	/**
	 * List categories
	 *
	 * @param unknown_type $option
	 * @param unknown_type $rows
	 * @param unknown_type $pageNav
	 * @param unknown_type $keyword
	 */
	function listBalances($option,$rows,$pageNav,$lists){
		global $mainframe,$configClass;
		JToolBarHelper::title(JText::_('OS_MANAGE_USER_BALANCE'),'user');
		JToolBarHelper::addNew('balance_add');
		if(count($rows) > 0){
			JToolBarHelper::editList('balance_edit');
			JToolBarHelper::deleteList(JText::_('OS_ARE_YOU_SURE_TO_REMOVE_ITEMS'),'balance_remove');
		}
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		?>
		<form method="POST" action="index.php?option=<?php echo $option; ?>&task=balance_list" name="adminForm" id="adminForm">
			<table class="adminlist table table-striped" width="100%">
				<thead>
					<tr>
						<th width="2%" style="text-align: center;">#</th>
						<th width="3%" style="text-align: center;">
							<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" />
						</th>
						<th width="10%">
                            <?php echo JHTML::_('grid.sort',   JText::_('OS_USER'), 'user_id', @$lists['order_Dir'], @$lists['order'] ,'balance_list'); ?>
						</th>
						<th width="10%">
                            <?php echo JHTML::_('grid.sort',   JText::_('OS_AMOUNT'), 'amount', @$lists['order_Dir'], @$lists['order'] ,'balance_list'); ?>
						</th>
						<th width="10%">
                            <?php echo JHTML::_('grid.sort',   JText::_('OS_DATE'), 'created_date', @$lists['order_Dir'], @$lists['order'] ,'balance_list'); ?>
						</th>
						<th width="65%">
                            <?php echo JText::_('OS_NOTE');?>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td width="100%" colspan="6" style="text-align:center;">
							<?php
								echo $pageNav->getListFooter();
							?>
						</td>
					</tr>
				</tfoot>
				<tbody>
				<?php
				$k = 0;
				for ($i=0, $n=count($rows); $i < $n; $i++) {
					$row = $rows[$i];
					$checked = JHtml::_('grid.id', $i, $row->id);
					$link 		= JRoute::_( 'index.php?option='.$option.'&task=balance_edit&cid[]='. $row->id );
					?>
					<tr class="<?php echo "row$k"; ?>">
						<td align="center" style="text-align:center;"><?php echo $pageNav->getRowOffset( $i ); ?></td>
						<td align="center" style="text-align:center;"><?php echo $checked; ?></td>
						<td align="left"><a href="<?php echo $link; ?>"><?php
                                $user = JFactory::getUser($row->user_id);
                                echo $user->name;
                                ?></a></td>
						<td align="left"><a href="<?php echo $link; ?>"><?php echo $row->amount; ?></a></td>
                        <td align="left"><?php echo $row->created_date; ?></td>
                        <td align="left"><?php echo $row->note; ?></td>
					</tr>
					<?php
					$k = 1 - $k;	
				}
				?>
				</tbody>
			</table>
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="balance_list" />
			<input type="hidden" name="boxchecked" value="0" />
            <input type="hidden" name="filter_order" value="<?php echo $lists['order'];?>" />
            <input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir'];?>" />
		</form>
		<?php
	}
	
	
	/**
	 * Edit coupon
	 *
	 * @param unknown_type $option
	 * @param unknown_type $row
	 * @param unknown_type $lists
	 */
	function editBalance($row){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		JRequest::setVar( 'hidemainmenu', 1 );
		if ($row->id){
			$title = ' ['.JText::_('OS_EDIT').']';
		}else{
			$title = ' ['.JText::_('OS_NEW').']';
		}
		JToolBarHelper::title(JText::_('OS_MANAGE_USER_BALANCE').$title,'tag');
		JToolBarHelper::save('balance_save');
		JToolBarHelper::apply('balance_apply');
		JToolBarHelper::cancel('balance_cancel');
		JHTML::_('behavior.tooltip');
		?>
		<form method="POST" action="index.php" class="form-horizontal" name="adminForm" id="adminForm">
		<table class="admintable" >
			<tr>
				<td class="key"><?php echo JText::_('OS_USER'); ?>: </td>
				<td >
					<?php
                    echo OSappscheduleEmployee::getUserInput($row->user_id);
                    ?>
				</td>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('OS_AMOUNT'); ?>: </td>
				<td>
					<input class="input-mini required" type="text" name="amount" id="amount" size="40" value="<?php echo $row->amount?>" />
				</td>
			</tr>
			<tr>
				<td class="key" valign="top">
					<?php echo JText::_('OS_NOTES'); ?>:
				</td>
				<td>
					<textarea name="note" cols="60" rows="4"><?php echo $row->note;?></textarea>
				</td>
			</tr>
		</table>
		<input type="hidden" name="option" value="com_osservicesbooking" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="id" value="<?php echo $row->id?>" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>
		<?php
	}
}
?>