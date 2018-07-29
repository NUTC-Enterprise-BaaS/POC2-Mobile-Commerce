<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class CheckboxesPlugin extends FSSCustFieldPlugin
{
	var $name = "Multiple Checkboxes Plugin";
	
	var $default_params = array(
		'entries' => array()
		);

	function DisplaySettings($params)
	{
		$params = $this->parseParams($params);

		ob_start();
		include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'plugins'.DS.'custfield'.DS.'checkboxes'.DS.'form.php';
		$result = ob_get_clean();

		return $result;
	}

	function SaveSettings()
	{
		$data = FSS_Input::getString('checkboxes_entries');
		
		$params = new stdClass();
		$params->entries = array();
		
		$lines = explode("\n", $data);
		foreach($lines as $line)
		{
			$line = trim($line);
			if (!$line || $line == "") continue;
			$params->entries[] = $line;	
		}
		
		return json_encode($params);		
	}

	function Input($current, $params, $context, $id)
	{
		$params = $this->parseParams($params);
		
		$output = array();
		$checked = array();
				
		$posted = FSS_Input::getInt("custom_{$id}_count");
		if ($posted)
		{
			for ($i = 1 ; $i <= $posted ; $i++)
			{
				$val = FSS_Input::getString("custom_{$id}_{$i}");	
				if ($val)
				{
					$checked[$val] = $val;	
				}
			}
		}

		$i = 0;
		foreach ($params->entries as $entry)
		{
			$i++;
			$output[] = '<label class="checkbox-inline">';
			$output[] = '<input type="checkbox" id="custom_$id_' . $i . '" name="custom_' . $id . '_' . $i . '" value="' . FSS_Helper::encode($entry) . '"  ';
			if (array_key_exists($entry, $checked))
				$output[] = " checked='checked' ";
			$output[] = '>';
			$output[] = $entry;
			$output[] = '</label>';
		}
		
		$output[] = "<input type='hidden' name='custom_{$id}_count' value='{$i}' />";
		
		return implode("\n", $output);
	}
	
	function Save($id, $params, $value = "")
	{
		$checked = array();
		
		$posted = FSS_Input::getInt("custom_{$id}_count");
		if ($posted)
		{
			for ($i = 1 ; $i <= $posted ; $i++)
			{
				$val = FSS_Input::getString("custom_{$id}_{$i}");	
				if ($val)
				{
					$checked[] = $val;	
				}
			}
		}

		return json_encode($checked);
	}

	function Display($value, $params, $context, $id) // output the field for display
	{
		if (is_array($value))
		{
			print_p(dumpStack());
			print_p($value);
			exit;
		}

		$data = @json_decode($value, true);
		
		if (!$data)
			return "";
		
		return implode(", ", $data);
	}
	
	function CanEdit()
	{
		return true;	
	}
}