<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

class JFormFieldFSSSQLCombo extends JFormFieldList
{
	protected $type = 'FSSSQLCombo';
	
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
	
	function getOptions()
	{
		$sql = $this->element->sql;
		if (!$sql)
		return array();
		
		$db = JFactory::getDBO();
		$db->setQuery($sql);
		
		return array_merge(parent::getOptions(), $db->loadObjectList());
	}
	
	function AdminDisplay($value, $name, $item)
	{
		return $value;
	}
}
