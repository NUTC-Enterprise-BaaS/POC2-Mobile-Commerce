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
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'captcha.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_canned.php');

class FssViewTicket_Open extends FssViewTicket
{
	function display($tpl = NULL)
	{
		if (!FSS_Permission::AllowSupportOpen()) return FSS_Helper::NoPerm();	

		FSS_Helper::allowBack();

		$this->ticket_view = "new";
		$this->loadCounts();
		$this->setupAdminCreate();
		if (FSS_Settings::Get('support_only_admin_open') && $this->admin_create < 1) return $this->noPermission("ACCESS_DENIED", "CREATING_NEW_TICKETS_BY_USERS_IS_CURRENTLY_DISABLED");			
		$this->checkWithout();
		
		if (!$this->checkOpenUser()) return;
				
		$result = SupportActions::ActionResult("Tickets_openNew", 
						array(
							'admin_create' => $this->admin_create,
							'type' => FSS_Input::getCmd('type'), 
							'email' => JFactory::getSession()->Get('ticket_email'), 
							'name' => JFactory::getSession()->Get('ticket_name')), 
						true);
					
		if ($result !== true) return $this->Denied($result);	

		if (FSS_Settings::get('open_search_enabled') == 1 && $this->needFAQSearch()) return; // before product

		if ($this->needProduct()) return;
		if ($this->prodid > 0) $this->product = SupportHelper::getProduct($this->prodid);
		
		if (FSS_Settings::get('open_search_enabled') == 2 && $this->needFAQSearch()) return; // before product

		if ($this->needDepartment()) return;
		if ($this->deptid > 0) $this->dept = SupportHelper::getDepartment($this->deptid);

		if (FSS_Settings::get('open_search_enabled') == 3 && $this->needFAQSearch()) return; // before product

		// defaults for blank ticket
		$this->ticket['ticket_pri_id'] = FSS_Input::getInt('priid', FSS_Settings::get('support_default_priority'));
		$this->ticket['body'] = FSS_Input::GetString('body');
		$this->ticket['title'] = FSS_Input::GetString('subject');
		$this->ticket['admin_id'] = FSS_Input::getInt('handler',0);
		$this->catid = FSS_Input::GetInt('catid');
		
		$this->errors['subject'] = '';
		$this->errors['body'] = '';
		$this->errors['cat'] = '';
		$this->errors['captcha'] = '';
		
		// done with ticket, try and save, if not, display any errors
		if (FSS_Input::getCmd('what') == "add")
		{
			if ($this->saveTicket())
			{
				$message = FSS_Helper::HelpText("support_open_thanks", true);
				if ($message != "")	FSS_Helper::enqueueMessage($message, "success");

				SupportActions::DoAction("ticketCreateRedirect", null, array('ticketid' => $this->ticketid));

				//exit;
				if ($this->admin_create > 0)
				{
					$link = 'index.php?option=com_fss&view=admin_support&Itemid=' . FSS_Input::getInt('Itemid','') . '&ticketid=' . $this->ticket->id;
					JFactory::getApplication()->redirect(FSSRoute::_($link, false));
				} else {
					
					if ($this->ticket->user_id > 0)
					{
						$message = FSS_Helper::HelpText("support_message_new_ticket_reg", true);
					} else {
						$message = FSS_Helper::HelpText("support_message_new_ticket_unreg", true);
					}

					if ($message)
					{
						$session = JFactory::getSession();
						$session->set('ticket_open_message', $message);
					}

					// need to set the session info that will display the ticket to the user here!
					$link = 'index.php?option=com_fss&view=ticket&layout=view&Itemid=' . FSS_Input::getInt('Itemid','') . '&ticketid=' . $this->ticket->id;
					JFactory::getApplication()->redirect(FSSRoute::_($link, false));
				}
				return;
			} else {
				//echo "Error saving ticket<br>";
				// should just show open page again with any errors listed
			}
		}
		
		$this->loadHandlers();
		$this->fields = FSSCF::GetCustomFields(0,$this->prodid,$this->deptid, 3, false, true);
		$this->sortCaptcha();
		$this->cats = SupportHelper::getCategoriesUserOpen($this->prodid,$this->deptid);
		$this->pris = SupportHelper::getPrioritiesUser();
		FSS_Helper::AddSCEditor(true);
		$this->_display();
	}	

