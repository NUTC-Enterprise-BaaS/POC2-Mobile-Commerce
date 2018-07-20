<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'tickethelper.php');

class FSSParser
{
	var $template = "";
	var $vars = array();
	
	var $language = "";
	var $ticket;
	var $message_body;
	var $message_subject;
	var $target = "user";
	var $extra_vars = array();
	var $sender;
	var $to;

	static $templates = array();
	static $template_type = array();
	static $emails = array();
	
	function __construct()
	{
		$this->init();	
	}
	
	function loadTemplate($template, $tpltype)
	{
		if (empty(self::$templates[$template]))
		{
			$db = JFactory::getDBO();
			$query = "SELECT * FROM #__fss_templates WHERE template = '" . FSSJ3Helper::getEscaped($db, $template) . "'";
			$db->SetQuery($query);
			self::$templates[$template] = $db->LoadObjectList();
		}
		
		if (empty(self::$template_type[$template . "-" . $tpltype]))
		{
			foreach (self::$templates[$template] as $t)
			{
				if ($t->tpltype == $tpltype)
				{
					self::$template_type[$template . "-" . $tpltype] = $this->ProcessLanguage($t->value);
				}
			}
		}

		$this->template = self::$template_type[$template . "-" . $tpltype];
	}	
	
	/* DO NOT USE ANY MORE */
	function Load($template, $tpltype)
	{
		return $this->loadTemplate($template, $tpltype);
	}	
	
	function loadText($template)
	{
		$this->template = $template;
	}
	
	function loadEmail($template_id)
	{
		if (is_array($template_id))
		{
			$this->data = new stdClass();
			
			foreach ($template_id as $key => $value)
			{
				$this->data->$key = $value;	
			}
		} else {
			
			if (!isset(self::$emails[$template_id]))
			{
				$db = JFactory::getDBO();
				$qry = 	"SELECT body, subject, ishtml, translation, tmpl FROM #__fss_emails WHERE tmpl = '".$db->escape($template_id)."'";
				$db->setQuery($qry);
				
				$tmpl = $db->loadObject();
				
				if ($tmpl->translation) $tmpl->translation = json_decode($tmpl->translation, true);
				
				self::$emails[$template_id] = $tmpl;
			}
			
			$this->data = self::$emails[$template_id];
		}
	}
	
	function parseTicket($ticket = null)
	{
		if (!$ticket) $ticket = $this->ticket;
		if ($ticket) $ticket->forParser($this->vars, ($this->target != "handler"), false, $this->data->subject . "\n" . $this->data->body . "\n" . $this->template);
	}
	
	function parseSender($sender = null)
	{
		if (!$sender) $sender = $this->sender;
		$this->parseUser("sender_", $sender);
	}
	
	function parseTo($to = null)
	{
		if (!$to) $to = $this->to;
		$this->parseUser("to_", $to);
	}
	
	function parseMessage()
	{
		if ($this->message_subject)
		{
			$subject = trim(str_ireplace("re:","",$this->message_subject));
			
			$this->setVar("subject", $subject);
			$this->setVar("subject_text", $subject);
		}
		
		if ($this->message_body)
		{
			$body = FSS_Helper::ParseBBCode($this->message_body,null,false,false, ($this->target == "user"));	
			$body = str_replace("&lt;", "XXXLTXXX", $body);
			$body = str_replace("&gt;", "XXXGTXXX", $body);

			$this->setVar("body", $body);
		}
	}
	
	function parseUser($prefix, $user)
	{
		//print_p($user);
		$this->setVar($prefix."email", $user->email);
		$this->setVar($prefix."name", $user->name);
		$this->setVar($prefix."userid", $user->user_id);
		if ($user->user_id > 0)
		{
			$this->setVar($prefix."username", JFactory::getUser($user->user_id)->username);
		} else {
			$this->setVar($prefix."username", $user->email);
		}
		if (isset($user->reason)) $this->setVar($prefix."reason", reset($user->reason));	
	}
	
	function setLanguageByUser($user_id, $fallback = "", $ticket = null)
	{
 		if ($ticket && $ticket->lang && $ticket->user_id == $user_id) 
		{
			$this->setLanguage($ticket->lang);
		} else if ($user_id > 0)
		{
			$user = JFactory::getUser($user_id);
			$lang = $user->getParam('language');
			
			$this->setLanguage($lang);
		} else  {
			$this->setLanguage($fallback);
		}
	}
	
	function setLanguage($language)
	{
		$this->language = str_replace("-", "", $language);
		$this->ticket->translate($language);
	}
	
	function setTicket($ticket)
	{
		$this->ticket = $ticket;	
	}
	
