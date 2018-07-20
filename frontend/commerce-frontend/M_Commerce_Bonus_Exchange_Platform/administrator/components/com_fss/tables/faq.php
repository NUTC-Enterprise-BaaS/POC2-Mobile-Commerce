<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class TableFaq extends JTable
{

	var $id = null;

	var $question = null;

	var $answer = null;

	var $fullanswer = null;

   	var $ordering = 0;

   	var $faq_cat_id = 0;

   	var $title = null;
	
	var $language = "*";
	
	var $access = 1;
	
	function TableFaq(& $db) {
		parent::__construct('#__fss_faq_faq', 'id', $db);
	}

	function check()
	{
		// make published by default and get a new order no
		if (!$this->id)
		{
			$this->set('ordering', $this->getNextOrder());
			$this->set('published', 1);
			
			$user = JFactory::GetUser();
			$this->set('author', $user->id);
		}
		
		if (!$this->question)
			return false;

		return true;
	}
}


