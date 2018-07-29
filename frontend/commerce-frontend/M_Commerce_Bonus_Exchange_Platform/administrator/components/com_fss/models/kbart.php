<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport('joomla.application.component.model');
jimport('joomla.filesystem.file');


class FsssModelKbart extends JModelLegacy
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
			$query = ' SELECT * FROM #__fss_kb_art '.
					'  WHERE id = '.FSSJ3Helper::getEscaped($this->_db,$this->_id);
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
			$this->_data->title = null;
			$this->_data->body = null;
			$this->_data->ordering = 0;
			$this->_data->published = 1;
			$this->_data->kb_cat_id = 0;
			$this->_data->rating = 0;
			$this->_data->allprods = 1;
			$this->_data->ratingdetail = "0|0|0";
			$this->_data->access = 1;
			$this->_data->language = "*";
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
 		
		// sort code for all products
		$db	= JFactory::getDBO();
		
		$query = "DELETE FROM #__fss_kb_art_prod WHERE kb_art_id = " . FSSJ3Helper::getEscaped($db, $row->id);
		
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
					$query = "INSERT INTO #__fss_kb_art_prod (kb_art_id, prod_id) VALUES (" . FSSJ3Helper::getEscaped($db, $row->id) . "," . FSSJ3Helper::getEscaped($db, $id) . ")";
					$db->setQuery($query);$db->Query();					
				}
			}
		}
		    
		$db->SetQuery("DELETE FROM #__fss_kb_art_related WHERE kb_art_id = " . FSSJ3Helper::getEscaped($db, $row->id) . " OR related_id = " . FSSJ3Helper::getEscaped($db, $row->id));
		$db->Query();
		
		$relart = JRequest::getVar( "relartfield" );
		$bits = explode(",",$relart);
		$pairs = array();
		$id = $row->id;
		foreach($bits as $bit)
		{
			$bit = (int)$bit;
			if ($bit < 1) continue;
			$pairs[] = "( '".FSSJ3Helper::getEscaped($db, $bit)."', '".FSSJ3Helper::getEscaped($db, $id)."')";	
			$pairs[] = "( '".FSSJ3Helper::getEscaped($db, $id)."', '".FSSJ3Helper::getEscaped($db, $bit)."')";	
		}
		
		if (count($pairs) > 0)
		{
			$qry = "INSERT INTO #__fss_kb_art_related (kb_art_id, related_id) VALUES";
			$qry .= implode(", ", $pairs);
		
			$db->setQuery($qry);$db->Query();
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
				
				$qry = "DELETE FROM #__fss_kb_art_prod WHERE kb_art_id = '".FSSJ3Helper::getEscaped($db, $cid)."'";
				$db->setQuery($qry);$db->Query();
				
				$db->SetQuery("DELETE FROM #__fss_kb_art_related WHERE kb_art_id = '".FSSJ3Helper::getEscaped($db, $cid)."' OR related_id = '".FSSJ3Helper::getEscaped($db, $cid)."'");
				$db->Query();

				$qry = "SELECT * FROM #__fss_kb_attach WHERE kb_art_id = '".FSSJ3Helper::getEscaped($db, $cid)."'";
				$db->setQuery($qry);
				$files = $db->loadAssocList();

				foreach ($files as $file)
				{
					$destname = JPATH_SITE.DS.FSS_Helper::getAttachLocation().DS.'kb'.DS.$file['diskfile'];    
					if (JFile::exists($destname))
					{
						JFile::delete($destname);	
					}
				}
				
			}
		}
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
}


