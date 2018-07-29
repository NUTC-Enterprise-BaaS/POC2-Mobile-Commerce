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

class VikChannelManagerViewresendarqconfirm extends JViewLegacy {
	
	function display($tpl = null) {
		// Set the toolbar
		$this->addToolBar();
		
		VCM::load_css_js();
		
		$cid = JRequest::getVar('cid', array(0));
		
		$dbo = JFactory::getDBO();
		$mainframe = JFactory::getDBO();
		
		$vborders = array();
		
		if(count($cid) > 0) {
			$q = "SELECT * FROM `#__vikbooking_orders` WHERE `id` IN (".implode(",", $cid).") AND `status`='confirmed';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if( $dbo->getNumRows() > 0 ) {
				$vborders = $dbo->loadAssocList();
				foreach($vborders as $k => $v) {
					$notifications = VikChannelManager::loadOrdersVbNotifications($v['id']);
					$vborders[$k]['notifications'] = is_array($notifications) ? $notifications : array();
				}
			} else {
				JError::raiseWarning('', JText::_('VCMNOVBVALIDORDFOUND'));
				$mainframe->redirect("index.php?option=com_vikchannelmanager&task=ordersvb");
			}
		} else {
			JError::raiseWarning('', JText::_('VCMNOVBVALIDORDFOUND'));
			$mainframe->redirect("index.php?option=com_vikchannelmanager&task=ordersvb");
		}

		$this->assignRef('orders', $vborders);
		
		// Display the template (default.php)
		parent::display($tpl);
		
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() {
		//Add menu title and some buttons to the page
		JToolBarHelper::title(JText::_('VCMMAINTARQCONFIRM'), 'vikchannelmanager');
		JToolBarHelper::cancel( 'cancelorders', JText::_('CANCEL'));
	}
}
?>