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

class FSS_ContentEdit_KB extends FSS_ContentEdit
{
	function __construct()
	{
		$this->id = "kb";
		$this->descs = JText::_("KNOWLEDGE_BASE_ARTICLES");
		$this->has_products = 1;
		$this->has_modified = 1;
		$this->has_ordering = 1;
		
		$this->table = "#__fss_kb_art";
		
		$this->fields = array();

		$field = new FSS_Content_Field("title",JText::_("TITLE"));
		$field->link = 1;
		$field->search = 1;
		$this->AddField($field);

		$field = new FSS_Content_Field("body",JText::_("ARTICLE"),"text");
		$this->AddField($field);
		
		$field = new FSS_Content_Field("kb_cat_id",JText::_("CATEGORY"),"lookup");
		$field->lookup_table = "#__fss_kb_cat";
		$field->lookup_required = 1;
		$field->lookup_id = "id";
		$field->lookup_order = "title";
		$field->lookup_title = "title";
		$field->lookup_extra[0] = JText::_('UNCATEGORIZED');
		$field->lookup_select_msg = JText::_("PLEASE_SELECT_A_CATEGORY");
		$field->lookup_nested = true;
		$this->AddField($field);
		
		// products
		$field = new FSS_Content_Field("allprods",JText::_("SHOW_FOR_ALL_PRODUCTS"),"products");
		$field->required = 0;
		$field->prod_table = "#__fss_kb_art_prod";
		$field->prod_artid = "kb_art_id";
		$field->prod_prodid = "prod_id";
		$field->default = 1;
		$field->prod_msg = JText::_('SHOW_FOR_ALL_PRODUCTS');
		$this->AddField($field);

		// related
		$field = new FSS_Content_Field("related",JText::_("RELATED_ARTICLES"),"related");
		$field->required = 0;
		$field->rel_table = "#__fss_kb_art_related";
		$field->rel_id = "kb_art_id";
		$field->rel_relid = "related_id";
		
		$field->rel_button_txt = JText::_('ADD_RELATED_ARTICLE');
		
		// define the lookup form
		$field->rel_lookup_table = "#__fss_kb_art";
		$field->rel_lookup_table_alias = "a";
		$field->rel_lookup_join[] = array('table' => '#__fss_kb_cat', 'source' => 'kb_cat_id', 'dest' => 'id', 'alias' => 'c');
		$field->rel_lookup_join[] = array('table' => '#__users', 'source' => 'author', 'dest' => 'id', 'alias' => 'u');
		$field->rel_lookup_id = "id";
		$field->rel_lookup_display = array(
			'a.title' => array('desc' =>JText::_('TITLE'),  'alias' => 'title'),
			'c.title' => array('desc' =>JText::_('CATEGORY'),  'alias' => 'category'),
			'a.published' => array('desc' =>JText::_('PUBLISHED'),  'alias' => 'published', 'type' => 'published'),
			'u.name' => array('desc' =>JText::_('USER'),  'alias' => 'user')
			);
		$field->rel_lookup_search = array("a.title", 'a.body');
		$field->rel_lookup_pick_field = "a.title";
		$field->rel_lookup_order = "a.title";
		$field->AddFilter(new FSS_Content_Filter("kb_cat_id","id","title","#__fss_kb_cat","title","CATEGORY", "", "", true));
		$field->rel_display = "title";
		
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
		$field->default = "*";
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
		
		$this->list = array();
		$this->list[] = "title";
		$this->list[] = "kb_cat_id";
		if (FSS_Helper::langEnabled())
			$this->list[] = "language";
		$this->list[] = "access";
		
		$this->edit = array();
		$this->edit[] = "kb_cat_id";
		$this->edit[] = "title";
		$this->edit[] = "allprods";
		$this->edit[] = "language";
		$this->edit[] = "access";
		$this->edit[] = "body";
		$this->edit[] = "related";
		
		$this->order = "modified DESC";

		$this->link = "index.php?option=com_fss&view=kb&kbartid=%ID%";

		$filter = new FSS_Content_Filter("kb_cat_id","id","title","#__fss_kb_cat","title","CATEGORY", "", "", true);
		$this->AddFilter($filter);
		
		if (FSS_Helper::langEnabled())
		{
			$filter = new FSS_Content_Filter("language","id","title","","","SELECT_LANGUAGE", "lang_filter", $filter_langs);
			$this->AddFilter($filter);
		}
			
		$filter = new FSS_Content_Filter("access","id","title","","","SELECT_ACCESS", "access_filter", $filter_access);
		$this->AddFilter($filter);
	}
}