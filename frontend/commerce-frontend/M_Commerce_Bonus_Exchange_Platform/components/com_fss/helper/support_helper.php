<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

/**
 * Generic support helper class
 * 
 * Does things like load in list of status etc
 * 
 * REPLACES the old TicketHelper (FSS_Ticket_Helper)
**/

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'translate.php');

class SupportHelper
{
	static $data_lists = array();
	static function getDataList($sort, $key, $query, $translate = false)
	{
		if (!array_key_exists($key, self::$data_lists))
		{
			$db = JFactory::getDBO();
			//echo $query."<br>";
			$db->setQuery($query);
			self::$data_lists[$key]['s'] = $db->loadObjectList();
			self::$data_lists[$key]['i'] = array();
			
			
			foreach (self::$data_lists[$key]['s'] as $item)
				self::$data_lists[$key]['i'][$item->id] = $item;
		}
		
		if ($sort)
		{
			if ($translate) FSS_Translate_Helper::Tr(self::$data_lists[$key]['s']);
			return self::$data_lists[$key]['s'];		
		}

		if ($translate) FSS_Translate_Helper::Tr(self::$data_lists[$key]['i']);
		return self::$data_lists[$key]['i'];		
	}
	
	static function getPriorities($sort = true, $order = "ordering", $for_user = false, $user = null)
	{
		if (!$user) $user = JFactory::getUser();
		
		if ($for_user)
		{
			$query = "SELECT * FROM #__fss_ticket_pri";
			$query .= " WHERE access IN (" . implode(',', $user->getAuthorisedViewLevels()) . ") ";
			
			return self::getDataList($sort, "priorities-user-" . $order, $query .= " ORDER BY " . $order, true);
		}
			
		return self::getDataList($sort, "priorities-" . $order, "SELECT * FROM #__fss_ticket_pri ORDER BY " . $order, true);
	}
	
	static function getPrioritiesUser($sort = true, $order = "ordering", $user = null)
	{
		return self::getPriorities($sort, $order, true, $user);
	}
	
	static function getStatuss($sort = true, $order = "ordering", $for_user = false)
	{
		$data = self::getDataList($sort, "statuss-" . $order, "SELECT * FROM #__fss_ticket_status ORDER BY " . $order, true);
		
		if ($for_user)
		{
			static $user_status;
			
			if (empty($user_status))
			{
				foreach ($data as $status)
				{
					$new_status = (object)(array)$status;
					if ($new_status->userdisp)
						$new_status->title = $new_status->userdisp;		
					
					$user_status[$new_status->id] = $new_status;
				}
			}
			
			return $user_status;
			// need to build a list of status for a user to see. Translate and use the 'user label' if exists	
		}
		
		return $data;
	}
	
	static function getStatussUser($sort = true, $order = "ordering")
	{
		return self::getStatuss($sort, $order, true);
	}
	static function getCategories($sort = true, $order = "ordering, section, title")
	{
		return self::getDataList($sort, "categories-" . $order, "SELECT * FROM #__fss_ticket_cat WHERE published = 1 ORDER BY " . $order, true);
	}
	
	static function getProducts($sort = true, $order = "ordering")
	{
		return self::getDataList($sort, "products-" . $order, "SELECT * FROM #__fss_prod WHERE published = 1 ORDER BY " . $order, true);
	}
		
	static function getProduct($prodid)
	{
		$prods = SupportHelper::getProducts(false);
		if (array_key_exists($prodid, $prods)) return $prods[$prodid];
		return null;
	}	
				
	static function getDepartment($deptid)
	{
		$depts = SupportHelper::getDepartments(false);
		if (array_key_exists($deptid, $depts)) return $depts[$deptid];
		return null;
	}	
		
