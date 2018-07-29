<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport('joomla.utilities.date');


class TableTest extends JTable
{

	var $id = null;

	var $ident = null;
	var $itemid = null;
	var $body = null;
	var $name = null;
	var $email = null;
	var $website = null;
   	var $created = null;
   	var $published = null;
	var $userid = null;

	function TableTest(& $db) {
		parent::__construct('#__fss_comments', 'id', $db);
	}

	function check()
	{
		// make published by default and get a new order no
		if (!$this->id)
		{
			if ($this->created == "")
			{
				$current_date = new JDate();
 				if (FSSJ3Helper::IsJ3())
				{
					$mySQL_conform_date = $current_date->toSql();
				} else { 
   					$mySQL_conform_date = $current_date->toMySQL();
				}
   				$this->set('created', $mySQL_conform_date);
			}
		}

		return true;
	}
}


