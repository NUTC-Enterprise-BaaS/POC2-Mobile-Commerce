<?php

/**
 * Example user listing custom plugin to display extra data within a user listing 
 * 
 * User listings are used when adding a user or handler to a support ticket, or 
 * when opening a ticket for a user
 *
 * rename this file to postcode.php to enable the plugin 
 **/

class User_List_Postcode extends User_List_Column
{
	var $title = "Postcode";
	var $description = "Show postcode from user profile when listing users in popup select";

	function getHeader()
	{
		return 'Postcode';
	}

	function loadData($users)
	{
		$ids = array();

		foreach ($users as $user)
		{
			$ids[] = $user->id;
		}

		if (count($ids) < 1) return;

		$db = JFactory::getDBO();

		$qry = "SELECT * FROM #__user_profiles WHERE profile_key = 'profile.postal_code' AND user_id IN (" . implode(", ", $ids) . ")";
		$db->setQuery($qry);
		$this->data = $db->loadObjectList('user_id');
	}

	function displayUser($user)
	{
		if (!array_key_exists($user->id, $this->data))
			return "";

		return FSS_Helper::escape_sequence_decode(trim($this->data[$user->id]->profile_value, "\""));
	}

	function search($string)
	{
		$db = JFactory::getDBO();

		$qry = "SELECT user_id FROM #__user_profiles WHERE profile_key = 'profile.postal_code' AND profile_value LIKE '%" . $db->escape($string) . "%'";
		$db->setQuery($qry);
		$results = $db->loadColumn();

		if (count($results) < 1)
			return array();

		return $results;
	}
}