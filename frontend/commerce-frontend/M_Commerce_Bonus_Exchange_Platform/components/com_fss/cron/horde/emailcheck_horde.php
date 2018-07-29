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
jimport( 'joomla.filesystem.file' );

class FSSCronEMailCheck_Horde extends FSSCron
{
	var $params;
	var $conn;

	function parse_addys($addy)
	{	
		$emails = array();

		if(preg_match_all('/\s*"?([^><,"]+)"?\s*((?:<[^><,]+>)?)\s*/', $addy, $matches, PREG_SET_ORDER) > 0)
		{
			foreach($matches as $m)
			{
				if(!empty($m[2]))
				{
					//$emails[] = ;
					$email = trim($m[2], '<>');
					$name = $m[1];
				}
				else
				{
					$email = $m[1];
					$name = "";
					//$emails[$m[1]] = '';
				}
									
				$mailbox = $email;
				$host = "";
				if (strpos($email, "@") > 0)
					list($mailbox, $host) = explode("@", $email, 2);
					
				$email_obj = new stdClass();
				$email_obj->email = trim($email);
				$email_obj->personal = trim($name);
				$email_obj->host = trim($host);
				$email_obj->mailbox = trim($mailbox);
				$emails[] = $email_obj;
			}
		}
			
		return $emails;
	}
	
