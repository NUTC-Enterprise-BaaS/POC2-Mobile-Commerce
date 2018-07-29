<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class FSS_Comments_Handler_KB extends FSS_Comments_Handler
{
	var $ident = 1;	
	
	function __construct($parent) 
	{
		$this->comments = $parent;
		$this->comments->use_comments = FSS_Settings::get('kb_comments');
		$this->comments->opt_display = 1;

		$this->short_thanks = 1;
		$this->email_title = "A Knowledge Base Article comment";
		$this->email_article_type = JText::_('Article');
		$this->description = JText::_('KNOWLEDGE_BASE_ARTICLE');	
		$this->descriptions = JText::_('KNOWLEDGE_BASE');	
		$this->long_desc = JText::_('COMMENTS_KNOWLEDGE_BASE_ARTICLE');
		
		$this->article_link = "index.php?option=com_fss&view=kb&kbartid={id}";
		
		$this->table = "#__fss_kb_art";
		$this->has_published = 1;
		$this->field_title = "title";
		$this->field_id = "id";
	}
}