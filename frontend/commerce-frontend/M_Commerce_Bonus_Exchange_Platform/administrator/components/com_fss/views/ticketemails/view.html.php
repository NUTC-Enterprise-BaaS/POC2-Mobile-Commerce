<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );
jimport('joomla.utilities.date');


class fsssViewticketemails extends JViewLegacy
{
  
    function display($tpl = null)
    {
		$test = JRequest::getVar('test');
		if ($test > 0)
			return $this->TestAccount($test);
		
		JToolBarHelper::title( JText::_( 'TICKET_EMAIL_ACCOUNT_MANAGER' ), 'fss_emailaccounts' );
		
        JToolBarHelper::custom('log','html','html','View Log',false);
		
		JToolBarHelper::deleteList();
        JToolBarHelper::editList();
		
        JToolBarHelper::addNew();
		JToolBarHelper::cancel('cancellist');
		FSSAdminHelper::DoSubToolbar();

		$task = JRequest::GetVar('task');
		if ($task == "log")
			return $this->ViewLog();
		
        $lists = $this->get('Lists');

		$document = JFactory::getDocument();
		//JHTML::_( 'behavior.mootools' );
 		//$document->addStyleSheet( JURI::base() . 'components/com_fss/assets/slimbox/slimbox.css' );
		//$document->addScript( JURI::base() .'components/com_fss/assets/slimbox/slimbox.js');


		$pubslihed = array();
		$pubslihed[] = JHTML::_('select.option', '-1', JText::_('Is_Published'), 'id', 'title');
		$pubslihed[] = JHTML::_('select.option', '1', JText::_('Published'), 'id', 'title');
		$pubslihed[] = JHTML::_('select.option', '0', JText::_('Unpublished'), 'id', 'title');
		$lists['published'] = JHTML::_('select.genericlist',  $pubslihed, 'ispublished', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'id', 'title', $lists['ispublished']);

		$this->lists = $lists;

        $this->data = $this->get('Data');
        $this->pagination = $this->get('Pagination');

		$this->imap_ok = function_exists("imap_open");
		$this->ini_location = $this->GetINILocation();
		
        parent::display($tpl);
    }

	function GetINILocation()
	{
		ob_start();
		phpinfo();
		$phpinfo_full = ob_get_contents();
		ob_end_clean();

		// Remove all <> tags from $phpinfo
		$phpinfo = preg_replace ('/<[^>]*>/', '', $phpinfo_full);

		// Find the php.ini location
		preg_match ('/Loaded Configuration File[ \t]*([^ \t\n]*)/', $phpinfo, $matches);
		$cfgfile = $matches[1];
		if (!$cfgfile) {
			return "Unknown";
		} 		
		
		return $cfgfile;
	}

	function ViewLog()
	{
		$mainframe = JFactory::getApplication();
		$link = FSSRoute::_('index.php?option=com_fss&view=emaillog',false);
		$mainframe->redirect($link);		
	}

	function TestAccount($id)
	{
		$db	= JFactory::getDBO();
		$qry = "SELECT * FROM #__fss_ticket_email WHERE id = '" . FSSJ3Helper::getEscaped($db, $id) . "'";
		$db->setQuery($qry);
		$row = $db->loadAssoc();

		$class = "FSSCronEMailCheck";
		$file = "emailcheck.php";

		$path = JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'cron'.DS;
		if (file_exists($path.$file))
		{
			require_once($path.$file);
			$inst = new $class();
			if ($inst->Test($row))
			{
				echo "Connect String: " . $inst->connect_string . "<br>";
				echo "OK: " . $inst->count . " emails in inbox";	
			} else {
				echo "Connect String: " . $inst->connect_string . "<br>";
				echo "ERROR: " . $inst->error;
			}
		} else {
			echo "ERROR: Unable to load email cron plugin";	
		}

		exit;
	}
}


