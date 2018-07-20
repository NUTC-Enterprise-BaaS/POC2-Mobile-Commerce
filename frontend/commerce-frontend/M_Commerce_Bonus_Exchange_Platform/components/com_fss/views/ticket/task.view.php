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

class Task_View extends Task_Helper
{
	/**
	 * Updates priority for ticket
	 */
	function refresh()
	{
		$this->ticket_id = FSS_Input::getInt("ticketid");
		$this->ticket = new SupportTicket();
		if (!$this->ticket->load($this->ticket_id, true)) exit;	
		$this->ticket->loadAll();
		$this->ticket->setupUserPerimssions();
				
		include $this->view->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_messages.php');
		exit;
	}
	
	function passlist()
	{
		$email = FSS_Input::getEMail('email');
		if ($email == "") $email = FSS_Input::getEMail('reference');
		
		if ($email == "")
		{
			$link = FSSRoute::_("index.php?option=com_fss&view=ticket", false);	
			JFactory::getApplication()->redirect($link, JText::sprintf("UNABLE_TO_FIND_ANY_TICKETS_FOR_EMAIL",$email));
		}

		$tickets = new SupportTickets();
		$tickets->limitstart = 0;
		$tickets->limit = 500;
		$tickets->loadTicketsByQuery(array("t.email = '$email'"), "lastupdate DESC");
		
		if ($tickets->ticket_count > 0)
		{	
			FSS_EMail::User_Unreg_Passwords($tickets->tickets, $email);
			
			$link = FSSRoute::_("index.php?option=com_fss&view=ticket", false);	
			JFactory::getApplication()->redirect($link, JText::sprintf("A_LIST_OF_YOUR_TICKETS_AND_PASSWORDS_HAS_BEEN_SENT_TO_YOU",$email));
		} else {
			$link = FSSRoute::_("index.php?option=com_fss&view=ticket", false);	
			JFactory::getApplication()->redirect($link, JText::sprintf("UNABLE_TO_FIND_ANY_TICKETS_FOR_EMAIL",$email));
		}
	}
}