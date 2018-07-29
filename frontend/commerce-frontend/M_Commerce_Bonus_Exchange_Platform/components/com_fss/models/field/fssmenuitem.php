<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class JFormFieldFSSMenuItem extends JFormFieldList
{
	function getOptions()
	{
		$db = JFactory::getDBO();
		
		$qry = $db->getQuery(true);
		$qry->select("title as display, id, parent_id, level");
		$qry->from("#__menu");
		$qry->order("menutype, lft");
		$qry->where("level > 0");
		$qry->where("menutype != 'menu'");
		$qry->where("published = 1");

		$db->setQuery($qry);
		
		$items = $db->loadObjectList();
		
		$options = array();

		foreach ($items as &$item)
		{
			$options[] = JHtml::_(
				'select.option', $item->id,
				str_repeat("|&mdash;&thinsp;", $item->level - 1) . $item->display,
				'value', 'text'
			);
		}

		return $options;
	}
}
