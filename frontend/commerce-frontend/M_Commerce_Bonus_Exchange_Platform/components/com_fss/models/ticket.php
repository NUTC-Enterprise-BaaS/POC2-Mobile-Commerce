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
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'tickethelper.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'translate.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_source.php');

class FssModelTicket extends JModelLegacy
{
	var $multiuser = 0;

	function __construct()
	{
		parent::__construct();
		/*$mainframe = JFactory::getApplication(); global $option;

		$limit = $mainframe->getUserStateFromRequest('global.list.limit_prod', 'limit', FSS_Settings::Get('ticket_prod_per_page'), 'int');

		$limitstart = FSS_Input::getInt('limitstart');
		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit_prod', $limit);
		$this->setState('limitstart', $limitstart);*/
	}
	
	function getProducts()
	{
		echo "No model calls!<br>";
		echo dumpStack();
		exit;

		// if data hasn't already been obtained, load it
		/*if (empty($this->_data)) {
			$query = $this->_buildProdQuery();
			
			$this->_db->setQuery( $query, $this->getState('limitstart'), $this->getState('limit_prod') );

			$this->_data = $this->_db->loadAssocList();
			FSS_Translate_Helper::Tr($this->_data);
		}
		return $this->_data;*/
	}
	
	function getProdLimit()
	{
		echo "No model calls!<br>";
		echo dumpStack();
		exit;

		//return $this->getState('limit_prod');
	}
	
	/*function _buildProdQuery()
	{
		$db = JFactory::getDBO();
		$search = FSS_Input::getString('prodsearch');  
		
		// products general query
		
		$query = "SELECT *, 0 as type FROM #__fss_prod";
		$where = array();
		$where[] = "insupport = 1";
		$order = array();
		$order[] = "ordering";
		
		if ($search != "__all__" && $search != '')
		{
			$where[] = "title LIKE '%".FSSJ3Helper::getEscaped($db, $search)."%'";
		}
		
		if (FSS_Settings::get('support_restrict_prod'))
			$where[] = "id = 0";
				
		$where[] = "insupport = 1";
		$where[] = "published = 1";

		
		// add language and access to query where
		$user = JFactory::getUser();
		$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				

		$query .= " WHERE " . implode(" AND ", $where);

		$prodids = $this->GetUserProdIDs();
		
		$qry = "SELECT *, 1 as type FROM #__fss_prod";
		$where = array();
		$where[] = "insupport = 1";
		$where[] = "published = 1";
		$user = JFactory::getUser();
		$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
		
		if (is_array($prodids) && count($prodids) > 0)
			$where[] = "id IN (" . implode(", ", $prodids) . ")";
			
		$order = array();
		$order[] = "ordering";

		if ($search != "__all__" && $search != '')
		{
			$where[] = "title LIKE '%".FSSJ3Helper::getEscaped($db, $search)."%'";
		}

		$qry .= " WHERE " . implode(" AND ", $where);

		$fq = "SELECT *, MAX(type) as maxtype FROM (($query) UNION ($qry)) as a GROUP BY id";

		if (!FSS_Settings::get('support_product_manual_category_order'))
		{
			$fq .= " ORDER BY maxtype DESC, category, subcat, " . implode(", ", $order);
		} else {
			$fq .= " ORDER BY maxtype DESC, " . implode(", ", $order);
		}

		return $fq;        
	}*/

	/*function getTotalProducts()
	{
		echo "No getTotalProducts";
		echo dumpStack();
		exit;
		if (empty($this->_prodtotal)) {
			$query = $this->_buildProdQuery();
			$this->_prodtotal = $this->_getListCount($query);
		}
		return $this->_prodtotal;		
	}*/

	function getTotalDepartments()
	{
		echo "No model calls!<br>";
		echo dumpStack();
		exit;

		/*	if (empty($this->_depttotal)) {
			$query = $this->_buildDeptQuery();
			$this->_depttotal = $this->_getListCount($query);
		}
		return $this->_depttotal;	*/	
	}

	function &getProdPagination()
	{
		echo "No model calls!<br>";
		echo dumpStack();
		exit;

		// Load the content if it doesn't already exist
		/*if (empty($this->_pagination)) {
			$this->_pagination = new JPaginationAjax($this->getTotalProducts(), $this->getState('limitstart'), $this->getState('limit_prod') );
		}
		return $this->_pagination;*/
	}	

