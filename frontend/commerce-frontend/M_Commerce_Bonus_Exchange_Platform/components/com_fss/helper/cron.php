<?php
require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'helper.php' );
require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'settings.php' );

class FSS_Cron_Helper 
{
	static function runCron($test = null)
	{
		$db = JFactory::getDBO();

		$max_runs = 10;
				
		while ($max_runs-- > 0 || $test > 0)
		{
			if ($test > 0)
			{
				error_reporting(E_ALL);
				ini_set('display_errors', 1);
				$qry = "SELECT * FROM #__fss_cron WHERE id = ".$db->escape($test);
			} else {		
				$qry = "SELECT * FROM #__fss_cron WHERE published = 1 AND ((UNIX_TIMESTAMP() - lastrun) - (`interval` * 60)) > 0 LIMIT 1";
			}
			
			$db->setQuery($qry);
			$rows = $db->loadObjectList();
					
			if (!$rows) 
				return;
		
			foreach ($rows as $row)
			{
				$db->setQuery("UPDATE #__fss_cron SET lastrun=UNIX_TIMESTAMP() WHERE id='{$row->id}' LIMIT 1");
				$db->query();

				$class = "FSSCron" . $row->class;
				if (substr($row->class, 0, 6) == "Plugin")
				{
					$file = strtolower(str_ireplace("plugin", "", $row->class) . ".php");
					$path = JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'plugins'.DS.'cron'.DS;
				} else {
					$file = strtolower($row->class) . ".php";
					$path = JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'cron'.DS;
				}

				if (file_exists($path.$file))
				{

					require_once($path.$file);
					$inst = new $class();
					$inst->id = $row->id;
					$inst->Execute(self::ParseParams($row->params));

					if ($test > 0)
					{
						echo "<pre>".$inst->_log."</pre>";	
					} else {
						$inst->SaveLog();
					}
				} else {
					$inst = new FSSCron();
					$inst->id = $row->id;
					$inst->_log = "Unable to find file:<br />" . $path.$file . "<br />";
					$inst->SaveLog();
				}
			}
			
			if ($test > 0)
			{
				$test = 0;
				break;
			}
		}
	}
	
	static function ParseParams(&$aparams)
	{
		if (substr($aparams,0, 1) == "{") return json_decode($aparams);
		if (substr($aparams,0, 1) == "[") return json_decode($aparams);
		if (substr($aparams,0,2) == "a:") return unserialize($aparams);	

		$out = array();
		$bits = explode(";",$aparams);
		foreach ($bits as $bit)
		{
			if (trim($bit) == "") continue;
			$res = explode(":",$bit,2);
			if (count($res) == 2)
			{
				$out[$res[0]] = $res[1];	
			}
		}
		return $out;	
	}
}