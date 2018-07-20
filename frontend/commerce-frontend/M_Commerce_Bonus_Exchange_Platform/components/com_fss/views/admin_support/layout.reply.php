<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_ticket.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_helper.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'parser.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'fields.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_canned.php');


class FssViewAdmin_Support_Reply extends FssViewAdmin_Support
{
	function display($tpl = NULL)
	{
		$what = FSS_Input::getCmd('what');
		
		$this->reply_type = FSS_Input::getCmd('type', 'reply');
		if ($this->reply_type == "") $this->reply_type = "reply";

		if ($this->reply_type == "reply" && !$this->can_Reply())
			return JFactory::getApplication()->redirect(FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . FSS_Input::getInt('ticketid'), false));	
		
		if ($this->reply_type == "user" && !$this->can_ChangeUser())
			return JFactory::getApplication()->redirect(FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . FSS_Input::getInt('ticketid'), false));	
		
		if (($this->reply_type == "product" || $this->reply_type == "handler") && !$this->can_Forward())
			return JFactory::getApplication()->redirect(FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . FSS_Input::getInt('ticketid'), false));	
		
		if ($what == "savereply")
			return $this->saveReply();
		
		if ($what == "draft")
			return $this->saveDraft();
		
		if ($what == "removedraft")
			return $this->doRemoveDraft();
				
		$this->doReply();
	}
	
	function doReply()
	{
		$this->ticketid = FSS_Input::getInt('ticketid');

		$this->ticket = new SupportTicket();
		
		if (!$this->ticket->load($this->ticketid))
		{
			return JError::raiseWarning(404, JText::_('Ticket not found'));
		}	
		
		$this->ticket->loadAll();
		
		if (FSS_Settings::get('time_tracking') != "")
		{
			if (FSS_Settings::get('time_tracking_type') == 'se')
			{
				$this->time_start = FSS_Helper::Date(time(), FSS_DATE_CUSTOM, "H:i:s");
				$this->time_end = FSS_Helper::Date(time(), FSS_DATE_CUSTOM, "H:i:s");
			} elseif (FSS_Settings::get('time_tracking_type') == 'tm')
			{
				$this->time_start = FSS_Helper::Date(time(), FSS_DATE_CUSTOM, FSS_Helper::getFormat());
				$this->time_end = FSS_Helper::Date(time(), FSS_DATE_CUSTOM, FSS_Helper::getFormat());
			} else {
				$this->taken_hours = 0;
				$this->taken_mins = 0;
			}
		}

		if (FSS_Settings::get('time_tracking') == "auto")
		{	
			$session = JFactory::getSession();
			$taken = $session->get( 'ticket_' . $this->ticket->id . "_opened" );
			
			if (FSS_Settings::get('time_tracking_type') == 'se')
			{
				$document = JFactory::getDocument();
				$document->addScript( JURI::root().'components/com_fss/assets/js/bootstrap/bootstrap-timepicker.min.js' );
				$document->addScriptDeclaration("jQuery(document).ready(function () {jQuery('#timetaken_start').timepicker({minuteStep:5, showMeridian: false});jQuery('#timetaken_end').timepicker({minuteStep:5, showMeridian: false});});");

				$this->time_start = FSS_Helper::Date($taken, FSS_DATE_CUSTOM, "H:i:s");
			} else {			
				if ($taken > 0)
					$taken = time() - $taken;
				$this->time_taken = $taken;
			
				$taken = ceil($taken / 60);
				$this->taken_hours = floor($taken / 60);
				$this->taken_mins = $taken % 60 + 1;
			}
		}

		$this->fields = FSSCF::GetCustomFields($this->ticket->id,$this->ticket->prod_id,$this->ticket->ticket_dept_id,3);
		$this->fieldvalues = FSSCF::GetTicketValues($this->ticket->id, $this->ticket);


		$pathway = JFactory::getApplication()->getPathway();
		$pathway->addItem(JText::_("SUPPORT"),FSSRoute::_( 'index.php?option=com_fss&view=admin_support&tickets=' . $this->ticket_view, false ));
		$pathway->addItem(JText::_("VIEW_TICKET")." : " . $this->ticket->reference . " - " . $this->ticket->title,FSSRoute::_( 'index.php?option=com_fss&view=admin_support&tickets=' . $this->ticket_view . "&ticketid=" . $this->ticket->id, false));
		
		$this->reply_type = FSS_Input::getCmd('type', 'reply');
		if ($this->reply_type == "") $this->reply_type = "reply";
		
		switch ($this->reply_type)
		{
		case 'reply':
			$this->reply_title = "REPLY_TO_SUPORT_TICKET";
			$this->reply_button = "POST_REPLY";
		
			$pathway->addItem(JText::_("POST_REPLY"));
			break;
				
		case 'private':
			$this->reply_title = "ADD_HANDLER_COMMENT_TO_TICKET";
			$this->reply_button = "POST_COMMENT";
		
			$pathway->addItem(JText::_("ADD_COMMENT"));
			break;
				
		case 'user':
			$this->reply_title = "FORWARD_TICKET_TO_A_DIFFERENT_USER";
			$this->reply_button = "FORWARD_TICKET";
		
			if ($this->ticket->user_id > 0)
			{
				$user = JFactory::getUser($this->ticket->user_id);
			} else {
				$user = new stdClass();
				$user->username = $this->ticket->email;	
				$user->name = $this->ticket->unregname;	
			}
			$this->user = $user;

			$pathway->addItem(JText::_("FORWARD_TO_USER"));
			break;
				
		case 'product':
			$this->reply_title = "FORWARD_TICKET_TO_A_DIFFERENT_DEPARTMENT";
			$this->reply_button = "FORWARD_TICKET";
			$this->handlers = SupportUsers::getHandlers(false, true);
				
			$pathway->addItem(JText::_("FORWARD_TO_DEPARTMENT"));
			break;
				
		case 'handler':
			$this->reply_title = "FORWARD_TICKET_TO_A_DIFFERENT_HANDLER";
			$this->reply_button = "FORWARD_TICKET";
			
			$this->handlers = SupportUsers::getHandlers(false, true);

			$pathway->addItem(JText::_("FORWARD_TO_HANDLER"));
			break;
				
		}
		
		$this->draft = FSS_Input::getInt('draft');
		$this->user_message = $this->loadDraft($this->draft);
				
		$this->support_assign_reply = FSS_Settings::get('support_assign_reply');

		FSS_Helper::IncludeModal();
		FSS_Helper::AddSCEditor();
		
		parent::_display();			
	}
	
	function doRemoveDraft()
	{
		$this->draft = FSS_Input::getInt('draft');
		$ticketid = FSS_Input::getInt('ticketid');
		
		$this->removeDraft($this->draft);
		
		
		$link = FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . $ticketid, false);
		JFactory::getApplication()->redirect($link);		
	}
	
	function loadDraft($id)
	{
		if ($id)
		{
			$db = JFactory::getDBO();
			$qry = "SELECT * FROM #__fss_ticket_messages WHERE id = " . (int)$id;
			$db->setQuery($qry);
			$draft = $db->loadObject();
			
			if ($draft)
				 return $draft->body;
		}
		return "";
	}
	
	function removeDraft($id)
	{
		if ($id > 0)
		{
			$ticketid = FSS_Input::getInt('ticketid');
			$db = JFactory::getDBO();
			$qry = "DELETE FROM #__fss_ticket_messages WHERE id = " . (int)$id . " AND ticket_ticket_id = " . (int)$ticketid . " AND admin = 4";
			$db->setQuery($qry);
			$db->Query();
		}
	}
	
	function saveDraft()
	{
		$this->removeDraft(FSS_Input::getInt('draft'));
		
		$ticketid = FSS_Input::getInt('ticketid');
		$reply_type = FSS_Input::getCmd('reply_type');
		$user_message = FSS_Input::getBBCode('body');
		$subject = FSS_Input::getString('subject');

		//print_p($_POST);
		//exit;
	
		$ticket = new SupportTicket();
		if (!$ticket->load($ticketid))
			exit;
			
		$user_id = JFactory::getUser()->id;

		$message_id = $ticket->addMessage($user_message, $subject, $user_id, TICKET_MESSAGE_DRAFT);
		
		$reply_status = FSS_Input::getInt('reply_status');
		
		if (FSS_Settings::get('support_update_satatus_on_draft') && $reply_status > 0 && $ticket->ticket_status_id != $reply_status) $ticket->updateStatus($reply_status);
		
		$link = FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . $ticket->id, false);
		JFactory::getApplication()->redirect($link);	
	}

	function saveReply()
	{	
		$this->removeDraft(FSS_Input::getInt('draft'));

		// get posted data
		$ticketid = FSS_Input::getInt('ticketid');
		$reply_type = FSS_Input::getCmd('reply_type');
		$user_message = trim(FSS_Input::getBBCode('body'));
		$handler_message = trim(FSS_Input::getBBCode('body2'));
		$subject = FSS_Input::getString('subject');
		$source = FSS_Input::getCmd('source');
		
		// load ticket
		$ticket = new SupportTicket();
		if (!$ticket->load($ticketid))
			exit;
		
		// set up some variables
		$user_id = JFactory::getUser()->id;
		$handler_id = $user_id;		
		$old_st = $ticket->getStatus();
		$action_name = "";
		$action_params = array();
		$message_id = 0;
		$files_private = 0;

		// add signatures to messages
		if (FSS_Input::getInt('signature'))
		{
			if ($user_message)
				$user_message .= SupportCanned::AppendSig(FSS_Input::getInt('signature'), $ticket);
			if ($handler_message)
				$handler_message .= SupportCanned::AppendSig(FSS_Input::getInt('signature'), $ticket);
		}
		
		$extra_time = 0;
		$timestart = 0;
		$timeend = 0;
		if (FSS_Settings::Get('time_tracking') != "")
		{
			if (FSS_Settings::get('time_tracking_type') == 'se')
			{
				$timestart = strtotime("1970-01-01 " . FSS_Input::getString('timetaken_start'));
				$timeend = strtotime("1970-01-01 " . FSS_Input::getString('timetaken_end'));
				
				$extra_time = (int)(($timeend - $timestart) / 60);
			} elseif (FSS_Settings::get('time_tracking_type') == 'tm')
			{
				$timestart = strtotime(FSS_Input::getString('timetaken_start'));
				$timeend = strtotime(FSS_Input::getString('timetaken_end'));
				
				$extra_time = (int)(($timeend - $timestart) / 60);
			} else {
				$extra_time = (int)(FSS_Input::getInt('timetaken_hours') * 60 + FSS_Input::getInt('timetaken_mins'));
			}
		}
		
		$old_handler = $ticket->admin_id;

		// different reply types
		switch ($reply_type)
		{
			case 'reply':
				// post reply to user
				if ($user_message) {
					$message_id = $ticket->addMessage($user_message, $subject, $user_id, TICKET_MESSAGE_ADMIN, $extra_time, $timestart, $timeend, $source);
					if ($extra_time > 0) $ticket->addTime($extra_time);
				} elseif ($extra_time > 0) { // no message, add time if needed
					$ticket->addTime($extra_time, "", true, $timestart, $timeend);
				}
			
				// update status
				$new_status = FSS_Input::getInt('reply_status');
				$ticket->updateStatus($new_status);
			
				// reassign ticket if needed
				if (FSS_Settings::get('support_assign_reply') == 1 && FSS_Input::getInt('dontassign') == 0)
					$ticket->assignHandler($handler_id, TICKET_ASSIGN_TOOK_OWNER);
				elseif (FSS_Settings::get('support_autoassign') == 3 && $ticket->admin_id == 0 && FSS_Input::getInt('dontassign') == 0)
					$ticket->assignHandler($handler_id, TICKET_ASSIGN_ASSIGNED);
				
				// call SupportActions handler for admin reply
				$action_name = "Admin_Reply";
				$action_params = array('subject' => $subject, 'user_message' => $user_message, 'status' => $new_status, 'sender' => $user_id, 'oldhandler' => $old_handler);
			
				break;
				
			case 'private':
				// add message to ticket
				if ($handler_message)
				{
					$message_id = $ticket->addMessage($handler_message, $subject, $user_id, TICKET_MESSAGE_PRIVATE, $extra_time, $timestart, $timeend);
					if ($extra_time > 0) $ticket->addTime($extra_time);
				} else if ($extra_time > 0)
				{
					$ticket->addTime($extra_time, "", true, $timestart, $timeend);
				}
			
				$files_private = 1;
			
				// call support actions for private comment
				$action_name = "Admin_Private";
				$action_params = array('subject' => $subject, 'handler_message' => $handler_message, 'sender' => $user_id);		
				break;
				
			case 'user':
				// update user on ticket
				$new_user_id = FSS_Input::getInt("user_id");
				$ticket->updateUser($new_user_id);
			
				if ($user_message)
				{
					$message_id = $ticket->addMessage($user_message, $subject, $user_id, TICKET_MESSAGE_ADMIN, $extra_time, $timestart, $timeend);
					if ($extra_time > 0) $ticket->addTime($extra_time);
				} else if ($extra_time > 0)
				{
					$ticket->addTime($extra_time, "", true, $timestart, $timeend);
					$extra_time = 0;
				}
			
				$action_name = "Admin_ForwardUser";
				$action_params = array('subject' => $subject, 'user_message' => $user_message, 'user_id' => $new_user_id, 'sender' => $user_id);
				// 
				break;
				
			case 'product':
				$new_handler_id = FSS_Input::getInt('new_handler');
				
				// update product and department
				$new_product_id = FSS_Input::getInt("new_product_id");
				$new_department_id = FSS_Input::getInt("new_department_id");

				$ticket->updateProduct($new_product_id);
				$ticket->updateDepartment($new_department_id);

				/**
				 * -2 - Auto Assign
				 * -1 - Unchanged
				 * 0 - Unassigned
				 * X - Hander
				 **/
				if ($new_handler_id == -1) // no change
				{
					//$ticket->assignHandler($new_handler_id, TICKET_ASSIGN_FORWARD);
				} else if ($new_handler_id == 0) // unassigned
				{
					$ticket->assignHandler(0, -1);
				} else if ($new_handler_id > 0) // user id specified
				{
					$ticket->assignHandler($new_handler_id, TICKET_ASSIGN_FORWARD);
				} else if ($new_handler_id == -2) {
					// auto assign new handler
					$params = array(
						'title' => $ticket->title,
						'user_id' => $ticket->user_id,
						'email' => $ticket->email,
						'unregname' => $ticket->unregname,
						'source' => 'forward_product'
						);
			
					$admin_id = FSS_Ticket_Helper::AssignHandler($new_product_id, $new_department_id, $ticket->ticket_cat_id, true, $params);
					$ticket->assignHandler($admin_id, TICKET_ASSIGN_FORWARD);
				}
				
				if ($user_message)
				{
					$message_id = $ticket->addMessage($user_message, $subject, $user_id, TICKET_MESSAGE_ADMIN, $extra_time, $timestart, $timeend);
					if ($extra_time > 0) $ticket->addTime($extra_time);
					$extra_time = 0;
				}

				if ($handler_message) // add private message to new handler
				{
					$ticket->addMessage($handler_message, $subject, $user_id, TICKET_MESSAGE_PRIVATE, $extra_time, $timestart, $timeend);
					if ($extra_time > 0) $ticket->addTime($extra_time);
					$extra_time = 0;
				}
				
				if ($extra_time > 0)
				{
					$ticket->addTime($extra_time, "", true, $timestart, $timeend);
				}
				
				$action_name = "Admin_ForwardProduct";
				$action_params = array('subject' => $subject, 'user_message' => $user_message, 'handler_message' => $handler_message, 'product_id' => $new_product_id, 'department_id' => $new_department_id, 'sender' => $user_id, 'oldhandler' => $old_handler);
	
				break;
				
			case 'handler':
				$new_handler_id = FSS_Input::getInt('new_handler');

				if ($new_handler_id == -2) {
					// auto assign new handler

					$params = array(
						'title' => $ticket->title,
						'user_id' => $ticket->user_id,
						'email' => $ticket->email,
						'unregname' => $ticket->unregname,
						'source' => 'forward_handler'
						);

					$admin_id = FSS_Ticket_Helper::AssignHandler($ticket->prod_id, $ticket->ticket_dept_id, $ticket->ticket_cat_id, true, $params);
					$ticket->assignHandler($admin_id, TICKET_ASSIGN_FORWARD);
				} else if ($new_handler_id != -1)
					$ticket->assignHandler($new_handler_id, TICKET_ASSIGN_FORWARD);
				
				// update status
				$new_status = FSS_Input::getCmd('reply_status');
				$ticket->updateStatus($new_status);
		
				if ($user_message)
				{
					$message_id = $ticket->addMessage($user_message, $subject, $user_id, TICKET_MESSAGE_ADMIN, $extra_time, $timestart, $timeend);
					if ($extra_time > 0) $ticket->addTime($extra_time);
					$extra_time = 0;
				}

				if ($handler_message) // add private message to new handler
				{
					$ticket->addMessage($handler_message, $subject, $user_id, TICKET_MESSAGE_PRIVATE, $extra_time, $timestart, $timeend);
					if ($extra_time > 0) $ticket->addTime($extra_time);
					$extra_time = 0;
				}

				if ($extra_time > 0)
				{
					$ticket->addTime($extra_time, "", true, $timestart, $timeend);
				}	
						
				$action_name = "Admin_ForwardHandler";
				$action_params = array('subject' => $subject, 'user_message' => $user_message, 'handler_message' => $handler_message, 'handler_id' => $new_handler_id, 'sender' => $user_id, 'oldhandler' => $old_handler);
		
				break;				
		}
		
		// add posted files
		$files = $ticket->addFilesFromPost($message_id, -1, $files_private);
		$ticket->stripImagesFromMessage($message_id);

		$action_params['files'] = $files;

		// call action handler
		SupportActions::DoAction($action_name, $ticket, $action_params);

		// Redirect to new page
		$link = FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . $ticket->id, false);

		$new_st = $ticket->getStatus();

		if ($reply_type == "reply")
		{
			if ($new_st->is_closed && SupportUsers::getSetting("return_on_close"))
			{
				$link = SupportHelper::parseRedirectType($old_st->id, SupportUsers::getSetting("return_on_close"));	
			} else if (SupportUsers::getSetting("return_on_reply"))
			{
				$link = SupportHelper::parseRedirectType($old_st->id, SupportUsers::getSetting("return_on_reply"));	
				
			}
		}	

		JFactory::getApplication()->redirect($link);	
	}
}