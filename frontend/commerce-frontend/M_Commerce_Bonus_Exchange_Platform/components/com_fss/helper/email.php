<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'fields.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'tickethelper.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_helper.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_ticket.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'mailer.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'email_to.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'parser.php');

class FSS_EMail
{
	/**
	 * Emails to users
	 */	
	// do the actual send to the users
	static function EMail_To_Ticket_User($template_id, $ticket, $subject, $body, $files, $extra_vars, $sender)
	{
		
		$ticket = SupportTicket::toObject($ticket, true);
		$ticket->loadAll();
		
		$cc_admins = $template_id == "email_on_create" || 
					 $template_id == "email_on_create_unreg" || 
					 $template_id == "email_unreg_passwords" 
					 ? false : true;

		$is_create = $template_id == "email_on_create" || 
					 $template_id == "email_on_create_unreg" 
					 ? true : false;
		
		$to = new FSS_EMail_To();
		$to->addTicketUsers($ticket);
		if ($cc_admins && FSS_Settings::get('support_email_bcc_handler')) $to->addTicketAdmins($ticket);

		$parser = new FSSParser();
		$parser->loadEmail($template_id);

		$parser->setTicket($ticket);
		$parser->setMessage($subject, $body);
		$parser->setTarget("user");
		$parser->setExtraVars($extra_vars);
		$parser->setSender(FSS_EMail_To::UserToRec($sender));

		foreach ($to->getAll() as $address)
		{	
			// sort template language
			$parser->setLanguageByUser($address->user_id, "", $ticket);	
			$parser->setTo($address);
			
			$parser->parseTicket();		
			$parser->parseSender();
			$parser->parseTo();	
			$parser->parseMessage();	

			// setup mailer
			$mailer = new FSSMailer();
			
			// dbeug info for mailer
			$mailer->addDebug('ticket', $ticket);
			$mailer->addDebug('template', $template_id);
			$mailer->addDebug('vars', $parser->vars);
			
			$mailer->isHTML($parser->getIsHtml());
			$mailer->setSubject($parser->getSubject());
			$mailer->setBody($parser->getBody());
			$mailer->addTo($address->email, $address->name, reset($address->reason));

			$session = JFactory::getSession();
			if (FSS_Settings::get('support_email_file_user') && (!$is_create || $session->Get('admin_create') > 0)) $mailer->addFiles($files);
			
			// send
			$mailer->send();
		}
		
		FSS_Helper::cleanLogs();
	}

	// Admin has replied to a ticket, send notification to user
	static function Admin_Reply($ticket, $subject, $body, $files = array(), $sender = null)
	{
		if (!self::ShouldSend('email_on_reply')) return;
		
		self::EMail_To_Ticket_User('email_on_reply', $ticket, $subject, $body, $files, array(), $sender);
	}
	
	// Admin has closed to a ticket, send notification to user
	static function Admin_Close($ticket, $subject, $body, $files = array(), $sender = null)
	{
		if (!self::ShouldSend('email_on_close')) return;
		
		self::EMail_To_Ticket_User('email_on_close', $ticket, $subject, $body, $files, array(), $sender);
	}
	
	// Ticket autoclosed, send notification to user
	static function Admin_AutoClose($ticket)
	{
		self::EMail_To_Ticket_User('email_on_autoclose', $ticket, "", "", array(), array(), null);
	}

	// User has created a ticket, send notification to user
	static function User_Create($ticket, $subject, $body, $files = array(), $sender = null)
	{
		if (!self::ShouldSend('email_on_create')) return;
		
		self::EMail_To_Ticket_User('email_on_create', $ticket, $subject, $body, $files, array(), $sender);
	}

	// Unregistered user has created a ticket, send notification to user
	static function User_Create_Unreg($ticket, $subject, $body, $files = array(), $sender = null)
	{
		if (!self::ShouldSend('email_on_create')) return;
		
		self::EMail_To_Ticket_User('email_on_create_unreg', $ticket, $subject, $body, $files, array(), $sender);
	}
	
