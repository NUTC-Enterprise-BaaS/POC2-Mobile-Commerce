<?php
/**------------------------------------------------------------------------
 * mod_vikbooking_horizontalsearch - VikBooking
 * ------------------------------------------------------------------------
 * author    Alessio Gaggii - e4j - Extensionsforjoomla.com
 * copyright Copyright (C) 2016 e4j - Extensionsforjoomla.com. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: http://www.extensionsforjoomla.com
 * Technical Support:  tech@extensionsforjoomla.com
 * ------------------------------------------------------------------------
*/

defined('_JEXEC') or die('Restricted Area');

class modVikbooking_horizontalsearchHelper {

	public static function getFormattingText(&$params){
		$tx = $params->get('heading_text');
		$tag = $params->get('tag_heading');
		$cssc = $params->get('css_tag_heading');
		if(strlen($tx)){
			if(strlen($tag)){
				$tag=str_replace("<", "", $tag);
				$tag=str_replace(">", "", $tag);
				$tag=str_replace("/", "", $tag);
				return "<".$tag.(!empty($cssc) ? " class=\"".$cssc."\"" : "").">".$tx."</".$tag.">";
			}else{
				return $tx."<br/>";
			}
		}
		return "";
	}
	
	public static function mgetHoursMinutes($secs) {
		if ($secs >= 3600) {
			$op = $secs / 3600;
			$hours = floor($op);
			$less = $hours * 3600;
			$newsec = $secs - $less;
			$optwo = $newsec / 60;
			$minutes = floor($optwo);
		} else {
			$hours = "0";
			$optwo = $secs / 60;
			$minutes = floor($optwo);
		}
		$x[] = $hours;
		$x[] = $minutes;
		return $x;
	}
	
