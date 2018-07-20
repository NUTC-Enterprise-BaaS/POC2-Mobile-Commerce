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

class VikbookingViewPromotions extends JViewLegacy {
	function display($tpl = null) {
		vikbooking::prepareViewContent();
		$dbo = JFactory::getDBO();
		$vbo_tn = vikbooking::getTranslator();
		$pshowrooms = JRequest::getInt('showrooms', 1, 'request');
		$pshowrooms = $pshowrooms == 1 ? 1 : 0;
		$pmaxdate = JRequest::getInt('maxdate', 6, 'request');
		$plim = JRequest::getInt('lim', 10, 'request');
		$promotions = array();
		$rooms = array();
		$ind = 0;
		$q = "SELECT * FROM `#__vikbooking_seasons` WHERE `promo`=1;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() > 0) {
			$all_promotions = $dbo->loadAssocList();
			$vbo_tn->translateContents($all_promotions, '#__vikbooking_seasons');
			$base_year = (int)date('Y');
			$base_ts = time();
			$base_month = (int)date('n');
			foreach ($all_promotions as $k => $promo) {
				$promo_year = !empty($promo['year']) && $promo['year'] > 0 ? $promo['year'] : $base_year;
				$promo_from_ts = ((int)mktime(0, 0, 0, 1, 1, $promo_year)) + $promo['from'];
				if($base_ts > $promo_from_ts) {
					if(empty($promo['year'])) {
						$promo_year++;
						$promo_from_ts = ((int)mktime(0, 0, 0, 1, 1, $promo_year)) + $promo['from'];
					}else {
						//Start ts is in the past, not tied to the year, check if season end ts is in the future
						$check_promo_to_ts = ((int)mktime(0, 0, 0, 1, 1, ($promo['from'] > $promo['to'] ? ($promo_year + 1) : $promo_year))) + $promo['to'];
						if($base_ts > $check_promo_to_ts) {
							continue;
						}
					}
				}
				if($promo['from'] > $promo['to']) {
					$promo_year++;
				}
				$promo_to_ts = ((int)mktime(0, 0, 0, 1, 1, $promo_year)) + $promo['to'];
				if($promo_from_ts < $promo_to_ts) {
					//Begin: Check Max Date in the Future (Months)
					$promo_from_month = (int)date('n', $promo_from_ts);
					$promo_from_year = (int)date('Y', $promo_from_ts);
					if($base_year == $promo_from_year) {
						//Same Year
						$months_diff = $promo_from_month - $base_month;
						if($months_diff > $pmaxdate) {
							continue;
						}
					}else {
						//Different Year
						$promo_from_month += 12 * ($promo_from_year - $base_year);
						$months_diff = $promo_from_month - $base_month;
						if($months_diff > $pmaxdate) {
							continue;
						}
					}
					//End: Check Max Date in the Future (Months)
					$promotions[$ind] = $all_promotions[$k];
					$promotions[$ind]['promo_from_ts'] = $promo_from_ts;
					$promotions[$ind]['promo_to_ts'] = $promo_to_ts;
					$promotions[$ind]['promo_valid_ts'] = $promo_from_ts;
					//set valid until to end ts in case of 0 days in advance
					if(empty($promo['promodaysadv']) || !($promo['promodaysadv'] > 0)) {
						$promotions[$ind]['promo_valid_ts'] = $promo_to_ts;
					}elseif(!empty($promo['promodaysadv']) && $promo['promodaysadv'] > 0) {
						$dst_from_ts = date('I', $promo_from_ts);
						$valid_ts = $promo_from_ts - (86400 * $promo['promodaysadv']);
						$dst_valid_ts = date('I', $valid_ts);
						if($dst_from_ts != $dst_valid_ts) {
							if($dst_valid_ts) {
								$valid_ts -= 3600;
							}else {
								$valid_ts += 3600;
							}
						}
						$promotions[$ind]['promo_valid_ts'] = $valid_ts;
					}
					$ind++;
				}
			}
			if(count($promotions) > 0) {
				$promo_map = array();
				$sorted = array();
				$promos_rooms = array();
				foreach ($promotions as $k => $v) {
					$promo_map[$k] = $v['promo_from_ts'];
					$allrooms = explode(",", $v['idrooms']);
					foreach ($allrooms as $idroom) {
						$idroom = intval(str_replace("-", "", trim($idroom)));
						if($idroom > 0) {
							$promos_rooms[$idroom] = $idroom;
						}
					}
				}
				asort($promo_map);
				foreach ($promo_map as $k => $v) {
					$sorted[$k] = $promotions[$k];
				}
				$promotions = $sorted;
				if(count($promos_rooms) > 0) {
					$q = "SELECT * FROM `#__vikbooking_rooms` WHERE `id` IN(".implode(",", $promos_rooms).") ORDER BY `#__vikbooking_rooms`.`name` ASC;";
					$dbo->setQuery($q);
					$dbo->Query($q);
					if($dbo->getNumRows() > 0) {
						$fetch_rooms = $dbo->loadAssocList();
						$vbo_tn->translateContents($fetch_rooms, '#__vikbooking_rooms');
						foreach ($fetch_rooms as $v) {
							$rooms[$v['id']] = $v;
						}
					}
				}
			}
		}
		
		$this->assignRef('promotions', $promotions);
		$this->assignRef('rooms', $rooms);
		$this->assignRef('showrooms', $pshowrooms);
		$this->assignRef('vbo_tn', $vbo_tn);
		//theme
		$theme = vikbooking::getTheme();
		if($theme != 'default') {
			$thdir = JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'themes'.DS.$theme.DS.'promotions';
			if(is_dir($thdir)) {
				$this->_setPath('template', $thdir.DS);
			}
		}
		//
		parent :: display($tpl);
	}
}
?>