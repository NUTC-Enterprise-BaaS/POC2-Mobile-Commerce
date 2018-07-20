<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_actions.php' );
class FSSMailer
{
	private $to = array();
	private $cc = array();
	private $files = array();
	private $skipped_files = array();
	private $subject = '';
	private $body = '';
	private $ishtml = '';
	private $debug_data = array();
	private $source = array();

	function addTo($email, $name = '', $source = '')
	{
		if ($source != "")
			$this->source[$email][] = $source;

		// only add if its missing or has no name
		if (!array_key_exists($email, $this->to) || $this->to[$email] == "")
			$this->to[$email] = $name;
	}

	function addCC($email, $name = '', $source = '')
	{
		if ($source != "")
			$this->source[$email][] = $source;

		// if we already have the address as a to dont add again
		if (array_key_exists($email, $this->to))
		{
			// add name if its missing
			if ($this->to[$email] == "") $this->to[$email] = $name;

			return;
		}

		if (!array_key_exists($email, $this->cc) || $this->cc[$email] == "")
			$this->cc[$email] = $name;
	}

	function addMultiAddress($address, $field = 'addTo', $source = '')
	{
		$address = trim($address);

		if ($address == "")
			return;
	
		if (strpos($address, ","))
		{
			$addresss = explode(",", $address);
			foreach ($addresss as $address)
			{
				if (trim($address))
				{
					$this->$field($address, '', $source);
				}
			}
		} else {
			if (trim($address))
			{
				$this->$field($address, '', $source);
			}
		}
	}

	function AddUserAddress($ids, $object_field = null, $field = 'addTo', $source = '')
	{	
		if (!$ids)
			return;

		if (!is_array($ids))
			$ids = array($ids);		
				
		if (count($ids) < 1)
			return;

		$id_list = array();

		// sanitize the user id list
		if ($object_field)
		{
			foreach($ids as $o)
				$id_list[] = (int)$o->$object_field;
		} else {
			foreach($ids as $o)
				$id_list[] = (int)$o;
		}
				
		$query = " SELECT * FROM #__users WHERE id IN (".implode(", ", $id_list) . ")";
		$db = JFactory::getDBO();
		$db->setQuery($query);
		$users = $db->loadObjectList();
		
		foreach ($users as $user)
			$this->$field($user->email, $user->name, $source);
	}

	function setSubject($subject)
	{
		$this->subject = $subject;
	}

	function setBody($body)
	{
		$this->body = $body;
	}

	function isHTML($ishtml)
	{
		$this->ishtml = $ishtml;
	}

