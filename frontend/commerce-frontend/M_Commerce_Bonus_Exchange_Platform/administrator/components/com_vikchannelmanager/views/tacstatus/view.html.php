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

class VikChannelManagerViewtacstatus extends JViewLegacy {
	
	function display($tpl = null) {
		// Set the toolbar
		$this->addToolBar();
		
		VCM::load_css_js();
		
		$dbo = JFactory::getDBO();
		
		$partner_id = VikChannelManager::getTripConnectPartnerID(true);
		$tac_api_key = VikChannelManager::getTripConnectApiKey(true);
		
		$_url = 'https://api.tripadvisor.com/api/partner/1.0/location_mappings/'.$partner_id.'?key='.$tac_api_key;
		
		$ch = curl_init($_url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$rs = curl_exec($ch);
		if(curl_errno($ch)) {
			JError::raiseWarning('', $rs);
			return;
		}
		curl_close($ch);
		
		$rs = json_decode($rs, true);
				
		$this->assignRef('contents', $rs);
		
		// Display the template (default.php)
		parent::display($tpl);
		
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() {
		//Add menu title and some buttons to the page
		JToolBarHelper::title(JText::_('VCMMAINTSTATUSTAC'), 'vikchannelmanager');
		
	}
	
}
?>