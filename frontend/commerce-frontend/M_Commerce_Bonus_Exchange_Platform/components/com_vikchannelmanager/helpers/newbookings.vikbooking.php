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
* This Class is used by VikChannelManager to process the new bookings
* received from e4jConnect in the BR_L task.
* Saves the new bookings into VikBooking and returns a response for
* e4jConnect
* 
*/

class newBookingsVikBooking {
	
	private $config;
	private $arrbookings;
	private $cypher;
	private $roomsinfomap;
	private $vbCheckinSeconds;
	private $vbCheckoutSeconds;
	private $vbhoursmorebookingback;
	private $totbookings;
	private $savedbookings;
	private $arrconfirmnumbers;
	private $errorString;
	private $response;
	
	public function __construct ($config, $arrbookings) {
		$this->config = $config;
		$this->arrbookings = $arrbookings;
		if (!class_exists('Encryption')) {
			require_once(JPATH_SITE.DS.'components'.DS.'com_vikchannelmanager'.DS.'helpers'.DS.'cypher.php');
		}
		$this->cypher = new Encryption(md5($this->config['apikey']));
		$this->roomsinfomap = array();
		$this->vbCheckinSeconds = '';
		$this->vbCheckoutSeconds = '';
		$this->vbhoursmorebookingback = '';
		$this->totbookings = count($arrbookings['orders']);
		$this->savedbookings = 0;
		$this->arrconfirmnumbers = array();
		$this->errorString = '';
		$this->response = 'e4j.error';
	}
	
	/**
	* Main method called by VikChannelManager in the BR_L task
	* Processes the information received from e4jConnect
	* Returns the response for e4jConnect
	*/
	public function processNewBookings () {
		if(intval($this->config['vikbookingsynch']) == 1) {
			foreach($this->arrbookings['orders'] as $order) {
				if ($this->checkOrderIntegrity($order)) {
					switch ($order['info']['ordertype']) {
						case 'Book':
							$result = $this->saveBooking($order);
							break;
						case 'Modify':
							$result = $this->modifyBooking($order);
							break;
						case 'Cancel':
							$result = $this->cancelBooking($order);
							break;
						case 'Download':
							$result = $this->downloadedBooking($order);
							break;
						default:
							break;
					}
					if ($result === true) {
						$this->savedbookings++;
					}
				}else {
					//save notification in VCM for invalid order format received from e4jConnect
					$this->saveNotify('0', ucwords($this->config['channel']['name']), "e4j.error.Channels.InvalidBooking\n".$this->getError()."\n".print_r($order, true));
				}
				if (strlen($this->getError()) > 0) {
					//TODO: store error log somewhere? not in notifies but maybe in a log table
					$this->errorString = '';
				}
			}
			//Set the response for e4jConnect to the serialized array with the confirmation numbers or to an ok string
			if (count($this->arrconfirmnumbers) > 0) {
				$this->arrconfirmnumbers['auth'] = md5($this->config['apikey'].'rs_e4j');
				$this->response = serialize($this->arrconfirmnumbers);
			}else {
				$this->response = 'e4j.ok.savedbookingsindb:0.savedbookings:'.$this->savedbookings;
			}
		}else {
			$this->response = 'e4j.ok.vcmsynchdisabled';
		}
		return $this->response;
	}
	
	/**
	* Checks that the single booking is valid and not
	* missing some required values to be processed and saved
	*/
	public function checkOrderIntegrity ($order) {
		$otype = '';
		switch ($order['info']['ordertype']) {
			case 'Book':
				$otype = 'Book';
				break;
			case 'Modify':
				$otype = 'Modify';
				break;
			case 'Cancel':
				$otype = 'Cancel';
				break;
			case 'Download':
				$otype = 'Download';
				break;
			default:
				$this->setError("1) checkOrderIntegrity: empty oType");
				return false;
				break;
		}
		//booking id, booking type, check-in, check-out
		$validate = array($order['info']['idorderota'], $order['info']['ordertype'], $order['info']['checkin'], $order['info']['checkout']);
		foreach($validate as $k => $elem) {
			if (strlen($elem) < 1) {
				if($otype != 'Cancel') {
					$this->setError("2) checkOrderIntegrity: empty index ".$k);
					return false;
				}else {
					//Booking Cancel may return empty checkin and checkout for some channels
					if((int)$k < 2) {
						$this->setError("3) checkOrderIntegrity: empty index ".$k);
						return false;
					}
				}
			}
		}
		if ($otype != 'Cancel' && strlen($order['roominfo']['idroomota']) < 1 && (array_key_exists(0, $order['roominfo']) && strlen($order['roominfo'][0]['idroomota']) < 1)) {
			$this->setError("4) checkOrderIntegrity: empty IDRoomOTA");
			return false;
		}
		return true;
	}
	
	/**
	* Saves a new booking into VikBooking
	* @param array $order
	*/
	public function saveBooking ($order) {
		if (!$this->otaBookingExists($order['info']['idorderota'])) {
			//idroomvb mapping the idroomota
			//check whether the room is one or more
			if(array_key_exists(0, $order['roominfo'])) {
				if(count($order['roominfo']) > 1) {
					//multiple rooms
					$check_idroomota = array();
					foreach ($order['roominfo'] as $rk => $ordr) {
						$check_idroomota[] = $ordr['idroomota'];
					}
				}else {
					//single room
					$check_idroomota = $order['roominfo'][0]['idroomota'];
				}
			}else {
				//single room
				$check_idroomota = $order['roominfo']['idroomota'];
			}
			//
			$idroomvb = $this->mapIdroomVbFromOtaId($check_idroomota);
			if (((!is_array($idroomvb) && intval($idroomvb) > 0) || (is_array($idroomvb) && count($idroomvb) > 0)) && $idroomvb !== false) {
				//check-in and check-out timestamps, num of nights for VikBooking
				$checkints = $this->getCheckinTimestamp($order['info']['checkin']);
				$checkoutts = $this->getCheckoutTimestamp($order['info']['checkout']);
				$numnights = $this->countNumberOfNights($checkints, $checkoutts);
				if ($checkints > 0 && $checkoutts > 0 && $numnights > 0) {
					//count num people, total order, compose customer info, purchaser email, special request
					$adults = 0;
					$children = 0;
					if (strlen($order['info']['adults']) > 0) {
						$adults = $order['info']['adults'];
					}
					if (strlen($order['info']['children']) > 0) {
						$children = $order['info']['children'];
					}
					$total = 0;
					if (strlen($order['info']['total']) > 0) {
						$total = (float)$order['info']['total'];
					}
					$customerinfo = '';
					$purchaseremail = '';
					if (array_key_exists('customerinfo', $order)) {
						foreach($order['customerinfo'] as $what => $cinfo) {
							$customerinfo .= ucwords($what).": ".$cinfo."\n";
						}
						if (array_key_exists('email', $order['customerinfo'])) {
							$purchaseremail = $order['customerinfo']['email'];
						}
					}
					//add information about Breakfast, Extra-bed, IATA, Promotion and such
					if(array_key_exists('breakfast_included', $order['info'])) {
						$customerinfo .= 'Breakfast Included: '.$order['info']['breakfast_included']."\n";
					}
					if(array_key_exists('extrabed', $order['info'])) {
						$customerinfo .= 'Extra Bed: '.$order['info']['extrabed']."\n";
					}
					if(array_key_exists('IATA', $order['info'])) {
						$customerinfo .= 'IATA ID: '.$order['info']['IATA']."\n";
					}
					if(array_key_exists('promotion', $order['info'])) {
						$customerinfo .= 'Promotion: '.$order['info']['promotion']."\n";
					}
					if(array_key_exists('loyalty_id', $order['info'])) {
						$customerinfo .= 'Loyalty ID: '.$order['info']['loyalty_id']."\n";
					}
					//
					$customerinfo = rtrim($customerinfo, "\n");

					//check if the room is available
					$room_available = false;
					if(is_array($idroomvb)) {
						$room_available = $this->roomsAreAvailableInVb($idroomvb, $order, $checkints, $checkoutts, $numnights);
						//TODO: if $room_available is an array it means that some rooms were not available
						//administrator should be notified because one or more rooms, not all of them, is in overbooking
					}else {
						$check_idroomota_key = array_key_exists(0, $order['roominfo']) ? $order['roominfo'][0]['idroomota'] : $order['roominfo']['idroomota'];
						$room_available = $this->roomIsAvailableInVb($idroomvb, $this->roomsinfomap[$check_idroomota_key]['totunits'], $checkints, $checkoutts, $numnights);
					}
					//
					if($room_available === true || @is_array($room_available)) {
						//decode credit card details
						$order['info']['credit_card'] = $this->processCreditCardDetails($order);
						
						//Save the new order, set confirmnumber for the booking id in the class array arrconfirmnumbers and save notification in VCM
						$newdata = $this->saveNewVikBookingOrder($order, $idroomvb, $checkints, $checkoutts, $numnights, $adults, $children, $total, $customerinfo, $purchaseremail);
						
						//Compose information about the RatePlan Name and the Payment
						$rateplan_info = $this->mapPriceVbFromRatePlanId($order);
						$notification_extra = '';
						if (!empty($rateplan_info)) {
							$notification_extra .= "\n".$rateplan_info;
						}
						if (count($order['info']['price_breakdown']) > 0) {
							$notification_extra .= "\nPrice Breakdown:\n";
							foreach ($order['info']['price_breakdown'] as $day => $cost) {
								$notification_extra .= $day." - ".$order['info']['currency'].' '.$cost."\n";
							}
							$notification_extra = rtrim($notification_extra, "\n");
						}
						if (count($order['info']['credit_card']) > 0) {
							$notification_extra .= "\nCredit Card:\n";
							foreach ($order['info']['credit_card'] as $card_info => $card_data) {
								if($card_info == 'card_number_pci') {
									//do not touch this part or you will lose any PCI-compliant function
									continue;
								}
								if (is_array($card_data)) {
									$notification_extra .= ucwords(str_replace('_', ' ', $card_info)).":\n";
									foreach ($card_data as $card_info_in => $card_data_in) {
										$notification_extra .= ucwords(str_replace('_', ' ', $card_info_in)).": ".$card_data_in."\n";
									}
								}else {
									$notification_extra .= ucwords(str_replace('_', ' ', $card_info)).": ".$card_data."\n";
								}
							}
							$notification_extra = rtrim($notification_extra, "\n");
						}
						//
						$this->saveNotify('1', ucwords($this->config['channel']['name']), "e4j.OK.Channels.NewBookingDownloaded".$notification_extra, $newdata['newvborderid']);
						//add values to be returned as serialized to e4jConnect as response
						$this->arrconfirmnumbers[$order['info']['idorderota']]['ordertype'] = 'Book';
						$this->arrconfirmnumbers[$order['info']['idorderota']]['confirmnumber'] = $newdata['confirmnumber'];
						$this->arrconfirmnumbers[$order['info']['idorderota']]['vborderid'] = $newdata['newvborderid'];
						$this->arrconfirmnumbers[$order['info']['idorderota']]['nkey'] = $this->generateNKey($newdata['newvborderid']);
						//
						//Notify AV=1-Channels for the new booking
						if (!class_exists('synchVikBooking')) {
							require_once(JPATH_SITE . DS ."components". DS ."com_vikchannelmanager". DS . "helpers" . DS ."synch.vikbooking.php");
						}
						$vcm = new synchVikBooking($newdata['newvborderid'], array($this->config['channel']['uniquekey']));
						$vcm->sendRequest();
						//
						return true;
					}else {
						//The room results not available in VikBooking, notify Administrator but return true anyways for e4jConnect
						//a notification will be saved also inside VCM. All of this only if booking does not come from iCal Download
						if(!array_key_exists('iCal', $order)) {
							$errmsg = $this->notifyAdministratorRoomNotAvailable($order);
							$this->saveNotify('0', ucwords($this->config['channel']['name']), "e4j.error.Channels.BookingDownload\n".$errmsg);
						}
						return true;
					}
				}else {
					$this->setError("2) saveBooking: OTAid: ".$order['info']['idorderota']." empty or invalid stay dates (".$order['info']['checkin']." - ".$order['info']['checkout'].")");
				}
			}else {
				$this->setError("1) saveBooking: OTAid: ".$order['info']['idorderota']." - OTARoom ".$order['roominfo']['idroomota'].", not mapped");
			}
		}
		return false;
	}
	
