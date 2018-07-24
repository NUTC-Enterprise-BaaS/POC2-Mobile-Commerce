<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class FSSRoute
{
	static $d;
	static $last_item_id;
	
	static function _($url, $xhtml = false, $ssl = null, $d = false)
	{
		// skip any external urls
		if (strpos($url, "option") !== false && strpos($url, "option=com_fss") === false)
		{
			return JRoute::_($url, $xhtml, $ssl);	
		}	
		
		global $FSSRoute_debug;
		global $FSSRoute_menus;
		global $FSSRoute_access;
		
		self::$d = $d;
		
		// get any menu items for fss
		FSS_Helper::GetRouteMenus();

		// Get the router
		$router = JFactory::getApplication()->getRouter();
		
		// if the url dont start with index.php, we need to add the exisitng url to what we want
		if (substr($url,0,9) != "index.php")
		{
			//echo "Making FUll URL: $url<br>";
			$url = self::_fullURL($router, $url);	
			//echo "Resut : $url<br>";
		}
		
		$uri = new JURI($url);

		// work out is we are in an Itemid already, if so, set it as the best match
		if ($uri->hasVar('Itemid'))
		{
			$bestmatch = $uri->getVar('Itemid');
		} else {
			$bestmatch = '';	
		}
		$bestcount = 0;
		
		$uriquery = $uri->toString(array('query'));
		
		$urivars = FSSRoute::SplitURL($uriquery);
		
		if ($d) 
		{ 
			echo "URL : $url<br />\n";
			echo "URI : $uriquery<br />\n";
			print_p($urivars);
		}
		
		$sourcevars = FSSRoute::SplitURL($url);
		
		// check through the menu item for the current url, and add any items to the new url that are missing
		if ($bestmatch && array_key_exists($bestmatch,$FSSRoute_menus))
		{
			foreach($FSSRoute_menus[$bestmatch] as $key => $value)
			{
				if (!array_key_exists($key,$urivars) && !array_key_exists($key,$sourcevars))
				{
					$urivars[$key] = $value;
				}
			}
		}

		$current_access = 0;
		if (array_key_exists(FSS_Input::getInt('Itemid'), $FSSRoute_access))
			$current_access = $FSSRoute_access[FSS_Input::getInt('Itemid')];
	
		if ($d)
		{
			echo "Incoming Link : $url<br>";
			echo "Cur Item ID : " . FSS_Input::getInt('Itemid') . "<br>";
			//print_p($FSSRoute_menus);
		}
	
		foreach($FSSRoute_menus as $id => $vars)
		{
			if ($d)
			{
				echo "$id => <Br>";
				print_p($vars);
			}
			// need to check if the access level is the same
			if ($current_access && array_key_exists($id, $FSSRoute_access) && $FSSRoute_access[$id] != $current_access)
			{
				if ($d) echo "No Access<br>";
				continue;
			}
	
			$count = FSSRoute::MatchVars($urivars,$vars);
			
			if (FSS_Input::getInt('Itemid') == $id && $count > 0)
			{
				if ($d) echo "Current ItemId: increase count<br>";
				$count++;	
			}
			
			if ($d) echo "Count: $count<br>";
			if ($count > $bestcount)
			{
				if ($d)
				{
					echo "New best match - $id<br>";	
				}
				$bestcount = $count;
				$bestmatch = $id;	
			}
		}
	
		if ($bestcount == 0 && array_key_exists('view',$sourcevars) && substr($sourcevars['view'], 0, 6) == "admin_")
		{
			foreach($FSSRoute_menus as $id => $item)
			{
				// need to check if the access level is the same
				if ($current_access && array_key_exists($id, $FSSRoute_access) && $FSSRoute_access[$id] != $current_access)
					continue;

				if ($item['view'] == "admin")
				{
					$bestcount = 1;
					$bestmatch = $id;					
				}
			}
		}
		
		// no match found, try to fallback on the main support menu id
		if ($bestcount == 0)
		{
			foreach($FSSRoute_menus as $id => $item)
			{
				// need to check if the access level is the same
				if ($current_access && array_key_exists($id, $FSSRoute_access) && $FSSRoute_access[$id] != $current_access)
					continue;

				if ($item['view'] == "main")
				{
					$bestcount = 1;
					$bestmatch = $id;					
				}
			}
		}
		
		if ($bestcount == 0)
		{
			// still no match found, use any fss menu
			if (count($FSSRoute_menus) > 0)
			{
				foreach($FSSRoute_menus as $id => $item)
				{
					// need to check if the access level is the same
					if ($current_access && array_key_exists($id, $FSSRoute_access) && $FSSRoute_access[$id] != $current_access)
						continue;

					$bestcount = 1;
					$bestmatch = $id;					
					break;
				}				
			}
		}

		if ($d) echo "Best Found : $bestcount, $bestmatch<br>";

		// sticky menu items
		if (FSS_Settings::get('sticky_menus_type'))
		{
			$cur_item_id = FSS_Input::GetInt("Itemid");
			if ($cur_item_id > 0)
			{
				$sticky_ids = explode(";", FSS_Settings::get('sticky_menus'));
				
				if (
					(FSS_Settings::get('sticky_menus_type') == 1 && in_array($cur_item_id, $sticky_ids)) ||
					(FSS_Settings::get('sticky_menus_type') == 2 && !in_array($cur_item_id, $sticky_ids))
				   )
				{
					$bestcount = 0;
					$uri->setVar('Itemid',$cur_item_id);
				}
			}
		}

		if ($bestcount > 0)
		{
			$uri->setVar('Itemid',$bestmatch);
			// we need to remove parameters that are in the main url as well as the sub one
			// wait till 2.2 for this change as it may break stuff
		}
	
		if ($d) echo "\n\nUsing : " .htmlentities($uri->toString(array('path', 'query', 'fragment'))) . "<br>\n\n";

		$final = JRoute::_($uri->toString(array('path', 'query', 'fragment')), $xhtml, $ssl);
		
		if ($d) echo "\n\nFinal : " . $final . "<br />\n\n";
		
		return $final;
	}	

	static function OutputDebug()
	{
		global $FSSRoute_debug;
		if (count($FSSRoute_debug) > 0)
			foreach($FSSRoute_debug as $debug)
				echo $debug;		
	}

	static function SplitURL($link)
	{
		$link = str_ireplace("index.php?","",$link);
		if (substr($link, 0, 1) == "?")
			$link = substr($link, 1);
		
		$parts = explode("&",$link);
		$res = array();
		foreach($parts as $part)
		{
			if (strpos($part,"=") > 0)
			{
				list($key,$value) = explode("=",$part,2);
			} else {
				$key = $part;
				$value = "";	
			}
			if ($key == "option") continue;
			if (!$key) continue;
			$res[$key] = $value;	
		}
		return $res;
	}

	static function MatchVars($urivars, $vars)
	{
		foreach($vars as $key => $value)
		{
			if ($key == "layout")
			{
				if (!array_key_exists($key,$urivars))
				{
					if (self::$d) echo "L Not matching, $key from vars not in uri<br>";
					return 0;
				}
				if ($value != "" && $urivars[$key] != $value)
				{
					if (self::$d) echo "L Not matching, $key in uri is {$urivars[$key]} and $value in vars<br>";
					return 0;
				}			
			} else {
				if (!array_key_exists($key,$urivars) && array_key_exists($key, $urivars) && $urivars[$key] != "")
				{
					if (self::$d) echo "Not matching, $key from vars not in uri<br>";
					return 0;
				}
				if ($value != "" && array_key_exists($key, $urivars) && $urivars[$key] != $value)
				{
					if (self::$d) echo "Not matching, $key in uri is {$urivars[$key]} and $value in vars<br>";
					return 0;
				}
			}
		}
		$count = 0;
		foreach($urivars as $key => $value)
		{
			if (self::$d) echo "Matching $key => $value<br>";
			if (array_key_exists($key,$vars) && $vars[$key] == $value)
			{
				if (self::$d) echo "FOUND!<br>";
				if ($key == "view") // view counts for 3 so this will pull towards the current view
					$count = $count + 2;
				$count++;
			}
		}	

		return $count;
	}
	
	static function _fullURL($router, $url)
	{
		$surl = $url;
		
		$vars = array();
		if (strpos($url, '&amp;') !== false) {
			$url = str_replace('&amp;','&',$url);
		}

		parse_str($url, $vars);

		$rvars = $router->getVars();

		if (!is_array($rvars) || count($rvars) == 0)
		{
			$vars = array_merge($_GET, $vars);		
		} else {
			$vars = array_merge($rvars, $vars);
		}

		$noHtmlFilter = JFilterInput::getInstance();

		foreach($vars as $key => $var) {
			
			$var = $noHtmlFilter->clean($var, 'cmd');
			$vars[$key] = urlencode($var);
			
			if ($var == "") {
				unset($vars[$key]);
				continue;
			}
		}
		
		unset($vars['Itemid']);

		$url = 'index.php?'.JURI::buildQuery($vars);
		
		if (self::$d) echo "URL <b>$surl</b> --> <b>$url</b><br />\n";
		
		return $url;
	}
	
	static function x($url, $xhtml = true, $ssl = null)
	{
		return self::_($url, $xhtml, $ssl);
	}
}

