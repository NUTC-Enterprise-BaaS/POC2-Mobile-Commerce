<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_helper.php');
require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'permission.php' );

class SupportUsers
{
	static $ftojmap = array();
	static $users = array();
	
	static function getUser($id = null)
	{
		if (!$id )
			$id = JFactory::getUser()->id;
		
		if ($id == 0)
		{
			if (empty(self::$users[$id]))
			{
				$user = new stdClass();
				$user->id = $id;
				$user->name = "";
				$user->username = "";
				$user->email = "";
				$user->user_id = 0;
				$user->settings = self::BlankSettings();	
				$user->rules = new stdClass();
			
				self::$users[$id] = $user;
			}
			return self::$users[$id];
		}
		
		if (empty(self::$users[$id]))
		{
			$db = JFactory::getDBO();
		
			$qry = "  SELECT u.id, u.name, u.username, u.email, f.settings, f.rules, f.user_id ";
			$qry .= " FROM #__users as u";
			$qry .= " LEFT JOIN #__fss_users as f ON u.id = f.user_id ";
			$qry .= " WHERE u.id = " . (int)$id;
			
			$db->setQuery($qry);
			$obj = $db->loadObject();
			
			if (!$obj)
			{
				$obj = new stdClass();
				$obj->settings = "";
				$obj->rules = "";
			}

			if ($obj->settings == "")
			{
				$obj->settings = self::BlankSettings();	
			} else {
				$obj->settings = json_decode($obj->settings);
			}
			
			if (!property_exists($obj->settings, "out_of_office"))
				$obj->settings->out_of_office = 0;

			$obj->rules = json_decode($obj->rules);

			self::$users[$id] = $obj;
		}
		
		return self::$users[$id];
	}
	
	static $user_defaults;
	static function BlankSettings()
	{
		if (empty(self::$user_defaults))
		{
			self::$user_defaults = new stdClass();
			self::$user_defaults->per_page = 10;
			self::$user_defaults->group_products = 0;
			self::$user_defaults->group_departments = 0;
			self::$user_defaults->group_cats = 0;
			self::$user_defaults->group_group = 0;
			self::$user_defaults->group_pri = 0;
			self::$user_defaults->return_on_reply = '';
			self::$user_defaults->return_on_close = '';
			self::$user_defaults->reverse_order = 0;
			self::$user_defaults->default_sig = 0;
			self::$user_defaults->out_of_office = 0;
			self::$user_defaults->reports_separator = '';

			// load user default from plugin if needed
			$db = JFactory::getDBO();
			$sql = "SELECT * FROM #__fss_plugins WHERE type = 'gui' AND name = 'default_prefs' AND enabled = 1";
			$db->setQuery($sql);
			
			$settings = $db->loadObject();
			if ($settings)
			{
				$settings = json_decode($settings->settings, true);
				if ($settings)
				{
					foreach ($settings['prefs'] as $pref => $value)
					{
						self::$user_defaults->$pref = $value;	
					}
				}
			}
		}
		return self::$user_defaults;
	}
	
	static function getAllSettings($id = null)
	{
		$user = self::getUser($id);
		
		return $user->settings;	
	}

	static $settings_or = array();
	
	static function getSetting($setting, $id = null)
	{
		$user = self::getUser($id);

		if (array_key_exists($setting, static::$settings_or))
			return static::$settings_or[$setting];

		if (property_exists($user->settings, $setting))
			return $user->settings->$setting;
			
		return "";
	}

	static function setSetting($setting, $value, $id = null)
	{
		static::$settings_or[$setting] = $value;
	}
	
	static function updateUserSettings($settings, $id = null)
	{
		$user = self::getUser($id); 	
		
		$db = JFactory::getDBO();
		
		if ($user->user_id > 0)
		{
			// we have a row existing, so update it
			$qry = "UPDATE #__fss_users SET settings = '" . $db->escape(json_encode($settings)) . "' WHERE user_id = " . (int)$user->id;
		} else {
			$qry = "REPLACE INTO #__fss_users (user_id, settings) VALUES (" . (int)$user->id . ", '" . $db->escape(json_encode($settings)) . "')";
		}

		$db->setQuery($qry);
		$db->Query();
	}
	
