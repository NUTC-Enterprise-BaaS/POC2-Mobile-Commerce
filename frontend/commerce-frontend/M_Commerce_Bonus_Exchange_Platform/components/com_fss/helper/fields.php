<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class FSSCustFieldPlugin
{
	var $name = "Please select a plugin";
	var $min_popup_height = 0;
	
	var $default_params = array();

	function DisplaySettings($params) // passed object with settings in
	{
		return "There are no settings for this plugin";
	}
	
	function GetGroupClass()
	{
		return "";	
	}
	
	function SaveSettings() // return object with settings in
	{
		return "";
	}
	
	function Input($current, $params, $context, $id) // output the field for editing
	{
		return "";
	}
	
	function Save($id, $params, $value = null)
	{
		return $value;
	}
	
	function Display($value, $params, $context, $id) // output the field for display
	{
		return $value;
	}
	
	function CanEdit()
	{
		return false;	
	}
	
	function CanSearch()
	{
		return false;	
	}

	function parseParams($params)
	{
		$data = new stdClass();
		
		foreach ($this->default_params as $key => $value)
			$data->$key = $value;

		$des = @unserialize($params);
		if ($des && is_array($des))
			foreach ($des as $key => $value)
				$data->$key = $value;

		$js = json_decode($params);
		if ($js && is_object($js))
			foreach ($js as $key => $value)
				$data->$key = $value;

		return $data;
	}

	function encodeParams($params)
	{
		return json_encode($params);
	}	
}

class FSSCF
{	
	static $_ticketvalues = array();
	
	static function &GetCustomFields($ticketid,$prod_id,$ticket_dept_id,$maxpermission = 3,$isopen = false,$foruser = false)
	{
		$db = JFactory::getDBO();

		if (!$ticketid) $ticketid = 0;
		if (!$prod_id) $prod_id = 0;
		if (!$ticket_dept_id) $ticket_dept_id = 0;
	
		// get a list of all available fields
		$qry = "SELECT * FROM #__fss_field as f WHERE f.published = 1 AND f.ident = 0 AND ";
		$qry .= " (allprods = 1 OR '".FSSJ3Helper::getEscaped($db, $prod_id)."' IN (SELECT prod_id FROM #__fss_field_prod WHERE field_id = f.id)) AND ";
		$qry .= " (alldepts = 1 OR '".FSSJ3Helper::getEscaped($db, $ticket_dept_id)."' IN (SELECT ticket_dept_id FROM #__fss_field_dept WHERE field_id = f.id)) ";
		
		if ($foruser)
		{
			if (!is_numeric($foruser)) $foruser = JFactory::getUser()->id;
			
			$qry .= " AND access IN (" . implode(',', JFactory::getUser($foruser)->getAuthorisedViewLevels()) . ')';				
		}
		/*if ($isopen)
		{
			$qry .= " 1 ";//(f.permissions <= '".FSSJ3Helper::getEscaped($db, $maxpermission)."' OR f.permissions = 4 OR f.permissions = 5) ";
		} else {
			$qry .= " (f.permissions <= '".FSSJ3Helper::getEscaped($db, $maxpermission)."' OR f.permissions = 5)  ";
		}*/
	
		$qry .= " ORDER BY f.ordering ";
		
		//echo $qry . "<br>";
		
		$db->setQuery($qry);
		
		$rows = $db->loadAssocList("id");

		FSS_Translate_Helper::Tr($rows);

		$indexes = array();

		if (count($rows) > 0)
		{
			foreach ($rows as $index => &$row)
			{
				$indexes[] = FSSJ3Helper::getEscaped($db, $index);
			} 
		}
	
		$indexlist = implode(",",$indexes);
		if (count($indexes) == 0)
			$indexlist = "0";
	
		$qry = "SELECT * FROM #__fss_field_values WHERE field_id IN ($indexlist)";
		$db->setQuery($qry);
		$values = $db->loadAssocList();

		if (count($values) > 0)
		{
			foreach($values as &$value)
			{
				$field_id = $value['field_id'];
				$rows[$field_id]['values'][] = $value['value'];

				if ($value['data'])
					$rows[$field_id]['values'][] = 'plugindata=' . $value['data'];
			}
		}
		
		// sort based on group
		$result = array();
		
		foreach ($rows as $field)
		{
			$result[$field['grouping']][] = $field;	
		}		
		
		$rows = array();
		
		foreach ($result as $group => $fields)
		{
			foreach ($fields as $field)
			{
				$rows[] = $field;	
			}	
		}

		return $rows;
	}

