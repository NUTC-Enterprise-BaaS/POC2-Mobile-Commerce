<?php
/*------------------------------------------------------------------------
# default.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2015 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// No direct access.
defined('_JEXEC') or die;

class OsAppscheduleDefault{
	/**
	 * Osproperty default
	 *
	 * @param unknown_type $option
	 */
	function display($option,$task){
		global $mainframe;
		$db = JFactory::getDbo();
		$config = new JConfig();
		$offset = $config->offset;
		date_default_timezone_set($offset);
		//remove the temporarily orders before 1 hour
		$current_time = time();
		$last_one_hour = $current_time - 3600;
		$db->setQuery("Select id from #__app_sch_temp_orders where created_on < '$last_one_hour'");
		//$db->query();
		$temp_ids = $db->loadColumn(0);
		if(count($temp_ids) > 0){
			$db->setQuery("Delete from #__app_sch_temp_orders where id in (".implode(",",$temp_ids).")");
			$db->query();
			$db->setQuery("Delete from #__app_sch_temp_order_items where order_id in (".implode(",",$temp_ids).")");
			$db->query();
		}
		$document = JFactory::getDocument();
		$order_id = JRequest::getVar('id',0);
		if($order_id == 0){
			$order_id = JRequest::getVar('order_id',0);	
		}
		switch ($task){
			default:
				OsAppscheduleDefault::defaultLayout($option);
			break;
			case "default_completeorder":
				OsAppscheduleDefault::completeOrder($option);
			break;
			case "default_payment":
				OsAppscheduleDefault::paymentProcess($option);
			break;
			case "defaul_paymentconfirm":
				OsAppscheduleDefault::paymentNotify();
			break;
			case "default_paymentcancel":
				OsAppscheduleDefault::cancelPayment($order_id);
			break;
			case "default_paymentreturn":
				OsAppscheduleDefault::returnPayment($order_id);
			break;
			case "default_paymentfailure":
				OsAppscheduleDefault::paymentFailure($order_id);
			break;
			case "default_cron":
				OsAppscheduleDefault::cron();
			break;
			case "default_cancelorder":
				OsAppscheduleDefault::cancelOrder();
			break;
			case "default_paymentComplete":
				$order_id = JRequest::getVar('order_id');
				OsAppscheduleDefault::paymentComplete($order_id);
			break;
			case "default_orderDetails":
				$eid = JRequest::getVar('eid',0);
				OsAppscheduleDefault::orderDetails($order_id,$eid);
			break;
			case "default_orderDetailsForm":
				$ref = JRequest::getVar('ref','');
				if(md5($order_id) != $ref){
					JError::raiseError( 404, JText::_('JERROR_LAYOUT_REQUESTED_RESOURCE_WAS_NOT_FOUND') );
				}
				OsAppscheduleDefault::orderDetailsForm($order_id);
			break;
			case "default_employeeworks":
				OsAppscheduleDefault::employeeWorks();
			break;
			case "default_orderDetails":
				$order_id = JRequest::getVar('order_id',0);
                $eid      = JRequest::getInt('eid',0);
				OsAppscheduleDefault::orderDetails($order_id,$eid);
			break;
			case "default_calculateBookingDate":
				HelperOSappscheduleCalendar::calculateBookingDate($from_date,$to_date,$type);
			break;
			case "default_failure":
				OsAppscheduleDefault::failure();
			break;
			case "default_customer":
				OsAppscheduleDefault::orderHistory();
			break;
			case "default_removeorder":
				OsAppscheduleDefault::removeOrder($order_id);
			break;
			case "default_showmap":
				OsAppscheduleDefault::showMap();
			break;
			//test//
			case "default_testrepeat":
				OsAppscheduleDefault::testRepeat();
			break;
			case "default_testdate":
				OsAppscheduleDefault::testDate();
			break;
			case "default_addEventToGCalendar":
				OsAppscheduleDefault::addEventToGCalendar();
			break;
			case "default_updateGoogleCalendar":
				OsAppscheduleDefault::updateGoogleCalendar($order_id);
			break;
			case "default_testsms":
				OsAppscheduleDefault::testSMS();
			break;
			case "default_allemployees":
				OsAppscheduleDefault::listAllEmployees();
			break;
			case "default_acymailing":
				HelperOSappscheduleCommon::updateAcyMailing(69);
			break;
            case "default_checkin":
                OsAppscheduleDefault::checkIn();
                break;
		}
	}
	
