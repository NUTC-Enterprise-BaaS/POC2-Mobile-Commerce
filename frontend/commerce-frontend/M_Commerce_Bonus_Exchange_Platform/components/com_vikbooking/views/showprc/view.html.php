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

class VikbookingViewShowprc extends JViewLegacy {
	function display($tpl = null) {
		$vbo_tn = vikbooking::getTranslator();
		$proomopt = JRequest::getVar('roomopt', array());
		$pdays = JRequest::getString('days', '', 'request');
		$pcheckin = JRequest::getInt('checkin', '', 'request');
		$pcheckout = JRequest::getInt('checkout', '', 'request');
		$padults = JRequest::getVar('adults', array());
		$pchildren = JRequest::getVar('children', array());
		$proomsnum = JRequest::getInt('roomsnum', '', 'request');
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
			if (!empty($proomopt[$ind])) {
				$q = "SELECT * FROM `#__vikbooking_rooms` WHERE `id`='".intval($proomopt[$ind])."' AND `avail`='1';";
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
			JError::raiseWarning('', JText::_('VBERRSELECTINGROOMS'));
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
		//check that room(s) are available
		$groupdays = vikbooking::getGroupDays($pcheckin, $pcheckout, $daysdiff);
		$morehst = vikbooking::getHoursRoomAvail() * 3600;
		$goonunits = true;
		$rooms_counts = array();
		foreach($rooms as $num => $r) {
			$check = "SELECT `id`,`checkin`,`checkout` FROM `#__vikbooking_busy` WHERE `idroom`='" . $r['id'] . "' AND `checkout`>".time().";";
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
						$goonunits = false;
						break;
					}
				}
			}
			$rooms_counts[$r['id']]['name'] = $r['name'];
			$rooms_counts[$r['id']]['units'] = $r['units'];
			$rooms_counts[$r['id']]['count'] = empty($rooms_counts[$r['id']]['count']) ? 1 : ($rooms_counts[$r['id']]['count'] + 1);
		}
		if ($goonunits) {
			foreach ($rooms_counts as $idr => $unitused) {
				if ($unitused['count'] > $unitused['units']) {
					JError::raiseWarning('', JText::sprintf('VBERRROOMUNITSNOTAVAIL', $unitused['count'], $unitused['name']));
					$mainframe = JFactory::getApplication();
					$mainframe->redirect(JRoute::_('index.php?option=com_vikbooking'));
					$goonunits = false;
					break;
				}
			}
		}
		//
		if ($goonunits) {
			$tars = array();
			$aretherefares = true;
			foreach($rooms as $num => $r) {
				$q = "SELECT * FROM `#__vikbooking_dispcost` WHERE `days`='" . $daysdiff . "' AND `idroom`='" . $r['id'] . "' ORDER BY `#__vikbooking_dispcost`.`cost` ASC;";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if ($dbo->getNumRows() > 0) {
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
					$aretherefares = false;
					break;
				}
			}
			if ($aretherefares === true) {
				if (vikbooking::dayValidTs($pdays, $pcheckin, $pcheckout)) {
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
					$this->assignRef('tars', $tars);
					$this->assignRef('rooms', $rooms);
					$this->assignRef('roomsnum', $proomsnum);
					$this->assignRef('arrpeople', $arrpeople);
					$this->assignRef('checkin', $pcheckin);
					$this->assignRef('checkout', $pcheckout);
					$this->assignRef('days', $daysdiff);
					$this->assignRef('pkg', $pkg);
					$this->assignRef('vbo_tn', $vbo_tn);
					//theme
					$theme = vikbooking::getTheme();
					if($theme != 'default') {
						$thdir = JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'themes'.DS.$theme.DS.'showprc';
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
				showSelectVb(JText::_('VBNOTARFNDSELO'));
			}
		}else {
			showSelectVb(JText::_('VBROOMNOTRIT') . " " . date($df . ' H:i', $pcheckin) . " " . JText::_('VBROOMNOTCONSTO') . " " . date($df . ' H:i', $pcheckout));
		}
	}
}
?>