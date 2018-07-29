<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @deprecated 3539e66e8f4c86015bc390f5ff589a75
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');

class FssViewPlugin extends FSSView
{
	function display($tpl = null)
	{
		$type = FSS_Input::getCmd('type');
		$name = FSS_Input::getCmd('name');
		
		$plugin_file = JPATH_SITE.DS."components".DS."com_fss".DS."plugins".DS.$type.DS.$name.".php";
		
		if (!file_exists($plugin_file))
			return;
		
		require_once($plugin_file);
		
		switch ($type)
		{
			case 'cron':
				$class = "FSSCronPlugin" . $name;
				break;
			case 'custfield':
				$class = $name."Plugin";
				break;
			case 'gui':
				$class = "FSS_GUIPlugin_" . $name;
				break;
			case 'tickets':
				$class = "SupportActions" . $name;
				break;
			case 'ticketsource':
				$class = "Ticket_Source_" . $name;
				break;
			case 'userlist':
				$class = "User_List_" . $name;
				break;
		}
		
		if (!class_exists($class))
			return;
		
		$plugin = new $class();
		$plugin->name = $name;
		$plugin->type = $type;
		$plugin->process();
		
		parent::display();	
	}
}