	// Sends list of tickets and their passwords out
	static function User_Unreg_Passwords($tickets, $email = null)
	{
		$parser = new FSSParser();
		$parser->loadEmail('email_unreg_passwords');
		reset($tickets)->forParser($parser->vars, true, false);
		$parser->SetVar('passlist', self::MakePassList($tickets, $parser->getIsHtml()));		

		// set result to mailer
		$mailer = new FSSMailer();
		$mailer->addTo($email);
		$mailer->isHTML($parser->getIsHtml());
		$mailer->setSubject($parser->getSubject());
		$mailer->setBody($parser->getBody());

		// send actual mail
		$mailer->send();
	}

	/**
	 * Emails to admins
	 */	
	static function EMail_To_Ticket_Handler($template_id, $ticket, $subject, $body, $files, $extra_vars, $sender)
	{
		$ticket = SupportTicket::toObject($ticket, true);
		$ticket->loadAll();
		
		// get ticket
		
		$to = new FSS_EMail_To();
		$to->addTicketAdmins($ticket);

		$parser = new FSSParser();
		$parser->loadEmail($template_id);

		$parser->setTicket($ticket);
		$parser->setMessage($subject, $body);
		$parser->setTarget("handler");
		$parser->setExtraVars($extra_vars);
		$parser->setSender(FSS_EMail_To::UserToRec($sender));

		$ticket->loadAll();

		foreach ($to->getAll() as $address)
		{			
			// sort template language

			$parser->setLanguageByUser($address->user_id);	
			$parser->setTo($address);
			
			$parser->parseTicket();		
			$parser->parseSender();
			$parser->parseTo();	
			$parser->parseMessage();	
			
			// setup mailer
			$mailer = new FSSMailer();
			
			$mailer->addDebug('ticket', $ticket);
			$mailer->addDebug('template', $template_id);
			$mailer->addDebug('vars', $parser->vars);

			$mailer->isHTML($parser->getIsHtml());
			$mailer->setSubject($parser->getSubject());
			$mailer->setBody($parser->getBody());
			$mailer->addTo($address->email, $address->name, reset($address->reason));
			
			if (FSS_Settings::get('support_email_file_handler') == 1) $mailer->addFiles($files);
			
			// send
			$mailer->send();
		}
	}

	// User created a ticket, send notification to admin
	static function Admin_Create($ticket, $subject, $body, $files = array(), $sender = null)
	{
		if (!self::ShouldSend('email_handler_on_create')) return;
		
		self::EMail_To_Ticket_Handler('email_handler_on_create', $ticket, $subject, $body, $files, array(), $sender);
	}

	// Ticket forwarded to another admin / product / department, send notification to admins
	static function Admin_Forward($ticket, $subject, $body, $files = array(), $sender = null, $oldhandler = null)
	{
		if (!self::ShouldSend('email_handler_on_forward')) return;
		
		if ($oldhandler > 0) FSS_EMail_To::$extra_admin_to_id[$oldhandler] = "Admin: Old Handler";

		self::EMail_To_Ticket_Handler('email_handler_on_forward', $ticket, $subject, $body, $files, array(), $sender);
	}
	
	// Private message added
	static function Admin_Private($ticket, $subject, $body, $files = array(), $sender = null)
	{
		if (!self::ShouldSend('email_handler_on_private')) return;
		
		self::EMail_To_Ticket_Handler('email_handler_on_private', $ticket, $subject, $body, $files, array(), $sender);
	}

	// New ticket via email pending approval, send notification to admins
	static function Admin_Pending($ticket, $subject, $body, $files = array(), $sender = null)
	{
		if (!self::ShouldSend('email_handler_on_pending')) return;
		
		self::EMail_To_Ticket_Handler('email_handler_on_pending', $ticket, $subject, $body, $files, array(), $sender);
	}

	// User had replied to a ticket, send notification to admins
	static function User_Reply($ticket, $subject, $body, $files = array(), $sender = null)
	{
		if (!self::ShouldSend('email_handler_on_reply')) return;
		
		self::EMail_To_Ticket_Handler('email_handler_on_reply', $ticket, $subject, $body, $files, array(), $sender);
	}

