<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );

class FsssViewMembers extends JViewLegacy
{
  
    function display($tpl = null)
    {
 		$groupid = JRequest::getVar('groupid');
		$db	= JFactory::getDBO();
		$qry = "SELECT * FROM #__fss_ticket_group WHERE id = '".FSSJ3Helper::getEscaped($db, $groupid)."'";
		$db->setQuery($qry);
		$group = $db->loadObject();
		$this->group =$group;
		
		$task = JRequest::getVar('task');
		
		if (JRequest::getVar('messages') != "")
			return $this->redirectMessages();
		
		if ($task == "setperm")
			return $this->SetPerm();	
		
		if ($task == "toggleallemail")
			return $this->ToggleAllEMail();
		
		if ($task == "toggleadmin")
			return $this->ToggleIsAdmin();
		
        JToolBarHelper::title( JText::_("TICKET_GROUP_MEMBERS") . " - " . $group->groupname, 'fss_groups' );
		//$bar= JToolBar::getInstance( 'toolbar' );
		//$bar->appendButton( 'Popup', 'new', "OLD", 'index.php?option=com_fss&view=listusers&tmpl=component&groupid='. $groupid, 630, 440 );
        JToolBarHelper::custom('popup','new', 'new', 'ADD_USERS', false);
		JToolBarHelper::deleteList();
        JToolBarHelper::cancel('cancellist','Close');
		FSSAdminHelper::DoSubToolbar();

		$this->lists = $this->get('Lists');
		
 		$query = 'SELECT * FROM #__fss_ticket_group ORDER BY groupname';
		$db->setQuery($query);
		$filter = $db->loadObjectList();
		$this->lists['groupid'] = JHTML::_('select.genericlist',  $filter, 'groupid', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'id', 'groupname', $this->lists['groupid']);
		$this->groupid = $groupid;

        $this->data = $this->get('Data');
        $this->pagination = $this->get('Pagination');

 		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::root().'components/com_fss/assets/css/popup.css'); 
		$document->addScript(JURI::root().'components/com_fss/assets/js/popup.js'); 

       parent::display($tpl);
    }

	function RedirectMessages()
	{
		$messages = explode("|", JRequest::getVar("messages"));
		foreach ($messages as $message)
		{
			JFactory::getApplication()->enqueueMessage($message, 'warning');
		}
		JFactory::getApplication()->redirect("index.php?option=com_fss&view=members&groupid=" . JRequest::getVar("groupid"));
	}
	
	function SetPerm()
	{
		$db	= JFactory::getDBO();
		
		$userid = JRequest::getVar('userid');
		$groupid = JRequest::getVar('groupid');
		$perm = JRequest::getVar('perm');
		
		$qry = "UPDATE #__fss_ticket_group_members SET allsee = '".FSSJ3Helper::getEscaped($db, $perm)."' WHERE user_id = '".FSSJ3Helper::getEscaped($db, $userid)."' AND group_id = '".FSSJ3Helper::getEscaped($db, $groupid)."'";
		
		$db->setQuery($qry);
		$db->Query();
		
		echo "1";
		
		exit;		
	}
	
	function ToggleIsAdmin()
	{
		$db	= JFactory::getDBO();
		
		$userid = JRequest::getVar('userid');
		$groupid = JRequest::getVar('groupid');
		
		$qry = "SELECT isadmin FROM #__fss_ticket_group_members WHERE user_id = '".FSSJ3Helper::getEscaped($db, $userid)."' AND group_id = '".FSSJ3Helper::getEscaped($db, $groupid)."'";
		$db->setQuery($qry);
		
		$current = $db->loadObject();
		$isadmin = $current->isadmin;
		$isadmin = 1 - $isadmin;
		
		$qry = "UPDATE #__fss_ticket_group_members SET isadmin = '".FSSJ3Helper::getEscaped($db, $isadmin)."' WHERE user_id = '".FSSJ3Helper::getEscaped($db, $userid)."' AND group_id = '".FSSJ3Helper::getEscaped($db, $groupid)."'";
		
		$db->setQuery($qry);
		$db->Query();
		
		echo FSS_GetYesNoText($isadmin);
		
		exit;		
		
	}
	
	function ToggleAllEMail()
	{
		$db	= JFactory::getDBO();
		
		$userid = JRequest::getVar('userid');
		$groupid = JRequest::getVar('groupid');
		
		$qry = "SELECT allemail FROM #__fss_ticket_group_members WHERE user_id = '".FSSJ3Helper::getEscaped($db, $userid)."' AND group_id = '".FSSJ3Helper::getEscaped($db, $groupid)."'";
		$db->setQuery($qry);
		
		$current = $db->loadObject();
		$allemail = $current->allemail;
		$allemail = 1 - $allemail;
		
		$qry = "UPDATE #__fss_ticket_group_members SET allemail = '".FSSJ3Helper::getEscaped($db, $allemail)."' WHERE user_id = '".FSSJ3Helper::getEscaped($db, $userid)."' AND group_id = '".FSSJ3Helper::getEscaped($db, $groupid)."'";
		
		$db->setQuery($qry);
		$db->Query();
		
		echo FSS_GetYesNoText($allemail);
		
		exit;		
		
	}
}



