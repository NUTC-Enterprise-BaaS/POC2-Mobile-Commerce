<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'settings.php' );
require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'tickethelper.php' );
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'email.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'cron'.DS.'cron.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'files.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_ticket.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_actions.php');

jimport( 'joomla.filesystem.file' );

define('FSS_EMR_SKIP_READ',1);
define('FSS_EMR_SKIP_TO',2);
define('FSS_EMR_SKIP_FROM',3);
define('FSS_EMR_SKIP_SUBJECT',4);
define('FSS_EMR_SKIP_REGONLY',5);
define('FSS_EMR_SKIP_NOTREPLY',6);
define('FSS_EMR_SKIP_UNKNOWNEMAIL',7);
define('FSS_EMR_REPLY_REG',8);
define('FSS_EMR_REPLY_UNREG',9);
define('FSS_EMR_OPEN_REG',10);
define('FSS_EMR_OPEN_UNREG',11);
define('FSS_EMR_SKIP_BULK',12);
define('FSS_EMR_SKIP_DOMAIN_RESTRICTED',13);

class FSSCronEMailCheck extends FSSCron
{
	var $params;
	var $conn;

	var $log_chunks = array();

	var $log_chunk = array();

	var $error = "";

	function Log($msg)
	{
		$this->log_chunk[] = $msg;
	}
	
	function LogNextBlock()
	{
		$this->log_chunks[] = $this->log_chunk;
		$this->log_chunk = array();
	}
	
	function LogEndMessages()
	{
		$this->log_chunks[] = $this->log_chunk;
		$this->log_chunk = array();
		
		foreach ($this->log_chunks as $log_chunk)
		{
			$this->_log .= implode("<br />", $log_chunk) . "<br />";
		}
	}
	
	function EmailResult($message, $status)
	{
		// add or update the current log to the email_log table	
		$this->Log($message);

		$msgid = $this->getCurMsgID();
		$accountid = $this->params['id'];
		
		$db = JFactory::getDBO();
		
		$qry = "SELECT * FROM #__fss_ticket_email_log WHERE accountid = '" . $db->escape($accountid) . "' AND messageident = '" . $db->escape($msgid) . "'";
		$db->setQuery($qry);
		$row = $db->loadObject();
		
		if ($row)
		{
			if ($status == $row->status) // status is same, so just update last seen date
			{
				$qry = "UPDATE #__fss_ticket_email_log SET lastseen = NOW() WHERE accountid = '" . $db->escape($accountid) . "' AND messageident = '" . $db->escape($msgid) . "'";
				$db->setQuery($qry);
				$db->Query();
			} else {
				$qry = "UPDATE #__fss_ticket_email_log SET lastseen = NOW()";
				$qry .= ", status = '" . $db->escape($status) . "'"; 
				$qry .= ", currentlog = '" . $db->escape($message) . "'"; 
				$oldlog = implode("\n", $this->log_chunk) . "\n-Previous at " . $row->lastseen . "-\n" .  $row->oldlog;
				$qry .= ", oldlog = '" . $db->escape($oldlog) . "'"; 
				$qry .= " WHERE accountid = '" . $db->escape($accountid) . "' AND messageident = '" . $db->escape($msgid) . "'";
				$db->setQuery($qry);
				$db->Query();
			}
		} else {
			$qry = "INSERT INTO #__fss_ticket_email_log (accountid, messageident, firstseen, lastseen, status, currentlog, subject, `from`, oldlog) VALUES( ";
			$qry .= "'" . $db->escape($accountid) . "', ";
			$qry .= "'" . $db->escape($msgid) . "', ";
			$qry .= "NOW(), ";
			$qry .= "NOW(), ";
			$qry .= "'" . $db->escape($status) . "', ";
			$qry .= "'" . $db->escape($message) . "', ";
			$qry .= "'" . $db->escape($this->orig_subject) . "', ";
			$qry .= "'" . $db->escape($this->from) . "', ";
			$qry .= "'" . $db->escape(implode("\n", $this->log_chunk)) . "')";
			
			$db->setQuery($qry);
			$db->Query();
		}
		
		$this->log_chunks[] = $this->log_chunk;
		$this->log_chunk = array();
	}
	
	function getCurMsgID()
	{
		$data = array();

		if (isset($this->headers->date)) $data[] = json_encode($this->headers->date);
		if (isset($this->headers->subject)) $data[] = json_encode($this->headers->subject);
		if (isset($this->headers->message_id)) $data[] = json_encode($this->headers->message_id);
		if (isset($this->headers->to)) $data[] = json_encode($this->headers->to);
		if (isset($this->headers->from)) $data[] = json_encode($this->headers->from);
		
		$text = implode($data);
		
		return substr(md5($text), 0, 12);
	}

