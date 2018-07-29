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

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');

class VikChannelManagerController extends JControllerLegacy {
	/**
	 * display task
	 *
	 * @return void
	 */
	function display($cachable = false, $urlparams = false) {
		// set default view if not set
		
		$api_key = VikChannelManager::getApiKey(true);
		
		if( !empty($api_key) ) {
		
			VCM::printMenu();
			
			JRequest::setVar('view', JRequest::getCmd('view', 'dashboard'));
			
		} else {
			JRequest::setVar('view', JRequest::getCmd('view', 'wizard'));
		}

		// call parent behavior
		parent::display();

		VCM::printFooter();
	}
	
	// ITEMS
	
	function config() {
		VCM::printMenu();
	
		JRequest::setVar('view', JRequest::getCmd('view', 'config'));
	
		parent::display();
		
		VCM::printFooter();
	}
	
	function oversight() {
		VCM::printMenu();
	
		JRequest::setVar('view', JRequest::getCmd('view', 'oversight'));
	
		parent::display();
		
		VCM::printFooter();
	}

	function diagnostic() {
		VCM::printMenu();
	
		JRequest::setVar('view', JRequest::getCmd('view', 'diagnostic'));
	
		parent::display();
		
		VCM::printFooter();
	}
	
	function ordervbfromsid() {
		$sid = JRequest::getVar('sid', '');
		$id = 0;
		
		$dbo = JFactory::getDBO();
		$q = "SELECT `id` FROM `#__vikbooking_orders` WHERE `confirmnumber`=".$dbo->quote($sid)." LIMIT 1";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if( $dbo->getNumRows() > 0 ) {
			$id = $dbo->loadResult();
		}
		
		$mainframe = JFactory::getApplication();
		$mainframe->redirect('index.php?option=com_vikbooking&task=editorder&cid[]='.$id.'&tmpl=component');
	}
	
	function notification() {
		JRequest::setVar('view', JRequest::getCmd('view', 'notification'));
	
		parent::display();
	}
	
	function resend_arq_confirm() {
		VCM::printMenu();
			
		JRequest::setVar('view', JRequest::getCmd('view', 'resendarqconfirm'));
	
		parent::display();
		
		VCM::printFooter();
	}
	
	function exec_par_products() {
		JRequest::setVar('view', JRequest::getCmd('view', 'execparproducts'));
	
		parent::display();
		
		exit;
	}
	
	function exec_rar_rq() {
		JRequest::setVar('view', JRequest::getCmd('view', 'execrarrq'));
	
		parent::display();
	
		exit;
	}

	function execpcid() {
		JRequest::setVar('view', JRequest::getCmd('view', 'execpcid'));
	
		parent::display();
	}

	function exec_acmp_rq() {
		$response = 'e4j.error.Generic';

		$session = JFactory::getSession();
		$cookie = JFactory::getApplication()->input->cookie;
		$dbo = JFactory::getDBO();

		$acmp_debug = false;
		if(isset($_REQUEST['e4j_debug'])) {
			if(intval($_REQUEST['e4j_debug']) == 1) {
				$acmp_debug = true;
			}
		}
		
		if(!function_exists('curl_init')) {
			echo 'e4j.error.'.VikChannelManager::getErrorFromMap('e4j.error.Curl');
			exit;
		}
		$config = VikChannelManager::loadConfiguration();
		$validate = array('apikey');
		foreach($validate as $v) {
			if( empty($config[$v]) ) {
				echo 'e4j.error.'.VikChannelManager::getErrorFromMap('e4j.error.Settings');
				exit;
			}
		}

		$fromdate = JRequest::getString('from', '', 'request');
		if(empty($fromdate)) {
			echo 'e4j.error.'.VikChannelManager::getErrorFromMap('e4j.error');
			exit;
		}
		$limitts = strtotime(date('Y-m-d'));
		$fromts = strtotime($fromdate);
		$fromdate = $fromts < $limitts ? date('Y-m-d') : $fromdate;

		$from_info = getdate(strtotime($fromdate));
		$tots = mktime(0, 0, 0, ($from_info['mon'] + 1), $from_info['mday'], $from_info['year']);
		$todate = date('Y-m-d', $tots);

		$rooms_xref = array();

		$q = "SELECT `r`.*, `c`.`name` AS `chname`, `c`.`uniquekey`, `b`.`name` AS `roomname` 
			FROM `#__vikchannelmanager_roomsxref` AS `r`, `#__vikchannelmanager_channel` AS `c`, `#__vikbooking_rooms` AS `b` 
			WHERE `b`.`id`=`r`.`idroomvb` AND `r`.`idchannel`=`c`.`uniquekey` AND `c`.`av_enabled`=1 GROUP BY `r`.`idroomvb`, `r`.`idchannel` ORDER BY `c`.`uniquekey` ASC;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if( $dbo->getNumRows() > 0 ) {
			$rooms_xref = $dbo->loadAssocList();
		}
		if(!(count($rooms_xref) > 0)) {
			echo 'e4j.error.No Relations found with channels supporting this request type, synchronize the rooms first';
			exit;
		}

		//check old session value
		$skip_call = false;
		$sess_acmp = $session->get('vcmExecAcmpRs', '');
		if (!empty($sess_acmp) && @is_array($sess_acmp)) {
			if ($fromdate == $sess_acmp['fromdate'] && @is_array($sess_acmp['acmp']) && @count($sess_acmp['acmp']) > 0) {
				$skip_call = true;
			}
		}
		//

		$channel_rooms = array();
		$channel_names_map = array();
		$ota_rooms_vbo_map = array();
		foreach ($rooms_xref as $xref) {
			$channel_names_map[$xref['uniquekey']] = $xref['channel'];
			$ota_rooms_vbo_map[$xref['idroomota']] = $xref['idroomvb'];
			$rateplanid = '0';
			if(((int)$xref['uniquekey'] == (int)VikChannelManagerConfig::AGODA || (int)$xref['uniquekey'] == (int)VikChannelManagerConfig::YCS50) && !empty($xref['otapricing'])) {
				$ota_pricing = json_decode($xref['otapricing'], true);
				if(count($ota_pricing) > 0 && array_key_exists('RatePlan', $ota_pricing)) {
					foreach ($ota_pricing['RatePlan'] as $rp_id => $rp_val) {
						$rateplanid = $rp_id;
						break;
					}
				}
			}
			$prop_params = array();
			if(!empty($xref['prop_params'])) {
				$prop_params = json_decode($xref['prop_params'], true);
			}
			$channel_rooms[$xref['uniquekey']][] = array('roomid' => $xref['idroomota'], 'rateplanid' => $rateplanid, 'vbroomid' => $xref['idroomvb'], 'prop_params' => $prop_params);
		}

		if(!$skip_call) {
			$prev_acmp = array();
			$cookie_acmp = $cookie->get('vcmAcmpData', '', 'string');
			if(!empty($cookie_acmp)) {
				$prev_acmp = json_decode($cookie_acmp, true);
				if(is_array($prev_acmp) && array_key_exists('a', $prev_acmp) && array_key_exists('t', $prev_acmp)) {
					$elapsed = $prev_acmp['t'] - time();
					if($elapsed >= 0 && $elapsed < 3600 && $prev_acmp['a'] >= (VikChannelManager::getProLevel() * 4)) {
						echo 'e4j.error.'.VikChannelManager::getErrorFromMap('e4j.error.Channels.ACMP_Busy:'.ceil(($elapsed / 60)).';;'.(!empty($sess_acmp['fromdate']) ? $sess_acmp['fromdate'] : '-------'));
						exit;
					}
				}
			}

			$e4jc_url = "https://e4jconnect.com/channelmanager/?r=acmp&c=channels";

			$xmlRQ = '<?xml version="1.0" encoding="UTF-8"?>
<!-- ACMP Request e4jConnect.com - VikChannelManager - VikBooking -->
<AvailCompareRQ xmlns="http://www.e4jconnect.com/avail/acmprq">
	<Notify client="'.JURI::root().'"/>
	<Api key="'.$config['apikey'].'"/>
	<AvailCompare from="'.$fromdate.'" to="'.$todate.'">'."\n";
			foreach ($channel_rooms as $idchannel => $rooms) {
				$xmlRQ .= '<Channel id="'.$idchannel.'">'."\n";
				foreach ($rooms as $ch_room) {
					$xmlRQ .= '<RoomType roomid="'.$ch_room['roomid'].'" rateplanid="'.$ch_room['rateplanid'].'" vbroomid="'.$ch_room['vbroomid'].'"'.(array_key_exists('hotelid', $ch_room['prop_params']) ? ' hotelid="'.$ch_room['prop_params']['hotelid'].'"' : '').'/>'."\n";
				}
				$xmlRQ .= '</Channel>'."\n";
			}
			$xmlRQ .= '</AvailCompare>
</AvailCompareRQ>';
			
			$e4jC = new E4jConnectRequest($e4jc_url);
			$e4jC->setPostFields($xmlRQ);
			$rs = $e4jC->exec();
			if($e4jC->getErrorNo()) {
				echo 'e4j.error.'.VikChannelManager::getErrorFromMap('e4j.error.Curl:Error #'.$e4jC->getErrorNo().' '.@curl_error($e4jC->getCurlHeader()));
				exit;
			}
			if(substr($rs, 0, 9) == 'e4j.error') {
				echo 'e4j.error.'.VikChannelManager::getErrorFromMap($rs);
				exit;
			}
			
			$jsondata = json_decode($rs, true);
			$json_err = false;
			if(function_exists('json_last_error')) {
				$json_err = (bool)(json_last_error() !== JSON_ERROR_NONE);
			}
			if($jsondata === null || $json_err || !(@count($jsondata) > 0)) {
				echo 'e4j.error.Bad Response, please report to e4jConnect ('.date('c').')';
				exit;
			}

			//Update session values and cookie
			$sess_acmp = array('fromdate' => $fromdate, 'ts' => time(), 'acmp' => $jsondata);
			$session->set('vcmExecAcmpRs', $sess_acmp);
			if(is_array($prev_acmp) && array_key_exists('a', $prev_acmp) && array_key_exists('t', $prev_acmp)) {
				$prev_acmp['a'] += 1;
				$cookie->set( 'vcmAcmpData', json_encode($prev_acmp), $prev_acmp['t'], '/' );
			}else {
				$cexp = (time() + 3600);
				$cookie->set( 'vcmAcmpData', json_encode(array('a' => 1, 't' => $cexp)), $cexp, '/' );
			}
			//

		}else {
			$jsondata = $sess_acmp['acmp'];
		}
		
		$response = array();
		foreach ($jsondata as $e4jc_channel_id => $ota_rooms_avail) {
			$channel_name = $channel_names_map[$e4jc_channel_id];
			$channel_name = ucwords($channel_name);
			//check if channel returned an error
			if(!is_array($ota_rooms_avail)) {
				if(substr($ota_rooms_avail, 0, 9) == 'e4j.error') {
					if(!array_key_exists('errors', $response)) {
						$response['errors'] = $channel_name.': '.VikChannelManager::getErrorFromMap($ota_rooms_avail);
					}else {
						$response['errors'] .= "\n".$channel_name.': '.VikChannelManager::getErrorFromMap($ota_rooms_avail);
					}
				}
				continue;
			}
			//
			foreach ($ota_rooms_avail as $ota_room_id => $avail) {
				$vbo_room_key = $ota_rooms_vbo_map[$ota_room_id];
				if(empty($vbo_room_key) || empty($channel_name)) {
					continue;
				}
				//check if channel returned an error
				if(!is_array($avail)) {
					if(substr($avail, 0, 9) == 'e4j.error') {
						if(!array_key_exists('errors', $response)) {
							$response['errors'] = $channel_name.': '.VikChannelManager::getErrorFromMap($avail);
						}else {
							$response['errors'] .= "\n".$channel_name.': '.VikChannelManager::getErrorFromMap($avail);
						}
						continue;
					}
				}
				//
				if(!array_key_exists($vbo_room_key, $response)) {
					$response[$vbo_room_key] = array();
				}
				if(!array_key_exists($channel_name, $response[$vbo_room_key])) {
					$response[$vbo_room_key][$channel_name] = array();
				}
				$response[$vbo_room_key][$channel_name] = $avail;
			}
		}

		if(array_key_exists('errors', $response) && !(count($response) > 1)) {
			//only errors from e4jConnect
			$response = 'e4j.error.'.$response['errors'];
		}else {
			//no errors or maybe just for some channels
			$response = json_encode($response);
		}

		if($acmp_debug === true) {
			$response = '<pre>'."Plain Request:\n".htmlentities($xmlRQ)."\n\nArray Response:\n".print_r($jsondata, true)."\n\nWorked Array for JS:\n".print_r($response, true).'</pre>';
		}
		
		echo $response;	
		exit;
	}
	
	function sendrar() {
		$dbo = JFactory::getDBO();
		$mainframe = JFactory::getApplication();
		$session = JFactory::getSession();
		$cookie = JFactory::getApplication()->input->cookie;
		$sess_rar = $session->get('vcmExecRarRs', '');
		$channel = VikChannelManager::getActiveModule(true);
		$channel['params'] = json_decode($channel['params'], true);
		$config = VikChannelManager::loadConfiguration();
		$validate = array('apikey');
		foreach($validate as $v) {
			if( empty($config[$v]) ) {
				JError::raiseWarning('', VikChannelManager::getErrorFromMap('e4j.error.Settings'));
				$mainframe->redirect('index.php?option=com_vikchannelmanager&task=roomsrar');
				exit;
			}
		}

		$ota_rooms = array();
		$prop_map = array();
		$q = "SELECT `vbr`.`id`,`vbr`.`name`,`vbr`.`img`,`vbr`.`units`,`vbr`.`smalldesc`,`vcmr`.`idroomvb`,`vcmr`.`idroomota`,`vcmr`.`channel`,`vcmr`.`otaroomname`,`vcmr`.`otapricing`,`vcmr`.`prop_params` FROM `#__vikbooking_rooms` AS `vbr` LEFT JOIN `#__vikchannelmanager_roomsxref` `vcmr` ON `vbr`.`id`=`vcmr`.`idroomvb` WHERE `vcmr`.`idchannel`=".$channel['uniquekey']." ORDER BY `vbr`.`name` ASC;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if( $dbo->getNumRows() > 0 ) {
			$vbrooms = $dbo->loadAssocList();
			foreach ($vbrooms as $rxref) {
				$ota_rooms[$rxref['idroomota']][] = $rxref;
				$prop_map[$rxref['idroomota']] = !empty($rxref['prop_params']) ? json_decode($rxref['prop_params'], true) : array();
			}
		}else {
			JError::raiseWarning('', VikChannelManager::getErrorFromMap('e4j.error.No Rooms Relations found, unable to update the rates on the OTA'));
			$mainframe->redirect('index.php?option=com_vikchannelmanager&task=roomsrar');
			exit;
		}

		$los_sent = false;

		$e4jc_url = "https://e4jconnect.com/channelmanager/?r=rar&c=".$channel['name'];
		$rar_updates = array();

