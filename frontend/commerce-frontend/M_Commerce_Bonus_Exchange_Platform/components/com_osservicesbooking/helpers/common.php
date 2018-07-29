<?php
/*------------------------------------------------------------------------
# common.php - Ossolution Services Booking
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
class HelperOSappscheduleCommon{

	public function showDescription($desc){
		$descArr = explode(" ",$desc);
		if(count($descArr) > 30){
			for($i=0;$i<30;$i++){
				echo $descArr[$i]." ";
			}
			echo "..";
		}else{
			echo $desc;
		}
	}

	/**
	 * Send Cancelled Email
	 *
	 * @param unknown_type $orderId
	 */
	function sendCancelledEmail($orderId){
		global $configClass;
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__app_sch_emails where email_key like 'admin_order_cancelled'");
		$email = $db->loadObject();
		$sbj = $email->email_subject;
		$body = $email->email_content;
		$body = stripslashes($body);
		$body = OSBHelper::convertImgTags($body);
		$db->setQuery("SELECT * FROM #__app_sch_orders WHERE id = '$orderId'");
		$order = $db->loadObject();
		
		$db->setQuery("SELECT * FROM #__app_sch_order_items WHERE order_id = '$orderId'");
		$items = $db->loadObjectList();
		
		ob_start();
		OsAppscheduleDefault::orderDetails($orderId,0);
		$service = ob_get_contents();
		ob_end_clean();
		
		$order_name = $order->order_name;
		$order_phone = $order->order_phone;
		$order_country = $order->order_country;
		$order_state = $order->order_state;
		$order_zip = $order->order_zip;
		$order_city = $order->order_city;
		$order_address = $order->order_address;
		$order_notes = $order->order_notes;
		
		$deposit = $configClass['currency_symbol']." ".number_format($order->order_upfront,2,'.','')." ".$configClass['currencyformat'];
		
		$tax = $configClass['currency_symbol']." ".number_format($order->order_tax,2,'.','')." ".$configClass['currencyformat'];
		$total = $configClass['currency_symbol']." ".number_format($order->order_final_cost,2,'.','')." ".$configClass['currencyformat'];
		
		$body = str_replace("{Name}",$order_name,$body);
		$body = str_replace("{Email}",$order_email,$body);
		$body = str_replace("{Phone}",$order_phone,$body);
		$body = str_replace("{Country}",$order_country,$body);
		$body = str_replace("{State}",$order_state,$body);
		$body = str_replace("{Zip}",$order_zip,$body);
		$body = str_replace("{City}",$order_city,$body);
		$body = str_replace("{Address}",$order_address,$body);
		$body = str_replace("{Notes}",$order_notes,$body);
		
		$body = str_replace("{BookingID}",$orderId,$body);
		$body = str_replace("{Services}",$service,$body);
		$body = str_replace("{Deposit}",$deposit,$body);
		$body = str_replace("{Tax}",$tax,$body);
		$body = str_replace("{Total}",$total,$body);
		
		if($configClass['allow_cancel_request'] == 1){
		
			$cancellink = JURI::root()."index.php?option=com_osservicesbooking&task=default_cancelorder&id=".$orderId."&ref=".md5($orderId);
			$cancellink = str_replace("components/com_osservicesbooking/","",$cancellink);
			$cancellink = "<a href='$cancellink' title='".JText::_('OS_CLICK_HERE_TO_CANCEL_THE_BOOKING_REQUEST')."'>".$cancellink."</a>";
			$body = str_replace("{CancelURL}",$cancellink,$body);
		}else{
			$body = str_replace("{CancelURL}","",$body);
		}
		//echo $body;
		//die();
		$config = new JConfig();
		$mailfrom = $config->mailfrom;
		$fromname = $config->fromname;
		$order_email = $configClass['value_string_email_address'];
		$mailer = JFactory::getMailer();
		if(($sbj != "") and ($body != "") and ($mailfrom != "") and ($order_email != "")){
			$mailer->Sendmail($mailfrom,$fromname,$order_email,$sbj,$body,1);
		}
	}
	/**
	 * Send email
	 *
	 * @param unknown_type $email_type
	 */
	function sendEmail($email_type,$orderId){
		global $configClass;
		$db = JFactory::getDbo();
		$db->setQuery("SELECT * FROM #__app_sch_orders WHERE id = '$orderId'");
		$order = $db->loadObject();
		$lang = $order->order_lang;
		switch ($email_type){
			case "order_status_changed_to_customer":
				$db->setQuery("Select * from #__app_sch_emails where email_key like 'order_status_changed_to_customer'");
				$email = $db->loadObject();
				$sbj = OSBHelper::getLanguageFieldValueOrder($email,'email_subject',$lang);
				$body = OSBHelper::getLanguageFieldValueOrder($email,'email_content',$lang); //$email->email_content;
			break;
			case "confirm":
				$db->setQuery("Select * from #__app_sch_emails where email_key like 'confirmation_email'");
				$email = $db->loadObject();
				$sbj = OSBHelper::getLanguageFieldValueOrder($email,'email_subject',$lang);
				$body = OSBHelper::getLanguageFieldValueOrder($email,'email_content',$lang); //$email->email_content;
			break;
			case "payment":
				$db->setQuery("Select * from #__app_sch_emails where email_key like 'payment_accept'");
				$email = $db->loadObject();
				$sbj = OSBHelper::getLanguageFieldValueOrder($email,'email_subject',$lang);
				$body = OSBHelper::getLanguageFieldValueOrder($email,'email_content',$lang);
			break;
			case "reminder":
				$db->setQuery("Select * from #__app_sch_emails where email_key like 'booking_reminder'");
				$email = $db->loadObject();
				$sbj = OSBHelper::getLanguageFieldValueOrder($email,'email_subject',$lang);
				$body = OSBHelper::getLanguageFieldValueOrder($email,'email_content',$lang);
				
				$order_item_id = $orderId;
				$db->setQuery("Select order_id from #__app_sch_order_items where id = '$order_item_id'");
				$orderId = $db->loadResult();

				$db->setQuery("SELECT * FROM #__app_sch_orders WHERE id = '$orderId'");
				$order = $db->loadObject();

				$order_lang = $order->order_lang;
				if($order_lang == ""){
					$order_lang = OSPHelper::getDefaultLanguage();
				}
				$language = JFactory::getLanguage();
				$language->load('com_osservicesbooking', JPATH_SITE, $order_lang, true);
			break;
			case "admin":
				$db->setQuery("Select * from #__app_sch_emails where email_key like 'admin_notification'");
				$email = $db->loadObject();
				$sbj = $email->email_subject;
				$body = $email->email_content;
			break;
		}
		
		$body = stripslashes($body);
		$body = OSBHelper::convertImgTags($body);
		
		$db->setQuery("SELECT * FROM #__app_sch_order_items WHERE order_id = '$orderId'");
		$items = $db->loadObjectList();
		
		ob_start();
		if($email_type == "reminder"){
			OsAppscheduleDefault::orderItemDetails($orderId,$order_item_id);
		}else{
			OsAppscheduleDefault::orderDetails($orderId,0);	
		}
		$service = ob_get_contents();
		ob_end_clean();
		
		$order_name 	= $order->order_name;
		$order_email 	= $order->order_email;
		$order_phone 	= $order->order_phone;
		$order_country 	= $order->order_country;
		$order_state 	= $order->order_state;
		$order_zip 		= $order->order_zip;
		$order_city 	= $order->order_city;
		$order_address 	= $order->order_address;
		$order_notes 	= $order->order_notes;
		$order_status	= $order->order_status;
		
		$deposit 		= $configClass['currency_symbol']." ".number_format($order->order_upfront,2,'.','')." ".$configClass['currencyformat'];
		$tax 			= $configClass['currency_symbol']." ".number_format($order->order_tax,2,'.','')." ".$configClass['currencyformat'];
		$total 			= $configClass['currency_symbol']." ".number_format($order->order_final_cost,2,'.','')." ".$configClass['currencyformat'];
		
		$body = str_replace("{Name}",$order_name,$body);
		$body = str_replace("{Email}",$order_email,$body);
		$body = str_replace("{Phone}",$order_phone,$body);
		$body = str_replace("{Country}",$order_country,$body);
		$body = str_replace("{State}",$order_state,$body);
		$body = str_replace("{Zip}",$order_zip,$body);
		$body = str_replace("{City}",$order_city,$body);
		$body = str_replace("{Address}",$order_address,$body);
		$body = str_replace("{Notes}",$order_notes,$body);
		
		$body = str_replace("{BookingID}",$orderId,$body);
		$body = str_replace("{Services}",$service,$body);
		$body = str_replace("{Deposit}",$deposit,$body);
		$body = str_replace("{Tax}",$tax,$body);
		$body = str_replace("{Total}",$total,$body);
		
		$body = str_replace("{new_status}",OSBHelper::orderStatus(0,$order_status),$body);
		
		if($configClass['allow_cancel_request'] == 1){
			$cancellink = JURI::root()."index.php?option=com_osservicesbooking&task=default_cancelorder&id=".$orderId."&ref=".md5($orderId);
			$cancellink = str_replace("components/com_osservicesbooking/","",$cancellink);
			$cancellink = "<a href='$cancellink' title='".JText::_('OS_CLICK_HERE_TO_CANCEL_THE_BOOKING_REQUEST')."'>".$cancellink."</a>";
			$body = str_replace("{CancelURL}",$cancellink,$body);
		}else{
			$body = str_replace("{CancelURL}","",$body);
		}
		
		$config = new JConfig();
		$mailfrom = $config->mailfrom;
		$fromname = $config->fromname;
		if($email_type == "admin"){
			$order_email = $configClass['value_string_email_address'];
		}
		$attachment = array();
		if($email_type == "confirm"){
			if(($configClass['activate_invoice_feature'] == 1) and ($configClass['send_invoice_to_customer'] == 1)){
				//generate order pdf file
				$return = OSBHelper::generateOrderPdf($orderId);
				$attachment = array($return[0]);
			}
		}
		$cc = array();
		$bcc = array();
		$mailer = JFactory::getMailer();
		
		if(($sbj != "") and ($body != "") and ($mailfrom != "") and ($order_email != "")){
			if($email_type == "confirm"){
				if($mailer->Sendmail($mailfrom,$fromname,$order_email,$sbj,$body,1,$cc,$bcc,$attachment)){
					$db->setQuery("UPDATE #__app_sch_orders SET send_email = '1' WHERE id = '$orderId'");
					$db->query();
				}else{
					$db->setQuery("UPDATE #__app_sch_orders SET send_email = '0' WHERE id = '$orderId'");
					$db->query();
				}
			}else{
				$mailer->Sendmail($mailfrom,$fromname,$order_email,$sbj,$body,1,$cc,$bcc,$attachment);
			}
		}else{
			/*
			$db->setQuery("Select send_email from #__app_sch_orders WHERE id = '$orderId'");
			$send_email = $db->loadResult();
			if((int) $send_email == 0){

				$db->setQuery("UPDATE #__app_sch_orders SET send_email = '0' WHERE id = '$orderId'");
				$db->query();

			}
			*/
		}
	}
	
	/**
	 * Send the notification email to employee
	 *
	 * @param unknown_type $order_id
	 */
	function sendEmployeeEmail($email_type,$orderId,$eid){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__app_sch_emails where email_key like '$email_type'");
		$email = $db->loadObject();
		$sbj = $email->email_subject;
		$body = $email->email_content;
		$body = stripslashes($body);
		$body = OSBHelper::convertImgTags($body);
		$db = JFactory::getDbo();
		$db->setQuery("SELECT * FROM #__app_sch_orders WHERE id = '$orderId'");
		$order = $db->loadObject();
		
		$db->setQuery("SELECT * FROM #__app_sch_order_items WHERE order_id = '$orderId'");
		$items = $db->loadObjectList();
		
		$order_name = $order->order_name;
		$order_email = $order->order_email;
		$order_phone = $order->order_phone;
		$order_country = $order->order_country;
		$order_state = $order->order_state;
		$order_zip = $order->order_zip;
		$order_city = $order->order_city;
		$order_address = $order->order_address;
		$order_notes = $order->order_notes;
		$order_status = $order->order_status;
		
		$deposit = $configClass['currency_symbol']." ".number_format($order->order_upfront,2,'.','')." ".$configClass['currencyformat'];
		
		$tax = $configClass['currency_symbol']." ".number_format($order->order_tax,2,'.','')." ".$configClass['currencyformat'];
		$total = $configClass['currency_symbol']." ".number_format($order->order_final_cost,2,'.','')." ".$configClass['currencyformat'];
		
		$body = str_replace("{Name}",$order_name,$body);
		$body = str_replace("{Email}",$order_email,$body);
		$body = str_replace("{Phone}",$order_phone,$body);
		$body = str_replace("{Country}",$order_country,$body);
		$body = str_replace("{State}",$order_state,$body);
		$body = str_replace("{Zip}",$order_zip,$body);
		$body = str_replace("{City}",$order_city,$body);
		$body = str_replace("{Address}",$order_address,$body);
		$body = str_replace("{Notes}",$order_notes,$body);
		$body = str_replace("{newstatus}",OSBHelper::orderStatus(0,$order_status),$body);
		//$body = str_replace("{Services}",$service,$body);
		
		$query = "Select a.*,b.start_time,b.end_time,b.booking_date from #__app_sch_employee as a"
				." inner join #__app_sch_order_items as b on b.eid = a.id"
				." where b.order_id = '$orderId'"
				." and a.employee_send_email = '1'";
		if($eid > 0){
			$query .= " and a.id = '$eid'";
		}
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		if(count($rows) > 0){
			for($i=0;$i<count($rows);$i++){
				$row = $rows[$i];
				$body1 = $body;
				$email = $row->employee_email;
				$start_time = date($configClass['time_format'],$row->start_time);
				$end_time = date($configClass['time_format'],$row->end_time);
				$booking_date = $row->booking_date;
				$body1 = str_replace("{Starttime}",$start_time,$body1);
				$body1 = str_replace("{Endtime}",$end_time,$body1);
				$body1 = str_replace("{Bookingdate}",$booking_date,$body1);
				ob_start();
				OsAppscheduleDefault::orderDetails($orderId,$row->id);
				$service = ob_get_contents();
				ob_end_clean();
				$body1 = str_replace("{Services}",$service,$body1);
				$config = new JConfig();
				$mailfrom = $config->mailfrom;
				$fromname = $config->fromname;
				$mailer = JFactory::getMailer();
				if(($sbj != "") and ($body1 != "") and ($mailfrom != "") and ($email != "")){
					$mailer->Sendmail($mailfrom,$fromname,$email,$sbj,$body1,1);
				}
			}
		}
	}
	
	/**
	 * Send SMS
	 *
	 * @param unknown_type $key
	 * @param unknown_type $orderId
	 */
	function sendSMS($key,$orderId){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		//ready to prepare the sms content
		switch ($key){
			case "confirm":
				$smscontent = $configClass['sms_new_booking_to_customer'];
				$sms_phone	= self::getCustomerMobileNumber($orderId);
			break;
			case "payment": //for admin
				$smscontent = $configClass['sms_payment_complete_to_admin'];
				$sms_phone	= self::getAdminMobileNumber();
			break;
			case "reminder":
				$smscontent = $configClass['sms_reminder_notification'];
				$sms_phone	= self::getCustomerMobileNumber($orderId);
			break;
			case "admin":
				$smscontent = $configClass['sms_new_booking_to_admin'];
				$sms_phone	= self::getAdminMobileNumber();
			break;
			case "cancel":
				$smscontent = $configClass['sms_order_cancelled_notification'];
				$sms_phone	= self::getAdminMobileNumber();
			break;
			case "order_status_changed_to_customer":
				$smscontent = $configClass['order_status_changed_to_customer'];
				$sms_phone	= self::getCustomerMobileNumber($orderId);
			break;
		}
		
		$db->setQuery("SELECT * FROM #__app_sch_orders WHERE id = '$orderId'");
		$order = $db->loadObject();
		
		$smscontent = str_replace("{OrderID}",$orderId,$smscontent);
		$smscontent = str_replace("{business_name}",$configClass['business_name'],$smscontent);
		$smscontent = str_replace("{OrderStatus}",OSBHelper::orderStatus(0,$order->order_status),$smscontent);
		
		if($configClass['enable_clickatell'] == 1){ //enable Clickatell sms
			if(($configClass['clickatell_username'] != "") and ($configClass['clickatell_password'] != "") and ($configClass['clickatell_api'] != "")){
				$smscontent =  str_replace(" ","+",$smscontent);
				if(($smscontent != "") and ($sms_phone != "")){
					$sms_phone = str_replace("-", "", $sms_phone);	
					$sms_phone = str_replace("+", "", $sms_phone);	
					$sms_phone = str_replace(" ", "", $sms_phone);	
					$to = $sms_phone;
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
						//echo $send[0];
						if ($send[0] == "ID"){
							$returnCode = $send[1];
						} else {
							$returnCode = $send[1];
						}
					}//sess ok
				}//sms content and sms phone is not empty
			}//config ready end
		}//end ClickAtell
		
		if($configClass['enable_eztexting'] == 1){ //enable Clickatell sms
			if(($configClass['eztexting_username'] != "") and ($configClass['eztexting_password'] != "") and ($configClass['clickatell_api'] != "")){
				//$smscontent =  str_replace(" ","+",$smscontent);
				if(($smscontent != "") and ($sms_phone != "")){
					$sms_phone = str_replace("-", "", $sms_phone);	
					$sms_phone = str_replace("+", "", $sms_phone);	
					$sms_phone = str_replace(" ", "", $sms_phone);	
					//if(strlen($sms_phone)>10){
						//$sms_phone = substr($sms_phone, strlen($sms_phone)-10 );
					//}
					$ch=curl_init('https://app.eztexting.com/api/sending');
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch,CURLOPT_POST,1);
					curl_setopt($ch,CURLOPT_POSTFIELDS,"user=".$configClass['eztexting_username'].
								"&pass=".trim(OSBHelper::encrypt_decrypt('decrypt', $configClass['eztexting_password'])).
								"&phonenumber=".$sms_phone.
								"&message=".$smscontent.
								"&express=1");
					curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
					$data = curl_exec($ch);
					//print($data); /* result of API call*/
					switch ($data) {
					    case 1:
							$returnCode = JText::_('OS_EZTEXTING_CODE_1');
							break;
						case -1:
							$returnCode = JText::_('OS_EZTEXTING_CODE_ERR_1');
							break;
						case -2:
							$returnCode = JText::_('OS_EZTEXTING_CODE_ERR_2');
							break;
						case -5:
							$returnCode = JText::_('OS_EZTEXTING_CODE_ERR_5');
							break;
						case -7:
							$returnCode = JText::_('OS_EZTEXTING_CODE_ERR_7');
							break;
						case -104:
							$returnCode = JText::_('OS_EZTEXTING_CODE_ERR_104');
							break;
						case -106:
							$returnCode = JText::_('OS_EZTEXTING_CODE_ERR_106');
							break;
						case -10:
							$returnCode = JText::_('OS_EZTEXTING_CODE_ERR_10');
							break;
					}
					if($data == 1){
						//return true;	
					} else {
						//return false;	
					}
					//}//sess ok
				}//sms content and sms phone is not empty
			}//config ready end
		}//end EzTexing
	}
	
	public static function utf16urlencode($str){
	    $str = mb_convert_encoding($str, 'UTF-16', 'UTF-8');
	    $out ='';
	    for ($i = 0; $i < mb_strlen($str, 'UTF-16'); $i++)
	    {
	        $out .= bin2hex(mb_substr($str, $i, 1, 'UTF-16'));
	    }
	    return $out;
	}
	
	public static function getCustomerMobileNumber($orderID){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$phone_mobile = "";
		$db->setQuery("Select dial_code,order_phone from #__app_sch_orders where id = '$orderID'");
		$phone_number = $db->loadObject();
		if($phone_number->dial_code != ""){
			$phone_mobile = $phone_number->dial_code.$phone_number->order_phone;
		}else{
			$phone_mobile = $configClass['clickatell_defaultdialingcode'].$phone_number->order_phone;
		}
		return $phone_mobile;
	}
	
	public static function getAdminMobileNumber(){
		global $configClass;
		$phone_mobile = "";
		if($configClass['mobile_notification'] != ""){
			$phone_mobile = $configClass['clickatell_defaultdialingcode'].$configClass['mobile_notification'];
		}
		return $phone_mobile;
	}
	
	
	function checkEmployee(){
		$user = JFactory::getUser();
		if(intval($user->id) == 0){
			return false;
		}else{
			$db = JFactory::getDbo();
			$db->setQuery("Select count(id) from #__app_sch_employee where user_id = '$user->id'");
			$count = $db->loadResult();
			if($count > 0){
				return true;
			}else{
				return false;
			}
		}
	}
	
	function getEmployeeID(){
		$user = JFactory::getUser();
		$db = JFactory::getDbo();
		$db->setQuery("Select id from #__app_sch_employee where user_id = '$user->id'");
		return $db->loadResult();
	}
	
	function getRealTime(){
		$config = new JConfig();
		$offset = $config->offset;
		date_default_timezone_set($offset);
		return strtotime(date('Y-m-d H:i:s'));
	}
	
	function removeTempSlots(){
		global $mainframe;
		$unique_cookie = $_COOKIE['unique_cookie'];
		$db = JFactory::getDbo();
		$db->setQuery("DELETE FROM #__app_sch_temp_temp_order_items WHERE unique_cookie LIKE '$unique_cookie'");
		$db->query();
	}
	
	/**
	 * Check to see whether the ideal payment plugin installed and activated
	 * @return boolean
	 */
	function idealEnabled() {
		$db = & JFactory::getDBO();
		$sql = 'SELECT COUNT(id) FROM #__app_sch_plugins WHERE name="os_ideal" AND published=1';
		$db->setQuery($sql) ;
		$total = $db->loadResult() ;
		if ($total) {
			require_once JPATH_ROOT.'/components/com_osservicesbooking/plugins/ideal/ideal.class.php';
			return true ;
		} else {
			return false ;
		}
	}
	/**
	 * Get list of banks for ideal payment plugin
	 * @return array
	 */
	public static function getBankLists() {
		$idealPlugin = os_payments::loadPaymentMethod('os_ideal');		
		$params = new JRegistry($idealPlugin->params) ;		
		$partnerId = $params->get('partner_id');
		$mode = $params->get('ideal_mode',0);
		$ideal = new iDEAL_Payment($partnerId,$mode) ;
		$bankLists = $ideal->getBanks();
		return $bankLists ;
	}
	
	/**
	 * Load Venue information
	 *
	 * @param unknown_type $sid
	 * @param unknown_type $eid
	 */
	function loadVenueInformation($sid,$eid){
		global $mainframe,$configClass;
		JHTML::_('behavior.modal','a.osmodal');
		$db = JFactory::getDbo();
		$db->setQuery("Select a.* from #__app_sch_venues as a inner join #__app_sch_employee_service as b on a.id = b.vid where b.employee_id = '$eid' and b.service_id = '$sid'");
		$row = $db->loadObject();
		if($row->id > 0){
		?>
		<tr>
			<td width="100%">
				<div style="width:100%;padding-top:5px;padding-bottom:5px;font-size:11px;">
				<?php
				if($row->image != ""){
					?>
					<div style="float:left;margin-right:5px;">
						<img src="<?php echo JURI::root()?>images/osservicesbooking/venue/<?php echo $row->image?>" class="img-polaroid" width="100" />
					</div>
					<?php
				}
				$addressArr = array();
				$addressArr[] = OSBHelper::getLanguageFieldValue($row,'address');
				if($row->city != ""){
					$addressArr[] = OSBHelper::getLanguageFieldValue($row,'city');
				}
				if($row->state != ""){
					$addressArr[] = OSBHelper::getLanguageFieldValue($row,'state');
				}
				if($row->country != ""){
					$addressArr[] = $row->country;
				}
				echo implode(", ",$addressArr);
				
				if(($row->lat_add != "") and ($row->long_add != "")){
					?>
					<a href="<?php echo JURI::root()?>index.php?option=com_osservicesbooking&task=default_showmap&vid=<?php echo $row->id?>&tmpl=component" class="osmodal" rel="{handler: 'iframe', size: {x: 600, y: 400}}" title="<?php echo JText::_('OS_VENUE_MAP');?>">
						<img src="<?php echo JURI::root()?>components/com_osservicesbooking/style/images/location24.png" />
					</a>
					<?php
				}
				?>
				
				<BR />
				<?php echo JText::_('OS_CONTACT_NAME')?>: <?php echo $row->contact_name;?>
				<BR />
				<?php echo JText::_('OS_CONTACT_EMAIL')?>: <?php echo $row->contact_email;?>
				<BR />
				<?php echo JText::_('OS_CONTACT_PHONE')?>: <?php echo $row->contact_phone;?>
				
				<?php
				?>
				</div>
				<input type="hidden" name="venue_available" id="venue_available" value="1" />
			</td>
		</tr>
		<?php
		}else{
			?>
			<input type="hidden" name="venue_available" id="venue_available" value="0" />
			<?php
		}
	}
	
	public function returnAccessSql($prefix){
		$user = JFactory::getUser();
		if($prefix != ""){
			$prefix .= ".";
		}
		if(intval($user->id) > 0){
			$special = self::checkSpecial();
			if($special){
				$access_sql = " and ".$prefix."access in (0,1,2) ";
			}else{
				$access_sql = " and ".$prefix."access in (0,1) ";
			}
		}else{
			$access_sql = " and ".$prefix."access = '0' ";
		}
		
		return $access_sql;
	}
	
	public static function checkSpecial(){
		global $mainframe;$_jversion;
		$db = JFactory::getDbo();
		$user = JFactory::getUser();
		$specialArr = array("Super Users","Super Administrator","Administrator","Manager");
		$db->setQuery("Select b.title from #__user_usergroup_map as a inner join #__usergroups as b on b.id = a.group_id where a.user_id = '$user->id'");
		$usertype = $db->loadResult();
		if(in_array($usertype,$specialArr)){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * Update AcyMailing
	 *
	 * @param unknown_type $orderId
	 */
	public static function updateAcyMailing($orderId){
		global $mainframe;
		$configClass = OSBHelper::loadConfig();
		$db = JFactory::getDbo();
		if(($configClass['enable_acymailing'] == 1) and (file_exists(JPATH_ADMINISTRATOR.'/components/com_acymailing/helpers/helper.php'))){
			$db->setQuery("Select * from #__app_sch_orders where id = '$orderId'");
			$row = $db->loadObject();
			$db->setQuery("Select a.* from #__app_sch_services as a inner join #__app_sch_order_items as b on b.sid = a.id where b.order_id = '$orderId'");
			$services = $db->loadObjectList();
			foreach ($services as $service){
				
				$acymailing_list_id = $service->acymailing_list_id;
				if($acymailing_list_id == -1){
					$add_to_acymailing = 0;
				}else{
					$add_to_acymailing = 1;
					if($acymailing_list_id == 0){
						$acymailing_list_id = $configClass['acymailing_default_list_id'];
					}
				}
				
				if($add_to_acymailing == 1){
					require_once JPATH_ADMINISTRATOR.'/components/com_acymailing/helpers/helper.php';
					$userClass = acymailing_get('class.subscriber');
					//Check to see whether the current users has been added as subscriber or not
							
					$myUser = new stdClass();				
					$myUser->email = $row->order_email ;				
					$myUser->name = $row->order_name ;
					$myUser->userid = $row->user_id ;	 				
					$subscriberClass = acymailing_get('class.subscriber');				
					$subid = $subscriberClass->save($myUser); //this				
					$subscribe = array($acymailing_list_id);
					$userClass = acymailing_get('class.subscriber');
					$newSubscription = array();
					if(!empty($subscribe)){
						foreach($subscribe as $listId){
							$newList = array();
							$newList['status'] = 1;
							$newSubscription[$listId] = $newList;
						}
					}
					$userClass->saveSubscription($subid,$newSubscription);
				}
			}
		}
	}
	
	static function getServiceInformation($service,$year,$month,$day){
		global $configClass;
		$db = JFactory::getDbo();
		$db->setQuery("Select count(id) from #__app_sch_employee_service where service_id = '$service->id'");
		$nstaff = $db->loadResult();
		$nstaff = intval($nstaff);
		
		if($configClass['disable_payments'] == 1){?>
			<span class="editlinktip hasTip" title="<?php echo JText::_('OS_PRICE')?>">
				<img src="<?php echo JURI::root()?>components/com_osservicesbooking/style/images/money.png">
			</span>
			<strong>
            <?php
				echo OSBHelper::showMoney(OSBHelper::returnServicePrice($service->id,$year."-".$month."-".$day),1);
			?>
			</strong>&nbsp;|&nbsp;
		<?php }?>
		
		<span class="editlinktip hasTip" title="<?php echo JText::_('OS_LENGTH')?>">
			<img src="<?php echo JURI::root()?>components/com_osservicesbooking/style/images/time.png" width="14" />
		</span>
		
		<strong><?php echo $service->service_total;?> <?php echo JText::_('OS_MINS')?></font></strong>
		&nbsp;|&nbsp;
		<span class="editlinktip hasTip" title="<?php echo JText::_('OS_NUMBER_STAFF')?>">
			<img src="<?php echo JURI::root()?>components/com_osservicesbooking/style/images/staff.png" />
		</span>
	
		 <strong><?php echo $nstaff;?></strong>
		<?php
		if($configClass['early_bird'] == 1){
			if(($service->early_bird_amount > 0) and ($service->early_bird_days > 0)){
				?>
				&nbsp;|&nbsp;
				<span class="editlinktip hasTip" title="<?php echo JText::_('OS_NUMBER_STAFF');?>">
					<img src="<?php echo JURI::root()?>components/com_osservicesbooking/asset/images/early_bird.png" />
				</span>
				<strong>
				 <?php
				 echo Jtext::_('OS_DISCOUNT').' ';
				 echo OSBHelper::generateDecimal($service->early_bird_amount);
				 if($service->early_bird_type == 0){
				 	echo ' '.$configClass['currency_format'];
				 }else{
				 	echo "% ";
				 	echo JText::_('OS_OF_SERVICE_PRICE');
				 }
				 echo JText::sprintf('OS_EARLY_BIRD_BOOKING_INFORM', $service->early_bird_days);
				 ?>
				 </strong>
				 <?php 
			}
		} 
	}
	
	static function loadEmployees($date,$sid,$employee_id,$vid){
		global $configClass;
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
		$db->setQuery("Select a.* from #__app_sch_employee as a inner join #__app_sch_employee_service as b on a.id = b.employee_id where a.published = '1' and b.service_id = '$sid' and b.".$day." = '1' and a.id NOT IN (Select eid from #__app_sch_employee_rest_days where rest_date <= '$day1' and rest_date_to >= '$day1') $vidSql $employeeSql order by a.employee_name");
		$employees = $db->loadObjectList();
		return $employees;
	}

    static function getCategoryName($sid){
        $db = JFactory::getDbo();
        $db->setQuery("Select category_id from #__app_sch_services where id = '$sid'");
        $category_id = $db->loadResult();
        $db->setQuery("Select category_name from #__app_sch_categories where id = '$category_id'");
        $category_name = $db->loadResult();
        return "<a href='".Jroute::_('index.php?option=com_osservicesbooking&task=default_layout&category_id='.$category_id.'&Itemid='.JRequest::getInt('Itemid',0))."' title='".JText::_('OS_CATEGORY_DETAILS')."'>".$category_name."</a>";
    }

    static function getServiceNames($eid){
        $db = JFactory::getDbo();
        $db->setQuery("Select a.* from #__app_sch_services as a inner join #__app_sch_employee_service as b on b.service_id = a.id where b.employee_id = '$eid' and a.published = '1' order by a.ordering");
        $rows = $db->loadObjectList();
        $tempArr = array();
        if(count($rows) > 0){
            foreach($rows as $row){
                $tempArr[] = "<a href='".Jroute::_('index.php?option=com_osservicesbooking&task=default_layout&sid='.$row->id.'&Itemid='.JRequest::getInt('Itemid',0))."' title='".JText::_('OS_SERVICE_DETAILS')."'>".OSBHelper::getLanguageFieldValue($row,'service_name')."</a>";
            }
        }
        return implode(", ",$tempArr);
    }
}

?>