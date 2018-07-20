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
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_tickets.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_print.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'multicol.php');

class FssViewTicket_View extends FssViewTicket
{
	function display($tpl = NULL)
	{
		$this->ticketid = FSS_Input::getInt("ticketid");

		if (!$this->validateUser()) return;
		
		$this->ticket = new SupportTicket();
		if (!$this->ticket->load($this->ticketid, $this->user_type))
		{
			return $this->noPermission();
		}
		
		$this->ticket->loadAll(!FSS_Settings::get('support_user_reverse_messages'));
		$this->ticket->setupUserPerimssions();
		
		SupportSource::doUser_View_Redirect($this->ticket);

		$this->redirectMergedTickets();
		$this->loadMergedTickets();
		$this->updateTicketLanguage();
		$this->pris = SupportHelper::getPrioritiesUser();
		$this->statuss = SupportHelper::getStatussUser();
		
		$errors['subject'] = '';
		$errors['body'] = '';
		$errors['cat'] = '';
		$this->errors = $errors;

		$this->ticket_view = "ticket";
		$this->loadCounts();
		
		if (FSS_Input::getCmd('print')) return $this->_display("print");
		
		FSS_Helper::IncludeModal();
		FSS_Helper::AddSCEditor(true);
		FSS_Helper::StylesAndJS(array('scrollsneak'));
		
		$this->_display();
	}	
	
	function redirectMergedTickets()
	{
		if ($this->ticket->merged > 0 && JFactory::getSession()->Get('ticket_email') == "")
		{
			$link = FSSRoute::_("index.php?option=com_fss&view=ticket&layout=view&ticketid=" . $this->ticket->merged);
			JFactory::getApplication()->redirect($link);
		}
	}
	
	function loadMergedTickets()
	{		
		$db = JFactory::getDBO();
		$this->merged = new SupportTickets();
		$this->merged->limitstart = 0;
		$this->merged->limit = 250;
		$this->merged->loadTicketsByQuery(array("merged = " . $db->escape($this->ticket->id)));
	}	
	
	function updateTicketLanguage()
	{
		$lang = JFactory::getLanguage()->getTag();
		$db = JFactory::getDBO();
		$qry = "UPDATE #__fss_ticket_ticket SET lang = '" . FSSJ3Helper::getEscaped($db, $lang) . "' WHERE id = " . $this->ticket->id;
		$db->setQuery($qry);
		$db->Query();	
	}
}
