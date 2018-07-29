<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport('joomla.application.component.model');



class FsssModelHelpText extends JModelLegacy
{

	function __construct()
	{
		parent::__construct();

		$this->setId(JRequest::getVar('identifier'));
	}

	function setId($id)
	{
		$this->_identifier		= $id;
		$this->_data	= null;
	}

	function &getData()
	{
		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__fss_help_text '.
				'  WHERE identifier = "'.$this->_db->escape($this->_identifier) . '"';
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		return $this->_data;
	}

	function store($data)
	{
		$row = $this->getTable();
		
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		    
		return true;
	}

	function unpublish()
	{
		$identifier = JRequest::getVar( 'identifier' );

		$table = $this->getTable();

		return $table->publish($identifier, 0);
	}

	function publish()
	{
		$identifier = JRequest::getVar( 'identifier' );

		$table = $this->getTable();

		return $table->publish($identifier, 1);
	}
}