		if(is_array($sess_rar) && count($sess_rar) > 0) {
			$ids = JRequest::getVar('cid', array(0));
			$currency = JRequest::getString('currency');
			$dates = array();
			foreach ($ids as $date) {
				if(!empty($date)) $dates[] = $date;
			}
			if (count($dates) > 0) {
				$avail_rates = array();
				foreach ($sess_rar['rars']['AvailRate'] as $day => $rooms) {
					if (in_array($day, $dates)) {
						$avail_rates[$day] = $rooms;
					}
				}
				//Copy Inventory on some other dates
				$copy_inventory = array();
				$copy_inventory_ibe = array();
				$copy_requests = JRequest::getVar('copyinventory', array());
				$copy_requests_where = JRequest::getVar('copyinventorywhere', array());
				if (count($copy_requests) > 0) {
					foreach ($copy_requests as $crk => $copy_request) {
						if(!empty($copy_request)) {
							$copy_parts = explode(",", $copy_request);
							if(count($copy_parts) == 2 && strlen($copy_parts[0]) == 10 && strlen($copy_parts[1]) == 10) {
								$copy_inventory[$copy_parts[0]] = $copy_parts[1];
								//TODO: the function below should copy the Rates (hardest for the RatePlans/Types of Price), the Inventory (easiest) and the Restrictions from VikBooking but it has to be implemented.
								$copy_inventory_ibe[$copy_parts[0]] = empty($copy_requests_where[$crk]) || !in_array($copy_requests_where[$crk], array('ota', 'ibe')) ? 'ota' : $copy_requests_where[$crk];
							}
						}
					}
				}
				//
				if (count($avail_rates) > 0) {
					unset($sess_rar['rars']['AvailRate']);
					$xmlRQ = '<?xml version="1.0" encoding="UTF-8"?>
<!-- RAR Request e4jConnect.com - VikChannelManager - VikBooking -->
<RarUpdateRQ xmlns="http://www.e4jconnect.com/avail/rarrq">
	<Notify client="'.JURI::root().'"/>
	<Api key="'.$config['apikey'].'"/>
	<Currency name="'.$currency.'"/>'."\n";
					$updated_days = array();
					foreach ($avail_rates as $day => $rooms) {
						if(in_array($day, $updated_days)) {
							continue;
						}
						$to_day = $day;
						$rar_updates[$day] = array();
						$rar_updates_copy = array();
						if(array_key_exists($day, $copy_inventory)) {
							//Copy Inventory on the consecutive dates
							$day_start = strtotime($day);
							$day_end = strtotime($copy_inventory[$day]);
							if($day_start < $day_end) {
								$to_day = $copy_inventory[$day];
								$is_dst = (bool)date('I', $day_start);
								while ($day_start < $day_end) {
									$day_start += 86400;
									//check if dst has changed
									$now_dst = (bool)date('I', $day_start);
									if($is_dst !== $now_dst) {
										if($now_dst === true) {
											$day_start -= 3600;
										}else {
											$day_start += 3600;
										}
										$is_dst = $now_dst;
									}
									array_push($updated_days, date('Y-m-d', $day_start));
									$rar_updates_copy[] = date('Y-m-d', $day_start);
								}
							}
						}
						$xmlRQ .= '<RarUpdate from="'.$day.'" to="'.$to_day.'">'."\n";
						foreach ($rooms as $kro => $room) {
							$rar_updates[$day][$room['id']] = $room;
							$ota_rate_plan = !empty($ota_rooms[$room['id']][key($ota_rooms[$room['id']])]['otapricing']) ? json_decode($ota_rooms[$room['id']][key($ota_rooms[$room['id']])]['otapricing'], true) : array();
							//Expedia RatePlans: when flexible products, only the parent or the derived RatePlan can be updated, not both
							$parent_rate_plans = array();
							$derived_rate_plans = array();
							$derived_rate_parents = array();
							if(array_key_exists('RatePlan', $ota_rate_plan) && $channel['uniquekey'] == VikChannelManagerConfig::EXPEDIA) {
								foreach ($ota_rate_plan['RatePlan'] as $rpkey => $rpval) {
									if(stripos($rpval['distributionModel'], 'expediacollect') !== false && (stripos($rpval['rateAcquisitionType'], 'derived') !== false || stripos($rpval['rateAcquisitionType'], 'netrate') !== false)) {
										if(count($parent_rate_plans) > 0) {
											foreach ($parent_rate_plans as $parent_rate_plan) {
												if(strpos((string)$parent_rate_plan, (string)$rpkey) !== false) {
													$derived_rate_plans[$parent_rate_plan][] = (string)$rpkey;
													$derived_rate_parents[] = (string)$rpkey;
													break;
												}
											}
										}
									}else {
										$parent_rate_plans[] = (string)$rpkey;
									}
								}
							}
							//
							$room_set_status = JRequest::getString('roomstatus_'.$day.'_'.$room['id'], '', 'request');
							$room_status = strlen($room_set_status) == 0 ? $room['closed'] : (intval($room_set_status) == 1 ? 'false' : 'true');
							$xmlRQ .= '<RoomType id="'.$room['id'].'" closed="'.$room_status.'"'.(array_key_exists($room['id'], $prop_map) && array_key_exists('hotelid', $prop_map[$room['id']]) ? ' hotelid="'.$prop_map[$room['id']]['hotelid'].'"' : '').'>'."\n";
							//RatePlan
							if (array_key_exists('RatePlan', $room)) {
								$set_rates = array();
								$restrictions_data = array();
								$skip_rateplans = array();
								$skip_restrictions = array();
								//Start - Expedia: Prevent Derived Rate Plans to be updated when Parent Rate Plans are set for Update
								if($channel['uniquekey'] == VikChannelManagerConfig::EXPEDIA) {
									foreach ($room['RatePlan'] as $rateplan) {
										$rateplan_type = JRequest::getString('rateplantype_'.$day.'_'.$room['id'].'_'.$rateplan['id'], '', 'request');
										if (array_key_exists($rateplan_type, $rateplan['Rate'])) {
											foreach ($rateplan['Rate'][$rateplan_type] as $kr => $rate) {
												$kr = is_numeric($kr) ? $kr : '0';
												$rate_cost = JRequest::getString('rateplan_'.$day.'_'.$room['id'].'_'.$rateplan['id'].'_'.$kr, '', 'request');
												if(strlen($rate_cost)) {
													$set_rates[] = (string)$rateplan['id'];
													break;
												}
											}
										}else {
											$rate_cost = JRequest::getString('rateplan_'.$day.'_'.$room['id'].'_'.$rateplan['id'], '', 'request');
											if(strlen($rate_cost)) {
												$set_rates[] = (string)$rateplan['id'];
											}
										}
										if (count($rateplan['Restrictions']) > 0) {
											$r_minlos = JRequest::getInt('restrmin_'.$room['id'].'_'.$day.'_'.$rateplan['id']);
											$r_maxlos = JRequest::getInt('restrmax_'.$room['id'].'_'.$day.'_'.$rateplan['id']);
											if(!empty($r_minlos) || !empty($r_maxlos)) {
												$restrictions_data[] = (string)$rateplan['id'];
											}
										}
									}
									if(count($set_rates) > 0) {
										//Check if there are some derived rate plans that should not be updated
										foreach ($set_rates as $rpid => $set_rate) {
											if(in_array($set_rate, $parent_rate_plans)) {
												//Parent Rate Plan
												if(array_key_exists($set_rate, $derived_rate_plans)) {
													foreach ($derived_rate_plans[$set_rate] as $drpk => $derived_rp) {
														if(in_array($derived_rp, $set_rates)) {
															$skip_rateplans[] = $derived_rp;
														}
														if(in_array($derived_rp, $restrictions_data)) {
															$skip_restrictions[] = $derived_rp;
														}
													}
												}
											}
										}
									}
									reset($room['RatePlan']);
								}
								//End - Expedia: Prevent Derived Rate Plans to be updated when Parent Rate Plans are set for Update
								foreach ($room['RatePlan'] as $krp => $rateplan) {
									$rar_updates[$day][$room['id']]['RatePlan'][$rateplan['id']] = $rateplan;
									unset($rar_updates[$day][$room['id']]['RatePlan'][$krp]);
									if($channel['uniquekey'] == VikChannelManagerConfig::EXPEDIA) {
										if(in_array((string)$rateplan['id'], $skip_rateplans) && in_array((string)$rateplan['id'], $skip_restrictions)) {
											continue;
										}elseif(in_array((string)$rateplan['id'], $derived_rate_parents)) {
											//Derived Rate Plan
											foreach ($derived_rate_plans as $parent_id => $deriveds) {
												if(in_array((string)$rateplan['id'], $deriveds)) {
													if(in_array((string)$parent_id, $set_rates)) {
														//Parent Rate was updated so this rate plan should not even be closed or opened
														continue 2;
													}
												}
											}
										}
									}
									$rateplan_set_status = JRequest::getString(($channel['uniquekey'] == VikChannelManagerConfig::BOOKING ? 'rateplanstatus'.$day.$room['id'].$rateplan['id'] : 'rateplanstatus'.$day.$rateplan['id']), '', 'request');
									$rateplan['closed'] = empty($rateplan['closed']) ? 'false' : $rateplan['closed'];
									$rateplan_status = strlen($rateplan_set_status) == 0 ? $rateplan['closed'] : (intval($rateplan_set_status) == 1 ? 'false' : 'true');
									$rateplan_type = JRequest::getString('rateplantype_'.$day.'_'.$room['id'].'_'.$rateplan['id'], '', 'request');
									$xmlRQ .= '<RatePlan id="'.$rateplan['id'].'" closed="'.$rateplan_status.'">'."\n";
									//Rate
									if($channel['uniquekey'] == VikChannelManagerConfig::EXPEDIA) {
										//Expedia
										if (array_key_exists($rateplan_type, $rateplan['Rate'])) {
											if(!in_array((string)$rateplan['id'], $skip_rateplans)) {
												$last_los = 0;
												foreach ($rateplan['Rate'][$rateplan_type] as $kr => $rate) {
													$kr = is_numeric($kr) ? $kr : '0';
													$rate_cost = JRequest::getString('rateplan_'.$day.'_'.$room['id'].'_'.$rateplan['id'].'_'.$kr, '', 'request');
													if(strlen($rate_cost)) {
														$xmlRQ .= '<Rate'.(array_key_exists('lengthOfStay', $rate) ? ' lengthOfStay="'.$rate['lengthOfStay'].'"' : '').'>'."\n";
														$xmlRQ .= '<'.$rateplan_type.' rate="'.floatval($rate_cost).'"'.(array_key_exists('occupancy', $rate) ? ' occupancy="'.$rate['occupancy'].'"' : '').'/>'."\n";
														$xmlRQ .= '</Rate>'."\n";
													}
													$last_los = (int)$kr;
												}
												//Costs per night added manually
												$addrateplans = JRequest::getInt('addrateplans_'.$day.'_'.$room['id'].'_'.$rateplan['id'], '', 'request');
												if($addrateplans > 0 && $last_los < $addrateplans) {
													for($i = ++$last_los; $i < $addrateplans; $i++) {
														$rate_cost = JRequest::getString('rateplan_'.$day.'_'.$room['id'].'_'.$rateplan['id'].'_'.$i, '', 'request');
														if(strlen($rate_cost)) {
															$xmlRQ .= '<Rate lengthOfStay="'.($i + 1).'">'."\n";
															$xmlRQ .= '<'.$rateplan_type.' rate="'.floatval($rate_cost).'"/>'."\n";
															$xmlRQ .= '</Rate>'."\n";
														}
													}
												}
												//
											}
										}else {
											$rate_cost = JRequest::getString('rateplan_'.$day.'_'.$room['id'].'_'.$rateplan['id'], '', 'request');
											if(strlen($rate_cost) && !in_array((string)$rateplan['id'], $skip_rateplans)) {
												$xmlRQ .= '<Rate'.(array_key_exists('lengthOfStay', $rateplan['Rate']) ? ' lengthOfStay="'.$rateplan['Rate']['lengthOfStay'].'"' : '').'>'."\n";
												$xmlRQ .= '<'.$rateplan_type.' rate="'.floatval($rate_cost).'"'.(array_key_exists('occupancy', $rateplan['Rate']) ? ' occupancy="'.$rateplan['Rate']['occupancy'].'"' : '').'/>'."\n";
												$xmlRQ .= '</Rate>'."\n";
											}
										}
									}elseif($channel['uniquekey'] == VikChannelManagerConfig::AGODA || $channel['uniquekey'] == VikChannelManagerConfig::YCS50) {
										//Agoda
										foreach ($rateplan['Rate'] as $rateplan_type => $rateplan_rate) {
											if(!in_array($rateplan_type, array('SingleRate', 'DoubleRate', 'FullRate', 'ExtraPerson', 'ExtraAdult', 'ExtraChild', 'ExtraBed'))) {
												continue;
											}
											$rate_cost = JRequest::getString('rateplan_'.$day.'_'.$room['id'].'_'.$rateplan['id'].'_'.$rateplan_type, '', 'request');
											if(strlen($rate_cost) > 0) {
												$xmlRQ .= '<Rate>'."\n";
												$xmlRQ .= '<'.$rateplan_type.' rate="'.floatval($rate_cost).'"/>'."\n";
												$xmlRQ .= '</Rate>'."\n";
											}
										}
									}elseif($channel['uniquekey'] == VikChannelManagerConfig::BOOKING) {
										//Booking.com
										foreach ($rateplan['Rate'] as $rateplan_type => $rateplan_rate) {
											if(!in_array($rateplan_type, array('price', 'price1'))) {
												continue;
											}
											$rate_cost = JRequest::getString('rateplan_'.$day.'_'.$room['id'].'_'.$rateplan['id'].'_'.$rateplan_type, '', 'request');
											if(strlen($rate_cost) > 0) {
												//take 1 as usage if it is price1
												$usage_attr = '';
												if(is_numeric(substr($rateplan_type, -1))) {
													$usage_attr = substr($rateplan_type, -1);
													if(!(intval($usage_attr) > 0)) {
														$usage_attr = '';
													}
												}
												//
												$rar_updates[$day][$room['id']]['RatePlan'][$rateplan['id']]['Rate'][$rateplan_type] = $rate_cost;
												$xmlRQ .= '<Rate>'."\n";
												$xmlRQ .= '<PerDay rate="'.floatval($rate_cost).'"'.(!empty($usage_attr) ? ' usage="'.$usage_attr.'"' : '').'/>'."\n";
												$xmlRQ .= '</Rate>'."\n";
											}
										}
										//Rates based on LOS and Occupancy
										$addrateplans = JRequest::getInt('addrateplans_'.$day.'_'.$room['id'].'_'.$rateplan['id'], '', 'request');
										$addrateplansocc = JRequest::getInt('addrateplansocc_'.$day.'_'.$room['id'].'_'.$rateplan['id'], '', 'request');
										if($addrateplans > 0 && $addrateplansocc > 0) {
											$rar_updates[$day][$room['id']]['RatePlan'][$rateplan['id']]['RatesLOS'] = array();
											for($x = 1; $x <= $addrateplansocc; $x++) {
												$upd_occ = array();
												for($i = 0; $i < $addrateplans; $i++) {
													$rate_cost = JRequest::getString('rateplan_'.$day.'_'.$room['id'].'_'.$rateplan['id'].'_'.$x.'_'.$i, '', 'request');
													if(strlen($rate_cost)) {
														$los_sent = true;
														$xmlRQ .= '<Rate lengthOfStay="'.($i + 1).'">'."\n";
														$xmlRQ .= '<PerDay rate="'.floatval($rate_cost).'" usage="'.$x.'"/>'."\n";
														$xmlRQ .= '</Rate>'."\n";
														$upd_occ[($i + 1)] = floatval($rate_cost);
													}
												}
												$rar_updates[$day][$room['id']]['RatePlan'][$rateplan['id']]['RatesLOS'][$x] = $upd_occ;
											}
										}
										//
									}
									//
									//Restrictions
									if (count($rateplan['Restrictions']) > 0) {
										$r_minlos = JRequest::getInt('restrmin_'.$room['id'].'_'.$day.'_'.$rateplan['id']);
										$r_maxlos = JRequest::getInt('restrmax_'.$room['id'].'_'.$day.'_'.$rateplan['id']);
										if(!empty($r_minlos) || !empty($r_maxlos) || $channel['uniquekey'] == VikChannelManagerConfig::BOOKING) {
											$lim_min_los = 28;
											$lim_down_min_los = $channel['uniquekey'] == VikChannelManagerConfig::BOOKING ? 0 : 1;
											$lim_max_los = $channel['uniquekey'] == VikChannelManagerConfig::AGODA ? 99 : ($channel['uniquekey'] == VikChannelManagerConfig::BOOKING ? 31 : 28);
											$lim_down_max_los = $channel['uniquekey'] == VikChannelManagerConfig::AGODA || $channel['uniquekey'] == VikChannelManagerConfig::BOOKING ? 0 : 1;
											$r_minlos = $r_minlos < $lim_down_min_los ? $lim_down_min_los : ($r_minlos > $lim_min_los ? $lim_min_los : $r_minlos);
											$r_maxlos = $r_maxlos < $lim_down_max_los ? $lim_down_max_los : ($r_maxlos > $lim_max_los ? $lim_max_los : $r_maxlos);
											$r_close_in = JRequest::getString(($channel['uniquekey'] == VikChannelManagerConfig::BOOKING ? 'restrplanarrival'.$day.$room['id'].$rateplan['id'] : 'restrplanarrival'.$day.$rateplan['id']), '', 'request');
											$r_close_in = strlen($r_close_in) == 0 ? $rateplan['Restrictions']['closedToArrival'] : (intval($r_close_in) == 1 ? 'true' : 'false');
											$r_close_out = JRequest::getString(($channel['uniquekey'] == VikChannelManagerConfig::BOOKING ? 'restrplandeparture'.$day.$room['id'].$rateplan['id'] : 'restrplandeparture'.$day.$rateplan['id']), '', 'request');
											$r_close_out = strlen($r_close_out) == 0 ? $rateplan['Restrictions']['closedToDeparture'] : (intval($r_close_out) == 1 ? 'true' : 'false');
											if($channel['uniquekey'] == VikChannelManagerConfig::EXPEDIA) {
												if(!in_array((string)$rateplan['id'], $skip_restrictions)) {
													$xmlRQ .= '<Restrictions minLOS="'.$r_minlos.'" maxLOS="'.$r_maxlos.'" closedToArrival="'.$r_close_in.'" closedToDeparture="'.$r_close_out.'"/>'."\n";
												}
											}elseif($channel['uniquekey'] == VikChannelManagerConfig::AGODA) {
												$r_breakfast = JRequest::getString('restrplanbreakfast'.$day.$rateplan['id'], '', 'request');
												$r_breakfast = strlen($r_breakfast) == 0 ? '' : (intval($r_breakfast) == 1 ? 'true' : 'false');
												$r_promoblackout = JRequest::getString('restrplanpromoblackout'.$day.$rateplan['id'], '', 'request');
												$r_promoblackout = strlen($r_promoblackout) == 0 ? '' : (intval($r_promoblackout) == 1 ? 'true' : 'false');
												$xmlRQ .= '<Restrictions minLOS="'.$r_minlos.'" maxLOS="'.$r_maxlos.'" closedToArrival="'.$r_close_in.'" closedToDeparture="'.$r_close_out.'"'.(strlen($r_breakfast) ? ' breakfastIncluded="'.$r_breakfast.'"' : '').(strlen($r_promoblackout) ? ' promotionBlackout="'.$r_promoblackout.'"' : '').'/>'."\n";
											}elseif($channel['uniquekey'] == VikChannelManagerConfig::YCS50) {
												$xmlRQ .= '<Restrictions minLOS="'.$r_minlos.'" maxLOS="'.$r_maxlos.'" closedToArrival="'.$r_close_in.'" closedToDeparture="'.$r_close_out.'"/>'."\n";
											}elseif($channel['uniquekey'] == VikChannelManagerConfig::BOOKING) {
												$rar_updates[$day][$room['id']]['RatePlan'][$rateplan['id']]['Restrictions']['minimumstay'] = $r_minlos;
												$rar_updates[$day][$room['id']]['RatePlan'][$rateplan['id']]['Restrictions']['maximumstay'] = $r_maxlos;
												$rar_updates[$day][$room['id']]['RatePlan'][$rateplan['id']]['Restrictions']['closedonarrival'] = $r_close_in;
												$rar_updates[$day][$room['id']]['RatePlan'][$rateplan['id']]['Restrictions']['closedondeparture'] = $r_close_out;
												$xmlRQ .= '<Restrictions minLOS="'.$r_minlos.'" maxLOS="'.$r_maxlos.'" closedToArrival="'.$r_close_in.'" closedToDeparture="'.$r_close_out.'"/>'."\n";
											}
										}
									}
									//
									$xmlRQ .= '</RatePlan>'."\n";
								}
							}
							//
							//Inventory
							$units_av = JRequest::getString('inv_'.$day.'_'.$room['id'], '', 'request');
							$units_type = JRequest::getString('invtype_'.$day.'_'.$room['id'], 'totalInventoryAvailable', 'request');
							if(strlen($units_av) > 0) {
								$units_av = intval($units_av);
								$units_av = $units_av < 0 ? 0 : $units_av;
								$xmlRQ .= '<Inventory totalInventoryAvailable="'.($units_type == 'totalInventoryAvailable' ? $units_av : '').'" flexibleAllocation="'.($units_type == 'flexibleAllocation' ? $units_av : '').'"/>'."\n";
							}
							//
							$xmlRQ .= '</RoomType>'."\n";
						}
						$xmlRQ .= '</RarUpdate>'."\n";
						$updated_days[] = $day;
						if(count($rar_updates_copy)) {
							foreach ($rar_updates_copy as $copyday) {
								$rar_updates[$copyday] = $rar_updates[$day];
							}
						}
					}
					$xmlRQ .= '</RarUpdateRQ>';
					
					//Debug:
					$rar_debug = false;
					if(isset($_REQUEST['e4j_debug'])) {
						if(intval($_REQUEST['e4j_debug']) == 1) {
							$rar_debug = true;
						}
					}
					if($rar_debug === true) {
						echo '<pre>'.print_r($_POST, true).'</pre><br/><br/>';
						if(class_exists('DOMDocument')) {
							$dom = new DOMDocument;
							$dom->preserveWhiteSpace = FALSE;
							$dom->loadXML($xmlRQ);
							$dom->formatOutput = TRUE;
							$xmlRQ = $dom->saveXML();
						}
						echo '<pre>'.htmlentities($xmlRQ).'</pre><br/><br/>';
						echo '<pre>'.print_r($rar_updates, true).'</pre><br/><br/>';
						die;
					}
					//
					
					$continue = true;
					$e4jC = new E4jConnectRequest($e4jc_url);
					$e4jC->setPostFields($xmlRQ);
					$e4jC->slaveEnabled = true;
					$rs = $e4jC->exec();
					if($e4jC->getErrorNo()) {
						JError::raiseWarning('', VikChannelManager::getErrorFromMap('e4j.error.Curl:Error #'.$curlerr_no.' '.@curl_error($e4jC->getCurlHeader())));
						$continue = false;
					}
					if(substr($rs, 0, 9) == 'e4j.error') {
						JError::raiseWarning('', VikChannelManager::getErrorFromMap($rs));
						$continue = false;
					}
					if(substr($rs, 0, 11) == 'e4j.warning') {
						JError::raiseNotice('', nl2br(VikChannelManager::getErrorFromMap($rs)));
					}
					
					$response = unserialize($rs);

					$channel_prefix = ucwords(str_replace('.com', '', $channel['name']));

					if(($response === false || !is_array($response) || !array_key_exists('esit', $response) || !in_array($response['esit'], array('Error', 'Warning', 'Success'))) && $continue) {
						JError::raiseWarning('', VikChannelManager::getErrorFromMap('e4j.error.'.$channel_prefix.'.RAR:InvalidSchema'));
						$continue = false;
					}
					
					if($response['esit'] == 'Error') {
						JError::raiseWarning('', nl2br(VikChannelManager::getErrorFromMap('e4j.error.'.$channel_prefix.'.RAR:'.$response['message'])));
						$continue = false;
					}
					
					if($response['esit'] == 'Warning') {
						JError::raiseNotice('', nl2br(VikChannelManager::getErrorFromMap('e4j.warning.'.$channel_prefix.'.RAR:'.$response['message'])));
					}
					
					if($continue) {
						//unset old rar_rq data
						$sess_rar['rars'] = '';
						$session->set('vcmExecRarRs', $sess_rar);
						$session->set('vcmExecAcmpRs', '');

						$mainframe->enqueueMessage(JText::_('VCMRARRQSUCCESS').(!empty($response['message']) ? '<br/>'.$response['message'] : ''));

						if($channel['uniquekey'] == VikChannelManagerConfig::BOOKING && count($rar_updates)) {
							//store in db the updates made to the inventory, rates and availability for this date and room
							foreach ($rar_updates as $day => $rar) {
								foreach ($rar as $idroom => $room) {
									$q = "SELECT `id` FROM `#__vikchannelmanager_rar_updates` WHERE `channel`=".$dbo->quote($channel['uniquekey'])." AND `date`=".$dbo->quote($day)." AND `room_type_id`=".$dbo->quote($idroom)." LIMIT 1;";
									$dbo->setQuery($q);
									$dbo->Query($q);
									if($dbo->getNumRows() == 1) {
										$rar_record = $dbo->loadAssoc();
										$q = "UPDATE `#__vikchannelmanager_rar_updates` SET `data`=".$dbo->quote(json_encode($room)).",`last_update`=CURRENT_TIMESTAMP WHERE `id`=".$rar_record['id'].";";
										$dbo->setQuery($q);
										$dbo->Query($q);
									}else {
										$q = "INSERT INTO `#__vikchannelmanager_rar_updates` (`channel`,`date`,`room_type_id`,`data`) VALUES(".$dbo->quote($channel['uniquekey']).", ".$dbo->quote($day).", ".$dbo->quote($idroom).", ".$dbo->quote(json_encode($room)).");";
										$dbo->setQuery($q);
										$dbo->Query($q);
									}
								}
								
							}
							if($los_sent === true && $response['esit'] != 'Warning') {
								//Booking.com: rates were accepted by LOS so force the cookie for that pricing model
								$cookie->set( 'vcmAriPrModel'.$channel['uniquekey'], 'los', (time() + (86400 * 365)), '/' );
							}
						}
					}
					
				}else {
					JError::raiseWarning('', JText::_('VCMRARERRNODATES'));
				}
			}else {
				JError::raiseWarning('', JText::_('VCMRARERRNODATES'));
			}
		}else {
			JError::raiseWarning('', JText::_('VCMRARERRNOSESSION'));
		}
		
