<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class ProfileSourceJoomla extends ProfileSource
{
	var $name = "Joomla";
	var $id = 'joomla';

	var $order = 0;
	function getFixedFields()
	{
		return array(
			'address' => 'Full Address',
			'addresstel' => 'Full Address + Phone',
			'email' => 'EMail',
			'username' => 'Username',
			'fullname' => 'Fullname',
			'aboutme' => 'About Me',
			'address1' => 'Address 1',
			'address2' => 'Address 2',
			'city' => 'City',
			'country' => 'Country',
			'dob' => 'Date of Birth',
			'favoritebook' => 'Favourite Book',
			'phone' => 'Phone',
			'postal_code' => 'Postcode / ZIP',
			'region' => 'Region', 
			'website' => 'Website'
			);
	}

	function getPresets()
	{
		return array(
			'address' => "{if,joomla.address1}{joomla.address1}<br />{endif}
{if,joomla.address2}{joomla.address2}<br />{endif}
{if,joomla.city}{joomla.city}<br />{endif}
{if,joomla.region}{joomla.region}<br />{endif}
{if,joomla.country}{joomla.country}<br />{endif}
{if,joomla.postal_code}{joomla.postal_code}{endif}<br />{endif}",
			'addresstel' => "{if,joomla.address1}{joomla.address1}<br />{endif}
{if,joomla.address2}{joomla.address2}<br />{endif}
{if,joomla.city}{joomla.city}<br />{endif}
{if,joomla.region}{joomla.region}<br />{endif}
{if,joomla.country}{joomla.country}<br />{endif}
{if,joomla.postal_code}{joomla.postal_code}{endif}<br />{endif}
{if,joomla.phone}{joomla.phone}<br />{endif}"
			);
	}

	static $data = array();
	function getValues($user)
	{
		if (!array_key_exists($user->id, self::$data))
		{
			$display = array();
			$display['email'] = $user->email;
			$display['username'] = $user->username;
			$display['fullname'] = $user->name;
		
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);	
			$query->select("*")->from("#__user_profiles")->where("user_id = " . $db->escape($user->id));
			$db->setQuery($query);
			$result = $db->loadObjectList();
			foreach ($result as $item)
			{
				$key = str_replace("profile.", "", $item->profile_key);
				$display[$key] = trim(trim($item->profile_value), "\"");
			}

			self::$data[$user->id] = $display;
		}

		return self::$data[$user->id];
	}

	function search($fields, $search)
	{
		$db = JFactory::getDBO();

		$search_profile = array();
		$search_users = array();

		$result_profile = array();
		$result_users = array();

		foreach ($fields as $field)
		{
			if ($field == "email" || $field == "username" || $field == "fullname")
			{
				$search_users[] = $field;
			} else {
				$search_profile[] = $field;
			}
		}

		if (count($search_profile) > 0)
		{
			$query = $db->getQuery(true);	
			$query->select("user_id")
				  ->from("#__user_profiles");

			$words = explode(" ", $search);
			$where = array();
			foreach ($words as $word)
			{
				$word = trim($word);
				if (!$word) continue;
				$where[] = "profile_value LIKE '%" . $db->escape($word) . "%'";
			}
			$query->where("(" . implode(" OR ", $where) . ")");

			$in = array();
			foreach ($search_profile as $field)
			{
				$in[] = "profile." . $field;
			}

			$query->where("profile_key IN ('" . implode("', '", $in) . "')");
			$db->setQuery($query);
			$result_profile = $db->loadColumn();
		}

		if (count($search_users) > 0)
		{
			$query = $db->getQuery(true);	
			$query->select("id")
				  ->from("#__users");

			$where = array();
			$words = explode(" ", $search);
			foreach ($words as $word)
			{
				$word = trim($word);
				if (!$word) continue;

				foreach ($search_users as $field)
				{
					$where[] = "{$field} LIKE '%{$word}%'";
				}
			}
			$query->where("(" . implode(" OR ", $where) . ")");
			$db->setQuery($query);
			$result_users = $db->loadColumn();
		}
		$result = array_unique(array_merge($result_users, $result_profile));

		return $result;
	}
}