	function Execute($aparams)
	{
		if (!function_exists("imap_open"))
		{
			$hoard_imap = JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'cron'.DS.'horde'.DS.'emailcheck_horde.php';
			$hoard_lib = JPATH_SITE.DS."Horde".DS."Autoloader".DS."Default.php";
			
			if (file_exists($hoard_imap) && file_exists($hoard_lib))
			{
				require_once($hoard_imap);	
				
				$check = new FSSCronEMailCheck_Horde();
				$result = $check->Execute($aparams);
				$this->_log = $check->_log;
				return $result;
			} else {
				return $this->Log("mod_imap not enabled in php config");
			}
		}
		
		FSS_Helper::cleanLogs();
		
		$this->Log("Checking email account - {$aparams['name']}");
		/*if (JRequest::getVar('email') != 1)
			return;*/

		if (!$aparams['server'])
			return $this->Log("No server specified");
		if (!$aparams['port'])
			return $this->Log("No port specified");
		if (!$aparams['username'])
			return $this->Log("No username specified");
		if (!$aparams['password'])
			return $this->Log("No password specified");

		$this->params = $aparams;

		$this->connect();

		if (!$this->conn)
		{
			$errors = imap_errors();
			$this->Log("Unable to connect");
			foreach($errors as $error)
				$this->Log($error);
			$this->error .= implode("<br>",$errors);
			return;
		}

		$mails = imap_search($this->conn, 'UNSEEN'); //analitica.ru

		if ($mails === false)
		{
			$errors = imap_errors();
			if (is_array($errors) && count($errors) > 0)
			{
				$this->Log("Unable to search messages");
				foreach($errors as $error)
					$this->Log($error);
				$this->error .= implode("<br>",$errors);
				$this->disconnect();
				$this->LogEndMessages();
				return;
			}
			$this->Log("No messages");
			$this->disconnect();
			$this->LogEndMessages();
			return;
		}
			
		$msgcount = count($mails);
		
		if ($msgcount == 0 || !is_array($mails))
		{
			$this->Log("No messages");
			$this->disconnect();
			$this->LogEndMessages();
			
			return;	
		} else {
			$this->Log("$msgcount messaeges");	
		}
	
		// only get the first 20 messages to make sure web page response is quicker
		// this only happens 
		if (JRequest::getVar('option') != "com_fss" || JRequest::getVar('view') != "cron")
		{
			$this->Log("Running as web plugin, trim messages to 20");
			$mails = array_slice($mails, -20);
		} elseif ($msgcount > 50) {
			$this->Log("Large quantity of unread messages, trim messages to 50");
			$mails = array_slice($mails, -50);
		}
		
		$this->LogNextBlock();

		foreach ($mails as $i)
		{
			$this->Log("---------------------");
			$this->Log("Processing message $i");

			$this->LogNextBlock();

			$this->attachments = array();

			// get headres of message
			if (!$this->GetHeaders($i)) 
			{
				$this->Log("Error getting headers");
				continue;
			}

			if (!empty($this->headers->subject))
			{
				$this->Log("Subject : {$this->headers->subject->text}");
				
				if ($this->Fix_Re2($this->headers->subject->text))
				{
					$this->Log("Subject Fixed To: {$this->headers->subject->text}");
				}
			}

			if (isset($this->headers->reply_to[0]))
				$this->headers->from[0] = $this->headers->reply_to[0];
			
			if ($this->headers->from[0]->mailbox == "no-reply" && isset($this->headers->from[0]->personal))
			{
				// no-reply from form builder, attempt to find address in name
				$text = $this->headers->from[0]->personal;
				//$text = str_replace("\"","",$text);
				
				$matches = array();
				$pattern="/(?:[a-z0-9!#$%&'*+=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/";
				preg_match_all($pattern, $text, $matches);

				//print_p($matches);
				if (count($matches[0]) > 0)
				{
					// new email found
					$newemail = $matches[0][0];
					list($name, $host) = explode("@", $newemail);
					
					$this->Log("No Reply message, New From address : $name@$host");
					
					$this->headers->from[0]->mailbox = $name;
					$this->headers->from[0]->host = $host;
					$this->headers->from[0]->personal = trim(str_replace("$name@$host","",$this->headers->from[0]->personal));
				}
			}
			
			SupportActions::DoAction("beforeEMailImport", null, array('headers' => $this->headers));

			if (isset($this->headers->from[0]->personal))
			{
				$this->Log("From : {$this->headers->from[0]->mailbox}@{$this->headers->from[0]->host} ({$this->headers->from[0]->personal})");
				$this->from = "{$this->headers->from[0]->mailbox}@{$this->headers->from[0]->host} ({$this->headers->from[0]->personal})";
			} else {
				$this->Log("From : {$this->headers->from[0]->mailbox}@{$this->headers->from[0]->host}");	
				$this->from = "{$this->headers->from[0]->mailbox}@{$this->headers->from[0]->host}";			
			}
			
			$this->orig_subject = "";
			if (isset($this->headers->subject))
				$this->orig_subject = $this->headers->subject->text;

			if ($this->TestForBulk())
			{
				$this->EmailResult("Bulk EMail / Auto Reply detected, skipping", FSS_EMR_SKIP_BULK);
				continue;
			}

			// validate to address is required
			if (!$this->ValidateToAddress())
			{
				$this->EmailResult("Skipping invalid to address", FSS_EMR_SKIP_TO);
				continue;
			}
			
			if (!$this->ValidateFromAddress())
			{
				$this->EmailResult("Skipping due to from address", FSS_EMR_SKIP_FROM);
				continue;	
			}
			
			if (!$this->ValidSubject())
			{
				$this->EmailResult("Skipping due to invalid subject", FSS_EMR_SKIP_SUBJECT);
				continue;	
			}
			
			$s = isset($this->headers->subject->text) ? $this->headers->subject->text : "";

			//check subject and to email to see if we have found a user and or ticket
			list($ticketid, $userid, $subject) = $this->ParseSubject($s, $this->headers->from[0]->mailbox . '@' . $this->headers->from[0]->host);

			if (!isset($this->headers->subject)) $this->headers->subject = new stdClass();

			$this->headers->subject->text = $subject;

			// ok, need to get the message as we have decided its ok to use this ticket
			
			if ($ticketid < 1 && $userid < 1 && $this->params['newticketsfrom'] == "registered")
			{
				$this->EmailResult("Skipping as registered only and not a registered email", FSS_EMR_SKIP_REGONLY);
				continue;
			}

			// if userid < 1 and in have restricted domains, check the email is ok or not
			if ($userid < 1 && !FSS_EMail::checkUnregEMail($this->headers->from[0]->mailbox . '@' . $this->headers->from[0]->host))
			{
				$this->EmailResult("Unable to reply or open ticket, domain is restricted (" . $this->headers->from[0]->host . ").", FSS_EMR_SKIP_DOMAIN_RESTRICTED);
				continue;
			}
			

			if ($this->params['allowrepliesonly'] && $ticketid < 1)
			{
				$this->EmailResult("Skipping as not a reply", FSS_EMR_SKIP_NOTREPLY);
				continue;
			}
			
			$this->messagetime = strtotime($this->headers->date->text);
			$this->Log("EMail Time: " . $this->headers->date->text);
			
			// check if user is handler
			$handlers = SupportUsers::getHandlers();
			$is_handler = false;

			if ($userid > 0)
			{
				foreach ($handlers as $handler)
				{
					if ($handler->id ==	$userid)
					{
						$is_handler = true;	
					}
				}	
			}	
				
			// validate that the ticket is being replied to by user or handler
			if ($ticketid > 0)
			{
				$ticket = $this->getTicket($ticketid);
				
				$this->Log("Ticket {$ticketid}, " . $ticket['title']);


				if ($is_handler)
				{
					// check if assign on reply if no current handler
					// check if Take ownership on handler reply 
					if ( (FSS_Settings::get('support_autoassign') == 3 && $ticket['handler_id'] < 1) || FSS_Settings::get('support_assign_reply'))
					{
						$this->Log("Re-Assign ticket to $user");
						$t = new SupportTicket();
						$t->load($ticketid, "force");
						$t->assignHandler($userid, TICKET_ASSIGN_TOOK_OWNER);
						$ticket['handler_id'] = $userid;
					}
				} else {
					// user replying to a ticket
					$t = new SupportTicket();
					$t->load($ticketid, "force");
					if ($t->is_closed)
					{
						if ($aparams['closedticket'] == 2)
						{
							$this->Log("Ticket is closed, ignoring email.");
						}
						if ($aparams['closedticket'] == 1)
						{
							$this->Log("Ticket $ticketid is closed, opening new ticket.");
							$ticketid = 0;
							unset($ticket);
						}
					}	
				}
			}
			
			$this->GetMessage($i);

			if ($aparams['onimport'] == "delete" || $aparams['type'] != "imap")
			{
				imap_delete($this->conn, $i);
			} else {
				imap_setflag_full($this->conn, $i, "\\Seen");
			}

			$this->TrimMessage();

			$messageid = 0;

			$this->should_close = false;

			// clear attachment list for all emails
			$this->files = array();

			$is_reply = false;
			// add to existing ticket
			if ($ticketid > 0)
			{
				$filesok = true;
				
				/*echo "EMail : " . $ticket['email'] . "<br>";
				echo "USer ID : " . $userid . "<br>";
				echo "Reply From : {$this->headers->from[0]->mailbox}@{$this->headers->from[0]->host}<br>";
				echo "Ticekt User ID : " . $ticket['user_id']  . "<br>";*/

				if ($userid > 0 && $is_handler) // user is a handler replying, so add as admin
				{
					// check message for [[CLOSE]] tag, only if user replying is the ticket handlers primary handler
					$tag = "[[CLOSE]]";
					
					if (strpos($this->plainmsg, $tag) !== false && $userid == $ticket['handler_id'])
					{
						$this->Log("$tag found in ticket and is primary handler, closing ticket.");
						$this->should_close = true;
					}
					
					//echo "Adding admin message to ticket - {$ticket['user_id']} -> {$userid}<br>";
					$this->DoTicketReply($ticketid,$userid,1,$messageid);	
					$is_reply = true;
					
				} else if ($ticket['email'] == "{$this->headers->from[0]->mailbox}@{$this->headers->from[0]->host}") // unregistered ticket, with matching email 
				{
					$this->DoTicketReply($ticketid,$userid,0,$messageid, "{$this->headers->from[0]->personal}", "{$this->headers->from[0]->mailbox}@{$this->headers->from[0]->host}");
					$is_reply = true;

				} else if ($this->canReplyToTicket($ticketid, $userid)) // user is the tickets main user
				{
					$this->DoTicketReply($ticketid,$userid,0,$messageid);
					$is_reply = true;
					if (!FSS_Settings::get('support_user_attach')) $filesok = false;
					
				} else if ($this->onTicketCC($ticketid, "{$this->headers->from[0]->mailbox}@{$this->headers->from[0]->host}")) // user is the tickets main user
				{
					$this->DoTicketReply($ticketid,$userid,0,$messageid, "{$this->headers->from[0]->personal}", "{$this->headers->from[0]->mailbox}@{$this->headers->from[0]->host}");
					$is_reply = true;
					
				} elseif ($this->params['allowunknown'])
				{
					$from_email = "{$this->headers->from[0]->mailbox}@{$this->headers->from[0]->host}";
					$from_name = "";
					if (isset($this->headers->from[0]->personal))
					$from_name = $this->headers->from[0]->personal;
					
					// unreg ticket, add users reply
					$this->DoTicketReply($ticketid,0,0,$messageid,$from_name,$from_email);	
					$is_reply = true;
				} else { // nothing found for the reply					
					$this->EmailResult("Unknown email replying to the message, ignore the email ({$this->headers->from[0]->mailbox}@{$this->headers->from[0]->host})", FSS_EMR_SKIP_UNKNOWNEMAIL);
					continue;
				}
				

				if ($filesok)
				{
					$this->AttachFiles($ticketid, $userid, $messageid);
					$this->processInlineImages($messageid);
				}		
						
			} else if ($userid > 0) // open new ticket for registered user	
			{
				//echo "Opening new ticket<br>";
				$ticketid = $this->OpenNewTicket($userid,$messageid);
			} else { // open ticket for unregistered user
				//echo "Opening new ticket for unreg user<br>";
				$ticketid = $this->OpenNewTicketUnreg($messageid);
			}
			
			$ticket = new SupportTicket();
			$ticket->load($ticketid, "force");
			
			if ($userid > 0)
			{
				$user = JFactory::getUser($userid);
				$type = $is_reply ? FSS_EMR_REPLY_REG : FSS_EMR_OPEN_REG;
				$this->EmailResult("Ticket ID : $ticketid - {$ticket->reference}, UserID : $userid - {$user->name} ({$user->username})", $type);
			} else {
				$type = $is_reply ? FSS_EMR_REPLY_UNREG : FSS_EMR_OPEN_UNREG;
				$this->EmailResult("Ticket ID : $ticketid - {$ticket->reference}, Unregistered User", $type);
			}

		}
			
		$this->LogEndMessages();

		imap_expunge($this->conn);
		$this->disconnect();
		//echo "</div>";
	}
	
