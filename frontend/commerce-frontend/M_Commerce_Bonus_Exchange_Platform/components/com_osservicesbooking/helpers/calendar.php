<?php
/*------------------------------------------------------------------------
# calendar.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2015 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// No direct access.
defined('_JEXEC') or die;

/**
 * Calendar class
 *
 */
class HelperOSappscheduleCalendar{
	/**
	 * Return number days in month
	**/
	public static function ndaysinmonth($month, $year) {
		if(checkdate($month, 31, $year)) return 31;
		if(checkdate($month, 30, $year)) return 30;
		if(checkdate($month, 29, $year)) return 29;
		if(checkdate($month, 28, $year)) return 28;
		return 0; // error
	} 
	/**
	 * Init the calendar
	 *
	 * @param unknown_type $option
	 */
	function initCalendar($year,$month,$category,$employee_id,$vid,$date_from,$date_to,$ajax=0){
		global $mainframe,$configClass;
		$realtime					= HelperOSappscheduleCommon::getRealTime();
		$current_month 				= intval(date("m",$realtime));
		$current_year				= intval(date("Y",$realtime));
		$current_date				= intval(date("d",$realtime));

		if($date_from != ""){
			$date_from_array = explode(" ",$date_from);
			$date_from_int = strtotime($date_from_array[0]);
			if($date_from_int > HelperOSappscheduleCommon::getRealTime()){
				$current_year = date("Y",$date_from_int);
				$current_month = intval(date("m",$date_from_int));
				$current_date = intval(date("d",$date_from_int));
			}
		}
		$date_from_int = strtotime($date_from);
		$date_to_int = strtotime($date_to);
		if($ajax == 0){
			if($current_year >  0){
				$year = $current_year;
			}
			if($current_month >  0){
				$month = $current_month;
			}
		}
		
		//set up the first date
		$start_date_current_month 	= strtotime($year."-".$month."-01");
		if($configClass['start_day_in_week'] == "monday"){
			$start_date_in_week			= date("N",$start_date_current_month);
		}else{
			$start_date_in_week			= date("w",$start_date_current_month);	
		}
		//$number_days_in_month		= cal_days_in_month(CAL_GREGORIAN,$month,$year);
		$number_days_in_month		= self::ndaysinmonth($month,$year);
		//$number_days_in_month		= HelperOSappscheduleCalendar::days_in_month($current_month,$current_year);


		$monthArr = array(JText::_('OS_JANUARY'),JText::_('OS_FEBRUARY'),JText::_('OS_MARCH'),JText::_('OS_APRIL'),JText::_('OS_MAY'),JText::_('OS_JUNE'),JText::_('OS_JULY'),JText::_('OS_AUGUST'),JText::_('OS_SEPTEMBER'),JText::_('OS_OCTOBER'),JText::_('OS_NOVEMBER'),JText::_('OS_DECEMBER'));
		?>
        <div id="calendardetails">
            <div id="cal<?php echo intval($month)?><?php echo $year?>" style="display:<?php echo $display?>;" class="row-fluid bookingformdiv">
                <div class="span12 <?php echo $configClass['header_style']?>">
                    <table width="100%" class="apptable">
                        <tr>
                            <td width="20%" align="center" style="font-weight:bold;padding:0px;border:0px !important;" class="headercalendar">
                                <?php
                                if(($date_from != "") and ($year == date("Y",$date_from_int)) and ($month == intval(date("m",$date_from_int)))){
                                }
                                elseif(($year == $current_year) and ($month == $current_month)){
                                }else{
                                ?>
                                <a href="javascript:prev('<?php echo JUri::root();?>','<?php echo $category; ?>','<?php echo $employee_id;?>','<?php echo $vid;?>','<?php echo $date_from;?>','<?php echo $date_to;?>')" class="applink">
                                <?php
                                if($configClass['calendar_arrow'] != ""){
                                    ?>
                                    <img src="<?php echo JURI::root()?>components/com_osservicesbooking/asset/images/icons/previous_<?php echo $configClass['calendar_arrow'];?>.png" style="border:0px;" />
                                    <?php
                                }else{
                                    ?>
                                    <
                                <?php } ?>
                                </a>
                                <?php
                                }
                                ?>
                            </td>
                            <td width="60%" align="center" style="height:25px;font-weight:bold;padding:0px;border:0px !important;" class="headercalendar">
                                <?php
                                echo $monthArr[$month-1];
                                ?>
                                &nbsp;
                                <?php echo $year;?>
                            </td>
                            <td width="20%" align="center" style="font-weight:bold;padding:0px;border:0px !important;" class="headercalendar">
                                <?php
                                if(($date_to != "") and ($year == date("Y",$date_to_int)) and ($month == intval(date("m",$date_to_int)))){
                                }elseif(($year == $current_year + 2) and ($month == 12)){
                                }else{
                                ?>
                                <a href="javascript:next('<?php echo JUri::root();?>','<?php echo $category; ?>','<?php echo $employee_id;?>','<?php echo $vid;?>','<?php echo $date_from;?>','<?php echo $date_to;?>')" class="applink">
                                <?php
                                if($configClass['calendar_arrow'] != ""){
                                    ?>
                                    <img src="<?php echo JURI::root()?>components/com_osservicesbooking/asset/images/icons/next_<?php echo $configClass['calendar_arrow'];?>.png" style="border:0px;" />
                                    <?php
                                }else{
                                    ?>
                                    >
                                <?php } ?>
                                </a>
                                <?php
                                }
                                ?>
                            </td>
                        </tr>
                        <?php if($configClass['show_dropdown_month_year'] == 1){?>
                        <tr>
                            <td width="100%" colspan="3" style="padding:3px;text-align:center;">
                                <select name="ossm" class="input-small" id="ossm" onchange="javascript:updateMonth(this.value)">
                                    <?php
                                    for($i=0;$i<count($monthArr);$i++){
                                        if(intval($month) == $i + 1){
                                            $selected = "selected";
                                        }else{
                                            $selected = "";
                                        }
                                        ?>
                                        <option value="<?php echo $i + 1?>" <?php echo $selected?>><?php echo $monthArr[$i]?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                <select name="ossy" class="input-small" id="ossy" onchange="javascript:updateYear(this.value)">
                                    <?php
                                    for($i=date("Y",$realtime);$i<=date("Y",$realtime)+3;$i++){
                                        if(intval($year) == $i){
                                            $selected = "selected";
                                        }else{
                                            $selected = "";
                                        }
                                        ?>
                                        <option value="<?php echo $i?>" <?php echo $selected?>><?php echo $i?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                <input type="button" class="<?php echo $configClass['calendar_normal_style'];?>" value="<?php echo JText::_('OS_GO');?>" onclick="javascript:calendarMovingSmall('<?php echo JUri::root();?>','<?php echo $category; ?>','<?php echo $employee_id;?>','<?php echo $vid;?>','<?php echo $date_from;?>','<?php echo $date_to;?>');">
                            </td>
                        </tr>
                        <?php } ?>
                    </table>
                </div>
                <table  width="100%">
                    <tr>
                        <?php
                        if($configClass['start_day_in_week'] == "sunday"){
                        ?>
                        <td class="header_calendar">
                            <?php echo JText::_('OS_SUN')?>
                        </td>
                        <?php
                        }
                        ?>
                        <td class="header_calendar">
                            <?php echo JText::_('OS_MON')?>
                        </td>
                        <td class="header_calendar">
                            <?php echo JText::_('OS_TUE')?>
                        </td>
                        <td class="header_calendar">
                            <?php echo JText::_('OS_WED')?>
                        </td>
                        <td class="header_calendar">
                            <?php echo JText::_('OS_THU')?>
                        </td>
                        <td class="header_calendar">
                            <?php echo JText::_('OS_FRI')?>
                        </td>
                        <td class="header_calendar">
                            <?php echo JText::_('OS_SAT')?>
                        </td>
                        <?php
                        if($configClass['start_day_in_week'] == "monday"){
                        ?>
                        <td class="header_calendar">
                            <?php echo JText::_('OS_SUN')?>
                        </td>
                        <?php
                        }
                        ?>
                    </tr>
                    <tr>
                        <?php
                        if($configClass['start_day_in_week'] == "sunday"){
                            $start = 0;
                        }else{
                            $start = 1;
                        }
                        for($i=$start;$i<$start_date_in_week;$i++){
                            //empty
                            ?>
                            <td>
                            </td>
                            <?php
                        }
                        $j = $start_date_in_week-1;

                        for($i=1;$i<=$number_days_in_month;$i++){
                            $j++;

                            //check to see if today
                            $today = strtotime($current_year."-".$current_month."-".$current_date);
                            $checkdate = strtotime($year."-".$month."-".$i);
                            if($today > $checkdate){
                                $classname = "btn btn-gray";
                                $show_link = 0;
                            }
                            elseif(($date_from != "") and ($date_from_int > $checkdate)){
                                $classname = "btn btn-gray";
                                $show_link = 0;
                            }
                            elseif(($date_to != "") and ($date_to_int < $checkdate)){
                                $classname = "btn btn-gray";
                                $show_link = 0;
                            }else{

                                $show_link = 1;
                                if($configClass['disable_calendar_in_off_date'] == 1)
                                {
                                    $services  = OSBHelper::getServices($category,$employee_id,$vid);
                                    $employees = OSBHelper::loadEmployees($services,$employee_id,$checkdate,$vid);
                                    $venue_check = 1;
                                    if($vid > 0){
                                        $venue_check = OSBHelper::checkDateInVenue($vid,$checkdate);
                                    }
                                    if(($i == $current_date) and ($month == $current_month) and ($year == $current_year)){
                                        $classname = $configClass['calendar_currentdate_style'];
                                    }elseif(OSBHelper::isOffDay($checkdate)){
                                        $classname = "btn btn-gray";
                                        $show_link = 0;
                                    }elseif(count($services) == 0){
                                        $classname = "btn btn-gray";
                                        $show_link = 0;
                                    }elseif(! $employees){
                                        $classname = "btn btn-gray";
                                        $show_link = 0;
                                    }elseif($venue_check == 0){
                                        $classname = "btn btn-gray";
                                        $show_link = 0;
                                    }else{
                                        $classname = $configClass['calendar_normal_style'];
                                    }
                                }else {
                                    if(($i == $current_date) and ($month == $current_month) and ($year == $current_year)){
                                        $classname = $configClass['calendar_currentdate_style'];
                                    }else{
                                        $classname = $configClass['calendar_normal_style'];
                                    }
                                }
                            }

                            if($i < 9){
                                $i1 = "0".$i;
                            }else{
                                $i1 = $i;
                            }
                            ?>
                            <td id="td_cal_<?php echo $i1?>"  align="center" style="padding:0px !important;padding-bottom:3px !important;padding-top:3px !important;">
                                <div class="<?php echo $classname; ?> buttonpadding10" style="" id="a<?php echo $year?><?php echo $month?><?php echo $i1;?>">
                                    <?php if($show_link == 1){?>
                                    <a href="javascript:loadServices(<?php echo $year?>,<?php echo $month?>,'<?php echo $i1?>');" class="callink">
                                    <?php } ?>
                                        <?php
                                        if($i > 9){
                                            echo $i;
                                        }else{
                                            echo "0".$i;
                                        }
                                        ?>
                                    <?php if($show_link == 1){?>
                                    </a>
                                    <?php } ?>
                                </div>
                            </td>
                            <?php
                            if($configClass['start_day_in_week'] == "sunday"){
                                if($j >= 6){
                                    $j = -1;
                                    echo "</tr><tr>";
                                }
                            }else{
                                if($j >= 7){
                                    $j = 0;
                                    echo "</tr><tr>";
                                }
                            }
                        }
                        ?>
                    </tr>
                </table>
            </div>
        </div>
		<?php
	}

	/**
	 * Set up calendar for 12 months in year
	 *
	 * @param unknown_type $year
	 */
	function initCalendarForYear($year,$category,$employee_id,$vid,$date_from,$date_to){

		//for($i=1;$i<=12;$i++){
        $realtime					= HelperOSappscheduleCommon::getRealTime();
        $current_month 				= intval(date("m",$realtime));
		HelperOSappscheduleCalendar::initCalendar($year,$current_month,$category,$employee_id,$vid,$date_from,$date_to);
		//}
	}

	/**
	 * Set up calendar for months of year from -> year to
	 *
	 * @param unknown_type $yearfrom
	 * @param unknown_type $yearto
	 */
	function initCalendarForSeveralYear($yearfrom,$category,$employee_id,$vid,$date_from,$date_to){
		//for($i=$yearfrom;$i<=$yearto;$i++){
			HelperOSappscheduleCalendar::initCalendarForYear($yearfrom,$category,$employee_id,$vid,$date_from,$date_to);
		//}
	}


	/**
	 * Get avaiable time 
	 *
	 * @param unknown_type $option
	 * @param array $date (day, month, year)
	 */
	function getAvailableTime($option,$date){
		global $mainframe;
		$db = JFactory::getDbo();

		$time = $date[2]."-".$date[1]."-".$date[0];
		$db->setQuery("Select count(id) from #__app_sch_working_time_custom where `worktime_date` <= '$time' and `worktime_date_to` >= '$time'");
		$count = $db->loadResult();
		if($count > 0){
			$db->setQuery("Select start_time,end_time from #__app_sch_working_time_custom where `worktime_date` <= '$time' and `worktime_date_to` >= '$time'");
			$time = $db->loadObject();
		}else{
			$time = strtotime($time);
			$date_int_week = date("N",$time);
			//
			$db->setQuery("Select start_time,end_time from #__app_sch_working_time where id = '$date_int_week'");
			$time = $db->loadObject();
		}
		return $time;
	}


	function getAvaiableTimeFrameOfOneEmployee($date,$eid,$sid,$vid){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$config = new JConfig();
		$offset = $config->offset;
		date_default_timezone_set($offset);
		
		$realtime					= HelperOSappscheduleCommon::getRealTime();
		
		$current_hour				= date("H",$realtime);
		$current_min				= date("i",$realtime);
		$realtime_this_day			= $current_hour*3600 + $current_min*60;
		$remain_time				= 24*3600 - $realtime_this_day;
		if($vid > 0){
			$db->setQuery("Select * from #__app_sch_venues where id = '$vid'");
			$venue = $db->loadObject();
			$disable_booking_before = $venue->disable_booking_before;
			$number_date_before = $venue->number_date_before;
			$number_hour_before = $venue->number_hour_before;
			$disable_date_before = $venue->disable_date_before;
			if($disable_booking_before == 2){
				$disable_time = $realtime + ($number_date_before-1)*24*3600 + $remain_time;
			}elseif($disable_booking_before  == 3){
				$disable_time = strtotime($disable_date_before);
			}elseif($disable_booking_before == 4){
				$disable_time = $realtime + $number_hour_before*3600;
				
			}
			$disable_booking_after = $venue->disable_booking_after;
			$number_date_after = $venue->number_date_after;
			$disable_date_after = $venue->disable_date_after ;
			if($disable_booking_after == 2){
				$disable_time_after = $realtime + $number_date_after*24*3600;
			}elseif($disable_booking_after  == 3){
				$disable_time_after = strtotime($disable_date_after);
			}
		}else{
			
			$disable_booking_after = 1;
			$disable_booking_before = 1;
		}
		
		$dateformat = $date[2]."-".$date[1]."-".$date[0];
		if($configClass['multiple_work']  == 1){
			$db->setQuery("SELECT a.* FROM #__app_sch_order_items AS a INNER JOIN #__app_sch_orders AS b ON a.order_id = b.id WHERE a.eid = '$eid' AND a.sid = '$sid' and a.booking_date = '$dateformat' AND b.order_status IN ('P','S')");
		}else{
			$db->setQuery("SELECT a.* FROM #__app_sch_order_items AS a INNER JOIN #__app_sch_orders AS b ON a.order_id = b.id WHERE a.eid = '$eid' and a.booking_date = '$dateformat' AND b.order_status IN ('P','S')");
		}
		//echo $db->getQuery();
		$employees = $db->loadObjectList();
		$tempEmployee = array();
		if(count($employees) > 0){
			for($i=0;$i<count($employees);$i++){
				$employee = $employees[$i];
				$tempEmployee[$i]->start_time = $employees[$i]->start_time;
				$tempEmployee[$i]->end_time   = $employees[$i]->end_time;
				$tempEmployee[$i]->show		  = 1;
			}
		}
		
		$db->setQuery("Select * from #__app_sch_custom_breaktime where sid = '$sid' and eid = '$eid' and bdate = '$dateformat'");
		$customs = $db->loadObjectList();
		if(count($customs) > 0){
			foreach ($customs as $custom){
				$count = count($tempEmployee);
				$tempEmployee[$count]->start_time = strtotime($dateformat." ".$custom->bstart);
				$tempEmployee[$count]->end_time   = strtotime($dateformat." ".$custom->bend);
				$tempEmployee[$count]->show		  = 0;
			}
		}
		
		//print_r($tempEmployee);
		$db->setQuery("Select * from #__app_sch_service_availability where sid = '$sid' and avail_date = '$dateformat'");
		$unavailable_values = $db->loadObjectList();
		if(count($unavailable_values) > 0){
			for($i=0;$i<count($unavailable_values);$i++){
				$employee = $unavailable_values[$i];
				$count = count($tempEmployee);
				$tempEmployee[$count]->start_time = strtotime($dateformat." ".$employee->start_time);
				$tempEmployee[$count]->end_time   = strtotime($dateformat." ".$employee->end_time);
				$tempEmployee[$count]->show		  = 0;
			}
		}
		
		//print_r($tempEmployee);
		//check unique_cookie
		$unique_cookie = $_COOKIE['unique_cookie'];
		$db->setQuery("SELECT COUNT(id) FROM #__app_sch_temp_orders WHERE unique_cookie LIKE '$unique_cookie'");
		$count = $db->loadResult();
		if($count > 0){
			$db->setQuery("SELECT id FROM #__app_sch_temp_orders WHERE unique_cookie LIKE '$unique_cookie'");
			$order_id = $db->loadResult();
			$db->setQuery("SELECT * FROM #__app_sch_temp_order_items WHERE order_id = '$order_id' and sid  = '$sid' and eid  = '$eid' and booking_date = '$dateformat'");
			$temp_orders = $db->loadObjectList();
			if(count($temp_orders) > 0){
				for($i=0;$i<count($temp_orders);$i++){
					$item = $temp_orders[$i];
					$counttempEmployee = count($tempEmployee);
					$tempEmployee[$counttempEmployee]->start_time = $item->start_time;
					$tempEmployee[$counttempEmployee]->end_time = $item->end_time;
					$tempEmployee[$counttempEmployee]->show  = 1;
				}
			}
		}
		
		//echo $dateformat;
		//echo date("N",strtotime($dateformat));
		$breakTime = array();
		$db->setQuery("Select * from #__app_sch_employee_service_breaktime where sid = '$sid' and eid = '$eid' and date_in_week = '".date("N",strtotime($dateformat))."'");
		$breaks = $db->loadObjectList();
		for($i=0;$i<count($breaks);$i++){
			$break_time_start = $dateformat." ".$breaks[$i]->break_from;
			$break_time_sint  = strtotime($break_time_start);
			$break_time_end   = $dateformat." ".$breaks[$i]->break_to;
			$break_time_eint  = strtotime($break_time_end);
			$count = count($tempEmployee);
			$tempEmployee[$count]->start_time = $break_time_sint;
			$tempEmployee[$count]->end_time   = $break_time_eint;
			$tempEmployee[$count]->show = 0;
			
			$count = count($breakTime);
			$breakTime[$count]->start_time    = $break_time_sint;
			$breakTime[$count]->end_time	  = $break_time_eint;
			
		}
		//print_r($tempEmployee);
		//print_r($breaks);

		$db->setQuery("SELECT * FROM #__app_sch_services WHERE id = '$sid'");
		$service = $db->loadObject();
		$service_length  = $service->service_total;
		$service_total   = $service->service_total;
		$service_total_int = $service_total*60;

		$time = HelperOSappscheduleCalendar::getAvailableTime($option,$date);
		$starttimetoday  = strtotime($date[2]."-".$date[1]."-".$date[0]." ".$time->start_time);
		$endtimetoday    = strtotime($date[2]."-".$date[1]."-".$date[0]." ".$time->end_time);
		$cannotbookstart = $endtimetoday - $service_total_int;

		$step_in_minutes = $service->step_in_minutes;
		if($step_in_minutes == 0){
			$amount	 = $configClass['step_format']*60;
		}elseif($step_in_minutes == 1){
			$amount  = $service_total_int;
		}else{
			$amount  = $step_in_minutes*60;
		}

		$db->setQuery("Select * from #__app_sch_employee where id = '$eid'");
		$employeeDetails = $db->loadObject();

		$db->setQuery("Select additional_price from #__app_sch_employee_service where employee_id = '$eid' and service_id = '$sid'");
		$additional_price = $db->loadResult();

		$db->setQuery("Select * from #__app_sch_employee_extra_cost where eid = '$eid' and (week_date = '".date("N",strtotime($dateformat))."' or week_date = '0')");
		$extras = $db->loadObjectList();

		
		$show_no_timeslot_text = 1;
		?>
		<div class="row-fluid">
			<div class="span12">
			<?php
			if(($configClass['disable_payments']  == 1) and ($configClass['show_employee_cost'] == 1)){
			?> 
			<div class="row-fluid">
				<div style="padding-bottom:5px;" class="span12">
					<div class="available_information">
						<?php echo JText::_('OS_SERVICES_COST_WITH_THIS_EMPLOYEE')?>:
						<?php
						$service_price = OSBHelper::returnServicePrice($service->id,$date[2]."-".$date[1]."-".$date[0]);
						?>
						<?php echo $configClass['currency_symbol']?> <?php echo $service_price + $additional_price?> <?php echo $configClass['currency_format'];?>
						<?php
						if($additional_price > 0){
							?>
							(<?php echo JText::_('OS_ADDITIONAL_COST1')?>: <?php echo $configClass['currency_symbol']?> <?php echo $additional_price;?> <?php echo $configClass['currency_format'];?>)
							<?php
						}
						if(count($extras) > 0){
						?>
						<BR />
						<?php
							for($i=0;$i<count($extras);$i++){
								$extra = $extras[$i];
								echo JText::_('OS_FROM').": ".$extra->start_time;
								echo " ".JText::_('OS_TO').": ".$extra->end_time;
								echo " + ".$extra->extra_cost." ".$configClass['currency_format'];
								echo "<BR />";
							}
						}
						?>
					</div>
				</div>
			</div>
			<?php
			}
			if($configClass['show_booked_information']== 1){
				if(count($tempEmployee) > 0){
				?>
				<div class="row-fluid">
					<div style="padding-bottom:5px;" class="span12">
						<div class="available_information">
							<?php echo JText::_('OS_NOT_AVAILABLE_TIME')?>: <BR />
							<?php
							for($i=0;$i<count($tempEmployee);$i++){
								if($tempEmployee[$i]->show == 1){
									echo $i + 1;
									echo ". ";
									echo date($configClass['time_format'],$tempEmployee[$i]->start_time)." - ".date($configClass['time_format'],$tempEmployee[$i]->end_time);
									echo " (".date($configClass['date_format'],$tempEmployee[$i]->start_time).")";
									echo "<BR />";
								}
							}
							?>
						</div>
					</div>
				</div>
				<?php
				}
			}
			
			$timezone1 = $configClass['timezone1'];
			$timezone2 = $configClass['timezone2'];
			$timezone3 = $configClass['timezone3'];
			$timezone4 = $configClass['timezone4'];
			$timezone5 = $configClass['timezone5'];
			
			$timezone   = array();
			$timezone[] = $timezone1;
			$timezone[] = $timezone2;
			$timezone[] = $timezone3;
			$timezone[] = $timezone4;
			$timezone[] = $timezone5;
			?>
			<div class="row-fluid">
				<div class="span12">
                    <?php
                    if($configClass['booking_theme'] == 0){
                    ?>
                        <div style="max-height:300px;overflow-y:scroll;">
                    <?php
                    }else{
                    ?>
                        <div>
							<?php
							if(($configClass['hidetabs'] == 1) and ($configClass['employee_bar'] == 0)){
							?>
							<div class="span12 <?php echo $configClass['header_style']?>" style="margin-bottom:10px;">
								<?php echo Jtext::_('OS_SELECT_TIME_SLOT'); ?>
							</div>
							<?php } ?>
                    <?php
                    }
					$db->setQuery("Select * from #__app_sch_services where id = '$sid'");
					$service_details = $db->loadObject();

					if($service_details->service_time_type == 0){
					?>
						<div class="row-fluid">
						<?php
						$j = 0;
						for($inctime = $starttimetoday;$inctime<=$endtimetoday;$inctime = $inctime + $amount){
							$start_booking_time = $inctime;
							$end_booking_time	= $inctime + $service_length*60;
							//Modify on 1st May to add the start time from break time
							foreach ($breakTime as $break){
								if(($inctime >= $break->start_time) and ($inctime <= $break->end_time)){
									$inctime = $break->end_time;
									$start_booking_time = $inctime;
									$end_booking_time	= $inctime + $service_length*60;
								}
							}

							$arr1 = array();
							$arr2 = array();
							$arr3 = array();

							if(count($tempEmployee) > 0){
								for($i=0;$i<count($tempEmployee);$i++){
									$employee = $tempEmployee[$i];
									$before_service = $employee->start_time - $service->service_total*60;
									$after_service  = $employee->end_time + $service->service_total*60;
									if(($employee->start_time < $inctime) and ($inctime < $employee->end_time) and ($inctime + $service->service_total*60 == $employee->end_time)){
										//echo "1";
										$arr1[] = $inctime;
										$bgcolor = $configClass['timeslot_background'];
										$nolink = true;
									}elseif(($employee->start_time > $inctime) and ($employee->start_time < $end_booking_time)){

										//echo "4";
										$arr2[] = $inctime;
										$bgcolor = "gray";
										$nolink = true;
									}elseif(($employee->end_time > $inctime) and ($employee->end_time < $end_booking_time)){
										//echo "5";

										$arr2[] = $inctime;
										$bgcolor = "gray";
										$nolink = true;
									}elseif(($employee->start_time > $inctime) and ($employee->end_time < $end_booking_time)){

										//echo "6";
										$arr2[] = $inctime;
										$bgcolor = "gray";
										$nolink = true;
									}elseif(($employee->start_time < $inctime) and ($employee->end_time > $end_booking_time)){
										//echo "7";

										$arr2[] = $inctime;
										$bgcolor = "gray";
										$nolink = true;
									}elseif(($employee->start_time == $inctime) or ($employee->end_time == $end_booking_time)){
										//echo "7";

										$arr2[] = $inctime;
										$bgcolor = "gray";
										$nolink = true;
									}else{
										//echo "8";
										$arr3[] = $inctime;
										$bgcolor = $configClass['timeslot_background'];
										$nolink = false;
									}
								}
							}else{
								$arr3[] = $inctime;
								$bgcolor = $configClass['timeslot_background'];
								$nolink = false;
							}
							//echo $bgcolor;
							$gray =  0;
							if($inctime + $service->service_total*60 > $endtimetoday){
								$bgcolor = "gray";
								$nolink  = true;
								$gray = 1;
							}

							if(($date[2] == date("Y",$realtime) and ($date[1] == intval(date("m",$realtime))) and ($date[0] == intval(date("d",$realtime))))){
								if($inctime <= $realtime){
									$bgcolor = "gray";
									$nolink  = true;

									$gray = 1;
								}
							}

							if($gray == 0){
								if(in_array($inctime,$arr2)){
									$bgcolor = "gray";
									$nolink = true;
								}elseif(in_array($inctime,$arr1)){
									$bgcolor = $configClass['timeslot_background'];
									$nolink = true;
								}else{
									$bgcolor = $configClass['timeslot_background'];
									$nolink = false;
								}
							}elseif($gray == 1){
								$bgcolor = "gray";
								$nolink  = true;
							}

							$tipcontent = "";
							$tipcontentArr = array();
							for($k=0;$k<count($timezone);$k++){
								if($timezone[$k] != ""){
									$tipcontentArr[] = $timezone[$k].": ".OSBHelper::showTime($timezone[$k],$inctime,$end_booking_time);
								}
							}
							if(count($tipcontentArr) > 0){
								$tipcontent = implode(" | ",$tipcontentArr);
							}

							if($configClass['multiple_work'] == 0){
								if(!OSBHelper::checkMultipleEmployees($sid,$eid,$start_booking_time,$end_booking_time)){
									$bgcolor = "gray";
									$nolink  = true;
								}
								if(!OSBHelper::checkMultipleEmployeesInTempOrderTable($sid,$eid,$start_booking_time,$end_booking_time)){
									$bgcolor = "gray";
									$nolink  = true;
								}

							}

							if($configClass['disable_timeslot'] == 1){
								if(!OSBHelper::checkMultipleServices($sid,$eid,$start_booking_time,$end_booking_time)){
									$bgcolor = "gray";
									$nolink  = true;
								}
								if(!OSBHelper::checkMultipleServicesInTempOrderTable($sid,$eid,$start_booking_time,$end_booking_time)){
									$bgcolor = "gray";
									$nolink  = true;
								}

							}

							//echo $bgcolor;
							if($disable_booking_before > 1){
								if($inctime < $disable_time){
									$bgcolor = "gray";
									$nolink  = true;
								}
							}
							if($disable_booking_after > 1){
								if($inctime > $disable_time_after){
									$bgcolor = "gray";
									$nolink  = true;
								}
							}
                            if ($configClass['booking_theme'] == 0) {
							    if((($nolink) and (($configClass['show_occupied'] == 1)) or (!$nolink)) and ($end_booking_time <= $endtimetoday)) {
                                    $j++;
                                    $show_no_timeslot_text = 0;
                                    ?>
                                    <div class="span6 timeslots divtimeslots" style="background-color:<?php echo $bgcolor?> !important;">
                                        <?php
                                        if (!$nolink) {
                                            $text = JText::_('OS_BOOK_THIS_EMPLOYEE_FROM') . "[" . date($configClass['date_time_format'], $inctime) . "] to [" . date($configClass['date_time_format'], $end_booking_time) . "]";
                                            ?>
                                            <input type="radio" name="<?php echo $eid?>[]"
                                                   id="<?php echo $eid?>_<?php echo $inctime?>"
                                                   onclick="javascript:addBooking('<?php echo $inctime?>','<?php echo $end_booking_time;?>','<?php echo date($configClass['date_format'], $inctime);?> <?php echo date($configClass['time_format'], $inctime);?>','<?php echo date($configClass['date_format'], $inctime + $service_total_int)?> <?php echo date($configClass['time_format'], $inctime + $service_total_int)?>',<?php echo $eid?>,<?php echo $sid?>,'<?php echo JText::_('OS_SUMMARY');?>','<?php echo JText::_('OS_FROM');?>','<?php echo JText::_('OS_TO');?>');">
                                        <?php
                                        } else {
                                            ?>
                                            <span class="label label-important"><font color="White"><?php echo JText::_('OS_OCCUPIED')?></font></span>
                                        <?php
                                        }
                                        ?>
                                        &nbsp;&nbsp;&nbsp;
                                        <?php
                                        if ($tipcontent != ""){
                                        ?>
                                            <span class="hasTip" title="<?php echo $tipcontent?>">
                                        <?php
                                        }
                                        echo date($configClass['time_format'], $inctime);
                                        ?>
                                        &nbsp;-&nbsp;
                                        <?php
                                        echo date($configClass['time_format'], $end_booking_time);
                                        $user = JFactory::getUser();
                                        if (($configClass['allow_multiple_timezones'] == 1) and ($user->id > 0) and (OSBHelper::getConfigTimeZone() != OSBHelper::getUserTimeZone())) {
                                            echo "<BR />";
                                            echo "<span class='additional_timezone'>";
                                            echo OSBHelper::getUserTimeZone() . ": ";
                                            echo date($configClass['time_format'], OSBHelper::convertTimezone($inctime));
                                            ?>
                                            &nbsp;-&nbsp;
                                            <?php
                                            echo date($configClass['time_format'], OSBHelper::convertTimezone($end_booking_time));
                                            echo "</span>";
                                        }
                                        if ($tipcontent != ""){
                                        ?>
                                            </span>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                    <?php
                                    }
                                    if($j==2){
                                        ?>
                                        </div><div class="row-fluid">
                                        <?php
                                        $j = 0;
                                    }
							    }else{ //sample layout
                                    if((($nolink) and (($configClass['show_occupied'] == 1)) or (!$nolink)) and ($end_booking_time <= $endtimetoday)) {
                                        $j++;
                                        $show_no_timeslot_text = 0;
                                        if (!$nolink) {
                                            ?>
                                            <div class="timeslots divtimeslots_simple"
                                                 id="timeslot<?php echo $sid ?>_<?php echo $eid ?>_<?php echo $inctime ?>"
                                                 style="background-color:<?php echo $bgcolor ?> !important;"
                                                 onclick="javascript:addBookingSimple('<?php echo $inctime ?>','<?php echo $end_booking_time; ?>','<?php echo date($configClass['date_format'], $inctime); ?> <?php echo date($configClass['time_format'], $inctime); ?>','<?php echo date($configClass['date_format'], $inctime + $service_total_int) ?> <?php echo date($configClass['time_format'], $inctime + $service_total_int) ?>',<?php echo $eid ?>,<?php echo $sid ?>,'<?php echo JText::_('OS_SUMMARY'); ?>','<?php echo JText::_('OS_FROM'); ?>','<?php echo JText::_('OS_TO'); ?>','timeslot<?php echo $sid ?>_<?php echo $eid ?>_<?php echo $inctime ?>','<?php echo $bgcolor ?>');" ontouchstart="javascript:addBookingSimple('<?php echo $inctime ?>','<?php echo $end_booking_time; ?>','<?php echo date($configClass['date_format'], $inctime); ?> <?php echo date($configClass['time_format'], $inctime); ?>','<?php echo date($configClass['date_format'], $inctime + $service_total_int) ?> <?php echo date($configClass['time_format'], $inctime + $service_total_int) ?>',<?php echo $eid ?>,<?php echo $sid ?>,'<?php echo JText::_('OS_SUMMARY'); ?>','<?php echo JText::_('OS_FROM'); ?>','<?php echo JText::_('OS_TO'); ?>','timeslot<?php echo $sid ?>_<?php echo $eid ?>_<?php echo $inctime ?>','<?php echo $bgcolor ?>');">
                                                <?php
                                                if ($tipcontent != ""){
                                                ?>
                                                <span class="hasTip" title="<?php echo $tipcontent?>">
                                                <?php
                                                }
                                                echo date($configClass['time_format'], $inctime);

                                                $user = JFactory::getUser();
                                                if (($configClass['allow_multiple_timezones'] == 1) and ($user->id > 0) and (OSBHelper::getConfigTimeZone() != OSBHelper::getUserTimeZone())) {
                                                    echo "<BR />";
                                                    echo "<span class='additional_timezone'>";
                                                    echo OSBHelper::getUserTimeZone() . ": ";
                                                    echo date($configClass['time_format'], OSBHelper::convertTimezone($inctime));
                                                    ?>
                                                    &nbsp;-&nbsp;
                                                    <?php
                                                    echo date($configClass['time_format'], OSBHelper::convertTimezone($end_booking_time));
                                                    echo "</span>";
                                                }
                                                if ($tipcontent != ""){
                                                ?>
                                                </span>
                                            <?php
                                            }
                                            ?>
                                            </div>
                                        <?php
                                        }
                                        if($j==2){ $j = 0; }
                                    }
                                }
                            }

                        if($j == 1){
                            ?>
                            </div>
                            <?php
                        }
                        if($j==0){
                            ?>
                            </div>
                            <?php
                        }
					}else{
						$dateformat_int = strtotime($dateformat);
						$date_in_week = date("N",$dateformat_int);
						$db->setQuery("Select * from #__app_sch_custom_time_slots where sid = '$sid' and id in (Select time_slot_id from #__app_sch_custom_time_slots_relation where date_in_week = '$date_in_week') order by start_hour,start_min");
						$rows = $db->loadObjectList();
						
						?>
						<div class="row-fluid">
							<?php
							$j = 0;
							for($i=0;$i<count($rows);$i++){
								$bgcolor = "";
								$row = $rows[$i];
								
								$start_hour = $row->start_hour;
								if($start_hour < 10){
									$start_hour = "0".$start_hour;
								}
								$start_min = $row->start_min;
								if($start_min < 10){
									$start_min = "0".$start_min;
								}

								$start_time = $date[2]."-".$date[1]."-".$date[0]." ".$start_hour.":".$start_min.":00";
								//echo $start_time;
								$start_time_int = strtotime($start_time);

								$end_hour = $row->end_hour;
								if($end_hour < 10){
									$end_hour = "0".$end_hour;
								}
								$end_min = $row->end_min;
								if($end_min < 10){
									$end_min = "0".$end_min;
								}

								$end_time = $date[2]."-".$date[1]."-".$date[0]." ".$end_hour.":".$end_min.":00";
								$end_time_int = strtotime($end_time);
								
								$tipcontent = "";
								$tipcontentArr = array();
								for($k=0;$k<count($timezone);$k++){
									if($timezone[$k] != ""){
										$tipcontentArr[] = $timezone[$k].": ".OSBHelper::showTime($timezone[$k],$start_time_int,$end_time_int);
									}
								}
								if(count($tipcontentArr) > 0){
									$tipcontent = implode(" | ",$tipcontentArr);
								}

								$db->setQuery("Select SUM(a.nslots) as nslots from #__app_sch_order_items as a inner join #__app_sch_orders as b on b.id = a.order_id where b.order_status in ('P','S') and a.start_time =  '$start_time_int' and a.end_time = '$end_time_int' and a.sid = '$sid' and a.eid = '$eid'");
								//$count = $db->loadResult();
								$nslotsbooked = $db->loadObject();
								$count = intval($nslotsbooked->nslots);
								$temp_start_hour = $row->start_hour;
								$temp_start_min  = $row->start_min;
								$temp_end_hour 	 = $row->end_hour;
								$temp_end_min    = $row->end_min;

								$db->setQuery("Select nslots from #__app_sch_custom_time_slots where sid = '$service->id' and start_hour = '$temp_start_hour' and start_min = '$temp_start_min' and end_hour = '$temp_end_hour' and end_min = '$temp_end_min'");
								//echo $db->getQuery();
								$nslots = $db->loadResult();

								//get the number count of the cookie table
								$query = "SELECT SUM(a.nslots) as bnslots FROM #__app_sch_temp_order_items AS a INNER JOIN #__app_sch_temp_orders AS b ON a.order_id = b.id WHERE a.sid = '$sid' AND a.eid = '$eid' AND a.start_time =  '$start_time_int' and a.end_time = '$end_time_int'";
								$db->setQuery($query);
								$bslots = $db->loadObject();
								$count_book = $bslots->bnslots;
								$avail = $nslots - $count - $count_book;
								if($avail <= 0){
									$bgcolor = $configClass['timeslot_background'];
									$nolink = true;
								}else{
									$bgcolor = $configClass['timeslot_background'];
									$nolink = false;
								}

								if(($date[2] == date("Y",$realtime) and ($date[1] == intval(date("m",$realtime))) and ($date[0] == intval(date("d",$realtime))))){
									//today
									if($start_time_int <= $realtime){
										$bgcolor = "gray";
										$nolink  = true;
									}
								}
								
								if($disable_booking_before > 1){
									if($start_time_int < $disable_time){
										$bgcolor = "gray";
										$nolink  = true;
									}
								}
								if($disable_booking_after > 1){
									if($start_time_int > $disable_time_after){
										$bgcolor = "gray";
										$nolink  = true;
									}
								}
								
								if($configClass['multiple_work'] == 0){
									if(!OSBHelper::checkMultipleEmployees($sid,$eid,$start_time_int,$end_time_int)){
										$bgcolor = "gray";
										$nolink  = true;
									}
									if(!OSBHelper::checkMultipleEmployeesInTempOrderTable($sid,$eid,$start_time_int,$end_time_int)){
										$bgcolor = "gray";
										$nolink  = true;
									}
								}

								if($configClass['disable_timeslot'] == 0){
									if(!OSBHelper::checkMultipleServices($sid,$eid,$start_time_int,$end_time_int)){
										$bgcolor = "gray";
										$nolink  = true;
									}
									if(!OSBHelper::checkMultipleServicesInTempOrderTable($sid,$eid,$start_time_int,$end_time_int)){
										$bgcolor = "gray";
										$nolink  = true;
									}
								}
								
								if(count($tempEmployee) > 0){
									for($k=0;$k<count($tempEmployee);$k++){
										$employee = $tempEmployee[$k];
										$before_service = $employee->start_time;
										$after_service  = $employee->end_time;
										if(($employee->start_time < $start_time_int) and ($end_time_int < $employee->end_time)){
											//echo "1";
											if(($avail <= 0) or ($employee->show == 0)){
												$bgcolor = "gray";
												$nolink = true;
											}
										}elseif(($employee->start_time > $start_time_int) and ($employee->start_time < $end_time_int)){
											//echo "2";
											if(($avail <= 0) or ($employee->show == 0)){
												$bgcolor = "gray";
												$nolink = true;
											}
										}elseif(($employee->end_time > $start_time_int) and ($employee->end_time < $end_time_int)){
											//echo "3";
											if(($avail <= 0) or ($employee->show == 0)){
												$bgcolor = "gray";
												$nolink = true;
											}
										}elseif(($employee->start_time <= $start_time_int) and ($employee->end_time >= $end_time_int)){
											if(($avail <= 0) or ($employee->show == 0)){
												$bgcolor = "gray";
												$nolink = true;
											}
										}elseif($end_time_int <= $employee->start_time){
											if($bgcolor != "gray"){
												$bgcolor = $configClass['timeslot_background'];
												$nolink = false;
											}
										}else{
											if($bgcolor != "gray"){
												$bgcolor = $configClass['timeslot_background'];
												$nolink = false;
											}
										}
									}
								}
								if($disable_booking_before > 1){
									if($start_time_int < $disable_time){
										$bgcolor = "gray";
										$nolink  = true;
									}
								}
								if($disable_booking_after > 1){
									if($start_time_int > $disable_time_after){
										$bgcolor = "gray";
										$nolink  = true;
									}
								}

								if($avail <= 0){
									$bgcolor = $configClass['timeslot_background'];
									$nolink = true;
								}

                                if ($configClass['booking_theme'] == 0) {
                                    if((($nolink) and (($configClass['show_occupied'] == 1)) or (!$nolink))) {
                                        if (($end_time_int <= $endtimetoday) and ($start_time_int >= $starttimetoday)) {
                                            $j++;
                                            $show_no_timeslot_text = 0;
                                            ?>
                                            <div class="span6 timeslots divtimeslots"
                                                 style="background-color:<?php echo $bgcolor?> !important;">
                                                <?php
                                                if (!$nolink) {
                                                    $text = "Book this employee from [" . date($configClass['date_time_format'], $start_time_int) . "] to [" . date($configClass['date_time_format'], $end_time_int) . "]";
                                                    ?>
                                                    <input type="radio" name="<?php echo $eid?>[]"
                                                           id="<?php echo $eid?>_<?php echo $start_time_int;?>"
                                                           onclick="javascript:addBooking('<?php echo $start_time_int?>','<?php echo $end_time_int;?>','<?php echo date($configClass['date_format'], $start_time_int);?> <?php echo date($configClass['time_format'], $start_time_int);?>','<?php echo date($configClass['date_format'], $end_time_int)?> <?php echo date($configClass['time_format'], $end_time_int)?>',<?php echo $eid?>,<?php echo $sid?>,'<?php echo JText::_('OS_SUMMARY');?>','<?php echo JText::_('OS_FROM');?>','<?php echo JText::_('OS_TO');?>');">
                                                <?php
                                                } else {
                                                    ?>
                                                    <span class="label label-important"><font
                                                            color="White"><?php echo JText::_('OS_OCCUPIED')?></font></span>
                                                <?php
                                                }
                                                ?>
                                                &nbsp;&nbsp;
                                                <?php
                                                if ($tipcontent != ""){
                                                ?>
                                                <span class="hasTip" title="<?php echo $tipcontent?>">
                                                <?php
                                                }
                                                $start_hour = $row->start_hour;
                                                if ($start_hour < 10) {
                                                    $start_hour = "0" . $start_hour;
                                                }
                                                //echo ":";
                                                $start_min = $row->start_min;
                                                if ($start_min < 10) {
                                                    $start_min = "0" . $start_min;
                                                }

                                                echo date($configClass['time_format'], strtotime(date("Y-m-d", $start_time_int) . " " . $start_hour . ":" . $start_min . ":00"));
                                                ?>
                                                    &nbsp;-&nbsp;
                                                    <?php
                                                    $end_hour = $row->end_hour;
                                                    if ($end_hour < 10) {
                                                        $end_hour = "0" . $end_hour;
                                                    }
                                                    $end_min = $row->end_min;
                                                    if ($end_min < 10) {
                                                        $end_min = "0" . $end_min;
                                                    }
                                                    echo date($configClass['time_format'], strtotime(date("Y-m-d", $start_time_int) . " " . $end_hour . ":" . $end_min . ":00"));
                                                    ?>

                                                    <?php
                                                    if ($tipcontent != ""){
                                                    ?>
                                                </span>
                                            <?php
                                            }
                                            ?>
                                                &nbsp;-&nbsp;
                                                <?php
                                                echo JText::_('OS_AVAIL') . ": ";
                                                echo $avail;
                                                ?>
                                                <?php
                                                $user = JFactory::getUser();
                                                if (($configClass['allow_multiple_timezones'] == 1) and ($user->id > 0) and (OSBHelper::getConfigTimeZone() != OSBHelper::getUserTimeZone())) {
                                                    echo "<BR />";
                                                    echo "<span class='additional_timezone'>";
                                                    echo OSBHelper::getUserTimeZone() . ": ";
                                                    echo date($configClass['time_format'], OSBHelper::convertTimezone(strtotime(date("Y-m-d", $start_time_int) . " " . $start_hour . ":" . $start_min . ":00")));
                                                    ?>
                                                    &nbsp;-&nbsp;
                                                    <?php
                                                    echo date($configClass['time_format'], OSBHelper::convertTimezone(strtotime(date("Y-m-d", $start_time_int) . " " . $end_hour . ":" . $end_min . ":00")));
                                                    echo "</span>";
                                                }
                                                ?>
                                            </div>
                                        <?php
                                        }
                                        if ($j == 2) {
                                            $j = 0;
                                            ?>
                                            </div><div class="row-fluid">
                                        <?php
                                        }
                                    }
                                }else{
                                    if((($nolink) and (($configClass['show_occupied'] == 1)) or (!$nolink))){
                                        if(($end_time_int <= $endtimetoday) and ($start_time_int >= $starttimetoday)){
                                            $j++;
                                            $show_no_timeslot_text = 0;
											if($avail > 0){
                                            ?>
												<div class="timeslots divtimecustomslots_simple" style="background-color:<?php echo $bgcolor?> !important;"
													 onclick="javascript:addBookingSimple('<?php echo $start_time_int?>','<?php echo $end_time_int;?>','<?php echo date($configClass['date_format'],$start_time_int);?> <?php echo date($configClass['time_format'],$start_time_int);?>','<?php echo date($configClass['date_format'],$end_time_int)?> <?php echo date($configClass['time_format'],$end_time_int)?>',<?php echo $eid?>,<?php echo $sid?>,'<?php echo JText::_('OS_SUMMARY');?>','<?php echo JText::_('OS_FROM');?>','<?php echo JText::_('OS_TO');?>','ctimeslots<?php echo $sid ?>_e<?php echo $eid ?>_<?php echo $start_time_int?>','<?php echo $bgcolor ?>');"  ontouchstart="javascript:addBookingSimple('<?php echo $start_time_int?>','<?php echo $end_time_int;?>','<?php echo date($configClass['date_format'],$start_time_int);?> <?php echo date($configClass['time_format'],$start_time_int);?>','<?php echo date($configClass['date_format'],$end_time_int)?> <?php echo date($configClass['time_format'],$end_time_int)?>',<?php echo $eid?>,<?php echo $sid?>,'<?php echo JText::_('OS_SUMMARY');?>','<?php echo JText::_('OS_FROM');?>','<?php echo JText::_('OS_TO');?>','ctimeslots<?php echo $sid ?>_e<?php echo $eid ?>_<?php echo $start_time_int?>','<?php echo $bgcolor ?>');"
													 id="ctimeslots<?php echo $sid ?>_e<?php echo $eid ?>_<?php echo $start_time_int?>" >
													 <script type="text/javascript">
													 $("#ctimeslots<?php echo $sid ?>_e<?php echo $eid ?>_<?php echo $start_time_int?>").touchstart(function(){
															alert("sdada");
													 });
													 </script>
											<?php
											}else{
											?>
												<div class="timeslots divtimecustomslots_simple" style="background-color:red !important;">
											<?php
										    }
											?>
                                                <?php
                                                if($tipcontent != ""){
                                                ?>
                                                    <span class="hasTip" title="<?php echo $tipcontent?>">
                                                <?php
                                                }
                                                $start_hour = $row->start_hour;
                                                if($start_hour < 10){
                                                    $start_hour = "0".$start_hour;
                                                }
                                                //echo ":";
                                                $start_min = $row->start_min;
                                                if($start_min < 10){
                                                    $start_min = "0".$start_min;
                                                }

                                                echo date($configClass['time_format'],strtotime(date("Y-m-d",$start_time_int)." ".$start_hour.":".$start_min.":00"));
                                                ?>
                                                -
                                                <?php
                                                $end_hour = $row->end_hour;
                                                if($end_hour < 10){
                                                    $end_hour = "0".$end_hour;
                                                }
                                                $end_min = $row->end_min;
                                                if($end_min < 10){
                                                    $end_min = "0".$end_min;
                                                }
                                                echo date($configClass['time_format'],strtotime(date("Y-m-d",$start_time_int)." ".$end_hour.":".$end_min.":00"));
                                                ?>

                                                <?php
                                                if($tipcontent != ""){
                                                ?>
                                                    </span>
                                                <?php
                                                }
                                                ?>
                                                &nbsp;
                                                <?php
                                                echo JText::_('OS_AVAIL').": ";
                                                echo $avail;
                                                ?>
                                                <?php
                                                $user = JFactory::getUser();
                                                if(($configClass['allow_multiple_timezones'] == 1) and ($user->id > 0) and (OSBHelper::getConfigTimeZone() != OSBHelper::getUserTimeZone())){
                                                    echo "<BR />";
                                                    echo "<span class='additional_timezone'>";
                                                    echo OSBHelper::getUserTimeZone().": ";
                                                    echo date($configClass['time_format'],OSBHelper::convertTimezone(strtotime(date("Y-m-d",$start_time_int)." ".$start_hour.":".$start_min.":00")));
                                                    ?>
                                                    &nbsp;-&nbsp;
                                                    <?php
                                                    echo date($configClass['time_format'],OSBHelper::convertTimezone(strtotime(date("Y-m-d",$start_time_int)." ".$end_hour.":".$end_min.":00")));
                                                    echo "</span>";
                                                }
                                                ?>
                                            </div>
                                        <?php
                                        }
                                    }
                                }
                            }
                            echo "</div>";
                        }
					if($show_no_timeslot_text == 1){
						?>
						<div class="no_available_time_slot">
							<?php echo JText::_('OS_NO_AVAILABLE_TIME_SLOTS');?>
						</div>
						<?php 
					}
					?>
					
					<input type="hidden" name="book_<?php echo $sid?>_<?php echo $eid?>" id="book_<?php echo $sid?>_<?php echo $eid?>" value="" />
					<input type="hidden" name="end_book_<?php echo $sid?>_<?php echo $eid?>" id="end_book_<?php echo $sid?>_<?php echo $eid?>" value="" />
					<input type="hidden" name="start_<?php echo $sid?>_<?php echo $eid?>" id="start_<?php echo $sid?>_<?php echo $eid?>" value="" /> 
					<input type="hidden" name="end_<?php echo $sid?>_<?php echo $eid?>" id="end_<?php echo $sid?>_<?php echo $eid?>" value="" />
					</div>
				</div>
			</div>
			<BR />
			<input type="hidden" name="service_time_type_<?php echo $sid?>" id="service_time_type_<?php echo $sid?>" value="<?php echo $service->service_time_type?>" />
			<?php
			if(($service->repeat_day == 1) OR ($service->repeat_week == 1) OR ($service->repeat_month == 1)){
			?>
			<div class="row-fluid bookingformdiv">
				<div class="span12 <?php echo $configClass['header_style']?>">
					<?php
					echo JText::_('OS_REPEAT_BOOKING');
					?>
				</div>
				<div class="span12" style="padding-top:10px;">
					<div class="span6">
						<?php
						echo JText::_('OS_REPEAT_BY');
						?>
						<BR />
						<select name="repeat_type_<?php echo $sid?>_<?php echo $eid?>" id="repeat_type_<?php echo $sid?>_<?php echo $eid?>" class="input-mini" >
						<option value=""></option>
						<?php
						if($service->repeat_day  == 1){
							?>
							<option value="1"><?php echo JText::_('OS_REPEAT_BY_DAY');?></option>
							<?php												
						}
						if($service->repeat_week  == 1){
							?>
							<option value="2"><?php echo JText::_('OS_REPEAT_BY_WEEK');?></option>
							<?php												
						}
						if($service->repeat_month  == 1){
							?>
							<option value="3"><?php echo JText::_('OS_REPEAT_BY_MONTH');?></option>
							<?php												
						}
						?>
						
						</select>
					</div>
					<div class="span6">
						<?php
						echo JText::_('OS_FOR_NEXT');
						?>
						<BR />
						<select name="repeat_to_<?php echo $sid?>_<?php echo $eid?>" class="input-mini" id="repeat_to_<?php echo $sid?>_<?php echo $eid?>">
							<option value=""></option>
							<?php
							for($m=1;$m<=10;$m++){
								?>
								<option value="<?php  echo $m?>"><?php echo $m?></option>
								<?php	
							}
							?>
						</select>
						<select name="repeat_type_<?php echo $sid?>_<?php echo $eid?>1" id="repeat_type_<?php echo $sid?>_<?php echo $eid?>1" class="input-mini">
						<option value=""></option>
						<?php
						if($service->repeat_day  == 1){
							?>
							<option value="1"><?php echo JText::_('OS_REPEAT_BY_DAY');?></option>
							<?php												
						}
						if($service->repeat_week  == 1){
							?>
							<option value="2"><?php echo JText::_('OS_REPEAT_BY_WEEK');?></option>
							<?php												
						}
						if($service->repeat_month  == 1){
							?>
							<option value="3"><?php echo JText::_('OS_REPEAT_BY_MONTH');?></option>
							<?php												
						}
						?>
						</select>
					</div>
				</div>
			</div>
			<?php
			}
			?>
			<BR />
			<?php
			if($service->service_time_type == 1){
				if($configClass['show_number_timeslots_booking'] == 1){
					?>
					<div class="row-fluid bookingformdiv">
						<div class="span12 <?php echo $configClass['header_style']?>">
							
								<?php
								echo JText::_('OS_YOUR_NUMBER_SLOTS_WHICH_YOU_NEED');
								?>
							
						</div>
						<div class="span12" style="padding-top:10px;">
							<?php
							echo JText::_('OS_HOW_MANY_SLOTS_WHICH_YOU_WANT_TO_BOOK').":";
							?>
							<input class="input-mini" type="text" name="nslots_<?php echo $sid?>_<?php echo $eid?>" id="nslots_<?php echo $sid?>_<?php echo $eid?>" value="" style="width:40px;"/>
							<?php 
							OSBHelper::customServicesDiscountChecking($sid);
							?>
							<div class="clearfix"></div>
							
						</div>
					</div>
					<?php
				}else{
					?>
					<input type="hidden" name="nslots_<?php echo $sid?>_<?php echo $eid?>" id="nslots_<?php echo $sid?>_<?php echo $eid?>" value="1"/>
					<?php 
				}
			}
			?>
			<?php
            if(OsAppscheduleDefault::checkExtraFields($sid,$eid)) {
                echo OsAppscheduleDefault::loadExtraFields($sid, $eid);
                echo "<Br />";
            }
            ?>
			<div class="row-fluid bookingformdiv">
				<div class="span12" style="text-align:center;">
					<div id="summary_<?php echo $sid?>_<?php echo $eid?>" style="padding:2px;text-align:left;" class="sumarry_div">
					</div>
					<input type="button" name="addtocartbtn" class="<?php echo $configClass['calendar_normal_style']?>" value="<?php echo JText::_('OS_ADD_TO_CART')?>" onclick="javascript:addtoCart(<?php echo $sid?>,<?php echo $eid?>,<?php echo $service_total_int;?>)" />
				</div>
			</div>
			<?php
			if($employeeDetails->employee_notes != ""){
			?>
			<Br />
			<div class="row-fluid bookingformdiv">
				<div class="span12" style="background-color:#F1F1FA;padding:3px;font-size:11px;color:#63648D;">
					<?php echo $employeeDetails->employee_notes;?>
				</div>
			</div>
			<?php
			}
			?>
			</div>
		</div>
		<?php
	}

	/**
	 * Check duplicate order item 
	 *
	 * @param unknown_type $userdata
	 */
	function checkDuplicateOrderItem(){
		global $mainframe;
		$db = JFactory::getDbo();
		//$userdata = explode("||",$userdata);
		$unique_cookie = $_COOKIE['unique_cookie'];
		$return   = array();
		$sids	  = array();
		if(count($userdata) > 0){
			for($i=0;$i<count($userdata);$i++){
				$data 				= explode("|",$userdata[$i]);
				$sid 				= $data[0];
				$start_booking_date = $data[1];
				$end_booking_date 	= $data[2];
				$eid				= $data[3];
				$db->setQuery("Select * from #__app_sch_services where id = '$sid'");
				$service = $db->loadObject();

				$service_before 	= intval($service->service_before);
				$service_after  	= intval($service->service_after);

				$start_time 		= $start_booking_date - $service_before;
				$end_time			= $end_booking_date + $service_after;

				$booking_date		= date("Y-m-d",$start_booking_date);

				//check to see if this employee is free in this time
				$query = "SELECT count(a.id) FROM #__app_sch_order_items AS a"
				." INNER JOIN #__app_sch_orders AS b ON b.id = a.order_id"
				." WHERE a.sid = '$sid' AND a.eid = '$eid' AND b.order_status in ('S','P') "
				." AND a.booking_date = '$booking_date' AND (((a.start_time <= '$start_time') AND (a.end_time >= '$start_time')) OR ((a.end_time >= '$end_time') AND (a.start_time <= '$end_time')) OR ((a.end_time >= '$end_time') AND (a.start_time <= '$start_time')))";
				$db->setQuery($query);
				$count = $db->loadResult();
				if($count > 0){
					$sids[count($sids)] = $sid;
				}
			}
		}
		if(count($sids) > 0){
			$return[0]->canCreateOrder = 0;
			$return[0]->sid			   = $sids;
		}else{
			$return[0]->canCreateOrder = 1;
		}

		return $return;
	}

	function days_in_month($month, $year){
		// calculate number of days in a month
		return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
	}
	

	/**
	 * function checkSlots
	 * if the custom time slot : check to see if is the any time slots available
	 * if the normal time slot : check to see if someone book the slot already
	 * check if it is offline date of service
	 * check if it is the rest day
	 * check if it isn't working day of employee
	 *
	 * @param unknown_type $row
	 */
	function checkSlots($row){
		global $mainframe,$configClass;
		$config 			= new JConfig();
		$offset 			= $config->offset;
		date_default_timezone_set($offset);
		$returnArr			= array();
		$db 				= JFactory::getDbo();
		$unique_cookie		= $_COOKIE['unique_cookie'];
		$start_time 		= $row->start_time;
		$end_time   		= $row->end_time;
		$booking_date 		= $row->booking_date;
		$sid				= $row->sid;
		$eid 				= $row->eid;
		$nslots 			= $row->nslots;
		$temp_start_min 	= intval(date("i",$start_time));
		$temp_start_hour  	= intval(date("H",$start_time));
		$temp_end_min   	= intval(date("i",$end_time));
		$temp_end_hour  	= intval(date("H",$end_time));
		//echo "1";
		//check multiple work
		if($configClass['multiple_work'] == 0){
			$query = "Select count(b.id) from #__app_sch_temp_order_items as b inner join #__app_sch_temp_orders as a on a.id = b.order_id where unique_cookie not like '$unique_cookie' and b.eid = '$eid' and b.booking_date = '$booking_date' and ((b.start_time > '$start_time' and b.start_time < '$end_time') or (b.end_time > '$start_time' and b.end_time < '$end_time') or (b.start_time <= '$start_time' and b.end_time >= '$end_time') or (b.start_time >= '$start_time' and b.end_time <= '$end_time'))";
			$db->setQuery($query);
			//echo $db->getQuery();
			$count = $db->loadResult();
			//echo $count;
			if($count > 0){
				if($count == 1){
					$query = "Select count(b.id) from #__app_sch_temp_order_items as b inner join #__app_sch_temp_orders as a on a.id = b.order_id where unique_cookie like '$unique_cookie' and b.eid = '$eid' and b.sid = '$sid' and b.booking_date = '$booking_date' and ((b.start_time > '$start_time' and b.start_time < '$end_time') or (b.end_time > '$start_time' and b.end_time < '$end_time') or (b.start_time <= '$start_time' and b.end_time >= '$end_time') or (b.start_time >= '$start_time' and b.end_time <= '$end_time'))";
					$db->setQuery($query);
					//echo "<BR />";
					//echo $db->getQuery();
					$count1 = $db->loadResult();
					if($count1 == 0){
						//echo "1";die();
						return false;
					}
				}else{
					//echo "2";die();
					return false;
				}
			}
		}


		//
		//echo "3";
		//die();
		//echo "2";
		
		$db->setQuery("Select * from #__app_sch_services where id = '$sid'");
		$service = $db->loadObject();
		//echo $booking_date."  : ".date("H:i",$start_time)." - ".date("H:i",$end_time);
		//is off day?
		$date_in_week       = date("N",$start_time);
		$db->setQuery("Select is_day_off from #__app_sch_working_time where id = '$date_in_week'");
		$is_day_off = $db->loadResult();
		if($is_day_off == 1){
			return false;
		}
		
		//echo "Off day";
		//echo "<BR />";
		//is off day in custom working time, for normal time slot only
		if($service->service_time_type == 1){
			$db->setQuery("Select count(id) from #__app_sch_working_time_custom where (worktime_date <= '$booking_date' and worktime_date_to >= '$booking_date') and is_day_off = '1'");
			$count = $db->loadResult();
			if($count > 0){
				return false;
			}else{ //book outtime of working time
				$db->setQuery("Select count(id) from #__app_sch_working_time_custom where (worktime_date <= '$booking_date' and worktime_date_to >= '$booking_date')");
				$count = $db->loadResult();
				if($count > 0){
					$db->setQuery("Select * from #__app_sch_working_time_custom where (worktime_date <= '$booking_date' and worktime_date_to >= '$booking_date')");
					$working_time_custom = $db->loadObject();
					$start_working_time  = strtotime($booking_date." ".$working_time_custom->start_time);
					$end_working_time    = strtotime($booking_date." ".$working_time_custom->end_time);
					if(($start_time < $start_working_time) or  ($end_time > $end_working_time)){
						return false;
					}
				}
			}
		}
		//echo "Custom working";
		//echo "<BR />";
		//check rest day
		
		$db->setQuery("Select count(id) from #__app_sch_employee_rest_days where rest_date <= '$booking_date' and rest_date_to >= '$booking_date' and eid = '$eid'");
		$count = $db->loadResult();
		if($count > 0){
			return false;
		}
		//echo "Rest";
		//echo "<BR />";
		//check if it isn't working day of employee
		$date_week       = substr(strtolower(date("l",$start_time)),0,2);
		$db->setQuery("Select count(id) from #__app_sch_employee_service where employee_id = '$eid' and service_id = '$sid' and  `$date_week` = '0'");
		$count = $db->loadResult();
		if($count > 0){
			return false;
		}
		
		//echo "Isnt working";
		//echo "<BR />";
		//custom time slot
		if($service->service_time_type == 1){
			if(!HelperOSappscheduleCalendar::checkCustomSlots($row)){
				return false;
			}
		}else{ //normal time slots
			if(!HelperOSappscheduleCalendar::checkNormalSlots($row)){
				return false;
			}
		}
		//echo "Time slot";
		//echo "<BR />";
		return true;
	}

	/**
	 * Return slots
	 *
	 * @param unknown_type $row
	 */
	function returnSlots($row){
		global $mainframe,$configClass;
		$config 			= new JConfig();
		$offset 			= $config->offset;
		date_default_timezone_set($offset);
		$returnArr			= array();
		$db 				= JFactory::getDbo();
		$unique_cookie		= $_COOKIE['unique_cookie'];
		$start_time 		= $row->start_time;
		$end_time   		= $row->end_time;
		$booking_date 		= $row->booking_date;
		$sid				= $row->sid;
		$eid 				= $row->eid;
		$nslots 			= $row->nslots;
		$temp_start_min 	= intval(date("i",$start_time));
		$temp_start_hour  	= intval(date("H",$start_time));
		$temp_end_min   	= intval(date("i",$end_time));
		$temp_end_hour  	= intval(date("H",$end_time));
		
		$db->setQuery("Select * from #__app_sch_services where id = '$sid'");
		$service = $db->loadObject();
		//echo $booking_date."  : ".date("H:i",$start_time)." - ".date("H:i",$end_time);
		//is off day?
		$date_in_week       = date("N",$start_time);
		$db->setQuery("Select is_day_off from #__app_sch_working_time where id = '$date_in_week'");
		$is_day_off = $db->loadResult();
		if($is_day_off == 1){
			$row->return = 0;
			return $row;
		}
		//echo "Off day";
		//echo "<BR />";
		//is off day in custom working time, for normal time slot only
		if($service->service_time_type == 1){
			$db->setQuery("Select count(id) from #__app_sch_working_time_custom where (worktime_date <= '$booking_date' and worktime_date_to >= '$booking_date') and is_day_off = '1'");
			$count = $db->loadResult();
			if($count > 0){
				$row->return = 0;
			}else{ //book outtime of working time
				$db->setQuery("Select count(id) from #__app_sch_working_time_custom where (worktime_date <= '$booking_date' and worktime_date_to >= '$booking_date')");
				$count = $db->loadResult();
				if($count > 0){
					$db->setQuery("Select * from #__app_sch_working_time_custom where (worktime_date <= '$booking_date' and worktime_date_to >= '$booking_date')");
					$working_time_custom = $db->loadObject();
					$start_working_time  = strtotime($booking_date." ".$working_time_custom->start_time);
					$end_working_time    = strtotime($booking_date." ".$working_time_custom->end_time);
					if(($start_time < $start_working_time) or  ($end_time > $end_working_time)){
						$row->return = 0;
					}
				}
			}
		}
		//echo "Custom working";
		//echo "<BR />";
		//check rest day
		$db->setQuery("Select count(id) from #__app_sch_employee_rest_days where rest_date <= '$booking_date' and rest_date_to >= '$booking_date' and eid = '$eid'");
		$count = $db->loadResult();
		if($count > 0){
			$row->return = 0;
			return $row;
		}
		//echo "Rest";
		//echo "<BR />";
		//check if it isn't working day of employee
		$date_week       = substr(strtolower(date("l",$start_time)),0,2);
		$db->setQuery("Select count(id) from #__app_sch_employee_service where employee_id = '$eid' and service_id = '$sid' and  `$date_week` = '0'");
		$count = $db->loadResult();
		if($count > 0){
			$row->return = 0;
			return $row;
		}
		//echo "Isnt working";
		//echo "<BR />";
		//custom time slot
		
		if($service->service_time_type == 1){
			if(!HelperOSappscheduleCalendar::checkCustomSlots($row)){
				$row->return 	= 1;
				if(HelperOSappscheduleCalendar::returnCustomSlots($row) > 0){
					$row->number_slots_available =  HelperOSappscheduleCalendar::returnCustomSlots($row);
				}else{
					$row->return = 0;	
				}
				return $row;
			}
		}
		//echo "Time slot";
		//echo "<BR />";
		return $row;
	}
	
	function returnCustomSlots($row){
		global $mainframe,$configClass;
		$config				= new JConfig();
		$offset 			= $config->offset;
		date_default_timezone_set($offset);
		$db 				= JFactory::getDbo();
		$unique_cookie		= $_COOKIE['unique_cookie'];
		$start_time 		= $row->start_time;
		$end_time   		= $row->end_time;
		$booking_date 		= $row->booking_date;
		$sid				= $row->sid;
		$eid 				= $row->eid;
		$nslots 			= $row->nslots;
		$temp_start_min 	= intval(date("i",$start_time));
		$temp_start_hour  	= intval(date("H",$start_time));
		$temp_end_min   	= intval(date("i",$end_time));
		$temp_end_hour  	= intval(date("H",$end_time));

		$db->setQuery("Select nslots from #__app_sch_custom_time_slots where sid = '$sid' and start_hour = '$temp_start_hour' and start_min = '$temp_start_min' and end_hour = '$temp_end_hour' and end_min = '$temp_end_min'");
		$number_slots_in_db = $db->loadResult();

		$query = "Select sum(a.nslots) from #__app_sch_temp_order_items as a inner join #__app_sch_temp_orders as b on b.id = a.order_id where b.unique_cookie like '$unique_cookie' and a.sid = '$row->sid' and a.eid = '$row->eid;' and a.start_time = '$start_time' and a.end_time = '$end_time'";
		$db->setQuery($query);
		$count = $db->loadResult();
		$number_slots_available = $number_slots_in_db - $count;
		return $number_slots_available;
	}
	
	/**
	 * Check one time slot if it is available
	 *
	 * @param unknown_type $row
	 */
	function checkNormalSlots($row){
		global $mainframe,$configClass;
		
		$db 				= JFactory::getDbo();
		$unique_cookie		= $_COOKIE['unique_cookie'];
		$start_time 		= $row->start_time;
		$end_time   		= $row->end_time;
		$booking_date 		= $row->booking_date;
		$sid				= $row->sid;
		$eid 				= $row->eid;
		$temp_start_min 	= intval(date("i",$start_time));
		$temp_start_hour  	= intval(date("H",$start_time));
		$temp_end_min   	= intval(date("i",$end_time));
		$temp_end_hour  	= intval(date("H",$end_time));
		
		//check in the table order_itesm first
		$query = "SELECT COUNT(a.id) FROM #__app_sch_order_items AS a INNER JOIN #__app_sch_orders AS b ON b.id = a.order_id WHERE b.order_status in ('P','S') AND a.sid = '$sid' AND a.eid = '$eid' AND a.booking_date = '$booking_date' AND((a.start_time > '$start_time' AND a.end_time < '$end_time') OR (a.start_time > '$start_time' AND a.start_time < '$end_time') OR (a.end_time > '$start_time' AND a.end_time < '$end_time')) ";
		$db->setQuery($query);
		$count = $db->loadResult();
		if($count > 0){
			return false;
		}

		$query = "SELECT COUNT(a.id) FROM #__app_sch_order_items AS a INNER JOIN #__app_sch_orders AS b ON b.id = a.order_id WHERE b.order_status in ('P','S') AND a.sid = '$sid' AND a.eid = '$eid' AND a.booking_date = '$booking_date' AND (a.start_time = '$start_time' AND a.end_time = '$end_time') ";
		$db->setQuery($query);
		$count = $db->loadResult();
		if($count > 0){
			return false;
		}

		//checck in the temp table to see if user already book this time slots
		$query = "SELECT COUNT(a.id) FROM #__app_sch_temp_order_items AS a INNER JOIN #__app_sch_temp_orders AS b ON b.id = a.order_id WHERE a.booking_date = '$booking_date' AND a.sid = '$sid' AND a.eid = '$eid' AND b.unique_cookie = '$unique_cookie' AND ((a.start_time > '$start_time' AND a.end_time < '$end_time') OR (a.start_time > '$start_time' AND a.start_time < '$end_time') OR (a.end_time > '$start_time' AND a.end_time < '$end_time')) ";
		$db->setQuery($query);
		$count = $db->loadResult();
		if($count > 0){
			return false;
		}

		$query = "SELECT COUNT(a.id) FROM #__app_sch_temp_order_items AS a INNER JOIN #__app_sch_temp_orders AS b ON b.id = a.order_id WHERE a.booking_date = '$booking_date' AND a.sid = '$sid' AND a.eid = '$eid' AND (a.start_time = '$start_time' AND a.end_time = '$end_time') ";
		$db->setQuery($query);
		$count = $db->loadResult();
		if($count > 0){
			return false;
		}
		return true;
	}
	
	/**
	 * Check number of available slots of custom time slots
	 *
	 * @param unknown_type $row
	 */
	function checkCustomSlots($row){
		global $mainframe,$configClass;
		$db 				= JFactory::getDbo();
		$unique_cookie		= $_COOKIE['unique_cookie'];
		$start_time 		= $row->start_time;
		$end_time   		= $row->end_time;
		$booking_date 		= $row->booking_date;
		$sid				= $row->sid;
		$eid 				= $row->eid;
		$nslots 			= $row->nslots;
		$temp_start_min 	= intval(date("i",$start_time));
		$temp_start_hour  	= intval(date("H",$start_time));
		$temp_end_min   	= intval(date("i",$end_time));
		$temp_end_hour  	= intval(date("H",$end_time));

		$db->setQuery("Select nslots from #__app_sch_custom_time_slots where sid = '$sid' and start_hour = '$temp_start_hour' and start_min = '$temp_start_min' and end_hour = '$temp_end_hour' and end_min = '$temp_end_min'");
		$number_slots_in_db = $db->loadResult();
		
		//select in order_items
		$query = "Select sum(a.nslots) from #__app_sch_order_items as a inner join #__app_sch_orders as b on b.id = a.order_id where a.sid = '$sid' and a.eid = '$eid' and a.start_time = '$start_time' and a.end_time = '$end_time' and b.order_status in ('P','S')";
		$db->setQuery($query);
		$remain_slots = $db->loadResult();
		$query = "Select sum(a.nslots) from #__app_sch_temp_order_items as a inner join #__app_sch_temp_orders as b on b.id = a.order_id where b.unique_cookie like '$unique_cookie' and a.sid = '$sid' and a.eid = '$eid' and a.start_time = '$start_time' and a.end_time = '$end_time'";
		$db->setQuery($query);
		$count = $db->loadResult();
		
		$number_slots_available = $number_slots_in_db - $count - $remain_slots;
		if($number_slots_available < $nslots){
			return false;
		}else{
			return true;
		}
	}

	/**
	 * Calculate booking date for repeat booking function
	 *
	 * @param unknown_type $from_date
	 * @param unknown_type $to_date
	 * @param unknown_type $type /day,week,month:  1,2,3
	 */
	function calculateBookingDate($from_date,$to_date,$type,$type1){
		global $mainframe;
		switch ($type){
			case "1":
				$returnArr = HelperOSappscheduleCalendar::calculateBookingDateFollowingDay($from_date,$to_date,$type1);
				break;
			case "2":
				$returnArr = HelperOSappscheduleCalendar::calculateBookingDateFollowingWeek($from_date,$to_date,$type1);
				break;
			case "3":
				$returnArr = HelperOSappscheduleCalendar::calculateBookingDateFollowingMonth($from_date,$to_date,$type1);
				break;
		}
		return $returnArr;
	}

	function calculateBookingDateFollowingDay($from_date,$to_date,$type1){
		global $mainframe;
		$returnArr = array();
		$from_date_int = strtotime($from_date);
		switch ($type1){
			case "1":
				$to_date_int = $from_date_int + 24*3600*$to_date;
			break;
			case "2":
				$to_date_int = $from_date_int + 7*24*3600*$to_date;
			break;
			case "3":
				$current_month = intval(date("m",$from_date_int));
				if($current_month + $to_date > 12){
					$month = $current_month + $to_dat - 12;
					$year  = date("Y",$from_date_int) + 1;
				}else{
					$month = $current_month + $to_date;
					$year  = date("Y",$from_date_int);
				}
				$day = date("d",$from_date_int);
				$to_date_int = strtotime($year."-".$month."-".$day);
			break;
		}
		for($i=$from_date_int;$i<=$to_date_int;$i=$i+24*3600){
			$returnArr[count($returnArr)] = date("Y-m-d",$i);
		}
		return $returnArr;
	}

	function calculateBookingDateFollowingWeek($from_date,$to_date,$type1){
		global $mainframe;
		$returnArr = array();
		$from_date_int = strtotime($from_date);
		switch ($type1){
			case "1":
				$to_date_int = $from_date_int + 24*3600*$to_date;
			break;
			case "2":
				$to_date_int = $from_date_int + 7*24*3600*$to_date;
			break;
			case "3":
				$current_month = intval(date("m",$from_date_int));
				if($current_month + $to_date > 12){
					$month = $current_month + $to_dat - 12;
					$year  = date("Y",$from_date_int) + 1;
				}else{
					$month = $current_month + $to_date;
					$year  = date("Y",$from_date_int);
				}
				$day = date("d",$from_date_int);
				$to_date_int = strtotime($year."-".$month."-".$day);
			break;
		}
		for($i=$from_date_int;$i<=$to_date_int;$i=$i+24*3600*7){
			$returnArr[count($returnArr)] = date("Y-m-d",$i);
		}
		return $returnArr;
	}

	function calculateBookingDateFollowingMonth($from_date,$to_date,$type1){
		global $mainframe;
		$returnArr = array();
		$from_date_int = strtotime($from_date);
		switch ($type1){
			case "1":
				$to_date_int = $from_date_int + 24*3600*$to_date;
			break;
			case "2":
				$to_date_int = $from_date_int + 7*24*3600*$to_date;
			break;
			case "3":
				$current_month = intval(date("m",$from_date_int));
				if($current_month + $to_date > 12){
					$month = $current_month + $to_dat - 12;
					$year  = date("Y",$from_date_int) + 1;
				}else{
					$month = $current_month + $to_date;
					$year  = date("Y",$from_date_int);
				}
				$day = date("d",$from_date_int);
				$to_date_int = strtotime($year."-".$month."-".$day);
			break;
		}

		$i = $from_date_int;
		while ($i<=$to_date_int) {
			$returnArr[count($returnArr)] = date("Y-m-d",$i);
			$d = intval(date("d",$i));
			$m = intval(date("m",$i));
			$y = intval(date("Y",$i));
			if($m==12){
				$y = $y + 1;
			}else{
				$m =  $m + 1;
			}
			$i = strtotime($y."-".$m."-".$d);
		}
		return $returnArr;
	}
}
?>