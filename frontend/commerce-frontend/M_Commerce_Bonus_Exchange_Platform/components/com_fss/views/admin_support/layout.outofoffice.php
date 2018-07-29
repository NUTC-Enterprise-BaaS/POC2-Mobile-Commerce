<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');

class FssViewAdmin_Support_OutOfOffice extends FssViewAdmin_Support
{
	function display($tpl = NULL)
	{
		$this->current_userid = JFactory::getUser()->id;
		$this->userid = FSS_Input::getInt('user_id', $this->current_userid);		
		if (!FSS_Permission::auth("fss.ticket_admin.ooo", "com_fss.support_admin", JFactory::getUser()->id))
			$this->userid = $this->current_userid;		
		$this->user = JFactory::getUser($this->userid);
		$this->loadTicketList();

		$values = SupportUsers::getAllSettings($this->userid);

		if ($values->out_of_office)
		{
			return $this->showUserOut();	
		} else {
			return $this->showUserIn();
		}
		//
	}
	
	function showUserIn()
	{
		FSS_Helper::AddSCEditor();
		
		$this->handlers = SupportUsers::getHandlers(false, true);
					
		$this->_display("in");
	}
	
	function showUserOut()
	{
		$this->_display("out");
	}
	
	function loadTicketList()
	{
		$this->tickets = new SupportTickets();
		$this->tickets->limitstart = 0;
		$this->tickets->limit = 250;
		
		$status_list = FSS_Ticket_Helper::GetStatusIDs("is_closed", true); // Get all open ticket status
		if (count($status_list) < 1)
			$status_list[] = 0;
		
		$this->tickets->loadTicketsByQuery(
			array(
					"t.admin_id = " . $this->userid,
					"t.ticket_status_id IN (" . implode(", ", $status_list) . ")"
				));	
	}
	
	function displayTickets()
	{
		$file = JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'layout.list.php';
		require_once($file);
		
		$obj = new FssViewAdmin_Support_List();
		$obj->ticket_list = $this->tickets;
		$obj->ticket_count = $this->tickets->ticket_count;
		$obj->refresh = 2;
		$obj->ticket_view = "open";
		$obj->merge = false;
		$obj->show_key = false;
		$obj->displayTicketList();		
	}
}