		$mainframe->redirect('index.php?option=com_vikchannelmanager&task=roomsrar');
		
	}

	function loadlosibe() {
		$dbo = JFactory::getDBO();
		$room_id = JRequest::getInt('room_id');
		$date = JRequest::getString('date');
		$date_ts = strtotime($date);
		$occupancy = JRequest::getVar('occupancy', array());
		$result = 'e4j.error.No Rates Available';
		$pricing = array();
		if(!class_exists('vikbooking')) {
			require_once(JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'helpers'.DS.'lib.vikbooking.php');
		}
		$channel = VikChannelManager::getActiveModule(true);
		$channel['settings'] = json_decode($channel['settings'], true);
		$taxincl_price_compare = false;
		$vbo_tax_included = vikbooking::ivaInclusa();
		if(@is_array($channel['settings']) && @array_key_exists('price_compare', $channel['settings'])) {
			if($channel['settings']['price_compare']['value'] == 'VCM_PRICE_COMPARE_TAX_INCL') {
				$taxincl_price_compare = true;
			}
		}

		if(!empty($room_id) && !empty($date) && !empty($date_ts) && count($occupancy)) {
			$date_ts += 7200;
			$end_date_ts = $date_ts + 86400;
			$q = "SELECT `d`.*,`p`.`name` AS `rate_name` FROM `#__vikbooking_dispcost` AS `d` LEFT JOIN `#__vikbooking_prices` `p` ON `p`.`id`=`d`.`idprice` WHERE `d`.`idroom`=".$room_id." AND `d`.`days` < 31 ORDER BY `d`.`days` ASC, `d`.`cost` ASC;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if( $dbo->getNumRows() > 0 ) {
				$rates = $dbo->loadAssocList();
				//Debug
				//$result = print_r($rates, true)."\n\n\n".print_r($occupancy, true)."\n\n\n";
				//
				$all_rate_plans = array();
				foreach ($rates as $rk => $rv) {
					$all_rate_plans[$rv['idprice']] = $rv['rate_name'];
				}
				$pricing = array('rate_plans' => $all_rate_plans, 'los' => array());
				$arr_rates = array();
				foreach( $rates as $rate ) {
					$arr_rates[$rate['idroom']][] = $rate;
				}
				$arr_rates = vikbooking::applySeasonalPrices($arr_rates, $date_ts, $end_date_ts);
				$multi_rates = 1;
				foreach ($arr_rates as $idr => $tars) {
					$multi_rates = count($tars) > $multi_rates ? count($tars) : $multi_rates;
				}
				if($multi_rates > 1) {
					for($r = 1; $r < $multi_rates; $r++) {
						$deeper_rates = array();
						$num_nights = 0;
						foreach ($arr_rates as $idr => $tars) {
							foreach ($tars as $tk => $tar) {
								if($tk == $r) {
									$deeper_rates[$idr][0] = $tar;
									$num_nights = ($tar['days'] - 1);
									break;
								}
							}
						}
						if(!count($deeper_rates) > 0) {
							continue;
						}
						$deeper_rates = vikbooking::applySeasonalPrices($deeper_rates, $date_ts, ($end_date_ts + (86400 * $num_nights)) );
						foreach ($deeper_rates as $idr => $dtars) {
							foreach ($dtars as $dtk => $dtar) {
								$arr_rates[$idr][$r] = $dtar;
							}
						}
					}
				}
				//Debug
				//$result = print_r($arr_rates[$room_id], true)."\n\n\n";
				//

				//Tax Rates
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
				//

				//charges/discounts per adults occupancy
				foreach ($occupancy as $occk => $num_adults) {
					$base_rates = $arr_rates;
					$roomnumb = $occk + 1;
					foreach ($base_rates as $r => $rates) {
						$diffusageprice = vikbooking::loadAdultsDiff($r, (int)$num_adults);
						//Occupancy Override - Special Price may be setting a charge/discount for this occupancy while default price had no occupancy pricing
						if (!is_array($diffusageprice)) {
							foreach($rates as $kpr => $vpr) {
								if(array_key_exists('occupancy_ovr', $vpr) && array_key_exists((int)$num_adults, $vpr['occupancy_ovr']) && strlen($vpr['occupancy_ovr'][(int)$num_adults]['value'])) {
									$diffusageprice = $vpr['occupancy_ovr'][(int)$num_adults];
									break;
								}
							}
							reset($rates);
						}
						//
						if (is_array($diffusageprice)) {
							foreach($rates as $kpr => $vpr) {
								if($roomnumb == 1) {
									$base_rates[$r][$kpr]['costbeforeoccupancy'] = $base_rates[$r][$kpr]['cost'];
								}
								//Occupancy Override
								if(array_key_exists('occupancy_ovr', $vpr) && array_key_exists((int)$num_adults, $vpr['occupancy_ovr']) && strlen($vpr['occupancy_ovr'][(int)$num_adults]['value'])) {
									$diffusageprice = $vpr['occupancy_ovr'][(int)$num_adults];
								}
								//
								$base_rates[$r][$kpr]['diffusage'] = $num_adults;
								if ($diffusageprice['chdisc'] == 1) {
									//charge
									if ($diffusageprice['valpcent'] == 1) {
										//fixed value
										$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $base_rates[$r][$kpr]['days'] : $diffusageprice['value'];
										$base_rates[$r][$kpr]['diffusagecost'][$roomnumb] = $aduseval;
										$base_rates[$r][$kpr]['cost'] += $aduseval;
									}else {
										//percentage value
										$aduseval = $diffusageprice['pernight'] == 1 ? round(($base_rates[$r][$kpr]['costbeforeoccupancy'] * $diffusageprice['value'] / 100) * $base_rates[$r][$kpr]['days'], 2) : round(($base_rates[$r][$kpr]['costbeforeoccupancy'] * $diffusageprice['value'] / 100), 2);
										$base_rates[$r][$kpr]['diffusagecost'][$roomnumb] = $aduseval;
										$base_rates[$r][$kpr]['cost'] += $aduseval;
									}
								}else {
									//discount
									if ($diffusageprice['valpcent'] == 1) {
										//fixed value
										$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $base_rates[$r][$kpr]['days'] : $diffusageprice['value'];
										$base_rates[$r][$kpr]['diffusagediscount'][$roomnumb] = $aduseval;
										$base_rates[$r][$kpr]['cost'] -= $aduseval;
									}else {
										//percentage value
										$aduseval = $diffusageprice['pernight'] == 1 ? round(((($base_rates[$r][$kpr]['costbeforeoccupancy'] / $base_rates[$r][$kpr]['days']) * $diffusageprice['value'] / 100) * $base_rates[$r][$kpr]['days']), 2) : round(($base_rates[$r][$kpr]['costbeforeoccupancy'] * $diffusageprice['value'] / 100), 2);
										$base_rates[$r][$kpr]['diffusagediscount'][$roomnumb] = $aduseval;
										$base_rates[$r][$kpr]['cost'] -= $aduseval;
									}
								}
							}
						}elseif($roomnumb == 1) {
							foreach($rates as $kpr => $vpr) {
								$base_rates[$r][$kpr]['costbeforeoccupancy'] = $base_rates[$r][$kpr]['cost'];
							}
						}
					}
					//Taxes included or Excluded
					if(count($tax_rates) > 0) {
						foreach ($base_rates as $r => $rates) {
							foreach ($rates as $k => $rate) {
								if (array_key_exists($rate['idprice'], $tax_rates)) {
									if($taxincl_price_compare === true) {
										if(!$vbo_tax_included) {
											$base_rates[$r][$k]['cost'] = vikbooking::sayCostPlusIva($rate['cost'], $rate['idprice']);
										}
									}else {
										if($vbo_tax_included) {
											$base_rates[$r][$k]['cost'] = vikbooking::sayCostMinusIva($rate['cost'], $rate['idprice']);
										}
									}
								}
							}
						}
					}
					//
					//Debug
					//$result .= "Occupancy $num_adults\n".print_r($base_rates[$room_id], true)."\n\n\n";
					//
					//build response array for LOS
					foreach ($all_rate_plans as $rp_id => $rp_name) {
						foreach ($base_rates[$room_id] as $rate_ind => $vpr) {
							if($vpr['idprice'] != $rp_id) {
								continue;
							}
							if(!array_key_exists($rp_id, $pricing['los'])) {
								$pricing['los'][$rp_id] = array(
									$num_adults => array(
										$vpr['days'] => round($vpr['cost'], 2)
									)
								);
							}else {
								if(!array_key_exists($num_adults, $pricing['los'][$rp_id])) {
									$pricing['los'][$rp_id][$num_adults] = array(
										$vpr['days'] => round($vpr['cost'], 2)
									);
								}else {
									$pricing['los'][$rp_id][$num_adults][$vpr['days']] = round($vpr['cost'], 2);
								}
							}
							
						}
					}
					//
				}
				//end charges/discounts per adults occupancy
				//Debug
				//$result .= "Pricing:\n\n".print_r($pricing, true)."\n\n\n";
				//
			}
		}
		if(count($pricing) > 0) {
			$result = json_encode($pricing);
			//Debug
			//$result = "Pricing:\n\n".print_r($pricing, true)."\n\n\n";
			//
		}

		echo $result;
		exit;
	}
	
	// CHANNEL VIEW - Expedia
	
	function rooms() {
		if( VikChannelManager::authorizeAction(VikChannelManagerConfig::EXPEDIA) || VikChannelManager::authorizeAction(VikChannelManagerConfig::AGODA) || VikChannelManager::authorizeAction(VikChannelManagerConfig::BOOKING) ) {
			VCM::printMenu();
		
			JRequest::setVar('view', JRequest::getCmd('view', 'rooms'));
		
			parent::display();
			
			VCM::printFooter();
		} else {
			JError::raiseWarning('', 'Authorization Denied!');
			$this->display();
		}
	}
	
	function roomsrar() {
		if( VikChannelManager::authorizeAction(VikChannelManagerConfig::EXPEDIA) || VikChannelManager::authorizeAction(VikChannelManagerConfig::AGODA) || VikChannelManager::authorizeAction(VikChannelManagerConfig::BOOKING) ) {
			VCM::printMenu();
			
			JRequest::setVar('view', JRequest::getCmd('view', 'roomsrar'));
			
			parent::display();
			
			VCM::printFooter();
		} else {
			JError::raiseWarning('', 'Authorization Denied!');
			$this->display();
		}
	}
	
	function roomsynch() {
		if( VikChannelManager::authorizeAction(VikChannelManagerConfig::EXPEDIA) || VikChannelManager::authorizeAction(VikChannelManagerConfig::AGODA) || VikChannelManager::authorizeAction(VikChannelManagerConfig::BOOKING) ) {
			VCM::printMenu();
		
			JRequest::setVar('view', JRequest::getCmd('view', 'roomsynch'));
		
			parent::display();
			
			VCM::printFooter();
		} else {
			JError::raiseWarning('', 'Authorization Denied!');
			$this->display();
		}
	}
	
	function confirmcustoma() {
		VCM::printMenu();
	
		JRequest::setVar('view', JRequest::getCmd('view', 'confirmcustoma'));
	
		parent::display();
		
		VCM::printFooter();
	}
	
	// CHANNEL VIEW - Trip Connect
	
	function hoteldetails() {	
		VCM::printMenu();
		
		JRequest::setVar('view', JRequest::getCmd('view', 'hoteldetails'));

		parent::display();
	
		VCM::printFooter();
	}
	
	function inventory() {
		if( VikChannelManager::authorizeAction(VikChannelManagerConfig::TRIP_CONNECT) ) {
			
			if( VikChannelManager::checkIntegrityHotelDetails() ) {
				VCM::printMenu();
				
				JRequest::setVar('view', JRequest::getCmd('view', 'inventory'));
		
				parent::display();
			
				VCM::printFooter();
			} else {
				JError::raiseNotice('', JText::_('VCMHOTELDETAILSNOTCOMPERR'));
				$this->hoteldetails();
			}
		} else {
			JError::raiseWarning('', 'Authorization Denied!');
			$this->display();
		}
	}
	
	function tacstatus() {
		if( VikChannelManager::authorizeAction(VikChannelManagerConfig::TRIP_CONNECT) ) {
			VCM::printMenu();
			
			JRequest::setVar('view', JRequest::getCmd('view', 'tacstatus'));
	
			parent::display();
		
			VCM::printFooter();
		} else {
			JError::raiseWarning('', 'Authorization Denied!');
			$this->display();
		}
	}
	
	function revexpress() {
		if( VikChannelManager::authorizeAction(VikChannelManagerConfig::TRIP_CONNECT) ) {
			VCM::printMenu();
			
			JRequest::setVar('view', JRequest::getCmd('view', 'revexpress'));
	
			parent::display();
		
			VCM::printFooter();
		} else {
			JError::raiseWarning('', 'Authorization Denied!');
			$this->display();
		}
	}
	
	// CHANNEL VIEW - Trivago
	
	function trinventory() {
		if( VikChannelManager::authorizeAction(VikChannelManagerConfig::TRIVAGO) ) {
			
			if( VikChannelManager::checkIntegrityHotelDetails() ) {
				VCM::printMenu();
				
				JRequest::setVar('view', JRequest::getCmd('view', 'trinventory'));
		
				parent::display();
			
				VCM::printFooter();
			} else {
				JError::raiseNotice('', JText::_('VCMHOTELDETAILSNOTCOMPERR'));
				$this->hoteldetails();
			}
		} else {
			JError::raiseWarning('', 'Authorization Denied!');
			$this->display();
		}
	}
	
	// CHANNEL VIEW - Airbnb
	
	function listings() {
		if( VikChannelManager::authorizeAction(VikChannelManagerConfig::AIRBNB) || 
			VikChannelManager::authorizeAction(VikChannelManagerConfig::FLIPKEY) || 
			VikChannelManager::authorizeAction(VikChannelManagerConfig::HOLIDAYLETTINGS) || 
			VikChannelManager::authorizeAction(VikChannelManagerConfig::WIMDU) ) {
				
			VCM::printMenu();
			
			JRequest::setVar('view', JRequest::getCmd('view', 'listings'));
	
			parent::display();
		
			VCM::printFooter();
		} else {
			JError::raiseWarning('', 'Authorization Denied!');
			$this->display();
		}
	}
	
	// MULTI-CHANNEL (EXPEDIA)
	
	function ordersvb() {
		if( VikChannelManager::authorizeAction(VikChannelManagerConfig::EXPEDIA) ) {
			VCM::printMenu();
		
			JRequest::setVar('view', JRequest::getCmd('view', 'ordersvb'));
		
			parent::display();
			
			VCM::printFooter();
		} else {
			JError::raiseWarning('', 'Authorization Denied!');
			$this->display();
		}
	}
	
	// SAVE TRIP ADVISOR HOTEL DETAILS
	
	function saveHotelDetails() {
		
		$mainframe = JFactory::getApplication();
		$dbo = JFactory::getDBO();
		
		$args = array();
		$args['name'] = JRequest::getVar('name');
		$args['street'] = JRequest::getVar('street');
		$args['city'] = JRequest::getVar('city');
		$args['zip'] = JRequest::getVar('zip');
		$args['state'] = JRequest::getVar('state');
		$args['country'] = JRequest::getVar('country');

		$args['countrycode'] = "";
		$q = "SELECT `country_2_code` FROM `#__vikbooking_countries` WHERE `country_name`=".$dbo->quote($args['country'])." LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if( $dbo->getNumRows() > 0 ) {
			$args['countrycode'] = $dbo->loadResult();
		}

		$args['latitude'] = JRequest::getVar('latitude');
		$args['longitude'] = JRequest::getVar('longitude');
		$args['description'] = JRequest::getVar('description');
		$args['amenities'] = JRequest::getVar('amenities', array());
		$args['url'] = JRequest::getVar('url');
		$args['email'] = JRequest::getVar('email');
		$args['phone'] = JRequest::getVar('phone');
		$args['fax'] = JRequest::getVar('fax');
		
		$args['amenities'] = implode(',', $args['amenities']);
		
		$not_changed = true;
		
		// Check if something has changed
		$q = "SELECT `key`,`value` FROM `#__vikchannelmanager_hotel_details`;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if( $dbo->getNumRows() > 0 ) {
			$rows = $dbo->loadAssocList();
			foreach( $rows as $r ) {
				$not_changed = $not_changed && ($r['value'] == $args[$r['key']]);
			}
		} else {
			$not_changed = false;
		}
		
		if( !$not_changed ) {
			foreach( $args as $k => $v ) {
				$q = "UPDATE `#__vikchannelmanager_hotel_details` SET `value`=".$dbo->quote($v)." WHERE `key`=".$dbo->quote($k)." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->Query($q);
			}
		}
		
		$session = JFactory::getSession();
		
		if( VikChannelManager::checkIntegrityHotelDetails() ) {
			if( !$not_changed || $session->get('hd-force-next-request', 0) ) {
				// if you are sending the request for trivago -> unset($args['countrycode']);
				if( $this->sendHotelDetails($args) ) {
					$mainframe->enqueueMessage(JText::_('VCMHOTELDETAILSUPDATED1'));
				}
			} else {
				$mainframe->enqueueMessage(JText::_('VCMHOTELDETAILSUPDATED2'));
			}
			
		} else {
			JError::raiseWarning('', JText::_('VCMHOTELDETAILSUPDATED0'));
		}
		
		$mainframe->redirect('index.php?option=com_vikchannelmanager&task=hoteldetails');
		
	}

	// SAVE TRIP ADVISOR ROOMS INVENTORY
	
	function saveRoomsInventory() {
		
		$mainframe = JFactory::getApplication();
		$dbo = JFactory::getDBO();
		
		$args = array();
		$args['names'] = JRequest::getVar('name', array());
		$args['costs'] = JRequest::getVar('cost', array());
		$args['images'] = JRequest::getVar('image', array());
		$args['descriptions'] = JRequest::getVar('desc', array());
		$args['urls'] = JRequest::getVar('url', array());
		$args['amenities'] = JRequest::getVar('amenities', array(array()));
		$args['codes'] = JRequest::getVar('codes', array());
		$args['ids'] = JRequest::getVar('tac_room_id', array());
		$args['vbids'] = JRequest::getVar('vb_room_id', array());
		$args['status'] = JRequest::getVar('status', array());
		
		$remove_rooms = array();
		
		$count = 0;
		
		for( $i = 0; $i < count($args['ids']); $i++ ) {
			
			if( $args['status'][$i] ) {
			
				$name = $args['names'][$i];
				$img = $args['images'][$i];
				if( !empty($img) ) {
					$img = JURI::root().'components'.DS.'com_vikbooking'.DS.'resources'.DS.'uploads'.DS.$img;
				}
				$desc = $args['descriptions'][$i];
				$url = $args['urls'][$i];
				$cost = $args['costs'][$i];
				$amenities = $args['amenities'][$i];
				$amenities_str = "";
				if( !empty($amenities) && count($amenities) > 0 ) {
					$amenities_str = implode(',', $amenities);
				}
				$codes = $args['codes'][$i];
				$id = $args['ids'][$i];
				$vb_id = $args['vbids'][$i];
				
				if( !empty($name) && !empty($url) ) {
					$q = "";
					if( $id == 0 ) {
						$q = "INSERT INTO `#__vikchannelmanager_tac_rooms`(`name`,`desc`,`img`,`url`,`cost`,`amenities`,`codes`,`id_vb_room`) VALUES(".
						$dbo->quote($name).",".$dbo->quote($desc).",".$dbo->quote($img).",".$dbo->quote($url).",".$cost.",".
						$dbo->quote($amenities_str).",".$dbo->quote($codes).",".$vb_id.");";
					} else {
						$q = "UPDATE `#__vikchannelmanager_tac_rooms` SET 
						`name`=".$dbo->quote($name).",
						`desc`=".$dbo->quote($desc).",
						`img`=".$dbo->quote($img).",
						`url`=".$dbo->quote($url).",
						`cost`=".$dbo->quote($cost).",
						`amenities`=".$dbo->quote($amenities_str).",
						`codes`=".$dbo->quote($codes).",
						`id_vb_room`=".$vb_id." WHERE `id`=".$id." LIMIT 1;";
					}
					
					$dbo->setQuery($q);
					$dbo->Query($q);
					
					$count++;
				}
			
			} else {
				$remove_rooms[count($remove_rooms)] = $args['ids'][$i];
			}
		}

		$r_count = 0;
		foreach( $remove_rooms as $r ) {
			$q = 'DELETE FROM `#__vikchannelmanager_tac_rooms` WHERE `id`='.$r." LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$r_count++;
		}
		
		if( $count > 0 ) {
			$mainframe->enqueueMessage(JText::sprintf('VCMTACROOMSCREATEDMSG', $count));
		}
		if( $r_count > 0 ) {
			$mainframe->enqueueMessage(JText::sprintf('VCMTACROOMSREMOVEDMSG', $r_count));
		}
		
		if( $count > 0 || $r_count > 0 ) {
			$rs = $this->sendTripConnectRoomsInventory();
			$mainframe->enqueueMessage(JText::sprintf('VCMTACROOMSSYNCHMSG', $rs['rooms']));
		} else {
			JError::raiseNotice('', JText::_('VCMTACROOMSNOACTIONMSG'));
		}
		
		$mainframe->redirect('index.php?option=com_vikchannelmanager&task=inventory');
		
	} 

	// SAVE TRIVAGO ROOMS INVENTORY
	
	function saveTrivagoRoomsInventory() {
		
		$mainframe = JFactory::getApplication();
		$dbo = JFactory::getDBO();
		
		$args = array();
		$args['names'] = JRequest::getVar('name', array());
		$args['costs'] = JRequest::getVar('cost', array());
		$args['images'] = JRequest::getVar('image', array());
		$args['descriptions'] = JRequest::getVar('desc', array());
		$args['urls'] = JRequest::getVar('url', array());
		$args['codes'] = JRequest::getVar('codes', array());
		$args['ids'] = JRequest::getVar('tri_room_id', array());
		$args['vbids'] = JRequest::getVar('vb_room_id', array());
		$args['status'] = JRequest::getVar('status', array());
		
		$remove_rooms = array();
		
		$count = 0;
		
		for( $i = 0; $i < count($args['ids']); $i++ ) {
			
			if( $args['status'][$i] ) {
			
				$name = $args['names'][$i];
				$img = $args['images'][$i];
				if( !empty($img) ) {
					$img = JURI::root().'components'.DS.'com_vikbooking'.DS.'resources'.DS.'uploads'.DS.$img;
				}
				$desc = $args['descriptions'][$i];
				$url = $args['urls'][$i];
				$cost = $args['costs'][$i];
				$codes = $args['codes'][$i];
				$id = $args['ids'][$i];
				$vb_id = $args['vbids'][$i];
				
				if( !empty($name) && !empty($url) ) {
					$q = "";
					if( $id == 0 ) {
						$q = "INSERT INTO `#__vikchannelmanager_tri_rooms`(`name`,`desc`,`img`,`url`,`cost`,`codes`,`id_vb_room`) VALUES(".
						$dbo->quote($name).",".$dbo->quote($desc).",".$dbo->quote($img).",".$dbo->quote($url).",".$cost.",".
						$dbo->quote($codes).",".$vb_id.");";
					} else {
						$q = "UPDATE `#__vikchannelmanager_tri_rooms` SET 
						`name`=".$dbo->quote($name).",
						`desc`=".$dbo->quote($desc).",
						`img`=".$dbo->quote($img).",
						`url`=".$dbo->quote($url).",
						`cost`=".$dbo->quote($cost).",
						`codes`=".$dbo->quote($codes).",
						`id_vb_room`=".$vb_id." WHERE `id`=".$id." LIMIT 1;";
					}
					
					$dbo->setQuery($q);
					$dbo->Query($q);
					
					$count++;
				}
			
			} else {
				$remove_rooms[count($remove_rooms)] = $args['ids'][$i];
			}
		}

		$r_count = 0;
		foreach( $remove_rooms as $r ) {
			$q = 'DELETE FROM `#__vikchannelmanager_tri_rooms` WHERE `id`='.$r." LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$r_count++;
		}
		
		if( $count > 0 ) {
			$mainframe->enqueueMessage(JText::sprintf('VCMTRIROOMSCREATEDMSG', $count));
		}
		if( $r_count > 0 ) {
			$mainframe->enqueueMessage(JText::sprintf('VCMTRIROOMSREMOVEDMSG', $r_count));
		}
		
		if( $count > 0 || $r_count > 0 ) {
			$rs = $this->sendTrivagoRoomsInventory();
			$mainframe->enqueueMessage(JText::sprintf('VCMTRIROOMSSYNCHMSG', $rs['rooms']));
		} else {
			JError::raiseNotice('', JText::_('VCMTRIROOMSNOACTIONMSG'));
		}
		
		$mainframe->redirect('index.php?option=com_vikchannelmanager&task=trinventory');
		
	} 

	// AIRBNB, FLIPKEY, HOMWAWAY
	
	function saveListings() {
		
		$mainframe = JFactory::getApplication();
		$dbo = JFactory::getDBO();
		
		$module = VikChannelManager::getActiveModule(true);
		
		$id_vb_rooms    = JRequest::getVar('id_vb_rooms', array());
		$id_listings    = JRequest::getVar('id_assoc', array());
		$urls           = JRequest::getVar('urls', array());
		
		$count = 0;
		
		for( $i = 0; $i < count($id_listings); $i++ ) {
			if( empty($id_listings[$i]) ) {
				$id_listings[$i] = -1;
			}
			
			$q = "";
			
			if( !empty($urls[$i]) ) {
				if( $id_listings[$i] == -1 ) {
					$q = "INSERT INTO `#__vikchannelmanager_listings` (`id_vb_room`, `retrieval_url`, `channel`) VALUES(".
					$id_vb_rooms[$i].",".
					$dbo->quote($urls[$i]).",".
					$dbo->quote($module['uniquekey']).");";
				} else {
					$q = "UPDATE `#__vikchannelmanager_listings` SET `retrieval_url`=".$dbo->quote($urls[$i])." WHERE `id`=".$id_listings[$i]." LIMIT 1;";
				}
			} else if( $id_listings[$i] != -1 ) {
				$q = "DELETE FROM `#__vikchannelmanager_listings` WHERE `id`=".$id_listings[$i]." LIMIT 1;";
			}
			
			if( !empty($q) ) {
				$dbo->setQuery($q);
				$dbo->Query($q);
				$count++;
			}
		}
		
		if( $count > 0 ) {
			$this->sendListingsRequest();

			$mainframe->enqueueMessage(JText::_('VCMLISTINGSUPDATED'));
		}
		$mainframe->redirect('index.php?option=com_vikchannelmanager&task=listings');
	}
	
	// SAVE CONFIGURATION
	
	function saveconfig() {
		
		$mainframe = JFactory::getApplication();
		$dbo = JFactory::getDBO();
		
		$args = array();
		$args['dateformat'] = JRequest::getString('dateformat');
		$args['currencysymb'] = JRequest::getString('currencysymb', '', 'request', JREQUEST_ALLOWHTML);
		$args['currencyname'] = JRequest::getString('currencyname');
		$args['defaultpayment'] = JRequest::getInt('defaultpayment');
		$args['apikey'] = JRequest::getString('apikey');
		$args['emailadmin'] = JRequest::getString('emailadmin');
		$args['vikbookingsynch'] = (JRequest::getInt('vikbookingsynch') == 1 ? 1 : 0);
		
		$vb_params = array();
		if( file_exists(JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'helpers'.DS.'lib.vikbooking.php') ) {
			require_once (JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'helpers'.DS.'lib.vikbooking.php');
			$vb_params['currencysymb'] = vikbooking::getCurrencySymb(true);
			$vb_params['currencyname'] = vikbooking::getCurrencyName(true);
			$vb_params['emailadmin'] = vikbooking::getAdminMail(true);
			$vb_params['dateformat'] = vikbooking::getDateFormat(true);
		}

		foreach( $vb_params as $k => $v ) {
			if( empty($args[$k]) ) {
				$args[$k] = $v;
			}
		}
		
		$module = VikChannelManager::getActiveModule(true);
		if( !empty($module['id']) ) {
			$module['params'] = json_decode($module['params'], true);
			$params = array();
			$changed = false;
			foreach( $module['params'] as $k => $v ) {
				$params[$k] = JRequest::getVar($k);
				if( empty($params[$k]) ) {
					$params[$k] = JRequest::getVar('old_'.$k, '');
				}
				$changed = $changed || ( $params[$k] != $v );
			}

			$err = false;
			$module['settings'] = json_decode($module['settings'], true);
			$settings = array();
			foreach( $module['settings'] as $k => $v ) {
				$settings[$k] = JRequest::getVar($k);
				$module['settings'][$k]['value'] = ( (@is_array($settings[$k]) && count($settings[$k]) > 0) || strlen($settings[$k]) > 0 ) ? $settings[$k] : $v['default'];
				$changed = $changed || ( $settings[$k] != $v['value'] );
				
				if( @is_array($settings[$k]) ) {
					$module['settings'][$k]['value'] = $settings[$k];
					$settings[$k] = json_encode($settings[$k]);
				}
				
				if( strlen($module['settings'][$k]['value']) == 0 && $module['settings'][$k]['required'] ) {
					$err = true;
				}
			}
			
			$q = "UPDATE `#__vikchannelmanager_channel` SET `params`=".$dbo->quote(json_encode($params)).", `settings`=".$dbo->quote(json_encode($module['settings']))." WHERE `id`=".$module['id']." LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			
			if( $changed && !$err ) {
				$this->sendCredentials($module['name'], $module['uniquekey'], $params, $settings);
			}
		}
		
		// validation
		
		// end validation
		
		foreach( $args as $key => $val ) {
			$q = "UPDATE `#__vikchannelmanager_config` SET " .
				"`setting`=". $dbo->quote( $val ) ." " .
				"WHERE `param`='". $key ."';";
			$dbo->setQuery( $q );
			$dbo->Query( $q );
		}
		
		if( !$err ) {
			$mainframe->enqueueMessage(JText::_("VCMSETTINGSUPDATED"));
		} else {
			JError::raiseWarning('', VikChannelManager::getErrorFromMap('e4j.error.RequestIntegrity'));
		}
		$mainframe->redirect("index.php?option=com_vikchannelmanager&task=config");
		
	}

	//Remove one of the more_accounts for this active channel
	function rmchaccount() {
		
		$mainframe = JFactory::getApplication();
		$dbo = JFactory::getDBO();
		
		$module = VikChannelManager::getActiveModule(true);
		$pind = JRequest::getInt('ind');
		$phid = JRequest::getString('hid');
		
		$more_accounts = array();
		if(!empty($module['id']) && $module['av_enabled'] == 1) {
			$q = "SELECT `prop_name`,`prop_params`, COUNT(DISTINCT `idroomota`) AS `tot_rooms` FROM `#__vikchannelmanager_roomsxref` WHERE `idchannel`=".(int)$module['uniquekey']." GROUP BY `prop_params` ORDER BY `prop_name` ASC;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if( $dbo->getNumRows() > 1 ) {
				$other_accounts = $dbo->loadAssocList();
				foreach ($other_accounts as $oacc) {
					if(!empty($oacc['prop_params'])) {
						$oacc['active'] = $oacc['prop_params'] == $module['params'] ? 1 : 0;
						$more_accounts[] = $oacc;
					}
				}
				if(!(count($more_accounts) > 1)) {
					$more_accounts = array();
				}
			}
		}
		if(count($more_accounts) && array_key_exists($pind, $more_accounts)) {
			$acc_info = json_decode($more_accounts[$pind]['prop_params'], true);
			if(array_key_exists('hotelid', $acc_info) && $acc_info['hotelid'] == $phid) {
				//remove all the mapped room types for this channel and account
				$q = "DELETE FROM `#__vikchannelmanager_roomsxref` WHERE `idchannel`=".(int)$module['uniquekey']." AND `prop_params`=".$dbo->quote($more_accounts[$pind]['prop_params']).";";
				$dbo->setQuery($q);
				$dbo->Query($q);
				//send PWD Request to e4jConnect with action="remove"
				$this->sendPwdRemoval($module['name'], $module['uniquekey'], $acc_info, json_decode($module['settings'], true));
				//
				$mainframe->enqueueMessage(JText::_("VCMSETTINGSUPDATED"));
				$mainframe->redirect("index.php?option=com_vikchannelmanager&task=config");
				exit;
			}
		}
		JError::raiseWarning('', VikChannelManager::getErrorFromMap('e4j.error.RequestIntegrity'));
		$mainframe->redirect("index.php?option=com_vikchannelmanager&task=config");
		
	}

	function changeStatusColumn() {
		$mainframe = JFactory::getApplication();
		
		$table = JRequest::getString('table_db','');
		$column = JRequest::getString('column_db','');
		$val = (JRequest::getInt('val',0)+1)%2;
		$id = JREquest::getInt('id',0);
		$return_url = 'index.php?option=com_vikchannelmanager&task=' . JRequest::getString('return_task');
		
		$dbo = JFactory::getDBO();
		
		$q = "UPDATE `#__vikchannelmanager_".$table."` SET `".$column."` = ".$val . " WHERE `id` = " . $id . ";";
		
		$dbo->setQuery($q);
		$dbo->Query($q);
		
		$mainframe->redirect($return_url);
		
	}
	
	function setmodule() {
		
		$mainframe = JFactory::getApplication();
		$dbo = JFactory::getDBO();
		
		$id = JRequest::getInt('id', 0);
		
		$q = "SELECT `id` FROM `#__vikchannelmanager_channel` WHERE `id`=".$id." LIMIT 1";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if( $dbo->getNumRows() > 0 ) {
			$q = "UPDATE `#__vikchannelmanager_config` SET `setting`=".$dbo->quote($id)." WHERE `param`='moduleactive' LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			//unset channel session values
			$session = JFactory::getSession();
			$session->set('vcmExecRarRs', '');
			//
		}
		
		$mainframe->redirect('index.php?option=com_vikchannelmanager&task=config');
		
	}
	
	// REMOVE
	
	function removeroomsxref() {
		
		$mainframe = JFactory::getApplication();
		$ids = JRequest::getVar('cid', array(0));
	
		if( count($ids) ) {
			$dbo = JFactory::getDBO();
			foreach($ids as $id){
				$q="DELETE FROM `#__vikchannelmanager_roomsxref` WHERE `id`=".intval($id)." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->Query($q);
			}
		}

		$mainframe->redirect("index.php?option=com_vikchannelmanager&task=rooms");
	}
	
	// CANCEL
	
	function cancel() {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikchannelmanager");
	}
	
	function cancelsynch() {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikchannelmanager&task=rooms");
	}
	
	function cancelorders() {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikchannelmanager&task=ordersvb");
	}
	
	function canceloversight() {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikchannelmanager&task=oversight");
	}
	
	// TASK
	
	function check_notifications() {
		$response = '0';
		$session = JFactory::getSession();
		$dbo = JFactory::getDBO();
		$q = "SELECT COUNT(*) FROM `#__vikchannelmanager_notifications` WHERE `read`=0;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$totnew = $dbo->loadResult();
		if (intval($totnew) > 0) {
			$response = intval($totnew) > 99 ? '99+' : $totnew;
			//unset any availability compare data
			$session->set('vcmExecAcmpRs', '');
			//
			$session->set('vcmNotifications', intval($totnew), 'vcm');
		}else {
			$session->set('vcmNotifications', 0, 'vcm');
		}
		echo $response;
		exit;
	}

	function wizard_store_api_key() {
		
		$api_key = JRequest::getVar('apikey', '');	
		
		if (!function_exists('curl_init')) {
			echo json_encode( array( 0, VikChannelManager::getErrorFromMap('e4j.error.Curl') ) );
			exit;
		}
		
		$e4jc_url = "https://e4jconnect.com/channelmanager/?r=exp&c=generic";
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
<!-- VikChannelManager EXP Request e4jConnect.com - VikBooking - extensionsforjoomla.com -->
<ExpiringRQ xmlns="http://www.e4jconnect.com/schemas/exprq">
	<Notify client="'.JURI::root().'"/>
	<Api key="'.$api_key.'"/>
	<Fetch question="api" channel="generic"/>
</ExpiringRQ>';
		$e4jC = new E4jConnectRequest($e4jc_url);
		$e4jC->setPostFields($xml);
		$rs = $e4jC->exec();
		if($e4jC->getErrorNo()) {
			echo json_encode( array( 0, curl_error($e4jC->getCurlHeader() ) ) );
			exit;
		}
		if( substr($rs, 0, 9) == 'e4j.error' || substr($rs, 0, 11) == 'e4j.warning') {
			echo json_encode( array( 0, VikChannelManager::getErrorFromMap($rs) ) );
			exit;
		}
		
		$dbo = JFactory::getDBO();
		$q = "UPDATE `#__vikchannelmanager_config` SET `setting`=".$dbo->quote($api_key)." WHERE `param`='apikey' LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		
		echo json_encode(array(1));
		exit;
		
	}

	function sendCustomAvailabilityRequest() {
		if (!function_exists('curl_init')) {
			echo VikChannelManager::getErrorFromMap('e4j.error.Curl');
			exit;
		}
		
		$config = VikChannelManager::loadConfiguration();
		
		$mainframe = JFactory::getApplication();
		$session = JFactory::getSession();
		
		$cust_a_req = JRequest::getVar('cust_av', array());
		$channels = JRequest::getVar('channel', array());
		
		if(!(count($channels) > 0)) {
			JError::raiseWarning('', JText::_('VCMNOCUSTOMAMODS'));
			$mainframe = JFactory::getApplication();
			$mainframe->redirect('index.php?option=com_vikchannelmanager&task=oversight');
			exit;
		}

		$cust_a = array();

		$rooms_id = array();
		$custa_details = '';
		
		foreach( $cust_a_req as $i => $v ) {
			list($idroom, $fromts, $endts, $units, $vbounits) = explode('-', $v);
			$details = array(
				'idroom' => $idroom,
				'fromts' => $fromts,
				'from' => date('Y-m-d', $fromts),
				'endts' => $endts,
				'end' => date('Y-m-d', $endts),
				'units' => $units,
				'vbounits' => $vbounits
			);
			$rooms_id[$idroom] = $idroom;
			if( empty($cust_a[$idroom]) ) {
				$cust_a[$idroom] = array();
			}
			
			array_push($cust_a[$idroom], $details);
		}
		
		$dbo = JFactory::getDBO();
		
		$q = "SELECT `id`,`name` FROM `#__vikbooking_rooms` WHERE `id` IN(".implode(', ', $rooms_id).");";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if( $dbo->getNumRows() > 0 ) {
			$rooms_details = $dbo->loadAssocList();
			foreach ($rooms_details as $room_details) {
				$rooms_id[$room_details['id']] = $room_details['name'];
			}
		}
		foreach ($rooms_id as $idr => $rname) {
			if(array_key_exists($idr, $cust_a)) {
				foreach ($cust_a[$idr] as $cust_det) {
					$custa_details .= $rname.': '.$cust_det['from'].' - '.$cust_det['end'].' Units: '.$cust_det['units']."\n";
					break;
				}
			}
		}
		$custa_details = rtrim($custa_details, "\n");

		//Clean vbo from channel IDs
		$channels_av = $channels;
		foreach( $cust_a as $idroom => $cust ) {
			if( !empty($channels_av[$idroom]) && count($channels_av[$idroom]) > 0 ) {
				foreach ($channels_av[$idroom] as $ch_av_k => $ch_av_v) {
					if($ch_av_v == 'vbo') {
						unset($channels_av[$idroom][$ch_av_k]);
					}
				}
			}
		}
		//

		$nkey = VikChannelManager::generateNKey('0');
		
		$e4jc_url = "https://e4jconnect.com/channelmanager/?r=custa&c=channels";
		
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
<!-- VikChannelManager CUSTA Request e4jConnect.com - Channels Module extensionsforjoomla.com -->
<CustAvailUpdateRQ xmlns="http://www.e4jconnect.com/channels/custarq">
	<Notify client="'.JURI::root().'" nkey="'.$nkey.'"/>
	<Api key="'.$config['apikey'].'"/>
	<AvailUpdate>'."\n";
		
		$totcombos = 0;
		foreach( $cust_a as $idroom => $cust ) {
			if( !empty($channels_av[$idroom]) && count($channels_av[$idroom]) > 0 ) {
				$q = "SELECT `r`.`idroomota`, `r`.`idchannel`, `r`.`otapricing`, `r`.`prop_params` FROM `#__vikchannelmanager_channel` AS `c`, `#__vikchannelmanager_roomsxref` AS `r`
				WHERE `c`.`uniquekey`=`r`.`idchannel` AND `c`.`av_enabled`=1 AND `r`.`idroomvb`=$idroom AND `c`.`uniquekey` IN (".implode(",", $channels_av[$idroom]).");";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if( $dbo->getNumRows() > 0 ) {
					$rows = $dbo->loadAssocList();
					
					foreach( $rows as $row ) {
						$hotelid = '';
						if(!empty($row['prop_params'])) {
							$prop_info = json_decode($row['prop_params'], true);
							if(array_key_exists('hotelid', $prop_info)) {
								$hotelid = $prop_info['hotelid'];
							}
						}
						foreach( $cust as $det ) {
							$rateplanid = '0';
							if((int)$row['idchannel'] == (int)VikChannelManagerConfig::AGODA && !empty($row['otapricing'])) {
								$ota_pricing = json_decode($row['otapricing'], true);
								if(count($ota_pricing) > 0 && array_key_exists('RatePlan', $ota_pricing)) {
									foreach ($ota_pricing['RatePlan'] as $rp_id => $rp_val) {
										$rateplanid = $rp_id;
										break;
									}
								}
							}
							$xml .= "\t\t".'<RoomType id="'.$row['idroomota'].'" rateplanid="'.$rateplanid.'" idchannel="'.$row['idchannel'].'" newavail="'.$det['units'].'"'.(!empty($hotelid) ? ' hotelid="'.$hotelid.'"' : '').'>'."\n";
							$xml .= "\t\t\t".'<Day from="'.$det['from'].'" to="'.$det['end'].'"/>'."\n";
							$xml .= "\t\t".'</RoomType>'."\n";
				
							$totcombos++;
						} 
					}
					
				}
			}
		}
		
		$xml .= "\t".'</AvailUpdate>
