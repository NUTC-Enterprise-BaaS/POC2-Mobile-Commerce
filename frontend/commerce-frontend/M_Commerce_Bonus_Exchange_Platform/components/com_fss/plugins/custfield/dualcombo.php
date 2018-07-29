<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'parser.php' );

class DualComboPlugin extends FSSCustFieldPlugin
{

	static $values = array();

	var $name = "Dual Combo Box";
	var $force_display = 1;
	
	var $default_params = array(
		'items' => '',
		'separator' => '',
		'header1' => 'Please Select',
		'header2' => 'Please Select',
		'boxsep' => '<br />',
		'hidegroup' => 0
		);

	function DisplaySettings($params)
	{
		$params = $this->parseParams($params);
		ob_start();
		include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'plugins'.DS.'custfield'.DS.'dualcombo'.DS.'form.php';
		$result = ob_get_clean();

		return $result;
	}

	function SaveSettings() // return object with settings in
	{
		$p =  array ( 
			'items'		=> FSS_Input::getString('dualcombo_items'),
			'separator'		=> $_POST['dualcombo_separator'], //JRequest::getVar('dualcombo_separator'),
			'header1'		=> FSS_Input::getString('dualcombo_header1'),
			'header2'		=> FSS_Input::getString('dualcombo_header2'),
			'hidegroup'		=> FSS_Input::getInt('dualcombo_hidegroup'),
			'boxsep'		=> FSS_Input::getHTML('dualcombo_boxsep')
		);

		return $this->encodeParams( $p );
	}

	function Input($current, $params, $context, $id) // output the field for editing
	{
		//if (FSSJ3Helper::IsJ3()) JHtml::_('formbehavior.chosen', 'select');
		$document = JFactory::getDocument();
		$document->addScript(JURI::root().'components/com_fss/plugins/custfield/dualcombo/dualcombo.js'); 
		
		$params = $this->parseParams($params);

		$source = explode("\n", $params->items);


		$items = array();

		$group = "";

		$option1 = array();
		$option2 = array();

		if ($params->header1) $option1[] = JHTML::_('select.option', '', $params->header1, 'id', 'title');
		if ($params->header2) $option2[] = JHTML::_('select.option', '', $params->header2, 'id', 'title');

		foreach ($source as $item)
		{
			$item = trim($item);
			if (!$item) continue;

			if (substr($item,0,1) == "+")
			{
				$group = substr($item,1);
				$option1[] = JHTML::_('select.option', $group, $group, 'id', 'title' );
			} else {
				$items[$group][] = $item;
				$option2[] = JHTML::_('select.option', $group . "=>" . $item, $item, 'id', 'title');
			}
		}

		$spacer = $params->boxsep;

		$style = "";
		if (stripos($spacer, "<br") !== false) $style .= "margin-top: 6px;";

		$select1 = JHTML::_('select.genericlist', $option1, "custom_dsgroup_" . $id, ' class="fsj_dual_combo_group" ', 'id', 'title', $current );
		$select2 = JHTML::_('select.genericlist', $option2, "custom_" . $id, 'style="'.$style.'"', 'id', 'title', $current );

		return $select1 . $spacer . $select2;
	}

	function CanEdit()
	{
		return true;	
	}

	function Display($value, $params, $context, $id) // output the field for display
	{
		$params = $this->parseParams($params);
		if ($params->hidegroup)
		{
			list($group, $value) = explode("=>", $value);
			return $value;
		}
		if ($params->separator == "") $params->separator = " ";
		return str_replace("=>", $params->separator, $value);
	}
	
	function Save($id, $params, $value = "")
	{
		$values = FSS_Input::getString("custom_$id");
		return $values;
	}
}