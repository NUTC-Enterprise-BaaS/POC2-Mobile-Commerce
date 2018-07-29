<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

// the class name (SamplePlugin) MUST match the name of the php file (ie, this class must be in sample.php)
class LogIPPlugin extends FSSCustFieldPlugin
{
	var $name = "Log IP Address Plugin";

	function GetGroupClass()
	{
		return "fss_ip_log";	
	}

	function Input($current, $params, $context, $id) // output the field for editing
	{
		return "<style>.fss_ip_log { display: none; }</style>";
	}
	
	function Save($id, $params, $value = "")
	{
		return FSS_Helper::GetClientIP();
	}
	
	function Display($value, $params, $context, $id) // output the field for display
	{
		return $value;
	}
	
	function CanEdit()
	{
		return false;	
	}
}