	static $allfields = array();
	static function &GetAllCustomFields($values = true, $ident = 0)
	{
		$values = true;
		
		$db = JFactory::getDBO();

		$key = $values.$ident;
		
		if (empty(FSSCF::$allfields[$key]))
		{
			// get a list of all available fields
			$qry = "SELECT * FROM #__fss_field as f WHERE f.published = 1";
			if ($ident > -1) $qry .= " AND f.ident = $ident ";
			$qry .= " ORDER BY f.grouping, f.ordering ";
			$db->setQuery($qry);
			$rows = $db->loadAssocList("id");
		
			FSS_Translate_Helper::Tr($rows);
		
			$indexes = array();

			if (count($rows) > 0)
			{
				foreach ($rows as $index => &$row)
				{
					$indexes[] = FSSJ3Helper::getEscaped($db, $index);
				} 
			}

			if ($values)
			{
				$indexlist = implode(",",$indexes);
				if (count($indexes) == 0)
					$indexlist = "0";
	
				$qry = "SELECT * FROM #__fss_field_values WHERE field_id IN ($indexlist)";
				$db->setQuery($qry);
				
				$values = $db->loadAssocList();

				if (count($values) > 0)
				{
					foreach($values as &$value)
					{
						$field_id = $value['field_id'];
						$rows[$field_id]['values'][] = $value['value'];
						if ($value['data']) $rows[$field_id]['values'][] = 'plugindata=' . $value['data'];
					}
				}

			}
			
			FSSCF::$allfields[$key] = $rows;
		}
		return FSSCF::$allfields[$key];
	}
	
	static function GetField($fieldid)
	{
		$db = JFactory::getDBO();
		$qry = "SELECT * FROM #__fss_field WHERE id = '".FSSJ3Helper::getEscaped($db, $fieldid)."'";
		$db->setQuery($qry);
		return $db->loadObject();
	}

	static function FieldHeader(&$field, $showreq = false, $showperuser = true)
	{
		echo $field['description'];
		if ($field['peruser'] && $showperuser)
			echo " <i class='icon-user fssTip' title='User Field'></i>";
	}
	
	static function FieldClass(&$field)
	{
		
		if ($field['type'] == "plugin")
		{
			$aparams = FSSCF::GetValues($field);
			$plugin = FSSCF::get_plugin($aparams['plugin']);
			return $plugin->GetGroupClass();
		}
		
		return "";			
	}

	static function FieldIsReq(&$field)
	{
		return $field['required'];
	}

	static function GetValues(&$field)
	{
		if ($field['type'] == "text" || $field['type'] == "area" || $field['type'] == "plugin")
		{
			if (!array_key_exists('values',$field))
				return array();
			
			$output = array();
			if ($field['type'] == "plugin")
			{
				$output['plugindata'] = "";
				$output['plugin'] = "";	
			}
			if (array_key_exists('values',$field) && count($field['values']) > 0)
			{
				foreach ($field['values'] as &$value)
				{
					$bits = explode("=",$value,2);
					if (count($bits) == 2)
					{
						$output[$bits[0]] = $bits[1];	
					}
				}
			}
		
			return $output;
		}
	
		if ($field['type'] == "radio" || $field['type'] == "combo")
		{
			if (!array_key_exists('values',$field))
				return array();
			
			$result = array();

			foreach ($field['values'] as $offset => $value)
			{
				if (strpos($value, "|") !== false)
				{
					list($offset, $value) = explode("|", $value, 2);
					$offset = (int)$offset;
					$result[$offset] = $value;
				} else {
					$result[$offset] = $value;	
				}
			}
			
			ksort($result);
			$field['values'] = $result;

			return $field['values'];	
		}
	
		if ($field['type'] == "checkbox")
			return array();
	}

