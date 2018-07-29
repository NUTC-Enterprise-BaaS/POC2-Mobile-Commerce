<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'parser.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_users.php');

class SupportCanned
{
	static $replies;
	static function GetCannedReplies()
	{
		if (empty(self::$replies))
		{
			$db = JFactory::getDBO();
			$qry = "SELECT * FROM #__fss_ticket_fragments WHERE type = 0";
			$db->setQuery($qry);
		
			self::$replies = $db->loadObjectList();
		}
		
		return self::$replies;
	}	
	
	static $settings_output = false;
	
	static function CannedDropdown($editorid, $hascontainer = true, $ticket = null)
	{
		if ($hascontainer)
			$html[] = '<div class="canned_list" editid="' . $editorid . '">';
		
		if (!self::$settings_output)
		{
			if (FSS_Settings::get('support_insertpopup') || FSS_Settings::get('bootstrap_v3'))
			{
				JFactory::getDocument()->addScriptDeclaration("\nvar support_insertpopup = true;\n");
			}
		}	
		
		$html[] = '<div class="btn-group pull-right">';
		$html[] = '<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#" onclick="cannedPopup(this);return false;">';
		//$html[] = '<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#"">';
		$html[] = JText::_('Insert');
		$html[] = '&nbsp;<span class="caret"></span>';
		$html[] = '</a>';
		$html[] = '<ul class="dropdown-menu">';
		
		$output = false;
		$group_replies = array();
		
		foreach (self::GetCannedReplies() as $reply)
		{
			if ($reply->grouping)
			{
				$group_replies[$reply->grouping][] = $reply;	
			} else {
				$html[] = '<li><a href="#" onclick="insertCanned(' . $reply->id . ', \''.$editorid.'\'); return false;">'. $reply->description . '</a></li>';
			}
			
			$output = true;
		}
			
		foreach ($group_replies as $key => $replies)
		{
			$html[] = '<li class="dropdown-submenu pull-left">';
			$html[] = '<a>'.$key.'</a>';
			$html[] = '<ul class="dropdown-menu">';
			
			foreach ($replies as $reply)
			{
				$html[] = '<li><a href="#" onclick="insertCanned(' . $reply->id . ', \''.$editorid.'\'); return false;">'. $reply->description . '</a></li>';
			}
		
			$html[] = '</ul>';
			$html[] = '</li>';
			$output = true;
			
		}
		
		if ($output)
			$html[] = '<li class="divider"></li>';
		
		$gui_plugin = FSS_GUIPlugins::output("adminCannedDropdown", array('ticket'=> $ticket, 'editor' => $editorid));
		if ($gui_plugin)
		{
			$html[] = $gui_plugin;
			$html[] = '<li class="divider"></li>';
		}

		$html[] = '<li class="dropdown-submenu pull-left"><a>'.JText::_('SITE_LINK') . '</a>';
		$html[] = '<ul class="dropdown-menu">';
		
		$plugins = self::getInsertPlugins();
		
		foreach($plugins as $plugin)
		{
			$html[] = '<li><a class="show_modal_iframe" href="' . FSSRoute::_("index.php?option=com_fss&view=admin_insert&tmpl=component&type=".$plugin->name."&editor=".$editorid) . '" data_modal_width="800">'.JText::_($plugin->title) . '</a></li>';
		}
		$html[] = '</ul></li>';
		$html[] = '<li class="divider"></li>';
		$html[] = '<li><a class="show_modal_iframe" href="' . FSSRoute::_("index.php?option=com_fss&view=admin_support&tmpl=component&layout=canned" ) . '"><i class="icon-edit"></i> '.JText::_('EDIT') . '</a></li>';
		
		$html[] = '</ul>';
		$html[] = '</div>';

		if ($hascontainer)
			$html[] = '</div>';
		
		return implode($html);
	}
	
	static function getInsertPlugins()
	{
		$path = JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'plugins'.DS.'picktable'.DS;
		$files = JFolder::files($path, ".xml$");	
		
		$data = array();
		
		foreach ($files as $file)
		{
			$name = str_ireplace(".xml", "", $file);
			$title = "";
			if ($name == "kb")
			{
				$title = "KB_ARTICLE";
			} else if ($name == "faq")
			{
				$title = "FAQ";
			} else {
				$filename = $path . $file;
				$xml = simplexml_load_file($filename);
				$title = (string)$xml->table->display;
			}
			
			$o = new stdClass();
			$o->name = $name;
			$o->title = $title;
			
			$data[] = $o;
		}

		return $data;
	}

	static function CannedList($ticket = null)
	{
		$output = array();
		foreach (self::GetCannedReplies() as $reply)
		{
			if ($ticket)
			{
				$output[] = '<div id="canned_reply_' . $reply->id .'">' . str_replace("\n", "&para;", self::ParseSig($reply->content, $ticket, false)) . '</div>';
			} else {
				$output[] = '<div id="canned_reply_' . $reply->id .'">' . str_replace("\n", "&para;", $reply->content) . '</div>';				
			}
		}
		
		return implode($output);
	}
	
	static $all_sigs;
	static function GetAllSigs($ticket)
	{
		if (empty(self::$all_sigs))
		{
			$userid = JFactory::getUser()->id;
			
			$db = JFactory::getDBO();
			$query = 'SELECT * FROM #__fss_ticket_fragments WHERE type = 1';
			$db->setQuery($query);
			$rows = $db->loadObjectList();
			self::$all_sigs = array();

			foreach($rows as $row)
			{
				$row->params = json_decode($row->params, true);
				if (is_string($row->params))
					$row->params = array();
				
				$row->default = 0;
				$row->personal = 0;
				
				if (isset($row->params['userid']))
				{
					if ($row->params['userid'] > 0 && $userid != $row->params['userid'])
						continue;
					
					$row->personal = 1;
				}
				
				self::$all_sigs[] = $row;
			}
			
			$qry = "SELECT * FROM #__fss_users WHERE user_id = $userid";
			$db->setQuery($qry);
			$user = $db->loadObject();
			if ($user)
				$settings = json_decode($user->settings, true);
			
			$def_sig = SupportUsers::getSetting('default_sig');
			
			$ds = 0;
			if ($def_sig > 0)
			{
				foreach (self::$all_sigs as &$sig)
				{
					if ($sig->id == $def_sig)
						$sig->default = 1;	
				}
			}
		}
		return self::$all_sigs;
	}

	static function ParseSig($text, $ticket, $bbcode = true)
	{
		if (!is_object($ticket))
		{
			echo "CALLING PARSESIG WITHOUT TICKET OBJECT!";
			exit;
		}
		
		$parser = new FSSParser();
		$parser->loadText($text);
		$parser->Clear();
		$ticket->forParser($parser->vars, true);
		//FSSParserTicket::core($parser, $ticket);
		
		$user = JFactory::getUser();
		
		$parser->SetVar('handlername',$user->name);
		$parser->SetVar('handlerusername',$user->username);
		$parser->SetVar('handleremail',$user->email);

		$text = $parser->getTemplate();
		
		if ($bbcode)
			return FSS_Helper::ParseBBCode($text);
		
		return $text;
	}

	static function AppendSig($sigid, $ticket)
	{
		if ($sigid == 0)
			return "";

		$db = JFactory::getDBO();
		$qry = "SELECT * FROM #__fss_ticket_fragments WHERE id = " . FSSJ3Helper::getEscaped($db, $sigid);
		$db->setQuery($qry);
		
		$sig = $db->loadObject();
		
		if (!$sig)
			return "";
		
		return "\n\n" . self::ParseSig($sig->content, $ticket, false);
	}	
}