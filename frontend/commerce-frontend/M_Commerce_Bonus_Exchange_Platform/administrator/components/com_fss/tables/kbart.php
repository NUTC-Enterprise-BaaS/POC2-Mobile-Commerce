<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class TableKbart extends JTable
{

	var $id = null;

	var $title = null;

	var $body = null;

   	var $ordering = 0;

   	var $kb_cat_id = 0;
	
   	var $rating = 0;
	
   	var $ratingdetail = "0|0|0";
	
	var $allprods = 1;
	
	var $added = null;
	
	var $modified = null;
	
	var $language = "*";
	
	var $access = 1;
	
	var $author;
	

	function TableKbart(& $db) {
		parent::__construct('#__fss_kb_art', 'id', $db);
	}

	function check()
	{
		// make published by default and get a new order no
		if (!$this->id)
		{
			$this->set('created', date("Y-m-d H:i:s"));
			$this->set('ordering', $this->getNextOrder());
			$this->set('published', 1);
			
			$user = JFactory::GetUser();
			$this->set('author', $user->id);
		}
		$this->set('modified', date("Y-m-d H:i:s"));

		return true;
	}
}