	static function FieldInput(&$field,&$errors,$errortype="ticket",$context = array(), $showhelp = false)
	{
		$field['min_popup_height'] = 0;
		
		$output = "";
		
		$id = $field['id'];
		
		$userid = 0;
		if (array_key_exists('userid',$context))
			$userid = $context['userid'];
		$ticketid = 0;
		if (array_key_exists('ticketid',$context))
			$ticketid = $context['ticketid'];
		
		// if its a per user field, try to load the value
		$current = $field['default'];

		if ($field['peruser'] && $errortype == "ticket")
		{
			$uservalues = FSSCF::GetUserValues($userid, $ticketid);
			
			if (array_key_exists($field['id'],$uservalues))
			{
				$current = $uservalues[$field['id']]['value'];
			}
		}
		
		$current = FSS_Input::getString("custom_$id",$current);

		if ($field['type'] == "text")
		{
			$aparams = FSSCF::GetValues($field);
			$text_max = $aparams['text_max'];
			$text_size = $aparams['text_size'];
			$output = "<input type='text' class='input-large custom_".$field['alias'] . "' name='custom_$id' id='custom_$id' value=\"".FSS_Helper::escape($current)."\" maxlength='$text_max' size='$text_size' ";
			if ($field['required'])
				$output .= " required ";
			$output .= " placeholder='" . htmlentities($field['description'],ENT_QUOTES,"utf-8") . "' ";
			$output .= ">\n";
		}
	
		if ($field['type'] == "radio")
		{
			$values = FSSCF::GetValues($field);
			$output = "";
			if (count($values) > 0)
			{
				foreach ($values as $value)
				{
					$output .= "<input class='custom_".$field['alias'] . "' type='radio' id='custom_$id' name='custom_$id' value=\"".FSS_Helper::escape($value)."\"";
					if ($value == $current) $output .= " checked";
					$output .= ">$value<br>\n";
				}	
			}
		} 
	
		if ($field['type'] == "combo")
		{
			$values = FSSCF::GetValues($field);
			$output = "<select class='custom_".$field['alias'] . "' name='custom_$id' id='custom_$id' ";
			if ($field['required']) $output .= " required ";						
			$output .= ">\n";
			$output .= "<option value=''>".JText::_("PLEASE_SELECT")."</option>\n";
			if (count($values) > 0)
			{
				foreach ($values as $value)
				{
					$output .= "<option value=\"".FSS_Helper::escape($value)."\"";
					if ($value == $current) $output .= " selected";
					$output .= ">$value</option>\n";
				}	
			}
			$output .= "</select>";
		}
	
		if ($field['type'] == "area")
		{
			$aparams = FSSCF::GetValues($field);
			$area_width = $aparams['area_width'];
			$area_height = $aparams['area_height'];
			$height = $area_height * 15;
			$output = "<textarea class='custom_".$field['alias'] . "' name='custom_$id' id='custom_$id' cols='$area_width' rows='$area_height' style='width:95%;height:{$height}px' ";
			if ($field['required']) $output .= " required ";			
			$output .= ">$current</textarea>\n";
		}
	
		if ($field['type'] == "checkbox")
		{	
			$output = "<input class='custom_".$field['alias'] . "' type='checkbox' name='custom_$id' id='custom_$id'";
			if ($current == "on") $output .= " checked";
			if ($field['required']) $output .= " required ";
			$output .= ">\n";
		}
		
		if ($field['type'] == "plugin")
		{
			$aparams = FSSCF::GetValues($field);
			$plugin = FSSCF::get_plugin($aparams['plugin']);
			$field['min_popup_height'] = $plugin->min_popup_height;
			
			$output = $plugin->Input($current, $aparams['plugindata'], $context, $id);
		}
	
		$id = "custom_" .$field['id'];
		
		if (array_key_exists($id,$errors))
		{
			if ($errortype == "ticket")
			{
				$output .= '<span class="help-inline">' . $errors[$id] . '</span>';
			} else {
				$output .= '</td><td class="fss_must_have_field">' . $errors[$id];
			}
		} else if ($showhelp)
		{
			$output .= '<span class="help-inline">' . $field['helptext'] . '</span>';
		}
	
		return $output;
	}
	
	static function HasErrors($field, $errors)
	{
		$id = "custom_" .$field['id'];
		
		if (array_key_exists($id,$errors))
			return true;
		
		return false;
	}
	
	static function get_plugin_from_row(&$row)
	{
		$db	= JFactory::getDBO();
		
		$query = "SELECT value FROM #__fss_field_values WHERE field_id = " . FSSJ3Helper::getEscaped($db, $row->id);
		$db->setQuery($query);
		$values = FSSJ3Helper::loadResultArray($db);
	
		$plugin_name = '';
		$plugin_data = '';
		
		foreach ($values as $value)
		{
			$bits = explode("=",$value);
			if (count($bits == 2))
			{
				if ($bits[0] == "plugin")
					$plugin_name = $bits[1];
				if ($bits[0] == "plugindata")
					$plugin_data = $bits[1];
			}
		}
		
		return FSSCF::get_plugin($plugin_name);
	}	
		
