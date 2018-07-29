<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport('joomla.application.component.model');

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'fields.php');

class FsssModelField extends JModelLegacy
{
	
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
			$query = ' SELECT * FROM #__fss_field WHERE id = '.FSSJ3Helper::getEscaped($this->_db,$this->_id);
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
			$this->_data->description = '';
			$this->_data->type = 'text';
			$this->_data->default = '';
			$this->_data->grouping = '';
			$this->_data->allprods = 1;
			$this->_data->alldepts = 1;
			$this->_data->required = 0;
			$this->_data->permissions = 0;
			$this->_data->published = 1;
			$this->_data->advancedsearch  = 0;
			$this->_data->inlist  = 0;
			$this->_data->basicsearch   = 0;
			$this->_data->ordering = 0;
			$this->_data->ident = 0;
			$this->_data->peruser = 0;
			$this->_data->helptext = '';
			$this->_data->javascript = '';
			$this->_data->adminhide = 0;
			$this->_data->reghide = 0;
			$this->_data->openhide = 0;
			$this->_data->alias = '';
			$this->_data->blankmessage = '';
			$this->_data->access = 1;
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
		
		// sort code for all products
		$db	= JFactory::getDBO();
		$query = "DELETE FROM #__fss_field_prod WHERE field_id = " . FSSJ3Helper::getEscaped($db, $row->id);
		
		$db->setQuery($query);$db->Query();

		// store new product ids
		if (!$row->allprods)
		{
			$query = "SELECT * FROM #__fss_prod ORDER BY title";
			$db->setQuery($query);
			$products = $db->loadObjectList();
			
			foreach ($products as $product)
			{
				$id = $product->id;
				$value = JRequest::getVar( "prod_" . $product->id );
				if ($value != "")
				{
					$query = "INSERT INTO #__fss_field_prod (field_id, prod_id) VALUES (" . FSSJ3Helper::getEscaped($db, $row->id) . "," . FSSJ3Helper::getEscaped($db, $id) . ")";
					$db->setQuery($query);$db->Query();
				}
			}
		}
		
		
		// sort code for all departments
		$query = "DELETE FROM #__fss_field_dept WHERE field_id = " . FSSJ3Helper::getEscaped($db, $row->id);
		
		$db->setQuery($query);$db->Query();

		// store new department ids
		if (!$row->alldepts)
		{
			$query = "SELECT * FROM #__fss_ticket_dept ORDER BY title";
			$db->setQuery($query);
			$products = $db->loadObjectList();
			
			foreach ($products as $product)
			{
				$id = $product->id;
				$value = JRequest::getVar( "dept_" . $product->id );
				if ($value != "")
				{
					$query = "INSERT INTO #__fss_field_dept (field_id, ticket_dept_id) VALUES (" . FSSJ3Helper::getEscaped($db, $row->id) . "," . FSSJ3Helper::getEscaped($db, $id) . ")";
					$db->setQuery($query);$db->Query();					
				}
			}
		}
		
		// sort code for all categories
		$query = "DELETE FROM #__fss_field_values WHERE field_id = " . FSSJ3Helper::getEscaped($db, $row->id);
		$db->setQuery($query);$db->Query();					
		
