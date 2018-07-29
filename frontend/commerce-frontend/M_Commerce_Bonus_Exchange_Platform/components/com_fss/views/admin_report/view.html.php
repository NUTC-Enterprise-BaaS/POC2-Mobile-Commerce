<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @deprecated 7ef029820f2711d25b876ac9a81b7a55
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
jimport( 'joomla.filesystem.folder' );
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'tickethelper.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'admin_helper.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'parser.php');

class FssViewAdmin_Report extends FSSView
{
	var $paths = array();
	
	function __construct()
	{
		$this->paths = array(
			JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'plugins'.DS.'reports',
			JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'report'.DS.'reports',
			JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'plugins'.DS.'reports'.DS.'custom',
			);	
	
		parent::__construct();
	}
	
	function display($tpl = null)
	{
		FSS_Helper::noBots();
		FSS_Helper::noCache();
		
		FSS_Helper::StylesAndJS(array('cookie'));

		if (!FSS_Permission::auth("fss.reports", "com_fss.reports"))
			return FSS_Admin_Helper::NoPerm();

		$this->CheckDateTables();

		$report = FSS_Input::getCmd('report');
		$this->report_name = $report;
		
		if (!$report)
			return $this->DisplayReports();
		
		return $this->report($report);
	}
	
	function CheckDateTables()
	{
		$db = JFactory::getDBO();

		$qry = "SELECT count(*) as cnt FROM #__fss_date_day";
		$db->setQuery($qry);
		$data = $db->loadObject();
				
		if ($data->cnt < 1)
		{
			for ($i = 0 ; $i < 365*15 ; $i++)
			{
				$date = strtotime("2008-01-01") + 86400 * $i;
				$qry = "REPLACE INTO #__fss_date_day (`date`) VALUES ('".date("Y-m-d", $date)."')";
				$db->setQuery($qry);
				$db->query();
			}
			
			$qry = "UPDATE #__fss_date_day SET week = CONCAT(YEAR(`date`), '-', LPAD(WEEK(`date`), 2, '0'))";
			$db->setQuery($qry);
			$db->query();
			
			$qry = "UPDATE #__fss_date_day SET month = CONCAT(YEAR(`date`), '-', LPAD(MONTH(`date`), 2, '0'))";
			$db->setQuery($qry);
			$db->query();
			
			$qry = "UPDATE #__fss_date_day SET year = YEAR(`date`)";
			$db->setQuery($qry);
			$db->query();
		}
	}	
	
	function DisplayReports()
	{
		$this->reports = array();
		foreach ($this->paths as $path)
			$this->LoadReportsInPath($path);		
		
		$lang = JFactory::getLanguage();
		foreach ($this->reports as $report)
		{
			$lang->load("report_" . $report->name . ".sys", JPATH_COMPONENT);
		}
		
		usort($this->reports, array($this, "SortReports"));
		
		parent::display();
	}
	
	function SortReports($a, $b)
	{
		return strcasecmp($a->name, $b->name);
	}
	
	function GetReports()
	{
		$this->reports = array();
		foreach ($this->paths as $path)
			$this->LoadReportsInPath($path);
		
		//print_p($this->reports);
		
		return $this->reports;
	}
	
	function LoadReportsInPath($path)
	{	
		if (!file_exists($path))
			return; 
		
		$files = JFolder::files($path, ".xml$");
		
		foreach ($files as $file)
		{
			$xmlfile = $path . DS . $file;
			
			$xml = @simplexml_load_file($xmlfile);
			
			if ($xml)
			{
				$report = new stdClass();
				$report->name = str_replace(".xml", "", $file);
				$report->title = (string)$xml->title;
				$report->description = (string)$xml->description;
				
				// no longer list old v1.x reports
				if (strpos($path, "views") > 0)
				{
					if ($report->name == "active_users") continue;	
					if ($report->name == "daily_tickets") continue;	
					if ($report->name == "list_tickets") continue;	
					if ($report->name == "open_tickets") continue;	
				}
				
				$this->reports[$report->name] = $report;	
			}
		}		
	}
	
	
	function Report($report_name)
	{
		if (!FSS_Permission::auth("fss.reports.report." . $report_name, "com_fss.reports") && 
			!FSS_Permission::auth("fss.reports.all", "com_fss.reports") )
			return FSS_Admin_Helper::NoPerm();
		
		
		$lang = JFactory::getLanguage();
		$lang->load("report_shared", JPATH_COMPONENT);
		$lang->load("report_" . $report_name . ".sys", JPATH_COMPONENT);
		$lang->load("report_" . $report_name, JPATH_COMPONENT);

		$report = $this->LoadXML($report_name);
		
		if (!$report)
		{
			echo "Unable to open report xml<br>";	
			return;
		}
		
		$this->graph = 0;
		if (isset($report->graph))
			$this->graph = 1;
		
		$report->getData();
		$report->translateData();
		
		//print_p($report);
		
		$this->OutputData($report);
	}
	
