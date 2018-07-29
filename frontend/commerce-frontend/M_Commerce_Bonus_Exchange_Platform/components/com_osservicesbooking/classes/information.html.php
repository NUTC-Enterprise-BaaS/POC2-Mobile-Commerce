<?php
/*------------------------------------------------------------------------
# information.html.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2012 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// No direct access.
defined('_JEXEC') or die;

class HTML_OSappscheduleInformation{
	/**
	 * Header information
	 *
	 * @param unknown_type $service
	 * @param unknown_type $employee
	 * @param unknown_type $date
	 */
	function showErrorHtml($service,$employee,$inforArr,$vid,$dateArr){
		global $mainframe,$configClass;
		
		?>
		<div id="bookingerrDiv">
		<table width="100%" class="apptable">
			<?php
			if(count($inforArr) >0){
			?>
			<tr>
				<td width="100%">
				<div class="div_error">
					<div class="div_error_title">
						<?php
							echo JText::_('OS_ERROR');
							echo ": ";
							echo JText::_('OS_THERE_IS_NOT_ENOUGH_SLOTS_FOR_YOUR_BOOKING_REQUEST');
						?>
					</div>
				</div>
				</td>
			</tr>
			<?php
			//content of error
			
				for($i=0;$i<count($inforArr);$i++){
					$row = $inforArr[$i];
					HTML_OSappscheduleInformation::bodyHtml($service,$employee,$row);
				}
			}else{
				?>
			<tr>
				<td width="100%">
				<div class="div_error">
					<div class="div_pass_title">
						<?php
							echo JText::_('OS_CHECKING_COMPLETE_YOU_PLEASE_SELECT_BELLOW_OPTIONS');
						?>
					</div>
				</div>
				</td>
			</tr>
			<?php
			}
			?>
			<tr>
				<td width="100%" style="padding:10px;text-align:center;">
					<?php if(count($inforArr) > 0) {?>
					<input type="button" class="btn btn-info" value="<?php echo JText::_('OS_ADD_TO_CART')?>" onclick="javascript:addtoCart2('<?php echo JURI::root();?>',<?php  echo $service->id?>,<?php echo $employee->id?>,'<?php echo $inforArr[0]->start_time?>','<?php echo $vid?>','<?php echo intval($dateArr[0]);?>','<?php echo intval($dateArr[1]);?>','<?php echo $dateArr[2];?>');"/>
					<?php  } ?>
					<input type="button" class="btn btn-warning" value="<?php echo JText::_('OS_RE_SELECT')?>" onclick="javascript:closeForm('<?php echo $dateArr[2];?>','<?php echo $dateArr[1];?>','<?php echo $dateArr[0];?>')"/>
				</td>
			</tr>
		</table>
		<input type="hidden"  name="live_site" id="live_site" value="<?php echo JURI::root()?>" />
		<input type="hidden" name="vid" id="vid" value="<?php echo $vid?>" />
		<input type="hidden" name="service_time_type_<?php echo $service->id?>" id="service_time_type_<?php echo $service->id?>" value="<?php echo $service->service_time_type;?>"/>
		</div>
		<?php
	}
	
	/**
	 * Show error content
	 *
	 * @param unknown_type $service
	 * @param unknown_type $employee
	 * @param unknown_type $date
	 * @param unknown_type $number_slots
	 * @param unknown_type $bookingdatearr
	 * @param unknown_type $lists
	 */
	function bodyHTML($service,$employee,$row){
		global $mainframe,$configClass;
		?>
		<tr>
			<td width="100%" style="border:1px solid #CCC !important;padding:5px;">
				<strong><?php echo JText::_('OS_SERVICE')?></strong>:&nbsp;<?php echo $service->service_name;?>
				<BR />
				<strong><?php echo JText::_('OS_EMPLOYEE')?></strong>:&nbsp;<?php echo $employee->employee_name;?>
				<BR />
				<strong><?php echo JText::_('OS_BOOK_FROM')?></strong>:&nbsp;<?php echo date($configClass['time_format'],$row->start_time)?>
				<BR />
				<strong><?php echo JText::_('OS_BOOK_TO')?></strong>:&nbsp;<?php echo date($configClass['time_format'],$row->end_time)?>
				<BR />
				<strong><?php echo JText::_('OS_ON')?></strong>:&nbsp;<?php echo date($configClass['date_format'],$row->start_time)?>
				<BR />
				<?php
				if($row->return == 0){
					?>
					<strong><?php echo JText::_('OS_STATUS');?>:&nbsp;<font color="red"><?php echo JText::_('OS_NOT_AVAILABLE')?></font></strong>
					<BR />
					<?php
				}elseif($row->return == 1){
					if($service->service_time_type == 1){
					?>
					<strong><?php echo JText::_('OS_YOU_ENTERED')?></strong>:&nbsp;<?php echo $row->nslots?> <?php echo JText::_('OS_SLOTS')?>
					<BR />
					<strong><?php echo JText::_('OS_NUMBER_SLOTS_AVAILABLE')?></strong>:&nbsp;<font color="Red"><?php echo $row->number_slots_available;?> <?php echo JText::_('OS_SLOTS')?></font>
					&nbsp;&nbsp;
					<?php
					echo $row->list;
					?>
					<BR />
					<?php
					}
				}
				?>
				<img src="<?php echo JURI::root()?>components/com_osservicesbooking/style/images/icon-16-deny.png"/>
				<a href="javascript:removeTempTimeSlot(<?php echo $row->id?>,'<?php echo JText::_('OS_ARE_YOU_SURE_YOU_WANT_TO_REMOVE_ITEM')?>','<?php echo JURI::root()?>');">
					<?php echo JText::_('OS_REMOVE_TIME_SLOT');?>
				</a>
			</td>
		</tr>
		<tr>
			<td height="10"></td>
		</tr>
		<?php
	}
	
	function showError($sids){
		
	}
}
?>