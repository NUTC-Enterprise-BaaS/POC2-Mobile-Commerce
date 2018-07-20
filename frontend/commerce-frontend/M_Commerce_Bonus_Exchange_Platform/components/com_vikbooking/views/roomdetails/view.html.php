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

class VikbookingViewRoomdetails extends JViewLegacy {
	function display($tpl = null) {
		vikbooking::prepareViewContent();
		$proomid = JRequest::getString('roomid', '', 'request');
		$dbo = JFactory::getDBO();
		$vbo_tn = vikbooking::getTranslator();
		$q = "SELECT * FROM `#__vikbooking_rooms` WHERE `id`=".$dbo->quote($proomid)." AND `avail`='1';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() == 1) {
			$room=$dbo->loadAssocList();
			$vbo_tn->translateContents($room, '#__vikbooking_rooms');
			$q="SELECT `id`,`cost` FROM `#__vikbooking_dispcost` WHERE `idroom`=".$dbo->quote($room[0]['id'])." AND `days`='1' ORDER BY `#__vikbooking_dispcost`.`cost` ASC LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if($dbo->getNumRows() == 1) {
				$tar=$dbo->loadAssocList();
				$room[0]['cost']=$tar[0]['cost'];
			}else {
				$q="SELECT `id`,`days`,`cost` FROM `#__vikbooking_dispcost` WHERE `idroom`=".$dbo->quote($room[0]['id'])." ORDER BY `#__vikbooking_dispcost`.`cost` ASC LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if($dbo->getNumRows() == 1) {
					$tar=$dbo->loadAssocList();
					$room[0]['cost']=($tar[0]['cost'] / $tar[0]['days']);
				}else {
					$room[0]['cost']=0;
				}
			}
			$actnow = mktime(0, 0, 0, date('m'), 1, date('Y'));
			$q="SELECT * FROM `#__vikbooking_busy` WHERE `idroom`='".$room[0]['id']."' AND (`checkin`>=".$actnow." OR `checkout`>=".$actnow.");";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() > 0) {
				$busy = $dbo->loadAssocList();
			}else {
				$busy="";
			}
			//seasons calendar
			$seasons_cal = array();
			$use_seasons_cal = vikbooking::getRoomParam('seasoncal', $room[0]['params']);
			$seasons_cal_nights = explode(',', vikbooking::getRoomParam('seasoncal_nights', $room[0]['params']));
			$seasons_cal_nights = vikbooking::filterNightsSeasonsCal($seasons_cal_nights);
			if(intval($use_seasons_cal) > 0 && count($seasons_cal_nights) > 0) {
				$q = "SELECT * FROM `#__vikbooking_seasons` WHERE `idrooms` LIKE '%-".$room[0]['id']."-%'".($use_seasons_cal == 2 ? " AND `promo`=0" : ($use_seasons_cal == 3 ? " AND `type`=1 AND `promo`=0" : "")).";";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if($dbo->getNumRows() > 0) {
					$seasons = $dbo->loadAssocList();
					$vbo_tn->translateContents($seasons, '#__vikbooking_seasons');
					$q = "SELECT `p`.*,`tp`.`name`,`tp`.`attr`,`tp`.`idiva`,`tp`.`breakfast_included`,`tp`.`free_cancellation`,`tp`.`canc_deadline` FROM `#__vikbooking_dispcost` AS `p` LEFT JOIN `#__vikbooking_prices` `tp` ON `p`.`idprice`=`tp`.`id` WHERE `p`.`days` IN (".implode(',', $seasons_cal_nights).") AND `p`.`idroom`=".$room[0]['id']." ORDER BY `p`.`days` ASC, `p`.`cost` ASC;";
					$dbo->setQuery($q);
					$dbo->Query($q);
					if($dbo->getNumRows() > 0) {
						$tars = $dbo->loadAssocList();
						$vbo_tn->translateContents($tars, '#__vikbooking_prices', array('id' => 'idprice'));
						$arrtar = array();
						foreach ($tars as $tar) {
							$arrtar[$tar['days']][] = $tar;
						}
						//Restrictions
						$all_restrictions = vikbooking::loadRestrictions(true, array($room[0]['id']));
						$all_seasons = array();
						$curtime = time();
						foreach ($seasons as $sk => $s) {
							$now_year = !empty($s['year']) ? $s['year'] : date('Y');
							list($sfrom, $sto) = vikbooking::getSeasonRangeTs($s['from'], $s['to'], $now_year);
							if($sto < $curtime && empty($s['year'])) {
								$now_year += 1;
								list($sfrom, $sto) = vikbooking::getSeasonRangeTs($s['from'], $s['to'], $now_year);
							}
							if($sto >= $curtime) {
								$s['from_ts'] = $sfrom;
								$s['to_ts'] = $sto;
								$all_seasons[] = $s;
							}
						}
						if(count($all_seasons) > 0) {
							$vbo_df = vikbooking::getDateFormat();
							$vbo_df = $vbo_df == "%d/%m/%Y" ? 'd/m/Y' : ($vbo_df == "%m/%d/%Y" ? 'm/d/Y' : 'Y/m/d');
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
							$seasons_cal['nights'] = $seasons_cal_nights;
							$seasons_cal['offseason'] = $arrtar;
							$all_seasons = vikbooking::sortSeasonsRangeTs($all_seasons);
							$seasons_cal['seasons'] = $all_seasons;
							$seasons_cal['season_prices'] = array();
							$seasons_cal['restrictions'] = array();
							//calc price changes for each season and for each num-night
							foreach ($all_seasons as $sk => $s) {
								$checkin_base_ts = $s['from_ts'];
								$is_dst = date('I', $checkin_base_ts);
								foreach ($arrtar as $numnights => $tar) {
									$checkout_base_ts = $s['to_ts'];
									for($i = 1; $i <= $numnights; $i++) {
										$checkout_base_ts += 86400;
										$is_now_dst = date('I', $checkout_base_ts);
										if ($is_dst != $is_now_dst) {
											if ((int)$is_dst == 1) {
												$checkout_base_ts += 3600;
											}else {
												$checkout_base_ts -= 3600;
											}
											$is_dst = $is_now_dst;
										}
									}
									//calc check-in and check-out ts for the two dates
									$first = vikbooking::getDateTimestamp(date($vbo_df, $checkin_base_ts), $hcheckin, $mcheckin);
									$second = vikbooking::getDateTimestamp(date($vbo_df, $checkout_base_ts), $hcheckout, $mcheckout);
									$tar = vikbooking::applySeasonsRoom($tar, $first, $second, $s);
									$seasons_cal['season_prices'][$sk][$numnights] = $tar;
									//Restrictions
									if(count($all_restrictions) > 0) {
										$season_restr = vikbooking::parseSeasonRestrictions($first, $second, $numnights, $all_restrictions);
										if(count($season_restr) > 0) {
											$seasons_cal['restrictions'][$sk][$numnights] = $season_restr;
										}
									}
								}
							}
						}
					}
				}
			}
			//end seasons calendar
			//promotion min number of nights
			$ppromo = JRequest::getInt('promo', 0, 'request');
			$promo_season = array();
			if($ppromo > 0) {
				$q = "SELECT * FROM `#__vikbooking_seasons` WHERE `id`=".(int)$ppromo." AND `promo`=1;";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if($dbo->getNumRows() > 0) {
					$promo_season = $dbo->loadAssoc();
					$vbo_tn->translateContents($promo_season, '#__vikbooking_seasons');
				}
			}
			//
			$custptitle = vikbooking::getRoomParam('custptitle', $room[0]['params']);
			$custptitlew = vikbooking::getRoomParam('custptitlew', $room[0]['params']);
			$metakeywords = vikbooking::getRoomParam('metakeywords', $room[0]['params']);
			$metadescription = vikbooking::getRoomParam('metadescription', $room[0]['params']);
			$document = JFactory::getDocument();
			if(!empty($custptitle)) {
				$ctitlewhere = !empty($custptitlew) ? $custptitlew : 'before';
				$set_title = $custptitle.' - '.$document->getTitle();
				if($ctitlewhere == 'after') {
					$set_title = $document->getTitle().' - '.$custptitle;
				}elseif($ctitlewhere == 'replace') {
					$set_title = $custptitle;
				}
				$document->setTitle($set_title);
			}
			if(!empty($metakeywords)) {
				$document->setMetaData('keywords', $metakeywords);
			}
			if(!empty($metadescription)) {
				$document->setMetaData('description', $metadescription);
			}
			//OpenGraph Tags
			if(!empty($custptitle)) {
				$document->setMetaData('og:title', $set_title);
			}
			if(!empty($room[0]['img'])) {
				$document->setMetaData('og:image', JURI::root().'components/com_vikbooking/resources/uploads/'.$room[0]['img']);
			}
			if(!empty($room[0]['smalldesc'])) {
				$document->setMetaData('og:description', $room[0]['smalldesc']);
			}
			//
			$this->assignRef('room', $room[0]);
			$this->assignRef('busy', $busy);
			$this->assignRef('seasons_cal', $seasons_cal);
			$this->assignRef('promo_season', $promo_season);
			$this->assignRef('vbo_tn', $vbo_tn);
			//theme
			$theme = vikbooking::getTheme();
			if($theme != 'default') {
				$thdir = JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'themes'.DS.$theme.DS.'roomdetails';
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