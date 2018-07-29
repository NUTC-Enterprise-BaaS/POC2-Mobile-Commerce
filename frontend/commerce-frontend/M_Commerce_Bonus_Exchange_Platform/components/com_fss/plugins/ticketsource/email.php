<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class Ticket_Source_EMail extends Ticket_Source
{
	var $name = "EMail Pending";
	
	var $user_show = false;
	var $admin_show = false;	
	
	static $tab_result;
	
	function getTabs()
	{
		$db = JFactory::getDBO();
		
		if (!self::$tab_result)
		{
			$qry = "SELECT count(*) as cnt FROM #__fss_ticket_ticket WHERE source = 'email'";
			$db->setQuery($qry);
			$cnt = $db->loadObject();

			if ($cnt->cnt > 0 || FSS_Input::getCmd('layout') == "emails")
			{		
				$tab = new Ticket_Source_Data();
				$tab->tabname = JText::sprintf("SUP_SRC_EMAILS", $cnt->cnt);
				$tab->name = JText::_("SUP_SRC_EMAIL");
				$tab->count = $cnt->cnt;
				$tab->link = "index.php?option=com_fss&view=admin_support&layout=emails";
				
				if (FSS_Input::getCmd('layout') == "emails")
					$tab->active = true;
				
				self::$tab_result = array($tab);
			} else {
				self::$tab_result = array();
			}
		}
		
		return self::$tab_result;
	}
	
	function getOverview_ListItem()
	{
		return $this->getTabs();
	}
	
	function getMainMenu_ListItem()
	{
		return $this->getTabs();
	}	
	
	function getMainMenu_Module_Admin_ListItem()
	{
		return $this->getTabs();
	}
}
