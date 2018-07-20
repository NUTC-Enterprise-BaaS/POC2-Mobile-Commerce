<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class TableTicketcat extends JTable
{
	
	var $id = null;

	var $title = null;

	var $section = null;

	var $allprods = 1;
	
	var $alldepts = 1;
	
	var $access = 1;
	
	var $translation = '';
	
	function TableTicketcat(& $db) {
		parent::__construct('#__fss_ticket_cat', 'id', $db);
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


