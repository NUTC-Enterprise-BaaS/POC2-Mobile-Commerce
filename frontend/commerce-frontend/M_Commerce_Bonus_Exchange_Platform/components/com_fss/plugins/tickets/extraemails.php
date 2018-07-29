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

class SupportActionsExtraEMails extends SupportActionsPlugin
{
	var $title = "EMail Notifications - Extras";
	var $description = "Extra email notifications that can be enabled. At the moment this is only for notifying on custom field changed. Please let us know if you want other notifications added.";
	
	
	function Ticket_updateCustomField($ticket, $params)
	{
		$this->loadSettings();

		if ($this->settings->cfhandler->enabled) $this->Ticket_updateCustomField_Handler($ticket, $params);

		if ($this->settings->cfuser->enabled) $this->Ticket_updateCustomField_User($ticket, $params);
	}
	
	function Ticket_updateCustomField_Handler($ticket, $params)
	{
		$field = FSSCF::GetField($params['field_id']);

		if ($this->settings->cfhandler->who == 1 && $params['who'] != "user") return;
		if ($this->settings->cfhandler->who == 2 && $params['who'] != "handler") return;
		
		$ids = explode(";", $this->settings->cfhandler->list);
		
		if ($this->settings->cfhandler->use == 1 && !in_array($params['field_id'], $ids)) return;
		if ($this->settings->cfhandler->use == -1 && in_array($params['field_id'], $ids)) return;
		
		$tmpl = array();
		$tmpl['body'] = $this->settings->cfhandler->message;
		$tmpl['subject'] = $this->settings->cfhandler->subject; 
		$tmpl['ishtml'] = 1; 
		$tmpl['translation'] = ''; 
		$tmpl['tmpl'] = 'custom'; 
		
		$vars['cf_old'] = $params['old'];
		$vars['cf_new'] = $params['new'];
		$vars['cf_id'] = $field->id;
		$vars['cf_name'] = $field->description;
		$vars['cf_alias'] = $field->alias;
		
		FSS_EMail::EMail_To_Ticket_Handler($tmpl, $ticket, "", "", array(), $vars, JFactory::getUser()->id);
	}	
	
	function Ticket_updateCustomField_User($ticket, $params)
	{
		$field = FSSCF::GetField($params['field_id']);

		if ($this->settings->cfuser->who == 1 && $params['who'] != "user") return;
		if ($this->settings->cfuser->who == 2 && $params['who'] != "handler") return;
		
		$ids = explode(";", $this->settings->cfuser->list);
		
		if ($this->settings->cfuser->use == 1 && !in_array($params['field_id'], $ids)) return;
		if ($this->settings->cfuser->use == -1 && in_array($params['field_id'], $ids)) return;
		
		$tmpl = array();
		$tmpl['body'] = $this->settings->cfuser->message;
		$tmpl['subject'] = $this->settings->cfuser->subject; 
		$tmpl['ishtml'] = 1; 
		$tmpl['translation'] = ''; 
		$tmpl['tmpl'] = 'custom'; 
		
		$vars['cf_old'] = $params['old'];
		$vars['cf_new'] = $params['new'];
		$vars['cf_id'] = $field->id;
		$vars['cf_name'] = $field->description;
		$vars['cf_alias'] = $field->alias;
		
		FSS_EMail::EMail_To_Ticket_User($tmpl, $ticket, "", "", array(), $vars, JFactory::getUser()->id);
	}
	
	function User_Reply($ticket, $params)
	{
		$this->loadSettings();

		if ($this->settings->useruser->enabled) 
		{	
			$tmpl = array();
			$tmpl['body'] = $this->settings->useruser->message;
			$tmpl['subject'] = $this->settings->useruser->subject; 
			$tmpl['ishtml'] = 1; 
			$tmpl['translation'] = ''; 
			$tmpl['tmpl'] = 'custom'; 
			
			FSS_EMail::EMail_To_Ticket_User($tmpl, $ticket, $params['subject'], $params['user_message'], array(), array(), JFactory::getUser()->id);
		}
	}

