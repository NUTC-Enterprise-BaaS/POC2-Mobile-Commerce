<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_ticket.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_helper.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'fields.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_print.php');

class FssViewAdmin_Support_Multiprint extends FssViewAdmin_Support
{
	function display($tpl = NULL)
	{
		$this->ticket_ids = FSS_Input::getString("ticketids");
		$this->ticket_ids = explode(":", trim($this->ticket_ids));
		
		$this->tickets = array();
		
		foreach ($this->ticket_ids as $ticketid)
		{
			$ticket = new SupportTicket();
			if ($ticket->load($ticketid))
			{
				$ticket->loadAll();
				$this->tickets[] = $ticket;
			}
		}
		
		$this->print = FSS_Input::getCmd('print');
		$this->_display();
	}	
}