	function LoadXML($report_name)
	{
		$xmlfile = $this->FindReport($report_name);
		
		$xml = @simplexml_load_file($xmlfile);
		
		if (!$xml)
			return null;
		
		$report = FSS_Report_Helper::XMLToClass($xml, "FSJ_Report");
		$report->sort();

		return $report;
	}
	
	function FindReport($report_name)
	{
		$file = "";
		
		foreach ($this->paths as $path)
		{
			$xmlfile = $path.DS."{$report_name}.xml";
			
			if (file_exists($xmlfile))
				$file = $xmlfile;
		}		
		
		return $file;
	}
	
	function OutputData(&$report)
	{
		$this->report = $report;
		
		if (FSS_Input::getCmd('output') == "csv")
			$this->outputCSV();
		
		if (FSS_Input::getCmd("output") == "print")
			return parent::display("print");
		
		parent::display("report");
	}
	
	function outputCSV()
	{	
		if (ob_get_level() > 0)
			ob_end_clean();

		$report_name = $this->report->title . " " . date("Y-m-d H.i.s");
		header('Content-Encoding: UTF-8');
		header("Content-type: text/csv; charset=UTF-8");
		header("Content-Disposition: attachment; filename=" . $report_name . ".csv");
		header("Pragma: no-cache");
		header("Expires: 0");

		$sep = FSS_Settings::get('reports_separator');
		
		if (SupportUsers::getSetting('reports_separator') != "")
			$sep = SupportUsers::getSetting('reports_separator');

		echo "\xEF\xBB\xBF"; 
		$outline = array();
		foreach ($this->report->field as $field)
		{
			$outline[] = $field->text;
		}
		
		$fp = fopen('php://output', 'w');
		fputcsv($fp, $outline, $sep);

		foreach ($this->report->data as $row)
		{
			$outline = array();
			
			foreach ($this->report->field as $field)
			{
				$name = $field->name;
				$value = $row->$name;
				if (isset($field->format))
				{
					if ($field->format == "date")	
					{
						if ($value != "" && $value != "0000-00-00" && $value != "0000-00-00 00:00:00")
						{
							$format = "Y-m-d"; 
							if (isset($field->dateformat)) 
								$format = $field->dateformat;
							if (substr($format, 0, 5) == "DATE_")
								$format = JText::_($format);
							$jdate = new JDate($value);
							$value = $jdate->format($format);
						} elseif (isset($field->blank)) {
							$value = $field->blank; 
						}
					} else if ($field->format == "messagetime")
					{
						if ($value < 1)
						{
							$value = "";
						} else if ($value > 0 && $value < 86400 * 10)
						{
							$value = date("H:i", $value);
						} else {
							$format = "Y-m-d"; 
							if (isset($field->dateformat)) 
							$format = $field->dateformat;
							if (substr($format, 0, 5) == "DATE_")
							$format = JText::_($format);
							$jdate = new JDate($value);
							$value = $jdate->format($format);
						}
					} else if ($field->format == "hm")
					{
						$val = $value; 
						$mins = $val % 60;
						$hrs = floor($val / 60);
						
						$value = sprintf("%d:%02d", $hrs, $mins); 
					} else if ($field->format == "bbcode")
					{
						$value = preg_replace("/\[img\]data(.*)\[\/img\]/i", "", $value);
						//$value = FSS_Helper::ParseBBCode($value, null, true, true); 	
					}
				}
				
				$outline[] = $value;

			}

			//echo implode($sep, $outline) . "\n";
			fputcsv($fp, $outline, $sep);
		}
		
		fclose($fp);
		exit;
	}
	
