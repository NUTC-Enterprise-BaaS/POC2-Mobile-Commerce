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

class VikChannelManagerViewdashboard extends JViewLegacy {
	
	function display($tpl = null) {
		// Set the toolbar
		$this->addToolBar();
		
		//No longer needed to run the backward compatibility function. Outdated VCM versions should be uninstalled and re-installed.
		//$this->backwardCompatibility();

		VCM::load_css_js();
		
		$dbo = JFactory::getDBO();
		$mainframe = JFactory::getApplication();
		
		$rmnotifications = JRequest::getString('rmnotifications', '', 'request');
		$notsids = JRequest::getVar('notsids', array());
		
		$lim = $mainframe->getUserStateFromRequest("com_vikchannelmanager.limit", 'limit', $mainframe->getCfg('list_limit'), 'int');
		$lim0 = JRequest::getVar('limitstart', 0, '', 'int');
		
		if( strlen($rmnotifications) > 0 && count($notsids) > 0 ) {
			$lim = 15;
			$lim0 = 0;
			foreach($notsids as $notid) {
				if (!empty($notid)) {
					$q = "DELETE FROM `#__vikchannelmanager_notifications` WHERE `id`='".$notid."';";
					$dbo->setQuery($q);
					$dbo->Query($q);
				}
			}
		}
		
		$notifications = array();
		
		$q = "SELECT SQL_CALC_FOUND_ROWS `n`.* FROM `#__vikchannelmanager_notifications` AS `n` ORDER BY `n`.`ts` DESC";
		$dbo->setQuery($q, $lim0, $lim);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$notifications = $dbo->loadAssocList();
			$dbo->setQuery('SELECT FOUND_ROWS();');
			jimport('joomla.html.pagination');
			$pageNav = new JPagination( $dbo->loadResult(), $lim0, $lim );
			$navbut="<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
			$nparent_ids = array();
			foreach ($notifications as $nf) {
				$nparent_ids[] = $nf['id'];
			}
			$q = "SELECT * FROM `#__vikchannelmanager_notification_child` WHERE `id_parent` IN (".implode(',', $nparent_ids).");";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() > 0) {
				$children = $dbo->loadAssocList();
				foreach ($notifications as $nk => $nf) {
					$notifications[$nk]['children'] = array();
					foreach ($children as $child) {
						if ($nf['id'] == $child['id_parent']) {
							$notifications[$nk]['children'][] = $child;
						}
					}
				}
			}
		}
		
		$active_channels = array();
		$q = "SELECT `uniquekey` FROM `#__vikchannelmanager_channel`;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if( $dbo->getNumRows() > 0 ) {
			$active_channels = $dbo->loadAssocList();
			for( $i = 0; $i < count($active_channels); $i++ ) {
				$active_channels[$i] = $active_channels[$i]['uniquekey'];
			}
		}
		
		$config = VikChannelManager::loadConfiguration();
        
        $q = "SELECT `id` FROM `#__vikchannelmanager_channel` WHERE `av_enabled`=1 LIMIT 1;";
        $dbo->setQuery($q);
        $dbo->Query($q);
        $show_sync = ($dbo->getNumRows() > 0);
		
		$this->assignRef('config', $config);
		$this->assignRef('notifications', $notifications);
		$this->assignRef('activeChannels', $active_channels);
		$this->assignRef('lim0', $lim0);
		$this->assignRef('navbut', $navbut);
        $this->assignRef('showSync', $show_sync);
		
		// Display the template (default.php)
		parent::display($tpl);
		
		
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() {
		//Add menu title and some buttons to the page
		JToolBarHelper::title(JText::_('VCMMAINTDASHBOARD'), 'vikchannelmanager');
		
		if (JFactory::getUser()->authorise('core.admin', 'com_vikchannelmanager')) {
			JToolBarHelper::preferences('com_vikchannelmanager');
		}
		
	}

	/**
	* Backward Compatibility with VCM 1.3.1 (front folder helpers + lang)
	*/
	protected function backwardCompatibility() {
		require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikchannelmanager'.DS.'helpers'.DS.'upd.installer.php');
		if(file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikchannelmanager'.DS.'assets'.DS.'tmp'.DS.'helpers'.DS.'lib.vikchannelmanager.php')) {
			$res = true;
			$site_folders = array(
				array(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikchannelmanager'.DS.'assets'.DS.'tmp'.DS.'helpers', JPATH_SITE.DS.'components'.DS.'com_vikchannelmanager'.DS.'helpers'),
			);
			
			foreach( $site_folders as $folder ) {
				if(!VikUpdaterInstaller::smartCopy($folder[0], $folder[1])) {
					$res = false;
					JError::raiseWarning('', 'Please report to e4j: Error copying the folder: '.$folder[0].' - to: '.$folder[1]);
				}
			}

			$res = VikUpdaterInstaller::copyFile(
				JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikchannelmanager'.DS.'assets'.DS.'tmp'.DS.'en-GB.com_vikchannelmanager.ini',
				JPATH_SITE.DS.'language'.DS.'en-GB'.DS.'en-GB.com_vikchannelmanager.ini'
			) && $res;
			
			//remove the file from tmp
			if($res === true) {
				unlink(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikchannelmanager'.DS.'assets'.DS.'tmp'.DS.'helpers'.DS.'lib.vikchannelmanager.php');
			}
			//
		}

	}
}
?>