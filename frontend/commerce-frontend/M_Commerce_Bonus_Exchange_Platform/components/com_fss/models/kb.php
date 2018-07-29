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

class FssModelKb extends JModelLegacy
{
    var $_total = null;
    var $_pagination = null;
    var $_curcatid = 0;
    var $_curcattitle = "";
    var $_search = "";
    var $_catlist = "";
	var $_art = null;

    function __construct()
	{
		parent::__construct();
		$mainframe = JFactory::getApplication(); global $option;

		$aparams = FSS_Settings::GetViewSettingsObj('kb');
		$this->_enable_prod_pages = $aparams->get('main_prod_pages',0);
		
		if ($this->_enable_prod_pages == 1)
		{
			$limit = $mainframe->getUserStateFromRequest('global.list.limit_prod', 'limit', FSS_Settings::Get('kb_prod_per_page'), 'int');

			$limitstart = FSS_Input::getInt('limitstart');

			// In case limit has been changed, adjust it
			$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

			$this->setState('limit_prod', $limit);
			$this->setState('limitstart', $limitstart);
		}

		$this->_enable_art_pages = $aparams->get('cat_art_pages',0);
		
		if ($this->_enable_art_pages == 1)
		{
			$limit = $mainframe->getUserStateFromRequest('global.list.limit_art', 'limit', FSS_Settings::Get('kb_art_per_page'), 'int');

			$limitstart = FSS_Input::getInt('limitstart');

			// In case limit has been changed, adjust it
			$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

			$this->setState('limit_art', $limit);
			$this->setState('limitstart', $limitstart);
		}
		//$aparams = new stdClass();
	}
    
    function &getProducts()
    {
		// if data hasn't already been obtained, load it
		if (empty($this->_data)) {
			$query = $this->_buildProdQuery();
			if ($this->_enable_prod_pages)
			{
				$this->_db->setQuery( $query, $this->getState('limitstart'), $this->getState('limit_prod') );
			} else {
				$this->_db->setQuery( $query );
			}
			$this->_data = $this->_db->loadAssocList();
		}
		return $this->_data;
	}
	
	function getProdLimit()
	{
		return $this->getState('limit_prod');
	}
	
	function _buildProdQuery()
	{
		$db = JFactory::getDBO();
		$search = FSS_Input::getString('prodsearch');  
		if ($search == "__all__" || $search == '')
		{
			$query = "SELECT * FROM #__fss_prod";
			
			$where = array();
			$where[] = "published = 1";
			$where[] = "inkb = 1";
			$where[] = 'access IN (' . implode(',', JFactory::getUser()->getAuthorisedViewLevels()) . ')';				
			
			if (count($where) > 0)
				$query .= " WHERE " . implode(" AND ",$where);

			$query .= " ORDER BY ordering";
		} else {
			$query = "SELECT * FROM #__fss_prod";
			
			$where = array();
			$where[] ="published = 1";
			$where[] = "inkb = 1";
			$where[] = "title LIKE '%".FSSJ3Helper::getEscaped($db, $search)."%'";
			$where[] = 'access IN (' . implode(',', JFactory::getUser()->getAuthorisedViewLevels()) . ')';					
		
			if (count($where) > 0)
				$query .= " WHERE " . implode(" AND ",$where);

			$query .= " ORDER BY ordering";
		}

		return $query;        
	}
	
	function getTotalProducts()
	{
		if (empty($this->_prodtotal)) {
			$query = $this->_buildProdQuery();
			$this->_prodtotal = $this->_getListCount($query);
		}
		return $this->_prodtotal;		
	}

	function &getProdPagination()
	{
		// Load the content if it doesn't already exist
		if (empty($this->_pagination)) {
			$this->_pagination = new JPaginationAjax($this->getTotalProducts(), $this->getState('limitstart'), $this->getState('limit_prod') );
		}
		return $this->_pagination;
	}	
	 