	function Execute($aparams)
	{
		require_once(JPATH_SITE.DS."Horde".DS."Autoloader.php");
		require_once(JPATH_SITE.DS."Horde".DS."Autoloader".DS."ClassPathMapper.php");
		require_once(JPATH_SITE.DS."Horde".DS."Autoloader".DS."ClassPathMapper".DS."Default.php");
		require_once(JPATH_SITE.DS."Horde".DS."Autoloader".DS."Default.php");

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
			return $this->Log("Unable to connect to server");

		// check if we have any messages at all
		$messages = $this->conn->search("INBOX");
		$ids = $messages['match'];
		
		$ids->reverse();

		$msgcount = $ids->count();
		
		if ($msgcount == 0)
		{
			$this->Log("No messages");
			$this->disconnect();
			return;	
		} else {
			$this->Log("$msgcount messaeges");	
		}

		// only get the first 20 messages to make sure web page response is quicker
		$maxmsgcount = 50;
		if ($msgcount < $maxmsgcount)
			$maxmsgcount = $msgcount;
	
		$query = new Horde_Imap_Client_Fetch_Query();
		$query->flags();
		$query->structure();
		$query->headerText(array('peek' => 1));
		
		$uid = new Horde_Imap_Client_Ids();
		
		for ($i = 0; $i < $maxmsgcount; $i++)
		{
			$uid->add($ids->ids[$i]);
		}	
		
		$messages = $this->conn->fetch('INBOX', $query, array(
			'ids' => $uid
		));

		// for the most recent xx messages
		for ($i = 0; $i < $maxmsgcount; $i++)
		{
			$mapped = $ids->ids[$i];
			
			$message = $messages->get($mapped);
			
			$this->Log("---------------------");
			$this->Log("Processing message $i / $mapped");

			// get headres of message
			if (!$this->GetHeaders($message)) 
			{
				$this->Log("Error getting headers");
				continue;
			}

			$subject = $this->headers->getValue("subject");
			$this->Log("Subject : " . $subject);

			$this->from = $this->parse_addys($this->headers->getValue("from"));
			$this->to = $this->parse_addys($this->headers->getValue("to"));

			$this->Log("From : {$this->from[0]->mailbox}@{$this->from[0]->host} ({$this->from[0]->personal})");
			
			if ($this->from[0]->mailbox == "no-reply")
			{
				// no-reply from form builder, attempt to find address in name
				$text = $this->from[0]->personal;
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
					
					$this->from[0]->mailbox = $name;
					$this->from[0]->host = $host;
					$this->from[0]->personal = trim(str_replace("$name@$host","",$this->from[0]->personal));
				}
			}
			
			// skip if already read
			if ($aparams['type'] == "imap" && $this->IsMessageRead($message))
			{
				$this->Log("Skipping read message");
				continue;
			}

			// validate to address is required
			if (!$this->ValidateToAddress())
			{
				$this->Log("Skipping invalid to address");
				continue;
			}
			
			if (!$this->ValidateFromAddress())
			{
				$this->Log("Skipping due to from address");
				continue;	
			}

			//check subject and to email to see if we have found a user and or ticket
			
			$subject = $this->headers->getValue("subject");
			
			list($ticketid, $userid, $subject) = $this->ParseSubject($subject,$this->from[0]->mailbox . '@' . $this->from[0]->host);
			
			$this->subject = $subject;

			//echo "Ticket : $ticketid, $userid, $subject<br>";
	
			// ok, need to get the message as we have decided its ok to use this ticket
			
			if ($ticketid < 1 && $userid < 1 && $this->params['newticketsfrom'] == "registered")
			{
				$this->Log("Skipping as registered only and not a registered email");
				continue;
			}
			
			if ($this->params['allowrepliesonly'] && $ticketid < 1)
			{
				$this->Log("Skipping as not a reply");
				continue;
			}

			
			// validate that the ticket is being replied to by user or handler
			if ($ticketid > 0)
			{
				$ticket = $this->getTicket($ticketid);
				
				if ($this->params['allowunknown'] < 1)
				{
					if ($ticket['user_id'] == 0 && $ticket['email'] != "{$this->from[0]->mailbox}@{$this->from[0]->host}")
					{
						$this->Log("Unknown email replying to the message, ignore the email ({$this->from[0]->mailbox}@{$this->from[0]->host})");
						continue;
					}	
					if ($userid != $ticket['handler_id'] && $userid != $ticket['user_id'])
					{
						$this->Log("Unknown user replying to the message, ignore the email ({$this->from[0]->mailbox}@{$this->from[0]->host})");	
						continue;
					}
				}
			}
			
			$this->GetMessage($message);

			$this->TrimMessage();

			$messageid = 0;

			$filesok = true;
			// add to existing ticket
			if ($ticketid > 0)
			{
				// unreg ticket, just check email
				if ($ticket['user_id'] == 0)
				{
					//echo "Adding message to ticket - {$ticket['email']}<br>";
					$this->DoTicketReply($ticketid,$userid,0,$messageid);
				} else {
					if ($userid == $ticket['handler_id'])
					{
						//echo "Adding admin message to ticket - {$ticket['user_id']} -> {$userid}<br>";
						$this->DoTicketReply($ticketid,$userid,1,$messageid);	
					} else if ($userid == $ticket['user_id']) {
						//echo "Adding message to ticket - {$ticket['user_id']} -> {$userid}<br>";
						$this->DoTicketReply($ticketid,$userid,0,$messageid);

						if (!FSS_Settings::get('support_user_attach'))
							$filesok = false;
					} else if ($this->params['allowunknown'])
					{
						// unreg ticket, add users reply
						$this->DoTicketReply($ticketid,0,0,$messageid);	
					}
				}
				
			} else if ($userid > 0) // open new ticket for registered user	
			{
				//echo "Opening new ticket<br>";
				$ticketid = $this->OpenNewTicket($userid,$messageid);
			} else { // open ticket for unregistered user
				//echo "Opening new ticket for unreg user<br>";
				$ticketid = $this->OpenNewTicketUnreg($messageid);
			}
			
			$ticket = $this->getTicket($ticketid);
			if ($userid > 0)
			{
				$user = JFactory::getUser($userid);
				$this->Log("Ticket ID : $ticketid - {$ticket['reference']}, UserID : $userid - {$user->name} ({$user->username})");
			} else {
				$this->Log("Ticket ID : $ticketid - {$ticket['reference']}, Unregistered User");
			}
			
			/* if ($filesok)
				$this->AttachFiles($ticketid, $userid, $messageid);*/
		}
			
