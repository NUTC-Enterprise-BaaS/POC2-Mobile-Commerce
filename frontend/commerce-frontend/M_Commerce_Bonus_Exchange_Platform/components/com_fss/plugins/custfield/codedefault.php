<?php

class CodeDefaultPlugin extends FSSCustFieldPlugin
{
	var $name = "Default Vaule from php code";

	function DisplaySettings($params)
	{
		$params = unserialize($params);
		
		if (!is_array($params))
		{
			$params = array();
			$params['code'] = "";
		}
		
		$code_file = JPATH_ROOT.DS."components".DS."com_fsj_codeincl".DS.'plugins'.DS.'include'.DS.'include.code'.DS.'include.code.php';		
		if (!file_exists($code_file))
			return "This plugin only works when Includes: Code is installed";
		
		$output = "PHP Code for Default : <br /><textarea cols='60' rows='10' name='codedefault_code' value=''>{$params['code']}</textarea>";
		
		return $output;
	}
	
	function SaveSettings() // return object with settings in
	{
		$params = array();
		$params['code'] = JRequest::getVar('codedefault_code', '', 'post', 'string', JREQUEST_ALLOWRAW);
		return serialize($params);
	}

	function Input($current, $params, $context, $id) // output the field for editing
	{
		$params = unserialize($params);
		
		
		$code_file = JPATH_ROOT.DS."components".DS."com_fsj_codeincl".DS.'plugins'.DS.'include'.DS.'include.code'.DS.'include.code.php';		
		if ($current == "" || file_exists($code_file))
		{
			jimport('fsj_core.lib.plugin_handler');
			jimport('fsj_core.lib.settings');
			include_once($code_file);
			
			if (class_exists("FSJ_Plugin_Include_Code"))
			{
				$code = new FSJ_Plugin_Include_Code();
				$codeparams = new stdClass();
				$codeparams->params = array();
				$codeparams->values = array();
				$codeparams->content = htmlentities("<?php \n".$params['code']."\n?>\n");
	
				$current = $code->Replace($codeparams, "com_fss", null);
			}
		}
		
		
		return "<input name='custom_$id' value='$current'>";
	}
	
	function Save($id)
	{
		return JRequest::getVar("custom_$id");
	}

	function Display($value, $params, $context, $id) // output the field for display
	{
		return $value;
	}
	
	function CanEdit()
	{
		return true;	
	}
}