</CustAvailUpdateRQ>';

		$extra_qstring = '';
		
		if($totcombos > 0) {
			$e4jC = new E4jConnectRequest($e4jc_url);
			$e4jC->setPostFields($xml);
			$e4jC->slaveEnabled = true;
			$rs = $e4jC->exec();
			if($e4jC->getErrorNo()) {
				JError::raiseWarning('', @curl_error($e4jC->getCurlHeader()));
				$mainframe->redirect("index.php?option=com_vikchannelmanager&task=oversight");
				exit;
			}
			if (substr($rs, 0, 9) == 'e4j.error' || substr($rs, 0, 11) == 'e4j.warning') {
				JError::raiseWarning('', VikChannelManager::getErrorFromMap($rs));
				$mainframe->redirect("index.php?option=com_vikchannelmanager&task=oversight");
				exit;
			}
			//save notification
			$esitstr = 'e4j.OK.Channels.CUSTAR_RQ'."\n".$custa_details;
			$q = "INSERT INTO `#__vikchannelmanager_notifications` (`ts`,`type`,`from`,`cont`,`read`) VALUES('".time()."', '1', 'VCM', ".$dbo->quote($esitstr).", 0);";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$id_notification = $dbo->insertId();
			VikChannelManager::updateNKey($nkey, $id_notification);
			//unset any availability compare data
			$session->set('vcmExecAcmpRs', '');
			//Speed up the notification downloading interval
			$extra_qstring = '&fastcheck=1';
			//

			$mainframe->enqueueMessage(JText::sprintf('VCMTOTCUSTARQRESENT', $totcombos));
		}
		
		//Update availability on VBO if necessary
		$vbo_updated = false;
		if(!class_exists('vikbooking')) {
			require_once(JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'helpers'.DS.'lib.vikbooking.php');
		}
		$vbo_df = vikbooking::getDateFormat();
		$vbo_df = $vbo_df == "%d/%m/%Y" ? 'd/m/Y' : ($vbo_df == "%m/%d/%Y" ? 'm/d/Y' : 'Y/m/d');
		$morerb = vikbooking::getHoursMoreRb();
		$addrealback = vikbooking::getHoursRoomAvail() * 3600;
		$hcheckin = 0;
		$mcheckin = 0;
		$hcheckout = 0;
		$mcheckout = 0;
		$timeopst = vikbooking::getTimeOpenStore();
		if (is_array($timeopst)) {
			$opent = vikbooking::getHoursMinutes($timeopst[0]);
			$closet = vikbooking::getHoursMinutes($timeopst[1]);
			$hcheckin = $opent[0];
			$mcheckin = $opent[1];
			$hcheckout = $closet[0];
			$mcheckout = $closet[1];
		}
		foreach( $cust_a as $idroom => $cust ) {
			if( !empty($channels[$idroom]) && count($channels[$idroom]) > 0 ) {
				if(!in_array('vbo', $channels[$idroom])) {
					//update on VBO not requested
					continue;
				}
				foreach( $cust as $det ) {
					if($det['vbounits'] > $det['units']) {
						//Update availability on VBO
						$block_units = $det['vbounits'] - $det['units'];
						if((int)$det['endts'] === 0 || $det['endts'] == $det['fromts']) {
							//one day only
							$det['endts'] = $det['fromts'] + 86500; //avoid dst
						}else {
							//end of booking must be set to the day after than the portals, at the check-out time
							$det['endts'] += 86500;
						}
						$first = vikbooking::getDateTimestamp(date($vbo_df, $det['fromts']), $hcheckin, $mcheckin);
						$second = vikbooking::getDateTimestamp(date($vbo_df, $det['endts']), $hcheckout, $mcheckout);
						$secdiff = $second - $first;
						$daysdiff = $secdiff / 86400;
						if (is_int($daysdiff)) {
							if ($daysdiff < 1) {
								$daysdiff=1;
							}
						}else {
							if ($daysdiff < 1) {
								$daysdiff=1;
							}else {
								$sum = floor($daysdiff) * 86400;
								$newdiff = $secdiff - $sum;
								$maxhmore = $morerb * 3600;
								if ($maxhmore >= $newdiff) {
									$daysdiff = floor($daysdiff);
								}else {
									$daysdiff = ceil($daysdiff);
								}
							}
						}
						$insertedbusy = array();
						for($b = 1; $b <= $block_units; $b++) {
							$realback = $second + $addrealback;
							$q = "INSERT INTO `#__vikbooking_busy` (`idroom`,`checkin`,`checkout`,`realback`) VALUES(".$idroom.",".$first.",".$second.",".$realback.");";
							$dbo->setQuery($q);
							$dbo->Query($q);
							$lid = $dbo->insertid();
							$insertedbusy[] = $lid;
						}
						if (count($insertedbusy) > 0) {
							$sid = vikbooking::getSecretLink();
							$q = "INSERT INTO `#__vikbooking_orders` (`custdata`,`ts`,`status`,`days`,`checkin`,`checkout`,`sid`,`roomsnum`,`channel`) VALUES(".$dbo->quote(JText::_('VCMDESCRORDVBO')).",'".time()."','confirmed',".$daysdiff.",".$first.",".$second.",'".$sid."','1',".$dbo->quote(JText::_('VCMVBORDERFROMVCM')).");";
							$dbo->setQuery($q);
							$dbo->Query($q);
							$newoid = $dbo->insertid();
							//ConfirmationNumber
							$confirmnumber = vikbooking::generateConfirmNumber($newoid, true);
							//end ConfirmationNumber
							foreach($insertedbusy as $lid) {
								$q = "INSERT INTO `#__vikbooking_ordersbusy` (`idorder`,`idbusy`) VALUES('".$newoid."','".$lid."');";
								$dbo->setQuery($q);
								$dbo->Query($q);
							}
							$q = "INSERT INTO `#__vikbooking_ordersrooms` (`idorder`,`idroom`,`adults`,`children`) VALUES(".$newoid.",".$idroom.",1,0);";
							$dbo->setQuery($q);
							$dbo->Query($q);
						}
						$vbo_updated = true;
					}
				}
			}
		}
		//End Update availability on VBO

		if($vbo_updated === true && $totcombos === 0) {
			$mainframe->enqueueMessage(JText::_('VCMCUSTARQOKVBO'));
		}
		
		$mainframe->redirect("index.php?option=com_vikchannelmanager&task=oversight".$extra_qstring);
		
	}
	
	function exec_exp() {
		if (!function_exists('curl_init')) {
			echo 'e4j.error.'.VikChannelManager::getErrorFromMap('e4j.error.Curl');
			exit;
		}
		
		$session = JFactory::getSession();
		$req_cont = $session->get('exec_exp', 0, 'vcm');
		if( $req_cont >= 5 ) {
			echo 'e4j.error.'.JText::_('VCMEXECMAXREQREACHEDERR');
			exit;
		}
		
		$vcmresponse = 'e4j.error';
		$config = VikChannelManager::loadConfiguration();
		$validate = array('apikey');
		foreach($validate as $v) {
			if (empty($config[$v])) {
				echo 'e4j.error.'.VikChannelManager::getErrorFromMap('e4j.error.Settings');
				exit;
			}
		}
		$e4jc_url = "https://e4jconnect.com/channelmanager/?r=exp&c=generic";
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
<!-- VikChannelManager EXP Request e4jConnect.com - VikBooking - extensionsforjoomla.com -->
<ExpiringRQ xmlns="http://www.e4jconnect.com/schemas/exprq">
	<Notify client="'.JURI::root().'"/>
	<Api key="'.$config['apikey'].'"/>
	<Fetch question="api" channel="all"/>
</ExpiringRQ>';
		$e4jC = new E4jConnectRequest($e4jc_url);
		$e4jC->setPostFields($xml);
		$rs = $e4jC->exec();
		
		VikChannelManager::validateChannelResponse($rs);

		if($e4jC->getErrorNo()) {
			echo 'e4j.error.'.@curl_error($e4jC->getCurlHeader());
			exit;
		}
		if (substr($rs, 0, 9) == 'e4j.error' || substr($rs, 0, 11) == 'e4j.warning') {
			echo 'e4j.error.'.VikChannelManager::getErrorFromMap($rs);
			exit;
		}
		
		$session->set('exec_exp', ++$req_cont, 'vcm');
		$vcmresponse = JText::sprintf('VCMAPIEXPRQRSMESS', $rs);
		echo $vcmresponse;
		exit;
	}

	function exec_cha() {
		if (!function_exists('curl_init')) {
			echo 'e4j.error.'.VikChannelManager::getErrorFromMap('e4j.error.Curl');
			exit;
		}

		$session = JFactory::getSession();
		$req_cont = $session->get('exec_cha', 0, 'vcm');
		if( $req_cont >= 5 ) {
			echo JText::_('VCMEXECMAXREQREACHEDERR');
			exit;
		}

		$vcmresponse = 'e4j.error';
		$config = VikChannelManager::loadConfiguration();
		$validate = array('apikey');
		foreach($validate as $v) {
			if (empty($config[$v])) {
				echo 'e4j.error.'.VikChannelManager::getErrorFromMap('e4j.error.Settings');
				exit;
			}
		}
		$e4jc_url = "https://e4jconnect.com/channelmanager/?r=cha&c=generic";
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
<!-- VikChannelManager CHA Request e4jConnect.com - VikBooking - extensionsforjoomla.com -->
<ChannelsRQ xmlns="http://www.e4jconnect.com/schemas/charq">
	<Notify client="'.JURI::root().'"/>
	<Api key="'.$config['apikey'].'"/>
	<Fetch question="api" channel="all"/>
</ChannelsRQ>';
		$e4jC = new E4jConnectRequest($e4jc_url);
		$e4jC->setPostFields($xml);
		$rs = $e4jC->exec();

		VikChannelManager::validateChannelResponse($rs);

		if($e4jC->getErrorNo()) {
			echo 'e4j.error.'.@curl_error($e4jC->getCurlHeader());
			exit;
		}
		if (substr($rs, 0, 9) == 'e4j.error' || substr($rs, 0, 11) == 'e4j.warning') {
			echo 'e4j.error.'.VikChannelManager::getErrorFromMap($rs);
			exit;
		}
		
		// PARSE
		$rs = unserialize($rs);
		
		$dbo = JFactory::getDBO();
		
		$channel_keys = array(); 
		foreach( $rs as $channel ) {
			$channel_keys[count($channel_keys)] = $dbo->quote($channel['idchannel']);
		}
		
		if( count($channel_keys) > 0 ) {

			$q = "SELECT `id` FROM `#__vikchannelmanager_channel` WHERE `uniquekey` NOT IN (".implode(",", $channel_keys).");";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if( $dbo->getNumRows() > 0 ) {
				$rows = $dbo->loadAssocList();
				foreach( $rows as $r ) {
					$q = "DELETE FROM `#__vikchannelmanager_channel` WHERE `id`=".$r['id']." LIMIT 1;";
					$dbo->setQuery($q);
					$dbo->Query($q);
				}
			}
			
		} else {
			$q = "TRUNCATE TABLE `#__vikchannelmanager_channel`;";
			$dbo->setQuery($q);
			$dbo->Query($q);
		}
		
		$response_text = "";
		
		$date_format = VikChannelManager::getClearDateFormat(true);
		
		foreach( $rs as $channel ) {
			if( empty($channel['settings']) ) {
				$channel['settings'] = array();
			} else {
				$channel['settings'] = VCM::parseChannelSettings($channel);
			}
			
			$q = "SELECT `id` FROM `#__vikchannelmanager_channel` WHERE `uniquekey`=".$dbo->quote($channel['idchannel'])." LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if( $dbo->getNumRows() > 0 ) {
				// update
				$q = "UPDATE `#__vikchannelmanager_channel` SET `name`=".$dbo->quote($channel['channel']).",`settings`=".$dbo->quote(json_encode($channel['settings']))." WHERE `id`=".$dbo->loadResult()." LIMIT 1;";
			} else {
				// insert
				$q = "INSERT INTO `#__vikchannelmanager_channel` (`name`, `params`, `uniquekey`, `av_enabled`, `settings`) VALUES(".
				$dbo->quote($channel['channel']).", ".$dbo->quote(json_encode($channel['params'])).", ".$dbo->quote($channel['idchannel']).",".intval($channel['av']).",".$dbo->quote(json_encode($channel['settings'])).");";
			}
			$dbo->setQuery($q);
			$dbo->Query($q);
			
			$response_text .= "<span class=\"vcmactivechsinglespan\">- <strong>".$channel['channel']."</strong> (".date($date_format, $channel['validthru']).").</span>";
		}
		// END PARSE
		
		if( count($rs) > 0 ) {
			$session->set('exec_cha', ++$req_cont, 'vcm');
			echo JText::sprintf('VCMGETCHANNELSRQRSMESS1', $response_text);
		} else {
			echo JText::_('VCMGETCHANNELSRQRSMESS0');
		}
		exit;
	}
	
	function resend_arq () {
		$mainframe = JFactory::getApplication();
		
		$oids = JRequest::getVar('cid', array(0));
		
		if (count($oids) > 0) {
			$dbo = JFactory::getDBO();
			$q="SELECT `id` FROM `#__vikbooking_orders` WHERE `id` IN (".implode(",", $oids).") AND `status`='confirmed' LIMIT 3;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() > 0) {
				$vborders = $dbo->loadAssocList();
				require_once(JPATH_SITE . DS ."components". DS ."com_vikchannelmanager". DS . "helpers" . DS ."synch.vikbooking.php");
				foreach($vborders as $k => $v) {
					$vcm = new synchVikBooking($v['id']);
					$vcm->sendRequest();
				}
				$mainframe->enqueueMessage(JText::sprintf('VCMTOTARQRESENT', count($vborders)));
				$mainframe->redirect("index.php?option=com_vikchannelmanager");
			}else {
				JError::raiseWarning('', JText::_('VCMNOVBVALIDORDFOUND'));
				$mainframe->redirect("index.php?option=com_vikchannelmanager&task=ordersvb");
			}
		}else {
			JError::raiseWarning('', JText::_('VCMNOVBVALIDORDFOUND'));
			$mainframe->redirect("index.php?option=com_vikchannelmanager&task=ordersvb");
		}
	}
	
	function savesynch() {
		$mainframe = JFactory::getApplication();
		
		$prop_name = JRequest::getString('prop_name', '', 'request');
		$ptototarooms = JRequest::getInt('tototarooms', '', 'request');
		$potaroomsids = JRequest::getVar('otaroomsids', array());
		$potaroomsnames = JRequest::getVar('otaroomsnames', array());
		$pvbroomsids = JRequest::getVar('vbroomsids', array());
		$potapricing = JRequest::getVar('otapricing', array());
		$tototaids = count($potaroomsids);
		$tototanames = count($potaroomsnames);
		$totvbids = count($pvbroomsids);
		if ($ptototarooms == 0) {
			JError::raiseWarning('', JText::_('VCMSAVERSYNCHERRNOROOMSOTA'));
			$mainframe->redirect("index.php?option=com_vikchannelmanager&task=roomsynch");
			exit;
		}
		if ($tototaids == 0 || $tototanames == 0 || $totvbids == 0) {
			JError::raiseWarning('', JText::_('VCMSAVERSYNCHERREMPTYVALUES'));
			$mainframe->redirect("index.php?option=com_vikchannelmanager&task=roomsynch");
			exit;
		}
		if ($tototaids != $tototanames || $tototaids != $totvbids) {
			JError::raiseWarning('', JText::_('VCMSAVERSYNCHERRDIFFVALUES'));
			$mainframe->redirect("index.php?option=com_vikchannelmanager&task=roomsynch");
			exit;
		}
		$rel = array();
		$relnames = array();
		$relplans = array();
		foreach($potaroomsids as $k => $otaid) {
			if (!empty($otaid) && !empty($potaroomsnames[$k]) && !empty($pvbroomsids[$k])) {
				$rel[$k] = $otaid.'_'.$pvbroomsids[$k];
				$relnames[$k] = $potaroomsnames[$k];
				$relplans[$k] = $potapricing[$k];
			}
		}
		
		$dbo = JFactory::getDBO();
		$module = VikChannelManager::getActiveModule(true);
		//VCM 1.4.0 rooms mapped for channels with av_enabled=1 must have the prop_params not empty
		//Those upgrading to 1.4.0 will have this value empty so it is necessary to remove those records
		$q = "SELECT * FROM `#__vikchannelmanager_roomsxref` WHERE `idchannel`=".intval($module['uniquekey']).";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() > 0) {
			$prev_mapped = $dbo->loadAssocList();
			foreach ($prev_mapped as $prev_map) {
				if(empty($prev_map['prop_params'])) {
					$q = "DELETE FROM `#__vikchannelmanager_roomsxref` WHERE `id`=".$prev_map['id'].";";
					$dbo->setQuery($q);
					$dbo->Query($q);
				}
			}
		}
		//
		$q = "DELETE FROM `#__vikchannelmanager_roomsxref` WHERE `idchannel`=".intval($module['uniquekey'])." AND `prop_params`=".$dbo->quote($module['params']).";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$rel = array_unique($rel);
		$totrelcreated = 0;
		foreach($rel as $k => $r) {
			$parts = explode('_', $r);
			$q = "INSERT INTO `#__vikchannelmanager_roomsxref` (`idroomvb`,`idroomota`,`idchannel`,`channel`,`otaroomname`,`otapricing`,`prop_params`,`prop_name`) VALUES('".trim($parts[1])."', '".trim($parts[0])."', ".intval($module['uniquekey']).", ".$dbo->quote($module['name']).", ".$dbo->quote($relnames[$k]).", ".$dbo->quote($relplans[$k]).", ".$dbo->quote($module['params']).", ".$dbo->quote($prop_name).");";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$totrelcreated++;
		}
		$config = VikChannelManager::loadConfiguration();
		//TODO: rename all config values that start with "expedia" to "channels"
		if (array_key_exists('expedialastsync', $config)) {
			$q = "UPDATE `#__vikchannelmanager_config` SET `setting`='".time()."' WHERE `param`='expedialastsync';";
		}else {
			//first time synching
			$q = "INSERT INTO `#__vikchannelmanager_config` (`param`,`setting`) VALUES('expedialastsync','".time()."');";
		}
		$dbo->setQuery($q);
		$dbo->Query($q);
		if (array_key_exists('expedialastnumroomsfetched', $config)) {
			$q="UPDATE `#__vikchannelmanager_config` SET `setting`='".$ptototarooms."' WHERE `param`='expedialastnumroomsfetched';";
		}else {
			//first time synching
			$q="INSERT INTO `#__vikchannelmanager_config` (`param`,`setting`) VALUES('expedialastnumroomsfetched','".$ptototarooms."');";
		}
		$dbo->setQuery($q);
		$dbo->Query($q);
		
		$mainframe->enqueueMessage(JText::sprintf('VCMRELATIONSSAVED', $totrelcreated));
		$mainframe->redirect("index.php?option=com_vikchannelmanager&task=rooms");
	}

	private function sendCredentials($ch_name, $ch_key, $ch_params, $ch_settings) {
		$api_key = VikChannelManager::getApiKey(true);
		
		$e4jc_url = "https://e4jconnect.com/channelmanager/?r=pwd&c=generic";
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
<!-- VikChannelManager PWD Request e4jConnect.com - VikBooking - extensionsforjoomla.com -->
<PasswordRQ xmlns="http://www.e4jconnect.com/schemas/pwdrq">
	<Notify client="'.JURI::root().'"/>
	<Params>';
		foreach( $ch_params as $k => $v ) {
			$xml .= '<Param name="'.$k.'" value="'.$v.'"/>';	
		}
		$xml .= '</Params>
	<Settings>';
		foreach( $ch_settings as $k => $v ) {
			$xml .= '<Setting name="'.$k.'" value="'.htmlentities($v).'"/>';	
		}
		$xml .= '</Settings>
	<Api key="'.$api_key.'"/>
	<Fetch channel="'.$ch_name.'" ukey="'.$ch_key.'"/>
</PasswordRQ>';
		
		$e4jC = new E4jConnectRequest($e4jc_url);
		$e4jC->setPostFields($xml);
		$rs = $e4jC->exec();
		if($e4jC->getErrorNo()) {
			JError::raiseWarning('', VikChannelManager::getErrorFromMap($e4jC->getErrorMsg()));
			return;
		}
		if (substr($rs, 0, 9) == 'e4j.error' || substr($rs, 0, 11) == 'e4j.warning') {
			JError::raiseWarning('', VikChannelManager::getErrorFromMap($rs));
			return;
		}

		if( $ch_key == VikChannelManagerConfig::TRIP_CONNECT ) {
			$args = explode('::', $rs);
			if( count($args) == 2 ) {
			
				$dbo = JFactory::getDBO();
				
				$q = "UPDATE `#__vikchannelmanager_config` SET `setting`=".$dbo->quote($args[0])." WHERE `param`='tac_account_id' LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->Query($q);
				
				$q = "UPDATE `#__vikchannelmanager_config` SET `setting`=".$dbo->quote($args[1])." WHERE `param`='tac_api_key' LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->Query($q);
			
			}
		}
		
	}

	private function sendPwdRemoval($ch_name, $ch_key, $ch_params, $ch_settings) {
		$api_key = VikChannelManager::getApiKey(true);
		
		$e4jc_url = "https://e4jconnect.com/channelmanager/?r=pwd&c=generic";
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
<!-- VikChannelManager PWD Request e4jConnect.com - VikBooking - extensionsforjoomla.com -->
<PasswordRQ xmlns="http://www.e4jconnect.com/schemas/pwdrq">
	<Notify client="'.JURI::root().'"/>
	<Params>';
		foreach( $ch_params as $k => $v ) {
			$xml .= '<Param name="'.$k.'" value="'.$v.'"/>';	
		}
		$xml .= '</Params>
	<Settings>';
		foreach( $ch_settings as $k => $v ) {
			$xml .= '<Setting name="'.$k.'" value="'.htmlentities($v).'"/>';	
		}
		$xml .= '</Settings>
	<Api key="'.$api_key.'"/>
	<Fetch channel="'.$ch_name.'" ukey="'.$ch_key.'" action="remove"/>
</PasswordRQ>';
		
		$e4jC = new E4jConnectRequest($e4jc_url);
		$e4jC->setPostFields($xml);
		$rs = $e4jC->exec();
		if($e4jC->getErrorNo()) {
			JError::raiseWarning('', VikChannelManager::getErrorFromMap($e4jC->getErrorMsg()));
			return;
		}
		if (substr($rs, 0, 9) == 'e4j.error' || substr($rs, 0, 11) == 'e4j.warning') {
			JError::raiseWarning('', VikChannelManager::getErrorFromMap($rs));
			return;
		}
	}

	private function sendHotelDetails($args) {
		$channel = VikChannelManager::getActiveModule(true);
		if( $channel['uniquekey'] == VikChannelManagerConfig::TRIP_CONNECT ) {
			return $this->sendTripConnectHotelDetails($args, $channel);
		} else if( $channel['uniquekey'] == VikChannelManagerConfig::TRIVAGO ) {
			// NOTICE //
			unset($args['countrycode']); // not send the countrycode for trivago
			////////////
			return $this->sendTrivagoHotelDetails($args, $channel);
		}
		
		return $this->sendGenericHotelDetails($args);
	} 

	private function sendTripConnectHotelDetails($args, $channel) {
		
		$session = JFactory::getSession();
		
		$api_key = VikChannelManager::getApiKey(true);
		
		$channel['params'] = json_decode($channel['params'], true);
		
		$args['amenities'] = json_encode(explode(',', $args['amenities']));
		
		$args['currency'] = VikChannelManager::getCurrencyName(true);
		
		$languages = glob('./language/*', GLOB_ONLYDIR);
		
		$lang_arr = array();
		for( $i = 0; $i < count($languages); $i++ ) {
			$app = explode('-', $languages[$i]);
			if( count($app) == 2 ) {
				$app[0] = substr($app[0], strrpos($app[0], '/')+1);
				
				if( file_exists(JPATH_SITE.substr($languages[$i], 1).DS.$app[0].'-'.$app[1].'.com_vikbooking.ini') ) {
					$lang_arr[count($lang_arr)] = $app[0];
				}
			}
		}
		
		$args['languages'] = json_encode($lang_arr);
		
		$e4jc_url = "https://e4jconnect.com/channelmanager/?r=tachd&c=tripadvisor";
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
<!-- VikChannelManager TACHD Request e4jConnect.com - VikBooking - TripAdvisor Module extensionsforjoomla.com -->
<HotelDetailsRQ xmlns="http://www.e4jconnect.com/tripadvisor/tachdrq">
	<Notify client="'.JURI::root().'"/>
	<Authentication username="'.$channel['params']['username'].'" password="'.$channel['params']['password'].'"/>
	<Hotel id="'.$channel['params']['tripadvisorid'].'"/>
	<Api key="'.$api_key.'"/>
	<HotelDetails>';
	foreach( $args as $k => $v ) {
		$xml .= '<'.ucwords($k).'>'.htmlspecialchars($v).'</'.ucwords($k).'>';
	}
	$xml .= '</HotelDetails>
</HotelDetailsRQ>';

		//echo htmlentities($xml);die;
		$e4jC = new E4jConnectRequest($e4jc_url);
		$e4jC->setPostFields($xml);
		$rs = $e4jC->exec();
		
		VikChannelManager::validateChannelResponse($rs);

		if($e4jC->getErrorNo()) {
			$session->set('hd-force-next-request', 1);
			JError::raiseWarning('', VikChannelManager::getErrorFromMap($e4jC->getErrorMsg()));
			return 0;
		}
		
		if (substr($rs, 0, 9) == 'e4j.error' || substr($rs, 0, 11) == 'e4j.warning') {
			$session->set('hd-force-next-request', 1);
			JError::raiseWarning('', VikChannelManager::getErrorFromMap($rs));
			return 0;
		}

		$session->set('hd-force-next-request', 0);
		
		$dbo = JFactory::getDBO();
		$q = "UPDATE `#__vikchannelmanager_config` SET `setting`=".$dbo->quote($rs)." WHERE `param`='tac_partner_id' LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		
		return 1;
		
	}

	private function sendTrivagoHotelDetails($args, $channel) {
		
		$session = JFactory::getDBO();
		
		$api_key = VikChannelManager::getApiKey(true);
		
		$channel['params'] = json_decode($channel['params'], true);
		
		$args['amenities'] = json_encode(explode(',', $args['amenities']));
		
		$args['currency'] = VikChannelManager::getCurrencyName(true);
		
		$languages = glob('./language/*', GLOB_ONLYDIR);
		
		$lang_arr = array();
		for( $i = 0; $i < count($languages); $i++ ) {
			$app = explode('-', $languages[$i]);
			if( count($app) == 2 ) {
				$app[0] = substr($app[0], strrpos($app[0], '/')+1);
				
				if( file_exists(JPATH_SITE.substr($languages[$i], 1).DS.$app[0].'-'.$app[1].'.com_vikbooking.ini') ) {
					$lang_arr[count($lang_arr)] = $app[0];
				}
			}
		}
		
		$args['languages'] = json_encode($lang_arr);
		
		$e4jc_url = "https://e4jconnect.com/channelmanager/?r=trihd&c=trivago";
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
<!-- VikChannelManager TRIHD Request e4jConnect.com - VikBooking - Trivago Module extensionsforjoomla.com -->
<HotelDetailsRQ xmlns="http://www.e4jconnect.com/trivago/trihdrq">
	<Notify client="'.JURI::root().'"/>
	<Authentication hotelname="'.$channel['params']['hotelname'].'"/>
	<Hotel id="'.intval(VikChannelManager::getTrivagoPartnerID()).'"/>
	<Api key="'.$api_key.'"/>
	<HotelDetails>';
	foreach( $args as $k => $v ) {
		$xml .= '<'.ucwords($k).'>'.htmlspecialchars($v).'</'.ucwords($k).'>';
	}
	$xml .= '</HotelDetails>
</HotelDetailsRQ>';

		//echo htmlentities($xml);die;
		$e4jC = new E4jConnectRequest($e4jc_url);
		$e4jC->setPostFields($xml);
		$rs = $e4jC->exec();

		VikChannelManager::validateChannelResponse($rs);

		if($e4jC->getErrorNo()) {
			$session->set('hd-force-next-request', 1);
			JError::raiseWarning('', VikChannelManager::getErrorFromMap($e4jC->getErrorMsg()));
			return 0;
		}

		if (substr($rs, 0, 9) == 'e4j.error' || substr($rs, 0, 11) == 'e4j.warning') {
			$session->set('hd-force-next-request', 1);
			JError::raiseWarning('', VikChannelManager::getErrorFromMap($rs));
			return 0;
		}

		$session->set('hd-force-next-request', 0);
		
		$dbo = JFactory::getDBO();
		$q = "UPDATE `#__vikchannelmanager_config` SET `setting`=".$dbo->quote($rs)." WHERE `param`='tri_partner_id' LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		
		return 1;
		
	}

	private function sendGenericHotelDetails($args) {
		$session = JFactory::getDBO();
		
		$api_key = VikChannelManager::getApiKey(true);
		
		$args['amenities'] = json_encode(explode(',', $args['amenities']));
		
		$args['currency'] = VikChannelManager::getCurrencyName(true);
		
		$languages = glob('./language/*', GLOB_ONLYDIR);
		
		$lang_arr = array();
		for( $i = 0; $i < count($languages); $i++ ) {
			$app = explode('-', $languages[$i]);
			if( count($app) == 2 ) {
				$app[0] = substr($app[0], strrpos($app[0], '/')+1);
				
				if( file_exists(JPATH_SITE.substr($languages[$i], 1).DS.$app[0].'-'.$app[1].'.com_vikbooking.ini') ) {
					$lang_arr[count($lang_arr)] = $app[0];
				}
			}
		}
		
		$args['languages'] = json_encode($lang_arr);
		
		$e4jc_url = "https://e4jconnect.com/channelmanager/?r=ehd&c=generic";
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
<!-- VikChannelManager EHD Request e4jConnect.com - VikBooking - extensionsforjoomla.com -->
<e4jHotelDetailsRQ xmlns="http://www.e4jconnect.com/schemas/ehdrq">
	<Notify client="'.JURI::root().'"/>
	<Api key="'.$api_key.'"/>
	<HotelDetails>';
	foreach( $args as $k => $v ) {
		$xml .= '<'.ucwords($k).'>'.htmlspecialchars($v).'</'.ucwords($k).'>';
	}
	$xml .= '</HotelDetails>
</e4jHotelDetailsRQ>';

		$e4jC = new E4jConnectRequest($e4jc_url);
		$e4jC->setPostFields($xml);
		$rs = $e4jC->exec();

		VikChannelManager::validateChannelResponse($rs);

		if($e4jC->getErrorNo()) {
			$session->set('hd-force-next-request', 1);
			JError::raiseWarning('', VikChannelManager::getErrorFromMap($e4jC->getErrorMsg()));
			return 0;
		}

		if (substr($rs, 0, 9) == 'e4j.error' || substr($rs, 0, 11) == 'e4j.warning') {
			$session->set('hd-force-next-request', 1);
			JError::raiseWarning('', VikChannelManager::getErrorFromMap($rs));
			return 0;
		}

		$session->set('hd-force-next-request', 0);
		
		return 1;
	}
	
	private function sendTripConnectRoomsInventory() {
		
		$api_key = VikChannelManager::getApiKey(true);
		
		$channel = VikChannelManager::getActiveModule(true);
		$channel['params'] = json_decode($channel['params'], true);
		
		$dbo = JFactory::getDBO();
		$args = array();
		$q = "SELECT * FROM `#__vikchannelmanager_tac_rooms`;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if( $dbo->getNumRows() > 0 ) {
			$args = $dbo->loadAssocList();
		}
		
		$e4jc_url = "https://e4jconnect.com/channelmanager/?r=tacrd&c=tripadvisor";
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
<!-- VikChannelManager TACRD Request e4jConnect.com - VikBooking - TripAdvisor Module extensionsforjoomla.com -->
<RoomsDetailsRQ xmlns="http://www.e4jconnect.com/tripadvisor/tacrdrq">
	<Notify client="'.JURI::root().'"/>
	<Authentication username="'.$channel['params']['username'].'" password="'.$channel['params']['password'].'"/>
	<Hotel id="'.$channel['params']['tripadvisorid'].'"/>
	<Api key="'.$api_key.'"/>
	<RoomsDetails>';
	foreach( $args as $row ) {
		if( !empty($row['amenities']) ) {
			$row['amenities'] = json_encode(explode(',', $row['amenities']));
		} else {
			$row['amenities'] = json_encode(array());
		}
		$row['url'] = urlencode($row['url']);
		
		$xml .= '<Room>';
		$xml .= '<Idvb>'.$row['id_vb_room'].'</Idvb>';
		$xml .= '<Name>'.htmlspecialchars($row['name']).'</Name>';
		$xml .= '<Url>'.htmlspecialchars($row['url']).'</Url>';
		$xml .= '<Description>'.htmlspecialchars($row['desc']).'</Description>';
		$xml .= '<Amenities>'.$row['amenities'].'</Amenities>';
		$xml .= '<Code>'.$row['codes'].'</Code>';
		$xml .= '<Cost>'.number_format($row['cost'], 2, ".", "").'</Cost>';
		$xml .= '</Room>';
	}
	$xml .= '</RoomsDetails>