	function parseLink($link, $row)
	{
		foreach ($row as $key => $value)
		{
			$link = str_ireplace("{" . $key . "}", urlencode($value), $link);
		}	
		
		return $link;
	}	
	function parseText($link, $row)
	{
		foreach ($row as $key => $value)
		{
			$link = str_ireplace("{" . $key . "}", $value, $link);
		}	
		
		return $link;
	}
}

class FSJ_Report
{
	var $field = array();
	var $filter = array();
	var $group = array();
	var $translate = array();
	var $error;
	
	function sort()
	{
		foreach ($this->filter as &$filter)
		{
			$filter->options = array();
			$filter->select = array();
			
			if (isset($filter->extra))
			{
				FSS_Report_Helper::O2A($filter, "extra");	
				
				foreach ($filter->extra as $extra)
				{
					if (!isset($extra->key))
						$extra->key = '';

					$option = new stdClass();					
					$option->key = $extra->key;
					$option->display = JText::_($extra->text);
					$option->value = $extra->key;					
					if (isset($extra->value))
						$option->value = $extra->value;	
					
					$filter->options[$option->key] = $option;
					$filter->select[] = $option;					
				}
			}
			
			if (isset($filter->sql))
			{
				$key = $filter->key;
				$display = $filter->display;
				
				//echo "Filter SQL : " . $filter->sql . "<br>";
				$qry = $this->makeSQL($filter->sql);
				//echo "Parsed SQL : " . $qry . "<br>";
				$db = JFactory::getDBO();
				$db->setQuery($qry);
				$rows = $db->loadObjectList();
				
				if (isset($filter->translate))
				{
					FSS_Translate_Helper::Tr($rows);

					$display = $filter->display;
					foreach ($rows as &$item)
					{
						if (isset($item->$display))
							$item->display = $item->$display;
					}					
				}

				foreach ($rows as $row)
				{
					$option = new stdClass();
					$option->key = $row->$key;
					$option->value = $row->$key;
					$option->display = $row->$display;
					
					$filter->options[$option->key] = $option;
					$filter->select[] = $option;					
				}
			}
		}
		
		foreach ($this->group as &$group)
		{
			if ($group->type == "dategroup")
			{
				$option = new stdClass();
				$option->key = "day";
				$option->value = "day";
				$option->display = JText::_("Day");
				
				$group->options[$option->key] = $option;
				$group->select[] = $option;	
							
				$option = new stdClass();
				$option->key = "week";
				$option->value = "week";
				$option->display = JText::_("Week");
				
				$group->options[$option->key] = $option;
				$group->select[] = $option;		
						
				$option = new stdClass();
				$option->key = "month";
				$option->value = "month";
				$option->display = JText::_("Month");
				
				$group->options[$option->key] = $option;
				$group->select[] = $option;	
							
				$option = new stdClass();
				$option->key = "year";
				$option->value = "year";
				$option->display = JText::_("Year");
				
				$group->options[$option->key] = $option;
				$group->select[] = $option;				
			}	
		}
	}
	
	function getData()
	{
		if (empty($this->sql)) $this->sql = "";

		$this->final_sql = $this->makeSQL($this->sql);

		if (isset($this->custom_data))
		{
			$fn = create_function('$report, $filter', (string)$this->custom_data);

			$this->data = $fn($this, $this->vars);

			return $this->data;
		}
		
		$db = JFactory::getDBO();
		$db->setQuery($this->final_sql);

		$this->data = array();

		try {
			$this->data = $db->loadObjectList();
		} catch (exception $e)
		{
		}
		
		if (!$this->data)
		{
			$this->error = $db->getErrorMsg();	
			if (strpos($this->error, "SQL=") !== false)
			{
				$this->error = substr($this->error, 0, strpos($this->error, "SQL="));
			}
		}
		return $this->data;	
	}
	
	function translateData()
	{
		$curlang = str_replace("-","",JFactory::getLanguage()->getTag());

		foreach ($this->translate as $tran)
		{
			$field = $tran->field;
			$trdata = $tran->data;
			$source = $tran->source;

			foreach ($this->data as &$data)
			{
				if (!property_exists($data, $trdata))
					continue;
				
				$translation = json_decode($data->$trdata, true);
				if (!$translation)
					continue;

				if (!array_key_exists($source, $translation)) continue;
				if (!array_key_exists($curlang, $translation[$source])) continue;

				$data->$field = $translation[$source][$curlang];
			}
		}
	}

