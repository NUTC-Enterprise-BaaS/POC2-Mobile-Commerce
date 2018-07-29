<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_users.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_actions.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_source.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'permission.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'helper.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'files.php');

define("TICKET_MESSAGE_USER", 0);
define("TICKET_MESSAGE_ADMIN", 1);
define("TICKET_MESSAGE_PRIVATE", 2);
define("TICKET_MESSAGE_AUDIT", 3);
define("TICKET_MESSAGE_DRAFT", 4);
define("TICKET_MESSAGE_TIME", 5);
define("TICKET_MESSAGE_OPENEDBY", 6);

define("TICKET_ASSIGN_FORWARD", 0);
define("TICKET_ASSIGN_TOOK_OWNER", 1);
define("TICKET_ASSIGN_UNASSIGNED", 2);
define("TICKET_ASSIGN_ASSIGNED", 3);
		
class SupportTicket
{
	var $loaded = false;
	var $loaded_tags = false;
	var $loaded_attachments = false;
	var $loaded_groups = false;
	var $loaded_messages = false;
	var $loaded_custom = false;
	var $loaded_cc = false;
	
	var $loaded_messages_order = null;

	/* Storage for sub things */
	var $tags = array();
	var $attach = array();
	var $messages = array();
	var $groups = array();
	var $fields = array();
	var $related = array();
	var $related_ids = array();
	var $cc = array();
	var $user_cc = array();
	var $admin_cc = array();
	
	var $for_user = false;
	
	/* Options */
	var $audit_changes = true;
	var $send_emails = true;
	
	var $current_user = 0;
	
	var $is_batch = false;
	var $update_last_updated = true;
	var $is_new_ticket = false;
		
	function create($data)
	{
		$db = JFactory::getDBO();

		$allowed = array('reference',
			'ticket_status_id',
			'ticket_pri_id',
			'ticket_cat_id',
			'ticket_dept_id',
			'prod_id',
			'title',
			'opened',
			'closed',
			'lastupdate',
			'user_id',
			'email',
			'password',
			'admin_id',
			'unregname',
			'checked_out',
			'checked_out_time',
			'timetaken',
			'lang',
			'source',
			'source_cat',
			'merged',
			'rating');

		$base_data = array(
			'ticket_status_id' => FSS_Ticket_Helper::GetStatusID('def_open'),
			'opened' => date("Y-m-d H:i:s"),
			'lastupdate' => date("Y-m-d H:i:s"),
			'reference' => md5(time())
		);
		
		foreach ($base_data as $key => $value)
		{
			if (!array_key_exists($key, $data))
				$data[$key] = $value;	
		}
		
		$keys = array();
		$values = array();
		foreach ($data as $key => $value)
		{
			if (!in_array($key, $allowed)) continue;

			$keys[] = $db->escape($key);
			$values[] = "'" . $db->escape($value) . "'";
		}		
			
		$qry = "INSERT INTO #__fss_ticket_ticket (" . implode(", ", $keys) . ") VALUES (" . implode(", ", $values) . ")";
		$db->setQuery($qry);
		$db->query();
		
		$id = $db->insertid();
		$this->load($id, "force");

		$fields = FSSCF::GetCustomFields($this->id,$this->prod_id,$this->ticket_dept_id);
		FSSCF::StoreFields($fields,$this->id);
		
		$this->reference = FSS_Ticket_Helper::createRef($this->id);
		
		$qry = "UPDATE #__fss_ticket_ticket SET reference = '" . $db->escape($this->reference) . "' WHERE id = " . (int)$id;
		$db->setQuery($qry);
		$db->query();
	}
	
	function canLoad($ticket_id, $for_user = false)
	{
		$db = JFactory::getDBO();

		$query = SupportHelper::getBaseSQL();

		$query .= " WHERE t.id = '".$db->escape($ticket_id)."' AND ";

		$this->for_user = $for_user;

		if ($for_user)
		{
			$query .= SupportUsers::getUsersWhere();
		} else {
			$query .= SupportUsers::getAdminWhere();
		}

		$db->setQuery($query);
		$row = $db->loadObject();
		
		if (!$row)
			return false;
		
		return true;
	}
	
	function loadBasic($ticket_id, $for_user = false)
	{
		$ticket_id = (int)$ticket_id;
		
		$this->id = $ticket_id;
		
		$db = JFactory::getDBO();

		$query = SupportHelper::getBaseSQL();

		$query .= " WHERE t.id = '".$db->escape($ticket_id)."' AND ";

		$this->for_user = $for_user;

		if ($for_user)
		{
			$query .= SupportUsers::getUsersWhere();
		} else {
			$query .= SupportUsers::getAdminWhere();
		}
		
		//echo $query . "<br>";
		//echo dumpStack();
		
		$db->setQuery($query);
		$row = $db->loadObject();
		
		if (!$row)
		return false;
		
		return $this->loadFromRow($row);
	}
	
	function load($ticket_id, $for_user = false)
	{
		$ticket_id = (int)$ticket_id;
		
		$this->id = $ticket_id;
		
		$db = JFactory::getDBO();

		$query = SupportHelper::getBaseSQL();

		$query .= " WHERE t.id = '".$db->escape($ticket_id)."' AND ";

		if ($for_user === "unreg" || $for_user === "force")
		{
			$query .= " 1 ";
		} else if ($for_user)
		{
			$query .= SupportUsers::getUsersWhere($for_user);
		} else {
			$query .= SupportUsers::getAdminWhere();
		}

		$db->setQuery($query);
		$row = $db->loadObject();
		
		if (!$row)
			return false;
		
		$result = $this->loadFromRow($row, $for_user);
		
		$this->translate();
		
		return $result;
	}

	function setupUserPerimssions($userid = null)
	{
		$this->loadCC();
		
		if (!is_numeric($userid))
		{
			$user = JFactory::getUser();
			$userid = $user->get('id');
		} else {
			$user = JFactory::getUser($userid);	
		}
		
		$db = JFactory::getDBO();

		// sort read only - if its my own ticket, not read only
		$this->readonly = true;
		$this->canclose = false;

		// if we have a ticket merged into this one, make things read only!
		if ($this->merged > 0)
		{
			$this->readonly = true;
			$this->canclose = false;
			return;
		}
		
		// current ticket user	
		if ($this->user_id > 0 && $this->user_id == $userid) 
		{
			$this->readonly = false;
			$this->canclose = FSS_Settings::get('support_user_can_close');
			return;
		}
		
		// current unreg user
		if ($this->email != "")
		{
			if ($this->email == JFactory::getSession()->Get('ticket_email') || $this->reference == JFactory::getSession()->Get('ticket_reference'))
			{
				$this->readonly = false;
				$this->canclose = FSS_Settings::get('support_user_can_close');
				return;
			}
		}
			
		// setup if we have a cc on ticket
		foreach ($this->user_cc as $row)
		{
			if ($userid == $row->id && !$row->readonly) $this->readonly = false;
		}

		// no point doing ticket groups if we are unreg
		if ($this->user_id < 1)
			return;
		
		// find a list of groups the owner belongs to
		$qry = "SELECT group_id FROM #__fss_ticket_group_members WHERE user_id = '" . $db->escape($this->user_id) . "'";
		$db->setQuery($qry);
		$owner_groups = $db->loadObjectList('group_id');
		
		// find a list of groups the user belongs to
		$qry = "SELECT * FROM #__fss_ticket_group_members WHERE user_id = '" . $db->escape($userid) . "'";
		$db->setQuery($qry);
		$user_groups = $db->loadObjectList('group_id');
		
		// find common groups
		$groups = array();
		$gids = array();
		foreach ($user_groups as $group_id => $group)
		{
			if (array_key_exists($group_id, $owner_groups))
			{
				$groups[] =$group;	
				$gids[$group_id] = $group_id;
			}
		}

		// no groups found
		if (count($gids) == 0) return;
		
		// for each of the common groups, check if the users permissions for em and elevate if available
		$qry = "SELECT * FROM #__fss_ticket_group WHERE id IN (" . implode(", ", $gids) . ")";
		$db->setQuery($qry);
		
		$groups = $db->loadObjectList('id');
		
		if (count($groups) == 0) return; 
		
		foreach($groups as $group_id => $group)
		{
			$perm = $user_groups[$group_id]->allsee;
			if ($perm == 0) $perm = $group->allsee;
			if ($perm > 1) $this->readonly = false;
			if ($perm > 2) $this->canclose = FSS_Settings::get('support_user_can_close');
		}
		
		return;
	}
	
	function checkExist($ticket_id)
	{
		$db = JFactory::getDBO();
		$qry = "SELECT id FROM #__fss_ticket_ticket WHERE id = " . $db->escape($ticket_id);
		$db->setQuery($qry);
		$row = $db->loadObject();
		if ($row)
			return true;
		
		return false; 
	}
	
	function loadFromRow($row, $for_user = false)
	{
		$this->for_user = $for_user;

		foreach ($row as $key => $value)
			$this->$key = $value;
		
		$this->loaded = true;
		
		// verify that the id is an integer
		$this->id = (int)$this->id;
		
		$this->current_user = JFactory::getUser()->id;
		
		$this->msgcount = array();
		for ($i = 0 ; $i < 8 ; $i++) $this->msgcount[$i] = 0;
		$this->msgcount['total'] = 0;
			
		return true;
	}
	
	function loadAll($reverseMessages = null)
	{
		$this->loadTags();
		$this->loadAttachments();
		$this->loadGroups();
		$this->loadMessages($reverseMessages);
		$this->loadLockedUser();
		$this->loadCustomFields();
		$this->loadRelated();
		$this->loadCC();
		
		$this->linkMessagesAttach();		
	}
	
