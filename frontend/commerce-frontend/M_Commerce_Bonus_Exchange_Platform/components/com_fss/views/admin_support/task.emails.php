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

class Task_EMails extends Task_Helper
{
	function approve()
	{
		$this->loadTicket();
		if ($this->ticket->source != "email")
			exit;
		
		$this->ticket->updateSource("email_accepted");

		$this->ticket->loadAttachments();
		$this->ticket->loadMessages(false, array(0));

		$body = "";
		if (count($this->ticket->messages) > 0)
			$body = reset($this->ticket->messages)->body;

		// trigger the emails to user and admin etc
		$action_name = "User_Open";
		$action_params = array('subject' => $this->ticket->title, 'user_message' => $body, 'files' => $this->ticket->attach);
		SupportActions::DoAction($action_name, $this->ticket, $action_params);
		
		exit;
	}

	function decline()
	{
		$this->loadTicket();
		if ($this->ticket->source != "email")
			return;
		
		$this->ticket->updateSource("email_declined");
		exit;
	}
	
	function delete()
	{
		$this->loadTicket();
		if ($this->ticket->source != "email_declined")
			return;
		
		$this->ticket->delete();
		exit;
	}
	
	private function loadTicket()
	{
		$ticketid = FSS_Input::getInt('ticketid');
		$this->ticket = new SupportTicket();
		$this->ticket->load($ticketid);
	}
}