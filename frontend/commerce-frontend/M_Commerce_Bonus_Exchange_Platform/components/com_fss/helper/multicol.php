<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class FSS_Multi_Col_Responsive
{
	var $cur_col = 1;
	var $max_col = 1;
	
	var $started = false;
	var $width = 100;
	
	function Init($cols, $data = array())
	{
		if ($cols < 1) $cols = 1;
		if ($cols > 10) $cols = 10;
		
		$this->cur_col = 1;
		$this->max_col = $cols;
		$this->width = floor(100 / $cols) - 1;
		
		if ($this->max_col == 1)
			return;
		
		//echo "<table class='table table-borderless'>";
		
	}	
	
	function Item()
	{		
		if ($this->max_col == 1)
			return;

			if ($this->started)
			$this->_end_item();
		
		$this->started = true;
		
		if ($this->cur_col == 1)
		{
			//echo "<tr>";
		}
		
		echo "<div style='width: {$this->width}%;' class='fsj_multicol'>";
		//echo "<td width='{$this->width}%' valign='top'>";
	}
	
	private function _end_item()
	{
		//echo "</td>";
		echo "</div>";
		
		if ($this->cur_col >= $this->max_col)
		{
			//echo "</tr>";
			$this->cur_col = 1;	
		} else {
			$this->cur_col ++;	
		}
	}
	
	function End()
	{		
		if ($this->max_col == 1)
			return;

		$this->_end_item();
		
		//echo "</table>";	
	}
}

class FSS_Multi_Col
{
	var $cur_col = 1;
	var $max_col = 1;
	
	var $started = false;
	var $width = 100;
	
	function Init($cols, $data = array())
	{
		if ($cols < 1) $cols = 1;
		if ($cols > 10) $cols = 10;
		
		$this->cur_col = 1;
		$this->max_col = $cols;
		$this->width = floor(100 / $cols);
		
		$this->rows_only = false;
		if (!empty($data['rows_only']))
			$this->rows_only = true;
		
		$this->force_table = false;
		if (!empty($data['force_table']))
			$this->force_table = true;
		
		if ($this->max_col == 1 && !$this->force_table)
			return;
		
		$classes = 'table table-borderless';
		if (!empty($data['class']))
			$classes = $data['class'];
		
		echo "<table class='".$classes."'>";
		
	}	
	
	function Item()
	{		
		if ($this->max_col == 1 && !$this->force_table)
			return;

		if ($this->started)
			$this->_end_item();
		
		$this->started = true;
		
		if ($this->cur_col == 1)
		{
			echo "<tr>";
		}
		
		if (!$this->rows_only)
			echo "<td width='{$this->width}%' valign='top'>";
	}
	
	private function _end_item()
	{
		if (!$this->rows_only)
			echo "</td>";
			
		if ($this->cur_col >= $this->max_col)
		{
			echo "</tr>";
			$this->cur_col = 1;	
		} else {
			$this->cur_col ++;	
		}
	}
	
	function End()
	{		
		if ($this->max_col == 1 && !$this->force_table)
			return;

		$this->_end_item();
		
		echo "</table>";	
	}
}