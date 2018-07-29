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

class VikbookingViewOconfirm extends JViewLegacy {
	function display($tpl = null) {
		$vbo_tn = vikbooking::getTranslator();
		$proomid = JRequest::getVar('roomid', array());
		$pdays = JRequest::getInt('days', '', 'request');
		$pcheckin = JRequest::getInt('checkin', '', 'request');
		$pcheckout = JRequest::getInt('checkout', '', 'request');
		$proomsnum = JRequest::getInt('roomsnum', '', 'request');
		$padults = JRequest::getVar('adults', array());
		$pchildren = JRequest::getVar('children', array());
		$ppkg_id = JRequest::getInt('pkg_id', '', 'request');
		$pitemid = JRequest::getInt('Itemid', '', 'request');
		$nowdf = vikbooking::getDateFormat();
		if ($nowdf == "%d/%m/%Y") {
			$df = 'd/m/Y';
		} elseif ($nowdf == "%m/%d/%Y") {
			$df = 'm/d/Y';
		} else {
			$df = 'Y/m/d';
		}
		$dbo = JFactory::getDBO();
		$rooms = array();
		$arrpeople = array();
		for($ir = 1; $ir <= $proomsnum; $ir++) {
			$ind = $ir - 1;
			if (!empty($proomid[$ind])) {
				$q = "SELECT * FROM `#__vikbooking_rooms` WHERE `id`='".intval($proomid[$ind])."' AND `avail`='1';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if ($dbo->getNumRows() > 0) {
					$takeroom = $dbo->loadAssocList();
					$rooms[$ir] = $takeroom[0];
				}
			}
			if (!empty($padults[$ind])) {
				$arrpeople[$ir]['adults'] = intval($padults[$ind]);
			}else {
				$arrpeople[$ir]['adults'] = 0;
			}
			if (!empty($pchildren[$ind])) {
				$arrpeople[$ir]['children'] = intval($pchildren[$ind]);
			}else {
				$arrpeople[$ir]['children'] = 0;
			}
		}
		if (count($rooms) != $proomsnum) {
			JError::raiseWarning('', JText::_('VBROOMNOTFND'));
			$mainframe = JFactory::getApplication();
			$mainframe->redirect(JRoute::_('index.php?option=com_vikbooking'));
			exit;
		}
		$vbo_tn->translateContents($rooms, '#__vikbooking_rooms');
		$secdiff = $pcheckout - $pcheckin;
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
				$maxhmore = vikbooking::getHoursMoreRb() * 3600;
				if ($maxhmore >= $newdiff) {
					$daysdiff = floor($daysdiff);
				} else {
					$daysdiff = ceil($daysdiff);
				}
			}
		}
		//check that room(s) are available and get the price(s)
		$groupdays = vikbooking::getGroupDays($pcheckin, $pcheckout, $daysdiff);
		$morehst = vikbooking::getHoursRoomAvail() * 3600;
		$validtime = true;
		$prices = array();
		foreach($rooms as $num => $r) {
			$ppriceid = JRequest::getString('priceid'.$num, '', 'request');
			if (!empty($ppriceid)) {
				$prices[$num] = intval($ppriceid);
			}
			$check = "SELECT `id`,`checkin`,`checkout` FROM `#__vikbooking_busy` WHERE `idroom`='" . $r['id'] . "';";
			$dbo->setQuery($check);
			$dbo->Query($check);
			if ($dbo->getNumRows() > 0) {
				$busy = $dbo->loadAssocList();
				foreach ($groupdays as $gday) {
					$bfound = 0;
					foreach ($busy as $bu) {
						if ($gday >= $bu['checkin'] && $gday <= ($morehst + $bu['checkout'])) {
							$bfound++;
						}
					}
					if ($bfound >= $r['units']) {
						$validtime = false;
						break;
					}
				}
			}
		}
		//
		if ($validtime == true) {
			if (count($prices) == count($rooms)) {
				//load options
				$optionals = '';
				$selopt = '';
				$q = "SELECT * FROM `#__vikbooking_optionals`;";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if ($dbo->getNumRows() > 0) {
					$optionals = $dbo->loadAssocList();
					$vbo_tn->translateContents($optionals, '#__vikbooking_optionals');
					$selopt = array();
				}
				//
				//Package
				$pkg = array();
				if(!empty($ppkg_id)) {
					$pkg = vikbooking::validateRoomPackage($ppkg_id, $rooms, $daysdiff, $pcheckin, $pcheckout);
					if(!is_array($pkg) || (is_array($pkg) && !(count($pkg) > 0)) ) {
						if(!is_array($pkg)) {
							JError::raiseWarning('', $pkg);
						}
						$mainframe = JFactory::getApplication();
						$mainframe->redirect(JRoute::_("index.php?option=com_vikbooking&view=packagedetails&pkgid=".$ppkg_id.(!empty($pitemid) ? "&Itemid=".$pitemid : ""), false));
						exit;
					}
				}
				//
				$tars = array();
				$validfares = true;
				foreach($rooms as $num => $r) {
					if(!(count($pkg) > 0)) {
						$q = "SELECT * FROM `#__vikbooking_dispcost` WHERE `idroom`='" . $r['id'] . "' AND `days`='" . $daysdiff . "' AND `idprice`='" . $prices[$num] . "';";
						$dbo->setQuery($q);
						$dbo->Query($q);
						if ($dbo->getNumRows() == 1) {
							$tar = $dbo->loadAssocList();
							$tar = vikbooking::applySeasonsRoom($tar, $pcheckin, $pcheckout);
							//different usage
							if ($r['fromadult'] <= $arrpeople[$num]['adults'] && $r['toadult'] >= $arrpeople[$num]['adults']) {
								$diffusageprice = vikbooking::loadAdultsDiff($r['id'], $arrpeople[$num]['adults']);
								//Occupancy Override
								$occ_ovr = vikbooking::occupancyOverrideExists($tar, $arrpeople[$num]['adults']);
								$diffusageprice = $occ_ovr !== false ? $occ_ovr : $diffusageprice;
								//
								if (is_array($diffusageprice)) {
									//set a charge or discount to the price(s) for the different usage of the room
									foreach($tar as $kpr => $vpr) {
										$tar[$kpr]['diffusage'] = $arrpeople[$num]['adults'];
										if ($diffusageprice['chdisc'] == 1) {
											//charge
											if ($diffusageprice['valpcent'] == 1) {
												//fixed value
												$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? 1 : 0;
												$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $tar[$kpr]['days'] : $diffusageprice['value'];
												$tar[$kpr]['diffusagecost'] = "+".$aduseval;
												$tar[$kpr]['cost'] = $vpr['cost'] + $aduseval;
											}else {
												//percentage value
												$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? $vpr['cost'] : 0;
												$aduseval = $diffusageprice['pernight'] == 1 ? round(($vpr['cost'] * $diffusageprice['value'] / 100) * $tar[$kpr]['days'] + $vpr['cost'], 2) : round(($vpr['cost'] * (100 + $diffusageprice['value']) / 100), 2);
												$tar[$kpr]['diffusagecost'] = "+".$diffusageprice['value']."%";
												$tar[$kpr]['cost'] = $aduseval;
											}
										}else {
											//discount
											if ($diffusageprice['valpcent'] == 1) {
												//fixed value
												$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? 1 : 0;
												$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $tar[$kpr]['days'] : $diffusageprice['value'];
												$tar[$kpr]['diffusagecost'] = "-".$aduseval;
												$tar[$kpr]['cost'] = $vpr['cost'] - $aduseval;
											}else {
												//percentage value
												$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? $vpr['cost'] : 0;
												$aduseval = $diffusageprice['pernight'] == 1 ? round($vpr['cost'] - ((($vpr['cost'] / $tar[$kpr]['days']) * $diffusageprice['value'] / 100) * $tar[$kpr]['days']), 2) : round(($vpr['cost'] * (100 - $diffusageprice['value']) / 100), 2);
												$tar[$kpr]['diffusagecost'] = "-".$diffusageprice['value']."%";
												$tar[$kpr]['cost'] = $aduseval;
											}
										}
									}
								}
							}
							//
							$tars[$num] = $tar;
						}else {
							$validfares = false;
							break;
						}
					}
					//load selected options
					if (@is_array($optionals)) {
						foreach ($optionals as $opt) {
							if (!empty($opt['ageintervals']) && $arrpeople[$num]['children'] > 0) {
								$tmpvar = JRequest::getVar('optid'.$num.$opt['id'], array(0));
								if (is_array($tmpvar) && count($tmpvar) > 0 && !empty($tmpvar[0])) {
									$opt['quan'] = 1;
									$optagecosts = vikbooking::getOptionIntervalsCosts($opt['ageintervals']);
									$optagenames = vikbooking::getOptionIntervalsAges($opt['ageintervals']);
									$optorigname = $opt['name'];
									foreach ($tmpvar as $chvar) {
										$opt['cost'] = $optagecosts[($chvar - 1)];
										$opt['name'] = $optorigname.' ('.$optagenames[($chvar - 1)].')';
										$opt['chageintv'] = $chvar;
										$selopt[$num][] = $opt;
									}
								}
							}else {
								$tmpvar = JRequest::getString('optid'.$num.$opt['id'], '', 'request');
								if (!empty ($tmpvar)) {
									$opt['quan'] = $tmpvar;
									$selopt[$num][] = $opt;
								}
							}
						}
					}
					//
				}	
				if ($validfares === true) {
					if (vikbooking::dayValidTs($pdays, $pcheckin, $pcheckout)) {
						$q = "SELECT * FROM `#__vikbooking_gpayments` WHERE `published`='1' ORDER BY `#__vikbooking_gpayments`.`ordering` ASC;";
						$dbo->setQuery($q);
						$dbo->Query($q);
						$payments = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : "";
						if(is_array($payments)) {
							$vbo_tn->translateContents($payments, '#__vikbooking_gpayments');
						}
						$q = "SELECT * FROM `#__vikbooking_custfields` ORDER BY `#__vikbooking_custfields`.`ordering` ASC;";
						$dbo->setQuery($q);
						$dbo->Query($q);
						$cfields = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : "";
						if(is_array($cfields)) {
							$vbo_tn->translateContents($cfields, '#__vikbooking_custfields');
						}
						$countries = '';
						if (is_array($cfields)) {
							foreach ($cfields as $cf) {
								if ($cf['type'] == 'country') {
									$q = "SELECT * FROM `#__vikbooking_countries` ORDER BY `#__vikbooking_countries`.`country_name` ASC;";
									$dbo->setQuery($q);
									$dbo->Query($q);
									$countries = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : "";
									break;
								}
							}
						}
						//coupon
						$pcouponcode = JRequest::getString('couponcode', '', 'request');
						$coupon = "";
						if(strlen($pcouponcode) > 0 && !(count($pkg) > 0)) {
							$coupon = vikbooking::getCouponInfo($pcouponcode);
							if(is_array($coupon)) {
								$coupondateok = true;
								$couponroomok = true;
								if(strlen($coupon['datevalid']) > 0) {
									$dateparts = explode("-", $coupon['datevalid']);
									$pickinfo = getdate($pcheckin);
									$dropinfo = getdate($pcheckout);
									$checkpick = mktime(0, 0, 0, $pickinfo['mon'], $pickinfo['mday'], $pickinfo['year']);
									$checkdrop = mktime(0, 0, 0, $dropinfo['mon'], $dropinfo['mday'], $dropinfo['year']);
									if(!($checkpick >= $dateparts[0] && $checkpick <= $dateparts[1] && $checkdrop >= $dateparts[0] && $checkdrop <= $dateparts[1])) {
										$coupondateok = false;
									}
								}
								if($coupondateok == true) {
									if($coupon['allvehicles'] == 0) {
										foreach($rooms as $num => $r) {
											if(!(preg_match("/;".$r['id'].";/i", $coupon['idrooms']))) {
												$couponroomok = false;
												break;
											}
										}
									}
									if($couponroomok == true) {
										$this->assignRef('coupon', $coupon);
									}else {
										JError::raiseWarning('', JText::_('VBCOUPONINVROOM'));
									}
								}else {
									JError::raiseWarning('', JText::_('VBCOUPONINVDATES'));
								}
							}else {
								JError::raiseWarning('', JText::_('VBCOUPONNOTFOUND'));
							}
						}
						//end coupon
						//Customer Details
						$cpin = vikbooking::getCPinIstance();
						$customer_details = $cpin->loadCustomerDetails();
						//
						$this->assignRef('rooms', $rooms);
						$this->assignRef('tars', $tars);
						$this->assignRef('prices', $prices);
						$this->assignRef('arrpeople', $arrpeople);
						$this->assignRef('roomsnum', $proomsnum);
						$this->assignRef('selopt', $selopt);
						$this->assignRef('days', $daysdiff);
						$this->assignRef('first', $pcheckin);
						$this->assignRef('second', $pcheckout);
						$this->assignRef('payments', $payments);
						$this->assignRef('cfields', $cfields);
						$this->assignRef('customer_details', $customer_details);
						$this->assignRef('countries', $countries);
						$this->assignRef('pkg', $pkg);
						$this->assignRef('vbo_tn', $vbo_tn);
						//theme
						$theme = vikbooking::getTheme();
						if($theme != 'default') {
							$thdir = JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'themes'.DS.$theme.DS.'oconfirm';
							if(is_dir($thdir)) {
								$this->_setPath('template', $thdir.DS);
							}
						}
						//
						parent::display($tpl);
					}else {
						showSelectVb(JText::_('VBERRCALCTAR'));
					}
				}else {
					showSelectVb(JText::_('VBTARNOTFOUND'));
				}
			}else {
				showSelectVb(JText::_('VBNOTARSELECTED'));
			}	
		}else {
			showSelectVb(JText::_('VBROOMNOTCONS') . " " . date($df . ' H:i', $pcheckin) . " " . JText::_('VBROOMNOTCONSTO') . " " . date($df . ' H:i', $pcheckout));
		}
	}
}
?>