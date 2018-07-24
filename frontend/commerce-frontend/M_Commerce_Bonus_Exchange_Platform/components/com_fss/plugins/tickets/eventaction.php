<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

/**
 * Handle all ticket events from the ticket plugins we have available
 * 
 * Included here is a full list of the calls that can be made to this plugin, inluding some example code.
 * 
 * Please do not use this file for your plugins, make a copy of the parts you require, and change the class name to
 * match your file name, ie SupportActions{filename}
 * 
 * You will need to enable your new plugin in the Plugins page (Components -> Freestyle Support -> Overview -> Plugins)
 */

class SupportActionsEventAction extends SupportActionsPlugin
{
	var $title = "Freestlye Event Action System";
	var $description = "Enable this to allow calls to the Freestyle Event Action System";

	static function event_Init($type, $event)
	{
		if (!class_exists("FSJ_Triggered_Helper"))
		{
			// try to load system	
			$file = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_fsj_events'.DS.'helpers'.DS.'triggered.php';
			
			if (file_exists($file))
			{
				require_once($file);	
			}
		}
		
		if (!class_exists("FSJ_Triggered_Helper")) return false;
		
		return self::eventExists($type, $event);
	}
	
	static function eventExists($type, $event)
	{
		return FSJ_Triggered_Helper::eventExists($type, $event);	
	}
	
	static function Ticket_updateStatus($ticket, $params)
	{
		if (!self::event_Init("ticket", "updateStatus")) return; // check event system installed and an event exists

		$data['new_status'] = $params['new_status_id'];
		$data['old_status'] = $params['old_status_id'];
		$data['ticket'] = FSJ_Events_Helper::createDataItem("ticket", $ticket);
		
		FSJ_Triggered_Helper::triggerEvent("ticket", "updateStatus", $data);
	}
}