<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class FSS_Table
{
	static $cols;
	static $curcol;
	
	static function TableOpen()
	{
		FSS_Table::$curcol = 1;
		$fullwidth = "";
		if (FSS_Table::$cols > 1)
		$fullwidth = "width:100% !important;";
		
		echo "<table class='table table-borderless table-condensed table-narrow table-valign' style='min-width:300px;$fullwidth'>";
		
		if (FSS_Table::$cols > 1)
		{
			echo "<tr>";
			for ($i = 0 ; $i < FSS_Table::$cols ; $i++)
			{
				echo "<td colspan='2' width='" . floor(100 / FSS_Table::$cols) . "%'></td>";
			}
			echo "</tr>";
		}
	}

	static function TableClose()
	{
		echo "</table>";
	}

	static function ColStart($class = '')
	{
		if (FSS_Table::$curcol == 1)
		{
			echo "<tr class='$class'>";		
		} else {
			echo "";
		}
		FSS_Table::$curcol++;
	}

	static function ColEnd()
	{
		if (FSS_Table::$curcol > FSS_Table::$cols)
		{
			echo "</tr>";	
			FSS_Table::$curcol = 1;
		} else {
			
		}
	}
	
}