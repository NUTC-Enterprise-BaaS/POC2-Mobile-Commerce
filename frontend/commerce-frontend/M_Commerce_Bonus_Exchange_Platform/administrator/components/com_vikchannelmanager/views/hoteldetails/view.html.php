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

class VikChannelManagerViewhoteldetails extends JViewLegacy {
	
	function display($tpl = null) {
		// Set the toolbar
		$this->addToolBar();
		
		VCM::load_css_js();
		
		$dbo = JFactory::getDBO();
		
		$params = array();
		$rows = array();
		
		$q = "SELECT * FROM `#__vikchannelmanager_hotel_details`;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if( $dbo->getNumRows() > 0 ) {
			$rows = $dbo->loadAssocList();
		}
		
		foreach($rows as $r) {
			$params[$r['key']] = $r['value'];
		}
        
        $countries = array();
        
        if( file_exists(JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'helpers'.DS.'lib.vikbooking.php') ) {
            $q = "SELECT * FROM `#__vikbooking_countries` ORDER BY `country_name`;";
            $dbo->setQuery($q);
            $dbo->Query($q);
            if( $dbo->getNumRows() > 0 ) {
                $countries = $dbo->loadAssocList();
            }
        }
		
		$this->assignRef('params', $params);
        $this->assignRef('countries', $countries);
		
		// Display the template (default.php)
		parent::display($tpl);
		
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() {
		//Add menu title and some buttons to the page
		JToolBarHelper::title(JText::_('VCMMAINTHOTELDETAILS'), 'vikchannelmanager');
		JToolBarHelper::apply('saveHotelDetails', JText::_('SAVE'));
		JToolBarHelper::spacer();
		
	}
}
?>