	function loadTags()
	{
		if (!$this->loaded_tags)
		{		
			$qry = "SELECT * FROM #__fss_ticket_tags WHERE ticket_id = {$this->id} ORDER BY tag";
			$db = JFactory::getDBO();
			$db->setQuery($qry);
			$tags = $db->loadObjectList();
			$this->tags = array();
			foreach ($tags as $tag)
				$this->tags[] = $tag->tag;
			$this->loaded_tags = true;	
		}
	}
	
	function loadAttachments()
	{
		if (!$this->loaded_attachments)
		{
			$db = JFactory::getDBO();
			$qry = "SELECT a.*, u.name FROM #__fss_ticket_attach as a LEFT JOIN #__users as u ON a.user_id = u.id WHERE ticket_ticket_id = {$this->id} ORDER BY added DESC";
			$db->setQuery($qry);
			$this->attach = $db->loadObjectList();
			$this->loaded_attachments = true;
			
			// check for any missing name entries, and look them up from message or ticket if needed
			foreach ($this->attach as &$attach)
			{
				if ($attach->name == "")
				{
					if (!$this->loaded_messages) $this->loadMessages();
					
					foreach ($this->messages as $message)
					{
						if ($message->id !=	$attach->message_id) continue;
						
						$attach->name = $message->name;
					}
				}
				
				if ($attach->name == "") // still no name, use name from ticket
				{
					if ($this->name)
					{
						$attach->name = $this->name;
					} else {
						$attach->name = $this->unregname;
					}
				}
			}
			
		}	
	}
	
	function loadGroups()
	{
		if (!$this->loaded_groups)
		{
			if ($this->user_id > 0)
			{
				$db = JFactory::getDBO();
				$query = "SELECT g.* FROM #__fss_ticket_group_members as m LEFT JOIN #__fss_ticket_group as g ON m.group_id = g.id WHERE m.user_id = " . (int)$this->user_id;
				$db->setQuery($query);
				$this->groups = $db->loadObjectList();
			} else {
				$this->groups = array();
			}
			$this->loaded_groups = true;	
		}
	}
	
	function loadMessages($reverse = null, $types = array())
	{
		if (!$this->loaded_messages)
		{
			$query = "SELECT m.*, u.name, u.username FROM #__fss_ticket_messages as m LEFT JOIN #__users as u ON m.user_id = u.id WHERE ticket_ticket_id = {$this->id}";
		
			if (count($types) > 0)
				$query .= " AND m.admin IN (" . implode(", ", $types) . ") ";
					
			if ($reverse === null)
			{
				if (SupportUsers::getSetting("reverse_order"))
				{
					$this->loaded_messages_order = true;
					$query .= " ORDER BY posted ASC";
				} else {
					$this->loaded_messages_order = false;
					$query .= " ORDER BY posted DESC";
				}
			} else if ($reverse)
			{
				$this->loaded_messages_order = false;
				$query .= " ORDER BY posted DESC";
			} else {
				$this->loaded_messages_order = true;
				$query .= " ORDER BY posted ASC";	
			}
			

			$db = JFactory::getDBO();
			$db->setQuery($query);
			
			$this->messages = $db->loadObjectList();
			$this->loaded_messages = true;
			
			$rating = 0;
			$rating_count = 0;
			
			foreach ($this->messages as $message)
			{
				$this->msgcount[$message->admin]++;	
				
				if ($message->rating > 0)
				{
					$rating += $message->rating;
					$rating_count ++;
				}
			}
			
			$this->message_rating = 0;
			if ($rating > 0) $this->message_rating = round($rating / $rating_count, 1);	
		}
	}

	function loadLockedUser()
	{
		$cotime = FSS_Helper::GetDBTime() - strtotime($this->checked_out_time);
		if ($cotime < FSS_Settings::get('support_lock_time') && $this->checked_out != JFactory::getUser()->id)
		{
			$this->co_user = JFactory::getUser($this->checked_out);
		}
	}
	
	function loadCustomFields()
	{
		if (!$this->loaded_custom)
		{
			$this->customfields = FSSCF::GetCustomFields($this->id,$this->prod_id,$this->ticket_dept_id,3);
			$this->custom = FSSCF::GetTicketValues($this->id, $this);
			$this->loaded_custom = true;
		}
	}
	
	function linkMessagesAttach()
	{
		foreach($this->attach as &$attach)
		{
			$message_id = $attach->message_id;
			foreach($this->messages as &$message)
			{
				if ($message->id == $message_id)
				{
					if (!array_key_exists('attach', $message))
						$message->attach = array();
						
					$message->attach[] = $attach;		
				}	
			}
		}
	}
	
	function loadRelated()
	{
		$db = JFactory::getDBO();
		$qry = "SELECT * FROM #__fss_ticket_related WHERE source_id = " . $this->id;
		$db->setQuery($qry);
		$rows = $db->loadObjectList();
		foreach ($rows as $row)
		{
			$this->related_ids[$row->dest_id] = $row->dest_id;
			
			$ticket = new SupportTicket();
			if ($ticket->load($row->dest_id))
			{
				$this->related[$row->dest_id] = $ticket;
			}
		}			
	}
	
	function loadCC()
	{
		if (!$this->loaded_cc)
		{
			$db = JFactory::getDBO();
			$qry = "SELECT u.name, u.username, u.email, u.id, c.isadmin, c.readonly, c.email as uremail, c.user_id as urid FROM #__fss_ticket_cc as c LEFT JOIN #__users as u ON c.user_id = u.id WHERE c.ticket_id = '{$this->id}' ORDER BY name";
			$db->setQuery($qry);
			$this->cc = $db->loadObjectList();
			
			$this->user_cc = array();
			$this->admin_cc = array();
			
			foreach ($this->cc as $cc)
			{
				if ($cc->isadmin)
					$this->admin_cc[] = $cc;
				else	
					$this->user_cc[] = $cc;
			}
			
			$this->loaded_cc = true;	
		}	
	}
	
/***************************************/
/** Modify functions for the ticket.  **/
/***************************************/

	function updateLastUpdated()
	{
		if (!$this->update_last_updated)
			return; 
		
		$now = FSS_Helper::CurDate();
		$qry = "UPDATE #__fss_ticket_ticket SET lastupdate = '{$now}' WHERE id = {$this->id}";
		$db = JFactory::getDBO();
		$db->setQuery($qry);
		$db->Query();	
		
		$this->lastupdate = $now;
	}
	
	function updatePriority($new_pri_id)
	{
		if ($new_pri_id == $this->ticket_pri_id)
			return true;
				
		$priorities = SupportHelper::getPriorities(false);
		
		$old_pri_id = $this->ticket_pri_id;
		
		$qry = "UPDATE #__fss_ticket_ticket SET ticket_pri_id = ".(int)$new_pri_id." WHERE id = {$this->id}";
		$db = JFactory::getDBO();
		$db->setQuery($qry);
		$db->Query();	
		
		$this->updateLastUpdated();
			
		$this->addAuditNote("Priority changed from '" . $priorities[$old_pri_id]->title . "' to '" . $priorities[$new_pri_id]->title . "'");
		
		$this->ticket_pri_id = $new_pri_id;
		
		SupportActions::DoAction_Ticket("updatePriority", $this, array('old_pri_id' => $old_pri_id, 'new_pri_id' => $new_pri_id));
	}
		
	function updateStatus($new_status_id, $direct_change = false, $is_batch = false)
	{
		// dont process if unchanged
		if ($new_status_id == $this->ticket_status_id) return true;
						
		// load in status list
		$statuss = SupportHelper::getStatuss(false);
	
		if ($new_status_id < 1) return true;
		
		if (!array_key_exists($new_status_id, $statuss)) return true;
	
		$old_status_id = $this->ticket_status_id;
	
		$old_st = $statuss[$old_status_id];
		$new_st = $statuss[$new_status_id];
				
		$now = FSS_Helper::CurDate();
		
		if ($new_st->is_closed)
		{
			$isclosed = "closed = '{$now}'";	
		} else {
			$isclosed = "closed = NULL";	
		}
			
		$qry = "UPDATE #__fss_ticket_ticket SET ticket_status_id = {$new_status_id}, {$isclosed} WHERE id = {$this->id}";
		$db = JFactory::getDBO();
		$db->setQuery($qry);
		$db->Query();	
		
		$this->updateLastUpdated();
				
		$this->addAuditNote("Status changed from '" . $statuss[$old_status_id]->title . "' to '" . $statuss[$new_status_id]->title . "'");
				
		// update the object with the new status
		$this->ticket_status_id = $new_status_id;
		
		// If we have closed the ticket, the closed field needs updating
		if ($new_st->is_closed)
		{
			$this->closed = $now;
		} else {
			// Otherwise it should be null
			$this->closed = null;
		}
	
		SupportActions::DoAction_Ticket("updateStatus", $this, array('old_status_id' => $old_status_id, 'new_status_id' => $new_status_id, 'direct' => $direct_change, 'batch' => $is_batch));
	}
	
	function updateCategory($new_cat_id)
	{
		if ($new_cat_id == $this->ticket_cat_id)
			return true;
		
		$cats = SupportHelper::getCategories(false);		
		
		$old_cat_id = $this->ticket_cat_id;

		$qry = "UPDATE #__fss_ticket_ticket SET ticket_cat_id = ".(int)$new_cat_id." WHERE id = {$this->id}";
		$db = JFactory::getDBO();
		$db->setQuery($qry);
		$db->Query();	
		
		$this->updateLastUpdated();
			
		$this->addAuditNote("Category changed from '" . $cats[$old_cat_id]->title . "' to '" . $cats[$new_cat_id]->title . "'");
		
		$this->ticket_cat_id = $new_cat_id;	
		
		SupportActions::DoAction_Ticket("updateCategory", $this, array('old_cat_id' => $old_cat_id, 'new_cat_id' => $new_cat_id));
	}
	
