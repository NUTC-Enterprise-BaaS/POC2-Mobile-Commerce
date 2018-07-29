<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport('joomla.utilities.date');


class TableMember extends JTable
{

	var $id = null;

	var $groupid;

	function TableMember(& $db) {
		parent::__construct('#__fss_ticket_group_members', 'user_id', $db);
	}

	function check()
	{
		// make published by default and get a new order no
		if (!$this->id)
		{
		}

		return true;
	}

	function delete_member($userid, $groupid)
	{
		if (!$userid)
		{
			$this->setError("No userid specified");
			return false;	
		}

		if (!$groupid)
		{
			$this->setError("No groupid specified");
			return false;	
		}
		// inherited from JTable

		$query = 'DELETE FROM '.$this->_tbl.
				" WHERE user_id = '".FSSJ3Helper::getEscaped($this->_db,$userid)."' AND group_id = '".FSSJ3Helper::getEscaped($this->_db,$groupid)."' ";
		$this->_db->setQuery( $query );
		
		if ($this->_db->query())
		{
			return true;
		}
		else
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}	
		
		// End inherited	
	}
}

