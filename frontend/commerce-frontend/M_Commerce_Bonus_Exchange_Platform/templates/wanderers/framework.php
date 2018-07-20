<?php
/**
* @package		Wanderers
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Wanderers is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

if (!function_exists('dump')) {

	function dump()
	{
		$args 	= func_get_args();

		echo '<pre>';
		foreach ($args as $arg) {
			var_dump($arg);
		}
		echo '</pre>';

		exit;
	}
}

// Constants
define('TEMPLATE_URI', JURI::root() . 'templates/wanderers');

// Variables
$app = JFactory::getApplication();
$input = $app->input;

// Determines if custom css is available
jimport('joomla.filesystem.file');
$customCss = JFile::exists(__DIR__ . '/css/custom.css');

JHtml::_('jquery.framework');

class Wanderers
{
	public static function isSubmenu()
	{
		$menu = JFactory::getApplication()->getMenu();
		$active = $menu->getActive();

		$db = JFactory::getDBO();

		$query = 'SELECT COUNT(1) FROM ' . $db->quoteName('#__menu');
		$query .= ' WHERE ' . $db->quoteName('id') . '=' . $db->Quote($active->id);
		$query .= ' AND ' . $db->quoteName('parent_id') . '!=' . $db->Quote(1);
		$query .= ' AND ' . $db->quoteName('published') . '=' . $db->Quote(1);

		$db->setQuery($query);

		$result = $db->loadResult() > 0 ? true : false;

		return $result;
	}

	public static function hasSubmenu()
	{
		$menu = JFactory::getApplication()->getMenu();
		$active = $menu->getActive();

		$db = JFactory::getDBO();

		$query = 'SELECT COUNT(1) FROM ' . $db->quoteName('#__menu');
		$query .= ' WHERE ' . $db->quoteName('parent_id') . '=' . $db->Quote($active->id);
		$query .= ' AND ' . $db->quoteName('published') . '=' . $db->Quote(1);

		$db->setQuery($query);

		$result = $db->loadResult() > 0 ? true : false;

		return $result;
	}
}
