<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class TableAnnounce extends JTable
{
	
	var $id = null;

	var $title = null;
	
	var $subtitle = null;

	var $body = null;
	
	var $added = null;

	var $fulltext = null;
	
	var $language = "*";
	
	var $access = 1;
	
	var $author;
	
	function TableAnnounce(& $db) {
		parent::__construct('#__fss_announce', 'id', $db);
	}

	function check()
	{
		// make published by default and get a new order no
		if (!$this->id)
		{
			$this->set('added', date("Y-m-d H:i:s"));
			$this->set('published', 1);
			
			$user = JFactory::GetUser();
			$this->set('author', $user->id);
		}

		return true;
	}
}


