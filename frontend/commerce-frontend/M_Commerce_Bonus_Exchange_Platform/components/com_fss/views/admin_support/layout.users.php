<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'pagination.php');

class FssViewAdmin_Support_Users extends FssViewAdmin_Support
{
	function display($tpl = NULL)
	{
		parent::init();	
		
		$this->plugins = $this->load_plugins();

		$this->mode = FSS_Input::getcmd('mode', 'pick');
		$this->usergroup = FSS_Input::getInt('usergroup');
		
		foreach ($this->plugins as $plugin)
			$plugin->mode = $this->mode;

		$limitstart = FSS_Input::getInt('limitstart');
		$mainframe = JFactory::getApplication();
		$limit = $mainframe->getUserStateFromRequest('users.limit', 'limit', 10, 'int');
		$search = FSS_Input::getString('search');
		
		
		$this->lists = array();
		$this->lists['order_Dir'] = FSS_Input::getCmd('filter_order_Dir');
		$this->lists['order'] = FSS_Input::getCmd('filter_order');
		
		$ticket_has_groups = false;
		// load in ticket if there is one
		$this->ticketid = FSS_Input::getInt("ticketid");
		if ($this->mode == "user" || $this->mode == "admin")
		{
			$this->ticket = new SupportTicket();
			$this->ticket->load($this->ticketid);
			$this->ticket->loadCC();
			$this->ticket->loadGroups();
			
			if (count($this->ticket->groups) > 0)
				$ticket_has_groups = true;
		}
		
		// ticket group, default to t if we are in user or admin mode
		$this->ticketgroup = null;
		if ($ticket_has_groups && ($this->mode == "user"))
			$this->ticketgroup = "t";
		$this->ticketgroup = FSS_Input::getcmd('ticketgroup', $this->ticketgroup);
	

		$db	= JFactory::getDBO();
		$qry = "SELECT * FROM #__users ";
		$where = array();
				
		if ($search != "")
		{
			$search_parts = array();
			$search_parts[] = "username LIKE '%".$db->escape($search)."%'";
			$search_parts[] = "name LIKE '%".$db->escape($search)."%'";
			$search_parts[] = "email LIKE '%".$db->escape($search)."%'";
			
			$this->searchFields($search_parts);
			
			foreach ($this->plugins as $plugin)
			{
				$ids = $plugin->search($search);
				if (count($ids) > 0)
				{
					$search_parts[] = "id IN ('" . implode("', '", $ids) . "')";
				}
			}

			$where[] = "( " . implode(" OR ", $search_parts) . " )";
		}
		
		// filter by usergroup
		if ($this->usergroup > 0)
		{
			$where[] = "id IN (SELECT user_id FROM #__user_usergroup_map WHERE group_id = " . $db->escape($this->usergroup) . ")";
		}


		// filter by ticket group
		if ($this->ticketgroup == "t")
		{
			$group_ids = array();
			$group_ids[] = 0;
			foreach ($this->ticket->groups as $group)
				$group_ids[] = $group->id;
			
			$where[] = "id IN (SELECT user_id FROM #__fss_ticket_group_members WHERE group_id IN (" . implode(", ", $group_ids) . "))";
		} elseif ($this->ticketgroup > 0)
		{
			$where[] = "id IN (SELECT user_id FROM #__fss_ticket_group_members WHERE group_id = " . $db->escape($this->ticketgroup) . ")";
		}

		if ($this->mode == "admin")
		{
			$handlers = SupportUsers::getHandlers(false, false);
			$ids = array();
			$ids[] = 0;
			foreach ($handlers as $handler)
				$ids[] = $handler->id;
			
			$where[] = "id IN (" . implode(", ", $ids) . ")";
		}

		// add where
		if (count($where) > 0)
		{
			$qry .= " WHERE " . implode(" AND ", $where);	
		}

		
		$order = FSS_Input::getCmd('filter_order');
		$dir = FSS_Input::getCmd('filter_order_Dir', 'asc');
		if ($order == "username" || $order == "name" || $order == "email")
		{
			// Sort ordering
			$qry .= " ORDER BY $order $dir ";
		} else {
			$qry .= " ORDER BY name ";
		}
		
		//echo $qry . "<br>";
		// get max items
		
		$db->setQuery( $qry );
		$db->query();
		$maxitems = $db->getNumRows();
			
		// select picked items
		$db->setQuery( $qry, $limitstart, $limit );
		$this->users = $db->loadObjectList();

		//print_p(reset($this->users));
		
		
		// build pagination
		$this->pagination = new JPaginationEx($maxitems, $limitstart, $limit );
		$this->search = $search;
		
		if ($this->mode != "admin")
		{
			// load in joomla user groups
			$qry = "SELECT * FROM #__usergroups ORDER BY lft";
			$db->setQuery($qry);
			
			$this->groups = $db->loadObjectList();
			
			$group_index = array();
			
			foreach ($this->groups as &$group)
			{
				$group_index[$group->id] = &$group;
				
				if ($group->parent_id == 0)
				{
					$group->level = 0;	
				} else {
					$group->level = $group_index[$group->parent_id]->level + 1;
				}
				
				$group->display = str_repeat("- ", $group->level) . $group->title;
			}
			
			array_unshift($this->groups, JHTML::_('select.option', '', JText::_("JOOMLA_USERGROUP"), 'id', 'display'));
			$this->jgroup_select = JHTML::_('select.genericlist',  $this->groups, 'usergroup', 'class="inputbox" size="1" onchange="document.fssForm.submit( );"', 'id', 'display', $this->usergroup);
			
			
			// load ticket groups
			$qry = "SELECT * FROM #__fss_ticket_group ORDER BY groupname";
			$db->setQuery($qry);
			$this->ticketgroups = $db->loadObjectList();
			if ($this->ticketid > 0 && $ticket_has_groups)
				array_unshift($this->ticketgroups, JHTML::_('select.option', 't', JText::_("CURRENT_TICKET"), 'id', 'groupname'));
			array_unshift($this->ticketgroups, JHTML::_('select.option', '', JText::_("ALL_TICKET_GROUPS"), 'id', 'groupname'));
			$this->ticketgroup_select = JHTML::_('select.genericlist',  $this->ticketgroups, 'ticketgroup', 'class="inputbox" size="1" onchange="document.fssForm.submit( );"', 'id', 'groupname', $this->ticketgroup);
		}
		
		
		$this->_display();
	}
	
