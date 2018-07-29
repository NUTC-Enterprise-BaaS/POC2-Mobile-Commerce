<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'parser.php' );

class SQLPlugin extends FSSCustFieldPlugin
{
	var $name = "SQL Query";
	var $force_display = 1;
	
	var $default_params = array(
		'command' => '',
		'display' => '',
		);

	function DisplaySettings($params)
	{
		$params = $this->parseParams($params);

		ob_start();
		include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'plugins'.DS.'custfield'.DS.'sql'.DS.'form.php';
		$result = ob_get_clean();

		return $result;
	}

	function SaveSettings() // return object with settings in
	{
		return $this->encodeParams( array ( 
			'command'		=> FSS_Input::getString('sql_command'),
			'display'		=> FSS_Input::GetHTML('sql_display')
			));
	}

	function Input($current, $params, $context, $id) // output the field for editing
	{
		$result = $this->Display($current, $params, $context, $id);
		if (strpos($result, 'fss_sql_custom_error_message') === false)
			return "<span class='input-large uneditable-input'>".$result."</span>";

		return $result;
	}

	function CanEdit()
	{
		return false;	
	}

	function Display($value, $params, $context, $id) // output the field for display
	{

		if (empty($context['ticketid'])) return "";
		if (empty($context['userid'])) $context['userid'] = 0;

		$params = $this->parseParams($params);
		$parser = new FSSParser();

		$parser->setVar('ticketid', (int)$context['ticketid']);
		$parser->setVar('userid', (int)$context['userid']);

		if ($context['ticketid'] > 0)
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

		if (trim($params->command) != "")
		{
			$parser->loadText($params->command);
			$sql = $parser->getTemplate();

			$db = JFactory::getDBO();
			$db->setQuery($sql);

			try {
				$data = $db->loadAssoc();
			} catch (exception $e)
			{
				return "<div class='alert alert-error fss_sql_custom_error_message' style='margin-bottom: 0'>" . $e->getMessage() . "</div>";
			}

			if (is_array($data))
			{
				foreach($data as $var => $value)
				{
					$parser->setVar($var, $value);
				}
			}
		}

		$parser->loadText($params->display);
		return $parser->getTemplate();
	}

	function displayTemplate($user, $tmpl, $ishtml = true, $plugins = null)
	{
		if ($plugins && !is_array($plugins))
		$plugins = array($plugins);

		if (!$plugins)
		$plugins = $this->allPlugins();

		foreach ($plugins as $plugin)
		{
			$source = $this->getSource($plugin);
			$data = $source->getValues($user);
			foreach ($data as $key => $value)
			{
				$tmpl = str_replace("{" . $plugin . "." . $key . "}", $value, $tmpl);
			}
		}
		if (!$ishtml)
		$tmpl = str_replace("\n", "<br />\n", $tmpl);

		return $tmpl;
	}
}