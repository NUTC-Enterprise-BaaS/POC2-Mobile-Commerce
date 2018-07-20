<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class TableTicketgroup extends JTable
{

	var $id = null;

	var $groupname = null;
	var $description = null;
	var $allsee = 0;
	var $allemail = 0;
	var $allprods = 0;
	var $ccexclude = 0;
	function TableTicketgroup(& $db) {
		parent::__construct('#__fss_ticket_group', 'id', $db);
	}

	function check()
	{
		// make published by default and get a new order no
		if (!$this->id)
		{
		}

		return true;
	}
}


