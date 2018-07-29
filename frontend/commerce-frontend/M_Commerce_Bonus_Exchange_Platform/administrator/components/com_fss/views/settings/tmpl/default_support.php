<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
		<style>
	
		.sub_selects input {
			float: left;
			margin-right: 6px !important;
		}
		</style>


<ul class="nav nav-tabs">
	<li class="active">
		<a href="#sup_open"><?php echo JText::_('OPENING_TICKETS'); ?></a>
	</li>	
	<li>
		<a href="#sup_user"><?php echo JText::_('Users'); ?></a>
	</li>	
	<li>
		<a href="#sup_handler"><?php echo JText::_('Handlers'); ?></a>
	</li>	
	<li>
		<a href="#sup_email"><?php echo JText::_('EMails'); ?></a>
	</li>	
	<li>
		<a href="#sup_general"><?php echo JText::_('General'); ?></a>
	</li>
	<li>
		<a href="#sup_visual"><?php echo JText::_('Visual'); ?></a>
	</li>
</ul>

<div class="tab-content">
	<div class="tab-pane" id="sup_general">
		<?php include JPATH_COMPONENT.DS.'views'.DS.'settings'.DS.'tmpl'.DS.'sup_general.php'; ?>
	</div>
	<div class="tab-pane active" id="sup_open">
		<?php include JPATH_COMPONENT.DS.'views'.DS.'settings'.DS.'tmpl'.DS.'sup_open.php'; ?>
	</div>
	<div class="tab-pane" id="sup_user">
		<?php include JPATH_COMPONENT.DS.'views'.DS.'settings'.DS.'tmpl'.DS.'sup_user.php'; ?>
	</div>
	<div class="tab-pane" id="sup_handler">
		<?php include JPATH_COMPONENT.DS.'views'.DS.'settings'.DS.'tmpl'.DS.'sup_handler.php'; ?>
	</div>
	<div class="tab-pane" id="sup_email">
		<?php include JPATH_COMPONENT.DS.'views'.DS.'settings'.DS.'tmpl'.DS.'sup_email.php'; ?>
	</div>
	<div class="tab-pane" id="sup_visual">
		<?php include JPATH_COMPONENT.DS.'views'.DS.'settings'.DS.'tmpl'.DS.'sup_visual.php'; ?>
	</div>
</div>
