<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

if (file_exists(JPATH_SITE.DS."components".DS."com_easysocial"))
{
	class ProfileSourceEasySocial extends ProfileSource
	{
		var $name = "Easy Social";
		var $id = 'es';

		function makeKey($name)
		{
			$name = str_replace("COM_EASYSOCIAL_FIELDS_PROFILE_DEFAULT_", "", $name);

			$key = preg_replace('/[^a-zA-Z0-9\-]+/', '-', $name);
			while (strpos($key, '--') !== false)
			{
				$key = str_replace("--", "-", $key);
			}

			$key = strtolower($key);

			return $key;
		}

		function getFields()
		{
			$db = JFactory::getDBO();
			$sql = "SELECT id FROM #__social_fields_steps WHERE type = 'profiles'";
			$db->setQuery($sql);
			$steps = $db->loadColumn();
			
			if (count($steps) < 1) return null;

			$sql = "SELECT * FROM #__social_fields WHERE step_id IN (" . implode(", ", $steps) . ") AND visible_display = 1 ORDER BY ordering";
			$db->setQuery($sql);
			return $db->loadObjectList();
		}

		function getFixedFields()
		{
			$lang = JFactory::getLanguage();
			$lang->load("com_easysocial");

			$options = array();		
			$fields = $this->getFields();

			foreach ($fields as $field)
			{
				$options[$this->makeKey($field->title)] = JText::_($field->title);
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
				$db->setQuery("SELECT * FROM #__social_fields_data as d LEFT JOIN #__social_fields as f ON d.field_id = f.id WHERE d.uid = " . $user->id);
				$result = $db->loadObjectList();

				foreach ($result as $item)
				{
					$display[$this->makeKey($item->title)] = $item->data;
				}

				self::$data[$user->id] = $display;
			}

			return self::$data[$user->id];
		}

		function search($fields, $search)
		{
			$db = JFactory::getDBO();

			$flist = $this->getFields();

			$ids = array();

			foreach ($flist as $fsource)
			{
				$name = $this->makeKey($fsource->title);
				if (in_array($name, $fields))
				{
					$ids[] = $fsource->id;
				}
			}
			
			if (count($ids) < 1)
				return array();

			$qry = "SELECT uid FROM #__social_fields_data ";
			$qry .= " WHERE field_id IN (" . implode(", ", $ids) . ") ";
			$where = array();
			$words = explode(" ", $search);
			foreach ($words as $word)
			{
				$word = trim($word);
				if (!$word) continue;

				$where[] = "data LIKE '%{$word}%'";
			}
			$qry .= " AND (" . implode(" OR ", $where) . ")";

			$db->setQuery($qry);

			return $db->loadColumn();
		}
	}
}