	function saveTicket()
	{
		$data['title'] = FSS_Input::getString('subject');
		$message = trim(FSS_Input::getBBCode('body'));
		$data['ticket_cat_id'] = FSS_Input::getInt('catid');
		$data['ticket_pri_id'] = FSS_Input::getInt('priid');
		$data['admin_id'] = FSS_Input::getInt('handler');
		$data['prod_id'] = $this->prodid;
		$data['ticket_dept_id'] = $this->deptid;
		$data['body'] = $message;
		
		$user = JFactory::getUser();
		$data['user_id'] = $user->id;
		$name = "";
		
		$session = JFactory::getSession();
		$this->admin_create = 0;
		
		if ($session->Get('admin_create')) $this->admin_create = $session->Get('admin_create');
		
		if ($this->admin_create == 1) $data['user_id'] = $session->Get('admin_create_user_id');
		if ($this->admin_create == 2) $data['user_id'] = 0;

		$db = JFactory::getDBO();
		
		if ($data['ticket_pri_id'] < 1)	$data['ticket_pri_id'] = FSS_Settings::get('support_default_priority');

		$errors['subject'] = '';
		$errors['body'] = '';
		$errors['cat'] = '';
		$errors['captcha'] = '';
		
		$fields = FSSCF::GetCustomFields(0,$data['prod_id'],$data['ticket_dept_id'], 3, false, true);
		
		if (FSS_Settings::get('support_subject_message_hide') == "subject") $data['subject'] = $data['title'] = substr(strip_tags($data['body']), 0, 40);
		
		if (FSS_Settings::get('support_altcat'))
		{
			$cats = SupportHelper::getCategoriesUserOpen($data['prod_id'],$data['ticket_dept_id']);
			if (count($cats) > 0 && $data['ticket_cat_id'] < 1 && !FSS_Settings::get('support_hide_category')) $errors['cat'] = JText::_("YOU_MUST_SELECT_A_CATEGORY");	
		}
		
		if ($message == "" && FSS_Settings::get('support_subject_message_hide') != "message" && FSS_Settings::get('support_subject_message_hide') != "both")
		{
			$errors['body'] = JText::_("YOU_MUST_ENTER_A_MESSAGE_FOR_YOUR_SUPPORT_TICKET");			
		}
		
		FSSCF::ValidateFields($fields,$errors);	
		
		$email = "";
		$password = "";
		$now = FSS_Helper::CurDate();
		
		$this->sortCaptcha();
		$captcha = new FSS_Captcha();
		if (!$captcha->ValidateCaptcha('support_captcha_type')) $errors['captcha'] = JText::_("INVALID_SECURITY_CODE");
		
		if ($data['user_id'] < 1)
		{	
			$email = $session->Get('ticket_email');
			$name = $session->Get('ticket_name');
			if (!$name) $name = $email;

			if ($email == "" && $this->admin_create != 2)
			{
				$errors['noemail'] = "No EMail";
			} else {
				$data['email'] = $email;
				$data['unregname'] = $name;
				$data['password'] = FSS_Helper::createRandomPassword();
				$session->Set('ticket_pass', $data['password']);
			}
		}
		
		$params = array(
			'title' => $data['title'],
			'user_id' => $data['user_id'],
			'email' => @$data['email'],
			'unregname' => @$data['unregname'],
			'source' => 'new_ticket'
			);
			
		// assign handler to ticket
		if (!$data['admin_id'])
		{
			$data['admin_id'] = FSS_Ticket_Helper::AssignHandler($data['prod_id'], $data['ticket_dept_id'], $data['ticket_cat_id'], false, $params);
		}

		$now = FSS_Helper::CurDate();
		$def_open = FSS_Ticket_Helper::GetStatusID('def_open');

		$custom_subject = false;

		if (FSS_Settings::get('support_subject_format') != "")
		{
			if ($data['title'] == "" || !FSS_Settings::get('support_subject_format_blank')) $custom_subject = true;
		} else if ($data['title'] == "")
		{
			$errors['subject'] = JText::_("YOU_MUST_ENTER_A_SUBJECT_FOR_YOUR_SUPPORT_TICKET");	
		}
		
		$ok = true;
		foreach ($errors as $value)
		{
			if (trim($value) != "") $ok = false;
		}

		if ($ok)
		{		
			$data['lang'] = JFactory::getLanguage()->getTag();

			$this->ticket = new SupportTicket();
			$this->ticket->create($data);
	
			$this->ticketid = $this->ticket->id;

			if ($this->admin_create)
			{
				$opened_message = JText::sprintf('TICKET_OPENED_BY', $user->name, $user->username);
				$this->ticket->addMessage($opened_message, $data['title'], $user->id, TICKET_MESSAGE_OPENEDBY);
			}

			if ($this->ticket->user_id < 1)
			{	
				$session->Set('ticket_reference', $this->ticket->reference);
			}

			if ($this->ticket->user_id > 0)
			{
				$messageid = $this->ticket->addMessage($message, $data['title'], $this->ticket->user_id, TICKET_MESSAGE_USER);
			} else {
				$messageid = $this->ticket->addMessageUnreg($message, $data['title'], TICKET_MESSAGE_USER, $data['unregname'], $data['email']);
			}

			FSSCF::StoreFields($fields,$this->ticket->id);
			
			// store tags if there are any posted
			$tags_input = FSS_Input::getString('tags');
			$parts = explode("|", $tags_input);	
			foreach ($parts as $part) $this->ticket->addTag($part);
			
			$files = $this->ticket->addFilesFromPost($messageid, $this->ticket->user_id);
			$this->ticket->stripImagesFromMessage($messageid);
			
			if ($custom_subject) $subject = $this->updateSubjectFormat($this->ticket);
			
			$action_params = array('subject' => $data['title'], 'user_message' => $message, 'files' => $files, 'sender' => $this->ticket->user_id);
			SupportActions::DoAction("User_Open", $this->ticket, $action_params);
			
			// additional users and emails if posted
			if ($this->admin_create > 0)
			{
				$additionalusers = JRequest::getVar('additionalusers');
				$additionalusers = explode(",", $additionalusers);
				$this->ticket->addCC($additionalusers, 0, 0);
				
				$additionalemails = JRequest::getVar('additionalemails');
				$additionalemails = explode(",", $additionalemails);
				foreach ($additionalemails as $email)
				{
					$email = trim($email);
					if ($email == "") continue;
					
					$this->ticket->addEMailCC($email);
				}

				if ($t->admin_id != JFactory::getUser()->id && !FSS_Settings::get('suport_dont_cc_handler'))
				{
					$this->ticket->addCC(JFactory::getUser()->id, 1, 0);
				}
			}	
			
			$this->cleanAdminCreate();

			// if related is passed as part of ticket open, relate the 2 tickets
			$related = JRequest::getVar('related');
			if ($related > 0) $this->ticket->addRelated($related);
		} else {
			$this->ticket = $data;	
		}
		
		$this->errors = $errors;

		return $ok;
	}
	
