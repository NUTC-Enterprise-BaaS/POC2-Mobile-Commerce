<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class FSS_Input
{
	// number
	static function getInt($name, $default = 0)
	{
		return JRequest::getInt($name, $default);
	}	
	
	static function getFloat($name, $default = 0)
	{
		return JRequest::getFloat($name, $default);
	}
	
	// command, only allow a-z0-9.-_ etc
	static function getCmd($name, $default = '')
	{
		return JRequest::getCmd($name, $default);
	}
	
	// text for things like names and subjects
	static function getString($name, $default = '')
	{
		return JRequest::getString($name, $default);		
	}
	
	static function getIntArray($name)
	{
		$var = JRequest::getVar($name);
		
		$out = array();
		
		if (is_array($var))
		{
			foreach ($var as $value)
			{
				$out[] = (int)$value;	
			}
			
			return $out;
		}
		
		if (is_string($var))
		{
			if (strpos($var, ",") !== false)
			{
				$bits = explode(",", $var);	
				foreach ($bits as $value)
				{
					$out[] = (int)$value;	
				}
				
				return $out;
			}
		}
		

		if (is_numeric($var)) return array((int)$var);	

		return array();
	}
	
	static function getCmdArray($name)
	{
		$var = JRequest::getVar($name);
		
		$out = array();
		
		if (is_array($var))
		{
			foreach ($var as $value)
			{
				$out[] = preg_replace("/[^a-zA-Z0-9]+/", "", $value);	
			}
			
			return $out;
		}
		
		if (is_string($var))
		{
			if (strpos($var, ",") !== false)
			{
				$bits = explode(",", $var);	
				foreach ($bits as $value)
				{
					$out[] = preg_replace("/[^a-zA-Z0-9]+/", "", $value);	
				}
				
				return $out;
			}
		}
		
		return array(preg_replace("/[^a-zA-Z0-9]+/", "", $var));			
	}
	
	// HTML
	static function getHTML($name, $default = '')
	{
		$val = JRequest::getString($name, '', 'default', JREQUEST_ALLOWRAW);

		// do we need to do any further sanitizinghere?
		
		return $val;
	}
	
	// BBCode, no HTML tags!
	static function getBBCode($name, $default = '')
	{
		if (array_key_exists($name, $_POST))
			return $_POST[$name];

		if (array_key_exists($name, $_GET))
			return $_GET[$name];

		return "";
	}
	
	// Email address
	static function getEMail($name, $default = '')
	{
		$val = JRequest::getString($name, $default);		
		$val = filter_var($val, FILTER_SANITIZE_EMAIL);
		return $val;
	}
	
	// URL
	static function getURL($name, $default = '')
	{
		$val = JRequest::getString($name, $default);		
		$val = filter_var($val, FILTER_SANITIZE_URL);
		return $val;
	}
	
	// Array
	static function getArray($name, $default = array())
	{
		return JRequest::getVar($name,  0, '', 'array');
	}
	
	// Array of ints
	static function getArrayInt($name, $default = array())
	{
		$array = JRequest::getVar($name,  0, '', 'array');
		$result = array();
		foreach ($array as $item)
			$result[] = (int)$item;
		
		return $result;
	}

	private function xss_clean($data)
	{
		// Fix &entity\n;
		$data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
		$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
		$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
		$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

		// Remove any attribute starting with "on" or xmlns
		$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

		// Remove javascript: and vbscript: protocols
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

		// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

		// Remove namespaced elements (we do not need them)
		$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

		do
		{
			// Remove really unwanted tags
			$old_data = $data;
			$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
		}
		while ($old_data !== $data);

		// we are done...
		return $data;
	}
	
}	 	 					 	   		