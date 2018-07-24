<?php
/**------------------------------------------------------------------------
 * com_vikchannelmanager - VikChannelManager
 * ------------------------------------------------------------------------
 * author    e4j - Extensionsforjoomla.com
 * copyright Copyright (C) 2016 e4j - Extensionsforjoomla.com. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: http://www.extensionsforjoomla.com
 * Technical Support:  tech@extensionsforjoomla.com
 * ------------------------------------------------------------------------
*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

class VikChannelManagerViewinventory extends JViewLegacy {
	
	function display($tpl = null) {
		// Set the toolbar
		$this->addToolBar();
		
		VCM::load_css_js();
		
		$dbo = JFactory::getDBO();
		
		$rooms = array();
		
		$module = VikChannelManager::getActiveModule(true);
		$module['settings'] = json_decode($module['settings'], true);
		
		$q = "SELECT * FROM `#__vikchannelmanager_tac_rooms`;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if( $dbo->getNumRows() > 0 ) {
			$rooms = $dbo->loadAssocList();
		}
		
		$vb_rooms = array();
		
		$q = "SELECT `id`, `name`, `smalldesc`, `img` FROM `#__vikbooking_rooms` ORDER BY `name`;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if( $dbo->getNumRows() > 0 ) {
			$vb_rooms = $dbo->loadAssocList();
		}
		
		for( $j = 0; $j < count($vb_rooms); $j++ ) {
			$found = false;
			for( $i = 0; $i < count($rooms) && !$found; $i++ ) {
				if( $rooms[$i]['id_vb_room'] == $vb_rooms[$j]['id'] ) {
					$found = true;
					$vb_rooms[$j]['tac_room_id'] = $rooms[$i]['id'];
					$vb_rooms[$j]['name'] = $rooms[$i]['name'];
					$vb_rooms[$j]['smalldesc'] = $rooms[$i]['desc'];
					$vb_rooms[$j]['img'] = substr($rooms[$i]['img'], strrpos($rooms[$i]['img'], DS)+1);
					$vb_rooms[$j]['amenities'] = explode(',', $rooms[$i]['amenities']);
					$vb_rooms[$j]['codes'] = $rooms[$i]['codes'];
					//$vb_rooms[$j]['url'] = $rooms[$i]['url'];
					$vb_rooms[$j]['cost'] = $rooms[$i]['cost'];
					$rooms[$i]['found'] = true;
				}
				
			}
			
			if( !$found ) {
				$vb_rooms[$j]['tac_room_id'] = 0;
				//$vb_rooms[$j]['url'] = JURI::root().'index.php?option=com_vikbooking&view=roomdetails&roomid='.$vb_rooms[$j]['id'];
				$vb_rooms[$j]['amenities'] = array();
				$vb_rooms[$j]['codes'] = '';
				$vb_rooms[$j]['cost'] = number_format(VikChannelManager::getRoomRatesCost($vb_rooms[$j]['id']), 2, ".", "");
			}
			
			// REFRESH ALWAYS URL
			if( $module['settings']['url_type']['value'] == 'VCM_TA_URL_TYPE_ROOM' ) {
				$vb_rooms[$j]['url'] = JURI::root().'index.php?option=com_vikbooking&view=roomdetails&roomid='.$vb_rooms[$j]['id'];
			} else {
				$vb_rooms[$j]['url'] = JURI::root().'index.php?option=com_vikbooking&task=search&roomid='.$vb_rooms[$j]['id'];
			}
		}
		
		foreach( $rooms as $r ) {
			if( empty($r['found']) ) {
				$q = "DELETE FROM `#__vikchannelmanager_tac_rooms` WHERE `id`=".$r['id']." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->Query($q);
			}
		}
		
		$vb_rooms = $this->sortRooms($vb_rooms);
		
		$this->assignRef('rooms', $vb_rooms);
		
		// Display the template (default.php)
		parent::display($tpl);
		
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() {
		//Add menu title and some buttons to the page
		JToolBarHelper::title(JText::_('VCMMAINTROOMINVENTORY'), 'vikchannelmanager');
		JToolBarHelper::apply( 'saveRoomsInventory', JText::_('SAVE'));
		JToolBarHelper::spacer();
		
	}
	
	protected function sortRooms($rooms) {
		$arr_active = array();
		$arr_unactive = array();
		foreach( $rooms as $r ) {
			if( $r['tac_room_id'] != 0 ) {
				$arr_active[count($arr_active)] = $r;
			} else {
				$arr_unactive[count($arr_unactive)] = $r;
			}
		}
		
		foreach( $arr_unactive as $r ) {
			$arr_active[count($arr_active)] = $r;
		}
		
		return $arr_active;
	}
}
?>