	function addFiles($files)
	{
		if (!is_array($files) || count($files) < 1)
			return;

		$free_attach = 1024 * 1024 * 20;

		foreach ($files as $file)
		{
			$filename = JPATH_SITE.DS.FSS_Helper::getAttachLocation().DS."support".DS.$file->diskfile;
			
			require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'bigfiletools.php');
			$f = BigFileTools::fromPath($filename);
			$file_size  = $f->getSize();

			if ($file_size < $free_attach)
			{
				$this->files[$filename] = $file->filename;
				$free_attach -= $file_size;
			} else {
				$this->skipped_files[$filename] = $file->filename;
			}
		}
	}

	function addDebug($key, $data)
	{
		if ($key == "template" && is_array($data))
		{
			$this->debug_data[$key] = 'custom';
			return;	
		}
		$this->debug_data[$key] = $data;
	}
	
	function clearDebug()
	{
		$this->debug_data = array();
	}

	function getAllTo()
	{
		$result = array();
		foreach ($this->to as $email => $name)
		{
			if (!array_key_exists($email, $result) || $result[$email] == "")
				$result[$email] = trim($name);
		}

		foreach ($this->cc as $email => $name)
		{
			if (!array_key_exists($email, $result) || $result[$email] == "")
				$result[$email] = trim($name);
		}

		return $result;
	}
	
	function Send()
	{
		$emails = $this->getAllTo();

		// limit to max 15 email target addresses
		if (count($emails) > 15)
		{
			$emails = array_slice($emails, 0, 15);
		}

		if (strpos($this->body, "{login_code}") !== false)
		{
			FSS_Settings::set('email_send_multiple', "multi");
		}

		if (FSS_Settings::Get('email_send_multiple') == "to" || FSS_Settings::Get('email_send_multiple') == "bcc")
		{
			$mailer = $this->getMailer();
			$mailer->isHTML($this->ishtml);
			$mailer->setSubject($this->subject);
			$mailer->setBody($this->body);

			foreach ($emails as $email => $name)
			{
				if (trim($email) == "")
					continue;

				if (FSS_Settings::Get('email_send_multiple') == "bcc")
				{
					$mailer->addBCC(array($email));
				} else {
					$mailer->addRecipient(array($email));
				}
			}
			foreach ($this->files as $filename => $display)
				$mailer->addAttachment($filename, $display);

			$this->debug_data['mailer'] = $mailer;

			if (!isset($this->debug_data['ticket'])) $this->debug_data['ticket'] = null;

			SupportActions::DoAction("beforeEMailSend", $this->debug_data['ticket'], $this->debug_data);
			unset($this->debug_data['mailer']);

			$mailer->Priority = null;

			$mailer->Send();
		} else {
			foreach ($emails as $email => $name)
			{
				if (trim($email) == "")
					continue;

				$mailer = $this->getMailer();
				$mailer->isHTML($this->ishtml);

				$body = $this->body;

				// strip and replace login code if its in the email
				if (strpos($body, "{login_code}") !== false)
				{
					// lookup user_id from email
					$db = JFactory::getDBO();
					$sql = "SELECT id FROM #__users WHERE email = '" . $db->escape($email) . "'";
					$db->setQuery($sql);
					$user_id = $db->loadResult();
					if ($user_id > 1)
					{
						$body = str_replace("{login_code}", FSS_Helper::AutoLoginCreate($user_id), $body);
					} else {
						$body = str_replace("{login_code}", "", $body);
					}
				}

				$mailer->setSubject($this->subject);
				$mailer->setBody($body);
				$mailer->addRecipient(array($email));
				foreach ($this->files as $filename => $display)
					$mailer->addAttachment($filename, $display);

				$this->debug_data['mailer'] = $mailer;

				if (empty($this->debug_data['ticket'])) $this->debug_data['ticket'] = null;

				SupportActions::DoAction("beforeEMailSend", $this->debug_data['ticket'], $this->debug_data);

				unset($this->debug_data['mailer']);

				$mailer->Priority = null;

				$mailer->Send();
			}
		}

		$this->doLog();
	}

	function doLog()
	{
		$output = "";
			
		$title = $this->subject;
		
		if (!empty($this->debug_data["template"])) $title .= " <span class='small muted'>" . $this->debug_data["template"] . "</small>";
			
		if (array_key_exists("ticket", $this->debug_data) && $this->debug_data["ticket"])
		{
			$ticket = $this->debug_data['ticket'];
			$output .= "Ticket: ";
			$output .= "<a href='" . FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . $ticket->id).  "'>";
			$output .= "{$ticket->id} / {$ticket->reference} / {$ticket->title}</a><br>";
		}
							
		$output .= "Subject: " . $this->subject . "<br />";

		if (count($this->getAllTo()) > 1)
		{
			$output .= "Send config: " . FSS_Settings::Get('email_send_multiple') . "<br />";
		}

		foreach ($this->getAllTo() as $email => $name)
		{
			$output .= "To: $name ($email)<br />";
		}
		
		if ($this->ishtml) $output .= "HTML EMail<br />";
		if (!empty($this->debug_data["template"])) $output .= "Template: " . $this->debug_data["template"] . "<br/>";

		$output .= "<hr />";
		if ($this->ishtml)
		{
			$output .= $this->body;
		} else {
			$output .= str_replace("\n", "<br />", $this->body);
		}

		if (count($this->files) || count($this->skipped_files))
		{
			$output .= "<hr />";

			if (count($this->files))
			{
				$output .= "Files: <br />";

				foreach ($this->files as $file => $display)
				{
					$output .= "$display<br />";
				}
			}

			if (count($this->skipped_files))
			{
				$output .= "Skipped Files (due to size restrictions): <br />";

				foreach ($this->skipped_files as $file => $display)
				{
					$output .= "$display<br />";
				}
			}
		}

		$output .= "<hr />";
		$output .= "To address reasons: <br />";

		foreach ($this->source as $address => $source)
		{
			$output .= "$address =&gt; " . implode(", ", $source) . "<br />";
		}
		
		if (count($this->source) < 1)
		{
			$output .= "No addresses on email. This email was not sent to anyone. <br />";	
		}
		
		$data = json_encode($this->debug_data);
		

		$now = FSS_Helper::CurDate();
		$db = JFactory::getDBO();
		$qry = "INSERT INTO #__fss_cron_log (cron, log, `when`, `type`, title, data) VALUES ('" . $db->escape("EMail Sent") . "', '" . $db->escape($output) . "', '{$now}', 'emailsend', '" . $db->escape($title) . "', '" . $db->escape($data) . "')";
		$db->setQuery($qry);
		$db->Query();
	}

	static function Get_Sender()
	{
		$config = JFactory::getConfig();
		
		if (FSSJ3Helper::IsJ3())
		{		
			$address = 	$config->get( 'config.mailfrom' );
			$name = $config->get( 'config.fromname' );		

			if (!FSS_Helper::isValidEmail($address) || !FSS_Helper::isValidName($name))
			{
				$address = 	$config->get( 'mailfrom' );
				$name = $config->get( 'fromname' );		
			}
		} else {		
			$address = 	$config->getValue( 'config.mailfrom' );
			$name = $config->getValue( 'config.fromname' );		
		}

		if (FSS_Helper::isValidName(FSS_Settings::get('support_email_from_name')))
			$name = trim(FSS_Settings::get('support_email_from_name'));

		if (FSS_Helper::isValidEmail(FSS_Settings::get('support_email_from_address')))
			$address = trim(FSS_Settings::get('support_email_from_address'));

		if (!FSS_Helper::isValidEmail($address))
		{
			$address = "noreply@none.com";
		}
			
		if (!FSS_Helper::isValidName($name))
		{
			$name = "Unknown";		
		}
		return array( $address, $name );
	}

	function getMailer()
	{
		if (!FSS_Settings::Get('email_send_override'))
		{
			$mailer = JFactory::getMailer();
			$mailer->setSender($this->Get_Sender());
			$mailer->CharSet = 'UTF-8';
			return $mailer;
		}

		$smtpauth = (FSS_Settings::Get('email_send_smtp_auth') == 0) ? null : 1;
		$smtpuser = FSS_Settings::Get('email_send_smtp_username'); // $conf->get('smtpuser');
		$smtppass = FSS_Settings::Get('email_send_smtp_password'); // $conf->get('smtppass');
		$smtphost = FSS_Settings::Get('email_send_smtp_host'); // $conf->get('smtphost');
		$smtpsecure = FSS_Settings::Get('email_send_smtp_security'); // $conf->get('smtpsecure');
		$smtpport = FSS_Settings::Get('email_send_smtp_port'); // $conf->get('smtpport');
		$mailfrom = FSS_Settings::Get('email_send_from_email'); // $conf->get('mailfrom');
		$fromname = FSS_Settings::Get('email_send_from_name'); // $conf->get('fromname');
		$mailer = FSS_Settings::Get('email_send_mailer'); // $conf->get('mailer');

		// Create a JMail object
		$mail = new JMail();

		// Set default sender without Reply-to
		$mail->SetFrom(JMailHelper::cleanLine($mailfrom), JMailHelper::cleanLine($fromname), 0);

		// Default mailer is to use PHP's mail function
		switch ($mailer)
		{
			case 'smtp':
				$mail->useSMTP($smtpauth, $smtphost, $smtpuser, $smtppass, $smtpsecure, $smtpport);
				break;

			case 'sendmail':
				$mail->IsSendmail();
				break;

			default:
				$mail->IsMail();
				break;
		}

		$mail->CharSet = 'UTF-8';
		return $mail;
	}


	function getSubject()
	{
		return $this->subject;
	}

	function getBody()
	{
		return $this->body;
	}

}
			 	 			 		   