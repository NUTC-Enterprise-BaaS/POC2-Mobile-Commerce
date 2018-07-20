<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.model' );
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'pagination.php');

class FssModelFaq extends JModelLegacy
{
	var $_total = null;

	var $_pagination = null;

	var $_curcatid = 0;

	var $_curcattitle = "";
	var $_curcatimage = "";
	var $_curcatdesc = "";

	var $_search = "";

	var $_catlist = "";
	
	var $_enable_pages = 0;



	function __construct()
	{
		parent::__construct();

		$mainframe = JFactory::getApplication(); global $option;

		// Get pagination request variables
        $aparams = FSS_Settings::GetViewSettingsObj('faqs');
        $this->_enable_pages = $aparams->get('enable_pages',1);
       
        	
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', FSS_Settings::Get('faq_per_page'), 'int');
		if ($this->_enable_pages == 0)
			$limit = 999999;
		
		$limitstart = FSS_Input::getInt('limitstart', 0);

		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);

		$this->_curcatid = FSS_Input::getInt('catid', '');
		$this->_search = FSS_Input::getString('search', '');

		$this->_catlist = $this->_getCatList();

		if ($this->_search != "")
		{
			$this->_curcattitle = JText::_("SEARCH_RESULTS");
			$this->_curcatid = -1;
			$this->_curcatimage = "/components/com_fss/assets/images/search.png";
		} else if ($this->_curcatid == "0") {
			$this->_curcattitle = JText::_("ALL_FAQS");
			$this->_curcatimage = "/components/com_fss/assets/images/allfaqs.png";
		} else {
			foreach ($this->_catlist as $cat)
			{
				if ($cat['id'] == $this->_curcatid)
				{
					$this->_curcattitle = $cat['title'];
					$this->_curcatimage = $cat['image'];
					$this->_curcatdesc = $cat['description'];
				}
			}
		}
	}

 	function &_getCatList( )
    {
        $db = JFactory::getDBO();
        $query = "SELECT * FROM #__fss_faq_cat";
		
		$where = array();
		
		$where[] = 'published = 1';
		
		// add language and access to query where
		$where[] = 'language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
		$user = JFactory::getUser();
		$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
		
		if (count($where) > 0)
			$query .= " WHERE " . implode(" AND ",$where);

		$query .= " ORDER BY ordering";

		$db->setQuery($query);
        $rows = $db->loadAssocList();
        return $rows;
    }
    
    function &getFaq()
    {
		$db = JFactory::getDBO();
		$faqid = FSS_Input::getInt('faqid', 0);
        $query = "SELECT f.id, f.question, f.answer, f.fullanswer, f.published, f.ordering, c.title, f.faq_cat_id, f.author, f.featured FROM #__fss_faq_faq as f LEFT JOIN #__fss_faq_cat as c ON f.faq_cat_id = c.id";
		
		$where = array();
		$where[] = "f.id = " . FSSJ3Helper::getEscaped($db, $faqid);
		$where[] = 'f.language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
		$user = JFactory::getUser();
		$where[] = 'f.access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
		
		if (FSS_Permission::auth("core.edit", "com_fss.faq")) // we have editor so can see all unpublished arts
		{
			
		} else if (FSS_Permission::auth("core.edit.own", "com_fss.faq")){
			$where[] = " ( f.published = 1 OR f.author = {$this->content->userid} ) ";	
		} else {
			$where[] = "f.published = 1";	
		}
		$query .= " WHERE " . implode(" AND ",$where);
		
		//echo $query."<br>";
		
        $db->setQuery($query);
        $rows = $db->loadAssoc();
        return $rows;        
    }

   	function _buildQuery()
	{
		$db = JFactory::getDBO();
		$query = "SELECT * FROM #__fss_faq_faq";
		$where = array();

		if ($this->_search != "")
		{
			$mode = "";
			if (FSS_Helper::contains($this->_search, array('*', '+', '-', '<', '>', '(', ')', '~', '"')))
				$mode = "IN BOOLEAN MODE";
			$search_sql = "MATCH (question, answer) AGAINST ('" . $db->escape($this->_search) . "' $mode)";
			
			if (FSS_Settings::get('search_extra_like'))
			{
				$search_sql = " ( " . $search_sql . " OR ";
				
				$words = explode(" ", $this->_search);
				$wsearch = array();
				foreach ($words as $word)
				{
					$word = trim($word);
					if (!$word) continue;
					
					$wsearch[] = " question LIKE ('%" . $db->escape($word) . "%') OR answer LIKE ('%" . $db->escape($word) . "%') ";
				}			
				$search_sql .= implode(" OR ", $wsearch);
				$search_sql .= " ) ";
			}

			$where[] = $search_sql;
						
		} else if ($this->_curcatid > 0)
		{
			$where[] = "faq_cat_id = '" . FSSJ3Helper::getEscaped($db, $this->_curcatid) . "'";
		}
		
		if ($this->_curcatid == -5)
		{
			$where[] = "featured = 1";		
		}
		
		if (FSS_Permission::auth("core.edit", "com_fss.faq")) // we have editor so can see all unpublished arts
		{
			
		} else if (FSS_Permission::auth("core.edit.own", "com_fss.faq")){
			$where[] = " ( published = 1 OR author = {$this->content->userid} ) ";	
		} else {
			$where[] = "published = 1";	
		}
			
		$where[] = 'language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
		$user = JFactory::getUser();
		$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				

		$tag = FSS_Input::getString('tag');
		if ($tag)
		{
			$qry2 = "SELECT faq_id FROM #__fss_faq_tags WHERE tag = '".FSSJ3Helper::getEscaped($db, $tag)."'";
			$db->setQuery($qry2);
			$rows = $db->loadObjectList();
			$ids = array();
			
			foreach ($rows as &$row) 
				$ids[] = $row->faq_id;
				
			if (count($ids) > 0)
				$where[] = "id IN (" . implode(", ", $ids). ")";
			else	
				$where[] = "id = 0";
		}
		
		if (count($where) > 0)
			$query .= " WHERE " . implode(" AND ",$where);

		$query .= " ORDER BY ordering";

		//echo $query."<br>";
		return $query;
	}

	function &getData()
	{
        // if data hasn't already been obtained, load it
        if (empty($this->_data)) {
            $query = $this->_buildQuery();
			//echo $query."<bR>";
            if ($this->_enable_pages)
            {
				$this->_db->setQuery( $query, $this->getState('limitstart'), $this->getState('limit') );
			} else {
				$this->_db->setQuery( $query );
			}
			$this->_data = $this->_db->loadAssocList();
        }

		/*echo "<pre>";
		print_r($this->_data);
		echo "</pre>";*/

        return $this->_data;
	}
	
	function getFeaturedFaqs()
	{
		// load all featured faqs
		$qry = "SELECT * FROM #__fss_faq_faq WHERE published = 1 AND featured = 1";
		$this->_db->setQuery($qry);
		return $this->_db->loadAssocList();
	}
	
	function &getAllData()
	{
    	$query = $this->_buildQuery();
		//echo $query."<br>";
		$this->_db->setQuery( $query );
		//echo $query."<br>";
		$blar = $this->_db->loadAssocList();
		/*echo "<pre>";
		print_r($blar);
		echo "</pre>";*/
		return $blar;
	}

	function getTotal()
	{
        // Load the content if it doesn't already exist
        if (empty($this->_total)) {
            $query = $this->_buildQuery();
            $this->_total = $this->_getListCount($query);
        }
        return $this->_total;
	}

	function &getPagination()
	{
        // Load the content if it doesn't already exist
        if (empty($this->_pagination)) {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPaginationEx($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
        }
        return $this->_pagination;
	}

	function getSearch()
	{
		return $this->_search;
	}

	function getCurCatID()
	{
		return $this->_curcatid;
	}

	function getCurCatTitle()
	{
		return $this->_curcattitle;
	}

	function getCurCatImage()
	{
		return $this->_curcatimage;
	}

	function getCurCatDesc()
	{
		return $this->_curcatdesc;
	}

	function getCatList( )
	{
		return $this->_catlist;
	}

}

