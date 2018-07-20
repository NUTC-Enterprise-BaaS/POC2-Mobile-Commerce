<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'task.php');

/**
 * Stuff related to archiving and deleting tickets
 **/

class Task_Archive extends Task_Helper
{
	function archive()
	{
		// load in tickets to do		
		$ticketid = FSS_Input::getInt('ticketid');
		$tickets = FSS_Input::getCmd('tickets');
		
		$def_archive = FSS_Ticket_Helper::GetStatusID('def_archive');

		if ($ticketid > 0)
		{
			$ticket = new SupportTicket();
			if ($ticket->load($ticketid))
				$ticket->updateStatus($def_archive);
			
		} else if ($tickets != '')
		{
			$tickets = new SupportTickets();
			$tickets->limitstart = 0;
			$tickets->limit = 100;
			
			$tickets->loadTicketsByStatus($tickets);
	
			foreach ($tickets->tickets as $ticket)
				$ticket->updateStatus($def_archive);
		}
		
		JFactory::getApplication()->redirect($_SERVER['HTTP_REFERER']);
	}
	
	function delete()
	{
		// load in tickets to do		
		$ticketid = FSS_Input::getInt('ticketid');
		$tickets = FSS_Input::getCmd('tickets');
		
		$def_archive = FSS_Ticket_Helper::GetStatusID('def_archive');

		if ($ticketid > 0)
		{
			$ticket = new SupportTicket();
			if ($ticket->load($ticketid))
				$ticket->delete();
			
		} else if ($tickets != '')
		{
			$tickets = new SupportTickets();
			$tickets->limitstart = 0;
			$tickets->limit = 100;
			
			$tickets->loadTicketsByStatus($tickets);
	
			foreach ($tickets->tickets as $ticket)
				$ticket->delete();
		}
		
		JFactory::getApplication()->redirect($_SERVER['HTTP_REFERER']);
	}
}