<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class TableTicket extends JTable
{
	
	var $id = null;

	var $title = null;
	var $opened = null;
	var $username = null;

	function TableFaq(& $db) {
		parent::__construct('#__fss_ticket_ticket', 'id', $db);
	}
}


