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

class VikChannelManagerViewconfig extends JViewLegacy {
	
	function display($tpl = null) {
		// Set the toolbar
		$this->addToolBar();
		
		VCM::load_css_js();
		
		$dbo = JFactory::getDBO();
		
		$config = VikChannelManager::loadConfiguration();
		
		$module = VikChannelManager::getActiveModule(true);
		$more_accounts = array();
		if($module['av_enabled'] == 1) {
			//Important: do not change the order by clause becuase the task rmchaccount (for the account removal) takes the index of the associative array returned by this query
			$q = "SELECT `prop_name`,`prop_params`, COUNT(DISTINCT `idroomota`) AS `tot_rooms` FROM `#__vikchannelmanager_roomsxref` WHERE `idchannel`=".(int)$module['uniquekey']." GROUP BY `prop_params` ORDER BY `prop_name` ASC;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if( $dbo->getNumRows() > 1 ) {
				$other_accounts = $dbo->loadAssocList();
				foreach ($other_accounts as $oacc) {
					if(!empty($oacc['prop_params'])) {
						$oacc['active'] = $oacc['prop_params'] == $module['params'] ? 1 : 0;
						$more_accounts[] = $oacc;
					}
				}
				if(!(count($more_accounts) > 1)) {
					$more_accounts = array();
				}
			}
		}

		if( !empty($module['id']) ) {
			$module['params'] = json_decode($module['params'], true);
			$module['settings'] = json_decode($module['settings'], true);
		}
		
		$vb_payments = array();
		$q = "SELECT `id`, `name`, `published` FROM `#__vikbooking_gpayments` ORDER BY `published` DESC, `name` ASC;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if( $dbo->getNumRows() > 0 ) {
			$vb_payments = $dbo->loadAssocList();
		}
		
		$q = "SELECT `id` FROM `#__vikchannelmanager_channel` WHERE `av_enabled`=1 LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$show_sync = ($dbo->getNumRows() > 0);
		
		$this->assignRef('config', $config);
		$this->assignRef('module', $module);
		$this->assignRef('more_accounts', $more_accounts);
		$this->assignRef('vb_payments', $vb_payments);
		$this->assignRef('showSync', $show_sync);
		
		// Display the template (default.php)
		parent::display($tpl);
		
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() {
		//Add menu title and some buttons to the page
		JToolBarHelper::title(JText::_('VCMMAINTCONFIG'), 'vikchannelmanager');
		JToolBarHelper::apply( 'saveconfig', JText::_('SAVE'));
		JToolBarHelper::spacer();
		JToolBarHelper::cancel( 'cancel', JText::_('CANCEL'));
		JToolBarHelper::spacer();
		
	}
}
?>