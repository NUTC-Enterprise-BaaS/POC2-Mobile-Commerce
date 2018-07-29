<?php
/*------------------------------------------------------------------------
# ajax.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2012 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// No direct access.
defined('_JEXEC') or die;


class OsAppscheduleAjax{
	/**
	 * Ajax default function
	 *
	 * @param unknown_type $option
	 * @param unknown_type $task
	 */
	function display($option,$task){
		switch ($task){
			case "ajax_checkcouponcode":
				OsAppscheduleAjax::checkCouponCode();
			break;
			case "ajax_addtocart":
				OsAppscheduleAjax::addToCart($option);
			break;
			case "ajax_reselect":
				OsAppscheduleAjax::reselect($option);
			break;
			case "ajax_showinfor":
				OsAppscheduleAjax::showInforForm($option);
			break;
			case "ajax_captcha":
				OsAppscheduleAjax::captcha($option);
			break;
			case "ajax_confirminfo":
				OsAppscheduleAjax::confirmInforForm($option);
			break;
			case "ajax_loadServices":
				$year 				= JRequest::getVar('year',0);
				$month 				= JRequest::getVar('month',0);
				$day 				= JRequest::getVar('day',0);
				$category_id		= JRequest::getInt('category_id',0);
				$employee_id  		= JRequest::getInt('employee_id',0);
				$vid				= JRequest::getInt('vid',0);
				$eid				= JRequest::getInt('eid',0);
				$sid				= JRequest::getVar('sid',0);
				$count_services		= JRequest::getInt('count_services',0);
				OsAppscheduleAjax::prepareLoadServices($option,$year,$month,$day,$category_id,$employee_id,$vid,$sid,$eid,$count_services);
			break;
			case "ajax_selectEmployee":
				OsAppscheduleAjax::selectEmployee($option);
			break;
			case "ajax_removeItem":
				OsAppscheduleAjax::removeItem($option);
			break;
			case "ajax_updatenslots":
				OsAppscheduleAjax::updatenSlots($option);
			break;
			case "ajax_removetemptimeslot":
				OsAppscheduleAjax::removeTemporarityTimeSlot($option);
			break;
			case "ajax_removerestdayAjax":
				OsAppscheduleAjax::removerestdayAjax();
			break;
			case "ajax_addrestdayAjax":
				OsAppscheduleAjax::addrestdayAjax();
			break;
			case "ajax_removeOrderItemAjax":
				OsAppscheduleAjax::removeOrderItemAjax();
			break;
			case "ajax_removeOrderItemAjaxCalendar":
				OsAppscheduleAjax::removeOrderItemAjaxCalendar();
			break;
			case "ajax_changeTimeSlotDate":
				OsAppscheduleAjax::changeTimeSlotDate();
			break;
            case "ajax_loadCalendatDetails":
                OsAppscheduleAjax::loadCalendatDetails();
            break;
			case "ajax_checkingVersion":
				OsAppscheduleAjax::checkingVersion();
			break;
            case "ajax_changeCheckinOrderItemAjax":
                OsAppscheduleAjax::changeCheckinOrderItem();
                break;
		}
	}
	
	/**
	 * Load Services information
	 *
	 * @param unknown_type $option
	 * @param unknown_type $year
	 * @param unknown_type $month
	 * @param unknown_type $day
	 * @param unknown_type $category_id
	 * @param unknown_type $employee_id
	 * @param unknown_type $vid
	 */
	static function prepareLoadServices($option,$year,$month,$day,$category_id,$employee_id,$vid,$sid,$eid,$count_services){
		global $mainframe;
		$db 				= JFactory::getDbo();
		if($category_id > 0){
			$catSql = " and category_id = '$category_id' ";
		}else{
			$catSql =  "";
		}
		if($employee_id > 0){
			$employeeSql = " and id in (Select service_id from #__app_sch_employee_service where employee_id = '$employee_id')";
		}else{
			$employeeSql = "";
		}
		if(($sid > 0) and ($count_services == 1)){
			$sidSql = " and id = '$sid'";
		}else{
			$sidSql = "";
		}

		if($vid > 0){
			$vidSql = " and id in (Select sid from #__app_sch_venue_services where vid = '$vid')";
		}else{
			$vidSql = "";
		}
		$current_day 		= date("Y-m-d",HelperOSappscheduleCommon::getRealTime());
		$current_day_int 	= strtotime($current_day);
		
		$temp_day 			= $year."-".$month."-".$day;
		$temp_day_int    	= strtotime($temp_day);
		
		if($temp_day_int < $current_day_int){
			//return nothing
			$services = array();
		}else{
			$db->setQuery("Select * from #__app_sch_services where published = '1' $catSql $employeeSql $vidSql $sidSql ".HelperOSappscheduleCommon::returnAccessSql()." order by ordering");
			$services = $db->loadObjectList();
		}
		//echo $db->getQuery();
		OsAppscheduleAjax::loadServices($option,$services,$year,$month,$day,$category_id,$employee_id,$vid,$sid,$eid);
		exit();
	}
	
	static function loadServices($option,$services,$year,$month,$day,$category_id,$employee_id,$vid,$sid,$eid){
		global $mainframe,$configClass;
		$unique_cookie = OSBHelper::getUniqueCookie();// $_COOKIE['unique_cookie'];
		$db = JFactory::getDbo();
		//$db->setQuery("DELETE FROM #__app_sch_temp_temp_order_items WHERE unique_cookie LIKE '$unique_cookie'");
		//$db->query();
		HelperOSappscheduleCommon::removeTempSlots();
		//check to see if this day is off
		if(intval($month) < 10){
			$month = "0".$month;
		}
		if(intval($day) < 10){
			$day = "0".$day;
		}
		$date = $year."-".$month."-".$day;
		$date_int = strtotime($date);
		$date_we  = date("N",$date_int);
		$db = JFactory::getDbo();
		$db->setQuery("Select `is_day_off` from #__app_sch_working_time where id = '$date_we'");
		$is_day_off = $db->loadResult();
		if($is_day_off == 0){
			$db->setQuery("Select count(id) from #__app_sch_working_time_custom where (`worktime_date` <= '$date' and `worktime_date_to` >= '$date')");
			$count = $db->loadResult();
			if($count > 0){
				$db->setQuery("Select `is_day_off` from #__app_sch_working_time_custom where (`worktime_date` <= '$date' and `worktime_date_to` >= '$date')");
				$vl = $db->loadResult();
				if($vl == 0){
					$is_day_off = 0;
				}else{
					$is_day_off = 1;
				}
			}
		}
		HTML_OsAppscheduleAjax::loadServicesHTML($option,$services,$year,$month,$day,$is_day_off,$category_id,$employee_id,$vid,$sid,$eid);
	}
	
	
	static function selectEmployee($option){
		global $mainframe;
		$sid = JRequest::getInt('sid',0);
		$year = JRequest::getVar('year');
		$month = JRequest::getVar('month');
		$day = JRequest::getVar('day');
		$date[0] = $day;
		$date[1] = $month;
		$date[2] = $year;
		OsAppscheduleAjax::loadEmployees($option,$sid,$date,$vid);
		echo  "@@@@";
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__app_sch_services where id = '$sid'");
		$service = $db->loadObject();
	
		if($day < 10){
			$day = "0".$day;
		}
		if($month < 10){
			$month= "0".$month;
		}
		$current_date = $year."-".$month."-".$day;
		$db->setQuery("Select count(a.id) from #__app_sch_order_items as a inner join #__app_sch_orders as b on b.id = a.order_id where a.sid = '$sid' and b.order_status in ('P','S') and a.booking_date = '$current_date'");
		$nbook = $db->loadResult();
		HTML_OsAppscheduleAjax::showServiceDetails($service,$date,$nbook);
	}
	/**
	 * Load the time frame of one employee
	 *
	 * @param int $sid
	 * @param int $eid
	 * @param array $date array(day,month,year)
	 */
	static  function loadEmployee($sid,$eid,$date,$vid){
		global $mainframe,$configClass;
		//print_r($date);
		//get start hour and end hour for this employee today
		HTML_OsAppscheduleAjax::loadEmployeeFrame($sid,$eid,$date,$vid);
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $option
	 * @param unknown_type $sid
	 * @param unknown_type $eid
	 * @param unknown_type $date
	 */
	static function loadEmployees($option,$sid,$employee_id,$date,$vid,$service_id,$eid){
		global $mainframe;
		/*
		$db = JFactory::getDbo();
		$tempdate = strtotime($date[2]."-".$date[1]."-".$date[0]);
		$day = strtolower(substr(date("D",$tempdate),0,2));
		$day1 = date("Y-m-d",$tempdate);
		if($vid > 0){
			$vidSql = " and a.id IN (Select employee_id from #__app_sch_employee_service where service_id = '$sid' and vid = '$vid')";
		}else{
			$vidSql = "";
		}
		if($employee_id > 0){
			$employeeSql = " and a.id = '$employee_id'";
		}else{
			$employeeSql = "";
		}
		$db->setQuery("Select a.* from #__app_sch_employee as a inner join #__app_sch_employee_service as b on a.id = b.employee_id where a.published = '1' and b.service_id = '$sid' and b.".$day." = '1' and a.id NOT IN (Select eid from #__app_sch_employee_rest_days where rest_date <= '$day1' and rest_date_to >= '$day1') $vidSql $employeeSql order by b.ordering");
		$employees = $db->loadObjectList();
		*/
		
		$employees = HelperOSappscheduleCommon::loadEmployees($date,$sid,$employee_id,$vid);
		HTML_OsAppscheduleAjax::loadEmployeeFrames($option,$employees,$sid,$date,$vid,$service_id,$eid);
	}
	
	/**
	 * Reselect item
	 *
	 * @param unknown_type $option
	 */
	function reselect($option){
		global $mainframe;
		$date 					= JRequest::getVar('date','');
		$sid					= JRequest::getVar('sid',0);
		$eid					= JRequest::getVar('eid',0);
		$category_id 			= JRequest::getInt('category_id',0);
		$date_from				= JRequest::getVar('date_from','');
		$date_to				= JRequest::getVar('date_to','');
		$vid		 			= JRequest::getInt('vid',0);
		
		$booking_date			= date("Y-m-d",$date);
		$date					= explode("-",$booking_date);
		echo "1111";
		OsAppscheduleAjax::cart($userdata,$vid, $category_id,$eid,$date_from,$date_to);
		echo "2222";
		OsAppscheduleAjax::loadEmployee($sid,$eid,$date);
		exit();
	}
	
	/**
	 * Add to cart
	 *
	 * @param unknown_type $option
	 */
	static function addToCart($option){
		global $mainframe,$configClass;
		$realtime 				= HelperOSappscheduleCommon::getRealTime();
		$db = JFactory::getDbo();
		$update_temp_table		= Jrequest::getVar('update_temp_table',0);
		$start_booking_time 	= JRequest::getVar('start_booking_time','');
		//echo date("H:i",$start_booking_time);
		$end_booking_time		= JRequest::getVar('end_booking_time','');
		$sid					= JRequest::getInt('sid',0);
		$eid					= JRequest::getInt('eid',0);
		$vid					= JRequest::getInt('vid',0);
		$category_id			= JRequest::getInt('category_id',0);
		$employee_id			= JRequest::getInt('employee_id',0);
		$date_from				= JRequest::getVar('date_from','');
		$date_to				= JRequest::getVar('date_to','');
		$count_services			= JRequest::getInt('count_services',0);
		
		$booking_date			= date("Y-m-d",$start_booking_time);
		$date 					= array();
		$date[0]				= date("d",$start_booking_time);
		$date[1]				= date("m",$start_booking_time);
		$date[2]			 	= date("Y",$start_booking_time);
		$unique_cookie 			= OSBHelper::getUniqueCookie();//$_COOKIE['unique_cookie'];
		$repeat					= Jrequest::getVar('repeat','');
		$nslots 				= Jrequest::getVar('nslots',0);
		$config = new JConfig();
		$offset = $config->offset;
		date_default_timezone_set($offset);
		if($update_temp_table == 1){
			$additional_information	= $_GET['additional_information'];
			$extraFields = explode("@",$additional_information);
			$bookingData = array();
			if($repeat != ""){ //prepare data for the repeat booking
				$repeatArr = explode("|",$repeat);
				$repeat_type  = $repeatArr[0];
				$repeat_to    = $repeatArr[1];
				$repeat_type1 = $repeatArr[2];
				$repeatDate   = HelperOSappscheduleCalendar::calculateBookingDate($booking_date,$repeat_to,$repeat_type,$repeat_type1);
				if(count($repeatDate) > 0){
					for($i=0;$i<count($repeatDate);$i++){
						$rdate = $repeatDate[$i];
						$bookingData[$i]->date  = $rdate;
						//prepare hours
						$stime = date("H:i:s",$start_booking_time);
						$etime = date("H:i:s",$end_booking_time);
						$tempsdate = $rdate." ".$stime;
						$tempedate = $rdate." ".$etime;
						$bookingData[$i]->start_time = strtotime($tempsdate);
						$bookingData[$i]->end_time   = strtotime($tempedate);
						$bookingData[$i]->additional_information = $additional_information;
						$bookingData[$i]->nslots 	 = $nslots;
						$bookingData[$i]->sid		 = $sid;
						$bookingData[$i]->eid		 = $eid;
					}
				}
			}else{
				$bookingData[0]->date 					= date("Y-m-d",$start_booking_time);
				$bookingData[0]->start_time 			= $start_booking_time;
				$bookingData[0]->end_time 				= $end_booking_time;
				$bookingData[0]->additional_information = $additional_information;
				$bookingData[0]->nslots 	 			= $nslots;
				$bookingData[0]->sid		 			= $sid;
				$bookingData[0]->eid		 			= $eid;
			}
			
			$db->setQuery("Select * from #__app_sch_services where id = '$sid'");
			$service = $db->loadObject();
			$canbook = 1;
			
			if($service->service_time_type == 1){
				//Booking data array
				for($i=0;$i<count($bookingData);$i++){
					$book = $bookingData[$i];
					
					$book_start_booking_date = $book->start_time;
					$book_end_booking_time   = $book->end_time;
					
					//convert to GMT timezone
					//date_default_timezone_set('UTC');
					//$local_time = date("Y-m-d H:i:s", time());
					
					$temp_start_min 		 = intval(date("i",$book_start_booking_date));
					$temp_start_hour  		 = intval(date("H",$book_start_booking_date));
					$temp_end_min   		 = intval(date("i",$book_end_booking_time));
					$temp_end_hour  		 = intval(date("H",$book_end_booking_time));
					
					$sid					 = $bookingData[$i]->sid;
					$eid 					 = $bookingData[$i]->eid;
					$nslots					 = $bookingData[$i]->nslots;
					$additional_information  = $bookingData[$i]->additional_information;
					
					//insert into temp temp data table
					if($i==0){
						$parent_id = 0;
					}else{
						$parent_id = 1;
					}
					$db->setQuery("Insert into #__app_sch_temp_temp_order_items (id,parent_id,unique_cookie,sid,eid,start_time,end_time,booking_date,nslots,params) values (NULL,'$parent_id','$unique_cookie','$sid','$eid','$book_start_booking_date','$book_end_booking_time','$book->date','$nslots','$repeat')");
					$db->query();
					$order_item_id = $db->insertID();
					if(count($extraFields) > 0){
						for($j=0;$j<count($extraFields);$j++){
							$field = $extraFields[$j];
							$field = explode("-",$field);
							$field_id = $field[0];
							if($field_id > 0){
								$field_values = $field[1];
								$field_values  = explode(",",$field_values);
								if(count($field_values) > 0){
									for($l=0;$l<count($field_values);$l++){
										$value = $field_values[$l];
										$db->setQuery("INSERT INTO #__app_sch_temp_temp_order_field_options (id,order_item_id,field_id,option_id) 	VALUES (NULL,'$order_item_id','$field_id','$value')");
										$db->query();
									}
								}
							}
						}
					}
				}//booking array
				
				//checking again
				//with each booking date need to check
				//if the custom time slot : check to see if is the any time slots available
				//if the normal time slot : check to see if someone book the slot already
				//check if it is offline date of service
				//check if it is the rest day
				//check if it isn't working day of employee
				
				$db->setQuery("Select * from #__app_sch_temp_temp_order_items where unique_cookie like '$unique_cookie'");
				$rows = $db->loadObjectList();
				if(count($rows) > 0){
					$errorArr = array();
					for($i=0;$i<count($rows);$i++){
						$row = $rows[$i];
						//check number of slots. 
						$config = new JConfig();
						$offset = $config->offset;
						date_default_timezone_set($offset);
						//echo date("H:i",$row->start_time);
						//echo "<BR />";
						if(!HelperOSappscheduleCalendar::checkSlots($row)){
							$canbook = 0;
							$errorArr[count($errorArr)] = HelperOSappscheduleCalendar::returnSlots($row);
						}
					}
				}
				//can book ?
				if($canbook == 1){
					$db->setQuery("SELECT COUNT(id) FROM #__app_sch_temp_orders where unique_cookie like '$unique_cookie'");
					$count = $db->loadResult();
					if($count == 0){
						//insert into order temp table
						$db->setQuery("INSERT INTO #__app_sch_temp_orders (id, unique_cookie,created_on) VALUES (NULL,'$unique_cookie','".time()."')");
						$db->query();
						$order_id = $db->insertID();
					}else{
						$db->setQuery("SELECT id FROM #__app_sch_temp_orders WHERE unique_cookie LIKE '$unique_cookie'");
						$order_id = $db->loadResult();
					}
					
					//update employee and time
					for($j=0;$j<count($rows);$j++){
						$row = $rows[$j];
						$start_booking_time = $row->start_time;
						$end_booking_time   = $row->end_time;
						$booking_date		= $row->booking_date;
						$nslots 			= $row->nslots;
						$db->setQuery("INSERT INTO #__app_sch_temp_order_items (id,order_id,sid,eid,start_time,end_time,booking_date,nslots,params) VALUES (NULL,'$order_id','$sid','$eid','$start_booking_time','$end_booking_time','$booking_date','$nslots','$repeat')");
						$db->query();
						$order_item_id = $db->insertID();
						//update fields
						if(count($extraFields) > 0){
							for($i=0;$i<count($extraFields);$i++){
								$field = $extraFields[$i];
								$field = explode("-",$field);
								$field_id = $field[0];
								if($field_id > 0){
									$field_values = $field[1];
									$field_values  = explode(",",$field_values);
									if(count($field_values) > 0){
										for($l=0;$l<count($field_values);$l++){
											$value = $field_values[$l];
											$db->setQuery("INSERT INTO #__app_sch_temp_order_field_options (id,order_item_id,field_id,option_id) 	VALUES (NULL,'$order_item_id','$field_id','$value')");
											$db->query();
										}
									}
								}
							}
						}
					}
					//empty from temp temp data table
					HelperOSappscheduleCommon::removeTempSlots();
				
					echo "1111";
					if($configClass['using_cart'] == 1){
						OsAppscheduleAjax::cart($userdata,$vid,$category_id,$employee_id,$date_from,$date_to);
					}else{
						echo Jroute::_('index.php?option=com_osservicesbooking&task=form_step1&vid='.$vid.'&category_id='.$category_id.'&employee_id='.$employee_id.'&date_from='.$date_from.'&date_to='.$date_to.'&Itemid='.JRequest::getInt('Itemid',0));
					}
					echo "@3333";
					//echo JText::_('OS_ITEM_HAS_BEEN_ADD_TO_CART');
					self::showInformPopup();
					echo "2222";
					//OsAppscheduleAjax::loadEmployee($sid,$eid,$date,$vid);
					OsAppscheduleAjax::prepareLoadServices($option,intval($date[2]),intval($date[1]),intval($date[0]),$category_id,$employee_id,$vid,$sid,$eid,$count_services);
				}else{//cannot book
					echo "1111";
					if($configClass['using_cart'] == 1){
						OsAppscheduleAjax::cart($userdata,$vid,$category_id,$employee_id,$date_from,$date_to);
					}else{
						echo "#";
					}
					echo "@";
					echo "2222";
					OSappscheduleInformation::showError($sid,$eid,$errorArr,$vid);
				}
			}else{ //time slot == 0 [normal]
				$additional_information	= $_GET['additional_information'];
				$extraFields = explode("@",$additional_information);
				$bookingData = array();
				
				if($repeat != ""){ //prepare data for the repeat booking
					$repeatArr = explode("|",$repeat);
					$repeat_type  = $repeatArr[0];
					$repeat_to    = $repeatArr[1];
					$repeat_type1 = $repeatArr[2];
					$repeatDate   = HelperOSappscheduleCalendar::calculateBookingDate($booking_date,$repeat_to,$repeat_type,$repeat_type1);
					if(count($repeatDate) > 0){
						for($i=0;$i<count($repeatDate);$i++){
							$rdate = $repeatDate[$i];
							$bookingData[$i]->date  = $rdate;
							//prepare hours
							$stime = date("H:i:s",$start_booking_time);
							$etime = date("H:i:s",$end_booking_time);
							$tempsdate = $rdate." ".$stime;
							$tempedate = $rdate." ".$etime;
							$bookingData[$i]->start_time = strtotime($tempsdate);
							$bookingData[$i]->end_time   = strtotime($tempedate);
							$bookingData[$i]->additional_information = $additional_information;
							$bookingData[$i]->nslots 	 = $nslots;
							$bookingData[$i]->sid		 = $sid;
							$bookingData[$i]->eid		 = $eid;
						}
					}
				}else{
					$bookingData[0]->date 					= date("Y-m-d",$start_booking_time);
					$bookingData[0]->start_time 			= $start_booking_time;
					$bookingData[0]->end_time 				= $end_booking_time;
					$bookingData[0]->additional_information = $additional_information;
					$bookingData[0]->nslots 	 			= $nslots;
					$bookingData[0]->sid		 			= $sid;
					$bookingData[0]->eid		 			= $eid;
				}
				$db->setQuery("Select * from #__app_sch_services where id = '$sid'");
				$service = $db->loadObject();
				$canbook = 1;
			
				//Booking data array
				for($i=0;$i<count($bookingData);$i++){
					$book = $bookingData[$i];
				
					$book_start_booking_date = $book->start_time;
					$book_end_booking_time   = $book->end_time;
					$temp_start_min 		 = intval(date("i",$book_start_booking_date));
					$temp_start_hour  		 = intval(date("H",$book_start_booking_date));
					$temp_end_min   		 = intval(date("i",$book_end_booking_time));
					$temp_end_hour  		 = intval(date("H",$book_end_booking_time));
					
					$sid					 = $bookingData[$i]->sid;
					$eid 					 = $bookingData[$i]->eid;
					$nslots					 = $bookingData[$i]->nslots;
					$additional_information  = $bookingData[$i]->additional_information;
					
					//insert into temp temp data table
					if($i==0){
						$parent_id = 0;
					}else{
						$parent_id = 1;
					}
					$db->setQuery("Insert into #__app_sch_temp_temp_order_items (id,parent_id,unique_cookie,sid,eid,start_time,end_time,booking_date,nslots,params) values (NULL,'$parent_id','$unique_cookie','$sid','$eid','$book_start_booking_date','$book_end_booking_time','$book->date','$nslots','$repeat')");
					$db->query();
					$order_item_id = $db->insertID();
					
					if(count($extraFields) > 0){
						for($j=0;$j<count($extraFields);$j++){
							$field = $extraFields[$j];
							$field = explode("-",$field);
							$field_id = $field[0];
							if($field_id > 0){
								$field_values = $field[1];
								$field_values  = explode(",",$field_values);
								if(count($field_values) > 0){
									for($l=0;$l<count($field_values);$l++){
										$value = $field_values[$l];
										$db->setQuery("INSERT INTO #__app_sch_temp_temp_order_field_options (id,order_item_id,field_id,option_id) 	VALUES (NULL,'$order_item_id','$field_id','$value')");
										$db->query();
									}
								}
							}
						}
					}
				}//end booking array
				//checking again
				//with each booking date need to check
				//if the custom time slot : check to see if is the any time slots available
				//if the normal time slot : check to see if someone book the slot already
				//check if it is offline date of service
				//check if it is the rest day
				//check if it isn't working day of employee
				
				$db->setQuery("Select * from #__app_sch_temp_temp_order_items where unique_cookie like '$unique_cookie'");
				//echo $db->getQuery();
				$rows = $db->loadObjectList();
				//print_r($rows);
				//die();
				if(count($rows) > 0){
					$errorArr = array();
					for($i=0;$i<count($rows);$i++){
						$row = $rows[$i];
						$config = new JConfig();
						$offset = $config->offset;
						date_default_timezone_set($offset);
						//check number of slots. 
						if(!HelperOSappscheduleCalendar::checkSlots($row)){
							$canbook = 0;
							$errorArr[count($errorArr)] = $row;
						}
					}
				}
				//die();
				//can book ?
				if($canbook == 1){
					$db->setQuery("SELECT COUNT(id) FROM #__app_sch_temp_orders where unique_cookie like '$unique_cookie'");
					$count = $db->loadResult();
					if($count == 0){
						//insert into order temp table
						$db->setQuery("INSERT INTO #__app_sch_temp_orders (id, unique_cookie,created_on) VALUES (NULL,'$unique_cookie','".time()."')");
						$db->query();
						$order_id = $db->insertID();
					}else{
						$db->setQuery("SELECT id FROM #__app_sch_temp_orders WHERE unique_cookie LIKE '$unique_cookie'");
						$order_id = $db->loadResult();
					}
					
					//update employee and time
					for($j=0;$j<count($rows);$j++){
						$row = $rows[$j];
						$start_booking_time = $row->start_time;
						$end_booking_time   = $row->end_time;
						$booking_date		= $row->booking_date;
						$nslots 			= 0;
						$db->setQuery("INSERT INTO #__app_sch_temp_order_items (id,order_id,sid,eid,start_time,end_time,booking_date,nslots,params) VALUES (NULL,'$order_id','$sid','$eid','$start_booking_time','$end_booking_time','$booking_date','$nslots','$repeat')");
						$db->query();
						$order_item_id = $db->insertID();
						//update fields
						if(count($extraFields) > 0){
							for($i=0;$i<count($extraFields);$i++){
								$field = $extraFields[$i];
								$field = explode("-",$field);
								$field_id = $field[0];
								if($field_id > 0){
									$field_values = $field[1];
									$field_values  = explode(",",$field_values);
									if(count($field_values) > 0){
										for($l=0;$l<count($field_values);$l++){
											$value = $field_values[$l];
											$db->setQuery("INSERT INTO #__app_sch_temp_order_field_options (id,order_item_id,field_id,option_id) 	VALUES (NULL,'$order_item_id','$field_id','$value')");
											$db->query();
										}
									}
								}
							}
						}
					}
					//empty from temp temp data table
					HelperOSappscheduleCommon::removeTempSlots();
				
					echo "1111";
					
					if($configClass['using_cart'] == 1){
						OsAppscheduleAjax::cart($userdata,$vid,$category_id,$employee_id,$date_from,$date_to);
					}else{
						echo Jroute::_('index.php?option=com_osservicesbooking&task=form_step1&vid='.$vid.'&category_id='.$category_id.'&employee_id='.$employee_id.'&date_from='.$date_from.'&date_to='.$date_to.'&Itemid='.JRequest::getInt('Itemid',0));
					}
					echo "@3333";
					//echo JText::_('OS_ITEM_HAS_BEEN_ADD_TO_CART');
					self::showInformPopup();
					echo "2222";
					//OsAppscheduleAjax::loadEmployee($sid,$eid,$date,$vid);
					OsAppscheduleAjax::prepareLoadServices($option,intval($date[2]),intval($date[1]),intval($date[0]),$category_id,$employee_id,$vid,$sid,$eid,$count_services);
				}else{//cannot book
					echo "1111";
					if($configClass['using_cart'] == 1){
						OsAppscheduleAjax::cart($userdata,$vid,$category_id,$employee_id,$date_from,$date_to);
					}else{
						//echo Jroute::_('index.php?option=com_osservicesbooking&task=form_step1&vid='.$vid.'&category_id='.$category_id.'&employee_id='.$employee_id.'&date_from='.$date_from.'&date_to='.$date_to);
						echo "#";
					}
					echo "@3333";
					echo "2222";
					OSappscheduleInformation::showError($sid,$eid,$errorArr,$vid);
					
				}
			}
		}else{ //only need to check update_temp_table = 0
			$canbook = 1;
			$db->setQuery("Select * from #__app_sch_services where id = '$sid'");
			$service = $db->loadObject();
			
			if($service->service_time_type == 1){
				//checking again
				$db->setQuery("Select * from #__app_sch_temp_temp_order_items where unique_cookie like '$unique_cookie'");
				$rows = $db->loadObjectList();
				if(count($rows) > 0){
					$errorArr = array();
					for($i=0;$i<count($rows);$i++){
						$row = $rows[$i];
						//check number of slots. 
						if(!HelperOSappscheduleCalendar::checkSlots($row)){
							$canbook = 0;
							$errorArr[count($errorArr)] = HelperOSappscheduleCalendar::returnSlots($row);
						}
					}
				}
				
				//can book ?
				if($canbook == 1){
					$db->setQuery("SELECT COUNT(id) FROM #__app_sch_temp_orders where unique_cookie like '$unique_cookie'");
					$count = $db->loadResult();
					if($count == 0){
						//insert into order temp table
						$db->setQuery("INSERT INTO #__app_sch_temp_orders (id, unique_cookie,created_on) VALUES (NULL,'$unique_cookie','".time()."')");
						$db->query();
						$order_id = $db->insertID();
					}else{
						$db->setQuery("SELECT id FROM #__app_sch_temp_orders WHERE unique_cookie LIKE '$unique_cookie'");
						$order_id = $db->loadResult();
					}
					
					//update employee and time
					for($j=0;$j<count($rows);$j++){
						$row = $rows[$j];
						$start_booking_time = $row->start_time;
						$end_booking_time   = $row->end_time;
						$booking_date		= $row->booking_date;
						$nslots 			= $row->nslots;
						$db->setQuery("INSERT INTO #__app_sch_temp_order_items (id,order_id,sid,eid,start_time,end_time,booking_date,nslots,params) VALUES (NULL,'$order_id','$sid','$eid','$start_booking_time','$end_booking_time','$booking_date','$nslots','$repeat')");
						$db->query();
						$order_item_id = $db->insertID();
						//update fields
						
						$db->setQuery("SELECT * FROM #__app_sch_temp_temp_order_field_options WHERE order_item_id = '$row->id'");
						$extraFields = $db->loadObjectList();
						if(count($extraFields) > 0){
							for($i=0;$i<count($extraFields);$i++){
								$field = $extraFields[$i];
								$db->setQuery("INSERT INTO #__app_sch_temp_order_field_options (id,order_item_id,field_id,option_id) 	VALUES (NULL,'$row->id','$field->field_id','$field->option_id')");
								$db->query();
							}
						}
					}
					//empty from temp temp data table
					HelperOSappscheduleCommon::removeTempSlots();
				
					echo "1111";
					if($configClass['using_cart'] == 1){
						OsAppscheduleAjax::cart($userdata,$vid,$category_id,$employee_id,$date_from,$date_to);
					}else{
						echo Jroute::_('index.php?option=com_osservicesbooking&task=form_step1&vid='.$vid.'&category_id='.$category_id.'&employee_id='.$employee_id.'&date_from='.$date_from.'&date_to='.$date_to.'&Itemid='.JRequest::getInt('Itemid',0));
					}
					echo "@3333";
					//echo JText::_('OS_ITEM_HAS_BEEN_ADD_TO_CART');
					self::showInformPopup();
					echo "2222";
					//OsAppscheduleAjax::loadEmployee($sid,$eid,$date,$vid);
					//echo $employee_id;
					OsAppscheduleAjax::prepareLoadServices($option,intval($date[2]),intval($date[1]),intval($date[0]),$category_id,$employee_id,$vid,$sid,$eid);
					
				}else{//cannot book
					echo "1111";
					if($configClass['using_cart'] == 1){
						OsAppscheduleAjax::cart($userdata,$vid,$category_id,$employee_id,$date_from,$date_to);
					}else{
						echo "#";
					}
					echo "@3333";
					echo "2222";
					OSappscheduleInformation::showError($sid,$eid,$errorArr,$vid);
					
				}
			}else{ //normal time slot
				if(count($rows) > 0){
					$errorArr = array();
					for($i=0;$i<count($rows);$i++){
						$row = $rows[$i];
						//check number of slots. 
						if(!HelperOSappscheduleCalendar::checkSlots($row)){
							$canbook = 0;
							$errorArr[count($errorArr)] = $row;
						}
					}
				}
				
				if($canbook == 1){
					$db->setQuery("SELECT COUNT(id) FROM #__app_sch_temp_orders where unique_cookie like '$unique_cookie'");
					$count = $db->loadResult();
					if($count == 0){
						//insert into order temp table
						$db->setQuery("INSERT INTO #__app_sch_temp_orders (id, unique_cookie,created_on) VALUES (NULL,'$unique_cookie','".time()."')");
						$db->query();
						$order_id = $db->insertID();
					}else{
						$db->setQuery("SELECT id FROM #__app_sch_temp_orders WHERE unique_cookie LIKE '$unique_cookie'");
						$order_id = $db->loadResult();
					}
					
					//update employee and time
					for($j=0;$j<count($rows);$j++){
						$row = $rows[$j];
						$start_booking_time = $row->start_time;
						$end_booking_time   = $row->end_time;
						$booking_date		= $row->booking_date;
						$nslots 			= 0;
						$db->setQuery("INSERT INTO #__app_sch_temp_order_items (id,order_id,sid,eid,start_time,end_time,booking_date,nslots,params) VALUES (NULL,'$order_id','$sid','$eid','$start_booking_time','$end_booking_time','$booking_date','$nslots','$repeat')");
						$db->query();
						$order_item_id = $db->insertID();
						//update fields
						
						$db->setQuery("SELECT * FROM #__app_sch_temp_temp_order_field_options WHERE order_item_id = '$row->id'");
						$extraFields = $db->loadObjectList();
						if(count($extraFields) > 0){
							for($i=0;$i<count($extraFields);$i++){
								$field = $extraFields[$i];
								$db->setQuery("INSERT INTO #__app_sch_temp_order_field_options (id,order_item_id,field_id,option_id) 	VALUES (NULL,'$row->id','$field->field_id','$field->option_id')");
								$db->query();
							}
						}
					}
					//empty from temp temp data table
					HelperOSappscheduleCommon::removeTempSlots();
				
					echo "1111";
					//echo $configClass['using_cart'];
					if($configClass['using_cart'] == 1){
						OsAppscheduleAjax::cart($userdata,$vid,$category_id,$employee_id,$date_from,$date_to);
					}else{
						echo Jroute::_('index.php?option=com_osservicesbooking&task=form_step1&vid='.$vid.'&category_id='.$category_id.'&employee_id='.$employee_id.'&date_from='.$date_from.'&date_to='.$date_to.'&Itemid='.JRequest::getInt('Itemid',0));
					}
					echo "@3333";
					//echo JText::_('OS_ITEM_HAS_BEEN_ADD_TO_CART');
					self::showInformPopup();
					echo "2222";
					//OsAppscheduleAjax::loadEmployee($sid,$eid,$date,$vid);
					OsAppscheduleAjax::prepareLoadServices($option,intval($date[2]),intval($date[1]),intval($date[0]),$category_id,$employee_id,$vid,$sid,$eid,$count_services);
					
				}else{//cannot book
					echo "1111";
					if($configClass['using_cart'] == 1){
						OsAppscheduleAjax::cart($userdata,$vid,$category_id,$employee_id,$date_from,$date_to);
					}else{
						echo "#";
					}
					echo "@3333";
					echo "2222";
					OSappscheduleInformation::showError($sid,$eid,$errorArr,$vid);
					
				}
			}
		}
		exit();
	}
	
	/**
	 * Show Inform Popup
	 *
	 */
	public static function showInformPopup(){
		?>
	    <p><?php echo JText::_('OS_ITEM_HAS_BEEN_ADD_TO_CART'); ?></p>
	    <BR />
	    <a href="<?php echo JRoute::_('index.php?option=com_osservicesbooking&task=form_step1');?>" class="btn"><?php echo JText::_('OS_CHECKOUT')?></a>
	    <a href="javascript:closeDialog();" class="btn"><?php echo JText::_('OS_CONTINUE_BOOKING')?></a>
	    <?php
	}
	
	static function removeItem($option){
		global $mainframe,$configClass;
		//$unique_cookie 		= $_COOKIE['unique_cookie'];
		$unique_cookie		= OSBHelper::getUniqueCookie();
		$sid 				= JRequest::getInt('sid');
		$start_time			= JRequest::getVar('start_time');
		$end_time			= JRequest::getVar('end_time');
		$eid				= JRequest::getInt('eid');
		$itemid				= JRequest::getInt('itemid',0);
		$category_id		= JRequest::getInt('category_id',0);
		$employee_id		= JRequest::getVar('employee_id',0);
		$vid				= JRequest::getVar('vid',0);
		$date_from		 	= JRequest::getVar('date_from','');
		$date_to			= JRequest::getVar('date_to','');
		
		$db = JFactory::getDbo();
		$db->setQuery("Select id from #__app_sch_temp_orders where unique_cookie like '$unique_cookie'");
		$order_id = $db->loadResult();
		$db->setQuery("Delete from #__app_sch_temp_order_items where id = '$itemid'");
		$db->query();
		$today = date("d-m-Y" ,HelperOSappscheduleCommon::getRealTime());
		$date  = explode("-",$today);
		
		$select_day 		= JRequest::getInt('select_day',intval(date("d",HelperOSappscheduleCommon::getRealTime())));
		$select_month 		= JRequest::getInt('select_month',intval(date("m",HelperOSappscheduleCommon::getRealTime())));
		$select_year 		= JRequest::getInt('select_year',intval(date("Y",HelperOSappscheduleCommon::getRealTime())));
		if(intval($select_day) == 0){
			$select_day = intval(date("d",HelperOSappscheduleCommon::getRealTime()));
		}
		if(intval($select_month) == 0){
			$select_month = intval(date("m",HelperOSappscheduleCommon::getRealTime()));
		}
		if(intval($select_year) == 0){
			$select_year = intval(date("Y",HelperOSappscheduleCommon::getRealTime()));
		}
		//$select_date		= strtotime()
		echo "1111";
		OsAppscheduleAjax::cart($userdata,$vid, $category_id,$employee_id,$date_from,$date_to);
		echo "@3333";
		echo "2222";
		//OsAppscheduleAjax::loadEmployee($sid,$eid,$date);
		OsAppscheduleAjax::prepareLoadServices($option,$select_year,$select_month,$select_day,$category_id,$employee_id,$vid,$sid,$eid);
		exit();
	}
	
	
	/**
	 * Cart function
	 *
	 */
	static function cart($userdata,$vid,$category_id,$employee_id,$date_from,$date_to){
		global $mainframe,$configClass,$lang_suffix;
		$db = JFactory::getDbo();
		$config = new JConfig();
		$offset = $config->offset;
		date_default_timezone_set($offset);
		//$userdata 			= $_COOKIE['userdata'];
		$unique_cookie			= OSBHelper::getUniqueCookie();//$_COOKIE['unique_cookie'];
		//echo $unique_cookie;
		
		$task = JRequest::getVar('task','');
		//echo $task;
		?>
			<?php
			if($unique_cookie != ""){
				$db->setQuery("SELECT count(id) FROM #__app_sch_temp_orders WHERE unique_cookie like '$unique_cookie'");
				$count_order = $db->loadResult();
				if($count_order > 0){
					$db->setQuery("SELECT id FROM #__app_sch_temp_orders WHERE unique_cookie like '$unique_cookie'");
					$order_id = $db->loadResult();
					$db->setQuery("SELECT * FROM #__app_sch_temp_order_items WHERE order_id = '$order_id'");
					$rows = $db->loadObjectList();
					if(count($rows) > 0){
						//$userdata			= explode("||",$userdata);
						$total = 0;
						?>
						<table width="100%" style="border:0px !important;">
							<?php if ($task  != "form_step2"){?>
							<tr>
								<td style="padding:0px;padding-left:5px;text-align:left;color:gray;">
									<img src="<?php echo JURI::root()?>components/com_osservicesbooking/style/images/icon-16-deny.png" border="0"> <?php echo JText::_('OS_REMOVE_SERVICE_FROM_CART');?>
								</td>
							</tr>
							<?php }?>
						<?php
						//print_r($userdata);
						for($i1=0;$i1<count($rows);$i1++){
							$row = $rows[$i1];
							$sid = $row->sid;
							if($sid > 0){
								$order_item_id		= $row->id;
								$start_booking_date = $row->start_time;
								$start_booking_date = $start_booking_date;
								$end_booking_date   = $row->end_time;
								$end_booking_date   = $end_booking_date;
								$eid				= $row->eid;
								$date_in_week		= date("N",$start_booking_date);
								$db->setQuery("SELECT field_id FROM #__app_sch_temp_order_field_options WHERE order_item_id = '$order_item_id' GROUP BY field_id");
								$fields = $db->loadObjectList();
								//calculate option value and additional price
								if(count($fields) > 0){
									//prepare the field array
									$fieldArr = array();
									for($i=0;$i<count($fields);$i++){
										$field = $fields[$i];
										if(!in_array($field->field_id,$fieldArr)){
											$fieldArr[count($fieldArr)] = $field->field_id;
										}
									}
									$field_amount = 0;
									$field_data   = "";
									for($i=0;$i<count($fieldArr);$i++){
										$fieldid = $fieldArr[$i];
										$db->setQuery("Select id,field_label$lang_suffix as field_label,field_type from #__app_sch_fields where id = '$fieldid'");
										$field = $db->loadObject();
										$field_type = $field->field_type;
										if($field_type == 1){
											//get field value
											$db->setQuery("SELECT option_id FROM #__app_sch_temp_order_field_options WHERE order_item_id= '$order_item_id' and field_id = '$fieldid'");
											$fieldvalue = $db->loadResult();
											$db->setQuery("Select * from #__app_sch_field_options where id = '$fieldvalue'");
											$fieldOption = $db->loadObject();
											if($fieldOption->additional_price > 0){
												$field_amount += $fieldOption->additional_price;
											}
											
											$field_data .= "<b>$field->field_label:</b>: ".$fieldOption->field_option;
											if($fieldOption->additional_price > 0){
												$field_data.= " - ".$fieldOption->additional_price." ".$configClass['currency_format'];
											}
											$field_data .= "<BR />";
										}elseif($field_type == 2){
											$db->setQuery("SELECT option_id FROM #__app_sch_temp_order_field_options WHERE order_item_id= '$order_item_id' and field_id = '$fieldid'");
											$fieldValueArr = $db->loadObjectList();
											if(count($fieldValueArr) > 0){
												$fieldValue = array();
												for($j=0;$j<count($fieldValueArr);$j++){
													$fieldValue[$j] = $fieldValueArr[$j]->option_id;
												}
											}
											if(count($fieldValue) > 0){
												$field_data .= "<b>$field->field_label:</b>: ";
												for($j=0;$j<count($fieldValue);$j++){
													$temp = $fieldValue[$j];
													$db->setQuery("Select * from #__app_sch_field_options where id = '$temp'");
													$fieldOption = $db->loadObject();
													if($fieldOption->additional_price > 0){
														$field_amount += $fieldOption->additional_price;
													}
													$field_data .= OSBHelper::getLanguageFieldValue($fieldOption,'field_option');
													if($fieldOption->additional_price > 0){
														$field_data.= " - ".$fieldOption->additional_price." ".$configClass['currency_format'];
													}
													$field_data .= ",";
												}
												$field_data = substr($field_data,0,strlen($field_data)-1);
												$field_data .= "<BR />";
											}
										}
									}
								}
								
								$db->setQuery("SELECT * FROM #__app_sch_services WHERE id = '$sid'");
								$service = $db->loadObject();
								
								$db->setQuery("Select a.*,b.additional_price from #__app_sch_employee as a inner join #__app_sch_employee_service as b on a.id = b.employee_id where a.id = '$eid' and b.service_id = '$sid'");
								$employee = $db->loadObject();
								
								//get extra cost
								$db->setQuery("Select * from #__app_sch_employee_extra_cost where eid = '$eid' and (week_date = '$date_in_week' or week_date = '0')");
								//echo $db->getQuery();
								$extras = $db->loadObjectList();
								$extra_cost = 0;
								if(count($extras) > 0){
									for($j=0;$j<count($extras);$j++){
										$extra = $extras[$j];
										$stime = $extra->start_time;
										$etime = $extra->end_time;
										$stime = date("Y-m-d",$start_booking_date)." ".$stime.":00";
										$etime = date("Y-m-d",$start_booking_date)." ".$etime.":00";
										$stime = strtotime($stime);
										$etime = strtotime($etime);
										if(($start_booking_date >= $stime) and ($start_booking_date <= $etime)){
											$extra_cost += $extra->extra_cost;
										}
									}
								}
								//echo $extra_cost;
								?>
								<tr>
									<td width="100%" style="padding:0px;border-bottom:1px dotted #D0C5C5 !important;padding-top:5px;">
										<table width="100%" style="border:0px !important;">
											<tr>
												<td width="100%" style="padding:0px;">
													<table  width="100%" style="border:0px !important;">
														<tr>
															<td width="100%" align="left" style="padding:0px;font-size:11px;color:gray;" colspan="4">
																<a href="javascript:openDiv(<?php echo $i1;?>)" title="<?php echo JText::_('OS_CLICK_FOR_MORE_DETAILS');?>" id="href_<?php echo $i1?>">
																[+]
																</a>
																<b>
																	<?php echo OSBHelper::getLanguageFieldValue($service,'service_name');?>
																</b>
															</td>
														</tr>
														<tr>
															<td width="25%" align="left" style="padding:0px;font-size:11px;color:gray;">
																<?php
																	echo date($configClass['date_format'],$start_booking_date);
																?>
															</td>
															<td width="20%" align="left" style="padding:0px;font-size:11px;color:gray;">
																<?php
																	echo date($configClass['time_format'],$start_booking_date);
																?>
															</td>
															<td width="20%" align="left" style="padding:0px;font-size:11px;color:gray;">
																<?php
																	echo date($configClass['time_format'],$end_booking_date);
																?>
															</td>
															<?php
															if($configClass['disable_payments'] == 1){
															?>
															<td width="25%" align="left" style="padding:0px;font-size:11px;color:gray;">
																<?php
																	echo OSBHelper::showMoney(OSBHelper::returnServicePrice($service->id,date("Y-m-d",$start_booking_date),$row->nslots) + $employee->additional_price + $extra_cost + $field_amount,0);
																	if($service->service_time_type == 1){
																		echo "x".$row->nslots;
																		$nslot = $row->nslots;
																	}else{
																		$nslot = 1;
																	}
																	$total += (OSBHelper::returnServicePrice($service->id,date("Y-m-d",$start_booking_date),$nslot) + $employee->additional_price + $extra_cost + $field_amount)*$nslot;
																?>
															</td>
															<?php
															}
															?>
															<?php if ($task  != "form_step2"){?>
															<td width="5%" align="center" style="padding:0px;">
																<a href="javascript:removeItem(<?php echo $order_item_id?>,<?php echo $sid?>,<?php echo $start_booking_date?>,<?php echo $end_booking_date?>,<?php echo $eid?>);" title="<?php echo JText::_('OS_REMOVE_ITEM');?>" class="applink">
																	<img src="<?php echo JURI::root()?>components/com_osservicesbooking/style/images/icon-16-deny.png" border="0">
																</a>
															</td>
															<?php }?>
														</tr>
														<?php
														$user = JFactory::getUser();
														if(($configClass['allow_multiple_timezones'] == 1) and ($user->id > 0) and (OSBHelper::getConfigTimeZone() != OSBHelper::getUserTimeZone())){
															?>
															<tr>
																<td width="25%" align="left" style="padding:0px;font-size:10px;color:red;">
																	<?php
																	$usertimezone =  OSBHelper::getUserTimeZone();
																	$usertimezone = explode("/",$usertimezone);
																	echo $usertimezone[0].'/';
																	echo "<BR />";
																	echo $usertimezone[1];
																	?>
																	
																</td>
																<td width="20%" align="left" style="padding:0px;font-size:11px;color:red;" valign="top">
																	<?php
																		echo date($configClass['time_format'],OSBHelper::convertTimezone($start_booking_date));
																	?>
																</td>
																<td width="20%" align="left" style="padding:0px;font-size:11px;color:red;" valign="top">
																	<?php
																		echo date($configClass['time_format'],OSBHelper::convertTimezone($end_booking_date));
																	?>
																</td>
																<?php
																if($configClass['disable_payments'] == 1){
																?>
																<td width="25%" align="left" style="padding:0px;font-size:11px;color:gray;">
																	
																</td>
																<?php
																}
																?>
																<td width="5%" align="center" style="padding:0px;">
																</td>
															</tr>
															<?php
														}
														?>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td style="padding:0px;background-color:#efefef;font-size:11px;">
										<div style="padding:2px;border-bottom:1px dotted #D0C5C5 !important;display:none;" id="cartdetails_<?php echo $i1?>"  >
										<?php echo JText::_('OS_EMPLOYEE')?>:
										<B>
										<?php
										echo $employee->employee_name
										?>
										</B>
										<br />
										<?php
										echo $field_data;
										?>
										</div>
									</td>
								</tr>
								<?php
							}
						}
						?>
						<?php
						if(($configClass['show_tax_in_cart'] == 1) and ($configClass['enable_tax'] == 1) and ($configClass['tax_payment'] > 0)){
							$tax_amount = $total*$configClass['tax_payment']/100;
							$total += $tax_amount;
							?>
							<tr>
								<td align="right" style="padding-top:5px;">
									<b><?php echo JText::_('OS_TAX')?>:</b>
									 <?php
										echo OSBHelper::showMoney($tax_amount,1);
									 ?>
								</td>
							</tr>
							<?php
						}
						if($configClass['disable_payments'] == 1){
						?>
						<tr>
							<td align="right" style="padding-top:5px;">
								<b><?php echo JText::_('OS_TOTAL')?>:</b>
								 <?php
									echo OSBHelper::showMoney($total,1);
								 ?>
							</td>
						</tr>
						<?php
						}
						?>
						<tr>
						 	<td align="left" style="padding-top:5px;">
						 		<?php
						 		if($configClass['use_ssl'] == 1){
						 		?>
								<a href="<?php echo $configClass['root_link'];?>index.php?option=com_osservicesbooking&task=form_step1&category_id=<?php echo Jrequest::getInt('category_id',0);?>&employee_id=<?php echo Jrequest::getInt('employee_id',0);?>&vid=<?php echo Jrequest::getInt('vid',0)?>&sid=<?php echo Jrequest::getInt('sid',0)?>&date_from=<?php echo $date_from?>&date_to=<?php echo $date_to.'&Itemid='.JRequest::getInt('Itemid',0);?>" title="<?php echo JText::_('OS_CHECKOUT');?>" class="btn">
								<?php
						 		}else{
						 		?>
								<a href="<?php echo JRoute::_("index.php?option=com_osservicesbooking&task=form_step1&category_id=".Jrequest::getInt('category_id',0)."&employee_id=".Jrequest::getInt('employee_id',0)."&vid=".Jrequest::getInt('vid',0)."&sid=".Jrequest::getInt('sid',0)."&date_from=".$date_from."&date_to=".$date_to);?>" title="<?php echo JText::_('OS_CHECKOUT');?>" class="btn">
								<?php
						 		}
						 		?>
									<i class="icon-checkedout"></i>
									<?php echo JText::_('OS_CHECKOUT');?>
								</a>
							</td>
						</tr>
						</table>
						<?php
					}else{
						echo JText::_('OS_YOUR_CART_IS_EMPTY');
					}
				}else{
					echo JText::_('OS_YOUR_CART_IS_EMPTY');
				}
			}else{
				echo JText::_('OS_YOUR_CART_IS_EMPTY');
			}
			?>
			
		<?php
	}
	
	/**
	 * Cart in the top of main page
	 *
	 * @param unknown_type $userdata
	 */
	function cart1($userdata){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		//$userdata 			= $_COOKIE['userdata'];
		if(trim($userdata) != ""){
			$userdata			= explode("||",$userdata);
			$total = 0;
			?>
			<table  width="100%">
			<tr>
				<td width="45%" align="left" style="font-size:11px;color:gray;border-bottom:1px dotted #D0C5C5 !important;font-weight:bold;">
					<?php echo JText::_('OS_SERVICE_NAME')?>
				</td>
				<td width="15%" align="left" style="font-size:11px;color:gray;border-bottom:1px dotted #D0C5C5 !important;font-weight:bold;">
					<?php echo JText::_('OS_BOOKING_DATE')?>
				</td>
				<td width="10%" align="left" style="font-size:11px;color:gray;border-bottom:1px dotted #D0C5C5 !important;font-weight:bold;">
					<?php echo JText::_('OS_START_TIME')?>
				</td>
				<td width="10%" align="left" style="font-size:11px;color:gray;border-bottom:1px dotted #D0C5C5 !important;font-weight:bold;">
					<?php echo JText::_('OS_END_TIME')?>
				</td>
				<td width="10%" align="left" style="font-size:11px;color:gray;border-bottom:1px dotted #D0C5C5 !important;font-weight:bold;">
					<?php echo JText::_('OS_PRICE')?>
				</td>
				<td width="10%" align="center" style="font-size:11px;color:gray;border-bottom:1px dotted #D0C5C5 !important;font-weight:bold;">
					<?php echo JText::_('OS_REMOVE')?>
				</td>
			</tr>
			<?php
			for($i=0;$i<count($userdata);$i++){
				$data = $userdata[$i];
				$data = explode("|",$data);
				$sid  = $data[0];
				if($sid > 0){
					$start_booking_date = $data[1];
					$end_booking_date   = $data[2];
					$eid				= $data[3];
					$add				= $data[4];
					$week_date			= date("N",$start_booking_date);
					$db->setQuery("SELECT * FROM #__app_sch_services WHERE id = '$sid'");
					$service = $db->loadObject();
					
					$db->setQuery("Select a.*,b.additional_price from #__app_sch_employee as a inner join #__app_sch_employee_service as b on a.id = b.employee_id where a.id = '$eid' and b.service_id = '$sid'");
					$employee = $db->loadObject();
					
					//get extra cost
					$db->setQuery("Select * from #__app_sch_employee_extra_cost where eid = '$eid' and (week_date = '$week_date' or week_date = '0')");
					//echo $db->getQuery();
					$extras = $db->loadObjectList();
					$extra_cost = 0;
					if(count($extras) > 0){
						for($j=0;$j<count($extras);$j++){
							$extra = $extras[$j];
							$stime = $extra->start_time;
							$etime = $extra->end_time;
							$stime = date("Y-m-d",$start_booking_date)." ".$stime.":00";
							$etime = date("Y-m-d",$start_booking_date)." ".$etime.":00";
							$stime = strtotime($stime);
							$etime = strtotime($etime);
							if(($start_booking_date >= $stime) and ($start_booking_date <= $etime)){
								$extra_cost += $extra->extra_cost;
							}
						}
					}
					?>
					<tr>
						<td width="45%" align="left" style="font-size:11px;color:gray;border-bottom:1px dotted #D0C5C5 !important;">
							<b><?php echo $service->service_name;?></b>
						</td>
						<td width="15%" align="left" style="font-size:11px;color:gray;border-bottom:1px dotted #D0C5C5 !important;">
							<?php
								echo intval(date("d",$start_booking_date))."/".intval(date("m",$start_booking_date))."/".intval(date("Y",$start_booking_date));
							?>
						</td>
						<td width="10%" align="left" style="font-size:11px;color:gray;border-bottom:1px dotted #D0C5C5 !important;">
							<?php
								echo date("H:i",$start_booking_date);
							?>
						</td>
						<td width="10%" align="left" style="font-size:11px;color:gray;border-bottom:1px dotted #D0C5C5 !important;">
							<?php
								echo date("H:i",$end_booking_date);
							?>
						</td>
						<td width="10%" align="left" style="font-size:11px;color:gray;border-bottom:1px dotted #D0C5C5 !important;">
							<?php
								echo OSBHelper::showMoney(OSBHelper::returnServicePrice($service->id,date("Y-m-d",$start_booking_date)) + $employee->additional_price + $extra_cost,0);
								$total += OSBHelper::returnServicePrice($service->id,date("Y-m-d",$start_booking_date)) + $employee->additional_price + $extra_cost;
							?>
						</td>
						<td width="10%" align="center" style="border-bottom:1px solid #D0C5C5 !important;">
							<a href="javascript:removeItem(<?php echo $sid?>,<?php echo $start_booking_date?>,<?php echo $end_booking_date?>,<?php echo $eid?>);" title="Remove item" class="applink">
								<img src="<?php echo JURI::root()?>components/com_osservicesbooking/style/images/icon-16-deny.png" border="0">
							</a>
						</td>
					</tr>
					<tr>
						<td colspan="6" style="padding:3px;background-color:#efefef;font-size:11px;">
							<?php echo JText::_('OS_EMPLOYEE')?>:
							<B>
							<?php
							echo $employee->employee_name
							?>
							</B>
							<br />
							<?php
							echo $add;
							?>
						</td>
					</tr>
					<?php
				}
			}
			?>
			<tr>
				<td align="right" style="padding-top:5px;font-size:11px;color:gray;" colspan="6">
					<b><?php echo JText::_('OS_TOTAL')?>:</b>
					<?php
						echo OSBHelper::showMoney($total,1);
					 ?>
				</td>
			</tr>
			<tr>
			 	<td align="left" style="padding-top:5px;">
					<a href="javascript:showInforForm()">
						<img src="<?php echo JURI::root()?>components/com_osservicesbooking/style/images/continue.png">
					</a>
				</td>
			</tr>
			</table>
			<?php
		}else{
			echo JText::_('OS_YOUR_CART_IS_EMPTY');
		}
	}
	
	/**
	 * Show information form
	 *
	 * @param unknown_type $option
	 */
	static function showInforForm($option){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$countryArr[] = JHTML::_('select.option','','');
		$db->setQuery("Select country_name as value, country_name as text from #__app_sch_countries order by country_name");
		$countries = $db->loadObjectList();
		$countryArr = array_merge($countryArr,$countries);
		$lists['country'] = JHTML::_('select.genericlist',$countryArr,'order_country','style="width:180px;" class="inputbox"','value','text');
		$db->setQuery("Select * from #__app_sch_fields where field_area = '1' and published = '1' order by ordering");
		$fields = $db->loadObjectList();
		if($configClass['disable_payments']  == 1){
			$paymentMethod = JRequest::getVar('payment_method', os_payments::getDefautPaymentMethod(), 'post');	
			if (!$paymentMethod)
			    $paymentMethod = os_payments::getDefautPaymentMethod();
			
			###############Payment Methods parameters###############################
		
			//Creditcard payment parameters		
			$x_card_num = JRequest::getVar('x_card_num', '', 'post');
			$expMonth =  JRequest::getVar('exp_month', date('m'), 'post') ;				
			$expYear = JRequest::getVar('exp_year', date('Y'), 'post') ;		
			$x_card_code = JRequest::getVar('x_card_code', '', 'post');
			$cardHolderName = JRequest::getVar('card_holder_name', '', 'post') ;
			$lists['exp_month'] = JHTML::_('select.integerlist', 1, 12, 1, 'exp_month', ' id="exp_month" ', $expMonth, '%02d') ;
			$currentYear = date('Y') ;
			$lists['exp_year'] = JHTML::_('select.integerlist', $currentYear, $currentYear + 10 , 1, 'exp_year', ' id="exp_year" ', $expYear) ;
			$options =  array() ;
			$cardTypes = explode(',', $configClass['enable_cardtypes']);
			if (in_array('Visa', $cardTypes)) {
				$options[] = JHTML::_('select.option', 'Visa', JText::_('OS_VISA_CARD')) ;			
			}
			if (in_array('MasterCard', $cardTypes)) {
				$options[] = JHTML::_('select.option', 'MasterCard', JText::_('OS_MASTER_CARD')) ;
			}
			
			if (in_array('Discover', $cardTypes)) {
				$options[] = JHTML::_('select.option', 'Discover', JText::_('OS_DISCOVER')) ;
			}		
			if (in_array('Amex', $cardTypes)) {
				$options[] = JHTML::_('select.option', 'Amex', JText::_('OS_AMEX')) ;
			}		
			$lists['card_type'] = JHTML::_('select.genericlist', $options, 'card_type', ' class="inputbox" ', 'value', 'text') ;
			//Echeck
					
			$x_bank_aba_code = JRequest::getVar('x_bank_aba_code', '', 'post') ;
			$x_bank_acct_num = JRequest::getVar('x_bank_acct_num', '', 'post') ;
			$x_bank_name = JRequest::getVar('x_bank_name', '', 'post') ;
			$x_bank_acct_name = JRequest::getVar('x_bank_acct_name', '', 'post') ;				
			$options = array() ;
			$options[] = JHTML::_('select.option', 'CHECKING', JText::_('OS_BANK_TYPE_CHECKING')) ;
			$options[] = JHTML::_('select.option', 'BUSINESSCHECKING', JText::_('OS_BANK_TYPE_BUSINESSCHECKING')) ;
			$options[] = JHTML::_('select.option', 'SAVINGS', JText::_('OS_BANK_TYPE_SAVING')) ;
			$lists['x_bank_acct_type'] = JHTML::_('select.genericlist', $options, 'x_bank_acct_type', ' class="inputbox" ', 'value', 'text', JRequest::getVar('x_bank_acct_type')) ;
			
			$methods = os_payments::getPaymentMethods(true, $onlyRecurring) ;
			
			$lists['x_card_num'] = $x_card_num;
			$lists['x_card_code'] = $x_card_code;
			$lists['cardHolderName'] = $cardHolderName;
			$lists['x_bank_acct_num'] = $x_bank_acct_num;
			$lists['x_bank_acct_name'] = $x_bank_acct_name;
			$lists['methods'] = $methods;
			$lists['idealEnabled'] = $idealEnabled;
			$lists['paymentMethod'] = $paymentMethod;
		}
		HTML_OsAppscheduleAjax::showInforFormHTML($option,$lists,$fields);
	}
	
	
	/**
	 * Confirm information
	 *
	 * @param unknown_type $option
	 */
	static function confirmInforForm($option){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$userdata = $_COOKIE['userdata'];
		$tax = $configClass['tax_payment'];
		$total = 0;
		$total = OsAppscheduleAjax::getOrderCost($userdata);
		$fieldObj = array();
		$fields = JRequest::getVar('fields','');
		$fieldArr = explode("||",$fields);
		if(count($fieldArr) > 0){
			$field_amount = 0;
			for($i=0;$i<count($fieldArr);$i++){
				$field_data = "";
				$field  = $fieldArr[$i];
				$fArr   = explode("|",$field);
				$fid    = $fArr[0];
				$fvalue = $fArr[1];
				$fvalue = str_replace("(@)","&",$fvalue);
				$db->setQuery("Select * from #__app_sch_fields where id = '$fid'");
				$field 	= $db->loadObject();
				$field_type = $field->field_type;
				if($field_type == 1){
					$db->setQuery("Select * from #__app_sch_field_options where id = '$fvalue'");
					$fieldOption = $db->loadObject();
					if($fieldOption->additional_price > 0){
						$field_amount += $fieldOption->additional_price;
					}
					$field_data .= $fieldOption->field_option;
					if($fieldOption->additional_price > 0){
						$field_data.= " - ".$fieldOption->additional_price." ".$configClass['currency_format'];
					}
				}elseif($field_type == 2){
					$fieldValueArr = explode(",",$fvalue);
					if(count($fieldValueArr) > 0){
						for($j=0;$j<count($fieldValueArr);$j++){
							$temp = $fieldValueArr[$j];
							$db->setQuery("Select * from #__app_sch_field_options where id = '$temp'");
							$fieldOption = $db->loadObject();
							if($fieldOption->additional_price > 0){
								$field_amount += $fieldOption->additional_price;
							}
							$field_data .= $fieldOption->field_option;
							if($fieldOption->additional_price > 0){
								$field_data.= " - ".$fieldOption->additional_price." ".$configClass['currency_format'];
							}
							$field_data .= ",";
						}
						$field_data = substr($field_data,0,strlen($field_data)-1);
					}
				}
				
				$count	= count($fieldObj);
				$fieldObj[$count]->field = $field;
				$fieldObj[$count]->fvalue = $field_data;
				$fieldObj[$count]->fieldoptions = $fvalue;
			}
		}
		$total += $field_amount;
		
		if($configClass['disable_payments'] == 1){
			$select_payment 	= JRequest::getVar('select_payment','');
			if($select_payment !=  ""){
				$method = os_payments::getPaymentMethod($select_payment) ;
				$x_card_num			= JRequest::getVar('x_card_num','');
				$x_card_code		= JRequest::getVar('x_card_code','');
				$card_holder_name	= JRequest::getVar('card_holder_name','');
				$exp_year			= JRequest::getVar('exp_year','');
				$exp_month			= JRequest::getVar('exp_month','');
				$card_type			= JRequest::getVar('card_type','');
				$lists['method'] 			= $method;
				$lists['x_card_num'] 		= $x_card_num;
				$lists['x_card_code'] 		= $x_card_code;
				$lists['card_holder_name'] 	= $card_holder_name;
				$lists['exp_year'] 			= $exp_year;
				$lists['exp_month'] 		= $exp_month;
				$lists['card_type'] 		= $card_type;
				$lists['select_payment']	= $select_payment;
			}
		}
		
		
		HTML_OsAppscheduleAjax::confirmInforFormHTML($option,$total,$fieldObj,$lists);
	}

	static function isAnyItemsInCart(){
		$db = JFactory::getDbo();
		$unique_cookie = OSBHelper::getUniqueCookie();//$_COOKIE['unique_cookie'];
		$db->setQuery("SELECT count(id) FROM #__app_sch_temp_orders WHERE unique_cookie like '$unique_cookie'");
		$count_order = $db->loadResult();
		
		if($count_order == 0){
			$unique_cookie = JRequest::getVar('unique_cookie','');
			$db->setQuery("SELECT count(id) FROM #__app_sch_temp_orders WHERE unique_cookie like '$unique_cookie'");
			$count_order = $db->loadResult();
		}
		
		if($count_order > 0){
			$db->setQuery("SELECT id FROM #__app_sch_temp_orders WHERE unique_cookie like '$unique_cookie'");
			$order_id = $db->loadResult();
			$db->setQuery("SELECT * FROM #__app_sch_temp_order_items WHERE order_id = '$order_id'");
			$rows = $db->loadObjectList();
		}
		if(count($rows) > 0){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * Get order cost
	 *
	 * @param unknown_type $userdata
	 */
	static function getOrderCost(){
		global $mainframe;
		$db = JFactory::getDbo();
		$total = 0;
		//$unique_cookie = $_COOKIE['unique_cookie'];
		$unique_cookie = OSBHelper::getUniqueCookie();
		$db->setQuery("SELECT count(id) FROM #__app_sch_temp_orders WHERE unique_cookie like '$unique_cookie'");
		
		$count_order = $db->loadResult();
		if($count_order == 0){
			$unique_cookie = JRequest::getVar('unique_cookie','');
			$db->setQuery("SELECT count(id) FROM #__app_sch_temp_orders WHERE unique_cookie like '$unique_cookie'");
			$count_order = $db->loadResult();
		}
		if($count_order > 0){
			
			$db->setQuery("SELECT id FROM #__app_sch_temp_orders WHERE unique_cookie like '$unique_cookie'");
			$order_id = $db->loadResult();
			$db->setQuery("SELECT * FROM #__app_sch_temp_order_items WHERE order_id = '$order_id'");
			$rows = $db->loadObjectList();
			
			for($i1=0;$i1<count($rows);$i1++){
				$row = $rows[$i1];
				$order_item_id = $row->id;
				$sid = $row->sid;
				$eid = $row->eid;
				$start_booking_date = $row->start_time;
				$week_date = date("N",$start_booking_date);
				//get extra cost				
				$db->setQuery("Select additional_price from #__app_sch_employee_service where employee_id = '$eid' and service_id = '$sid'");
				$additional_price = $db->loadResult();
				$db->setQuery("Select service_price,service_time_type from #__app_sch_services where id = '$sid'");
				$service = $db->loadObject();
				$service_price = OSBHelper::returnServicePrice($sid,date("Y-m-d",$start_booking_date),$row->nslots);
				//$service_price+= $extra_cost;
				$service_time_type = $service->service_time_type;
				if($service_time_type == 1){
					$service_price = $service_price*$row->nslots;
				}
				
				//get extra cost
				
				$db->setQuery("Select * from #__app_sch_employee_extra_cost where eid = '$eid' and (week_date = '$week_date' or week_date = '0')");
				//echo $db->getQuery();
				$extras = $db->loadObjectList();
				$extra_cost = 0;
				if(count($extras) > 0){
					for($j=0;$j<count($extras);$j++){
						$extra = $extras[$j];
						$stime = $extra->start_time;
						$etime = $extra->end_time;
						$stime = date("Y-m-d",$start_booking_date)." ".$stime.":00";
						$etime = date("Y-m-d",$start_booking_date)." ".$etime.":00";
						$stime = strtotime($stime);
						$etime = strtotime($etime);
						if(($start_booking_date >= $stime) and ($start_booking_date <= $etime)){
							$extra_cost += $extra->extra_cost;
						}
					}
				}
				
				//$add				= $data[4];
				//calculate option value and additional price
				$db->setQuery("SELECT field_id FROM #__app_sch_temp_order_field_options WHERE order_item_id = '$order_item_id' GROUP BY field_id");
				$fields = $db->loadObjectList();
				//calculate option value and additional price
				if(count($fields) > 0){
					$field_amount = 0;
					$field_data   = "";
					for($k=0;$k<count($fields);$k++){
						$field = $fields[$k];
						$fieldid = $field->field_id;
						$db->setQuery("Select id,field_type from #__app_sch_fields where  id = '$fieldid'");
						$field = $db->loadObject();
						$field_type = $field->field_type;
						if($field_type == 1){
							$db->setQuery("Select a.additional_price from #__app_sch_field_options as a inner join #__app_sch_temp_order_field_options as b on a.id = b.option_id where b.field_id = '$fieldid' and b.order_item_id = '$order_item_id'");
							$additional_price_fields = $db->loadResult();
							
							if($additional_price_fields > 0){
								$field_amount += $additional_price_fields;
							}
						}elseif($field_type == 2){
							$db->setQuery("Select option_id from #__app_sch_temp_order_field_options where order_item_id = '$order_item_id' and field_id = '$fieldid'");
							$optionids = $db->loadObjectList();
							if(count($optionids) > 0){
								for($j=0;$j<count($optionids);$j++){
									$temp = $optionids[$j]->option_id;
									$db->setQuery("Select additional_price from #__app_sch_field_options where id = '$temp'");
									$additional_price_fields = $db->loadResult();
									if($additional_price_fields > 0){
										$field_amount += $additional_price_fields;
									}
								}
							}
						}
					}
				}
				$total += $service_price + $additional_price + $extra_cost +$field_amount;
			}
		}
		return $total;
	}
	
	/**
	 * Captcha generetor
	 *
	 * @param unknown_type $option
	 */
	static function captcha($option){
		global $mainframe;
		while (@ob_end_clean());
		$ResultStr = JRequest::getVar('resultStr');
		$NewImage =imagecreatefromjpeg(JPATH_ROOT.DS."components".DS."com_osservicesbooking".DS."style".DS."images".DS."img.jpg");//image create by existing image and as back ground 
		$LineColor = imagecolorallocate($NewImage,233,239,239);//line color 
		$TextColor = imagecolorallocate($NewImage, 255, 255, 255);//text color-white
		imageline($NewImage,1,1,40,40,$LineColor);//create line 1 on image 
		imageline($NewImage,1,100,60,0,$LineColor);//create line 2 on image 
		imagestring($NewImage, 5, 20, 10, $ResultStr, $TextColor);// Draw a random string horizontally 
		header("Content-type: image/jpeg");// out out the image 
		imagejpeg($NewImage);//Output image to browser 
		exit();
	}
	
	static function updatenSlots($option){
		global $mainframe;
		$db = JFactory::getDbo();
		//$unique_cookie = $_COOKIE['unique_cookie'];
		$unique_cookie = OSBHelper::getUniqueCookie();
		$sid = Jrequest::getVar('sid',0);
		$eid = Jrequest::getVar('eid',0);
		$start_time = Jrequest::getVar('start_time',0);
		$end_time = Jrequest::getVar('end_time',0);
		$newvalue = Jrequest::getVar('newvalue',0);
		$db->setQuery("UPDATE #__app_sch_temp_temp_order_items SET nslots = '$newvalue' WHERE unique_cookie LIKE '$unique_cookie' AND sid = '$sid' AND eid = '$eid' AND start_time  = '$start_time' AND end_time = '$end_time'");
		$db->query();
		exit();
	}
	
	/**
	 * remove time slots from cart
	 *
	 * @param unknown_type $option
	 */
	function removeTemporarityTimeSlot($option){
		global $mainframe;
		$db = JFactory::getDbo();
		//$unique_cookie = $_COOKIE['unique_cookie'];
		$unique_cookie = OSBHelper::getUniqueCookie();
		$id = Jrequest::getVar('id',0);
		if($id > 0){
			$db->setQuery("SELECT * FROM #__app_sch_temp_temp_order_items where id = '$id'");
			$row = $db->loadObject();
			$db->setQuery("DELETE FROM #__app_sch_temp_temp_order_items WHERE id = '$id'");
			$db->query();
		}
		OsAppscheduleAjax::checkingErrorinCart($row->sid,$row->eid);
		exit();
	}
	
	/**
	 * Check error in adding time slots to cart. 
	 * Call in the show error page and in ajax loading (from error page)
	 *
	 */
	function checkingErrorinCart($sid,$eid){
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__app_sch_services where id = '$sid'");
		$service = $db->loadObject();
		$service_time_type = $service->service_time_type;
		//$unique_cookie =  $_COOKIE['unique_cookie'];
		$unique_cookie = OSBHelper::getUniqueCookie();
		$db->setQuery("Select * from #__app_sch_temp_temp_order_items where unique_cookie like '$unique_cookie'");
		//echo $db->getQuery();
		$rows = $db->loadObjectList();
		if(count($rows) > 0){
			$errorArr = array();
			if($service_time_type == 1){
				for($i=0;$i<count($rows);$i++){
					$row = $rows[$i];
					//check number of slots. 
					if(!HelperOSappscheduleCalendar::checkSlots($row)){
						$canbook = 0;
						$errorArr[count($errorArr)] = HelperOSappscheduleCalendar::returnSlots($row);
					}
				}
			}else{
				for($i=0;$i<count($rows);$i++){
					$row = $rows[$i];
					//check number of slots. 
					if(!HelperOSappscheduleCalendar::checkSlots($row)){
						$canbook = 0;
						$errorArr[count($errorArr)] = $row;
					}
				}
			}
		}
		OSappscheduleInformation::showError($sid,$eid,$errorArr);
	}
	
	/**
	 * Remove rest date
	 *
	 */
	static function removerestdayAjax(){
		global $mainframe;
		$day = Jrequest::getVar('day','');
		$eid = Jrequest::getVar('eid',0);
		$db  = JFactory::getDbo();
		$i   = Jrequest::getVar('item');
		$db->setQuery("DELETE FROM #__app_sch_employee_rest_days WHERE eid = '$eid' AND rest_date <= '$day' and rest_date_to >= '$day'");
		$db->query();
		OSBHelper::calendarItemAjax($i,$eid,$day);
		exit();
	}
	
	static function addrestdayAjax(){
		global $mainframe;
		$db  = JFactory::getDbo();
		$day = Jrequest::getVar('day','');
		$eid = Jrequest::getVar('eid',0);
		$i   = Jrequest::getVar('item');
		$db->setQuery("INSERT INTO #__app_sch_employee_rest_days (id,eid,rest_date,rest_date_to) VALUES  (NULL,'$eid','$day','$day')");
		$db->query();
		OSBHelper::calendarItemAjax($i,$eid,$day);
		exit();
	}
	
	/**
	 * Remove order item
	 *
	 */
	static function removeOrderItemAjax(){
		global $mainframe;
		$db = JFactory::getDbo();
		$order_id = Jrequest::getVar('order_id',0);
		$id = Jrequest::getVar('id',0);
		$db->setQuery("Select eid from #__app_sch_order_items where id = '$id'");
		$eid = $db->loadResult();
		$db->setQuery("DELETE FROM #__app_sch_order_field_options WHERE order_item_id = '$id'");
		$db->query();
		$db->setQuery("DELETE FROM #__app_sch_order_items WHERE id = '$id'");
		$db->query();
		HelperOSappscheduleCommon::sendEmployeeEmail('employee_order_cancelled',$order_id,$eid);
		OsAppscheduleDefault::getListOrderServices($order_id);
		exit();
	}
	
	static function removeOrderItemAjaxCalendar(){
		global $mainframe;
		$db = JFactory::getDbo();
		$i = Jrequest::getVar('i',0);
		$date = Jrequest::getVar('date','');
		$order_id = Jrequest::getVar('order_id',0);
		$id = Jrequest::getVar('id',0);
		$db->setQuery("Select eid from #__app_sch_order_items where id = '$id'");
		$eid = $db->loadResult();
		$db->setQuery("DELETE FROM #__app_sch_order_field_options WHERE order_item_id = '$id'");
		$db->query();
		$db->setQuery("DELETE FROM #__app_sch_order_items WHERE id = '$id'");
		$db->query();
		HelperOSappscheduleCommon::sendEmployeeEmail('employee_order_cancelled',$order_id,$eid);
		OSBHelper::calendarCustomerItemAjax($i,$date);
		exit();
	}
	
	/**
	 * Check coupon code
	 *
	 */
	static function checkCouponCode(){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$current_date = HelperOSappscheduleCommon::getRealTime();
		$current_date = date("Y-m-d H:i:s",$current_date);
		$coupon_code = JRequest::getVar('coupon_code','');
		$db->setQuery("Select count(id) from #__app_sch_coupons where published = '1' and coupon_code like '$coupon_code' and ((expiry_date = '' or expiry_date = '0000-00-00 00:00:00') or (expiry_date <> '' and expiry_date > '$current_date'))");
		$isCorrectCoupon = $db->loadResult();
		if($isCorrectCoupon > 0){
			//get the coupon
			$db->setQuery("Select * from #__app_sch_coupons where published = '1' and coupon_code like '$coupon_code' and ((expiry_date = '' or expiry_date = '0000-00-00 00:00:00') or (expiry_date <> '' and expiry_date > '$current_date'))");
			$coupon = $db->loadObject();
			if($coupon->max_total_use > 0){
				$db->setQuery("Select count(id) from #__app_sch_coupon_used where coupon_id = '$coupon->id'");
				$nused = $db->loadResult();
				if($nused >= $coupon->max_total_use){
					$useCoupon = 9999;
				}
			}
			if($useCoupon != 9999){
				//check user
				$user = JFactory::getUser();
				$user_id = $user->id;
				if(($user_id > 0) and ($coupon->max_user_use > 0)){
					$db->setQuery("Select count(id) from #__app_sch_coupon_used where user_id = '$user_id' and coupon_id = '$coupon->id'");
					$alreadyUsedCoupon = $db->loadResult();
					if($alreadyUsedCoupon >= $coupon->max_user_use){
						$useCoupon = 9999;
					}else{
						$useCoupon = $coupon->id;
					}
				}else{
					$useCoupon = $coupon->id;
				}
			}
		}else{
			$useCoupon = 0;
		}
		
		echo "@return@";
		if(($useCoupon != 0) and ($useCoupon != 9999)){
			if(($coupon->discount == 100) and ($coupon->discount_type == 0)){
				echo $useCoupon."XXX1||";
			}else{
				echo $useCoupon."XXX0||";
			}
			?>
			<span style="color:green;font-weight:bold;">
				<?php echo JText::_('OS_CONGRATULATION');?>, <?php echo JText::_('OS_YOU_GET_THE_DISCOUNT')?> [<?php echo $coupon->coupon_name;?>] <?php echo JText::_('OS_WITH');?> <?php echo $coupon->discount?> 
				<?php 
				if($coupon->discount_type == 0){
					echo " ".JText::_('OS_PERCENT')." (%)";
					echo " ".JText::_('OS_OF_TOTAL_AMOUNT');
				}else{
					echo " ".$configClass['currency_format'];
					echo " ".JText::_('OS_DISCOUNT');
				}
				?>
			</span>
			<?php
		}elseif($useCoupon == 9999){
			echo "9999||";
			?>
			<span style="color:red;font-weight:bold;">
			<?php
			echo JText::_('OS_YOU_CANNOT_USE_THIS_COUPON_CODE_AGAIN');
			?>
			</span>
			<?php
		}else{
			echo "0||";
			?>
			<input type="text" class="input-small search-query" value="" size="10" name="coupon_code" id="coupon_code" />
			<input type="button" class="btn" value="<?php echo JText::_('OS_CHECK_COUPON');?>" onclick="javascript:checkCoupon();"/>
			<div class="clearfix"></div>
			<span style="color:red;font-weight:bold;">
				<?php
				echo JText::_('OS_COUPON_CODE_IS_NOT_CORRECT');
				?>
			</span>
			<?php
		}
	}
	
	static function changeTimeSlotDate(){
		global $mainframe;
		$db = JFactory::getDbo();
		$tstatus = JRequest::getInt('tstatus',0);
		$date	 = JRequest::getInt('date',0);
		$tid	 = JRequest::getInt('tid',0);
		$sid	 = JRequest::getInt('sid',0);
		if($tstatus == 0){
			$db->setQuery("Delete from #__app_sch_custom_time_slots_relation where time_slot_id = '$tid' and date_in_week = '$date'");
			$db->query();
			?>
			<a href="javascript:changeTimeSlotDate(1,<?php echo $date?>,<?php echo $sid?>,<?php echo $tid?>,'<?php echo JUri::root();?>');" title="Select this day">
				<img alt="Select this day" src="<?php echo JUri::root()?>components/com_osservicesbooking/asset/images/unpublish.png" border="0" />
			</a>
			<?php 
		}else{
			$db->setQuery("Insert into #__app_sch_custom_time_slots_relation (id,time_slot_id,date_in_week) values (NULL,'$tid','$date')");
			$db->query();
			?>
			<a href="javascript:changeTimeSlotDate(0,<?php echo $date?>,<?php echo $sid?>,<?php echo $tid?>,'<?php echo JUri::root();?>');" title="Select this day">
				<img alt="Select this day" src="<?php echo JUri::root()?>components/com_osservicesbooking/asset/images/publish.png" border="0" />
			</a>
			<?php 
		}
		exit();
	}


    static function loadCalendatDetails(){
        global $mainframe;$configClass;
        $month = JRequest::getInt('month',0);
        $year = Jrequest::getInt('year',0);
        $category_id = JRequest::getInt('category_id',0);
        $vid = JRequest::getInt('vid',0);
        $employee_id = Jrequest::getInt('employee_id',0);
        $date_from = Jrequest::getVar('date_from','');
        $date_to = Jrequest::getVar('date_to','');
        HelperOSappscheduleCalendar::initCalendar($year,$month,$category_id,$employee_id,$vid,$date_from,$date_to,1);
        exit();
    }

	static function checkingVersion(){
		global $mainframe;
		$current_version = JRequest::getVar('current_version','');
		if (function_exists('curl_init'))
		{
			$url = 'http://joomdonation.com/images/osservicesbooking/version.txt';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$latestVersion = curl_exec($ch);
			curl_close($ch);
			$latestVersion = trim($latestVersion);
			if ($latestVersion)
			{
				if (version_compare($latestVersion, $current_version, 'gt'))
				{
					?>
					<div class="icon"><a href='http://joomdonation.com/joomla-extensions/joomla-services-appointment-booking.html' target='_blank' title='Update latest OS Services Booking version'>
					<img src="<?php echo JUri::root();?>administrator/components/com_osservicesbooking/asset/images/noupdated.png" />
				
					<?php
					echo '<span style="color:red;">'.JText::sprintf('OS_UPDATE_CHECKING_UPDATE_FOUND', $latestVersion).'</span>';
					echo '</a>';
					echo '</div>';
				}
				else
				{
					?>
					<div class="icon"><a href='#'>
					<img src="<?php echo JUri::root();?>administrator/components/com_osservicesbooking/asset/images/updated.png" />
					<?php
					echo '<span style="color:green;">'.JText::_('OS_UPDATE_CHECKING_UP_TO_DATE').'</span>';
					echo '</a>';
					echo '</div>';
				}
			}
		}
		exit();
	}

    function changeCheckinOrderItem(){
        $db = JFactory::getDbo();
        $order_id = JRequest::getInt('order_id',0);
        $id = JRequest::getInt('id',0);
        $db->setQuery("Select checked_in from #__app_sch_order_items where id = '$id' and order_id = '$order_id'");
        $checked_in = $db->loadResult();
        ?>
        <a href="javascript:changeCheckin(<?php echo $row->order_id ?>,<?php echo $row->order_item_id ?>,'<?php echo JURI::root() ?>');" title="<?php echo JText::_('OS_CLICK_HERE_TO_CHANGE_CHECK_IN_STATUS'); ?>"/>
        <?php
        if($checked_in == 0){
            $db->setQuery("Update #__app_sch_order_items set checked_in = '1' where id = '$id'");
            $db->query();
            ?>
                <img src="<?php echo JURI::root() ?>administrator/components/com_osservicesbooking/asset/images/publish.png"/>
            <?php
        }else{
            $db->setQuery("Update #__app_sch_order_items set checked_in = '0' where id = '$id'");
            $db->query();
            ?>
                <img src="<?php echo JURI::root() ?>components/com_osservicesbooking/style/images/icon-16-deny.png"/>
            <?php
        }
        ?>
        </a>
        <?php
        exit();
    }
}

?>