<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );
jimport('joomla.filesystem.file');
require_once (JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_fss'.DS.'updatedb.php');

class FsssViewBackup extends JViewLegacy
{
    function display($tpl = null)
    {
        JToolBarHelper::title( JText::_("ADMINISTRATION"), 'fss_admin' );
        JToolBarHelper::cancel('cancellist');
		FSSAdminHelper::DoSubToolbar();
		
		$this->log = "";
		
		$task = JRequest::getVar('task');
		$updater = new FSSUpdater();
			
		if ($task == "saveapi")
		{
			return $this->SaveAPI();
				
		}
		if ($task == "cancellist")
		{
			$mainframe = JFactory::getApplication();
			$link = FSSRoute::_('index.php?option=com_fss&view=fsss',false);
			$mainframe->redirect($link);
			return;			
		}
		if ($task == "update")
		{
			$this->log = $updater->Process();
			parent::display();
			return;
		}
				
		if ($task == "backup")
		{
			$this->log = $updater->BackupData('fss');
		}
		
		if ($task == "restore")
		{
			// process any new file uploaded
			$file = JRequest::getVar('filedata', '', 'FILES', 'array');
			if (array_key_exists('error',$file) && $file['error'] == 0)
			{
				$data = file_get_contents($file['tmp_name']);
				$data = unserialize($data);
				
				global $log;
				$log = "";
				$log = $updater->RestoreData($data);
				$this->log = $log;
				parent::display();
				return;
			}
			
		}
		
        parent::display($tpl);
    }
	
	function SaveAPI()
	{
		$username = JRequest::getVar('username');
		$apikey = JRequest::getVar('apikey');

		$db = JFactory::getDBO();
		
		$qry = "REPLACE INTO #__fss_settings (setting, value) VALUES ('fsj_username','".FSSJ3Helper::getEscaped($db, $username)."')";
		$db->setQuery($qry);
		$db->Query();
		
		$qry = "REPLACE INTO #__fss_settings (setting, value) VALUES ('fsj_apikey','".FSSJ3Helper::getEscaped($db, $apikey)."')";
		$db->setQuery($qry);
		$db->Query();
		
		// update url links
		$updater = new FSSUpdater();
		$updater->SortAPIKey($username, $apikey);
		
		$mainframe = JFactory::getApplication();
		$link = FSSRoute::_('index.php?option=com_fss&view=backup',false);
		$mainframe->redirect($link);
	}
}
