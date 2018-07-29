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
				'cron_lbl' => array('type' => 'custom', 'label' => '', 'html' => '<h4><i class="vboicn-file-text2"></i><i class="vboicn-coin-dollar"></i>Invoices Generator</h4>'),
				'checktype' => array('type' => 'select', 'label' => JText::_('VBOCRONINVGENPARAMCWHEN').':', 'options' => array(JText::_('VBOCRONINVGENPARAMCWHENA') => 'checkin', JText::_('VBOCRONINVGENPARAMCWHENB') => 'payment', JText::_('VBOCRONINVGENPARAMCWHENC') => 'checkout')),
				'invdates' => array('type' => 'select', 'label' => JText::_('VBINVUSEDATE').':', 'options' => array(JText::_('VBOCRONINVGENPARAMDGEN') => '1', JText::_('VBINVUSEDATEBOOKING') => '0')),
				'skipotas' => array('type' => 'select', 'label' => JText::_('VBOCRONINVGENPARAMSKIPOTAS').'://'.JText::_('VBOCRONINVGENPARAMSKIPOTASHELP'), 'options' => array(JText::_('VBYES') => 'ON', JText::_('VBNO') => 'OFF')),
				'test' => array('type' => 'select', 'label' => JText::_('VBOCRONINVGENPARAMTEST').'://'.JText::_('VBOCRONINVGENPARAMTESTHELP'), 'options' => array('OFF', 'ON')),
				'emailsend' => array('type' => 'select', 'label' => JText::_('VBOCRONINVGENPARAMEMAILSEND').':', 'options' => array(JText::_('VBNO') => 'OFF', JText::_('VBYES') => 'ON')),
				'subject' => array('type' => 'text', 'label' => JText::_('VBOCRONEMAILREMPARAMSUBJECT').':', 'default' => JText::_('VBOCRONEMAILREMPARAMSUBJECT'), 'attributes' => array('style' => 'width: 180px !important;')),
				'tpl_text' => array('type' => 'textarea', 'label' => JText::_('VBOCRONINVGENPARAMTEXT').':', 'default' => 'Dear {customer_name},'."\n".'Attached to this message you will find the invoice for your booking.'."\n".'Thank you!', 'attributes' => array('id' => 'tpl_text', 'style' => 'width: 70%; height: 80px;')),
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
				'help' => array('type' => 'custom', 'label' => '', 'html' => '<p class="vbo-cronparam-suggestion"><i class="vboicn-lifebuoy"></i>'.JText::_('VBOCRONINVGENHELP').'</p>'),
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
		$this->params['invdates'] = intval($this->params['invdates']);
		$this->params['skipotas'] = $this->params['skipotas'] == 'ON' ? true : false;
		$this->params['emailsend'] = $this->params['emailsend'] == 'ON' ? true : false;
	}
	
	public function run () {
		$dbo = JFactory::getDBO();
		$this->getCronData();
		$ts_limit_firstrun = 0;
		if(!($this->cron_data['flag_int'] > 0)) {
			//first run ever of this cron - do not generate all the invoices but just the ones of this month
			$ts_limit_firstrun = mktime(0, 0, 0, date('n'), 1, date('Y'));
			if($this->debug) {
				echo '<p>This is the first execution ever of this cron: only the bookings of the current month will be read to prevent issues. Next run will have no limits.</p>';
			}
		}
		$start_ts = time();
		if($this->debug) {
			echo '<p>Reading bookings with '.($this->checktype == 'checkout' ? 'check-out' : ($this->checktype == 'checkin' ? 'check-in' : 'confirmation')).' datetime earlier than: '.date('c', $start_ts).'</p>';
		}
		$q = "SELECT `o`.*,`co`.`idcustomer`,CONCAT_WS(' ',`c`.`first_name`,`c`.`last_name`) AS `customer_name`,`c`.`pin` AS `customer_pin`,`nat`.`country_name`,`i`.`number` " .
			"FROM `#__vikbooking_orders` AS `o` " .
			"LEFT JOIN `#__vikbooking_customers_orders` `co` ON `co`.`idorder`=`o`.`id` " .
			"LEFT JOIN `#__vikbooking_customers` `c` ON `c`.`id`=`co`.`idcustomer` " .
			"LEFT JOIN `#__vikbooking_countries` `nat` ON `nat`.`country_3_code`=`o`.`country` " .
			"LEFT JOIN `#__vikbooking_invoices` `i` ON `i`.`idorder`=`o`.`id` " .
			"WHERE `i`.`number` IS NULL AND `o`.`".($this->checktype == 'checkout' ? 'checkout' : ($this->checktype == 'checkin' ? 'checkin' : 'ts'))."`<=".(int)$start_ts.(!empty($ts_limit_firstrun) ? " AND `o`.`".($this->checktype == 'checkout' ? 'checkout' : ($this->checktype == 'checkin' ? 'checkin' : 'ts'))."`>=".$ts_limit_firstrun : "")." AND `o`.`status`='confirmed' AND `o`.`total` > 0 " .
			"ORDER BY `o`.`id` ASC;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() > 0) {
			$bookings = $dbo->loadAssocList();
			if($this->debug) {
				echo '<p>Creating '.count($bookings).' invoices.</p>';
			}
			$log_str = '';
			$invoice_num = vikbooking::getNextInvoiceNumber();
			$def_subject = $this->params['subject'];
			foreach ($bookings as $k => $booking) {
				if($this->params['skipotas'] === true) {
					if(!empty($booking['idorderota'])) {
						echo '<span>Skipping the Booking ID '.$booking['id'].' because transmitted by the Channel Manager ('.$booking['channel'].' '.$booking['idorderota'].')</span>';
						continue;
					}
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
				$message = $this->parseCronEmailTemplate($message, $booking);
				$gen_res = $this->params['test'] === true ? false : vikbooking::generateBookingInvoice($booking, $invoice_num, '', $this->params['invdates'], '', true, ($this->debug ? false : true));
				if($this->debug) {
					echo '<span>Result for generating the invoice for Booking ID '.$booking['id'].' ('.$booking['customer_name'].(!empty($booking['lang']) ? ' '.$booking['lang'] : '').'): '.($gen_res !== false ? '<i class="vboicn-checkmark"></i>Success' : '<i class="vboicn-cancel-circle"></i>Failure').($this->params['test'] === true ? ' (Test Mode ON)' : '').'</span>';
				}
				if($gen_res !== false) {
					$invoice_num++;
					$log_str .= 'Invoice #'.$gen_res.' generated for Booking ID '.$booking['id'].' ('.$booking['customer_name'].(!empty($booking['lang']) ? ' '.$booking['lang'] : '').')'."\n";
					if($this->params['emailsend'] === true) {
						$send_res = vikbooking::sendBookingInvoice($gen_res, $booking, $message, $this->params['subject']);
						if($this->debug) {
							echo '<span>Result for sending the invoice for Booking ID '.$booking['id'].' via eMail to '.$booking['custmail'].' ('.$booking['customer_name'].(!empty($booking['lang']) ? ' '.$booking['lang'] : '').'): '.($send_res !== false ? '<i class="vboicn-checkmark"></i>Success' : '<i class="vboicn-cancel-circle"></i>Failure').'</span>';
						}
						$log_str .= 'Invoice #'.$gen_res.' for Booking ID '.$booking['id'].' was '.($send_res === true ? 'SUCCESSFULLY' : 'NOT').' sent to '.$booking['custmail']."\n";
					}
				}
			}
			if($invoice_num > vikbooking::getNextInvoiceNumber()) {
				$q = "UPDATE `#__vikbooking_config` SET `setting`='".($invoice_num - 1)."' WHERE `param`='invoiceinum';";
				$dbo->setQuery($q);
				$dbo->Query($q);
			}
			if(!empty($log_str)) {
				$this->log = $log_str;
			}
		}else {
			if($this->debug) {
				echo '<span>No invoices must be generated for this time span.</span>';
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
		//update cron record - the field flag_int is set to 1 every time so the first run of this cron can generate the invoices only of the current month to avoid problems
		$q = "UPDATE `#__vikbooking_cronjobs` SET `last_exec`=".time().(!empty($log_str) ? ", `logs`=".$dbo->quote($log_str) : "").", `flag_int`=1 WHERE `id`=".(int)$this->cron_id.";";
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

	private function parseCronEmailTemplate($message, $booking) {
		$dbo = JFactory::getDBO();
		if(empty($message)) {
			return false;
		}
		$tpl = $message;
		$booking_rooms = array();
		$q = "SELECT `or`.*,`r`.`name` AS `room_name` FROM `#__vikbooking_ordersrooms` AS `or` LEFT JOIN `#__vikbooking_rooms` `r` ON `r`.`id`=`or`.`idroom` WHERE `or`.`idorder`=".(int)$booking['id'].";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() > 0) {
			$booking_rooms = $dbo->loadAssocList();
		}
		$vbo_tn = vikbooking::getTranslator();
		$vbo_tn->translateContents($booking_rooms, '#__vikbooking_rooms', array('id' => 'idroom', 'name' => 'room_name'));
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