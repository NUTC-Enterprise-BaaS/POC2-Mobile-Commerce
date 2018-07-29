<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_ticket.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_source.php');

class FSS_Huru_Import
{
	// source prefix for the tables
	var $sp = "fukc"; // set to "#_" for current tables
	var $offset = 0;
	function Run()
	{
		$this->count = 0;
		$this->offset = JRequest::getVar('offset', 0);
		if ($this->offset < 1)
		{
			$this->doPriorities();
			$this->doStatus();
			$this->doCategories();
		}
		return $this->doTickets();
	}	
	
	function doPriorities()
	{
		$db = JFactory::getDBO();
		
		$qry = "TRUNCATE #__fss_ticket_pri";
		$db->setQuery($qry);
		$db->Query();
		
		$qry = "INSERT INTO #__fss_ticket_pri (id, title, access, ordering, color) SELECT priority_id, pname, 1, priority_id, '#000000' as col FROM {$this->sp}_huruhelpdesk_priority";
		$db->setQuery($qry);
		$db->Query();
	}	
	
	function doStatus()
	{
		$db = JFactory::getDBO();
		
		$qry = "TRUNCATE #__fss_ticket_status";
		$db->setQuery($qry);
		$db->Query();
		
		$qry = "INSERT INTO #__fss_ticket_status (id, title, color, published, ordering) SELECT id, sname, '#000000' as col, 1, id FROM {$this->sp}_huruhelpdesk_status";
		$db->setQuery($qry);
		$db->Query();
		
		$db->setQuery("UPDATE #__fss_ticket_status SET def_open = 1 WHERE title = 'NEW'");
		$db->Query();
		
		$db->setQuery("UPDATE #__fss_ticket_status SET def_user = 1 WHERE title = 'NEW'");
		$db->Query();
		
		$db->setQuery("UPDATE #__fss_ticket_status SET def_admin = 1 WHERE title = 'WAITING'");
		$db->Query();
		
		$db->setQuery("UPDATE #__fss_ticket_status SET is_closed = 1, def_closed = 1 WHERE title = 'CLOSED'");
		$db->Query();
		
		$db->setQuery("INSERT INTO #__fss_ticket_status (title, color, published, ordering, def_archive, is_closed) VALUES ('ARCHIVED', '#000000', 1, 99, 1, 1)");
		$db->Query();
	}
	
	function doCategories()
	{
		$db = JFactory::getDBO();
		
		$qry = "TRUNCATE #__fss_ticket_cat";
		$db->setQuery($qry);
		$db->Query();
		
		$qry = "INSERT INTO #__fss_ticket_cat (id, title, allprods, alldepts, access, ordering, published) SELECT category_id, cname, 1, 1, 1, category_id, 1 FROM {$this->sp}_huruhelpdesk_categories";
		$db->setQuery($qry);
		$db->Query();
	}	
	
