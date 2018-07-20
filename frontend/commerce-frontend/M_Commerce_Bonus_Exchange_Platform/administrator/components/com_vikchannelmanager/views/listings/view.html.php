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

class VikChannelManagerViewlistings extends JViewLegacy {
	
	function display($tpl = null) {
		$module = VikChannelManager::getActiveModule(true);    

        $mainframe = JFactory::getApplication();
        $lim = $mainframe->getUserStateFromRequest("com_vikchannelmanager.limit", 'limit', $mainframe->getCfg('list_limit'), 'int');
        $lim0 = JRequest::getVar('limitstart', 0, '', 'int');
        $navbut = '';
            
		// Set the toolbar
		$this->addToolBar($module['uniquekey']);
		
		VCM::load_css_js();
        
        $api_key = VikChannelManager::getApiKey(true);
        
        $properties = array();
        $vb_rooms = array();
        
        $dbo = JFactory::getDBO();
        
        $q = "SELECT * FROM `#__vikchannelmanager_listings` WHERE `channel`=".$dbo->quote($module['uniquekey']).";";
        $dbo->setQuery($q);
        $dbo->Query($q);
        if( $dbo->getNumRows() > 0 ) {
            $properties = $dbo->loadAssocList();    
        }
        
        $q = "SELECT SQL_CALC_FOUND_ROWS `id`, `name`, `smalldesc`, `img` FROM `#__vikbooking_rooms` ORDER BY `name`";
        $dbo->setQuery($q, $lim0, $lim);
        $dbo->Query($q);
        if( $dbo->getNumRows() > 0 ) {
            $vb_rooms = $dbo->loadAssocList();
            $dbo->setQuery('SELECT FOUND_ROWS();');
            jimport('joomla.html.pagination');
            $pageNav = new JPagination( $dbo->loadResult(), $lim0, $lim );
            $navbut="<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
        }
        
        foreach( $vb_rooms as $index => $r ) {
            $vb_rooms[$index]['retrieval_url'] = "";
            $vb_rooms[$index]['download_url'] = "https://e4jconnect.com/ical/".$module['name']."/$api_key/".$r['id'];
            $vb_rooms[$index]['id_assoc'] = -1;
             
            foreach( $properties as $p ) {
                if( $r['id'] == $p['id_vb_room'] ) {
                    $vb_rooms[$index]['retrieval_url'] = $p['retrieval_url'];
                    $vb_rooms[$index]['id_assoc'] = $p['id'];
                }
            }
        }
		
        $this->assignRef('listings', $vb_rooms);
        $this->assignRef('module', $module);
        $this->assignRef('navbut', $navbut);
		
		// Display the template (default.php)
		parent::display($tpl);
		
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar($unique_key) {
		//Add menu title and some buttons to the page
		
		$title = "";
		
		switch($unique_key) {
            case VikChannelManagerConfig::AIRBNB: $title = "VCMMAINAIRBNBLISTINGS"; break;
            case VikChannelManagerConfig::FLIPKEY: $title = "VCMMAINFLIPKEYLISTINGS"; break;
            case VikChannelManagerConfig::HOLIDAYLETTINGS: $title = "VCMMAINHOLIDAYLETTINGSLISTINGS"; break;
            case VikChannelManagerConfig::WIMDU: $title = "VCMMAINWIMDULISTINGS"; break;
            case VikChannelManagerConfig::HOMEAWAY: $title = "VCMMAINHOMEAWAYLISTINGS"; break;
            case VikChannelManagerConfig::VRBO: $title = "VCMMAINVRBOLISTINGS"; break;
        }
		
		JToolBarHelper::title(JText::_($title), 'vikchannelmanager');
		JToolBarHelper::apply('saveListings', JText::_('SAVE'));
		JToolBarHelper::spacer();
		
	}
}
?>