	public static function loadRestrictions ($filters = true, $rooms = array()) {
		$restrictions = array();
		$dbo = JFactory::getDBO();
		if(!$filters) {
			$q="SELECT * FROM `#__vikbooking_restrictions`;";
		}else {
			if (count($rooms) == 0) {
				$q="SELECT * FROM `#__vikbooking_restrictions` WHERE `allrooms`=1;";
			}else {
				$clause = array();
				foreach($rooms as $idr) {
					if (empty($idr)) continue;
					$clause[] = "`idrooms` LIKE '%-".intval($idr)."-%'";
				}
				if (count($clause) > 0) {
					$q="SELECT * FROM `#__vikbooking_restrictions` WHERE `allrooms`=1 OR (`allrooms`=0 AND (".implode(" OR ", $clause)."));";
				}else {
					$q="SELECT * FROM `#__vikbooking_restrictions` WHERE `allrooms`=1;";
				}
			}
		}
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$allrestrictions = $dbo->loadAssocList();
			foreach($allrestrictions as $k=>$res) {
				if(!empty($res['month'])) {
					$restrictions[$res['month']] = $res;
				}else {
					$restrictions['range'][$k] = $res;
				}
			}
		}
		return $restrictions;
	}
	
	public static function parseJsDrangeWdayCombo ($drestr) {
		$combo = array();
		if (strlen($drestr['wday']) > 0 && strlen($drestr['wdaytwo']) > 0 && !empty($drestr['wdaycombo'])) {
			$cparts = explode(':', $drestr['wdaycombo']);
			foreach($cparts as $kc => $cw) {
				if (!empty($cw)) {
					$nowcombo = explode('-', $cw);
					$combo[intval($nowcombo[0])][] = intval($nowcombo[1]);
				}
			}
		}
		return $combo;
	}
	
	public static function getDateFormat($skipsession = false) {
		if($skipsession) {
			$dbo = JFactory::getDBO();
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='dateformat';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$s = $dbo->loadAssocList();
			return $s[0]['setting'];
		}else {
			$session = JFactory::getSession();
			$sval = $session->get('vbgetDateFormat', '');
			if(!empty($sval)) {
				return $sval;
			}else {
				$dbo = JFactory::getDBO();
				$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='dateformat';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$s = $dbo->loadAssocList();
				$session->set('vbgetDateFormat', $s[0]['setting']);
				return $s[0]['setting'];
			}
		}
	}
	
	public static function getTimeOpenStore($skipsession = false) {
		if($skipsession) {
			$dbo = JFactory::getDBO();
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='timeopenstore';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$n = $dbo->loadAssocList();
			if (empty ($n[0]['setting']) && $n[0]['setting'] != "0") {
				return false;
			} else {
				$x = explode("-", $n[0]['setting']);
				if (!empty ($x[1]) && $x[1] != "0") {
					return $x;
				}
			}
		}else {
			$session = JFactory::getSession();
			$sval = $session->get('vbgetTimeOpenStore', '');
			if(!empty($sval)) {
				return $sval;
			}else {
				$dbo = JFactory::getDBO();
				$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='timeopenstore';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$n = $dbo->loadAssocList();
				if (empty ($n[0]['setting']) && $n[0]['setting'] != "0") {
					return false;
				} else {
					$x = explode("-", $n[0]['setting']);
					if (!empty ($x[1]) && $x[1] != "0") {
						$session->set('vbgetTimeOpenStore', $x);
						return $x;
					}
				}
			}
		}
		return false;
	}

	public static function getHoursMinutes($secs) {
		if ($secs >= 3600) {
			$op = $secs / 3600;
			$hours = floor($op);
			$less = $hours * 3600;
			$newsec = $secs - $less;
			$optwo = $newsec / 60;
			$minutes = floor($optwo);
		} else {
			$hours = "0";
			$optwo = $secs / 60;
			$minutes = floor($optwo);
		}
		$x[] = $hours;
		$x[] = $minutes;
		return $x;
	}
	
	public static function showChildrenFront($skipsession = false) {
		if($skipsession) {
			$dbo = JFactory::getDBO();
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='showchildren';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() == 1) {
				$s = $dbo->loadAssocList();
				return (intval($s[0]['setting']) == 1 ? true : false);
			} else {
				return false;
			}
		}else {
			$session = JFactory::getSession();
			$sval = $session->get('vbshowChildrenFront', '');
			if(strlen($sval) > 0) {
				return (intval($sval) == 1 ? true : false);
			}else {
				$dbo = JFactory::getDBO();
				$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='showchildren';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if ($dbo->getNumRows() == 1) {
					$s = $dbo->loadAssocList();
					$session->set('vbshowChildrenFront', $s[0]['setting']);
					return (intval($s[0]['setting']) == 1 ? true : false);
				} else {
					return false;
				}
			}
		}
	}
	
	public static function getMinDaysAdvance($skipsession = false) {
		if($skipsession) {
			$dbo = JFactory::getDBO();
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='mindaysadvance';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$s = $dbo->loadAssocList();
			return (int)$s[0]['setting'];
		}else {
			$session = JFactory::getSession();
			$sval = $session->get('vbminDaysAdvance', '');
			if(!empty($sval)) {
				return (int)$sval;
			}else {
				$dbo = JFactory::getDBO();
				$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='mindaysadvance';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$s = $dbo->loadAssocList();
				$session->set('vbminDaysAdvance', $s[0]['setting']);
				return (int)$s[0]['setting'];
			}
		}
	}
	
	public static function getDefaultNightsCalendar($skipsession = false) {
		if($skipsession) {
			$dbo = JFactory::getDBO();
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='autodefcalnights';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$s = $dbo->loadAssocList();
			return (int)$s[0]['setting'];
		}else {
			$session = JFactory::getSession();
			$sval = $session->get('vbdefaultNightsCalendar', '');
			if(!empty($sval)) {
				return (int)$sval;
			}else {
				$dbo = JFactory::getDBO();
				$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='autodefcalnights';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$s = $dbo->loadAssocList();
				$session->set('vbdefaultNightsCalendar', $s[0]['setting']);
				return (int)$s[0]['setting'];
			}
		}
	}
	
	public static function getSearchNumRooms($skipsession = false) {
		if($skipsession) {
			$dbo = JFactory::getDBO();
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='numrooms';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$s = $dbo->loadAssocList();
			return (int)$s[0]['setting'];
		}else {
			$session = JFactory::getSession();
			$sval = $session->get('vbsearchNumRooms', '');
			if(!empty($sval)) {
				return (int)$sval;
			}else {
				$dbo = JFactory::getDBO();
				$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='numrooms';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$s = $dbo->loadAssocList();
				$session->set('vbsearchNumRooms', $s[0]['setting']);
				return (int)$s[0]['setting'];
			}
		}
	}
	
	public static function getSearchNumAdults($skipsession = false) {
		if($skipsession) {
			$dbo = JFactory::getDBO();
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='numadults';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$s = $dbo->loadAssocList();
			return $s[0]['setting'];
		}else {
			$session = JFactory::getSession();
			$sval = $session->get('vbsearchNumAdults', '');
			if(!empty($sval)) {
				return $sval;
			}else {
				$dbo = JFactory::getDBO();
				$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='numadults';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$s = $dbo->loadAssocList();
				$session->set('vbsearchNumAdults', $s[0]['setting']);
				return $s[0]['setting'];
			}
		}
	}
	
	public static function getSearchNumChildren($skipsession = false) {
		if($skipsession) {
			$dbo = JFactory::getDBO();
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='numchildren';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$s = $dbo->loadAssocList();
			return $s[0]['setting'];
		}else {
			$session = JFactory::getSession();
			$sval = $session->get('vbsearchNumChildren', '');
			if(!empty($sval)) {
				return $sval;
			}else {
				$dbo = JFactory::getDBO();
				$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='numchildren';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$s = $dbo->loadAssocList();
				$session->set('vbsearchNumChildren', $s[0]['setting']);
				return $s[0]['setting'];
			}
		}
	}
	
	public static function getMaxDateFuture($skipsession = false) {
		if($skipsession) {
			$dbo = JFactory::getDBO();
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='maxdate';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$s = $dbo->loadAssocList();
			return $s[0]['setting'];
		}else {
			$session = JFactory::getSession();
			$sval = $session->get('vbmaxDateFuture', '');
			if(!empty($sval)) {
				return $sval;
			}else {
				$dbo = JFactory::getDBO();
				$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='maxdate';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$s = $dbo->loadAssocList();
				$session->set('vbmaxDateFuture', $s[0]['setting']);
				return $s[0]['setting'];
			}
		}
	}

	public static function parseJsClosingDates() {
		if(!class_exists('vikbooking') && file_exists(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_vikbooking'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'lib.vikbooking.php')) {
			require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_vikbooking'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'lib.vikbooking.php');
		}
		if(class_exists('vikbooking') && method_exists('vikbooking', 'parseJsClosingDates')) {
			return vikbooking::parseJsClosingDates();
		}
		return array();
	}
	
}

?>