	static $user_open_products = array();
	static $user_open_products_count = array();
	static function getProductsUserOpen($limitstart = 0, $limit = 50, $search = "", $user = null)
	{
		if (!$user) $user = JFactory::getUser();

		$key = $user->id."-".$limitstart."-".$limit."-".(string)$search;
		$countkey = $user->id."-".(string)$search;
		
		if (isset(self::$user_open_products[$key]))
		{
			FSS_Translate_Helper::Tr(self::$user_open_products[$key]);
			return self::$user_open_products[$key];
		}

		$db = JFactory::getDBO();
	
		// products general query		
		$query = "SELECT *, 0 as type FROM #__fss_prod";
		$where = array();
		$where[] = "insupport = 1";
		$order = array();
		$order[] = "ordering";
		
		if ($search != "__all__" && $search != '')
		{
			$where[] = "title LIKE '%".$db->escape($search)."%'";
		}
		
		if (FSS_Settings::get('support_restrict_prod'))
			$where[] = "id = 0";
				
		$where[] = "insupport = 1";
		$where[] = "published = 1";

		// add language and access to query where
		$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				

		$query .= " WHERE " . implode(" AND ", $where);

		$prodids = self::getUserProdIDs($user);
		
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
			$where[] = "title LIKE '%".$db->escape($search)."%'";
		}

		$qry .= " WHERE " . implode(" AND ", $where);

		$fq = "SELECT *, MAX(type) as maxtype FROM (($query) UNION ($qry)) as a GROUP BY id";

		if (!FSS_Settings::get('support_product_manual_category_order'))
		{
			$fq .= " ORDER BY maxtype DESC, category, subcat, " . implode(", ", $order);
		} else {
			$fq .= " ORDER BY maxtype DESC, " . implode(", ", $order);
		}

		$db->setQuery($fq);
		$db->query();
		self::$user_open_products_count[$countkey] = $db->getNumRows();
		
		$db->setQuery($fq, $limitstart, $limit);
		self::$user_open_products[$key] = $db->loadObjectList();
		
		FSS_Translate_Helper::Tr(self::$user_open_products[$key]);
		