	function canReplyToTicket($ticketid, $userid)
	{
		$t = new SupportTicket();
		$t->load($ticketid, (int)$userid);

		if (!$t->loaded) { return false; }
		
		$t->setupUserPerimssions((int)$userid);
		
		if (!$t->readonly) return true;
		
		return false;	
	}
	
	function onTicketCC($ticketid, $email)
	{
		$t = new SupportTicket();
		$t->load($ticketid, "force");
		$t->loadCC();
		
		foreach ($t->user_cc as $user_cc)
		{
			if ($user_cc->uremail == $email)
				return true;
		}
		
		return false;
	}
	
	function Fix_Re2(&$text)
	{
		if (preg_match("/(re|fwd)\[\d{1,3}\]\:/i", $text))
		{
			$text = preg_replace("/\[\d{1,2}\]\:/i",":", $text);
			return true;
		}
		
		return false;
	}

	function Test($aparams)
	{
		if (!function_exists("imap_open"))
		{
			$this->error = "mod_imap not enabled in php config";
			return $this->Log("mod_imap not enabled in php config");
		}
		
		$this->params = $aparams;

		if (!isset($this->error)) $this->error = "";

		$this->connect();
		if (!$this->conn)
		{
			$errors = imap_errors();
			$this->Log("Unable to connect");
			foreach($errors as $error)
				$this->Log($error);
			$this->error .= implode("<br>",$errors);
			return false;
		} else {
			$mails = imap_search($this->conn, 'UNSEEN'); //analitica.ru
			if ($mails === false)
			{
				$errors = imap_errors();
				if (is_array($errors) && count($errors) > 0)
				{
					$this->Log("Unable to search messages");
					foreach($errors as $error)
						$this->Log($error);
					$this->error .= implode("<br>",$errors);
					return false;
				}
				$this->count = "0 unread out of " . imap_num_msg($this->conn);
			} else {
				$this->count = count($mails) . " unread out of " . imap_num_msg($this->conn);
			}
		}
		$this->disconnect();
		return true;	
	}

