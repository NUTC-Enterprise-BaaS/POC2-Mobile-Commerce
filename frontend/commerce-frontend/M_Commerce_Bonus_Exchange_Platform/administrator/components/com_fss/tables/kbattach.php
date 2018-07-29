<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class TableKbattach extends JTable
{

	var $id = null;

	var $filename;
    var $diskfile;
    var $title;
    var $description;
    var $kb_art_id;

	function TableKbattach(& $db) {
		parent::__construct('#__fss_kb_attach', 'id', $db);
	}
}