	static function updateUserPermissions($permissions, $id = null)
	{	
		$user = self::getUser($id); 	
		
		$db = JFactory::getDBO();
		
		if ($user->user_id > 0)
		{
			// we have a row existing, so update it
			$qry = "UPDATE #__fss_users SET rules = '" . $db->escape(json_encode($permissions)) . "' WHERE user_id = " . (int)$user->id;
		} else {
			$qry = "REPLACE INTO #__fss_users (user_id, rules) VALUES (" . (int)$user->id . ", '" . $db->escape(json_encode($permissions)) . "')";
		}

		$db->setQuery($qry);
		$db->Query();
	}
	
	static function updateSingleSetting($setting, $value, $id = null)
	{
		$user = self::getUser($id); 	
		$user->settings->$setting = $value;
		self::updateUserSettings($user->settings, $user->id);
	}
	
	static $misc_perms = array();
	static function usersWithPerm($set, $perm, $explicit = false)
	{
		$set = str_replace("com_fss.", "", $set);
		
		// doesnt use the assets inherited permissions
		$key = "$set-$perm-" . (int)$explicit;
		
		//echo "Key : $key<br>";
		
		if (empty(self::$misc_perms[$key]))
		{
			self::$misc_perms[$key] = array();
			
			$db = JFactory::getDBO();
			$qry = "SELECT lft, rgt FROM #__assets WHERE name = 'com_fss.{$set}'";
			$db->setQuery($qry);
			$asset = $db->loadObject();

			$assets = array();
			
			if ($asset)
			{
				// need to load in all parent groups and merge in the rules FUCK!
				$qry = "SELECT * FROM #__assets WHERE lft <= {$asset->lft} AND rgt >= {$asset->rgt} ORDER BY level DESC";
				$db->setQuery($qry);
				$assets = $db->loadObjectList();
			}
			if (!$assets)
				$assets = array();
			
			$groups = array();
			$denied = array();
			
			foreach ($assets as $asset)
			{
				//echo "Sub Asset : {$asset->name}<br >";
				$arules = json_decode($asset->rules);
				if (isset($arules->$perm))
				{
					foreach ($arules->$perm as $gid => $allowed)
					{
						if ($allowed)
						{
							$groups[$gid] = $gid;	
						} else {
							$denied[$gid] = $gid;	
						}
					}
				}
			}
			
			foreach ($denied as $gid)
				unset($groups[$gid]);
			
			$uids = array();
			$final_gids = array();
			
			// need to load in all child groups for the groups in the list!
			$qry = "SELECT * FROM #__usergroups";
			$db->setQuery($qry);
			$all_groups = $db->loadObjectList('id');

			if (count($groups) > 0)
			{
				foreach ($groups as $gid)
				{
					$final_gids[$gid] = $gid;
				
					$this_group = $all_groups[$gid];
				
					//echo "Group : {$this_group->title} - {$this_group->lft} -> {$this_group->rgt}<br>";
				
					foreach ($all_groups as $child_group)
					{
						if (testRange($child_group->lft, $this_group->lft, $this_group->rgt ) &&
							testRange($child_group->rgt, $this_group->lft, $this_group->rgt))
						{
							//echo "Adding {$child_group->id} as child of $gid<br>";
							$final_gids[$child_group->id] = $child_group->id;
						}
					}	
				}
			}	
			
			// Not sure if to include super users in this or not? They wont have auto assign set
			if (!$explicit)
			{
				foreach ($all_groups as $group)
				{
					if ($group->title == "Super Users")
						$final_gids[$group->id] = $group->id;
				}
			}

			if (isset($final_gids[0])) unset($final_gids[0]);
			
			if (count($final_gids) > 0)
			{
				$qry = "SELECT user_id FROM #__user_usergroup_map WHERE group_id IN (" . implode(", ", $final_gids) . ")";
				$db->setQuery($qry);
				$result = $db->loadObjectList();
				foreach ($result as $row)
					$uids[$row->user_id] = $row->user_id;	
			}
			
			$qry = "SELECT user_id, rules FROM #__fss_users WHERE rules != ''";
			$db->setQuery($qry);
			$users = $db->loadObjectList();
			
			foreach ($users as $user)
			{
				$user->rules = json_decode($user->rules);
				if (isset($user->rules->$set->$perm) && $user->rules->$set->$perm)
					$uids[$user->user_id] = $user->user_id;
			}
			
			if (count($uids) > 0)
			{
					
				$qry = "  SELECT u.id, u.name, u.username, u.email, f.settings, f.rules, f.user_id ";
				$qry .= " FROM #__users as u";
				$qry .= " LEFT JOIN #__fss_users as f ON u.id = f.user_id ";
				$qry .= " WHERE u.id IN (" . implode(", " , $uids) . ") AND u.block = 0";
			
				$db->setQuery($qry);
				$objs = $db->loadObjectList();
				foreach ($objs as $obj)
				{
					if ($obj->settings == "")
					{
						$obj->settings = self::BlankSettings();	
					} else {
						$obj->settings = json_decode($obj->settings);
					}
					$obj->rules = json_decode($obj->rules);
			
					if (isset($obj->rules->$set->$perm) &&
						!$obj->rules->$set->$perm)
							continue;
						
					self::$users[$obj->id] = $obj;
					self::$misc_perms[$key][$obj->id] = $obj;
				}
			}
		}
		
		return self::$misc_perms[$key];
	}
	