	function OpenNewTicket($userid,&$messageid)
	{
		$db = JFactory::getDBO();
		
		$priid = $this->params['pri_id'];	
		$catid = $this->params['cat_id'];	
		$deptid = $this->params['dept_id'];	
		$prodid = $this->params['prod_id'];

		$params = array(
			'title' => $this->headers->subject->text,
			'user_id' => $userid,
			'email' => '',
			'unregname' => '',
			'source' => 'new_ticket_email'
			);
			
		$admin_id = FSS_Ticket_Helper::AssignHandler($prodid, $deptid, $catid, false, $params);
		
		$subject = $this->headers->subject->text;
		$body = $this->plainmsg;
		
		$now = FSS_Helper::CurDate();
		
		if ($this->messagetime > 0)
			$now = date("Y-m-d H:i:s", $this->messagetime);
		
		$def_open = FSS_Ticket_Helper::GetStatusID('def_open');

		$source = "email_accepted";
		if (isset($this->params['confirmnew']) && $this->params['confirmnew'] == 1)
			$source = "email";
		
		$qry = "INSERT INTO #__fss_ticket_ticket (reference, ticket_status_id, ticket_pri_id, ticket_cat_id, ticket_dept_id, prod_id, title, opened, lastupdate, user_id, admin_id, email, password, unregname, source) VALUES ";
		$qry .= "('', $def_open, '".FSSJ3Helper::getEscaped($db, $priid)."', '".FSSJ3Helper::getEscaped($db, $catid)."', '".FSSJ3Helper::getEscaped($db, $deptid)."', '".FSSJ3Helper::getEscaped($db, $prodid)."', '".FSSJ3Helper::getEscaped($db, $subject)."', '{$now}', '{$now}', '".FSSJ3Helper::getEscaped($db, $userid)."', '".FSSJ3Helper::getEscaped($db, $admin_id)."', '', '', '', '$source')";
		//echo $qry."<br>";	
		$db->setQuery($qry);$db->Query();
		$ticketid = $db->insertid();
		$ref = FSS_Ticket_Helper::createRef($ticketid);

		$qry = "UPDATE #__fss_ticket_ticket SET reference = '".FSSJ3Helper::getEscaped($db, $ref)."' WHERE id = '" . FSSJ3Helper::getEscaped($db, $ticketid) . "'";  
		$db->setQuery($qry);$db->Query();
		//echo $qry."<br>";	

		$qry = "INSERT INTO #__fss_ticket_messages (ticket_ticket_id, subject, body, user_id, posted, source) VALUES ('";
		$qry .= FSSJ3Helper::getEscaped($db, $ticketid) . "','".FSSJ3Helper::getEscaped($db, $subject)."','".FSSJ3Helper::getEscaped($db, $body)."','".FSSJ3Helper::getEscaped($db, $userid)."','{$now}', 'email')";
		//$this->Log($qry);	
		$db->setQuery($qry);$db->Query();
	
		$messageid = $db->insertid();
	
		// attach files to ticket
		if (FSS_Settings::get('support_user_attach'))
		{
			$this->AttachFiles($ticketid, $userid, $messageid);		
			$this->processInlineImages($messageid, $body);
		}

		$ticket = new SupportTicket();
		$ticket->load($ticketid);
		
		if (isset($this->params['confirmnew']) && $this->params['confirmnew'] == 1)
		{
			// send admin pending email
			FSS_EMail::Admin_Pending($ticket, $subject, $body);
		} else {
			FSS_EMail::User_Create($ticket, $subject, $body);
			FSS_EMail::Admin_Create($ticket, $subject, $body, $this->files);
		}
		
		return $ticketid; 
	}

	function OpenNewTicketUnreg($messageid)
	{
		$db = JFactory::getDBO();
		
		$priid = $this->params['pri_id'];	
		$catid = $this->params['cat_id'];	
		$deptid = $this->params['dept_id'];	
		$prodid = $this->params['prod_id'];
		$userid = 0;
		
		$subject = $this->headers->subject->text;
		$body = $this->plainmsg;
		
		$email = "{$this->headers->from[0]->mailbox}@{$this->headers->from[0]->host}";
		$name = "";

		if (isset($this->headers->from[0]->personal))
			$name = $this->headers->from[0]->personal;

		if (trim($name) == "")
			$name = $email;
		
		$this->Log("Unreg Name : $name");
		
		$params = array(
			'title' => $subject,
			'user_id' => 0,
			'email' => $email,
			'unregname' => $name,
			'source' => 'new_ticket_email'
			);
			
		$admin_id = FSS_Ticket_Helper::AssignHandler($prodid, $deptid, $catid, false, $params);

		$password = FSS_Helper::createRandomPassword();	
		$now = FSS_Helper::CurDate();
		
		if ($this->messagetime > 0)
			$now = date("Y-m-d H:i:s", $this->messagetime);
		
		$def_open = FSS_Ticket_Helper::GetStatusID('def_open');

		$source = "email_accepted";
		if (isset($this->params['confirmnew']) && $this->params['confirmnew'])
			$source = "email";
		
		$qry = "INSERT INTO #__fss_ticket_ticket (reference, ticket_status_id, ticket_pri_id, ticket_cat_id, ticket_dept_id, prod_id, title, opened, lastupdate, user_id, admin_id, email, password, unregname, source) VALUES ";
		$qry .= "('', $def_open, '".FSSJ3Helper::getEscaped($db, $priid)."', '".FSSJ3Helper::getEscaped($db, $catid)."', '".FSSJ3Helper::getEscaped($db, $deptid)."', '".FSSJ3Helper::getEscaped($db, $prodid)."', '".FSSJ3Helper::getEscaped($db, $subject)."', '{$now}', '{$now}', '".FSSJ3Helper::getEscaped($db, $userid)."', '".FSSJ3Helper::getEscaped($db, $admin_id)."', '".FSSJ3Helper::getEscaped($db, $email)."', '".FSSJ3Helper::getEscaped($db, $password)."', '".FSSJ3Helper::getEscaped($db, $name)."', '$source')";
		//echo $qry."<br>";	
		$db->setQuery($qry);$db->Query();
		$ticketid = $db->insertid();
		$ref = FSS_Ticket_Helper::createRef($ticketid);

		$qry = "UPDATE #__fss_ticket_ticket SET reference = '".FSSJ3Helper::getEscaped($db, $ref)."' WHERE id = '" . FSSJ3Helper::getEscaped($db, $ticketid) . "'";  
		$db->setQuery($qry);$db->Query();
		//echo $qry."<br>";	
		

		$qry = "INSERT INTO #__fss_ticket_messages (ticket_ticket_id, subject, body, user_id, posted, source, poster, email) VALUES ('";
		$qry .= FSSJ3Helper::getEscaped($db, $ticketid) . "','".FSSJ3Helper::getEscaped($db, $subject)."','".FSSJ3Helper::getEscaped($db, $body)."','".FSSJ3Helper::getEscaped($db, $userid)."','{$now}', 'email', '".FSSJ3Helper::getEscaped($db, $name)."', '".FSSJ3Helper::getEscaped($db, $email)."')";
		$db->setQuery($qry);$db->Query();
		$messageid = $db->insertid();	

		// attach files to ticket
		if (FSS_Settings::get('support_user_attach') > 1)
		{
			$this->AttachFiles($ticketid, $userid, $messageid);
			$this->processInlineImages($messageid, $body);
		}

		// process body for inline attachments

		$ticket = new SupportTicket();
		$ticket->load($ticketid);
		
		if (isset($this->params['confirmnew']) && $this->params['confirmnew'])
		{
			// send admin pending email
			FSS_EMail::Admin_Pending($ticket, $subject, $body, array(), null);
		} else {	
			FSS_EMail::User_Create_Unreg($ticket, $subject, $body, array(), null);
			FSS_EMail::Admin_Create($ticket, $subject, $body, $this->files, null);
		}
		
		return $ticketid; 
	}

