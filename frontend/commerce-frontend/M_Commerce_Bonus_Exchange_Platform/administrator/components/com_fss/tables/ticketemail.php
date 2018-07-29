<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class Tableticketemail extends JTable
{

	var $id = null;

	var $name;
	var $server;
	var $type;
	var $port;
	var $username;
	var $password;
	var $checkinterval;
	var $newticketsfrom;
	var $prod_id;
	var $dept_id;
	var $cat_id;
	var $pri_id;
	var $handler;
	var $usessl;
	var $allowunknown;
	var $usetls;
	var $validatecert;
	var $cronid;
	var $toaddress;
	var $ignoreaddress;
	var $onimport;
	var $confirmnew;
	var $import_html;

	function Tableticketemail(& $db) {
		parent::__construct('#__fss_ticket_email', 'id', $db);
	}

	function check()
	{
		// make published by default and get a new order no
		if (!$this->id)
		{
			$this->set('published', 1);
		}

		return true;
	}
}

