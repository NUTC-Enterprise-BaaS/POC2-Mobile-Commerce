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
 * 
 * Included here is a full list of the calls that can be made to this plugin, inluding some example code.
 * 
 * Please do not use this file for your plugins, make a copy of the parts you require, and change the class name to
 * match your file name, ie SupportActions{filename}
 * 
 * You will need to enable your new plugin in the Plugins page (Components -> Freestyle Support -> Overview -> Plugins)
 */

class SupportActionsLimitOpen extends SupportActionsPlugin
{
	var $title = "Limit opened tickets";
	var $description = "Limit opened tickets per user to xx per week, month etc";

	function Tickets_openNew($params)
	{
		$this->loadSettings();
		
		if (!isset($this->settings->conditions->enabled) || !$this->settings->conditions->enabled) return true;
		
		if (isset($params['admin_create']) && $params['admin_create'] > 0) return true;
		
		if (isset($this->settings->custom->custom) && $this->settings->custom->custom)
		{
			$result = $this->doCustom($params);
			if ($result !== true)
				return $result;
		}
		
		$db = JFactory::getDBO();
		$qry = $db->getQuery(true);
		$qry->select("count(*) as ticketcount");
		$qry->from("#__fss_ticket_ticket");
		
		if (isset($params['type']) && $params['type'] == 'without')
		{
			$qry->where('email = "' . $db->escape($params['email']) . '"');
		} else {
			$user = JFactory::getUser();
			
			if ($this->settings->conditions->group)
			{
				$sql = "SELECT group_id FROM #__fss_ticket_group_members WHERE user_id = '" . $db->escape($user->id) . "'";
				$db->setQuery($sql);
				$groups = $db->loadColumn();
					
				$all_users = array($user->id);
				
				if (count($groups) > 0)
				{
					$sql = "SELECT user_id FROM #__fss_ticket_group_members WHERE group_id IN (" . implode(", ", $groups) . ")";
					$db->setQuery($sql);
					$users = $db->loadColumn();
					
					$all_users = array_merge($all_users, $users);
				}
								
				$qry->where('user_id IN (' . implode(", ", $all_users) . ')');
			} else {
				$qry->where('user_id = ' . $user->id);
			}
		}

		if ($this->settings->conditions->type_count < 1) $this->settings->conditions->type_count = 1;

		switch ($this->settings->conditions->type)
		{
			case 'thisweek':
				$qry->where('WEEKOFYEAR(opened)=WEEKOFYEAR(NOW())');
				$qry->where('YEAR(opened)=YEAR(NOW())');
				break;
			case 'thismonth':
				$qry->where('MONTH(opened)=MONTH(NOW())');
				$qry->where('YEAR(opened)=YEAR(NOW())');
				break;
			case 'lastdays';
				$qry->where('opened > DATE_SUB(NOW(), INTERVAL ' . $this->settings->conditions->type_count . ' DAY)');
				break;
			case 'lastweeks';
				$qry->where('opened > DATE_SUB(NOW(), INTERVAL ' . $this->settings->conditions->type_count . ' WEEK)');
				break;
			case 'lastmonths';
				$qry->where('opened > DATE_SUB(NOW(), INTERVAL ' . $this->settings->conditions->type_count . ' MONTH)');
				break;
		}

		$db->setQuery($qry);
		
		$result = $db->loadResult();

		if ($result >= $this->settings->conditions->max_tickets)
		{
			$this->settings->message->message = str_replace("{count}", $result, $this->settings->message->message);
			
			return array('title' => $this->settings->message->subject, 'body' => $this->settings->message->message);
		}
		
		return true;
	}
	
	function doCustom($params)
	{
		$code = $this->settings->custom->php;	
		
		$fn = create_function('$type,$email,$name', (string)$code);
		
		if (!$fn) return array('title' => 'Unable to use custom code', 'body' => 'Unable to use custom php code for open condition');
		
		$result = $fn($params['type'], $params['email'], $params['name']);

		if (is_string($result))
		{
			return array('title' => $this->settings->message->subject, 'body' => $result);
		}
		
		if ($result === true) return true;
		if (is_array($result)) return $result;
		
		return array('title' => $this->settings->message->subject, 'body' => $this->settings->message->message);
	}
}