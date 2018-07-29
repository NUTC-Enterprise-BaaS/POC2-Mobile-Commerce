<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php foreach ($this->_data as $this->comment): ?>
	<?php if ($this->comment['itemid'] != $this->onlyitem) continue; ?>
	<?php include $this->tmplpath . DS .'comment.php' ?>
<?php endforeach; ?>

