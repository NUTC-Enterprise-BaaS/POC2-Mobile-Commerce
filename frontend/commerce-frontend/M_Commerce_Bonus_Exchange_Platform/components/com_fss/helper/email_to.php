<?php

class FSS_EMail_To
{
	var $to = array();
	static $extra_admin_to;
	static $extra_admin_to_id;
	static $only_to;
	
	function add($email)
	{
		if (array_key_exists($email->email, $this->to))
		{
			// add reason
			$this->to[$email->email]->reasons[] = $email->source;
			
			if ( 
				($this->to[$email->email]->name == "" || $this->to[$email->email]->name == $this->to[$email->email]->email) && 
					$email->name != "" && $email->name != $email->email
				)
			{
				$this->to[$email->email]->name = $email->name;
			}
		} else {
			$email->reason = array($email->source);
			unset($email->source);
			$this->to[$email->email] = $email;
		}
	}
		
	function addAddress($address, $source = '')
	{
		$address = trim($address);

		if ($address == "") return;
		
		if (strpos($address, ","))
		{
			$addresss = explode(",", $address);
			foreach ($addresss as $address)
			{
				if (trim($address))
				{
					$target = new stdClass();
					$target->email = $address;
					$target->name = $address;
					$target->user_id = 0;
					$target->source = $source;
					$this->add($target);
				}
			}
		} else {
			if (trim($address))
			{
				$target = new stdClass();
				$target->email = $address;
				$target->name = $address;
				$target->user_id = 0;
				$target->source = $source;
				$this->add($target);
			}
		}
	}

	function addUser($ids, $object_field = null, $source = '')
	{	
		if (!$ids) return;
		if (!is_array($ids)) $ids = array($ids);		
		if (count($ids) < 1) return;

		$id_list = array();

		// sanitize the user id list
		if ($object_field)
		{
			foreach($ids as $o)
				$id_list[] = (int)$o->$object_field;
		} else {
			foreach($ids as $o)
				$id_list[] = (int)$o;
		}
		
		$query = " SELECT * FROM #__users WHERE id IN (".implode(", ", $id_list) . ")";
		$db = JFactory::getDBO();
		$db->setQuery($query);
		$users = $db->loadObjectList();
		
		foreach ($users as $user)
		{
			$target = new stdClass();
			$target->email = $user->email;
			$target->name = $user->name;
			$target->user_id = $user->id;
			$target->source = $source;
			$this->add($target);
		}
	}
	
	function addTicketAdmins($ticket)
	{
		// add the actual assigned ticket handler
		if ($ticket->admin_id > 0)
		{
			$this->addUser($ticket->admin_id, null, 'Admin: Ticket Admin ID');
		} else { // no assigned handler, so use the unassigned email list
			$this->addAddress(FSS_Settings::get('support_email_unassigned'), 'Admin: Unassigned ticket setting');
		} 	

		// if email all admins is set
		if (FSS_Settings::get('support_email_all_admins') && !(FSS_Settings::get('support_email_all_admins_only_unassigned') && $ticket->admin_id > 0))
		{
			// Build a list of all available ticket handlers
			$this->addUser(
				SupportUsers::getHandlersTicket(
						$ticket->prod_id, $ticket->ticket_dept_id, $ticket->ticket_cat_id, 
						FSS_Settings::get('support_email_all_admins_ignore_auto'), 
						FSS_Settings::get('support_email_all_admins_can_view'),
						false)
					, null, 'Admin: All admins setting');
		}
		
		// any cc emails need adding	
		$this->addAddress(FSS_Settings::get('support_email_admincc'), 'Admin: CC all setting');

		// any admins that are cc'd on the ticket
		$db = JFactory::getDBO();
		$qry = "SELECT user_id FROM #__fss_ticket_cc as c LEFT JOIN #__users as u ON c.user_id = u.id WHERE ticket_id = " . (int)$ticket->id . " AND isadmin = 1";
		$db->setQuery($qry);
		$this->addUser($db->loadObjectList(), 'user_id', 'Admin: CCd on ticket');
		
		if (isset(self::$extra_admin_to) && is_array(self::$extra_admin_to) && count(self::$extra_admin_to) > 0)
		{
			foreach (self::$extra_admin_to as $addy => $reason)
			{
				$target = new stdClass();
				$target->email = $addy;
				$target->name = $addy;
				$target->user_id = 0;
				$target->source = $reason;
				$this->add($target);
			}
			
			self::$extra_admin_to = array();
		}
				
		if (isset(self::$extra_admin_to_id) && is_array(self::$extra_admin_to_id) && count(self::$extra_admin_to_id) > 0)
		{
			foreach (self::$extra_admin_to_id as $userid => $reason)
			{
				$this->addUser($userid, null, $reason);
			}
			
			self::$extra_admin_to_id = array();
		}

		self::doOnlyToAddress();
	}
	
