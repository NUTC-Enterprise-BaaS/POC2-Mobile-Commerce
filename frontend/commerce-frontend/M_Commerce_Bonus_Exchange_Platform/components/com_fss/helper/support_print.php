<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class Support_Print
{
	static function getPrintList($for_admin, $ticket = null, $isbatch = false)
	{
		$result = array();
		
		$path = JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'plugins'.DS.'ticketprint';
		$files = JFolder::files($path, ".xml$");
		
		foreach ($files as $file)
		{
			$id = pathinfo($file, PATHINFO_FILENAME);

			if (!FSS_Helper::IsPluignEnabled("ticketprint", $id))
				continue;

			$xml = simplexml_load_file($path . DS . $file);
			if ($for_admin && (int)$xml->admin != 1) continue;
			if (!$for_admin && (int)$xml->user != 1) continue;
			if ($isbatch && (int)$xml->batch != 1) continue;
			
			if ($xml->can_run_php && $ticket)
			{
				$fn = create_function('$for_admin,$ticket', (string)$xml->can_run_php);
				if (!$fn($for_admin, $ticket))
					continue;
			}
			
			$result[str_ireplace(".xml", "", $file)] = (string)$xml->title;
		}
		
		return $result;
	}
	
	static function loadPrint($name)
	{
		if ($name == "") return null;

		$path = JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'plugins'.DS.'ticketprint'.DS.$name.".xml";

		if (!file_exists($path)) return null;

		return simplexml_load_file($path);
	}
	
	static function outputPrint($name, $print)
	{
		$file = (string)$print->include;

		if ($name == "" || $file == "") return null;

		$path = JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'plugins'.DS.'ticketprint'.DS.$name.DS.$file;
		
		if (!file_exists($path)) return null;

		return $path;
	}
}