	function getDateRangeFrom($filter)
	{
		$default = "";
		if (isset($filter->default)) $default = $filter->default;

		switch ($default)
		{
			case 'thisweek':
				$string_date = date("Y-m-d");
				$day_of_week = date('N', strtotime($string_date));
				return date('Y-m-d', strtotime($string_date . " - " . ($day_of_week - 1) . " days"));

			case 'thismonth':
				return date('Y-m-01');

			case 'lastmonth':
				$month_ini = new DateTime("first day of last month");
				return $month_ini->format('Y-m-d');

			case 'lastweek':
				$string_date = date("Y-m-d");
				$day_of_week = date('N', strtotime($string_date));
				return date('Y-m-d', strtotime($string_date . " - " . ($day_of_week + 6) . " days"));

			case 'datetime':
				$month_ini = new DateTime($filter->default_from);
				return $month_ini->format('Y-m-d');

			case 'diff':
				return date("Y-m-d", time() - $filter->default_from * 86400);

			default:
				return date("Y-m-d", time() - 90 * 86400);
		}
	}

	function getDateRangeTo($filter)
	{
		$default = "";
		if (isset($filter->default)) $default = $filter->default;

		switch ($default)
		{
			case 'thisweek':
				$string_date = date("Y-m-d");
				$day_of_week = date('N', strtotime($string_date));
				return date('Y-m-d', strtotime($string_date . " + " . (7 - $day_of_week) . " days"));

			case 'thismonth':
				return date('Y-m-t');

			case 'lastmonth':
				$month_ini = new DateTime("last day of last month");
				return $month_ini->format('Y-m-d');

			case 'lastweek':
				$string_date = date("Y-m-d");
				$day_of_week = date('N', strtotime($string_date));
				return date('Y-m-d', strtotime($string_date . " - " . ($day_of_week) . " days"));

			case 'datetime':
				$month_ini = new DateTime($filter->default_to);
				return $month_ini->format('Y-m-d');

			case 'diff':
				return date("Y-m-d", time() - ($filter->default_to-1) * 86400);

			default:
				return date("Y-m-d", time() + 86400);
		}
	}
	
	function makeSQL($sql)
	{
		$db = JFactory::getDBO();
		
		$parser = new FSSParser();
		$parser->template = $sql;
		
		$user = JFactory::getUser();
		$parser->setVar('user_id', $user->id);
		$parser->setVar('username', $user->username);

		foreach ($this->filter as $filter)
		{
			if (isset($filter->type) && $filter->type == "daterange")
			{
				$replace = 1;
				
				$to = FSS_Input::getString("{$filter->name}_to", $this->getDateRangeTo($filter));
				$from = FSS_Input::getString("{$filter->name}_from", $this->getDateRangeFrom($filter));

				$find = "{" . $filter->name . "_from}";
				$replace = FSSJ3Helper::getEscaped($db,$from);
				//$sql = str_replace($find, $replace, $sql);
				$parser->SetVar($filter->name . "_from", $replace);

				$find = "{" . $filter->name . "_to}";
				$replace = FSSJ3Helper::getEscaped($db,$to);
				//$sql = str_replace($find, $replace, $sql);
				$parser->SetVar($filter->name . "_to", $replace);

				
				// need to filter the column by the date rand selected
			} elseif (isset($filter->type) && $filter->type == "date")
			{

				$replace = 1;
				$date = FSS_Input::getString("{$filter->name}", date("Y-m-d"));
				$find = "{" . $filter->name . "}";
				$replace = FSSJ3Helper::getEscaped($db,$date);
				$parser->SetVar($filter->name, $replace);

				// need to filter the column by the date rand selected
			} elseif (isset($filter->type) && $filter->type == "lookup") 
			{
				$value = FSS_Input::getString('filter_' . $filter->name, '');
				$find = "{" . $filter->name . "}";
				if ($value == "")
				{
					//$parser->SetVar($find, "1");
				} else {
					$replace = " {$filter->field} = '" . FSSJ3Helper::getEscaped($db,$value) . "' "; 
					//$sql = str_replace($find, $replace, $sql);
					
					$parser->SetVar($filter->name, $replace);
				}
			} else {
				$value = FSS_Input::getString('filter_' . $filter->name, $filter->default);
				
				if (!empty($filter->options) && array_key_exists($value, $filter->options))
				{
					$option = $filter->options[$value];
					$find = "{" . $filter->name . "}";
					$replace = $option->value;
				} else {
					$replace = "";
				}
				//$sql = str_replace($find, $replace, $sql);
				
				$parser->SetVar($filter->name, $replace);
				
			}
		}
		
		foreach ($this->group as $group)
		{
			$find = "{" . $group->name . "}";
			$find_disp = "{" . $group->name . "_disp}";
			if ($group->type == "dategroup")
			{
				$value = FSS_Input::getString('group_' . $group->name, 'day');

				$replace = "DATE({$group->field})";
				$replace_disp = "DATE({$group->field})";
				switch ($value)
				{
					case 'week':
						$replace = "week";
						$replace_disp = "CONCAT(YEAR({$group->field}), ' week ', WEEK({$group->field}))";
						break;
					case 'month':
						$replace = "month";
						$replace_disp = "CONCAT(MONTHNAME({$group->field}), ' ', YEAR({$group->field}))";
						break;
					case 'year':
						$replace = "year";
						$replace_disp = "YEAR({$group->field})";
						break;
				}
			}	
			//$sql = str_replace($find, $replace, $sql);
			//$sql = str_replace($find_disp, $replace_disp, $sql);
			
			$parser->SetVar($group->name, $replace);
			$parser->SetVar($group->name . "_disp", $replace_disp);
		}

		$final_sql = $parser->getTemplate();
		$final_sql = str_replace("#__", FSS_Helper::dbPrefix(), $final_sql);

		$this->vars =  $parser->vars;

		return $final_sql;	
	}
	