	function updateUser($new_user_id)
	{
		if ($new_user_id == $this->user_id)
			return true;
		
		$old_user = JFactory::getUser($this->user_id);
		$new_user = JFactory::getUser($new_user_id);
		$qry = "UPDATE #__fss_ticket_ticket SET user_id = ".(int)$new_user_id." WHERE id = {$this->id}";
		$db = JFactory::getDBO();
		$db->setQuery($qry);
		$db->Query();	
		
		$this->updateLastUpdated();
			
		$this->addAuditNote("User changed from '{$old_user->name} ({$old_user->username})' to '{$new_user->name} ({$new_user->username})'");
		
		$this->user_id = $new_user_id;	
		
		SupportActions::DoAction_Ticket("updateUser", $this, array('old_user_id' => $this->user_id, 'new_user_id' => $new_user_id));
	}
	
	function updateProduct($new_product_id)
	{
		if ($new_product_id == $this->prod_id)
			return true;
		
		$old_product = $this->getProduct();
		
		$qry = "UPDATE #__fss_ticket_ticket SET prod_id = ".(int)$new_product_id." WHERE id = {$this->id}";
		$db = JFactory::getDBO();
		$db->setQuery($qry);
		$db->Query();	
		
		$this->updateLastUpdated();
		
		$this->prod_id = $new_product_id;	
		$new_product = $this->getProduct();
		
		$this->addAuditNote("Product changed from '{$old_product->title}' to '{$new_product->title}'");
		
		SupportActions::DoAction_Ticket("updateProduct", $this, array('old_prod_id' => $old_product->id, 'new_prod_id' => $new_product_id));	
	}
	
	function updateDepartment($new_department_id)
	{
		if ($new_department_id == $this->ticket_dept_id)
			return true;
		
		$old_department = $this->getDepartment();
		
		$qry = "UPDATE #__fss_ticket_ticket SET ticket_dept_id = ".(int)$new_department_id." WHERE id = {$this->id}";
		$db = JFactory::getDBO();
		$db->setQuery($qry);
		$db->Query();	
		
		$this->updateLastUpdated();
		
		$this->ticket_dept_id = $new_department_id;	
		$new_department = $this->getDepartment();
		
		$this->addAuditNote("Department changed from '{$old_department->title}' to '{$new_department->title}'");
		
		SupportActions::DoAction_Ticket("updateDepartment", $this, array('old_department_id' => $old_department->id, 'new_department_id' => $new_department_id));	
	}
	
	function updateUnregEMail($new_email)
	{
		if ($new_email == $this->email)
			return true;

		$old_email = $this->email;

		$db = JFactory::getDBO();
		$qry = "UPDATE #__fss_ticket_ticket SET email = '".FSSJ3Helper::getEscaped($db,$new_email)."' WHERE id = {$this->id}";
		$db->setQuery($qry);
		$db->Query();	
		
		$this->updateLastUpdated();
			
		$this->addAuditNote("EMail changed from '" . $old_email . "' to '" . $new_email . "'");
		
		$this->email = $new_email;	
		
		SupportActions::DoAction_Ticket("updateUnregEMail", $this, array('old_email' => $old_email, 'new_email' => $new_email));
	}
	
	function updateSubject($new_subject)
	{
		if ($new_subject == $this->title)
			return true;

		$old_subject = $this->title;

		$db = JFactory::getDBO();
		$qry = "UPDATE #__fss_ticket_ticket SET title = '".FSSJ3Helper::getEscaped($db,$new_subject)."' WHERE id = {$this->id}";
		$db->setQuery($qry);
		$db->Query();	
		
		$this->updateLastUpdated();
			
		$this->addAuditNote("Subject changed from '" . $old_subject . "' to '" . $new_subject . "'");
		
		$this->title = $new_subject;	
		
		SupportActions::DoAction_Ticket("updateSubject", $this, array('old_subject' => $old_subject, 'new_subject' => $new_subject));
	}
	
	function updateSource($new_source)
	{
		if ($new_source == $this->source)
			return true;

		$old_source = $this->source;
		
		$db = JFactory::getDBO();
		$qry = "UPDATE #__fss_ticket_ticket SET source = '".FSSJ3Helper::getEscaped($db,$new_source)."' WHERE id = {$this->id}";
		$db->setQuery($qry);
		$db->Query();	
		
		$this->updateLastUpdated();
			
		$new_source_title = SupportSource::get_source_title($new_source);
		$old_source_title = SupportSource::get_source_title($old_source);
		
		$this->addAuditNote("Source changed from '" . $old_source_title . "' to '" . $new_source_title . "'");
		
		$this->source = $new_source;	
		
		SupportActions::DoAction_Ticket("updateSource", $this, array('old_source' => $old_source, 'new_source' => $new_source));
	}
	
	function updateLock()
	{
		$db = JFactory::getDBO();
		$now = FSS_Helper::CurDate();
		$qry = "UPDATE #__fss_ticket_ticket SET checked_out = '".(int)JFactory::getUser()->id."', checked_out_time = '{$now}' where id = {$this->id}";
		$db->setQuery($qry);
		$db->query();		
		
		SupportActions::DoAction_Ticket("updateLock", $this);
	}
	
	function updateCustomField($fieldid, $value, $max_permission = 3)
	{
		//echo "updateCustomField($fieldid, $value, $max_permission)<br>";
		// TODO: Dont like how this works, needs the field data to be stored in the class object!
		if (empty($this->fields))
			$this->fields = FSSCF::GetCustomFields($this->id,$this->prod_id,$this->ticket_dept_id,$max_permission);
		
		list($old, $new) = FSSCF::StoreField($fieldid, $this->id, $this, $value);
					
		if ($old != $new)
		{
			$field = FSSCF::GetField($fieldid);
			if ($field->type == 'checkbox')
			{
				if ($old == "") $old = "No";
				if ($old == "on") $old = "Yes";	
				if ($new == "") $new = "No";
				if ($new == "on") $new = "Yes";	
			}
			$this->addAuditNote("Custom field '" . $field->description . "' changed from '" . $old . "' to '" . $new . "'");
			
			$this->updateLastUpdated();

			SupportActions::DoAction_Ticket("updateCustomField", $this, array('field_id' => $fieldid, 'old' => $old, 'new' => $new));
		}
	}
	
	function addTag($tag)
	{	
		$tag = trim($tag);
		if (!$tag) return;
		
		$this->loadTags();

		if (in_array($tag, $this->tags))
			return true;
		
		$tag = trim($tag);
		
		$db = JFactory::getDBO();
		$qry = "REPLACE INTO #__fss_ticket_tags (ticket_id, tag) VALUES ({$this->id}, '".FSSJ3Helper::getEscaped($db, $tag)."')";
		$db->setQuery($qry);
		$db->query();		
			
		$this->addAuditNote("Add tag '" . $tag . "'");

		$this->tags[] = $tag;
		
		sort($this->tags);
		
		$this->updateLastUpdated();

		SupportActions::DoAction_Ticket("addTag", $this, array('tag' => $tag));
	}
	
	function removeTag($tag)
	{
		$this->loadTags();

		if (!in_array($tag, $this->tags))
			return true;	
		
		$db = JFactory::getDBO();
		$qry = "DELETE FROM #__fss_ticket_tags WHERE ticket_id = {$this->id} AND tag = '".FSSJ3Helper::getEscaped($db, $tag)."'";
		$db->setQuery($qry);
		$db->query();		
			
		$this->addAuditNote("Remove tag '" . $tag . "'");

		if (($key = array_search($tag, $this->tags)) !== false) {
			unset($this->tags[$key]);
		}

		sort($this->tags);
		
		$this->updateLastUpdated();

		SupportActions::DoAction_Ticket("removeTag", $this, array('tag' => $tag));
	}
	
	function addTime($minutes, $notes = "", $post_message = false, $timestart = 0, $timeend = 0)
	{
		$db = JFactory::getDBO();
		$qry = "UPDATE #__fss_ticket_ticket SET timetaken = timetaken + " . (int)$minutes . " WHERE id = {$this->id}";
		$db->setQuery($qry);
		$db->Query();
		
		// extra query to force the time to always be above 0
		$qry = "UPDATE #__fss_ticket_ticket SET timetaken = 0 WHERE id = {$this->id} AND timetaken < 0";
		$db->setQuery($qry);
		$db->Query();
		
		$msg = "Added $minutes minutes to time taken.";
		if ($notes)
			$msg .= " Notes: " . $notes;
		// add audit message for the time logged
		$this->addAuditNote($msg);
		
		if ($minutes == 0)
		{
			$timestart = 0;
			$timeend = 0;
		}
		
		if ($post_message)
		{
			$this->addMessage($notes, "", -1, TICKET_MESSAGE_TIME, $minutes, $timestart, $timeend);	
		}
		
		SupportActions::DoAction_Ticket("addTime", $this, array('time' => $minutes, 'notes' => $notes));
	}
	
	function addTimeQuiet($minutes)
	{
		$db = JFactory::getDBO();
		$qry = "UPDATE #__fss_ticket_ticket SET timetaken = timetaken + " . (int)$minutes . " WHERE id = {$this->id}";
		$db->setQuery($qry);
		$db->Query();
	}
	
