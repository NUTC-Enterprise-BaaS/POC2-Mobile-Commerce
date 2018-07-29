<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class FSS_GUIPlugin_OpenRelatedTicket extends FSS_Plugin_GUI
{
	var $title = "Open Related Ticket";
	var $description = "Adds an item to the support ticket Tools menu to open a new ticket related to this one for the same user.";
	

	// adds item to the bottom of the view ticket tools menu
	function adminTicketViewTools($ticket)
	{
		$this->loadSettings();
		
		if (isset($this->settings->related->display) && $this->settings->related->display != 0) return;

		$output = array();
		$output[] = '	<a class="pull-right btn btn-primary" style="margin-left: 8px" href="' . $this->getRelatedLink($ticket) . '">';
		$output[] = $this->getLabel();
		$output[] = '	</a>';

		return implode("\n", $output);
	}
	
	function adminTicketViewToolsMenu($ticket)
	{
		$this->loadSettings();
		
		if (isset($this->settings->related->display) && $this->settings->related->display != 1) return;
		
		$output = array();
		$output[] = '<li><a href="' . $this->getRelatedLink($ticket) . '">';
		$output[] = $this->getLabel();
		$output[] = '</a></li>';

		return implode("\n", $output);
	}
	
	function getLabel()
	{
		$label = 'Open related ticket';
		if (isset($this->settings->related->label)) $label = $this->settings->related->label;
		
		return JText::_($label);
	}
	
	function getRelatedLink($ticket)
	{		
		$link = 'index.php?option=com_fss&view=ticket&layout=open&related=' . $ticket->id;
		
		// add curreent ticket user
		if ($ticket->user_id > 0)
		{
			$link .= '&admincreate=1';
			$link .= '&user_id=' . $ticket->user_id;
		} else {
			$link .= '&admincreate=2';
			$link .= '&admin_create_email=' . urlencode($ticket->email);
			$link .= '&admin_create_name=' . urlencode($ticket->unregname);
		}
		
		// add product
		if ($this->settings->related->change_product == 0)
		{
			$link .= '&prodid=' . $ticket->prod_id;
		} else if ($this->settings->related->change_product == 1)
		{
			
		} else if ($this->settings->related->change_product == 2)
		{
			$link .= '&prodid=' . $this->settings->related->product;
		}
		
		// add department
		if ($this->settings->related->change_department == 0)
		{
			$link .= '&deptid=' . $ticket->ticket_dept_id;
		} else if ($this->settings->related->change_product == 1)
		{
			
		} else if ($this->settings->related->change_product == 2)
		{
			$link .= '&deptid=' . $this->settings->related->department;
		}
		
		// add subject
		$link = JRoute::_($link);
		
		return $link;
	}
}							  			  	