	function getFilters()
	{
		$html = array();
		$db = JFactory::getDBO();
		$document = JFactory::getDocument();
		
		foreach ($this->filter as $filter)
		{
			if (isset($filter->type) && $filter->type == "daterange")
			{
				FSS_Helper::StylesAndJS(array('calendar'));

				$to = FSS_Input::getString("{$filter->name}_to", $this->getDateRangeTo($filter));
				$from = FSS_Input::getString("{$filter->name}_from", $this->getDateRangeFrom($filter));

				$html[] = "<div class='control-group'>";
				$html[] = "<label class='control-label' for='{$filter->name}_from'>".JText::_('FSS_FROM')."</label>";
				$html[] = "<div class='controls'>";
				$html[] = "<input type='text' name='{$filter->name}_from' id='{$filter->name}_from' onclick=\"setSens_{$filter->name}('{$filter->name}_to', 'max');\">";
				$html[] = "</div>";
				$html[] = "</div>";
				
				$html[] = "<div class='control-group'>";
				$html[] = "<label class='control-label' for='{$filter->name}_to'>".JText::_('FSS_TILL')."</label>";
				$html[] = "<div class='controls'>";
				$html[] = "<input type='text' name='{$filter->name}_to' id='{$filter->name}_to' onclick=\"setSens_{$filter->name}('{$filter->name}_from', 'min');\">";
				$html[] = "</div>";
				$html[] = "</div>";
	
				FSS_Translate_Helper::CalenderLocale();

				$js = "
					var cal_{$filter->name};
					
					jQuery(document).ready( function () {
					    cal_{$filter->name} = new dhtmlXCalendarObject(['{$filter->name}_from', '{$filter->name}_to'], 'omega');
						cal_{$filter->name}.setDate('{$from}');
						cal_{$filter->name}.hideTime();
						// init values
						var t = new Date();
						jQuery('#{$filter->name}_from').val('{$from}');
						jQuery('#{$filter->name}_to').val('{$to}');
						
						cal_{$filter->name}.attachEvent('onClick', function(d) {
							document.report_params.submit();
						});

						cal_{$filter->name}.loadUserLanguage('" . FSS_Translate_Helper::CalenderLocaleCode()  . "');
					});
					
					
					function setSens_{$filter->name}(id, k) {
						// update range
						if (k == 'min') {
							cal_{$filter->name}.setSensitiveRange(jQuery('#'+id).val(), null);
						} else {
							cal_{$filter->name}.setSensitiveRange(null, jQuery('#'+id).val());
						}
					}
					";
					
				$document->addScriptDeclaration($js);
			
			} elseif (isset($filter->type) && $filter->type == "date")
			{
				FSS_Helper::StylesAndJS(array('calendar'));
				FSS_Translate_Helper::CalenderLocale();

				$to = FSS_Input::getString("{$filter->name}", $this->getDateRangeTo($filter));
				
				$html[] = "<div class='control-group'>";
				$html[] = "<label class='control-label' for='{$filter->name}'>".JText::_($filter->title)."</label>";
				$html[] = "<div class='controls'>";
				$html[] = "<input type='text' name='{$filter->name}' id='{$filter->name}' >";
				$html[] = "</div>";
				$html[] = "</div>";
				
				$js = "
					var cal_{$filter->name};
					
					jQuery(document).ready( function () {
					    cal_{$filter->name} = new dhtmlXCalendarObject(['{$filter->name}'], 'omega');
						cal_{$filter->name}.setDate('{$from}');
						cal_{$filter->name}.hideTime();
						// init values
						var t = new Date();
						jQuery('#{$filter->name}').val('{$to}');
						
						cal_{$filter->name}.attachEvent('onClick', function(d) {
							document.report_params.submit();
						});
						cal_{$filter->name}.loadUserLanguage('" . FSS_Translate_Helper::CalenderLocaleCode()  . "');

					});
					";
				
				$document->addScriptDeclaration($js);
				
			} else if ($filter->type == "lookup")
			{
				
				// need to lookup the values from the db
				$qry = "SELECT {$filter->key} as `key`, {$filter->display} as display";

				if (!empty($filter->translate))
					$qry .= ", translation ";

				$qry .= " FROM {$filter->table}";
				if (isset($filter->published) && $filter->published)
					$qry .= " WHERE published = 1 ";
				$qry .= " GROUP BY {$filter->order}";
				
				$db->setQuery($qry);
				
				$data = $db->loadObjectList();

				// translate any lookups for the display field
				if (!empty($filter->translate))
				{
					FSS_Translate_Helper::Tr($data);

					$display = $filter->display;

					foreach ($data as &$item)
					{
						if (isset($item->$display))
							$item->display = $item->$display;
					}
				}

				$values = array();
				$values[] = JHTML::_('select.option', '', JText::_($filter->header), 'key', 'display');
				$values = array_merge($values, $data);
				$value = FSS_Input::getString('filter_' . $filter->name, isset($filter->default) ? $filter->default : '');
				
				
				$html[] = "<div class='control-group'>";
				$html[] = "<label class='control-label'>".JText::_($filter->title)."</label>";
				$html[] = "<div class='controls'>";
				$html[] = JHTML::_('select.genericlist',  $values, 'filter_' . $filter->name, ' onchange="document.report_params.submit( );"', 'key', 'display', $value);	
				$html[] = "</div>";
				$html[] = "</div>";
			
				
			} else if ($filter->type == "datepresets")
			{
				$value = FSS_Input::getString('filter_' . $filter->name, isset($filter->default) ? $filter->default : '');
			
				$html[] = "<div class='control-group'>";
				$html[] = "<label class='control-label'>".JText::_($filter->title)."</label>";
				$html[] = "<div class='controls'>";		
				$html[] = JHTML::_('select.genericlist',  $filter->select, 'filter_' . $filter->name, ' fsjfield="'.$filter->title.'" onchange="fsj_datepreset(this);"', 'key', 'display', $value);	
				$html[] = "</div>";
				$html[] = "</div>";
			} else {
				$value = FSS_Input::getString('filter_' . $filter->name, isset($filter->default) ? $filter->default : '');

				$html[] = "<div class='control-group'>";
				$html[] = "<label class='control-label'>".JText::_($filter->title)."</label>";
				$html[] = "<div class='controls'>";		
				$html[] = JHTML::_('select.genericlist',  $filter->select, 'filter_' . $filter->name, ' onchange="document.report_params.submit( );"', 'key', 'display', $value);				
				$html[] = "</div>";
				$html[] = "</div>";
			}
			
		}	
		
		foreach ($this->group as $group)
		{
			$html[] = "<div class='control-group'>";
			$html[] = "<label class='control-label'>".JText::_($group->title)."</label>";
			$html[] = "<div class='controls'>";		

			//$html[] = "<div style='float:left;padding:3px;height:60px;'>";
			//if (isset($group->title))
			//	$html[] = "<div style='margin-top: 5px;margin-bottom: 6px;margin-left:3px;'><span class='label'>".$group->title . "</span></div>";
			
			if ($group->type == "dategroup")
			{
				$value = FSS_Input::getString('group_' . $group->name, 'day');
				$html[] = JHTML::_('select.genericlist',  $group->select, 'group_' . $group->name, ' onchange="document.report_params.submit( );"', 'key', 'display', $value);	
			}	
			
			$html[] = "</div>";
			$html[] = "</div>";
		}	
		return implode($html);
	}
	