	/**
	 * Misc Emails
	 **/

	// comment awaiting moderation, send notification
	static function Send_Comment($comments)
	{	
		// TODO! This needs updating to latest version of stuff
		$tpl = $comments->handler->EMail_GetTemplate($comments->moderate);

		$to = new FSS_EMail_To();
		$to->addAddress($comments->dest_email);

		$parser = new FSSParser();
		$parser->loadEmail($tpl);
		$parser->setTarget("handler");

		foreach ($comments->comment as $key => $value)
		{
			$parser->setVar($key, $value);	
		}

		$parser->setVar('moderated', $comments->moderate ? $comments->moderate : "");
		$parser->setVar('linkmod', $comments->moderate ? $comments->GetModLink() : "");	

		$comments->handler->EMail_AddFields($parser->vars);

		$customfields = "";
		
		foreach($comments->customfields as &$field)
		{
			$value = $comments->comment['custom_' . $field['id']];
			$customfields .= $field['description'] . ": " . $value . ($parser->getIsHtml() ? "<br />" : "\n");
			$parser->setVar('custom'.$field['id'], $value);
			$parser->setVar('custom_'.$field['id'], $value);
			$parser->setVar('custom_'.$field['alias'], $value);
			$parser->setVar('custom_'.$field['id'].'_name', $field['description']);
		}
		$parser->setVar('customfields', $customfields);

		if ($parser->getIsHtml())
		{
			$parser->setVar('article', "<a href='" . $parser->getVar('linkart') . "'>" . $parser->getVar('article') . "</a>");
			$parser->setVar('linkart', "<a href='" . $parser->getVar('linkart') . "'>here</a>");	
			if ($comments->moderate) $parser->setVar('linkmod', "<a href='" . $parser->getVar('linkmod') . "'>here</a>");	
		}

		foreach ($to->getAll() as $address)
		{
			$parser->setTo($address);
			
			$parser->parseTo();
			$parser->parseMessage();
			
			// setup mailer
			$mailer = new FSSMailer();
			
			// dbeug info for mailer
			$mailer->addDebug('template', $tpl);
			$mailer->addDebug('vars', $parser->vars);
			
			$mailer->isHTML($parser->getIsHtml());
			$mailer->setSubject($parser->getSubject());
			$mailer->setBody($parser->getBody());
			$mailer->addTo($address->email, $address->name, reset($address->reason));

			// send
			$mailer->send();
		}	
	}

	/*******************
	 * Helper Functions
	 *******************/	
	
	static function ShouldSend($tag)
	{
		return FSS_Settings::get('support_' . $tag);
	}
	
	static function MakePassList($tickets, $is_html)
	{
		if ($is_html)
		{
			$output[] = "<table cellspacing='4' cellpadding='4'><tr><th>".JText::_('SUBJECT')."</th><th>".JText::_('LAST_UPDATE')."</th><th>".JText::_('STATUS')."</th>";
			
			if (in_array(FSS_Settings::get('support_unreg_type'), array(1,2)))
				$output[] = "<th>".JText::_('REFERENCE')."</th></tr>";

			if (in_array(FSS_Settings::get('support_unreg_type'), array(0,1)))
				$output[] = "<th>".JText::_('PASSWORD')."</th></tr>";


			foreach ($tickets as $ticket)
			{
				$output[] = "<tr><td>" . $ticket->title . "</td>";		
				$output[] = "<td>" . FSS_Helper::Date($ticket->lastupdate, FSS_DATETIME_MID) . "</td>";		
				$output[] = "<td>" . $ticket->status . "</td>";		

				if (in_array(FSS_Settings::get('support_unreg_type'), array(1,2)))
					$output[] = "<td>" . $ticket->reference . "</td>";		

				if (in_array(FSS_Settings::get('support_unreg_type'), array(0,1)))
					$output[] = "<td>" . $ticket->password . "</td>";	

				$output[] = "</tr>";	
			}
			$output[] = "</table>";
		} else {
			$output[] = "";
			foreach ($tickets as $ticket)
			{
				$output[] = JText::_('SUBJECT') . " : " . $ticket->title . "\n";		
				$output[] = JText::_('LAST_UPDATE') . " : " . FSS_Helper::Date($ticket->lastupdate, FSS_DATETIME_MID) . "\n";		
				$output[] = JText::_('STATUS') . " : " . $ticket->status . "\n";
						
				if (in_array(FSS_Settings::get('support_unreg_type'), array(1,2)))
					$output[] = JText::_('REFERENCE') . " : " . $ticket->reference . "\n";		

				if (in_array(FSS_Settings::get('support_unreg_type'), array(0,1)))
					$output[] = JText::_('PASSWORD') . " : " . $ticket->password . "\n";	
						
				$output[] = "\n";
			}
		}
		
		return implode($output);
	}