	function deleteAttach($file_id)
	{
		$attach = $this->getAttach($file_id);
		if ($attach)
		{
			$db = JFactory::getDBO();
			$qry = "DELETE FROM #__fss_ticket_attach WHERE ticket_ticket_id = {$this->id} AND id = {$attach->id}";
			$db->setQuery($qry);
			$db->Query();
			
			$destpath = JPATH_SITE.DS.FSS_Helper::getAttachLocation().DS.'support'.DS;			
			$file = $destpath . $attach->diskfile;

			if (file_exists($file))
				JFile::delete($file);
			
			if (file_exists($file. ".thumb"))
				JFile::delete($file. ".thumb");
			
			$this->addAuditNote("Deleting attachment '" . $attach->filename . "'");
		
			SupportActions::DoAction_Ticket("deleteAttach", $this, array('attach' => $attach));
		}
	}
	
	function addMessage($body, $subject = "", $user_id = -1, $type = TICKET_MESSAGE_USER, $time = 0, $timestart = 0, $timeend = 0, $source = '')
	{
		$db = JFactory::getDBO();
		
		// no user id passed, so use the one from the current user
		if ($user_id == -1)
			$user_id = JFactory::getUser()->id;
		
		if ($time == 0)
		{
			$timestart = 0;
			$timeend = 0;
		}
		
		// add a message to the ticket
		$qry = "INSERT INTO #__fss_ticket_messages (ticket_ticket_id, subject, body, user_id, admin, posted, `time`, timestart, timeend, source) VALUES ('";
		$qry .= $db->escape($this->id) . "','".$db->escape($subject)."','".$db->escape($body)."',".(int)$user_id.", " . (int)$type . ", '".FSS_Helper::CurDate()."', '" . $db->escape($time) ."', '" . $db->escape($timestart) ."', '" . $db->escape($timeend) ."', '" . $db->escape($source) ."')";
			
		//echo $qry . "<br>";
		
		$db->SetQuery( $qry );
		$db->Query();
		
		$message_id = $db->insertid();
		
		$this->updateLastUpdated();
			
		SupportActions::DoAction_Ticket("addMessage", $this, array('user_id' => $user_id, 'type' => $type, 'subject' => $subject, 'body' => $body, 'message_id' => $message_id, 'source' => $source));
			
		return $message_id;
	}
	
	function addMessageUnreg($body, $subject, $type, $name, $email, $source = '')
	{
		$db = JFactory::getDBO();
		
		$qry = "INSERT INTO #__fss_ticket_messages (ticket_ticket_id, subject, body, admin, posted, poster, email, source) VALUES ('";
		$qry .= $db->escape($this->id) . "','".$db->escape($subject)."','".$db->escape($body)."', " . (int)$type . ", '".FSS_Helper::CurDate()."', '" . $db->escape($name) ."', '" . $db->escape($email) ."', '" . $db->escape($source) ."')";
		
		$db->SetQuery( $qry );
		$db->Query();
		
		$message_id = $db->insertid();
		
		$this->updateLastUpdated();
		
		SupportActions::DoAction_Ticket("addMessage", $this, array('email' => $email, 'name' => $name, 'unreg' => 1, 'type' => $type, 'subject' => $subject, 'body' => $body, 'message_id' => $message_id, 'source' => $source));
		
		return $message_id;
	}
	
	function addFilesFromPost($message_id, $user_id = -1, $hide_from_user = 0)
	{
		// ADD ALL POSTED FILES TO THE TICKET	
		if ($user_id == -1)
			$user_id = JFactory::getUser()->id;
		
		$files = array();
		
		// save any file attachments
		for ($i = 0; $i < 10; $i ++)
		{
			$file = JRequest::getVar('filedata_'.$i, '', 'FILES', 'array');
			if (array_key_exists('error',$file) && $file['error'] == 0 && $file['name'] != '')
			{
				$destpath = JPATH_SITE.DS.FSS_Helper::getAttachLocation().DS.'support'.DS;					
				$destname = FSS_File_Helper::makeAttachFilename("support", $file['name'], date("Y-m-d"), $this, $user_id);
		 
				if (JFile::upload($file['tmp_name'], $destpath . $destname))
				{
					$qry = "INSERT INTO #__fss_ticket_attach (ticket_ticket_id, filename, diskfile, size, user_id, added, message_id, hidefromuser) VALUES ('";
					$qry .= FSSJ3Helper::getEscaped($db, $this->id) . "',";
					$qry .= "'" . FSSJ3Helper::getEscaped($db, $file['name']) . "',";
					$qry .= "'" . FSSJ3Helper::getEscaped($db, $destname) . "',";
					$qry .= "'" . $file['size'] . "',";
					$qry .= "'" . FSSJ3Helper::getEscaped($db, $user_id) . "',";
					$qry .= "'".FSS_Helper::CurDate()."', $message_id, '".FSSJ3Helper::getEscaped($db, $hide_from_user)."' )";
				
					//echo $qry . "<br>";

					$file_obj = new stdClass();
					$file_obj->filename = $file['name'];
					$file_obj->diskfile = $destname;
					$file_obj->size = $file['size'];
					$files[] = $file_obj;

					$db->setQuery($qry);$db->Query();    
					
					SupportActions::DoAction_Ticket("addFile", $this, array('file' => $file_obj));
				}
			}
		}

		// new style posted files using jquery file uploaded
		$post_files = JRequest::getVar('new_filename', 'POST', 'array');
		$token = FSS_File_Helper::makeUploadSubdir(JRequest::getVar('upload_token'));
		if (is_array($post_files))
		{
			foreach ($post_files as $file)
			{
				$destpath = JPATH_SITE.DS.FSS_Helper::getAttachLocation().DS.'support'.DS;					
				$destname = FSS_File_Helper::makeAttachFilename("support", $file, date("Y-m-d"), $this, $user_id);
				$source = JPATH_ROOT.'/tmp/fss/incoming/'.$token.'/'.$file;

				require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'bigfiletools.php');
				$f = BigFileTools::fromPath($source);
				$size  = $f->getSize();

				$dest = $destpath . $destname;

				if (JFile::move($source, $dest))
				{
					$qry = "INSERT INTO #__fss_ticket_attach (ticket_ticket_id, filename, diskfile, size, user_id, added, message_id, hidefromuser) VALUES ('";
					$qry .= FSSJ3Helper::getEscaped($db, $this->id) . "',";
					$qry .= "'" . FSSJ3Helper::getEscaped($db, $file) . "',";
					$qry .= "'" . FSSJ3Helper::getEscaped($db, $destname) . "',";
					$qry .= "'" . $size . "',";
					$qry .= "'" . FSSJ3Helper::getEscaped($db, $user_id) . "',";
					$qry .= "'".FSS_Helper::CurDate()."', $message_id, '".FSSJ3Helper::getEscaped($db, $hide_from_user)."' )";
					
					$file_obj = new stdClass();
					$file_obj->filename = $file;
					$file_obj->diskfile = $destname;
					$file_obj->size = $size;
					$files[] = $file_obj;

					$db->setQuery($qry);$db->Query();    
					
					SupportActions::DoAction_Ticket("addFile", $this, array('file' => $file_obj));
				}
			}	

		}

		if (is_dir(JPATH_ROOT.'/tmp/fss/incoming/'.$token))
			@rmdir(JPATH_ROOT.'/tmp/fss/incoming/'.$token);

		FSS_File_Helper::CleanupIncoming();

		if (count($files) < 1)
			return false;

