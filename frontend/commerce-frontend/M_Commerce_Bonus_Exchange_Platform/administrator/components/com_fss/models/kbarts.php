<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.model' );



class FsssModelKbarts extends JModelLegacy
{
    
    var $_data;

	var $_total = null;

	var $lists = array(0);

	var $_pagination = null;

    function __construct()
	{
        parent::__construct();

        $mainframe = JFactory::getApplication(); global $option;
		$context = "kb_arts_";

        // Get pagination request variables
		$layout = JRequest::getString('layout');

		if ($layout == 'pick')
		{
			$limit = $mainframe->getUserStateFromRequest('global.list.limitpick', 'limit', 10, 'int');
		} else {
			$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		}
		$limitstart = $mainframe->getUserStateFromRequest($context.'.list.limitstart', 'limitstart', 0, 'int');

 		$search	= $mainframe->getUserStateFromRequest( $context.'search', 'search',	'',	'string' );
		$search	= JString::strtolower($search);
		$filter_order		= $mainframe->getUserStateFromRequest( $context.'filter_order',		'filter_order',		'',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',	'filter_order_Dir',	'',	'word' );
		$filter_kb_cat_id	= $mainframe->getUserStateFromRequest( $context.'filter_faqcatid',	'kb_cat_id',	0,	'int' );
		$filter_prod_id	= $mainframe->getUserStateFromRequest( $context.'filter_prodid',	'prod_id',	0,	'int' );
		$ispublished	= $mainframe->getUserStateFromRequest( $context.'filter_ispublished',	'ispublished',	-1,	'int' );
		if (!$filter_order)
			$filter_order = "k.ordering";

		$this->lists['order_Dir']	= $filter_order_Dir;
		$this->lists['order']		= $filter_order;
		$this->lists['search'] = $search;
		$this->lists['kb_cat_id'] = $filter_kb_cat_id;
		$this->lists['prod_id'] = $filter_prod_id;
		$this->lists['ispublished'] = $ispublished;

        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
   }

    function _buildQuery()
    {
 		$db	= JFactory::getDBO();

        $query = ' SELECT k.id, k.title, k.body, k.ordering, k.published, c.title as cattitle, f.filecount, k.rating, k.ratingdetail, k.allprods, k.created, k.modified, k.views, k.access, k.language FROM #__fss_kb_art as k LEFT JOIN #__fss_kb_cat as c ';
        $query .= ' ON k.kb_cat_id = c.id ';
		$query2 = '52b8d6e063f9294e244d42e540facd80';
        $query .= ' LEFT JOIN (SELECT count(*) as filecount, kb_art_id FROM #__fss_kb_attach GROUP BY kb_art_id) as f ON k.id = f.kb_art_id ';

		$where = array();

		if ($this->lists['search']) {
			$where[] = '(LOWER( k.title ) LIKE '.$db->Quote( '%'.FSSJ3Helper::getEscaped($db,  $this->lists['search'], true ).'%', false ) . ')';
		}

		if ($this->lists['order'] == 'k.ordering') {
			$order = ' ORDER BY k.ordering '. $this->lists['order_Dir'];
		} else {
			$order = ' ORDER BY '. $this->lists['order'] .' '. $this->lists['order_Dir'] .', k.ordering';
		}

		if ($this->lists['kb_cat_id'] > 0)
		{
			$where[] = 'kb_cat_id = ' . $this->lists['kb_cat_id'];
		}

		if ($this->lists['prod_id'] > 0)
		{
			$where[] = "allprods = 1 OR k.id IN (SELECT kb_art_id FROM #__fss_kb_art_prod WHERE prod_id = '{$this->lists['prod_id']}')";
		}

		if ($this->lists['ispublished'] > -1)
		{
			$where[] = 'k.published = ' . $this->lists['ispublished'];
		}
		
		FSSAdminHelper::LA_GetFilterState();
		if (FSSAdminHelper::$filter_lang)	
			$where[] = "k.language = '" . FSSJ3Helper::getEscaped($db, FSSAdminHelper::$filter_lang) . "'";
		if (FSSAdminHelper::$filter_access)	
			$where[] = "k.access = '" . FSSJ3Helper::getEscaped($db, FSSAdminHelper::$filter_access) . "'";

 		$where = (count($where) ? ' WHERE '.implode(' AND ', $where) : '');

  		$query .= $where . $order;
 
        return $query;
    }

    function getData()
    {
        // Lets load the data if it doesn't already exist
        if (empty( $this->_data ))
        {
            $query = $this->_buildQuery();
            $this->_data = $this->_getList( $query, $this->getState('limitstart'), $this->getState('limit') );
        }

        return $this->_data;
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

    function getPagination()
    {
        // Load the content if it doesn't already exist
        if (empty($this->_pagination)) {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
        }
        return $this->_pagination;
    }

    function getLists()
    {
		return $this->lists;
	}

}
