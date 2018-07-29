<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('text');

class JFormFieldFSSChecklist extends JFormFieldText
{
	protected $type = 'FSSChecklist';
	
	static $init = false;
	function __construct()
	{
		if (!JFormFieldFSSChecklist::$init)
		{
			FSS_Helper::StylesAndJS(array(), array("administrator/components/com_fss/assets/css/field.fsschecklist.css"), array("administrator/components/com_fss/assets/js/field.fsschecklist.js"));
			JFormFieldfssChecklist::$init = true; 
		}
		//parent::__construct();
	
	}
	
	protected function getData()
	{
		if ($this->element->sql)
		{
			$db = JFactory::GetDBO();
			$db->setQuery($this->element->sql);
			$options = $db->loadObjectList();
			return $options;			
		}
		
		// Initialize variables.
		$options = array();

		foreach ($this->element->children() as $option)
		{

			// Only add <option /> elements.
			if ($option->getName() != 'option')
			{
				continue;
			}
			
			$item = new stdClass();
			$item->id = (string) $option['value'];
			$item->display = JText::_(trim((string) $option));

			$options[] = $item;
		}

		reset($options);

		return $options;
	}
	
	public function getInput()
	{
		$data = $this->getData();		
		
		echo "<div class='fss_fsschecklist_cont' id='{$this->id}-cont'>";
		//echo "Value : {$this->value}<br>";
		
		if (!isset($this->element['hide_buttons']))
		{		
			echo "<button class='fss_checklist_checkall btn' field='{$this->id}'>" . JText::_('CHECK_ALL') . "</button>&nbsp;";
			echo "<button class='fss_checklist_uncheckall btn' field='{$this->id}'>" . JText::_('UNCHECK_ALL') . "</button><br />";
		}
		
		if (isset($this->element['show']))
		{
			$show = true;
			
			list($group, $setting, $type, $value) = explode(";", $this->element['show']);
			
			$current = $this->form->getValue($setting, $group);
			
			if ($type == "not" || $type == "unchecked")
			{
				if ($current == $value)
					$show = false;
			} else {
				if ($current != $value)
					$show = false;
			}
			
			$js = "
			jQuery(document).ready(function () {
				jQuery('#jform_{$group}_{$setting}').change( function (ev) {
					var value = jQuery(this).val();
				";
				
			if ($type == "not")
			{
				$js .= " if (value != '{$value}') { ";
			} else {
				$js .= " if (value == '{$value}') { ";
			}
			
			$js .= "
						fss_checklist_showhide(true, '{$this->id}');
					} else {
						fss_checklist_showhide(false, '{$this->id}');
					}
				}); ";
			
			if (!$show)
				$js .= "fss_checklist_showhide(false, '{$this->id}');";					
				
			$js .= "
			});			
			";
			
			$document = JFactory::getDocument();
			$document->addScriptDeclaration($js);
		}
		
		$class = "";
		if (isset($this->element->class))
			$class = (string)$this->element->class;

		echo "<div class='fss_fsschecklist $class'>";
		
		echo parent::getInput();

		//echo "Value : $this->value<br>";
		
		if ($this->value)
		{
			$set = explode(";", $this->value);
		} else {
			$set = array();
		}
		
		/*echo "<select name='{$this->name}' multiple id='{$this->id}'>";
		foreach ($data as $item)
		{
			echo "<option value='{$item->id}' ";
			if (in_array($item->id, $set))
				echo " selected='selected' ";
			echo ">{$item->display}</option>";
		}
		echo "</select>";*/
		
		foreach ($data as $item)
		{
			echo "<div class='item'>";
			echo "<input type='checkbox' class='fss_checklist_checkbox' field='{$this->id}' value='{$item->id}'";
			if (in_array($item->id, $set))
				echo " checked='checked' ";
			echo ">{$item->display}";
			echo "</div>";
		}
		echo "</div>";
		echo "</div>";
	}
	
	function AdminDisplay($value, $name, $item)
	{
		return $value;	
	}
	
	function doSave($field, &$data)
	{
		print_p($_POST);
		exit;
	}
	
}
