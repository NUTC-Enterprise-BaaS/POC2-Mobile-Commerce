<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

if (file_exists(JPATH_SITE.DS."components".DS."com_jsn"))
{
	class ProfileSourceEasyProfile extends ProfileSource
	{
		var $name = "Easy Profile";
		var $id = 'easyprofile';

		function getFixedFields()
		{
			$db = JFactory::getDBO();
			$db->setQuery("SELECT * FROM #__jsn_fields WHERE published = 1 AND type = 'text'");
			
			$fields = $db->loadObjectList();
			
			$options = array();

			foreach ($fields as $field)
			{
				$options[$field->alias] = $field->title;
			}

			return $options;
		}

		static $data = array();
		function getValues($user)
		{
			if (!array_key_exists($user->id, self::$data))
			{
				$display = array();
				$db = JFactory::getDBO();
				$db->setQuery("SELECT * FROM #__jsn_fields WHERE published = 1 AND type = 'text'");
				$fields = $db->loadObjectList();

				$query = $db->getQuery(true);	
				$query->select("*")->from("#__jsn_users")->where("id = " . $db->escape($user->id));
				$db->setQuery($query);
				$result = $db->loadAssoc();

				foreach($fields as $field)
				{
					$name = $field->alias;
					if (array_key_exists($name, $result))
					$display[$name] = $result[$name];
				}

				self::$data[$user->id] = $display;
			}

			return self::$data[$user->id];
		}
	}
}