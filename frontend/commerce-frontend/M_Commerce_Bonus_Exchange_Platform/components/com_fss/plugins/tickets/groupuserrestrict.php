<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

/**
 * Handle all ticket events from the ticket plugins we have available
 */
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'email.php');

class SupportActionsGroupUserRestrict extends SupportActionsPlugin
{
	var $title = "Single ticket group per user.";
	var $description = "If enabled, this allows a user to belong to only a single ticket group.";
	
	function groupAdd($params)
	{
		$user_id = (int)$params['user_id'];
		$group_id = (int)$params['group_id'];

		$qry = "SELECT * FROM #__fss_ticket_group_members WHERE user_id = $user_id";
		$db = JFactory::getDBO();
		$db->setQuery($qry);
		$result = $db->loadObjectList();

		if (count($result) > 0)
		{
			$user = JFactory::getUser($user_id);

			// already in group
			foreach ($result as $res)
			{
				if ($res->group_id == $group_id)
					return JText::sprintf("GROUPUSERRESTRICT_BELONG_TO_THIS", $user->username); //"The user '" . $user->username . "' already belongs to this ticket group";
			}

			return JText::sprintf("GROUPUSERRESTRICT_BELONG_TO_A", $user->username);// "The user '" . $user->username . "' already belongs to a ticket group";
		}

		return true;
	}
}