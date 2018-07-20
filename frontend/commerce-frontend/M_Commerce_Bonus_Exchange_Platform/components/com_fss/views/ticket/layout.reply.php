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

class FssViewTicket_Reply extends FssViewTicket
{
	function display($tpl = NULL)
	{
		$this->ticket_id = FSS_Input::getInt("ticketid");

		$this->ticket = new SupportTicket();
		if (!$this->ticket->load($this->ticket_id, true)) return $this->noPermission();

		$this->ticket->loadAll();
		$this->ticket->setupUserPerimssions();
		
		SupportSource::doUser_View_Redirect($this->ticket);

		$this->pris = SupportHelper::getPrioritiesUser();
		$this->statuss = SupportHelper::getStatussUser();
		
		$errors['subject'] = '';
		$errors['body'] = '';
		$errors['cat'] = '';
		$this->errors = $errors;

		$this->ticket_view = "reply";
		$this->loadCounts();
		
		FSS_Helper::IncludeModal();
		FSS_Helper::AddSCEditor(true);
		$this->_display();
	}	
	
}
