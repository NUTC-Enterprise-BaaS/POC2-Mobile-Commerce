<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

if (file_exists(JPATH_SITE.DS."components".DS."com_community"))
{
	class ProfileSourceJomSocial extends ProfileSource
	{
		var $name = "JomSocial";
		var $id = 'js';

		function makeKey($name)
		{
			$key = preg_replace('/[^a-zA-Z0-9\-]+/', '-', $name);
			while (strpos($key, '--') !== false)
			{
				$key = str_replace("--", "-", $key);
			}

			$key = strtolower($key);

			return $key;
		}

		function getFixedFields()
		{
			$db = JFactory::getDBO();
			$db->setQuery("SELECT * FROM #__community_fields WHERE published = 1 ORDER BY ordering");
			$fields = $db->loadObjectList();
			
			$options = array();

			foreach ($fields as $field)
			{
				$options[$this->makeKey($field->name)] = $field->name;
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
				$db->setQuery("SELECT * FROM #__community_fields_values as v LEFT JOIN #__community_fields as f ON v.field_id = f.id WHERE f.published = 1 AND v.user_id = " . $user->id);
				$result = $db->loadObjectList();

				foreach ($result as $item)
				{
					$display[$this->makeKey($item->name)] = $item->value;
				}

				self::$data[$user->id] = $display;
			}

			return self::$data[$user->id];
		}

		function search($fields, $search)
		{
			$db = JFactory::getDBO();
			$db->setQuery("SELECT * FROM #__community_fields WHERE published = 1 ORDER BY ordering");
			$flist = $db->loadObjectList();

			$ids = array();

			foreach ($flist as $fsource)
			{
				$name = $this->makeKey($fsource->name);
				if (in_array($name, $fields))
				{
					$ids[] = $fsource->id;
				}
			}
			
			if (count($ids) < 1)
				return array();

			$qry = "SELECT user_id FROM #__community_fields_values ";
			$qry .= " WHERE field_id IN (" . implode(", ", $ids) . ") ";
			$where = array();
			$words = explode(" ", $search);
			foreach ($words as $word)
			{
				$word = trim($word);
				if (!$word) continue;

				$where[] = "value LIKE '%{$word}%'";
			}
			$qry .= " AND (" . implode(" OR ", $where) . ")";
			$db->setQuery($qry);

			return $db->loadColumn();
		}
	}
}		    							 	