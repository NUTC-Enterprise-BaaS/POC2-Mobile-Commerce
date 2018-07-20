<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'task.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_ticket.php');

/**
 * Stuff related to archiving and deleting tickets
 **/

class Task_OutOfOffice extends Task_Helper
{
	function out()
	{
		$current_user = JFactory::getUser()->id;		
		$manager = FSS_Permission::auth("fss.ticket_admin.ooo", "com_fss.support_admin", JFactory::getUser()->id);		
		$user_id = FSS_Input::getInt('user_id');
		if (!$manager && $current_user != $user_id)
			return $this->cancel();

		// update the current users setting
		$values = SupportUsers::getAllSettings($user_id);
		$values->out_of_office = 1;
		SupportUsers::updateUserSettings($values, $user_id);
		
		$assign = FSS_Input::getCmd('assign');
		$handler = FSS_Input::getInt('handler');
		$body = FSS_Input::getHTML('body');
		
		if ($assign == "auto" || $assign == "handler")
		{
			
			$this->loadTicketList($user_id);

			foreach ($this->tickets->tickets as $ticket)
			{
				if ($assign == "auto")
				{
					$params = array(
						'title' => $ticket->title,
						'user_id' => $ticket->user_id,
						'email' => $ticket->email,
						'unregname' => $ticket->unregname,
						'source' => 'outofoffice'
						);
			
					$handler = FSS_Ticket_Helper::AssignHandler($ticket->prod_id, $ticket->ticket_dept_id, $ticket->ticket_cat_id, true, $params);
				}
				if ($assign == "unassigned")
					$handler = 0;
				
				$ticket->assignHandler($handler);
				if ($body)
				{
					$ticket->addMessage($body, "", $user_id, TICKET_MESSAGE_PRIVATE);
					FSS_EMail::Admin_Forward($ticket, $ticket->title, $body);
				}
			}
		}

		JFactory::getApplication()->redirect(FSSRoute::_("index.php?option=com_fss&view=admin_support&layout=listhandlers", false));	
	}
	
	function in()
	{
		$current_user = JFactory::getUser()->id;		
		$manager = FSS_Permission::auth("fss.ticket_admin.ooo", "com_fss.support_admin", JFactory::getUser()->id);		
		$user_id = FSS_Input::getInt('user_id');
		if (!$manager && $current_user != $user_id)
			return $this->cancel();

		// update the current users setting
		$values = SupportUsers::getAllSettings($user_id);
		$values->out_of_office = 0;
		SupportUsers::updateUserSettings($values, $user_id);
		
		JFactory::getApplication()->redirect(FSSRoute::_("index.php?option=com_fss&view=admin_support&layout=listhandlers", false));	
	}
	
	function loadTicketList($userid)
	{
		$this->tickets = new SupportTickets();
		$this->tickets->limitstart = 0;
		$this->tickets->limit = 250;
		
		$status_list = FSS_Ticket_Helper::GetStatusIDs("is_closed", true); // Get all open ticket status
		if (count($status_list) < 1)
			$status_list[] = 0;
		
		$this->tickets->loadTicketsByQuery(
			array(
					"t.admin_id = " . $userid,
					"t.ticket_status_id IN (" . implode(", ", $status_list) . ")"
					));	
	}
	
	function cancel()
	{
		JFactory::getApplication()->redirect(FSSRoute::_("index.php?option=com_fss&view=admin_support", false));	
	}
}	 	 	 	 	 	 	