	function processInlineImages($message_id, $body = null)
	{
		// scan body for [cid:image001.png@01D04526.5927F8D0] and replace with any images attached to that message
		$db = JFactory::getDBO();
						
		if ($body == "")
		{
			$qry = "SELECT * FROM #__fss_ticket_messages WHERE id = " . $db->escape($message_id);
			$db->setQuery($qry);
			$message = $db->loadObject();
			
			if (!$message) return;
			
			$body = $message->body;
		}

		$qry = "SELECT * FROM #__fss_ticket_attach WHERE message_id = " . $db->escape($message_id);
		$db->setQuery($qry);
		$attach = $db->loadObjectList();


		if (preg_match_all("/\[cid\:(.*)\@.*\]/", $body, $matches))
		{
			$changed = false;
			foreach ($matches[0] as $offset => $match)
			{
				$image = $matches[1][$offset];

				foreach ($attach as $att)
				{
					if ($att->filename == $image)
					{
						$attach_id = $att->id;
						$key = FSS_Helper::base64url_encode(FSS_Helper::encrypt($attach_id, FSS_Helper::getEncKey("file")));
						$replace = "[img]" . JURI::base() . "index.php?option=com_fss&view=image&fileid={$attach_id}&key={$key}" . "[/img]";
						$body = str_replace($match, $replace, $body);

						$changed = true;
					}
				}
			}
			
			if ($changed)
			{
				$qry = "UPDATE #__fss_ticket_messages SET body = '" . $db->escape($body). "' WHERE id = " . $db->escape($message_id);
				$db->setQuery($qry);
				$db->Query();
			}
		}



		return $body;
	}

	function AttachFiles($ticketid, $userid, $messageid)
	{
		$db = JFactory::getDBO();

		if (empty($this->attachments))
			return false;
			
		if (!is_array($this->attachments))
			return false;
		
		if (count($this->attachments) == 0)
			return false;

		$now = FSS_Helper::CurDate();

		$st = new SupportTicket();
		$st->load($ticketid);

		foreach ($this->attachments as $filename => &$data)
		{
			$new = imap_mime_header_decode($filename);
			if (function_exists("mb"))
			{
				$filename = @mb_convert_encoding($new[0]->text, "UTF-8", $new[0]->charset);
			} else {
				$filename = htmlspecialchars_decode(utf8_decode(htmlentities($new[0]->text, ENT_COMPAT, 'utf-8', false)));	
			}
			
			$this->Log("Attachment : $filename - " . strlen($data));

			$destpath = JPATH_SITE.DS.FSS_Helper::getAttachLocation().DS.'support'.DS;					
			$destname = FSS_File_Helper::makeAttachFilename("support", $filename, date("Y-m-d"), $st, $userid);

			if (JFile::write($destpath.$destname, $data))
			{
				$this->Log("Wrote file to $destname");
				$qry = "INSERT INTO #__fss_ticket_attach (ticket_ticket_id, filename, diskfile, size, user_id, added, message_id) VALUES ('";
				$qry .= FSSJ3Helper::getEscaped($db, $ticketid) . "',";
				$qry .= "'" . FSSJ3Helper::getEscaped($db, $filename) . "',";
				$qry .= "'" . FSSJ3Helper::getEscaped($db, $destname) . "',";
				$qry .= "'" . strlen($data) . "',";
	            $qry .= "'" . FSSJ3Helper::getEscaped($db, $userid) . "',";
				$qry .= "'{$now}', $messageid )";
	            	
	            $db->setQuery($qry);$db->Query();     
				
				$file_obj = new stdClass();
				$file_obj->filename = $filename;
				$file_obj->diskfile = $destname;
				$file_obj->size = strlen($data);
				$this->files[] = $file_obj;

			} else {
	            // ERROR : File cannot be uploaded! try permissions	
			}
		}

		return count($this->files);
	}

	function DoTicketReply($ticketid, $userid, $isadmin, &$messageid, $unreg_name = '', $unreg_email = '')
	{
		$db = JFactory::getDBO();

		$subject = $this->headers->subject->text;
		$body = $this->plainmsg;

		$now = FSS_Helper::CurDate();
		
		if ($this->messagetime > 0)
			$now = date("Y-m-d H:i:s", $this->messagetime);
		
		if ($body)
		{
			$qry = "INSERT INTO #__fss_ticket_messages (ticket_ticket_id, subject, body, user_id, admin, posted, poster, email, source) VALUES ('";
			$qry .= FSSJ3Helper::getEscaped($db, $ticketid) . "','".FSSJ3Helper::getEscaped($db, $subject)."','".FSSJ3Helper::getEscaped($db, $body)."','".FSSJ3Helper::getEscaped($db, $userid)."', '".FSSJ3Helper::getEscaped($db, $isadmin)."', '{$now}', '".FSSJ3Helper::getEscaped($db, $unreg_name)."', '".FSSJ3Helper::getEscaped($db, $unreg_email)."', 'email')";
			$db->setQuery($qry);$db->Query();
			$messageid = $db->insertid();
			
			$qry = "SELECT ticket_status_id FROM #__fss_ticket_ticket WHERE id = '".FSSJ3Helper::getEscaped($db, $ticketid)."'";
			$db->setQuery($qry);
			$status = $db->loadAssoc();
			
			
			if ($this->should_close)
			{
				$newstatus = FSS_Ticket_Helper::GetStatusID('def_closed');
			} else if ($isadmin)
			{
				$newstatus = FSS_Ticket_Helper::GetStatusID('def_admin');
			} else {
				$newstatus = FSS_Ticket_Helper::GetStatusID('def_user');
			}
			
			if ($newstatus > 0)
			{
				$qry = "UPDATE #__fss_ticket_ticket SET ticket_status_id = '".FSSJ3Helper::getEscaped($db, $newstatus)."', closed = NULL WHERE id = '".FSSJ3Helper::getEscaped($db, $ticketid)."'";
			} else {
				$qry = "UPDATE #__fss_ticket_ticket SET closed = NULL WHERE id = '".FSSJ3Helper::getEscaped($db, $ticketid)."'";
			}
			
			$db->setQuery($qry);
			$db->Query();
			
			if ($newstatus > 0)
			{
				$oldstatus = $this->GetStatus($status['ticket_status_id']);
				$newstatus = $this->GetStatus($newstatus);
				$this->AddTicketAuditNote($ticketid,"Status changed from '" . $oldstatus['title'] . "' to '" . $newstatus['title'] . "'",$userid);
			}
		}
		
		$qry = "UPDATE #__fss_ticket_ticket SET lastupdate = '{$now}' WHERE id = '".FSSJ3Helper::getEscaped($db, $ticketid)."'";
		$db->setQuery($qry);
		$db->Query(); 
		
		$ticket = new SupportTicket();
		$ticket->load($ticketid);

		if ($isadmin)
		{
			FSS_EMail::Admin_Reply($ticket, $subject, $body, array(), null);
		} else {
			FSS_EMail::User_Reply($ticket, $subject, $body, array(), null);			
		}
	}

