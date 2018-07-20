<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class TableKbcat extends JTable
{

	var $id = null;

	var $title = null;

	var $description = null;

	var $ordering = 0;
	
	var $image = null;

	var $parcatid = 0;
	
	var $language = "*";
	
	var $access = 1;

	function TableKbcat(& $db) {
		parent::__construct('#__fss_kb_cat', 'id', $db);
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