	/**
	* Modifies a booking of VikBooking
	* @param array $order
	*/
	public function modifyBooking ($order) {
		$dbo = JFactory::getDBO();
		if ($vborderinfo = $this->otaBookingExists($order['info']['idorderota'], true)) {
			//idroomvb mapping the idroomota
			//check whether the room is one or more
			if(array_key_exists(0, $order['roominfo'])) {
				if(count($order['roominfo']) > 1) {
					//multiple rooms
					$check_idroomota = array();
					foreach ($order['roominfo'] as $rk => $ordr) {
						$check_idroomota[] = $ordr['idroomota'];
					}
				}else {
					//single room
					$check_idroomota = $order['roominfo'][0]['idroomota'];
				}
			}else {
				//single room
				$check_idroomota = $order['roominfo']['idroomota'];
			}
			//
			$idroomvb = $this->mapIdroomVbFromOtaId($check_idroomota);
			if (((!is_array($idroomvb) && intval($idroomvb) > 0) || (is_array($idroomvb) && count($idroomvb) > 0)) && $idroomvb !== false) {
				//check-in and check-out timestamps, num of nights for VikBooking
				$checkints = $this->getCheckinTimestamp($order['info']['checkin']);
				$checkoutts = $this->getCheckoutTimestamp($order['info']['checkout']);
				$numnights = $this->countNumberOfNights($checkints, $checkoutts);
				if ($checkints > 0 && $checkoutts > 0 && $numnights > 0) {
					//count num people, total order, compose customer info, purchaser email, special request
					$adults = 0;
					$children = 0;
					if (strlen($order['info']['adults']) > 0) {
						$adults = $order['info']['adults'];
					}
					if (strlen($order['info']['children']) > 0) {
						$children = $order['info']['children'];
					}
					$total = 0;
					if (strlen($order['info']['total']) > 0) {
						$total = (float)$order['info']['total'];
					}
					$customerinfo = '';
					$purchaseremail = '';
					if (array_key_exists('customerinfo', $order)) {
						foreach($order['customerinfo'] as $what => $cinfo) {
							$customerinfo .= ucwords($what).": ".$cinfo."\n";
						}
						if (array_key_exists('email', $order['customerinfo'])) {
							$purchaseremail = $order['customerinfo']['email'];
						}
					}
					//add information about Breakfast, Extra-bed, IATA, Promotion and such
					if(array_key_exists('breakfast_included', $order['info'])) {
						$customerinfo .= 'Breakfast Included: '.$order['info']['breakfast_included']."\n";
					}
					if(array_key_exists('extrabed', $order['info'])) {
						$customerinfo .= 'Extra Bed: '.$order['info']['extrabed']."\n";
					}
					if(array_key_exists('IATA', $order['info'])) {
						$customerinfo .= 'IATA ID: '.$order['info']['IATA']."\n";
					}
					if(array_key_exists('promotion', $order['info'])) {
						$customerinfo .= 'Promotion: '.$order['info']['promotion']."\n";
					}
					if(array_key_exists('loyalty_id', $order['info'])) {
						$customerinfo .= 'Loyalty ID: '.$order['info']['loyalty_id']."\n";
					}
					//
					$customerinfo = rtrim($customerinfo, "\n");

					//check if the room is available
					//get the busy ids for the order
					$excludebusyids = array();
					$q = "SELECT * FROM `#__vikbooking_ordersbusy` WHERE `idorder`='".$vborderinfo['id']."';";
					$dbo->setQuery($q);
					$dbo->Query($q);
					if ($dbo->getNumRows() > 0) {
						$ordbusy = $dbo->loadAssocList();
						foreach($ordbusy as $ob) {
							$excludebusyids[] = $ob['idbusy'];
						}
					}
					$room_available = false;
					if(is_array($idroomvb)) {
						$room_available = $this->roomsAreAvailableInVbModification($idroomvb, $order, $checkints, $checkoutts, $numnights, $excludebusyids);
						//TODO: if $room_available is an array it means that some rooms were not available for modification
						//administrator should be notified because one or more rooms, not all of them, is in overbooking
					}else {
						$check_idroomota_key = array_key_exists(0, $order['roominfo']) ? $order['roominfo'][0]['idroomota'] : $order['roominfo']['idroomota'];
						$room_available = $this->roomIsAvailableInVbModification($idroomvb, $this->roomsinfomap[$check_idroomota_key]['totunits'], $checkints, $checkoutts, $numnights, $excludebusyids);
					}
					//
					if($room_available === true || @is_array($room_available)) {
						//delete old busy ids
						if (count($excludebusyids) > 0) {
							$q = "DELETE FROM `#__vikbooking_busy` WHERE `id` IN (".implode(", ", $excludebusyids).");";
							$dbo->setQuery($q);
							$dbo->Query($q);
						}
						$q = "DELETE FROM `#__vikbooking_ordersrooms` WHERE `idorder`=".(int)$vborderinfo['id'].";";
						$dbo->setQuery($q);
						$dbo->Query($q);
						$q = "DELETE FROM `#__vikbooking_ordersbusy` WHERE `idorder`=".(int)$vborderinfo['id'].";";
						$dbo->setQuery($q);
						$dbo->Query($q);
						//always set $idroomvb to an array even if it is just a string
						$orig_idroomvb = $idroomvb;
						unset($idroomvb);
						if(is_array($orig_idroomvb)) {
							$idroomvb = array_values($orig_idroomvb);
						}else {
							$idroomvb = array($orig_idroomvb);
						}
						//
						//insert new busy and room data
						//Number of Rooms
						$num_rooms = 1;
						if(array_key_exists('num_rooms', $order['info']) && intval($order['info']['num_rooms']) > 1) {
							$num_rooms = intval($order['info']['num_rooms']);
						}
						//
						$busy_ids = array();
						for($i = 1; $i <= $num_rooms; $i++) {
							$room_checkints = $checkints;
							$room_checkoutts = $checkoutts;
							//Set checkin and check out dates for each room if they are different than the check-in or check-out date of the booking (Booking.com)
							if(array_key_exists(($i - 1), $order['roominfo']) && array_key_exists('checkin', $order['roominfo'][($i - 1)]) && array_key_exists('checkout', $order['roominfo'][($i - 1)])) {
								if($order['roominfo'][($i - 1)]['checkin'] != $order['info']['checkin'] || $order['roominfo'][($i - 1)]['checkout'] != $order['info']['checkout']) {
									$room_checkints = $this->getCheckinTimestamp($order['roominfo'][($i - 1)]['checkin']);
									$room_checkoutts = $this->getCheckinTimestamp($order['roominfo'][($i - 1)]['checkout']);
								}
							}
							//
							$q = "INSERT INTO `#__vikbooking_busy` (`idroom`,`checkin`,`checkout`,`realback`) VALUES('" . $idroomvb[($i - 1)] . "', '" . $room_checkints . "', '" . $room_checkoutts . "','" . $room_checkoutts . "');";
							$dbo->setQuery($q);
							$dbo->Query($q);
							$busyid = $dbo->insertid();
							$busy_ids[$i] = $busyid;
						}
						//Adults and Children are returned as total by the OTA. If multiple rooms, dispose the Adults and Children accordingly
						if($num_rooms > 1) {
							$adults_per_room = floor($adults / $num_rooms);
							$adults_per_room = $adults_per_room < 0 ? 0 : $adults_per_room;
							$spare_adults = ($adults - ($adults_per_room * $num_rooms));
							$children_per_room = floor($children / $num_rooms);
							$children_per_room = $children_per_room < 0 ? 0 : $children_per_room;
							$spare_children = ($children - ($children_per_room * $num_rooms));
							for($i = 1; $i <= $num_rooms; $i++) {
								$adults_occupancy = $adults_per_room;
								$children_occupancy = $children_per_room;
								if($i == 1 && ($spare_adults > 0 || $spare_children > 0)) {
									$adults_occupancy += $spare_adults;
									$children_occupancy += $spare_children;
								}
								$rooms_aduchild[$i]['adults'] = $adults_occupancy;
								$rooms_aduchild[$i]['children'] = $children_occupancy;
							}
						}else {
							$rooms_aduchild[$num_rooms]['adults'] = $adults;
							$rooms_aduchild[$num_rooms]['children'] = $children;
						}
						//
						$has_different_checkins_notif = false;
						$traveler_first_name = array_key_exists('traveler_first_name', $order['info']) ? $order['info']['traveler_first_name'] : '';
						$traveler_last_name = array_key_exists('traveler_last_name', $order['info']) ? $order['info']['traveler_last_name'] : '';
						//Assign room specific unit
						$set_room_indexes = $this->autoRoomUnit();
						$room_indexes_usemap = array();
						//
						foreach($busy_ids as $num_room => $id_busy) {
							$q = "INSERT INTO `#__vikbooking_ordersbusy` (`idorder`,`idbusy`) VALUES(".(int)$vborderinfo['id'].", ".(int)$id_busy.");";
							$dbo->setQuery($q);
							$dbo->Query($q);
							//traveler name for each room if available
							$room_t_first_name = $traveler_first_name;
							$room_t_last_name = $traveler_last_name;
							if(array_key_exists(($num_room - 1), $order['roominfo'])) {
								if(strlen($order['roominfo'][($num_room - 1)]['traveler_first_name'])) {
									$room_t_first_name = $order['roominfo'][($num_room - 1)]['traveler_first_name'];
									$room_t_last_name = $order['roominfo'][($num_room - 1)]['traveler_last_name'];
								}
							}
							//
							//Set checkin and check out dates next to traveler name if they are different than the check-in or check-out (Booking.com)
							if(array_key_exists(($num_room - 1), $order['roominfo']) && array_key_exists('checkin', $order['roominfo'][($num_room - 1)]) && array_key_exists('checkout', $order['roominfo'][($num_room - 1)])) {
								if($order['roominfo'][($num_room - 1)]['checkin'] != $order['info']['checkin'] || $order['roominfo'][($num_room - 1)]['checkout'] != $order['info']['checkout']) {
									$room_t_last_name .= ' ('.$order['roominfo'][($num_room - 1)]['checkin'].' - '.$order['roominfo'][($num_room - 1)]['checkout'].')';
									//notification details (Booking.com) with guests, check-in and check-out dates for this room
									if(!is_array($has_different_checkins_notif)) {
										unset($has_different_checkins_notif);
										$has_different_checkins_notif = array();
									}
									$has_different_checkins_notif[] = $this->roomsinfomap[$order['roominfo'][($num_room - 1)]['idroomota']]['roomnamevb'].' - Check-in: '.$order['roominfo'][($num_room - 1)]['checkin'].' - Check-out: '.$order['roominfo'][($num_room - 1)]['checkout'].' - Guests: '.$order['roominfo'][($num_room - 1)]['guests'];
									//
								}else {
									//Maybe the check-in and check-out dates for the whole booking have now been set to the same ones as for this room, compare it with the old order with the date format Y-m-d
									$booking_prev_checkin = date('Y-m-d', $vborderinfo['checkin']);
									$booking_prev_checkout = date('Y-m-d', $vborderinfo['checkout']);
									if($order['roominfo'][($num_room - 1)]['checkin'] != $booking_prev_checkin || $order['roominfo'][($num_room - 1)]['checkout'] != $booking_prev_checkout) {
										//notification details (Booking.com) with guests, check-in and check-out dates for this room
										if(!is_array($has_different_checkins_notif)) {
											unset($has_different_checkins_notif);
											$has_different_checkins_notif = array();
										}
										$has_different_checkins_notif[] = $this->roomsinfomap[$order['roominfo'][($num_room - 1)]['idroomota']]['roomnamevb'].' - Check-in: '.$order['roominfo'][($num_room - 1)]['checkin'].' - Check-out: '.$order['roominfo'][($num_room - 1)]['checkout'].' - Guests: '.$order['roominfo'][($num_room - 1)]['guests'];
										//
									}
								}
							}
							//
							//Assign room specific unit
							$room_indexes = $set_room_indexes === true ? $this->getRoomUnitNumsAvailable(array('id' => $vborderinfo['id'], 'checkin' => $checkints, 'checkout' => $checkoutts), $idroomvb[($num_room - 1)]) : array();
							$use_ind_key = 0;
							if(count($room_indexes)) {
								if(!array_key_exists($idroomvb[($num_room - 1)], $room_indexes_usemap)) {
									$room_indexes_usemap[$idroomvb[($num_room - 1)]] = $use_ind_key;
								}else {
									$use_ind_key = $room_indexes_usemap[$idroomvb[($num_room - 1)]];
								}
								$rooms[$num]['roomindex'] = (int)$room_indexes[$use_ind_key];
							}
							//
							$q = "INSERT INTO `#__vikbooking_ordersrooms` (`idorder`,`idroom`,`adults`,`children`,`t_first_name`,`t_last_name`".(count($room_indexes) ? ",`roomindex`" : "").") VALUES(".(int)$vborderinfo['id'].", ".(int)$idroomvb[($num_room - 1)].", ".(int)$rooms_aduchild[$num_room]['adults'].", ".(int)$rooms_aduchild[$num_room]['children'].", ".$dbo->quote($room_t_first_name).", ".$dbo->quote($room_t_last_name).(count($room_indexes) ? ", ".(int)$room_indexes[$use_ind_key] : "").");";
							$dbo->setQuery($q);
							$dbo->Query($q);
							//Assign room specific unit
							if(count($room_indexes)) {
								$room_indexes_usemap[$idroomvb[($num_room - 1)]]++;
							}
							//
						}
						//update order record
						$q = "UPDATE `#__vikbooking_orders` SET " .
								"`custdata`=".$dbo->quote($customerinfo)."," .
								"`ts`='".time()."'," .
								"`days`='".$numnights."'," .
								"`checkin`='".$checkints."'," .
								"`checkout`='".$checkoutts."'," .
								"`custmail`=".$dbo->quote($purchaseremail)."," .
								"`roomsnum`=".$num_rooms."," .
								"`total`=".$dbo->quote($total)."," .
								"`channel`=".$dbo->quote($this->config['channel']['name'].'_'.$order['info']['source'])."," .
								"`chcurrency`=".$dbo->quote($order['info']['currency'])." " .
								"WHERE `id`='".$vborderinfo['id']."';";
						$dbo->setQuery($q);
						$dbo->Query($q);
						//compose notification detail message
						$notifymess = "OTA Booking ID: ".$order['info']['idorderota']."\n";
						if($has_different_checkins_notif === false) {
							//only if the check-in and check-out are the same for each room
							$notifymess .= "Check-in: ".$order['info']['checkin']." (Before Modification: ".date('Y-m-d', $vborderinfo['checkin']).")\n";
							$notifymess .= "Check-out: ".$order['info']['checkout']." (Before Modification: ".date('Y-m-d', $vborderinfo['checkout']).")\n";
						}
						$oldroomdata = "";
						if(array_key_exists('rooms_info', $vborderinfo) && count($vborderinfo['rooms_info']) > 0) {
							$prev_adults = 0;
							$prev_children = 0;
							$prev_rooms = array();
							foreach ($vborderinfo['rooms_info'] as $room_info) {
								$prev_adults += $room_info['adults'];
								$prev_children += $room_info['children'];
								$prev_rooms[] = $room_info['roomnamevb'];
							}
							$oldroomdata = " (Before Modification: ".implode(", ", $prev_rooms)." - Adults: ".$prev_adults.($prev_children > 0 ? " - Children: ".$prev_children : "").")";
						}
						$all_vb_room_names = array();
						foreach ($this->roomsinfomap as $idrota => $room_det) {
							$all_vb_room_names[] = $room_det['roomnamevb'];
						}
						if($has_different_checkins_notif === false) {
							$notifymess .= "Room: ".implode(', ', $all_vb_room_names)." - Adults: ".$adults.($children > 0 ? " - Children: ".$children : "").$oldroomdata."\n";
						}else {
							//only if the check-in and check-out are different for some rooms (Booking.com)
							$notifymess .= "Rooms:\n".implode("\n", $has_different_checkins_notif)."\n".ltrim($oldroomdata);
						}
						//decode credit card details
						$order['info']['credit_card'] = $this->processCreditCardDetails($order);
						$notification_extra = '';
						$price_breakdown = '';
						//Price Breakdown
						if (count($order['info']['price_breakdown']) > 0) {
							$price_breakdown .= "\nPrice Breakdown:\n";
							foreach ($order['info']['price_breakdown'] as $day => $cost) {
								$price_breakdown .= $day." - ".$order['info']['currency'].' '.$cost."\n";
							}
							$price_breakdown = rtrim($price_breakdown, "\n");
						}
						//
						$payment_log = '';
						if (count($order['info']['credit_card']) > 0) {
							$notification_extra .= "\nCredit Card:\n";
							foreach ($order['info']['credit_card'] as $card_info => $card_data) {
								if($card_info == 'card_number_pci') {
									//do not touch this part or you will lose any PCI-compliant function
									continue;
								}
								if (is_array($card_data)) {
									$notification_extra .= ucwords(str_replace('_', ' ', $card_info)).":\n";
									foreach ($card_data as $card_info_in => $card_data_in) {
										$notification_extra .= ucwords(str_replace('_', ' ', $card_info_in)).": ".$card_data_in."\n";
									}
								}else {
									$notification_extra .= ucwords(str_replace('_', ' ', $card_info)).": ".$card_data."\n";
								}
							}
							$payment_log = $notification_extra."\n\n";
						}
						//Update payment log with credit card details
						if(!empty($payment_log)) {
							$q = "UPDATE `#__vikbooking_orders` SET `paymentlog`=CONCAT(".$dbo->quote($payment_log).", `paymentlog`) WHERE `id`='".$vborderinfo['id']."';";
							$dbo->setQuery($q);
							$dbo->Query($q);
							$this->sendCreditCardDetails($order);
						}
						//
						$notifymess .= $price_breakdown.$notification_extra;
						//
						$this->saveNotify('1', ucwords($this->config['channel']['name']), "e4j.OK.Channels.BookingModified\n".$notifymess, $vborderinfo['id']);
						//add values to be returned as serialized to e4jConnect as response
						$this->arrconfirmnumbers[$order['info']['idorderota']]['ordertype'] = 'Modify';
						$this->arrconfirmnumbers[$order['info']['idorderota']]['confirmnumber'] = $vborderinfo['confirmnumber'].'mod';
						$this->arrconfirmnumbers[$order['info']['idorderota']]['vborderid'] = $vborderinfo['id'];
						$this->arrconfirmnumbers[$order['info']['idorderota']]['nkey'] = $this->generateNKey($vborderinfo['id']);
						//Notify AV=1-Channels for the booking modification
						if (!class_exists('synchVikBooking')) {
							require_once(JPATH_SITE . DS ."components". DS ."com_vikchannelmanager". DS . "helpers" . DS ."synch.vikbooking.php");
						}
						$vcm = new synchVikBooking($vborderinfo['id'], array($this->config['channel']['uniquekey']));
						$vcm->setFromModification($vborderinfo);
						$vcm->sendRequest();
						//

						return true;
					}else {
						//The room results not available for modification in VikBooking, notify Administrator but return true anyways for e4jConnect
						//a notification will be saved also inside VCM
						$errmsg = $this->notifyAdministratorRoomNotAvailableModification($order, $vborderinfo['id']);
						$this->saveNotify('0', ucwords($this->config['channel']['name']), "e4j.error.Channels.BookingModification\n".$errmsg);
						return true;
					}
				}else {
					$this->setError("2) modifyBooking: OTAid: ".$order['info']['idorderota']." empty stay dates");
				}
			}else {
				$this->setError("1) modifyBooking: OTAid: ".$order['info']['idorderota']." - OTARoom ".(is_array($check_idroomota) ? $check_idroomota[0] : $check_idroomota).", not mapped");
			}
		}else {
			//The booking to modify does not exist in VikBooking or was cancelled before, notify VCM administrator
			$message = JText::sprintf('VCMOTAMODORDERNOTFOUND', ucwords($this->config['channel']['name']), $order['info']['idorderota'], (is_array($check_idroomota) ? $check_idroomota[0] : $check_idroomota));
			@mail($this->config['emailadmin'], JText::_('VCMOTAMODORDERNOTFOUNDSUBJ'), $message, "MIME-Version: 1.0" . "\r\n" . "Content-type: text/plain; charset=UTF-8" . "\r\n" . "From: ".$this->config['emailadmin'] . "\r\n" . "Reply-To: ".$this->config['emailadmin']);
		}
		return false;
	}
	
