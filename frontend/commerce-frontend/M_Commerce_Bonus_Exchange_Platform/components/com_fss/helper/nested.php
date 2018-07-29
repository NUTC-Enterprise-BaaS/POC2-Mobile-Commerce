<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class FSS_Nested_Helper
{
	static $id_field;
	static $par_field;
	static $order_field;
	
	static $data;
	static $result;
	static function BuildNest(&$data, $idfield, $parfield, $orderfield, $level_start = 0)
	{
		self::$id_field = $idfield;
		self::$par_field = $parfield;
		self::$order_field = $orderfield;
		
		self::ArrayObjSort($data, $orderfield);
		self::$data = $data;
		self::$result = array();
		
		$lft = 0;
	
		self::_build_tree(0, $lft, $level_start);
		
		self::ArrayObjSort(self::$result, 'lft');

		return self::$result;
	}	

	static function _build_tree($parent_id, &$lft, $level = 0)
	{
		$idfield = self::$id_field;
		$parfield = self::$par_field;
		$orderfield = self::$order_field;
		
		foreach (self::$data as $item)
		{
			if ($item->$parfield == $parent_id)
			{
				$item->lft = $lft++;
				$item->level = $level;
				
				// need to find all child items here
				//$item->children = self::_build_tree($item->$idfield, $lft, $level + 1);
				self::_build_tree($item->$idfield, $lft, $level + 1);
				
				$item->rgt = $lft++;
				self::$result[] = $item;
			}	
		}	
	}
	
	static function ArrayObjSort(&$array, $field, $dir = '')
	{
		usort($array, array(new FSS_Array_Obj_Sorter($field, $dir), "compare"));
	}

}


if (!class_exists("FSS_Array_Obj_Sorter"))
{
	class FSS_Array_Obj_Sorter
	{
		private $field;
		private $dir;

		function __construct( $field, $dir ) {
			$this->field = $field;
			$this->dir = 0;
			if (substr(strtolower($dir),0,1) == "d")
				$this->dir = 1;
		}

		function compare( $a, $b ) {
			
			// actual compare
			if (!property_exists($a, $this->field))
				return -1;
			
			if (!property_exists($b, $this->field))
				return 1;
			
			$field = $this->field;
			
			if (is_numeric($a->$field))
			{
				if ($this->dir) // desc
					return $a->$field < $b->$field;
				
				return $a->$field > $b->$field;
			}
			
			if ($this->dir) // desc
				return - strcmp($a->$field, $b->$field);	
			
			return strcmp($a->$field, $b->$field);
		}
	}
}