	static $all_handlers = array();
	static function getHandlers($explicit = false, $use_out_of_office = true)
	{
		// HOW! - There has to be a better way that this!
		$key = 0;
		if ($explicit) $key = 1;
		if ($use_out_of_office) $key += 2;
		
		if (empty(self::$all_handlers))
			self::$all_handlers = array();
		
		if (!array_key_exists($key, self::$all_handlers))
		{
			if ($use_out_of_office)
			{
				$users = self::usersWithPerm("support_admin", "fss.handler", $explicit);
				self::$all_handlers[$key] = array();
				
				foreach ($users as $user)
				{
					if (!empty($user->settings) && !empty($user->settings->out_of_office) && $user->settings->out_of_office)
						continue;
					
					self::$all_handlers[$key][] = $user;
				}
			} else {
				self::$all_handlers[$key] = self::usersWithPerm("support_admin", "fss.handler", $explicit);
			}
		}
		
		if (FSS_Settings::get('support_hide_super_users'))
		{
			foreach (self::$all_handlers[$key] as $user_id => $user)
			{
				
				$user = JFactory::getUser($user->id);
				if ($user->authorise('core.admin')) 
				{
					unset(self::$all_handlers[$key][$user_id]);
				}
			}
		}

		return self::$all_handlers[$key];	
	}
	
	static function getHandler($user_id, $explicit = false, $use_out_of_office = true)
	{
		$key = 0;
		if ($explicit) $key = 1;
		if ($use_out_of_office) $key += 2;

		if (!array_key_exists($key, self::$all_handlers))
			self::getHandlers($explicit, $use_out_of_office);
		
		if (array_key_exists($user_id, self::$all_handlers[$key]))
			return self::$all_handlers[$key][$user_id];
		
		return null;
	}

	static function getUserName($user_id)
	{
		$user = self::getUser($user_id);
		return $user->name . " (" . $user->username . ")";
	}
	
	static function getAdminWhere()
	{
		$user = self::getUser();
		
		if (!FSS_Permission::auth("fss.handler", "com_fss.support_admin"))
			return "0";
		
		// can always view own tickets
		$where = " (t.admin_id = {$user->id} OR ";
		$where .= " t.id IN (SELECT ticket_id FROM #__fss_ticket_cc WHERE user_id = {$user->id} AND isadmin = 1) OR ";
		$where .= " (";
		
		// now need to filter by handler. we have several and sections here
		$inner = array();
		
		// users
		$can_see_unassigned = FSS_Permission::auth("fss.handler.seeunassigned", "com_fss.support_admin");
		$can_see_others = FSS_Permission::auth("fss.handler.seeothers", "com_fss.support_admin");
		
		if (!$can_see_unassigned && !$can_see_others) // neither
		{
			$inner[] = "0";	
		} elseif (!$can_see_unassigned && $can_see_others) { // only others, not unassigned
			$inner[] = "t.admin_id > 0";
		} elseif ($can_see_unassigned && !$can_see_others) {
			$inner[] = "t.admin_id = 0";
		}
		
		// products
		if (!FSS_Permission::auth("fss.handler.view.products", "com_fss.support_admin"))
		{
			$prod_ids = array();
			$prod_ids[0] = "0";
			$products = SupportHelper::getProducts();
			
			foreach ($products as $product)
			{
				if (FSS_Permission::auth("fss.handler.view.product." . $product->id, "com_fss.support_admin"))
				{
					$prod_ids[$product->id] = $product->id;
				}
			}
			
			$inner[] = " t.prod_id IN (" . implode(", ", $prod_ids) . ")";
		}
		
		// departments
		if (!FSS_Permission::auth("fss.handler.view.departments", "com_fss.support_admin"))
		{
			$dept_ids = array();
			$dept_ids[0] = "0";
			$depts = SupportHelper::getDepartments();
			
			foreach ($depts as $dept)
			{
				if (FSS_Permission::auth("fss.handler.view.department." . $dept->id, "com_fss.support_admin"))
				{
					$dept_ids[$dept->id] = $dept->id;
				}
			}
			
			$inner[] = " t.ticket_dept_id IN (" . implode(", ", $dept_ids) . ")";
		}
			
		// categories	
		if (!FSS_Permission::auth("fss.handler.view.categories", "com_fss.support_admin"))
		{
			$cat_ids = array();
			$cat_ids[0] = "0";
			$cats = SupportHelper::getCategories();
			
			foreach ($cats as $cat)
			{
				if (FSS_Permission::auth("fss.handler.view.category." . $cat->id, "com_fss.support_admin"))
				{
					$cat_ids[$cat->id] = $cat->id;
				}
			}
			
			$inner[] = " t.ticket_cat_id IN (" . implode(", ", $cat_ids) . ")";
		}
						
		if (count($inner) == 0)
			$inner[] = "1";
			
		$where .= implode(" AND ", $inner);
			
		$where .= " ))";
		
		return $where;
	}
	