	static function get_plugin($name)
	{
		if ($name == "")
			return new FSSCustFieldPlugin();
		
		$name = preg_replace("/[^a-zA-Z0-9\s]/", "", $name);
		$name = strtolower($name);
		$filename = JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'plugins'.DS.'custfield'.DS.$name.".php";
		if (!file_exists($filename))
			return new FSSCustFieldPlugin();
		
		require_once($filename);
		$classname = $name . "Plugin";
		$obj = new $classname();
		
		return $obj;	
	}
	
	static $plugins;
	static function get_plugins()
	{
		if (!empty(self::$plugins))
			return self::$plugins;
		
		$path = JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'plugins'.DS.'custfield'.DS;
		
		$plugins = array();
		
		$files = JFolder::files($path,'(.php$)');

		foreach ($files as $file)
		{
			$filename = $path . $file;
			$file = str_replace(".php","",$file);
			$class = $file . "Plugin";
			
			if (file_exists($filename) && is_readable($filename))
				@include_once($filename);

			if (class_exists($class))
				$plugins[$file] = new $class();
		}
		
		self::$plugins = $plugins;
		
		return $plugins;
	}
	
	static function get_filenames($source_dir, $include_path = FALSE, $_recursion = FALSE)
	{
		static $_filedata = array();

		if ($fp = @opendir($source_dir))
		{
			// reset the array and make sure $source_dir has a trailing slash on the initial call
			if ($_recursion === FALSE)
			{
				$_filedata = array();
				$source_dir = rtrim(realpath($source_dir), DS).DS;
			}

			while (FALSE !== ($file = readdir($fp)))
			{
				if (@is_dir($source_dir.$file) && strncmp($file, '.', 1) !== 0)
				{
					FSSCF::get_filenames($source_dir.$file.DS, $include_path, TRUE);
				}
				elseif (strncmp($file, '.', 1) !== 0)
				{
					$_filedata[] = ($include_path == TRUE) ? $source_dir.$file : $file;
				}
			}
			return $_filedata;
		}
		else
		{
			return FALSE;
		}
	}
		
	static function ValidateFields(&$fields, &$errors)
	{
		$ok = true;
		foreach ($fields as &$field)
		{
			if ($field['required'] > 0)
			{
				$value = FSS_Input::getString("custom_" . $field['id'],"XXXYYYXXX");
				
				if (
						($field['type'] == "checkbox" && $value == "XXXYYYXXX")
					||
						$value == ""
					)
				{
					$message = JText::sprintf("YOU_MUST_ENTER_A_VALUE_FOR",$field['description']);	
					if ($field['blankmessage'] != "") $message = $field['blankmessage'];
					$errors["custom_" . $field['id']] = $message;
					$ok = false;
				}	
			}
		}

		return $ok;
	}

	static function StoreFields(&$fields, $ticketid)
	{
		$ticket = new SupportTicket();
		$ticket->load($ticketid, "force");

		$allfields = FSSCF::GetAllCustomFields(false);
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$userid = $ticket->user_id;

		if (count($fields) > 0)
		{
			foreach ($fields as &$field)
			{
				// only place this is called is creating a new ticket, so dont overwrite any per user fields that have permissions > 0
				if (array_key_exists($field['id'],$allfields) && $allfields[$field['id']]['peruser'] && $allfields[$field['id']]['permissions'] > 0 && $allfields[$field['id']]['permissions'] < 4)
				{
					continue;
				}
					
				$value = FSS_Input::getString("custom_" . $field['id'],"XX--XX--XX");
				
				if ($field['type'] == "plugin")
				{
					$aparams = FSSCF::GetValues($field);
					$plugin = FSSCF::get_plugin($aparams['plugin']);
					
					$value = $plugin->Save($field['id'], $aparams['plugindata']);
				}
				
				if ($value != "XX--XX--XX")
				{
					if (array_key_exists($field['id'],$allfields) && $allfields[$field['id']]['peruser'] && $userid > 0)
					{
						$qry = "REPLACE INTO #__fss_ticket_user_field (user_id, field_id, value) VALUES ('".FSSJ3Helper::getEscaped($db, $userid)."','";
						$qry .= FSSJ3Helper::getEscaped($db, $field['id']) . "','";
						$qry .= FSSJ3Helper::getEscaped($db, $value) . "')";
						$db->setQuery($qry);
						$db->Query();
					} else {
						$qry = "REPLACE INTO #__fss_ticket_field (ticket_id, field_id, value) VALUES ('".FSSJ3Helper::getEscaped($db, $ticketid)."','";
						$qry .= FSSJ3Helper::getEscaped($db, $field['id']) . "','";
						$qry .= FSSJ3Helper::getEscaped($db, $value) . "')";
						$db->setQuery($qry);
						$db->Query();
					}
				}	
			}
		}	
	}