	function Ticket_addCC($ticket, $params)
	{
		if ($params['is_admin'] != 1) return;

		$this->loadSettings();

		if (!$this->settings->handlercc->enabled) return;

		$tmpl = array();
		$tmpl['body'] = $this->settings->handlercc->message;
		$tmpl['subject'] = $this->settings->handlercc->subject; 
		$tmpl['ishtml'] = 1; 
		$tmpl['translation'] = ''; 
		$tmpl['tmpl'] = 'custom'; 
		
		if (isset($params['user_id']) && $params['user_id'] > 0)
		{
			$user = JFactory::getUser($params['user_id']);
			$vars['ccd_email'] = $user->email;
			$vars['ccd_username'] = $user->username;
			$vars['ccd_name'] = $user->name;
		} else {
			$vars['ccd_email'] = $params['email'];
			$vars['ccd_username'] = $params['email'];
			$vars['ccd_name'] = $params['email'];
		}
		
		FSS_EMail::EMail_To_Ticket_Handler($tmpl, $ticket, "", "", array(), $vars, JFactory::getUser()->id);		
	}
		
	function Ticket_updateStatus($ticket, $params)
	{
		$direct = (isset($params['direct']) && $params['direct']);
		if (!$direct) return;

		$this->loadSettings();

		if ($this->settings->statushandler->enabled) $this->Ticket_updateStatus_Handler($ticket, $params);

		if ($this->settings->statususer->enabled) $this->Ticket_updateStatus_User($ticket, $params);
	}
	
	function Ticket_updateStatus_User($ticket, $params)
	{
		$batch = (isset($params['batch']) && $params['batch']);
		if ($batch && $this->settings->statususer->batch == 0) return;
		if (!$batch && $this->settings->statususer->dropdown == 0) return;
		
		$statuss = SupportHelper::getStatussUser(false);

		$tmpl = array();
		$tmpl['body'] = $this->settings->statususer->message;
		$tmpl['subject'] = $this->settings->statususer->subject; 
		$tmpl['ishtml'] = 1; 
		$tmpl['translation'] = ''; 
		$tmpl['tmpl'] = 'custom'; 


		$vars['status_old_id'] = $statuss[$params['old_status_id']]->id;
		$vars['status_old_name'] = $statuss[$params['old_status_id']]->title;
		$vars['status_new_id'] = $statuss[$params['new_status_id']]->id;
		$vars['status_new_name'] = $statuss[$params['new_status_id']]->title;
		
		FSS_EMail::EMail_To_Ticket_User($tmpl, $ticket, "", "", array(), $vars, JFactory::getUser()->id);
	}	

	function Ticket_updateStatus_Handler($ticket, $params)
	{
		if (isset($params['batch']) && $this->settings->statushandler->batch == 0) return;
		if (!isset($params['batch']) && $this->settings->statushandler->dropdown == 0) return;
		
		$statuss = SupportHelper::getStatuss(false);

		$tmpl = array();
		$tmpl['body'] = $this->settings->statushandler->message;
		$tmpl['subject'] = $this->settings->statushandler->subject; 
		$tmpl['ishtml'] = 1; 
		$tmpl['translation'] = ''; 
		$tmpl['tmpl'] = 'custom'; 


		$vars['status_old_id'] = $statuss[$params['old_status_id']]->id;
		$vars['status_old_name'] = $statuss[$params['old_status_id']]->title;
		$vars['status_new_id'] = $statuss[$params['new_status_id']]->id;
		$vars['status_new_name'] = $statuss[$params['new_status_id']]->title;
		
		FSS_EMail::EMail_To_Ticket_Handler($tmpl, $ticket, "", "", array(), $vars, JFactory::getUser()->id);
	}
}