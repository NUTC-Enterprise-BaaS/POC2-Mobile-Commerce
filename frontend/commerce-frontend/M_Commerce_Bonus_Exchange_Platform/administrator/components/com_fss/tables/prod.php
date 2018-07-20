<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class TableProd extends JTable
{

	var $id = null;

	var $title = null;

	var $description = null;

	var $ordering = 0;
	
	var $image = null;
	
	var $extratext = null;
	var $inkb = 0;
	var $insupport = 0;
	var $intest = 0;
	
	var $translation = "";
	
	var $access = 1;
	var $category = '';
	var $subcat = '';
	
	function TableProd(& $db) {
		parent::__construct('#__fss_prod', 'id', $db);
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


