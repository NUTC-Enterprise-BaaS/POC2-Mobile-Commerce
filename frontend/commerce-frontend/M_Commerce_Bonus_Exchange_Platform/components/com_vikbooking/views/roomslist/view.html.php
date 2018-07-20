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

class VikbookingViewRoomslist extends JViewLegacy {
	function display($tpl = null) {
		vikbooking::prepareViewContent();
		$dbo = JFactory::getDBO();
		$vbo_tn = vikbooking::getTranslator();
		$pcategory_id = JRequest::getInt('category_id', '', 'request');
		$psortby = JRequest::getString('sortby', '', 'request');
		$psortby = !in_array($psortby, array('price', 'name', 'id', 'random')) ? 'price' : $psortby;
		$psorttype = JRequest::getString('sorttype', '', 'request');
		$psorttype = $psorttype == 'desc' ? 'DESC' : 'ASC';
		$preslim = JRequest::getInt('reslim', '', 'request');
		$preslim = empty($preslim) || $preslim < 1 ? 20 : $preslim;
		$category = "";
		if($pcategory_id > 0) {
			$q="SELECT * FROM `#__vikbooking_categories` WHERE `id`='".$pcategory_id."';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if($dbo->getNumRows() == 1) {
				$category = $dbo->loadAssocList();
				$category = $category[0];
				$vbo_tn->translateContents($category, '#__vikbooking_categories');
			}
		}
		$ordbyclause = '';
		if ($psortby == 'name') {
			$ordbyclause = ' ORDER BY `#__vikbooking_rooms`.`name` '.$psorttype;
		}elseif ($psortby == 'id') {
			$ordbyclause = ' ORDER BY `#__vikbooking_rooms`.`id` '.$psorttype;
		}
		if(is_array($category)) {
			$q = "SELECT * FROM `#__vikbooking_rooms` WHERE `avail`='1' AND (`idcat`='".$category['id'].";' OR `idcat` LIKE '".$category['id'].";%' OR `idcat` LIKE '%;".$category['id'].";%' OR `idcat` LIKE '%;".$category['id'].";')".$ordbyclause.";";
		}else {
			$q = "SELECT * FROM `#__vikbooking_rooms` WHERE `avail`='1'".$ordbyclause.";";
		}
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() > 0) {
			$rooms=$dbo->loadAssocList();
			$vbo_tn->translateContents($rooms, '#__vikbooking_rooms');
			foreach($rooms as $k=>$c) {
				$custprice = vikbooking::getRoomParam('custprice', $c['params']);
				if (!empty($custprice)) {
					$rooms[$k]['cost']=floatval($custprice);
				}else {
					$q="SELECT `id`,`cost` FROM `#__vikbooking_dispcost` WHERE `idroom`=".$dbo->quote($c['id'])." AND `days`='1' ORDER BY `#__vikbooking_dispcost`.`cost` ASC LIMIT 1;";
					$dbo->setQuery($q);
					$dbo->Query($q);
					if($dbo->getNumRows() == 1) {
						$tar=$dbo->loadAssocList();
						$rooms[$k]['cost']=$tar[0]['cost'];
					}else {
						$q="SELECT `id`,`days`,`cost` FROM `#__vikbooking_dispcost` WHERE `idroom`=".$dbo->quote($c['id'])." ORDER BY `#__vikbooking_dispcost`.`cost` ASC LIMIT 1;";
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
			if ($psortby == 'random') {
				$keys = array_keys($rooms);
				shuffle($keys);
				$new = array();
				foreach($keys as $key) {
					$new[$key] = $rooms[$key];
				}
				$rooms = $new;
			}elseif ($psortby == 'price') {
				$rooms = vikbooking::sortRoomPrices($rooms);
				if ($psorttype == 'DESC') {
					$rooms = array_reverse($rooms, true);
				}
			}
			//pagination
			$lim=$preslim; //results limit
			$lim0 = JRequest::getVar('limitstart', 0, '', 'int');
			jimport('joomla.html.pagination');
			$pageNav = new JPagination(count($rooms), $lim0, $lim);
			$navig = $pageNav->getPagesLinks();
			$this->assignRef('navig', $navig);
			$rooms = array_slice($rooms, $lim0, $lim, true);
			//
			
			$this->assignRef('rooms', $rooms);
			$this->assignRef('category', $category);
			$this->assignRef('vbo_tn', $vbo_tn);
			//theme
			$theme = vikbooking::getTheme();
			if($theme != 'default') {
				$thdir = JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'themes'.DS.$theme.DS.'roomslist';
				if(is_dir($thdir)) {
					$this->_setPath('template', $thdir.DS);
				}
			}
			//
			parent::display($tpl);
		}
	}
}
?>