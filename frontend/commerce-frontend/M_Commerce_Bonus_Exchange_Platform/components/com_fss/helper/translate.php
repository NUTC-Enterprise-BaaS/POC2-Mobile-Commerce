<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class FSS_Translate_Helper
{
	static $language = "";
	
	static function setLanguage($lang)
	{
		$old = self::$language;
		self::$language = str_replace("-","",$lang);	
		
		return $old;
	}
	
	static function TrF($field, $current, $trdata, $curlang = "")
	{
		$data = json_decode($trdata, true);
		//print_p($data);
		
		if (!is_array($data))
			return $current;
		
		if (!array_key_exists($field, $data))
			return $current;
		
		if ($curlang == "") $curlang = self::$language;
		if ($curlang == "")	$curlang = JFactory::getLanguage()->getTag();
		$curlang = str_replace("-","",$curlang);
			
		if (!array_key_exists($curlang, $data[$field]))
			return $current;
		
		return $data[$field][$curlang];	
	}
	
	static function Tr(&$data, $curlang = "")
	{
		/*if (empty($data))
		{
			echo "Tr with no data<Br/>";
			echo dumpStack();	
		}*/
		
		foreach ($data as &$item)
		{
			if (is_array($item)) self::TrA($item, $curlang);		
			if (is_object($item)) self::TrO($item, $curlang);	
		}
		return;	
	}
	
	static function TrSingle(&$data, $curlang = "")
	{
		if (is_array($data)) self::TrA($data, $curlang);		
		if (is_object($data)) self::TrO($data, $curlang);	
		return;	
	}
	
	static function TrA(&$data, $curlang = "")
	{
		// translate all fields in data that are found in the translation field
		if ($curlang == "") $curlang = self::$language;
		if ($curlang == "")	$curlang = JFactory::getLanguage()->getTag();
		$curlang = str_replace("-","",$curlang);
		
		if (!array_key_exists("translation", $data))
			return;
				
		$translation = json_decode($data['translation'], true);
		if (!$translation)
			return;
		
		// if we have old data, reset what we have
		if (isset($data['orig_fields']))
		{
			foreach ($data['orig_fields'] as $key => $value)
			{
				$data[$key] = $value;	
			}	
		}
		
		$data['orig_fields'] = array();
		foreach ($translation as $field => $langs)
		{
			if (array_key_exists($field, $data)) $data['orig_fields'][$field] = $data[$field];
			foreach ($langs as $lang => $text)
			{
				if ($lang == $curlang && $text)
					$data[$field] = $text;
			}
		}
	}	
	
	static function TrO(&$data, $curlang = "")
	{
		// translate all fields in data that are found in the translation field
		if ($curlang == "") $curlang = self::$language;
		if ($curlang == "")	$curlang = JFactory::getLanguage()->getTag();
		$curlang = str_replace("-","",$curlang);
		
		if (!property_exists($data, "translation"))
			return;
		
		$translation = json_decode($data->translation, true);
		if (!$translation)
			return;
		
		// if we have old data, reset what we have
		if (isset($data->orig_fields))
		{
			foreach ($data->orig_fields as $key => $value)
			{
				$data->$key = $value;	
			}	
		}	
		
		$data->orig_fields = array();
		
		foreach ($translation as $field => $langs)
		{
			if (!empty($data->$field))
			{
				$data->orig_fields[$field] = $data->$field;
				foreach ($langs as $lang => $text)
				{
					if ($lang == $curlang && $text)
					$data->$field = $text;
				}
			}
		}
	}
	
	static function CalenderLocale() 
	{
		$js = '
			dhtmlXCalendarObject.prototype.langData["' . self::CalenderLocaleCode() . '"] = {
			dateformat: \'%d.%m.%Y\',
			monthesFNames: ["' . JText::_('JANUARY') . '","' . JText::_('FEBRUARY') . '","' . JText::_('MARCH') . '","' . JText::_('APRIL') . '",
							"' . JText::_('MAY') . '","' . JText::_('JUNE') . '","' . JText::_('JULY') . '","' . JText::_('AUGUST') . '",
							"' . JText::_('SEPTEMBER') . '","' . JText::_('OCTOBER') . '","' . JText::_('NOVEMBER') . '","' . JText::_('DECEMBER') . '"],
			monthesSNames: ["' . JText::_('JANUARY_SHORT') . '","' . JText::_('FEBRUARY_SHORT') . '","' . JText::_('MARCH_SHORT') . '","' . JText::_('APRIL_SHORT') . '",
							"' . JText::_('MAY_SHORT') . '","' . JText::_('JUNE_SHORT') . '","' . JText::_('JULY_SHORT') . '","' . JText::_('AUGUST_SHORT') . '",
							"' . JText::_('SEPTEMBER_SHORT') . '","' . JText::_('OCTOBER_SHORT') . '","' . JText::_('NOVEMBER_SHORT') . '","' . JText::_('DECEMBER_SHORT') . '"],
			daysFNames:	   ["' . JText::_('SUNDAY') . '","' . JText::_('MONDAY') . '","' . JText::_('TUESDAY') . '","' . JText::_('WEDNESDAY') . '",
							"' . JText::_('THURSDAY') . '","' . JText::_('FRIDAY') . '","' . JText::_('SATURDAY') . '"],
			daysSNames:    ["' . JText::_('SUN') . '","' . JText::_('MON') . '","' . JText::_('TUE') . '","' . JText::_('WED') . '",
							"' . JText::_('THU') . '","' . JText::_('FRI') . '","' . JText::_('SAT') . '"],
			weekstart: 1,
			weekname: "W" 
			};
			var fss_calendar_locale = "' . self::CalenderLocaleCode() . '"; 
		';
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($js);
	}
	
	static function CalenderLocaleCode() 
	{
		$curlang = str_replace("-","",JFactory::getLanguage()->getTag());

		return $curlang;
	}	
}