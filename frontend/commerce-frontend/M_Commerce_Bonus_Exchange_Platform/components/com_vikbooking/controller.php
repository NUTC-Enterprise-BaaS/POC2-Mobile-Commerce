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

jimport('joomla.application.component.controller');

class VikbookingController extends JControllerLegacy {
	function display() {
		$view=JRequest::getVar('view', '');
		if($view == 'roomslist') {
			JRequest::setVar('view', 'roomslist');
		}elseif($view == 'roomdetails') {
			JRequest::setVar('view', 'roomdetails');
		}elseif($view == 'searchdetails') {
			JRequest::setVar('view', 'searchdetails');
		}elseif($view == 'loginregister') {
			JRequest::setVar('view', 'loginregister');
		}elseif($view == 'orderslist') {
			JRequest::setVar('view', 'orderslist');
		}elseif($view == 'promotions') {
			JRequest::setVar('view', 'promotions');
		}elseif($view == 'availability') {
			JRequest::setVar('view', 'availability');
		}elseif($view == 'packageslist') {
			JRequest::setVar('view', 'packageslist');
		}elseif($view == 'packagedetails') {
			JRequest::setVar('view', 'packagedetails');
		}else {
			JRequest::setVar('view', 'vikbooking');
		}
		parent::display();
	}

	function search() {
		JRequest::setVar('view', 'search');
		parent::display();
	}

	function showprc() {
		JRequest::setVar('view', 'showprc');
		parent::display();
	}

	function oconfirm() {
		$requirelogin = vikbooking::requireLogin();
		if($requirelogin) {
			if(vikbooking::userIsLogged()) {
				JRequest::setVar('view', 'oconfirm');
			}else {
				JRequest::setVar('view', 'loginregister');
			}
		}else {
			JRequest::setVar('view', 'oconfirm');
		}
		parent::display();
	}
	
