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

class Task_Batch extends Task_Helper
{
	function process()
	{	
		$posted = JRequest::get('post');
		$url = FSS_Input::GetString("return");

		$ticket_ids = array();
		
		foreach ($posted as $var => $value)
		{
			if (substr($var, 0, 7) == "ticket_")
			{
				$ticket_id = (int)substr($var, 7);
				if ($ticket_id > 0)
					$ticket_ids[$ticket_id] = $ticket_id;
			}	
		}
		
		if (count($ticket_ids) == 0)
		{
			if ($url) JFactory::getApplication()->redirect($url);	
			return;
		}
		
		$db = JFactory::getDBO();
		
		$tickets = array();

		foreach ($ticket_ids as $ticketid)
		{
			$ticket = new SupportTicket();
			if ($ticket->Load($ticketid))
			{
				$ticket->is_batch = true;
				$tickets[$ticketid] = $ticket;
			} else {
				unset($ticket_ids[$ticket_id]);
			}
		}
				
		$new_pri = FSS_Input::getInt('batch_priority');
		if ($new_pri > 0)
		{
			foreach ($ticket_ids as $ticketid)
				$tickets[$ticketid]->updatePriority($new_pri);
		}
				
		$new_status = FSS_Input::getInt('batch_status');
		if ($new_status > 0)
		{
			foreach ($ticket_ids as $ticketid)
				$tickets[$ticketid]->updateStatus($new_status, true, true);
		}
				
		if (FSS_Input::getString('batch_handler') != "")
		{
			foreach ($ticket_ids as $ticketid)
				$tickets[$ticketid]->assignHandler(FSS_Input::getInt('batch_handler'));
		}	
		
		$should_delete = FSS_Input::getCmd('batch_status');
		if ($should_delete == "delete")
			foreach ($ticket_ids as $ticketid)
				$tickets[$ticketid]->delete();
		
		if ($url) JFactory::getApplication()->redirect($url);	
	}
}