	function &getTicket($ticketid)
	{
		$db = JFactory::getDBO();

		$query = "SELECT t.*, u.name, u.username, p.title as product, d.title as dept, c.title as cat, s.title as status, ";
		$query .= "s.color as scolor, s.id as sid, pr.title as pri, pr.color as pcolor, pr.id as pid, au.name as assigned, au.id as handler_id ";
		$query .= " FROM #__fss_ticket_ticket as t ";
		$query .= " LEFT JOIN #__users as u ON t.user_id = u.id ";
		$query .= " LEFT JOIN #__fss_prod as p ON t.prod_id = p.id ";
		$query .= " LEFT JOIN #__fss_ticket_dept as d ON t.ticket_dept_id = d.id ";
		$query .= " LEFT JOIN #__fss_ticket_cat as c ON t.ticket_cat_id = c.id ";
		$query .= " LEFT JOIN #__fss_ticket_status as s ON t.ticket_status_id = s.id ";
		$query .= " LEFT JOIN #__fss_ticket_pri as pr ON t.ticket_pri_id = pr.id ";
		$query .= " LEFT JOIN #__users as au ON au.id = t.admin_id ";
		$query .= " WHERE t.id = '".FSSJ3Helper::getEscaped($db, $ticketid)."' ";

		$db->setQuery($query);
		$rows = $db->loadAssoc();
		return $rows;   		
	}

	function TrimMessage()
	{
		$lines = explode("\n",$this->plainmsg);
		
		$html_mode = false;
		if (substr($this->plainmsg, 0,9) == "{RAWHTML}") $html_mode = true;
		
		$this->plainmsg = array();
		
		require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'xml.php');
		
		$path = JPATH_ROOT.DS.'components'.DS.'com_fss'.DS.'plugins'.DS.'emailcheck'.DS.'trim'.DS;
		$files = JFolder::files($path,'(.xml$)');

		$matches = array();

		foreach ($files as $file)
		{
			$xml = simplexml_load_file($path.$file);
			foreach ($xml->trimmatch as $match)
			{
				$matches[] = FSJ_XML::XMLToClass($match, "TrimMatch");
			}
		}
		
		$found_trim = false;

		foreach($lines as $offset => $line)
		{	
			$start_trim = false;

			foreach ($matches as $match)
			{
				if ($html_mode && !$match->rawhtml) continue;
				if (!$html_mode && !$match->plaintext) continue;
				
							
				$match_offset = $match->Match($line, $lines, $offset, $html_mode);
				if ($match_offset !== false)
				{
					$start_trim = $match_offset;
				}
			}
			
			if ($start_trim !== false && !$found_trim)
			{
				if (!$html_mode)
				{
					while (true && count($this->plainmsg))
					{
						$last = end($this->plainmsg);
						$last = trim($last);

						if ($last == "")
						{
							array_pop($this->plainmsg);
						} else {
							break;
						}
					}
					$this->plainmsg[] = "[quoted]";
				} else {
					$line1 = substr($line, 0, $start_trim);
					$line2 = substr($line, $start_trim);
					
					$this->plainmsg[] = $line1;
					$this->plainmsg[] = "[quoted]";
					$this->plainmsg[] = $line2;
				}

				$found_trim = true;
			}

			$this->plainmsg[] = $line;
		}	

		if ($found_trim)
			$this->plainmsg[] = "[/quoted]";

		for ($i = count($this->plainmsg) - 1; $i > 0; $i--)
		{
			if (trim($this->plainmsg[$i]) == "")
			{
				unset($this->plainmsg[$i]);
			} else {
				break;
			}				
		}

