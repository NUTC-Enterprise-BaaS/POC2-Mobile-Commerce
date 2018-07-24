<?php
/**------------------------------------------------------------------------
 * com_vikbooking - VikBooking
 * ------------------------------------------------------------------------
 * author    Alessio Gaggii - e4j - Extensionsforjoomla.com
 * copyright Copyright (C) 2016 e4j - Extensionsforjoomla.com. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: http://www.extensionsforjoomla.com
 * Technical Support:  tech@extensionsforjoomla.com
 * ------------------------------------------------------------------------
*/

defined('_JEXEC') OR die('Restricted Area');

class VikCronJob {
	
	public $cron_id;
	public $params;
	public $debug;
	private $checktype;
	private $use_date;
	private $cron_data;
	private $flag_char;
	private $exec_flag_char;
	public $log;
	
	/**
	 * Do not edit this function unless you know what you are doing
	 * it is just meant to define the parameters of the payment method
	 */
	static function getAdminParameters () {
		return array(
				'cron_lbl' => array('type' => 'custom', 'label' => '', 'html' => '<h4><i class="vboicn-mail4"></i><i class="vboicn-alarm"></i>eMail Reminder</h4>'),
				'checktype' => array('type' => 'select', 'label' => JText::_('VBOCRONSMSREMPARAMCTYPE').':', 'options' => array(JText::_('VBOCRONSMSREMPARAMCTYPEA') => 'checkin', JText::_('VBOCRONSMSREMPARAMCTYPEB') => 'payment', JText::_('VBOCRONSMSREMPARAMCTYPEC') => 'checkout')),
				'remindbefored' => array('type' => 'number', 'label' => JText::_('VBOCRONSMSREMPARAMBEFD').'://'.JText::_('VBOCRONSMSREMPARAMCTYPECHELP'), 'default' => '2', 'attributes' => array('style' => 'width: 60px !important;')),
				'test' => array('type' => 'select', 'label' => JText::_('VBOCRONSMSREMPARAMTEST').'://'.JText::_('VBOCRONEMAILREMPARAMTESTHELP'), 'options' => array('OFF', 'ON')),
				'subject' => array('type' => 'text', 'label' => JText::_('VBOCRONEMAILREMPARAMSUBJECT').':', 'default' => JText::_('VBOCRONEMAILREMPARAMSUBJECT'), 'attributes' => array('style' => 'width: 180px !important;')),
				'tpl_text' => array('type' => 'textarea', 'label' => JText::_('VBOCRONSMSREMPARAMTEXT').':', 'default' => 'Dear {customer_name},'."\n".'This is an automated message to remind you the check-in time for your stay: {checkin_date} at 13:00. You can always drop your luggage at the Hotel, should you arrive earlier.', 'attributes' => array('id' => 'tpl_text', 'style' => 'width: 70%; height: 80px;')),
				'buttons' => array(
					'type' => 'custom',
					'label' => '',
					'html' => '<div class="btn-toolbar vbo-smstpl-toolbar vbo-cronparam-cbar" style="margin-top: -10px;">
						<div class="btn-group pull-left vbo-smstpl-bgroup">
							<button type="button" class="btn" onclick="setCronTplTag(\'tpl_text\', \'{customer_name}\');">{customer_name}</button>
							<button type="button" class="btn" onclick="setCronTplTag(\'tpl_text\', \'{customer_pin}\');">{customer_pin}</button>
							<button type="button" class="btn" onclick="setCronTplTag(\'tpl_text\', \'{booking_id}\');">{booking_id}</button>
							<button type="button" class="btn" onclick="setCronTplTag(\'tpl_text\', \'{checkin_date}\');">{checkin_date}</button>
							<button type="button" class="btn" onclick="setCronTplTag(\'tpl_text\', \'{checkout_date}\');">{checkout_date}</button>
							<button type="button" class="btn" onclick="setCronTplTag(\'tpl_text\', \'{num_nights}\');">{num_nights}</button>
							<button type="button" class="btn" onclick="setCronTplTag(\'tpl_text\', \'{rooms_booked}\');">{rooms_booked}</button>
							<button type="button" class="btn" onclick="setCronTplTag(\'tpl_text\', \'{tot_adults}\');">{tot_adults}</button>
							<button type="button" class="btn" onclick="setCronTplTag(\'tpl_text\', \'{tot_children}\');">{tot_children}</button>
							<button type="button" class="btn" onclick="setCronTplTag(\'tpl_text\', \'{tot_guests}\');">{tot_guests}</button>
							<button type="button" class="btn" onclick="setCronTplTag(\'tpl_text\', \'{total}\');">{total}</button>
							<button type="button" class="btn" onclick="setCronTplTag(\'tpl_text\', \'{total_paid}\');">{total_paid}</button>
							<button type="button" class="btn" onclick="setCronTplTag(\'tpl_text\', \'{remaining_balance}\');">{remaining_balance}</button>
							<button type="button" class="btn" onclick="setCronTplTag(\'tpl_text\', \'{booking_link}\');">{booking_link}</button>
						</div>
					</div>
					<br clear="all"/>
					<br clear="all"/>
					<script type="text/javascript">
					function setCronTplTag(taid, tpltag) {
						var tplobj = document.getElementById(taid);
						if(tplobj != null) {
							var start = tplobj.selectionStart;
							var end = tplobj.selectionEnd;
							tplobj.value = tplobj.value.substring(0, start) + tpltag + tplobj.value.substring(end);
							tplobj.selectionStart = tplobj.selectionEnd = start + tpltag.length;
							tplobj.focus();
						}
					}
					</script>'),
				'help' => array('type' => 'custom', 'label' => '', 'html' => '<p class="vbo-cronparam-suggestion"><i class="vboicn-lifebuoy"></i>'.JText::_('VBOCRONSMSREMHELP').'</p>'),
		);
	}
	
	public function __construct ($cron_id, $params = array()) {
		$this->cron_id = $cron_id;
		$this->params = $params;
		$this->params['test'] = $params['test'] == 'ON' ? true : false;
		$this->debug = false; //debug is set to true by the back-end manual execution to print the debug messages
		$this->checktype = $params['checktype'] == 'payment' ? 'payment' : ($params['checktype'] == 'checkout' ? 'checkout' : 'checkin');
		$this->use_date = time();
		$this->cron_data = array();
		$this->flag_char = array();
		$this->exec_flag_char = array();
		$this->params['remindbefored'] = intval($this->params['remindbefored']);
	}
	
	public function run () {
		$dbo = JFactory::getDBO();
		$this->getCronData();
		$start_ts = $this->use_date = mktime(0, 0, 0, date('n'), ((int)date('j') + $this->params['remindbefored']), date('Y'));
		$end_ts = mktime(23, 59, 59, date('n'), ((int)date('j') + $this->params['remindbefored']), date('Y'));
		if($this->debug) {
			echo '<p>Reading bookings with '.($this->checktype == 'checkout' ? 'check-out' : 'check-in').' datetime between: '.date('c', $this->use_date).' - '.date('c', $end_ts).'</p>';
		}
		$q = "SELECT `o`.*,`co`.`idcustomer`,CONCAT_WS(' ',`c`.`first_name`,`c`.`last_name`) AS `customer_name`,`c`.`pin` AS `customer_pin`,`nat`.`country_name` FROM `#__vikbooking_orders` AS `o` LEFT JOIN `#__vikbooking_customers_orders` `co` ON `co`.`idorder`=`o`.`id` LEFT JOIN `#__vikbooking_customers` `c` ON `c`.`id`=`co`.`idcustomer` LEFT JOIN `#__vikbooking_countries` `nat` ON `nat`.`country_3_code`=`o`.`country` WHERE `o`.`".($this->checktype == 'checkout' ? 'checkout' : 'checkin')."`>=".(int)$start_ts." AND `o`.`".($this->checktype == 'checkout' ? 'checkout' : 'checkin')."`<=".(int)$end_ts." ".($this->checktype == 'checkin' || $this->checktype == 'checkout' ? "AND `o`.`status`='confirmed'" : "AND `o`.`status`!='cancelled' AND `o`.`total` > 0 AND `o`.`totpaid` > 0 AND `o`.`totpaid`<`o`.`total`").";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() > 0) {
			$bookings = $dbo->loadAssocList();
			$this->exec_flag_char[$this->use_date] = array();
			if($this->debug) {
				echo '<p>Bookings to be notified: '.count($bookings).'</p>';
			}
			$log_str = '';
			$def_subject = $this->params['subject'];
			foreach ($bookings as $k => $booking) {
				if(array_key_exists($this->use_date, $this->flag_char) && array_key_exists($booking['id'], $this->flag_char[$this->use_date])) {
					if($this->debug) {
						echo '<span>Booking ID '.$booking['id'].' ('.$booking['customer_name'].') was already notified. Skipped.</span>';
					}
					continue;
				}
				$message = $this->params['tpl_text'];
				$this->params['subject'] = $def_subject;
				//language translation
				if(!empty($booking['lang'])) {
					$lang = JFactory::getLanguage();
					if($lang->getTag() != $booking['lang']) {
						$lang->load('com_vikbooking', JPATH_SITE, $booking['lang'], true);
						$lang->load('com_vikbooking', JPATH_ADMINISTRATOR, $booking['lang'], true);
					}
					$vbo_tn = vikbooking::getTranslator();
					$cron_tn = $this->cron_data;
					$vbo_tn->translateContents($cron_tn, '#__vikbooking_cronjobs', array(), array(), $booking['lang']);
					$params_tn = json_decode($cron_tn['params'], true);
					if(is_array($params_tn) && array_key_exists('tpl_text', $params_tn)) {
						$message = $params_tn['tpl_text'];
					}
					if(is_array($params_tn) && array_key_exists('subject', $params_tn)) {
						$this->params['subject'] = $params_tn['subject'];
					}
				}
				//
				$send_res = $this->params['test'] === true ? false : $this->sendEmailReminder($booking, $message);
				if($this->debug) {
					echo '<span>Result for sending eMail to '.$booking['custmail'].' - Booking ID '.$booking['id'].' ('.$booking['customer_name'].(!empty($booking['lang']) ? ' '.$booking['lang'] : '').'): '.($send_res !== false ? '<i class="vboicn-checkmark"></i>Success' : '<i class="vboicn-cancel-circle"></i>Failure').($this->params['test'] === true ? ' (Test Mode ON)' : '').'</span>';
				}
				if($send_res !== false) {
					$log_str .= 'eMail sent to '.$booking['custmail'].' - Booking ID '.$booking['id'].' ('.$booking['customer_name'].(!empty($booking['lang']) ? ' '.$booking['lang'] : '').')'."\n";
					//store in execution flag that this booking ID was notified
					$this->exec_flag_char[$this->use_date][$booking['id']] = (int)$send_res;
				}
			}
			if(!empty($log_str)) {
				$this->log = $log_str;
			}
		}else {
			if($this->debug) {
				echo '<span>No bookings to notify.</span>';
			}
		}
		return true;
	}
	