		return $files;
	}
	
	function updateMessage($message_id, $subject, $body, $time = 0, $timestart = 0, $timeend = 0)
	{
		$db = JFactory::getDBO();
		$message = $this->getMessage($message_id);
		
		if (!$message)
			return;
		
		if ($message->subject != $subject)
		{
			$this->addAuditNote("Message on " . $message->posted . ", subject changed from '".$message->subject."'");
		} 
		if ($message->body != $body)
		{
			$this->addAuditNote("Message on " . $message->posted . ", body changed from '".$message->body."'");
		} 

		$qry = "UPDATE #__fss_ticket_messages SET subject = '".$db->escape($subject)."', body = '".$db->escape($body)."'";
		
		if ($time > 0)
		{
			$qry .= ", time = " . (int)$time . " ";	
			$qry .= ", timestart = " . (int)$timestart . " ";	
			$qry .= ", timeend = " . (int)$timeend . " ";	
			
			$old_time = $message->time;
			$this->addTimeQuiet(-$old_time);
			$this->addTimeQuiet($time);
			
			$this->addAuditNote("Message on " . $message->posted . ", time changed from '".$old_time."'");
		}
		
		$qry .= " WHERE id = " . FSSJ3Helper::getEscaped($db, $message_id);
		$db->setQuery($qry);
		$db->Query($qry);
		
		SupportActions::DoAction_Ticket("updateMessage", $this, array('message_id' => $message_id, 'old_subject' => $message->subject, 'new_subject' => $subject, 'old_body' => $message->body, 'new_body' => $body));
	}
	
	function deleteMessage($message_id, $subject, $body)
	{
		$message = $this->getMessage($message_id);
		
		if (!$message)
			return;
		
		$this->addAuditNote("Message on " . $message->posted . " deleted, '".$message->subject . "', '".$message->body."'");
		
		$qry = "DELETE FROM #__fss_ticket_messages WHERE id = " . FSSJ3Helper::getEscaped($db, $message_id);
		$db->setQuery($qry);
		$db->Query($qry);
		
		SupportActions::DoAction_Ticket("deleteMessage", $this, array('message_id' => $message_id, 'subject' => $message->subject, 'body' => $message->body));
	}
	
	function addRelated($ticketid)
	{
		$db = JFactory::getDBO();
		
		$qry = "REPLACE INTO #__fss_ticket_related (source_id, dest_id) VALUES ";
		$qry .= "(" . (int)$this->id . ", " . (int)$ticketid . ") ,";
		$qry .= "(" . (int)$ticketid . ", " . (int)$this->id . ")";
		
		$db->setQuery($qry);
		$db->Query();
	}
	
	function removeRelated($ticketid)
	{
		$db = JFactory::getDBO();
		
		$qry = "DELETE FROM #__fss_ticket_related WHERE source_id = " . (int)$this->id . " AND dest_id = " .  (int)$ticketid;
		$db->setQuery($qry);
		$db->Query();
		
		$qry = "DELETE FROM #__fss_ticket_related WHERE source_id = " . (int)$ticketid . " AND dest_id = " .  (int)$this->id;
		$db->setQuery($qry);
		$db->Query();
	}
	
	function addCC($ids, $is_admin, $is_readonly)
	{
		$db = JFactory::getDBO();
		
		if (!is_array($ids))
		{
			$t = $ids;
			$ids = array();
			$ids[] = $t;
		}

		foreach ($ids as $id)
		{
			if ($id > 0)
			{
				$qry = "REPLACE INTO #__fss_ticket_cc (ticket_id, user_id, isadmin, readonly) VALUES (" . $db->escape((int)$this->id) . ", ";
				$qry .= $db->escape((int)$id) . ", " . $db->escape((int)$is_admin) . ", " . $db->escape($is_readonly) . ")";
			
				$db->setQuery($qry);
				$db->Query();
		
				SupportActions::DoAction_Ticket("addCC", $this, array('user_id' => $id, 'is_admin' => $is_admin, 'is_readonly' => $is_readonly));
			}
		}
	}
	
	function addEMailCC($email)
	{
		$db = JFactory::getDBO();
		
		$id = 100000000 + mt_rand(100000,999999);
		
		$qry = "REPLACE INTO #__fss_ticket_cc (ticket_id, user_id, isadmin, email) VALUES (" . $db->escape((int)$this->id) . ", ";
		$qry .= $db->escape((int)$id) . ", 0, '" . $db->escape($email) . "')";
			
		$db->setQuery($qry);
		$db->Query();
		
		SupportActions::DoAction_Ticket("addCC", $this, array('email' => $email, 'is_admin' => $is_admin, 'is_readonly' => $is_readonly));
	}
	
	function removeCC($ids, $is_admin)
	{
		$db = JFactory::getDBO();
		
		if (!is_array($ids))
		{
			$t = $ids;
			$ids = array();
			$ids[] = $t;
		}

		foreach ($ids as $id)
		{
			$qry = "DELETE FROM #__fss_ticket_cc WHERE ticket_id = " . $db->escape($this->id);
			$qry .= " AND user_id = " . $db->escape($id);
			$qry .= " AND isadmin = " . $db->escape((int)$is_admin);

			$db->setQuery($qry);
			$db->Query();
		}
	}

	
