<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.html.pagination');

if (!class_exists('JPaginationEx'))
{
	class JPaginationEx extends JPagination
	{
		var $skinstyle = 0;
	
		function __construct($total, $limitstart, $limit)
		{
			$this->skinstyle = FSS_Settings::get('skin_style');
		
			parent::__construct($total, $limitstart, $limit);	
		}
	
		function getPagesLinks()
		{
			$mainframe = JFactory::getApplication();
		 
			$lang = JFactory::getLanguage();

			// Build the page navigation list
			$data = $this->_buildDataObject();

			$data->previous->text = "&laquo;";
			$data->next->text = "&raquo;";

			$list = array();

			$itemOverride = false;
			$listOverride = false;

			$chromePath = JPATH_THEMES.DS.$mainframe->getTemplate().DS.'html'.DS.'pagination.php';
			if ($this->skinstyle == 1 && file_exists($chromePath))
			{
				require_once ($chromePath);
				if (function_exists('pagination_item_active') && function_exists('pagination_item_inactive')) {
					$itemOverride = true;
				}
				if (function_exists('pagination_list_render')) {
					$listOverride = true;
				}
			}

			// Build the select list
			if ($data->all->base !== null) {
				$list['all']['active'] = true;
				$list['all']['data'] = ($itemOverride) ? pagination_item_active($data->all) : $this->_item_active($data->all);
			} else {
				$list['all']['active'] = false;
				$list['all']['data'] = ($itemOverride) ? pagination_item_inactive($data->all) : $this->_item_inactive($data->all);
			}

			if ($data->start->base !== null) {
				$list['start']['active'] = true;
				$list['start']['data'] = ($itemOverride) ? pagination_item_active($data->start) : $this->_item_active($data->start);
			} else {
				$list['start']['active'] = false;
				$list['start']['data'] = ($itemOverride) ? pagination_item_inactive($data->start) : $this->_item_inactive($data->start);
			}
			if ($data->previous->base !== null) {
				$list['previous']['active'] = true;
				$list['previous']['data'] = ($itemOverride) ? pagination_item_active($data->previous) : $this->_item_active($data->previous);
			} else {
				$list['previous']['active'] = false;
				$list['previous']['data'] = ($itemOverride) ? pagination_item_inactive($data->previous) : $this->_item_inactive($data->previous);
			}

			$list['pages'] = array(); //make sure it exists
			foreach ($data->pages as $i => $page)
			{
				if ($page->base !== null) {
					$list['pages'][$i]['active'] = true;
					$list['pages'][$i]['data'] = ($itemOverride) ? pagination_item_active($page) : $this->_item_active($page);
				} else {
					$list['pages'][$i]['active'] = false;
					$list['pages'][$i]['data'] = ($itemOverride) ? pagination_item_inactive($page) : $this->_item_inactive($page);
				}
			}

			if ($data->next->base !== null) {
				$list['next']['active'] = true;
				$list['next']['data'] = ($itemOverride) ? pagination_item_active($data->next) : $this->_item_active($data->next);
			} else {
				$list['next']['active'] = false;
				$list['next']['data'] = ($itemOverride) ? pagination_item_inactive($data->next) : $this->_item_inactive($data->next);
			}
			if ($data->end->base !== null) {
				$list['end']['active'] = true;
				$list['end']['data'] = ($itemOverride) ? pagination_item_active($data->end) : $this->_item_active($data->end);
			} else {
				$list['end']['active'] = false;
				$list['end']['data'] = ($itemOverride) ? pagination_item_inactive($data->end) : $this->_item_inactive($data->end);
			}

			if($this->total > $this->limit){
				return ($listOverride) ? pagination_list_render($list) : $this->_list_render($list);
			}
			else{
				return '';
			}
		}
	
		function getListFooter()
		{
			$mainframe = JFactory::getApplication();

			$list = array();
			$list['limit']			= $this->limit;
			$list['limitstart']		= $this->limitstart;
			$list['total']			= $this->total;
			$list['limitfield']		= $this->getLimitBox();
			$list['pagescounter']	= $this->getPagesCounter();
			$list['pageslinks']		= $this->getPagesLinks();
	
			if ($this->total < 5)
				return "";
		
			$chromePath		= JPATH_THEMES.DS.$mainframe->getTemplate().DS.'html'.DS.'pagination.php';
			if ($this->skinstyle == 1 && file_exists( $chromePath ))
			{
				require_once( $chromePath );
				if (function_exists( 'pagination_list_footer' )) {
					return pagination_list_footer( $list );
				}
			}
			return $this->_list_footer($list);
		}

		function _list_footer($list)
		{
			// Initialize variables
			$html = "<div class=\"pagination\">\n";
			$html .= "\n<div class=\"pull-right\">".JText::_("DISPLAY_NUM").$list['limitfield']."</div>";
			//$html .= "\n<div class=\"fss_pagination\">&nbsp;".$list['pageslinks']."</div>";
			$html .= "<div class=\"pagination wtf\">\n";
			$html .= "\n<ul>".$list['pageslinks']."</ul>";
			$html .= "</div>";
			//$html .= "\n<div class=\"fss_counter\">&nbsp;".$list['pagescounter']."</div>";
			$html .= "\n<input type=\"hidden\" name=\"limitstart\" value=\"".$list['limitstart']."\" />";
			$html .= "\n</div>";

			return $html;
		}

		function _list_render($list)
		{
			// Initialize variables
			$html = null;

			// Reverse output rendering for right-to-left display
			$html .= $list['previous']['data'];
			foreach( $list['pages'] as $page ) {
				$html .= ' '.$page['data'];
			}
			$html .= ' '. $list['next']['data'];
			
			return $html;
		}

		function _item_active(JPaginationObject $item)
		{
			return "<li><a title=\"".$item->text."\" href=\"".$item->link."\" limit='{$item->base}'>".$item->text."</a></li>";
		}

		function _item_inactive(JPaginationObject $item)
		{
				return "<li class='active'><a href='#'>".$item->text."</a></li>";
		}

	}

}