	private function load_plugins()
	{

		if (empty($this->plugins))
		{
			$this->plugins = array();

			$path = JPATH_SITE.DS."components".DS."com_fss".DS."plugins".DS."userlist".DS;
			$files = JFolder::files($path, ".php$");
		
			foreach ($files as $file)
			{
				$id = pathinfo($file, PATHINFO_FILENAME);

				if (!FSS_Helper::IsPluignEnabled("userlist", $id))
					continue;

				$class = "User_List_" . $id;
				require_once($path . DS . $file);
				if (class_exists($class))
				{
					$this->plugins[$id] = new $class();	
					$this->plugins[$id]->id = $id;
				}
			}
		}
		
		return $this->plugins;
	}

	private function searchFields(&$wherebits)
	{
		// search custom fields that are set to be searched
		$fields = FSSCF::GetAllCustomFields(true);
		
		foreach ($fields as $field)
		{			
			if (!$field['basicsearch'])
				continue;
			
			if (!$field['peruser'])
				continue;
			
			$fieldid = $field['id'];

			$search = FSS_Input::getString('search');

			if ($field['type'] == "checkbox")
			{
				if ($search == "1")
				{
					$search = "on";
				} else {
					$search = "";
				}
			}
			
			if ($field['type'] == "plugin")
			{
				// try to do a plugin based search
				$data = array();
				foreach ($field['values'] as $item)
				{
					list($key, $value) = explode("=", $item, 2);
					$data[$key] = $value;	
				}
				if (array_key_exists("plugin", $data))
				{
					$plugins = FSSCF::get_plugins();
					if (array_key_exists($data['plugin'], $plugins))
					{
						$po = $plugins[$data['plugin']];	

						if (method_exists($po, "Search"))
						{
							$res = $po->Search($data['plugindata'], $search, true);
							
							if ($res !== false)
							{
								$wherebits[] = $this->IDsToWhere($res, "id", "user_id"). " /* Per User Plugin - " . $field['id'] . " */";
								continue;
							}
						}
					}
				}
			}

			{
				
				$qry = "SELECT user_id FROM #__fss_ticket_user_field WHERE field_id = '" . FSSJ3Helper::getEscaped($db, $fieldid) . "' AND value LIKE '%" . FSSJ3Helper::getEscaped($db, $search) . "%'";	
				$db->setQuery($qry);
				$res = $db->loadObjectList();
				$wherebits[] = $this->IDsToWhere($res, "id", "user_id"). " /* Per User CF - " . $field['id'] . " */";
			}
		}	
	}
	
	private function IDsToWhere($ticketids, $target, $field)
	{
		if (!$ticketids)
		return "0";
		
		$tids = array();
		if (count($ticketids) < 1)
		return "0";
		
		foreach ($ticketids as $ticketid)
		{
			$id = $ticketid->$field;
			if ($id > 0) $tids[] = $id;
		}
		
		if (count($tids) > 0)
		return "$target IN (".implode(",",$tids).")";

		return "0";
	}
}

class User_List_Column 
{
	var $mode = '';

	function getHeader()
	{
		return 'Unspecified';
	}

	function getHeaderClass()
	{
		return "";
	}	
	
	function getHeaderAttrs()
	{
		return "";
	}

	function loadData($users)
	{

	}

	function displayUser($user)
	{

	}

	function search($string)
	{
		return array();
	}
}