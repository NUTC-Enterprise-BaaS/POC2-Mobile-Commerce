<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'parser.php' );

class SQLComboPlugin extends FSSCustFieldPlugin
{

	static $values = array();

	var $name = "SQL Combo Box Query";
	var $force_display = 1;
	
	var $default_params = array(
		'command' => '',
		'display' => '',
		'field' => '',
		'group' => '',
		'multi' => 0,
		'class_select' => 'input-xlarge',
		'class_label' => 'label label-info'
		);

	function DisplaySettings($params)
	{
		$params = $this->parseParams($params);
		ob_start();
		include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'plugins'.DS.'custfield'.DS.'sqlcombo'.DS.'form.php';
		$result = ob_get_clean();

		return $result;
	}

	function SaveSettings() // return object with settings in
	{
		return $this->encodeParams( array ( 
			'command'		=> FSS_Input::getString('sql_command'),
			'display'		=> FSS_Input::getString('sql_display'),
			'field'		=> FSS_Input::getString('sql_field'),
			'group'		=> FSS_Input::getString('sql_group'),
			'multi'		=> FSS_Input::getInt('sql_multi'),
			'class_select'		=> FSS_Input::getString('sql_class_select'),
			'class_label'		=> FSS_Input::getString('sql_class_label')
		));
	}

	function getData($id, $params, $context)
	{
		if (empty(self::$values[$id]))
		{
			$sql = $this->parseText($params->command, $context);
			$db = JFactory::getDBO();
			$db->setQuery($sql);
			try {
				self::$values[$id] = $db->loadAssocList();
			} catch (exception $e)
			{
				self::$values[$id] = "<div class='alert alert-error fss_sql_custom_error_message' style='margin-bottom: 0'>" . $e->getMessage() . "</div>";
			}
		}

		return self::$values[$id];
	}

	function Input($current, $params, $context, $id) // output the field for editing
	{
		//if (FSSJ3Helper::IsJ3()) JHtml::_('formbehavior.chosen', 'select');
		
		$params = $this->parseParams($params);
		$items = $this->getData($id, $params, $context);
		if (is_string($items))
			return $items;

		$option = array();

		$group = "";

		$open = false;
		foreach ($items as $item)
		{
			if ($params->group && $item[$params->group] != $group)
			{
				if ($open) $option[] = JHTML::_('select.optgroup', '');
				$option[] = JHTML::_('select.optgroup', $item[$params->group], 'value', 'text');
				$group = $item[$params->group];
				
				$open = true;
			}
			$option[] = JHTML::_('select.option', $item[$params->display], $item[$params->field], 'text', 'value' );
		}

		if ($open) $option[] = JHTML::_('select.optgroup', '');

		$details = " class='" . $params->class_select . "' ";
		if ($params->multi)
			$details .= " multiple style='height: 200px;' ";

		$current = json_decode($current);

		$spacer = "<div class='sqlcombo_spacer'></div>";
		$spacer .= "<style> .modal-body .sqlcombo_spacer { height: 200px; } </style>";

		return JHTML::_('select.genericlist', $option, "custom_" . $id . "[]", $details, 'value', 'text', $current ) . $spacer;
	}

	function parseText($text, $context)
	{
		$parser = new FSSParser();

		if (is_array($context))
		{
			if (isset($context['ticketid'])) $parser->setVar('ticketid', (int)$context['ticketid']);
			if (isset($context['userid'])) $parser->setVar('userid', (int)$context['userid']);

			if (isset($context['ticketid']) && $context['ticketid'] > 0)
			{
				foreach ($context['ticket'] as $var => $value)
				{
					if (!is_array($value))
					$parser->setVar($var, $value);
				}

				if (isset($context['ticket']->custom))
				{
					foreach ($context['ticket']->custom as $field_id => $value)
					{
						if (is_array($value)) $value = $value['value'];
						$parser->setVar("custom_{$field_id}", $value);

						if (array_key_exists($field_id, $context['ticket']->customfields))
						{
							$cf = $context['ticket']->customfields[$field_id];
							$parser->setVar("custom_{$cf['alias']}", $value);
						}
					}
				}
			}
		}

		$parser->loadText($text);
		return $parser->getTemplate();
	}

	function CanEdit()
	{
		return true;	
	}

	function Display($value, $params, $context, $id) // output the field for display
	{
		$params = $this->parseParams($params);
		if (is_array($value)) $value = $value['value'];

		$data = json_decode($value, true);
		if (!is_array($data)) $data = array();

		$values = $this->getData($id, $params, $context);

		$display = array();
		foreach ($data as $id)
		{
			foreach ($values as $value)
			{
				if ($value[$params->field] == $id)
				{
					$display[] = $value[$params->display];
					break;
				}
			}
		}

		if (isset($context['inlist']))
		{
			$result = implode(", ", $display);		
		} else {
			$result = "<div style='line-height: 1.8em'><span class='" . $params->class_label . "'>" . implode("</span>&nbsp;<span class='" . $params->class_label . "'>", $display) . "</span></div>";		
		}

		return $result;
	}
	
	function Save($id, $params, $value = "")
	{
		$params = $this->parseParams($params);
		$values = FSS_Input::getArray("custom_$id");
		return json_encode($values);
	}
}				 		     	 		