if (!class_exists("JPaginationJS"))
{
	class JPaginationJS extends JPaginationEx
	{
		function _item_active(JPaginationObject $item)
		{
			if($item->base>0)
				return "<li><a href='' title=\"".$item->text."\" onclick=\"javascript: document.fssForm.limitstart.value=".$item->base.";jQuery('#fssForm').submit();jQuery('#fssFormTS').submit();return false;\" class=\"pagenav\">".$item->text."</a></li>";

			return "<li><a href='' title=\"".$item->text."\" onclick=\"javascript: document.fssForm.limitstart.value=0;jQuery('#fssForm').submit();jQuery('#fssFormTS').submit();return false;\" class=\"pagenav\">".$item->text."</a></li>";	
		}
	}
}

if (!class_exists('JPaginationAjax'))
{
	class JPaginationAjax extends JPaginationEx
	{
		function _buildDataObject()
		{
			if (empty($this->_viewall)) $this->_viewall = false;
		
			// Initialize variables
			$data = new stdClass();

			$data->all	= new JPaginationObject(JText::_("VIEW_ALL"));
			if (!$this->_viewall) {
				$data->all->base	= '0';
				$data->all->link	= FSSRoute::x("&limitstart=");
			}

			// Set the start and previous data objects
			$data->start	= new JPaginationObject(JText::_("START"));
			$data->previous	= new JPaginationObject(JText::_("PREV"));

			if ($this->get('pages.current') > 1)
			{
				$page = ($this->get('pages.current') -2) * $this->limit;

				$page = $page == 0 ? '' : $page; //set the empty for removal from route

				$data->start->base	= '0';
				$data->start->link	= "javascript:ChangePage(0);";
				$data->previous->base	= $page;
				$data->previous->link	= "javascript:ChangePage($page);";
			}

			// Set the next and end data objects
			$data->next	= new JPaginationObject(JText::_("NEXT"));
			$data->end	= new JPaginationObject(JText::_("END"));

			if ($this->get('pages.current') < $this->get('pages.total'))
			{
				$next = $this->get('pages.current') * $this->limit;
				$end  = ($this->get('pages.total') -1) * $this->limit;

				$data->next->base	= $next;
				$data->next->link	= "javascript:ChangePage($next);";
				$data->end->base	= $end;
				$data->end->link	= "javascript:ChangePage($end);";
			}

			$data->pages = array();
			$stop = $this->get('pages.stop');
			for ($i = $this->get('pages.start'); $i <= $stop; $i ++)
			{
				$offset = ($i -1) * $this->limit;

				$offset = $offset == 0 ? '' : $offset;  //set the empty for removal from route

				$data->pages[$i] = new JPaginationObject($i);
				if ($i != $this->get('pages.current') || $this->_viewall)
				{
					$data->pages[$i]->base	= $offset;
					$data->pages[$i]->link	= "javascript:ChangePage($offset);";
				}
			}
			return $data;
		}
	
		function getLimitBox()
		{
			if (empty($this->_viewall)) $this->_viewall = false;
			$mainframe = JFactory::getApplication();

			// Initialize variables
			$limits = array ();

			// Make the option list
			for ($i = 5; $i <= 30; $i += 5) {
				$limits[] = JHTML::_('select.option', "$i");
			}
			$limits[] = JHTML::_('select.option', '50');
			$limits[] = JHTML::_('select.option', '100');
			$limits[] = JHTML::_('select.option', '0', JText::_("ALL"));

			$selected = $this->_viewall ? 0 : $this->limit;

			// Build the select list
			$html = JHTML::_('select.genericlist',  $limits, 'limit_base', 'class="input-mini" onchange="ChangePageCount(this.value)"', 'value', 'text', $selected);
		
			return $html;
		}
	}
}