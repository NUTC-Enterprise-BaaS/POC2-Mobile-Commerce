<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class TableTicketdept extends JTable
{

	var $id = null;

	var $title = null;

	var $description = null;
	
	var $allprods = 1;
	
	var $translation = '';
	
	var $access = 1;

	function TableTicketdept(& $db) {
		parent::__construct('#__fss_ticket_dept', 'id', $db);
	}
	
	function check()
	{
		// make published by default and get a new order no
		if (!$this->id)
		{
			$this->set('ordering', $this->getNextOrder());
			$this->set('published', 1);
		}

		return true;
	}
}


