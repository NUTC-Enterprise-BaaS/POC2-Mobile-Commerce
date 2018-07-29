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

class VikbookingViewLoginregister extends JViewLegacy {
	function display($tpl = null) {
		$dbo = JFactory::getDBO();
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
		$q = "SELECT * FROM `#__vikbooking_optionals`;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$optionals = $dbo->loadAssocList();
			foreach($rooms as $num => $r) {
				foreach ($optionals as $opt) {
					$tmpvar = JRequest::getString('optid'.$num.$opt['id'], '', 'request');
					if (!empty ($tmpvar)) {
						$opt['quan'] = $tmpvar;
						$selopt[$num][] = $opt;
					}
				}
			}
		}
		$this->assignRef('prices', $prices);
		$this->assignRef('rooms', $rooms);
		$this->assignRef('days', $pdays);
		$this->assignRef('checkin', $pcheckin);
		$this->assignRef('checkout', $pcheckout);
		$this->assignRef('selopt', $selopt);
		$this->assignRef('roomsnum', $proomsnum);
		$this->assignRef('adults', $padults);
		$this->assignRef('children', $pchildren);
		$this->assignRef('arrpeople', $arrpeople);
		//theme
		$theme = vikbooking::getTheme();
		if($theme != 'default') {
			$thdir = JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'themes'.DS.$theme.DS.'loginregister';
			if(is_dir($thdir)) {
				$this->_setPath('template', $thdir.DS);
			}
		}
		//
		parent::display($tpl);
	}
}


?>