</RoomsDetailsRQ>';
		
		$e4jC = new E4jConnectRequest($e4jc_url);
		$e4jC->setPostFields($xml);
		$rs = $e4jC->exec();
		
		VikChannelManager::validateChannelResponse($rs);
		
		if($e4jC->getErrorNo()) {
			JError::raiseWarning('', VikChannelManager::getErrorFromMap($e4jC->getErrorMsg()));
			return;
		}
		if (substr($rs, 0, 9) == 'e4j.error' || substr($rs, 0, 11) == 'e4j.warning') {
			JError::raiseWarning('', VikChannelManager::getErrorFromMap($rs));
			return;
		}
		
		return json_decode($rs, true);
		
	}

	private function sendTrivagoRoomsInventory() {
		
		$api_key = VikChannelManager::getApiKey(true);
		
		$channel = VikChannelManager::getActiveModule(true);
		$channel['params'] = json_decode($channel['params'], true);
		
		$dbo = JFactory::getDBO();
		$args = array();
		$q = "SELECT * FROM `#__vikchannelmanager_tri_rooms`;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if( $dbo->getNumRows() > 0 ) {
			$args = $dbo->loadAssocList();
		}
		
		$e4jc_url = "https://e4jconnect.com/channelmanager/?r=trird&c=trivago";
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
<!-- VikChannelManager TRIRD Request e4jConnect.com - VikBooking - Trivago Module extensionsforjoomla.com -->
<RoomsDetailsRQ xmlns="http://www.e4jconnect.com/trivago/trirdrq">
	<Notify client="'.JURI::root().'"/>
	<Authentication hotelname="'.$channel['params']['hotelname'].'"/>
	<Hotel id="'.VikChannelManager::getTrivagoPartnerID().'"/>
	<Api key="'.$api_key.'"/>
	<RoomsDetails>';
	foreach( $args as $row ) {
		$row['amenities'] = json_encode(explode(',', $row['amenities']));
		$row['url'] = urlencode($row['url']);
		
		$xml .= '<Room>';
		$xml .= '<Idvb>'.$row['id_vb_room'].'</Idvb>';
		$xml .= '<Name>'.htmlspecialchars($row['name']).'</Name>';
		$xml .= '<Url>'.htmlspecialchars($row['url']).'</Url>';
		$xml .= '<Description>'.htmlspecialchars($row['desc']).'</Description>';
		$xml .= '<Code>'.$row['codes'].'</Code>';
		$xml .= '<Cost>'.number_format($row['cost'], 2, ".", "").'</Cost>';
		$xml .= '</Room>';
	}
	$xml .= '</RoomsDetails>
