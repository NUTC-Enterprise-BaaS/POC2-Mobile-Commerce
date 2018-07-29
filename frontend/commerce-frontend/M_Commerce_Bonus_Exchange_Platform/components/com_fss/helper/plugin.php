<?php

class FSS_Plugin 
{
	var $title = "";
	var $description = "";
	var $name = "";
	var $type = "";

	var $data;
	var $settings;
	var $settings_loaded = false;

	function loadData()
	{

	}
	
	function process()
	{
		
	}
	
	function display($tpl)
	{
		$file = JPATH_SITE.DS."components".DS."com_fss".DS."plugins".DS.$this->type.DS.$this->name.DS.$tpl.".php";	

		if (file_exists($file))
		{
			include($file);	
		}
	}

	function loadSettings()
	{
		if (empty($this->settings_loaded))
		{
			$db = JFactory::getDBO();
			$sql = "SELECT settings FROM #__fss_plugins WHERE `type` = '" . $db->escape($this->type) . "' AND name = '" . $db->escape($this->name) . "'";
			$db->setQuery($sql);
			$plugin = $db->loadObject();

			if ($plugin->settings == "")
			{
				$this->settings = null;
			} else {
				$this->settings = json_decode($plugin->settings);
			}

			$this->settings_loaded = true;
		}

		if ($this->settings) return true;

		return false;
	}

	function storeData()
	{

	}
}