	function doTickets()
	{
		$db = JFactory::getDBO();

		$import_limit = 100;

		if ($this->offset < 1)
		{
			$db->setQuery("TRUNCATE #__fss_ticket_ticket");
			$db->Query();
			
			$db->setQuery("TRUNCATE #__fss_ticket_messages");
			$db->Query();
		}
		
		$qry = "SELECT hu.*, u.username FROM {$this->sp}_huruhelpdesk_users as hu LEFT JOIN {$this->sp}_users as u ON hu.joomla_id = u.id WHERE isrep = 1";
		$db->setQuery($qry);
		$reps = $db->loadObjectList('id');
		$rep_jid = $db->loadObjectList('username');
		
		$qry = "SELECT * FROM {$this->sp}_huruhelpdesk_problems LIMIT {$this->offset}, $import_limit";
		$db->setQuery($qry);
		$problems = $db->loadObjectList();
		
		$missing_rep = array();
		
		foreach ($problems as $problem)
		{
			$ticket = new SupportTicket();
			$data = array();
			$data['id'] = $problem->id;
			$data['ticket_status_id'] = $problem->status;
			$data['title'] = $problem->title;
			$data['opened'] = $problem->start_date;
			$data['lastupdate'] = $problem->start_date;
			$data['ticket_pri_id'] = $problem->priority;
			$data['ticket_cat_id'] = $problem->category;
			$data['closed'] = $problem->close_date;
			$data['timetaken'] = $problem->time_spent;
			$data['user_id'] = static::getUser($problem->uid);
			$data['admin_id'] = 0;
			
			if (!array_key_exists($problem->rep, $reps))
			{
				$missing_rep[$problem->rep] = "Missing Rep : {$problem->rep}";	
			} else {
				$admin =  $reps[$problem->rep];
				if ($admin->username)
				{
					$data['admin_id'] = static::getUser($admin->username);
				} else {
					$missing_rep[$problem->rep] = "No User for Rep : {$problem->rep}, Joomla User ID : {$admin->joomla_id}";	
				}
			}
			
			$ticket->create($data);
			
			$mid = $ticket->addMessage($problem->description, "Problem", $data['user_id']);
			$db->setQuery("UPDATE #__fss_ticket_messages SET posted = '{$problem->start_date}' WHERE id = " . $mid);
			$db->Query();
			
			$qry = "SELECT * FROM {$this->sp}_huruhelpdesk_notes WHERE id = '{$problem->id}' ORDER BY adddate ASC";
			$db->setQuery($qry);
			$notes = $db->loadObjectList();
			
			$last_date = $problem->close_date ? $problem->close_date : $problem->start_date;
			foreach ($notes as $note)
			{
				$type = TICKET_MESSAGE_USER;
				
				if (array_key_exists($note->uid, $rep_jid))
					$type = TICKET_MESSAGE_ADMIN;
				
				if ($note->priv)
					$type = TICKET_MESSAGE_PRIVATE;

				$mid = $ticket->addMessage($note->note, "", static::getUser($note->uid), $type);
				$db->setQuery("UPDATE #__fss_ticket_messages SET posted = '{$note->adddate}' WHERE id = " . $mid);
				$db->Query();
				$last_date = $note->adddate;
			}
			
			// missing from problems
			/*
			uemail
			ulocation
			uphone
			*/
			
			if ($problem->solution != "")
			{
				$mid = $ticket->addMessage($problem->solution, "Solution", $data['admin_id'], TICKET_MESSAGE_ADMIN);
				$db->setQuery("UPDATE #__fss_ticket_messages SET posted = '{$problem->close_date}' WHERE id = " . $mid);
				$db->Query();
			}
			
			$db->setQuery("UPDATE #__fss_ticket_ticket SET lastupdate = '{$last_date}' WHERE id = " . $problem->id);
			$db->Query();

			$this->count++;
		}	
		
		$log = "<h4>Imported {$this->count} tickets, Nos {$this->offset} to " . ($this->offset + $this->count) . "</h4>";
		
		if ($this->count == $import_limit)
		{
			$log .= "<a href='". JRoute::_("index.php?option=com_fss&view=import&source=huru&offset=" . ($this->offset+$import_limit))."'>Run next $import_limit</a><Br/>";
		}
		
		$log .= "<h3>Missing Rep Lookups</h3>";
		$log .= "<pre>".print_r($missing_rep, true)."</pre>";	
		$log .= "<h3>Missing Usernames</h3>";
		$log .= "<pre>".print_r(static::$missing, true)."</pre>";	

		return $log;
	}
	
	static $users;
	static $missing;
	function getUser($username)
	{
		if (empty(static::$users))
		{
			$db = JFactory::getDBO();
			$qry = "SELECT * FROM #__users";
			$db->setQuery($qry);
			static::$users = $db->loadObjectList("username");
			static::$missing = array();
		}
		
		if (array_key_exists($username, static::$users))
			return static::$users[$username]->id;
		
		if (!array_key_exists($username, static::$missing))
			static::$missing[$username] = 0;
		
		static::$missing[$username]++;
		
		return 0;
	}	
}