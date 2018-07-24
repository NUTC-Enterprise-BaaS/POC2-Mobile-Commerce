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

class VikChannelManagerViewordersvb extends JViewLegacy {
	
	function display($tpl = null) {
		// Set the toolbar
		$this->addToolBar();
		
		VCM::load_css_js();
		
		$dbo = JFactory::getDBO();
		$mainframe = JFactory::getApplication();
		
		$confirmnumber = JRequest::getString('confirmnumber', '', 'request');
		$ordersfound = false;
		
		$lim = $mainframe->getUserStateFromRequest("com_vikchannelmanager.limit", 'limit', $mainframe->getCfg('list_limit'), 'int');
		$lim0 = JRequest::getVar('limitstart', 0, '', 'int');
		
		$rows = array();
		
		if( !empty($confirmnumber) ) {
			$q = "SELECT SQL_CALC_FOUND_ROWS * FROM `#__vikbooking_orders` WHERE `confirmnumber` LIKE '%".$confirmnumber."%' AND `status`<>'cancelled' ORDER BY `#__vikbooking_orders`.`ts` DESC";
			$dbo->setQuery($q, $lim0, $lim);
			$dbo->Query($q);
			if( $dbo->getNumRows() > 0) {
				$rows = $dbo->loadAssocList();
				$dbo->setQuery('SELECT FOUND_ROWS();');
				$totres = $dbo->loadResult();
				$ordersfound = true;
				jimport('joomla.html.pagination');
				$pageNav = new JPagination( $totres, $lim0, $lim );
				$navbut = "<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
			}
		}
		
		if( !$ordersfound ) {
			$q = "SELECT SQL_CALC_FOUND_ROWS * FROM `#__vikbooking_orders` WHERE `status`<>'cancelled' ORDER BY `#__vikbooking_orders`.`ts` DESC";
			$dbo->setQuery($q, $lim0, $lim);
			$dbo->Query($q);
			if( $dbo->getNumRows() > 0 ) {
				$rows = $dbo->loadAssocList();
				$dbo->setQuery('SELECT FOUND_ROWS();');
				jimport('joomla.html.pagination');
				$pageNav = new JPagination( $dbo->loadResult(), $lim0, $lim );
				$navbut = "<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
			} 
		}

		
		$config = VikChannelManager::loadConfiguration();
		
		$this->assignRef('config', $config);
		$this->assignRef('rows', $rows);
		$this->assignRef('lim0', $lim0);
		$this->assignRef('navbut', $navbut);
		
		// Display the template (default.php)
		parent::display($tpl);
		
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() {
		//Add menu title and some buttons to the page
		JToolBarHelper::title(JText::_('VCMMAINTORDERSVB'), 'vikchannelmanager');
		
		JToolBarHelper::custom( 'resend_arq_confirm', 'send', 'send', JText :: _('VCMTLBNOTIFYORDERTOOTA'), true, false);
		JToolBarHelper::spacer();
		JToolBarHelper::cancel( 'cancel', JText::_('CANCEL'));
		JToolBarHelper::spacer();
		
	}
}
?>