	/*static function ParseGeneralTemplate($template, $data)
	{
		if ($template['ishtml'])
		{
			$data['body'] = str_replace("\n","<br>\r\n",$data['body']);	
		}
	
		foreach($data as $var => $value)
			$vars[] = self::BuildVar($var,$value);

		$email['subject'] = self::ParseText($template['subject'],$vars);
		$email['body'] = self::ParseText($template['body'],$vars);
	
		if ($template['ishtml'])
			$email['body'] = FSS_Helper::MaxLineLength($email['body']);
		
		return $email;			
	}*/

	//static $extra_admin_to;
	//static $current_to = array();
	//static $last_vars = array();
	
	/*static function AddTicketCC($ticket)
	{
		$t = new SupportTicket();
		$t->load($ticket->id);
		$t->loadCC();

		$names = array();


		if ($ticket['user_id'] == 0)
		{
			$names[] = $ticket->unregname;
		} else {
			$names[] = $t->name;
		}

		foreach ($t->user_cc as $user)
		{
			$names[] = $user->name;
		}

		return self::BuildVar('user_names', implode(", ", $names));
	}*/

	static function ParseMessageRows(&$messages, $ishtml, $foruser = false)
	{
		$template = self::Get_Template('messagerow');
		$result = "";
		
		foreach ($messages as &$message)
		{
			$vars = array();
			//print_p($message);
			if ($message['name'])
			{
				$vars[] = self::BuildVar('name',$message['name']);
				$vars[] = self::BuildVar('email',$message['email']);
				$vars[] = self::BuildVar('username',$message['username']);
			} else {
				$vars[] = self::BuildVar('name','Unknown');
				$vars[] = self::BuildVar('email','Unknown');
				$vars[] = self::BuildVar('username','Unknown');
			}
			$vars[] = self::BuildVar('subject',$message['subject']);
			$vars[] = self::BuildVar('posted',FSS_Helper::Date($message['posted']));
			
			$message['body'] = FSS_Helper::ParseBBCode($message['body'],null,false,false,$foruser);

			if ($ishtml)
			{
				$message['body'] = str_replace("\n","<br>\n",$message['body']);	
				$vars[] = self::BuildVar('body',$message['body'] . "<br />");	
			} else {
				$vars[] = self::BuildVar('body',$message['body'] . "\n");	
			}
			
			$result .= self::ParseText($template['body'],$vars);
		}
		
		return $result;
	}

	/*static function BuildVar($name,$value)
	{
		return array('name' => $name, 'value' => $value);
	}*/

	/*static function GetHandler($admin_id, $tmpl)
	{
		// email to user from handler, if we have a logged in handler and its not cron, change the from id
		if ( ($tmpl == "email_on_reply" || $tmpl == "email_on_close" ||
			  $tmpl == "email_on_autoclose" || $tmpl == "email_handler_on_forward") && 
				JRequest::getVar('view') != "cron")
			$admin_id = JFactory::getUser()->id;
		
		if ($admin_id == 0)
			return array("name" => JText::_("UNASSIGNED"),"username" => JText::_("UNASSIGNED"),"email" => "");	
		
		$db = JFactory::getDBO();
		$query = " SELECT * FROM #__users WHERE id = '".FSSJ3Helper::getEscaped($db, $admin_id)."'";
		$db->setQuery($query);
		$handler = $db->loadAssoc();
		return $handler;
	}*/