	function setMessage($subject, $body)
	{
		$this->message_body = $body;
		$this->message_subject = $subject;	
	}
	
	function setTarget($target)
	{
		$this->target = $target;	
	}
	
	function setExtraVars($vars)
	{
		foreach ($vars as $key => $value)
			$this->extra_vars[$key] = $value;
	}
	
	function addExtraVars()
	{
		if (isset($this->extra_vars))
		{
			foreach ($this->extra_vars as $key => $value)
			{
				if (!array_key_exists($key, $this->vars)) $this->setVar($key, $value);
			}	
		}
	}
	
	function setSender($sender)
	{
		$this->sender = $sender;	
	}
	
	function setTo($to)
	{
		$this->to = $to;	
	}
	
	function getIsHtml()
	{
		return $this->data->ishtml;
	}
	
	function getSubject()
	{
		$this->addExtraVars();
		
		// get correct language version of subject - TODO
		$subject = $this->data->subject;
		
		if (isset($this->data->translation['subject'][$this->language]) && $this->data->translation['subject'][$this->language])
		{
			$subject = $this->data->translation['subject'][$this->language];
		}
		
		$subject = $this->ParseInt($subject);
		
		return $subject;
	}	
	
	function getBody()
	{
		$this->addExtraVars();

		// get correct language version of subject - TODO
		$body = $this->data->body;
		
		if (isset($this->data->translation['body'][$this->language]) && $this->data->translation['body'][$this->language])
		{
			$body = $this->data->translation['body'][$this->language];
		}
		
		$body = $this->ParseInt($body);

		if ($this->data->ishtml)
		{
			$body = FSS_Helper::MaxLineLength($body);
		} else {	
			$body = str_replace("<br />","\n",$body);
			$body = html_entity_decode($body);
			$body = preg_replace_callback("/(&#[0-9]+;)/", array("FSS_Helper", "email_decode_utf8"), $body); 
			$body = strip_tags($body);
		}
		
		$body = str_replace("XXXLTXXX", "<", $body);
		$body = str_replace("XXXGTXXX", ">", $body);
		$body = str_replace("82f4975759af26d3e9f7bcb5d7f92f35", "", $body);
		
		return $body;
	}

	function getTemplate()
	{
		$this->addExtraVars();

		$t = $this->template;
		$o = $this->ParseInt($t);
		return $o;
	}
	
	/* DO NOT USE ANY MORE */
	function Parse()
	{
		return $this->getTemplate();
	}
	
	function processLanguage($text)
	{
		if (preg_match_all("/\%([A-Za-z_]+)\%/", $text, $matches))
		{
			foreach($matches[1] as $match)
			{
				$find = "%" . $match . "%";
				$replace = JText::_($match);

				$text = str_replace($find, $replace, $text);
			}
		}
		
		return $text;
	}
	
	function clear()
	{
		$this->vars = array();	
		
		$this->init();
	}

	function setVar($var, $value)
	{
		$this->vars[$var] = $value;
	}

	function getVar($var)
	{
		return $this->vars[$var];
	}

	function addVars(&$vars)
	{
		foreach($vars as $var => $value)
			$this->vars[$var] = $value;
	}
	
	function convertOldTags($t)
	{	
		$t = preg_replace("/\{([a-z\-\_\.]+)_start\}/", "{if,$1}", $t);
		$t = preg_replace("/\{([a-z\-\_\.]+)_end\}/", "{endif}", $t);
		
		return $t;
	}
	
	function init()
	{
		// current date and time
		$this->vars["date"] = date("Y-m-d");
		$this->vars["time"] = date("H:i:s");
		$this->vars["current_date"] = FSS_Helper::Date(time(), FSS_DATE_LONG);
		$this->vars["current_time"] = FSS_Helper::Date(time(), FSS_TIME_SHORT);
		$this->vars["current_datetime"] = FSS_Helper::Date(time(), FSS_DATETIME_LONG);
		
	}

