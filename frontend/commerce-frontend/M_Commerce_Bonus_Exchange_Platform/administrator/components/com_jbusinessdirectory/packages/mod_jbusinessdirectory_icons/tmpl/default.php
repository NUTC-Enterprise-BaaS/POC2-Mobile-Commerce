<?php
/**
 * @package JBusinessDirectory
 * @author CMSJunkie http://www.cmsjunkie.com
 * @copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

//no direct accees
defined ('_JEXEC') or die ('Resticted aceess');
?>

<div id="jbusinessdirectory-wrap" class="clearfix">
	<div class="dir-box-icon-wrapper">
		<div class="dir-box-icon">
			<a href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=applicationsettings'); ?>">
				<div class="dir-icon">
					<i class="fa dir-icon-cog"></i>
				</div>
				<span><?php echo JText::_('LNG_APPLICATION_SETTINGS'); ?></span>
			</a>
		</div>
	</div>
	<div class="dir-box-icon-wrapper">
		<div class="dir-box-icon">
			<a href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=companies'); ?>">
				<div class="dir-icon">
					<i class="fa dir-icon-tasks"></i>
				</div>
				<span><?php echo JText::_('LNG_MANAGE_COMPANIES'); ?></span>
			</a>
		</div>
	</div>
	<div class="dir-box-icon-wrapper">
		<div class="dir-box-icon">
			<a href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=offers'); ?>">
				<div class="dir-icon">
					<i class="fa dir-icon-certificate"></i>
				</div>
				<span><?php echo JText::_('LNG_MANAGE_OFFERS'); ?></span>
			</a>
		</div>
	</div>
	<div class="dir-box-icon-wrapper">
		<div class="dir-box-icon">
			<a href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=events'); ?>">
				<div class="dir-icon">
					<i class="fa dir-icon-calendar"></i>
				</div>
				<span><?php echo JText::_('LNG_MANAGE_EVENTS'); ?></span>
			</a>
		</div>
	</div>
	<div class="dir-box-icon-wrapper">
		<div class="dir-box-icon">
			<a href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=orders'); ?>">
				<div class="dir-icon">
					<i class="fa dir-icon-shopping-cart"></i>
				</div>
				<span><?php echo JText::_('LNG_MANAGE_ORDERS'); ?></span>
			</a>
		</div>
	</div>
	<div class="dir-box-icon-wrapper">
		<div class="dir-box-icon">
			<a href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=reports'); ?>">
				<div class="dir-icon">
					<i class="fa dir-icon-bar-chart"></i>
				</div>
				<span><?php echo JText::_('LNG_MANAGE_REPORTS'); ?></span>
			</a>
		</div>
	</div>
</div>

