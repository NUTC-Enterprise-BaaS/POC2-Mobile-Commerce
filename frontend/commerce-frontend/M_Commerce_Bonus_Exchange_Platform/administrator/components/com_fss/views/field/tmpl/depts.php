<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<h1><?php echo JText::_("DEPARTMENTS_SELECTED_FOR_FIELD"); ?> <?php echo $this->field->description; ?></h1>
<?php

foreach ($this->departments as $department)
{
		echo "<h3>".$department->title ."</h3>";
}

if (count($this->departments) == 0)
	echo "<h3>None Selected</h3>";