	function cleanAdminCreate()
	{
		// remove any admin open stuff
		$session = JFactory::getSession();
		$session->clear('admin_create');
		$session->clear('admin_create_user_id');
	}
	
	function needFAQSearch()
	{
		if ($this->admin_create > 0) return false;
		
		$session = JFactory::getSession();
		if (FSS_Input::getString('find') == "done"  || $session->get('ticket_find'))
		{
			$session->set('ticket_find', 1);
			return false;
		}
		
		FSS_Helper::IncludeModal();
		
		$this->subject = FSS_Input::GetString('subject');
		
		$this->open_link = "index.php?option=com_fss&view=ticket&layout=open&find=done";
		if (isset($this->prodid)) $this->open_link .= "&prodid=" . $this->prodid;
		if (isset($this->deptid)) $this->open_link .= "&deptid=" . $this->deptid;
		
		$this->_display("faqs");	
		return true;
	}
	
	function updateSubjectFormat($ticket)
	{
		$ticket->loadAll();
		
		$parser = new FSSParser();	
		$ticket->forParser($parser->vars, true, false);
			
		$parser->loadText(FSS_Settings::get('support_subject_format'));

		$result = $parser->getTemplate();

		$result = strip_tags($result);

		// final fallback to prevent a ticket with no subject
		if (trim($result) == "") $result = substr(strip_tags($ticket['body']), 0, 40);
		if (trim($result) == "") $result = date("Y-m-d H:i:s");

		$db = JFactory::getDBO();
		$qry = "UPDATE #__fss_ticket_ticket SET title = '" . $db->escape($result) . "' WHERE id = " . $ticket->id;
		$db->setQuery($qry);
		$db->Query();
		
		return $result;		
	}
		
