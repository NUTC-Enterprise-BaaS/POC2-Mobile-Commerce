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
 */
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'email.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'plugin.php');

jimport( 'joomla.filesystem.folder' );

/*
Current Actions:

SupportActions::DoAction_Ticket("updatePriority", $this, array('old_pri_id' => $old_pri_id, 'new_pri_id' => $new_pri_id));
SupportActions::DoAction_Ticket("updateStatus", $this, array('old_status_id' => $old_status_id, 'new_status_id' => $new_status_id));
SupportActions::DoAction_Ticket("updateCategory", $this, array('old_cat_id' => $old_cat_id, 'new_cat_id' => $new_cat_id));
SupportActions::DoAction_Ticket("updateUser", $this, array('old_user_id' => $this->user_id, 'new_user_id' => $new_user_id));
SupportActions::DoAction_Ticket("updateProduct", $this, array('old_prod_id' => $old_product->id, 'new_prod_id' => $new_product_id));	
SupportActions::DoAction_Ticket("updateDepartment", $this, array('old_department_id' => $old_product->id, 'new_department_id' => $new_department_id));	
SupportActions::DoAction_Ticket("updateUnregEMail", $this, array('old_email' => $old_email, 'new_email' => $new_email));
SupportActions::DoAction_Ticket("updateSubject", $this, array('old_subject' => $old_subject, 'new_subject' => $new_subject));
SupportActions::DoAction_Ticket("updateLock", $this);
SupportActions::DoAction_Ticket("updateCustomField", $this, array('field_id' => $fieldid, 'old' => $old, 'new' => $new));
SupportActions::DoAction_Ticket("addTag", $this, array('tag' => $tag));
SupportActions::DoAction_Ticket("removeTag", $this, array('tag' => $tag));
SupportActions::DoAction_Ticket("addTime", $this, array('time' => $minutes, 'notes' => $notes));
SupportActions::DoAction_Ticket("deleteAttach", $this, array('attach' => $attach));
SupportActions::DoAction_Ticket("addMessage", $this, array('user_id' => $user_id, 'type' => $type, 'subject' => $subject, 'body' => $body, 'message_id' => $message_id));
SupportActions::DoAction_Ticket("addFile", $this, array('file' => $file_obj));
SupportActions::DoAction_Ticket("updateMessage", $this, array('message_id' => $message_id, 'old_subject' => $message->subject, 'new_subject' => $subject, 'old_body' => $message->body, 'new_body' => $body));
SupportActions::DoAction_Ticket("assignHandler", $this, array('handler' => $handler_id, 'type' => $type));

$action_name = "Admin_Reply";
$action_params = array('subject' => $subject, 'user_message' => $user_message, 'status' => $new_status);
			
$action_name = "Admin_Private";
$action_params = array('subject' => $subject, 'handler_message' => $handler_message);		
						
$action_name = "Admin_ForwardUser";
$action_params = array('subject' => $subject, 'user_message' => $user_message, 'user_id' => $new_user_id);
				
$action_name = "Admin_ForwardProduct";
$action_params = array('subject' => $subject, 'user_message' => $user_message, 'handler_message' => $handler_message, 'product_id' => $new_product_id, 'department_id' => $new_department_id);
	
$action_name = "Admin_ForwardHandler";
$action_params = array('subject' => $subject, 'user_message' => $user_message, 'handler_message' => $handler_message, 'handler_id' => $new_handler_id);		
		
$action_name = "User_Reply";
$action_params = array('subject' => $subject, 'user_message' => $body, 'files' => $files);

$action_name = "User_Open";
$action_params = array('subject' => $this->ticket->title, 'user_message' => $body, 'files' => $this->ticket->attach);
SupportActions::DoAction($action_name, $this->ticket, $action_params);	
*/

class SupportActions
{
	static function DoAction_Ticket($name, $ticket, $params = null)
	{
		$params['who'] = 'handler';
		SupportActions::DoAction("Ticket_" . $name, $ticket, $params);
	}		
	
	static function DoAction_Ticket_User($name, $ticket, $params = null)
	{
		if (!is_object($ticket))
		{
			$id = $ticket;
			$ticket = new SupportTicket();
			$ticket->load($id);
			$ticket->loadAll();	
		}
		
		$params['who'] = 'user';
		SupportActions::DoAction("Ticket_" . $name, $ticket, $params);
	}		
	
	static function DoAction($name, $ticket, $params)
	{
		$plugins = self::LoadPlugins();
		
		foreach ($plugins as $plugin)
		{
			if (!$plugin->enabled)
				continue;
			
			if (method_exists($plugin, $name))
			{
				$plugin->$name($ticket, $params);	
			}	
		}
	}	
	
	static function ActionResult($name, $params, $needed)
	{
		$plugins = self::LoadPlugins();

		foreach ($plugins as $plugin)
		{
			if (!$plugin->enabled) continue;
			
			if (method_exists($plugin, $name))
			{
				$result = $plugin->$name($params);	
				
				if ($result !== $needed)
				{
					return $result;
				}
			}	
		}
		
		return $needed;
	}
	
	static $plugins;
	static function LoadPlugins($also_disabled = false)
	{
		// load in all php files in components/com_fss/plugins/tickets and for each make a new object
		if (empty(self::$plugins))
		{
			self::$plugins = array();
			
			$path = JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'plugins'.DS.'tickets';
			
			$files = JFolder::files($path, ".php$");

			foreach ($files as $file)
			{
				$fullpath = $path . DS . $file;
				
				$info = pathinfo($fullpath);
				
				if (!FSS_Helper::IsPluignEnabled("tickets", $info['filename']))
					continue;

				$ext = $info['extension'];
				
				$classname = "SupportActions" . $info['filename'];
				
				require_once($fullpath);
				
				if (class_exists($classname))
				{
					$plugin = new $classname();
					$plugin->enabled = true;
					$plugin->php_file = $fullpath;
					$plugin->id = $info['filename'];
					$plugin->type = 'tickets';
					$plugin->name = $info['filename'];
					
					self::$plugins[] = $plugin;
				}
			}
		}	

		return self::$plugins;
	}
}

class SupportActionsPlugin extends FSS_Plugin
{
	function CanEnable()
	{
		return true;	
	}	
}