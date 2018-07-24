<?php
/**------------------------------------------------------------------------
 * mod_vikbooking_rooms - VikBooking
 * ------------------------------------------------------------------------
 * author    Alessio Gaggii - Extensionsforjoomla.com
 * copyright Copyright (C) 2014 extensionsforjoomla.com. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: http://www.extensionsforjoomla.com
 * Technical Support:  tech@extensionsforjoomla.com
 * ------------------------------------------------------------------------
*/

// no direct access
defined('_JEXEC') or die;

class modvikbooking_roomsHelper {
	static function getRooms($params) {
		if(!class_exists('vikbooking')) {
			require_once(JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'helpers'.DS.'lib.vikbooking.php');
		}
		$vbo_tn = vikbooking::getTranslator();
		$dbo = JFactory :: getDBO();
		$showcatname = intval($params->get('showcatname')) == 1 ? true : false;
		$rooms = array();
		$query = $params->get('query');
		if($query == 'price') {
			//simple order by price asc
			$q = "SELECT `id`,`name`,`img`,`idcat`,`smalldesc`,`totpeople`,`params` FROM `#__vikbooking_rooms` WHERE `avail`='1';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if($dbo->getNumRows() > 0) {
				$rooms=$dbo->loadAssocList();
				$vbo_tn->translateContents($rooms, '#__vikbooking_rooms');
				foreach($rooms as $k=>$c) {
					if($showcatname) $rooms[$k]['catname'] = self::getCategoryName($c['idcat']);
					$custprice = self::getRoomParam('custprice', $c['params']);
					if(strlen($custprice) > 0 && (float)$custprice > 0.00) {
						$rooms[$k]['cost']=(float)$custprice;
						$custpricetxt = self::getRoomParam('custpricetxt', $c['params']);
						$custpricetxt = empty($custpricetxt) ? '' : JText::_($custpricetxt);
						$rooms[$k]['custpricetxt']=$custpricetxt;
					}else {
						$q="SELECT `id`,`cost` FROM `#__vikbooking_dispcost` WHERE `idroom`='".$c['id']."' AND `days`='1' ORDER BY `#__vikbooking_dispcost`.`cost` ASC LIMIT 1;";
						$dbo->setQuery($q);
						$dbo->Query($q);
						if($dbo->getNumRows() == 1) {
							$tar=$dbo->loadAssocList();
							$rooms[$k]['cost']=$tar[0]['cost'];
						}else {
							$q="SELECT `id`,`days`,`cost` FROM `#__vikbooking_dispcost` WHERE `idroom`='".$c['id']."' ORDER BY `#__vikbooking_dispcost`.`cost` ASC LIMIT 1;";
							$dbo->setQuery($q);
							$dbo->Query($q);
							if($dbo->getNumRows() == 1) {
								$tar=$dbo->loadAssocList();
								$rooms[$k]['cost']=($tar[0]['cost'] / $tar[0]['days']);
							}else {
								$rooms[$k]['cost']=0;
							}
						}
					}
				}
			}
			$rooms = self::sortRoomsByPrice($rooms, $params);
		}elseif($query == 'name') {
			//order by name
			$q = "SELECT `id`,`name`,`img`,`idcat`,`totpeople`,`params` FROM `#__vikbooking_rooms` WHERE `avail`='1' ORDER BY `#__vikbooking_rooms`.`name` ".strtoupper($params->get('order'))." LIMIT ".$params->get('numb').";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if($dbo->getNumRows() > 0) {
				$rooms=$dbo->loadAssocList();
				$vbo_tn->translateContents($rooms, '#__vikbooking_rooms');
				foreach($rooms as $k=>$c) {
					if($showcatname) $rooms[$k]['catname'] = self::getCategoryName($c['idcat']);
					$custprice = self::getRoomParam('custprice', $c['params']);
					if(strlen($custprice) > 0 && (float)$custprice > 0.00) {
						$rooms[$k]['cost']=(float)$custprice;
						$custpricetxt = self::getRoomParam('custpricetxt', $c['params']);
						$custpricetxt = empty($custpricetxt) ? '' : JText::_($custpricetxt);
						$rooms[$k]['custpricetxt']=$custpricetxt;
					}else {
						$q="SELECT `id`,`cost` FROM `#__vikbooking_dispcost` WHERE `idroom`='".$c['id']."' AND `days`='1' ORDER BY `#__vikbooking_dispcost`.`cost` ASC LIMIT 1;";
						$dbo->setQuery($q);
						$dbo->Query($q);
						if($dbo->getNumRows() == 1) {
							$tar=$dbo->loadAssocList();
							$rooms[$k]['cost']=$tar[0]['cost'];
						}else {
							$q="SELECT `id`,`days`,`cost` FROM `#__vikbooking_dispcost` WHERE `idroom`='".$c['id']."' ORDER BY `#__vikbooking_dispcost`.`cost` ASC LIMIT 1;";
							$dbo->setQuery($q);
							$dbo->Query($q);
							if($dbo->getNumRows() == 1) {
								$tar=$dbo->loadAssocList();
								$rooms[$k]['cost']=($tar[0]['cost'] / $tar[0]['days']);
							}else {
								$rooms[$k]['cost']=0;
							}
						}
					}
				}
			}
		}else {
			//sort by category
			$q = "SELECT `id`,`name`,`img`,`idcat`,`idcarat`,`info`,`totpeople`,`params` FROM `#__vikbooking_rooms` WHERE `avail`='1' AND (`idcat`='".$params->get('catid').";' OR `idcat` LIKE '".$params->get('catid').";%' OR `idcat` LIKE '%;".$params->get('catid').";%' OR `idcat` LIKE '%;".$params->get('catid').";') ORDER BY `#__vikbooking_rooms`.`name` ".strtoupper($params->get('order'))." LIMIT ".$params->get('numb').";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if($dbo->getNumRows() > 0) {
				$rooms=$dbo->loadAssocList();
				$vbo_tn->translateContents($rooms, '#__vikbooking_rooms');
				foreach($rooms as $k=>$c) {
					if($showcatname) $rooms[$k]['catname'] = self::getCategoryName($c['idcat']);
					$custprice = self::getRoomParam('custprice', $c['params']);
					if(strlen($custprice) > 0 && (float)$custprice > 0.00) {
						$rooms[$k]['cost']=(float)$custprice;
						$custpricetxt = self::getRoomParam('custpricetxt', $c['params']);
						$custpricetxt = empty($custpricetxt) ? '' : JText::_($custpricetxt);
						$rooms[$k]['custpricetxt']=$custpricetxt;
					}else {
						$q="SELECT `id`,`cost` FROM `#__vikbooking_dispcost` WHERE `idroom`='".$c['id']."' AND `days`='1' ORDER BY `#__vikbooking_dispcost`.`cost` ASC LIMIT 1;";
						$dbo->setQuery($q);
						$dbo->Query($q);
						if($dbo->getNumRows() == 1) {
							$tar=$dbo->loadAssocList();
							$rooms[$k]['cost']=$tar[0]['cost'];
						}else {
							$q="SELECT `id`,`days`,`cost` FROM `#__vikbooking_dispcost` WHERE `idroom`='".$c['id']."' ORDER BY `#__vikbooking_dispcost`.`cost` ASC LIMIT 1;";
							$dbo->setQuery($q);
							$dbo->Query($q);
							if($dbo->getNumRows() == 1) {
								$tar=$dbo->loadAssocList();
								$rooms[$k]['cost']=($tar[0]['cost'] / $tar[0]['days']);
							}else {
								$rooms[$k]['cost']=0;
							}
						}
					}
				}
			}
			if($params->get('querycat') == 'price') {
				$rooms = self::sortRoomsByPrice($rooms, $params);
			}
		}
		return $rooms;
	}
	
	static function getRoomParam ($paramname, $paramstr) {
		if (empty($paramstr)) return '';
		$paramarr = json_decode($paramstr, true);
		if (array_key_exists($paramname, $paramarr)) {
			return $paramarr[$paramname];
		}
		return '';
	}
	
	static function sortRoomsByPrice($arr, $params) {
		$newarr = array ();
		foreach ($arr as $k => $v) {
			$newarr[$k] = $v['cost'];
		}
		asort($newarr);
		$sorted = array ();
		foreach ($newarr as $k => $v) {
			$sorted[$k] = $arr[$k];
		}
		return $params->get('order') == 'desc' ? array_reverse($sorted) : $sorted;
	}
	
	static function getCategoryName($idcat) {
		if(!class_exists('vikbooking')) {
			require_once(JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'helpers'.DS.'lib.vikbooking.php');
		}
		$vbo_tn = vikbooking::getTranslator();
		$dbo = JFactory :: getDBO();
		$q = "SELECT `id`,`name` FROM `#__vikbooking_categories` WHERE `id`='" . str_replace(";", "", $idcat) . "';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$p = @ $dbo->loadAssocList();
		$vbo_tn->translateContents($p, '#__vikbooking_categories');
		return $p[0]['name'];
	}
	
	static function limitRes($rooms, $params) {
		return array_slice($rooms, 0, $params->get('numb'));
	}
	
}