	function loadHandlers()
	{
		// load handlers if required. This depends on what product and department have been selected
		if (FSS_Settings::get('support_choose_handler') != "none")
		{
			$allow_no_auto = 0;
			
			if ($this->admin_create > 0)
			{ 
				$allow_no_auto = 1;
				$this->autohandlers = SupportUsers::getHandlersTicket($this->prodid, $this->deptid, 0);
			}
			
			$handlers = SupportUsers::getHandlersTicket($this->prodid, $this->deptid, 0, $allow_no_auto);

			/**
			 * I DONT KNOW IF THIS IS A GOOD CHANGE OR NOT, BUT IT MAKES IT CONSISTANT EVERYWHERE I THINK 
			 **/

			// if the hide super users checkbox is tickets, hide them all from the dropdown
			if (FSS_Settings::get('support_hide_super_users'))
			{
				foreach ($handlers as $offset => $handler)
				{
					$fssuser = SupportUsers::getUser($handler);
					$juser = JFactory::getUser($handler);
					if ($juser->get('isRoot') && $this->userid != $juser->id)
					{
						unset($handlers[$offset]);
					}
				}
			}

			if (count($handlers) == 0) $handlers[] = 0;
			
			$qry = "SELECT * FROM #__users WHERE id IN (" . implode(", ", $handlers) . ")";
			$db = JFactory::getDBO();
			
			$db->setQuery($qry);
			$handlers = $db->loadAssocList();
			
			$this->handlers = array();
			$h = array();
			$h['id'] = 0;
			$h['name'] = JText::_('AUTO_ASSIGN');
			$this->handlers[] = $h;
			
			if (is_array($handlers))
			{
				foreach ($handlers as $handler)
				{
					$this->handlers[] = $handler;
				}
			}
		}	
	}
	
	function needProduct()
	{
		if (!isset($this->prodid)) $this->prodid = FSS_Input::getInt('prodid');

		// prod id not set, should we display product list???
		if ($this->prodid > 0) return false;
			
		$mainframe = JFactory::getApplication();
		$this->limit = $mainframe->getUserStateFromRequest('global.list.limit_prod', 'limit', FSS_Settings::Get('ticket_prod_per_page'), 'int');
		$this->limitstart = FSS_Input::getInt('limitstart');
		$this->limitstart = ($this->limit != 0 ? (floor($this->limitstart / $this->limit) * $this->limit) : 0);
		//$this->_view->setState('limit_prod', $limit);
		//$this->_view->setState('limitstart', $limitstart);

		$this->search = FSS_Input::GetString("search");
		$this->products = SupportHelper::getProductsUserOpen($this->limitstart, $this->limit, $this->search);
		
		if (count($this->products) < 1) return false;
		
		if (count($this->products) == 1)
		{
			$this->prodid = reset($this->products)->id;
			return false;
		}	
		
		$this->pagination = new JPaginationAjax(SupportHelper::getProductsUserOpenCount($this->search), $this->limitstart, $this->limit );
		$this->_display("product");
		return true;
	}
	
	function needDepartment()
	{
		if (!isset($this->deptid)) $this->deptid = FSS_Input::getInt('deptid');
		
		// prod id not set, should we display product list???
		if ($this->deptid > 0) return false;
		
		$mainframe = JFactory::getApplication();
		$this->limit = $mainframe->getUserStateFromRequest('global.list.limit_dept', 'limit', FSS_Settings::Get('ticket_prod_per_page'), 'int');
		$this->limitstart = FSS_Input::getInt('limitstart');
		$this->limitstart = ($this->limit != 0 ? (floor($this->limitstart / $this->limit) * $this->limit) : 0);
		//$this->_view->setState('limit_prod', $limit);
		//$this->_view->setState('limitstart', $limitstart);

		$this->search = FSS_Input::GetString("search");
		$this->depts = SupportHelper::getDepartmentsUserOpen($this->prodid, $this->limitstart, $this->limit, $this->search);
		
		if (count($this->depts) < 1) return false;
		
		if (count($this->depts) == 1)
		{
			$this->deptid = reset($this->depts)->id;
			return false;
		}	
		
		$this->pagination = new JPaginationAjax(SupportHelper::getDepartmentsUserOpenCount($this->prodid, $this->search), $this->limitstart, $this->limit );
		$this->_display("department");
		return true;
	}
	