</RoomsDetailsRQ>';
		
		$e4jC = new E4jConnectRequest($e4jc_url);
		$e4jC->setPostFields($xml);
		$rs = $e4jC->exec();

		VikChannelManager::validateChannelResponse($rs);
		
		if($e4jC->getErrorNo()) {
			JError::raiseWarning('', VikChannelManager::getErrorFromMap($e4jC->getErrorMsg()));
			return;
		}
		if (substr($rs, 0, 9) == 'e4j.error' || substr($rs, 0, 11) == 'e4j.warning') {
			JError::raiseWarning('', VikChannelManager::getErrorFromMap($rs));
			return;
		}
		
		return json_decode($rs, true);
		
	}

	function sendListingsRequest() {
		
		$api_key = VikChannelManager::getApiKey(true);
		
		$channel = VikChannelManager::getActiveModule(true);
		//$channel['params'] = json_decode($channel['params'], true);
		
		$dbo = JFactory::getDBO();
		$args = array();
		$q = "SELECT * FROM `#__vikchannelmanager_listings` WHERE `channel`=".$dbo->quote($channel['uniquekey']).";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if( $dbo->getNumRows() > 0 ) {
			$args = $dbo->loadAssocList();
		}
		
		$e4jc_url = "https://e4jconnect.com/channelmanager/?r=icalurl&c=".$channel['name'];
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
<!-- VikChannelManager ICALURL Request e4jConnect.com - VikBooking - Trivago Module extensionsforjoomla.com -->
<IcalurlRQ xmlns="http://www.e4jconnect.com/schemas/icalurlrq">
	<Notify client="'.JURI::root().'"/>
	<Api key="'.$api_key.'"/>
	<Listings>';
	foreach( $args as $row ) {
		$row['retrieval_url'] = urlencode($row['retrieval_url']);
		
		$xml .= '<Listing roomid="'.$row['id_vb_room'].'" url="'.$row['retrieval_url'].'"/>';
	}
	$xml .= '</Listings>