		return self::$user_open_products[$key];        
	}
	
	static function getProductsUserOpenCount($search = "", $user = null)
	{
		if (!$user) $user = JFactory::getUser();
		$countkey = $user->id."-".(string)$search;	
		return self::$user_open_products_count[$countkey];	
	}
	
	static $user_open_departments = array();
	static $user_open_departments_count = array();
	static function getDepartmentsUserOpen($prodid, $limitstart = 0, $limit = 50, $search = "", $user = null)
	{
		if (!$user) $user = JFactory::getUser();

		$key = $user->id."-".$prodid."-".$limitstart."-".$limit."-".(string)$search;
		$countkey = $user->id."-".$prodid."-".(string)$search;
		
		if (isset(self::$user_open_departments[$key])) 
		{
			FSS_Translate_Helper::Tr(self::$user_open_departments[$key]);
			return self::$user_open_departments[$key];
		}

		$db = JFactory::getDBO();
	
		$search = FSS_Input::getString('deptsearch');  
		$swhere = "";
		if ($search != "__all__" && $search != '')
			$swhere = "title LIKE '%".$db->escape($search)."%'";

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

		$where[] = "id IN ( SELECT ticket_dept_id FROM #__fss_ticket_dept_prod WHERE prod_id = '".$db->escape($prodid)."' )";

		if (count($where) > 0)
			$query2 .= " WHERE " . implode(" AND ",$where);

		$fq = "SELECT * FROM (($query1) UNION ($query2)) as a GROUP BY id";
		
		if (!FSS_Settings::get('support_product_manual_category_order'))
		{
			$fq .= " ORDER BY category, subcat, ordering, title ";
		} else {
			$fq .= " ORDER BY ordering, title ";
		}

		$db->setQuery($fq);
		$db->query();
		self::$user_open_departments_count[$countkey] = $db->getNumRows();
		
		$db->setQuery($fq, $limitstart, $limit);
		self::$user_open_departments[$key] = $db->loadObjectList();
		
		FSS_Translate_Helper::Tr(self::$user_open_departments[$key]);
		return self::$user_open_departments[$key];        
	}
	
	static function getDepartmentsUserOpenCount($prodid, $search = "", $user = null)
	{
		if (!$user) $user = JFactory::getUser();
		$countkey = $user->id."-".$prodid."-".(string)$search;
		return self::$user_open_departments_count[$countkey];	
	}
	
	static $user_open_categories = array();	
	static function getCategoriesUserOpen($prodid, $deptid, $user = null)
	{
		if (!$user) $user = JFactory::getUser();

		$key = $user->id."-".$prodid."-".$deptid;
		
		if (isset(self::$user_open_categories[$key])) 
		{
			FSS_Translate_Helper::Tr(self::$user_open_categories[$key]);
			return self::$user_open_categories[$key];
		}


		$db = JFactory::getDBO();
		$query = "SELECT * FROM #__fss_ticket_cat ";
		$query .= "WHERE access IN (" . implode(',', $user->getAuthorisedViewLevels()) . ") ";
		$query .= "ORDER BY ordering, section, title";
		
		// add language and access to query where
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		$query = "SELECT * FROM #__fss_ticket_cat_prod WHERE prod_id = '".$db->escape($prodid)."'";
		$db->setQuery($query);
		$products = $db->loadAssocList('ticket_cat_id');
		
		$query = "SELECT * FROM #__fss_ticket_cat_dept WHERE ticket_dept_id = '".$db->escape($deptid)."'";
		$db->setQuery($query);
		$departments = $db->loadAssocList('ticket_cat_id');
		
		$output = array();
		
		if (is_array($rows))
		{
			foreach ($rows as $row)
			{
				if ($row->allprods == 0)
				{
					if (!array_key_exists($row->id,$products))
					{
						continue;
					}
				}
				
				if ($row->alldepts == 0)
				{
					if (!array_key_exists($row->id,$departments))
					{
						continue;
					}
				}
				
				$output[] = $row;
			}
		}
		
		self::$user_open_categories[$key] = $output; 
		FSS_Translate_Helper::Tr(self::$user_open_categories[$key]);
		return self::$user_open_categories[$key];
	}
		
	static function getDepartments($sort = true, $order = "ordering, title")
	{
		return self::getDataList($sort, "departments-" . $order, "SELECT * FROM #__fss_ticket_dept WHERE published = 1 ORDER BY " . $order, true);
	}
	
	static function getTicketGroups($sort = true, $order = "groupname")
	{
		return self::getDataList($sort, "ticketgroups-" . $order, "SELECT * FROM #__fss_ticket_group ORDER BY " . $order);
	}
	
	static function getTags($sort = true, $order = "cnt DESC", $limit = 10)
	{
		return self::getDataList($sort, "tags-" . $order . "-" . $limit, "SELECT count(*) as cnt, tag, tag as id FROM #__fss_ticket_tags GROUP BY tag ORDER BY {$order} LIMIT {$limit}");
	}

	static function getAllowedCategories($ticket, $sort = true, $order = "ordering, section, title")
	{
		return self::getCategoriesUserOpen($ticket->prod_id, $ticket->ticket_dept_id);

		// TODO: Make this only display categories available for the current ticket
		//return self::getDataList($sort, "allowed-categories-" . $order . "-" . $ticket->id, "SELECT * FROM #__fss_ticket_cat WHERE published = 1 ORDER BY " . $order, true);
	}
	
	static function parseRedirectType($status, $type)
	{
		$ticketid = FSS_Input::getInt('ticketid');
		
		$ticket = new SupportTicket();
		if (!$ticket->load($ticketid))
			$ticketid = 0;
		
		if ($type == "" && $ticketid > 0)
			return FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . FSS_Input::getInt('ticketid'), false);
		
		$bits = explode("_", $type);
		
		// 2nd parameter of type is the status, so if current, use the status that has been passed in
		if ($bits[1] == "current")
			$bits[1] = $status;
		
		if (count($bits) != 2)
		{
			if ($ticketid > 0)
			{
				return FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . FSS_Input::getInt('ticketid'), false);
			} else {
				return FSSRoute::_('index.php?option=com_fss&view=admin_support', false);
			}
		}
		
		if ($bits[0] == "list")
			return FSSRoute::_('index.php?option=com_fss&view=admin_support&tickets=' . $bits[1], false);
		
		if ($bits[0] == "new" || $bits[0] == "old")
		{
			// get current tickets for the current handler of a specific type
			JRequest::setVar('tickets', $bits[1]);

			$tickets = new SupportTickets();
			$tickets->limitstart = 0;
			$tickets->limit = 500;
			$tickets->loadTicketsByStatus($bits[1]);
	
			$oldest_time = time();
			$oldest_id = -1;
			
			$newest_time = 0;
			$newset_id = -1;
			
			foreach ($tickets->tickets as $ticket)
			{
				$updated = strtotime($ticket->lastupdate);
				if ($updated > $newest_time)
				{
					$newest_time = $updated;
					$newset_id = $ticket->id;
				}
				if ($updated < $oldest_time)
				{
					$oldest_time = $updated;
					$oldest_id = $ticket->id;
				}
			}
			
			if ($bits[0] == "new" && $newset_id > 0)
				return FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . $newset_id, false);
			
			if ($bits[0] == "old" && $oldest_id > 0)
				return FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . $oldest_id, false);
			
			return FSSRoute::_('index.php?option=com_fss&view=admin_support&tickets=' . $bits[1], false);
		}
		
		if ($ticketid > 0)
			return FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . FSS_Input::getInt('ticketid'));
		
		return FSSRoute::_('index.php?option=com_fss&view=admin_support', false);
	}	
	
	static function getUserTicketCountsForAdmin($userid,$email)
	{
		$db = JFactory::getDBO();
		
		if ($userid)
		{
			$qry = "SELECT count(*) as cnt, ticket_status_id FROM #__fss_ticket_ticket WHERE user_id = '".FSSJ3Helper::getEscaped($db, $userid)."' GROUP BY ticket_status_id";
		} else {
			$qry = "SELECT count(*) as cnt, ticket_status_id FROM #__fss_ticket_ticket WHERE email = '".FSSJ3Helper::getEscaped($db, $email)."' GROUP BY ticket_status_id";
		}
		
		$db->setQuery($qry);
		$rows = $db->loadObjectList();

		$out = array();
		FSS_Ticket_Helper::GetStatusList();
		$out['total'] = 0;
			
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				$out[$row->ticket_status_id] = $row->cnt;
				$out['total'] += $row->cnt;
			}
		}
	
		return $out;	
	}
		
	static $ticket_counts;
	static function getUserTicketCount($user = null)
	{
		if (!$user) $user = JFactory::getUser();
		$userid = $user->get('id');

		if (empty(self::$ticket_counts)) self::$ticket_counts = array();
		if (array_key_exists($userid, self::$ticket_counts)) return self::$ticket_counts[$userid];
		
		$uidlist = self::getUIDS($userid);
		$tidlist = self::getTIDS($userid);

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
			
		self::$ticket_counts[$userid] = $out;
		return self::$ticket_counts[$userid];	
	}
		
	static $user_prod_ids;
	static function getUserProdIDs($user = null)
	{
		// get list of users groups
		$db = JFactory::getDBO();
		
		if (!$user) $user = JFactory::getUser();
		$userid = $user->get('id');

		if (empty(self::$user_prod_ids))
			self::$user_prod_ids = array();
		
		if (array_key_exists($userid, self::$user_prod_ids))
			return self::$user_prod_ids[$userid];
		
		self::$user_prod_ids[$userid] = -1;
		
		$qry = "SELECT * FROM #__fss_ticket_group_members WHERE user_id = '".FSSJ3Helper::getEscaped($db, $userid)."'";
		$db->setQuery($qry);
		$user_groups = $db->loadObjectList('group_id');
		
		$gids = array();
		foreach ($user_groups as $group_id => $group)
		{
			$gids[$group_id] = $group_id;
		}

		if (count($gids) == 0)
			return -1;

		$qry = "SELECT * FROM #__fss_ticket_group WHERE id IN (" . implode(", ", $gids) . ")";
		$db->setQuery($qry);	
		$groups = $db->loadObjectList('id');
		
		// check for all prods
		foreach($groups as $group)
		{
			if ($group->allprods)
			{
				if (FSS_Settings::get('support_restrict_prod'))
				{
					return -1;
				} else {
					unset($gids[$group->id]);
				}
				//return -1;	
			}		
		}
		
		if (count($gids) == 0)
			return -1;
		
		
		$qry = "SELECT prod_id FROM #__fss_ticket_group_prod WHERE group_id IN (" . implode(", ", $gids) . ")";
		$db->setQuery($qry);	
		$prods = $db->loadObjectList('prod_id');
		
		$pids = array();
		foreach($prods as $id => &$prod)
		{
			$pids[$id] = $id;	
		}
		
		self::$user_prod_ids[$userid] = $pids;
	
		return $pids;
	}
	
	static function TimeTaken($message) {
		if ($message->time != 0): ?>
			<span class="<?php if ($message->time < 0): ?> text-error <?php endif; ?>">
				<i class="icon-clock"></i> 
				<?php if ($message->time < 0): ?>-<?php endif; ?>
				<?php 	
					echo "<span style='display:none' class='ticket_time_dur'>" . $message->time . "</span>";
					
					$time = abs($message->time);
					$hours = floor($time / 60);
					$mins = sprintf("%02d",$time % 60);

					if ($message->timestart > 0 && $message->timeend > 0 && $message->timestart < 86400)
					{
						echo "<span style='display:none' class='ticket_time_start'>" . date("H:i", $message->timestart) . "</span>";
						echo "<span style='display:none' class='ticket_time_end'>" . date("H:i", $message->timeend) . "</span>";
						echo "<i class='ticket_time_time'>" . date("H:i", $message->timestart) . " - " . date("H:i", $message->timeend) . "</i> (<b>";
					} else if ($message->timestart > 0 && $message->timeend > 0)
					{
						echo "<span style='display:none' class='ticket_time_start'>" . $message->timestart . "</span>";
						echo "<span style='display:none' class='ticket_time_end'>" . $message->timeend . "</span>";
						echo "<i class='ticket_time_date'>" . FSS_Helper::Date($message->timestart, FSS_DATETIME_SHORT) . " - " . FSS_Helper::Date($message->timeend, FSS_DATETIME_SHORT) . "</i> (<b>";
					} else {
						echo "<span style='display:none' class='ticket_time_hours'>" .$hours . "</span>";
						echo "<span style='display:none' class='ticket_time_mins'>" . $mins . "</span>";
					}
					echo "<span class='ticket_time_duration'>".JText::sprintf("TIME_TAKEN_DISP", $hours, $mins)."</span>";
					if ($message->timestart > 0 && $message->timeend > 0) echo "</b>)";
				?> 
			</span>
			&nbsp;
		<?php endif;
	}
	
	static function attachThumbnail($ticketid, $fileid, $for_user = false)
	{
		$ticket = new SupportTicket();
		if ($ticket->load($ticketid, $for_user))
		{
			$attach = $ticket->getAttach($fileid);
			$image = in_array(strtolower(pathinfo($attach->filename, PATHINFO_EXTENSION)), array('jpg','jpeg','png','gif'));
			
			if (!$image)
				exit;
					
			$image_file = JPATH_SITE.DS.FSS_Helper::getAttachLocation().DS."support".DS.$attach->diskfile;
			$thumb_file = JPATH_SITE.DS.FSS_Helper::getAttachLocation().DS."thumbnail".DS.$attach->diskfile.".thumb";
					
			$thumb_path = pathinfo($thumb_file, PATHINFO_DIRNAME);

			if (!file_exists($thumb_path))
				mkdir($thumb_path, 0755, true);

			require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'files.php');
			FSS_File_Helper::Thumbnail($image_file, $thumb_file);
		}
		
		exit;
	}
	
	static function attachView($ticketid, $fileid, $for_user = false)
	{
		$ticket = new SupportTicket();
		if ($ticket->load($ticketid, $for_user))
		{
			$attach = $ticket->getAttach($fileid);
			$image = in_array(strtolower(pathinfo($attach->filename, PATHINFO_EXTENSION)), array('jpg','jpeg','png','gif'));
			
			if (!$image)
				exit;
					
			$image_file = JPATH_SITE.DS.FSS_Helper::getAttachLocation().DS."support".DS.$attach->diskfile;
					
			require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'files.php');
			FSS_File_Helper::OutputImage($image_file, pathinfo($attach->filename, PATHINFO_EXTENSION));
		}
		
		exit;
	}
	
	static function attachDownload($ticketid, $fileid, $for_user = false)
	{
		$ticket = new SupportTicket();
		if ($ticket->load($ticketid, $for_user))
		{
			$attach = $ticket->getAttach($fileid);
			
			if (substr($attach->diskfile, 0, 7) == "http://" || substr($attach->diskfile, 0, 8) == "https://")
			{
				header('Location: ' . $attach->diskfile);
				exit;
			}
			
			$file = JPATH_SITE.DS.FSS_Helper::getAttachLocation().DS."support".DS.$attach->diskfile;
					
			$display_filename = FSS_Helper::basename($attach->filename);
			
			if (!$for_user)
			{
				$user = JFactory::GetUser($attach->user_id);      
				$type = FSS_Settings::get('support_filename');
				switch ($type)
				{
					case 1:
						$display_filename = $user->username . "_" . $display_filename;
						break;
					case 2:
						$display_filename = $user->username . "_" . date("Y-m-d") . "_" . $display_filename;
						break;	
					case 3:
						$display_filename = date("Y-m-d") . "_" . $user->username . "_" . $display_filename;
						break;	
					case 4:
						$display_filename = date("Y-m-d") . "_" . $display_filename;
						break;	
				}
			}
		
			require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'files.php');
			FSS_File_Helper::DownloadFile($file, $display_filename);
		}
		
		exit;
	}
	
		
	static $multiuser = array();
	static $uidlist = array();
	static $tid_list = array();	
	static function getTIDS($user_id)
	{
		if (array_key_exists($user_id, self::$tid_list))
			return self::$tid_list[$user_id];

		$db = JFactory::getDBO();
		
		$qry = "SELECT ticket_id FROM #__fss_ticket_cc WHERE user_id = '".FSSJ3Helper::getEscaped($db,  $user_id)."' AND isadmin = 0";
		$db->setQuery($qry);
		$rows = $db->loadAssocList();
		$tidlist = array();
		
		foreach ($rows as $row)
		{
			$tidlist[$row['ticket_id']] = $row['ticket_id'];	
			self::$multiuser[$user_id] = 1;
		}		
		
		if (count($tidlist) == 0)
			$tidlist[] = 0;
			
		self::$tid_list[$user_id] = $tidlist;
		
		return self::$tid_list[$user_id];
	}

	static function getProdIDS($user_id)
	{
		$db = JFactory::getDBO();

		$query = " SELECT * FROM #__fss_ticket_group_members as m LEFT JOIN #__fss_ticket_group AS g on m.group_id = g.id ";
		$query .= " WHERE user_id = '".$db->escpae($user_id)."'";
		
		$db->setQuery($query);
		$groups = $db->loadObjectList('group_id');

		if (!$groups || count($groups) < 1) return true;

		$gids = array();

		foreach ($groups as $group)
		{
			if ($group->allprods) return true;

			$gids[] = $group->id;
		}

		$query = "SELECT prod_id FROM #__fss_ticket_group_prod WHERE group_id IN (" . implode($gids) . ")";		
		$db->setQuery($query);
		$prodids = $db->loadColumn(0);
		if (!is_array($prodids)) $prodids = array(0);
		//$prodids[] = 0;
		return $prodids;
	}
	
	static function getUIDS($user_id)
	{
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
		self::$multiuser[$user_id] = 1;	        
		
		return self::$uidlist[$user_id];   		
	}
	
	static function userIdMultiUser($user_id)
	{
		if (empty(self::$uidlist[$user_id])) self::getUIDS($user_id);
		if (empty(self::$tid_list[$user_id])) self::getTIDS($user_id);
		
		if (isset(self::$multiuser[$user_id])) return self::$multiuser[$user_id];
		
		return 0;
	}
	
	static function getBaseSQL()
	{
		$query = "SELECT t.*, s.title as status, s.color, u.name, au.name as assigned, u.email as useremail, u.username as username, au.email as handleremail, au.username as handlerusername, ";
		$query .= " dept.title as department, cat.title as category, prod.title as product, prod.description as proddesc, dept.description as deptdesc, pri.title as priority, pri.color as pricolor, ";
		$query .= " grp.groupname as groupname, grp.id as group_id, s.is_closed as is_closed, ";
		$query .= " pri.translation as pritr, dept.translation as dtr, s.translation as str, cat.translation as ctr, prod.translation as prtr, ";
		$query .= " prod.image as prod_img, dept.image as dept_img ";
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
	
		return $query;
	}
	
	
	static function messageRating($message, $inline = false, $always_change = false)
	{
		$url = JRoute::_('index.php?option=com_fss&view=ticket&task=update.rating');
		return self::ratingChoose($message->rating, $message->id, $url, $inline, $always_change, "CLICK_TO_RATE_MESSAGE");
	}

	static function ticketRating($ticket, $inline = false, $always_change = false, $nothanks = false)
	{
		$url = 'index.php?option=com_fss&view=ticket&task=update.ticketrating';
		if ($nothanks) $url .= "&nothanks=1";
		return self::ratingChoose($ticket->rating, $ticket->id, JRoute::_($url), $inline, $always_change, "CLICK_TO_RATE_TICKET");
	}
	
	static function ratingChoose($rating, $ident, $url, $inline, $always_change, $text)
	{
		$output = array();
		
		$can_rate = ($rating == 0 || $always_change);
		
		$class = $can_rate ? "can_rate" : "";

		if (!$inline) $output[] = "<div class='fss_ticket_rating' style='display: inline-block;' id='{$ident}' url='" . $url . "' wait='" . JText::_('PLEASE_WAIT') . "'>";
		if ($can_rate)
		{
			$output[] = "<a class='fssTip' href='#' onclick='return false;' title='". JText::_($text)."'>";
		} else {
			$output[] = "<a onclick='return false;'>";
		}
	
		for ($i = 0 ; $i < $rating ; $i++)
		{
			$rate = $i+1;
			$output[] = "<span class='icon-star lit rating $class' rating='$rate'></span>";	
		}
		
		for ($i = $rating ; $i < 5 ; $i++)
		{
			$rate = $i+1;
			$output[] = "<span class='icon-star unlit rating $class' rating='$rate'></span>";	
		}
		
		$output[] = "</a>";
		if (!$inline) $output[] = "</div>";
		
		JFactory::getDocument()->addScript(JURI::root(true).'/components/com_fss/assets/js/rating.js'); 

		return implode($output);
	}
		
	static function displayRating($rating, $showvalue = true)
	{
		$output = array();

		$output[] = "<div class='fss_ticket_rating' style='display: inline-block;'>";
		
		if ($showvalue) $output[] = "$rating &nbsp; ";
		$output[] = "<a>";
		
		$rating = round($rating, 0);
		
		for ($i = 0 ; $i < $rating ; $i++)
		{
			$rate = $i+1;
			$output[] = "<span class='icon-star lit rating' rating='$rate'></span>";	
		}
		
		for ($i = $rating ; $i < 5 ; $i++)
		{
			$rate = $i+1;
			$output[] = "<span class='icon-star unlit rating' rating='$rate'></span>";	
		}
		
		$output[] = "</a></div>";
		
		return implode($output);	
	}
	
	
	
}