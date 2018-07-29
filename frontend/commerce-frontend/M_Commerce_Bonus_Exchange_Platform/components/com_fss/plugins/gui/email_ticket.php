<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'guiplugins.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_ticket.php');

class FSS_GUIPlugin_EMail_Ticket extends FSS_Plugin_GUI
{
	var $title = "EMail Ticket";
	var $description = "Add EMail Ticket link to Tools dropdown. Sends details of a ticket to an external email.";
	
	function adminTicketViewToolsMenu($ticket)
	{
		$output = "";

		// divider
		/*$output[] = '<li class="divider"></li>';*/

		// new item
		$output[] = '<li>';
		$output[] = '	<a class="show_modal_iframe" href="' . JRoute::_('index.php?option=com_fss&view=plugin&type=gui&name=email_ticket&tmpl=component&ticketid=' . $ticket->id) . '">';
		$output[] = 'EMail Ticket';
		$output[] = '	</a>';
		$output[] = '</li>';	

		return implode("\n", $output);
	}
	
	function process()
	{
		$this->ticket = new SupportTicket();
		$this->ticket->load(JRequest::getVar('ticketid'));
		$this->ticket->loadAll();
		
		// do process of data
		$this->postData = JRequest::getVar('jform', array(), 'array');
		
		// if OK, send and show done
		if (isset($this->postData['email']['email']) && $this->postData['email']['email'] != "") return $this->sendMessage();
	
		// show main page
		$this->showForm();
	}	
	
	function showForm()
	{
		$this->form = new JForm('set', array('control' => 'jform'));
		$this->form->addFieldPath(JPATH_ROOT.DS.'components'.DS.'com_fss'.DS.'models'.DS.'field');
		$form_file = JPATH_SITE.DS."components".DS."com_fss".DS."plugins".DS."gui".DS."email_ticket".DS."form.xml";
		$this->xml = JFactory::getXML($form_file, true);
		$this->form->load($this->xml, true);
		$this->form->bind($this->postData);
		
		$this->display("form");	
	}
	
	function sendMessage()
	{
		$this->loadSettings();

		$to = $this->postData['email']['email'];
		
		$tmpl = array();
		$tmpl['body'] = $this->settings->template->message;
		$tmpl['subject'] = $this->settings->template->subject; 
		$tmpl['ishtml'] = 1; 
		$tmpl['translation'] = ''; 
		$tmpl['tmpl'] = 'custom'; 
		
		$user = JFactory::getUser();
		
		$vars['sending_name'] = $user->name;
		$vars['sending_username'] = $user->username;
		$vars['sending_email'] = $user->email;
		
		FSS_EMail_To::$only_to[$to] = "EMail Ticket";
		
		FSS_EMail::EMail_To_Ticket_Handler($tmpl, $this->ticket, "", "", array(), $vars, JFactory::getUser()->id);	
			
		$this->display("result");	
	}
}