	//this function is called after the cron has been executed
	public function afterRun ($extra = array()) {
		$dbo = JFactory::getDBO();
		$log_str = '';
		if(strlen($this->log) && count($this->cron_data) > 0) {
			$log_str = date('c')."\n".$this->log."\n----------\n".$this->cron_data['logs'];
		}
		$new_flag_str = '';
		if(count($this->exec_flag_char) && count($this->exec_flag_char[$this->use_date])) {
			//array_merge does not preserve numeric keys. The union (+) operator does
			$new_flag_arr = $this->exec_flag_char + $this->flag_char;
			if(count($new_flag_arr) > 3) {
				//keep max 3 days
				$tot_dates = 1;
				foreach ($new_flag_arr as $flag_date => $flag) {
					if($tot_dates > 3) {
						unset($new_flag_arr[$flag_date]);
					}
					$tot_dates++;
				}
			}
			$new_flag_str = json_encode($new_flag_arr);
		}
		//update cron record
		$q = "UPDATE `#__vikbooking_cronjobs` SET `last_exec`=".time().(!empty($log_str) ? ", `logs`=".$dbo->quote($log_str) : "").(!empty($new_flag_str) ? ", `flag_char`=".$dbo->quote($new_flag_str) : "")." WHERE `id`=".(int)$this->cron_id.";";
		$dbo->setQuery($q);
		$dbo->Query($q);
	}

