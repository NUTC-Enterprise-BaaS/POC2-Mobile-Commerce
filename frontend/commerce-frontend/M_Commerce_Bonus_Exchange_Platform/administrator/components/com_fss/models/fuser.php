<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport('joomla.application.component.model');
jimport('joomla.application.component.modeladmin');

class FsssModelFuser extends JModelAdmin
{

	var $_users = null;
	
	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
	}

	function setId($id)
	{
		$this->_id		= $id;
		$this->_data	= null;
	}

	function &getData()
	{
		if (empty( $this->_data )) {
			$query = ' SELECT u.user_id as id, u.*, ' .
				'CONCAT(m.username," (",m.name,")") as name ' .
				' FROM #__fss_users as u ' .
				' LEFT JOIN #__users as m ON u.user_id = m.id '.
				'  WHERE u.user_id = '.FSSJ3Helper::getEscaped($this->_db,$this->_id);
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
			$this->_data->user_id = 0;
			
			$this->name = "";
		}
		return $this->_data;
	}

	function store($data)
	{

		$row = $this->getTable();

		if (!$row->bind($data)) {
			print $this->_db->getErrorMsg();
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if (!$row->store()) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		$this->_id = $row->id;
		$this->_data = $row;

		return true;
	}

	function getUsers()
	{
		if (empty( $this->_users )) 
		{
			$query = "SELECT m.id, CONCAT(m.username,' (',m.name,')') as name, m.email, u.rules FROM #__users as m LEFT JOIN #__fss_users as u ON m.id = u.user_id WHERE u.rules IS NULL OR u.rules = '' ORDER BY m.username";
			$db = JFactory::getDBO();
			$db->setQuery($query);
			$this->_users = $db->loadAssocList();
		}
		return $this->_users;
	}
	
	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm("com_fss.user_perms", 'user_perms', array('control' => 'jform', 'load_data' => $loadData));
		
		return $form;
	}
	
	protected function loadFormData()
	{
		return $this->user;
	}
	
	function delete(&$pks)
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row =& $this->getTable();
		$db = JFactory::getDBO();

		if (count( $cids )) {
			foreach($cids as $cid) {
				$qry = "UPDATE #__fss_users SET rules = '' WHERE user_id = " . (int)$cid;
				$db->setQuery($qry);
				$db->Query();
			}
		}
		
		return true;
	}
	
}


