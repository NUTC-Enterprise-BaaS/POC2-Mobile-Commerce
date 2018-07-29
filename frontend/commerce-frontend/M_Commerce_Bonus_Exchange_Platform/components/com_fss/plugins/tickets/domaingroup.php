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

class SupportActionsDomainGroup extends SupportActionsPlugin
{
	var $title = "Domain Groups";
	var $description = "When a user opens a ticket, they are automatically added to a ticket group based on the domain name of their email address.";
	
	function User_Open($ticket, $params)
	{
		$email = $ticket->useremail;
		
		if (!$email)
			return;
				
		list($part, $domain) = @explode("@", $email, 2);
		
		if (!$domain)
			return;
		
		// find group with name same as domain
		$db = JFactory::getDBO();
		$qry = "SELECT * FROM #__fss_ticket_group WHERE groupname = '" . $db->escape($domain) . "'";
		$db->setQuery($qry);
		$group = $db->loadObject();
		
		// if not found, create blank groups
		if (!$group)
		{
			$qry = "INSERT INTO #__fss_ticket_group (groupname, allsee, allemail, allprods, ccexclude) VALUES ('" . $db->escape($domain) . "', 0, 0, 1, 0)";
			$db->setQuery($qry);
			$db->query();
			$group_id = $db->insertid();
		} else {
			$group_id = $group->id;
		}
		
		// see if the user is already in the group
		$qry = "SELECT * FROM #__fss_ticket_group_members WHERE group_id = " . (int)$group_id . " AND user_id = " . (int)$ticket->user_id;
		$db->setQuery($qry);
		$entry = $db->loadObject();
		
		// if not, then add them
		if (!$entry)
		{
			$qry = "INSERT INTO #__fss_ticket_group_members (group_id, user_id) VALUES (" . (int)$group_id . ", " . (int)$ticket->user_id . ")";
			$db->setQuery($qry);
			$db->query();
		}
	}
	
}