<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'helper.php' );
require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'settings.php' );

class FSSCron
{
	var $_log;

	function Log($message)
	{
		$this->_log .= $message."<br>";
	}

	function SaveLog()
	{
		$db = JFactory::getDBO();
		$class = get_class($this);
		$class = str_ireplace("FSSCron","",$class);
		$now = FSS_Helper::CurDate();
		
		$qry = "INSERT INTO #__fss_cron_log (cron, `when`, log) VALUES ('".FSSJ3Helper::getEscaped($db, $class)."', '{$now}', '" . FSSJ3Helper::getEscaped($db, $this->_log) . "')";
		$db->SetQuery($qry);
		$db->Query();
		//echo $qry."<br>";
		
		FSS_Helper::cleanLogs();
	}

	function updateData($data)
	{
		$data = json_encode($data);
		$db = JFactory::getDBO();
		$qry = "UPDATE #__fss_cron SET params = '" . $db->escape($data) . "' WHERE id = " . $this->id;

		$db->setQuery($qry);
		$db->Query();
	}
}