/**
	 * Default layout
	 *
	 * @param unknown_type $option
	 */
	function defaultLayout($option){
		global $mainframe,$configClass,$languages;
		$db = JFactory::getDbo();
		$category_id = JRequest::getInt('category_id',0);
		if($category_id > 0){
			$catSql = " and category_id = '$category_id' ";
			$db->setQuery("Select * from #__app_sch_categories where id = '$category_id'");
			$category = $db->loadObject();
		}else{
			$catSql = "";
		}
		
		$employee_id = Jrequest::getInt('employee_id',0);
		if($employee_id > 0){
			$employeeSql = " and id in (Select service_id from #__app_sch_employee_service where employee_id = '$employee_id')";
		}else{
			$employeeSql = "";
		}
		
		$vid = JRequest::getInt('vid',0);
		if($vid > 0){
			$vidSql = " and id in (Select sid from #__app_sch_venue_services where vid = '$vid')";
		}else{
			$vidSql = "";
		}
		
		$sid = JRequest::getInt('sid',0);
		if($sid > 0){
			$sidSql = " and id = '$sid'";
		}else{
			$sidSql = "";
		}
		
		$document = JFactory::getDocument();
		if($configClass['business_name'] != "") $document->setTitle($configClass['business_name']);
		
        $orig_metakey = $document->getMetaData('keywords');
        if( $configClass['meta_keyword'] != "" ) $document->setMetaData( "keywords", $configClass['meta_keyword'] );

        $orig_metadesc = $document->getMetaData('description');
        if( $configClass['meta_desc'] != "" ) $document->setMetaData( "description", $configClass['meta_desc'] );   
        
		
		$year = date("Y",HelperOSappscheduleCommon::getRealTime());
		$month = intval(date("m",HelperOSappscheduleCommon::getRealTime()));
		$day = intval(date("d",HelperOSappscheduleCommon::getRealTime()));
		
		$date_from = JRequest::getVar('date_from','');
		$date_to   = JRequest::getVar('date_to','');
		if($date_from != ""){
			$date_from_array = explode(" ",$date_from);
			$date_from_int = strtotime($date_from_array[0]);
			if($date_from_int > HelperOSappscheduleCommon::getRealTime()){
				$year = date("Y",$date_from_int);
				$month = intval(date("m",$date_from_int));
				$day = intval(date("d",$date_from_int));
			}
		}
		
		$db->setQuery("Select * from #__app_sch_services where published = '1' $sidSql $catSql $employeeSql $vidSql ".HelperOSappscheduleCommon::returnAccessSql('')." order by ordering");
		$services = $db->loadObjectList();
		
		$translatable = JLanguageMultilang::isEnabled() && count($languages);
		
		if(count($services) > 1){
			$optionArr = array();
			$optionArr[] = JHtml::_('select.option','0',JText::_('OS_SELECT_SERVICES'));
			foreach ($services as $service){
				if($translatable){
					$optionArr[] = JHtml::_('select.option',$service->id,OSBHelper::getLanguageFieldValue($service,'service_name'));
				}else{
					$optionArr[] = JHtml::_('select.option',$service->id,$service->service_name);
				}
			}
			$lists['services'] = JHtml::_('select.genericlist',$optionArr,'sid','class="input-large chosen"','value','text',$sid);
		}
		
		
		HTML_OsAppscheduleDefault::defaultLayoutHTML($option,$services,$year,$month,$day,$category,$employee_id,$vid,$sid,$date_from,$date_to,$lists);
	}
	
	
	/**
	 * Employee works
	 *
	 */
	function employeeWorks(){
		global $mainframe,$configClass;
		$config = new JConfig();
		$offset = $config->offset;
		date_default_timezone_set($offset);
		if(!HelperOSappscheduleCommon::checkEmployee()){
			$mainframe->redirect(JURI::root()."index.php",JText::_('OS_YOU_DONT_HAVE_PERMISSION_TO_GO_TO_THIS_AREA'));
		}
		$db = JFactory::getDbo();
		$date1 = JRequest::getVar('date1','');
		$date2 = JRequest::getVar('date2','');
		$date  = "";
		if($date1 != ""){
			$date .= " and a.booking_date >= '$date1'";
		}
		if($date2 != ""){
			$date .= " and a.booking_date <= '$date2'";
		}
		$eid = HelperOSappscheduleCommon::getEmployeeId();
		$today = date("Y",HelperOSappscheduleCommon::getRealTime())."-".date("m",HelperOSappscheduleCommon::getRealTime())."-".date("d",HelperOSappscheduleCommon::getRealTime());
		//get the work of this employee
		$db->setQuery("Select a.*,b.order_name,c.service_name from #__app_sch_order_items as a inner join #__app_sch_orders as b on a.order_id = b.id inner join #__app_sch_services as c on c.id = a.sid where b.order_status in ('P','S') and a.eid = '$eid' and a.booking_date >= '$today' $date order by a.start_time");
		$rows = $db->loadObjectList();
		
		$db->setQuery("Select * from #__app_sch_employee where id = '$eid'");
		$employee = $db->loadObject();
		HTML_OsAppscheduleDefault::listEmployeeWorks($employee,$rows);
	}
	
	/**
	 * Order form
	 *
	 * @param unknown_type $order_id
	 */
	public static function orderDetailsForm($order_id,$checkin = 0){
		global $mainframe;
		$config = new JConfig();
		$offset = $config->offset;
		date_default_timezone_set($offset);
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__app_sch_orders where id = '$order_id'");
		$order = $db->loadObject();
		$db->setQuery("Select a.*,b.id,b.*,c.id,c.employee_name from #__app_sch_order_items as a inner join #__app_sch_services as b on b.id = a.sid inner join #__app_sch_employee as c on c.id = a.eid where a.order_id = '$order_id'");
		$rows = $db->loadObjectList();
		
		HTML_OsAppscheduleDefault::showOrderDetailsForm($order,$rows,$checkin);
	}
	
	/**
	 * Cancel the payment
	 *
	 * @param unknown_type $order_id
	 */
	function cancelPayment($order_id){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$db->setQuery("UPDATE #__app_sch_orders SET order_status = 'C' WHERE id = '$order_id'");
		$db->query();
		HelperOSappscheduleCommon::sendCancelledEmail($order_id);
		HelperOSappscheduleCommon::sendSMS('cancel',$order_id);
		HelperOSappscheduleCommon::sendEmployeeEmail('employee_order_cancelled',$order_id,0);
		?>
		<h2>
			<?php echo JText::_('OS_YOUR_BOOKING_REQUEST_HAS_BEEN_CANCELLED');?>
		</h2>
		<?php
	}
	
	/**
	 * Payment failure
	 *
	 * @param unknown_type $order_id
	 */
	function paymentFailure($order_id){
		global $mainframe;
		$db = JFactory::getDbo();
		$db->setQuery("UPDATE #__app_sch_orders SET order_status = 'C' WHERE id = '$order_id'");
		$db->query();
		?>
		<h2>
			<?php echo JText::_('OS_YOUR_TRANSACTION_IS_FAILURE');?>
		</h2>
		<?php
		if($_SESSION['reason']!=""){
			echo $_SESSION['reason'];
		}
	}
	
	/**
	 * Payment return
	 *
	 * @param unknown_type $order_id
	 */
	function returnPayment($order_id){
		global $mainframe,$configClass;
        $msg = JRequest::getVar('msg','');
		$mainframe->redirect(JURI::root()."index.php?option=com_osservicesbooking&task=default_orderDetailsForm&order_id=".$order_id."&ref=".md5($order_id),$msg);
	}
	
	/**
	 * Paypal notification
	 *
	 * @param unknown_type $option
	 */
	function paymentNotify(){
		global $mainframe,$configClass;
		$paymentMethod =  JRequest::getVar('payment_method', '');
		$method = os_payments::getPaymentMethod($paymentMethod) ;
		$method->verifyPayment();
	}
	
	/**
	 * Payment complete
	 *
	 * @param unknown_type $orderId
	 */
	function paymentComplete($orderId){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$db->setQuery("UPDATE #__app_sch_orders SET order_status = 'S' WHERE id = '$orderId'");
		$db->query();
		
		$db->setQuery("Select * from #__app_sch_orders where id = '$orderId'");
		$row = $db->loadObject();
		JPluginHelper::importPlugin('osservicesbooking');
		$dispatcher = JDispatcher::getInstance();
		$dispatcher->trigger('onOrderActive', array($row));
				
		//send email to customer to inform the payment is completed
		if($configClass['value_enum_email_payment'] == 2){
			HelperOSappscheduleCommon::sendEmail('payment',$orderId);
			HelperOSappscheduleCommon::sendSMS('payment',$orderId);
		}
		//send confirm email 
		if($configClass['value_enum_email_confirmation'] == 3){
			HelperOSappscheduleCommon::sendEmail('confirm',$orderId);
			HelperOSappscheduleCommon::sendEmail('admin',$orderId);
			HelperOSappscheduleCommon::sendEmployeeEmail('employee_notification',$orderId,0);
			HelperOSappscheduleCommon::sendSMS('confirm',$orderId);
			HelperOSappscheduleCommon::sendSMS('admin',$orderId);
			HelperOSappscheduleCommon::updateAcyMailing($orderId);
		}
		
		//update to Google Calendar
		include_once(JPATH_ADMINISTRATOR.DS."components".DS."com_osservicesbooking".DS."helpers".DS."helper.php");
		OSBHelper::updateGoogleCalendar($orderId);
	}
	
	/**
	 * Payment process
	 *
	 * @param unknown_type $option
	 */
	function paymentProcess($option){
		global $mainframe,$configClass;
		$Itemid = JRequest::getint('Itemid');
		$order_id = JRequest::getVar('order_id',0);
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__app_sch_orders where id = '$order_id'");
		$order = $db->loadObject();
		$data['payment_method'] 			= $order->order_payment;
		$data['x_card_num'] 				= $order->order_card_number;
		$data['x_card_code'] 				= $order->order_cvv_code;
		$data['card_holder_name'] 			= $order->order_card_holder;
		$data['exp_year'] 					= $order->order_card_expiry_year;
		$data['exp_month'] 					= $order->order_card_expiry_month;
		$data['card_type'] 					= $order->order_card_type;
		$data['address'] 					= $order->order_address;
		$data['city'] 						= $order->order_city;
		$data['state'] 						= $order->order_state;
		$data['zip'] 						= $order->order_zip;
		$data['phone']						= $order->order_phone;
		$data['bank_id']					= $order->bank_id;
		$order_name 						= $order->order_name;
		$order_name							= explode(" ",$order_name);
		if(count($order_name) > 1){
			$first_name = $order_name[0];
			$last_name  = "";
			for($i=1;$i<count($order_name);$i++){
				$last_name = $order_name[$i]." ";
			}
		}
		$order_country						= $order->order_country;
		if($order_country == ""){
			$order_country = "US";
		}else{
			$db->setQuery("Select country_code from #__app_sch_countries where country_name like '$order_country'");
			$order_country = $db->loadResult();
		}
		$data['country']					= $order_country;
		$data['first_name'] 				= $first_name;
		$data['last_name'] 					= $last_name;
		$data['amount']						= $order->order_upfront;
		$data['currency']					= $configClass['currency_format'];
		$data['item_name']					= JText::_('OS_PAYMENT_FOR_SERVICES_BOOKING_REQUEST');
		
		$order_payment = $order->order_payment;
		if($configClass['disable_payments'] == 1){
			if($order_payment == ""){
				JError::raiseError( 500, JText::_('Opps, there is a problem with Booking Progress, please try to make booking again later!') );
			}else{
				require_once JPATH_COMPONENT.'/plugins/'.$order_payment.'.php';
				$sql = 'SELECT params FROM #__app_sch_plugins WHERE name="'.$order_payment.'"';
				$db->setQuery($sql) ;
				$plugin = $db->loadObject();
				$params = $plugin->params ;
				$params = new JRegistry($params) ;
				$paymentClass = new $order_payment($params) ;  
				$paymentClass->processPayment($order, $data);
			}
		}else{
			$db->setQuery("Update #__app_sch_orders set order_status  = 'P' where id = '$order->id'");
			$db->query();
			OsAppscheduleDefault::paymentComplete($order->id);
			$mainframe->redirect(JURI::root()."index.php?option=com_osservicesbooking&task=default_orderDetailsForm&order_id=".$order_id."&ref=".md5($order_id));	
		}
		/*
		if($order_payment != ""){
			if(file_exists(JPATH_COMPONENT.DS."helpers".DS."payments".DS.$order_payment.".php")){
				require_once(JPATH_COMPONENT.DS."helpers".DS."payments".DS.$order_payment.".php");
				$pClass=new $order_payment();
				$pClass->processPayment($order);
			}else{
				$db->setQuery("Update #__app_sch_orders set order_status  = 'P' where id = '$order->id'");
				$db->query();
				OsAppscheduleDefault::paymentComplete($order->id);
				$mainframe->redirect(JURI::root()."index.php?option=com_osservicesbooking&task=default_orderDetailsForm&order_id=".$order_id);
			}
		}else{
			$db->setQuery("Update #__app_sch_orders set order_status  = 'P' where id = '$order->id'");
			$db->query();
			OsAppscheduleDefault::paymentComplete($order->id);
			$mainframe->redirect(JURI::root()."index.php?option=com_osservicesbooking&task=default_orderDetailsForm&order_id=".$order_id);	
		}
		*/
	}

	
	/**
	 * Complete Order
	 *
	 * @param unknown_type $option
	 */
	function completeOrder($option){
		global $mainframe,$configClass,$languages;
		$db = JFactory::getDbo();
		$user = JFactory::getUser();
		$lang = JFactory::getLanguage();
		$lang = $lang->getTag();		
		//before create the order, checking in the table order first
		//$unique_cookie = $_COOKIE['unique_cookie'];
		$unique_cookie = JRequest::getVar('unique_cookie');
		@setcookie('unique_cookie',$unique_cookie,time()+1000,'/');
		if($unique_cookie != ""){
			//create the order
			$order_price = OsAppscheduleAjax::getOrderCost();
			$db->setQuery("Select id from #__app_sch_fields where published = '1' and field_area = '1' order by ordering");
			$fields = $db->loadObjectList();
			if(count($fields)>0){
				for($i=0;$i<count($fields);$i++){
					$fid = $fields[$i]->id;
					$fieldvalue = JRequest::getVar('field_'.$fid,'');
					if($fieldvalue != ""){
						$db->setQuery("Select id,field_label,field_type from #__app_sch_fields where  id = '$fid'");
						$field = $db->loadObject();
						$field_type = $field->field_type;
						if($field_type == 1){
							$db->setQuery("Select * from #__app_sch_field_options where id = '$fieldvalue'");
							$optionvalue = $db->loadObject();
							if($optionvalue->additional_price > 0){
								$order_price += $optionvalue->additional_price;
							}
						}elseif($field_type == 2){
							$fieldValueArr = explode(",",$fieldvalue);
							if(count($fieldValueArr) > 0){
								for($j=0;$j<count($fieldValueArr);$j++){
									$temp = $fieldValueArr[$j];
									$db->setQuery("Select * from #__app_sch_field_options where id = '$temp'");
									$optionvalue = $db->loadObject();
									if($optionvalue->additional_price > 0){
										$order_price += $optionvalue->additional_price;
									}
								}
							}
						}
					}
				}
			}
			
			$tax								= round($configClass['tax_payment']*$order_price/100,2);
			$order_total						= $order_price + $tax;
			$coupon_id 							= JRequest::getInt('coupon_id',0);
			if($coupon_id > 0){
				$db->setQuery("Select * from #__app_sch_coupons where id = '$coupon_id'");
				$coupon = $db->loadObject();
				$max_user_use = $coupon->max_user_use;
				$max_total_use = $coupon->max_total_use;
				if($max_total_use > 0){
					$db->setQuery("Select count(id) from #__app_sch_coupon_used where coupon_id = '$coupon_id'");
					$nused = $db->loadResult();
					if($nused >= $max_total_use){
						$coupon_id = 0;
					}
				}
				if(($max_user_use > 0) and ($coupon_id > 0)){
					if($user->id > 0){
						$db->setQuery("Select count(id) from #__app_sch_coupon_used where user_id = '$user->id' and coupon_id = '$coupon_id'");
						$nused = $db->loadResult();
						if($nused >= $max_user_use){
							$coupon_id = 0;
						}
					}
				}
			}
			$discount_amount = 0;
			if($coupon_id > 0){
				$db->setQuery("Select * from #__app_sch_coupons where id = '$coupon_id'");
				$coupon = $db->loadObject();
				if($coupon->discount_type == 0){
					$discount_amount = $order_total*$coupon->discount/100;
				}else{
					$discount_amount = $coupon->discount;
				}
			}
			$order_total_temp					= $order_total - $discount_amount;
			if($order_total_temp <= 0){
				$discount_amount				= $order_total;
				$order_total					= 0;
			}else{
				$order_total					= $order_total_temp;
			}
			$user								= JFactory::getUser();
			$deposit							= $order_total*$configClass['deposit_payment']/100;
			$row 								= &JTable::getInstance('Order','OsAppTable');
			$row->id = 0;
			$row->user_id						= $user->id;
			$row->order_name 					= JRequest::getVar('order_name','');
			$row->order_email 					= JRequest::getVar('order_email','');
			$order_phone						= JRequest::getVar('order_phone','');
			if(substr($order_phone,0,1) == "0"){
				$order_phone					= trim(substr($order_phone,1));
			}
			$row->order_phone					= $order_phone;
			$row->order_country 				= JRequest::getVar('order_country','');
			$row->order_state 					= JRequest::getVar('order_state','');
			$row->order_city					= JRequest::getVar('order_city','');
			$row->order_zip 					= JRequest::getVar('order_zip','');
			$row->order_address 				= JRequest::getVar('order_address','');
			$row->dial_code						= JRequest::getVar('dial_code','');
			$row->order_total					= $order_price;
			$row->order_tax						= $tax;
			$row->order_final_cost				= $order_total;
			$row->order_upfront					= $deposit;
			if($configClass['disable_payments'] == 0){
				$row->order_status				= $configClass['disable_payment_order_status'];
			}elseif($row->order_final_cost == 0){
				$row->order_status				= 'S';
			}else{
				$row->order_status				= 'P';
			}
			$row->order_date					= date("Y-m-d H:i:s",HelperOSappscheduleCommon::getRealTime());
			$row->order_lang				 	= $lang;
			$row->order_payment					= JRequest::getVar('select_payment','');
			$notes								= $_POST['notes'];
			$row->order_notes					= $notes;
			$row->order_card_number				= JRequest::getVar('x_card_num','');
			$row->order_cvv_code				= JRequest::getVar('x_card_code','');
			$row->order_card_holder				= JRequest::getVar('card_holder_name','');
			$row->order_card_expiry_year		= JRequest::getVar('exp_year','');
			$row->order_card_expiry_month		= JRequest::getVar('exp_month','');
			$row->order_card_type				= JRequest::getVar('card_type','');
			$row->coupon_id						= $coupon_id;
			$row->order_discount				= $discount_amount;
			$row->bank_id						= JRequest::getVar('bank_id','');
			
			$row->store();
			$order_id							= $db->insertID();

            //add qrcode
            if($configClass['use_qrcode']){
                OSBHelper::generateQrcode($order_id);
            }

			if($row->coupon_id > 0){
				//added into coupon used table
				$db->setQuery("Insert into #__app_sch_coupon_used (id,user_id,coupon_id,order_id) values (NULL,'$user->id','$row->coupon_id','$order_id')");
				$db->query();
			}
			
			//update order items table
			
			$db->setQuery("Select id from #__app_sch_temp_orders where unique_cookie like '$unique_cookie'");
			$temp_order_id = $db->loadResult();
			$db->setQuery("Select * from #__app_sch_temp_order_items where order_id = '$temp_order_id'");
			$orders = $db->loadObjectList();
			
			if(count($orders) > 0){
				for($i=0;$i<count($orders);$i++){
					$orderdata = $orders[$i];
					$db->setQuery("INSERT INTO #__app_sch_order_items (id,order_id,sid,eid,start_time,end_time,booking_date,nslots) VALUES (NULL,$order_id,'$orderdata->sid','$orderdata->eid','$orderdata->start_time','$orderdata->end_time','$orderdata->booking_date','$orderdata->nslots')");
					$db->query();
					$order_item_id = $db->insertID();
					
					$db->setQuery("Delete from #__app_sch_temp_order_items where id = '$orderdata->id'");
					$db->query();
					
					$db->setQuery("Select * from #__app_sch_temp_order_field_options where order_item_id = '$orderdata->id'");
					$addArr = $db->loadObjectList();
					
					$field_amount = 0;
					$field_data   = "";
					if(count($addArr) > 0){
						for($i1=0;$i1<count($addArr);$i1++){
							$addtemp = $addArr[$i1];
							$db->setQuery("INSERT INTO #__app_sch_order_field_options (id, order_item_id,field_id,option_id) VALUES (NULL,'$order_item_id','$addtemp->field_id','$addtemp->option_id')");
							$db->query();
							$db->setQuery("Delete from #__app_sch_temp_order_field_options where id = '$addtemp->id'");
							$db->query();
						}
					}
				}
				//break;
			}
			//break;
			//add custom fields into the table order booking
			$db->setQuery("Select id from #__app_sch_fields where published = '1' and field_area = '1' order by ordering");
			$fields = $db->loadObjectList();
			if(count($fields)>0){
				for($i=0;$i<count($fields);$i++){
					$fid = $fields[$i]->id;
					$fieldvalue = JRequest::getVar('field_'.$fid,'');
					if($fieldvalue != ""){
						$db->setQuery("Select id,field_label,field_type from #__app_sch_fields where  id = '$fid'");
						$field = $db->loadObject();
						$field_type = $field->field_type;
						if($field_type == 0){
							$fielddata = &JTable::getInstance('FieldData','OsAppTable');
							$fielddata->id 			= 0;
							$fielddata->order_id 	= $order_id;
							$fielddata->fid			= $fid;
							$fielddata->fvalue 		= $fieldvalue;
							$fielddata->store();
						}elseif($field_type == 1){
							$fielddata = &JTable::getInstance('OrderField','OsAppTable');
							$fielddata->id 			= 0;
							$fielddata->order_id 	= $order_id;
							$fielddata->field_id	= $fid;
							$fielddata->option_id 	= $fieldvalue;
							$fielddata->store();
						}elseif($field_type == 2){
							$fieldValueArr = explode(",",$fieldvalue);
							if(count($fieldValueArr) > 0){
								for($j=0;$j<count($fieldValueArr);$j++){
									$temp = $fieldValueArr[$j];
									$fielddata = &JTable::getInstance('OrderField','OsAppTable');
									$fielddata->id = 0;
									$fielddata->order_id 	= $order_id;
									$fielddata->field_id	= $fid;
									$fielddata->option_id 	= $temp;
									$fielddata->store();
								}
							}
						}
					}
				}
			}			
		
			//empty the cookie
			//@setcookie('','',HelperOSappscheduleCommon::getRealTime()-3600,'/');
			if($configClass['disable_payments'] == 1){
				if($configClass['value_enum_email_confirmation'] == 2){
					HelperOSappscheduleCommon::sendEmail('confirm',$order_id);
					HelperOSappscheduleCommon::sendEmail('admin',$order_id);
					HelperOSappscheduleCommon::sendEmployeeEmail('employee_notification',$order_id,0);
					HelperOSappscheduleCommon::sendSMS('confirm',$order_id);
					HelperOSappscheduleCommon::sendSMS('admin',$order_id);
					HelperOSappscheduleCommon::updateAcyMailing($order_id);
				}
				
				if(($row->order_final_cost == 0) or ($deposit == 0)){
					if($row->order_payment == "os_offline"){
						$mainframe->redirect($configClass['root_link']."index.php?option=com_osservicesbooking&task=default_payment&order_id=".$order_id);
					}else{
						OSBHelper::updateGoogleCalendar($order_id);
						$mainframe->redirect(JURI::root()."index.php?option=com_osservicesbooking&task=default_orderDetailsForm&order_id=".$order_id."&ref=".md5($order_id)."&Itemid=".JRequest::getInt('Itemid',0));
					}
				}else{
					$mainframe->redirect($configClass['root_link']."index.php?option=com_osservicesbooking&task=default_payment&order_id=".$order_id);
				}
			}else{
				if(($configClass['value_enum_email_confirmation'] == 2) or ($configClass['disable_payments'] == 0)){
					HelperOSappscheduleCommon::sendEmail('confirm',$order_id);
					HelperOSappscheduleCommon::sendEmail('admin',$order_id);
					HelperOSappscheduleCommon::sendEmployeeEmail('employee_notification',$order_id,0);
					HelperOSappscheduleCommon::sendSMS('confirm',$order_id);
					HelperOSappscheduleCommon::sendSMS('admin',$order_id);
					OSBHelper::updateGoogleCalendar($order_id);
					HelperOSappscheduleCommon::updateAcyMailing($order_id);
				}
				//$mainframe->redirect($configClass['page_location']);
				$mainframe->redirect(JURI::root()."index.php?option=com_osservicesbooking&task=default_orderDetailsForm&order_id=".$order_id."&ref=".md5($order_id)."&Itemid=".JRequest::getInt('Itemid',0));
			}
		}
	}
	
	/**
	 * Process cron task
	 *
	 */
	function cron(){
		global $mainframe, $configClass;
		$db = JFactory::getDbo();
		$current_time = HelperOSappscheduleCommon::getRealTime();
		$reminder = $configClass['value_sch_reminder_email_before'];
		$reminder = $current_time + $reminder*3600;
		$query = "Select a.* from #__app_sch_order_items as a"
				." inner join #__app_sch_orders as b on b.id = a.order_id"
				." where a.start_time <= '$reminder' and a.start_time > '$current_time' and b.order_status = 'S' and a.id not in (Select order_item_id from #__app_sch_cron)";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		if(count($rows) > 0){
			for($i=0;$i<count($rows);$i++){
				$row = $rows[$i];
				HelperOSappscheduleCommon::sendEmail('reminder',$row->id);
				HelperOSappscheduleCommon::sendSMS('reminder',$row->order_id);
				//add into the cron table
				$db->setQuery("Insert into #__app_sch_cron (id,order_item_id) values (NULL,'$row->id')");
				$db->query();
			}
		}
	}
	
	/**
	 * Cancel the order
	 *
	 */
	function cancelOrder(){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$id = JRequest::getVar('id',0);
		if($id > 0){
			$ref = JRequest::getVar('ref','');
			$ide = md5($id);
			$cancel_before = $configClass['cancel_before'];
			$db->setQuery("Select a.start_time from #__app_sch_order_items as a inner join #__app_sch_orders as b on b.id = a.order_id where b.id = '$id' order by a.start_time");
			$earliest = $db->loadResult();
			
			$current_time = HelperOSappscheduleCommon::getRealTime();
			if(($current_time + $cancel_before*3600 < $earliest) and ($ide == $ref)){			
				$db->setQuery("UPDATE #__app_sch_orders SET order_status = 'C' WHERE id = '$id'");
				$db->query();
				
				//send notification email
				HelperOSappscheduleCommon::sendCancelledEmail($id);
				HelperOSappscheduleCommon::sendSMS('cancel',$id);
				HelperOSappscheduleCommon::sendEmployeeEmail('employee_order_cancelled',$id,$eid);
				if($configClass['integrate_gcalendar'] == 1){
					OSBHelper::removeEventOnGCalendar($id);
				}
				?>
				<div>
					<h2>
						<?php echo JText::_('OS_ORDER')?> (<?php echo $id?>) <?php echo JText::_('OS_HAS_BEEN_CANCELLED')?>.
					</h2>
				</div>
				<?php
			}else{
				?>
			<div>
				<h2>
					<?php echo JText::_('OS_YOU_CANNOT_REQUEST_TO_CANCEL_BOOKING_REQUEST_ANYMORE');?>
				</h2>
			</div>
			<?php
			}
		}else{
			?>
			<div>
				<h2>
					<?php echo JText::_('OS_OPPS_ERROR');?>
				</h2>
			</div>
			<?php
		}
	}
	
