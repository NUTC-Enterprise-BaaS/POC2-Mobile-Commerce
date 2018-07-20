<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.model' );


class fsssModelticketemails extends JModelLegacy
{
    
    var $_data;

	var $_total = null;

	var $lists = array(0);

	var $_query;
	
	var $_pagination = null;

    function __construct()
	{
        parent::__construct();

        global $option;
		$context = "ticketemail_";
		$mainframe = JFactory::getApplication();
        // Get pagination request variables
        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest($context.'.list.limitstart', 'limitstart', 0, 'int');

 		$search	= $mainframe->getUserStateFromRequest( $context.'search', 'search',	'',	'string' );
		$search	= JString::strtolower($search);
		$filter_order		= $mainframe->getUserStateFromRequest( $context.'filter_order',		'filter_order',		'',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',	'filter_order_Dir',	'',	'word' );
		
		$this->lists['order_Dir']	= $filter_order_Dir;
		$this->lists['order']		= $filter_order;
		$this->lists['search'] = $search;
		
		
		
		$ispublished	= $mainframe->getUserStateFromRequest( $context.'filter_ispublished',	'ispublished',	-1,	'int' );
		$this->lists['ispublished'] = $ispublished;

        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
   }

    function _buildQuery()
    {
		if ($this->_query)
			return $this->_query;
 		$db	= JFactory::getDBO();

        $query = 'SELECT a.id as `id`, a.cronid, a.name as `name`, a.server as `server`, a.type as `type`, a.port as `port`, a.username as `username`, a.password as `password`, a.checkinterval as `checkinterval`, a.newticketsfrom as `newticketsfrom`, a.prod_id as `prod_id`, l1.title as `lf1`, a.dept_id as `dept_id`, l2.title as `lf2`, a.cat_id as `cat_id`, l3.title as `lf3`, a.pri_id as `pri_id`, l4.title as `lf4`, a.handler as `handler`, l5.name as `lf5`, a.usessl as `usessl`, a.usetls as `usetls`, a.validatecert as `validatecert`, a.allow_joomla as `allow_joomla`, a.published
 FROM #__fss_ticket_email AS a
 LEFT JOIN #__fss_prod AS l1 ON a.prod_id = l1.id 
 LEFT JOIN #__fss_ticket_dept AS l2 ON a.dept_id = l2.id 
 LEFT JOIN #__fss_ticket_cat AS l3 ON a.cat_id = l3.id 
 LEFT JOIN #__fss_ticket_pri AS l4 ON a.pri_id = l4.id 
 LEFT JOIN #__users AS l5 ON a.handler = l5.id ';

		$where = array();

        /*if ($this->lists['search']) {
			$where[] = '(LOWER( t.title ) LIKE '.$db->Quote( '%'.FSSJ3Helper::getEscaped($db,  $this->lists['search'], true ).'%', false ) . ')';
		}*/

		$order = "";
		
		if ($this->lists['order']) {
			$order = ' ORDER BY '. $this->lists['order'] .' '. $this->lists['order_Dir'] .'';
		}

		if ($this->lists['ispublished'] > -1)
		{
			$where[] = 'a.published = ' . $this->lists['ispublished'];
		}


  		$where = (count($where) ? ' WHERE '.implode(' AND ', $where) : '');

  		$query .= $where . $order;
  		$this->_query = $query;
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

		    	 		  	   