    function &getCats()
    {
		$prodid = FSS_Input::getInt('prodid');
        $db = JFactory::getDBO();
        
        if ($prodid > 0)
        {
        	$query1 = "SELECT * FROM #__fss_kb_cat WHERE published = 1 AND id IN (";
        	$query1 .= "SELECT a.kb_cat_id FROM #__fss_kb_art as a LEFT JOIN #__fss_kb_art_prod as p ON a.id = p.kb_art_id WHERE p.prod_id = '" . FSSJ3Helper::getEscaped($db, $prodid) . "'";
			$query1 .= ' AND a.language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ') ';
			$query1 .= ' AND a.access IN (' . implode(',', JFactory::getUser()->getAuthorisedViewLevels()) . ') ';				
			$query1 .= " GROUP BY a.kb_cat_id";
        	$query1 .= ")";

			$query2 = "SELECT * FROM #__fss_kb_cat WHERE published = 1 AND id IN (";
			$query2 .= "SELECT a.kb_cat_id FROM #__fss_kb_art as a WHERE a.allprods = '1'";
			$query2 .= ' AND a.language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ') ';
			$query2 .= ' AND a.access IN (' . implode(',', JFactory::getUser()->getAuthorisedViewLevels()) . ') ';				
			$query2 .= " GROUP BY a.kb_cat_id";
			$query2 .= ")";
			
	 		//echo $query1."<br>";
			//echo $query2."<br>";
			
