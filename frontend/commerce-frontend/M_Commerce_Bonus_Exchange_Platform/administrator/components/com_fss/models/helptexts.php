<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.model' );



class FsssModelHelpTexts extends JModelLegacy
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
		$filter_group	= $mainframe->getUserStateFromRequest( $context.'filter_group',	'group',	'',	'string' );
		$ispublished	= $mainframe->getUserStateFromRequest( $context.'filter_ispublished',	'ispublished',	-1,	'int' );
		if (!$filter_order)
			$filter_order = "`description`";

		$this->lists['order_Dir']	= $filter_order_Dir;
		$this->lists['order']		= $filter_order;
		$this->lists['search'] = $search;
		$this->lists['group'] = $filter_group;
		$this->lists['ispublished'] = $ispublished;

        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
   }

    function _buildQuery()
    {
 		$db	= JFactory::getDBO();

        $query = ' SELECT * FROM #__fss_help_text ';
        
		$where = array();

		if ($this->lists['search'])
			{
			$search = array();
			$search[] = '(LOWER( description ) LIKE '.$db->Quote( '%'.FSSJ3Helper::getEscaped($db,  $this->lists['search'], true ).'%', false ) . ')';
			$search[] = '(LOWER( message ) LIKE '.$db->Quote( '%'.FSSJ3Helper::getEscaped($db,  $this->lists['search'], true ).'%', false ) . ')';
			$search[] = '(LOWER( identifier ) LIKE '.$db->Quote( '%'.FSSJ3Helper::getEscaped($db,  $this->lists['search'], true ).'%', false ) . ')';

			$where[] = " ( " . implode(" OR ",$search) . " ) ";
		}

		
		if ($this->lists['group'] != "")
		{
			$where[] = "`group` = '" . $db->escape($this->lists['group']) . "'";
		}

		if ($this->lists['ispublished'] > -1)
		{
			$where[] = 'published = ' . $this->lists['ispublished'];
		}
		
  		$where = (count($where) ? ' WHERE '.implode(' AND ', $where) : '');

		$order_ok = array("group", "description", "identifier", "message", "published");

		if (!in_array($this->lists['order'], $order_ok))
			$this->lists['order'] = "`group`, `description`";

		if ($this->lists['order'] == "group")
			$this->lists['order'] = "`group`";

		$order = ' ORDER BY '. $this->lists['order'] .' '. $this->lists['order_Dir'];

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



