<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

/**
 * SupportActionsUDDEIM
 * 
 * Adds PM using UDDEIM as ticket notification
 **/

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'email.php');

class SupportActionsUDDEIM extends SupportActionsPlugin
{
	var $title = "uddeIM Interface";
	var $description = "Post personal messages using uddeIM when ticket actions occur. The messages posted are taken from the email templates.";
	
	function Admin_Reply($ticket, $params)
	{
		if (!$params['user_message'])
			return;
		
		$status = $ticket->getStatus();
		
		if ($status->is_closed)
		{
			FSS_EMail_UDDEIM::Admin_Close($ticket, $params['subject'], $params['user_message'], $params['files']);
		} else {
			FSS_EMail_UDDEIM::Admin_Reply($ticket, $params['subject'], $params['user_message'], $params['files']);
		}
	}
	
	function Admin_Private($ticket, $params)
	{
		if (!$params['handler_message'])
			return;
		
		if (JFactory::getUser()->id != $ticket->admin_id)
		{
			FSS_EMail_UDDEIM::Admin_Forward($ticket, $params['subject'], $params['handler_message'], $params['files']);
		}
	}
		
	function Admin_ForwardUser($ticket, $params)
	{
		if (!$params['user_message'])
			return;
		
		FSS_EMail_UDDEIM::User_Create($ticket, $params['subject'], $params['user_message'], $params['files']);
	}
		
	function Admin_ForwardProduct($ticket, $params)
	{
		if ($ticket->admin_id > 0)
		{
			if ($params['handler_message'])
			{
				FSS_EMail_UDDEIM::Admin_Forward($ticket, $params['subject'], $params['handler_message'], $params['files']);
			} else if ($params['user_message']) {
				FSS_EMail_UDDEIM::Admin_Forward($ticket, $params['subject'], $params['user_message'], $params['files']);
			}	
		}
		
		if ($params['user_message'])
			FSS_EMail_UDDEIM::Admin_Reply($ticket, $params['subject'], $params['user_message'], $params['files']);
	}
		
	function Admin_ForwardHandler($ticket, $params)
	{
		if ($params['handler_message'])
		{
			FSS_EMail_UDDEIM::Admin_Forward($ticket, $params['subject'], $params['handler_message'], $params['files']);
		} else if ($params['user_message']) {
			FSS_EMail_UDDEIM::Admin_Forward($ticket, $params['subject'], $params['user_message'], $params['files']);
		}
		
		if ($params['user_message'])
			FSS_EMail_UDDEIM::Admin_Reply($ticket, $params['subject'], $params['user_message'], $params['files']);
	}
	
	function User_Open($ticket, $params)
	{
		if ($ticket->email)
		{
			FSS_EMail_UDDEIM::User_Create_Unreg($ticket, $params['subject'], $params['user_message'], $params['files']);
		} else {
			FSS_EMail_UDDEIM::User_Create($ticket, $params['subject'], $params['user_message'], $params['files']);
		}
		FSS_EMail_UDDEIM::Admin_Create($ticket, $params['subject'], $params['user_message'], $params['files']);
	}
	
	function User_Reply($ticket, $params)
	{
		if ($params['user_message'])
			FSS_EMail_UDDEIM::User_Reply($ticket, $params['subject'], $params['user_message'], $params['files']);
	}
	
	function CanEnable()
	{
		if (FSS_Helper::TableExists("#__uddeim"))
			return true;
		
		return "uddeIM not installed";
	}	
}

/**
 * Extend email class, and override the send function
 * 
 * This allows the email to be intercepted and instead of being sent as an email 
 * added into the database as a personal message
 **/
class FSS_EMail_UDDEIM extends FSS_EMail
{
	static function Send($mailer)
	{
		
		$from_uid = 0;
		
		$db = JFactory::getDBO();
		
		$msg = $mailer->Subject . "\n" . $mailer->Body;
		
		foreach (self::$recips as $email)
		{
			$qry = "SELECT * FROM #__users WHERE email = '" . $db->escape($email) . "'";
			//echo $qry . "<br>";
			$db->SetQuery($qry);
			$user = $db->loadObject();
	
			if ($user && $user->id > 0)
			{
				$qry = "INSERT INTO #__uddeim (fromid, toid, message, datum) VALUES ($from_uid, {$user->id}, '" . $db->escape($msg) . "', " . time() . ")";	
				$db->SetQuery($qry);
				$db->Query();
				//echo $qry . "<br>";
			}
			
		}
	}
	
	static function ShouldSend($tag)
	{
		return true;
	}
}