	function &getDeptPagination()
	{
		echo "No model calls!<br>";
		echo dumpStack();
		exit;

		// Load the content if it doesn't already exist
		/*if (empty($this->_dept_pagination)) {
			$this->_dept_pagination = new JPaginationAjax($this->getTotalDepartments(), $this->getState('limitstart'), $this->getState('limit_prod') );
		}
		return $this->_dept_pagination;*/
	}	

	function &getProduct()
	{
		echo "No model calls!<br>";
		echo dumpStack();
		exit;
		
		/*$db = JFactory::getDBO();
		$prodid = FSS_Input::getInt('prodid');
		$query = "SELECT * FROM #__fss_prod WHERE id = '".FSSJ3Helper::getEscaped($db, $prodid)."'";

		$db->setQuery($query);
		$rows = $db->loadAssoc();
		FSS_Translate_Helper::TrSingle($rows);
		return $rows;    */    
	} 
	
	/*function compareOrderTitle($a, $b) 
	{ 
		if ($a['ordering'] == $b['ordering'])
			return strnatcmp($a['title'], $b['title']); 
	
		return $a['ordering'] > $b['ordering'];
	}
	
	function compartTitle($a, $b) 
	{ 
		return strnatcmp($a['title'], $b['title']); 
	}*/
	
	function getDepartments()
	{
		echo "No model calls!<br>";
		echo dumpStack();
		exit;

		// if data hasn't already been obtained, load it
		/*if (empty($this->_departments)) {
			$query = $this->_buildDeptQuery();
			
			if (FSS_Settings::get('support_advanced_department') == 1)
			{
				$this->_db->setQuery( $query, $this->getState('limitstart'), $this->getState('limit_prod') );
			} else {
				$this->_db->setQuery( $query );
			}

			$this->_departments = $this->_db->loadAssocList();
			FSS_Translate_Helper::Tr($this->_departments);
		}
		return $this->_departments;*/
	}
		
	function _buildDeptQuery()
	{
		echo "No model calls!<br>";
		echo dumpStack();
		exit;

		/*	if (!empty($this->_dept_query))
			return $this->_dept_query;

		$search = FSS_Input::getString('deptsearch');  
		$swhere = "";
		if ($search != "__all__" && $search != '')
			$swhere = "title LIKE '%".FSSJ3Helper::getEscaped($db, $search)."%'";

		$prodid = FSS_Input::getInt('prodid');
		$db = JFactory::getDBO();
		
		$query1 = "SELECT * FROM #__fss_ticket_dept";
		$where = array();
		if ($swhere) $where[] = $swhere;
		$where[] = "allprods = 1";
		$where[] = "published = 1";
		
		// add language and access to query where
		$user = JFactory::getUser();
		$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
		
		if (count($where) > 0)
			$query1 .= " WHERE " . implode(" AND ",$where);

		$query2 = "SELECT * FROM #__fss_ticket_dept";
			
		$where = array();
		if ($swhere) $where[] = $swhere;
		$where[] = "published = 1";
		// add language and access to query where
		$user = JFactory::getUser();
		$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				

		$where[] = "id IN ( SELECT ticket_dept_id FROM #__fss_ticket_dept_prod WHERE prod_id = '".FSSJ3Helper::getEscaped($db, $prodid)."' )";

		if (count($where) > 0)
			$query2 .= " WHERE " . implode(" AND ",$where);

		$fq = "SELECT * FROM (($query1) UNION ($query2)) as a GROUP BY id";
		
		if (!FSS_Settings::get('support_product_manual_category_order'))
		{
			$fq .= " ORDER BY category, subcat, ordering, title ";
		} else {
			$fq .= " ORDER BY ordering, title ";
		}

		$this->_dept_query = $fq;

		return $fq;  */      
	}  
	
