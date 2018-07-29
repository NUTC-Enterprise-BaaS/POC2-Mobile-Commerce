<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class FSS_Comments_Handler
{
	var $ident = 0;	
	var $name;
	var $item_select_must_have = 0;
	var $short_thanks = 0;
	
	function __construct($parent) 
	{
		$this->comments = $parent;
	}

	function GetName()
	{
		if (empty($this->name))
		{
			$classname = get_class($this);
			$classname = str_ireplace("FSS_Comments_Handler_","",$classname);
			if ($classname == " FSS_Comments_Handler")
				$classname = "general";
			$this->name = strtolower($classname);	
		}
		return $this->name;	
	}

	function EMail_AddFields(&$comment)
	{
		$comment['title'] = $this->email_title;
		$comment['article_type'] = $this->email_article_type;
		
		$itemid = $comment['itemid'];
		
		$item = $this->GetItem($itemid);
		
		$comment['article'] = $item[$this->field_title];
		
		$link = FSS_Helper::GetBaseURL() . FSSRoute::_(str_replace("{id}",$itemid,$this->article_link));
		$comment['linkart'] = $link;
	}

	function GetItem($itemid)
	{
		$db = JFactory::getDBO();
		$qry = "SELECT {$this->field_id}, {$this->field_title} FROM {$this->table} WHERE {$this->field_id} = '".FSSJ3Helper::getEscaped($db, $itemid)."'";
		$db->setQuery($qry);
		return $db->loadAssoc();	
	}
	
	function GetDesc()
	{
		return $this->description;	
	}
	
	function GetLongDesc()
	{
		return $this->long_desc;	
	}
	
	function GetItemData($itemids = null)
	{
		$db = JFactory::getDBO();
		$qry = "SELECT {$this->field_id}, {$this->field_title} FROM {$this->table} WHERE {$this->field_id}";
		if ($itemids)
		{
			$ids = array();
			foreach ($itemids as $id)
				$ids[] = FSSJ3Helper::getEscaped($db, $id);
			$qry .= " IN (" . implode(", ",$ids) . ")";
		}
		$db->setQuery($qry);
		$this->itemdata = $db->loadAssocList($this->field_id);		
	}
	
	function GetItemTitle($itemid)
	{
		if (!array_key_exists($itemid,$this->itemdata))
			return "";
		if (!array_key_exists($this->field_title,$this->itemdata[$itemid]))
			return "";
		return $this->itemdata[$itemid][$this->field_title];	
	}
	
	function GetItemLink($itemid)
	{
		return FSSRoute::_(str_replace("{id}",$itemid,$this->article_link));
	}
	
	function EMail_GetTemplate()
	{
		return 'comment';
	}
}