	function register() {
		$mainframe = JFactory::getApplication();
		$dbo = JFactory::getDBO();
		//user data
		$pname = JRequest::getString('name', '', 'request');
		$plname = JRequest::getString('lname', '', 'request');
		$pemail = JRequest::getString('email', '', 'request');
		$pusername = JRequest::getString('username', '', 'request');
		$ppassword = JRequest::getString('password', '', 'request');
		$pconfpassword = JRequest::getString('confpassword', '', 'request');
		//
		//order data
		$pitemid = JRequest::getString('Itemid', '', 'request');
		$proomid = JRequest::getVar('roomid', array());
		$pdays = JRequest::getInt('days', '', 'request');
		$pcheckin = JRequest::getInt('checkin', '', 'request');
		$pcheckout = JRequest::getInt('checkout', '', 'request');
		$proomsnum = JRequest::getInt('roomsnum', '', 'request');
		$padults = JRequest::getVar('adults', array());
		$pchildren = JRequest::getVar('children', array());
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
		$prices = array();
		foreach($rooms as $num => $r) {
			$ppriceid = JRequest::getString('priceid'.$num, '', 'request');
			if (!empty($ppriceid)) {
				$prices[$num] = intval($ppriceid);
			}
		}
		$selopt = array();
		$q = "SELECT * FROM `#__vikbooking_optionals` ORDER BY `#__vikbooking_optionals`.`ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$optionals = $dbo->loadAssocList();
			foreach($rooms as $num => $r) {
				foreach ($optionals as $opt) {
					if (!empty($opt['ageintervals']) && $arrpeople[$num]['children'] > 0) {
						$tmpvar = JRequest::getVar('optid'.$num.$opt['id'], array(0));
						if (is_array($tmpvar) && count($tmpvar) > 0 && !empty($tmpvar[0])) {
							$optagecosts = vikbooking::getOptionIntervalsCosts($opt['ageintervals']);
							$optagenames = vikbooking::getOptionIntervalsAges($opt['ageintervals']);
							$optorigname = $opt['name'];
							foreach ($tmpvar as $chvar) {
								$opt['quan'] = $chvar;
								$opt['chageintv'] = $chvar;
								$opt['cost'] = $optagecosts[($chvar - 1)];
								$opt['name'] = $optorigname.' ('.$optagenames[($chvar - 1)].')';
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
		}
		$strpriceid = "";
		foreach($prices as $num => $pid) {
			$strpriceid .= ($num > 1 ? "&" : "")."priceid".$num."=".$pid;
		}
		$stroptid = "";
		for($ir = 1; $ir <= $proomsnum; $ir++) {
			if (is_array($selopt[$ir])) {
				foreach($selopt[$ir] as $opt) {
					if (array_key_exists('chageintv', $opt)) {
						$stroptid .= "&optid".$ir.$opt['id']."[]=".$opt['chageintv'];
					}else {
						$stroptid .= "&optid".$ir.$opt['id']."=".$opt['quan'];
					}
				}
			}
		}
		$strroomid = "";
		foreach($rooms as $num => $r) {
			$strroomid .= "&roomid[]=".$r['id'];
		}
		$straduchild = "";
		foreach($arrpeople as $indroom => $aduch) {
			$straduchild .= "&adults[]=".$aduch['adults'];
			$straduchild .= "&children[]=".$aduch['children'];
		}
		
		$qstring = $strpriceid.$stroptid.$strroomid.$straduchild."&roomsnum=".$proomsnum."&days=".$pdays."&checkin=".$pcheckin."&checkout=".$pcheckout.(!empty($pitemid) ? "&Itemid=".$pitemid : "");
		//
		if(!vikbooking::userIsLogged()) {
			if (!empty($pname) && !empty($plname) && !empty($pusername) && validEmail($pemail) && $ppassword == $pconfpassword) {
				//save user
				$newuserid=vikbooking::addJoomlaUser($pname." ".$plname, $pusername, $pemail, $ppassword);
				if ($newuserid!=false && strlen($newuserid)) {
					//registration success
					$credentials = array('username' => $pusername, 'password' => $ppassword );
					//autologin
					$mainframe->login($credentials);
					$currentUser = JFactory::getUser();
					$currentUser->setLastVisit(time());
					$currentUser->set('guest', 0);
					//
					$mainframe->redirect(JRoute::_('index.php?option=com_vikbooking&task=oconfirm&'.$qstring, false));
				}else {
					//error while saving new user
					JError::raiseWarning('', JText::_('VBREGERRSAVING'));
					$mainframe->redirect(JRoute::_('index.php?option=com_vikbooking&view=loginregister&'.$qstring, false));
				}
			}else {
				//invalid data
				JError::raiseWarning('', JText::_('VBREGERRINSDATA'));
				$mainframe->redirect(JRoute::_('index.php?option=com_vikbooking&view=loginregister&'.$qstring, false));
			}
		}else {
			//user is already logged in, proceed
			$mainframe->redirect(JRoute::_('index.php?option=com_vikbooking&task=oconfirm&'.$qstring, false));
		}
	}
	
	function saveorder() {
		$dbo = JFactory::getDBO();
		$session = JFactory::getSession();
		$vbo_tn = vikbooking::getTranslator();
		$prooms = JRequest::getVar('rooms', array());
		$proomsnum = JRequest::getInt('roomsnum', '', 'request');
		$padults = JRequest::getVar('adults', array());
		$pchildren = JRequest::getVar('children', array());
		$pdays = JRequest::getString('days', '', 'request');
		$pcouponcode = JRequest::getString('couponcode', '', 'request');
		$pcheckin = JRequest::getString('checkin', '', 'request');
		$pcheckout = JRequest::getString('checkout', '', 'request');
		$pprtar = JRequest::getVar('prtar', array());
		$ppriceid = JRequest::getVar('priceid', array());
		$poptionals = JRequest::getString('optionals', '', 'request');
		$ptotdue = JRequest::getString('totdue', '', 'request');
		$pgpayid = JRequest::getString('gpayid', '', 'request');
		$ppkg_id = JRequest::getInt('pkg_id', '', 'request');
		$pitemid = JRequest::getInt('Itemid', '', 'request');
		$vaildtoken = true;
		if (vikbooking::tokenForm()) {
			$validtoken = false;
			$pviktoken = JRequest::getString('viktoken', '', 'request');
			$sessvbtkn = $session->get('vikbtoken', '');
			if (!empty($pviktoken) && $sessvbtkn == $pviktoken) {
				$session->set('vikbtoken', '');
				$validtoken = true;
			}
		}
		if ($validtoken) {
			$q = "SELECT * FROM `#__vikbooking_custfields` ORDER BY `#__vikbooking_custfields`.`ordering` ASC;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$cfields = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : "";
			$suffdata = true;
			$useremail = "";
			$usercountry = '';
			$nominatives = array();
			$t_first_name = '';
			$t_last_name = '';
			$phone_number = '';
			if (@ is_array($cfields)) {
				$vbo_tn->translateContents($cfields, '#__vikbooking_custfields');
				foreach ($cfields as $cf) {
					if (intval($cf['required']) == 1 && $cf['type'] != 'separator') {
						$tmpcfval = JRequest::getString('vbf' . $cf['id'], '', 'request');
						if (strlen(str_replace(" ", "", trim($tmpcfval))) <= 0) {
							$suffdata = false;
							break;
						}
					}
				}
				//save user email, nominatives, phone number and create custdata array
				$arrcustdata = array();
				$arrcfields = array();
				$emailwasfound = false;
				foreach ($cfields as $cf) {
					if (intval($cf['isemail']) == 1 && $emailwasfound == false) {
						$useremail = trim(JRequest::getString('vbf' . $cf['id'], '', 'request'));
						$emailwasfound = true;
					}
					if ($cf['isnominative'] == 1) {
						$tmpcfval = JRequest::getString('vbf' . $cf['id'], '', 'request');
						if (strlen(str_replace(" ", "", trim($tmpcfval))) > 0) {
							$nominatives[] = $tmpcfval;
						}
					}
					if ($cf['isphone'] == 1) {
						$tmpcfval = JRequest::getString('vbf' . $cf['id'], '', 'request');
						if (strlen(str_replace(" ", "", trim($tmpcfval))) > 0) {
							$phone_number = $tmpcfval;
						}
					}
					if($cf['type'] != 'separator' && $cf['type'] != 'country' && ( $cf['type'] != 'checkbox' || ($cf['type'] == 'checkbox' && intval($cf['required']) != 1) ) ) {
						$arrcustdata[JText::_($cf['name'])] = JRequest::getString('vbf' . $cf['id'], '', 'request');
						$arrcfields[$cf['id']] = JRequest::getString('vbf' . $cf['id'], '', 'request');
					}elseif ($cf['type'] == 'country') {
						$countryval = JRequest::getString('vbf' . $cf['id'], '', 'request');
						if (!empty($countryval) && strstr($countryval, '::') !== false) {
							$countryparts = explode('::', $countryval);
							$usercountry = $countryparts[0];
							$arrcustdata[JText::_($cf['name'])] = $countryparts[1];
						}else {
							$arrcustdata[JText::_($cf['name'])] = '';
						}
					}
				}
				//
			}
			if(!empty($phone_number) && !empty($usercountry)) {
				$phone_number = vikbooking::checkPhonePrefixCountry($phone_number, $usercountry);
			}
			if ($suffdata === true) {
				if(count($nominatives) >= 2) {
					$t_last_name = array_pop($nominatives);
					$t_first_name = array_pop($nominatives);
				}
				if (vikbooking::dayValidTs($pdays, $pcheckin, $pcheckout)) {
					$currencyname = vikbooking::getCurrencyName();
					$rooms = array();
					$prices = array();
					$arrpeople = array();
					for($ir = 1; $ir <= $proomsnum; $ir++) {
						$ind = $ir - 1;
						if (!empty($prooms[$ind])) {
							$q = "SELECT * FROM `#__vikbooking_rooms` WHERE `id`='".intval($prooms[$ind])."' AND `avail`='1';";
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
						$prices[$ir] = $ppriceid[$ind];
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
						if(count($pkg) > 0) {
							break;
						}
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
					$is_package = (bool)(count($pkg) > 0);
					if ($validfares === true) {
						$isdue = 0;
						$tot_taxes = 0;
						$tot_city_taxes = 0;
						$tot_fees = 0;
						if($is_package === true) {
							foreach($rooms as $num => $r) {
								$pkg_cost = $pkg['pernight_total'] == 1 ? ($pkg['cost'] * $daysdiff) : $pkg['cost'];
								$pkg_cost = $pkg['perperson'] == 1 ? ($pkg_cost * ($arrpeople[$num]['adults'] > 0 ? $arrpeople[$num]['adults'] : 1)) : $pkg_cost;
								$cost_plus_tax = vikbooking::sayPackagePlusIva($pkg_cost, $pkg['idiva']);
								$isdue += $cost_plus_tax;
								if($cost_plus_tax == $pkg_cost) {
									$cost_minus_tax = vikbooking::sayPackageMinusIva($pkg_cost, $pkg['idiva']);
									$tot_taxes += ($pkg_cost - $cost_minus_tax);
								}else {
									$tot_taxes += ($cost_plus_tax - $pkg_cost);
								}
							}
						}else {
							foreach($tars as $num => $tar) {
								$cost_plus_tax = vikbooking::sayCostPlusIva($tar[0]['cost'], $tar[0]['idprice']);
								$isdue += $cost_plus_tax;
								if($cost_plus_tax == $tar[0]['cost']) {
									$cost_minus_tax = vikbooking::sayCostMinusIva($tar[0]['cost'], $tar[0]['idprice']);
									$tot_taxes += ($tar[0]['cost'] - $cost_minus_tax);
								}else {
									$tot_taxes += ($cost_plus_tax - $tar[0]['cost']);
								}
							}
						}
						$selopt = array();
						$optstr = array();
						$children_age = array();
						if (!empty ($poptionals)) {
							$stepo = explode(";", $poptionals);
							foreach ($stepo as $oo) {
								if (!empty ($oo)) {
									$stept = explode(":", $oo);
									$rnoid = explode("_", $stept[0]);
									$q = "SELECT * FROM `#__vikbooking_optionals` WHERE `id`=" . $dbo->quote($rnoid[1]) . ";";
									$dbo->setQuery($q);
									$dbo->Query($q);
									if ($dbo->getNumRows() == 1) {
										$actopt = $dbo->loadAssocList();
										$vbo_tn->translateContents($actopt, '#__vikbooking_optionals');
										$chvar = '';
										if (!empty($actopt[0]['ageintervals']) && $arrpeople[$rnoid[0]]['children'] > 0 && strstr($stept[1], '-') != false) {
											$optagecosts = vikbooking::getOptionIntervalsCosts($actopt[0]['ageintervals']);
											$optagenames = vikbooking::getOptionIntervalsAges($actopt[0]['ageintervals']);
											$agestept = explode('-', $stept[1]);
											$stept[1] = $agestept[0];
											$chvar = $agestept[1];
											$actopt[0]['chageintv'] = $chvar;
											$actopt[0]['name'] .= ' ('.$optagenames[($chvar - 1)].')';
											$actopt[0]['quan'] = $stept[1];
											$selopt[$rnoid[0]][] = $actopt[0];
											$selopt['room'.$rnoid[0]] = $selopt['room'.$rnoid[0]].$actopt[0]['id'].":".$stept[1]."-".$chvar.";";
											$realcost = (intval($actopt[0]['perday']) == 1 ? (floatval($optagecosts[($chvar - 1)]) * $pdays * $stept[1]) : (floatval($optagecosts[($chvar - 1)]) * $stept[1]));
											$children_age[$rnoid[0]][] = array('ageinterval' => $optagenames[($chvar - 1)], 'age' => '', 'cost' => $realcost);
										}else {
											$actopt[0]['quan'] = $stept[1];
											$selopt[$rnoid[0]][] = $actopt[0];
											$selopt['room'.$rnoid[0]] = $selopt['room'.$rnoid[0]].$actopt[0]['id'].":".$stept[1].";";
											$realcost = (intval($actopt[0]['perday']) == 1 ? ($actopt[0]['cost'] * $pdays * $stept[1]) : ($actopt[0]['cost'] * $stept[1]));
										}
										if (!empty($actopt[0]['maxprice']) && $actopt[0]['maxprice'] > 0 && $realcost > $actopt[0]['maxprice']) {
											$realcost = $actopt[0]['maxprice'];
											if(intval($actopt[0]['hmany']) == 1 && intval($stept[1]) > 1) {
												$realcost = $actopt[0]['maxprice'] * $stept[1];
											}
										}
										$realcost = ($actopt[0]['perperson'] == 1 ? ($realcost * $arrpeople[$rnoid[0]]['adults']) : $realcost);
										$tmpopr = vikbooking::sayOptionalsPlusIva($realcost, $actopt[0]['idiva']);
										if ($actopt[0]['is_citytax'] == 1) {
											$tot_city_taxes += $tmpopr;
										}elseif ($actopt[0]['is_fee'] == 1) {
											$tot_fees += $tmpopr;
										}else {
											if($tmpopr == $realcost) {
												$opt_minus_iva = vikbooking::sayOptionalsMinusIva($realcost, $actopt[0]['idiva']);
												$tot_taxes += ($realcost - $opt_minus_iva);
											}else {
												$tot_taxes += ($tmpopr - $realcost);
											}
										}
										$isdue += $tmpopr;
										$optstr[$rnoid[0]][] = ($stept[1] > 1 ? $stept[1] . " " : "") . $actopt[0]['name'] . ": " . $tmpopr . " " . $currencyname . "\n";
									}
								}
							}
						}
						$origtotdue = $isdue;
						$usedcoupon = false;
						//coupon
						if(strlen($pcouponcode) > 0 && $is_package !== true) {
							$coupon = vikbooking::getCouponInfo($pcouponcode);
							if(is_array($coupon)) {
								$coupondateok = true;
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
								if($coupondateok === true) {
									$couponroomok = true;
									if($coupon['allvehicles'] == 0) {
										foreach($rooms as $num => $r) {
											if(!(preg_match("/;".$r['id'].";/i", $coupon['idrooms']))) {
												$couponroomok = false;
												break;
											}
										}
									}
									if($couponroomok === true) {
										$coupontotok = true;
										if(strlen($coupon['mintotord']) > 0) {
											if($isdue < $coupon['mintotord']) {
												$coupontotok = false;
											}
										}
										if($coupontotok === true) {
											$usedcoupon = true;
											if($coupon['percentot'] == 1) {
												//percent value
												$minuscoupon = 100 - $coupon['value'];
												$coupondiscount = ($isdue - $tot_city_taxes - $tot_fees) * $coupon['value'] / 100;
												$isdue = ($isdue - $tot_taxes - $tot_city_taxes - $tot_fees) * $minuscoupon / 100;
												$tot_taxes = $tot_taxes * $minuscoupon / 100;
												$isdue += ($tot_taxes + $tot_city_taxes + $tot_fees);
											}else {
												//total value
												$coupondiscount = $coupon['value'];
												//isdue : taxes = coupon_discount : x
												$tax_prop = $tot_taxes * $coupon['value'] / $isdue;
												$tot_taxes -= $tax_prop;
												$tot_taxes = $tot_taxes < 0 ? 0 : $tot_taxes;
												$isdue -= $coupon['value'];
												$isdue = $isdue < 0 ? 0 : $isdue;
											}
											$strcouponeff = $coupon['id'].';'.$coupondiscount.';'.$coupon['code'];
										}
									}
								}
							}
						}
						//
						$strisdue = number_format($isdue, 2)."vikbooking";
						$ptotdue = number_format($ptotdue, 2)."vikbooking";
						if ($strisdue == $ptotdue) {
							$nowts = time();
							$checkts = $nowts;
							$today_bookings = vikbooking::todayBookings();
							if($today_bookings) {
								$checkts = mktime(0, 0, 0, date('n'), date('j'), date('Y'));
							}
							if ($checkts <= $pcheckin && $checkts < $pcheckout && $pcheckin < $pcheckout) {
								$roomsavailable = true;
								foreach($rooms as $num => $r) {
									if (!vikbooking::roomNotLocked($r['id'], $r['units'], $pcheckin, $pcheckout)) {
										$roomsavailable = false;
										break;
									}
									if (!vikbooking::roomBookable($r['id'], $r['units'], $pcheckin, $pcheckout)) {
										$roomsavailable = false;
										break;
									}
								}
								if ($roomsavailable === true) {
									//save in session the checkin and checkout time of the reservation made
									$session->set('vikbooking_order_checkin', $pcheckin);
									$session->set('vikbooking_order_checkout', $pcheckout);
									//
									$sid = vikbooking::getSecretLink();
									$custdata = vikbooking::buildCustData($arrcustdata, "\r\n");
									$viklink = JURI::root() . "index.php?option=com_vikbooking&task=vieworder&sid=" . $sid . "&ts=" . $nowts . (!empty ($pitemid) ? "&Itemid=" . $pitemid : "");
									$admail = vikbooking::getAdminMail();
									$ftitle = vikbooking::getFrontTitle();
									$pricestr = array();
									if($is_package === true) {
										foreach($rooms as $num => $r) {
											$pkg_cost = $pkg['pernight_total'] == 1 ? ($pkg['cost'] * $daysdiff) : $pkg['cost'];
											$pkg_cost = $pkg['perperson'] == 1 ? ($pkg_cost * ($arrpeople[$num]['adults'] > 0 ? $arrpeople[$num]['adults'] : 1)) : $pkg_cost;
											$cost_plus_tax = vikbooking::sayPackagePlusIva($pkg_cost, $pkg['idiva']);
											$pricestr[$num] = $pkg['name'].": ".$cost_plus_tax." ".$currencyname;
										}
									}else {
										foreach($tars as $num => $tar) {
											$pricestr[$num] = vikbooking::getPriceName($tar[0]['idprice'], $vbo_tn) . ": " . vikbooking::sayCostPlusIva($tar[0]['cost'], $tar[0]['idprice'])  . " " . $currencyname . (!empty ($tar[0]['attrdata']) ? "\n" . vikbooking::getPriceAttr($tar[0]['idprice'], $vbo_tn) . ": " . $tar[0]['attrdata'] : "");
										}
									}
									$currentUser = JFactory::getUser();
									//VikBooking 1.5/1.6
									$langtag = $vbo_tn->current_lang;
									$vcmchanneldata = $session->get('vcmChannelData', '');
									$vcmchanneldata = !empty($vcmchanneldata) && is_array($vcmchanneldata) && count($vcmchanneldata) > 0 ? $vcmchanneldata : '';
									$cpin = vikbooking::getCPinIstance();
									$cpin->saveCustomerDetails($t_first_name, $t_last_name, $useremail, $phone_number, $usercountry, $arrcfields);
									//
									if (vikbooking::areTherePayments()) {
										$payment = vikbooking::getPayment($pgpayid);
										$realback = vikbooking::getHoursRoomAvail() * 3600;
										$realback += $pcheckout;
										if (is_array($payment)) {
											if (intval($payment['setconfirmed']) == 1) {
												$arrbusy = array();
												foreach($rooms as $num => $r) {
													$q = "INSERT INTO `#__vikbooking_busy` (`idroom`,`checkin`,`checkout`,`realback`) VALUES('" . $r['id'] . "', " . $dbo->quote($pcheckin) . ", " . $dbo->quote($pcheckout) . ",'" . $realback . "');";
													$dbo->setQuery($q);
													$dbo->Query($q);
													$lid = $dbo->insertid();
													$arrbusy[$num] = $lid;
												}
												$q = "INSERT INTO `#__vikbooking_orders` (`custdata`,`ts`,`status`,`days`,`checkin`,`checkout`,`custmail`,`sid`,`idpayment`,`ujid`,`coupon`,`roomsnum`,`total`,`channel`,`lang`,`country`,`tot_taxes`,`tot_city_taxes`,`tot_fees`,`phone`,`pkg`) VALUES(" . $dbo->quote($custdata) . ",'" . $nowts . "','confirmed'," . $dbo->quote($pdays) . "," . $dbo->quote($pcheckin) . "," . $dbo->quote($pcheckout) . "," . $dbo->quote($useremail) . ",'" . $sid . "'," . $dbo->quote($payment['id'] . '=' . $payment['name']) . ",'".$currentUser->id."',".($usedcoupon === true ? $dbo->quote($strcouponeff) : "NULL").",'".count($rooms)."','".$isdue."',".(is_array($vcmchanneldata) ? $dbo->quote($vcmchanneldata['name']) : 'NULL')."," . $dbo->quote($langtag) . ",".(!empty($usercountry) ? $dbo->quote($usercountry) : 'NULL').",'".$tot_taxes."','".$tot_city_taxes."','".$tot_fees."', ".$dbo->quote($phone_number).", ".($is_package === true ? (int)$pkg['id'] : "NULL").");";
												$dbo->setQuery($q);
												$dbo->Query($q);
												$neworderid = $dbo->insertid();
												//ConfirmationNumber and Customer Booking
												$confirmnumber = vikbooking::generateConfirmNumber($neworderid, true);
												$cpin->saveCustomerBooking($neworderid);
												//end ConfirmationNumber and Customer Booking
												//Assign room specific unit
												$set_room_indexes = vikbooking::autoRoomUnit();
												$room_indexes_usemap = array();
												//
												foreach($rooms as $num => $r) {
													$q = "INSERT INTO `#__vikbooking_ordersbusy` (`idorder`,`idbusy`) VALUES('".$neworderid."', '".$arrbusy[$num]."');";
													$dbo->setQuery($q);
													$dbo->Query($q);
													$json_ch_age = '';
													if (array_key_exists($num, $children_age)) {
														$json_ch_age = json_encode($children_age[$num]);
													}
													//Assign room specific unit
													$room_indexes = $set_room_indexes === true ? vikbooking::getRoomUnitNumsAvailable(array('id' => $neworderid, 'checkin' => $pcheckin, 'checkout' => $pcheckout), $r['id']) : array();
													$use_ind_key = 0;
													if(count($room_indexes)) {
														if(!array_key_exists($r['id'], $room_indexes_usemap)) {
															$room_indexes_usemap[$r['id']] = $use_ind_key;
														}else {
															$use_ind_key = $room_indexes_usemap[$r['id']];
														}
														$rooms[$num]['roomindex'] = (int)$room_indexes[$use_ind_key];
													}
													//
													$pkg_cost = 0;
													if($is_package === true) {
														$pkg_cost = $pkg['pernight_total'] == 1 ? ($pkg['cost'] * $daysdiff) : $pkg['cost'];
														$pkg_cost = $pkg['perperson'] == 1 ? ($pkg_cost * ($arrpeople[$num]['adults'] > 0 ? $arrpeople[$num]['adults'] : 1)) : $pkg_cost;
														$pkg_cost = vikbooking::sayPackagePlusIva($pkg_cost, $pkg['idiva']);
													}
													$q = "INSERT INTO `#__vikbooking_ordersrooms` (`idorder`,`idroom`,`adults`,`children`,`idtar`,`optionals`,`childrenage`,`t_first_name`,`t_last_name`,`roomindex`,`pkg_id`,`pkg_name`,`cust_cost`,`cust_idiva`) VALUES('".$neworderid."', '".$r['id']."', '".$arrpeople[$num]['adults']."', '".$arrpeople[$num]['children']."', '".$tars[$num][0]['id']."', '".$selopt['room'.$num]."', ".(!empty($json_ch_age) ? $dbo->quote($json_ch_age) : 'NULL').", ".$dbo->quote($t_first_name).", ".$dbo->quote($t_last_name).", ".(count($room_indexes) ? (int)$room_indexes[$use_ind_key] : "NULL").", ".($is_package === true ? (int)$pkg['id'].", ".$dbo->quote($pkg['name']).", ".$dbo->quote($pkg_cost).", ".intval($pkg['idiva']) : "NULL, NULL, NULL, NULL").");";
													$dbo->setQuery($q);
													$dbo->Query($q);
													if(count($room_indexes)) {
														$room_indexes_usemap[$r['id']]++;
													}
													//
												}
												if($usedcoupon === true && $coupon['type'] == 2) {
													$q="DELETE FROM `#__vikbooking_coupons` WHERE `id`='".$coupon['id']."';";
													$dbo->setQuery($q);
													$dbo->Query($q);
												}
												vikbooking::sendAdminMail($admail.';_;'.$useremail, JText::sprintf('VBNEWORDER', $neworderid), $ftitle, $nowts, $custdata, $rooms, $pcheckin, $pcheckout, $pricestr, $optstr, $isdue, JText::_('VBCOMPLETED'), $payment['name'], $strcouponeff, $arrpeople, $confirmnumber);
												vikbooking::sendCustMail($useremail, strip_tags($ftitle) . " " . JText::_('VBORDNOL'), $ftitle, $nowts, $custdata, $rooms, $pcheckin, $pcheckout, $pricestr, $optstr, $isdue, $viklink, JText::_('VBCOMPLETED'), $neworderid, $strcouponeff, $arrpeople, $confirmnumber);
												//SMS
												vikbooking::sendBookingSMS($neworderid);
												//
												//invoke VikChannelManager
												if (file_exists(JPATH_SITE . DS ."components". DS ."com_vikchannelmanager". DS . "helpers" . DS ."synch.vikbooking.php")) {
													require_once(JPATH_SITE . DS ."components". DS ."com_vikchannelmanager". DS . "helpers" . DS ."synch.vikbooking.php");
													$vcm = new synchVikBooking($neworderid);
													$vcm->sendRequest();
												}
												//end invoke VikChannelManager
												$mainframe = JFactory::getApplication();
												$mainframe->redirect("index.php?option=com_vikbooking&task=vieworder&sid=" . $sid . "&ts=" . $nowts . (!empty ($pitemid) ? "&Itemid=" . $pitemid : ""));
											} else {
												$q = "INSERT INTO `#__vikbooking_orders` (`custdata`,`ts`,`status`,`days`,`checkin`,`checkout`,`custmail`,`sid`,`totpaid`,`idpayment`,`ujid`,`coupon`,`roomsnum`,`total`,`channel`,`lang`,`country`,`tot_taxes`,`tot_city_taxes`,`tot_fees`,`phone`,`pkg`) VALUES(" . $dbo->quote($custdata) . ",'" . $nowts . "','standby'," . $dbo->quote($pdays) . "," . $dbo->quote($pcheckin) . "," . $dbo->quote($pcheckout) . "," . $dbo->quote($useremail) . ",'" . $sid . "',''," . $dbo->quote($payment['id'] . '=' . $payment['name']) . ",'".$currentUser->id."',".($usedcoupon == true ? $dbo->quote($strcouponeff) : "NULL").",'".count($rooms)."','".$isdue."',".(is_array($vcmchanneldata) ? $dbo->quote($vcmchanneldata['name']) : 'NULL')."," . $dbo->quote($langtag) . ",".(!empty($usercountry) ? $dbo->quote($usercountry) : 'NULL').",'".$tot_taxes."','".$tot_city_taxes."','".$tot_fees."', ".$dbo->quote($phone_number).", ".($is_package === true ? (int)$pkg['id'] : "NULL").");";
												$dbo->setQuery($q);
												$dbo->Query($q);
												$neworderid = $dbo->insertid();
												//Customer Booking
												$cpin->saveCustomerBooking($neworderid);
												//end Customer Booking
												foreach($rooms as $num => $r) {
													$json_ch_age = '';
													if (array_key_exists($num, $children_age)) {
														$json_ch_age = json_encode($children_age[$num]);
													}
													$pkg_cost = 0;
													if($is_package === true) {
														$pkg_cost = $pkg['pernight_total'] == 1 ? ($pkg['cost'] * $daysdiff) : $pkg['cost'];
														$pkg_cost = $pkg['perperson'] == 1 ? ($pkg_cost * ($arrpeople[$num]['adults'] > 0 ? $arrpeople[$num]['adults'] : 1)) : $pkg_cost;
														$pkg_cost = vikbooking::sayPackagePlusIva($pkg_cost, $pkg['idiva']);
													}
													$q = "INSERT INTO `#__vikbooking_ordersrooms` (`idorder`,`idroom`,`adults`,`children`,`idtar`,`optionals`,`childrenage`,`t_first_name`,`t_last_name`,`pkg_id`,`pkg_name`,`cust_cost`,`cust_idiva`) VALUES('".$neworderid."', '".$r['id']."', '".$arrpeople[$num]['adults']."', '".$arrpeople[$num]['children']."', '".$tars[$num][0]['id']."', '".$selopt['room'.$num]."', ".(!empty($json_ch_age) ? $dbo->quote($json_ch_age) : 'NULL').", ".$dbo->quote($t_first_name).", ".$dbo->quote($t_last_name).", ".($is_package === true ? (int)$pkg['id'].", ".$dbo->quote($pkg['name']).", ".$dbo->quote($pkg_cost).", ".intval($pkg['idiva']) : "NULL, NULL, NULL, NULL").");";
													$dbo->setQuery($q);
													$dbo->Query($q);
												}
												if($usedcoupon === true && $coupon['type'] == 2) {
													$q="DELETE FROM `#__vikbooking_coupons` WHERE `id`='".$coupon['id']."';";
													$dbo->setQuery($q);
													$dbo->Query($q);
												}
												foreach($rooms as $num => $r) {
													$q = "INSERT INTO `#__vikbooking_tmplock` (`idroom`,`checkin`,`checkout`,`until`,`realback`,`idorder`) VALUES('" . $r['id'] . "'," . $dbo->quote($pcheckin) . "," . $dbo->quote($pcheckout) . ",'" . vikbooking::getMinutesLock(true) . "','" . $realback . "', ".(int)$neworderid.");";
													$dbo->setQuery($q);
													$dbo->Query($q);
												}
												vikbooking::sendAdminMail($admail.';_;'.$useremail, JText::sprintf('VBNEWORDER', $neworderid), $ftitle, $nowts, $custdata, $rooms, $pcheckin, $pcheckout, $pricestr, $optstr, $isdue, JText::_('VBINATTESA'), $payment['name'], $strcouponeff, $arrpeople);
												vikbooking::sendCustMail($useremail, strip_tags($ftitle) . " " . JText::_('VBORDNOL'), $ftitle, $nowts, $custdata, $rooms, $pcheckin, $pcheckout, $pricestr, $optstr, $isdue, $viklink, JText::_('VBINATTESA'), $neworderid, $strcouponeff, $arrpeople);
												$mainframe = JFactory::getApplication();
												$mainframe->redirect("index.php?option=com_vikbooking&task=vieworder&sid=" . $sid . "&ts=" . $nowts . (!empty ($pitemid) ? "&Itemid=" . $pitemid : ""));
											}
										} else {
											//error, payment was not selected
											JError::raiseWarning('', JText::_('ERRSELECTPAYMENT'));
											$strpriceid = "";
											foreach($prices as $num => $pid) {
												$strpriceid .= "&priceid".$num."=".$pid;
											}
											$stroptid = "";
											for($ir = 1; $ir <= $proomsnum; $ir++) {
												if (is_array($selopt[$ir])) {
													foreach($selopt[$ir] as $opt) {
														if (array_key_exists('chageintv', $opt)) {
															$stroptid .= "&optid".$ir.$opt['id']."[]=".$opt['chageintv'];
														}else {
															$stroptid .= "&optid".$ir.$opt['id']."=".$opt['quan'];
														}
													}
												}
											}
											$strroomid = "";
											foreach($rooms as $num => $r) {
												$strroomid .= "&roomid[]=".$r['id'];
											}
											$straduchild = "";
											foreach($arrpeople as $indroom => $aduch) {
												$straduchild .= "&adults[]=".$aduch['adults'];
												$straduchild .= "&children[]=".$aduch['children'];
											}
											$mainframe = JFactory::getApplication();
											$mainframe->redirect("index.php?option=com_vikbooking&task=oconfirm".$strpriceid.$stroptid.$strroomid.$straduchild."&roomsnum=".$proomsnum."&days=".$pdays."&checkin=".$pcheckin."&checkout=".$pcheckout.(!empty($pitemid) ? "&Itemid=".$pitemid : ""));
										}
									} else {
										$realback = vikbooking::getHoursRoomAvail() * 3600;
										$realback += $pcheckout;
										$arrbusy = array();
										foreach($rooms as $num => $r) {
											$q = "INSERT INTO `#__vikbooking_busy` (`idroom`,`checkin`,`checkout`,`realback`) VALUES('" . $r['id'] . "', " . $dbo->quote($pcheckin) . ", " . $dbo->quote($pcheckout) . ",'" . $realback . "');";
											$dbo->setQuery($q);
											$dbo->Query($q);
											$lid = $dbo->insertid();
											$arrbusy[$num] = $lid;
										}
										$q = "INSERT INTO `#__vikbooking_orders` (`custdata`,`ts`,`status`,`days`,`checkin`,`checkout`,`custmail`,`sid`,`totpaid`,`ujid`,`coupon`,`roomsnum`,`total`,`channel`,`lang`,`country`,`tot_taxes`,`tot_city_taxes`,`tot_fees`,`phone`,`pkg`) VALUES(" . $dbo->quote($custdata) . ",'" . $nowts . "','confirmed'," . $dbo->quote($pdays) . "," . $dbo->quote($pcheckin) . "," . $dbo->quote($pcheckout) . "," . $dbo->quote($useremail) . ",'" . $sid . "'," . $dbo->quote($isdue) . ",'".$currentUser->id."',".($usedcoupon == true ? $dbo->quote($strcouponeff) : "NULL").",'".count($rooms)."','".$isdue."',".(is_array($vcmchanneldata) ? $dbo->quote($vcmchanneldata['name']) : 'NULL')."," . $dbo->quote($langtag) . ",".(!empty($usercountry) ? $dbo->quote($usercountry) : 'NULL').",'".$tot_taxes."','".$tot_city_taxes."','".$tot_fees."', ".$dbo->quote($phone_number).", ".($is_package === true ? (int)$pkg['id'] : "NULL").");";
										$dbo->setQuery($q);
										$dbo->Query($q);
										$neworderid = $dbo->insertid();
										//ConfirmationNumber and Customer Booking
										$confirmnumber = vikbooking::generateConfirmNumber($neworderid, true);
										$cpin->saveCustomerBooking($neworderid);
										//end ConfirmationNumber and Customer Booking
										//Assign room specific unit
										$set_room_indexes = vikbooking::autoRoomUnit();
										$room_indexes_usemap = array();
										//
										foreach($rooms as $num => $r) {
											$q = "INSERT INTO `#__vikbooking_ordersbusy` (`idorder`,`idbusy`) VALUES('".$neworderid."', '".$arrbusy[$num]."');";
											$dbo->setQuery($q);
											$dbo->Query($q);
											$json_ch_age = '';
											if (array_key_exists($num, $children_age)) {
												$json_ch_age = json_encode($children_age[$num]);
											}
											//Assign room specific unit
											$room_indexes = $set_room_indexes === true ? vikbooking::getRoomUnitNumsAvailable(array('id' => $neworderid, 'checkin' => $pcheckin, 'checkout' => $pcheckout), $r['id']) : array();
											$use_ind_key = 0;
											if(count($room_indexes)) {
												if(!array_key_exists($r['id'], $room_indexes_usemap)) {
													$room_indexes_usemap[$r['id']] = $use_ind_key;
												}else {
													$use_ind_key = $room_indexes_usemap[$r['id']];
												}
												$rooms[$num]['roomindex'] = (int)$room_indexes[$use_ind_key];
											}
											//
											$pkg_cost = 0;
											if($is_package === true) {
												$pkg_cost = $pkg['pernight_total'] == 1 ? ($pkg['cost'] * $daysdiff) : $pkg['cost'];
												$pkg_cost = $pkg['perperson'] == 1 ? ($pkg_cost * ($arrpeople[$num]['adults'] > 0 ? $arrpeople[$num]['adults'] : 1)) : $pkg_cost;
												$pkg_cost = vikbooking::sayPackagePlusIva($pkg_cost, $pkg['idiva']);
											}
											$q = "INSERT INTO `#__vikbooking_ordersrooms` (`idorder`,`idroom`,`adults`,`children`,`idtar`,`optionals`,`childrenage`,`t_first_name`,`t_last_name`,`roomindex`,`pkg_id`,`pkg_name`,`cust_cost`,`cust_idiva`) VALUES('".$neworderid."', '".$r['id']."', '".$arrpeople[$num]['adults']."', '".$arrpeople[$num]['children']."', '".$tars[$num][0]['id']."', '".$selopt['room'.$num]."', ".(!empty($json_ch_age) ? $dbo->quote($json_ch_age) : 'NULL').", ".$dbo->quote($t_first_name).", ".$dbo->quote($t_last_name).", ".(count($room_indexes) ? (int)$room_indexes[$use_ind_key] : "NULL").", ".($is_package === true ? (int)$pkg['id'].", ".$dbo->quote($pkg['name']).", ".$dbo->quote($pkg_cost).", ".intval($pkg['idiva']) : "NULL, NULL, NULL, NULL").");";
											$dbo->setQuery($q);
											$dbo->Query($q);
											if(count($room_indexes)) {
												$room_indexes_usemap[$r['id']]++;
											}
											//
										}
										if($usedcoupon == true && $coupon['type'] == 2) {
											$q="DELETE FROM `#__vikbooking_coupons` WHERE `id`='".$coupon['id']."';";
											$dbo->setQuery($q);
											$dbo->Query($q);
										}
										vikbooking::sendAdminMail($admail.';_;'.$useremail, JText::sprintf('VBNEWORDER', $neworderid), $ftitle, $nowts, $custdata, $rooms, $pcheckin, $pcheckout, $pricestr, $optstr, $isdue, JText::_('VBCOMPLETED'), "", $strcouponeff, $arrpeople, $confirmnumber);
										vikbooking::sendCustMail($useremail, strip_tags($ftitle) . " " . JText::_('VBORDNOL'), $ftitle, $nowts, $custdata, $rooms, $pcheckin, $pcheckout, $pricestr, $optstr, $isdue, $viklink, JText::_('VBCOMPLETED'), $neworderid, $strcouponeff, $arrpeople, $confirmnumber);
										//SMS
										vikbooking::sendBookingSMS($neworderid);
										//
										//invoke VikChannelManager
										if (file_exists(JPATH_SITE . DS ."components". DS ."com_vikchannelmanager". DS . "helpers" . DS ."synch.vikbooking.php")) {
											require_once(JPATH_SITE . DS ."components". DS ."com_vikchannelmanager". DS . "helpers" . DS ."synch.vikbooking.php");
											$vcm = new synchVikBooking($neworderid);
											$vcm->sendRequest();
										}
										//end invoke VikChannelManager
										echo vikbooking::getFullFrontTitle();
										?>
										<h3 class="successmade"><?php echo JText::_('VBTHANKSONE'); ?></h3>
										<br />
										<p class="vbo-static-thankyou-p"><?php echo JText::_('VBTHANKSTWO'); ?> <a href="<?php echo $viklink; ?>"><?php echo JText::_('VBTHANKSTHREE'); ?></a></p>
										<?php
									}
								}else {
									showSelectVb(JText::_('VBROOMBOOKEDBYOTHER'));
								}
							}else {
								showSelectVb(JText::_('VBINVALIDDATES'));
							}
						}else {
							showSelectVb(JText::_('VBINCONGRTOT'));
						}
					}else {
						showSelectVb(JText::_('VBINCONGRDATAREC'));
					}
				} else {
					showSelectVb(JText::_('VBINCONGRDATA'));
				}
			} else {
				showSelectVb(JText::_('VBINSUFDATA'));
			}
		} else {
			showSelectVb(JText::_('VBINVALIDTOKEN'));
		}
	}

	function vieworder() {
		$sid = JRequest::getString('sid', '', 'request');
		$ts = JRequest::getString('ts', '', 'request');
		if (!empty ($sid) && !empty ($ts)) {
			$dbo = JFactory::getDBO();
			$q = "SELECT `o`.*,(SELECT SUM(`or`.`adults`) FROM `#__vikbooking_ordersrooms` AS `or` WHERE `or`.`idorder`=`o`.`id`) AS `tot_adults` FROM `#__vikbooking_orders` AS `o` WHERE `o`.`sid`=" . $dbo->quote($sid) . " AND `o`.`ts`=" . $dbo->quote($ts) . ";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() == 1) {
				$order = $dbo->loadAssocList();
				if ($order[0]['status'] == "confirmed") {
					//prepare impression data for channels
					$impressiondata = $order[0];
					$q="SELECT `or`.`idtar`,`d`.`idprice`,`p`.`idiva`,`t`.`aliq` FROM `#__vikbooking_ordersrooms` AS `or` " .
						"LEFT JOIN `#__vikbooking_dispcost` `d` ON `d`.`id`=`or`.`idtar` " . 
						"LEFT JOIN `#__vikbooking_prices` `p` ON `p`.`id`=`d`.`idprice` " . 
						"LEFT JOIN `#__vikbooking_iva` `t` ON `t`.`id`=`p`.`idiva` " . 
						"WHERE `or`.`idorder`='".$order[0]['id']."' ORDER BY `t`.`aliq` ASC LIMIT 1;";
					$dbo->setQuery($q);
					$dbo->Query($q);
					if ($dbo->getNumRows() == 1) {
						$taxdata = $dbo->loadAssocList();
						$taxes = 0;
						if (!empty($taxdata[0]['aliq'])) {
							$realtotal = round(($order[0]['total'] * (100 - $taxdata[0]['aliq']) / 100), 2);
							$taxes = round(($order[0]['total'] - $realtotal), 2);
							$impressiondata['total'] = $realtotal;
						}
						$impressiondata['taxes'] = $taxes;
						$impressiondata['fees'] = 0;
					}
					vikbooking::invokeChannelManager(true, $impressiondata);
					//end prepare impression data for channels
					JRequest::setVar('view', 'confirmedorder');
					parent::display();
				}else {
					$roomavail = true;
					$q="SELECT `or`.*,`r`.`units` FROM `#__vikbooking_ordersrooms` AS `or`, `#__vikbooking_rooms` AS `r` WHERE `or`.`idorder`='".$order[0]['id']."' AND `or`.`idroom`=`r`.`id`;";
					$dbo->setQuery($q);
					$dbo->Query($q);
					if ($dbo->getNumRows() > 0) {
						$orderrooms = $dbo->loadAssocList();
						foreach($orderrooms as $or) {
							$roomavail = vikbooking::roomBookable($or['idroom'], $or['units'], $order[0]['checkin'], $order[0]['checkout']);
							if (!$roomavail) {
								break;
							}
						}
					}else {
						$roomavail = false;
					}
					$today_midnight = mktime(0, 0, 0, date('n'), date('j'), date('Y'));
					if ($today_midnight > $order[0]['checkin']) {
						$roomavail = false;
					}
					if ($roomavail == true) {
						//SHOW PAYMENT FORM
						vikbooking::invokeChannelManager(false);
						JRequest::setVar('view', 'standbyorder');
						parent::display();
					}else {
						$q = "DELETE FROM `#__vikbooking_orders` WHERE `id`='" . $order[0]['id'] . "' LIMIT 1;";
						$dbo->setQuery($q);
						$dbo->Query($q);
						$q="SELECT * FROM `#__vikbooking_ordersbusy` WHERE `idorder`='".$order[0]['id']."';";
						$dbo->setQuery($q);
						$dbo->Query($q);
						if ($dbo->getNumRows() > 0) {
							$allbusy = $dbo->loadAssocList();
							foreach($allbusy as $b) {
								$q="DELETE FROM `#__vikbooking_busy` WHERE `id`='" . $b['idbusy'] . "' LIMIT 1;";
								$dbo->setQuery($q);
								$dbo->Query($q);
							}
						}
						$q="DELETE FROM `#__vikbooking_ordersrooms` WHERE `idorder`='".$order[0]['id']."';";
						$dbo->setQuery($q);
						$dbo->Query($q);
						if ($today_midnight > $order[0]['checkin']) {
							showSelectVb("");
						}else {
							showSelectVb(JText::_('VBERRREPSEARCH'));
						}
					}
				}
			}else {
				showSelectVb(JText::_('VBORDERNOTFOUND'));
			}
		} else {
			showSelectVb(JText::_('VBINSUFDATA'));
		}
	}
	
	function cancelrequest() {
		$psid = JRequest::getString('sid', '', 'request');
		$pidorder = JRequest::getString('idorder', '', 'request');
		$dbo = JFactory::getDBO();
		if (!empty($psid) && !empty($pidorder)) {
			$q = "SELECT * FROM `#__vikbooking_orders` WHERE `id`=".intval($pidorder)." AND `sid`=".$dbo->quote($psid).";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() == 1) {
				$order = $dbo->loadAssocList();
				$pemail = JRequest::getString('email', '', 'request');
				$preason = JRequest::getString('reason', '', 'request');
				if (!empty($pemail) && !empty($preason)) {
					$to = vikbooking::getAdminMail();
					$subject = JText::_('VBCANCREQUESTEMAILSUBJ');
					//$subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
					$msg = JText::sprintf('VBCANCREQUESTEMAILHEAD', $order[0]['id'], JURI::root().'index.php?option=com_vikbooking&task=vieworder&sid='.$order[0]['sid'].'&ts='.$order[0]['ts'])."\n\n".$preason;
					$mailer = JFactory::getMailer();
					$sender = array($pemail, $pemail);
					$mailer->setSender($sender);
					$mailer->addRecipient($to);
					$mailer->addReplyTo($pemail);
					$mailer->setSubject($subject);
					$mailer->setBody($msg);
					$mailer->isHTML(false);
					$mailer->Encoding = 'base64';
					$mailer->Send();
					$app = JFactory::getApplication();
					$app->enqueueMessage(JText::_('VBCANCREQUESTMAILSENT'));
					$mainframe = JFactory::getApplication();
					$mainframe->redirect("index.php?option=com_vikbooking&task=vieworder&sid=".$order[0]['sid']."&ts=".$order[0]['ts']."&Itemid=".JRequest::getString('Itemid', '', 'request'));
				}else {
					$mainframe = JFactory::getApplication();
					$mainframe->redirect("index.php?option=com_vikbooking&task=vieworder&sid=".$order[0]['sid']."&ts=".$order[0]['ts']);
				}
			}else {
				$mainframe = JFactory::getApplication();
				$mainframe->redirect("index.php");
			}
		}else {
			$mainframe = JFactory::getApplication();
			$mainframe->redirect("index.php");
		}
	}

	function reqinfo() {
		$proomid = JRequest::getInt('roomid', '', 'request');
		$preqinfotoken = JRequest::getInt('reqinfotoken', '', 'request');
		$pitemid = JRequest::getInt('Itemid', '', 'request');
		$dbo = JFactory::getDBO();
		$session = JFactory::getSession();
		$mainframe = JFactory::getApplication();
		if (!empty($proomid)) {
			$q = "SELECT `id`,`name` FROM `#__vikbooking_rooms` WHERE `id`=".(int)$proomid.";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() == 1) {
				$room = $dbo->loadAssocList();
				$goto = JRoute::_('index.php?option=com_vikbooking&view=roomdetails&roomid='.$room[0]['id'].'&Itemid='.$pitemid, false);
				$preqname = JRequest::getString('reqname', '', 'request');
				$preqemail = JRequest::getString('reqemail', '', 'request');
				$preqmess = JRequest::getString('reqmess', '', 'request');
				if (!empty($preqemail) && !empty($preqmess)) {
					$sesstoken = $session->get('vboreqinfo'.$room[0]['id'], '');
					if((int)$sesstoken == (int)$preqinfotoken) {
						$session->set('vboreqinfo'.$room[0]['id'], '');
						$to = vikbooking::getAdminMail();
						$subject = JText::sprintf('VBOROOMREQINFOSUBJ', $room[0]['name']);
						$msg = JText::_('VBOROOMREQINFONAME').": ".$preqname."\n\n".JText::_('VBOROOMREQINFOEMAIL').": ".$preqemail."\n\n".JText::_('VBOROOMREQINFOMESS').":\n\n".$preqmess;
						$mailer = JFactory::getMailer();
						$sender = array($preqemail, $preqemail);
						$mailer->setSender($sender);
						$mailer->addRecipient($to);
						$mailer->addReplyTo($preqemail);
						$mailer->setSubject($subject);
						$mailer->setBody($msg);
						$mailer->isHTML(false);
						$mailer->Encoding = 'base64';
						$mailer->Send();
						$mainframe->enqueueMessage(JText::_('VBOROOMREQINFOSENTOK'));
					}else {
						JError::raiseWarning('', JText::_('VBOROOMREQINFOTKNERR'));
					}
					$mainframe->redirect($goto);
				}else {
					JError::raiseWarning('', JText::_('VBOROOMREQINFOMISSFIELD'));
					$mainframe->redirect($goto);
				}
			}else {
				$mainframe->redirect("index.php");
			}
		}else {
			$mainframe->redirect("index.php");
		}
	}

	function cron_exec() {
		$dbo = JFactory::getDBO();
		$pcron_id = JRequest::getInt('cron_id', '', 'request');
		$pcronkey = JRequest::getString('cronkey', '', 'request');
		if(empty($pcron_id) || empty($pcronkey)) {
			die('Error[1]');
		}
		if($pcronkey != md5(vikbooking::getCronKey())) {
			die('Error[2]');
		}
		$q = "SELECT * FROM `#__vikbooking_cronjobs` WHERE `id`=".$dbo->quote($pcron_id)." AND `published`=1;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() != 1) {
			die('Error[3]');
		}
		$cron_data = $dbo->loadAssoc();
		if(!file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'cronjobs'.DS.$cron_data['class_file'])) {
			die('Error[4]');
		}
		require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'cronjobs'.DS.$cron_data['class_file']);
		$cron_obj = new VikCronJob($cron_data['id'], json_decode($cron_data['params'], true));
		$run_res = $cron_obj->run();
		$cron_obj->afterRun();
		echo intval($run_res);
		die;
	}
	
