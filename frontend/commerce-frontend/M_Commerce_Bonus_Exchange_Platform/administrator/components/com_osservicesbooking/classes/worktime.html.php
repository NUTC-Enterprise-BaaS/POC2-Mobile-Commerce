<?php
/*------------------------------------------------------------------------
# worktime.html.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2015 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die;


class HTML_OSappscheduleWorktime{
	/**
	 * Extra field list HTML
	 *
	 * @param unknown_type $option
	 * @param unknown_type $rows
	 * @param unknown_type $pageNav
	 * @param unknown_type $lists
	 */
	function worktime_list($option,$rows){
		global $mainframe,$_jversion;
		JHtml::_('behavior.multiselect');
		JToolBarHelper::title(JText::_('OS_MANAGE_WORKTIME'),'clock');
		JToolBarHelper::save('worktime_save');
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
	?>
		<form method="POST" action="index.php?option=<?php echo $option; ?>&task=worktime_list" name="adminForm" id="adminForm">
			<table  width="100%" class="adminlist table table-striped">
				<thead>
					<tr>
						<th width="5%">#</th>
						<th>
							<?php echo JText::_('OS_DAY_OF_WEEK'); ?>
						</th>
						<th width="15%">
							<?php echo JText::_('OS_WORKTIME_START_TIME'); ?>
						</th>
						<th width="15%">
							<?php echo JText::_('OS_WORKTIME_END_TIME'); ?>
						</th>
						<th width="10%" style="text-align:center;">
							<?php echo JText::_('OS_IS_DAY_OFF'); ?>
						</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$k = 0;
				for ($i=0, $n=count($rows); $i < $n; $i++) {
					$row = $rows[$i];
				?>
					<tr class="<?php echo "row$k";?>">
						<td align="center"><?php echo ( $i + 1); ?></td>
						<td align="left"><?php echo JText::_($row->worktime_date); ?></td>
						<td align="center" >
							<?php  
								list($row->start_time_hour,$row->start_time_minutes) 	= explode(':',$row->start_time); 
								echo HelperDateTime::CreatDropHour('start_time_hour_'.$row->id,$row->start_time_hour,'class="input-mini is_day_'. $row->id.'"');
								echo HelperDateTime::CreatDropMinuste('start_time_minutes_'.$row->id,$row->start_time_minutes,'class="input-mini is_day_'. $row->id.'"')
							?>
						</td>
						<td align="center">
							<?php 
								list($row->end_time_hour,$row->end_time_minutes) 	= explode(':',$row->end_time); 
								echo HelperDateTime::CreatDropHour('end_time_hour_'.$row->id,$row->end_time_hour,'class="input-mini is_day_'. $row->id.'"');
								echo HelperDateTime::CreatDropMinuste('end_time_minutes_'.$row->id,$row->end_time_minutes,'class="input-mini is_day_'. $row->id.'"')
							?>
						</td>
						<td align="center" style="text-align:center;">
							<input type="hidden" name="cid[]" value="<?php echo $row->id?>">
							<?php $checked = ''; if ($row->is_day_off) $checked = ' checked="checked" ';?>
							<input type="checkbox" <?php echo $checked?> name="is_day_off_<?php echo $row->id?>" class="is_day" value="<?php echo $row->id?>">
						</td>
					</tr>
				<?php
					$k = 1 - $k;	
				}
				?>
				</tbody>
			</table>
			<input type="hidden" name="option" value="<?php echo $option; ?>">
			<input type="hidden" name="task" value="worktime_list">
		</form>
		<script type="text/javascript">
			window.addEvent('domready', function() {
				$$('.is_day').each(function(el){
					el.onclick=function(){
						$$('.is_day_' + el.value).each(function(e){
							e.disabled = el.checked;
						});
					};
					$$('.is_day_' + el.value).each(function(e){
						e.disabled = el.checked;
					});
				});
			})
		</script>
		<?php
	}
}
?>