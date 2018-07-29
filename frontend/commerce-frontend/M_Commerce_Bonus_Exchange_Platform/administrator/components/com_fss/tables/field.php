<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class TableField extends JTable
{
	
	var $id = null;

	var $description = '';
	var $type = '';
	var $default = '';
	var $allprods = 0;
	var $alldepts = 0;
	var $required = 0;
	var $grouping = '';
	var $permissions = 0;
	var $basicsearch = 0;
	var $advancedsearch = 0;
	var $adminhide = 0;
	var $reghide = 0;
	var $inlist = 0;
	var $peruser = 0;
   	var $ordering = 0;
	var $ident = 0;
	var $helptext = '';
	var $javascript = '';
	var $alias = '';
	var $openhide = 0;
	
	function TableField(& $db) {
		parent::__construct('#__fss_field', 'id', $db);
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


