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

jimport('joomla.application.component.view');

class VikbookingViewSearch extends JViewLegacy {
	function display($tpl = null) {
		if (vikbooking::allowBooking()) {
			$session = JFactory::getSession();
			$mainframe = JFactory::getApplication();
			$vbo_tn = vikbooking::getTranslator();
			$pcheckindate = JRequest::getString('checkindate', '', 'request');
			$pcheckinm = JRequest::getString('checkinm', '', 'request');
			$pcheckinh = JRequest::getString('checkinh', '', 'request');
			$pcheckoutdate = JRequest::getString('checkoutdate', '', 'request');
			$pcheckoutm = JRequest::getString('checkoutm', '', 'request');
			$pcheckouth = JRequest::getString('checkouth', '', 'request');
			$pcategories = JRequest::getString('categories', '', 'request');
			$proomsnum = JRequest::getInt('roomsnum', '', 'request');
			$proomsnum = $proomsnum < 1 ? 1 : $proomsnum;
			$showchildren = vikbooking::showChildrenFront();
			$padults = JRequest::getVar('adults', array());
			$pchildren = JRequest::getVar('children', array());
			$ppkg_id = JRequest::getInt('pkg_id', '', 'request');
			$nowdf = vikbooking::getDateFormat();
			if ($nowdf == "%d/%m/%Y") {
				$df = 'd/m/Y';
			} elseif ($nowdf == "%m/%d/%Y") {
				$df = 'm/d/Y';
			} else {
				$df = 'Y/m/d';
			}
			//vikbooking 1.5 channel manager
			$ch_start_date = JRequest::getString('start_date', '', 'request');
			$ch_end_date = JRequest::getString('end_date', '', 'request');
			$ch_num_adults = JRequest::getInt('num_adults', '', 'request');
			$ch_num_children = JRequest::getInt('num_children', '', 'request');
			if (!empty($ch_start_date) && !empty($ch_end_date)) {
				if(!empty($ch_num_adults) && $ch_num_adults > 0) {
					$padults = array(0 => $ch_num_adults);
				}
				if(!empty($ch_num_children) && $ch_num_children > 0) {
					$pchildren = array(0 => $ch_num_children);
				}
				if ($ch_start_date_ts = strtotime($ch_start_date)) {
					if ($ch_end_date_ts = strtotime($ch_end_date)) {
						$pcheckindate = date($df, $ch_start_date_ts);
						$pcheckoutdate = date($df, $ch_end_date_ts);
						$timeopst = vikbooking::getTimeOpenStore();
						if (is_array($timeopst)) {
							$opent = vikbooking::getHoursMinutes($timeopst[0]);
							$closet = vikbooking::getHoursMinutes($timeopst[1]);
							$pcheckinh = $opent[0];
							$pcheckinm = $opent[1];
							$pcheckouth = $closet[0];
							$pcheckoutm = $closet[1];
						} else {
							$pcheckinh = 0;
							$pcheckinm = 0;
							$pcheckouth = 0;
							$pcheckoutm = 0;
						}
					}
				}
			}
			//
			$arradultsrooms = array();
			$arradultsclause = array();
			$arrpeople = array();
			if (count($padults) > 0) {
				foreach($padults as $kad => $adu) {
					$roomnumb = $kad + 1;
					if (strlen($adu)) {
						$numadults = intval($adu);
						if ($numadults >= 0) {
							$arradultsrooms[$roomnumb] = $numadults;
							$arrpeople[$roomnumb]['adults'] = $numadults;
							$strclause = "(`r`.`fromadult`<=".$numadults." AND `r`.`toadult`>=".$numadults."";
							if ($showchildren && !empty($pchildren[$kad]) && intval($pchildren[$kad]) > 0) {
								$numchildren = intval($pchildren[$kad]);
								$arrpeople[$roomnumb]['children'] = $numchildren;
								$strclause .= " AND `r`.`fromchild`<=".$numchildren." AND `r`.`tochild`>=".$numchildren."";
							}else {
								$arrpeople[$roomnumb]['children'] = 0;
								//VikBooking 1.4 May Patch: if no children then the room must accept no children
								if ($showchildren && intval($pchildren[$kad]) == 0) {
									$strclause .= " AND `r`.`fromchild` = 0";
								}
								//
							}
							$strclause .= " AND `r`.`totpeople` >= ".($arrpeople[$roomnumb]['adults'] + $arrpeople[$roomnumb]['children']);
							$strclause .= " AND `r`.`mintotpeople` <= ".($arrpeople[$roomnumb]['adults'] + $arrpeople[$roomnumb]['children']);
							$strclause .= ")";
							$arradultsclause[] = $strclause;
						}
					}
				}
			}
			$session->set('vbroomsnum', $proomsnum);
			$session->set('vbarrpeople', $arrpeople);
			if (!empty ($pcheckindate) && !empty ($pcheckoutdate) && $proomsnum > 0 && count($arradultsrooms) == $proomsnum) {
				if (vikbooking::dateIsValid($pcheckindate) && vikbooking::dateIsValid($pcheckoutdate)) {
					$first = vikbooking::getDateTimestamp($pcheckindate, $pcheckinh, $pcheckinm);
					$second = vikbooking::getDateTimestamp($pcheckoutdate, $pcheckouth, $pcheckoutm);
					$actnow = time();
					$today_bookings = vikbooking::todayBookings();
					if($today_bookings) {
						$actnow = mktime(0, 0, 0, date('n'), date('j'), date('Y'));
					}
					if ($second > $first && $first >= $actnow) {
						$session->set('vbcheckin', $first);
						$session->set('vbcheckout', $second);
						$secdiff = $second - $first;
						$daysdiff = $secdiff / 86400;
						if (is_int($daysdiff)) {
							if ($daysdiff < 1) {
								$daysdiff = 1;
							}
						} else {
							if ($daysdiff < 1) {
								$daysdiff = 1;
							} else {
								$sum = floor($daysdiff) * 86400;
								$newdiff = $secdiff - $sum;
								$maxhmore = vikbooking::getHoursMoreRb() * 3600;
								if ($maxhmore >= $newdiff) {
									$daysdiff = floor($daysdiff);
								} else {
									$daysdiff = ceil($daysdiff);
								}
							}
						}
						//Restrictions
						$allrestrictions = vikbooking::loadRestrictions(false);
						$restrictions = vikbooking::globalRestrictions($allrestrictions);
						$restrcheckin = getdate($first);
						$restrcheckout = getdate($second);
						$restrictionsvalid = true;
						$restrictionerrmsg = '';
						if (count($restrictions) > 0) {
							if (array_key_exists($restrcheckin['mon'], $restrictions)) {
								//restriction found for this month, checking:
								if (strlen($restrictions[$restrcheckin['mon']]['wday']) > 0) {
									$rvalidwdays = array($restrictions[$restrcheckin['mon']]['wday']);
									if (strlen($restrictions[$restrcheckin['mon']]['wdaytwo']) > 0) {
										$rvalidwdays[] = $restrictions[$restrcheckin['mon']]['wdaytwo'];
									}
									if (!in_array($restrcheckin['wday'], $rvalidwdays)) {
										$restrictionsvalid = false;
										$restrictionerrmsg = JText::sprintf('VBRESTRERRWDAYARRIVAL', vikbooking::sayMonth($restrcheckin['mon']), vikbooking::sayWeekDay($restrictions[$restrcheckin['mon']]['wday']).(strlen($restrictions[$restrcheckin['mon']]['wdaytwo']) > 0 ? '/'.vikbooking::sayWeekDay($restrictions[$restrcheckin['mon']]['wdaytwo']) : ''));
									}elseif ($restrictions[$restrcheckin['mon']]['multiplyminlos'] == 1) {
										if (($daysdiff % $restrictions[$restrcheckin['mon']]['minlos']) != 0) {
											$restrictionsvalid = false;
											$restrictionerrmsg = JText::sprintf('VBRESTRERRMULTIPLYMINLOS', vikbooking::sayMonth($restrcheckin['mon']), $restrictions[$restrcheckin['mon']]['minlos']);
										}
									}
									$comborestr = vikbooking::parseJsDrangeWdayCombo($restrictions[$restrcheckin['mon']]);
									if (count($comborestr) > 0) {
										if (array_key_exists($restrcheckin['wday'], $comborestr)) {
											if (!in_array($restrcheckout['wday'], $comborestr[$restrcheckin['wday']])) {
												$restrictionsvalid = false;
												$restrictionerrmsg = JText::sprintf('VBRESTRERRWDAYCOMBO', vikbooking::sayMonth($restrcheckin['mon']), vikbooking::sayWeekDay($comborestr[$restrcheckin['wday']][0]).(count($comborestr[$restrcheckin['wday']]) == 2 ? '/'.vikbooking::sayWeekDay($comborestr[$restrcheckin['wday']][1]) : ''), vikbooking::sayWeekDay($restrcheckin['wday']));
											}
										}
									}
								}
								if (!empty($restrictions[$restrcheckin['mon']]['maxlos']) && $restrictions[$restrcheckin['mon']]['maxlos'] > 0 && $restrictions[$restrcheckin['mon']]['maxlos'] > $restrictions[$restrcheckin['mon']]['minlos']) {
									if ($daysdiff > $restrictions[$restrcheckin['mon']]['maxlos']) {
										$restrictionsvalid = false;
										$restrictionerrmsg = JText::sprintf('VBRESTRERRMAXLOSEXCEEDED', vikbooking::sayMonth($restrcheckin['mon']), $restrictions[$restrcheckin['mon']]['maxlos']);
									}
								}
								if ($daysdiff < $restrictions[$restrcheckin['mon']]['minlos']) {
									$restrictionsvalid = false;
									$restrictionerrmsg = JText::sprintf('VBRESTRERRMINLOSEXCEEDED', vikbooking::sayMonth($restrcheckin['mon']), $restrictions[$restrcheckin['mon']]['minlos']);
								}
							}elseif (array_key_exists('range', $restrictions)) {
								foreach($restrictions['range'] as $restr) {
									if ($restr['dfrom'] <= $first && ($restr['dto'] + 82799) >= $first) {
										//restriction found for this date range, checking:
										if (strlen($restr['wday']) > 0) {
											$rvalidwdays = array($restr['wday']);
											if (strlen($restr['wdaytwo']) > 0) {
												$rvalidwdays[] = $restr['wdaytwo'];
											}
											if (!in_array($restrcheckin['wday'], $rvalidwdays)) {
												$restrictionsvalid = false;
												$restrictionerrmsg = JText::sprintf('VBRESTRERRWDAYARRIVALRANGE', vikbooking::sayWeekDay($restr['wday']).(strlen($restr['wdaytwo']) > 0 ? '/'.vikbooking::sayWeekDay($restr['wdaytwo']) : ''));
											}elseif ($restr['multiplyminlos'] == 1) {
												if (($daysdiff % $restr['minlos']) != 0) {
													$restrictionsvalid = false;
													$restrictionerrmsg = JText::sprintf('VBRESTRERRMULTIPLYMINLOSRANGE', $restr['minlos']);
												}
											}
											$comborestr = vikbooking::parseJsDrangeWdayCombo($restr);
											if (count($comborestr) > 0) {
												if (array_key_exists($restrcheckin['wday'], $comborestr)) {
													if (!in_array($restrcheckout['wday'], $comborestr[$restrcheckin['wday']])) {
														$restrictionsvalid = false;
														$restrictionerrmsg = JText::sprintf('VBRESTRERRWDAYCOMBORANGE', vikbooking::sayWeekDay($comborestr[$restrcheckin['wday']][0]).(count($comborestr[$restrcheckin['wday']]) == 2 ? '/'.vikbooking::sayWeekDay($comborestr[$restrcheckin['wday']][1]) : ''), vikbooking::sayWeekDay($restrcheckin['wday']));
													}
												}
											}
										}
										if (!empty($restr['maxlos']) && $restr['maxlos'] > 0 && $restr['maxlos'] > $restr['minlos']) {
											if ($daysdiff > $restr['maxlos']) {
												$restrictionsvalid = false;
												$restrictionerrmsg = JText::sprintf('VBRESTRERRMAXLOSEXCEEDEDRANGE', $restr['maxlos']);
											}
										}
										if ($daysdiff < $restr['minlos']) {
											$restrictionsvalid = false;
											$restrictionerrmsg = JText::sprintf('VBRESTRERRMINLOSEXCEEDEDRANGE', $restr['minlos']);
										}
										if ($restrictionsvalid == false) {
											break;
										}
									}
								}
							}
						}
						//Closing Dates
						$err_closingdates = vikbooking::validateClosingDates($first, $second, $df);
						if(!empty($err_closingdates)) {
							$restrictionsvalid = false;
							$restrictionerrmsg = JText::sprintf('VBERRDATESCLOSED', $err_closingdates);
						}
						//
						if ($restrictionsvalid === true) {
							$dbo = JFactory::getDBO();
							$q = "SELECT `p`.*,`r`.`id` AS `r_reference_id`,`r`.`name`,`r`.`img`,`r`.`idcat`,`r`.`idcarat`,`r`.`units`,`r`.`fromadult`,`r`.`toadult`,`r`.`fromchild`,`r`.`tochild`,`r`.`smalldesc`,`r`.`totpeople`,`r`.`params` FROM `#__vikbooking_dispcost` AS `p`, `#__vikbooking_rooms` AS `r` WHERE `p`.`days`='".$daysdiff."' AND `p`.`idroom`=`r`.`id` AND `r`.`avail`='1' AND (".implode(" OR ", $arradultsclause).") ORDER BY `p`.`cost` ASC, `p`.`idroom` ASC;";
							$dbo->setQuery($q);
							$dbo->Query($q);
							if ($dbo->getNumRows() > 0) {
								$tars = $dbo->loadAssocList();
								$vbo_tn->translateContents($tars, '#__vikbooking_rooms', array('id' => 'r_reference_id'));
								$arrtar = array();
								foreach ($tars as $tar) {
									$arrtar[$tar['idroom']][] = $tar;
								}
								$filtercat = (!empty ($pcategories) && $pcategories != "all" ? true : false);
								//vikbooking 1.1
								$groupdays = vikbooking::getGroupDays($first, $second, $daysdiff);
								$morehst = vikbooking::getHoursRoomAvail() * 3600;
								//
								$allbusy = vikbooking::loadBusyRecords(array_keys($arrtar), $actnow);
								$all_locked = vikbooking::loadLockedRecords(array_keys($arrtar), $actnow);
								foreach ($arrtar as $kk => $tt) {
									if ($filtercat) {
										$cats = explode(";", $tt[0]['idcat']);
										if (!in_array($pcategories, $cats)) {
											unset ($arrtar[$kk]);
											continue;
										}
									}
									$arrtar[$kk][0]['unitsavail'] = $tt[0]['units'];
									if (count($allbusy) > 0 && array_key_exists($kk, $allbusy) && count($allbusy[$kk]) > 0) {
										$units_booked = array();
										$check_locked = count($all_locked) > 0 && array_key_exists($kk, $all_locked) && count($all_locked[$kk]) > 0 ? true : false;
										foreach ($groupdays as $gday) {
											$bfound = 0;
											foreach ($allbusy[$kk] as $bu) {
												if ($gday >= $bu['checkin'] && $gday <= ($morehst + $bu['checkout'])) {
													$bfound++;
												}
											}
											if ($bfound >= $tt[0]['units']) {
												unset ($arrtar[$kk]);
												break;
											}else {
												$units_booked[] = $bfound;
												if($check_locked === true) {
													foreach ($all_locked[$kk] as $bu) {
														if ($gday >= $bu['checkin'] && $gday <= $bu['realback']) {
															$bfound++;
														}
													}
													if ($bfound >= $tt[0]['units']) {
														unset ($arrtar[$kk]);
														break;
													}
												}
											}
										}
										if(count($units_booked) > 0) {
											$tot_u_booked = max($units_booked);
											$tot_u_left = ($tt[0]['units'] - $tot_u_booked);
											$arrtar[$kk][0]['unitsavail'] = $tot_u_left >= 0 ? $tot_u_left : 0;
										}
									}elseif (!vikbooking::roomNotLocked($kk, $tt[0]['units'], $first, $second)) {
										unset ($arrtar[$kk]);
									}
									//single room restrictions
									if (count($allrestrictions) > 0 && array_key_exists($kk, $arrtar)) {
										$roomrestr = vikbooking::roomRestrictions($kk, $allrestrictions);
										if (count($roomrestr) > 0) {
											$restrictionerrmsg = vikbooking::validateRoomRestriction($roomrestr, $restrcheckin, $restrcheckout, $daysdiff);
											if (strlen($restrictionerrmsg) > 0) {
												unset ($arrtar[$kk]);
											}
										}
									}
									//end single room restrictions
								}
								if (@ count($arrtar) > 0) {
									$arrtar = vikbooking::applySeasonalPrices($arrtar, $first, $second);
									$arrtar = vikbooking::sortResults($arrtar);
									//separate results per number of rooms with $results
									$tmparrtar = $arrtar;
									$results = array();
									$multiroomcount = array();
									foreach($arrpeople as $numroom => $aduchild) {
										$arrtar = $tmparrtar;
										$diffusage = array();
										$aduchild['children'] = !array_key_exists('children', $aduchild) ? 0 : $aduchild['children'];
										$nowtotpeople = $aduchild['adults'] + $aduchild['children'];
										foreach ($arrtar as $kk => $tt) {
											$validchildren = true;
											if ($showchildren) {
												if (!($tt[0]['fromchild'] <= $aduchild['children'] && $tt[0]['tochild'] >= $aduchild['children']) && $aduchild['children'] > 0) {
													$validchildren = false;
												}
											}
											$validtotpeople = true;
											if ($nowtotpeople > $tt[0]['totpeople']) {
												$errmess = JText::sprintf('VBERRPEOPLEPERROOM', $nowtotpeople, $aduchild['adults'], $aduchild['children']);
												$validtotpeople = false;
											}
											if($validchildren && $validtotpeople) {
												if ($tt[0]['toadult'] == $aduchild['adults']) {
													//clean the diffusage from best usage in case it exists from before
													foreach($arrtar[$kk] as $kpr => $vpr) {
														if (array_key_exists('diffusage', $arrtar[$kk][$kpr])) {
															unset($arrtar[$kk][$kpr]['diffusage']);
														}
														if (array_key_exists('diffusagecost', $arrtar[$kk][$kpr])) {
															//restore original price
															$operator = substr($arrtar[$kk][$kpr]['diffusagecost'], 0, 1);
															$valpcent = substr($arrtar[$kk][$kpr]['diffusagecost'], -1);
															if ($operator == "+") {
																if ($valpcent == "%") {
																	$diffvalue = substr($arrtar[$kk][$kpr]['diffusagecost'], 1, (strlen($arrtar[$kk][$kpr]['diffusagecost']) - 1));
																	if (array_key_exists('diffusagecostpernight', $arrtar[$kk][$kpr]) && $arrtar[$kk][$kpr]['diffusagecostpernight'] > 0) {
																		$arrtar[$kk][$kpr]['cost'] = $arrtar[$kk][$kpr]['diffusagecostpernight'];
																	}else {
																		$arrtar[$kk][$kpr]['cost'] = round(($vpr['cost'] * (100 - $diffvalue) / 100), 2);
																	}
																}else {
																	$diffvalue = substr($arrtar[$kk][$kpr]['diffusagecost'], 1);
																	$arrtar[$kk][$kpr]['cost'] = $vpr['cost'] - $diffvalue;
																}
															}elseif ($operator == "-") {
																if ($valpcent == "%") {
																	$diffvalue = substr($arrtar[$kk][$kpr]['diffusagecost'], 1, (strlen($arrtar[$kk][$kpr]['diffusagecost']) - 1));
																	if (array_key_exists('diffusagecostpernight', $arrtar[$kk][$kpr]) && $arrtar[$kk][$kpr]['diffusagecostpernight'] > 0) {
																		$arrtar[$kk][$kpr]['cost'] = $arrtar[$kk][$kpr]['diffusagecostpernight'];
																	}else {
																		$arrtar[$kk][$kpr]['cost'] = round(($vpr['cost'] * (100 + $diffvalue) / 100), 2);
																	}
																}else {
																	$diffvalue = substr($arrtar[$kk][$kpr]['diffusagecost'], 1);
																	$arrtar[$kk][$kpr]['cost'] = $vpr['cost'] + $diffvalue;
																}
															}
															//
															unset($arrtar[$kk][$kpr]['diffusagecost']);
															unset($arrtar[$kk][$kpr]['diffusagecostpernight']);
														}
													}
													//
													//VikBooking 1.3 - Maximum Occupancy Charges/Discounts
													$diffusageprice = vikbooking::loadAdultsDiff($kk, $aduchild['adults']);
													//Occupancy Override
													if(array_key_exists('occupancy_ovr', $tt[0]) && array_key_exists($aduchild['adults'], $tt[0]['occupancy_ovr']) && strlen($tt[0]['occupancy_ovr'][$aduchild['adults']]['value'])) {
														$diffusageprice = $tt[0]['occupancy_ovr'][$aduchild['adults']];
													}
													//
													if (is_array($diffusageprice)) {
														//set a charge or discount to the price(s) for the different usage of the room
														foreach($arrtar[$kk] as $kpr => $vpr) {
															$arrtar[$kk][$kpr]['diffusage'] = $aduchild['adults'];
															if ($diffusageprice['chdisc'] == 1) {
																//charge
																if ($diffusageprice['valpcent'] == 1) {
																	//fixed value
																	$arrtar[$kk][$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? 1 : 0;
																	$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $arrtar[$kk][$kpr]['days'] : $diffusageprice['value'];
																	$arrtar[$kk][$kpr]['diffusagecost'] = "+".$aduseval;
																	$arrtar[$kk][$kpr]['cost'] = $vpr['cost'] + $aduseval;
																}else {
																	//percentage value
																	$arrtar[$kk][$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? $vpr['cost'] : 0;
																	$aduseval = $diffusageprice['pernight'] == 1 ? round(($vpr['cost'] * $diffusageprice['value'] / 100) * $arrtar[$kk][$kpr]['days'] + $vpr['cost'], 2) : round(($vpr['cost'] * (100 + $diffusageprice['value']) / 100), 2);
																	$arrtar[$kk][$kpr]['diffusagecost'] = "+".$diffusageprice['value']."%";
																	$arrtar[$kk][$kpr]['cost'] = $aduseval;
																}
															}else {
																//discount
																if ($diffusageprice['valpcent'] == 1) {
																	//fixed value
																	$arrtar[$kk][$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? 1 : 0;
																	$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $arrtar[$kk][$kpr]['days'] : $diffusageprice['value'];
																	$arrtar[$kk][$kpr]['diffusagecost'] = "-".$aduseval;
																	$arrtar[$kk][$kpr]['cost'] = $vpr['cost'] - $aduseval;
																}else {
																	//percentage value
																	$arrtar[$kk][$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? $vpr['cost'] : 0;
																	$aduseval = $diffusageprice['pernight'] == 1 ? round($vpr['cost'] - ((($vpr['cost'] / $arrtar[$kk][$kpr]['days']) * $diffusageprice['value'] / 100) * $arrtar[$kk][$kpr]['days']), 2) : round(($vpr['cost'] * (100 - $diffusageprice['value']) / 100), 2);
																	$arrtar[$kk][$kpr]['diffusagecost'] = "-".$diffusageprice['value']."%";
																	$arrtar[$kk][$kpr]['cost'] = $aduseval;
																}
															}
														}
													}
													//VikBooking 1.3 - Maximum Occupancy Charges/Discounts
													//best usage
													$results[$numroom][]=$arrtar[$kk];
													$multiroomcount[$arrtar[$kk][0]['idroom']]['count'] += 1;
													$multiroomcount[$arrtar[$kk][0]['idroom']]['unitsavail'] = (int)$arrtar[$kk][0]['unitsavail'];
													$multiroomcount[$arrtar[$kk][0]['idroom']]['diffusage_r'.$numroom] = 0;
												}elseif ($tt[0]['fromadult'] <= $aduchild['adults'] && $tt[0]['toadult'] > $aduchild['adults']) {
													//different usage
													$diffusageprice = vikbooking::loadAdultsDiff($kk, $aduchild['adults']);
													//Occupancy Override
													if(array_key_exists('occupancy_ovr', $tt[0]) && array_key_exists($aduchild['adults'], $tt[0]['occupancy_ovr']) && strlen($tt[0]['occupancy_ovr'][$aduchild['adults']]['value'])) {
														$diffusageprice = $tt[0]['occupancy_ovr'][$aduchild['adults']];
													}
													//
													if (is_array($diffusageprice)) {
														//set a charge or discount to the price(s) for the different usage of the room
														foreach($arrtar[$kk] as $kpr => $vpr) {
															$arrtar[$kk][$kpr]['diffusage'] = $aduchild['adults'];
															if ($diffusageprice['chdisc'] == 1) {
																//charge
																if ($diffusageprice['valpcent'] == 1) {
																	//fixed value
																	$arrtar[$kk][$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? 1 : 0;
																	$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $arrtar[$kk][$kpr]['days'] : $diffusageprice['value'];
																	$arrtar[$kk][$kpr]['diffusagecost'] = "+".$aduseval;
																	$arrtar[$kk][$kpr]['cost'] = $vpr['cost'] + $aduseval;
																}else {
																	//percentage value
																	$arrtar[$kk][$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? $vpr['cost'] : 0;
																	$aduseval = $diffusageprice['pernight'] == 1 ? round(($vpr['cost'] * $diffusageprice['value'] / 100) * $arrtar[$kk][$kpr]['days'] + $vpr['cost'], 2) : round(($vpr['cost'] * (100 + $diffusageprice['value']) / 100), 2);
																	$arrtar[$kk][$kpr]['diffusagecost'] = "+".$diffusageprice['value']."%";
																	$arrtar[$kk][$kpr]['cost'] = $aduseval;
																}
															}else {
																//discount
																if ($diffusageprice['valpcent'] == 1) {
																	//fixed value
																	$arrtar[$kk][$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? 1 : 0;
																	$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $arrtar[$kk][$kpr]['days'] : $diffusageprice['value'];
																	$arrtar[$kk][$kpr]['diffusagecost'] = "-".$aduseval;
																	$arrtar[$kk][$kpr]['cost'] = $vpr['cost'] - $aduseval;
																}else {
																	//percentage value
																	$arrtar[$kk][$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? $vpr['cost'] : 0;
																	$aduseval = $diffusageprice['pernight'] == 1 ? round($vpr['cost'] - ((($vpr['cost'] / $arrtar[$kk][$kpr]['days']) * $diffusageprice['value'] / 100) * $arrtar[$kk][$kpr]['days']), 2) : round(($vpr['cost'] * (100 - $diffusageprice['value']) / 100), 2);
																	$arrtar[$kk][$kpr]['diffusagecost'] = "-".$diffusageprice['value']."%";
																	$arrtar[$kk][$kpr]['cost'] = $aduseval;
																}
															}
														}
													}else {
														$arrtar[$kk][0]['diffusage'] = $aduchild['adults'];
													}
													$diffusage[$numroom][]=$arrtar[$kk];
													$multiroomcount[$arrtar[$kk][0]['idroom']]['count'] += 1;
													$multiroomcount[$arrtar[$kk][0]['idroom']]['unitsavail'] = (int)$arrtar[$kk][0]['unitsavail'];
													$multiroomcount[$arrtar[$kk][0]['idroom']]['diffusage_r'.$numroom] = 1;
												}
											}
										}
										//merge $diffusage rooms with and after best usage rooms in $results
										if (count($diffusage) > 0) {
											foreach($diffusage as $nr => $du) {
												foreach($du as $duroom) {
													$results[$nr][]=$duroom;
												}
											}
										}
										//
									}
									//
									//check if rooms repeated passed the availability
									$limitpassed = false;
									$search_type = vikbooking::getSmartSearchType();
									$js_search = $search_type == 'dynamic' ? true : false;
									//dynamic smart search via PHP
									$js_overcounter = array();
									if ($js_search && $proomsnum > 1 && count($multiroomcount) > 0) {
										$tot_avail = 0;
										foreach($multiroomcount as $idroom => $info) {
											$tot_avail += $info['unitsavail'];
										}
										if ($tot_avail >= $proomsnum) {
											$gen_avail = $results;
											foreach($multiroomcount as $idroom => $info) {
												$multiroomcount[$idroom]['used'] = 0;
												if ($info['count'] > $info['unitsavail']) {
													$excessnum = $info['count'] - $info['unitsavail'];
													if ($excessnum > 0) {
														for($z = $proomsnum; $z >= 1; $z--) {
															if ($excessnum > 0) {
																foreach($results[$z] as $kres => $res) {
																	if ($res[0]['idroom'] == $idroom) {
																		unset($gen_avail[$z][$kres]);
																	}
																}
																$excessnum--;
															}
														}
													}
												}
											}
											for($z = $proomsnum; $z >= 1; $z--) {
												if (count($gen_avail[$z]) == 0) {
													foreach($gen_avail as $oknroom => $res) {
														if (count($gen_avail[$oknroom]) > 1) {
															$searchfrom = min(array_keys($res));
															foreach($res as $kr => $rr) {
																if (intval($kr) > intval($searchfrom)) {
																	//check if the second, third.. cheapest room(s) is compatible
																	if ($rr[0]['fromadult'] <= $arrpeople[$z]['adults'] && $rr[0]['toadult'] >= $arrpeople[$z]['adults'] && $rr[0]['fromchild'] <= $arrpeople[$z]['children'] && $rr[0]['tochild'] >= $arrpeople[$z]['children']) {
																		$gen_avail[$z][] = $gen_avail[$oknroom][$kr];
																		unset($gen_avail[$oknroom][$kr]);
																		break 2;
																	}
																}
															}
														}
													}
												}
											}
											if (count($gen_avail) == $proomsnum) {
												$js_overcounter = $multiroomcount;
												unset($gen_avail);
											}
										}
									}
									//
									//automatic smart search via PHP
									if ($proomsnum > 1 && count($multiroomcount) > 0) {
										foreach($multiroomcount as $idroom => $info) {
											if ($info['count'] > $info['unitsavail']) {
												$excessnum = $info['count'] - $info['unitsavail'];
												for($z = $proomsnum; $z >= 1; $z--) {
													if (array_key_exists('diffusage_r'.$z, $info) && $info['diffusage_r'.$z] == 1) {
														//remove repeated room where diffusage and excessnum still exceeds
														if ($excessnum > 0 && count($js_overcounter) == 0) {
															foreach($results[$z] as $kres => $res) {
																if ($res[0]['idroom'] == $idroom) {
																	unset($results[$z][$kres]);
																	$limitpassed = true;
																}
															}
															$excessnum--;
														}
														//
													}
												}
												//if excessnum still exceeds, means that the room is not available for the repeated best usages
												if ($excessnum > 0) {
													for($z = $proomsnum; $z >= 1; $z--) {
														if ($excessnum > 0 && count($js_overcounter) == 0) {
															foreach($results[$z] as $kres => $res) {
																if ($res[0]['idroom'] == $idroom) {
																	unset($results[$z][$kres]);
																	$limitpassed = true;
																}
															}
															$excessnum--;
														}
													}
												}
												//
											}
										}
									}
									//
									//if some room was repeated and removed from the multi rooms searched, check if enough results for each room
									if ($limitpassed == true) {
										$critic = array();
										for($z = $proomsnum; $z >= 1; $z--) {
											if (count($results[$z]) == 0) {
												$critic[] = $z;
												unset($results[$z]);
											}
										}
										if (count($critic) > 0) {
											//some rooms have 0 results, check if something good for this num of adults can be placed here
											$moved = array();
											foreach($critic as $kcr => $nroom) {
												foreach($results as $oknroom => $res) {
													if (count($results[$oknroom]) > 1) {
														$searchfrom = min(array_keys($res));
														foreach($res as $kr => $rr) {
															if (intval($kr) > intval($searchfrom)) {
																//check if the second, third.. cheapest room(s) is compatible
																if ($rr[0]['fromadult'] <= $arrpeople[$nroom]['adults'] && $rr[0]['toadult'] >= $arrpeople[$nroom]['adults'] && $rr[0]['fromchild'] <= $arrpeople[$nroom]['children'] && $rr[0]['tochild'] >= $arrpeople[$nroom]['children']) {
																	$results[$nroom][] = $results[$oknroom][$kr];
																	$moved[] = $oknroom.'_'.$nroom;
																	unset($results[$oknroom][$kr]);
																	unset($critic[$kcr]);
																	break 2;
																}
															}
														}
													}
												}
											}
											ksort($results);
											if (count($moved) > 0) {
												//check if moved rooms had charges/discounts for adults occupancy to update it
												foreach ($moved as $move) {
													$movedata = explode('_', $move);
													if ($arrpeople[$movedata[0]]['adults'] != $arrpeople[$movedata[1]]['adults'] && array_key_exists('diffusagecost', $results[$movedata[1]][0][0])) {
														//reset prices of the room
														foreach($results[$movedata[1]][0] as $kpr => $vpr) {
															if (array_key_exists('diffusage', $results[$movedata[1]][0][$kpr])) {
																unset($results[$movedata[1]][0][$kpr]['diffusage']);
															}
															if (array_key_exists('diffusagecost', $results[$movedata[1]][0][$kpr])) {
																//restore original price
																$operator = substr($results[$movedata[1]][0][$kpr]['diffusagecost'], 0, 1);
																$valpcent = substr($results[$movedata[1]][0][$kpr]['diffusagecost'], -1);
																if ($operator == "+") {
																	if ($valpcent == "%") {
																		$diffvalue = substr($results[$movedata[1]][0][$kpr]['diffusagecost'], 1, (strlen($results[$movedata[1]][0][$kpr]['diffusagecost']) - 1));
																		if (array_key_exists('diffusagecostpernight', $results[$movedata[1]][0][$kpr]) && $results[$movedata[1]][0][$kpr]['diffusagecostpernight'] > 0) {
																			$results[$movedata[1]][0][$kpr]['cost'] = $results[$movedata[1]][0][$kpr]['diffusagecostpernight'];
																		}else {
																			$results[$movedata[1]][0][$kpr]['cost'] = round(($vpr['cost'] * (100 - $diffvalue) / 100), 2);
																		}
																	}else {
																		$diffvalue = substr($results[$movedata[1]][0][$kpr]['diffusagecost'], 1);
																		$results[$movedata[1]][0][$kpr]['cost'] = $vpr['cost'] - $diffvalue;
																	}
																}elseif ($operator == "-") {
																	if ($valpcent == "%") {
																		$diffvalue = substr($results[$movedata[1]][0][$kpr]['diffusagecost'], 1, (strlen($results[$movedata[1]][0][$kpr]['diffusagecost']) - 1));
																		if (array_key_exists('diffusagecostpernight', $results[$movedata[1]][0][$kpr]) && $results[$movedata[1]][0][$kpr]['diffusagecostpernight'] > 0) {
																			$results[$movedata[1]][0][$kpr]['cost'] = $results[$movedata[1]][0][$kpr]['diffusagecostpernight'];
																		}else {
																			$results[$movedata[1]][0][$kpr]['cost'] = round(($vpr['cost'] * (100 + $diffvalue) / 100), 2);
																		}
																	}else {
																		$diffvalue = substr($results[$movedata[1]][0][$kpr]['diffusagecost'], 1);
																		$results[$movedata[1]][0][$kpr]['cost'] = $vpr['cost'] + $diffvalue;
																	}
																}
																//
																unset($results[$movedata[1]][0][$kpr]['diffusagecost']);
																unset($results[$movedata[1]][0][$kpr]['diffusagecostpernight']);
															}
														}
														//end reset prices of the room
														$diffusageprice = vikbooking::loadAdultsDiff($results[$movedata[1]][0][0]['idroom'], $arrpeople[$movedata[1]]['adults']);
														//Occupancy Override - Special Price may be setting a charge/discount for this occupancy while default price had no occupancy pricing
														if (!is_array($diffusageprice)) {
															foreach($results[$movedata[1]][0] as $kpr => $vpr) {
																if(array_key_exists('occupancy_ovr', $vpr) && array_key_exists($arrpeople[$movedata[1]]['adults'], $vpr['occupancy_ovr']) && strlen($vpr['occupancy_ovr'][$arrpeople[$movedata[1]]['adults']]['value'])) {
																	$diffusageprice = $vpr['occupancy_ovr'][$arrpeople[$movedata[1]]['adults']];
																	break;
																}
															}
															reset($results[$movedata[1]][0]);
														}
														//
														if (is_array($diffusageprice)) {
															//set a charge or discount to the price(s) for the different usage of the room
															foreach($results[$movedata[1]][0] as $kpr => $vpr) {
																//Occupancy Override
																if(array_key_exists('occupancy_ovr', $results[$movedata[1]][0][$kpr]) && array_key_exists($arrpeople[$movedata[1]]['adults'], $results[$movedata[1]][0][$kpr]['occupancy_ovr']) && strlen($results[$movedata[1]][0][$kpr]['occupancy_ovr'][$arrpeople[$movedata[1]]['adults']]['value'])) {
																	$diffusageprice = $results[$movedata[1]][0][$kpr]['occupancy_ovr'][$arrpeople[$movedata[1]]['adults']];
																}
																//
																$results[$movedata[1]][0][$kpr]['diffusage'] = $arrpeople[$movedata[1]]['adults'];
																if ($diffusageprice['chdisc'] == 1) {
																	//charge
																	if ($diffusageprice['valpcent'] == 1) {
																		//fixed value
																		$results[$movedata[1]][0][$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? 1 : 0;
																		$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $results[$movedata[1]][0][$kpr]['days'] : $diffusageprice['value'];
																		$results[$movedata[1]][0][$kpr]['diffusagecost'] = "+".$aduseval;
																		$results[$movedata[1]][0][$kpr]['cost'] = $vpr['cost'] + $aduseval;
																	}else {
																		//percentage value
																		$results[$movedata[1]][0][$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? $vpr['cost'] : 0;
																		$aduseval = $diffusageprice['pernight'] == 1 ? round(($vpr['cost'] * $diffusageprice['value'] / 100) * $results[$movedata[1]][0][$kpr]['days'] + $vpr['cost'], 2) : round(($vpr['cost'] * (100 + $diffusageprice['value']) / 100), 2);
																		$results[$movedata[1]][0][$kpr]['diffusagecost'] = "+".$diffusageprice['value']."%";
																		$results[$movedata[1]][0][$kpr]['cost'] = $aduseval;
																	}
																}else {
																	//discount
																	if ($diffusageprice['valpcent'] == 1) {
																		//fixed value
																		$results[$movedata[1]][0][$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? 1 : 0;
																		$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $results[$movedata[1]][0][$kpr]['days'] : $diffusageprice['value'];
																		$results[$movedata[1]][0][$kpr]['diffusagecost'] = "-".$aduseval;
																		$results[$movedata[1]][0][$kpr]['cost'] = $vpr['cost'] - $aduseval;
																	}else {
																		//percentage value
																		$results[$movedata[1]][0][$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? $vpr['cost'] : 0;
																		$aduseval = $diffusageprice['pernight'] == 1 ? round($vpr['cost'] - ((($vpr['cost'] / $results[$movedata[1]][0][$kpr]['days']) * $diffusageprice['value'] / 100) * $results[$movedata[1]][0][$kpr]['days']), 2) : round(($vpr['cost'] * (100 - $diffusageprice['value']) / 100), 2);
																		$results[$movedata[1]][0][$kpr]['diffusagecost'] = "-".$diffusageprice['value']."%";
																		$results[$movedata[1]][0][$kpr]['cost'] = $aduseval;
																	}
																}
															}
														}
													}
												}
												//end check if moved rooms had charges/discounts for adults occupancy to update it
											}
											//
										}
									}
									//
									$results = vikbooking::sortMultipleResults($results);
									//save prices in session for the modules
									$sessvals = array();
									$modprices = array();
									$sessvals['roomsnum'] = $proomsnum;
									$sessvals['checkin'] = $first;
									$sessvals['checkout'] = $second;
									for($i = 1; $i <= $proomsnum; $i++) {
										foreach($results[$i] as $indres => $res) {
											$modprices[$i][] = $res[0]['cost'];
										}
									}
									for($i = 1; $i <= $proomsnum; $i++) {
										$mincost = min($modprices[$i]);
										$maxcost = max($modprices[$i]);
										$sessvals[$i]['min'] = $mincost;
										$sessvals[$i]['max'] = $maxcost;
										$sessvals[$i]['adults'] = $arrpeople[$i]['adults'];
										$sessvals[$i]['children'] = $arrpeople[$i]['children'];
									}
									$session->set('vbsearchdata', $sessvals);
									//end save prices in session for the modules
									//apply price filters
									$ppricefrom = JRequest::getInt('r1pricefrom', '', 'request');
									$ppriceto = JRequest::getInt('r1priceto', '', 'request');
									if (!empty($ppricefrom) && !empty($ppriceto)) {
										foreach($results as $oknroom => $res) {
											$totroomres = count($res);
											foreach($res as $kr => $rr) {
												if ($oknroom > 1) {
													$ppricefrom = JRequest::getInt('r'.$oknroom.'pricefrom', '', 'request');
													$ppriceto = JRequest::getInt('r'.$oknroom.'priceto', '', 'request');
												}
												if (!empty($ppricefrom) && !empty($ppriceto)) {
													if ($rr[0]['cost'] < $ppricefrom || $rr[0]['cost'] > $ppriceto) {
														if ($totroomres > 1) {
															unset($results[$oknroom][$kr]);
															$totroomres--;
														}
													}
												}
											}
										}
									}
									//end apply price filters
									if (count($results) == $proomsnum) {
										//check whether the user is coming from roomdetails
										$proomdetail = JRequest::getInt('roomdetail', '', 'request');
										$pitemid = JRequest::getInt('Itemid', '', 'request');
										if(!empty($proomdetail) && array_key_exists($proomdetail, $arrtar) && $proomsnum == 1) {
											$mainframe->redirect(JRoute::_("index.php?option=com_vikbooking&task=showprc&roomsnum=1&roomopt[]=".$proomdetail."&adults[]=".$arrpeople[1]['adults']."&children[]=".$arrpeople[1]['children']."&days=".$daysdiff."&checkin=".$first."&checkout=".$second.(!empty($ppkg_id) ? "&pkg_id=" . $ppkg_id : "").(!empty($pitemid) ? "&Itemid=" . $pitemid : ""), false));
											exit;
										}else {
											if(!empty($proomdetail) && $proomsnum == 1) {
												$q="SELECT `id`,`name` FROM `#__vikbooking_rooms` WHERE `id`=".intval($proomdetail).";";
												$dbo->setQuery($q);
												$dbo->Query($q);
												if($dbo->getNumRows() > 0) {
													$cdet=$dbo->loadAssocList();
													$vbo_tn->translateContents($cdet, '#__vikbooking_rooms');
													JError::raiseWarning('', JText::sprintf('VBDETAILCNOTAVAIL', $cdet[0]['name'], $daysdiff));
												}
											}elseif(!empty($proomdetail) && $proomsnum > 1) {
												//check whether the user is coming from roomdetails and if the room is available for any room party
												$room_missing = false;
												foreach ($results as $indroom => $rooms) {
													$room_found = false;
													foreach($rooms as $room) {
														if($room[0]['idroom'] == $proomdetail && (!array_key_exists('unitsavail', $room[0]) || $room[0]['unitsavail'] >= $proomsnum)) {
															$room_found = true;
															break;
														}
													}
													if(!$room_found) {
														$room_missing = true;
														break;
													}
												}
												if($room_missing === false) {
													$aduchild_str = '';
													foreach ($arrpeople as $people) {
														$aduchild_str .= '&adults[]='.$people['adults'].'&children[]='.$people['children'];
													}
													$mainframe->redirect(JRoute::_("index.php?option=com_vikbooking&task=showprc&roomsnum=".$proomsnum."&roomopt[]=".$proomdetail."&roomopt[]=".$proomdetail.$aduchild_str."&days=".$daysdiff."&checkin=".$first."&checkout=".$second.(!empty($ppkg_id) ? "&pkg_id=" . $ppkg_id : "").(!empty ($pitemid) ? "&Itemid=" . $pitemid : ""), false));
													exit;
												}else {
													$q="SELECT `id`,`name` FROM `#__vikbooking_rooms` WHERE `id`=".intval($proomdetail).";";
													$dbo->setQuery($q);
													$dbo->Query($q);
													if($dbo->getNumRows() > 0) {
														$cdet=$dbo->loadAssocList();
														$vbo_tn->translateContents($cdet, '#__vikbooking_rooms');
														JError::raiseWarning('', JText::sprintf('VBDETAILMULTIRNOTAVAIL', $proomsnum, $cdet[0]['name'], $daysdiff));
													}
												}
											}
											$this->assignRef('res', $results);
											$this->assignRef('days', $daysdiff);
											$this->assignRef('checkin', $first);
											$this->assignRef('checkout', $second);
											$this->assignRef('roomsnum', $proomsnum);
											$this->assignRef('arrpeople', $arrpeople);
											$showchildren = $showchildren ? 1 : 0;
											$this->assignRef('showchildren', $showchildren);
											$this->assignRef('js_overcounter', $js_overcounter);
											$this->assignRef('vbo_tn', $vbo_tn);
											//theme
											$theme = vikbooking::getTheme();
											if($theme != 'default') {
												$thdir = JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'themes'.DS.$theme.DS.'search';
												if(is_dir($thdir)) {
													$this->_setPath('template', $thdir.DS);
												}
											}
											//
											parent::display($tpl);
										}
										//
									}else {
										//zero results for some room
										if (count($critic) > 0) {
											$errmess = array();
											foreach($critic as $nroom) {
												$errmess[] = $arrpeople[$nroom]['adults']." ".($arrpeople[$nroom]['adults'] == 1 ? JText::_('VBSEARCHRESADULT') : JText::_('VBSEARCHRESADULTS')).($arrpeople[$nroom]['children'] > 0 ? ", ".$arrpeople[$nroom]['children']." ".($arrpeople[$nroom]['children'] == 1 ? JText::_('VBSEARCHRESCHILD') : JText::_('VBSEARCHRESCHILDREN')) : "");
											}
											$errmess = array_unique($errmess);
											$errmess = implode(" - ", $errmess);
										}
										$this->setVboError(JText::sprintf('VBSEARCHERRNOTENOUGHROOMS', $errmess));
									}
								} else {
									if (strlen($restrictionerrmsg) > 0) {
										JError::raiseWarning('', $restrictionerrmsg);
									}
									$this->setVboError(JText::_('VBNOROOMSINDATE'));
								}
							} else {
								$sayerr = JText::_('VBNOROOMAVFOR') . " " . $daysdiff . " " . ($daysdiff > 1 ? JText::_('VBDAYS') : JText::_('VBDAY'));
								if(count($padults) == 1) {
									$sayerr .= ", ".$arrpeople[1]['adults']." ".($arrpeople[1]['adults'] > 1 ? JText::_('VBSEARCHRESADULTS') : JText::_('VBSEARCHRESADULT'));
									if ($arrpeople[1]['children'] > 0) {
										$sayerr .= ", ".$arrpeople[1]['children']." ".($arrpeople[1]['children'] > 1 ? JText::_('VBSEARCHRESCHILDREN') : JText::_('VBSEARCHRESCHILD'));
									}
								}
								$this->setVboError($sayerr);
							}
						}else {
							$this->setVboError($restrictionerrmsg);
						}
					} else {
						$session->set('vbcheckin', '');
						$session->set('vbcheckout', '');
						if ($first <= $actnow) {
							if (date('d/m/Y', $first) == date('d/m/Y', $actnow)) {
								$emess = JText::_('VBSRCHERRCHKINPASSED');
							}else {
								$emess = JText::_('VBSRCHERRCHKINPAST');
							}
						}else {
							$emess = JText::_('VBPICKBRET');
						}
						$this->setVboError($emess);
					}
				} else {
					$this->setVboError(JText::_('VBWRONGDF') . ": " . vikbooking::sayDateFormat());
				}
			} else {
				$this->setVboError(JText::_('VBSELPRDATE'));
			}
		} else {
			echo vikbooking::getDisabledBookingMsg();
		}
	}

	protected function setVboError($err) {
		$ppkg_id = JRequest::getInt('pkg_id', '', 'request');
		$pitemid = JRequest::getInt('Itemid', '', 'request');
		$mainframe = JFactory::getApplication();
		if(!empty($ppkg_id)) {
			if(!empty($err)) {
				JError::raiseWarning('', $err);
			}
			$mainframe->redirect(JRoute::_("index.php?option=com_vikbooking&view=packagedetails&pkgid=".$ppkg_id.(!empty($pitemid) ? "&Itemid=".$pitemid : ""), false));
		}else {
			showSelectVb($err);
		}
	}
}
?>