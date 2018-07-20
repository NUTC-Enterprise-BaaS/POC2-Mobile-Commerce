<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class TableGlossary extends JTable
{

	var $id = null;

	var $word = null;

	var $description = null;

	var $fullanswer = null;

   	var $longdesc = 0;
	
	var $language = "*";
	
	var $access = 1;
	
	var $casesens = 0;
	var $altwords = '';
	
	function TableGlossary(& $db) {
		parent::__construct('#__fss_glossary', 'id', $db);
	}

	function check()
	{
		if (!$this->id)
		{
			$this->set('published', 1);
		}
		return true;
	}
}