	function &getCats()
	{
		echo "No model calls!<br>";
		echo dumpStack();
		exit;

		/*$db = JFactory::getDBO();
		$query = "SELECT * FROM #__fss_ticket_cat ";
		
		$where = array();
		// add language and access to query where
		$user = JFactory::getUser();
		$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
		
		if (count($where) > 0)
			$query .= " WHERE " . implode(" AND ",$where);
		
		$query .= "ORDER BY ordering, section, title";
		
		$db->setQuery($query);
		$rows = $db->loadAssocList();
		
		$prodid = FSS_Input::getInt('prodid');
		$deptid = FSS_Input::getInt('deptid');

		$query = "SELECT * FROM #__fss_ticket_cat_prod WHERE prod_id = '".FSSJ3Helper::getEscaped($db, $prodid)."'";
		$db->setQuery($query);
		$products = $db->loadAssocList('ticket_cat_id');
		
		$query = "SELECT * FROM #__fss_ticket_cat_dept WHERE ticket_dept_id = '".FSSJ3Helper::getEscaped($db, $deptid)."'";
		$db->setQuery($query);
		$departments = $db->loadAssocList('ticket_cat_id');
		
		$output = array();
		
		if (is_array($rows))
		{
			foreach ($rows as $row)
			{
				if ($row['allprods'] == 0)
					if (!array_key_exists($row['id'],$products))
					{
						continue;
					}
					
				if ($row['alldepts'] == 0)
					if (!array_key_exists($row['id'],$departments))
					{
						continue;
					}
				
				$output[] = $row;
			}
		}
		
		return $output;  */      
	}  
	
	function &getDepartment()
	{
		echo "No model calls!<br>";
		echo dumpStack();
		exit;

		/*$db = JFactory::getDBO();
		$deptid = FSS_Input::getInt('deptid');
		$query = "SELECT * FROM #__fss_ticket_dept WHERE id = '".FSSJ3Helper::getEscaped($db, $deptid)."'";

		$db->setQuery($query);
		$rows = $db->loadAssoc();
		return $rows;    */    
	} 
	
	function &getTicket($ticketid)
	{
		echo "No model calls!<br>";
		echo dumpStack();
		exit;

		/*$db = JFactory::getDBO();

		$query = "SELECT t.*, u.name, u.username, p.title as product, d.title as dept, d.translation as dtr, c.title as cat, c.translation as ctr, s.title as status, s.translation as str, s.userdisp, ";
		$query .= "s.color as scolor, s.id as sid, pr.title as pri, pr.color as pcolor, pr.translation as ptr, pr.id as pid, au.name as assigned, p.translation as prtr ";
		$query .= " FROM #__fss_ticket_ticket as t ";
		$query .= " LEFT JOIN #__users as u ON t.user_id = u.id ";
		$query .= " LEFT JOIN #__fss_prod as p ON t.prod_id = p.id ";
		$query .= " LEFT JOIN #__fss_ticket_dept as d ON t.ticket_dept_id = d.id ";
		$query .= " LEFT JOIN #__fss_ticket_cat as c ON t.ticket_cat_id = c.id ";
		$query .= " LEFT JOIN #__fss_ticket_status as s ON t.ticket_status_id = s.id ";
		$query .= " LEFT JOIN #__fss_ticket_pri as pr ON t.ticket_pri_id = pr.id ";
		$query .= " LEFT JOIN #__users as au ON t.admin_id = au.id ";
		$query .= " WHERE t.id = '".FSSJ3Helper::getEscaped($db, $ticketid)."' ";

		$query .= " AND " . SupportSource::user_show_sql();
	
		$db->setQuery($query);

		$rows = $db->loadAssoc();
		return $rows; */  		
	}
	
	function &getMessages($ticketid)
	{
		echo "No model calls!<br>";
		echo dumpStack();
		exit;

		/*$db = JFactory::getDBO();
		
		$query = "SELECT m.*, u.name FROM #__fss_ticket_messages as m LEFT JOIN #__users as u ON m.user_id = u.id WHERE ticket_ticket_id = '".FSSJ3Helper::getEscaped($db, $ticketid)."' ORDER BY posted DESC";

		$db->setQuery($query);
		$rows = $db->loadAssocList();
		return $rows;   */		
	}
	
	function &getAttach($ticketid)
	{
		echo "No model calls!<br>";
		echo dumpStack();
		exit;

		/*$db = JFactory::getDBO();
 
		$query = "SELECT a.*, u.name FROM #__fss_ticket_attach as a LEFT JOIN #__users as u ON a.user_id = u.id WHERE ticket_ticket_id = '".FSSJ3Helper::getEscaped($db, $ticketid)."' AND hidefromuser = 0 ORDER BY added DESC";

		$db->setQuery($query);
		$rows = $db->loadAssocList();
		return $rows;   	*/	
	}
	