	function setupAdminCreate()
	{
		$session = JFactory::getSession();
		
		$this->userid = JFactory::getUser()->id;
		$this->email = '';
		$this->admin_create = 0;
		
		if (FSS_Input::getInt('admincreate') > 0)
		{
			$session->Set("admin_create", FSS_Input::getInt('admincreate'));
			
			if ($session->Get("admin_create") == 1 && FSS_Input::getInt('user_id') > 0)
			{
				$session->Set('admin_create_user_id', FSS_Input::getInt('user_id'));
			} else if ($session->Get("admin_create") == 1 && FSS_Input::getString('username'))
			{
				$user = JFactory::getUser(FSS_Input::getString('username'));
				$session->Set('admin_create_user_id', $user->id);
			} else if ($session->Get("admin_create") == 2 && (FSS_Input::getString('admin_create_email') || FSS_Input::getString('admin_create_name')))
			{
				$session->Set('ticket_email', FSS_Input::getEMail('admin_create_email'));
				$session->Set('ticket_name', FSS_Input::getString('admin_create_name'));
			}
		}
		
		if ($session->Get("admin_create") == 1)
		{
			$this->admin_create = 1;
			$this->user = JFactory::getUser($session->Get('admin_create_user_id'));
		} else if ($session->Get("admin_create") == 2) {
			$this->unreg_email = $session->Get('ticket_email');
			$this->unreg_name = $session->Get('ticket_name');
			$this->admin_create = 2;
		}	
	}
	
	function checkWithout()
	{
		$session = JFactory::getSession();

		if (FSS_Input::getCmd('what') == "without")
		{
			$this->email = FSS_Input::getEMail('email');
			$this->name = FSS_Input::getString('name');
			
			if ($this->name == "") $this->name = $this->email;

			if (!FSS_EMail::checkUnregEMail($this->email))
			{
				JFactory::getApplication()->redirect(FSSRoute::_("index.php?option=com_fss&view=ticket&layout=open"), JText::_("THIS_EMAIL_ADDRESS_NOT_ALLOWED"));
			}
			
			if ($this->email != "")
			{
				$session->Set('ticket_email', $this->email);
				$session->Set('ticket_name', $this->name);
				//echo "SET Session EMail : " . $session->get('ticket_email') . "<br />";
				//echo "SET Session Name : " . $session->get('ticket_name') . "<br />";
			}
		}
		
		$this->email = $session->get('ticket_email');
		$this->name = $session->get('ticket_name');
		
		//echo "Session EMail : " . $session->get('ticket_email') . "<br />";
		//echo "Session Name : " . $session->get('ticket_name') . "<br />";
	}
	
	function checkOpenUser()
	{
		$user = JFactory::getUser();
		$userid = $user->get('id');
		
		if ($userid > 0) return true;
		
		// use email for non registered ticket
		$session = JFactory::getSession();
		$sessionemail = "";

		if ($this->email == "")
		{
			$this->needLogin();
			return false;
		}
		
		if (!FSS_Helper::isValidEmail($this->email))
		{
			$this->needLogin(3);
			return false;	
		}
			
		if ($this->isDupeEmail($this->email))
		{
			$this->needLogin(1);
			return false;
		}

		return true;		
	}

	function sortCaptcha()
	{
		if ($this->admin_create > 0) FSS_Settings::set('support_captcha_type', 'none');
		$capset = FSS_Settings::get('support_captcha_type');
		if (substr($capset, 0, 3) == "ur-")
		{
			if (JFactory::getUser()->id == 0)
			{
				$capset = substr($capset, 3);	
			} else {
				$capset = "";
			}
			FSS_Settings::set('support_captcha_type', $capset);
		}
		
		$captcha = new FSS_Captcha();
		$this->captcha = $captcha->GetCaptcha('support_captcha_type');
	}

	
	function isDupeEmail($email)
	{
		if (FSS_Settings::get('support_dont_check_dupe')) return false;
		
		$db = JFactory::getDBO();
		$query = 'SELECT * FROM #__users WHERE email = "' . FSSJ3Helper::getEscaped($db, $email) . '" LIMIT 1';
		$db->setQuery($query);
		$row = $db->loadAssoc();
		
		if ($row)
		{
			if (array_key_exists('block', $row) && $row['block'] > 0) return false;
			return true;
		}
		
		return false;		
	}
}
