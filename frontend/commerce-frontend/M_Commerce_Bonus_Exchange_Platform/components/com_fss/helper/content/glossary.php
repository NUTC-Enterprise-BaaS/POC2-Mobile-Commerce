<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'content.php');
require_once (JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_fss'.DS.'adminhelper.php');

class FSS_ContentEdit_Glossary extends FSS_ContentEdit
{
	function __construct()
	{
		$this->id = "glossary";
		$this->descs = JText::_("Glossary");
		
		$this->table = "#__fss_glossary";
		
		$this->fields = array();

		$field = new FSS_Content_Field("word",JText::_("Word"));
		$field->link = 1;
		$field->search = 1;
		$this->AddField($field);
		
		if (empty(FSSAdminHelper::$langs))
		{
			FSSAdminHelper::LoadLanguages();
			FSSAdminHelper::LoadAccessLevels();
		}

		$filter_langs = array();
		$filter_access = array();
			
		$field = new FSS_Content_Field("language",JText::_("LANGUAGE"),"lookup","lang_art");
		$field->lookup_required = 1;
		$field->lookup_id = "id";
		$field->lookup_title = "title";
		foreach (FSSAdminHelper::$langs as $lang)
		{
			$filter_langs[$lang->value] = $lang->text;
			$field->lookup_extra[$lang->value] = $lang->text;
		}
		if (!FSS_Helper::langEnabled())
			$field->hide = 1;
		$this->AddField($field);

		$field = new FSS_Content_Field("access",JText::_("Access"),"lookup");
		$field->lookup_required = 1;
		$field->lookup_id = "id";
		$field->lookup_title = "title";
		$field->default = 1;
		foreach (FSSAdminHelper::$access_levels as $lang)
		{
			$filter_access[$lang->value] = $lang->text;
			$field->lookup_extra[$lang->value] = $lang->text;
		}
		$this->AddField($field);
		
		$field = new FSS_Content_Field("description",JText::_("DESCRIPTION"),"text");
		$field->show_pagebreak = 0;
		$this->AddField($field);
		
		$field = new FSS_Content_Field("longdesc",JText::_("Long Description"),"text");
		$field->show_pagebreak = 1;
		$field->required = false;
		$this->AddField($field);
		
		$field = new FSS_Content_Field("altwords",JText::_("Alternate Words"),"long");
		$field->required = false;
		$this->AddField($field);

		$this->list = array();
		$this->list[] = "word";
		if (FSS_Helper::langEnabled())
			$this->list[] = "language";
		$this->list[] = "access";
		
		$this->edit = array();
		$this->edit[] = "word";
		$this->edit[] = "language";
		$this->edit[] = "access";
		$this->edit[] = "altwords";
		$this->edit[] = "description";
		$this->edit[] = "longdesc";

		$this->order = "word ASC";
		
		$this->link = "";
		
		$this->list_added = 0;
		
		if (FSS_Helper::langEnabled())
		{
			$filter = new FSS_Content_Filter("language","id","title","","","SELECT_LANGUAGE", "lang_filter", $filter_langs);
			$this->AddFilter($filter);
		}
			
		$filter = new FSS_Content_Filter("access","id","title","","","SELECT_ACCESS", "", $filter_access);
		$this->AddFilter($filter);
	}
	
	function MakeAnchor($word)
	{
		return strtolower(preg_replace("/[^A-Za-z0-9]/", '-', $word));	
	}
	
	function getArtLink()
	{
		$anchor = $this->MakeAnchor($this->item['word']);
		$letter = strtolower(substr($this->item['word'],0,1));

		return FSSRoute::_("index.php?option=com_fss&view=glossary&letter=" . $letter . "#" . $anchor, false);
	}
}