	static function StoreField($fieldid, $ticketid, $ticket, $value = null)
	{
		$allfields = FSSCF::GetAllCustomFields(true);
		//echo "V1 : $value<br>";
		
		//print_p($allfields);
		$db = JFactory::getDBO();
		
		if (!$value)
			$value = FSS_Input::getString("custom_" . $fieldid,"");

		$field = $allfields[$fieldid];
		
		//echo "V2 : $value<br>";
		
		if ($field['type'] == "plugin")
		{
			$aparams = FSSCF::GetValues($field);
			$plugin = FSSCF::get_plugin($aparams['plugin']);
									
			$value = $plugin->Save($field['id'], $aparams['plugindata'], $value);
		}
		//echo "V3 : $value<br>";
		
		if (is_array($ticket))
		{
			$userid = $ticket['user_id'];	
		} else {
			$userid = $ticket->user_id;
		}
		
		if (array_key_exists($fieldid, $allfields) && $allfields[$fieldid]['peruser'] && $userid > 0)
		{
			
			$qry = "SELECT value FROM #__fss_ticket_user_field WHERE user_id = '".FSSJ3Helper::getEscaped($db, $userid)."' AND field_id = '".FSSJ3Helper::getEscaped($db, $fieldid)."'";
			//echo $qry . "<br>";
			$db->setQuery($qry);
			$row = $db->loadObject();
			$qry = "REPLACE INTO #__fss_ticket_user_field (user_id, field_id, value) VALUES ('".FSSJ3Helper::getEscaped($db, $userid)."','";
			$qry .= FSSJ3Helper::getEscaped($db, $fieldid). "','";
			$qry .= FSSJ3Helper::getEscaped($db, $value) . "')";
			$db->setQuery($qry);
			//echo $qry . "<br>";
			$db->Query();
			
		} else{
						
			$qry = "SELECT value FROM #__fss_ticket_field WHERE ticket_id = '".FSSJ3Helper::getEscaped($db, $ticketid)."' AND field_id = '".FSSJ3Helper::getEscaped($db, $fieldid)."'";
			//echo $qry . "<br>";
			$db->setQuery($qry);
			$row = $db->loadObject();
			$qry = "REPLACE INTO #__fss_ticket_field (ticket_id, field_id, value) VALUES ('".FSSJ3Helper::getEscaped($db, $ticketid)."','";
			$qry .= FSSJ3Helper::getEscaped($db, $fieldid). "','";
			$qry .= FSSJ3Helper::getEscaped($db, $value) . "')";
			//echo $qry . "<br>";
			$db->setQuery($qry);
			$db->Query();
		}
		if (!$row)
			return array("",$value);
			
		return array($row->value,$value);
	}

	static $user_values;
	static $ticket_user_id;
	static function GetUserValues($userid = 0,$ticketid = 0)
	{
		if ($ticketid < 1 && $userid < 1)
		{
			$result = array();
			return $result;
		}
		
		if (empty(FSSCF::$user_values))
		{
			$db = JFactory::getDBO();
			if ($userid < 1)
			{
				if (empty(FSSCF::$ticket_user_id))
				{
					$qry = "SELECT user_id FROM #__fss_ticket_ticket WHERE id = '".FSSJ3Helper::getEscaped($db, $ticketid)."'";
					$db->setQuery($qry);
					$row = $db->loadObject();
					if ($row)
						FSSCF::$ticket_user_id = $row->user_id;	
				}
				
				$userid = FSSCF::$ticket_user_id;
			}
			
			if ($userid < 1)
				return array();
			
			$qry = "SELECT * FROM #__fss_ticket_user_field WHERE user_id ='".FSSJ3Helper::getEscaped($db, $userid)."'";
			$db->setQuery($qry);
			FSSCF::$user_values = $db->loadAssocList('field_id');
		}
		
		return FSSCF::$user_values;
	}

