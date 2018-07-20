<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_users.php');

class FSS_Ticket_Helper
{		
	static function AssignHandler($prodid, $deptid, $catid, $force = false, $params = array())
	{
		//echo "Assigning hander for $prodid, $deptid, $catid<br>";
		$admin_id = 0;
		
		$assignuser = FSS_Settings::get('support_autoassign');
		if ($assignuser == 1 || $force)
		{
			$okusers = SupportUsers::getHandlersTicket($prodid, $deptid, $catid);
			if (count($okusers) > 0)
			{
				$count = count($okusers);
				$picked = mt_rand(0,$count-1);
				$admin_id = $okusers[$picked];
			} else {
				// no users found
				$fallback = FSS_Settings::get('support_handler_fallback');	
				if ($fallback != "" && $fallback > 0)
				{
					$user = JFactory::getUser($fallback);
					if ($user)
					{
						$admin_id = $user->id;	
					}
				}
			}
		}

		$params['admin_id'] = $admin_id;
		$params['prodid'] = $prodid;
		$params['deptid'] = $deptid;
		$params['catid'] = $catid;

		$new_admin_id = SupportActions::ActionResult("Tickets_customAssign", $params, true);

		if ($new_admin_id > 0 && $new_admin_id !== true) $admin_id = $new_admin_id;

		return $admin_id;
	}

	static function createRef($ticketid,$format = "",$depth = 0)
	{
		if ($format == "")
			$format = FSS_Settings::get('support_reference');

		if ($depth > 4)
			$format = "{4L}-{4L}-{4L}";

		preg_match_all("/(\d[LNX])/i",$format,$out);
		
		if (strpos($format, "{") !== false)
		{			
			preg_match_all("/\{([^\}]*)\}/i",$format,$out);
			$key = $format;
			foreach($out[1] as $match)
			{
				$count = substr($match,0,1);
				
				$format = "Y-m-d";
				if ($count == "D")
				{
					$type = "D";
					if (strlen($match) > 1)
						$format = substr($match,1);
				} else {
					$type = strtoupper(substr($match,1,1));
					if ($type == "" && (int)$count < 1)
					{
						$type = $count;
						$count = 1;
					}
				}
				$replace = "";

				if ($type == "X")
				{
					$replace = sprintf("%0".$count."d",$ticketid);		
				} else if ($type == "N")
				{
					for ($i = 0; $i < $count; $i++)
					{
						$replace .= rand(0,9);	
					}		
				} else if ($type == "L")
				{
					for ($i = 0; $i < $count; $i++)
					{
						$replace .= chr(rand(0,25)+ord('A'));	
					}								
				} else if ($type == "D")
				{
					$replace = date($format);	
				}
				
				$pos = strpos($key,"{".$match."}");
				if ($pos !== false)
				{
					$key = substr($key,0,$pos) . $replace . substr($key,$pos+strlen($match)+2);	
				}

			}
		} elseif (count($out) > 0)
		{
			$key = $format;
			foreach($out[0] as $match)
			{
				$count = substr($match,0,1);
				$type = strtoupper(substr($match,1,1));
				$replace = "";

				if ($type == "X")
				{
					$replace = sprintf("%0".$count."d",$ticketid);
						
				} else if ($type == "N")
				{
					for ($i = 0; $i < $count; $i++)
					{
						$replace .= rand(0,9);	
					}		
				} else if ($type == "L")
				{
					for ($i = 0; $i < $count; $i++)
					{
						$replace .= chr(rand(0,25)+ord('A'));	
					}								
				}
				
				$pos = strpos($key,$match);
				if ($pos !== false)
				{
					$key = substr($key,0,$pos) . $replace . substr($key,$pos+strlen($match));	
				}

			}
		} else {
			$key = FSS_Ticket_Helper::createRef($ticketid,"4L-4L-4L",$depth + 1);	
		}	
		
		// no [ ] in key
		$key = str_replace("[","", $key);
		$key = str_replace("]","", $key);

		$db = JFactory::getDBO();
		
		$query = "SELECT id FROM #__fss_ticket_ticket WHERE reference = '".FSSJ3Helper::getEscaped($db, $key)."'";
		$db->setQuery($query);
		$rows = $db->loadAssoc();
		
		if ($rows)
		{
			$key = FSS_Ticket_Helper::createRef($ticketid,$format,$depth + 1);
		}		

		return $key;
	}

	static $status_list;
	static function GetStatusList()
	{
		// get a list of all status
		if (empty(FSS_Ticket_Helper::$status_list))
		{
			$db = JFactory::getDBO();
			$db->setQuery("SELECT * FROM #__fss_ticket_status ORDER BY ordering");
			FSS_Ticket_Helper::$status_list = $db->loadObjectList();
		}
	}
	
	static function GetStatusByID($id)
	{
		FSS_Ticket_Helper::GetStatusList();
		
		if ($id == "open")
		{
			$ids = FSS_Ticket_Helper::GetStatusIDs("def_open");
			if (count($ids) > 0)
			{
				return FSS_Ticket_Helper::GetStatusByID($ids[0]);		
			}
		}

		foreach (FSS_Ticket_Helper::$status_list as $status)
		{
			if ($status->id == $id)
				return $status;
		}	
		
		return null;
	}

	static function GetStatuss($type, $not = false)
	{
		// returns the object of the status row with field $type set as 1	
		FSS_Ticket_Helper::GetStatusList();
		
		$rows = array();
		
		foreach (FSS_Ticket_Helper::$status_list as $status)
		{
			if ($not)
			{
				if ($status->$type == 0)
					$rows[] = $status;
			} else {
				if ($status->$type > 0)
					$rows[] = $status;
			}
		}

		return $rows;
	}
	
	static function GetStatusID($type)
	{
		FSS_Ticket_Helper::GetStatusList();
		foreach (FSS_Ticket_Helper::$status_list as $status)
		{
			if ($status->$type > 0)
				return (int)$status->id;
		}
		
		return 0;	
	}
	
	static function GetStatusIDs($type, $not = false, $exclude_archived = false)
	{
		FSS_Ticket_Helper::GetStatusList();
		
		$ids = array();
		
		foreach (FSS_Ticket_Helper::$status_list as $status)
		{
			if ($exclude_archived && $status->def_archive) continue;
			
			if ($not)
			{
				if ($status->$type == 0)
					$ids[] = (int)$status->id;
			} else {
				if ($status->$type > 0)
					$ids[] = (int)$status->id;
			}
		}
		
		if (count($ids) == 0)
			$ids[] = 0;
		
		return $ids;
	}	
	
	static function GetClosedStatus()
	{
		FSS_Ticket_Helper::GetStatusList();
		
		$ids = array();
		
		foreach (FSS_Ticket_Helper::$status_list as $status)
		{
			if ($status->is_closed && ! $status->def_archive)
					$ids[(int)$status->id] = (int)$status->id;
		}
		
		if (count($ids) == 0)
			$ids[] = 0;
		
		return $ids;
	}
}
