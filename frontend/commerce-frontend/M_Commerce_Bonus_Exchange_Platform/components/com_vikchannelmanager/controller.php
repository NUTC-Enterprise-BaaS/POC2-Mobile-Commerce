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
* the front-site part of VikChannelManager does not have any output, it is only meant
* to generate responses in JSON format. All the methods below should not return any
* PHP Strict Standards, Notice, Warning or Error messages but if any plugin published on the website
* will cause some, the JSON responses will be corrupted and therefore, impossible to be decoded.
* For this reason it is safer to force the error_reporting to None to suppress any
* PHP message and ensure the JSON responses to be valid.
*/
$er_l = isset($_REQUEST['error_reporting']) && intval($_REQUEST['error_reporting'] == '-1') ? -1 : 0;
error_reporting($er_l);
//

jimport('joomla.application.component.controller');

class VikchannelmanagerController extends JControllerLegacy {
	function display() {
		$view=JRequest :: getVar('view', '');
		if($view == 'default') {
			JRequest :: setVar('view', 'default');
		}else {
			JRequest :: setVar('view', 'default');
		}
		parent :: display();
	}
	
	/**
	* A_RSL Availability Update Response Listener
	* Retrieves the response from e4jConnect of a AR_RQ
	* that was previously sent to save the Notification
	*/
	function a_rsl() {
		$response = 'e4j.error';
		$porderid = JRequest :: getString('orderid', '', 'request');
		$pnkey = JRequest :: getString('nkey', '', 'request');
		$pchannel = JRequest :: getInt('channel', '', 'request');
		$pecode = JRequest :: getString('ecode', '', 'request');
		$pemessage = JRequest :: getString('emessage', '', 'request');
		if (!empty($porderid) && !empty($pnkey)) {
			$ecode = '0';
			$valsecode = array('0', '1', '2');
			$ecode = in_array($pecode, $valsecode) ? $pecode : $ecode;
			$dbo = JFactory::getDBO();
			$q = sprintf("SELECT * FROM `#__vikchannelmanager_keys` WHERE `idordervb`='%d' AND `key`='%d';", $porderid, $pnkey);
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() > 0) {
				$keys = $dbo->loadAssocList();
				//Check if notification should be saved as new or as a child
				$q = "SELECT * FROM `#__vikchannelmanager_notifications` WHERE `from`='VCM' AND `idordervb`='".$keys[0]['idordervb']."' ORDER BY `#__vikchannelmanager_notifications`.`id` DESC LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if ($dbo->getNumRows() == 1) {
					$notification = $dbo->loadAssoc();
					$id_parent = $notification['id'];
					$set_channel = 0;
					$channel_info = VikChannelManager::getChannel($pchannel);
					if(count($channel_info) > 0) {
						$set_channel = (int)$channel_info['uniquekey'];
					}
					$q = "INSERT INTO `#__vikchannelmanager_notification_child` (`id_parent`,`type`,`cont`,`channel`) VALUES(".(int)$id_parent.", '".$ecode."', ".$dbo->quote($pemessage).", ".$set_channel.");";
					$dbo->setQuery($q);
					$dbo->Query($q);
					$child_id = $dbo->insertId();
					//get new type for parent notification
					$set_type = (int)$notification['type'];
					$all_types = array(intval($ecode));
					$q = "SELECT * FROM `#__vikchannelmanager_notification_child` WHERE `id_parent`=".(int)$id_parent." AND `id`!=".(int)$child_id.";";
					$dbo->setQuery($q);
					$dbo->Query($q);
					if ($dbo->getNumRows() > 0) {
						$child_types = $dbo->loadAssocList();
						foreach ($child_types as $ctype) {
							$all_types[] = intval($ctype['type']);
						}
					}
					foreach ($all_types as $newtype) {
						if ($newtype == 0) {
							$set_type = 0;
							break;
						}
						if ($newtype == 2) {
							$set_type = 2;
						}
					}
					//
					//Set parent Notification to be read and update time and type
					$q = "UPDATE `#__vikchannelmanager_notifications` SET `ts`=".time().", `type`=".$set_type.", `read`=0 WHERE `id`=".(int)$id_parent.";";
					$dbo->setQuery($q);
					$dbo->Query($q);
					//
				}else {
					$q = "INSERT INTO `#__vikchannelmanager_notifications` (`ts`,`type`,`from`,`cont`,`idordervb`) VALUES('".time()."', '".$ecode."', 'e4jConnect', ".$dbo->quote($pemessage).", '".$keys[0]['idordervb']."');";
					$dbo->setQuery($q);
					$dbo->Query($q);
				}
				//
				//Clean up the notification keys leaving the ones for the last 20 bookings
				$key_ord_lim = (int)$keys[0]['idordervb'] - 20;
				if($key_ord_lim > 0) {
					$q = "DELETE FROM `#__vikchannelmanager_keys` WHERE `idordervb`<".$key_ord_lim." AND `idordervb`>0;";
					$dbo->setQuery($q);
					$dbo->Query($q);
				}
				//
				$response = 'e4j.ok';
			}else {
				$response .= '.InvalidOrderKey';
			}
		}else {
			$response .= '.MissingOrderIdOrNkey';
		}
		echo $response;
		exit;
	}
	
	/**
	* CUSTA_RSL Availability Update Response Listener
	* Retrieves the response from e4jConnect of a AR_RQ
	* that was previously sent to save the Notification
	*/
	function custa_rsl() {
		$response = 'e4j.error';
		$pnkey = JRequest :: getString('nkey', '', 'request');
		$pchannel = JRequest :: getInt('channel', '', 'request');
		$pecode = JRequest :: getString('ecode', '', 'request');
		$pemessage = JRequest :: getString('emessage', '', 'request');
		if (!empty($pnkey)) {
			$ecode = '0';
			$valsecode = array('0', '1', '2');
			$ecode = in_array($pecode, $valsecode) ? $pecode : $ecode;
			$dbo =JFactory::getDBO();
			$q = sprintf("SELECT * FROM `#__vikchannelmanager_keys` WHERE `idordervb`='0' AND `key`='%d';", $pnkey);
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() > 0) {
				$keys = $dbo->loadAssocList();
				if (!empty($keys[0]['id_notification'])) {
					$set_channel = 0;
					$channel_info = VikChannelManager::getChannel($pchannel);
					if(count($channel_info) > 0) {
						$set_channel = (int)$channel_info['uniquekey'];
					}
					$q = "INSERT INTO `#__vikchannelmanager_notification_child` (`id_parent`,`type`,`cont`,`channel`) VALUES(".(int)$keys[0]['id_notification'].", '".$ecode."', ".$dbo->quote($pemessage).", ".$set_channel.");";
					$dbo->setQuery($q);
					$dbo->Query($q);
					$child_id = $dbo->insertId();
					//get new type for parent notification
					$q = "SELECT * FROM `#__vikchannelmanager_notifications` WHERE `id`=".(int)$keys[0]['id_notification'].";";
					$dbo->setQuery($q);
					$dbo->Query($q);
					$notification = $dbo->loadAssoc();
					$set_type = (int)$notification['type'];
					$all_types = array(intval($ecode));
					$q = "SELECT * FROM `#__vikchannelmanager_notification_child` WHERE `id_parent`=".(int)$keys[0]['id_notification']." AND `id`!=".(int)$child_id.";";
					$dbo->setQuery($q);
					$dbo->Query($q);
					if ($dbo->getNumRows() > 0) {
						$child_types = $dbo->loadAssocList();
						foreach ($child_types as $ctype) {
							$all_types[] = intval($ctype['type']);
						}
					}
					foreach ($all_types as $newtype) {
						if ($newtype == 0) {
							$set_type = 0;
							break;
						}
						if ($newtype == 2) {
							$set_type = 2;
						}
					}
					//
					//Set parent Notification to be read and update time and type
					$q = "UPDATE `#__vikchannelmanager_notifications` SET `ts`=".time().", `type`=".$set_type.", `read`=0 WHERE `id`=".(int)$keys[0]['id_notification'].";";
					$dbo->setQuery($q);
					$dbo->Query($q);
					//
				}else {
					$q = "INSERT INTO `#__vikchannelmanager_notifications` (`ts`,`type`,`from`,`cont`) VALUES('".time()."', '".$ecode."', 'e4jConnect', ".$dbo->quote($pemessage).");";
					$dbo->setQuery($q);
					$dbo->Query($q);
				}
				$q = "DELETE FROM `#__vikchannelmanager_keys` WHERE `id`<".$keys[0]['id']." AND `idordervb`=0;";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$response = 'e4j.ok';
			}else {
				$response .= '.InvalidOrderKey';
			}
		}else {
			$response .= '.MissingNkey';
		}
		echo $response;
		exit;
	}
	
	/**
	* BR_L Booking Retrieval Listener
	* Retrieves the new bookings sent by e4jConnect
	*/
	function br_l() {
		$response = 'e4j.error';
		$pe4jauth = JRequest :: getString('e4jauth', '', 'request', JREQUEST_ALLOWRAW);
		$pchannel = JRequest :: getInt('channel', '', 'request');
		$pnewbookings = JRequest :: getString('newbookings', '', 'request');
		$parrbookings = JRequest :: getString('arrbookings', '', 'request', JREQUEST_ALLOWRAW);
		if (!empty($pe4jauth) && !empty($pnewbookings) && !empty($parrbookings)) {
			$config = VikChannelManager::loadConfiguration();
			$channel = VikChannelManager::getChannel($pchannel);
			if(count($channel) > 0) {
				$config['channel'] = array_merge($channel, json_decode($channel['params'], true));
				//$checkauth = md5($config['channel']['username'].'e4j'.$config['channel']['password']);
				$checkauth = md5($config['apikey']);
				if ($checkauth == $pe4jauth) {
					$arrbookings = unserialize($parrbookings);
					if (count($arrbookings['orders']) > 0 && count($arrbookings['orders']) == (int)$pnewbookings && $checkauth == $arrbookings['e4jauth']) {
						require_once(JPATH_SITE . DS ."components". DS ."com_vikchannelmanager". DS . "helpers" . DS ."newbookings.vikbooking.php");
						$e4j = new newBookingsVikBooking($config, $arrbookings);
						$response = $e4j->processNewBookings();
					}else {
						$response = 'e4j.error.1';
						if($arrbookings === false) {
							$arrbookings = json_decode($parrbookings, true);
							if (@is_array($arrbookings) && count($arrbookings['orders']) > 0 && count($arrbookings['orders']) == (int)$pnewbookings && $checkauth == $arrbookings['e4jauth']) {
								require_once(JPATH_SITE . DS ."components". DS ."com_vikchannelmanager". DS . "helpers" . DS ."newbookings.vikbooking.php");
								$e4j = new newBookingsVikBooking($config, $arrbookings);
								$response = $e4j->processNewBookings();
							}
						}
					}
				}else {
					$response = 'e4j.error.Authentication';
				}
			}else {
				$response = 'e4j.error.NoChannel';
			}
		}
		echo $response;
		exit;
	}
	
	/**
	* BC_RSL Booking Confirmation Response Listener
	* Retrieves the response from e4jConnect of a BC_RQ
	* that was previously sent to save the Notification
	*/
	function bc_rsl() {
		$response = 'e4j.error';
		$porderid = $_POST['orderid'];
		$pnkey = $_POST['nkey'];
		$pecode = $_POST['ecode'];
		$pemessage = $_POST['emessage'];
		$pchannel = $_POST['channel'];
		if (!empty($porderid) && !empty($pnkey) && @count($porderid) > 0 && @count($pnkey) > 0) {
			$dbo = JFactory::getDBO();
			$valsecode = array('0', '1', '2');
			foreach($porderid as $k => $orderid) {
				if (!empty($orderid) && !empty($pnkey[$k])) {
					$ecode = '0';
					$ecode = in_array($pecode[$k], $valsecode) ? $pecode[$k] : $ecode;
					$q = sprintf("SELECT `k`.*,`vbo`.`id` AS `fetchvboid` FROM `#__vikchannelmanager_keys` AS `k` LEFT JOIN `#__vikbooking_orders` `vbo` ON `k`.`idordervb`=`vbo`.`id` WHERE `idordervb`='%d' AND `key`='%d';", $orderid, $pnkey[$k]);
					$dbo->setQuery($q);
					$dbo->Query($q);
					if ($dbo->getNumRows() > 0) {
						$keys = $dbo->loadAssocList();
						//Check if notification should be saved as new or as a child
						$set_channel = 0;
						$channel_info = VikChannelManager::getChannel($pchannel[$k]);
						if(count($channel_info) > 0) {
							$set_channel = (int)$channel_info['uniquekey'];
						}
						$q = "SELECT * FROM `#__vikchannelmanager_notifications` WHERE `from`=".$dbo->quote($channel_info['name'])." AND `idordervb`='".$keys[0]['idordervb']."' ORDER BY `#__vikchannelmanager_notifications`.`id` DESC LIMIT 1;";
						$dbo->setQuery($q);
						$dbo->Query($q);
						if ($dbo->getNumRows() == 1) {
							$notification = $dbo->loadAssoc();
							$id_parent = $notification['id'];
							$q = "INSERT INTO `#__vikchannelmanager_notification_child` (`id_parent`,`type`,`cont`,`channel`) VALUES(".(int)$id_parent.", '".$ecode."', ".$dbo->quote($pemessage[$k]).", ".$set_channel.");";
							$dbo->setQuery($q);
							$dbo->Query($q);
							$child_id = $dbo->insertId();
							//get new type for parent notification
							$set_type = (int)$notification['type'];
							$all_types = array(intval($ecode));
							$q = "SELECT * FROM `#__vikchannelmanager_notification_child` WHERE `id_parent`=".(int)$id_parent." AND `id`!=".(int)$child_id.";";
							$dbo->setQuery($q);
							$dbo->Query($q);
							if ($dbo->getNumRows() > 0) {
								$child_types = $dbo->loadAssocList();
								foreach ($child_types as $ctype) {
									$all_types[] = intval($ctype['type']);
								}
							}
							foreach ($all_types as $newtype) {
								if ($newtype == 0) {
									$set_type = 0;
									break;
								}
								if ($newtype == 2) {
									$set_type = 2;
								}
							}
							//
							//Set parent Notification to be read and update time and type
							$q = "UPDATE `#__vikchannelmanager_notifications` SET `ts`=".time().", `type`=".$set_type.", `read`=0 WHERE `id`=".(int)$id_parent.";";
							$dbo->setQuery($q);
							$dbo->Query($q);
							//
						}else {
							$q = "INSERT INTO `#__vikchannelmanager_notifications` (`ts`,`type`,`from`,`cont`,`idordervb`,`read`) VALUES('".time()."', '".$ecode."', 'e4jConnect', ".$dbo->quote($pemessage[$k]).", ".(strlen($keys[0]['fetchvboid']) > 0 ? "'".$keys[0]['idordervb']."'" : "null").", 0);";
							$dbo->setQuery($q);
							$dbo->Query($q);
						}
						$q = "DELETE FROM `#__vikchannelmanager_keys` WHERE `id`='".$keys[0]['id']."';";
						$dbo->setQuery($q);
						$dbo->Query($q);
						$response = 'e4j.ok';
					}
				}
			}
		}
		echo $response;
		exit;
	}
	
	/**
	* TripAdvisor (Instant Booking) Booking Sync listener
	*/
	function tac_bsync_l() {
        
		$crono = new Crono();
        $crono->start();
		
		$response = 'e4j.error';
		$args = array();
		$args['hash'] = JRequest::getString('e4jauth', '', 'request');
		$args['reservation_id'] = JRequest::getString('reservation_id', '', 'request');
		
		$valid = true;
		foreach( $args as $k => $v ) {
			$valid = $valid && !empty($v);
		}
		
		//request type
		$req_type = JRequest::getString('req_type', '', 'request');
		
		if( $valid ) {
			$config = VikChannelManager::loadConfiguration();
			$channel = VikChannelManager::getChannelCredentials(2);
			$checkauth = md5($channel['tripadvisorid'].'e4j'.$channel['tripadvisorid']);
			
			if( $checkauth == $args['hash'] ) {
				require_once (JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'helpers'.DS.'lib.vikbooking.php');				
				$dbo = JFactory::getDBO();
				$q="SELECT * FROM `#__vikbooking_orders` WHERE `id`='".intval($args['reservation_id'])."';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if( $dbo->getNumRows() == 0 ) {
					echo json_encode(array('e4j.error' => 'UnknownReference', 'error.code' => 1));
					exit;
				}
				$order = $dbo->loadAssocList();
				
				$nowstatus = $order[0]['status'];
				$nowts = time();
				$res_status = 'Booked';
				
				if($nowstatus == 'cancelled') {
					$res_status = 'Cancelled';
				}else {
					if($nowts >= $order[0]['checkin'] && $nowts < $order[0]['checkout']) {
						$res_status = 'CheckedIn';
					}elseif($nowts > $order[0]['checkout']) {
						$res_status = 'CheckedOut';
					}
				}
								
				$esit = true;
				$cancellation_number = !empty($order[0]['confirmnumber']) ? $order[0]['confirmnumber'] : $order[0]['id'].'canc';
				
				echo json_encode(array(
					'response' => array(
						'esit' => $esit,
						'status' => $res_status,
						'cancellation_number' => $cancellation_number,
						'currency' => vikbooking::getCurrencyName(),
						'order' => $order[0]
					)
				));
				exit;
				
			}else {
				$response = 'e4j.error.auth';
			}
		}
		echo $response;
		exit;
	}
	
	/**
	* TripAdvisor (Instant Booking) Booking Cancel listener
	*/
	function tac_bcanc_l() {
        
		$crono = new Crono();
        $crono->start();
		
		$response = 'e4j.error';
		$args = array();
		$args['hash'] = JRequest::getString('e4jauth', '', 'request');
		$args['reservation_id'] = JRequest::getString('reservation_id', '', 'request');
		
		$valid = true;
		foreach( $args as $k => $v ) {
			$valid = $valid && !empty($v);
		}
		
		//request type
		$req_type = JRequest::getString('req_type', '', 'request');
		
		if( $valid ) {
			$config = VikChannelManager::loadConfiguration();
			$channel = VikChannelManager::getChannelCredentials(2);
			$checkauth = md5($channel['tripadvisorid'].'e4j'.$channel['tripadvisorid']);
			
			if( $checkauth == $args['hash'] ) {
				$dbo = JFactory::getDBO();
				$q="SELECT * FROM `#__vikbooking_orders` WHERE `id`='".intval($args['reservation_id'])."';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if( $dbo->getNumRows() == 0 ) {
					echo json_encode(array('e4j.error' => 'UnknownReference', 'error.code' => 1));
					exit;
				}
				$order = $dbo->loadAssocList();
				
				$nowstatus = $order[0]['status'];
				$nowts = time();
				$gocancel = true;
				$res_status = 'Error';
				
				//check if the reservation can be cancelled
				if($nowstatus != 'cancelled' && $nowts >= $order[0]['checkin']) {
					$res_status = 'CannotBeCancelled';
					$gocancel = false;
				}
				
				if($gocancel && ($nowstatus == 'confirmed' || $nowstatus == 'standby')) {
					$q="UPDATE `#__vikbooking_orders` SET `status`='cancelled' WHERE `id`='".$order[0]['id']."';";
					$dbo->setQuery($q);
					$dbo->Query($q);
					$q="SELECT * FROM `#__vikbooking_ordersbusy` WHERE `idorder`='".$order[0]['id']."';";
					$dbo->setQuery($q);
					$dbo->Query($q);
					if ($dbo->getNumRows() > 0) {
						$ordbusy = $dbo->loadAssocList();
						foreach($ordbusy as $ob) {
							$q="DELETE FROM `#__vikbooking_busy` WHERE `id`='".$ob['idbusy']."';";
							$dbo->setQuery($q);
							$dbo->Query($q);
						}
					}
					$q="DELETE FROM `#__vikbooking_ordersbusy` WHERE `idorder`='".$order[0]['id']."';";
					$dbo->setQuery($q);
					$dbo->Query($q);
					$res_status = 'Success';
				}elseif($nowstatus == 'cancelled') {
					$res_status = 'AlreadyCancelled';
				}
				
				$esit = true;
				$cancellation_number = !empty($order[0]['confirmnumber']) ? $order[0]['confirmnumber'] : $order[0]['id'].'canc';
				
				echo json_encode(array(
					'response' => array(
						'esit' => $esit,
						'status' => $res_status,
						'cancellation_number' => $cancellation_number
					)
				));
				exit;
				
			}else {
				$response = 'e4j.error.auth';
			}
		}
		echo $response;
		exit;
	}
	
	/**
	* TripAdvisor (Instant Booking) Booking Verify listener
	*/
	function tac_bv_l() {
	    
        $crono = new Crono();
        $crono->start();
        
		$response = 'e4j.error';
		$args = array();
		$args['hash'] = JRequest::getString('e4jauth', '', 'request');
		$args['reference_id'] = JRequest::getString('reference_id', '', 'request');
		$args['reservation_id'] = JRequest::getString('reservation_id', '', 'request');
		
		$valid = true;
		foreach( $args as $k => $v ) {
			$valid = $valid && !empty($v);
		}
		
		//request type
		$req_type = JRequest::getString('req_type', '', 'request');
				
		if( $valid ) {
			$config = VikChannelManager::loadConfiguration();
			$channel = VikChannelManager::getChannelCredentials(2);
			$checkauth = md5($channel['tripadvisorid'].'e4j'.$channel['tripadvisorid']);
			
			if( $checkauth == $args['hash'] ) {
				
				$dbo = JFactory::getDBO();
				$q="SELECT * FROM `#__vikbooking_orders` WHERE `id`='".intval($args['reservation_id'])."';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if( $dbo->getNumRows() == 0 ) {
					echo json_encode(array('e4j.error' => 'UnknownUserProblem', 'error.code' => 1, 'explanation' => 'Unknown Reservation_ID', 'response' => 'UnknownReference'));
					exit;
				}
				$order = $dbo->loadAssocList();
				
				$args['nights'] = $order[0]['days'];
				
				$q="SELECT `or`.`idroom`,`or`.`adults`,`or`.`children`,`or`.`idtar`,`or`.`optionals`,`or`.`childrenage`,`or`.`t_first_name`,`or`.`t_last_name`,`r`.`id` AS `langidroom`,`r`.`name`,`r`.`img`,`r`.`idcarat`,`r`.`fromadult`,`r`.`toadult` FROM `#__vikbooking_ordersrooms` AS `or`,`#__vikbooking_rooms` AS `r` WHERE `or`.`idorder`='".$order[0]['id']."' AND `or`.`idroom`=`r`.`id` ORDER BY `or`.`id` ASC;";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if( $dbo->getNumRows() == 0 ) {
					echo json_encode(array('e4j.error' => 'UnknownUserProblem', 'error.code' => 2, 'explanation' => 'Missing order data with this Reservation_ID', 'response' => 'UnknownReference'));
					exit;
				}
				$orderrooms = $dbo->loadAssocList();
				
				$partner_rates = array();
				$avail_rooms = array();
				foreach($orderrooms as $or) {
					$partner_rates[$or['idtar']] = $or['idtar'];
					$avail_rooms[] = $or['idroom'];
				}
				
				require_once (JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'helpers'.DS.'lib.vikbooking.php');
				
				$args['start_ts'] = $order[0]['checkin'];
				$args['end_ts'] = $order[0]['checkout'];
				
				// GET RATES
				$rates = array();
				$q = "SELECT `p`.*, `r`.`img`, `r`.`units`, `r`.`moreimgs`, `r`.`imgcaptions`, `prices`.`name` AS `pricename`, `prices`.`breakfast_included`, `prices`.`free_cancellation`, `prices`.`canc_deadline` FROM `#__vikbooking_dispcost` AS `p`, `#__vikbooking_rooms` AS `r`, `#__vikbooking_prices` AS `prices` WHERE `r`.`id`=`p`.`idroom` AND `p`.`idprice`=`prices`.`id` AND `p`.`days`=".$order[0]['days']." AND `p`.`id` IN (".implode(',', array_keys($partner_rates)).") AND `r`.`id` IN (".implode(',', $avail_rooms).") ORDER BY `p`.`cost` ASC;";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if( $dbo->getNumRows() == 0 ) {
					echo json_encode(array('e4j.error' => 'PriceMismatch', 'error.code' => 1));
					exit;
				}
				
				$rates = $dbo->loadAssocList();
				$arr_rates = array();
				foreach( $rates as $rate ) {
					$arr_rates[$rate['idroom']][] = $rate;
				}
				
				$arrpeople = array();
				foreach($orderrooms as $kor => $or) {
					$numr = ($kor + 1);
					$arrpeople[$numr]['adults'] = $or['adults'];
					$arrpeople[$numr]['children'] = $or['children'];
					$children_age = array();
					if(!empty($or['childrenage'])) {
						$json_dec = json_decode($or['childrenage'], true);
						if(is_array($json_dec['age']) && count($json_dec['age']) > 0) {
							$children_age = $json_dec['age'];
						}
					}
					$arrpeople[$numr]['children_age'] = $children_age;
				}
				
				//apply special prices
				$arr_rates = vikbooking::applySeasonalPrices($arr_rates, $args['start_ts'], $args['end_ts']);
				//
				
				//children ages charge
				$children_sums = array();
				//end children ages charge
				
				//set $args['num_adults'] to the number of adults occupying the first room
				$args['num_adults'] = $arrpeople[key($arrpeople)]['adults'];
				//
				
				//sum charges/discounts per occupancy for each room party
				foreach($arrpeople as $roomnumb => $party) {
					//charges/discounts per adults occupancy
					foreach ($arr_rates as $r => $rates) {
						$children_charges = vikbooking::getChildrenCharges($r, $party['children'], $party['children_age'], $args['nights']);
						if(count($children_charges) > 0) {
							$children_sums[$r] += $children_charges['total'];
						}
						$diffusageprice = vikbooking::loadAdultsDiff($r, $party['adults']);
						//Occupancy Override - Special Price may be setting a charge/discount for this occupancy while default price had no occupancy pricing
						if (!is_array($diffusageprice)) {
							foreach($rates as $kpr => $vpr) {
								if(array_key_exists('occupancy_ovr', $vpr) && array_key_exists($party['adults'], $vpr['occupancy_ovr']) && strlen($vpr['occupancy_ovr'][$party['adults']]['value'])) {
									$diffusageprice = $vpr['occupancy_ovr'][$party['adults']];
									break;
								}
							}
							reset($rates);
						}
						//
						if (is_array($diffusageprice)) {
							foreach($rates as $kpr => $vpr) {
								if($roomnumb == 1) {
									$arr_rates[$r][$kpr]['costbeforeoccupancy'] = $arr_rates[$r][$kpr]['cost'];
								}
								//Occupancy Override
								if(array_key_exists('occupancy_ovr', $vpr) && array_key_exists($party['adults'], $vpr['occupancy_ovr']) && strlen($vpr['occupancy_ovr'][$party['adults']]['value'])) {
									$diffusageprice = $vpr['occupancy_ovr'][$party['adults']];
								}
								//
								$arr_rates[$r][$kpr]['diffusage'] = $party['adults'];
								if ($diffusageprice['chdisc'] == 1) {
									//charge
									if ($diffusageprice['valpcent'] == 1) {
										//fixed value
										$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $arr_rates[$r][$kpr]['days'] : $diffusageprice['value'];
										$arr_rates[$r][$kpr]['diffusagecost'][$roomnumb] = $aduseval;
										$arr_rates[$r][$kpr]['cost'] += $aduseval;
									}else {
										//percentage value
										$aduseval = $diffusageprice['pernight'] == 1 ? round(($arr_rates[$r][$kpr]['costbeforeoccupancy'] * $diffusageprice['value'] / 100) * $arr_rates[$r][$kpr]['days'], 2) : round(($arr_rates[$r][$kpr]['costbeforeoccupancy'] * $diffusageprice['value'] / 100), 2);
										$arr_rates[$r][$kpr]['diffusagecost'][$roomnumb] = $aduseval;
										$arr_rates[$r][$kpr]['cost'] += $aduseval;
									}
								}else {
									//discount
									if ($diffusageprice['valpcent'] == 1) {
										//fixed value
										$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $arr_rates[$r][$kpr]['days'] : $diffusageprice['value'];
										$arr_rates[$r][$kpr]['diffusagediscount'][$roomnumb] = $aduseval;
										$arr_rates[$r][$kpr]['cost'] -= $aduseval;
									}else {
										//percentage value
										$aduseval = $diffusageprice['pernight'] == 1 ? round(((($arr_rates[$r][$kpr]['costbeforeoccupancy'] / $arr_rates[$r][$kpr]['days']) * $diffusageprice['value'] / 100) * $arr_rates[$r][$kpr]['days']), 2) : round(($arr_rates[$r][$kpr]['costbeforeoccupancy'] * $diffusageprice['value'] / 100), 2);
										$arr_rates[$r][$kpr]['diffusagediscount'][$roomnumb] = $aduseval;
										$arr_rates[$r][$kpr]['cost'] -= $aduseval;
									}
								}
							}
						}elseif($roomnumb == 1) {
							foreach($rates as $kpr => $vpr) {
								$arr_rates[$r][$kpr]['costbeforeoccupancy'] = $arr_rates[$r][$kpr]['cost'];
							}
						}
					}
					//end charges/discounts per adults occupancy
				}
				//end sum charges/discounts per occupancy for each room party
				
				//if the rooms are given to a party of multiple rooms, multiply the basic rates per room per number of rooms
				for($i = 2; $i <= count($arrpeople); $i++) {
					foreach ($arr_rates as $r => $rates) {
						foreach($rates as $kpr => $vpr) {
							$arr_rates[$r][$kpr]['cost'] += $arr_rates[$r][$kpr]['costbeforeoccupancy'];
						}
					}
				}
				//end if the rooms are given to a party of multiple rooms, multiply the basic rates per room per number of rooms
				
				//children ages charge
				if(count($children_sums) > 0) {
					foreach ($arr_rates as $r => $rates) {
						if(array_key_exists($r, $children_sums)) {
							foreach($rates as $kpr => $vpr) {
								$arr_rates[$r][$kpr]['cost'] += $children_sums[$r];
							}
						}
					}
				}
				//end children ages charge
				
				//compose taxes information
				$session = JFactory::getSession();
				$sval = $session->get('vbivaInclusa', '');
				if(strlen($sval) > 0) {
					$ivainclusa = $sval;
				}else {
					$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='ivainclusa';";
					$dbo->setQuery($q);
					$dbo->Query($q);
					$iva = $dbo->loadAssocList();
					$session->set('vbivaInclusa', $iva[0]['setting']);
					$ivainclusa = $iva[0]['setting'];
				}
				$rates_ids = array();
				foreach ($arr_rates as $r => $rate) {
					foreach ($rate as $ids) {
						if (!in_array($ids['idprice'], $rates_ids)) {
							$rates_ids[] = $ids['idprice'];
						}
					}
				}
				$tax_rates = array();
				$q = "SELECT `p`.`id`,`t`.`aliq` FROM `#__vikbooking_prices` AS `p` LEFT JOIN `#__vikbooking_iva` `t` ON `p`.`idiva`=`t`.`id` WHERE `p`.`id` IN (".implode(',', $rates_ids).");";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if ($dbo->getNumRows() > 0) {
					$alltaxrates = $dbo->loadAssocList();
					foreach ($alltaxrates as $tx) {
						if(!empty($tx['aliq']) && $tx['aliq'] > 0) {
							$tax_rates[$tx['id']] = $tx['aliq'];
						}
					}
				}
				$city_tax_fees = array();
				if(count($tax_rates) > 0) {
					foreach ($arr_rates as $r => $rates) {
						//$city_tax_fees = vikbooking::getMandatoryTaxesFees(array($r), $args['num_adults'], $args['nights']);
						foreach ($rates as $k => $rate) {
							if (array_key_exists($rate['idprice'], $tax_rates)) {
								if (intval($ivainclusa) == 1) {
									//prices tax included
									$realcost = $rate['cost'] / ((100 + $tax_rates[$rate['idprice']]) / 100);
									$tax_oper = ($tax_rates[$rate['idprice']] + 100) / 100;
									$taxes = $rate['cost'] - ($rate['cost'] / $tax_oper);
								}else {
									//prices tax excluded
									$realcost = $rate['cost'] * (100 + $tax_rates[$rate['idprice']]) / 100;
									$taxes = $realcost - $rate['cost'];
									$realcost = $rate['cost'];
								}
								$arr_rates[$r][$k]['cost'] = round($realcost, 2);
								$arr_rates[$r][$k]['taxes'] = round($taxes, 2);
								//$arr_rates[$r][$k]['city_taxes'] = round($city_tax_fees['city_taxes'], 2);
								//$arr_rates[$r][$k]['fees'] = round($city_tax_fees['fees'], 2);
							}
						}
					}
					//sum taxes/fees for each room party
					foreach($arrpeople as $roomnumb => $party) {
						foreach ($arr_rates as $r => $rates) {
							$city_tax_fees = vikbooking::getMandatoryTaxesFees(array($r), $party['adults'], $args['nights']);
							foreach ($rates as $k => $rate) {
								$arr_rates[$r][$k]['city_taxes'] += round($city_tax_fees['city_taxes'], 2);
								$arr_rates[$r][$k]['fees'] += round($city_tax_fees['fees'], 2);
							}
						}
					}
					//end sum taxes/fees for each room party
				}else {
					foreach ($arr_rates as $r => $rates) {
						foreach ($rates as $k => $rate) {
							$arr_rates[$r][$k]['taxes'] = round(0, 2);
							$arr_rates[$r][$k]['city_taxes'] = round(0, 2);
							$arr_rates[$r][$k]['fees'] = round(0, 2);
						}
					}
				}
				//end compose taxes information
								
				//customer_data
				$custdata = $order[0]['custdata'];
				$customer_info = array();
				$cust_parts = explode("\n", $custdata);
				foreach($cust_parts as $custval) {
					if(empty($custval)) {
						continue;
					}
					$keyval = explode(':', trim($custval));
					$readablekv = strtolower(str_replace(' ', '_', trim($keyval[0])));
					$customer_info[$readablekv] = trim($keyval[1]);
				}
				//
				
				$esit = true;
				$nowts = time();
				$confirmnumber = $order[0]['confirmnumber'];
				$orderlink = JURI::root()."index.php?option=com_vikbooking&task=vieworder&sid=".$order[0]['sid']."&ts=".$order[0]['ts'];
				$neworder_status = $order[0]['status'];
				$reservation_status = 'Booked';
				if($order[0]['status'] == 'cancelled') {
					$reservation_status = 'Cancelled';
				}else {
					if($nowts > $order[0]['checkout']) {
						$reservation_status = 'CheckedOut';
					}elseif($nowts >= $order[0]['checkin'] && $nowts < $order[0]['checkout']) {
						$reservation_status = 'CheckedIn';
					}
				}
				
				$arr_rates['response'] = array(
					'esit' => $esit,
					'status' => $neworder_status,
					'reservationstatus' => $reservation_status,
					'id' => $order[0]['id'],
					'confirmnumber' => $confirmnumber,
					'orderlink' => $orderlink,
					'currency' => vikbooking::getCurrencyName(),
					'order' => $order[0],
					'order_rooms' => $orderrooms,
					'customer_info' => $customer_info
				);
				
				$response = $arr_rates;
								
				// store elapsed time statistics
                
                $elapsed_time = $crono->stop();
                
                VikChannelManager::storeCallStats(VikChannelManagerConfig::TRIP_CONNECT, 'tac_bv_l', $elapsed_time);
                
                //
                
                $args['response'] = $response['response'];
				
				echo json_encode( $response );
				exit;
				
			} else {
				$response = 'e4j.error.auth';
			}
		}
		echo $response;
		exit;
	}
	
	/**
	* TripAdvisor (Instant Booking) Booking Submit listener
	*/
	function tac_bs_l() {
	    
        $crono = new Crono();
        $crono->start();
        
		$response = 'e4j.error';
		$args = array();
		$args['hash'] = JRequest::getString('e4jauth', '', 'request');
		$args['start_date'] = JRequest::getString('start_date', '', 'request');
		$args['end_date'] = JRequest::getString('end_date', '', 'request');
		$args['nights'] = JRequest::getInt('nights', 1, 'request');
		$args['num_rooms'] = JRequest::getInt('num_rooms', 1, 'request');
		$args['start_ts'] = strtotime($args['start_date']);
		$args['end_ts'] = strtotime($args['end_date']);
		$args['adults'] = JRequest::getVar('adults', array());
		$args['customer_info'] = JRequest::getVar('customer_info', array());
		$args['rooms_info'] = JRequest::getVar('rooms_info', array());
		$args['partner_data'] = JRequest::getVar('partner_data', array());
		$args['final_price_at_booking'] = JRequest::getVar('final_price_at_booking', array());
		$args['payment_method'] = JRequest::getString('payment_method', '', 'request', JREQUEST_ALLOWRAW);
		
		$valid = true;
		foreach( $args as $k => $v ) {
			$valid = $valid && !empty($v);
		}
		
		VikChannelManager::loadCypherFramework();
		$partner_id = VikChannelManager::getTripConnectPartnerID();
		$enc = new Encryption($partner_id);
		$decoded_paym = $enc->decode($args['payment_method']);
		$args['payment_method'] = unserialize($decoded_paym);
		if($args['payment_method'] === false || !is_array($args['payment_method'])) {
			$valid = false;
			$response = 'e4j.error.CreditCardTypeNotSupported';
		}
		
		//request type
		$req_type = JRequest::getString('req_type', '', 'request');
		
		$args['children'] = JRequest::getVar('children', array());
		$args['children_age'] = JRequest::getVar('children_age', array());
		$args['final_price_at_checkout'] = JRequest::getVar('final_price_at_checkout', array());
		$args['reference_id'] = JRequest::getString('reference_id', '', 'request');
		
		if( $valid ) {
			$config = VikChannelManager::loadConfiguration();
			$channel = VikChannelManager::getChannelCredentials(2);
			$checkauth = md5($channel['tripadvisorid'].'e4j'.$channel['tripadvisorid']);
			
			if( $checkauth == $args['hash'] ) {
				$debug_mode = isset($_REQUEST['e4j_debug']) && intval($_REQUEST['e4j_debug']) == 1 ? true : false;

				$partner_rooms = array();
				$partner_rates = array();
				if(!is_int(key($args['partner_data']))){
					$partner_rooms[$args['partner_data']['id_room']] = $args['partner_data']['id_room'];
					$partner_rates[$args['partner_data']['id_cost']] = $args['partner_data']['id_cost'];
				}else {
					foreach($args['partner_data'] as $vbroom) {
						$partner_rooms[$vbroom['id_room']] = $vbroom['id_room'];
						$partner_rates[$vbroom['id_cost']] = $vbroom['id_cost'];
					}
				}
				
				$tac_rooms = array();
				$dbo = JFactory::getDBO();
				$q = "SELECT `id_vb_room` AS `id` FROM `#__vikchannelmanager_tac_rooms` WHERE `id_vb_room` IN (".implode(',', array_keys($partner_rooms)).");";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if( $dbo->getNumRows() == 0 ) {
					echo json_encode(array());
					exit;
				}
				
				$rows = $dbo->loadAssocList();
				for( $i = 0; $i < count($rows); $i++ ) {
					$tac_rooms[$i] = $rows[$i]['id'];
				}
				
				$avail_rooms = array();
				
				//compose adults-children array and sql clause
				$arradultsrooms = array();
				$arradultsclause = array();
				$arrpeople = array();
				if (count($args['adults']) > 0) {
					foreach($args['adults'] as $kad => $adu) {
						$roomnumb = $kad + 1;
						if (strlen($adu)) {
							$numadults = intval($adu);
							if ($numadults >= 0) {
								$arradultsrooms[$roomnumb] = $numadults;
								$arrpeople[$roomnumb]['adults'] = $numadults;
								$strclause = "(`fromadult`<=".$numadults." AND `toadult`>=".$numadults."";
								if (!empty($args['children'][$kad]) && intval($args['children'][$kad]) > 0) {
									$numchildren = intval($args['children'][$kad]);
									$arrpeople[$roomnumb]['children'] = $numchildren;
									$arrpeople[$roomnumb]['children_age'] = count($args['children_age'][$roomnumb]) > 0 ? $args['children_age'][$roomnumb] : array();
									$strclause .= " AND `fromchild`<=".$numchildren." AND `tochild`>=".$numchildren."";
								}else {
									$arrpeople[$roomnumb]['children'] = 0;
									$arrpeople[$roomnumb]['children_age'] = array();
									if (intval($args['children'][$kad]) == 0) {
										$strclause .= " AND `fromchild` = 0";
									}
								}
								$strclause .= " AND `totpeople` >= ".($arrpeople[$roomnumb]['adults'] + $arrpeople[$roomnumb]['children']);
								$strclause .= " AND `mintotpeople` <= ".($arrpeople[$roomnumb]['adults'] + $arrpeople[$roomnumb]['children']);
								$strclause .= ")";
								$arradultsclause[] = $strclause;
							}
						}
					}
				}
				//
				//Set $args['adults'] to the number of adults occupying the first room but it could be a party of multiple rooms
				$args['num_adults'] = $arrpeople[1]['adults'];
				//
				//This clause would return one room type for each party type: implode(" OR ", $arradultsclause) - the AND clause must be used rather than OR.
				$q = "SELECT `id`, `units` FROM `#__vikbooking_rooms` WHERE `avail`=1 AND (".implode(" AND ", $arradultsclause).") AND `id` IN (".implode(',', $tac_rooms).");";
				
				$dbo->setQuery($q);
				$dbo->Query($q);
				if( $dbo->getNumRows() == 0 ) {
					echo json_encode(array('e4j.error' => 'RoomNotAvailable', 'error.code' => 1));
					exit;
				}
		
				$avail_rooms = $dbo->loadAssocList();
				
				if(count($arrpeople) != $args['num_rooms']) {
					echo json_encode(array('e4j.error' => 'RoomNotAvailable', 'error.code' => 2));
					exit;
				}
				
				require_once (JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'helpers'.DS.'lib.vikbooking.php');
				
				// arr[0] = (sec) checkin, arr[1] = (sec) checkout
				$check_in_out = vikbooking::getTimeOpenStore();
				$args['start_ts'] += $check_in_out[0];
				$args['end_ts'] += $check_in_out[1];
				
				$room_ids = array();
				for( $i = 0; $i < count($avail_rooms); $i++ ) {
					$room_ids[$i] = $avail_rooms[$i]['id'];
				}
				
				$all_restrictions = vikbooking::loadRestrictions(true, $room_ids);
				$glob_restrictions = vikbooking::globalRestrictions($all_restrictions);
				
				if( count($glob_restrictions) > 0 && strlen(vikbooking::validateRoomRestriction($glob_restrictions, getdate($args['start_ts']), getdate($args['end_ts']), $args['nights'])) > 0) {
					echo json_encode(array('e4j.error' => 'RoomNotAvailable', 'error.code' => 3));
					exit;
				}
				
				//Get Rates
				$room_ids = array();
				foreach( $avail_rooms as $k => $room ) {
					$room_ids[$room['id']] = $room;
				}
				$rates = array();
				$q = "SELECT `p`.*, `r`.`img`, `r`.`units`, `r`.`moreimgs`, `r`.`imgcaptions`, `prices`.`name` AS `pricename`, `prices`.`breakfast_included`, `prices`.`free_cancellation`, `prices`.`canc_deadline` FROM `#__vikbooking_dispcost` AS `p`, `#__vikbooking_rooms` AS `r`, `#__vikbooking_prices` AS `prices` WHERE `r`.`id`=`p`.`idroom` AND `p`.`idprice`=`prices`.`id` AND `p`.`days`=".$args['nights']." AND `p`.`id` IN (".implode(',', array_keys($partner_rates)).") AND `r`.`id` IN (".implode(',', array_keys($room_ids)).") ORDER BY `p`.`cost` ASC;";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if( $dbo->getNumRows() == 0 ) {
					echo json_encode(array());
					exit;
				}
				$rates = $dbo->loadAssocList();
				$arr_rates = array();
				foreach( $rates as $rate ) {
					$arr_rates[$rate['idroom']][] = $rate;
				}
				
				//Check Availability for the rooms with a rate for this number of nights
				$minus_units = 0;
				if(count($arr_rates) < $args['num_rooms']) {
					$minus_units = $args['num_rooms'] - count($arr_rates);
				}
				foreach( $arr_rates as $k => $datarate ) {
					$room = $room_ids[$k];
					$consider_units = $room['units'] - $minus_units;
					if( !vikbooking::roomBookable($room['id'], $consider_units, $args['start_ts'], $args['end_ts']) || $consider_units <= 0) {
						unset($arr_rates[$k]);
					} else {
						
						if( count($all_restrictions) > 0 ) {
							$room_restr = vikbooking::roomRestrictions($room['id'], $all_restrictions);
							if( count($room_restr) > 0 ) {
								if( strlen(vikbooking::validateRoomRestriction($room_restr, getdate($args['start_ts']), getdate($args['end_ts']), $args['nights'])) > 0 ) {
									unset($arr_rates[$k]);
								}
							}
						}	
						
					}
				}
				
				if( count($arr_rates) == 0 ) {
					echo json_encode(array('e4j.error' => 'RoomNotAvailable', 'error.code' => 4));
					exit;
				}
				
				//apply special prices
				$arr_rates = vikbooking::applySeasonalPrices($arr_rates, $args['start_ts'], $args['end_ts']);
				//
				
				//children ages charge
				$children_sums = array();
				$children_options = array();
				//end children ages charge
				
				//sum charges/discounts per occupancy for each room party
				foreach($arrpeople as $roomnumb => $party) {
					//charges/discounts per adults occupancy
					foreach ($arr_rates as $r => $rates) {
						$children_charges = vikbooking::getChildrenCharges($r, $party['children'], $party['children_age'], $args['nights']);
						if(count($children_charges) > 0) {
							$children_sums[$r] += $children_charges['total'];
							$children_options[$roomnumb] = $children_charges['options'];
						}
						$diffusageprice = vikbooking::loadAdultsDiff($r, $party['adults']);
						//Occupancy Override - Special Price may be setting a charge/discount for this occupancy while default price had no occupancy pricing
						if (!is_array($diffusageprice)) {
							foreach($rates as $kpr => $vpr) {
								if(array_key_exists('occupancy_ovr', $vpr) && array_key_exists($party['adults'], $vpr['occupancy_ovr']) && strlen($vpr['occupancy_ovr'][$party['adults']]['value'])) {
									$diffusageprice = $vpr['occupancy_ovr'][$party['adults']];
									break;
								}
							}
							reset($rates);
						}
						//
						if (is_array($diffusageprice)) {
							foreach($rates as $kpr => $vpr) {
								if($roomnumb == 1) {
									$arr_rates[$r][$kpr]['costbeforeoccupancy'] = $arr_rates[$r][$kpr]['cost'];
								}
								//Occupancy Override
								if(array_key_exists('occupancy_ovr', $vpr) && array_key_exists($party['adults'], $vpr['occupancy_ovr']) && strlen($vpr['occupancy_ovr'][$party['adults']]['value'])) {
									$diffusageprice = $vpr['occupancy_ovr'][$party['adults']];
								}
								//
								$arr_rates[$r][$kpr]['diffusage'] = $party['adults'];
								if ($diffusageprice['chdisc'] == 1) {
									//charge
									if ($diffusageprice['valpcent'] == 1) {
										//fixed value
										$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $arr_rates[$r][$kpr]['days'] : $diffusageprice['value'];
										$arr_rates[$r][$kpr]['diffusagecost'][$roomnumb] = $aduseval;
										$arr_rates[$r][$kpr]['cost'] += $aduseval;
									}else {
										//percentage value
										$aduseval = $diffusageprice['pernight'] == 1 ? round(($arr_rates[$r][$kpr]['costbeforeoccupancy'] * $diffusageprice['value'] / 100) * $arr_rates[$r][$kpr]['days'], 2) : round(($arr_rates[$r][$kpr]['costbeforeoccupancy'] * $diffusageprice['value'] / 100), 2);
										$arr_rates[$r][$kpr]['diffusagecost'][$roomnumb] = $aduseval;
										$arr_rates[$r][$kpr]['cost'] += $aduseval;
									}
								}else {
									//discount
									if ($diffusageprice['valpcent'] == 1) {
										//fixed value
										$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $arr_rates[$r][$kpr]['days'] : $diffusageprice['value'];
										$arr_rates[$r][$kpr]['diffusagediscount'][$roomnumb] = $aduseval;
										$arr_rates[$r][$kpr]['cost'] -= $aduseval;
									}else {
										//percentage value
										$aduseval = $diffusageprice['pernight'] == 1 ? round(((($arr_rates[$r][$kpr]['costbeforeoccupancy'] / $arr_rates[$r][$kpr]['days']) * $diffusageprice['value'] / 100) * $arr_rates[$r][$kpr]['days']), 2) : round(($arr_rates[$r][$kpr]['costbeforeoccupancy'] * $diffusageprice['value'] / 100), 2);
										$arr_rates[$r][$kpr]['diffusagediscount'][$roomnumb] = $aduseval;
										$arr_rates[$r][$kpr]['cost'] -= $aduseval;
									}
								}
							}
						}elseif($roomnumb == 1) {
							foreach($rates as $kpr => $vpr) {
								$arr_rates[$r][$kpr]['costbeforeoccupancy'] = $arr_rates[$r][$kpr]['cost'];
							}
						}
					}
					//end charges/discounts per adults occupancy
				}
				//end sum charges/discounts per occupancy for each room party
				
				//if the rooms are given to a party of multiple rooms, multiply the basic rates per room per number of rooms
				for($i = 2; $i <= $args['num_rooms']; $i++) {
					foreach ($arr_rates as $r => $rates) {
						foreach($rates as $kpr => $vpr) {
							$arr_rates[$r][$kpr]['cost'] += $arr_rates[$r][$kpr]['costbeforeoccupancy'];
						}
					}
				}
				//end if the rooms are given to a party of multiple rooms, multiply the basic rates per room per number of rooms
				
				//children ages charge
				if(count($children_sums) > 0) {
					foreach ($arr_rates as $r => $rates) {
						if(array_key_exists($r, $children_sums)) {
							foreach($rates as $kpr => $vpr) {
								$arr_rates[$r][$kpr]['cost'] += $children_sums[$r];
							}
						}
					}
				}
				//end children ages charge
				
				//compose taxes information
				$session = JFactory::getSession();
				$sval = $session->get('vbivaInclusa', '');
				if(strlen($sval) > 0) {
					$ivainclusa = $sval;
				}else {
					$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='ivainclusa';";
					$dbo->setQuery($q);
					$dbo->Query($q);
					$iva = $dbo->loadAssocList();
					$session->set('vbivaInclusa', $iva[0]['setting']);
					$ivainclusa = $iva[0]['setting'];
				}
				$rates_ids = array();
				foreach ($arr_rates as $r => $rate) {
					foreach ($rate as $ids) {
						if (!in_array($ids['idprice'], $rates_ids)) {
							$rates_ids[] = $ids['idprice'];
						}
					}
				}
				$tax_rates = array();
				$q = "SELECT `p`.`id`,`t`.`aliq` FROM `#__vikbooking_prices` AS `p` LEFT JOIN `#__vikbooking_iva` `t` ON `p`.`idiva`=`t`.`id` WHERE `p`.`id` IN (".implode(',', $rates_ids).");";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if ($dbo->getNumRows() > 0) {
					$alltaxrates = $dbo->loadAssocList();
					foreach ($alltaxrates as $tx) {
						if(!empty($tx['aliq']) && $tx['aliq'] > 0) {
							$tax_rates[$tx['id']] = $tx['aliq'];
						}
					}
				}
				$city_tax_fees = array();
				if(count($tax_rates) > 0) {
					foreach ($arr_rates as $r => $rates) {
						//$city_tax_fees = vikbooking::getMandatoryTaxesFees(array($r), $args['num_adults'], $args['nights']);
						foreach ($rates as $k => $rate) {
							if (array_key_exists($rate['idprice'], $tax_rates)) {
								if (intval($ivainclusa) == 1) {
									//prices tax included
									$realcost = $rate['cost'];
									$tax_oper = ($tax_rates[$rate['idprice']] + 100) / 100;
									$taxes = $rate['cost'] - ($rate['cost'] / $tax_oper);
								}else {
									//prices tax excluded ($rate['cost'] must always be rounded or errors will occur when discounts from special prices apply and tax excluded)
									$realcost = round($rate['cost'], 2) * (100 + $tax_rates[$rate['idprice']]) / 100;
									$taxes = $realcost - $rate['cost'];
								}
								$arr_rates[$r][$k]['cost'] = round($realcost, 2);
								$arr_rates[$r][$k]['taxes'] = round($taxes, 2);
								//$arr_rates[$r][$k]['city_taxes'] = round($city_tax_fees['city_taxes'], 2);
								//$arr_rates[$r][$k]['fees'] = round($city_tax_fees['fees'], 2);
							}
						}
					}
					//sum taxes/fees for each room party
					foreach($arrpeople as $roomnumb => $party) {
						foreach ($arr_rates as $r => $rates) {
							$city_tax_fees = vikbooking::getMandatoryTaxesFees(array($r), $party['adults'], $args['nights']);
							foreach ($rates as $k => $rate) {
								$arr_rates[$r][$k]['city_taxes'] += round($city_tax_fees['city_taxes'], 2);
								$arr_rates[$r][$k]['fees'] += round($city_tax_fees['fees'], 2);
							}
						}
					}
					//end sum taxes/fees for each room party
				}else {
					foreach ($arr_rates as $r => $rates) {
						foreach ($rates as $k => $rate) {
							$arr_rates[$r][$k]['taxes'] = round(0, 2);
							$arr_rates[$r][$k]['city_taxes'] = round(0, 2);
							$arr_rates[$r][$k]['fees'] = round(0, 2);
						}
					}
				}
				//end compose taxes information
				$room_ind = key($arr_rates);
				$price_ind = key($arr_rates[$room_ind]);
				$final_price = $arr_rates[$room_ind][$price_ind]['cost'] + $arr_rates[$room_ind][$price_ind]['city_taxes'] + $arr_rates[$room_ind][$price_ind]['fees'];
				$final_price = round($final_price, 2);
				$args['final_price_at_booking']['amount'] = round($args['final_price_at_booking']['amount'], 2);
				if($final_price < (float)$args['final_price_at_booking']['amount'] || $final_price > (float)$args['final_price_at_booking']['amount']) {
					echo json_encode(array('e4j.error' => 'PriceMismatch', 'error.code' => 2, 'explanation' => JText::sprintf('VCM_TAC_ERR_PRICE_MISMATCH', trim($args['final_price_at_booking']['currency'].' '.$final_price))));
					exit;
				}
				
				$channel_data = VikChannelManager::getChannel(2);
				$channel_settings = json_decode($channel_data['settings'], true);
				$neworder_status = $channel_settings['paystatus']['value'] == 'VCM_TA_PAYMENT_STATUS_CONFIRMED' ? 'confirmed' : 'standby';
				
				//customer_data
				$custdata = '';
				foreach($args['customer_info'] as $custkey => $custval) {
					$readablekv = ucwords(str_replace('_', ' ', $custkey));
					$custdata .= $readablekv.': '.$custval."\n";
				}
				//
				//email
				$customer_email = '';
				if(!empty($args['customer_info']['email'])) {
					$customer_email = $args['customer_info']['email'];
				}
				//
				//country code
				$country_code = '';
				if(!empty($args['customer_info']['country'])) {
					$q = "SELECT * FROM `#__vikbooking_countries` WHERE `country_2_code`='".$args['customer_info']['country']."';";
					$dbo->setQuery($q);
					$dbo->Query($q);
					if( $dbo->getNumRows() > 0 ) {
						$vbcountry = $dbo->loadAssocList();
						$country_code = $vbcountry[0]['country_3_code'];
					}
				}
				//
				
				//save order in VikBooking
				$esit = false;
				$neworderid = -1;
				$confirmnumber = '';
				$realback = vikbooking::getHoursRoomAvail() * 3600;
				$realback += $args['end_ts'];
				$sid = vikbooking::getSecretLink();
				$nowts = time();
				$orderlink = JURI::root()."index.php?option=com_vikbooking&task=vieworder&sid=".$sid."&ts=".$nowts;
				$lang = JFactory::getLanguage();
				$langtag = $lang->getTag();
				$options_str = '';
				if(is_array($city_tax_fees['options']) && count($city_tax_fees['options']) > 0) {
					$options_str = implode(';', $city_tax_fees['options']).';';
				}
                
                $pay_str = 'NULL';
                $q = "SELECT `id`, `name` FROM `#__vikbooking_gpayments` WHERE `id`=".VikChannelManager::getDefaultPaymentID()." LIMIT 1;";
                $dbo->setQuery($q);
                $dbo->Query($q);
                if( $dbo->getNumRows() > 0 ) {
                    $app = $dbo->loadAssoc();
                    $pay_str = $app['id'].'='.$app['name'];
                }
                if($debug_mode === false) {
					if($neworder_status == 'confirmed') {
						//Confirmed Status
						$arrbusy = array();
						foreach($arrpeople as $rnum => $party) {
							foreach($arr_rates as $idr => $rate) {
								$q = "INSERT INTO `#__vikbooking_busy` (`idroom`,`checkin`,`checkout`,`realback`) VALUES('" . $idr . "', '" . $args['start_ts'] . "', '" . $args['end_ts'] . "','" . $realback . "');";
								$dbo->setQuery($q);
								$dbo->Query($q);
								$lid = $dbo->insertid();
								$arrbusy[] = $lid;
							}
						}
						$q = "INSERT INTO `#__vikbooking_orders` (`custdata`,`ts`,`status`,`days`,`checkin`,`checkout`,`custmail`,`sid`,`totpaid`,`ujid`,`coupon`,`roomsnum`,`total`,`idorderota`,`channel`,`lang`,`country`,`tot_taxes`,`tot_city_taxes`,`tot_fees`,`idpayment`) VALUES(" . $dbo->quote($custdata) . "," . $nowts . ",'confirmed','" . $args['nights'] . "','" . $args['start_ts'] . "','" . $args['end_ts'] . "'," . $dbo->quote($customer_email) . ",'" . $sid . "',NULL,'',NULL,'".count($arrpeople)."','".$final_price."'," . $dbo->quote($args['reference_id']) . ",'tripconnect'," . $dbo->quote($langtag) . ",".(!empty($country_code) ? "".$dbo->quote($country_code)."" : 'NULL').",'".$arr_rates[$room_ind][$price_ind]['taxes']."','".$arr_rates[$room_ind][$price_ind]['city_taxes']."','".$arr_rates[$room_ind][$price_ind]['fees']."',".$dbo->quote($pay_str).");";
						$dbo->setQuery($q);
						$dbo->Query($q);
						$neworderid = $dbo->insertid();
						if(empty($neworderid)) {
							echo json_encode(array('e4j.error' => 'UnknownPartnerProblem', 'error.code' => 1));
							exit;
						}
						//ConfirmationNumber
						$confirmnumber = vikbooking::generateConfirmNumber($neworderid, true);
						//end ConfirmationNumber
						foreach($arrpeople as $rnum => $party) {
							foreach($arr_rates as $idr => $rate) {
								$q = "INSERT INTO `#__vikbooking_ordersbusy` (`idorder`,`idbusy`) VALUES('".$neworderid."', '".$arrbusy[($rnum - 1)]."');";
								$dbo->setQuery($q);
								$dbo->Query($q);
								$json_ch_age = '';
								if (count($party['children_age']) > 0) {
									$json_ch_age = json_encode(array('age' => $party['children_age']));
								}
								$opt_children = '';
								if(array_key_exists($rnum, $children_options)) {
									$opt_children .= $children_options[$rnum];
								}
								$q = "INSERT INTO `#__vikbooking_ordersrooms` (`idorder`,`idroom`,`adults`,`children`,`idtar`,`optionals`,`childrenage`,`t_first_name`,`t_last_name`) VALUES('".$neworderid."', '".$idr."', '".$arrpeople[$rnum]['adults']."', '".$arrpeople[$rnum]['children']."', '".$rate[0]['id']."', '".$options_str.$opt_children."', ".(!empty($json_ch_age) ? $dbo->quote($json_ch_age) : 'NULL').", ".$dbo->quote($args['rooms_info'][$rnum]['traveler_first_name']).", ".$dbo->quote($args['rooms_info'][$rnum]['traveler_last_name']).");";
								$dbo->setQuery($q);
								$dbo->Query($q);
							}
						}
						$esit = true;
						if (file_exists(JPATH_SITE . DS ."components". DS ."com_vikchannelmanager". DS . "helpers" . DS ."synch.vikbooking.php")) {
							require_once(JPATH_SITE . DS ."components". DS ."com_vikchannelmanager". DS . "helpers" . DS ."synch.vikbooking.php");
							$vcm = new synchVikBooking($neworderid);
							$vcm->sendRequest();
						}
					}else {
						//Pending Status
						$q = "INSERT INTO `#__vikbooking_orders` (`custdata`,`ts`,`status`,`days`,`checkin`,`checkout`,`custmail`,`sid`,`totpaid`,`ujid`,`coupon`,`roomsnum`,`total`,`idorderota`,`channel`,`lang`,`country`,`tot_taxes`,`tot_city_taxes`,`tot_fees`,`idpayment`) VALUES(" . $dbo->quote($custdata) . ",'" . $nowts . "','standby','" . $args['nights'] . "','" . $args['start_ts'] . "','" . $args['end_ts'] . "'," . $dbo->quote($customer_email) . ",'" . $sid . "',NULL,'',NULL,'".count($arrpeople)."','".$final_price."'," . $dbo->quote($args['reference_id']) . ",'tripconnect'," . $dbo->quote($langtag) . ",".(!empty($country_code) ? "".$dbo->quote($country_code)."" : 'NULL').",'".$arr_rates[$room_ind][$price_ind]['taxes']."','".$arr_rates[$room_ind][$price_ind]['city_taxes']."','".$arr_rates[$room_ind][$price_ind]['fees']."',".$dbo->quote($pay_str).");";
						$dbo->setQuery($q);
						$dbo->Query($q);
						$neworderid = $dbo->insertid();
						if(empty($neworderid)) {
							echo json_encode(array('e4j.error' => 'UnknownPartnerProblem', 'error.code' => 2));
							exit;
						}
						foreach($arrpeople as $rnum => $party) {
							foreach($arr_rates as $idr => $rate) {
								$json_ch_age = '';
								if (count($party['children_age']) > 0) {
									$json_ch_age = json_encode(array('age' => $party['children_age']));
								}
								$opt_children = '';
								if(array_key_exists($rnum, $children_options)) {
									$opt_children .= $children_options[$rnum];
								}
								$q = "INSERT INTO `#__vikbooking_ordersrooms` (`idorder`,`idroom`,`adults`,`children`,`idtar`,`optionals`,`childrenage`,`t_first_name`,`t_last_name`) VALUES('".$neworderid."', '".$idr."', '".$arrpeople[$rnum]['adults']."', '".$arrpeople[$rnum]['children']."', '".$rate[0]['id']."', '".$options_str.$opt_children."', ".(!empty($json_ch_age) ? $dbo->quote($json_ch_age) : 'NULL').", ".$dbo->quote($args['rooms_info'][$rnum]['traveler_first_name']).", ".$dbo->quote($args['rooms_info'][$rnum]['traveler_last_name']).");";
								$dbo->setQuery($q);
								$dbo->Query($q);
							}
						}
						foreach($arrpeople as $rnum => $party) {
							foreach($arr_rates as $idr => $rate) {
								//$q = "INSERT INTO `#__vikbooking_tmplock` (`idroom`,`checkin`,`checkout`,`until`,`realback`) VALUES('" . $idr . "','" . $args['start_ts'] . "','" . $args['end_ts'] . "','" . vikbooking::getMinutesLock(true) . "','" . $realback . "');";
								//$dbo->setQuery($q);
								//$dbo->Query($q);
							}
						}
						$esit = true;
					}
				}
				
				$conf_mail_sent = false;
				if($debug_mode === false) {
					//send confirmation email to customer
					$conf_mail_sent = vikbooking::sendCustMailByOrderId($neworderid);
					//
				}
				
				$arr_rates['response'] = array(
					'esit' => $esit,
					'status' => $neworder_status,
					'reservationstatus' => 'Booked',
					'id' => $neworderid,
					'confirmnumber' => $confirmnumber,
					'orderlink' => $orderlink,
					'conf_mail_sent' => $conf_mail_sent,
					'currency' => vikbooking::getCurrencyName()
				);
				
				$response = $arr_rates;
								
				// store elapsed time statistics
                
                $elapsed_time = $crono->stop();
                
                VikChannelManager::storeCallStats(VikChannelManagerConfig::TRIP_CONNECT, 'tac_bs_l', $elapsed_time);
                
                //
                
                $args['response'] = $response['response'];
                
                // STORE CC INFO ORDER
                if( $arr_rates['response']['esit'] && $debug_mode === false ) {
                    
                    $q = "UPDATE `#__vikbooking_orders` SET `paymentlog`=".$dbo->quote(VikChannelManager::getTripConnectPaymentLog($args['payment_method']))." WHERE `id`=".$arr_rates['response']['id']." LIMIT 1;";
                    $dbo->setQuery($q);
                    $dbo->Query($q);
                    
                    $admail = VikChannelManager::getAdminMail();
                    
                    $vik = new VikApplication(VersionListener::getID());
                    $vik->sendMail(
                        $admail, 
                        $admail, 
                        $admail, 
                        $admail, 
                        JText::_('VCMTACNEWORDERMAILSUBJECT'),
                        VikChannelManager::getTripConnectCCMailContent($args),
                        false
                    );
                }
                //
				
				echo json_encode( $response );
				exit;
				
			} else {
				$response = 'e4j.error.auth';
			}
		}
		echo $response;
		exit;
	}

	/**
	* TripAdvisor rooms availability listener
	*/
	function tac_av_l() {
	    
        $crono = new Crono();
        $crono->start();
        
		$response = 'e4j.error';
		$args = array();
		$args['hash'] = JRequest::getString('e4jauth', '', 'request');
		$args['start_date'] = JRequest::getString('start_date', '', 'request');
		$args['end_date'] = JRequest::getString('end_date', '', 'request');
		$args['nights'] = JRequest::getInt('nights', 1, 'request');
		$args['num_rooms'] = JRequest::getInt('num_rooms', 1, 'request');
		$args['start_ts'] = strtotime($args['start_date']);
		$args['end_ts'] = strtotime($args['end_date']);
		
		$valid = true;
		foreach( $args as $k => $v ) {
			$valid = $valid && !empty($v);
		}
		
		//request type
		$req_type = JRequest::getString('req_type', '', 'request');
		
		//API version
		$tac_apiv = 4;
		//API v4
		$args['num_adults'] = JRequest::getInt('num_adults', 1, 'request');
		//API v5
		$args['adults'] = JRequest::getVar('adults', array());
		$args['children'] = JRequest::getVar('children', array());
		$args['children_age'] = JRequest::getVar('children_age', array());
		if(!empty($args['adults']) && !empty($args['children']) && !isset($_REQUEST['num_adults'])) {
			$tac_apiv = 5;
		}
		if($tac_apiv == 4) {
			$valid = !empty($args['num_adults']) ? $valid : false;
		}elseif($tac_apiv == 5) {
			$valid = !empty($args['adults']) ? $valid : false;
		}
		//
		
		if( $valid ) {
			$config = VikChannelManager::loadConfiguration();
			$channel = VikChannelManager::getChannelCredentials(VikChannelManagerConfig::TRIP_CONNECT);
			$checkauth = md5($channel['tripadvisorid'].'e4j'.$channel['tripadvisorid']);
			
			if( $checkauth == $args['hash'] ) {

				$response = $this->retrieve_av_l(VikChannelManager::getChannel(VikChannelManagerConfig::TRIP_CONNECT));
				
				//echo '<pre>'.print_r($response, true).'</pre>';
				
				// store elapsed time statistics
                
                $elapsed_time = $crono->stop();
                
                VikChannelManager::storeCallStats(VikChannelManagerConfig::TRIP_CONNECT, 'tac_av_l', $elapsed_time);
                
                //
				
				echo json_encode( $response );
				exit;
				
			} else {
				$response = 'e4j.error.auth';
			}
		}
		echo $response;
		exit;
	}
	
	/**
	* Trivago rooms availability listener
	*/
	function tri_av_l() {
		
		$crono = new Crono();
		$crono->start();
		
		$response = 'e4j.error';
		$args = array();
		$args['hash'] = JRequest::getString('e4jauth', '', 'request');
		$args['start_date'] = JRequest::getString('start_date', '', 'request');
		$args['end_date'] = JRequest::getString('end_date', '', 'request');
		$args['nights'] = JRequest::getInt('nights', 1, 'request');
		$args['num_rooms'] = JRequest::getInt('num_rooms', 1, 'request');
		$args['start_ts'] = strtotime($args['start_date']);
		$args['end_ts'] = strtotime($args['end_date']);
		
		$valid = true;
		foreach( $args as $k => $v ) {
			$valid = $valid && !empty($v);
		}
		
		if( $valid ) {
			$config = VikChannelManager::loadConfiguration();
			$channel = VikChannelManager::getChannelCredentials(VikChannelManagerConfig::TRIVAGO);
			$checkauth = md5($channel['trivagoid'].'e4j'.$channel['trivagoid']);
			
			if( $checkauth == $args['hash'] ) {

				$response = $this->retrieve_av_l(VikChannelManager::getChannel(VikChannelManagerConfig::TRIVAGO));

				// store elapsed time statistics
				
				$elapsed_time = $crono->stop();
				
				VikChannelManager::storeCallStats(VikChannelManagerConfig::TRIVAGO, 'tri_av_l', $elapsed_time);
				
				//
				
				echo json_encode( $response );
				exit;
				
			} else {
				$response = 'e4j.error.auth';
			}
		}
		echo $response;
		exit;
	}

	private static function retrieve_av_l($channel = array()) {

		$response = 'e4j.error';
		$args = array();
		$args['hash'] = JRequest::getString('e4jauth', '', 'request');
		$args['start_date'] = JRequest::getString('start_date', '', 'request');
		$args['end_date'] = JRequest::getString('end_date', '', 'request');
		$args['nights'] = JRequest::getInt('nights', 1, 'request');
		$args['num_rooms'] = JRequest::getInt('num_rooms', 1, 'request');
		$args['start_ts'] = strtotime($args['start_date']);
		$args['end_ts'] = strtotime($args['end_date']);
		
		//request type
		$req_type = JRequest::getString('req_type', '', 'request');
		
		//API version
		$tac_apiv = 4;
		//API v4
		$args['num_adults'] = JRequest::getInt('num_adults', 1, 'request');
		//API v5
		$args['adults'] = JRequest::getVar('adults', array());
		$args['children'] = JRequest::getVar('children', array());
		$args['children_age'] = JRequest::getVar('children_age', array());
		if(!empty($args['adults']) && !empty($args['children']) && !isset($_REQUEST['num_adults'])) {
			$tac_apiv = 5;
		}
		//

		$tac_rooms = array();
		$dbo = JFactory::getDBO();
		
		if((int)$channel['uniquekey'] == (int)VikChannelManagerConfig::TRIP_CONNECT) {
			$q = "SELECT `id_vb_room` AS `id` FROM `#__vikchannelmanager_tac_rooms`;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if( $dbo->getNumRows() == 0 ) {
				echo json_encode(array('e4j.error' => '`#__vikchannelmanager_tac_rooms` is empty'));
				exit;
			}
		}elseif((int)$channel['uniquekey'] == (int)VikChannelManagerConfig::TRIVAGO) {
			$q = "SELECT `id_vb_room` AS `id` FROM `#__vikchannelmanager_tri_rooms`;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if( $dbo->getNumRows() == 0 ) {
				echo json_encode(array('e4j.error' => '`#__vikchannelmanager_tri_rooms` is empty'));
				exit;
			}
		}else {
			echo json_encode(array('e4j.error' => 'invalid channel id ('.(int)$channel['uniquekey'].')'));
			exit;
		}
		
		$rows = $dbo->loadAssocList();
		for( $i = 0; $i < count($rows); $i++ ) {
			$tac_rooms[$i] = $rows[$i]['id'];
		}
		
		$avail_rooms = array();
		
		if($tac_apiv == 5) {
			//compose adults-children array and sql clause
			$arradultsrooms = array();
			$arradultsclause = array();
			$arrpeople = array();
			if (count($args['adults']) > 0) {
				foreach($args['adults'] as $kad => $adu) {
					$roomnumb = $kad + 1;
					if (strlen($adu)) {
						$numadults = intval($adu);
						if ($numadults >= 0) {
							$arradultsrooms[$roomnumb] = $numadults;
							$arrpeople[$roomnumb]['adults'] = $numadults;
							$strclause = "(`fromadult`<=".$numadults." AND `toadult`>=".$numadults."";
							if (!empty($args['children'][$kad]) && intval($args['children'][$kad]) > 0) {
								$numchildren = intval($args['children'][$kad]);
								$arrpeople[$roomnumb]['children'] = $numchildren;
								$arrpeople[$roomnumb]['children_age'] = count($args['children_age'][$roomnumb]) > 0 ? $args['children_age'][$roomnumb] : array();
								$strclause .= " AND `fromchild`<=".$numchildren." AND `tochild`>=".$numchildren."";
							}else {
								$arrpeople[$roomnumb]['children'] = 0;
								$arrpeople[$roomnumb]['children_age'] = array();
								if (intval($args['children'][$kad]) == 0) {
									$strclause .= " AND `fromchild` = 0";
								}
							}
							$strclause .= " AND `totpeople` >= ".($arrpeople[$roomnumb]['adults'] + $arrpeople[$roomnumb]['children']);
							$strclause .= " AND `mintotpeople` <= ".($arrpeople[$roomnumb]['adults'] + $arrpeople[$roomnumb]['children']);
							$strclause .= ")";
							$arradultsclause[] = $strclause;
						}
					}
				}
			}
			//
			//Set $args['adults'] to the number of adults occupying the first room but it could be a party of multiple rooms
			$args['num_adults'] = $arrpeople[1]['adults'];
			//
			//This clause would return one room type for each party type: implode(" OR ", $arradultsclause) - the AND clause must be used rather than OR.
			$q = "SELECT `id`, `units` FROM `#__vikbooking_rooms` WHERE `avail`=1 AND (".implode(" AND ", $arradultsclause).") AND `id` IN (".implode(',', $tac_rooms).");";
		}else {
			//API v4
			$arrpeople = array();
			$arrpeople[1]['adults'] = $args['num_adults'];
			$arrpeople[1]['children'] = 0;
			$q = "SELECT `id`, `units` FROM `#__vikbooking_rooms` WHERE `avail`=1 AND `toadult`>=".$args['num_adults']." AND `id` IN (".implode(',', $tac_rooms).");";
		}
		$dbo->setQuery($q);
		$dbo->Query($q);
		if( $dbo->getNumRows() == 0 ) {
			echo json_encode(array('e4j.error' => 'The Query for fetching the rooms returned an empty result'));
			exit;
		}

		$avail_rooms = $dbo->loadAssocList();
		
		require_once (JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'helpers'.DS.'lib.vikbooking.php');
		
		// arr[0] = (sec) checkin, arr[1] = (sec) checkout
		$check_in_out = vikbooking::getTimeOpenStore();
		$args['start_ts'] += $check_in_out[0];
		$args['end_ts'] += $check_in_out[1];
		
		$room_ids = array();
		for( $i = 0; $i < count($avail_rooms); $i++ ) {
			$room_ids[$i] = $avail_rooms[$i]['id'];
		}
		
		$all_restrictions = vikbooking::loadRestrictions(true, $room_ids);
		$glob_restrictions = vikbooking::globalRestrictions($all_restrictions);
		
		if( count($glob_restrictions) > 0 && strlen(vikbooking::validateRoomRestriction($glob_restrictions, getdate($args['start_ts']), getdate($args['end_ts']), $args['nights'])) > 0) {
			echo json_encode(array('e4j.error' => 'Unable to proceed because of booking Restrictions in these dates'));
			exit;
		}
		
		//Get Rates
		$room_ids = array();
		foreach( $avail_rooms as $k => $room ) {
			$room_ids[$room['id']] = $room;
		}
		$rates = array();
		//$q = "SELECT `p`.*, `r`.`id` AS `r_reference_id`, `r`.`img`, `r`.`units`, `r`.`moreimgs`, `r`.`imgcaptions`, `prices`.`name` AS `pricename`, `prices`.`breakfast_included`, `prices`.`free_cancellation`, `prices`.`canc_deadline` FROM `#__vikbooking_dispcost` AS `p`, `#__vikbooking_rooms` AS `r`, `#__vikbooking_prices` AS `prices` WHERE `r`.`id`=`p`.`idroom` AND `p`.`idprice`=`prices`.`id` AND `p`.`days`=".$args['nights']." AND `r`.`id` IN (".implode(',', array_keys($room_ids)).") ORDER BY `p`.`cost` ASC;";
		$q = "SELECT `p`.*, `r`.`id` AS `r_reference_id`, `r`.`name` AS `r_short_desc`, `r`.`img`, `r`.`units`, `r`.`moreimgs`, `r`.`imgcaptions`, `prices`.`id` AS `price_reference_id`, `prices`.`name` AS `pricename`, `prices`.`breakfast_included`, `prices`.`free_cancellation`, `prices`.`canc_deadline` FROM `#__vikbooking_dispcost` AS `p`, `#__vikbooking_rooms` AS `r`, `#__vikbooking_prices` AS `prices` WHERE `r`.`id`=`p`.`idroom` AND `p`.`idprice`=`prices`.`id` AND `p`.`days`=".$args['nights']." AND `r`.`id` IN (".implode(',', array_keys($room_ids)).") ORDER BY `p`.`cost` ASC;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if( $dbo->getNumRows() == 0 ) {
			echo json_encode(array('e4j.error' => 'The Query for fetching the rates returned an empty result'));
			exit;
		}
		$rates = $dbo->loadAssocList();
		if(method_exists('vikbooking', 'getTranslator')) {
			$vbo_tn = vikbooking::getTranslator();
			$vbo_tn->translateContents($rates, '#__vikbooking_rooms', array('id' => 'r_reference_id', 'r_short_desc' => 'name'));
			$vbo_tn->translateContents($rates, '#__vikbooking_prices', array('id' => 'price_reference_id', 'pricename' => 'name'));
		}
		$arr_rates = array();
		foreach( $rates as $rate ) {
			$arr_rates[$rate['idroom']][] = $rate;
		}
		
		//Check Availability for the rooms with a rate for this number of nights
		$minus_units = 0;
		if(count($arr_rates) < $args['num_rooms']) {
			$minus_units = $args['num_rooms'] - count($arr_rates);
		}
		$groupdays = vikbooking::getGroupDays($args['start_ts'], $args['end_ts'], $args['nights']);
		$morehst = vikbooking::getHoursRoomAvail() * 3600;
		$allbusy = vikbooking::loadBusyRecords(array_keys($arr_rates), time());
		foreach( $arr_rates as $k => $datarate ) {
			$room = $room_ids[$k];
			$consider_units = $room['units'] - $minus_units;
			//March 31st 2015: old availability check
			//if( !vikbooking::roomBookable($room['id'], $consider_units, $args['start_ts'], $args['end_ts']) || $consider_units <= 0) { = do unset, continue.
			//New Availability Check + Unitsleft
			if($consider_units <= 0) {
				unset($arr_rates[$k]);
				continue;
			}
			$units_left = $room['units'];
			if (count($allbusy) > 0 && array_key_exists($k, $allbusy) && count($allbusy[$k]) > 0) {
				$units_booked = array();
				foreach ($groupdays as $gday) {
					$bfound = 0;
					foreach ($allbusy[$k] as $bu) {
						if ($gday >= $bu['checkin'] && $gday <= ($morehst + $bu['checkout'])) {
							$bfound++;
						}
					}
					if ($bfound >= $consider_units) {
						unset($arr_rates[$k]);
						continue 2;
					}else {
						$units_booked[] = $bfound;
					}
				}
				if(count($units_booked) > 0) {
					$tot_u_booked = max($units_booked);
					$tot_u_left = ($room['units'] - $tot_u_booked);
					$units_left = $tot_u_left > 0 ? $tot_u_left : 1;
				}
			}
			foreach ($arr_rates[$k] as $tpk => $tpv) {
				//Cancellation Deadline and Rooms Available
				if(array_key_exists('canc_deadline', $tpv) && !empty($tpv['canc_deadline']) && intval($tpv['canc_deadline']) > 0) {
					$is_dst = date('I', $args['start_ts']);
					$canc_date_ts = $args['start_ts'] - (86400 * intval($tpv['canc_deadline']));
					$is_now_dst = date('I', $canc_date_ts);
					if ($is_dst != $is_now_dst) {
						//Daylight Saving Time has changed, check how
						if ((int)$is_dst == 1) {
							$canc_date_ts += 3600;
						}else {
							$canc_date_ts -= 3600;
						}
						$is_dst = $is_now_dst;
					}
					$arr_rates[$k][$tpk]['canc_deadline_date'] = date('Y-m-dTH:i:s', $canc_date_ts);
				}
				$arr_rates[$k][$tpk]['unitsleft'] = (int)$units_left;
			}
			//
			if( count($all_restrictions) > 0 ) {
				$room_restr = vikbooking::roomRestrictions($room['id'], $all_restrictions);
				if( count($room_restr) > 0 ) {
					if( strlen(vikbooking::validateRoomRestriction($room_restr, getdate($args['start_ts']), getdate($args['end_ts']), $args['nights'])) > 0 ) {
						unset($arr_rates[$k]);
					}
				}
			}
		}
		
		if( count($arr_rates) == 0 ) {
			echo json_encode(array('e4j.error' => 'No availability for these dates'));
			exit;
		}
		
		//apply special prices
		$arr_rates = vikbooking::applySeasonalPrices($arr_rates, $args['start_ts'], $args['end_ts']);
		//
		
		//children ages charge
		$children_sums = array();
		$children_sums_rooms = array();
		//end children ages charge
		
		//sum charges/discounts per occupancy for each room party
		foreach($arrpeople as $roomnumb => $party) {
			//charges/discounts per adults occupancy
			foreach ($arr_rates as $r => $rates) {
				$children_charges = vikbooking::getChildrenCharges($r, $party['children'], $party['children_age'], $args['nights']);
				if(count($children_charges) > 0) {
					$children_sums[$r] += $children_charges['total'];
					$children_sums_rooms[$roomnumb][$r] += $children_charges['total'];
				}
				$diffusageprice = vikbooking::loadAdultsDiff($r, $party['adults']);
				//Occupancy Override - Special Price may be setting a charge/discount for this occupancy while default price had no occupancy pricing
				if (!is_array($diffusageprice)) {
					foreach($rates as $kpr => $vpr) {
						if(array_key_exists('occupancy_ovr', $vpr) && array_key_exists($party['adults'], $vpr['occupancy_ovr']) && strlen($vpr['occupancy_ovr'][$party['adults']]['value'])) {
							$diffusageprice = $vpr['occupancy_ovr'][$party['adults']];
							break;
						}
					}
					reset($rates);
				}
				//
				if (is_array($diffusageprice)) {
					foreach($rates as $kpr => $vpr) {
						if($roomnumb == 1) {
							$arr_rates[$r][$kpr]['costbeforeoccupancy'] = $arr_rates[$r][$kpr]['cost'];
						}
						//Occupancy Override
						if(array_key_exists('occupancy_ovr', $vpr) && array_key_exists($party['adults'], $vpr['occupancy_ovr']) && strlen($vpr['occupancy_ovr'][$party['adults']]['value'])) {
							$diffusageprice = $vpr['occupancy_ovr'][$party['adults']];
						}
						//
						$room_cost = $arr_rates[$r][$kpr]['costbeforeoccupancy'];
						$arr_rates[$r][$kpr]['diffusage'] = $party['adults'];
						if ($diffusageprice['chdisc'] == 1) {
							//charge
							if ($diffusageprice['valpcent'] == 1) {
								//fixed value
								$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $arr_rates[$r][$kpr]['days'] : $diffusageprice['value'];
								$arr_rates[$r][$kpr]['diffusagecost'][$roomnumb] = $aduseval;
								$arr_rates[$r][$kpr]['cost'] += $aduseval;
								$room_cost += $aduseval;
							}else {
								//percentage value
								$aduseval = $diffusageprice['pernight'] == 1 ? round(($arr_rates[$r][$kpr]['costbeforeoccupancy'] * $diffusageprice['value'] / 100) * $arr_rates[$r][$kpr]['days'], 2) : round(($arr_rates[$r][$kpr]['costbeforeoccupancy'] * $diffusageprice['value'] / 100), 2);
								$arr_rates[$r][$kpr]['diffusagecost'][$roomnumb] = $aduseval;
								$arr_rates[$r][$kpr]['cost'] += $aduseval;
								$room_cost += $aduseval;
							}
						}else {
							//discount
							if ($diffusageprice['valpcent'] == 1) {
								//fixed value
								$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $arr_rates[$r][$kpr]['days'] : $diffusageprice['value'];
								$arr_rates[$r][$kpr]['diffusagediscount'][$roomnumb] = $aduseval;
								$arr_rates[$r][$kpr]['cost'] -= $aduseval;
								$room_cost -= $aduseval;
							}else {
								//percentage value
								$aduseval = $diffusageprice['pernight'] == 1 ? round(((($arr_rates[$r][$kpr]['costbeforeoccupancy'] / $arr_rates[$r][$kpr]['days']) * $diffusageprice['value'] / 100) * $arr_rates[$r][$kpr]['days']), 2) : round(($arr_rates[$r][$kpr]['costbeforeoccupancy'] * $diffusageprice['value'] / 100), 2);
								$arr_rates[$r][$kpr]['diffusagediscount'][$roomnumb] = $aduseval;
								$arr_rates[$r][$kpr]['cost'] -= $aduseval;
								$room_cost -= $aduseval;
							}
						}
						//Trivago: save in array the cost for each Room Number when multiple rooms
						//Their system needs the rooms for any party returned separately, therefore the cost must be exact depending on the occupancy
						if((int)$channel['uniquekey'] == (int)VikChannelManagerConfig::TRIVAGO && $args['num_rooms'] > 1) {
							$arr_rates[$r][$kpr]['cost_array'][(int)$roomnumb] = $room_cost;
						}
					}
				}elseif($roomnumb == 1) {
					foreach($rates as $kpr => $vpr) {
						$arr_rates[$r][$kpr]['costbeforeoccupancy'] = $arr_rates[$r][$kpr]['cost'];
						//Trivago: save in array the cost for each Room Number when multiple rooms
						//Their system needs the rooms for any party returned separately, therefore the cost must be exact depending on the occupancy
						if((int)$channel['uniquekey'] == (int)VikChannelManagerConfig::TRIVAGO && $args['num_rooms'] > 1) {
							$arr_rates[$r][$kpr]['cost_array'][(int)$roomnumb] = $arr_rates[$r][$kpr]['cost'];
						}
					}
				}elseif((int)$channel['uniquekey'] == (int)VikChannelManagerConfig::TRIVAGO && $args['num_rooms'] > 1) {
					//Trivago: save in array the cost for each Room Number when multiple rooms
					//Their system needs the rooms for any party returned separately, therefore the cost must be exact depending on the occupancy
					foreach($rates as $kpr => $vpr) {
						$arr_rates[$r][$kpr]['cost_array'][(int)$roomnumb] = $arr_rates[$r][$kpr]['cost'];
					}
				}
			}
			//end charges/discounts per adults occupancy
		}
		//end sum charges/discounts per occupancy for each room party
		
		//if the rooms are given to a party of multiple rooms, multiply the basic rates per room per number of rooms
		for($i = 2; $i <= $args['num_rooms']; $i++) {
			foreach ($arr_rates as $r => $rates) {
				foreach($rates as $kpr => $vpr) {
					$arr_rates[$r][$kpr]['cost'] += $arr_rates[$r][$kpr]['costbeforeoccupancy'];
				}
			}
		}
		//end if the rooms are given to a party of multiple rooms, multiply the basic rates per room per number of rooms
		
		//children ages charge
		if(count($children_sums) > 0) {
			foreach ($arr_rates as $r => $rates) {
				if(array_key_exists($r, $children_sums)) {
					foreach($rates as $kpr => $vpr) {
						$arr_rates[$r][$kpr]['cost'] += $children_sums[$r];
					}
				}
			}
		}
		if((int)$channel['uniquekey'] == (int)VikChannelManagerConfig::TRIVAGO) {
			foreach($arrpeople as $roomnumb => $party) {
				if(count($children_sums_rooms[$roomnumb]) > 0) {
					foreach ($arr_rates as $r => $rates) {
						if(array_key_exists($r, $children_sums_rooms[$roomnumb])) {
							foreach($rates as $kpr => $vpr) {
								$arr_rates[$r][$kpr]['cost_array'][(int)$roomnumb] += $children_sums_rooms[$roomnumb][$r];
							}
						}
					}
				}
			}
		}
		//end children ages charge
		
		//sort results by price ASC
		$arr_rates = vikbooking::sortResults($arr_rates);
		//
		
		//compose taxes information
		$session = JFactory::getSession();
		$sval = $session->get('vbivaInclusa', '');
		if(strlen($sval) > 0) {
			$ivainclusa = $sval;
		}else {
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='ivainclusa';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$iva = $dbo->loadAssocList();
			$session->set('vbivaInclusa', $iva[0]['setting']);
			$ivainclusa = $iva[0]['setting'];
		}
		$rates_ids = array();
		foreach ($arr_rates as $r => $rate) {
			foreach ($rate as $ids) {
				if (!in_array($ids['idprice'], $rates_ids)) {
					$rates_ids[] = $ids['idprice'];
				}
			}
		}
		$tax_rates = array();
		$q = "SELECT `p`.`id`,`t`.`aliq` FROM `#__vikbooking_prices` AS `p` LEFT JOIN `#__vikbooking_iva` `t` ON `p`.`idiva`=`t`.`id` WHERE `p`.`id` IN (".implode(',', $rates_ids).");";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$alltaxrates = $dbo->loadAssocList();
			foreach ($alltaxrates as $tx) {
				if(!empty($tx['aliq']) && $tx['aliq'] > 0) {
					$tax_rates[$tx['id']] = $tx['aliq'];
				}
			}
		}
		if(count($tax_rates) > 0) {
			foreach ($arr_rates as $r => $rates) {
				//$city_tax_fees = vikbooking::getMandatoryTaxesFees(array($r), $args['num_adults'], $args['nights']);
				foreach ($rates as $k => $rate) {
					if (array_key_exists($rate['idprice'], $tax_rates)) {
						if (intval($ivainclusa) == 1) {
							//prices tax included
							$realcost = $rate['cost'];
							$tax_oper = ($tax_rates[$rate['idprice']] + 100) / 100;
							$taxes = $rate['cost'] - ($rate['cost'] / $tax_oper);
						}else {
							//prices tax excluded
							$realcost = $rate['cost'] * (100 + $tax_rates[$rate['idprice']]) / 100;
							$taxes = $realcost - $rate['cost'];
						}
						if($req_type == 'hotel_availability' || $req_type == 'booking_availability') {
							//always set 'cost' to the base rate tax excluded
							$realcost = $realcost - $taxes;
						}
						$arr_rates[$r][$k]['cost'] = round($realcost, 2);
						$arr_rates[$r][$k]['taxes'] = round($taxes, 2);
						//$arr_rates[$r][$k]['city_taxes'] = round($city_tax_fees['city_taxes'], 2);
						//$arr_rates[$r][$k]['fees'] = round($city_tax_fees['fees'], 2);
					}
				}
			}
			//sum taxes/fees for each room party
			foreach($arrpeople as $roomnumb => $party) {
				foreach ($arr_rates as $r => $rates) {
					$city_tax_fees = vikbooking::getMandatoryTaxesFees(array($r), $party['adults'], $args['nights']);
					foreach ($rates as $k => $rate) {
						//Trivago: save in array the city_taxes and fees for each Room Number when multiple rooms
						//Their system needs the rooms for any party returned separately, therefore the taxes and fees must be exact depending on the occupancy
						if((int)$channel['uniquekey'] == (int)VikChannelManagerConfig::TRIVAGO && $args['num_rooms'] > 1) {
							$arr_rates[$r][$k]['city_taxes_array'][(int)$roomnumb] = round($city_tax_fees['city_taxes'], 2);
							$arr_rates[$r][$k]['fees_array'][(int)$roomnumb] = round($city_tax_fees['fees'], 2);
							//Trivago re-calculate taxes
							if (array_key_exists($rate['idprice'], $tax_rates)) {
								if (intval($ivainclusa) == 1) {
									//prices tax included
									$realcost = $arr_rates[$r][$k]['cost_array'][(int)$roomnumb];
									$tax_oper = ($tax_rates[$rate['idprice']] + 100) / 100;
									$taxes = $arr_rates[$r][$k]['cost_array'][(int)$roomnumb] - ($arr_rates[$r][$k]['cost_array'][(int)$roomnumb] / $tax_oper);
								}else {
									//prices tax excluded
									$realcost = $arr_rates[$r][$k]['cost_array'][(int)$roomnumb] * (100 + $tax_rates[$rate['idprice']]) / 100;
									$taxes = $realcost - $arr_rates[$r][$k]['cost_array'][(int)$roomnumb];
								}
								//always set 'cost' to the base rate tax excluded
								$realcost = $realcost - $taxes;
								$arr_rates[$r][$k]['cost_array'][(int)$roomnumb] = round($realcost, 2);
								$arr_rates[$r][$k]['taxes_array'][(int)$roomnumb] = round($taxes, 2);
							}
							//end Trivago re-calculate taxes
						}
						//TripConnect
						$arr_rates[$r][$k]['city_taxes'] += round($city_tax_fees['city_taxes'], 2);
						$arr_rates[$r][$k]['fees'] += round($city_tax_fees['fees'], 2);
					}
				}
			}
			//end sum taxes/fees for each room party
		}else {
			foreach ($arr_rates as $r => $rates) {
				foreach ($rates as $k => $rate) {
					$arr_rates[$r][$k]['cost'] = round($rate['cost'], 2);
					$arr_rates[$r][$k]['taxes'] = round(0, 2);
					$arr_rates[$r][$k]['city_taxes'] = round(0, 2);
					$arr_rates[$r][$k]['fees'] = round(0, 2);
				}
			}
		}
		//end compose taxes information
		
		return $arr_rates;

	}
	
	/**
	* Orders retrieve listener
	*/
	function orders_rv_l() {
		$response = 'e4j.error';
		
		$args = array();
		$args['hash'] = JRequest::getString('e4jauth', '', 'request');
		
		$api_key = VikChannelManager::getApiKey();
		
		if( $args['hash'] != md5($api_key) ) {
			echo $response.'.HashMismatch';
			die;
		}
		
		$dbo = JFactory::getDBO();
		
		$args['ids'] = JRequest::getVar('ids', array());
		$args['filter'] = JRequest::getVar('channel_filter', '');
		$args['checkout'] = explode('-', JRequest::getVar('checkout'));
        $args['id_room'] = JRequest::getInt('id_room');
		
		$where_claus = '';
		
		if( !empty($args['filter']) ) {
			$where_claus = '`o`.`channel`='.$dbo->quote($args['filter']);
		}
		
		if( !empty($args['ids']) ) {
			if( !empty($where_claus) ) {
				$where_claus .= ' AND ';
			}
			$where_claus .= '`o`.`id` IN ('.implode(',', $args['ids']).')';
		}
		
		if( count($args['checkout']) == 3 ) {
			$ts = mktime(0, 0, 0, $args['checkout'][1], $args['checkout'][2], $args['checkout'][0]);
			
			if( !empty($where_claus) ) {
				$where_claus .= ' AND ';
			}
			$where_claus .= $ts.'<`o`.`checkout` AND `o`.`checkout`<'.($ts+86399);
		}

        if( !empty($args['id_room']) ) {
            if( !empty($where_claus) ) {
                $where_claus .= ' AND ';
            }
            $where_claus .= '`or`.`idroom`='.$args['id_room'];
        }

        if( !empty($where_claus) ) {
        	$where_claus = 'AND '.$where_claus;
        }
		
		$orders = array();
		
		$q = "SELECT `o`.*, `or`.`idroom`, `or`.`adults`, `or`.`children`, `or`.`t_first_name`, `or`.`t_last_name`, `r`.`name` 
		FROM `#__vikbooking_orders` AS `o`, `#__vikbooking_ordersrooms` AS `or`, `#__vikbooking_rooms` AS `r`  
		WHERE `o`.`id`=`or`.`idorder` AND `or`.`idroom`=`r`.`id` AND `o`.`status`='confirmed' ".$where_claus.";";
		
		$dbo->setQuery($q);
		$dbo->Query($q);
		if( $dbo->getNumRows() > 0 ) {
			$rows = $dbo->loadAssocList();
			foreach( $rows as $r ) {
				$r['checkin_date'] = date('Y-m-d', $r['checkin']);
				$r['checkout_date'] = date('Y-m-d', $r['checkout']);
				$orders[$r['id']] = $r;
			}
		}
		
		echo json_encode($orders);
		die;
		
	}

	/**
	* Stats listener
	*/
	function retrieve_stats_l() {
		$response = 'e4j.error';
		
		$args = array();
		$args['hash'] = JRequest::getString('e4jauth', '', 'request');
		
		$api_key = VikChannelManager::getApiKey();
		
		if( $args['hash'] != md5($api_key) ) {
			echo $response.'.HashMismatch';
			die;
		}
		
		$args['channel'] = JRequest::getVar('channel', -1);
		
		$dbo = JFactory::getDBO();
		
		$stats = array();
		$q = "SELECT * FROM `#__vikchannelmanager_call_stats` WHERE `channel`=".$dbo->quote($args['channel'])." OR ".$dbo->quote($args['channel'])."='-1' ORDER BY `channel` ASC, `call` ASC;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if( $dbo->getNumRows() > 0 ) {
			$stats = $dbo->loadAssocList();
		}
		
		echo json_encode($stats);
		
		die;
	}

	/**
	* Input Output Diagnostic
	*/

	function iod_rq() {

		$args = array();
		$args['hash'] = JRequest::getString('e4jauth', '', 'request');
		$args['token'] = JRequest::getString('token', '', 'request');
		$args['bookings'] = json_decode(JRequest::getString('bookings', '', 'request'), true);

		if(function_exists('json_last_error')) {
			$decode_err = json_last_error();
			switch ($decode_err) {
				case JSON_ERROR_NONE:
					break;
				case JSON_ERROR_DEPTH:
					echo 'e4j.error.Curl.Broken:Maximum stack depth exceeded '.strlen($_REQUEST['bookings']);
					exit;
				case JSON_ERROR_STATE_MISMATCH:
					echo 'e4j.error.Curl.Broken:Underflow or the modes mismatch '.str_replace(':', '-', str_replace('.', ' ', $_REQUEST['bookings']));
					exit;
				case JSON_ERROR_CTRL_CHAR:
					echo 'e4j.error.Curl.Broken:Unexpected control character found '.str_replace(':', '-', str_replace('.', ' ', $_REQUEST['bookings']));
					exit;
				case JSON_ERROR_SYNTAX:
					echo 'e4j.error.Curl.Broken:Syntax error or malformed JSON <br/>'.str_replace(':', '-', str_replace('.', ' ', $_REQUEST['bookings']));
					exit;
				case JSON_ERROR_UTF8:
					echo 'e4j.error.Curl.Broken:Malformed UTF-8 characters and possibly incorrectly encoded <br/>'.str_replace(':', '-', str_replace('.', ' ', $_REQUEST['bookings']));
					exit;
				default:
					echo 'e4j.error.Curl.Broken:Unknown Decoding Error <br/>'.str_replace(':', '-', str_replace('.', ' ', $_REQUEST['bookings']));
					exit;
			}
		}else {
			if(!is_array($args['bookings'])) {
				echo 'e4j.error.Curl.Broken:Cannot Detect Decoding Error <br/>'.str_replace(':', '-', str_replace('.', ' ', $_REQUEST['bookings']));
				exit;
			}
		}
		
		$api_key = VikChannelManager::getApiKey();
		
		if( $args['hash'] != md5($api_key) ) {
			echo 'e4j.error.Authentication';
			exit;
		}

		$filename = JPATH_SITE.DS.'components'.DS.'com_vikchannelmanager'.DS.'assets'.DS.$args['token'].".txt";

		if( !file_exists($filename) ) {
			echo 'e4j.error.File.NotFound';
			exit;
		}

		if (!class_exists('Encryption')) {
			require_once(JPATH_SITE.DS.'components'.DS.'com_vikchannelmanager'.DS.'helpers'.DS.'cypher.php');
		}
		$cipher = new Encryption(md5($api_key));

		// PARSE ORDER
		$order_str = "";
		foreach( $args['bookings']['orders'] as $o ) {
			if( !empty($order_str) ) {
				$order_str .= "--------------------------------------------------<br />";
			}

			foreach( $o as $section => $arr ) {

				$order_str .= "### ".ucwords(str_replace("_", " ", $section))." ###<br />";

				foreach( $arr as $k => $v ) {
					if( is_array($v) ) {
						$v = implode(", ", $v);
					}

					if( $k == "credit_card" ) {
						$v = $cipher->decode($v);
					}

					$order_str .= ucwords(str_replace("_", " ", $k)).": ".$v."<br />";
				}

			}
		}
		//////////////

		$handle = fopen($filename, "w");
		$bytes = fwrite($handle, $order_str);
		fclose($handle);

		if( $bytes == 0 ) {
			echo 'e4j.error.File.Permissions.Write';
			exit;
		}

		echo "e4j.ok";
		exit;
	}

	/**
	* Update listener
	*/
	function update_l() {
		$response = 'e4j.error';
		
		$args = array();
		$args['hash'] = JRequest::getString('e4jauth', '', 'request');
		
		$api_key = VikChannelManager::getApiKey();
		
		if( $args['hash'] != md5($api_key) ) {
			echo $response.'.HashMismatch';
			die;
		}
		
		$args['latest_version'] = JRequest::getVar('latest_version');
		$args['required'] = JRequest::getInt('required');
		$args['message'] = JRequest::getString('message', '', 'request');
		
		$dbo = JFactory::getDBO();
		
		if( $args['latest_version'] != VIKCHANNELMANAGER_SOFTWARE_VERSION ) {
			$q = "UPDATE `#__vikchannelmanager_config` SET `setting`='1' WHERE `param`='to_update' LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			
			$q = "UPDATE `#__vikchannelmanager_config` SET `setting`=".$dbo->quote($args['required'])." WHERE `param`='block_program' LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->Query($q);
            
            $vik = new VikApplication(VersionListener::getID());
            $vik->sendMail(
                'info@e4jconnect.com', 
                'e4jConnect', 
                VikChannelManager::getAdminMail(), 
                'info@e4jconnect.com', 
                'VikChannelManager - New Version', 
                VikChannelManager::getNewVersionMailContent($args)
            );
			
			echo 0; // NOT UPDATED
		} else {
			echo 1; // UPDATED
		}
		
		die;
		
	}
	
	/**
	* VCMV writes the version of VikChannelManager and 
	* the Joomla version where the program is running
	*/
	function vcmv() {
		$response = 'VCM_VERSION:'.VIKCHANNELMANAGER_SOFTWARE_VERSION;
		$responsetwo = '';
		echo $response;
		if (defined('JVERSION')) {
			$responsetwo = '__J_VERSION:'.JVERSION;
		}elseif (function_exists('jimport')) {
			jimport('joomla.version');
			if (class_exists('JVersion')) {
				$version = new JVersion();
				$responsetwo = '__J_VERSION:'.$version->getShortVersion();
			}
		}
		echo $responsetwo;
		exit;
	}

	/**
	* Pro_Level listener
	*/
	function pro_level_l() {
		$response = 'e4j.error';
		
		$args = array();
		$args['hash'] = JRequest::getString('e4jauth', '', 'request');
		
		$api_key = VikChannelManager::getApiKey();
		
		if( $args['hash'] != md5($api_key) ) {
			echo $response.'.HashMismatch';
			die;
		}
		
		$args['level'] = JRequest::getInt('level');
		
		$dbo = JFactory::getDBO();
		
		$q = "UPDATE `#__vikchannelmanager_config` SET `setting`='".$args['level']."' WHERE `param`='pro_level' LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		
		echo 'e4j.ok.'.VikChannelManager::getProLevel();
		die;
	}
	
}
?>