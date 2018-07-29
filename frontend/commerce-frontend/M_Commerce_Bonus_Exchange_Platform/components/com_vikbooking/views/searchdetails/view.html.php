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

class VikbookingViewSearchdetails extends JViewLegacy {
	function display($tpl = null) {
		$vbo_tn = vikbooking::getTranslator();
		$proomid = JRequest::getInt('roomid', '', 'request');
		$pcheckin = JRequest::getInt('checkin', '', 'request');
		$pcheckout = JRequest::getInt('checkout', '', 'request');
		$padults = JRequest::getInt('adults', '', 'request');
		$pchildren = JRequest::getInt('children', '', 'request');
		$dbo = JFactory::getDBO();
		$q = "SELECT * FROM `#__vikbooking_rooms` WHERE `id`=".$dbo->quote($proomid)." AND `avail`='1';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() == 1) {
			$room=$dbo->loadAssocList();
			$vbo_tn->translateContents($room, '#__vikbooking_rooms');
			$secdiff = $pcheckout - $pcheckin;
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
					}else {
						$daysdiff = ceil($daysdiff);
					}
				}
			}
			$q="SELECT * FROM `#__vikbooking_dispcost` WHERE `idroom`='".$room[0]['id']."' AND `days`='".$daysdiff."' ORDER BY `#__vikbooking_dispcost`.`cost` ASC LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if($dbo->getNumRows() == 1) {
				$tar=$dbo->loadAssocList();
			}else {
				$q="SELECT * FROM `#__vikbooking_dispcost` WHERE `idroom`='".$room[0]['id']."' ORDER BY `#__vikbooking_dispcost`.`cost` ASC LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if($dbo->getNumRows() == 1) {
					$tar=$dbo->loadAssocList();
					$tar[0]['cost']=($tar[0]['cost'] / $tar[0]['days']);
				}else {
					$tar[0]['cost']=0;
				}
			}
			$tar = vikbooking::applySeasonsRoom($tar, $pcheckin, $pcheckout);
			//different usage
			if ($room[0]['fromadult'] <= $padults && $room[0]['toadult'] >= $padults) {
				$diffusageprice = vikbooking::loadAdultsDiff($room[0]['id'], $padults);
				//Occupancy Override
				$occ_ovr = vikbooking::occupancyOverrideExists($tar, $padults);
				$diffusageprice = $occ_ovr !== false ? $occ_ovr : $diffusageprice;
				//
				if (is_array($diffusageprice)) {
					//set a charge or discount to the price for the different usage of the room
					foreach($tar as $kpr => $vpr) {
						$tar[$kpr]['diffusage'] = $padults;
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
				}elseif($room[0]['toadult'] > $padults) {
					$tar[0]['diffusage'] = $padults;
				}
			}
			//
			$this->assignRef('room', $room[0]);
			$this->assignRef('tar', $tar);
			$this->assignRef('checkin', $pcheckin);
			$this->assignRef('checkout', $pcheckout);
			$this->assignRef('adults', $padults);
			$this->assignRef('children', $pchildren);
			$this->assignRef('daysdiff', $daysdiff);
			$this->assignRef('vbo_tn', $vbo_tn);
			//theme
			$theme = vikbooking::getTheme();
			if($theme != 'default') {
				$thdir = JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'themes'.DS.$theme.DS.'searchdetails';
				if(is_dir($thdir)) {
					$this->_setPath('template', $thdir.DS);
				}
			}
			//
			parent::display($tpl);
		}else {
			$mainframe = JFactory::getApplication();
			$mainframe->redirect("index.php?option=com_vikbooking&view=roomslist");
		}
	}
}
?>