	function &getTickets()
	{		
		echo "No model calls!<br>";
		echo dumpStack();
		exit;
		
		/*$db = JFactory::getDBO();
		
		$user = JFactory::getUser();
		$userid = $user->get('id');
		
		$uidlist = $this->getUIDS($userid);
		$tidlist = $this->getTIDS($userid);
		
		$query = "SELECT t.*, s.title as status, s.color, u.name, au.name as assigned, u.email as useremail, u.username as username, au.email as handleremail, au.username as handlerusername, ";
		$query .= " dept.title as department, cat.title as category, prod.title as product, pri.title as priority, pri.color as pricolor, ";
		$query .= " grp.groupname as groupname, grp.id as group_id ";
		$query .= " , pri.translation as ptl, dept.translation as dtr, s.translation as str, cat.translation as ctr, prod.translation as prtr";
		$query .= " FROM #__fss_ticket_ticket as t ";
		$query .= " LEFT JOIN #__fss_ticket_status as s ON t.ticket_status_id = s.id ";
		$query .= " LEFT JOIN #__users as u ON t.user_id = u.id ";
		$query .= " LEFT JOIN #__users as au ON t.admin_id = au.id ";
		$query .= " LEFT JOIN #__fss_ticket_dept as dept ON t.ticket_dept_id = dept.id ";
		$query .= " LEFT JOIN #__fss_ticket_cat as cat ON t.ticket_cat_id = cat.id ";
		$query .= " LEFT JOIN #__fss_prod as prod ON t.prod_id = prod.id ";
		$query .= " LEFT JOIN #__fss_ticket_pri as pri ON t.ticket_pri_id = pri.id ";
		$query .= " LEFT JOIN (SELECT group_id, user_id FROM #__fss_ticket_group_members GROUP BY user_id) as mem ON t.user_id = mem.user_id ";
		$query .= " LEFT JOIN #__fss_ticket_group as grp ON grp.id = mem.group_id ";
		
		// add product, department and category
				
		$query .= " WHERE ( t.user_id IN (" . implode(", ",$uidlist) . ") OR t.id IN (" . implode(", ", $tidlist) . ") ) ";

		$query .= " AND " . SupportSource::user_list_sql();
	
		$tickets = FSS_Input::getCmd('tickets','open');
		
		if (FSS_Settings::get('support_simple_userlist_tabs'))
			$tickets = "all";
		
		if (FSS_Input::getCmd('search_all'))
		{
			$tickets = "";
		}
			
		if ($tickets == 'open')
		{
			$allopen = FSS_Ticket_Helper::GetStatusIDs("is_closed", true);
			// tickets that arent closed
			$query .= " AND ticket_status_id IN ( " . implode(", ", $allopen) . ") ";
		}
		if ($tickets == 'closed')
		{
			$allopen = FSS_Ticket_Helper::GetStatusIDs("is_closed");
			// remove the archived tickets from the list to deal with
				
			$def_archive = FSS_Ticket_Helper::GetStatusID('def_archive');
			foreach ($allopen as $offset => $value)
				if ($value == $def_archive)
					unset($allopen[$offset]);

			// tickets that are closed
			$query .= " AND ticket_status_id IN ( " . implode(", ", $allopen) . ") ";
		} else if ($tickets > 0) {
			$statuss = SupportHelper::getStatuss(false);
				
			$status_list = array();
			$status_list[] = (int)$tickets;
				
			foreach ($statuss as $status)
			{
				if ($status->combine_with == (int)$tickets)
				{
					$status_list[] = $status->id;
				}
			}
			
			$query .= " AND ticket_status_id IN (" . implode(", ", $status_list) . ")";
		}
		
		$search = FSS_Input::getString('search');
		if ($search != "")
		{
			FSS_Helper::allowBack();

			// We have the nearly full query here, so use it to get a list of ticket ids
			$db->setQuery($query);
			$recs = $db->loadObjectList();
			
			$ids = array();
			$ids[] = 0;
			
			foreach ($recs as $rec)
				$ids[] = $rec->id;
			
			$mode = "";
			if (FSS_Helper::contains($search, array('*', '+', '-', '<', '>', '(', ')', '~', '"'))) $mode = "IN BOOLEAN MODE";

			$msgsrch = "SELECT ticket_ticket_id FROM #__fss_ticket_messages WHERE ticket_ticket_id IN (" . implode(", ", $ids) . ") AND admin < 3 AND ";
			$msgsrch .= " ( MATCH (body) AGAINST ('" . $db->escape($search) . "' $mode) ";
			if (FSS_Settings::get('search_extra_like')) $msgsrch .= " OR body LIKE '" . FSS_Helper::strForLike($search) . "'";
			$msgsrch .= " )";
			$db->setQuery($msgsrch);
			$results = $db->loadObjectList();
		
			$ids = array();
			$ids[] = 0;
			
			foreach ($results as $rec)
				$ids[] = $rec->ticket_ticket_id;

			// search custom fields that are set to be searched
			$fields = FSSCF::GetAllCustomFields(true);			
			foreach ($fields as $field)
			{
				if (!$field["basicsearch"]) continue;
				if ($field['permissions'] > 1 && $field['permissions'] < 5) continue;
				$fieldid = $field['id'];
				
				if ($field['type'] == "checkbox")
				{
					if ($search == "1")
					{
						$search = "on";
					} else {
						$search = "";
					}
				}
				
				if ($field['peruser'])
					continue;
				
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
								$res = $po->Search($data['plugindata'], $search, false, false);
								
								if ($res !== false)
								{
									foreach ($res as $item)
										$ids[] = (int)$item->ticket_id;
									continue;
								}
							}
						}
					}
				}
				
				$qry = "SELECT ticket_id FROM #__fss_ticket_field WHERE field_id = '" . FSSJ3Helper::getEscaped($db, $fieldid) . "' AND value LIKE '%" . FSSJ3Helper::getEscaped($db, $search) . "%'";
				$db->setQuery($qry);	
				$data = $db->loadObjectList();
				foreach ($data as $item)
				{
					$id = (int)$item->ticket_id;
					if ($id > 0)
						$ids[] = $id;
				}
				
			}	
			
			 //"MATCH (question, answer) AGAINST ('" . $db->escape($search) . "')"
			$query .= " AND ( t.id IN (" . implode(", ", $ids) . ") OR MATCH (t.title) AGAINST ('" . $db->escape($search) . "' $mode) ";
			if (FSS_Settings::get('search_extra_like') || strlen($search) < 4) $query .= " OR t.title LIKE '" . FSS_Helper::strForLike($search) . "'";
			$query .= " OR t.reference LIKE '" . FSS_Helper::strForLike($search) . "' ) ";
		}

		$order = FSS_Input::getCmd('order');
		$order_dir = FSS_Input::getCmd('order_dir', 'asc');
		
		$order_dir_allowed = array('asc', 'desc');
		if (!in_array($order_dir, $order_dir_allowed))
			$order_dir = 'asc';
		
		$order_allowed = array('t.title', 'lastupdate', 'status', 'assigned', 'lastupdate', 'u.name');
		if (!in_array($order, $order_allowed))
			$order = '';
		
		if ($order != "")
		{
			$query .= " ORDER BY $order $order_dir";	
		} else {
			$query .= " ORDER BY lastupdate DESC ";
		}

		$mainframe = JFactory::getApplication();

		$limit = $mainframe->getUserStateFromRequest('global.list.limit_ticket', 'limit', FSS_Settings::Get('ticket_per_page'), 'int');
		$limitstart = FSS_Input::getInt('limitstart');
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		
		$db->setQuery($query);
		$db->query();
		
		//echo "<br>".str_replace("\n", "<br>", $query)."<br>";
		$count = $db->getNumRows();
		$result['pagination'] = new JPaginationJs($count, $limitstart, $limit );
		
		$db->setQuery($query, $limitstart, $limit);
		$result['tickets'] = $db->loadObjectList();

		foreach ($result['tickets'] as &$ticket)
		{
			$fields = FSSCF::GetCustomFields($ticket->id,$ticket->prod_id,$ticket->ticket_dept_id);
			$values = FSSCF::GetTicketValues($ticket->id, $ticket);
			
			$ticket->fields = array();
			
			foreach ($fields as &$field)
			{
				$ticket->fields[$field['id']] = array();
				$ticket->fields[$field['id']]['name'] = $field['description'];
				$ticket->fields[$field['id']]['value'] = '';
				
				if (isset($values[$field['id']]))
					$ticket->fields[$field['id']]['value'] = $values[$field['id']]['value'];
			}
		}

		return $result;   */		
	}
	
