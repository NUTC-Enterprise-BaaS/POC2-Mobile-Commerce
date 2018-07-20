<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport("joomla.filesystem.folder");

class SupportSource 
{
	static $loaded = false;
	static $plugins = array();
	
	static function load()
	{
		if (self::$loaded)
			return;
		
		$path = JPATH_SITE.DS."components".DS."com_fss".DS."plugins".DS."ticketsource".DS;
		$files = JFolder::files($path, ".php$");
		
		foreach ($files as $file)
		{
			$id = pathinfo($file, PATHINFO_FILENAME);
			$class = "Ticket_Source_" . $id;
			require_once($path . DS . $file);
			if (class_exists($class))
			{
				self::$plugins[$id] = new $class();	
				self::$plugins[$id]->id = $id;
			}
		}
		
		self::$loaded = true;
	}
	
	static function get_all()
	{
		self::load();
		
		return self::$plugins;	
	}
	
	static function get($id)
	{
		self::load();
		
		// if we are trying to get the base source, return a blank one
		if ($id == "")
			return new Ticket_Source_Support();
		
		return self::$plugins[$id];	
	}
	
	static function _get_func_array($name)
	{
		self::load();
		
		$tabs = array();
		
		foreach (self::$plugins as $plugin)
		{
			foreach ($plugin->$name() as $tab)
				$tabs[] = $tab;
		}	
		
		return $tabs;
	}
		
	static function _get_func($name)
	{
		self::load();
		
		$tabs = array();
		
		foreach (self::$plugins as $plugin)
		{
			$tabs[] = $plugin->$name();
		}	
		
		return $tabs;
	}
	
	static function get_tabs() { return self::_get_func_array("getTabs"); }
	static function getOverview_ListItems() { return self::_get_func_array("getOverview_ListItem"); }
	static function getOverview_Appends() { return self::_get_func("getOverview_Append"); }
	static function getMainMenu_ListItems()	{ return self::_get_func_array("getMainMenu_ListItem");	}
	static function getMainMenu_Module_Admin_ListItems() { return self::_get_func_array("getMainMenu_Module_Admin_ListItem"); }
	static function getUser_Tabs() { return self::_get_func_array("getUser_Tabs"); }
	
	static function user_show_sql()
	{
		self::load();
		
		$where = array();
		$where[] = " source = '' ";
		
		foreach (self::$plugins as $plugin)
		{
			if ($plugin->user_show)
				$where[] = " source = '" . $plugin->id . "' ";
		}	
		
		return " ( " . implode(" OR ", $where) . " ) ";
	}	
		
	static function user_list_sql()
	{
		self::load();
		
		$where = array();
		$where[] = " source = '' ";
		
		foreach (self::$plugins as $plugin)
		{
			if ($plugin->user_list)
				$where[] = " source = '" . $plugin->id . "' ";
		}	
		
		return " ( " . implode(" OR ", $where) . " ) ";
	}	
	
	static function admin_show_sql()
	{
		self::load();
		
		$where = array();
		$where[] = " source = '' ";
		
		foreach (self::$plugins as $plugin)
		{
			if ($plugin->admin_show)
				$where[] = " source = '" . $plugin->id . "' ";
		}	
		
		return " ( " . implode(" OR ", $where) . " ) ";
	}
	
	static function admin_list_sql()
	{
		self::load();
		
		$where = array();
		$where[] = " source = '' ";
		
		foreach (self::$plugins as $plugin)
		{
			if ($plugin->admin_list)
				$where[] = " source = '" . $plugin->id . "' ";
		}	
		
		return " ( " . implode(" OR ", $where) . " ) ";
	}	
	
	static function get_source_title($id)
	{
		return self::get($id)->name;	
	}
	
	static function doUser_View_Redirect($ticket)
	{
		$ticket = self::TicketToArray($ticket);
		
		if ($ticket['source'] == "")
			return;
		
		if (!array_key_exists($ticket['source'], self::$plugins))
			return;
		
		$plugin = self::$plugins[$ticket['source']];
		
		$url = $plugin->getUser_View_Redirect($ticket);
		
		if ($url)
		{
			JFactory::getApplication()->redirect($url);
		}
		
		return;
	}
	
	static function TicketToArray($ticket)
	{
		if (is_array($ticket))
			return $ticket;
		
		$res = array();
		foreach ($ticket as $field => $value)
			$res[(string)$field] = $value;

		return $res;		
	}
}

class Ticket_Source
{
	var $name = "Support Ticket";
	
	// should the source be displayed in the usual ticket listing?
	var $user_show = false;
	var $admin_show = false;
	var $user_list = false;
	var $admin_list = false;
	
	function getTabs()
	{
		return array();	
	}
	
	function getOverview_ListItem()
	{
		return array();	
	}
	
	function getOverview_Append()
	{
		return "";	
	}
	
	function getMainMenu_ListItem()
	{
		return array();	
	}
	
	function getMainMenu_Module_Admin_ListItem()
	{
		return array();	
	}
	
	function getUser_View_Redirect($ticket)
	{
		return false;	
	}
	
	function getUser_Tabs()
	{
		return array();	
	}
}

class Ticket_Source_Support extends Ticket_Source
{
	var $id = "";
	var $name = "Support Ticket";	
}

class Ticket_Source_Data
{
	var $name = "";
	var $link = "";
	var $active = false;	
	var $tabname = "";
	var $count = 0;
}
