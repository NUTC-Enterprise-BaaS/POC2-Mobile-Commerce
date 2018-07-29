<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

if (file_exists(JPATH_SITE.DS."components".DS."com_comprofiler"))
{
	class ProfileSourceCommunityBuilder extends ProfileSource
	{
		var $name = "Community Builder";
		var $id = 'cb';

		function getFixedFields()
		{
			$db = JFactory::getDBO();
			$db->setQuery("SELECT * FROM #__comprofiler_fields WHERE published = 1 AND tablecolumns != '' AND `table` LIKE '%__comprofiler%' ORDER BY ordering");
		
			$fields = $db->loadObjectList();
		
			$options = array();
		
			$db_lang_strings = JPATH_SITE.DS.'components'.DS.'com_comprofiler'.DS.'plugin'.DS.'language'.DS.'default_language'.DS.'default_language.php';
			if (file_exists($db_lang_strings))
				include($db_lang_strings);

			foreach ($fields as $field)
			{
				if (substr($field->title,0,1) == "_")
				{
					$t = @constant($field->title);
					if ($t != "")
						$field->title = $t;
				}
				$field->title = str_ireplace("_pg_", "", $field->title);
				$options[$field->name] = $field->title;
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
				$db->setQuery("SELECT * FROM #__comprofiler_fields WHERE published = 1 AND tablecolumns != '' AND `table` LIKE '%__comprofiler%' ORDER BY ordering");
				$fields = $db->loadObjectList();
			
				$query = $db->getQuery(true);	
				$query->select("*")->from("#__comprofiler")->where("user_id = " . $db->escape($user->id));
				$db->setQuery($query);
				$result = $db->loadAssoc();

				if ($result)
				{
					foreach($fields as $field)
					{
						$name = $field->name;
						$fld = $field->tablecolumns;
						if (array_key_exists($fld, $result)) $display[$name] = $result[$fld];
					}
				}

				self::$data[$user->id] = $display;
			}

			return self::$data[$user->id];
		}

		function search($fields, $search)
		{
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);	
			$query->select("user_id")
				->from("#__comprofiler");

			$where = array();
			$words = explode(" ", $search);
			foreach ($words as $word)
			{
				$word = trim($word);
				if (!$word) continue;

				foreach ($fields as $field)
				{
					$where[] = "{$field} LIKE '%{$word}%'";
				}
			}
			$query->where("(" . implode(" OR ", $where) . ")");
			$db->setQuery($query);

			return $db->loadColumn();
		}
	}
}