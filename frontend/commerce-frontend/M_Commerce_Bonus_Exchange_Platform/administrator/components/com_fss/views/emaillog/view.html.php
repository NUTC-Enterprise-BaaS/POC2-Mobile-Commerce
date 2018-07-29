<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');

class FsssViewEMailLog extends JViewLegacy
{
	function display($tpl = null)
	{
		$document = JFactory::getDocument();
		JHtml::_('behavior.framework');
		JHTML::_('behavior.tooltip');

		$task = JRequest::getVar('task');
		JToolBarHelper::title( JText::_("EMail_Log"), 'fss_cronlog' );
		JToolBarHelper::cancel('cancellist');
		FSSAdminHelper::DoSubToolbar();
		
		if ($task == "cancellist")
			return $this->BackToEmails();

		if ($task == "clear")
			return $this->ClearCronLog();

		$this->DisplayList();
	}

	function BackToEmails()
	{
		$mainframe = JFactory::getApplication();
		$link = FSSRoute::_('index.php?option=com_fss&view=fsss',false);
		$mainframe->redirect($link);
	}

	function ClearCronLog()
	{
		$db = JFactory::getDBO();
		$qry = "TRUNCATE #__fss_cron_log";
		$db->SetQuery($qry);
		$db->Query($qry);
		$mainframe = JFactory::getApplication();
		$link = FSSRoute::_('index.php?option=com_fss&view=cronlog',false);
		$mainframe->redirect($link);
	}

	function DisplayList()
	{
		JHTML::_('behavior.modal', 'a.modal');

		$page = JRequest::getVar('page',0);
		$perpage = 20;

		$date = JRequest::getVar('date');
		$qry = "SELECT DATE(`firstseen`) as `date`, DATE(`firstseen`) as `label` FROM #__fss_ticket_email_log GROUP BY `date` ORDER BY `date` DESC";
		$db = JFactory::getDBO();
		$db->setQuery($qry);
		$dates = array();
		$dates[] = JHTML::_('select.option', '', JText::_("SELECT_DATE"), 'date', 'label');
		$dates = array_merge($dates, $db->loadObjectList());
		$datelist = JHTML::_('select.genericlist',  $dates, 'date', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'date', 'label', $date);
		$this->dates = $datelist;
		
		
		$account = JRequest::getVar('account');
		$qry = "SELECT id, CONCAT(name, ' / ', username) as title FROM #__fss_ticket_email";
		$db = JFactory::getDBO();
		$db->setQuery($qry);
		$tasks = array();
		$tasks[] = JHTML::_('select.option', '', JText::_("SELECT_ACCOUNT"), 'id', 'title');
		$tasks = array_merge($tasks, $db->loadObjectList());
		$takslist = JHTML::_('select.genericlist',  $tasks, 'account', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'id', 'title', $account);
		$this->account = $takslist;
		
		$status = JRequest::getVar('emailstatus');
		$statuss = array();
		$statuss[] = JHTML::_('select.option', '', JText::_("SELECT_STATUS"), 'id', 'title');
		$statuss[] = JHTML::_('select.option', 2, JText::_("FSS_EMR_SKIP_TO"), 'id', 'title');
		$statuss[] = JHTML::_('select.option', 3, JText::_("FSS_EMR_SKIP_FROM"), 'id', 'title');
		$statuss[] = JHTML::_('select.option', 4, JText::_("FSS_EMR_SKIP_SUBJECT"), 'id', 'title');
		$statuss[] = JHTML::_('select.option', 5, JText::_("FSS_EMR_SKIP_REGONLY"), 'id', 'title');
		$statuss[] = JHTML::_('select.option', 6, JText::_("FSS_EMR_SKIP_NOTREPLY"), 'id', 'title');
		$statuss[] = JHTML::_('select.option', 7, JText::_("FSS_EMR_SKIP_UNKNOWNEMAIL"), 'id', 'title');
		$statuss[] = JHTML::_('select.option', 12, JText::_("FSS_EMR_SKIP_BULK"), 'id', 'title');
		$statuss[] = JHTML::_('select.option', 8, JText::_("FSS_EMR_REPLY_REG"), 'id', 'title');
		$statuss[] = JHTML::_('select.option', 9, JText::_("FSS_EMR_REPLY_UNREG"), 'id', 'title');
		$statuss[] = JHTML::_('select.option', 10, JText::_("FSS_EMR_OPEN_REG"), 'id', 'title');
		$statuss[] = JHTML::_('select.option', 11, JText::_("FSS_EMR_OPEN_UNREG"), 'id', 'title');
		
		$this->statuslist = JHTML::_('select.genericlist',  $statuss, 'emailstatus', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'id', 'title', $status);

		$qry = "SELECT * FROM #__fss_ticket_email_log ";
		$wheres = array();
		if ($date)
			$wheres[] = " DATE(firstseen) = '".FSSJ3Helper::getEscaped($db, $date)."' ";
		if ($account)
			$wheres[] = " accountid = '".FSSJ3Helper::getEscaped($db, $account)."' ";
		if ($status)
		$wheres[] = " status = '".FSSJ3Helper::getEscaped($db, $status)."' ";

		if (count($wheres) > 0)
			$qry .= "WHERE " . implode(" AND " , $wheres);
		$qry .= " ORDER BY firstseen desc";
		$db = JFactory::getDBO();
		$db->setQuery($qry);
		$db->query();
		$rowcount = $db->getNumRows();

		if ($rowcount > $perpage)
		{
			$db->setQuery($qry, $page * $perpage, $perpage);
		}
		$rows = $db->loadObjectList();
		
		$this->rows = $rows;
		$pagecount = ceil($rowcount / $perpage);

		$this->pagecount = $pagecount;
		$this->page = $page;
		parent::display();	
	}
}
