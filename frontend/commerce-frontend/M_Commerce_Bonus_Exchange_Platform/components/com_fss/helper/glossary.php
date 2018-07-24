<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class FSS_Glossary
{
	static $glossary;
	static $glo_ref;
	static $context;
	
	static $cdom = null;

	static function isWordCS($data)
	{
		if ($data->casesens == 1)
			return 0;
		if ($data->casesens == 2)
			return 1;
	
		$iscs = 0;
		
		if (FSS_Settings::get('glossary_case_sensitive') == 2)
			$iscs = 1;
		
		if (FSS_Settings::get('glossary_case_sensitive') == 1 && $data->word == strtoupper($data->word))
			$iscs = 1;
		
		return $iscs;
	}
	
	static function MakeAnchor($word)
	{
		return strtolower(preg_replace("/[^A-Za-z0-9]/", '-', $word));	
	}
	
	static function GetGlossary()
	{
		if (empty(FSS_Glossary::$glossary))
		{
			$db = JFactory::getDBO();
			$query = 'SELECT * FROM #__fss_glossary';
			
			$where = array();
			$where[] = " published = 1 ";
			$where[] = 'language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
			$user = JFactory::getUser();
			$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				

			if (count($where) > 0)
				$query .= " WHERE " . implode(" AND ",$where);

			$query .= ' ORDER BY LENGTH(word) DESC';
			
			$db->setQuery($query);
			self::$glossary = $db->loadObjectList();
			
			$extra = array();
			
			foreach (self::$glossary as $offset => &$data)
			{
				$data->base_offset = -1;
				$altwords = explode("\n", $data->altwords);
				$awp = array();
				foreach ($altwords as $aw)
				{
					$aw = trim($aw);
					if (!$aw) continue;
					
					$ex = clone $data;
					$ex->is_clone = true;
					$ex->word = $aw;
					$ex->linkword = $data->word;
					$ex->base_offset = $offset;
					$extra[] = $ex;
				}
			}
			
			self::$glossary = array_merge(self::$glossary, $extra);
			
			// add javascript
			FSS_Helper::StylesAndJS(array('tooltip', 'glossary'));
		}
	}
	
	static function ReplaceGlossary($text)
	{
		if (stripos($text, "id='XXX_GLOSSARY_DONE_XXX'") !== FALSE)
			return $text;

		self::GetGlossary();
		
		FSS_Settings::GetAllViewSettings();

		if (count(self::$glossary) == 0)
			return $text;

		// build a rough list of terms in the document in question. This means less stuff for the preg to check later on
		self::$glo_ref = array();
		foreach (self::$glossary as $offset => &$data)
		{
			$data->limit = 999;
			if (FSS_Settings::get('glossary_word_limit') > 0)
				$data->limit = FSS_Settings::get('glossary_word_limit');

			if (empty($data->linkword))
				$data->linkword = $data->word;

			$data->anc = self::MakeAnchor($data->word);
			$data->ref = $data->id . "-" . $data->anc;
			
			$linkanc = self::MakeAnchor($data->linkword);
			$linkref = $data->id . "-" . $data->anc;
			
			$data->href = "#";
			$data->cs = self::isWordCS($data);

			// word for regex						
			$rword = preg_quote($data->word);
			$rword = str_replace("/", "\/", $rword);				
			$data->regex = "/\b($rword)\b/";
			if (!$data->cs)	
				$data->regex .= "i";

			if (FSS_Settings::get('glossary_link'))
			{
				if (FSS_Settings::$fss_view_settings['glossary_long_desc'] == 1 && $data->longdesc) // if long description is set to be shown an a separate page and we have a long description available
				{
					// link directly to the item
					$data->href = FSSRoute::_("index.php?option=com_fss&view=glossary&layout=word&word=" . $linkref, false);
				} else {
					$data->href = FSSRoute::_( 'index.php?option=com_fss&view=glossary&letter='.strtolower(substr($data->linkword,0,1)).'#' . $linkanc, false );
				}
			}
			
			if (stripos($text, $data->word) !== FALSE) // found a word
			{
				self::$glo_ref[$data->ref] = $offset;
				
				$data->inuse = true;
			}
		}

		// setup empty dom object
		libxml_use_internal_errors(TRUE);
		$dom=new DOMDocument('1.0','UTF-8');
		
		FSS_Glossary::$cdom = $dom;

		$dom->substituteEntities=false;
		$dom->recover=true;
		$dom->strictErrorChecking=false;
		$dom->resolveExternals = false;


		// load the xml file. Add padding tags as the dom adds crap to the start of the output
		$dom->loadHTML('<?xml version="1.0" encoding="UTF-8"?><meta http-equiv="content-type" content="text/html; charset=utf-8"><xxxglossaryxxx>' . $text . "</xxxglossaryxxx>");
		$domx = "b0ddb3871b9b762418bdd24698da55e7";
		
		// get list of html tags to ignore
		$tag_ignore = FSS_Settings::get('glossary_exclude');
		$tag_ignore = explode(",", $tag_ignore);
		
		$tags = array();
		$tags[] = "a";
		foreach ($tag_ignore as $tag)
		{
			$tag = trim($tag);
			if (!$tag) continue;
			$tags[] = $tag;
		}
	
		// replace all glossary terms
		FSS_Glossary::preg_replace_dom($dom->documentElement, $tags);

		// get resultant html
		$result = $dom->saveHTML();

		//$result = str_replace("&amp;","&", $result);
		// use padding added earlier to remove appended content
		$pos1 = strpos($result, "<xxxglossaryxxx>") + 16;
		$pos2 = strrpos($result, "</xxxglossaryxxx>");
		$result = substr($result, $pos1, $pos2-$pos1) . "<div style='display: none' id='XXX_GLOSSARY_DONE_XXX'></div>";

		return $result;
	}
	
	static function preg_replace_dom(DOMNode $dom, array $exclude = array()) {
		
		if (empty($dom->childNodes))
			return;

		foreach ($dom->childNodes as $node) {
			
			// if the node is in the exclude list, skip it and its children
			if (in_array($node->nodeName, $exclude))
			continue;
			
			if ($node instanceof DOMText) // only process text elements
			{
				$totalcount = 0;

				// try and match all glossary words we have in the 
				foreach (self::$glossary as &$data)
				{
					$data_offset = $data->ref;
					
					if (empty($data->inuse) || !$data->inuse)
						continue;
					
					$replace = "XXX_GLOSSARY_".$data_offset."_$1_XXX";
					$count = 0;
					

					// UNCOMMENT and CHANGE $data->limit to $base_data->limit to make sure that alt words
					// share the same count and the main word.
					//$base_data = $data;
					//if ($data->base_offset > -1)
					//	$base_data = self::$glossary[$data->base_offset];

					$node->nodeValue = preg_replace_callback(
											$data->regex, 
											function($match) use ($data_offset) 
											{
												return "XXX_GLOSSARY_" . base64_encode($data_offset) . "_" . base64_encode($match[0]) . "_XXX";
												//return str_replace('$1', $match[0], $replace); 
											},
											$node->nodeValue, 
											$data->limit, 
											$count
										);
					if ($count)
					{
						$data->limit -= $count;
						$totalcount += $count;
					}
					
				}
				
				if ($totalcount)
				{
					$runs = 0; // run counter incase it goes wrong and gets carried away
					
					// temp node that we are working on
					$temp = $node;
					
					// find tag in node text
					if (function_exists("mb_strpos"))
					{
						$pos = mb_strpos($temp->nodeValue, "XXX_GLOSSARY_");							
					} else {
						$pos = strpos($temp->nodeValue, "XXX_GLOSSARY_");
					}
					
					while ($pos !== FALSE && $runs++ < 50) // while we have found an instance of 
					{
						// split the text node around the match
						$new = $temp->splitText($pos);
						
						// remove match text from split text and retrieve info
						list($elem, $new->nodeValue) = explode("_XXX", $new->nodeValue, 2);
						
						// parse the info found
						$elem = substr($elem, 13);
						list ($elem, $text) = explode("_", $elem, 2);
						
						$elem = base64_decode($elem);
						$text = base64_decode($text);
						
						// lookup info we saved earlier
						$data_offset = self::$glo_ref[$elem];
						$info = self::$glossary[$data_offset];
												
						// build link element
						$link_elem = FSS_Glossary::$cdom->createElement('a', $text);					
						$link_elem->setAttribute("href", $info->href);
						$link_elem->setAttribute("class", 'fss_glossary_word');
						$link_elem->setAttribute("ref", $info->ref);
						$link_elem->setAttribute("context", FSS_Glossary::$context);
						
						// insert link element before 2nd part of text split
						$temp->parentNode->insertBefore($link_elem, $new);
						
						// copy the new node over the old temp one
						$temp = $new;
						
						// find the text again
						if (function_exists("mb_strpos"))
						{
							$pos = mb_strpos($temp->nodeValue, "XXX_GLOSSARY_");							
						} else {
							$pos = strpos($temp->nodeValue, "XXX_GLOSSARY_");
						}
					}
				}
			} 
			else
			{
				FSS_Glossary::preg_replace_dom($node, $exclude);
			}
		}
	}

	static function Footer()
	{
		FSS_Glossary::GetGlossary();
		
		if (count(FSS_Glossary::$glossary) == 0)
			return "";
	
		$tail = "<div id='glossary_words' style='display:none;'>";
		$temp = "";

		$count = 0;

		foreach(FSS_Glossary::$glossary as $data)
		{
			if (empty($data->inuse) || !$data->inuse)
				continue;

			//if (!empty($data->is_clone) && $data->is_clone)
			//	continue;

			$count++;
			$footer = "";
			if ($data->longdesc && FSS_Settings::get('glossary_show_read_more'))
				$footer = "<p class='right fss_glossary_read_more' style='text-align: right'>" . JText::_(FSS_Settings::get('glossary_read_more_text')) . "</p>";
			
			if (FSS_Settings::get('glossary_title'))
			{
				$tail .= "<div id='glossary_" . $data->ref . "'><h4>" . $data->linkword . "</h4><div class='fsj_gt_inner'>" . $data->description . " $footer</div></div>";
			} else {
				$tail .= "<div id='glossary_" . $data->ref . "'><div class='fsj_gt_inner'>" . $data->description . " $footer</div></div>";
			}
		}
		$tail .= "</div>";
		
		if (!$count)
			return "";
		
		
		return $tail;
	}
}