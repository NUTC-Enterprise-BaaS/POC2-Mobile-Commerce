<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @deprecated 0d4595b2dfe4fd12125d1f3961c9e702
**/
defined('_JEXEC') or die;


jimport( 'joomla.application.component.view');
jimport('joomla.filesystem.file');
jimport('joomla.utilities.date');

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'task.php');

class FssViewTicket extends FSSView
{
	function display($tpl = NULL)
	{		
		FSS_Helper::noBots();
		FSS_Helper::noCache();

		if (!FSS_Permission::AllowSupport())
		{
			return $this->noPermission("NO_PERM", "YOU_DO_NOT_HAVE_PERMISSION_TO_DO_THIS");
		}

		$autologin = FSS_Input::getCmd('login');
		if ($autologin != "") FSS_Helper::AutoLogin($autologin);

		$this->claimTickets();

		$layout = FSS_Input::getCmd('layout', 'list');	
		$layout = preg_replace("/[^a-z0-9\_]/", '', $layout);
		
		$file = JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'layout.' . $layout . '.php';
		if (!file_exists($file))
		{
			$file = JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'layout.list.php';
			$layout = "list";
		}
		require_once($file);
		
		$class_name = "FssViewTicket_" . $layout;
		
		$layout_handler = new $class_name();
		$layout_handler->setLayout($layout);
		$layout_handler->_models = $this->_models;
		$layout_handler->_defaultModel = $this->_defaultModel;
		if (!$layout_handler->init()) return false;

		$layout_handler->display();
	}
	
	public function getName()
	{
		$this->_name = "ticket";
		return $this->_name;
	}
	
	function _display($tpl = NULL)
	{
		parent::display($tpl);	
	}
	
	function init()
	{
		$user = JFactory::getUser();
		$this->userid = $user->get('id');

		$this->model = $this->getModel("ticket");
		
		if (!FSS_Permission::auth("fss.ticket.view", "com_fss.support_user") && 
			!FSS_Permission::auth("fss.ticket.open", "com_fss.support_user"))
		return FSS_Helper::NoPerm();	

		FSS_Helper::StylesAndJS(array('calendar', 'base64'));

		if (Task_Helper::HandleTasks($this)) return false;
		
		return true;
	}
	
	function setupView()
	{
		$this->def_open = FSS_Ticket_Helper::GetStatusID('def_open');
		$this->ticket_view = FSS_Input::getCmd('tickets', $this->def_open);
		
		if (!$this->ticket_view && FSS_Settings::get('support_simple_userlist_tabs')) $this->ticket_view = "all";	
	}
	
	function loadCounts()
	{
		$this->count = SupportHelper::getUserTicketCount();	
	}
	
	
	function CanEditField($field)
	{
		if ($this->ticket->readonly) return false;
		
		if (is_array($field) && $field['type'] == "plugin")
		{
			$aparams = FSSCF::GetValues($field);
			$plugin = FSSCF::get_plugin($aparams['plugin']);
			if (!$plugin->CanEdit()) return false;
		}
		
		$peruser = "";
		
		if (is_array($field))
		{
			$peruser = $field['peruser'];			
		} else {
			$peruser = $field->peruser;
		}
		
		if ($peruser == 1)
		{
			$owner = $this->ticket->user_id;
			
			$user = JFactory::getUser();
			$userid = $user->get('id');
			if ($owner == $userid) return true;
		}
		
		return true;
	}
	
	
	function needLogin($type = 0)
	{
		// type 0 = normal
		// type 1 = dupe email
		// type 2 = no ticket
		
		$session = JFactory::getSession();
		$session->clear('ticket_pass');
		$session->clear('ticket_email');
		$session->clear('ticket_reference');
		$session->clear('ticket_find');

		$layout = FSS_Input::getCmd('layout');

		$url = FSS_Helper::getCurrentURL();
		/*if (array_key_exists('REQUEST_URI',$_SERVER))
		{
			$url = $_SERVER['REQUEST_URI'];//JURI::current() . "?" . $_SERVER['QUERY_STRING'];
		} else {
			$option = FSS_Input::getCmd('option');
			$view = FSS_Input::getCmd('view');
			$Itemid = FSS_Input::getInt('Itemid');
			$url = "index.php?option=" . $option . "&view=" . $view . "&layout=" . $layout . "&Itemid=" . $Itemid; 
		}

		$url = str_replace("&what=find","",$url);*/
		$url = base64_encode($url);

		$this->assign('type',$type);		
		$this->return = $url;

		if ($layout == "") $this->setLayout("view");	
		$this->_display("login");
	}

