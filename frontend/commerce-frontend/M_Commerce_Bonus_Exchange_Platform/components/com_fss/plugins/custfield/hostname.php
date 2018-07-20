<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class HostNamePlugin extends FSSCustFieldPlugin
{
	var $name = "Hostname Log";

	function Input($current, $params, $context, $id) // output the field for editing
	{
		$params = unserialize($params);
		
		if (empty($current) || $current == "")
			$current = gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
		$output = array();
		$output[] = $current;
		$output[] = "<input name='custom_$id' type='hidden' value='$current'>";

		return implode($output);
	}
	
	// called to save the value from the submitted form
	// $id is the id of the custom field. It is reccomended to use the name of custom_$id when outputting the field
	function Save($id, $params, $value = "")
	{
		return FSS_Input::getString("custom_$id");
	}
	
	// display the value of the field
	// $value is the value of the field,
	// $params is the fields parameters
	// $context is the ????
	// $id is the id of the custom field. It is reccomended to use the name of custom_$id when outputting the field
	function Display($value, $params, $context, $id) // output the field for display
	{
		return "$value";
	}
	
	// if the field can be edited within the ticket admin interface (using the popup dialog), then this should be set to true
	// if its a read only field, return false
	function CanEdit()
	{
		return true;	
	}
}