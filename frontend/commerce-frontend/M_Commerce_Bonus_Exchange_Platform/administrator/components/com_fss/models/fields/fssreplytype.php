<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

class JFormFieldFSSReplyType extends JFormFieldList
{
	protected $type = 'FSSReplyType';
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function __get($name)
	{
		$res = parent::__get($name);
		
		if ($res)
		return $res;
		
		return $this->$name;		
	}
	
	public function getInput()
	{
		$options = $this->getReturnViewOptions($this->value);
		
		$output = array();
		$output[] = '<select name="' . $this->name . '" id="' . $this->id . '">';
		$output[] = $options;
		$output[] = "</select>";
		return implode($output);
	}
	
	function AdminDisplay($value, $name, $item)
	{
		return $value;
	}
	
	function getReturnViewOptions($current)
	{
		$language = JFactory::getLanguage();
		$language->load("com_fss", JPATH_SITE);

		$statuss = SupportHelper::getStatuss();
		
		$temp = new stdClass();
		$temp->id = "closed";
		$temp->title = JText::_("Closed");
		$statuss[] = $temp;
		
		$temp = new stdClass();
		$temp->id = "allopen";
		$temp->title = JText::_("ALL_OPEN");
		$statuss[] = $temp;
		
		$temp = new stdClass();
		$temp->id = "all";
		$temp->title = JText::_("All");
		$statuss[] = $temp;
		
		$output[] = "<option value=''>".JText::_('SHOW_CURRENT_TICKET')."</option>";
		
		$output[] = "<optgroup label='".JText::_('SHOW_TICKET_LIST').":'>";
		
		$selected = "";
		if ($current == "list_current") $selected = "selected";
		$output[] = "<option value='list_current' {$selected}>".JText::_('LIST') . ": ".JText::_('CURRENT_TICKETS_STATUS')."</option>";
		
		foreach ($statuss as $status)
		{
			$selected = "";
			if ($current == "list_" . $status->id) $selected = "selected";
			$output[] = "<option value='list_" . $status->id . "' {$selected}>".JText::_('LIST') . ": " . $status->title . "</option>";
		}
		$output[] = "</optgroup>";
		
		
		$output[] = "<optgroup label='".JText::_('SHOW_NEWEST_TICKET').":'>";
		
		$selected = "";
		if ($current == "new_current") $selected = "selected";
		$output[] = "<option value='new_current' {$selected}>".JText::_('NEWEST') . ": ".JText::_('CURRENT_TICKETS_STATUS')."</option>";
		
		foreach ($statuss as $status)
		{
			$selected = "";
			if ($current == "new_" . $status->id) $selected = "selected";
			$output[] = "<option value='new_" . $status->id . "' {$selected}>".JText::_('NEWEST') . ": " . $status->title . "</option>";
		}
		$output[] = "</optgroup>";
		
		$output[] = "<optgroup label='".JText::_('SHOW_OLDEST_TICKET').":'>";
		
		$selected = "";
		if ($current == "old_current") $selected = "selected";
		$output[] = "<option value='old_current' {$selected}>".JText::_('OLDEST') . ": ".JText::_('CURRENT_TICKETS_STATUS')."</option>";
		foreach ($statuss as $status)
		{
			$selected = "";
			if ($current == "old_" . $status->id) $selected = "selected";
			$output[] = "<option value='old_" . $status->id . "' {$selected}>".JText::_('OLDEST') . ": " . $status->title . "</option>";
		}
		$output[] = "</optgroup>";
		
		return implode($output);
	}	
}
