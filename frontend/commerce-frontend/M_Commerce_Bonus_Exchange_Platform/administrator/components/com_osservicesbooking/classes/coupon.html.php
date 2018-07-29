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

class HTML_OsAppscheduleCoupon{
	/**
	 * List categories
	 *
	 * @param unknown_type $option
	 * @param unknown_type $rows
	 * @param unknown_type $pageNav
	 * @param unknown_type $keyword
	 */
	function listCoupons($option,$rows,$pageNav,$keyword){
		global $mainframe,$_jversion,$configClass;
		JToolBarHelper::title(JText::_('OS_MANAGE_COUPONS'),'tags');
		JToolBarHelper::addNew('coupon_add');
		if(count($rows) > 0){
			JToolBarHelper::editList('coupon_edit');
			JToolBarHelper::deleteList(JText::_('OS_ARE_YOU_SURE_TO_REMOVE_ITEMS'),'coupon_remove');
			JToolBarHelper::publish('coupon_publish');
			JToolBarHelper::unpublish('coupon_unpublish');
		}
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		?>
		<form method="POST" action="index.php?option=<?php echo $option; ?>&task=coupon_list" name="adminForm" id="adminForm">
			<table style="width: 100%;">
				<tr>
					<td align="right" width="100%">
						<input type="text" placeholder="<?php echo JText::_('OS_SEARCH');?>"  class="input-medium search-query" name="keyword" value="<?php echo  $lists['keyword']; ?>" />
                        <div class="btn-group">
						<input type="submit" class="btn btn-warning"  value="<?php echo JText::_('OS_SEARCH');?>" />
						<input type="reset"  class="btn btn-info"     value="<?php echo JText::_('OS_RESET');?>" onclick="this.form.keyword.value='';this.form.filter_state.value='';this.form.submit();" />
                            </div>
					</td>
				</tr>
			</table>
			<table class="adminlist table table-striped" width="100%">
				<thead>
					<tr>
						<th width="5%">#</th>
						<th width="5%">
							<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" />
						</th>
						<th width="15%">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_COUPON_TITLE'), 'counpon_name', @$lists['order_Dir'], @$lists['order'],'counpon_list' ); ?>
						</th>
						<th width="10%">
							<?php echo JText::_('OS_COUPON_CODE'); ?>
						</th>
						<th width="10%">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_DISCOUNT'), 'discount', @$lists['order_Dir'], @$lists['order'],'counpon_list' ); ?>
						</th>
						<th width="10%">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_DISCOUNT_TYPE'), 'discount_type', @$lists['order_Dir'], @$lists['order'],'counpon_list' ); ?>
						</th>
						<th width="10%">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_MAX_TOTAL_USE'), 'max_total_use', @$lists['order_Dir'], @$lists['order'],'counpon_list' ); ?>
						</th>
						<th width="10%">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_MAX_USER_USE'), 'max_user_use', @$lists['order_Dir'], @$lists['order'],'counpon_list' ); ?>
						</th>
						<th width="15%">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_EXPIRY_DATE'), 'expiry_date', @$lists['order_Dir'], @$lists['order'],'counpon_list' ); ?>
						</th>
						<th width="5%">
							<?php echo JText::_('OS_PUBLISHED'); ?>
						</th>
						<th width="5%" style="text-align:center;">
							<?php echo JHTML::_('grid.sort',   JText::_('ID'), 'id', @$lists['order_Dir'], @$lists['order'],'counpon_list' ); ?>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td width="100%" colspan="11" style="text-align:center;">
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
					$link 		= JRoute::_( 'index.php?option='.$option.'&task=coupon_edit&cid[]='. $row->id );
					$published 	= JHTML::_('jgrid.published', $row->published, $i, 'coupon_');
					?>
					<tr class="<?php echo "row$k"; ?>">
						<td align="center" style="text-align:center;"><?php echo $pageNav->getRowOffset( $i ); ?></td>
						<td align="center" style="text-align:center;"><?php echo $checked; ?></td>
						<td align="left"><a href="<?php echo $link; ?>"><?php echo $row->coupon_name; ?></a></td>
						<td align="left"><a href="<?php echo $link; ?>"><?php echo $row->coupon_code; ?></a></td>
						<td align="left"><a href="<?php echo $link; ?>"><?php echo $row->discount; ?></a></td>
						<td align="left">
							<?php 
							if($row->discount_type == 0){
								echo JText::_('OS_PERCENT');
							}else{
								echo JText::_('OS_FIXED');
							}
							?>
						</td>
						<td align="center" style="text-align:center;"><?php echo $row->max_total_use?></td>
						<td align="center" style="text-align:center;"><?php echo $row->max_user_use?></td>
						<td align="center" style="text-align:center;"><?php echo $row->expiry_date;?></td>
						<td align="center" style="text-align:center;"><?php echo $published?></td>
						<td align="center" style="text-align:center;"><?php echo $row->id; ?></td>
					</tr>
					<?php
					$k = 1 - $k;	
				}
				?>
				</tbody>
			</table>
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="coupon_list" />
			<input type="hidden" name="boxchecked" value="0" />
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
	function editCoupon($option,$row,$lists){
		global $mainframe, $_jversion,$configClass;
		$db = JFactory::getDbo();
		JRequest::setVar( 'hidemainmenu', 1 );
		if ($row->id){
			$title = ' ['.JText::_('OS_EDIT').']';
		}else{
			$title = ' ['.JText::_('OS_NEW').']';
		}
		JToolBarHelper::title(JText::_('OS_MANAGE_COUPONS').$title,'tag');
		JToolBarHelper::save('coupon_save');
		JToolBarHelper::apply('coupon_apply');
		JToolBarHelper::cancel('coupon_cancel');
		JHTML::_('behavior.tooltip');
		?>
		<form method="POST" action="index.php" class="form-horizontal" name="adminForm" id="adminForm" enctype="multipart/form-data">
		<table class="admintable" >
			<tr>
				<td class="key"><?php echo JText::_('OS_COUPON_TITLE'); ?>: </td>
				<td >
					<input class="input-large required" type="text" name="coupon_name" id="coupon_name" size="40" value="<?php echo $row->coupon_name?>" />
				</td>
			</tr>
			<tr>
				<td class="key" valign="top">
					<span class="hasTip" title="<?php echo JText::_('OS_COUPON_CODE'); ?>::<?php  echo JText::_('OS_COUPON_CODE_EXPLAIN')?>">
						<?php echo JText::_('OS_COUPON_CODE'); ?>: 
					</span>
				</td>
				<td>
					<input class="input-mini required" type="text" name="coupon_code" id="coupon_code" size="40" value="<?php echo $row->coupon_code?>" />
				</td>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('OS_DISCOUNT'); ?>: </td>
				<td>
					<input class="input-mini required" type="text" name="discount" id="discount" size="40" value="<?php echo $row->discount?>" />
				</td>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('OS_DISCOUNT_TYPE'); ?>: </td>
				<td>
					<?php
					echo $lists['discount_type'];
					?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_('OS_MAX_TOTAL_USE'); ?>::<?php  echo JText::_('OS_MAX_TOTAL_USE_EXPLAIN')?>">
						<?php echo JText::_('OS_MAX_TOTAL_USE'); ?>: 
					</span>
				</td>
				<td>
					<input class="input-mini" type="text" name="max_total_use" id="max_total_use" size="40" value="<?php echo $row->max_total_use?>" />
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_('OS_MAX_USER_USE'); ?>::<?php  echo JText::_('OS_MAX_USER_USE_EXPLAIN')?>">
						<?php echo JText::_('OS_MAX_USER_USE'); ?>: 
					</span>
				</td>
				<td>
					<input class="input-mini" type="text" name="max_user_use" id="max_user_use" size="40" value="<?php echo $row->max_user_use?>" />
				</td>
			</tr>
			<tr>
				<td class="key"><span class="hasTip" title="<?php echo JText::_('OS_EXPIRY_DATE'); ?>::<?php  echo JText::_('OS_EXPIRY_DATE_EXPLAIN')?>"><?php echo JText::_('OS_EXPIRY_DATE'); ?>:</span> </td>
				<td >
					
						<?php
						echo JHTML::_('calendar',$row->expiry_date, 'expiry_date', 'expiry_date', '%Y-%m-%d', array('class'=>'input-small', 'size'=>'19'));
						?>
					
				</td>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('OS_PUBLISHED'); ?>: </td>
				<td >
					<?php
					echo $lists['published'];
					?>
				</td>
			</tr>
		</table>
		<input type="hidden" name="option" value="<?php echo $option?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="id" value="<?php echo $row->id?>" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>
		<?php
	}
}
?>