	/**
	* Cancels a booking of VikBooking
	* @param array $order
	*/
	public function cancelBooking ($order) {
		if ($vborderinfo = $this->otaBookingExists($order['info']['idorderota'], true)) {
			$dbo = JFactory::getDBO();
			$notifymess = "OTA Booking ID: ".$order['info']['idorderota']."\n";
			$notifymess .= "Check-in: ".$order['info']['checkin']."\n";
			$notifymess .= "Check-out: ".$order['info']['checkin']."\n";
			$q = "SELECT * FROM `#__vikbooking_ordersbusy` WHERE `idorder`='".$vborderinfo['id']."';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() > 0) {
				$ordbusy = $dbo->loadAssocList();
				foreach($ordbusy as $ob) {
					$q = "DELETE FROM `#__vikbooking_busy` WHERE `id`='".$ob['idbusy']."';";
					$dbo->setQuery($q);
					$dbo->Query($q);
				}
			}
			//load room details
			$q = "SELECT `or`.`idroom`,`or`.`adults`,`or`.`children`,`r`.`name` FROM `#__vikbooking_ordersrooms` AS `or` LEFT JOIN `#__vikbooking_rooms` `r` ON `or`.`idroom`=`r`.`id` WHERE `or`.`idorder`='".$vborderinfo['id']."';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() > 0) {
				$orderrooms = $dbo->loadAssocList();
				foreach($orderrooms as $or) {
					$notifymess .= "Room: ".$or['name']." - Adults: ".$or['adults'].($or['children'] > 0 ? " - Children: ".$or['children'] : "")."\n";
				}
			}
			$notifymess .= $vborderinfo['custdata']."\n";
			$notifymess .= $vborderinfo['custmail'];
			$q = "DELETE FROM `#__vikbooking_ordersbusy` WHERE `idorder`='".$vborderinfo['id']."';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$q = "UPDATE `#__vikbooking_orders` SET `status`='cancelled' WHERE `id`='".$vborderinfo['id']."';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			
			$this->saveNotify('1', ucwords($this->config['channel']['name']), "e4j.OK.Channels.BookingCancelled\n".$notifymess);
			//add values to be returned as serialized to e4jConnect as response
			$this->arrconfirmnumbers[$order['info']['idorderota']]['ordertype'] = 'Cancel';
			$this->arrconfirmnumbers[$order['info']['idorderota']]['confirmnumber'] = $vborderinfo['confirmnumber'].'canc';
			$this->arrconfirmnumbers[$order['info']['idorderota']]['vborderid'] = $vborderinfo['id'];
			$this->arrconfirmnumbers[$order['info']['idorderota']]['nkey'] = $this->generateNKey($vborderinfo['id']);
			//
			//Notify AV=1-Channels for the booking cancellation
			if (!class_exists('synchVikBooking')) {
				require_once(JPATH_SITE . DS ."components". DS ."com_vikchannelmanager". DS . "helpers" . DS ."synch.vikbooking.php");
			}
			$vcm = new synchVikBooking($vborderinfo['id'], array($this->config['channel']['uniquekey']));
			$vcm->setFromCancellation($vborderinfo);
			$vcm->sendRequest();
			//
			return true;
		}else {
			//The booking to cancel does not exist in VikBooking or was cancelled before, notify VCM administrator
			$all_ota_room_ids = array();
			if(array_key_exists(0, $order['roominfo'])) {
				foreach ($order['roominfo'] as $rinfo) {
					$all_ota_room_ids[] = $rinfo['idroomota'];
				}
			}else {
				$all_ota_room_ids[] = $order['roominfo']['idroomota'];
			}
			$message = JText::sprintf('VCMOTACANCORDERNOTFOUND', ucwords($this->config['channel']['name']), $order['info']['idorderota'], implode(', ', $all_ota_room_ids));
			@mail($this->config['emailadmin'], JText::_('VCMOTACANCORDERNOTFOUNDSUBJ'), $message, "MIME-Version: 1.0" . "\r\n" . "Content-type: text/plain; charset=UTF-8" . "\r\n" . "From: ".$this->config['emailadmin'] . "\r\n" . "Reply-To: ".$this->config['emailadmin']);
		}
		return false;
	}
	
	/**
	* Checks whether the downloaded booking was already processed.
	* This function is used for parsing bookings that were originally in ICS format
	* so a lot of them may have been downloaded already
	* @param array $order
	*/
	public function downloadedBooking ($order) {
		if ($vborderinfo = $this->otaBookingExists($order['info']['idorderota'], true, true)) {
			//booking previously downloaded, check if the dates have changed
			if ($vborderinfo['status'] != 'cancelled' && (date('Y-m-d', $vborderinfo['checkin']) != $order['info']['checkin'] || date('Y-m-d', $vborderinfo['checkout']) != $order['info']['checkout'])) {
				return $this->modifyBooking($order);
			}
		}else {
			//The booking was never downloaded, save it onto VikBooking
			return $this->saveBooking($order);
		}
		return false;
	}
	
	/**
	* Decodes the credit card details and returns an array with
	* PCI-compliant values that can be stored in the database
	* @param $order
	*/
	private function processCreditCardDetails($order) {
		$credit_card = array();
		if (!empty($order['info']['credit_card'])) {
			$decoded_card = $this->cypher->decode($order['info']['credit_card']);
			$decoded_card = unserialize($decoded_card);
			if ($decoded_card !== false && is_array($decoded_card)) {
				if(strpos($decoded_card['card_number'], '*') === false) {
					//Mask credit card if not masked already
					$cc = str_replace(' ', '', trim($decoded_card['card_number']));
					$cc_num_len = strlen($cc);
					$cc_hidden = '';
					$cc_pci = '';
					if( $cc_num_len == 14 ) {
						// Diners Club
						$cc_hidden .= substr($cc, 0, 4)." **** **** **";
						$app = "****".substr($cc, 4, 10);
						for( $i = 1; $i <= $cc_num_len; $i++ ) {
							$cc_pci .= $app[$i-1].($i%4 == 0 ? ' ':'');
						}
					}elseif( $cc_num_len == 15 ) {
						// American Express
						$cc_hidden .= "**** ****** ".substr($cc, 10, 5);
						$app = substr($cc, 0, 10)."*****";
						for( $i = 1; $i <= $cc_num_len; $i++ ) {
							$cc_pci .= $app[$i-1].($i==4 || $i==10 ? ' ':'');
						}
					}else {
						// Master Card, Visa, Discover, JCB
						$cc_hidden .= "**** **** **** ".substr($cc, 12, 4);
						$app = substr($cc, 0, 12)."****";
						for( $i = 1; $i <= $cc_num_len; $i++ ) {
							$cc_pci .= $app[$i-1].($i%4 == 0 ? ' ':'');
						}
					}
					$decoded_card['card_number'] = $cc_hidden;
					$decoded_card['card_number_pci'] = $cc_pci;
					//
				}
				$credit_card = $decoded_card;
			}
		}
		
		return $credit_card;
	}
	
	/**
	* Sends via email to the administrator email address
	* the PCI-compliant and remaining number of the 
	* credit card returned by the channel
	* @param $order
	*/
	private function sendCreditCardDetails($order) {
		if (!array_key_exists('card_number_pci', $order['info']['credit_card'])) {
			return false;
		}
		$vik = new VikApplication(VersionListener::getID());
		$admail = $this->config['emailadmin'];
		$vik->sendMail(
				$admail,
				$admail,
				$admail,
				$admail,
				JText::_('VCMCHANNELNEWORDERMAILSUBJECT'),
				JText::sprintf('VCMCHANNELNEWORDERMAILCONTENT', $order['info']['idorderota'], ucwords($this->config['channel']['name']), $order['info']['credit_card']['card_number_pci'], $order['order_link']),
				false
		);
		return true;
	}
	
	/**
	* Saves the new order from the OTA in the DB tables of VikBooking
	* @param array orderinfo
	* @param idroomvb
	* @param checkints
	* @param checkoutts
	* @param numnights
	* @param adults
	* @param children
	* @param total
	* @param customerinfo
	* @param purchaseremail
	*/
	public function saveNewVikBookingOrder ($order, $idroomvb, $checkints, $checkoutts, $numnights, $adults, $children, $total, $customerinfo, $purchaseremail) {
		$dbo = JFactory::getDBO();
		//default number of adults
		if ((int)$adults == 0 && (int)$children == 0 && !is_array($idroomvb)) {
			$q = "SELECT `fromadult` FROM `#__vikbooking_rooms` WHERE `id`=".(int)$idroomvb.";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() == 1) {
				$num_adults = $dbo->loadResult();
				if(intval($num_adults) > 0) {
					$adults = (int)$num_adults;
				}
			}
		}
		//
		$orderinfo = $order['info'];
		$tot_taxes = 0;
		if (!empty($orderinfo['tax']) && floatval($orderinfo['tax']) > 0) {
			$tot_taxes = floatval($orderinfo['tax']);
		}
		//Compose payment log
		$payment_log = '';
		if (count($order['info']['credit_card']) > 0) {
			$payment_log .= "Credit Card Details:\n";
			foreach ($order['info']['credit_card'] as $card_info => $card_data) {
				if($card_info == 'card_number_pci') {
					//do not touch this part or you will lose any PCI-compliance function
					continue;
				}
				if (is_array($card_data)) {
					$payment_log .= ucwords(str_replace('_', ' ', $card_info)).":\n";
					foreach ($card_data as $card_info_in => $card_data_in) {
						$payment_log .= ucwords(str_replace('_', ' ', $card_info_in)).": ".$card_data_in."\n";
					}
				}else {
					$payment_log .= ucwords(str_replace('_', ' ', $card_info)).": ".$card_data."\n";
				}
			}
			$payment_log = rtrim($payment_log, "\n");
		}
		//
		//always set $idroomvb to an array even if it is just a string
		$orig_idroomvb = $idroomvb;
		unset($idroomvb);
		if(is_array($orig_idroomvb)) {
			$idroomvb = array_values($orig_idroomvb);
		}else {
			$idroomvb = array($orig_idroomvb);
		}
		//
		//Phone Number and Customers Management (VikBooking 1.6 or higher, check if cpin.php exists - since v1.6)
		$do_customer_management = false;
		$phone = '';
		if(file_exists(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_vikbooking'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'cpin.php')) {
			$do_customer_management = true;
			if(array_key_exists('telephone', $order['customerinfo'])) {
				if(!empty($order['customerinfo']['telephone'])) {
					$phone = $order['customerinfo']['telephone'];
				}
			}
		}
		//
		//Country
		$country = '';
		if(array_key_exists('country', $order['customerinfo'])) {
			if(!empty($order['customerinfo']['country'])) {
				if(strlen($order['customerinfo']['country']) == 3) {
					$country = $order['customerinfo']['country'];
				}elseif(strlen($order['customerinfo']['country']) == 2) {
					$q = "SELECT `country_3_code` FROM `#__vikbooking_countries` WHERE `country_2_code`=".$dbo->quote($order['customerinfo']['country']).";";
					$dbo->setQuery($q);
					$dbo->Query($q);
					if($dbo->getNumRows() == 1) {
						$country = $dbo->loadResult();
					}
				}
			}
		}
		//
		//Nominative
		$traveler_first_name = array_key_exists('traveler_first_name', $orderinfo) ? $orderinfo['traveler_first_name'] : '';
		$traveler_last_name = array_key_exists('traveler_last_name', $orderinfo) ? $orderinfo['traveler_last_name'] : '';
		//
		//Number of Rooms
		$num_rooms = 1;
		if(array_key_exists('num_rooms', $order['info']) && intval($order['info']['num_rooms']) > 1) {
			$num_rooms = intval($order['info']['num_rooms']);
		}
		//
		$busy_ids = array();
		for($i = 1; $i <= $num_rooms; $i++) {
			$q = "INSERT INTO `#__vikbooking_busy` (`idroom`,`checkin`,`checkout`,`realback`) VALUES('" . $idroomvb[($i - 1)] . "', '" . $checkints . "', '" . $checkoutts . "','" . $checkoutts . "');";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$busyid = $dbo->insertid();
			$busy_ids[$i] = $busyid;
		}
		$q = "INSERT INTO `#__vikbooking_orders` (`custdata`,`ts`,`status`,`days`,`checkin`,`checkout`,`custmail`,`sid`,`totpaid`,`ujid`,`coupon`,`roomsnum`,`total`,`idorderota`,`channel`,`chcurrency`,`paymentlog`,`country`,`tot_taxes`".(!empty($phone) ? ",`phone`" : '').($this->setCommissions() ? ",`cmms`" : '').") VALUES(" .
			"" . $dbo->quote($customerinfo) . ",'" . time() . "','confirmed','" . $numnights . "','" . $checkints . "','" . $checkoutts . "'," . $dbo->quote($purchaseremail) . ",'','0','0','',".$num_rooms.",". $dbo->quote($total) .",". $dbo->quote($orderinfo['idorderota']) .",". $dbo->quote($this->config['channel']['name'].'_'.$orderinfo['source']) .",". $dbo->quote($orderinfo['currency']) .",". $dbo->quote($payment_log) .",".(!empty($country) ? $dbo->quote($country) : 'NULL').",".$tot_taxes."".(!empty($phone) ? ','.$dbo->quote($phone) : '').($this->setCommissions() ? ','.(array_key_exists('commission_amount', $order['info']) ? $dbo->quote($order['info']['commission_amount']) : "NULL") : '').");";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$neworderid = $dbo->insertid();
		//notify the administrator with the credit credit card details for PCI-compliance
		$order['order_link'] = JURI::root().'administrator/index.php?option=com_vikbooking&task=editorder&cid[]='.$neworderid.'#paymentlog';
		$this->sendCreditCardDetails($order);
		//
		$confirmnumber = $this->generateConfirmNumber($neworderid, true);
		$rooms_aduchild = array();
		//Adults and Children are returned as total by the OTA. If multiple rooms, dispose the Adults and Children accordingly
		if($num_rooms > 1) {
			$adults_per_room = floor($adults / $num_rooms);
			$adults_per_room = $adults_per_room < 0 ? 0 : $adults_per_room;
			$spare_adults = ($adults - ($adults_per_room * $num_rooms));
			$children_per_room = floor($children / $num_rooms);
			$children_per_room = $children_per_room < 0 ? 0 : $children_per_room;
			$spare_children = ($children - ($children_per_room * $num_rooms));
			for($i = 1; $i <= $num_rooms; $i++) {
				$adults_occupancy = $adults_per_room;
				$children_occupancy = $children_per_room;
				if($i == 1 && ($spare_adults > 0 || $spare_children > 0)) {
					$adults_occupancy += $spare_adults;
					$children_occupancy += $spare_children;
				}
				$rooms_aduchild[$i]['adults'] = $adults_occupancy;
				$rooms_aduchild[$i]['children'] = $children_occupancy;
			}
		}else {
			$rooms_aduchild[$num_rooms]['adults'] = $adults;
			$rooms_aduchild[$num_rooms]['children'] = $children;
		}
		//
		//Assign room specific unit
		$set_room_indexes = $this->autoRoomUnit();
		$room_indexes_usemap = array();
		//
		foreach($busy_ids as $num_room => $id_busy) {
			$q = "INSERT INTO `#__vikbooking_ordersbusy` (`idorder`,`idbusy`) VALUES(".(int)$neworderid.", ".(int)$id_busy.");";
			$dbo->setQuery($q);
			$dbo->Query($q);
			//traveler name for each room if available
			$room_t_first_name = $traveler_first_name;
			$room_t_last_name = $traveler_last_name;
			if(array_key_exists(($num_room - 1), $order['roominfo'])) {
				if(strlen($order['roominfo'][($num_room - 1)]['traveler_first_name'])) {
					$room_t_first_name = $order['roominfo'][($num_room - 1)]['traveler_first_name'];
					$room_t_last_name = $order['roominfo'][($num_room - 1)]['traveler_last_name'];
				}
			}
			//
			//Assign room specific unit
			$room_indexes = $set_room_indexes === true ? $this->getRoomUnitNumsAvailable(array('id' => $neworderid, 'checkin' => $checkints, 'checkout' => $checkoutts), $idroomvb[($num_room - 1)]) : array();
			$use_ind_key = 0;
			if(count($room_indexes)) {
				if(!array_key_exists($idroomvb[($num_room - 1)], $room_indexes_usemap)) {
					$room_indexes_usemap[$idroomvb[($num_room - 1)]] = $use_ind_key;
				}else {
					$use_ind_key = $room_indexes_usemap[$idroomvb[($num_room - 1)]];
				}
				$rooms[$num]['roomindex'] = (int)$room_indexes[$use_ind_key];
			}
			//
			$q = "INSERT INTO `#__vikbooking_ordersrooms` (`idorder`,`idroom`,`adults`,`children`,`t_first_name`,`t_last_name`".(count($room_indexes) ? ",`roomindex`" : "").") VALUES(".(int)$neworderid.", ".(int)$idroomvb[($num_room - 1)].", ".(int)$rooms_aduchild[$num_room]['adults'].", ".(int)$rooms_aduchild[$num_room]['children'].", ".$dbo->quote($room_t_first_name).", ".$dbo->quote($room_t_last_name).(count($room_indexes) ? ", ".(int)$room_indexes[$use_ind_key] : "").");";
			$dbo->setQuery($q);
			$dbo->Query($q);
			//Assign room specific unit
			if(count($room_indexes)) {
				$room_indexes_usemap[$idroomvb[($num_room - 1)]]++;
			}
			//
		}
		$insertdata = array('newvborderid' => $neworderid, 'confirmnumber' => $confirmnumber);

		//save customer (VikBooking 1.6 or higher)
		if($do_customer_management === true && !empty($traveler_first_name) && !empty($traveler_last_name) && !empty($purchaseremail)) {
			if(!class_exists('VikBookingCustomersPin')) {
				require_once(JPATH_SITE . DIRECTORY_SEPARATOR ."components". DIRECTORY_SEPARATOR ."com_vikbooking". DIRECTORY_SEPARATOR . "helpers" . DIRECTORY_SEPARATOR ."cpin.php");
			}
			$cpin = new VikBookingCustomersPin();
			$cpin->saveCustomerDetails($traveler_first_name, $traveler_last_name, $purchaseremail, $phone, $country);
			$cpin->saveCustomerBooking($neworderid);
		}
		//
		
		return $insertdata;
	}
	
	/**
	* VikBooking v1.7 or higher.
	* If the method exists, check whether the room specific unit should be assigned to the booking.
	*/	
	private function autoRoomUnit() {
		if(!class_exists('vikbooking')) {
			require_once(JPATH_SITE . DIRECTORY_SEPARATOR ."components". DIRECTORY_SEPARATOR ."com_vikbooking". DIRECTORY_SEPARATOR . "helpers" . DIRECTORY_SEPARATOR ."lib.vikbooking.php");
		}
		if(method_exists('vikbooking', 'autoRoomUnit')) {
			return vikbooking::autoRoomUnit();
		}
		return false;
	}

	/**
	* VikBooking v1.7 or higher.
	* If the method exists, return the specific indexes available.
	* @param $order array
	* @param $roomid int
	*/	
	private function getRoomUnitNumsAvailable($order, $roomid) {
		if(!class_exists('vikbooking')) {
			require_once(JPATH_SITE . DIRECTORY_SEPARATOR ."components". DIRECTORY_SEPARATOR ."com_vikbooking". DIRECTORY_SEPARATOR . "helpers" . DIRECTORY_SEPARATOR ."lib.vikbooking.php");
		}
		if(method_exists('vikbooking', 'getRoomUnitNumsAvailable')) {
			return vikbooking::getRoomUnitNumsAvailable($order, $roomid);
		}
		return array();
	}

	/**
	* VikBooking v1.7 or higher.
	* The commissions amount is only supported by the v1.7 or higher. Check if a method of that version exists.
	*/	
	private function setCommissions() {
		if(!class_exists('vikbooking')) {
			require_once(JPATH_SITE . DIRECTORY_SEPARATOR ."components". DIRECTORY_SEPARATOR ."com_vikbooking". DIRECTORY_SEPARATOR . "helpers" . DIRECTORY_SEPARATOR ."lib.vikbooking.php");
		}
		return method_exists('vikbooking', 'autoRoomUnit') ? true : false;
	}

	/**
	* Generates a confirmation number for the order and returns it.
	* It can also update the order record with it.
	* @param $oid
	* @param $update
	*/
	public function generateConfirmNumber($oid, $update = true) {
		$confirmnumb = date('ym');
		$confirmnumb .= (string)rand(100, 999);
		$confirmnumb .= (string)rand(10, 99);
		$confirmnumb .= (string)$oid;
		if($update) {
			$dbo = JFactory::getDBO();
			$q="UPDATE `#__vikbooking_orders` SET `confirmnumber`='".$confirmnumb."' WHERE `id`='".$oid."';";
			$dbo->setQuery($q);
			$dbo->Query($q);
		}
		return $confirmnumb;
	}
	
	/**
	* Checks if the given OTA booking exists in VikBooking
	* @param $idorderota
	* @param $retvbid
	* @param $cancelled
	*/
	public function otaBookingExists ($idorderota, $retvbid = false, $cancelled = false) {
		$dbo = JFactory::getDBO();
		if(!(strlen($idorderota) > 0)) {
			return false;
		}
		$q = "SELECT * FROM `#__vikbooking_orders` WHERE ".(!$cancelled ? "`status`!='cancelled' AND " : "")."`idorderota`=" . $dbo->quote($idorderota) . " AND `channel` LIKE '".$this->config['channel']['name']."%';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			if ($retvbid === true) {
				$fetch = $dbo->loadAssocList();
				$q = "SELECT `or`.`idroom`,`or`.`adults`,`or`.`children`,`or`.`idtar`,`or`.`optionals`,`or`.`childrenage`,`or`.`t_first_name`,`or`.`t_last_name`,`r`.`name` AS `roomnamevb`,`r`.`units` FROM `#__vikbooking_ordersrooms` AS `or` LEFT JOIN `#__vikbooking_rooms` `r` ON `or`.`idroom`=`r`.`id` WHERE `or`.`idorder`=".(int)$fetch[0]['id'].";";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if ($dbo->getNumRows() > 0) {
					$rooms_data = $dbo->loadAssocList();
					$fetch[0]['rooms_info'] = $rooms_data;
				}
				return $fetch[0];
			}else {
				return true;
			}
		}
		return false;
	}
	
	/**
	* Maps the corresponding IdRoom in VikBooking to the IdRoomOta
	* In case the room belongs to more than one room of VikBooking
	* only the first active one is returned.
	* It also stores some values in the class array roomsinfomap
	* for later actions like room name, room total units.
	* If the ID is negative then it's because the downloaded booking
	* in ICS format is generic for the entire property. The absolute
	* value of the number will be taken in that case.
	* $idroomota could also be an array of room type id because some
	* channels allow bookings of multiple rooms, different ones (Booking.com)
	* @param mixed $idroomota
	* @return mixed string idroomvb or array idroomvb
	*/
	public function mapIdroomVbFromOtaId ($idroomota) {
		$dbo = JFactory::getDBO();
		if (!is_array($idroomota) && intval($idroomota) < 0) {
			$pos_id = (int)abs((float)$idroomota);
			$q = "SELECT `id`,`name`,`units` FROM `#__vikbooking_rooms` WHERE `id`=".$pos_id.";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() > 0) {
				$assocs = $dbo->loadAssocList();
				$this->roomsinfomap[$idroomota]['idroomvb'] = $assocs[0]['id'];
				$this->roomsinfomap[$idroomota]['roomnamevb'] = $assocs[0]['name'];
				$this->roomsinfomap[$idroomota]['totunits'] = $assocs[0]['units'];
				return $assocs[0]['id'];
			}
		}
		if (!is_array($idroomota)) {
			$q = "SELECT `x`.`idroomvb`,`vbr`.`name`,`vbr`.`units` FROM `#__vikchannelmanager_roomsxref` AS `x` " .
				"LEFT JOIN `#__vikbooking_rooms` `vbr` ON `x`.`idroomvb`=`vbr`.`id` " .
				"WHERE `x`.`idroomota`=".$dbo->quote($idroomota)." AND `x`.`idchannel`='".$this->config['channel']['uniquekey']."' AND `vbr`.`avail`='1' " .
				"ORDER BY `x`.`id` ASC;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() > 0) {
				$assocs = $dbo->loadAssocList();
				$this->roomsinfomap[$idroomota]['idroomvb'] = $assocs[0]['idroomvb'];
				$this->roomsinfomap[$idroomota]['roomnamevb'] = $assocs[0]['name'];
				$this->roomsinfomap[$idroomota]['totunits'] = $assocs[0]['units'];
				return $assocs[0]['idroomvb'];
			}
		}else {
			if(!(count($idroomota) > 0)) {
				return false;
			}
			$roomsota_count_map = array();
			$in_clause = array();
			foreach ($idroomota as $k => $v) {
				$in_clause[$k] = $dbo->quote($v);
				$roomsota_count_map[$v] = empty($roomsota_count_map[$v]) ? 1 : ($roomsota_count_map[$v] + 1);
			}
			$q = "SELECT `x`.`idroomvb`,`x`.`idroomota`,`vbr`.`name`,`vbr`.`units` FROM `#__vikchannelmanager_roomsxref` AS `x` " .
				"LEFT JOIN `#__vikbooking_rooms` `vbr` ON `x`.`idroomvb`=`vbr`.`id` " .
				"WHERE `x`.`idroomota` IN (".implode(', ', array_unique($in_clause)).") AND `x`.`idchannel`='".$this->config['channel']['uniquekey']."' AND `vbr`.`avail`='1' " .
				"GROUP BY `x`.`idroomota` ORDER BY `x`.`id` ASC LIMIT ".count($in_clause).";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() > 0) {
				$idroomvb = array();
				$assocs = $dbo->loadAssocList();
				foreach ($assocs as $rass) {
					$idroomvb[] = $rass['idroomvb'];
					if($roomsota_count_map[$rass['idroomota']] > 1) {
						for($i = 1; $i < $roomsota_count_map[$rass['idroomota']]; $i++) {
							$idroomvb[] = $rass['idroomvb'];
						}
					}
					$this->roomsinfomap[$rass['idroomota']]['idroomvb'] = $rass['idroomvb'];
					$this->roomsinfomap[$rass['idroomota']]['roomnamevb'] = $rass['name'];
					$this->roomsinfomap[$rass['idroomota']]['totunits'] = $rass['units'];
				}
				return count($idroomvb) > 0 ? $idroomvb : false;
			}
		}

		return false;
	}
	
	/**
	* Maps the corresponding Price in VikBooking to the OTA RatePlanID
	* @param $order
	*/
	public function mapPriceVbFromRatePlanId ($order) {
		$dbo = JFactory::getDBO();
		if(array_key_exists(0, $order['roominfo'])) {
			//multiple rooms or channel supporting multiple rooms
			$idroomota = array();
			$idroomota_plain = array();
			$otarateplanid = array();
			foreach ($order['roominfo'] as $rk => $rinfo) {
				$idroomota[$rk] = $dbo->quote($rinfo['idroomota']);
				$idroomota_plain[$rk] = (int)$rinfo['idroomota'];
				$otarateplanid[$rk] = $rinfo['rateplanid'];
			}
			$q = "SELECT `x`.`idroomota`,`x`.`otapricing`,`vbr`.`name` FROM `#__vikchannelmanager_roomsxref` AS `x` " .
				"LEFT JOIN `#__vikbooking_rooms` `vbr` ON `x`.`idroomvb`=`vbr`.`id` " .
				"WHERE `x`.`idroomota` IN (".implode(',', $idroomota).") AND `x`.`idchannel`='".$this->config['channel']['uniquekey']."' " .
				"GROUP BY `x`.`idroomota` ORDER BY `x`.`id` ASC;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() > 0) {
				$assocs = $dbo->loadAssocList();
				$rateplan_info = array();
				foreach ($idroomota_plain as $kk => $rota_id) {
					foreach ($assocs as $k => $rp) {
						if($rota_id == (int)$rp['idroomota']) {
							if (!empty($rp['otapricing'])) {
								$otapricing = json_decode($rp['otapricing'], true);
								if (!is_null($otapricing) && @count($otapricing) > 0 && @count($otapricing['RatePlan']) > 0) {
									foreach ($otapricing['RatePlan'] as $rpid => $orp) {
										if ((string)$rpid == (string)$otarateplanid[$kk]) {
											$rateplan_info[] = $orp['name'];
											break;
										}
									}
								}
							}
							break;
						}
					}
				}
				if(count($rateplan_info) > 0) {
					return 'RatePlan: '.implode(', ', $rateplan_info);
				}
			}
		}else {
			//single room
			$idroomota = $order['roominfo']['idroomota'];
			$otarateplanid = $order['roominfo']['rateplanid'];
			$q = "SELECT `x`.`otapricing`,`vbr`.`name` FROM `#__vikchannelmanager_roomsxref` AS `x` " .
				"LEFT JOIN `#__vikbooking_rooms` `vbr` ON `x`.`idroomvb`=`vbr`.`id` " .
				"WHERE `x`.`idroomota`=".$dbo->quote($idroomota)." AND `x`.`idchannel`='".$this->config['channel']['uniquekey']."' " .
				"ORDER BY `x`.`id` ASC;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() > 0) {
				$assocs = $dbo->loadAssocList();
				if (!empty($assocs[0]['otapricing'])) {
					$otapricing = json_decode($assocs[0]['otapricing'], true);
					if (!is_null($otapricing) && @count($otapricing) > 0 && @count($otapricing['RatePlan']) > 0) {
						foreach ($otapricing['RatePlan'] as $rpid => $rp) {
							if ((string)$rpid == (string)$otarateplanid) {
								return 'RatePlan: '.$rp['name'];
							}
						}
					}
				}
			}
		}
		return false;
	}
	
	/**
	* Calculates and Returns the timestamp for the Checkin
	* Adding the hours and minutes of the VikBooking
	* Checkin time to the OTA Arrival Date.
	* The method also sets the class variables
	* vbCheckinSeconds and vbCheckoutSeconds
	* @param checkindate
	*/
	public function getCheckinTimestamp ($checkindate) {
		$timestamp = 0;
		$parts = explode('-', trim($checkindate));
		$basets = strtotime($parts[1].'/'.$parts[2].'/'.$parts[0]);
		$timestamp += $basets;
		if (strlen($this->vbCheckinSeconds) > 0) {
			$timestamp += $this->vbCheckinSeconds;
		}else {
			$timeopst = $this->getTimeOpenStore();
			if (is_array($timeopst)) {
				$opent = $timeopst[0];
				$closet = $timeopst[1];
			}else {
				$opent = 0;
				$closet = 0;
			}
			$timestamp += $opent;
			$this->vbCheckinSeconds = $opent;
			$this->vbCheckoutSeconds = $closet;
		}
		return $timestamp;
	}
	
	/**
	* Calculates and Returns the timestamp for the Checkout
	* Adding the hours and minutes of the VikBooking
	* Checkout time to the OTA Arrival Date.
	* The method also sets the class variables
	* vbCheckinSeconds and vbCheckoutSeconds
	* @param checkoutdate
	*/
	public function getCheckoutTimestamp ($checkoutdate) {
		$timestamp = 0;
		$parts = explode('-', trim($checkoutdate));
		$basets = strtotime($parts[1].'/'.$parts[2].'/'.$parts[0]);
		$timestamp += $basets;
		if (strlen($this->vbCheckoutSeconds) > 0) {
			$timestamp += $this->vbCheckoutSeconds;
		}else {
			$timeopst = $this->getTimeOpenStore();
			if (is_array($timeopst)) {
				$opent = $timeopst[0];
				$closet = $timeopst[1];
			}else {
				$opent = 0;
				$closet = 0;
			}
			$timestamp += $closet;
			$this->vbCheckinSeconds = $opent;
			$this->vbCheckoutSeconds = $closet;
		}
		return $timestamp;
	}
	
	/**
	* Gets the configuration value of VikBooking for the
	* opening time used by the check-in and the check-out
	* Returns the values or false
	*/
	public function getTimeOpenStore () {
		$dbo = JFactory::getDBO();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='timeopenstore';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$n = $dbo->loadAssocList();
		if (empty ($n[0]['setting']) && $n[0]['setting'] != "0") {
			return false;
		} else {
			$x = explode("-", $n[0]['setting']);
			if (!empty ($x[1]) && $x[1] != "0") {
				return $x;
			}
		}
		return false;
	}
	
	/**
	* Counts and Returns the number of nights with the given
	* Arrival and Departure timestamps previously calculated
	* @param checkints
	* @param checkoutts
	*/
	public function countNumberOfNights ($checkints, $checkoutts) {
		if (empty($checkints) || empty($checkoutts)) {
			return 0;
		}
		$secdiff = $checkoutts - $checkints;
		$daysdiff = $secdiff / 86400;
		if (is_int($daysdiff)) {
			if ($daysdiff < 1) {
				$daysdiff = 1;
			}
		}else {
			if ($daysdiff < 1) {
				$daysdiff = 1;
			}else {
				$sum = floor($daysdiff) * 86400;
				$newdiff = $secdiff - $sum;
				$maxhmore = $this->getHoursMoreRb() * 3600;
				if ($maxhmore >= $newdiff) {
					$daysdiff = floor($daysdiff);
				}else {
					$daysdiff = ceil($daysdiff);
				}
			}
		}
		return $daysdiff;
	}
	
	/**
	* Gets and Returns the setting in the configuration of
	* VikBooking hoursmorebookingback. Sets the class variable
	* vbhoursmorebookingback for later cycles
	*/
	public function getHoursMoreRb () {
		if (strlen($this->vbhoursmorebookingback) > 0) {
			return $this->vbhoursmorebookingback;
		}
		$dbo = JFactory::getDBO();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='hoursmorebookingback';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$s = $dbo->loadAssocList();
		$this->vbhoursmorebookingback = $s[0]['setting'];
		return $s[0]['setting'];
	}
	
	/**
	* Checks if at least one unit of the given room is available
	* for the given checkin and checkout dates
	* @param idroomvb
	* @param totunits
	* @param checkin
	* @param checkout
	* @param numnights
	*/
	public function roomIsAvailableInVb ($idroomvb, $totunits, $checkin, $checkout, $numnights) {
		$dbo = JFactory::getDBO();
		$groupdays = $this->getGroupDays($checkin, $checkout, $numnights);
		$q = "SELECT `id`,`checkin`,`realback` FROM `#__vikbooking_busy` WHERE `idroom`=" . (int)$idroomvb . " AND `realback` > ".(int)$checkin.";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$busy = $dbo->loadAssocList();
			foreach ($groupdays as $gday) {
				$bfound = 0;
				foreach ($busy as $bu) {
					if ($gday >= $bu['checkin'] && $gday <= $bu['realback']) {
						$bfound++;
					}
				}
				if ($bfound >= $totunits) {
					return false;
				}
			}
		}
		return true;
	}
	
	/**
	* Checks if at least one unit of the given room is available
	* for the given checkin and checkout dates excluding the 
	* busy ids for the old VikBooking order
	* @param idroomvb
	* @param totunits
	* @param checkin
	* @param checkout
	* @param numnights
	* @param excludebusyids
	*/
	public function roomIsAvailableInVbModification ($idroomvb, $totunits, $checkin, $checkout, $numnights, $excludebusyids) {
		$dbo = JFactory::getDBO();
		$groupdays = $this->getGroupDays($checkin, $checkout, $numnights);
		$q = "SELECT `id`,`checkin`,`realback` FROM `#__vikbooking_busy` WHERE `idroom`=" . (int)$idroomvb . "".(count($excludebusyids) > 0 ? " AND `id` NOT IN (".implode(", ", $excludebusyids).")" : "")." AND `realback` > ".(int)$checkin.";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$busy = $dbo->loadAssocList();
			foreach ($groupdays as $gday) {
				$bfound = 0;
				foreach ($busy as $bu) {
					if ($gday >= $bu['checkin'] && $gday <= $bu['realback']) {
						$bfound++;
					}
				}
				if ($bfound >= $totunits) {
					return false;
				}
			}
		}
		return true;
	}

	/**
	* Checks if all the rooms booked (more than one) are available
	* for the given checkin and checkout dates.
	* @param idroomsvb
	* @param order
	* @param checkin
	* @param checkout
	* @param numnights
	* @return mixed bool true, false or array in case some of the rooms are not available but not all
	*/
	public function roomsAreAvailableInVb ($idroomsvb, $order, $checkin, $checkout, $numnights) {
		if(!is_array($idroomsvb) || !(count($idroomsvb) > 0)) {
			return false;
		}
		$dbo = JFactory::getDBO();
		$groupdays = $this->getGroupDays($checkin, $checkout, $numnights);
		$q = "SELECT `b`.*,`r`.`units` AS `room_tot_units` FROM `#__vikbooking_busy` AS `b` LEFT JOIN `#__vikbooking_rooms` `r` ON `r`.`id`=`b`.`idroom` WHERE `b`.`idroom` IN (" . implode(',', array_unique($idroomsvb)) . ") AND `b`.`realback` > ".(int)$checkin.";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$busy = $dbo->loadAssocList();
			$busy_rooms = array();
			foreach ($busy as $bu) {
				$busy_rooms[$bu['idroom']] = $bu;
			}
			//check if multiple units of the same room were booked
			$rooms_count_map = array();
			$tot_rooms_booked = 0;
			foreach ($idroomsvb as $idr) {
				$rooms_count_map[(int)$idr] = empty($rooms_count_map[(int)$idr]) ? 1 : ($rooms_count_map[(int)$idr] + 1);
				$tot_rooms_booked++;
			}
			//now the array can be unique
			$idroomsvb = array_unique($idroomsvb);
			//rooms that are not available
			$rooms_not_available = array();
			//
			foreach ($idroomsvb as $kr => $idr) {
				if(array_key_exists((int)$idr, $busy_rooms)) {
					foreach ($groupdays as $gday) {
						$bfound = 0;
						$totunits = 1;
						foreach ($busy_rooms[(int)$idr] as $bu) {
							$totunits = $bu['room_tot_units'];
							if ($gday >= $bu['checkin'] && $gday <= $bu['realback']) {
								$bfound++;
							}
						}
						if(($bfound + intval($rooms_count_map[$idr]) - 1) >= $totunits) {
							$rooms_not_available[] = (int)$idr;
						}
					}
				}
			}
			if(count($rooms_not_available) > 0) {
				//some rooms are not available
				if(count($rooms_not_available) < $tot_rooms_booked) {
					//some rooms may still be available but not all, return the array in this case
					return $rooms_not_available;
				}else {
					//none of the rooms booked is available
					return false;
				}
			}else {
				return true;
			}
		}
		return true;
	}

	/**
	* Checks if all the rooms booked (more than one) are available
	* for the given checkin and checkout dates excluding the 
	* busy ids for the old VikBooking order.
	* @param idroomsvb
	* @param order
	* @param checkin
	* @param checkout
	* @param numnights
	* @param excludebusyids
	* @return mixed bool true, false or array in case some of the rooms are not available but not all
	*/
	public function roomsAreAvailableInVbModification ($idroomsvb, $order, $checkin, $checkout, $numnights, $excludebusyids) {
		if(!is_array($idroomsvb) || !(count($idroomsvb) > 0)) {
			return false;
		}
		$dbo = JFactory::getDBO();
		$groupdays = $this->getGroupDays($checkin, $checkout, $numnights);
		$q = "SELECT `b`.*,`r`.`units` AS `room_tot_units` FROM `#__vikbooking_busy` AS `b` LEFT JOIN `#__vikbooking_rooms` `r` ON `r`.`id`=`b`.`idroom` WHERE `b`.`idroom` IN (" . implode(',', array_unique($idroomsvb)) . ")".(count($excludebusyids) > 0 ? " AND `b`.`id` NOT IN (".implode(", ", $excludebusyids).")" : "")." AND `b`.`realback` > ".(int)$checkin.";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$busy = $dbo->loadAssocList();
			$busy_rooms = array();
			foreach ($busy as $bu) {
				$busy_rooms[$bu['idroom']] = $bu;
			}
			//check if multiple units of the same room were booked
			$rooms_count_map = array();
			$tot_rooms_booked = 0;
			foreach ($idroomsvb as $idr) {
				$rooms_count_map[(int)$idr] = empty($rooms_count_map[(int)$idr]) ? 1 : ($rooms_count_map[(int)$idr] + 1);
				$tot_rooms_booked++;
			}
			//now the array can be unique
			$idroomsvb = array_unique($idroomsvb);
			//rooms that are not available
			$rooms_not_available = array();
			//
			foreach ($idroomsvb as $kr => $idr) {
				if(array_key_exists((int)$idr, $busy_rooms)) {
					$use_groupdays = $groupdays;
					//Check if some rooms have a different check-in or check-out date than the booking information (Booking.com)
					foreach ($order['roominfo'] as $rcount => $ota_room) {
						if(array_key_exists($ota_room['idroomota'], $this->roomsinfomap)) {
							//Room has been mapped, check if it is this one
							if($this->roomsinfomap[$ota_room['idroomota']]['idroomvb'] == $idr) {
								if(array_key_exists('checkin', $ota_room) && array_key_exists('checkout', $ota_room)) {
									if($ota_room['checkin'] != $order['info']['checkin'] || $ota_room['checkout'] != $order['info']['checkout']) {
										$use_checkints = $this->getCheckinTimestamp($ota_room['checkin']);
										$use_checkoutts = $this->getCheckoutTimestamp($ota_room['checkout']);
										$use_numnights = $this->countNumberOfNights($use_checkints, $use_checkoutts);
										$use_groupdays = $this->getGroupDays($use_checkints, $use_checkoutts, $use_numnights);
									}
								}
							}
						}
					}
					//
					foreach ($use_groupdays as $gday) {
						$bfound = 0;
						$totunits = 1;
						foreach ($busy_rooms[(int)$idr] as $bu) {
							$totunits = $bu['room_tot_units'];
							if ($gday >= $bu['checkin'] && $gday <= $bu['realback']) {
								$bfound++;
							}
						}
						if(($bfound + intval($rooms_count_map[$idr]) - 1) >= $totunits) {
							$rooms_not_available[] = (int)$idr;
						}
					}
				}
			}
			if(count($rooms_not_available) > 0) {
				//some rooms are not available
				if(count($rooms_not_available) < $tot_rooms_booked) {
					//some rooms may still be available but not all, return the array in this case
					return $rooms_not_available;
				}else {
					//none of the rooms booked is available
					return false;
				}
			}else {
				return true;
			}
		}
		return true;
	}
	
	/**
	* Gets all the days between the checkin and the checkout.
	* Here the last day so the departure must be considered
	* to see if the room is available in VikBooking
	* @param checkin
	* @param checkout
	* @param numnights
	*/
	function getGroupDays($checkin, $checkout, $numnights) {
		$ret = array();
		$ret[] = $checkin;
		if($numnights > 1) {
			$start = getdate($checkin);
			$end = getdate($checkout);
			$endcheck = mktime(0, 0, 0, $end['mon'], $end['mday'], $end['year']);
			for($i = 1; $i < $numnights; $i++) {
				$checkday = $start['mday'] + $i;
				$dayts = mktime(0, 0, 0, $start['mon'], $checkday, $start['year']);
				if($dayts != $endcheck) {				
					$ret[] = $dayts;
				}
			}
		}
		$ret[] = $checkout;
		return $ret;
	}
	
	/**
	* Sends an email to the Administrator saying that the room was not
	* available for the dates requested in the order received from the OTA.
	* Returns the error message composed to be stored inside the VCM notifications
	* @param order
	*/
	public function notifyAdministratorRoomNotAvailable ($order) {
		$idroomota = '';
		$roomnamevb = '';
		if(array_key_exists(0, $order['roominfo'])) {
			//Multiple Rooms Booked or channel supporting multiple rooms
			foreach ($order['roominfo'] as $rinfo) {
				$idroomota .= $rinfo['idroomota'].', ';
				$roomnamevb .= !empty($this->roomsinfomap[$rinfo['idroomota']]['roomnamevb']) ? $this->roomsinfomap[$rinfo['idroomota']]['roomnamevb'].', ' : '';
			}
			$idroomota = rtrim($idroomota, ', ');
			$roomnamevb = rtrim($roomnamevb, ', ');
		}else {
			$idroomota = $order['roominfo']['idroomota'];
			$roomnamevb = $this->roomsinfomap[$order['roominfo']['idroomota']]['roomnamevb'];
		}
		$message = JText::sprintf('VCMOTANEWORDERROOMNOTAVAIL', ucwords($this->config['channel']['name']), $order['info']['idorderota'], $idroomota, $roomnamevb, $order['info']['checkin'], $order['info']['checkout']);
		
		@mail($this->config['emailadmin'], JText::_('VCMOTANEWORDERROOMNOTAVAILSUBJ'), $message, "MIME-Version: 1.0" . "\r\n" . "Content-type: text/plain; charset=UTF-8" . "\r\n" . "From: ".$this->config['emailadmin'] . "\r\n" . "Reply-To: ".$this->config['emailadmin']);
		
		return $message;
	}
	
	/**
	* Sends an email to the Administrator saying that the room was not
	* available for the dates requested in the order received from the OTA.
	* Method used when the booking type is Modify.
	* Returns the error message composed to be stored inside the VCM notifications
	* @param order
	* @param idordervb
	*/
	public function notifyAdministratorRoomNotAvailableModification ($order, $idordervb) {
		$idroomota = '';
		$roomnamevb = '';
		if(array_key_exists(0, $order['roominfo'])) {
			//Multiple Rooms Booked or channel supporting multiple rooms
			foreach ($order['roominfo'] as $rinfo) {
				$idroomota .= $rinfo['idroomota'].', ';
				$roomnamevb .= !empty($this->roomsinfomap[$rinfo['idroomota']]['roomnamevb']) ? $this->roomsinfomap[$rinfo['idroomota']]['roomnamevb'].', ' : '';
			}
			$idroomota = rtrim($idroomota, ', ');
			$roomnamevb = rtrim($roomnamevb, ', ');
		}else {
			$idroomota = $order['roominfo']['idroomota'];
			$roomnamevb = $this->roomsinfomap[$order['roominfo']['idroomota']]['roomnamevb'];
		}
		$message = JText::sprintf('VCMOTAMODORDERROOMNOTAVAIL', ucwords($this->config['channel']['name']), $order['info']['idorderota'], $idroomota, $roomnamevb, $order['info']['checkin'], $order['info']['checkout'], $idordervb);
		
		@mail($this->config['emailadmin'], JText::_('VCMOTAMODORDERROOMNOTAVAILSUBJ'), $message, "MIME-Version: 1.0" . "\r\n" . "Content-type: text/plain; charset=UTF-8" . "\r\n" . "From: ".$this->config['emailadmin'] . "\r\n" . "Reply-To: ".$this->config['emailadmin']);
		
		return $message;
	}
	
	/**
	* Sets errors
	* @param error
	*/
	public function setError ($error) {
		$this->errorString .= $error;
	}
	
	/**
	* Gets active errors
	* @param error
	*/
	public function getError () {
		return $this->errorString;
	}
	
	/**
	* Stores a notification in the db for VikChannelManager
	* Type can be: 0 (Error), 1 (Success), 2 (Warning)
	*/
	public function saveNotify($type, $from, $cont, $idordervb = '') {
		$dbo = JFactory::getDBO();
		$from = empty($from) ? 'VCM' : $from;
		$q = "INSERT INTO `#__vikchannelmanager_notifications` (`ts`,`type`,`from`,`cont`,`idordervb`,`read`) VALUES('".time()."', '".$type."', ".$dbo->quote($from).", ".$dbo->quote($cont).", '".$idordervb."', 0);";
		$dbo->setQuery($q);
		$dbo->Query($q);
		return true;
	}
	
	/**
	* Generates and Saves a notification key for e4jConnect and VikChannelManager
	* 
	*/
	public function generateNKey($idordervb) {
		$nkey = rand(1000, 9999);
		$dbo = JFactory::getDBO();
		$q = "INSERT INTO `#__vikchannelmanager_keys` (`idordervb`,`key`) VALUES('".$idordervb."', '".$nkey."');";
		$dbo->setQuery($q);
		$dbo->Query($q);
		return $nkey;
	}
	
}


?>