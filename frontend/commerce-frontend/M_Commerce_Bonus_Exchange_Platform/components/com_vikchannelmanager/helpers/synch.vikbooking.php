<?php
/**------------------------------------------------------------------------
 * com_vikchannelmanager - VikChannelManager
 * ------------------------------------------------------------------------
 * author    e4j - Extensionsforjoomla.com
 * copyright Copyright (C) 2016 e4j - Extensionsforjoomla.com. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: http://www.extensionsforjoomla.com
 * Technical Support:  tech@extensionsforjoomla.com
 * ------------------------------------------------------------------------
*/

defined('_JEXEC') OR die('Restricted Area');

/**
* This Class is used by VikChannelManager to send A Requests to
* e4jConnect to synchronize VikBooking with the OTA
*/
class synchVikBooking {
	
	private $order_id;
	private $exclude_ids;
	private $modified_order;
	private $cancelled_order;
	private $skip_check_auto_sync;
	private $config;
	
	public function __construct ($orderid, $exclude_channels = array()) {
		if(!class_exists('VikChannelManager')) {
			require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'lib.vikchannelmanager.php');
		}
		if(!class_exists('VikChannelManagerConfig')) {
			require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'vcm_config.php');
		}
		$this->order_id = $orderid;
		$this->exclude_ids = $exclude_channels;
		$this->modified_order = array();
		$this->cancelled_order = array();
		$this->skip_check_auto_sync = false;
		$this->config = VikChannelManager::loadConfiguration();
	}
	
	private function isAvailabilityRequest() {
		$dbo = JFactory::getDBO();
		
		$q = "SELECT `id` FROM `#__vikchannelmanager_channel` WHERE `av_enabled`=1".(count($this->exclude_ids) > 0 ? " AND `uniquekey` NOT IN (".implode(',', $this->exclude_ids).")" : "")." LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		return ($dbo->getNumRows() > 0);
	}
	
	private function getAvChannelIds() {
		$dbo = JFactory::getDBO();
		$ch_ids = array();
		$q = "SELECT `id`,`name`,`uniquekey` FROM `#__vikchannelmanager_channel` WHERE `av_enabled`=1".(count($this->exclude_ids) > 0 ? " AND `uniquekey` NOT IN (".implode(',', $this->exclude_ids).")" : "").";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() > 0) {
			$channels = $dbo->loadAssocList();
			foreach ($channels as $cha) {
				$ch_ids[] = $cha['uniquekey'];
			}
		}
		return $ch_ids;
	}

	/**
	* This method sets the original booking array of VBO before it was Updated.
	* If called, the system will merge dates and room types of the original booking
	* with the dates and room types of the new and updated order.
	*/
	public function setFromModification($m_order) {
		if(is_array($m_order)) {
			$this->modified_order = $m_order;
		}
	}

	/**
	* This method sets the original booking array of VBO before it was Cancelled.
	* If called, the system will fetch the dates and room types of the original booking
	* and will notify the new availability to all channels.
	*/
	public function setFromCancellation($c_order) {
		if(is_array($c_order)) {
			$this->cancelled_order = $c_order;
		}
	}

	/**
	* If called before the main method, the system will execute the
	* A_RQ to e4jConnect even if the Configuration setting Auto-Sync of VCM
	* is disabled. This is useful in the back-end of VBO for Modifications and Cancellations.
	*/
	public function setSkipCheckAutoSync() {
		$this->skip_check_auto_sync = $this->skip_check_auto_sync === false ? true : false;
	}
	
	/**
	* Sends A(U)_RQ to E4jConnect.com
	* this method is called by VikBooking in the front site
	* every time the status of an order is set to Confirmed.
	* The same method can be called also by VCM newbookings.vikbooking.php
	* for a Cancellation of a booking or a Modification or for other channels like TripConnect
	* @return bool
	*/
	public function sendRequest () {
		if( (intval($this->config['vikbookingsynch']) == 1 || $this->skip_check_auto_sync === true) && $this->isAvailabilityRequest() ) {
			$arr_order = $this->getOrderDetails();
			$order = array_key_exists('vikbooking_order', $arr_order) ? $arr_order['vikbooking_order'] : array();
			unset($arr_order['vikbooking_order']);
			if (count($order) > 0 && count($arr_order) > 0) {
				$xml = $this->composeXmlARequest($order, $arr_order);
				if ($xml !== false && strlen($xml) > 0) {
					return $this->executeARequest($xml);
				}
			}
		}
		return false;
	}
	
	/**
	* Executes the A(U)_RQ sending the XML to e4jConnect
	* @param $xml string
	* @return bool
	*/
	private function executeARequest ($xml) {
		if (!function_exists('curl_init')) {
			$this->saveNotify('0', 'VCM', 'e4j.error.Curl', '');
			return false;
		}
		$e4jC = new E4jConnectRequest("https://e4jconnect.com/channelmanager/?r=a&c=channels");
		$e4jC->setPostFields($xml);
		$e4jC->slaveEnabled = true;
		$rs = $e4jC->exec();
		if($e4jC->getErrorNo()) {
			$this->saveNotify('0', 'VCM', $e4jC->getErrorMsg(), $this->order_id);
			return false;
		}
		if (substr($rs, 0, 4) == 'e4j.') {
			//Response for single channel request
			if (substr($rs, 0, 9) == 'e4j.error') {
				if($rs != 'e4j.error.Skip') {
					$this->saveNotify('0', 'VCM', $rs, $this->order_id);
				}
				return false;
			}
			$this->saveNotify('1', 'VCM', 'e4j.OK.Channels.AR_RQ', $this->order_id);
		}else {
			//JSON Response for multiple channels request
			$arr_rs = json_decode($rs, true);
			if(is_array($arr_rs) && @count($arr_rs) > 0) {
				$this->saveMultipleNotifications($arr_rs);
			}
		}
		
		return true;
	}
	
	/**
	* Generates the XML string for the A(U)_RQ
	*/
	private function composeXmlARequest ($order, $rooms) {
		$build = array();
		foreach($rooms as $k => $room) {
			$build[$k] = $room;
			foreach($room['adates'] as $day => $daydet) {
				$build[$k]['newavail'][$day] = $daydet['newavail'];
			}
		}
		if (count($build) > 0) {
			$nkey = $this->generateNKey($order['id']);
			$vbrooms_parsed = array();
			$xmlstr = '<?xml version="1.0" encoding="UTF-8"?>
<!-- A Request e4jConnect.com - VikChannelManager - VikBooking -->
<AvailUpdateRQ xmlns="http://www.e4jconnect.com/avail/arq">
	<Notify client="'.JURI::root().'" nkey="'.$nkey.'"/>
	<Api key="'.$this->config['apikey'].'"/>
	<AvailUpdate>
		<Order id="'.$order['id'].'" confirmnumb="'.$order['confirmnumber'].'">
			<DateRange from="'.date('Y-m-d', $order['checkin']).'" to="'.date('Y-m-d', $order['checkout']).'"/>
		</Order>'."\n";
			foreach($build as $k => $data) {
				if(in_array($data['idroom'], $vbrooms_parsed)) {
					continue;
				}
				$vbrooms_parsed[] = $data['idroom'];
				foreach($data['newavail'] as $day => $avail) {
					$xmlstr .= "\t\t".'<RoomType newavail="'.$avail.'">
			<Channels>'."\n";
					foreach($data['channels'] as $channel) {
						$rateplanid = '0';
						if(((int)$channel['idchannel'] == (int)VikChannelManagerConfig::AGODA || (int)$channel['idchannel'] == (int)VikChannelManagerConfig::YCS50) && !empty($channel['otapricing'])) {
							$ota_pricing = json_decode($channel['otapricing'], true);
							if(count($ota_pricing) > 0 && array_key_exists('RatePlan', $ota_pricing)) {
								foreach ($ota_pricing['RatePlan'] as $rp_id => $rp_val) {
									$rateplanid = $rp_id;
									break;
								}
							}
						}
						$xmlstr .= "\t\t\t\t".'<Channel id="'.$channel['idchannel'].'" roomid="'.$channel['idroomota'].'" rateplanid="'.$rateplanid.'"'.(array_key_exists('hotelid', $channel) ? ' hotelid="'.$channel['hotelid'].'"' : '').'/>'."\n";
					}
					$xmlstr .= "\t\t\t".'</Channels>
			<Adults num="'.$data['adults'].'"/>
			<Children num="'.$data['children'].'"/>
			<Day date="'.$day.'"/>
		</RoomType>'."\n";
				}
			}
			$xmlstr .= "\t".'</AvailUpdate>
</AvailUpdateRQ>';
			
			return $xmlstr;
		}
		return false;
	}
	
	/**
	* Get one availability number for the room of the OTA
	* In case one room of the OTA is linked to more than one room
	* of VikBooking, this method returns the highest value for the
	* availability of the rooms in VikBooking for these dates.
	* It also returns the number of Children and Adults in the first room assigned.
	*/
	private function getUniqueRoomAvailabilityAndPeople ($room) {
		$values = array();
		$ret = array();
		foreach($room as $k => $r) {
			foreach($r['adates'] as $day => $daydet) {
				$values[$day][] = $daydet['newavail'];
			}
		}
		foreach($values as $k => $v) {
			$values[$k] = max($v);
		}
		$ret['newavail'] = $values;
		$ret['adults'] = $room[key($room)]['adults'];
		$ret['children'] = $room[key($room)]['children'];
		return $ret;
	}
	
	private function getOrderDetails () {
		$rooms = array();
		$dbo = JFactory::getDBO();
		$q = "SELECT * FROM `#__vikbooking_orders` WHERE `id`='" . $this->order_id . "';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$rows = $dbo->loadAssocList();
			if ($rows[0]['status'] != 'confirmed' && !(count($this->cancelled_order) > 0)) {
				return array();
			}
			$rooms['vikbooking_order'] = $rows[0];
			$q="SELECT `or`.`idroom`,`or`.`adults`,`or`.`children`,`or`.`idtar`,`or`.`optionals`,`r`.`name`,`r`.`units`,`r`.`fromadult`,`r`.`toadult` FROM `#__vikbooking_ordersrooms` AS `or` LEFT JOIN `#__vikbooking_rooms` `r` ON `or`.`idroom`=`r`.`id` WHERE `or`.`idorder`='".$rows[0]['id']."' ORDER BY `or`.`id` ASC;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() > 0) {
				$orderrooms = $dbo->loadAssocList();
				//in case of modification, if the rooms were different in $this->modified_order['rooms_info'], the new availability should be taken also for the previous rooms
				if(count($this->modified_order) > 0) {
					$new_room_ids = array();
					foreach ($orderrooms as $orderroom) {
						$new_room_ids[] = $orderroom['idroom'];
					}
					if(count($this->modified_order['rooms_info']) > 0) {
						$or_next_index = count($orderrooms);
						foreach ($this->modified_order['rooms_info'] as $mod_orderroom) {
							if(!in_array($mod_orderroom['idroom'], $new_room_ids)) {
								$mod_orderroom['modification'] = 1;
								$orderrooms[$or_next_index] = $mod_orderroom;
								$or_next_index++;
							}
						}
					}
				}
				//build channels relations with VB Rooms
				$av_ch_ids = $this->getAvChannelIds();
				foreach ($orderrooms as $kor => $or) {
					$orderrooms[$kor]['channels'] = array();
					$q = "SELECT * FROM `#__vikchannelmanager_roomsxref` WHERE `idroomvb`=".(int)$or['idroom'].";";
					$dbo->setQuery($q);
					$dbo->Query($q);
					if ($dbo->getNumRows() > 0) {
						$ch_rooms = $dbo->loadAssocList();
						foreach ($ch_rooms as $ch_room) {
							if(strlen($ch_room['idroomota']) && strlen($ch_room['idchannel']) && in_array($ch_room['idchannel'], $av_ch_ids)) {
								$ch_r_info = array('idroomota' => $ch_room['idroomota'], 'idchannel' => $ch_room['idchannel'], 'otapricing' => $ch_room['otapricing']);
								if(!empty($ch_room['prop_params'])) {
									$prop_params_info = json_decode($ch_room['prop_params'], true);
									if(!empty($prop_params_info['hotelid'])) {
										$ch_r_info['hotelid'] = $prop_params_info['hotelid'];
									}
								}
								$orderrooms[$kor]['channels'][] = $ch_r_info;
							}
						}
					}
					if(!(count($orderrooms[$kor]['channels']) > 0)) {
						//room is not on any channel
						unset($orderrooms[$kor]);
					}
				}
				if (!(count($orderrooms) > 0)) {
					return array();
				}
				//
				$earliest_checkin = $rows[0]['checkin'];
				$prev_groupdays = array();
				//in case of modification, if the check-in/out dates were different in $this->modified_order, the new availability should be calculated also for the previous dates
				if(count($this->modified_order) > 0) {
					if($this->modified_order['checkin'] != $rows[0]['checkin'] || $this->modified_order['checkout'] != $rows[0]['checkout']) {
						$prev_groupdays = $this->getGroupDays($this->modified_order['checkin'], $this->modified_order['checkout'], $this->modified_order['days']);
						if($this->modified_order['checkin'] < $earliest_checkin) {
							$earliest_checkin = $this->modified_order['checkin'];
						}
					}
				}
				$groupdays = $this->getGroupDays($rows[0]['checkin'], $rows[0]['checkout'], $rows[0]['days']);
				if(count($prev_groupdays)) {
					$groupdays = array_merge($groupdays, $prev_groupdays);
					$groupdays = array_unique($groupdays);
				}
				$morehst = $this->getHoursRoomAvail() * 3600;
				foreach($orderrooms as $kor => $or) {
					if(count($or['channels']) > 0) {
						$rooms[$kor] = $or;
						$check = "SELECT `id`,`checkin`,`checkout` FROM `#__vikbooking_busy` WHERE `idroom`='" . $or['idroom'] . "' AND `checkout` > ".$earliest_checkin.";";
						$dbo->setQuery($check);
						$dbo->Query($check);
						if ($dbo->getNumRows() > 0) {
							$busy = $dbo->loadAssocList();
							foreach ($groupdays as $gday) {
								$oday = date('Y-m-d', $gday);
								$gday_info = getdate($gday);
								$midn_gday = mktime(0, 0, 0, $gday_info['mon'], $gday_info['mday'], $gday_info['year']);
								$bfound = 0;
								foreach ($busy as $bu) {
									//old method before VCM 1.4.0
									/*
									if ($gday >= $bu['checkin'] && $gday <= ($morehst + $bu['checkout'])) {
										$bfound++;
									}
									*/
									$checkin_info = getdate($bu['checkin']);
									$checkout_info = getdate($bu['checkout']);
									$midn_checkin = mktime(0, 0, 0, $checkin_info['mon'], $checkin_info['mday'], $checkin_info['year']);
									$midn_checkout = mktime(0, 0, 0, $checkout_info['mon'], $checkout_info['mday'], $checkout_info['year']);
									if ($midn_gday >= $midn_checkin && $midn_gday < $midn_checkout) {
										$bfound++;
									}
								}
								if ($bfound >= $or['units']) {
									$rooms[$kor]['adates'][$oday]['newavail'] = 0;
								}else {
									$rooms[$kor]['adates'][$oday]['newavail'] = ($or['units'] - $bfound);
								}
							}
						}else {
							foreach ($groupdays as $gday) {
								$oday = date('Y-m-d', $gday);
								$rooms[$kor]['adates'][$oday]['newavail'] = $or['units'];
							}
						}
					}
				}
				
				if (count($rooms) > 0) {
					return $rooms;
				}else {
					$this->saveNotify('0', 'VCM', 'e4j.error.Channels.NoSynchRooms', $this->order_id);
				}
			}
		}
		return array();
	}
	
	private function getGroupDays($first, $second, $daysdiff) {
		$ret = array();
		$ret[] = $first;
		if($daysdiff > 1) {
			$start = getdate($first);
			$end = getdate($second);
			$endcheck = mktime(0, 0, 0, $end['mon'], $end['mday'], $end['year']);
			for($i = 1; $i < $daysdiff; $i++) {
				$checkday = $start['mday'] + $i;
				$dayts = mktime(0, 0, 0, $start['mon'], $checkday, $start['year']);
				if($dayts != $endcheck) {
					$ret[] = $dayts;
				}
			}
		}
		//do not send the availability information about the checkout day
		//$ret[] = $second;
		return $ret;
	}
	
	private function getHoursRoomAvail() {
		$dbo = JFactory::getDBO();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='hoursmoreroomavail';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$s = $dbo->loadAssocList();
		return $s[0]['setting'];
	}
	
	/**
	* Stores a notification in the db for VikChannelManager
	* Type can be: 0 (Error), 1 (Success), 2 (Warning)
	*/
	private function saveNotify($type, $from, $cont, $idordervb = '') {
		$dbo = JFactory::getDBO();
		$q = "INSERT INTO `#__vikchannelmanager_notifications` (`ts`,`type`,`from`,`cont`,`idordervb`,`read`) VALUES('".time()."', '".$type."', '".$from."', ".$dbo->quote($cont).", '".$idordervb."', 0);";
		$dbo->setQuery($q);
		$dbo->Query($q);
		return true;
	}
	
	/**
	* Stores multiple notifications in the db for VikChannelManager
	*/
	private function saveMultipleNotifications($arr_rs) {
		$dbo = JFactory::getDBO();
		$gen_type = 1;
		foreach ($arr_rs as $chid => $chrs) {
			if (substr($chrs, 0, 9) == 'e4j.error') {
				$gen_type = 0;
				break;
			}elseif (substr($chrs, 0, 11) == 'e4j.warning') {
				$gen_type = 2;
			}
		}
		//Store parent notification
		$q = "INSERT INTO `#__vikchannelmanager_notifications` (`ts`,`type`,`from`,`cont`,`idordervb`,`read`) VALUES('".time()."', ".$gen_type.", 'VCM', ".$dbo->quote('Availability Update RQ').", ".$this->order_id.", 0);";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$id_parent = $dbo->insertId();
		if(!empty($id_parent)) {
			//Store child notifications
			foreach ($arr_rs as $chid => $chrs) {
				if (substr($chrs, 0, 9) == 'e4j.error') {
					$q = "INSERT INTO `#__vikchannelmanager_notification_child` (`id_parent`,`type`,`cont`,`channel`) VALUES(".$id_parent.", 0, ".$dbo->quote($chrs).", ".(int)$chid.");";
					$dbo->setQuery($q);
					$dbo->Query($q);
				}elseif (substr($chrs, 0, 11) == 'e4j.warning') {
					$q = "INSERT INTO `#__vikchannelmanager_notification_child` (`id_parent`,`type`,`cont`,`channel`) VALUES(".$id_parent.", 2, ".$dbo->quote($chrs).", ".(int)$chid.");";
					$dbo->setQuery($q);
					$dbo->Query($q);
				}else {
					$q = "INSERT INTO `#__vikchannelmanager_notification_child` (`id_parent`,`type`,`cont`,`channel`) VALUES(".$id_parent.", 1, ".$dbo->quote($chrs).", ".(int)$chid.");";
					$dbo->setQuery($q);
					$dbo->Query($q);
				}
			}
		}
		
		return true;
	}
	
	/**
	* Generates and Saves a notification key for e4jConnect and VikChannelManager
	* 
	*/
	private function generateNKey($idordervb) {
		$nkey = rand(1000, 9999);
		$dbo = JFactory::getDBO();
		$q = "INSERT INTO `#__vikchannelmanager_keys` (`idordervb`,`key`) VALUES('".$idordervb."', '".$nkey."');";
		$dbo->setQuery($q);
		$dbo->Query($q);
		return $nkey;
	}
	
}


?>
