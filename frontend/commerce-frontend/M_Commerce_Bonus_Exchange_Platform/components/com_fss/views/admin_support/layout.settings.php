<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'task.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_helper.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_users.php');

class FssViewAdmin_Support_Settings extends FssViewAdmin_Support
{
	function display($tpl = NULL)
	{
		$action = FSS_Input::getCmd('action');
		
		if ($action == "cancel")
		{
			$mainframe = JFactory::getApplication();
			$link = FSSRoute::_('index.php?option=com_fss&view=admin_support',false);
			$mainframe->redirect($link);	
			
			return;		
		}
		
		if ($action == "save" || $action == "apply")
		{
			$all = array(
					'per_page', 'group_products', 'group_departments', 'group_cats', 'group_group', 'group_pri', 'return_on_reply', 'return_on_close', 'reverse_order',
					'reports_separator'
				);
			
			$values = array();
			
			$values = SupportUsers::getAllSettings();

			foreach ($all as $setting)
			{
				$new = FSS_Input::getString($setting, 0);
				$values->$setting = $new;
			}
			
			$mainframe = JFactory::getApplication();
			$mainframe->setUserState('global.list.limit_ticket', $values->per_page);

			SupportUsers::updateUserSettings($values);
			
			if ($action == "save")
			{
				$link = FSSRoute::_('index.php?option=com_fss&view=admin_support',false);
			} else {
				$link = FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=settings',false);
			}
			$mainframe = JFactory::getApplication();
			$mainframe->redirect($link,JText::_('SETTINGS_SAVED'));			
			return;
		}
		
		$this->_display();
	}	
	
	function getReturnViewOptions($current)
	{
		$statuss = SupportHelper::getStatuss();
		
		$temp = new stdClass();
		$temp->id = "closed";
		$temp->title = JText::_("Closed");
		$statuss[] = $temp;
		
		$temp = new stdClass();
		$temp->id = "allopen";
		$temp->title = JText::_("ALL_OPEN");
		$statuss[] = $temp;
	
		$temp = new stdClass();
		$temp->id = "all";
		$temp->title = JText::_("All");
		$statuss[] = $temp;
			
		$output[] = "<option value=''>".JText::_('SHOW_CURRENT_TICKET')."</option>";
		
		$output[] = "<optgroup label='".JText::_('SHOW_TICKET_LIST').":'>";
		
		$selected = "";
		if ($current == "list_current") $selected = "selected";
		$output[] = "<option value='list_current' {$selected}>".JText::_('LIST') . ": ".JText::_('CURRENT_TICKETS_STATUS')."</option>";
		
		foreach ($statuss as $status)
		{
			$selected = "";
			if ($current == "list_" . $status->id) $selected = "selected";
			$output[] = "<option value='list_" . $status->id . "' {$selected}>".JText::_('LIST') . ": " . $status->title . "</option>";
		}
		$output[] = "</optgroup>";
		
		
		$output[] = "<optgroup label='".JText::_('SHOW_NEWEST_TICKET').":'>";
		
		$selected = "";
		if ($current == "new_current") $selected = "selected";
		$output[] = "<option value='new_current' {$selected}>".JText::_('NEWEST') . ": ".JText::_('CURRENT_TICKETS_STATUS')."</option>";
		
		foreach ($statuss as $status)
		{
			$selected = "";
			if ($current == "new_" . $status->id) $selected = "selected";
			$output[] = "<option value='new_" . $status->id . "' {$selected}>".JText::_('NEWEST') . ": " . $status->title . "</option>";
		}
		$output[] = "</optgroup>";
		
		$output[] = "<optgroup label='".JText::_('SHOW_OLDEST_TICKET').":'>";
		
		$selected = "";
		if ($current == "old_current") $selected = "selected";
		$output[] = "<option value='old_current' {$selected}>".JText::_('OLDEST') . ": ".JText::_('CURRENT_TICKETS_STATUS')."</option>";
		foreach ($statuss as $status)
		{
			$selected = "";
			if ($current == "old_" . $status->id) $selected = "selected";
			$output[] = "<option value='old_" . $status->id . "' {$selected}>".JText::_('OLDEST') . ": " . $status->title . "</option>";
		}
		$output[] = "</optgroup>";
		
		return implode($output);
	}	
}