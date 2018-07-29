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

class VikChannelManagerViewcustoma extends JViewLegacy {
	
	function display($tpl = null) {
		// Set the toolbar
		$this->addToolBar();
		
		VCM::load_css_js();
		
		$dbo = JFactory::getDBO();
		
		$otarooms = "";
		$q = "SELECT * FROM `#__vikchannelmanager_roomsxref` GROUP BY `#__vikchannelmanager_roomsxref`.`idroomota`, `#__vikchannelmanager_roomsxref`.`idchannel` ORDER BY `#__vikchannelmanager_roomsxref`.`channel` ASC, `#__vikchannelmanager_roomsxref`.`otaroomname` ASC;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if( $dbo->getNumRows() > 0 ) {
			$otarooms = $dbo->loadAssocList();
		}
		
		$this->assignRef('otarooms', $otarooms);
		
		// Display the template (default.php)
		parent::display($tpl);
		
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() {
		//Add menu title and some buttons to the page
		JToolBarHelper::title(JText::_('VCMMAINTCUSTOMA'), 'vikchannelmanager');
		JToolBarHelper::custom( 'confirmcustoma', 'refresh', 'refresh', JText :: _('VCMTLBCUSTOMA'), false, false);
		JToolBarHelper::spacer();
		JToolBarHelper::cancel( 'cancel', JText::_('CANCEL'));
		JToolBarHelper::spacer();
		
	}
}
?>