	function ParseInt($t)
	{
		/**
		 * Todo:
		 * 
		 * Need to add the following stuff:
		 * 
		 * {else}
		 * 
		 * {if var = "value"}
		 * {if var != "value"}
		 * {if var}
		 * {if !var}
		 * {for var as new} - need to have nested variables and array for this
		 * {var = value}
		 * {var = something + 1}
		 * 
		 * {data.field} - sub data output
		 * 
		 */
		
		if (strpos($t, "_start}") !== false) $t = $this->convertOldTags($t);
		
		if (is_array($t))
		{
			print_p($t);
			print_p(dumpStack());
			exit;
		}
		$max = 0;
		$o = "";
		$toffset = 0;
		
		while (strpos($t,"{",$toffset) !== false && $max < 1000)
		{
			$start = strpos($t,"{",$toffset)+1;	
			$end = strpos($t,"}",$start);
			$tag = substr($t,$start,$end-$start);
			$max++;

			$bits = explode(",",$tag);
			//echo "Tag : " . $bits[0] . "<br>";

			$o .= substr($t,$toffset,$start-$toffset-1);
			$toffset = $end + 1;

			if ($bits[0] == "if" || $bits[0] == "endif")
			{
				//echo "Processing IF <br>";

				// find the endif. Allows nested if statements
				$open = 1;
				$ifstart = $toffset;
				while (strpos($t,"{",$toffset) !== false && $open > 0)
				{
					$start = strpos($t,"{",$toffset)+1;	
					$end = strpos($t,"}",$start);
					$tag = substr($t,$start,$end-$start);

					$bits2 = explode(",",$tag);
					if ($bits2[0] == "if")
					{
						$open ++;	
					} else if ($bits2[0] == "endif")
					{
						$open--;	
					}
					$toffset = $end + 1;
					//echo "If tag $tag, depth = $open<br>";
				}
				$ifend = $toffset;
				$ifcode = substr($t,$ifstart,$ifend-$ifstart-7);

				//echo "IF Code : <pre>" . htmlentities($ifcode) . "</pre><br>";

				// match the if
				$matched = false;
				//echo "If: " . print_r($bits, true) . " - ";
				//echo $this->vars[$bits[1]] . " - ";
				if (count($bits) == 2)
				{
					$var = $bits[1];

					if (array_key_exists($var,$this->vars))
					{
						$value = $this->vars[$var];
						if ($value)
							$matched = true;
					}
				} else if (count($bits) == 3)
				{
					$var = $bits[1];
					$value = trim($bits[2],"\"'");	
					
					if (array_key_exists($var,$this->vars))
					{
						$varvalue = $this->vars[$var];
						if ($varvalue == $value)
							$matched = true;
					}
				} else if (count($bits) == 4)
				{
					$var = $bits[1];
					$value = trim($bits[2],"\"'");	
					$op = $bits[3];
					if (array_key_exists($var,$this->vars))
					{
						$varvalue = $this->vars[$var];
						if ($op == "not")
						{
							if ($varvalue != $value)
								$matched = true;
						} else {
							if ($varvalue == $value)
								$matched = true;
						}
					}
				}

				/*if ($matched)
					echo "TRUE";
				else 
					echo "FALSE";

				echo "<br>";*/
				// if IF statement is matched, parse the insides of it
				if ($matched)
					$o .= $this->ParseInt($ifcode);
			} else if ($bits[0] == "set")
			{
				if (count($bits) == 3)
				{
					$var = $bits[1];
					$value = $bits[2];
					if (is_numeric($value))
					{
						$this->vars[$var] = $value;	
					} else if ( 
							(substr($value,0,1) == "\"" || substr($value,0,1) == "'") &&
							(substr($value,strlen($value)-1,1) == "\"" || substr($value,strlen($value)-1,1) == "'"))
					{
						$this->vars[$var] = trim($value,"\"'");	
					} else if (array_key_exists($value,$this->vars))
					{
						$this->vars[$var] = $this->vars[$value];
					} else {
						$this->vars[$var] = $value;	
					}
					//echo "Setting $var to {$this->vars[$var]}<br>";
				}
			} else {
				if (array_key_exists($bits[0],$this->vars))
				{
					if (isset($bits[1]) && $bits[1] > 0)
					{
						$ending = "";
						if (isset($bits[2])) $ending = $bits[2];
						$is_trimmed = false;
						$o .= FSS_Helper::truncate($this->vars[$bits[0]], $bits[1], $is_trimmed, $ending);
					} else {
						$o .= $this->vars[$bits[0]];
					}
				}	
			}
		}

		$o .= substr($t,$toffset);

		if ($max == 1000)
		{	
			echo "error finding {endif}<br />";
			echo htmlspecialchars($t);
			exit;
		}
		
		return $o;
	}

	function processSortTags()
	{
		$text = $this->template;

		while (strpos($text, "{order ") !== FALSE)
		{
			$start = strpos($text, "{order ");
			$end = strpos($text, "}", $start + 1) + 1;
			$tag = substr($text, $start+7, $end-($start+8));
			list($field, $display) = explode(" ", $tag, 2);
			$replace = "<a href='#' onclick='fssAdminOrder(\"$field\");return false;'>" . $display . "</a>";

			$text = substr($text,0, $start) . $replace . substr($text, $end);
		}

		$this->template = $text;	
	}
}