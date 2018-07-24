<?php
/*------------------------------------------------------------------------
# pagination.php - OS Services Booking
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2015 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// Check to ensure this file is within the rest of the framework
defined('_JEXEC') or die();

/**
 * Pagination Class.  Provides a common interface for content pagination for the
 * Joomla! Framework
 *
 * @author		Dang Thuc Dam
 * @package 	Joomla.Framework
 * @subpackage	HTML
 * @since		1.5
 */

if (!class_exists('OSBJPagination')){
	class OSBJPagination extends JObject
	{
		/**
		 * The record number to start dislpaying from
		 *
		 * @access public
		 * @var int
		 */
		var $limitstart = null;
	
		/**
		 * Number of rows to display per page
		 *
		 * @access public
		 * @var int
		 */
		var $limit = null;
	
		/**
		 * Total number of rows
		 *
		 * @access public
		 * @var int
		 */
		var $total = null;
	
		/**
		 * View all flag
		 *
		 * @access protected
		 * @var boolean
		 */
		var $_viewall = false;
	
		/**
		 * Constructor
		 *
		 * @param	int		The total number of items
		 * @param	int		The offset of the item to start at
		 * @param	int		The number of items to display per page
		 */
		function __construct($total, $limitstart, $limit)
		{
			// Value/Type checking
			$this->total		= (int) $total;
			$this->limitstart	= (int) max($limitstart, 0);
			$this->limit		= (int) max($limit, 0);
	
			if ($this->limit > $this->total) {
				$this->limitstart = 0;
			}
	
			if (!$this->limit)
			{
				$this->limit = $total;
				$this->limitstart = 0;
			}
	
			if ($this->limitstart > $this->total) {
				$this->limitstart -= $this->limitstart % $this->limit;
			}
	
			// Set the total pages and current page values
			if($this->limit > 0)
			{
				$this->set( 'pages.total', ceil($this->total / $this->limit));
				$this->set( 'pages.current', ceil(($this->limitstart + 1) / $this->limit));
			}
	
			// Set the pagination iteration loop values
			$displayedPages	= 10;
			$this->set( 'pages.start', (floor(($this->get('pages.current') -1) / $displayedPages)) * $displayedPages +1);
			if ($this->get('pages.start') + $displayedPages -1 < $this->get('pages.total')) {
				$this->set( 'pages.stop', $this->get('pages.start') + $displayedPages -1);
			} else {
				$this->set( 'pages.stop', $this->get('pages.total'));
			}
	
			// If we are viewing all records set the view all flag to true
			if ($this->limit == $total) {
				$this->_viewall = true;
			}
		}
	
		/**
		 * Return the rationalised offset for a row with a given index.
		 *
		 * @access	public
		 * @param	int		$index The row index
		 * @return	int		Rationalised offset for a row with a given index
		 * @since	1.5
		 */
		function getRowOffset($index)
		{
			return $index +1 + $this->limitstart;
		}
	
		/**
		 * Return the pagination data object, only creating it if it doesn't already exist
		 *
		 * @access	public
		 * @return	object	Pagination data object
		 * @since	1.5
		 */
		function getData()
		{
			static $data;
			if (!is_object($data)) {
				$data = $this->_buildDataObject();
			}
			return $data;
		}
	
		/**
		 * Create and return the pagination pages counter string, ie. Page 2 of 4
		 *
		 * @access	public
		 * @return	string	Pagination pages counter string
		 * @since	1.5
		 */
		function getPagesCounter()
		{
			// Initialize variables
			$html = null;
			if ($this->get('pages.total') > 1) {
				$html .= JText::_('Page')." ".$this->get('pages.current')." ".JText::_('of')." ".$this->get('pages.total');
			}
			return $html;
		}
	
		/**
		 * Create and return the pagination result set counter string, ie. Results 1-10 of 42
		 *
		 * @access	public
		 * @return	string	Pagination result set counter string
		 * @since	1.5
		 */
		function getResultsCounter()
		{
			// Initialize variables
			$html = null;
			$fromResult = $this->limitstart + 1;
	
			// If the limit is reached before the end of the list
			if ($this->limitstart + $this->limit < $this->total) {
				$toResult = $this->limitstart + $this->limit;
			} else {
				$toResult = $this->total;
			}
	
			// If there are results found
			if ($this->total > 0) {
				$msg = JText::sprintf('Results of', $fromResult, $toResult, $this->total);
				$html .= "\n".$msg;
			} else {
				$html .= "\n".JText::_('No records found');
			}
	
			return $html;
		}
	
		/**
		 * Create and return the pagination page list string, ie. Previous, Next, 1 2 3 ... x
		 *
		 * @access	public
		 * @return	string	Pagination page list string
		 * @since	1.0
		 */
		function getPagesLinks()
		{
			global $mainframe;
	
			$lang = JFactory::getLanguage();
	
			// Build the page navigation list
			$data = $this->_buildDataObject();
	
			$list = array();
	
			$itemOverride = false;
			$listOverride = false;
		
			$itemOverride = false;
			$listOverride = false;
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
	
		/**
		 * Return the pagination footer
		 *
		 * @access	public
		 * @return	string	Pagination footer
		 * @since	1.0
		 */
		function getListFooter()
		{
			global $mainframe;

			$list = array();
			$list['limit']			= $this->limit;
			$list['limitstart']		= $this->limitstart;
			$list['total']			= $this->total;
			$list['limitfield']		= $this->getLimitBox();
			$list['pagescounter']	= $this->getPagesCounter();
			$list['pageslinks']		= $this->getPagesLinks();
			if (version_compare(JVERSION, '3.0', 'ge')) {
				$chromePath		= JPATH_THEMES.DS.$mainframe->getTemplate().DS.'html'.DS.'pagination.php';
				if (file_exists( $chromePath ))
				{
					require_once( $chromePath );
					if (function_exists( 'pagination_list_footer' )) {
						return pagination_list_footer( $list );
					}
				}
			}
			return $this->_list_footer($list);
		}
	
		/**
		 * Creates a dropdown box for selecting how many records to show per page
		 *
		 * @access	public
		 * @return	string	The html for the limit # input box
		 * @since	1.0
		 */
		function getLimitBox()
		{
			global $mainframe;
			
			$selected = $this->_viewall ? 0 : $this->limit;
			// Initialize variables
			$limits = array ();
	
			if(in_array($this->limit,array(10,15,20,30,50,100))){
				// Make the option list

				$limits[] = JHTML::_('select.option', 10);
				$limits[] = JHTML::_('select.option', 15);
				$limits[] = JHTML::_('select.option', 20);
				$limits[] = JHTML::_('select.option', 30);
				$limits[] = JHTML::_('select.option', '50');
				$limits[] = JHTML::_('select.option', '100');
				
		
				// Build the select list
				if ($mainframe->isAdmin()) {
					$html = JHTML::_('select.genericlist',  $limits, 'limit', 'class="input-mini" size="1" onchange="submitform();"', 'value', 'text', $selected);
				} else {
					$html = JHTML::_('select.genericlist',  $limits, 'limit', 'class="input-mini" size="1" onchange="document.ftForm.submit()"', 'value', 'text', $selected);
				}
				return $html;
			}else{
				return '';
			}
		}
	
		/**
		 * Return the icon to move an item UP
		 *
		 * @access	public
		 * @param	int		$i The row index
		 * @param	boolean	$condition True to show the icon
		 * @param	string	$task The task to fire
		 * @param	string	$alt The image alternate text string
		 * @return	string	Either the icon to move an item up or a space
		 * @since	1.0
		 */
		function orderUpIcon($i, $condition = true, $task = 'orderup', $alt = 'Move Up', $enabled = true)
		{
			$alt = JText::_($alt);
	
			$html = '&nbsp;';
			if (($i > 0 || ($i + $this->limitstart > 0)) && $condition)
			{
				if($enabled) {
					$html	= '<a href="#reorder" onclick="return listItemTask(\'cb'.$i.'\',\''.$task.'\')" title="'.$alt.'">';
					$html	.= '   <img src="images/uparrow.png" width="16" height="16" border="0" alt="'.$alt.'" />';
					$html	.= '</a>';
				} else {
					$html	= '<img src="images/uparrow0.png" width="16" height="16" border="0" alt="'.$alt.'" />';
				}
			}
	
			return $html;
		}
	
		/**
		 * Return the icon to move an item DOWN
		 *
		 * @access	public
		 * @param	int		$i The row index
		 * @param	int		$n The number of items in the list
		 * @param	boolean	$condition True to show the icon
		 * @param	string	$task The task to fire
		 * @param	string	$alt The image alternate text string
		 * @return	string	Either the icon to move an item down or a space
		 * @since	1.0
		 */
		function orderDownIcon($i, $n, $condition = true, $task = 'orderdown', $alt = 'Move Down', $enabled = true)
		{
			$alt = JText::_($alt);
	
			$html = '&nbsp;';
			if (($i < $n -1 || $i + $this->limitstart < $this->total - 1) && $condition)
			{
				if($enabled) {
					$html	= '<a href="#reorder" onclick="return listItemTask(\'cb'.$i.'\',\''.$task.'\')" title="'.$alt.'">';
					$html	.= '  <img src="images/downarrow.png" width="16" height="16" border="0" alt="'.$alt.'" />';
					$html	.= '</a>';
				} else {
					$html	= '<img src="images/downarrow0.png" width="16" height="16" border="0" alt="'.$alt.'" />';
				}
			}
	
			return $html;
		}
	
		function _list_footer($list)
		{
			// Initialize variables
			$lang = JFactory::getLanguage();
			$html = "<div class=\"pagination-wrap\">\n";
	
			if ($lang->isRTL()) {
				$html .= "\n<div class=\"counter\">".$list['pagescounter']."</div>";
				$html .= $list['pageslinks'];
				if($list['limitfield'] != ""){
					$html .= "\n<div class=\"limit\">".JText::_("OS_DISPLAY_NUM").$list['limitfield']."</div>";
				}
			} else {
				if($list['limitfield'] != ""){
					$html .= "\n<div class=\"limit\">".JText::_("OS_DISPLAY_NUM").$list['limitfield']."</div>";
				}
				$html .= $list['pageslinks'];
				//$html .= "\n<div class=\"counter\">".$list['pagescounter']."</div>";
			}
	
			$html .= "\n<input type=\"hidden\" name=\"limitstart\" value=\"".$list['limitstart']."\" />";
			$html .= "\n</div>";
	
			return $html;
		}
	
		function _list_render($list)
		{
			// Initialize variables
			$lang =& JFactory::getLanguage();
			$html = null;
			$html = "<ul class='pagination'>";
			// Reverse output rendering for right-to-left display
			if($lang->isRTL())
			{
				$html .=  $list['previous']['data'];
				$html .= $list['start']['data'];
				$list['pages'] = array_reverse( $list['pages'] );
				foreach( $list['pages'] as $page ) {
					$html .= $page['data'];
				}
				$html .= $list['end']['data'];
				$html .= $list['next']['data'];
			}
			else
			{
			//	$html .= '&lt;&lt; ';
				$html .= $list['start']['data'];
			//	$html .= ' &lt; ';
				$html .= $list['previous']['data'];
				foreach( $list['pages'] as $page ) {
					$html .= $page['data'];
				}
				$html .=  $list['next']['data'];
			//	$html .= ' &gt;';
				$html .=  $list['end']['data'];
				//$html .= ' &gt;&gt;';
			}
			$html .= "</ul>";
			return $html;
		}
	
		function _item_active(&$item)
		{
			global $mainframe;
			//better for seo
			if(intval($item->text) > 0){
				$hiddenphone = "hidden-phone";
			}else{
				$hiddenphone = "";
			}
			
			if($item->base>0)
			{
				return "<li class='".$hiddenphone."'><a href=\"javascript: document.ftForm.limitstart.value=".$item->base."; document.ftForm.submit();\" onclick=\"javascript: document.ftForm.limitstart.value=".$item->base."; document.ftForm.submit();\" >".$item->text."</a></li>";
			}
			else
			{
				return "<li class='".$hiddenphone."'><a href=\"javascript: document.ftForm.limitstart.value=".$item->base."; document.ftForm.submit();\" title=\"".$item->text."\" onclick=\"javascript: document.ftForm.limitstart.value=0; document.ftForm.submit();\" >".$item->text."</a></li>";
			}
			
		}
	
		function _item_inactive(&$item)
		{
			global $mainframe;
			if(intval($item->text) > 0){
				$hiddenphone = "hidden-phone";
			}else{
				$hiddenphone = "";
			}
			if ($mainframe->isAdmin()) {
				return "<span>".$item->text."</span>";
			} else {
				return "<li class='active ".$hiddenphone."'><a>" . $item->text . "</a></li>";
			}
		}
	
		/**
		 * Create and return the pagination data object
		 *
		 * @access	public
		 * @return	object	Pagination data object
		 * @since	1.5
		 */
		function _buildDataObject()
		{
			// Initialize variables
			$data = new stdClass();
	
			$data->all	= new OSBJPaginationObject(JText::_('OS_VIEW_ALL'));
			if (!$this->_viewall) {
				$data->all->base	= '0';
				$data->all->link	= JRoute::_("&limitstart=");
			}
	
			// Set the start and previous data objects
			$data->start	= new OSBJPaginationObject(JText::_("OS_START"));
			$data->previous	= new OSBJPaginationObject(JText::_("OS_PREVIOUS"));
	
			if ($this->get('pages.current') > 1)
			{
				$page = ($this->get('pages.current') -2) * $this->limit;
				
				$page = $page == 0 ? '0' : $page; //set the empty for removal from route

				$data->start->base	= '0';
				$data->start->link	= JRoute::_("&limitstart=0");
				$data->previous->base	= $page;
				$data->previous->link	= JRoute::_("&limitstart=".$page);
			}
	
			// Set the next and end data objects
			$data->next	= new OSBJPaginationObject(JText::_("OS_NEXT"));
			$data->end	= new OSBJPaginationObject(JText::_("OS_END"));
	
			if ($this->get('pages.current') < $this->get('pages.total'))
			{
				$next = $this->get('pages.current') * $this->limit;
				$end  = ($this->get('pages.total') -1) * $this->limit;
	
				$data->next->base	= $next;
				$data->next->link	= JRoute::_("&limitstart=".$next);
				$data->end->base	= $end;
				$data->end->link	= JRoute::_("&limitstart=".$end);
			}
	
			$data->pages = array();
			$stop = $this->get('pages.stop');
			for ($i = $this->get('pages.start'); $i <= $stop; $i ++)
			{
				$offset = ($i -1) * $this->limit;
	
				$offset = $offset == 0 ? '0' : $offset;  //set the empty for removal from route
	
				$data->pages[$i] = new OSBJPaginationObject($i);
				if ($i != $this->get('pages.current') || $this->_viewall)
				{
					$data->pages[$i]->base	= $offset;
					$data->pages[$i]->link	= JRoute::_("&limitstart=".$offset);
				}
			}
			return $data;
		}
	}
	
	if (!class_exists('JPaginationClone')){
		class JPaginationClone extends JObject
		{
			/**
			 * The record number to start dislpaying from
			 *
			 * @access public
			 * @var int
			 */
			var $limitstart = null;
		
			/**
			 * Number of rows to display per page
			 *
			 * @access public
			 * @var int
			 */
			var $limit = null;
		
			/**
			 * Total number of rows
			 *
			 * @access public
			 * @var int
			 */
			var $total = null;
		
			/**
			 * View all flag
			 *
			 * @access protected
			 * @var boolean
			 */
			var $_viewall = false;
		
			/**
			 * Constructor
			 *
			 * @param	int		The total number of items
			 * @param	int		The offset of the item to start at
			 * @param	int		The number of items to display per page
			 */
			function __construct($total, $limitstart, $limit)
			{
				// Value/Type checking
				$this->total		= (int) $total;
				$this->limitstart	= (int) max($limitstart, 0);
				$this->limit		= (int) max($limit, 0);
		
				if ($this->limit > $this->total) {
					$this->limitstart = 0;
				}
		
				if (!$this->limit)
				{
					$this->limit = $total;
					$this->limitstart = 0;
				}
		
				if ($this->limitstart > $this->total) {
					$this->limitstart -= $this->limitstart % $this->limit;
				}
		
				// Set the total pages and current page values
				if($this->limit > 0)
				{
					$this->set( 'pages.total', ceil($this->total / $this->limit));
					$this->set( 'pages.current', ceil(($this->limitstart + 1) / $this->limit));
				}
		
				// Set the pagination iteration loop values
				$displayedPages	= 10;
				$this->set( 'pages.start', (floor(($this->get('pages.current') -1) / $displayedPages)) * $displayedPages +1);
				if ($this->get('pages.start') + $displayedPages -1 < $this->get('pages.total')) {
					$this->set( 'pages.stop', $this->get('pages.start') + $displayedPages -1);
				} else {
					$this->set( 'pages.stop', $this->get('pages.total'));
				}
		
				// If we are viewing all records set the view all flag to true
				if ($this->limit == $total) {
					$this->_viewall = true;
				}
			}
		
			/**
			 * Return the rationalised offset for a row with a given index.
			 *
			 * @access	public
			 * @param	int		$index The row index
			 * @return	int		Rationalised offset for a row with a given index
			 * @since	1.5
			 */
			function getRowOffset($index)
			{
				return $index +1 + $this->limitstart;
			}
		
			/**
			 * Return the pagination data object, only creating it if it doesn't already exist
			 *
			 * @access	public
			 * @return	object	Pagination data object
			 * @since	1.5
			 */
			function getData()
			{
				static $data;
				if (!is_object($data)) {
					$data = $this->_buildDataObject();
				}
				return $data;
			}
		
			/**
			 * Create and return the pagination pages counter string, ie. Page 2 of 4
			 *
			 * @access	public
			 * @return	string	Pagination pages counter string
			 * @since	1.5
			 */
			function getPagesCounter()
			{
				// Initialize variables
				$html = null;
				if ($this->get('pages.total') > 1) {
					$html .= JText::_('Page')." ".$this->get('pages.current')." ".JText::_('OS_OF')." ".$this->get('pages.total');
				}
				return $html;
			}
		
			/**
			 * Create and return the pagination result set counter string, ie. Results 1-10 of 42
			 *
			 * @access	public
			 * @return	string	Pagination result set counter string
			 * @since	1.5
			 */
			function getResultsCounter()
			{
				// Initialize variables
				$html = null;
				$fromResult = $this->limitstart + 1;
		
				// If the limit is reached before the end of the list
				if ($this->limitstart + $this->limit < $this->total) {
					$toResult = $this->limitstart + $this->limit;
				} else {
					$toResult = $this->total;
				}
		
				// If there are results found
				if ($this->total > 0) {
					$msg = JText::sprintf('Results of', $fromResult, $toResult, $this->total);
					$html .= "\n".$msg;
				} else {
					$html .= "\n".JText::_('No records found');
				}
		
				return $html;
			}
		
			/**
			 * Create and return the pagination page list string, ie. Previous, Next, 1 2 3 ... x
			 *
			 * @access	public
			 * @return	string	Pagination page list string
			 * @since	1.0
			 */
			function getPagesLinks()
			{
				global $mainframe;
				$lang =& JFactory::getLanguage();
		
				// Build the page navigation list
				$data = $this->_buildDataObject();
		
				$list = array();
		
				$itemOverride = false;
				$listOverride = false;
				
				$itemOverride = false;
				$listOverride = false;
			
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
					$data->start = '<i class="icon-first"></i>';
					$list['start']['data'] = ($itemOverride) ? pagination_item_inactive($data->start) : $this->_item_inactive($data->start);
				}
				if ($data->previous->base !== null) {
					$list['previous']['active'] = true;
					$list['previous']['data'] = ($itemOverride) ? pagination_item_active($data->previous) : $this->_item_active($data->previous);
				} else {
					$list['previous']['active'] = false;
					$data->previous = '<i class="icon-previous"></i>';
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
		
			/**
			 * Return the pagination footer
			 *
			 * @access	public
			 * @return	string	Pagination footer
			 * @since	1.0
			 */
			function getListFooter()
			{
				global $mainframe;
		
				$list = array();
				$list['limit']			= $this->limit;
				$list['limitstart']		= $this->limitstart;
				$list['total']			= $this->total;
				$list['limitfield']		= $this->getLimitBox();
				$list['pagescounter']	= $this->getPagesCounter();
				$list['pageslinks']		= $this->getPagesLinks();
			/*
				$chromePath		= JPATH_THEMES.DS.$mainframe->getTemplate().DS.'html'.DS.'pagination.php';
				if (file_exists( $chromePath ))
				{
					require_once( $chromePath );
					if (function_exists( 'pagination_list_footer' )) {
						return pagination_list_footer( $list );
					}
				}
				*/
				return $this->_list_footer($list);
			}
		
			/**
			 * Creates a dropdown box for selecting how many records to show per page
			 *
			 * @access	public
			 * @return	string	The html for the limit # input box
			 * @since	1.0
			 */
			function getLimitBox()
			{
				global $mainframe;
				// Initialize variables
				$limits = array ();
				// Make the option list
				for ($i = 5; $i <= 30; $i += 5) {
					$limits[] = JHTML::_('select.option', "$i");
				}
				$limits[] = JHTML::_('select.option', '50');
				$limits[] = JHTML::_('select.option', '100');
				$limits[] = JHTML::_('select.option', '0', JText::_('all'));
		
				$selected = $this->_viewall ? 0 : $this->limit;
		
				// Build the select list
				if ($mainframe->isAdmin()) {
					$html = JHTML::_('select.genericlist',  $limits, 'limit', 'class="input-mini" size="1" onchange="submitform();"', 'value', 'text', $selected);
				} else {
					$html = JHTML::_('select.genericlist',  $limits, 'limit', 'class="input-mini" size="1" onchange="document.ftForm1.submit()"', 'value', 'text', $selected);
				}
				return $html;
			}
		
			/**
			 * Return the icon to move an item UP
			 *
			 * @access	public
			 * @param	int		$i The row index
			 * @param	boolean	$condition True to show the icon
			 * @param	string	$task The task to fire
			 * @param	string	$alt The image alternate text string
			 * @return	string	Either the icon to move an item up or a space
			 * @since	1.0
			 */
			function orderUpIcon($i, $condition = true, $task = 'orderup', $alt = 'Move Up', $enabled = true)
			{
				$alt = JText::_($alt);
		
				$html = '&nbsp;';
				if (($i > 0 || ($i + $this->limitstart > 0)) && $condition)
				{
					if($enabled) {
						$html	= '<a href="#reorder" onclick="return listItemTask(\'cb'.$i.'\',\''.$task.'\')" title="'.$alt.'">';
						$html	.= '   <img src="images/uparrow.png" width="16" height="16" border="0" alt="'.$alt.'" />';
						$html	.= '</a>';
					} else {
						$html	= '<img src="images/uparrow0.png" width="16" height="16" border="0" alt="'.$alt.'" />';
					}
				}
		
				return $html;
			}
		
			/**
			 * Return the icon to move an item DOWN
			 *
			 * @access	public
			 * @param	int		$i The row index
			 * @param	int		$n The number of items in the list
			 * @param	boolean	$condition True to show the icon
			 * @param	string	$task The task to fire
			 * @param	string	$alt The image alternate text string
			 * @return	string	Either the icon to move an item down or a space
			 * @since	1.0
			 */
			function orderDownIcon($i, $n, $condition = true, $task = 'orderdown', $alt = 'Move Down', $enabled = true)
			{
				$alt = JText::_($alt);
		
				$html = '&nbsp;';
				if (($i < $n -1 || $i + $this->limitstart < $this->total - 1) && $condition)
				{
					if($enabled) {
						$html	= '<a href="#reorder" onclick="return listItemTask(\'cb'.$i.'\',\''.$task.'\')" title="'.$alt.'">';
						$html	.= '  <img src="images/downarrow.png" width="16" height="16" border="0" alt="'.$alt.'" />';
						$html	.= '</a>';
					} else {
						$html	= '<img src="images/downarrow0.png" width="16" height="16" border="0" alt="'.$alt.'" />';
					}
				}
		
				return $html;
			}
		
			function _list_footer($list)
			{
				// Initialize variables
				$lang =& JFactory::getLanguage();
				$html = "<div class=\"pagination-wrap\">\n";
		
				if ($lang->isRTL()) {
					$html .= "\n<div class=\"counter\">".$list['pagescounter']."</div>";
					$html .= $list['pageslinks'];
					$html .= "\n<div class=\"limit\">".JText::_("Display num").$list['limitfield']."</div>";
				} else {
					$html .= "\n<div class=\"limit\">".JText::_("Display num").$list['limitfield']."</div>";
					$html .= $list['pageslinks'];
					$html .= "\n<div class=\"counter\">".$list['pagescounter']."</div>";
				}
		
				$html .= "\n<input type=\"hidden\" name=\"limitstart\" value=\"".$list['limitstart']."\" />";
				$html .= "\n</div>";
		
				return $html;
			}
		
			function _list_render($list)
			{
				// Initialize variables
				$lang =& JFactory::getLanguage();
				$html = null;
				$html = "<ul>";
				// Reverse output rendering for right-to-left display
				if($lang->isRTL())
				{
					$html .=  $list['previous']['data'];
					$html .= $list['start']['data'];
					$list['pages'] = array_reverse( $list['pages'] );
					foreach( $list['pages'] as $page ) {
						$html .= $page['data'];
					}
					$html .= $list['end']['data'];
					$html .= $list['next']['data'];
				}
				else
				{
				//	$html .= '&lt;&lt; ';
					$html .= $list['start']['data'];
				//	$html .= ' &lt; ';
					$html .= $list['previous']['data'];
					foreach( $list['pages'] as $page ) {
						$html .= ' '.$page['data'];
					}
					$html .= ' '. $list['next']['data'];
				//	$html .= ' &gt;';
					$html .= ' '. $list['end']['data'];
					//$html .= ' &gt;&gt;';
				}
				$html .= "</ul>";
				return $html;
			}
		
			function _item_active(&$item)
			{
				global $mainframe;
					if($item->base>0)
					{
						return "<a href=\"javascript: document.ftForm1.limitstart.value=".$item->base."; document.ftForm1.submit();\" onclick=\"javascript: document.ftForm1.limitstart.value=".$item->base."; document.ftForm1.submit();\" >".$item->text."</a>";
					}
					else
					{
						return "<a href=\"javascript: document.ftForm1.limitstart.value=".$item->base."; document.ftForm1.submit();\" title=\"".$item->text."\" onclick=\"javascript: document.ftForm1.limitstart.value=0; document.ftForm1.submit();\" >".$item->text."</a>";
					}
			}
		
			function _item_inactive(&$item)
			{
				global $mainframe;
				if ($mainframe->isAdmin()) {
					return "<span>".$item->text."</span>";
				} else {
					return "<span class=\"pagenav\">" . $item->text . "</span>";
				}
			}
		
			/**
			 * Create and return the pagination data object
			 *
			 * @access	public
			 * @return	object	Pagination data object
			 * @since	1.5
			 */
			function _buildDataObject()
			{
				// Initialize variables
				$data = new stdClass();
		
				$data->all	= new OSPOSBJPaginationObject(JText::_('View All'));
				if (!$this->_viewall) {
					$data->all->base	= '0';
					$data->all->link	= JRoute::_("&limitstart=");
				}
		
				// Set the start and previous data objects
				$data->start	= new OSBJPaginationObject(JText::_("Start"));
				$data->previous	= new OSBJPaginationObject(JText::_("Previous"));
		
				if ($this->get('pages.current') > 1)
				{
					$page = ($this->get('pages.current') -2) * $this->limit;
		
					$page = $page == 0 ? '' : $page; //set the empty for removal from route
		
					$data->start->base	= '0';
					$data->start->link	= JRoute::_("&limitstart=");
					$data->previous->base	= $page;
					$data->previous->link	= JRoute::_("&limitstart=".$page);
				}
		
				// Set the next and end data objects
				$data->next	= new OSBJPaginationObject(JText::_("Next"));
				$data->end	= new OSBJPaginationObject(JText::_("End"));
		
				if ($this->get('pages.current') < $this->get('pages.total'))
				{
					$next = $this->get('pages.current') * $this->limit;
					$end  = ($this->get('pages.total') -1) * $this->limit;
		
					$data->next->base	= $next;
					$data->next->link	= JRoute::_("&limitstart=".$next);
					$data->end->base	= $end;
					$data->end->link	= JRoute::_("&limitstart=".$end);
				}
		
				$data->pages = array();
				$stop = $this->get('pages.stop');
				for ($i = $this->get('pages.start'); $i <= $stop; $i ++)
				{
					$offset = ($i -1) * $this->limit;
		
					$offset = $offset == 0 ? '' : $offset;  //set the empty for removal from route
		
					$data->pages[$i] = new OSBJPaginationObject($i);
					if ($i != $this->get('pages.current') || $this->_viewall)
					{
						$data->pages[$i]->base	= $offset;
						$data->pages[$i]->link	= JRoute::_("&limitstart=".$offset);
					}
				}
				return $data;
			}
		}
	}
	
	
	/**
	 * Pagination object representing a particular item in the pagination lists
	 *
	 * @author		Louis Landry <louis.landry@joomla.org>
	 * @package 	Joomla.Framework
	 * @subpackage	HTML
	 * @since		1.5
	 */
	
	if (!class_exists('OSBJPaginationObject')){
		class OSBJPaginationObject extends JObject
		{
			var $text;
			var $base;
			var $link;
		
			function __construct($text, $base=null, $link=null)
			{
				$this->text = $text;
				$this->base = $base;
				$this->link = $link;
			}
		}
	}
}