/**
	 * Order fields in the booking form
	 *
	 * @param unknown_type $field
	 */
	function orderFieldData($field,$order_id){
		global $mainframe,$configClass;
		switch ($field->field_type){
			case "0":
				return OsAppscheduleDefault::orderInputboxData($field,$order_id);
			break;
			case "1":
				return OsAppscheduleDefault::orderSelectListData($field,$order_id);
			break;
			case "2":
				return OsAppscheduleDefault::orderCheckboxesData($field,$order_id);
			break;
		}
	}
	
	/**
	 * Order fields in the booking form
	 *
	 * @param unknown_type $field
	 */
	function orderField($field,$order_id){
		global $mainframe,$configClass;
		switch ($field->field_type){
			case "0":
				OsAppscheduleDefault::orderInputbox($field,$order_id);
			break;
			case "1":
				OsAppscheduleDefault::orderSelectList($field,$order_id);
			break;
			case "2":
				OsAppscheduleDefault::orderCheckboxes($field,$order_id);
			break;
		}
	}
	
	/**
	 * Show inputbox
	 *
	 * @param unknown_type $field
	 */
	function orderInputbox($field,$order_id){
		$db = JFactory::getDbo();
		$db->setQuery("Select `fvalue` from #__app_sch_field_data where fid = '$field->id' and order_id = '$order_id'");
		$fvalue = $db->loadResult();
		?>
		<input type="text" class="input-large" size="30" name="field_<?php echo $field->id?>" id="field_<?php echo $field->id?>" value="<?php echo $fvalue?>" />
		<input type="hidden" name="field_<?php echo $field->id?>_required" id="field_<?php echo $field->id?>_required" value="<?php echo $field->required; ?>" />
		<input type="hidden" name="field_<?php echo $field->id?>_label" id="field_<?php echo $field->id?>_label" value="<?php echo OSBHelper::getLanguageFieldValue($field,'field_label');?>" />
		<?php
	}
	
	function orderInputboxData($field,$order_id){
		$db = JFactory::getDbo();
		$db->setQuery("Select `fvalue` from #__app_sch_field_data where fid = '$field->id' and order_id = '$order_id'");
		//echo $db->getQuery();
		$fvalue = $db->loadResult();
		return $fvalue;
	}
	
	/**
	 * Show select list in booking form
	 *
	 * @param unknown_type $field
	 */
	function orderSelectList($field,$order_id){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		
		if($order_id > 0){
			//find the option value of order in this field
			$query = $db->getQuery(true);
			$query->select('option_id');
			$query->from($db->quoteName('#__app_sch_order_options'));
			$query->where("order_id = '$order_id' and field_id = '$field->id'");
			$db->setQuery($query);
			$option_id = $db->loadResult();
		}
		//echo $option_id;
		$db->setQuery("Select * from #__app_sch_field_options where field_id = '$field->id'");
		$optionArr = $db->loadObjectList();
		//print_r($optionArr);
		?>
		<select name="field_<?php echo $field->id?>" id="field_<?php echo $field->id?>" class="input-small">
			<option value=""></option>
			<?php
			if(count($optionArr) > 0){
				for($i=0;$i<count($optionArr);$i++){
					$op = $optionArr[$i];
					$field_value = OSBHelper::getLanguageFieldValue($op,'field_option');
					if(!$mainframe->isadmin()){
						if($op->additional_price > 0){
							$field_value .= " - ".$op->additional_price." ".$configClass['currency_format'];
						}
					}
					if($option_id == $optionArr[$i]->id){
						$selected = "selected";
					}else{
						$selected = "";
					}
					?>
					<option value="<?php echo $optionArr[$i]->id?>" <?php echo $selected?>><?php echo $field_value?></option>
					<?php
				}
			}
			?>
		</select>
		<input type="hidden" name="field_<?php echo $field->id?>_required" id="field_<?php echo $field->id?>_required" value="<?php echo $field->required; ?>" />
		<input type="hidden" name="field_<?php echo $field->id?>_label" id="field_<?php echo $field->id?>_label" value="<?php echo OSBHelper::getLanguageFieldValue($field,'field_label');?>" />
		<?php
	}
	
	function orderSelectListData($field,$order_id){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		if($order_id > 0){
			//find the option value of order in this field
			$query = $db->getQuery(true);
			$query->select('option_id');
			$query->from($db->quoteName('#__app_sch_order_options'));
			$query->where("order_id = '$order_id' and field_id = '$field->id'");
			$db->setQuery($query);
			$option_id = $db->loadResult();
			if($option_id > 0){
				$db->setQuery("Select * from #__app_sch_field_options where id = '$option_id'");
				$fieldvalue = $db->loadObject();
				//echo OSBHelper::getLanguageFieldValue($fieldvalue,'field_option');
				return OSBHelper::getLanguageFieldValue($fieldvalue,'field_option');
			}
		}
		return "";
	}
	
	
	/**
	 * Show checkboxes in booking form
	 *
	 * @param unknown_type $field
	 */
	function orderCheckboxes($field,$order_id){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		
		if($order_id > 0){
			//find the option value of order in this field
			$query = $db->getQuery(true);
			$query->select('option_id');
			$query->from($db->quoteName('#__app_sch_order_options'));
			$query->where("order_id = '$order_id' and field_id = '$field->id'");
			$db->setQuery($query);
			$option_ids = $db->loadObjectList();
			
			$options = array();
			if(count($option_ids) > 0){
				for($i=0;$i<count($option_ids);$i++){
					$options[$i] = $option_ids[$i]->option_id;
				}
			}
			$query->clear();
		}
		$db->setQuery("Select * from #__app_sch_field_options where field_id = '$field->id' order by ordering");
		$optionArr = $db->loadObjectList();
		?>
		<table width="100%">
			<tr>
				<?php
				$j = 0;
				$temp = array();
				for($i=0;$i<count($optionArr);$i++){
					$j++;
					$op = $optionArr[$i];
					$field_value = OSBHelper::getLanguageFieldValue($op,'field_option'); //$op->field_option;
					if(!$mainframe->isadmin()){
						if($op->additional_price > 0){
							$field_value .= " - ".$op->additional_price." ".$configClass['currency_format'];
						}
					}
					?>
					<td width="50%" style="padding:2px;text-align:left;padding-left:20px;">
						<?php
						if(in_array($op->id,$options)){
							$checked = "checked";
							$temp[] = $op->id;
						}else{
							$checked = "";
						}
						?>
						<input type="checkbox" name="field_<?php echo $field->id?>checkboxes" id="field_<?php echo $field->id?>_checkboxes<?php echo $i?>" value="<?php echo $op->id?>" onclick="javascript:updateCheckboxOrderForm(<?php echo $field->id?>)" <?php echo $checked?> /> &nbsp;&nbsp;<?php echo $field_value?>
					</td>
					<?php
					if($j==2){
						$j = 0;
						echo "</tr><tr>";
					}
				}
				?>
			</tr>
		</table>
		<input type="hidden" name="field_<?php echo $field->id?>_count" id="field_<?php echo $field->id?>_count" value="<?php echo count($optionArr)?>">
		<input type="hidden" name="field_<?php echo $field->id?>" id="field_<?php echo $field->id?>" value="<?php echo implode(",",$temp)?>" />
		<input type="hidden" name="field_<?php echo $field->id?>_required" id="field_<?php echo $field->id?>_required" value="<?php echo $field->required; ?>" />
		<input type="hidden" name="field_<?php echo $field->id?>_label" id="field_<?php echo $field->id?>_label" value="<?php echo OSBHelper::getLanguageFieldValue($field,'field_label');?>" />
		<?php
	}
	
	
	function orderCheckboxesData($field,$order_id){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		
		if($order_id > 0){
			//find the option value of order in this field
			$query = $db->getQuery(true);
			$query->select('option_id');
			$query->from($db->quoteName('#__app_sch_order_options'));
			$query->where("order_id = '$order_id' and field_id = '$field->id'");
			$query->order('ordering');
			$db->setQuery($query);
			$option_ids = $db->loadObjectList();
			
			$options = array();
			if(count($option_ids) > 0){
				for($i=0;$i<count($option_ids);$i++){
					$options[$i] = $option_ids[$i]->option_id;
				}
				$db->setQuery("Select * from #__app_sch_field_options where id in (".implode(",",$options).")");
				$optionArr = $db->loadObjectList();
				$field_value_array = array();
				for($i=0;$i<count($optionArr);$i++){
					$op = $optionArr[$i];
					$field_value = OSBHelper::getLanguageFieldValue($op,'field_option'); //$op->field_option;
					if($op->additional_price > 0){
						$field_value .= " - ".$op->additional_price." ".$configClass['currency_format'];
					}
					$field_value_array[] = $field_value;
				}
				return implode(", ",$field_value_array);
			}
		}
		return "";
	}

    public static function checkExtraFields($sid,$eid){
        global $mainframe,$configClass;
        $mainframe = JFactory::getApplication();
        $db = JFactory::getDbo();
        $db->setQuery("Select count(id) from #__app_sch_fields where published = '1' and field_area = '0' and field_type in (1,2) and id in (Select field_id from #__app_sch_service_fields where service_id = '$sid') order by ordering");
        $count = $db->loadResult();
        if($count > 0){
            return true;
        }else{
            return false;
        }
    }
	/**
	 * Load extra fields in the employee forms
	 *
	 * @param unknown_type $sid
	 * @param unknown_type $eid
	 */
	function loadExtraFields($sid,$eid){
		global $mainframe,$configClass;
		$mainframe = JFactory::getApplication();
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__app_sch_fields where published = '1' and field_area = '0' and field_type in (1,2) and id in (Select field_id from #__app_sch_service_fields where service_id = '$sid') order by ordering");
		//echo $db->getQuery();
		$fields = $db->loadObjectList();
		$fieldArr = array();
		if(count($fields) > 0){
			?>
			<BR />
			<div class="row-fluid bookingformdiv">
				<div class="span12 <?php echo $configClass['header_style']?>">
					<?php echo JText::_('OS_OTHER_INFORMATION')?>
				</div>
				<BR /><BR />
				<?php
				for($i=0;$i<count($fields);$i++){
					
				
				$field = $fields[$i];
				$fieldArr[] = $field->id;
				?>
				<div class="span12">
					<div class="span4" style="font-weight:bold;">
						<?php echo OSBHelper::getLanguageFieldValue($field,'field_label');?>
					</div>
					<div class="span8">
						<?php
							switch ($field->field_type){
								case "0":
									OsAppscheduleDefault::showInputbox($field,$sid,$eid);
								break;
								case "1":
									OsAppscheduleDefault::showSelectList($field,$sid,$eid);
								break;
								case "2":
									OsAppscheduleDefault::showCheckboxes($field,$sid,$eid);
								break;
							}
						?>
					</div>
				</div>
				<?php
				}
				?>
			</div>
			<?php
		}
		?>
		<input type="hidden" name="field_ids<?php echo $sid?>" id="field_ids<?php echo $sid?>" value="<?php echo implode(",",$fieldArr)?>">
		<?php
	}
	
	/**
	 * Show input box
	 *
	 * @param unknown_type $field
	 * @param unknown_type $sid
	 * @param unknown_type $eid
	 */
	function showInputbox($field,$sid,$eid){
		global $mainframe;
		?>
		<input type="text" class="inputbox" size="30" name="field_<?php echo $sid?>_<?php echo $eid?>_<?php echo $field->id?>" id="field_<?php echo $sid?>_<?php echo $eid?>_<?php echo $field->id?>">
		<input type="hidden" name="field_<?php echo $sid?>_<?php echo $eid?>_<?php echo $field->id?>_label" id="field_<?php echo $sid?>_<?php echo $eid?>_<?php echo $field->id?>_label" value="<?php echo $field->field_label?>">
		<input type="hidden" name="field_<?php echo $sid?>_<?php echo $eid?>_<?php echo $field->id?>_required" id="field_<?php echo $sid?>_<?php echo $eid?>_<?php echo $field->id?>_required" value="<?php echo $field->required;?>">
		<?php
	}
	
	
	/**
	 * Show select list
	 *
	 * @param unknown_type $field
	 * @param unknown_type $sid
	 * @param unknown_type $eid
	 */
	function showSelectList($field,$sid,$eid){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		?>
		<input type="hidden" name="field_<?php echo $sid?>_<?php echo $eid?>_<?php echo $field->id?>_label" id="field_<?php echo $sid?>_<?php echo $eid?>_<?php echo $field->id?>_label" value="<?php echo $field->field_label?>">
		
		<select name="field_<?php echo $sid?>_<?php echo $eid?>_<?php echo $field->id?>_selectlist" id="field_<?php echo $sid?>_<?php echo $eid?>_<?php echo $field->id?>_selectlist" class="input-small" onChange="javascript:updateSelectlist(<?php echo $sid?>,<?php echo $eid;?>,<?php echo $field->id?>,'<?php echo JText::_('OS_SUMMARY');?>','<?php echo JText::_('OS_FROM');?>','<?php echo JText::_('OS_TO');?>')">
			<option value=""></option>
			<?php
			//$options = $field->field_options;
			$db->setQuery("Select * from #__app_sch_field_options where field_id = '$field->id' order by ordering");
			$optionArr = $db->loadObjectList();
			if(count($optionArr) > 0){
				for($i=0;$i<count($optionArr);$i++){
					$op = $optionArr[$i];
					$field_value = OSBHelper::getLanguageFieldValue($op,'field_option');
					if(!$mainframe->isadmin()){
						if($op->additional_price > 0){
							$field_value .= " - ".$op->additional_price." ".$configClass['currency_format'];
						}
					}
					?>
					<option value="<?php echo $op->id?>||<?php echo $field_value;?>"><?php echo $field_value;?></option>
					<?php
				}
			}
			?>
		</select>
		<input type="hidden" name="field_<?php echo $sid?>_<?php echo $eid?>_<?php echo $field->id?>_selected" id="field_<?php echo $sid?>_<?php echo $eid?>_<?php echo $field->id?>_selected" value="">
		<input type="hidden" name="field_<?php echo $sid?>_<?php echo $eid?>_<?php echo $field->id?>" id="field_<?php echo $sid?>_<?php echo $eid?>_<?php echo $field->id?>" value="">
		<input type="hidden" name="field_<?php echo $sid?>_<?php echo $eid?>_<?php echo $field->id?>_required" id="field_<?php echo $sid?>_<?php echo $eid?>_<?php echo $field->id?>_required" value="<?php echo $field->required;?>">
		<?php
	}
	
	/**
	 * Show checkboxes
	 *
	 * @param unknown_type $field
	 * @param unknown_type $sid
	 * @param unknown_type $eid
	 */
	function showCheckboxes($field,$sid,$eid){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$options = $field->field_options;
		$optionArr = explode("\n",$options);
		//if(count($optionArr) > 0){
		?>
		<div class="row-fluid">
			<?php
			$j = 0;
			$db->setQuery("Select * from #__app_sch_field_options where field_id = '$field->id' order by ordering");
			$optionArr = $db->loadObjectList();
			for($i=0;$i<count($optionArr);$i++){
				$j++;
				$op = $optionArr[$i];
				//$field_value = $op->field_option;
				$field_value = OSBHelper::getLanguageFieldValue($op,'field_option');
				if(!$mainframe->isadmin()){
					if($op->additional_price > 0){
						$field_value .= " - ".$op->additional_price." ".$configClass['currency_format'];
					}
				}
				?>
				<div class="span6" style="margin-left:0px;">
					<input type="checkbox" name="field_<?php echo $sid?>_<?php echo $eid?>_<?php echo $field->id?>checkboxes" id="field_<?php echo $sid?>_<?php echo $eid?>_<?php echo $field->id?>_checkboxes<?php echo $i?>" onclick="javascript:updateCheckbox(<?php echo $sid?>,<?php echo $eid?>,<?php echo $field->id?>,'<?php echo JText::_('OS_SUMMARY');?>','<?php echo JText::_('OS_FROM');?>','<?php echo JText::_('OS_TO');?>');" value="<?php echo $op->id?>||<?php echo $field_value;?>"> <?php echo $field_value;?>
				</div>
				<?php
				if($j==2){
					$j = 0;
					?>
					</div><div class="row-fluid">
					<?php
				}
			}
			
			?>
		</div>
		<input type="hidden" name="field_<?php echo $sid?>_<?php echo $eid?>_<?php echo $field->id?>_count" id="field_<?php echo $sid?>_<?php echo $eid?>_<?php echo $field->id?>_count" value="<?php echo count($optionArr)?>">
		<input type="hidden" name="field_<?php echo $sid?>_<?php echo $eid?>_<?php echo $field->id?>" id="field_<?php echo $sid?>_<?php echo $eid?>_<?php echo $field->id?>" value="">
		<input type="hidden" name="field_<?php echo $sid?>_<?php echo $eid?>_<?php echo $field->id?>_label" id="field_<?php echo $sid?>_<?php echo $eid?>_<?php echo $field->id?>_label" value="<?php echo $field->field_label?>">
		<input type="hidden" name="field_<?php echo $sid?>_<?php echo $eid?>_<?php echo $field->id?>_selected" id="field_<?php echo $sid?>_<?php echo $eid?>_<?php echo $field->id?>_selected" value="">
		<input type="hidden" name="field_<?php echo $sid?>_<?php echo $eid?>_<?php echo $field->id?>_required" id="field_<?php echo $sid?>_<?php echo $eid?>_<?php echo $field->id?>_required" value="<?php echo $field->required;?>">
		<?php
		//}
	}
	
	/**
	 * Services details
	 *
	 * @param unknown_type $order_id
	 * @param unknown_type $eid
	 */
	function orderDetails($order_id,$eid){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__app_sch_orders where id = '$order_id'");
		$order = $db->loadObject();
		$order_lang = $order->order_lang;
		$config = new JConfig();
		$offset = $config->offset;
		date_default_timezone_set($offset);
		//check extra fields
		$db->setQuery("Select * from #__app_sch_fields where field_area = '1' and published = '1' order by ordering");
		$fields = $db->loadObjectList();
		if(count($fields) > 0){
			for($i=0;$i<count($fields);$i++){
				$field = $fields[$i];
				$db->setQuery("Select count(id) from #__app_sch_order_options where order_id = '$order_id' and field_id = '$field->id'");
				$count = $db->loadResult();
				if($field->field_type == 0){
					$db->setQuery("Select fvalue from #__app_sch_field_data where order_id = '$order_id' and fid = '$field->id'");
					$fvalue = $db->loadResult();
					if($fvalue != ""){
						echo OSBHelper::getLanguageFieldValueOrder($field,'field_label',$order_lang);?>:<?php echo $fvalue;?><BR /><?php
					}
				}
				if($count > 0){
					if($field->field_type == 1){
						$db->setQuery("Select option_id from #__app_sch_order_options where order_id = '$order_id' and field_id = '$field->id'");
						$option_id = $db->loadResult();
						$db->setQuery("Select * from #__app_sch_field_options where id = '$option_id'");
						$optionvalue = $db->loadObject();
						?>
						<?php echo OSBHelper::getLanguageFieldValueOrder($field,'field_label',$order_lang);?>:
						<?php
						$field_data = OSBHelper::getLanguageFieldValueOrder($optionvalue,'field_option',$order_lang); //$optionvalue->field_option;
						if($optionvalue->additional_price > 0){
							$field_data.= " - ".$optionvalue->additional_price." ".$configClass['currency_format'];
						}
						echo $field_data;
						?>
						<BR />
						<?php
					}elseif($field->field_type == 2){
						$db->setQuery("Select option_id from #__app_sch_order_options where order_id = '$order_id' and field_id = '$field->id'");
						$option_ids = $db->loadObjectList();
						$fieldArr = array();
						for($j=0;$j<count($option_ids);$j++){
							$oid = $option_ids[$j];
							$db->setQuery("Select * from #__app_sch_field_options where id = '$oid->option_id'");
							$optionvalue = $db->loadObject();
							//$field_data = $optionvalue->field_option;
							$field_data = OSBHelper::getLanguageFieldValueOrder($optionvalue,'field_option',$order_lang);
							if($optionvalue->additional_price > 0){
								$field_data.= " - ".$optionvalue->additional_price." ".$configClass['currency_format'];
							}
							$fieldArr[] = $field_data;
						}
						?>
						<?php echo OSBHelper::getLanguageFieldValueOrder($field,'field_label',$order_lang);?>:
						<?php
						echo implode(", ",$fieldArr);
						?>
						<BR />
						<?php
					}
				}
			}
		}
		
		$query = "Select a.*,b.id as bid,b.start_time,b.end_time,b.booking_date,b.additional_information,c.id as eid,c.employee_name from #__app_sch_services as a"
				." inner join #__app_sch_order_items as b on b.sid = a.id"
				." inner join #__app_sch_employee as c on c.id  = b.eid "
				." where b.order_id = '$order_id'";
				//. HelperOSappscheduleCommon::returnAccessSql('a');
		if($eid > 0){
			$query .= " and b.eid = '$eid'";
		}
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		if(count($rows) > 0){
			?>
			<table  width="100%">
			<tr>
				<td width="25%" align="left" style="font-size:11px;color:gray;border-bottom:1px dotted #D0C5C5 !important;font-weight:bold;">
					<?php echo JText::_('OS_SERVICE_NAME')?>
				</td>
				<td width="10%" align="left" style="font-size:11px;color:gray;border-bottom:1px dotted #D0C5C5 !important;font-weight:bold;">
					<?php echo JText::_('OS_BOOKING_DATE')?>
				</td>
				<td width="10%" align="left" style="font-size:11px;color:gray;border-bottom:1px dotted #D0C5C5 !important;font-weight:bold;">
					<?php echo JText::_('OS_START_TIME')?>
				</td>
				<td width="10%" align="left" style="font-size:11px;color:gray;border-bottom:1px dotted #D0C5C5 !important;font-weight:bold;">
					<?php echo JText::_('OS_END_TIME')?>
				</td>
				<td width="55%" align="left" style="font-size:11px;color:gray;border-bottom:1px dotted #D0C5C5 !important;font-weight:bold;">
					<?php echo JText::_('OS_ADDITIONAL_INFORMATION')?>
				</td>
			</tr>
			<?php
			for($i1=0;$i1<count($rows);$i1++){
				$row = $rows[$i1];
				?>
				<tr>
					<td width="25%" align="left" style="font-size:11px;color:gray;border-bottom:1px dotted #D0C5C5 !important;">
						<b><?php echo OSBHelper::getLanguageFieldValueOrder($row,'service_name',$order_lang); //$row->service_name;?></b>
					</td>
					<td width="10%" align="left" style="font-size:11px;color:gray;border-bottom:1px dotted #D0C5C5 !important;">
						<?php
							//echo intval(date("d",$row->start_time))."/".intval(date("m",$row->start_time))."/".intval(date("Y",$row->start_time));
							echo date($configClass['date_format'],$row->start_time);
						?>
					</td>
					<td width="10%" align="left" style="font-size:11px;color:gray;border-bottom:1px dotted #D0C5C5 !important;">
						<?php
							//echo date("H:i",$row->start_time);
							echo date($configClass['time_format'],$row->start_time);
						?>
					</td>
					<td width="10%" align="left" style="font-size:11px;color:gray;border-bottom:1px dotted #D0C5C5 !important;">
						<?php
							//echo date("H:i",$row->end_time);
							echo date($configClass['time_format'],$row->end_time);
						?>
					</td>
					<td width="55%" align="left" style="font-size:11px;color:gray;border-bottom:1px dotted #D0C5C5 !important;">
						<?php
							echo JText::_('OS_EMPLOYEE').": <B>".$row->employee_name."</B>";
							$db->setQuery("Select a.* from #__app_sch_venues as a inner join #__app_sch_employee_service as b on b.vid = a.id where b.employee_id = '$row->eid' and b.service_id = '$row->id'");
							$venue = $db->loadObject();
							if(OSBHelper::getLanguageFieldValueOrder($venue,'address',$order_lang) != ""){
								echo "<BR />";
								echo JText::_('OS_VENUE').": <B>".OSBHelper::getLanguageFieldValueOrder($venue,'address',$order_lang)."</B>";
							}
						?>
						<BR />
						<?php
							//echo $row->additional_information;
							$db->setQuery("Select * from #__app_sch_fields where field_area = '0' and published = '1' order by ordering");
							$fields = $db->loadObjectList();
							if(count($fields) > 0){
								for($i=0;$i<count($fields);$i++){
									$field = $fields[$i];
									$db->setQuery("Select count(id) from #__app_sch_order_field_options where order_item_id = '$row->bid' and field_id = '$field->id'");
									$count = $db->loadResult();
									if($count > 0){
										if($field->field_type == 1){
											$db->setQuery("Select option_id from #__app_sch_order_field_options where order_item_id = '$row->bid' and field_id = '$field->id'");
											$option_id = $db->loadResult();
											$db->setQuery("Select * from #__app_sch_field_options where id = '$option_id'");
											$optionvalue = $db->loadObject();
											?>
											<?php echo OSBHelper::getLanguageFieldValueOrder($field,'field_label',$order_lang); //$field->field_label;?>:
											<?php
											$field_data = OSBHelper::getLanguageFieldValueOrder($optionvalue,'field_option',$order_lang); //$optionvalue->field_option;
											if($optionvalue->additional_price > 0){
												$field_data.= " - ".$optionvalue->additional_price." ".$configClass['currency_format'];
											}
											echo $field_data;
											echo "<BR />";
										}elseif($field->field_type == 2){
											$db->setQuery("Select option_id from #__app_sch_order_field_options where order_item_id = '$row->bid' and field_id = '$field->id'");
											$option_ids = $db->loadObjectList();
											$fieldArr = array();
											for($j=0;$j<count($option_ids);$j++){
												$oid = $option_ids[$j];
												$db->setQuery("Select * from #__app_sch_field_options where id = '$oid->option_id'");
												$optionvalue = $db->loadObject();
												$field_data = OSBHelper::getLanguageFieldValueOrder($optionvalue,'field_option',$order_lang); //$optionvalue->field_option;
												if($optionvalue->additional_price > 0){
													$field_data.= " - ".$optionvalue->additional_price." ".$configClass['currency_format'];
												}
												$fieldArr[] = $field_data;
											}
											?>
											<?php echo OSBHelper::getLanguageFieldValueOrder($field,'field_label',$order_lang); //$field->field_label;?>:
											<?php
											echo implode(", ",$fieldArr);
											echo "<BR />";
										}
									}
								}
							}
						?>
					</td>
				</tr>
				<?php
			}
			?>
			</table>
			<?php
            if($configClass['use_qrcode']){
                if(!file_exists(JPATH_ROOT.'/media/com_osservicesbooking/qrcodes/'.$order_id.'.png')){
                    OSBHelper::generateQrcode($order_id);
                }
                $imgTag = '<img src="'.JUri::root().'media/com_osservicesbooking/qrcodes/'.$order_id.'.png" border="0" />';
                echo "<BR />";
                echo $imgTag;
            }

		}
	}
	
	/**
	 * Services details
	 *
	 * @param unknown_type $order_id
	 * @param unknown_type $eid
	 */
	function orderItemDetails($order_id,$order_item_id){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__app_sch_orders where id = '$order_id'");
        $order = $db->loadObject();
		//check extra fields
		$db->setQuery("Select * from #__app_sch_fields where field_area = '1' and published = '1' order by ordering");
		$fields = $db->loadObjectList();
		if(count($fields) > 0){
			for($i=0;$i<count($fields);$i++){
				$field = $fields[$i];
				$db->setQuery("Select count(id) from #__app_sch_order_options where order_id = '$order_id' and field_id = '$field->id'");
				$count = $db->loadResult();
				if($count > 0){
					if($field->field_type == 1){
						$db->setQuery("Select option_id from #__app_sch_order_options where order_id = '$order_id' and field_id = '$field->id'");
						$option_id = $db->loadResult();
						$db->setQuery("Select * from #__app_sch_field_options where id = '$option_id'");
						$optionvalue = $db->loadObject();
						?>
						<?php echo OSBHelper::getLanguageFieldValueOrder($field,'field_label',$order->order_lang);//$field->field_label;?>:
						<?php
						$field_data = OSBHelper::getLanguageFieldValueOrder($optionvalue,'field_option',$order->order_lang); //$optionvalue->field_option;
						if($optionvalue->additional_price > 0){
							$field_data.= " - ".$optionvalue->additional_price." ".$configClass['currency_format'];
						}
						echo $field_data;
						?>
						<BR />
						<?php
					}elseif($field->field_type == 2){
						$db->setQuery("Select option_id from #__app_sch_order_options where order_id = '$order_id' and field_id = '$field->id'");
						$option_ids = $db->loadObjectList();
						$fieldArr = array();
						for($j=0;$j<count($option_ids);$j++){
							$oid = $option_ids[$j];
							$db->setQuery("Select * from #__app_sch_field_options where id = '$oid->option_id'");
							$optionvalue = $db->loadObject();
							$field_data = OSBHelper::getLanguageFieldValueOrder($optionvalue,'field_option',$order->order_lang); //$optionvalue->field_option;
							if($optionvalue->additional_price > 0){
								$field_data.= " - ".$optionvalue->additional_price." ".$configClass['currency_format'];
							}
							$fieldArr[] = $field_data;
						}
						?>
						<?php echo OSBHelper::getLanguageFieldValueOrder($field,'field_label',$order->order_lang);//$field->field_label;?>:
						<?php
						echo implode(", ",$fieldArr);
						?>
						<BR />
						<?php
					}
				}
			}
		}
		
		$query = "Select a.*,b.id as bid,b.start_time,b.end_time,b.booking_date,b.additional_information,c.id as eid, c.employee_name from #__app_sch_services as a"
				." inner join #__app_sch_order_items as b on b.sid = a.id"
				." inner join #__app_sch_employee as c on c.id  = b.eid "
				." where b.id = '$order_item_id'";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		if(count($rows) > 0){
			?>
			<table  width="100%">
			<tr>
				<td width="25%" align="left" style="font-size:11px;color:gray;border-bottom:1px dotted #D0C5C5 !important;font-weight:bold;">
					<?php echo JText::_('OS_SERVICE_NAME')?>
				</td>
				<td width="10%" align="left" style="font-size:11px;color:gray;border-bottom:1px dotted #D0C5C5 !important;font-weight:bold;">
					<?php echo JText::_('OS_BOOKING_DATE')?>
				</td>
				<td width="10%" align="left" style="font-size:11px;color:gray;border-bottom:1px dotted #D0C5C5 !important;font-weight:bold;">
					<?php echo JText::_('OS_START_TIME')?>
				</td>
				<td width="10%" align="left" style="font-size:11px;color:gray;border-bottom:1px dotted #D0C5C5 !important;font-weight:bold;">
					<?php echo JText::_('OS_END_TIME')?>
				</td>
				<td width="55%" align="left" style="font-size:11px;color:gray;border-bottom:1px dotted #D0C5C5 !important;font-weight:bold;">
					<?php echo JText::_('OS_ADDITIONAL_INFORMATION')?>
				</td>
			</tr>
			<?php
			for($i1=0;$i1<count($rows);$i1++){
				$row = $rows[$i1];
				?>
				<tr>
					<td width="25%" align="left" style="font-size:11px;color:gray;border-bottom:1px dotted #D0C5C5 !important;">
						<b><?php echo $row->service_name;?></b>
					</td>
					<td width="10%" align="left" style="font-size:11px;color:gray;border-bottom:1px dotted #D0C5C5 !important;">
						<?php
							//echo intval(date("d",$row->start_time))."/".intval(date("m",$row->start_time))."/".intval(date("Y",$row->start_time));
							echo date($configClass['date_format'],$row->start_time);
						?>
					</td>
					<td width="10%" align="left" style="font-size:11px;color:gray;border-bottom:1px dotted #D0C5C5 !important;">
						<?php
							//echo date("H:i",$row->start_time);
							echo date($configClass['time_format'],$row->start_time);
						?>
					</td>
					<td width="10%" align="left" style="font-size:11px;color:gray;border-bottom:1px dotted #D0C5C5 !important;">
						<?php
							//echo date("H:i",$row->end_time);
							echo date($configClass['time_format'],$row->end_time);
						?>
					</td>
					<td width="55%" align="left" style="font-size:11px;color:gray;border-bottom:1px dotted #D0C5C5 !important;">
						<?php
							echo JText::_('OS_EMPLOYEE').": <B>".$row->employee_name."</B>";
						?>
						<BR />
						<?php
							$db->setQuery("Select a.* from #__app_sch_venues as a inner join #__app_sch_employee_service as b on b.vid = a.id where b.employee_id = '$row->eid' and b.service_id = '$row->bid'");
							$venue = $db->loadObject();
							if($venue->address != ""){
								echo JText::_('OS_VENUE').": <B>".$venue->address."</B>";
								echo "<BR />";
							}
							//echo $row->additional_information;
							$db->setQuery("Select * from #__app_sch_fields where field_area = '0' and published = '1' order by ordering");
							$fields = $db->loadObjectList();
							if(count($fields) > 0){
								for($i=0;$i<count($fields);$i++){
									$field = $fields[$i];
									$db->setQuery("Select count(id) from #__app_sch_order_field_options where order_item_id = '$row->bid' and field_id = '$field->id'");
									$count = $db->loadResult();
									if($count > 0){
										if($field->field_type == 1){
											$db->setQuery("Select option_id from #__app_sch_order_field_options where order_item_id = '$row->bid' and field_id = '$field->id'");
											$option_id = $db->loadResult();
											$db->setQuery("Select * from #__app_sch_field_options where id = '$option_id'");
											$optionvalue = $db->loadObject();
											?>
											<?php echo OSBHelper::getLanguageFieldValueOrder($field,'field_label',$order->order_lang);//$field->field_label;?>:
											<?php
											$field_data = OSBHelper::getLanguageFieldValueOrder($optionvalue,'field_option',$order->order_lang);//$optionvalue->field_option;
											if($optionvalue->additional_price > 0){
												$field_data.= " - ".$optionvalue->additional_price." ".$configClass['currency_format'];
											}
											echo $field_data;
											echo "<BR />";
										}elseif($field->field_type == 2){
											$db->setQuery("Select option_id from #__app_sch_order_field_options where order_item_id = '$row->bid' and field_id = '$field->id'");
											$option_ids = $db->loadObjectList();
											$fieldArr = array();
											for($j=0;$j<count($option_ids);$j++){
												$oid = $option_ids[$j];
												$db->setQuery("Select * from #__app_sch_field_options where id = '$oid->option_id'");
												$optionvalue = $db->loadObject();
												$field_data = OSBHelper::getLanguageFieldValueOrder($optionvalue,'field_option',$order->order_lang); //$optionvalue->field_option;
												if($optionvalue->additional_price > 0){
													$field_data.= " - ".$optionvalue->additional_price." ".$configClass['currency_format'];
												}
												$fieldArr[] = $field_data;
											}
											?>
											<?php echo OSBHelper::getLanguageFieldValueOrder($field,'field_label',$order->order_lang);//$field->field_label;?>:
											<?php
											echo implode(", ",$fieldArr);
											echo "<BR />";
										}
									}
								}
							}
						?>
					</td>
				</tr>
				<?php
			}
			?>
			</table>
			<?php	
		}
	}
	
	/**
	 * Show payment failure information
	 *
	 */
	function failure(){
		global $mainframe,$configClass;
		$reason =  isset($_SESSION['reason']) ? $_SESSION['reason'] : '';
		if (!$reason) {
			$reason = JRequest::getVar('failReason', '') ;
		}
		HTML_OsAppscheduleDefault::failureHtml($reason);
	}
	
	
	/**
	 * List all orders history 
	 *
	 */
	function orderHistory(){
		global $mainframe,$configClass;
		$config = new JConfig();
		$offset = $config->offset;
		date_default_timezone_set($offset);
		$user = JFactory::getUser();
		$db   = JFactory::getDbo();
		if(intval($user->id) == 0){
			$mainframe->redirect(JURI::root()."index.php",JText::_('OS_YOU_DONT_HAVE_PERMISSION_TO_GO_TO_THIS_AREA'));
		}
		$db->setQuery("Select * from #__app_sch_orders where user_id = '$user->id' order by order_date desc");
		$orders = $db->loadObjectList();
		
		if(count($orders) > 0){
			for($i=0;$i<count($orders);$i++){
				$order = $orders[$i];
				$db->setQuery("Select * from #__app_sch_orders where id = '$order->id'");
				$orderdetails = $db->loadObject();
				$order_lang = $orderdetails->order_lang;
				$suffix = "";
				$lgs = OSBHelper::getLanguages();
				$translatable = JLanguageMultilang::isEnabled() && count($lgs);
				$default_language = OSBHelper::getDefaultLanguage();
				if($order_lang == ""){
					$order_lang = $default_language;
				}
				if($translatable){
					//$suffix = self::getFieldSuffix();
					if($default_language != $order_lang){
						$langugeArr = explode("-",$order_lang);
						$suffix = "_".$langugeArr[0];
					}
				}
				//get services information
				$db->setQuery("SELECT a.id,a.service_name$suffix as service_name FROM #__app_sch_services AS a INNER JOIN #__app_sch_order_items AS b ON a.id = b.sid WHERE b.order_id = '$order->id'");
				$rows = $db->loadObjectList();
				$service = "";
				if(count($rows) > 0){
					for($j=0;$j<count($rows);$j++){
						$row = $rows[$j];
						$service .= $row->service_name.", ";
					}
					$service = substr($service,0,strlen($service)-2);
				}
				$order->service = $service;
				
			}
		}
		HTML_OsAppscheduleDefault::listOrdersHistory($orders);
	}
	
	/**
	 * Get List of Order
	 *
	 * @param unknown_type $order_id
	 */
	function getListOrderServices($order_id,$checkin){
		global $mainframe;
		$db = JFactory::getDbo();
		$db->setQuery("Select a.id as order_item_id,a.*,b.id as sid,b.*,c.id as eid,c.employee_name from #__app_sch_order_items as a inner join #__app_sch_services as b on b.id = a.sid inner join #__app_sch_employee as c on c.id = a.eid where a.order_id = '$order_id'");
		$rows = $db->loadObjectList();
		$db->setQuery("Select * from #__app_sch_orders where id = '$order_id'");
		$order = $db->loadObject();
		HTML_OsAppscheduleDefault::listOrderServices($rows,$order,$checkin);
	}
	
	/**
	 * Remove Order
	 *
	 * @param unknown_type $order_id
	 */
	function removeOrder($order_id){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__app_sch_order_items where order_id = '$order_id'");
		$items = $db->loadObjectList();
		if(count($items) > 0){
			for($i=0;$i<count($items);$i++){
				$item = $items[$i];
				$db->setQuery("DELETE FROM #__app_sch_order_field_options WHERE order_item_id = '$item->id'");
				$db->query();
			}
		}

		//send notification email
		HelperOSappscheduleCommon::sendCancelledEmail($order_id);
		HelperOSappscheduleCommon::sendSMS('cancel',$order_id);
		HelperOSappscheduleCommon::sendEmployeeEmail('employee_order_cancelled',$order_id,$eid);

		$db->setQuery("DELETE FROM #__app_sch_orders WHERE id = '$order_id'");
		$db->query();
		$db->setQuery("DELETE FROM #__app_sch_order_options WHERE order_id = '$order_id'");
		$db->query();
		$db->setQuery("DELETE FROM #__app_sch_order_items WHERE order_id = '$order_id'");
		$db->query();
		
		
		$mainframe->redirect(JURI::root()."index.php?option=com_osservicesbooking&task=default_customer&Itemid=".Jrequest::getVar('Itemid'));
	}
	
	/**
	 * Show Google Map
	 *
	 */
	function showMap(){
		global $mainframe,$configClass;
		$vid = JRequest::getVar('vid',0);
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__app_sch_venues where id = '$vid'");
		$venue = $db->loadObject();
		HTML_OsAppscheduleDefault::showMap($venue);
	}

	
	function listAllEmployees(){
		global $mainframe,$configClass;
		$document = JFactory::getDocument();
		$menus = JSite::getMenu();
		$menu = $menus->getActive();
		$pagetitle = "";
        if (is_object($menu)) {
            $params = new JRegistry() ;
            $params->loadString($menu->params);
            if($params->get('page_title') != ""){
                $document->setTitle($params->get('page_title'));
            }else{
                $document->setTitle($configClass['business_name'].' | '.JText::_('OS_LIST_ALL_EMPLOYEES'));
            }
            $list_type = $params->get('list_type',0);
        }else{
            $document->setTitle($configClass['business_name'].' | '.JText::_('OS_LIST_ALL_EMPLOYEES'));
        }
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__app_sch_employee where published = '1' order by employee_name");
		$employees = $db->loadObjectList();
		HTML_OsAppscheduleDefault::listEmployees($employees,$params,$list_type);
	}

    /**
     * Check in
     */
    function checkIn(){
        $user = JFactory::getUser();
        if ($user->authorise('osservicesbooking.checkin_management', 'com_osservicesbooking')) {
            //check in for all item
            $id = JRequest::getInt('id',0);
            OsAppscheduleDefault::orderDetailsForm($id,1);
        }else{
            Jerror::raiseError(500,JText::_('OS_YOU_DONT_HAVE_PERMISSION_TO_GO_TO_THIS_AREA'));
        }
    }


    //for testing

    function testSMS(){
		global $mainframe,$configClass;
		$to = "";
		$smscontent = "Test from DEV";
		$baseurl ="http://api.clickatell.com";
		$url  =  $baseurl."/http/auth?user=".$configClass['clickatell_username'];
		$url .= "&password=".$configClass['clickatell_password'];
		$url .= "&api_id=".$configClass['clickatell_api'];	
		$ret  = file($url);
		// split our response. return string is on first line of the data returned
		$sess = explode(":",$ret[0]);
		if ($sess[0] == "OK"){
			
			$sess_id = trim($sess[1]); // remove any whitespace
			 //echo $message;
			if($configClass['clickatell_senderid'] != ""){
				$sender = "&from=".$configClass['clickatell_senderid'];
			} else {
				$sender = "";
			}
			if($configClass['clickatell_enable_unicode'] == "0"){
				$url = $baseurl."/http/sendmsg?session_id=".$sess_id."&to=".$to.$sender."&concat=3&text=".$smscontent;
			} else {
				$url = $baseurl."/http/sendmsg?session_id=".$sess_id."&to=".$to.$sender."&unicode=1&concat=3&text=".self::utf16urlencode($smscontent);
			}
			// do sendmsg call
			$ret = file($url);
			$send = explode(":",$ret[0]);
			echo $send[0];
			if ($send[0] == "ID"){
				$returnCode = $send[1];
			} else {
				$returnCode = $send[1];
			}
		}//sess ok
	}



	
	function updateGoogleCalendar($order_id){
		global $mainframe,$configClass;
		OSBHelper::updateGoogleCalendar($order_id);
	}
	
	/**
	 * Add Event to Google Calendar
	 *
	 */
	function addEventToGCalendar(){
		global $mainframe;
		
		$eid = JRequest::getVar('eid',1);
		$current = OSBHelper::getCurrentDate();
		$gmttime =  strtotime(JFactory::getDate('now'));
		$distance = round(($current - $gmttime)/3600);
		if($distance < 10){
			$distance = "0".$distance;
		}
		if($distance > 0){
			$distance =  "+".$distance;
		}
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__app_sch_employee where id = '$eid'");
		$employee  = $db->loadObject();
		$gusername = $employee->gusername;
		$gusername.= "@gmail.com";
		$gpassword = $employee->gpassword;
		
		$path = JPATH_COMPONENT_SITE.DS."google-api-php-client-master".DS."src".DS."Google";
		set_include_path(get_include_path() . PATH_SEPARATOR . $path);
		//echo $path;
		if(!file_exists ( $path.DS.'Client.php' )){
			echo "ABPro set to use Google Calendar but the Google Library is not installed. See <a href='http://appointmentbookingpro.com/index.php?option=com_content&view=article&id=89&Itemid=190' target='_blank'>Tutorial</a>";
			exit;
		}	
		require_once $path.DS."Client.php";
	    require_once $path.DS."Service.php";
	    
		try {
	 	    $client = new Google_Client();
			$client->setApplicationName("Calendar Project");
			$client->setClientId("");
			$client->setAssertionCredentials( 
				new Google_Auth_AssertionCredentials(
					"",
					array("https://www.googleapis.com/auth/calendar"),
					file_get_contents(JPATH_COMPONENT_SITE.DS."Calendar Project-56943acdc616.p12"),
					'notasecret','http://oauth.net/grant_type/jwt/1.0/bearer',false,false
				)
			);
		}
		catch (RuntimeException $e) {
		    return 'Problem authenticating Google Calendar:'.$e->getMessage();
		}
		
		// validate input
		$title = "Having lunch with company";
		$start_date = "20";
		$start_month = "12";
		$start_year = "2014";
		
		$end_date = "20";
		$end_month = "12";
		$end_year = "2014";
		$where = "Hanoi";
		//$start = date(DATE_ATOM, mktime(14, 14, 0, $start_month,$start_date, $start_year));
		//$end = date(DATE_ATOM, mktime(15, 15, 0, $end_month, $end_date, $end_year));
		$start =  "2014-12-31T08:00:00+00:00";
		$end   =  "2014-12-31T09:00:00+00:00";
		
		$service = new Google_Service_Calendar($client);		
		$newEvent = new Google_Service_Calendar_Event();
		$newEvent->setSummary($title);
		$newEvent->setLocation($where);
		$newEvent->setDescription($desc);
		$event_start = new Google_Service_Calendar_EventDateTime();
		$event_start->setDateTime($start);
		$newEvent->setStart($event_start);
		$event_end = new Google_Service_Calendar_EventDateTime();
		$event_end->setDateTime($end);
		$newEvent->setEnd($event_end);
		
		$createdEvent = null;
		//if($this->cal_id != ""){
			try {
				$createdEvent = $service->events->insert("", $newEvent);
				$createdEvent_id= $createdEvent->getId();
			} catch (Google_ServiceException $e) {
				logIt("svgcal_v3,".$e->getMessage()); 
//				echo $e->getMessage();
//				exit;
			}			
			
//		$createdEvent = $gdataCal->insertEvent($newEvent, "http://www.google.com/calendar/feeds/".$this->cal_id."/private/full");
			
		echo  $createdEvent_id;
		
		
		
		// construct event object
		// save to server  
		/*    
		try {
			$event = $gcal->newEventEntry();        
			$event->title = $gcal->newTitle($title);        
			$when = $gcal->newWhen();
			$when->startTime = $start;
			$when->endTime = $end;
			$event->when = array($when);        
			$gcal->insertEvent($event);   
		} catch (Zend_Gdata_App_Exception $e) {
			echo "Error: " . $e->getResponse();
		}
		*/
		echo 'Event successfully added!';    
	}
	
	function testDate(){
		$config = new JConfig();
		$offset = $config->offset;
		echo date("H:i",strtotime(JFactory::getDate('now',$offset)));
	}
	
	function testRepeat(){
		$return = HelperOSappscheduleCalendar::calculateBookingDate('2013-02-21','2013-03-29',2);
		print_r($return);
	}


}
?>