			$query = "(" . $query1 . ") UNION (" . $query2 . ") ORDER BY ordering";
			//$query = $query2;	
		} else {
            $query = "SELECT * FROM #__fss_kb_cat WHERE published = 1";
			$query .= ' AND language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ') ';
			$query .= ' AND access IN (' . implode(',', JFactory::getUser()->getAuthorisedViewLevels()) . ') ';				
			$query .= " ORDER BY ordering";
		}
		//echo $query."<br>";
		
        $db->setQuery($query);
        $rows = $db->loadAssocList();
        return $rows;        
	}    
	
	function &getCatsForProd()
	{
		$prodid = FSS_Input::getInt('prodid');
    	$db = JFactory::getDBO();
		if ($prodid > 0)
		{
			$qry1 = "SELECT a.kb_cat_id FROM #__fss_kb_art as a LEFT JOIN #__fss_kb_art_prod as p ON a.id = p.kb_art_id WHERE p.prod_id = '" . FSSJ3Helper::getEscaped($db, $prodid) . "' AND published = 1";
			$qry1 .= ' AND a.language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ') ';
			$qry1 .= ' AND a.access IN (' . implode(',', JFactory::getUser()->getAuthorisedViewLevels()) . ') ';				
			$qry1 .= " GROUP BY a.kb_cat_id";
			
			$qry2 = "SELECT a.kb_cat_id FROM #__fss_kb_art as a WHERE a.allprods = '1' AND published = 1 ";
			$qry2 .= ' AND language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ') ';
			$qry2 .= ' AND access IN (' . implode(',', JFactory::getUser()->getAuthorisedViewLevels()) . ') ';				
			$qry2 .= " GROUP BY a.kb_cat_id";
			
			$query = "($qry1) UNION ($qry2)";
			$db->setQuery($query);

			$rows = $db->loadAssocList('kb_cat_id');
			$catids = array();
			foreach($rows as &$rows)
			{
				$catids[$rows['kb_cat_id']] = $rows['kb_cat_id'];
			}

			if (count($catids) > 0)
			{
				$query = "SELECT parcatid FROM #__fss_kb_cat WHERE id IN (".implode(", ",$catids).") AND parcatid > 0";
				$db->setQuery($query);
				$rows = $db->loadAssocList();
				foreach($rows as &$rows)
				{
					$catids[$rows['parcatid']] = $rows['parcatid'];
				}
			}

			$query = "SELECT * FROM #__fss_kb_cat WHERE published = 1";
			if (count($catids) > 0)
			{
				$query .= " AND id IN (".implode(", ",$catids) . ")";
			} else {
				$query .= " AND 0 ";
			}
			$query .= ' AND language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ') ';
			$query .= ' AND access IN (' . implode(',', JFactory::getUser()->getAuthorisedViewLevels()) . ') ';				
			
			$query .= " ORDER BY ordering";
		} else {
			$query = "SELECT * FROM #__fss_kb_cat WHERE published = 1";
			$query .= ' AND language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ') ';
			$query .= ' AND access IN (' . implode(',', JFactory::getUser()->getAuthorisedViewLevels()) . ') ';				
			$query .= " ORDER BY ordering";
		}
		
	    $db->setQuery($query);
        $rows = $db->loadAssocList();
        return $rows;        
	}
    
    function &getCatsArts()
    {
		//echo "CAT ARTS<br>";
    	//$cats = $this->getCats();
    	
		// get all categories that are relevant to the product
		$cats = $this->getCatsForProd();

		$prodid = FSS_Input::getInt('prodid');
    	$db = JFactory::getDBO();

		$where = array();
		if (FSS_Permission::auth("core.edit", "com_fss.kb")) // we have editor so can see all unpublished arts
		{
			
		} else if (FSS_Permission::auth("core.edit.own", "com_fss.kb")){
			$where[] = " ( published = 1 OR author = {$this->content->userid} ) ";	
		} else {
			$where[] = "published = 1";	
		}

        if ($prodid > 0)
		{
			$query1 = "SELECT a.id, a.title, a.kb_cat_id, a.ordering, a.published, a.author FROM #__fss_kb_art as a LEFT JOIN #__fss_kb_art_prod as p ON a.id = p.kb_art_id WHERE p.prod_id = '" . FSSJ3Helper::getEscaped($db, $prodid) . "'";
			$query1 .= ' AND a.language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ') ';
			$query1 .= ' AND a.access IN (' . implode(',', JFactory::getUser()->getAuthorisedViewLevels()) . ') ';				
			
			$query2 = "SELECT a.id, a.title, a.kb_cat_id, a.ordering, a.published, a.author FROM #__fss_kb_art as a WHERE a.allprods = '1'";
			$query2 .= ' AND a.language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ') ';
			$query2 .= ' AND a.access IN (' . implode(',', JFactory::getUser()->getAuthorisedViewLevels()) . ') ';				
			
			if (count($where) > 0)
				$query1 .= " AND " . implode(" AND ",$where);
			if (count($where) > 0)
				$query2 .= " AND " . implode(" AND ",$where);
			
			$query = "(" . $query1 . ") UNION (" . $query2 . ") ORDER BY ordering";
		} else {
			$query = "SELECT a.id, a.title, a.kb_cat_id, a.ordering, a.published, a.author FROM #__fss_kb_art as a";
			$where[] = 'language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
			$where[] = 'access IN (' . implode(',', JFactory::getUser()->getAuthorisedViewLevels()) . ')';				
			
			if (count($where) > 0)
				$query .= " WHERE " . implode(" AND ",$where);
		
			$query .= " ORDER BY ordering";	
		}

        $db->setQuery($query);
        $rows = $db->loadAssocList();
        
        
        foreach ($rows as &$row)
        {
        	$catid = $row['kb_cat_id'];	
        	
        	foreach ($cats as &$cat)
        	{
        		if ($cat['id'] == $catid)
        		{
        			$cat['arts'][] = $row;
        			break;
				}
			}
		}
       
		$notinbase = array();
		foreach($cats as $key => &$cat)
		{
			$pid = $cat['parcatid'];
			if ($pid != 0)
			{
				$foundid = 0;
				foreach($cats as $fid => &$pcat)
				{
					if ($pcat['id'] == $pid)
					{
						$foundid = $fid;
						break;		
					}
				}
				//echo "Putting $pid ({$cat['title']}) into $foundid ({$cats[$foundid]['title']})<br>";
				
				if (!array_key_exists($foundid, $cats))
					continue;
					
				if (!array_key_exists("subcats",$cats[$foundid]))
					$cats[$foundid]["subcats"] = array();
					
				$cats[$foundid]["subcats"][] = &$cat;
				$notinbase[$key] = $key;
			}
		}
		
		// if we have a cat id set, then we need to return a list of cats that live inside the current cat id
		$curcatid = FSS_Input::getInt('catid');
		if ($curcatid > 0)
		{
			foreach($cats as &$cat)
			{
				if ($cat['id'] == $curcatid)
					return $cat['subcats'];		
			}
		}
		
		foreach($notinbase as $id)
		{
			unset($cats[$id]);	
		}
		
		/*print "<pre>";
        print_r($cats);
        print "</pre>";*/
        return $cats;
	}
	
	function &getUncatArts()
	{
        $db = JFactory::getDBO();
		$prodid = FSS_Input::getInt('prodid');
		
		$where = array();
		if (FSS_Permission::auth("core.edit", "com_fss.kb")) // we have editor so can see all unpublished arts
		{
			
		} else if (FSS_Permission::auth("core.edit.own", "com_fss.kb")){
			$where[] = " ( published = 1 OR author = {$this->content->userid} ) ";	
		} else {
			$where[] = "published = 1";	
		}

		if ($prodid > 0)
		{
			$query1 = "SELECT a.id, a.title, a.kb_cat_id, a.ordering, a.published, a.author FROM #__fss_kb_art as a LEFT JOIN #__fss_kb_art_prod as p ON a.id = p.kb_art_id WHERE p.prod_id = '" . FSSJ3Helper::getEscaped($db, $prodid) . "' AND kb_cat_id = 0";
			$query1 .= ' AND a.language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ') ';
			$query1 .= ' AND a.access IN (' . implode(',', JFactory::getUser()->getAuthorisedViewLevels()) . ') ';				
			
			$query2 = "SELECT a.id, a.title, a.kb_cat_id, a.ordering, a.published, a.author FROM #__fss_kb_art as a WHERE a.allprods = '1' AND kb_cat_id = 0";
			$query2 .= ' AND a.language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ') ';
			$query2 .= ' AND a.access IN (' . implode(',', JFactory::getUser()->getAuthorisedViewLevels()) . ') ';				
			
			if (count($where) > 0)
				$query1 .= " AND " . implode(" AND ",$where);
			if (count($where) > 0)
				$query2 .= " AND " . implode(" AND ",$where);
			$query = "(" . $query1 . ") UNION (" . $query2 . ") ORDER BY ordering";
		} else {
			$query = "SELECT a.id, a.title, a.kb_cat_id, a.ordering, a.published, a.author FROM #__fss_kb_art as a WHERE kb_cat_id = 0";
			$where[] = 'language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
			$where[] = 'access IN (' . implode(',', JFactory::getUser()->getAuthorisedViewLevels()) . ')';				
			
			if (count($where) > 0)
				$query .= " AND " . implode(" AND ",$where);
			$query .= " ORDER BY ordering";
		}
		
		//echo $query."<br>";
        $db->setQuery($query);

        $rows = $db->loadAssocList();
		
		return $rows;        
	}	
    
    function &getArticle()
    {
        $db = JFactory::getDBO();
		$kbartid = FSS_Input::getInt('kbartid');
        $query = "SELECT f.id, f.title, f.body, f.kb_cat_id, c.title as cattile, f.allprods, f.created, f.modified, f.published, f.author FROM #__fss_kb_art as f LEFT JOIN #__fss_kb_cat as c ON f.kb_cat_id = c.id";
		
		$where = array();
		$where[] = "f.id = " . FSSJ3Helper::getEscaped($db, $kbartid);
		$where[] = 'f.language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
		$where[] = 'f.access IN (' . implode(',', JFactory::getUser()->getAuthorisedViewLevels()) . ')';				
		
		if (FSS_Permission::auth("core.edit", "com_fss.kb")) // we have editor so can see all unpublished arts
		{
			
		} else if (FSS_Permission::auth("core.edit.own", "com_fss.kb")) {
			$where[] = " ( f.published = 1 OR f.author = {$this->content->userid} ) ";	
		} else {
			$where[] = "f.published = 1";	
		}

		$query .= " WHERE " . implode(" AND ", $where);
		

        $db->setQuery($query);
        $rows = $db->loadAssoc();
		$this->_art = $rows;
        return $rows;        
    }
    
    function getProduct()
    {
        $db = JFactory::getDBO();
		$prodid = FSS_Input::getInt('prodid');
		if (!$prodid)
			return null;
		
        $query = "SELECT * FROM #__fss_prod";
		
		$where[] = "id = " . FSSJ3Helper::getEscaped($db, $prodid);
		$where[] = 'access IN (' . implode(',', JFactory::getUser()->getAuthorisedViewLevels()) . ')';				
		
		$query .= " WHERE " . implode(" AND ", $where);
		
        $db->setQuery($query);
        $rows = $db->loadAssoc();
        return $rows;        
    }
    
    function getCat()
    {
        $db = JFactory::getDBO();
		$catid = FSS_Input::getInt('catid');
		if (!$catid)
			return null;
        $query = "SELECT * FROM #__fss_kb_cat";
		
		$where[] = "id = " . FSSJ3Helper::getEscaped($db, $catid);
		$where[] = 'language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
		$where[] = 'access IN (' . implode(',', JFactory::getUser()->getAuthorisedViewLevels()) . ')';					
		
		$query .= " WHERE " . implode(" AND ", $where);
		

        $db->setQuery($query);
        $rows = $db->loadAssoc();
		return $rows;        
    }
   
    function &getArts()
    {
		// if data hasn't already been obtained, load it
		if (empty($this->_arts)) {
			$query = $this->_buildArtQuery();
			if ($this->_enable_art_pages)
			{
				$this->_db->setQuery( $query, $this->getState('limitstart'), $this->getState('limit_art') );
			} else {
				$this->_db->setQuery( $query );
			}
			$this->_arts = $this->_db->loadAssocList();
		}
		return $this->_arts;
	}
	
    function &getArtsWhat()
    {
		$db = JFactory::getDBO();
		// if data hasn't already been obtained, load it
		if (empty($this->_arts)) {
			
			// get allowed category list!
			$allowed_cats = $this->getCatsForProd();		
			$allowed_cat_ids = array();
			foreach ($allowed_cats as $cat)
				$allowed_cat_ids[] = $cat['id'];
			$allowed_cat_ids[] = 0;
			
			$catid = FSS_Input::getInt('catid');
			$prodid = FSS_Input::getInt('prodid');
			$search = FSS_Input::getString('kbsearch', '');  
			$what = FSS_Input::getCmd('what', '');  

			$search_term = "";
			
			if ($search)
			{
				$mode = "";
				if (FSS_Helper::contains($search, array('*', '+', '-', '<', '>', '(', ')', '~', '"')))
				$mode = "IN BOOLEAN MODE";
				$search_term = "MATCH (title, body) AGAINST ('" . $db->escape($search) . "' $mode)";
			}

			$query1 = "SELECT a.*";
			if ($search)
			$query1 .= ", " . $search_term . " as score ";
			$query1 .= " FROM #__fss_kb_art as a WHERE 1 ";
			
			$catlist = array();

			if ($catid > 0)
			{
				// need to get a list of cats that are under the current one
				$cats = $this->getCatsForProd();
				$catlist[$catid] = $catid;
				
				$count = 0;
				$listcount = count($catlist);
				$runs = 0;
				//echo "Searching for subcats in " . implode(" ",$catlist) . "<br>";
				while ($count != $listcount && $runs < 10)
				{
					$runs++;
					$count = $listcount;
					foreach ($cats as &$cat)
					{
						$pid = $cat['parcatid'];
						//echo "Cat {$cat['title']} ({$cat['id']}) - Parent : $pid<br>";
						if ($pid == 0)
						continue;
						
						// is parent id in the current cat list
						if (array_key_exists($pid, $catlist))
						{
							//echo "Cat {$cat['id']} has parid $pid<br>";
							$catlist[$cat['id']] = $cat['id'];
						}
					}
					$listcount = count($catlist);
					//echo "Ending list : " .implode(" ",$catlist)."<br>";
				}
				
				//print_p($catlist);
				
				//if (count($catlist) == 0)
				$catlist[] = 0;		
				
				$query1 .= " AND kb_cat_id IN (" . implode(", ",$catlist) . ")";
			}
			
			if (count($allowed_cat_ids) == 0)
			$allowed_cat_ids[] = 0;
			
			if ($prodid > 0)
			$query1 .= " AND a.id IN (SELECT kb_art_id FROM #__fss_kb_art_prod WHERE prod_id = " . FSSJ3Helper::getEscaped($db, $prodid) . ") ";
			
			if (FSS_Settings::get('search_extra_like'))
			{
				$search_term = " ( " . $search_term . " OR ";
				
				$words = explode(" ", $search);
				$wsearch = array();
				foreach ($words as $word)
				{
					$word = trim($word);
					if (!$word) continue;
					
					$wsearch[] = " title LIKE ('%" . $db->escape($word) . "%') OR body LIKE ('%" . $db->escape($word) . "%') ";
				}			
				$search_term .= implode(" OR ", $wsearch);
				$search_term .= " ) ";
			}
				
			if ($search != '')
			{
				$query1 .= " AND ". $search_term;
			}
			
			$query1 .= " AND a.allprods = 0 AND published = 1 ";
			$query1 .= ' AND language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ') ';
			$query1 .= ' AND access IN (' . implode(',', JFactory::getUser()->getAuthorisedViewLevels()) . ') ';				
			
			$query1 .= " AND kb_cat_id IN (" . implode(", ", $allowed_cat_ids) . ") ";
			
			$query2 = "SELECT a.*";
			if ($search)
			$query2 .= ", " . $search_term . " as score ";
			$query2 .= " FROM #__fss_kb_art as a WHERE 1 ";
			
			if ($catid > 0)
			{
				$query2 .= " AND kb_cat_id IN (" . implode(", ",$catlist) . ")";
			}
			
			if ($search != '')
			{
				$query2.= " AND " . $search_term;
			}
			
			$query2 .= " AND a.allprods = 1 AND published = 1 ";
			$query2 .= ' AND language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ') ';
			$query2 .= ' AND access IN (' . implode(',', JFactory::getUser()->getAuthorisedViewLevels()) . ') ';				
			$query2 .= " AND kb_cat_id IN (" . implode(", ", $allowed_cat_ids) . ") ";
			
			$query = "(\n" . $query1 . "\n) UNION (\n" . $query2 . "\n)\n ";
			
			if ($what == "recent")
			{
				$query .= " ORDER BY modified DESC ";
			} else if ($what == "viewed")
			{
				$query .= " ORDER BY views DESC ";
			} else if ($what == "rated")
			{
				$query .= " ORDER BY rating DESC ";
			} else {
				$query .= " ORDER BY score DESC ";
			}
			//print_p($query) . "<br>";
			
			$this->_db->setQuery( $query, 0, 20 );
			
			$this->_arts = $this->_db->loadAssocList();
			
			//print_p($this->_arts);
			
		}
		return $this->_arts;
	}
	
	function getArtLimit()
	{
		return $this->getState('limit_art');
	}
	
	function _buildArtQuery()
	{
 		$db = JFactory::getDBO();
		$catid = FSS_Input::getInt('catid');
		$prodid = FSS_Input::getInt('prodid');
		$search = FSS_Input::getString('kbsearch');  

		$query1 = "SELECT a.* FROM #__fss_kb_art as a WHERE 1 ";
		
		if ($catid > 0)
		 $query1 .= " AND kb_cat_id = " . FSSJ3Helper::getEscaped($db, $catid);
        
        if ($prodid > 0)
        	$query1 .= " AND a.id IN (SELECT kb_art_id FROM #__fss_kb_art_prod WHERE prod_id = " . FSSJ3Helper::getEscaped($db, $prodid) . ") ";
        	
			
		// stuff to show extra arts when have edit permission
		$where = array();
		if (FSS_Permission::auth("core.edit", "com_fss.kb")) // we have editor so can see all unpublished arts
		{
			
		} else if (FSS_Permission::auth("core.edit.own", "com_fss.kb")){
			$where[] = " ( published = 1 OR author = {$this->content->userid} ) ";	
		} else {
			$where[] = "published = 1";	
		}
		
		$where[] = 'language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
		$where[] = 'access IN (' . implode(',', JFactory::getUser()->getAuthorisedViewLevels()) . ')';				
		
		$mode = "";
		if (FSS_Helper::contains($search, array('*', '+', '-', '<', '>', '(', ')', '~', '"'))) $mode = "IN BOOLEAN MODE";
		$search_sql = "MATCH (title, body) AGAINST ('" . $db->escape($search) . "' $mode)";
	
		if ($search != '')
		{
			if (FSS_Settings::get('search_extra_like'))
			{
				$new = " ( " . $search_sql . " OR ";
				
				$words = explode(" ", $search);
				$wsearch = array();
				foreach ($words as $word)
				{
					$word = trim($word);
					if (!$word) continue;
					
					$wsearch[] = " title LIKE ('%" . $db->escape($word) . "%') OR body LIKE ('%" . $db->escape($word) . "%') ";
				}			
				$new .= implode(" OR ", $wsearch);
				$new .= " ) ";
				
				$where[] = $new;
			} else {
				$where[] = $search_sql;
			}
		}			
				
		if (count($where) > 0)
			$query1 .= " AND " . implode(" AND ",$where);

		
		$query2 = "SELECT a.* FROM #__fss_kb_art as a WHERE 1 ";
		if ($catid > 0)
			$query2 .= " AND kb_cat_id = " . FSSJ3Helper::getEscaped($db, $catid);
			
		if ($search != '') $query2 .= " AND $search_sql ";
		
		$query2 .= " AND a.allprods = 1";// AND published = 1 ";
		if (count($where) > 0)
			$query2 .= " AND " . implode(" AND ",$where);

		if ($prodid > 0)
		{
			if ($search)
			{
				$query = "(" . $query1 . ") UNION (" . $query2 . ")";
			} else {
				$query = "(" . $query1 . ") UNION (" . $query2 . ") ORDER BY ordering";
			}
		} else {
			if ($search)
			{
				$query = $query1;
			} else {
				$query = $query1 . " ORDER BY ordering";
			}
		}
		//echo $query."<br>";
		return $query;        
	}
	
	function getTotalArts()
	{
		if (empty($this->_arttotal)) {
			$query = $this->_buildArtQuery();
			$this->_arttotal = $this->_getListCount($query);
		}
		return $this->_arttotal;		
	}

	function &getArtPagination()
	{
		// Load the content if it doesn't already exist
		if (empty($this->_pagination)) {
			$this->_pagination = new JPaginationEx($this->getTotalArts(), $this->getState('limitstart'), $this->getState('limit_art') );
		}
		return $this->_pagination;
	}	 

	function &getArtPaginationSearch()
	{
		// Load the content if it doesn't already exist
		if (empty($this->_pagination)) {
			$this->_pagination = new JPaginationAjax($this->getTotalArts(), $this->getState('limitstart'), $this->getState('limit_art') );
		}
		return $this->_pagination;
	}	 


    function &getArticleAttach()
    {
        $db = JFactory::getDBO();
		$kbartid = FSS_Input::getInt('kbartid');
		$query = "SELECT * FROM #__fss_kb_attach WHERE kb_art_id = " . FSSJ3Helper::getEscaped($db, $kbartid) . " ORDER BY ordering, title";
        
        $db->setQuery($query);
        $rows = $db->loadAssocList();
        return $rows;        
    }
    
    function &getAppliesTo()
    {
        $db = JFactory::getDBO();
		$kbartid = FSS_Input::getInt('kbartid');
        $query = "SELECT p.* FROM #__fss_kb_art_prod as ap LEFT JOIN #__fss_prod as p ON ap.prod_id = p.id WHERE p.published = 1 AND p.inkb = 1 AND ap.kb_art_id = " . FSSJ3Helper::getEscaped($db, $kbartid);
		$query .= ' AND p.access IN (' . implode(',', JFactory::getUser()->getAuthorisedViewLevels()) . ')';				
		        
        $db->setQuery($query);
        $rows = $db->loadAssocList();
		FSS_Translate_Helper::Tr($rows);
		
		if ($this->_art['allprods'] > 0)
		{
			$allprod = array();
			$allprod['title'] = JText::_("ALL_PRODUCTS");
			$rows[] = $allprod;
		}
				
		return $rows;        
	}
	
    function &getRelated()
    {
        $db = JFactory::getDBO();
		$kbartid = FSS_Input::getInt('kbartid');
        $query = "SELECT a.id, a.title FROM #__fss_kb_art_related as r LEFT JOIN #__fss_kb_art as a ON r.related_id = a.id WHERE a.published = 1 AND r.kb_art_id = " . FSSJ3Helper::getEscaped($db, $kbartid) . " ORDER BY a.title";
 		$query .= ' AND a.language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ') ';
		$query .= ' AND a.access IN (' . implode(',', JFactory::getUser()->getAuthorisedViewLevels()) . ') ';				
		
        $db->setQuery($query);
        $rows = $db->loadAssocList();
		return $rows;        
	}
	
	function &getSubCats()
	{
		$catid = FSS_Input::getInt('catid');
		if ($catid == 0)
			return array();
			
        $db = JFactory::getDBO();
        $query = "SELECT * FROM #__fss_kb_cat WHERE parcatid = ".FSSJ3Helper::getEscaped($db, $catid);
		$query .= ' AND language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ') ';
		$query .= ' AND access IN (' . implode(',', JFactory::getUser()->getAuthorisedViewLevels()) . ') ';				
		
		$query .= " ORDER BY ordering";
        
	    $db->setQuery($query);
        $rows = $db->loadAssocList();		
		return $rows;   		
	}
}

