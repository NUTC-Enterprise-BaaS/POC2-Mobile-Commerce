<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('editor');

class JFormFieldFSSEditor extends JFormFieldEditor
{
	public function setup(SimpleXMLElement $element, $value, $group = null)
	{
		if (isset($element->default) && $value == "")
		{
			$value = (string)$element->default;
		}


		return parent::setup($element, $value, $group);	
	}
	
	public function getInput()
	{
		if ($this->element['code'])
		{
			$document = JFactory::getDocument();

			$document->addScript(JURI::root().'administrator/components/com_fss/assets/js/codemirror/codemirror.js'); 
			$document->addScript(JURI::root().'administrator/components/com_fss/assets/js/codemirror/init.js'); 
			$document->addScript(JURI::root().'administrator/components/com_fss/assets/js/codemirror/modes/css/css.js'); 
			$document->addScript(JURI::root().'administrator/components/com_fss/assets/js/codemirror/modes/javascript/javascript.js'); 
			$document->addScript(JURI::root().'administrator/components/com_fss/assets/js/codemirror/modes/xml/xml.js'); 
			$document->addScript(JURI::root().'administrator/components/com_fss/assets/js/codemirror/modes/php/php.js'); 
			$document->addScript(JURI::root().'administrator/components/com_fss/assets/js/codemirror/modes/htmlmixed/htmlmixed.js'); 
			$document->addScript(JURI::root().'administrator/components/com_fss/assets/js/codemirror/modes/sql/sql.js'); 
			$document->addScript(JURI::root().'administrator/components/com_fss/assets/js/codemirror/modes/clike/clike.js'); 
			$document->addScript(JURI::root().'administrator/components/com_fss/assets/js/codemirror/modes/smarty/smarty.js'); 
			$document->addScript(JURI::root().'administrator/components/com_fss/assets/js/codemirror/modes/smartymixed/smartymixed.js'); 
			$document->addStyleSheet(JURI::root().'administrator/components/com_fss/assets/css/codemirror/codemirror.css'); 
			
			return $this->CodeInput();
		} else {
			return parent::getInput();	
		}
	}
	
	function CodeInput()
	{
		$class = ' class="fss_codemirror_editor html" ';
		
		$columns = $this->element['cols'] ? ' cols="' . (int) $this->element['cols'] . '"' : '';
		$rows = $this->element['rows'] ? ' rows="' . (int) $this->element['rows'] . '"' : '';

		$codetype = "htmlmixed";
		if (isset($this->element['codetype']))
		$codetype = $this->element['codetype'];
		
		$codetype = " codetype='$codetype' ";

		$styles = "display:inline-block;";
		if (isset($this->element['clear']))
		$styles .= "clear: both;";
		
		if ($this->element['width'])
		$styles .= "width:".$this->element['width']."%;";
		else 
			$styles .= "width:60%;";
		
		return '<div style="'.$styles.'"><textarea name="' . $this->name . '" id="' . $this->id . '"' . $columns . $rows . $class . $codetype . '>'
			. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '</textarea></div>';		
	}
}
		 		  	  			  