	/**
	 * Fetch data relating to the ticket
	 **/
	/*static function GetUser($user_id)
	{
		try {
			return JFactory::getUser($user_id);
		} catch (exception $e)
		{
			
		}
		
		return self::blankUser();
		$db = JFactory::getDBO();
		$qry = "SELECT * FROM #__users WHERE id = '".FSSJ3Helper::getEscaped($db, $user_id)."'";
		$db->setQuery($qry);
		$row = $db->loadAssoc();
		return $row;
	}*/

	/*static function GetArticle($artid)
	{
		$db = JFactory::getDBO();
		$qry = "SELECT title FROM #__fss_kb_art WHERE id = '".FSSJ3Helper::getEscaped($db, $artid)."'";	
		$db->setQuery($qry);
		$row = $db->loadAssoc();
		return $row['title'];
	}*/

	/*static function GetPriority($pri_id)
	{
		$db = JFactory::getDBO();
		$qry = "SELECT title FROM #__fss_ticket_pri WHERE id = '".FSSJ3Helper::getEscaped($db, $pri_id)."'";	
		$db->setQuery($qry);
		$row = $db->loadAssoc();
		return $row['title'];
	}*/

	/*static function GetCategory($cat_id)
	{
		$db = JFactory::getDBO();
		$qry = "SELECT title FROM #__fss_ticket_cat WHERE id = '".FSSJ3Helper::getEscaped($db, $cat_id)."'";	
		$db->setQuery($qry);
		$row = $db->loadAssoc();
		return $row['title'];
	}*/

	/*static function GetDepartment($dept_id, $field = 'title')
	{
		static $department;
		if (empty($department))
		{
			$db = JFactory::getDBO();
			$qry = "SELECT title, description FROM #__fss_ticket_dept WHERE id = '".FSSJ3Helper::getEscaped($db, $dept_id)."'";	
			$db->setQuery($qry);
			$department = $db->loadAssoc();
		}
		if (is_array($department) && array_key_exists($field, $department))
			return $department[$field];
		
		return "";
	}*/

	/*static function GetProduct($prod_id, $field = 'title')
	{
		static $product;
		if (empty($product))
		{
			$db = JFactory::getDBO();
			$qry = "SELECT * FROM #__fss_prod WHERE id = '".FSSJ3Helper::getEscaped($db, $prod_id)."'";	
			$db->setQuery($qry);
			$product = $db->loadAssoc();
		}
		if (is_array($product) && array_key_exists($field, $product))
		return $product[$field];
		
		return "";
	}*/
	
	/*static function GetMessageHist($ticket_id)
	{
		$db = JFactory::getDBO();
		$qry = "SELECT m.*, u.name, u.username, u.email FROM #__fss_ticket_messages as m";
		$qry .= " LEFT JOIN #__users as u ON m.user_id = u.id";
		$qry .= " WHERE ticket_ticket_id = '".FSSJ3Helper::getEscaped($db, $ticket_id)."'";	
		$qry .= " AND admin IN (0, 1) ORDER BY posted DESC";
		
		//echo $qry."<br>";
		$db->setQuery($qry);
		$rows = $db->loadAssocList();

		return $rows;
	}*/

	
	static function checkUnregEMail($email)
	{
		if (FSS_Settings::get('support_unreg_domain_restrict') == 0) return true;

		$lines = explode("\n", FSS_Settings::get('support_unreg_domain_list'));
		if (count($lines) < 1) return true;

		$match = false;
		foreach ($lines as $line)
		{
			$line = trim($line);
			if ($line == "") continue;

			if (substr($line, 0, 1) == "/")
			{
				// regex
				if (preg_match($line, $email))
				$match = true;
			} else {
				// domain
				if (FSS_Helper::endsWith($email, "@" . $line))
				$match = true;
			}
		}

		if (FSS_Settings::get('support_unreg_domain_restrict') == 1) return $match;
		if (FSS_Settings::get('support_unreg_domain_restrict') == 2) return !$match;
	}	
}

			       	 	 		