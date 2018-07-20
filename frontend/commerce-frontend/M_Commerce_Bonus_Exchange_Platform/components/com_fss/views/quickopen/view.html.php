a<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @deprecated 2aa0ce1ead4d5be2544cc804b91357f3
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'pagination.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'tickethelper.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'fields.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'email.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'helper.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'parser.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_actions.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_ticket.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_tickets.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'third'.DS.'simpleimage.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'multicol.php');

class FssViewQuickOpen extends FSSView
{
	function display($tpl = null)
	{
		if (FSS_Settings::Get('support_only_admin_open'))
			return $this->noPermission("Access Denied", "CREATING_NEW_TICKETS_BY_USERS_IS_CURRENTLY_DISABLED");	
		
		if (!FSS_Permission::auth("fss.ticket.open", "com_fss.support_user"))
			return FSS_Helper::NoPerm();	

		$mainframe = JFactory::getApplication();
		$user = JFactory::getUser();
		$userid = $user->get('id');
		
		$this->assign('userid',$userid);
		$this->assign('email','');
		
		// defaults for blank ticket
		
		$this->ticket = new stdClass();
		$this->ticket->prodid = FSS_Input::getInt('prodid');
		$this->ticket->deptid = FSS_Input::getInt('deptid');
		$this->ticket->catid = FSS_Input::getInt('catid');
		$this->ticket->priid = FSS_Input::getInt('priid');
		$this->ticket->subject = FSS_Input::getString('subject');
		$this->ticket->body = FSS_Input::getBBCode('body');
		
		$this->errors['subject'] = '';
		$this->errors['body'] = '';
		$this->errors['cat'] = '';

		$what = FSS_Input::getCmd('what');
		
		// done with ticket, try and save, if not, display any errors
		if ($what == "add")
		{
			if ($this->saveTicket())
			{
				echo "Saved OK!";
				exit;
				$link = 'index.php?option=com_fss&view=ticket&layout=view&Itemid=' . FSS_Input::getInt('Itemid') . '&ticketid=' . $this->ticketid;
				$mainframe->redirect($link);
				return;
			}
		}

		$this->product = $this->get('Product');
		$this->dept = $this->get('Department');
		$this->cats = SupportHelper::getCategories();
		$this->pris = SupportHelper::getPriorities();
		
		$this->support_user_attach = FSS_Settings::get('support_user_attach');
		
		$this->fields = FSSCF::GetCustomFields(0,$prodid,$deptid);

		parent::display();
	}
	
	
	function saveTicket()
	{
		$name = "";
		
		$db = JFactory::getDBO();

		$ok = true;
		$this->errors['subject'] = '';
		$this->errors['body'] = '';
		$this->errors['cat'] = '';
		
		if (FSS_Settings::get('support_subject_message_hide') == "subject")
		{
			$ticket->subject = substr(strip_tags($ticket->body), 0, 40);
		} else if ($ticket->subject == "")
		{
			$this->errors['subject'] = JText::_("YOU_MUST_ENTER_A_SUBJECT_FOR_YOUR_SUPPORT_TICKET");	
			$ok = false;
		}
		
		if (FSS_Settings::get('support_altcat'))
		{
			$cats = $this->get('Cats');
			
			if (count($cats) > 0 && $catid == 0)
			{
				$this->errors['cat'] = JText::_("YOU_MUST_SELECT_A_CATEGORY");	
				$ok = false;
			}
		}
		
		if ($body == "" && FSS_Settings::get('support_subject_message_hide') != "message")
		{
			$this->errors['body'] = JText::_("YOU_MUST_ENTER_A_MESSAGE_FOR_YOUR_SUPPORT_TICKET");	
			$ok = false;
		}
		
		$fields = FSSCF::GetCustomFields(0,$prodid,$deptid);
		if (!FSSCF::ValidateFields($fields,$this->errors))
		{
			$ok = false;	
		}
		
		$email = "";
		$password = "";
		$now = FSS_Helper::CurDate();
		
		if ($ok)
		{		
			/*$admin_id = FSS_Ticket_Helper::AssignHandler($prodid, $deptid, $catid);
			
			$now = FSS_Helper::CurDate();
			
			$def_open = FSS_Ticket_Helper::GetStatusID('def_open');
			
			$qry = "INSERT INTO #__fss_ticket_ticket (reference, ticket_status_id, ticket_pri_id, ticket_cat_id, ticket_dept_id, prod_id, title, opened, lastupdate, user_id, admin_id, email, password, unregname, lang) VALUES ";
			$qry .= "('', $def_open, '".FSSJ3Helper::getEscaped($db, $priid)."', '".FSSJ3Helper::getEscaped($db, $catid)."', '".FSSJ3Helper::getEscaped($db, $deptid)."', '".FSSJ3Helper::getEscaped($db, $prodid)."', '".FSSJ3Helper::getEscaped($db, $subject)."', '{$now}', '{$now}', '".FSSJ3Helper::getEscaped($db, $userid)."', '".FSSJ3Helper::getEscaped($db, $admin_id)."', '{$email}', '".FSSJ3Helper::getEscaped($db, $password)."', '{$name}', '".JFactory::getLanguage()->getTag()."')";
			

			$db->setQuery($qry);$db->Query();
			$this->ticketid = $db->insertid();
			
			$ref = FSS_Ticket_Helper::createRef($this->ticketid);

			$qry = "UPDATE #__fss_ticket_ticket SET reference = '".FSSJ3Helper::getEscaped($db, $ref)."' WHERE id = '" . FSSJ3Helper::getEscaped($db, $this->ticketid) . "'";  
			$db->setQuery($qry);$db->Query();


			$qry = "INSERT INTO #__fss_ticket_messages (ticket_ticket_id, subject, body, user_id, posted) VALUES ('";
			$qry .= FSSJ3Helper::getEscaped($db, $this->ticketid) . "','".FSSJ3Helper::getEscaped($db, $subject)."','".FSSJ3Helper::getEscaped($db, $body)."','".FSSJ3Helper::getEscaped($db, $userid)."','{$now}')";
			
			$db->setQuery($qry);$db->Query();
			$messageid = $db->insertid();
			
			FSSCF::StoreFields($fields,$this->ticketid);
			
			
			$files = array();
			// save any uploaded file
			
			for ($i = 1; $i < 10; $i++)
			{
				$file = JRequest::getVar('filedata_' . $i, '', 'FILES', 'array');
				if (array_key_exists('error',$file) && $file['error'] == 0 && $file['name'] != '')
				{
					$destpath = JPATH_COMPONENT_SITE.DS.'files'.DS.'support'.DS;					
					$destname = md5(mt_rand(0,999999).'-'.$file['name']); 
					
					while (JFile::exists($destpath . $destname))
					{
						$destname = md5(mt_rand(0,999999).'-'.$file['name']);               
					}
					
					if (JFile::upload($file['tmp_name'], $destpath . $destname))
					{
						$qry = "INSERT INTO #__fss_ticket_attach (ticket_ticket_id, filename, diskfile, size, user_id, added, message_id) VALUES ('";
						$qry .= FSSJ3Helper::getEscaped($db, $this->ticketid) . "',";
						$qry .= "'" . FSSJ3Helper::getEscaped($db, $file['name']) . "',";
						$qry .= "'" . FSSJ3Helper::getEscaped($db, $destname) . "',";
						$qry .= "'" . $file['size'] . "',";
						$qry .= "'" . FSSJ3Helper::getEscaped($db, $userid) . "',";
						$qry .= "'{$now}', $messageid )";
						
						
						$file_obj = new stdClass();
						$file_obj->filename = $file['name'];
						$file_obj->diskfile = $destname;
						$file_obj->size = $file['size'];
						$files[] = $file_obj;
						
						
						$db->setQuery($qry);$db->Query();     
					} else {
						// ERROR : File cannot be uploaded! try permissions	
					}
				}
			}
			
			$t = new SupportTicket();
			$t->load($this->ticketid, true);
			
			$subject = JRequest::getVar('subject','','','string');
			$body = JRequest::getVar('body','','','string', JREQUEST_ALLOWRAW);
			
			$action_name = "User_Open";
			$action_params = array('subject' => $subject, 'user_message' => $body, 'files' => $files);
			SupportActions::DoAction($action_name, $t, $action_params);*/
		}
		
		$this->errors = $errors;
		$this->ticket = $ticket;

		return $ok;
	}
	
	function noPermission($pagetitle = "INVALID_TICKET", $message = "YOU_ARE_TYING_TO_EITHER_ACCESS_AN_INVALID_TICKET_OR_DO_NOT_HAVE_PERMISSION_TO_VIEW_THIS_TICKET")
	{
		//echo dumpStack();
		
		$this->no_permission_title = $pagetitle;
		$this->no_permission_message = $message;
		
		$this->setLayout("nopermission");
		//print_r($this->ticket);
		parent::display();	    
	}

}
