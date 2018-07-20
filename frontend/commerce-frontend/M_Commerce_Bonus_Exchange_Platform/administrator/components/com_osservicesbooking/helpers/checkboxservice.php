<?php
/*------------------------------------------------------------------------
# checkboxservice.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution tem
# copyright Copyright (C) 2015 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die;

class ServiceCheckbox{
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $services
	 */
	function checkingBreaktime($sid,$eid){
		$db = JFactory::getDbo();
		$serviceids = array();
		if($eid > 0){
			$db->setQuery("SELECT `service_id` FROM #__app_sch_employee_service WHERE `employee_id` = '$eid'");
			//$serviceids = $db->loadResultArray();
			$results = $db->loadObjectList();
			if(count($results) > 0){
				for($i=0;$i<count($results);$i++){
					$serviceids[$i] = $results[$i]->service_id;
				}
			}
			if (!count($serviceids)) $serviceids = array();
		}
		
		$db->setQuery("SELECT `id`, `service_name` FROM #__app_sch_services WHERE `published`=1 and id = '$sid' ORDER BY service_name, ordering ");
		$services = $db->loadObjectList();
		$html = '';
		foreach ($services as $service) {
			$checked = '';
			$db->setQuery("Select * from #__app_sch_employee_service where employee_id = '$eid' and service_id = '$service->id'");
			$item = $db->loadObject();
			$additional_cost = $item->additional_price;
			$vid = $item->vid;
			
			$db->setQuery("Select id as value, address as text from #__app_sch_venues where published = '1' and id in (Select vid from #__app_sch_venue_services where sid = '$service->id') order by address");
			$venues = $db->loadObjectList();
			$venue  = "";
			if(count($venues) > 0){
				$venueArr 	 = array();
				$venueArr[]  = JHTML::_('select.option','','');
				$venueArr    = array_merge($venueArr,$venues);
				$venue 		.= JText::_('OS_VENUE').": ";
				$venue 		.= JHTML::_('select.genericlist',$venueArr,'vid_'.$service->id,'class="input-large"','value','text',$vid);
			}
			
			if (in_array($service->id,$serviceids))$checked = 'checked="checked"';
			
			$db->setQuery("Select * from #__app_sch_breaktime where eid = '$eid' and sid = '$service->id'");
			$breaks = $db->loadObjectList();
			
			$lists['hours'] = OSappscheduleEmployee::generateHoursIncludeSecond();
			
			ob_start();
			?>
			<tr>
				<td width="100%" colspan="4" style="font-size:12px;">
					<b><?php echo JText::_('OS_WORKING_TIME')?>:</b>
					<BR/>
					<?php
					if($item->mo == 1){
						$mo = "checked";
					}else{
						$mo = "";
					}
					if($item->tu == 1){
						$tu = "checked";
					}else{
						$tu = "";
					}
					if($item->we == 1){
						$we = "checked";
					}else{
						$we = "";
					}
					if($item->th == 1){
						$th = "checked";
					}else{
						$th = "";
					}
					if($item->fr == 1){
						$fr = "checked";
					}else{
						$fr = "";
					}
					if($item->sa == 1){
						$sa = "checked";
					}else{
						$sa = "";
					}
					if($item->su == 1){
						$su = "checked";
					}else{
						$su = "";
					}
					?>
					<table width="100%" style="border:1px solid #CCC;" class="table table-striped">  
						<tr>
							<td style="background-color:gray;color:white;text-align:center;font-weight:bold;">
								<?php echo JText::_('OS_DATE');?>
							</td>
							<td style="background-color:gray;color:white;text-align:center;font-weight:bold;">
								<?php echo JText::_('OS_WORK');?>
							</td>
							<td style="background-color:gray;color:white;text-align:center;font-weight:bold;">
								<?php echo JText::_('OS_BREAKTIME');?>
							</td>
						</tr>
						<tbody>
						<tr>
							<td width="20%" align="left" style="border-right:1px solid #CCC;border-bottom:1px solid #CCC;" valign="top">
								<B><?php echo JText::_('OS_DAY_OF_WEEK_MONDAY');?></B>
							</td>
							<td width="20%" align="center" style="border-right:1px solid #CCC;border-bottom:1px solid #CCC;text-align:center;" valign="top">
								<input type="checkbox" name="mo_<?php echo $service->id?>" id="mo_<?php echo $service->id?>" value="<?php echo intval($item->mo)?>" <?php echo $mo?> onclick="javascript:changeValue('mo_<?php echo $service->id?>');">
							</td>
							<td width="60%" style="border-bottom:1px solid #CCC;">
								<table width="100%">
									<?php
									$i1 = 0;
									$db->setQuery("Select * from #__app_sch_employee_service_breaktime where date_in_week = '1' and eid = '$eid' and sid = '$service->id' order by break_from");
									$breaks = $db->loadObjectList();
									if(count($breaks) > 0){
										 for($i1=0;$i1<count($breaks);$i1++){
										 	$break = $breaks[$i1];
										 	?>
										 	<tr>
										 		<td width="50%" align="left">
										 			<?php echo JText::_('OS_START');?>:
										 			<?php
													echo JHTML::_('select.genericlist',$lists['hours'],'start_from'.$service->id.$i1.'_1','class="input-small"','value','text',$break->break_from);
													?>
										 		</td>
										 		<td width="50%" align="left">
										 			<?php echo JText::_('OS_END');?>:
										 			<?php
													echo JHTML::_('select.genericlist',$lists['hours'],'end_to'.$service->id.$i1.'_1','class="input-small"','value','text',$break->break_to);
													?>
										 		</td>
										 	</tr>
										 	<?php
										 }
									}
									if($i1<3){
									 	 for($j=$i1;$j<4;$j++){
										 	?>
										 	<tr>
										 		<td width="50%" align="left">
										 			<?php echo JText::_('OS_START');?>:
										 			<?php
													echo JHTML::_('select.genericlist',$lists['hours'],'start_from'.$service->id.$j.'_1','class="input-small"','value','text');
													?>
										 		</td>
										 		<td width="50%" align="left">
										 			<?php echo JText::_('OS_END');?>:
										 			<?php
													echo JHTML::_('select.genericlist',$lists['hours'],'end_to'.$service->id.$j.'_1','class="input-small"','value','text');
													?>
										 		</td>
										 	</tr>
										 	<?php
									 	 }
									 }
									?>
								</table>
							</td>
						</tr>
						<tr>
							<td width="20%" align="left" style="border-right:1px solid #CCC;border-bottom:1px solid #CCC;" valign="top">
								<B><?php echo JText::_('OS_DAY_OF_WEEK_TUESDAY');?></B>
							</td>
							<td width="20%" align="center" style="border-right:1px solid #CCC;border-bottom:1px solid #CCC;text-align:center;" valign="top">
								<input type="checkbox" name="tu_<?php echo $service->id?>" id="tu_<?php echo $service->id?>" value="<?php echo intval($item->tu)?>" <?php echo $tu?> onclick="javascript:changeValue('tu_<?php echo $service->id?>');">
							</td>
							<td width="60%" style="border-bottom:1px solid #CCC;">
								<table width="100%">
									<?php
									$i1 = 0;
									$db->setQuery("Select * from #__app_sch_employee_service_breaktime where date_in_week = '2' and eid = '$eid' and sid = '$service->id' order by break_from");
									$breaks = $db->loadObjectList();
									if(count($breaks) > 0){
										 for($i1=0;$i1<count($breaks);$i1++){
										 	$break = $breaks[$i1];
										 	?>
										 	<tr>
										 		<td width="50%" align="left">
										 			<?php echo JText::_('OS_START');?>:
										 			<?php
													echo JHTML::_('select.genericlist',$lists['hours'],'start_from'.$service->id.$i1.'_2','class="input-small"','value','text',$break->break_from);
													?>
										 		</td>
										 		<td width="50%" align="left">
										 			<?php echo JText::_('OS_END');?>:
										 			<?php
													echo JHTML::_('select.genericlist',$lists['hours'],'end_to'.$service->id.$i1.'_2','class="input-small"','value','text',$break->break_to);
													?>
										 		</td>
										 	</tr>
										 	<?php
										 }
									}
									if($i1<3){
									 	 for($j=$i1;$j<4;$j++){
										 	?>
										 	<tr>
										 		<td width="50%" align="left">
										 			<?php echo JText::_('OS_START');?>:
										 			<?php
													echo JHTML::_('select.genericlist',$lists['hours'],'start_from'.$service->id.$j.'_2','class="input-small"','value','text');
													?>
										 		</td>
										 		<td width="50%" align="left">
										 			<?php echo JText::_('OS_END');?>:
										 			<?php
													echo JHTML::_('select.genericlist',$lists['hours'],'end_to'.$service->id.$j.'_2','class="input-small"','value','text');
													?>
										 		</td>
										 	</tr>
										 	<?php
									 	 }
									 }
									?>
								</table>
							</td>
						</tr>
						<tr>
							<td width="20%" align="left" style="border-right:1px solid #CCC;border-bottom:1px solid #CCC;" valign="top">
								<B><?php echo JText::_('OS_DAY_OF_WEEK_WEDNESDAY');?></B>
							</td>
							<td width="20%" align="center" style="border-right:1px solid #CCC;border-bottom:1px solid #CCC;text-align:center;" valign="top">
								<input type="checkbox" name="we_<?php echo $service->id?>" id="we_<?php echo $service->id?>" value="<?php echo intval($item->we)?>" <?php echo $we?> onclick="javascript:changeValue('we_<?php echo $service->id?>');">
							</td>
							<td width="60%" style="border-bottom:1px solid #CCC;">
								<table width="100%">
									<?php
									$i1 = 0;
									$db->setQuery("Select * from #__app_sch_employee_service_breaktime where date_in_week = '3' and eid = '$eid' and sid = '$service->id' order by break_from");
									$breaks = $db->loadObjectList();
									if(count($breaks) > 0){
										 for($i1=0;$i1<count($breaks);$i1++){
										 	$break = $breaks[$i1];
										 	?>
										 	<tr>
										 		<td width="50%" align="left">
										 			<?php echo JText::_('OS_START');?>:
										 			<?php
													echo JHTML::_('select.genericlist',$lists['hours'],'start_from'.$service->id.$i1.'_3','class="input-small"','value','text',$break->break_from);
													?>
										 		</td>
										 		<td width="50%" align="left">
										 			<?php echo JText::_('OS_END');?>:
										 			<?php
													echo JHTML::_('select.genericlist',$lists['hours'],'end_to'.$service->id.$i1.'_3','class="input-small"','value','text',$break->break_to);
													?>
										 		</td>
										 	</tr>
										 	<?php
										 }
									}
									if($i1<3){
									 	 for($j=$i1;$j<4;$j++){
										 	?>
										 	<tr>
										 		<td width="50%" align="left">
										 			<?php echo JText::_('OS_START');?>:
										 			<?php
													echo JHTML::_('select.genericlist',$lists['hours'],'start_from'.$service->id.$j.'_3','class="input-small"','value','text');
													?>
										 		</td>
										 		<td width="50%" align="left">
										 			<?php echo JText::_('OS_END');?>:
										 			<?php
													echo JHTML::_('select.genericlist',$lists['hours'],'end_to'.$service->id.$j.'_3','class="input-small"','value','text');
													?>
										 		</td>
										 	</tr>
										 	<?php
									 	 }
									 }
									?>
								</table>
							</td>
						</tr>
						<tr>
							<td width="20%" align="left" style="border-right:1px solid #CCC;border-bottom:1px solid #CCC;" valign="top">
								<B><?php echo JText::_('OS_DAY_OF_WEEK_THURSDAY');?></B>
							</td>
							<td width="20%" align="center" style="border-right:1px solid #CCC;border-bottom:1px solid #CCC;text-align:center;" valign="top">
								<input type="checkbox" name="th_<?php echo $service->id?>" id="th_<?php echo $service->id?>" value="<?php echo intval($item->th)?>" <?php echo $th?>  onclick="javascript:changeValue('th_<?php echo $service->id?>');">
							</td>
							<td width="60%" style="border-bottom:1px solid #CCC;">
								<table width="100%">
									<?php
									$i1 = 0;
									$db->setQuery("Select * from #__app_sch_employee_service_breaktime where date_in_week = '4' and eid = '$eid' and sid = '$service->id' order by break_from");
									$breaks = $db->loadObjectList();
									if(count($breaks) > 0){
										 for($i1=0;$i1<count($breaks);$i1++){
										 	$break = $breaks[$i1];
										 	?>
										 	<tr>
										 		<td width="50%" align="left">
										 			<?php echo JText::_('OS_START');?>:
										 			<?php
													echo JHTML::_('select.genericlist',$lists['hours'],'start_from'.$service->id.$i1.'_4','class="input-small"','value','text',$break->break_from);
													?>
										 		</td>
										 		<td width="50%" align="left">
										 			<?php echo JText::_('OS_END');?>:
										 			<?php
													echo JHTML::_('select.genericlist',$lists['hours'],'end_to'.$service->id.$i1.'_4','class="input-small"','value','text',$break->break_to);
													?>
										 		</td>
										 	</tr>
										 	<?php
										 }
									}
									if($i1<3){
									 	 for($j=$i1;$j<4;$j++){
										 	?>
										 	<tr>
										 		<td width="50%" align="left">
										 			<?php echo JText::_('OS_START');?>:
										 			<?php
													echo JHTML::_('select.genericlist',$lists['hours'],'start_from'.$service->id.$j.'_4','class="input-small"','value','text');
													?>
										 		</td>
										 		<td width="50%" align="left">
										 			<?php echo JText::_('OS_END');?>:
										 			<?php
													echo JHTML::_('select.genericlist',$lists['hours'],'end_to'.$service->id.$j.'_4','class="input-small"','value','text');
													?>
										 		</td>
										 	</tr>
										 	<?php
									 	 }
									 }
									?>
								</table>
							</td>
						</tr>
						<tr>
							<td width="20%" align="left" style="border-right:1px solid #CCC;border-bottom:1px solid #CCC;" valign="top">
								<B><?php echo JText::_('OS_DAY_OF_WEEK_FRIDAY');?></B>
							</td>
							<td width="20%" align="center" style="border-right:1px solid #CCC;border-bottom:1px solid #CCC;text-align:center;" valign="top">
								<input type="checkbox" name="fr_<?php echo $service->id?>" id="fr_<?php echo $service->id?>" value="<?php echo intval($item->fr)?>" <?php echo $fr?>  onclick="javascript:changeValue('fr_<?php echo $service->id?>');">
							</td>
							<td width="60%" style="border-bottom:1px solid #CCC;">
								<table width="100%">
									<?php
									$i1 = 0;
									$db->setQuery("Select * from #__app_sch_employee_service_breaktime where date_in_week = '5' and eid = '$eid' and sid = '$service->id' order by break_from");
									$breaks = $db->loadObjectList();
									if(count($breaks) > 0){
										 for($i1=0;$i1<count($breaks);$i1++){
										 	$break = $breaks[$i1];
										 	?>
										 	<tr>
										 		<td width="50%" align="left">
										 			<?php echo JText::_('OS_START');?>:
										 			<?php
													echo JHTML::_('select.genericlist',$lists['hours'],'start_from'.$service->id.$i1.'_5','class="input-small"','value','text',$break->break_from);
													?>
										 		</td>
										 		<td width="50%" align="left">
										 			<?php echo JText::_('OS_END');?>:
										 			<?php
													echo JHTML::_('select.genericlist',$lists['hours'],'end_to'.$service->id.$i1.'_5','class="input-small"','value','text',$break->break_to);
													?>
										 		</td>
										 	</tr>
										 	<?php
										 }
									}
									if($i1<3){
									 	 for($j=$i1;$j<4;$j++){
										 	?>
										 	<tr>
										 		<td width="50%" align="left">
										 			<?php echo JText::_('OS_START');?>:
										 			<?php
													echo JHTML::_('select.genericlist',$lists['hours'],'start_from'.$service->id.$j.'_5','class="input-small"','value','text');
													?>
										 		</td>
										 		<td width="50%" align="left">
										 			<?php echo JText::_('OS_END');?>:
										 			<?php
													echo JHTML::_('select.genericlist',$lists['hours'],'end_to'.$service->id.$j.'_5','class="input-small"','value','text');
													?>
										 		</td>
										 	</tr>
										 	<?php
									 	 }
									 }
									?>
								</table>
							</td>
						</tr>
						<tr>
							<td width="20%" align="left" style="border-right:1px solid #CCC;border-bottom:1px solid #CCC;" valign="top">
								<B><?php echo JText::_('OS_DAY_OF_WEEK_SATURDAY');?></B>
							</td>
							<td width="20%" align="center" style="border-right:1px solid #CCC;border-bottom:1px solid #CCC;text-align:center;" valign="top">
								<input type="checkbox" name="sa_<?php echo $service->id?>" id="sa_<?php echo $service->id?>" value="<?php echo intval($item->sa)?>" <?php echo $sa?> onclick="javascript:changeValue('sa_<?php echo $service->id?>');">
							</td>
							<td width="60%" style="border-bottom:1px solid #CCC;">
								<table width="100%">
									<?php
									$i1 = 0;
									$db->setQuery("Select * from #__app_sch_employee_service_breaktime where date_in_week = '6' and eid = '$eid' and sid = '$service->id' order by break_from");
									$breaks = $db->loadObjectList();
									if(count($breaks) > 0){
										 for($i1=0;$i1<count($breaks);$i1++){
										 	$break = $breaks[$i1];
										 	?>
										 	<tr>
										 		<td width="50%" align="left">
										 			<?php echo JText::_('OS_START');?>:
										 			<?php
													echo JHTML::_('select.genericlist',$lists['hours'],'start_from'.$service->id.$i1.'_6','class="input-small"','value','text',$break->break_from);
													?>
										 		</td>
										 		<td width="50%" align="left">
										 			<?php echo JText::_('OS_END');?>:
										 			<?php
													echo JHTML::_('select.genericlist',$lists['hours'],'end_to'.$service->id.$i1.'_6','class="input-small"','value','text',$break->break_to);
													?>
										 		</td>
										 	</tr>
										 	<?php
										 }
									}
									if($i1<3){
									 	 for($j=$i1;$j<4;$j++){
										 	?>
										 	<tr>
										 		<td width="50%" align="left">
										 			<?php echo JText::_('OS_START');?>:
										 			<?php
													echo JHTML::_('select.genericlist',$lists['hours'],'start_from'.$service->id.$j.'_6','class="input-small"','value','text');
													?>
										 		</td>
										 		<td width="50%" align="left">
										 			<?php echo JText::_('OS_END');?>:
										 			<?php
													echo JHTML::_('select.genericlist',$lists['hours'],'end_to'.$service->id.$j.'_6','class="input-small"','value','text');
													?>
										 		</td>
										 	</tr>
										 	<?php
									 	 }
									 }
									?>
								</table>
							</td>
						</tr>
						<tr>
							<td width="20%" align="left" style="border-bottom:1px solid #CCC;border-right:1px solid #CCC;" valign="top">
								<B><?php echo JText::_('OS_DAY_OF_WEEK_SUNDAY');?></B>
							</td>
							<td width="20%" align="center" style="border-bottom:1px solid #CCC;border-right:1px solid #CCC;text-align:center;" valign="top">
								<input type="checkbox" name="su_<?php echo $service->id?>" id="su_<?php echo $service->id?>" value="<?php echo intval($item->su)?>" <?php echo $su?> onclick="javascript:changeValue('su_<?php echo $service->id?>');">
							</td>
							<td width="60%" style="border-bottom:1px solid #CCC;">
								<table width="100%">
									<?php
									$i1 = 0;
									$db->setQuery("Select * from #__app_sch_employee_service_breaktime where date_in_week = '7' and eid = '$eid' and sid = '$service->id' order by break_from");
									$breaks = $db->loadObjectList();
									if(count($breaks) > 0){
										 for($i1=0;$i1<count($breaks);$i1++){
										 	$break = $breaks[$i1];
										 	?>
										 	<tr>
										 		<td width="50%" align="left">
										 			<?php echo JText::_('OS_START');?>:
										 			<?php
													echo JHTML::_('select.genericlist',$lists['hours'],'start_from'.$service->id.$i1.'_7','class="input-small"','value','text',$break->break_from);
													?>
										 		</td>
										 		<td width="50%" align="left">
										 			<?php echo JText::_('OS_END');?>:
										 			<?php
													echo JHTML::_('select.genericlist',$lists['hours'],'end_to'.$service->id.$i1.'_7','class="input-small"','value','text',$break->break_to);
													?>
										 		</td>
										 	</tr>
										 	<?php
										 }
									}
									if($i1<3){
									 	 for($j=$i1;$j<4;$j++){
										 	?>
										 	<tr>
										 		<td width="50%" align="left">
										 			<?php echo JText::_('OS_START');?>:
										 			<?php
													echo JHTML::_('select.genericlist',$lists['hours'],'start_from'.$service->id.$j.'_7','class="input-small"','value','text');
													?>
										 		</td>
										 		<td width="50%" align="left">
										 			<?php echo JText::_('OS_END');?>:
										 			<?php
													echo JHTML::_('select.genericlist',$lists['hours'],'end_to'.$service->id.$j.'_7','class="input-small"','value','text');
													?>
										 		</td>
										 	</tr>
										 	<?php
									 	 }
									 }
									?>
								</table>
							</td>
						</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<?php
			$working_time = ob_get_contents();
			ob_end_clean();
			
			$html .= '<div style="padding:5px;"><table  width="100%"><tr><td width="2%"><input type="checkbox" name="service_id[]" id="service_id_'.$service->id.'" value="'.$service->id.'" '.$checked.'></td><td width="15%"><B>'.$service->service_name.'</B> </td><td width="30%">'.$venue.'</td><td width="30%">'.JText::_('OS_ADDITIONAL_COST').': <input type="text" size="4" class="input-mini" value="'.$additional_cost.'" name="add_'.$service->id.'"></td></tr>'.$working_time.$body.'</table></div>';
		}
		
		return $html;
	}
}


class HelperDateTime{
	/**
	 * Enter description here...
	 *
	 */
	function CreatDropHour($name = 'default',$select=null,$attribute='null'){
		$option = array();
		for ($i=0; $i<=24; $i++){
			$option[] = JHtml::_('select.option',$i,($i<10)? "0$i":$i);
		}
		return JHtml::_('select.genericlist',$option,$name,$attribute,'value','text',$select);		
	}
	
	function CreatDropMinuste($name='default',$select=null,$attribute=null){
		$option = array();
		for ($i=0; $i<60; $i+=5){
			$option[] = JHtml::_('select.option',$i,($i<10)? "0$i":$i);
		}
		return JHtml::_('select.genericlist',$option,$name,$attribute,'value','text',$select);	
	}
}


/**
 * class menu
 *
 */
class Helpermenu{
	
	function creatmenu($option, $task,$subMenus = array()){
		foreach ($subMenus as $name => $extension) {
			JSubMenuHelper::addEntry(JText::_( $name ), "index.php?option=$option&task=$extension"."_list", ($extension == $task));
		}
	}
	
	
}


?>