	static function getUsersWhere($userid = null)
	{
		if (!is_numeric($userid)) $userid = JFactory::getUser()->id;
		//echo "Getting users where<br>";

		if ($userid > 0)
		{
			
			$uidlist = SupportHelper::getUIDS($userid);
			$tidlist = SupportHelper::getTIDS($userid);

			$ors = array();
			
			if (count($uidlist) > 0) $ors[] = "t.user_id IN (" . implode(", ",$uidlist) . ")";
			if (count($tidlist) > 0) $ors[] = "t.id IN (" . implode(", ",$tidlist) . ")";

			$sql = " ( ( " . implode(" OR ", $ors) . " ) AND " . SupportSource::user_list_sql();

			if (FSS_Settings::get('support_restrict_prod_view'))
			{
				$prodlist = SupportHelper::getProdIDS($userid);
				if ($prodlist !== true)
				{
					$sql .= " AND t.prod_id IN (" . implode($prodlist, ", ") . ") ";
				}
			}

			$sql .= " ) ";

			//echo $sql . "<br>";
			return $sql;
		} else {
			return " 0 ";	
		}
	}
	
	
	static function getHandlersTicket($prodid, $deptid, $catid, $allownoauto = false, $assign_ticket = true, $explicit = false, $use_out_of_office = true) 
	{
		$users = self::getHandlers($explicit, $use_out_of_office);

		$okusers = array();
		
		foreach ($users as $user)
		{
			$perm_set = "fss.handler.view";
			if (FSS_Permission::auth("fss.handler.assign.separate", "com_fss.support_admin", $user->id) && $assign_ticket)
				$perm_set = "fss.handler.assign";
			
			if (FSS_Permission::auth("fss.handler.dontassign", "com_fss.support_admin", $user->id) && !$allownoauto)
				continue;	
			
			if ($prodid > 0 && !FSS_Permission::auth($perm_set.".products", "com_fss.support_admin", $user->id))
				if (!FSS_Permission::auth($perm_set.".product.". $prodid, "com_fss.support_admin", $user->id))
					continue;	
			
			if ($deptid > 0 && !FSS_Permission::auth($perm_set.".departments", "com_fss.support_admin", $user->id))
				if (!FSS_Permission::auth($perm_set.".department.". $deptid, "com_fss.support_admin", $user->id))
					continue;
					//echo "Skip : Not department<br>";
			
			if ($catid > 0 && !FSS_Permission::auth($perm_set.".categories", "com_fss.support_admin", $user->id))
				if (!FSS_Permission::auth($perm_set.".category.". $catid, "com_fss.support_admin", $user->id))
					continue;
					//echo "Skip : Not category<br>";
				
			$okusers[] = $user->id;
		}

		return $okusers;
	}
}

if (!function_exists("testRange"))
{
	function testRange($int,$min,$max)
	{
		if($int>$min && $int<$max)
			return true;
		else
			return false;
	}	
}