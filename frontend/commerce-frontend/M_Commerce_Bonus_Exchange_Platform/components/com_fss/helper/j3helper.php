<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

if (!class_exists("JControllerLegacy"))
{
	jimport( 'joomla.application.component.view');
	jimport( 'joomla.application.component.model');
	jimport( 'joomla.application.component.controller');
	class JControllerLegacy extends JController {}
	class JModelLegacy extends JModel {}
	class JViewLegacy extends JView {}
}

class FSSJ3Helper
{
	static function IsJ3()
	{
		$version = new JVersion();
		if ($version->RELEASE >= 3)
		{
			return true;
		} else {
			return false;
		}
	}
	
	static function getEscaped(&$db, $string)
	{
		if (!$db) $db = JFactory::getDBO();
		if (FSSJ3Helper::IsJ3())
		{
			return $db->escape($string);
		} else {
			return $db->getEscaped($string);
		}	
	}
	
	static function loadResultArray(&$db)
	{
		if (FSSJ3Helper::IsJ3())
		{
			return $db->loadColumn(0);
		} else {
			return $db->loadResultArray();
		}		
	}
}