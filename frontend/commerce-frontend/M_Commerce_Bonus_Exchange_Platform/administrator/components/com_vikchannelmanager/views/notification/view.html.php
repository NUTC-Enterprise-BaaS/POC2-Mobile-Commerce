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

class VikChannelManagerViewnotification extends JViewLegacy {
	
	function display($tpl = null) {
	
		VCM::load_css_js();
	
		$cid = JRequest::getVar('cid', array(0));
		
		$not = "";
		$row = "";
		$rooms = "";
		$busy = "";
		$rows = "";
		
		$dbo = JFactory::getDBO();
		$q = "SELECT * FROM `#__vikchannelmanager_notifications` WHERE `id`=".$cid[0]." LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if( $dbo->getNumRows() == 1 ) {
			$not = $dbo->loadAssoc();
			$not['children'] = array();
			$q = "SELECT * FROM `#__vikchannelmanager_notification_child` WHERE `id_parent`=".$not['id'].";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() > 0) {
				$not['children'] = $dbo->loadAssocList();
			}
			if( !empty($not['idordervb']) ) {
				$q = "SELECT * FROM `#__vikbooking_orders` WHERE `id`=".$not['idordervb']." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if( $dbo->getNumRows() == 1 ) {
					$row = $dbo->loadAssoc();
					$q = "SELECT `or`.*,`r`.`name`,`r`.`fromadult`,`r`.`toadult` FROM `#__vikbooking_ordersrooms` AS `or`,`#__vikbooking_rooms` AS `r` WHERE `or`.`idorder`='".$row['id']."' AND `or`.`idroom`=`r`.`id` ORDER BY `or`.`id` ASC;";
					$dbo->setQuery($q);
					$dbo->Query($q);
					$rooms = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : "";
					$q = "SELECT * FROM `#__vikbooking_ordersbusy` WHERE `idorder`=".$not['idordervb'].";";
					$dbo->setQuery($q);
					$dbo->Query($q);
					$busy = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : "";
				}
			}
		}
		
		$this->assignRef('notification', $not);
		$this->assignRef('row', $row);
		$this->assignRef('rooms', $rooms);
		$this->assignRef('busy', $busy);
		
		// Display the template (default.php)
		parent::display($tpl);
		
	}
}
?>