	function listFilterValues()
	{
		$html = array();
		$db = JFactory::getDBO();
		$document = JFactory::getDocument();
		
		$html[] = "<dl class='dl-horizontal margin-none'>";
		foreach ($this->filter as $filter)
		{
			if (isset($filter->type) && $filter->type == "daterange")
			{
				FSS_Helper::StylesAndJS(array('calendar'));
			
				$to = FSS_Input::getString("{$filter->name}_to", $this->getDateRangeTo($filter));
				$from = FSS_Input::getString("{$filter->name}_from", $this->getDateRangeFrom($filter));

				$html[] = "<dt>".JText::_('FSS_FROM')."</dt>";
				$html[] = "<dd>".$from."</dd>";
				$html[] = "<dt>".JText::_('FSS_TILL')."</dt>";
				$html[] = "<dd>".$to."</dd>";
						
			} else if ($filter->type == "lookup")
			{
				
				// need to lookup the values from the db
				$qry = "SELECT {$filter->key} as `key`, {$filter->display} as display FROM {$filter->table}";
				if (isset($filter->published) && $filter->published)
					$qry .= " WHERE published = 1 ";
				$qry .= " GROUP BY {$filter->order}";
				
				$db->setQuery($qry);
				
				$values = array();
				$values[] = JHTML::_('select.option', '', JText::_($filter->header), 'key', 'display');
				$values = array_merge($values, $db->loadObjectList());
				$value = FSS_Input::getString('filter_' . $filter->name, isset($filter->default) ? $filter->default : '');

				foreach ($values as $temp)
				{
					if ($temp->key == $value && $value != "")
					{
						$value = $temp->display;
						break;	
					}	
				}

				if ($value == "")
					$value = "-";
				
				$html[] = "<dt>".JText::_($filter->title)."</dt>";
				$html[] = "<dd>".$value."&nbsp;</dd>";
		
			} else if ($filter->type == "datepresets")
			{
				
			} else {
				$value = FSS_Input::getString('filter_' . $filter->name, isset($filter->default) ? $filter->default : '');

				if (isset($filter->options[$value]))
					$value = $filter->options[$value]->display;
				
				$html[] = "<dt>".JText::_($filter->title)."</dt>";
				$html[] = "<dd>".$value."&nbsp;</dd>";
			}
			
		}	

		$html[] = "</dl>";
		return implode($html);
	}
}

