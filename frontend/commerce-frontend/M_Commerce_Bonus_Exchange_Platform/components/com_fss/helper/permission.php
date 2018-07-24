<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_users.php');

class FSS_Permission
{
	static $user_perms;
	static $user_data;
	
	static function auth($action, $asset, $user = null)
	{
		//echo "Auth : $action ($asset) = ";
		$user_obj = JFactory::getUser($user);
		
		$inh = $user_obj->authorise($action, $asset);	
		
		// Guest user, so always use the inherited permissions
		if ($user_obj->id == 0)
		{
			//echo "I : " . $inh . "<br>";
			return $inh;
		}
		
		$user_perms = self::loadUser($user_obj->id);

		$user_set = str_replace("com_fss.", "", $asset);
		
		if (!property_exists($user_perms, $user_set))
		{
			//echo "K : " . $inh . "<br>";
			return $inh;	
		}
		
		if (!property_exists($user_perms->$user_set, $action))
		{
			//echo "M : " . $inh . "<br>";
			return $inh;	
		}
		
		//echo "D : " . $user_perms->$user_set->$action . "<br>";
		return $user_perms->$user_set->$action;
	}	
	
	static function loadUser($userid)
	{
		if (empty(self::$user_perms[$userid]))
		{
			$user_data = SupportUsers::getUser($userid);
			
			if (!$user_data)
			{
				$perms = new stdClass();
			} else {
				$perms = $user_data->rules;
			}
			
			if (!is_object($perms))
				$perms = new stdClass();
			
			if (!property_exists($perms, "support_admin"))
				$perms->support_admin = new stdClass();
			
			self::mergeSet($perms, "support_admin", "support_admin_misc");
			self::mergeSet($perms, "support_admin", "support_admin_ticket");
			self::mergeSet($perms, "support_admin", "support_admin_ticket_cc");
			self::mergeSet($perms, "support_admin", "support_admin_ticket_other");
			self::mergeSet($perms, "support_admin", "support_admin_ticket_una");
			self::mergeSet($perms, "support_admin", "view_products");
			self::mergeSet($perms, "support_admin", "view_departments");
			self::mergeSet($perms, "support_admin", "view_categories");
			self::mergeSet($perms, "support_admin", "assign_products");
			self::mergeSet($perms, "support_admin", "assign_departments");
			self::mergeSet($perms, "support_admin", "assign_categories");
						
			self::$user_perms[$userid] = $perms;
			
		}	

		return self::$user_perms[$userid];	
	}

	static function mergeSet(&$perms, $target, $source)
	{
		if (!isset($perms->$source))
			return;
		
		foreach ($perms->$source as $key => $value)
		{
			$perms->$target->$key = $value;	
		}
		
		unset($perms->$source);
	}
	
		
	static function PermAnyContent()
	{
		if (FSS_Permission::auth("core.edit.own", "com_fss.faq") ||
			FSS_Permission::auth("core.edit.own", "com_fss.kb") ||
			FSS_Permission::auth("core.edit.own", "com_fss.announce") ||
			FSS_Permission::auth("core.edit.own", "com_fss.glossary") ||
			FSS_Permission::auth("core.edit", "com_fss.faq") ||
			FSS_Permission::auth("core.edit", "com_fss.kb") ||
			FSS_Permission::auth("core.edit", "com_fss.announce") ||
			FSS_Permission::auth("core.edit", "com_fss.glossary"))
			return true;
		return false;
	}	
	
	static function PermOthersContent()
	{
		if (FSS_Permission::auth("core.edit", "com_fss.faq") ||
			FSS_Permission::auth("core.edit", "com_fss.kb") ||
			FSS_Permission::auth("core.edit", "com_fss.announce") ||
			FSS_Permission::auth("core.edit", "com_fss.glossary"))
			return true;
		return false;
	}
	
	static $group_id_access = array();
	static function AdminGroups()
	{
		if (FSS_Permission::auth("fss.groups", "com_fss.groups"))
			return true;
		
		// otherwise we need to build a list of group ids that we are manager for
		$user = JFactory::getUser();
		$userid = $user->id;
		
		$qry = "SELECT group_id FROM #__fss_ticket_group_members WHERE user_id = '$userid' AND isadmin = 1";
		$db = JFactory::getDBO();
		
		$db->setQuery($qry);
		self::$group_id_access = $db->loadObjectList('group_id');
			
		if (count(self::$group_id_access) < 1)
			return false;
			
		foreach (self::$group_id_access as $id => &$temp)
			self::$group_id_access[$id] = $id;
		
		return true;
	}
	
	static function OnlyGroups()
	{
		if (!FSS_Permission::PermAnyContent() && 
			!FSS_Permission::auth("fss.handler", "com_fss.support_admin") && 
			!FSS_Permission::auth("fss.reports", "com_fss.reports") && 
			!FSS_Permission::CanModerate())
			return true;
		
		return false;
	}
	
	static function CanModerate()
	{
		if (FSS_Permission::auth("fss.mod.all", "com_fss.moderation") ||
			FSS_Permission::auth("fss.mod.1", "com_fss.moderation") ||
			FSS_Permission::auth("fss.mod.4", "com_fss.moderation") ||
			FSS_Permission::auth("fss.mod.5", "com_fss.moderation"))
			return true;
		return false;
	}
	
	static function AnyAdmin()
	{
		if (self::CanModerate())
			return true;
		if (self::AdminGroups())
			return true;
		if (self::PermAnyContent())
			return true;	
		if (FSS_Permission::auth("fss.handler", "com_fss.support_admin"))
			return true;
		if (FSS_Permission::auth("fss.reports", "com_fss.reports"))
			return true;
		
		return false;
	}

	static function AllowSupport()
	{
		if (FSS_Permission::auth("fss.handler", "com_fss.support_admin"))
			return true;

		$access = FSS_Settings::get('support_access_level');

		$levels = JFactory::getUser()->getAuthorisedViewLevels();

		return in_array($access, $levels);
	}

	static function AllowSupportOpen()
	{
		if (FSS_Permission::auth("fss.handler", "com_fss.support_admin"))
			return true;

		if (FSS_Settings::Get('support_only_admin_open'))
			return false;

		if (!FSS_Permission::auth("fss.ticket.open", "com_fss.support_user"))
			return false;

		$access = FSS_Settings::get('support_open_access_level');

		$levels = JFactory::getUser()->getAuthorisedViewLevels();

		return in_array($access, $levels);
	}
}