	static function &GetTicketValues($ticketid,$ticket)
	{
		if (empty(FSSCF::$_ticketvalues))
			FSSCF::$_ticketvalues = array();
			
		if (!array_key_exists($ticketid,FSSCF::$_ticketvalues))
		{
			$allfields = FSSCF::GetAllCustomFields(true);
			
			$db = JFactory::getDBO();
			$qry = "SELECT * FROM #__fss_ticket_field WHERE ticket_id ='".FSSJ3Helper::getEscaped($db, $ticketid)."'";
			$db->setQuery($qry);
			$values = $db->loadAssocList('field_id');
		
			if (is_array($ticket))
			{
				$values2 = FSSCF::GetUserValues($ticket['user_id'], $ticket['id']);
			} else {
				$values2 = FSSCF::GetUserValues($ticket->user_id, $ticket->id);
			}
			
			foreach ($values2 as $id => $value)
			{
				if (array_key_exists($id, $allfields) && $allfields[$id]['peruser'])
					$values[$id] = $value;
			}
			FSSCF::$_ticketvalues[$ticketid] = $values;
		}
		return FSSCF::$_ticketvalues[$ticketid];	
	}

	static function FieldOutput($field, $fieldvalues,$context /*$ticketid = 0, $userid = 0*/)
	{
		$value = "";
		if (count($fieldvalues) > 0)
		{
			foreach ($fieldvalues as $fieldvalue)
			{
				if ($fieldvalue['field_id'] == $field['id'])
				{
					$value = $fieldvalue['value'];
					break;	
				}	
			}
		}
		
		if ($field['type'] == "plugin")
		{
			$aparams = FSSCF::GetValues($field);
			$plugin = FSSCF::get_plugin($aparams['plugin']);
			$value = $plugin->Display($value, $aparams['plugindata'], $context, $field['id']);
		}
		
		if ($field['type'] == "area")
		{
			$value = str_replace("\n","<br />",$value);	
		}
	
		if ($field['type'] == "checkbox")
		{
			if ($value == "on")
				return "Yes";
			return "No";
		}

		return $value;
	}
	
	// stuff below here is specific for comments
	static function &Comm_GetCustomFields($ident)
	{
		$db = JFactory::getDBO();
	
		// get a list of all available fields
		if ($ident != -1)
		{
			$qry = "SELECT * FROM #__fss_field as f WHERE f.published = 1 AND (f.ident = 999 OR f.ident = '".FSSJ3Helper::getEscaped($db, $ident)."') ";
		} else {
			$qry = "SELECT * FROM #__fss_field as f WHERE f.published = 1 ";
		}
	
		$qry .= " ORDER BY f.ordering";
		$db->setQuery($qry);
		$rows = $db->loadAssocList("id");

		$indexes = array();

		if (count($rows) > 0)
		{
			foreach ($rows as $index => &$row)
			{
				$indexes[] = FSSJ3Helper::getEscaped($db, $index);
			} 
		}
	
		$indexlist = implode(",",$indexes);
		if (count($indexes) == 0)
			$indexlist = "0";
	
		$qry = "SELECT * FROM #__fss_field_values WHERE field_id IN ($indexlist)";
		$db->setQuery($qry);
		$values = $db->loadAssocList();

		if (count($values) > 0)
		{
			foreach($values as &$value)
			{
				$field_id = $value['field_id'];
				$rows[$field_id]['values'][] = $value['value'];
				if ($value['data']) $rows[$field_id]['values'][] = 'plugindata=' . $value['data'];
			}
		}

		return $rows;
	}
	
	static function Comm_StoreFields(&$fields)
	{
		$allfields = FSSCF::GetAllCustomFields(false);

		$result = array();
		
		if (count($fields) > 0)
		{
			foreach ($fields as &$field)
			{
				$value = JRequest::getVar("custom_" . $field['id'],"XX--XX--XX");
				
				if ($field['type'] == "plugin")
				{
					$aparams = FSSCF::GetValues($field);
					$plugin = FSSCF::get_plugin($aparams['plugin']);
					
					$value = $plugin->Save($field['id'], $aparams['plugindata']);
				}
				
				if ($value != "XX--XX--XX")
				{
					$result[$field['id']] = $value;
				}	
			}
		}

		return $result;
	}

}