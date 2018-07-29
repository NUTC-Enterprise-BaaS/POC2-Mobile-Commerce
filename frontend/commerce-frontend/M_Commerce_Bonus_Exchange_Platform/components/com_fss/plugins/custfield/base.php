<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

// the class name (BasePlugin) MUST match the name of the php file (ie, this class must be in base.php)
class BasePlugin extends FSSCustFieldPlugin
{
	var $name = "Base Example Plugin";
	var $sources = array();

	function BasePlugin()
	{
		// Constructor. Only needed in specific cases.
	}
	
	// called when displaying the field within the custom field edit page
	// the $params var is a string that contains the settings for the custom field. It is the same
	// as the value returned from the SaveSettings. it will be blank if creating a new field
	// if you require more than one parameter with the custom field, then you will need to use the serialize
	// and unserialize like in this example, or use json_encode / json_decode to convert an object to/from a string
	function DisplaySettings($params)
	{
		$params = unserialize($params);
		
		if (!is_array($params))
		{
			$params = array();
			$params['prefix'] = "";
			$params['postfix'] = "";	
		}
		
		$output = "Prefix : <input name='sample_prefix' value='{$params['prefix']}'><br />";
		$output .= "Postfix : <input name='sample_postfix' value='{$params['postfix']}'>";
		
		return $output;
	}
	
	// called to save any parameters set up from within the custom field edit page
	// needs to return a string
	function SaveSettings() // return object with settings in
	{
		$params = array();
		$params['prefix'] = FSS_Input::getString('sample_prefix');
		$params['postfix'] = FSS_Input::getString('sample_postfix');
		return serialize($params);
	}
	
	// called when displaying the custom field on the create ticket screen
	// $current is the current value of the field
	// $params is the fields parameters
	// $context is the ????
	// $id is the id of the custom field. It is reccomended to use the name of custom_$id when outputting the field
	function Input($current, $params, $context, $id) // output the field for editing
	{
		$params = unserialize($params);
		
		$output = array();
		
		if ($params['prefix'])
			$output[] = "{$params['prefix']}:<br />";
		
		$output[] = "<input name='custom_$id' type='text' value='$current'>";
			
		if ($params['postfix'])
			$output[] = " ({$params['postfix']})";
		
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
		$params = unserialize($params);
		return "{$params['prefix']}: $value ({$params['postfix']})";
	}
		
	// if the field can be edited within the ticket admin interface (using the popup dialog), then this should be set to true
	// if its a read only field, return false
	function CanEdit()
	{
		return true;	
	}

	// a custom search function can be provided if needed.
	// its fairly complex, but the search string is passed in the $search parameter.
	// if the $peruser flag is set, you need to return an array of user ids that
	// match the search for this field.
	// if the $peruser flag is not set, you need to return a list of ticekt ids
	// that match the search for this field.
	function Search($params, $search, $peruser)
	{
		// 
	}
}
