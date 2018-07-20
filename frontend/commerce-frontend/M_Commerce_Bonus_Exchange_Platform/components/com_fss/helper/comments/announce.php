<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class FSS_Comments_Handler_Announce extends FSS_Comments_Handler
{
	var $ident = 4;	
	
	function __construct($parent) 
	{
		$this->comments = $parent;
		$this->comments->use_comments = FSS_Settings::get('announce_comments_allow');
		$this->comments->opt_display = 1;	
		
		$this->short_thanks = 1;
		$this->email_title = "An Announcement comment";
		$this->email_article_type = JText::_('ANNOUNCEMENT');
		$this->description = JText::_('ANNOUNCEMENT');	
		$this->descriptions = JText::_('ANNOUNCEMENTS');	
		$this->long_desc = JText::_('COMMENTS_ANNOUNCEMENTS');
		
		$this->article_link = "index.php?option=com_fss&view=announce&announceid={id}";
		
		$this->table = "#__fss_announce";
		$this->has_published = 1;
		$this->field_title = "title";
		$this->field_id = "id";
	}
}		      	    	  