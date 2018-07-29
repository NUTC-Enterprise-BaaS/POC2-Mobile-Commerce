<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_source.php');

class SourcePlugin extends FSSCustFieldPlugin
{
	var $name = "Ticket Source Plugin";

	// display the value of the field
	// $value is the value of the field,
	// $params is the fields parameters
	// $context is the ????
	// $id is the id of the custom field. It is reccomended to use the name of custom_$id when outputting the field
	function Display($value, $params, $context, $id) // output the field for display
	{
		if (is_array($context['ticket']))
		{
			$source = $context['ticket']['source'];	
		} else {
			$source = $context['ticket']->source;
		}
		
		return SupportSource::get_source_title($source);
	}
		
	// if the field can be edited within the ticket admin interface (using the popup dialog), then this should be set to true
	// if its a read only field, return false
	function CanEdit()
	{
		return false;	
	}
}