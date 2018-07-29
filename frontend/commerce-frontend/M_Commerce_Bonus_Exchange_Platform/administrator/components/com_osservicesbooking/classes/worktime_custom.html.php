<?php
/*------------------------------------------------------------------------
# worktime_custom.html.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2015 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die;


class HTML_OSappscheduleWorktimecustom{
	/**
	 * Extra field list HTML
	 *
	 * @param unknown_type $option
	 * @param unknown_type $rows
	 * @param unknown_type $pageNav
	 * @param unknown_type $lists
	 */
	function worktimecustom_list($option,$rows,$pageNav,$lists){
		global $mainframe,$_jversion,$configClass;
		
		JHtml::_('behavior.multiselect');
		
		JToolBarHelper::title(JText::_('OS_MANAGE_WORKTIMECUSTOM'),'clock');
		JToolBarHelper::addNew('worktimecustom_add');
		if(count($rows) > 0){
			JToolBarHelper::editList('worktimecustom_edit');
			JToolBarHelper::deleteList(JText::_('OS_ARE_YOU_SURE_TO_REMOVE_ITEMS'),'worktimecustom_remove');
		}
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
	?>
		<form method="POST" action="index.php?option=<?php echo $option; ?>&task=worktimecustom_list" name="adminForm" id="adminForm">
			<table cellpadding="0" cellspacing="0" width="100%" border="0">
				<tr>
					<td align="left">
						<?php echo JText::_("OS_FILTER")?>: &nbsp;
						<input type="text"  class="input-medium search-query" name="keyword" value="<?php echo $lists['keyword']; ?>">
						<input type="submit" class="btn btn-warning" value="Go">
						<input type="reset"  class="btn btn-info" value="Reset" onclick="this.form.keyword.value='';this.form.submit();">
					</td>
				</tr>
			</table>
			<table class="adminlist table table-striped" width="100%">
				<thead>
					<tr>
						<th width="2%">#</th>
						<th width="3%">
							<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
						</th>
						<th width="35%">
							<?php echo JText::_('OS_REASON');?>
						</th>
						<th width="10%">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_FROM'), 'worktime_date', @$lists['order_Dir'], @$lists['order'] ); ?>
						</th>
						<th width="10%">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_TO'), 'worktime_date_to', @$lists['order_Dir'], @$lists['order'] ); ?>
						</th>
						<th width="15%">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_WORKTIME_START_TIME'), 'start_time', @$lists['order_Dir'], @$lists['order'] ); ?>
						</th>
						<th width="15%">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_WORKTIME_END_TIME'), 'end_time', @$lists['order_Dir'], @$lists['order'] ); ?>
						</th>
						<th width="10%" style="text-align:center;">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_IS_DAY_OFF'), '	is_day_off', @$lists['order_Dir'], @$lists['order'] ); ?>
						</th>
						<th width="5%" style="text-align:center;">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_ID'), 'id', @$lists['order_Dir'], @$lists['order'] ); ?>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td width="100%" colspan="9" style="text-align:center;">
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
					$link 		= JRoute::_( 'index.php?option='.$option.'&task=worktimecustom_edit&cid[]='. $row->id );
					$is_day_off = ($row->is_day_off)? JText::_('OS_YES'):JText::_('OS_NO'); 
				?>
					<tr class="<?php echo "row$k";?>">
						<td align="center" style="text-align:center;"><?php echo $pageNav->getRowOffset( $i ); ?></td>
						<td align="center" style="text-align:center;"><?php echo $checked; ?></td>
						<td align="left">
							<a href="<?php echo $link; ?>">
								<?php echo $row->reason;?>
							</a>
						</td>
						<td align="center" style="text-align:center;"><a href="<?php echo $link; ?>"><?php echo JText::_($row->worktime_date); ?></a></td>
						<td align="center" style="text-align:center;"><a href="<?php echo $link; ?>"><?php echo JText::_($row->worktime_date_to); ?></a></td>
						<td align="center" style="text-align:center;"><?php echo date($configClass['time_format'],strtotime($row->worktime_date." ".$row->start_time)); ?> </td>
						<td align="center" style="text-align:center;"><?php echo date($configClass['time_format'],strtotime($row->worktime_date." ".$row->end_time)); ?></td>
						<td align="center" style="text-align:center;"><?php echo $is_day_off?></td>
						<td align="center" style="text-align:center;"><?php echo $row->id; ?></td>
					</tr>
				<?php
					$k = 1 - $k;	
				}
				?>
				</tbody>
			</table>
			<input type="hidden" name="option" value="<?php echo $option; ?>">
			<input type="hidden" name="task" value="worktimecustom_list">
			<input type="hidden" name="boxchecked" value="0">
			<input type="hidden" name="filter_order" value="<?php echo $lists['order'];?>">
			<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir'];?>">
		</form>
		<?php
	}
	
	
	/**
	 * Agent field
	 *
	 * @param unknown_type $option
	 * @param unknown_type $row
	 * @param unknown_type $lists
	 */
	function worktimecustom_modify($option,$row,$lists,$services){
		global $mainframe, $_jversion;
		$db = JFactory::getDbo();
		$version 	= new JVersion();
		$_jversion	= $version->RELEASE;		
		$mainframe 	= JFactory::getApplication();
		JRequest::setVar( 'hidemainmenu', 1 );
		if ($row->id){
			$title = ' <small><small>['.JText::_('OS_EDIT').']</small></small>';
		}else{
			$title = ' <small><small>['.JText::_('OS_NEW').']</small></small>';
		}
		JToolBarHelper::title(JText::_('OS_WORKTIME').$title,'clock');
		JToolBarHelper::save('worktimecustom_save');
		JToolBarHelper::apply('worktimecustom_apply');
		JToolBarHelper::cancel('worktimecustom_cancel');
		?>
		<form method="POST" action="index.php" name="adminForm" id="adminForm" enctype="multipart/form-data">
			<table class="admintable">
				<tr>
					<td class="key"><?php echo JText::_('OS_REASON'); ?>: </td>
					<td >
						<input type="text" class="input-large" name="reason" value="<?php echo $row->reason?>" />
					</td>
				</tr>
				<tr>
					<td class="key"><?php echo JText::_('OS_DATE_FROM'); ?>: </td>
					<td >
						<?php echo JHtml::_('calendar',$row->worktime_date,'worktime_date','worktime_date','%Y-%m-%d','class="input-small required" readonly="readonly" ');?>
						<div id="worktime_date_invalid" style="display: none; color: red;"><?php echo JText::_('OS_THIS_FIELD_IS_REQUIRED')?></div>
					</td>
				</tr>
				<tr>
					<td class="key"><?php echo JText::_('OS_DATE_TO'); ?>: </td>
					<td >
						<?php echo JHtml::_('calendar',$row->worktime_date_to,'worktime_date_to','worktime_date_to','%Y-%m-%d','class="input-small required" readonly="readonly" ');?>
						<div id="worktime_date_invalid" style="display: none; color: red;"><?php echo JText::_('OS_THIS_FIELD_IS_REQUIRED')?></div>
					</td>
				</tr>
				<tr>
					<td class="key"><?php echo JText::_('OS_WORKTIME_START_TIME'); ?>: </td>
					<td >
						<?php echo $lists['start_time_hour'];?>:<?php echo $lists['start_time_minutes'];?>
					</td>
				</tr>
				
				<tr>
					<td class="key"><?php echo JText::_('OS_WORKTIME_END_TIME'); ?>: </td>
					<td >
						<?php echo $lists['end_time_hour'];?>:<?php echo $lists['end_time_minutes'];?>
					</td>
				</tr>
				<tr>
					<td class="key"><?php echo JText::_('OS_IS_DAY_OFF'); ?>: </td>
					<td width="80%">
						<?php
						if($row->is_day_off == 1){
							$checked = "checked";
						}else{
							$checked = "";
						}
						?>
						<input type="checkbox" name="is_day_off" id="is_day_off" <?php echo $checked?> value="<?php echo $row->is_day_off?>" onclick="javascript:changeValue('is_day_off');">
					</td>
				</tr>
			</table>
			<input type="hidden" name="option" value="<?php echo $option?>">
			<input type="hidden" name="task" value="">
			<input type="hidden" name="id" value="<?php echo $row->id?>">
		</form>
		<script type="text/javascript">
			window.addEvent('domready', function() {
				$$('.required').each(function(el){
					el.onchange=function(){
						if(this.value != '')$$('#' + this.id + "_invalid").setStyle('display','none');
					}
				})
			})
			Joomla.submitbutton = function(pressbutton){
				var form = document.adminForm;
				if (pressbutton == 'worktimecustom_cancel'){
					submitform( pressbutton );
					return;
				}else if (form.worktime_date.value == ''){
					$$("#worktime_date_invalid").setStyle('display','');
					document.getElementById('worktime_date_img').onclick();
					return;
				}else{
					submitform( pressbutton );
					return;
				}
			}
		</script>
		<?php
	}
}
?>