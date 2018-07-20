<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.model' );
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'pagination.php');

class FssModelAdmin_Groups extends JModelLegacy
{
	function getGroups()
	{
		if (!empty($this->groups))
			return $this->groups;
			
		$db = JFactory::getDBO();

		
		$qry = "SELECT g.id, g.groupname, g.description, cnt, allsee, allemail, allprods, ccexclude FROM #__fss_ticket_group as g LEFT JOIN (SELECT group_id, count(*) as cnt FROM #__fss_ticket_group_members GROUP BY group_id) as c ON g.id = c.group_id ";
		
		if (!FSS_Permission::auth("fss.groups", "com_fss.groups"))
			$qry .= " WHERE g.id IN (" . implode(", ", FSS_Permission::$group_id_access) . ") ";
		
		//echo $qry . "<br>";
		$db->setQuery($qry);
			
		$this->groups = $db->loadObjectList();
		
		return $this->groups;
	}
	
	function getGroupProds()
	{
		if (!empty($this->group_prods))
			return $this->group_prods;
			
		$db = JFactory::getDBO();

		$groupid = FSS_Input::getInt('groupid');
		
		$db	= JFactory::getDBO();

		$query = "SELECT * FROM #__fss_ticket_group_prod as a LEFT JOIN #__fss_prod as p ON a.prod_id = p.id WHERE a.group_id = '".FSSJ3Helper::getEscaped($db, $groupid)."' ORDER BY p.title ";
		$db->setQuery($query);
			
		$this->group_prods = $db->loadObjectList();
		
		return $this->group_prods;
	}
	
	function getGroup()
	{
		if (!empty($this->group))
			return $this->group;
			
		$db = JFactory::getDBO();

		$groupid = FSS_Input::getInt('groupid');
		
		$db	= JFactory::getDBO();

		$query = "SELECT * FROM #__fss_ticket_group WHERE id = '".FSSJ3Helper::getEscaped($db, $groupid)."'";
		$db->setQuery($query);
			
		$this->group = $db->loadObject();
		
		return $this->group;
		
	}
	
	function getGroupMembers()
	{
		if (!empty($this->groupmembers))
			return $this->groupmembers;
			
		$mainframe = JFactory::getApplication();
		$db = JFactory::getDBO();

		$groupid = FSS_Input::getInt('groupid');
		
		$db	= JFactory::getDBO();

	    $query = ' SELECT g.group_id, g.user_id, u.name, u.username, u.email, g.allsee, g.allemail, g.isadmin FROM #__fss_ticket_group_members as g LEFT JOIN ';
		$query .= "#__users as u ON g.user_id = u.id";
		$where = array();
		$where[] = 'g.group_id = "' . $groupid . '"';
 		$query .= (count($where) ? ' WHERE '.implode(' AND ', $where) : '');

		$this->order = FSS_Input::getCmd('filter_order');
		$this->order_Dir = FSS_Input::getCmd('filter_order_Dir');
		if ($this->order) {
			$query .= ' ORDER BY '. $this->order .' '. $this->order_Dir .'';
		}

		$db->setQuery($query);
		$db->query();

		$row_count = $db->getNumRows();
		
		$this->filter_values['limitstart'] = FSS_Input::getInt("limit_start",0);
		$this->filter_values['limit'] = $mainframe->getUserStateFromRequest("gmemberslimit_base","limit_base","20");
		
		$this->groupmembers_pagination = new JPaginationAjax($row_count, $this->filter_values['limitstart'], $this->filter_values['limit'] );

		$db->setQuery($query, $this->filter_values['limitstart'], $this->filter_values['limit']);	
		$this->groupmembers = $db->loadObjectList();
		
		return $this->groupmembers;	
	}
	
	function getGroupMembersPagination()
	{
		return $this->groupmembers_pagination;
	}
	
	function getUsers()
	{
		if (!empty($this->users))
			return $this->users;
			
		$mainframe = JFactory::getApplication();

		$db = JFactory::getDBO();

		$db	= JFactory::getDBO();

		$query = 'SELECT a.id, a.username, a.name, a.email, g.title as lf1, gm.group_id as gid FROM #__users as a 
			LEFT JOIN #__user_usergroup_map as gm ON a.id = gm.user_id
			LEFT JOIN #__usergroups as g ON gm.group_id = g.id';
		
		$where = array();

		$this->search = strtolower(FSS_Input::getString('search'));

		if (!FSS_Permission::auth("fss.groups", "com_fss.groups"))
 		{
			$this->username = strtolower(FSS_Input::getString('username'));
			$this->email = strtolower(FSS_Input::getString('email'));
			if ($this->username && $this->email)
			{
				$where[] = "LOWER( a.username ) = '".FSSJ3Helper::getEscaped($db, $this->username)."'";
				$where[] = "LOWER( email ) = '".FSSJ3Helper::getEscaped($db, $this->email)."'";
			} else {
 				$where[] = "a.id = 0";	
			}
		} elseif ($this->search) {
			$search = array();
			$search[] = '(LOWER( a.username ) LIKE '.$db->Quote( '%'.FSSJ3Helper::getEscaped($db,  $this->search, true ).'%', false ) . ')';
			$search[] = '(LOWER( a.name ) LIKE '.$db->Quote( '%'.FSSJ3Helper::getEscaped($db,  $this->search, true ).'%', false ) . ')';
			$search[] = '(LOWER( a.email ) LIKE '.$db->Quote( '%'.FSSJ3Helper::getEscaped($db,  $this->search, true ).'%', false ) . ')';

			$where[] = " ( " . implode(" OR ",$search) . " ) ";
		}

		$order = "";
		
		$this->order = FSS_Input::getCmd('filter_order');
		$this->order_Dir = FSS_Input::getCmd('filter_order_Dir');
		if ($this->order) {
			$order = ' ORDER BY '. FSSJ3Helper::getEscaped($db,  $this->order) .' '. FSSJ3Helper::getEscaped($db,  $this->order_Dir) .'';
		}

		$this->gid = FSS_Input::getInt('gid');
		if ($this->gid != '')
		{
			$where[] = 'gm.group_id = "' . FSSJ3Helper::getEscaped($db,  $this->gid) . '"';
		}

  		$where = (count($where) ? ' WHERE '.implode(' AND ', $where) : '');

  		$query .= $where . " GROUP BY a.username " . $order;

		//echo $query;

		$db->setQuery($query);
		$db->query();

		$row_count = $db->getNumRows();
		
		$this->filter_values['limitstart'] = FSS_Input::getInt("limit_start",0);
		$this->filter_values['limit'] = $mainframe->getUserStateFromRequest("pickuserlimit_base","limit_base","20");
		
		$this->users_pagination = new JPaginationAjax($row_count, $this->filter_values['limitstart'], $this->filter_values['limit'] );

		$db->setQuery($query, $this->filter_values['limitstart'], $this->filter_values['limit']);
		
		$this->users = $db->loadObjectList();
		
		return $this->users;		
	}
	
	function getUsersPagination()
	{
		return $this->users_pagination;
	}
	
	
	function GetProducts()
	{
		if (empty($this->products))
		{
			$db = JFactory::getDBO();
			$query = "SELECT * FROM #__fss_prod WHERE insupport = 1 ORDER BY title";
			$db->setQuery($query);
			$this->products = $db->loadObjectList();
		}
		
		return $this->products;
	}
}

	    	 	 	   			