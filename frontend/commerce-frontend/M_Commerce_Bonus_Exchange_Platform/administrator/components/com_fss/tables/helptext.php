<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class TableHelpText extends JTable
{

	var $dentifier = null;

	function TableHelpText(& $db) {
		parent::__construct('#__fss_help_text', 'identifier', $db);
	}

	function publish($ident, $state)
	{
		$db = JFactory::getDBO();
		$qry = "UPDATE #__fss_help_text SET published = " . $db->escape($state) . " WHERE identifier = '" . $db->escape($ident) . "'";
		$db->setQuery($qry);
		$db->Query();
		
		return true;
	}
}