	function denied($message)
	{
		if (is_array($message))
		{
			$this->no_permission_title = $message['title'];	
			$this->no_permission_message = $message['body'];	
		} else {
			$this->no_permission_title = JText::_("UNABLE_TO_OPEN_TICKET");	
			$this->no_permission_message = $message;	
		}	
		
		$this->no_permission_header = true;
		
		$this->setLayout("nopermission");
		$this->_display();
	}
	
	function noPermission($pagetitle = "INVALID_TICKET", $message = "YOU_ARE_TYING_TO_EITHER_ACCESS_AN_INVALID_TICKET_OR_DO_NOT_HAVE_PERMISSION_TO_VIEW_THIS_TICKET")
	{
		if (FSS_Permission::auth("fss.handler", "com_fss.support_admin"))
		{
			// we are a ticket handler, redirect to admin link
			$ticket_id = JRequest::getVar('ticketid');
			
			JFactory::getApplication()->redirect(FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . $ticket_id, false));	
		}
		
		$this->no_permission_title = $pagetitle;
		$this->no_permission_message = $message;
		
		$this->setLayout("nopermission");
		//print_r($this->ticket);
		parent::display();	    
	}
	
	function validateUser()
	{
		$user = JFactory::getUser();
		$userid = $user->get('id');
		$this->user_type = true;
		
		if ($userid > 0) return true;
		
		$this->user_type = "unreg";
		// use email for non registered ticket
		$session = JFactory::getSession();
		$sessionemail = "";
		$reference = "";

		if ($session->Get('ticket_email')) $sessionemail = $session->Get('ticket_email');	
		if ($session->Get('ticket_reference')) $reference = $session->Get('ticket_reference');
		
		$email = FSS_Input::getEMail('email',$sessionemail);
		$reference = FSS_Input::getEMail('reference',$reference);
		$session->Set('ticket_email', $email);
		$session->Set('ticket_reference', $reference);

		if ($email == "" && $reference == "")
		{
			$this->needLogin();
			return false;
		}
		
		$this->email = $email;
		
		if (in_array(FSS_Settings::get('support_unreg_type'), array(1, 2)))
		{
			$need_pass = (FSS_Settings::get('support_unreg_type') == 1);
			
			if ($need_pass)
			{
				$sessionpass = "";
				if ($session->Get('ticket_pass')) $sessionpass = $session->Get('ticket_pass');

				$password = FSS_Input::getString('password',$sessionpass);
				$session->Set('ticket_pass', $password);
			}

			$db = JFactory::getDBO();
			
			$qry = "SELECT id FROM #__fss_ticket_ticket WHERE reference = '" . $db->escape($reference) . "'";
			if ($need_pass)
			$qry .= " AND password = '" . $db->escape($password) . "'";

			$db->setQuery($qry);
			$row = $db->loadAssoc();
			
			if ($row)
			{
				$this->ticketid = $row['id'];
			} else {
				$this->needLogin(2);
				return false;
			}

		} else {
			if ($email == "")
			{
				$this->needLogin(2);
				return false;
			}

			// validate ticket password and find ticket id!
			$sessionpass = "";
			if ($session->Get('ticket_pass')) $sessionpass = $session->Get('ticket_pass');

			$password = FSS_Input::getString('password',$sessionpass);
			$session->Set('ticket_pass', $password);
			
			$db = JFactory::getDBO();
			
			$qry = "SELECT id FROM #__fss_ticket_ticket WHERE email = '".FSSJ3Helper::getEscaped($db, $email)."' AND password = '".FSSJ3Helper::getEscaped($db, $password)."'";
			//echo $qry."<br>";
			$db->setQuery($qry);
			$row = $db->loadAssoc();
			
			if ($row)
			{
				$this->ticketid = $row['id'];
			} else {
				$this->needLogin(2);
				return false;
			}
		}
		
		//echo "New Ticket ID : " . $this->ticketid . "<Br />";
		
		return true;	
	}
	
	
	function claimTickets()
	{
		$user = JFactory::getUser();
		
		if ($user->email != "" && $user->get('id') > 0)
		{
			$db = JFactory::getDBO();
			$qry = "UPDATE #__fss_ticket_ticket SET user_id = " . $user->get('id') . " WHERE email = '" . FSSJ3Helper::getEscaped($db, $user->email) . "'";
			$db->setQuery($qry);
			$db->Query();
		}
	}	

}
