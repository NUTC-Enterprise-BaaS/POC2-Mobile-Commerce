<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.model' );



class FsssModelFaqs extends JModelLegacy
{
     var $_data;

	var $_total = null;

	var $lists = array(0);

	var $_pagination = null;

    function __construct()
	{
        parent::__construct();

        $mainframe = JFactory::getApplication(); global $option;
		$context = "faqs_";

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
		$filter_faq_cat_id	= $mainframe->getUserStateFromRequest( $context.'filter_faqcatid',	'faq_cat_id',	0,	'int' );
		$ispublished	= $mainframe->getUserStateFromRequest( $context.'filter_ispublished',	'ispublished',	-1,	'int' );
		if (!$filter_order)
			$filter_order = "f.ordering";

		$this->lists['order_Dir']	= $filter_order_Dir;
		$this->lists['order']		= $filter_order;
		$this->lists['search'] = $search;
		$this->lists['faq_cat_id'] = $filter_faq_cat_id;
		$this->lists['ispublished'] = $ispublished;

        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
   }

    function _buildQuery()
    {
 		$db	= JFactory::getDBO();

        $query = ' SELECT f.id as id, question, answer, f.ordering as ordering, f.published as published, c.title as title, f.featured, f.access, f.language FROM #__fss_faq_faq as f LEFT JOIN #__fss_faq_cat as c ';
        $query .= ' ON f.faq_cat_id = c.id ';

		$where = array();

        if ($this->lists['search']) {
			$where[] = '(LOWER( question ) LIKE '.$db->Quote( '%'.FSSJ3Helper::getEscaped($db,  $this->lists['search'], true ).'%', false ) . ')';
		}

		if ($this->lists['order'] == 'f.ordering') {
			$order = ' ORDER BY f.ordering '. $this->lists['order_Dir'];
		} else {
			$order = ' ORDER BY '. $this->lists['order'] .' '. $this->lists['order_Dir'] .', f.ordering';
		}

		if ($this->lists['faq_cat_id'] > 0)
		{
			$where[] = 'faq_cat_id = ' . $this->lists['faq_cat_id'];
		}

		if ($this->lists['ispublished'] > -1)
		{
			$where[] = 'f.published = ' . $this->lists['ispublished'];
		}
		
		FSSAdminHelper::LA_GetFilterState();
		if (FSSAdminHelper::$filter_lang)	
			$where[] = "f.language = '" . FSSJ3Helper::getEscaped($db, FSSAdminHelper::$filter_lang) . "'";
		if (FSSAdminHelper::$filter_access)	
			$where[] = "f.access = '" . FSSJ3Helper::getEscaped($db, FSSAdminHelper::$filter_access) . "'";

  		$where = (count($where) ? ' WHERE '.implode(' AND ', $where) : '');

  		$query .= $where . $order;

		//echo $query . "<br>";

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



