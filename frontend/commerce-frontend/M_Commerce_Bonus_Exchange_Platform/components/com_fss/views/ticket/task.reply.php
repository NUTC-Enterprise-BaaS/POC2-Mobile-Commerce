<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'task.php');

/**
 * Stuff related to archiving and deleting tickets
 **/

class Task_Reply extends Task_Helper
{
	/**
	 * Updates priority for ticket
	 */
	function post()
	{
		if (!$this->_post()) return false;

		$this->setupReplyMessage();

		echo "<script>\n
		window.parent.refreshPage();
		</script>";
		//location = window.parent.location + '#message" . $this->messageid . "';\n </script>";
		exit;
	}
	
	function fullpost()
	{
		if (!$this->_post()) return false;

		$this->setupReplyMessage();

		$this->redirect($this->link);
	}

	private function setupReplyMessage()
	{
		$message = FSS_Helper::HelpText("support_message_user_reply", true);

		if ($message)
		{
			$session = JFactory::getSession();
			$session->set('ticket_message', $message);
		}
	}
	
	private function _post()
	{
		$user = JFactory::getUser();
		$userid = $user->get('id');
		$db = JFactory::getDBO();

		$ticketid = FSS_Input::getInt('ticketid');
		

		$this->link = $link = FSSRoute::_('index.php?option=com_fss&view=ticket&layout=view&ticketid=' . $ticketid,false);
		
		if (!$this->view->validateUser())
		{
			echo "Redirect 0";
			exit;
			$this->redirect($link);
		}

		$ticket = new SupportTicket();
		if (!$ticket->Load($ticketid, $this->view->user_type))
		{
			echo "Redirect 1";
			exit;
			return $this->redirect($link);
		}
		$ticket->setupUserPerimssions();
		
		// dont change read only tickets
		if ($ticket->readonly)
		{
			echo "Redirect 2";
			exit;
			return $this->redirect($link);
		}

		$subject = FSS_Input::getString('subject');
		$body = FSS_Input::getBBCode('body');
		$source = FSS_Input::getCmd('source');

		$messageid = -1;
		if ($body) $messageid = $ticket->addMessage($body, $subject, $userid, TICKET_MESSAGE_USER, 0, 0, 0, $source);

		$files = $ticket->addFilesFromPost($messageid, $userid);		
		$ticket->stripImagesFromMessage($messageid);		
		
		$def_user = FSS_Ticket_Helper::GetStatusID('def_user');

		// if we have requested a close of the ticket, set the status to the default closed instead of default reply
		if (FSS_Input::getInt('should_close') && FSS_Settings::get('support_user_show_close_reply') && $ticket->canclose) $def_user = FSS_Ticket_Helper::GetStatusID('def_closed');			
		if (FSS_Input::GetInt('reply_status') > 0) $def_user = FSS_Input::GetInt('reply_status');	
			
		$ticket->updateStatus($def_user);
			
		$action_params = array('subject' => $subject, 'user_message' => $body, 'files' => $files, 'status' => $def_user, 'sender' => $userid);
		SupportActions::DoAction("User_Reply", $ticket, $action_params);

		$this->messageid = $messageid;

		return true;
	}
}