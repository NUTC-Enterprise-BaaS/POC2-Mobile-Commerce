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

class Task_Canned extends Task_Helper
{
	function dolist()
	{
		$ticketid = FSS_Input::getInt('ticketid');
		$ticket = new SupportTicket();
		$ticket->load($ticketid);
		echo SupportCanned::CannedList($ticket);
		exit;
	}
	
	function dropdown()
	{
		$ticketid = FSS_Input::getInt('ticketid');
		$ticket = new SupportTicket();
		$ticket->load($ticketid);
		echo SupportCanned::CannedDropdown(FSS_Input::getCmd('elem'), false, $ticket);
		exit;
	}
}