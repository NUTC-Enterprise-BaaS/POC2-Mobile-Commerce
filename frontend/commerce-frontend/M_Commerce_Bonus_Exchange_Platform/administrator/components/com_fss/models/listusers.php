<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.model' );


class FsssModellistusers extends JModelLegacy
{
	
	var $_data;

	var $_total = null;

	var $lists = array(0);

	
	var $_pagination = null;

	function __construct()
	{
		parent::__construct();

		global $option;
		$context = "listuser_";
		$mainframe = JFactory::getApplication();
		// Get pagination request variables
		$limit = $mainframe->getUserStateFromRequest($context.'global.list.limit', 'limit', 10, 'int');
		$limitstart = $mainframe->getUserStateFromRequest($context.'.list.limitstart', 'limitstart', 0, 'int');

		$search	= $mainframe->getUserStateFromRequest( $context.'search', 'search',	'',	'string' );
		$search	= JString::strtolower($search);
		$filter_order		= $mainframe->getUserStateFromRequest( $context.'filter_order',		'filter_order',		'',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',	'filter_order_Dir',	'',	'word' );
		
		$this->lists['order_Dir']	= $filter_order_Dir;
		$this->lists['order']		= $filter_order;
		$this->lists['search'] = $search;
		
		
		$filter_gid	= $mainframe->getUserStateFromRequest( $context.'filter_gid',	'gid',	'');
		$this->lists['gid'] = $filter_gid;
		

		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
   }

	function _buildQuery()
	{
		if (!empty($this->_query))
			return $this->_query;
		$db	= JFactory::getDBO();

		
			$query = 'SELECT u.id, u.username, u.name, u.email, g.title as lf1, gm.group_id as gid FROM #__users as u 
				LEFT JOIN #__user_usergroup_map as gm ON u.id = gm.user_id
				LEFT JOIN #__usergroups as g ON gm.group_id = g.id';

		$where = array();

		if ($this->lists['search']) {
			$search = array();
			$search[] = '(LOWER( u.username ) LIKE '.$db->Quote( '%'.FSSJ3Helper::getEscaped($db,  $this->lists['search'], true ).'%', false ) . ')';
			$search[] = '(LOWER( u.name ) LIKE '.$db->Quote( '%'.FSSJ3Helper::getEscaped($db,  $this->lists['search'], true ).'%', false ) . ')';
			$search[] = '(LOWER( u.email ) LIKE '.$db->Quote( '%'.FSSJ3Helper::getEscaped($db,  $this->lists['search'], true ).'%', false ) . ')';

			$where[] = " ( " . implode(" OR ",$search) . " ) ";
		}

		$order = "";
		
		if ($this->lists['order']) {
			$order = ' ORDER BY '. $this->lists['order'] .' '. $this->lists['order_Dir'] .'';
		}

		if ($this->lists['gid'] != '')
		{
			$where[] = 'gm.group_id = "' . $this->lists['gid'] . '"';
		}

		if (JRequest::getVar('tpl') == 'fuser')
		{
			$query .= " LEFT JOIN #__fss_users AS fssu ON u.id = fssu.user_id";
			$where[] = "(rules = '' OR rules IS NULL)";
		}

		$where = (count($where) ? ' WHERE '.implode(' AND ', $where) : '');

		$query .= $where . " GROUP BY username " . $order;
		
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