</IcalurlRQ>';
		
		$e4jC = new E4jConnectRequest($e4jc_url);
		$e4jC->setPostFields($xml);
		$rs = $e4jC->exec();
		
		VikChannelManager::validateChannelResponse($rs);
		
		if($e4jC->getErrorNo()) {
			JError::raiseWarning('', VikChannelManager::getErrorFromMap($e4jC->getErrorMsg()));
			return;
		}
		if (substr($rs, 0, 9) == 'e4j.error' || substr($rs, 0, 11) == 'e4j.warning') {
			JError::raiseWarning('', VikChannelManager::getErrorFromMap($rs));
			return;
		}
		
		return json_decode($rs, true);
		
	}

	public function input_output_diagnostic() {
		
		if (!function_exists('curl_init')) {
			echo 'e4j.error.'.VikChannelManager::getErrorFromMap('e4j.error.Curl');
			exit;
		}

		$token = VikChannelManager::generateSerialCode(16);

		$filename = JPATH_SITE.DS.'components'.DS.'com_vikchannelmanager'.DS.'assets'.DS.$token.".txt";

		$handle = fopen($filename, "w");
		if( $handle === null ) {
			echo 'e4j.error.'.VikChannelManager::getErrorFromMap('e4j.error.File.Permissions.Write');
			exit;
		}
		fclose($handle);

		$api_key = VikChannelManager::getApiKey(true);
		
		$e4jc_url = "https://e4jconnect.com/channelmanager/?r=iod&c=generic";
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
<!-- VikChannelManager IOD Request e4jConnect.com - VikBooking - extensionsforjoomla.com -->
<InputOutputDiagnosticRQ xmlns="http://www.e4jconnect.com/schemas/iodrq">
	<Notify client="'.JURI::root().'"/>
	<Api key="'.$api_key.'"/>
	<Session token="'.$token.'"/>
</InputOutputDiagnosticRQ>';
		
		$e4jC = new E4jConnectRequest($e4jc_url);
		$e4jC->setTimeout(40);
		$e4jC->setConnectTimeout(40);
		$e4jC->slaveEnabled = true;
		$e4jC->setPostFields($xml);
		$rs = $e4jC->exec();
		
		if($e4jC->getErrorNo()) {
			echo 'e4j.error.'.VikChannelManager::getErrorFromMap($e4jC->getErrorMsg())."<br />".$e4jC->getErrorMsg();
			exit;
		}
		if (substr($rs, 0, 9) == 'e4j.error' || substr($rs, 0, 11) == 'e4j.warning') {
			echo 'e4j.error.'.VikChannelManager::getErrorFromMap($rs, true);
			exit;
		}
		if( $rs != "e4j.ok" ) {
			echo "e4j.error.$rs";
			exit;
		}

		$handle = fopen($filename, "r");
		$bytes = fread($handle, filesize($filename));
		fclose($handle);

		@unlink(JPATH_SITE.DS.'components'.DS.'com_vikchannelmanager'.DS.'assets'.DS.$token.".txt");

		//add SSL/TLS Info
		if(function_exists('curl_version')) {
			$bytes .= "<br/><hr/><br/>\n";
			$bytes .= "<h3>Server</h3>";
			$curl_info = curl_version();
			$bytes .= "<p><strong>OpenSSL</strong> includes support for TLS v1.1 and TLS v1.2 in OpenSSL 1.0.1 - <strong>NSS</strong> included support for TLS v1.1 in 3.14 and for TLS v1.2 in 3.15</p>\n";
			$bytes .= "<strong>SSL Version: ".(array_key_exists('ssl_version', $curl_info) ? $curl_info['ssl_version'] : '----')."</strong><br/>\n";
			//Howsmyssl.com TLS Check - Start
			$ch = curl_init('https://www.howsmyssl.com/a/check');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$ssl_data = curl_exec($ch);
			curl_close($ch);
			if($ssl_data !== false) {
				$ssl_extra = json_decode($ssl_data, true);
				if(is_array($ssl_extra) && count($ssl_extra) > 0) {
					$bytes .= "<strong>TLS Version: ".(array_key_exists('tls_version', $ssl_extra) ? $ssl_extra['tls_version'] : '----')."</strong><br/>\n";
				}
			}
			//Howsmyssl.com TLS Check - End
			foreach ($curl_info as $ck => $cv) {
				if(is_array($cv) || $ck == 'ssl_version') {
					continue;
				}
				$bytes .= $ck.': '.$cv."<br/>\n";
			}
		}
		//

		echo json_encode($bytes);
		exit;

	}

	// UPDATE FUNCTION 
	
	function update_program() {
		if (!function_exists('curl_init')) {
			echo VikChannelManager::getErrorFromMap('e4j.error.Curl');
			die;
		}
		
		$mainframe = JFactory::getApplication();
		
		if( !VikChannelManager::isNewVersionAvailable(true) ) {
			JError::raiseWarning('', 'No update available.');
			$mainframe->redirect('index.php?option=com_vikchannelmanager');
		}
		
		$vcmresponse = 'e4j.error';
		$config = VikChannelManager::loadConfiguration();
		$validate = array('apikey');
		foreach($validate as $v) {
			if (empty($config[$v])) {
				JError::raiseWarning('', VikChannelManager::getErrorFromMap('e4j.error.Settings'));
				$mainframe->redirect('index.php?option=com_vikchannelmanager');
			}
		}
		
		$joomla_version = '0.0';
		if( defined('JVERSION') ) {
			$joomla_version = JVERSION;
		} else if( function_exists('jimport') ) {
			jimport('joomla.version');
			if( class_exists('JVersion') ) {
				$version = new JVersion();
				$joomla_version = $version->getShortVersion();
			}
		}
		
		$e4jc_url = "https://e4jconnect.com/channelmanager/?r=upd&c=generic";
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
<!-- VikChannelManager UPD Request e4jConnect.com - VikBooking - extensionsforjoomla.com -->
<UpdateRQ xmlns="http://www.e4jconnect.com/schemas/updrq">
	<Notify client="'.JURI::root().'"/>
	<Api key="'.$config['apikey'].'"/>
	<Fetch vcm_version="'.VIKCHANNELMANAGER_SOFTWARE_VERSION.'" joomla_version="'.$joomla_version.'"/>
</UpdateRQ>';
		$e4jC = new E4jConnectRequest($e4jc_url);
		$e4jC->setPostFields($xml);
		$rs = $e4jC->exec();
		
		VikChannelManager::validateChannelResponse($rs);
		
		if($e4jC->getErrorNo()) {
			echo @curl_error($e4jC->getCurlHeader());
			die;
		}
		if( substr($rs, 0, 9) == 'e4j.error' ) {
			JError::raiseWarning('', VikChannelManager::getErrorFromMap($rs));
			$mainframe->redirect('index.php?option=com_vikchannelmanager');
			exit;
		} else if( substr($rs, 0, 11) == 'e4j.warning' ) {
			JError::raiseNotice('', VikChannelManager::getErrorFromMap($rs));
			$mainframe->redirect('index.php?option=com_vikchannelmanager');
			exit;
		} 
		
		$rs = $this->execute_updater(json_decode($rs, true));
		
		if( $rs['esit'] ) {
			$mainframe->enqueueMessage(JText::_($rs['message']));
		} else {
			JError::raiseWarning('', JText::_($rs['message']));
		}
		
		$mainframe->redirect('index.php?option=com_vikchannelmanager');
		
	}

	/**
	 * $content - array(
	 * 	['url'] => [updater.zip download url]
	 * 	['queries'] => [list of queries to execute]
	 * 	['upd_name'] => [name of the updater file]
	 * )
	 */

	private function execute_updater($contents) {
		
		require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikchannelmanager'.DS.'helpers'.DS.'upd.installer.php');
		
		$response = array('esit' => 0, 'message' => '');
		
		$package_file = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikchannelmanager'.DS.'helpers'.DS.'updater.zip';
		
		if( !file_exists($package_file) ) {
			set_time_limit(0); // unlimited max execution time
			
			$curl_opt = array(
				CURLOPT_FILE => fopen($package_file, 'w+'),
				CURLOPT_TIMEOUT => 3600,
				CURLOPT_URL => $contents['url']
			);
			$ch = curl_init();
			curl_setopt_array($ch, $curl_opt);
			curl_exec($ch);
		}
		
		$extracted = VikUpdaterInstaller::unzip($package_file, JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikchannelmanager'.DS.'helpers');
		if( !$extracted ) {
			$response['message'] = JText::_('VCMDOUPDATEUNZIPERROR');
			return $response;
		}

		$dir_path = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikchannelmanager'.DS.'helpers'.DS.$contents['upd_name'];
		if( !is_dir($dir_path) ) {
			$response['message'] = JText::_('VCMDOUPDATEPACKAGENOTFOUND');
			return $response;
		}
		
		VikUpdaterInstaller::executeQueries($contents['queries']);
		
		// ADMIN FILES 
		$admin_files = array('vikchannelmanager.php', 'controller.php', 'install.mysql.utf8.sql', 'uninstall.mysql.utf8.sql', 'access.xml', 'config.xml', 'vikchannelmanager.xml');
		foreach( $admin_files as $file ) {
			VikUpdaterInstaller::copyFile(
				JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikchannelmanager'.DS.'helpers'.DS.$contents['upd_name'].DS.'admin'.DS.$file,
				JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikchannelmanager'.DS.$file
			);
		}
		
		$admin_folders = array(
			array(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikchannelmanager'.DS.'helpers'.DS.$contents['upd_name'].DS.'admin'.DS.'assets', JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikchannelmanager'.DS.'assets'),
			array(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikchannelmanager'.DS.'helpers'.DS.$contents['upd_name'].DS.'admin'.DS.'helpers', JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikchannelmanager'.DS.'helpers'),
			array(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikchannelmanager'.DS.'helpers'.DS.$contents['upd_name'].DS.'admin'.DS.'views', JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikchannelmanager'.DS.'views'),
			array(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikchannelmanager'.DS.'helpers'.DS.$contents['upd_name'].DS.'admin'.DS.'language', JPATH_ADMINISTRATOR.DS.'language'.DS.'en-GB'),
		);
		
		foreach( $admin_folders as $folder ) {
			if(!VikUpdaterInstaller::smartCopy($folder[0], $folder[1])) {
				JError::raiseWarning('', 'Please report to e4j: Error copying the folder: '.$folder[0].' - to: '.$folder[1]);
			}
		}
		
		// SITE FILES
		$site_files = array('controller.php', 'vikchannelmanager.php');
		foreach( $site_files as $file ) {
			$res = VikUpdaterInstaller::copyFile(
				JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikchannelmanager'.DS.'helpers'.DS.$contents['upd_name'].DS.'site'.DS.$file,
				JPATH_SITE.DS.'components'.DS.'com_vikchannelmanager'.DS.$file
			);
		}
		
		$site_folders = array(
			array(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikchannelmanager'.DS.'helpers'.DS.$contents['upd_name'].DS.'site'.DS.'assets', JPATH_SITE.DS.'components'.DS.'com_vikchannelmanager'.DS.'assets'),
			array(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikchannelmanager'.DS.'helpers'.DS.$contents['upd_name'].DS.'site'.DS.'helpers', JPATH_SITE.DS.'components'.DS.'com_vikchannelmanager'.DS.'helpers'),
			array(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikchannelmanager'.DS.'helpers'.DS.$contents['upd_name'].DS.'site'.DS.'views', JPATH_SITE.DS.'components'.DS.'com_vikchannelmanager'.DS.'views'),
			array(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikchannelmanager'.DS.'helpers'.DS.$contents['upd_name'].DS.'site'.DS.'language', JPATH_SITE.DS.'language'.DS.'en-GB'),
		);
		
		foreach( $site_folders as $folder ) {
			if(!VikUpdaterInstaller::smartCopy($folder[0], $folder[1])) {
				JError::raiseWarning('', 'Please report to e4j: Error copying the folder: '.$folder[0].' - to: '.$folder[1]);
			}
		}
		
		VikUpdaterInstaller::uninstall($package_file);
		VikUpdaterInstaller::uninstall(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikchannelmanager'.DS.'helpers'.DS.$contents['upd_name']);
		if(is_dir(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikchannelmanager'.DS.'helpers'.DS.'__MACOSX')) {
			VikUpdaterInstaller::uninstall(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikchannelmanager'.DS.'helpers'.DS.'__MACOSX');
		}
		
		$response['esit'] = 1;
		$response['message'] = JText::_('VCMDOUPDATECOMPLETED');
		
		return $response;
		
	}
	
}
?>