/************************************/
/** Get functions for the ticket.  **/
/** Retrieve various data about it **/
/************************************/

	/**
	 * Returns the category object for the current ticket
	 */	
	function getCategory()
	{
		$cats = SupportHelper::getCategories(false);			
		return $cats[$this->ticket_cat_id];	
	}
	
	function getPriority()
	{
		$pris = SupportHelper::getPriorities(false);
		if (isset($pris[$this->ticket_pri_id]))
			return $pris[$this->ticket_pri_id];
		
		return reset($pris);
	}	
	
	function getProduct()
	{
		$prods = SupportHelper::getProducts(false);
		if (array_key_exists($this->prod_id, $prods))
			return $prods[$this->prod_id];
	
		return null;
	}	
	
	function getDepartment()
	{
		$depts = SupportHelper::getDepartments(false);
		if (array_key_exists($this->ticket_dept_id, $depts))
			return $depts[$this->ticket_dept_id];
	
		return null;
	}	
	
	function getStatus($foruser = false)
	{
		$statuss = SupportHelper::getStatuss(false);
		FSS_Translate_Helper::Tr($statuss);
		
		$cur_status = $statuss[$this->ticket_status_id];

		if ($foruser)
		{
			if ($cur_status->combine_with > 0)
			{
				$new_status = $statuss[$cur_status->combine_with];
				
				if ($new_status->userdisp) $new_status->title = $new_status->userdisp;
				
				return $new_status;
				
			} else if ($cur_status->userdisp) {
				
				$new_status = (object) (array) $cur_status;
				$new_status->title = $new_status->userdisp;
				
				return $new_status;
			}	
		}
		
		return $cur_status;
	}
	/**
	 * Return an array of the current tags
	 */	
	function getTags()
	{
		$this->loadTags();
		
		return $this->tags;
	}
	
	function getUserEMail()
	{
		if ($this->user_id > 0)
			return $this->useremail;
		
		if ($this->email == "@" || $this->email == "")
			return "Unknown";
		
		return $this->email;
	}	
	
	function getUserName()
	{
		if ($this->user_id > 0)
		{
			return $this->name;
		}
		
		if ($this->unregname == "@" || $this->unregname == "")
			return "Unknown";
		
		return $this->unregname;
	}	
	
	function getTitle()
	{
		return self::parseTitle($this->title, $this->id);
	}
	
	static function parseTitle($title, $ticketid)
	{
		// This needs updating to use loaded messages if we have them
		if (trim($title) != "")
		{
			return $title;
		} else {
			// no title for the ticket, so load the oldest message, and display the first part of that
			$db = JFactory::getDBO();
			$qry = "SELECT * FROM #__fss_ticket_messages WHERE ticket_ticket_id = " . $db->escape($ticketid) . " ORDER BY posted ASC LIMIT 1";
			$db->setQuery($qry);
			$message = $db->loadObject();
			$msg = $message->body;
			$msg = FSS_Helper::ParseBBCode($msg);
			$msg = strip_tags($msg);
			if (trim($msg) != "")
			{
				if (strlen($msg) > 50) return substr($msg, 0, 50) . "...";	
				
				return $msg;
			} else {
				return JText::_('NO_SUBJECT');
			}
		}	
	}	
	function getAttach($file_id)
	{
		if (!$this->loaded_attachments)
			$this->loadAttachments();
		
		foreach ($this->attach as $file)
		{
			if ($file->id == $file_id)
				return $file;	
		}	
		
		return null;
	}
	
	function getField($field_id)
	{
		if (!$this->loaded_custom) $this->loadCustomFields();	
		
		foreach ($this->customfields as $field)
		{
			if ($field['id'] == $field_id) return $field;	
		}	
		
		return null;
	}	
	
	function getFieldValue($field_id)
	{
		if (!$this->loaded_custom) $this->loadCustomFields();	
		
		foreach ($this->custom as $custom)
		{
			if ($custom['field_id'] == $field_id) return $custom['value'];	
		}	
		
		return null;
	}
	
	function getMessage($message_id)
	{
		if (!$this->loaded_messages)
			$this->loadMessages();	
		
		foreach ($this->messages as $message)
		{
			if ($message->id == $message_id)
				return $message;	
		}	
		
		return null;
	}
	
	function isLocked()
	{
		if (empty($this->locked))
		{
			$cotime = FSS_Helper::GetDBTime(); - strtotime($this->checked_out_time);
			$this->locked = false;
			if ($cotime < FSS_Settings::get('support_lock_time') && $this->checked_out != JFactory::getUser()->id && $this->checked_out != 0)
			{
				$this->locked = true;
			}
		}
	
		return $this->locked;
	}

	/**
	 * Assign a new handler to the ticket
	 * 
	 * handler_id = the FSS User ID of the ticket handler
	 * 
	 * Type:
	 * 
	 * define("TICKET_ASSIGN_FORWARD", 0);
	 * define("TICKET_ASSIGN_TOOK_OWNER", 1);
	 * define("TICKET_ASSIGN_UNASSIGNED", 2);
	 * define("TICKET_ASSIGN_ASSIGNED", 3);
	 * 
	 * if handler_id is 0, then type gets set to 2 and ticket becomes unassigned 
	 **/
	function assignHandler($handler_id, $type = TICKET_ASSIGN_FORWARD)
	{
		if ($handler_id == $this->admin_id)
			return true;
		
		$qry = "UPDATE #__fss_ticket_ticket SET admin_id = {$handler_id} WHERE id = {$this->id}";
		$db = JFactory::getDBO();
		$db->setQuery($qry);
		$db->Query();	
	
		if ($handler_id == 0)
		{
			$type = TICKET_ASSIGN_UNASSIGNED;	
		}
		
		// update last_update
		$this->updateLastUpdated();
		
		// add audit note
		if ($type == TICKET_ASSIGN_FORWARD)
		{
			$this->addAuditNote("Forwarded to handler '" . SupportUsers::getUserName($handler_id) . "'");
		} else if ($type == TICKET_ASSIGN_TOOK_OWNER)
		{
			$this->addAuditNote("Handler '" . SupportUsers::getUserName($handler_id) . "' took ownership of the ticket");
		} else if ($type == TICKET_ASSIGN_UNASSIGNED)
		{
			$this->addAuditNote("Ticket set as unassigned");
		} else if ($type == TICKET_ASSIGN_ASSIGNED)
		{
			$this->addAuditNote("Ticket assigned to '" . SupportUsers::getUserName($handler_id) . "'");
		}
		
		// change this object
		$this->admin_id = $handler_id;
		
		SupportActions::DoAction_Ticket("assignHandler", $this, array('handler' => $handler_id, 'type' => $type));
	}
	
	/**
	 * Adds an audit note to this ticket
	 **/
	function addAuditNote($note)
	{
		if (!$this->audit_changes)
			return;
		
		$db = JFactory::getDBO();
		$now = FSS_Helper::CurDate();
		
		if ($this->is_batch)
			$note = "Batch: " . $note;
		
		$qry = "INSERT INTO #__fss_ticket_messages (ticket_ticket_id, subject, body, user_id, admin, posted) VALUES ('";
		$qry .= FSSJ3Helper::getEscaped($db, $this->id)."','Audit Message','".FSSJ3Helper::getEscaped($db, $note)."','".FSSJ3Helper::getEscaped($db, $this->current_user)."',3, '{$now}')";
			
  		$db->SetQuery( $qry );
		$db->Query();
	}
	
	// CANNOT BE UNDONE! USE WITH CAUTION!
	function delete()
	{
		if (FSS_Settings::get('support_delete'))
		{
			$this->loadAttachments();
			
			foreach ($this->attach as $attach)
			{
				$image_file = JPATH_SITE.DS."components/com_fss/files/support/" . $attach->diskfile;
				$thumb_file = JPATH_SITE.DS."components/com_fss/files/support/" . $attach->diskfile . ".thumb";
				
				if (file_exists($image_file))
				@unlink($image_file);
				
				if (file_exists($thumb_file))
				@unlink($thumb_file);
			}
			
			$db = JFactory::getDBO();
			
			$qry = "DELETE FROM #__fss_ticket_messages WHERE ticket_ticket_id = {$this->id}";
			$db->setQuery($qry);
			$db->query();
			
			$qry = "DELETE FROM #__fss_ticket_attach WHERE ticket_ticket_id = {$this->id}";
			$db->setQuery($qry);
			$db->query();
			
			$qry = "DELETE FROM #__fss_ticket_cc WHERE ticket_id = {$this->id}";
			$db->setQuery($qry);
			$db->query();
			
			$qry = "DELETE FROM #__fss_ticket_field WHERE ticket_id = {$this->id}";
			$db->setQuery($qry);
			$db->query();
			
			$qry = "DELETE FROM #__fss_ticket_tags WHERE ticket_id = {$this->id}";
			$db->setQuery($qry);
			$db->query();
			
			$qry = "DELETE FROM #__fss_ticket_ticket WHERE id = {$this->id}";
			$db->setQuery($qry);
			$db->query();
		}
	}

	function stripImagesFromMessage($message_id)
	{
		$db = JFactory::getDBO();

		$qry = "SELECT * FROM #__fss_ticket_messages WHERE id = " . $db->escape($message_id);
		$db->setQuery($qry);
		$message = $db->loadObject();

		$body = $message->body;
		$count = 0;

		while (strpos($body, "]data:image") !== false)
		{		
			$start = strpos($body, "]data:image");
			$end = strpos($body, "[/img]", $start);

			if ($end < 1)
				break;

			$count++;

			$content = substr($body, $start+5, ($end-$start)-5);

			list ($type, $rest) = explode(";", $content, 2);
			list ($encoding, $data) = explode(",", $rest, 2);

			$image_data = base64_decode($data);
			list ($junk, $extension) = explode("/", $type, 2);

			$filename = "message-$message_id-inline-image-$count." . $extension;

			$destpath = JPATH_SITE.DS.FSS_Helper::getAttachLocation().DS.'support'.DS;					
			$destname = FSS_File_Helper::makeAttachFilename("support", $filename, date("Y-m-d", strtotime($message->posted)), $this, $message->user_id);

			if (file_put_contents($destpath.$destname, $image_data))
			{
				$size = filesize($destpath.$destname);

				$qry = "INSERT INTO #__fss_ticket_attach (ticket_ticket_id, filename, diskfile, size, user_id, added, message_id, inline) VALUES ('";
				$qry .= FSSJ3Helper::getEscaped($db, $this->id) . "',";
				$qry .= "'" . FSSJ3Helper::getEscaped($db, $filename) . "',";
				$qry .= "'" . FSSJ3Helper::getEscaped($db, $destname) . "',";
				$qry .= "'" . $size . "',";
				$qry .= "'" . FSSJ3Helper::getEscaped($db, $message->user_id) . "',";
				$qry .= "'".$message->posted."', ".$message->id.", 1)";

				$db->setQuery($qry);$db->Query();  
				
				$attach_id = $db->insertid();  
			}
			$key = FSS_Helper::base64url_encode(FSS_Helper::encrypt($attach_id, FSS_Helper::getEncKey("file")));
			$replace = "]" . JURI::base() . "index.php?option=com_fss&view=image&fileid={$attach_id}&key={$key}" . "[/img]";
			$replace = str_replace("/administrator", "", $replace);
			$body = substr($body, 0, $start) . $replace . substr($body, $end+6);
		}

		if ($count > 0)
		{
			$qry = "UPDATE #__fss_ticket_messages SET body = \"" . $db->escape($body) . "\" WHERE id = " . $db->escape($message_id);
			$db->setQuery($qry);
			$db->Query();
		}
	}
		
	static $rowclass = "odd";
	function forParser(&$vars, $foruser, $display = true, $template = "")
	{
		
		// if $handler is set, then include elements specific to the tickets handler
		
		// if $display is set, then include elements only needed when outputting the ticket in a list
		
		$vars = array();

		// id
		$vars['ticket_id'] = $this->id;
		
		// reference
		$vars['ref'] = $this->reference;
		$vars['reference'] = $this->reference;
		
		$vars['admin_id'] = $this->admin_id;
		$vars['user_id'] = $this->user_id;
		
		// subject
		$title = $this->getTitle();
		$vars['subject_text'] = $title;
		$vars['subject'] = $title;
		
		if ($display)
		{
			if ($foruser)
			{
				$vars['subject'] = "<a href='".FSSRoute::_( 'index.php?option=com_fss&view=ticket&layout=view&ticketid=' . $this->id )."'>" . $title . "</a>";
			} else {
				$vars['subject'] = "<input type='checkbox' class='ticket_cb' style='display: none' id='ticket_cb_{$this->id}' name='ticket_{$this->id}'><a href='".FSSRoute::_( 'index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . $this->id )."'>" . $title . "</a>";				
				$vars['checkbox'] = "<input type='checkbox' class='ticket_cb' style='display: none' id='ticket_cb_{$this->id}' name='ticket_{$this->id}'>";
			}	
		}
		
		$uri = JURI::getInstance();	
		$baseUrl = FSS_Settings::get('support_email_no_domain') ? "" : $uri->toString( array('scheme', 'host', 'port'));

		// choose which user link to generate
		if ($this->user_id < 1)
		{
			// unregistered user
			$url = 'index.php?option=com_fss&t=' . $this->id . "&p=" . $this->password;
			if (FSS_Settings::get('support_email_link_unreg') > 0) $url .= "&Itemid=" . FSS_Settings::get('support_email_link_unreg'); // add fixed item id if needed
			$vars['ticket_link'] = $baseUrl . JRoute::_($url, false);
		} else {
			// registered user
			$url = 'index.php?option=com_fss&view=ticket&layout=view&ticketid=' . $this->id;

			if (FSS_Settings::get('support_email_include_autologin')) $url .= "&login={login_code}";
			if (FSS_Settings::get('support_email_link_reg') > 0) $url .= "&Itemid=" . FSS_Settings::get('support_email_link_reg'); // add fixed item id if needed
			$vars['ticket_link'] = $baseUrl . JRoute::_($url, false);
		}
		
		// ticket admin link
		$url = 'index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . $this->id;
		if (FSS_Settings::get('support_email_link_admin') > 0) $url .= "&Itemid=" . FSS_Settings::get('support_email_link_admin'); // add fixed item id if needed
		if (FSS_Settings::get('support_email_include_autologin_handler')) $url .= "&login={login_code}";
		$vars['admin_link'] = $baseUrl . JRoute::_($url, false);

		// email pending link
		$url = 'index.php?option=com_fss&view=admin_support&layout=emails';
		if (FSS_Settings::get('support_email_link_pending') > 0) $url .= "&Itemid=" . FSS_Settings::get('support_email_link_pending'); // add fixed item id if needed
		$vars['email_pending_link'] = $baseUrl . JRoute::_($url, false);

		// status
		$vars["status"] = $this->status;
		if ($display) $vars["status"] = "<span style='color:" . $this->color . ";'>" . $this->status . "</span>";	
		$vars["status_text"] = $this->status;
		$vars["status_color"] = $this->color;
		
		// priority
		$vars["priority"] = $this->priority;
		if ($display) $vars["priority"] = "<span style='color:" . $this->pricolor . ";'>" . $this->priority . "</span>";	
		$vars["priority_text"] = $this->priority;
		$vars["priority_color"] = $this->pricolor;
			
		// product
		$vars["product"] = $this->product;
		$vars["product_desc"] = strip_tags($this->proddesc);
		$vars["product_desc_html"] = $this->proddesc;
		// TODO - Add image here
		
		// department
		$vars["department"] = $this->department;
		$vars["department_desc"] = strip_tags($this->deptdesc);
		$vars["department_desc_html"] = $this->deptdesc;
		// TODO - Add image here
		
		// category
		$vars["category"] = $this->category;

		// dates
		$vars["lastactivity"] = FSS_Helper::TicketTime($this->lastupdate,FSS_DATETIME_SHORT);
		$vars["opened"] = FSS_Helper::TicketTime($this->opened,FSS_DATETIME_SHORT);
		$vars["closed"] = FSS_Helper::TicketTime($this->closed,FSS_DATETIME_SHORT);
		
		// time tracking - only if in template
		if (!$template || strpos($template, "{time_taken}") !== false)
		{
			if (FSS_Settings::get('time_tracking') != "")
			{
				if ($this->timetaken > 0)
				{
					$hours = floor($this->timetaken / 60);
					$mins = sprintf("%02d",$this->timetaken % 60);
					$vars["time_taken"] = "<i class='icon-clock'></i>".JText::sprintf("TIME_TAKEN_DISP", $hours, $mins);
				} else {
					$vars["time_taken"] = "<i class='icon-clock'></i>".JText::sprintf("TIME_TAKEN_DISP", "0", "00");
				}
			} else {
				$vars["time_taken"] = "";
			}
			
			if (!$display) $vars["time_taken"] = strip_tags($vars["time_taken"]);
		}
		
		// website title
		$vars["websitetitle"] = FSS_Helper::getSiteName();
		
		// password
		$vars["password"] = $this->password;
		$vars['haspassword'] = in_array(FSS_Settings::get('support_unreg_type'), array(0,1)) ? 1 : 0; // if the system is using passwords for tickets or not

		if ($this->prod_img) $vars['product_img'] = JURI::root( true ) . "/images/fss/products/" . $this->prod_img;
		if ($this->dept_img) $vars['department_img'] = JURI::root( true ) . "/images/fss/departments/" . $this->dept_img;

		// ticket user
		if ($this->user_id > 0)
		{
			$vars['user_name'] = $this->name;
			$vars['name'] = $this->name;
			$vars['user_username'] = $this->username;
			$vars['user_email'] = $this->useremail;
		} else {
			$vars['user_name'] = $this->unregname;
			$vars['name'] = $this->unregname . " (" . JText::_("UNREG") . ")";
			$vars['user_username'] = JText::_("UNREGISTERED");
			$vars['user_email'] = $this->email;
		}
		$vars['username'] = $vars['user_username'];
		$vars['email'] = $vars['user_email'];
		
		// ticket handler
		if ($this->admin_id > 0)
		{
			$vars['handler_name'] = $this->assigned;
			$vars['handler_username'] = $this->handlerusername;
			$vars['handler_email'] = $this->handleremail;
		} else {
			$vars['handler_name'] = JText::_("UNASSIGNED");
			$vars['handler_username'] = '';
			$vars['handler_email'] = '';
		}
		$vars['handlername'] = $vars['handler_name'];
		$vars['handlerusername'] = $vars['handler_username'];
		$vars['handleremail'] = $vars['handler_email'];
		
		// last post
		if (!$template ||
			strpos($template, "last_poster") !== false || 
			strpos($template, "last_poster_username") !== false)
		{
			$vars['last_poster'] = '';
			$vars['last_poster_username'] = '';

			$db = JFactory::getDBO();
			$qry = "SELECT user_id, posted FROM #__fss_ticket_messages WHERE ticket_ticket_id = " . $db->escape($this->id) . " AND admin IN (0, 1) ORDER BY posted DESC LIMIT 1";
			$db->setQuery($qry);
			$rows = $db->loadObjectList();
			
			if ($rows)
			{
				$row = reset($rows);
				
				if ($row)
				{
					$user_id = $row->user_id;
					try {
						$user = JFactory::getUser($user_id);
						$vars['last_poster'] = $user->name;
						$vars['last_poster_username'] = $user->username;
					} catch (exception $e) {}
				}
			}
		}
		
		// users cc'd
		if (!$template || strpos($template, "user_names") !== false)
		{
			$this->loadCC();
			
			$names = array();

			if ($this->user_id == 0)
			{
				$names[] = $this->unregname;
			} else {
				$names[] = $this->name;
			}

			foreach ($this->user_cc as $user)
			{
				$names[] = $user->name;
			}
			
			$vars['user_names'] = implode(", ", $names);
		}
		
		// message history tag
		if (!$template || strpos($template, "messagehistory") !== false)
		{
			$this->loadMessages();
			
			$vars['messagehistory'] = $this->parseMessageHistory($display, $foruser);
			
			// TODO PARSE MESSAGE ROWS WITH CORRECT TEMPLATE
		}
		
		// users groups
		if (!$template || strpos($template, "groups") !== false)
		{
			$this->loadGroups();
			
			$group_names = array();
			if (isset($this->groups)) foreach ($this->groups as $group) $group_names[] = $group->groupname;
			$vars["groups"] = implode(", ", $group_names);
		}
		
		// ticket lock time
		$vars['lock'] = "";
		if (!$foruser && $display)
		{
			$cotime = FSS_Helper::GetDBTime() - strtotime($this->checked_out_time);
			if ($cotime < FSS_Settings::get('support_lock_time') && $this->checked_out != JFactory::getUser()->id && $this->checked_out > 0)
			{
				$html = "<div>" . $this->co_user->name . " (" .  $this->co_user->email . ")</div>";
				$vars['lock'] = "<img class='fssTip' title='<b>" . JText::_("TICKET_LOCKED") . "</b><br />".htmlentities($html,ENT_QUOTES,"utf-8")."' src='". JURI::root( true ) . "/components/com_fss/assets/images/lock.png'>";
			}
		}
		
		// ticket tags
		$vars['tags'] = "";		
		if (!$template || strpos($template, "tags") !== false)
		{
			if (!FSS_Settings::get('support_hide_tags'))
			{
				$this->loadTags();
				if (count($this->tags) > 0)
				{
					if ($display)
					{
						$html = "<div>" . implode("</div><div>", $this->tags) . "</div>";
						$vars['tags'] = "<img class='fssTip' title='".htmlentities($html,ENT_QUOTES,"utf-8")."' src='". JURI::root( true ) . "/components/com_fss/assets/images/tag.png'>";
					} else {
						$vars['tags'] = implode(", ", $this->tags);
					}
				}
			}
		}	
			
		// attachments
		$vars['attach'] = "";
		if (!$template || strpos($template, "attach") !== false)
		{
			$this->loadAttachments();
			$vars['attach_cnt'] = count($this->attach);
			if ($vars['attach_cnt'] > 0)
			{
				if ($display)
				{
					$html = "";
					foreach($this->attach as $attach)
					{
						$html .= "<div>" . $attach->filename ." (" . FSS_Helper::display_filesize($attach->size) . ")</div>";
					}
						
					$vars['attach'] = "<img class='fssTip' title='".htmlentities($html,ENT_QUOTES,"utf-8")."' src='". JURI::root( true ) . "/components/com_fss/assets/images/attach.png'>";
				} else {
					$vars['attach'] = $vars['attach_cnt'];
				}
			}
		}
		
		// message counts
		if (FSS_Settings::get('support_show_msg_counts') || $foruser)
		{
			if (!$template || strpos($template, "msgcount") !== false || strpos($template, "msgcnt") !== false)
			{
				if ($this->msgcount['total'] == 0) $this->loadMessages();
				
				$vars["msgcount_total"] = $this->msgcount['total'];
				$vars["msgcount_user"] = $this->msgcount['0'];
				$vars["msgcount_handler"] = $this->msgcount['1'];
				$vars["msgcount_private"] = $this->msgcount['2'];
				$vars["msgcount_draft"] = $this->msgcount['4'];
				
				if ($foruser) $this->msgcount['total'] = $this->msgcount['0'] + $this->msgcount['1'];

				if ($display)
				{

					$tip = "<strong>".$this->msgcount['total'] . " " . JText::_('MESSAGES') . ":</strong><br>";
					if ($this->msgcount['0'] > 0) $tip .= $this->msgcount['0'] . " " . JText::_('USER') . "<br>";
					if ($this->msgcount['1'] > 0) $tip .= $this->msgcount['1'] . " " . JText::_('HANDLER') . "<br>";
					if (!$foruser)
					{
						if ($this->msgcount['2'] > 0) $tip .= $this->msgcount['2'] . " " . JText::_('PRIVATE') . "<br>";
						if ($this->msgcount['4'] > 0) $tip .= $this->msgcount['4'] . " " . JText::_('DRAFT') . "<br>";
					}
					
					$vars['msgcnt'] = "<span class='fssTip label label-default' title='".htmlentities($tip,ENT_QUOTES,"utf-8")."'>".$this->msgcount['total']."</span>";
				}
			}
		}
		
		// rating
		if ($this->rating > 0)
		{
			$vars['rating_html'] = SupportHelper::displayRating($this->rating);	
			$vars['rating_raw'] = $this->rating;
		} else {
			$vars['rating_html'] = '';	
			$vars['rating_raw'] = '';
		}
		
		// source
		$vars['source'] = $this->source;
		
		// delete and archive buttons
		if ($display && !$foruser)
		{
			$delete = "<a class='pull-right btn btn-default btn-mini' href='" . FSSRoute::_( 'index.php?option=com_fss&view=admin_support&task=archive.delete&tickets='.FSS_Input::getCmd('tickets').'&ticketid=' . $this->id) . "'>";
			$delete .= JText::_("DELETE") . "</a>";
			$vars['deletebutton'] = $delete;

			$archive = "<a class='pull-right btn btn-default btn-mini' href='" . FSSRoute::_( 'index.php?option=com_fss&view=admin_support&task=archive.archive&tickets='.FSS_Input::getCmd('tickets').'&ticketid=' . $this->id) . "'>";
			$archive .= JText::_("ARCHIVE") . "</a>";		
			$vars['archivebutton'] = $archive;
		}
		
		// handler tag
		if ($display)
		{
			$handler_highlight = '<span class="fssTip label label-warning pull-right" title="'. JText::_('UNASSIGNED_TICKET') . '">' . $vars['handlername'] . '</span>';
			
			if ($this->admin_id == JFactory::getUser()->id)
			$handler_highlight = '<span class="fssTip label label-success pull-right" title="'. JText::_('MY_TICKET') . '">' . $vars['handlername'] . '</span>';
			else if ($this->admin_id > 0)
				$handler_highlight = '<span class="fssTip label label-info pull-right" title="' . JText::_('OTHER_HANDLERS_TICKET'). '">' . $vars['handlername'] . '</span>';
			
			$vars['handler_tag'] = $handler_highlight;
		}
		
		// display styling for admin
		if ($display && !$foruser)
		{
			$style = "";
			//$trhl = " onmouseover='highlightticket({$ticket->id})' onmouseout='unhighlightticket({$ticket->id})' ";
			$trhl = " "; // no longer highlighting tickets!
			
			$priority = $this->getPriority();
			if ($priority->backcolor) $style .= "background-color: {$priority->backcolor};"; 	

			if (FSS_Settings::get('support_entire_row'))
			{
				$style .= "cursor: pointer;";
				$trhl .= " onclick='window.location=\"".FSSRoute::_( 'index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . $this->id )."\"' ";
			}
			
			$trhl .= " style='$style' ";
			
			$vars['trhl'] = $trhl;
			
			$vars['class'] = static::$rowclass . " ticket_{$this->id}";

			if (static::$rowclass == "odd")
			{
				static::$rowclass = "even";
			} else {
				static::$rowclass = "odd";
			}
		}
		
		// random display stuff 
		if ($display)
		{
			$vars['hidehandler'] = (FSS_Settings::get('support_hide_handler') == 1);
			$vars["candelete"] = FSS_Settings::get('support_delete');
		}
		
		// custom fields
		
		if (empty($this->custom) && !empty($this->fields)) $this->custom = $this->fields;
		
		if (isset($this->customfields))
		{
			foreach($this->customfields as $field)
			{
				if ($field['type'] != "plugin") continue;
				$aparams = FSSCF::GetValues($field);
				$plugin = FSSCF::get_plugin($aparams['plugin']);
				$id = $field['id'];

				$value = "";
				if (isset($this->custom) && array_key_exists($field['id'], $this->custom))
				$value = $this->custom[$field['id']];

				if (is_array($value))
				$value = $value['value'];

				$text = $plugin->Display($value, $aparams['plugindata'], array('ticketid' => $this->id, 'userid' => $this->user_id, 'ticket' => $this, 'inlist' => 1), $field['id']);
				$vars["custom".$id] = $text;	
				$vars["custom_".$id] = $text;	
				
				$vars["custom_".$field['alias']] = $text;	
				$vars["custom_".$id."_name"] = $field['description'];	
			}
		}
		
		if (isset($this->custom))
		{
			$allcustom = array();
			if (count($this->custom) > 0)
			{
				foreach	($this->custom as $id => $value)
				{
					if (is_array($value)) $value = $value['value'];
					
					foreach ($this->customfields as $field)
					{
						if ($field['id'] != $id) continue;
						
						if ($field['type'] == "plugin")
						{
							$aparams = FSSCF::GetValues($field);
							if (array_key_exists("plugin", $aparams) && array_key_exists("plugindata", $aparams))
							{	
								$plugin = FSSCF::get_plugin($aparams['plugin']);
								$value = $plugin->Display($value, $aparams['plugindata'], array('ticketid' => $this->id, 'userid' => $this->user_id, 'ticket' => $this, 'inlist' => 1), $field['id']);
							}
						}
						
						$prefix = "<span class='cust_field_label cust_field_label_".$field['alias']." cust_field_label_$id'>" . $field['description'] . ":</span> ";
						if (isset($this->customfields[$id]) && $this->customfields[$id]['type'] == "checkbox")
						{
							if ($value == "on")
							$text = JText::_("Yes");
							else
								$text = JText::_("No");
						} else {
							$text = $value;
						}

						
						$vars["custom".$id] = $text;	
						$vars["custom_".$id] = $text;	
						$vars["custom_".$field['alias']] = $text;	
						
						foreach ($this->customfields as $customfield)
						{
							if ($customfield['id'] == $id) $vars["custom_".$id."_name"] = $customfield['description'];	
						}
						
						if ($field['inlist'])
						{
							$allcustom[] = $prefix."<span class='cust_field_value cust_field_value_".$field['alias']." cust_field_value_$id'>".$text."</span>";
						}
					}
				}
			}
			$vars["custom"] = implode(", ",$allcustom);
		}	

		if ($vars['subject'] == "") $vars['subject'] = $vars['subject_text'];			
	}
	
	function translate($lang = "")
	{
		$old_lang = FSS_Translate_Helper::setLanguage($lang);
		
		if (empty($this->lang_backup))
		{
			$this->lang_backup = array();	
			$this->lang_backup['product'] = $this->product;
			$this->lang_backup['proddesc'] = $this->proddesc;
			$this->lang_backup['department'] = $this->department;
			$this->lang_backup['deptdesc'] = $this->deptdesc;
			$this->lang_backup['category'] = $this->category;
			$this->lang_backup['priority'] = $this->priority;
			$this->lang_backup['status'] = $this->status;
			$this->lang_backup['color'] = $this->color;
		} else {
			// restore prev backup
			foreach ($this->lang_backup as $field => $value)
				$this->$field = $value;	
		}
		
		$another = 'cdcada4c7baacb4767891467d63699cb';
		
		$this->product = FSS_Translate_Helper::TrF('title', $this->product, $this->prtr);
		$this->proddesc = FSS_Translate_Helper::TrF('description', $this->proddesc, $this->prtr);
		
		$this->department = FSS_Translate_Helper::TrF('title', $this->department, $this->dtr);
		$this->deptdesc = FSS_Translate_Helper::TrF('description', $this->deptdesc, $this->dtr);
		
		$this->category = FSS_Translate_Helper::TrF('title', $this->category, $this->ctr);
		$this->priority = FSS_Translate_Helper::TrF('title', $this->priority, $this->pritr);
		
		$this->status = FSS_Translate_Helper::TrF('title', $this->status, $this->str);
		
		
		// sort user status
		if ($this->for_user)
		{
			$all_status = SupportHelper::getStatussUser();
			$current_status = $all_status[$this->ticket_status_id];
			if ($current_status->combine_with > 0) $current_status = $all_status[$current_status->combine_with];
			
			$this->status = $current_status->title;
			$this->color = $current_status->color;
		}
		
		FSS_Translate_Helper::setLanguage($old_lang);
	}
	
	function parseMessageHistory($ishtml, $foruser = false)
	{
		$parser = new FSSParser();
		$parser->loadEmail('messagerow');

		$result = "";

		$marray = array();

		foreach ($this->messages as &$message)
			$marray[] = $message;

		if ($this->loaded_messages_order) // remove last
		{
			array_pop($marray);
		} else { // remove first
			array_shift($marray);
		}
		
		foreach ($marray as &$message)
		{
			if ($message->admin > 1) continue;
			$parser->clear();

			$vars = array();
			//print_p($message);
			if ($message->name)
			{
				$parser->setVar('name',$message->name);
				$parser->setVar('email',$message->email);
				$parser->setVar('username',$message->username);
			} else {
				$parser->setVar('name','Unknown');
				$parser->setVar('email','Unknown');
				$parser->setVar('username','Unknown');
			}
			$parser->setVar('subject',$message->subject);
			$parser->setVar('posted',FSS_Helper::Date($message->posted));
			
			$body = FSS_Helper::ParseBBCode($message->body,null,false,false,$foruser);

			if ($ishtml)
			{
				$body = str_replace("\n","<br>\n",$body);	
				$parser->setVar('body',$body . "<br />");	
			} else {
				$parser->setVar('body',$body . "\n");	
			}
			
			$result .= $parser->getBody();;
		}
		
		return $result;
	}	
	static function idFromMessage($messageid)
	{
		$db = JFactory::getDBO();
		$sql = "SELECT ticket_ticket_id FROM #__fss_ticket_messages WHERE id = '" . $db->escape($messageid) . "'";
		$db->setQuery($sql);
		$result = $db->loadObject();
		if ($result) return $result->ticket_ticket_id;
		return 0;		
	}
	
	function rateMessage($messageid, $rating)
	{
		$db = JFactory::GetDBO();
		$sql = "UPDATE #__fss_ticket_messages SET rating = '" . $db->escape($rating) . "' WHERE id = '" . $db->escape($messageid) . "'";
		$db->setQuery($sql);
		$db->query();
		
		$this->updateLastUpdated();
		$this->addAuditNote("Message '$messageid' rated as $rating");
		SupportActions::DoAction_Ticket("messageRated", $this, array('message' => $messageid, 'rating' => $rating));
	}
	
	function rate($rating)
	{
		$db = JFactory::GetDBO();
		$sql = "UPDATE #__fss_ticket_ticket SET rating = '" . $db->escape($rating) . "' WHERE id = '" . $db->escape($this->id) . "'";
		$db->setQuery($sql);
		$db->query();
		
		$this->rating = $rating;
		
		$this->updateLastUpdated();
		$this->addAuditNote("Ticket rated as $rating");
		SupportActions::DoAction_Ticket("ticketRated", $this, array('rating' => $rating));
	}
	
	function isClosed()
	{
		return (bool)$this->is_closed;
	}
	
	static function toObject($in, $force_reload = false)
	{
		if (is_object($in) && !$force_reload) return $in;
		
		if (is_array($in))
		{
			$t = new SupportTicket();
			$t->load($in['id'], "force");
			return $t;
		}
		
		if (is_int($in) || is_numeric($in))
		{
			$t = new SupportTicket();
			$t->load($in, "force");
			return $t;
		}
		
		if (is_object($in))
		{
			$t = new SupportTicket();
			$t->load($in->id, "force");
			return $t;	
		}
		
		return null;
	}
}
