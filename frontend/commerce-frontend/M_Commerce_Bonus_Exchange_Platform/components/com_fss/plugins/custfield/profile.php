<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class ProfilePlugin extends FSSCustFieldPlugin
{
	var $name = "Profile display";
	var $sources = array();

	var $default_params = array(
		'field' => 'joomla.email',
		'custom' => ''
		);

	function __construct()
	{
		$this->getSourcePlugins();
	}

	function getSourcePlugins()
	{
		$path = JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'plugins'.DS.'custfield'.DS.'profile'.DS.'sources';
		$files = JFolder::files($path,'(.php$)');
		foreach ($files as $file)
		{
			include_once ($path.DS.$file);

			$class = 'ProfileSource' . pathinfo($file, PATHINFO_FILENAME);

			if (class_exists($class))
			{
				$source = new $class();
				$this->sources[$source->order.$source->name] = $source;
			}
		}

		ksort($this->sources);
	}

	function getSource($id)
	{
		foreach($this->sources as $source)
		{
			if ($source->id == $id)
				return $source;
		}

		return null;
	}

	function allPlugins()
	{
		$result = array();
		foreach($this->sources as $source) $result[] = $source->id;

		return $result;
	}

	function DisplaySettings($params) // passed object with settings in
	{
		$params = $this->parseParams($params);
			
		$options_parsed = array();
		$options_parsed[] = JHTML::_('select.option', 'custom.html', 'Custom HTML Format' );
		$options_parsed[] = JHTML::_('select.option', 'custom.text', 'Custom Text Format' );

		$keys = array();

		foreach ($this->sources as $source)
		{
			$options_parsed[] = JHTML::_('select.optgroup', $source->name);
			foreach ($source->getFixedFields() as $key => $value)
			{
				$options_parsed[] = JHTML::_('select.option', $source->id . "." . $key, $value );
				$keys[$source->id . "." . $key] = $value;
			}
			$options_parsed[] = JHTML::_('select.optgroup', $source->name);
		}
		
		ob_start();
		include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'plugins'.DS.'custfield'.DS.'profile'.DS.'form.php';
		$result = ob_get_clean();

		return $result;
	}

	function SaveSettings() // return object with settings in
	{
		return $this->encodeParams( array ( 
			'field'		=> FSS_Input::getCmd('profile_field'),
			'custom'	=> JRequest::getVar('profile_custom', '', 'post', 'string', JREQUEST_ALLOWRAW)
			));
	}

	function Input($current, $params, $context, $id) // output the field for editing
	{
		return "<span class='input-large uneditable-input'>".$this->Display($current, $params, $context, $id)."</span>";
	}

	function Display($value, $params, $context, $id) // output the field for display
	{
		$userid = 0;
		if (isset($context['userid'])) $userid = $context['userid'];
		if (isset($context['data']) && isset($context['data']->user_id)) $userid = $context['data']->user_id;
		if ($userid < 1) return "";

		$params = $this->parseParams($params);
		$field = $params->field;

		$user = JFactory::getUser($userid);
		
		if ($user->id < 1)
			return "";
		
		list($plugin, $field) = explode(".", $field);

		if ($plugin == "custom")
			return $this->displayTemplate($user, $params->custom, ($field == "html") ? true : false); 

		$source = $this->getSource($plugin);
		$presets = $source->getPresets();

		if (array_key_exists($field, $presets)) 
			return FSS_Helper::escape_sequence_decode($this->displayTemplate($user, $presets[$field], true, $plugin));

		$values = $source->getValues($user);

		if (array_key_exists($field, $values))
			return FSS_Helper::escape_sequence_decode($values[$field]);

		return "";
	}

	function displayTemplate($user, $tmpl, $ishtml = true, $plugins = null)
	{
		if ($plugins && !is_array($plugins))
			$plugins = array($plugins);

		if (!$plugins)
			$plugins = $this->allPlugins();

		$parser = new FSSParser();

		foreach ($plugins as $plugin)
		{
			$source = $this->getSource($plugin);
			$data = $source->getValues($user);
			foreach ($data as $key => $value)
			{
				$parser->setVar($plugin . "." . $key, $value);
			}
		}

		$parser->loadText($tmpl);

		$tmpl = $parser->Parse();

		if (!$ishtml)
			$tmpl = str_replace("\n", "<br />\n", $tmpl);

		return $tmpl;
	}

	function CanEdit()
	{
		return false;	
	}

	function Search($params, $search, $peruser)
	{
		$params = $this->parseParams($params);
		$plugins = $this->allPlugins();

		$to_search = array();
		list ($type, $field) = explode(".", $params->field, 2);
		if ($type == "custom")
		{
			if (preg_match_all("/\{([a-z0-9A-Z\.\-\_\,]+)\}/", $params->custom, $matches))
			{
				foreach ($matches[1] as $match)
				{
					list ($type, $field) = explode(".", $match, 2);
					$to_search[$type][] = $field;
				}
			}
		} else {
			$to_search[$type][] = $field;
		}

		$result = array();

		foreach ($to_search as $type => $fields)
		{
			$source = $this->getSource($type);
			if ($source)
				$result = array_unique(array_merge($result, $source->search($fields, $search)));
		}

		if ($peruser) 
		{
			$out = array();
			foreach ($result as $userid)
			{
				$u = new stdClass();
				$u->user_id = $userid;
				$out[] = $u;
			}
			return $out;
		}

		if (count($result) > 0)
		{
			// not per user, need a list of ticket ids
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);	
			$query->select("id as ticket_id")
				->from("#__fss_ticket_ticket")
				->where("user_id IN (" . implode(", ", $result) . ")");

			$db->setQuery($query);
			return $db->loadObjectList();	
		}	

		return array();
	}
}

class ProfileSource
{
	var $name = '';
	var $order = 5;
	function getFixedFields()
	{
		return array();
	}

	function getPresets()
	{
		return array();
	}

	function getValues($user)
	{
		return array();
	}

	function search($fields, $search)
	{
		return array('-1');
	}
}