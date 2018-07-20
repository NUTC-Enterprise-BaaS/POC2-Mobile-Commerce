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

class VikbookingViewAvailability extends JViewLegacy {
	function display($tpl = null) {
		$room_ids = JRequest::getVar('room_ids', array());
		$room_ids = count($room_ids) > 0 && empty($room_ids[0]) ? array() : $room_ids;
		$dbo = JFactory::getDBO();
		$vbo_tn = vikbooking::getTranslator();
		$q = "SELECT * FROM `#__vikbooking_rooms` WHERE ".(count($room_ids) > 0 ? "`id` IN (".implode(',', $room_ids).") AND " : "")."`avail`='1' ORDER BY `#__vikbooking_rooms`.`toadult` ASC, `#__vikbooking_rooms`.`totpeople` ASC, `#__vikbooking_rooms`.`name` ASC;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() > 0) {
			$rooms=$dbo->loadAssocList();
			$vbo_tn->translateContents($rooms, '#__vikbooking_rooms');
			$pmonth = JRequest::getInt('month', '', 'request');
			if(!empty($pmonth)) {
				$tsstart=$pmonth;
			}else {
				$oggid=getdate();
				$tsstart=mktime(0, 0, 0, $oggid['mon'], 1, $oggid['year']);
			}
			$oggid=getdate($tsstart);
			if($oggid['mon']==12) {
				$nextmon=1;
				$year=$oggid['year'] + 1;
			}else {
				$nextmon=$oggid['mon'] + 1;
				$year=$oggid['year'];
			}
			$tsend=mktime(0, 0, 0, $nextmon, 1, $year);
			$today=getdate();
			$firstmonth=mktime(0, 0, 0, $today['mon'], 1, $today['year']);
			$wmonthsel="<select name=\"month\" onchange=\"document.vbmonths.submit();\">\n";
			$wmonthsel.="<option value=\"".$firstmonth."\"".($firstmonth==$tsstart ? " selected=\"selected\"" : "").">".vikbooking::sayMonth($today['mon'])." ".$today['year']."</option>\n";
			$futuremonths = 12;
			for($i=1; $i<=$futuremonths; $i++) {
				$newts=getdate($firstmonth);
				if($newts['mon']==12) {
					$nextmon=1;
					$year=$newts['year'] + 1;
				}else {
					$nextmon=$newts['mon'] + 1;
					$year=$newts['year'];
				}
				$firstmonth=mktime(0, 0, 0, $nextmon, 1, $year);
				$newts=getdate($firstmonth);
				$wmonthsel.="<option value=\"".$firstmonth."\"".($firstmonth==$tsstart ? " selected=\"selected\"" : "").">".vikbooking::sayMonth($newts['mon'])." ".$newts['year']."</option>\n";
			}
			$wmonthsel.="</select>\n";
			$busy=array();
			$q="SELECT * FROM `#__vikbooking_busy` WHERE ".(count($room_ids) > 0 ? "`idroom` IN (".implode(',', $room_ids).") AND " : "")."(`checkin`>=".$tsstart." OR `checkout`>=".$tsstart.");";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() > 0) {
				$all_busy = $dbo->loadAssocList();
				foreach ($all_busy as $brecord) {
					$busy[$brecord['idroom']][] = $brecord;
				}
			}
			$this->assignRef('rooms', $rooms);
			$this->assignRef('tsstart', $tsstart);
			$this->assignRef('wmonthsel', $wmonthsel);
			$this->assignRef('busy', $busy);
			$this->assignRef('vbo_tn', $vbo_tn);
			//theme
			$theme = vikbooking::getTheme();
			if($theme != 'default') {
				$thdir = JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'themes'.DS.$theme.DS.'availability';
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