	function &getPriority($priid)
	{
		echo "No model calls!<br>";
		echo dumpStack();
		exit;

		/*$db = JFactory::getDBO();
		
		$query = "SELECT * FROM #__fss_ticket_pri WHERE id = '".FSSJ3Helper::getEscaped($db, $priid)."'";

		$db->setQuery($query);
		$rows = $db->loadAssoc();
		return $rows; */  		
		
	}
	
	function &getCategory($catid)
	{
		echo "No model calls!<br>";
		echo dumpStack();
		exit;

		/*$db = JFactory::getDBO();
		
		$query = "SELECT * FROM #__fss_ticket_cat WHERE id = '".FSSJ3Helper::getEscaped($db, $catid)."'";

		$db->setQuery($query);
		$rows = $db->loadAssoc();
		return $rows;  */ 		
		
	}
	
	function &getPriorities()
	{
		echo "No getPriorities<br>";
		echo dumpStack();
		exit;
	
		/*
		$db = JFactory::getDBO();
		
		$query = "SELECT * FROM #__fss_ticket_pri";
		
		$where = array();
		// add language and access to query where
		$user = JFactory::getUser();
		$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
		
		if (count($where) > 0)
			$query .= " WHERE " . implode(" AND ",$where);

		$query .= " ORDER BY ordering ASC";

		$db->setQuery($query);
		$rows = $db->loadAssocList();
		return $rows;   		
		*/
	}
	
