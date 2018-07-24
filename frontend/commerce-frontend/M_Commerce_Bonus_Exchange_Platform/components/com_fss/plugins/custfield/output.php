<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class OutputPlugin extends FSSCustFieldPlugin
{
	var $name = "HTML Output";
	
	function DisplaySettings($params) // passed object with settings in
	{
		ob_start();
		include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'plugins'.DS.'custfield'.DS.'output'.DS.'form.php';
		$result = ob_get_clean();

		return $result;
	} 
	
	function SaveSettings() // return object with settings in
	{
		return FSS_Input::getHTML('plugin_html_output');
	}
	
	function Input($current, $params, $context, $id) // output the field for editing
	{
		return $params;
	}
	
	function Save($id, $params, $value = "")
	{
		return "";
	}
	
	function Display($value, $params, $context, $id) // output the field for display
	{
		return $params;
	}
		
	function CanEdit()
	{
		return false;	
	}
}