class FSS_Report_Helper
{
	
	static function O2A(&$obj, $key)
	{
		if (!is_array($obj->$key))
		{
			$t = $obj->$key;
			$obj->$key = array();
			array_push($obj->$key,$t);	
		}
	}

	static function XMLToClass($xml, $class = null)
	{
		if ($class)
		{
			$obj = new $class();	
		} else {
			$obj = new stdClass();
		}
		
		if (!$xml)
			return (string)$xml;
		
		if (count($xml->attributes()) == 0
			&& count($xml->children()) == 0)
		{
			return (string)$xml;	
		}
		
		foreach ($xml->attributes() as $id => $value)
		{
			$value = trim((string)$value);
			if ($value == "") continue;
			
			$obj->$id = (string)$value;	
		}
		
		$text = (string)trim($xml);
		
		if ($text)
			$obj->text = $text;
		
		foreach ($xml->children() as $child)
		{
			$name = $child->getName();
			if (property_exists($obj, $name))
			{ 
				if (!is_array($obj->$name))
				{
					$t = $obj->$name;
					$obj->$name = array();
					array_push($obj->$name,$t);	
				}
				array_push($obj->$name,FSS_Report_Helper::XMLToClass($child));
			} else {
				$obj->$name = FSS_Report_Helper::XMLToClass($child);
			}
		}
		
		return $obj;
	}

}