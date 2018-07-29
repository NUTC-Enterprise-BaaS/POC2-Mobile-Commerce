<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class ProfileSourceContact extends ProfileSource
{
	var $name = "Joomla Linked Contact";
	var $id = 'contact';

	var $order = 0;
	function getFixedFields()
	{
		return array(
			'con_position' => 'Position',
			'email_to' => 'Email',
			'address' => 'Address',
			'suburb' => 'City or Suburb',
			'state' => 'State or Province',
			'country' => 'Country',
			'postcode' => 'Postal/ZIP Code',
			'telephone' => 'Telephone',
			'fax' => 'Fax',
			'mobile' => 'Mobile',
			'webpage' => 'Website'
			);
	}

	static $data = array();
	function getValues($user)
	{
		if (!array_key_exists($user->id, self::$data))
		{
			$display = array();
			
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);	
			$query->select("*")->from("#__contact_details")->where("user_id = " . $db->escape($user->id));
			$db->setQuery($query);
			$result = $db->loadObject();

			if ($result)
			{
				foreach ($this->getFixedFields() as $field => $name)
				{
					$display[$field] = $result->$field;	
				}
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

		if (count($fields) > 0)
		{
			$query = $db->getQuery(true);	
			$query->select("user_id")
				->from("#__contact_details");

			$words = explode(" ", $search);
			$where = array();
			foreach ($fields as $field)
			{
				foreach ($words as $word)
				{
					$word = trim($word);
					if (!$word) continue;
					$where[] = "$field LIKE '%" . $db->escape($word) . "%'";
				}
			}
			$query->where("(" . implode(" OR ", $where) . ")");
			$db->setQuery($query);			

			return $db->loadColumn();
		}

		return array();
	}
}