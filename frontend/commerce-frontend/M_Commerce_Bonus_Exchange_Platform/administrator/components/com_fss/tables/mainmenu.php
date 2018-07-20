<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class TableMainmenu extends JTable
{

	var $id = null;

	var $title = null;

	var $description = null;

	var $icon = null;

	var $ordering = 0;
	
	var $itemtype = 0;
	
	var $link = "";
	
	var $itemid = 0;
	
	var $published = 0;
	
	var $language = "*";
	
	var $access = 1;

	var $target = '';
	var $translation = "";
	
	function TableMainmenu(& $db) {
		parent::__construct('#__fss_main_menu', 'id', $db);
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