	function doOnlyToAddress()
	{
		if (isset(self::$only_to) && is_array(self::$only_to) && count(self::$only_to) > 0)
		{
			$this->to = array();
			
			foreach (self::$only_to as $addy => $reason)
			{
				$target = new stdClass();
				$target->email = $addy;
				$target->name = $addy;
				$target->user_id = 0;
				$target->source = $reason;
				$this->add($target);
			}
			
			self::$only_to = array();
		}
	}

	function addTicketUsers($ticket)
	{
		$db = JFactory::getDBO();
		$count = 0;
		
		$recipient = array();
		// add ticket user as recipient
		if ($ticket->user_id == 0 && $ticket->email)
		{
			$target = new stdClass();
			$target->email = $ticket->email;
			$target->name = $ticket->unregname;
			$target->user_id = 0;
			$target->source = "User: Ticket Unreg EMail";
			$this->add($target);
		} else {
			if ($ticket->user_id > 0) $this->addUser($ticket->user_id, null, "User: Ticket User ID");
		}

		// check for any ticket cc users
		if ($ticket->id > 0)
		{
			$qry = "SELECT u.name, u.id, u.email, c.email as uremail FROM #__fss_ticket_cc as c LEFT JOIN #__users as u ON c.user_id = u.id WHERE c.ticket_id = {$ticket->id} AND isadmin = 0 ORDER BY name";
			$db->setQuery($qry);
			$ticketcc = $db->loadObjectList();
			foreach ($ticketcc as $cc)
			{
				if ($cc->email)
				{
					$this->addUser($cc->id, null, "User: CC User ID");
				} else if ($cc->uremail) 
				{
					$this->addAddress($cc->uremail, null, "User: CC Unreg EMail");
				}
			}
		}

		// if user_id on ticket is set, then check for any group recipients
		if ($ticket->user_id > 0)
		{
			// get groups that the user belongs to
			$qry = "SELECT * FROM #__fss_ticket_group WHERE id IN (SELECT group_id FROM #__fss_ticket_group_members WHERE user_id = '".FSSJ3Helper::getEscaped($db, $ticket->user_id)."')";
			$db->setQuery($qry);
			$groups = $db->loadObjectList('id');
			
			if (count($groups) > 0)
			{
				$gids = array();
				foreach ($groups as $id => &$group)
					$gids[$id] = $id;	
				
				// get list of users in the groups
				$qry = "SELECT m.*, u.id as user_id, u.email, u.name FROM #__fss_ticket_group_members as m LEFT JOIN #__users as u ON m.user_id = u.id WHERE group_id IN (" . implode(", ",$gids) . ")";
				$db->setQuery($qry);
				$users = $db->loadObjectList();
				
				// for all users, if group has cc or user has cc then add to cc list			
				foreach($users as &$user)
				{
					if ($user->allemail || $groups[$user->group_id]->allemail) 
					{
						$this->addUser($user->user_id, null, "User: Ticket Group '" . $groups[$user->group_id]->groupname . "'");
					}
				}
			}
		}
		
		self::doOnlyToAddress();
	}
	
	function getAll()
	{
		return $this->to;	
	}
	
	static function UserToRec($user_id)
	{
		$target = new stdClass();
		$target->email = '';
		$target->name = '';
		$target->user_id = 0;

		if ($user_id > 0)
		{
			try {
				$user = JFactory::getUser($user_id);
				$target->email = $user->email;
				$target->name = $user->name;
				$target->user_id = $user_id;
				$target->reason[] = "";
			} catch (exception $e)
			{
				
			}	
		}
		
		return $target;
	}
}			 	 	    	 		