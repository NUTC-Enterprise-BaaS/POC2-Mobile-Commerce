<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'task.php');

class Task_Users extends Task_Helper
{
	/**
	 * Updates the category for a ticket
	 */	
	function search()
	{
		$q = FSS_Input::getString('q');
		$db = JFactory::getDBO();
		
		$qry = "SELECT username, name FROM #__users WHERE username LIKE '%" . $db->escape($q) . "%' OR name LIKE '%" . $db->escape($q) . "%' ORDER BY username LIMIT 10";
		
		$db->setQuery($qry);
		
		$users = $db->loadObjectList();

		$output = array();
		
		foreach ($users as $user)
			$output[$user->username] = $user->name;

		header("Content-Type: application/json");
		
		echo json_encode($output);
		exit;
	}
}