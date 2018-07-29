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

class VikChannelManagerViewrooms extends JViewLegacy {
	
	function display($tpl = null) {
		// Set the toolbar
		$this->addToolBar();
		
		VCM::load_css_js();
		
		$dbo = JFactory::getDBO();
		
		$q = "SELECT `vcmr`.*,`vbr`.`name`,`vbr`.`img`,`vbr`.`smalldesc` FROM `#__vikchannelmanager_roomsxref` AS `vcmr` LEFT JOIN `#__vikbooking_rooms` `vbr` ON `vcmr`.`idroomvb`=`vbr`.`id` ORDER BY `vbr`.`name` ASC, `vcmr`.`channel` ASC;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$roomsxrefmap = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : array();

		$q = "SELECT `vcmr`.*,`vbr`.`name`,`vbr`.`img`,`vbr`.`smalldesc` FROM `#__vikchannelmanager_roomsxref` AS `vcmr` LEFT JOIN `#__vikbooking_rooms` `vbr` ON `vcmr`.`idroomvb`=`vbr`.`id` ORDER BY `vcmr`.`channel` ASC, `vbr`.`name` ASC;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$roomsxref = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : array();
	
		$config = VikChannelManager::loadConfiguration();
		
		$this->assignRef('config', $config);
		$this->assignRef('rowsmap', $roomsxrefmap);
		$this->assignRef('rows', $roomsxref);
		
		// Display the template (default.php)
		parent::display($tpl);
		
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() {
		//Add menu title and some buttons to the page
		JToolBarHelper::title(JText::_('VCMMAINTROOMSLIST'), 'vikchannelmanager');
		
		JToolBarHelper::deleteList('', 'removeroomsxref', JText::_('REMOVE'));
		
	}
}
?>