		$this->plainmsg = implode("\n",$this->plainmsg);
	}
	
	function connect()
	{		
		$server = $this->params['server'];
		$port = $this->params['port'];
		$flags = '/'.$this->params['type'];
		if ($this->params['usessl'])
			$flags .= '/ssl';
		if ($this->params['usetls'] == 1)
		{
			$flags .= '/tls';
		} elseif ($this->params['usetls'] == 2) {
			$flags .= '/notls';	
		}
		
		if ($this->params['validatecert'] == 0)
		{
			if ($this->params['usessl'] || $this->params['usetls'] == 1) $flags .= '/novalidate-cert';
		} else if ($this->params['validatecert'] == 1) {
			$flags .= '/validate-cert';
		} else if ($this->params['validatecert'] == 2) {
			$flags .= '/novalidate-cert';
		}
		
		$connect = '{'.$server.':'.$port.$flags.'}INBOX';

		if (isset($this->params['connectstring']) && trim($this->params['connectstring']))
			$connect = trim($this->params['connectstring']);

		$this->connect_string = $connect;

		$this->conn = @imap_open($connect, $this->params['username'], $this->params['password']);
	}
	
	function disconnect()
	{
		return imap_close($this->conn);
	}

	function GetHeaders($i)
	{
		
		$fullheaders = imap_fetchheader($this->conn, $i);
		$headers = new stdClass(); 
		
		$h_array=explode("\n",$fullheaders);

		foreach ( $h_array as $h ) 
		{
			// Check if row start with a char
			if ( preg_match("/^[A-Z]/i", $h )) {

				$tmp = explode(":",$h);
				$header_name = $tmp[0];
				$header_value = $tmp[1];
				
				if (!property_exists($headers, $header_name))
				{ 
					$headers->$header_name = trim($header_value);
				}
				
			} else {
				// Append row to previous field
				$headers->$header_name = $headers->$header_name . trim($h);
			}
		}		
	
		// get basic headers, and overwrite any previous ones		
		foreach (imap_headerinfo($this->conn, $i) as $prop => $value)
		{
			$headers->$prop = $value;	
		}
		
		$this->headers = null;

		if (empty($headers))
		{
			$this->Log("Unable to decode headers from email");
			return false;
		}
		
		if (array_key_exists("X-Google-Original-From", $headers)) $this->headers->from = imap_rfc822_parse_adrlist($headers->{"X-Google-Original-From"}, '');
		if (array_key_exists("X-Original-From", $headers)) $this->headers->from = imap_rfc822_parse_adrlist($headers->{"X-Original-From"}, '');
		
		$this->headers = new stdClass();

		foreach ($headers as $header => $value)
		{
			if (is_string($value))
			{

				$obj = new stdClass();
				$obj->text = @iconv_mime_decode(trim($value),0,"UTF-8");
				$obj->charset = 'UTF-8';

				$this->headers->$header = $obj;
				
			} else if (is_array($value)) {
				
				foreach ($value as $offset => $values)
				{
					foreach ($values as $key => $text)
					{
						if (is_string($text))
						{
							if (!property_exists($this->headers, $header)) $this->headers->{$header} = array();
							if (!array_key_exists($offset, $this->headers->{$header})) $this->headers->{$header}[$offset] = new stdClass();
							
							$this->headers->{$header}[$offset]->$key = @iconv_mime_decode($text);	
						}
					}	
				}
					
			}
		}
	
		$this->headers_bare = $headers;

		return true;
	}

	function ValidateToAddress()
	{
		if (trim($this->params['toaddress']) != "")
		{
			$toaddys = explode("\n",$this->params['toaddress']);
			$check = array();
			foreach($toaddys as $toaddy)
			{
				$toaddy = strtolower(trim($toaddy));
				if ($toaddy == "") continue;
				$check[$toaddy] = 1;	
			}

			if (count($check) > 0)
			{
				$found = false;
				$addys = "";
				foreach($this->headers->to as $to)
				{
					$sentto = strtolower($to->mailbox."@".$to->host);
					$addys .= "$sentto,";
					if (array_key_exists($sentto, $check))
						$found = true;	
				}

				if (!$found)
				{
					//echo "To address not found - ignoring<br>";
					return false;
				}
			}
		}
		return true;		
	}
	
	function ValidateFromAddress()
	{
		$from = "{$this->headers->from[0]->mailbox}@{$this->headers->from[0]->host}";
		$config = JFactory::getConfig();
		
		if (FSSJ3Helper::IsJ3())
		{		
			$address = 	$config->get( 'config.mailfrom' );
		} else {		
			$address = 	$config->getValue( 'config.mailfrom' );
		}

		if ($from == $address)
		{
			// if we dont have allow_joomla set, skip this email addy!
			if ($this->params['allow_joomla'] != 1)
			{
				$this->Log("From address is Joomla mail from address");
				return false;
			}
		}
			
		if (FSS_Settings::get('support_email_from_address') != "")
			$address = FSS_Settings::get('support_email_from_address');
		
		if ($from == $address)
		{
			if ($this->params['allow_joomla'] != 1)
			{
				$this->Log("From address is Freestyle Support mail from address");
				return false;
			}
		}
		
		if (trim($this->params['ignoreaddress']) != "")
		{
			$toaddys = explode("\n",strtolower($this->params['ignoreaddress']));
			$check = array();
			foreach($toaddys as $toaddy)
			{
				$toaddy = strtolower(trim($toaddy));
				if ($toaddy == "") continue;
				$check[$toaddy] = 1;	
			}

			$from_l = strtolower($from);

			if (count($check) > 0)
			{
				$found = false;
				$matched = null;
				foreach($check as $addy => $temp)
				{
					$addy_o = $addy;

					if (substr($addy, 0, 1) == "*")
					{
						$addy = substr($addy, 1);
						$end = substr($from_l, strlen($from_l) - strlen($addy));

						if ($end == $addy)
							$found = true;	
					} else if (substr($addy, strlen($addy) - 1, 1) == "*")
					{
						$addy = substr($addy, 0, strlen($addy) - 1);
						$begin = substr($from_l, 0, strlen($addy));
						if ($begin == $addy)
							$found = true;	
					} else {
						if ($from_l == $addy)
							$found = true;	
					}

					if ($found && !$matched)
						$matched = $addy_o;
				}

				if ($found)
				{
					$this->Log("From address matched '$matched'");
					return false;
				}
			}
		}
		return true;	
	}

	function ValidSubject()
	{
		if (!isset($this->headers->subject))
			return true;

		$subject = $this->headers->subject->text;
		
		if (!isset($this->params['ignoresubject']))
			return true;
		
		$subjectmatch = explode("\n", $this->params['ignoresubject']);
		
		foreach ($subjectmatch as $match)
		{
			$match = trim($match);
			if (!$match) 
				continue;
			
			if (stripos($subject, $match) !== false)
			{
				return false;
			}
		}
		
		return true;
	}

	function GetMessage($i)
	{
		$this->plainmsg = "";
		$this->htmlmsg = "";
		
		$structure = imap_fetchstructure($this->conn, $i);

		if (empty($structure->parts))
		{
			$this->getPart($this->conn,$i, $structure,0);
		} else {
			foreach($structure->parts as $partno => $part)
			{
				$this->getPart($this->conn,$i, $part, $partno+1);
			}
		}

		if (empty($this->plainmsg))
			$this->plainmsg = "EMPTY";
		
		if (empty($this->htmlmsg))
			$this->htmlmsg = "EMPTY";

		if (strlen($this->plainmsg) < 10 && strlen($this->htmlmsg) > 20) // very short plain, longer html, so use html message instead
		{
			require_once(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'third'.DS.'html2text.php');
			
			$h2t = new html2text($this->htmlmsg);
			$this->plainmsg = $h2t->getText();
		}

		if (isset($this->params['import_html']) && $this->params['import_html'] && FSS_Settings::get('allow_raw_html_messages') && strlen($this->htmlmsg) > 10)
		{
			$this->plainmsg = "{RAWHTML}".FSS_Helper::filterText($this->htmlmsg);	
		}
	}

	function ParseSubject($subject, $from)
	{
		$ticketid = 0;
		$userid = 0;

		$format = FSS_Settings::get('support_reference');
		$format = preg_quote($format);
		
		$db = JFactory::getDBO();

		if (preg_match_all("/\[(.*?)\]/", $subject, $matches))
		{
			foreach ($matches[1] as $ref)
			{
			
				$qry = "SELECT t.id, t.reference, t.title, t.user_id, u.name, u.username, u.email FROM #__fss_ticket_ticket as t LEFT JOIN #__users as u ON t.user_id = u.id WHERE t.reference = '".FSSJ3Helper::getEscaped($db, $ref)."'";
				$db->setQuery($qry);
				$row = $db->loadObject();
				if ($row)
				{
					$ticketid = $row->id;

					$subject = str_ireplace("[$ref]","",$subject);
					$subject = trim($subject);
					
					break;
				}
			}
		}

		$qry = "SELECT id, name, username, email FROM #__users WHERE email = '".FSSJ3Helper::getEscaped($db, $from)."'";
		$db->setQuery($qry);
		$row = $db->loadObject();
		if ($row)
		{
			$userid = $row->id;
		}	

		return array($ticketid, $userid, $subject);	
	}

	function IsMessageRead($message = null)
	{
		if (trim($this->headers->Unseen->text) == "")
		{
			return true;
		}	

		return false;
	}

	function getPart($mbox,$mid,$p,$partno) {

		// $partno = '1', '2', '2.1', '2.1.3', etc if multipart, 0 if not multipart
		global $htmlmsg,$plainmsg,$charset,$attachments;

		// DECODE DATA
		$data = ($partno)?
			imap_fetchbody($mbox,$mid,$partno):  // multipart
			imap_body($mbox,$mid);  // not multipart
			
		// Any part may be encoded, even plain text messages, so check everything.
		if ($p->encoding==4)
			$data = quoted_printable_decode($data);
		elseif ($p->encoding==3)
			$data = base64_decode($data);
		// no need to decode 7-bit, 8-bit, or binary

		// PARAMETERS
		// get all parameters, like charset, filenames of attachments, etc.
		$aparams = array();
		if ($p->parameters)
			foreach ($p->parameters as $x)
				$aparams[ strtolower( $x->attribute ) ] = $x->value;
		if (!empty($p->dparameters))
			foreach ($p->dparameters as $x)
				$aparams[ strtolower( $x->attribute ) ] = $x->value;

		// ATTACHMENT
		// Any part with a filename is an attachment,
		// so an attached text file (type 0) is not mistaken as the message.
		if ( (array_key_exists("filename",$aparams) && $aparams['filename']) || 
			(array_key_exists("name",$aparams) &&  $aparams['name'])
			) {
			// filename may be given as 'Filename' or 'Name' or both
			$filename = isset($aparams['filename'])? $aparams['filename'] : $aparams['name'];
			// filename may be encoded, so see imap_mime_header_decode()
			if (empty($this->attachments))
				$this->attachments = array();

			while (array_key_exists($filename,$this->attachments))
				$filename = "-".$filename;
			$this->attachments[$filename] = $data;  // this is a problem if two files have same name
		}

		// TEXT
		elseif ($p->type==0 && $data) {

			if ($aparams['charset'] != 'UTF-8')
				$data = iconv($aparams['charset'], 'UTF-8', $data);

			// Messages may be split in different parts because of inline attachments,
			// so append parts together with blank row.
			if (strtolower($p->subtype)=='plain')
				$this->plainmsg .= trim($data) ."\n\n";
			else
				$this->htmlmsg .= $data ."<br><br>";
		}

		// EMBEDDED MESSAGE
		// Many bounce notifications embed the original message as type 2,
		// but AOL uses type 1 (multipart), which is not handled here.
		// There are no PHP functions to parse embedded messages,
		// so this just appends the raw source to the main message.
		elseif ($p->type==2 && $data) {

			$this->plainmsg .= trim($data) ."\n\n";
		}

		// SUBPART RECURSION
		if (!empty($p->parts)) {
			foreach ($p->parts as $partno0=>$p2)
				$this->getpart($mbox,$mid,$p2,$partno.'.'.($partno0+1));  // 1.2, 1.2.1, etc.
		}
	}

	function &getStatuss()
	{
		if (empty($this->_statuss))
		{
			$db = JFactory::getDBO();
		
			$query = "SELECT * FROM #__fss_ticket_status ORDER BY id ASC";

			$db->setQuery($query);
			$this->_statuss = $db->loadAssocList('id');
		}
		return $this->_statuss;   		
	}	
	
	function &getStatus($statusid)
	{
		if (empty($this->_statuss))
		{
			$this->getStatuss();
		}

		return $this->_statuss[$statusid];
	}

	function AddTicketAuditNote($ticketid,$note,$userid)
	{
		if ($ticketid < 1)
		{
			echo "ERROR: AddTicketAuditNote called with no ticket id ($note)<br>";
			exit;	
		}
	    $db = JFactory::getDBO();
		$now = FSS_Helper::CurDate();
		$qry = "INSERT INTO #__fss_ticket_messages (ticket_ticket_id, subject, body, user_id, admin, posted) VALUES ('";
		$qry .= FSSJ3Helper::getEscaped($db, $ticketid)."','Audit Message','".FSSJ3Helper::getEscaped($db, $note)."','".FSSJ3Helper::getEscaped($db, $userid)."',3, '{$now}')";
			
  		$db->SetQuery( $qry );
		//echo $qry. "<br>";
		$db->Query();
		//echo "Audit: $ticketid - $note<br>";	
	}
	
	function TestForBulk()
	{
		if (isset($this->headers_bare->{'X-Autoreply'})) return true;	
		if (isset($this->headers_bare->{'X-Autorespond'})) return true;
		
		if (isset($this->headers_bare->{'Auto-Submitted'}) && $this->headers_bare->{'Auto-Submitted'} == "auto-replied") return true;
		if (isset($this->headers_bare->{'Auto-Submitted'}) && $this->headers_bare->{'Auto-Submitted'} == "auto-generated") return true;

		return false;
	}
}


