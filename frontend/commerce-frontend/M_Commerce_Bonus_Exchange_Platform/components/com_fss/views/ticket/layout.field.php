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

class FssViewTicket_Field extends FssViewTicket
{
	function display($tpl = NULL)
	{
		$this->ticket_id = FSS_Input::getInt("ticketid");
		$this->fieldid = FSS_Input::getInt('fieldid');
		
		$this->errors = array();
		
		$this->ticket = new SupportTicket();
		if (!$this->ticket->load($this->ticket_id, true)) return $this->noPermission();
		
		//$this->ticket->loadAll();
		$this->ticket->setupUserPerimssions();
		
		if ($this->ticket->readonly) return;
		
		$this->ticket->loadCustomFields();
		$this->field = $this->ticket->getField($this->fieldid);
		
		if (FSS_Input::GetInt('savefield') > 0) return $this->saveField();
		
		JRequest::setVar('custom_' . $this->fieldid, $this->ticket->getFieldValue($this->fieldid));

		$this->_display();
	}	
	
	function saveField()
	{
		// load in tickets to do		
		$ticketid = FSS_Input::getInt('ticketid');
		$fieldid = FSS_Input::getInt('fieldid');
		$value = FSS_Input::getString("custom_" . $fieldid,"");
		
		$this->ticket->updateCustomField($fieldid, $value);	

		echo "<script>parent.window.location.reload();</script>";
		exit;				
	}
}