		if ($row->type == "text")
		{
			$query = "INSERT INTO #__fss_field_values (field_id, value) VALUES (" . FSSJ3Helper::getEscaped($db, $row->id) . ",'text_min=" . FSSJ3Helper::getEscaped($db, JRequest::getInt('text_min',0)) . "')";
			$db->setQuery($query);$db->Query();					
			$query = "INSERT INTO #__fss_field_values (field_id, value) VALUES (" . FSSJ3Helper::getEscaped($db, $row->id) . ",'text_max=" . FSSJ3Helper::getEscaped($db, JRequest::getInt('text_max',60)) . "')";
			$db->setQuery($query);$db->Query();					
			$query = "INSERT INTO #__fss_field_values (field_id, value) VALUES (" . FSSJ3Helper::getEscaped($db, $row->id) . ",'text_size=" . FSSJ3Helper::getEscaped($db, JRequest::getInt('text_size',40)) . "')";
			$db->setQuery($query);$db->Query();					
		} else if ($row->type == "area")
		{
			$query = "INSERT INTO #__fss_field_values (field_id, value) VALUES (" . FSSJ3Helper::getEscaped($db, $row->id) . ",'area_width=" . FSSJ3Helper::getEscaped($db, JRequest::getInt('area_width',60)) . "')";
			$db->setQuery($query);$db->Query();					
			$query = "INSERT INTO #__fss_field_values (field_id, value) VALUES (" . FSSJ3Helper::getEscaped($db, $row->id) . ",'area_height=" . FSSJ3Helper::getEscaped($db, JRequest::getInt('area_height',4)) . "')";
			$db->setQuery($query);$db->Query();					
		} else if ($row->type == "combo")
		{
			$values = explode("\n",JRequest::getVar('combo_values',''));
			$offset = 0;
			foreach ($values as $value)
			{
				$value = trim($value);
				if ($value == '')
					continue;
				
				$query = "INSERT INTO #__fss_field_values (field_id, value) VALUES (" . FSSJ3Helper::getEscaped($db, $row->id) . ",'" . sprintf("%03d",$offset) . "|" . FSSJ3Helper::getEscaped($db, $value) . "')";
				$db->setQuery($query);$db->Query();					
				$offset++;
			}	
		} else if ($row->type == "radio")
		{
			$values = explode("\n",JRequest::getVar('radio_values',''));
			$offset = 0;
			foreach ($values as $value)
			{
				$value = trim($value);
				if ($value == '')
					continue;
				
				$query = "INSERT INTO #__fss_field_values (field_id, value) VALUES (" . FSSJ3Helper::getEscaped($db, $row->id) . ",'" . sprintf("%03d",$offset) . "|" . FSSJ3Helper::getEscaped($db, $value) . "')";
				$db->setQuery($query);$db->Query();					
				$offset++;
			}	
		} else if ($row->type == "plugin")
		{
			$plugin = JRequest::getVar( "plugin" , "");

			$data = "";

			if ($plugin)
			{
				$plo = FSSCF::get_plugin($plugin);
				$data = $plo->SaveSettings();
			}			

			$query = "INSERT INTO #__fss_field_values (field_id, value, data) VALUES (" . FSSJ3Helper::getEscaped($db, $row->id) . ",'plugin=" . FSSJ3Helper::getEscaped($db, $plugin) . "', '" . $db->escape($data) . "')";
			$db->setQuery($query);$db->Query();	
		}
		
		$this->_id = $row->id;
		$this->_data = $row;

		return true;
	}

	function delete()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row = $this->getTable();
		$db = JFactory::getDBO();

		if (count( $cids )) {
			foreach($cids as $cid) {
				if (!$row->delete( $cid )) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
				
				$qry = "DELETE FROM #__fss_field_prod WHERE field_id = '".FSSJ3Helper::getEscaped($db, $cid)."'";
				$db->setQuery($qry);$db->Query();
				$qry = "DELETE FROM #__fss_field_dept WHERE field_id = '".FSSJ3Helper::getEscaped($db, $cid)."'";
				$db->setQuery($qry);$db->Query();
				$qry = "DELETE FROM #__fss_field_values WHERE field_id = '".FSSJ3Helper::getEscaped($db, $cid)."'";
				$db->setQuery($qry);$db->Query();				
				$qry = "DELETE FROM #__fss_ticket_field WHERE field_id = '".FSSJ3Helper::getEscaped($db, $cid)."'";
				$db->setQuery($qry);$db->Query();
			}
		}
		
		return true;
	}
	function changeorder($direction)
	{
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		if (isset( $cid[0] ))
		{
			$row = $this->getTable();
			$row->load( (int) $cid[0] );
			$row->move($direction);

			return true;
		}
		return false;
	}

	function saveorder()
	{
		$cid		= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$order		= JRequest::getVar( 'order', array (0), 'post', 'array' );
		$total		= count($cid);

		JArrayHelper::toInteger($cid, array(0));
		JArrayHelper::toInteger($order, array(0));

		// Instantiate an article table object
		$row = $this->getTable();

		// Update the ordering for items in the cid array
		for ($i = 0; $i < $total; $i ++)
		{
			$row->load( (int) $cid[$i] );
			if ($row->ordering != $order[$i]) {
				$row->ordering = $order[$i];

				if (!$row->store()) {
					JError::raiseError( 500, $db->getErrorMsg() );
					return false;
				}
			}
		}

		$row->reorder();
		$row->reset();

		return true;
	}
	function unpublish()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$table = $this->getTable();

		return $table->publish($cids, 0);
	}

	function publish()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$table = $this->getTable();

		return $table->publish($cids, 1);
	}
}