class TrimMatch
{
	var $starts = array();
	var $regex = array();
	var $nearby = array();
	var $rawhtml = 0;
	var $plaintext = 1;

	function Match($line, $lines, $offset, $html_mode)
	{	
		if (!$html_mode) $line = trim($line);

		if ($line == "") return false;

		$match = false;

		// check for any starts with
		if (count($this->starts) > 0)
		{
			foreach ($this->starts as $starts)
			{
				if (FSS_Helper::stringStartsWith($line, $starts))
				{
					$match = true;
				}
			}
		}

		// check for any regular expressions
		if (count($this->regex) > 0)
		{
			foreach ($this->regex as $regex)
			{
				if (preg_match("/".$regex."/i", $line, $matches))
				{
					$match = strpos($line, $matches[0]);
				}
			}
		}

		// no match found, so done
		if ($match === false) return false;

		// have a match, if there are any nearby clauses, check them all
		if (count($this->nearby) > 0)
		{
			foreach ($this->nearby as $nearby)
			{
				// setup default lines before and after
				if (empty($nearby->lines)) $nearby->lines = 3;
				if (empty($nearby->before)) $nearby->before = $nearby->lines;
				if (empty($nearby->after)) $nearby->after = $nearby->lines;

				// if there is a regex in the nearby, check it
				if (isset($nearby->regex))
				{
					// create new match class
					$test = new TrimMatch();
					$test->regex[] = $nearby->regex;

					// work out which lines
					$start = max(0, $offset - $nearby->before);
					$end = min(count($lines)-1, $offset + $nearby->after);

					$found = false;

					// check each line
					for ($i = $start ; $i <= $end ; $i++)
					{
						$subline = trim($lines[$i]);

						if ($test->Match($subline, $lines, $i, $html_mode) !== false)
						{
							$found = true;
							break;
						}
					}

					// if its not found, we failed for this line
					if (!$found)
					{
						return false;
					}
				}
			}
		}

		return $match;
	}
}