	function notifypayment() {
		$psid = JRequest::getString('sid', '', 'request');
		$pts = JRequest::getString('ts', '', 'request');
		$dbo = JFactory::getDBO();
		$nowdf = vikbooking::getDateFormat();
		if ($nowdf == "%d/%m/%Y") {
			$df = 'd/m/Y';
		} elseif ($nowdf == "%m/%d/%Y") {
			$df = 'm/d/Y';
		} else {
			$df = 'Y/m/d';
		}
		if (strlen($psid) && strlen($pts)) {
			$admail = vikbooking::getAdminMail();
			$q = "SELECT * FROM `#__vikbooking_orders` WHERE `ts`=" . $dbo->quote($pts) . " AND `sid`=" . $dbo->quote($psid) . ";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() > 0) {
				$rows = $dbo->loadAssocList();
				//check if the language in use is the same as the one used during the checkout
				if (!empty($rows[0]['lang'])) {
					$lang = JFactory::getLanguage();
					if($lang->getTag() != $rows[0]['lang']) {
						$lang->load('com_vikbooking', JPATH_SITE, $rows[0]['lang'], true);
					}
				}
				//
				$vbo_tn = vikbooking::getTranslator();
				if($rows[0]['status']!='confirmed' || (vikbooking::multiplePayments() && $rows[0]['paymcount'] > 0)) {
					$rows[0]['admin_email'] = $admail;
					$realback = vikbooking::getHoursRoomAvail() * 3600;
					$realback += $rows[0]['checkout'];
					$currencyname = vikbooking::getCurrencyName();
					$ftitle = vikbooking::getFrontTitle();
					$nowts = time();
					$viklink = JURI::root() . "index.php?option=com_vikbooking&task=vieworder&sid=" . $psid . "&ts=" . $pts;
					$rooms = array();
					$tars = array();
					$arrpeople = array();
					$is_package = !empty($rows[0]['pkg']) ? true : false;
					$q="SELECT `or`.`id` AS `or_id`,`or`.`idroom`,`or`.`adults`,`or`.`children`,`or`.`idtar`,`or`.`optionals`,`or`.`roomindex`,`or`.`pkg_id`,`or`.`pkg_name`,`or`.`cust_cost`,`or`.`cust_idiva`,`r`.`id` AS `r_reference_id`,`r`.`name`,`r`.`img`,`r`.`idcarat`,`r`.`fromadult`,`r`.`toadult`,`r`.`params` FROM `#__vikbooking_ordersrooms` AS `or`,`#__vikbooking_rooms` AS `r` WHERE `or`.`idorder`='".$rows[0]['id']."' AND `or`.`idroom`=`r`.`id` ORDER BY `or`.`id` ASC;";
					$dbo->setQuery($q);
					$dbo->Query($q);
					if ($dbo->getNumRows() > 0) {
						$orderrooms = $dbo->loadAssocList();
						$vbo_tn->translateContents($orderrooms, '#__vikbooking_rooms', array('id' => 'r_reference_id'));
						foreach($orderrooms as $kor => $or) {
							$num = $kor + 1;
							$rooms[$num] = $or;
							$arrpeople[$num]['adults'] = $or['adults'];
							$arrpeople[$num]['children'] = $or['children'];
							if($is_package === true || (!empty($or['cust_cost']) && $or['cust_cost'] > 0.00)) {
								//package or custom cost set from the back-end
								continue;
							}
							$q = "SELECT * FROM `#__vikbooking_dispcost` WHERE `id`='" . $or['idtar'] . "';";
							$dbo->setQuery($q);
							$dbo->Query($q);
							if ($dbo->getNumRows() == 1) {
								$tar = $dbo->loadAssocList();
								$tar = vikbooking::applySeasonsRoom($tar, $rows[0]['checkin'], $rows[0]['checkout']);
								//different usage
								if ($or['fromadult'] <= $or['adults'] && $or['toadult'] >= $or['adults']) {
									$diffusageprice = vikbooking::loadAdultsDiff($or['idroom'], $or['adults']);
									//Occupancy Override
									$occ_ovr = vikbooking::occupancyOverrideExists($tar, $or['adults']);
									$diffusageprice = $occ_ovr !== false ? $occ_ovr : $diffusageprice;
									//
									if (is_array($diffusageprice)) {
										//set a charge or discount to the price(s) for the different usage of the room
										foreach($tar as $kpr => $vpr) {
											$tar[$kpr]['diffusage'] = $or['adults'];
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
								$tars[$num] = $tar[0];
							}
						}
					}
					$rows[0]['order_rooms'] = $orderrooms;
					$rows[0]['fares'] = $tars;
					$isdue = 0;
					$tot_taxes = 0;
					$tot_city_taxes = 0;
					$tot_fees = 0;
					$pricestr = array();
					$optstr = array();
					foreach($orderrooms as $kor => $or) {
						$num = $kor + 1;
						if($is_package === true || (!empty($or['cust_cost']) && $or['cust_cost'] > 0.00)) {
							//package cost or cust_cost should always be inclusive of taxes
							$calctar = $or['cust_cost'];
							$isdue += $calctar;
							if($calctar == $or['cust_cost']) {
								$cost_minus_tax = vikbooking::sayPackageMinusIva($or['cust_cost'], $or['cust_idiva']);
								$tot_taxes += ($or['cust_cost'] - $cost_minus_tax);
							}else {
								$tot_taxes += ($calctar - $or['cust_cost']);
							}
							$pricestr[$num] = (!empty($or['pkg_name']) ? $or['pkg_name'] : JText::_('VBOROOMCUSTRATEPLAN')).": ".$calctar." ".$currencyname;
						}elseif (array_key_exists($num, $tars) && is_array($tars[$num])) {
							$calctar = vikbooking::sayCostPlusIva($tars[$num]['cost'], $tars[$num]['idprice']);
							$tars[$num]['calctar'] = $calctar;
							$isdue += $calctar;
							if($calctar == $tars[$num]['cost']) {
								$cost_minus_tax = vikbooking::sayCostMinusIva($tars[$num]['cost'], $tars[$num]['idprice']);
								$tot_taxes += ($tars[$num]['cost'] - $cost_minus_tax);
							}else {
								$tot_taxes += ($calctar - $tars[$num]['cost']);
							}
							$pricestr[$num] = vikbooking::getPriceName($tars[$num]['idprice'], $vbo_tn) . ": " . $calctar . " " . $currencyname . (!empty ($tars[$num]['attrdata']) ? "\n" . vikbooking::getPriceAttr($tars[$num]['idprice'], $vbo_tn) . ": " . $tars[$num]['attrdata'] : "");
						}
						if (!empty ($or['optionals'])) {
							$stepo = explode(";", $or['optionals']);
							foreach ($stepo as $oo) {
								if (!empty ($oo)) {
									$stept = explode(":", $oo);
									$q = "SELECT * FROM `#__vikbooking_optionals` WHERE `id`=" . $dbo->quote($stept[0]) . ";";
									$dbo->setQuery($q);
									$dbo->Query($q);
									if ($dbo->getNumRows() == 1) {
										$actopt = $dbo->loadAssocList();
										$vbo_tn->translateContents($actopt, '#__vikbooking_optionals');
										$chvar = '';
										if (!empty($actopt[0]['ageintervals']) && $or['children'] > 0 && strstr($stept[1], '-') != false) {
											$optagecosts = vikbooking::getOptionIntervalsCosts($actopt[0]['ageintervals']);
											$optagenames = vikbooking::getOptionIntervalsAges($actopt[0]['ageintervals']);
											$agestept = explode('-', $stept[1]);
											$stept[1] = $agestept[0];
											$chvar = $agestept[1];
											$actopt[0]['chageintv'] = $chvar;
											$actopt[0]['name'] .= ' ('.$optagenames[($chvar - 1)].')';
											$actopt[0]['quan'] = $stept[1];
											$realcost = (intval($actopt[0]['perday']) == 1 ? (floatval($optagecosts[($chvar - 1)]) * $rows[0]['days'] * $stept[1]) : (floatval($optagecosts[($chvar - 1)]) * $stept[1]));
										}else {
											$actopt[0]['quan'] = $stept[1];
											$realcost = (intval($actopt[0]['perday']) == 1 ? ($actopt[0]['cost'] * $rows[0]['days'] * $stept[1]) : ($actopt[0]['cost'] * $stept[1]));
										}
										if (!empty ($actopt[0]['maxprice']) && $actopt[0]['maxprice'] > 0 && $realcost > $actopt[0]['maxprice']) {
											$realcost = $actopt[0]['maxprice'];
											if(intval($actopt[0]['hmany']) == 1 && intval($stept[1]) > 1) {
												$realcost = $actopt[0]['maxprice'] * $stept[1];
											}
										}
										if ($actopt[0]['perperson'] == 1) {
											$realcost = $realcost * $or['adults'];
										}
										$tmpopr = vikbooking::sayOptionalsPlusIva($realcost, $actopt[0]['idiva']);
										if ($actopt[0]['is_citytax'] == 1) {
											$tot_city_taxes += $tmpopr;
										}elseif ($actopt[0]['is_fee'] == 1) {
											$tot_fees += $tmpopr;
										}else {
											if($tmpopr == $realcost) {
												$opt_minus_tax = vikbooking::sayOptionalsMinusIva($realcost, $actopt[0]['idiva']);
												$tot_taxes += ($realcost - $opt_minus_tax);
											}else {
												$tot_taxes += ($tmpopr - $realcost);
											}
										}
										$isdue += $tmpopr;
										$optstr[$num][] = ($stept[1] > 1 ? $stept[1] . " " : "") . $actopt[0]['name'] . ": " . $tmpopr . " " . $currencyname . "\n";
									}
								}
							}
						}
					}
					//vikbooking 1.1 coupon
					$usedcoupon = false;
					$origisdue = $isdue;
					if(strlen($rows[0]['coupon']) > 0) {
						$usedcoupon = true;
						$expcoupon = explode(";", $rows[0]['coupon']);
						$isdue = $isdue - $expcoupon[1];
					}
					//
					//invoke the payment method class
					$exppay = explode('=', $rows[0]['idpayment']);
					$payment = vikbooking::getPayment($exppay[0], $vbo_tn);
					require_once(JPATH_ADMINISTRATOR . DS ."components". DS ."com_vikbooking". DS . "payments" . DS . $payment['file']);
					$obj = new vikBookingPayment($rows[0], json_decode($payment['params'], true));
					$array_result = $obj->validatePayment();
					$newpaymentlog = date('c')."\n".$array_result['log']."\n----------\n".$rows[0]['paymentlog'];
					if ($array_result['verified'] == 1) {
						//valid payment
						$shouldpay = $isdue;
						if ($payment['charge'] > 0.00) {
							if($payment['ch_disc'] == 1) {
								//charge
								if($payment['val_pcent'] == 1) {
									//fixed value
									$shouldpay += $payment['charge'];
								}else {
									//percent value
									$percent_to_pay = $shouldpay * $payment['charge'] / 100;
									$shouldpay += $percent_to_pay;
								}
							}else {
								//discount
								if($payment['val_pcent'] == 1) {
									//fixed value
									$shouldpay -= $payment['charge'];
								}else {
									//percent value
									$percent_to_pay = $shouldpay * $payment['charge'] / 100;
									$shouldpay -= $percent_to_pay;
								}
							}
						}
						if (!vikbooking::payTotal()) {
							$percentdeposit = vikbooking::getAccPerCent();
							if ($percentdeposit > 0) {
								if(vikbooking::getTypeDeposit() == "fixed") {
									$shouldpay = $percentdeposit;
								}else {
									$shouldpay = $shouldpay * $percentdeposit / 100;
								}
							}
						}
						//check if the total amount paid is the same as the total order
						if(array_key_exists('tot_paid', $array_result)) {
							$shouldpay = round($shouldpay, 2);
							$totreceived = round($array_result['tot_paid'], 2);
							if($shouldpay != $totreceived && $rows[0]['paymcount'] == 0) {
								//the amount paid is different than the total order
								//fares might have changed or the deposit might be different
								//Sending just an email to the admin that will check
								$mailer = JFactory::getMailer();
								$adsendermail = vikbooking::getSenderMail();
								$sender = array($adsendermail, $adsendermail);
								$mailer->setSender($sender);
								$mailer->addRecipient($admail);
								$mailer->addReplyTo($adsendermail);
								$mailer->setSubject(JText::_('VBTOTPAYMENTINVALID'));
								$mailer->setBody(JText::sprintf('VBTOTPAYMENTINVALIDTXT', $rows[0]['id'], $totreceived." (".$array_result['tot_paid'].")", $shouldpay));
								$mailer->isHTML(false);
								$mailer->Encoding = 'base64';
								$mailer->Send();
							}
						}
						//
						if($rows[0]['paymcount'] == 0) {
							foreach($orderrooms as $indnum => $r) {
								$num = $indnum + 1;
								$q = "INSERT INTO `#__vikbooking_busy` (`idroom`,`checkin`,`checkout`,`realback`) VALUES('" . $r['idroom'] . "', '" . $rows[0]['checkin'] . "', '" . $rows[0]['checkout'] . "','" . $realback . "');";
								$dbo->setQuery($q);
								$dbo->Query($q);
								$lid = $dbo->insertid();
								$q = "INSERT INTO `#__vikbooking_ordersbusy` (`idorder`,`idbusy`) VALUES('".$rows[0]['id']."', '".$lid."');";
								$dbo->setQuery($q);
								$dbo->Query($q);
							}
						}
						//ConfirmationNumber
						if($rows[0]['paymcount'] == 0) {
							$confirmnumber = vikbooking::generateConfirmNumber($rows[0]['id'], true);
						}
						//end ConfirmationNumber
						$q = "UPDATE `#__vikbooking_orders` SET `status`='confirmed'" . ($array_result['tot_paid'] ? ", `totpaid`='" . ($array_result['tot_paid'] + $rows[0]['totpaid']) . "', `paymcount`=".($rows[0]['paymcount'] + 1) : "") . (!empty($array_result['log']) ? ", `paymentlog`=".$dbo->quote($newpaymentlog) : "") . " WHERE `id`='" . $rows[0]['id'] . "';";
						$dbo->setQuery($q);
						$dbo->Query($q);
						//Assign room specific unit
						$set_room_indexes = vikbooking::autoRoomUnit();
						$room_indexes_usemap = array();
						if($set_room_indexes === true) {
							$q = "SELECT `id`,`idroom` FROM `#__vikbooking_ordersrooms` WHERE `idorder`=".(int)$rows[0]['id'].";";
							$dbo->setQuery($q);
							$dbo->Query($q);
							$orooms = $dbo->loadAssocList();
							foreach ($orooms as $oroom) {
								//Assign room specific unit
								$room_indexes = vikbooking::getRoomUnitNumsAvailable($rows[0], $oroom['idroom']);
								$use_ind_key = 0;
								if(count($room_indexes)) {
									if(!array_key_exists($oroom['idroom'], $room_indexes_usemap)) {
										$room_indexes_usemap[$oroom['idroom']] = $use_ind_key;
									}else {
										$use_ind_key = $room_indexes_usemap[$oroom['idroom']];
									}
									$q = "UPDATE `#__vikbooking_ordersrooms` SET `roomindex`=".(int)$room_indexes[$use_ind_key]." WHERE `id`=".(int)$oroom['id'].";";
									$dbo->setQuery($q);
									$dbo->Query($q);
									//update rooms references for the customer email sending function
									foreach ($rooms as $rnum => $rr) {
										if($rr['or_id'] == $oroom['id']) {
											$rooms[$rnum]['roomindex'] = (int)$room_indexes[$use_ind_key];
											break;
										}
									}
									//
									$room_indexes_usemap[$oroom['idroom']]++;
								}
								//
							}
						}
						//
						//VikBooking 1.6 : unlock room(s) for other imminent bookings
						$q = "DELETE FROM `#__vikbooking_tmplock` WHERE `idorder`=" . intval($rows[0]['id']) . ";";
						$dbo->setQuery($q);
						$dbo->Query($q);
						//
						//send mails
						vikbooking::sendAdminMail($admail.';_;'.$rows[0]['custmail'], JText::sprintf('VBORDERPAYMENT', $rows[0]['id']), $ftitle, $nowts, $rows[0]['custdata'], $rooms, $rows[0]['checkin'], $rows[0]['checkout'], $pricestr, $optstr, $isdue, JText::_('VBCOMPLETED'), $payment['name'], $rows[0]['coupon'], $arrpeople, $confirmnumber);
						vikbooking::sendCustMail($rows[0]['custmail'], strip_tags($ftitle) . " " . JText::_('VBRENTALORD'), $ftitle, $nowts, $rows[0]['custdata'], $rooms, $rows[0]['checkin'], $rows[0]['checkout'], $pricestr, $optstr, $isdue, $viklink, JText::_('VBCOMPLETED'), $rows[0]['id'], $rows[0]['coupon'], $arrpeople, $confirmnumber);
						//SMS
						vikbooking::sendBookingSMS($rows[0]['id']);
						//
						//invoke VikChannelManager
						if (file_exists(JPATH_SITE . DS ."components". DS ."com_vikchannelmanager". DS . "helpers" . DS ."synch.vikbooking.php")) {
							require_once(JPATH_SITE . DS ."components". DS ."com_vikchannelmanager". DS . "helpers" . DS ."synch.vikbooking.php");
							$vcm = new synchVikBooking($rows[0]['id']);
							$vcm->sendRequest();
						}
						$session = JFactory::getSession();
						$vcmchanneldata = $session->get('vcmChannelData', '');
						if (!empty($vcmchanneldata)) {
							$session->set('vcmChannelData', '');
						}
						//end invoke VikChannelManager
						if(method_exists($obj, 'afterValidation')) {
							$obj->afterValidation(1);
						}
					} else {
						if(!array_key_exists('skip_email', $array_result) || $array_result['skip_email'] != 1) {
							$mailer = JFactory::getMailer();
							$adsendermail = vikbooking::getSenderMail();
							$sender = array($adsendermail, $adsendermail);
							$mailer->setSender($sender);
							$mailer->addRecipient($admail);
							$mailer->addReplyTo($adsendermail);
							$mailer->setSubject(JText::_('VBPAYMENTNOTVER'));
							$mailer->setBody(JText::_('VBSERVRESP') . ":\n\n" . $array_result['log']);
							$mailer->isHTML(false);
							$mailer->Encoding = 'base64';
							$mailer->Send();
						}
						if (!empty($array_result['log'])) {
							$q = "UPDATE `#__vikbooking_orders` SET `paymentlog`=".$dbo->quote($newpaymentlog)." WHERE `id`='" . $rows[0]['id'] . "';";
							$dbo->setQuery($q);
							$dbo->Query($q);
						}
						if(method_exists($obj, 'afterValidation')) {
							$obj->afterValidation(0);
						}
					}
				}
			}
		}
		return true;
	}
	
	function currencyconverter() {
		$session = JFactory::getSession();
		$pprices = JRequest::getVar('prices', array(0));
		$pfromsymbol = JRequest::getString('fromsymbol', '', 'request');
		$ptocurrency = JRequest::getString('tocurrency', '', 'request');
		$pfromcurrency = JRequest::getString('fromcurrency', '', 'request');
		$default_cur = !empty($pfromcurrency) ? $pfromcurrency : vikbooking::getCurrencyName();
		$response = array();
		if (!empty($default_cur) && !empty($pprices) && count($pprices) > 0 && !empty($ptocurrency)) {
			require_once(JPATH_SITE . DS ."components". DS ."com_vikbooking". DS . "helpers" . DS ."currencyconverter.php");
			if ($default_cur != $ptocurrency) {
				$format = vikbooking::getNumberFormatData();
				$converter = new vboCurrencyConverter($default_cur, $ptocurrency, $pprices, explode(':', $format));
				$exchanged = $converter->convert();
				if (count($exchanged) > 0) {
					$response = $exchanged;
					$session->set('vboLastCurrency', $ptocurrency);
				}else {
					$response['error'] = JText::_('VBERRCURCONVINVALIDDATA');
				}
			}else {
				$session->set('vboLastCurrency', $ptocurrency);
				foreach ($pprices as $i => $price) {
					$response[$i]['symbol'] = $pfromsymbol;
					$response[$i]['price'] = $price;
				}
			}
		}else {
			$response['error'] = JText::_('VBERRCURCONVNODATA');
		}
		if(array_key_exists('error', $response)) {
			$session->set('vboLastCurrency', $ptocurrency);
		}
		echo json_encode($response);
		exit;
	}

	function validatepin() {
		$ppin = JRequest::getString('pin', '', 'request');
		$cpin = vikbooking::getCPinIstance();
		$response = array();
		$customer = $cpin->getCustomerByPin($ppin);
		if(count($customer) > 0) {
			$response = $customer;
			$response['success'] = 1;
		}
		echo json_encode($response);
		exit;
	}
	
	function tac_av_l() {
		require_once(JPATH_SITE . DS ."components". DS ."com_vikbooking". DS . "helpers" . DS ."tac.vikbooking.php");
		TACVBO::tac_av_l();
	}
	
}
?>