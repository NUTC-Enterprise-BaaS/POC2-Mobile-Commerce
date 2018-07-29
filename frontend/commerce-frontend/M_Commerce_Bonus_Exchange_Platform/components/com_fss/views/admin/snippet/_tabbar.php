<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

if (empty($this->view)) $this->view = FSS_Input::getCmd('view');
if (empty($this->layout)) $this->layout = FSS_Input::getCmd('layout');

?>

<ul class="nav nav-tabs fss_admin_tabbar">

	<?php if (FSS_Permission::auth("fss.handler", "com_fss.support_admin") || FSS_Permission::PermAnyContent()): ?>
		<li class="<?php if ($this->view == "admin") echo "active";?>">
			<a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin' );?>'>
				<img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/support/supportadmin_16.png'>
				<?php echo JText::_("OVERVIEW"); ?>
			</a> 
		</li>
	<?php endif; ?>

	<?php if (FSS_Permission::auth("fss.handler", "com_fss.support_admin")): ?>
		<li class="<?php if ($this->view == "admin_support") echo "active";?>">
			<a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support' );?>'>
				<img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/support/support_16.png'>
				<?php echo JText::_("SA_SUPPORT"); ?>
			</a> 
		</li>
	<?php endif; ?>

	<?php if (FSS_Permission::CanModerate()): ?>
		<li class="<?php if ($this->view == "admin_moderate") echo "active";?>">
			<a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_moderate' );?>'>
				<img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/support/moderate_16.png'>
				<?php echo JText::_("SA_MODERATE"); ?>
			</a>
		</li>
	<?php endif; ?>

	<?php if (FSS_Permission::PermAnyContent()): ?>
		<li class="<?php if ($this->view == "admin_content") echo "active";?>">
			<a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_content' );?>'>
				<img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/support/content_16.png'>
				<?php echo JText::sprintf("SA_CONTENT"); ?>
			</a>
		</li>
	<?php endif; ?>

	<?php if (FSS_Permission::AdminGroups()): ?>
		<li class="<?php if ($this->view == "admin_groups") echo "active";?>">
			<a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_groups' );?>'>
				<img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/support/groups_16.png'>
				<?php echo JText::_("GROUPS"); ?>
			</a>
		</li>
	<?php endif; ?>

	<?php if (FSS_Permission::auth("fss.reports", "com_fss.reports")): ?>
		<li class="<?php if ($this->view == "admin_report") echo "active";?>">
			<a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_report' );?>'>
				<img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/support/report_16.png'>
				<?php echo JText::_("REPORTS"); ?>
			</a>
		</li> 
	<?php endif; ?>

	<?php echo FSS_GUIPlugins::output("adminTabs"); ?>
</ul>