	private function getCronData() {
		$dbo = JFactory::getDBO();
		$q = "SELECT * FROM `#__vikbooking_cronjobs` WHERE `id`=".(int)$this->cron_id.";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() == 1) {
			$this->cron_data = $dbo->loadAssoc();
			if(!empty($this->cron_data['flag_char'])) {
				$this->flag_char = json_decode($this->cron_data['flag_char'], true);
			}
		}
	}

	private function sendEmailReminder($booking, $message) {
		if(!class_exists('VikApplication')) {
			require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'helpers'.DS.'jv_helper.php');
		}
		$vbo_app = new VikApplication;
		if(empty($booking['id']) || empty($booking['custmail'])) {
			return false;
		}
		$dbo = JFactory::getDBO();
		$booking_rooms = array();
		$q = "SELECT `or`.*,`r`.`name` AS `room_name` FROM `#__vikbooking_ordersrooms` AS `or` LEFT JOIN `#__vikbooking_rooms` `r` ON `r`.`id`=`or`.`idroom` WHERE `or`.`idorder`=".(int)$booking['id'].";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() > 0) {
			$booking_rooms = $dbo->loadAssocList();
		}
		$admin_sendermail = vikbooking::getSenderMail();
		$vbo_tn = vikbooking::getTranslator();
		$vbo_tn->translateContents($booking_rooms, '#__vikbooking_rooms', array('id' => 'idroom', 'name' => 'room_name'));
		$message = $this->parseCustomerEmailTemplate($message, $booking, $booking_rooms, $vbo_tn);
		if(empty($message)) {
			return false;
		}
		$is_html = strpos($message, '<') !== false || strpos($message, '</') !== false ? true : false;
		$vbo_app->sendMail($admin_sendermail, $admin_sendermail, $booking['custmail'], $admin_sendermail, $this->params['subject'], $message, $is_html);

		return true;
	}

	private function parseCustomerEmailTemplate($message, $booking, $booking_rooms, $vbo_tn = null) {
		$tpl = $message;
		$vbo_df = vikbooking::getDateFormat();
		$df = $vbo_df == "%d/%m/%Y" ? 'd/m/Y' : ($vbo_df == "%m/%d/%Y" ? 'm/d/Y' : 'Y-m-d');
		$tpl = str_replace('{customer_name}', $booking['customer_name'], $tpl);
		$tpl = str_replace('{booking_id}', $booking['id'], $tpl);
		$tpl = str_replace('{checkin_date}', date($df, $booking['checkin']), $tpl);
		$tpl = str_replace('{checkout_date}', date($df, $booking['checkout']), $tpl);
		$tpl = str_replace('{num_nights}', $booking['days'], $tpl);
		$rooms_booked = array();
		$tot_adults = 0;
		$tot_children = 0;
		$tot_guests = 0;
		foreach ($booking_rooms as $broom) {
			if(array_key_exists($broom['room_name'], $rooms_booked)) {
				$rooms_booked[$broom['room_name']] += 1;
			}else {
				$rooms_booked[$broom['room_name']] = 1;
			}
			$tot_adults += (int)$broom['adults'];
			$tot_children += (int)$broom['children'];
			$tot_guests += ((int)$broom['adults'] + (int)$broom['children']);
		}
		$tpl = str_replace('{tot_adults}', $tot_adults, $tpl);
		$tpl = str_replace('{tot_children}', $tot_children, $tpl);
		$tpl = str_replace('{tot_guests}', $tot_guests, $tpl);
		$rooms_booked_quant = array();
		foreach ($rooms_booked as $rname => $quant) {
			$rooms_booked_quant[] = ($quant > 1 ? $quant.' ' : '').$rname;
		}
		$tpl = str_replace('{rooms_booked}', implode(', ', $rooms_booked_quant), $tpl);
		$tpl = str_replace('{total}', vikbooking::numberFormat($booking['total']), $tpl);
		$tpl = str_replace('{total_paid}', vikbooking::numberFormat($booking['totpaid']), $tpl);
		$remaining_bal = $booking['total'] - $booking['totpaid'];
		$tpl = str_replace('{remaining_balance}', vikbooking::numberFormat($remaining_bal), $tpl);
		$tpl = str_replace('{customer_pin}', $booking['customer_pin'], $tpl);
		$book_link = JURI::root().'index.php?option=com_vikbooking&task=vieworder&sid='.$booking['sid'].'&ts='.$booking['ts'];
		$tpl = str_replace('{booking_link}', $book_link, $tpl);

		return $tpl;
	}
	
}

?>