		//imap_expunge($this->conn);
		$this->disconnect();
		//echo "</div>";
	}

	function Test($aparams)
	{
		$this->params = $aparams;

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
			$this->count = imap_num_msg($this->conn);
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
			
		$admin_id = FSS_Ticket_Helper::AssignHandler($prodid, $deptid, $catid);
		
		$subject = $this->subject;
		$body = $this->plainmsg;
		
		$now = FSS_Helper::CurDate();
		
		$def_open = FSS_Ticket_Helper::GetStatusID('def_open');
		

		$source = "email_accepted";
		// only confirm if set to all emails
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

		$qry = "INSERT INTO #__fss_ticket_messages (ticket_ticket_id, subject, body, user_id, posted) VALUES ('";
		$qry .= FSSJ3Helper::getEscaped($db, $ticketid) . "','".FSSJ3Helper::getEscaped($db, $subject)."','".FSSJ3Helper::getEscaped($db, $body)."','".FSSJ3Helper::getEscaped($db, $userid)."','{$now}')";
		//echo $qry."<br>";	
		$messageid = $db->insertid();
		
		$db->setQuery($qry);$db->Query();
		
		$ticket = $this->getTicket($ticketid);
		//FSS_EMail::User_Create($ticket, $subject, $body, array());
		//FSS_EMail::Admin_Create($ticket, $subject, $body, array());
		
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
		
		$admin_id = FSS_Ticket_Helper::AssignHandler($prodid, $deptid, $catid);
		
		$subject = $this->subject;
		$body = $this->plainmsg;
		
		$email = "{$this->from[0]->mailbox}@{$this->from[0]->host}";
		$name = $this->from[0]->personal;
		if (trim($name) == "")
			$name = $email;
		
		$this->Log("Unreg Name : $name");

		$password = FSS_Helper::createRandomPassword();	
		$now = FSS_Helper::CurDate();
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

		$qry = "INSERT INTO #__fss_ticket_messages (ticket_ticket_id, subject, body, user_id, posted) VALUES ('";
		$qry .= FSSJ3Helper::getEscaped($db, $ticketid) . "','".FSSJ3Helper::getEscaped($db, $subject)."','".FSSJ3Helper::getEscaped($db, $body)."','".FSSJ3Helper::getEscaped($db, $userid)."','{$now}')";
		//echo $qry."<br>";	
		$messageid = $db->insertid();
		
		$db->setQuery($qry);$db->Query();
		
		$ticket = $this->getTicket($ticketid);
		//FSS_EMail::User_Create_Unreg($ticket, $subject, $body);
		//FSS_EMail::Admin_Create($ticket, $subject, $body, array());
		
		return $ticketid; 
	}

	function AttachFiles($ticketid, $userid, $messageid)
	{
		$db = JFactory::getDBO();

		if (empty($this->attachments))
			return;
			
		if (!is_array($this->attachments))
			return;
		
		if (count($this->attachments) == 0)
			return;
		$now = FSS_Helper::CurDate();
		
		foreach ($this->attachments as $filename => &$data)
		{
			$this->Log("Attachment : $filename - " . strlen($data));
			
			$random = dechex(rand(0,65535));
			$destname = JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'files'.DS.'support'.DS.$random.'-'.$filename;    
			while (JFile::exists($destname))
			{
				$random = dechex(rand(0,65535)); 
				$destname = JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'files'.DS.'support'.DS.$random.'-'.$filename;                
			}
	            
			if (JFile::write($destname, $data))
			{
				$this->Log("Wrote file to $destname");
				$qry = "INSERT INTO #__fss_ticket_attach (ticket_ticket_id, filename, diskfile, size, user_id, added, message_id) VALUES ('";
				$qry .= FSSJ3Helper::getEscaped($db, $ticketid) . "',";
				$qry .= "'" . FSSJ3Helper::getEscaped($db, $filename) . "',";
				$qry .= "'" . FSSJ3Helper::getEscaped($db, $random.'-'.$filename) . "',";
				$qry .= "'" . strlen($data) . "',";
	            $qry .= "'" . FSSJ3Helper::getEscaped($db, $userid) . "',";
				$qry .= "'{$now}', $messageid )";
	            	
	            $db->setQuery($qry);$db->Query();     
			} else {
	            // ERROR : File cannot be uploaded! try permissions	
			}
		}
	}

	function DoTicketReply($ticketid,$userid,$isadmin,&$messageid)
	{
		$db = JFactory::getDBO();

		$subject = $this->subject;
		$body = $this->plainmsg;

		$now = FSS_Helper::CurDate();
		
		if ($body)
		{
			$qry = "INSERT INTO #__fss_ticket_messages (ticket_ticket_id, subject, body, user_id, admin, posted) VALUES ('";
			$qry .= FSSJ3Helper::getEscaped($db, $ticketid) . "','".FSSJ3Helper::getEscaped($db, $subject)."','".FSSJ3Helper::getEscaped($db, $body)."','".FSSJ3Helper::getEscaped($db, $userid)."', '".FSSJ3Helper::getEscaped($db, $isadmin)."', '{$now}')";
			$db->setQuery($qry);$db->Query();
			$messageid = $db->insertid();
			
			$qry = "SELECT ticket_status_id FROM #__fss_ticket_ticket WHERE id = '".FSSJ3Helper::getEscaped($db, $ticketid)."'";
			$db->setQuery($qry);
			$status = $db->loadAssoc();
			
			if ($isadmin)
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
		
		$ticket = $this->getTicket($ticketid);

		if ($isadmin)
		{
			FSS_EMail::Admin_Reply($ticket, $subject, $body, array());
		} else {
			FSS_EMail::User_Reply($ticket, $subject, $body, array());			
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
		$query .= " LEFT JOIN #__users as au ON t.admin_id = au.id ";
		$query .= " WHERE t.id = '".FSSJ3Helper::getEscaped($db, $ticketid)."' ";

		$db->setQuery($query);
		$rows = $db->loadAssoc();
		return $rows;   		
	}

	function TrimMessage()
	{
		$lines = explode("\n",$this->plainmsg);
		$this->plainmsg = array();
		
		foreach($lines as $line)
		{
			if (substr($line,0,1) == ">")
				continue;

			if (substr($line,0,3) == "On ")
			{
				if (strpos($line,"wrote:") > 0)
					continue;	
			}
			$this->plainmsg[] = $line;
		}	

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
		$cd = array();
		
		$cd['username'] = $this->params['username'];
		$cd['password'] = $this->params['password'];
		$cd['hostspec'] = $this->params['server'];
		$cd['port'] = $this->params['port'];
		if ($this->params['usessl'])
			$cd['secure'] = "ssl";
		if ($this->params['usetls'])
			$cd['secure'] = "tls";
		
		$this->conn = new Horde_Imap_Client_Socket($cd);
		$this->connect_string = "Horde";
	}
	
	function disconnect()
	{
		//return imap_close($this->conn);
	}

	function GetHeaders($message)
	{
		$this->headers = Horde_Mime_Headers::parseHeaders($message->getHeaderText());
		
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
				foreach($this->to as $to)
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
		$from = "{$this->from[0]->mailbox}@{$this->from[0]->host}";
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
			$this->Log("From address is Freestyle Support mail from address");
			return false;
		}
		
		if (trim($this->params['ignoreaddress']) != "")
		{
			$toaddys = explode("\n",$this->params['ignoreaddress']);
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
				foreach($check as $addy => $temp)
				{
					if ($from == $addy)
						$found = true;	
				}

				if ($found)
				{
					//echo "From address found - ignoring<br>";
					return false;
				}
			}
		}
		return true;	
	}

	function GetMessage($message)
	{
		$uid = new Horde_Imap_Client_Ids($message->getUid());
		
		$this->plainmsg = "";
		$this->htmlmsg = "";
		
		$part = $message->getStructure();
	
		$plain_id = $part->findBody("plain");
		$plain_body = $part->getPart($plain_id);
		$html_id = $part->findBody("html");
		$html_body = $part->getPart($html_id);

		$query2 = new Horde_Imap_Client_Fetch_Query();
		$query2->bodyPart($plain_id, array('decode' => true));
		$list2 = $this->conn->fetch('INBOX', $query2, array('ids' => $uid));
 
		$message2 = $list2->first();
		$this->plainmsg = $message2->getBodyPart($plain_id);
		if (!$message2->getBodyPartDecode($plain_id) && $plain_body) {
			// Quick way to transfer decode contents
			$plain_body->setContents($this->plainmsg);
			$this->plainmsg = $plain_body->getContents();
		}

		if ($html_id != "")
		{	
			$query2 = new Horde_Imap_Client_Fetch_Query();
			$query2->bodyPart($html_id, array('decode' => true));
			$list2 = $this->conn->fetch('INBOX', $query2, array('ids' => $uid));
 
			$message2 = $list2->first();
			$this->htmlmsg = $message2->getBodyPart($html_id);
			if (!$message2->getBodyPartDecode($html_id) && $html_body) {
				// Quick way to transfer decode contents
				$html_body->setContents($this->htmlmsg);
				$this->htmlmsg = $html_body->getContents();
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
			$this->plainmsg = $h2t->get_text();
		}
	}

	function ParseSubject($subject, $from)
	{
		//echo "Parsing subject : $subject<Br>";
		//echo "From : $from<br>";

		$ticketid = 0;
		$userid = 0;

		$format = FSS_Settings::get('support_reference');
		$format = preg_quote($format);
		
		$db = JFactory::getDBO();

		$ref = substr($subject, strpos($subject,"[")+1);
		$ref = substr($ref, 0, strpos($ref,"]"));
			
		//echo "Ref : $ref<br>";
			
		$qry = "SELECT t.id, t.reference, t.title, t.user_id, u.name, u.username, u.email FROM #__fss_ticket_ticket as t LEFT JOIN #__users as u ON t.user_id = u.id WHERE t.reference = '".FSSJ3Helper::getEscaped($db, $ref)."'";
		$db->setQuery($qry);
		$row = $db->loadObject();
		if ($row)
		{
			$ticketid = $row->id;

			$subject = str_ireplace("[$ref]","",$subject);
			$subject = trim($subject);
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

	function IsMessageRead($message)
	{
		$flags = $message->getFlags();
		
		foreach ($flags as $flag)
		{
			if ($flag == "\seen")
				return true;	
		}
		
		return false;
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
}