	function &getStatuss()
	{
		echo "No model calls!<br>";
		echo dumpStack();
		exit;

		/*if (empty($this->_statuss))
		{
			$db = JFactory::getDBO();
		
			$query = "SELECT * FROM #__fss_ticket_status ORDER BY ordering ASC";

			$db->setQuery($query);
			$this->_statuss = $db->loadAssocList('id');
		}
		return $this->_statuss;   */		
	}	
	
	function &getStatus($statusid)
	{
		echo "No model calls!<br>";
		echo dumpStack();
		exit;

		/*	if (empty($this->_statuss))
		{
			$this->getStatuss();
		}

		return $this->_statuss[$statusid];*/
	}
	
	static $ticket_counts;
	
	function &getTicketCount()
	{
		/*echo "No model calls!<br>";
		echo dumpStack();
		exit;*/

		$user = JFactory::getUser();
		$userid = $user->get('id');

		if (empty(self::$ticket_counts))
			self::$ticket_counts = array();
		
		if (array_key_exists($userid, self::$ticket_counts))
		{
			$this->_counts = self::$ticket_counts[$userid];
			return $this->_counts;
		}
		
		$uidlist = SupportHelper::getUIDS($userid);
		$tidlist = SupportHelper::getTIDS($userid);

		$db = JFactory::getDBO();
		$query = "SELECT count( * ) AS count, ticket_status_id FROM #__fss_ticket_ticket WHERE (user_id IN (".implode(", ",$uidlist) . ") OR id IN ( " . implode(", ",$tidlist) . ")) ";
		$query .= " AND " . SupportSource::user_list_sql();
		$query .= " GROUP BY ticket_status_id";
				
		$db->setQuery($query);
	
		$rows = $db->loadAssocList();
				
		$out = array();
		FSS_Ticket_Helper::GetStatusList();
		foreach (FSS_Ticket_Helper::$status_list as $status)
		{
			$out[$status->id] = 0;
		}
			
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				$out[$row['ticket_status_id']] = $row['count'];
			}
		}
			
		// work out counts for allopen, closed, all, archived
			
		$archived = FSS_Ticket_Helper::GetStatusID("def_archive");
		if (array_key_exists($archived, $out))
		{
			$out['archived'] = $out[$archived];
		} else {
			$out['archived'] = 0;		
		}


		$allopen = FSS_Ticket_Helper::GetStatusIDs("is_closed", true);
		$out['open'] = 0;
		foreach ($allopen as $id)
		{
			if (array_key_exists($id, $out))
				$out['open'] += $out[$id];
		}
		
			
		$allclosed = FSS_Ticket_Helper::GetClosedStatus();
		$out['closed'] = 0;
		foreach ($allclosed as $id)
		{
			if (array_key_exists($id, $out))
				$out['closed'] += $out[$id];
		}

			
		$all = FSS_Ticket_Helper::GetStatusIDs("def_archive");
		$out['all'] = 0;
		foreach ($rows as $row)
		{
			if ($row['ticket_status_id'] != $all)
				$out['all'] += $row['count'];
		}
			
			
		$this->_counts = $out;
		self::$ticket_counts[$userid] = $out;
		return $this->_counts;
	}
	
		
	function getUser($user_id)
	{
		echo "No model calls!<br>";
		echo dumpStack();
		exit;

		/*$db = JFactory::getDBO();
		$query = " SELECT * FROM #__users ";
		$query .= " WHERE id = '".FSSJ3Helper::getEscaped($db, $user_id)."'";
		
		$db->setQuery($query);
		$rows = $db->loadAssoc();
		return $rows; */  		
	}
	
	/*static $tid_list;	
	function getTIDS($user_id)
	{
		if (empty(self::$tid_list))
			self::$tid_list = array();
		
		if (array_key_exists($user_id, self::$tid_list))
			return self::$tid_list[$user_id];

		$db = JFactory::getDBO();
		
		$qry = "SELECT ticket_id FROM #__fss_ticket_cc WHERE user_id = '".FSSJ3Helper::getEscaped($db,  $user_id)."' AND isadmin = 0";
		$db->setQuery($qry);
		$rows = $db->loadAssocList();
		$this->tidlist = array();
		
		foreach ($rows as $row)
		{
			$this->tidlist[$row['ticket_id']] = $row['ticket_id'];	
			
			$this->multiuser = 1;
		}		
		
		if (count($this->tidlist) == 0)
			$this->tidlist[] = 0;
			
		self::$tid_list[$user_id] = $this->tidlist;
		
		return $this->tidlist;
	}
	
	static $uidlist;
	function getUIDS($user_id)
	{
		if (empty(self::$uidlist))
			self::$uidlist = array();
		
		if (array_key_exists($user_id, self::$uidlist))
			return self::$uidlist[$user_id];

		$db = JFactory::getDBO();
		
		// get groups
		$query = " SELECT * FROM #__fss_ticket_group_members ";
		$query .= " WHERE user_id = '".FSSJ3Helper::getEscaped($db, $user_id)."'";
		
		$db->setQuery($query);
		$usergrouplist = $db->loadAssocList('group_id');
		//print_p($usergrouplist);
		
		if (count($usergrouplist) == 0)
		{
			self::$uidlist[$user_id] = array($user_id => $user_id);
			return self::$uidlist[$user_id];
		}

		$gids = array();
		foreach ($usergrouplist as $group)
		{
			$gids[] = FSSJ3Helper::getEscaped($db, $group['group_id']);
		}
		
		$query = "SELECT * FROM #__fss_ticket_group WHERE id IN (" . implode(", ",$gids) .")";
		$db->setQuery($query);
		$grouplist = $db->loadAssocList();
		//print_p($grouplist);
		
		$gids = array();
		foreach($grouplist as $group)
		{
			// find if the user has permissions to view this groups tickets
			$perm = $usergrouplist[$group['id']]['allsee'];
			$groupperm = $group['allsee'];
			if ($perm == 0)
				$perm = $groupperm;
	
			if ($perm > 0) // view allowed
			{
				$gids[] = FSSJ3Helper::getEscaped($db, $group['id']);
			}
		}

		if (count($gids) == 0)
		{
			self::$uidlist[$user_id] = array($user_id => $user_id);
			return self::$uidlist[$user_id];
		}
		
		$query = "SELECT user_id FROM #__fss_ticket_group_members WHERE group_id IN (" . implode(", ",$gids) .")";
		$db->setQuery($query);
		$groupmemberlist = $db->loadAssocList();

		if (count($groupmemberlist) == 0)
		{
			self::$uidlist[$user_id] = array($user_id => $user_id);
			return self::$uidlist[$user_id];
		}

		$uids = array();
		
		foreach ($groupmemberlist as $row)
		{
			$uids[$row['user_id']] = $row['user_id'];
		}

		self::$uidlist[$user_id] = $uids;
		$this->multiuser = 1;	        
		
		return self::$uidlist[$user_id];   		
	}*/
}