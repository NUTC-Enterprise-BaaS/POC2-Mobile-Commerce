<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.model' );



class FsssModelMainmenus extends JModelLegacy
{
	
	var $_data;

	
	var $_total = null;

	var $lists = array(0);

	
	var $_pagination = null;

	function __construct()
	{
		parent::__construct();

		global $option;
		$mainframe = JFactory::getApplication();

		$context = "mainmenu_";

		// Get pagination request variables
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest($context.'.list.limitstart', 'limitstart', 0, 'int');

		$search	= $mainframe->getUserStateFromRequest( $context.'search', 'search',	'',	'string' );
		$search	= JString::strtolower($search);
		$filter_order		= $mainframe->getUserStateFromRequest( $context.'filter_order',		'filter_order',		'',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',	'filter_order_Dir',	'',	'word' );
		$ispublished	= $mainframe->getUserStateFromRequest( $context.'filter_ispublished',	'ispublished',	-1,	'int' );
		if (!$filter_order)
			$filter_order = "ordering";

		$this->lists['order_Dir']	= $filter_order_Dir;
		$this->lists['order']		= $filter_order;
		$this->lists['search'] = $search;
		$this->lists['ispublished'] = $ispublished;

		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	
	function _buildQuery()
	{
		$db	= JFactory::getDBO();

		$query = ' SELECT * FROM #__fss_main_menu ';

		$where = array();

		if ($this->lists['search']) {
			$where[] = '(LOWER( title ) LIKE '.$db->Quote( '%'.FSSJ3Helper::getEscaped($db,  $this->lists['search'], true ).'%', false ) . ')';
		}

		if ($this->lists['order'] == 'ordering') {
			$order = ' ORDER BY ordering '. $this->lists['order_Dir'];
		} else {
			$order = ' ORDER BY '. $this->lists['order'] .' '. $this->lists['order_Dir'] .', ordering';
		}

		if ($this->lists['ispublished'] > -1)
		{
			$where[] = 'published = ' . $this->lists['ispublished'];
		}
		
		FSSAdminHelper::LA_GetFilterState();
		if (FSSAdminHelper::$filter_lang)	
			$where[] = "language = '" . FSSJ3Helper::getEscaped($db, FSSAdminHelper::$filter_lang) . "'";
		if (FSSAdminHelper::$filter_access)	
			$where[] = "access = '" . FSSJ3Helper::getEscaped($db, FSSAdminHelper::$filter_access) . "'";

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


